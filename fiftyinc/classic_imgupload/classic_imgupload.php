<?php
if(is_uploaded_file($_FILES['file']['tmp_name'])){
$temp=$_FILES['file']['tmp_name'];
$file=time()."-".$_FILES['file']['name'];
move_uploaded_file($temp,"uploads/".$file);	
}
$data=$file;
echo $data;
?>