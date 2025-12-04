<?php 
//print_r($user);
$login_uid = $user['user_id'];
$login_email = $user['email'];

$supplier_id = $_COOKIE['userid'];
if (isset($_GET['confirm-attendance'], $_GET['token']) && $_GET['confirm-attendance'] == 1) {
    $token = mysql_real_escape_string($_GET['token']);
    $checkTokenSql = "SELECT * FROM attending_staff_attendance WHERE token = '$token' LIMIT 1";
    $result = mysql_query($checkTokenSql);
    
    if ($row = mysql_fetch_assoc($result)) {
        // if(isset($row['supplier_id'])){
        //     $supplier_id = $row['supplier_id'];
        // }
		if (empty($user['first_name']) && empty($user['last_name']) && empty($user['phone_number']) || empty($user['company'])) {
			echo "<script>
				setTimeout(() => {
					swal({
						title: 'Update Required!',
						text: 'Please update your basic details to continue.',
						icon: 'warning',
						buttons: false,
						closeOnClickOutside: false
					}).then(() => window.location.href = 'https://www.motiv8search.com/account/contact?redirect=/account/add-supplier-card/view/wip');
				}, 100);
			</script>";
		}
    } else {
        echo "<script>
            setTimeout(function() {
                swal({
                    title: 'Error!',
                    text: 'Invalid token. Please check the link or contact support.',
                    type: 'error'
                });
            }, 100);
        </script>";
    }
}
// Handle cancellation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_attended'])) {
    $post_id = intval($_POST['post_id']);
    $supplier_id = intval($_POST['supplier_id']);
    $staff_id = $_POST['staff_id']; // This can be either staff_id or staff_email
    
    if ($supplier_id == $_COOKIE['userid']) {
        if (filter_var($staff_id, FILTER_VALIDATE_EMAIL)) {
            // If staff_id is actually an email, handle deletion by email
            $delete_sql = "DELETE FROM attending_staff_attendance WHERE post_id = $post_id AND supplier_id = $supplier_id AND staff_email = '" . mysql_real_escape_string($staff_id) . "'";
            //$update_sql = "UPDATE attending_staff_attendance SET `status` = 'canceled' WHERE post_id = $post_id AND supplier_id = $supplier_id AND staff_email = '" . mysql_real_escape_string($staff_id) . "'";

        } else {
            // Otherwise, handle by staff_id
            $delete_sql = "DELETE FROM attending_staff_attendance WHERE post_id = $post_id  AND supplier_id = $supplier_id AND staff_id = " . intval($staff_id);
            //$update_sql = "UPDATE attending_staff_attendance SET `status` = 'canceled'WHERE post_id = $post_id AND supplier_id = $supplier_id AND staff_id = " . intval($staff_id);
        }

        // Execute the deletion query
        if (mysql_query($delete_sql)) {
            header("Location: https://www.motiv8search.com/account/add-supplier-card/view/wip");
            exit();
        } else {
            echo "<script>alert('Error cancelling invitation');</script>";
        }
    }
}

$complete = 'Published';
$incomplete = 'Incomplete';
$delete_label = 'Delete Supplier Card?';
$firstAccordion = true;

// Database query to fetch supplier data
// $sql = "SELECT lep.*, dp.post_title, dp.post_id, dp.post_start_date, dp.post_live_date, dp.post_expire_date, dp.post_token 
//         FROM live_events_posts AS lep 
//         JOIN data_posts AS dp ON lep.post_id = dp.post_id 
//         WHERE lep.user_id = '" . $supplier_id . "'  
//         ORDER BY ABS(dp.post_start_date - DATE_FORMAT(NOW(), '%Y%m%d')) ASC";

$where_str = " AND dp.post_start_date > DATE_FORMAT(NOW(), '%Y%m%d') "; // Compare as string

if (isset($_POST['event_type'])) {
    if ($_POST['event_type'] == "0") {
        $where_str = " AND dp.post_start_date > DATE_FORMAT(NOW(), '%Y%m%d') ";
    } else if ($_POST['event_type'] == "1") {
        $where_str = " AND STR_TO_DATE(dp.post_expire_date, '%Y%m%d') < CURDATE() ";
    }
}

$sql = "SELECT
          lep.*,
          dp.post_title,
          dp.post_id,
          dp.post_start_date,
          dp.post_live_date,
          dp.post_expire_date,
          dp.post_token,
          asa.supplier_id as sender_id
      FROM
          live_events_posts AS lep
      JOIN data_posts AS dp
      ON
          lep.post_id = dp.post_id
      LEFT JOIN attending_staff_attendance asa ON lep.id = asa.lep_id
      WHERE
          (lep.user_id = '$supplier_id' OR asa.supplier_id = '$supplier_id' OR asa.staff_id = '$supplier_id')
          $where_str
      GROUP BY lep.id
      ORDER BY dp.post_start_date ASC";

//echo $sql;

$postresults = mysql_query($sql);
$mysqlnumrows = mysql_num_rows($postresults);


function formatDeadlineCounter($post_live_date) {
    $date = DateTime::createFromFormat('YmdHis', $post_live_date);
    if (!$date) {
        return "Invalid date format";
    }
    $now = new DateTime();
    $diff = $date->getTimestamp() - $now->getTimestamp();
    if ($diff <= 0) {
        return "Deadline has passed";
    }
    $days = floor($diff / 86400);   
    $hours = floor(($diff % 86400) / 3600); 
    return sprintf(" %d Days, %02d Hours", $days, $hours);
}
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="account-form-box">
    <?php if ($mysqlnumrows == 0) { ?>
        <div class="new-element">
            <div class="row">
                <div class="col-md-12 bmargin">
                    <form class="form-inline pull-right bmargin" id="event-filter-form" action="" method="post">
                    <div class="form-group">
                        <select class="form-control" name="event_type" id="event_type">
                            <option value="0" selected>Upcoming Events</option>
                            <option value="1" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == "1") ? 'selected' : ''; ?>>Past Events</option>
                        </select>
                    </div>
                    <button type="submit" name="event_search" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <div class="col-md-12">
                    <a class="btn btn-success publish-post-button">Currently you don't have a Supplier Card</a>
                </div>
          </div>
        </div>
    <?php } else { ?>
        <div class="new-element">
          <div class="row">
            <div class="col-md-12 bmargin">
                <form class="form-inline pull-right bmargin" id="event-filter-form" action="" method="post">
                  <div class="form-group">
                    <select class="form-control" name="event_type" id="event_type">
                        <option value="0" selected>Upcoming Events</option>
                        <option value="1" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == "1") ? 'selected' : ''; ?>>Past Events</option>
                    </select>
                  </div>
                  <button type="submit" name="event_search" class="btn btn-primary">Search</button>
                </form>
            </div>
          </div>
            <div class="row">
                <div class="col-sm-12">
                    <table id="supplier-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="supplier-table-th" style="width: 200px;"></th>
                                <th class="supplier-table-th" style="width: 455px;"></th>
                                <th class="supplier-table-th" style="width: 144px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysql_fetch_assoc($postresults)) { 
                                // Fetch additional info link for supplier
                                $add_info_link_sql = "SELECT `value` FROM `users_meta` 
                                                     WHERE `key` = 'additional_info_link' 
                                                     AND `database_id` = '".$row['post_id']."' 
                                                     AND `database` = 'data_posts'";
                                $add_info_link_results = mysql_query($add_info_link_sql);
                                $add_info_link = mysql_fetch_assoc($add_info_link_results);
                                $start_date = $row['start_date'];
                                $start_time = $row['start_time'];
                                $eventdatetime = date("YmdHis", strtotime("$start_date $start_time"));
                                $packages = mysql_fetch_array(mysql_query(" SELECT packages_section FROM `supplier_registration_form` WHERE user_id = " . $row['user_id'] . " AND event_id = " . $row['post_id']));
                                // echo "SELECT packages_section FROM `supplier_registration_form` WHERE user_id = " . $row['user_id'] . " AND event_id = " . $row['post_id'];
                                $packages = $packages['packages_section'];
                                $maxStaff = ($packages == 'Desktop Package') ? 2 : (($packages == 'SuperBooth Package') ? 4 : 0);
                                //echo "Max Staff: $maxStaff";
                            ?>
                            <tr>
                                <!-- Supplier Picture and Status -->
                                <td>
                                    <div style="display:none"><?= $row['id'] ?></div>
                                    <div class='clearfix'></div>
                                    <?php if ($row['staus'] == 1 || $row['staus'] == 0) { ?>
                                        <div class="btn-xs bold line-height-xl center-block no-radius-bottom label-danger">
                                            <?= $incomplete ?>
                                        </div>
                                    <?php } elseif ($row['staus'] == 2) { ?>
                                        <div class="btn-xs bold line-height-xl center-block no-radius-bottom label-success">
                                            <?= $complete ?>
                                        </div>
                                    <?php } ?>
                                    <div class="alert-default btn-block text-center the-post-image">
                                        <img src="https://www.motiv8search.com/images/events/<?= $row['thumb_booth'] ?>" 
                                             alt="Event Image" width="100%">
                                    </div>
                                </td>

                                <!-- Supplier Description -->
                                <td>
                                    <h4 class="line-height-lg bold xs-nomargin post-title">
                                        <a href="/account/edit-supplier-card/edit?id=<?= $row['id'] ?>">
                                            <?= $row['post_title'] ?>
                                        </a>
                                    </h4>
                                    <div class="small bmargin hidden-xs">
                                        <p class="the-post-description">
                                            <?= substr(strip_tags($row['event_description']), 0, 200) ?>
                                        </p>
                                    </div>
                                    <span class='the-post-location small'><b>Presentation Slot:</b> 
                                        <?= $row['start_time'] . ' - ' . $row['end_time'] ?>
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="dropdown center-block">
                                        <a class="btn btn-primary bmargin btn-sm bold btn-block" 
                                           href="/account/edit-supplier-card/edit?id=<?= $row['id'] ?>">
                                           Edit Supplier Card
                                        </a>
                                        <a class="btn btn-sm bold btn-block <?php echo (isset($add_info_link['value']) && !empty($add_info_link['value'])) ? 'btn-primary' : 'btn-secondary disabled'; ?>" 
                                          href="<?php echo (isset($add_info_link['value']) && !empty($add_info_link['value'])) ? $add_info_link['value'] : '#'; ?>" 
                                          target="_blank">
                                          Event Info
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <!-- Accordion Row for Attending Staff -->
                            <tr>
                                <td colspan="3">
                                    <div class="panel-group" id="staff_accordion_<?= $row['id'] ?>">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" 
                                                    data-parent="#staff_accordion_<?= $row['id'] ?>" 
                                                    href="#staff_collapse_<?= $row['id'] ?>" 
                                                    <?= $firstAccordion ? 'aria-expanded="true"' : '' ?>>
                                                    <span class="pull-left">
                                                        <b>Attending Staff</b>
                                                    </span>
                                                        <span class="col-md-4"></span>
                                                        <span class="text-danger lpad">Deadline:<?= formatDeadlineCounter($eventdatetime) ?></span>
                                                        <i class="pull-right fa fa-caret-down" style="font-size: 25px;"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="staff_collapse_<?= $row['id'] ?>" class="panel-collapse collapse <?= $firstAccordion ? 'in' : '' ?>">
                                                <div class="panel-body" style="padding: 0;">
                                                    <table class="table table-bordered" style="background-color: transparent">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 352px;">Staff Name</th>
                                                                <th>Status</th>
                                                                <th>Registration</th>
                                                                <th class="text-center"></th>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                   <p class="textone bold text-muted small">
                                                                    Register yourself to attend the event.
                                                                   </p>
                                                                   <p class="texttwo bold text-muted small">
                                                                    At least one of you must be the Event Coordinator
                                                                   </p>
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody data-lep-id="<?= $row['id'] ?>" data-maxstaff="<?= $maxStaff ?>" data-post-title="<?= $row['post_title'] ?>" data-post-id="<?= $row['post_id'] ?>" data-post-date="<?= formatDeadlineCounter($eventdatetime) ?>">
                                                        <?php  
                                                            $isCoordinatorExists = false;
                                                            $attendingStaff = mysql_query("
                                                                SELECT a.supplier_id, a.staff_id, a.status, a.staff_email, CONCAT(u.first_name, ' ', u.last_name) AS name 
                                                                FROM attending_staff_attendance a
                                                                LEFT JOIN users_data u ON a.staff_id = u.user_id
                                                                WHERE a.post_id = " . intval($row['post_id']) . " 
                                                                AND a.supplier_id = " . intval($row['sender_id'])
                                                            );
                                                            // $staffCount = mysql_num_rows($attendingStaff);
                                                            $staffCount = 0;
                                                            while ($staff = mysql_fetch_assoc($attendingStaff)) {
                                                                if ($staff['status'] != 'canceled') {
                                                                    $staffCount++;
                                                                }
                                                                $checkCoordinatorQuery = "SELECT * FROM attending_supplier_staff_registration WHERE user_id =".$staff['staff_id']." AND supplier_id =".$staff['supplier_id']." AND post_id = ".$row['post_id']." AND is_event_coordinator = '1' LIMIT 1";
                                                                $result = mysql_query($checkCoordinatorQuery);
                                                                $isCoordinatorExists = ($result && mysql_num_rows($result) > 0) ? true : false;
                                                                $trColor = ($staff['status'] == 'I am going') ? 'success' : 'danger';
                                                                $CoordinatorLabel = ($isCoordinatorExists === true) ? "<b> Event Coordinator</b>" : '';
                                                                echo '<tr class="' . $trColor . '">';
                                                                echo '<td><span class="selected_staff">' . ($staff['staff_id'] > 0 ? htmlspecialchars($staff['name']) : htmlspecialchars($staff['staff_email'])) . '</span></td>';
                                                                echo '<td class="status">' . htmlspecialchars($staff['status']) . '</td>';
                                                                $staff_id = ($staff['staff_id'] > 0) ? $staff['staff_id'] : $staff['staff_email'];
                                                                if ($staff['status'] == 'Invited') {
                                                                    echo '<td class="register">Awaiting Registration'.  $CoordinatorLabel. ' </td>';
                                                                    if($row['sender_id'] == $supplier_id){
                                                                        echo '<td class="action">
                                                                        <form action="" method="post">
                                                                            <input type="hidden" name="post_id" value="'.$row['post_id'].'">
                                                                            <input type="hidden" name="supplier_id" value="'.$row['sender_id'].'">
                                                                            <input type="hidden" name="staff_id" value="'.$staff_id.'">
                                                                            <input type="hidden" name="delete_attended" value="delete">
                                                                            <button type="submit" class="btn btn-sm btn-danger  cancel-invite">
                                                                            <!-- <i class="fa fa-trash-o" aria-hidden="true"></i> -->
                                                                            Cancel Invitation
                                                                            </button>
                                                                        </form>
                                                                        </td>';
                                                                    }
                                                                } elseif ($staff['status'] == 'I am going') {
                                                                    echo '<td class="register">Registered & Going</td><td>'. $CoordinatorLabel.'</td>';
                                                                } elseif ($staff['status'] == 'canceled') {
                                                                    echo '<td class="register">Expired</td><td>'. $CoordinatorLabel.'</td>';
                                                                }
                                                                
                                                                echo '</tr>';
                                                            }
                                                            if ($staffCount >= $maxStaff) {
                                                                echo '<tr><td colspan="4" style="text-align: center; color: red;">Limit Reached: You can only add up to ' . $maxStaff . ' attending staff for this event.</td></tr>';
                                                            }
                                                    
                                                            ?>
                                                            <?php if (($staffCount < $maxStaff ) && $login_uid == $row['user_id']): ?>
                                                            <tr class="">
                                                                <td>
                                                                    <!-- <select class="form-control attending_staff" name="attending_staff[]">
                                                                        <option value="">Select Attending Staff</option>
                                                                        <?php
                                                                        /*
                                                                        $attendingStaffQuery = mysql_query("SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS `name`, u.email FROM users_data u WHERE FIND_IN_SET(u.user_id, (SELECT staff_ids FROM supplier_attendingstaffs));");
                                                                        while ($attendingStaff = mysql_fetch_assoc($attendingStaffQuery)) {
                                                                            echo '<option value="' . $attendingStaff['user_id'] . '" data-email="' . $attendingStaff['email'] . '">';
                                                                            echo $attendingStaff['name'];
                                                                            echo '</option>';
                                                                        } */
                                                                        ?>
                                                                    </select> -->
                                                                    <?php
                                                                    $attendingStaff = array();
                                                                    $q = mysql_query("SELECT staff_id FROM attending_staff_attendance WHERE post_id=" . intval($row['post_id']) . " AND supplier_id=" . intval($login_uid) . " AND `status` != 'Cancelled'"  );
                                                                    
                                                                    
                                                                    while ($s = mysql_fetch_assoc($q)) {$attendingStaff[] = $s['staff_id'];}
                                                                    $q = mysql_query("SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, email FROM users_data WHERE FIND_IN_SET(user_id, (SELECT GROUP_CONCAT(staff_ids) FROM supplier_attendingstaffs WHERE supplier_id = ".intval($login_uid)."))");
                                                                    ?>

                                                                    <select class="form-control attending_staff" name="attending_staff[]">
                                                                        <option value="">Select Attending Staff</option>
                                                                        <?php while ($s = mysql_fetch_assoc($q)) if (!in_array($s['user_id'], $attendingStaff)) { ?>
                                                                            <option value="<?= $s['user_id'] ?>" data-email="<?= $s['email'] ?>"><?= $s['name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>

                                                                    <span class="selected_staff" style="display: none; padding: 5px; margin-left: 10px;"></span>
                                                                </td>
                                                                <td class="status"></td>
                                                                <td class="register"></td>
                                                                <td class="action">
                                                                    <button type="button" class="btn btn-sm btn-success add-row" style="display: none;">
                                                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                                                    </button>
                                                                    <form action="" method="post">
                                                                         <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                                                                         <input type="hidden" name="supplier_id" value="<?= $supplier_id ?>">
                                                                         <input type="hidden" name="staff_id" class="staff-delete" value="<?= $staff['staff_id'] ?>">
                                                                         <input type="hidden" name="delete_attended" value="delete">
                                                                         <button type="submit" class="btn btn-sm btn-danger  cancel-invite" style="display: none;">
                                                                         <!-- <i class="fa fa-trash-o" aria-hidden="true"></i> -->
                                                                         Cancel Invitation
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php  
                             $firstAccordion = false;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } 
    ?>
</div>
<!-- Add Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php 
/*
<script>
    $(document).ready(function() {
        $('.publish-post-button').remove();

        function isValidEmail(email) {
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
        }
        // Object to store selected staff IDs for each post
        let selectedStaffIds = {};
        $('.attending_staff').select2({
            tags: true,
                placeholder: "Select or add staff email address",
                createTag: function(params) {
                    let email = params.term;
                    if (!isValidEmail(email)) {
                        return {
                            id: email,
                            text: "❌ Invalid Email",
                            newTag: true,
                            invalid: true
                        };
                    }

                    let name = email.split('@')[0]
                        .replace(/[._]/g, ' ')
                        .replace(/\b\w/g, c => c.toUpperCase());

                    return {
                        id: email,
                        text: name,
                        newTag: true
                    };
                }
        });

        // Handle change event for attending staff dropdown
        $(document).on('change', '.attending_staff', function() {
            const Element = $(this);
            const parentTbody = Element.closest('tbody');
            const postTitle = parentTbody.data('post-title');
            const postId = parentTbody.data('post-id');
            const postDate = parentTbody.data('post-date');
            
            var selectedOption = Element.find(":selected");
            const staffName = selectedOption.text();
            var staffId = selectedOption.val();
            var staffEmail = selectedOption.data("email");
            var supplierName = "<?= $user['company'] ?>";
            var supplierId = "<?= $user['user_id'] ?>";
            if (!selectedStaffIds[postId]) { selectedStaffIds[postId] = []; }
            // Validate email if manually entered
            if (!isValidEmail(staffId) && selectedOption.data('newTag')) {
                // If email is invalid, show error and prevent selection
                swal("Invalid Email", "Please enter a valid email address.", "error");
                
                // Mark the invalid email in red
                Element.next('.select2-container').find('.select2-selection__rendered').css('color', 'red');
                
                return false;
            } else {
                // If email is valid, reset the color
                Element.next('.select2-container').find('.select2-selection__rendered').css('color', 'black');
            }
            if (staffId && !selectedStaffIds[postId].includes(staffId)) {
                swal({
                    title: "Confirm Invitation!",
                    text: "Are you sure you want to invite " + staffName + " for this event?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willInvite) => {
                    if (willInvite) {
                        $.ajax({
                            url: "https://www.motiv8search.com/api/widget/json/get/invite_attending_staff",
                            type: "POST",
                            data: {
                                supplier_name: supplierName,
                                supplier_id: supplierId,
                                staff_id: staffId,
                                staff_name: staffName,
                                staff_email: staffEmail,
                                post_title: postTitle,
                                post_id: postId,
                                post_date: postDate
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Add the selected staff ID to the array for this post
                                    selectedStaffIds[postId].push(staffId);

                                    // Update the UI
                                    Element.closest('tr').find('.selected_staff').text(staffName).show();
                                    Element.closest('tr').find('.status').text('Invited');
                                    Element.closest('tr').find('.register').text('Awating Registration');
                                    Element.closest('tr').addClass('danger');
                                    Element.select2('destroy'); 
                                    Element.hide();
                                    Element.closest('tr').find('.add-row').show();
                                    Element.closest('tr').find('.cancel-invite').show();
                                    Element.closest('tr').find('.staff-delete').val(staffId);
                                } else {
                                    swal("Error", "Could not send the invitation. Please try again.", "error");
                                }
                            },
                            error: function() {
                                swal("Error", "Something went wrong. Please try again.", "error");
                            }
                        });
                    }
                });
            }
        });

        // Handle click event for add-row button
        // Handle click event for add-row button
        $(document).on('click', '.add-row', function() {
            const postTbody = $(this).closest('tbody');
            const maxStaff = postTbody.data('maxstaff');
            const currentStaffCount = postTbody.find('.attending_staff').length; 
            console.log(maxStaff, currentStaffCount);

            // Check if the maximum number of staff has been reached
            if (currentStaffCount >= maxStaff) {
                swal("Limit Reached", "You can only add up to " + maxStaff + " attending staff for this event.", "warning");
                return;
            }

            // Generate the dropdown options, excluding already selected staff
            let options = `<?php
                $attendingStaffQuery = mysql_query("SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS `name`, u.email FROM users_data u WHERE FIND_IN_SET(u.user_id, (SELECT staff_ids FROM supplier_attendingstaffs));");
                while ($attendingStaff = mysql_fetch_assoc($attendingStaffQuery)) {
                    echo '<option value="' . $attendingStaff['user_id'] . '" data-email="' . $attendingStaff['email'] . '">';
                    echo $attendingStaff['name'];
                    echo '</option>';
                }
            ?>`;

           
            var postid = $(this).closest('tbody').data('post-id');
            var supplierid = "<?= $_COOKIE['userid'] ?>";
            // Remove already selected staff for the current post
            if (selectedStaffIds[postid]) {
                selectedStaffIds[postid].forEach(staffId => {
                    options = options.replace(new RegExp(`<option value="${staffId}".*?</option>`, 'g'), '');
                });
            }

            // Create the new row (Fixed HTML structure)
            var newRow = $(`
                <tr>
                    <td>
                        <select class="form-control attending_staff" name="attending_staff[]">
                            <option value="">Select Attending Staff</option>
                            ${options}
                        </select>
                        <span class="selected_staff" style="display: none; padding: 5px; margin-left: 10px;"></span>
                    </td>
                    <td class="status"></td>
                    <td class="register"></td>
                    <td class="action">
                        <button type="button" class="btn btn-sm btn-success add-row">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                        </button>
                        <form action="" method="post">
                            <input type="hidden" name="post_id" value="${postid}">
                            <input type="hidden" name="supplier_id" value="${supplierid}">
                            <input type="hidden" name="staff_id" class="staff-delete" value="">
                            <input type="hidden" name="delete_attended" value="delete">
                            <button type="submit" class="btn btn-sm btn-danger cancel-invite" style="display:none">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            `);

            // Append the new row to the table
            $(this).closest('tbody').append(newRow);

            // Initialize Select2 on the new row's select dropdown
            newRow.find('.attending_staff').select2({
                tags: true,
                placeholder: "Select or add staff email address",
                createTag: function(params) {
                    let email = params.term;
                    if (!isValidEmail(email)) {
                        return {
                            id: email,
                            text: "❌ Invalid Email",
                            newTag: true,
                            invalid: true
                        };
                    }

                    let name = email.split('@')[0]
                        .replace(/[._]/g, ' ')
                        .replace(/\b\w/g, c => c.toUpperCase());

                    return {
                        id: email,
                        text: name,
                        newTag: true
                    };
                }
            });
            // Prevent selection of invalid emails
            newRow.find('.attending_staff').on("select2:select", function(e) {
                let selectedData = e.params.data;
                if (selectedData.invalid) {
                    swal("Invalid Email", "Please enter a valid email address.", "error");
                    $(this).val(null).trigger("change");
                }
            });

            // Remove the add-row button from the previous row
            $(this).remove();
        });

        // Cancel invitation handler
        $(document).on('click', '.cancel-invite', function(e) {
            e.preventDefault();
            const button = $(this);
            swal({
                title: "Confirm Cancellation",
                text: "Are you sure you want to cancel this invitation?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm) => {
                if (confirm) {
                    button.closest('form').submit();
                }
            });
        });
    });
</script>
*/
?>
<script>
    $(document).ready(function() {
        //$('.publish-post-button').remove();
        function isValidEmail(email) {
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
        }
        let selectedStaffIds = {};
        function initializeSelect2(element) {
            element.select2({
                tags: true,
                placeholder: "Select or add staff email address",
                createTag: function(params) {
                    let email = params.term;
                    if (!isValidEmail(email)) {
                        return { id: email, text: "❌ Invalid Email", newTag: true, invalid: true };
                    }
                    let name = email.split('@')[0]
                        .replace(/[._]/g, ' ')
                        .replace(/\b\w/g, c => c.toUpperCase());
                    return { id: email, text: "Invite: " + email, newTag: true };
                }
            }).on("select2:select", function(e) {
                if (e.params.data.invalid) {
                    swal("Invalid Email", "Please enter a valid email address.", "error");
                    $(this).val(null).trigger("change");
                }
            });
        }
        $('.attending_staff').each(function() {
            initializeSelect2($(this));
        });
        $(document).on('change', '.attending_staff', function() {
            const Element = $(this);
            const parentTbody = Element.closest('tbody');
            const postTitle = parentTbody.data('post-title');
            const postId = parentTbody.data('post-id');
            const lepId = parentTbody.data('lep-id');
            const postDate = parentTbody.data('post-date');
            
            var selectedOption = Element.find(":selected");
            const staffName = selectedOption.text();
            var staffId = selectedOption.val();
            var staffEmail = selectedOption.data("email");
            var supplierName = "<?= $user['company'] ?>";
            var supplierId = "<?= $user['user_id'] ?>";

            if (!selectedStaffIds[postId]) selectedStaffIds[postId] = [];
            
            if (!isValidEmail(staffId) && selectedOption.data('newTag')) {
                swal("Invalid Email", "Please enter a valid email address.", "error");
                Element.next('.select2-container').find('.select2-selection__rendered').css('color', 'red');
                return false;
            } else {
                Element.next('.select2-container').find('.select2-selection__rendered').css('color', 'black');
            }

            if (staffId && !selectedStaffIds[postId].includes(staffId)) {
                swal({
                    title: "Confirm Invitation!",
                    text: "Are you sure you want to invite " + staffName + "?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willInvite) => {
                    if (willInvite) {
                        $.ajax({
                            url: "https://www.motiv8search.com/api/widget/json/get/invite_attending_staff",
                            type: "POST",
                            data: {
                                supplier_name: supplierName,
                                supplier_id: supplierId,
                                staff_id: staffId,
                                staff_name: staffName,
                                staff_email: staffEmail,
                                post_title: postTitle,
                                post_id: postId,
                                lep_id: lepId,
                                post_date: postDate
                            },
                            success: function(response) {
                                if (response.success) {
                                    selectedStaffIds[postId].push(staffId);
                                    Element.closest('tr').find('.selected_staff').text(staffName).show();
                                    Element.closest('tr').find('.status').text('Invited');
                                    Element.closest('tr').find('.register').text('Awaiting Registration');
                                    Element.closest('tr').addClass('danger');
                                    Element.select2('destroy').hide();
                                    Element.closest('tr').find('.add-row, .cancel-invite').show();
                                    Element.closest('tr').find('.staff-delete').val(staffId);
                                } else {
                                    swal("Error", "Could not send the invitation. Please try again.", "error");
                                }
                            },
                            error: function() {
                                swal("Error", "Something went wrong. Please try again.", "error");
                            }
                        });
                    }
                });
            }
        });
        $(document).on('click', '.add-row', function() {
            const postTbody = $(this).closest('tbody');
            const maxStaff = postTbody.data('maxstaff');
            const currentStaffCount = postTbody.find('.selected_staff:visible').length;
            
            if (currentStaffCount >= maxStaff) {
                swal("Limit Reached", "You can only add up to " + maxStaff + " attending staff.", "warning");
                return;
            }

            let options = `<?php
                $query = mysql_query("SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, email FROM users_data WHERE FIND_IN_SET(user_id, (SELECT GROUP_CONCAT(staff_ids) FROM supplier_attendingstaffs WHERE supplier_id = ".intval($login_uid)."))");
                while ($row = mysql_fetch_assoc($query)) {
                    echo '<option value="' . $row['user_id'] . '" data-email="' . $row['email'] . '">' . $row['name'] . '</option>';
                }
            ?>`;

            let postId = postTbody.data('post-id');
            if (selectedStaffIds[postId]) {
                selectedStaffIds[postId].forEach(staffId => {
                    options = options.replace(new RegExp(`<option value="${staffId}".*?</option>`, 'g'), '');
                });
            }

            let newRow = $(`
                <tr>
                    <td>
                        <select class="form-control attending_staff" name="attending_staff[]">
                            <option value="">Select Attending Staff</option>
                            ${options}
                        </select>
                        <span class="selected_staff" style="display: none; padding: 5px;"></span>
                    </td>
                    <td class="status"></td>
                    <td class="register"></td>
                    <td class="action">
                        <button type="button" class="btn btn-sm btn-success add-row"><i class="fa fa-plus-square"></i></button>
                    </td>
                </tr>
            `);

            postTbody.append(newRow);
            initializeSelect2(newRow.find('.attending_staff'));
            $(this).remove();
        });
        $(document).on('click', '.cancel-invite', function(e) {
            e.preventDefault();
            const button = $(this);
            swal({
                title: "Confirm Cancellation",
                text: "Are you sure you want to cancel this invitation?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirm) => {
                if (confirm) {
                    button.closest('form').submit();
                }
            });
        });
    });
</script>

<style>
    .label-success { background-color: #5cb85c; }
    .label-danger { background-color: #d9534f; }
    .label-warning { background-color: #f0ad4e; }
</style>

