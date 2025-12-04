<?php
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);

	header('Access-Control-Allow-Origin: *');

	header('Access-Control-Allow-Methods: GET, POST');

	header("Access-Control-Allow-Headers: X-Requested-With");

	$get_post_sql = mysql_query('SELECT * FROM `data_posts` WHERE post_id = '.$_GET['post_id']);
	$get_post = mysql_fetch_assoc($get_post_sql);

	$get_start_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = ".$get_post['post_id']." AND `key` = 'start_time'");
	$get_start_time = mysql_fetch_assoc($get_start_time_sql);
	$start_time = $get_start_time['value'];

	$get_end_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = ".$get_post['post_id']." AND `key` = 'end_time'");
	$get_end_time = mysql_fetch_assoc($get_end_time_sql);
	$end_time = $get_end_time['value'];

	$get_post['start_time'] = $start_time;
	$get_post['end_time'] = $end_time;

	$get_post['post_expire_date'] = date('Y-m-d', strtotime($get_post['post_expire_date']));
	$get_post['post_start_date'] = date('Y-m-d', strtotime($get_post['post_start_date']));

	
	// Generate time sloats 

	$post_start_time_str = strtotime(str_replace(': ', ':', $start_time));
	$post_end_time_str = strtotime(str_replace(': ', ':', $end_time));

	$post_total_duration = $post_end_time_str - $post_start_time_str;

	$presentation_duration_second = 2400;

	$total_event_sloats = $post_total_duration / $presentation_duration_second;

	$final_sloats = floor($total_event_sloats);
	$time_sloats_arr = array();

	$time_interval = 0;
	for ($i=0; $i < $final_sloats; $i++) { 
	    $time_sloats_arr[$i]['start_time'] = $post_start_time_str + ($i * $presentation_duration_second) + $time_interval;
	    $time_sloats_arr[$i]['end_time'] = $time_sloats_arr[$i]['start_time'] + $presentation_duration_second;
	    $time_interval += 300;
	}

	// $sloats_html_select = "<select>";
	foreach ($time_sloats_arr as $key => $value) {
	    $sloat = date('H:i', $value['start_time'])." - ".date('H:i', $value['end_time']);
	    $sloats_html_select .= "<option value='".$sloat."'>".$sloat."</option>";
	}
	// $sloats_html_select .= "<select>";
	$get_post['time_sloats'] = $sloats_html_select;

	echo json_encode($get_post);
 ?>