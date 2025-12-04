<?php

if ($data_results[$dc['data_id']]['total'] == 0) {
    echo widget("Bootstrap Theme - Member Results - Page Title");
} else {
    /*echo widget("Bootstrap Theme - Member Results - Sorting Options");*/
    echo widget("Bootstrap Theme - Member Results - Page Title");
}

$page = getMetaData('list_seo',$page['seo_id'],$page,$w);
$loggedUser = getUser($_COOKIE['userid'], $w);
$myLevels = explode(",", $page['allowed_products']);
$notAllowedMember = true;
$wa['custom_45'] = (checkIfMobile(true) && $dc['sidebar_position_mobile'] == 'hide') ? '2' : $wa['custom_45'];
if ($_COOKIE['userid'] > 0 && $loggedUser['active'] == "2" && empty($page['allowed_products']) && $page['content_footer'] == 1 || (!empty($page['allowed_products']) && in_array($loggedUser['subscription_id'], $myLevels)) || $page['content_footer'] != 1){
    $notAllowedMember = false;
}
if($page['allowed_products']){
    $allowedProducts = explode(",", $page['allowed_products']);
}

if (addonController::isAddonActive('members_only') && (((strlen($dc['always_on']) == 1 && $dc['always_on'] == '0') || (strlen($dc['always_on']) > 1 && $dc['always_on'][0] == "1")) && (!isset($_COOKIE['userid']) || $loggedUser['active'] != "2")) || $notAllowedMember){
    addonController::showWidget('members_only','389ffec8e86fc926655fab47a7b01a5a');
} else {
if($page['enable_hero_section'] == "1" || $page['enable_hero_section'] == "2"){ ?>
    </div>
    <style>
        <?php if($page['hero_hide_banner_ad'] == "1"){ ?>
        .above-content-banner-ad{
            display: none;
        }
        <?php } ?>
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
		#first_container > .container:first-child .clearfix.body-content {
			display: none !important;
		}
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
                } ?>

                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix clearfix-lg"></div>

    <div class="container">
        <?php }
        if ($page['content'] != "" && $page['custom_html_placement'] == "3") {
            echo '</div><div class="page-content-above-body"><div class="container">'.$page['content'].'</div><div class="clear"></div></div><div class="container">';
        }
        if ($error == "") { ?>
            <div class="clearfix"></div>
        <?php }
        if (($wa['custom_160'] == 1 && addonController::isAddonActive('grid_view_search_reults')) || ($wa['custom_160'] == 2 && addonController::isAddonActive('google_map_search_results'))) { ?>
            <style>
                .grid-container{
                    opacity: 0;
                    transition: opacity 0.9s ease-in-out;
                }
            </style>
        <?php } ?>
        <div class="row content_w_sidebar member_results">
            <?php
            if($_GET['devmode'] > 0 && strpos($_ENV['sqlquery'],"address") !== false){
                $colors = array();

                $colors['main'] = '#FF0000';
                $serviceArea = array();

                $servicesAreasResultSet = mysql($w['database'],$_ENV['sqlquery']);

                while ($serviceArea = mysql_fetch_assoc($servicesAreasResultSet)) {

                    $tempArray = array();
                    $tempArray['nelat']         = (int)$serviceArea['nelat'];
                    $tempArray['nelng']         = (int)$serviceArea['nelng'];
                    $tempArray['swlat']         = (int)$serviceArea['swlat'];
                    $tempArray['swlng']         = (int)$serviceArea['swlng'];
                    $tempArray['lat']           = (int)(!empty($serviceArea['lat']))? $serviceArea['lat'] : $serviceArea['user_lat'];
                    $tempArray['lng']           = (int)(!empty($serviceArea['lng']))? $serviceArea['lng'] : $serviceArea['user_lng'];
                    $tempArray['address']       = $serviceArea['address'];
                    $tempArray['user_id']       = $serviceArea['user_id'];
                    $tempArray['name']          = $serviceArea['name'];
                    $tempArray['color']         = '#000000';

                    $serviceAreas[] = $tempArray;
                }

                $serviceAreas['main'] = array(
                    'nelat'     => $_GET['nelat'],
                    'nelng'     => $_GET['nelng'],
                    'swlat'     => $_GET['swlat'],
                    'swlng'     => $_GET['swlng'],
                    'lat'       => $_GET['lat'],
                    'lng'       => $_GET['lng'],
                    'address'   => $_GET['location_value'],
                    'name'      => 'My Search : '.$_GET['location_value'],
                    'color'     => $colors['main'],
                    'user_id'   => 0
                );

                print_r($serviceAreas['main']);
                ?>

                <div id="map-bounds"></div>
                <script type="text/javascript">
                    var google_result = sessionStorage.getItem('google_result');
                    if(google_result != ""){
                        $(".member_results").append('<pre style="white-space:pre-wrap!important;">'+JSON.stringify(JSON.parse(google_result), null, "\\t")+'</pre>');
                    }
                </script>
            <?php } ?>

            <?php if ($page['menu_layout'] != '' && $page['seo_id'] != '') { ?>

                <?php
                if($page['form_name'] != ""){
                    switch ($page['menu_layout']) {
                        // Left Wide
                        case "1":
                            echo '<div class="col-md-8 col-md-push-4 main-container">';
                            break;
                        // Right Wide
                        case "2":
                            echo '<div class="col-md-8 main-container">';
                            break;
                        // Left Slim
                        case "3":
                            echo "<div class='col-md-9 col-md-push-3 main-container'>";
                            break;
                        // Right Slim
                        case "4":
                            echo "<div class='col-md-9 main-container'>";
                            break;
                    }
                } else {
                    echo '<div class="col-md-12">';
                }
            } else { ?>
        <?php if ($dc['category_sidebar'] == "" || $wa['custom_45'] == "2") { ?>
            <div class="col-lg-12">
                <?php } else if ($dc['category_sidebar'] != "") {
                    switch ($wa['custom_45']) {
                        case "0":
                            echo "<div class='col-md-9 col-md-push-3 main-container'>";
                            break;
                        case "3":
                            echo "<div class='col-md-8 col-md-push-4 main-container'>";
                            break;
                        case "1":
                            echo "<div class='col-md-9 main-container'>";
                            break;
                        case "4":
                            echo "<div class='col-md-8 main-container'>";
                            break;
                    }
                }
                }
                if ($_ENV['error']!="") { ?>
                    <?php echo widget("Bootstrap Theme - Member Results - Page Title"); ?>
                    <div id="top-alert">%%%invalid_location_search%%%</div>

                <?php } else if ($_ENV['error'] == "") {
                    echo eval("?>".$page['content_header']."<?");

                    if ($page['content'] != "" && $page['custom_html_placement'] == "1") {
                        echo "<div class='page-content-above-results inline-block btn-block'>".$page['content']."<div class='clear'></div></div>";
                    }

                    // if ($data_results[$dc['data_id']]['total'] == 0) {
                    //     echo widget("Bootstrap Theme - Member Results - Page Title");

                    // } else {
                    //     echo widget("Bootstrap Theme - Member Results - Sorting Options");
                    //     echo widget("Bootstrap Theme - Member Results - Page Title");
                    // }
                        // ItemList schema
                        if ($searcherror == "") {
                            $itemListName = htmlspecialchars(stripslashes($page['h1']), ENT_QUOTES, 'UTF-8');
                            $pageTitle = !empty($page['title']) ? html_entity_decode($page['title'], ENT_QUOTES, 'UTF-8') : $itemListName;
                            $GLOBALS['search_result_position'] = 0;
                            global $profs;
                        $schema_page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/' . strtolower($page['filename']);
                        if (strtolower($page['filename']) == 'search_results' && !empty($w['default_search_url']) && $w['default_search_url'] != 'search_results') {
                            $schema_page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/' . $w['default_search_url'];
                        }
                        $schema_url = $schema_page_url . '#entity';
                        $schema_has_location = (isset($profs['city_name']) && $profs['city_name'] != '') || (isset($profs['state_name']) && $profs['state_name'] != '') || (isset($profs['country_name']) && $profs['country_name'] != '');
                        $schema_has_category = (isset($profs['service_name']) && $profs['service_name'] != '') || (isset($profs['profession_name']) && $profs['profession_name'] != '');
                        $schema_site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
                        $schema_description = !empty($page['meta_desc']) ? htmlspecialchars(strip_tags($page['meta_desc']), ENT_QUOTES, 'UTF-8') : '';
                        $aboutContent = trim(strip_tags(stripslashes($page['h1'])) . (!empty($page['h1']) && !empty($page['h2']) ? ' - ' : '') . strip_tags(stripslashes($page['h2'])));
                        $collectionImage = '';
                        if(!empty($page['facebook_image'])) {
                            $collectionImage = $page['facebook_image'];
                        } elseif(!empty($page['hero_image'])) {
                            $collectionImage = $page['hero_image'];
                        } elseif(!empty($w['facebook_app_id'])) {
                            $collectionImage = $w['facebook_app_id'];
                        } elseif(!empty($w['website_logo'])) {
                            $collectionImage = $w['website_logo'];
                        } elseif(!empty($w['favicon'])) {
                            $collectionImage = $w['favicon'];
                        }
                        ob_start();
				?>
                <div itemscope itemtype="https://schema.org/CollectionPage" itemid="<?php echo htmlspecialchars($schema_url, ENT_QUOTES, 'UTF-8'); ?>">
                    <meta itemprop="name" content="<?php echo $pageTitle; ?>">
                    <meta itemprop="headline" content="<?php echo $itemListName; ?>">
                    <meta itemprop="description" content="<?php echo $schema_description; ?>">
					<?php if(!empty($collectionImage)) { ?>
					<link itemprop="primaryImageOfPage" href="<?php echo htmlspecialchars($collectionImage, ENT_QUOTES, 'UTF-8'); ?>">
					<?php } ?>
					<?php
					$keywordsParts = array();
					if(!empty($page['h1'])) $keywordsParts[] = strip_tags(stripslashes($page['h1']));
					if(!empty($w['website_name'])) $keywordsParts[] = $w['website_name'];
					if(!empty($w['profession'])) $keywordsParts[] = $w['profession'];
					if(count($keywordsParts) > 0) {
					?>
					<meta itemprop="keywords" content="<?php echo htmlspecialchars(implode(', ', $keywordsParts), ENT_QUOTES, 'UTF-8'); ?>">
					<?php } ?>
					<meta itemprop="inLanguage" content="<?php echo htmlspecialchars(isset($w['website_language']) ? $w['website_language'] : 'en', ENT_QUOTES, 'UTF-8'); ?>">
					<link itemprop="url" href="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>">
					<meta itemprop="mainEntityOfPage" content="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>#page">
					<meta itemprop="dateModified" content="<?php echo date('c', strtotime(date('Y-m-01'))); ?>">
					<meta itemprop="lastReviewed" content="<?php echo date('c', strtotime(date('Y-m-01'))); ?>">
					<meta itemprop="copyrightYear" content="<?php echo date('Y'); ?>">
					<link itemprop="copyrightHolder" href="<?php echo htmlspecialchars($schema_site_url, ENT_QUOTES, 'UTF-8'); ?>/#organization">
					<link itemprop="reviewedBy" href="<?php echo htmlspecialchars($schema_site_url, ENT_QUOTES, 'UTF-8'); ?>/#organization">
					<link itemprop="isPartOf" href="<?php echo htmlspecialchars($schema_site_url, ENT_QUOTES, 'UTF-8'); ?>/#website">
					<link itemprop="publisher" href="<?php echo htmlspecialchars($schema_site_url, ENT_QUOTES, 'UTF-8'); ?>/#organization">
					<?php
					$location_parts = array();
					if($schema_has_location) {
						if(isset($profs['city_name']) && $profs['city_name'] != '') { $location_parts[] = $profs['city_name']; }
						if(isset($profs['state_name']) && $profs['state_name'] != '') { $location_parts[] = $profs['state_name']; }
						if(isset($profs['country_name']) && $profs['country_name'] != '') { $location_parts[] = $profs['country_name']; }
					}
					$category_parts = array();
					if($schema_has_category) {
						if(isset($profs['profession_name']) && $profs['profession_name'] != '') { $category_parts[] = $profs['profession_name']; }
						if(isset($profs['service_name']) && $profs['service_name'] != '') { $category_parts[] = $profs['service_name']; }
					}
					if(count($category_parts) > 0) {
					?>
					<div itemprop="about" itemscope itemtype="https://schema.org/Service">
						<meta itemprop="serviceType" content="<?php echo htmlspecialchars(implode(' - ', $category_parts), ENT_QUOTES, 'UTF-8'); ?>">
						<?php if(count($location_parts) > 0) { ?>
						<meta itemprop="areaServed" content="<?php echo htmlspecialchars(implode(', ', $location_parts), ENT_QUOTES, 'UTF-8'); ?>">
						<?php } ?>
					</div>
					<?php } ?>
					<?php if(count($location_parts) > 0) { ?>
					<div itemprop="spatialCoverage" itemscope itemtype="https://schema.org/Place">
						<meta itemprop="name" content="<?php echo htmlspecialchars(implode(', ', $location_parts), ENT_QUOTES, 'UTF-8'); ?>">
					</div>
					<div itemprop="contentLocation" itemscope itemtype="https://schema.org/Place">
						<meta itemprop="name" content="<?php echo htmlspecialchars(implode(', ', $location_parts), ENT_QUOTES, 'UTF-8'); ?>">
					</div>
					<?php } ?>
                    <div itemprop="mainEntity" itemscope itemtype="https://schema.org/ItemList" itemid="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>#itemlist">
                        <meta itemprop="name" content="<?php echo $itemListName; ?>">
                        <link itemprop="url" href="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>">
                        <meta itemprop="description" content="<?php echo $schema_description ? $schema_description : htmlspecialchars($itemListName, ENT_QUOTES, 'UTF-8'); ?>">
                        <meta itemprop="mainEntityOfPage" content="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>#page">
                        <meta itemprop="itemListOrder" content="http://schema.org/ItemListOrderAscending">
                        <?php if(isset($_ENV['end']) && $_ENV['end'] > 0) { ?>
                        <meta itemprop="numberOfItems" content="<?php echo $_ENV['end']; ?>">
                        <?php } ?>
                        <?php echo $searchresults; ?>
                    </div>
                </div>
					<?php
					$schema_wrapped_results = ob_get_clean();
				}
                    if ($page['content'] != "" && ($page['custom_html_placement'] == "0" || $page['custom_html_placement'] == "")) { ?>
                        <hr>
                        <div role="tabpanel">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active" role="presentation">
                                    <a href="#results" aria-controls="results" role="tab" data-toggle="tab">%%%results_search_results_tab%%%</a>
                                </li>
                                <li role="presentation">
                                    <a href="#info" aria-controls="info" role="tab" data-toggle="tab">%%%read_more_search_tab%%%</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane" id="info">
                                    <?php echo $page['content']; ?>
                                </div>
                                <div role="tabpanel" class="tab-pane fade in active" id="results">
                                    <?php
                                    if ($searcherror == "") {
                                        echo $schema_wrapped_results;
                                    } else {
                                        echo $searcherror;
                                    } ?>
                                </div>
                            </div>
                        </div>

                    <?php } else {
                        if ($searcherror == "") {
                            echo $schema_wrapped_results;
                        } else {
                            echo $searcherror;
                        }
                    }
                    if ($page['content'] != "" && $page['custom_html_placement'] == "2") {
                        echo "<div class='page-content-below-results inline-block btn-block'>".$page['content']."<div class='clear'></div></div>";
                    }
                } ?>
            </div>

            <?php if ($page['menu_layout'] != '' && $page['seo_id'] != '') { ?>
                <?php
                if($page['form_name'] != ""){
                    switch ($page['menu_layout']) {
                        // Left Wide
                        case "1":
                            echo '<div class="col-md-4 col-md-pull-8 main-container">';
                            break;
                        // Right Wide
                        case "2":
                            echo '<div class="col-md-4 main-container">';
                            break;
                        // Left Slim
                        case "3":
                            echo "<div class='col-md-3 col-md-pull-9 main-container'>";
                            break;
                        // Right Slim
                        case "4":
                            echo "<div class='col-md-3 main-container'>";
                            break;
                    }
                    echo sidebar($page['form_name'], "", $w['website_id'], $w);
                    echo '</div>';
                }
                ?>
            <?php } else if ($wa['custom_45'] != 2) { ?>
            <?php
            switch ($wa['custom_45']) {
                case "0":
                    echo '<div class="col-md-3 col-md-pull-9 sidebar-container">';
                    break;
                case "3":
                    echo '<div class="col-md-4 col-md-pull-8 sidebar-container">';
                    break;
                case "1":
                    echo '<div class="col-sm-12 col-md-3 sidebar-container">';
                    break;
                case "4":
                    echo '<div class="col-sm-12 col-md-4 sidebar-container">';
                    break;
            }
            ?>
            <?php echo sidebar($dc['category_sidebar'],"",$w['website_id'],$w); ?>
        </div>
    <?php } ?>
    </div>
    <?php
    if ($page['content'] != "" && $page['custom_html_placement'] == "4") {
        echo '</div><div class="page-content-below-body"><div class="container">'.$page['content'].'</div><div class="clear"></div></div>';
    } ?>

    <?php if($_GET['devmode'] > 0){?>
        <script type="text/javascript">
            $(document).ready(function(){
                initializeMap();
                <?php
                foreach ($serviceAreas as $key => $serviceArea) {

                    if(strtolower($key) == 'main'){

                        echo "map.setCenter(new google.maps.LatLng(".$serviceArea['lat'].", ".$serviceArea['lng']."));";
                        echo "loadMarker(".$serviceArea['lat'].",".$serviceArea['lng'].",true);";

                    }

                    if($serviceArea['lat'] != 0 && $serviceArea['lng'] != 0 && $serviceArea['swlat'] != 0 && $serviceArea['swlng'] != 0 && $serviceArea['nelat'] != 0 && $serviceArea['nelng'] != 0){

                        echo "drawBounds(".$serviceArea['lat'].",".$serviceArea['lng'].",".$serviceArea['swlat'].",".$serviceArea['swlng'].",".$serviceArea['nelat'].",".$serviceArea['nelng'].",'".$serviceArea['color']."','".addslashes($serviceArea['address'])."',".$serviceArea['user_id'].",'".addslashes($serviceArea['name'])."');";

                    }

                    if($serviceArea['lat'] != 0 && $serviceArea['lng'] != 0 && $serviceArea['swlat'] == 0 && $serviceArea['swlng'] == 0 && $serviceArea['nelat'] == 0 && $serviceArea['nelng'] == 0){
                        echo "loadMarker(".$serviceArea['lat'].",".$serviceArea['lng'].",false,'".addslashes($serviceArea['name'])."');";
                    }
                }
                ?>

            });
        </script>
    <?php }
} ?>