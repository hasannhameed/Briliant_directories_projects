<?php
if($pars[0] === 'admin' && $pars[1] === 'go.php'){
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="event_heading">Event Enquiries</h2>
                <div class="clearfix"></div>
                <table style="width:100%" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <!-- <th>ID</th> -->
                            <th>User Info</th>
                            <!-- <th>Post ID</th> -->
                            <th>Post Title</th>
                            <th>Enquire Message</th>
                            <th>Enquiry Date </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT ee.id, ee.user_id, ee.post_id, ee.post_title,ee.enquirydate, ee.enquire_message, ee.post_token, ud.listing_type,ud.company, ud.first_name, ud.last_name,ud.email FROM event_enquire ee JOIN users_data ud ON ee.user_id = ud.user_id ORDER BY `ee`.`id` DESC";
                        //echo $sql."<br>";
                        $result = mysql_query($sql);

                        if ($result) {
                            $serialNumber = 1;
                            while ($row = mysql_fetch_assoc($result)) {
                                $myquery = "SELECT cap.token AS post_token, cap.* FROM create_application_pages cap JOIN data_posts dp ON cap.event_id = dp.event_id JOIN event_enquire ee ON ee.post_id = dp.post_id WHERE ee.id = $row[id]";
                                $myresult = mysql_query($myquery);
                                $myrow = mysql_fetch_assoc($myresult);
                                if(count($myrow['id']) === 0){
                                    $myrow = $row;
                                }
                                echo "<tr>";
                                echo "<td>" . $serialNumber . "</td>";
                                //echo "<td>" . $row["id"] . "</td>";
                                echo "<td><a href='https://ww2.managemydirectory.com/admin/viewMembers.php?faction=view&userid=" . $row["user_id"] . "' target='_blank'>" . $row["first_name"] . ' ' . $row["last_name"] . "</a>";
                                if($row['listing_type']=='Company' && !empty($row['company'])){
                                 echo "<p>". $row['company']. "</p>";
                                }
								if(empty($row['first_name']) && empty($row['company'])){
									echo "<p>". $row['email']. "</p>";
								}
                                echo "</td>";
                                //echo "<td>" . $row["post_id"] . "</td>";

                                echo "<td><a href='https://www.motiv8search.com/account/events-announcements/edit/" . $row["post_token"] . "' target='_blank'>" . $row["post_title"] . "</a></td>";
                                echo "<td class='enquire_box'>" . $row["enquire_message"] . "</td>";
                                $email = $row['email'];
                                $Subject = $row["post_title"];

                                $date_from_db = $row['enquirydate']; 

                                if ($date_from_db !== '0000-00-00 00:00:00') {
                                     $datedb = date('d/m/Y', strtotime($date_from_db));
                                } else {
                                    $datedb = '';
                                }
                                 echo "<td>" . $datedb . "</td>";
                                
                                ?>
                                <td>
                                    <button class="notify_button btn btn-primary" onclick="window.location.href='<?php echo 'mailto:'. $email; echo '?subject='.$Subject ?>'"> Send Email</button>
                                    <button class='copy_btn btn btn-primary' data-token="<?php echo $myrow['post_token'] ?>" data-target="<?php echo $myrow['id'] ?>"><i class='fa fa-files-o' aria-hidden='true'></i> Event URL </button>

                                </td>
                                <?php
                                echo "</tr>";
                                $serialNumber++;
                            }
                        } else {
                            echo "Error";
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
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