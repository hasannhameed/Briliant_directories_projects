<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = isset($_POST['uid']) ? trim($_POST['uid']) : null;
	$postID = isset($_POST['pid']) ? trim($_POST['pid']) : null;
	
    if (empty($uid)) {
        echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
        exit;
    }

    $query = "SELECT id, user_id, comments AS comment_body, comment_by AS user_name, date AS comment_date 
              FROM supplier_comments 
              WHERE user_id = '" . mysql_real_escape_string($uid) . "' AND post_id = '" . mysql_real_escape_string($postID) . "'";
	 
    $result = mysql_query($query);

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Error fetching comments: ' . mysql_error()]);
        exit;
    }

    $comments = [];
    while ($row = mysql_fetch_assoc($result)) {
		 $user_logo_view = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_photo` WHERE user_id = '" . $row['user_id'] . "' AND type = 'logo' LIMIT 1"));
        $comments[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'user_name' => htmlspecialchars($row['user_name'] ?? 'Anonymous User'),
            'comment_body' => htmlspecialchars($row['comment_body'] ?? 'No content available.'),
            'comment_date' => $row['comment_date'] ?? 'Unknown Date',
            'user_image' => $user_logo_view['file'] ?? 'https://www.motiv8search.com/images/profile-profile-holder.png', // Placeholder
        ];
    }

    if (!empty($comments)) {
        echo json_encode(['success' => true, 'data' => $comments]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No comments found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
