<?php if ($message != "") {
    echo showMessage($message, "good", 1) ;

} else if ($_SESSION['error'] != "") {
    echo showMessage($_SESSION['error'], "error", 1);
}
$_SESSION['error'] = "";
$page = getMetaData('list_seo',$page['seo_id'],$page,$w);

$loggedUser = getUser($_COOKIE['userid'], $w);

$membersOnlyActivated = addonController::isAddonActive('members_only');

$myLevels = explode(",", $page['allowed_products']);

//we remove any subscription id selected to be membersonly that dont exists anymore
if(count($myLevels) > 0){
    $websiteSubscriptionsIds = bd_controller::subscription_types(WEBSITE_DB)->getAllSubscriptionsIds();
    foreach($myLevels as $key => $subscription_id){
        if(!in_array(trim($subscription_id),$websiteSubscriptionsIds,true)){
            unset($myLevels[$key]);
        }
    }
    
    $page['allowed_products'] = implode(',',$myLevels);
}

$notAllowedMember = true;
if ($_COOKIE['userid'] > 0 && $loggedUser['active'] == "2" && (empty($page['allowed_products']) || (!empty($page['allowed_products']) && in_array($loggedUser['subscription_id'], $myLevels)))){
    $notAllowedMember = false;
}

global $digitalProductPurchased,$digitalProductLink;
$digitalProductPurchased = false;
$digitalProductLink = "";

if(isset($page['multiple_content_order']) && $page['multiple_content_order'] != ""){
    $page['content_order'] = $page['multiple_content_order'];
}

if($page['content_order'] != 0 && addonController::isAddonActive('sell_feature_post')){
    addonController::showWidget('sell_feature_post','88ca4e3ff17f13e8a5343042248f0e51');
}

if ($membersOnlyActivated === true && $page['content_footer'] == "1" && $notAllowedMember) {
    addonController::showWidget('members_only','389ffec8e86fc926655fab47a7b01a5a');
} else if ($page['content_order'] != "" && $page['content_order'] != 0 && !$digitalProductPurchased && addonController::isAddonActive('sell_feature_post')) { ?>
    <div class="clearfix clearfix-lg"></div> 
    <div class="col-md-offset-2 col-md-8 well text-center">
        <h1 style="line-height: 1.3em;">
            %%%digital_product_restriction_page_message%%%
        </h1>
        <hr>
        <a class="btn btn-lg btn-primary digital_product_click_for_access" href="<?php echo $digitalProductLink; ?>">
            %%%digital_product_click_for_access%%%
        </a>
    </div>
    <div class="clearfix clearfix-lg"></div>
<?php } else {
    if($page['enable_hero_section'] == "1" || $page['enable_hero_section'] == "2"){ ?>
    </div>
<style>
    <?php if($page['hero_hide_banner_ad'] == "1"){ ?>
    .above-content-banner-ad{
        display: none;
    }
    <?php } ?>
	#first_container > .container:first-child .clearfix.body-content {
		display: none !important;
	}
    <?php if($page['hero_content_overlay_opacity'] != "" && $page['hero_content_overlay_opacity'] != "0" && $page['hero_content_overlay_color'] != ""){ ?>
        .hero_section_container::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: <?php echo $page['hero_content_overlay_color']?>;
            opacity: <?php echo $page['hero_content_overlay_opacity']?>;
            left:0;
            top:0;
        }
    <?php } ?>
    .hero_section_container {
        background-image: url("<?php echo $page['hero_image']?>");
        background-position: center center;
        background-repeat: no-repeat;
        <?php if ($page['hero_background_image_size'] == "mobile-ready" || $page['hero_background_image_size'] == "") { ?>
        background-size: cover;
        <?php } else { ?>
        background-size: 100% auto;
        <?php } ?>
        position: relative;
    }
    .hero_section_container .container {
        padding-top: <?php echo $page['hero_top_padding']?>px;
        padding-bottom: <?php echo $page['hero_bottom_padding']?>px;
    }
    .hero_section_container .hero_h1 {
        color: <?php echo $page['h1_font_color']?>;
        font-size: <?php echo $page['h1_font_size']?>px;
        font-weight: <?php echo $page['h1_font_weight']?>;
    }
    .hero_section_container .hero_h2 {
        color: <?php echo $page['h2_font_color']?>;
        font-size: <?php echo $page['h2_font_size']?>px;
        font-weight: <?php echo $page['h2_font_weight']?>;
		line-height: 1.4em;
    }   
    .hero_section_container .hero_content {
        color: <?php echo $page['hero_content_font_color']?>;
        font-size: <?php echo $page['hero_content_font_size']?>px;
    }   
    .hero_section_container > .container > div {
        <?php if($page['hero_alignment'] == "center"){ ?>
        float: none!important;
        <?php } else { ?>
        float: <?php echo $page['hero_alignment']; ?>!important;
        <?php } ?>
        text-align: <?php echo $page['hero_alignment']; ?>;
    }
    <?php if($page['hero_alignment'] != "center"){ ?>
    @media only screen and (max-width: 768px){
    .hero_section_container {background-position: <?php echo $page['hero_alignment']; ?> top;}
    }
    <?php } ?>
    <?php if($page['enable_hero_section'] == "2"){ ?>
    @media only screen and (max-width: 768px){
    .hero_section_container {display: none;}
    }
    <?php } ?>
</style>
    <div class="hero_section_container">
        <div class="container">
            <div class="col-md-<?php echo $page['hero_column_width']?> center-block">
                <?php
                    if ($page['h1'] != "") {
                    echo "<h1 class='hero_h1'>" . stripslashes($page['h1']) . "</h1>";
                }
                if ($page['h2'] != "") {
                    echo "<h2 class='hero_h2'>" . stripslashes($page['h2']) . "</h2>";
                }
                if ($page['hero_section_content'] != ""){
                    $heroContent = $w;
                    if(isset($service)){
                        $service['service_name']    = $service['name'];
                        $heroContent                = array_merge($heroContent,$service);
                    }
                    echo "<div class='clearfix'></div><div class='hero_content'>".replaceChars($heroContent, stripslashes($page['hero_section_content']))."</div>";
                }   
                
                $page['hero_link_text'] = trim($page['hero_link_text']);
                $page['hero_link_url']  = trim($page['hero_link_url']);

                if ($page['hero_link_url'] != "" && $page['hero_link_text'] != ""){
                    if($page['hero_link_color'] == ""){
                        $page['hero_link_color'] = "btn-primary";
                    } else {
                        $page['hero_link_color'] = 'btn-'.$page['hero_link_color'];
                    }
                    echo "<div class='clearfix'></div><div class='hero_button tmargin tpad'> <a style='min-width:40%;' class='bold btn ".$page['hero_link_color']." ".$page['hero_link_size']."' href='".$page['hero_link_url']."' ".($page['hero_link_target_blank'] == 1 ? "target=_blank" : "" )."> ".$page['hero_link_text']." </a> </div>";
                }                               
                ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix clearfix-lg"></div>
        
    <div class="container">
    <?php }
    if ($page['content_layout'] == "1") { ?>

        <style>.body-content {
                margin-bottom: 0;
            }</style>

        <!-- Closes Container Divs from framework if Full-Widths Page -->
        </div></div>

        <div class="clearfix"></div>

        <div class="web-page-content fr-view">

            <? if (($page['h1'] != "" || $page['h2'] != "") && $page['enable_hero_section'] != '1' && $page['enable_hero_section'] != '2') { ?>
                <div class="pagehead">
                    <?php
                    if ($page['h1'] != "") {
                        echo "<h1>" . stripslashes($page['h1']) . "</h1>";
                    }
                    if ($page['h2'] != "") {
                        echo "<h2>" . stripslashes($page['h2']) . "</h2>";
                    } ?>
                </div>
            <? } ?>
            <?php echo $page['content']; ?>

        </div>

        <div class="clearfix"></div>

    <? } else { ?>

        <div class="row">

        <?php
        if($page['form_name'] != ""){
            switch ($page['menu_layout']) {
                // Left Wide
                case "1":
                    echo '<div class="col-md-8 col-md-push-4">';
                    break;
                // Right Wide
                case "2":
                    echo '<div class="col-md-8">';
                    break;
                // Left Slim
                case "3":
                    echo "<div class='col-md-9 col-md-push-3'>";
                    break;
                // Right Slim
                case "4":
                    echo "<div class='col-md-9'>";
                    break;
            }
        } else {
            echo '<div class="col-md-12">';
        }
        ?>

        <? if (($page['h1'] != "" || $page['h2'] != "") && $page['enable_hero_section'] != '1' && $page['enable_hero_section'] != '2') { ?>
            <div class="pagehead">
                <?php
                if ($page['h1'] != "") {
                    echo "<h1>" . stripslashes($page['h1']) . "</h1>";
                }
                if ($page['h2'] != "") {
                    echo "<h2>" . stripslashes($page['h2']) . "</h2>";
                } ?>
            </div>
        <? } ?>
        <?php echo $page['content']; ?>
        </div>

        <?php 
        if($page['form_name'] != ""){
            switch ($page['menu_layout']) {
                // Left Wide
                case "1":
                    echo '<div class="col-md-4 col-md-pull-8">';
                    break;
                // Right Wide
                case "2":
                    echo '<div class="col-md-4">';
                    break;
                // Left Slim
                case "3":
                    echo "<div class='col-md-3 col-md-pull-9'>";
                    break;
                // Right Slim
                case "4":
                    echo "<div class='col-md-3'>";
                    break;
            }
            echo sidebar($page['form_name'], "", $w['website_id'], $w);
            echo '</div>';
        }
        ?>

        </div>  <!-- Closes Row -->

    <? } ?>

    <? if ($page['content_layout'] == "1") { ?>
    <!-- Reopens Container Divs from framework If Full-Width Page -->
    <div>
    <div>
<? } ?>
<?php } ?>
<?php
// WebPage Schema - Static Pages
$schema_site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
$schema_page_url = $schema_site_url . '/' . ltrim($page['filename'], '/');
// Clean page fields
$title = !empty($page['title']) ? trim(strip_tags($page['title'])) : '';
$h1 = !empty($page['h1']) ? trim(strip_tags($page['h1'])) : '';
$h2 = !empty($page['h2']) ? trim(strip_tags($page['h2'])) : '';
$metaDesc = !empty($page['meta_desc']) ? trim(strip_tags($page['meta_desc'])) : '';
$siteName = !empty($w['website_name']) ? trim(strip_tags($w['website_name'])) : '';
$filename = ucwords(str_replace(array('-', '_', '/'), ' ', trim($page['filename'], '/')));
// Schema: name
$name = $title ?: $h1 ?: ($siteName ? $siteName . ' - ' . $filename : $filename);
// Schema: headline
if ($metaDesc) {
	$headline = $metaDesc;
} else {
	$headline = trim($h1 . ($h1 && $h2 ? ' - ' : '') . $h2) ?: $name;
}
// Schema: about
$about = $h2 ?: ($siteName ? $siteName . ' > ' . $filename : $filename);
// Build schema
$schema = array(
	"@context" => "https://schema.org/",
	"@type" => "WebPage",
	"@id" => $schema_page_url,
	"url" => $schema_page_url,
	"name" => $name,
	"headline" => $headline,
	"about" => $about,
	"inLanguage" => isset($w['website_language']) ? $w['website_language'] : 'en-US',
	"dateModified" => date('c', strtotime(date('Y-m-01'))),
	"mainEntityOfPage" => $schema_page_url,
	"author" => array("@id" => $schema_site_url . "/#organization"),
	"publisher" => array("@id" => $schema_site_url . "/#organization"),
	"isPartOf" => array("@id" => $schema_site_url . "/#website")
);
// Optional: image
$image = '';
if (!empty($page['facebook_image'])) $image = $page['facebook_image'];
elseif (!empty($w['facebook_app_id'])) $image = $w['facebook_app_id'];
elseif (!empty($w['website_logo'])) $image = $w['website_logo'];
if ($image) $schema["primaryImageOfPage"] = array("@type" => "ImageObject", "url" => $image);
// Optional: keywords
if (!empty($page['meta_keywords'])) {
	$schema["keywords"] = array_values(array_unique(array_map('trim', explode(',', $page['meta_keywords']))));
} elseif ($name || $siteName) {
	$schema["keywords"] = array_values(array_unique(array_filter(array($name, $siteName))));
}
?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
