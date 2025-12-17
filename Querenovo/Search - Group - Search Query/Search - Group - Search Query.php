<?php

global $dc, $_GET, $profession, $service, $ps, $city, $bsearch, $profs, $country, $state, $_REQUEST, $vars, $user,$sqlWhereParameters,$sqlTablesParameters;
ob_start();
$now                = date('YmdHis');
$stickyPostAddon    = getAddOnInfo('sticky_post');
$sql;
$isCountrySearch    = false;
$isStateSearch      = false;

$coordinatesArray = array('nelng','nelat','swlng','swlat','lat','lng');
foreach ($_GET as $getkey=>$getvalue) {
    if (in_array($getkey,$coordinatesArray) && !is_numeric($getvalue)) {
        $_GET[$getkey] = 0;
    }
}

//Default select parameters
$sqlSelectParameters = array(
    "upg.*"
);
//Default tables for a select
$sqlTablesParameters = array(
    "`users_portfolio_groups` AS upg",
    "`data_categories` AS dc",
    "`users_data` AS ud",
    "`subscription_types` AS st"
);
//Default Where parameters
$sqlWhereParameters = array(
    "upg.user_id = ud.user_id",
    "upg.data_type = dc.data_type",
    "upg.data_id = dc.data_id",
    "ud.subscription_id = st.subscription_id",
    "upg.group_status = '1'",
    "ud.active = '2'",
    "st.data_settings LIKE '%".$dc['data_id']."%'"
);

if(!isset($dc['show_expire_post']) || $dc['show_expire_post'] == 0){
    $dateObjectNow  = createDateTimeObject(brilliantDirectories::getWebsiteTimeZone());
    $currentDate    = $dateObjectNow->format('Y-m-d H:i:s');
    $sqlWhereParameters[] = "(group_date >= '".$currentDate."' || group_date IS NULL || group_date=0)";
}

//Default Group By parameters
$sqlGroupByParameters = array();
//Default Having parameters
$sqlHavingParameters = array();
//Default Order By parameters

$sqlOrderByParameters = array(
    "upg.date_updated DESC",
    "upg.group_name DESC"
);



//if there is a data type and data id on the enviroment stated, use them for getting the posts from that data category
if ($dc['data_type'] > 0) {
    $sqlWhereParameters[] = "upg.data_type = '" . $dc['data_type'] . "'";
}
if ($dc['data_id'] > 0) {
    $sqlWhereParameters[] = "upg.data_id = '" . $dc['data_id'] . "'";
}

//custon fields 

if (!empty($_GET['application_markets']) && is_array($_GET['application_markets'])) {

    $markets = array();

    foreach ($_GET['application_markets'] as $v) {
        $v = trim($v);
        if ($v !== '') {
            $markets[] = "'" . mysql_real_escape_string($v) . "'";
        }
    }

    if (!empty($markets)) {
        $sqlWhereParameters[] = "upg.application_markets IN (" . implode(',', $markets) . ")";
    }
}
if (!empty($_GET['manufacturers']) && is_array($_GET['manufacturers'])) {

    $manufacturers = array();

    foreach ($_GET['manufacturers'] as $v) {
        $v = trim($v);
        if ($v !== '') {
            $manufacturers[] = "'" . mysql_real_escape_string($v) . "'";
        }
    }

    if (!empty($manufacturers)) {
        $sqlWhereParameters[] = "upg.manufacturers IN (" . implode(',', $manufacturers) . ")";
    }
}
if (!empty($_GET['has_video']) && $_GET['has_video'] == 1) {
    $sqlWhereParameters[] = "upg.video_link != ''";
}


$searchKeyWordAddonInfo = getAddonInfo('search_members_keywords','6aa2f7c946eaaa69a87a05475f459cbf');

if(isset($searchKeyWordAddonInfo['status']) && $searchKeyWordAddonInfo['status'] == 'success' && $_GET['q'] != ""){
    global $isGroups;
    $isGroups = true;
    widget($searchKeyWordAddonInfo['widget'],"",$w['website_id'],$w);
}else if($_GET['q'] != "") {

    $cleanQ =mysql_real_escape_string(stripslashes(stripslashes($_GET['q'])));
    $queryStringEntitie = htmlentities($cleanQ, ENT_QUOTES, 'UTF-8');
    // check if needed to check for special chars too
    $flagSpecialChars = false;

    if($cleanQ !==htmlspecialchars($queryStringEntitie)){
        $flagSpecialChars = true;
    }
    $sqlSearchQ = '(
        upg.group_name LIKE "%' . $cleanQ . '%" OR
        upg.post_tags LIKE "%' . $cleanQ . '%" OR
        upg.group_desc LIKE "%' . $cleanQ . '%"';
    if ($flagSpecialChars) {
        $sqlSearchQ .= ' OR upg.group_desc LIKE "%' . $queryStringEntitie . '%"';
    }
    $sqlSearchQ .= ' )';
    $sqlWhereParameters[] = $sqlSearchQ;
}


//if there is a user id
if ($user['user_id'] > 0 || (isset($_GET['userid']) && intval($_GET['userid']) > 0) ) {

    if($user['user_id'] > 0){
        $userid = $user['user_id'];
    }else if(isset($_GET['userid']) && intval($_GET['userid']) > 0){
        $userid = intval($_GET['userid']);
    }

    $sqlWhereParameters[] = "upg.user_id = '" . $userid . "'";
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
        $sqlSelectParameters[] = "( " . $metricUnit . " * acos( cos( radians(" . mysql_real_escape_string($_GET['lat']) . ") ) * cos( radians( upg.lat ) ) * cos( radians( upg.lon ) - radians(" . mysql_real_escape_string($_GET['lng']) . ") ) + sin( radians(" . mysql_real_escape_string($_GET['lat']) . ") ) * sin(radians(upg.lat)) ) ) AS distance";
        $sqlHavingParameters[] = "distance <= ".$w['default_radius'] . " OR distance IS NULL";
        $sqlOrderByParameters = array(
            "upg.date_updated ASC",
            "distance ASC",
            "st.search_priority ASC",
            "upg.group_name ASC"
        );
        $profs['location_search_type'] = "radius";
        //if the fsearch parameter is not submited or empty then the system will do the regular search
    } else {

        // all the posible search types provided by google and how they are grouped in 6 main groups for their use in our system and for search settings in our system
        $AddressComponentsArray = array(
            "country" => array(
                "country"
            ),
            "administrative_area_level_1" => array(
                "administrative_area_level_1"
            ),
            "administrative_area_level_2" => array(
                "administrative_area_level_2",
                "administrative_area_level_3",
                "administrative_area_level_4",
                "administrative_area_level_5"
            ),
            "locality" => array(
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
            "postal_code" => array(
                "postal_code"
            ),
            "neighborhood" => array(
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
            if (in_array($_GET['location_type'], $acvalue)) {
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
                $searchMethod   = $w['adm_area_lvl_1_search_method'];
                $isStateSearch  = true;
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
                $sqlWhereParameters[] = " (LOWER(upg.state_sn) = '" . mysql_real_escape_string(strtolower($_GET['adm_lvl_1_sn'])) . "') ";
            }

            if(!empty($_GET['country_sn'])){
                $sqlWhereParameters[] = " (LOWER(upg.country_sn) = '" . mysql_real_escape_string(strtolower($_GET['country_sn'])) . "') ";
            }
        }

        //include the sql where parameters depending on the type of search you are performing
        switch ($searchMethod) {
            case 'bounds':
            default:

                if( ($isStateSearch === true || $isCountrySearch === true) && $w['strict_search_results'] == 0){

                    if($isCountrySearch === true){
                        $sqlWhereParameters[] = " (LOWER(upg.country_sn) = '" . mysql_real_escape_string(strtolower($_GET['country_sn'])) . "' ) ";
                    }else if($isStateSearch === true){
                        $sqlWhereParameters[] = " (LOWER(upg.state_sn) = '" . mysql_real_escape_string(strtolower($_GET['adm_lvl_1_sn'])) . "' ) ";
                        $sqlWhereParameters[] = " (LOWER(upg.country_sn) = '" . mysql_real_escape_string(strtolower($_GET['country_sn'])) . "' ) ";
                    }

                }else if($isStateSearch === false && $isCountrySearch === false){

                    $sqlWhereParameters[] = "upg.lon >= " . mysql_real_escape_string($_GET['swlng']);
                    $sqlWhereParameters[] = "upg.lon <= " . mysql_real_escape_string($_GET['nelng']);
                    $sqlWhereParameters[] = "upg.lat >= " . mysql_real_escape_string($_GET['swlat']);
                    $sqlWhereParameters[] = "upg.lat <= " . mysql_real_escape_string($_GET['nelat']);

                }

                $profs['location_search_type'] = "bounds";
                break;
            case 'radius':
                //perform a radius search
                $sqlSelectParameters[] = "( " . $metricUnit . " * acos( cos( radians(" . mysql_real_escape_string($_GET['lat']) . ") ) * cos( radians( upg.lat ) ) * cos( radians( upg.lon ) - radians(" . mysql_real_escape_string($_GET['lng']) . ") ) + sin( radians(" . mysql_real_escape_string($_GET['lat']) . ") ) * sin(radians(upg.lat)) ) ) AS distance";
                $sqlHavingParameters[] = "distance <= ".$w['default_radius'] . " OR distance IS NULL";
                $sqlOrderByParameters = array(
                    "upg.date_updated ASC",
                    "distance ASC",
                    "upg.group_name ASC"
                );
                $profs['location_search_type'] = "radius";
                break;
        }
    }
    $profs['location'] = $_GET['faddress'];
}// END if $_GET['qlocation'] != ""

if ($_GET['type'][0] != "") {
    $arrayType            = $_GET['type'];
    $myCategory     = array();

    if(is_array($arrayType)){
        foreach($arrayType as $row => $value){
            $myCategory[] = "'".mysql_real_escape_string(trim($value))."'";
        }
    }else if(is_string($arrayType)){
        $myCategory[] = "'".mysql_real_escape_string(trim($arrayType))."'";
    }
	
   if ($_GET['type'][0] != "") {
        $sqlWhereParameters[] = "upg.property_type IN (" . implode(',',$myCategory) . ")";
    }
}

if ($_GET['category'][0] != "") {
    $arrayCategory           = $_GET['category'];
    $myCategory     = array();

    if(is_array($arrayCategory)){
        foreach($arrayCategory as $row => $value){
            $myCategory[] = "'".mysql_real_escape_string(trim($value))."'";
        }
    }else if(is_string($arrayCategory)){
        $myCategory[] = "'".mysql_real_escape_string(trim($arrayCategory))."'";
    }
	
    $myType = rtrim($myType, "|");
   
    if(count($myCategory) && $_GET['category'][0] != ""){
        $sqlWhereParameters[] = "upg.group_category IN (" . implode(',',$myCategory) . ")";
    }
}

if ($_GET['property_type'][0] != "") {
	
	$arrPropertyType            = $_GET['property_type'];
    $myPropertyType     = array();
	
	if(is_array($arrPropertyType)){
       foreach($arrPropertyType as $row => $value){
           $myPropertyType[] = "'".mysql_real_escape_string(trim($value))."'";
       }
    }else if(is_string($arrPropertyType)){
        $myPropertyType[] = "'".mysql_real_escape_string(trim($arrPropertyType))."'";
    }
	
	if ($_GET['property_type'][0] != "") {
        $sqlWhereParameters[] = "upg.property_type IN (" . implode(',',$myPropertyType) . ")";
    }
}
if ($_GET['property_status'] != "") {
    $sqlWhereParameters[] = "upg.property_status = '" . mysql_real_escape_string($_GET['property_status']) . "'";
}
if ($_GET['bedrooms'] != "") {
    $sqlWhereParameters[] = "upg.property_beds  >='" . mysql_real_escape_string($_GET['bedrooms']) . "'";
}
if ($_GET['bathrooms'] != "") {
    $sqlWhereParameters[] = "upg.property_baths  >='" . mysql_real_escape_string($_GET['bathrooms']) . "'";
}
if ($_GET['price'] != "") {
    $myFlag = strpos($_GET['price'], ";");
    if ($myFlag) {
        $price_range = explode(";", $_GET['price']);
        $sqlWhereParameters[] = "upg.property_price between " . mysql_real_escape_string($price_range[0]) . " AND " . mysql_real_escape_string($price_range[1]);
    } else {
        $sqlWhereParameters[] = "upg.property_price ='" . mysql_real_escape_string($_GET['price']) . "'";
    }
}
$sqlGroupByParameters[] = "upg.group_id";

if ($dc['category_order_by'] != "default" && $dc['category_order_by'] != "advanced") {

    switch ($dc['category_order_by']) {
        case 'random':
                session_start();
            if (!isset($_SESSION['randomSeed']) || (!isset($_REQUEST['currentPage']) && (!isset($_GET['page']) || $_GET['page'] < 1))) {
                // Generate a new random seed for the first visit or when the current page is invalid
                $randomSeed = mt_rand(100000000, 999999999);
                $_SESSION['randomSeed'] = $randomSeed;
            } else {
                $randomSeed = $_SESSION['randomSeed'];
            }

            $randomSeed = preg_replace("/[^0-9]/", "", $randomSeed);
            $randomSeed = substr($randomSeed, 0, 9);
            $sqlOrderByParameters = array(
                "RAND($randomSeed)"
            );

            break;
        case 'alphabet-asc':
            $sqlOrderByParameters = array(
                "upg.group_name ASC"
            );
            break;
        case 'alphabet-desc':
            $sqlOrderByParameters = array(
                "upg.group_name DESC"
            );
            break;
        case 'publish-desc':
            $sqlOrderByParameters = array(
                "upg.date_updated DESC"
            );
            break;
        case 'publish-asc':
            $sqlOrderByParameters = array(
                "upg.date_updated ASC"
            );
            break;
        case 'price-asc':
            $sqlOrderByParameters = array(
                "upg.property_price ASC"
            );
            break;
        case 'price-desc':
            $sqlOrderByParameters = array(
                "upg.property_price DESC"
            );
            break;
        case 'group-id-asc':
            $sqlOrderByParameters = array(
                "upg.group_id ASC"
            );
            break;
        case 'group-id-desc':
            $sqlOrderByParameters = array(
                "upg.group_id DESC"
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
    $sqlSelectParameters[] = "IF(REPLACE(upg.sticky_post_expiration_date,'-','') >= '".$currentDate."' OR upg.sticky_post_expiration_date = '' OR upg.sticky_post_expiration_date = '0000-00-00' , upg.sticky_post, 0) AS sticky";
}

if (count($sqlSelectParameters) > 0) {
    $sql .= "SELECT ";
    $sql .= implode(", ", $sqlSelectParameters);
}
if (count($sqlTablesParameters) > 0) {
    $sql .= " FROM ";
    $sql .= implode(", ",$sqlTablesParameters);
}

if (count($sqlWhereParameters) > 0) {
    $sql .= " WHERE ";
    $sql .= implode(" AND ", $sqlWhereParameters);
}
if (count($sqlGroupByParameters) > 0) {
    $sql .= " GROUP BY ";
    $sql .= implode(", ", $sqlGroupByParameters);
}
if (count($sqlHavingParameters) > 0) {
    $sql .= " HAVING ";
    $sql .= implode(" AND ", $sqlHavingParameters);
}
if (count($sqlOrderByParameters) > 0) {
    $sql .= " ORDER BY ";
    $sql .= implode(", ", $sqlOrderByParameters);
}
echo $sql;
$_ENV['sqlquery'] = ob_get_contents();
ob_end_clean();
?>