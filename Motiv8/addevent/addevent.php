<?php 
$data = json_decode(file_get_contents('php://input'), true);


$action     = mysql_real_escape_string($data['action']);
$user_id    = mysql_real_escape_string($data['userId']);
$actionData = mysql_real_escape_string($data['actionData']);
$status     = mysql_real_escape_string($data['status']);
$dueDate    = mysql_real_escape_string($data['dueDate']);
$content    = mysql_real_escape_string($data['content']);
$event_name = mysql_real_escape_string($data['event_name']);
$event_id   = mysql_real_escape_string($data['eventId']);
$id         = mysql_real_escape_string($data['id']);


if ($action == 'add') {
    $sql = "INSERT INTO event_schedule(priority, event_id, event_name, action_no, action_by, assigned_to, action_title, status, due_date, reminder, content, manage) VALUES (0, '$event_id', '$event_name', UNIX_TIMESTAMP(), '$user_id', NULL, '$actionData', '$status' , " . (($dueDate === null || $dueDate === '') ? "NULL" : "'$dueDate'") . ", NULL, " . ($content === null ? "NULL" : "'$content'") . ", NULL)";
    $exc = mysql_query($sql);
    if(!$exc){
         echo json_encode(['status'=>'Failed','action' => 'add','sql'=>$sql,'error'=>mysql_error()]);
    }else{
         echo json_encode(['status'=>'Success','action' => 'add','sql'=>$sql]);
    }
   

} else if ($action == 'edit') {
    $sql = "UPDATE event_schedule SET  event_id ='$event_id', event_name = '$event_name',action_no = UNIX_TIMESTAMP(),action_by = '$user_id',action_title = '$actionData',status =  '$status',due_date = " . (($dueDate === null || $dueDate === '') ? "NULL" : "'$dueDate'") . ",content = " . ($content === null ? "NULL" : "'$content'") . " WHERE id = $id LIMIT 1";

    
    $exc = mysql_query($sql);
    if(!$exc){
         echo json_encode([
            'status'=>'Failed',
            'action' => 'edit',
            'sql'=>$sql,
            'error'=>mysql_error()
        ]);
    }else{
         echo json_encode(['status'=>'Success','action' => 'edit','sql'=>$sql]);
    }

} else if ($action == 'delete') {
   $sql = "DELETE FROM event_schedule WHERE id = $id LIMIT 1";

   $exc = mysql_query($sql);
    if(!$exc){
         echo json_encode(['status'=>'Failed','action' => 'delete','sql'=>$sql]);
    }else{
         echo json_encode(['status'=>'Success','action' => 'delete','sql'=>$sql]);
    }

} else if ($action == 'order') {
    unset($data['action']);
    
    $all_success = true;

    foreach ($data as $id => $priority) {
        $id = mysql_real_escape_string($id);
        $priority = mysql_real_escape_string($priority);
        
        $sql = "UPDATE event_schedule SET priority = '$priority' WHERE id = '$id' LIMIT 1";
        $exc = mysql_query($sql);
        
        if (!$exc) {
            $all_success = false;
        }
    }

    if ($all_success) {
        echo json_encode([
            'status' => 'Success',
            'action' => 'order',
            'message' => 'Task order updated successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'Failed',
            'action' => 'order',
            'message' => 'Some updates failed.',
            'error' => mysql_error()
        ]);
    }
} else if ($action == 'fetch') {
    $sql = "SELECT * FROM event_schedule WHERE 1"
        . ($event_id !== '' ? " AND event_name = '$event_id'" : "")
        . (isset($status) ? " AND status = " . (int)$status : "")
        . " ORDER BY id DESC LIMIT 500";

    
    $exc = mysql_query($sql);
    if(!$exc){
         echo json_encode(['status'=>'Failed','action' => 'fetch','sql'=>$sql]);
    }else{
         echo json_encode(['status'=>'Success','action' => 'fetch','sql'=>$sql]);
    }

} else {
    echo json_encode(['action' => 'not defined']);
}
?>
