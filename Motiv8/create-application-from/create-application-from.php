<!-- create-application-from -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
if($pars[0] === 'admin' && $pars[1] === 'go.php'){

function log_Activity($action, $reference_table, $reference_id, $description = '') {
    global $sess; 
    $user_id = isset($sess['admin_id']) ? $sess['admin_id'] : 0;
    $user_name = isset($sess['admin_name']) ? $sess['admin_name'] : $sess['admin_user'];
    $user_type = 'admin';
    $ip = $_SERVER['REMOTE_ADDR'];
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
    $now = date('Y-m-d H:i:s');
    $descriptionEscaped = mysql_real_escape_string($description);
    $sql = "INSERT INTO activity_log (user_id, user_name, user_type, `action`, reference_table, reference_id, `description`, ip_address, user_agent, created_at)
            VALUES ('$user_id', '$user_name', '$user_type', '$action', '$reference_table', '$reference_id', '$descriptionEscaped', '$ip', '$agent', '$now')";

    mysql_query($sql);
}


global $sess; 
$loggedname = $sess['admin_name'];
$loggeduser = $sess['admin_user']; 
$randomString = uniqid();
$hash = hash('sha256', $randomString);
$hash32 = substr($hash, 0, 32);
$username = brilliantDirectories::getDatabaseConfiguration('website_user');
$password = brilliantDirectories::getDatabaseConfiguration('website_pass');
$host = brilliantDirectories::getDatabaseConfiguration('ftp_server');
$file_errorMSG = array();
$file_name = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit_coupon_btn"])) {
    //$event_id = $_POST['event_id'];
    $subheading = $_POST['subheading'];
    $short_description = $_POST['short_description_coupon'];
    $desktop_package = $_POST['desktop_package'];
    $actual_price = $_POST['actual_price'];
    $superbooth_package = $_POST['superbooth_package'];
    $token = $_POST['token'];
    $type = 'credit form';
    $discount = $_POST['discount'];

    $insertsql = "INSERT INTO create_application_pages (subheading, `type`, short_description, desktop_package, actual_price, superbooth_package, token, discount)
            VALUES ('$subheading', '$type', '$short_description', '$desktop_package', '$actual_price', '$superbooth_package', '$token', '$discount')";

    if (mysql_query($insertsql) === TRUE) {
        $lastId = mysql_insert_id();
        log_Activity('credit_form_created', 'create_application_pages', $lastId, "Credit Form created by admin: $loggedname");
        echo "Inserted Successfully";
    } else {
        echo "Error";
    }
    /*  echo "INSERT INTO create_application_pages (subheading, `type`, short_description, desktop_package, actual_price, superbooth_package, token, discount)
    VALUES ('$subheading', '$type', '$short_description', '$desktop_package', '$actual_price', '$superbooth_package', '$token', '$discount')";*/
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit_btn"])) {

    $event_id = $_POST['event_id'];
    $addons = $_POST['addons'];
    $addons_list  = isset($_POST['addons']) ? implode(',', $_POST['addons']) : '';
    $subheading = mysql_real_escape_string($_POST['subheading']);
    $short_description = mysql_real_escape_string($_POST['short_description']);
    $desktop_package = $_POST['desktop_package'];
    $superbooth_package = $_POST['superbooth_package'];
    $token = $_POST['token'];
    $type = 'registration form';
    $event_image = $_POST['imagecropped'];
    // Store stock balance as JSON
    $addons_stock = array();
    if (!empty($_POST['addons_stock'])) {
        foreach ($_POST['addons_stock'] as $id => $stock) {
            if (in_array($id, $addons)) {
                $addons_stock[$id] = (int)$stock;
            }
        }
    }
    $addons_stock_json = json_encode($addons_stock);
    $insertsql = "INSERT INTO create_application_pages (event_id, subheading, event_image, `type`, short_description, desktop_package, superbooth_package, token, add_ons_id, add_ons_stock)
                VALUES ('$event_id', '$subheading', '$event_image', '$type', '$short_description', '$desktop_package', '$superbooth_package', '$token', '$addons_list', '$addons_stock_json')";
    //echo $insertsql;
    if (mysql_query($insertsql) === TRUE) {
        $lastId = mysql_insert_id();
        log_Activity('registration_form_created', 'create_application_pages', $lastId, "Registration Form created by admin: $loggedname for Event ID: $event_id");
        //echo "Inserted Successfully";
    } else {
        echo "Error";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_btn"])) {

    $event_image_edit = (isset($_POST['edit_imagecropped']) && $_POST['edit_imagecropped'] != "") ? $_POST['edit_imagecropped'] : $_POST['previous_image'];
    $registration_status = $_POST['registration_status'];
    $event_id = $_POST['eventname'];
    $addons_edit = $_POST['addons_edit'];
    $addons_list_edit = isset($_POST['addons_edit']) ? implode(',', $_POST['addons_edit']) : '';
    $event_hide = $_POST['event_hide'];
    $event_heading = $_POST['eventhead'];
    $actual_price = $_POST['actual_price'];
    $event_description = $_POST['event_description'];
    $desktop_packageamount = $_POST['desktop_packageamount'];
    $superbooth_packageamount = $_POST['superbooth_packageamount'];
    $discount = $_POST['discount'];
    // Update Store stock balance as JSON
    $addons_stock_edit = array();
    if (!empty($_POST['addons_stock_edit'])) {
        foreach ($_POST['addons_stock_edit'] as $id => $stock) {
            if (in_array($id, $addons_edit)) {
                $addons_stock_edit[$id] = (int)$stock;
            }
        }
    }
    $addons_stock_edit_json = json_encode($addons_stock_edit);
   
    $updateQuery = "UPDATE create_application_pages SET `status`= '$registration_status', event_id='$event_id', actual_price='$actual_price', subheading='$event_heading', event_image='$event_image_edit', short_description='$event_description', desktop_package='$desktop_packageamount', superbooth_package='$superbooth_packageamount',add_ons_id='$addons_list_edit', add_ons_stock= '$addons_stock_edit_json', discount='$discount' WHERE id='$event_hide'";

    //echo $updateQuery;
    if (mysql_query($updateQuery) === TRUE) {
        log_Activity('form_updated', 'create_application_pages', $event_hide, "Form updated by $loggedname (Event ID: $event_id)");
        //echo "Update Successful";
        if (isset($file_errorMSG) && isset($file_errorMSG['error'])) {
            $errorMessage = $file_errorMSG['error'];
            $alertType = 'error';
        } else {
            $errorMessage = 'Application Form Saved Successful!';
            $alertType = 'success';
        }
        ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var message = "<?php echo $errorMessage; ?>";
                var alertType = "<?php echo $alertType; ?>";

                Swal.fire(
                    alertType === 'error' ? 'Opps! Error' : 'Application Form Saved!',
                    message,
                    alertType
                );
            });
        </script>
    <?php
        } else {
            echo "Error updating data";
        }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    $delete_btn = $_POST['delete_id'];

    $deleteQuery = "DELETE FROM create_application_pages WHERE id='$delete_btn'";
    //echo "DELETE FROM create_application_pages WHERE id='$delete_btn'";
    $delete_log = mysql_query("INSERT INTO log_delete (loggedname, loggeduser, delete_type, deleted_id) 
                               VALUES ('$loggedname', '$loggeduser', 'Registration_Form', '$delete_btn')");
    if (mysql_query($deleteQuery) === TRUE) {
        log_Activity('form_deleted', 'create_application_pages', $delete_btn, "Form deleted by $loggedname (Form ID: $delete_btn)");
        //echo "Delete Successful";
    } else {
        echo "Error deleting data";
    }
}

$selectQuery = "SELECT create_application_pages.*, data_posts.post_title, data_posts.post_id, data_posts.post_start_date,(SELECT COUNT(*) FROM live_events_posts AS lep WHERE lep.post_id = create_application_pages.event_id ) AS count_suppliers FROM create_application_pages LEFT JOIN data_posts ON create_application_pages.event_id = data_posts.post_id ORDER BY (data_posts.post_start_date < NOW()) ASC, ABS(DATEDIFF(data_posts.post_start_date, NOW())) ASC"; //`create_application_pages`.`id` DESC;
$result = mysql_query($selectQuery);
$data = array();

while ($row = mysql_fetch_assoc($result)) {
    $data[] = $row;
}
$current_date = date('Ymd');
$counter = 1;
//var_dump(result);
//echo $selectQuery;
?>
<section class="main_content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <?php if (isset($file_errorMSG) && isset($file_errorMSG['error'])) { ?>
                    <div class="alert alert-warning fade in alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= $file_errorMSG['error'] ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-12">
                <div class="create_form">
                    <div class="create_btn">
                        <h2>Registration Forms</h2>
                        <div>
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#largeShoes_coupon" data-backdrop="static" data-keyboard="false">Create Credit Form </button>
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#largeShoes" data-backdrop="static" data-keyboard="false">Create Registration Form</button>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal" id="largeShoes" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="modalLabelLarge">Create Application Form</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="registation_form" action="" method="post" onsubmit="return validateForm();" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="event">Choose Event</label>
                                            <select name="event_id" class="event_id" id="event_names_select" required>
                                                <option value="" disabled selected>Select Event</option>
                                                <?php
                                                $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '73' AND post_status = '1' ORDER BY `post_id` DESC");
                                                while ($row = mysql_fetch_assoc($selectquery)) {
                                                    echo '<option value="' . $row['post_id'] . '">' . $row['post_title'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="subheading">Event Heading</label>
                                            <input type="text" class="form-control subheading-input" name="subheading" required>
                                        </div>
                                        <div class="form-group image_section">
                                            <label>Event Images</label>
                                            <div class="image_preview_main">
                                            </div>
                                            <div style="width: 600px;height: 364px;" class="center-block">
                                                <img id="preview_img" class='img-thumbnail center-block' src="https://www.motiv8search.com/images/1709386060_7f878ed5-ee52-4c1f-a5e0-92f3cfc91942.jpg" alt="preview image" style="display: block; max-width: 100%;">
                                            </div>

                                            <div class="image_action row">
                                                <div class="col-md-12 text-center" style="padding: 15px;">
                                                    <label class="btn btn-primary" for="event_image" id="upload_img"><span class="glyphicon glyphicon-folder-open"></span> Upload Image </label>
                                                    <input type="file" accept="image/*" class="form-control" id="event_image" name="event_image" required style="display: none;">
                                                    <input type="hidden" name="imagecropped" id="imagecropped" value="">
                                                    <button style="display: none;" type="button" class="btn btn-primary bold col-md-6 croper-action" id="crop_submit" data-method="crop" data-option="crop" title="Crop" data-origin="photo" disabled="disabled"> Crop &amp; Upload </button>
                                                    <button style="display: none;" type="button" class="btn btn-default bold col-md-4 col-md-offset-1 croper-action" id="cancel_submit" data-method="cancel" data-option="cancel" title="Crop" data-origin="photo" disabled="disabled"> cancel <i class="fa fa-close"></i></button>
                                                </div>
                                                <div class="image-controls center col-md-12 hidden">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary croper-action" data-method="zoom" data-option="0.1" title="Zoom In" data-origin="photo" disabled="disabled">
                                                            <span class="docs-tooltip" title="">
                                                                <span class="fa fa-search-plus"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary croper-action" data-method="zoom" data-option="-0.1" title="Zoom Out" data-origin="photo" disabled="disabled">
                                                            <span class="docs-tooltip" title="">
                                                                <span class="fa fa-search-minus"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary croper-action" data-method="rotate" data-option="-45" title="Rotate Left" data-origin="photo" disabled="disabled">
                                                            <span class="docs-tooltip" title="Rotate Left">
                                                                <span class="fa fa-rotate-left"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary croper-action" data-method="rotate" data-option="45" title="Rotate Right" data-origin="photo" disabled="disabled">
                                                            <span class="docs-tooltip" title="Rotate Right">
                                                                <span class="fa fa-rotate-right"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary croper-action" data-method="reset" data-option="cropper.reset()" title="Reset" data-origin="photo" disabled="disabled">
                                                            <span class="docs-tooltip" title="Reset">
                                                                <span class="fa fa-retweet"></span>
                                                            </span>
                                                        </button>

                                                        <button type="button" class="btn btn-primary croper-action" data-method="crop" data-option="crop" title="Crop" data-origin="photo" disabled="disabled">
                                                            <span class="docs-tooltip" title="Crop">
                                                                <span class="fa fa-crop"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary croper-action" data-method="scaleX" data-option="-1" title="Flip Horizontal" data-origin="photo">
                                                            <span class="docs-tooltip" title="Flip Horizontal">
                                                                <span class="fa fa-arrows-h"></span>
                                                            </span>
                                                        </button>
                                                        <button type="button" class="btn btn-primary croper-action" data-method="scaleY" data-option="-1" title="Flip Vertical" data-origin="photo">
                                                            <span class="docs-tooltip" title="Flip Vertical">
                                                                <span class="fa fa-arrows-v"></span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="range_zoom-in_out row">
                                                        <div class="btn-group center-block" style=" width: 50%;">
                                                            <input type="range" id="zoomRange" min="0.1" data-method="zoom" max="2" step="0.1" value="0.5">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <small class="text-danger"><b>The image must be exactly 800 pixels in width and 480 pixels in height.</b></small>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="desktoppackage">Enter amount for desktop package</label>
                                            <input type="text" class="form-control subheading-input" name="desktop_package" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="superboothpackage">Enter amount for superbooth package</label>
                                            <input type="text" class="form-control subheading-input" name="superbooth_package" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="add-ons">Choose Add-Ons</label>

                                            <?php
                                            $select_sql_add_ons = "SELECT * FROM `add_ons` ORDER BY `add_ons`.`id` ASC";
                                            $result_add_ons = mysql_query($select_sql_add_ons);

                                            if (mysql_num_rows($result_add_ons) > 0) {
                                                while ($lists = mysql_fetch_assoc($result_add_ons)) { ?>
                                                    <div class="checkbox-group">
                                                        <input type="checkbox" name="addons[]" id="add-ons" value="<?= $lists['id'] ?>" class="checkbox checkbox-addons col-md-2">
                                                        <span class="add-ons-name col-md-3"><?= $lists['name'] ?></span>
                                                        <span class="add-ons-price col-md-2"><?= $lists['price'] ?></span>
                                                        <span class="add-ons-label col-md-3">
                                                            <span class="badge">
                                                                <?php if ($lists['label'] == 1) {
                                                                    echo "<span class='badge'>Presentation Add-On</span>";
                                                                }
                                                                if ($lists['label'] == 2) {
                                                                    echo "<span class='badge'>Additional Add-Ons</span>";
                                                                } ?>
                                                            </span>
                                                        </span>
                                                        <!-- Stock Balance Input -->
                                                        <input type="text" name="addons_stock[<?= $lists['id'] ?>]" min="0" pattern="^[0-9]+$" inputmode="numeric" class="col-md-2" placeholder="Stock Balance">
                                                    </div> <?php
                                                        }
                                                    }

                                                            ?>
                                        </div>
                                        <div class="form-group" id="example">
                                            <label for="description_app">Short description</label>
                                            <!-- <textarea name="short_description" class="form-control" cols="30"
                                                rows="10"></textarea> -->
                                            <textarea name="short_description" id="description_app" placeholder="Product Details" class="form-control" rows="12" cols="50"></textarea>
                                            <div id="shortDescriptionError" class="text-danger"></div>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" class="form-control" name="token" value="<?php echo $hash32; ?>">
                                        </div>
                                        <div class="btn_submit text-right">
                                            <button class="btn btn-primary" type="submit" name="submit_btn">Create
                                                application form</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Model for Coupon -->
                    <div class="modal" id="largeShoes_coupon" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="modalLabelLarge">Event Credit Package</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="registation_form" action="" method="post" onsubmit="return validateForm();">
                                        <!-- <div class="form-group">
                                            <label for="event">Choose event</label>
                                            <select name="event_id" class="event_id" id="event_names_select" required>
                                                <option value="" disabled selected>Select Event</option>
                                                <?php /*
                                                $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '73' AND post_status = '1' ORDER BY `post_id` DESC");
                                                while ($row = mysql_fetch_assoc($selectquery)) {
                                                    echo '<option value="' . $row['post_id'] . '">' . $row['post_title'] . '</option>';
                                                } */
                                                ?>
                                            </select>
                                        </div> -->

                                        <div class="form-group">
                                            <label for="subheading">Event heading</label>
                                            <input type="text" class="form-control subheading-input" name="subheading" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="desktoppackage">Enter Package price</label>
                                            <input type="text" class="form-control subheading-input" id="actulaprice_credit" name="actual_price" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="desktoppackage">Enter discount Percentage <i class="fa fa-percentage"></i></label>
                                                    <input type="text" class="form-control subheading-input" id="discount_credit" name="discount" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="desktoppackage">Discounted Price <i class="fa fa-percentage"></i></label>
                                                    <input type="text" class="form-control subheading-input" id="desktpc_credit" name="desktop_package" value="" readonly style="font-size: 20px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="eventCredits">Enter number credits</label>
                                            <input type="text" class="form-control subheading-input" name="superbooth_package" required>
                                        </div>

                                        <div class="form-group" id="example">
                                            <label for="description_promo">Short description</label>
                                            <!-- <textarea name="short_description" class="form-control" cols="30"
                                                rows="10"></textarea> -->
                                            <textarea name="short_description_coupon" id='description_promo' placeholder="Product Details" class="form-control" rows="6" cols="50"></textarea>
                                            <div id="shortDescriptionError" class="text-danger"></div>
                                        </div>

                                        <div class="form-group">
                                            <input type="hidden" class="form-control" name="token" value="<?php echo $hash32; ?>">
                                        </div>
                                        <div class="btn_submit text-right">
                                            <button class="btn btn-primary" type="submit" value="1" name="submit_coupon_btn">Create
                                                Event Credit Package </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <table class="table_data">
                        <thead>
                            <tr class="mhead">
                                <th id="upcoming_events">Upcoming Events</th>
                            </tr>
                            <tr>
                                <th class="thead_subhead">Registration</th>
                                <th class="thead_date">Date</th>
                                <th class="thead_token">Total Registrants</th>
                                <th style="width: 35%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $rows){
							    $postUrl = 'https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=' . $rows['post_id'];
                                if($rows['type']!== 'credit form' && $rows['post_start_date'] >= $current_date){
                                ?>
                                <tr>
                                    <td><?php echo ($rows['subheading']); ?></td>
                                    <td><?php echo !empty($rows['post_start_date']) ? date('d F Y', strtotime($rows['post_start_date'])) : ''; ?></td>
                                    <td class="text-center">
                                        <?php 
                                        $countReg = mysql_fetch_assoc(mysql_query("SELECT COUNT(form_id)AS total_registrants FROM `supplier_registration_form` WHERE form_id = ".$rows['id'].";"));

										if($rows['type'] == 'credit form'){
											echo $countReg['total_registrants'];
										}else{ ?>
											<a href="<?= $postUrl ?>" target="_blank" class="btn-link nopad">
												<strong><?php echo $rows['count_suppliers'] ?></strong>
											</a>
										<?php } ?>
                                    </td>
                                    <td class="action_btn">
                                        <button class="edit-btn btn-pr" data-toggle="modal" data-target="#largeModal<?php echo $counter; ?>">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>Edit
                                        </button>
                                        <form action="" method="post" class="delete_form" onclick="return confirmDelete(event, '<?php echo $rows['id']; ?>');">
                                            <button class="delete-btn" type="button" name="delete_btn" value="<?php echo $rows['id']; ?>">
                                                <i class="fa fa-trash" aria-hidden="true"></i>Delete
                                            </button>
                                        </form>
                                        <button class="copy_btn" data-toggle="modal" data-token="<?php echo $rows['token']; ?>" data-target="<?php echo $counter; ?>" data-coupon="<?php echo $rows['type']; ?>">
                                            <i class="fa fa-files-o" aria-hidden="true"></i>Reg Link
                                        </button>
                                        <?php 
                                        $query = mysql_query("SELECT COUNT(event_id) AS event FROM data_posts WHERE data_id = 75 AND post_status = 1 AND post_category = 'provisional_credits' AND event_id =". $rows['post_id'] ) ;
                                        if (mysql_result($query, 0, 'event') > 0) { ?>
                                            <button class="copy_btn" data-toggle="modal" data-token="pre-register=<?php echo $rows['post_id'] ?>" data-target="<?php echo $counter; ?>" data-coupon="pre-registration">
                                                <i class="fa fa-files-o" aria-hidden="true"></i>Pre-Reg Link
                                            </button>
                                        <?php } ?>

                                    </td>
                                </tr>
                                <!-- Edit Modal -->
                                <div class="modal" id="largeModal<?php echo $counter; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <?php if ($rows['type'] == 'registration form') { ?>
                                                    <h4 class="modal-title" id="modalLabelLarge">Edit Application Form</h4>
                                                <?php } else { ?>
                                                    <h4 class="modal-title" id="modalLabelLarge">Edit Credit Form</h4>
                                                <?php } ?>

                                            </div>

                                            <div class="modal-body">
                                                <form action="" method="post" class="edit_form" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control" name="event_hide" value="<?php echo ($rows['id']); ?>">
                                                    </div>
                                                    <!-- Application Edit Form -->
                                                    <?php if ($rows['type'] == 'registration form') { ?>
                                                        <div class="form-group">
                                                            <label for="registration_status">Status</label>&nbsp; &nbsp;&nbsp;
                                                            <label for="status_publish1" class="radio-inline">
                                                                <input name="registration_status" id="status_publish1" value="1" type="radio" 
                                                                <?php if ($rows['status'] == 1) echo 'checked'; ?>>
                                                                Publish
                                                            </label>
                                                            <label for="status_draft1" class="radio-inline">
                                                                <input name="registration_status" id="status_draft1" value="2" type="radio" 
                                                                <?php if ($rows['status'] == 2) echo 'checked'; ?>>
                                                                Draft
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="eventname">Choose Event</label>
                                                            <select class="form-control" name="eventname">
                                                                <option value="" disabled selected>Select Event</option>
                                                                <?php
                                                                // $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '8' AND post_status = '1' ORDER BY `post_id` DESC");
                                                                // while ($row = mysql_fetch_assoc($selectquery)) {
                                                                //     echo '<option value="' . $row['post_id'] . '">' . $row['post_title'] . '</option>';
                                                                // }
                                                                $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '73' AND post_status = '1' ORDER BY `post_id` DESC");
                                                                while ($row = mysql_fetch_assoc($selectquery)) {
                                                                    $selected = ($row['post_id'] == $rows['event_id']) ? 'selected' : '';
                                                                    echo '<option value="' . $row['post_id'] . '" ' . $selected . '>' . $row['post_title'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="eventhead">Event Heading</label>
                                                            <input type="text" class="form-control subheading-input" name="eventhead" value="<?php echo ($rows['subheading']); ?>" required>
                                                            <p class="error-message"></p>
                                                        </div>
                                                        <div class="form-group" id="image_section<?php echo $counter; ?>">
                                                            <label>Event Images</label>
                                                            <div class="image_preview_main"></div>
                                                            <div style="width: 600px;height: 364px;" class="center-block">
                                                                <?php $src = ($rows['event_image'] != "") ? "https://www.motiv8search.com/images/".$rows['event_image'] : "https://www.motiv8search.com/images/1709386060_7f878ed5-ee52-4c1f-a5e0-92f3cfc91942.jpg";?>
                                                                <img id="preview_img<?php echo $counter; ?>" class='img-thumbnail center-block' src="<?= $src ?>" alt="preview image" style="display: block; max-width: 100%;">
                                                            </div>
                                                            <div class="image_action row">
                                                                <div class="col-md-12 text-center" style="padding: 15px;">
                                                                    <label class="btn btn-primary" for="event_image<?php echo $counter; ?>" id="upload_img<?php echo $counter; ?>">
                                                                        <span class="glyphicon glyphicon-folder-open"></span> Upload Image </label>
                                                                    <input type="file" accept="image/*" class="form-control" id="event_image<?php echo $counter; ?>" name="edit_event_image" <?php echo ($rows['event_image'] != " ") ? " ": 'required'; ?> style="display: none;">
																	<input type="hidden" name="previous_image" id="previous_image<?php echo $counter; ?>" value="<?= $rows['event_image'] ?>">
                                                                    <input type="hidden" name="edit_imagecropped" id="imagecropped<?php echo $counter; ?>" value="">
                                                                    <button style="display: none;" type="button" class="btn btn-primary bold col-md-6 croper-action" id="crop_submit<?php echo $counter; ?>" data-method="crop" data-option="crop" title="Crop" data-origin="photo" disabled="disabled"> Crop &amp; Upload </button>
                                                                    <button style="display: none;" type="button" class="btn btn-default bold col-md-4 col-md-offset-1 croper-action" id="cancel_submit<?php echo $counter; ?>" data-method="cancel" data-option="cancel" title="Crop" data-origin="photo" disabled="disabled"> cancel <i class="fa fa-close"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="image-controls center col-md-12 hidden">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="zoom" data-option="0.1" title="Zoom In" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="">
                                                                                <span class="fa fa-search-plus"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="zoom" data-option="-0.1" title="Zoom Out" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="">
                                                                                <span class="fa fa-search-minus"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="rotate" data-option="-45" title="Rotate Left" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Rotate Left">
                                                                                <span class="fa fa-rotate-left"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="rotate" data-option="45" title="Rotate Right" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Rotate Right">
                                                                                <span class="fa fa-rotate-right"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="reset" data-option="cropper.reset()" title="Reset" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Reset">
                                                                                <span class="fa fa-retweet"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="crop" data-option="crop" title="Crop" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Crop">
                                                                                <span class="fa fa-crop"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="scaleX" data-option="-1" title="Flip Horizontal" data-origin="photo">
                                                                            <span class="docs-tooltip" title="Flip Horizontal">
                                                                                <span class="fa fa-arrows-h"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="scaleY" data-option="-1" title="Flip Vertical" data-origin="photo">
                                                                            <span class="docs-tooltip" title="Flip Vertical">
                                                                                <span class="fa fa-arrows-v"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="range_zoom-in_out row">
                                                                        <div class="btn-group center-block" style=" width: 50%;">
                                                                            <input type="range" id="zoomRange<?php echo $counter; ?>" min="0.1" data-method="zoom" max="2" step="0.1" value="0.5">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <small class="text-danger">
                                                                        <b>The image must be exactly 800 pixels in width and 480 pixels in height.</b>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $("#upload_img<?php echo $counter; ?>").click(function(e) {
                                                                    //e.preventDefault();
                                                                    var $imageInput = $("#event_image<?php echo $counter; ?>");
                                                                    var $preview = $("#preview_img<?php echo $counter; ?>");
                                                                    var $zoomrange = $("#zoomRange<?php echo $counter; ?>");
                                                                    var $imagecropped = $("#imagecropped<?php echo $counter; ?>");
                                                                    var $canclebtn = $("#cancel_submit<?php echo $counter; ?>");
                                                                    var $cropbtn = $("#crop_submit<?php echo $counter; ?>");
                                                                    var $uploadimg = $("#upload_img<?php echo $counter; ?>");
                                                                    cropperdImageUpload($imageInput, $preview, $zoomrange, $imagecropped, $canclebtn, $cropbtn, $uploadimg);
                                                                });

                                                            });
                                                        </script>

                                                        <div class="form-group">
                                                            <label for="desktoppackage">Enter amount for desktop package</label>
                                                            <input type="text" class="form-control subheading-input" name="desktop_packageamount" value="<?php echo ($rows['desktop_package']); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="superboothpackage">Enter amount for superbooth
                                                                package</label>
                                                            <input type="text" class="form-control subheading-input" name="superbooth_packageamount" value="<?php echo ($rows['superbooth_package']); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="add-ons">Choose Add-Ons</label>
                                                            <?php
                                                            $stockBalances = json_decode($rows['add_ons_stock'], true);
                                                            $select_sql_add_ons = "SELECT * FROM `add_ons` ORDER BY `add_ons`.`id` ASC";
                                                            $result_add_ons = mysql_query($select_sql_add_ons);

                                                            if (mysql_num_rows($result_add_ons) > 0) {
                                                                // Get the comma-separated string of add-ons IDs from the database
                                                                $storedAddons = $rows['add_ons_id'];
                                                                $storedAddonsArray = explode(',', $storedAddons);

                                                                while ($lists = mysql_fetch_assoc($result_add_ons)) {
                                                                    $addonId = $lists['id'];
                                                                    $remaining_stock = isset($stockBalances[$addonId]) ? $stockBalances[$addonId] : 0;
                                                                    $hideCheckbox = ($remaining_stock === 0); 
                                                                    // Check if the current add-on ID exists in the stored array
                                                                    $checked = (in_array($lists['id'], $storedAddonsArray)) ? 'checked' : '';
                                                            ?>
                                                                    <div class="checkbox-group">
                                                                        <input type="checkbox" name="addons_edit[]" id="add-ons" value="<?= $lists['id'] ?>" class="checkbox checkbox-addons col-md-2" <?= $checked ?>>
                                                                        <span class="add-ons-name col-md-3"><?= $lists['name'] ?></span>
                                                                        <span class="add-ons-price col-md-2"><?= $lists['price'] ?></span>
                                                                        <span class="add-ons-label col-md-3">
                                                                            <span class="badge">
                                                                                <?php if ($lists['label'] == 1) {
                                                                                    echo "<span class='badge'>Presentation Add-On</span>";
                                                                                }
                                                                                if ($lists['label'] == 2) {
                                                                                    echo "<span class='badge'>Additional Add-Ons</span>";
                                                                                } ?>
                                                                            </span>
                                                                        </span>
                                                                        <!-- Stock Balance Input -->
                                                                        <input type="text" name="addons_stock_edit[<?= $lists['id'] ?>]" min="0" pattern="^[0-9]+$" inputmode="numeric" value="<?= $remaining_stock ?>" class="col-md-2" placeholder="Stock Balance">
                                                                    </div>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <!-- Edit Promo Code Form -->
                                                        <div class="form-group">
                                                            <label for="subheading">Event heading</label>
                                                            <input type="text" class="form-control subheading-input" name="eventhead" value="<?php echo ($rows['subheading']); ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="desktoppackage">Enter Package price</label>
                                                            <input type="text" class="form-control subheading-input" id="actulaprice_credit<?php echo $counter; ?>" name="actual_price" value="<?= $rows['actual_price'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="desktoppackage">Enter discount Percentage <i class="fa fa-percentage"></i></label>
                                                                    <input type="text" class="form-control subheading-input" id="discount_credit<?php echo $counter; ?>" name="discount" value="<?= $rows['discount'] ?>" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="desktoppackage">Discounted Price <i class="fa fa-percentage"></i></label>
                                                                    <input type="text" class="form-control subheading-input" id="desktpc_credit<?php echo $counter; ?>" name="desktop_packageamount" value="<?php echo ($rows['desktop_package']); ?>" readonly style="font-size: 20px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="eventCredits">Enter number credits</label>
                                                            <input type="text" class="form-control subheading-input" name="superbooth_packageamount" value="<?php echo ($rows['superbooth_package']); ?>" required>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $(document.body).on('change', '#discount_credit<?php echo $counter; ?>, #actulaprice_credit<?php echo $counter; ?>', function() {
                                                                    let discountValue = $('#discount_credit<?php echo $counter; ?>').val();
                                                                    let actualPriceValue = $('#actulaprice_credit<?php echo $counter; ?>').val();
                                                                    let desktopPackageInput = $('#desktpc_credit<?php echo $counter; ?>');
                                                                    calculateDiscountedPrice(discountValue, actualPriceValue, desktopPackageInput);
                                                                });
                                                            });
                                                        </script>

                                                    <?php } ?>
                                                    <div class="form-group">
                                                        <label for="description">Short description</label>
                                                        <textarea name="event_description" class="form-control event-description" id="event-description<?php echo $counter; ?>" rows="12" cols="50"><?php echo ($rows['short_description']); ?></textarea>
                                                    </div>
                                                    <div class="btn_save text-right">
                                                        <button type="submit" class="btn btn-primary" name="update_btn">Save
                                                            Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        CKEDITOR.replace('event-description<?php echo $counter; ?>');
                                    });
                                </script>
                                <?php
                                }
                                $counter++;
                            }
                             ?>
                        </tbody>
                        <thead>
                            <tr class="mhead">
                                <th id="events_credits">Events Credits</th>
                            </tr>
                            <tr>
                                <th class="thead_subhead">Registration</th>
                                <th class="thead_date">Date</th>
                                <th class="thead_token">Total Registrants</th>
                                <th style="width: 35%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach ($data as $rows){
							    $postUrl = 'https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=' . $rows['post_id'];
                                if($rows['type']== 'credit form'){
                                ?>
                                <tr>
                                    <td><?php echo ($rows['subheading']); ?></td>
                                    <td><?php echo !empty($rows['post_start_date']) ? date('d F Y', strtotime($rows['post_start_date'])) : ''; ?></td>
                                    <td class="text-center">
                                        <?php 
                                        $countReg = mysql_fetch_assoc(mysql_query("SELECT COUNT(form_id)AS total_registrants FROM `supplier_registration_form` WHERE form_id = ".$rows['id'].";"));

										if($rows['type'] == 'credit form'){
											echo $countReg['total_registrants'];
										}else{ ?>
											<a href="<?= $postUrl ?>" target="_blank" class="btn-link nopad">
												<strong><?php echo $countReg['total_registrants'] ?></strong>
											</a>
										<?php } ?>
                                    </td>
                                    <td class="action_btn">
                                        <button class="edit-btn btn-pr" data-toggle="modal" data-target="#largeModal<?php echo $counter; ?>">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>Edit
                                        </button>
                                        <form action="" method="post" class="delete_form" onclick="return confirmDelete(event, '<?php echo $rows['id']; ?>');">
                                            <button class="delete-btn" type="button" name="delete_btn" value="<?php echo $rows['id']; ?>">
                                                <i class="fa fa-trash" aria-hidden="true"></i>Delete
                                            </button>
                                        </form>
                                        <button class="copy_btn" data-toggle="modal" data-token="<?php echo $rows['token']; ?>" data-target="<?php echo $counter; ?>" data-coupon="<?php echo $rows['type']; ?>">
                                            <i class="fa fa-files-o" aria-hidden="true"></i>Reg Link
                                        </button>
                                        <?php 
                                        $query = mysql_query("SELECT COUNT(event_id) AS event FROM data_posts WHERE data_id = 75 AND post_status = 1 AND post_category = 'provisional_credits' AND event_id =". $rows['post_id'] ) ;
                                        if (mysql_result($query, 0, 'event') > 0) { ?>
                                            <button class="copy_btn" data-toggle="modal" data-token="pre-register=<?php echo $rows['post_id'] ?>" data-target="<?php echo $counter; ?>" data-coupon="pre-registration">
                                                <i class="fa fa-files-o" aria-hidden="true"></i>Pre-Reg Link
                                            </button>
                                        <?php } ?>

                                    </td>
                                </tr>
                                <!-- Edit Modal -->
                                <div class="modal" id="largeModal<?php echo $counter; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <?php if ($rows['type'] == 'registration form') { ?>
                                                    <h4 class="modal-title" id="modalLabelLarge">Edit Application Form</h4>
                                                <?php } else { ?>
                                                    <h4 class="modal-title" id="modalLabelLarge">Edit Credit Form</h4>
                                                <?php } ?>

                                            </div>

                                            <div class="modal-body">
                                                <form action="" method="post" class="edit_form" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control" name="event_hide" value="<?php echo ($rows['id']); ?>">
                                                    </div>
                                                    <!-- Application Edit Form -->
                                                    <?php if ($rows['type'] == 'registration form') { ?>
                                                        <div class="form-group">
                                                            <label for="registration_status">Status</label>&nbsp; &nbsp;&nbsp;
                                                            <label for="status_publish2" class="radio-inline">
                                                                <input name="registration_status" id="status_publish2" value="1" type="radio"
                                                                <?php if ($rows['status'] == 1) echo 'checked'; ?>>
                                                                Publish
                                                            </label>
                                                            <label for="status_draft2" class="radio-inline">
                                                                <input name="registration_status" id="status_draft2" value="2" type="radio"
                                                                <?php if ($rows['status'] == 2) echo 'checked'; ?>>
                                                                Draft
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="eventname">Choose Event</label>
                                                            <select class="form-control" name="eventname">
                                                                <option value="" disabled selected>Select Event</option>
                                                                <?php
                                                                // $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '8' AND post_status = '1' ORDER BY `post_id` DESC");
                                                                // while ($row = mysql_fetch_assoc($selectquery)) {
                                                                //     echo '<option value="' . $row['post_id'] . '">' . $row['post_title'] . '</option>';
                                                                // }
                                                                $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '73' AND post_status = '1' ORDER BY `post_id` DESC");
                                                                while ($row = mysql_fetch_assoc($selectquery)) {
                                                                    $selected = ($row['post_id'] == $rows['event_id']) ? 'selected' : '';
                                                                    echo '<option value="' . $row['post_id'] . '" ' . $selected . '>' . $row['post_title'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="eventhead">Event Heading</label>
                                                            <input type="text" class="form-control subheading-input" name="eventhead" value="<?php echo ($rows['subheading']); ?>" required>
                                                            <p class="error-message"></p>
                                                        </div>
                                                        <div class="form-group" id="image_section<?php echo $counter; ?>">
                                                            <label>Event Images</label>
                                                            <div class="image_preview_main"></div>
                                                            <div style="width: 600px;height: 364px;" class="center-block">
                                                                <?php $src = ($rows['event_image'] != "") ? "https://www.motiv8search.com/images/".$rows['event_image'] : "https://www.motiv8search.com/images/1709386060_7f878ed5-ee52-4c1f-a5e0-92f3cfc91942.jpg";?>
                                                                <img id="preview_img<?php echo $counter; ?>" class='img-thumbnail center-block' src="<?= $src ?>" alt="preview image" style="display: block; max-width: 100%;">
                                                            </div>
                                                            <div class="image_action row">
                                                                <div class="col-md-12 text-center" style="padding: 15px;">
                                                                    <label class="btn btn-primary" for="event_image<?php echo $counter; ?>" id="upload_img<?php echo $counter; ?>">
                                                                        <span class="glyphicon glyphicon-folder-open"></span> Upload Image </label>
                                                                    <input type="file" accept="image/*" class="form-control" id="event_image<?php echo $counter; ?>" name="edit_event_image" <?php echo ($rows['event_image'] != " ") ? " ": 'required'; ?> style="display: none;">
																	<input type="hidden" name="previous_image" id="previous_image<?php echo $counter; ?>" value="<?= $rows['event_image'] ?>">
                                                                    <input type="hidden" name="edit_imagecropped" id="imagecropped<?php echo $counter; ?>" value="">
                                                                    <button style="display: none;" type="button" class="btn btn-primary bold col-md-6 croper-action" id="crop_submit<?php echo $counter; ?>" data-method="crop" data-option="crop" title="Crop" data-origin="photo" disabled="disabled"> Crop &amp; Upload </button>
                                                                    <button style="display: none;" type="button" class="btn btn-default bold col-md-4 col-md-offset-1 croper-action" id="cancel_submit<?php echo $counter; ?>" data-method="cancel" data-option="cancel" title="Crop" data-origin="photo" disabled="disabled"> cancel <i class="fa fa-close"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="image-controls center col-md-12 hidden">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="zoom" data-option="0.1" title="Zoom In" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="">
                                                                                <span class="fa fa-search-plus"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="zoom" data-option="-0.1" title="Zoom Out" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="">
                                                                                <span class="fa fa-search-minus"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="rotate" data-option="-45" title="Rotate Left" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Rotate Left">
                                                                                <span class="fa fa-rotate-left"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="rotate" data-option="45" title="Rotate Right" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Rotate Right">
                                                                                <span class="fa fa-rotate-right"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="reset" data-option="cropper.reset()" title="Reset" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Reset">
                                                                                <span class="fa fa-retweet"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="crop" data-option="crop" title="Crop" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Crop">
                                                                                <span class="fa fa-crop"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="scaleX" data-option="-1" title="Flip Horizontal" data-origin="photo">
                                                                            <span class="docs-tooltip" title="Flip Horizontal">
                                                                                <span class="fa fa-arrows-h"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="scaleY" data-option="-1" title="Flip Vertical" data-origin="photo">
                                                                            <span class="docs-tooltip" title="Flip Vertical">
                                                                                <span class="fa fa-arrows-v"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="range_zoom-in_out row">
                                                                        <div class="btn-group center-block" style=" width: 50%;">
                                                                            <input type="range" id="zoomRange<?php echo $counter; ?>" min="0.1" data-method="zoom" max="2" step="0.1" value="0.5">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <small class="text-danger">
                                                                        <b>The image must be exactly 800 pixels in width and 480 pixels in height.</b>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $("#upload_img<?php echo $counter; ?>").click(function(e) {
                                                                    //e.preventDefault();
                                                                    var $imageInput = $("#event_image<?php echo $counter; ?>");
                                                                    var $preview = $("#preview_img<?php echo $counter; ?>");
                                                                    var $zoomrange = $("#zoomRange<?php echo $counter; ?>");
                                                                    var $imagecropped = $("#imagecropped<?php echo $counter; ?>");
                                                                    var $canclebtn = $("#cancel_submit<?php echo $counter; ?>");
                                                                    var $cropbtn = $("#crop_submit<?php echo $counter; ?>");
                                                                    var $uploadimg = $("#upload_img<?php echo $counter; ?>");
                                                                    cropperdImageUpload($imageInput, $preview, $zoomrange, $imagecropped, $canclebtn, $cropbtn, $uploadimg);
                                                                });

                                                            });
                                                        </script>

                                                        <div class="form-group">
                                                            <label for="desktoppackage">Enter amount for desktop package</label>
                                                            <input type="text" class="form-control subheading-input" name="desktop_packageamount" value="<?php echo ($rows['desktop_package']); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="superboothpackage">Enter amount for superbooth
                                                                package</label>
                                                            <input type="text" class="form-control subheading-input" name="superbooth_packageamount" value="<?php echo ($rows['superbooth_package']); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="add-ons">Choose Add-Ons</label>
                                                            <?php
                                                            $select_sql_add_ons = "SELECT * FROM `add_ons` ORDER BY `add_ons`.`id` ASC";
                                                            $result_add_ons = mysql_query($select_sql_add_ons);

                                                            if (mysql_num_rows($result_add_ons) > 0) {
                                                                // Get the comma-separated string of add-ons IDs from the database
                                                                $storedAddons = $rows['add_ons_id'];
                                                                $storedAddonsArray = explode(',', $storedAddons);

                                                                while ($lists = mysql_fetch_assoc($result_add_ons)) {
                                                                    // Check if the current add-on ID exists in the stored array
                                                                    $checked = (in_array($lists['id'], $storedAddonsArray)) ? 'checked' : '';
                                                            ?>
                                                                    <div class="checkbox-group">
                                                                        <input type="checkbox" name="addons_edit[]" id="add-ons" value="<?= $lists['id'] ?>" class="checkbox checkbox-addons col-md-3" <?= $checked ?>>
                                                                        <span class="add-ons-name"><?= $lists['name'] ?></span>
                                                                        <span class="add-ons-price"><?= $lists['price'] ?></span>
                                                                        <span class="add-ons-label">
                                                                            <span class="badge">
                                                                                <?php if ($lists['label'] == 1) {
                                                                                    echo "<span class='badge'>Presentation Add-On</span>";
                                                                                }
                                                                                if ($lists['label'] == 2) {
                                                                                    echo "<span class='badge'>Additional Add-Ons</span>";
                                                                                } ?>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <!-- Edit Promo Code Form -->
                                                        <div class="form-group">
                                                            <label for="registration_status">Status</label>&nbsp; &nbsp;&nbsp;
                                                            <label for="status_publish4" class="radio-inline">
                                                                <input name="registration_status" id="status_publish4" value="1" type="radio"
                                                                <?php if ($rows['status'] == 1) echo 'checked'; ?>>
                                                                Publish
                                                            </label>
                                                            <label for="status_draft4" class="radio-inline">
                                                                <input name="registration_status" id="status_draft4" value="2" type="radio"
                                                                <?php if ($rows['status'] == 2) echo 'checked'; ?>>
                                                                Draft
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="subheading">Event heading</label>
                                                            <input type="text" class="form-control subheading-input" name="eventhead" value="<?php echo ($rows['subheading']); ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="desktoppackage">Enter Package price</label>
                                                            <input type="text" class="form-control subheading-input" id="actulaprice_credit<?php echo $counter; ?>" name="actual_price" value="<?= $rows['actual_price'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="desktoppackage">Enter discount Percentage <i class="fa fa-percentage"></i></label>
                                                                    <input type="text" class="form-control subheading-input" id="discount_credit<?php echo $counter; ?>" name="discount" value="<?= $rows['discount'] ?>" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="desktoppackage">Discounted Price <i class="fa fa-percentage"></i></label>
                                                                    <input type="text" class="form-control subheading-input" id="desktpc_credit<?php echo $counter; ?>" name="desktop_packageamount" value="<?php echo ($rows['desktop_package']); ?>" readonly style="font-size: 20px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="eventCredits">Enter number credits</label>
                                                            <input type="text" class="form-control subheading-input" name="superbooth_packageamount" value="<?php echo ($rows['superbooth_package']); ?>" required>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $(document.body).on('change', '#discount_credit<?php echo $counter; ?>, #actulaprice_credit<?php echo $counter; ?>', function() {
                                                                    let discountValue = $('#discount_credit<?php echo $counter; ?>').val();
                                                                    let actualPriceValue = $('#actulaprice_credit<?php echo $counter; ?>').val();
                                                                    let desktopPackageInput = $('#desktpc_credit<?php echo $counter; ?>');
                                                                    calculateDiscountedPrice(discountValue, actualPriceValue, desktopPackageInput);
                                                                });
                                                            });
                                                        </script>

                                                    <?php } ?>
                                                    <div class="form-group">
                                                        <label for="description">Short description</label>
                                                        <textarea name="event_description" class="form-control event-description" id="event-description<?php echo $counter; ?>" rows="12" cols="50"><?php echo ($rows['short_description']); ?></textarea>
                                                    </div>
                                                    <div class="btn_save text-right">
                                                        <button type="submit" class="btn btn-primary" name="update_btn">Save
                                                            Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        CKEDITOR.replace('event-description<?php echo $counter; ?>');
                                    });
                                </script>
                                <?php
                                }
                                $counter++;
                            }
                             ?>
                        </tbody>
                        <thead>
                            <tr class="mhead">
                                <th id="past_events">Past Events</th>
                            </tr>
                            <tr>
                                <th class="thead_subhead">Registration</th>
                                <th class="thead_date">Date</th>
                                <th class="thead_token">Total Registrants</th>
                                <th style="width: 35%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                               // $counter = 1;
                                foreach ($data as $rows){
							    $postUrl = 'https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=' . $rows['post_id'];
                                if($rows['type']!== 'credit form' && $rows['post_start_date'] < $current_date){
                                ?>
                                <tr>
                                    <td><?php echo ($rows['subheading']); ?></td>
                                    <td><?php echo !empty($rows['post_start_date']) ? date('d F Y', strtotime($rows['post_start_date'])) : ''; ?></td>
                                    <td class="text-center">
                                        <?php 
                                        $countReg = mysql_fetch_assoc(mysql_query("SELECT COUNT(form_id)AS total_registrants FROM `supplier_registration_form` WHERE form_id = ".$rows['id'].";"));

										if($rows['type'] == 'credit form'){
											echo $countReg['total_registrants'];
										}else{ ?>
											<a href="<?= $postUrl ?>" target="_blank" class="btn-link nopad">
												<strong><?php echo $countReg['total_registrants'] ?></strong>
											</a>
										<?php } ?>
                                    </td>
                                    <td class="action_btn">
                                        <button class="edit-btn btn-pr" data-toggle="modal" data-target="#largeModal<?php echo $counter; ?>">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>Edit
                                        </button>
                                        <form action="" method="post" class="delete_form" onclick="return confirmDelete(event, '<?php echo $rows['id']; ?>');">
                                            <button class="delete-btn" type="button" name="delete_btn" value="<?php echo $rows['id']; ?>">
                                                <i class="fa fa-trash" aria-hidden="true"></i>Delete
                                            </button>
                                        </form>
                                        <button class="copy_btn" data-toggle="modal" data-token="<?php echo $rows['token']; ?>" data-target="<?php echo $counter; ?>" data-coupon="<?php echo $rows['type']; ?>">
                                            <i class="fa fa-files-o" aria-hidden="true"></i>Reg Link
                                        </button>
                                        <?php 
                                        $query = mysql_query("SELECT COUNT(event_id) AS event FROM data_posts WHERE data_id = 75 AND post_status = 1 AND post_category = 'provisional_credits' AND event_id =". $rows['post_id'] ) ;
                                        if (mysql_result($query, 0, 'event') > 0) { ?>
                                            <button class="copy_btn" data-toggle="modal" data-token="pre-register=<?php echo $rows['post_id'] ?>" data-target="<?php echo $counter; ?>" data-coupon="pre-registration">
                                                <i class="fa fa-files-o" aria-hidden="true"></i>Pre-Reg Link
                                            </button>
                                        <?php } ?>

                                    </td>
                                </tr>
                                <!-- Edit Modal -->
                                <div class="modal" id="largeModal<?php echo $counter; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <?php if ($rows['type'] == 'registration form') { ?>
                                                    <h4 class="modal-title" id="modalLabelLarge">Edit Application Form</h4>
                                                <?php } else { ?>
                                                    <h4 class="modal-title" id="modalLabelLarge">Edit Credit Form</h4>
                                                <?php } ?>

                                            </div>

                                            <div class="modal-body">
                                                <form action="" method="post" class="edit_form" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control" name="event_hide" value="<?php echo ($rows['id']); ?>">
                                                    </div>
                                                    <!-- Application Edit Form -->
                                                    <?php if ($rows['type'] == 'registration form') { ?>
                                                        <div class="form-group">
                                                            <label for="registration_status">Status </label>&nbsp;&nbsp;&nbsp;
                                                            <label for="status_publish3" class="radio-inline">
                                                                <input name="registration_status" id="status_publish3" value="1" type="radio"
                                                                <?php if ($rows['status'] == 1) echo 'checked'; ?>>
                                                                Publish
                                                            </label>
                                                            <label for="status_draft3" class="radio-inline">
                                                                <input name="registration_status" id="status_draft3" value="2" type="radio"
                                                                <?php if ($rows['status'] == 2) echo 'checked'; ?>>
                                                                Draft
                                                            </label>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="eventname">Choose Event</label>
                                                            <select class="form-control" name="eventname">
                                                                <option value="" disabled selected>Select Event</option>
                                                                <?php
                                                                // $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '8' AND post_status = '1' ORDER BY `post_id` DESC");
                                                                // while ($row = mysql_fetch_assoc($selectquery)) {
                                                                //     echo '<option value="' . $row['post_id'] . '">' . $row['post_title'] . '</option>';
                                                                // }
                                                                $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '73' AND post_status = '1' ORDER BY `post_id` DESC");
                                                                while ($row = mysql_fetch_assoc($selectquery)) {
                                                                    $selected = ($row['post_id'] == $rows['event_id']) ? 'selected' : '';
                                                                    echo '<option value="' . $row['post_id'] . '" ' . $selected . '>' . $row['post_title'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="eventhead">Event Heading</label>
                                                            <input type="text" class="form-control subheading-input" name="eventhead" value="<?php echo ($rows['subheading']); ?>" required>
                                                            <p class="error-message"></p>
                                                        </div>
                                                        <div class="form-group" id="image_section<?php echo $counter; ?>">
                                                            <label>Event Images</label>
                                                            <div class="image_preview_main"></div>
                                                            <div style="width: 600px;height: 364px;" class="center-block">
                                                                <?php $src = ($rows['event_image'] != "") ? "https://www.motiv8search.com/images/".$rows['event_image'] : "https://www.motiv8search.com/images/1709386060_7f878ed5-ee52-4c1f-a5e0-92f3cfc91942.jpg";?>
                                                                <img id="preview_img<?php echo $counter; ?>" class='img-thumbnail center-block' src="<?= $src ?>" alt="preview image" style="display: block; max-width: 100%;">
                                                            </div>
                                                            <div class="image_action row">
                                                                <div class="col-md-12 text-center" style="padding: 15px;">
                                                                    <label class="btn btn-primary" for="event_image<?php echo $counter; ?>" id="upload_img<?php echo $counter; ?>">
                                                                        <span class="glyphicon glyphicon-folder-open"></span> Upload Image </label>
                                                                    <input type="file" accept="image/*" class="form-control" id="event_image<?php echo $counter; ?>" name="edit_event_image" <?php echo ($rows['event_image'] != " ") ? " ": 'required'; ?> style="display: none;">
																	<input type="hidden" name="previous_image" id="previous_image<?php echo $counter; ?>" value="<?= $rows['event_image'] ?>">
                                                                    <input type="hidden" name="edit_imagecropped" id="imagecropped<?php echo $counter; ?>" value="">
                                                                    <button style="display: none;" type="button" class="btn btn-primary bold col-md-6 croper-action" id="crop_submit<?php echo $counter; ?>" data-method="crop" data-option="crop" title="Crop" data-origin="photo" disabled="disabled"> Crop &amp; Upload </button>
                                                                    <button style="display: none;" type="button" class="btn btn-default bold col-md-4 col-md-offset-1 croper-action" id="cancel_submit<?php echo $counter; ?>" data-method="cancel" data-option="cancel" title="Crop" data-origin="photo" disabled="disabled"> cancel <i class="fa fa-close"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="image-controls center col-md-12 hidden">
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="zoom" data-option="0.1" title="Zoom In" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="">
                                                                                <span class="fa fa-search-plus"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="zoom" data-option="-0.1" title="Zoom Out" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="">
                                                                                <span class="fa fa-search-minus"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="rotate" data-option="-45" title="Rotate Left" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Rotate Left">
                                                                                <span class="fa fa-rotate-left"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="rotate" data-option="45" title="Rotate Right" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Rotate Right">
                                                                                <span class="fa fa-rotate-right"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="reset" data-option="cropper.reset()" title="Reset" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Reset">
                                                                                <span class="fa fa-retweet"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="crop" data-option="crop" title="Crop" data-origin="photo" disabled="disabled">
                                                                            <span class="docs-tooltip" title="Crop">
                                                                                <span class="fa fa-crop"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="scaleX" data-option="-1" title="Flip Horizontal" data-origin="photo">
                                                                            <span class="docs-tooltip" title="Flip Horizontal">
                                                                                <span class="fa fa-arrows-h"></span>
                                                                            </span>
                                                                        </button>
                                                                        <button type="button" class="btn btn-primary croper-action" data-method="scaleY" data-option="-1" title="Flip Vertical" data-origin="photo">
                                                                            <span class="docs-tooltip" title="Flip Vertical">
                                                                                <span class="fa fa-arrows-v"></span>
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="range_zoom-in_out row">
                                                                        <div class="btn-group center-block" style=" width: 50%;">
                                                                            <input type="range" id="zoomRange<?php echo $counter; ?>" min="0.1" data-method="zoom" max="2" step="0.1" value="0.5">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <small class="text-danger">
                                                                        <b>The image must be exactly 800 pixels in width and 480 pixels in height.</b>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $("#upload_img<?php echo $counter; ?>").click(function(e) {
                                                                    //e.preventDefault();
                                                                    var $imageInput = $("#event_image<?php echo $counter; ?>");
                                                                    var $preview = $("#preview_img<?php echo $counter; ?>");
                                                                    var $zoomrange = $("#zoomRange<?php echo $counter; ?>");
                                                                    var $imagecropped = $("#imagecropped<?php echo $counter; ?>");
                                                                    var $canclebtn = $("#cancel_submit<?php echo $counter; ?>");
                                                                    var $cropbtn = $("#crop_submit<?php echo $counter; ?>");
                                                                    var $uploadimg = $("#upload_img<?php echo $counter; ?>");
                                                                    cropperdImageUpload($imageInput, $preview, $zoomrange, $imagecropped, $canclebtn, $cropbtn, $uploadimg);
                                                                });

                                                            });
                                                        </script>

                                                        <div class="form-group">
                                                            <label for="desktoppackage">Enter amount for desktop package</label>
                                                            <input type="text" class="form-control subheading-input" name="desktop_packageamount" value="<?php echo ($rows['desktop_package']); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="superboothpackage">Enter amount for superbooth
                                                                package</label>
                                                            <input type="text" class="form-control subheading-input" name="superbooth_packageamount" value="<?php echo ($rows['superbooth_package']); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="add-ons">Choose Add-Ons</label>
                                                            <?php
                                                            $select_sql_add_ons = "SELECT * FROM `add_ons` ORDER BY `add_ons`.`id` ASC";
                                                            $result_add_ons = mysql_query($select_sql_add_ons);

                                                            if (mysql_num_rows($result_add_ons) > 0) {
                                                                // Get the comma-separated string of add-ons IDs from the database
                                                                $storedAddons = $rows['add_ons_id'];
                                                                $storedAddonsArray = explode(',', $storedAddons);

                                                                while ($lists = mysql_fetch_assoc($result_add_ons)) {
                                                                    // Check if the current add-on ID exists in the stored array
                                                                    $checked = (in_array($lists['id'], $storedAddonsArray)) ? 'checked' : '';
                                                            ?>
                                                                    <div class="checkbox-group">
                                                                        <input type="checkbox" name="addons_edit[]" id="add-ons" value="<?= $lists['id'] ?>" class="checkbox checkbox-addons col-md-3" <?= $checked ?>>
                                                                        <span class="add-ons-name"><?= $lists['name'] ?></span>
                                                                        <span class="add-ons-price"><?= $lists['price'] ?></span>
                                                                        <span class="add-ons-label">
                                                                            <span class="badge">
                                                                                <?php if ($lists['label'] == 1) {
                                                                                    echo "<span class='badge'>Presentation Add-On</span>";
                                                                                }
                                                                                if ($lists['label'] == 2) {
                                                                                    echo "<span class='badge'>Additional Add-Ons</span>";
                                                                                } ?>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <!-- Edit Promo Code Form -->
                                                        <div class="form-group">
                                                            <label for="subheading">Event heading</label>
                                                            <input type="text" class="form-control subheading-input" name="eventhead" value="<?php echo ($rows['subheading']); ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="desktoppackage">Enter Package price</label>
                                                            <input type="text" class="form-control subheading-input" id="actulaprice_credit<?php echo $counter; ?>" name="actual_price" value="<?= $rows['actual_price'] ?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="desktoppackage">Enter discount Percentage <i class="fa fa-percentage"></i></label>
                                                                    <input type="text" class="form-control subheading-input" id="discount_credit<?php echo $counter; ?>" name="discount" value="<?= $rows['discount'] ?>" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="desktoppackage">Discounted Price <i class="fa fa-percentage"></i></label>
                                                                    <input type="text" class="form-control subheading-input" id="desktpc_credit<?php echo $counter; ?>" name="desktop_packageamount" value="<?php echo ($rows['desktop_package']); ?>" readonly style="font-size: 20px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="eventCredits">Enter number credits</label>
                                                            <input type="text" class="form-control subheading-input" name="superbooth_packageamount" value="<?php echo ($rows['superbooth_package']); ?>" required>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $(document.body).on('change', '#discount_credit<?php echo $counter; ?>, #actulaprice_credit<?php echo $counter; ?>', function() {
                                                                    let discountValue = $('#discount_credit<?php echo $counter; ?>').val();
                                                                    let actualPriceValue = $('#actulaprice_credit<?php echo $counter; ?>').val();
                                                                    let desktopPackageInput = $('#desktpc_credit<?php echo $counter; ?>');
                                                                    calculateDiscountedPrice(discountValue, actualPriceValue, desktopPackageInput);
                                                                });
                                                            });
                                                        </script>

                                                    <?php } ?>
                                                    <div class="form-group">
                                                        <label for="description">Short description</label>
                                                        <textarea name="event_description" class="form-control event-description" id="event-description<?php echo $counter; ?>" rows="12" cols="50"><?php echo ($rows['short_description']); ?></textarea>
                                                    </div>
                                                    <div class="btn_save text-right">
                                                        <button type="submit" class="btn btn-primary" name="update_btn">Save
                                                            Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        CKEDITOR.replace('event-description<?php echo $counter; ?>');
                                    });
                                </script>
                                <?php
                                }
                                $counter++;
                                }
                            
                             ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    $('#event_names_select').select2();

    $('#event_names_select').on('select2:opening select2:closing', function(event) {
        var $searchfield = $(this).parent().find('.select2-search__field');
        $searchfield.prop('disabled', true);
    });
</script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('description_promo');
    });
</script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('description_app');
    });
</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<script>
    function calculateDiscountedPrice(discountValue, actualPriceValue, desktopPackageInput) {
        var discount = parseFloat(discountValue);
        var actualPrice = parseFloat(actualPriceValue);

        // Check if both values are valid positive numbers
        if (!isNaN(discount) && !isNaN(actualPrice) && discount >= 0 && actualPrice >= 0) {
            // Calculate the discounted price
            var discountedPrice = actualPrice - (actualPrice * (discount / 100));

            // Round down the discounted price to the nearest integer
            discountedPrice = Math.floor(discountedPrice);

            // Set the result in the "desktop_package" input of the current row
            $(desktopPackageInput).val(discountedPrice);
        } else {
            // Handle invalid input (e.g., display an error message)
            $(desktopPackageInput).val('Invalid input');
        }
    }

    $(document).ready(function() {
        $(document.body).on('change', '#discount_credit, #actulaprice_credit', function() {
            let discountValue = $('#discount_credit').val();
            let actualPriceValue = $('#actulaprice_credit').val();
            let desktopPackageInput = $('#desktpc_credit');
            calculateDiscountedPrice(discountValue, actualPriceValue, desktopPackageInput);
        });
    });
</script>

<!-- <script>
    $(document).ready(function() {
        var file = '';
        var tmpName = '';
        var fileSize = '';
        var fileName = '';
        var fileType = '';
        var oldimage = '';
        var $imageInput = $("#event_image");
        var $preview = $("#preview_img");
        var $currentCrop = null;
        var oldimage = $preview.attr("src");
        const fillColor = '<?php $rgbarr = explode(",", cleanRGBA($wa["custom_1"]), 3);
                            echo sprintf("#%02x%02x%02x", $rgbarr[0], $rgbarr[1], $rgbarr[2]); ?>';
        $imageInput.on("change", function() {
            var file = this.files[0];
            if (file) {
                const validationResult = validateImage(file);
                if (!validationResult.isValid) {
                    // Display error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: validationResult.errorMessage,
                    });
                    $preview.attr(src, oldimage);
                } else {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $preview.attr("src", e.target.result);
                        // Get image details
                        tmpName = file.name;
                        fileSize = file.size;
                        fileName = file.name;
                        fileType = file.type;
                        // Initialize the Cropper plugin
                        if ($currentCrop) {
                            $currentCrop.cropper('destroy');
                        }

                        $currentCrop = $preview.cropper({
                            aspectRatio: 1.7,
                            viewMode: 1,
                            dragMode: 'move',
                            cropBoxResizable: false,
                            cropBoxFixed: true,
                            minContainerWidth: 600,
                            minContainerHeight: 364
                            // minCropBoxWidth: 800, 
                            // minCropBoxHeight: 480
                        });
                    };
                    $('#upload_img').hide();
                    $('#crop_submit').show();
                    $('#cancel_submit').show();
                    $('.image-controls').removeClass('hidden');
                    $(".croper-action").attr("disabled", false);
                    reader.readAsDataURL(file);

                }

            }

        });
        //image controls
        $(document).on('click', '.croper-action', function() {
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
                    var imgData = {
                        croppedImage: croppedImageData,
                        width: croppedImgWidth,
                        height: croppedImgHeight,
                        tmpName: tmpName,
                        fileSize: fileSize,
                        fileName: fileName,
                        fileType: fileType
                        //application_image: 'applicationImage'
                        //updateId: update_booth_id
                    };
                    console.log(imgData);
                    currentCrop.attr("src", croppedImageData);
                    $('#upload_img').show();
                    $('#crop_submit').hide();
                    $('#cancel_submit').hide();
                    $('.image-controls').addClass('hidden');
                    $(".croper-action").attr("disabled", true);
                    // Send the data to the server using AJAX
                    $.ajax({
                        type: 'POST',
                        url: 'https://www.motiv8search.com/api/widget/json/get/ftpfilesupload',
                        data: imgData,
                        success: function(response) {
                            if (response.error == 0) {
                                $("#imagecropped").val(response.file);
                                $preview.attr("src", 'https://www.motiv8search.com' + response.imgUrl);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: response.message,
                                });
                                $preview.attr('src', oldimage);
                            }
                            console.log('Image data sent successfully.');
                            console.log(response);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error sending image data:', textStatus, errorThrown);
                        }
                    });


                    // Clean up
                    $currentCrop.cropper("destroy");
                    //$imageInput.val("");
                    break;
                case 'zoom':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    break;
                case 'reset':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    break;
                case 'rotate':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    break;
                case 'scaleX':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    var currentValue = currentButton.data("option");

                    if (currentValue == 1) {
                        currentButton.data("option", "-1");

                    } else {
                        currentButton.data("option", "1");
                    }
                    break;
                case 'scaleY':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    var currentValue = currentButton.data("option");
                    if (currentValue == 1) {
                        currentButton.data("option", "-1");

                    } else {
                        currentButton.data("option", "1");
                    }
                    break;

                case 'cancel':
                    $currentCrop.cropper("destroy");
                    //$imageInput.val("");
                    currentCrop.attr("src", oldimage);
                    $('#upload_img').show();
                    $('#crop_submit').hide();
                    $('#cancel_submit').hide();
                    $('.image-controls').addClass('hidden');
                    $(".croper-action").attr("disabled", true);
                    break;

            }


        });
        $("#zoomRange").on("input", function() {
            var zoomValue = parseFloat($(this).val());
            console.log(zoomValue);

            if ($preview) {
                $preview.cropper("zoomTo", zoomValue);
                var croppedDataUrl = $preview.cropper("getCroppedCanvas").toDataURL("image/jpeg");

            }
        });

        function validateImage(file) {
                // Allowed mime types
                const mimeTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
                // Allowed file extensions
                const allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'bmp'];
                // Maximum file size in bytes (10 MB)
                const maxSize = 10 * 1024 * 1024;
                // Result object to store validation result and error messages
                const result = {
                    isValid: true,
                    errorMessage: ''
                };
                // Check if file type is allowed
                if (!mimeTypes.includes(file.type)) {
                    result.isValid = false;
                    result.errorMessage = 'Please upload a valid image file (GIF, JPEG, PNG, SVG, or WebP).';
                    return result;
                }
                // Check if file extension is allowed
                const fileExtension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExtension)) {
                    result.isValid = false;
                    result.errorMessage = 'Please upload a file with one of the following extensions: PNG, JPG, JPEG, GIF, SVG, BMP, or WebP.';
                    return result;
                }
                // Check if file size is within limit
                if (file.size > maxSize) {
                    result.isValid = false;
                    result.errorMessage = 'Please upload an image file smaller than 10 MB.';
                    return result;
                }

                // All validations passed
                return result;
        }

    });
</script> -->
<script>
    function cropperdImageUpload($imageInput, $preview, $zoomrange, $imagecropped, $canclebtn, $cropbtn, $uploadimg) {
        var file = '',tmpName = '',fileSize = '',fileName = '',fileType = '',oldimage = '',$currentCrop = null;
        const fillColor = '<?php $rgbarr = explode(",", cleanRGBA($wa["custom_1"]), 3); echo sprintf("#%02x%02x%02x", $rgbarr[0], $rgbarr[1], $rgbarr[2]); ?>';
        oldimage = $preview.attr("src");
        $imageInput.on("change", function() {
            file = this.files[0];
            if (file) {
                const validationResult = validateImage(file);
                if (!validationResult.isValid) {
                    // Display error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: validationResult.errorMessage,
                    });
                    $preview.attr(src, oldimage);
                } else {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $preview.attr("src", e.target.result);
                        // Get image details
                        tmpName = file.name;
                        fileSize = file.size;
                        fileName = file.name;
                        fileType = file.type;
                        // Initialize the Cropper plugin
                        // if ($currentCrop) {
                        //     $currentCrop.cropper('destroy');
                        // }

                        $currentCrop = $preview.cropper({
                            aspectRatio: 1.7,
                            viewMode: 1,
                            dragMode: 'move',
                            cropBoxResizable: false,
                            cropBoxFixed: true,
                            minContainerWidth: 600,
                            minContainerHeight: 364
                            // minCropBoxWidth: 800, 
                            // minCropBoxHeight: 480
                        });
                    };
                    $uploadimg.hide();
                    $cropbtn.show();
                    $canclebtn.show();
                    $('.image-controls').removeClass('hidden');
                    $(".croper-action").attr("disabled", false);
                    reader.readAsDataURL(file);

                }
            }
        });
        //image controls
        $(document).on('click', '.croper-action', function() {
            var currentButton = $(this);
            var dataMethod = currentButton.data('method');
            var currentCrop = $preview;
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
                    var imgData = {
                        croppedImage: croppedImageData,
                        width: croppedImgWidth,
                        height: croppedImgHeight,
                        tmpName: tmpName,
                        fileSize: fileSize,
                        fileName: fileName,
                        fileType: fileType
                        //application_image: 'applicationImage'
                        //updateId: update_booth_id
                    };
                    console.log(imgData);
                    currentCrop.attr("src", croppedImageData);
                    $uploadimg.show();
                    $cropbtn.hide();
                    $canclebtn.hide();
                    $('.image-controls').addClass('hidden');
                    $(".croper-action").attr("disabled", true);
                    // Send the data to the server using AJAX
                    $.ajax({
                        type: 'POST',
                        url: 'https://www.motiv8search.com/api/widget/json/get/ftpfilesupload',
                        data: imgData,
                        success: function(response) {
                            if (response.error == 0) {
                                $imagecropped.val(response.file);
                                $preview.attr("src", 'https://www.motiv8search.com' + response.imgUrl);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: response.message,
                                });
                                $preview.attr('src', oldimage);
                            }
                            console.log('Image data sent successfully.');
                            console.log(response);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error sending image data:', textStatus, errorThrown);
                        }
                    });


                    // Clean up
                    $currentCrop.cropper("destroy");
                    //$imageInput.val("");
                    break;
                case 'zoom':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    break;
                case 'reset':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    break;
                case 'rotate':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    break;
                case 'scaleX':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    var currentValue = currentButton.data("option");

                    if (currentValue == 1) {
                        currentButton.data("option", "-1");

                    } else {
                        currentButton.data("option", "1");
                    }
                    break;
                case 'scaleY':
                    currentCrop.cropper(dataMethod, currentButton.data('option'));
                    var currentValue = currentButton.data("option");
                    if (currentValue == 1) {
                        currentButton.data("option", "-1");

                    } else {
                        currentButton.data("option", "1");
                    }
                    break;

                case 'cancel':
                    $currentCrop.cropper("destroy");
                    //$imageInput.val("");
                    currentCrop.attr("src", oldimage);
                    $uploadimg.show();
                    $cropbtn.hide();
                    $canclebtn.hide();
                    $('.image-controls').addClass('hidden');
                    $(".croper-action").attr("disabled", true);
                    break;

            }


        });

        $zoomrange.on("input", function() {
            var zoomValue = parseFloat($(this).val());
            console.log(zoomValue);
            if ($preview) {
                $preview.cropper("zoomTo", zoomValue);
                var croppedDataUrl = $preview.cropper("getCroppedCanvas").toDataURL("image/jpeg");
            }
        });

        function validateImage(file) {
            // Allowed mime types
            const mimeTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
            // Allowed file extensions
            const allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'bmp'];
            // Maximum file size in bytes (10 MB)
            const maxSize = 10 * 1024 * 1024;
            // Result object to store validation result and error messages
            const result = {
                isValid: true,
                errorMessage: ''
            };
            // Check if file type is allowed
            if (!mimeTypes.includes(file.type)) {
                result.isValid = false;
                result.errorMessage = 'Please upload a valid image file (GIF, JPEG, PNG, SVG, or WebP).';
                return result;
            }
            // Check if file extension is allowed
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                result.isValid = false;
                result.errorMessage = 'Please upload a file with one of the following extensions: PNG, JPG, JPEG, GIF, SVG, BMP, or WebP.';
                return result;
            }
            // Check if file size is within limit
            if (file.size > maxSize) {
                result.isValid = false;
                result.errorMessage = 'Please upload an image file smaller than 10 MB.';
                return result;
            }

            // All validations passed
            return result;
        }
    }

    $(document).ready(function() {
        $("#upload_img").click(function(e) {
            //e.preventDefault();
            var $imageInput = $("#event_image");
            var $preview = $("#preview_img");
            var $zoomrange = $("#zoomRange");
            var $imagecropped = $("#imagecropped");
            var $canclebtn = $("#cancel_submit");
            var $cropbtn = $("#crop_submit");
            var $uploadimg = $('#upload_img');
            cropperdImageUpload($imageInput, $preview, $zoomrange, $imagecropped, $canclebtn, $cropbtn, $uploadimg);
        });

    });
</script>








<?php } else { ?>
<section>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Access Denied</h1>
        <p>You do not have the necessary permissions to view this page.</p>
      </div>
    </div>
  </div>
</section>
<?php } ?>