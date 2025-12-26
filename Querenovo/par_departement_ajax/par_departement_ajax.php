<?php
header("Content-Type: application/json");

$string_department = "SELECT * FROM `departments`";
$string_query = mysql_query($string_department);

$array = array();

while ($data = mysql_fetch_assoc($string_query)) {
    $array[] = $data;   // append each row
}

echo json_encode($array);
exit;
?>
