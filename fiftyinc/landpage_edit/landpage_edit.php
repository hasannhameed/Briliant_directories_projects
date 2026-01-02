<? 
$db=brilliantDirectories::getDatabaseConfiguration('database'); 
if($_POST['edited_value']!='' && $_POST['db_name']!="" && $_POST['edited_value']!="<p>&nbsp; &nbsp;&nbsp;</p>" && $_POST['edited_value']!="<p><br></p>"){
$htmlcode=mysql_real_escape_string($_POST['edited_value']);	
mysql($db,"UPDATE `landing_page_fields` SET `".$_POST['db_name']."`='$htmlcode' WHERE id=39"); 
//echo "UPDATE `landing_page_fields` SET `".$_POST['db_name']."`='$htmlcode' WHERE id=39";
$data="success";	
}else{
$ $pageld=mysql($db,"UPDATE `landing_page_fields` SET `".$_POST['db_name']."`='Sample Text' WHERE id=39"); 
$data="failure";	
	}
echo $data;
?>
<? 
$db=brilliantDirectories::getDatabaseConfiguration('database'); 
if($_POST['embd_link']!=''){
$embedcode=mysql_real_escape_string($_POST['embd_link']);	
mysql($db,"UPDATE `landing_page_fields` SET `embed_link`='$embedcode' WHERE id=39"); 
$data="success";	
} else if($_POST['embed_link_url']!=''){
$embedcodeurl=mysql_real_escape_string($_POST['embed_link_url']);
$ebd_url=str_replace('watch?v=','embed/',$embedcodeurl);
mysql($db,"UPDATE `landing_page_fields` SET `embed_link_url`='$ebd_url' WHERE id=39"); 
$data="success";
} else if($_POST['embd_link']!='' && $_POST['embed_link_url']!=''){
$embedcode=mysql_real_escape_string($_POST['embed_link_url']);
$embedcodeurl=mysql_real_escape_string($_POST['embed_link_url']);
$ebd_url=str_replace('watch?v=','embed/',$embedcodeurl);
mysql($db,"UPDATE `landing_page_fields` SET `embed_link_url`='$embedcode',`embed_link_url`='$ebd_url' WHERE id=39"); 
$data="success";
}else{
$data="failure";
}
echo $data;
?>