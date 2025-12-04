<?php
$member = getSubscription($user['subscription_id'],$w);
$member_photo = getUserPhoto($user['user_id'], $user['listing_type'], $w);
$member_photo = $member_photo['file'];

if (($member['searchable'] == 1 || $member['receive_messages'] != 1) && subscription_types_controller::__canSearchMember($_COOKIE['userid'],$user['subscription_id']) === true) {
?>
	<div class="well author-snapshot">
		<?php 
		if ($member['searchable'] == 1) { ?>
		<div class="col-md-12 nopad sm-text-center snapshot-member-posted-by-title">
			<p class="inline-block nobmargin">
				%%%posted_by_membership_features%%%
			</p>
			<h4 class="inline-block bold">
				<a href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
					<?php echo $user['full_name']; ?>
				</a>
			</h4>
		</div>
		<div class="col-md-4 nopad sm-bmargin">
			<a href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
				<img class="search_result_image img-rounded center-block" alt="<?php echo $user_data['full_name'];?>" src="<?php echo $member_photo; ?>" />
			</a>
		</div>
		<div class="col-md-8 norpad xs-nopad">
			<?php /*
				$clickPhoneAddOn  = getAddOnInfo("click_to_phone","16c3439fea1f8b6d897987ea402dcd8e");
				$statisticsAddOn  = getAddOnInfo("user_statistics_addon","7f778bc02f0e6acbbd847b4061c7b76d");

				if(isset($clickPhoneAddOn['status']) && $clickPhoneAddOn['status'] === 'success'){
					echo widget($clickPhoneAddOn['widget'],"",$w['website_id'],$w);
				} else if (isset($statisticsAddOn['status']) && $statisticsAddOn['status'] === 'success') {
					echo widget($statisticsAddOn['widget'],"",$w['website_id'],$w);
				} else {
					if ($user['phone_number'] != "" && $member['show_phone'] == 1) { ?>
						<span class="well btn-block nobmargin text-center author-phone">
							<i class="fa fa-phone"></i>
							<?php echo $user['phone_number']; ?>
						</span>
			        <?php }
                } */?>
				<div class="clearfix"></div>
				<a class="btn btn-primary btn-block vmargin" href="/<?php echo $user['filename']; ?>">
					%%%view_listing_label%%%
				</a>
			    <p class="nomargin sm-text-center">
				<!--	<span class="snapshot-member-top-category">
						<?php
						if ($user['profession_id'] > "0"){
							echo stripslashes(getProfession($user['profession_id'],$w))."<br />";
						} ?> 
					</span>
					<small class="snapshot-member-join-date">
						%%%member_join_date%%% <?php /* echo transformDate($user['signup_date'],"QB"); */?>
					</small>-->
			    </p>
		</div>
		<div class="clearfix vmargin"></div>
		<?php } /*
		if ($member['receive_messages'] != 1 && $user['active'] == 2) { ?>
		[widget=Bootstrap Theme - Contact Member Modal] 
		<a data-toggle="modal" data-target="#contactModal" class="btn btn-success btn-lg btn-block">
			%%%contact_member_label%%%
		</a>
		<?php 
		} */?>		
	</div>
<?php 
} ?>