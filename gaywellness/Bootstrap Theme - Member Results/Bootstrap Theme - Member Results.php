<?php
$page = getMetaData('list_seo',$page['seo_id'],$page,$w);
$loggedUser = getUser($_COOKIE['userid'], $w);
$myLevels = explode(",", $page['allowed_products']);
$notAllowedMember = true;
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
        #first_container > .container:first-child .clearfix.body-content{
            display:none;
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
                        
                    }
                    if ($page['h2'] != "") {
                        echo "<h2 class='hero_h2'>" . stripslashes($page['h2']) . "</h2>";
                    }
                    if ($page['hero_section_content'] != ""){
                        echo "<div class='clearfix'></div><div class='hero_content'>".replaceChars($w, stripslashes($page['hero_section_content']))."</div>";
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
            if($_GET['devmode'] > 0){
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
        <?php } ?>
    
        <?php if ($page['menu_layout'] != '' && $page['seo_id'] != '') { ?>
    
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
        } else { ?>
			<?php if ($dc['category_sidebar'] == "" || $wa['custom_45'] == "2") { ?>
                <div class="col-lg-12">
			<?php } else if ($dc['category_sidebar'] != "") {
                switch ($wa['custom_45']) {
                    case "0":
                        echo "<div class='col-md-9 col-md-push-3'>";
                        break;
                    case "3":
                        echo "<div class='col-md-8 col-md-push-4'>";
                        break;
                    case "1":
                        echo "<div class='col-md-9'>";
                        break;
                    case "4":
                        echo "<div class='col-md-8'>";
                        break;
                }
       		}
		}
        if ($_ENV[error]!="") { ?>
            <?php echo widget("Bootstrap Theme - Member Results - Page Title",'',$w['website_id'],$w); ?>
            <div id="top-alert">%%%invalid_location_search%%%</div>
    
        <?php } else if ($_ENV[error] == "") {
            echo eval("?>".$page[content_header]."<?");
    
            if ($page['content'] != "" && $page['custom_html_placement'] == "1") {
                echo "<div class='page-content-above-results inline-block btn-block'>".$page['content']."<div class='clear'></div></div>";
            } 
    
            if ($data_results[$dc['data_id']]['total'] == 0) {
                echo widget("Bootstrap Theme - Member Results - Page Title",'',$w['website_id'],$w);
    
            } else {
                echo widget("Bootstrap Theme - Member Results - Sorting Options",'',$w['website_id'],$w);
                echo widget("Bootstrap Theme - Member Results - Page Title",'',$w['website_id'],$w);
            }
            
            if ($page['content'] != "" && ($page['custom_html_placement'] == "0" || $page['custom_html_placement'] == "")) { ?>
                
                <hr>
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active">
                            <a href="#results" aria-controls="results" role="tab" data-toggle="tab">%%%results_search_results_tab%%%</a>
                        </li>
                        <li>
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
                                echo $searchresults;
                            } else {
                                echo $searcherror;
                            } ?>
                        </div>
                    </div>
                </div>
    
            <?php } else {
                
                if ($searcherror == "") {
                    echo $searchresults;
    
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
     <?php } else if ($wa['custom_45'] != 2) { ?>
        <?php 
        switch ($wa['custom_45']) {
            case "0":
                echo '<div class="col-md-3 col-md-pull-9">';
                break;
            case "3":
                echo '<div class="col-md-4 col-md-pull-8">';
                break;
            case "1":
                echo '<div class="col-sm-12 col-md-3">';
                break;
            case "4":
                echo '<div class="col-sm-12 col-md-4">';
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