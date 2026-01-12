<?php
$subscription=getSubscription($user['subscription_id'],$w);
$badgesAddOn = getAddOnInfo('member_listing_badges','fd3492d19ba387b710a1670bcccd1fdf');
if (isset($badgesAddOn['status']) && $badgesAddOn['status'] == 'success' && $subscription['profile_badge']!="") {
	echo widget($badgesAddOn['widget'],"",$w['website_id'],$w);
}
?>

<?php if ($user['nationwide']==1) { ?>
	<a href="#" data-original-title="%%%services_badge_title%%%" id="popoverOption" rel="popover" data-placement="left" data-trigger="hover" class="member_popover fpad-sm btn btn-default btn-block hidden-xs" data-content="%%%services_badge_content%%%">
        <?php if ($w['all_location_badge']!="") { ?>
            <img alt="%%%services_badge_title%%%"src="<?php echo $w['all_location_badge'];?>">
        <?php } else { ?>
            <img alt="%%%services_badge_title%%%" src="/images/world_icon.png">
        <?php } ?>
	</a>
<?php } ?>


<script type="text/javascript">
$(document).ready(function(){
$('.member_popover').popover({
	container: 'body'
});
});

</script>