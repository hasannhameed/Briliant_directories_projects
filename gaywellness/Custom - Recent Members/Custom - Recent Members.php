<?php
global $subscription;
?>
<?php
$sub = getSubscription($group['subscription_id'],$w);
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
    $sqlTablesParameters[] = "`subscription_types` AS st";
}

if ($onlyActiveMembers) {
    $sqlWhereParameters[] = "ud.active = '2'";
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
    $sqlTablesParameters[] = "`rel_services` AS rs";
    array_unshift($sqlWhereParameters, "rs.user_id = ud.user_id");
    $sqlWhereParameters[] = "rs.service_id > 0";
    $sqlGroupByParameters[] = "ud.user_id";
}
if ($onlySubscriptionIds != "") {
    $sqlWhereParameters[] = "ud.subscription_id IN (".$onlySubscriptionIds.")";
}

if (!isset($hidePostsWithoutPhotos)) {
    $hidePostsWithoutPhotos = true;
}
if ($hidePostsWithoutPhotos == true) {
    $sqlTablesParameters[] = "`users_photo` AS up";
    array_unshift($sqlWhereParameters, "up.user_id = ud.user_id");
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
$sql = "";
/* -------------------------- Code That Constructs the SQL statement ------------------------------------- */
if (count($sqlSelectParameters) > 0) {
    $sql .= "SELECT ";
    $sql .= implode(", ",$sqlSelectParameters);
}
if (count($sqlTablesParameters) > 0) {
    $sql .= " FROM ";
    $sql .= implode(", ",$sqlTablesParameters);
}
if (count($sqlWhereParameters) > 0) {
    $sql .= " WHERE ";
    $sql .= implode(" AND ",$sqlWhereParameters);
}
if (count($sqlGroupByParameters) > 0) {
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

if (isset($_GET['devmode']) && $_GET['devmode'] == 1) {
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
    <div class="clearfix"></div><br><br>
    <div class="hpad">
        <div class="clearfix"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($wa['members_show_view_all'] == 1) { ?>
                        <a href="/<?php echo $w['default_search_url'];?>" class="view-all-btn-desktop hidden-xs btn <?php echo $viewAllClass ?>">
                            View all practitioners
                        </a>
                    <?php } ?>
                    <h2 class="nomargin sm-text-center bold streaming-title" <?php echo $titleStyleColor ?>>
                       Featured Practitioners
                    </h2>
                    <hr>
                </div>
                <div class="clearfix"></div>
                <div class="grid-container display-recent-members ">
                    <div class="row" >
                        <div class="slickMembers col-md-12" >
                            <?php while ($post = mysql_fetch_array($featureResults)) {

                                foreach ($post as $key => $value) {
                                    $post[$key] = stripslashes($value);
                                }
                                $post = getUser($post['user_id'],$w);
                                $userRating= getUserRating($post['user_id'],'',$w);
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

                                // Profession Name Check
                                if ($post['profession_id']) {
                                    $professionName = getProfession($post['profession_id'], $w);
                                    $mainProfessionName = $professionName;

                                    if (strlen($professionName) > $serviceLength ) {
                                        $professionName = mb_substr($professionName,0,$serviceLength, 'UTF-8').'...';
                                    }
                                }

                                // Service Name Check
                                if ($useServiceName == 1) {
                                    if (strlen($serviceName) > $serviceLength ) {
                                        $professionName = mb_substr($serviceName,0,$serviceLength, 'UTF-8').'...';

                                    } else {
                                        $professionName = $serviceName;
                                    }

                                    if (empty($serviceName)) {
                                        $professionName = $mainProfessionName;
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
                                } ?>

                                <div class="col-xs-12 col-sm-6 <?php echo $columnWidth; ?> member recent-member " style="padding-bottom:20px;">
                                   <div class="col-xs-12 nopad text-center recent-member-image  " style="padding-bottom:10px; display: flex;
    justify-content: center; height: 100%; overflow: hidden; border-radius: 10px; 10px; 0px; 0px;">
		<div style="position: relative;   display: inline;">
                                            <a title="<?php echo $nameTitle; ?> - %%%view_listing_label%%%" href="/<?php echo $memberUrl; ?>">
                                                <img class="img-responsive enlarge" alt="<?php echo $name; ?>" src="<?php echo $userPhoto; ?>" style="min-width:420px; max-height:420px; border-radius: 0px; 0px; 0px; 0px; ">
                                            </a>
			 <?php if ($post['status_check'] != "") { ?>
			 <div class="hidden-xs">
			<div title="Local" class="badge btn-xs bmargin bold nopad nolpad rmargin inline-block member-search-verified" style="background-color:#aae2d3;">
                    <span class="btn-xs novpad bg-local pull-left" style="border-radius: 3px 0 0 3px;">
                        <i class="fa fa-street-view"></i>
                    </span>
                    <span class="btn-xs nolpad novpad">
                       Available Now
                    </span>
                </div>  
			</div><div class="hidden-sm hidden-md hidden-lg hidden-xl "> 
				<div title="Local" class="badge2 btn-xs bmargin bold nopad nolpad rmargin inline-block member-search-verified" style="background-color:#aae2d3;">
                    <span class="btn-xs novpad bg-local pull-left" style="border-radius: 3px 0 0 3px;">
                        <i class="fa fa-street-view"></i>
                    </span>
                    <span class="btn-xs nolpad novpad">
                       Available Now
                    </span>
                </div> </div>
			
			<?php } ?>
</div>
                                        </div>
									<div class="well" >
                                        
                                            <div style=" min-height:55px; padding-top:5px;" ><div class="col-xs-2"><div style="text-align: left">

                                               <?php if ($post[subscription_id] == '10') { ?>
                <img alt="Barber" title="Barber" src="/images/icons/HAIRSKINCARE-homepage-category-b&w-button.png" style="height: 40px; margin-left:-20px;">
                                               <?php } ?>
<?php  if ($post[subscription_id] == '9' xor $post[subscription_id] == '1' xor $post[subscription_id] == '2' xor $post[subscription_id] == '3')  { ?>
              <img alt="Massage" title="Massage" src="/images/icons/MASSAGE-homepage-category-b&w-button.png" style="height: 40px; margin-left:-20px;">
                                              <?php } ?>
<?php if ($post[subscription_id] == '7') { ?>
                 <img alt="Fitness & Yoga" title="Fitness & Yoga" src="/images/icons/FITNESSYOGA-homepage-category-b&w-button.png.png" style="height: 40px; margin-left:-20px;">
                                              <?php } ?>
<?php if ($post[subscription_id] == '6') { ?>
                <img alt="Coaching" title="Coaching" src="/images/icons/COACHES-homepage-category-b&w-button.png" style="height: 40px; margin-left:-20px;">
                                             <?php } ?>
<?php if ($post[subscription_id] == '11') { ?>
                <img alt="Therapy" title="Therapy" src="/images/icons/THERAPY-homepage-category-b&w-button.png" style="height: 40px; margin-left:-20px;">
                                             <?php } ?>
<?php if ($post[subscription_id] == '12') { ?>
                <img alt="Other" title="Other" src="/images/icons/NUTRITION-homepage-category-b&w-button.png" style="height: 40px; margin-left:-20px;">
                                             <?php } ?> </div></div><div class="col-xs-10" style=" min-height:70px;"><div style="text-align: center">

												<a class="h5 bold bmargin inline-block tpad" title="<?php echo $nameTitle; ?> - %%%view_listing_label%%%" href="/<?php echo $memberUrl; ?>" style="font-weight:700;">
                                              <?php echo $post['company']; ?>  <?php if ($post['company'] != "" && $post['first_name'] != "") { ?>    by  <?php } ?> <?php echo $post['first_name']; ?>
                                                </a></div></div></div>
                                        <div class="clearfix"></div>
                                        <div class="col-xs-12" style="line-height: 0.9; min-height:40px;">
											
											
											

											
											
											<span style="font-size:0.7em;"><small>
          <i> <?php echo getMemberSubCategory($post[user_id], "sub", "random", 3, "linked");?></i>
			</small></span></div>

                                        <div class="col-xs-12 norpad nolpad small recent-member-info" >
											<div style="min-height:45px;">

											
                                            <div class="col-sm-6">



                                                <?php if($location != ""):?>
                                                <div class="clearfix" ></div><small><span style="font-size:0.8em;">
                                                <i class="fa fa-map-marker" style="color:#d77d64;"></i> 

                                                    <?php echo $location; ?>
                                                        <?php endif; ?>
</span></small><div class="clearfix" ></div>
												<?php if ($post['phone_number'] != "") { ?>
												<small><span style="font-size:0.8em;">
                                                <i class="fa fa-phone" style="color:#d77d64;"></i> 

                                                    <?php echo $post['phone_number']?>
                                                      
</span></small><?php } ?>
                                            </div>
                                            <div class="col-sm-6" style=" text-align: center;"> <span style="font-size:0.7em;"><?php echo $userRating['stars'];?></span>

                                            </div></div><hr style="height:1px;border-width:0;color:black;background-color:#E0E0E0">
                                            <div class="" style="line-height: 0.9; padding-bottom:10px; min-height: 70px;"><small><span style="font-size:0.9em;">
												<?php if ($useServiceName != "4" && $useServiceName != "3") { ?>
                                                    <?php if($showMemberProfession === true):?>
                                                        <span class="bold center-block">
                                                    %Service%
                                                </span>
                                                        <?php echo $professionName;?>
                                                    <?php endif; ?>
                                                <?php }else if($useServiceName == "4"){
                                                    $post['search_description'] = bdString::prepareSpecialCharacter($post['search_description']);
                                                    echo limitWords(preg_replace('#<[^>]+>#', ' ', $post['search_description']),75);
                                                    if (strlen($post['search_description']) > 75) { ?>...<?php }
                                                }else if($useServiceName == "3"){
                                                    if ($subscription['show_about_tab'] != 0) {
                                                        $post['about_me'] = bdString::prepareSpecialCharacter($post['about_me']);
                                                        echo limitWords(preg_replace('#<[^>]+>#', ' ', $post['about_me']),75);
                                                        if (strlen($post['about_me']) > 75) { ?>...<?php }
                                                    }
                                                } ?></span>
                                                </small></div>
                                        </div>
                                        <div class="clearfix"></div><div style="min-height: 70px">

										<?php if ($post['sms_number'] != "" && $subscription['show_phone'] == 1)  {  ?><div style="padding-top:10px; text-align: center">  <div   style="display: inline-block;">

									
<a class="btn btn-lg" style="background-color:  #aae2d3; color:#0D3F4F !important; border-radius: 25px;" href="sms://<?php echo $post['sms_number']?>&body=Hi,%20I%20saw%20your%20profile%20on%20Gay%20Wellness."> Text me at <span style="text-decoration:none; color:#OD3F4F !important;"><?php echo $post['sms_number']?> </span></a> </div></div> 
<?php } else if ($post['phone_number'] != "" && $subscription['show_phone'] == 1)  {  ?> <div style="padding-top:10px; text-align: center">  <div   style="display: inline-block">
   
<a class="btn btn-lg " style="background-color:  #aae2d3; color:#0D3F4F !important; border-radius: 25px;" href="sms://<?php echo $post['phone_number']?>&body=Hi,%20I%20saw%20your%20profile%20on%20Gay%20Wellness."> Text me at <span style="text-decoration:none; color:#OD3F4F !important;"><?php echo $post['phone_number']?> </span></a></div></div> 
<?php } ?></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    
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
