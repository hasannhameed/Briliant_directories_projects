<?php 
//get the website picture settings
///error_reporting(E_ERROR | E_WARNING | E_PARSE);
if ($_POST['imageAction'] == 'upload_cover') {
    // we set the actions to work with cover
    $_POST['module_action'] = "upload_cover_main";
    $file_tmp = $_FILES['coverFile']['tmp_name'];
    $imageTemp = file_get_contents($file_tmp);
    $_POST['image_info'] = base64_encode($imageTemp);

}

$pictureSettingsQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
        *
    FROM
        `image_settings`");
$pictureSettings = array();

while ($setting = mysql_fetch_assoc($pictureSettingsQuery)) {
    $pictureSettings[$setting['setting_name']] = $setting;
}
if ($_POST['module_action'] == "get_image_settings") {

    foreach ($pictureSettings as $jiskey => $jisvalue) {

        if ($jisvalue['ratio'] != "free") {
            $pictureSettings[$jiskey]['js_ratio'] = str_replace(":", " / ", $jisvalue['ratio']);

        } else {
            $pictureSettings[$jiskey]['js_ratio'] = "";
        }
    }
    echo json_encode(bdHtmlSpecialCharsFrontend($pictureSettings));
    exit;
}

if ($_POST['module_action'] == "upload_image_main" || $_POST['module_action'] == "upload_image_social" || $_POST['module_action'] == "upload_logo_main" || $_POST['module_action'] == "upload_logo_social" || $_POST['module_action'] == "upload_cover_main") {

    if ($_POST['image_info'] != "") {
        //get current photo image
        $user = getUser($_COOKIE['userid'], $w);
        switch ($_POST['module_action']) {
            case ('upload_image_main'):
            case ('upload_image_social'):
                $imageTypeVar = "photo";
                break;
            case ('upload_logo_main'):
            case ('upload_logo_social'):
                $imageTypeVar = "logo";
                break;
            case ('upload_cover_main'):
                $imageTypeVar = "cover_photo";
                break;
        }
        $checkExistance = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                file,
                photo_id
            FROM
                `users_photo`
            WHERE
                user_id = '" . $user['user_id'] . "'
            AND
                type = '" . $imageTypeVar . "'");

        if (mysql_num_rows($checkExistance) > 0) {
            $currentRecord = mysql_fetch_assoc($checkExistance);

        } else {
            $currentRecord = array();
        }
        //get the user
        $img = $_POST['image_info'];
        $img = str_replace(" ", "+", $img);
        $data = base64_decode($img);
        $randImageNum = rand(0, 500);
        $baseDir = '/public_html/';

        imageValidator::sanitizeName($currentRecord['file']);

        $currentRecord['file'] = protectFrontendBadCode($currentRecord['file']);

        //assign image nomenclature
        switch ($_POST['module_action']) {
            case ('upload_image_main'):
                $imageFileNameVar = "pimage";
                $file = $_SERVER['DOCUMENT_ROOT'] . "/pictures/profile/" . $imageFileNameVar . "-" . $user['user_id'] . "-" . $randImageNum . "-photo." . $_POST['image_extension'];
                $filePath = $baseDir . 'pictures/profile/';
                $imageName = str_replace($_SERVER['DOCUMENT_ROOT'] . "/pictures/profile/", "", $file);
                break;
            case ('upload_image_social'):
                $imageFileNameVar = "pimage";
                $file = $_SERVER['DOCUMENT_ROOT'] . "/pictures/social_media/" . $currentRecord['file'];
                $filePath = $baseDir . 'pictures/social_media/';
                $imageName = $currentRecord['file'];
                break;
            case ('upload_logo_main'):
                $imageFileNameVar = "limage";
                $file = $_SERVER['DOCUMENT_ROOT'] . "/logos/profile/" . $imageFileNameVar . "-" . $user['user_id'] . "-" . $randImageNum . "-photo." . $_POST['image_extension'];
                $imageName = str_replace($_SERVER['DOCUMENT_ROOT'] . "/logos/profile/", "", $file);
                $filePath = $baseDir . 'logos/profile/';
                break;
            case ('upload_logo_social'):
                $imageFileNameVar = "limage";
                $file = $_SERVER['DOCUMENT_ROOT'] . "/logos/social_media/" . $currentRecord['file'];
                $imageName = $currentRecord['file'];
                $filePath = $baseDir . 'logos/social_media/';
                break;
            case ('upload_cover_main'):
                $imageFileNameVar = "cimage";
                $file = $_SERVER['DOCUMENT_ROOT'] . "/covers/profile/" . $imageFileNameVar . "-" . $user['user_id'] . "-" . $randImageNum . "-photo." . $_POST['image_extension'];
                $imageName = str_replace($_SERVER['DOCUMENT_ROOT'] . "/covers/profile/", "", $file);
                $filePath = $baseDir . 'logos/profile/';
                break;
        }

        //we connect to ftp
        $BDFtpManager = new BDFtpManager(
            brilliantDirectories::getDatabaseConfiguration('website_user'),
            brilliantDirectories::getDatabaseConfiguration('website_pass'),
            brilliantDirectories::getDatabaseConfiguration('ftp_server')
        );
        //we set our base folders
        $BDFtpManager->loadCommonImageFolders();
        if (addonController::isAddonActive('profile_cover_photo') && $_POST['module_action'] == "upload_cover_main") {
            //we execute upload
            $BDFtpManager->setCurrentFolder('/public_html/covers/profile');
            //open folder permissions
            $BDFtpManager->openFolderPermission(true);
        }

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
        $imageValidator = new imageValidator(
            imageValidator::IMAGE,
            $imageObject,
            websiteSettingsController::getFileUploadSizeByType(websiteSettingsController::UPLOAD_FILE_TYPE) * 1000000,
            255,
            $file
        );

        //we set static variables for the log system
        imagesUploaderLog::setStatiVariables(
            $w['website_id'],
            brilliantDirectories::getDatabaseConfiguration('website_user'),
            brilliantDirectories::getDatabaseConfiguration('website_pass'),
            brilliantDirectories::getDatabaseConfiguration('ftp_server')
        );

        $imageValidator->setIsAdmin(false);
        $imageValidator->setUserId($user['user_id']);
        $imageValidator->setPathFrom('Bootstrap Theme - Account - Profile Image Upload');

        $success = $imageValidator->validate();

        //if new file was uploaded correctly, delete old picture form other member
        if ($success !== FALSE) {

            $otherUserWhere = array(
                array('value' => $currentRecord['file'] , 'column' => 'file', 'logic' => '='),
                array('value' => $user['user_id'] , 'column' => 'user_id', 'logic' => '!=')
            );
            $otherUserCheck = bd_controller::users_photo()->getCount('photo_id', $otherUserWhere);
            if($otherUserCheck == 0){
                if ($_POST['module_action'] == "upload_image_main") {

                    if (@filesize($_SERVER['DOCUMENT_ROOT'] . "/pictures/profile/" . $currentRecord['file'])) {
                        @unlink($_SERVER['DOCUMENT_ROOT'] . "/pictures/profile/" . $currentRecord['file']);
                    }
                    if (@filesize($_SERVER['DOCUMENT_ROOT'] . "/pictures/social_media/" . $currentRecord['file'])) {
                        @unlink($_SERVER['DOCUMENT_ROOT'] . "/pictures/social_media/" . $currentRecord['file']);
                    }

                } else if ($_POST['module_action'] == "upload_logo_main") {

                    if (@filesize($_SERVER['DOCUMENT_ROOT'] . "/logos/profile/" . $currentRecord['file'])) {
                        @unlink($_SERVER['DOCUMENT_ROOT'] . "/logos/profile/" . $currentRecord['file']);
                    }
                    if (@filesize($_SERVER['DOCUMENT_ROOT'] . "/logos/social_media/" . $currentRecord['file'])) {
                        @unlink($_SERVER['DOCUMENT_ROOT'] . "/logos/social_media/" . $currentRecord['file']);
                    }
                } else if ($_POST['module_action'] == "upload_cover_main") {
                    if (@filesize($_SERVER['DOCUMENT_ROOT'] . "/covers/profile/" . $currentRecord['file'])) {
                        @unlink($_SERVER['DOCUMENT_ROOT'] . "/covers/profile/" . $currentRecord['file']);
                    }
                }
            }
        } else {
            unlink($file);
        }

        if (count($currentRecord) > 0 && ($_POST['module_action'] == "upload_image_main" || $_POST['module_action'] == "upload_logo_main" || $_POST['module_action'] == "upload_cover_main")) {
            //create the profile image record  for this upload
            mysql(brilliantDirectories::getDatabaseConfiguration('database'), "DELETE FROM
                    `users_photo`
                WHERE
                    photo_id = '" . $currentRecord['photo_id'] . "'");
        }
        if ($success !== false && $_POST['module_action'] == "upload_image_main" || $_POST['module_action'] == "upload_logo_main" || $_POST['module_action'] == "upload_cover_main") {
            switch ($_POST['module_action']) {
                case 'upload_image_main':
                    $photoTypeInsert = "photo";
                    break;
                case 'upload_cover_main':
                    $photoTypeInsert = "cover_photo";
                    break;
                case 'upload_logo_main':
                default:
                    $photoTypeInsert = "logo";
                    break;
            }
            $updateQuery = "INSERT INTO
                    `users_photo`
                SET
                    user_id = '" . $user['user_id'] . "',
                    file = '" . strip_tags($imageName) . "',
                    type ='" . $photoTypeInsert . "'";
            mysql(brilliantDirectories::getDatabaseConfiguration('database'), $updateQuery);
            users_controller::trackPhotoUpload($user,$photoTypeInsert);
        }
    }
    if ($success !== FALSE) {
        $response[] = $success;
        $response['status'] = $success;
        /*
        $response[] = $file;
        $response[] = error_get_last();
        $response[] = $currentRecord;
        $response[] = $user;
        $response[] = $_POST;
        */
    } else {
        $response[] = $success;
        $response['status'] = $success;
        $response['message'] = "Unable to save the file";
        $response[] = "Unable to save the file";
        /*
        $response[] = error_get_last();
        $response[] = $currentRecord;
        $response[] = $user;
        $response[] = $_POST;
        */
        
    }
    echo json_encode(bdHtmlSpecialCharsFrontend($response));
    exit;
}

if ($_POST['module_action'] == "delete_image" && $_POST['user_token'] != "" && $_POST['image_type'] != "") {
    //get the userInfo
    $currentUser = getUser($_POST['user_token'], $w);
    //get the photo information
    $photoInfoQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
            *
        FROM
            `users_photo`
        WHERE
            user_id = '" . $currentUser['user_id'] . "'
        AND
            type = '" . $_POST['image_type'] . "'");

    if (mysql_num_rows($photoInfoQuery) > 0) {
        $imageInfo = mysql_fetch_assoc($photoInfoQuery);

        switch ($_POST['image_type']) {
            case 'photo':
                $mainPath = $_SERVER['DOCUMENT_ROOT'] . "/pictures/profile/" . $imageInfo['file'];
                $socialPath = $_SERVER['DOCUMENT_ROOT'] . "/pictures/social_media/" . $imageInfo['file'];
                break;
            case 'logo':
                $mainPath = $_SERVER['DOCUMENT_ROOT'] . "/logos/profile/" . $imageInfo['file'];
                $socialPath = $_SERVER['DOCUMENT_ROOT'] . "/logos/social_media/" . $imageInfo['file'];
                break;
            case 'cover_photo':
                $mainPath = $_SERVER['DOCUMENT_ROOT'] . "/covers/profile/" . $imageInfo['file'];
                break;
        }
        //delete all images attached to this name

        // Check if another user is currently using that image to not delete it
        $otherUserWhere = array(
            array('value' => $imageInfo['file'] , 'column' => 'file', 'logic' => '='),
            array('value' => $currentUser['user_id'] , 'column' => 'user_id', 'logic' => '!=')
        );
        $otherUserCheck = bd_controller::users_photo()->get($otherUserWhere);
        if($otherUserCheck === false){
            if (@filesize($mainPath)) {
                @unlink($mainPath);
            }
            if (@filesize($socialPath)) {
                @unlink($socialPath);
            }
        }
        //delete record from the database
        mysql(brilliantDirectories::getDatabaseConfiguration('database'), "DELETE FROM
                `users_photo`
            WHERE
                user_id = '" . $currentUser['user_id'] . "'
            AND
                type = '" . $_POST['image_type'] . "'");
        $responseArray['status'] = "success";
        $responseArray['text'] = "%%%photo_upload_image_deleted%%%";
        $responseArray['title'] = "%%%system_success_label%%%";

    } else {
        $responseArray['status'] = "error";
        $responseArray['text'] = "%%%photo_upload_no_image%%%";
        $responseArray['title'] = "%%%error_label%%%";
    }
    echo json_encode($responseArray);
    exit;
}
//calculate the classes of the image and logo uploader based on the membership level permissions
if ($subscription['show_profile_photo'] == 1 && $subscription['show_logo_upload'] == 1) {
    $widthClass = "col-md-6";

} else {
    $widthClass = "col-md-12";
}
?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cropper/4.0.0/cropper.min.css">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<?php if ($label['my_profile_photos'] != "") { ?>
<h3 class="photoUploadTitle">%%%my_profile_photos%%%</h3>
<?php } if ($label['photo_accepted_formats'] != "") { ?>
<div class="alert bg-primary fpad photo_accepted_formats">
    <b>%%%photo_accepted_formats%%%</b> %%%list_photo_accepted_formats%%%
</div>
<?php } ?>
<div class="alert bg-primary fpad photo_accepted_formats">
    <a href="/photo-guidelines" target="_blank"><span style="color:#fa6131;"><strong>Click Here</strong></span></a> to read the GayWellness photo guidelines

</div>
<div class="row">
    <?php
    if ($subscription['show_profile_photo'] == 1) { ?>
        <div class="<?php echo $widthClass; ?>">
            
            <?php
            //show current image if any
            if ($user_data['photo_file'] != "") {
                ?>
                <div class="current-photo-container well">
                    <h3 class="text-center">%%%account_listing_tab_2%%%</h3>
                    <p>%%%profile_photo_recommended_size%%%</p>
                    <img class="bmargin" src="<?php echo $user_data['photo_file']; ?>">
                    <div class="clearfix"></div>
                    <?php
                    $userInfo = bd_controller::user()->get($user_data['user_id'], 'user_id');
                    $userProfession = bd_controller::list_professions()->get($userInfo->profession_id, 'profession_id');
                    if ($user_data['photo_file'] != $w['default_profile_image'] && $user_data['photo_file'] != $userProfession->image) { ?>
                        <button class="btn btn-lg btn-primary change-current-image">%%%change_photo_label%%%</button>
                        <button class="btn btn-lg btn-danger delete-current-image" data-imgtype="photo">
                            %%%delete_photo_label%%%
                        </button>
                        <?php
                    } else { ?>
                        <button class="btn btn-lg btn-primary change-current-image">%%%upload_photo_label%%%</button>
                        <?php
                    } ?>
                </div>
            <?php } ?>
            
        </div>
        <?php
    }
    if ($subscription['show_logo_upload'] == 1) { ?>
        <div class="<?php echo $widthClass; ?>">
            <?php
            if ($user_data['logo_file'] != "") { ?>
                <div class="current-logo-container well">
                    <h3 class="text-center"> %%%profile_logo_label%%% </h3>
                    <p>%%%profile_logo_recommended_size%%%</p>
                    <img class="bmargin" src="<?php echo $user_data['logo_file']; ?>">
                    <div class="clearfix"></div>
                    <?php
                    if ($user_data['logo_file'] != $w['default_logo_image'] && $user_data['default_category_image'] === false) { ?>
                        <button class="btn btn-lg btn-primary change-current-logo">%%%change_logo_label%%%</button>
                        <button class="btn btn-lg btn-danger delete-current-image" data-imgtype="logo">
                            %%%delete_logo_label%%%
                        </button>
                        <?php
                    } else { ?>
                        <button class="btn btn-lg btn-primary change-current-logo">%%%upload_logo_label%%%</button>
                        <?php
                    } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<?php
if ($subscription['show_profile_photo'] == 1) { ?>
<!-- Profile Photo Upload Modal -->
<div class="modal fade" id="profilePhotoUpload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-default bold pull-right profile-photo-close-upload col-md-4" data-dismiss="modal">%%%close_label%%%</button>
                <button style="display:none;" type="button" class="btn btn-primary bold croper-action profile-photo-confirm col-md-8" data-method="crop" disabled>
                    <span class="glyphicon glyphicon-cloud-upload"></span>
                    %%%crop_image_label%%%
                </button>
                <h4 class="modal-title" id="myModalLabel">%%%modal_profile_photo_upload%%%</h4>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                <div class="image-preview hidden-uploader">
                    <!-- image-preview-input -->
                    <div class="fpad-xl fa-2x btn btn-primary btn-block image-preview-input">
                        <div class="fpad-xl nohpad weight-light font-lg">                       
                            <span class="glyphicon glyphicon-cloud-upload"></span>
                            <span class="image-preview-input-title"> %%%choose_image_label%%%</span>
                            <input type="file" accept="image/gif, image/png, image/jpeg, image/jpg"
                            id="profile-img-upload-input" name="input-file-preview"/> <!-- rename it -->
                        </div>
                    </div>
                </div><!-- /input-group image-preview [TO HERE]-->

                <div class="interactive-container hidden-interactive-container" style="display:none;">
                <div class="profile-preview-image">
                    <img src="" class="preview-image-img-tag">
                </div>
                <div class="image-controls">
                    <div class="btn-group ">
                        <button type="button" class="btn btn-primary croper-action" data-method="zoom" data-option="0.1"
                                title="%%%photo_zoom_in%%%" data-origin="photo">
                            <span class="docs-tooltip" title="">
                                <span class="fa fa-search-plus"></span>
                            </span>
                        </button>
                        <button type="button" class="btn btn-primary croper-action" data-method="zoom"
                                data-option="-0.1" title="%%%photo_zoom_out%%%" data-origin="photo">
                            <span class="docs-tooltip" title="">
                                <span class="fa fa-search-minus"></span>
                            </span>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary croper-action" data-method="rotate"
                                data-option="-45" title="%%%photo_rotate_left%%%" data-origin="photo">
                            <span class="docs-tooltip" title="">
                                <span class="fa fa-rotate-left"></span>
                            </span>
                        </button>
                        <button type="button" class="btn btn-primary croper-action" data-method="rotate"
                                data-option="45" title="%%%photo_rotate_right%%%" data-origin="photo">
                            <span class="docs-tooltip" title="">
                                <span class="fa fa-rotate-right"></span>
                            </span>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary croper-action" data-method="scaleX"
                                data-option="-1" title="%%%photo_flip_horizontal%%%" data-origin="photo">
                            <span class="docs-tooltip" title="">
                                <span class="fa fa-arrows-h"></span>
                            </span>
                        </button>
                        <button type="button" class="btn btn-primary croper-action" data-method="scaleY"
                                data-option="-1" title="%%%photo_flip_vertical%%%" data-origin="photo">
                            <span class="docs-tooltip" title="">
                                <span class="fa fa-arrows-v"></span>
                            </span>
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }
if ($subscription['show_logo_upload'] == 1) { ?>
<!-- Logo Upload Modal -->
<div class="modal fade" id="logoUpload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-default bold pull-right col-md-4" data-dismiss="modal">%%%close_label%%%</button>
                <button style="display:none;" type="button" class="btn btn-primary bold upload-logo col-md-8" data-method="crop" disabled>
                    <span class="glyphicon glyphicon-cloud-upload"></span>
                    %%%confirm_logo_upload%%%
                </button>
                <h4 class="modal-title" id="myModalLabel">%%%modal_logo_upload%%%</h4>
                <div class="clearfix"></div>
            </div>
            
            <div class="modal-body">
                <div class="logo-preview">
                    <img src="" id="logo-preview-img-tag">
                    <!-- image-preview-input -->
                    <div class="fpad-xl fa-2x btn btn-primary btn-block logo-preview-input">
                        <div class="fpad-xl nohpad weight-light font-lg">                       
                            <span class="glyphicon glyphicon-cloud-upload"></span>
                            <span class="logo-preview-input-title"> %%%choose_logo_label%%%</span>
                            <input type="file" accept="image/gif, image/png, image/jpeg, image/jpg"
                            id="logo-img-upload-input" name="input-file-preview"/> <!-- rename it -->
                        </div>
                    </div>
                </div><!-- /input-group image-preview [TO HERE]--> 
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- COVER PHOTO CODE START -->

<?php
if ($subscription['coverPhoto'] == 1) {
    addonController::showWidget('profile_cover_photo', '1fff04a691e382b90a3fd561ebd98b30', ''); ?>


<!-- Cover Photo Modal -->
<div class="modal fade" id="coverPhotoUpload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-default bold pull-right col-md-4" data-dismiss="modal">%%%close_label%%%</button>
                <button style="display:none;" type="button" class="btn btn-primary croper-action cover-confirm col-md-8" data-method="crop" data-origin="cover" disabled>
                    <span class="glyphicon glyphicon-cloud-upload"></span>
                    %%%confirm_cover_upload%%%
                </button>
                <h4 class="modal-title" id="myModalLabel">%%%modal_cover_photo_upload%%%</h4>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
            <?php
            if ($subscription['coverPhoto'] == 1) {
                addonController::showWidget('profile_cover_photo', 'd91ac50656d0e685381a752590434f98', '');
            } ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<div class="next-step-container">
    <a href="/account/resume" class="btn btn-success btn-lg btn-block">
        <small>I confirm I have uploaded a main profile picture with good resolution, showing my face, and adhering to guidelines (mentioned above)</small> - %%%continue_label%%%
    </a>
</div>
<canvas id="profile_img_canvas" class="pre-process-canvas"></canvas>
<canvas id="profile_social_canvas" class="pre-process-canvas"></canvas>
<canvas id="profile_logo_canvas" class="pre-process-canvas"></canvas>
<canvas id="profile_logo_social_canvas" class="pre-process-canvas"></canvas>
<canvas id="profile_cover_canvas" class="pre-process-canvas"></canvas>
<canvas id="profile_cover_social_canvas" class="pre-process-canvas"></canvas>
<span data-usertoken="<?php echo $user_data['token']; ?>" id="usertokenspan"></span>
<style type="text/css">
    .sweet-alert button.cancel{
        background-color: #dd3333 !important;
    }
    .image-preview-tag {
        margin: 0 auto;
        overflow: hidden;
        width: 100%;
    }
    .profile-preview {
        height: <?php echo $pictureSettings[pictures][profile][height]; ?>px;
    }
    .thumbnails-preview {
        height: <?php echo $pictureSettings[pictures][thumbnails][height]; ?>px;
    }
    .preview-container p {
        font-weight: bold;
        font-size: 15px;
        margin-top: 10px;
    }
    .image-preview-input input[type=file],
    .logo-preview-input input[type=file],
    .cover-preview-input input[type=file] {
        cursor: pointer;
        filter: alpha(opacity=0);
        font-size: 20px;
        margin: 0;
        opacity: 0;
        padding: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: 100%;
        height: 100%;
    }
    .image-preview-input-title,
    .cover-preview-input-title,
    .logo-preview-input-title {
        margin-left: 2px;
    }
    .btn.btn-default.image-preview-input {
        width: 100%;
    }
    .input-group.image-preview {
        margin: 10px 0px;
    }
    .image-controls, .image-controls-cover {
        margin: 10px 0px;
        text-align:center;
    }
    .image-action-btn {
        background: <?php echo $wa[custom_4]; ?>;
        border: <?php echo $wa[custom_4]; ?>;
    }
    .image-action-btn:hover {
        background: <?php echo $wa[custom_69]; ?>;
        border: <?php echo $wa[custom_69]; ?>;
    }
    .image-action-btn:focus {
        background: <?php echo $wa[custom_69]; ?>;
        border: <?php echo $wa[custom_69]; ?>;
    }
    .image-action-btn:active {
        background: <?php echo $wa[custom_69]; ?> !important;
        border: <?php echo $wa[custom_69]; ?> !important;
    }
    .preview-container {
        background: #fff;
        border-radius: 4px;
        border: 1px solid #EEE;
        margin: 5px 0px;
        padding: 10px;
        display: none;
        text-align: center;
    }
    .hidden-uploader {
        display: none;
    }
    .hidden-interactive-container {
        display: none!important;
    }
    .current-photo-container,
    .current-logo-container,
    .current-cover-container {
        text-align: center;
        padding: 19px;
        display: block;
        margin-bottom: 15px;
    }
    .current-photo-container img,
    .current-logo-container img,
    .current-cover-container img {
        border: 1px solid #ddd;
        border-radius: 4px;
        margin: 4px;
    }
    .change-current-image,
    .change-current-logo,
    .change-current-cover {
        display: inline;
        font-size: 15px;
        width: 49%;
    }
    .edit-current-image,
    .edit-current-logo,
    .edit-current-cover {
        display: inline-block;
        font-size: 15px;
        width: 30%;
    }
    .delete-current-image,
    .delete-current-logo {
        display: inline-block;
        width: 49%;
        font-size: 15px;
    }
    input.form-control.image-preview-filename {
        display: none !important;;
    }
    .image-controls, .image-controls-cover {
        display: none;
    }
    .upload-btn-container {
        margin-top: 7px;
        width: 100%;
    }
    .upload-btn {
        width: 100%;
    }
    input.form-control.logo-preview-filename, input.form-control.cover-preview-filename {
        width: 45%;
        display: inline-block;
        border-radius: 4px 0px 0px 4px !important;
    }
    .input-group.logo-preview, .input-group.cover-preview {
        display: none;
    }
    .logo-controls-btn, .cover-controls-btn {
        margin: 10px 0px;
        display: none;
    }
    .logo-controls-btn .upload-logo, .logo-controls-btn .upload-cover {
        width: 100%;
        margin-top: 8px;
    }
    .next-step-container {
        margin-top: 20px;
    }
    .pre-process-canvas {
        display: none;
    }
    #logo-preview-img-tag, #cover-preview-img-tag {
        margin: 0 auto;
        display: block;
    }
    @media (max-width: 1030px) {
        .change-current-image,
        .change-current-logo,
        .change-current-cover {
            display: block;
            font-size: 15px;
            width: 90%;
            margin: 0 auto 15px auto;
        }
        .delete-current-image,
        .delete-current-logo,
        .delete-current-cover {
            display: block;
            width: 90%;
            font-size: 15px;
            margin: 0 auto 15px auto;
        }
    }
</style>