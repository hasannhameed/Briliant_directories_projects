.first-post-inner {
	display:none;
}
.account-form-box {
	position: relative;
}
#purschase-limit.publish-post-button, .publish-post-button {
	position: absolute;
	right: 0px;
	top: -42px;
	text-transform: capitalize;
	font-weight:600;
}
@media only screen and (max-width: 767px) {
	#purschase-limit.publish-post-button,.publish-post-button {
		position: relative;
		right: auto;
		top: auto;
		display: block;
		margin-bottom:10px;
	}
}

/* CSS for $post and $group datatables */
<?php if ($_GET['external_action'] != "getresults") { ?>
.table.dataTable > thead > tr > th {
	padding: 0px;
	border: none;
	height:0;
}
.table.dataTable > thead > tr > th:first-child {
	width: 25% !important;
}	
.table.dataTable > thead > tr > th:last-child {
	width: 18% !important;
}	
.table.dataTable tr td {
	padding: 15px 10px;
}
.table.dataTable tr td:first-child {
	padding-left: 0;
}
.table.dataTable tr td:last-child {
	padding-right: 0;
}
.member_accounts .table.dataTable {
	width: 100%!important;
	margin: 10px 0 0!important;
	vertical-align: top;
	display: inline-table;
}
.member_accounts .the-post-description {
	word-break: break-word;
}
.member_accounts .the-post-image {
	max-height: 110px;
	overflow: hidden;
}
.member_accounts .the-post-image img {
	display: block;
	margin-right: auto;
	margin-left: auto;
}		
@media only screen and (max-width: 767px) {
	#feature-body-datatable_wrapper {
		margin: 0 -15px;
	}			
	.table.dataTable,.table.dataTable  > tbody,.table.dataTable  > tbody > tr,.table.dataTable  > tbody > tr > td {
		width: 100% !important;
		display: block;
		box-sizing: border-box;
		border: none;
		padding:0;
	}
	.table.dataTable  > tbody > tr, .table.dataTable  > tbody > tr > td {
		padding: 10px 0 0;
	}
	.member_accounts .table.dataTable {
		display: block;
		margin: 0 !important;
	}
	.member_accounts .the-post-image img {
		max-height: 110px;
	}
	.member_accounts .post-title {
		font-size: 14px;
		line-height: 1.2em !important;
		text-align:center;
	}			
}
<?php } ?>