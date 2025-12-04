<?php
$sub = getSubscription($user['subscription_id'],$w);
//get the review data_id
$reviewIdQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
        *
    FROM
        `data_categories`
    WHERE
        data_type = '13'
    LIMIT
        1");
$reviewId = mysql_fetch_assoc($reviewIdQuery);
$reviewState = 0;
foreach ($sub['data_settings'] as $sdskey => $sdsvalue) {

    if ($sdsvalue == $reviewId['data_id']) {
        $reviewState = 1;//ON
    }
}
$userPhoto = getUserPhoto ($user['user_id'], $user['listing_type'], $w);
$userPhoto = $userPhoto['file'];

if($sub['coverPhoto'] == 1){
    addonController::showWidget('profile_cover_photo', '023876071143573e41dd0e03f5f56894','');
}
?>

<div class="row member-profile-header">
    <div class="col-xs-12 col-sm-2 norpad text-center xs-nopad xs-bmargin">
        <?php
        if ($sub['receive_messages'] != 1) { ?>
            <a href="/<?php echo $user['filename']; ?>/connect" title="%%%contact_label%%% <?php echo $w['profession']; ?> <?php echo $user['full_name'];?>">
                <img class="img-rounded" src="<?php echo $userPhoto;?>" alt="<?php echo $w['profession']; ?> <?php echo $user['full_name']; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>" title="%%%contact_label%%% <?php echo $user['full_name']; ?>">
            </a>
        <? } else { ?>
            <img class="img-rounded" src="<?php echo $userPhoto;?>" alt="<?php echo $w['profession']; ?> <?php echo $user['full_name']; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>" title="%%%contact_label%%% <?php echo $user['full_name']; ?>">
        <?php } ?>
    </div>
    <div class="xs-text-center col-xs-12 <?php if ($subscription['profile_badge'] != "" || $user['verified'] == 1 || $user['nationwide'] == 1) { ?>col-sm-9<? } else { ?>col-sm-10<?php } ?>">
        <div class="row">
            <div class="col-sm-10 norpad xs-nopad">
                <h1 class="bold inline-block">
                    <?php echo $user['full_name']; ?>
                </h1>
			</div>
			<?php
			$addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
			if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
				echo "<div class='col-sm-2 text-right nolpad bmargin xs-nopad xs-text-center'>";
				echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
				echo "</div>";
			} ?>
        </div>
        <div class="row">
            <div class="col-sm-6 tmargin xs-nomargin">
                <p class="line-height-xl nomargin">
                    <?php /*
                    if ($user['profession_id'] != "") {
                        echo "<span class=profile-header-top-category>";
                        echo stripslashes(getProfession($user['profession_id'],$w))."<br /></span>";
                    }*/
                    if ($user['listing_type'] != 'Company') {
                        echo "<span class=profile-header-company>";
                        if ($user['company'] != "") {

                            if ($user['position'] != "") { ?>
                                <?php echo $user['position'];?> %%%at_label%%%
                            <?php }
                            echo $user['company'];
                            echo "<br /></span>";
                        }
                    }
                    if ($sub['profile_layout'] != "0") {
                        echo "<span class=profile-header-location>";
                        if ($user['city'] != "") { ?>
                            <i class="fa fa-map-marker text-danger"></i>
                            <?php echo $user['city'];?>
                        <?php }
                        if ($user['state_name'] != "") {
                            echo $user['state_name']." ";

                            if ($user['zip_code']!="") {
                                echo $user['zip_code'];
                            }

                        } else {
                            echo getCountry($user['country_code'],$w);
                        }
                        echo "</span>";
                    }?>
                </p>
            </div>

            <?php
            //review on && can send leads,show phone || review on && can't send leads, hide phone
            if ($reviewState == 1 && (($sub['receive_messages'] != 1 && $user['phone_number'] != "" && $sub['show_phone'] == 1) || ($subscription['receive_messages'] == 1 && ($subscription['show_phone'] != 1 || $sub['show_phone'] == 1 && $user['phone_number'] == "")))) { ?>
                <div class="col-sm-6 tmargin profile-header-write-review">
                    <?php
                    if ($reviewState == 1 && $subscription['hide_reviews_rating_options'] == 0) {
                        echo $rating['stars'];
                    } ?>
                    <?php
                    if ($reviewState == 1) { ?>
                        <a class="tmargin btn btn-primary btn-lg btn-block" href="/<?php echo $user['filename']; ?>/writeareview" title="%%%write_a_review_for%%%">
                            %%%profile_write_a_review%%%
                        </a>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            <?php } else { ?>
                <div class="clearfix"></div>
            <?php } ?>


            <?php
            //can send leads
            if ($sub['receive_messages'] != 1) { ?>
                <div class="col-sm-6 tmargin profile-header-send-message">
                    <a class="btn btn-success btn-block btn-lg" title="%%%contact_label%%% <?php echo $user['full_name']; ?>" href="/<?php echo $user['filename']; ?>/connect">
                        %%%profile_action%%%
                    </a>
                </div>
            <?php } ?>

            <?php
            //show phone number
            if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>
                <div class="col-sm-6 tmargin">
                    <?php
                    $clickPhoneAddOn = getAddOnInfo("click_to_phone","16c3439fea1f8b6d897987ea402dcd8e");
                    $statisticsAddOn = getAddOnInfo("user_statistics_addon","7f778bc02f0e6acbbd847b4061c7b76d");

                    if(isset($clickPhoneAddOn['status']) && $clickPhoneAddOn['status'] === 'success'){
                        echo widget($clickPhoneAddOn['widget'],"",$w['website_id'],$w);
                    } else if (isset($statisticsAddOn['status']) && $statisticsAddOn['status'] === 'success') {
                        echo widget($statisticsAddOn['widget'],"",$w['website_id'],$w);
                    } else {
                        if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>
                            <span style="padding:10px 16px;" class="well nobmargin text-center btn-lg btn-block author-phone">
                                <i class="fa fa-phone fa-fw"></i>
                                <?php echo $user['phone_number']; ?>
                            </span>
                        <? }
                    } ?>
                </div>
            <? } ?>

            <?php //echo $subscription['hide_reviews_rating_options'] ;?>

            <?php
            //reviews on && can send leads,hide phone || can't send leads, show phone
            if ($reviewState == 1 && (($subscription['receive_messages'] == 1 && $subscription['show_phone'] == 1 && $user['phone_number'] != "") || ($sub['receive_messages'] != 1 && ($sub['show_phone'] == 0 ||  $sub['show_phone'] == 1 && $user['phone_number'] == "")))) {
                $reviewsAmount = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT 1
                FROM
                    `users_reviews`
                WHERE
                    user_id = ".$user['user_id']." AND review_status = 2");
                $memberReviewsExist = mysql_fetch_array($reviewsAmount);
                ?>
                <div class="col-sm-6 profile-header-write-review" style="margin-top:<?php if ($memberReviewsExist == false || $subscription['hide_reviews_rating_options'] == 1){echo '0px';}else{echo '-20px';}?>;">


                    <?php
                    if ($reviewState == 1 && $subscription['hide_reviews_rating_options'] == 0) {
                        echo $rating['stars'];
                    } ?>
                    <?php
                    if ($reviewState == 1) { ?>
                        <a class="tmargin btn btn-primary btn-lg btn-block" href="/<?php echo $user['filename']; ?>/writeareview" title="%%%write_a_review_for%%%">
                            %%%profile_write_a_review%%%
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>
    </div>
    <?php
    if ($subscription['profile_badge'] != "" || $user['verified'] == 1 || $user['nationwide'] == 1) { ?>
        <div class="hidden-xs col-sm-1 nolpad member-badges">
            <?php echo widget("Bootstrap Theme - Member Profile - Badges","",$w['website_id'],$w); ?>
        </div>
    <?php } ?>
</div>
<div class="clearfix"></div>
