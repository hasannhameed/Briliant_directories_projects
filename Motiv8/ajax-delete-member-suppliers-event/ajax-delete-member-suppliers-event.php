<?php
global $sess; 
$loggedname = $_GET['name']; // admin name 
$loggeduser = $_GET['useremail']; // admin user 
$userid = $_GET['id'];
$admin  = $_GET['admin'];

if ($userid != "") {

    function activity_log($action, $reference_table, $reference_id, $description = '') {
        $user_id = isset($_COOKIE['userid']) ? intval($_COOKIE['userid']) : 0;
        $user_name = isset($user_full_name) ? mysql_real_escape_string($user_full_name) : (isset($user_data['company']) ? $user_data['company'] : $user_data['email']);

        $action            = mysql_real_escape_string($action);
        $reference_table   = mysql_real_escape_string($reference_table);
        $reference_id      = intval($reference_id);
        $description       = mysql_real_escape_string($description);
        $ip                = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
        $agent             = isset($_SERVER['HTTP_USER_AGENT']) ? mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']) : 'unknown';
        $now               = date('Y-m-d H:i:s');
        $sql = "INSERT INTO activity_log 
                (user_id, user_name,user_type, `action`, reference_table, reference_id, `description`, ip_address, user_agent, created_at)
                VALUES 
                ('$user_id', '$user_name', 'Admin', '$action', '$reference_table', '$reference_id', '$description', '$ip', '$agent', '$now')
                ";

        mysql_query($sql);
    }
    // Delete the user from supplier_attendingstaffs based on user_id 

    $staffQuery = mysql_query("DELETE FROM `supplier_attendingstaffs` WHERE user_id = $userid ");

    // Correcting the UPDATE query to remove $userid from the staff_id list in supplier_attendingstaffs
    $deleteStaffIdQuery = mysql_query("
        UPDATE `supplier_attendingstaffs`
        SET `staff_ids` = 
            CASE
                WHEN FIND_IN_SET('$userid', `staff_ids`) > 0 THEN
                    TRIM(
                        BOTH ',' FROM 
                        REPLACE(
                            CONCAT(',', `staff_ids`, ','), 
                            CONCAT(',', '$userid', ','), 
                            ','
                        )
                    )
                ELSE `staff_ids`
            END
        WHERE FIND_IN_SET('$userid', `staff_ids`) > 0
    ");
    
    // $delete_post = mysql_query("DELETE FROM `live_events_posts` WHERE user_id=$userid");
    $supplier_registration = mysql_query("DELETE FROM `supplier_registration_form` WHERE user_id=$userid");
    $supplier_labels = mysql_query("DELETE FROM `supplier_labels` WHERE user_id=$userid");
    $lep = mysql_query("DELETE FROM `live_events_posts` WHERE user_id=$userid");
    $registration_form = mysql_query("DELETE FROM `preregistation_form` WHERE user_id=$userid");
    $event_enquire = mysql_query("DELETE FROM `event_enquire` WHERE user_id=$userid");
    $supplier_comments = mysql_query("DELETE FROM `supplier_comments` WHERE user_id=$userid");
    $deleteattendesquery = mysql_query("DELETE FROM `attending_staff_attendance` WHERE user_id=$userid");
    $usersdatasquery = mysql_query("DELETE FROM `users_data` WHERE user_id=$userid");
    $usersphotoquery = mysql_query("DELETE FROM `users_photo` WHERE user_id=$userid");
    $datapostsquery = mysql_query("DELETE FROM `data_posts` WHERE user_id=$userid");
    $usersportfoliogroups = mysql_query("DELETE FROM `users_portfolio_groups` WHERE user_id=$userid");
    $usersportfolio = mysql_query("DELETE FROM `users_portfolio` WHERE user_id=$userid");
    $metadata = mysql_query("DELETE FROM `users_meta` WHERE `database`='users_data' AND database_id=$userid");
	$delete_log = mysql_query("INSERT INTO log_delete (loggedname, loggeduser, delete_type, deleted_id) VALUES ('$loggedname', '$loggeduser','member_delete','$userid')");
		echo "INSERT INTO log_delete (loggedname, loggeduser, delete_type) VALUES ('$loggedname', '$loggeduser','member_delete')";
    // Check if all queries were successful
  
    activity_log('DELETED', 'users_data and all table data', $userid, 'All user data deleted by' . $admin);
    
}
?>
