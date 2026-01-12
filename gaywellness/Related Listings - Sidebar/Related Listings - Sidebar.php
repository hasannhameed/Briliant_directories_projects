<?php

/* Configurations */
$relatedListingsConfigurations = array(
    'membership_ids' => '1,2,3,6,7,8,9,10,11,12,13,14', /* Enter the list of membership IDs to show on the sidebar */
    'block_title' => 'Other Wellness Practitioners', /* Enter the title to use at the top of the block */
    'use_membership_level_filter' => 0, /* Show listings with the same membership level */
    'use_profession_id_filter' => 0, /* Show listings with the same profession */
    'use_service_ids_filter' => 0, /* Show listings with the same services */
    'use_subcategory_filter' => 0, /* Show listings with the same subcategories */
    'listing_type' => '', /* Enter Individual, Company or leave empty for both */
    'use_country_filter' => 0, /* Show listings from the same country */
    'use_state_filter' => 0, /* Show listings from the same state */
    'use_city_filter' => 1, /* Show listings from the same city */
    'number_of_listings' => 3, /* Limit the amount of results to show */
    'featured_only' => 0, /* Show featured listings only */
    'order_by' => 'RAND()', /* Possible uses: user_id DESC [latest listings first], user_id ASC [oldest listings first], RAND() [random order] */
    'template' => 3, /* Enter 1, 2 or 3 to use different layouts for the listings (4 for full body width) */
    'logo_background_type' => 'contain', /* Enter 'contain' to make the logos fit in the size of the box, or 'cover' to make the logos cover the lowest proportion of the box (recommended for directories with square logos). */
    'title_icon' => '', /* Enter the fontawesome icon to use for the title */
    'has_title_icon' => 1, /* Enter 1 or 0 to enable or disable the icon in the title of the block */
    'active_status' => 2, /* Leave blank to show all accounts. Enter 2 to show only activated accounts. */
    'verified_status' => "", /* Leave blank to show all verified/unverified accounts. Enter 1 to show only verified accounts. Enter 0 to show unverified accounts. */
    'full_width_mode' => 0, /* Enter 1 to leave no spacing on the sides and 0 to leave some spacing on the sides */
    'include_image' => 1, /* Enable the image display for listings on the sidebar. */
    'widget_class' => 'module', /* Enter the class which should be added to the HTML. Example: module */
    'widget_background_color' => 'white', /* Enter the background color to use for the element. */
    'widget_padding' => '10px 15px', /* Enter the padding to add to the contents of the element. Order: top right bottom left. Example: 5px 10px (5px top/bottom and 10px left/right) / 5px 10px 15px (5px top, 10px left/right, 15px bottom) / 5px 6px 7px 8px (5px top, 6px right, 7px bottom, 8px right. */
    'widget_title_padding' => '10px 15px', /* Enter the padding to add to the title of the element. Order: top right bottom left. Example: 5px 10px (5px top/bottom and 10px left/right) / 5px 10px 15px (5px top, 10px left/right, 15px bottom) / 5px 6px 7px 8px (5px top, 6px right, 7px bottom, 8px right. */
    'widget_title_icon_padding' => '0 10px 0 0', /* Enter the padding to add to the title's icon of the element. Order: top right bottom left. Example: 5px 10px (5px top/bottom and 10px left/right) / 5px 10px 15px (5px top, 10px left/right, 15px bottom) / 5px 6px 7px 8px (5px top, 6px right, 7px bottom, 8px right. */
    'widget_margin' => '35px 0 35px', /* Enter the margin to add to the element. Order: top right bottom left. Example: 5px 10px (5px top/bottom and 10px left/right) / 5px 10px 15px (5px top, 10px left/right, 15px bottom) / 5px 6px 7px 8px (5px top, 6px right, 7px bottom, 8px right. */
);

$categoryData = array();
$subscriptionID = $user['subscription_id']; /* Enter a specific membership type ID to show a specific type of members only */
$professionID = $user['profession_id']; /* Enter a specific profession ID to filter users by their profession */
$countryCode = $user['country_code']; /* Enter the country code to filter listings by country. */
$stateCode = $user['state_code']; /* Enter the country code to filter listings by country. */
$cityCode = $user['city']; /* Enter the country code to filter listings by country. */
$numberOfListings = $relatedListingsConfigurations['number_of_listings']; /* Enter the number of listings to show */
$listingType = ""; /* Enter Company or Individual if you want to filter the data */
$queryFilters = "";
$isActive = $relatedListingsConfigurations['active_status'];
$isVerified = $relatedListingsConfigurations['verified_status'];
$widgetClass = $relatedListingsConfigurations['widget_class'];
$widgetBackgroundColor = $relatedListingsConfigurations['widget_background_color'];
$widgetPadding = $relatedListingsConfigurations['widget_padding'];
$widgetMargin = $relatedListingsConfigurations['widget_margin'];
$widgetTitlePadding = $relatedListingsConfigurations['widget_title_padding'];
$widgetTitleIconPadding = $relatedListingsConfigurations['widget_title_icon_padding'];
$logoBackgroundType = $relatedListingsConfigurations['logo_background_type'];
$userID = $user['user_id'];
if ($subscriptionID > 0 && $relatedListingsConfigurations['use_membership_level_filter'] == 1) {
    $queryFilters .= " AND subscription_id = ".$subscriptionID;
}
if ($professionID > 0 && $relatedListingsConfigurations['use_profession_id_filter'] == 1) {
    $queryFilters .= " AND profession_id = $professionID";
}
if ($relatedListingsConfigurations['featured_only'] == 1) {
    $queryFilters .= " AND featured = 1";
}
if ($relatedListingsConfigurations['listing_type'] != "") {
    $queryFilters .= " AND listing_type = '".$relatedListingsConfigurations['listing_type']."'";
}
if ($relatedListingsConfigurations['use_country_filter'] == 1) {
    $queryFilters .= " AND country_code = '$countryCode'";
}
if ($relatedListingsConfigurations['use_state_filter'] == 1) {
    $queryFilters .= " AND state_code = '$stateCode'";
}
if ($relatedListingsConfigurations['use_city_filter'] == 1) {
    $queryFilters .= " AND city = '$cityCode'";
}
if ($isActive != "") {
    $queryFilters .= " AND active = $isActive";
}
if ($isVerified != "") {
    $queryFilters .= " AND verified = $isVerified";
}
if ($relatedListingsConfigurations['membership_ids'] != "") {
    $membershipIDs = $relatedListingsConfigurations['membership_ids'];
    $queryFilters .= " AND subscription_id IN ($membershipIDs)";
}
if ($relatedListingsConfigurations['use_service_ids_filter']) {
    $currentUserServices = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `rel_services` rserv
      LEFT JOIN `list_services` lserv on rserv.service_id = lserv.service_id
      WHERE user_id = $userID
      AND master_id = 0
    ");
    $currentUserServicesCount = mysql_num_rows($currentUserServices);
    if ($currentUserServicesCount > 0) {
        $queryFilters .= " AND user_id IN ( SELECT user_id FROM `rel_services` WHERE service_id IN ( ";
        $currentUserServiceIDs = array();
        while ($userServiceInfo = mysql_fetch_assoc($currentUserServices)) {
            $serviceID = $userServiceInfo['service_id'];
            $currentUserServiceIDs[] = $serviceID;
        }
        $currentServiceIDs = implode(", ", $currentUserServiceIDs);
        $queryFilters .= " $currentServiceIDs ) )";
    }
}
if ($relatedListingsConfigurations['use_subcategory_filter']) {
    $currentUserServices = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `rel_services` rserv
      LEFT JOIN `list_services` lserv on rserv.service_id = lserv.service_id
      WHERE user_id = $userID
      AND master_id > 0
    ");
    $currentUserServicesCount = mysql_num_rows($currentUserServices);
    if ($currentUserServicesCount > 0) {
        $queryFilters .= " AND user_id IN ( SELECT user_id FROM `rel_services` WHERE service_id IN ( ";
        $currentUserServiceIDs = array();
        while ($userServiceInfo = mysql_fetch_assoc($currentUserServices)) {
            $serviceID = $userServiceInfo['service_id'];
            $currentUserServiceIDs[] = $serviceID;
        }
        $currentServiceIDs = implode(", ", $currentUserServiceIDs);
        $queryFilters .= " $currentServiceIDs ) )";
    }
}
$orderBy = $relatedListingsConfigurations['order_by'];
$userDataQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * FROM `users_data` udata
  WHERE city != ''
  AND user_id != $userID 
  $queryFilters
  GROUP BY udata.user_id
  ORDER BY $orderBy
  LIMIT $numberOfListings
");
if ($userDataQuery !== false && mysql_num_rows($userDataQuery) > 0) { ?>
    <style>
        /* Global overrides */
        .related-listings.full-width-mode .widget-content {
            padding-left: 0px!important;
            padding-right: 0px!important;
        }
        <?php if ($widgetMargin != "") { ?>
        .related-listings {
            margin: <?php echo $widgetMargin; ?>!important;
        }
        <?php } ?>
        .related-listings.no-image-mode div.element867 div.element981.todo-thumbnail-area {
            display: none!important;
        }
        .related-listings.no-image-mode div.element867 div.element871.content-entry-wrap {
            max-width: 100%!important;
            flex: 0 0 100%!important;
        }
        /* Theme 1 */
        <?php if ($widgetBackgroundColor != "") { ?>
        .related-listings.theme-1 {
            background-color: <?php echo $widgetBackgroundColor; ?>;
        }
        <?php } ?>
        .related-listings.theme-1 div.element759 div.element862.widget.widget-recent-view-todo {
            background-color:#ffffff;
            margin-bottom:30px;
            border:1px solid #DDDDDD;
            box-shadow:3px 3px 11px rgba(0, 0, 0, 0.07);
        }
        .related-listings.theme-1 div.element862 div.element863.widget-title {
            margin-bottom:0;
            <?php if ($widgetTitlePadding != "") { ?>
            padding:<?php echo $widgetTitlePadding; ?>;
            <?php } ?>
            border-bottom:1px solid #DDDDDD;
            position:relative;
            font-size:18px;
            text-transform:capitalize;
            font-weight:600;
            display:flex;
            align-items:center;
        }
        <?php if ($widgetTitleIconPadding != "") { ?>
        .related-listings div.element862 div.element863.widget-title > .fa {
            padding: <?php echo $widgetTitleIconPadding; ?>;
        }
        <?php } ?>
        .related-listings.theme-1 div.element863 span.element864.fa.fa-eye {
            padding-right:20px;
            font-size:27px;
            color:#a7a7a7;
        }
        <?php if ($widgetPadding != "") { ?>
        .related-listings.theme-1 div.element862 div.element866.widget-content {
            padding: <?php echo $widgetPadding; ?>;
        }
        <?php } ?>
        .related-listings.theme-1 div.element866 div.element867.popular-todo-item {
            background-color:#fff;
            margin-bottom:30px;
            border:0 solid;
            box-shadow:0 0 0;
            display:flex;
            align-items:center;
            flex-wrap:wrap;
        }
        .related-listings.theme-1 div.element867 div.element981.todo-thumbnail-area {
            position:relative;
            overflow:hidden;
            max-width:105px;
            flex:0 0 105px;
        }
        .related-listings.theme-1 div.element867 div.element871.content-entry-wrap {
            max-width:calc(100% - 105px);
            flex:0 0 calc(100% - 105px);
        }
        .related-listings.theme-1 div.element871 div.element872.todo-content {
            padding:0px 0 0 20px;
        }
        .related-listings.theme-1 div.element872 div.element873.title {
            margin-bottom:8px;
            font-size:16px;
            font-weight:600;
            color:#000;
            line-height:1.2em;
        }
        .related-listings.theme-1 div.element873 a.element874 {
            background-color:transparent;
            font-size:16px;
            color:#000000;
            text-decoration:none;
            transition:color 0.25s ease;
            display:inline-block;
        }
        .related-listings.theme-1 div.element872 div.element992.todo-rating {
            margin-bottom:6px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
        }
        .related-listings.theme-1 div.element992 div.element993.rating-value {
            font-size:11px;
        }
        .related-listings.theme-1 div.element993 span.element994 {
            background-color:#5bb638;
            margin-right:10px;
            width:35px;
            color:#ffffff;
            text-align:center;
            display:inline-block;
        }
        .related-listings.theme-1 div.element996 ul.element997 {
            list-style:outside none none;
            margin-bottom:0;
            padding-left:0;
        }
        .related-listings.theme-1 ul.element997 li.element998.yellow {
            font-size:13px;
            display:inline-block;
        }
        .related-listings.theme-1 div.element872 div.element999.todo-meta {
            margin:0 -5px 18px;
            font-size:13px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
        }
        .related-listings.theme-1 div.element999 div.element1000.todo-location {
            margin:2px 5px;
        }
        .related-listings.theme-1 div.element1000 span.element1001.fa.fa-map-marker {
            margin-right:4px;
            display:inline-block;
        }
        .related-listings.theme-1 div.element999 div.element1003.todo-number {
            margin:2px 5px;
        }
        .related-listings.theme-1 div.element1003 span.element1004.fa.fa-phone {
            margin-right:4px;
            display:inline-block;
        }

        /* Theme 2 */
        <?php if ($widgetBackgroundColor != "") { ?>
        .related-listings.theme-2 {
            background-color: <?php echo $widgetBackgroundColor; ?>;
        }
        <?php } ?>
        .related-listings.theme-2 div.element759 div.element862.widget.widget-recent-view-todo {
            background-color:#ffffff;
            margin-bottom:30px;
            border:1px solid #DDDDDD;
            box-shadow:3px 3px 11px rgba(0, 0, 0, 0.07);
        }
        .related-listings.theme-2 div.element862 div.element863.widget-title {
            margin-bottom:0;
            <?php if ($widgetTitlePadding != "") { ?>
                padding:<?php echo $widgetTitlePadding; ?>;
            <?php } ?>
            border-bottom:1px solid #DDDDDD;
            position:relative;
            font-size:18px;
            text-transform:capitalize;
            font-weight:600;
            display:flex;
            align-items:center;
        }
        .related-listings.theme-2 div.element863 span.element864.fa.fa-eye {
            padding-right:20px;
            font-size:27px;
            color:#a7a7a7;
        }
        <?php if ($widgetPadding != "") { ?>
        .related-listings.theme-2 div.element862 div.element866.widget-content {
            padding: <?php echo $widgetPadding; ?>;
        }
        <?php } ?>
        .related-listings.theme-2 div.element866 div.element867.popular-todo-item {
            background-color:#fff;
            margin-bottom:30px;
            border:0 solid;
            box-shadow:0 0 0;
            display:flex;
            align-items:center;
            flex-wrap:wrap;
        }
        .related-listings.theme-2 div.element867 div.element981.todo-thumbnail-area {
            position:relative;
            overflow:hidden;
            max-width:100%;
            flex:0 0 100%;
            margin-bottom:15px;
        }
        .related-listings.theme-2 div.element867 div.element871.content-entry-wrap {
            max-width:100%;
            flex:0 0 100%;
        }
        .related-listings.theme-2 div.element871 div.element872.todo-content {
            padding:0px 0 0 20px;
        }
        .related-listings.theme-2 div.element872 div.element873.title {
            margin-bottom:8px;
            font-size:16px;
            font-weight:600;
            color:#000;
            line-height:1.2em;
        }
        .related-listings.theme-2 div.element873 a.element874 {
            background-color:transparent;
            font-size:16px;
            color:#000000;
            text-decoration:none;
            transition:color 0.25s ease;
            display:inline-block;
        }
        .related-listings.theme-2 div.element872 div.element992.todo-rating {
            margin-bottom:6px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
        }
        .related-listings.theme-2 div.element992 div.element993.rating-value {
            font-size:11px;
        }
        .related-listings.theme-2 div.element993 span.element994 {
            background-color:#5bb638;
            margin-right:10px;
            width:35px;
            color:#ffffff;
            text-align:center;
            display:inline-block;
        }
        .related-listings.theme-2 div.element996 ul.element997 {
            list-style:outside none none;
            margin-bottom:0;
            padding-left:0;
        }
        .related-listings.theme-2 ul.element997 li.element998.yellow {
            font-size:13px;
            display:inline-block;
        }
        .related-listings.theme-2 div.element872 div.element999.todo-meta {
            margin:0 -5px 0px;
            font-size:13px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
        }
        .related-listings.theme-2 div.element999 div.element1000.todo-location {
            margin:2px 5px;
        }
        .related-listings.theme-2 div.element1000 span.element1001.fa.fa-map-marker {
            margin-right:4px;
            display:inline-block;
        }
        .related-listings.theme-2 div.element999 div.element1003.todo-number {
            margin:2px 5px;
        }
        .related-listings.theme-2 div.element1003 span.element1004.fa.fa-phone {
            margin-right:4px;
            display:inline-block;
        }

        /* Theme 3 */
        <?php if ($widgetBackgroundColor != "") { ?>
        .related-listings.theme-3 {
            background-color: <?php echo $widgetBackgroundColor; ?>;
        }
        <?php } ?>
        .related-listings.theme-3 div.element759 div.element862.widget.widget-recent-view-todo {
            background-color:#ffffff;
            margin-bottom:30px;
            border:1px solid #DDDDDD;
            box-shadow:3px 3px 11px rgba(0, 0, 0, 0.07);
        }
        .related-listings.theme-3 div.element862 div.element863.widget-title {
            margin-bottom:0;
            <?php if ($widgetTitlePadding != "") { ?>
                padding:<?php echo $widgetTitlePadding; ?>;
            <?php } ?>
            border-bottom:1px solid #DDDDDD;
            position:relative;
            font-size:18px;
            text-transform:capitalize;
            font-weight:600;
            display:flex;
            align-items:center;
        }
        .related-listings.theme-3 div.element863 span.element864.fa.fa-eye {
            padding-right:20px;
            font-size:27px;
            color:#a7a7a7;
        }
        <?php if ($widgetPadding != "") { ?>
        .related-listings.theme-3 div.element862 div.element866.widget-content {
            padding: <?php echo $widgetPadding; ?>;
        }
        <?php } ?>
        .related-listings.theme-3 div.element866 div.element867.popular-todo-item {
            background-color: transparent;
            padding-bottom: 15px;
            margin-bottom: 15px;
            padding-top: 15px;
            margin-top: 15px;
            border: 0 solid;
            box-shadow: 0 0 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            border-color: #dee2e6;
            border-bottom-width: 1px;
        }
        .related-listings.theme-3 div.element867 div.element981.todo-thumbnail-area {
            position:relative;
            overflow:hidden;
            max-width:105px;
            flex:0 0 105px;
        }
        .related-listings.theme-3 div.element867 div.element871.content-entry-wrap {
            max-width:calc(100% - 105px);
            flex:0 0 calc(100% - 105px);
        }
        .related-listings.theme-3 div.element871 div.element872.todo-content {
            padding:0px 0 0 20px;
        }
        .related-listings.theme-3 div.element872 div.element873.title {
            margin-bottom:8px;
            font-size:16px;
            font-weight:600;
            color:#000;
            line-height:1.2em;
        }
        .related-listings.theme-3 div.element873 a.element874 {
            background-color:transparent;
            font-size:16px;
            color:#000000;
            text-decoration:none;
            transition:color 0.25s ease;
            display:inline-block;
        }
        .related-listings.theme-3 div.element872 div.element992.todo-rating {
            margin-bottom:6px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
        }
        .related-listings.theme-3 div.element992 div.element993.rating-value {
            font-size:11px;
        }
        .related-listings.theme-3 div.element993 span.element994 {
            background-color:#5bb638;
            margin-right:10px;
            width:35px;
            color:#ffffff;
            text-align:center;
            display:inline-block;
        }
        .related-listings.theme-3 div.element996 ul.element997 {
            list-style:outside none none;
            margin-bottom:0;
            padding-left:0;
        }
        .related-listings.theme-3 ul.element997 li.element998.yellow {
            font-size:13px;
            display:inline-block;
        }
        .related-listings.theme-3 div.element872 div.element999.todo-meta {
            margin:0 -5px 18px;
            font-size:13px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
        }
        .related-listings.theme-3 div.element999 div.element1000.todo-location {
            margin:2px 5px;
        }
        .related-listings.theme-3 div.element1000 span.element1001.fa.fa-map-marker {
            margin-right:4px;
            display:inline-block;
        }
        .related-listings.theme-3 div.element999 div.element1003.todo-number {
            margin:2px 5px;
        }
        .related-listings.theme-3 div.element1003 span.element1004.fa.fa-phone {
            margin-right:4px;
            display:inline-block;
        }

        /* Theme 4 */
        <?php if ($widgetBackgroundColor != "") { ?>
        .related-listings.theme-4 {
            background-color: <?php echo $widgetBackgroundColor; ?>;
        }
        <?php } ?>
        .related-listings.theme-4 div.element759 div.element862.widget.widget-recent-view-todo {
            background-color:#ffffff;
            margin-bottom:30px;
            border:1px solid #DDDDDD;
            box-shadow:3px 3px 11px rgba(0, 0, 0, 0.07);
        }
        .related-listings.theme-4 div.element862 div.element863.widget-title {
            margin-bottom:0;
        <?php if ($widgetTitlePadding != "") { ?>
            padding:<?php echo $widgetTitlePadding; ?>;
        <?php } ?>
            border-bottom:1px solid #DDDDDD;
            position:relative;
            font-size:18px;
            text-transform:capitalize;
            font-weight:600;
            display:flex;
            align-items:center;
        }
        .related-listings.theme-4 div.element863 span.element864.fa.fa-eye {
            padding-right:20px;
            font-size:27px;
            color:#a7a7a7;
        }
        <?php if ($widgetPadding != "") { ?>
        .related-listings.theme-4 div.element862 div.element866.widget-content {
            padding: <?php echo $widgetPadding; ?>;
        }
        <?php } ?>
        .related-listings.theme-4 div.element866 div.element867.popular-todo-item {
            background-color: transparent;
            padding-bottom: 15px;
            margin-bottom: 15px;
            padding-top: 15px;
            margin-top: 15px;
            border: 0 solid;
            box-shadow: 0 0 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            border-color: #dee2e6;
            border-bottom-width: 1px;
        }
        .related-listings.theme-4 div.element867 div.element981.todo-thumbnail-area {
            position:relative;
            overflow:hidden;
            max-width:105px;
            flex:0 0 105px;
        }
        .related-listings.theme-4 div.element867 div.element871.content-entry-wrap {
            max-width:calc(100% - 105px);
            flex:0 0 calc(100% - 105px);
        }
        .related-listings.theme-4 div.element871 div.element872.todo-content {
            padding:0px 0 0 20px;
        }
        .related-listings.theme-4 div.element872 div.element873.title {
            margin-bottom:8px;
            font-size:16px;
            font-weight:600;
            color:#000;
            line-height:1.2em;
        }
        .related-listings.theme-4 div.element873 a.element874 {
            background-color:transparent;
            font-size:16px;
            color:#000000;
            text-decoration:none;
            transition:color 0.25s ease;
            display:inline-block;
        }
        .related-listings.theme-4 div.element872 div.element992.todo-rating {
            margin-bottom:6px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
        }
        .related-listings.theme-4 div.element992 div.element993.rating-value {
            font-size:11px;
        }
        .related-listings.theme-4 div.element993 span.element994 {
            background-color:#5bb638;
            margin-right:10px;
            width:35px;
            color:#ffffff;
            text-align:center;
            display:inline-block;
        }
        .related-listings.theme-4 div.element996 ul.element997 {
            list-style:outside none none;
            margin-bottom:0;
            padding-left:0;
        }
        .related-listings.theme-4 ul.element997 li.element998.yellow {
            font-size:13px;
            display:inline-block;
        }
        .related-listings.theme-4 div.element872 div.element999.todo-meta {
            margin:0 -5px 18px;
            font-size:13px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            flex-wrap:wrap;
        }
        .related-listings.theme-4 div.element999 div.element1000.todo-location {
            margin:2px 5px;
        }
        .related-listings.theme-4 div.element1000 span.element1001.fa.fa-map-marker {
            margin-right:4px;
            display:inline-block;
        }
        .related-listings.theme-4 div.element999 div.element1003.todo-number {
            margin:2px 5px;
        }
        .related-listings.theme-4 div.element1003 span.element1004.fa.fa-phone {
            margin-right:4px;
            display:inline-block;
        }

    </style>
    <div class="related-listings theme-<?php echo $relatedListingsConfigurations['template']; ?> <?php echo ($relatedListingsConfigurations['full_width_mode'] == 1) ? 'full-width-mode' : ''; ?> <?php echo ($relatedListingsConfigurations['include_image'] == 0) ? 'no-image-mode' : ''; ?> <?php echo $widgetClass; ?>">
        <div class="widget widget-recent-view-todo element862">
            <div class="widget-title element863">
                <?php if ($relatedListingsConfigurations['has_title_icon'] == 1) { ?>
                    <span class="fa fa-<?php echo $relatedListingsConfigurations['title_icon']; ?> element864 "></span>
                <?php } ?>
                <h4>

				<span class=" element865"><?php echo $relatedListingsConfigurations['block_title']; ?></span></h4>
            </div>
            <div class="widget-content element866">
                <?php
                while ($userInfo = mysql_fetch_assoc($userDataQuery)) {
                    $userID = $userInfo['user_id'];
                    $userDetails = strip_tags($userInfo['about_me'], "<br>");
                    if (strlen($userDetails) > 350)
                        $userDetails = substr($userDetails, 0, 350) . '...';
                    $p['user_id'] = $userID;
                    $userPhoto = getUserPhoto($userInfo['user_id'], $userInfo['listing_type'], $w);
                    $userPhoto = $userPhoto['file'];
                    $userPhone = !empty($userInfo['phone_number'])?$userInfo['phone_number']:'N/A';
                    $userProfileURL = $userInfo['filename'];
                    $userCompany = ($userInfo['company']!="")?$userInfo['company']:"&nbsp;";
                    $userCountry = $userInfo['country_ln'];
                    $userLocation = $userInfo['city'].", ".$userInfo['country_code'];
                    $userLocationLong = $userInfo['address1'].", ".$userInfo['city'];
                    $userLat = $userInfo['lat'];
                    $userLon = $userInfo['lon'];
                    $userMapURL = "https://maps.google.com/maps?q=$userLat,$userLon";
                    $userQuote = "";
                    $userWebsite = $userInfo['website'];
                    $userTwitter = $userInfo['twitter'];
                    $userYoutube = $userInfo['youtube'];
                    $userFacebook = $userInfo['facebook'];
                    $userIsFeatured = $userInfo['featured']==1?true:false;
                    $userTag = "";
                    if ($userIsFeatured) {
                        $userTag = "Featured";
                    }
                    if ($userInfo['quote']) {
                        $userQuote = $userInfo['quote'];
                        if (strlen($userQuote) > 280) {
                            $userQuote = substr($userQuote, 0, 280)."...";
                        }
                    }
                    $cardDisplayName = $userInfo['first_name']." ".$userInfo['last_name'];
                    $professionName = getProfession($userInfo['profession_id'], $w);
                    $companyName = $userInfo['company'];
                    if ($userInfo['listing_type'] == 'Company') {
                        $cardDisplayName = $companyName;
                        if (strlen($companyName) == 0) {
                            $cardDisplayName = $userInfo['first_name']." ".$userInfo['last_name'];
                        }
                    } else {
                        $cardDisplayName = $userInfo['first_name']." ".$userInfo['last_name'];
                    }
                    /* Load ratings information */
                    $userProfileRatingsSQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * 
                      FROM `users_reviews`
                      WHERE `user_id` = '$userID'
                    ");
                    $reviewCount = 0;
                    $reviewAverage = 0;
                    if ($userProfileRatingsSQL !== false && mysql_num_rows($userProfileRatingsSQL) > 0) {
                        $reviewCount = mysql_num_rows($userProfileRatingsSQL);
                        while ($userReviewInfo = mysql_fetch_assoc($userProfileRatingsSQL)) {
                            $reviewAverage += intval($userReviewInfo['rating_overall']);
                        }
                        $reviewAverage = $reviewAverage/$reviewCount;
                        $reviewAverage = number_format((float)$reviewAverage, 2, '.', '');
                    } else {
                        $reviewAverage = "N/A";
                    }
                    /* Load the photos information */
                    $userPhotosSQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT * 
                      FROM `users_portfolio`
                      WHERE `user_id` = '$userID'
                    ");
                    $userPicturesCount = 0;
                    if ($userProfileRatingsSQL !== false && mysql_num_rows($userProfileRatingsSQL) > 0) {
                        $userPicturesCount = mysql_num_rows($userProfileRatingsSQL);
                    }
                    if ($relatedListingsConfigurations['template'] == 1) { ?>
                        <div class="popular-todo-item element867">
                            <div class="todo-thumbnail-area element981">
                                <a href="/<?php echo $userProfileURL; ?>">
                                    <figure class="item-thumb element982 "    style="background:white url('<?php echo $userPhoto; ?>') no-repeat center center;
                                            margin:0;
                                            min-height:120px;
                                            position:relative;
                                            overflow:hidden;
                                            background-size: <?php echo $logoBackgroundType; ?>;
                                            "></figure>
                                </a>
                            </div>
                            <div class="content-entry-wrap element871">
                                <div class="todo-content element872">
                                    <div class="title element873">    <a class=" element874 "   href="/<?php echo $userProfileURL; ?>" ><?php echo $cardDisplayName; ?></a>
                                    </div>
                                    <div class="todo-rating element992">
                                        <div class="rating-value element993">    <span class=" element994 "><?php echo $reviewAverage; ?></span>
                                            <span class=" element995 "><?php echo $reviewCount; ?> Ratings</span>
                                        </div>
                                        <div class="rating-icon element996">
                                            <ul class=" element997 ">
                                                <?php
                                                $reviewStarCount = 1;
                                                while ($reviewStarCount <= 4) {
                                                    $reviewColor = "";
                                                    if ($reviewStarCount <= $reviewAverage) {
                                                        $reviewColor = "#FFB400";
                                                    }
                                                    $reviewStarCount++;
                                                    ?>
                                                    <li class="yellow element998 "   <?php if (!empty($reviewColor)) { ?>style="color: <?php echo $reviewColor; ?>"<?php } ?>>$</li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="todo-meta element999">
                                        <div class="todo-location element1000">
                                            <a href="<?php echo $userMapURL; ?>" target="_blank">
                                                <span class="fa fa-map-marker element1001 "></span>
                                            </a>
                                            <span class=" element1002 "><?php echo $userLocation; ?></span>
                                        </div>
                                        <div class="todo-number element1003">    <span class="fa fa-phone element1004 "></span>
                                            <span class=" element1005 "><?php echo $userPhone; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else if ($relatedListingsConfigurations['template'] == 2) { ?>
                        <div class="popular-todo-item element867">
                            <div class="todo-thumbnail-area element981">
                                <a href="/<?php echo $userProfileURL; ?>">
                                    <figure class="item-thumb element982 "    style="background:white url('<?php echo $userPhoto; ?>') no-repeat center center;
                                            margin:0;
                                            min-height:120px;
                                            position:relative;
                                            overflow:hidden;
                                            background-size: <?php echo $logoBackgroundType; ?>;;
                                            "></figure>
                                </a>
                            </div>
                            <div class="content-entry-wrap element871">
                                <div class="todo-content element872">
                                    <div class="title element873">
                                        <a class="element874" href="/<?php echo $userProfileURL; ?>" ><?php echo $cardDisplayName; ?></a>
                                    </div>
                                    <div class="todo-rating element992">
                                        <div class="rating-value element993">
                                            <span class=" element994 "><?php echo $reviewAverage; ?></span>
                                            <span class=" element995 "><?php echo $reviewCount; ?> Ratings</span>
                                        </div>
                                        <div class="rating-icon element996">
                                            <ul class=" element997 ">
                                                <?php
                                                $reviewStarCount = 1;
                                                while ($reviewStarCount <= 4) {
                                                    $reviewColor = "";
                                                    if ($reviewStarCount <= $reviewAverage) {
                                                        $reviewColor = "#FFB400";
                                                    }
                                                    $reviewStarCount++;
                                                    ?>
                                                    <li class="yellow element998 "   <?php if (!empty($reviewColor)) { ?>style="color: <?php echo $reviewColor; ?>"<?php } ?>><i class="fa fa-star"></i></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="todo-meta element999">
                                        <div class="todo-location element1000">
                                            <a href="<?php echo $userMapURL; ?>" target="_blank">
                                                <span class="fa fa-map-marker element1001 "></span>
                                            </a>
                                            <span class=" element1002 "><?php echo $userLocation; ?></span>
                                        </div>
                                        <div class="todo-number element1003">    <span class="fa fa-phone element1004 "></span>
                                            <span class=" element1005 "><?php echo $userPhone; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else if ($relatedListingsConfigurations['template'] == 3) { ?>
                        <div class="popular-todo-item element867">
                            <div class="todo-thumbnail-area element981">
                                <a href="/<?php echo $userProfileURL; ?>">
                                    <figure class="item-thumb element982"  style="background:white url('<?php echo $userPhoto; ?>') no-repeat center center;
                                            margin:0;
                                            min-height:90px;
                                            position:relative;
                                            overflow:hidden;
                                            background-size:<?php echo $logoBackgroundType; ?>;;
                                            "></figure>
                                </a>
                            </div>
                            <div class="content-entry-wrap element871">
                                <div class="todo-content element872">
                                    <div class="title element873">    <a class=" element874 "   href="/<?php echo $userProfileURL; ?>" ><?php echo $cardDisplayName; ?></a>
                                    </div>
                                    <div class="todo-rating element992">
                                        <div class="rating-value element993">    <span class=" element994 "><?php echo $reviewAverage; ?></span>
                                            <span class=" element995 "><?php echo $reviewCount; ?> Ratings</span>
                                        </div>
                                    </div>
                                    <div class="todo-meta element999">
                                        <div class="todo-location element1000">
                                            <a href="<?php echo $userMapURL; ?>" target="_blank">
                                                <span class="fa fa-map-marker element1001 "></span>
                                            </a>
                                            <span class=" element1002 "><?php echo $userLocationLong; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else if ($relatedListingsConfigurations['template'] == 4) { ?>
                        <div class="popular-todo-item element867">
                            <div class="todo-thumbnail-area element981">
                                <a href="/<?php echo $userProfileURL; ?>">
                                    <figure class="item-thumb element982"  style="background:white url('<?php echo $userPhoto; ?>') no-repeat center center;
                                            margin:0;
                                            min-height:90px;
                                            position:relative;
                                            overflow:hidden;
                                            background-size:<?php echo $logoBackgroundType; ?>;;
                                            "></figure>
                                </a>
                            </div>
                            <div class="content-entry-wrap element871">
                                <div class="todo-content element872">
                                    <div class="title element873">
                                        <a class=" element874 "   href="/<?php echo $userProfileURL; ?>" ><?php echo $cardDisplayName; ?></a>
                                    </div>
                                    <div class="description">
                                        <?php echo $userDetails; ?>
                                    </div>
                                    <div class="todo-rating element992">
                                        <div class="rating-value element993">    <span class=" element994 "><?php echo $reviewAverage; ?></span>
                                            <span class=" element995 "><?php echo $reviewCount; ?> Ratings</span>
                                        </div>
                                    </div>
                                    <div class="todo-meta element999">
                                        <div class="todo-location element1000">
                                            <a href="<?php echo $userMapURL; ?>" target="_blank">
                                                <span class="fa fa-map-marker element1001 "></span>
                                            </a>
                                            <span class=" element1002 "><?php echo $userLocationLong; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } } ?>
            </div>
            <div class='clearfix'></div>
        </div>
    </div>
<?php } ?>