<?php
if (isset($_GET['q']) && $_GET['q'] != "") {
    $_GET['q'] = mysql_real_escape_string(urldecode($_GET['q']));
}
function apiFormField($vars, $w)
{
    global $DBMain, $_POST, $_GET, $_REQUEST, $_SERVER, $_SESSION, $pars, $post, $dc, $user, $pic, $user, $user_data, $label, $Label, $w, $db_main, $db_website;
    $response['request_string'] = $pars;
    $response['datatype'] = $pars[1];
    $response['dataformat'] = $pars[2];
    $response['requested_method'] = $pars[3];
    $response['datatable'] = $pars[4];
    $response['primary_key'] = $pars[5];
    $response['row_id'] = $pars[6];

    if ($response['requested_method'] == "mysql_main") {
        $field_database = "findades_main";

    } else {
        $field_database = $w['database'];
    }
    $sqlquery = "SELECT * FROM `form_fields` WHERE `$response[primary_key]`='$response[row_id]'";
    $apiresults = mysql($field_database, $sqlquery);
    $response['mysql_error'] = mysql_error();

    while ($fm = mysql_fetch_assoc($apiresults)) {
        $f = $fm;
        $f['field_options'] = json_decode($f['field_options'], true);

        if (is_array($f['field_options'])) {
            $io = 0;
            $f['field_options_array'] = array();

            foreach ($f['field_options'] as $key => $value) {

                if (stristr($f['field_options'][$io]['option_type'], "mysql") && substr(strtoupper($f['field_options'][$io]['option_value']), 0, 6) == "SELECT") {

                    if ($f['field_options'][$io]['option_label'] != "") {
                        $f['field_options'][$io]['mysql_fields'] = explode(",", $f['field_options'][$io]['option_label']);
                    }
                    if ($f['field_options'][$io]['option_type'] == "mysql_main") {
                        $database = $DBMain;

                    } else {
                        $database = $w['database'];
                    }
                    $f['field_options_array'] = getDatatoArray($database, $f['field_options'][$io]['option_value'], $f['field_options'][$io]['mysql_fields'][0], $f['field_options'][$io]['mysql_fields'][1], $w);

                } else {
                    $option_value = $f['field_options'][$io]['option_value'];
                    $f['field_options_array'][$option_value] = $f['field_options'][$io]['option_label'];
                }
                $io++;
            }
            $final['field_options']['total_count'] = count(array_keys($f['field_options_array']));
            $final['field_options']['incomplete_results'] = false;

            if ($final['field_options']['total_count'] > 0) {

                foreach ($f['field_options_array'] as $id => $text) {
                    $final['field_options']['items'][] = array("id" => $id, "text" => $text);
                }
            }
        }
        $return = $f;
    }
    if ($response['datatype'] == "field_options") {
        $return = $final['field_options'];
    }
    return $return;
}

function apiRemote($vars, $w)
{
    global $_POST, $_GET, $_REQUEST, $_SERVER, $_SESSION, $pars, $post, $dc, $user, $pic, $user, $user_data, $label, $Label, $w;
    $response['request_string'] = $pars;
    $response['datatype'] = $pars[1];
    $response['dataformat'] = $pars[2];
    $response['requested_method'] = $pars[3];
    $response['datatable'] = $pars[4];
    $response['primary_key'] = $pars[5];
    $sqlquery = "SELECT * FROM `$response[datatable]` WHERE `$response[primary_key]` LIKE '%$_GET[q]%'";

    if ($_GET['devmode'] == 1) {
        echo $sqlquery;
    }
    $apiresults = mysql($w['database'], $sqlquery);
    $response['mysql_error'] = mysql_error();
    $suggests = array();
    $i = 0;

    if ($response['mysql_error'] == "") {

        while ($post = mysql_fetch_assoc($apiresults)) {
            $posts['avatar'] = "http://placehold.it/50/55C1E7/fff&text=Widget";
            $posts['name'] = $post['widget_name'];
            $posts['value'] = $post['widget_name'];
            $posts['location'] = $response['primary_key'];
            $suggests[] = $posts;
            $i++;
        }
        $response['status'] = "success";

    } else if ($response['mysql_error'] != "") {
        $response['status'] = "error";
        $response['notification'] = "Your requested action $_SERVER[REQUEST_URI] returned an error:<ul>$sqlquery</ul>" . $response['mysql_error'];
    }
    return $suggests;
}

function apiStats($vars, $w)
{
    global $_POST, $_GET, $_REQUEST, $_SERVER, $_SESSION, $pars, $post, $dc, $user, $pic, $user, $user_data, $label, $Label, $w;

    $userInfo = bd_controller::user()->get($_REQUEST['user_id'],'user_id');

    $userInfoMetaWhere = array(
        array('value' => 'users_data' , 'column' => 'database', 'logic' => '='),
        array('value' => $_REQUEST['user_id'] , 'column' => 'database_id', 'logic' => '=')
    );
    $userInfoMeta = bd_controller::users_meta()->get($userInfoMetaWhere);
    $userInfoMeta = (!is_array($userInfoMeta) && $userInfoMeta !== false)?array($userInfoMeta):$userInfoMeta;

    $membershipInfo = bd_controller::subscription_types()->get($userInfo->subscription_id, 'subscription_id');
    bd_controller::subscription_types(WEBSITE_DB)->loadProperties((array)$membershipInfo);
    $membershipInfo = (array)clone bd_controller::subscription_types(WEBSITE_DB);

    foreach($userInfoMeta as $userMeta){
        $userMetaKey = $userMeta->key;
        $userInfo->$userMetaKey = $userMeta->value;
    }

    if($userInfo->active != 2 || $membershipInfo['profile_statistics'] == 0){
        //User is not active or membership setting for stats is disabled, returning error
        return 'User not active or setting disabled';
    } else {
        // Validating the url
        if ($_REQUEST['click_url'] != '') {
            $dataToLookUpFlag = false;
            if (strpos($_REQUEST['click_url'], '://') !== false) {
                $dataToLookUp = explode('://', $_REQUEST['click_url']);
                if (!empty($dataToLookUp[1]) && in_array($dataToLookUp[1], (array)$userInfo) || in_array(urlencode($dataToLookUp[1]), (array)$userInfo)) {
                    $dataToLookUpFlag = true;
                }
            }
			
				$dataToLookUpFlag = true;
			
            if (!in_array($_REQUEST['click_url'], (array)$userInfo) && !in_array(urlencode($_REQUEST['click_url']), (array)$userInfo) && !$dataToLookUpFlag) {
                return 'Url passed not valid';
            } else {
                foreach($userInfo as $key => $value){
                    if($value == $_REQUEST['click_url']){
                        $_REQUEST['click_type'] = ucwords($key);
                    }
                }
            }
        } else {
            $validClickTypes = array('facebook','twitter','linkedin','googleplus','youtube','instagram','pinterest','blog','profile','phone number','contact form', 'booking link');
            $validClickNames = array('statistics_view_phone', 'statistics_profile_views','Contact Form');

            $validClickTypeCheck = false;
            $validClickNameCheck = false;

            foreach ($validClickTypes as $value) {
                if(strtolower($_REQUEST['click_type']) == $value){
                    $validClickTypeCheck = true;
                    break;
                }
            }
            foreach ($validClickNames as $value) {
                if($value == 'Contact Form'){
                    if($_REQUEST['click_name'] == $value){
                        $validClickNameCheck = true;
                        break;
                    }
                } else {
                    if($_REQUEST['click_name'] == $label[$value]){
                        $validClickNameCheck = true;
                        break;
                    }
                }

            }
            if(!$validClickTypeCheck && !$validClickNameCheck){
                return 'Not Valid User Click';
            }
        }
    }
    $us = getUser($_REQUEST['user_id'], $w);
    if($us['token'] == $_COOKIE['token']){
        return false;
    }
    if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])){
        return false;
    }
    $_REQUEST['user_id'] = $us['user_id'];
    $response['request_string'] = $pars;
    $response['datatype'] = $pars[1];
    $response['dataformat'] = $pars[2];
    $response['requested_method'] = $pars[3];
    $response['datatable'] = $pars[4];
    $response['requested_action'] = $pars[5];

    if ($_REQUEST['user_id'] > 0) {
        $_REQUEST['click_ip'] = $_SERVER['REMOTE_ADDR'];
        $_REQUEST['click_from'] = $_SERVER['HTTP_REFERER'];
        $_REQUEST['click_token'] = hmac($pars[6], date("YmdHis"));
        $_REQUEST['visitor_token'] = $_COOKIE['vtoken'];
        $_REQUEST['date_clicked'] = date("YmdHis");
        $now = date("Ymd");
        $statsCheckQuery = "SELECT
                `user_id`
            FROM
                `users_clicks`
            WHERE
                `user_id` = '" . $_REQUEST['user_id'] . "'
            AND
                `user_id` > 0
            AND
                `click_token` = '" . $_COOKIE['vtoken'] . "'
            AND
                `date_clicked` < '" . $now . "'
            AND 
                `post_id` = ''";
        $sresults = mysql($w['database'], $statsCheckQuery);
        if (mysql_num_rows($sresults) == 0) {
            $columns = getColumns($response['datatable'], $w);

            foreach ($_REQUEST as $key => $value) {
                if($key == 'click_name'){

                    //we now check if we are in devmode
                    if(strpos($value, '<span class=text-label>') !== false){
                        $value = bdString::getStringBetweenTwoCharacters($value,'<span class=text-label>', '<span');
                    }

                    $value = strip_tags($value);
                    $value = preg_replace('/[^a-zA-Z ]+/', "", $value);
                }

                if (in_array($key, $columns) && !is_array($value)) {
                    $sql .= "`$key`='" . mysql_real_escape_string($value) . "', ";

                } else {
                    $array[$key] = $value;
                }
            }
            if (strstr($sql, ',')) {
                $sql = rtrim($sql, ", ");
            }
            if ($sql != "") {
                $sqlquery = "INSERT INTO `$response[datatable]` SET $sql";
                mysql($w['database'], $sqlquery);
                $response['insert_id'] = mysql_insert_id();
                $response['mysql_error'] = mysql_error() . "<u>$sqlquery</ul>";
            }
        }
    }
    if ($response['insert_id'] > 0) {
        $response['status'] = "success";
        $_ENV['alert_type'] = "success";
        $_ENV['alert_notification'] = "Your Status is Updated!";

    } else {
        $response['status'] = "error";
        $_ENV['alert_type'] = "warning";
        $_ENV['alert_notification'] = "Warning! Double check yourself and try submitting again.";
    }
    $response['notification'] = widget('bootstrap alerts', '', $w['website_id'], $w);
    return $response;
}

function apiPost($vars, $w)
{
    global $_POST, $_GET, $_REQUEST, $_SERVER, $_SESSION, $pars, $post, $dc, $user, $pic, $user, $user_data, $label, $Label, $w;
    $response['request_string'] = $pars;
    $response['datatype'] = $pars[1];
    $response['dataformat'] = $pars[2];
    $response['requested_method'] = $pars[3];
    $response['datatable'] = $pars[4];
    $response['requested_action'] = $pars[5];
    $_REQUEST['data_id'] = (int)$_REQUEST['data_id'];

    if ($_REQUEST['data_id'] > 0) {
        $_REQUEST['post_live_date'] = $w['date'];
        $_REQUEST['post_date'] = $w['date'];
        $_REQUEST['post_status'] = 1;
        $dcresults = mysql($w['database'], "SELECT
                *
            FROM
                `data_categories`
            WHERE
                `data_id` = '" . $_REQUEST['data_id'] . "'
            LIMIT
                1");
        $dc = mysql_fetch_assoc($dcresults);
        $_REQUEST['data_type'] = $dc['data_type'];
    }
    if ($response['datatable'] != "") {
        $_REQUEST['date_added'] = $w['date'];
        $columns = getColumns($response['datatable'], $w);

        foreach ($_REQUEST as $key => $value) {

            if (in_array($key, $columns) && !is_array($value)) {
                $sql .= "`$key`='" . mysql_real_escape_string($value) . "', ";

            } else {
                $array[$key] = $value;
            }
        }
        if (strstr($sql, ',')) {
            $sql = rtrim($sql, ", ");
        }
        if ($sql != "") {
            $sqlquery = "INSERT INTO `$response[datatable]` SET $sql";
            mysql($w['database'], $sqlquery);
            $response['insert_id'] = mysql_insert_id();
            $response['mysql_error'] = mysql_error() . "<u>$sqlquery</ul>";
        }
        if (is_array($array) && $response['insert_id'] > 0) {
            storeMetaData($response['datatable'], $response['insert_id'], $array, $w);
        }
    }
    if ($response['insert_id'] > 0) {
        $response['status'] = "success";
        $_ENV['alert_type'] = "success";
        $_ENV['alert_notification'] = "Your Status is Updated!";

    } else {
        $response['status'] = "error";
        $_ENV['alert_type'] = "warning";
        $_ENV['alert_notification'] = "Warning! Double check yourself and try submitting again.";
    }
    $response['notification'] = widget('bootstrap alerts', '', $w['website_id'], $w);
    return $response;
}

function apiGet($vars, $w)
{
    global $_POST, $_GET, $_REQUEST, $_SERVER, $_SESSION, $pars, $post, $dc, $user, $pic, $user, $user_data, $label, $Label, $w;

    foreach ($pars as $key => $value) {
        $pars[$key] = urldecode($pars[$key]);
    }
    $response['request_string'] = $pars;
    $response['datatype'] = $pars[1];
    $response['dataformat'] = $pars[2];
    $response['requested_method'] = $pars[3];
    $response['datatable'] = $pars[4];
    $response['primary_key'] = $pars[5];

    if (is_int($pars[6]) || $pars[6] > 0) {
        $pars[6] = (int)$pars['6'];
    }
    $response['row_id'] = $pars[6];
    $response['sort'] = $pars[7];

    if ($response['requested_method'] == "validate" || $response['requested_method'] == "validatem") {

        if ($response['requested_method'] == "validatem") {
            $response['requested_method'] = "validate";
            $w['database'] = $w['DBMain'];
            $response['mysql_database'] = $w['database'];
            $columns = getColumns($response['datatable'], $w, $w['DBMain']);

        } else {
            $columns = getColumns($response['datatable'], $w);
        }
        foreach ($_GET as $vk => $vf) {

            if (in_array($vk, $columns)) {
                $extrastatement .= " " . $vk . "='" . $vf . "' AND ";
            }
        }
        if ($_COOKIE['claim_listing'] != "" && $_COOKIE['claim_listing'] != "hide" && $_GET['email'] != "" && $response['datatable'] == "users_data" && $response['primary_key'] == "email") {
            $extrastatement .= " " . "`token` != '" . $_COOKIE['claim_listing'] . "' AND ";
        }

        if ($pars[5] != "" && $pars[6] == "") {
            $pars[6] = $_REQUEST[$pars[5]];
            $response['row_id'] = $pars[6];
        }
    }
    if ($response['datatable'] == "data_widgets" && $response['requested_method'] != "validate") {

        if ($pars[5] == "widget_name") {
            $_GET['name'] = urldecode($_GET['name']);
            $response['widget'] = widget($_GET['name'], "", $w['website_id'], $w);

        } else {
            $apiresults = mysql($w['database'], "SELECT
                    *
                FROM
                    `$response[datatable]`
                WHERE
                    `widget_id` = '" . $pars[6] . "' OR `widget_name` = '" . $pars[6] . "'
                ORDER BY
                    widget_id DESC
                LIMIT
                    1");
            $response['mysql_error'] = mysql_error();

            if ($response['mysql_error'] == "") {

                if (mysql_num_rows($apiresults) < 1) {
                    $apiresults = mysql($w['database'], "SELECT
                            *
                        FROM
                            `" . $response['datatable'] . "`
                        WHERE
                            `widget_id` = '" . $pars[6] . "' OR `widget_name` = '" . $pars[6] . "'
                        ORDER BY
                            widget_id DESC
                        LIMIT
                            1");
                }
                while ($post = mysql_fetch_assoc($apiresults)) {
                    $response['widget'] = widget($post['widget_name'], "", $w['website_id'], $w);
                }
            }
        }

    } else {

        if ($response['sort'] == "recent") {
            $response['sort'] = ">";

        } else if ($response['sort'] == "") {
            $response['sort'] = "=";
        }
        if ($response['primary_key'] != "") {
            $columns = getColumns($response['datatable'], $w);
            $operands = array("=", "LIKE", "NOT LIKE", ">", ">=", "<", "<=", "!=");
            if (in_array($response['primary_key'], $columns) && in_array($response['sort'], $operands)) {
                $where = "WHERE " . $extrastatement . " " . $response['primary_key'] . "!='' AND " . $response['primary_key'] . $response['sort'] . "'" . $response['row_id'] . "'";
            } else {
                $where = "WHERE";
            }
        }
        if ($response['requested_method'] == "feed") {
            $where .= "ORDER BY post_live_date ASC";
        }
        // custom check to make sure sub accounts emails dont conflict with each other
        if($response['datatable'] == 'users_data' && $response['primary_key'] == 'user_id' && $response['requested_method'] == 'validate' && isset($emailCheck) && in_array('parent_id', $columns)){
            $userData = bd_controller::user()->get($response['row_id'], 'user_id');
            $parentID = $userData->parent_id;
            $where = "WHERE email ='".$emailCheck."' AND user_id != '".$response['row_id']."'AND parent_id != '".$response['row_id']."' AND user_id != '".$parentID."' AND parent_id != '".$parentID."'";
        }
        $sqlquery = "SELECT * FROM `" . $response['datatable'] . "` " . $where;
        $apiresults = mysql($w['database'], $sqlquery);
        $response['total_sqlresults'] = mysql_num_rows($apiresults);
        $response['mysql_error'] = mysql_error();
        $response['mysql_query'] = $sqlquery;

        if ($response['mysql_error'] == "") {

            if ($response['requested_method'] == "validate") {

                if ($response['total_sqlresults'] > 0) {
                    $response['valid'] = false;

                } else {
                    $response['valid'] = true;
                }

            } else if ($response['requested_method'] == "feed") {

                while ($post = mysql_fetch_assoc($apiresults)) {
                    $response['widget'] .= widget("Individual Member Feature - Feed", "", $w['website_id'], $w);
                }
            }
            $response['status'] = "success";

        } else {
            $response['status'] = "error";
            $response['notification'] = "Your requested action " . $_SERVER['REQUEST_URI'] . " returned an error:<ul>" . $sqlquery . "</ul>" . $response['mysql_error'];
        }
    }
    if ($_REQUEST['devmode'] < 1) {
        unset($response['mysql_error']);
        unset($response['mysql_query']);
        unset($response['notification']);
    }
    return $response;
}

function supportForVisibilityOptions()
{
    global $COOKIE, $w;
    $membershipAdvOptQuery = mysql($w['database'], "SHOW COLUMNS FROM
        `subscription_types`
    LIKE
        'search_membership_permissions'");
    $sqlSupportVisibilityOptions = "";

    if (mysql_num_rows($membershipAdvOptQuery) > 0) {


        $membersOnlySearchVisibilityAddOn = getAddOnInfo('members_only', 'a12e81906e726b11a95ed205c0c1ed36');


        if (isset($membersOnlySearchVisibilityAddOn['status']) && $membersOnlySearchVisibilityAddOn['status'] === 'success') {

            echo widget($membersOnlySearchVisibilityAddOn['widget'], "", $w['website_id'], $w);

            if ($_ENV['whereValueSearchOption'] != "") {
                $sqlSupportVisibilityOptions = "AND " . $_ENV['whereValueSearchOption'];
            }


        } else {

            $sqlSupportVisibilityOptions = "AND (st.search_membership_permissions REGEXP 'visitor' OR st.search_membership_permissions = '') ";
        }

    }

    return $sqlSupportVisibilityOptions;
}

function formatSelects($vars, $table, $requireCompleteProfile = false,$isEventsAddonActive = false,$dataCategoriesWhere = array())
{
    global $dc,$w;

    $now = date("YmdHis");
    switch ($table) {
        case 'users_data':
        case 'users_reviews':
            $visibilityOptions = supportForVisibilityOptions();

            if ($vars['comesf'] == 1) {
                $comesfParam = "CASE WHEN ud.listing_type = 'Company' THEN CONCAT(ud.first_name,' ',ud.last_name) ELSE ud.company END AS comes_f";

            } else {
                $comesfParam = "'novalue' AS comes_f";
            }
            if ($vars['location'] == 1) {
                $locationParam = "CONCAT(ud.city,', ',ud.state_code) AS location";

            } else {
                $locationParam = "'novalue' AS location";
            }
            if ($vars['link'] == 1) {
                $linkParam = "CONCAT('/',ud.filename) AS link";

            } else {
                $linkParam = "'novalue' AS link";
            }
            if ($vars['limit'] == 0 || $vars['limit'] == "") {
                $limitParam = "3";

            } else {
                $limitParam = $vars['limit'];
            }
            $finalSql = "SELECT DISTINCT
                CASE
                    WHEN ud.listing_type = 'Individual'
                    THEN
                        CASE
                            WHEN CONCAT(ud.first_name,' ',ud.last_name) = ''
                            THEN
                              ud.company
                            ELSE
                                CONCAT(ud.first_name,' ',ud.last_name)
                        END
                    ELSE
                        CASE
                            WHEN ud.company = ''
                            THEN
                                CONCAT(ud.first_name,' ',ud.last_name)
                            ELSE
                                ud.company
                        END
                END AS value,
                " . $comesfParam . ",
                " . $locationParam . ",
                " . $linkParam . ",
                ud.user_id,
                ud.listing_type";

            $userReviewExtraSql         = '';
            $userReviewExtraSqlWhere    = '';
            $userReviewExtraSqlOrder    = '';
            $whereString                = "";
            $usersMetaQuery             = "";
            $categoriesWhere            = "";
            $categoriesResults          = array();

            if ($table == 'users_reviews') {
                $userReviewExtraSql         = ' RIGHT JOIN users_reviews AS ur ON ud.user_id = ur.user_id  ';
                $userReviewExtraSqlWhere    = ' AND ur.review_status = 2 ';
                $userReviewExtraSqlOrder    = ' ,review_added DESC  ';
                $finalSql .= " FROM `users_data` AS ud INNER JOIN `subscription_types` AS st ON ud.subscription_id = st.subscription_id ".$usersMetaQuery." " . $userReviewExtraSql;
                $finalSql .= " WHERE ud.active = '2' " . $userReviewExtraSqlWhere;
                $finalSql .= " 
                    AND
                        st.searchable = '1'
                        " . $visibilityOptions . "
                    AND
                        ( 
                            ".$whereString."
                        )
                    ".$categoriesWhere."
                    GROUP BY
                        ud.user_id
                    ORDER BY
                        st.search_priority ASC,
                        value ASC". $userReviewExtraSqlOrder ."
                    LIMIT
                        " . $limitParam;

            }else{
                $dc = getDataCategory($vars['data_id'],"data_id",$w);
                widget("Search - Member - Search Query","",$w['website_id'],$w);
                $queryExploded = explode('FROM `users_data`',$_ENV['sqlquery']);

                if(strpos($queryExploded[1], "reviews_number") !== false){
                    $finalSql .= ", (SELECT count(*) AS reviews_number FROM `users_reviews` AS ur WHERE ud.user_id = ur.user_id AND ur.review_status = 2 ) AS reviews_number";
                }

                $finalSql .= str_replace("name ASC","value ASC"," FROM `users_data`".$queryExploded[1]);
                $finalSql .= " LIMIT ".$limitParam;
            }

            return $finalSql;
            break;

        case 'data_posts':

            $result = bd_controller::data_categories(WEBSITE_DB)->get($vars['data_id'],'data_id');

            if(
                (
                    bd_controller::data_categories(WEBSITE_DB)->form_name == "event_fields"
                    ||
                    bd_controller::data_categories(WEBSITE_DB)->is_event_feature == 1
                )
                &&
                (
                    bd_controller::data_categories(WEBSITE_DB)->enable_recurring_events_addon == 1
                    ||
                    !isset(bd_controller::data_categories(WEBSITE_DB)->enable_recurring_events_addon)
                )
            ){

                if($isEventsAddonActive){
                    $whereDate = "( ( dp.recurring_type <= 0 && dp.post_expire_date >= '" . $now . "') || ( dp.recurring_type > 0 && (dp.post_expire_date >= '" . $now . "' OR dp.post_expire_date= '' ) ) )";
                }else{
                    $whereDate = "(dp.post_expire_date >= '" . $now . "')";
                }
            }else{
                $whereDate = "(dp.post_expire_date >= '" . $now . "' OR dp.post_expire_date= '' )";
            }
            if ($vars['comesf'] == 1) {
                $comesfParam = "dc.data_name AS comes_f";

            } else {
                $comesfParam = "'novalue' AS comes_f";
            }
            if ($vars['location'] == 1) {
                $locationParam = "post_location AS location";

            } else {
                $locationParam = "'novalue' AS location";
            }
            if ($vars['link'] == 1) {
                $linkParam = "dp.post_filename AS link";
            } else {
                $linkParam = "'novalue' AS link";
            }
            if ($vars['photo'] == 1) {
                $photoParam = "CASE WHEN dp.post_image = '' THEN 'novalue' ELSE dp.post_image END AS photo";

            } else {
                $photoParam = "'novalue' AS photo";
            }
            if ($vars['limit'] == 0 || $vars['limit'] == "") {
                $limitParam = "3";

            } else {
                $limitParam = $vars['limit'];
            }

            global $sqlWhereParameters,$isGroups,$dc,$searchLevel;

            if(isset($dataCategoriesWhere['data_id_'.$vars['data_id']])){
                $whereString = $dataCategoriesWhere['data_id_'.$vars['data_id']];
            }else{
                $whereString = "(dp.post_title LIKE '%".$_GET[q]."%' OR dp.post_tags LIKE '%".$_GET[q]."%')";
            }

            $finalSql = "SELECT DISTINCT
                    CONCAT(dp.post_title,' -/- data_posts',dp.post_id) AS value,
                    CONCAT(dp.post_title,' -/- data_posts',dp.post_id) AS index_column,
                    " . $comesfParam . ",
                    " . $locationParam . ",
                    " . $linkParam . ",
                    " . $photoParam . "
                FROM
                    `data_posts` AS dp
                INNER JOIN 
                    `data_categories` AS dc
                ON
                    dp.data_id  = dc.data_id AND dp.data_type = dc.data_type
                INNER JOIN
                    `users_data` AS ud 
                ON
                    dp.user_id = ud.user_id
                INNER JOIN
                    `subscription_types` AS st
                ON
                    ud.subscription_id = st.subscription_id
                LEFT JOIN ( SELECT um.value ,um.database_id,um.database FROM `users_meta` as um) as um ON dp.post_id = um.database_id AND um.database = 'data_posts'
                WHERE
                    dp.data_id  = dc.data_id
                AND
                    dp.data_type = dc.data_type
                AND
                    dp.user_id = ud.user_id
                AND
                    ud.subscription_id = st.subscription_id
                AND
                    dp.data_id = '" . $vars['data_id'] . "'
                AND
                    ".$whereString."
                AND
                    ".$whereDate."
                AND
                    dp.post_status = 1
                AND
                    ud.active = 2
                GROUP BY
                    dp.post_id
                ORDER BY
                    st.search_priority ASC,
                    dp.post_title ASC
                LIMIT
                    " . $limitParam;
            return $finalSql;
            break;

        case 'list_professions':

            if ($vars['comesf'] == 1) {
                $comesfParam = "' ' AS comes_f";

            } else {
                $comesfParam = "'novalue' AS comes_f";
            }
            if ($vars['location'] == 1) {
                $locationParam = "' ' AS location";
            } else {
                $locationParam = "'novalue' AS location";
            }
            if ($vars['link'] == 1) {
                $linkParam = "CONCAT('/search_results?sid=',lp.profession_id) AS link";
            } else {
                $linkParam = "'novalue' AS link";
            }
            if ($vars['photo'] == 1) {
                $photoParam = "image AS photo";

            } else {
                $photoParam = "'novalue' AS photo";
            }
            if ($vars['limit'] == 0 || $vars['limit'] == "") {
                $limitParam = "3";

            } else {
                $limitParam = $vars['limit'];
            }
            $finalSql = "SELECT DISTINCT
                    CONCAT(lp.name,' -/- list_professions',lp.profession_id) AS value,
                    CONCAT(lp.name,' -/- list_professions',lp.profession_id) AS index_column,
                    " . $comesfParam . ",
                    " . $locationParam . ",
                    " . $linkParam . ",
                    " . $photoParam . "
                FROM
                    `list_professions` AS lp
                WHERE
                    (lp.name LIKE '%$_GET[q]%' OR lp.keywords LIKE '%$_GET[q]%' OR lp.filename LIKE '%$_GET[q]%')
                ORDER BY
                    lp.name ASC
                LIMIT
                    " . $limitParam;
            return $finalSql;
            break;

        case 'list_services':

            if ($vars['comesf'] == 1) {
                $comesfParam = "CASE WHEN ls.master_id > '0' THEN (SELECT name FROM `list_services` WHERE ls.master_id = service_id) ELSE lp.name END AS comes_f";

            } else {
                $comesfParam = "'novalue' AS comes_f";
            }
            if ($vars['location'] == 1) {
                $locationParam = "' ' AS location";
            } else {
                $locationParam = "'novalue' AS location";
            }
            if ($vars['link'] == 1) {
                $linkParam = "CASE WHEN ls.master_id = 0 THEN CONCAT('/search_results?tid=',ls.service_id) ELSE CONCAT('/search_results?ttid=',ls.service_id) END AS link";
            } else {
                $linkParam = "'novalue' AS link";
            }
            if ($vars['photo'] == 1) {
                $photoParam = "(SELECT value FROM `users_meta` AS um WHERE um.database_id = ls.service_id AND um.key = 'image' ) AS photo";

            } else {
                $photoParam = "'novalue' AS photo";
            }
            if ($vars['limit'] == 0 || $vars['limit'] == "") {
                $limitParam = "3";

            } else {
                $limitParam = $vars['limit'];
            }
            $finalSql = "SELECT  DISTINCT
                    CONCAT(ls.name,' -/- list_services',ls.service_id) AS value,
                    CONCAT(ls.name,' -/- list_services',ls.service_id) AS index_column,
                    " . $comesfParam . ",
                    " . $locationParam . ",
                    " . $linkParam . ",
                    " . $photoParam . "
                FROM
                    `list_services` AS ls ,
                    `list_professions` AS lp
                WHERE
                    ls.profession_id = lp.profession_id
                AND
                    (ls.name LIKE '%$_GET[q]%' OR ls.filename LIKE '%$_GET[q]%' OR ls.keywords LIKE '%$_GET[q]%')
                GROUP BY
                    ls.service_id
                ORDER BY
                    ls.name ASC
                LIMIT
                    " . $limitParam;
            return $finalSql;
            break;
        case 'users_portfolio_groups':

            if ($vars['comesf'] == 1) {
                $comesfParam = "dc.data_name AS comes_f";

            } else {
                $comesfParam = "'novalue' AS comes_f";
            }
            if ($vars['location'] == 1) {
                $locationParam = "post_location AS location";
            } else {
                $locationParam = "'novalue' AS location";
            }
            if ($vars['link'] == 1) {
                $linkParam = "upg.group_filename AS link";
            } else {
                $linkParam = "'novalue' AS link";
            }
            if ($vars['photo'] == 1) {
                $photoParam = "CASE WHEN (SELECT CONCAT('/photos/main/',file) FROM `users_portfolio` WHERE group_id = upg.group_id AND data_id = upg.data_id AND user_id = upg.user_id ORDER BY group_cover DESC LIMIT 1) IS NULL THEN '/images/profile-profile-holder.png' ELSE (SELECT CONCAT('/photos/main/',file) FROM `users_portfolio` WHERE group_id = upg.group_id ORDER BY group_cover DESC LIMIT 1) END AS photo";

            } else {
                $photoParam = "'novalue' AS photo";
            }
            if ($vars['limit'] == 0 || $vars['limit'] == "") {
                $limitParam = "3";

            } else {
                $limitParam = $vars['limit'];
            }

            if(isset($dataCategoriesWhere['data_id_'.$vars['data_id']])){
                $whereString = $dataCategoriesWhere['data_id_'.$vars['data_id']];
            }else{
                $whereString = "upg.group_name LIKE '%".$_GET[q]."%'";
            }

            $finalSql = "SELECT DISTINCT
                    CONCAT(upg.group_name,' -/- users_portfolio_groups',upg.group_id) AS value,
                    CONCAT(upg.group_name,' -/- users_portfolio_groups',upg.group_id) AS index_column,
                    " . $comesfParam . ",
                    " . $locationParam . ",
                    " . $linkParam . ",
                    " . $photoParam . "
                FROM
                    `users_portfolio_groups` AS upg
                INNER JOIN
                    `users_data` AS ud
                ON
                    upg.user_id = ud.user_id
                INNER JOIN
                    `data_categories` AS dc
                ON
                    upg.data_type = dc.data_type
                AND
                    upg.data_id = dc.data_id
                INNER JOIN
                    `subscription_types` AS st
                ON
                    ud.subscription_id = st.subscription_id
                LEFT JOIN 
                    ( SELECT um.value ,um.database_id,um.database FROM `users_meta` as um) as um ON upg.group_id = um.database_id AND um.database = 'users_portfolio_groups'
                WHERE
                    upg.data_id = '" . $vars['data_id'] . "'
                AND
                    ".$whereString."
                AND
                    upg.group_status = 1
                AND
                    ud.active = 2
                GROUP BY
                    upg.group_id
                ORDER BY
                    st.search_priority ASC,
                    upg.group_name ASC
                LIMIT
                    " . $limitParam;
            return $finalSql;
            break;
    }//END switch ($table)
}

function apiSuggest($vars, $w)
{
    global $_POST, $_GET, $_REQUEST, $_SERVER, $_SESSION, $pars, $post, $dc, $user, $pic, $user, $user_data, $label, $Label, $w , $_ENV;
    $now = date('Ymd');
    $pars[4] = urldecode($pars[4]);
    $requestingTables = explode("|", $pars[4]);
    $qExplode = explode(" ", $_GET['q']);

    $isEventsAddonActive = addonController::isAddonActive('recurring_events');

    if(addonController::isAddonActive('search_members_keywords') === true){
        $singleDataCategoryWhere    = dataCategoryController::getDataCategoryAutoSuggestWhere();
        $groupDataCategoryWhere     = dataCategoryController::getDataCategoryAutoSuggestWhere(true);
        $dataCategoriesWhere        = array_merge($singleDataCategoryWhere['dataCategories'],$groupDataCategoryWhere['dataCategories']);
        $addonMemberKeywords        = getAddOnInfo("search_members_keywords","6a2f4bd232e526ac53144b56da479f03");
        $_ENV['searchQuery']        = $_GET['q'];
        widget($addonMemberKeywords['widget'],"",$w['website_id'],$w);
        $dataCategoriesWhere['user_where'] = $_ENV['whereValue'];
    }else{
        $dataCategoriesWhere    = array();
    }

    foreach ($requestingTables as $rtvalue) {
        $moduleArray = array();
        $modParams = explode(",", $rtvalue);
        $moduleArray['data_id'] = $modParams[0];
        $moduleArray['table'] = $modParams[1];
        $moduleArray['title'] = $modParams[2];
        $moduleArray['header'] = $modParams[3];
        $moduleArray['photo'] = $modParams[4];
        $moduleArray['location'] = $modParams[5];
        $moduleArray['link'] = $modParams[6];
        $moduleArray['comesf'] = $modParams[7];
        $moduleArray['limit'] = $modParams[8];
        $moduleSql = formatSelects($moduleArray, $moduleArray['table'],$w['search_results_require_complete_profile'],$isEventsAddonActive,$dataCategoriesWhere);

        $processQuery = mysql($w['database'], $moduleSql);

        if (mysql_num_rows($processQuery) > 0) {

            //insert the header if the module has set it to 1
            if ($moduleArray['header'] == 1) {
                $headerModule = array(
                    "value" => $moduleArray['title'],
                    "comes_f" => "heading",
                    "location" => "novalue",
                    "link" => "novalue",
                    "photo" => "novalue");
                $suggests[] = $headerModule;
            }
            while ($result = mysql_fetch_assoc($processQuery)) {

                if (($moduleArray['table'] == 'users_data' || $moduleArray['table'] == 'users_reviews') && $moduleArray['photo'] == 1) {

                    if ($result['listing_type'] == "Individual") {
                        $pType = "photo";

                    } else {
                        $pType = "logo";
                    }
                    $cuPhoto = getProfilePhotoURL($result['user_id'], $pType, $w);
                    $result['photo'] = $cuPhoto['file'];
                    unset($result['user_id']);
                    unset($result['listing_type']);
                }
                foreach ($result as $key => $value) {
                    $result[$key] = stripslashes(htmlspecialchars($value, ENT_QUOTES));

                    if ($moduleArray['table'] != 'users_data' && $key == "link" && $moduleArray['table'] != 'list_professions' && $moduleArray['table'] != 'list_services' && $value != 'novalue') {
                        $result[$key] = "/" . $value;
                    }
                    // Grabbing the thumbnail of single posts images
                    if (strpos($value, '/news-pictures/') !== false) {
                        $value = str_replace('/news-pictures/', '/news-pictures-thumbnails/', $value);
                        $result[$key] = $value;
                    }
                    // Grabbing the thumbnail of group post main image
                    if (strpos($value, '/main/') !== false) {
                        $value = str_replace('/main/', '/display/', $value);
                        $result[$key] = $value;
                    }
                }
                $suggests[] = $result;
            }
            //print_r($suggests);
        }
    }
    return $suggests;
}

function deleteAccount($userId, $w)
{
    $user           = getUser($userId, $w);
    $subscription   = getSubscription($user['subscription_id'],$w);
    $userModel = new user();
    $userModel->getUserById($userId);
    if ($user['clientid']) {
        $response['status'] = user::closeAccount($userId);
    } else {
        $userModel->updateUserStatus(CANCELLED);
        $response['status'] = true;
    }
    websiteNotification('4', 'na', 'Cancellation Request: '.$user['email'], '0', '11', 'users_data', 'user_id', $user['user_id'], 'member', $user['user_id']);
    if($_GET['deletesubaccounts'] == "1"){
        $userModel->updateChildUserStatus(CANCELLED);
        $childUserModel = new user();
        $childrenUsers = $childUserModel->get($userId,'parent_id');
        $childrenUsers   = (is_object($childrenUsers)) ? array($childrenUsers) : $childrenUsers;
        foreach($childrenUsers as $child){
            if ($child->clientid){
                user::closeAccount($child->user_id);
            }
            websiteNotification('4', 'na', 'Cancellation Request: '.$child->email, '0', '11', 'users_data', 'user_id', $child->user_id, 'member', $child->user_id);
        }
    }

    if ($response['status']) {
        $w = array_merge($w, $user);
        $email = prepareEmail("cancel-account", $w);
        sendEmailTemplate($w['website_email'], $w['website_email'], $email['subject'], $email['html'], $email['text'], $email['priority'], $w, $email);
        sendEmailTemplate($w['website_email'], $user['email'], $email['subject'], $email['html'], $email['text'], $email['priority'], $w, $email);
        websiteNotification('4', 'na', 'Account Closed : '.$user['email'], '0', '11', 'users_data', 'user_id', $user['user_id'], 'member', $user['user_id']);

        //webhook trigger
        $webhookData                        = $user;
        $webhookData['subscription_id']     = $subscription['subscription_id'];
        $webhookData['subscription_name']   = $subscription['subscription_name'];

        webhook_controller::__executeWebhookByType(webhook_controller::MEMBER_PLAN_CANCEL_TYPE,$webhookData);
    }

    return $response;

}

if ($pars[0] == "api" && $pars[1] == "stats" && $pars[5] != "") {
    $w['load_from_database'] = 1;
    $_ENV['fromadmin'] = 1;
    $response = apiStats($vars, $w);
    header('Content-Type: application/json');
    echo json_encode($response);

} else if ($pars[0] == "api" && ($pars[1] == "field" || $pars[1] == "field_options")) {
    $w['load_from_database'] = 1;
    $_ENV['fromadmin'] = 1;
    $response = apiFormField($vars, $w);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;

} else if ($pars[0] == "api" && $pars[1] == "suggest") {
    $w['load_from_database'] = 1;
    $_ENV['fromadmin'] = 1;
    $response = apiSuggest($vars, $w);
    header('Content-Type: application/json');
    echo json_encode($response);

} else if ($pars[0] == "api" && $pars[1] == "widget" && $pars[4] != "") {
    $_ENV['fromadmin'] = 0;
    $w['load_from_database'] = 0;

    if ($pars[2] == "json") {
        header('Content-Type: application/json');

    } else {
        header('Content-Type: text/html');
    }
    echo widget(urldecode($pars[4]), "", $w['website_id'], $w);

} else if ($pars[0] == "api" && $pars[1] == "data" && $pars[5] != "") {
    $w['load_from_database'] = 1;
    $_ENV['fromadmin'] = 1;

    if ($pars[3] == "post") {
        $response = apiPost($vars, $w);

    } else {
        $response = apiGet($vars, $w);
    }
    $_SESSION['api_since'] = date("YmdHis");

    if ($response['dataformat'] == "json") {
        header('Content-Type: application/json');
        echo json_encode($response, true);

    } else if ($response['dataformat'] == "html") {
        header('Content-Type: text/html');
        echo $response['widget'];
    }
} else if ($pars[0] == "api" && $pars[1] == "deleteaccount") {

    if(isset($_COOKIE['userid'])){
        $userIdSent     = dataCrypt('decrypt',$_GET['user-id']);
        $loggedUserId   = dataCrypt('decrypt',$_COOKIE['userid']);
        if($userIdSent == $loggedUserId){
            $response       = deleteAccount($userIdSent, $w);
        }
    }

    echo json_encode($response, true);
}

?>