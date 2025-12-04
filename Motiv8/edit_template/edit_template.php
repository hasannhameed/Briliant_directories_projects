<?php
/**
 * Event Template Manager API 
 * ---------------------------------------------
 * precise, edge-case safe.
 */

header('Content-Type: application/json');

/* ---------------- Helpers ---------------- */
function mysql_fail($msg = 'Database error') {
    echo json_encode(['status'=>'error','message'=>$msg,'error'=>mysql_error()]);
    exit;
}
function esc($s) { return mysql_real_escape_string($s); }
function is_valid_date($s) {
    if ($s === '0000-00-00') return true;
    return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $s);
}

/* ---------------- Input ---------------- */
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data) || !isset($data['action'])) {
    echo json_encode(['status'=>'error','message'=>'Invalid request']); exit;
}
$action = $data['action'];

/* ---------------- Actions ---------------- */

if ($action === 'update') {
    if (empty($data['id'])) {
        echo json_encode(['status'=>'error','message'=>'id is required']); exit;
    }

    $id = (int)$data['id'];
    if ($id <= 0) {
        echo json_encode(['status'=>'error','message'=>'Invalid id']); exit;
    }

    $sets = [];
    if (isset($data['userId']))     $sets[] = "action_by = " . (int)$data['userId'];
    if (isset($data['actionData'])) $sets[] = "action_title = '" . esc($data['actionData']) . "'";

    if (empty($sets)) {
        echo json_encode(['status'=>'error','message'=>'No fields to update']); exit;
    }

    // Build the SQL query
    $sql = "UPDATE event_template_actions SET " . implode(', ', $sets) . " WHERE id = {$id} LIMIT 1";

    if (!mysql_query($sql)) {
        echo json_encode(['status'=>'error','message'=>'Failed to update template actions']); exit;
    }

    echo json_encode([
        'status'  => 'success',
        'message' => 'Template action updated successfully',
        'id'      => $id
    ]);
}elseif($action === 'add'){

    $actionData = $data['actionData'];
    $userId     = $data['userId'];
    $eventId    = $data['eventId'];

    $result     = mysql_query('SELECT COUNT(*) AS cnt FROM event_template_actions');
    $row        = mysql_fetch_assoc($result);
    $priority   = ((int) $row['cnt']) + 1;

    $check_status = mysql_query("
            INSERT INTO event_template_actions 
                (event_id, action_by, action_title, priority) 
            VALUES 
                ('{$eventId}', '{$userId}', '".mysql_real_escape_string($actionData)."', '{$priority}')
            ");
    if ($check_status) {
        $inserted_id = mysql_insert_id();
        echo json_encode([
            'message'      => 'Successfully template assigned',
            'status'       => 'success',
            'inserted_id'  => $inserted_id,
            'query'        => $sql
        ]);
    } else {
        $error_msg = mysql_error();
        echo json_encode([
            'message' => 'Error assigning template: ' . $error_msg,
            'status'  => 'error',
            'query'   => $sql
        ]);
    }


} elseif($data['action'] === 'delete') {
    $id          = (int) $data['id'];

    if ($id > 0 ) {
        $deleteQuery = "DELETE FROM event_template_actions WHERE id = ".$id;
        $result = mysql_query($deleteQuery);

        if ($result) {
            echo json_encode([
                'status'  => 'success',
                'message' => "Action ID {$id} deleted from template."
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'SQL delete failed: ' . mysql_error()
            ]);
        }
    } else {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Invalid id or template_id.'
        ]);
    }
} elseif($action === 'assignTemplate') {
    $event_id    = $data['eventId'];
    $check_string = "SELECT post_id, post_start_date FROM `data_posts` WHERE data_id = 73 AND post_start_date >= CURDATE() AND post_id = $event_id";
    $check_query = mysql_query($check_string);

    if(mysql_num_rows($check_query)<1){
        echo json_encode([
            'message'=>'Invalid Event Id',
            'status' => 'error',
            'query' => $check_string
        ]);
    }
    // Ordered list of actions (from your file), in exact order
    // Fetch ordered actions + user mapping directly from template table
    $ordered_actions = array();
    $action_user_map = array();

    $template_query = mysql_query("
        SELECT priority, action_title, action_by 
        FROM event_template_actions 
        WHERE event_id = 0 
        ORDER BY priority ASC
    ");

    while ($template = mysql_fetch_assoc($template_query)) {
        $ordered_actions[] = $template['action_title'];
        $action_user_map[$template['action_title']] = (int)$template['action_by'];
    }

    // Specific due dates (in weeks before)
    $specific_due_dates = array(
        'Banner Overlays Complete' => 5,
        'Flyers Complete' => 2,
        'Banners / Banner Overlays sent to / taken to OEM' => 5,
        'All Suppliers Paid (1 Month Before Event Date)' => 4,
        'Banner Overlays In Manufacturer' => 4,
        'Flyers In Manufacturer' => 1,
        'TV’s / Showreels @ Manufacturer' => 5,
    );

    while ($rows_check = mysql_fetch_assoc($check_query)) {
        $event_id = $rows_check['post_id'];
        $post_start_date = $rows_check['post_start_date'];

        $evenet_rows_count = mysql_query("SELECT COUNT(*) AS count FROM `event_schedule` WHERE event_id = '$event_id'");
        $row_count = mysql_fetch_row($evenet_rows_count);

        if ($row_count[0] < 1) {
            echo 'Assigning Event data to New Event... failed temporary<br>';
            //return;
            //echo "SELECT COUNT(*) AS count FROM `event_schedule` WHERE event_id = '$event_id'";

            foreach ($ordered_actions as $action_title) {
                $user_id = isset($action_user_map[$action_title]) ? $action_user_map[$action_title] : 0;

                if (isset($specific_due_dates[$action_title])) {
                    $weeks_before = $specific_due_dates[$action_title];
                    $due_date = date('Y-m-d', strtotime("-$weeks_before weeks", strtotime($post_start_date)));
                } else {
                    $due_date = '0000-00-00';
                }

                $sql = "INSERT INTO `event_schedule` (event_id, action_title, due_date, status, action_by)
                        VALUES ('$event_id', '$action_title', '$due_date', 'Not Started', '$user_id')";
                $query  = mysql_query($sql);
                if(!$query){
                    echo mysql_error();
                }
            }
        }
        
        $query = 'SELECT COUNT(*) AS cnt FROM event_schedule WHERE event_id = '.$event_id;
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $count = (int) $row['cnt'];  // get the count as integer

        if ($count > 1) {
            echo json_encode([
                'message' => 'Successfully template assigned',
                'status'  => 'success',
                'query'   => $query
            ]);
        }

    }
} elseif ($action === 'event_data') {
    /**
     * Fetches template actions from event_template_actions for a given event_id (or global if 0).
     */
    // if (empty($data['event_id'])) {
    //     echo json_encode(['status'=>'error','message'=>'event_id is required']); exit;
    // }

    $event_id = (int)$data['event_id'];

    $q = mysql_query("SELECT * FROM event_template_actions WHERE 1=1 ORDER BY priority ASC");
    if (!$q) mysql_fail('Failed to fetch event template actions');

    $items = [];
    while ($row = mysql_fetch_assoc($q)) {
        $items[] = [
            'id'          => (int)$row['id'],
            'priority'    => (int)($row['priority'] ?? 0),
            'event_id'    => (int)$row['event_id'],
            'action_title'=> $row['action_title'] ?? null,
            'action_by'   => (int)($row['action_by'] ?? 0),
        ];
    }

    echo json_encode([
        'status'   => 'success',
        'event_id' => $event_id,
        'data'     => $items
    ]);
} elseif ($action === 'templates') {

    $q = mysql_query("SELECT template_id, template_name FROM event_templates ORDER BY template_id ASC");
    if (!$q) mysql_fail('Failed to fetch templates');

    $templates = [];
    while ($row = mysql_fetch_assoc($q)) {
        $templates[] = [
            'template_id'   => (int)$row['template_id'],
            'template_name' => $row['template_name']
        ];
    }

    echo json_encode(['status'=>'success','templates'=>$templates]);

} elseif ($action === 'template_data') {

    if (empty($data['template_id'])) {
        echo json_encode(['status'=>'error','message'=>'template_id is required']); exit;
    }
    $tid = (int)$data['template_id'];
    $q = mysql_query("SELECT id, priority, action_title, action_by 
                      FROM event_template_actions ");
    if (!$q) mysql_fail('Failed to fetch template actions');

    $items = [];

    while ($r = mysql_fetch_assoc($q)) {
        $items[] = [
            'id'          => (int)$r['id'],
            'priority'    => (int)$r['priority'],
            'action_title'=> $r['action_title'],
            'action_by'   => (int)$r['action_by']
        ];
    }

    echo json_encode(['status'=>'success','template_id'=>$tid,'data'=>$items]);

}elseif ($action === 'users') {

    $q = mysql_query("SELECT user_id, first_name, last_name, company 
                      FROM `users_data` 
                      WHERE active = 2 AND subscription_id = 4");
    if (!$q) mysql_fail('Failed to fetch users');

    $users = [];
    while ($u = mysql_fetch_assoc($q)) {

        $photo_query = mysql_query("SELECT file FROM users_photo WHERE user_id = " . $u['user_id'] . " AND type = 'photo' LIMIT 1");
        $photo_data = mysql_fetch_assoc($photo_query);

        if (!empty($photo_data['file'])) {
            $img_file = $photo_data['file'];
            $img_url = "https://www.motiv8search.com/pictures/profile/" . $img_file;
        } else {
            $logo_query = mysql_query("SELECT file FROM users_photo WHERE user_id = " . $u['user_id'] . " AND type = 'logo' LIMIT 1");
            $logo_data = mysql_fetch_assoc($logo_query);

            if (!empty($logo_data['file'])) {
                $img_file = $logo_data['file'];
                $img_url = "https://www.motiv8search.com/logos/profile/" . $img_file;
            } else {
                $img_url = "https://www.motiv8search.com/images/profile-profile-holder.png";
            }
        }

        $name = trim($u['first_name'] . ' ' . $u['last_name']);
        if ($name === '') $name = trim($u['company']);
        $users[] = [
            'user_id' => (int)$u['user_id'],
            'name'    => $name,
            'url'     => $img_url,
        ];
    }

    echo json_encode(['status' => 'success', 'users' => $users]);

} elseif ($action === 'events') {

    $q = mysql_query("
        SELECT p.post_id, p.post_title, p.post_filename
        FROM data_posts p
        WHERE p.data_id = 73
        AND p.post_start_date >= CURDATE()
        AND p.post_id NOT IN (
            SELECT DISTINCT event_id FROM event_schedule
        )
        ORDER BY p.post_start_date ASC
    ");

    if (!$q) mysql_fail('Failed to fetch events');

    // Clean event name

    function extract_event_name($event_title) {
        //preg_match('/^(.*?)(?=\s*-*\s*\d|$)/', $event_title, $matches);
        //return trim($matches[1]);
        return $event_title;
    }

    // function extract_event_name($event_title) {
    //     preg_match('/^(.*?)(?=\s*-*\s*\d|$)/', $event_title, $m);
    //     return trim(isset($m[1]) ? $m[1] : $event_title);
    // }

    $events = [];

    while ($row = mysql_fetch_assoc($q)) {
        $events[] = [
            'post_id'       => (int)$row['post_id'],
            'eventName'     => extract_event_name($row['post_title']),
            'post_filename' => $row['post_filename'],
        ];
    }

    if (empty($events)) {
        $events[] = [
            'post_id'       => 0,
            'eventName'     => 'No upcoming events without a template.',
            'post_filename' => '#',
        ];
    }

    echo json_encode([
        'status' => 'success',
        'events' => $events
    ]);
    

}elseif ($action === 'delete_template') {
    $templateId = (int)($data['template_id'] ?? 0);
    if ($templateId <= 0) {
        echo json_encode(['status'=>'error','message'=>'Invalid template id']); 
        exit;
    }

    $sql0 = "UPDATE event_schedule SET template_id = NULL WHERE template_id = $templateId";
    if (!mysql_query($sql0)) mysql_fail('Failed to unlink schedules from template', $sql0);

    $sql1 = "DELETE FROM event_template_actions WHERE template_id = $templateId";
    if (!mysql_query($sql1)) mysql_fail('Failed to delete related actions', $sql1);

    $sql2 = "DELETE FROM event_templates WHERE template_id = $templateId";
    if (!mysql_query($sql2)) mysql_fail('Failed to delete template', $sql2);

    echo json_encode([
        'status'  => 'success',
        'message' => 'Template deleted and schedules unlinked',
        'template_id' => $templateId
    ]);
} elseif ($action === 'order') {
    /**
     * Update order (priority) for BOTH event_schedule AND event_template_actions
     * Payload: { action:"order", event_id:123, template_id:1, "3155":1, "3156":2, ... }
     */
    if (empty($data['event_id']) && empty($data['template_id'])) {
        echo json_encode(['status'=>'error','message'=>'event_id or template_id required']); exit;
    }
    $event_id    = isset($data['event_id']) ? (int)$data['event_id'] : 0;
    $template_id = isset($data['template_id']) ? (int)$data['template_id'] : 0;

    $orderMap = $data;
    unset($orderMap['action'],$orderMap['event_id'],$orderMap['template_id']);

    if (empty($orderMap)) { echo json_encode(['status'=>'error','message'=>'No order data provided']); exit; }

    $success = true;
    foreach ($orderMap as $rid=>$priority) {
        $id = (int)$rid; 
        $priority = (int)$priority;
        if ($id > 0) {
            if ($event_id > 0) {
                $sql = "UPDATE event_schedule SET priority=$priority, template_id=$template_id WHERE id=$id AND event_id=$event_id";
                if (!mysql_query($sql)) $success=false;
            }
            if ($template_id > 0) {
                $sql2 = "UPDATE event_template_actions SET priority=$priority WHERE id=$id AND template_id=$template_id";
                mysql_query($sql2); // ignore fail
            }
        }
    }

    if ($success) echo json_encode(['status'=>'success','message'=>'Order updated']);
    else mysql_fail('Failed to update order');

} else {
    echo json_encode(['status'=>'error','message'=>'Unknown action']);
}
?>