<div class="col-xs-12 col-sm-12 col-md-12 advance_search search_box fpad img-rounded">
 <div class="test">
 <form class="nopad form-inline website-search" name="frm1" action="/search_results">
       <!-- <input type="hidden" name="sid" value="" />
        <input type="hidden" name="tid" value="" /> -->
	 <div class="row">

	 
            <div class="form-group col-sm-5 col-xs-5 col-md-5">
                <div class="input-group input-group-sm col-xs-12">
                   <input type="text" class="form-control member_search1 input-lg" name="q" value="<?=$_GET[q]?>" placeholder="Enter Keyword or Name" autocomplete="off" spellcheck="false" dir="auto">
                </div>
            </div>
            
            <div class="form-group col-sm-5 col-xs-5 col-md-5 nolpad">
                <div class="input-group input-group-sm col-xs-12">
                    <span class="input-group-addon"><i class="fa fa-fw fa-location-arrow"></i></span>
                    <input type="text" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage2" value="<?=$_GET[location_value]?>" placeholder="City or Post Code" autocomplete="off">
                </div>
            </div>
            <div class="form-group col-sm-2 col-xs-2 col-md-2 nolpad nomargin">
                <button type="submit" class="btn btn-sm btn-block btn_home_search">Search Now</button>
            </div></div>
            <div class="clearfix"></div>
        </form></div>
        <div class="clearfix"></div>
    </div>