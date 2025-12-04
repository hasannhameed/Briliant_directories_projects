/* --- Custom CSS for the Callback Form --- */

.callback-form-container {
    background-color: #fff;
    max-width: 600px; /* Example max-width for the form/modal */
    margin: auto;
    border-radius: 4px; /* Optional slight rounding */
}

/* Modal Header/Title Styling */
.modal-header {
    border-bottom: none; /* Remove default Bootstrap border */
    padding: 20px 25px 5px 25px; /* Adjust padding */
}

.modal-header .close {
    font-size: 30px;
    margin-top: -5px;
}

.modal-title {
    font-weight: bold;
    font-size: 24px;
    color: #333; /* Dark text color */
}

/* Modal Body/Form Fields Styling */
.modal-body {
    padding: 0 25px;
}

.form-group {
    margin-bottom: 15px;
}

.control-label {
    font-weight: bold;
    font-size: 13px;
    color: #333;
}

/* Ensure form controls look correct and have consistent height */
.form-control {
    border-radius: 4px;
    height: 40px; /* Standardizing input height */
    padding: 6px 12px;
}

textarea.form-control {
    height: auto;
    resize: vertical;
}

/* Custom Footer for the Full-Width Button */
.modal-footer-custom {
    padding: 25px;
    border-top: none;
    margin-top: 15px; /* Add space above the button */
}

/* Submit Button Styling (Red) */
.custom-red-submit {
    background-color: #d90000; /* Custom Red */
    border-color: #d90000;
    color: #fff;
    font-weight: bold;
    font-size: 18px;
    padding: 10px 20px;
}

.custom-red-submit:hover, .custom-red-submit:focus {
    background-color: #c90000; /* Slightly darker red on hover */
    border-color: #c90000;
    color: #fff;
}

.custom-red-submit .glyphicon {
    margin-right: 10px;
}