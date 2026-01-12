<?php
foreach ($_GET as $gkey => $gvalue) {
    if (!is_array($gvalue)) {
        $_GET[$gkey] = stripslashes($gvalue);
    }
}
?> 
<div class="col-md-9 text-right sm-text-center header-right-container nolpad xs-hpad">
	 
	  <div class="hidden-xs"> 
    <?php
    if ($_COOKIE['userid'] > 0) {
        echo widget('Bootstrap Theme - Header - Member Links', '', $w['website_id'], $w);
    } else { ?>
        <div class="mini-nav nobmargin list-inline xs-nopad xs-tmargin <?php if ($wa['custom_159'] != 4) { ?>vpad<?php } ?>">
            <?php
            if ($wa['custom_148'] == "1") {
                $addOnGoogleTranslate = getAddOnInfo("google_translate", "b20c91eaa0e30a1322cade0a40001dc8");
                if (isset($addOnGoogleTranslate['status']) && $addOnGoogleTranslate['status'] === 'success') {
                    echo widget($addOnGoogleTranslate['widget'], "", $w['website_id'], $w);
                }
            }
			if ($wa['header_public_menu'] == "hidden_admin_menu") {
				/// Show Nothing
			} else if($wa['header_public_menu'] != ''){
				echo menuArray($wa['header_public_menu'], 0, $w);	
			} else {
				echo menuArray("header_mini_nav", 0, $w);	
			} ?>
        </div>
        <?php
    } ?></div>
  <div class="clearfix"></div>
    <?php
    if (($wa['custom_159'] != 0 || $wa['custom_159'] == "") && !stristr($_SERVER['HTTP_HOST'],"securemypayment.com")) {
        if ($wa['custom_159'] == 4) { ?>
            [widget=Bootstrap Theme - Banner - 320_50]
            <?php
        } elseif ($wa['custom_159'] == 14) { ?>
            [widget=Bootstrap Theme - Social Media Links]
            <?php
        } else {
            $featurename = '';

            if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 2) {
                $featurename = 'search_results';
            }
            if ($wa['custom_159'] == 5) {
                $featurename = 'coupons';
            }
            if ($wa['custom_159'] == 6) {
                $featurename = 'events';
            }
            if ($wa['custom_159'] == 7) {
                $featurename = 'jobs';
            }
            if ($wa['custom_159'] == 8) {
                $featurename = 'products';
            }
            if ($wa['custom_159'] == 9) {
                $featurename = 'properties';
            }
            if ($wa['custom_159'] == 10) {
                $featurename = 'classifieds';
            }
            if ($wa['custom_159'] == 11) {
                $featurename = 'videos';
            }
            if ($wa['custom_159'] == 12) {
                $featurename = 'blog';
            }
            if ($wa['custom_159'] == 13) {
                $featurename = 'articles';
            }
            if ($wa['custom_159'] == 17) {
                $featurename = 'discussions';
                $autoSuggestName = 'discussion';
            }
            if($wa['custom_159'] == 15 || $wa['custom_159'] == 16){
                $globalSearchURL = bd_controller::list_seo_template(WEBSITE_DB)->get('search_results_all', 'seo_type');
                
                //if list seo template is not customized OR disabled
                if( $globalSearchURL === false || (bd_controller::list_seo_template(WEBSITE_DB)->template_id > 0 && bd_controller::list_seo_template(WEBSITE_DB)->isDisable()) ){
                    $globalSearchURL = bd_controller::list_seo_template(MAIN_DB)->get('search_results_all', 'seo_type');         
                }

                $featurename = $globalSearchURL->filename;
                $autoSuggestName = 'global';
            }
            ?>
            <form action="/<?php echo $featurename; ?>" name="frm1" class="form-inline website-search">
                <?php
                if ($wa['custom_159'] == 3 || $wa['custom_159'] == 2 || $wa['custom_159'] == 5 || $wa['custom_159'] == 6 || $wa['custom_159'] == 7 || $wa['custom_159'] == 8 || $wa['custom_159'] == 9 || $wa['custom_159'] == 10 || $wa['custom_159'] == 11 || $wa['custom_159'] == 12 || $wa['custom_159'] == 13 || $wa['custom_159'] == 15 || $wa['custom_159'] == 16|| $wa['custom_159'] == 17) { ?>
                    <div class="input-group input-group-sm bmargin sm-autosuggest">
                        <span class="input-group-addon hidden-md"><i class="fa fa-search"></i></span>
                        <input type="text"
                               placeholder="<?php if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 2) { echo $label['keyword_search_default'];
                               } else if($wa['custom_159'] == 15 || $wa['custom_159'] == 16){ ?> %%%global_search_placeholder%%% <?php }else{ ?>%%%search_by_keyword_label%%%<?php } ?>" value="<? if ($_GET['q'] != '') {
                            echo htmlspecialchars($_GET['q']);
                        } ?>" name="q"
                               class=" <? if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 2) { ?>member<? } else if ($wa['custom_159'] != 9) {
                                   if($wa['custom_159'] == 15 || $wa['custom_159'] == 16 || $wa['custom_159'] == 17){ $featurename = $autoSuggestName; } echo $featurename;
                               } else { ?>property<? } ?>_search form-control input-sm" autocomplete="off">
                    </div>
                    <?php
                }
                if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 10  || $wa['custom_159'] == 16) { ?>
                    <div class="input-group input-group-sm bmargin">
                        <span class="input-group-addon hidden-md"><i class="fa fa-location-arrow"></i></span>
                        <input type="text" placeholder="<?= $label['location_search_default'] ?>"
                               value="<? if ($_GET['location_value'] != "") {
                                   echo htmlspecialchars($_GET['location_value']);
                               } ?>" id="location_google_maps_header" name="location_value"
                               class="googleSuggest googleLocation form-control">
                    </div>
                    <?php
                } ?>
                <input type="submit" value="%%%search_label%%%" class="btn btn-sm btn_search bmargin xs-btn-block bold">
            </form>
            <?php
        }
    } ?>
</div>
