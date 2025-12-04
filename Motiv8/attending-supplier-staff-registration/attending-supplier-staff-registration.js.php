<script>
  $(document).ready(function () {
    const $equipmentOthers = $('#equipment_others');
    const $otherEquipmentField = $('#other_equipment_field');
    const $otherEquipmentInput = $('#other_equipment');
    const $otherEquipmentFileInput = $('#other_equipment_file');

    // Initially hide input fields but keep the section visible
    $otherEquipmentField.show();

    // Enable or disable input fields based on checkbox selection
    $equipmentOthers.on('change', function () {
      if ($equipmentOthers.is(':checked')) {
        $otherEquipmentInput.prop('readonly', false);  // Make input editable
        $otherEquipmentFileInput.prop('disabled', false); // Enable file input
      } else {
        $otherEquipmentInput.prop('readonly', true);  // Make input readonly
        $otherEquipmentFileInput.prop('disabled', true); // Disable file input
      }
    });
  });
</script>
 <?php if(isset($_GET['ref']) && $_GET['ref']== '1ed4dd132bf32f83221f2990031daa42'){ ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get references to the elements
        const travelRadios = document.querySelectorAll('input[name="travel_method"]');
        const vehiclePlateDiv = document.getElementById('vehicle-plate-div');
        const vehiclePlateInput = vehiclePlateDiv.querySelector('input');
        const otherSpecifyDiv = document.getElementById('other-specify-div');

        // Function to handle showing/hiding the text fields
        function toggleTravelInputs() {
            // Hide both divs initially
            vehiclePlateDiv.style.display = 'none';
            vehiclePlateInput.required = false; // Make it not required
            otherSpecifyDiv.style.display = 'none';

            // Check which radio is selected
            const selectedValue = document.querySelector('input[name="travel_method"]:checked').value;

            if (selectedValue === 'Vehicle') {
                vehiclePlateDiv.style.display = 'block';
                vehiclePlateInput.required = true; // Make it required
            } else if (selectedValue === 'Other') {
                otherSpecifyDiv.style.display = 'block';
            }
        }

        // Add an event listener to each radio button
        travelRadios.forEach(radio => {
            radio.addEventListener('change', toggleTravelInputs);
        });
    });
</script>
 <?php } ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
  let count = 1;

  document.querySelectorAll(".form-group > label").forEach(label => {
    // Skip if label is inside a .radio_filed container
    if (label.closest('.radio_filed')) return;

    // Add number to main labels only
    const span = document.createElement("span");
    span.textContent = count + ". ";
    span.style.fontWeight = "bold";
    span.style.marginRight = "5px";
    label.prepend(span);
    count++;
  });
});
</script>

<script>
                    document.addEventListener("DOMContentLoaded", function () {
                      
                        const password = document.getElementById("password");
                        const confirmPassword = document.getElementById("confirm_password");

                        if (password && confirmPassword) {
                            confirmPassword.addEventListener("input", function () {
                            let msg = confirmPassword.nextElementSibling; // <small class="text-danger confirm_password">
                            if (password.value === "" || confirmPassword.value === "") {
                                msg.textContent = "";
                                msg.style.color = "";
                                return;
                            }
                            if (password.value !== confirmPassword.value) {
                                msg.textContent = " Passwords do not match";
                                msg.style.color = "red";
                            } else {
                                msg.textContent = " Passwords match";
                                msg.style.color = "green";
                            }
                            });
                        }
                    });
                </script>
<form id="registrationForm" method="POST" action="your_submission_url">
    <div class="form_body" bis_skin_checked="1">
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit Registration</button>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('.supplier_form');
        
        // Check if the form element exists before proceeding
        if (!form) {
            console.error("Form with ID 'registrationForm' not found.");
            return;
        }

        form.addEventListener('submit', function (event) {
            // Prevent the default form submission
            event.preventDefault(); 
            
            // Run the validation check
            if (validateForm(form)) {
                // If validation passes, you can submit the form programmatically
                // or use AJAX/Fetch for submission.
                console.log("Validation successful. Submitting form data...");
                form.submit();
                // **UNCOMMENT THE LINE BELOW TO ACTUALLY SUBMIT THE FORM**
                // form.submit();
            } else {
                alert("Validation failed. Please correct the highlighted fields.");
                // Form submission is already prevented by event.preventDefault()
            }
        });
    });

    /**
     * Checks all required input fields, ensuring they are not empty and not just whitespace.
     * @param {HTMLFormElement} formElement - The form element to check.
     * @returns {boolean} - True if validation passes, False otherwise.
     */
    function validateForm(formElement) {
        let isValid = true;
        
        // 1. Get all text/number/email input fields with the 'required' attribute
        const textInputs = formElement.querySelectorAll('input[required]:not([type="radio"]):not([type="hidden"])');

        textInputs.forEach(input => {
            // Trim the value to remove leading/trailing whitespace (the edge case you mentioned)
            const trimmedValue = input.value.trim();
            const errorElement = document.querySelector(`.text-danger.${input.name}`);

            if (trimmedValue === '') {
                // Check for pure emptiness OR only whitespace
                isValid = false;
                input.classList.add('is-invalid'); // Add error class for visual feedback
                if (errorElement) {
                    errorElement.textContent = `${input.name.replace('_', ' ').toUpperCase()} is required and cannot be empty.`;
                }
            } else {
                // Validation passed for this field
                input.classList.remove('is-invalid');
                if (errorElement) {
                    errorElement.textContent = '';
                }
            }
            
            // Update the input value with the trimmed version to prevent whitespace submission
            input.value = trimmedValue;
        });

        // 2. Check Radio Button Groups (specifically 'attended_before' in your HTML)
        const radioGroups = ['attended_before']; // Add other radio group names if needed
        radioGroups.forEach(groupName => {
            const radioButtons = formElement.querySelectorAll(`input[name="${groupName}"]`);
            const isGroupChecked = Array.from(radioButtons).some(radio => radio.checked);
            
            if (!isGroupChecked) {
                isValid = false;
                // Add visual error cue (e.g., to the label or parent container)
                // For simplicity, we'll log an error. You may need custom CSS/HTML for radio errors.
                console.error(`Radio group '${groupName}' is required.`); 
            }
        });

        // 3. Handle the Phone Number Field
        // Assuming the phone number input uses the name 'phone_number' and is visible.
        const phoneInput = formElement.querySelector('input[name="phone_number"]');
        if (phoneInput) {
            const trimmedPhoneValue = phoneInput.value.trim();
            const errorElement = document.querySelector('.text-danger.phone_number');
            
            if (trimmedPhoneValue === '') {
                isValid = false;
                phoneInput.classList.add('is-invalid');
                if (errorElement) {
                    errorElement.textContent = 'Mobile Phone Number is required and cannot be empty.';
                }
            } else {
                phoneInput.classList.remove('is-invalid');
                if (errorElement) {
                    errorElement.textContent = '';
                }
            }
            phoneInput.value = trimmedPhoneValue;
        }

        return isValid;
    }
</script>

