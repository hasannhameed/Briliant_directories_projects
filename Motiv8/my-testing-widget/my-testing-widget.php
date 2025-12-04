<?php
//plugin labels
//$complet = 'Complete';
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
  $currentDate = date('Y-m-d H:i:s');
  if (isset($Id) && isset($content) && isset($type)) {
    $content = mysql_real_escape_string($content);
    if ($type === 'comment') {
      // $sql = "UPDATE users_data SET comments = '$content' WHERE user_id  = $Id";
      $sql = "INSERT INTO supplier_comments (user_id, comments, comment_by, `date`) VALUES ('$Id', '$content', '" . $sess['admin_name'] . "', '$currentDate') ";
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
  //echo "DELETE FROM create_application_pages WHERE id='$delete_btn'";

  if (mysql_query($deleteQuery) === TRUE) {
    //echo "Delete Successful";
  } else {
    echo "Error deleting data" . mysql_error();
  }
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

// $labels = [];
$query = mysql_query("SELECT DISTINCT text_label FROM supplier_labels ORDER BY text_label ASC");

while ($labelOption = mysql_fetch_assoc($query)) {
  $labels[] = $labelOption['text_label'];


}


// $labelsWithColors = [];
$result = mysql_query("SELECT text_label, color_code FROM supplier_labels");

while ($row = mysql_fetch_assoc($result)) {
  $labelsWithColors[$row['text_label']] = $row['color_code'];
}

?>
<section>
  <a href="/admin/go.php?widget=video-publishing-plugin" class="back-button"><i class="fa fa-reply"
      aria-hidden="true"></i> Back</a>
  <a href="https://www.motiv8search.com/<?= $get_post_details['post_filename'] ?>" class="view-post-button"
    target="_blank"><i class="fa fa-external-link"></i> View Event Page</a>
  <hr class="header-hr">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="row">
          <div class="col-lg-8 col-md-7">
            <h2 class="float-left" style="margin-bottom: 10px;"><?= $get_post_details['post_title'] ?></h2>
            <div class="clearfix"></div>
            <p style="display:none;"><strong>Start :</strong>
              <?php echo date('Y-m-d', strtotime($get_post_details['post_start_date'])) . " " . $start_time ?>
            </p>
            <div class="clearfix"></div>
            <p style="display:none;"><strong>End :</strong>
              <?php echo date('Y-m-d', strtotime($get_post_details['post_start_date'])) . " " . $end_time ?>
            </p>
          </div>
          <div class="col-lg-2 col-md-3">
            <input type="text" class="form-control" value="<?php echo $_POST['key'] ?>" placeholder="Search by keyword"
              name="key" id="event_searchInput">
          </div>
          <div class="col-lg-2 col-md-2">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">Add
              Supplier</button>
          </div>
        </div>
        <div class="clearfix"></div>
        <table class="table">
          <thead>
            <tr>
              <!-- <th>Event Detail</th> -->
              <th>Supplier</th>
              <th>Supplier Information</th>
              <th>Incomplete Fields</th>
              <th style="width: 300px;">Comments</th>
              <th style="width: 155px;">Labels</th>
              <th style="width: 289px;">Attending Staff <i class="fa fa-download" aria-hidden="true"></i>
              </th>
              <th>Event Equipment</th>
            </tr>
          </thead>
          <tbody id="eventTable">
            <?php
            // ini_set('display_errors', '1');
            // ini_set('display_startup_errors', '1');
            // error_reporting(E_ALL);
            
            if (isset($_POST)) {
              if (isset($_POST['delete_post'])) {
                $delete_post = mysql_query("DELETE FROM `live_events_posts` WHERE `live_events_posts`.`id` = " . $_POST['delete_post']);
                echo "<script>location = location</script>";
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
                    ud.token
                FROM
                    `live_events_posts` AS lep
                LEFT JOIN data_posts AS dp 
                ON
                    dp.post_id = lep.post_id
                LEFT JOIN users_data as ud ON lep.user_id = ud.user_id " . $where_str . $key_where_str . " ORDER BY id DESC
                ");



            while ($videos = mysql_fetch_assoc($get_videos_sql)) {
              $get_start_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $videos['post_id'] . " AND `key` = 'start_time'");
              $get_start_time = mysql_fetch_assoc($get_start_time_sql);
              $start_time = $get_start_time['value'];

              $get_end_time_sql = mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $videos['post_id'] . " AND `key` = 'end_time'");
              $get_end_time = mysql_fetch_assoc($get_end_time_sql);
              $end_time = $get_end_time['value'];

              ?>
                <tr>
                  <td class="Supplier">
                    <div class="video_detail">
                      <p><strong><?php echo $videos['first_name'] . " " . $videos['last_name'] ?></strong>
                      </p>
                      <?php if ($videos['company'] != ""): ?>
                          <p>Company : <?php echo $videos['company'] ?></p>
                      <?php endif ?>

                      <p>Email: <a href="mailto:<?= $videos['email'] ?>"><?= $videos['email'] ?></a></p>
                      <p>Member ID : <?php echo $videos['user_id'] ?></p>
                      <a href="https://www.motiv8search.com/login/token/<?= $videos['token'] ?>/home" target="_blank"><i
                          class="fa fa-external-link fa-fw"></i> Login as Member</a>
                      <br>
                      <br>

                      <?php
                      $user_logo_view = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_photo` WHERE user_id = '" . $videos['user_id'] . "' AND type = 'logo' LIMIT 1"));
                      if ($user_logo_view != '') { ?>
                          <div class="member-image">
                            <img src="<?php echo 'https://www.motiv8search.com/logos/profile/' . $user_logo_view['file']; ?>">
                          </div>
                          <a href="<?php echo 'https://www.motiv8search.com/logos/profile/' . $user_logo_view['file']; ?>"
                            class="" target='_blank'><i class="fa fa-eye" aria-hidden="true"></i>View
                            Logo</a>
                      <? } else {
                        echo 'Logo N/A';
                      }
                      ?>

                    </div>
                  </td>
                  <td class="Supplier-Info">
                    <div class="video_detail">
                      <?php
                      if ($videos['video_option'] == 'link') { ?>
                          <p><strong><?php echo $videos['event_name'] ?></strong></p>
                      <? }

                      if ($videos['video_option'] == 'none') { ?>
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
                        <?php
                        if ($videos['staus'] == '1') { ?>
                            <input type="hidden" name="approve" value="<?php echo $videos['id'] ?>">
                            <button type="button" class="btn btn-success approve_btn" style="margin-top: 2px;"> <?= $approve ?>
                            </button>
                        <? } elseif ($videos['staus'] == '2') { ?>
                            <input type="hidden" name="reject" value="<?php echo $videos['id'] ?>">
                            <!--    <button type="button" class="btn btn-warning reject_btn"> <?= $reject ?> </button> -->
                        <? }
                        ?>
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
                      if (empty($videos['event_description']) && $videos['video_option'] == 'none') {
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
                    $commentSql = "SELECT id, user_id, comments AS comment_body, comment_by AS user_name, date AS comment_date FROM supplier_comments WHERE user_id = '" . $videos['user_id'] . "'ORDER BY `supplier_comments`.`id` DESC LIMIT 2";
                    //echo $commentSql;
                    $commentResult = mysql_query($commentSql);
                    $rowCount = mysql_num_rows($commentResult);
                    $currentRow = 0;
                    while ($commentRow = mysql_fetch_assoc($commentResult)) {
                      $currentRow++; ?>

                        <span>
                          <b><?php echo $commentRow['user_name']; ?>: </b>
                          <span class="<?php echo ($currentRow === 1) ? 'updated-comment-text' : ''; ?>">
                            <?php echo htmlspecialchars(substr($commentRow['comment_body'], 0, 45)); ?>
                          </span>
                          <form action="" method="post" class="delete_form"
                            onclick="return confirmDelete(event, '<?php echo $commentRow['id']; ?>');">
                            <input type="hidden" name="cid" value="<?php echo $commentRow['id']; ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                          </form>
                          <br>
                          <div class="d-flex">
                            <?php if ($currentRow === $rowCount && $currentRow == 2) { ?>
                                <span>
                                  <small class="btn-link more-comments" data-uid="<?= $videos['user_id'] ?>"
                                    data-pid="<?= $videos['post_id'] ?>">
                                    view more comments
                                  </small>
                                </span>
                            <?php } ?>
                            <span
                              class="float-right"><b><?php echo !empty($commentRow['comment_date']) ? date("d-M-Y", strtotime($commentRow['comment_date'])) : 'Unknown Date'; ?></b></span>
                          </div>
                          <hr>
                        </span>

                    <?php } ?>
                    <textarea data-id="<?php echo $videos['user_id']; ?>" data-type="comment" class="comments form-control"
                      rows="3" placeholder="Write a comment..."
                      style="position: absolute;bottom: 5px;width: calc(100% - 15px);"></textarea>
                  </td>
                  <td class="st-labels">
                    <div class="status-labels">
                      <?php
                      if ($videos['staus'] == '1') {
                        // Draft
                        echo '<span style="font-size: 100%;" class="label label-warning">' . $inComplet . '</span>';
                      } elseif ($videos['staus'] == '2') {
                        // Published
                        echo '<span style="font-size: 100%;" class="label label-success">' . $complet . '</span>';
                      }

                      echo '<span style="margin: 0 2px;"></span>';
                      $packages = mysql_fetch_array(mysql_query(" SELECT packages_section FROM `supplier_registration_form` WHERE user_id = " . $videos['user_id'] . " AND event_id = " . $videos['post_id']));
                      $packages = $packages['packages_section'];
                      $start_datetime = strtotime($videos['start_date'] . ' ' . $videos['start_time']);
                      $end_datetime = strtotime($videos['end_date'] . ' ' . $videos['end_time']);
                      $current_time = time();
                      if ($videos['video_option'] == 'link' && $packages != 'SuperBooth Package') {
                        // Draft
                        echo '<span style="font-size: 100%;" class="label label-primary">Presentation</span>';
                      } elseif ($videos['video_option'] == 'none' && $packages != 'SuperBooth Package') {
                        // Published
                        echo '<span style="font-size: 100%;" class="label label-info">Desktop</span>';
                      } elseif ($packages != '' && $packages == 'SuperBooth Package') {
                        echo '<span style="font-size: 100%;" class="label label-purple">SuperBooth</span>';
                      }
                      echo '<span style="margin: 0 2px;"></span>';
                      ?>
                      <div class="labels-container" id="labelsContainer<?= $videos['user_id'] ?>">

                        <!-- Dynamic labels will appear here -->
                        <?php
                        $supplierLabel_query = mysql_query("SELECT * FROM `supplier_labels` WHERE `user_id` =  " . $videos['user_id'] . " AND `post_id` = " . $videos['post_id'] . "  ORDER BY `supplier_labels`.`id` ASC ;");
                        while ($labelRow = mysql_fetch_assoc($supplierLabel_query)) {
                          echo '<div class="label-wrapper">
                          <div class="label-item label" data-sl-id="' . $labelRow['id'] . '" style="background-color: ' . $labelRow['color_code'] . ';">
                          <span class="label-title">' . $labelRow['text_label'] . '</span>
                          </div><button class="edit-btn" data-sl-id="' . $labelRow['id'] . '">✎</button></div>';
                        }
                        ?>

                      </div>
                      <button class="openModalBtn btn btn-default" data-row-id="<?= $videos['user_id'] ?>"
                        data-post-id="<?= $videos['post_id'] ?>" style="width: 100%;">Add Label</button>
                    </div>
                    <!-- Modal -->
                    <div id="labelModal<?= $videos['user_id'] ?>" data-backdrop="static"  class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Create/Edit Label</h4>
                          </div>
                          <div class="modal-body">

                            <div class="form-group">
                              <label for="labelTitle">Select or Enter Label</label>
                              <div class="custom-dropdown">
                                <input type="text" id="labelTitle" class="form-control"
                                  placeholder="Start typing or select..." autocomplete="off">
                                <div id="dropdownSuggestions" class="suggestions-container" style="display: none;">
                                  <!-- Suggestions will appear here -->
                                </div>
                              </div>
                            </div>

                            <script>
                              // Assuming $labelsWithColors is an associative array containing labels and their colors
                              const labelsWithColors = <?php echo json_encode($labelsWithColors); ?>;  // Convert PHP array to JS object

                              document.addEventListener("DOMContentLoaded", function () {
                                document.querySelectorAll('.openModalBtn').forEach(button => {
      button.addEventListener('click', function () {
        const userId = this.getAttribute('data-row-id'); // Get user_id
        const modal = document.querySelector(`#labelModal${userId}`); // Find modal for the user
        const inputElement = modal.querySelector('#labelTitle'); // Input field
        const suggestionsContainer = modal.querySelector('#dropdownSuggestions'); // Suggestions container
        let existingLabels = [];

        // Get already assigned labels for this user
        document.querySelectorAll(`#labelsContainer${userId} .label-title`).forEach(label => {
          existingLabels.push(label.textContent.trim());
        });

        inputElement.addEventListener('input', function () {
    showSuggestions(inputElement.value, existingLabels, suggestionsContainer);

    const colorGrid = modal.querySelector('.color-grid');

    // If input is empty, show color grid with correct styles
    if (inputElement.value.trim() === '') {
      if (colorGrid) {
        colorGrid.style.display = 'grid';
        colorGrid.style.gridTemplateColumns = 'repeat(6, 1fr)';
        colorGrid.style.gap = '5px';
      }
      selectedColor = ''; // Reset selected color
    }
  });


        inputElement.addEventListener('focus', function () {
          showSuggestions('', existingLabels, suggestionsContainer);
        });

        function showSuggestions(value, existingLabels, suggestionsContainer) {
          suggestionsContainer.innerHTML = ''; // Clear old suggestions

          const filteredLabels = Object.keys(labelsWithColors)
            .filter(label => 
              (label.toLowerCase().includes(value.toLowerCase()) || value === "") && 
              !existingLabels.includes(label) // Exclude already existing labels
            );

          filteredLabels.forEach(label => {
            const optionElement = document.createElement('div');
            optionElement.classList.add('suggestion-item');
            optionElement.textContent = label;
            optionElement.style.backgroundColor = labelsWithColors[label];
            optionElement.style.color = '#fff';
            optionElement.style.padding = '5px';
            optionElement.style.borderRadius = '4px';
            optionElement.setAttribute('data-color', labelsWithColors[label]);

            optionElement.addEventListener('click', function () {
              inputElement.value = label;
              inputElement.setAttribute('data-selected-color', labelsWithColors[label]);

              // Hide suggestions
              suggestionsContainer.innerHTML = '';
              suggestionsContainer.style.display = 'none';

              // Hide color grid
              const colorGrid = modal.querySelector('.color-grid');
              if (colorGrid) colorGrid.style.display = 'none';

              // Store selected color
              selectedColor = labelsWithColors[label];
            });

            suggestionsContainer.appendChild(optionElement);
          });

          suggestionsContainer.style.display = filteredLabels.length > 0 ? 'block' : 'none';
        }

        document.addEventListener('click', function (event) {
          if (!suggestionsContainer.contains(event.target) && event.target !== inputElement) {
            suggestionsContainer.style.display = 'none';
          }
        });
      });
    });
  });


                            </script>

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
                              <label>Select a color</label>
                              <div class="color-grid">
                                <!-- Dynamic color boxes -->
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button id="saveBtn" class="btn btn-primary pull-left">Save</button>
                            <button id="deleteBtn" class="btn btn-danger pull-right">Delete</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="attend-td">
                  </td>
                  <td class="equipment">
                  </td>
                </tr>
            <?php }
            ?>
          </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
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

                    <select data-placeholder="Choose Status" name="staus" id="status_new" class="form-control">
                      <!-- <option value="0">Choose Status</option> -->
                      <option value="1"><?= $inComplet ?></option>
                      <option value="2"><?= $complet ?></option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="">Select Member </label>
                    <select data-placeholder="Search User" name="user_id" id="bd-chained" class="form-control">
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
                      <input type="radio" name="video_option" id="inlineRadio1" value="link" required=""
                        class="package-type"> Presentation
                    </label>
                    <!-- <label class="radio-inline">
                            <input type="radio" name="video_option" id="inlineRadio2" value="embed"> Presentation Embed
                        </label> -->
                    <label class="radio-inline">
                      <input type="radio" name="video_option" id="inlineRadio3" value="none" class="package-type">
                      Desktop
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
                    <!-- <input type="text" name="location_value" id="google-input-lead" class="googleSuggest googleLocation pac-target-input" placeholder="Search Location" value="" autocomplete="off"> -->
                  </div>

                  <div class="form-group">
                    <label for="">Thumbnail for Supplier Card</label>
                    <input type="file" name="thumbnail_booth" class="form-control">
                  </div>
                  <!-- <div class="form-group">
                    <label for="">Booth position priority </label>
                    <input type="number" name="booth_priority" class="form-control">
                  </div> -->
                  <?php

                  $get_start_time_sql = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_posts_query['post_id'] . " AND `key` = 'start_time'"));
                  $start_time = $get_start_time_sql['value'];

                  $get_end_time_sql = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_posts_query['post_id'] . " AND `key` = 'end_time'"));
                  $end_time = $get_end_time_sql['value'];


                  $post_expire_date = date('Y-m-d', strtotime($get_posts_query['post_expire_date']));
                  $post_start_date = date('Y-m-d', strtotime($get_posts_query['post_start_date']));


                  // Generate time sloats 
                  
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


                  // $sloats_html_select .= "<select>";
                  
                  ?>
                  <div class="form-group package-presentation">
                    <label for="">Choose Webinar Slot</label>
                    <select name="time_sloat" class="form-control" id="time_sloat">
                      <?php
                      foreach ($time_sloats_arr as $key => $value) {
                        $sloat = date('H:i', $value['start_time']) . " - " . date('H:i', $value['end_time']);
                        echo "<option value='" . $sloat . "'>" . $sloat . "</option>";
                      } ?>
                    </select>
                  </div>

                  <div class="form-group" style="display: none;">
                    <label for="">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                      value="<?= $post_start_date ?>">
                  </div>

                  <div class="form-group" style="display: none;">
                    <label for="">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                      value="<?= $post_expire_date ?>">
                  </div>

                  <div class="form-group package-presentation">
                    <label for="">Presentation link</label>
                    <input type="url" name="video_link" class="form-control">
                  </div>

                  <div class="form-group package-presentation">
                    <label for="">Replay Embed Code</label>
                    <textarea name="replay_embed" id="" rows="5" class="form-control"></textarea>
                  </div>

                  <div class="form-group link-container package-presentation">
                    <label for="">Presentation PDF Link</label>
                    <input type="url" name="pdf_link" class="form-control">
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
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
                      <p>In mauris porttitor tincidunt mauris massa sit lorem sed scelerisque.
                        Fringilla pharetra vel
                        massa enim sollicitudin cras. At pulvinar eget sociis adipiscing eget
                        donec ultricies nibh
                        tristique.</p>
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
            $post_id = $_POST['edit_post'];
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
            ?>
            <!-- Modal -->
            <div class="modal fade in d-flex justify-content-center align-items-center" id="edit-modal" tabindex="-1"
              role="dialog" aria-labelledby="" aria-hidden="false" data-backdrop="static" data-keyboard="false"
              style="display: block;">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="width: 900px;">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="">Edit Booth</h4>
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
                          <?/*<option <?php if($get_post['staus']=='0'){ echo 'selected'; } ?> value="0">Choose Status</option>*/ ?>
                          <option <?php if ($get_post['staus'] == '1') {
                            echo 'selected';
                          } ?> value="1"><?= $inComplet ?>
                          </option>
                          <option <?php if ($get_post['staus'] == '2') {
                            echo 'selected';
                          } ?> value="2"><?= $complet ?>
                          </option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Select Member</label>
                        <select data-placeholder="Search User" name="user_id" id="bd-chained-2" class="form-control">
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

                          <a href="<?php echo ($user_logo['file']) ? 'https://www.motiv8search.com/logos/profile/' . $user_logo['file'] : '#'; ?>"
                            class="open-logo btn btn-primary" target='_blank'>View Logo</a>
                        </div>
                        <div class="clearfix"></div>
                      </div>

                      <div class="form-group">
                        <label for="">Package</label>
                        <div class="clearfix"></div>
                        <label class="radio-inline">
                          <input type="radio" <?php echo ($get_post['video_option'] == "link") ? "checked" : "" ?>
                            name="video_option" id="inlineRadio1" value="link" class="package-type"> Presentation
                        </label>

                        <label class="radio-inline">
                          <input type="radio" <?php echo ($get_post['video_option'] == "none") ? "checked" : "" ?>
                            name="video_option" id="inlineRadio3" value="none" class="package-type"> Desktop
                        </label>

                      </div>

                      <div class="form-group package-presentation">
                        <label for="">Presentation Title </label>
                        <input type="text" name="event_name" class="form-control"
                          value="<?php echo htmlspecialchars($get_post['event_name']) ?>">
                      </div>

                      <div class="form-group">
                        <label for="">Company Description </label>
                        <input type="text" name="event_description" class="form-control"
                          value="<?php echo htmlspecialchars($get_post['event_description']) ?>">
                      </div>

                      <div class="form-group package-presentation">
                        <label for="">Location</label>
                        <input type="text" name="event_location" class="form-control"
                          value="<?php echo htmlspecialchars($get_post['event_location']) ?>">
                      </div>

                      <div class="form-group">
                        <label for="">Thumbnail for Supplier Card</label>
                        <input type="file" name="thumbnail_booth" class="form-control">
                        <?php if ($get_post['thumb_booth'] != ""): ?>
                            <a style="margin-top: 4px;"
                              href="<?php echo $website_domain ?>/images/events/<?php echo strtolower($get_post['thumb_booth']) ?>"
                              target="_blank" class="btn btn-primary">View image</a>
                        <?php endif ?>
                      </div>
                      <?/*<div class="form-group">
                        <label for="">Booth position priority </label>
                        <input type="number" name="booth_priority" value="<?php echo $get_post['booth_priority'] ?>" class="form-control">
                      </div> */ ?>
                      <?php

                      $get_start_time_sql = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_post['post_id'] . " AND `key` = 'start_time'"));
                      $start_time = $get_start_time_sql['value'];

                      $get_end_time_sql = mysql_fetch_assoc(mysql_query("SELECT * FROM `users_meta` WHERE database_id = " . $get_post['post_id'] . " AND `key` = 'end_time'"));
                      $end_time = $get_end_time_sql['value'];


                      $post_expire_date = date('Y-m-d', strtotime($get_post['post_expire_date']));
                      $post_start_date = date('Y-m-d', strtotime($get_post['post_start_date']));


                      // Generate time sloats 
                    
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


                      // $sloats_html_select .= "<select>";
                    
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
                            echo "<option value='" . $sloat . "' $selected >" . $sloat . "</option>";
                          }
                          ?>
                        </select>
                      </div>

                      <div class="form-group" style="display: none;">
                        <label for="">Start Date</label>
                        <input type="date" name="start_date" id="start_date_edit" class="form-control"=""
                          value="<?php echo $get_post['start_date'] ?>">
                      </div>
                      <div class="form-group" style="display: none;">
                        <label for="">End Date</label>
                        <input type="date" name="end_date" id="end_date_edit" class="form-control"=""
                          value="<?php echo $get_post['end_date'] ?>">
                      </div>
                      <!-- <p class="account-tip">Please enter Start and End time cautiously</p> -->


                      <div class="form-group package-presentation">
                        <label for="">Presentation link</label>
                        <input type="url" name="video_link" value="<?php echo $get_post['video_link'] ?>"
                          class="form-control">

                      </div>


                      <div class="form-group package-presentation">
                        <label for="">Replay Embed Code</label>
                        <textarea name="replay_embed" id="" rows="5"
                          class="form-control"><?php echo $get_post['replay_embed'] ?></textarea>
                      </div>

                      <div class="form-group package-presentation">
                        <label for="">Presentation PDF Link</label>
                        <input type="url" name="pdf_link" class="form-control"
                          value="<?php echo $get_post['presentation_pdf_link'] ?> ">
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
              $(document).ready(function () {
                $('#select-event-edit').change();
                setTimeout(function () {
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
  $('#add_video').submit(function (event) {
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
      success: function (data) {
        console.log(data);
        location = location
      }
    });
  });

  $('#edit_video').submit(function (event) {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      url: '<?php echo $website_domain ?>/edit_event.php',
      type: 'POST',
      dataType: '',
      processData: false,
      contentType: false,
      data: formData,
      success: function (data) {
        location = location
        console.log(data);
      }
    });
  });
  $('#select-event').change(function (event) {
    var post_id = $(this).val();
    console.log(post_id);
    $.ajax({
      url: '<?php echo $website_domain ?>/api/widget/json/get/get_post_details_ajax',
      type: 'GET',
      dataType: 'json',
      data: {
        post_id: post_id
      },
      success: function (data) {
        $('#start_date').val(data.post_start_date);
        $('#end_date').val(data.post_expire_date);
        $('#time_sloat').html(data.time_sloats).prop('disabled', false);
      }
    })

  });
  $('#select-event-edit').change(function (event) {
    var post_id = $(this).val();
    console.log(post_id);
    $.ajax({
      url: '<?php echo $website_domain ?>/api/widget/json/get/get_post_details_ajax',
      type: 'GET',
      dataType: 'json',
      data: {
        post_id: post_id
      },
      success: function (data) {
        $('#start_date_edit').val(data.post_start_date);
        $('#end_date_edit').val(data.post_expire_date);
        $('#time_sloat_edit').html(data.time_sloats).prop('disabled', false);
      }
    })

  });
  $(function () {
    $(".delete_post_btn").click(function (event) {
      var form = $(this).closest('form');
      if (confirm("You really want to delete this Booth?")) {
        form.submit();
      }
    });
  });
  //You really want to set to Complete this presentation?
  $(function () {
    $(".approve_btn").click(function (event) {
      var form_approve = $(this).closest('form');
      if (confirm("Are you sure you want to publish this Booth?")) {
        form_approve.submit();
      }
    });
  });
  //You really want to set to Incomplete this Booth?

  $(function () {
    $(".reject_btn").click(function (event) {
      var form_reject = $(this).closest('form');
      if (confirm("Are you sure you want to mark this Booth as incomplete?")) {
        form_reject.submit();
      }
    });
  });
  $(document).on('change', '#bd-chained, #bd-chained-2', function (event) {
    var logo_this = $(this);
    logo_this.parent().find('.member-links').hide();
    logo_this.siblings('.show_load').show();
    logo_this.siblings('.show_load').text('Please wait..');

    var selectedMember = $(this).val();

    $.ajax({
      url: '/api/widget/json/get/open_logo',
      type: 'POST',
      data: {
        user_id: selectedMember
      },
      dataType: 'json',
      success: function (data) {

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


  // Get the initial value of the package type
  var packageType = $(".package-type:checked").val();

  // Set the initial state of the radio buttons and package elements
  if (packageType == "link") {
    $(".package-presentation").show();
    $(".package-desktop").hide();
  } else if (packageType == "none") {
    $(".package-desktop").show();
    $(".package-presentation").hide();
  }

  // Add click event listener to the radio buttons
  $("input[name='video_option']").click(function () {
    if ($(this).val() == "link") {
      // Show elements with class "package-presentation" and hide elements with class "package-desktop"
      $(".package-presentation").show();
      $(".package-desktop").hide();
    } else if ($(this).val() == "none") {
      // Show elements with class "package-desktop" and hide elements with class "package-presentation"
      $(".package-desktop").show();
      $(".package-presentation").hide();
    }
  });
</script>
<script>
  // script code for PID: 547 by vikash
  $(document).ready(function () {
    $('#comments_modal').modal('hide');
    $(document).on('click', '.more-comments', function () {
      let $element = $(this);
      let uid = $element.data('uid');
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
          uid: uid
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            const details = response.data;
            console.log(details);
            let commentsHtml = '';

            details.forEach(detail => {
              const userProfile = detail.user_image ?
                detail.user_image :
                "https://www.motiv8search.com/images/profile-profile-holder.png";
              const commentDate = detail.comment_date || "Unknown Date";
              const userName = detail.user_name || "Anonymous User";
              const commentBody = detail.comment_body || "No content available.";

              commentsHtml += `
                        <div class="comment-box">
                            <img src="${userProfile}" alt="User Profile">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <h5>${userName}</h5>
                                    <small>${commentDate}</small>
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

            // Update the modal's body
            $('#comments_modal .modal-body').html(commentsHtml);

            // Show the modal
            $('#comments_modal').modal('show');
          } else {
            // Handle failure response
            $('#comments_modal').modal('hide');
            swal("Action Warning!", response.message, "warning");
          }
        },
        error: function () {
          $('#comments_modal').modal('hide');
          // Handle AJAX error
          swal("Action Failed!", "Failed to fetch details. Please try again.", "error");
        }
      });
    });
    $(document).on("blur", ".comments", function () {
      const $el = $(this);
      let updatedText = $el.val().trim();
      const type = $el.data("type");
      const id = $el.data("id");
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
        type: type
      })
        .done(function () {
          $el.val('');
          $el.prop("disabled", false);
          swal("Action Successful!", "Comment updated successfully.", "success");
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
        .fail(function () {
          $el.prop("disabled", false);
          swal("Action Failed!", "Failed to update the comment. Please try again.", "error");
        });
    });
  });
  // Now start
  // Function to convert RGB to Hex
  function rgbToHex(rgb) {
    var rgbValues = rgb.match(/\d+/g); // Extract RGB values
    var hex = "#";
    for (var i = 0; i < 3; i++) {
      var hexValue = parseInt(rgbValues[i]).toString(16);
      hex += hexValue.length == 1 ? "0" + hexValue : hexValue; // Ensure two digits for each color
    }
    return hex.toUpperCase();
  }

  $(document).ready(function () {
    const colors = [
      '#4B164C', '#FFC107', '#FF5722', '#4CAF50', '#2196F3', '#9C27B0',
      '#FFEB3B', '#8BC34A', '#03A9F4', '#673AB7', '#E91E63', '#FF9800',
      '#795548', '#607D8B', '#00BCD4', '#CDDC39', '#FF4081', '#BDBDBD'
    ];

    let selectedColor = ''; // This will store the selected color in HEX

    function initializeColorGrid(modal, labelsContainer) {
  const colorGrid = modal.find('.color-grid');
  colorGrid.empty();

  // Collect all used colors across all users
  let usedColors = new Set();
  $('.labels-container .label-item').each(function () {
    usedColors.add(rgbToHex($(this).css('background-color')));
  });

  colors.forEach(color => {
    const colorBox = $('<div>')
      .addClass('color-box')
      .css('background-color', color)
      .data('color', color)
      .click(function () {
        if ($(this).hasClass('disabled')) return;
        colorGrid.find('.color-box').removeClass('selected');
        $(this).addClass('selected');
        selectedColor = $(this).data('color'); // Store the selected color as HEX
      });

    // Disable colors that are already used globally
    if (usedColors.has(color)) {
      colorBox.addClass('disabled').css('cursor', 'not-allowed')
        .append('<div class="color-overlay">❌</div>');
    }

    colorGrid.append(colorBox);
  });
}


    $('tbody').on('click', '.openModalBtn, .edit-btn', function () {
      const rowId = $(this).data('row-id') || $(this).closest('.labels-container').attr('id').replace('labelsContainer', '');
      const postId = $(this).data('post-id');
      const modal = $('#labelModal' + rowId);
      const labelsContainer = $('#labelsContainer' + rowId);
      let currentEditingLabel = null;

      initializeColorGrid(modal, labelsContainer);

      const labelTitleInput = modal.find('#labelTitle');
      const deleteBtn = modal.find('#deleteBtn');

      if ($(this).hasClass('edit-btn')) {
        // Editing existing label
        currentEditingLabel = $(this).closest('.label-wrapper').find('.label-item');
        const labelTitle = currentEditingLabel.find('.label-title').text();
        const labelColor = currentEditingLabel.css('background-color');

        labelTitleInput.val(labelTitle);
        selectedColor = rgbToHex(labelColor); // Convert RGB to HEX and set it

        // Highlight selected color
        modal.find('.color-box').each(function () {
          if (rgbToHex($(this).css('background-color')) === selectedColor) {
            $(this).addClass('selected');
          }
        });

        modal.find('#saveBtn').text('Update');
        deleteBtn.prop('disabled', false); // Enable delete button
      } else {
        // Creating a new label
        labelTitleInput.val(''); // Clear input field
        selectedColor = ''; // Reset selected color
        modal.find('.color-box').removeClass('selected'); // Reset color selection
        modal.find('#saveBtn').text('Save');
        deleteBtn.prop('disabled', true); // Disable delete button for new label
      }

      modal.modal('show');

      modal.find('#saveBtn').off('click').on('click', function () {
    const title = modal.find('#labelTitle').val().trim();
    let colorCode = selectedColor; // Default from selectedColor

    // If user entered a custom label but didn't select a color
    if (!colorCode) {
        const inputElement = modal.find('#labelTitle');
        colorCode = inputElement.attr('data-selected-color') || ''; // Empty string means no color selected
    }

    // Validation: Ensure title and color are selected
    if (!title) {
        swal("Oops!", "Please enter a label name.", "warning");
        return;
    }

    if (!colorCode) {
        swal("Oops!", "Please select a color for the label.", "warning");
        return;
    }

    if (currentEditingLabel) {
        const labelId = currentEditingLabel.data('sl-id');

        $.post('/api/widget/html/get/label_ajax', {
    action: 'update',
    label_id: labelId,
    text_label: title,
    color_code: colorCode
}, function (response) {
    if (response.trim() === "Success") {
        // Reload the page to update all instances of the label
        location.reload(true);
    } else {
        alert('Error updating label: ' + response);
    }
}).fail(function (xhr, status, error) {
    console.error('AJAX error:', status, error);
    alert('Error communicating with the server.');
});

    } else {
        $.post('/api/widget/html/get/label_ajax', {
            action: 'create',
            text_label: title,
            color_code: colorCode,
            user_id: rowId,
            post_id: postId,
        }, function (response) {
            if (response.trim() === "Success") {
                location.reload(true);
            } else {
                alert('Error creating label: ' + response);
            }
        }).fail(function (xhr, status, error) {
            console.error('AJAX error:', status, error);
            alert('Error communicating with the server.');
        });
    }
});



      modal.find('#deleteBtn').off('click').on('click', function () {
        if (currentEditingLabel) {
          swal({
            title: "Are you sure?",
            text: "Are you sure you want to delete this label?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
          },
            function () {
              const labelId = currentEditingLabel.data('sl-id');

              $.post('/api/widget/html/get/label_ajax', { action: 'delete', label_id: labelId }, function () {
                // Remove the deleted label from the UI
                currentEditingLabel.closest('.label-wrapper').remove();

                // Hide the modal
                modal.modal('hide');

                // Show success message after deletion
                swal("Deleted!", "Your label has been deleted.", "success");
              });
            });
        }
      });

    });
  });



  $('#labelModal<?= $videos['user_id'] ?>').modal({
    backdrop: 'true', // This ensures clicking outside the modal closes it
    keyboard: true // This allows closing with the escape key
  });

  $(document).ready(function () {
    $('#labelSelect').change(function () {
      var selectedValue = $(this).val();
      if (selectedValue) {
        $('#labelTitle').val(selectedValue);
      }
    });
  });


</script>