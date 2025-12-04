<?php global $dcProfileFooter; ?>
<div role="tabpanel"
     class="<?php if ($wa['profile_full_width_header'] != "1") { ?>tmargin <?php } ?> member-profile-tabs ">
    <?php if (is_array($tabs)) { ?>
        <?php if (data_categories::isFeaturesTabsEmpty($data_results) === false || data_categories::isOverViewTabEmpty($user) === false) { ?>
            <ul class="nav nav-tabs fpad nobpad profile-tabs-nav" role="tablist" aria-label="Profile Tabs">
                <?php
                foreach ($tabs as $key => $tab) {
                    if (
                        $data_results[$key]['total'] > 0 &&
                        (
                            ($data_results[$key]['data_type'] != 10 && !data_categories::isFeatureTabEmpty($data_results[$key])) ||
                            ($data_results[$key]['data_type'] == 10 && !data_categories::isOverViewTabEmpty($user, $data_results[$key]))
                        )
                    ) {
                        if ($data_results[$key]['data_filename'] == "specialities") {
                            if ($subscription['hide_specialties'] == 0) {
                                $tabsnum++;
								$ti++;?>
								<li <?php if ($ti == 1) { ?>class="active hideMyTab"<?php } ?> role="presentation">
                                    <a href="#div<?php echo $key; ?>" rel=" nofollow" aria-controls="t<?php echo $key; ?>" aria-label="<?php echo eval("?>" . strip_tags($tab) . "<?"); ?>" role="tab" data-toggle="tab" aria-selected="false">
                                        <?php echo eval("?>" . $tab . "<?"); ?>
                                        <?php echo $dc['data_type']; ?>
                                        <?php if ($data_results[$key]['total'] > 0 && $data_results[$key]['data_type'] != 10) {
                                            echo " <small>(" . $data_results[$key]['total'] . ")</small>";
                                        } ?>
                                    </a>
                                </li>
                            <?php }
                        } else {
                            $tabsnum++; 
							$ti++;?>
                            <li <?php if ($ti == 1) { ?>class="active hideMyTab"<?php } ?> role="presentation">
                                <a href="#div<?php echo $key; ?>" rel=" nofollow" aria-controls="t<?php echo $key; ?>" aria-label="<?php echo eval("?>" . strip_tags($tab) . "<?"); ?>" role="tab" data-toggle="tab" aria-selected="false">
                                    <?php echo eval("?>" . $tab . "<?"); ?>
                                    <?php echo $dc['data_type']; ?>
                                    <?php if ($data_results[$key]['total'] > 0 && $data_results[$key]['data_type'] != 10) {
                                        echo " <small>(" . $data_results[$key]['total'] . ")</small>";
                                    } ?>
                                </a>
                            </li>
                            <?php
                        }
                    }

                }

                if ($tabsnum == 1) {
                    echo '<style>.hideMyTab{ display:none !important;}.profile-tabs-nav{height: 1px;padding:0;}</style>';
                } ?>
            </ul>
        <?php } ?>
        <?php
        if (data_categories::isFeaturesTabsEmpty($data_results) === false || data_categories::isOverViewTabEmpty($user) === false) {
            ?>
            <div class="tab-content">
                <?php if (is_array($data_results)) {
                    $dataCategoriesModel = new data_categories();

                    foreach ($data_results as $key => $value) {

                        if ($data_results[$key]['data_filename'] == 'specialities' && $subscription['hide_specialties'] == 1) {
                        } else if ((
                            ($data_results[$key]['data_type'] != 10 && !data_categories::isFeatureTabEmpty($data_results[$key])) ||
                            ($data_results[$key]['data_type'] == 10 && !data_categories::isOverViewTabEmpty($user, $data_results[$key]))
                        )) {
                            $userMetaInfo = new users_meta();
                            $userMetaInfo->getMetaData('data_categories', $key, 'is_event_feature');
                            $data_results[$key]['is_event_feature'] = $userMetaInfo->value;;
                            if ($data_results[$key]['total'] > 0 && array_key_exists($key, $tabs)) { ?>

                                <div id="div<?php echo $key; ?>" role="tabpanel" aria-labelledby="t<?php echo $key; ?> div<?php echo $key; ?>"
                                     class="tab-pane <?php if ($activediv < 1) { ?>active<?php $activediv++;
                                     } ?>">
                                    <?php
                                    $dataCategoryInfo = $dataCategoriesModel->get($data_results[$key]['data_filename'], 'data_filename');
                                    echo eval("?>" . $value['profile_header'] . "<?");
                                    if (!isset($me)) {
                                        $me = getUser($_COOKIE['userid'], $w);
                                    }
                                    $loggedSubscription = getSubscription($me['subscription_id'], $w);
                                    $loggedCanViewIDsArray = explode(",", $loggedSubscription['data_settings_read']);
                                    $memberCanView = false;
                                    if ((in_array($dataCategoryInfo->data_id, $loggedCanViewIDsArray) && array_key_exists('user_id', $me) && $me['active'] == "2") || $data_results[$key]['data_type'] == '10' || $data_results[$key]['data_type'] == '28') {
                                        $memberCanView = true;
                                    }
                                    if (strlen($dataCategoryInfo->always_on) > 1) {
                                        if ($dataCategoryInfo->always_on[0] == "2" && $dataCategoryInfo->always_on[1] == "1") {
                                            $dataCategoryInfo->always_on = "2";
                                        }
                                        if ($dataCategoryInfo->always_on[0] == "2" && $dataCategoryInfo->always_on[1] == "2") {
                                            $dataCategoryInfo->always_on = "1";
                                        }
                                        if ($dataCategoryInfo->always_on[0] == "1" && $dataCategoryInfo->always_on[1] == "2") {
                                            $dataCategoryInfo->always_on = "3";
                                        }
                                        if ($dataCategoryInfo->always_on[0] == "1" && $dataCategoryInfo->always_on[1] == "1") {
                                            $dataCategoryInfo->always_on = "0";
                                        }
                                    }
                                    if (($dataCategoryInfo->always_on == "0" || $dataCategoryInfo->always_on == "3") && addonController::isAddonActive('members_only') && !$memberCanView) {
                                        addonController::showWidget('members_only', '389ffec8e86fc926655fab47a7b01a5a');
                                    } else {
                                        echo replaceChars($w, $value['results']);
                                        if ($data_results[$key]['total'] > $data_results[$key]['end'] && $data_results[$key]['data_type'] != 10) { ?>
										 	<div class="clearfix"></div>
                                            <div id="clickToLoadMore-<?php echo $data_results[$key]['data_filename']; ?>"
                                                 class="text-center loadContainer bmargin">
                                                <button class="btn btn-default btn-block btn-lg bmargin bold clickToLoadMoreBtn"
                                                        href="#div<?php echo $key; ?>" type="button"
                                                        data-total="<?php echo $data_results[$key]['total']; ?>"
                                                        data-page="2"
                                                        data-type="<?php echo $data_results[$key]['data_type'];?>"
                                                        data-owner="<?php echo $user['user_id']?>"
                                                        data-lvl="<?php echo $user['subscription_id'];?>"
                                                        data-replyToReview="<?php echo $memberSubscription['reply_visitor_review'];?>"
                                                        data-dc="<?php echo $key; ?>"
                                                        data-end="<?php echo $data_results[$key]['end']; ?>"
                                                        rel="nofollow"
                                                        aria-controls="t<?php echo $key; ?>" role="tab"
                                                        data-toggle="tab">
                                                    <?php echo $label['lazyLoadBtnText']; ?>
                                                </button>
												<div class="clearfix"></div>
                                            </div>
										 	<div class="clearfix"></div>
                                            <?php
                                        }
                                        echo eval("?>" . $dcProfileFooter[$data_results[$key]['data_type']] . "<?");
                                    }
                                    ?>
                                </div>
                            <?php }
                        }
                    }
                } ?>
				<div class="clearfix"></div>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<?php echo widget("Bootstrap - Search - Lazy Loader tabs"); ?>