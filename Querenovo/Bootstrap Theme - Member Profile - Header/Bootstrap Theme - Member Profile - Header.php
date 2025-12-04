<style>
.member-profile-header {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
}
.btn-danger-custom {
    width: fit-content;
    display: flex;
    background-color: #e10c1a;
    color: white;
    padding: 0px 5px;
    border-radius: 5px;
    margin: 0;
    align-items: center;
}
.member-profile-header .profile-image img {
    
    border: 15px solid white;
    height: 160px;
    width: 155px;
}
.btn-secondary.btn-outline {
    background: #222222;
    color: rgb(247 246 246);
    transition: all 250ms 
ease-in-out 0ms;
}
.member-profile-header .content_w_sidebar{
	
}
.custom_btn:hover{
    color: white;
}
.custom_btn{
    color: white;
    padding-top: 0px;
}
.member-profile-header .member-badges {
	align-self: flex-start;
}
.text-yellow-400 {
    --tw-text-opacity: 1;
    color: rgb(250 204 21 / var(--tw-text-opacity, 1));
}
.fill-yellow-400 {
    fill: #facc15;
}
@media only screen and (max-width: 767px) {
	.profile-header-write-review {
		margin-top: 10px !important;
	}
}

.content_w_sidebar{
    width: 1420px;
    margin: 0 auto;
}
@media (min-width: 992px) {
    .member-profile-header{
        background-color: #152850;
        color: white;
        padding: 20px 10px;
    }
    #first_container .container{
        width: 100% !important;
        max-width: 100vw;
    }
}
</style>


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

<div class="row member-profile-header bmargin">
<div class='content_w_sidebar'>
        <div class="col-xs-12 <?php if ($subscription['profile_badge'] != "" || $user['verified'] == 1 || $user['nationwide'] == 1 || strtolower($subscription['location_limit']) == "all") { ?>col-sm-2<? } else { ?>col-sm-3<?php } ?> norpad text-center xs-hpad xs-bmargin profile-image">
            <?php
            list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $userPhoto);
            if ($attr == "") {
                $attr = 'width="400" height="400"';
            }
            if ($sub['receive_messages'] != 1) { 
            ?>
                <a href="/<?php echo $user['filename']; ?>/<?php echo $w['default_connect_url'];?>" title="%%%contact_label%%% <?php echo $w['profession']; ?> <?php echo $user['full_name'];?>">
                    <img <?php echo $attr; ?> class="img-rounded" src="<?php echo $userPhoto;?>" alt="<?php echo $w['profession']; ?> <?php echo $user['full_name']; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>" title="%%%contact_label%%% <?php echo $user['full_name']; ?>">
                </a>
            <? } else { ?>
                <img <?php echo $attr; ?> class="img-rounded" src="<?php echo $userPhoto;?>" alt="<?php echo $w['profession']; ?> <?php echo $user['full_name']; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>" title="%%%contact_label%%% <?php echo $user['full_name']; ?>">
            <?php } ?>
        </div>
    <div class="xs-text-center col-xs-12 col-sm-9 the-header-member-main-info">
        <div class="row the-header-member-name">
            <div class="col-sm-10 norpad xs-hpad header-member-name xs-center-block notranslate">
                <h1 class="bold inline-block">
                    <?php echo $user['full_name']; ?>
                </h1>
			</div>
			<?php
			$addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
			if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
				echo "<div class='col-sm-2 text-right nolpad bmargin xs-nopad xs-text-center xs-center-block header-favorite-button'>";
				echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
				echo "</div>";
			} ?>
        </div>
        <div class="row the-header-member-details">
            <div class="col-sm-6 tmargin xs-nomargin">
                <p class="line-height-xl nomargin">
                    <?php
                    $invisibleClass ='';
                    if($w['hide_top_level_member_profile'] == 1){
                        $invisibleClass ='invisible';
                    } ?> 
                    <div style="display: flex;padding-bottom: 15px;">
                        <p class="btn-danger-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-3 h-3 mr-1"><path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path><circle cx="12" cy="8" r="6"></circle></svg>
                            France Renov'
                        </p> &nbsp;&nbsp;

                        <?php
                        if ($user['profession_id'] != "") {
                        
                            echo "<span class=' btn-danger-custom profile-header-top-category $invisibleClass'>";
                            echo stripslashes(getProfession($user['profession_id'],$w))."<br /></span>";
                        } ?>
                    </div>
                    <div style="display: flex;padding-bottom: 15px;">
                        <div class="flex items-center gap-1" bis_skin_checked="1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-5 h-5 fill-yellow-400 text-yellow-400"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-5 h-5 fill-yellow-400 text-yellow-400"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-5 h-5 fill-yellow-400 text-yellow-400"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-5 h-5 fill-yellow-400 text-yellow-400"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star w-5 h-5 fill-yellow-400 text-yellow-400"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path></svg><span class="ml-2 text-sm"><font dir="auto" style="vertical-align: inherit;">
                                <font dir="auto" style="vertical-align: inherit;"> 
                                    &nbsp;
                                    <?php 
                                    $user_id = (int)$user['user_id'];

                                    $query = mysql_query("SELECT COUNT(*) AS count FROM `users_reviews` WHERE user_id = $user_id");
                                    $result = mysql_fetch_assoc($query);

                                    echo $result['count'];
                                    ?>


                                    review</font>
                            </font>
                        </span>
                    </div>
                    </div>
                    <?php

                    if ($user['listing_type'] != 'Company') {
                        echo "<span style='display: flex;padding-bottom: 15px;' class=profile-header-company>";
                        if ($user['company'] != "") {

                            if ($user['position'] != "") { ?>
                                <?php echo $user['position'];?> %%%at_label%%%
                            <?php }
                            echo $user['company'];
                            echo "<br /></span> <div class='clearfix'></div> ";
                        }
                    }
                    
                    if ($sub['profile_layout'] != "0" && (!empty($user['city']) || !empty($user['state_ln']) || !empty($user['zip_code']) || !empty($user['country_ln']))) {
                        echo '<span class=profile-header-location>
                        <span style="float:left;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-5 h-5 flex-shrink-0 mt-0.5"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg> 
                        </span> &nbsp;
                        ';

                        if (!empty($user['city']) || !empty($user['state_ln']) || !empty($user['zip_code'])) {
                            if (!empty($user['city'])) { 
                                echo $user['city'];
                            }
                            if (!empty($user['state_ln'])) {
                                if(!empty($user['city'])) {
                                    echo ", ";
                                }
                                echo $user['state_ln'];
                            }
                            if (!empty($user['zip_code'])) {
                                if(!empty($user['city']) || !empty($user['state_ln'])) {
                                    echo ", ";
                                }
                                echo $user['zip_code'];
                            }
                        } else if (!empty($user['country_ln'])) {
                            echo $user['country_ln'];
                        }
                        echo "</span>";
                    } ?>
                </p>
            </div>


            <?php
            //review on && can send leads,show phone || review on && can't send leads, hide phone
            if ($reviewState == 1 && (($sub['receive_messages'] != 1 && $user['phone_number'] != "" && $sub['show_phone'] == 1) || ($subscription['receive_messages'] == 1 && ($subscription['show_phone'] != 1 || $sub['show_phone'] == 1 && $user['phone_number'] == "")))) { ?>
                <div class="col-sm-6 tmargin profile-header-write-review" style="display: none;">
                    <?php
                    if ($reviewState == 1 && $subscription['hide_reviews_rating_options'] == 0) {
                        echo $rating['stars'];
                    } ?>
                    <?php
                    if ($reviewState == 1) { ?>
                        <a class="tmargin btn btn-secondary btn-lg btn-write_a_review_for" href="/<?php echo $user['filename']; ?>/writeareview" title="%%%write_a_review_for%%%">
                            %%%profile_write_a_review%%%
                        </a>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            <?php } else { ?>
                <div class="clearfix"></div>
            <?php } ?>


            <!-- <?php
            //can send leads
            if ($sub['receive_messages'] != 1) { ?>
                <div class="col-sm-6 tmargin profile-header-send-message">
                    <a class="btn btn-primary btn-block btn-lg btn-send_message_action" title="%%%contact_label%%% <?php echo $user['full_name']; ?>" href="/<?php echo $user['filename']; ?>/<?php echo $w['default_connect_url'];?>">
                    <?php if ($w['enable_direct_chat_messages'] == "1" && $subscription['enable_direct_messages'] == "1") { ?>
                        %%%send_message_action%%%
                    <?php } else { ?>
                        %%%profile_send_message_button%%%
                    <?php } ?>
                    </a>
                </div>
            <?php } ?> -->

            <?php
            //show phone number
            if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>

                <p class='btn custom_btn'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone w-5 h-5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    &nbsp;
                    <a href="tel:<?php echo $user['phone_number']; ?>" class="custom_btn">
                        <?php echo $user['phone_number']; ?> 
                    </a>
                </p>

            <? } ?>

            <?php
            //show email
            if ($user['email'] != "" ) { ?>
            <p class='btn custom_btn'>
                
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail w-5 h-5"><rect width="20" height="18" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                &nbsp;
                <a href="mailto:<?php echo $user['email']; ?>" class="custom_btn">
                    <?php echo $user['email']; ?> 
                </a>
            </p>
            <? } ?>

            <?php
            //show website
            if ($user['website'] != "" ) { ?>
                <p class='btn custom_btn'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe w-5 h-5"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
                    &nbsp;
                    <a href="<?php echo $user['website']; ?>" class="custom_btn">
                        <?php echo $user['website']; ?> 
                    </a>
                </p>
            <? } ?>

           <?php
            if ($reviewState == 1) { ?>
             <p class='btn custom_btn'>
                <a class="tmargin btn text-default btn-write_a_review_for custom_btn" href="/<?php echo $user['filename']; ?>/writeareview" title="%%%write_a_review_for%%%">
                    %%%profile_write_a_review%%%
                </a>
            </p>
            <?php } ?>
            

            <?php
            //  echo $subscription['hide_reviews_rating_options'] ;
            //  echo 'this is wolign';
            ?>

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
                <div class="col-sm-6 profile-header-write-review" style="display:none; margin-top:<?php if ($memberReviewsExist == false || $subscription['hide_reviews_rating_options'] == 1){echo '0px';}else{echo '-20px';}?>;">

                    <?php
                    if ($reviewState == 1 && $subscription['hide_reviews_rating_options'] == 0) {
                        echo $rating['stars'];
                    } ?>
                    <?php
                    if ($reviewState == 1) { ?>
                    
                        <a class="tmargin btn btn-secondary btn-lg btn-block btn-write_a_review_for" href="/<?php echo $user['filename']; ?>/writeareview" title="%%%write_a_review_for%%%">
                            %%%profile_write_a_review%%%
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>
    </div>
    <?php
    if ($subscription['profile_badge'] != "" || $user['verified'] == 1 || $user['nationwide'] == 1 || strtolower($subscription['location_limit']) == "all") { ?>
        <div class="hidden-xs col-sm-1 nolpad member-badges">
            <?php echo widget("Bootstrap Theme - Member Profile - Badges","",$w['website_id'],$w); ?>
        </div>
    <?php } ?>
</div>
</div>
<div class="clearfix"></div>