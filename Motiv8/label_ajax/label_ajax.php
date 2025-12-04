<?php 
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $labelId = isset($_POST['label_id']) ? (int)$_POST['label_id'] : 0;
    $newTextLabel = isset($_POST['text_label']) ? $_POST['text_label'] : '';
    $newColorCode = isset($_POST['color_code']) ? $_POST['color_code'] : '';

    // Sanitize inputs
    $newTextLabel = mysql_real_escape_string($newTextLabel);
    $newColorCode = mysql_real_escape_string($newColorCode);

    // Get the current label text
    $getOldLabelQuery = "SELECT text_label FROM supplier_labels WHERE id = $labelId";
    $oldLabelResult = mysql_query($getOldLabelQuery);
    $oldLabelRow = mysql_fetch_assoc($oldLabelResult);
    $oldTextLabel = $oldLabelRow['text_label'];

    // Update all labels that have the same text_label
    $updateQuery = "UPDATE supplier_labels SET text_label = '$newTextLabel', color_code = '$newColorCode' WHERE text_label = '$oldTextLabel'";
    //echo "UPDATE supplier_labels SET text_label = '$newTextLabel', color_code = '$newColorCode' WHERE text_label = '$oldTextLabel'";
    if (mysql_query($updateQuery)) {
       echo "Success";
    } else {
        die("Error: " . mysql_error()); 
    }
}



if (isset($_POST['action']) && $_POST['action'] === 'create') {
  $text_label = $_POST['text_label'];
  $color_code = $_POST['color_code'];
  $post_id = $_POST['post_id'];
  $user_id = $_POST['user_id'];
  $type = $_POST['label_type'];
  $insertQuery = "INSERT INTO supplier_labels (text_label, color_code, post_id, user_id,type,created_by, created_at) VALUES ('$text_label', '$color_code', '$post_id', '$user_id', '$type', '" . $sess['admin_name'] . "',NOW())";
  
  if (mysql_query($insertQuery)) {
	 // echo $insertQuery;
    echo "Success";
  } else {
    echo "Error: " . $insertQuery . "<br>" . mysql_error();
  }
}


if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $labelId = isset($_POST['label_id']) ? (int)$_POST['label_id'] : 0;

    if ($labelId === 0) {
        echo json_encode(["status" => "error", "message" => "Invalid label ID"]);
        exit;
    }

    $deleteQuery = "DELETE FROM supplier_labels WHERE id = $labelId";
    $result = mysql_query($deleteQuery); 

    if ($result) {
        echo json_encode(["status" => "Success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete label"]);
    }
    exit;
}




?>