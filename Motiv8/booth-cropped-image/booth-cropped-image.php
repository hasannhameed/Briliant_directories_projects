<?php
header('Access-Control-Allow-Origin: *');
$username = brilliantDirectories::getDatabaseConfiguration('website_user'); //username
$password = brilliantDirectories::getDatabaseConfiguration('website_pass'); //password
$host = brilliantDirectories::getDatabaseConfiguration('ftp_server');

// Connect to FTP server
$ftp = ftp_connect($host) or die("Couldn't connect to $ftp");
$login = ftp_login($ftp, $username, $password);
ftp_pasv($ftp, true);


// Check FTP connection
if (!$ftp || !$login) {
    die("FTP connection failed: " . ftp_error());
}
$allowedExtensions = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp', 'application/pdf', 'application/msword', 'application/vnd.ms-powerpoint');
$maxFileSize = '5000000';

$output = array();
$msg='';
$error = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['croppedImage'])) {
		$boothId = $_POST['updateId'];
        $imageData = $_POST['croppedImage'];
        $thumbnail_booth_name = $_POST["fileName"];
        $thumbnail_booth_tmp_name = $_POST["tmpName"];
        $thumbnail_booth_type = $_POST["fileType"];
        $thumbnail_booth_size = $_POST["fileSize"];
        $thumbnail_booth_width = $_POST["width"];
        $thumbnail_booth_height = $_POST["height"];
        $imgData = str_replace('data:image/jpeg;base64,', '', $imageData);
        if (!in_array($thumbnail_booth_type, $allowedExtensions)) {
            $msg = "Please select valid file line no 45";
            $error = 1;
            exit;
        }

        if ($thumbnail_booth_size > $maxFileSize) {
            $msg = "Please select upto $maxFileSize MB";
            $error = 1;
            exit;
        }

         $file = time() . "-" . str_replace(' ', '-', $thumbnail_booth_name);
        //$file = time() .'cropped_image.jpg';

        // $server_path = "/public_html/images/events/" . $file;
        $server_path = "/public_html/images/events/";
        if (ftp_chdir($ftp, $server_path)) {
            // Upload the image to the FTP server
            if (ftp_fput($ftp, $file, fopen('data://text/plain;base64,' . $imgData, 'r'), FTP_BINARY)) {
                $msg = 'File uploaded successfully.';
                $error = 0;
                } else {
                    $msg = 'Failed to upload the file.';
                    $error = 1;
            }
        } else {
            $msg = 'Failed to change the remote directory.';
            $error = 1;
        }
		
		if($output['error']== 0 && isset($boothId)){
			$update_query = "UPDATE `live_events_posts` SET `thumb_booth` = '" . $file . "'";
			$update_query .= " WHERE `live_events_posts`.`id` = " . $boothId;
			//echo $update_query;
			$insert_video = mysql_query($update_query);
			$show_saved_message = false;
			if ($insert_video) {
				$show_saved_message = true;
			}
		}
        $output['message'] = $msg;
        $output['imgUrl'] = '/images/events/'.$file;
        $output['error'] = $error;
        $output['file'] = $file;
        $output['show_saved_message'] = $show_saved_message;
    }
     echo json_encode($output);
}
?>
