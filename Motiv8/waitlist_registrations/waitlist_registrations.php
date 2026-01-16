    <?php

    // ===============================
    // Decrypt post_id from URL param
    // ===============================

    //echo 'working';
    //echo $_SERVER["REQUEST_URI"];


    session_start();

    if (!isset($_COOKIE['userid']) || $_COOKIE['userid'] === '') {

        $redirect_url = !empty($_SERVER['REQUEST_URI'])
            ? $_SERVER['REQUEST_URI']
            : '/home';

        $_SESSION['redirect_url'] = $redirect_url;

        header(
            "Location: https://www.motiv8search.com/login?redirect=" .
            urlencode($_SESSION['redirect_url'])
        );
        exit();
    }





    $postId = 0; // default
    $event_name = '';
    $post_title = '';

    if (!empty($_GET['pre-register'])) {
        $secretKey = "MY_SECRET_KEY"; // must match your encrypt script
        $iv        = substr(hash('sha256', $secretKey), 0, 16);
        $decoded   = base64_decode($_GET['pre-register']);
        $postId    = (int) openssl_decrypt($decoded, 'AES-256-CBC', $secretKey, 0, $iv);

        if ($postId <= 0) {
            die("Invalid or tampered link.");
        }
    }

    // ===============================
    // Fetch event details
    // ===============================

    if ($postId > 0) {
        $eventRes = mysql_query("SELECT post_title FROM data_posts WHERE post_id = {$postId} LIMIT 1");
        if ($eventRes && ($eventRow = mysql_fetch_assoc($eventRes))) {
            $post_title = $eventRow['post_title'];
            $event_name = $eventRow['post_title']; // you can change this if needed
        }
    }

    // ===============================
    // Handle form submit
    // ===============================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $postId > 0) {
        $user_id      = isset($user['user_id']) ? (int)$user['user_id'] : (int)$_COOKIE['userid'];
        $full_name    = mysql_real_escape_string($_POST['full_name']);
        $job_title    = mysql_real_escape_string($_POST['job_title']);
        $email_id     = mysql_real_escape_string($_POST['email_id']);
        $phone_number = mysql_real_escape_string($_POST['phone_number']);
        $company_name = mysql_real_escape_string($_POST['company_name']);
        $showing_text = mysql_real_escape_string($_POST['showing_text']);

        $insertQuery = "
            INSERT INTO preregistation_form 
                (user_id, full_name, job_title, event_name, event_id, post_id, email_id, phone_number, company_name, showing_text) 
            VALUES 
                ('$user_id', '$full_name', '$job_title', '$event_name', '$postId', '$postId', '$email_id', '$phone_number', '$company_name', '$showing_text')
        ";

        if (mysql_query($insertQuery)) {
            echo '<div class="alert alert-success text-center"> You have successfully joined the waitlist!</div>';

            if(true){

            // ===============================
            // Handle Email
            // ===============================

                $w['eventname']   		= $event_name;
                $w['staffname']   		= $full_name;
                $w['suppliercompany']	= $company_name;
                $w['deadlinedate']  	= $mailDate;
                
                $emailPrepareOne  = prepareEmail('waiting_list_invitation', $w);

                
                // Send email
                sendEmailTemplate($w['website_email'], $email_id, $emailPrepareOne['subject'], $emailPrepareOne['html'], $emailPrepareOne['text'], $emailPrepareOne['priority'], $w);
            }
        } else {
            echo '<div class="alert alert-danger text-center"> Error: ' . mysql_error() . '</div>';
        }

        exit; // important: stop full page output
    }


    ?>

    <style>
        .alert-success {
            /* color: #3c763d !important;
        background-color: #dff0d8;
        border-color: #d6e9c6; */
        }
        .form_header {
    text-align: center;
    padding-bottom: 20px;
}
.main_form {
    padding: 30px 30px;
}
    </style>

    <?php if ($_GET['pre-register'] != '') { ?>
        <div class="" id="waitlistModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content main_form">
                    <div class="form_header" bis_skin_checked="1"> 
                        <h1>Join the waitlist for <?= htmlspecialchars($post_title) ?></h1>
                        <!-- <p></p>
                        <p>This registration is for 3 Desktop Packages at Supplier Engagement Days.</p>
                        <p>1 Event Credit = 1 Deskop Package</p>
                        <p></p> -->
                    </div>
                    <!-- <div class="modal-header">
                        <h4 class="modal-title"></h4>
                    </div> -->
                    <form action="" method="post" class="enquireForm " id="enquireForm<?= $postId ?>">
                        <input type="hidden" name="event_name" value="<?= htmlspecialchars($event_name) ?>">
                        <input type="hidden" name="post_id" value="<?= $postId ?>">

                        <div class="modal-body">
                            <div class="form-group">
                                <label><span style="color:red">*</span> Full Name</label>
                                <input type="text" class="form-control" name="full_name" id="full_name" required>
                            </div>
                            <div class="form-group">
                                <label><span style="color:red">*</span> Job Title</label>
                                <input type="text" class="form-control" name="job_title" id="job_title" required>
                            </div>
                            <div class="form-group">
                                <label><span style="color:red">*</span> Email</label>
                                <input type="email" class="form-control" name="email_id" id="email_id" required>
                            </div>
                            <div class="form-group">
                                <label><span style="color:red">*</span> Mobile</label>
                                <input type="tel" class="form-control" name="phone_number" id="phone_number" required>
                            </div>
                            <div class="form-group">
                                <label><span style="color:red">*</span> Company</label>
                                <input type="text" class="form-control" name="company_name" id="company_name" required>
                            </div>
                            <div class="form-group">
                                <label><span style="color:red">*</span> What would you like to showcase?</label>
                                <textarea class="form-control" name="showing_text" id="showing_text" required></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-block join">Join Waitlist</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } else { ?>
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
                            <h4>No event selected for waitlist registration.!</h4>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector(".enquireForm");

            if (form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    let join = document.getElementsByClassName('join');
                    join.textContent = 'Joining Waitlist';

                    let fields = ["full_name", "job_title", "email_id", "phone_number", "company_name", "showing_text"];
                    for (let id of fields) {
                        if (document.getElementById(id).value.trim() === "") {
                            swal("Missing Information", "Please fill all the details.", "warning");
                            return;
                        }
                    }

                    document.querySelector("#waitlistModal .join").disabled = true;

                    let formData = new FormData(form);

                    fetch(form.action, {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.text())
                        .then(html => {

                            document.querySelector("#waitlistModal .modal-body").insertAdjacentHTML("beforeend", html);
                            

                            swal("Success!", "Waitlist Joined!", "success");

                            document.addEventListener("click", function(e) {
                                if (e.target && e.target.classList.contains("swal2-confirm") && e.target.classList.contains("swal2-styled")) {
                                    let sub_id = '<?php echo $_COOKIE['subscription_id']; ?>';
                                    if (sub_id == 33) {
                                        window.location.href = "/account/add-supplier-card/view";
                                    } else {
                                        window.location.href = "/account/home";
                                    }
                                }
                            });

                        })
                        .catch(err => {
                            swal("Error!", "Request failed.\n\n(" + err.message + ")", "error");
                        });
                });
            }
        });
    </script>
    <script>
        (function () {
            var url = window.location.href;

            if (url.indexOf('??') !== -1) {
                var cleanUrl = url.replace('??', '?');
                window.location.replace(cleanUrl);
            }
        })();
    </script>
