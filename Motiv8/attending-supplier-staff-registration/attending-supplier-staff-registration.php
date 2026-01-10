<?php
session_start();
if (isset($_COOKIE['userid']) && $_COOKIE['userid'] !== '') {
  $user_data = getUser($_COOKIE['userid'], $w);
  ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>
  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Handle password only if new user registration ---
    if (isset($_POST['password']) && isset($_POST['confirm_password']) && $_GET['newuser'] == 1) {
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if ($password === $confirm_password && $password !== '') {
            // Hash password (safe, works on PHP >= 5.5)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $user_id = intval($_COOKIE['userid']); // current logged in user
            $updatePasswordSQL = "
                UPDATE users_data
                SET password = '" . mysql_real_escape_string($hashedPassword) . "'
                WHERE user_id = $user_id
            ";
            if (!mysql_query($updatePasswordSQL)) {
                echo "<div class='alert alert-danger'>Error saving password: " . mysql_error() . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Passwords do not match. Please try again.</div>";
            exit; // stop registration if mismatch
        }
    }


    $full_name = $_POST['full_name'];
	 $customphotoid =$_POST['customphotoid'];
    $is_event_coordinator = $_POST['is_event_coordinator'];
    $large_items = $_POST['large_items'];
    $job_title = $_POST['job_title'];
    $attended_before = $_POST['attended_before']; // "Yes" or "No"
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $company_name = mysql_real_escape_string($_POST['company_name']);
    $registered_company = mysql_real_escape_string($_POST['registered_company']);
    $setup_instructions = isset($_POST['setup_instructions']) ? 1 : 0;
    $shipping_items = isset($_POST['shipping_items']) ? 1 : 0;
    $end_time = isset($_POST['end_time']) ? 1 : 0;
    $cannot_bring = isset($_POST['cannot_bring']) ? 1 : 0;
    $event_equipment = isset($_POST['event_equipment']) ? 1 : 0;
    $event_space = isset($_POST['event_space']) ? implode(',', $_POST['event_space']) : '';
    $internet_wifi = isset($_POST['internet_wifi']) ? 1 : 0;
    $attendance_numbers = isset($_POST['attendance_numbers']) ? 1 : 0;
    $event_briefing = isset($_POST['event_briefing']) ? 1 : 0;
    $areas_access = isset($_POST['areas_access']) ? 1 : 0;
    $manufacturer_attendees = isset($_POST['manufacturer_attendees']) ? 1 : 0;
    $access_requirement = isset($_POST['access_requirement']) ? 1 : 0;
    $event_hours = isset($_POST['event_hours']) ? 1 : 0;
    $packages_section = $_POST['packages_section'];
    $supplier_id = $_POST['supplier_id'];
    $user_id = $_COOKIE['userid'];
    $post_id = $_POST['post_id'];
    $staffToken = $_POST['staff_token'];
    $update_time = date('Y-m-d H:i:s');

    $travel_method = '';
    $travel_details = '';

    if (isset($_POST['travel_method'])) {
        $travel_method = mysql_real_escape_string($_POST['travel_method']);

        if ($travel_method === 'Vehicle' && isset($_POST['vehicle_registration_plate'])) {
            $travel_details = mysql_real_escape_string($_POST['vehicle_registration_plate']);
        } elseif ($travel_method === 'Other' && isset($_POST['other_travel_specify'])) {
            $travel_details = mysql_real_escape_string($_POST['other_travel_specify']);
        }
    }
    //$equipment_type = $_POST['equipment_type'];

    // if ($equipment_type === "Others" && !empty($_POST['other_equipment'])) {
    //   $equipment_type = $_POST['other_equipment']; // Only override if "Others" is chosen
    // }
    $otherequipment = mysql_real_escape_string($_POST['other_equipment']);

    // FTP server credentials
    $ftp_host = brilliantDirectories::getDatabaseConfiguration('ftp_server'); //host
    $ftp_user = brilliantDirectories::getDatabaseConfiguration('website_user'); //username
    $ftp_pass = brilliantDirectories::getDatabaseConfiguration('website_pass'); //password
    $ftp_dir = "/public_html/images/";      // FTP upload directory

    // Establish an FTP connection
    $conn_id = ftp_connect($ftp_host);

    if (!$conn_id) {
      die("Could not connect to FTP server");
    }

    // Login to FTP server
    if (!ftp_login($conn_id, $ftp_user, $ftp_pass)) {
      ftp_close($conn_id);
      die("FTP login failed");
    }

    // Enable passive mode

    ftp_pasv($conn_id, true);

    if (isset($_FILES['files'])) {
      if ($_FILES['files']['error'] === UPLOAD_ERR_OK) {
        $tmp_file = $_FILES['files']['tmp_name']; // Temporary file path
        $file_name = $_FILES['files']['name'];
        // Extract file extension
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_id = mt_rand(10000000, 99999999); // Generates an 8-digit unique ID
        $file_base_name = pathinfo($file_name, PATHINFO_FILENAME); // Extracts filename without extension
        $safe_file_name = preg_replace("/[^a-zA-Z0-9-_]/", "-", $file_base_name); // Remove special characters
        $final_file_name = $safe_file_name . "-" . $unique_id . "." . $file_extension; // Append ID and extension
        $ftp_target_file = $ftp_dir . basename($final_file_name);

        // Upload the file
        if (ftp_put($conn_id, $ftp_target_file, $tmp_file, FTP_BINARY)) {

        } else {

        }
      } else {

      }

    } else {
      // echo "No files selected for upload.";
      echo "";
    }

    ftp_close($conn_id);

    $insertQuery = "
    INSERT INTO attending_supplier_staff_registration (
        is_event_coordinator, large_items, full_name, job_title, attended_before, email, phone_number, company_name, registered_company,
        setup_instructions, shipping_items, end_time, cannot_bring, event_equipment, event_space,
        internet_wifi, attendance_numbers, event_briefing, areas_access, manufacturer_attendees,
        access_requirement, event_hours, Supplier_id, user_id, post_id, update_time, packages_section,files,other_equipment,customphotoid, travel_method, travel_details
    ) VALUES (
        '$is_event_coordinator', '$large_items', '$full_name', '$job_title', '$attended_before', '$email', '$phone_number', '$company_name', '$registered_company',
        '$setup_instructions', '$shipping_items', '$end_time', '$cannot_bring', '$event_equipment', '$event_space',
        '$internet_wifi', '$attendance_numbers', '$event_briefing', '$areas_access', '$manufacturer_attendees',
        '$access_requirement', '$event_hours', '$supplier_id', '$user_id', '$post_id', '$update_time', '$packages_section','$final_file_name','$otherequipment','$customphotoid', '$travel_method', '$travel_details'
    )";
    
    //echo $insertQuery;
    // if ($_POST['staff_id'] == $user_data['user_id'] || strtolower($_POST['staff_email']) == strtolower($user_data['email'])) {

    if (mysql_query($insertQuery)) {
      $Id = mysql_insert_id();
      $sql = "UPDATE attending_staff_attendance SET `status` = 'Completed', `staff_id` = " . $user_data['user_id'] . " WHERE token = '$staffToken'";
      mysql_query($sql);
      mysql_query("DELETE FROM supplier_attendingstaffs WHERE supplier_id = $supplier_id");
      // Insert attending staff IDs into supplier_attendingstaffs table
      $attending_staff_query = "SELECT DISTINCT staff_id 
          FROM attending_staff_attendance 
          WHERE supplier_id = $supplier_id 
            AND status = 'Completed' 
            AND staff_id != 0";
        $attending_staff_result = mysql_query($attending_staff_query);
        $staff_ids = [];
        while ($row = mysql_fetch_assoc($attending_staff_result)) {
            $staff_ids[] = $row['staff_id'];
        }
        $staff_ids_str = implode(',', $staff_ids);

        mysql_query("INSERT INTO supplier_attendingstaffs (supplier_id, staff_ids, created_by) 
           VALUES ($supplier_id, '$staff_ids_str', 'System')");

      // Display success message and redirect
      echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
                <script>
                  Swal.fire({
                    title: 'Registration Received!',
                    text: 'Please look out for your confirmation email with important event information
(Check Junk/Spam)',
                    icon: 'success',
                    showCancelButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Go to Account'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = 'https://www.motiv8search.com/account/add-supplier-card/view?confirm-attendance=1&token=$staffToken';
                    }
                  })
                </script>";
                $add_info_link_sql = "SELECT `value` FROM `users_meta` WHERE `key` = 'additional_info_link' AND `database_id` = $post_id AND `database` = 'data_posts'";
                $add_info_link_results = mysql_query($add_info_link_sql);
                $add_info_link = mysql_fetch_assoc($add_info_link_results);
                $info_link = $add_info_link['value'];
      $w['staffname'] = $full_name;
      $w['infolinkkking'] = $info_link;
      $emailPrepareone = prepareEmail('attendee_register_success', $w);
      $sendemail = sendEmailTemplate($w['website_email'], $email, $emailPrepareone[subject], $emailPrepareone[html], $emailPrepareone[text], $emailPrepareone[priority], $w);
    } else {
      echo "Error inserting live event post: " . mysql_error();
    }
    if (!empty($full_name) && !empty($email) && $user_id > 0) {
      // Check if first_name and last_name are already set
      $has_first = !empty($user_data['first_name']);
      $has_last  = !empty($user_data['last_name']);

      if (!$has_first || !$has_last) {
          // Split full_name into first word (first name) and the rest (last name)
          $space_pos = strpos($full_name, ' ');
          if ($space_pos !== false) {
              $first_name = mysql_real_escape_string(substr($full_name, 0, $space_pos));
              $last_name = mysql_real_escape_string(substr($full_name, $space_pos + 1));
          } else {
              $first_name = mysql_real_escape_string($full_name);
              $last_name = '';
          }

          // Update users_data table
          $sql = "UPDATE users_data
                  SET first_name = '$first_name', last_name = '$last_name'
                  WHERE user_id = $user_id AND email = '$email'";
          $result = mysql_query($sql);

          if ($result) {
              echo "";
          } else {
              echo "<div class='alert alert-danger'>Error: " . mysql_error() . "</div>";
          }
      }
  }
  }
  // Fetch user data and check if the user has already submitted a registration form
  $user_data = getUser($_COOKIE['userid'], $w); // Get Current User Data
  $selectSql = mysql_fetch_assoc(mysql_query("SELECT * FROM create_application_pages WHERE token = '$_GET[ref]' GROUP BY event_id "));
  $myselectSql = mysql_query("SELECT * FROM create_application_pages WHERE event_id = '{$selectSql['event_id']}' HAVING COUNT(*) <= 30");

  if (mysql_num_rows($myselectSql) != '0') { ?>
  <?php
                if (isset($_GET['ref'])) {
                  $token = $_GET['ref'];
                  $query = "SELECT id, event_id, subheading, event_image, short_description, add_ons_id FROM create_application_pages WHERE token = '$token'";
                  $results = mysql_query($query);

                  if ($results) {
                    $data = mysql_fetch_assoc($results);
                    $form_id = $data['id'];
                    $event_id = $data['event_id'];
                    $user_id = $_COOKIE['userid'];
                    $subheading = $data['subheading'];
                    $shortDescription = $data['short_description'];
                    $selectedAddOns = $data['add_ons_id'];
                    $event_image = $data['event_image'];
                  } else {
                    echo "Error executing query";
                  }
                  $attenstafftoken_query = mysql_query("SELECT * FROM attending_staff_attendance WHERE post_id = '$event_id' AND (staff_id = '" . $user_data['user_id'] . "' OR staff_email = '" . $user_data['email'] . "')");

                  if ($attenstafftoken_query) {
                    $attenstafftoken = mysql_fetch_assoc($attenstafftoken_query);
                    $staffToken = $attenstafftoken['token'];
                    $staffEmail = $attenstafftoken['staff_email'];
                    $staffId = $attenstafftoken['staff_id'];
                    $supplier_id = $attenstafftoken['supplier_id'];
                    $supplier_data = getUser($supplier_id, $w);
                     if($_SERVER['REMOTE_ADDR'] === '49.205.169.64'){
                       // print_r($supplier_data);
                        //echo 'working';
                       // echo $_COOKIE['userid'];
                     }
                  } else {
                    echo "Error executing query: " . mysql_error();
                  }

                }
                ?>
                

      <?php
      if($_SERVER['REMOTE_ADDR'] === '49.205.169.64'){
          //echo "supplier: ".$supplier_id."<br> user: ". $user_id."<br>Form Token: ".$staffToken."<br> Get Token: ".$_GET['token'];
      }
if ($_SERVER['REMOTE_ADDR'] === '183.83.135.7') {
      echo "supplier: ".$supplier_id."<br> user: ". $user_id."<br>Form Token: ".$staffToken."<br> Get Token: ".$_GET['token'];
      echo "<br>";
      echo "SELECT * FROM attending_staff_attendance WHERE post_id = '$event_id' AND (staff_id = '" . $user_data['user_id'] . "' OR staff_email = '" . $user_data['email'] . "')";
}


$currentUrlbase = $_SERVER['REQUEST_URI'];
$ref_pos = strpos($currentUrlbase, 'ref=');
$token_pos = strpos($currentUrlbase, 'token=');
$slug_one = '';
$slug_two = '';
if ($ref_pos !== false) {
    $slug_one = substr($currentUrlbase, $ref_pos + 4, ($token_pos - $ref_pos - 5));
}
if ($token_pos !== false) {
    $slug_two = substr($currentUrlbase, $token_pos + 6);
}
$currentUrl = 'https://www.motiv8search.com/checkout/36?redirect=/attending-supplier-staff-registration?ref=' . $slug_one . '&token=' . $slug_two;
$user_data = getUser($_COOKIE['userid'], $w);
if ($supplier_id == $user_id && $_GET['token'] != $staffToken || empty($supplier_id)) {
  // if ($supplier_id == $user_id && $_GET['token'] != $staffToken) {

  ?>
  <style>
    .swal2-icon .swal2-icon-content {
    display: flex;
    align-items: center;
    font-size: 1.75em;
}
  </style>
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
<script>
  Swal.fire({
    title: 'Warning',
    text: 'The email you have invited doesn’t match the one in your Company Account, please either change the email to match or create a personal account as well.',
    icon: 'warning',
    showCancelButton: false,
    showDenyButton: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonColor: '#3085d6',
    denyButtonColor: '#3085d6',
    confirmButtonText: 'Change Email',
    denyButtonText: 'Create Personal Account'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = '/account/contact';
    } else if (result.isDenied) {
      window.location.href = '/logout'; setTimeout(() => window.location.href = '<?php echo $currentUrl; ?>', 1500);
    }
  });
</script>

  <?php
} else {
?>

    <section class="main_form col-md-10 col-md-offset-1">
    <div class="container">
        <div class="row">
          <div class="col-md-12">
            <form class="supplier_form" action="" method="post" enctype="multipart/form-data">
              <div class="form_header">
                
                <table style="width:100%">
                  <tbody>
                    <tr>
                      <td style="border-style:none">
                        <table style="width:100%" id="desktopview">
                          <tbody>
                            <tr>
                              <td style="border-style:none; width:30%">
                                <img alt="" src="/images/<?php echo $event_image ?>" />
                              </td>
                              <td style="border-style:none; text-align:left; width:70%">
                                <h1><strong>
                                    <?= $subheading ?>
                                  </strong></h1>
                                <h2>Attending Supplier Staff Registration</h2>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table style="width:100%" id="mobileview">
                          <tbody>
                            <tr>
                              <td style="border-style:none;">
                                <img alt="" src="/images/<?php echo $event_image ?>" />
                              </td>
                            </tr>
                            <tr>
                              <td style="border-style:none; text-align:left;">
                                <h1><strong>
                                    <?= $subheading ?>
                                  </strong></h1>
                                <h2>Attending Supplier Staff Registration</h2>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td style="border-style:none; text-align:left">
                        <!-- <?= $shortDescription ?> -->
                        <p>If you are physically attending the
                          <?= $subheading ?>, you MUST complete this form.
                        </p>
                        <p><strong>If you are attending SET UP ONLY, you still MUST complete this form. Please, in place of
                            "Job Title" please put "SET UP ONLY."</strong></p>
                            <?php if($_GET['newuser']==1){?>
                        <p><strong>Since you are not a member on motiv8 directory by filling the form it will create a account and registration.</strong></p>
                        <?php } ?>
                        <p style="color: #ff0000;">Important:
                        <ul style="color: #ff0000;">
                          <li>Failure to complete this form will result in you not being granted access onsite. NO
                            EXCEPTIONS.</li>
                          <li>This Form must be completed by the person that is attending and NOT by a third party.</li>
                        </ul>
                        </p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <?php 
                $form_userid = $user_data['user_id'];
                $form_eventid = $data['event_id'];
                // $double_register = mysql_query("SELECT 1 FROM attending_supplier_staff_registration WHERE user_id='$form_userid' AND post_id='$form_eventid' AND supplier_id='$supplier_id' LIMIT 1");
                // if (mysql_num_rows($double_register)) {
                //   echo '<div style="height: 1000px;"></div>';
                //   echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
                //   <script>
                //     Swal.fire({
                //       title: 'You are already registered',
                //       text: 'You are not allowed to register this form again.',
                //       icon: 'warning',
                //       showCancelButton: false,
                //       allowOutsideClick: false,
                //       allowEscapeKey: false,
                //       confirmButtonColor: '#3085d6',
                //       confirmButtonText: 'Go to Booked Events'
                //     }).then((result) => {
                //       if (result.isConfirmed) {
                //         window.location.href = 'https://www.motiv8search.com/account/add-supplier-card/view';
                //       }
                //     })
                //   </script>";
                // } else {
                  
               ?>
              <div class="form_body">
                <div class="create_page">
                  <!-- <input type="hidden" class="form-control" name="form_id" id="form_id" value="<?php echo $data['id'] ?>">
                  <input type="hidden" class="form-control" name="event_id" id="event_id" value="<?php echo $data['event_id'] ?>"> 
                  <input type="hidden" class="form-control" name="subheading" id="subheading" value="<?php echo $data['subheading'] ?>">
                  <input type="hidden" class="form-control" name="short_description" id="short_description" value="<?php echo $data['short_description'] ?>"> -->
                  <input type="hidden" class="form-control" name="user_id" id="user_id"
                    value="<?php echo $data['user_id'] ?>">
                  <input type="hidden" class="form-control" name="post_id" id="post_id"
                    value="<?php echo $data['event_id'] ?>">
                  <input type="hidden" name="staff_token" value="<?= $staffToken ?>">
                  <input type="hidden" name="staff_email" value="<?= $staffEmail ?>">
                  <input type="hidden" name="staff_id" value="<?= $staffId ?>">
                  <input type="hidden" name="supplier_id" value="<?= $supplier_id ?>">
                </div>

                <?php if (isset($_GET['alert'])): ?>
                  <noscript>
                    <div style="color:red;font-weight:700">
                      Error:
                      <?= htmlspecialchars($_GET['alert']); ?><br>
                      Please enable JavaScript in your browser and try again
                    </div>
                  </noscript>
                <?php endif; ?>

                <?php
                // Retrieve post_id (which is event_id in this case)
                $post_id = null;

                if (isset($_GET['ref'])) {
                  $token = $_GET['ref'];
                  $query = "SELECT id, event_id, subheading, event_image, short_description, add_ons_id 
              FROM create_application_pages WHERE token = '$token'";
                  $results = mysql_query($query);

                  if ($results) {
                    $data = mysql_fetch_assoc($results);
                    $post_id = $data['event_id']; // Assign event_id as post_id
                    $user_id = $_COOKIE['userid'];
                  } else {
                    echo "Error executing query";
                  }
                }

                $isCoordinatorExists = false;
                if ($post_id) {

                  $checkCoordinatorQuery = "SELECT * FROM attending_supplier_staff_registration 
                              WHERE post_id = '$post_id' AND is_event_coordinator = '1' AND supplier_id = '$supplier_id'
                              LIMIT 1";
                  $result = mysql_query($checkCoordinatorQuery);

                  if ($result && mysql_num_rows($result) > 0) {
                    $isCoordinatorExists = true;
                  }

                  // Debugging output for browser console
                  echo "<script>console.log('Post ID: $post_id, Coordinator Exists: " . ($isCoordinatorExists ? 'Yes' : 'No') . "');</script>";
                }
                ?>

                <!-- Hide the field if an event coordinator already exists -->
                <?php if (!$isCoordinatorExists) { ?>
                  <div class="form-group">
                    <label for="is_event_coordinator">Are you the event coordinator?<span class="text-danger">*</span></label>
                    <div class="radio_filed">
                      <label>
                        <input name="is_event_coordinator" id="is_event_coordinator_yes" value="1" type="radio">
                        Yes
                      </label>
                      <label>
                        <input name="is_event_coordinator" id="is_event_coordinator_no" value="0" type="radio">
                        No
                      </label>
                    </div>
                  </div>
                <?php } ?>




                <div class="form-group">
                  <label for="full_name"><span></span> Full Name <span class="text-danger">*</span>
                  </label>
                  <input name="full_name" value="<?php echo (!empty($user_data['first_name']) || !empty($user_data['last_name'])) ? htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']) : ''; ?>" id="full_name" class="form-control" placeholder="John Smith" required>
                  <small class="text-danger full_name"></small>
                </div>

                <div class="form-group">
                  <label for="job_title"><span></span> Job Title <span class="text-danger">*</span>
                  </label>
                  <input name="job_title" id="job_title" class="form-control" placeholder="Business Development Manager"
                    required>
                  <small class="text-danger job_title"></small>
                </div>
                <div class="form-group">
                  <label for="attended_before"><span></span> Have you (personally) attended a Motiv8 Connect Supplier Engagement Day
                    before? <span class="text-danger">*</span>
                    <span class="special-info">If "No", you will be contacted regarding a mandatory introduction teams call
                      in the weeks prior to the event. Due to the location of our events, there is a lot that differs
                      between our events and your average exhibition hall trade show.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="attended_before" value="Yes" type="radio" required>
                      Yes
                    </label>
                    <label>
                      <input name="attended_before" value="No" type="radio" required>
                      No
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="email_id"><span></span> Email <span class="text-danger">*</span>
                    <span class="special-info">(john.smith@yourcompany.com)</span>
                  </label>
                  <input name="email" value="<?= $user_data['email']; ?>" id="email_id" type="email" class="form-control"
                    placeholder="Email" required readonly>
                  <small class="text-danger email"></small>
                </div>

                <div class="form-group">
                  <label for="phone_number"><span></span> Mobile Phone Number <span class="text-danger">*</span>
                    <span class="special-info">(with country code)</span></label>
                  <input name="phone_number" value="<?= $user_data['phone_number']; ?>" id="phone_number" type="tel"
                    class="form-control" placeholder="Phone Number" autofill="off" required>
                  <small class="text-danger phone_number"></small>
                </div>
                <div class="form-group">
                  <label for="company_name"><span></span> Company Name </label>
                  <input type="text" class="form-control" id="company_name" value="<?= $supplier_data['company'] ?>"
                    placeholder="Enter your answer" name="company_name" required>
                  <small class="text-danger company_name"></small>
                </div>
				
                <div class="form-group">
                  <label for="registered_company"><span></span> Name of Company that registered for this event
                    <span class="special-info">(If it is different from your email / company name)</span></label>
                  <input type="text" class="form-control" id="registered_company" value="<?= $supplier_data['company'] ?>"
                    placeholder="Enter your answer" name="registered_company">
                  <small class="text-danger registered_company"></small>
                </div>

                <?php if($_GET['newuser']==1){?>
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password"><span></span> Password</label>
                        <span class="text-danger">*</span>
                         <span class="special-info">(Please set the password and remember the password for future login to the motiv8 site)</span>
                        <input type="password" class="form-control" id="password"
                            placeholder="Enter your password" name="password" required>
                        <small class="text-danger password"></small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="confirm_password"><span></span> Confirm Password</label>
                        <span class="text-danger">*</span>
                        <input type="password" class="form-control" id="confirm_password"
                            placeholder="Re-enter your password" name="confirm_password" required>
                        <small class="text-danger confirm_password"></small>
                    </div>

                


                <?php } ?>

                <div class="form-group">
                  <label for="setup_instructions"><span></span> Setup Instructions <span class="text-danger">*</span>
                    <span class="special-info">You MUST comply with the setup instructions as set out on the Next Steps
                      page.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="setup_instructions" id="setup_instructions" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label for="shipping_items"><span></span> Shipping Items <span class="text-danger">*</span>
                    <span class="special-info">There is NO option to ship any items to the site for the event and likewise
                      there is NO option to leave any items behind to be collected via a courier etc - there is absolutely
                      NO exception to this rule, everything must arrive with you and leave with you. Additionally, there is
                      NO option to forklift items in and out, only items that can be either carried or taken in and out on a
                      trolley can be used at the event.</span></label>

                  <div class="radio_filed">
                    <label>
                      <input name="shipping_items" id="shipping_items" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="end_time"><span></span> End Time for the Event <span class="text-danger">*</span>
                    <span class="special-info"><b>Motiv8 Connect</b> agrees in advance with the manufacturer an end time for
                      the event. This is negotiated with them involving many departments internally (facilities, health and
                      safety, security, etc). Therefore packing up early is NOT an option and you must stay until the end
                      time outlined on the Next Steps page. </span>
                    <span class="special-info" style="color: #CB2613; padding-top: 10px;"><strong>Important Note:</strong>
                      <i>Attending staff from your company will be asked to confirm their understanding of the End Time upon
                        arrival. If they cannot stay until the agreed End Time then your company will be rejected from the
                        event. </i></span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="end_time" id="end_time" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="cannot_bring"><span></span> What you CANNOT Bring <span class="text-danger">*</span>
                    <span class="special-info">Any form of hazardous and/or dangerous materials, any: paints, liquids,
                      aerosols, flammable materials / products, explosives, products emptying a naked flame / sparks, sharp
                      objects i.e. knifes, etc. If you are not sure; don't bring it in. Or, if you have any questions about
                      potential items, please ask us.</span>
                    <span class="special-info" style="color: #CB2613; padding-top: 10px;"><strong>Important Note:</strong>
                      <i>It is at the discretion of the manufacturer to allow items on-site. Please ensure you have correct
                        documentation for all items, especially relating to any customs or clearance documents. Motiv8
                        cannot be held accountable for items that are refused entry. </i></span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="cannot_bring" id="cannot_bring" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label for="event_equipment"><span></span> Event Equipment <span class="text-danger">*</span>
                    <span class="special-info">Neither Motiv8 Connect or the manufacturer can provide extension leads, so
                      you must bring extension leads if you require power. Desktops are allocated as you arrive on a first
                      come first served basis, a specific table / space cannot be pre-allocated.</span>
                    <span class="special-info" style="padding-top: 10px;">Note: plug socket adaptors are not supplied. Desk
                      sizes will vary and there is no guarantee on sizes of them as this is a working environment and
                      subject to change.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="event_equipment" id="event_equipment" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="event_space[]"><span></span> Event Space Requirement(s) <span class="text-danger">*</span>
                    <span class="special-info">Please select required from the following list.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="event_space[]" value="Standard Power Supply" type="checkbox">
                      Standard Power Supply (As before - we do not provide extension leads)
                    </label>
                    <label>
                      <input name="event_space[]" value="Exhibition Desk(s)" type="checkbox">
                      Exhibition Desk(s) (Remember your table covering)
                    </label>

                  </div>
                  <div id="equipment_requirements">
                    <div class="radio_field">
                      <label>
                        <input name="event_space[]" id="equipment_others" value="Others" type="checkbox">
                        Others
                      </label>
                    </div>

                    <div id="other_equipment_field">
                      <label for="other_equipment">Please specify</label>
                      <input type="text" id="other_equipment" name="other_equipment" class="form-control" readonly>
                      <label for="other_equipment_file">Please specify file</label>
                      <input type="file" id="other_equipment_file" name="files" class="form-control" accept="image/*"
                        disabled>
                    </div>
                  </div>

                </div>
                <script>
				  document.addEventListener("DOMContentLoaded", function () {
					const form = document.querySelector(".supplier_form");
					const checkboxes = document.querySelectorAll('input[name="event_space[]"]');
					const submitButton = document.getElementById("registerButton");

					let formSubmitted = false;

					form.addEventListener("submit", function (event) {

					  if (formSubmitted) {
						event.preventDefault();
						return false;
					  }


					  let isChecked = false;
					  checkboxes.forEach((checkbox) => {
						if (checkbox.checked) {
						  isChecked = true;
						}
					  });

					  if (!isChecked) {
						event.preventDefault();
						alert("Please select at least one Event Space Requirement.");
						return false;
					  }


					  formSubmitted = true;
					  submitButton.disabled = true;
					  submitButton.innerText = "Submitting..."; 
					});
				  });
				</script>


                <!-- Equipment Requirements Field (Initially Hidden) -->

                <div class="form-group">
                  <label for="large_items"><span></span> Is your company planning on bringing any large items, (larger than a
                    standard door) a large amount of equipment or equipment that requires a tail lift?
                    <span class="text-danger">*</span></label>
                  <span class="special-info" style="color: #CB2613; padding-top: 10px;">
                    If "Yes", please contact
                    <a href="mailto:enquiries@motiv8connect.com" style="color: blue; text-decoration: underline;">
  enquiries@motiv8connect.com
</a>

                    to discuss. If you fail to make contact regarding this, access with your chosen items or in your own
                    vehicle is likely to be denied.
                  </span>

                  <div class="radio_filed">
                    <label>
                      <input name="large_items" id="large_items" value="1" type="radio">
                      Yes (read above statement)
                    </label>
                    <label>
                      <input name="large_items" id="large_items" value="0" type="radio">
                      No
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="internet_wifi"><span></span> Internet / Wifi <span class="text-danger">*</span>
                    <span class="special-info">Internet / Wifi is NOT available at the event.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="internet_wifi" id="internet_wifi" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="attendance_numbers"><span></span> Attendance Numbers & Expectations <span class="text-danger">*</span>
                    <span class="special-info">The number of attending manufacturer staff differ at every event. No
                      guarantee can be made as to the number of staff that will attend on the day to either each desktop or
                      supporting presentations or what time of the day they will attend.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="attendance_numbers" id="attendance_numbers" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="event_briefing"><span></span> Event Briefing Requirements <span class="text-danger">*</span>
                    <span class="special-info">You are required to be onsite, setup and in the Main Event Area in time for
                      the Event Briefing on the Day. The timings are located on the Next Steps page.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="event_briefing" id="event_briefing" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="areas_access"><span></span> Areas of Access <span class="text-danger">*</span>
                    <span class="special-info">Once you are onsite you are under complete supervision by Motiv8 Connect and
                      must follow our direction. You must remain only within areas indicated by Motiv8 Connect.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="areas_access" id="areas_access" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="manufacturer_attendees"><span></span> Manufacturer Attendees <span class="text-danger">*</span>
                    <span class="special-info">Motiv8 Connect are unable to disclose the names, job titles and emails of the
                      manufacturer attendees. Generally the attendees do not carry business cards, therefore it is in your
                      interest to have a method of capturing their data on the day.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="manufacturer_attendees" id="manufacturer_attendees" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="access_requirement"><span></span> Access Requirement <span class="text-danger">*</span>
                    <span class="special-info">To gain access to the site You MUST bring a valid form of ID (Passport,
                      Driving Licence, Etc.)</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="access_requirement" id="access_requirement" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="event_hours"><span></span> Event Hours Requirement <span class="text-danger">*</span>
                    <span class="special-info">If you register for this "Attending Staff Registration", you are required to
                      be onsite for the entire event. Therefore you agree that you must and will be onsite from the morning
                      briefing through to the agreed event end time.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input name="event_hours" id="event_hours" value="1" type="radio" required>
                      I understand and agree
                    </label>
                  </div>
                </div>
				        <?php if($data['event_id']=='984' || $data['event_id']=='979'){ ?>
				        <div class="form-group">
                  <label for="customphotoid">22. Photo ID Number <span class="text-danger">*</span>
                     <span class="special-info">your Photo ID Number - this MUST match your physical ID.<br>Which you’re required to bring to access the site.</span>
                   </label>
                  <input type="text" class="form-control" id="customphotoid" value=""
                    placeholder="Passport Number or Drivers Licence Number, etc." name="customphotoid" required >
                  <small class="text-danger customphotoid"></small>
                </div>
                 <?php if(isset($_GET['ref']) && $_GET['ref']== '1ed4dd132bf32f83221f2990031daa42'){ ?>
                  <div class="form-group">
                      <label>23. Please select how you will travel to Lamborghini:</label>
                      <div class="radio_filed">
                          <div class="radio">
                              <label>
                                  <input type="radio" name="travel_method" value="Taxi" required> Taxi
                              </label>
                          </div>
                          <div class="radio">
                              <label>
                                  <input type="radio" name="travel_method" value="Vehicle"> Vehicle
                              </label>
                              <div id="vehicle-plate-div" style="padding-left: 25px; margin-top: 5px; display: none;">
                                  <input type="text" class="form-control" name="vehicle_registration_plate" placeholder="Vehicle Registration Plate">
                                  <small class="text-danger">*Required</small>
                              </div>
                          </div>
                          <div class="radio">
                              <label>
                                  <input type="radio" name="travel_method" value="Rental Vehicle">
                                  Rental Vehicle (registration unknown) - email jazz@motiv8connect.com urgently to organise.
                              </label>
                          </div>
                          <div class="radio">
                              <label>
                                  <input type="radio" name="travel_method" value="Other"> Other
                              </label>
                              <div id="other-specify-div" style="padding-left: 25px; margin-top: 5px; display: none;">
                                  <input type="text" class="form-control" name="other_travel_specify" placeholder="Please specify">
                              </div>
                          </div>
                      </div>
                  </div>
                <?php } ?>

				      <?php } ?>
                <div class="submit_btn text-right">
                  <button type="submit" class="btn btn-primary btn-lg btn-block" id="registerButton" data-toggle="modal"
                    data-target="#paypalModal">Register Now</button>
                  <noscript>
                    <style>
                      #registerButton {
                        display: none;
                      }

                      .noscript-message {
                        color: red;
                        font-weight: bold;
                        margin-top: 20px;
                        text-align: center;
                        font-size: 18px;
                      }
                    </style>
                    <div class="noscript-message">
                      JavaScript is required to submit this form. Please enable JavaScript in your browser and try again.
                    </div>
                  </noscript>
                </div>
              </div>
              <?php //} ?>
            </form>
          </div>
        </div>
      </div>
    </section>
    <?php 
} } else { ?>
    <section class="col-md-10 col-md-offset-1 main_form">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <?php
              $requestUri = $_SERVER['REQUEST_URI'];
              $hasDoubleQuestion = (strpos($requestUri, '??') !== false);
            ?>
             <?php if ($hasDoubleQuestion) { ?>
                <h1>Please Wait</h1>
                <h4>Taking you to the registration page…</h4>
            <?php } else { ?>
                <h1>Sorry!</h1>
                <h4>Event Registration is Closed</h4>
            <?php } ?>
          </div>
        </div>
      </div>
    </section>
  <?php } ?>
  <script>
    function updatePackageAmount(amount) {
      document.getElementById("hide_package_price").value = amount;
    }
  </script>
  <?php
} else {
  $redirect_url = isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "/home";
  $_SESSION['redirect_url'] = $redirect_url;

  if (!empty($_SESSION['redirect_url'])) {
    // Redirect to the stored URL
    header("Location: https://www.motiv8search.com/login?redirect=" . urlencode($_SESSION['redirect_url']));
    exit;
  }

  exit;
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector("#phone_number");
    window.intlTelInput(input, {
      initialCountry: "auto",
      geoIpLookup: function (callback) {
        fetch("https://ipapi.co/json")
          .then(res => res.json())
          .then(data => callback(data.country_code))
          .catch(() => callback("us"));
      },
      utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js"
    });
  });
</script>
<?php if (isset($_GET['alert'])): ?>
  <script>
    swal('', ' <?php echo htmlspecialchars($_GET['alert']); ?>', 'warning');
  </script>

<?php endif; ?>