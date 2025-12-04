/* Import Bootstrap 3 CSS first for this to work correctly */

/* --- General Section Styling --- */
.help-section {
    font-family: Arial, sans-serif; /* Example font */
}
.d-flex{
	display:flex;
}

.d-flex .col-sm-8{
	margin:0 auto;
}

/* --- Top Section (White/Light Red Background) --- */
.top-content {
    padding: 60px 0;
    /* Custom background gradient to match the image's subtle light red fade */
    background: #fef3f1; 
    border-bottom: 1px solid #eee; /* Subtle separator line */
}

.top-content h2 {
    font-size: 28px;
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 10px;
}
.action-buttons svg{
	
}
.top-content .description {
    font-size: 18px;
    color: #555;
    margin-bottom: 30px;
    margin-top: 20px;
}

.icon-pin .glyphicon {
    
}
.icon-pin svg{
	font-size: 40px;
    color: #d9534f; /* Custom Red */
    border-radius: 50%;
    width: 60px;
    height: 60px;
    line-height: 30px;
    vertical-align: middle;
}

.custom-red-btn {
    background-color: #dc060b;
    border-color: #dc060b;
    padding: 15px 20px;
	font-size :14px;
}
.custom-red-btn svg {
    padding: 3px;
    margin-right: 10px;
    margin-bottom: -5px;
}
.custom-red-btn:hover, .custom-red-btn:focus {
    background-color: #c9302c; /* Darker Red on hover */
    border-color: #c9302c;
}

.custom-red-btn .glyphicon {
    margin-right: 8px;
}


/* --- Bottom Section (Dark Blue Background) --- */
.bottom-content {
    background-color: #172a4d; /* Dark Blue */
    color: #fff;
    padding: 60px 0;
}

.bottom-content h3 {
    font-size: 24px;
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 10px;
}

.bottom-content .description {
    font-size: 17px;
    color: #ccc;
    margin-bottom: 30px;
    margin-top: 20px;
}

.icon-phone .glyphicon {
   
}
.icon-phone svg {
    font-size: 30px;
    color: #ff0000d9;
    /* border: 2px solid #fff; */
    /* border-radius: 50%; */
    /* padding: 10px; */
    width: 60px;
    height: 59px;
    line-height: 30px;
    vertical-align: middle;
}

/* Red Button in the Bottom Section */
.custom-red-btn-2 {
    background-color: #dc060b;
    border-color: #dc060b;
    color: #fff;
    padding: 15px 20px;
    margin-right: 15px; /* Spacing between the two buttons */
	font-size :14px;
}

.custom-red-btn-2 svg {
    width: 18px;
    height: 18px;
    margin-right: 12px;
}

.custom-red-btn-2:hover, .custom-red-btn-2:focus {
    background-color: #c9302c; /* Darker Red on hover */
    border-color: #c9302c;
    color: #fff;
}

/* White Button in the Bottom Section */
.custom-white-btn {
    background-color: #fff;
    border-color: #fff;
    color: #172a4d; /* Text color matching the dark blue background */
    padding: 15px 20px;
    margin-right: 15px; /* Spacing between the two buttons */
	font-size :14px;
}
.custom-white-btn:hover, .custom-white-btn:focus {
    background-color: #f0f0f0;
    border-color: #f0f0f0;
    color: #172a4d;
}

/* Responsive adjustment for small screens (Bootstrap 3 breakpoint) */
@media (max-width: 767px) {
    .action-buttons a {
        display: block; /* Stack buttons vertically on small screens */
        margin-right: 0 !important;
        margin-bottom: 10px;
    }
}