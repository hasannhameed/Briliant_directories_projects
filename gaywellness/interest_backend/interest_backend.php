<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");


$method = $_SERVER['REQUEST_METHOD'];


if ($method == 'POST') {

    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    if (!empty($input['user_id']) && isset($input['items'])) {
        
        $user_id = addslashes($input['user_id']);
        
        $items_string = implode(',', $input['items']);
        $items_string = addslashes($items_string); 

        $sql = "UPDATE users_data SET interests = '$items_string' WHERE user_id = '$user_id'";

        $result = mysql_query($sql);

        if ($result) {
            echo json_encode(array("status" => "success", "message" => "Interests updated"));
        } else {
            echo json_encode(array("status" => "error", "message" => mysql_error()));
        }
        
    } else {
        echo json_encode(array("status" => "error", "message" => "Missing user_id or items"));
    }
}


elseif ($method == 'GET') {

    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

    if (!empty($user_id)) {
        
        $user_id = addslashes($user_id);

        $sql = "SELECT interests FROM users_data WHERE user_id = '$user_id' LIMIT 1";

        $stmt = mysql_query($sql);

        if ($stmt) {
            $result = mysql_fetch_assoc($stmt);

            if ($result) {
                $items_array = !empty($result['interests']) ? explode(',', $result['interests']) : array();

                echo json_encode(array(
                    "status" => "success",
                    "user_id" => $user_id,
                    "items" => $items_array
                ));
            } else {
                echo json_encode(array("status" => "success", "items" => array()));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => mysql_error()));
        }

    } else {
        echo json_encode(array("status" => "error", "message" => "User ID required"));
    }
}
?>