<?php
$subscription = getSubscription($user['subscription_id'],$w);
$dc=getDataCategory($dc['data_id'],"data_id",$w);
?>

    <div class="module fpad bmargin posted-by-snippet" style='display:none;'>
        <div class="col-xs-8 col-sm-8 btn-sm nohpad nobpad">
			<span class="posted-by-snippet-posted">
				%%%posted_on%%%
			</span>
			<span class="posted-by-snippet-date">
				<?php
				if ($group['date_updated'] != "") {
					echo transformDate($group['date_updated'],"QB");
				} else if ($dc['form_name'] == "blog_article_fields") {
					echo transformDate($post['post_start_date'],"QB");
				} else if ($post['post_live_date'] != "") {
					echo transformDate($post['post_live_date'],"QB");?>
				<?php } ?>
			</span>
            <?php if ($group['group_category'] != "") { ?>
                <span class="inline-block posted-by-snippet-category">
					%in_membership_feature_details% 
					<a class="bold" title="<?php echo $dc['data_name']; ?> - <?php echo $group['group_category']; ?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo urlencode($group['group_category']); ?>">
						<?php echo $group['group_category']; ?>
					</a>
				</span>
			<?php
            } if ($post['post_category'] != "") { ?>
                <span class="inline-block posted-by-snippet-category">
					%in_membership_feature_details% 
					<a class="bold" title="<?php echo $dc['data_name']; ?> - <?php echo $post['post_category']?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo urlencode($post['post_category'])?>">
						<?php echo $post['post_category']; ?>
					</a> 
				</span>
			<?php
            } if ($subscription['searchable'] == 1 && subscription_types_controller::__canSearchMember($_COOKIE['userid'],$user['subscription_id']) === true) { ?>
                <span class="inline-block posted-by-snippet-author">
					%%%by_label%%%
					<a class="bold notranslate" href="/<?php echo $user['filename']?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']?>">
						<?php echo $user['full_name']?>
					</a>
				</span>
			<?php
            } ?>
        </div>
        <div class="col-xs-4 col-sm-4 nopad text-right posted-by-snippet-buttons">
            <?php
            if ($post['post_id'] > "0") { ?>
                <?php
                echo widget("Bootstrap Theme - Display - Print Button",'',$w['website_id'],$w);
                $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
                if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                    echo '<span class="postItem postData" data-userid="'.$post['user_id'].'" data-datatype="'.$post['data_type'].'" data-dataid="'.$post['data_id'].'" data-postid="'.$post['post_id'].'"></span>';
                    echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
                } ?>
                <?php
                addonController::showWidget('post_comments','c61c5d9618e1dff00ed70f4e1ad180dc'); ?>
            <?php } else { ?>
                <?php
                echo widget("Bootstrap Theme - Display - Print Button",'',$w['website_id'],$w);
                $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
                if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                    echo '<span class="postItem groupData" data-userid="'.$group['user_id'].'" data-datatype="'.$group['data_type'].'" data-dataid="'.$group['data_id'].'" data-postid="'.$group['group_id'].'"></span>';
                    echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
                } ?>
                <?php
                addonController::showWidget('post_comments','c61c5d9618e1dff00ed70f4e1ad180dc');
            } ?>
        </div>
        <div class="clearfix"></div>
    </div>
<?php
global $commentPosLeft;
$commentPosLeft=true;
addonController::showWidget('star_ratings_for_posts','609d748eaa051578e8ae2e3c8f848d9a');
?>