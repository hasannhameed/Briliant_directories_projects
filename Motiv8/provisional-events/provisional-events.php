<!-- Select2 CSS -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" /> -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="<?php if ($pars[0] == 'provisional-events-admin-only') {
                echo 'col-md-12';
            } else {
                echo 'col-md-9';
            } ?> ">
    <div class="main_content">
        <h1 class="main_title bold">Waitlist Events</h1>
        <?php
        $sql = "SELECT post_id, user_id, event_id, post_title, post_image, post_content, post_filename, post_category, post_location, post_caption FROM data_posts WHERE data_id = 75 AND post_status = 1 AND post_caption < CURDATE() ORDER BY `data_posts`.`post_id` DESC";
        $result = mysql_query($sql);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $user['user_id'];
            $full_name = $_POST['full_name'];
            $job_title = $_POST['job_title'];
            $email_id = $_POST['email_id'];
            $phone_number = $_POST['phone_number'];
            $company_name = $_POST['company_name'];
            $showing_text = mysql_real_escape_string($_POST['showing_text']);
            $post_title = $_POST['post_title'];
            $post_id = $_POST['post_id'];
            $event_name = $_POST['event_name'];
            $event_id   = isset($_POST['event_id']) ? $_POST['event_id'] : null;
            $package    = isset($_POST['package']) ? $_POST['package'] : null;
            $abletopay  = isset($_POST['abletopay']) ? $_POST['abletopay'] : null;

            $insertQuery = "INSERT INTO preregistation_form (user_id, full_name, job_title, event_name, event_id, post_id, email_id, phone_number, company_name, showing_text, package, abletopay) VALUES ('$user_id','$full_name', '$job_title', '$event_name', ' $event_id', '$post_id', '$email_id', '$phone_number', '$company_name', '$showing_text', '$package', '$abletopay')";
            //echo $insertQuery;

            if (mysql_query($insertQuery)) {

                $w['send_first_name'] = ucfirst($user_data['first_name']) . ' ' . ucfirst($user_data['last_name']);
                $w['post_maintitle'] = $post_title;
                $emailPrepareone = prepareEmail('pre_registration', $w);
                sendEmailTemplate(
                    $w['website_email'],
					$email_id,
                    $emailPrepareone['subject'],
                    $emailPrepareone['html'],
                    $emailPrepareone['text'],
                    $emailPrepareone['priority'],
                    $w
                );

        ?>
                <script>
                    swal({
                        title: `Success!`,
                        text: `Your Enquire Submited Successfully!`,
                        type: "success"
                    });
                </script>
                <?php
            } else {
                echo "Error: ".mysql_error();
            }
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['post_category'] === 'provisional_events' || $row['post_category'] === 'provisional_credits') {
                ?>
                    <div class="row b-margin">
                        <div class="img_section nopad sm-bmargin col-sm-4">
                            <a title="<?php echo $row['post_title']; ?>" href="<?php echo (empty($row['post_image']) ? 'https://www.motiv8search.com/images/placeholder-image.png' : $row['post_image']); ?>">
                                <div class="alert-secondary btn-block text-center">
                                    <img width="400" height="174" class="search_result_image center-block" alt="<?php echo $row['post_title']; ?>" title="<?php echo $row['post_title']; ?>" src="<?php echo (empty($row['post_image']) ? 'https://www.motiv8search.com/images/placeholder-image.png' : $row['post_image']); ?>">
                                </div>
                            </a>
                        </div>
                        <div class="mid_section ex-col-8 col-sm-8">
                            <span class="h3 bold bmargin center-block" title="<?php echo $row['post_title']; ?>">
                                <?php echo $row['post_title']; ?>
                            </span>
                            <div class="clearfix"></div>
                            <div class="post-location-snippet bmargin">
                                <?php if ($row['post_caption'] != "") { ?>
                                    <span class="inline-block">
                                        <i class="fa fa-calendar"></i>
                                        <b> <?php
                                            $db_date = $row['post_caption'];
                                            $date_obj = DateTime::createFromFormat('d/m/Y', $db_date);
                                            $formatted_date = $date_obj->format('M d Y');
                                            echo $formatted_date;
                                            ?>
                                        </b>
                                    </span>
                                <?php } ?>
                            </div>
                            <?php if ($row['post_content'] != "") { ?>
                                <p class="bpad xs-nomargin xs-nopad">
                                    <?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $row['post_content']), 350) ?>
                                </p>
                            <?php
                            } ?>
                            <div class="clearfix"></div>
                            <?php
                            if ($row['post_location'] != "" || $row['post_venue'] != "") { ?>
                                <div class="post-location-snippet tmargin">
                                    <i class="fa fa-map-marker text-danger"></i>
                                    <b> <?php echo $row['post_venue']; ?></b>&nbsp;
                                    <span class="inline-block font-sm">
                                        <?php echo $row['post_location']; ?>
                                    </span>
                                </div>
                            <?php
                            } ?>
                            <div class="clearfix"></div>
                            <div class="btn_view">
                                <?php if ($row['post_category'] === 'provisional_events' || $row['post_category'] === 'provisional_credits') { ?>
                                    <?php 
                                    //echo "SELECT COUNT(post_id) AS post FROM preregistation_form WHERE event_id = ". $row['event_id']." AND post_id = ".$row['post_id']." AND user_id = ".$user['user_id'];
                                    $query = mysql_query("SELECT COUNT(post_id) AS post FROM preregistation_form WHERE event_id = ". $row['event_id']." AND post_id = ".$row['post_id']." AND user_id = ".$user['user_id']) ;
                                    if (mysql_result($query, 0, 'post') > 0) { 
                                        $showModal = false;
                                        ?>
                                        <a class="preregister_btn btn disabled text-muted">
                                            Waitlist Joined <span><i class="fa fa-angle-right" aria-hidden="true"></i></span> <br>
                                        </a>
                                    <?php }else{  $showModal = true; ?>
                                    <a class="preregister_btn" data-toggle="modal" data-target="#flipFlop<?php echo ($row['post_category'] === 'provisional_credits') ? $row['event_id'] : $row['post_id']; ?>">
                                        Join Waitlist <span><i class="fa fa-angle-right" aria-hidden="true"></i></span> <br>
                                    </a>
                                    <?php } ?>
                                    <span class="underline"></span>
                                <?php } ?>
                                <!-- Modal -->
                                <div class="modal fade" id="flipFlop<?php echo ($row['post_category'] === 'provisional_credits') ? $row['event_id'] : $row['post_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" data-backdrop="static" data-keyboard="false">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="modalLabel">Join the waitlist for the 
                                                    <?php echo $row['post_title']; ?>
                                                </h4>
                                            </div>
                                            <form action="" method="post" id="enquireForm">
                                                <input type="hidden" name="event_name" value="<?= $row['post_title'] ?>">
                                                <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="name"><span style='color:red'>*</span>Full Name</label>
                                                        <input type="text" class="form-control full_name" name="full_name" value="<?= $user_data['first_name'] . ' ' . $user_data['last_name'] ?>" placeholder="Full Name" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="jobtitle"><span style='color:red'>*</span>Job Title</label>
                                                        <input type="text" class="form-control jobtitle" name="job_title" value="" placeholder="Job Title" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="email_id"><span style='color:red'>*</span>Email</label>
                                                        <input type="email" class="form-control email" id="email_id" name="email_id" value="<?= $user_data['email'] ?>" placeholder="Email Address" required>
                                                        <small class="form-text text-muted">Please enter your email for us to contact you.</small>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="phone"><span style='color:red'>*</span>Mobile Phone Number</label>
                                                        <input type="tel" class="form-control phone_number" name="phone_number" value="" placeholder="Mobile Phone Number" required>
                                                        <small class="form-text text-muted">Include country code if applicable.</small>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="company"><span style='color:red'>*</span>Company</label>
                                                        <input type="text" class="form-control company_name" name="company_name" value="" placeholder="Company Name" required>
                                                    </div>
                                                    <?php if($row['post_category'] === 'provisional_credits'){ ?>
                                                    <div class="form-group">
                                                        <label for="event_names_select">Which Events are you planning to use the Event Credits on?</label>
                                                        <select name="event_id" class="form-control event_id" id="event_names_select" >
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
                                                        <label for="package">Please specify whether these credits are Desktop(s), SuperBooth(s) and/or Presentation(s)</label>
                                                        <input type="text" class="form-control package" name="package" value="" placeholder="I would like 2 Desktops and 1 SuperBooth">
                                                        <!-- <small class="form-text text-muted">Please enter your email for us to contact you.</small> -->
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="abletopay">When are you able to pay/register?</label>
                                                        <input type="text" class="form-control abletopay" name="abletopay" value="" placeholder="Will we be able to pay in January 2025?">
                                                        <!-- <small class="form-text text-muted">Please enter your email for us to contact you.</small> -->
                                                    </div>
                                                    <?php } else { ?>
                                                    <div class="form-group">
                                                        <label for="showing_text"> <span style='color:red'>*</span> What would you like to showcase to the manufacturer?</label>
                                                        <textarea name="showing_text" class="showing_text form-control" cols="30" rows="5" placeholder="I'd like to demonstrate this XYZ" required></textarea>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Join Waitlist</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="hr_line">
        <?php
                }
            }
        } else {
            echo "No data found.";
        }
        ?>
    </div>
</div>
<!-- Select2 -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php

    $showForm = true;
	//echo "SELECT COUNT(post_id) AS post FROM preregistation_form WHERE event_id = {$_GET['pre-register']} AND user_id = {$user['user_id']}";
    $query = mysql_query("SELECT COUNT(post_id) AS post FROM preregistation_form WHERE event_id = {$_GET['pre-register']} AND user_id = {$user['user_id']}");

    if (mysql_result($query, 0, 'post') > 0) {
        $showForm = false;
    }

    //echo "Debug: ShowModal is set to " . ($showForm ? 'true' : 'false') . "<br>";
?>
<script>
   /* const ShowModal = <?php echo json_encode($showForm); ?>;
    console.log("ShowModal value:", ShowModal); // Debug output

    $(document).ready(function () {
        const modalId = new URLSearchParams(window.location.search).get('pre-register');

        if (ShowModal === true && modalId && $('#flipFlop' + modalId).length) {
            $('#flipFlop' + modalId).modal('show');
        } else if (ShowModal === false) {
            swal("It looks like you’ve already registered!", "Thank you!", "info");
        }
    }); */
</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
	
   	$(document).ready(function () {
		const modalId = new URLSearchParams(window.location.search).get('pre-register');
		const ShowModal = <?php echo json_encode($showForm); ?>;

		console.log("ShowModal value:", ShowModal); // Debug output

		// Show modal if needed
		if (ShowModal === true && modalId && $('#flipFlop' + modalId).length) {
			$('#flipFlop' + modalId).modal('show');
		} else if (ShowModal === false) {
			swal("It looks like you’ve already Pre-registered!", "Thank you!", "info");
		}

		// Handle form submission
		$(document).on('submit', '#enquireForm', function (e) {
			e.preventDefault(); // Prevent default form submission

			const form = $(this);
			const modal = $('#flipFlop' + modalId);

			$.ajax({
				type: form.attr('method'),
				url: form.attr('action'),
				data: form.serialize(),
				success: function (response) {
					if (modalId && modal.length) {
						modal.modal('hide'); // Close modal
					}

					
					// Remove 'pre-register' from URL
					const url = new URL(window.location.href);
					url.searchParams.delete('pre-register');
					window.history.replaceState({}, document.title, url.toString());
					
					// Show success alert
					swal("Success!", "You've successfully joined the waitlist! we'll be in touch as soon as the Event Opens.", "success");
					setTimeout(function () {
                    	location.reload();
               		}, 3000);
				},
				error: function () {
					swal("Error!", "Something went wrong. Please try again later.", "error");
				},
			});
		});

		// Initialize Select2
		$('#event_names_select').select2({
			width: '100%', // Adjust width
		});
	});

</script>