.cities {

}

<?php if ($pars[0] != "account") { ?>
	.member_sidebar {
		display: none;
	}
	<?php } ?>
.sidemenu_icon {
	max-height: 24px;
	position: relative;
}
.list-group-item {
	border-radius: 0px!important;
}
.nobr>li>a, .nobr>li>ul>li>a{
	border-radius: 0;
}
.no_favorites{
	font-size: 13px;
	text-align: center;
	padding: 6px;
}
.member_admin_sidemenu.panel-default a:focus {
	color: inherit;
}
.panel-title .label.bg-default,.featureCount {
	min-width: 30px;
	margin-top: -1px;
}
.member_accounts {
	min-height: 1150px;
}
.member_sidebar.open {
	left: 0;
	top: 50px;
}
.sidemenu_panel .panel {
	border: 0;
	box-shadow: none;
	margin: 0!important;
	border-radius: 0!important;
	position: relative;
}
.sidemenu_panel .panel-collapse div .list-group-item {
	padding-left: 39px;
	border: 0;
	transition-delay: 0s;
	transition-duration: 0.3s;
	transition-property: all;
	transition-timing-function: linear;
}
.member_admin_sidemenu .panel-default > .panel-heading a {
	display: block;
}
h4.panel-title a {
	text-decoration: none;
}
.sidemenu_panel .panel-collapse div .list-group-item:hover {
	padding-left: 45px;
}
.sidemenu_panel .panel:hover .panel-heading,.sidemenu_panel .panel:hover .panel-heading a,.sidemenu_panel .panel-heading:hover{
	background-color: <?php echo $wa['custom_58']?>!important;
	color: <?php echo $wa['custom_59']?>!important;
}
.sidemenu_panel .panel-heading a:focus {
	color: <?php echo $wa['custom_2']?>!important;
}
.panel-default > .panel-heading {
	border-radius: 0!important;
}
.sidemenu_panel {
	margin: 0 -15px;
}
.panel-title a > i:not(.fa-caret-down) {
	min-width: 32px;
	text-align: center;
	font-size: 16px;
	margin-bottom: 10px;
	float: left;
}
.panel-title a > i.fa-caret-down {
    min-width: inherit;
    font-size: inherit;
    width: 30px;
    text-align: center;
}
@media only screen and (max-width: 991px) {
	.member_sidebar {
		display:block;
		position: fixed;
		z-index: 10000;
		width: 350px;
		max-width: 100%;
		overflow-y:auto;
		height: 90%;
		height: calc(100% - 50px);
		padding-right: 0px;
		-webkit-transition: -webkit-all 300ms ease;
		-moz-transition: -moz-all 300ms ease;
		transition: all 600ms ease;
		top: 50px;
		left: -450px;
	}
	.well.member_admin_sidemenu {border-radius: 0px; margin-bottom: 0px;}
}