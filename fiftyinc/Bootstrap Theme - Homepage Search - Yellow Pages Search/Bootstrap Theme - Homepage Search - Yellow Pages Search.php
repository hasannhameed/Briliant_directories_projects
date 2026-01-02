<?php if ($wa['custom_29'] == "horizontal") { ?>
    <div class="col-md-12 search_box fpad img-rounded">
        <?php if ($wa['custom_131']!="") { ?>
            <h2 class="fpad nomargin sm-text-center">
                <?php echo $wa['custom_131'];?>
            </h2>
        <?php } ?>
        <div class="clearfix"></div>
        <div class="form-group nomargin hidden-sm hidden-xs col-md-4">
            <label class="nomargin">
                %%%home_search_dropdown_1%%%
            </label>
        </div>
        <div class="form-group nomargin hidden-xs hidden-sm col-md-4">
            <label class="nomargin">
                %%%home_search_dropdown_3%%%
            </label>
        </div>
        <div class="clearfix"></div>
        <form class="tpad form-inline website-search" name="frm1" action="/<?php echo $w['default_search_url'];?>">
            <div class="form-group col-sm-12 col-md-4 bmargin">
                <div class="input-group input-group-lg col-sm-12 large-autosuggest">
                    <div class="input-group-addon">
                        <i class="fa fa-fw fa-search"></i>
                    </div>
                    <input type="text" class="member_search1 form-control input-lg large-autosuggest-input" name="q" value="<?php echo $_GET['q'];?>" placeholder="%%%keyword_search_default%%%">
                </div>
            </div>
            <div class="form-group col-sm-12 col-md-4 bmargin">
                <div class="input-group input-group-lg col-sm-12">
                    <div class="input-group-addon"><i class="fa fa-fw fa-location-arrow"></i></div>
                    <input type="text" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage" value="<?php if ($_GET['location_value']!='') { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!='') { echo $_SESSION['vdisplay']; } ?>" placeholder="%%%location_search_default%%%" autocomplete="off">
                </div>
            </div>
            <div class="form-group col-sm-12 col-md-4 bmargin">
                <button type="submit" class="btn-block btn btn-lg btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } else { ?>
    <div class="col-xs-12 col-sm-12 col-md-6 search_box fpad img-rounded center-block">
        <?php if ($wa['custom_131'] != "") { ?>
            <h2 class="fpad nomargin sm-text-center">
                <?php echo $wa['custom_131'];?>
            </h2>
        <?php } ?>
        <div class="clearfix"></div>
        <form class="fpad form-horizontal website-search" name="frm1" action="/<?=$w['default_search_url']?>">
            <div class="form-group nomargin hidden-sm hidden-xs col-lg-5">
                <label>%%%home_search_dropdown_1%%%</label>
            </div>
            <div class="input-group input-group-lg bmargin col-lg-7 large-autosuggest">
                <span class="input-group-addon">
                    <i class="fa fa-fw fa-search"></i>
                </span>
                <input type="text" class="member_search1 form-control input-lg large-autosuggest-input" name="q"     id="keywordSuggest" value="<?=$_GET['q']?>" placeholder="%%%keyword_search_default%%%">
            </div>
            <div class="clearfix"></div>
            <div class="form-group nomargin hidden-sm hidden-xs col-lg-5">
                <label>
                    %%%home_search_dropdown_3%%%
                </label>
            </div>
            <div class="input-group input-group-lg bmargin col-lg-7">
                <span class="input-group-addon">
                    <i class="fa fa-fw fa-location-arrow"></i>
                </span>
                <input type="text" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage" value="<?php if ($_GET['location_value']!='') { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!='') { echo $_SESSION['vdisplay']; } ?>" placeholder="%%%location_search_default%%%" autocomplete="off">
            </div>
            <div class="col-lg-offset-5 col-lg-7 nopad">
                <button type="submit" class="btn-block btn btn-lg btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } ?>