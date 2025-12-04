<?php
$addOn = getAddOnInfo('claim_listing');
if ($subscription['claim_link_join_page'] != "") {
	$myLink = trim($subscription['claim_link_join_page']);
} else if ($label['default_signup_url'] != "") {
	$myLink = trim($label['default_signup_url']);
} else {
	$myLink = "/join";
}
$parameter = "?";
if (strpos($myLink,"?")) {
	$parameter = "&";
}
if($addOn['status'] == 'success'){?>
<div class="well vmargin xs-text-center fpad claim-listing-message">
	<img class="hmargin bmargin" src="/images/icon-claim.gif" alt="Claim listing">
	<span class="inline-block bold bmargin">%%%are_you_label%%% <?php echo $user['full_name'];?>?</span>
	<span class="inline-block bmargin">%%%claim_listing_text%%%</span>
	<a class="btn btn-warning btn-sm" href="<?php echo $myLink.$parameter;?>claim=<?php echo $user['token'];?>">
		%%%claim_listing%%%
	</a>
	<a class="btn btn-success btn-sm" href="/learn_more">
		Learn More
	</a>
</div>
<?php } ?>
