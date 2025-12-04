<?php
$me = getUser($_COOKIE['userid'], $w);
$subscription = getSubscription($me['subscription_id'], $w);
$memberCanView = false;
if ($me['token'] != "") {
    $data_array = explode(",", $subscription['data_settings_read']);
    if (in_array($dc['data_id'], $data_array) && $me['active'] == "2") {
        $memberCanView = true;
    }
}
$membersOnlyAddOn = getAddOnInfo('members_only', '389ffec8e86fc926655fab47a7b01a5a');
$membersOnlyActivated = false;

if (isset($membersOnlyAddOn['status']) && $membersOnlyAddOn['status'] === 'success') {
    $membersOnlyActivated = true;
}
$hidePost = false;
if(strlen($dc['always_on']) > 1){
    if($dc['always_on'][0] == "2" && $dc['always_on'][1] == "1"){
        $dc['always_on'] = "2";
    }
    if($dc['always_on'][0] == "2" && $dc['always_on'][1] == "2"){
        $dc['always_on'] = "1";
    }
    if($dc['always_on'][0] == "1" && $dc['always_on'][1] == "2"){
        $dc['always_on'] = "3";
    }
    if($dc['always_on'][0] == "1" && $dc['always_on'][1] == "1"){
        $dc['always_on'] = "0";
    }
}
if (!$memberCanView && ($dc['always_on'] != "1" && $dc['always_on'] != "3") && $membersOnlyActivated) {
    $hidePost = true;
    if ($hidePost && $me['user_id'] == $user['user_id'] && $me['active'] == "2") {
        $hidePost = false;
    }
}
if ($hidePost) {
    echo widget($membersOnlyAddOn['widget'], '', $w['website_id'], $w);

} else { ?>
    <?php 
        if(isset($user['active']) && $user['active'] != ACTIVE){
            echo widget("Bootstrap Theme - Post Page No Active User","",$w['website_id'],$w);
        }
    ?>
    <div class="row content_w_sidebar photo_albums">
    <?php
    if ($error != "" && $error != "success") {
        echo showMessage($error, 'error', 1);

    } else if ($error == "success") {
        echo showMessage('Thank you. Your Message has been sent.', 'good', 1);
    }
    if($dc['profile_sidebar'] != "" && $wa['custom_48'] != '2'){
        switch ($wa['custom_48']) {
            case "3":
                echo "<div class='col-md-9 col-md-push-3'>";
                break;
            case "0":
                echo "<div class='col-md-8 col-md-push-4'>";
                break;
            case "4":
                echo "<div class='col-md-9'>";
                break;
            case "1":
                echo "<div class='col-md-8'>";
                break;
        } ?>
    <?php } else{ ?>
        <div class="col-lg-12">
    <?php }
    echo eval("?>" . replaceChars($w, $dc['search_results_layout']) . "<?");
   	
	if ($w['enable_post_comments'] == "1") {
        addonController::showWidget('post_comments','edf6a434e514be0513ad265a71872271');
    }
	
    if ($dc['comments_code'] != "") {
        echo eval("?>" . $dc['comments_code'] . "<?");
    }
    ?>
    </div>
    <?php
    if($dc['profile_sidebar'] != "" && $wa['custom_48'] != '2'){
        switch ($wa['custom_48']) {
            case "3":
                echo '<div class="col-md-3 col-md-pull-9">';
                break;
            case "0":
                echo '<div class="col-md-4 col-md-pull-8">';
                break;
            case "4":
                echo '<div class="col-sm-12 col-md-3">';
                break;
            case "1":
                echo '<div class="col-sm-12 col-md-4">';
                break;
        }  
        echo sidebar($dc['profile_sidebar'], $user, $w['website_id'], $w); ?>
        </div>
    <?php } ?>
    </div>
    <div class="clearfix clearfix-lg photo-album-page-end page-end"></div>
    <?php
} ?>
<?php echo widget("Bootstrap Theme - Banner - Photo Albums Ad");?>