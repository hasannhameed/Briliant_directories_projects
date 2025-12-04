<!-- attending-staff-view-edit -->
<?php
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $id = intval($_POST['id']); 
       $is_event_coordinator = intval($_POST['is_event_coordinator']); 
       $full_name = addslashes($_POST['full_name']);
       $job_title = addslashes($_POST['job_title']);
       $email = addslashes($_POST['email']);
       $phone_number = addslashes($_POST['phone_number']);
       $company_name = addslashes($_POST['company_name']);
       $registered_company = addslashes($_POST['registered_company']);
       $attended_before = addslashes($_POST['attended_before']);
       $other_equipment = addslashes($_POST['other_equipment']);
       
       // Convert event_space array to a comma-separated string
       $event_space = isset($_POST['event_space']) ? implode(',', $_POST['event_space']) : '';

//   FTP CODE

       // FTP server credentials
  $ftp_host = brilliantDirectories::getDatabaseConfiguration('ftp_server'); //host
  $ftp_user = brilliantDirectories::getDatabaseConfiguration('website_user'); //username
  $ftp_pass =  brilliantDirectories::getDatabaseConfiguration('website_pass'); //password
  $ftp_dir = "/public_html/images/";      // FTP upload directory

  // Establish an FTP connection
  $conn_id = ftp_connect($ftp_host);

  if (!$conn_id) {
    die("Could not connect to FTP server");
  }

  // Login to FTP server
  if (!ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    ftp_close($conn_id);
    die("FTP login failed");
  }

  // Enable passive mode

  ftp_pasv($conn_id, true);

  if (isset($_FILES['files'])) {
    // Check if file is uploaded
if (isset($_FILES['files']) && $_FILES['files']['error'] === UPLOAD_ERR_OK) {
    $tmp_file = $_FILES['files']['tmp_name'];
    $file_name = $_FILES['files']['name'];
    
    // Extract file extension
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $unique_id = mt_rand(10000000, 99999999); // Unique ID for file
    $file_base_name = pathinfo($file_name, PATHINFO_FILENAME);
    $safe_file_name = preg_replace("/[^a-zA-Z0-9-_]/", "-", $file_base_name);
    $final_file_name = $safe_file_name . "-" . $unique_id . "." . $file_extension;
    $ftp_target_file = $ftp_dir . basename($final_file_name);
    
    // Upload the file
    if (ftp_put($conn_id, $ftp_target_file, $tmp_file, FTP_BINARY)) {
        // File uploaded successfully
    } else {
        echo "File upload failed.";
    }
} else {
    // If no file is uploaded, use the old file (make sure to fetch the old file name from the database)
    $final_file_name = isset($_POST['old_file']) ? $_POST['old_file'] : ''; // Assuming $row['files'] holds the old file name
}

  } else {
    echo "No files selected for upload.";
  }
 
  ftp_close($conn_id);

//   FTP CODE
   
       // Build the UPDATE query
       $update_query = "UPDATE attending_supplier_staff_registration SET 
           is_event_coordinator = '$is_event_coordinator',
           full_name = '$full_name',
           job_title = '$job_title',
           email = '$email',
           phone_number = '$phone_number',
           company_name = '$company_name',
           registered_company = '$registered_company',
           attended_before = '$attended_before',
           event_space = '$event_space',
           other_equipment = '$other_equipment',
           files = '$final_file_name'
       WHERE id = $id";
   
       // Execute the query
       $update_result = mysql_query($update_query);
   
       // Handle the response
       if ($update_result) {
           echo "<script>
                   document.addEventListener('DOMContentLoaded', function() {
                       swal({
                           title: 'Success!',
                           text: 'Record updated successfully.',
                           type: 'success',
                           confirmButtonText: 'OK'
                       },
                       function(isConfirm){
                           if (isConfirm){
                               window.location.href = 'https://ww2.managemydirectory.com/admin/go.php?widget=attending-staff-view-edit&id=" . $id . "';';
                           }
                       });
                   });
                 </script>";
       } else {
           echo "<script>
                   setTimeout(function() {
                       swal({
                           title: 'Error!',
                           text: 'Failed to update record.',
                           type: 'error',
                           confirmButtonText: 'OK'
                       });
                   }, 100);
                 </script>";
       }
   }
   ?>
<section>
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
            <div class="registation_form">
               <h2>Application Details</h2>
            </div>
            <div class="clearfix"></div>
            <div class="card">
               <table class="table">
                  <tbody>
                     <?php
                        if (isset($_GET['supplier_id'], $_GET['post_id'], $_GET['staff_id'])) {
                            $supplier_id = $_GET['supplier_id'];
                            $post_id = $_GET['post_id'];
                            $staff_id = $_GET['staff_id'];
                            // $payment_summary = 0;
                            $sql = "SELECT * FROM `attending_supplier_staff_registration` 
                                    WHERE  `supplier_id` = $supplier_id 
                                    AND `post_id` = $post_id 
                                    AND `user_id` = $staff_id";
                            $result = mysql_query($sql);
                            $formType = '';
                            if (mysql_num_rows($result) > 0) {
                                $row = mysql_fetch_assoc($result);
                        
                                ?>
                     <tr>
                        <td class="left_data">Is he event coordinator?</td>
                        <td>
                           <?php echo ($row['is_event_coordinator'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Full Name</td>
                        <td>
                           <?php echo $row['full_name']; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Job Title</td>
                        <td>
                           <?php echo $row['job_title']; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Is He/She (personally) attended a Motiv8 Connect Supplier
                           Engagement Day before?
                        </td>
                        <td>
                           <?php echo $row['attended_before']; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Email</td>
                        <td>
                           <?php echo $row['email']; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Mobile Phone Number</td>
                        <td>
                           <?php echo $row['phone_number']; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Company Name</td>
                        <td>
                           <?php echo $row['company_name']; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Name of Company that registered for this event</td>
                        <td>
                           <?php echo $row['registered_company']; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Setup Instructions</td>
                        <td>
                           <?php echo ($row['setup_instructions'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Shipping Items</td>
                        <td>
                           <?php echo ($row['shipping_items'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">End Time for the Event</td>
                        <td>
                           <?php echo ($row['end_time'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">What He/She CANNOT Bring</td>
                        <td>
                           <?php echo ($row['cannot_bring'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Event Space Requirement</td>
                        <td>
                           <?php echo $row['event_space']; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Internet / Wifi</td>
                        <td>
                           <?php echo ($row['internet_wifi'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Attendance Numbers & Expectations</td>
                        <td>
                           <?php echo ($row['attendance_numbers'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Event Briefing Requirements</td>
                        <td>
                           <?php echo ($row['event_briefing'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Areas of Access</td>
                        <td>
                           <?php echo ($row['areas_access'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Manufacturer Attendees</td>
                        <td>
                           <?php echo ($row['manufacturer_attendees'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Access Requirement</td>
                        <td>
                           <?php echo ($row['access_requirement'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="left_data">Event Hours Requirement</td>
                        <td>
                           <?php echo ($row['event_hours'] == 1) ? 'Yes' : 'No'; ?>
                        </td>
                     </tr>
                     <tr>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                           aria-labelledby="editModalLabel">
                           <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                       aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="editModalLabel">Edit Application Details
                                    </h4>
                                 </div>
                                 <!-- Form -->
                                 <form action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <div class="modal-body">
                                       <div class="form-group">
                                          <label for="is_event_coordinator">Are you the event coordinator?<span class="text-danger">*</span></label>
                                          <div class="radio_filed">
                                             <label>
                                             <input name="is_event_coordinator" value="1" type="radio" required 
                                                <?php if ($row['is_event_coordinator'] === '1') echo 'checked'; ?> 
                                                onclick="toggleOthersCheckbox()">
                                             Yes
                                             </label>
                                             <label>
                                             <input name="is_event_coordinator" value="" type="radio" required 
                                                id="no_event_coordinator" 
                                                <?php if ($row['is_event_coordinator'] === '' || $row['is_event_coordinator'] === '0') echo 'checked'; ?> 
                                                onclick="toggleOthersCheckbox()">
                                             No
                                             </label>
                                          </div>
                                       </div>
                                       <div class="form-group">
                                          <label for="full_name">1. Full Name <span
                                             class="text-danger">*</span></label>
                                          <input name="full_name" value="<?php echo $row['full_name']; ?>"
                                             id="full_name" class="form-control" placeholder="John Smith"
                                             required>
                                       </div>
                                       <div class="form-group">
                                          <label for="job_title">2. Job Title <span
                                             class="text-danger">*</span></label>
                                          <input name="job_title" id="job_title" class="form-control"
                                             placeholder="Business Development Manager"
                                             value="<?php echo $row['job_title']; ?>" required>
                                       </div>
                                       <div class="form-group">
                                          <label for="email_id">4. Email <span
                                             class="text-danger">*</span></label>
                                          <input name="email" value="<?php echo $row['email']; ?>"
                                             id="email_id" type="email" class="form-control"
                                             placeholder="Email" required>
                                       </div>
                                       <div class="form-group">
                                          <label for="phone_number">5. Mobile Phone Number <span
                                             class="text-danger">*</span></label>
                                          <input name="phone_number"
                                             value="<?php echo $row['phone_number']; ?>"
                                             id="phone_number" type="tel" class="form-control"
                                             placeholder="Phone Number" required>
                                       </div>
                                       <div class="form-group">
                                          <label for="company_name">6. Company Name <span
                                             class="text-danger">*</span></label>
                                          <input type="text" class="form-control" id="company_name"
                                             value="<?php echo $row['company_name']; ?>"
                                             name="company_name" required>
                                       </div>
                                       <div class="form-group">
                                          <label for="registered_company">7. Name of Company that
                                          registered for this event</label>
                                          <input type="text" class="form-control" id="registered_company"
                                             value="<?php echo $row['registered_company']; ?>"
                                             name="registered_company">
                                       </div>
                                       <div class="form-group">
                                          <label for="attended_before">8. Have you attended before? <span
                                             class="text-danger">*</span></label>
                                          <div class="radio_filed">
                                             <label>
                                             <input name="attended_before" value="Yes" type="radio"
                                                required <?php if ($row['attended_before'] === 'Yes')
                                                   echo 'checked'; ?>>
                                             Yes
                                             </label>
                                             <label>
                                             <input name="attended_before" value="No" type="radio"
                                                required <?php if ($row['attended_before'] === 'No')
                                                   echo 'checked'; ?>>
                                             No
                                             </label>
                                          </div>
                                       </div>
                                       <?php
                                          // Handle event space checkboxes
                                          $selected_spaces = explode(',', $row['event_space']);
                                          ?>
                                       <!-- Add this script to handle the toggle functionality -->
                                       <script>
                                          function toggleOthersCheckbox() {
    let isNoSelected = document.getElementById("no_event_coordinator").checked;
    let othersCheckbox = document.getElementById("others_checkbox");
    let othersLabel = document.getElementById("others_checkbox_label");
    let otherEquipmentField = document.getElementById("other_equipment_field");

    if (isNoSelected) {
        othersCheckbox.dataset.prevChecked = othersCheckbox.checked; // Store previous state
        othersCheckbox.checked = false; // Uncheck "Others" when switching to No
        othersCheckbox.style.display = "none";
        othersLabel.style.display = "none";
        otherEquipmentField.style.display = "none"; // Hide extra input fields
    } else {
        othersCheckbox.style.display = "inline-block";
        othersLabel.style.display = "inline-block";

        // Restore previous checked state
        if (othersCheckbox.dataset.prevChecked === "true") {
            othersCheckbox.checked = true;
            otherEquipmentField.style.display = "block"; // Show input fields if previously checked
        }
    }
}

function toggleOtherEquipmentField() {
    let othersCheckbox = document.getElementById("others_checkbox");
    let otherEquipmentField = document.getElementById("other_equipment_field");

    if (othersCheckbox.checked) {
        otherEquipmentField.style.display = "block";
    } else {
        otherEquipmentField.style.display = "none";
    }
}

// Run function on page load to check initial state
window.onload = function() {
    toggleOthersCheckbox();
    toggleOtherEquipmentField();
};

                                       </script>
                                       <!-- Add the onchange event to the "Others" checkbox -->
                                       <div class="form-group">
                                          <label for="event_space[]">9. Event Space Requirement(s) <span class="text-danger">*</span></label>
                                          <div class="radio_filed">
                                             <label>
                                             <input name="event_space[]" value="Standard Power Supply" type="checkbox" 
                                                <?php if (in_array("Standard Power Supply", $selected_spaces)) echo 'checked'; ?>>
                                             Standard Power Supply (As before - we do not provide extension leads)
                                             </label><br>
                                             <label>
                                             <input name="event_space[]" value="Exhibition Desk(s)" type="checkbox" 
                                                <?php if (in_array("Exhibition Desk(s)", $selected_spaces)) echo 'checked'; ?>>
                                             Exhibition Desk(s) (Remember your table covering)
                                             </label><br>
                                             <label id="others_checkbox_label">
                                                <input name="event_space[]" value="Others" type="checkbox" id="others_checkbox"
                                                   <?php if (in_array("Others", $selected_spaces)) echo 'checked'; ?>
                                                   onchange="toggleOtherEquipmentField()"> <!-- Add onchange event here -->
                                                Others
                                             </label>
                                          </div>
                                       </div>
                                       <!-- The additional input field for "Others" -->
                                       <div id="other_equipment_field" style="display: none;">
    <label for="other_equipment">Please specify</label>
    <input type="text" id="other_equipment" name="other_equipment" class="form-control" value="<?php echo $row['other_equipment']; ?>">
    
    <label for="other_equipment_file">Please specify file</label>
    <!-- Set the default value of the file input field -->
    <input type="file" id="other_equipment_file" name="files" class="form-control">
    <input type="hidden" name="old_file" value="<?php echo $row['files']; ?>"> <!-- Store the old file name in a hidden field -->
    
    <!-- Link to view the current file -->
    <a href="<?php echo 'https://www.motiv8search.com/images/' . $row['files']; ?>" class="" target='_blank'>
        <i class="fa fa-eye" aria-hidden="true"></i> View Equipment
    </a>
</div>

                                    </div>
                                    <div class="modal-footer">
                                       <button type="button" class="btn btn-default"
                                          data-dismiss="modal">Close</button>
                                       <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </tr>
                     <?php
                        } else {
                            echo "Registration not found.";
                        }
                        } else {
                        echo "Invalid ID parameter.";
                        }
                        ?>
                  </tbody>
               </table>
               <div class="btn_btn">
                  <!-- <a href="https://www.motiv8search.com/payment-invoice-download?id=<?php echo $id; ?>" class="btn btn-primary">Download Payment Summary</a> -->
                  <button class="btn btn-success" data-toggle="modal" data-target="#editModal">Edit
                  Application</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>