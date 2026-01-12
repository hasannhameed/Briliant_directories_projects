<?php

$fieldConfigurations = array(
    'widget_name' => 'Bootstrap Theme - Availability Button',
    'button_text' => 'Set as Available',
    'availability_options' => array(
        '1 hour',
        '2 hours',
        '4 hours',
        '8 hours'
    )
);

// Delete expired records
$currentTime = strtotime('now');

// Set available record data
$availableMembers = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT * FROM users_meta WHERE `key` = 'available_until'");
while ($availableMemberData = mysql_fetch_assoc($availableMembers)) {
  $availableMetaID = $availableMemberData['meta_id'];
  $availableMemberID = $availableMemberData['database_id'];
  $availableUntil = $availableMemberData['value'];
  // Check if the availability expired
  if ($availableUntil < $currentTime) {
    mysql(brilliantDirectories::getDatabaseConfiguration('database'), "DELETE FROM users_meta WHERE `meta_id` = '$availableMetaID'");
    mysql(brilliantDirectories::getDatabaseConfiguration('database'), "DELETE FROM users_meta WHERE `key` = 'available_now' AND `database_id` = $availableMemberID");
  } else {
    // Check if the available flag exists
    $availabilityMetaCheck = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "
      SELECT * FROM users_meta WHERE `database_id` = $availableMemberID AND `key` = 'available_now' AND database = 'users_data'
    ");
    if (mysql_num_rows($availabilityMetaCheck) == 0) {
        // Create the available record
        $dateAdded = date('YmdHis');
        $availabilityCreateSQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "
          INSERT INTO `users_meta`
              (`meta_id`, `database`, `database_id`, `key`, `value`, `date_added`, `revision_timestamp`)
          VALUES (NULL, 'users_data', '$availableMemberID', 'available_now', '1', '$dateAdded', CURRENT_TIMESTAMP)
        ");
    }
  }

}

if (!empty($_POST['setAvailableStatus'])) {
  $response = array(
      'status' => 'success',
      'message' => 'Availability status updated successfully!'
  );
  $userID = intval($_POST['userID']);
  $userToken = mysql_real_escape_string($_POST['userToken']);
  $availableTime = $_POST['availability_time'];
  $availableUntil = strtotime("+ ".$availableTime);
  $response['available_until'] = "Available until: " . date('Y/m/d H:i:s', $availableUntil);
  $dateAdded = date('YmdHis');
  $checkUserToken = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT * FROM users_data WHERE user_id = $userID AND token = '$userToken'");
  if (mysql_num_rows($checkUserToken) > 0) {
    // Update available status
    $availabilityMetaCheck = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "
      SELECT * FROM users_meta WHERE database_id = $userID AND `key` = 'available_until' AND database = 'users_data'
    ");
    if (mysql_num_rows($availabilityMetaCheck) > 0) {
      // Update status
      $availabilityUpdateSQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "
        UPDATE users_meta
        SET value = '$availableUntil',
        revision_timestamp = CURRENT_TIMESTAMP
        WHERE database_id = $userID AND `key` = 'available_until' AND database = 'users_data'
      ");
    } else {
      // Create status
      $availabilityCreateSQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "
        INSERT INTO `users_meta`
            (`meta_id`, `database`, `database_id`, `key`, `value`, `date_added`, `revision_timestamp`)
        VALUES (NULL, 'users_data', '$userID', 'available_until', '$availableUntil', '$dateAdded', CURRENT_TIMESTAMP)
      ");
    }
  }
  echo json_encode($response);die();
}

if ($pars[0] == 'account') {
  // Do availability button logic
  ?>
  <style>
    @media only screen and (max-width: 768px) {

      .center {
        text-align: center;
      }

      #member_listing_details_320 > div.availability-button > a {
        font-size: 14px !important;
        width: 100%;
        display: block;
        margin: 0 !important;
      }
      .availability-button .form-group {
        width: 100%;
        margin-left: 0 !important;
        margin-bottom: 5px !important;
      }

      #first_container > div > div.col-sm-12.col-md-9.norpad.sm-nopad.member_accounts > div.account-menu-tabs > ul {
        font-size:14px !important;
      }
    }

    @media only screen and (min-width: 769px) {

      .center {
        margin-left:20px;
      }
    }


    .availability-button .form-group {
      display: inline-block;
      margin-left: 20px;
    }
  </style>
  <div class="availability-button">

    <p class="center"><a class="btn btn-success mark-as-available"><?php echo $fieldConfigurations['button_text']; ?></a></p>


    <div class="form-group">
      <select class="form-control available-time">
        <option value="">Select an availability timeframe</option>
        <?php foreach ($fieldConfigurations['availability_options'] as $availability_option) { ?>
          <option value="<?php echo $availability_option; ?>"><?php echo $availability_option; ?></option>
        <?php } ?>
      </select>

    </div><a href="#" class=" how-available btn-warning btn-md" style="margin-left:30px; padding-top:5px; padding-bottom:5px; padding-left:10px; padding-right:10px; font-weight:600;   border-radius:5px;">"How to set Available Now"</a>
    <div class="tpad availability-status">
      <div class="panel panel-default">
        <div class="panel-heading" id="heading-101">
          <h4 class="panel-title">
            Current Availability:
          </h4>
        </div>
        <div class="panel-body">
          <?php
          $availableUntil = 'Not set';
          $user = getMetaData("users_data",$user['user_id'],$user,$w);
          if (isset($user['available_until'])) {
            $availableUntil = $user['available_until'];
            $availableUntil = "Available until: " . date('Y/m/d H:i:s', $availableUntil);
          }
          ?>
          <div class="availability-status-value">
            <?php echo $availableUntil; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    var availabilityFeature = {
      init: function() {
        $(document).on("click", ".mark-as-available", function(e) {
          e.preventDefault();
          var selectedAvailability = $('select.available-time').val();
          if (selectedAvailability) {
            var formData = new FormData();
            // add assoc key values, this will be posts values
            formData.append("availability_time", selectedAvailability);
            formData.append("userID", <?php echo $user['user_id']; ?>);
            formData.append("userToken", "<?php echo $user['token']; ?>");
            formData.append("setAvailableStatus", true);
            swal({
              showCancelButton:   false,
              showConfirmButton:  false,
              closeOnConfirm:     true,
              allowOutsideClick:  false,
              allowEscapeKey:     false,
              imageUrl:           '//www.securemypayment.com/directory/cdn/images/bars-loading.gif',
              title:              'Processing...',
              html:               'Please wait while we save your availability status...'
            });
            $.ajax({
              type: "POST",
              url: '/api/data/html/get/data_widgets/widget_name?name=<?php echo $fieldConfigurations['widget_name']; ?>',
              success: function (data) {
                data = JSON.parse(data);
                if (data['status'] == 'success') {
                  var availableUntil = data['available_until'];
                  $(".availability-status-value").html(availableUntil);
                  swal({ title: "Success", text: "Your availability status has been set successfully.", type: "success" } );
                } else {
                  swal({ title: "Error", text: 'There was a problem while setting your availability status, please reload the page and try again.', type: "error" });
                }
              },
              error: function (error) {
                swal({ title: "Error", text: error, type: "error" });
              },
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              timeout: 60000
            });
          } else {
            alert('Please select an availability from the dropdown to continue.');
          }
        });
      }
    };
    availabilityFeature.init();
  </script>
<?php } else {
  // Show available button
  $user_data = getMetaData("users_data",$user_data['user_id'],$user_data,$w);
  if (isset($user_data['available_until'])) {
    $availableUntil = $user_data['available_until'];
    if ($availableUntil >= strtotime('now')) { ?>
      <div class="hidden-xs">
        <div title="Local" class="badge btn-xs bold nopad nolpad rmargin inline-block member-search-verified" style="background-color:#aae2d3;">
        <span class="btn-xs novpad bg-local pull-left" style="border-radius: 3px 0 0 3px;">
            <i class="fa fa-street-view"></i>
        </span>
          <span class="btn-xs nolpad novpad">
            Available Now
        </span>
        </div>
      </div>
    <?php }
  } ?>
<?php } ?>
