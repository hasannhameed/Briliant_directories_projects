<?php

global $oldFormName, $newFormName, $optionValue, $fieldName, $dataID, $showSlider;

$oldFormName = "classified_fields";          // Name of the old form (used on sites before master forms update)
$newFormName = $dc['form_fields_name'];      // Name of new master form
$fieldName = "group_category";               // Name of the field of the select
$dataID = $dc['data_id'];                    // ID of the feature

echo widget("Bootstrap Theme - Function - Post Category Dropdown Controller");
echo widget("Bootstrap Theme - Function - Post Category Price Slider Check");
?>

<div class="module">
    <h3>%%%search_dc_h1%%%</h3>

    <form class="form website-search" action="/<?php echo $dc['data_filename']; ?>" method="get">

    [widget=job_custom_filters]
    
        <div class="input-group md-autosuggest bmargin">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control classifieds_search md-autosuggest-input" id="keyword" placeholder="%%%home_search_keyword%%%" name="q"
                   value="<?php echo stripslashes($_GET['q']);?>">
        </div>
        <div class="input-group bmargin location-search-field">
            <span class="input-group-addon"><i class="fa fa-globe"></i></span>
            <input type="text" autocomplete="off" class="form-control  googleSuggest googleLocation" id="location_google_maps_homepage"
                   placeholder="%%%location_search_default%%%" name="location_value"
                   value='<?php echo $_GET['location_value']; ?>'>
        </div>
        <?php if (!empty($optionValue)){ ?>
        <div class="form-group">
            <select name="category[]" multiple data-selected-text-format="count>2" id="type_classifieds" class="selectpicker" title="%%%category_label%%%">
                <option value="">%%%all_categories_label%%%</option>
                <?php
                foreach ($optionValue as $key) {
                    if (preg_match('/=>/',$key)) {
                        $key = explode('=>', $key);
                        $key[0] = trim($key[0]);
                        $key[1] = trim($key[1]);
                        if (in_array($key[0], $_GET['category'])) {
                            echo '<option value="' .$key[0] . '" selected>' . $key[1] . '</option>';
                        } else {
                            echo '<option value="' . $key[0] . '">' . $key[1] . '</option>';
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
        <?php } ?>
        <?php if($showSlider == true){ ?>
            <div class="form-group hpad" title="%%%home_search_pricerange%%%">
            <div id="classifieds_slider" class="classifieds_slider"></div>
                <input type="hidden" name="price">
            </div>
        <?php } ?>
        <div class="form-group nobmargin">
            <button type="submit" class="btn btn-primary btn-block">%%%sidebar_module_search%%%</button>
        </div>
    </form>
</div>