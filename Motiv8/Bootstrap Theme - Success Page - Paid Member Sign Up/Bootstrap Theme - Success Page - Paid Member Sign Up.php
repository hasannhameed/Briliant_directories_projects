<?php if (@is_array($_ENV['json']) && $_ENV['json']['last_insert_id']>0) {
    $user=getUser($_ENV['json']['last_insert_id'],$w);
    $b=getUserBilling($user['user_id'],$_ENV['billing_id'],$w);
    $subscription=getSubscription($user['subscription_id'],$w);
    
    //check if the new subscription variable for redirect is empty (those cases when subscription is not saved)
	if (empty($subscription['signup_redirect'])) {
		$subscription['signup_redirect'] = $subscription['login_redirect'];
	}
    
    $slash = substr($subscription['signup_redirect'], 0, 1);
    
    if($slash != '/'){
        $subscription['signup_redirect'] = '/'.$subscription['signup_redirect'];
    }
}
if ($_ENV['apitype']=="json") {
    $json['result']="success";
    $json['message_title']="%%%congratulations_label%%%";
    ob_start();

    if($subscription['auto_activate'] == "hold_req_admin"){ ?>
        %%%payment_successful_label%%% %%%account_awaiting_approval%%%
	<?php } else if ($subscription['auto_activate'] == "inactive") { ?>
		  %%%payment_successful_label%%%  %%%listing_verify_email%%% 
	<?php }else if(addonController::isAddonActive('free_trials') && $subscription['enable_free_trial_period'] != 0 && !empty($subscription['enable_free_trial'])) { ?>
        %%%payment_successful_free_trial%%%
    <?php } else { ?> 
        %%%payment_successful_label%%% %%%create_listing_paid_label%%%
    <?php } 
     if(!empty($_POST['supplier_redirect'])){
        $subscription['signup_redirect'] = $_POST['supplier_redirect'];
        $applic_msg = 'Back to Registration';//Go to the Application
    }else{
        $applic_msg ='%%%continue_to_listing%%%'; 
    }
    ?>
    <p>
    <a class="btn btn-success btn-lg text-uppercase bmargin bold" style="margin-top:15px;"  href="<?php echo brilliantDirectories::getWebsiteUrl(); ?>/login/fromsignup/<?php echo brilliantDirectories::getEncryptedToken('encrypt',$user['token']); ?>?login_direct_url=<?php echo $subscription['signup_redirect']; ?>">
    <?php echo $applic_msg; ?> <i class="lmargin fa fa-chevron-right"></i>
    </a>
    <?php
        if(websiteSettingsController::canViewTrackingCode(websiteSettingsController::TRACKED_PURCHASE_PAID)){
            echo widget("Bootstrap Theme - Conversion Tracking Codes","",$w['website_id'],$w);
        }
    ?>
    <?php
    $json['message']=ob_get_contents();
    ob_end_clean();
    echo json_encode($json);

} else {
    ?>
    <div class="clearfix clearfix-lg"></div>
    <div class="col-md-8 col-md-offset-2">
        <div class="well text-center">
            <h1 class="pagehead bold">%%%payment_successful_label%%%</h1>
            <?php if($subscription['auto_activate'] != "hold_req_admin") { ?>
                <h2>%%%create_listing_paid_label%%%</h2>
            <?php } else { ?>
                <h2>%%%account_awaiting_approval%%%</h2>
            <?php } 
             if(!empty($_POST['supplier_redirect'])){
                $subscription['signup_redirect'] = $_POST['supplier_redirect'];
                $applic_msg = 'Back to Registration';//Go to the Application
            }else{
                $applic_msg ='%%%continue_to_listing%%%'; 
            }
            ?>
            <hr>
            <a class="btn btn-success btn-lg text-uppercase bmargin bold" href="<?php echo brilliantDirectories::getWebsiteUrl(); ?>/login/fromsignup/<?php echo brilliantDirectories::getEncryptedToken('encrypt',$user['token']); ?>?login_direct_url=<?php echo $subscription['signup_redirect']; ?>">
                <?php echo $applic_msg; ?> <i class="lmargin fa fa-chevron-right"></i>
            </a>
        </div>
    </div>
    <div class="clearfix clearfix-lg"></div>

    <?php
        if(websiteSettingsController::canViewTrackingCode(websiteSettingsController::TRACKED_PURCHASE_PAID)){
            echo widget("Bootstrap Theme - Conversion Tracking Codes",'',$w['website_id'],$w);
        }
    ?>
<?php } ?>
