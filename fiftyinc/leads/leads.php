<?php
	function auth_user($email="", $password="")
	{
		global $w;
		$raw_pass = $password;
		$salt = substr(hash("md5",$w['website_id']."qmzpalvt193764"), -22);
		$password = crypt($password, '$2a$11$'.$salt);
		$get_user_sql = mysql($w['database'],"SELECT * FROM users_data WHERE email = '".$email."' AND password = '".$password."'");
		$is_valid_user = false;
		if (mysql_num_rows($get_user_sql)) {
			$is_valid_user = true;
		}
		return $is_valid_user;
	}
	$response = array();
	$error = array();
	if (isset($_POST)) {
		if (isset($_POST['email']) && isset($_POST['password'])) {
			$is_auth = auth_user($_POST['email'], $_POST['password']);
			if ($is_auth) {
				$response['auth'] = true;
			} else {
				$error['authentication_failure'] = "Authentication credentials are invalid";
			}
		} else {
			$error['login_credentials'] = "Authentication credentials were not provided";
		}
	} else {
		$error['http_request_method'] = "This is not a post request";
	}

	if (count($error)) {
		$response['error'] = $error;
	}

	echo json_encode($response);
 ?>