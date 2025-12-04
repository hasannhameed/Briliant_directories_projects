<?php
/*
 * Widget Name:  Bootstrap Theme - Display - Recent Members
 * Short Description: Enables the Streaming of the Membership Feature Listing.
 */
global $Label;

// Main Query Settings
$maxItems = $wa['custom_171']; // Maximum amount of streaming members shown. Default is 4.
$onlyActiveMembers = true; // Show or Hide results from Active Members Only. Default is true.
$enableMemberPriority = false; // Gives priority to members based on the search priority in their membership level. Default is false.
$sortingOrder = 'DESC'; // Sorting Order - DESC (Descending) & ASC (Ascending). Default is DESC.
$hideEmptyStream = true; // This hides the feature if there are no posts to show in it. Default is true.

// Profile Settings
$showMembersWithServicesOnly = false; // Only show members who have sub level categories? Default is false.
$onlySubscriptionIds = $wa['members_onlySubscriptionIds']; // Comma separated list of the subscription_ids that members are listed in to show
$hideNoSearchable = true; // This hides members who are "not-publicly" searchable on the website
$hidePostsWithoutPhotos = $wa['custom_183']; // This will show or hide members if they do not have profile photos. Default is true.

// Category Settings
$showMemberProfession = true; // Enable or Disable showing the member's category. Default is true.
$useServiceName = 0; // A value of 0 displays the member's Top Level Category. A value of 1 displays the member's Sub Level Category.

// Location Settings
$showMemberLocation = true; // Enable or Disable showing the Location of a member. Default is true.
$enableLocalArea = true; // Show the member's City and State location instead of State and Country. Default is true.
$showProfileBtn = ($wa['recent_members_show_profile_btn'])?true:false;


if(isset($wa['HL-HSOO-recent_members-category_to_show'])){
    $useServiceName = $wa['HL-HSOO-recent_members-category_to_show'];
    if($useServiceName == "2"){
        $showMemberProfession = false;
    }
}
if($wa['streaming_view_more_class'] != ''){
    $viewAllClass = $wa['streaming_view_more_class'];
} else {
    $viewAllClass = 'btn-info';
}

if($wa['recent_members_show_location'] == "0"){
    $showMemberLocation = false;
}

// Character Text Limits
$memberNameLength = 30;
$companyLength = 30;
$serviceLength = 30;
$locationLength = 24;

if (empty($maxItems)) {
    $maxItems = 6;
}

$sqlSelectParameters = array(
    "ud.user_id",
    "ud.filename",
    "ud.first_name",
    "ud.last_name",
    "ud.company",
    "ud.listing_type",
    "ud.state_ln",
    "ud.state_code",
    "ud.city",
    "ud.country_ln",
    "ud.profession_id",
    "ud.subscription_id",
    "ud.about_me"
);

$sqlTablesParameters = array(
    "`users_data` AS ud"
);
$sqlWhereParameters = array();
$sqlGroupByParameters = array();
$sqlHavingParameters = array();
if($wa['members_sort_order'] == 'newest' || $wa['members_sort_order'] == ''){
    $sqlOrderByParameters = array(
        $sortingOrder = 'DESC'
    );
} else if ($wa['members_sort_order'] == 'oldest') {
    $sqlOrderByParameters = array(
        $sortingOrder = 'ASC'
    );
}
if ($wa['members_sort_order'] != 'random'){
    $sqlOrderByParameters = array(
        "ud.signup_date " . $sortingOrder
    );
} else {
    $sqlOrderByParameters = array(
        "RAND()"
    );
}
$sqlLimitParameters = array(
    $maxItems
);
if ($hideNoSearchable == true || $enableMemberPriority == true) {
    $sqlTablesParameters[] = " INNER JOIN subscription_types AS st ON ud.subscription_id = st.subscription_id";
}

if ($wa['recent_members_with_star_rating']) { //rating
    //$sqlSelectParameters[]='(SELECT count(*) AS reviews_number FROM `users_reviews` AS ur WHERE ud.user_id = ur.user_id AND ur.review_status = 2 ) AS reviews_number';
    $sqlSelectParameters[] = '(SELECT AVG(rating_overall) FROM `users_reviews` AS ur WHERE ud.user_id = ur.user_id AND ur.review_status = 2) AS rating_overall ';
    $sqlTablesParameters[] = "INNER JOIN users_reviews AS ur ON ud.user_id = ur.user_id ";
    $sqlWhereParameters[] = "ur.review_status = 2";
    $sqlGroupByParameters[] = "ud.user_id";
    $sqlHavingParameters[] = "rating_overall >= " . $wa['recent_members_with_star_rating'];
}

if ($onlyActiveMembers) {
    $sqlWhereParameters[] = "ud.active = '2'";
}
if ($wa['recent_members_display_verified']) {
    $sqlWhereParameters[] = "ud.verified = '1'";
}
$verifyCode = '';
if ($wa['recent_members_display_verified_badge']) {
    $verifyCode = '	<div title="%%%verified_badge_content%%%" class="btn-xs alert-success pull-right bold nopad nolpad inline-block member-search-verified align-middle">
				<span class="btn-xs novpad bg-success pull-left" style="border-radius: 3px 0 0 3px; height: 17px;position: relative;top2px">
					<i class="fa fa-check"></i>
				</span>
				<span class="btn-xs novpad">
					%%%search_results_verified_member%%%
				</span>
			</div>';
}
if ($enableMemberPriority && $wa['members_sort_order'] != 'random') {

    if (count($sqlWhereParameters) > 0) {
        array_unshift($sqlWhereParameters, "ud.subscription_id = st.subscription_id");

    } else {
        $sqlWhereParameters[] = "ud.subscription_id = st.subscription_id";
    }
    if (count($sqlOrderByParameters) > 0) {
        array_unshift($sqlOrderByParameters, "st.search_priority ASC");

    } else {
        $sqlOrderByParameters[] = "st.search_priority ASC";
    }
}
if ($showMembersWithServicesOnly) {
    $sqlTablesParameters[] = "INNER JOIN `rel_services` AS rs ON rs.user_id = ud.user_id";
    $sqlWhereParameters[] = "rs.service_id > 0";
    $sqlGroupByParameters[] = "ud.user_id";
}
if ($onlySubscriptionIds != "") {
    $sqlWhereParameters[] = "ud.subscription_id IN (".$onlySubscriptionIds.")";
}

if(addonController::isAddonActive('sub_accounts') && property_exists(bd_controller::user(WEBSITE_DB), "parent_id")){

    $hideParentsWhere = array(
        array('value' => '1,0' , 'column' => 'value', 'logic' => '='),
        array('value' => 'hide_parent_accounts' , 'column' => 'key', 'logic' => '='),
        array('value' => 'subscription_types' , 'column' => 'database', 'logic' => '=')
    );

    $subsWithHideParent = bd_controller::users_meta()->get($hideParentsWhere);
    $hideParentSubs     = 0;
    if(is_array($subsWithHideParent)){
        foreach($subsWithHideParent as $subWithParent){
            $hideParentSubsArray[] = $subWithParent->database_id;
        }
        $hideParentSubs = implode(',', $hideParentSubsArray);
    } else if($subsWithHideParent !== false) {
        $hideParentSubs = $subsWithHideParent->database_id;
    }

    $subAccountsIds = bd_controller::user()->getSubAccountsUserIds();

    $sqlWhereParameters[]=  "IF ( 
                    st.subscription_id IN (".$hideParentSubs."),
                    IF(
                        ud.user_id NOT IN (".$subAccountsIds."), 1, 0
                    ) ,1
                )  = 1";
}

if (!isset($hidePostsWithoutPhotos)) {
    $hidePostsWithoutPhotos = true;
}
if ($hidePostsWithoutPhotos == true) {

    $sqlTablesParameters[] = "INNER JOIN `users_photo` AS up ON up.user_id = ud.user_id";
    $sqlWhereParameters[] = "up.file != ''";
    $sqlGroupByParameters[] = "ud.user_id";
}

if ($hideNoSearchable == true) {
    $sqlWhereParameters[] = "st.subscription_id = ud.subscription_id";
    $sqlWhereParameters[] = "st.searchable = 1";
}

$membershipAdvOptQuery = mysql($w['database'], "SHOW COLUMNS FROM
`subscription_types`
LIKE
'search_membership_permissions'");
$membershipAdvOpt = mysql_num_rows($membershipAdvOptQuery);

if ($wa['custom_313'] == "2"){
    $columnWidth = "col-md-6";
} else if ($wa['custom_313'] == "0") {
    $columnWidth = "col-md-3";
} else {
    $columnWidth = "col-md-4";
}

if ($membershipAdvOpt > 0) {

    $membersOnlySearchVisibilityAddOn = getAddOnInfo('members_only','a12e81906e726b11a95ed205c0c1ed36');

    if (isset($membersOnlySearchVisibilityAddOn['status']) && $membersOnlySearchVisibilityAddOn['status'] === 'success') {

        echo widget($membersOnlySearchVisibilityAddOn['widget'],"",$w['website_id'],$w);

        if ($_ENV['whereValueSearchOption'] != "" && $w['new_members_search_visibility_options'] == 1) {
            $sqlWhereParameters[] = $_ENV['whereValueSearchOption'];
        }


    }
}
$completedFrom      = array();
$w['is_front_end']                              = true;
$_ENV['completed_users_meta_fields_user_ids']   = array();

users_controller::setCompleteProfileFieldsQuery($completedFrom,false);
users_controller::setCompleteProfileFieldsQuery($sqlWhereParameters);

if(count($completedFrom) > 0 && isset($completedFrom['file'])){

    if (($w['search_results_require_complete_profile'] == "1" && strpos($w['complete_profile_fields'],"file") === false) && $hidePostsWithoutPhotos == "1") {
        $completedFrom['file'].= " INNER JOIN `users_photo` AS up ON up.user_id = ud.user_id ";
    }

    if(strpos($completedFrom['file'],"INNER JOIN `subscription_types`") !== false){
        $tableString = "`users_data` AS ud ".$completedFrom['file'];
    }else{
        $tableString = "`users_data` AS ud INNER JOIN `subscription_types` AS st on st.subscription_id = ud.subscription_id ".$completedFrom['file'];
    }

    if ($wa['recent_members_with_star_rating']) { //rating

        $tableString .= " INNER JOIN users_reviews AS ur ON ud.user_id = ur.user_id ";
        //$sqlHavingParameters[] = "rating_overall >= " . $wa['recent_members_with_star_rating'];
    }

    if(count($_ENV['completed_users_meta_fields_user_ids']) > 0){

        $tableString .= " LEFT JOIN users_meta ON ud.user_id = users_meta.database_id AND users_meta.database = 'users_data'";

        $listOfIds = array();
        foreach($_ENV['completed_users_meta_fields_user_ids'] as $ids){

            if(strpos($ids,"-1") !== false){//if theres no members in at least one users_meta complete profile fields
                $listOfIds = array("-1");
                break;
            }

            $ids        = explode(',',$ids);

            if(count($listOfIds) <= 0){
                $listOfIds  = $ids;
            }else{
                $listOfIds  = array_intersect($listOfIds,$ids);
            }
        }

        $listOfIds                  = implode(',',array_unique($listOfIds));
        $sqlWhereParameters[]       = " ud.user_id IN (".$listOfIds.") ";
    }

    $sqlTablesParameters    = array($tableString);
    $sqlGroupByParameters   = array("ud.user_id");
}

$sql = "";
/* -------------------------- Code That Constructs the SQL statement ------------------------------------- */
if (count($sqlSelectParameters) > 0) {
    $sql .= "SELECT ";
    $sql .= implode(", ",$sqlSelectParameters);
}
if (count($sqlTablesParameters) > 0) {
    $sql .= " FROM ";
    $sql .= implode(" ",$sqlTablesParameters);
}
if (count($sqlWhereParameters) > 0) {
    $sql .= " WHERE ";
    $sql .= implode(" AND ",$sqlWhereParameters);
}
if (count($sqlGroupByParameters) > 0) {
    $sqlGroupByParameters = array_unique($sqlGroupByParameters);
    $sql .= " GROUP BY ";
    $sql .= implode(", ",$sqlGroupByParameters);
}
if (count($sqlHavingParameters) > 0) {
    $sql .= " HAVING ";
    $sql .= implode(" AND ",$sqlHavingParameters);
}
if (count($sqlOrderByParameters) > 0) {
    $sql .= " ORDER BY ";
    $sql .= implode(", ",$sqlOrderByParameters);
}
if (count($sqlLimitParameters) > 0) {
    $sql .= " LIMIT ";
    $sql .= implode(", ",$sqlLimitParameters);
}
/* -------------------------- END Code That Constructs the SQL statement ------------------------------------- */

if ($_GET['devmode'] == 1) {
    echo $sql;
}
$featureResults = mysql($w['database'], $sql);
$featureNum = mysql_num_rows($featureResults);
$showFeature = true;

if ($hideEmptyStream == true) {

    if ($featureNum > 0) {
        $showFeature = true;
    } else {
        $showFeature = false;
    }
}

$titleStyleColor = "";
if($wa['recent_members_tColor'] != ""){
    $titleStyleColor = 'style="color: '.$wa['recent_members_tColor'].'"';
}

if ($showFeature == true) { ?>
    <div class="clearfix"></div>
    <div class="content-container">
        <div class="clearfix"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($wa['members_show_view_all'] == 1) { ?>
                        <a href="/<?php echo $w['default_search_url'];?>" class="view-all-btn-desktop hidden-xs btn <?php echo $viewAllClass ?>">
                            %%%view_all_label%%%
                        </a>
                    <?php } ?>
                    <h2 class="nomargin sm-text-center bold streaming-title h2title" <?php echo $titleStyleColor ?>>
                        <?php echo $wa['custom_125']; ?>
                    </h2>
                    <hr>
                </div>
                <div class="clearfix"></div>
                <div class="grid-container display-recent-members boarder">
                    <div class="row">
                        <div class="slickMembers col-md-12 ">
                            <?php while ($post = mysql_fetch_array($featureResults)) {

                                foreach ($post as $key => $value) {
                                    $post[$key] = stripslashes($value);
                                }
                                $post = getUser($post['user_id'],$w);

                                $subscription = getSubscription($post['subscription_id'], $w);
                                // Query State, Country and Profession Information
                                if ($enableLocalArea) {
                                    $stateName = $post['city'];
                                    $countryName = $post['state_code'];
                                } else {
                                    $stateName = $post['state_ln'];
                                    $countryName = $post['country_ln'];
                                }

                                $memberUrl = $post['filename'];
                                $memberProfession = ucwords($w['profession']);
                                $listingLabel = $Label['listing'];
                                $listingSignupUrl = $Label['default_signup_url'];
                                $serviceName = $post['service_name'];
                                $professionName = '';
                                $subSubCategory = '';
                                // Profession Name Check
                                if ($post['profession_id']) {
                                    $professionName = getProfession($post['profession_id'], $w);
                                    $mainProfessionName = $professionName;

                                    if (strlen($professionName) > $serviceLength ) {
                                        $professionName = mb_substr($professionName,0,$serviceLength, 'UTF-8').'...';
                                    }
                                }

                                // Service Name Check if 1 use sub, if 5 use sub-sub logic
                                if ($useServiceName == 1 || $useServiceName == 5) {

                                    if ($useServiceName == 1) {

                                        if (strlen($serviceName) > $serviceLength ) {
                                            $professionName = mb_substr($serviceName,0,$serviceLength, 'UTF-8').'...';

                                        } else {
                                            $professionName = $serviceName;
                                        }

                                        if (empty($serviceName)) {
                                            $professionName = $mainProfessionName;
                                        }
                                    } else if ($useServiceName == 5 ) {

                                        $subSubCategory =  getMemberSubCategory($post['user_id'], "subsub", "first", 1, "text");

                                        if (strlen($subSubCategory) > $serviceLength ) {
                                            $professionName =  mb_substr($subSubCategory,0,$serviceLength, 'UTF-8').'...';
                                        } else {
                                            $professionName = $subSubCategory;
                                        }

                                        if (empty($subSubCategory)) {
                                            $professionName = $mainProfessionName;
                                        }

                                    }
                                }

                                // First & Last Name Checks
                                $firstName = $post['first_name'];
                                $lastName = $post['last_name'];

                                // Photo Checks
                                $userPhoto = getUserPhoto($post['user_id'], $post['listing_type'], $w);
                                $userPhoto = $userPhoto['file'];

                                // Profession Check
                                if ($showMemberProfession) {

                                    if ($professionName) {
                                        $professionName;
                                    } else {
                                        $professionName = '';
                                    }
                                } else {
                                    $professionName = '';
                                }

                                // Location Check
                                if ($stateName && $countryName) {
                                    $location = $stateName.', '.$countryName;
                                }
                                else if (empty($stateName) && $countryName) {
                                    $location =  $countryName;
                                }
                                else if ($stateName && empty($countryName)) {
                                    $location =  $stateName;
                                } else {
                                    $location = '';
                                }

                                if (!$showMemberLocation) {
                                    $location = '';
                                }

                                if (strlen($location) > $locationLength ) {
                                    $location = mb_substr($location,0,$locationLength, 'UTF-8').'...';
                                }

                                // Company Check
                                $companyName = $post['company'];

                                if ($post['listing_type'] == 'Company') {

                                    if (strlen($companyName) > $companyLength) {
                                        $name = mb_substr($companyName,0,$companyLength, 'UTF-8').'...';

                                    } else {
                                        $name = $companyName;
                                    }
                                    if (strlen($companyName) == 0) {
                                        $name = $firstName.' '.$lastName;
                                    }
                                    $nameTitle = $companyName;

                                } else {
                                    $name = $firstName.' '.$lastName;
                                    $nameTitle = $name;

                                    if (strlen($name) > $memberNameLength) {
                                        $name = mb_substr($name,0,$memberNameLength, 'UTF-8').'...';
                                    }
                                }

                                $ratingStar ='';
                                $noviewclass ='';
                                if ($wa['recent_members_display_star_rating']) {
                                    $rating = getUserRating($post['user_id'], 'profile', $w);
                                    if ($showProfileBtn) {
                                        $ratingStar = '<div class="star_rating small streaming-recent-member-stars stars-with-image" style="line-height:1em;margin-top: 4px;"><small>' . widget('Bootstrap Theme - Function - Overall Member Rating') . '</small></div>';
                                    } else {
                                        $ratingStar = '<div class="star_rating streaming-recent-member-stars" style="line-height:1em;margin-top: 4px;">' . widget('Bootstrap Theme - Function - Overall Member Rating') . '</div>';
                                    }
                                }
                                if ($showProfileBtn) {
                                    $noviewclass = 'smaller-image';
                                }
                                ?>

                                <div class="col-xs-12 col-sm-6 <?php echo $columnWidth; ?> member recent-member <?php echo $noviewclass; ?>">
                                    <div class="well boarder">
                                        <a class="h4 bold bmargin inline-block notranslate name" title="<?php echo $nameTitle; ?> - %%%view_listing_label%%%" href="/<?php echo $memberUrl; ?>">
                                            <?php echo $name; ?>
                                        </a><?php 
											if ($post['verified'] == '1') {
												echo $verifyCode;
											} ?>
                                        <div class="clearfix fpad-sm nobpad"></div>
                                        <div class="col-xs-4 nopad text-center recent-member-image ">
                                            <a title="<?php echo $nameTitle; ?> - %%%view_listing_label%%%" href="/<?php echo $memberUrl; ?>">
                                                <?php if (!empty($w['lazy_load_images'])) { ?>
                                                    <img class="img-rounded center-block lazyloader" loading="lazy" width="400" height="400" alt="<?php echo $name; ?>" data-src="<?php echo $userPhoto; ?>">
                                                <?php } else { ?>
                                                    <img class="img-rounded center-block" loading="lazy" width="400" height="400" alt="<?php echo $name; ?>" src="<?php echo $userPhoto; ?>">
                                                <?php } ?>
                                            </a>

                                            <?php if ($showProfileBtn) {
                                                echo $ratingStar;
                                            } ?>

                                        </div>
                                        <div class="col-xs-8 norpad small recent-member-info">
                                            <p>
                                                <?php if ($useServiceName != "4" && $useServiceName != "3") { ?>
                                                    <?php if ($showMemberProfession === true && !empty($professionName)): ?>
                                                        <span class="bold center-block">
                                                        <?php echo ($useServiceName === '0') ? $Label['category'] : $Label['service']; ?>
                                                        </span>
                                                        <?php echo $professionName; ?>
                                                    <?php endif; ?>
                                                <?php } else if ($useServiceName == "4") {
                                                    $post['search_description'] = bdString::prepareSpecialCharacter($post['search_description']);
                                                    echo limitWords(preg_replace('#<[^>]+>#', ' ', $post['search_description']), 80);
                                                    if (strlen($post['search_description']) > 80) { ?>...<?php }
                                                } else if ($useServiceName == "3") {
                                                    if ($subscription['show_about_tab'] != 0) {
                                                        $post['about_me'] = bdString::prepareSpecialCharacter($post['about_me']);

                                                        if (!empty($w['froala_embedly']) && stristr($post['about_me'], 'fr-embedly') !== false) { // remove style attr
                                                            $post['about_me'] = preg_replace('#" style="(.*?)">#', '', $post['about_me']);
                                                        }
                                                        echo limitWords(preg_replace('#<[^>]+>#', ' ', $post['about_me']),80);
                                                        if (strlen($post['about_me']) > 80) { ?>...<?php }
                                                    }
                                                } ?>
                                                <?php if($location != ""):?>

                                                    <span class="tmargin  center-block ">
                                                        <i class="fa fa-map-marker icon"></i>
														<span class="location"><?php echo $location; ?></span>
                                                    </span>
                                                   
                                                <?php endif;?>
                                            </p>
                                            <?php if ($showProfileBtn) { ?>
                                                <a title="<?php echo $nameTitle; ?> - %%%view_listing_label%%%" class="tmargin text link style color" href="/<?php echo $memberUrl; ?>">
                                                    %%%view_listing_label%%%
                                                </a>
                                            <?php } else {
                                                echo $ratingStar;
                                            } ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($wa['members_show_view_all'] == 1) { ?>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <a href="/<?php echo $w['default_search_url'];?>" class="btn btn-lg <?php echo $viewAllClass ?> btn-block visible-xs-block">
                                %%%view_all_label%%%
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <?php
    global $featureSliderEnabled, $featureMaxPerRow, $featureSliderClass, $postsCount;
    $postsCount = $featureNum;
    $featureSliderEnabled = (isset($wa['members_carousel_slider']))?$wa['members_carousel_slider']:NULL;
    $featureMaxPerRow = $wa['custom_313'];
    $featureSliderClass = '.slickMembers';
    addonController::showWidget('post_carousel_slider','1a19675a36d28232077972bbdb6bb7fe');
    ?>
<?php } ?>