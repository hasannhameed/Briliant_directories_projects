<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = isset($_POST['uid']) ? intval($_POST['uid']) : null;

    if (empty($uid)) {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
        exit;
    }
	// Fetch supplier information
    $supplierQuery = "SELECT first_name, last_name, company, email, user_id FROM users_data WHERE user_id = '" . mysql_real_escape_string($uid) . "'";

    $supplierResult = mysql_query($supplierQuery);

    if (!$supplierResult) {
        echo json_encode(['success' => false, 'message' => 'Error fetching supplier info: ' . mysql_error()]);
        exit;
    }

    $supplierData = mysql_fetch_assoc($supplierResult);

    if (!$supplierData) {
        echo json_encode(['success' => false, 'message' => 'No supplier info found']);
        exit;
    }

    // Query to fetch events for the given user ID
    $query = "SELECT 
                lep.id,
                lep.user_id,
                lep.post_id AS lep_post_id,
                lep.staus,
                lep.start_date,
				lep.end_date,
				lep.start_time,
				lep.end_time,
                lep.video_option,
                dp.post_id,
                dp.data_id,
                dp.user_id AS dp_user_id,
                dp.post_status,
                dp.post_filename,
                dp.post_title,
                dp.post_live_date,
                dp.revision_timestamp,
                dp.post_start_date,
                dp.post_expire_date
            FROM 
                live_events_posts AS lep
            LEFT JOIN 
                data_posts AS dp 
            ON 
                lep.post_id = dp.post_id
            WHERE 
                lep.user_id = " . $uid . " 
                AND dp.data_id = 73
			ORDER BY 
				(lep.start_date < NOW()) ASC, 
    ABS(DATEDIFF(lep.start_date, NOW())) ASC;
            ";
			//lep.start_date DESC
			//ABS(DATEDIFF(lep.start_date, NOW())) ASC
    $result = mysql_query($query);

    if ($result && mysql_num_rows($result) > 0) {
        $events = [];

        while ($row = mysql_fetch_assoc($result)) {
            $get_start_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $row['post_id'] . " AND `key` = 'start_time'");
            $get_start_time = mysql_fetch_assoc($get_start_time_sql);
            $start_time = $get_start_time['value'];
            $get_end_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $row['post_id'] . " AND `key` = 'end_time'");
            $get_end_time = mysql_fetch_assoc($get_end_time_sql);
            $end_time = $get_end_time['value'];
            $postUrl = 'https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=' . $row['post_id'];
			$packages = mysql_fetch_assoc(mysql_query("SELECT packages_section FROM `supplier_registration_form` WHERE user_id = " . $row['user_id'] . " AND event_id = " . $row['post_id']));
			$video_option = ($packages && $packages['packages_section'] == 'SuperBooth Package') ? 'superbooth' : $row['video_option'];
			$start_datetime = strtotime($row['start_date'] . ' ' . $row['start_time']);
            $end_datetime = strtotime($row['end_date'] . ' ' . $row['end_time']);
            $current_time = time();
			$eventType = '';
			if ($current_time < $start_datetime) {
				$eventType = 'Upcoming';
			} elseif ($current_time > $end_datetime) {
				$eventType = 'Past';
			} else {
				$eventType = ' ';
			}
			
			$labels = [];
			$labelQuery = mysql_query("
				SELECT text_label, color_code 
				FROM supplier_labels 
				WHERE user_id = " . intval($row['user_id']) . "
				  AND post_id = " . intval($row['lep_post_id'])
			);

			while ($labelRow = mysql_fetch_assoc($labelQuery)) {
				if (!empty(trim($labelRow['text_label']))) {
					$labels[] = [
						'text_label' => htmlspecialchars($labelRow['text_label']),
						'color_code' => htmlspecialchars($labelRow['color_code'])
					];
				}
			}

			
            $events[] = [
                'title' => $row['post_title'],
                'start_time' => date('d/m/Y h:i a', $start_datetime),
                'end_time' => date('d/m/Y h:i a', $end_datetime),
                'status' => $row['staus'],
                'video_option' => $video_option,
				'event_type' => $eventType,
                'link' => $postUrl,
				'labels' => $labels
            ];
        }
		
		$response = [
        'success' => true,
        'supplier' => [
            'name' => htmlspecialchars(trim($supplierData['first_name'] . ' ' . $supplierData['last_name'])),
            'company' => htmlspecialchars($supplierData['company']),
            'email' => htmlspecialchars($supplierData['email']),
            'user_id' => $supplierData['user_id']
        ],
        'data' => $events,
			'sql' =>  $query,
             'status' => $row['staus'],
                'video_option' => $video_option,
				'event_type' => $eventType,
			'labels' => $labels
    ];

		echo json_encode($response);
        //echo json_encode(['success' => true, 'data' => $events]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No events found.'. mysql_error()]);
    }
}
?>
