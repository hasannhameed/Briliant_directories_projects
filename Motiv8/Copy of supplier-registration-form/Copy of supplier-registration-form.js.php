<script>
    $(document).ready(function () {
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
            var packageTotal = parseFloat($("input[name='packages_section']:checked").data("price"));
            console.log("packageTotal= " + packageTotal);

            if (isNaN(packageTotal)) {
                packageTotal = 0;
            }

            originalSubtotal = packageTotal + addOnsTotal;
            console.log("originalSubtotal= " + originalSubtotal);
            var promoDiscount = parseFloat($(".promo-discount td.alnright").text().substring(1));
            if (isNaN(promoDiscount)) {
                promoDiscount = 0;
            }

            var discountedTotal = originalSubtotal - promoDiscount;

            $(".subtotal td.alnright").text("£" + originalSubtotal.toLocaleString('en-US'));
            $(".total td.alnright").text("£" + discountedTotal.toLocaleString('en-US'));
            $("#totalAmount").text("£" + discountedTotal.toLocaleString('en-US'));

            // Update promo discount amount
            $(".promo-discount td.alnright").text("£" + promoDiscount.toLocaleString('en-US'));
        }

            // For add ons
            $("input[name='add_ons[]']").on("change", function () {
                updateOrderSummary();
            });

            // For package
            $("input[name='packages_section']").on("change", function () {
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
            $("#coupon_code").on("click", function () {
                var promoCodeValue = $('#promo_code_section').val();

                $.ajax({
                    type: "get",
                    url: '/api/widget/json/get/Bootstrap Theme - Function - Form Promotion Code Validation',
                    data: { refcode: promoCodeValue },
                    success: function (response) {
                        console.log(response);
                        if (response['valid'] === true) {
                            var discountPercentage = parseFloat(response['discount_text']);
                            var discountAmount = (discountPercentage / 100) * originalSubtotal;

                            $("#promo-code-alert-message").text("Promo code is valid! " + discountPercentage + "% discount applied.").addClass("alert-success").show();

                            var discountedTotal = originalSubtotal - discountAmount;

                            $(".promo-discount td.alnright").text("£" + discountAmount.toFixed(2));
                            $(".total td.alnright").text("£" + discountedTotal.toFixed(2));
                            $(".promo-discount").show();

                            updateOrderSummary();
                        } else {
                            $("#promo-code-alert-message").text("Promo code is not valid.").addClass("alert-danger").show();
                            $(".promo-discount").hide();
                            updateOrderSummary();
                        }
                    }
                });
            });

            updateOrderSummary();
        });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const registerButton = document.getElementById('registerButton');

    registerButton.addEventListener('click', function (e) {
        const requiredFields = [
            'contact_person',
            'job_title',
            'email_id',
            'phone_number',
            'company_name',
            'invoice_email',
            'invoiceaddress_id'
        ];

        let allValid = true;
        let firstInvalidField = null;

        requiredFields.forEach(function (id) {
            const field = document.getElementById(id);
            if (field && !field.value.trim()) {
                allValid = false;

                if (!firstInvalidField) {
                    firstInvalidField = field;
                }

                field.classList.add('is-invalid');

                const errorSpan = field.parentElement.querySelector('small.text-danger');
                if (errorSpan) {
                    errorSpan.textContent = 'This field is required.';
                }
            } else {
                field.classList.remove('is-invalid');
                const errorSpan = field.parentElement.querySelector('small.text-danger');
                if (errorSpan) {
                    errorSpan.textContent = '';
                }
            }
        });

        const currencyChecked = document.querySelector('input[name="invoice_currency"]:checked');
        if (!currencyChecked) {
            allValid = false;
            document.querySelector('.invoice_currency').textContent = 'Please select a currency.';
            if (!firstInvalidField) {
                const currencyInput = document.querySelector('input[name="invoice_currency"]');
                if (currencyInput) {
                    firstInvalidField = currencyInput;
                }
            }
        } else {
            document.querySelector('.invoice_currency').textContent = '';
        }

        if (!allValid) {
            e.preventDefault();

            // Scroll to and focus first invalid field
            if (firstInvalidField) {
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidField.focus();
            }

            return;
        }

        // Fill in calculated fields
        const totalAmount = document.getElementById('totalAmount').textContent;
        document.getElementById('payment_summary').value = totalAmount;

        const promoDiscountAmount = document.getElementById('promoDiscountAmount').textContent;
        document.getElementById('discount_input').value = promoDiscountAmount;

        // Disable button to prevent resubmission
        setTimeout(() => {
            registerButton.disabled = true;
            registerButton.textContent = 'Submitting...';
        }, 100);
    });
});
</script>


<!-- From Validation -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var form = document.querySelector(".supplier_form");
        form.addEventListener("submit", function (event) {
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
            packagesSection.forEach(function (radio) {
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
            }
        });
    });
</script>
