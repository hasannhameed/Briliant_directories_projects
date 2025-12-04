<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);
if ($_POST['imageAction'] == 'upload_image') {
    // we set the actions to work with images
    $file_tmp = $_FILES['croppedImage']['tmp_name'];
    $imageTemp = file_get_contents($file_tmp);
    $_POST['image_info'] = base64_encode($imageTemp);
	
	//get the user
	$img = $_POST['image_info'];
	$img = str_replace(" ", "+", $img);
	$data = base64_decode($img);
	$randImageNum = rand(0, 500);
	$baseDir = '/public_html/';
	 $_POST['image_extension']   = protectFrontendBadCode($_POST['image_extension']);
	
	$imageFileNameVar = "applicationimage";
	$file = "https://www.motiv8search.com" . "/images/" . $imageFileNameVar . "-" . $randImageNum .'.'. $_POST['image_extension'];
	$imageName = str_replace("https://www.motiv8search.com" . "/images/", "", $file);
	$filePath = $baseDir . 'images';
	
	//we connect to ftp
	$BDFtpManager = new BDFtpManager(
		brilliantDirectories::getDatabaseConfiguration('website_user'),
		brilliantDirectories::getDatabaseConfiguration('website_pass'),
		brilliantDirectories::getDatabaseConfiguration('ftp_server')
	);
	//we set our base folders
	$BDFtpManager->loadCommonImageFolders();
	//we execute upload
	$BDFtpManager->setCurrentFolder('/public_html/images');
	//open folder permissions
	$BDFtpManager->openFolderPermission(true);
	
	//we need to check if the real file is not a valid image file
        $slugs                  = explode('/', $file);
        $explodedRealFile       = array_filter(explode('/',$realFile));
        $fileNameToCheck        = end($slugs);
        $explodeFileNameToCheck = strtolower(strrchr($fileNameToCheck, '.'));
        $validImageExtensions   = array(
            '.png',
            '.jpg',
            '.jpeg',
            '.gif',
            '.svg',
            '.webp'
        );
	    if(in_array($explodeFileNameToCheck, $validImageExtensions)){
            $success = $BDFtpManager->filePutContents($file, $data);
        }

        $BDFtpManager->closeFolderPermission();

        $imageInfo = getimagesize($file);
        $info = pathinfo($file);
        $imageMimeType = '';

        if ($imageInfo !== false && isset($imageInfo['mime'])) {
            $imageMimeType = $imageInfo['mime'];
        }

        //we now validate the image
        $image = array(
            'extension' => $info['extension'],
            'mimeType' => $imageMimeType,
            'size' => filesize($file),
            'name' => $info['filename']
        );

        $imageObject = (object)($image);
        

}
$response[] = $success;
$response['status'] = $success;
$response[] = $file;
$response[] = error_get_last();
$response[] = $imageObject;
echo json_encode(bdHtmlSpecialCharsFrontend($response));
exit;



?>