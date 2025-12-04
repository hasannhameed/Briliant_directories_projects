<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');

// FTP Configuration
$username = brilliantDirectories::getDatabaseConfiguration('website_user'); // Username
$password = brilliantDirectories::getDatabaseConfiguration('website_pass'); // Password
$host = brilliantDirectories::getDatabaseConfiguration('ftp_server');
$ftp_path = "/public_html/images/";

// Connect to FTP server
$ftp = ftp_connect($host) or die("Couldn't connect to $host");
$login = ftp_login($ftp, $username, $password);
ftp_pasv($ftp, true);

// Check FTP connection
if (!$ftp || !$login) {
    die("FTP connection failed: " . ftp_error());
}

// Validate and process the uploaded image
$output = array();
$error = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['croppedImage'])) {
        $raw_image = $_POST['croppedImage'];
        $thumbnail_booth_name = $_POST["fileName"];
        $thumbnail_booth_type = $_POST["fileType"];
        $thumbnail_booth_tmp_name = $_POST["tmpName"];
        $thumbnail_booth_size = $_POST["fileSize"];
        $thumbnail_booth_width = $_POST["width"];
        $thumbnail_booth_height = $_POST["height"];
        // Check if the directory exists and is writable
        // if (ftp_mkdir($ftp, $ftp_path)) {
        //     ftp_chmod($ftp, 0777, $ftp_path); // Set directory permissions
        // }
        $image_url_decode = urldecode($raw_image);
        $replace_unwanted_chars = str_replace(" ", "+", $raw_image);
        $replace_unwanted_chars2 = str_replace('data:image/jpeg;base64', "", $replace_unwanted_chars);
        $imageData = base64_decode($replace_unwanted_chars2);


        $temp_file = tempnam(sys_get_temp_dir(), 'img_');
        file_put_contents($temp_file, $imageData);

        if (ftp_chdir($ftp, $ftp_path)) {
            // Upload the image to the FTP server
            $file = time() . "-" . str_replace(' ', '-', $thumbnail_booth_name); // Adjust filename as needed


            $output['file'] = $file;
            if (ftp_put($ftp, $file, $temp_file, FTP_BINARY)) {
                // FTP upload successful
                $output['message'] = 'File uploaded successfully.';
                $output['imgUrl'] = '/images/' . $file;
            } else {
                // FTP upload failed
                $output['message'] = 'Failed to upload the file. FTP error: ' . ftp_last_error($ftp);
                $error = 1;
            }
        } else {
            echo "test4";
            // Failed to change directory
            $output['message'] = 'Failed to change the remote directory.';
            $error = 1;
        }
    } else {
        $output['message'] = 'No image data received.';
        $error = 1;
    }

    $output['error'] = $error;
    echo json_encode($output);
}
?>
 