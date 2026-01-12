<div class="col-md-6 ">[widget=Custom - Bootstrap Theme - Homepage Keyword Search]</div>


<div class="col-md-6 "><?php if ($wa['custom_29']=="horizontal") { ?>
    <div class="col-md-12 search_box fpad img-rounded">
        <?php if ($wa['custom_131']!="") { ?>
            <h2 class="fpad bold nomargin sm-text-center">
                <?php echo $wa['custom_131'];?>
            </h2>
        <?php } ?>
        <div class="clearfix"></div>
        <form class="fpad website-search" name="frm1" action="/<?php echo $w['default_search_url'];?>">
            <div class="form-group col-sm-12 col-md-6 nolpad sm-nopad">
                <div class="input-group col-sm-12 input-group-lg">
                    <div class="input-group-addon">
                        <i class="fa fa-fw fa-location-arrow"></i>
                    </div>
                    <input type="text" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage1" value="<?php if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>" placeholder="%%%location_search_default%%%">
                </div>
            </div>
            <div class="form-group col-sm-12 col-md-6 nopad nomargin">
                <button type="submit" class="btn-block btn btn-lg btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } else { ?>
    <div class="col-md-5 search_box nopad img-rounded center-block">
        <?php if ($wa['custom_131']!="") { ?>
            <h2 class="fpad bold nomargin sm-text-center">
                <?php echo $wa['custom_131'];?></h2>
        <?php } ?>
        <div class="clearfix"></div>
		
        <form class="nopad form-horizontal website-search website-search-homepage2" name="frm1" action="/<?php echo $w['default_search_url']?>">
            <div class="form-group nomargin hidden-sm hidden-xs">
                <?php if ($label['home_search_dropdown_3']!="") { ?>
                    <label>
                        %%%home_search_dropdown_3%%%
                    </label>
                <?php } ?>
            </div>
            <div class="input-group bmargin">
                <span class="input-group-addon"><button type="submit" class="btn-block btn btn-lg btn_home_search">%%%home_search_submit%%%</button></span>
                <input type="text" class="googleSuggest googleLocation form-control2 input-lg" name="location_value" id="location_google_maps_homepage" value="<?php if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>" placeholder="%%%location_search_default%%%">
            </div>
            <!--div class="col-md-12 nopad">
                <button type="submit" class="btn-block btn btn-lg btn_home_search">%%%home_search_submit%%%</button>
            </div-->
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } ?></div>