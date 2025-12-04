<?php
$titleStyleColor = "";
if($wa['recent_sCategories_tColor'] != ""){
    $titleStyleColor = 'style="color: '.$wa['recent_sCategories_tColor'].'"';
}
if($wa['streaming_view_more_class'] != ''){
    $viewAllClass = $wa['streaming_view_more_class'];
} else {
    $viewAllClass = 'btn-info';
}

?>
<div class="clearfix"></div>
<div class="content-container sub-level-category-stream">
	<div class="clearfix clearfix-lg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if ($wa['scategories_show_view_all'] != '0'){ ?>
                    <a href="/categories" class="pull-right hidden-xs btn <?php echo $viewAllClass ?>">
                        %%%view_all_label%%%
                    </a>
                <?php } ?>
                <h2 class="nomargin sm-text-center bold" <?php echo $titleStyleColor ?>>
                    <?php echo $wa['custom_320']?>
                </h2>
                <hr>
            </div>

<?php
$listServicesModel = new list_services();
if ($w['default_top_category_sort'] == "") {
    $w['default_top_category_sort'] = " name ";
}
//$limitQuery= $wa['custom_321'];
$limitQuery= 200;

if ($wa['custom_322'] == "" || $wa['custom_322'] == 0){
    $listServicesModel->setOrder('name','ASC');
    $orderQuery = 'name ASC';
} else if ($wa['custom_322'] == 1) {
    $listServicesModel->setOrder('name','DESC');
    $orderQuery = 'name DESC';
} else if ($wa['custom_322'] == 2) {
    $listServicesModel->setOrder('sort_order','ASC');
    $orderQuery = 'sort_order ASC';
} else if ($wa['custom_322'] == 3) {
    $listServicesModel->setOrder('sort_order','DESC');
    $orderQuery = 'sort_order DESC';
}

if ($wa['custom_312'] == "2"){
    $columnWidth = "col-md-6";
} else if ($wa['custom_312'] == "1") {
    $columnWidth = "col-md-4";
} else {
    $columnWidth = "col-md-3";
}

if (empty($limitQuery)) {
    $limitQuery=8;
}
if (isset($wa['HL-HSOO-sub_level-only_photos']) && $wa['HL-HSOO-sub_level-only_photos'] == "1") {
    $listServicesResults = $listServicesModel->getSubCategoriesWithImage($limitQuery, $orderQuery);
} else {
    $listServicesModel->setOrder('name','ASC');
    $listServicesResults = $listServicesModel->getByLimit(0,$limitQuery);
}

foreach($listServicesResults as $profession){
$usersMetaModel = new users_meta();
$usersMetaModel->getMetaData('list_services',$profession->service_id,'image');
$profession->value = $usersMetaModel->value;
?>
        <div class="sub-category-single col-sm-6 <?php echo $columnWidth; ?> text-center bmargin bpad">
            <div class="pic" <?php if ($profession->value) { ?>style="background-image:url('<?php echo $profession->value; ?>')" <?php } ?>>
                <span class="pic-caption bottom-to-top">
                    <h3 class="pic-title bmargin">
                        <?php echo substr($profession->name,0,100); if (strlen($profession->name) > 100) { ?>...<?php } ?>
                    </h3>
                    <hr>
                    <a alt="<?php echo $profession->name;?>" title="<?php echo $profession->name;?>" href="/<?php echo $profession->filename; ?>" class="btn btn-success fpad-lg vpad view-more">%%%view_category_label%%%</a>
                </span>
            </div>
        </div>
<?php } ?>
            <div class="clearfix"></div>
            <?php if ($wa['scategories_show_view_all'] != '0'){ ?>
            <div class="col-md-12">
                <a href="/categories" class="btn btn-lg <?php echo $viewAllClass ?> btn-block visible-xs-block">%%%view_all_label%%%</a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>