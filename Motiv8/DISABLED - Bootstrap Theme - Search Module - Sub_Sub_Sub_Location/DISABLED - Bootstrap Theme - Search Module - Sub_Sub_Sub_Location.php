<?php
if ($pars[0]!='search_results' && $pars[0] != '') {
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
if ($_GET['location_value'] != "") { 
   $googleLocationValue = $_GET['location_value']; 

} else if ($w['geocode_visitor_default'] == 1 && $w['geocode'] == 1 && $_SESSION['vdisplay'] != "") { 
   $googleLocationValue = $_SESSION['vdisplay']; 
}
?>
<div class="module">
    <h3>%%%find_profession_label%%%</h3>
    <form action="/search_results" class="website-search"  accept-charset="UTF-8" method="get">
        <div class="form-group">
            <label>%%%home_search_dropdown_2%%%</label>
            <select data-placeholder="%%%home_search_dropdown_2%%%" name="tid" id="bd-chained" class="form-control">
                <option value="">%%%all_categories_label%%%</option>
                <?php 
                $list_profession_model = new list_professions();
                $topCategory = $list_profession_model->getByLimit(0,1);
                $topCategory = (is_object($topCategory))?$topCategory:$topCategory[0];
                $prof = $topCategory->profession_id;
                echo listServices(0,"list",$w,$prof); ?>
            </select>
        </div>
        <div class="form-group">
            <label>%%%more_options_label%%%</label>
            <select data-placeholder="%%%more_options_label%%%" name="ttid" id="tid" class="form-control">
                <option value="">%%%all_categories_label%%%</option>
            </select>
        </div>
        <div class="form-group">
            <label>%%%home_search_dropdown_3%%%</label>
            <input type=text placeholder="%%%home_search_default_3%%%" class="googleSuggest googleLocation form-control" id="location_google_maps_sidebar" name="location_value" value="<?php echo $googleLocationValue;?>">
        </div>
        <div class="form-group nomargin">
            <button type="submit" class="btn btn-primary btn-block">%%%home_search_submit%%%</button> 
        </div>    
    </form>
</div>