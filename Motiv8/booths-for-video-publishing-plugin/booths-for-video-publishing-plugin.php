<!-- Today's(27) end of editing booths-for-video-publishing-plugin -->
<?php

global $sess;
$loggeduser = $sess['admin_user'];

if ($pars[0] === 'admin' && $pars[1] === 'go.php') {

  global $sess;
  $loggedname = $sess['admin_name'];
  $loggeduser = $sess['admin_user'];
  $complet = 'Published';
  $inComplet = 'Incomplete';
  $approve = 'Set to Publish';
  $reject = 'Set as Incomplete';
  $website_domain = "https://launch18186.directoryup.com";
  $website_domain = "https://www.motiv8search.com";
  if ($_GET['post_id'] != '') {
    $get_post_details = mysql_fetch_assoc(mysql_query("SELECT * FROM data_posts WHERE post_id = '" . $_GET['post_id'] . "'"));
    $get_start_time = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_post_details['post_id'] . " AND `key` = 'start_time'"));
    $start_time = $get_start_time['value'];
    $get_end_time = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_post_details['post_id'] . " AND `key` = 'end_time'"));
    $end_time = $get_end_time['value'];
  }
  $where_str = "";

  if (isset($_POST['key'])) {
    if ($_POST['key'] != "") {
      $key_where_str = " ud.user_id LIKE '%" . $_POST['key'] . "%' OR ud.company  LIKE '%" . $_POST['key'] . "%' OR ud.email  LIKE '%" . $_POST['key'] . "%' OR concat(ud.first_name, ' ', ud.last_name) LIKE '%" . $_POST['key'] . "%' OR ud.first_name LIKE '%" . $_POST['key'] . "%' OR ud.last_name LIKE '%" . $_POST['key'] . "%' OR lep.event_name LIKE '%" . $_POST['key'] . "%'";
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id = $_POST['id'];
    $content = $_POST['content'];
    $type = $_POST['type'];
    $post_id = $_POST['postID'];

    $currentDate = date('Y-m-d H:i:s');
    if (isset($Id) && isset($content) && isset($type)) {
      $content = mysql_real_escape_string($content);
      if ($type === 'comment') {
        $sql = "INSERT INTO supplier_comments (user_id, post_id, comments, comment_by, `date`) VALUES ('$Id', '$post_id', '$content', '" . $sess['admin_name'] . "', '$currentDate') ";
      }
      if ($type === 'owner') {
        $sql = "UPDATE users_data SET owners = '$content' WHERE user_id  = $Id";
      }
      echo $sql;
      $query = mysql_query($sql);
      if ($query) {
        echo "Comments updated successfully";
      } else {
        echo "Error updating comments: " . mysql_error();
      }
    }
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cid"])) {
    $cid = $_POST['cid'];
    $deleteQuery = "DELETE FROM supplier_comments WHERE id='$cid'";
    $delete_log = mysql_query("INSERT INTO log_delete (loggedname, loggeduser, delete_type, deleted_id) 
                               VALUES ('$loggedname', '$loggeduser', 'comment_delete_booth', '$cid')");
    if (mysql_query($deleteQuery) === TRUE) {
    } else {
      echo "Error deleting data: " . mysql_error();
    }
  } else {
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["text_label"])) {
    $text_label = $_POST['text_label'];
    $color_code = $_POST['color_code'];
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
    $insertQuery = "INSERT INTO supplier_labels (text_label, color_code, post_id, user_id, created_by, created_at) VALUES ('$text_label', '$color_code', '$post_id', '$user_id', '" . $sess['admin_name'] . "',NOW())";
    if (mysql_query($insertQuery) === TRUE) {
    } else {
      echo "Error: " . $insertQuery . "<br>" . mysql_error();
    }
  }
  if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['existing_equipment'])) {
    $more_equipment = mysql_real_escape_string($_POST['existing_equipment']);
    $supplier_id = mysql_real_escape_string($_POST['supplier_id']);
    $post_id = mysql_real_escape_string($_GET['post_id']);
    $updateQuery = "UPDATE attending_supplier_staff_registration 
                  SET equipment_type = '$more_equipment' 
                  WHERE supplier_id = '$supplier_id' AND post_id = '$post_id'";

    echo $updateQuery;

    $updateResult = mysql_query($updateQuery);
    if ($updateResult) {
      echo "<script>
              swal({
                  title: 'Action Successful!',
                  text: 'Equipment updated successfully.',
                  type: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'OK',
                  closeOnConfirm: false
              }, function(isConfirm) {
                  if (isConfirm) {
                      window.location.href = 'https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=" . $post_id . "';
                  }
              });
            </script>";
    } else {
      echo "<script>
              swal({
                  title: 'Error!',
                  text: 'Failed to update equipment.',
                  type: 'error',
                  showCancelButton: false,
                  confirmButtonColor: '#d33',
                  confirmButtonText: 'OK'
              });
            </script>";
    }
  }
  if (isset($_POST)) {
    if (isset($_POST['delete_post'])) {
      $delete_post = mysql_query("DELETE FROM `live_events_posts` WHERE `live_events_posts`.`id` = " . $_POST['delete_post']);
      echo "<script>location = location</script>";
      $delete_log = mysql_query("INSERT INTO log_delete (loggedname, loggeduser, delete_type, deleted_id) 
                                 VALUES ('$loggedname', '$loggeduser', 'supplier_delete_booth', '" . $_POST['delete_post'] . "')");
    }
    if (isset($_POST['approve'])) {
      $update_to_approve = mysql_query("UPDATE `live_events_posts` SET `staus` = '2' WHERE `live_events_posts`.`id` = '" . $_POST['approve'] . "'");
      echo "<script>location = location</script>";
    }
    if (isset($_POST['reject'])) {
      $update_to_approve = mysql_query("UPDATE `live_events_posts` SET `staus` = '1' WHERE `live_events_posts`.`id` = '" . $_POST['reject'] . "'");
      echo "<script>location = location</script>";
    }
  }
  if (isset($_POST['event_search'])) {
    if ($_POST['event_name'] != "") {
      $where_str = " dp.post_title LIKE '%" . $_POST['event_name'] . "%' ";
    }

    if ($_POST['user_id'] != "") {
      $where_str = " (concat(ud.first_name, ' ', ud.last_name) LIKE '%" . $_POST['user_id'] . "%' OR ud.user_id = '" . $_POST['user_id'] . "') ";
    }
  }
  if ($_GET['post_id'] != '') {
    $where_str = " lep.post_id = '" . $_GET['post_id'] . "'";
  }
  if ($where_str != "") {
    $where_str = "WHERE " . $where_str;
  }
  if ($key_where_str != "") {
    $key_where_str = " AND (" . $key_where_str . ")";
  }
  $get_videos_sql = mysql_query("
      SELECT
          lep.*,
          dp.post_title,
          dp.post_start_date,
          dp.post_expire_date,
          ud.first_name,
          ud.last_name,
          ud.company,
          ud.user_id,
          ud.email,
          ud.token,
          ud.filename
      FROM
          `live_events_posts` AS lep
      LEFT JOIN data_posts AS dp ON dp.post_id = lep.post_id
      LEFT JOIN users_data as ud ON lep.user_id = ud.user_id " . $where_str . $key_where_str . " ORDER BY id DESC
      ");
  $num_of_suppliers = mysql_num_rows($get_videos_sql);
  
?>
  <section>
    <a href="/admin/go.php?widget=video-publishing-plugin" class="back-button"><i class="fa fa-reply"></i> Back</a>
    <a href="https://www.motiv8search.com/<?= $get_post_details['post_filename'] ?>" class="view-post-button" target="_blank"><i class="fa fa-external-link"></i> View Event Page</a>
    <hr class="header-hr">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 col-md-12">
          <div class="row">
            <div class="col-lg-7 col-md-6">
              <h2 class="float-left" style="margin-bottom: 10px;"><?= $get_post_details['post_title'] ?></h2>
              <div class="clearfix"></div>
              <p style="display:none;"><strong>Start :</strong> <?php echo date('Y-m-d', strtotime($get_post_details['post_start_date'])) . " " . $start_time ?></p>
              <div class="clearfix"></div>
              <p style="display:none;"><strong>End :</strong> <?php echo date('Y-m-d', strtotime($get_post_details['post_start_date'])) . " " . $end_time ?></p>
            </div>
            <div class="col-lg-2 col-md-3">
              <input type="text" class="form-control" value="<?php echo $_POST['key'] ?>" placeholder="Search by keyword" name="key" id="event_searchInput">
            </div>
            <div class="col-lg-3 col-md-3">
              <a href="<?php echo $website_domain ?>/api/widget/html/get/export-suppliers-data?post_id=<?php echo $post_id; ?>" class="btn btn-primary float-right ">Export CSV <i class="fa fa-download"></i></a>
              <button type="button" class="btn btn-primary lmargin" data-toggle="modal" data-target="#myModal"><i class="fa fa-user-plus"></i> Add Supplier</button>
            </div>
          </div>
          <div class="clearfix"></div>
          <table class="table">
            <thead>
              <tr>
                <!-- <th>Event Detail</th> -->
                <th style="width:300px">Supplier <span class="count-supplier"><?= $num_of_suppliers ?></span></th>
                <th>Supplier Information</th>
                <th>Incomplete Fields</th>
                <th style="width: 300px;">Comments</th>
                <th style="width: 155px;">Labels</th>
                <th style="width: 289px;">Attending Staff <a href="<?php echo $website_domain ?>/api/widget/html/get/download-attending-supplier-staff-registration-csv?post_id=<?php echo $post_id; ?>" id='download_cssv'> <i class="fa fa-download"></i></a></th>
                <th style="width: 300px;">Event Equipment</th>
              </tr>
            </thead>
            <tbody id="eventTable">
              <?php

              while ($videos = mysql_fetch_assoc($get_videos_sql)) {
                $get_start_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $videos['post_id'] . " AND `key` = 'start_time'");
                $get_start_time = mysql_fetch_assoc($get_start_time_sql);
                $start_time = $get_start_time['value'];

                $get_end_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $videos['post_id'] . " AND `key` = 'end_time'");
                $get_end_time = mysql_fetch_assoc($get_end_time_sql);
                $end_time = $get_end_time['value']
              ?>
                <tr data-userId=<?php echo $videos['user_id'];?>>
                  <td class="Supplier">
                    <div class="video_detail">
                      <p><strong><?php echo $videos['first_name'] . " " . $videos['last_name'] ?></strong></p>
                      <?php if ($videos['company'] != ""): ?>
                        <p>Company : <?php echo $videos['company'] ?></p>
                      <?php endif ?>
                      <p>Email: <a href="mailto:<?= $videos['email'] ?>"><?= $videos['email'] ?></a></p>
                      <p>Member ID : <?php echo $videos['user_id'] ?></p>
                      <?php
                      $user_logo_view = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_photo` WHERE user_id = '" . $videos['user_id'] . "' AND type = 'logo' LIMIT 1"));
                      if ($user_logo_view != '') { ?>
                        <div class="member-image">
                          <img src="<?php echo 'https://www.motiv8search.com/logos/profile/' . $user_logo_view['file']; ?>">
                        </div>
                        <a href="<?php echo 'https://www.motiv8search.com/logos/profile/' . $user_logo_view['file']; ?>" class="" target='_blank'><i class="fa fa-eye"></i>View Logo</a>
                        <? } else {
                        echo 'Logo N/A';
                      }
                        ?>
                        <br>
                        <br>
                        <button class="btn btn-info" data-user-id="<?php echo $videos['user_id']; ?>">
                          Manage Account <i class="fa fa-caret-down fa-fw"></i>
                        </button>
                        <div class="action-links" id="action-links-<?php echo $videos['user_id']; ?>" style="display: none;">
                          <a href="https://www.motiv8search.com/login/token/<?= $videos['token'] ?>/home" target="_blank">Login as Member</a>
                          <a href="https://www.motiv8search.com/<?= $videos['filename'] ?>?bdt=<?= $videos['token'] ?>" target="_blank">View Live Profile</a>
                          <a href="/admin/viewMembers.php?faction=view&userid=<?= $videos['token'] ?>&newsite=19348" target="_blank">Account Details</a>
                          <a href="/admin/viewMembers.php?faction=edit&userid=<?= $videos['token'] ?>&newsite=19348&noheader=1" class="popup">Quick Edit</a>
                          <a href="/admin/emailCompose.php?noheader=1&form_name=contact&table_index=user_id&email_state=full&email_from=george%40motiv8connect.com&email_recipients=<?= $videos['email'] ?>&email_template=&user_id=<?= $videos['user_id'] ?>&select2=0&name_from=Motiv8+Search&subscription_name=Free+Membership&invoiceid=" class="popup"> Compose Email</a>
                          <a href="/admin/createReminder.php?faction=orders&userid=<?= $videos['token'] ?>&method=view&newsite=19348" target="_blank"> Payment History</a>
                          <a href="/admin/go.php?widget=Admin%20-%20Module%20-%20Change%20Password%20Tool&user=<?= $videos['token'] ?>&noheader=1" class="popup">Change Password</a>
                          <a href="#" class="action-link-delete delete_member_plugin" attr-name="<?= $videos['first_name'] . " " . $videos['last_name'] ?>" attr-id="<?= $videos['user_id'] ?>">Delete Account</a>
                        </div>
                    </div>
                  </td>
                  <td class="Supplier-Info">
                    <div class="video_detail">
                      <?php
                      if ($videos['video_option'] == 'link') { ?>
                        <p><strong><?php echo $videos['event_name'] ?></strong></p>
                        <? }

                      if ($videos['video_option'] != 'link') { ?>
                        <p><strong><?php echo $videos['event_description'] ?></strong></p>
                        <? }
                        ?>
                        <?php if ($videos['video_option'] == 'link') { ?>
                          <p>Start time : <?php echo date('h:i a', strtotime($videos['start_time'])) ?></p>
                          <p>End time : <?php echo date('h:i a', strtotime($videos['end_time'])) ?></p>
                          <? } ?>
                          <hr>
                          <form action="" method="post">
                            <input type="hidden" name="edit_post" value="<?php echo $videos['id'] ?>">
                            <button href="" class="btn btn-primary">Manage</button>
                          </form>

                          <form action="" method="post" class="delete_post">
                            <input type="hidden" name="delete_post" value="<?php echo $videos['id'] ?>">
                            <button type="button" class="btn btn-danger delete_post_btn">Delete</button>
                          </form>

                          <form action="" method="post" class="change_status">
                          </form>
                    </div>
                  </td>
                  <td class="incomplete">
                    <div class="incomplete_fields_detail">
                      <?php

                      if (empty($videos['post_id'])) {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Select Event - Empty</span><br>';
                      }
                      if (empty($videos['user_id'])) {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Select Member - Empty</span><br>';
                      }
                      if (empty($videos['video_option'])) {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Package - Not Selected</span><br>';
                      }
                      if (empty($videos['event_name']) && $videos['video_option'] == 'link') {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Presentation Title - Empty</span><br>';
                      }
                      if (empty($videos['event_description']) ) {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Company Description - Empty</span><br>';
                      }
                      if (empty($videos['event_location']) && $videos['video_option'] == 'link') {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Location - Empty</span><br>';
                      }
                      if (empty($videos['thumb_booth'])) {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Thumbnail for Supplier Card - Empty</span><br>';
                      }
                      if (empty($videos['start_time']) && $videos['video_option'] == 'link') {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Choose Webinar Slot of Start Time - Empty</span><br>';
                      }
                      if (empty($videos['end_time']) && $videos['video_option'] == 'link') {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Choose Webinar Slot of End Time - Empty</span><br>';
                      }
                      if (empty($videos['video_link']) && $videos['video_option'] == 'link') {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Presentation Link - Empty</span><br>';
                      }
                      if (empty($videos['presentation_pdf_link']) && $videos['video_option'] == 'link') {
                        echo '<span style="color:red;" class="glyphicon glyphicon-remove"></span><span>Presentation PDF Link - Empty</span><br>';
                      }
                      ?>
                    </div>
                  </td>
                  <td class="comments-td" style="position: relative;">
                    <?php
                    //$commentSql = "SELECT id, user_id, comments AS comment_body, comment_by AS user_name, date AS comment_date FROM supplier_comments WHERE user_id = '" . $videos['user_id'] . "' ORDER BY `supplier_comments`.`id` DESC LIMIT 2";
                     $commentSql = "SELECT id, user_id, comments AS comment_body, comment_by AS user_name, date AS comment_date FROM supplier_comments WHERE user_id = '" . $videos['user_id'] . "' AND post_id = '" . $videos['post_id'] . "' ORDER BY `supplier_comments`.`id` DESC LIMIT 2";
                    $commentResult = mysql_query($commentSql);
                    $rowCount = mysql_num_rows($commentResult);
                    $currentRow = 0;
                    while ($commentRow = mysql_fetch_assoc($commentResult)) {
                      $currentRow++; ?>
                      <div class="scrollable_comment">
                        <b><?php echo $commentRow['user_name']; ?>: </b>
                        <span class="<?php echo ($currentRow === 1) ? 'updated-comment-text' : ''; ?>">
                          <?php echo $commentRow['comment_body']; ?>
                        </span>
                        <form action="" method="post" class="delete_form" onclick="return confirmDelete(event, '<?php echo $commentRow['id']; ?>');">
                          <input type="hidden" name="cid" value="<?php echo $commentRow['id']; ?>">
                          <i class="fa fa-trash"></i>
                        </form>
                        <br>
                        <div class="d-flex">
                          <?php if ($currentRow === $rowCount && $currentRow == 2) { ?>
                            <span>
                              <small class="btn-link more-comments" data-uid="<?= $videos['user_id'] ?>" data-pid="<?= $videos['post_id'] ?>">
                                view more comments
                              </small>
                            </span>
                          <?php } ?>
                          <span class="float-right"><b><?php echo !empty($commentRow['comment_date']) ? date("d-M-Y", strtotime($commentRow['comment_date'])) : 'Unknown Date'; ?></b></span>
                        </div>
                        <hr>
                      </div>

                    <?php } ?>
                    <textarea data-id="<?php echo $videos['user_id']; ?>" data-pid="<?= $videos['post_id'] ?>" data-type="comment" class="comments form-control" rows="3" placeholder="Write a comment..." style="position: absolute;bottom: 5px;width: calc(100% - 15px);"></textarea>
                  </td>
                  <td class="st-labels">
                    <div class="status-labels">
                      <?php
                      // if ($_SERVER['REMOTE_ADDR'] === '49.43.234.189') { echo "Your IP only"; }
                      if ($videos['staus'] == '1') {
                        echo '<span style="font-size: 100%;" class="label label-warning">' . $inComplet . '</span>';
                      } elseif ($videos['staus'] == '2') {
                        echo '<span style="font-size: 100%;" class="label label-success">' . $complet . '</span>';
                      }
                      echo '<span style="margin: 0 2px;"></span>';
                      $packages = mysql_fetch_array(mysql_query(" SELECT packages_section FROM `supplier_registration_form` WHERE user_id = " . $videos['user_id'] . " AND event_id = " . $videos['post_id']));
                      $packages = $packages['packages_section'];
                      $start_datetime = strtotime($videos['start_date'] . ' ' . $videos['start_time']);
                      $end_datetime = strtotime($videos['end_date'] . ' ' . $videos['end_time']);
                      $current_time = time();
                      //if ($videos['video_option'] == 'link' && $packages != 'SuperBooth Package') {
                        // Draft
                      if ($videos['video_option'] == 'link'){
                        echo '<span style="font-size: 100%;" class="label label-primary">Presentation</span>';
                      } //elseif ($videos['video_option'] == 'none' && $packages != 'SuperBooth Package') {
                        // Published
                        elseif ($videos['video_option'] == 'none') {
                        echo '<span style="font-size: 100%;" class="label label-info">Desktop</span>';
                      } //elseif ($packages != '' && $packages == 'SuperBooth Package' || $videos['video_option'] == 'superbooth') {
                        elseif ($videos['video_option'] == 'superbooth') {
                        echo '<span style="font-size: 100%;" class="label label-purple">SuperBooth</span>';
                      }elseif($videos['video_option'] == 'other'){
                        echo '<span style="font-size: 100%;background-color:#333;" class="label">Other</span>';
                      }
                      echo '<span style="margin: 0 2px;"></span>';
                      ?>
                      <div class="labels-container" id="labelsContainer<?= $videos['user_id'] ?>">
                        <?php
                        $supplierLabel_query = mysql_query("(SELECT srf.user_id, srf.add_on, srf.event_id, sl.id, sl.text_label, sl.color_code, sl.type FROM supplier_registration_form srf LEFT JOIN supplier_labels sl ON srf.user_id = sl.user_id AND srf.event_id = sl.post_id WHERE srf.user_id = " . (int)$videos['user_id'] . " AND srf.event_id = " . (int)$videos['post_id'] . " ) UNION ALL (SELECT sl.user_id, NULL AS add_on, NULL AS event_id, sl.id, sl.text_label, sl.color_code, sl.type FROM supplier_labels sl LEFT JOIN supplier_registration_form srf ON sl.user_id = srf.user_id AND sl.post_id = srf.event_id WHERE (srf.user_id IS NULL OR srf.event_id IS NULL) AND sl.user_id = " . (int)$videos['user_id'] . " AND sl.post_id = " . (int)$videos['post_id'] . ") ORDER BY id ASC");
                        
                        while ($labelRow = mysql_fetch_assoc($supplierLabel_query)) {
                          if (!empty($labelRow['text_label'])) {
                              echo '<div class="label-wrapper"><div class="label-item label" data-sl-id="' . htmlspecialchars($labelRow['id']) . '" style="background-color: ' . htmlspecialchars($labelRow['color_code']) . ';"><span class="label-title">' . htmlspecialchars(strtolower($labelRow['text_label'])) . '</span></div>';
                          if ($labelRow['type'] == 'label') {
                              echo '<button class="edit-btn" data-sl-id="' . htmlspecialchars($labelRow['id']) . '">✎</button>';
                          }
                          if ($labelRow['type'] == 'addon') {
                              echo '<button class="deleteBtn" data-sl-id="' . htmlspecialchars($labelRow['id']) . '"><i class="fa fa-trash"></i></button>';
                          }
                              echo '</div>';
                          } elseif (!empty($labelRow['add_on'])) {
                              $addOns = array_map('trim', explode(',', $labelRow['add_on']));
                              foreach ($addOns as $addOn) {
                                    $colorQuery = mysql_fetch_assoc(mysql_query( "SELECT color_code FROM add_ons WHERE name = '$addOn'"));
                                  echo '<div class="label-wrapper"><div class="label-item label" data-sl-id="' . htmlspecialchars($labelRow['id']) . '" style="background-color: ' . htmlspecialchars($colorQuery['color_code']) . ';"><span class="label-title">' . htmlspecialchars(strtolower($addOn)) . '</span></div>';
                         if ($labelRow['type'] == 'label') {
                              echo '<button class="edit-btn" data-sl-id="' . htmlspecialchars($labelRow['id']) . '">✎</button>';
                          }
                          if ($labelRow['type'] == 'addon') {
                              echo '<button class="deleteBtn" data-sl-id="' . htmlspecialchars($labelRow['id']) . '"><i class="fa fa-trash"></i></button>';
                          }

                                  echo '</div>';
                              }
                          }
                      
                         
                      
                          //echo '</div>';
                      }
                      
                        ?>
                      </div>
                      <button class="openModalBtn btn btn-default" data-row-id="<?= $videos['user_id'] ?>"
                        data-post-id="<?= $videos['post_id'] ?>" style="width: 100%;">Assign Label</button>
                    </div>
                    <div id="labelModal<?= $videos['user_id'] ?>" data-backdrop="static" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" onclick="window.location.reload();">&times;</button>
                            <h4 class="modal-title">Select/Create Label</h4>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="labelTitle">Select or Enter a New Label Text</label>
                              <div class="custom-dropdown">
                                <input type="text" id="labelTitle" class="form-control"
                                  placeholder="Start typing or select..." autocomplete="off">
                                <div id="dropdownSuggestions" class="suggestions-container" style="display: none;">
                                </div>
                              </div>
                            </div>
                            <style>
                              .custom-dropdown {
                                position: relative;
                              }

                              .suggestions-container {
                                position: absolute;
                                top: 100%;
                                left: 0;
                                width: 100%;
                                border: 1px solid #ccc;
                                border-top: none;
                                max-height: 600px;
                                z-index: 10;
                                background-color: #f5f5f5;
                              }

                              .suggestion-item {
                                padding: 8px;
                                cursor: pointer;
                                margin: 5px;
                              }

                              .suggestion-item:hover {
                                background-color: #f1f1f1;
                              }

                              .suggestion-item:active {
                                background-color: #ddd;
                              }
                            </style>
                            <div class="form-group">
                              <div class="disapper-grid">
                                <label>Select a color</label>
                                <div class="color-grid">
                                  <!-- Dynamic color boxes -->
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button id="deleteBtn" class="btn btn-danger pull-left">Delete</button>
                            <button id="saveBtn" class="btn btn-primary pull-right">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="attend-td">
                    <?/*<div class="attendee_grid">
                    <?php
                    $supplier_id = $videos['user_id'];
                   
                    $attending_staff_sql = mysql_query("
                                                      SELECT id, staff_id, supplier_id, post_id, first_name, last_name , company, status
                                                      FROM attending_staff_attendance 
                                                      JOIN users_data ON user_id = staff_id 
                                                      WHERE supplier_id = '" . $supplier_id . "' 

                                                      AND post_id='" . $_GET['post_id'] . "' ");

                    $attending_staff_sql_invites = mysql_query("SELECT * FROM attending_staff_attendance WHERE supplier_id = '" . $supplier_id . "' AND post_id='" . $_GET['post_id'] . "'  AND staff_id='0'");
                                                
                      $staff_names = array();    
                    if (mysql_num_rows($attending_staff_sql) > 0 || mysql_num_rows($attending_staff_sql_invites) > 0 ) {
                      while ($staff = mysql_fetch_assoc($attending_staff_sql)) {
                        $staff_mainid = $staff['id'];
                        $staff_id = $staff['staff_id'];
                        $suppliers_id = $staff['supplier_id'];
                        $post_id = $staff['post_id'];
                        $staff_name = ($staff['first_name'] != '') ? $staff['first_name'] . " " . $staff['last_name'] : $staff['company'];
                        $staff_name = htmlspecialchars($staff_name);
                        $sqlstaffname = mysql_fetch_assoc(mysql_query("SELECT * FROM `attending_supplier_staff_registration` WHERE supplier_id='$suppliers_id' AND post_id=$post_id AND user_id='$staff_id' "));
                        if($staff_name=="" && $sqlstaffname['full_name']!=""){
                           $staff_name = $sqlstaffname['full_name'];
                        }
                        $staffstatus = $staff['status'];
                        if($staffstatus == 'Completed' ){
                        $staff_names[] = " 
                                        <span class='attendee_staff_edit_class'>
                                          $staff_name ($staffstatus)
                                          <a href='https://ww2.managemydirectory.com/admin/go.php?widget=attending-staff-view-edit&supplier_id=$suppliers_id&post_id=$post_id&staff_id=$staff_id' target='_blank'>
                                            <i class='fa fa-pencil' aria-hidden='true'></i>
                                          </a>
                                          <a class='attendeedeleteBtn' 
                                             data-attendersupplier='$suppliers_id' 
                                             data-attenderpost='$post_id' 
                                             data-attenderstaff='$staff_id'>
                                            <i class='fa fa-trash' aria-hidden='true' style='color:red;'></i>
                                          </a>
                                        </span>";

 
                        } else {
                          $staff_names[] = "<span class='attendee_staff_edit_class'>$staff_name ($staffstatus)</span>";
                        }
                      }
                      while ($staffinvites = mysql_fetch_assoc($attending_staff_sql_invites)) { 

                        if ($staffinvites['staff_email'] != 'Not Required - We do not need this member'):
                        if($staff_id!=''){ ?>
                            <span class='attendee_staff_edit_class'>
                            <?php echo $staffinvites['staff_email'] . ' (' . $staffstatus . ')'; ?>
                            
                            <a href='https://ww2.managemydirectory.com/admin/go.php?widget=attending-staff-view-edit&supplier_id=<?php echo $suppliers_id; ?>&post_id=<?php echo $post_id; ?>&staff_id=<?php echo $staff_id; ?>' target='_blank'>
                              <i class='fa fa-pencil' aria-hidden='true'></i>
                            </a>
                            
                            <a 
                              class='attendeedeleteBtn' 
                              data-attendersupplier='<?php echo $suppliers_id; ?>' 
                              data-attenderpost='<?php echo $post_id; ?>' 
                              data-attenderstaff='<?php echo $staff_id; ?>'
                              style='cursor:pointer;'>
                              <i class='fa fa-trash' aria-hidden='true' style='color:red;'></i>
                            </a>
                          </span>
                        <?php }else{ ?>
                        <span class='attendee_staff_edit_class'>
                            <?php echo $staffinvites['staff_email'] . ' (TBC)'; ?>

                       <?php } ?>

<?php endif; ?>


                    <?php }
                      echo implode(" ", $staff_names);
                    } else {
                      echo "No attending staff";
                    }
                    ?>
                    </div>*/?>
                    <div class="attendee_grid">
                      <?php
                      $supplier_id = $videos['user_id'];

                      $attending_staff_sql = mysql_query("
                        SELECT id, staff_id, supplier_id, post_id, first_name, last_name , company, status
                        FROM attending_staff_attendance 
                        JOIN users_data ON user_id = staff_id 
                        WHERE supplier_id = '$supplier_id' AND post_id='" . $_GET['post_id'] . "'");

                      $attending_staff_sql_invites = mysql_query("
                        SELECT * FROM attending_staff_attendance 
                        WHERE supplier_id = '$supplier_id' AND post_id='" . $_GET['post_id'] . "' AND staff_id='0'");

                      $staff_names = array();    

                      // First Loop: Registered Staff
                      if (mysql_num_rows($attending_staff_sql) > 0) {
                        while ($staff = mysql_fetch_assoc($attending_staff_sql)) {
                          $staff_mainid = $staff['id'];
                          $staff_id = $staff['staff_id'];
                          $suppliers_id = $staff['supplier_id'];
                          $post_id = $staff['post_id'];
                          $staff_name = ($staff['first_name'] != '') ? $staff['first_name'] . " " . $staff['last_name'] : $staff['company'];
                          $staff_name = htmlspecialchars($staff_name);

                          // Fallback for empty name
                          $sqlstaffname = mysql_fetch_assoc(mysql_query("SELECT * FROM `attending_supplier_staff_registration` WHERE supplier_id='$suppliers_id' AND post_id=$post_id AND user_id='$staff_id' "));
                          if ($staff_name == "" && $sqlstaffname['full_name'] != "") {
                            $staff_name = $sqlstaffname['full_name'];
                          }

                          $staffstatus = $staff['status'];

                          if ($staffstatus == 'Completed') {
                            $staff_names[] = "
                              <span class='attendee_staff_edit_class'>
                                $staff_name ($staffstatus)
                                <a href='https://ww2.managemydirectory.com/admin/go.php?widget=attending-staff-view-edit&supplier_id=$suppliers_id&post_id=$post_id&staff_id=$staff_id' target='_blank'>
                                  <i class='fa fa-pencil' aria-hidden='true'></i>
                                </a>
                                <a class='attendeedeleteBtn' 
                                   data-attendersupplier='$suppliers_id' 
                                   data-attenderpost='$post_id' 
                                   data-attenderstaff='$staff_id'
                                   style='cursor:pointer;'>
                                  <i class='fa fa-trash' aria-hidden='true' style='color:red;'></i>
                                </a>
                              </span>";
                          } else {

                            $applicationTokenQuery2 = mysql_query("SELECT token FROM create_application_pages WHERE event_id = '".$post_id."' AND `type` = 'registration form'");
                            $applicationTokenResult2 = mysql_fetch_assoc($applicationTokenQuery2);
                            $applicationtoken2 = $applicationTokenResult2['token'];
                            $attendingStaff2 = mysql_query("SELECT token from attending_staff_attendance WHERE staff_id =".$staff_id. " AND post_id = ".$post_id );
                            $staff2 = mysql_fetch_assoc($attendingStaff2);
                            $attendingUrl2 = "https://www.motiv8search.com/attending-supplier-staff-registration?ref=".$applicationtoken2."&token=".$staff2['token'];

                            $staff_names[] = "
                                <span class='attendee_staff_edit_class'>
                                    $staff_name ($staffstatus)";
                                    
                            //if ($staff['token'] != '') {
                                $staff_names[] .= "
                                    &nbsp;<a style='display: inline-block;' data-url='$attendingUrl2' href='$attendingUrl2' data-post='$post_id' data-user='" . $staff_id . "' class='url-copy'><i class='fa fa-files-o' aria-hidden='true'></i> Copy Link</a>";
                           // }

                            $staff_names[] .= "</span>";

                            //$staff_names[] = "<span class='attendee_staff_edit_class'>$staff_name ($staffstatus)</span>";

                          }
                        }
                      }

                      // Second Loop: Email-only Invited Staff (No staff_id)
                      while ($staffinvites = mysql_fetch_assoc($attending_staff_sql_invites)) {
                        if ($staffinvites['staff_email'] != 'Not Required - We do not need this member') {

                            $staff_email = trim($staffinvites['staff_email']);
                            $sta_name = $staff_email; // default fallback

                            $get_user = mysql_fetch_assoc(mysql_query("
                            SELECT first_name, last_name, company
                            FROM users_data
                            WHERE email = '" . mysql_real_escape_string($staff_email) . "'
                            LIMIT 1
                            "));

                            $sta_name = $get_user
                            ? (
                                ($get_user['first_name'] != '' || $get_user['last_name'] != '')
                                    ? trim($get_user['first_name'] . ' ' . $get_user['last_name'])
                                    : (($get_user['company'] != '') ? $get_user['company'] : $staff_email)
                                )
                            : $staff_email;

                          $suppliers_id = $staffinvites['supplier_id'];
                          $post_id = $staffinvites['post_id'];
                          //$staff_email = htmlspecialchars($staffinvites['staff_email']);
                          $staffstatus = $staffinvites['status'];
                          $staff_mainid = $staffinvites['id']; // This ID can be used for deletion

                          $applicationTokenQuery = mysql_query("SELECT token FROM create_application_pages WHERE event_id = '".$post_id."' AND `type` = 'registration form'");
                          $applicationTokenResult = mysql_fetch_assoc($applicationTokenQuery);
                          $applicationtoken = $applicationTokenResult['token'];
                          $attendingStaff = mysql_query("SELECT token from attending_staff_attendance WHERE staff_email = '$staff_email' ");
                          $staff = mysql_fetch_assoc($attendingStaff);
                          $attendingUrl = "https://www.motiv8search.com/attending-supplier-staff-registration?ref=".$applicationtoken."&token=".$staff['token'];
                         
                            $staff_names[] = "
                                <span class='attendee_staff_edit_class'>
                                    $sta_name ($staffstatus)";
                                    
                            if ($staff['token'] != '') {
                                $staff_names[] .= "
                                    &nbsp;<a style='display: inline-block;' data-url='$attendingUrl' href='$attendingUrl' data-email='$staff_email' class='url-copy'><i class='fa fa-files-o' aria-hidden='true'></i> Copy Link</a>";
                            }

                            $staff_names[] .= "</span>";

                          
						}else{
							$staff_names[] = "
                            <span class='attendee_staff_edit_class'>
                              Not required
                              
                            </span>";
						}
                      }

                      // Output all
                      if (!empty($staff_names)) {
                        echo implode(" ", $staff_names);
                      } else {
                        echo "No attending staff";
                      }
                      ?>
                      </div>

                    <br>
                    <form id="packageForm" class="packageForm">
                      <div class="form-group">
                        <label>Package Limit</label>
                        <?php $retrivelimit = mysql_fetch_assoc(mysql_query("SELECT package_limit FROM supplier_registration_form WHERE user_id='$supplier_id' AND event_id='$post_id'")); ?>
                        <input type="number" class="form-control packageLimit" id="packageLimit" name="package_limit" 
                          data-user-id="<?php echo htmlspecialchars($supplier_id); ?>" 
                          data-post-id="<?php echo htmlspecialchars($post_id); ?>" 
                          value="<?php echo htmlspecialchars($retrivelimit['package_limit']); ?>">
                      </div>
                    </form>
                                        <script>
                      document.querySelectorAll('.packageForm').forEach(function(form) {
                        form.addEventListener('keydown', function(event) {
                          if (event.key === 'Enter') {
                            event.preventDefault();
                          }
                        });
                      });
                    </script>

                  </td>
                  <td class="equipment">
                    <?php
                    $equipment_sql = mysql_query("SELECT full_name, other_equipment, event_space, files FROM `attending_supplier_staff_registration` WHERE supplier_id = '" . $supplier_id . "' AND post_id='" . $_GET['post_id'] . "' AND is_event_coordinator = '1'");
                    $existing_equipment = '';
                    if (mysql_num_rows($equipment_sql) > 0) {
                      while ($equipment = mysql_fetch_assoc($equipment_sql)) {
                        $existing_equipment =  $equipment['other_equipment'];
                    ?>
                    <span class="bold">
                      Co-Ordinator: 
                    </span>
                    <span class="equipment-type"><?php echo $equipment['full_name'];  ?></span> 
                    <br>
                    <?php if(!empty($existing_equipment)){ ?>
                    <span class="bold">
                      Equipment:
                    </span>
                    <span class="equipment-type"><?php echo ucwords($equipment['other_equipment']);  ?>&nbsp;</span> 
                    <?php if(!empty($equipment['files'])){ ?>
                      <a href="<?php echo 'https://www.motiv8search.com/images/' . $equipment['files']; ?>" class="" target='_blank'><i class="fa fa-eye"></i>View Equipment</a>
                      <?php } ?>
                      <form action="https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=<?= $_GET['post_id'] ?>" method="POST" role="form">
                        <input type="hidden" name="supplier_id" value="<?= $supplier_id ?>">
                        <input type="hidden" class="existing-equipment" name="existing_equipment" value="<?= $existing_equipment ?>">
                        <div class="form-group">
                          <div class="col-md-10">
                            <textarea name="more_equipment" class="form-control textarea-equipment" placeholder="Add more equipment" rows="3" style="position: absolute;width: calc(100% - -24px);left: 0;top: 50px;"></textarea>
                          </div>
                        </div>
                      </form>
                    <?php } }
                    }
                    ?>
                  </td>
                </tr>
              <?php }
              ?>
            </tbody>
          </table>
          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Add new booth and presentation</h4>
                </div>
                <div class="modal-body">
                  <form action="" id="add_video">
                    <?php
                    if ($_GET['post_id'] != '') {
                      $get_posts_query = mysql_fetch_assoc(mysql_query("SELECT * FROM `data_posts` WHERE post_id = '" . $_GET['post_id'] . "'"));
                    ?>
                      <div class="form-group">
                        <label for="">Event</label>
                        <div class="form-control"><?= $get_posts_query['post_title'] ?></div>
                        <input type="hidden" name="post_id" value="<?= $get_posts_query['post_id'] ?>">
                      </div>
                      <? } else { ?>
                      <div class="form-group">
                        <label for="">Select Event</label>
                        <select name="post_id" id="select-event" class="form-control" required="">
                          <option value="">~ SELECT ~</option>
                          <?php
                          $get_posts_query = mysql_query("SELECT * FROM `data_posts` WHERE post_filename LIKE 'vforum/%'");
                          while ($posts = mysql_fetch_assoc($get_posts_query)) {
                            echo "<option value='" . $posts['post_id'] . "'>" . $posts['post_title'] . "</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <? } ?>
                      <div class="form-group">
                        <label for="">Choose Status </label>
                        <select data-placeholder="Choose Status" name="status" id="status_new" class="form-control">
                          <option value="1"><?= $inComplet ?></option>
                          <option value="2"><?= $complet ?></option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="bd-chained">Select Member </label>
                        <select data-placeholder="Search User" name="user_id" id="bd-chained" class="form-control" required>
                          <option value="">~ SELECT ~</option>
                          <?php
                          $get_users_query = mysql_query("SELECT `user_id`, `company` FROM `users_data`");
                          while ($user = mysql_fetch_assoc($get_users_query)) {
                            echo "<option value='" . $user['user_id'] . "'>" . $user['company'] . "</option>";
                          }
                          ?>
                        </select>
                        <span class="show_load">Please wait..</span>
                        <div class="clearfix"></div>
                        <div class="member-links">
                          <a href="#" class="open-logo btn btn-primary" target='_blank'>View Logo</a>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                      <div class="form-group">
                        <label for="">Package</label>
                        <div class="clearfix"></div>
                        <label class="radio-inline">
                          <input type="radio" name="video_option" id="inlineRadio1" value="link" required class="package-type"> Presentation
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="video_option" id="inlineRadio3" value="none" class="package-type"> Desktop
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="video_option" id="inlineRadio4" value="superbooth" class="package-type"> SuperBooth
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="video_option" id="inlineRadio5" value="other" class="package-type"> Other
                        </label>
                      </div>
                      <div class="form-group package-presentation">
                        <label for="">Presentation Title </label>
                        <input type="text" name="event_name" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="">Company Description </label>
                        <input type="text" name="event_description" class="form-control">
                      </div>

                      <div class="form-group package-presentation">
                        <label for="">Location</label>
                        <input type="text" name="event_location" id="google-input-lead" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="">Thumbnail for Supplier Card</label>
                        <input type="file" name="thumbnail_booth" class="form-control">
                      </div>

                      <!-- Presentation Time Slots -->
                      <div class="form-group package-presentation">
                        <label for="">Choose Webinar Slot</label>
                        <select name="time_sloat" class="form-control" id="time_sloat">
                          <?php
                          foreach ($time_sloats_arr as $key => $value) {
                            $sloat = date('H:i', $value['start_time']) . " - " . date('H:i', $value['end_time']);
                            echo  "<option value='" . $sloat . "'>" . $sloat . "</option>";
                          } ?>
                        </select>
                      </div>
                      <div class="form-group" style="display: none;">
                        <label for="">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= $post_start_date ?>">
                      </div>
                      <div class="form-group" style="display: none;">
                        <label for="">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= $post_expire_date ?>">
                      </div>
                      <div class="form-group package-presentation">
                        <label for="">Presentation link</label>
                        <input type="url" name="video_link" class="form-control">
                      </div>
                      <div class="form-group package-presentation">
                        <label for="">Replay Embed Code</label>
                        <textarea name="replay_embed" rows="5" class="form-control"></textarea>
                      </div>
                      <div class="form-group link-container package-presentation">
                        <label for="">Presentation PDF Link</label>
                        <input type="url" name="pdf_link" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Text on Border</label>
                        <input type="text" name="border_text" class="form-control" maxlength="20" value="Technical Workshop" placeholder="Technical Workshop">
                        <small class="text-muted">Max 20 characters</small>
                      </div>
                      <div class="form-group">
                        <label for="">Border Color</label>
                        <input type="color" name="border_color" class="form-control-color" value="#666" title="Choose border color">
                      </div>
                      <div class="form-group package-other" style="display:none;">
                        <div class="form-group">
                          <label for="">Time Slot</label>
                          <div class="row">
                            <div class="col-md-6">
                              <input type="time" name="start_time" class="form-control">
                            </div>
                            <div class="col-md-6">
                              <input type="time" name="end_time" class="form-control">
                            </div>
                          </div>
                          <small class="text-muted">15-minute intervals</small>
                        </div>

                        <div class="form-group">
                          <label for="">Location</label>
                          <input type="text" name="event_location" id="google-input-lead" class="form-control">
                        </div>

                        <div class="form-group">
                          <label for="">Button Text</label>
                          <input type="text" name="button_text" class="form-control"
                            value="Register to Attend">
                        </div>

                        <div class="form-group">
                          <label for="">Button URL</label>
                          <input type="url" name="button_url" class="form-control"
                            pattern="https?://.+">
                        </div>
                      </div>

                      <div class="form-group">
                        <button class="btn btn-primary" type="submit">Submit</button>
                      </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div id="comments_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                  <h4 class="modal-title" id="commentsModalLabel">Suppliers Comments</h4>
                </div>
                <div class="modal-body">
                  <div class="comment-box">
                    <img src="https://www.motiv8search.com/images/profile-profile-holder.png" alt="User Profile">
                    <div class="comment-content">
                      <div class="comment-header">
                        <h5>Ralph Edwards</h5>
                        <small>August 19, 2021</small>
                      </div>
                      <div class="comment-body">
                        <p>In mauris porttitor tincidunt mauris massa sit lorem sed scelerisque. Fringilla pharetra vel massa enim sollicitudin cras. At pulvinar eget sociis adipiscing eget donec ultricies nibh tristique.</p>
                      </div>
                      <div class="comment-footer d-none">
                        <span><i class="glyphicon glyphicon-thumbs-up"></i>5 Likes</span>
                        <span><i class="glyphicon glyphicon-comment"></i>3 Replies</span>
                        <a href="#">Reply</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <?php if (isset($_POST['edit_post'])): ?>
            <?php
            echo $post_id = $_POST['edit_post'];
            $get_post_sql = mysql_query("
                SELECT
                    lep.*,
                    dp.post_title,
                    dp.post_start_date,
                    dp.post_expire_date,
                    ud.first_name,
                    ud.last_name,
                    ud.company,
                    ud.user_id
                FROM
                    `live_events_posts` AS lep
                LEFT JOIN data_posts AS dp 
                ON
                    dp.post_id = lep.post_id
                LEFT JOIN users_data as ud ON lep.user_id = ud.user_id
                WHERE lep.id = " . $post_id . "
              ");
            $get_post = mysql_fetch_assoc($get_post_sql);
            
            $get_start_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_post['post_id'] . " AND `key` = 'start_time'");
            $get_start_time = mysql_fetch_assoc($get_start_time_sql);
            $start_time = $get_start_time['value'];

            $get_end_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_post['post_id'] . " AND `key` = 'end_time'");
            $get_end_time = mysql_fetch_assoc($get_end_time_sql);
            $end_time = $get_end_time['value'];
            $packages = mysql_fetch_array(mysql_query(" SELECT packages_section FROM `supplier_registration_form` WHERE user_id = " . $get_post['user_id'] . " AND event_id = " . $post_id));
            $packages = $packages['packages_section'];
            ?>
            <!-- Modal -->
  <div class="modal fade in d-flex justify-content-center align-items-center" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="false" data-backdrop="static" data-keyboard="false" style="display: block;">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="width: 900px;">
                  <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id=""></php>Edit Booth</h4>
                  </div>
                  <div class="modal-body">
                    <form action="" id="edit_video">

                      <div class="form-group">
                        <label for="">Event</label>
                        <div class="form-control"><?= $get_post['post_title'] ?></div>
                        <input type="hidden" name="post_id" value="<?= $get_post['post_id'] ?>">
                      </div>
                      <div class="form-group">
                        <label for="">Choose Status </label>
                        <select data-placeholder="Choose Status" name="staus" id="status_edit" class="form-control">
                          <option <?php if ($get_post['staus'] == '1') {
                                    echo 'selected';
                                  } ?> value="1"><?= $inComplet ?></option>
                          <option <?php if ($get_post['staus'] == '2') {
                                    echo 'selected';
                                  } ?> value="2"><?= $complet ?></option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="bd-chained-2">Select Member</label>
                        <select data-placeholder="Search User" name="user_id" id="bd-chained-2" class="form-control" required>
                          <option value="">~ SELECT ~</option>
                          <?php
                          $get_users_query = mysql_query("SELECT `user_id`, `company` FROM `users_data`");
                          while ($user = mysql_fetch_assoc($get_users_query)) {
                            $selected = "";
                            if ($get_post['user_id'] == $user['user_id']) {
                              $selected = "selected";
                            }
                            echo "<option " . $selected . " value='" . $user['user_id'] . "'>" . $user['company'] . "</option>";
                          }
                          ?>
                        </select>
                        <span class="show_load">Please wait..</span>
                        <div class="clearfix"></div>
                        <?php
                        $user_logo = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_photo` WHERE user_id = '" . $get_post['user_id'] . "' AND type = 'logo' LIMIT 1"));
                        ?>
                        <div class="member-links" <?php echo ($user_logo['file']) ? 'style="display:block;"' : ''; ?>>

                          <a href="<?php echo ($user_logo['file']) ? 'https://www.motiv8search.com/logos/profile/' . $user_logo['file'] : '#'; ?>" class="open-logo btn btn-primary" target='_blank'>View Logo</a>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                      <!-- Package Selection -->
                      <div class="form-group">
                        <?php 
                        $video_option = $get_post['video_option']; 

                        // Determine the checked state for each radio button
                        /*$is_link_checked = ($video_option == "link" && $packages !== 'SuperBooth Package' && $packages !== 'Desktop Package');
                        $is_none_checked = ($packages == 'Desktop Package' || $video_option == 'none' && $packages !== 'SuperBooth Package');
                        $is_superbooth_checked = ($packages == 'SuperBooth Package' || $video_option == 'superbooth' || $video_option == 'none' && $packages !== 'Desktop Package');
                        $is_other_checked = ($video_option == "other")*/;
                        $is_link_checked = ($video_option == "link");
                        $is_none_checked = ($video_option == "none");
                        $is_superbooth_checked = ($video_option == "superbooth");
                        $is_other_checked = ($video_option == "other");
                        ?>

                        <label for="">Package</label>
                        <div class="clearfix"></div>

                        <label class="radio-inline">
                            <input type="radio" <?= $is_link_checked ? "checked" : "" ?> name="video_option" value="link" required class="package-type"> Presentation
                        </label>

                        <label class="radio-inline">
                            <input type="radio" <?= $is_none_checked ? "checked" : "" ?> name="video_option" value="none" class="package-type"> Desktop
                        </label>

                        <label class="radio-inline">
                            <input type="radio" <?= $is_superbooth_checked ? "checked" : "" ?> name="video_option" value="superbooth" class="package-type"> SuperBooth
                        </label>

                        <label class="radio-inline">
                            <input type="radio" <?= $is_other_checked ? "checked" : "" ?> name="video_option" value="other" class="package-type"> Other
                        </label>
                      </div>


                      <div class="form-group package-presentation">
                        <label for="">Presentation Title </label>
                        <input type="text" name="event_name" class="form-control" value="<?php echo htmlspecialchars($get_post['event_name']) ?>">
                      </div>

                      <div class="form-group">
                        <label for="">Company Description </label>
                        <input type="text" name="event_description" class="form-control" value="<?php echo htmlspecialchars($get_post['event_description']) ?>">
                      </div>

                      <div class="form-group package-presentation package-other">
                        <label for="">Location</label>
                        <input type="text" name="event_location" class="form-control" value="<?php echo htmlspecialchars($get_post['event_location']) ?>">
                      </div>

                      <div class="form-group">
                        <label for="">Thumbnail for Supplier Card</label>
                        <input type="file" name="thumbnail_booth" class="form-control">
                        <?php if ($get_post['thumb_booth'] != ""): ?>
                          <a style="margin-top: 4px;" href="<?php echo $website_domain ?>/images/events/<?php echo strtolower($get_post['thumb_booth']) ?>" target="_blank" class="btn btn-primary">View image</a>
                        <?php endif ?>
                      </div>
                      <?php
                      $get_start_time_sql = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_post['post_id'] . " AND `key` = 'start_time'"));
                      $start_time = $get_start_time_sql['value'];
                      $get_end_time_sql = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_post['post_id'] . " AND `key` = 'end_time'"));
                      $end_time = $get_end_time_sql['value'];
                      $post_expire_date = date('Y-m-d', strtotime($get_post['post_expire_date']));
                      $post_start_date = date('Y-m-d', strtotime($get_post['post_start_date']));
                      $post_start_time_str = strtotime(str_replace(': ', ':', $start_time));
                      $post_end_time_str = strtotime(str_replace(': ', ':', $end_time));
                      $post_total_duration = $post_end_time_str - $post_start_time_str;
                      $presentation_duration_second = 2400;
                      $total_event_sloats = $post_total_duration / $presentation_duration_second;
                      $final_sloats = floor($total_event_sloats);
                      $time_sloats_arr = array();
                      $time_interval = 0;
                      for ($i = 0; $i < $final_sloats; $i++) {
                        $time_sloats_arr[$i]['start_time'] = $post_start_time_str + ($i * $presentation_duration_second) + $time_interval;
                        $time_sloats_arr[$i]['end_time'] = $time_sloats_arr[$i]['start_time'] + $presentation_duration_second;
                        $time_interval += 300;
                      }
                      ?>
                      <div class="form-group package-presentation">
                        <label for="">Choose Presentation sloat</label>
                        <select name="time_sloat" class="form-control" id="time_sloat_edit">
                          <?php
                          foreach ($time_sloats_arr as $key => $value) {
                            $sloat = date('H:i', $value['start_time']) . " - " . date('H:i', $value['end_time']);
                            if ($sloat == $get_post['start_time'] . ' - ' . $get_post['start_time']) {
                              $selected = 'selected';
                            } else {
                              $selected = '';
                            }
                            echo  "<option value='" . $sloat . "' $selected >" . $sloat . "</option>";
                          }
                          ?>
                        </select>
                      </div>

                      <div class="form-group" style="display: none;">
                        <label for="">Start Date</label>
                        <input type="date" name="start_date" id="start_date_edit" class="form-control" value="<?php echo $get_post['start_date'] ?>">
                      </div>
                      <div class="form-group" style="display: none;">
                        <label for="">End Date</label>
                        <input type="date" name="end_date" id="end_date_edit" class="form-control" value="<?php echo $get_post['end_date'] ?>">
                      </div>
                      <div class="form-group package-presentation">
                        <label for="">Presentation link</label>
                        <input type="url" name="video_link" value="<?php echo $get_post['video_link'] ?>" class="form-control">
                      </div>
                      <div class="form-group package-presentation">
                        <label for="">Replay Embed Code</label>
                        <textarea name="replay_embed" id="" rows="5" class="form-control"><?php echo $get_post['replay_embed'] ?></textarea>
                      </div>
                      <div class="form-group package-presentation">
                        <label for="">Presentation PDF Link</label>
                        <input type="url" name="pdf_link" class="form-control" value="<?php echo $get_post['presentation_pdf_link'] ?> ">
                      </div>
                      <div class="form-group">
                        <label for="">Text on Border</label>
                        <input type="text" name="border_text" class="form-control" maxlength="20" value="<?php echo $get_post['border_text']; ?>" placeholder="Technical Workshop">
                        <small class="text-muted">Max 20 characters</small>
                      </div>
                      <div class="form-group">
                        <label for="">Border Color</label>
                        <input type="color" name="border_color" class="form-control-color" value="<?php echo $get_post['border_color']; ?>" title="Choose border color">
                      </div>
                      <!-- Other Package Fields -->
                      <div class="form-group package-other" style="display:none;">
                        <div class="form-group">
                          <label for="">Time Slot</label>
                          <div class="row">
                            <div class="col-md-6">
                            <input type="text" name="start_time" value="<?php echo $get_post['start_time']; ?>" class="form-control">
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="end_time" value="<?php echo $get_post['end_time']; ?>" class="form-control">
                            </div>
                          </div>
                          <small class="text-muted">15-minute intervals</small>
                        </div>
                        <div class="form-group">
                          <label for="">Button Text</label>
                          <input type="text" name="button_text" class="form-control" value="<?php echo $get_post['button_text']; ?>">
                        </div>
                        <div class="form-group">
                          <label for="">Button URL</label>
                          <input type="url" name="button_url" class="form-control" value="<?php echo $get_post['button_url']; ?>" pattern="https?://.+">
                        </div>
                      </div>
                      <div class="form-group">
                        <input type="hidden" name="video_id" value="<?php echo $post_id ?>">
                        <button class="btn btn-primary" type="submit">Update</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <script>
              $('#edit-modal').modal('show');
              $(document).ready(function() {
              $('#edit-modal').on('hidden.bs.modal', function () {
                  // Redirect to your desired page
                  window.location.href = "https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=<?php echo $_GET['post_id']; ?>"; // Change this path as needed
                  });
                $('#select-event-edit').change();
                setTimeout(function() {
                  $("#time_sloat_edit").val('<?php echo date('H:i', strtotime($get_post['start_time'])) . " - " . date('H:i', strtotime($get_post['end_time'])) ?>').trigger('change');
                  console.log('done');
                }, 3000);

              });
            </script>
          <?php endif ?>
        </div>
      </div>
    </div>

  </section>
  <script>
    $('#add_video').submit(function(event) {
      var form = $(this);
      form.find('button').prop('disabled', true);
      event.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        url: '<?php echo $website_domain ?>/add_event.php',
        type: 'POST',
        dataType: '',
        processData: false,
        contentType: false,
        data: formData,
        success: function(data) {
          console.log(data);
          location = location
        }
      });
    });
    $('#edit_video').submit(function(event) {
      event.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        url: '<?php echo $website_domain ?>/edit_event.php',
        type: 'POST',
        dataType: '',
        processData: false,
        contentType: false,
        data: formData,
        success: function(data) {
          location = location
          console.log(data);
        }
      });
    });
    $('#select-event').change(function(event) {
      var post_id = $(this).val();
      console.log(post_id);
      $.ajax({
        url: '<?php echo $website_domain ?>/api/widget/json/get/get_post_details_ajax',
        type: 'GET',
        dataType: 'json',
        data: {
          post_id: post_id
        },
        success: function(data) {
          $('#start_date').val(data.post_start_date);
          $('#end_date').val(data.post_expire_date);
          $('#time_sloat').html(data.time_sloats).prop('disabled', false);
        }
      })
    });
    $('#select-event-edit').change(function(event) {
      var post_id = $(this).val();
      console.log(post_id);
      $.ajax({
        url: '<?php echo $website_domain ?>/api/widget/json/get/get_post_details_ajax',
        type: 'GET',
        dataType: 'json',
        data: {
          post_id: post_id
        },
        success: function(data) {
          $('#start_date_edit').val(data.post_start_date);
          $('#end_date_edit').val(data.post_expire_date);
          $('#time_sloat_edit').html(data.time_sloats).prop('disabled', false);
        }
      })

    });
    $(function() {
      $(".delete_post_btn").click(function(event) {
        var form = $(this).closest('form');
        if (confirm("You really want to delete this Booth?")) {
          form.submit();
        }
      });
    });
    $(function() {
      $(".approve_btn").click(function(event) {
        var form_approve = $(this).closest('form');
        if (confirm("Are you sure you want to publish this Booth?")) {
          form_approve.submit();
        }
      });
    });
    $(function() {
      $(".reject_btn").click(function(event) {
        var form_reject = $(this).closest('form');
        if (confirm("Are you sure you want to mark this Booth as incomplete?")) {
          form_reject.submit();
        }
      });
    });
    $(document).on('change', '#bd-chained, #bd-chained-2', function(event) {
      var logo_this = $(this);
      logo_this.parent().find('.member-links').hide();
      logo_this.siblings('.show_load').show();
      logo_this.siblings('.show_load').text('Please wait..');
      var selectedMember = $(this).val();
      $.ajax({
        url: '<?php echo $website_domain ?>/api/widget/json/get/open_logo',
        type: 'POST',
        data: {
          user_id: selectedMember
        },
        dataType: 'json',
        success: function(data) {

          var logo = data.logo;
          if (logo) {

            logo_this.parent().find('.open-logo').attr('href', 'https://www.motiv8search.com/logos/profile/' + logo);
            logo_this.parent().find('.member-links').show();
            logo_this.siblings('.show_load').hide();
          } else {
            logo_this.siblings('.show_load').text('Logo NA');
          }


        }
      });
    });
    var packageType = $(".package-type:checked").val();
    if (packageType == "link") {
      $(".package-presentation").show();
      $(".package-desktop").hide();
    } else if (packageType == "none") {
      $(".package-desktop").show();
      $(".package-presentation").hide();
    }
    $("input[name='video_option']").click(function() {
      if ($(this).val() == "link") {
        $(".package-presentation").show();
        $(".package-desktop").hide();
      } else if ($(this).val() == "none") {
        $(".package-desktop").show();
        $(".package-presentation").hide();
      }
    });
  </script>
  <script>
    // script code for PID: 547 by vikash
    $(document).ready(function() {
      $('#comments_modal').modal('hide');
      $(document).on('click', '.more-comments', function() {
        let $element = $(this);
        let uid = $element.data('uid');
        let PID = $element.data('pid');
        const loadHtml = `
      <div class="loading-spinner text-center">
        <img src="/images/bars-loading.gif" alt="Loading..." style="width: 50px;">
        <p>Loading, please wait...</p>
      </div>`;
        $('#comments_modal').modal('show');
        $(`#comments_modal .modal-body`).html(loadHtml);
        $.ajax({
          type: "POST",
          url: "<?php echo $website_domain ?>/api/widget/json/get/get_suppliers_comments_ajax",
           data: {
                uid: uid,
                pid: PID
            },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              const details = response.data;
              console.log(details);
              let commentsHtml = '';

              details.forEach(detail => {
                const userProfile = "https://www.motiv8search.com/images/default_profile_photo.jpg";
                const commentDate = detail.comment_date || "Unknown Date";
                const userName = detail.user_name || "Anonymous User";
                const commentBody = detail.comment_body || "No content available.";
                const userId = detail.id || "0";

                commentsHtml += `
                        <div class="comment-box">
                            <img src="https://www.motiv8search.com/logos/profile/${userProfile}" alt="User Profile">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <h5>${userName}</h5>
                                    <small>${commentDate}</small>
                  <form action="" method="post" class="delete_form pull-right" onclick="return confirmDelete(event, '${userId}');">
                    <input type="hidden" name="cid" value="${userId}">
                    <i class="fa fa-trash" ></i>
                                    </form>
                                </div>
                                <div class="comment-body">
                                    <p>${commentBody}</p>
                                </div>
                                <div class="comment-footer d-none">
                                    <span><i class="glyphicon glyphicon-thumbs-up"></i> 0 Likes</span>
                                    <span><i class="glyphicon glyphicon-comment"></i> 0 Replies</span>
                                    <a href="#">Reply</a>
                                </div>
                            </div>
                        </div>
                      `;
              });
              $('#comments_modal .modal-body').html(commentsHtml);
              $('#comments_modal').modal('show');
            } else {
              $('#comments_modal').modal('hide');
              swal("Action Warning!", response.message, "warning");
            }
          },
          error: function() {
            $('#comments_modal').modal('hide');
            // Handle AJAX error
            swal("Action Failed!", "Failed to fetch details. Please try again.", "error");
          }
        });
      });
      $(document).on("blur", ".comments", function() {
        const $el = $(this);
        let updatedText = $el.val().trim();
        const type = $el.data("type");
        const id = $el.data("id");
        const PID = $el.data("pid");

        if (!id || !type) {
          swal("Action Failed!", "Invalid data. Please try again.", "error");
          return;
        }
        if (!updatedText) {
          swal("Warning!", "Comment cannot be empty or null.", "warning");
          return;
        }

        $el.prop("disabled", true);

        $.post(window.location.href, {
            id: id,
            content: updatedText,
            type: type,
            postID: PID
          })
          .then(function() {
            $el.val('');
            $el.prop("disabled", false);

            return swal({
              title: "Action Successful!",
              text: "Comment updated successfully.",
              icon: "success",
              buttons: {
                confirm: {
                  text: "OK",
                  className: "swal-button--confirm",
                }
              },
              closeOnConfirm: false
            });
          })
          .then(function(isConfirm) {
            if (isConfirm) {
              location.reload();
            }
          })
          .then(function() {
            updatedText = updatedText.substring(0, 45);
            const $commentSpan = $el.closest('td').find('.updated-comment-text');

            if ($commentSpan.length > 0) {
              $commentSpan.text(updatedText);
            } else {
              const adminName = "<?php echo $sess['admin_name']; ?>";
              const commentDate = new Date().toLocaleDateString("en-GB", {
                day: "2-digit",
                month: "short",
                year: "numeric"
              });

              $el.closest('td').append(`
                <span>
                  <b>${adminName}: </b><span class="updated-comment-text">${updatedText}</span>
                  <br>
                  <div class="d-flex">
                    <span class="float-right"><b>${commentDate}</b></span>
                  </div>
                  <hr>
                </span>
            `);
            }
          })
          .catch(function() {
            $el.prop("disabled", false);
            swal("Action Failed!", "Failed to update the comment. Please try again.", "error");
          });
      });
      $('.textarea-equipment').on('blur', function() {
        var moreEquipment = $(this).val();
        $(this).prop("disabled", true);
        var form = $(this).closest('form');
        var existingEquipment = form.find('input[name="existing_equipment"]').val();
        var supplierId = form.find('input[name="supplier_id"]').val();
        var postId = "<?= $_GET['post_id'] ?>";
        var combinedEquipment = existingEquipment;
        if (!moreEquipment) {
          swal("Warning!", "Equipment cannot be empty or null.", "warning");
          $(this).prop("disabled", false);
          return;
        }
        if (moreEquipment.trim() !== '') {
          combinedEquipment += (existingEquipment ? ", " : "") + moreEquipment;
        }
        form.find('input[name="existing_equipment"]').val(combinedEquipment);
        console.log(combinedEquipment);
        form.submit();
      });
    });

    function rgbToHex(rgb) {
      var rgbValues = rgb.match(/\d+/g);
      var hex = "#";
      for (var i = 0; i < 3; i++) {
        var hexValue = parseInt(rgbValues[i]).toString(16);
        hex += hexValue.length == 1 ? "0" + hexValue : hexValue;
      }
      return hex.toUpperCase();
    }
    $(document).ready(function() {
      const colors = [
        '#4B164C', '#FFC107', '#FF5722', '#4CAF50', '#2196F3', '#9C27B0',
        '#FFEB3B', '#8BC34A', '#03A9F4', '#673AB7', '#E91E63', '#FF9800',
        '#795548', '#607D8B', '#00BCD4', '#CDDC39', '#FF4081', '#BDBDBD'
      ];
      let selectedColor = '';

      function initializeColorGrid(modal, labelsContainer) {
        const colorGrid = modal.find('.color-grid');
        colorGrid.empty();
        let usedColors = new Set();
        $('.labels-container .label-item').each(function() {
          usedColors.add(rgbToHex($(this).css('background-color')));
        });
        colors.forEach(color => {
          const colorBox = $('<div>')
            .addClass('color-box')
            .css('background-color', color)
            .data('color', color)
            .click(function() {
              if ($(this).hasClass('disabled')) return;
              colorGrid.find('.color-box').removeClass('selected');
              $(this).addClass('selected');
              selectedColor = $(this).data('color'); // Store the selected color as HEX
            });
          if (usedColors.has(color)) {
            colorBox.addClass('disabled').css('cursor', 'not-allowed')
              .append('<div class="color-overlay">❌</div>');
          }

          colorGrid.append(colorBox);
        });
      }
      $('tbody').on('click', '.openModalBtn, .edit-btn', function() {
        const rowId = $(this).data('row-id') || $(this).closest('.labels-container').attr('id').replace('labelsContainer', '');
        const postId = $(this).data('post-id');
        const modal = $('#labelModal' + rowId);
        const labelsContainer = $('#labelsContainer' + rowId);
        let currentEditingLabel = null;
        let selectedColor = '';
        initializeColorGrid(modal, labelsContainer);
        const labelTitleInput = modal.find('#labelTitle');
        const deleteBtn = modal.find('#deleteBtn');
        if ($(this).hasClass('edit-btn')) {
          currentEditingLabel = $(this).closest('.label-wrapper').find('.label-item');
          const labelTitle = currentEditingLabel.find('.label-title').text();
          const labelColor = currentEditingLabel.css('background-color');
          labelTitleInput.val(labelTitle);
          selectedColor = rgbToHex(labelColor);
          modal.find('.color-box').removeClass('selected').each(function() {
            if (rgbToHex($(this).css('background-color')) === selectedColor) {
              $(this).addClass('selected');
            }
          });
          modal.find('#saveBtn').text('Update');
          deleteBtn.prop('disabled', false);
          modal.find('.modal-title').text('Edit Label');
        } else {
          selectedColor = ''; // Reset selectedColor for new label
          labelTitleInput.val('');
          modal.find('.color-box').removeClass('selected'); // Clear previous selection
          modal.find('#saveBtn').text('Save');
          deleteBtn.prop('disabled', true);
          modal.find('.modal-title').text('Select/Create Label');
          const userId = rowId;
          const suggestionsContainer = modal.find('#dropdownSuggestions');
          let existingLabels = [];
          let existingAddOns = [];
          labelsContainer.find('.label-title').each(function() {
            existingLabels.push($(this).text().trim());
            existingAddOns.push($(this).text().trim());
          });
          fetchLabelsAndAddOnsData();

          function fetchLabelsAndAddOnsData() {
            $.ajax({
              url: 'https://www.motiv8search.com/api/widget/html/get/fetch_labels?t=' + new Date().getTime(),
              type: 'GET',
              dataType: 'json',
              cache: false,
              timeout: 10000,
              success: function(data) {
                if (!data || !data.labelsWithColors || !data.addOnData) {
                  console.error('❌ Invalid JSON structure received');
                  return;
                }
                window.labelsWithColors = data.labelsWithColors;
                window.addOnData = data.addOnData;
                attachSuggestionHandlers();
                if (labelTitleInput.is(":focus")) {
                  showSuggestions("", true);
                }
              },
              error: function(xhr, status, error) {
                console.error('❌ API Error:', status, error);
              }
            });
          }

          function attachSuggestionHandlers() {
            labelTitleInput.off('input focus')
              .on('input', function() {
                const inputValue = $(this).val().trim();
                showSuggestions(inputValue, false);
              })
              .on('focus', function() {
                showSuggestions("", true);
              });
          }

          function showSuggestions(value, showAll) {
            value = value.trim();
            suggestionsContainer.empty(); // Clear previous suggestions

            if (!window.labelsWithColors || !window.addOnData) {
              fetchLabelsAndAddOnsData();
              return;
            }
            const filteredLabels = Object.keys(window.labelsWithColors).filter(label =>
              (showAll || label.toLowerCase().includes(value.toLowerCase())) &&
              !existingLabels.includes(label)
            );
            const filteredAddOns = Object.keys(window.addOnData).filter(name =>
              (showAll || name.toLowerCase().includes(value.toLowerCase())) &&
              !existingAddOns.includes(name)
            );
            if (filteredLabels.length === 0 && filteredAddOns.length === 0) {
              suggestionsContainer.hide();
              return;
            }
            filteredLabels.forEach(label => {
              $('<div class="suggestion-item"></div>')
                .text(label)
                .css({
                  'background-color': window.labelsWithColors[label],
                  'color': '#fff',
                  'padding': '5px',
                  'border-radius': '4px'
                })
                .data({
                  'color': window.labelsWithColors[label],
                  'label-type': 'label'
                })
                .on('click', function() {
                  labelTitleInput.val($(this).text())
                    .data('selected-color', $(this).data('color'))
                    .data('selected-label-type', 'label');
                  selectedColor = $(this).data('color'); // Ensure the color is stored globally
                  suggestionsContainer.empty().hide();
                })
                .appendTo(suggestionsContainer);
            });
            filteredAddOns.forEach(name => {
              $('<div class="suggestion-item"></div>')
                .text(name)
                .css({
                  'background-color': window.addOnData[name] || '#ddd',
                  'color': '#fff',
                  'padding': '5px',
                  'border-radius': '4px'
                })
                .data({
                  'color': window.addOnData[name],
                  'label-type': 'addon'
                })
                .on('click', function() {
                  labelTitleInput.val(name)
                    .data('selected-color', $(this).data('color'))
                    .data('selected-label-type', 'addon');
                  selectedColor = $(this).data('color');
                  suggestionsContainer.empty().hide();
                })
                .appendTo(suggestionsContainer);
            });
            suggestionsContainer.show();
          }
        }
        modal.find('.color-box').off('click').on('click', function() {
          modal.find('.color-box').removeClass('selected');
          $(this).addClass('selected');
          selectedColor = rgbToHex($(this).css('background-color'));
          labelTitleInput.data('selected-color', selectedColor);
        });
        modal.find('#saveBtn').off('click').on('click', function() {
          const title = labelTitleInput.val().trim();
          let colorCode = selectedColor || labelTitleInput.data('selected-color') || '';
          if (!title) {
            swal("Oops!", "Please enter a label name.", "warning");
            return;
          }
          if (!colorCode) {
            swal("Oops!", "Please select a color for the label.", "warning");
            return;
          }
          const postData = {
            action: currentEditingLabel ? 'update' : 'create',
            text_label: title,
            color_code: colorCode,
            label_type: labelTitleInput.data('selected-label-type') || 'label',
            user_id: rowId,
            post_id: postId
          };
          if (currentEditingLabel) {
            const labelId = currentEditingLabel.data('sl-id');
            postData.label_id = labelId;
          }
          $.ajax({
            url: '<?php echo $website_domain ?>/api/widget/html/get/label_ajax',
            type: 'POST',
            data: postData,
            dataType: 'text',
            success: function(response) {
              response = response.trim();
              if (response === "Success") {
                swal("Success!", "Label saved successfully.", "success").then(() => {
                  modal.modal('hide');
                  location.reload();
                });
              } else {
                swal("Error!", "Could not save label: " + response, "error");
              }
            },
            error: function() {
              swal("Error!", "Something went wrong. Please try again.", "error");
            }
          });
        });
        modal.find('#deleteBtn').off('click').on('click', function() {
          if (currentEditingLabel) {
            swal({
              title: "Are you sure?",
              text: "Are you sure you want to delete this label?",
              icon: "warning",
              buttons: ["Cancel", "Yes, delete it!"],
              dangerMode: true,
            }).then((isConfirm) => {
              if (!isConfirm) return;
              const labelId = currentEditingLabel.data('sl-id');
              $.ajax({
                  url: '<?php echo $website_domain ?>/api/widget/html/get/label_ajax',
                  type: 'POST',
                  data: {
                    action: 'delete',
                    label_id: labelId
                  },
                  dataType: 'json'
                })
                .then((response) => {
                  if (response.status === "Success") {
                    currentEditingLabel.closest('.label-wrapper').remove();
                    modal.modal('hide');
                    return swal("Deleted!", "Your label has been deleted.", "success");
                  } else {
                    return swal("Error!", response.message || "Could not delete label.", "error");
                  }
                })
                .fail(() => {
                  swal("Error!", "Could not delete label. Please try again.", "error");
                });
            });
          }
        });
        modal.modal('show');
      });
    });
    $('.deleteBtn').on('click', function() {
      const button = $(this);
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this label?",
        icon: "warning",
        buttons: ["Cancel", "Yes, delete it!"],
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          const labelId = button.data('sl-id');
          $.post('<?php echo $website_domain ?>/api/widget/html/get/label_ajax', {
              action: 'delete',
              label_id: labelId
            })
            .done(function() {
              button.closest('.label-wrapper').remove();
              swal("Deleted!", "Your label has been deleted.", "success");
            });
        }
      });
    });
    $('#labelModal<?= $videos['user_id'] ?>').modal({
      backdrop: 'true',
      keyboard: true
    });
    $(document).ready(function() {
      $('#labelSelect').change(function() {
        var selectedValue = $(this).val();
        if (selectedValue) {
          $('#labelTitle').val(selectedValue);
        }
      });
    });
    $('.attendeedeleteBtn').on('click', function() {
    const btn = $(this);
    const supplier_id = btn.data('attendersupplier');
    const post_id = btn.data('attenderpost');
    const staff_id = btn.data('attenderstaff') || 0;
    var member_name = "<?php echo $loggedname; ?>";
    var member_email = "<?php echo $loggeduser; ?>";

    swal({
      title: "Are you sure?",
      text: "Are you sure you want to delete this Attendee?",
      icon: "warning",
      buttons: ["Cancel", "Yes, delete it!"],
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.post('<?php echo $website_domain ?>/api/widget/html/get/attendee_delete_ajax', {
            action: 'delete',
            supplier_id,
            post_id,
            staff_id,
            member_name,
            member_email
            
          })
          .done(() => {
            swal("Deleted!", "Your Attendee has been deleted.", "success");
            btn.closest('.attendee_staff_edit_class').remove();
          })
          .fail(() => {
            swal("Error!", "Failed to delete attendee. Try again.", "error");
          });
      }
    });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const packageTypes = document.querySelectorAll('.package-type');
      const packageSections = {
        'link': document.querySelectorAll('.package-presentation'),
        'other': document.querySelectorAll('.package-other')
      };

      function togglePackageSections(selectedValue) {
        // Hide all package-specific sections
        Object.values(packageSections).forEach(sections => {
          sections.forEach(section => section.style.display = 'none');
        });
        if (packageSections[selectedValue]) {
          packageSections[selectedValue].forEach(section => {
            section.style.display = 'block';
          });
        }
      }
      packageTypes.forEach(radio => {
        radio.addEventListener('change', function() {
          togglePackageSections(this.value);
        });
      });
      const initialSelection = document.querySelector('.package-type:checked');
      if (initialSelection) {
        togglePackageSections(initialSelection.value);
      }
    });
    $(document).ready(function() {
      $('button[data-user-id]').click(function(event) {
        event.preventDefault();
        var userId = $(this).data('user-id');
        var target = $('#action-links-' + userId);
        if (target.is(':visible')) {
          target.hide();
        } else {
          $('[id^="action-links-"]').hide(); // Hide all others
          target.show();
        }
      });
    });
    $(document).on("click", ".delete_member_plugin", function(e) {
      e.preventDefault();
      var member_id = $(this).attr('attr-id');
      var member_name = "<?php echo $loggedname; ?>";
      var member_email = "<?php echo $loggeduser; ?>";
      swal({
        title: "Delete Member",
        text: "Deleting member can delete entire images and posts",
        icon: "warning",
        buttons: ["No, cancel", "Yes, continue"],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) {
          $.ajax({
              url: '<?php echo $website_domain ?>/api/widget/html/get/ajax-delete-member-suppliers-event',
              data: {
                id: member_id,
                name: member_name,
                useremail: member_email,
                admin:'<?php echo $loggeduser; ?>'
              },
              type: 'GET',
            })
            .done(function() {
              swal({
                title: "Final Confirmation",
                text: "Permanently delete this member? This action cannot be reversed.",
                icon: "warning",
                buttons: ["No, cancel", "Yes, continue"],
                dangerMode: true,
              }).then(function(finalResult) {
                if (finalResult) {
                  location.reload();
                } else {
                  swal.close();
                }
              });

            });

        } else {
          swal.close();
        }
      });
    });
    $(".popup, .popup-websites, .popup-large, .iframe-link").fancybox({
      type: "iframe",
      afterClose: function() {
        var loc = parent.location.href;
        if (loc.search('billingPromotions.php') >= 0) {
          parent.location.reload(true);
        }
      },
      autoDimensions: false,
      iframe: {
        css: {
          width: '100%',
          height: '100%',
          'border-radius': '3px 3px 4px 4px'
        }
      },
      buttons: [
        'fullScreen',
        'close'
      ]
    });
    $().fancybox({
      selector: '.popup',
      loop: true,
      type: "iframe",
      autoDimensions: false,
      idleTime: 99999,
      afterClose: function() {
        var loc = parent.location.href;
        if (loc.search('billingPromotions.php') >= 0) {
          parent.location.reload(true);
        }
      },
      iframe: {
        css: {
          width: '100%',
          height: '100%',
          padding: '20px 0 0 0',
          'border-radius': '3px 3px 4px 4px'
        }
      },
      buttons: [
        'fullScreen',
        'close'

      ]
    });
    $().fancybox({
      selector: '.popup-email',
      loop: true,
      type: "iframe",
      autoDimensions: false,
      idleTime: 99999,
      iframe: {
        css: {
          width: '100%',
          height: '100%',
          padding: '20px 0 0 0',
          'border-radius': '3px 3px 4px 4px',
          'max-width': '940px'
        }
      },
      buttons: [
        'fullScreen',
        'close'

      ]
    });
    $(".popup-wizard").fancybox({
      overlayOpacity: "0.7",
      type: "iframe",
      width: 800,
      height: 500,
      autoScale: true
    });
    $("#accountchange").fancybox({
      overlayOpacity: "0.7",
      width: "820",
      height: "500",
      autoScale: "true"
    });
    $(".cropme").fancybox({
      overlayOpacity: "0.7",
      type: "iframe",
      width: "900",
      height: "500",
      autoScale: "true"
    });
    $("#wizard").fancybox({
      overlayOpacity: "0.7",
      type: "iframe",
      width: "820",
      height: "500",
      autoScale: "true"
    });
    $("#signupbox").fancybox({
      overlayOpacity: "0.7",
      type: "iframe",
      autoScale: "false",
      width: "820",
      height: "500"
    });
    $("#newsletterbox").fancybox({
      overlayOpacity: "0.7",
      width: "820",
      height: "500"
    })
  </script>
  <style>
    .package-other .form-control-color {
      height: 38px;
      padding: 5px;
    }

    .text-muted {
      color: #6c757d;
      font-size: 0.9em;
    }
  </style>
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