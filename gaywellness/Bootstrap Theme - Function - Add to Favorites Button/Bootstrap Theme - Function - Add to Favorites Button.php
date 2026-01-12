<?php



if (!user::isUserLogged($_COOKIE) || $_COOKIE['userid'] != $_POST['favoriteUserClickId']) {
    echo(json_encode(array('status' => 'error','title'=>$label['contact_admin_error'], 'message' => $label['error_label'])));
	exit;
}

$postEscaped = mysql_real_escape_string_array($_POST);
///$userClickId = $postEscaped['favoriteUserClickId'];
$userClickId = $_COOKIE['userid'];
$userId = $postEscaped['favoriteUserId'];
$postId = $postEscaped['favoritePostId'];
$dataId = $postEscaped['favoriteDataId'];
$dataType = $postEscaped['favoriteDataType'];
$featureName = $postEscaped['favoriteDataName'];
$favoriteMode = $postEscaped['favoriteMode'];
$action = $postEscaped['favoriteAction'];
$postArray = implode(',', $postId);
$ownerArray = implode(',', $userId);
$dataIdArray = implode(',', $dataId);
$dataTypeArray = implode(',', $dataType);

if ($action != 'show') {

    // Data Type 10 is Listing
    if ($dataType == 10) {
        $dataTypeResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT `data_id` AS `dataId` FROM `data_categories` WHERE `data_type` = '10'");
        while ($dataTypeCheck = mysql_fetch_assoc($dataTypeResults)) {
            $dataId = $dataTypeCheck['dataId'];
        }
    }

    // Inserts favorite to database
    if ($action == 'add') {

        $whereArray = array(
            array(
                'column'    => 'user_id',
                'value'     => $userClickId,
                'logic'     => '='
            ),
            array(
                'column'    => 'active',
                'value'     => '2',
                'logic'     => '='
            )
        );

        $userResult = bd_controller::user(WEBSITE_DB)->get($whereArray);   

        if($userResult === false){
            die('<split>save_favorite_error<split>');
        } 
    
        mysql(brilliantDirectories::getDatabaseConfiguration('database'), "INSERT IGNORE INTO `users_favorite` (`userId`, `ownerId`, `postId`, `dataType`, `featureId`) VALUES ('".$userClickId."', '".$userId."', '".$postId."', '".$dataType."', '".$dataId."');
");
    }

    // Delete favorite from database
    if ($action == 'delete') {

        if ($dataType != 10) {
            mysql(brilliantDirectories::getDatabaseConfiguration('database'), "DELETE FROM `users_favorite` WHERE `userId` = '".$userClickId."' AND `postId` = '".$postId."' AND `dataType` = '".$dataType."' AND `featureId` = '".$dataId."'");
        } else {
            mysql(brilliantDirectories::getDatabaseConfiguration('database'), "DELETE FROM `users_favorite` WHERE `userId` = '".$userClickId."' AND `ownerId` = '".$userId."' AND `dataType` = '".$dataType."' AND `featureId` = '".$dataId."'");
        }
    }

    // Check if favorites already exist and mark them
    if ($action == 'check') {
        if ($dataType != 10 || $favoriteMode == 'multiSidebar') {
            if ($favoriteMode != 'detail' && $favoriteMode != 'multiSidebar') {
                $checkResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT `postId` AS `postQuery` FROM `users_favorite` WHERE `postId` IN (".$postArray.") AND `userId` = '".$userClickId."' AND `dataType` = '".$dataType."' AND `featureId` = '".$dataId."'");
            }
            else if ($favoriteMode == 'multiSidebar') {
                $totalArrayCount = count($userId);
                if ($totalArrayCount > 0) {
                    for ($i = 0; $i < $totalArrayCount; $i++) {
                        if ($dataType[$i] == 4) {
                            $checkArrayResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT `postId` AS `postQuery`, `featureId` AS `dataQuery` FROM `users_favorite` WHERE `postId` IN (".$postId[$i].") AND `userId` = '".$userClickId."' AND `dataType` = '".$dataType[$i]."' AND `featureId` = '".$dataId[$i]."'");
                        }
                        else if ($dataType[$i] == 10) {
                            $checkArrayResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT `ownerId` AS `postQuery`, '0' AS `dataQuery` FROM `users_favorite` WHERE `ownerId` IN (".$userId[$i].") AND `userId` = '".$userClickId."' AND `dataType` = '".$dataType[$i]."'");
                        }
                        else if ($dataType[$i] == 20) {
                            $checkArrayResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT `postId` AS `postQuery`, `featureId` AS `dataQuery` FROM `users_favorite` WHERE `postId` IN (".$postId[$i].") AND `userId` = '".$userClickId."' AND `dataType` = '".$dataType[$i]."' AND `featureId` = '".$dataId[$i]."'");
                            // echo '<split>PostId: '.$postId[$i].'| dataType: '.$dataType[$i].'| featureId:'.$dataId[$i].'<split>';
                        }
                        else if ($dataType[$i] == 9) {
                            $checkArrayResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT `postId` AS `postQuery`, `featureId` AS `dataQuery` FROM `users_favorite` WHERE `postId` IN (".$postId[$i].") AND `userId` = '".$userClickId."' AND `dataType` = '".$dataType[$i]."' AND `featureId` = '".$dataId[$i]."'");
                        }
                        while ($checkDataArray = mysql_fetch_assoc($checkArrayResults)) {
                            $checkFinalArray[] = $checkDataArray;
                        }
                    }
                }
            } else {
                $checkResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT `postId` AS `postQuery` FROM `users_favorite` WHERE `postId` IN (".$postId.") AND `userId` = '".$userClickId."' AND `dataType` = '".$dataType."' AND `featureId` = '".$dataId."'");
            }
        } else {
            $checkResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT `ownerId` AS `postQuery` FROM `users_favorite` WHERE `ownerId` IN (".$ownerArray.") AND `userId` = '".$userClickId."' AND `dataType` = '".$dataType."' AND `featureId` = '".$dataId."'");
        }
        if ($favoriteMode == 'multiSidebar') {
            echo '<split>'.json_encode($checkFinalArray).'<split>';
        } else {
            while ($checkData = mysql_fetch_assoc($checkResults)) {
                $checkFinal[] = $checkData['postQuery'];
            }
            echo '<split>'.json_encode($checkFinal).'<split>';
			exit();
        }
    }
} else {
    switch($dataType) {
        case 4: // Multi Photo Post
            $html = '<div class="account-form-box">
                        <table class="dataTable table favoriteTable">
                            <thead>
                                <tr>
                                    <th>'.$label['photo_albums_picture'].'</th>
                                    <th>'.$label['data_table_content'].'</th>
                                    <th>'.$label['photo_albums_actions'].'</th>
                                </tr>
                            </thead>
                            <tbody>';
            $dataQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT 
    uf.`ownerId` AS `ownerId`, 
    upg.`group_id` AS `groupId`, 
    upg.`user_id` AS `groupUserId`, 
    upg.`group_filename`, 
    upg.`group_name` AS `groupName`, 
    upg.`group_desc` AS `groupDesc`, 
    upg.`group_filename` AS `groupFilename`, 
    upg.`group_status` AS `groupStatus`, 
    upg.`data_type` AS `groupDataType`, 
    upg.`data_id` AS `groupDataId`
FROM 
    `users_portfolio_groups` AS upg
JOIN 
    `users_favorite` AS uf 
    ON uf.`ownerId` = upg.`user_id` 
    AND uf.`featureId` = upg.`data_id` 
    AND uf.`postId` = upg.`group_id`
WHERE 
    uf.`featureId` = '".$dataId."' 
    AND uf.`userId` = '".$_COOKIE['userid']."' 
    AND upg.`group_status` = 1
GROUP BY 
    upg.`group_id");
            while ($dataResults = mysql_fetch_assoc($dataQuery)) {

                $groupImage = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `users_portfolio` WHERE `user_id` = '".$dataResults['ownerId']."' AND `group_id` = '".$dataResults['groupId']."' LIMIT 1");

                if (mysql_num_rows($groupImage) > 0) {
                    $postImage = mysql_fetch_assoc($groupImage);
                    $dataResults['userPhoto'] = $postImage['file'];
                    $imgHTML =  '<img src="/photos/display/'.$dataResults['userPhoto'].'">';
                } else {
                    $imgHTML = '';
                }

                $flag = true;
                $html .= '<tr>
                <td>' . $imgHTML . '</td>
                <td><span class="favoriteTitle"><a target="_blank" href="http://'.$w['website_url'] .'/'. $dataResults['group_filename'].'">'.stripslashes($dataResults['groupName']).'</a></span><div class="favContent">'.stripslashes(substr(strip_tags($dataResults['groupDesc']), 0, 200)).'...</div></td>
                <td><a class="actionButton actionView btn btn-success btn-block tmargin" target="_blank" href="http://'.$w['website_url'] .'/'. $dataResults['group_filename'].'">'.$label['view_label'].'</a><button class="actionButton actionDelete favoriteFeature btn btn-block btn-danger bold" data-favoriteuser="'.$dataResults['groupUserId'].'" data-favoritefeaturetype="'.$dataType.'" data-favoritefeature="0" data-favoritedataid="'.$dataResults['groupId'].'" data-favoritefeatureid="'.$dataResults['groupDataId'].'">Delete</button>';
                $html .='</td></tr>';
            }
            break;
        case 10: // Listing Type
            $html = '<div class="account-form-box">
                        <table class="dataTable table favoriteTable">
                            <thead>
                                <tr>
                                    <th>'.$label['photo_albums_picture'].'</th>
                                    <th>'.$Label['member'].'</th>
                                    <th>'.$label['photo_albums_actions'].'</th>
                                </tr>
                            </thead>
                            <tbody>';
            $queryListing = "SELECT 
    `userDataId`, 
    `userFilename`, 
    `userActive`, 
    `userFirstName`, 
    `userLastName`, 
    `company`, 
    `userPhoto`, 
    `userPhotoType`, 
    `listingType` 
FROM (
    SELECT 
        ud.`user_id` AS `userDataId`, 
        ud.`filename` AS `userFilename`, 
        ud.`active` AS `userActive`, 
        ud.`first_name` AS `userFirstName`, 
        ud.`last_name` AS `userLastName`, 
        ud.`company` AS `company`, 
        up.`file` AS `userPhoto`, 
        up.`type` AS `userPhotoType`, 
        `listing_type` AS `listingType` 
    FROM 
        `users_data` AS ud, 
        `users_photo` AS up, 
        `users_favorite` AS uf 
    WHERE 
        ud.`user_id` = uf.`ownerId` 
        AND up.`user_id` = uf.`ownerId` 
        AND uf.`userId` = '".$_COOKIE['userId']."' 
        AND uf.`dataType` = '".$dataType."' 
        AND ud.`active` = 2 
        AND up.`type` IN ('logo', 'photo') 
    GROUP BY 
        ud.`user_id` 
    UNION 
    SELECT 
        ud.`user_id` AS `userDataId`, 
        ud.`filename` AS `userFilename`, 
        ud.`active` AS `userActive`, 
        ud.`first_name` AS `userFirstName`, 
        ud.`last_name` AS `userLastName`, 
        ud.`company` AS `company`, 
        '/images/profile-profile-holder.png' AS `userPhoto`, 
        'photo' AS `userPhotoType`, 
        `listing_type` AS `listingType` 
    FROM 
        `users_data` AS ud, 
        `users_favorite` AS uf 
    WHERE 
        ud.`user_id` = uf.`ownerId` 
        AND uf.`userId` = '".$_COOKIE['userId']."' 
        AND uf.`dataType` = '".$dataType."' 
        AND ud.`active` = 2 
    GROUP BY 
        ud.`user_id`
) AS `userListing` 
GROUP BY 
    `userDataId`";
            $dataQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),$queryListing );

            while ($dataResults = mysql_fetch_assoc($dataQuery)) {
                $userPhoto = getUserPhoto($dataResults['userDataId'], $dataResults['listingType'], $w);
                $photoUrl = $userPhoto['file'];
                if ($dataResults['listingType'] == 'Individual') {
                    if($dataResults['userFirstName'] != '' || $dataResults['userLastName'] != ''){
                        $favoriteMemberName = $dataResults['userFirstName'].' '.$dataResults['userLastName'];
                    } else if ($dataResults['company'] != '') {
                        $favoriteMemberName = $dataResults['company'];
                    } else {
                        $favoriteMemberName = $label['member'];
                    }
                    
                } else {
                    if($dataResults['company'] != ''){
                        $favoriteMemberName = $dataResults['company'];
                    } else if ($dataResults['userFirstName'] != '' || $dataResults['userLastName'] != '') {
                        $favoriteMemberName = $dataResults['userFirstName'].' '.$dataResults['userLastName'];
                    } else {
                        $favoriteMemberName = $label['member'];
                    }                    
                }
                $flag = true;

                $html .= '<tr>
                    <td><img src="'.$photoUrl.'"></td>
                    <td><span class="favoriteTitle"><a target="_blank" href="http://'.$w['website_url'] .'/'. $dataResults['userFilename'].'">'.stripslashes($favoriteMemberName).'</a></span></td>
                    <td><a class="actionButton actionView btn btn-success btn-block tmargin" target="_blank" href="http://'.$w['website_url'] .'/'. $dataResults['userFilename'].'">'.$label[view_label].'</a><button class="actionButton actionDelete favoriteFeature btn btn-block btn-danger" data-favoriteuser="'.$dataResults['userDataId'].'" data-favoritefeaturetype="'.$dataType.'" data-favoritefeature="0" data-favoritefeatureid="deleteMember">'.$label['delete_label'].'</button>';
                $html .= '</td></tr>';
            }
            break;
        case 20: // Single Posts
        case 9: // Videos
            if ($dataType == 9) {
                $columnTypeName = 'Video';
                $dataQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT 
    dp.`post_id` AS `postId`, 
    dp.`user_id` AS `postUserId`, 
    dp.`post_image` AS `postImage`, 
    dp.`post_title` AS `postTitle`, 
    dp.`post_content` AS `postContent`, 
    dp.`post_status` AS `postStatus`, 
    dp.`post_filename` AS `postFilename`, 
    dp.`data_id` AS `postDataId`, 
    dp.`post_expire_date` AS `expireDate`, 
    dp.`post_video` AS `postURL`
FROM 
    `data_posts` AS dp, 
    `users_favorite` AS uf, 
    `users_meta` AS um
WHERE 
    um.`database_id` = dp.`post_id` 
    AND uf.`featureId` = dp.`data_id` 
    AND uf.`postId` = dp.`post_id` 
    AND uf.`featureId` = '".$dataId."' 
    AND uf.`userId` = '".$_COOKIE['userId']."' 
    AND dp.`post_status` = 1
GROUP BY 
    dp.`post_id`");
            } else {
                $columnTypeName = 'Picture';
                $dataQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT 
    dp.`post_id` AS `postId`, 
    dp.`user_id` AS `postUserId`, 
    dp.`post_image` AS `postImage`, 
    dp.`post_title` AS `postTitle`, 
    dp.`post_content` AS `postContent`, 
    dp.`post_status` AS `postStatus`, 
    dp.`post_filename` AS `postFilename`, 
    dp.`data_id` AS `postDataId`, 
    dp.`post_expire_date` AS `expireDate`
FROM 
    `data_posts` AS dp, 
    `users_favorite` AS uf
WHERE 
    uf.`featureId` = dp.`data_id` 
    AND uf.`postId` = dp.`post_id` 
    AND uf.`featureId` = '".$dataId."' 
    AND uf.`userId` = '".$_COOKIE['userId']."' 
    AND dp.`post_status` = 1");
            }
            $html = '<div class="account-form-box"><table class="dataTable table favoriteTable"><thead><tr><th>'.$label['photo_albums_picture'].'</th><th>'.$label['data_table_content'].'</th><th>'.$label['photo_albums_actions'].'</th></tr></thead><tbody>';

            while ($dataResults = mysql_fetch_assoc($dataQuery)) {
                $flag = true;
                if($dataResults['postImage']=='' && $dataType != 9) {
                    $dataResults['postImage']='/images/NoImageAvailable.png';
                }
                $html .= '<tr><td>';

                if ($dataType == 9) {
                    $html .= $link;
                    $url = str_replace( 'https://', 'http://', $dataResults['postURL']);
                    $urlDomain = parse_url($url);
                    if (strpos($urlDomain['host'], "youtube") !== false) {
                        $cart = substr($url , 31);
                        $html .= '<a target="_blank" class="video__play_link" id="'.$cart.'" href="http://'.$w['website_url'] .'/'. $dataResults['postFilename'].'"><img src="//img.youtube.com/vi/'.$cart.'/0.jpg" class="search_result_image img-rounded center-block"></a>';
                    }
                    if (strpos($urlDomain['host'], "youtu.be") !== false) {
                        $cart =  substr($url , 16);
                        $html .= '<a target="_blank" class="video__play_link" id="'.$cart.'" href="http://'.$w['website_url'] .'/'. $dataResults['postFilename'].'"><img src="//img.youtube.com/vi/'.$cart.'/0.jpg" class="search_result_image img-rounded center-block"></a>';

                    }
                    if (strpos($urlDomain['host'], "vimeo") !== false) {
                        $cart = substr($url , 17);
                        $imgid = $cart;
                        $hash = unserialize(curlPage("//vimeo.com/api/v2/video/$imgid.php"));
                        $html .= '<a target="_blank" class="video__play_link"  id="'.$cart.'" href="http://'.$w['website_url'] .'/'. $dataResults['postFilename'].'"><img class="search_result_image img-rounded center-block" src="'.$hash[0]['thumbnail_large'].'"/></a>';
                    }
                } else {

                    if ($dataResults['postImage'] == '') {
                        $dataResults['postImage'] == '/images/profile-profile-holder.png';
                    }
                    $html .= '<img src="'.$dataResults['postImage'].'">';
                }

                $html .= '</td>
                    <td><span class="favoriteTitle"><a target="_blank" href="http://'.$w['website_url'] .'/'. $dataResults['postFilename'].'">'.stripslashes($dataResults['postTitle']).'</a></span><div class="favContent">'.stripslashes(substr(strip_tags($dataResults['postContent']), 0, 200)).'...</div></td>
                    <td><a class="actionButton actionView btn btn-success btn-block tmargin" target="_blank" href="http://'.$w['website_url'] .'/'. $dataResults['postFilename'].'">'.$label['view_label'].'</a><button class="actionButton actionDelete favoriteFeature btn btn-block btn-danger" data-favoriteuser="'.$dataResults['postUserId'].'" data-favoritefeaturetype="'.$dataType.'" data-favoritefeature="0" data-favoritedataid="'.$dataResults['postId'].'" data-favoritefeatureid="'.$dataResults['postDataId'].'">'.$label['delete_label'].'</button>
                        ';
                $html .='</td></tr>';
            }
            break;
        default:
            echo '<split><split>';
    }
    $html .= '</tbody></table></div><style>.dataTable.favoriteTable tr > td:nth-child(1),.dataTable tr > th:nth-child(1){width:20%}.dataTable.favoriteTable tr > td:nth-child(2),.dataTable tr > th:nth-child(2){width:35%}.dataTable.favoriteTable tr > td:nth-child(3),.dataTable tr > th:nth-child(3){width:15%}.dataTable.favoriteTable tr > td:nth-child(4),.dataTable tr > th:nth-child(4){width:30%}.dataTable.favoriteTable tr > td,.dataTable tr > th{padding:10px 20px}.dataTable.favoriteTable tr td img{max-height:150px}table.favoriteTable{width:100%}div.dataTables_filter input{font-weight:400;color:#333}table.favoriteTable thead{border-bottom:1px solid grey}table.favoriteTable thead tr > th:nth-child(2){text-align:left}span.favoriteTitle{color:#205081;display:block;font-size:20px;font-weight:700;text-align:left}.favContent{text-align:left}#favorites > div > span{display:block;width:100%}a.list-group-item.favoriteFeature{display:block}span.featureCount{display:inline-block;float:right}.favoriteTable thead {display: none;}.favoriteTable {margin-top: 10px;}</style>';
    echo '<split>'.$html.'<split>';
}
?>