<?php if ($pars[1] == '33'): ?>
    <div class="hidden">
<?php endif ?>
<?php
/**
 * This widget follows BD code standards
 * Widget Name: Bootstrap Theme - Form - Membership Payment Options
 * Short Description: Shows payment options for signup and upgrade orders
 * Version: 0.2
 * Developed By: Matthew Brooks
 * Collaborators: Jason H.
 */


if (!isset($subscription['subscription_id']) && ($pars[0] == 'payment' || urldecode($pars[0]) == urldecode(brilliantDirectories::getDefaultCheckoutUrl())) ) {
    $subscription = getSubscription($pars[1],$w);
    
} else if(!isset($subscription['subscription_id']) || (!empty($pars[2]) && $subscription['subscription_id'] != $pars[2])) {
    
    $subscription = getSubscription($pars[2],$w);
}

if (!is_array($subscription) && is_array($_ENV['subscription'])) {
    $subscription = $_ENV['subscription'];
}

//// SHOW PAYMENT OPTION FAIL CHECK -- This will check to see if all the amounts are set to Admin Only, then show the preferred payment option
if ($subscription['hide_initial_amount'] != 1 && $subscription['initial_amount'] > 0) {
 $show_option++;
}
if ($subscription['hide_monthly_amount'] != 1 && $subscription['monthly_amount'] > 0) {
 $show_option++;
}
if ($subscription['hide_quarterly_amount'] != 1 && $subscription['quarterly_amount'] > 0) {
 $show_option++;
}
if ($subscription['hide_semiyearly_amount'] != 1 && $subscription['semiyearly_amount'] > 0) {
 $show_option++;
}
if ($subscription['hide_semiyearly_amount'] != 1 && $subscription['semiyearly_amount'] > 0) {
 $show_option++;
}
if ($show_option == 0) {
	if ($subscription['payment_default'] == "monthly") {
		$subscription['hide_monthly_amount']=0;
	} else if ($subscription['payment_default'] == "monthly") {
		$subscription['hide_monthly_amount']=0;
	} else if ($subscription['payment_default'] == "quarterly") {
		$subscription['hide_quarterly_amount']=0;
	} else if ($subscription['payment_default'] == "semiyearly") {
		$subscription['hide_semiyearly_amount']=0;
	} else if ($subscription['payment_default'] == "yearly") {
		$subscription['hide_yearly_amount']=0;
	}
	if ($subscription['initial_amount'] > 0) { 
		$subscription['hide_initial_amount']=0; 
	}
}
//// END PAYMENT OPTION FAIL CHECK



if ($subscription['hide_initial_amount'] != 1 && $subscription['initial_amount'] > 0) { ?>
    <label class="bmargin one_time_setup_fee">
      <?php if ($subscription['yearly_amount'] == 0 && $subscription['quarterly_amount'] == 0 && $subscription['monthly_amount'] == 0 && $subscription['semiyearly_amount'] == 0) {
           $subption = 1; ?>
        <b>%%%one_time_payment_billing%%%: <?php echo displayPrice($subscription['initial_amount'],$w); ?></b>
        <input type="hidden" name="member_type" value="onetime">
      <?php } else{ ?>
        <b>%%%one_time_setup_fee%%%: <?php echo displayPrice($subscription['initial_amount'],$w); ?></b> %%%one_time_fee_billing%%%
      <?php } ?>
    </label>
<?php }

if ($subscription['hide_monthly_amount'] != 1 && $subscription['monthly_amount'] > 0) {
    $suboption++; ?>
    <div class="form-group nomargin monthly_billing">
        <div class="checkbox nomargin">
            <label class="bmargin">
                <input type="radio" type="radio" name="member_type" value="monthly" <?php if ($b['member_type'] == "monthly" || ($subscription['payment_default'] == "monthly" && $b['member_type'] == "") || $_GET['plan'] == "monthly" || ($subscription['monthly_amount']>0 && $subscription['quarterly_amount']==0 && $subscription['semiyearly_amount']==0 && $subscription['yearly_amount']==0)) { echo " checked"; } ?>>
                %%%month_to_month%%%: <?php echo displayPrice($subscription['monthly_amount'],$w)?> %%%month_label%%% %%%cancel_anytime_billing%%%
            </label>
        </div>
    </div>
<?php }
if ($subscription['hide_quarterly_amount'] != 1 && $subscription['quarterly_amount'] > 0) {
    $suboption++;
    if ($subscription['quarterly_amount'] > 0 && $subscription['monthly_amount'] > 0 && $subscription['hide_monthly_amount'] != 1) {
        $subscription['quarterly_savings']=100-($subscription['quarterly_amount']/($subscription['monthly_amount']*3)*100);
    } ?>
    <div class="form-group nomargin quarterly_billing">
        <div class="checkbox nomargin">
            <label class="bmargin">
                <input type="radio" type="radio" name="member_type" value="quarterly" <?php if ($b['member_type'] == "quarterly" || ($subscription['payment_default'] == "quarterly" && $b['member_type'] == "") || $_GET['plan'] == "quarterly" || ($subscription['quarterly_amount']>0 && $subscription['semiyearly_amount']==0 && $subscription['yearly_amount']==0 &&  $subscription['monthly']==0)) { echo " checked"; } ?>>
                %%%quarterly_billing%%%: <?php echo displayPrice($subscription['quarterly_amount'],$w)?> %%%four_times_per_year_billing%%%
				<?php if ($subscription['quarterly_savings'] >= 5) { ?>
				<?php if ($label['save_label_billing'] != "" || $label['per_year_option_billing'] != "") { ?>
                    <span class="bg-primary bold hpad img-rounded sm-block inline-block">
                    <?php if ($label['save_label_billing'] != "") { ?>
                    <span class="save_label_billing">%%%save_label_billing%%% <?php echo floor($subscription['quarterly_savings'])."%"; ?> </span>
                    <?php } ?><span class="per_year_option_billing">%%%per_year_option_billing%%%</span>
                    </span>
				<?php } } ?>
            </label>
        </div>
    </div>
<?php }
if ($subscription['hide_semiyearly_amount'] != 1 && $subscription['semiyearly_amount'] > 0) {
    $suboption++; ?>
    <div class="form-group nomargin semiyearly_billing">
        <div class="checkbox nomargin">
            <label class="bmargin">
                <input type="radio" name="member_type" value="semiyearly" <?php if ($b['member_type'] == "semiyearly" || ($subscription['payment_default'] == "semiyearly" && $b['member_type'] == "") || $_GET[plan] == "semiyearly" || ($subscription['monthly_amount']==0 && $subscription['semiyearly_amount']>0 && $subscription['yearly_amount']==0 && $subscription['quarterly_amount']==0)) { echo " checked"; } ?>>
                %%%semi_annually_billing%%%: <?php echo displayPrice($subscription['semiyearly_amount'],$w);?> %%%twice_per_year_billing%%%
                <?php if ($subscription['semiyearly_savings'] >= 5) { ?>
				<?php if ($label['save_label_billing'] != "" || $label['per_year_option_billing'] != "") { ?>
                    <span class="bg-primary bold hpad img-rounded sm-block inline-block">
                    <?php if ($label['save_label_billing'] != "") { ?>
                    <span class="save_label_billing">%%%save_label_billing%%% <?php echo floor($subscription['semiyearly_savings'])."%"; ?> </span>
                    <?php } ?><span class="per_year_option_billing">%%%per_year_option_billing%%%</span>
                    </span>
				<?php } } ?>
            </label>
        </div>
    </div>
<?php }


if ($subscription['hide_yearly_amount'] != 1 && $subscription['yearly_amount'] > 0) {
    $suboption++;  ?>
    <div class="form-group nomargin yearly_billing">
        <div class="checkbox nomargin">
            <label class="bmargin">
                <input type="radio" name="member_type" value="yearly" <?php if ($b['member_type'] == "yearly" || ($subscription['payment_default'] == "yearly" && $b['member_type'] == "") || $_GET[plan] == "yearly" || ($subscription['monthly_amount']==0 && $subscription['semiyearly_amount']==0 && $subscription['quarterly_amount']==0 && $subscription['yearly_amount']>0)) { echo " checked"; } ?>>
                %%%annually_payment_billing%%%: <?php echo displayPrice($subscription['yearly_amount'],$w);?> %%%per_year_billing%%%
                <?php if ($subscription['yearly_savings'] >= 5) { ?>
				<?php if ($label['save_label_billing'] != "" || $label['per_year_option_billing'] != "") { ?>
                    <span class="bg-primary bold hpad img-rounded sm-block inline-block">
                    <?php if ($label['save_label_billing'] != "") { ?>
                        <span class="save_label_billing">%%%save_label_billing%%% <?php echo floor($subscription['yearly_savings'])."%"; ?> </span>
                    <?php } ?><span class="per_year_option_billing">%%%per_year_option_billing%%%</span>
                    </span>
				<?php } } ?>
            </label>
        </div>
    </div>
<?php } ?>

<?php if ($suboption < 1) { ?>
  <input type="hidden" name="member_type" value="onetime">
<?php } ?>

<?php
$freeTrialPeriod = getAddOnInfo("free_trials","9f8fd8fd1b3dacd32731984f903065cb");
/* Enable Free TrialPeriod */
if (isset($freeTrialPeriod['status']) && $freeTrialPeriod['status'] === 'success' && $pars[0]=="checkout" && $subscription['profile_type']!="free") {
	//// removed  && $pars[0] != 'account' && $pars[1] != 'upgrade' to make it work for upgrades
    echo widget($freeTrialPeriod['widget'],"",$w['website_id'],$w);
}
?>

<div class="clearfix clearfix-lg"></div>
<?php
$addonPromoCodes = getAddOnInfo("promo_codes","fcaac051c874768af8aeb934ba2a7be7");
/* Enable Coupons Codes */
if (addonController::isAddonActive('promo_codes') === true) {
 	//// removed  && $pars[0] != 'account' && $pars[1] != 'upgrade' to make it work for upgrades
    echo widget($addonPromoCodes['widget'],"",$w['website_id'],$w);
} else if($subscription['show_order_summary_signup'] != 0){
    echo widget("Bootstrap Theme - Function - Order Summary - Container");
}
?>
<?php if ($pars[1] == '33'): ?>
    </div>
<?php endif ?>