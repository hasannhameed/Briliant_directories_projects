<?php
header("Access-Control-Allow-Origin:*");
if($_REQUEST['list_id']!="" && $_REQUEST['content_data']!=""){
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"UPDATE `classicpage_list` SET `placeholder`= '".$_REQUEST['placeholder']."',`list_content`='".$_REQUEST['content_data']."' WHERE `token`=".$_REQUEST['list_id']); 	
$data="success";
}
echo $data;
?>