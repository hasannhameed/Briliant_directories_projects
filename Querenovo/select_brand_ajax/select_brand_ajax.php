<?php

header("Content-Type: application/json");


$input = file_get_contents("php://input");
$data  = json_decode($input, true);


if (!isset($data['action']) || $data['action'] !== "save_brand_selection") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request"
    ]);
    exit;
}


$user_id = isset($_COOKIE['userid']) ? (int)$_COOKIE['userid'] : 0;

if ($user_id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in"
    ]);
    exit;
}


$brands = isset($data['brands']) ? $data['brands'] : [];

$brands_json = mysql_real_escape_string(json_encode($brands));

mysql_query("
    UPDATE users_data 
    SET brands_list = '$brands_json'
    WHERE user_id = '$user_id'
");


if (mysql_error()) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . mysql_error()
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "user_id" => $user_id,
    "received_brands" => $brands,
    "message" => "Brand list updated successfully"
]);

exit;
?>
