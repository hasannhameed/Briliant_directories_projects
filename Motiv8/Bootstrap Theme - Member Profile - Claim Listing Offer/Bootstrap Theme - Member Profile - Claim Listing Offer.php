<?php
$MemberSub = getSubscription($user['subscription_id'],$w);
//check claim listing add on
$addOn = getAddOnInfo('claim_listing','54cc1a4084226d4d1b68c558c593f758');

if (($MemberSub['receive_messages'] != 1 && $subscription['profile_type']!="claim") || ($subscription['profile_type']=="claim" && isset($addOn['status']) && $addOn['status'] === 'success') ) { ?>
    
	<?php 
	if ($subscription['profile_type'] == "claim") { 

		echo widget($addOn['widget'],"",$w['website_id'],$w);

	} else if ($MemberSub['receive_messages'] != 1 && $subscription['profile_type'] != "claim") { ?>
	<div class="well <?php if ($subscription['profile_connection_banner_position'] != "above_profile_photo"){ ?> tmargin <?php } ?> bmargin xs-text-center make-connection" style="padding:10px 15px;">
		<span class="h3 nobmargin">
			<i class="fa fa-comments-o fa-fw" aria-hidden="true"></i>
		</span>
		<b>%%%profile_connection%%%</b> 
		<?php echo $user['full_name']?> 
		%%%profile_available%%% 
		<a class="inline-block" href="/<?=$user['filename']?>/connect">
			%Profile_action%
		</a>
	</div>
	<?php 
	} 
} ?>
<div class="clearfix"></div>