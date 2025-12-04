<?php
/**
 * This widget follows BD code standards
 * Widget Name: Search - Post - Search Query
 * Short Description:
 * Version: 0.1
 */
global $dc,$pars,$_GET,$profession,$service,$ps,$city,$bsearch,$profs,$country,$state,$_REQUEST,$vars,$user,$finalDate,$finalDate2,$sqlWhereParameters,$sqlTablesParameters;

$recurringEventAddOnInfo    = getAddOnInfo("recurring_events");
$stickyPostAddon            = getAddOnInfo('sticky_post');
$isCountrySearch            = false;
$isStateSearch              = false;

$coordinatesArray = array('nelng','nelat','swlng','swlat','lat','lng');
foreach ($_GET as $getkey=>$getvalue) {
    if (in_array($getkey,$coordinatesArray) && !is_numeric($getvalue)) {
        $_GET[$getkey] = 0;
    }
}

if (!array_key_exists("blog_article_fields",$dc)) {
    $dc = getDataCategory($dc['data_id'],"data_id",$w);
}


ob_start();
$now = date ('YmdHis');
$sql;
//Default select parameters
$sqlSelectParameters = array(
    "dp.*",
    "CASE WHEN length(dp.post_expire_date) = 8 THEN CONCAT(dp.post_expire_date, '000000') ELSE dp.post_expire_date END AS expire_date",
    "CASE WHEN length(dp.post_start_date) = 8 THEN CONCAT(dp.post_start_date, '000000') ELSE dp.post_start_date END AS start_date",
);

//Default tables for a select
$sqlTablesParameters = array(
    "`data_posts` AS dp",
    "`users_data` AS ud",
    "`subscription_types` AS st"
);
//Default Where parameters
$sqlWhereParameters = array(
    "dp.user_id = ud.user_id",
    "ud.subscription_id = st.subscription_id",
    "dp.post_status = '1'",
    "ud.active = '2'",
    "st.data_settings LIKE '%".$dc['data_id']."%'"
);
//Default Group By parameters
$sqlGroupByParameters = array();


//Default Having parameters
if ($dc['form_fields_name'] == "blog_article_fields") {
    $sqlHavingParameters = array(
        "(start_date <= '".$now."' OR start_date = '')",
        "(expire_date >= '".$now."' OR expire_date = '')"
    );

} else if((!isset($recurringEventAddOnInfo['status']) || $recurringEventAddOnInfo['status'] != 'success' || (isset($dc['enable_recurring_events_addon']) && $dc['enable_recurring_events_addon'] == 0)) && ($dc['form_name'] == 'event_fields' || $dc['is_event_feature'] == 1)){
    $sqlHavingParameters = array(
        "((expire_date >= '".$now."' OR expire_date = '') AND start_date > 0)"
    );
}else if(isset($recurringEventAddOnInfo['status']) && $recurringEventAddOnInfo['status'] == 'success' && ($dc['form_name'] == 'event_fields' || $dc['is_event_feature'] == 1) && ( $dc['enable_recurring_events_addon'] == 1 || !isset($dc['enable_recurring_events_addon']) ) ){
    $sqlHavingParameters = array(
        "((expire_date >= '".$now."' OR expire_date >= '".date('Ymd')."' OR expire_date = '') AND start_date > 0)"
    );
}else if($dc['form_name'] != 'event_fields'){
    $sqlHavingParameters = array(
        "(expire_date >= '".$now."' OR expire_date = '')"
    );
}

//Default Order By parameters
$sqlOrderByParameters = array(
    "start_date DESC",
    "dp.post_live_date DESC",
    "st.search_priority DESC",
    "dp.post_title DESC"
);

//if there is a data type and data id on the enviroment stated, use them for getting the posts from that data category
if ($dc['data_type'] > 0) {
    $sqlWhereParameters[] = "dp.data_type = '".$dc['data_type']."'";
}
if ($dc['data_id'] > 0) {
    $sqlWhereParameters[] = "dp.data_id = '".$dc['data_id']."'";
}
$searchKeyWordAddonInfo = getAddonInfo('search_members_keywords','6aa2f7c946eaaa69a87a05475f459cbf');

if(isset($searchKeyWordAddonInfo['status']) && $searchKeyWordAddonInfo['status'] == 'success' && $_GET['q'] != ""){
    widget($searchKeyWordAddonInfo['widget'],"",$w['website_id'],$w);
}else if($_GET['q'] != "") {
    $sqlWhereParameters[] = '(dp.post_title LIKE "%'.mysql_real_escape_string(stripslashes(stripslashes($_GET['q']))).'%" OR dp.post_tags LIKE "%'.mysql_real_escape_string(stripslashes(stripslashes($_GET['q']))).'%")';
}

//if there is an user id
if ($user['user_id'] > 0 || (isset($_GET['userid']) && intval($_GET['userid']) > 0) ) {

    if($user['user_id'] > 0){
        $userid = $user['user_id'];
    }else if(isset($_GET['userid']) && intval($_GET['userid']) > 0){
        $userid = intval($_GET['userid']);
    }

    $sqlWhereParameters[] = "dp.user_id = '".$userid."'";
}
// if a location is being searched
if ($_GET['location_value'] != "") {

    //check if the website is using kilometers or miles as the metric structure
    if ($w['distance'] == "mi") {
        $metricUnit = 3959;

    } else {
        $metricUnit = 6371;
    }

    //NOTE: this engine relies on the implementation of the google maps api on the pages that will send the coordinates parameters on the url
    //check if there a fsearch (force search) parameter to based search on that
    if ($_GET['fsearch'] != "") {
        //perform a radius search
        $sqlSelectParameters[] = "( ".$metricUnit." * acos( cos( radians(".mysql_real_escape_string($_GET['lat']).") ) * cos( radians( dp.lat ) ) * cos( radians( dp.lon ) - radians(".mysql_real_escape_string($_GET['lng']).") ) + sin( radians(".mysql_real_escape_string($_GET['lat']).") ) * sin(radians(dp.lat)) ) ) AS distance";
        $sqlHavingParameters[] = "distance <= ".$w['default_radius'] . " OR distance IS NULL";
        $sqlOrderByParameters = array(
            "dp.post_start_date DESC",
            "distance ASC",
            "st.search_priority ASC",
            "dp.post_title ASC"
        );
        $profs['location_search_type'] = "radius";
        //if the fsearch parameter is not submited or empty then the system will do the regular search
    } else {

        // all the posible search types provided by google and how they are grouped in 6 main groups for their use in our system and for search settings in our system
        $AddressComponentsArray = array (
            "country" => array (
                "country"
            ),
            "administrative_area_level_1" => array (
                "administrative_area_level_1"
            ),
            "administrative_area_level_2" => array (
                "administrative_area_level_2",
                "administrative_area_level_3",
                "administrative_area_level_4",
                "administrative_area_level_5"
            ),
            "locality" => array (
                "locality",
                "colloquial_area",
                "ward",
                "sublocality",
                "sublocality_level_1",
                "sublocality_level_2",
                "sublocality_level_3",
                "sublocality_level_4",
                "sublocality_level_5"
            ),
            "postal_code" => array (
                "postal_code"
            ),
            "neighborhood" => array (
                "neighborhood",
                "street_address",
                "route",
                "intersection",
                "political",
                "premise",
                "subpremise",
                "natural_feature",
                "airport",
                "park",
                "point_of_interest"
            )
        );
        foreach ($AddressComponentsArray as $ackey => $acvalue) {

            //by having the type of search we can apply the search method chosen on the advanced settings of the given site
            if (in_array($_GET['location_type'],$acvalue)) {
                $location_type = $ackey;
                break;
            }
        }
        //now that we know the type of search we will get the search method based on the advanced settings on the system
        switch ($location_type) {
            case 'country':
                $searchMethod       = "bounds";
                $isCountrySearch    = true;
                break;
            case 'administrative_area_level_1':
                $isStateSearch      = true;
                $searchMethod       = $w['adm_area_lvl_1_search_method'];
                break;
            case 'administrative_area_level_2':
                $searchMethod = $w['adm_area_lvl_2_search_method'];
                break;
            case 'locality':
                $searchMethod = $w['locality_search_method'];
                break;
            case 'postal_code':
                $searchMethod = $w['zipcode_search_method'];
                break;
            case 'neighborhood':
                $searchMethod = $w['neighborhood_search_method'];
                break;
            default:
                $searchMethod = "bounds";
                break;
        }

        if ($w['strict_search_results'] != 0) {

            if (!empty($_GET['adm_lvl_1_sn'])) {
                $sqlWhereParameters[] = " (LOWER(dp.state_sn) = '" . mysql_real_escape_string(strtolower($_GET['adm_lvl_1_sn'])) . "') ";
            }
            if(!empty($_GET['country_sn'])){
                $sqlWhereParameters[] = " (LOWER(dp.country_sn) = '" . mysql_real_escape_string(strtolower($_GET['country_sn'])) . "') ";
            }
        }

        //include the sql where parameters depending on the type of search you are performing
        switch ($searchMethod) {
            case 'bounds':
            default:

                if( ($isStateSearch === true || $isCountrySearch === true) && $w['strict_search_results'] == 0){

                    if($isCountrySearch === true){
                        $sqlWhereParameters[] = " (LOWER(dp.country_sn) = '" . mysql_real_escape_string(strtolower($_GET['country_sn'])) . "' ) ";
                    }else if($isStateSearch === true){
                        $sqlWhereParameters[] = " (LOWER(dp.state_sn) = '" . mysql_real_escape_string(strtolower($_GET['adm_lvl_1_sn'])) . "' ) ";
                        $sqlWhereParameters[] = " (LOWER(dp.country_sn) = '" . mysql_real_escape_string(strtolower($_GET['country_sn'])) . "' ) ";
                    }

                }else if($isStateSearch === false && $isCountrySearch === false){
                    $sqlWhereParameters[] = "dp.lon >= ".mysql_real_escape_string($_GET['swlng']);
                    $sqlWhereParameters[] = "dp.lon <= ".mysql_real_escape_string($_GET['nelng']);
                    $sqlWhereParameters[] = "dp.lat >= ".mysql_real_escape_string($_GET['swlat']);
                    $sqlWhereParameters[] = "dp.lat <= ".mysql_real_escape_string($_GET['nelat']);
                }

                $profs['location_search_type'] = "bounds";

                break;
            case 'radius':
                //perform a radius search
                $sqlSelectParameters[] = "( ".$metricUnit." * acos( cos( radians(".mysql_real_escape_string($_GET['lat']).") ) * cos( radians( dp.lat ) ) * cos( radians( dp.lon ) - radians(".mysql_real_escape_string($_GET['lng']).") ) + sin( radians(".mysql_real_escape_string($_GET['lat']).") ) * sin(radians(dp.lat)) ) ) AS distance";
                $sqlHavingParameters[] = "distance <= ".$w['default_radius'] . " OR distance IS NULL";
                $sqlOrderByParameters = array(
                    "dp.post_start_date DESC",
                    "distance ASC",
                    "st.search_priority ASC",
                    "dp.post_title ASC"
                );
                $profs['location_search_type'] = "radius";
                break;
        }
    }
    $profs['location'] = $_GET['faddress'];
}// END if $_GET[qlocation] != ""
// events search

$finalDate  = "";
$finalDate2 = "";

if (isset($_GET['daterange']) && $_GET['daterange'] != "") {

    $date_range = explode("-", $_GET['daterange']);
    $startDate = explode("/",$date_range[0]);
    $startDate2 = explode("/",$date_range[1]);
    switch ($w['default_jquery_date_format']) {
        case 'mm/dd/yy':
        case 'm/d/y':
        case 'm/d/yy':
        case 'mm/dd/yy':
        case 'mm/dd/yyyy':
            /* this final format: MM/DD/YYYY*/
            $yearDate= $startDate[2];
            $monthDate= $startDate[0];
            $dayDate= $startDate[1];
            $yearDate2= $startDate2[2];
            $monthDate2= $startDate2[0];
            $dayDate2= $startDate2[1];
            break;
        case 'dd/mm/yy':
        case 'd/m/y':
        case 'd/m/yy':
        case 'dd/mm/yy':
        case 'dd/mm/yyyy':
            /* this final format: DD/MM/YYYY*/
            $yearDate= $startDate[2];
            $monthDate= $startDate[1];
            $dayDate= $startDate[0];
            $yearDate2= $startDate2[2];
            $monthDate2= $startDate2[1];
            $dayDate2= $startDate2[0];
            break;
        case 'yy/mm/dd':
        case 'y/m/d':
        case 'yy/m/d':
        case 'yy/mm/dd':
        case 'yyyy/mm/dd':
            /* this final format: 'YYYY/MM/DD' */
            $yearDate= $startDate[0];
            $monthDate= $startDate[1];
            $dayDate= $startDate[2];
            $yearDate2= $startDate2[0];
            $monthDate2= $startDate2[1];
            $dayDate2= $startDate2[2];
            break;
        case 'yy/dd/mm':
        case 'y/d/m':
        case 'yy/d/m':
        case 'yy/dd/mm':
        case 'yyyy/dd/mm':
            /* this final format: 'YYYY/DD/MM' */
            $yearDate= $startDate[0];
            $monthDate= $startDate[2];
            $dayDate= $startDate[1];
            $yearDate2= $startDate2[0];
            $monthDate2= $startDate2[2];
            $dayDate2= $startDate2[1];
            break;
        default:
            /* this final format: MM/DD/YYYY*/
            $yearDate= $startDate[2];
            $monthDate= $startDate[0];
            $dayDate= $startDate[1];
            $yearDate2= $startDate2[2];
            $monthDate2= $startDate2[0];
            $dayDate2= $startDate2[1];
    }
    $finalDate =  $yearDate . $monthDate . $dayDate;
    $finalDate2 = $yearDate2 . $monthDate2 . $dayDate2;
    $finalDate = str_replace(" ","",$finalDate);
    $finalDate2 = str_replace(" ","",$finalDate2);
}else{
    $dateObjectNow = createDateTimeObject();

    //we get today date
    $finalDate      = $dateObjectNow->format('Ymd');
    //by default we need to get maximun end date (end of the days? +1000 years)
    $in1000yearsTimeStamp = $dateObjectNow->getTimestamp()+31536000000;

    $dateObjectNow->setTimestamp($in1000yearsTimeStamp);

    $finalDate2 = $dateObjectNow->format('Ymd');
}

if(!empty($finalDate) && !empty($finalDate2) && ($dc['form_name'] == 'event_fields' || $dc['is_event_feature'] == 1)){
    $recurringEventAddOn = getAddOnInfo("recurring_events","dc8a4cdcf23bb242c347c7e6bf121db4");

    if(isset($recurringEventAddOn['status']) && $recurringEventAddOn['status'] == 'success' && ( $dc['enable_recurring_events_addon'] == 1 || !isset($dc['enable_recurring_events_addon']) ) ){

        widget($recurringEventAddOn['widget'],"",$w['website_id'],$w);

    }else{
        $sqlWhereParameters[] = "(dp.post_start_date <= '".trim($finalDate)."000000' OR (dp.post_start_date <='".trim($finalDate)."000000' AND dp.post_expire_date <= '".trim($finalDate2)."240000') OR dp.post_start_date <= '".trim($finalDate2)."000000')";
    }

}

if ($_GET['category'][0] != ""){

    $arr            = $_GET['category'];
    $myCategory     = '';

    if(is_array($arr)){
        foreach($arr as $row => $value){
            $myCategory .= mysql_real_escape_string($value) . "|";
        }
        $myCategory = rtrim($myCategory,"|");
    }else if(is_string($arr)){
        $myCategory = mysql_real_escape_string($arr);
    }

    if($myCategory != ''){
        $sqlWhereParameters[] = "dp.post_category REGEXP '" . $myCategory . "'";
    }

}
if ($_GET['employment_type'][0]!=""){
    $arr2 = $_GET;
    $myEmployment;

    foreach($arr2 as $rows => $values){

        foreach($values as $row2s => $value2s){
            $myEmployment .= mysql_real_escape_string($value2s) . "|";
        }
    }
    $myEmployment = rtrim($myEmployment,"|");
    $sqlWhereParameters[] = "dp.post_job REGEXP '" . $myEmployment . "'";
}
if ($_GET['price'] != "") {
    $myFlag = strpos($_GET['price'], ";");

    if ($myFlag) {
        $price_range = explode(";", $_GET['price']);
        $sqlWhereParameters[] = "dp.post_price between " . mysql_real_escape_string($price_range[0]) . " AND " . mysql_real_escape_string($price_range[1]);

    } else {
        $sqlWhereParameters[] = "dp.post_price ='" . mysql_real_escape_string($_GET['price']) . "'";
    }
}
$sqlGroupByParameters[] = "dp.post_id";

if ($dc['category_order_by'] != "default" && $dc['category_order_by'] != "advanced") {

    switch ($dc['category_order_by']) {
        case 'random':
            $randomSeed = session_id();
            $randomSeed = preg_replace("/[^0-9,.]/", "", $randomSeed);
            $sqlOrderByParameters = array(
                "RAND($randomSeed)"
            );
            break;
        case 'alphabet-asc':
            $sqlOrderByParameters = array(
                "dp.post_title ASC"
            );
            break;

        case 'alphabet-desc':
            $sqlOrderByParameters = array(
                "dp.post_title DESC"
            );
            break;

        case 'publish-desc':
            $sqlOrderByParameters = array(
                "dp.post_live_date DESC"
            );
            break;

        case 'publish-asc':
            $sqlOrderByParameters = array(
                "dp.post_live_date ASC"
            );
            break;

        case 'price-asc':
            $sqlOrderByParameters = array(
                "dp.post_price ASC"
            );
            break;

        case 'price-desc':
            $sqlOrderByParameters = array(
                "dp.post_price DESC"
            );
            break;

        case 'date-start-desc':
            $sqlOrderByParameters = array(
                "start_date ASC"
            );
            break;

        case 'date-start-asc':
            $sqlOrderByParameters = array(
                "start_date DESC"
            );
            break;
        case 'date-end-desc':
            $sqlOrderByParameters = array(
                "expire_date ASC"
            );
            break;
        case 'date-end-asc':
            $sqlOrderByParameters = array(
                "expire_date DESC"
            );
            break;
        case 'post-id-asc':
            $sqlOrderByParameters = array(
                "dp.post_id ASC"
            );
            break;
        case 'post-id-desc':
            $sqlOrderByParameters = array(
                "dp.post_id DESC"
            );
            break;

    }
}
// SELECT Constructor Code
// NOTE: No LIMIT needed

$searchPriorityFlag = false;

if( isset($dc['search_priority_flag']) && $dc['search_priority_flag'] == 1){
    $searchPriorityFlag = true;
}

if($searchPriorityFlag === true){
    //we need to remove from default values
    foreach ($sqlOrderByParameters as $key => $order) {
        if($order == 'st.search_priority ASC'){
            unset($sqlOrderByParameters[$key]);
        }
    }
    //we now set as first parameter as search_priority;
    array_unshift($sqlOrderByParameters , 'st.search_priority ASC');
}

if($stickyPostAddon['status'] === 'success'){
    $dateObjectNow  = createDateTimeObject();
    $currentDate    = $dateObjectNow->format('Ymd');

    array_unshift($sqlOrderByParameters , '(sticky > 0) DESC');
    $sqlSelectParameters[] = "IF(REPLACE(dp.sticky_post_expiration_date,'-','') >= '".$currentDate."' OR dp.sticky_post_expiration_date = '' OR dp.sticky_post_expiration_date = '0000-00-00', dp.sticky_post, 0) AS sticky";
}

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
    if ($pars[0] != 'vforum') {
      $sql .= " HAVING ";
      $sql .= implode(" AND ",$sqlHavingParameters);
    }
}
if (count($sqlOrderByParameters) > 0) {
    $sql .= " ORDER BY ";
    $sql .= implode(", ",$sqlOrderByParameters);
}
echo $sql;
$_ENV['sqlquery'] = ob_get_contents();
$_ENV[$dc['form_name']]['finalDate'] = $finalDate;
$_ENV[$dc['form_name']]['finalDate2'] = $finalDate2;
ob_end_clean();
?>