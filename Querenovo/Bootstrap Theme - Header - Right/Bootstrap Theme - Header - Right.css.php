.rd {
	border-radius:20px;
	color: white;
    font-weight: bold;
}

.rd:hover{
color: white !important;
} 
body input.tt-hint,body input.form-control.normal-autosuggest-input.tt-query {
	background-color: white !important;
}
/* Parent Container - Main List */
.mini-nav li:has(ul) {
	position: relative;
	border-radius: 5px 5px 0 0;
}
.mini-nav > li:has(ul) {
	padding-left: 0;
	padding-right: 0;
	background: <?php echo $wa['custom_5']?>;
}
.mini-nav > li:has(ul) > a:not(.btn), .mini-nav > li:has(ul) > span {
	padding: 10px 15px;
	margin: 0!important;
}
.mini-nav > li:hover:has(ul), .mini-nav > li:hover:has(ul) {
	box-shadow: 0 -1px 1px rgba(<?php echo cleanRGBA($wa['custom_6']); ?>,0.25);
}
/* Parent Links */
.mini-nav > li > a:not(.btn), .mini-nav > li > span:not(.btn) {
	display:inline-block;
	border-radius: 5px 5px 0 0;
}
.mini-nav > li:hover > a:not(.btn), .mini-nav > li:hover > span {
	background: <?php echo $wa['custom_5']?>;
	display: inline-block;
	position: relative;
	z-index: 1000;
}
/* First Level Dropdowns */
.mini-nav li ul {
	background: <?php echo $wa['custom_5']?>;
	box-shadow: 0 0px 1px rgba(<?php echo cleanRGBA($wa['custom_6']); ?>,0.25), 0 0 5px 5px rgba(<?php echo cleanRGBA($wa['custom_6']); ?>,0.05);
	text-align: left;
	display: none;
	border-radius: 0 5px 5px 5px;
	list-style: none;
	padding: 10px;
	position: absolute;
	white-space: nowrap;
	min-width: 100%;
	width: auto;
	top: 100%;
	left: 0;
	z-index: 999;
	margin-top: -1px;
}
.mini-nav li:hover > ul {
	display: block;
}
/* First Level Dropdown Items */
.mini-nav li ul li {
	position: relative;
	display: block;
	width: 100%;
}
.mini-nav li ul li a, .mini-nav li ul li span {
	font-size: <?php echo $wa['header_font_link_size'] * 0.925; ?>px !important;
	display: block;
	font-weight: 400;
	padding: 10px;
	border-radius: 5px;
	text-decoration: none;
	width: 100%;
}
.mini-nav li ul li a:hover, .mini-nav li ul li span:hover {
	box-shadow: 0 0 0 25px rgba(<?php echo cleanRGBA($wa['custom_6']); ?>,0.1) inset;
}
/* Second Level Dropdowns */
.mini-nav li ul li ul {
	display: none;
	position: absolute;
	top: 0;
	left: 100%;
	margin: 0;
	border-radius: 5px;
}
.mini-nav li ul li:hover > ul {
	display: block;
}
/* Edge Positioning - Last Child Dropdowns */
.mini-nav > li:last-child ul {
	right: 0;
	left: auto;
	border-radius: 5px 0 5px 5px;
}
.mini-nav > li:last-child ul li ul {
	right: 100%;
	left: auto;
}
/* Flex Spacing Utility */
.mini-nav.mini-nav-flex-spaced {
	display: flex;
	justify-content: space-between;
	align-items: center;
	flex-wrap: wrap;
	row-gap: 0;
}
.mini-nav.mini-nav-flex-spaced > li {
	flex: none;
	padding: 0;
	margin: 2px 0;
	order: 2;
}
.mini-nav.mini-nav-flex-spaced > li.header-member-account-links,
.mini-nav:has(li ul) > li.header-member-account-links {
	order: 1;
	margin-left: auto;
	width: 100%;
	margin-bottom: 10px;
}
.logged-in-member-header .mini-nav.mini-nav-flex-spaced.list-inline > li {
	line-height: 1em;
	min-height: 0;
}
@media (min-width: 992px) {
	.header-main-row {
		display: flex;
		align-items: center;
		flex-wrap: wrap;
	}
}
@media (max-width: 991px) {
	.mini-nav.mini-nav-flex-spaced {
		justify-content: center;
	}
	.mini-nav.mini-nav-flex-spaced > li {
		margin-left: 5px !important;
		margin-right: 5px !important;
	}
}