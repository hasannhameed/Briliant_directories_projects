<?php
if ($pars[0]!= $w['default_search_url'] && $pars[0] != '') {
    $list_services_model = new list_services();
    $sub_where = array(
    array('value' => $pars[0] , 'column' => 'filename', 'logic' => '='),
    array('value' => 0 , 'column' => 'master_id', 'logic' => '=')
    );
    $subCategory = $list_services_model->get($sub_where);
    if ($subCategory != false){
        $subCategory = (is_object($subCategory))?$subCategory:$subCategory[0];
        $_GET['tid']= $subCategory->service_id;
    }
}
?>
<div class="module">
  <h3>%%%find_profession_label%%%</h3>
    <form action="/<?php echo $w['default_search_url'];?>" class="website-search"  accept-charset="UTF-8" method="get">
        <div class="form-group">
            <label>%%%home_search_dropdown_2%%%</label>
                <select data-placeholder="%%%home_search_default_2%%%" name="tid" id="bd-chained" next="ttid" class="form-control infinite-chained">
					<option value="">%%%all_categories_label%%%</option>
                    <?php 
                    $list_profession_model = new list_professions();
                    $topCategory = $list_profession_model->getByLimit(0,1);
                    $topCategory = (is_object($topCategory))?$topCategory:$topCategory[0];
                    $prof = $topCategory->profession_id;
					echo listServices($_GET['tid'],"list",$w,$prof);
					?>
				</select>
          	</div>
          	<div class="form-group">
                <label>Location</label>
                <input type=text placeholder="Add location" class="googleSuggest googleLocation form-control" id="location_google_maps_homepage" name="location_value" value="<? if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>">
          	</div>
			<div class="form-group nomargin">
				<button class="btn btn-primary btn-block" type="submit">%%%home_search_submit%%%</button>      
			</div> 
    </form>
</div>