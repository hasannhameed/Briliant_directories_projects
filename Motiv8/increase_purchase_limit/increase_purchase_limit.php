<?php
// Read inputs from GET (because your JS sends method: GET)
$packageLimit = isset($_GET['package_limit']) ? trim($_GET['package_limit']) : '';
$userId       = isset($_GET['user_id']) ? (int) $_GET['user_id'] : '';
$postId       = isset($_GET['post_id']) ? (int) $_GET['post_id'] : '';



 $query = "UPDATE supplier_registration_form SET package_limit = '$packageLimit' WHERE user_id = $userId AND event_id = $postId";
   

$result = mysql_query($query);
if($result){
	 $affected = mysql_affected_rows();
    if ($affected > 0) {
        echo json_encode(['ok' => true, 'message' => 'Updated successfully']);
    } else {
        echo json_encode(
            [
            'ok' => false, 
            'message' => 'No matching row found',
            'query' => $query
        ]);
    }
} else {
    echo json_encode(['ok' => false, 'message' => mysql_error()]);
}




?>
