<?php

if (@is_array($_ENV['json']) && $_ENV['json']['last_insert_id'] > 0) {
    $user = getUser($_ENV['json']['last_insert_id'], $w);
}
if (!empty($_POST['token'])) {
    $user['token'] = $_POST['token'];
}

if (!empty($_POST['subscription_id'])) {
    $subscription = getSubscription($_POST['subscription_id'], $w);
}

if ($_ENV['apitype'] == "json" || !empty($_POST['widget_name'])) {
    $json['result'] = "success";
    $json['message_title'] = "%%%congratulations_label%%%";
    ob_start();

    if (($subscription['profile_type'] == 'paid' && $w['auto_activate_paid_members'] != 1) || ($subscription['profile_type'] == 'free' && $w['auto_activate_free_members'] != 1)) {

        switch($subscription['auto_activate']){

            case "active":?>
                %%%create_listing_paid_label%%%
                <?php break;

            case "inactive": ?>
             %%%listing_created_label%%%  %%%listing_verify_email%%%
              <?php  break;

            case "hold_req_admin": ?>
             %%%account_awaiting_approval%%%
                <?php break;

            default: ?>
                 %%%listing_created_label%%%  %%%listing_verify_email%%%
               <?php  break;

        } ?>

        <br><br>
        <?php if ($label['listing_junk_mail'] != "" && $subscription['auto_activate'] == "inactive")  { ?>
            <span class="fpad bold h4 bg-danger img-rounded hpad hmargin inline-block">%%%listing_junk_mail%%% </span>
        <?php } ?>
    <?php }
    $express_registration_reload_url_exception_list = $w['express_registration_reload_url_exception_list'].",/".brilliantDirectories::getDefaultCheckoutUrl();
    $express_registration_reload_url_exception_list = trim($express_registration_reload_url_exception_list,",");
    $redirectTo = $subscription['signup_redirect'];
    $currentPath = (!empty($_POST['current_path'])) ? $_POST['current_path'] : $_SERVER['REQUEST_URI'];

    if( isset($_POST['url_origin_pars']) && $_POST['url_origin_pars'] != "" && $_POST['url_origin_pars'] != $currentPath){
       $currentPath =  $_POST['url_origin_pars'];
    }

    if ($w['express_registration_reload_url'] && !empty($express_registration_reload_url_exception_list)) {
        $autoReloadArray = explode(',', $express_registration_reload_url_exception_list);
        $parsURL = parse_url($currentPath, PHP_URL_PATH);
        $currentParse =  explode("/", $parsURL);

        if (!in_array('/' . $currentParse[1], $autoReloadArray) && !in_array($parsURL, $autoReloadArray)) {
            $redirectTo = $currentPath;
        }
    }
    ?>
    <a class="btn btn-success btn-lg bmargin bold" style="margin-top:15px;" href="<?php echo brilliantDirectories::getWebsiteUrl(); ?>/login/fromsignup/<?php echo brilliantDirectories::getEncryptedToken('encrypt',$user['token']); ?>?login_direct_url=<?php echo $redirectTo; ?>">
        %%%continue_to_listing%%% <i class="lmargin fa fa-chevron-right"></i>
    </a>
    <?php
        if(websiteSettingsController::canViewTrackingCode(websiteSettingsController::TRACKED_PURCHASE_PAID)){
            echo widget("Bootstrap Theme - Conversion Tracking Codes","",$w['website_id'],$w);
        }
    ?>
    <?php
    $json['message'] = ob_get_contents();
    ob_end_clean();
    if($_POST['header_type']=='html') {
        echo $json['message'];
        exit();
    }
    echo json_encode($json);

} else { ?>

    <div class="clearfix clearfix-lg"></div>
    <div class="col-md-8 col-md-offset-2">
        <div class="well text-center">
            <h1 class="pagehead bold">
                %%%congratulations_label%%%! %%%listing_created_label%%%
            </h1>
            <?php
            if ($_POST['active'] == 2) { ?>
                <h2 class="text-center">%%%listing_created_label%%% </h2>
            <?php } else { ?>
                <h4 class="text-center">
                <?php if($subscription['auto_activate'] != "hold_req_admin") { ?>
                    %%%listing_verify_email%%%
                <?php } else { ?>
                    %%%account_awaiting_approval%%%
                <?php }?>
                    <br><br>
                    <?php if ($label['listing_junk_mail'] != "" && $subscription['auto_activate'] != "hold_req_admin")  { ?>
                        <span class="fpad bold h4 bg-danger img-rounded hpad hmargin inline-block">%%%listing_junk_mail%%%</span>
                    <?php } ?>
                </h4>
            <?php }
            $express_registration_reload_url_exception_list = $w['express_registration_reload_url_exception_list'].",/".brilliantDirectories::getDefaultCheckoutUrl();
            $express_registration_reload_url_exception_list = trim($express_registration_reload_url_exception_list,",");
            $redirectTo = $subscription['signup_redirect'];
            
            $currentPath = (!empty($_POST['current_path'])) ? $_POST['current_path'] : $_SERVER['REQUEST_URI'];

            if( isset($_POST['url_origin_pars']) && $_POST['url_origin_pars'] != "" && $_POST['url_origin_pars'] != $currentPath){
                $currentPath =  $_POST['url_origin_pars'];
            }

            if ($w['express_registration_reload_url'] && !empty($express_registration_reload_url_exception_list)) {
                $autoReloadArray = explode(',', $express_registration_reload_url_exception_list);
                $parsURL = parse_url($currentPath, PHP_URL_PATH);
                $currentParse =  explode("/", $parsURL);
                if (!in_array('/' . $currentParse[1], $autoReloadArray) && !in_array($parsURL, $autoReloadArray)) {
                    $redirectTo = $currentPath;
                }
            }
            if(!empty($_POST['redirect'])){
                $redirectTo = $_POST['redirect'];
            }

            ?>
            <a class="btn btn-success btn-lg btn-block bmargin bold"
               href="<?php echo brilliantDirectories::getWebsiteUrl(); ?>/login/fromsignup/<?php echo brilliantDirectories::getEncryptedToken('encrypt',$user['token']); ?>?login_direct_url=<?php echo $redirectTo; ?>">
                %%%continue_to_listing%%% <i class="lmargin fa fa-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="clearfix clearfix-lg"></div>
    <?php
        if(websiteSettingsController::canViewTrackingCode(websiteSettingsController::TRACKED_PURCHASE_PAID)){
            echo widget("Bootstrap Theme - Conversion Tracking Codes", "", $w['website_id'], $w);
        }
    ?>
<?php } ?>