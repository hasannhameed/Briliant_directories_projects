<?php
//// If the new billing module is enabled, this code will redirect a non secure page to a secure page to enable the purchase button in the new billing module
$sub = getSubscription($user_data['subscription_id'], $w);
$lead = array_map('stripslashes', $lead);
if ($w['whmcs_api_enabled'] > 0 && $_SERVER['HTTPS'] != "on" && $lead['lead_status'] == 1 && $pars[0] != "devpreview" && $sub['lead_price'] > 0) {
    ?>
    <script>
        window.location = 'https://<?php echo $w['secure_url']; ?>/login/token/<?php echo $user_data['token']; ?>/leads/<?php echo $lead[lead_token]; ?>/checkout?webtoken=<?php echo $w['website_token']; ?>';
    </script>
    <?php
    exit;
}
$newConversion = array(
    "1" => "%%%pending_label%%%",
    "2" => "%%%accepted_label%%%",
    "3" => "%%%declined_label%%%",
    "4" => "%%%soldout_label%%%"
);
$newInvertedConversion = array(
    "%%%pending_label%%%" => "1",
    "%%%accepted_label%%%" => "2",
    "%%%declined_label%%%" => "3",
    "%%%soldout_label%%%" => "4"
);
if ($_GET['noframe'] != 1) { ?>
    <a class="btn btn-default pull-right btn-sm js-action hidden-xs">
		<i class="fa fa-print"></i> 
		%%%print_leads%%%
	</a>
    <?php
} else { ?>
    <div style="margin-bottom:5px;padding-bottom:8px;height:70px;margin:0px;">
        <a class="logo" href="/" style="margin-left:-10px;">
            <img alt="<?php echo $w['website_name']; ?>" src="<?php echo $w['website_logo']; ?>"/>
        </a>
    </div>
    <script>window.print();</script>
    <?php
} ?>
<h1 class="h4 line-height-lg bold alert bg-primary vpad img-rounded nomargin no-radius-bottom lead-detail-title">
	%%%lead_details_label%%% #<?php echo $lead['lead_id']; ?>
</h1>
<div class="well no-radius-top nomargin">
    <?php
    if ($user_data['active'] == 2) { ?>
        <div class="insidedetails">
            <div class="lead-detail-status">
                <table class="table table-striped ">
                    <tr>
                        <td style="width:200px;vertical-align: middle;border: none;padding-top: 0;">
                            %%%lead_status_label%%%
                        </td>
                        <td style="border: none;padding-top: 0;">
                            <?php if ($lead['lead_status'] == 1) { ?>
								<span class="btn-sm bold bg-warning inline-block">
									%%%available_label%%%
									<small>
										- %%%waiting_lead_label%%%
									</small>
								</span>
                            <? } else if ($lead['lead_status'] == 2) { ?>
                                <span class="btn-sm bold bg-success inline-block">
									<i class="fa fa-check" aria-hidden="true"></i>
                                    <?php echo $newConversion[$lead['lead_status']]; ?>
									<?php if ($lead['lead_accepted'] != "") { ?>
										<small>
											on
											<?php echo transformDate($lead['lead_accepted'], "QBTIME"); ?>
										</small>
									<?php } ?>
                                </span>
                            <?php } else if ($lead['lead_status'] == 3 || $lead['lead_status'] == 4) { ?>
								<span class="btn-sm bold bg-danger inline-block rmargin">
									<?php echo $newConversion[$lead['lead_status']]; ?>
								</span>
                            <?php } else if ($lead['status'] == 6 || $lead['status'] == 7) { ?>
                                <span class="btn-sm bold bg-danger inline-block">
                                    %%%lead_not_available%%%
                                </span>							
							<?php if ($lead['lead_match_notes'] != "") { ?>
								<span class="btn-sm bold bg-default inline-block">
									<?php echo $lead['lead_match_notes']; ?>
								</span>
							<?php }
                            } else {
                                echo $newConversion[$lead['lead_status']];
                            } ?>
                        </td>
                    </tr>
					
                    <?php
                    if ($lead['lead_matched'] != "") { ?>
                        <tr>
                            <td>%%%lead_date_matched%%%</td>
                            <td><?php echo transformDate($lead['lead_matched'], "QBTIME"); ?></td>
                        </tr>
                        <?php
                    }																	
                    if (($lead['status'] == '6' || $lead['status'] == '7' || $lead['status'] == '5' || $lead['lead_status'] == '4') && $lead['lead_status'] == '4') { ?>
                        <tr>
                            <td colspan="2" align="left" style="padding:5px;">
                                <p class="alert alert-danger nomargin">
                                    %%%lead_missed_label%%%<br/>%%%lead_missed_text%%%
                                </p>
                            </td>
                        </tr>
                        <?php
                    }
                    if ($lead['ip_location'] != "") { ?>
                        <tr>
                            <td>%%%client_ip_location%%%</td>
                            <td><?php echo $lead['ip_location'] ?></td>
                        </tr>
                        <?php
                    }
                    if ($lead['lead_distance'] != "Unknown" && $lead['lead_distance'] != "") { ?>
                        <tr>
                            <td>%%%distrance_from_you%%%</td>
                            <td>
                                %%%approx_label%%% <? if ($lead['lead_distance'] == 0) { ?>%%%within%%% 1 <? } else if ($lead['lead_distance'] > 0) {
                                    echo number_format($lead['lead_distance'], 1); ?> %%%mile_label%%%<? if ($lead['lead_distance'] > 1) { ?>s<? }
                                } ?> %%%distance_from_you_label%%%
                            </td>
                        </tr>
                    <?php
                    } 
					if ($lead['lead_status'] == '1' || $lead['lead_status'] == '2') {
						if ($totalmatches < $_ENV['dataFlow']['auto_match_acceptance']) {
                        $s = getSubscription($user_data['subscription_id'], $w);
                        $lead['item_price'] = $lead['match_price'];
                        if ($s['lead_price']) {
                            $lead['item_price'] = $s['lead_price'];
                            $lead['match_price'] = displayPrice($s['lead_price'], $w);
                        }
                        $addonLeadByPrice = getAddOnInfo("lead_price_by_category", 'a58a2b15f9baa162f334b37011fd172e');
                        if (isset($addonLeadByPrice['status']) && $addonLeadByPrice['status'] === 'success') {
                            echo widget($addonLeadByPrice['widget'], "", $w['website_id'], $w);
                            if ($lead['item_price'] > 0) {
                                $lead['purchase'] = 1;
                            } else {
                                $lead['purchase'] = 0;
                            }
                        } ?>
                        <tr>
                            <td style="width:200px;vertical-align: middle;">
                                <b>
                                    %%%lead_price_label%%%
                                </b>
                            </td>
                            <td>
								<span class="h3 nomargin weight-bold-lg align-middle">
									  <?php
									  if ($lead['item_price'] == 0) { ?>
										  %%%label_free_leads%%%
									  <?php } else {
										  echo $lead['match_price'];
									  }
									  ?>
								</span>
                            </td>
                        </tr>
						<? } 
					}
                    if ($lead['lead_notes'] != "") { ?>
                        <tr class="internal-note">
                            <td></td>
                            <td>
								<b>%%%lead_internal_notes%%%</b>
								<div class="clearfix"></div>
								<small>
									<?php echo $lead['lead_notes']; ?>
								</small>
							</td>
                        </tr>
                        <?php
                    } ?>					
					
                </table>
            </div>
            <?php
            if ($lead['lead_status'] == 2) {
                $view = "email";

            } else {
                $view = "preview";
            }
            $lead_details = showFormView($lead['formname'], $lead, array("view" => $view), $w);

            if ($lead_details != "") { ?>
                <div class="lead-detail-info">
                    <?php echo $lead_details; ?>
                </div>
                <?php
            } else {
                echo widget("Bootstrap Theme - Account - Leads Information", "", $w['website_id'], $w);
            }
            if ($lead['lead_status'] == 1 && $totalmatches < $_ENV['dataFlow']['auto_match_acceptance'] && $lead['closed'] != 1) { ?>
				<div class="clearfix bmargin"></div>
				<div class="well nohpad nomargin">
					<div class="col-sm-6 col-sm-offset-3">
						<?php
							if ($lead['purchase'] == 0 && $lead['lead_status'] == 1) { ?>
						<a class="btn btn-success btn-lg btn-block accept-pending-lead"
						href="/account/leads/accept/<?php echo $lead['lead_token']; ?>/<?php echo $user_data['token']; ?>">
							%%%accept_referral_label%%%
						</a>
						<?php
						} else if ($lead['purchase'] == 1 && $lead['lead_status'] == 1) {
							$leadStatus = leadMatchAction($pars[2], $user_data['token'], "accept", $w, "check");
							echo widget("Bootstrap Theme - Account - Leads Details Page - Purchase Button", "", $w['website_id'], $w);
							if ($leadStatus['status'] == "available") { ?>
							<a class="purchase btn btn-success btn-lg btn-block purchase-lead">
								%%%purchase_referral_label%%%
							</a>
							<?php
							} else { ?>
							<a class="btn btn-success btn-lg btn-block accept-lead"
							href="/account/leads/accept/<?php echo $lead['lead_token']; ?>/<?php echo $user_data['token']; ?>">
								%%%purchase_referral_label%%%
							</a>
							<?php
							}
						} ?>
					</div>
					<div class="clearfix"></div>
				</div>
                <?php
            } ?>
            <div class="clearfix"></div>
        </div>

        <?php
    } else { ?>
		<div class="well fmargin-lg fpad-xl empty-holder text-center invalid-chat-message">
			<h2>
				<span class="font-sm">
					%%%account_on_hold_1%%%
				</span>
			</h2>
			<p>
				%%%account_on_hold_2%%% 
				<a href='/account/billing/change'>
					%%%account_on_hold_3%%%
				</a>
			</p>
			<div class="clearfix"></div>
		</div>	
	<?php } ?>
</div>