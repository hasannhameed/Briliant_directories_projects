 <div class="hidden-sm hidden-md hidden-lg hidden-xl ">

<?php if ($wa['custom_29'] == "horizontal") { ?>
    <div class="col-xs-12 search_box  fpad img-rounded">
        <?php if ($wa['custom_131'] != "") { ?>
            <h2 class="fpad bold nomargin sm-text-center">
                <?php echo $wa['custom_131'];?>
            </h2>
        <?php } ?>
        <div class="clearfix"></div>
        <div class="form-group nomargin hidden-xs hidden-sm col-md-5">
            <label class="nomargin">
                %%%home_search_dropdown_1%%%
            </label>
        </div>
        <div class="form-group nomargin hidden-xs hidden-sm col-md-5 nolpad">
            <label class="nomargin">
                %%%home_search_dropdown_3%%%
            </label>
        </div>
        <div class="clearfix"></div>
        <form class="fpad form-inline website-search" name="frm1" action="/<?php echo $w['default_search_url'];?>">
            <div class="form-group col-sm-12 col-md-5 nolpad sm-norpad">
                <div class="input-group input-group-lg col-xs-12">
                    <select placeholder="%%%home_search_default_1%%%" name="sid" id="sid" class="form-control input-lg">
                        <option value=""></option>
                        <?php echo listProfessions($_GET['sid'],"option",$w);?>
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-12 col-md-5 nolpad sm-norpad">
                <div class="input-group input-group-lg col-xs-12">
                    <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                    <input type="text" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage" value="<?php if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>" placeholder="%%%location_search_default%%%">
                </div>
            </div>
            <div class="form-group col-sm-12 col-md-2 nopad nomargin">
                <button type="submit" class="btn btn-lg btn-block btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } else { ?>
    <div class="col-md-6 col-xs-12 search_box fpad img-rounded center-block">
        <?php if ($wa['custom_131']!="") { ?>
            <h2 class="fpad bold nomargin sm-text-center">
                <?php echo $wa['custom_131'];?>
            </h2>
        <?php } ?>
        <div class="clearfix"></div>
        <form class="fpad form-horizontal website-search" name="frm1" action="/<?php echo $w['default_search_url'];?>">
            <div class="form-group nomargin hidden-sm hidden-xs">
                <label>
                    %%%home_search_dropdown_1%%%
                </label>
            </div>
            <div class="form-group nomargin bpad">
                <select placeholder="%%%home_search_default_1%%%" name="sid" id="sid" class="form-control input-lg">
                    <option value=""></option>
                    <?php echo listProfessions($_GET['sid'],"option",$w);?>
                </select>
            </div>
            <div class="form-group nomargin hidden-sm hidden-xs">
                <label>
                    %%%home_search_dropdown_3%%%
                </label>
            </div>
            <div class="form-group nomargin">
                <input type="text" placeholder="%%%location_search_default%%%" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage" value="<?php if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>">
            </div>
            <div class="col-md-12 nopad tmargin">
                <button type="submit" class="btn btn-lg btn-block btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } ?>
	
</div>