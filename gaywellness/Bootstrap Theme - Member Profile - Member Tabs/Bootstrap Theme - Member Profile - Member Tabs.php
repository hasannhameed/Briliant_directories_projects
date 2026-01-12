<?php global $dcProfileFooter; ?>
<div role="tabpanel" class="member-profile-tabs">
    <?php
    if(is_array($tabs)) {
        if(data_categories::isFeaturesTabsEmpty($data_results) === false || data_categories::isOverViewTabEmpty($user) === false){ ?>
            <ul class="nav nav-tabs fpad nobpad" role="tablist">
                <?php
                foreach ($tabs as $key => $tab) {
                    if  (
                        $data_results[$key]['total'] > 0 &&
                        (
                            ( $data_results[$key]['data_type'] != 10 && !data_categories::isFeatureTabEmpty($data_results[$key]) ) ||
                            ( $data_results[$key]['data_type'] == 10 && !data_categories::isOverViewTabEmpty($user,$data_results[$key]) )
                        )
                    ) {
                        if ($data_results[$key]['data_filename'] == "specialities") {
                            if ($subscription['hide_specialties'] == 0) {
                                $tabsnum++;?>
                                <li <?php $ti++; if ($ti == 1) { ?>class="active hideMyTab"<? } ?>>
                                    <a href="#<?php echo $key?>" rel="nofollow" aria-controls="t<?php echo $key?>" role="tab" data-toggle="tab">
                                        <?php
                                        echo eval("?>".$tab."<?");
                                        echo $dc['data_type'];

                                        if ($data_results[$key]['total'] > 0 && $data_results[$key]['data_type'] != 10) { ?>
                                            <small>(<?php echo $data_results[$key]['total'];?>)</small>
                                        <?php } ?>
                                    </a>
                                </li>
                                <?php
                            }
                        } else {
                            $tabsnum++;?>
                            <li <?php $ti++; if ($ti == 1) { ?>class="active hideMyTab"<? } ?>>
                                <a href="#<?php echo $key?>" rel="nofollow" aria-controls="t<?php echo $key?>" role="tab" data-toggle="tab">
                                    <?php
                                    echo eval("?>".$tab."<?");
                                    echo $dc['data_type'];

                                    if ($data_results[$key]['total'] > 0 && $data_results[$key][data_type] != 10) { ?>
                                        <small>(<?php echo $data_results[$key]['total'];?>)</small>
                                    <?php } ?>
                                </a>
                            </li>
                            <?php
                        }
                    }
                }
                if($tabsnum == 1) {
                    echo '<style> .hideMyTab{ display:none !important;}</style>';
                }?>
            </ul>
        <?php }
        if(data_categories::isFeaturesTabsEmpty($data_results) === false || data_categories::isOverViewTabEmpty($user) === false){
            ?>
            <div class="tab-content">
                <?php
                if (is_array($data_results)) {
                    $dataCategoriesModel = new data_categories();
                    foreach ($data_results as $key => $value) {

                        if ($data_results[$key]['data_filename'] == 'specialities' && $subscription['hide_specialties'] == 1) {

                        } else if((
                            ( $data_results[$key]['data_type'] != 10 && !data_categories::isFeatureTabEmpty($data_results[$key]) ) ||
                            ( $data_results[$key]['data_type'] == 10 && !data_categories::isOverViewTabEmpty($user,$data_results[$key]) )
                        )){
                            if ($data_results[$key]['total'] > 0 && array_key_exists($key, $tabs)) { ?>

                                <div id="<?php echo $key?>" role="tabpanel" class="tab-pane <?php if ($activediv < 1) { ?>active<?php $activediv++; } ?>">
                                    <?php
                                    $dataCategoryInfo = $dataCategoriesModel->get($data_results[$key]['data_filename'], 'data_filename');
                                    $loggedSubscription = getSubscription($me['subscription_id'], $w);
                                    $loggedCanViewIDsArray = explode(",",$loggedSubscription['data_settings_read']);
                                    $memberCanView = false;
                                    if((in_array($dataCategoryInfo->data_id, $loggedCanViewIDsArray) && array_key_exists('user_id', $me) && $me['active'] == "2") || $data_results[$key]['data_type'] == '10'){
                                        $memberCanView = true;
                                    }
                                    if(strlen($dataCategoryInfo->always_on) > 1){
                                        if($dataCategoryInfo->always_on[0] == "2" && $dataCategoryInfo->always_on[1] == "1"){
                                            $dataCategoryInfo->always_on = "2";
                                        }
                                        if($dataCategoryInfo->always_on[0] == "2" && $dataCategoryInfo->always_on[1] == "2"){
                                            $dataCategoryInfo->always_on = "1";
                                        }
                                        if($dataCategoryInfo->always_on[0] == "1" && $dataCategoryInfo->always_on[1] == "2"){
                                            $dataCategoryInfo->always_on = "3";
                                        }
                                        if($dataCategoryInfo->always_on[0] == "1" && $dataCategoryInfo->always_on[1] == "1"){
                                            $dataCategoryInfo->always_on = "0";
                                        }
                                    }
                                    if (($dataCategoryInfo->always_on == "0" || $dataCategoryInfo->always_on == "3") && addonController::isAddonActive('members_only') && !$memberCanView){
                                        addonController::showWidget('members_only','389ffec8e86fc926655fab47a7b01a5a');
                                    } else {
                                        echo eval("?>".$value['profile_header']."<?");
                                        echo replaceChars($w,$value['results']);
										if ($value['profile_footer'] == "" && $dcProfileFooter[$data_results[$key]['data_type']] != "") { 
											$value['profile_footer'] = $dcProfileFooter[$data_results[$key]['data_type']];
										}
                                        echo eval("?>".$value['profile_footer']."<?");
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        }
                    }
                } ?>
            </div>
            <?php
        } } ?>
</div>