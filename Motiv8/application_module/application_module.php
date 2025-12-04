<!-- application module -->
<?php
if($pars[0] === 'admin' && $pars[1] === 'go.php'){
	
function log_Activity($action, $reference_table, $reference_id, $description = '') {
    global $sess; 
    $user_id = isset($sess['admin_id']) ? $sess['admin_id'] : 0;
    $user_name = isset($sess['admin_name']) ? $sess['admin_name'] : $sess['admin_user'];
    $user_type = 'admin';
    $ip = $_SERVER['REMOTE_ADDR'];
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
    $now = date('Y-m-d H:i:s');
    $descriptionEscaped = mysql_real_escape_string($description);
    $sql = "INSERT INTO activity_log (user_id, user_name, user_type, `action`, reference_table, reference_id, `description`, ip_address, user_agent, created_at)
            VALUES ('$user_id', '$user_name', '$user_type', '$action', '$reference_table', '$reference_id', '$descriptionEscaped', '$ip', '$agent', '$now')";

    mysql_query($sql);
}
    global $sess; 
    $loggedname = $sess['admin_name'];
    $loggeduser = $sess['admin_user']; 

if (isset($_GET['method']) && isset($_GET['delete_id']) && $_GET['delete_id'] != "") {
    $deleteID = intval($_GET['delete_id']); // Prevent SQL injection

    // Delete supplier, live event posts, and attending staff 
    $result = mysql_query("SELECT id FROM live_events_posts WHERE supplier_id=$deleteID");

    $delete_log = mysql_query("INSERT INTO log_delete (loggedname, loggeduser, delete_type, deleted_id) 
                               VALUES ('$loggedname', '$loggeduser', 'Registration_Invoice', '$deleteID')");
    while ($row = mysql_fetch_assoc($result)) {
        mysql_query("DELETE FROM attending_staff_attendance WHERE lep_id={$row['id']}");
        log_Activity('Delete', 'attending_staff_attendance', $row['id'], 'Deleted attending staff for live event post with ID: ' . $row['id'] . ' for supplier with ID: ' . $deleteID);
    }
    // 1. Get event_id and add_on from the supplier row
/*$supplierData = mysql_fetch_assoc(mysql_query("SELECT event_id, add_on FROM supplier_registration_form WHERE id = $deleteID"));
if ($supplierData) {
    $eventID = intval($supplierData['event_id']);
    $addOnName = mysql_real_escape_string($supplierData['add_on']);

    // 2. Lookup add_on ID by name from add_ons table
    $addOnResult = mysql_fetch_assoc(mysql_query("SELECT id FROM add_ons WHERE name = '$addOnName'"));
    if ($addOnResult) {
        $addOnID = $addOnResult['id'];

        // 3. Fetch existing add_ons_stock from create_application_pages
        $capResult = mysql_fetch_assoc(mysql_query("SELECT add_ons_stock FROM create_application_pages WHERE event_id = $eventID"));
        if ($capResult) {
           echo $stockJson = $capResult['add_ons_stock'];
            $stockArray = json_decode($stockJson, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                // 4. Increment the add_on stock count
                if (isset($stockArray[$addOnID])) {
                    $stockArray[$addOnID] += 1;
                } else {
                    $stockArray[$addOnID] = 1;
                }

                // 5. Save updated stock JSON
                $updatedStockJson = mysql_real_escape_string(json_encode($stockArray));
				echo "UPDATE create_application_pages SET add_ons_stock = '$updatedStockJson' WHERE event_id = $eventID";
                mysql_query("UPDATE create_application_pages SET add_ons_stock = '$updatedStockJson' WHERE event_id = $eventID");
            } else {
               echo "Error in json";
            }
        }
    }
}*/
	
$supplierData = mysql_fetch_assoc(mysql_query("SELECT event_id, add_on FROM supplier_registration_form WHERE id = $deleteID"));
if ($supplierData) {
    $eventID = intval($supplierData['event_id']);
    $addOnNames = explode(',', $supplierData['add_on']); // Handle multiple add-ons

    $capResult = mysql_fetch_assoc(mysql_query("SELECT add_ons_stock FROM create_application_pages WHERE event_id = $eventID"));
    if ($capResult) {
        $stockJson = $capResult['add_ons_stock'];
        $stockArray = json_decode($stockJson, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($addOnNames as $addonName) {
                $addonName = trim($addonName);
                if ($addonName == '') continue;

                $addOnRow = mysql_fetch_assoc(mysql_query("SELECT id FROM add_ons WHERE name = '" . mysql_real_escape_string($addonName) . "' LIMIT 1"));
                if ($addOnRow) {
                    $addonId = $addOnRow['id'];
                    if (isset($stockArray[$addonId])) {
                        $stockArray[$addonId] += 1; // Increment the deleted add-on
                    } else {
                        $stockArray[$addonId] = 1; // Set to 1 if not present
                    }
                } else {
                    echo "Add-on '$addonName' not found.<br>";
                }
            }

            $updatedStockJson = mysql_real_escape_string(json_encode($stockArray));
            $updateQuery = "UPDATE create_application_pages SET add_ons_stock = '$updatedStockJson' WHERE event_id = $eventID";
            $updateResult = mysql_query($updateQuery);

            if (!$updateResult) {
                die("Stock update failed: " . mysql_error());
            }
        } else {
            echo "Invalid JSON in add_ons_stock";
        }
    }
}


// 6. Now delete the supplier form row
mysql_query("DELETE FROM supplier_registration_form WHERE id = $deleteID");
log_Activity('Delete', 'supplier_registration_form', $deleteID, 'Deleted supplier registration form with ID: ' . $deleteID);

    mysql_query("DELETE FROM live_events_posts WHERE supplier_id=$deleteID");
    log_Activity('Delete', 'live_events_posts', $deleteID, 'Deleted live event posts(Booth) for supplier with ID: ' . $deleteID);
    
}
?>


<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="registation_form">
                    <h2>Manage Registrations</h2>
                    <div class="bulk-action pull-left">
                        <div id="bottom-actions">
                            <form id="event-form" method="get" action="https://ww2.managemydirectory.com/admin/go.php">
								
								<input type="text" class="select-sm bulk_action" placeholder="Search by keyword" id="event_searchInput" style="min-height: 30px; border-radius: 2px;color:black;">
								
                                <input type="hidden" name="widget" value="application_module">
                                <select class="select-sm bulk_action" name="filterByCredit" id="filterByCredit" style="border-radius: 2px;color:black;">
                                    <option value="">Sort By Event Credits</option>
                                    <?php
                                    $sqlcredit = mysql_query("SELECT * FROM `create_application_pages` WHERE type = 'credit form' ORDER BY `create_application_pages`.`subheading` ASC");
                                    
                                    while ($row = mysql_fetch_assoc($sqlcredit)) {
                                        $post_title = strlen($row['subheading']) > 30? substr($row['subheading'], 0, 30) . "..." : $row['subheading'];
                                        echo '<option value="'. $row['subheading']. '">'. $post_title. '</option>';
                                    }
                                    ?>
                                </select>
                                <select class="select-sm bulk_action" name="bulk_action" id="bulk-action-type" style="border-radius: 2px;color:black;">
                                    <option value="">Sort By Event</option>
                                    <?php
                                    $selectquery = mysql_query("SELECT * FROM `data_posts` WHERE data_id = '73' AND post_status = '1' ORDER BY `post_id` DESC");
                                    while ($row = mysql_fetch_assoc($selectquery)) {
                                        $post_title = strlen($row['post_title']) > 25 ? substr($row['post_title'], 0, 25) . "..." : $row['post_title'];
                                        echo '<option value="' . $row['post_id'] . '">' . $post_title . '</option>';
                                    }
                                    ?>
                                </select>
                                <input type="submit" value="Apply" class="sbuttonwiz sbuttonwiz-sm white-bg perform-bulk">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>
                                <th style="width: 250px;">Event Name</th>
                                <th style="width: 150px;">Full Name</th>
                                <th style="width: 200px;">Email</th>
                                <th style="width: 200px;">Company Name</th>
                                <th style="width: 100px;">Payment Summary(Total)</th>
                                <th style="width: 170px;" class="text-center">Action</th>
                                <th style="width: 20px;">Invoiced</th>
                                <th style="width: 20px;">Paid</th>
                                <th style="width: 20px;">Invoice link</th>
                            </tr>
                        </thead>
                        <tbody id="app_tbody">
                            <?php
                            if (isset($_GET['bulk_action']) && $_GET['bulk_action'] != "") {
                                $where = "WHERE `supplier_registration_form`.`event_id` = " . $_GET['bulk_action'];
                            }elseif (isset($_GET['filterByCredit']) && $_GET['filterByCredit'] != ""){
                                $where = "WHERE `create_application_pages`.`subheading` = '". $_GET['filterByCredit']. "' AND `create_application_pages`.`type` = 'credit form'";
                                
                            } 
                            
                            else {
                                $where = "";
                            }
                            $oldsql = "SELECT `supplier_registration_form`.*, `create_application_pages`.`subheading` AS event_title FROM `supplier_registration_form` JOIN `create_application_pages` ON `supplier_registration_form`.`event_id` = `create_application_pages`.`event_id` " . $where . " ORDER BY `supplier_registration_form`.id DESC";

                            $supplier_resgistration_form_sql = "SELECT `supplier_registration_form`.*, `create_application_pages`.`subheading` AS event_title FROM `supplier_registration_form` JOIN `create_application_pages` ON `supplier_registration_form`.`form_id` = `create_application_pages`.`id` " . $where . " ORDER BY `supplier_registration_form`.id DESC";

                            $result = mysql_query($supplier_resgistration_form_sql);
                            //echo mysql_num_rows($result);

                            $serialNumber = 1;
                            if (mysql_num_rows($result) > 0) {
                                while ($row = mysql_fetch_assoc($result)) {

                                    if ($row['complete_status'] == 1 && $row['paid_status'] != 1) {
                                        $class = "invoiceActive";
                                    } elseif ($row['paid_status'] == 1) {
                                        $class = "paidActive";
                                    } else {
                                        $class = "";
                                    }
									$user_data = getUser($row['user_id'], $w);
									$postUrl = 'https://ww2.managemydirectory.com/admin/go.php?widget=booths-for-video-publishing-plugin&post_id=' . $row['event_id'];
									$supplierUrl = 'https://ww2.managemydirectory.com/admin/go.php?widget=suppliers_overview&key=' . $user_data['email'];
                            ?>
                                    <tr class="<?= $class ?>">
                                        <td>
                                            <?php echo $serialNumber; ?>
                                        </td>
                                        <td>
											<a href="<?= $postUrl ?>" target="_blank" class="btn-link nopad">
                          						<strong><?php echo $row['event_title'] ?></strong>
                        					</a>
                                        </td>
                                        <td>
                                            <?php echo $row['contact_person']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['email_address']; ?>
                                        </td>
                                        <td>
                                            <?php //echo $row['company_name']; ?>
											<a href="<?= $supplierUrl ?>" target="_blank" class="btn-link nopad">
                          						<strong><?php echo $row['company_name'] ?></strong>
                        					</a>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                $value = $row['payment_summary'];
                                                $clean_value = trim(str_replace('£', '', $value));
                                                echo '£' . $clean_value;
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="https://ww2.managemydirectory.com/admin/go.php?widget=application_module_more&id=<?php echo $row['id']; ?>" class="btn btn-primary btn_more" target="/">View Details</a>
                                            <a class="btn btn-danger btn_more" href="javascript:void(0);" onclick="decision('This will delete both the Supplier Form and the Supplier Card.','/admin/go.php?widget=application_module&method=Delete&delete_id=<?php echo $row['id']; ?>&bulk_action=<?php echo $_GET['bulk_action']; ?>');"><i class="fa fa-times fa-fw"></i> Delete</a>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="invoice_status" id="invoice_status<?= $serialNumber ?>" data-id="<?= $row['id'] ?>" <?php echo ($row['complete_status'] == 1) ? 'checked' : ''; ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="paid_status" id="paid_status<?= $serialNumber ?>" data-id="<?= $row['id'] ?>" <?php echo ($row['paid_status'] == 1) ? 'checked' : ''; ?>>
                                        </td>
                                        <td class="text-center invoice-url-cell" data-id="<?= $row['id']; ?>">
                                            <?php if (!empty($row['invoice_url'])): ?>
                                                <span style="display: inline-flex; align-items: center; gap: 6px;">
                                                    <a href="<?= $row['invoice_url']; ?>" class="btn btn-primary" target="_blank">View Link</a>
                                                    <i class="fa fa-edit fa-fw edit-icon" style="cursor: pointer;"></i>
                                                </span>
                                            <?php else: ?>
                                                <input type="text" class="invoice-input" name="invoice_url" data-id="<?= $row['id']; ?>" value="">
                                            <?php endif; ?>
                                        </td>
                                            
                                    </tr>
                                    <script>
                                        $('invoice_status<?= $serialNumber ?>').click(function(e) {
                                            //e.preventDefault();

                                        });
                                    </script>
                            <?php
                                    $serialNumber++;
                                }
                            } else {
                                echo "<tr><td colspan='15'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    tbody#app_tbody tr.invoiceActive {
        background: #f9f871;
    }
    
    tbody#app_tbody tr.paidActive {
        /* background: #ceca; */
        background: #8beb8baa;
    }
</style>
<link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');" href="https://www.optimizecdn.com/directory/cdn/bootstrap/sweetalert/sweetalert2/dist/sweetalert2.css">
<noscript>
    <link rel="stylesheet" href="https://www.optimizecdn.com/directory/cdn/bootstrap/sweetalert/sweetalert2/dist/sweetalert2.css">
</noscript>
<script src="https://www.optimizecdn.com/directory/cdn/bootstrap/sweetalert/sweetalert2/dist/sweetalert2.min.js"></script>
<script>
    $(document).on('click', '.edit-icon', function () {
    const td = $(this).closest('.invoice-url-cell');
    const id = td.data('id');
    const currentUrl = td.find('a').attr('href') || '';

    // Replace the cell contents with input
    td.html(`
        <input type="text" class="invoice-input" name="invoice_url" data-id="${id}" value="${currentUrl}">
    `);
});

// If user presses Enter in the input, trigger blur/save
$(document).on('keypress', '.invoice-input', function (e) {
    if (e.which === 13) {
        $(this).blur(); // triggers the existing AJAX
    }
});

    // jQuery script for handling checkbox click and text input with AJAX
    $(document).ready(function() {

        // Handle invoice_status checkbox
        $('[name="invoice_status"]').on('click', function(e) {
            handleCheckboxUpdate($(this), 'invoice_status');
        });

        // Handle paid_status checkbox
        $('[name="paid_status"]').on('click', function(e) {
            handleCheckboxUpdate($(this), 'paid_status');
        });

        // Debounced handler for invoice_url text input
        let debounceTimer;
        $(document).on('input', '[name="invoice_url"]', function(e) {
            clearTimeout(debounceTimer);
            const input = $(this);
            debounceTimer = setTimeout(function() {
                handleInputTextUpdate(input, 'invoice_url');
            }, 1000); // Adjust debounce delay as needed
        });

        // Handle checkbox updates
        function handleCheckboxUpdate(checkbox, statusType) {
            var statusValue = checkbox.prop('checked') ? 1 : 0;
            var id = checkbox.data('id');

            $.ajax({
                url: 'https://www.motiv8search.com/api/widget/html/get/update_status',
                method: 'POST',
                data: {
                    id: id,
                    status_type: statusType,
                    status_value: statusValue
                },
                dataType: 'html',
                success: function(response) {
                    if (response.trim() === 'updated') {
                        swal({
                            type: 'success',
                            title: 'Success!',
                            text: 'Changes Saved!',
                        });
                        setTimeout(function() {
                            swal.closeModal();
                            window.location.reload();
                        }, 850);
                    } else {
                        swal("Error!", response, "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    swal("Error!", xhr.responseText, "error");
                }
            });
        }

        // Handle input text updates
        function handleInputTextUpdate(input, statusType) {
            var statusValue = input.val();
            var id = input.data('id');
            if (statusType === 'invoice_url' && statusValue !== '') {
        if (!/^https?:\/\//i.test(statusValue)) {
            statusValue = 'https://' + statusValue;
        }
    }
            $.ajax({
                url: 'https://www.motiv8search.com/api/widget/html/get/update_status',
                method: 'POST',
                data: {
                    id: id,
                    status_type: statusType,
                    status_value: statusValue
                },
                dataType: 'html',
                success: function(response) {
                    if (response.trim() === 'updated') {
                        swal({
                            type: 'success',
                            title: 'Success!',
                            text: 'Changes Saved!',
                        });
                        setTimeout(function() {
                            swal.closeModal();
                             window.location.reload(); // Optional: uncomment if a reload is needed
                        }, 850);
                    } else {
                        swal("Error!", response, "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    swal("Error!", xhr.responseText, "error");
                }
            });
        }
    });
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