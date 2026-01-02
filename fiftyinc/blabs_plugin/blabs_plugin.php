<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<?php
echo $w['ip_address'];
//print_r($_COOKIE);
$url=$_COOKIE[newsite_url];
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"CREATE TABLE `purchased_landingpages` (id INT AUTO_INCREMENT PRIMARY KEY,`lp_id` INT NOT NULL,`lpage_path` VARCHAR(255) NOT NULL,`lp_token` VARCHAR(500) NOT NULL)");
?>
<?php
//echo $url;
$servername = base64_decode("ZGlyYXBwMjYuZGlyZWN0b3J5c2VjdXJlLmNvbQ==");
$username = base64_decode("d3N0YW4xMjYzMl9iZXN0d29yZHByZXNz");
$password = base64_decode("YmVzdHdvcmRwcmVzc0AyMDE5");
$dbname = base64_decode("d3N0YW4xMjYzMl9kaXJlY3Rvcnk=");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if ($conn) {
  $sql = "SELECT * FROM landingpages where id!='1' AND status=1";
$result = mysqli_query($conn, $sql);
	$sql1= "SELECT * FROM landing_page_website_members	where `website_domain`='$url'";									
$result1 = mysqli_query($conn, $sql1);
//echo $result;
$row1 = mysqli_fetch_assoc($result1);
if(mysqli_num_rows($result) > 0) { ?>
<div class="test" style="height:70px;">

</div>
<?  $sql22= "SELECT * FROM landing_page_website_members where `website_domain`='".$w['website_url']."' AND status=1";
    $result22 = mysqli_query($conn, $sql22);
    $row22 = mysqli_num_rows($result22);
	if($row22==0){   ?>
  <div class="alert alert-warning col-md-8">
  <strong>Warning!</strong> You are deactivated. Please contact Admin
</div>  
    
    <? } ?>
<div class="row">
    <div class="col-sm-9">
	<div class="col-sm-12">

   <? while($row = mysqli_fetch_assoc($result)) { ?>
     <div class="col-sm-3 overlay" style="border:2px solid black;height:250px;margin:30px 20px 30px;padding:0px !important;">
		
		
<img src="<?=$row[lp_screenshot]?>" class="image" style="width:100%;height:70%;">
  <div class="middle">
	  <? $lpids = explode(',',$row1['lp_id']);
	   if(in_array($row[id],$lpids)){
$check_lp=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `purchased_landingpages` WHERE lp_id='".$row[id]."'");  
$checked_lp=mysql_fetch_assoc($check_lp); 
if($checked_lp['lpage_path']==""){
		   ?>
    <a href="/admin/go.php?widget=blabs_plugin&install_package=<?=$row[id]?>" class="link"><div class="textone"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> Install Now</div></a>
<? } else{ ?>
    <a href="https://<?=$w['website_url']?>/<?=$checked_lp['lpage_path']."?edit_token=".$checked_lp['lp_token']?>" target="_blank" class="link"><div class="textone"><i class="fa fa-pencil-square" aria-hidden="true"></i> Edit Page</div></a>
        <a href="https://<?=$w['website_url']."/".$checked_lp['lpage_path'];?>" target="_blank" class="link"><div class="textone"><i class="fa fa-eye" aria-hidden="true"></i> View Page</div></a>
<? } ?>
	  <? } else{ ?>
		
		<a href="<?=$row['view_demo_page_link']?>" target="_blank" class="link"><div class="textone">View Demo Page</div></a>
	  <a href="<?=$row['buy_this_landing_page_link']?>" target="_blank" class="link"><div class="textone">Buy this Template</div></a>
	   <a href="#" target="_blank" class="link"><div class="textone">Get Landing Pages Suite</div></a>
		<? }?>
  </div>

		 
         
		<? 
	//echo "hello";
		//echo $row1[lp_id];
		//echo $row[id];
   	  $lpids = explode(',',$row1['lp_id']);
	   if(in_array($row[id],$lpids)){
 $check_lp_one=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `purchased_landingpages` WHERE lp_id='".$row[id]."'");  
$checked_lp_one=mysql_fetch_assoc($check_lp_one); 
if($checked_lp_one['lpage_path']==""){
		   ?>
         <p class="text-center" style="margin-top:5px;"><?=$row[lp_name]?></p>
		 <p class="text-center" style="margin-top:5px;">Purchased and Not Active</p>
	<?  } else{ ?>
 <p class="text-center" style="margin-top:5px;"><?=$row[lp_name]?></p>
		 <p class="text-center" style="margin-top:5px;">Purchased and Active</p>   
    <? } ?>
         <? }
        else{?>
         <p class="text-center" style="margin-top:5px;"><?=$row[lp_name]?></p> 
		 <p class="text-center" style="margin-top:5px;">Not Purchased</p>
            
       <? }          
?>
  </div>
		<? } ?>
	</div></div>
<div class="col-sm-2" style="border:2px solid black;min-height:340px;margin:20px;position:fixed;top: 110px;left: 1090px;">
	
	<div class="middle1">
    <?	$sql_res= "SELECT * FROM `lp_settings` WHERE `id` = 1";									
$result_settings = mysqli_query($conn, $sql_res);
//echo $result;
$lp_settings = mysqli_fetch_assoc($result_settings); ?>
		<p>Messaging</p>
		 <a href="<?="https://".$lp_settings['request_for_customizations']?>" target="_blank" class="link"><div class="textone" style="font-size:14px;padding: 14px 0px;">Request for Customization</div></a>
		 <a href="<?="https://".$lp_settings['request_new_functionalities']?>" target="_blank" class="link"><div class="textone"  style="font-size:14px;padding: 14px 0px;">Request new functionalities</div></a>
		 <a href="<?="https://".$lp_settings['book_strategy_call']?>" target="_blank" class="link"><div class="textone" style="font-size:14px;padding: 14px 0px;">Book Strategy Call</div></a>
         <a href="<?="https://".$lp_settings['submit_landing_page_idea']?>" target="_blank" class="link"><div class="textone" style="font-size:14px;padding: 14px 0px;">Submit Landing Page Idea</div></a>
		 <a href="<?="https://".$lp_settings['get_landing_page_suite']?>" target="_blank" class="link"><div class="textone" style="font-size:14px;padding: 14px 0px;">Get Landing Page Suite</div></a>


		</div>
	
	</div></div>
<?
    
}


if($_GET['install_package']!=""){
	if($_GET['install_package']==2){
		
		
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"CREATE TABLE `landingpage_images` (id INT AUTO_INCREMENT PRIMARY KEY,`file` VARCHAR(255) NOT NULL,`token` VARCHAR(255) NOT NULL,`status` INT NOT NULL)");
$sql7= "SELECT * FROM landingpage_images";									
$result7 = mysqli_query($conn, $sql7);
while($row7=mysqli_fetch_assoc($result7)){
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `landingpage_images` SET `id`='".$row7['id']."',`file`='".$row7['file']."',`token`='".$row7['token']."',`status`=0");
}
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"CREATE TABLE landing_page_fields (id INT(11) AUTO_INCREMENT PRIMARY KEY,`edit_token` LONGTEXT NOT NULL,`main_head` LONGTEXT NOT NULL,`heading` LONGTEXT NOT NULL,`subtitle` LONGTEXT NOT NULL,`heading_2` LONGTEXT NOT NULL,`heading_3` LONGTEXT NOT NULL,`heading_3_sub` LONGTEXT NOT NULL,`heading_4` LONGTEXT NOT NULL,`heading_4_sub` LONGTEXT NOT NULL,`heading_4_one` LONGTEXT NOT NULL,`heading_4_two` LONGTEXT NOT NULL,`heading_5` LONGTEXT NOT NULL,`heading_6_one` LONGTEXT NOT NULL,`heading_6_two` LONGTEXT NOT NULL,`heading_6_three` LONGTEXT NOT NULL,`heading_6_four` LONGTEXT NOT NULL,`heading_6_five` LONGTEXT NOT NULL,`heading_6_six` LONGTEXT NOT NULL,`heading_6_seven` LONGTEXT NOT NULL,`heading_6_eight` LONGTEXT NOT NULL,`pricing_cost` LONGTEXT NOT NULL,`pricing_head` LONGTEXT NOT NULL,`pricing_sub` LONGTEXT NOT NULL,`pricing_tag` LONGTEXT NOT NULL,`heading_9` LONGTEXT NOT NULL,`heading_quote` LONGTEXT NOT NULL,`footer_auth` LONGTEXT NOT NULL,`footer_authreg` LONGTEXT NOT NULL,`footer_authcompany` LONGTEXT NOT NULL,`heading_4_onetext` LONGTEXT NOT NULL,`heading_4_onesubhead` LONGTEXT NOT NULL,`heading_4_twotxt` LONGTEXT NOT NULL,`heading_4_twotxthd` LONGTEXT NOT NULL,`heading_5_txt` LONGTEXT NOT NULL,`heading_6_onetxt` LONGTEXT NOT NULL,`heading_6_onesub` LONGTEXT NOT NULL,`heading_6_twotxt` LONGTEXT NOT NULL,`heading_6_twosub` LONGTEXT NOT NULL,`heading_6_threetxt` LONGTEXT NOT NULL,`heading_6_threetxtsub` LONGTEXT NOT NULL,`heading_6_fourtxt` LONGTEXT NOT NULL,`heading_6_foursub` LONGTEXT NOT NULL,`heading_6_fivetxt` LONGTEXT NOT NULL,`heading_6_fivesub` LONGTEXT NOT NULL,`heading_6_sixtxt` LONGTEXT NOT NULL,`heading_6_sixsub` LONGTEXT NOT NULL,`heading_6_seventxt` LONGTEXT NOT NULL,`heading_6_sevensub` LONGTEXT NOT NULL,`heading_6_eighttxt` LONGTEXT NOT NULL,`heading_6_eightsub` LONGTEXT NOT NULL,`classic` LONGTEXT NOT NULL,`restaurantpro` LONGTEXT NOT NULL,`placespro` LONGTEXT NOT NULL,`embed_link` LONGTEXT NOT NULL,`embed_link_url` LONGTEXT NOT NULL)");	
	$sql6= "SELECT * FROM landing_page_fields where id=39";									
$result6 = mysqli_query($conn, $sql6);
$row6=mysqli_fetch_assoc($result6);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `landing_page_fields` SET `id`=39,`edit_token`='".$row6['edit_token']."',`main_head`='".mysql_real_escape_string($row6['main_head'])."',`heading`='".mysql_real_escape_string($row6['heading'])."',`subtitle`='".mysql_real_escape_string($row6['subtitle'])."',`heading_2`='".mysql_real_escape_string($row6['heading_2'])."',`heading_3`='".mysql_real_escape_string($row6['heading_3'])."',`heading_3_sub`='".mysql_real_escape_string($row6['heading_3_sub'])."',`heading_4`='".mysql_real_escape_string($row6['heading_4'])."',`heading_4_sub`='".mysql_real_escape_string($row6['heading_4_sub'])."',`heading_4_one`='".mysql_real_escape_string($row6['heading_4_one'])."',`heading_4_two`='".mysql_real_escape_string($row6['heading_4_two'])."',`heading_5`='".mysql_real_escape_string($row6['heading_5'])."',`heading_6_one`='".mysql_real_escape_string($row6['heading_6_one'])."',`heading_6_two`='".mysql_real_escape_string($row6['heading_6_two'])."',`heading_6_three`='".mysql_real_escape_string($row6['heading_6_three'])."',`heading_6_four`='".mysql_real_escape_string($row6['heading_6_four'])."',`heading_6_five`='".mysql_real_escape_string($row6['heading_6_five'])."',`heading_6_six`='".mysql_real_escape_string($row6['heading_6_six'])."',`heading_6_seven`='".mysql_real_escape_string($row6['heading_6_seven'])."',`heading_6_eight`='".mysql_real_escape_string($row6['heading_6_eight'])."',`pricing_cost`='".mysql_real_escape_string($row6['pricing_cost'])."',`pricing_head`='".mysql_real_escape_string($row6['pricing_head'])."',`pricing_sub`='".mysql_real_escape_string($row6['pricing_sub'])."',`pricing_tag`='".mysql_real_escape_string($row6['pricing_tag'])."',`heading_9`='".mysql_real_escape_string($row6['heading_9'])."',`heading_quote`='".mysql_real_escape_string($row6['heading_quote'])."',`footer_auth`='".mysql_real_escape_string($row6['footer_auth'])."',`footer_authreg`='".mysql_real_escape_string($row6['footer_authreg'])."',`footer_authcompany`='".mysql_real_escape_string($row6['footer_authcompany'])."',`heading_4_onetext`='".mysql_real_escape_string($row6['heading_4_onetext'])."',`heading_4_onesubhead`='".mysql_real_escape_string($row6['heading_4_onesubhead'])."',`heading_4_twotxt`='".mysql_real_escape_string($row6['heading_4_twotxt'])."',`heading_4_twotxthd`='".mysql_real_escape_string($row6['heading_4_twotxthd'])."',`heading_5_txt`='".mysql_real_escape_string($row6['heading_5_txt'])."',`heading_6_onetxt`='".mysql_real_escape_string($row6['heading_6_onetxt'])."',`heading_6_onesub`='".mysql_real_escape_string($row6['heading_6_onesub'])."',`heading_6_twotxt`='".mysql_real_escape_string($row6['heading_6_twotxt'])."',`heading_6_twosub`='".mysql_real_escape_string($row6['heading_6_twosub'])."',`heading_6_threetxt`='".mysql_real_escape_string($row6['heading_6_threetxt'])."',`heading_6_threetxtsub`='".mysql_real_escape_string($row6['heading_6_threetxtsub'])."',`heading_6_fourtxt`='".mysql_real_escape_string($row6['heading_6_fourtxt'])."',`heading_6_foursub`='".mysql_real_escape_string($row6['heading_6_foursub'])."',`heading_6_fivetxt`='".mysql_real_escape_string($row6['heading_6_fivetxt'])."',`heading_6_fivesub`='".mysql_real_escape_string($row6['heading_6_fivesub'])."',`heading_6_sixtxt`='".mysql_real_escape_string($row6['heading_6_sixtxt'])."',`heading_6_sixsub`='".mysql_real_escape_string($row6['heading_6_sixsub'])."',`heading_6_seventxt`='".mysql_real_escape_string($row6['heading_6_seventxt'])."',`heading_6_sevensub`='".mysql_real_escape_string($row6['heading_6_sevensub'])."',`heading_6_eighttxt`='".mysql_real_escape_string($row6['heading_6_eighttxt'])."',`heading_6_eightsub`='".mysql_real_escape_string($row6['heading_6_eightsub'])."',`classic`='".mysql_real_escape_string($row6['classic'])."',`restaurantpro`='".mysql_real_escape_string($row6['restaurantpro'])."',`placespro`='".mysql_real_escape_string($row6['placespro'])."',`embed_link`='".$row6['embed_link']."',`embed_link_url`='".$row6['embed_link_url']."'");
$sql8= "SELECT * FROM data_widgets where `widget_name`='landpage_edit'";
$result8 = mysqli_query($conn, $sql8);
$row8 = mysqli_fetch_assoc($result8);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='landpage_edit',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row8['widget_data'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83540.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_html_element`='div'");
$sql9= "SELECT * FROM data_widgets where `widget_name`='imgupload'";
$result9 = mysqli_query($conn, $sql9);
$row9 = mysqli_fetch_assoc($result9);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='imgupload',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row9['widget_data'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83541.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_html_element`='div'");
$sql10= "SELECT * FROM data_widgets where `widget_name`='img_path_save'";
$result10 = mysqli_query($conn, $sql10);
$row10 = mysqli_fetch_assoc($result10);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='img_path_save',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row10['widget_data'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83542.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_html_element`='div'");
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `purchased_landingpages` SET `lp_id`='".$_GET['install_package']."',lpage_path='landingpage-one',`lp_token`='".$row6['edit_token']."'");
	}
		if($_GET['install_package']==11){
			
			
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"CREATE TABLE `classicpage_list` (id INT AUTO_INCREMENT PRIMARY KEY,`list_content` LONGTEXT NOT NULL,`token` VARCHAR(255) NOT NULL,`placeholder` VARCHAR(255) NOT NULL)");
$sql77= "SELECT * FROM classicpage_list";									
$result77 = mysqli_query($conn, $sql77);
while($row77=mysqli_fetch_assoc($result77)){
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `classicpage_list` SET `id`='".$row77['id']."',`list_content`='".$row77['list_content']."',`token`='".$row77['token']."',`placeholder`='".$row77['placeholder']."'");
}			
			
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"CREATE TABLE `classicpage_images` (id INT AUTO_INCREMENT PRIMARY KEY,`file` VARCHAR(255) NOT NULL,`token` VARCHAR(255) NOT NULL,`status` INT NOT NULL)");
$sql7= "SELECT * FROM classicpage_images";									
$result7 = mysqli_query($conn, $sql7);
while($row7=mysqli_fetch_assoc($result7)){
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `classicpage_images` SET `id`='".$row7['id']."',`file`='".$row7['file']."',`token`='".$row7['token']."',`status`=0");
}
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"CREATE TABLE classic_page_fields (id INT(11) AUTO_INCREMENT PRIMARY KEY,`edit_token` LONGTEXT NOT NULL,`heading` LONGTEXT NOT NULL,`heading_one` LONGTEXT NOT NULL,`heading_three` LONGTEXT NOT NULL,`mainheading_two` LONGTEXT NOT NULL,`mainheading_twosub` LONGTEXT NOT NULL,`mainheading_twosubsub` LONGTEXT NOT NULL,`mainheading_three` LONGTEXT NOT NULL,`mainheading_threesub` LONGTEXT NOT NULL,`article` LONGTEXT NOT NULL,`mainheading_four` LONGTEXT NOT NULL,`mainheading_foursub` LONGTEXT NOT NULL,`mainheading_five` LONGTEXT NOT NULL,`mainheading_fivesub` LONGTEXT NOT NULL,`mainheading_fiveauth` LONGTEXT NOT NULL,`mainheading_six` LONGTEXT NOT NULL,`mainheading_sixsub` LONGTEXT NOT NULL,`bike_content` LONGTEXT NOT NULL,`smile_content` LONGTEXT NOT NULL,`source_content` LONGTEXT NOT NULL,`heading_text` LONGTEXT NOT NULL,`village` LONGTEXT NOT NULL,`rating_one` LONGTEXT NOT NULL,`textreal` LONGTEXT NOT NULL,`realprof` LONGTEXT NOT NULL,`hotel` LONGTEXT NOT NULL,`rating_two` LONGTEXT NOT NULL,`realprof_two` LONGTEXT NOT NULL,`realtext_two` LONGTEXT NOT NULL,`barrel` LONGTEXT NOT NULL,`rating_three` LONGTEXT NOT NULL,`realprof_three` LONGTEXT NOT NULL,`realtext_three` LONGTEXT NOT NULL,`embed_link` LONGTEXT NOT NULL,`embed_link_url` LONGTEXT NOT NULL)");	
	$sql6= "SELECT * FROM classic_page_fields where id=39";									
$result6 = mysqli_query($conn, $sql6);
$row6=mysqli_fetch_assoc($result6);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `classic_page_fields` SET `id`=39,`edit_token`='".$row6['edit_token']."',`heading`='".mysql_real_escape_string($row6['heading'])."',`heading_one`='".mysql_real_escape_string($row6['heading_one'])."',`heading_three`='".mysql_real_escape_string($row6['heading_three'])."',`mainheading_two`='".mysql_real_escape_string($row6['mainheading_two'])."',`mainheading_twosub`='".mysql_real_escape_string($row6['mainheading_twosub'])."',`mainheading_twosubsub`='".mysql_real_escape_string($row6['mainheading_twosubsub'])."',`mainheading_three`='".mysql_real_escape_string($row6['mainheading_three'])."',`mainheading_threesub`='".mysql_real_escape_string($row6['mainheading_threesub'])."',`article`='".mysql_real_escape_string($row6['article'])."',`mainheading_four`='".mysql_real_escape_string($row6['mainheading_four'])."',`mainheading_foursub`='".mysql_real_escape_string($row6['mainheading_foursub'])."',`mainheading_five`='".mysql_real_escape_string($row6['mainheading_five'])."',`mainheading_fivesub`='".mysql_real_escape_string($row6['mainheading_fivesub'])."',`mainheading_fiveauth`='".mysql_real_escape_string($row6['mainheading_fiveauth'])."',`mainheading_six`='".mysql_real_escape_string($row6['mainheading_six'])."',`mainheading_sixsub`='".mysql_real_escape_string($row6['mainheading_sixsub'])."',`bike_content`='".mysql_real_escape_string($row6['bike_content'])."',`smile_content`='".mysql_real_escape_string($row6['smile_content'])."',`source_content`='".mysql_real_escape_string($row6['source_content'])."',`heading_text`='".mysql_real_escape_string($row6['heading_text'])."',`village`='".mysql_real_escape_string($row6['village'])."',`rating_one`='".mysql_real_escape_string($row6['rating_one'])."',`textreal`='".mysql_real_escape_string($row6['textreal'])."',`realprof`='".mysql_real_escape_string($row6['realprof'])."',`hotel`='".mysql_real_escape_string($row6['hotel'])."',`rating_two`='".mysql_real_escape_string($row6['rating_two'])."',`realprof_two`='".mysql_real_escape_string($row6['realprof_two'])."',`realtext_two`='".mysql_real_escape_string($row6['realtext_two'])."',`barrel`='".mysql_real_escape_string($row6['barrel'])."',`rating_three`='".mysql_real_escape_string($row6['rating_three'])."',`realprof_three`='".mysql_real_escape_string($row6['realprof_three'])."',`realtext_three`='".mysql_real_escape_string($row6['realtext_three'])."',`embed_link`='".mysql_real_escape_string($row6['embed_link'])."',`embed_link_url`='".mysql_real_escape_string($row6['embed_link_url'])."'");
$sql8= "SELECT * FROM data_widgets where `widget_name`='Classic Search'";
$result8 = mysqli_query($conn, $sql8);
$row8 = mysqli_fetch_assoc($result8);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='Classic Search',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row8['widget_data'])."',widget_style='".mysql_real_escape_string($row8['widget_style'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83539.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_javascript`='".mysql_real_escape_string($row8['widget_javascript'])."',`widget_html_element`='div'");
$sql88= "SELECT * FROM data_widgets where `widget_name`='homepagelist_one'";
$result88 = mysqli_query($conn, $sql88);
$row88 = mysqli_fetch_assoc($result88);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='homepagelist_one',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row88['widget_data'])."',widget_style='".mysql_real_escape_string($row88['widget_style'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83567.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_javascript`='".mysql_real_escape_string($row88['widget_javascript'])."',`widget_html_element`='div'");
$sql9= "SELECT * FROM data_widgets where `widget_name`='classic_imgupload'";
$result9 = mysqli_query($conn, $sql9);
$row9 = mysqli_fetch_assoc($result9);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='classic_imgupload',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row9['widget_data'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83541.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_html_element`='div'");
$sql91= "SELECT * FROM data_widgets where `widget_name`='classic_save'";
$result91 = mysqli_query($conn, $sql91);
$row91 = mysqli_fetch_assoc($result91);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='classic_save',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row91['widget_data'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83544.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_html_element`='div'");
$sql99= "SELECT * FROM data_widgets where `widget_name`='Classic Editable'";
$result99 = mysqli_query($conn, $sql99);
$row99 = mysqli_fetch_assoc($result99);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='Classic Editable',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row99['widget_data'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83543.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_html_element`='div'");
$sql10= "SELECT * FROM data_widgets where `widget_name`='classic_img_path_save'";
$result10 = mysqli_query($conn, $sql10);
$row10 = mysqli_fetch_assoc($result10);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='classic_img_path_save',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row10['widget_data'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83542.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_html_element`='div'");
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `purchased_landingpages` SET `lp_id`='".$_GET['install_package']."',lpage_path='homepage-one',`lp_token`='".$row6['edit_token']."'");
	}
	
	$sql3= "SELECT * FROM landingpages	where `id`=".$_GET['install_package'];									
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($result3);
$lp_name = substr(strrchr($row3['view_demo_page_link'], '/'), +1);
	$sql4= "SELECT * FROM list_seo where `filename`='".$lp_name."'";
$result4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_assoc($result4);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `list_seo` SET `master_id`=0,`seo_type`='content',`database_id`=0,`filename`='".$lp_name."',`updated_by`='bdteam@businesslabs.org',`content`='".$row4['content']."',`content_active`=1,`show_form`=0,`content_css`='".$row4['content_css']."',`menu_layout`=1,`hide_from_menu`=0,`hide_header_links`=0,`hide_header`=0,`hide_footer`=0,`hide_top_right`=0,`org_template`=0,`content_layout`=1,`custom_html_placement`=0");
$data_one= str_replace(array('[',']'),'',$row4['content']);  
$widget_name = substr($data_one,strpos($data_one, "=")+1);
$w_name = strip_tags($widget_name);
$sql5= "SELECT * FROM data_widgets where `widget_name`='".$w_name."'";
$result5 = mysqli_query($conn, $sql5);
$row5 = mysqli_fetch_assoc($result5);
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"INSERT INTO `data_widgets` SET `widget_name`='".$w_name."',`widget_type`='Widget',`widget_data`='".mysql_real_escape_string($row5['widget_data'])."',widget_style='".mysql_real_escape_string($row5['widget_style'])."',`widget_viewport`='front',`short_code`='data_widgets-970a73f615e451921a9c77556cc83539.bd',`bootstrap_enabled`=4,`ssl_enabled`=1,`mobile_enabled`=1,`widget_javascript`='".mysql_real_escape_string($row5['widget_javascript'])."',`widget_html_element`='div'");

echo '<script>swal({
     title: "Sucess!",
     text: "Your Landing Page Installed Successfully.",
     type: "success",
     timer: 3000
     });
      setTimeout(function() {
        window.location.href="https://www.managemydirectory.com/admin/go.php?widget=blabs_plugin";
		  }, 5000);</script>';

	}




}

?>