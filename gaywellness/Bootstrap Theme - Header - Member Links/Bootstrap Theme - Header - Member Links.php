<?php
$me = getUser($_COOKIE['userid'],$w);
$subscription = getSubscription($me['subscription_id'],$w);
$userPhoto = getUserPhoto ($me['user_id'], $me['listing_type'], $w);
$userPhoto = $userPhoto['file'];
?>

<div class="col-md-12 vmargin sm-nomargin pull-right nohpad sm-text-center logged-in-member-header">
    
	<ul class="nav navbar-nav tablet-menu-ul inline nav-justified mini-nav list-inline nobmargin lmargin sm-text-center pull-right">
		<?php 
		if($wa['header_logged_in_menu'] != ''){
			echo menuArray($wa['header_logged_in_menu'], 0, $w);	
		}
		?>
		<li style="padding-top:1px;" class="pull-right hidden-sm hidden-xs">
			<a 
			type="button" 
			id="popover" 
			tabindex="0" 
			role="button" 
			data-container="body" 
			data-toggle="popover" 
			data-placement="bottom" 
			data-html="true"
			style="margin-left:30px;"
			class=" sm-text-center pull-right toggle-member-info">
				<img alt="Member Profile Image" src="<?php echo $userPhoto; ?>" class="mini_profile_pic">
				<span style="color:#OD3F4F !important;">%Welcome_member% 
				<?php echo limitWords($me['full_name'],1)?></span>
				<i class="fa fa-angle-down fa-fw"></i>
			</a>
		</li>
		
    </ul>
	
	<?php if ($wa['custom_148'] == "1") {
			$addOnGoogleTranslate = getAddOnInfo("google_translate", "c93d40dd427f14184884519422f1719e");
			if (isset($addOnGoogleTranslate['status']) && $addOnGoogleTranslate['status'] === 'success') {
				echo widget($addOnGoogleTranslate['widget'], "", $w['website_id'], $w);
			} ?>
	<?php } ?>
</div>

<div class="clearfix"></div>

<script>
    $(document).ready(function(){
        $('#popover').popover({
			content: `<img alt="Member Profile Image" style="width: 90px" src="<?php echo $userPhoto; ?>" class="img-responsive img-rounded center-block bmargin">
			<center>
				<p class="badge bg-primary">
					<?php echo str_replace("'", "&#39;",$me['full_name']); ?>
				</p>
			</center>
			<p class="nomargin small">
				<?php if ($me['active']==1) { ?>
				<a href="/account/home" class="text-danger bold">%%%dashboard_notactivated%%%</a>
				<?php } else { if ($me['active']==2) { ?>
				%%%account_status%%%: <b>%%%dashboard_active%%%</b>
				<?php } else if ($me['active'] == 5) { ?>
				%%%account_status%%%: <a href="/account/billing" class="text-danger bold">%%%past_due_text_label%%%</a>
				<?php } else { ?>
				%%%account_status%%%: <a href="/account/billing" class="text-danger bold">%%%dashboard_onhold%%%</a>
				<?php } } ?>
			</p>
			<p class="nomargin small">%%%member_level%%%: <b><?php echo str_replace("'", "&#39;",$subscription['subscription_name']); ?></b></p>

			<div class="clearfix"></div>

			<p class="vpad nomargin inline-block">
				<?php if ($me['filename'] != "" && $subscription['searchable'] == "1") { ?>
				<a style="margin-right: 5px;" href="/<?php echo urldecode($me['filename']);?>" class="btn btn-primary btn-sm pull-left">%%%view_listing_label%%%</a>
				<?php } ?>
				<a style="margin-left: 5px;" href="/account/home" class="btn btn-success btn-sm pull-right">%%%profile_edit_listing%%%</a>
			</p>
			<p class="text-right font-sm nomargin">
				<a href="/logout">%%%dashboard_logout%%%</a>
			</p>`
		});
    });
</script>