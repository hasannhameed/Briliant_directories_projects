<?php
global $page;
if ($page['seo_type'] == "data_category" ) {
    echo widget("Bootstrap Theme - Display - Member Feature Search Results H1","",$w['website_id'],$w);
}

echo widget("Bootstrap Theme - HEAD", "", $w['website_id'], $w);
if (!empty($w['fb_app_id'])) {
    $facebookAppId = '&appId=' . $w['fb_app_id'];
}
?>
<body>
<?php
if (($_COOKIE['editmode'] == 1 || $_COOKIE['editmode'] == 2) && ( !isset($_ENV['admin_settings']['editor']) || $_ENV['admin_settings']['editor'] != 1) && (isset($_GET['designview']) || ( isset($_COOKIE['design']) && $_COOKIE['design'] == 1) )) {
    echo widget('Admin - Bootstrap Theme - Front End Design Settings','',$w['website_id'],$w);
}
$facebookChat = getAddOnInfo('facebook_chat','f60ec467c22b9d4319900131f88b671b');
$showFBChat = false;

if($w['fb_login_status'] == "1" && !empty($w['fb_app_id'])){
    $showFBChat = true;
}
if(addonController::isAddonActive('facebook_chat') && $w['facebook_page_id'] != "" && !$showFBChat){
    if($w['facebook_messenger_status'] == "1"){
        $showFBChat = true;
    } else if ($w['facebook_messenger_status'] == "2" && !checkIfMobile()) {
        $showFBChat = true;
    } else if ($w['facebook_messenger_status'] == "3" && checkIfMobile()){
        $showFBChat = true;
    }
}
if($showFBChat){ ?>
<!-- Facebook Javascript SDK -->
<div id="fb-root"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/<?php echo str_replace('-','_',$w['website_language']); ?>/sdk/xfbml.customerchat.js#xfbml=1&version=v10.0<?php echo $facebookAppId;?>";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<?php } ?>
<?php
if (addonController::isAddonActive('global_keyword_search') && $page['seo_type'] == 'home') {
   addonController::showWidget('global_keyword_search','7ca232c71a303b8eb13165b0c8753486');
}
if (isset($facebookChat['status']) && $facebookChat['status'] === 'success') {
    echo widget($facebookChat['widget'], '', $w['website_id'], $w);
}
echo widget('Bootstrap Theme - Google Tag Manager Code', '', $w['website_id'], $w);

if ((!isset($page['disable_css_stylesheets']) || $page['disable_css_stylesheets'] != "1") && $w['website_disable_css_stylesheets'] != "1"){
    echo widget('Bootstrap Theme - Custom Appearance Settings', '', $w['website_id'], $w);
}

if ($wa["website_css"] != "" || ( isset($page['content_css']) && $page['content_css'] != "") || ( isset($usetting['content_css']) && $usetting['content_css'] != "")) { ?>
    <!-- ON-PAGE CSS FUNCTIONS -->
    <style type="text/css">
        <?php
        if ($wa['website_css'] != "") {
            echo eval("?>".trim($wa['website_css'])."<?");
        }
        if ($page['content_css'] != "") {

            if ($_SERVER["HTTPS"] == "on") {
                $page['content_css'] = str_replace("//","/",str_replace('http://'.$w["website_url"],'',$page['content_css']));
            }
            echo eval("?>".trim($page['content_css'])."<?");
        }
        if ($usetting['content_css'] != "") {

            if ($_SERVER["HTTPS"] == "on") {
                $usetting['content_css'] = str_replace("//","/",str_replace('http://'.$w["website_url"],'',$usetting['content_css']));
            }
            echo eval("?>".trim($usetting['content_css'])."<?");
        } ?>
    </style>
    <?php
}

if (( !isset($page['hide_header']) || $page['hide_header'] != 1) && ( ( !isset($subscription['page_header']) || $subscription['page_header'] != 1) || ( ( isset($subscription['page_header']) && $subscription['page_header'] == 1 ) && $page['seo_type'] != 'profile')) && ( !isset($_GET['hide_header']) || $_GET['hide_header'] != 1)) {
    $header = widget("Bootstrap Theme - Header", "", $w['website_id'], $w);
    echo eval("?>" . replaceChars($w, $header) . "<?");
} ?>
<div class="clearfix"></div>
<!-- Begin  Content -->
	[widget=Custom - Mobile Login]
<div id="first_container" class="content-container fr-view">
    <?php
    if ($wa['custom_78'] == "1" && $page['seo_type'] == "home" && addonController::isAddonActive('homepage_background_slideshow')) {
        echo widget("Bootstrap Theme - Display - Background Slideshow", "", $w['website_id'], $w);
    } ?>
    <div class="container">
        <?php if($pars[0] == "account" && isset($_GET['error_code']) && $_GET['error_code'] == "1"){ ?>
            <div class="col-md-8 col-md-offset-2 tmargin">
                <div class="alert alert-danger tmargin text-center">
                    <p>
                        %%%post_do_not_belong_error%%%
                    </p>
                </div>
            </div>
        <?php } ?>
        <?php
        if ($pars[0] == "account" && ($user_data['pre_hold'] > 0 || ($user_data['active'] > 0 && $user_data['active'] != 2 && $user_data['active'] != ""))) {

            if ($user_data['active'] == 1) { ?>
                <div class="col-md-8 col-md-offset-2 tmargin">
                    <div class="alert alert-danger text-center">
                        <h4 class="text-warning bold">
                            <?php echo $label['account_not_activated_title']; ?>
                        </h4>
                        <p class="bmargin">
                            <?php echo $label['account_not_activated_text']; ?>
                        </p>
						
                        <a href="/account/resendverification" class="btn btn-primary btn-lg resend_verification_email">
                            <?php echo $label['account_not_activated_button']; ?>
                        </a>
                    </div>
                </div>
                <?php
            } else if ($user_data['active'] == 1 || $user_data['active'] == 3 || $user_data['active'] == 4 || $user_data['active'] == 5) {

                switch ( $user_data['active'] ) {
                    case NO_ACTIVE:
                        $accountStatusTitle     = $label["account_not_active_title"];
                        $accountStatusMessage   = $label["account_not_active_message"];
                        $accountNotActiveButton = $label["account_not_active_button"];
                        $accountNotActiveButtonURL = $label["account_not_active_button_url"];
                        break;

                    case CANCELLED:
                        $accountStatusTitle     = $label["account_cancelled_title"];
                        $accountStatusMessage   = $label["account_cancelled_message"];
                        $accountNotActiveButton = $label["account_cancelled_button"];
                        $accountNotActiveButtonURL = $label["account_cancelled_button_url"];
                        break;

                    case ON_HOLD:
                        $accountStatusTitle     = $label["account_on_hold_title"];
                        $accountStatusMessage   = $label["account_on_hold_message"];
                        $accountNotActiveButton = $label["account_on_hold_button"];
                        $accountNotActiveButtonURL = $label["account_on_hold_button_url"];
                        break;

                    case SUSPENDED:
                        $accountStatusTitle     = $label["account_suspended_title"];
                        $accountStatusMessage   = $label["account_suspended_message"];
                        $accountNotActiveButton = $label["account_suspended_button"];
                        $accountNotActiveButtonURL = $label["account_suspended_url"];
                        break;
                }

                ?>
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-danger tmargin text-center">
                        <h4 class="text-warning bold">
                            <?php echo $accountStatusTitle; ?>
                        </h4>
                        <p class="bmargin">
                            <?php echo $accountStatusMessage; ?>
                        </p>
						<a href="/account/upgrade" class="btn btn-primary btn-lg ">
                             Upgrade Listing
                        </a>
                        <?php
                        if ($subscription['profile_type'] != 'free') { ?>
                            <a href="<?php echo $accountNotActiveButtonURL; ?>"
                               class="btn btn-lg btn-primary"><?php echo $accountNotActiveButton; ?></a>
                        <?php } ?>
                    </div>
                </div>
                <?
            }
        }

        if ( isset($content_widget) && $content_widget != "") {
            $page['content'] = widget($content_widget, "", $w['website_id'], $w);
            $page['content'] =  replaceChars($w, $page['content'], true);

        } else if ( isset($sidebar_widget) && $sidebar_widget != "") {
            $page['content'] = sidebar($sidebar_widget, "", $w['website_id'], $w);

        } else if ( isset($content) && $content != "") {
            ob_start();
            include $content;
            $page['content'] = ob_get_contents();
            ob_end_clean();
        }
        $MemberSub = getSubscription($user['subscription_id'], $w);

        if ( isset($page['breadcrumbs']) && (( !isset($_ENV['no_search_results']) || $_ENV['no_search_results'] != 1) && ($page['breadcrumbs'] != "") && $pars[0] != "join" && $pars[0] != "getmatched" && (( isset($page['seo_type']) && $page['seo_type'] == 'profile' && $MemberSub['show_breadcrumbs'] != '0')) || (isset($page['seo_type']) && $page['seo_type'] != 'profile')) ) {
            
        } ?>
        <div class="clearfix body-content"></div>
        <?php echo websiteMessages($w);
        if (isset($_COOKIE['userid']) && $_COOKIE['userid'] > 0){ ?> <?php if ($user_data['active'] != 1) { 
            echo widget("Bootstrap Theme - Account - Sidebar", "", $w['website_id'], $w);
        }  } 
        if ($page["seo_type"] == "home") {
            echo widget("Bootstrap Theme - Homepage", "", $w['website_id'], $w);

        } else if ($pars[0] == "account") { ?>
		
		<?php if ($user_data['active'] != 1) { ?>
            <div class="col-sm-12 col-md-9 norpad sm-nopad member_accounts">
                <?php
                if ($subscription['show_dashboard_steps'] != "0") {
                    echo widget("Bootstrap Theme - Account - Checklist Wizard", "", $w['website_id'], $w);
                }
                echo $accountmenu . $page['content']; ?>
            </div>

            <?
		} } else {
            echo $page['content'];
        } ?>
        <div class="clearfix"></div>
    </div>
</div>
<!-- End Content -->
<?php
if ( isset($page['content_footer_html']) && $page['content_footer_html'] != "") {
    echo eval("?>".trim($page['content_footer_html'])."<?");
}
?>
<?

if ( (!isset($page['hide_footer']) || $page['hide_footer'] != 1) && ($subscription['page_footer'] != 1 || ($subscription['page_footer'] == 1 && $page['seo_type'] != 'profile')) && ( !isset($_GET['hide_footer']) || $_GET['hide_footer'] != 1) ) {
    echo widget("Bootstrap Theme - Footer", "", $w['website_id'], $w);
}else{
    global $usetting;
    if($usetting['user_id'] > 0){
        bd_controller::user(WEBSITE_DB)->get($usetting['user_id'],'user_id');
        echo bd_controller::user(WEBSITE_DB)->content_footer;
    }
}

echo widget("Bootstrap Theme - Footer - Scripts", "", $w['website_id'], $w);
echo $wa['website_footer'];
?>
</body>
</html>