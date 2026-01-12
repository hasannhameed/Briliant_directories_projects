<?php
$member = getSubscription($user['subscription_id'],$w);
$member_photo = getUserPhoto($user['user_id'], $user['listing_type'], $w);
$member_photo = $member_photo['file'];

if ( ($member['searchable'] == 1 || ($member['searchable'] == 0 && $member['receive_messages'] != 1)) && subscription_types_controller::__canSearchMember($_COOKIE['userid'],$user['subscription_id']) === true) {
?>
	<div class="well author-snapshot">
		<?php 
		if ($member['searchable'] == 1 && $user['active'] == 2) { ?>
		<div class="row">
			<div class="col-md-12 snapshot-member-posted-by-title">
				<p class="inline-block nobmargin">
					%%%posted_by_membership_features%%%
				</p>
				<h2 class="h4 inline-block bold author-name">
					<?php if ($post['author_page'] != "") { ?>
         <a href="<?php echo $post['author_page']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
						<?php echo $user['full_name']; ?>
					</a>       
					<?php } else {?>				
					<a href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
						<?php echo $user['full_name']; ?>
					</a> <?php } ?>
				</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-2 col-md-4 norpad sm-bmargin">
				<?php if ($post['author_page'] != "") { ?>
        	<a href="<?php echo $post['author_page']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
					<?php list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $member_photo);
						if ($attr == "") {
							$attr = 'width="400" height="400"';
						}
					?>
					<img <?php echo $attr; ?> loading="lazy" class="search_result_image img-rounded center-block" alt="<?php echo $user['full_name'];?>" src="<?php echo $member_photo; ?>" />
				</a>
				
					<?php } else {?>	
				
				
				<a href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
					<?php list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $member_photo);
						if ($attr == "") {
							$attr = 'width="400" height="400"';
						}
					?>
					<img <?php echo $attr; ?> loading="lazy" class="search_result_image img-rounded center-block" alt="<?php echo $user['full_name'];?>" src="<?php echo $member_photo; ?>" />
				</a><?php } ?>
			</div>
			<div class="col-xs-10 col-md-8">
				<?php if ($member['receive_messages'] != 1) { ?>
					[widget=Bootstrap Theme - Contact Member Modal] 
					<a href="#" data-toggle="modal" data-target="#contactModal" class="btn btn-success text-left btn-block send-message-button">
						%%%contact_member_label%%%
					</a>
				<?php }

				$membersOnly = addonController::isAddonActive('members_only');
				if (
                    $user['phone_number'] != "" && 
                    $member['show_phone'] == 1 && 
                    ( 
                        $membersOnly  === false  || ( $membersOnly === true && ( isset($_COOKIE['userid']) || (!isset($_COOKIE['userid']) && strpos($subscription['membership_restriction'], "profile_pages") === false) ) ) 
                    ) 
                ) {
					$clickPhoneAddOn  = getAddOnInfo("click_to_phone","16c3439fea1f8b6d897987ea402dcd8e");
					$statisticsAddOn  = getAddOnInfo("user_statistics_addon","7f778bc02f0e6acbbd847b4061c7b76d");

					if(isset($clickPhoneAddOn['status']) && $clickPhoneAddOn['status'] === 'success'){
						echo widget($clickPhoneAddOn['widget'],"",$w['website_id'],$w);
					} else if (isset($statisticsAddOn['status']) && $statisticsAddOn['status'] === 'success') {
						echo widget($statisticsAddOn['widget'],"",$w['website_id'],$w);
					} else { ?>
						<span class="well btn-block nobmargin text-center author-phone">
							%%%show_phone_number_icon%%%
							<?php echo $user['phone_number']; ?>
						</span>
						<?php }
				} ?>
				
				<?php if ($post['author_page'] != "") { ?>
        <a class="btn btn-primary text-left btn-block view-listing-button" href="<?php echo $post['author_page']; ?>">
					%%%view_listing_icon%%%
					%%%view_listing_label%%%
				</a>    
					<?php } else {?>	
				<a class="btn btn-primary text-left btn-block view-listing-button" href="/<?php echo $user['filename']; ?>">
					%%%view_listing_icon%%%
					%%%view_listing_label%%%
				</a> <?php } ?>
			</div>
		</div>
		<div class="clearfix"></div>
		<?php } else { ?>
		[widget=Bootstrap Theme - Contact Member Modal] 
		<a data-toggle="modal" data-target="#contactModal" class="btn btn-success btn-lg btn-block send-message-button-solo">
			%%%contact_member_label%%%
		</a>
		<?php 
		} ?>		
	</div>
<?php 
} ?>