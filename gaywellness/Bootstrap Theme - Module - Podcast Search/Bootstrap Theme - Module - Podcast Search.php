<?php 
global $oldFormName, $newFormName, $optionValue, $fieldName, $dataID;

$oldFormName = "video_fields";                // Name of the old form (used on sites before master forms update)
$fieldName = "post_category";                 // Name of the field of the select

if ($dc['data_id'] != "") {
	$newFormName = $dc['form_fields_name'];       // Dynamic Form Name of Post Type
	$dataID = $dc['data_id'];                     // Dynamic ID of Post Type
} else {
	$newFormName = "video_fields_bootstrap";      // Name of new master form
	$dataID = 13;                                 // ID of the feature
}

echo widget("Bootstrap Theme - Function - Post Category Dropdown Controller","",$w['website_id'],$w);

?>
<div class="module">
    <h3>%%%search_dc_h1%%%</h3>
    <form class="form website-search" action="/<?php echo $dc['data_filename']; ?>" method="get">
        <?php if (!empty($optionValue)){ ?>
        <div class="input-group categoryVideos bmargin col-lg-12">
            <div class="styled-select2">
                <select class="videoPicker" multiple data-selected-text-format="count>2" name="category[]" title="%%%category_label%%%">
                    <option value="">%%%all_categories_label%%%</option>
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
        </div>
        <?php } ?>
        <div class="input-group md-autosuggest bmargin">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control videos_search md-autosuggest-input" id="keyword" placeholder="%%%home_search_keyword%%%" name="q" value="<?php echo stripslashes($_GET['q']);?>">
        </div>
        
        <div class="form-group nobmargin">
            <button type="submit" class="btn btn-primary btn-block">%%%sidebar_module_search%%%</button>
        </div>
    </form>
</div>