<?php
// Get the logo URL from the request parameter

$response = array();

$user_id = $_POST['user_id'];

$user_logo = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_photo` WHERE user_id = '$user_id' AND type = 'logo' LIMIT 1"));

if ($user_logo['file'] != '') {
    $response['logo'] = $user_logo['file'];
} else {
    $response['logo'] = "";
}

echo json_encode($response);
?>