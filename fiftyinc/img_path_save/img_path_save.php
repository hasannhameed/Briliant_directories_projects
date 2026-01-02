<?php
header("Access-Control-Allow-Origin:*");
if($_REQUEST['img_id']!="" && $_REQUEST['file_name']!=""){
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"UPDATE `landingpage_images` SET `file`='".$_REQUEST['file_name']."',`status`=1 WHERE `id`=".$_REQUEST['img_id']); 	
$data="success";
}
echo $data;
?> 