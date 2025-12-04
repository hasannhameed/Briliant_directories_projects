<?php
/**
 * @Name: Bootstrap Theme - Account - Feature Body
 * @brief This widget holds the layout of the feature's posts created on your user account
 * @details hold the loops that display the table with each post created on a certain membership feature from you user account, holds the option of view live, update, delete and copy to manage the content you have posted
 */

 /// Sanitize Data that we are adding from user inputs into the modules
//$_GET = mysql_real_escape_string_array($_GET);

$postTransactionWasMade = false;
$purchaseLimitAddOn = getAddOnInfo('purchase_limit');
$userInfo = getUser($_COOKIE['userid'], $w);

if ($dc['data_type'] == 28) { //this addon don't work with sub accounts
    unset($purchaseLimitAddOn['status']);
}
if ($pars[1] == "additional_locations") {
    if (!empty($user_data['parent_id']) && $user_data['parent_id'] > 0) {
        header('Location: /account/home');
    }
}
if (isset($_GET['purchase_action']) && isset($_GET['invoiceid']) && $_GET['purchase_action'] != "" && $_GET['invoiceid'] != "") {
    $postTransactionWasMade = post_payment_controller::checkCredit(
        $_GET['invoiceid'],
        $_GET['purchase_action'],
        $user_data['user_id'],
        $dc['data_id']
    );
}

if ($postTransactionWasMade === true) {
    echo showMessage("%%%payment_successful_label%%% ", "good", $w);
}

$allowingCopyPost = true;
$now = date('YmdHis'); //this variable is for the expire if statement

if ($_GET['statusFilter'] != '') {
    $statusFilter = '';
    switch ($_GET['statusFilter']) {
        case "ppublished":
            $statusFilter = " AND post_status = 1";
            break;
        case "pdraft":
            $statusFilter = " AND post_status = 0";
            break;
        case "ppendingapproval":
            $statusFilter = " AND post_status = 3";
            break;
        case "pexpired":
            $statusFilter = " AND ( STR_TO_DATE(date_format(dp.post_expire_date, '%d/%m/%Y'), '%d/%m/%Y') < NOW() ) ";
            break;
    }
}
switch ($_GET['sortValue']) {
    case 'newest_first':
        $sqlOrder = " dp.post_id DESC";
        break;
    case 'newest_last':
        $sqlOrder = " dp.post_id ASC";
        break;
    case 'updated_first':
        $sqlOrder = " dp.revision_timestamp DESC";
        break;
    case 'updated_last':
        $sqlOrder = " dp.revision_timestamp ASC";
        break;
    case 'date_start_first':
        $sqlOrder = " dp.post_start_date DESC";
        break;
    case 'date_start_last':
        $sqlOrder = " dp.post_start_date ASC";
        break;
    case 'featured':
        $sqlOrder = " dp.post_featured DESC";
        break;
}
// if the limit is bigger than 0, will bring the total of publication the client has.
if ($subscriptionCategoryLimit > 0 && $subscriptionCategoryLimit != -1) {
    $membersTotalPostQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
            count(post_id)
        FROM
            `data_posts`
        WHERE
            user_id = '" . $user_data[user_id] . "'
        AND
            data_id = '" . $dc[data_id] . "'" . $sqlWhereFilter);
    $membersTotalPosts = mysql_fetch_array($membersTotalPostQuery);

    if ($membersTotalPosts[0] >= $subscriptionCategoryLimit) {
        $allowingCopyPost = false;
    }
} else if (isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === 'success' && $subscriptionCategoryLimit == -1 && post_payment_controller::canPost($dc['data_id'], $user_data['user_id'], post_payment_controller::LIMIT, $subscriptionCategoryLimit,false,$dataPostLimitted->{$dc['data_id']}->post_limitted) == false) {

    $allowingCopyPost = false;

}

$canCreateSubAccounts = users_controller::canCreateSubAccounts($user_data['active'],$dc['data_type']);

if(!$canCreateSubAccounts){
    $allowingCopyPost       = false;
}

if ($_GET['website_custom_action'] == "sub_account_action") {
    switch ($_GET['perform']) {

        case 'delete_member':
            //get the user id with the token
            $getUserId = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                    user_id,parent_id
                FROM
                    `users_data`
                WHERE
                    token = '" . $_GET[token] . "'");

            if (mysql_num_rows($getUserId) > 0) {
                $selectedUser = mysql_fetch_assoc($getUserId);
                deleteUser($selectedUser['user_id'], $w);
                websiteNotification('4', "Delete Sub Account", "Sub Account Member ID #".$selectedUser['user_id']." Deleted from parent account ID #".$selectedUser['parent_id'], "0", "33" , 'users_data', 'user_id', $selectedUser['parent_id'], 'member', $selectedUser['parent_id']);
            }
            echo "user deleted";
            exit;
            break;
    }
}
$userStatesArray = array(
    "1" => "Not Active",
    "2" => "Active",
    "3" => "Canceled",
    "4" => "On Hold"
);
$postStatesArray = array(
    "0" => "draft",
);


if ((($pars[2] == "") || ($pars[2] == "view")) && ($_GET['external_action'] != "getresults")) {
    $dc['category_order_by'] = "post_id DESC";

    $pageStart = (isset($_GET['start'])) ? $_GET['start'] : 0;
    $pageLength = (isset($_GET['length'])) ? $_GET['length'] : 10;
    switch ($dc['data_type']) {
        case '1':
            $postresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                    *
                FROM
                    `news_posts`
                WHERE
                    `user_id` = '" . $_COOKIE[userid] . "'
                ORDER BY
                    " . $dc['category_order_by']);
            break;
        case '28':

            $postresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                    *
                FROM
                    `users_data`
                WHERE
                    `parent_id`='" . $_COOKIE['userid'] . "'
                ORDER BY
                     user_id DESC");
            break;
        default:

            $postresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                    *
                FROM
                    `data_posts` AS dpp
                    WHERE
                    `data_id`='" . $dc['data_id'] . "'
                AND
                    `user_id`='" . $_COOKIE['userid'] . "'
                ORDER BY
                    dpp.post_status ASC,
                    " . $dc['category_order_by'] . " LIMIT " . $pageStart . "," . $pageLength);
            break;
    }

    $postresultsCount = mysql_num_rows($postresults);
    if ($postresultsCount > 0) {

        if (($postresultsCount < $subscriptionCategoryLimit) && (!isset($purchaseLimitAddOn['status']) || $purchaseLimitAddOn['status'] !== 'success')) { ?>
                <?php if($canCreateSubAccounts === true || !isset($user_data['parent_id']) || $user_data['parent_id'] == 0){?>
                    <a href="/account/<?php echo $pars[1]; ?>/<?php echo $w['default_add_post_url'];?>" class="btn btn-success publish-post-button">
                        %%%add_a_new_label%%% <?php echo $dc['data_name']; ?>
                    </a>
                <?php }else if($canCreateSubAccounts === false && $dc['data_type'] == "28"){
                    echo showMessage("%%%sub_account_owner_user_inactive%%%", "error", $w);
                }?>
                
        <?php } else if ((isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === 'success') && isset($dataPostLimitted->{$dc['data_id']}->post_limitted) && ($dataPostLimitted->{$dc['data_id']}->post_limitted != 2 && post_payment_controller::canPost($dc['data_id'], $user_data['user_id'], post_payment_controller::LIMIT, $subscriptionCategoryLimit,false,$dataPostLimitted->{$dc['data_id']}->post_limitted) === true)) {
            $credits  = post_payment_controller::amountOfCreditsLeft($dc['data_id'],$user_data['user_id'],post_payment_controller::LIMIT,$subscriptionCategoryLimit);
            $extraLabelTextForAddButton = ($credits > 0)?"- %%%you_have_credits%% +".$credits: "" ;
            ?>
            <a href="/account/<?php echo $pars[1]; ?>/<?php echo $w['default_add_post_url'];?>" class="btn btn-success publish-post-button">
                %%%add_a_new_label%%% <?php echo $dc['data_name']." ".$extraLabelTextForAddButton; ?>
            </a>
        <?php } else {

            $purchaseLimitAddOn = getAddOnInfo('purchase_limit');
            if ($dc['data_type'] == 28) { //this addon don't work with sub accounts
                $purchaseLimitAddOn = array();
            }
            $userInfo = getUser($_COOKIE['userid'], $w);
            $sub = getSubscription($userInfo['subscription_id'], $w);
            $postPublishLimitArray = json_decode($sub['data_settings_limit'], true);
            $postPublishLimit = $postPublishLimitArray[$dc['data_id']];
            $payPerAll = false;
            $dataPostLimitted = json_decode($sub['data_post_limitted']);
            $postCount = post_payment_controller::getDataPostCount($userInfo['user_id'], $dc['data_id']);

            if (isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] == 'success') {
                if (isset($dataPostLimitted->{$dc['data_id']}->post_limitted) && $dataPostLimitted->{$dc['data_id']}->post_limitted == 1) {
                    $payPerAll = true;
                }
            }

            $showLimitAlert = ($postCount >= $postPublishLimit && $postPublishLimit > 0)?true:false;

            if ( ($showLimitAlert || $purchaseLimitAddOn['status'] != 'success') && (isset($dataPostLimitted->{$dc['data_id']}->post_limitted) && $dataPostLimitted->{$dc['data_id']}->post_limitted == 2) ) {//without new addon old validation

                echo showMessage($label['limit_reached_message'] . " " . $subscriptionCategoryLimit . " " . $dc['data_name'] . ' ' . $label['Posts_label'], "alert", $w);
            } else if (isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === 'success' && post_payment_controller::canPost($dc['data_id'], $user_data['user_id'], post_payment_controller::LIMIT, $subscriptionCategoryLimit,false,$dataPostLimitted->{$dc['data_id']}->post_limitted) === false) {//i have addon new validation
                $userSubscription = getSubscription($user_data['subscription_id'], $w);
                $prices = json_decode($userSubscription['data_price_limit']);
                $priceAmount = (!isset($prices->{$dc['data_id']}->price) || $prices->{$dc['data_id']}->price <= 0) ? 1 : $prices->{$dc['data_id']}->price;
                $dataView = array(
                    'user_id' => $user_data['user_id'],
                    'data_id' => $dc['data_id'],
                    'post_action' => post_payment_controller::LIMIT,
                    'w' => $w,
                    'buttonTitle' => $label['add_a_new_label'] . ' ' . $dc['data_name'] . ' '.html_entity_decode('&mdash;', ENT_QUOTES, 'UTF-8').' ' . displayPrice($priceAmount, $w),
                    'userToken' => $user_data['token'],
                    'dataCategoryFilename' => $dc['data_filename']
                );

                echo posts_view::renderView('buy_limit_button', $dataView);
            } else if ((isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] == 'success')) { ?>

                <?php
                //we check if we have some credits left
                $credits  = post_payment_controller::amountOfCreditsLeft(
                    $dc['data_id'],
                    $user_data['user_id'],
                    post_payment_controller::LIMIT,
                    $subscriptionCategoryLimit
                );

                $extraLabelTextForAddButton = ($credits > 0) ? " - %%%you_have_credits%% +".$credits : "" ;
                ?>

                <a href="/account/<?php echo $pars[1]; ?>/<?php echo $w['default_add_post_url'];?>" class="btn btn-success publish-post-button">
                    %%%add_a_new_label%%% <?php echo $dc['data_name'].$extraLabelTextForAddButton; ?>
                </a>
            <?php }
        }

        if ($dc['data_type'] != 28) { ?>
            <table id="feature-body-datatable" class="table table-striped">
                <!-- datatables -->
            </table>
            <?php
        } else { ?>
            <div class="table-responsive">
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th style="width:30%;"><?php echo $dc['data_name']; ?></th>
                        <th>%%%photo_albums_status%%%</th>
                        <th>%%%photo_albums_actions%%%</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($post = mysql_fetch_array($postresults)) {
                     //   $post = additionalFields($post, $w);
                        $post = getUser($post['user_id'], $w);
                        foreach ($post as $key => $value) {
                            
                            //if the sub account can't be create we set the user status to no active
                            if($key == 'active' && $canCreateSubAccounts == false){
                                $value = NO_ACTIVE;
                            }

                            $post[$key] = stripslashes($value);
                        } ?>
                        <tr>
                            <td class="col-md-5">
                                <h4>
                                    <a href="/account/<?php echo $pars[1]; ?>/edit/<?php echo $post['token']; ?>">
                                        <b><?php echo $post['full_name']; ?></b>
                                    </a>
                                </h4>
                                <p class="font-sm">
                                    <b>%%%location_label%%%:</b>
                                    <?php echo $post['location']; ?>
                                    <br/>
                                    <b>%%%email_label%%%:</b>
                                    <?php echo $post['email']; ?>
                                    <br/>
                                </p>
                            </td>
                            <td class="col-md-2">
                                <div class="text-success bold"><?php echo $userStatesArray[$post['active']]; ?></div>
                            </td>
                            <td class="col-md-3">
                                <table class="actionstable">
                                    <tbody>
                                    <tr>
                                        <td class="rpad bpad" colspan="2">
                                            <a href="/login/token/<?php echo $post['token']; ?>/home?parentToken=<?php echo $userInfo['token']?>"
                                               class="btn btn-primary btn-xs btn-block">
                                                %%%login_to_label%%% <?php echo $dc['data_name']; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="rpad bpad">
                                            <a href="/account/<?php echo $pars[1]; ?>/edit/<?php echo $post['token']; ?>"
                                               class="btn btn-warning btn-xs btn-block">
                                                <i class="fa fa-pencil"></i> %%%photo_albums_edit%%%
                                            </a>
                                        </td>
                                        <td class="rpad bpad">
                                            <a href="#" class="btn btn-danger btn-xs btn-block delete-sub-account"
                                               data-token="<?php echo $post['token']; ?>">
                                                <i class="fa fa-minus-circle"></i> %%%delete_label%%%
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php } //end while?>
                    </tbody>
                </table>
            </div>
        <?php }
    } else { ?>
        <?php

        if(isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === 'success'){
            $userInfo           = getUser($_COOKIE['userid'], $w);
            $sub                = getSubscription($userInfo['subscription_id'], $w);
            $dataPostLimitted   = json_decode($sub['data_post_limitted']);
        }

        if(isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === 'success' && post_payment_controller::canPost($dc['data_id'], $user_data['user_id'], post_payment_controller::LIMIT, $subscriptionCategoryLimit,false,$dataPostLimitted->{$dc['data_id']}->post_limitted) === false && $dataPostLimitted->{$dc['data_id']}->post_limitted != 2){
            $userSubscription = getSubscription($user_data['subscription_id'], $w);
            $prices = json_decode($userSubscription['data_price_limit']);
            $priceAmount = (!isset($prices->{$dc['data_id']}->price) || $prices->{$dc['data_id']}->price <= 0) ? 1 : $prices->{$dc['data_id']}->price;
            $dataView = array(
                'user_id' => $user_data['user_id'],
                'data_id' => $dc['data_id'],
                'post_action' => post_payment_controller::LIMIT,
                'w' => $w,
                'buttonTitle' => $label['add_a_new_label'] . ' ' . $dc['data_name'] . ' '.html_entity_decode('&mdash;', ENT_QUOTES, 'UTF-8').' ' . displayPrice($priceAmount, $w),
                'userToken' => $user_data['token'],
                'dataCategoryFilename' => $dc['data_filename']
            );

            echo posts_view::renderView('buy_limit_button', $dataView);
        }else{
            ?>
            <?php if($canCreateSubAccounts === true){?>
                <a href="/account/<?php echo $pars[1]; ?>/<?php echo $w['default_add_post_url'];?>" class="btn btn-success publish-post-button">
                    %%%add_a_new_label%%% <?php echo $dc['data_name']; ?>
                </a>
            <?php }else if($canCreateSubAccounts === false && $dc['data_type'] == "28"){
                    echo showMessage("%%%sub_account_owner_user_inactive%%%", "error", $w);
            }?>

        <?php   }
    } //end else
} ?>


<?php
//ajax call handler
if ($_GET['external_action'] == "getresults" && $_GET['header_type'] == "json") {

    $dc['category_order_by'] = "dp.post_id DESC";

    $dataType = (isset($_GET['dataType'])) ? $_GET['dataType'] : "";
    $dataId = (isset($_GET['dataId'])) ? $_GET['dataId'] : "0";
    $pageStart = (isset($_GET['start'])) ? $_GET['start'] : 0;
    $pageLength = (isset($_GET['length'])) ? $_GET['length'] : 10;
    $draw = (isset($_GET['draw'])) ? $_GET['draw'] : 1;

    $searchValue = (isset($_GET['search']['value'])) ? $_GET['search']['value'] : "";
    $searchValue = (strlen($searchValue) > 0) ? trim($searchValue) : $searchValue;

    $pageLength = ($pageLength == "-1") ? "9999999" : $pageLength;

    $sqlWhereFilter = "";
    $sqlWhereDateFilter = "";
    $sqlLeftJoinWhereFilter = "";
    $sqlLeftJoinWhereFilter2 = "";

    //if it's a date, filter by date
    if (
        (DateTime::createFromFormat('!m/d', $searchValue)) ||
        (DateTime::createFromFormat('!d/m', $searchValue)) ||
        (DateTime::createFromFormat('!m/Y', $searchValue)) ||
        (DateTime::createFromFormat('!m/d/Y', $searchValue)) ||
        (DateTime::createFromFormat('!d/m/Y', $searchValue)) ||
        (DateTime::createFromFormat('!Y', $searchValue))
    ) {

        $sqlWhereDateFilter .= " OR (
                (date_format(dp.post_start_date, '%m/%d/%Y') REGEXP '$searchValue')
                OR (date_format(dp.post_expire_date, '%m/%d/%Y') REGEXP '$searchValue')
                OR (date_format(dp.post_live_date, '%m/%d/%Y') REGEXP '$searchValue')

                OR (date_format(dp.revision_timestamp, '%m/%d/%Y') REGEXP '$searchValue')
                OR (date_format(dp.revision_timestamp, '%d/%m/%Y') REGEXP '$searchValue')

                OR (date_format(dp.post_start_date, '%d/%m/%Y') REGEXP '$searchValue')
                OR (date_format(dp.post_expire_date, '%d/%m/%Y') REGEXP '$searchValue')
                OR (date_format(dp.post_live_date, '%d/%m/%Y') REGEXP '$searchValue')

            ) ";
    }

    //if not a date, search by a query string
    if (!empty($searchValue)) {

        //if it's a search by status
        /*if( in_array(strtolower($searchValue), $postStatesArray) ) {
            $postStatusFilter = ( in_array(strtolower($searchValue), $postStatesArray) ) ? array_search(strtolower($searchValue), $postStatesArray) : false;
            ( $postStatusFilter >= 0 ) ? $sqlWhereFilter .= " AND dp.post_status = $postStatusFilter " : false;
        } else {*/
        //Simple query string search

        //Search for expired status (by date)
        if (preg_match("/pexpired/i", $searchValue)) {
            $sqlWhereFilter .= " AND ( STR_TO_DATE(date_format(dp.post_expire_date, '%d/%m/%Y'), '%d/%m/%Y') < NOW() ) ";
        } else if (preg_match("/ppublished/i", $searchValue)) {
            $sqlWhereFilter .= " AND dp.post_status = 1 ";
        } else if (preg_match("/pdraft/i", $searchValue)) {
            $sqlWhereFilter .= " AND dp.post_status = '0' ";
        } else {

            $sqlWhereFilter .= (!empty($searchValue)) ?
                " AND (
                        ( (dp.post_title REGEXP '$searchValue')
                        OR (dp.post_tags REGEXP '$searchValue')
                        OR (dp.post_content REGEXP '$searchValue')
                        OR (dp.post_category REGEXP '$searchValue')
                        OR ( um.value REGEXP '$searchValue' )
                        OR ( um2.value REGEXP '$searchValue' ) )

                        $sqlWhereDateFilter

                        ) "
                : "";

            $sqlLeftJoinWhereFilter .= " AND um.value REGEXP '$searchValue' ";
            $sqlLeftJoinWhereFilter2 .= " AND um2.value REGEXP '$searchValue' ";

        }
        //}
    }

    $totalNumRows = 0;
    $mysqlNumRows = 0;
    $sqlWhereFilter = $sqlWhereFilter . " " . $statusFilter;
    switch ($dataType) {
        case '1':
            $postresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT SQL_CALC_FOUND_ROWS *
                FROM
                    `news_posts`
                WHERE
                    `user_id` = '" . $_COOKIE[userid] . "'
                ORDER BY
                    " . $dc[category_order_by] . "
                    LIMIT $pageStart, $pageLength");

            break;
        case '28':
            $postresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT SQL_CALC_FOUND_ROWS *
                FROM
                    `users_data`
                WHERE
                    `parent_id`='" . $_COOKIE[userid] . "'
                ORDER BY
                    user_id DESC
                LIMIT $pageStart, $pageLength");

            break;
        default:

            $postresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),
                "SELECT SQL_CALC_FOUND_ROWS
                            dp.*,
                            dc.data_filename, dc.data_name,
                            IF ( um.value is null, '', um.value ) as location_value,
                            IF ( um2.value is null, '', um2.value ) as meta_value
                    FROM
                            `data_posts` dp

                    LEFT JOIN `users_meta` um ON dp.post_id = um.database_id AND um.database = 'data_posts' AND um.key = 'post_location' $sqlLeftJoinWhereFilter

                    LEFT JOIN `users_meta` um2 ON dp.post_id = um2.database_id AND um2.database = 'data_posts' AND um2.key IN ('post_venue', 'post_promo', 'post_url', 'post_video') /*$sqlLeftJoinWhereFilter2*/

                    INNER JOIN `data_categories` dc ON dc.`data_id` = dp.`data_id`

                    WHERE
                            dp.`data_id`='" . $dataId . "'
                    AND
                            dp.`user_id`='" . $_COOKIE[userid] . "'

                            $sqlWhereFilter

                    GROUP BY
                            dp.post_id

                    ORDER BY
                            ".$sqlOrder."
                            
                    LIMIT $pageStart, $pageLength"
            );

            break;
    }


    ob_clean();

    $data = array();
    $td1 = "";
    $td2 = "";
    $td4 = "";


    $mysqlNumRows = mysql_num_rows($postresults);

    $totalNumRowsQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT FOUND_ROWS();");
    $totalNumRowsQuery = mysql_fetch_array($totalNumRowsQuery);
    $totalNumRows = ($totalNumRowsQuery[0] > 0) ? $totalNumRowsQuery[0] : 0;
    $userStatisticsAddonStatus = addonController::isAddonActive('user_statistics_addon');
    $userModel      = new user();
    $userModel->getUserById($_COOKIE['userid']);
    $subscription   = getSubscription($userModel->subscription_id,$w);

    if (mysql_num_rows($postresults) > 0) {

        $j = 0;

        $userInfo                   = getUser($_COOKIE['userid'], $w);
        $sub                        = getSubscription($userInfo['subscription_id'], $w);
        $subscriptionLimits         = json_decode($sub['data_settings_limit'], true);
        $dataPostLimitted           = json_decode($sub['data_post_limitted']);

        while ($post = mysql_fetch_array($postresults)) {

            //$post = additionalFields($post, $w);
            $post = getMetaData("data_posts", $post['post_id'], $post, $w);
            foreach ($post as $key => $value) {
                $post[$key] = stripslashes($value);
            }
            if ($post['post_article'] != "") {
                $post['post_content'] = $post['post_article'];
            }

            $post['post_video_link'] = $post['post_video'];
            $videoOembed = new oembed_controller($post['post_video_link'],null,$post['post_image']);
            $videoData = $videoOembed->getOembedData();
            if (!empty($videoData)) {
                $post['post_video_oembed'] = (array)$videoData;
                $post['post_video_thumbnail'] =$videoOembed->getThumbnailHtml($post);

            }

            //---------------------------------------------------------------------------------------------------------
            // 1
            //---------------------------------------------------------------------------------------------------------
            $td1 = "<div class='clearfix'></div>";
            //here we concat the end date with the post_expire_date in order to does not get an expired label always
            //fix only for events
            if ($post['formname'] == "event_fields") {
                $post['post_expire_date'] = $post['post_expire_date'] . $post['end_time'];
            }
            if ($post['post_expire_date'] != "" && strlen($post['post_expire_date']) == 8) {
                $now2 = date('Ymd');
                if ($post['post_expire_date'] < $now2) {
                    $post['post_status'] = 2;
                }
            } else if ($post['post_expire_date'] != "" && $post['post_expire_date'] < $now) {
                $post['post_status'] = 2;
            }
            if ($post['post_status'] == 0) {
                $td1 .= "<div class='btn-xs bold line-height-xl center-block no-radius-bottom label-warning'>%%%feature_datatable_draftsaved%%%";
            } else {
                $metaModel = new users_meta();
                $sub_where = array(
                    array('value' => 'data_categories', 'column' => 'database', 'logic' => '='),
                    array('value' => $post['data_id'], 'column' => 'database_id', 'logic' => '='),
                    array('value' => 'post_approval', 'column' => 'key', 'logic' => '=')
                );
                $dcPostApprovalMeta = $metaModel->get($sub_where);
                if ($post['post_status'] == 2) {
                    $td1 .= "<div class='btn-xs bold line-height-xl center-block no-radius-bottom label-danger'>%%%feature_datatable_expired%%%";
                } else if ($post['post_status'] == 3) {
                    $postApprovalVariable = "post_approval_data".$post['data_id'];

                    if (addonController::isAddonActive('post_approval') && (($dcPostApprovalMeta->value == "admin_control" && (!isset($subscription[$postApprovalVariable]) || $subscription[$postApprovalVariable] == 'default')) || $subscription[$postApprovalVariable] == "admin_control")) {

                        global $td1;
                        addonController::showWidget('post_approval', 'aacc768c11b36a5b0f08842954482cd6', '');
                    } else {
                        $td1 .= "<div class='btn-xs bold line-height-xl center-block no-radius-bottom label-warning'>%%%feature_datatable_draftsaved%%%";
                    }
                } else {
                    $td1 .= "<div class='btn-xs bold line-height-xl center-block no-radius-bottom label-primary'>%%%featured_datatable_published%%%";
                }
            }
            if($userStatisticsAddonStatus === true && $subscription['profile_statistics'] == 1 && $post['post_status'] != 3){
                $td1 .= "<span class='pull-right line-height-xl post-views'>".user_statistics_controller::getPostViewCountByPostId($post['post_id'])." <span class='small'>%%%photo_albums_views%%%</span></span>";
            }
            //Close div before image loads
            $td1 .= "<div class='clearfix'></div></div>";

            //Open div that holds image
            $td1 .= "<div class='alert-default btn-block text-center the-post-image'>";

            if ($post['post_image'] != "" && $w['image_settings_exist'] == "1") {
                $post['post_image'] = str_replace('news-pictures','news-pictures-thumbnails', $post['post_image']);
                $td1 .= "<img src='" . $post['post_image'] . "'>";
            } else if ($post['post_video_thumbnail'] != "" && $dataType == 9 ) {
                $td1 .= $post['post_video_thumbnail'];
            } else if ($post['post_url'] && strpos($post['post_url'], 'soundcloud')) {
                $oembedUrl = 'https://soundcloud.com/oembed?format=json&url=' . urlencode($post['post_url']);
                $ch = curl_init($oembedUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                $soundCloudResult = curl_exec($ch);
                curl_close($ch);

                $soundCloudResult = json_decode($soundCloudResult, true);
                $soundCloudImg = $soundCloudResult['thumbnail_url'];

                if (!empty($soundCloudImg)){
                    $td1 .= "<img src='" . $soundCloudImg . "' data-url='" . $post['post_url'] . "'>";
                } else {
                    $td1 .= "<div style='padding:30px 0;'><span class='badge alert-secondary fpad img-circle' style='margin:0 auto;display:block;width:50%;'>%%%No_image_label%%%</span><div class='clearfix'></div></div>";
                }
            } else {
                $td1 .= "<div style='padding:30px 0;'><span class='badge alert-secondary fpad img-circle' style='margin:0 auto;display:block;width:50%;'>%%%No_image_label%%%</span><div class='clearfix'></div></div>";
            }

            //Close div that holds image
            $td1 .= "<div class='clearfix'></div></div>";

            if(subscription_types_controller::isBookMarkCountEnable($sub['data_settings'])){
                $td1 .= "<div class='btn-xs bold line-height-xl center-block no-radius-top label-default'><span class='pull-left line-height-xl post-likes'> <span class='small'>%%%bookmark_counts%%%: </span>".users_favorite::getFavoritesCountPerPost($post['post_id'])."</span><div class='clearfix'></div></div>";
            }

            //---------------------------------------------------------------------------------------------------------

            //---------------------------------------------------------------------------------------------------------
            // 2
            //---------------------------------------------------------------------------------------------------------


            $td2 = "<h4 class='line-height-lg bold xs-nomargin post-title' data-year=" . $post['event_year'] ."><a href='/account/" . $post['data_filename'] ."/". $w['default_edit_post_url'] ."/". $post['post_token'] . "'>" . $post['post_title'] . ($post['event_year'] != 0 ? " <span class='event-year pull-right'>" . $post['event_year'] . "</span>" : '')."</a>".($post['post_featured'] == 1 ? '<span class="badge lmargin">%%%featured_badge%%%</span>' : '')."</h4>";
            $td2 .= "<div class='small bmargin hidden-xs'>";
            $td2 .= "<p class='the-post-description'>" . limitWords(trim(strip_tags(str_replace('src="http://', 'src="//', $post['post_content']))), 200) . "</p>";
            if ($post['location_value'] != "" || $post['post_location'] != "") {
                $td2 .= "<span class='the-post-location small'><b>".$label['location_label'].":</b> ";
                $td2 .= $post['post_location'];
                $td2 .= "</span>";
            }

            //---------------------------------------------------------------------------------------------------------

            //---------------------------------------------------------------------------------------------------------
            // 4
            //---------------------------------------------------------------------------------------------------------


            $td4 = "<div class='dropdown center-block'>";
            $td4 .= "<button style='white-space:nowrap;' type='button' data-toggle='dropdown' aria-expanded='false' class='btn btn-default bmargin btn-sm bold btn-block dropdown-toggle'>%%%photo_albums_actions%%% <i class='fa fa-angle-down pull-right' style='margin-top: 4px;'></i></button>";
            $td4 .= "<ul class='dropdown-menu pull-right font-sm'>";
            $td4 .= "<li>";

            $td4 .= "<a class='action-post-link-view' href='/" . $post['post_filename'] . "' target='_blank'>";
            if ($post['post_status'] == 0 || $post['post_status'] == 3) {
                $td4 .= "%%%photo_albums_preview%%%";
            } else {
                $td4 .= "%%%photo_albums_view_post%%%";
            }
            $td4 .= "</a>";
            $td4 .= "</li>";
            $td4 .= "<li>";
            $td4 .= "<a class='action-post-link-edit' href='/account/" . $post['data_filename'] ."/". $w['default_edit_post_url'] ."/". $post['post_token'] . "'>";
            $td4 .= "%%%photo_albums_edit%%%";
            $td4 .= "</a>";
            $td4 .= "</li>";

            $subscriptionCategoryLimit  = ( isset( $subscriptionLimits[$post['data_id']] ) && isset( $subscriptionLimits[$post['data_id']] ) != "")?$subscriptionLimits[$post['data_id']]:0;

            $payPerPostOnclickJs = (((isset($purchaseLimitAddOn['status']) && $purchaseLimitAddOn['status'] === 'success') && ($dataPostLimitted->{$post['data_id']}->post_limitted == 2 || post_payment_controller::canPost($post['data_id'], $userInfo['user_id'], post_payment_controller::LIMIT, $subscriptionCategoryLimit,false,$dataPostLimitted->{$post['data_id']}->post_limitted) === true)) || (!isset($purchaseLimitAddOn['status']) || $purchaseLimitAddOn['status'] !== 'success') )?" href='/account/" . $post['data_filename'] . "/". $w['default_clone_post_url'] ."/" . $post['post_token'] . "' ":" onclick = payPerPost('".$post['post_token']."') href='javascript:' ";
            if ($allowingCopyPost) {
                $td4 .= "<li>";
                $td4 .= "<a class='action-post-link-clone' ".$payPerPostOnclickJs." >";
                $td4 .= "%%%clone_label%%%";
                $td4 .= "</a>";
                $td4 .= "</li>";
            }

            $td4 .= "<li>";
            $deleteMessage  = $label['delete_label'] . " " . $post['data_name']."?";
            $deleteLink     = "/account/".$post['data_filename']."/".$w['default_delete_post_url']."/".$post['post_token'];

            $td4 .= '<a href="javascript:decisionDelete(`'.$deleteMessage.'`, `'.$deleteLink.'`);" class="text-danger action-post-link-delete">';
            $td4 .= "%%%delete_label%%%";
            $td4 .= "</a>";
            $td4 .= "</li>";
            $td4 .= "</ul>";
            $td4 .= "</div>";


            $td4 .= "<div class='small line-height-lg hidden-sm hidden-xs text-right post-dates'><div class='small'>";
            if ($post['post_live_date'] != "") {
                $td4 .= "<span class='inline-block bold'>%%%created_datatable_label%%%</span> <span class='lpad inline-block'>";
                $td4 .= transformDate($post['post_live_date'], "QB");
                $td4 .= "</span><div class='clearfix'></div>";
            }
            if ($post['post_start_date'] != "") {
                $td4 .= "<span class='inline-block bold'>%%%starts_datatable_label%%%</span> <span class='lpad inline-block'>";
                $td4 .= transformDate($post['post_start_date'], "QB");
                $td4 .= "</span><div class='clearfix'></div>";
            }
            if ($post['post_expire_date'] != "") {
                $td4 .= "<span class='inline-block bold'>%%%ends_datatable_label%%%</span> <span class='lpad inline-block'>";
                $td4 .= transformDate($post['post_expire_date'], "QB");
                $td4 .= "</span><div class='clearfix'></div>";
            }
            $td4 .= "</div></div>";


            $data[$j] = array(
                "picture" => $td1,
                "description" => $td2,
                "actions" => $td4
            );

            $j++;
        } //while ( $post = mysql_fetch_array($postresults) )
    } //if (mysql_num_rows($postresults) > 0)

    $retVal = json_encode(array("draw" => $draw, "recordsTotal" => $totalNumRows, "recordsFiltered" => $totalNumRows, "data" => $data));
    echo $retVal;
}
?>