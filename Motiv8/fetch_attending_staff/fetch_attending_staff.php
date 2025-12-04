<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


if (isset($_POST['post_id'], $_POST['supplier_id'])) {
    $postId = intval($_POST['post_id']);
    $supplierId = intval($_POST['supplier_id']);

    $query = "
        SELECT a.id, a.supplier_id, a.staff_id, a.status, a.staff_email, 
               CONCAT(u.first_name, ' ', u.last_name) AS name
        FROM attending_staff_attendance a
        LEFT JOIN users_data u ON a.staff_id = u.user_id
        WHERE a.post_id = '$postId' 
        AND a.supplier_id = '$supplierId'
    ";
    
    $result = mysql_query($query);
    $attendingStaff = [];

    while ($staff = mysql_fetch_assoc($result)) {
        $attendingStaff[] = $staff;
    }

    echo json_encode(['success' => true, 'data' => $attendingStaff]);
} else {
    echo json_encode(['success' => false, 'message' => 'Missing post_id or supplier_id']);
}
?>
