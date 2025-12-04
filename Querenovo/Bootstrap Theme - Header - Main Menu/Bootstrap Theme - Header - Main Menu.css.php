.mobile-main-menu .hasChildren span a {
	padding: 0px;
}

.user_sidebar > img {
	width: 32px;
	height: 32px;
	position: absolute;
	z-index: 10;
	border-radius: 100px;
	top: -1px;
	object-fit: cover;
	left: -10px;
	background: <?php echo $wa['custom_74']?>;
}
#member_sidebar_toggle img+.fa {
	display: none;
}
.main_menu, .user_sidebar, .compact-mobile-search {
	padding: 5px 8px;
	margin-right:0;
	min-height: 33.0333px;
	min-width: 38.116px;	
}
.main_menu i, .user_sidebar i {
	font-size: 16px;
}
.navbar-nav li {
	float: none!Important;
}
.navbar-default{
	-webkit-transition: all .6s ease-in-out;
	-moz-transition: all .6s ease-in-out;
	-o-transition: all .6s ease-in-out;
	transition: all .6s ease-in-out;
}
.transparent_menu {
	<?php
	$theme_color = cleanRGBA($wa['custom_16']); 
	?>
	background-color: rgba(<?php echo $theme_color;?>,0.95)!important;
}
@media only screen and (max-width: 991px) {
	#popover {display: none}
}
@media only screen and (max-width: 1100px){
	.mobile-main-menu{display:block!important}
	.navbar-toggle {display: block}
	#bs-main_menu .nav {display: none!important}
}
.mobile-main-menu {
	position: fixed;
	height: calc(100% - 50px)!important;
	width: 250px;
	background: <?php echo $wa['custom_16']?>;
	z-index: 99999;
	right: -250px;
	top: 50px;
	display: none;
	-webkit-transition: all .6s ease-in-out;
	-moz-transition: all .6s ease-in-out;
	-o-transition: all .6s ease-in-out;
	transition: all .6s ease-in-out;
	overflow-y: auto!important;
	overflow-x: hidden!important;
}
.mobile-main-menu ul li i {
	color: <?php echo $wa['custom_17']?>;
	cursor: pointer;
	float: right;
	padding: 13px;
}
.mobile-main-menu.opened{
	right: 0px!important;
}
.mobile-main-menu ul li a, .mobile-main-menu ul li span {
	display: inline-block;
	float: left;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	padding: 10px 0px;
	width: 80%;
	width: calc(100% - 40px);
}
.mobile-main-menu .sidebar-nav li ul {
	height: 0px;
	overflow: hidden;
	list-style: none;
	color: <?php echo $wa['custom_17']?>;
	padding-left: 10px;
}
.mobile-main-menu .sidebar-nav li.sub_open > ul{
	height: 100%;
}
.mobile-main-menu ul.sidebar-nav {
	position: absolute;
	width: 250px;
	margin: 0;
	padding: 0;
	list-style: none;
	font-size: 14px;
}
.mobile-main-menu .sidebar-nav > li {
	display: block;
	line-height: 20px;
	padding: 0 15px 0 20px;
}
.mobile-main-menu .sidebar-nav li a, .mobile-main-menu .sidebar-nav li span {
	text-decoration: none;
	color: <?php echo $wa['custom_17']?>;
	padding-left:10px;
}
.mobile-main-menu .sidebar-nav li a i, .mobile-main-menu .sidebar-nav li span i {
	display: none;
}

<?php if ($wa['custom_297'] == "2") { ?>
	@media only screen and (max-width: 991px) {
		.header .website-search {
			display: none;
			position: fixed;
			top: 50px;
			background: <?php echo $wa['custom_5']?>;
			width: 100%;
			padding: 20px;
			left: 0;
			z-index: 9999;
			box-shadow: 0px 15px 30px 0px rgba(0, 0, 0, 0.1);
		}
		.header .website-search input[type="submit"] {
			margin-bottom:0!important;
			vertical-align: top;
		}
	}
	@media only screen and (min-width: 992px) {
		.header .website-search {
			display: block!important;
		}
	}
<?php } ?>


.example a {
    display: inline-block;
    padding: 10px 14px;
    border-bottom: 2px solid transparent !important;
    color: #0b1a33 !important;
    transition: all 0.25s ease;
    text-decoration: none;
}

/* Active State (URL matched) */
.example a.active-nav {
    color: red !important;
    border-bottom: 2px solid red !important;
}

/* Hover State */
.example a.hover-nav {
    color: red !important;
	background: none !important;
}
.navbar-default .navbar-nav>li:focus, .navbar-default .navbar-nav>li:hover {
    /* color: #fff; */
    box-shadow: none !important;
}
.example{
	    border-right: none !important;
	    border-bottom: 2px solid white !important;
}
.example:hover{
	    border-right: none !important;
	    border-bottom: 2px solid white !important;
        border-bottom: 2px solid #ccc !important;
}