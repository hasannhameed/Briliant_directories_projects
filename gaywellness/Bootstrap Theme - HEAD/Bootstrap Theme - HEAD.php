<?php
//Redirects and Setup
//Members Only Rule
if (isset($_COOKIE['userid']) && $_COOKIE['userid'] > 0) {
    $checkUserActiveQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
            active
        FROM
            `users_data`
        WHERE
            user_id = '".$_COOKIE[userid]."'");

    if (mysql_num_rows($checkUserActiveQuery) > 0) {
        $checkUserActive = mysql_fetch_assoc($checkUserActiveQuery);

        if ($checkUserActive['active'] == 3) {
            $loginStatus = 0;
        } else {
            $loginStatus = 1;
        }

    } else {
        $loginStatus = 0;
    }
    if ($loginStatus == 0) {
        echo '<script>window.location="/logout";</script>';
        exit;
    }
}

// Set protocol
$https_on = 0;

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    $http = 'https://';
    $https_on = 1;

} else {
    $http = 'http://';
    $https_on = 0;
}
// Set Membership Feature Currency
if ($w['member_feature_currency'] != "") {
    $w['currency_symbol'] = html_entity_decode( $w['member_feature_currency'], ENT_QUOTES, 'UTF-8');
}
//// Set all of the most appropriate meta data sources
// Set Follow or NoFollow
$meta_robots = 'index, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large';

if(is_object($post)){
    $post = (array)$post;
}

if  (( isset($page['show_form']) && $page['show_form']== "1") || $w['website_active']== 2 || $user['active']== "0" || $user['active']== "1" || $user['active']== "3" || $user['active']== "4" || ($subscription['index_rule'] == 1 && $page['seo_type'] == 'profile') || (isset($page['content_order']) && $page['content_order'] != 0) || ( isset($page['content_footer']) && $page['content_footer'] == 1) || (isset($post) && !empty($post) && $post['post_status'] != '1') || (isset($group) && !empty($group) && $group['group_status'] != '1')|| array_key_exists("page", $_GET)) {
    $meta_robots = 'noindex, nofollow';

} else if (isset($page['meta_robots']) && $page['meta_robots'] != '') {
    $meta_robots = $page['meta_robots'];

} else {
    $meta_robots = $w['meta_robots'];
}
// Set Canonical Link
$app_uri = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

if ($pos_get = strpos($app_uri, '?')) {
    $app_uri = substr($app_uri, 0, $pos_get);
}
$app_uri = str_replace(".php","",$app_uri);
$app_uri = str_replace(".html","",$app_uri);
$app_uri = str_replace(".htm","",$app_uri);

if ($pars[0] == 'home') {
    $meta_canonical = '<link rel="canonical" href="' . $http . $_SERVER['HTTP_HOST'] . '/home">';

} else if ($page['filename'] != '') {

    if ($page['template_id'] == 203 && $w['reduce_canon_url_cat'] == 0) {
        $page['filename'] = $pars[0] . "/" . $pars[1];
    }

    if (array_key_exists("page", $_GET)) {
        $page['filename'] = $page['filename']."?page=".htmlspecialchars($_GET['page']);
    }
    if ($page['seo_type'] == "payment") {
        $meta_canonical = '<link rel="canonical" href="' . $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '">';

    } else {
        $meta_canonical = '<link rel="canonical" href="' . $http . $_SERVER['HTTP_HOST'] . '/' . $page['filename'] . '">';
    }

} else if (isset($app_uri) && $page['filename'] == '') {

    if (array_key_exists("page", $_GET)) {
        $app_uri = $app_uri."?page=".htmlspecialchars($_GET['page']);
    }
    $meta_canonical = '<link rel="canonical" href="' . $app_uri . '">';
}
// Favicon
if ($w['favicon']!='') {
    $meta_fav_url = $w['favicon'];

} else if (file_exists('/home/$w[website_user]/public_html/favicon.ico')) {
    $meta_fav_url = '/favicon.ico';

}else if ($https_on == 1 && $w['favicon'] == '') {
    $meta_fav_url = '/images/secure-favicon.ico';

} else {
    $meta_fav_url = 'data:;base64,iVBORw0KGgo=';
}
$meta_fav = '<link rel="preload" as="image" href="' . $meta_fav_url . '"><link rel="shortcut icon" href="' . $meta_fav_url . '">';
//// Set all of the most appropriate social media sources
// Social media URL
if ($page['seo_type'] == 'home') {
    $og_url = $http . $_SERVER['HTTP_HOST'];

} else if ($page['filename'] != "") {
    if ($page['seo_type'] == "payment") {
        $og_url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    } else {
        if(count($_GET['category']) == 1){
            $page['filename'] = $page['filename'].'?category[]='.$_REQUEST['category'][0];
        }
        $og_url = $http . $_SERVER['HTTP_HOST'] . '/' . $page['filename'];
    }
} else if ($fb['url'] == '') {
    $og_url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

} else {
    $og_url = $fb['url'];
}
// Social media title
if ($page['facebook_title'] != '') {
    $og_title = $page['facebook_title'];

} else if ($group['group_name'] != '' && $page['seo_type'] == "photo_group_profile") {
    $og_title = $group['group_name'];

} else if ($post['post_title'] != '' && $page['seo_type'] == "data_post") {
    $og_title = $post['post_title'];

} else if ($page['title'] != '') {
    $og_title = $page['title'];

} else if ($page['h1'] != '') {
    $og_title = $page['h1'];
}
// Social media description
if ($post['post_meta_description'] != '') {
	$og_desc = $post['post_meta_description'];
	
} else if ($page['facebook_desc'] != '') {
    $og_desc = $page['facebook_desc'];

} else if ($group['group_desc'] != '') {
    $og_desc = $group['group_desc'];

} else if ($post['post_content'] != '') {
    $og_desc = $post['post_content'];

} else {
    $og_desc = $page['meta_desc'];
}
// Clean social media description
$og_desc = stripslashes(str_replace('"', '', $og_desc));
// Social media sharing image
$og_outside_image = 0;


if($w['profile_share_cover_photo_priority'] == '1' && $user['cover_photo'] != '' && addonController::isAddonActive('profile_cover_photo') && $page['template_id'] == '100'){
    $og_image = $user['cover_photo'];
} else if ( isset($page['facebook_image']) && $page['facebook_image'] != '') {
    $og_image = $page['facebook_image'];

} else if ($user['listing_type'] == 'Company' && $user['logo_file'] != '' && $page['seo_type'] == 'profile') {
    $og_image = $user['logo_file'];
    //check if the new images implementation has been done
    $checkDataFlowsTableQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),'SELECT
            1
        FROM
            `image_settings`
        LIMIT
            1');

    if ($checkDataFlowsTableQuery !== FALSE) {
        $checkPath = str_replace("/profile/","/social_media/",$user['logo_file']);

        //check if social media version exists
        if (file_exists("/home/".$w['website_user']."/public_html".$checkPath)) {
            $og_image = $checkPath;
        }
    }
} else if ($user['listing_type'] == 'Individual' && $user['photo_file'] != '' && $page['seo_type'] == 'profile') {

    $og_image = $user['photo_file'];
    //check if the new images implementation has been done
    $checkDataFlowsTableQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),'SELECT
            1
        FROM
            `image_settings`
        LIMIT
            1');

    if ($checkDataFlowsTableQuery !== FALSE) {
        $checkPath = str_replace("/profile/","/social_media/",$user['photo_file']);

        //check if social media version exists
        if (file_exists("/home/".$w['website_user']."/public_html".$checkPath)) {
            $og_image = $checkPath;
        }
    }

} else if ($photo['file'] != '' && $page['seo_type'] == 'profile') {
    $og_image = $photo['file'];

    //check if the new images implementation has been done
    $checkDataFlowsTableQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),'SELECT
            1
        FROM
            `image_settings`
        LIMIT
            1');

    if ($checkDataFlowsTableQuery !== FALSE) {
        $checkPath = str_replace("/profile/","/social_media/",$photo['file']);

        //check if social media version exists
        if (file_exists("/home/".$w['website_user']."/public_html".$checkPath)) {
            $og_image = $checkPath;
        }
    }

} else if ($post['post_image'] != '') {
    $og_image = $post['post_image'];

} else if ($post['post_video'] != '') {
    $video_link = $post['post_video'];
    $video_url = str_replace( 'https://', 'http://', $video_link );
    $urlDomain = parse_url($video_url);
    if (strpos($urlDomain['host'], "youtube") !== false) {
        $cart =  substr($video_url , 31);
        $video_img = "https://img.youtube.com/vi/$cart/0.jpg";
    }
    if (strpos($urlDomain['host'], "youtu.be") !== false) {
        $cart =  substr($video_url , 16);
        $video_img = "https://img.youtube.com/vi/$cart/0.jpg";
    }
    if (strpos($urlDomain['host'], "vimeo") !== false) {  //vimeo
        $cart = explode("/",$video_url);
        $cartLength = count($cart) - 1;
        $imgid = $cart[$cartLength];
        $url = "https://vimeo.com/api/v2/video/".$imgid.".json";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $curlData = curl_exec($curl);
        curl_close($curl);
        $hash = current(json_decode($curlData, true));
        $video_img = $hash['thumbnail_medium'];
    }
    $og_image = $video_img;
    $og_outside_image = 1;

} else if ($group['group_id'] != '') {
    $group_image_query = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
            `file`
        FROM
            `users_portfolio`
        WHERE
            `group_id` = '".$group['group_id']."'
        AND 
            `user_id` = '".$group['user_id']."'
        AND 
            `data_id` = '".$group['data_id']."'
        AND
            `file` != ''
        ORDER BY
            `order` ASC
        LIMIT
            1");
    $group_image = mysql_fetch_assoc($group_image_query);
    $og_image = '/' . $w['photo_folder'] . '/main/' . $group_image['file'];

} else if (isset($_ENV['results_loop_image'][0]) && $_ENV['results_loop_image'][0] != '') {
    $og_image = '/' . $w['photo_folder'] . '/main/' . $_ENV['results_loop_image'][0];

} else if ($p['file'] != '') {
    $og_image = '/' . $w['photo_folder'] . '/main/' . $p['file'];

} else if ($w['facebook_app_id'] != '') {
    $og_image = $w['facebook_app_id'];

} else if ($wa['custom_82'] != '' && $wa['custom_78'] == '1'&& addonController::isAddonActive('homepage_background_slideshow')) {
    $og_image = $wa['custom_82'];

} else if ($wa['custom_76'] != '') {
    $og_image = $wa['custom_76'];

} else if ($w['website_logo'] != '') {
    $og_image = $w['website_logo'];

} else {
    $og_image = '/images/slide2.jpg';
}




//Custom meta data for post details pages
if ($post['post_meta_description'] != "" && $pars[1] != '' && $page['seo_type'] != 'profile') {
    $page['meta_desc'] = $post['post_meta_description'];
} else if ($group['post_meta_description'] != "" && $pars[1] != '' && $page['seo_type'] != 'profile') {
    $page['meta_desc'] = $group['post_meta_description'];
}

if ($post['post_meta_title'] != "" && $pars[1] != '' && $page['seo_type'] != 'profile') {
    $page['title'] = $post['post_meta_title'];
} else if ($group['post_meta_title'] != "" && $pars[1] != '' && $page['seo_type'] != 'profile') {
    $page['title'] = $group['post_meta_title'];
}

if ($post['post_meta_keywords'] != "" && $pars[1] != '' && $page['seo_type'] != 'profile') {
    $page['meta_keywords'] = $post['post_meta_keywords'];
} else if ($group['post_meta_keywords'] != "" && $pars[1] != '' && $page['seo_type'] != 'profile') {
    $page['meta_keywords'] = $group['post_meta_keywords'];
}




// Clean the image URL
if ($og_outside_image != 1) {
    $og_image = str_replace('http://','',$og_image);
    $og_image = str_replace($w['website_url'],'',$og_image);
    $og_image = $http . $_SERVER['HTTP_HOST'] . $og_image;
}

$textDirection = 'ltr';
if(strtolower($w['text_direction']) == 'right to left'){
    $textDirection = 'rtl';
}

// Preload Homepage Hero Image
if ($page['seo_type'] == 'home' && $wa['custom_76'] != '' && $wa['custom_77'] == '1') {
    $preload_hero = $wa['custom_76'];
// Profile Cover Photo
} else if ($page['template_id'] == '100' && $user['cover_photo'] != '' && $subscription['coverPhoto'] == '1') {	
	$preload_hero = $user['cover_photo'];
// Profile Photo
} else if ($page['template_id'] == '100') {
	$preload_hero = $og_image;
// Static Web Page Hero
} else if ($page['seo_type'] == 'content' && $page['hero_image'] != '' && $page['enable_hero_section'] != '0') {
    $preload_hero = $page['hero_image'];
} else {
    $preload_hero = '';
}
if(!isset($wa['announcement_bar_addon_ffamily'])){
	$wa['announcement_bar_addon_ffamily'] = array();
}



//Custom no index for certain post pages
if ($dc['data_id'] == 7 || $dc['data_id'] == 8 || $dc['data_id'] == 10 || $dc['data_id'] == 11 || $dc['data_id'] == 13 || $dc['data_id'] == 72 || $dc['data_id'] == 74 || $dc['data_id'] == 75 || $dc['data_id'] == 76 || $dc['data_id'] == 77  ) {
  $meta_robots = 'noindex, nofollow';
}


// Google Fonts
$websiteFonts = array_unique(array_filter(array($wa['custom_3'], $wa['custom_13'], $wa['custom_27'], $wa['custom_68'], $wa['custom_208'], $wa['announcement_bar_addon_ffamily'], $wa['streaming_title_family'])));
$websiteFonts = implode($websiteFonts, ':300,400,600,700,800,900|');
$websiteFonts .=':300,400,600,700,800,900';
$websiteFonts = preg_replace("/ /", "+", $websiteFonts);
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $textDirection;?>" lang="<?php echo substr($w['website_language'],0,2); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=1">
	<?php echo $meta_fav; ?>	
	
    <!-- Site Meta Data -->
    <?php
    //we decode title in case is encode
    $filenameBdString = new bdString($page['title']);
    $filenameBdString->urldecode();
    $page['title'] = $filenameBdString->modifiedValue;
    $page['title'] = htmlentities(html_entity_decode(strip_tags($page['title'])),ENT_COMPAT, 'UTF-8');

    //we decode the og:title in case is encode
    $filenameBdString->originalValue = $og_title;
    $filenameBdString->modifiedValue = $og_title;
    $filenameBdString->rollBack();
    $filenameBdString->urldecode();
    $og_title = $filenameBdString->modifiedValue;
    $og_title = htmlentities(html_entity_decode($og_title), ENT_COMPAT, 'UTF-8');
    ?>
<title><?php echo $page['title']; ?></title>    
    <meta name="description" content="<?php echo strip_tags($page['meta_desc']); ?>">
    <meta name="keywords" content="<?php echo strip_tags($page['meta_keywords']); ?>">
    <meta name="robots" content="<?php echo $meta_robots; ?>">
	
    <!-- Social Media Meta Data -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $og_url; ?>">
    <meta property="og:site_name" content="<?php echo $w['website_name']; ?>">
    <meta property="og:title" content="<?php echo $og_title; ?>">
    <meta property="og:description" content="<?php echo strip_tags($og_desc); ?>">
    <meta property="og:image" content="<?php echo $og_image; ?>">
	
	<!-- Canonical and Rel Links -->
    <?php
    echo $meta_canonical;

    if(isset($rel_links['prev']))
        echo $rel_links['prev'];

    if(isset($rel_links['next']))
        echo $rel_links['next'];
    $rssFeedAddon = getAddOnInfo('rss_feed');
    if (isset($rssFeedAddon['status']) && $rssFeedAddon['status'] === 'success') { ?>
	
	<link rel="alternate" type="application/rss+xml" title="<?php echo $w['website_name']; ?> RSS Feed" href="<?php echo $http.$w['website_url']; ?>/rss" />
	<?php } ?>
	
	<!-- Preconnect -->
	<span style="position:absolute;z-index:-1;color:transparent;bottom:0;left:0;"><?php echo $w['website_name']; ?></span>		
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php 
    /// Preconnect CDN
	//// Get the list of CDN resources for images and JS/CSS resources and add preconnect tag
	$preConnectCdn = brilliantDirectories::cdnUrl('preconnect');
	if($preConnectCdn != "") { echo $preConnectCdn; } 
    ?>
	
	<link rel="preload" as="style" href="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/css/critical-styles.pkgd.min.css">
	<?php if ($preload_hero != '') { ?>
<link rel="preload" as="image" href="<?php echo $preload_hero; ?>">
	<?php } if ($w['website_logo'] != '') { ?>
<link rel="preload" as="image" href="<?php echo $w['website_logo']; ?>">
	<?php } ?>
	
	<!-- Critical JS -->
    <?php echo widget("Bootstrap Theme - HEAD - JS - Libraries");

    if (( isset($page['disable_css_stylesheets']) && $page['disable_css_stylesheets'] != '' && $page['disable_css_stylesheets'] == "1") || ($w['website_disable_css_stylesheets'] != '' && $w['website_disable_css_stylesheets'] == "1")) {
        $noPageStylesheets = true;
    } if (!isset($noPageStylesheets) || !$noPageStylesheets) { ?>
<!-- Non-Critical Stylesheet -->
	<?php if ($pars[0] == "account") {  ?>
<link rel="stylesheet" href="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/css/non-critical-styles.pkgd.min.css">
	<?php } ?>
<noscript>
		<link rel="stylesheet" href="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/css/non-critical-styles.pkgd.min.css">
	</noscript>
	
	<!-- Critical Stylesheet -->
	<link id="critical-styles" rel="stylesheet" href="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/css/critical-styles.pkgd.min.css">
	<?php } ?>
	
	<!-- Google Fonts -->
	<link rel="preload" as="style" onload="this.rel='stylesheet'" href="https://fonts.googleapis.com/css?family=<?php echo $websiteFonts; ?>&display=swap">
	<noscript>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=<?php echo $websiteFonts; ?>&display=swap">
	</noscript>	
    <?php 
    // Add custom HEAD content
    $custom_head = '';

    if ($wa['website_head'] != '') {
        echo '<!-- Custom Site-wide HEAD Content -->';
        echo eval("?>".trim($wa['website_head'])."<?") . '';
    }
    if (isset($page['content_head']) && $page['content_head']!='') {
        echo '<!-- Custom Page HEAD Content -->';
        echo eval("?>".trim($page['content_head'])."<?") . '';
    }
    if (isset($usetting['content_head']) && $usetting['content_head']!='') {
        echo '<!-- Custom Content HEAD Content -->';
        echo @replaceChars($w, trim($usetting['content_head']));
    } ?>
	[widget=Bootstrap Theme - Function - Google Location Suggest]
</head>
<?php

// Code that redirects members to the next active tab in their member management settings.
if(isset($pars[1])){
    $locationCheck = $pars[0].'/'.$pars[1];
}else{
    $locationCheck = $pars[0].'/';
}

if (is_null($subscription['show_contact_detail'])) {
    $subscription['show_contact_detail'] = 1;
    $subscription['show_logo_upload'] = 1;
    $subscription['show_profile_photo'] = 1;
    $subscription['show_listing_details'] = 1;
    $subscription['show_about_tab'] = 1;
    $subscription['location_limit'] = 0;
}
$contactTab = $subscription['show_contact_detail'];
$listingTab = $subscription['show_listing_details'];
$aboutTab = $subscription['show_about_tab'];

if ($subscription['location_limit'] == '' || $subscription['location_limit'] == 0) {
    $serviceTab = 0;

} else {
    $serviceTab = 1;
}
if ($subscription['show_logo_upload'] == 0 && $subscription['show_profile_photo'] == 0) {
    $photoTab = 0;

} else {
    $photoTab = 1;
}

if ($locationCheck == "account/profile") {

    if ($photoTab == 0) {

        if ($listingTab == 1) {
            header("Location: /account/resume");
            exit;

        } else if ($aboutTab == 1) {
            header("Location: /account/about");
            exit;

        } else if ($serviceTab == 1) {
            header("Location: /account/locations");
            exit;

        } else {
            header("Location: /account/home");
            exit;
        }
    }
}
if ($locationCheck == "account/resume") {

    if ($listingTab == 0) {

        if ($aboutTab == 1) {
            header("Location: /account/about");
            exit;

        } else if ($serviceTab == 1) {
            header("Location: /account/locations");
            exit;

        } else {
            header("Location: /account/home");
            exit;
        }
    }
}
if ($locationCheck == "account/about") {

    if ($aboutTab == 0) {

        if ($serviceTab == 1) {
            header("Location: /account/locations");
            exit;

        } else {
            header("Location: /account/home");
            exit;
        }
    }
}
if ($locationCheck == "account/locations") {

    if ($serviceTab == 0) {
        header("Location: /account/home");
        exit;
    }
} ?>