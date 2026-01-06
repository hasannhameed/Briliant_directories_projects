<script>
    $(document).ready(function() {
        var originalSubtotal = parseFloat($("#subtotalAmount").text().substring(1));
        var originalTotal = originalSubtotal;

        // Function to update order summary
        function updateOrderSummary() {
            var selectedAddOns = $("input[name='add_ons[]']:checked");
            var addOnsTotal = 0;
            selectedAddOns.each(function() {
                var addOnPrice = parseFloat($(this).data("price"));
                if (!isNaN(addOnPrice)) {
                    addOnsTotal += addOnPrice;
                }
            });
            console.log("addOnsTotal= " + addOnsTotal);
            // var packageTotal = parseFloat($("input[name='packages_section']:checked").siblings("span").text().substring(1));
            var packageTotal = parseFloat($("input[name='packages_section']:checked").siblings("span").data("price"));
            console.log("packageTotal= " + packageTotal);

            if (isNaN(packageTotal)) {
                packageTotal = 0;
            }

            originalSubtotal = packageTotal + addOnsTotal;
            console.log("originalSubtotal= " + originalSubtotal);
			$("#payment_summary").val("£" + originalSubtotal.toLocaleString('en-US'));
            //var promoDiscount = parseFloat($(".promo-discount td.alnright").text().substring(1));
            //var discountPercentage = parseFloat($("#promoDiscountAmount").text());
            var discountText = $("#promoDiscountAmount").text();
            var discountValue = parseFloat(discountText.replace(/[^0-9.]/g, ''));
            console.log("discountValue= "  + discountValue);

            if (isNaN(discountValue)) {
                discountValue = 0;
            }
            if(discountValue === 100){
                var discountedamt = (discountValue / 100) * originalSubtotal;
                console.log(" discountedamt= " + discountedamt);
                var discountedTotal = originalSubtotal - discountedamt;
            }else{
                //var discountedamt = (discountPercentage / 100) * originalSubtotal;
                console.log(" discountedamt= " + discountValue);
                var discountedTotal = originalSubtotal - discountValue;
            }

            //var discountedTotal = originalSubtotal - promoDiscount;

            $(".subtotal td.alnright").text("£" + originalSubtotal.toLocaleString('en-US'));
            //$(".total td.alnright").text("£" + discountedTotal.toLocaleString('en-US'));
            $("#totalAmount").text("£" + discountedTotal.toLocaleString('en-US'));
			$("#payment_summary").val("£" + discountedTotal.toLocaleString('en-US'));
            $("#discount_input").val(discountedTotal);

            // Update promo discount amount
            //$(".promo-discount td.alnright").text("£" + promoDiscount.toLocaleString('en-US'));
			//$("#promoDiscountAmount").text(promoDiscount + "%");

        }

        // For add ons
        $("input[name='add_ons[]']").on("change", function() {
            updateOrderSummary();
        });

        // For package
        $("input[name='packages_section']").on("change", function() {
            updateOrderSummary();
        });
        // for invoice currency update
        $("input[name='invoice_currency']").on("change", function() {
            var inv_curr = $(this).val();
            var adminAmount = 0;
            if (inv_curr == "$" || inv_curr == "€") {
                adminAmount = 60;
                $(".admin-amount").removeClass("hide");
            } else {
                adminAmount = 0;
                $(".admin-amount").addClass("hide");
            }
            var r = "+ " + inv_curr + adminAmount;
            $("#adminAmount").text(r);

        });

        // For promocode
        
        let promoCodeApplied = false;

$("#coupon_code").on("click", function () {
    promoCodeApplied = true;
    var promoCodeValue = $('#promo_code_section').val();

    $.ajax({
        type: "get",
        url: '/api/widget/json/get/Bootstrap Theme - Function - Form Promotion Code Validation',
        data: {
            refcode: promoCodeValue
        },
        success: function (response) {
            console.log(response);
            console.log("originalsubtotal in coupon = " + originalSubtotal);

            if (response['valid'] === true) {
                function extractDiscount(discountText) {
                    let percentageMatch = discountText.match(/(\d+)%/);
                    if (percentageMatch) {
                        return { type: 'percentage', value: parseFloat(percentageMatch[1]) };
                    }

                    let amountMatch = discountText.match(/[\u00a3\$€]?(\d{1,3}(?:,\d{3})*(?:\.\d{2})?)/);
                    if (amountMatch) {
                        return { type: 'amount', value: parseFloat(amountMatch[0].replace(/[^0-9.-]+/g, '')) };
                    }

                    return null;
                }

                let discount = extractDiscount(response['discount_text']);
                if (discount) {
                    let discountAmount, discountMessage;
                    if (discount.type === 'percentage') {
                        discountAmount = (discount.value / 100) * originalSubtotal;
                        discountMessage = discount.value + "%";
                    } else if (discount.type === 'amount') {
                        discountAmount = discount.value;
                        discountMessage = "£" + discountAmount.toLocaleString('en-US', { maximumFractionDigits: 2 });
                    } else {
                        console.log("Discount with price override Object:", discount);
                    }

                    if (discountAmount < 0) {
                        discountAmount = 0;
                    }

                    console.log("discountAmount = " + discountAmount);
                    console.log("Discount Object:", discount);
                    console.log("originalSubtotal:", originalSubtotal);

                    $("#promo-code-alert-message")
                        .text("Event Credit Code is valid! " + discountMessage + " discount applied.")
                        .removeClass("alert-danger")
                        .addClass("alert-success")
                        .show();

                    var discountedTotal = originalSubtotal - discountAmount;

                    console.log("discountedTotal = " + discountedTotal);

                    var formattedAmount = discountAmount.toLocaleString('en-US', {
                        maximumFractionDigits: 0
                    });

                    const oldPaymentSummary = document.getElementById("payment_summary");
                    if (oldPaymentSummary) oldPaymentSummary.remove();

                    const oldTotalAmount = document.getElementById("totalAmount");
                    if (oldTotalAmount) oldTotalAmount.remove();

                    const ta2 = document.createElement("td");
                    const ps2 = document.createElement("input");

                    ps2.type = "hidden";
                    ps2.name = "payment_summary";
                    ps2.id = "payment_summary2";
                    ps2.value = discountedTotal;

                    ta2.className = "alnright";
                    ta2.id = "totalAmount2";
                    ta2.textContent = "£" + discountedTotal.toLocaleString();

                    const totalRow = document.querySelector(".total");

                    const existingTotalAmount2 = document.getElementById("totalAmount2");
                    if (existingTotalAmount2) {
                        existingTotalAmount2.textContent = "£" + discountedTotal.toLocaleString();
                    } else {
                        totalRow.appendChild(ta2);
                    }

                    const existingPaymentSummary2 = document.getElementById("payment_summary2");
                    if (existingPaymentSummary2) {
                        existingPaymentSummary2.value = discountedTotal;
                    } else {
                        totalRow.appendChild(ps2);
                    }

                    $("#promoDiscountAmount").text(discountMessage);
                    $("#discount_input").val(discountAmount);

                    $(".promo-discount").show();

                    updateOrderSummary();
                }
            } else {
                $("#promo-code-alert-message")
                    .text("Event Credit Code is not valid.")
                    .addClass("alert-danger")
                    .show();
                $(".promo-discount").hide();
                updateOrderSummary();
            }
        }
    });
});

$(".supplier_form").on("submit", function (e) {
    if (!promoCodeApplied) {
        $('#promo_code_section').val(''); // Clear promo code if not applied
    }
});

        updateOrderSummary();
    });
</script>

<script>
    //For insert total amount
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"].addon_checkbox');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('click', function() {
                const row = this.closest('.form-check');
                toggleRow(row);
            });
        });

        const registerButton = document.getElementById('registerButton');
        registerButton.addEventListener('click', function() {
            const totalAmount = document.getElementById('totalAmount').textContent;
            const paymentSummaryInput = document.getElementById('payment_summary');
            paymentSummaryInput.value = totalAmount;
        });
    });


    //For insert discount amount
    document.addEventListener('DOMContentLoaded', function() {
        const registerButton = document.getElementById('registerButton');

        registerButton.addEventListener('click', function() {
            const promoDiscountAmount = document.getElementById('promoDiscountAmount').textContent;
            const discountInput = document.getElementById('discount_input');
            discountInput.value = promoDiscountAmount;
        });
    });
</script>

<!-- From Validation -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector(".supplier_form");
        const registerButton = document.getElementById('registerButton');
        form.addEventListener("submit", function(event) {
            var isValid = true;

            var fullName = document.getElementById("contact_person").value;
            if (fullName.trim() === "") {
                isValid = false;
                document.querySelector(".contact_person").textContent = "Full Name is required.";
            } else {
                document.querySelector(".contact_person").textContent = "";
            }

            var jobTitle = document.getElementById("job_title").value;
            if (jobTitle.trim() === "") {
                isValid = false;
                document.querySelector(".job_title").textContent = "Job Title is required.";
            } else {
                document.querySelector(".job_title").textContent = "";
            }

            var phone_number = document.getElementById("phone_number").value;
            if (phone_number.trim() === "") {
                isValid = false;
                document.querySelector(".phone_number").textContent = "Phone number is required.";
            } else {
                document.querySelector(".phone_number").textContent = "";
            }

            var packagesSection = document.querySelectorAll("input[name='packages_section']");
            var selectedPackage = false;
            packagesSection.forEach(function(radio) {
                if (radio.checked) {
                    selectedPackage = true;
                }
            });

            if (!selectedPackage) {
                isValid = false;
                document.querySelector(".Package").textContent = "Please select a package.";
            } else {
                document.querySelector(".Package").textContent = "";
            }

            var company_name = document.getElementById("company_name").value;
            if (company_name.trim() === "") {
                isValid = false;
                document.querySelector(".company_name").textContent = "Company Name is required.";
            } else {
                document.querySelector(".company_name").textContent = "";
            }

            var invoice_email = document.getElementById("invoice_email").value;
            if (invoice_email.trim() === "") {
                isValid = false;
                document.querySelector(".email_invoice").textContent = "Email invoice is required.";
            } else {
                document.querySelector(".email_invoice").textContent = "";
            }

            var invoiceCurrency = document.querySelector("input[name='invoice_currency']:checked");
            if (!invoiceCurrency) {
                isValid = false;
                document.querySelector(".invoice_currency").textContent = "Please select an Invoice Currency.";
            } else {
                document.querySelector(".invoice_currency").textContent = "";
            }

            var invoiceaddress_id = document.getElementById("invoiceaddress_id").value;
            if (invoiceaddress_id.trim() === "") {
                isValid = false;
                document.querySelector(".address_invoice").textContent = "Invoice address is required.";
            } else {
                document.querySelector(".address_invoice").textContent = "";
            }

            var main_contact = document.getElementById("main_contact").value;
            if (main_contact.trim() === "") {
                isValid = false;
                document.querySelector(".main_person").textContent = "Main Contact is required.";
            } else {
                document.querySelector(".main_person").textContent = "";
            }

            var department_intrest = document.getElementById("department_intrest").value;
            if (department_intrest.trim() === "") {
                isValid = false;
                document.querySelector(".interst_dept").textContent = "Department's of Intrest is required.";
            } else {
                document.querySelector(".interst_dept").textContent = "";
            }

            var brief_summary = document.getElementById("brief_summary").value;
            if (brief_summary.trim() === "") {
                isValid = false;
                document.querySelector(".summary_desc").textContent = "Summary is required.";
            } else {
                document.querySelector(".summary_desc").textContent = "";
            }


            var agreement1 = document.querySelector("input[name='invoice_currency']:checked");
            if (!agreement1) {
                isValid = false;
                document.querySelector(".invoice_currency").textContent = "Please select an Invoice Currency.";
            } else {
                document.querySelector(".invoice_currency").textContent = "";
            }

            var agreement2 = document.querySelector("input[name='agreement_radio_one']:checked");
            if (!agreement2) {
                isValid = false;
                document.querySelector(".agreement_radio_one").textContent = "Please confirm the agreement.";
            } else {
                document.querySelector(".agreement_radio_one").textContent = "";
            }

            var agreement3 = document.querySelector("input[name='agreement_radio_two']:checked");
            if (!agreement3) {
                isValid = false;
                document.querySelector(".agreement_radio_two").textContent = "Please confirm the agreement.";
            } else {
                document.querySelector(".agreement_radio_two").textContent = "";
            }

            var agreement4 = document.querySelector("input[name='agreement_radio_three']:checked");
            if (!agreement4) {
                isValid = false;
                document.querySelector(".agreement_radio_three").textContent = "Please confirm the agreement.";
            } else {
                document.querySelector(".agreement_radio_three").textContent = "";
            }

            var agreement5 = document.querySelector("input[name='agreement_radio_four']:checked");
            if (!agreement5) {
                isValid = false;
                document.querySelector(".agreement_radio_four").textContent = "Please confirm the agreement.";
            } else {
                document.querySelector(".agreement_radio_four").textContent = "";
            }

            var agreement6 = document.querySelector("input[name='agreement_radio_five']:checked");
            if (!agreement6) {
                isValid = false;
                document.querySelector(".agreement_radio_five").textContent = "Please confirm the agreement.";
            } else {
                document.querySelector(".agreement_radio_five").textContent = "";
            }

            var agreement7 = document.querySelector("input[name='agreement_radio_six']:checked");
            if (!agreement7) {
                isValid = false;
                document.querySelector(".agreement_radio_six").textContent = "Please confirm the agreement.";
            } else {
                document.querySelector(".agreement_radio_six").textContent = "";
            }

            var agreement8 = document.querySelector("input[name='agreement_radio_seven']:checked");
            if (!agreement8) {
                isValid = false;
                document.querySelector(".agreement_radio_seven").textContent = "Please confirm the agreement.";
            } else {
                document.querySelector(".agreement_radio_seven").textContent = "";
            }

            var agreement9 = document.querySelector("input[name='agreement_radio_eight']:checked");
            if (!agreement9) {
                isValid = false;
                document.querySelector(".agreement_radio_eight").textContent = "Please confirm the agreement.";
            } else {
                document.querySelector(".agreement_radio_eight").textContent = "";
            }

            if (!isValid) {
                event.preventDefault();
            }else{
                registerButton.disabled = true;
            }

        });
    });
</script>




<script>
    document.addEventListener("DOMContentLoaded", function() {
    // 1. Handle the "Use It" button click
    const useItBtn = document.querySelector('.coupon-wrapper button');
    const promoInput = document.getElementById('promo_code_section');
    const couponCodeSpan = document.querySelector('.coupon-wrapper span span');

    // if (useItBtn && promoInput && couponCodeSpan) {
    //     useItBtn.addEventListener('click', function(e) {
    //         e.preventDefault();
    //         // Get the text from the red span and put it in the input
    //         promoInput.value = couponCodeSpan.innerText.trim();
    //         // Optional: Trigger the "Apply" button automatically
    //         document.getElementById('coupon_code').click();
    //     });
    // }

    // 2. Browser Alert on Form Submission
    // Target the main search/payment form
    const mainForm = document.querySelector('form.website-search') || document.querySelector('#ordersumm').closest('form');

    if (mainForm) {
        mainForm.addEventListener('submit', function(e) {
            const hasAvailableCredit = document.querySelector('.coupon-wrapper');
            const isInputEmpty = promoInput.value.trim() === "";

            // If credit is sitting there but the user didn't type/paste it in
            if (hasAvailableCredit && isInputEmpty) {
                const confirmChoice = confirm("You have an available Event Credit Code. Would you like to use it before continuing?");
                
                if (confirmChoice) {
                    // Stop form submission so they can apply the code
                    e.preventDefault();
                    promoInput.focus();
                    promoInput.style.border = "2px solid #bcc631";
                }
            }
        });
    }
});
</script>