<?php
$complete = 'Published';
$incomplete = 'Incomplete';
header('Access-Control-Allow-Origin: *');
$user_data = getUser($_COOKIE['userid'], $w); // Get Current User Data
$user_full_name = $user_data['first_name'] . ' ' . $user_data['last_name'];

  function activity_log($action, $reference_table, $reference_id, $description = '') {
      $user_id = isset($_COOKIE['userid']) ? intval($_COOKIE['userid']) : 0;
      $user_name = isset($user_full_name) ? mysql_real_escape_string($user_full_name) : (isset($user_data['company']) ? $user_data['company'] : $user_data['email']);

      $action            = mysql_real_escape_string($action);
      $reference_table   = mysql_real_escape_string($reference_table);
      $reference_id      = intval($reference_id);
      $description       = mysql_real_escape_string($description);
      $ip                = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
      $agent             = isset($_SERVER['HTTP_USER_AGENT']) ? mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']) : 'unknown';
      $now               = date('Y-m-d H:i:s');
      $sql = "INSERT INTO activity_log 
              (user_id, user_name,user_type, `action`, reference_table, reference_id, `description`, ip_address, user_agent, created_at)
              VALUES 
              ('$user_id', '$user_name', 'member', '$action', '$reference_table', '$reference_id', '$description', '$ip', '$agent', '$now')
            ";

      mysql_query($sql);
  }
  
if (isset($_POST['add_booth'])) {
    $file = $_POST['booth_cropedImg'];
    $file_pre = $_POST['booth_cropedImg'];
    $time_sloat = $_POST['time_sloat'];
    $time_sloat_arr = explode('-', $time_sloat);
    $start_time = trim($time_sloat_arr[0]);
    $end_time = trim($time_sloat_arr[1]);
    $staus = '1';
    $insert_query = mysql_query("INSERT INTO `live_events_posts`(`post_id`, `user_id`, `staus`, `event_name`, `start_date`, `end_date`, `start_time`, `end_time`, `video_option`, `thumb`, `thumb_booth`, `booth_priority`, `video_link`, `embed`, `replay_embed`, `presentation_pdf_link`, `event_location`) VALUES ('" . $_POST['post_id'] . "','" . $_POST['user_id'] . "', '" . $staus . "','" . mysql_real_escape_string($_POST['event_name']) . "','" . $_POST['start_date'] . "', '" . $_POST['end_date'] . "', '" . $start_time . "', '" . $end_time . "', '" . $_POST['video_option'] . "', '" . $file_pre . "','" . $file . "', '" . $_POST['booth_priority'] . "', '" . $_POST['video_link'] . "', '" . $_POST['embed'] . "', '" . mysql_real_escape_string($_POST['event_name']) . "', '" . $_POST['pdf_link'] . "', '" . $_POST['event_location'] . "')");
    
    if ($insert_query) {
        $lep_id = mysql_insert_id();
        //activity_log('Add Supplier Card', 'live_events_posts', $lep_id, 'Supplier Card Added by ' . $user_full_name);
        header("Location: https://www.motiv8search.com/account/add-supplier-card/view");
        exit;
    }
}


//Update Booth
if (isset($_POST['update_booth'])) {
    $time_sloat = $_POST['time_sloat'];
    //$file = $_POST['booth_cropedImg'];
   // $file_pre = $_POST['booth_cropedImg'];  
    $time_sloat_arr = explode('-', $time_sloat);
    $start_time = trim($time_sloat_arr[0]);
    $end_time = trim($time_sloat_arr[1]);
    //1=Draft
    //2=Approved
    //$staus = '1';
    $staus = '2';
    $update_query = "UPDATE `live_events_posts` SET `staus`='$staus', `event_name` = '" . mysql_real_escape_string($_POST['event_name']) . "', `event_description` = '" . mysql_real_escape_string($_POST['event_description']) . "',`event_summary` = '" . mysql_real_escape_string($_POST['event_summary']) . "',`presentation_pdf_link` = '" . $_POST['pdf_link'] . "'  ";

    // if ($file != "") {
    //     $update_query .= " , `thumb_booth` = '" . $file . "' ";
    // }
    // if ($file_pre != "") {
    //     $update_query .= " , `thumb` = '".$file_pre."' ";
    // }

    $update_query .= " WHERE `live_events_posts`.`id` = " . $_POST['update_booth_id'];
    //echo $update_query;
    $insert_video = mysql_query($update_query);
    $lep_id = $_POST['update_booth_id'];
    $show_saved_message = false;

    if ($insert_video) {
        $show_saved_message = true;
        activity_log('Update', 'live_events_posts', $lep_id, 'Supplier Card / Booth Updated by ' . $user_full_name);

         /* 
         * Sending Email to Current User for Inviting Attendees as Supplier for this Booth
         * - Constructs the email data
         * - Retrieves the registration form token
         * - Generates the attending URL
         * - Prepares and sends the email notification
        */
        /*
        $supplier_id    = $user_data['user_id'];
        $staff_id       = $user_data['user_id'];
        $staff_name     = $user_data['first_name']." ".$user_data['last_name'];
        $staff_email    = $user_data['email'];
        $staff_company  = $user_data['company'];

        $post_title     = $_POST['event_name']; 
        $post_id        = $_POST['update_post_id'];
        $post_date      = $_POST['update_start_date'];
        
        $token          = bin2hex(random_bytes(16)); 
        $expires        = date('Y-m-d H:i:s', strtotime($post_date));

        $selectSql = "SELECT staff_email FROM attending_staff_attendance WHERE post_id = '$post_id' AND supplier_id = '$supplier_id' AND staff_email = '$staff_email'";
        $insertSql = "INSERT INTO attending_staff_attendance (supplier_id, post_id, lep_id, staff_id, staff_email, `status`, token, expries) VALUES ('$supplier_id', '$post_id', '$lep_id', '$staff_id', '$staff_email','Invited', '$token', '$expires')";   

        $query  = mysql_query($selectSql);
        $result = mysql_num_rows($query);
        
        if ($result == 0) {
            mysql_query($insertSql);
        }
        
        $applicationTokenQuery = mysql_query("SELECT token FROM create_application_pages WHERE event_id = '$post_id' AND `type` = 'registration form'");
        $applicationTokenResult = mysql_fetch_assoc($applicationTokenQuery);
        $applicationtoken = $applicationTokenResult['token'];
	    $attendingUrl = "https://www.motiv8search.com/attending-supplier-staff-registration?ref=".$applicationtoken."&token=".$token;
        // Prepare email data
        $w['eventname']   		= $post_title;
		$w['staffname']   		= $staff_name;
		$w['suppliercompany']	= $staff_company;
	    $w['supplier_token']    = $attendingUrl;
		$w['event_date']  		= date("d-M-Y H:i:s", strtotime($post_date));
        $emailPrepareOne  = prepareEmail('attending_staff_invitation', $w);
        // Send email
        sendEmailTemplate($w['website_email'], $staff_email, $emailPrepareOne['subject'], $emailPrepareOne['html'], $emailPrepareOne['text'], $emailPrepareOne['priority'], $w);
        */
    }

}


if ($pars[1] == 'edit-supplier-card' && $pars[2] != '' && $_GET['id'] != '') {

    $supplier_card_id = $_GET['id'];
    //$sql = "SELECT lep.*, dp.* FROM live_events_posts AS lep JOIN data_posts AS dp ON lep.post_id = dp.post_id WHERE lep.id = '" . $supplier_card_id . "'";

    $sql = "SELECT * FROM `live_events_posts` WHERE id = '" . $supplier_card_id . "'";

    $single_row = mysql_fetch_assoc(mysql_query($sql));
    
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 style="font-size: 20px;font-weight: 600; margin: 25px 0px;">Your Suppplier Card Preview is what the Manufacturer's Staff see for the event.</h2>
        </div>
    </div>
    <div class="row">
        <?php
        if ($show_saved_message) { ?>
            <div class="alert alert-success center-block vmargin alert-dismissible bmargin" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                <i class="fa fa-check-circle"></i>
                Saved
            </div>
        <? } ?>

        <div class="col-sm-6 col-md-6 ">
            <div class="card-input well">
                <h4>Add Supplier Card</h4>
                <hr>
                <form method="post" enctype="multipart/form-data" id="add_supplier_card">
                    <div class="form-group">
                        <label for="event">Status </label>
                        <?php
                        if ($single_row[staus] == 2) {
                            echo '<span style="font-size: 100%; background-color: #5cb85c;" class="label label-success">' . $complete . '</span>';
                        }

                        if ($single_row[staus] == 1) {
                            echo '<span style="font-size: 100%;" class="label label-danger">' . $incomplete . '</span>';
                        }
                        ?>
                    </div>
                    <?php
                    $get_posts_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `data_posts` WHERE post_id = '" . $single_row['post_id'] . "'"));
                    ?>
                    <div class="form-group">
                        <label for="event">Event </label>
                        <div class="form-control">
							<?php echo substr($get_posts_query['post_title'], 0, 47) . '...'; ?>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" value="<?= $_COOKIE['userid'] ?>">
                    <?php
                    if ($single_row['video_option'] == 'link' || $single_row['video_option'] == 'other' || $single_row['video_option'] == 'superbooth' || $single_row['video_option'] == 'none') { 
                        
                        if ($single_row['video_option'] != 'other' && $single_row['video_option'] != 'superbooth' && $single_row['video_option'] != 'none') {
                        ?>
                        <div class="form-group">
                            <label for="presentation-title">* Presentation Title</label>
                            <input type="text" class="form-control" id="presentation-title" name="event_name" data-preview="Presentation Title" required value="<?php  echo htmlspecialchars($single_row['event_name'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="presentation-description">* Promotional description of what you'll be showing at the event</label>

                            <textarea rows="6" class="form-control" id="presentation-description" name="event_description" data-preview="Company Description" required><?= $single_row['event_description'] ?></textarea>
                            <!-- <span class="help-block form-field-help-block">350 Character Limit</span> -->
                            <p class="help-block form-field-help-block">Character count: <span id="charCount">0</span></p>
                            <span class="help_text"><small>Please enter a short, snappy overview of what you will be showing at the event.</small></span>
                            <span id="description-error" class="error-message"></span>
                        </div>
                        <? /*<input type="hidden" name="event_description" value="<?=$single_row['event_description']?>"><?= $single_row['event_description'] ?> */ ?>
                    <?php }
                    if ($single_row['video_option'] == 'none' || $single_row['video_option'] == 'superbooth') { ?>
                        
                        <input type="hidden" name="event_name" value="<?php echo  $get_posts_query['post_title']; ?>">
                    <? } ?>
                    <div class="form-group">
                        <label for="thumbnail-booth">* Thumbnail for Supplier Card</label>
                         <?php
                        if ($single_row['thumb_booth'] != '') {
                            $img = $single_row['thumb_booth'];
                            $src = 'https://www.motiv8search.com/images/events/'.$single_row['thumb_booth'];
                        ?>
                            <a id="img-preview-btn" style="margin-top: 4px;visibility: hidden;" target="_blank" href="https://www.motiv8search.com/images/events/<?= $img ?>" class="btn btn-info">View Image</a>

                        <? }else{ $src ='/images/IMG-20230916-WA0016.jpg'; } ?>
                        <div class="form-group data-upload-image col-xs-12 col-sm-12 pull-right" style="z-index: 1;">
                            <img id="preview_img" class='img-thumbnail center-block' src="<?= $src ?>" alt="preview image">
                            <div class="filebutton tmargin" style="width:100%;overflow:hidden;">
                                <div class="bootstrap-filestyle input-group" id="upload_img">
                                    <span class="group-span-filestyle ">
                                        <label class="btn btn-primary" for="thumbnail-booth">
                                            <span class="glyphicon glyphicon-folder-open"></span>
                                            %%%upload_from_computer%%%
                                        </label>
                                    </span>
                                </div>
                                <button style="display:none;" type="button" class="btn btn-primary bold btn-lg center-block croper-action col-md-6" id="crop_submit" data-method="crop"  data-option="crop" title="Crop" data-origin="photo" disabled>
                                    Crop & Upload
                                </button>
                                <button style="display:none;" type="button" class="btn btn-default bold btn-lg center-block croper-action" id="cancel_submit" data-method="cancel" data-option="cancel" title="Crop" data-origin="photo" disabled>
                                cancel <i class="fa fa-close"></i>
                                </button>
                                <input type="file" class="filestyle upload-form-field upload-form-field--hidden" data-buttonText="%%%upload_from_computer%%%" data-input="false" accept="image/jpeg, image/jpg, image/png, image/gif, image/bmp" id="thumbnail-booth" name="thumbnail_booth" value="<?= $single_row['thumb_booth'] ?>" <?php //echo ($single_row['thumb_booth'] != '') ? '' : 'required'; ?> >
                                
                                <input type="hidden" name="booth_cropedImg" id="booth_cropedImg" value="<?= $single_row['thumb_booth'] ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="interactive-container hidden">
                                <div class="image-controls">
                                    <div class="btn-group hidden">
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
                                    <div class="btn-group"style=" width: 77%;">
                                        <input type="range" id="zoomRange" min="0.1" data-method="zoom" max="2" step="0.1" value="0.5">
                                    </div>
                                    <div class="btn-group hidden">
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
                                    <div class="btn-group hidden">
                                        <button type="button" class="btn btn-primary croper-action" data-method="setDragMode"
                                                data-option="move" title="Move" data-origin="photo">
                                            <span class="docs-tooltip" title="">
                                                <span class="fa fa-arrows-alt"></span>
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-primary croper-action" data-method="crop"
                                                data-option="crop" title="Crop" data-origin="photo">
                                            <span class="docs-tooltip" title="">
                                            <span class="fa fa-crop"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <span class="help-block text-center">Recommended Size: 600 by 370 pixels</span>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <?php if ($single_row['video_option'] == 'link') { ?>
                        <div class="form-group link-container">
                            <label for="presentation-pdf-link">Presentation PDF/PPTX URL</label>
                            <p class="account-tip-vk">Please paste a URL link here (non-expiring link)</p>
                            <input type="url" name="pdf_link" class="form-control" id="presentation-pdf-link" value="<?php echo $single_row['presentation_pdf_link'] ?>">
                        </div>
                    <?php } ?>

                    <?php if ($_GET['id'] != '') { ?>
                        <input type="hidden" name="update_booth_id" value="<?= $_GET['id'] ?>">
                        <input type="hidden" name="update_post_id" value="<?= $single_row['post_id'] ?>">
                        <input type="hidden" name="update_start_date" value="<?= $single_row['start_date'] ?>">
                        <input type="submit" name="update_booth" value=" Save " class="btn btn-primary croper-action" id="update_booth" data-method="crop"  data-option="crop" title="Crop" data-origin="photo">

                    <? } else { ?>
                        <input type="submit" name="add_booth" value=" Save to Draft " class="btn btn-primary" id="add_booth" >
                    <? } ?>
                </form>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 well wellv2" style="position: sticky; top: 50px;">
            <div class="loading-preview">
                <img style="width: 50px;" src="https://www.motiv8search.com/images/loading.gif" alt="loading..">
            </div>
            <div class="card-input">
                <h4>Your Supplier Card Preview</h4>
                <hr>
            </div>
            <div class="company-thum thumbnail">
                <?php
                if ($single_row['video_option'] == 'link') {
                    $style_vibility = 'style="visibility: visible;"';
                }
                if ($single_row['video_option'] == 'superbooth' || $single_row['video_option'] == 'none' || $single_row['video_option'] == 'other') {
                    $style_vibility = 'style="visibility: hidden;"';
                }
                ?>
                <span class="presenting-ribbon video_option-radio-preview" <?= $style_vibility ?>> <?php if($single_row['post_id'] == '865'){ echo "Meet The Supplier"; }else{ echo "Presentation";} ?></span>
                <div>
                <?php
$supplier_card_id = $_GET['id'];

// First query to get data from live_events_posts table
$sql = "SELECT * FROM `live_events_posts` WHERE id = '" . $supplier_card_id . "'";
$single_row = mysql_fetch_assoc(mysql_query($sql));

// Get user_id from the first query result
$user_id = $single_row['user_id'];

// Second query to get the file column from users_photo table
//$sql2 = "SELECT file FROM `users_photo` WHERE user_id = '" . $user_id . "'";

$sql2 = "SELECT file FROM `users_photo` WHERE user_id = '" . $user_id . "' AND type = 'logo'";
$result2 = mysql_query($sql2);



$user_photo = mysql_fetch_assoc($result2);

// Check if there's a valid file and assign it to the img tag
$file = $user_photo['file'] ? $user_photo['file'] : 'default-image.jpg'; // Default image if no file is found
?>

<img class="company_logo" style="height: 60px;" src="https://www.motiv8search.com/logos/profile/<?php echo $file; ?>" alt="...">


                        <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var logo = document.querySelector(".company_logo");
                            
                            if (logo && (logo.src.includes("/images/No-Company-Logo---Logo-3.png") || logo.src.includes("/logos/profile/default-image.jpg"))) {
                                swal({
                                    title: "Oops!",
                                    text: "Please upload your logo!",
                                    type: "warning",
                                    showConfirmButton: true,
                                    allowEscapeKey: false,
                                    allowOutsideClick: false
                                }, function () {
                                    // This function runs when the "OK" button is clicked
                                    window.location.href = "/account/profile?redirect=/account/edit-supplier-card/edit?id=<?= $_GET[id] ?>";
                                });
                                var okButton = document.querySelector(".swal2-confirm");
                            if (okButton) {
                                okButton.addEventListener("click", function() {
                                    window.location.href = "/account/profile?redirect=/account/edit-supplier-card/edit?id=<?= $_GET[id] ?>";
                                });
                            }
                            }
                        });
                        </script>

					
                   <?php
                        if ($single_row['thumb_booth'] != '') {
                            $img = $single_row['thumb_booth'];
                            $src = 'https://www.motiv8search.com/images/events/'.$single_row['thumb_booth'];
                        }else{
                            $src ='/images/IMG-20230916-WA0016.jpg'; } ?>
                    <div class="company-thumb-img image-preview">
                        <img src="<?= $src ?>" alt="image-preview">
                    </div>
                    <div class="caption">
                        <p style="margin-bottom:5px; <?php echo ($single_row['video_option'] == 'none' || $single_row['video_option'] == 'superbooth') ? 'visibility: hidden;' : ''; ?>">
                            <strong>Time:</strong>
                            <span class="time_sloat-preview">
                                <?php echo $single_row[start_time] . '-' . $single_row[end_time]; ?>
                            </span>
                        </p>
                        <p style="display: flex; <?php echo ($single_row['video_option'] == 'none' || $single_row['video_option'] == 'superbooth') ? 'visibility: hidden;' : ''; ?>">
                            <strong style=" margin-right: 3px;">Location:</strong>
                            <span class="event_location-preview">
                                <?php echo ($single_row[event_location]) ? $single_row[event_location] : '****'; ?>
                            </span>
                        </p>
                        <?php
                        if ($single_row['video_option'] == 'other') {
                            echo '<h4 class="company_desc event_name-preview" style="text-align: center;">'.$single_row['event_name'].'</h4>';
                            echo '<p class="event_name-preview" style="text-align: center;">'.$single_row['event_description'].'</p>';
                        }



                        if ($single_row['video_option'] == 'link') { ?>
                            <h4 class="company_desc event_name-preview" style="text-align: center;">Presentation Title</h4>
                        <?php } 
                         if ($single_row['video_option'] == 'none' || $single_row['video_option'] == 'superbooth') { ?>
                            <h4 class="company_desc event_description-preview" style="text-align: center;">Promotional description of what you'll be showing at the event</h4>
                        <? } ?> 
                    </div>
                </div>
                <?php if ($single_row['video_option'] == 'link') { ?>
                    <div class="buttons-container">
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="https://www.motiv8search.com/<?php echo urldecode($user_data['filename']); ?>" style="display: block; padding-left: 7px; padding-right: 7.5px;" target="_blank" class="btn btn-primary " role="button">View Company</a>
                            </div>

                            <div class="col-xs-6">
                                <? /*<a target="_blank" class="btn <?php echo ($single_row['video_link']) ? 'btn-primary':'btn-secondary'; ?> video_link-btnpreview" <?php echo ($single_row['video_link']) ? '':'disabled'; ?> style="display: block; padding-left: 7px;" role="button" href="<?php echo ($single_row['video_link']) ? $single_row['video_link']:''; ?>">Join Online</a>*/ ?>
                                <a class="btn btn-secondary" disabled style="display: block; padding-left: 7px;">Join
                                    Online</a>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-xs-6">
                                <a target="_blank" class="btn btn-secondary disabled pdf_link-btnpreview" style="display: block; padding-left: 7px; padding-right: 7px;" role="button" href="">Presentation PDF</a>
                            </div>

                            <div class="col-xs-6">
                                <a target="_blank" class="btn btn-info" style="display: block; padding-left: 7px;" role="button" href="https://www.motiv8search.com/<?php echo urldecode($user_data['filename']); ?>/connect">Message</a>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="buttons-container">
                        <div class="row single-row-button">
                            <div class="col-xs-6">
                                <a href="https://www.motiv8search.com/<?php echo urldecode($user_data['filename']); ?>" style="display: block; padding-left: 7px; padding-right: 7.5px;" target="_blank" class="btn btn-primary " role="button">View Company</a>
                            </div>
                            <div class="col-xs-6">
                                <a target="_blank" class="btn btn-info" style="display: block; padding-left: 7px;" role="button" href="https://www.motiv8search.com/<?php echo urldecode($user_data['filename']); ?>/connect">Message</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .image-controls, .image-controls-cover {
        margin: 10px 0px;
        text-align:center;
    }
</style>
<script>
    <?php
    if ($single_row['video_option'] == 'link') { ?>
        $('.embed-container').hide();
    <?php
    } elseif ($single_row['video_option'] == 'embed') {
    ?>
        $('.link-container').hide();
        $('.thumb-container').hide();
    <?php
    }
    ?>
    // $('#edit-modal').modal('show');
    <?php if ($_GET[id] != '') { ?>
        $('.company-thum').hide();
        $('.loading-preview').show();
        //$('#add_supplier_card input, #add_supplier_card select, #add_supplier_card textarea').change();
        //$('#add_supplier_card input').change();
        $('#presentation-title').change();
        //var existingImageURL = $('#thumbnail-boothexistingImage').attr('src');
        //if (existingImageURL) {
        $('.image-preview .loading-spinner').show();
        setTimeout(function() {
            //$('.image-preview img').attr('src',existingImageURL);
            $('.company-thum').show();
            $('.loading-preview').hide();
            $('#presentation-title, #presentation-description').change();
        }, 1000);
        // setTimeout( function(){
        //       $('#presentation-title').change();
        // }  , 2000 );
        //}
        <? /* 
         setTimeout( function(){
         $('#select-event').change();
         }  , 2000 );
         setTimeout( function(){
         $("#time_sloat").val('<?php echo date('H:i',strtotime($single_row['start_time']))." - ".date('H:i',strtotime($single_row['end_time'])) ?>').trigger('change');
         }  , 5000 ); */ ?>
    <? } ?>
</script>

<? /*<script src="/directory/cdn/assets/bootstrap/js/bootstrap-progressbar.min.js"></script>*/ ?>
<script src="/directory/cdn/bootstrap/select2/3.5.2/select2.min.js"></script>
<script>
    $(document).ready(function() {

        $(".select-two").select2();

        const selectTwo = $(".select-two");

        selectTwo.on("invalid", function(event) {
            event.preventDefault();
            $(this).addClass("is-invalid");
            $(this).parent().find('.invalid-feedback').show();
        });

        selectTwo.change(function() {
            if ($(this).val()) {
                $(this).removeClass("is-invalid");
                $(this).parent().find('.invalid-feedback').hide();
            }
        });


        const textarea = document.getElementById("presentation-description");
        let isComposing = false;

        function trimText() {
            if (textarea.value.length > 200) {
                textarea.value = textarea.value.slice(0, 200);
            }
        }
        textarea.addEventListener("compositionstart", () => {
            isComposing = true;
        });

        textarea.addEventListener("compositionend", () => {
            isComposing = false;
            trimText();
        });

        textarea.addEventListener("input", () => {
            if (!isComposing) {
                trimText();
            }
        });



        //character limit of 350 for the "Company Description" textarea
        var descriptionTextarea = $('#presentation-description');
        var descriptionError = $('#description-error');

        descriptionTextarea.on('input', function(e) {
            var description = $(this).val();
            var characterLimit = 350;

            if (description.length > characterLimit) {
                descriptionError.text('Exceeded character limit');
                descriptionError.css('color', 'red');
                descriptionTextarea.css('border-color', 'red');
            } else {
                descriptionError.text('');
                descriptionError.css('color', '');
                descriptionTextarea.css('border-color', '');
            }
        });

        $('#add_supplier_card').on('submit', function(e) {
            var description = descriptionTextarea.val();
            var characterLimit = 350;

            if (description.length > characterLimit) {
                e.preventDefault(); // Prevent form submission
            }
        });
        //End of character limit of 350 for the "Company Description" textarea
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var upload_type = input.files[0].type;

                if (upload_type.indexOf("image") < 0) {
                    $("#myfile").filestyle('clear');
                    $('.img-thumbnail').css("display", "none");
                    $('.emptyphoto').css("display", "block");

                } else {
                    reader.onload = function(e) {
                        $('#preview_img').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                    $('.emptyphoto').css("display", "none");
                    $('#preview_img').css("display", "block");
                }

            }
        }
        // Thumbnail for Booth field validation
        function validateFileInput(fileInput) {
            var file = fileInput.files[0];
            var allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
            var maxFileSize = 5 * 1024 * 1024; // 5 MB

            if (!file) {
                // No file selected
                return true;
            }

            var fileName = file.name;
            var fileSize = file.size;
            var fileExtension = fileName.split('.').pop().toLowerCase();

            if (allowedExtensions.indexOf(fileExtension) === -1) {
                // Invalid file type
                $(fileInput).parent().find('.invalid-feedback').show().text('File type not allowed. Only ' + allowedExtensions.join(', ') + ' files are allowed.');
                fileInput.value = '';
                return false;
            }

            if (fileSize > maxFileSize) {
                // File is too large
                $(fileInput).parent().find('.invalid-feedback').show().text('The selected file is too large. Please select a file smaller than 5 MB');
                fileInput.value = '';
                return false;
            }

            if (file.type.startsWith('image/')) {
                // It's an image file
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = new Image();
                    img.src = e.target.result;
                    img.onload = function() {
                        var widthInRange = Math.abs(img.width - 600) <= 20;
                        var heightInRange = Math.abs(img.height - 370) <= 20;

                        /*if (widthInRange && heightInRange) {
                            // Image has dimensions within the acceptable range
                            // Proceed with other actions or display the image
                            $(fileInput).closest('.form-group').find('#preview_img').attr('src', e.target.result);
                        } else {
                            // Display an error message for incorrect dimensions
                            // swal({
                            //     title: "Error",
                            //     text: "The image dimensions must be within a range of 10 to 20 pixels from 600x370.",
                            //     type: "error"
                            // });
                            $(fileInput).parent().find('.invalid-feedback').show().text('The image dimensions must be within a range of 10 to 20 pixels from 600x370.');

                            fileInput.value = '';
                        }*/
                    };
                };
                reader.readAsDataURL(file);
            } else {
                // Not an image file
                $(fileInput).parent().find('.invalid-feedback').show().text('File type not allowed. Only image files are allowed.');
                fileInput.value = '';
            }

            $(fileInput).parent().find('.invalid-feedback').hide();
            return true;
        }

        //var thumbnailBooth = $('#thumbnail-booth');
        //var thumbnailBooth = $('.thumbnail-booth');
        var thumbnailInput = $('#thumbnail-booth');
        var presentationInput = $('#presentation-thumbnail');

        thumbnailInput.on('change', function() {
            validateFileInput(this);
            console.log('vikku');
        });

        presentationInput.on('change', function() {
            validateFileInput(this);
        });



        $('input[type=radio][name=video_option]').change(function() {
            //console.log($(this).val());
            if (this.value == 'link') {
                $('.link-container').show();
                $('.thumb-container').show();
                $('.embed-container').hide();
            } else if (this.value == 'embed') {
                $('.link-container').hide();
                $('.thumb-container').hide();
                $('.embed-container').show();
            }
        });

        $('#select-event').change(function(event) {
            var post_id = $(this).val();
            console.log(post_id);
            $.ajax({
                url: '<?php echo $website_domain ?>/api/widget/json/get/get_post_details_ajax',
                type: 'GET',
                dataType: 'json',
                data: {
                    post_id: post_id
                },
                success: function(data) {
                    $('#start_date').val(data.post_start_date);
                    $('#end_date').val(data.post_expire_date);
                    $('#time_sloat').html(data.time_sloats).prop('disabled', false);
                }
            })
        });

        //Preview to the right hand side.

        //Preview to the right hand side.
        var $form = $('#add_supplier_card');

        // Preview to the right hand side.

        $('#add_supplier_card input, #add_supplier_card select, #add_supplier_card textarea').on('input change', function(event) {

            // Serialize the form data
            var formData = [];
            $('#add_supplier_card input, #add_supplier_card select, #add_supplier_card textarea').each(function(index, element) {
                var element = $(element);
                var get_data = element.val();
                if ($.trim(get_data) == '') {
                    if (element.data('preview')) {
                        get_data = element.data('preview');
                    }
                }
                if (element.attr('name')) {

                    // if(element.attr('type') === 'radio' && !element.prop('checked')) {
                    //     return; // skip unchecked radio buttons
                    // }
                    formData.push({
                        name: element.attr('name'),
                        value: get_data
                    });
                }
                // formData.push({ name: element.attr('name'), value: get_data });
            });



            // Add image preview
            var imageFile = $('#add_supplier_card [name="thumbnail_booth"]')[0].files[0];
            var imgPreviewBtn = $('#img-preview-btn').attr('href');
            if (imageFile) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    //$('.image-preview').html('<img src="' + e.target.result + '" alt="Image Preview">');
                }
                reader.readAsDataURL(imageFile);
            } else if (imgPreviewBtn) {
                //$('.image-preview').html('<img src="' + imgPreviewBtn + '" alt="Image Preview">');
            } else {
                //$('.image-preview').html('<img src="https://www.motiv8search.com/images/download.jpg" alt="image-preview">');
            }

            // Update preview
            $.each(formData, function(index, field) {

                // Check if the input is a url input
                if ($form.find('[name="' + field.name + '"]').attr('type') === 'url') {
                    const url = field.value;
                    if (url) {
                        $('.' + field.name + '-btnpreview').removeClass('btn-secondary disabled');
                        $('.' + field.name + '-btnpreview').addClass('btn-info');
                        $('.' + field.name + '-btnpreview').attr('href', url);
                        // btn-secondary disabled
                        // btn-info

                    } else {
                        $('.' + field.name + '-btnpreview').removeClass('btn-info');
                        $('.' + field.name + '-btnpreview').addClass('btn-secondary disabled');
                        $('.' + field.name + '-btnpreview').removeAttr('href');
                    }

                }
                //check if the input is a radio button
                /*
                if ($form.find('[name="' + field.name + '"]').attr('type') === 'radio') {
                    const video_option = field.value;
                    if(video_option == 'link' || video_option == 'embed'){
                        $('.' + field.name + '-radio-preview').css('visibility', 'visible');
                    }else{
                        $('.' + field.name + '-radio-preview').css('visibility', 'hidden');
                    }
                } */

                $('.' + field.name + '-preview').text(field.value);
            });

        });

        // Get references to the textarea and character count elements
        const $textArea = $('#presentation-description');
        const $charCount = $('#charCount');

        // Function to update character count
        function updateCharCount() {
            // Get the current character count
            const currentCount = $textArea.val().length;

            // Update the character count display
            $charCount.text(currentCount + '/200');

            // Check if the character count exceeds the limit
            if (currentCount > 200) {
                // Trim the text to the character limit
                $textArea.val($textArea.val().slice(0, 200));

                // Update the character count display again
                $charCount.text('200/200');
            }
        }

        // Check character count on page load
        updateCharCount();

        // Add an input event listener to the textarea
        $textArea.on('input', function() {
            // Update character count whenever there is input
            updateCharCount();
        });


    });
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/4.1.0/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/4.1.0/cropper.min.js"></script>

<script>
    var file = '';
    var tmpName = ''; 
    var fileSize = '';
    var fileName = '';
    var fileType = '';
    var oldimage= '';
    const fillColor = '<?php $rgbarr = explode(",", cleanRGBA($wa["custom_1"]), 3);
        echo sprintf("#%02x%02x%02x", $rgbarr[0], $rgbarr[1], $rgbarr[2]); ?>';
    $(document).ready(function() {
		var thumbnailBoothValue = $('#thumbnail_booth').val();
        var $imageInput = $("#thumbnail-booth");
        var $preview = $("#preview_img");
        var $currentCrop = null;
             oldimage = $preview.attr("src");
        $imageInput.on("change", function() {
            var file = this.files[0];
            // Get image details
            tmpName = file.name;
            fileSize = file.size;
            fileName = file.name;
            fileType = file.type;
            var reader = new FileReader();

            reader.onload = function(e) {
                $preview.attr("src", e.target.result);

                // Initialize the Cropper plugin
                $currentCrop = $preview.cropper({
                    aspectRatio: 1.6, 
                    viewMode: 1,
                    dragMode: 'move',
                    cropBoxResizable: false,
                    cropBoxFixed: true, 
                    cropBoxWidth: 600, 
                    cropBoxHeight: 370
                });
                    
            };
            if(file){
                $('#upload_img').hide();
                $('#crop_submit').show();
                $('#cancel_submit').show();
                
                $('.interactive-container').removeClass('hidden');
                $(".croper-action").attr("disabled", false);

            }

            reader.readAsDataURL(file);
        });

        //profile image controls
        $(document).on('click', '.croper-action', function () {
            var currentButton = $(this);
            var dataMethod = currentButton.data('method');
            var currentCrop = $('#preview_img');
            switch (dataMethod) {
                case 'crop':
                        var croppedDataUrl = currentCrop.cropper("getCroppedCanvas").toDataURL("image/jpeg");
                        var profileImgCroppedData = currentCrop.cropper('getCroppedCanvas', {
                            "imageSmoothingEnabled": true,
                            "imageSmoothingQuality": "high",
                            fillColor: fillColor,
                        });
                        var croppedImgWidth = parseInt(profileImgCroppedData.width);
                        var croppedImgHeight = parseInt(profileImgCroppedData.height);
                        var croppedImageData = profileImgCroppedData.toDataURL("image/jpeg");
                        var update_booth_id = $('input[name="update_booth_id"]').val();
                        var imgData = {
                            croppedImage: croppedImageData,
                            width: croppedImgWidth,
                            height: croppedImgHeight,
                            tmpName: tmpName,    
                            fileSize: fileSize,  
                            fileName: fileName,  
                            fileType: fileType,
                            updateId: update_booth_id
                        };
                        updateImagePreview($currentCrop);
                        currentCrop.attr("src", croppedImageData);
                        $('#upload_img').show();
                        $('#crop_submit').hide();
                        $('#cancel_submit').hide();
                        $('.interactive-container').addClass('hidden');
                        $(".croper-action").attr("disabled", true);
                        $('input[name="update_booth"]').prop("disabled", true);
                        $("#update_booth").val('Please wait...');
                        
                        // Send the data to the server using AJAX
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $website_domain ?>/api/widget/json/get/booth-cropped-image', 
                            data: imgData,
                            success: function(response) {
                                if(response.error == 0){
                                    $("#booth_cropedImg").val(response.file);
                                    $('input[name="update_booth"]').prop("disabled", false);
                                    $("#update_booth").val('Save');
                                }else{
                                    swal({
                                        title: 'Oops! error found!',
                                        text: response.message,
                                        type: "error",
                                        showConfirmButton: false
                                    });
                                }
                                console.log('Image data sent successfully.');
                                console.log(response);
                            },
                            error: function(error) {
                                $('input[name="update_booth"]').prop("disabled", false);
                                $("#update_booth").val('Save');
                                console.error('Error sending image data:', error);
                            }
                        });
                        $('#add_supplier_card').submit(function(event) {
                            location.reload();
        
                            });
                        // Clean up
                        $currentCrop.cropper("destroy");
                        //$imageInput.val("");
                    break;
                case 'cancel':
                    $currentCrop.cropper("destroy");
                    //$imageInput.val("");
                    currentCrop.attr("src", oldimage);
                    $('.company-thumb-img.image-preview').html('<img src="' + oldimage + '" alt="Image Preview">');
                    $('#upload_img').show();
                    $('#crop_submit').hide();
                    $('#cancel_submit').hide();
                    $('.interactive-container').addClass('hidden');
                    $(".croper-action").attr("disabled", true);
                    break;
                
            }
            

        });

        $("#zoomRange").on("input", function() {
            var zoomValue = parseFloat($(this).val());
            console.log(zoomValue);
            $('.company-thumb-img.image-preview').html('');
            if ($currentCrop) {
                $currentCrop.cropper("zoomTo", zoomValue);
                var croppedDataUrl = $currentCrop.cropper("getCroppedCanvas").toDataURL("image/jpeg");
                $('.company-thumb-img.image-preview').html('<img src="' + croppedDataUrl + '" alt="Image Preview">');
                // Update the image preview on zoom
                //updateImagePreview($currentCrop);
            }
        });
        // Add event listeners for various Cropper events
        $preview.on('crop', function() { updateImagePreview($currentCrop); }); // Update on cropping
        $preview.on('zoom', function() {  updateImagePreview($currentCrop); }); // Update on zooming
        $preview.on('dragend', function() { updateImagePreview($currentCrop); }); // Update on moving the crop box
        $preview.on('zoomend', function() { updateImagePreview($currentCrop); }); // Update on ending zoom
        $(document).on('click', '#update_booth', function () {
            var value = $(this).val();
            console.log(thumbnailBoothValue);
            if(thumbnailBoothValue ==''){
                $(this).closest('form#add_supplier_card').find('.invalid-feedback').show().text('Please upload an image!, This field is required.');
                $(this).prop("disabled", true);
            }else{
                $(this).closest('form#add_supplier_card').find('.invalid-feedback').hide();
                $(this).prop("disabled", false);
            }

        });
        
		if (thumbnailBoothValue === "") {
    		// Add the "required" attribute
    		$('#thumbnail_booth').attr('required', 'name=thumbnail_booth');
  		}
    });
    function updateImagePreview(currentCrop) {
        if (currentCrop) {
            var croppedDataUrl = currentCrop.cropper("getCroppedCanvas").toDataURL("image/jpeg");
            $('.image-preview').html('<img src="' + croppedDataUrl + '" alt="Image Preview">');
        }
        
    }
    
</script>
<script>
    // const img = document.querySelector('.company_logo');
    // img.onload = function () {
    //     console.log('Image loaded successfully.');
    // };

    // img.onerror = function () {
    //     console.log('Company logo not found or failed to load.');
    //     //img.src = 'https://example.com/default-logo.png';
    // };

</script>