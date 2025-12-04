<?php
//echo $_COOKIE['userid'];
$evnt_en_SQL = "SELECT data_posts.post_id, data_posts.post_title, data_posts.post_image, data_posts.post_filename, data_posts.post_category, data_posts.post_location, data_posts.post_caption FROM data_posts JOIN supplier_registration_form ON data_posts.post_id = supplier_registration_form.event_id WHERE supplier_registration_form.user_id = '".$_COOKIE['userid']."'";
//echo "SELECT data_posts.post_id, data_posts.post_title, data_posts.post_image, data_posts.post_filename, data_posts.post_category, data_posts.post_location, data_posts.post_caption FROM data_posts JOIN supplier_registration_form ON data_posts.post_id = supplier_registration_form.event_id WHERE supplier_registration_form.user_id = '".$_COOKIE['userid']."'";
$evnt_en_Query = mysql_query($evnt_en_SQL);

$sql = "SELECT ee.id, ee.user_id, ee.post_id, ee.post_title, ee.enquire_message, ee.post_token, ud.listing_type, ud.first_name, ud.last_name, ud.email FROM event_enquire ee JOIN users_data ud ON ee.user_id = ud.user_id WHERE ud.user_id = '".$_COOKIE['userid']."' ORDER BY `ee`.`id` DESC";
//echo $sql;
$result = mysql_query($sql);
$show_application = false;
?>


<div class="col-md-9">
    <h1 class="main_title bold">Event Enquiries</h1>
    <div class="table-responsive">
        <?php if($show_application == true){ ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Location</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <!-- Add your table data here -->
                    <?php
                    while ($event_en_row = mysql_fetch_assoc($evnt_en_Query) ) { ?>
                    <tr>
                        <td><?= $event_en_row['post_id'] ?></td>
                        <td><?= $event_en_row['post_title'] ?></td>
                        <td><?= $event_en_row['post_caption'] ?></td>
                        <td><?= $event_en_row['post_location'] ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        <?php }else{ ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result) {
                        $serialNumber = 1;
                        while ($row = mysql_fetch_assoc($result)) {
                            $myquery = "SELECT * FROM create_application_pages cap JOIN data_posts dp ON cap.event_id = dp.event_id JOIN event_enquire ee ON ee.post_id = dp.post_id WHERE ee.id = $row[id]";
                            $myresult = mysql_query($myquery);
                            $myrow = mysql_fetch_assoc($myresult);
                            echo "<td>" . $row["post_title"] . "</td>";
                            echo "<td class='enquire_box'>" . $row["enquire_message"] . "</td>";
                            echo "</tr>";
                            echo "<tr> <td class='hidden'>'SELECT * FROM create_application_pages cap JOIN data_posts dp ON cap.event_id = dp.event_id JOIN event_enquire ee ON ee.post_id = dp.post_id WHERE ee.id = $row[id]'</td> </tr>";
                            $serialNumber++;
                        }
                        
                    } else {
                        echo "Error";
                    } 
                    $pfSql = "SELECT * FROM `preregistation_form` WHERE `preregistation_form`.`user_id`= '".$_COOKIE['userid']."' AND event_id > 0 ORDER BY `preregistation_form`.`id` DESC";
                    $pfResult = mysql_query($pfSql);
                    if($pfResult){
                        while ($pfRow = mysql_fetch_assoc($pfResult) ) {
                            echo "<tr>";
                            echo "<td>". $pfRow["event_name"]. "</td>";
                            echo "<td class='enquire_box'>". $pfRow["package"]. " <br> ". $pfRow['abletopay'] ."</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>