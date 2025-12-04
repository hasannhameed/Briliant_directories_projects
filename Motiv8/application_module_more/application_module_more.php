<?php

  $user_data = getUser($_COOKIE['userid'], $w); // Get Current User Data
  $user_full_name = $user_data['first_name'] . ' ' . $user_data['last_name'];

  function activity_log($action, $reference_table, $reference_id, $description = '')
  {
    $user_id = isset($_COOKIE['userid']) ? intval($_COOKIE['userid']) : 0;
    $user_name = isset($user_full_name) ? mysql_real_escape_string($user_full_name) : (isset($user_data['company']) ? $user_data['company'] : $user_data['email']);

    $action            = mysql_real_escape_string($action);
    $reference_table   = mysql_real_escape_string($reference_table);
    $reference_id      = intval($reference_id);
    $description       = mysql_real_escape_string($description);
    $ip                = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
    $agent             = isset($_SERVER['HTTP_USER_AGENT']) ? mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']) : 'unknown';
    $now               = date('Y-m-d H:i:s');
    $sql = "INSERT INTO activity_log 
              (user_id, user_name, user_type, `action`, reference_table, reference_id, `description`, ip_address, user_agent, created_at)
              VALUES 
              ('$user_id', '$user_name', 'admin', '$action', '$reference_table', '$reference_id', '$description', '$ip', '$agent', '$now')
            ";

    mysql_query($sql);
    //echo $sql;
  }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $packages_section = mysql_real_escape_string($_POST['packages_section']);
    $package_amount = floatval($_POST['package_amount']);
    $payment_summary = floatval($_POST['payment_summary']);
    $add_on = isset($_POST['add_on']) ? implode(',', array_map('mysql_real_escape_string', $_POST['add_on'])) : '';
    $promo_code_section = $_POST['promo_code_section'];
    $discount = $_POST['discount_type'];
    $update_query = "UPDATE supplier_registration_form SET discount = '$discount', promo_code_section = '$promo_code_section', packages_section = '$packages_section', add_on = '$add_on', package_amount = '$package_amount', payment_summary = '$payment_summary' WHERE id = $id";
    
    $update_result = mysql_query($update_query);
    
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
                            window.location.href = 'https://ww2.managemydirectory.com/admin/go.php?widget=application_module_more&id=".$id."';
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

    if($update_result){
        activity_log('coupanApply','supplier_registration_form',$id,'Edit Application Details success');
    }else{
        activity_log('coupanApply','supplier_registration_form',$id,'Edit Application Details failed');
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
                            if (isset($_GET['id'])) {
                                $id = $_GET['id'];
                                $payment_summary = 0;
                                $sql = "SELECT * FROM `supplier_registration_form` WHERE `id` = $id";
                                $result = mysql_query($sql);
								$formType = '';
                                if (mysql_num_rows($result) > 0) {
                                    $row = mysql_fetch_assoc($result);
                                    $pid = $row['event_id'];
									$formType = ($pid >0) ? 'registration form' : 'credit form';
                                    $event_name = mysql_fetch_assoc(mysql_query("SELECT post_title FROM `data_posts` WHERE post_id = '$pid'"));
                            ?>

                                    <tr>
                                        <td class="left_data">Event Name</td>
                                        <td>
                                            <?php echo $event_name['post_title']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left_data">Application Id</td>
                                        <td>
                                            <?php echo $row['id']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left_data">Registration Date</td>
                                        <td>
                                            <?php echo $row['creation_date']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left_data">Full Name</td>
                                        <td>
                                            <?php echo $row['contact_person']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left_data">Job Title</td>
                                        <td>
                                            <?php echo $row['job_title']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left_data">Email</td>
                                        <td>
                                            <?php echo $row['email_address']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left_data">Phone Number</td>
                                        <td>
                                            <?php echo $row['phone_number']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left_data">Packages section</td>
                                        <td>
                                            <?php 
												if($formType === 'registration form'){
													echo $row['packages_section']; 
												}
											?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Addon section</td>
                                        <td>
                                            <?php echo $row['add_on']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Company Name</td>
                                        <td>
                                            <?php echo $row['company_name']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Email Invoice</td>
                                        <td>
                                            <?php echo $row['email_invoice']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Invoice Currency</td>
                                        <td>
                                            <?php echo $row['invoice_currency']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Purchase order number</td>
                                        <td>
                                            <?php echo $row['purchase_order_number']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Vat number</td>
                                        <td>
                                            <?php echo $row['vat_number']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Invoice address</td>
                                        <td>
                                            <?php echo $row['invoice_address']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Main contact</td>
                                        <td>
                                            <?php echo $row['main_contact']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Department interest</td>
                                        <td>
                                            <?php echo $row['department_intrest']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">
                                            Summary of what you'll be showing at the event
                                        </td>
                                        <td>
                                            <?php echo $row['brief_summary']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data">Manufacturer products</td>
                                        <td>
                                            <?php echo $row['manufacturer_products']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data payment_data">Payment Summary</td>
                                        <td class="payment_data" data="<?= $row['payment_summary'].' and '.$row['invoice_currency'] ?>">
                                            <?php
                                            function formatIndianCurrency($number) {
                                                $decimal = '';
                                                if (strpos($number, '.') !== false) {
                                                    list($number, $decimal) = explode('.', $number);
                                                    $decimal = '.' . $decimal;
                                                }
                                            
                                                $lastThree = substr($number, -3);
                                                $restUnits = substr($number, 0, -3);
                                                if ($restUnits != '') {
                                                    $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
                                                    $formatted = $restUnits . ',' . $lastThree;
                                                } else {
                                                    $formatted = $lastThree;
                                                }
                                            
                                                return $formatted . $decimal;
                                            }
                                        
                                            $numeric_only = preg_replace("/[^0-9.]/", "", $row['payment_summary']); // Allow decimals
                                            $payment_summary = $numeric_only;
                                            echo "£" . formatIndianCurrency($payment_summary);
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data summary_details">Packages Amount</td>
                                        <td class="summary_details">
                                            <?php 
                                            if($row['package_amount']!="" && $row['package_amount']>0){
                                                echo "£" . number_format($row['package_amount']);     
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data summary_details">Coupon Code</td>
                                        <td class="summary_details">
                                            <?php echo $row['promo_code_section']; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="left_data summary_details">Discount</td>
                                        <td class="summary_details">
                                            <?php
                                            $discount = $row['discount'];
                                            $discount_amount = preg_replace("/[^0-9]/", "", $discount);
                                            if ($discount_amount > 0) {
                                                if (strpos($discount, '%') !== false) {
                                                    // If the discount contains a percentage symbol, display as percentage
                                                    echo $discount;
                                                } else {
                                                    // Otherwise, display the discount with the currency symbol
                                                    echo '£'. number_format($discount_amount);
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="editModalLabel">Edit Application Details</h4>
                                                    </div>
                                                    <form action="/admin/go.php?widget=application_module_more&id=<?php echo $id; ?>" method="post" class="customform">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                            <input type="hidden" name="widget" value="application_module_more" >
                                                            <?php if($formType === 'registration form'): ?>
                                                            <?php
                                                                $packagequery  = "SELECT desktop_package, superbooth_package FROM create_application_pages WHERE event_id =" . $row['event_id'];
                                                                $packageresult = mysql_query($packagequery);
                                                                if ($packageresult) {
                                                                $packageRow                = mysql_fetch_assoc($packageresult);
                                                                $desktopPackage     = $packageRow['desktop_package'];
                                                                $superboothPackage  = $packageRow['superbooth_package'];
                                                                }
                                                            ?>
                                                            <div class="form-group">
                                                                <label>Packages Section</label><br>
                                                                <label class="rmargin"><input type="radio" name="packages_section" data-packages-amount="<?= $desktopPackage ?>" value="Desktop Package" <?php echo ($row['packages_section'] == 'Desktop Package') ? 'checked' : ''; ?>> Desktop Package</label>
                                                                <label class="rmargin"><input type="radio" name="packages_section" data-packages-amount="" value="Presentation Package" <?php echo ($row['packages_section'] == 'Presentation Package') ? 'checked' : ''; ?>> Presentation Package</label>
                                                                <label class="rmargin"><input type="radio" name="packages_section" data-packages-amount="<?= $superboothPackage ?>" value="SuperBooth Package" <?php echo ($row['packages_section'] == 'SuperBooth Package') ? 'checked' : ''; ?>> SuperBooth Package</label>
                                                                <label class="rmargin"><input type="radio" name="packages_section" data-packages-amount="" value="Other Package" <?php echo ($row['packages_section'] == 'Other Package') ? 'checked' : ''; ?>> Other Package</label>
                                                            </div>
                                                            <?php endif; ?>
                                                            
                                                            <div class="form-group">
                                                                <label>Add-on Section</label>
                                                                <select name="add_on[]" class="form-control select2" multiple="multiple" id="edit-addons">
                                                                    <?php 
                                                                    $addons_query = "SELECT * FROM add_ons";
                                                                    $addons_result = mysql_query($addons_query);
                                                                    
                                                                    while ($addon = mysql_fetch_assoc($addons_result)) {
                                                                        $selected = '';
                                                                        if (isset($row['add_on'])) {
                                                                            if (strpos($row['add_on'], ',') !== false) {
                                                                                $selected = in_array($addon['name'], explode(',', $row['add_on'])) ? 'selected' : '';
                                                                            } else {
                                                                                $selected = ($addon['name'] == $row['add_on']) ? 'selected' : '';
                                                                            }
                                                                        }
                                                                        echo "<option value='" . htmlspecialchars($addon['name']) . "' data-addons-price='" . $addon['price'] . "' $selected>" 
                                                                            . htmlspecialchars($addon['name']) . " - £" . htmlspecialchars($addon['price']) . 
                                                                            "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Package Amount (£)</label>
                                                                <input type="text" name="package_amount" id="edit-package-amount" class="form-control" value="<?php echo $row['package_amount']; ?>" readonly>
                                                            </div>
                                                            <?php if(isset($row['promo_code_section']) && !empty($row['promo_code_section'])): ?>
                                                            <div class="form-group">

                                                                <label for="edit-code" class="code">Coupon Code &nbsp; <span class='check badge'>Checking</span></label> 
                                                                <!-- <input type="text" name="promo_code_section" class="form-control" value="<?php echo $row['promo_code_section']; ?>"> -->

                                                                
                                                                <input type="text" name="promo_code_section" class="form-control" id="edit-code" value="<?php echo $row['promo_code_section']; ?>">
                                                                <!-- name="code" -->

                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label>Discount</label>
                                                                <input type="text" name="discount" class="form-control" value="<?php echo $row['discount']; ?>" readonly>
                                                            </div>
                                                            <?php endif; ?>
                                                            <div class="form-group">
                                                                <label for="">Payment Summary</label>
                                                                <?php  $numeric_only = preg_replace("/[^0-9]/", "", $row['payment_summary']); ?>
                                                                <input type="text" name="payment_summary" class="form-control" id="edit-payment-summary" value="<?php echo $numeric_only; ?>" >
                                                            </div>
                                                            <?php if(empty($row['promo_code_section'])){ ?>
                                                                <div class="form-group">
                                                                    <label for="edit-code" class="code">Coupon Code &nbsp; <span class='check badge'>Checking</span></label> 
                                                                    <input type="text"  name="promo_code_section" class="form-control" id="edit-code" value="">
                                                                    <!-- name="code" -->
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" id="saveprice">Save Changes</button>
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
                    <div class="btn_btn"><a
                            href="https://www.motiv8search.com/payment-invoice-download?id=<?php echo $id; ?>"
                            class="btn btn-primary">Download Payment Summary</a>
                            <button class="btn btn-success" data-toggle="modal" data-target="#editModal">Edit Application</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        function calculatePaymentSummary() {
            let packageAmount = parseFloat($("input[name='packages_section']:checked").data("packages-amount")) || 0;
            let addOnsTotal = 0;
            let discount = 0;
            // Calculate add-ons total
            $("#edit-addons option:selected").each(function () {
                addOnsTotal += parseFloat($(this).data("addons-price")) || 0;
            });
            
            let totalAmount = packageAmount + addOnsTotal;
            console.log("Package Amount: " + packageAmount);
            console.log("Add-ons Total: " + addOnsTotal);
            console.log("TotalAmount:", totalAmount);
            // Check if discount input exists
            if ($("input[name='discount']").length) {
                let discountValue = $("input[name='discount']").val().trim();
                if (discountValue) {
                    // Remove currency symbols before processing
                    discountValue = discountValue.replace(/[$£€]/g, '');
                    if (discountValue.includes('%')) {
                        let discountPercentage = parseFloat(discountValue.replace('%', ''));
                        discount = (totalAmount * discountPercentage) / 100;
                    } else {
                        discount = parseFloat(discountValue) || 0;
                    }
                }
            }
            console.log("Discount: " + discount);
            let finalAmount = totalAmount - discount;
            if (finalAmount < 0) finalAmount = 0;
            console.log('finalAmount:', finalAmount);
            // Update the payment summary input
            $("#edit-payment-summary").val(finalAmount.toFixed(2));
            $('#edit-package-amount').val(packageAmount);
        }
        
        // Event Listeners
        $("input[name='packages_section']").change(calculatePaymentSummary);
        $("#edit-addons").change(calculatePaymentSummary);
        $("input[name='discount']").on('input', calculatePaymentSummary);
        
        // Initial Calculation on Page Load
        calculatePaymentSummary();
    });
</script>
<script>
    let url = 'https://www.motiv8search.com/api/widget/json/get/find_code';
    let saveprice = document.getElementById('saveprice');
    let editcode = document.getElementById('edit-code');
    let code = document.querySelector('.check');
    let select2choices = document.querySelector('.select2-choices');
    let debounceTimer;

    let updatePrice = (data) => {
        let editPaymentSummaryEl = document.getElementById('edit-payment-summary');
        let couponInput = document.getElementById('edit-code'); // Reference input to insert after
        let originalAmount = parseFloat(editPaymentSummaryEl.value);

        console.log(data);
        window.myGlobalVariable = data;

        if (!isNaN(originalAmount)) {
            let discount = 0;
            let newAmount = originalAmount;
            let discountValueForInput = '';

            if (data.type === 'Percentage') {
                discount = (originalAmount * parseFloat(data.value)) / 100;
                newAmount = originalAmount - discount;
                discountValueForInput = `${data.value}%`;
                swal("Discount Applied!", `A ${data.value}% discount has been applied.`, "success");

            } else if (data.type === 'Price Override') {
                newAmount = parseFloat(data.value);
                discount = originalAmount - newAmount;
                discountValueForInput = `Overridden to £${data.value}`;
                swal("Price Overridden!", `Price has been overridden to £${newAmount.toFixed(2)}.`, "info");

            } else if (data.type === 'Fixed Amount') {
                discount = parseFloat(data.value);
                newAmount = originalAmount - discount;
                discountValueForInput = `£${data.value}`;
                swal("Fixed Discount Applied!", `A discount of £${discount.toFixed(2)} has been applied.`, "success");

            } else {
                swal("Oops...", "Your coupon is invalid!", "error");
                return;
            }

            // Set updated payment summary value
            editPaymentSummaryEl.value = newAmount.toFixed(2);

                insertSummaryFields(couponInput, originalAmount, discount, newAmount);

                createOrUpdateHiddenDiscountInput(discountValueForInput);
            }
    };

    function createOrUpdateHiddenDiscountInput(value) {
        let existingInput = document.querySelector('input[name="discount_type"]');

        if (existingInput) {
            existingInput.value = value;
        } else {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'discount_type';
            hiddenInput.value = value;

            const form = document.querySelector('form.customform');

            if (form) {
                form.appendChild(hiddenInput);
            }
        }
    }


    function insertSummaryFields(afterElement, subtotal, discount, total) {
        // Prevent duplicate rendering
        if (document.getElementById('price-summary')) return;

        const wrapper = document.createElement('div');
        wrapper.id = 'price-summary';
        wrapper.style.display = 'flex';
        wrapper.style.marginTop = '20px';
        wrapper.style.flexWrap = 'wrap';
        wrapper.style.gap = '10px';
        const createDisplayField = (labelText, value, id, color = null) => {
            const group = document.createElement('div');
            group.className = 'col-sm-12 flexx';

            // Add the border class to parent if color is provided (e.g., for discount)
            if (color) {
                group.classList.add('border');
            }

            const label = document.createElement('span');
            label.textContent = labelText;
            label.style.display = 'block';

            const valueDisplay = document.createElement('label');
            valueDisplay.className = 'form-control-plaintext';
            valueDisplay.id = id;
            valueDisplay.textContent = `£${value.toFixed(2)}`;

            if (color) {
                valueDisplay.style.color = color;
            }

            group.appendChild(label);
            group.appendChild(valueDisplay);
            return group;
        };


        wrapper.appendChild(createDisplayField('Subtotal (£)', subtotal, 'subtotal-amount'));
        wrapper.appendChild(createDisplayField('Discount (£)', discount, 'discount-amount', 'red')); // 🔴 Discount in red
        wrapper.appendChild(createDisplayField('Total (£)', total, 'total-amount'));

        afterElement.parentNode.insertBefore(wrapper, afterElement.nextSibling);
    }




    let fetchData = async (code, id) => {
        try {
            let obj = { code, id };
            let res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(obj)
            });
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            if(res.ok){
                let el = document.querySelector('.checking');
                if (el) {
                    el.classList.remove('checking');
                    el.classList.add('check');
                    saveprice.disabled = false;
                }
            }
            let data = await res.json();
            if(data.maxuses != 0 && data.maxuses == data.uses){
                swal("Oops...", "Your coupon Limit reached!", "error");
                return;
            }
            await updatePrice(data);
        } catch (e) {
            console.error('Fetch error:', e);
        }
    };

    let applyCode = () => {
        clearTimeout(debounceTimer);
        if(editcode!=''){
            debounceTimer = setTimeout(() => {
                let id = document.querySelector('input[name="id"]');
                code.classList.remove('check');
                code.classList.add('checking');
                saveprice.disabled = true;
                fetchData(editcode.value,id.value);
            }, 1000);
        }else{
            console.log('editcode',editcode);
        }
    };

    let updateUses = async (e) =>{
        if(editcode.value == ''){
            return;
        }
        console.log('myGlobalVariable',window.myGlobalVariable.input.code);
        try {
            let obj = {code:window.myGlobalVariable.input.code,uses:window.myGlobalVariable.uses};
            let res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(obj)
            });
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
        } catch (e) {
            console.error('Fetch error:', e);
        }
    }

    editcode.addEventListener('input', applyCode); 
    saveprice.addEventListener('click', updateUses); 

</script>
