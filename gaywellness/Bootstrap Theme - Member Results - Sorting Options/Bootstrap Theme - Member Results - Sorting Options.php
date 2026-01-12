<?php 
if ($pars[0]!= $w['default_search_url']) {
	$list_profession_model = new list_professions();
	$topCategory = $list_profession_model->get($pars[0],'filename');
	if ($topCategory != false){
		$topCategory = (is_object($topCategory))?$topCategory:$topCategory[0];
		$_GET['sid']= $topCategory->profession_id;
	}
	if ($pars[1]!=''){
		$list_services_model = new list_services();
        $sub_where = array(
        array('value' => $pars[1] , 'column' => 'filename', 'logic' => '='),
        array('value' => $_GET['sid'] , 'column' => 'profession_id', 'logic' => '='),
        array('value' => 0 , 'column' => 'master_id', 'logic' => '=')
        );
        $subCategory = $list_services_model->get($sub_where);
        if ($subCategory != false){
            $subCategory = (is_object($subCategory))?$subCategory:$subCategory[0];
            $_GET['tid']= $subCategory->service_id;
        }
	}
}
global $searchMethodRadius;
?>
<?php
if ($_ENV['total'] > 0) {
    $sortingStart = displayNumberFormat($_ENV['start']);
    $sortingEnd = displayNumberFormat($_ENV['end']);
    $sortingTotal = displayNumberFormat($_ENV['total']);
    ?>

    <div class="col-sm-3 nopad bmargin">
       
    </div>
    <div class="col-sm-9 nopad text-right bmargin">
	

        <div class="form-inline">
				
            <?php
			if ($_GET['radius'] > 0) {
				$w['default_radius'] = $_GET['radius'];
			}
			if ($w['default_search_url'] == "") {
				$w['default_search_url'] = "search_results";
			}
			?>
			<div class="zipcodesearch form-group" style='margin-top:12px;'>
				<form  action="" method="get" class="website-search" name="frm1">
					
					<div class="form-group " style='min-width:150px;text-align:left;'>
						
                    			<input type="text" name="q" value="<?php echo stripslashes($_REQUEST['q']);?>" placeholder="%%%keyword_search_default%%%" class="form-control member_search">


					</div>
					
					
					<div class="form-group">
						<!--input type="text" placeholder="%%%location_search_default%%%" value="<?php if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>" id="location_google_maps_sidebar_radius" name="location_value" class="googleSuggest googleLocation form-control"-->
					</div>
					<div class="form-group">
						<select class="form-control" name="radius">
							<?=selectRange(5,100,"",$w['distance'],5,$w['default_radius'],$_GET['radius'],$w)?>
						</select>
					</div>
					<div class="form-group sort-members-select">
						<select class="form-control pull-right" name="sort"
								onchange="window.location='<?php echo str_replace("sort=", "", $REQUEST_URI);
								if (!strstr($REQUEST_URI, '?')) {
									echo '?';
								} else {
									echo '&';
								} ?>sort='+this.value;" id="sortbox">
							<option value="" <?php if ($_GET['sort'] == "") { ?>selected<?php } ?>>%%%default_sort_label%%%
							</option>

							<?php
							if ( empty($searchMethodRadius) ||  $searchMethodRadius == 'radius' || $_GET['search'] == "zip-code" || $_GET['postal_code'] != "" || $_COOKIE['sort'] == "distance" || ($city['LAT'] != "" && $city['LONG'] != "")) { ?>
								<option value="distance" <?php if ($_GET['sort'] == "distance") { ?>selected<?php } ?>>%%%closest_location_sort_label%%%</option>
							<?php }
							if (!empty($label['most_reviews_sort_label'])) { ?>
								<option value="reviews" <?php if ($_GET['sort'] == "reviews") { ?>selected<?php } ?>>
									%%%most_reviews_sort_label%%%
								</option>
							<?php }
							if (!empty($label['az_sort_label']) && false) { ?>
								<option value="name ASC" <?php if ($_GET['sort'] == "name ASC") { ?>selected<?php } ?>>
									%%%az_sort_label%%%
								</option>
							<?php }
							if (!empty($label['za_sort_label']) && false) { ?>
								<option value="name DESC" <?php if ($_GET['sort'] == "name DESC") { ?>selected<?php } ?>>
									%%%za_sort_label%%%
								</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group form-submit">
						<button type="submit" class="btn btn-primary btn-block">Refresh</button>
					</div>
					<div class="form-group connect-hide" style="margin-right:15px;">
						<?php
						$addOnWidget = '';
						$gridViewAddOn = getAddOnInfo('grid_view_search_reults', '769e3e86f08b2d05aaabb1e555221b87');
						$mapViewAddOn = getAddOnInfo('google_map_search_results', 'ccda1a004e20781cca3712ec22a57434');

						if (isset($gridViewAddOn['status']) && $gridViewAddOn['status'] == 'success') {
							$addOnWidget = $gridViewAddOn['widget'];
						}
						if (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success') {
							$addOnWidget = $mapViewAddOn['widget'];
						}
						if ((isset($gridViewAddOn['status']) && $gridViewAddOn['status'] == 'success') || (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success')) {
							echo widget($addOnWidget, "", $w['website_id'], $w);
						} ?>
					</div>
					
				</form>
			
			</div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
<?php } ?>