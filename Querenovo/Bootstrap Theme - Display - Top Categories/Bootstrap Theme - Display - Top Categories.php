<?php 
echo "<!-- Widget Loaded -->";
$titleStyleColor = "";
if($wa['recent_tCategories_tColor'] != ""){
    $titleStyleColor = 'style="color: '.$wa['recent_tCategories_tColor'].'"';
}
if($wa['streaming_view_more_class'] != ''){
    $viewAllClass = $wa['streaming_view_more_class'];
} else {
    $viewAllClass = 'btn-info';
}

if($wa['streaming_read_more_class'] != ''){
    $readMoreClass = $wa['streaming_read_more_class'];
} else {
    $readMoreClass = 'btn-success';
}
?>
<div class="clearfix"></div>
<div class="content-container top-level-category-stream">
	<div class="clearfix"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if ($wa['tcategories_show_view_all'] != '0'){ ?>
                    <a href="<?php echo $w['categories_page_url']?>" class="view-all-btn-desktop hidden-xs btn <?php echo $viewAllClass ?>">
                        %%%view_all_label%%%
                    </a>
                <?php } ?>
                <h2 class="nomargin sm-text-center streaming-title" <?php echo $titleStyleColor; ?>>
                    <?php echo $wa['custom_217']?>                    
                </h2>
                <hr>
            </div>
			<div class="clearfix"></div>
<?php

$professionsModel = new list_professions();
$checkImages = false;

if(isset($wa['HL-HSOO-top_categories-only_photos']) && $wa['HL-HSOO-top_categories-only_photos'] == "1"){
    $professionsWhere = array(
        array('value' => "" , 'column' => 'image', 'logic' => '!=')
    );
}else{
    $professionsWhere = array();
}

if ($w['default_top_category_sort'] == "") {
    $w['default_top_category_sort'] = " name ";
};
$limitQuery= $wa['custom_218'];

if ($wa['custom_219'] == "" || $wa['custom_219'] == 0){
    $professionsModel->setOrder('name','ASC');
} else if ($wa['custom_219'] == 1) {
    $professionsModel->setOrder('name','DESC');
} else if ($wa['custom_219'] == 2) {
    $professionsModel->setOrder('sort_order','ASC');
} else if ($wa['custom_219'] == 3) {
    $professionsModel->setOrder('sort_order','DESC');
}

if ($wa['custom_311'] == "2"){
    $columnWidth = "col-md-6";
} else if ($wa['custom_311'] == "1") {
    $columnWidth = "col-md-4";
} else {
    $columnWidth = "col-md-3";
}

if (empty($limitQuery)) {
    $limitQuery=8;
};

$professionsModel->setReturnQueryOnly(true);
$professionsQuery = $professionsModel->getByLimit(0,$limitQuery,$professionsWhere);


if(isset($w['fast_search']) && $w['fast_search'] == "0"){

    if(strpos($professionsQuery, "WHERE" ) !== false){
        $replaceQueryString = "SELECT lp.* FROM `list_professions` as lp RIGHT JOIN `users_data` AS ud ON ud.profession_id = lp.profession_id";
        $professionsQuery   = str_replace("WHERE  `image` != ''","WHERE lp.profession_id IS NOT NULL AND lp.image != '' ",$professionsQuery);
    }else{
        $replaceQueryString = "SELECT lp.* FROM `list_professions` as lp RIGHT JOIN `users_data` AS ud ON ud.profession_id = lp.profession_id WHERE lp.profession_id IS NOT NULL ";
    }

    $professionsQuery = str_replace("SELECT * FROM `list_professions`",$replaceQueryString,$professionsQuery);
    $professionsQuery = str_replace("ORDER BY","GROUP BY lp.profession_id ORDER BY",$professionsQuery);
}

$professionsResultSet   = $professionsModel->query($professionsQuery);
$professionsResults     = $professionsModel->getResults($professionsResultSet);


$renderCount = 0;
?>
<div class="row">
<div class="col-md-12 slickTopCategories">
<?php foreach($professionsResults as $profession){ ?>
    <div class="top-category-single col-sm-6 <?php echo $columnWidth; ?> text-center bmargin bpad">
         <?php if (!empty($w['lazy_load_images'])) { ?>
            <div class="pic lazyloader" <?php if ($profession->image) { ?>data-src="<?php echo $profession->image; ?>"<?php } ?>>
        <?php } else { ?>
            <div class="pic" <?php if ($profession->image) { ?>style="background-image:url('<?php echo $profession->image; ?>')"<?php } ?>>
        <?php } ?>
            <span class="pic-caption bottom-to-top" onclick>
                <h3 class="pic-title bmargin">
                    <?php echo substr($profession->name,0,100); if (strlen($profession->name) > 100) { ?>...<?php } ?>
                </h3>
                <hr>
                <a alt="<?php echo $profession->name;?>" title="<?php echo $profession->name;?>" href="/<?php echo $profession->filename; ?>" title="<?php echo ucwords($profession->name); ?>" class="btn <?php echo $readMoreClass?> fpad-lg vpad view-more">
                    %%%view_category_label%%%
                </a>
            </span>
			<a aria-label="<?php echo strip_tags($Label['view_category_label']);?>" alt="<?php echo $profession->name;?>" title="<?php echo $profession->name;?>" href="/<?php echo $profession->filename; ?>" class="homepage-link-element <?php if ($wa['streaming_info_display'] == "on_hover") { ?>hidden-xs<?php } ?>"></a>
        </div>
    </div>
<?php $renderCount++;
} ?>
</div>
    <div class="clearfix"></div>
    <?php if ($wa['tcategories_show_view_all'] != '0'){ ?>
        <div class="col-md-6">
            <a href="<?php echo $w['categories_page_url']?>" class="btn btn-lg <?php echo $viewAllClass ?> btn-block visible-xs-block">%%%view_all_label%%%</a>
        </div>
    <?php } ?>
</div>
</div>
</div>
</div>
<?php
    global $featureSliderEnabled, $featureMaxPerRow, $featureSliderClass, $postsCount;
    $postsCount = $renderCount;
    $featureSliderEnabled = $wa['tcategories_carousel_slider'];
    $featureMaxPerRow = $wa['custom_311'];
    $featureSliderClass = '.slickTopCategories';
    addonController::showWidget('post_carousel_slider','1a19675a36d28232077972bbdb6bb7fe');
?>