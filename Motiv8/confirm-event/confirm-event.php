<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="<?php if($pars[0]=='confirmed-events-admin-only'){ echo 'col-md-12';}else{ echo 'col-md-9';} ?> ">
    <div class="main_content">
        <h1 class="main_title bold">Scheduled Events</h1>
        <?php
        $sql = "SELECT post_id, post_title, post_image, post_content, post_filename, post_category, post_location, post_caption, post_expire_date, post_token FROM data_posts WHERE data_id = 75 AND post_status = 1 AND STR_TO_DATE(post_caption, '%d/%m/%Y') > CURDATE() ORDER BY STR_TO_DATE(post_caption, '%d/%m/%Y') ASC;";
//echo $sql;
        $result = mysql_query($sql);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $users_id = $_POST['user_id'];
            $post_id = $_POST['post_id'];
            $post_title = $_POST['post_title'];
            $enquireMessage = $_POST['help_text'];
            $post_token = $_POST['post_token'];

            $insertQuery = "INSERT INTO event_enquire (user_id, post_id, post_title, enquire_message, post_token) VALUES ('$users_id', '$post_id', '$post_title', '$enquireMessage', '$post_token')";
            //echo "INSERT INTO event_enquire (user_id, post_id, post_title, enquire_message) VALUES ('$users_id', '$post_id', '$post_title', '$enquireMessage')";

            if (mysql_query($insertQuery)) {
                //echo "Enquire sent successfully!";
                //$w['send_first_name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
                $w['send_first_name'] = ucfirst($user_data['first_name']) . ' ' . ucfirst($user_data['last_name']);
                $w['post_maintitle'] = $post_title;
                $w['post_modalmessage'] = $enquireMessage;
                $emailPrepareone = prepareEmail('inquiry', $w);
                sendEmailTemplate(
                    $user['email'],
                    $w['website_email'],
                    $emailPrepareone['subject'],
                    $emailPrepareone['html'],
                    $emailPrepareone['text'],
                    $emailPrepareone['priority'],
                    $w
                );

        ?>
                <script>
                    swal({
						icon: 'success',
                        text: `Your enquiry has been sent!`,
                        type: "success"
                    });
                </script>
                <?php
            } else {
                //echo "Error";
            }
        }
       // echo $w['post_modalmessage'];
        // print_r($user_data);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['post_category'] === 'confirmed_events') {
                ?>
                    <div class="row b-margin">
                        <div class="img_section nopad sm-bmargin col-sm-4">
                            <a title="Pinot Days Wine Festival" href="/events/pinot-days-wine-festival">
                                <div class="alert-secondary btn-block text-center">
                                    <img width="400" height="174" class="search_result_image center-block" alt="<?php echo $row['post_title']; ?>" title="<?php echo $row['post_title']; ?>" src="<?php echo (empty($row['post_image']) ? 'https://www.motiv8search.com/images/placeholder-image.png' : $row['post_image']); ?>">
                                </div>
                            </a>
                        </div>
                        <div class="mid_section ex-col-8 col-sm-8">
                            <span class="h3 bold bmargin center-block" title="Pinot Days Wine Festival">
                                <?php echo $row['post_title']; ?>
                            </span>
                            <div class="clearfix"></div>
                            <div class="post-location-snippet bmargin">
                                <span class="inline-block">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <b><?php /*
                                   echo $dateStr = $row['post_caption'];
                                   echo  $timestamp = strtotime($dateStr);
                                    $newDate = date("M d Y", $timestamp);
                                    echo $newDate; */?>
									
									<?php

// Your date string
$db_date = $row['post_caption'];

// Use DateTime to parse the date string
$date_obj = DateTime::createFromFormat('d/m/Y', $db_date);

// Format the date object to the desired format
$formatted_date = $date_obj->format('M d Y');

// Print the formatted date
echo $formatted_date;

?>
</b>
                                </span>
                            </div>
                           <?php if ($row['post_content'] != "") { ?>
                            <p class="bpad xs-nomargin xs-nopad">
                                <?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $row['post_content']),350) ?>
                            </p>
                            <?php
                            } ?>
                            <div class="clearfix"></div>
                            <?php
                            if ($row['post_location'] != "" || $row['post_venue'] != "") { ?>
                                <div class="post-location-snippet tmargin">
                                    <i class="fa fa-map-marker text-danger"></i>&nbsp;
                                    <b><?php echo $row['post_venue']; ?></b>
                                    <span class="inline-block font-sm">
                                        <?php echo $row['post_location']; ?>
                                    </span>
                                </div>
                            <?php
                            } ?>
                            <div class="clearfix"></div>
                            <div class="btn_view">
                                <?php if ($row['post_category'] === 'confirmed_events') { ?>
                                    <!-- <a href="/" class="inquiry_btn">
                                        Enquire <span><i class="fa fa-angle-right" aria-hidden="true"></i></span> <br>
                                    </a> -->
                                    <a class="inquiry_btn" data-toggle="modal" data-target="#smallShoes<?php echo $row['post_id']; ?>">
                                        Enquire <span><i class="fa fa-angle-right" aria-hidden="true"></i></span> <br>
                                    </a>
                                    <span class="underline"></span>
                                <?php } ?>
                                <div class="modal fade" id="smallShoes<?php echo $row['post_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabelSmall" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="modalLabel">Enquiry for
                                                    <?php echo $row['post_title']; ?>
                                                </h4>
                                            </div>
                                            <form action="" method="post" id="enquireForm">
                                                <div class="modal-body">
                                                    <textarea name="help_text" class="help_text form-control" cols="30" rows="10" placeholder="Hello, I’m interested in this event. We offer XYZ…"></textarea>
                                                    <input type="hidden" name="post_id" class="post_id" value="<?php echo $row['post_id']; ?>">
                                                    <input type="hidden" name="post_title" class="post_title" value="<?php echo $row['post_title']; ?>">
                                                    <input type="hidden" name="user_id" class="user_id" value="<?php echo $_COOKIE['userid']; ?> ">
                                                    <input type="hidden" name="post_token" class="post_token" value="<?php echo $row['post_token']; ?>">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" id="sendEnquireButton" class="btn btn-primary">Send
                                                    Enquiry</button>
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

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
