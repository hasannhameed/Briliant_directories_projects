[widget=seo_front_end]
<?php

echo widget("portfolio_email","",$w[website_id],$w);
echo widget("Email Verification Status","",$w[website_id],$w);
global $page;
if ($page['seo_type'] != "data_post" ) {
    echo widget("Bootstrap Theme - Display - Member Feature Search Results H1","",$w['website_id'],$w);
}

echo widget("Bootstrap Theme - HEAD", "", $w['website_id'], $w);
if (!empty($w['fb_app_id'])) {
    $facebookAppId = '&appId=' . $w['fb_app_id'];
}
?>
<body>

<?php
if (($_COOKIE['editmode'] == 1 || $_COOKIE['editmode'] == 2) && $_ENV['admin_settings']['editor'] != 1 && (isset($_GET['designview']) || $_COOKIE['design'] == 1)) {
    echo widget('Admin - Bootstrap Theme - Front End Design Settings','',$w['website_id'],$w);
}
$facebookChat = getAddOnInfo('facebook_chat','f60ec467c22b9d4319900131f88b671b');
?>
<!-- Facebook Javascript SDK -->
<div id="fb-root"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v3.2<?php echo $facebookAppId;?>";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<?php
if (isset($facebookChat['status']) && $facebookChat['status'] === 'success') {
    echo widget($facebookChat['widget'], '', $w['website_id'], $w);
}
echo widget('Bootstrap Theme - Google Tag Manager Code', '', $w['website_id'], $w);

if ($wa[website_css] != "" || $page['content_css'] != "" || $usetting['content_css'] != "") { ?>
    <!-- ON-PAGE CSS FUNCTIONS -->
    <style type="text/css">
        <?php
        if ($wa['website_css'] != "") {
            echo eval("?>".trim($wa['website_css'])."<?");
        }
        if ($page['content_css'] != "") {

            if ($_SERVER[HTTPS] == "on") {
                $page['content_css'] = str_replace("//","/",str_replace('http://'.$w[website_url],'',$page['content_css']));
            }
            echo eval("?>".trim($page['content_css'])."<?");
        }
        if ($usetting['content_css'] != "") {

            if ($_SERVER[HTTPS] == "on") {
                $usetting['content_css'] = str_replace("//","/",str_replace('http://'.$w[website_url],'',$usetting['content_css']));
            }
            echo eval("?>".trim($usetting['content_css'])."<?");
        } ?>
    </style>
    <?php
}
echo widget('Bootstrap Theme - Custom Appearance Settings', '', $w['website_id'], $w);

if ($page['hide_header'] != 1 && ($subscription['page_header'] != 1 || ($subscription['page_header'] == 1 && $page['seo_type'] != 'profile')) && $_GET['hide_header'] != 1) {
    $header = widget("Bootstrap Theme - Header", "", $w['website_id'], $w);
    echo eval("?>" . replaceChars($w, $header) . "<?");
} ?>
<div class="clearfix"></div>
<!-- Begin  Content -->
<div id="first_container" class="content-container fr-view">
    <?php
    if ($wa['custom_78'] == "1" && $page['seo_type'] == "home" && addonController::isAddonActive('homepage_background_slideshow')) {
        echo widget("Bootstrap Theme - Display - Background Slideshow", "", $w['website_id'], $w);
    } ?>
    <div class="container">
        <?php
        if ($pars[0] == "account" && ($user_data['pre_hold'] > 0 || ($user_data['active'] > 0 && $user_data['active'] != 2 && $user_data['active'] != "" && $user_data['active'] != "5"))) {

            if ($user_data['active'] == 1 && $user_data[email_verify]==0) { ?>
                <div class="col-md-8 col-md-offset-2 tmargin">
                    <div class="alert alert-danger text-center">
                        <h4 class="text-warning bold">
                            <?php echo $label['account_not_activated_title']; ?>
                        </h4>
                        <p class="bmargin" style="text-align:left;">
                           An email was sent to you from this website with the subject "[Action Required] Please verify your email - AndroidDevelopers.Co" when you signed up. Click the verify link in that email to activate your account or click the button below to resend it. 
                        </p>
                        <p class="bmargin" style="text-align:left;">
                            Post your email is been verified, our Partnerships Manager would review your profile and will contact you for more details or to verify details.
                        </p>
                        <a href="/account/resendverification" class="btn btn-primary btn-lg">
                            <?php echo $label['account_not_activated_button']; ?>
                        </a>
                      
                    </div>
                </div>
                <?php
            } else if($user_data[active] == 1 && $user_data[email_verify]== 1){?>
            
<div class="col-md-8 col-md-offset-2 tmargin">
                    <div class="alert alert-danger text-center">
                        <h4 class="text-warning bold">
                                Your Profile is Pending Review and Not Yet Active!
                            </h4>
                            <p class="bmargin" style="text-align:left;">
                                Our team must review your profile to ensure the highest quality on our site.  Common reasons profiles are rejected or sent back for editing are:<br>
                            </p>
                            <div class="row">
                            <div class="col-sm-12" style="padding:0px;">
                           <ol style="text-align:left;padding: 0px 29px;">
                       <li> Profile is not for a Mobile App Development Company/Agency/an Individual Mobile app developer.</li>
                        <li>Profile name contains incorrect information.Only a real names are permitted.</li>
                         <li>Profile contains incorrect information.Ex: Los Angeles App Development company in the company name field.Only the real / legal name of a developer / development company or agency is permitted.</li>  
                         <ol>
                          
                           </div></div>
                             <p style="text-align:left;">
One of our Partnerships Manager will contact you to get more information as needed and we are excited to see you joining this community.</p>
                    </div>
                </div>
            <? }



            else if ($user_data['active'] == 1 || $user_data['active'] == 3 || $user_data['active'] == 4 || $user_data['pre_hold'] > 0) { ?>
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-danger tmargin text-center">
                        <h4 class="text-warning bold">
                            %%%account_not_active_title%%%
                        </h4>
                        <p class="bmargin">
                            %%%account_not_active_message%%%
                        </p>
                        <?php
                        if ($subscription['profile_type'] != 'free') { ?>
                          <a href="/account/billing"
                           class="btn btn-lg btn-primary">%%%account_not_active_button%%%</a>
                        <?php } ?>
                    </div>
                </div>
                <?
            }
        }
        if ($content_widget != "") {
            $page[content] = widget($content_widget, "", $w['website_id'], $w);

        } else if ($sidebar_widget != "") {
            $page[content] = sidebar($sidebar_widget, "", $w['website_id'], $w);

        } else if ($content != "") {
            ob_start();
            include $content;
            $page['content'] = ob_get_contents();
            ob_end_clean();
        }
        $MemberSub = getSubscription($user['subscription_id'], $w);

        if ($_ENV['no_search_results'] != 1 && $page['breadcrumbs'] != "" && $pars[0] != "join" && $pars[0] != "getmatched") {
            echo $page['breadcrumbs'];
        } ?>
        <div class="clearfix body-content"></div>
        <?php echo websiteMessages($w);
        if ($_COOKIE['userid'] > 0){
            echo widget("Bootstrap Theme - Account - Sidebar", "", $w['website_id'], $w);
        }
        if ($page[seo_type] == "home") {
            echo widget("Bootstrap Theme - Homepage", "", $w['website_id'], $w);

        } else if ($pars[0] == "account") { 
        		if($pars[2]=="home"){
        		    echo widget("dashboard-initialization-plugin","",$w[website_id],$w);
        		}
        	?>

            <div class="col-sm-12 col-md-9 nopad member_accounts">
                <?php
                if ($subscription['show_dashboard_steps'] != "0") {
                    echo widget("Bootstrap Theme - Account - Checklist Wizard", "", $w['website_id'], $w);
                }
                  if($pars[0]=="account" && $pars[1]=="team"){
                    echo widget("Team Bulit","", $w[website_id], $w);
                    }
else{
                echo $accountmenu . $page['content']; }?>
            </div>
<?
            
            if($pars[1]=="portfolio" && $pars[2]=="approved-live"){
                    echo widget("Approved And Live", "", $w[website_id], $w);
                    }  else if($pars[1]=="portfolio" && $pars[2]=="edit-required"){
                    echo widget("Edit Required", "", $w[website_id], $w);
                    } else if($pars[1]=="portfolio" && $pars[2]=="rejected"){
                    echo widget("Rejected", "", $w[website_id], $w);
                    } else if($pars[1]=="portfolio" && $pars[2]=="draft"){
                    echo widget("Portfolio Draft", "", $w[website_id], $w);
                    } else if($pars[0]=="account" && $pars[1]=="portfolio" && $pars[2]=="share-for-a-review"){
                    echo widget("Share For Review", "", $w[website_id], $w);
                    }
                   
        } else {
            echo $page[content];
        } ?>
        <div class="clearfix"></div>
    </div>
</div>
<!-- End Content -->
<?php
if ($page['content_footer_html'] != "") {
    echo eval("?>".trim($page['content_footer_html'])."<?");
}
?>

<?
if ($page['hide_footer'] != 1 && ($subscription['page_footer'] != 1 || ($subscription['page_footer'] == 1 && $page['seo_type'] != 'profile')) && $_GET['hide_footer'] != 1) {
    echo widget("Bootstrap Theme - Footer", "", $w['website_id'], $w);
}
echo widget("Bootstrap Theme - Footer - Scripts", "", $w['website_id'], $w); 
echo $wa['website_footer'];
?>
</body>
</html>