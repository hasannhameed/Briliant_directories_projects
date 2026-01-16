<style>
    .coupon-wrapper{
        background-color: #d6eef5;
        text-align: center;
        padding-top: 12px;
        padding-bottom: 12px;
    }
</style>
<!-- supplier registration form -->
<?php
session_start();

if (isset($_COOKIE['userid']) && $_COOKIE['userid'] !== '') { ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous" referrerpolicy="no-referrer">
  </script>
  <?php
  $user_data = getUser($_COOKIE['userid'], $w); // Get Current User Data
  $user_full_name = $user_data['first_name'] . ' ' . $user_data['last_name'];

  function activity_log($action, $reference_table, $reference_id, $description = '')
  {
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
              (user_id, user_name, user_type, `action`, reference_table, reference_id, `description`, ip_address, user_agent, created_at)
              VALUES 
              ('$user_id', '$user_name', 'member', '$action', '$reference_table', '$reference_id', '$description', '$ip', '$agent', '$now')
            ";

    mysql_query($sql);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    // if ($_SERVER['REMOTE_ADDR'] == '49.205.169.64') {
    //     exit('Access Denied');
    //     echo "working";
    // }



    if (!empty($_POST['packages_section'])) {
      $form_id = $_POST['form_id'];
      $invoice_email = $_POST['invoice_email'];
      $invoice_currency = $_POST['invoice_currency'];
      $purchasenumber = $_POST['purchasenumber'];
      $vatnumber = $_POST['vatnumber'];
      $main_contact = $_POST['main_contact'];
      $manufacturer_product = $_POST['manufacturer_product'];
      $contactPerson = $_POST['contact_person'];
      $email = $_POST['email'];
      $event_id = $_POST['event_id'];
      $user_id = $_COOKIE['userid'];
      $phoneNumber = $_POST['phone_number'];
      $packagesSection = $_POST['packages_section'];
      $package_amount = $_POST['package_amount'];
      $promoCodeSection = $_POST['promo_code_section'];
      $discount = $_POST['discount'];
      $paymentSummary = $_POST['payment_summary'];
      $selectedAddOns = implode(', ', $_POST['add_ons']);
      $addons = $_POST['add_ons'];

      $companyName = mysql_real_escape_string($_POST['company_name']);
      $invoice_address = mysql_real_escape_string($_POST['invoice_address']);
      $department_intrest = mysql_real_escape_string($_POST['department_intrest']);
      $brief_summary = mysql_real_escape_string($_POST['brief_summary']);
      $job_title = mysql_real_escape_string($_POST['job_title']);

      $agreement_radio_one = isset($_POST['agreement_radio_one']) ? 1 : 0;
      $agreement_radio_two = isset($_POST['agreement_radio_two']) ? 1 : 0;
      $agreement_radio_three = isset($_POST['agreement_radio_three']) ? 1 : 0;
      $agreement_radio_four = isset($_POST['agreement_radio_four']) ? 1 : 0;
      $agreement_radio_five = isset($_POST['agreement_radio_five']) ? 1 : 0;
      $agreement_radio_six = isset($_POST['agreement_radio_six']) ? 1 : 0;
      $agreement_radio_seven = isset($_POST['agreement_radio_seven']) ? 1 : 0;
      $agreement_radio_eight = isset($_POST['agreement_radio_eight']) ? 1 : 0;

      $packageLimit = 0;
      if ($packagesSection === 'Desktop Package') {
        $packageLimit = 2;
      } elseif ($packagesSection === 'SuperBooth Package') {
        $packageLimit = 4;
      }

      $insertQuery = "
          INSERT INTO supplier_registration_form (
            form_id, user_id, event_id, company_name, email_invoice, invoice_currency, purchase_order_number,
            vat_number, invoice_address, main_contact, department_intrest, brief_summary, manufacturer_products,
            agreement_radio_one, agreement_radio_two, agreement_radio_three, agreement_radio_four, agreement_radio_five,
            agreement_radio_six, agreement_radio_seven, agreement_radio_eight, job_title, contact_person, email_address,
            phone_number, packages_section, package_amount, add_on, promo_code_section, discount, payment_summary, package_limit
          ) VALUES (
            '$form_id','$user_id','$event_id', '$companyName', '$invoice_email', '$invoice_currency', '$purchasenumber',
            '$vatnumber', '$invoice_address', '$main_contact', '$department_intrest', '$brief_summary', '$manufacturer_product',
            '$agreement_radio_one', '$agreement_radio_two', '$agreement_radio_three', '$agreement_radio_four', '$agreement_radio_five',
            '$agreement_radio_six', '$agreement_radio_seven', '$agreement_radio_eight', '$job_title', '$contactPerson', '$email',
            '$phoneNumber', '$packagesSection', '$package_amount', '$selectedAddOns', '$promoCodeSection', '$discount', '$paymentSummary', '$packageLimit')";

      $paymentSummaryNumber = preg_replace('/[^0-9]/', '', $paymentSummary);
      if (empty($promoCodeSection) && $paymentSummaryNumber === "0") {
        // Redirect back to the previous page with an alert message
        $message = urlencode("Uh-oh! Something went wrong. Please try again.");
        header("Location: " . $_SERVER['HTTP_REFERER'] . "&alert=" . $message);
        exit();
      }

      if (mysql_query($insertQuery)) {
        $supplier_registration_formId = mysql_insert_id();
        // Insert into activity log
        activity_log('supplier_registered', 'supplier_registration_form', $supplier_registration_formId, "Supplier registration submitted with form_id: $form_id for event_id: $event_id");

        if (!empty($_POST['add_ons'])) {
          $selectedAddOnsArray = $_POST['add_ons'];
          $selectedAddOns = array_map(function ($addon) {
            return "'" . mysql_real_escape_string($addon) . "'";
          }, $selectedAddOnsArray);
          $selectedAddOnsString = implode(', ', $selectedAddOns);
          $fetchAddOnTypeSql = "SELECT `id` FROM `add_ons` WHERE `name` IN ($selectedAddOnsString) ORDER BY `id` ASC";
          $fetchResult = mysql_query($fetchAddOnTypeSql);
          if (!$fetchResult) {
            die('Invalid query: ' . mysql_error());
          }

          $selectedAddOnIds = array();
          while ($row = mysql_fetch_assoc($fetchResult)) {
            $selectedAddOnIds[] = $row['id'];
          }

          // Step 1: Get existing stock JSON from DB
          $existingStock = [];
          $existingQuery = "SELECT add_ons_stock FROM create_application_pages WHERE id = '$form_id' AND event_id = '$event_id'";
          $result = mysql_query($existingQuery);
          if ($result && mysql_num_rows($result) > 0) {
            $row = mysql_fetch_assoc($result);
            $existingStockJson = $row['add_ons_stock'];
            $existingStock = json_decode($existingStockJson, true);
          }

          // Step 2: Initialize updated stock array with existing data
          $addons_stock = $existingStock;

          // Step 3: Update only submitted add-ons
          //$addons_stock = array();
          if (!empty($_POST['addons_stock'])) {
            foreach ($_POST['addons_stock'] as $id => $stock) {
              $id = (int)$id;
              $stock = (int)$stock;
              if (in_array($id, $selectedAddOnIds)) {
                $addons_stock[$id] = $stock;
              }
            }
          }
          $addons_stock_json = json_encode($addons_stock);
          $updatequery = "UPDATE create_application_pages SET add_ons_stock = '$addons_stock_json' WHERE id = '$form_id' AND event_id = '$event_id';";
          //echo "UPDATE create_application_pages SET add_ons_stock = '$addons_stock_json' WHERE id = '$form_id' AND event_id = '$event_id'";
          if (mysql_query($updatequery)) {
            // echo "Stock updated successfully!";
            activity_log('add_ons_stock_updated', 'create_application_pages', $form_id, "Stock updated for form_id: $form_id and event_id: $event_id with addons_stock: $addons_stock_json");
          } else {
            echo "Error updating stock: " . mysql_error();
          }
        }


        $lastusedpromo_query = mysql_query('SELECT * FROM `supplier_registration_form` ORDER BY `id` DESC LIMIT 1');
        $lastusedpromo_result = mysql_fetch_assoc($lastusedpromo_query);
        $last_promo = $lastusedpromo_result['promo_code_section'];
        $supplier_id = $lastusedpromo_result['id'];

        //Billing table database connection
        $conn = new mysqli(
          $w['whmcs_database_host'],
          $w['whmcs_database_user'],
          $w['whmcs_database_password'],
          $w['whmcs_database_name']
        );
        if (!$conn) {
          die('Failed to connect to WHMCS database');
        }

        $result = mysqli_query($conn, "SELECT `uses` FROM `tblpromotions` WHERE `code` = '$last_promo'");
        if ($result && $row = mysqli_fetch_assoc($result)) {
          $current_uses = (int)$row['uses'];
          $new_uses = $current_uses + 1;
          mysqli_query($conn, "UPDATE `tblpromotions` SET `uses` = '$new_uses' WHERE `code` = '$last_promo'");
          activity_log('promo_code_used', 'tblpromotions', $supplier_id, "Promo code used: $last_promo, new uses count: $new_uses");
        }
        $conn->close();

        // Fetch add-on labels and determine video option
        $selectedAddOnsArray = $_POST['add_ons'];
        $selectedAddOns = array_map(function ($addon) {
          return "'" . mysql_real_escape_string($addon) . "'";
        }, $selectedAddOnsArray);
        $selectedAddOnsString = implode(', ', $selectedAddOns);
        $fetchAddOnTypeSql = "SELECT `label` FROM `add_ons` WHERE `name` IN ($selectedAddOnsString) ORDER BY `id` ASC";
        $fetchAddOnTypeResult = mysql_query($fetchAddOnTypeSql);
        // $video_option = 'none';
        $video_option = isset($_POST['packages_section']) && $_POST['packages_section'] === 'SuperBooth Package' ? 'superbooth' : 'none';

        while ($row = mysql_fetch_array($fetchAddOnTypeResult)) {
          if ($row['label'] == 1) {
            $video_option = 'link';
            break;
          }
        }


        // Fetch event Start date and End date From meta data
        $getPostsQuery = mysql_fetch_assoc(mysql_query("SELECT * FROM `data_posts` WHERE post_id = '$event_id'"));
        $post_expire_date = date('Y-m-d', strtotime($getPostsQuery['post_expire_date']));
        $post_start_date = date('Y-m-d', strtotime($getPostsQuery['post_start_date']));
        $start_time = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = {$getPostsQuery['post_id']} AND `key` = 'start_time'"))['value'];
        $end_time = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = {$getPostsQuery['post_id']} AND `key` = 'end_time'"))['value'];

        // Insert live event post
        $insertEventQuery = "
           INSERT INTO live_events_posts (post_id, user_id, supplier_id, staus, video_option, event_description,
           start_date, end_date, start_time, end_time)
           VALUES ('$event_id', '$user_id', '$supplier_id', 1, '$video_option',
           '$brief_summary','$post_start_date', '$post_expire_date', '$start_time', '$end_time')";

        if (mysql_query($insertEventQuery)) {
          $Id = mysql_insert_id();
          // Insert into activity log for live event post
          activity_log('booth_created', 'live_events_posts', $Id, "Live event post(Booth) created for event_id: $event_id by user_id: $user_id");
          // Display success message and redirect
          echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
                <script>
                  Swal.fire({
                    title: 'Registration Complete!',
                    text: 'Head into your account and add content to appear on the event advertisment.',
                    icon: 'success',
                    showCancelButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Go to Account'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = 'https://www.motiv8search.com/account/add-supplier-card/view';
                    }
                  })
                </script>";

          // Send emails
          $w['even_name'] = $_POST['subheading'];
          $w['total_amt'] = $paymentSummary;
          $recipients = ['cheryl@motiv8connect.com']; //'contact@motiv8search.com',george@motiv8connect.com
          $emailPrepareOne = prepareEmail('registration-confirmation-email', $w);
          $emailPrepareTwo = prepareEmail('supplier-registration-form', $w);

          sendEmailTemplate($w['website_email'], $email, $emailPrepareOne['subject'], $emailPrepareOne['html'], $emailPrepareOne['text'], $emailPrepareOne['priority'], $w);
          sendEmailTemplate($w['website_email'], $recipients, $emailPrepareTwo['subject'], $emailPrepareTwo['html'], $emailPrepareTwo['text'], $emailPrepareTwo['priority'], $w);
        } else {
          echo "Error inserting live event post: " . mysql_error();
        }
      } else {
        echo "Error inserting registration form: " . mysql_error();
      }
    } else {
      // Redirect back to the previous page with an alert message
      $message = urlencode("Please select a package to proceed.");
      header("Location: " . $_SERVER['HTTP_REFERER'] . "&alert=" . $message);
      exit();
    }
  }
  // Fetch user data and check if the user has already submitted a registration form
  parse_str(ltrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), '?'), $p);
  $selectSql = mysql_fetch_assoc(mysql_query("SELECT * FROM create_application_pages WHERE token = '$p[ref]' GROUP BY event_id "));
  $myselectSql = mysql_query("SELECT * FROM create_application_pages WHERE event_id = '{$selectSql['event_id']}' HAVING COUNT(*) <= 30");

  if (mysql_num_rows($myselectSql) != '0' && $selectSql['status'] == 1) { ?>

    <section class="main_form col-md-10 col-md-offset-1">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <form class="supplier_form" action="" method="post">
              <div class="form_header">
                <?php
                if (isset($p[ref])) {
                  $token = $p[ref];
                  $query = "SELECT id, event_id, subheading, event_image, short_description, add_ons_id, add_ons_stock FROM create_application_pages WHERE token = '$token'";
                  $results = mysql_query($query);

                  if ($results) {
                    $data = mysql_fetch_assoc($results);
                    $form_id = $data['id'];
                    $event_id = $data['event_id'];
                    $user_id = $_COOKIE['userid'];
                    $subheading = $data['subheading'];
                    $shortDescription = $data['short_description'];
                    $selectedAddOns = $data['add_ons_id'];
                    $stockBalances = json_decode($data['add_ons_stock'], true);
                    $event_image = $data['event_image'];
                  } else {
                    echo "Error executing query";
                  }
                }

                $user = mysql_real_escape_string($user_data['user_id']);
                $event = mysql_real_escape_string($data['event_id']);
                $form = mysql_real_escape_string($data['id']);
                $double_register = mysql_query("SELECT 1 FROM supplier_registration_form WHERE user_id='$user' AND event_id='$event' AND form_id='$form' LIMIT 1");
                ?>
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
                                <h1><strong><?= $subheading ?></strong></h1>
                                <h2>Supplier Registration</h2>
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
                                <h1><strong><?= $subheading ?></strong></h1>
                                <h2>Supplier Registration</h2>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td style="border-style:none; text-align:left">
                        <?= $shortDescription ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <?php
                
                // if (mysql_num_rows($double_register)) {
                  // echo '<div style="height: 1000px;"></div>';
                  // echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
                  // <script>
                    // Swal.fire({
                      // title: 'You are already registered',
                      // text: 'You are not allowed to register this form again.',
                      // icon: 'warning',
                      // showCancelButton: false,
                      // allowOutsideClick: false,
                      // allowEscapeKey: false,
                      // confirmButtonColor: '#3085d6',
                      // confirmButtonText: 'Go to Booked Events'
                    // }).then((result) => {
                      // if (result.isConfirmed) {
                        // window.location.href = 'https://www.motiv8search.com/account/add-supplier-card/view';
                      // }
                    // })
                  // </script>";
              // } else { ?>
              <div class="form_body">
                
                <div class="create_page">
                  <input type="hidden" class="form-control" name="form_id" id="form_id" value="<?php echo $data['id'] ?>">
                  <input type="hidden" class="form-control" name="event_id" id="event_id"
                    value="<?php echo $data['event_id'] ?>">
                  <input type="hidden" class="form-control" name="user_id" id="user_id"
                    value="<?php echo $data['user_id'] ?>">
                  <input type="hidden" class="form-control" name="subheading" id="subheading"
                    value="<?php echo $data['subheading'] ?>">
                  <input type="hidden" class="form-control" name="short_description" id="short_description"
                    value="<?php echo $data['short_description'] ?>">
                </div>

                <?php if (isset($_GET['alert'])): ?>
                  <noscript>
                    <div style="color:red;font-weight:700">
                      Error: <?= htmlspecialchars($_GET['alert']); ?><br>
                      Please enable JavaScript in your browser and try again
                    </div>
                  </noscript>
                <?php endif; ?>

                <!-- Form fields -->
                <div class="form-group">
                  <label for="contact_person">Full Name <span class="text-danger">*</span></label>
                  <input name="contact_person" value="<?= $user_data['first_name'] . ' ' . $user_data['last_name']; ?>"
                    id="contact_person" class="form-control" placeholder="Contact Person Name" required>
                  <small class="text-danger contact_person"></small>
                </div>

                <div class="form-group">
                  <label for="job_title">Job Title <span class="text-danger">*</span></label>
                  <input name="job_title" id="job_title" class="form-control" placeholder="Business Development Manager" required>
                  <small class="text-danger job_title"></small>
                </div>

                <div class="form-group hide">
                  <label for="email_id">Email <span class="text-danger">*</span></label>
                  <input name="email" value="<?= $user_data['email']; ?>" id="email_id" type="hidden" class="form-control"
                    placeholder="Email" readonly>
                  <small class="text-danger email"></small>
                </div>

                <div class="form-group">
                  <label for="phone_number">Mobile Phone Number <span class="text-danger">*</span></label>
                  <input name="phone_number" value="<?= $user_data['phone_number']; ?>" id="phone_number" type="tel"
                    class="form-control" placeholder="Phone Number" autofill="off" required>
                  <small class="text-danger phone_number"></small>
                </div>

                <?php
                $myquery = "SELECT desktop_package, superbooth_package FROM create_application_pages WHERE token = '$token'";
                $myresult = mysql_query($myquery);

                if ($myresult) {
                  $row = mysql_fetch_assoc($myresult);
                  $desktopPackage = $row['desktop_package'];
                 $superboothPackage = $row['superbooth_package'];
                }
                ?>

                <div class="form-group">
                  <label>Package Selection <span class="text-danger">*</span></label><br>
                  <small class="help help-text">Please select the Package you would like to purchase.</small><br>
                  <small class="help help-text">Full Package Details: <a
                      href="https://motiv8connect.com/supplier-engagement-days-1">https://motiv8connect.com/supplier-engagement-days-1</a></small><br>
                  <small class="help help-text">Payment is via Invoice</small><br>
                  <small class="help help-text">* Prices subject to VAT</small><br><br>
					
                  <div class="radio_filed">
				  <?php if($row['desktop_package']!='0' && $row['desktop_package']!=''){ ?>
                    <label>
                      <input name="packages_section" value="Desktop Package" type="radio" required
                        onclick="updatePackageAmount(<?= $row['desktop_package']; ?>)">
                      Desktop Package: <span class="package_price"
                        data-price="<?= $row['desktop_package']; ?>">£<?= number_format(intval($row['desktop_package']), 0, '.', ','); ?>
                        GBP</span>
                    </label>
				  <?php } ?>
				   <?php if($row['superbooth_package']!='0' && $row['superbooth_package']!=''){ ?>  
                    <label>
                      <input name="packages_section" value="SuperBooth Package" type="radio" required
                        onclick="updatePackageAmount(<?= $row['superbooth_package']; ?>)">
                      SuperBooth Package: <span class="package_price"
                        data-price="<?= $row['superbooth_package']; ?>">£<?= number_format(intval($row['superbooth_package']), 0, '.', ','); ?>
                        GBP</span>
                    </label>
					  <?php } ?>
                  </div>
                  <input name="package_amount" id="hide_package_price" type="hidden" class="package_amount">
                  <small class="text-danger Package"></small>
                </div>
                <!-- Additional Add-ons, Invoice Details, Terms and Conditions -->

                <?php
                $selectQuery = "SELECT * FROM `add_ons` WHERE id IN($selectedAddOns) AND `label` = 1 ORDER BY `id` ASC;";
                $query1 = mysql_query($selectQuery);
                $result1 = mysql_num_rows($query1);
                if ($result1 > 0) { ?>
                  <div class="form-group row">
                    <div class="col-md-12"><label>Presentation Add-On</label> </div>
                    <div class="col-md-12 mb-3">
                      <small class="help help-text">- Subject to suitability and approval, please confirm with Motiv8 Connect
                        before booking this Add-on.</small><br>
                      <small class="help help-text">Contact about availability: <a
                          href="mailto:contact@motiv8connect.com">contact@motiv8connect.com</a></small>
                    </div>
                    <?php while ($row = mysql_fetch_assoc($query1)) {
                      $addonId = $row['id'];
                      $remaining_stock = isset($stockBalances[$addonId]) ? $stockBalances[$addonId] : 0;
                      $hideCheckbox = ($remaining_stock === 0);

                    ?>
                      <div class="form-check row">
                        <div class="check_div_main col-md-12">
                          <div class="check_div" <?php if ($hideCheckbox) echo 'style="display: none;"'; ?>>
                            <input class="form-check-input" type="checkbox" id="addon_<?= $row['id'] ?>"
                              data-price="<?= $row['price'] ?>" name="add_ons[]" value="<?= $row['name'] ?>">
                            <?php if ($remaining_stock > 0) { ?>
                              <input type="hidden" name="addons_stock[<?= $row['id'] ?>]" value="<?php echo $remaining_stock - 1; ?>">
                            <?php } ?>
                          </div>
                          <div class="check_div_text">
                            <label class="form-check-label" <?php if ($hideCheckbox) {
                                                              echo 'style="text-decoration: line-through; color: gray;"';
                                                            } ?>>
                              <?= $row['name'] ?>: <span
                                class="package_price">£<?= number_format(intval($row['price']), 0, '.', ',') ?> GBP</span>
                            </label>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <small
                            class=" help help-text" <?php if ($hideCheckbox) {
                                                      echo 'style="text-decoration: line-through; color: gray;"';
                                                    } ?>>
                            <?= strlen($row['description']) > 150 ? substr($row['description'], 0, 150) . '...' : $row['description'] ?></small>
                        </div>

                        <?php

                        if ($remaining_stock >= 0) { // Only show messages if stock is defined (not unlimited) 
                        ?>
                          <div class="check_div_main col-md-12">
                            <?php

                            if ($remaining_stock === 0) { ?>
                              <span style="color:red;">This Add-On is out of stock</span>
                            <?php } elseif ($remaining_stock === 1) { ?>
                              <span style="color:red;">Only One is Available</span>
                            <?php } elseif ($remaining_stock === 2) { ?>
                              <span style="color:red;">Only Two Remaining </span>
                            <?php } ?>
                          </div>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  </div>
                <?php
                }
                $selectQuery2 = "SELECT * FROM `add_ons` WHERE id IN($selectedAddOns) AND `label` = 2 ORDER BY `id` ASC;";
                $query2 = mysql_query($selectQuery2);
                $result2 = mysql_num_rows($query2);
                if ($result2 > 0) { ?>

                  <div class="form-group row">
                    <div class="col-md-12"><label>Additional Add-Ons</label></div>
                    <?php while ($row2 = mysql_fetch_assoc($query2)) {
                      /*
                        $stock = intval($row2['stock']); 
                        $balance_stock = intval($row2['balance_stock']); 
                        $remaining_stock = ($stock > 0) ? ($stock - $balance_stock) : -1; 
                        // Hide the checkbox when stock runs out (except for unlimited stock)
                        $hideCheckbox = ($remaining_stock === 0 && $stock > 0);  
                    */
                      $addonId = $row2['id'];
                      $remaining_stock = isset($stockBalances[$addonId]) ? $stockBalances[$addonId] : 0;
                      $hideCheckbox = ($remaining_stock === 0);
                    ?>
                      <div class="form-check row">
                        <div class="check_div_main col-md-12">
                          <div class="check_div" <?php if ($hideCheckbox) echo 'style="display: none;"'; ?>> <!-- Hide checkbox -->
                            <input class="form-check-input" type="checkbox" id="addon_<?= $row2['id'] ?>" data-price="<?= $row2['price'] ?>" name="add_ons[]" value="<?= $row2['name'] ?>">
                            <?php if ($remaining_stock > 0) { ?>
                              <input type="hidden" name="addons_stock[<?= $row2['id'] ?>]" value="<?php echo $remaining_stock - 1; ?>">
                            <?php } ?>

                          </div>
                          <div class="check_div_text">
                            <label class="form-check-label" for="addon_<?= $row2['id'] ?>"
                              <?php if ($hideCheckbox) {
                                echo 'style="text-decoration: line-through; color: gray;"';
                              } ?>>
                              <?= $row2['name'] ?>:
                              <span class="package_price">£<?= number_format(intval($row2['price']), 0, '.', ',') ?> GBP</span>
                            </label>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <small class="help help-text" <?php if ($hideCheckbox) {
                                                          echo 'style="text-decoration: line-through; color: gray;"';
                                                        } ?>>
                            <?/*= strlen($row2['description']) > 50 ? substr($row2['description'], 0, 50) . '...' : $row2['description'] */
							echo $row2['description']; ?>
                          </small>
                        </div>

                        <?php if ($remaining_stock >= 0) { // Only show messages if stock is defined (not unlimited) 
                          //echo $remaining_stock.'Hello'; 
                        ?>
                          <div class="check_div_main col-md-12">
                            <?php if ($remaining_stock === 0) { ?>
                              <span style="color:red;">This Add-On is out of stock</span>
                            <?php } elseif ($remaining_stock === 1) { ?>
                              <span style="color:red;">Only One is Available</span>
                            <?php } elseif ($remaining_stock === 2) { ?>
                              <span style="color:red;">Only Two Remaining</span>
                            <?php } ?>
                          </div>
                        <?php } ?>
                      </div>
                    <?php } ?>

                  </div>



                <?php } ?>
                <hr class="space_line">
                <h2><b>Invoice Details</b></h2>
                <div class="clearfix"></div>

                <div class="form-group">
                  <label>Invoice Currency <span class="text-danger">*</span></label>
                  <p>Payment will be converted on invoice if € or $ is selected</p>
                  <div class="radio_filed">
                    <label><input name="invoice_currency" value="£" type="radio" required> £</label>
                    <label><input name="invoice_currency" value="€" type="radio" required> €
                      <small class="help help-text">(€60 Admin Fee)</small>
                    </label>
                    <label><input name="invoice_currency" value="$" type="radio" required> $
                      <small class="help help-text">($60 Admin Fee)</small>
                    </label>
                  </div>
                  <small class="text-danger invoice_currency"></small>
                </div>
                <div class="promo-code-module">
                  <div class="clearfix"></div>

                  <div class="col-md-6 nolpad sm-norpad sm-bmargin promo-code-group">
                    <div class="well nomargin nobpad form-group promo-code-input">
                      <label>Have an Event Credit Code?</label> 
                      <!-- &nbsp;<span class='badge label-primary loadind hide'>loading...</span> -->
                      <div class="input-group">
                        <input name="promo_code_section" value="" placeholder="Enter code here" autocomplete="off"
                          class="form-control promo_code_section" data-fv-field="refcode" type="text" id="promo_code_section">
                        <span class="input-group-btn">
                          <button class="btn btn-success" type="button" id="coupon_code">Apply</button>
                        </span>
                      </div>
                      <small class="help-block refcode-block" data-fv-validator="remote" data-fv-for="refcode"
                        data-fv-result="NOT_VALIDATED"></small>
                    </div>
                  </div>

                  <div class="col-md-6 norpad sm-nolpad order-summ-module">
                    <div class="well nomargin" id="ordersumm">
                      <div class="ordersummarytitle">Order Summary</div>
                      <div id="ordersummary">
                        <table style="width:100%">
                          <tbody id="order-summary-body">
                            <tr class="subtotal">
                              <td class="title_td">Subtotal</td>
                              <td class="alnright" id="subtotalAmount">$0.00</td>
                            </tr>
                            <tr class="promo-discount" style="display: none;">
                              <td class="title_td">Event Credit Discount</td>
                              <td class="alnright" id="promoDiscountAmount">$0.00</td>
                              <input type="hidden" name="discount" id="discount_input" value="">
                            </tr>
                            <tr class="total">
                              <td class="title_td">Total</td>
                              <td class="alnright" id="totalAmount">$0.10</td>
                              <input type="hidden" name="payment_summary" id="payment_summary" value="">
                            </tr>
                            <tr class="admin-amount hide">
                              <td class="title_td">Admin Fee</td>
                              <td class="alnright" id="adminAmount">+ £0</td>
                              <input type="hidden" name="adminfee" id="admin_fee" value="">
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <textarea id="rawordersummary" style="display:none;"></textarea>
                  </div>
                  <div class="clearfix clearfix-lg"></div>
                  <div class="alert alert-primary" role="alert" id="promo-code-alert-message" style="display: none;">
                  </div>
                </div>

                <div class="form-group">
                  <label for="company_name">Company Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="company_name" value="<?= $user_data['company'] ?>" placeholder="Company Name"
                    name="company_name"  required>
                  <small class="text-danger company_name"></small>
                </div>

                <div class="form-group">
                  <label for="invoice_email">Email for Invoice <span class="text-danger">*</span></label>
                  <p>Please enter the email that the invoice should be sent to</p>
                  <input type="email" class="form-control" value="" placeholder="john.smith@yourcomany.com"
                    name="invoice_email" id="invoice_email" required>
                  <small class="text-danger email_invoice"></small>
                </div>

                <div class="form-group">
                  <label for="vat_id">VAT Number</label>
                  <p>(If applicable)</p>
                  <input type="text" class="form-control" value="" placeholder="VAT Number" name="vatnumber" id="vat_id">
                </div>

                <div class="form-group">
                  <label for="purchase_id">Purchase Order Number</label>
                  <p>(If applicable)</p>
                  <input type="text" class="form-control" value="" placeholder="Purchase Number" name="purchasenumber"
                    id="purchase_id">
                </div>

                <div class="form-group">
                  <label for="invoiceaddress_id">Invoice Address <span class="text-danger">*</span></label>
                  <!-- <input type="text" class="form-control" value="" placeholder="Invoice Address" name="invoice_address" id="invoiceaddress_id"> -->
                  <textarea name="invoice_address" class="form-control" id="invoiceaddress_id" placeholder="Invoice Address"
                    cols="30" rows="3" required></textarea>
                  <small class="text-danger address_invoice"></small>
                </div>

                <hr class="space_line">

                <h2><b>Supplier Information</b></h2>
                <div class="clearfix"></div>
                <div class="form-group">
                  <label for="main_contact">Main Contact <span class="text-danger">*</span></label>
                  <p>Please enter the email of the person responsible for the event</p>
                  <input type="email" class="form-control" value="" placeholder="john.smith@yourcomany.com"
                    name="main_contact" id="main_contact" required>
                  <small class="text-danger main_person"></small>
                </div>

                <div class="form-group">
                  <label for="department_intrest">Department(S) of Interest <span class="text-danger">*</span></label>
                  <p>Please enter the departments/people you're hoping to see while at the event</p>
                  <input type="text" class="form-control" value="" placeholder="Department(S) of Interest"
                    name="department_intrest" id="department_intrest" required>
                  <small class="text-danger interst_dept"></small>
                </div>

                <div class="form-group">
                  <label for="brief_summary">Promotional description of what you'll be showing at the event<span
                      class="text-danger">*</span></label>
                    <p><?php //echo $event_id; ?>Please enter a promotional description of what you'll be showing to the manufacturer at this event</p>
                    <?php if (isset($event_id) && $event_id == 993) { ?>
                    <p>What you enter here is what Audi will use to determine if they would like you to exhibit.</p>
                  <?php } ?>
                  <textarea name="brief_summary" class="form-control" id="brief_summary" cols="30" rows="3" required 
                  placeholder="We will be showcasing our XYZ, its next-generation high-performance datalogger, engineered to meet the rigorous demands of your automotive and industrial testing environments."></textarea>
                  <small class="text-danger summary_desc"></small>
                  <p class="summary-limit">Character count: <span class="descriptionCount">0</span>/200 </p>
                </div>

                <div class="form-group">
                  <label for="manufacturer_product">Manufacturer of products being shown</label>
                  <p>(if different from your company name)</p>
                  <input type="text" class="form-control" value="" placeholder="Manufacturer of products being shown"
                    name="manufacturer_product" id="manufacturer_product">
                </div>

                <hr class="space_line">

                <h2><b>Terms, Conditions & Agreements</b></h2>
                <div class="clearfix"></div>
                <div class="form-group">
                  <label for="agreement_radio_one">%%%custom_booth_term_bold%%%</label>
                  <div class="radio_filed">
                    <label>
                      <input type="checkbox" name="agreement_radio_one" value="" id="agreement_radio_one" required>
                      I confirmed / agree
                    </label>
                  </div>
                  <small class="text-danger agreement_radio_one"></small>
                </div>

                <div class="form-group">
                  <label for="agreement_radio_two">%%%custom_booth_term_bold2%%%</label>
                  <label>%%%custom_booth_term_small2%%%</label>
                  <div class="radio_filed">
                    <label>
                      <input type="checkbox" name="agreement_radio_two" value="" id="agreement_radio_two" required>
                      I confirmed / agree
                    </label>
                  </div>
                  <small class="text-danger agreement_radio_two"></small>
                </div>

                <div class="form-group">
                  <label for="agreement_radio_three">%%%custom_booth_term_bold3%%%</label>
                  <div class="radio_filed">
                    <label>
                      <input type="checkbox" name="agreement_radio_three" value="" id="agreement_radio_three" required>
                      I confirmed / agree
                    </label>
                  </div>
                  <small class="text-danger agreement_radio_three"></small>
                </div>
                <div class="form-group">
                  <label for="agreement_radio_four">%%%custom_booth_term_bold4%%%</label>

                  <div class="radio_filed">
                    <label>
                      <input type="checkbox" name="agreement_radio_four" value="" id="agreement_radio_four" required>
                      I confirmed / agree
                    </label>
                  </div>
                  <small class="text-danger agreement_radio_four"></small>
                </div>
                <div class="form-group">
                  <label for="agreement_radio_five">%%%custom_booth_term_bold5%%%</label>
                  <label>%%%custom_booth_term_small5%%% <span class="text-danger">*</span></label>
                  <div class="radio_filed">
                    <label>
                      <input type="checkbox" name="agreement_radio_five" value="" id="agreement_radio_five" required>
                      I confirmed / agree
                    </label>
                  </div>
                  <small class="text-danger agreement_radio_five"></small>
                </div>
                <div class="form-group">
                  <label for="agreement_radio_six">%%%custom_booth_term_bold6%%% here - <a
                      href="https://motiv8connect.com/conditions-of-business">https://motiv8connect.com/conditions-of-business</a>
                    <span class="text-danger">*</span> </label>
                  <div class="radio_filed">
                    <label>
                      <input type="checkbox" name="agreement_radio_six" value="" id="agreement_radio_six" required>
                      I confirmed / agree
                    </label>
                  </div>
                  <small class="text-danger agreement_radio_six"></small>
                </div>

                <div class="form-group">
                  <label for="agreement_radio_seven">%%%custom_booth_term_bold7%%%</label>
                  <label>%%%custom_booth_term_small7%%% <br>
                    <span class="text-danger">You will not appear on any event advertisement until you do this.</span>
                  </label>
                  <div class="radio_filed">
                    <label>
                      <input type="checkbox" name="agreement_radio_seven" value="" id="agreement_radio_seven" required>
                      I confirmed / agree
                    </label>
                  </div>
                  <small class="text-danger agreement_radio_seven"></small>
                </div>
                <div class="form-group">
                  <label for="agreement_radio_eight">%%%custom_booth_term_bold8%%%</label>
                  <label>%%%custom_booth_term_small8%%%</label>
                  <div class="radio_filed">
                    <label>
                      <input type="checkbox" name="agreement_radio_eight" value="" id="agreement_radio_eight" required>
                      I confirmed / agree
                    </label>
                  </div>
                  <small class="text-danger agreement_radio_eight"></small>
                </div>
                <div class="submit_btn text-right">
                  <button type="submit" class="btn btn-primary btn-lg btn-block registerButton_custom" id="registerButton" data-toggle="modal"
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
  <?php } else { ?>
    <section class="col-md-10 col-md-offset-1 main_form">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <h1>Sorry!</h1>
            <h4>Event Registration is Closed</h4>
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
  document.addEventListener("DOMContentLoaded", function() {
    const input = document.querySelector("#phone_number");
    window.intlTelInput(input, {
      initialCountry: "auto",
      geoIpLookup: function(callback) {
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

<?php 
$user_subscription = $_COOKIE['subscription_id'];
if ($user_subscription == 36) { ?>

    <script>
    document.querySelector('.supplier_form').remove();
       swal(
            "Oops - you can't register from your personal account!",
            "Please login to the main company account to register",
            "warning"
        ).then(() => {
            window.location.href = "/login";
        });

        let buttonContainer = document.querySelector('.swal-button-container');
        if (buttonContainer) {
            let newButton = document.createElement('button');
            newButton.textContent = 'Back';
            newButton.classList.add('custom-swal-button');
            newButton.style.marginLeft = 'margin-left: 20px;';
            buttonContainer.appendChild(newButton);
            newButton.onclick = function() {
                window.history.back();
            };
        }

        let swalButton = document.querySelector('.swal-button');
        if (swalButton) {
            swalButton.innerText = 'Login to Company Account';
            swalButton.onclick = function() {
                let logoutLink = document.createElement('a');
                logoutLink.href = "/logout";
                logoutLink.click();
            };
        }
        
    </script>
<?php } 
?>


<?php 
if(true){
    $token = $_COOKIE['userid'];

    $whmcs_database_host = $w['whmcs_database_host'];
    $whmcs_database_name = $w['whmcs_database_name'];
    $whmcs_database_password = $w['whmcs_database_password'];
    $whmcs_database_user = $w['whmcs_database_user'];

    // Create connection for `tblpromotions`
    $conn = new mysqli($whmcs_database_host, $whmcs_database_user, $whmcs_database_password, $whmcs_database_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Initialize the credits array
    $credits = [];

    // Check if user token is available
    if (!empty($token)) {
        $promoQuery = "SELECT code, maxuses, uses, startdate, expirationdate FROM tblpromotions WHERE notes = '$token'";
        $promoResult = mysqli_query($conn, $promoQuery);

        if ($promoResult) {
            while ($promo = mysqli_fetch_assoc($promoResult)) {
                $promoCode = $promo['code'];
                $isUsed = $promo['uses'] == $promo['maxuses']; 
        
                $promotionDetails = [
                 'code' => $promoCode,
                 'is_used' => $isUsed,
                 'start_date' => $promo['startdate'],
                 'expiration_date' => $promo['expirationdate']
                ];
        
                if ($isUsed) {
                    $postIdQuery = "SELECT event_id AS post_id FROM supplier_registration_form WHERE promo_code_section = '$promoCode'";
                    $postIdResult = mysql_query($postIdQuery);
        
                    if ($postIdResult) {
                        while ($post = mysql_fetch_assoc($postIdResult)) {
                            $postId = $post['post_id'];
        
                            $postDetailsQuery = "SELECT lep.id AS live_event_post_id, dp.post_title, dp.post_filename FROM data_posts dp LEFT JOIN live_events_posts lep ON dp.post_id = lep.post_id WHERE dp.post_id = '$postId' AND lep.user_id = '$token';";
                            //echo $postDetailsQuery;
                            $postDetailsResult = mysql_query($postDetailsQuery);
        
                            if ($postDetailsResult) {
                                while ($postDetails = mysql_fetch_assoc($postDetailsResult)) {
                                    $credits[] = array_merge($promotionDetails, [
                                        'live_event_post_id' => $postDetails['live_event_post_id'],
                                        'post_id' => $postId,
                                        'post_title' => $postDetails['post_title'],
                                        'post_link' => $postDetails['post_filename']
                                    ]);
                                }
                            }
                        }
                    }
                } else {
                    // If the credit is not used, add only promotion details
                    $credits[] = $promotionDetails;
                }
            }
        }
        // echo 'i am working';
        // print_r($credits);

        // Find the first *unused* code from the $credits array
        $firstCode = null;
        $used = null;

        foreach ($credits as $credit) {
            if (isset($credit['is_used']) && $credit['is_used'] != 1) {
                $firstCode = $credit['code'];
                $used = $credit['is_used'];
                break; // Stop after finding the first unused code
            }
        }

        

    }
}
?>
<script>
document.addEventListener("DOMContentLoaded", function () {
  let coupan = '<?php echo $firstCode ?>'; 
   let coupanused = '<?php echo $used  ?>';
    if(!coupan) return;
   console.log('coupanused',coupanused);
  console.log('coupan', coupan);

  const refcodeBlock = document.querySelector('.help-block.refcode-block');

  if (refcodeBlock && coupan) {

    const wrapperDiv = document.createElement('div');
    wrapperDiv.className = 'coupon-wrapper';
    wrapperDiv.style.marginTop = '8px';


    const couponText = document.createElement('span');
    couponText.innerHTML = 'You have an available credit: <span style="color: red;background: white;padding: 5px; padding-bottom: 5px; */">' + coupan + '</span>';
    couponText.style.color = '#687788';
    couponText.style.marginRight = '10px';
    couponText.style.fontWeight = 'bold';


    const useButton = document.createElement('button');
    useButton.textContent = 'Use it';
    useButton.className = 'btn btn-sm btn-primary use_btn';
    useButton.type = "button";
    useButton.style.padding = '3px 8px';
    useButton.style.fontSize = '12px';
    useButton.addEventListener('click', function () {
      const inputField = document.getElementById('promo_code_section');
      if (inputField) {
        inputField.value = coupan;
        inputField.focus();
      }
    });

   
    wrapperDiv.appendChild(couponText);
    wrapperDiv.appendChild(useButton);


    refcodeBlock.parentNode.insertBefore(wrapperDiv, refcodeBlock.nextSibling);
  }
});
</script>

<script>
    let description = document.getElementById('brief_summary');
    let dCount = document.querySelector('.descriptionCount');
    description.addEventListener('input',function(e){
        let length = e.target.value.length;
        let value = e.target.value;
        
        if(length >= 200){
           e.target.value = value.slice(0, 199); 
        }
        dCount.textContent = length>200?200:length;
    })
</script>


<script>
  let submit_btn = document.querySelector('.submit_btn');
  submit_btn.addEventListener("click", function(e) {

    if (!e.target.classList.contains('registerButton_custom')) return;

    let coupon_wrapper = document.querySelector('.coupon-wrapper');
    let promo_code_section = document.querySelector(".promo_code_section");

    if (coupon_wrapper && promo_code_section && promo_code_section.value.trim() === "") {

        let ask = confirm("You have an unused coupon. Would you like to use it?");

        if (ask) {
            e.preventDefault();
            promo_code_section.focus();
            return;
        }

        
    }
});

</script>