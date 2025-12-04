<div role="tabpanel" class="tmargin member-profile-tabs">
    <?php if (is_array($tabs)) { ?>
    <?php if(data_categories::isFeaturesTabsEmpty($data_results) === false || data_categories::isOverViewTabEmpty($user) === false){?>
        <ul class="nav nav-tabs" role="tablist">
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
                            $tabsnum++; ?>
                            <li <?php $ti++;
                            if ($ti == 1) { ?> class="active hideMyTab"<?php } ?>>
                                <a href="#div<?php echo $key; ?>"
                                   rel=" nofollow"
                                   aria-controls="t<?php echo $key; ?>" role="tab"
                                   data-toggle="tab">
                                    <?php echo eval("?>" . $tab . "<?"); ?>
                                    <?php echo $dc['data_type']; ?>
                                    <?php if ($data_results[$key]['total'] > 0 && $data_results[$key]['data_type'] != 10) {
                                        echo " <small>(".$data_results[$key]['total'].")</small>";
                                    } ?>
                                </a>
                            </li>
                        <?php }

                    } else {
                        $tabsnum++; ?>
                        <li <?php $ti++;
                        if ($ti == 1) { ?> class="active hideMyTab"<?php } ?>>
                            <a href="#div<?php echo $key; ?>"
                               rel=" nofollow"
                               aria-controls="t<?php echo $key; ?>" role="tab"
                               data-toggle="tab">
                                <?php echo eval("?>" . $tab . "<?"); ?>
                                <?php echo $dc['data_type']; ?>
                                <?php if ($data_results[$key]['total'] > 0 && $data_results[$key]['data_type'] != 10) {
                                    echo " <small>(".$data_results[$key]['total'].")</small>";
                                } ?>
                            </a>
                        </li>
                        <?php
                    }
                }

            }

            if ($tabsnum == 1) {
                echo '<style> .hideMyTab{ display:none !important;}</style>';
            } ?>
        </ul>
    <?php }?>
        <?php 
        if(data_categories::isFeaturesTabsEmpty($data_results) === false || data_categories::isOverViewTabEmpty($user) === false){?>
        <div class="tab-content">
            <?php if (is_array($data_results)) {
                $dataCategoriesModel = new data_categories();
                foreach ($data_results as $key => $value) {

                    if ($data_results[$key]['data_filename'] == 'specialities' && $subscription['hide_specialties'] == 1) {

                    } else if((
                            ( $data_results[$key]['data_type'] != 10 && !data_categories::isFeatureTabEmpty($data_results[$key]) ) || 
                            ( $data_results[$key]['data_type'] == 10 && !data_categories::isOverViewTabEmpty($user,$data_results[$key]) ) 
                        )){
                        $userMetaInfo = new users_meta();
                        $userMetaInfo->getMetaData('data_categories', $key, 'is_event_feature');
                        $data_results[$key]['is_event_feature'] = $userMetaInfo->value;;
                        if ($data_results[$key]['total'] > 0 && array_key_exists($key, $tabs)) { ?>
    
                            <div id="div<?php echo $key; ?>" role="tabpanel"
                                 class="tab-pane <?php if ($activediv < 1) { ?>active<?php $activediv++;
                                 } ?>">
                                <?php
                                $dataCategoryInfo = $dataCategoriesModel->get($data_results[$key]['data_filename'], 'data_filename');
                                echo eval("?>" . $value['profile_header'] . "<?");
                                $loggedSubscription = getSubscription($me['subscription_id'], $w);
                                $loggedCanViewIDsArray = explode(",",$loggedSubscription['data_settings_read']);
                                $memberCanView = false;
                                if((in_array($dataCategoryInfo->data_id, $loggedCanViewIDsArray) && array_key_exists('user_id', $me) && $me['active'] == "2") || $data_results[$key]['data_filename'] == 'listing'){
                                    $memberCanView = true;
                                }			
                                if ($dataCategoryInfo->always_on == "0" && addonController::isAddonActive('members_only') && !$memberCanView){
                                    addonController::showWidget('members_only','389ffec8e86fc926655fab47a7b01a5a');
                                } else {
                                    echo replaceChars($w, $value['results']);
                                    echo eval("?>" . $dcProfileFooter[$data_results[$key]['data_type']] . "<?");
                                    if ($data_results[$key]['total'] > $data_results[$key]['end'] && $data_results[$key]['data_type'] != 10) { ?>
                                        <div id="clickToLoadMore-<?php echo $data_results[$key]['data_filename']; ?>"
                                             class="text-center loadContainer">
                                            <button class="btn btn-default btn-block clickToLoadMoreBtn"
                                                    href="#div<?php echo $key; ?>" type="button"
                                                    data-query="<?php echo $data_results[$key]['sqlquery']; ?>"
                                                    data-total="<?php echo $data_results[$key]['total']; ?>"
                                                    data-filename="<?php echo $data_results[$key]['data_filename']; ?>"
                                                    data-start="0"
                                                    data-formname="<?php echo $data_results[$key]['form_name']; ?>"
                                                    data-isevent="<?php echo $data_results[$key]['is_event_feature']; ?>"
                                                    data-finalDate="<?php echo $data_results[$key]['finalDate']; ?>"
                                                    data-finalDate2="<?php echo $data_results[$key]['finalDate2']; ?>"
                                                    data-end="<?php echo $data_results[$key]['end']; ?>"
                                                    data-type="<?php echo $data_results[$key]['data_type']; ?>"
                                                    rel=" nofollow"
                                                    aria-controls="t<?php echo $key; ?>" role="tab"
                                                    data-toggle="tab">
                                                <?php echo $label['lazyLoadBtnText']; ?>
                                            </button>
                                        </div>
                                        <?php
                                    }
                                }
                                
                                ?>
                            </div>
                        <?php }
                    }
                }
            } ?>
        </div>
        <?php }?>
    <?php } ?>
</div>
<?php echo widget("Bootstrap - Search - Lazy Loader tabs", "", $w['website_id'], $w); ?>