<?php

if (addonController::isAddonActive('members_only') && ((((strlen($listingFeature->always_on) == 1 && ($listingFeature->always_on == '0' || $listingFeature->always_on == '2')) || (strlen($listingFeature->always_on) > 1 && $listingFeature->always_on[1] == "1")))) && (!isset($_COOKIE['userid']) || $loggedUser['active'] != "2")){
    addonController::showWidget('members_only','389ffec8e86fc926655fab47a7b01a5a');
}else if(subscription_types_controller::__canSearchMember($_COOKIE['userid'],$user['subscription_id']) === false){ ?>
<!-- <div class="col-md-offset-2 col-md-8 well text-center h3 members-only-message">
    <h1>%%%member_not_public_or_active%%%</h1>
    <br>
    <hr>
    <a class="btn btn-lg btn-success bold back_to_previous_page" href="/" onclick="window.history.go(-1); return false;">
        %%%back_to_previous_page%%%
    </a>
</div> -->
[widget=Bootstrap Theme - Display - Members Only Message]
<?php }else{
global $statsUser;
$statsUser = $user;
echo widget("Bootstrap Theme - Member Profile - Statistics", '', $w['website_id'], $w);
if ($user['active'] != "2" || $subscription['searchable'] != 1) { ?>
    <div class="col-md-10 col-md-offset-1">
        <div class="alert alert-warning text-center tmargin">
            <h3>
                %%%profile_inactive%%%
            </h3>
            <p class="bmargin">
                %%%profile_inactive_text%%% <a href="%%%default_contact_us_url%%%">%%%contact_us%%%</a> %%%profile_restore_listing%%%.
            </p>
            <div class="clearfix"></div>
        </div>
    </div>
    <style>
        .breadcrumb {
            display: none;
        }
    </style>
<?php } else {
if ($message != "") {
    echo showMessage($message, 'good', 1);
}
if ($error != "") {
    $w['show_override'] = 1;
    echo showMessage($error, 'error', 1);
    echo "<br>";
} ?>

<div class="row content_w_sidebar member_profile">

    <?php if ($subscription['profile_sidebar'] != "" && $wa['custom_46'] != "2"){ 

        switch ($wa['custom_46']) {
            case "0":
                echo '<div class="col-md-9 col-md-push-3">';
                break;
            case "3":
                echo '<div class="col-md-8 col-md-push-4">';
                break;
            case "1":
                echo "<div class='col-md-9'>";
                break;
            case "4":
                echo "<div class='col-md-8'>";
                break;
        }  
        

    } else { ?>
        <div class="col-lg-12">
    <?php } 
        if ($subscription['profile_connection_banner_position'] == "above_profile_photo"){
            if($wa['profile_full_width_header'] == "1"){
                echo '</div> <div class="col-md-12 bmargin">';
                echo widget("Bootstrap Theme - Member Profile - Claim Listing Offer", '', $w['website_id'], $w);
            } else {
                echo widget("Bootstrap Theme - Member Profile - Claim Listing Offer", '', $w['website_id'], $w);
            }                    
        }
        if($wa['profile_full_width_header'] == "1" && $subscription['profile_connection_banner_position'] != "above_profile_photo"){
            echo '</div> <div class="col-md-12 bmargin">';
        }
        echo widget("Bootstrap Theme - Member Profile - Header", '', $w['website_id'], $w) ?>
        <div class="clearfix"></div>
            
        <?php if($wa['profile_full_width_header'] == "1"){ ?>
        <? if (!isset($subscription['profile_connection_banner_position']) || $subscription['profile_connection_banner_position'] == "below_profile_photo"){
            echo widget("Bootstrap Theme - Member Profile - Claim Listing Offer", '', $w['website_id'], $w);
        } ?>
            </div>
            <?php if($subscription['profile_sidebar'] != "" && $wa['custom_46'] != '2'){
                switch ($wa['custom_46']) {
                    case "0":
                        echo '<div class="col-md-9 col-md-push-3">';
                        break;
                    case "3":
                        echo '<div class="col-md-8 col-md-push-4">';
                        break;
                    case "1":
                        echo '<div class="col-sm-12 col-md-9">';
                        break;
                    case "4":
                        echo '<div class="col-sm-12 col-md-8">';
                        break;
                }
            } else { ?>
                <div class="col-md-12">
            <? } ?>  
        <?php } ?>
        
        <?php
        if ((!isset($subscription['profile_connection_banner_position']) || $subscription['profile_connection_banner_position'] == "below_profile_photo") && $wa['profile_full_width_header'] != "1"){
            echo widget("Bootstrap Theme - Member Profile - Claim Listing Offer", '', $w['website_id'], $w);
        }
        if ($w['enable_lazy_load'] != 0) {
            echo widget("Bootstrap Theme - Member Profile - Member Tabs lazyLoad", '', $w['website_id'], $w);
        } else {
            echo widget("Bootstrap Theme - Member Profile - Member Tabs", '', $w['website_id'], $w);
        } ?>
    </div>
    <?php 
    if($subscription['profile_sidebar'] != "" && $wa['custom_46'] != '2'){
        switch ($wa['custom_46']) {
            case "0":
                echo '<div class="col-md-3 col-md-pull-9">';
                break;
            case "3":
                echo '<div class="col-md-4 col-md-pull-8">';
                break;
            case "1":
                echo '<div class="col-sm-12 col-md-3">';
                break;
            case "4":
                echo '<div class="col-sm-12 col-md-4">';
                break;
        }  
        ?>
        <?php echo sidebar($subscription['profile_sidebar'], "", $w['website_id'], $w) ?>
        </div>
    <?php }?> 
</div>
<?php } ?>
<div class="clearfix"></div>
<?php }
$userPortfolioModel = new users_portfolio_groups();
$userPortfolioResults = $userPortfolioModel->get($user['user_id'],'user_id');
if($userPortfolioResults !== false && addonController::isAddonActive('albums_search_results_slider') && $wa['searchResultsCarousel'] != "0"){
    addonController::showWidget('albums_search_results_slider','fe6a39bb61505a6904e8a88673e52084','');
}
?>