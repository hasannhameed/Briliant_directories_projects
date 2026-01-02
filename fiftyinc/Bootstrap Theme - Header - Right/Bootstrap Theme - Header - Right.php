
<?php
foreach ($_GET as $gkey => $gvalue) {

    if (!is_array($gvalue)) {
        $_GET[$gkey] = stripslashes($gvalue);
    }
}
?>
<div class="col-md-7 text-right sm-text-center header-right-container">
    <?php
    if ($_COOKIE['userid'] > 0) {
        echo widget('Bootstrap Theme - Header - Member Links', '', $w['website_id'], $w);

    } else { ?>
        <ul class="mini-nav nomargin list-inline <?php if ($wa['custom_159'] != 4) { ?>vpad<?php } ?>">
            <?php
            if ($wa['custom_148'] == "1") {
                $addOnGoogleTranslate = getAddOnInfo("google_translate", "b20c91eaa0e30a1322cade0a40001dc8");
                if (isset($addOnGoogleTranslate['status']) && $addOnGoogleTranslate['status'] === 'success') {
                    echo widget($addOnGoogleTranslate['widget'], "", $w['website_id'], $w);
                }
            }
            echo menuArray("header_mini_nav", 0, $w); ?>
        </ul>
        <?php
    } ?>
    <div class="clearfix"></div>
    <?php
    if (($wa['custom_159'] != 0 || $wa['custom_159'] == "") && $_SERVER['HTTP_HOST'] != "www.securemypayment.com") {

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
            if ($wa[custom_159] == 5) {
                $featurename = 'coupons';
            }
            if ($wa[custom_159] == 6) {
                $featurename = 'events';
            }
            if ($wa[custom_159] == 7) {
                $featurename = 'jobs';
            }
            if ($wa[custom_159] == 8) {
                $featurename = 'products';
            }
            if ($wa[custom_159] == 9) {
                $featurename = 'properties';
            }
            if ($wa[custom_159] == 10) {
                $featurename = 'classifieds';
            }
            if ($wa[custom_159] == 11) {
                $featurename = 'videos';
            }
            if ($wa[custom_159] == 12) {
                $featurename = 'blog';
            }
            if ($wa[custom_159] == 13) {
                $featurename = 'articles';
            }
            ?>
            <form action="/<?php echo $featurename; ?>" name="frm1" class="form-inline website-search">
                <?php
                if ($wa['custom_159'] == 3 || $wa['custom_159'] == 2 || $wa['custom_159'] == 5 || $wa['custom_159'] == 6 || $wa['custom_159'] == 7 || $wa['custom_159'] == 8 || $wa['custom_159'] == 9 || $wa['custom_159'] == 10 || $wa['custom_159'] == 11 || $wa['custom_159'] == 12 || $wa['custom_159'] == 13) { ?>
                    <div class="input-group input-group-sm bmargin sm-autosuggest">
                        <span class="input-group-addon hidden-md"><i class="fa fa-search"></i></span>
                        <input type="text"
                               placeholder="<?php if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 2) {
                                   echo $label['keyword_search_default'];
                               } else { ?>%%%search_by_keyword_label%%%<? } ?>" value="<? if ($_GET['q'] != '') {
                            echo htmlspecialchars($_GET['q']);
                        } ?>" name="q"
                               class=" <? if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 2) { ?>member<? } else if ($wa['custom_159'] != 9) {
                                   echo $featurename;
                               } else { ?>property<? } ?>_search form-control input-sm" autocomplete="off">
                    </div>
                    <?php
                }
                if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 10) { ?>
                    <div class="input-group input-group-sm bmargin">
                        <span class="input-group-addon hidden-md"><i class="fa fa-location-arrow"></i></span>
                        <input type="text" placeholder="<?= $label['location_search_default'] ?>"
                               value="<? if ($_GET['location_value'] != "") {
                                   echo htmlspecialchars($_GET['location_value']);
                               } else if ($w['geocode_visitor_default'] == 1 && $w['geocode'] == 1 && $_SESSION['vdisplay'] != "") {
                                   echo htmlspecialchars($_SESSION['vdisplay']);
                               } ?>" id="location_google_maps_header" name="location_value"
                               class="googleSuggest googleLocation form-control">
                    </div>
                    <?php
                } ?>
                <input type="submit" value="Search" class="btn btn-sm btn_search bmargin xs-btn-block">
            </form>
            <?php
        }
    } ?>
</div>
<div class=clearfix>

</div>
<div class="reg">
<form action="/search_results" class="form-inline website-search large vis_search"  accept-charset="UTF-8" method="get">
				<div class='bodge'>				
			<div class="form-group normal-autosuggest col-xs-12">				
				
				<input type="text" name="q" value="<?php echo $_REQUEST['q']?>" placeholder="%%%keyword_search_default%%%" class="form-control member_search1">
			</div>
			
			<div class="form-group  col-xs-12">				
				
				<input type=text placeholder="%%%location_search_default%%%" class="googleSuggest googleLocation form-control" id="location_google_maps_homepage1" name="location_value" value="<?php if ($_GET[location_value] != '') { echo htmlspecialchars($_GET['location_value']); } ?>">
			</div>
			
			<div class="form-group col-xs-12" style="margin-bottom:6px;">
				<button class="btn btn-primary btn-block" type="submit">%%%home_search_submit%%%</button>    
			</div>  </div>  
		</form></div>