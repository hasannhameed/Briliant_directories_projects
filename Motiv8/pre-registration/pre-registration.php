<?php
// Handle GET request (update owner)

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'], $_GET['id'])){

    $user_id = $_GET['user_id'];
    $id = $_GET['id'];

    if(!empty($id)){
        $string = 'DELETE FROM preregistation_form WHERE id = '. $id .' AND user_id ='. $user_id;
        $sql= mysql_query($string);
        if($sql){ ?>

        <script>
            document.addEventListener('DOMContentLoaded',function(){
                  swal("Member Deleted!", "The member has been successfully removed.", "success");
                  window.history.replaceState(null, '', '?widget=pre-registration');
            })
           
        </script>

     <?php   }else{ ?>

            <script>
            document.addEventListener('DOMContentLoaded',function(){
                swal("Oops...", "Something went wrong! try again", "error");
            })
            </script>
      <?php  }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['new_owner'], $_GET['user_id'])) {

    $new_owner = (int) $_GET['new_owner'];
    $user_id   = (int) $_GET['user_id'];

    $update_sql = "UPDATE users_data SET owners = $new_owner WHERE user_id = $user_id";
    $query_check = mysql_query($update_sql);

    // Check affected rows
    if ($query_check && mysql_affected_rows() === 1) {
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                swal("Success!", "Owner updated successfully!", "success");
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                swal("Error!", "No changes were made or update failed.", "error");
            });
        </script>
        <?php
    }
}

?>


    
    <style>
        .swal-footer{
            display: flex
;
    justify-content: center;
    align-items: center;
}
        
        .badge {
    display: inline-block;
    min-width: 10px;
    padding: 4px 8px;
    font-size: 10px;
    font-weight: 600;
    color: #fff;
    line-height: 10px;
    vertical-align: text-bottom;
    white-space: nowrap;
    text-align: center;
    background: #ff6200;
    border-radius: 10px;
    margin-left: 2px;
}
       .alert {
            line-height: 1.5em;
            color: #636f7c;
            background-color: #fff;
            border-color: #d7d7d7;
            border-style: solid;
            border-width: 1px 1px 1px 4px;
            display: inline-block;
            font-size: 14px;
            margin: 0px;
            padding: 8px 15px 8px 10px;
            clear: both;
            min-height: 40px;
            box-sizing: border-box;
            border-radius: 7px;
        }

        .prehead{
            font-size: 20px;
            font-weight: 600;
        }
        .table_custom{
            display: none;
        }
        .alert_custom{
            cursor: pointer;
            font-size: large;
            display: flex;
            justify-content: space-between;
        }
        .custom_label{
            background-color: #ff6200;
                color: white !important;
                height: 10px;
        }
    /* .action_btn .edit-btn {
        font-size: 14px;
        color: #428bca !important;
        border: 1px solid #428bca !important;
    }
    .action_btn .edit-btn:hover {
        color: #fff !important;
        background-color: #428bca !important;
    } */
        .class2{
            width: 25% !important;
            /* color: transparent; */
        }
    .hidden{
        display: none;
    }
    </style>
<?php

  
$past_waitlist_string = "SELECT
                dp.*,
                COALESCE(pf.count_post, 0) AS count_post
            FROM
                data_posts dp
            LEFT JOIN(
                SELECT
                    post_id,
                    COUNT(*) AS count_post
                FROM
                    preregistation_form
                WHERE
                    post_id IS NOT NULL
                GROUP BY
                    post_id
            ) pf
            ON
                dp.post_id = pf.post_id
            WHERE
                dp.data_id = 75 AND dp.post_status = 1 AND dp.post_category = 'confirmed_events' AND confirm = 0 AND event_year != 0
            ORDER BY
                count_post
            DESC";


    $past_sql = "SELECT 
        dp.*,
        COALESCE(pf.count_post, 0) AS count_post
    FROM
        data_posts dp
    LEFT JOIN (
        SELECT
            post_id,
            COUNT(*) AS count_post
        FROM
            preregistation_form
        WHERE
            post_id IS NOT NULL
        GROUP BY
            post_id
    ) pf ON dp.post_id = pf.post_id
    WHERE
        dp.data_id = 75 AND dp.post_status = 1 AND dp.post_category = 'provisional_events'
        AND STR_TO_DATE(dp.post_caption, '%m/%d/%Y') < NOW() AND confirm != 1
    ORDER BY
        count_post DESC ";

  
    $provisional_sql = " SELECT 
            dp.*,
            COALESCE(pf.count_post, 0) AS count_post
        FROM
            data_posts dp
        LEFT JOIN (
            SELECT
                post_id,
                COUNT(*) AS count_post
            FROM
                preregistation_form
            WHERE
                post_id IS NOT NULL
            GROUP BY
                post_id
        ) pf ON dp.post_id = pf.post_id
        WHERE
            dp.data_id = 75 AND dp.post_status = 1 AND dp.post_category = 'provisional_credits'
        ORDER BY
            count_post DESC 
    ";

  
    $futur_sql = "SELECT 
			dp.*,
			COALESCE(pf.count_post, 0) AS count_post
		FROM
			data_posts dp
		LEFT JOIN (
			SELECT
				post_id,
				COUNT(*) AS count_post
			FROM
				preregistation_form
			WHERE
				post_id IS NOT NULL
			GROUP BY
				post_id
		) pf ON dp.post_id = pf.post_id
		WHERE
			dp.data_id = 75
			AND dp.post_status = 1
			AND dp.post_category = 'provisional_events'
			AND (STR_TO_DATE(dp.post_caption, '%m/%d/%Y') >= NOW() OR confirm = 1)
		ORDER BY
			count_post DESC";


 
    $res  = mysql_query($futur_sql);
    $res1 = mysql_query($provisional_sql);
    $res2 = mysql_query($past_sql);
    //echo $past_waitlist_string;
    $res3 = mysql_query($past_waitlist_string);
    //$res3_data = mysql_fetch_assoc($res3);
    //print_r($res3_data);
    ?>


    <?php
        if($pars[0] === 'admin' && $pars[1] === 'go.php'){

      
        $emailTemplatesQuery = "SELECT email_id, email_body FROM email_templates WHERE email_id = '29'";
        $emailTemplatesResult = mysql_query($emailTemplatesQuery);
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
            integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="pre_head">
                            Waitlist Registrations
                        </h2>
                        

                    <div class="table-responsive">

                        <!-- <p class="prehead prehead1" >Upcoming Events</p>
                        <table class="table table1">
                            <thead>
                                <tr>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table> -->

                        <p class="prehead" > Events </p>
                        <table class="table table3">
                            <thead>
                                <tr>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysql_num_rows($res2)>0){
                                while ($rowss = mysql_fetch_assoc($res2)) { ?>
                                    <tr class="accordion-header" data-group="megan" >
                                        <td colspan='12'>
                                            <div class="alert col-sm-12 alert_info alert_custom"
                                                id="<?= (int)$rowss['post_id']; ?>"
                                                data-selectedDate='<?php echo $rowss['post_caption'];?>'
                                                style="font-size: large;">
                                            <span style="width: 30%;"><?= htmlspecialchars($rowss['post_title']); ?></span>
                                            <span class="class2">
                                            <?php
                                                $raw = $rowss['event_year']!=0 ? trim($rowss['event_year']) : '';
                                                echo $raw;
                                            ?>

                                            </span>
                                            <?php
                                                $postId   = (int)$rowss['post_id'];
                                                $cnt      = 0;
                                                $event_name;
                                                $cntRes   = mysql_query("SELECT COUNT(*) AS total FROM preregistation_form WHERE post_id = {$postId}");
                                              
                                                if ($cntRes && ($cntRow = mysql_fetch_assoc($cntRes))) {
                                                    $cnt = (int)$cntRow['total'];
                                                    
                                                }

                                                $secretKey = "MY_SECRET_KEY"; // keep private
                                                $iv        = substr(hash('sha256', $secretKey), 0, 16);
                                                $encrypted = openssl_encrypt($postId, 'AES-256-CBC', $secretKey, 0, $iv);
                                                $token     = urlencode(base64_encode($encrypted));
                                               
                                            ?>
                                            <span class="custom_label label label-item"><?= $cnt ?></span>
                                            <button 

                                            class="copy_btn btn-clear-dark" 
                                            data-toggle="modal" 
                                            data-token="<?php echo $token; ?>" 
                                            data-target="<?php echo $postId; ?>" 
                                            data-coupon="wait-listregistration form">
                                            
                                                <i class="fa fa-files-o" aria-hidden="true"></i>Reg Link
                                            </button>
                                            <span>
                                                <i class="fa fa-plus" style="font-size:14px"></i>
                                                <i class="fa fa-minus" style="font-size:14px"></i>
                                            </span>
                                            </div>

                                            <table class="table table_custom table_id-<?php echo $rowss['post_id']; ?>">
                                                <thead>
                                                    <th style="width: 70px;">#</th>
                                                    <th>Full Name</th>
                                                    <th style="width: 120px;">Event</th>
                                                    <th>Job Title</th>
                                                    <th style="width: 120px;">Email</th>
                                                    <th>Registration Date</th>
                                                    <th>Phone Number</th>
                                                    <th>Company Name</th>
                                                    <th>Account Owner</th>
                                                    <th>Description</th>
                                                    <th>Actions(notify)</th>
                                                    <th>Actions(delete)</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $past_result = "SELECT * FROM preregistation_form WHERE post_id = " . (int)$rowss['post_id'] ." ORDER BY id DESC";
                                                    $past_sql    = mysql_query($past_result);
                                                    $counter     = 1;
                                                    ?>

                                                    <?php if ($past_sql && mysql_num_rows($past_sql) > 0): ?>
                                                    <?php while ($past_data = mysql_fetch_assoc($past_sql)): ?>
                                                        <tr class="accordion-header" data-group="megan" data-userId=<?php echo $past_data['user_id']; ?> data-regId=<?php echo $past_data['id']; ?>>
                                                        <td><?= $counter++; ?></td>
                                                        <td>
                                                            <a target="_blank" href="https://ww2.managemydirectory.com/admin/viewMembers.php?faction=view&userid=<?= (int)$past_data['user_id']; ?>">
                                                                <?= htmlspecialchars($past_data['full_name']); ?>
                                                            </a>
                                                        </td>
                                                        <td><?= htmlspecialchars($past_data['event_name']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['job_title']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['email_id']); ?></td>
                                                        <td><?= htmlspecialchars(date('Y-m-d', strtotime($past_data['created_at']))); ?></td>
                                                        <td><?= htmlspecialchars($past_data['phone_number']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['company_name']); ?></td>
                                                        <td>
                                                          <?php
                                                            $user_id = (int)$past_data['user_id'];

                                                            $sql = "SELECT owners FROM users_data WHERE user_id = $user_id";
                                                            $result = mysql_query($sql);
                                                            $current_owner_id = 0;
                                                            if ($result && mysql_num_rows($result) > 0) {
                                                                $row = mysql_fetch_assoc($result);
                                                                $current_owner_id = (int)$row['owners'];
                                                            }

                                                            $owners_q = mysql_query("SELECT user_id, first_name, last_name FROM users_data WHERE subscription_id = 4");
                                                          ?>
                                                          <form method="get" id="ownerForm<?= $user_id; ?>">
                                                              <select name="new_owner" onchange="this.form.submit();" >
                                                                  <option value="0">Select an owner</option>
                                                                  <?php while ($owner = mysql_fetch_assoc($owners_q)) : ?>
                                                                      <option value="<?= (int)$owner['user_id']; ?>" 
                                                                          <?= $current_owner_id === (int)$owner['user_id'] ? 'selected' : ''; ?>>
                                                                          <?= htmlspecialchars($owner['first_name'] . ' ' . $owner['last_name']); ?>
                                                                      </option>
                                                                  <?php endwhile; ?>
                                                              </select>
                                                              <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                                              <input type="hidden" name="widget" value="pre-registration">
                                                          </form>
                                                        </td>
                                                        <td><?= htmlspecialchars($past_data['showing_text']); ?></td>
                                                        <td class='action_btn' ><button class="notify_button btn btn-primary  edit-btn" onclick="window.location.href='<?php echo 'mailto:'. $email; echo '?subject='.$event_title ?>'"> Notify Member</button></td>
                                                        <!-- Optional actions cell: -->
                                                        <!-- <td class="action_btn">
                                                            <button class="btn btn-primary"
                                                                    onclick="window.location.href='mailto:<?= htmlspecialchars($past_data['email_id']); ?>?subject=<?= rawurlencode($past_data['event_name']); ?>'">
                                                            Notify Member
                                                            </button>
                                                        </td> -->
                                                       <td><button class='btn btn-danger delete_btn'>Delete</button></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                    <?php else: ?>
                                                    <tr><td colspan="9">No data available</td></tr>
                                                    <?php endif; ?>
                                                    </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php }
                                }  ?>
                                <?php 
                                
                                if(mysql_num_rows($res) > 0){
                                while ($rows = mysql_fetch_assoc($res)) { ?>
                                    <tr class="accordion-header" data-group="megan" data-userId=<?php echo $rows['user_id']; ?> data-regId=<?php echo $rows['id']; ?>>
                                        <td colspan='12'>
                                            <div class="alert col-sm-12 alert_info alert_custom"
                                                id="<?= (int)$rows['post_id']; ?>"
                                                style="font-size: large;" 
                                                data-selectedDate='<?php echo $rows['post_caption'];?>'
                                                >
                                            <span class="class1" style="width: 30%;">
                                                <?= htmlspecialchars($rows['post_title']); ?> <?php if($rows['confirm'] == 1) { ?> 
                                            <span class="badge">TBC</span> <?php } ?></span>
                                            <span class="class2">
                                                 <?php
                                                    $raw2 = $rows['event_year']!=0 ? trim($rows['event_year']) : '';
                                                    echo $raw2;
                                                ?>

                                            </span>
                                            <div>
                                                <?php
                                                    $postId   = (int)$rows['post_id'];
                                                    $cnt      = 0;
                                                    $event_name ;
                                                    $cntRes   = mysql_query("SELECT COUNT(*) AS total FROM preregistation_form WHERE post_id = {$postId}");
                                                    if ($cntRes && ($cntRow = mysql_fetch_assoc($cntRes))) {
                                                        $cnt        = (int)$cntRow['total'];
                                                        
                                                    }

                                                    $secretKey = "MY_SECRET_KEY"; // keep private
                                                    $iv        = substr(hash('sha256', $secretKey), 0, 16);
                                                    $encrypted = openssl_encrypt($postId, 'AES-256-CBC', $secretKey, 0, $iv);
                                                    $token     = urlencode(base64_encode($encrypted));

                                                ?>
                                                <span class="custom_label label label-item"><?= $cnt ?></span>
                                            </div>

                                            <div>
                                                <button 
                                                    class="copy_btn btn-clear-dark" 
                                                    data-toggle="modal" 
                                                    data-token="<?php echo $token; ?>" 
                                                    data-target="<?php echo $postId; ?>"
                                                    data-coupon="wait-listregistration form">
                                                    <i class="fa fa-files-o" aria-hidden="true"></i>Reg Link
                                                </button>
                                            </div>
                                            <span>
                                                <i class="fa fa-plus" style="font-size:14px"></i>
                                                <i class="fa fa-minus" style="font-size:14px"></i>
                                            </span>
                                            </div>

                                            <table class="table table_custom table_id-<?php echo $rows['post_id']; ?>">
                                                <thead>
                                                    <th style="width: 70px;">#</th>
                                                    <th>Full Name</th>
                                                    <th style="width: 120px;">Event</th>
                                                    <th>Job Title</th>
                                                    <th style="width: 120px;">Email</th>
                                                    <th>Registration Date</th>
                                                    <th>Phone Number</th>
                                                    <th>Company Name</th>
                                                    <th>Account Owner</th>
                                                    <th>Description</th>
                                                    <th>Actions(notify)</th>
                                                    <th>Actions(delete)</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $past_result = "SELECT * FROM preregistation_form WHERE post_id = " . (int)$rows['post_id'] ." ORDER BY id DESC";
                                                    $past_sql    = mysql_query($past_result);
                                                    $counter     = 1;
                                                    ?>

                                                    <?php if (mysql_num_rows($past_sql) >0): ?>
                                                    <?php while ($past_data = mysql_fetch_assoc($past_sql)): ?>
                                                        <tr class="accordion-header" data-group="megan" data-userId=<?php echo $past_data['user_id']; ?> data-regId=<?php echo $past_data['id']; ?>>
                                                        <td><?= $counter++; ?></td>
                                                        <td>
                                                            <a target="_blank"
                                                            href="https://ww2.managemydirectory.com/admin/viewMembers.php?faction=view&userid=<?= (int)$past_data['user_id']; ?>">
                                                            <?= htmlspecialchars($past_data['full_name']); ?>
                                                            </a>
                                                        </td>
                                                        <td><?= htmlspecialchars($past_data['event_name']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['job_title']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['email_id']); ?></td>
                                                        <td><?= htmlspecialchars(date('Y-m-d', strtotime($past_data['created_at']))); ?></td>
                                                        <td><?= htmlspecialchars($past_data['phone_number']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['company_name']); ?></td>
                                                        <td>
                                                        <?php
                                                          $user_id = (int)$past_data['user_id'];

                                                          $sql = "SELECT owners FROM users_data WHERE user_id = $user_id";
                                                          $result = mysql_query($sql);
                                                          $current_owner_id = 0;
                                                          if ($result && mysql_num_rows($result) > 0) {
                                                              $row = mysql_fetch_assoc($result);
                                                              $current_owner_id = (int)$row['owners'];
                                                          }

                                                          $owners_q = mysql_query("SELECT user_id, first_name, last_name FROM users_data WHERE subscription_id = 4");
                                                        ?>
                                                        <form method="get" id="ownerForm<?= $user_id; ?>">
                                                            <select name="new_owner" onchange="this.form.submit();">
                                                                <option value="0">Select an owner</option>
                                                                <?php while ($owner = mysql_fetch_assoc($owners_q)) : ?>
                                                                    <option value="<?= (int)$owner['user_id']; ?>" 
                                                                        <?= $current_owner_id === (int)$owner['user_id'] ? 'selected' : ''; ?>>
                                                                        <?= htmlspecialchars($owner['first_name'] . ' ' . $owner['last_name']); ?>
                                                                    </option>
                                                                <?php endwhile; ?>
                                                            </select>
                                                            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                                          <input type="hidden" name="widget" value="pre-registration">
                                                        </form>
                                                        </td>

                                                        <td><?= htmlspecialchars($past_data['showing_text']); ?></td>
                                                        <td class='action_btn' ><button class="notify_button btn btn-primary  edit-btn" onclick="window.location.href='<?php echo 'mailto:'. $email; echo '?subject='.$event_title ?>'"> Notify Member</button></td>
                                                        <td><button class='btn btn-danger delete_btn'>Delete</button></td>
                                                        <!-- Optional actions cell: -->
                                                        <!-- <td class="action_btn">
                                                            <button class="btn btn-primary"
                                                                    onclick="window.location.href='mailto:<?= htmlspecialchars($past_data['email_id']); ?>?subject=<?= rawurlencode($past_data['event_name']); ?>'">
                                                            Notify Member
                                                            </button>
                                                        </td> -->
                                                       
                                                        </tr>
                                                    <?php endwhile; ?>
                                                    <?php else: ?>
                                                    <tr><td colspan="9">No data available</td></tr>
                                                    <?php endif; ?>
                                                    </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php }
                                }else{ ?>
                                <style>
                                    .prehead1{
                                        display: none;
                                    }
                                </style>
                                 <!-- <div class="alert col-sm-12 alert_info alert_custom" style="font-size: large;">No Events Avalable</div> -->
                               <?php  } ?>
                            </tbody>
                        </table>

                        <p class="prehead" >Event Credits</p>
                        <table class="table table2">
                            <thead>
                                <tr>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysql_num_rows($res1) > 0){
                                while ($rows1 = mysql_fetch_assoc($res1)) { ?>
                                    <tr class="accordion-header" data-group="megan" >
                                        <td colspan='12'>
                                            <div class="alert col-sm-12 alert_info alert_custom"
                                                id="<?= (int)$rows1['post_id']; ?>"
                                                style="font-size: large;" 
                                                data-selectedDate='<?php echo $rows1['post_caption'];?>'
                                                >
                                            <span class="class1" style="width: 30%;"><?= htmlspecialchars($rows1['post_title']); ?></span>
                                            <span class="class2">
                                                 <?php
													$postCaption = $rows1['event_year']!=0 ? trim($rows1['event_year']) : '';
                                                    echo $postCaption;
												?>

                                            </span>
                                            <div>
                                                <?php
                                                    $postId   = (int)$rows1['post_id'];
    
                                                    $cnt      = 0;
                                                    $event_name ;
                                                    $cntRes   = mysql_query("SELECT COUNT(*) AS total FROM preregistation_form WHERE post_id = {$postId}");
                                                    if ($cntRes && ($cntRow = mysql_fetch_assoc($cntRes))) {
                                                        $cnt = (int)$cntRow['total'];
                                                        
                                                    }

                                                    $secretKey = "MY_SECRET_KEY"; // keep private
                                                    $iv        = substr(hash('sha256', $secretKey), 0, 16);
                                                    $encrypted = openssl_encrypt($postId, 'AES-256-CBC', $secretKey, 0, $iv);
                                                    $token     = urlencode(base64_encode($encrypted));

                                                ?>
                                                <span class="custom_label label label-item"><?= $cnt ?></span>
                                            </div>

                                            <button class="copy_btn btn-clear-dark" 
                                                    data-toggle="modal" 
                                                    data-token="<?php echo $token; ?>" 
                                                    data-target="<?php echo $postId; ?>"
                                                    data-coupon="wait-listregistration form" >

                                                <i class="fa fa-files-o" aria-hidden="true"></i>Reg Link
                                            </button>
                                            <span>
                                                <i class="fa fa-plus" style="font-size:14px"></i>
                                                <i class="fa fa-minus" style="font-size:14px"></i>
                                            </span>
                                            </div>

                                            <table class="table table_custom table_id-<?php echo $rows1['post_id']; ?>">
                                                <thead>
                                                    <th style="width: 70px;">#</th>
                                                    <th>Full Name</th>
                                                    <th style="width: 120px;">Event</th>
                                                    <th>Job Title</th>
                                                    <th style="width: 120px;">Email</th>
                                                    <th>Registration Date</th>
                                                    <th>Phone Number</th>
                                                    <th>Company Name</th>
                                                    <th>Account Owner</th>
                                                    <th>Description</th>
                                                    <th>Actions(notify)</th>
                                                    <th>Actions(delete)</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $past_result = "SELECT * FROM preregistation_form WHERE post_id = " . (int)$rows1['post_id'] ." ORDER BY id DESC";
                                                    $past_sql    = mysql_query($past_result);
                                                    $counter     = 1;
                                                    ?>

                                                    <?php if (mysql_num_rows($past_sql) >0): ?>
                                                    <?php while ($past_data = mysql_fetch_assoc($past_sql)): ?>
                                                        <tr class="accordion-header" data-group="megan" data-user_id='<?php echo $past_data['user_id'];?>' data-regId=<?php echo $past_data['id']; ?>>
                                                        <td><?= $counter++; ?></td>
                                                        <td>
                                                            <a target="_blank"
                                                            href="https://ww2.managemydirectory.com/admin/viewMembers.php?faction=view&userid=<?= (int)$past_data['user_id']; ?>">
                                                            <?= htmlspecialchars($past_data['full_name']); ?>
                                                            </a>
                                                        </td>
                                                        <td><?= htmlspecialchars($past_data['event_name']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['job_title']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['email_id']); ?></td>
                                                        <td><?= htmlspecialchars(date('Y-m-d', strtotime($past_data['created_at']))); ?></td>
                                                        <td><?= htmlspecialchars($past_data['phone_number']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['company_name']); ?></td>
                                                        <td>
                                                          <?php
                                                            $user_id = (int)$past_data['user_id'];

                                                            $sql = "SELECT owners FROM users_data WHERE user_id = $user_id";
                                                            $result = mysql_query($sql);
                                                            $current_owner_id = 0;
                                                            if ($result && mysql_num_rows($result) > 0) {
                                                                $row = mysql_fetch_assoc($result);
                                                                $current_owner_id = (int)$row['owners'];
                                                            }

                                                            $owners_q = mysql_query("SELECT user_id, first_name, last_name FROM users_data WHERE subscription_id = 4");
                                                          ?>
                                                          <form method="get" id="ownerForm<?= $user_id; ?>">
                                                              <select name="new_owner" onchange="this.form.submit();">
                                                                  <option value="0">Select an owner</option>
                                                                  <?php while ($owner = mysql_fetch_assoc($owners_q)) : ?>
                                                                      <option value="<?= (int)$owner['user_id']; ?>" 
                                                                          <?= $current_owner_id === (int)$owner['user_id'] ? 'selected' : ''; ?>>
                                                                          <?= htmlspecialchars($owner['first_name'] . ' ' . $owner['last_name']); ?>
                                                                      </option>
                                                                  <?php endwhile; ?>
                                                              </select>
                                                              <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                                              <input type="hidden" name="widget" value="pre-registration">
                                                          </form>
                                                        </td>
                                                        <td><?= htmlspecialchars($past_data['showing_text']); ?></td>
                                                        <td class='action_btn' ><button class="notify_button btn btn-primary  edit-btn" onclick="window.location.href='<?php echo 'mailto:'. $email; echo '?subject='.$event_title ?>'"> Notify Member</button></td>
                                                        <td><button class='btn btn-danger delete_btn'>Delete</button></td>
                                                        <!-- Optional actions cell: -->
                                                        <!-- <td class="action_btn">
                                                            <button class="btn btn-primary"
                                                                    onclick="window.location.href='mailto:<?= htmlspecialchars($past_data['email_id']); ?>?subject=<?= rawurlencode($past_data['event_name']); ?>'">
                                                            Notify Member
                                                            </button>
                                                        </td> -->
                                                       
                                                        </tr>
                                                    <?php endwhile; ?>
                                                    <?php else: ?>
                                                    <tr><td colspan="9">No data available</td></tr>
                                                    <?php endif; ?>
                                                    </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php }
                                }else{ ?>
                                    <div class="alert col-sm-12 alert_info alert_custom" style="font-size: large;">No Events Avalable</div>
                               <?php  } ?>
                            </tbody>
                        </table>

                        
                        <p class="prehead" >Past Waitlist Events</p>
                        <table class="table table2">
                            <thead>
                                <tr>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysql_num_rows($res3) > 0){
                                while ($rows3 = mysql_fetch_assoc($res3)) { ?>
                                    <tr class="accordion-header" >
                                        <td colspan='12'>
                                            <div class="alert col-sm-12 alert_info alert_custom"
                                                id="<?= (int)$rows3['post_id']; ?>"
                                                style="font-size: large;" 
                                                data-selectedDate='<?php echo $rows3['post_caption'];?>'
                                                >
                                            <span class="class1" style="width: 30%;"><?= htmlspecialchars($rows3['post_title']); ?></span>
                                            <span class="class2">
                                                 <?php
													$postCaption = $rows3['event_year']!=0 ? trim($rows3['event_year']) : '';
                                                    echo $postCaption;
												?>

                                            </span>
                                            <div>
                                                <?php
                                                    $postId   = (int)$rows3['post_id'];
    
                                                    $cnt      = 0;
                                                    $event_name ;
                                                    $cntRes   = mysql_query("SELECT COUNT(*) AS total FROM preregistation_form WHERE post_id = {$postId}");
                                                    if ($cntRes && ($cntRow = mysql_fetch_assoc($cntRes))) {
                                                        $cnt = (int)$cntRow['total'];
                                                        
                                                    }

                                                    $secretKey = "MY_SECRET_KEY"; // keep private
                                                    $iv        = substr(hash('sha256', $secretKey), 0, 16);
                                                    $encrypted = openssl_encrypt($postId, 'AES-256-CBC', $secretKey, 0, $iv);
                                                    $token     = urlencode(base64_encode($encrypted));

                                                ?>
                                                <span class="custom_label label label-item"><?= $cnt ?></span>
                                            </div>

                                            <button class="copy_btn btn-clear-dark" 
                                                    data-toggle="modal" 
                                                    data-token="<?php echo $token; ?>" 
                                                    data-target="<?php echo $postId; ?>" 
                                                    data-coupon="wait-listregistration form" >

                                                <i class="fa fa-files-o" aria-hidden="true"></i>Reg Link
                                            </button>
                                            <span>
                                                <i class="fa fa-plus" style="font-size:14px"></i>
                                                <i class="fa fa-minus" style="font-size:14px"></i>
                                            </span>
                                            </div>

                                            <table class="table table_custom table_id-<?php echo $rows3['post_id']; ?>">
                                                <thead>
                                                    <th style="width: 70px;">#</th>
                                                    <th>Full Name</th>
                                                    <th style="width: 120px;">Event</th>
                                                    <th>Job Title</th>
                                                    <th style="width: 120px;">Email</th>
                                                    <th>Registration Date</th>
                                                    <th>Phone Number</th>
                                                    <th>Company Name</th>
                                                    <th>Account Owner</th>
                                                    <th>Description</th>
                                                    <th>Actions(notify)</th>
                                                    <th>Actions(delete)</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $past_result = "SELECT * FROM preregistation_form WHERE post_id = " . (int)$rows3['post_id'] ." ORDER BY id DESC";
                                                    $past_sql    = mysql_query($past_result);
                                                    $counter     = 1;
                                                    ?>

                                                    <?php if (mysql_num_rows($past_sql) >0): ?>
                                                    <?php while ($past_data = mysql_fetch_assoc($past_sql)): ?>
                                                        <tr class="accordion-header" data-group="megan" data-user_id='<?php echo $past_data['user_id'];?>' data-regId=<?php echo $past_data['id']; ?>>
                                                        <td><?= $counter++; ?></td>
                                                        <td>
                                                            <a target="_blank"
                                                            href="https://ww2.managemydirectory.com/admin/viewMembers.php?faction=view&userid=<?= (int)$past_data['user_id']; ?>">
                                                            <?= htmlspecialchars($past_data['full_name']); ?>
                                                            </a>
                                                        </td>
                                                        <td><?= htmlspecialchars($past_data['event_name']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['job_title']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['email_id']); ?></td>
                                                        <td><?= htmlspecialchars(date('Y-m-d', strtotime($past_data['created_at']))); ?></td>
                                                        <td><?= htmlspecialchars($past_data['phone_number']); ?></td>
                                                        <td><?= htmlspecialchars($past_data['company_name']); ?></td>
                                                        <td>
                                                          <?php
                                                            $user_id = (int)$past_data['user_id'];

                                                            $sql = "SELECT owners FROM users_data WHERE user_id = $user_id";
                                                            $result = mysql_query($sql);
                                                            $current_owner_id = 0;
                                                            if ($result && mysql_num_rows($result) > 0) {
                                                                $row = mysql_fetch_assoc($result);
                                                                $current_owner_id = (int)$row['owners'];
                                                            }

                                                            $owners_q = mysql_query("SELECT user_id, first_name, last_name FROM users_data WHERE subscription_id = 4");
                                                          ?>
                                                          <form method="get" id="ownerForm<?= $user_id; ?>">
                                                              <select name="new_owner" onchange="this.form.submit();">
                                                                  <option value="0">Select an owner</option>
                                                                  <?php while ($owner = mysql_fetch_assoc($owners_q)) : ?>
                                                                      <option value="<?= (int)$owner['user_id']; ?>" 
                                                                          <?= $current_owner_id === (int)$owner['user_id'] ? 'selected' : ''; ?>>
                                                                          <?= htmlspecialchars($owner['first_name'] . ' ' . $owner['last_name']); ?>
                                                                      </option>
                                                                  <?php endwhile; ?>
                                                              </select>
                                                              <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                                              <input type="hidden" name="widget" value="pre-registration">
                                                          </form>
                                                        </td>
                                                        <td><?= htmlspecialchars($past_data['showing_text']); ?></td>
                                                        <td class='action_btn' ><button class="notify_button btn btn-primary  edit-btn" onclick="window.location.href='<?php echo 'mailto:'. $email; echo '?subject='.$event_title ?>'"> Notify Member</button></td>
                                                        <td><button class='btn btn-danger delete_btn'>Delete</button></td>
                                                        <!-- Optional actions cell: -->
                                                        <!-- <td class="action_btn">
                                                            <button class="btn btn-primary"
                                                                    onclick="window.location.href='mailto:<?= htmlspecialchars($past_data['email_id']); ?>?subject=<?= rawurlencode($past_data['event_name']); ?>'">
                                                            Notify Member
                                                            </button>
                                                        </td> -->
                                                       
                                                        </tr>
                                                    <?php endwhile; ?>
                                                    <?php else: ?>
                                                    <tr><td colspan="9">No data available</td></tr>
                                                    <?php endif; ?>
                                                    </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php }
                                } else{ ?>
                                 <div class="alert col-sm-12 alert_info alert_custom" style="font-size: large;">No Events Avalable</div>
                               <?php  } ?>
                            </tbody>
                        </table>

                    </div>
                    </div>
                </div>
            </div>
    </section>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <!-- <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        $(document).ready(function () {
            $('.notify_text').each(function () {
                CKEDITOR.replace(this);
            });
        });
    </script> -->
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
    <script>

        document.addEventListener('click', function runToggle(e) {
            if(e.target.closest('.copy_btn')){
                return;
            }
            if (e.target.closest('.accordion-header')) {
                let headerRow = e.target.closest('.accordion-header');
                let table = headerRow.querySelector('.table_custom');
                if (table) {
                    table.style.display = (table.style.display === 'none' || table.style.display === '') ? 'block' : 'none';
                }
            }
        });

    </script>

  <script>

    document.addEventListener("click", function(e) {
        const copyButton = e.target.classList.contains("copy_btn") ? e.target : e.target.closest(".copy_btn");

        if (copyButton) {
            const tokenValue = copyButton.getAttribute("data-token");
            const baseURL = "https://www.motiv8search.com/waitlist_registrations?pre-register=";
            const fullURL = baseURL + tokenValue;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(fullURL)
                    .then(() => {
                        swal("Link Copied!", "Link Copied Successfully!!", "success")
                    })
                    .catch(err => {
                        console.error('Copy failed:', err);
                        alert('Copy failed. Your browser may not support the copy function.');
                    });
            } else {
              
                alert(`Link not copied (Secure context required): ${fullURL}`);
            }
        }
    });

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  
    const spans = document.querySelectorAll('span.class2');

    spans.forEach(span => {
      
        const rawDate = span.textContent.trim();

      
        if (/^\d{8}$/.test(rawDate)) {
            const year = rawDate.substring(0, 4);
            const month = rawDate.substring(4, 6);
            const day = rawDate.substring(6, 8);

          
            const dateObj = new Date(`${year}-${month}-${day}`);

          
            const formattedDate = dateObj.toLocaleDateString('en-GB', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

          
            span.textContent = formattedDate;
        }
    });
});
</script>
<script>

  const tableResponsive = document.querySelector('.table-responsive');

  tableResponsive.addEventListener('click', deleteRegistration);

  function deleteRegistration(e) {
    if (e.target.classList.contains('delete_btn')) {
    
      const accordionHeader = e.target.closest('.accordion-header');

      if (!accordionHeader) return; 

    
      const user_id = accordionHeader.getAttribute('data-userid') || accordionHeader.getAttribute('data-user_id');
      const regid = accordionHeader.getAttribute('data-regid');

      console.log('user_id:', user_id);
      console.log('regid:', regid);

      if (confirm("Are you sure you want to delete this registration?")) {
         location.href=`https://ww2.managemydirectory.com/admin/go.php?widget=pre-registration&user_id=${user_id}&id=${regid}`;
      }
    }
  }
</script>
