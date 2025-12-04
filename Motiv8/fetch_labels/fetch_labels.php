<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$labelsWithColors = [];
$addOnData = [];


$query = mysql_query("SELECT text_label, color_code FROM supplier_labels");
while ($row = mysql_fetch_assoc($query)) {
    $labelsWithColors[$row['text_label']] = $row['color_code'];
}

/*$addOnQuery = mysql_query("SELECT name, color_code FROM add_ons");
while ($row = mysql_fetch_assoc($addOnQuery)) {
    $addOnData[$row['name']] = $row['color_code'];
}*/

echo json_encode(['labelsWithColors' => $labelsWithColors, 'addOnData' => $addOnData]);

?>
