<?php
session_start();
// Check if the 'userid' cookie is set and not empty
if (isset($_COOKIE['userid']) && $_COOKIE['userid'] !== '') { ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $form_id = $_POST['form_id'];
        $companyName = $_POST['company_name'];
        $invoice_email = $_POST['invoice_email'];
        $invoice_currency = $_POST['invoice_currency'];
        $purchasenumber = $_POST['purchasenumber'];
        $vatnumber = $_POST['vatnumber'];
        $invoice_address = $_POST['invoice_address'];
        $main_contact = $_POST['main_contact'];
        $department_intrest = $_POST['department_intrest'];
        $brief_summary = $_POST['brief_summary'];
        $manufacturer_product = $_POST['manufacturer_product'];
        $agreement_radio_one = isset($_POST['agreement_radio_one']) ? 1 : 0;
        $agreement_radio_two = isset($_POST['agreement_radio_two']) ? 1 : 0;
        $agreement_radio_three = isset($_POST['agreement_radio_three']) ? 1 : 0;
        $agreement_radio_four = isset($_POST['agreement_radio_four']) ? 1 : 0;
        $agreement_radio_five = isset($_POST['agreement_radio_five']) ? 1 : 0;
        $agreement_radio_six = isset($_POST['agreement_radio_six']) ? 1 : 0;
        $agreement_radio_seven = isset($_POST['agreement_radio_seven']) ? 1 : 0;
        $agreement_radio_eight = isset($_POST['agreement_radio_eight']) ? 1 : 0;
        $job_title = $_POST['job_title'];
        $contactPerson = $_POST['contact_person'];
        $email = $_POST['email'];
        $event_id = $_POST['event_id'];
        $user_id = $_COOKIE['userid'];
        $phoneNumber = $_POST['phone_number'];
        $packagesSection = $_POST['packages_section'];
        $package_amount = $_POST['package_amount'];
        $selectedAddOns = implode(', ', $_POST['add_ons']);
        //$totalAmount = $_POST['total_amount'];
        $promoCodeSection = $_POST['promo_code_section'];
        $discount = $_POST['discount'];
        $paymentSummary = $_POST['payment_summary'];

        $insertQuery = "INSERT INTO supplier_registration_form (form_id, user_id, event_id, company_name, email_invoice, invoice_currency, purchase_order_number, vat_number, invoice_address, main_contact, department_intrest, brief_summary, manufacturer_products, agreement_radio_one, agreement_radio_two, agreement_radio_three, agreement_radio_four, agreement_radio_five, agreement_radio_six, agreement_radio_seven, agreement_radio_eight, job_title, contact_person, email_address, phone_number, packages_section, package_amount, add_on, promo_code_section, discount, payment_summary)
    VALUES ('$form_id','$user_id','$event_id', '$companyName', '$invoice_email', '$invoice_currency', '$purchasenumber', '$vatnumber', '$invoice_address', '$main_contact', '$department_intrest', '$brief_summary', '$manufacturer_product', '$agreement_radio_one', '$agreement_radio_two', '$agreement_radio_three', '$agreement_radio_four', '$agreement_radio_five', '$agreement_radio_six', '$agreement_radio_seven', '$agreement_radio_eight', '$job_title', '$contactPerson', '$email', '$phoneNumber', '$packagesSection', '$package_amount', '$selectedAddOns', '$promoCodeSection', '$discount', '$paymentSummary')";

        if (mysql_query($insertQuery) === TRUE) {
            //echo "Data inserted successfully";

            // $w['send_first_name'] = ucfirst($user_data['first_name']) . ' ' . ucfirst($user_data['last_name']);
            // $w['post_maintitle'] = $post_title;
            // $w['post_modalmessage'] = $enquireMessage;
            $emailPrepareone = prepareEmail('supplier-registration-form', $w);
            sendEmailTemplate(
                $w['website_email'],
                $w['website_email'],
                $emailPrepareone['subject'],
                $emailPrepareone['html'],
                $emailPrepareone['text'],
                $emailPrepareone['priority'],
                $w
            );

    ?>
            <!-- <script>
                swal({
                    title: `Good job!`,
                    text: `Registration from has been submitted successfully!`,
                    type: "success"
                });
            </script> -->
        <?php
        } else {
            echo "Error: ";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event_id = $_POST['event_id'];
            $user_id = $_COOKIE['userid'];
            $generate = $_POST["submit_coupon_btn"];
            $status = 1;
            $subheading = $_POST['subheading'];
            $no_of_coupon = $_SESSION['superbooth_package'];
            // print_r($no_of_coupon);
            // exit();
            $shortDescription = $_POST['short_description'];
            $video_option = isset($_POST['packages_section']) && $_POST['packages_section'] === 'SuperBooth Package' ? 'link' : 'none';

            if ($generate == 1) {
                $cu = 'cu=active&token=' . $_POST['token'] . '&n=' . $no_of_coupon;
            } else {
                $cu = '';
            }

            /*$insertQuery = "INSERT INTO live_events_posts (post_id, user_id, staus, event_name, event_description, video_option)
                    VALUES ('$event_id', '$user_id', '$status', '$subheading', '$shortDescription', '$video_option')";

        if (mysql_query($insertQuery)) {
            $Id = mysql_insert_id();
           
			
        } else {
            echo "Error insert:";
        }*/

            //header("Location: https://www.motiv8search.com/thank-you?" . $cu . "&id=$Id");

            //Billing table database connection
            'host: ' . $whmcs_database_host = $w['whmcs_database_host'];
            'DBName: ' . $whmcs_database_name = $w['whmcs_database_name'];
            'Password: ' . $whmcs_database_password = $w['whmcs_database_password'];
            'User: ' . $whmcs_database_user = $w['whmcs_database_user'];
            $conn = new mysqli($whmcs_database_host, $whmcs_database_user, $whmcs_database_password, $whmcs_database_name);
            if ($conn) {
                // echo 'Connected';
            } else {
                echo 'Not connected';
            }
            //End of Billing table database connection


            function getName($n)
            {
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';

                for ($i = 0; $i < $n; $i++) {
                    $index = rand(0, strlen($characters) - 1);
                    $randomString .= $characters[$index];
                }
                return $randomString;
            }

            //$generate = 1;
            $tblpromoinsert = '';
            if ($generate == '1') {

                for ($i = 0; $i < $no_of_coupon; $i++) {
                    $token = $_COOKIE['userid'];
                    $n = 10;
                    $strname = getName($n);
                    $startdate = date('Y-m-d'); //for database
                    $newstdate = date('M-d-Y'); // for users or send email
                    $expiredate = date('Y-m-d', strtotime($startdate . ' + 366 days')); //for database
                    $newexpdate = date("M-d-Y", strtotime($expiredate)); // for users or send email

                    // Perform the insertion
                    $tblpromoinsert = mysqli_query($conn, "insert into `tblpromotions` (code, startdate, expirationdate,  type, value, notes, maxuses) values ('$strname', '$startdate', '$expiredate', 'Percentage', '100', '$token', '1')");
                }
                if ($tblpromoinsert) {
                    //echo "Insertion successful for coupon $i.<br>";

                    $tblpromoquery = mysqli_query($conn, "SELECT * FROM `tblpromotions` WHERE tblpromotions.notes='$token' ORDER BY `id` DESC LIMIT $no_of_coupon");
                    $promo_codes = array();
                    while ($tblpromofetch = mysqli_fetch_assoc($tblpromoquery)) {
                        $promo_codes[] = $tblpromofetch['code'];
                    }
                    $promo_code = implode('<br> ', $promo_codes);
                    //echo $promo_code;
                    // send email here
                    $w['promo_code'] = $promo_code;
                    $emailPrepareone = prepareEmail('Admin-Pre-Register', $w);
                    sendEmailTemplate(
                        $w['website_email'],
                        $email,
                        $emailPrepareone['subject'],
                        $emailPrepareone['html'],
                        $emailPrepareone['text'],
                        $emailPrepareone['priority'],
                        $w
                    );
                } else {
                    echo "Insertion failed for coupon $i: " . mysqli_error($conn) . "<br>";
                }
                //}  

                ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                <script>
                    Swal.fire({
                    title: 'Registration Complete!',
                    text: 'Head into your account to view your Event Credits Codes.',
                    icon : 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Go to Account'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'https://www.motiv8search.com/account/my-promo'; 
                    }
                    })
                </script>
                <?php

            }
        }
    };

    $user_data = getUser($_COOKIE['userid'], $w);
    //print_r($user_data);

    //$selectSql = mysql_query("SELECT token FROM create_application_pages where token = '$_GET[ref]'");
    $selectSql = "SELECT * FROM create_application_pages WHERE  token = '$_GET[ref]' GROUP BY event_id HAVING COUNT(*) <= 30";
    $resultPromo = mysql_num_rows($selectSql);
    $queryPromo = mysql_query($selectSql);
    $rowPromo = mysql_fetch_assoc($queryPromo);
    // echo $selectSql . "<br>";
    // echo $resultPromo; 

if ($rowPromo['status'] == 1) { ?>
        <section class="main_form">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <form class="supplier_form" action="" method="post">
                            <div class="form_header">
                                <!-- <h2 class="form_title text-center">Supplier Registration</h2> -->
                                <?php
                                if (isset($_GET['ref'])) {
                                   $token = $_GET['ref'];

                                    $query = "SELECT id, event_id, subheading, short_description FROM create_application_pages WHERE token = '$token'";

                                    $results = mysql_query($query);

                                    if ($results) {
                                        $data = mysql_fetch_assoc($results);
                                        $form_id = $data['id'];
                                        $event_id = $data['event_id'];
                                        $user_id = $_COOKIE['userid'];
                                        $subheading = $data['subheading'];
                                        $shortDescription = $data['short_description'];

                                        //echo "<p>$form_id</p>";
                                        //echo "<p>$event_id</p>";
                                        echo "<h1>$subheading</h1>";
                                        echo "<p>$shortDescription</p>";
                                    } else {
                                        echo "Error executing query";
                                    }
                                }

                                ?>
                            </div>
                            <div class="create_page">
                                <input type="hidden" class="form-control" name="form_id" id="form_id" value="<?php echo $data['id'] ?>">
                                <input type="hidden" class="form-control" name="event_id" id="event_id" value="<?php echo $data['event_id'] ?>">
                                <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo $data['user_id'] ?>">
                                <input type="hidden" class="form-control" name="subheading" id="subheading" value="<?php echo $data['subheading'] ?>">
                                <input type="hidden" class="form-control" name="short_description" id="short_description" value="<?php echo $data['short_description'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="contact_person">Full Name<span class='required'> *</span></label>
                                <input type="text" class="form-control" value="<?= $user_data['first_name'] . ' ' . $user_data['last_name'] ?>" placeholder="Contact Person Name" name="contact_person" id="contact_person" required>
                                <small class="text-danger contact_person"></small>
                            </div>

                            <div class="form-group">
                                <label for="job_title">Job Title<span class='required'> *</span></label>
                                <input type="text" class="form-control" id="job_title" value="" placeholder="Business Development Manager" name="job_title" required>
                                <small class="text-danger job_title"></small>
                            </div>

                            <div class="form-group">
                                <label for="email">Email<span class='required'> *</span></label>
                                <input type="email" class="form-control" value="<?= $user_data['email'] ?>" placeholder="Email" name="email" id="email_id" required>
                                <small class="text-danger email"></small>
                            </div>

                            <!--<div class="form-group">
                            <label for="phone_number">Mobile Phone Number<span class='required'> *</span></label>
                            <input type="tel" class="form-control" value="<?= $user_data['phone_number'] ?>"
                                placeholder="Phone Number" name="phone_number" pattern="[0-9]{10}" id="phone_number">
                                <span><small>Please enter a 10-digit phone number. This field should contain only numerical digits (0-9) and must have exactly 10 digits.</small></span>
                            <small class="text-danger phone_number"></small>
                        </div>-->
                            <div class="form-group">
                                <label for="phone_number">Mobile Phone Number<span class='required'> *</span></label>
                                <input type="tel" class="form-control" value="<?= $user_data['phone_number'] ?>" placeholder="Phone Number" name="phone_number" id="phone_number" required>
                                <small class="text-danger phone_number"></small>
                            </div>


                            <?php
                            $myquery = "SELECT desktop_package, superbooth_package, actual_price, discount FROM create_application_pages WHERE token = '$token'";
                            $myresult = mysql_query($myquery);

                            if ($myresult) {
                                $row = mysql_fetch_assoc($myresult);
                                $desktopPackage = $row['desktop_package'];
                                $superboothPackage = $row['superbooth_package'];
                                $actual_price = $row['actual_price'];
                                $discount = $row['discount'];
                                  $_SESSION['superbooth_package'] = $superboothPackage;
                            }

                            ?>

                            <div class="form-group">
                                <label for="packages_section">Packages Details<span class='required'> *</span></label>
                                <div class="radio_filed">
                                <?php if($superboothPackage != 0): ?>
                                    <p class="small"><?= $superboothPackage ?>x Event Credits</p>
                                <?php endif; ?>
                                    <p class="small">£ <?php echo number_format($row['actual_price']); ?> GBP</p>
                                    <label>
                                        <input type="radio" name="packages_section" value="Desktop Package" data-price="<?php echo $row['desktop_package']; ?>" onclick="updatePackageAmount(<?php echo $row['desktop_package']; ?>)" checked>
                                        <?= $discount ?>% Discounted Price: <span class="package_price">£
                                            <?php echo number_format($row['desktop_package']); ?> GBP
                                        </span>
                                    </label>
                                    <!-- <label>
                                    <input type="radio" name="packages_section" value="SuperBooth Package"
                                        onclick="updatePackageAmount(<?php echo $row['superbooth_package']; ?>)">
                                    SuperBooth Package <span class="package_price">£
                                        <?php echo number_format($row['superbooth_package']); ?>
                                    </span>
                                </label> -->
                                </div>
                                <input type="hidden" name="package_amount" id="hide_package_price" class="package_amount" value="">
                                <small class="text-danger Package"></small>
                            </div>

                            <?php
                            $selectQuery = "SELECT * FROM `add_ons` ORDER BY `id` ASC";
                            $result = mysql_query($selectQuery);
                            ?>
                            <div class="form-group addon_input hidden">
                                <label for="add-on">Presentation Add-On</label>
                                <?php
                                if ($result) {
                                    echo '<table class="addon_table">';
                                    while ($row = mysql_fetch_assoc($result)) {

                                        echo '<tr class="addon_row" data-addonid="' . $row['id'] . '">';
                                        echo '<td class="check_box"><label>';
                                        echo '<input type="checkbox" name="add_ons[]" value="' . $row['name'] . '" id="addon_' . $row['id'] . '" data-price="' . $row['price'] . '">';
                                        echo '<span class="addon_name"><strong>' . $row['name'] . '</strong><br>' . substr($row['description'], 0, 200) . '</span>';
                                        echo '</label></td>';
                                        echo '<td class="addon_price">' . '£' . $row['price'] . '</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                } else {
                                    echo 'Error fetching data';
                                }
                                ?>
                            </div>

                            <hr class="space_line">
                            <!-- --------- Update data -------------------- -->
                            <h2><b>Invoice Details</b></h2>
                            <br>
                            <div class="form-group tmargin">
                                <label for="invoice_currency">Invoice Currency <span class="text-danger">*</span></label>
                                <p>Payment will be converted on invoice if € or $ is selected</p>
                                <div class="radio_filed">
                                    <label>
                                        <input type="radio" name="invoice_currency" value="£" required>
                                        £
                                    </label>
                                    <label>
                                        <input type="radio" name="invoice_currency" value="€">
                                        € &nbsp;&nbsp; <small class="help help-text">(€60 Admin Fee)</small>
                                    </label>
                                    <label>
                                        <input type="radio" name="invoice_currency" value="$">
                                        $ &nbsp;&nbsp; <small class="help help-text">($60 Admin Fee)</small>
                                    </label>
                                </div>
                                <small class="text-danger invoice_currency"></small>
                            </div>
                            <div class="promo-code-module">
                                <div class="clearfix"></div>
                                <div class="col-md-6 norpad nolpad order-summ-module">
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
                            <!-- --------- Update data -------------------- -->

                            <div class="form-group">
                                <label for="company">Company Name<span class='required'> *</span></label>
                                <input type="text" class="form-control" id="company_name" value="" placeholder="Company Name" name="company_name" <?= $user_data['company'] ?> required>
                                <small class="text-danger company_name"></small>
                            </div>

                            <div class="form-group">
                                <label for="email">Email for Invoice<span class='required'> *</span></label>
                                <p>Pleae enter the email that the invoice should be sent to</p>
                                <input type="email" class="form-control" value="" placeholder="Email for Invoice" name="invoice_email" id="invoice_email" required>
                                <small class="text-danger email_invoice"></small>
                            </div>

                            <div class="form-group">
                                <label for="vatnumber">VAT Number</label>
                                <p>(If applicable)</p>
                                <input type="text" class="form-control" value="" placeholder="VAT Number" name="vatnumber" id="vat_id">
                            </div>

                            <div class="form-group">
                                <label for="purchasenumber">Purchase Order Number</label>
                                <p>(If applicable)</p>
                                <input type="text" class="form-control" value="" placeholder="Purchase Number" name="purchasenumber" id="purchase_id">
                            </div>

                            <div class="form-group">
                                <label for="address">Invoice Address<span class='required'> *</span></label>
                                <!--<input type="text" class="form-control" value="" placeholder="Invoice Address" name="invoice_address" id="invoiceaddress_id">-->
								 <textarea name="invoice_address" class="form-control" id="invoiceaddress_id" placeholder="Invoice Address" cols="30" rows="3" required></textarea>
                                <small class="text-danger address_invoice"></small>
                            </div>
                            <div class="submit_btn text-right">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="superbooth_package" value="<?= $superboothPackage ?>">

                                <button type="submit" class="btn btn-primary btn-lg btn-block" id="registerButton" data-toggle="modal" data-target="#paypalModal" name="submit_coupon_btn" value="1">Register Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"].addon_checkbox');

                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('click', function() {
                        const row = this.closest('.addon_row');
                        toggleRow(row);
                    });
                });
            });

            function toggleRow(row) {
                const checkbox = row.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            }

            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    <?php
    } else {
        echo '<div style="text-align: center; font-size: 22px;">Page not available/Registration limit has been reached for this event</div>';
    }

    ?>

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
    const input = document.querySelector("#phone_number");
    window.intlTelInput(input, {
        initialCountry: "auto",
        geoIpLookup: callback => {
            fetch("https://ipapi.co/json")
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("us"));
        },
        utilsScript: "/intl-tel-input/js/utils.js?1695806485509" /* just for formatting/placeholders etc */
    });
</script>