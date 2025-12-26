<?php
if($pars[0] !='account'){ 
    return;
}
$user_data = getUser($_COOKIE['userid'],$w);
$sub = getSubscription($user_data['subscription_id'],$w);

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
        $reviewState = 1;
    }
}
$notification_num = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
        *
    FROM
        `users_meta`,
        `users_reviews`
    WHERE
        database_id = ".$user_data['subscription_id']."
    AND
        `key` = 'accept_delete_reviews' 
	AND
		`value` = '1'
	AND 
		`user_id` = ".$user_data['user_id']."
    AND
        users_reviews.review_status != 3
    ORDER BY
        users_reviews.spoken_language DESC
    LIMIT
        1");

if (mysql_num_rows($notification_num) != null) {

    while ($number_notification = mysql_fetch_array($notification_num)) {
        $ability_rest_sidebar = $number_notification['value'];
        $total_notif = $number_notification['spoken_language'];
    }
}

$userPhoto = getUserPhoto($user_data['user_id'], $user_data['listing_type'], $w);
$userPhoto = $userPhoto['file'];

?>
<div class="col-xs-6 col-sm-3 col-md-3 nolpad member_sidebar">
    <div class="well member_admin_sidemenu" style="padding-top:10px;">
		
        <?php
        if($user_data['parent_id'] > 0){
            $parentUser = getUser($user_data['parent_id'], $w);
            ?>
            <?php if (($_COOKIE['parentToken'] != "" || $_SESSION['parentToken'] != "") && $user['parent_id'] != 0){ ?>
                <center><a href="/login/token/<?php echo $parentUser['token']?>/home"><span class="badge bg-primary hpad line-height-lg bmargin">%%%back_to_parent_account%%%</span></a></center>
            <?php } ?>
            <center><span class="badge bg-danger hpad line-height-lg bmargin">%%%sub_account_of%%%<br><?php echo $parentUser['full_name']?> <small>#<?php echo $user_data['parent_id']?></small></span></center>
        <?php } ?>
        <?php if ($user_data['full_name'] != "") { ?>
            <h4 style="text-transform: capitalize;word-wrap: break-word;" class="bold text-center">
                <?php echo $user_data['full_name']; ?>
            </h4>
        <?php } ?>
        <?php if ($userPhoto != "") { ?>
            <a href="/<?php echo $w['default_account_url']; ?>/<?php echo $w['default_profile_url'];?>">
                <img src="<?php echo $userPhoto ?>" style="max-height:160px;" class="img-thumbnail center-block bmargin">
            </a>
            <div class="clearfix"></div>

        <?php }
        /*decode any slug in filename in case is missing to avoid any error*/
        $filenameBdString = new bdString($user_data['filename']);
        $filenameBdString = $filenameBdString->split('/');

        foreach ($filenameBdString->splittedString as $key => $string) {
            $filenameBdString->splittedString[$key] = $string->urldecode();
        }

        $filenameBdString->glue('/');

        $user_data['filename'] = $filenameBdString->modifiedValue;

        if ($user_data['filename']!="" && $sub['searchable'] == "1") { ?>
            <a target="_blank" href="/<?php echo $user_data['filename']?>" style="" class="btn btn-primary bold btn-block no-radius-bottom view-listing-link">
                %%%view_public_listing_account%%%
                <small><i class="bi bi-box-arrow-up-right fa-fw"></i></small>
            </a>
        <?php } ?>
        <span class="module center-block fpad member-account-information text-center bmargin <?php if ($user_data['filename']!="" && $sub['searchable'] == "1") { ?>no-radius-top<?php } ?>">
            <span class="bold member-account-id">
                %%%account_sidebar_member_id%%%<?php echo $user_data['user_id']; ?>
            </span>
            <br>
            <span class="small member-level-name">
                %%%dashboard_level%%%: <?php echo str_replace("'", "&#39;",$sub['subscription_name']); ?>
            </span>
            <?php if (upgradeAvailable($user_data,$w)>0 && ($user['parent_id'] == "0" || $user['parent_id'] == "")) { ?>
                <a href="/account/upgrade" class="btn btn-secondary btn-sm bold btn-block upgrade-listing-link" style="margin-top: 5px;">
                %%%upgrade_listing%%%
            </a>
            <?php } ?>
        </span>

        <?php
        $lresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
            *
        FROM
            `leads`,
            `lead_matches`
        WHERE
            lead_matches.lead_id = leads.lead_id
        AND
            lead_matches.user_id = '".$_COOKIE['userid']."'
        AND
            lead_matches.lead_status != '7'
        AND
            lead_matches.lead_status != '0'
        AND
            leads.status != '7'
        AND
            leads.status != '6'  AND lead_status = '1'");

        if (mysql_num_rows($lresults) > 0) {
            $leadtotals = mysql_num_rows($lresults);
        }
        if ($ability_rest_sidebar != 0) {
            $lresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
                    *
                FROM
                    `users_reviews`
                WHERE
                    `user_id` = '".$_COOKIE[userid]."'
                AND
                    `review_status` = '0'");

            if (mysql_num_rows($lresults) > 0) {
                $reviewtotals = mysql_num_rows($lresults);
            }
            $notificationtotals = $reviewtotals + $leadtotals;

        } else {

            if ($total_notif == 0) {
                $total_notif = "";
            }
            $notificationtotals = $total_notif + $leadtotals;
            $reviewtotals = $total_notif;
        }
        $homeTabState = '';

        if ($pars[1] == "home") {
            $homeTabState = 'active';
        }
        ?>
        <div class="panel-group sidemenu_panel" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading <?php echo $homeTabState; ?>" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a href="/<?php echo $w['default_account_url'];?>/<?php echo $w['default_account_home_url'];?>">%%%account_dashboard_icon%%% %%%dashboard_home_title%%%</a>
                    </h4>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            %%%manage_listing_icon%%% %%%manage_label%%% %%%listing_member_dashboard_sidebar%%% <i class="pull-right fa fa-caret-down"></i>
                        </a>
                    </h4>
                </div>
                <div class="clearfix"></div>
                <?php
                $listingVarState = '';

                if ($type == "listing" || $type == "promote") {
                    $listingVarState = "in";
                }
                ?>
                <div id="collapseThree" class="panel-collapse collapse <?php echo $listingVarState; ?>" role="tabpanel" aria-labelledby="headingThree">
                    <div>
                        <a href="/<?php echo $w['default_account_url']; ?>/<?php echo $w['default_contact_url'];?>" class="list-group-item <?php if($pars[1] == "contact"){?>bold<?php }?>">%%%dash_label%%% %%%account_listing_tab_1%%%</a>
                        <?php
                        if ($sub['show_profile_photo'] == 1 || $sub['show_logo_upload'] == 1) { ?>
                            <a href="/<?php echo $w['default_account_url']; ?>/<?php echo $w['default_profile_url'];?>" class="list-group-item <?php if($pars[1] == "profile"){?>bold<?php }?>">%%%dash_label%%% %%%account_listing_tab_2%%%</a>
                        <?php }
                        if ($sub['show_listing_details'] == 1) { ?>
                            <a href="/<?php echo $w['default_account_url']; ?>/<?php echo $w['default_resume_url'];?>" class="list-group-item <?php if($pars[1] == "resume"){?>bold<?php }?>">%%%dash_label%%% %%%account_listing_tab_3%%%</a>
                        <?php }
                        if ($sub['show_about_tab'] == 1) { ?>
                            <a href="/<?php echo $w['default_account_url']; ?>/<?php echo $w['default_about_url'];?>" class="list-group-item <?php if($pars[1] == "about"){?>bold<?php }?>">%%%dash_label%%% %%%account_listing_tab_4%%%</a>
                        <?php }
                        $multiListing2 = getAddOnInfo('multi_location');
                        if ((isset($multiListing2['status']) && $multiListing2['status'] === 'success') && $sub['location_limit'] > 0 && $sub['location_limit'] != '') { ?>
                            <a href="/<?php echo $w['default_account_url']; ?>/<?php echo $w['default_locations_url']?>" class="list-group-item">%%%dash_label%%% %%%account_listing_tab_5%%%</a>
                        <?php }
                        if ($w['promote_feature'] == 1) {
							
							// if $subscription['promote_feature_verify'] is not set, set it to $w['promote_feature_verify']
                            if($w['promote_feature_verify'] == 1 && !isset($sub['promote_feature_verify'])) { 
                                $sub['promote_feature_verify'] = $w['promote_feature_verify'];
                            }

                            if ($sub['promote_feature_verify'] == 1 && $user_data['verified'] == 0) { ?>
                                <a href="/account/<?php echo $w['default_verify_url'];?>" class="list-group-item">%%%dash_label%%% %%%verify_listing%%%</a>
                                <?php
                            }
                            if ($w['promote_feature_reviews'] == 1 && $reviewState == 1) { ?>
                                <a href="/account/promote/recommendations" class="list-group-item">%%%dash_label%%% %%%request_recommendation%%%</a>
                                <?php
                            }
                            if ($w['promote_feature_invite'] == 1) { ?>
                                <a href="/account/promote/invite" class="list-group-item">%%%dash_label%%% %%%invite_colleagues%%% </a>
                                <?php
                            }
                        } ?>
                    </div>
                </div>
            </div>
            <?php
            $addonStatistics = getAddOnInfo("user_statistics_addon");
            if (isset($addonStatistics['status']) && $addonStatistics['status'] === 'success' && $sub['profile_statistics'] == "1") { ?>
                <div class="panel panel-default">
                    <div class="panel-heading" id="headingOne">
                        <h4 class="panel-title">
                            <a href="/account/stats" class="sidebar-profile-stats-link">
                                <?php if ($label['profile_statistics_icon'] != "") { ?>
                                    %%%profile_statistics_icon%%%
                                <?php } else { ?>
                                    <i class='fa fa-pie-chart'></i>
                                <?php } ?>
                                %%%sidebar_profile_statistics%%%
                            </a>
                        </h4>
                    </div>
                </div>
                <?php
            }
            if ($sub['hide_notifications'] != "1") { ?>
                <div class="panel panel-default notifications">
                    <div class="panel-heading" id="headingOne">
                        <h4 class="panel-title">
                            <a href="/account/<?php echo $w['default_lead_url'];?>">
                                %%%manage_referral_icon%%%
                                %%%manage_label_leads%%% %%Referral%%
                                <?php if ($leadtotals !="") { ?>
                                    <span class="label img-circle weight-normal font-sm bg-default pull-right">
									<?php  echo displayNumberFormat($leadtotals); ?>
								</span>
                                <?php } ?>
                            </a>
                        </h4>
                    </div>
                </div>
            <?php } ?>

            <?php
            $addOnDirectMessages = getAddOnInfo("member_direct_messages");
            if (isset($addOnDirectMessages['status']) && $addOnDirectMessages['status'] == "success" && $w['enable_direct_chat_messages'] == "1" && $sub['enable_direct_messages']){
                //calculate how many unseen message threads does this member has
                $nonSeenCounter = 0;
                $threadCalcQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
                                        thread_token
                                    FROM
                                        chat_message_threads
                                    WHERE
                                        thread_owner = '".$user_data["token"]."'
                                        OR
                                        thread_responders = '".$user_data["token"]."'");
                $totalChats = mysql_num_rows($threadCalcQuery);
                if($sub['enable_direct_messages'] == "1" || $totalChats > 0) {
                while ($thread = mysql_fetch_assoc($threadCalcQuery)) {
                    //get the last message of each thread and count the non seen
                    $lastMessageQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
                                            *
                                        FROM
                                            chat_message_items
                                        WHERE
                                            thread_token = '".$thread["thread_token"]."'
                                        ORDER BY
                                            created_at DESC
                                        LIMIT
                                            1");
                    $lastMessage = mysql_fetch_assoc($lastMessageQuery);

                    if ($lastMessage["message_owner"] != $user_data["token"] && $lastMessage["message_status"] == 0) {
                        $nonSeenCounter++;
                    }
                }
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading" id="headingTwo">
                        <h4 class="panel-title">
                            <a href="/account/chat_messages" >
                                %%%manage_chat_message_icon%%%
                                %%%chat_messages_page_title%%%
                                <?php if ($nonSeenCounter > 0) { ?>
                                    <span class="label img-circle weight-normal font-sm bg-default pull-right">
									<?php echo displayNumberFormat($nonSeenCounter); ?>
								</span>
                                <?php } ?>
                            </a>
                        </h4>
                    </div>
                </div>
            <?php }
            } ?>
            <?php if ($reviewState == 1) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading" id="headingTwo">
                        <h4 class="panel-title">
                            <a href="/account/recommendations">
                                %%%manage_recommendations_icon%%%
                                %%%manage_label%%% %%Recommendation%%
                                <?php if ($reviewtotals > 0) { ?>
                                    <span class="label img-circle weight-normal font-sm bg-default pull-right">
									<?php echo displayNumberFormat($reviewtotals); ?>
								</span>
                                <?php } ?>
                            </a>
                        </h4>
                    </div>
                </div>
            <?php } ?>

            <?php if ($sub['show_business_toolkit'] == 1 && $w['default_business_toolkit_url'] != "") { ?>
			<div class="panel panel-default">
                <div class="panel-heading <?php echo $homeTabState; ?>" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a href="/<?php echo $w['default_account_url'];?>/<?php echo $w['default_business_toolkit_url'];?>">%%%business_toolkit_icon%%% %%%business_toolkit_title%%%</a>
                    </h4>
                </div>
            </div>
			<?php } ?>

            <hr class="vmargin">

            <?php
            $sub['data_settings']  = array_filter($sub['data_settings']);
            if (is_array($sub['data_settings']) && count($sub['data_settings']) > 0) {
                $subresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
                        `data_id`
                    FROM
                        data_categories
                    WHERE
                        data_id IN (".join(',',$sub['data_settings']).")
                    ORDER BY
						data_type = 29 DESC,
                        profile_display_order ASC");

                $subscriptionData               = $sub;
                $subscriptionLimits             = json_decode($subscriptionData['data_settings_limit'], true);
                $digitalDownloadsAddonStatus    = addonController::isAddonActive('sell_feature_post');

                while ($dt = mysql_fetch_assoc($subresults)) {
                    global $ds;
                    $ds = getDataCategory($dt['data_id'],"data_id",$w);

                    $dataCategoryModelInstance = new data_categories();
                    $dataCategoryModelInstance->getDataCategoryByPostId($ds['data_id']);

                    if($dataCategoryModelInstance->isDigitalProduct() == true && $digitalDownloadsAddonStatus === false){
                        unset($dataCategoryModelInstance);
                        continue;
                    }

                    unset($dataCategoryModelInstance);

                    if ($ds['data_type'] == 28) {
                        //check if this site is using the new sub accounts module
                        /*$checkSubAccountsQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SHOW COLUMNS FROM
                                `users_data`
                            LIKE
                                'parent_id'");*/
                        //get the parent id
                        $parentIdQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
                                parent_id
                            FROM
                                `users_data`
                            WHERE
                                user_id = '".$user_data[user_id]."'");
                        $parentId = mysql_fetch_assoc($parentIdQuery);

                        if ($parentId['parent_id'] != 0) {
                            $hideSubAccounts = 1;

                        } else {
                            $hideSubAccounts = 0; //show sub_accounts
                        }
                        $multiListingAddOnInfo = getAddOnInfo("sub_accounts",'9268c02160f09766325e6b39cd4b7b87');
                    }
                    if ($ds['data_type'] == 28 && $hideSubAccounts == 0 && addonController::isAddonActive('sub_accounts') === true) {

                        if ($ds['data_name']!="") {

                            $lresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
                                    `post_id`
                                FROM
                                    `data_posts`
                                WHERE
                                    `user_id` = '".$user_data[user_id]."'
                                AND
                                    `post_status` = '1'
                                AND
                                    `data_type` = '".$ds[data_id]."'");
                            $total = mysql_num_rows($lresults);

                            echo widget($multiListingAddOnInfo['widget'],"",$w['website_id'],$w);

                        }

                    } else if ($ds['data_type'] != 28) {
                        if ($ds['data_name']!="") {

                            if ($ds['data_type'] != 10 && $ds['data_type'] != 13 && $ds['data_type'] != 21 && $ds['data_type'] != 22 && $ds['data_type'] != 19) {
                                if ($ds['data_type'] == 29) {
                                    $_ENV['addListingHTML'] = false;
                                    $addonFavorites = getAddOnInfo("add_to_favorites","d193769616c816ecf71966f548b68e34");
                                    if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success'){
                                        if($label['my_favorites_label'] == ""){
                                            $favoritesLabel = plural($ds['data_name']);
                                        } else {
                                            $favoritesLabel = $label['my_favorites_label'];
                                        }
                                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" id="heading<?php echo plural($ds['data_id']); ?>">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo plural($ds['data_id']); ?>" aria-expanded="false" aria-controls="collapse<?php echo plural($ds['data_id']); ?>"><i class="fa <?php echo $wa['custom_161']?>"></i> <?php echo $favoritesLabel; ?><i class="pull-right fa fa-caret-down"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="clearfix"></div>
                                            <?php
                                            $dataFnVarStat = '';
                                            if ($pars[1] == $ds['data_filename']) {
                                                $dataFnVarStat = " in ";
                                            }
                                            ?>
                                            <div id="collapse<?php echo plural($ds['data_id']); ?>" class="panel-collapse collapse<?php echo $dataFnVarStat;?>" role="tabpanel" aria-labelledby="heading<?php echo plural($ds['data_id']); ?>">
                                                <div>
                                                    <?php echo widget($addonFavorites['widget'],"",$w['website_id'],$w); ?>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <?php if($_ENV['addListingHTML']) {  ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" id="heading-listing">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" data-favoritefeature="<?php echo strip_tags($label['my_saved_members']);?>" data-favoritefeatureid="<?php echo $_ENV['favoriteFeatureListingResult']['featureId']; ?>" data-favoritefeaturetype="<?php echo $_ENV['favoriteFeatureListingResult']['featureType']; ?>" data-favoriteuser="<?php echo $user['user_id']; ?>"  class="favoriteFeature" href="/account/favorite-members">%%%my_saved_members%%%
                                                        </a>
                                                    </h4>
                                                </div>
                                            </div>
                                            <?php
                                        } if($sub['enable_post_feed'] == 1 && $_ENV['addListingHTML']) {
                                            $postFeedURL = bd_controller::list_seo_template()->get('search_results_favorites', 'seo_type');
                                            $url = $postFeedURL->filename;
                                            ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" id="heading-listing">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" class="favoriteFeature" target="_blank" href="/<?php echo $url;?>">%%%posts_by_saved_members%%%
                                                        </a>
                                                    </h4>
                                                </div>
                                            </div>
                                        <?php }
                                        unset($_ENV['addListingHTML']);
                                        unset($_ENV['favoriteFeatureListingResult']);
                                        ?>

                                        <hr class="vmargin">
                                    <?php }
                                } else { ?>
                                    <?php
                                    if(post_payment_controller::canViewDigitalFeature($ds['data_id'])){
                                        $dataCategoriesModel = new data_categories();
                                        $dataCategoriesModel->getDataCategoryByPostId($ds['data_id']);
                                        ?>
                                        <?php
                                        $dataFnVarStat = '';
                                        if ($pars[1] == $ds['data_filename']) {
                                            $dataFnVarStat = " in ";
                                        }
                                        $subscriptionCategoryLimitSidebar   = $subscriptionLimits[$ds['data_id']];
                                        $purchaseLimitAddOn                 = getAddOnInfo('purchase_limit');
                                        $haveCredit                         = true;
                                        $isGroup                            = ($ds['data_type'] == 14 || $ds['data_type'] == 4 || $ds['data_type'] == 6 || $ds['data_type'] == 25)?true:false;
                                        if(isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === "success"){
                                            $haveCredit         = post_payment_controller::canPost($ds['data_id'],$user_data['user_id'],post_payment_controller::LIMIT,$subscriptionCategoryLimitSidebar,$isGroup,$dataPostLimitted->{$ds['data_id']}->post_limitted);
                                            $dataPostLimitted   = json_decode($subscriptionData['data_post_limitted']);

                                            if(isset($dataPostLimitted->{$ds['data_id']}->post_limitted) && $dataPostLimitted->{$ds['data_id']}->post_limitted == 1){
                                                $subscriptionCategoryLimitSidebar = -1;
                                            }
                                        }

                                        if ($subscriptionCategoryLimitSidebar == 0){
                                            $subscriptionCategoryLimitSidebar = 99999;
                                        }
                                        if ($ds['data_type'] == 14 || $ds['data_type'] == 4 || $ds['data_type'] == 6 || $ds['data_type'] == 25) {
                                            $membersTotalPostQuerySidebar = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT 
                                                count(group_id) 
                                            FROM 
                                                `users_portfolio_groups` 
                                            WHERE 
                                                user_id = '".$user_data['user_id']."' 
                                            AND 
                                                data_id = '".$ds['data_id']."'");
                                        } else {
                                            $membersTotalPostQuerySidebar = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT 
                                            count(post_id) 
                                        FROM 
                                            `data_posts` 
                                        WHERE 
                                            user_id = '".$user_data['user_id']."' 
                                        AND 
                                            data_id = '".$ds['data_id']."'");
                                        }

                                        $membersTotalPostsSidebar = mysql_fetch_array($membersTotalPostQuerySidebar);
                                        ?>
                                        <div class="panel panel-default data-post-sidebar-link">
                                            <div class="panel-heading" id="heading-<?php echo $ds['data_id']; ?>">
                                                <h4 class="panel-title">
                                                    <a href="/account/<?php echo $ds['data_filename']; ?>/<?php echo $w['default_view_post_url'];?>">
													<span class="label img-circle weight-normal font-sm bg-default pull-right post-count-<?php echo $membersTotalPostsSidebar[0]; ?>">
														<?php echo $membersTotalPostsSidebar[0]; ?>
													</span>
                                                        <?php echo $ds['icon'] ;?>
                                                        <?php echo plural($ds['data_name']); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                }
            } ?>
            <hr class="vmargin">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFour">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            %%%manage_account_icon%%% %%%account_label%%% <i class="pull-right fa fa-caret-down"></i>
                        </a>
                    </h4>
                </div>
                <div class="clearfix"></div>
                <?php
                $settingVarStat = "";
                if ($type == "settings") {
                    $settingVarStat = "in";
                }
                ?>
                <div id="collapseFour" class="panel-collapse collapse<?php echo $settingVarStat; ?>" role="tabpanel" aria-labelledby="headingFour">
                    <div>
                        <?php if($sub['hide_billing_links'] != 1){?>
                            <a href="/<?php echo $w['default_account_url']; ?>/<?php echo $w['default_account_billing_url']; ?>" class="list-group-item <?php if($pars[1] == 'billing'){ ?>bold<?php } ?>">%%%dash_label%%% %%%billing_details_account_sidebar%%%</a>
                        <?php }?>
                        <a href="/<?php echo $w['default_account_url']; ?>/<?php echo $w['default_account_changelist_url']; ?>" class="list-group-item <?php if($pars[1] == 'changelisting'){ ?>bold<?php } ?>">%%%dash_label%%% %%%manage_account%%%</a>
                        <a href="/<?php echo $w['default_account_url']; ?>/<?php echo $w['default_account_password_url']; ?>" class="list-group-item change-password-sidebar <?php if($pars[1] == 'password'){ ?>bold<?php } ?>">%%%dash_label%%% %%%profile_edit_pw%%%</a>
                        <a href="%%%default_contact_us_url%%%" class="list-group-item">%%%dash_label%%% %%%contact_us%%%</a>
                        <a href="/logout" class="list-group-item">%%%dash_label%%% %%%dashboard_logout%%%</a>
                    </div>
                </div>
            </div>
			
        </div>
		
    </div>
	
</div>
