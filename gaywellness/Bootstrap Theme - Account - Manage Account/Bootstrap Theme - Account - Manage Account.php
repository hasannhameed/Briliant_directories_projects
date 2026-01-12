<?php if (upgradeAvailable($user_data,$w) > 0) { ?>
<div class="change-membership-plan-snippet">
	<h3>%%%change_membership_plan_title%%%</h3>
	<p>%%%manage_account_upgrade%%%</p>
	<a class="btn btn-primary tmargin" href="/account/upgrade">%%%manage_account_upgrade_button%%%</a> 
	<hr>
	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<?php } ?>

<?php if($subscription['hide_billing_links'] != 1){?>
<div class="manage-billing-information-snippet">
	<h3>%%%billing_details_information%%%</h3>
	<p>%%%manage_account_billing_links%%% %%%billing_assistance%%% <a href="%%%default_contact_us_url%%%">%%%contact_us%%%</a></p>
	<?php if ($w[whmcs_api_enabled]>0) { ?>
		<a class="btn btn-primary tmargin" href="/account/billing"> %%%manage_view_details%%%</a>
	<?php } else { ?>
		<a class="btn btn-primary tmargin" href="/account/billing/change"><i class="fa fa-credit-card"></i> %%%manage_update_cc%%%</a>
	<?php } ?>
	<hr>
	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<?php } ?>

<a class="cancel btn btn-default" href="/account/deleteaccount">%%%manage_close_account%%%</a>
<hr>