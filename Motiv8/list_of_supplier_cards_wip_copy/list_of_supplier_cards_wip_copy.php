<?php
// Login user details
$login_uid   = $user['user_id'];
$login_email = $user['email'];

// Supplier ID from cookie
$supplier_id = $_COOKIE['userid'];

// === Handle Attendance Confirmation via Token ===
if (isset($_GET['confirm-attendance'], $_GET['token']) && $_GET['confirm-attendance'] == 1) {
    $token = mysql_real_escape_string($_GET['token']);

    $checkTokenSql = "SELECT * FROM attending_staff_attendance WHERE token = '$token' LIMIT 1";
    $result        = mysql_query($checkTokenSql);

    if ($row = mysql_fetch_assoc($result)) {
        if (
            (empty($user['first_name']) && empty($user['last_name']) && empty($user['phone_number'])) ||
            empty($user['company'])
        ) {
            echo "<script>
                setTimeout(() => {
                    swal({
                        title: 'Update Required!',
                        text: 'Please update your basic details to continue.',
                        icon: 'warning',
                        buttons: false,
                        closeOnClickOutside: false
                    }).then(() => {
                        window.location.href = 'https://www.motiv8search.com/account/contact?redirect=/account/add-supplier-card/view';
                    });
                }, 100);
            </script>";
        }
    } else {
        echo "<script>
            setTimeout(() => {
                swal({
                    title: 'Error!',
                    text: 'Invalid token. Please check the link or contact support.',
                    icon: 'error'
                });
            }, 100);
        </script>";
    }
}

// === Handle Cancellation (POST Request) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_attended'])) {
    $post_id     = intval($_POST['post_id']);
    $supplier_id = intval($_POST['supplier_id']);
    $staff_id    = $_POST['staff_id']; // Can be email or staff_id

    // Determine delete method: by email or ID
    if (filter_var($staff_id, FILTER_VALIDATE_EMAIL)) {
        $staff_id = mysql_real_escape_string($staff_id);
        $delete_sql = "DELETE FROM attending_staff_attendance 
                       WHERE post_id = $post_id 
                       AND supplier_id = $supplier_id 
                       AND staff_email = '$staff_id'";
    } else {
        $delete_sql = "DELETE FROM attending_staff_attendance 
                       WHERE post_id = $post_id 
                       AND supplier_id = $supplier_id 
                       AND staff_id = " . intval($staff_id);
    }

    // Execute delete query
    if (mysql_query($delete_sql)) {
        $updateSql = "UPDATE supplier_registration_form 
                      SET limit_reached = 0 
                      WHERE user_id = $supplier_id AND event_id = $post_id";

        mysql_query($updateSql);

        // Redirect after success
        header("Location: https://www.motiv8search.com/account/add-supplier-card/view");
        exit();
    } else {
        echo "<script>alert('Error cancelling invitation');</script>";
    }
}

// === Fetch Upcoming Events ===
$sql_upcoming = "SELECT
                    lep.*,
                    dp.post_title,
                    dp.post_id,
                    dp.post_start_date,
                    dp.post_live_date,
                    dp.invitation_deadline,
                    dp.post_expire_date,
                    dp.post_token,
                    asa.supplier_id AS sender_id
                FROM live_events_posts AS lep
                JOIN data_posts AS dp ON lep.post_id = dp.post_id
                LEFT JOIN attending_staff_attendance asa ON lep.id = asa.lep_id
                WHERE (lep.user_id = '$supplier_id' 
                       OR asa.supplier_id = '$supplier_id' 
                       OR asa.staff_id = '$supplier_id')
                      AND dp.post_start_date >= DATE_FORMAT(NOW(), '%Y%m%d')
                GROUP BY lep.id
                ORDER BY dp.post_start_date ASC";

$result_upcoming = mysql_query($sql_upcoming);

// === Fetch Past Events ===
$sql_past = "SELECT
                lep.*,
                dp.post_title,
                dp.post_id,
                dp.post_start_date,
                dp.post_live_date,
                dp.invitation_deadline,
                dp.post_expire_date,
                dp.post_token,
                asa.supplier_id AS sender_id
            FROM live_events_posts AS lep
            JOIN data_posts AS dp ON lep.post_id = dp.post_id
            LEFT JOIN attending_staff_attendance asa ON lep.id = asa.lep_id
            WHERE (lep.user_id = '$supplier_id' 
                   OR asa.supplier_id = '$supplier_id' 
                   OR asa.staff_id = '$supplier_id')
                  AND STR_TO_DATE(dp.post_expire_date, '%Y%m%d') < CURDATE()
            GROUP BY lep.id
            ORDER BY dp.post_start_date DESC";

$result_past = mysql_query($sql_past);


// === Deadline Counter Formatter ===
function formatDeadlineCounter($post_live_date, $fallback_date = null)
{
    if (empty($post_live_date)) {
        $post_live_date = $fallback_date;
    }

    if (empty($post_live_date)) {
        return "8 Days Left";
    }

    $date = DateTime::createFromFormat('d/m/Y', $post_live_date);
    if (!$date) {
        $date = DateTime::createFromFormat('Y-m-d', $post_live_date);
    }

    if (!$date) {
        return "8 Days Left";
    }

    $date->setTime(0, 0, 0);
    $now = new DateTime();
    $now->setTime(0, 0, 0);

    if ($date < $now) {
        return "Deadline has passed";
    }

    $diff   = $date->diff($now);
    $months = $diff->m;
    $days   = $diff->d;

    return ($months > 0)
        ? sprintf("%d %s %d Days Left", $months, ($months == 1 ? "Month" : "Months"), $days)
        : sprintf("%d %s Left", $days, ($days == 1 ? "Day" : "Days"));
}
// === variables for UI ===
$complete = 'Published';
$incomplete = 'Incomplete';
$delete_label = 'Delete Supplier Card?';
$firstAccordion = true;
?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Loader code start -->
<style>
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loader-spinner .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    h3.section-header {
        border-bottom: 1px solid rgb(48, 111, 195);
        width: 25%;
        box-shadow: 0 1px 0 0 rgb(48, 111, 195);
    }
</style>
<div id="loader" class="loader-overlay">
    <div class="loader-spinner">
        <div class="spinner"></div>
    </div>
</div>
<script>
    window.addEventListener('load', function() {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('content').style.display = 'block';
    });
</script>
<!-- Loader code end -->
<div class="account-form-box">
    <div class="new-element">
        <div class="row">
            <div class="col-md-12 bmargin">
                <h3 class="section-header">Upcoming Events</h3>
                <?php if (mysql_num_rows($result_upcoming) > 0): ?>
                    <?php while ($row = mysql_fetch_assoc($result_upcoming)):
                        // Fetch additional info link for supplier
                        $post_id = (int) $row['post_id'];
                        $user_id = (int) $row['user_id'];
                        $add_info_link_sql = "SELECT `value` FROM `users_meta` WHERE `key` = 'additional_info_link' AND `database_id` = '$post_id' AND `database` = 'data_posts'";
                        $add_info_link_results = mysql_query($add_info_link_sql);
                        $add_info_link = mysql_fetch_assoc($add_info_link_results);

                        // Adjust event date and calculate event datetime
                        $deadline = $row['invitation_deadline'];
                        $start_date = date('Y-m-d', strtotime($row['start_date'] . ' -7 days'));
                        $start_time = $row['start_time'];
                        $eventdatetime = date("YmdHis", strtotime("$start_date $start_time"));

                        // Get supplier registration package details
                        $package_sql = "SELECT * FROM `supplier_registration_form`  WHERE user_id = $user_id  AND event_id = $post_id";
                        $packages = mysql_fetch_array(mysql_query($package_sql));

                        $packages_section = $packages['packages_section'];
                        $maxStaff = $packages['package_limit'];
                        $srf_user = $packages['user_id']; // supplier_id
                        $srf_event = $packages['event_id']; // post_id
                        $sender_id = (int) $row['sender_id'];

                        // Determine supplier ID for filtering
                        $Selectwhere = "WHERE supplier_id = " . intval($login_uid);
                        if (!empty($sender_id)) {
                            $Selectwhere .= " OR supplier_id = $sender_id";
                        }
                    ?>
                        <div class="module">
                            <div class="row">
                                <!-- Supplier Picture and Status -->
                                <div class="col-md-3">
                                    <div style="display:none"><?= (int)$row['id'] ?></div>
                                    <div class="clearfix"></div>

                                    <?php if ($row['staus'] == 1 || $row['staus'] == 0): ?>
                                        <div class="btn-xs bold line-height-xl center-block no-radius-bottom label-danger">
                                            <?= $incomplete ?>
                                        </div>
                                    <?php elseif ($row['staus'] == 2): ?>
                                        <div class="btn-xs bold line-height-xl center-block no-radius-bottom label-success">
                                            <?= $complete ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="alert-default btn-block text-center the-post-image">
                                        <img src="<?= !empty($row['thumb_booth'])
                                                        ? "https://www.motiv8search.com/images/events/" . $row['thumb_booth']
                                                        : "https://www.motiv8search.com/images/IMG-20230916-WA0016.jpg"; ?>"
                                            alt="Event Image" width="100%">
                                    </div>
                                </div>
                                 <!-- Supplier Description -->
                                <div class="col-md-6">
                                    <h4 class="line-height-lg bold xs-nomargin post-title">
                                        <a href="/account/edit-supplier-card/edit?id=<?= (int)$row['id'] ?>">
                                            <?= htmlspecialchars($row['post_title']) ?>
                                        </a>
                                    </h4>

                                    <div class="small bmargin hidden-xs">
                                        <p class="the-post-description">
                                            <?= substr(strip_tags($row['event_description']), 0, 200) ?>
                                        </p>
                                    </div>

                                    <?php if ($packages_section !== "Desktop Package" && $packages_section !== "SuperBooth Package"): ?>
                                        <span class="the-post-location small">
                                            <b>Presentation Slot:</b> <?= $start_time . ' - ' . $row['end_time'] ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <!-- Action buttons -->
                                <div class="col-md-3">
                                    <div class="dropdown center-block">
                                        <a class="btn btn-primary bmargin btn-sm bold btn-block edit-supplier-card"
                                        href="/account/edit-supplier-card/edit?id=<?= (int)$row['id'] ?>">
                                            Edit Supplier Card
                                        </a>

                                        <a class="btn btn-sm bold btn-block <?= (!empty($add_info_link['value']) ? 'btn-primary' : 'btn-secondary disabled') ?>"
                                        href="<?= !empty($add_info_link['value']) ? $add_info_link['value'] : '#' ?>"
                                        target="_blank">
                                            Event Info
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="bmargin"></div>
                            <!-- Accordion Row for Attending Staff -->
                            <div class="row">
                                <div class="panel-group" id="staff_accordion_<?= $row['id'] ?>">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"
                                                style="display: flex; align-items: center; justify-content: space-between;">
                                                <span><b>Attending Staff</b></span>
                                                <span class="text-danger lpad" style="flex-grow: 1; text-align: right;">
                                                    Deadline: <?php echo formatDeadlineCounter($deadline, $start_date) ?>
                                                </span>
                                                <a data-toggle="collapse" data-parent="#staff_accordion_<?= $row['id'] ?>" href="#staff_collapse_<?= $row['id'] ?>"  <?= $firstAccordion ? 'aria-expanded="true"' : '' ?>></a>
                                            </h4>
                                            <p class="textone bold text-muted small">
                                                Register yourself and/or invite your colleagues to register to attend.
                                            </p>
                                        </div>
                                        <div id="staff_collapse_<?= $row['id'] ?>" class="panel-collapse collapse <?= $firstAccordion ? 'in' : '' ?>">
                                            <div class="panel-body" style="padding: 0;">
                                                <table class="table table-bordered" style="background-color: transparent">
                                                    <thead>
                                                        <tr>
                                                            <th class="staff">Staff Name</th>
                                                            <th class="status">Status</th>
                                                            <th class="registration">Registration</th>
                                                            <th class="text-center cancel">
                                                                <?php
                                                                if ($packages_section == 'Desktop Package') {
                                                                    echo 'Desktop';
                                                                } elseif ($packages_section == 'SuperBooth Package') {
                                                                    echo 'SuperBooth';
                                                                } else {
                                                                    echo $packages_section; 
                                                                }
                                                                ?>
                                                                <span>(<?php
                                                                $countquery = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS total FROM attending_staff_attendance WHERE supplier_id = $srf_user AND post_id = $srf_event"));
                                                                $total_attending = $countquery['total'];
                                                                echo $total_attending;
                                                                ?>
                                                                    /<?php echo $maxStaff; ?>)</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                        <?php
                                                            $emailstart_date = date("d-m-Y", strtotime($start_date));
                                                            $emaildeadline = '';
                                                            if (!empty($deadline)) {
                                                                $emaildeadline = date("d-m-Y", strtotime(str_replace('/', '-', $deadline)));
                                                            }

                                                            $finalDate = !empty($emaildeadline) ? $emaildeadline : $emailstart_date;

                                                            // echo "start: " . $emailstart_date . " deadline: " . $emaildeadline . "<br>";
                                                            // echo $finalDate;

                                                        ?>
                                                        <tbody data-lep-id="<?= $row['id'] ?>"
                                                            data-maxstaff="<?= $maxStaff ?>"
                                                            data-post-title="<?= $row['post_title'] ?>"
                                                            data-post-id="<?= $row['post_id'] ?>"
                                                            data-post-date="<?= !empty($row['invitation_deadline']) ? $row['invitation_deadline'] : $row['start_date'] ?>"
                                                            data-mail-date="<?= $finalDate ?>">
                                                            <?php

                                                            $isCoordinatorExists = false;
                                                            $attendingStaff = mysql_query("SELECT a.id, a.supplier_id, a.staff_id, a.status, a.staff_email, a.token,  CONCAT(u.first_name, ' ', u.last_name) AS name, u.user_id FROM attending_staff_attendance a LEFT JOIN users_data u ON a.staff_id = u.user_id WHERE a.post_id = " . intval($row['post_id']) . " AND a.supplier_id = " . intval($row['user_id']) . " ORDER BY a.id ASC");
                                                                    echo '<div class="hidden">'. "SELECT a.id, a.supplier_id, a.staff_id, a.status, a.staff_email, a.token, CONCAT(IF(TRIM(CONCAT(u.first_name, u.last_name)) != '', CONCAT(u.first_name, ' ', u.last_name), u.company)) AS name FROM attending_staff_attendance a LEFT JOIN users_data u ON a.staff_id = u.user_id WHERE a.post_id = " . intval($row['post_id']) . "  AND a.supplier_id = " . intval($row['user_id']) . " ORDER BY a.id ASC".'</div>';
                                                            $staffCount = 0;
                                                            while ($staff = mysql_fetch_assoc($attendingStaff)) {
                                                                if ($staff['status'] != 'canceled') {
                                                                    $staffCount++;
                                                                }

                                                                $checkCoordinatorQuery = "SELECT * FROM attending_supplier_staff_registration WHERE user_id = " . $staff['staff_id'] . " AND supplier_id = " . $staff['supplier_id'] . " AND post_id = " . $row['post_id'] . " AND is_event_coordinator = '1' LIMIT 1";

                                                                $result = mysql_query($checkCoordinatorQuery);
                                                                $isCoordinatorExists = ($result && mysql_num_rows($result) > 0) ? true : false;

                                                                // Check if staff is "Not Required"
                                                                $notRequired = ($staff['name'] == "Not Required - We do not need this membe" ||
                                                                    $staff['staff_email'] == "Not Required - We do not need this member");

                                                                // Determine row color
                                                                if ($notRequired) {
                                                                    $trColor = 'danger';
                                                                } else {
                                                                    $trColor = ($staff['status'] == 'Completed') ? 'success' : 'warning';
                                                                }
                                                                    
                                                                $tokensql = mysql_fetch_assoc(mysql_query("SELECT * FROM users_data WHERE user_id =".$staff['staff_id']));
                                                                $CoordinatorLabel = '';
                                                                $logback_admin = mysql_fetch_assoc(mysql_query("SELECT token FROM users_data WHERE user_id =".$staff['supplier_id']));
                                                                session_start();
                                                                $_SESSION['val'] = $logback_admin['token'];
                                                                if ($user_data['user_id'] == $staff['supplier_id']) {
                                                                    if ($user_data['user_id'] != $staff['user_id']) {
                                                                    $CoordinatorLabel = ($isCoordinatorExists)
                                                                        ? '<div style="text-align: center;"><strong>Event Coordinator</strong><a href="/login/token/' . $tokensql['token'] . '/add-supplier-card/wip/'.$logback_admin['token'].'" class="btn btn-primary" data-user="'.$user_data['user_id'].' '.$staff['user_id'].'">Login as Member</a></div>'
                                                                        : '<a href="/login/token/' . $tokensql['token'] . '/add-supplier-card/wip/'.$logback_admin['token'].'" class="btn btn-primary">Login as Member</a>';
                                                                    }
                                                                } else {
                                                                    // if ($pars['3'] == $logback_admin['token']) {
                                                                        $CoordinatorLabel = ($isCoordinatorExists) ? '<center><b>Event Coordinator</b><a href="/login/token/' . $logback_admin['token'] . '/add-supplier-card/wip" class="btn btn-primary back-to-company-from">Back to company</a></center>' : '<center><a href="/login/token/' . $logback_admin['token'] . '/add-supplier-card/wip" class="btn btn-primary back-to-company-from">Back thhkhho company</a></center>';                                                                                  
                                                                    // } else {
                                                                        // $CoordinatorLabel = ($isCoordinatorExists) ? '<b><center>Event Coordinator</center></b>' : '';
                                                                    // }
                                                                }

                                                                $applicationTokenQuery = mysql_query("SELECT token FROM create_application_pages WHERE event_id = '".$row['post_id']."' AND `type` = 'registration form'");
                                                                $applicationTokenResult = mysql_fetch_assoc($applicationTokenQuery);
                                                                $applicationtoken = $applicationTokenResult['token'];
                                                                $attendingUrl = "https://www.motiv8search.com/attending-supplier-staff-registration?ref=".$applicationtoken."&token=".$staff['token'];
                                                                
                                                                // Output row with appropriate class
                                                                echo '<tr class="' . $trColor . '">';
                                                                echo '<td class="table_selected_staff">';
                                                                
                                                                $disabled = (date('Y-m-d') > $start_date) ? 'disabled' : '';
                                                                $style = (date('Y-m-d') > $start_date) ? 'disabled-select' : '';
                                                                    
                                                                echo '<select class="form-control attending_staff' . $style . '" name="attending_staff[]" data-id="' . htmlspecialchars($staff['id']) . '" '.$disabled.'><option value="">Select Attending Staff</option>';

                                                                    // Check if the staff name or email should be pre-selected
                                                                    
                                                                $formname_query = mysql_query("SELECT full_name FROM `attending_supplier_staff_registration` WHERE email = '".mysql_real_escape_string($staff['staff_email'])."'");
                                                                $formname = mysql_fetch_assoc($formname_query);
                                                                $formnamevalue = $formname['full_name'];
                                                                $name = trim($staff['name']);

                                                                if (!empty($name)) {
                                                                    $selectedValue = htmlspecialchars($name);
                                                                } elseif (!empty($formnamevalue)) {
                                                                    $selectedValue = htmlspecialchars($formnamevalue);
                                                                } else {
                                                                    $selectedValue = htmlspecialchars($staff['staff_email']);
                                                                    $isEmailUsed = true;
                                                                }

                                                                // Create the first option with pre-selection
                                                                echo '<option value="' . $selectedValue . '" selected>' . $selectedValue . '</option>';

                                                                $attendingStaff_query = array();

                                                                // Fetch the attending staff IDs from the database
                                                                $q = mysql_query("SELECT staff_id FROM attending_staff_attendance WHERE post_id=" . intval($row['post_id']) . " AND `status` != 'Cancelled'");

                                                                while ($s = mysql_fetch_assoc($q)) {
                                                                    $attendingStaff_query[] = $s['staff_id'];
                                                                }

                                                                // Query to get the list of staff from users_data
                                                                $stuffq = mysql_query("SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, email FROM users_data WHERE FIND_IN_SET(user_id, (SELECT GROUP_CONCAT(staff_ids) FROM supplier_attendingstaffs $Selectwhere ))");

                                                                while ($stuff = mysql_fetch_assoc($stuffq)) {
                                                                    // Check if the user_id is not already in the attending staff list
                                                                    if (!in_array($stuff['user_id'], $attendingStaff_query)) {
                                                                        $formnames_query = mysql_query("SELECT full_name FROM `attending_supplier_staff_registration` WHERE email = '".mysql_real_escape_string($stuff['email'])."'");
                                                                        $formnames = mysql_fetch_assoc($formnames_query);
                                                                        $formnamesvalue = $formnames['full_name'];
                                                                        // Check if this user should be pre-selected (match the value with selected staff)
                                                                        $selected = ($stuff['name'] == $staff['name'] || $stuff['email'] == $staff['staff_email']) ? 'selected="selected"' : '';
                                                                        echo '<option value="' . $stuff["user_id"] . '" data-email="' . $stuff["email"] . '" ' . $selected . '>' . (empty(trim($stuff["name"])) ? $formnamesvalue : $stuff['name']) . '</option>';
                                                                    }
                                                                }

                                                                echo '<option style="font-size: 10px !important;">Not Required - We do not need this member</option>
                                                                </select>';

                                                                // }

                                                                echo '</td>';
                                                                //  echo "SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, email FROM users_data WHERE FIND_IN_SET(user_id, (SELECT GROUP_CONCAT(staff_ids) FROM supplier_attendingstaffs WHERE supplier_id = " . intval($login_uid) . "))";
                                                                // Status column - apply strikethrough only if "Not Required"
                                                                if ($notRequired && $staff['status'] == 'TBC') {
                                                                    echo '<td class="status"><s>TBC</s></td>';
                                                                } else {
                                                                    echo '<td class="status">' . htmlspecialchars($staff['status']);
                                                                if ($staff['supplier_id'] == $user_data['user_id'] && $staff['status'] == 'TBC') {
                                                                    echo '<br>
                                                                        <button type="button" class="copy_btn btn btn-default btn-sm" data-copylink="' . htmlspecialchars($attendingUrl) . '">
                                                                            <i class="fa fa-files-o" aria-hidden="true"></i> Copy Link
                                                                        </button>';
                                                                }
                                                                    echo '</td>';
                                                                }

                                                                // Staff ID (For cancel invitation action)
                                                                $staff_id = ($staff['staff_id'] > 0) ? $staff['staff_id'] : $staff['staff_email'];

                                                                if ($staff['status'] == 'TBC') {
                                                                    // Apply strikethrough to "Awaiting Registration" only if "Not Required"
                                                                    $registerText = $notRequired ? '<s>Awaiting Registration</s>' : 'Awaiting Registration';
                                                                    echo '<td class="register">' . $registerText . ' </td>';

                                                                    // Hide cancel button only if "Not Required"
                                                                    if (!$notRequired) {
                                                                        if(date('Y-m-d') > $start_date && $event_supplierid != $supplier_id){
                                                                            echo "
                                                                            <style> 
                                                                                .cancel-invite{
                                                                                    display: none !important;
                                                                                }
                                                                            </style>
                                                                            ";
                                                                        }
                                                                        echo '<td class="action text-center">
                                                                        <form action="" method="post">
                                                                            <input type="hidden" name="post_id" value="' . htmlspecialchars($row['post_id']) . '">
                                                                            <input type="hidden" name="supplier_id" value="' . htmlspecialchars($row['sender_id']) . '">
                                                                            <input type="hidden" name="staff_id" value="' . htmlspecialchars($staff_id) . '">
                                                                            <input type="hidden" name="delete_attended" value="delete">
                                                                            <button type="submit" class="btn btn-danger cancel-invite">Cancel Invitation</button>
                                                                        </form>';

                                                                        if ($user_data['user_id'] == $staff['supplier_id']) {
                                                                            if ($user_data['user_id'] != $staff['user_id']) {
                                                                            if(!$isEmailUsed) {
                                                                            echo '<div style="padding-top:10px;">
                                                                                    <a href="/login/token/' . urlencode($tokensql['token']) . '/add-supplier-card/wip/' . urlencode($logback_admin['token']) . '" class="btn btn-primary">Login as Member</a>
                                                                                </div>';
                                                                            }
                                                                        }
                                                                        }
                                                                        

                                                                        echo '</td>';

                                                                    } else {
                                                                        echo '<td></td>'; // Empty column to maintain table structure
                                                                    }

                                                                } elseif ($staff['status'] == 'Completed') {
                                                                    echo '<td class="register">Registered & Going</td><td class="text-center">' . $CoordinatorLabel . '</td>';
                                                                } elseif ($staff['status'] == 'canceled') {
                                                                    echo '<td class="register">Expired</td><td class="text-center">' . $CoordinatorLabel . '</td>';
                                                                }

                                                                echo '</tr>';
                                                            }
                                                            if ($staffCount >= $maxStaff) {
                                                                echo '<tr><td colspan="4" style="text-align: center; color: red;">Limit Reached: You can only add up to ' . $maxStaff . ' attending staff for this event.</td></tr>';
                                                            }
                                                            // Check if the limit is already reached in supplier_registration_form
                                                            // $limitCheckSql = "SELECT limit_reached FROM supplier_registration_form WHERE user_id = $supplier_id AND event_id = " . $row['post_id'];
                                                            // $macro = $row['user_id'];
                                                            // $limitCheckSql = "SELECT limit_reached FROM supplier_registration_form WHERE user_id = $macro AND event_id = " . $row['post_id'];
                                                            // // $limitCheckSql = "SELECT limit_reached FROM supplier_registration_form WHERE event_id = " . $row['post_id'];
                                                            // $limitCheckResult = mysql_query($limitCheckSql);
                                                            // $limitReached = false;
                                                            // if ($limitCheckRow = mysql_fetch_assoc($limitCheckResult)) {
                                                            //     $limitReached = $limitCheckRow['limit_reached'] == 1;
                                                            // }
                                                            // if (($staffCount < $maxStaff) && $login_uid == $row['user_id']): 
                                                            if (($staffCount < $maxStaff)):
                                                                
                                                                // Additional check if the limit is reached
                                                                $checkLimitSql = "SELECT COUNT(*) as count FROM attending_staff_attendance 
                                                                WHERE supplier_id = $supplier_id 
                                                                AND post_id = " . $row['post_id'] . " 
                                                                AND lep_id = " . $row['id'];
                                                                $limitResult = mysql_query($checkLimitSql);
                                                                $limitReachedAttendance = false;

                                                                if ($limitRow = mysql_fetch_assoc($limitResult)) {
                                                                    if ($limitRow['count'] > 0) {
                                                                        $limitReachedAttendance = true;
                                                                    }
                                                                }
                                                                ?>


                                                                <?php
                                                                $limit_members = $maxStaff - $total_attending;
                                                                for ($i = 0; $i < $limit_members; $i++): ?>
                                                                    <tr class="search-member">
                                                                        <td style="vertical-align: middle;">
                                                                            <div class="row">
                                                                                    <div class="col-md-6 col-sm-6 col-xs-6 this-one">
                                                                                        <select class="form-control attending_staff"
                                                                                            name="attending_staff[]">
                                                                                            <option value="">Select Attending Staff
                                                                                            </option>
                                                                                            <?php
                                                                                            $attendingStaff = array();
                                                                                             $q = mysql_query("SELECT staff_id FROM attending_staff_attendance WHERE post_id=" . intval($row['post_id']) . " AND supplier_id=" . intval($login_uid) . " AND `status` != 'Cancelled'");
                                                                                            while ($s = mysql_fetch_assoc($q)) {
                                                                                                $attendingStaff[] = $s['staff_id'];
                                                                                            }
                                                                                            $q = mysql_query("SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, email FROM users_data WHERE FIND_IN_SET(user_id, (SELECT GROUP_CONCAT(staff_ids) FROM supplier_attendingstaffs  $Selectwhere ))");
                                                                                            
                                                                                            while ($s = mysql_fetch_assoc($q)) {
                                                                                                if (!in_array($s['user_id'], $attendingStaff)) { 
                                                                                                    
                                                                                                    
                                                                                            $formname_query = mysql_query("SELECT full_name FROM attending_supplier_staff_registration WHERE user_id = ".$s['user_id']);
                                                                                            $formname = mysql_fetch_assoc($formname_query);
                                                                                                
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                            ?>
                                                                                            <option value="<?= $s['user_id'] ?>" data-email="<?= $s['email'] ?>">
                                                                                                <?php echo !empty(trim($s['name'])) ? $s['name'] : (!empty(trim($formname['full_name'])) ? trim($formname['full_name']) : $s['email']); ?>
                                                                                            </option>
                                                                                                <?php }
                                                                                            } ?>
                                                                                            <option style="font-size: 1px !important;">
                                                                                                Not Required - We do not need this member</option>
                                                                                        </select>
                                                                                    </div>  
                                                                            </div>
                                                                        </td>
                                                                        <td class="status">TBC</td>
                                                                        <td class="register">Awaiting Registration</td>
                                                                        <td class="action">
                                                                           
                                                                                <form action="" method="post">
                                                                                    <input type="hidden" name="post_id"
                                                                                        value="<?= $row['post_id'] ?>">
                                                                                    <input type="hidden" name="supplier_id"
                                                                                        value="<?= $supplier_id ?>">
                                                                                    <input type="hidden" name="staff_id"
                                                                                        class="staff-delete"
                                                                                        value="<?= $staff['staff_id'] ?>">
                                                                                    <input type="hidden" name="delete_attended"
                                                                                        value="delete">
                                                                                    <button type="submit"
                                                                                        class="btn btn-sm btn-danger cancel-invite"
                                                                                        style="display: none;">
                                                                                        Cancel Invitation
                                                                                    </button>
                                                                                </form>
                                                                            
                                                                        </td>
                                                                    </tr>
                                                                <?php endfor; ?>

                                                            <?php endif; ?>
                                                            <script>
                                                                $(document).ready(function () {
                                                                    $(".limit-reached-btn").click(function () {
                                                                        var supplier_id = <?= $supplier_id ?>;
                                                                        var event_id = $(this).closest("tr").find(".event-id").text().trim(); // Ensure fetching the right event_id

                                                                        $.ajax({
                                                                            url: "https://www.motiv8search.com/api/widget/html/get/update_limit",
                                                                            type: "POST",
                                                                            data: { supplier_id: supplier_id, event_id: event_id },
                                                                            success: function (response) {
                                                                                console.log(response);
                                                                                if (response.trim() === "success") {
                                                                                    location.reload();
                                                                                } else {
                                                                                    location.reload();
                                                                                }
                                                                            },
                                                                            error: function () {
                                                                                alert("Failed to update limit. Please try again.");
                                                                            }
                                                                        });
                                                                    });
                                                                });
                                                            </script>
                                                        </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">No upcoming events found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="account-form-box">
    <div class="new-element">
        <div class="row">
            <div class="col-md-12 bmargin">
                <h3 class="section-header">Past Events</h3>
                <?php if (mysql_num_rows($result_past) > 0): ?>
                    <?php while ($row = mysql_fetch_assoc($result_past)): 
                        // Fetch additional info link for supplier
                        $post_id = (int) $row['post_id'];
                        $user_id = (int) $row['user_id'];
                        $add_info_link_sql = "SELECT `value` FROM `users_meta` WHERE `key` = 'additional_info_link' AND `database_id` = '$post_id' AND `database` = 'data_posts'";
                        $add_info_link_results = mysql_query($add_info_link_sql);
                        $add_info_link = mysql_fetch_assoc($add_info_link_results);

                        // Adjust event date and calculate event datetime
                        $deadline = $row['invitation_deadline'];
                        $start_date = date('Y-m-d', strtotime($row['start_date'] . ' -7 days'));
                        $start_time = $row['start_time'];
                        $eventdatetime = date("YmdHis", strtotime("$start_date $start_time"));

                        // Get supplier registration package details
                        $package_sql = "SELECT * FROM `supplier_registration_form`  WHERE user_id = $user_id  AND event_id = $post_id";
                        $packages = mysql_fetch_array(mysql_query($package_sql));

                        $packages_section = $packages['packages_section'];
                        $maxStaff = $packages['package_limit'];
                        $srf_user = $packages['user_id']; // supplier_id
                        $srf_event = $packages['event_id']; // post_id
                        $sender_id = (int) $row['sender_id'];

                        // Determine supplier ID for filtering
                        $Selectwhere = "WHERE supplier_id = " . intval($login_uid);
                        if (!empty($sender_id)) {
                            $Selectwhere .= " OR supplier_id = $sender_id";
                        }
                        
                        ?>
                        <div class="module">
                            <div class="row">
                                <!-- Supplier Picture and Status -->
                                <div class="col-md-3">
                                    <div style="display:none"><?= (int)$row['id'] ?></div>
                                    <div class="clearfix"></div>

                                    <?php if ($row['staus'] == 1 || $row['staus'] == 0): ?>
                                        <div class="btn-xs bold line-height-xl center-block no-radius-bottom label-danger">
                                            <?= $incomplete ?>
                                        </div>
                                    <?php elseif ($row['staus'] == 2): ?>
                                        <div class="btn-xs bold line-height-xl center-block no-radius-bottom label-success">
                                            <?= $complete ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="alert-default btn-block text-center the-post-image">
                                        <img src="<?= !empty($row['thumb_booth'])
                                                        ? "https://www.motiv8search.com/images/events/" . $row['thumb_booth']
                                                        : "https://www.motiv8search.com/images/IMG-20230916-WA0016.jpg"; ?>"
                                            alt="Event Image" width="100%">
                                    </div>
                                </div>
                                 <!-- Supplier Description -->
                                <div class="col-md-6">
                                    <h4 class="line-height-lg bold xs-nomargin post-title">
                                        <a href="/account/edit-supplier-card/edit?id=<?= (int)$row['id'] ?>">
                                            <?= htmlspecialchars($row['post_title']) ?>
                                        </a>
                                    </h4>

                                    <div class="small bmargin hidden-xs">
                                        <p class="the-post-description">
                                            <?= substr(strip_tags($row['event_description']), 0, 200) ?>
                                        </p>
                                    </div>

                                    <?php if ($packages_section !== "Desktop Package" && $packages_section !== "SuperBooth Package"): ?>
                                        <span class="the-post-location small">
                                            <b>Presentation Slot:</b> <?= $start_time . ' - ' . $row['end_time'] ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <!-- Action buttons -->
                                <div class="col-md-3">
                                    <div class="dropdown center-block">
                                        <a class="btn btn-primary bmargin btn-sm bold btn-block edit-supplier-card"
                                        href="/account/edit-supplier-card/edit?id=<?= (int)$row['id'] ?>">
                                            Edit Supplier Card
                                        </a>

                                        <a class="btn btn-sm bold btn-block <?= (!empty($add_info_link['value']) ? 'btn-primary' : 'btn-secondary disabled') ?>"
                                        href="<?= !empty($add_info_link['value']) ? $add_info_link['value'] : '#' ?>"
                                        target="_blank">
                                            Event Info
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="bmargin"></div>
                            <!-- Accordion Row for Attending Staff -->
                            <div class="row">
                                <div class="panel-group" id="staff_accordion_<?= $row['id'] ?>">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"
                                                style="display: flex; align-items: center; justify-content: space-between;">
                                                <span><b>Attending Staff</b></span>
                                                <span class="text-danger lpad" style="flex-grow: 1; text-align: right;">
                                                    Deadline: <?php echo formatDeadlineCounter($deadline, $start_date) ?>
                                                </span>
                                                <a data-toggle="collapse" data-parent="#staff_accordion_<?= $row['id'] ?>" href="#staff_collapse_<?= $row['id'] ?>"  <?= $firstAccordion ? 'aria-expanded="true"' : '' ?>></a>
                                            </h4>
                                            <p class="textone bold text-muted small">
                                                Register yourself and/or invite your colleagues to register to attend.
                                            </p>
                                        </div>
                                        <div id="staff_collapse_<?= $row['id'] ?>" class="panel-collapse collapse <?= $firstAccordion ? 'in' : '' ?>">
                                            <div class="panel-body" style="padding: 0;">
                                                <table class="table table-bordered" style="background-color: transparent">
                                                    <thead>
                                                        <tr>
                                                            <th class="staff">Staff Name</th>
                                                            <th class="status">Status</th>
                                                            <th class="registration">Registration</th>
                                                            <th class="text-center cancel">
                                                                <?php
                                                                if ($packages_section == 'Desktop Package') {
                                                                    echo 'Desktop';
                                                                } elseif ($packages_section == 'SuperBooth Package') {
                                                                    echo 'SuperBooth';
                                                                } else {
                                                                    echo $packages_section; 
                                                                }
                                                                ?>
                                                                <span>(<?php
                                                                $countquery = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS total FROM attending_staff_attendance WHERE supplier_id = $srf_user AND post_id = $srf_event"));
                                                                $total_attending = $countquery['total'];
                                                                echo $total_attending;
                                                                ?>
                                                                    /<?php echo $maxStaff; ?>)</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                        <?php
                                                            $emailstart_date = date("d-m-Y", strtotime($start_date));
                                                            $emaildeadline = '';
                                                            if (!empty($deadline)) {
                                                                $emaildeadline = date("d-m-Y", strtotime(str_replace('/', '-', $deadline)));
                                                            }

                                                            $finalDate = !empty($emaildeadline) ? $emaildeadline : $emailstart_date;

                                                            // echo "start: " . $emailstart_date . " deadline: " . $emaildeadline . "<br>";
                                                            // echo $finalDate;

                                                        ?>
                                                        <tbody data-lep-id="<?= $row['id'] ?>"
                                                            data-maxstaff="<?= $maxStaff ?>"
                                                            data-post-title="<?= $row['post_title'] ?>"
                                                            data-post-id="<?= $row['post_id'] ?>"
                                                            data-post-date="<?= !empty($row['invitation_deadline']) ? $row['invitation_deadline'] : $row['start_date'] ?>"
                                                            data-mail-date="<?= $finalDate ?>">
                                                            <?php

                                                            $isCoordinatorExists = false;
                                                            $attendingStaff = mysql_query("SELECT a.id, a.supplier_id, a.staff_id, a.status, a.staff_email, a.token, CONCAT(u.first_name, ' ', u.last_name) AS name FROM attending_staff_attendance a LEFT JOIN users_data u ON a.staff_id = u.user_id WHERE a.post_id = " . intval($row['post_id']) . " AND a.supplier_id = " . intval($row['user_id']) . " ORDER BY a.id ASC");
                                                                    echo '<div class="hidden">'. "SELECT a.id, a.supplier_id, a.staff_id, a.status, a.staff_email, a.token, CONCAT(IF(TRIM(CONCAT(u.first_name, u.last_name)) != '', CONCAT(u.first_name, ' ', u.last_name), u.company)) AS name FROM attending_staff_attendance a LEFT JOIN users_data u ON a.staff_id = u.user_id WHERE a.post_id = " . intval($row['post_id']) . "  AND a.supplier_id = " . intval($row['user_id']) . " ORDER BY a.id ASC".'</div>';
                                                            $staffCount = $maxStaff;
                                                            while ($staff = mysql_fetch_assoc($attendingStaff)) {
                                                                if ($staff['status'] != 'canceled') {
                                                                    $staffCount++;
                                                                }

                                                                $checkCoordinatorQuery = "SELECT * FROM attending_supplier_staff_registration WHERE user_id = " . $staff['staff_id'] . " AND supplier_id = " . $staff['supplier_id'] . " AND post_id = " . $row['post_id'] . " AND is_event_coordinator = '1' LIMIT 1";

                                                                $result = mysql_query($checkCoordinatorQuery);
                                                                $isCoordinatorExists = ($result && mysql_num_rows($result) > 0) ? true : false;

                                                                // Check if staff is "Not Required"
                                                                $notRequired = ($staff['name'] == "Not Required - We do not need this membe" ||
                                                                    $staff['staff_email'] == "Not Required - We do not need this member");

                                                                // Determine row color
                                                                if ($notRequired) {
                                                                    $trColor = 'danger';
                                                                } else {
                                                                    $trColor = ($staff['status'] == 'Completed') ? 'success' : 'warning';
                                                                }
                                                                    
                                                                $tokensql = mysql_fetch_assoc(mysql_query("SELECT * FROM users_data WHERE user_id =".$staff['staff_id']));
                                                                $CoordinatorLabel = '';
                                                                $logback_admin = mysql_fetch_assoc(mysql_query("SELECT token FROM users_data WHERE user_id =".$staff['supplier_id']));
                                                                session_start();
                                                                $_SESSION['val'] = $logback_admin['token'];
                                                                if ($user_data['user_id'] == $staff['supplier_id']) {
                                                                    if ($user_data['user_id'] != $staff['user_id']) {
                                                                    $CoordinatorLabel = ($isCoordinatorExists)
                                                                        ? '<div style="text-align: center;"><strong>Event Coordinator</strong><a href="/login/token/' . $tokensql['token'] . '/add-supplier-card/wip/'.$logback_admin['token'].'" class="btn btn-primary">Login as Member</a></div>'
                                                                        : '<a href="/login/token/' . $tokensql['token'] . '/add-supplier-card/wip/'.$logback_admin['token'].'" class="btn btn-primary">Login as Member</a>';
                                                                }
                                                                } else {
                                                                    // if ($pars['3'] == $logback_admin['token']) {
                                                                        $CoordinatorLabel = ($isCoordinatorExists) ? '<center><b>Event Coordinator</b><a href="/login/token/' . $logback_admin['token'] . '/add-supplier-card/wip" class="btn btn-primary back-to-company-from">Back to company</a></center>' : '<center><a href="/login/token/' . $logback_admin['token'] . '/add-supplier-card/wip" class="btn btn-primary back-to-company-from">Back to company</a></center>';                                                                                  
                                                                    // } else {
                                                                        // $CoordinatorLabel = ($isCoordinatorExists) ? '<b><center>Event Coordinator</center></b>' : '';
                                                                    // }
                                                                }

                                                                $applicationTokenQuery = mysql_query("SELECT token FROM create_application_pages WHERE event_id = '".$row['post_id']."' AND `type` = 'registration form'");
                                                                $applicationTokenResult = mysql_fetch_assoc($applicationTokenQuery);
                                                                $applicationtoken = $applicationTokenResult['token'];
                                                                $attendingUrl = "https://www.motiv8search.com/attending-supplier-staff-registration?ref=".$applicationtoken."&token=".$staff['token'];
                                                                
                                                                // Output row with appropriate class
                                                                echo '<tr class="' . $trColor . '">';
                                                                echo '<td class="table_selected_staff">';
                                                                
                                                                $disabled = (date('Y-m-d') > $start_date) ? 'disabled' : '';
                                                                $style = (date('Y-m-d') > $start_date) ? 'disabled-select' : '';
                                                                    
                                                                echo '<select class="form-control attending_staff' . $style . '" name="attending_staff[]" data-id="' . htmlspecialchars($staff['id']) . '" '.$disabled.'><option value="">Select Attending Staff</option>';

                                                                    // Check if the staff name or email should be pre-selected
                                                                    
                                                                $formname_query = mysql_query("SELECT full_name FROM `attending_supplier_staff_registration` WHERE email = '".mysql_real_escape_string($staff['staff_email'])."'");
                                                                $formname = mysql_fetch_assoc($formname_query);
                                                                $formnamevalue = $formname['full_name'];
                                                                $name = trim($staff['name']);

if (!empty($name)) {
    $selectedValue = htmlspecialchars($name);
} elseif (!empty($formnamevalue)) {
    $selectedValue = htmlspecialchars($formnamevalue);
} else {
    $selectedValue = htmlspecialchars($staff['staff_email']);
    $isEmailUsed = true;
}

                                                                // Create the first option with pre-selection
                                                                echo '<option value="' . $selectedValue . '" selected>' . $selectedValue . '</option>';

                                                                $attendingStaff_query = array();

                                                                // Fetch the attending staff IDs from the database
                                                                $q = mysql_query("SELECT staff_id FROM attending_staff_attendance WHERE post_id=" . intval($row['post_id']) . " AND `status` != 'Cancelled'");

                                                                while ($s = mysql_fetch_assoc($q)) {
                                                                    $attendingStaff_query[] = $s['staff_id'];
                                                                }

                                                                // Query to get the list of staff from users_data
                                                                $stuffq = mysql_query("SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, email FROM users_data WHERE FIND_IN_SET(user_id, (SELECT GROUP_CONCAT(staff_ids) FROM supplier_attendingstaffs $Selectwhere ))");

                                                                while ($stuff = mysql_fetch_assoc($stuffq)) {
                                                                    // Check if the user_id is not already in the attending staff list
                                                                    if (!in_array($stuff['user_id'], $attendingStaff_query)) {
                                                                        $formnames_query = mysql_query("SELECT full_name FROM `attending_supplier_staff_registration` WHERE email = '".mysql_real_escape_string($stuff['email'])."'");
                                                                        $formnames = mysql_fetch_assoc($formnames_query);
                                                                        $formnamesvalue = $formnames['full_name'];
                                                                        // Check if this user should be pre-selected (match the value with selected staff)
                                                                        $selected = ($stuff['name'] == $staff['name'] || $stuff['email'] == $staff['staff_email']) ? 'selected="selected"' : '';
                                                                        echo '<option value="' . $stuff["user_id"] . '" data-email="' . $stuff["email"] . '" ' . $selected . '>' . (empty(trim($stuff["name"])) ? $formnamesvalue : $stuff['name']) . '</option>';
                                                                    }
                                                                }

                                                                echo '<option style="font-size: 10px !important;">Not Required - We do not need this member</option>
                                                                </select>';

                                                                // }

                                                                echo '</td>';
                                                                //  echo "SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, email FROM users_data WHERE FIND_IN_SET(user_id, (SELECT GROUP_CONCAT(staff_ids) FROM supplier_attendingstaffs WHERE supplier_id = " . intval($login_uid) . "))";
                                                                // Status column - apply strikethrough only if "Not Required"
                                                                if ($notRequired && $staff['status'] == 'TBC') {
                                                                    echo '<td class="status"><s>TBC</s></td>';
                                                                } else {
                                                                    echo '<td class="status">' . htmlspecialchars($staff['status']);
                                                                if ($staff['supplier_id'] == $user_data['user_id'] && $staff['status'] == 'TBC') {
                                                                    echo '<br>
                                                                        <button type="button" class="copy_btn btn btn-default btn-sm" data-copylink="' . htmlspecialchars($attendingUrl) . '">
                                                                            <i class="fa fa-files-o" aria-hidden="true"></i> Copy Link
                                                                        </button>';
                                                                }
                                                                    echo '</td>';
                                                                }

                                                                // Staff ID (For cancel invitation action)
                                                                $staff_id = ($staff['staff_id'] > 0) ? $staff['staff_id'] : $staff['staff_email'];

                                                                if ($staff['status'] == 'TBC') {
                                                                    // Apply strikethrough to "Awaiting Registration" only if "Not Required"
                                                                    $registerText = $notRequired ? '<s>Awaiting Registration</s>' : 'Awaiting Registration';
                                                                    echo '<td class="register">' . $registerText . ' </td>';

                                                                    // Hide cancel button only if "Not Required"
                                                                    if (!$notRequired) {
                                                                        if(date('Y-m-d') > $start_date && $event_supplierid != $supplier_id){
                                                                            echo "
                                                                            <style> 
                                                                                .cancel-invite{
                                                                                    display: none !important;
                                                                                }
                                                                            </style>
                                                                            ";
                                                                        }
                                                                        echo '<td class="action text-center">
                                                                        <form action="" method="post">
                                                                            <input type="hidden" name="post_id" value="' . htmlspecialchars($row['post_id']) . '">
                                                                            <input type="hidden" name="supplier_id" value="' . htmlspecialchars($row['sender_id']) . '">
                                                                            <input type="hidden" name="staff_id" value="' . htmlspecialchars($staff_id) . '">
                                                                            <input type="hidden" name="delete_attended" value="delete">
                                                                            <button type="submit" class="btn btn-danger cancel-invite">Cancel Invitation</button>
                                                                        </form>';

                                                                        if ($user_data['user_id'] == $staff['supplier_id']) {
                                                                            if(!$isEmailUsed) {
                                                                            echo '<div style="padding-top:10px;">
                                                                                    <a href="/login/token/' . urlencode($tokensql['token']) . '/add-supplier-card/wip/' . urlencode($logback_admin['token']) . '" class="btn btn-primary">Login as Member</a>
                                                                                </div>';
                                                                            }
                                                                        }
                                                                        

                                                                        echo '</td>';

                                                                    } else {
                                                                        echo '<td></td>'; // Empty column to maintain table structure
                                                                    }

                                                                } elseif ($staff['status'] == 'Completed') {
                                                                    echo '<td class="register">Registered & Going</td><td class="text-center">' . $CoordinatorLabel . '</td>';
                                                                } elseif ($staff['status'] == 'canceled') {
                                                                    echo '<td class="register">Expired</td><td class="text-center">' . $CoordinatorLabel . '</td>';
                                                                }

                                                                echo '</tr>';
                                                            }
                                                            if ($staffCount >= $maxStaff) {
                                                                echo '<tr><td colspan="4" style="text-align: center; color: red;">Limit Reached: You can only add up to ' . $maxStaff . ' attending staff for this event.</td></tr>';
                                                            }
                                                            // Check if the limit is already reached in supplier_registration_form
                                                            // $limitCheckSql = "SELECT limit_reached FROM supplier_registration_form WHERE user_id = $supplier_id AND event_id = " . $row['post_id'];
                                                            $macro = $row['user_id'];
                                                            $limitCheckSql = "SELECT limit_reached FROM supplier_registration_form WHERE user_id = $macro AND event_id = " . $row['post_id'];
                                                            // $limitCheckSql = "SELECT limit_reached FROM supplier_registration_form WHERE event_id = " . $row['post_id'];
                                                            $limitCheckResult = mysql_query($limitCheckSql);
                                                            $limitReached = false;
                                                            if ($limitCheckRow = mysql_fetch_assoc($limitCheckResult)) {
                                                                $limitReached = $limitCheckRow['limit_reached'] == 1;
                                                            }
                                                            // if (($staffCount < $maxStaff) && $login_uid == $row['user_id']): 
                                                            if (($staffCount < $maxStaff)):
                                                                
                                                                // Additional check if the limit is reached
                                                                $checkLimitSql = "SELECT COUNT(*) as count FROM attending_staff_attendance 
                                                                WHERE supplier_id = $supplier_id 
                                                                AND post_id = " . $row['post_id'] . " 
                                                                AND lep_id = " . $row['id'];
                                                                $limitResult = mysql_query($checkLimitSql);
                                                                $limitReachedAttendance = false;

                                                                if ($limitRow = mysql_fetch_assoc($limitResult)) {
                                                                    if ($limitRow['count'] > 0) {
                                                                        $limitReachedAttendance = true;
                                                                    }
                                                                }
                                                                ?>


                                                                <?php
                                                                $limit_members = $maxStaff - $total_attending;
                                                                for ($i = 0; $i < $limit_members; $i++): ?>
                                                                    <tr class="search-member">
                                                                        <td style="vertical-align: middle;">
                                                                            <div class="row">
                                                                                <?php if (!$limitReached): ?>
                                                                                    <div
                                                                                        class="col-md-6 col-sm-6 col-xs-6 this-one">
                                                                                        <select class="form-control attending_staff"
                                                                                            name="attending_staff[]">
                                                                                            <option value="">Select Attending Staff
                                                                                            </option>
                                                                                            <?php
                                                                                            $attendingStaff = array();
                                                                                             $q = mysql_query("SELECT staff_id FROM attending_staff_attendance WHERE post_id=" . intval($row['post_id']) . " AND supplier_id=" . intval($login_uid) . " AND `status` != 'Cancelled'");
                                                                                            while ($s = mysql_fetch_assoc($q)) {
                                                                                                $attendingStaff[] = $s['staff_id'];
                                                                                            }
                                                                                            $q = mysql_query("SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, email FROM users_data WHERE FIND_IN_SET(user_id, (SELECT GROUP_CONCAT(staff_ids) FROM supplier_attendingstaffs  $Selectwhere ))");
                                                                                            
                                                                                            while ($s = mysql_fetch_assoc($q)) {
                                                                                                if (!in_array($s['user_id'], $attendingStaff)) { 
                                                                                                    
                                                                                                    
                                                                                            $formname_query = mysql_query("SELECT full_name FROM attending_supplier_staff_registration WHERE user_id = ".$s['user_id']);
                                                                                            $formname = mysql_fetch_assoc($formname_query);
                                                                                                
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                            ?>
                                                                                            <option value="<?= $s['user_id'] ?>" data-email="<?= $s['email'] ?>">
                                                                                                <?php echo !empty(trim($s['name'])) ? $s['name'] : (!empty(trim($formname['full_name'])) ? trim($formname['full_name']) : $s['email']); ?>
                                                                                            </option>
                                                                                                <?php }
                                                                                            } ?>
                                                                                            <option style="font-size: 1px !important;">
                                                                                                Not Required - We do not need this member</option>
                                                                                        </select>

                                                                                    </div>
                                                                                <?php else: ?>
                                                                                    <p style="text-align: center; color: red; width: 100%; margin: 0;"
                                                                                        class="limit-message">You have submitted and
                                                                                        closed the invitation. No further
                                                                                        invitations can be sent.</p>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </td>
                                                                        <td class="status">TBC</td>
                                                                        <td class="register">Awaiting Registration</td>
                                                                        <td class="action">
                                                                            <?php if (!$limitReached): ?>
                                                                                <form action="" method="post">
                                                                                    <input type="hidden" name="post_id"
                                                                                        value="<?= $row['post_id'] ?>">
                                                                                    <input type="hidden" name="supplier_id"
                                                                                        value="<?= $supplier_id ?>">
                                                                                    <input type="hidden" name="staff_id"
                                                                                        class="staff-delete"
                                                                                        value="<?= $staff['staff_id'] ?>">
                                                                                    <input type="hidden" name="delete_attended"
                                                                                        value="delete">
                                                                                    <button type="submit"
                                                                                        class="btn btn-sm btn-danger cancel-invite"
                                                                                        style="display: none;">
                                                                                        Cancel Invitation
                                                                                    </button>
                                                                                </form>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endfor; ?>

                                                            <?php endif; ?>
                                                            <script>
                                                                $(document).ready(function () {
                                                                    $(".limit-reached-btn").click(function () {
                                                                        var supplier_id = <?= $supplier_id ?>;
                                                                        var event_id = $(this).closest("tr").find(".event-id").text().trim(); // Ensure fetching the right event_id

                                                                        $.ajax({
                                                                            url: "https://www.motiv8search.com/api/widget/html/get/update_limit",
                                                                            type: "POST",
                                                                            data: { supplier_id: supplier_id, event_id: event_id },
                                                                            success: function (response) {
                                                                                console.log(response);
                                                                                if (response.trim() === "success") {
                                                                                    location.reload();
                                                                                } else {
                                                                                    location.reload();
                                                                                }
                                                                            },
                                                                            error: function () {
                                                                                alert("Failed to update limit. Please try again.");
                                                                            }
                                                                        });
                                                                    });
                                                                });
                                                            </script>
                                                        </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        No past events to display yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Add Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php
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


$postresults = mysql_query($sql);

while ($row = mysql_fetch_assoc($postresults)) {
    // echo $row['user_id'];
    // echo "<h1>Sup ID: ".$srf_user."</h1>";
    ?>
    <script>
        $(document).ready(function () {
            //$('.publish-post-button').remove();
            function isValidEmail(email) {
                let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return emailPattern.test(email);
            }
            let selectedStaffIds = {};
            function initializeSelect2(element) {
                element.select2({
                    tags: true,
                    placeholder: "Select Staff or add new by typing their email",
                    createTag: function (params) {
                        let email = params.term;
                        if (!isValidEmail(email)) {
                            return { id: email, text: "❌ Invalid Email", newTag: true, invalid: true };
                        }
                        if (existingEmails.includes(email)) {
                            return null; // Do not allow creating a tag for existing emails
                        }
                        let name = email.split('@')[0]
                            .replace(/[._]/g, ' ')
                            .replace(/\b\w/g, c => c.toUpperCase());
                        return { id: email, text: email, newTag: true };
                    }
                }).on("select2:select", function (e) {
                    if (e.params.data.invalid) {
                        swal("Invalid Email", "Please enter a valid email address.", "error");
                        $(this).val(null).trigger("change");
                    }
                });
            }
            $('.attending_staff').each(function () {
                initializeSelect2($(this));
            });

            $(document).on('focus', '.attending_staff', function () {
                $(this).data('prev', $(this).val());  // Store previous value when focused
            });

            $(document).on('change', '.attending_staff', function () {
            	console.log("change");
                const Element = $(this);
                const parentTbody = Element.closest('tbody');
                const postTitle = parentTbody.data('post-title');
                const postId = parentTbody.data('post-id');
                const postDate = parentTbody.data('post-date');
                const mailDate = parentTbody.data('mail-date');
                const lepId = parentTbody.data('lep-id');

                var selectedOption = Element.find(":selected");
                var staffName = selectedOption.text();
                var staffId = selectedOption.val();
                var staffEmail = selectedOption.data("email") || ""; // Default empty
                var supplierName = "<?= $user['company']?>";

                var supplierId = "<?= isset($srf_user) ? $srf_user : $row['user_id'] ?>";
                var staffDatabaseId = Element.data("id"); // Retrieve staff ID from select

                var previousValue = Element.data("prev") || ""; // Ensure correct previous value
                var actionType = (previousValue && previousValue !== staffId) ? "update" : "insert";
                Element.data("prev", staffId); // Store new value for tracking

                // 🔹 If user enters a custom email, treat it as `staff_email`
                if (!$.isNumeric(staffId)) {
                    staffEmail = staffId;  // Store entered text in staff_email
                    staffId = ""; // Clear staff_id
                }

                $.ajax({
                    url: "https://www.motiv8search.com/api/widget/json/get/invite_attending_staff",
                    type: "POST",
                    data: {
                        supplier_name: supplierName,
                        id: staffDatabaseId,
                        supplier_id: supplierId,
                        staff_id: staffId,  // Could be empty if email is entered manually
                        staff_name: staffName,
                        staff_email: staffEmail,
                        post_title: postTitle,
                        post_id: postId,
                        post_date: postDate,
                        mail_date: mailDate,
                        lep_id: lepId,
                        action: actionType
                    },
                    success: function (response) {
                    	console.log(response);
                        console.log(data);
                    if (response.success) {
				        // If success is true, show a success message
					swal({
                        title: "Success",
                        text: response.message,  // This will allow HTML content in the message
                        icon: "success",
                        closeOnClickOutside: false
                    }).then(function() {
                        // Call the reloadPage function after the alert is closed
                        location.reload();
                    });
				    } else {
				        // If success is false, show an error message
				        swal("Error", response.message, "error");
				    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
					    // Log the details for debugging
					    console.log("Request failed: " + textStatus);
					    console.log("Error details: " + errorThrown);
					    
					    // Provide user feedback
					    swal({
                        title: "Warning",
                        text: "Please look out for your confirmation email with important information (Check Junk/Spam!)",
                        icon: "warning",
                        button: "OK",
                        closeOnClickOutside: false, // Prevent closing the alert by clicking on the background
                        closeOnEsc: false // Prevent closing the alert using the Escape key
                    }).then(() => {
                        location.reload(); // Reload the page after clicking OK
                    });

				    }
                });
            });




            $(document).on('click', '.add-row', function () {
                const postTbody = $(this).closest('tbody');
                const maxStaff = postTbody.data('maxstaff');
                const currentStaffCount = postTbody.find('.selected_staff:visible').length;

                if (currentStaffCount >= maxStaff) {
                    swal("Limit Reached", "You can only add up to " + maxStaff + " attending staff.", "warning");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    return;
                }

                let options = `<?php
                $query = mysql_query("SELECT user_id, CONCAT(first_name, ' ', last_name) AS name, email FROM users_data WHERE FIND_IN_SET(user_id, (SELECT GROUP_CONCAT(staff_ids) FROM supplier_attendingstaffs $Selectwhere ))");
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
                        
                    </td>
                </tr>
            `);

                postTbody.append(newRow);
                initializeSelect2(newRow.find('.attending_staff'));
                $(this).remove();
            });
            $(document).on('click', '.cancel-invite', function (e) {
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
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("tr.search-member").forEach(function (row) {
                let firstTd = row.querySelector("td");
                // if (firstTd) {
                //     firstTd.setAttribute("colspan", "4");
                // }

                // Hide remaining <td> elements
                let otherTds = row.querySelectorAll("td:nth-child(n+2)");
                otherTds.forEach(function (td) {
                    // td.style.display = "none";
                });
            });
        });
        let existingEmails = [];
        document.querySelectorAll('.table_selected_staff .selected_staff').forEach(function (element) {
            existingEmails.push(element.textContent.trim());
        });
    </script>
<?php } ?>

<style>
    .label-success {
        background-color: #5cb85c;
    }

    .label-danger {
        background-color: #d9534f;
    }

    .label-warning {
        background-color: #f0ad4e;
    }
    .select2-selection__rendered[title="Not Required - We do not need this member"] {
    font-size: 13px;
    }
</style>