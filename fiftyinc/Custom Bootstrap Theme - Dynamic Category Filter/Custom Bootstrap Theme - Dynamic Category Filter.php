<?php
//get all the top and sub level categories in an array
$professionsQuery = mysql($w['database'],"SELECT
        service_id,
        name
    FROM
        list_services
    WHERE
        master_id = '0'
    ORDER BY
        name ASC");
$professionArray = array();

while ($currentProfession = mysql_fetch_assoc($professionsQuery)) {
    $professionArray[$currentProfession['service_id']] = $currentProfession;
}


$servicesQuery = mysql($w['database'],"SELECT
        service_id,
        profession_id,
        master_id,
        name
    FROM
        list_services
    WHERE
        master_id > 0
    ORDER BY
        name ASC");


while ($currentService = mysql_fetch_assoc($servicesQuery)) {
    $professionArray[$currentService['master_id']]['services'][$currentService['service_id']] = $currentService;
}
$currentCategories = explode(",",$_GET['tid']);
$activeFiltersArray = array();

$show_all_filters = array('194','190','294');

?>

<div class="module custom-sidebar-search-filters">
    <h3>Filter by Category</h3>
    <div class="search-filter-element categories-search-filters">
        <?php
        foreach ($professionArray as $pakey => $pavalue) { ?>
            <div class="category-group closed-mode-cat-group" data-pid="<?php echo $pavalue['service_id']; ?>">
                <span class="category-view-switch-button">
                    <p class="custom-group-cat-title">
                        <?php echo $pavalue['name']; ?>
                    </p>
                </span>
                <div class="sub-cat-checkbox-container">
                    <?php
                    foreach ($pavalue['services'] as $passkey => $passvalue) {
                        $checkStatus = "";

                        if (in_array($passvalue['service_id'],$currentCategories)) {
                            $checkStatus = ' checked="checked" ';
                            $activeFiltersArray[] = $pavalue['profession_id'];
                        }
                        ?>
                        <label class="sub-sub-item <?php echo (!in_array($pakey, $show_all_filters)) ? 'sub-sub-item-hidden' : 'sub-sub-item-display-block' ?> ">
                            <input type="checkbox" name="tid[]" value="<?php echo $passvalue['service_id']; ?>" <?php echo $checkStatus; ?> class="single-checkbox-filter">
                            <span class="checkbox-name-filter-category">
                                <?php echo $passvalue['name']; ?>
                            </span>
                        </label>
                        <?php
                    } ?>
                    <?php if (count($pavalue['services']) > 3): ?>
                    	<?php if (!in_array($pakey, $show_all_filters)): ?>
                    		<span id="loadMore">See all <?php echo count($pavalue['services']) ?> options</span>
                    	<?php endif ?>
                    <?php endif ?>
                </div>
            </div>
        <? } ?>
    </div>
</div>
<span class="current-active-filters" data-caf="<?php echo implode(",",$activeFiltersArray); ?>"></span>
<script>
	$('.closed-mode-cat-group').each(function(index, el) {
		var sub_sub_item = $(el).find('.sub-sub-item-hidden'),
		load_more = $(el).find('#loadMore'),
		size_of_item_group = sub_sub_item.length,
		x=3;
		$(sub_sub_item.slice(0,x)).css('display', 'block');
		$(load_more).click(function () {
		    x= (x+7 <= size_of_item_group) ? x+7 : size_of_item_group;
		    $(sub_sub_item.slice(0,x)).css('display', 'block');
		    if(x >= size_of_item_group){
		        $(load_more).hide();
		    }
		    // console.log(size_of_item_group);
		});
	});
	
</script>
<style>
	.sub-cat-checkbox-container label {
	    font-weight: normal;
	    font-size: 14px;
	}
	.custom-group-cat-title {
	    font-size: 16px;
	    font-weight: bold;
	    margin-top: 20px;
	}
	.sub-sub-item-hidden {
		display: none;
	}
	.custom-sidebar-search-filters {
	    padding: 0;
	    background: #fafafa;
	    border: none;
	}
	#loadMore {
		color: #007bff;
		cursor: pointer;
	}
	.sub-sub-item-display-block {
		display: block;
	}
</style>
