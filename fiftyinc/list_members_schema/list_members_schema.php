<?php
$sql = $_ENV[sqlquery]." LIMIT 2000";
$query = mysql_query($sql);
$main_array = [];

while ($row = mysql_fetch_assoc($query)) {
	$main_array[] = $row['user_id'];
}

$user_ids = implode(',', $main_array);

$get_users_sql = "SELECT DISTINCT ud.user_id,ud.company, ud.first_name, ud.last_name, ud.listing_type, ud.filename,(SELECT file FROM users_photo WHERE user_id = ud.user_id AND users_photo.type = 'logo') AS logo_file, (SELECT file FROM users_photo WHERE user_id = ud.user_id AND users_photo.type = 'photo') AS photo_file FROM users_data ud WHERE ud.user_id IN ( ". $user_ids . ") ORDER BY FIELD(ud.user_id, ". $user_ids . ") LIMIT 2000";

//echo  $get_users_sql;

$get_users = mysql_query($get_users_sql);


$list_count= mysql_num_rows($get_users);

$page_name = "";

$schema_title = "Top " . $list_count . " ";

$template_id = $page['template_id'];


$filename_structure = end(explode('/', $page['filename_structure']));

if ($filename_structure == '%profession_name%' || $filename_structure == '%service_name%') {
	if ($pars[0] != 'search_results') {
		$service_name = ucfirst(str_replace('-', ' ', end($pars)));
		$second_last_item = $pars[count($pars) - 2];
		if ($second_last_item) {
			$service_name .= " in ". ucfirst(str_replace('-', ' ', $second_last_item));
		}
	} else {
		$service_name = "Members";
	}
	$page_name = $service_name;
}

if ($filename_structure != '%profession_name%' || $filename_structure != '%service_name%') {
	if ($pars[0] != 'search_results') {
		$service_name .= ucfirst(str_replace('-', ' ', array_pop(end($pars))));
	}
}



$schema_title .= $page_name;

?>


<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "url": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>",
  "name": "<?php echo $schema_title; ?>",
  
	"itemListOrder": "https://schema.org/ItemListOrderDescending",
  "numberOfItems": "<?php echo $list_count;?>",
  "itemListElement": [
	<?php 
	$list_numbers=1;
	while ($list_members_data=mysql_fetch_assoc($get_users)) {
		
			$user_short_des = mysql_query("SELECT value FROM `users_meta` WHERE `key`='short_description' AND `database`='users_data' AND `database_id`='$list_members_data[user_id]'");
				$short_des = mysql_fetch_assoc($user_short_des);
		//echo "SELECT value FROM `users_meta` WHERE `key`='short_description' AND `database`='users_data' AND `database_id`='$list_members_data[user_id]'";
			
		$logo ="";
		if ($list_members_data['listing_type'] == 'Company') {
			if ($list_members_data['logo_file'] != '') {
				$logo .="/logos/profile/".$list_members_data['logo_file'];
			} else {
				$logo .="/pictures/profile/".$list_members_data['photo_file'];
			}
		} else if ($list_members_data['listing_type'] == 'Individual') {
			if ($list_members_data['photo_file'] != '') {
				$logo .="/pictures/profile/".$list_members_data['photo_file'];
			} else {
				$logo .="/logos/profile/".$list_members_data['logo_file'];
			}
		}
	 ?>		
			{
		    	"@type": "ListItem",
		      	"position": <?php echo $list_numbers++;?>,
		      	"item": {
		        "@type": "ListItem"
		          <?php if($list_members_data['logo_file'] !='' || $list_members_data['photo_file'] != ''){ ?>
    				,"image" : "<?php echo 'http://localbulls.com/'.$logo ?>"
    			<? } ?>
		        <?php if($list_members_data['filename'] !=''){ ?>
    				,"url" : "<?php echo 'https://%website_url%/'.$list_members_data['filename']?>"
    			<?php } ?>
		           <?php if($list_members_data['company'] || $list_members_data['first_name']){ ?>
    				,"name": "<?php echo ($list_members_data['company'] != '') ?  $list_members_data['company'] : $list_members_data['first_name']." ".$list_members_data['last_name'] ?>"
    			<?php } ?>
				<?php if($list_members_data['filename'] !=''){ ?>
    				,"mainEntityOfPage" : "<?php echo 'https://%website_url%/'.$list_members_data['filename']?>"
    			<?php } ?>
				
				
				
				<?php if($short_des[value]!=''){?>
    			,"description" : "<?php echo strip_tags(str_replace('"', '', $short_des[value]));?>"

				<?php } ?>
		      	}
		    }
		   <?php 
		
		echo $list_count >= $list_numbers ? "," : "" ?>
		<?php } ?>
		
		 ]
}

</script>