<?php
global $subscriptionData, $subscrpitionLimits, $subscriptionCategoryLimit, $membersTotalPostQuery;

$subscriptionData           = getSubscription($user_data['subscription_id'], $w);
$subscriptionLimits         = json_decode($subscriptionData['data_settings_limit'], true);
$subscriptionCategoryLimit  = $subscriptionLimits[$dc['data_id']];
$haveCredit                 = true;
$purchaseLimitAddOn         = getAddOnInfo('purchase_limit');




if ($pars[1] == 'add-supplier-card') {
    /*
    $membersTotalPostQuery = mysql($w['database'], "SELECT 
        count(user_id) 
    FROM 
        `live_events_posts` 
    WHERE 
        user_id = '".$user_data['user_id']."'");*/

    $membersTotalPostQuery = mysql($w['database'], "SELECT COUNT(*) FROM live_events_posts LEFT JOIN data_posts ON live_events_posts.post_id = data_posts.post_id WHERE live_events_posts.user_id = '" . $_COOKIE[userid] . "' AND data_posts.post_id IS NOT NULL");    

}
// SELECT lep.*, dp.* FROM live_events_posts AS lep JOIN data_posts AS dp ON lep.post_id = dp.post_id WHERE lep.user_id = '" . $_COOKIE[userid] . "' ORDER BY `lep`.`id` DESC
$membersTotalPosts = mysql_fetch_array($membersTotalPostQuery); ?>
<?php if ($pars[2]=="" || $pars[2]=="view") { ?>
<div class="col-md-12 nolpad">
    <div class="main_content">
        <h1 class="h1 title bold">
            Booked Events
        </h1>
        <h4 class="paragraph bold">
            Complete your Supplier Card to become visible on the event advertisements. 
        </h4>
        
    </div>
    <div class="clearfix"></div>
	<br>
</div>
<?php } ?>
<div role="tabpanel" class="manage-post-tabs">
    <ul role="tablist" class="nav nav-tabs">

        <!--<li class="<?php if ($pars[2]=="" || $pars[2]=="view" || $pars[2] == "deletegroup" || $pars[2] == "delete") { ?>active<? } ?>" id="goback">
            <a href="/account/add-supplier-card/view">
                <?php if ($pars[2]=="" || $pars[2]=="view" || $pars[2] == "deletegroup" || $pars[2] == "delete") { 
                    $dc['icon'] = '<i class="fa fa-address-card"></i>';
                    // $dc['data_name'] = 'Supplier Card';
                    $dc['data_name'] = 'Booked Events';
                    ?>
                    <?php echo $dc['icon'];?>
                    <?php echo ($dc['data_name']);?>
                    <?php if ($membersTotalPosts[0] > 0 && ($pars[2]=="" || $pars[2]=="view" || $pars[2] == "deletegroup" || $pars[2] == "delete")) { ?>
                        <span style="vertical-align: text-top;margin-left: 3px;" class="badge img-circle bg-secondary">
                            <?php echo $membersTotalPosts[0]?>
                        </span>
                    <?php } else if ($dc['data_type'] != 28) { ?>
                        <style>
                            #purschase-limit.publish-post-button,.publish-post-button{position:relative;right:0;top:0;text-transform:capitalize;font-weight:600;font-size:18px;margin:25px auto 10px;width: 85%;display:block;padding:10px}
                            .first-post{text-align: center;font-size: 26px;background-color:<?php echo $wa['custom_1']?>;padding:30px;border:3px dashed <?php echo $wa['custom_72']?>;margin:20px}
                            .first-post-inner{display:block;}
                        </style>
                    <?php }                 
                } else { ?>
                    <i class="fa fa-reply" aria-hidden="true"></i>
                    Go Back
                <?php } ?>
            </a>
        </li>-->  
    </ul>
</div>