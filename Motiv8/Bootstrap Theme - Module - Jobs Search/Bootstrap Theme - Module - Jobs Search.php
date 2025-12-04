<?php
global $oldFormName, $newFormName, $optionValue, $fieldName, $dataID, $showSlider;

$oldFormName = "job_fields";                // Name of the old form (used on sites before master forms update)
$newFormName = "job_fields";      			// Name of new master form
$fieldName = "post_job";                    // Name of the field of the select
$dataID = $dc['data_id'];                   // ID of the feature

echo widget("Bootstrap Theme - Function - Post Category Dropdown Controller","",$w['website_id'],$w);
echo widget("Bootstrap Theme - Function - Post Category Price Slider Check","",$w['website_id'],$w);
?>

<link href="/directory/cdn/assets/bootstrap/css/bootstrap-select.min.css" rel="stylesheet">
<div class="module">
    <h3>%%%search_dc_h1%%%</h3>

    <form class="form website-search" action="/<?php echo $dc['data_filename']; ?>" method="get">
        <div class="input-group md-autosuggest bmargin">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control jobs_search md-autosuggest-input" id="keyword" placeholder="%%%home_search_keyword%%%" name="q" value="<?php echo stripslashes($_GET['q']);?>">
        </div>
        <div class="input-group bmargin">
            <span class="input-group-addon"><i class="fa fa-globe"></i></span>
            <input type="text" class="form-control googleSuggest googleLocation" id="location_google_maps_homepage" placeholder="%%%location_search_default%%%" name="location_value" value='<?php echo $_GET["location_value"]; ?>'>
        </div>
        <?php if (!empty($optionValue)){ ?>
            <div class="form-group">
                <select name="employment_type[]" multiple data-selected-text-format="count>2" id="type_property" class="selectpicker" title="%%%search_employment_type%%%">
                    <option value="">%%%search_any_type%%%</option>
                    <?php 
                    foreach ($optionValue as $key) {
                        if (preg_match('/=>/',$key)) {
                            $key = explode('=>', $key);
                            if (in_array($key[0], $_GET['category'])) {
                                echo '<option value="' . trim($key[0]) . '" selected>' . trim($key[1]) . '</option>';
                            } else {
                                echo '<option value="' . trim($key[0]) . '">' . trim($key[1]) . '</option>';
                            }
                        } else {
                            if (in_array($key, $_GET['category'])) {
                                echo '<option value="' . trim($key) . '" selected>' . $key . '</option>';
                            } else {
                                echo '<option value="' . trim($key) . '">' . $key . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        <?php } 
        $fieldName = "post_category";
        $dataID = 9;    // ID of the feature
        echo widget("Bootstrap Theme - Function - Post Category Dropdown Controller","",$w['website_id'],$w);
        if (!empty($optionValue)){
        ?>
        <div class="form-group">
            <select name="category[]" multiple data-selected-text-format="count>2" id="type_job" class="selectpicker" title="%%%category_label%%%">
                <option value="">%%%all_categories_label%%%</option>
                <?php foreach ($optionValue as $key) {

                    if (preg_match('/=>/',$key)) {
                        $key = explode('=>', $key);
                        if (in_array($key[0], $_GET['category'])) {
                            echo '<option value="' . trim($key[0]) . '" selected>' . trim($key[1]) . '</option>';
                        } else {
                            echo '<option value="' . trim($key[0]) . '">' . trim($key[1]) . '</option>';
                        }
                    } else {
                        if (in_array($key, $_GET['category'])) {
                            echo '<option value="' . trim($key) . '" selected>' . $key . '</option>';
                        } else {
                            echo '<option value="' . trim($key) . '">' . $key . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </div>
        <? } ?>
		<?php if($showSlider == false){ ?>
			<div class="form-group hpad" title="%%%home_search_pricerange%%%">
				<div id="jobs_slider" class="jobs_slider"></div>
				<input type="hidden" name="price" value='<?php if ($_GET["price"] != "") { echo $_GET["price"];} else { echo "0;100000"; } ?>'>
			</div>
		<?php } ?>
        <div class="form-group nobmargin">
            <button type="submit" class="btn btn-primary btn-block">%%%sidebar_module_search%%%</button>
        </div>
    </form>
</div>