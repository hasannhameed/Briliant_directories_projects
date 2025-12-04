<?php 
global $sess; 
$loggedname = $_POST['member_name']; // admin name 
$loggeduser = $_POST['member_email']; // admin user 
 $supplier_id = $_POST['supplier_id'];
 $post_id = $_POST['post_id'];
 $staffid = $_POST['staff_id'];
    $AttendingattendencedeleteQuery = "DELETE FROM attending_staff_attendance WHERE post_id='$post_id' AND supplier_id='$supplier_id' AND staff_id=' $staffid'";
 $AttendingregistrationdeleteQuery = "DELETE FROM attending_supplier_staff_registration WHERE post_id='$post_id' AND supplier_id='$supplier_id' AND user_id=' $staffid'";
    $delete_log = mysql_query("INSERT INTO log_delete (loggedname, loggeduser, delete_type, deleted_id) 
                               VALUES ('$loggedname', '$loggeduser', 'Attendee delete from Event management plugin', '$staffid')");
    if (mysql_query($AttendingattendencedeleteQuery) === TRUE &&  mysql_query($AttendingregistrationdeleteQuery) === TRUE) {
		
    } else {
      echo "Error deleting data: " . mysql_error();
    }

?>