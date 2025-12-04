<?php
// $event_id = $row['post_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier_id = intval($_POST['supplier_id']);
    $event_id = intval($_POST['event_id']);

    if ($supplier_id > 0 && $event_id > 0) {
        echo $updateSql = "UPDATE supplier_registration_form SET limit_reached = 1 WHERE user_id = $supplier_id AND event_id = $event_id";
        // $updateSql = "UPDATE supplier_registration_form SET limit_reached = 1 WHERE user_id = $supplier_id AND event_id = $event_id";
		
        if (mysql_query($updateSql)) {
            echo "success"; 
        } else {
            echo "error"; 
        }
    } else {
        echo "error"; 
    }
}
?>