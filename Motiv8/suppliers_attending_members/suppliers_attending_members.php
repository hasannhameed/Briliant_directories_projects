<?php
if($pars[0] === 'admin' && $pars[1] === 'go.php'){
global $sess;
$loginMember = $sess['admin_name'];
$message = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier_id = mysql_real_escape_string($_POST['supplier_id']);
    $selected_staff = isset($_POST['selected_staff']) ? $_POST['selected_staff'] : array();

$staff_ids = implode(',', array_map('mysql_real_escape_string', $selected_staff));


    $existingStaffQuery = mysql_query("SELECT staff_ids FROM supplier_attendingstaffs WHERE supplier_id = '$supplier_id'");
    
    if (mysql_num_rows($existingStaffQuery) > 0) {
        // **Directly replace the old values instead of merging**
        $query = "UPDATE supplier_attendingstaffs 
                  SET staff_ids = '$staff_ids', created_by = '$loginMember' 
                  WHERE supplier_id = '$supplier_id'";
    } else {
        $query = "INSERT INTO supplier_attendingstaffs (supplier_id, staff_ids, created_by) 
                  VALUES ('$supplier_id', '$staff_ids', '$loginMember')";
    }

    if (mysql_query($query)) {
        echo "<script>
              swal({
                  title: 'Action Successful!',
                  text: 'Attending Staff updated successfully.',
                  type: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'OK',
                  closeOnConfirm: false
              });
            </script>";
    } else {
        echo "<script>
              swal({
                  title: 'Error!',
                  text: 'Failed to update Attending Staff.',
                  type: 'error',
                  showCancelButton: false,
                  confirmButtonColor: '#d33',
                  confirmButtonText: 'OK'
              });
            </script>";
    }
}


?>
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="event_heading">Supplier Attending Staff</h2>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="form-group">
                            <input type="text" class="form-control" value="" placeholder="Search by Company" name="key" id="myInput">
                        </div>
                    </div>

                </div>
                <table style="width:100%" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <!-- <th>Supplier Name</th> -->
                            <th>Supplier Company</th>
                            <th style="width: 480px">Choose Attending Staff</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </thead>
                    <tbody id="supplier_table">
                        <?php
                        // Fetch supplier companies
                        $result = mysql_query("
                            SELECT ud.user_id, ud.first_name, ud.last_name, ud.company, ud.email FROM users_data AS ud LEFT JOIN live_events_posts AS lep ON ud.user_id = lep.user_id LEFT JOIN data_posts AS dp ON lep.post_id = dp.post_id WHERE ud.subscription_id IN(30, 33, 36) GROUP BY ud.user_id ORDER BY lep.id DESC;
                        ");
                        //echo "SELECT ud.user_id, ud.first_name, ud.last_name, ud.company, ud.email FROM users_data AS ud LEFT JOIN live_events_posts AS lep ON ud.user_id = lep.user_id LEFT JOIN data_posts AS dp ON lep.post_id = dp.post_id WHERE ud.subscription_id IN(30, 33, 36) GROUP BY ud.user_id ORDER BY lep.id DESC;";
                        // Fetch all staff for the dropdown (outside the loop)
                        $staffQuery = mysql_query("SELECT user_id, first_name, last_name, email FROM users_data ORDER BY company ASC");
                        //echo "SELECT user_id, first_name, last_name, email FROM users_data ORDER BY company ASC";
                        $staff = array();
                        while ($staffRow = mysql_fetch_assoc($staffQuery)) {
                            $staff[] = $staffRow;
                        }

                        // Loop through each supplier
                        $i = 1;
                        $fullname='Unknown';
                        while ($row = mysql_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$row['user_id']}</td>";
                            //echo "<td>{$row['first_name']} {$row['last_name']}</td>";
                            echo "<td>" . (!empty($row['company']) ? $row['company'] : $row['email']) . "</td>";

                            // Dropdown for selecting staff
                            
$supplier_id = $row['user_id'];
$staffCountQuery = mysql_query("SELECT staff_ids FROM supplier_attendingstaffs WHERE supplier_id = '$supplier_id'");
$staffCount = 0;

if (mysql_num_rows($staffCountQuery) > 0) {
    $staffRow = mysql_fetch_assoc($staffCountQuery);
    $staffCount = count(array_filter(explode(',', $staffRow['staff_ids'])));
}

echo '<td>
        <form action="https://ww2.managemydirectory.com/admin/go.php?widget=suppliers_attending_members" method="post" 
        style="display: flex;justify-content: space-between;align-items: center;gap: 5px;">
            <input type="hidden" name="supplier_id" value="'.$supplier_id.'">
            <input type="hidden" name="widget" value="suppliers_attending_members">

            <div class="multi-select-dropdown">
                <div class="cpa-control form-control" onclick="toggleDropdown()" style="padding-top: 8px;">
                    Select Staff <span id="selectedCount" data-supplier-id="'.$supplier_id.'">('.$staffCount.')</span>
                </div>
                <div class="dropdown-list" id="dropdownList" style="display: none;">
                    <input type="text" id="searchInput" class="search-box" placeholder="Search attending staff by name, company, and email..." autocomplete="off">
                    <div class="dropdown-items">
                        <label class="dropdown-item no-results">
                            <input type="checkbox" disabled style="margin: 2px;"> Search attending staff by name, company, and email...
                        </label>
                    </div>
                </div>
            </div>
            <button class="btn btn-success add_staff">Add Staff</button>
        </form>
      </td>';

                            echo "</tr>";
                            $i++;
                        }
                        ?>
                        
                    </tbody>

                </table>
            </div>
            <div class="col-md-4 text-right">
                <?php echo $message; ?>  <!-- Display Bootstrap Alert Message -->
            </div>
        </div>
    </div>
</section>
<style>
    /* Styles for Dropdown */
    .multi-select-dropdown {
        position: relative;
        width: auto;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        cursor: pointer;
        width: 100%;
    }

    .dropdown-list {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid #ccc;
        background-color: #fff;
        z-index: 1000;
        padding: 5px;
    }

    .search-box {
        width: 100%;
        padding: 5px!important;
        margin-bottom: 10px!important;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
        font-size: 12px;
        white-space: nowrap;
    }

    .dropdown-item input {
        margin-right: 8px;
    }
</style> 
<script>
$(document).ready(function () {
    let searchTimeout;

    $(".multi-select-dropdown").each(function () {
        const dropdown = $(this);
        const searchInput = dropdown.find(".search-box");
        const dropdownList = dropdown.find(".dropdown-list");
        const selectedCount = dropdown.find("#selectedCount");
        const supplierId = selectedCount.data("supplier-id");
        const staffItemsContainer = dropdown.find(".dropdown-items");
        const addStaffButton = dropdown.closest("form").find(".add_staff"); // Locate "Add Staff" button

        // Initially disable the button
        addStaffButton.prop("disabled", true);

        // Toggle dropdown visibility
        dropdown.find(".cpa-control").on("click", function () {
            $(".dropdown-list").not(dropdownList).hide();
            dropdownList.toggle();
        });

        // Search staff on typing (min 4 characters)
        searchInput.on("keyup", function () {
            clearTimeout(searchTimeout);
            let searchTerm = searchInput.val().trim();

            if (searchTerm.length >= 4) {
                searchTimeout = setTimeout(() => {
                    fetchStaffData(searchTerm, staffItemsContainer, supplierId);
                }, 300);
            } else {
                staffItemsContainer.html("");
            }
        });

        function fetchStaffData(searchTerm, staffItemsContainer, supplierId) {
            $.ajax({
                url: 'https://www.motiv8search.com/api/widget/json/get/get_suppliers_attending_staff_ajax',
                type: 'GET',
                dataType: 'json',
                data: { search: searchTerm },
                success: function (response) {
                    if (Array.isArray(response) && response.length > 0) {
                        updateDropdownItems(response, staffItemsContainer, supplierId);
                    } else {
                        staffItemsContainer.html("<p class='no-results'>No results found</p>");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching staff data:", error);
                }
            });
        }

        function updateDropdownItems(staffData, staffItemsContainer, supplierId) {
            staffItemsContainer.html("");
            if (staffData.length > 0) {
                staffData.forEach(function (staff) {
                    let fullname = staff.fullname.trim() !== "" ? staff.fullname : "Unknown Name";
                    let email = staff.email ? `(${staff.email})` : "";
                    let company = staff.company ? ` - ${staff.company}` : "";
                    let checked = staff.supplier_id == supplierId ? "checked" : ""; // Pre-select if applicable

                    staffItemsContainer.append(`
                        <label class="dropdown-item">
                            <input type="checkbox" name="selected_staff[]" value="${staff.uid}" style="margin: 2px;" ${checked}>
                            ${fullname} ${email} ${company}
                        </label>`);
                });

                // Ensure the button state is updated when checkboxes are pre-checked
                updateButtonState();
            } else {
                staffItemsContainer.append("<p class='no-results'>No results found</p>");
            }
        }

        function updateButtonState() {
            const checkboxes = dropdownList.find('.dropdown-item input[type="checkbox"]');
            const checkedCount = checkboxes.filter(":checked").length;

            // Fix: Enable button even if all are unchecked, but keep it disabled if no checkboxes exist
            addStaffButton.prop("disabled", checkboxes.length === 0);
            selectedCount.text(`(${checkedCount})`);
        }

        // ✅ Fix: Use event delegation to detect checkbox changes dynamically
        dropdown.on("change", ".dropdown-item input[type='checkbox']", function () {
            updateButtonState();
        });
    });

    // Close dropdown when clicking outside
    $(document).click(function (event) {
        if (!$(event.target).closest('.multi-select-dropdown').length) {
            $(".dropdown-list").hide();
        }
    });
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