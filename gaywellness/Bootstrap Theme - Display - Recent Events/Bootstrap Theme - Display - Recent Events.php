<?php
/*
 * Widget Name:  Bootstrap Theme - Display - Recent Events
 * Short Description: Enables the Streaming of Particular Membership Feature Posts.
 */
global $postExtra,$post,$postLocation,$sqlWhereParameters,$dc;

$eventFeatureUsersMeta = bd_controller::users_meta(WEBSITE_DB)->get('1','is_event_feature');

if($eventFeatureUsersMeta !== false){//we try to get is_event_feature = 1
    $eventFeatureUsersMeta = (is_array($eventFeatureUsersMeta))?$eventFeatureUsersMeta[0]:$eventFeatureUsersMeta;
    $dc = bd_controller::data_categories(WEBSITE_DB)->get($eventFeatureUsersMeta->database_id,'data_id');
    bd_controller::data_categories(WEBSITE_DB)->loadProperties($dc);
    $dc = (array)clone bd_controller::data_categories(WEBSITE_DB);
}else{//we try to get form_name = event_fields
    $dataCategoriesResult = bd_controller::data_categories(WEBSITE_DB)->get('event_fields','form_name');
    if($dataCategoriesResult !== false){
        $dc = (is_array($dataCategoriesResult))?$dataCategoriesResult[0]:$dataCategoriesResult;
        bd_controller::data_categories(WEBSITE_DB)->loadProperties($dc);
        $dc = (array)clone bd_controller::data_categories(WEBSITE_DB);
    }
}

$featureUrl = $wa['custom_192']; // Use to Set the name of the Membership Feature this Streaming Widget will use. Can be controlled in the Design Settings.
$maxItems = $wa['custom_169']; // Maximum amount of streaming items shown. Default is 4. Can be controlled in the Design Settings.
$hidePostsWithoutPhotos = $wa['custom_181']; // This will show or hide posts if they do not have photos. Default is true. Can be controlled in the Design Settings.

if ($wa['post_events_hideEmptyStream'] == "1" || $wa['post_events_hideEmptyStream'] == "") {
    $hideEmptyStream = true; // This hides the feature if there are no posts to show on it. Default is true. Can be controlled in the Design Settings.
} else {
    $hideEmptyStream = false;
}
if ($wa['post_events_onlyActiveMembers'] == "1" || $wa['post_events_onlyActiveMembers'] == ""){
    $onlyActiveMembers = true; // Show or Hide results from Active Members Only. Default is true.
} else {
    $onlyActiveMembers = false;
}
if ($wa['post_events_searchablePostsOnly'] == "0" || $wa['post_events_searchablePostsOnly'] == ""){
    $searchablePostsOnly = true; // Show posts even if they are not searchable. Useful for Membership Levels that do not want to show in the search results but want to show their posts in the streaming widgets, similar to how an admin account would work.
} else {
    $searchablePostsOnly = false;
}
if($wa['streaming_view_more_class'] != ''){
    $viewAllClass = $wa['streaming_view_more_class'];
} else {
    $viewAllClass = 'btn-info';
}

if($wa['streaming_read_more_class'] != ''){
    $readMoreClass = $wa['streaming_read_more_class'];
} else {
    $readMoreClass = 'btn-success';
}
$sortingOrder = 'dp.post_start_date ASC'; // Sorting Order in which results will be shown. Values supported are DESC (Descending) & ASC (Ascending). Default is ASC.

//order logic for recurring events
$isRamdon               = false;
$recurringEventsOrder   = 'default';

if(isset($wa['custom_285'])){
    $sortingOrder   = ($wa['custom_285'] != 'RAND()')?"dp.post_start_date ".$wa['custom_285']:$wa['custom_285'];

    switch ($wa['custom_285']) {
        case 'RAND()':
            $isRamdon = true;
            break;

        case 'ASC':
            $recurringEventsOrder = 'publish-asc';
            $sortingOrder = "dp.post_live_date ASC";
            break;

        case 'DESC':
            $recurringEventsOrder = 'publish-desc';
            $sortingOrder = "dp.post_live_date DESC";
            break;

        case 'date-start-asc':
            $recurringEventsOrder = 'date-start-asc';
            $sortingOrder = "dp.post_start_date ASC";
            break;

        case 'date-start-desc':
            $recurringEventsOrder = 'date-start-desc';
            $sortingOrder = "dp.post_start_date DESC";
            break;

        case "date-end-desc":
            $recurringEventsOrder = $wa['custom_285'];
            $sortingOrder = "dp.post_expire_date DESC";
            break;
        case "date-end-asc":
            $recurringEventsOrder = $wa['custom_285'];
            $sortingOrder = "dp.post_expire_date ASC";
            break;

        default:
            $recurringEventsOrder = $wa['custom_285'];
            $sortingOrder = $wa['custom_285'];
            break;
    }
    
    if(!addonController::isAddonActive('recurring_events')){
        
        if (strpos($sortingOrder,"_date ASC") !== false){
            $sortingOrder = str_replace("_date ASC","_date DESC", $sortingOrder);
        } else {
            $sortingOrder = str_replace("_date DESC","_date ASC", $sortingOrder);
        }
    }
}
$onlySubscriptionIds = $wa['post_events_onlySubscriptionIds'];
$recurringEventAddOnSecurityWidget = getAddOnInfo("recurring_events","7e166d959bd900cb0d9df212166e1f4d");

if (empty($featureUrl)) {
    $featureUrl = 'events';
}

if (empty($maxItems)) {
    $maxItems = 4;
}

if(is_numeric($featureUrl)){
    $where = "data_id = ".$featureUrl;
}else{
    $where = "data_filename LIKE '".$featureUrl."'";
}

if ($wa['custom_305'] == "2"){
    $columnWidth = "col-md-6";
} else if ($wa['custom_305'] == "1") {
    $columnWidth = "col-md-4";
} else {
    $columnWidth = "col-md-3";
}

// Query that grabs information from the Membership Feature selected
$membershipFeaturesSQL = mysql($w['database'], "SELECT
        data_id, data_filename
    FROM
        data_categories
    WHERE
        ".$where."
    AND
        data_active = '1'
    ORDER BY
        data_id
    LIMIT
        1");
$features = mysql_fetch_assoc($membershipFeaturesSQL);

$sqlSelectParameters = array(
    "dp.post_id",
    "dp.post_title",
    "dp.post_content",
    "dp.post_image",
    "dp.post_filename",
    "dp.post_updated",
    "dp.post_start_date",
    "dp.post_expire_date",
    "dp.user_id",
    "CASE WHEN length(dp.post_expire_date) = 8 THEN CONCAT(dp.post_expire_date, '000000') ELSE dp.post_expire_date END AS expire_date",
    "CASE WHEN length(dp.post_start_date) = 8 THEN CONCAT(dp.post_start_date, '000000') ELSE dp.post_start_date END AS start_date"
);

if( ($dc['form_name'] == "event_fields" || $dc['is_event_feature'] == 1) && ( ( isset($dc['enable_recurring_events_addon']) && $dc['enable_recurring_events_addon'] == 1) || !isset($dc['enable_recurring_events_addon']) ) && (isset($recurringEventAddOnSecurityWidget['status']) && $recurringEventAddOnSecurityWidget['status'] == 'success' && ( isset($dc['enable_recurring_events_addon']) && $dc['enable_recurring_events_addon'] == 1) ) ){
    $sqlSelectParameters[] = "IF(dp.post_expire_date='',IF(dp.recurring_type > 0,'',dp.post_start_date),dp.post_expire_date) as post_expire_date_having";
}else{
    $sqlSelectParameters[] = "IF(dp.post_expire_date='',dp.post_start_date,dp.post_expire_date) as post_expire_date_having";
}

if ($wa['post_events_onePostperMember'] == "1") {
    if ($wa['custom_285'] == "" || $wa['custom_285'] == "DESC"){
        $tempTableOrder = $sortingOrder.", post_live_date DESC";
    } else if ($wa['custom_285'] == "ASC"){
        $tempTableOrder = $sortingOrder.", post_live_date ASC";
    } else {
        $tempTableOrder = "RAND()";
    }
    mysql(brilliantDirectories::getDatabaseConfiguration('database'), "CREATE TEMPORARY TABLE temp_data_posts SELECT * FROM data_posts as dp WHERE dp.data_id = '".$features['data_id']."' AND (post_expire_date >= NOW() OR post_expire_date = '') ORDER BY ".$tempTableOrder."");
    $sqlTablesParameters = array(
        "`temp_data_posts` AS dp",
        "`users_data` AS ud",
        "`subscription_types` AS st"
    );
} else {
    $sqlTablesParameters = array(
        "`data_posts` AS dp",
        "`users_data` AS ud",
        "`subscription_types` AS st"
    );
}
$sqlWhereParameters = array(
    "dp.user_id = ud.user_id",
    "ud.subscription_id = st.subscription_id",
    "dp.data_id = '".$features['data_id']."'",
    "dp.post_title != ''",
    "dp.post_status = '1'"
);
if (!empty($onlySubscriptionIds) && $onlySubscriptionIds != " ") {
    array_push($sqlWhereParameters,"ud.subscription_id IN (".$onlySubscriptionIds.")");
}

$sqlGroupByParameters = array();
if ($wa['post_events_onePostperMember'] == "1") {
    array_push($sqlGroupByParameters,"user_id");
}

if(!isset($dc['show_expire_post']) || $dc['show_expire_post'] == 0){
    $sqlHavingParameters = array(
        "((post_expire_date_having >= '".date('YmdHis')."' OR post_expire_date_having >= '".date('Ymd')."' OR post_expire_date_having = '') AND start_date > 0)"
    );
}

$sqlOrderByParameters = array(
    $sortingOrder
);
$sqlLimitParameters = array(
    $maxItems
);

global $finalDate,$finalDate2;

$dateObjectNow = createDateTimeObject();

//we get today date
$finalDate      = $dateObjectNow->format('Ymd');
//by default we need to get maximun end date (end of the days? +1000 years)
$in1000yearsTimeStamp = $dateObjectNow->getTimestamp()+31536000000;

$dateObjectNow->setTimestamp($in1000yearsTimeStamp);

$finalDate2 = $dateObjectNow->format('Ymd');

if(!isset($dc['show_expire_post']) || $dc['show_expire_post'] == 0){
    if( ($dc['form_name'] == "event_fields" || $dc['is_event_feature'] == 1) && ( ( isset($dc['enable_recurring_events_addon']) && $dc['enable_recurring_events_addon'] == 1) || !isset($dc['enable_recurring_events_addon']) ) && (isset($recurringEventAddOnSecurityWidget['status']) && $recurringEventAddOnSecurityWidget['status'] == 'success' && ( isset($dc['enable_recurring_events_addon']) && $dc['enable_recurring_events_addon'] == 1) ) ){
        
        $sqlSelectParameters[] = "dp.recurring_type";

        //we removed the limit
        $sqlLimitParameters = array();

        //we get the security widget to add new where parameters
        $recurringEventQueryAddOn = getAddOnInfo("recurring_events","dc8a4cdcf23bb242c347c7e6bf121db4");

        echo widget($recurringEventQueryAddOn['widget'],"",$w['website_id'],$w);

    }else{
        $sqlWhereParameters[] = "(dp.post_start_date <= '".trim($finalDate)."000000' OR (dp.post_start_date <='".trim($finalDate)."000000' AND dp.post_expire_date <= '".trim($finalDate2)."240000') OR dp.post_start_date <= '".trim($finalDate2)."240000')";
    }
}

if ($onlyActiveMembers) {
    $sqlWhereParameters[] = "ud.active = '2'";
}
if ($hidePostsWithoutPhotos) {
    $sqlWhereParameters[] = "dp.post_image != ''";
}
if ($searchablePostsOnly == true) {
    $sqlWhereParameters[] = "st.searchable = '1'";
}
$sqlWhereParameters[] = "st.data_settings LIKE '%".$features[data_id]."%'";

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

if ($_GET['devmode']) {
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

$postArray = array();
$objectsArray = array();

if ($wa['post_events_onePostperMember'] == "1") {
    mysql(brilliantDirectories::getDatabaseConfiguration('database'), "DROP TABLE temp_data_posts");
}

//if we are doing events and we have recurring events
if(($dc['form_name'] == "event_fields" || $dc['is_event_feature'] == 1) && ( (isset($dc['enable_recurring_events_addon']) && $dc['enable_recurring_events_addon'] == 1) || !isset($dc['enable_recurring_events_addon']) ) && (isset($recurringEventAddOnSecurityWidget['status']) && $recurringEventAddOnSecurityWidget['status'] == 'success' && ( isset($dc['enable_recurring_events_addon']) && $dc['enable_recurring_events_addon'] == 1)) ){

    $indexArray = array();

    //pre index the mysql result set to shuffle it if need it
    while ($post = mysql_fetch_assoc($featureResults)) {
        $indexArray[] = $post;
    }

    //if the order is ramdon we shuffle it
    if($isRamdon === true){
        shuffle($indexArray);
    }


    foreach ($indexArray as $post){
        $post               = getMetaData("data_posts",$post['post_id'],$post,$w);
        $event              = new event($post);
        $isEventDateInRange = $event->isEventDateInRange($finalDate,$finalDate2);
        $isEventTimeExpire  = $event->isEventTimeExpire();
        $currentTimeStamp   = $event->currentDateObject->getTimestamp();
        $startTimeStamp     = $event->closestDate->getTimestamp();

        //this for events in date range and the event hasnt start
        if($isEventDateInRange && $currentTimeStamp < $startTimeStamp){
            $objectsArray[] = $event;
        }else if($isEventDateInRange && $currentTimeStamp >= $startTimeStamp && $isEventTimeExpire === false){//this is for events in date range and the event already started and the time hasnt expired
            $objectsArray[] = $event;
        }else{ //debug purspose
            //debugDev::output($event);
            if($_GET['devmode'] == 1){
                echo $post['post_title'].' is expired<br>';
            }   
        }
    }

    //is not ramdon we order it by the order selected.
    if($isRamdon === false){

        event::sortEvents($objectsArray,$recurringEventsOrder);
    }


    require_once($w['flocation'].'/dev_refactor_oop_3.0/classes/paginator.php');
    $paginatorObject = new paginator($maxItems,1,$objectsArray);

    //we re index the post array with the new order

    $objectsArray = array_slice($objectsArray, $paginatorObject->getStart(),$paginatorObject->getRecordsPerPage());

    foreach ($objectsArray as $event) {
        $postArray[] = $event->post;
    }

}else{
    while ($post = mysql_fetch_assoc($featureResults)) {
        $post = getMetaData("data_posts", $post['post_id'], $post, $w);
        $postArray[] = $post;
    }
}

$titleStyleColor = "";
if($wa['recent_events_tColor'] != ""){
    $titleStyleColor = 'style="color: '.$wa['recent_events_tColor'].'"';
}

if ($showFeature == true) { ?>
    <div class="clearfix"></div>
    <div class="content-container">
        <div class="clearfix"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($wa['events_show_view_all'] != '0'){ ?>
                        <a href="/<?php echo $features['data_filename'];?>" class="view-all-btn-desktop hidden-xs btn <?php echo $viewAllClass ?>">
                            %%%view_all_label%%%
                        </a>
                    <?php } ?>
                    <h2 class="nomargin sm-text-center streaming-title" <?php echo $titleStyleColor ?>>
                        <?php echo $wa['custom_123'];?>
                    </h2>
                    <hr>
                </div>
				<div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 slickEvents">
                        <?php
                        foreach ($postArray as $post ) {

                            //if end date is empty is one time event
                            if(empty($post['post_expire_date'])){
                                $post['post_expire_date'] = $post['post_start_date'];
                            }

                            if(strlen((string)$post['post_expire_date']) == 8){
                                $post['post_expire_date'] .= "000000";
                            }

                            if(strlen((string)$post['post_start_date']) == 8){
                                $post['post_start_date'] .= "000000";
                            }

                            //we now check if the event expired
                            if(
                                ($dc['form_name'] == "event_fields" || $dc['is_event_feature'] == 1) &&
                                ( ( isset($dc['enable_recurring_events_addon']) && $dc['enable_recurring_events_addon'] == 0 ) || addonController::isAddonActive('recurring_events') !== true) &&
                                $post['post_expire_date'] <= date('YmdHis') && (!isset($dc['show_expire_post']) || $dc['show_expire_post'] == 0)
                            ){
                                if($_GET['devmode'] == 1){
                                    echo $post['post_title'].' is expired and theres not recurring event addon active<br>';
                                }
                                continue;
                            }

                            $postExtra = getMetaData("data_posts",$post['post_id'],$post,$w);
                            foreach ($post as $key => $value) {
                                $post[$key] = stripslashes($value);
                            }
                            $post['post_title'] = stripslashes($post['post_title']);
                            if (strlen($post['post_title']) > 40 ) {
                                $postTitle = limitName($post['post_title'],40);
                            } else {
                                $postTitle = $post['post_title'];
                            }

                            if (isset($postExtra['post_location']) && strlen($postExtra['post_location']) > 50 ) {
                                $postLocation = limitName($postExtra['post_location'],50);
                            } else if(isset($postExtra['post_location'])){
                                $postLocation = $postExtra['post_location'];
                            }else{
                                $postLocation = "";
                            }

                            if ($post['post_image'] != "") {
                                $postImageFile = explode("/",str_replace("'","",$post['post_image']));
                                $postImageFileName = $postImageFile[3];
                                $thumbnailImage = "/uploads/news-pictures/" . $postImageFileName;
                                if($wa['events_img_quality']){ $thumbnailImage = str_replace('news-pictures','news-pictures-thumbnails',$thumbnailImage);}
                                $postimage = "background-image:url(' ".$thumbnailImage." ')";
                            } else {
                                if($w['default_post_image'] != ''){
                                    $thumbnailImage = $w['default_post_image'];
                                    $postimage = "background-image:url(' ".$thumbnailImage." ')";
                                } else {
                                    $thumbnailImage = "/images/image-placeholder.jpg";
                                    $postimage = "background-image:url(' ".$thumbnailImage." ')";
                                }
                            } ?>
                            <div class="col-sm-6 <?php echo $columnWidth; ?> text-center bmargin">
                            <?php if (!empty($w['lazy_load_images'])) { ?>
                                <div class="pic lazyloader" data-src="<?php echo $thumbnailImage; ?>">
                            <?php } else { ?>
                                <div class="pic" style="<?php echo $postimage; ?>">
                            <?php } ?>
                                    <span class="pic-caption bottom-to-top" onclick>
                                        <h3 class="pic-title">
                                            <?php echo $postTitle;?>
                                        </h3>
                                        <?php
                                        if(($dc['form_name'] == "event_fields" || $dc['is_event_feature'] == 1) && ( $dc['enable_recurring_events_addon'] == 1 || !isset($dc['enable_recurring_events_addon']) ) && (isset($recurringEventAddOnSecurityWidget['status']) && $recurringEventAddOnSecurityWidget['status'] == 'success' && $dc['enable_recurring_events_addon'] == 1)){
                                            echo widget($recurringEventAddOnSecurityWidget['widget'],"",$w['website_id'],$w);
                                        }else{
                                            ?>
                                            <p>
                                            %%%start_date_membership_feature_details%%%:
                                            <?php echo transformDate($post['post_start_date'],"QB");?>
                                            <br>
                                            <?php
                                            if ($postExtra['post_location'] != "") {
                                                echo '<i class="fa fa-map-marker"></i> '.$postLocation;
                                            } ?>
                                        </p>
                                        <?php }?>
                                        <a href="/<?php echo $post['post_filename'];?>" class="btn <?php echo $readMoreClass?> fpad-lg vpad view-more">
                                            %%%results_view_details_link%%%
                                        </a>
                                    </span>
                                    <a aria-label="<?php echo strip_tags($Label['results_view_details_link']);?>" href="/<?php echo $post['post_filename'];?>" class="homepage-link-element <?php if ($wa['streaming_info_display'] == "on_hover") { ?>hidden-xs<?php } ?>"></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <?php if ($wa['events_show_view_all'] != '0'){ ?>
                    <div class="col-md-6">
                        <a href="<?php echo $features['data_filename'];?>" class="btn btn-lg <?php echo $viewAllClass ?> btn-block visible-xs-block">%%%view_all_label%%%</a>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    global $featureSliderEnabled, $featureMaxPerRow, $featureSliderClass, $postsCount;
    $postsCount = $featureNum;
    $featureSliderEnabled = $wa['events_posts_slider'];
    $featureMaxPerRow = $wa['custom_305'];
    $featureSliderClass = '.slickEvents';
    addonController::showWidget('post_carousel_slider','1a19675a36d28232077972bbdb6bb7fe');
} ?>