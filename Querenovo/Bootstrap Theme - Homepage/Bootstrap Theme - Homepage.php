<?php
    function printHomepageCallToAction($position = "under"){
        global $wa, $w;
		
        if(is_null($wa['search_box_placement'])){
            $wa['search_box_placement'] = 'under';
        }
        $positionPlacement = $wa['search_box_placement'];
        if($wa['custom_30'] == "Bootstrap Theme - Homepage Search - Hero Message with Link" || $wa['custom_30'] == "Bootstrap Theme - Homepage Search - Hero Message with Button Links"){
            $positionPlacement = 'inside_primary';
        }
        if($position != "inside_primary" && ($wa['custom_30'] == "Bootstrap Theme - Homepage Search - Hero Message with Link" || $wa['custom_30'] == "Bootstrap Theme - Homepage Search - Hero Message with Button Links")){
           return;
        }
       
        if($position == $positionPlacement){
            if ($wa['custom_267'] == 1) {
                if ($_COOKIE['userid'] > 0) {
                    echo widget($wa['custom_30'],'',$w['website_id'],$w);
                } else {
                    echo widget('Bootstrap Theme - Homepage Search - Member Login Form','',$w['website_id'],$w);
                }
            } else {
                echo widget($wa['custom_30'],'',$w['website_id'],$w);
            }
        }
    }

if (strpos($wa['custom_30'],'Map') !== false) {
    $clickableMapsAddOn = getAddOnInfo('clickable_maps','d14aabb4bbe44caf9a45c3c50edb9558');
    if (isset($clickableMapsAddOn['status']) && $clickableMapsAddOn['status'] == 'success') {
        echo widget($clickableMapsAddOn['widget'],"",$w['website_id'],$w);
    }

    ?>
    </div></div> <!-- closes container + content-container -->
<?php } else if ($wa['custom_30']!="Blank") { ?>
    <div class="row-fluid row homepage_settings">
            <div class="col-xs-12 col-sm-12 col-md-<?php if ($wa['custom_216'] != "") { echo $wa['custom_216']; } else { ?>7<?php }?> center-block homepage_title primary-hero-content">
                <?php if ($wa['custom_130'] != "" && $wa['custom_30'] != "Bootstrap Theme - Homepage Search - Hero Message with Link" && $wa['custom_30'] != "Bootstrap Theme - Homepage Search - Hero Message with Button Links"){ ?>
                    <h1 class="sm-text-center" style='margin:0;'><?php echo replaceChars($w,$wa['custom_130']);?></h1>
				    <h3 class="sm-text-center text-default text-center custom-title">Trouvez les professionnels de la rénovation énergétique pour tous types de bâtiments : logements, tertiaire, industrie, collectivités​​</h3>
                <?php } ?>
                <?php 
                    printHomepageCallToAction('inside_primary');
                  if ($wa['custom_30']=="Hide Homepage Hero Search" && $wa['custom_131']!="") { ?>
                    <div class="center-block nopad homepage_title">
                        <h2 class="sm-text-center"><?php echo replaceChars($w,$wa['custom_131']);?></h2>
						<div class="center-block nopad homepage_title">
                        
                    </div>
                    </div>
                <?php } ?>
            </div>
            <?php if ($wa['custom_216'] != "12" && $wa['custom_25'] != "center" && ($wa['secondary_hero_content_homepage'] != "" || $wa['search_box_placement'] == "inside_secondary")) { ?>
            <div class="col-xs-12 col-sm-12 col-md<?php if ($wa['custom_216'] != "") { echo $wa['custom_216'] - 12; } else { ?>5<?php }?> center-block secondary-hero-content <?php if($wa['secondary_mobile_display'] == "none" ){ ?>hidden-xs<?php }?>">
                <?php echo $wa['secondary_hero_content_homepage'];
                printHomepageCallToAction('inside_secondary');
                ?>
            </div>
        <?php } ?>
		<div class="clearfix"></div>
		<div class="col-md-12">
			[widget=Bootstrap Theme - Homepage Search - Extra Content Above]
			<div class="clearfix"></div>
			<?php if($wa['custom_30']!="Hide Homepage Hero Search") { ?>
				<div class="clearfix"></div>
				<?php printHomepageCallToAction('under'); ?>
				<div class="clearfix"></div>
			<?php } ?>
			<div class="clearfix"></div>
			[widget=Bootstrap Theme - Homepage Search - Extra Content Below]
			<div class="clearfix"></div>
		</div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    </div></div> <!-- closes container + content-container -->
<?php } else if ($wa['custom_30']=="Blank") { ?>
    <div class="clearfix" style="margin-top: -40px;"></div>
    </div></div> <!-- closes container + content-container -->
<?php } ?>


<div class="homepage-sections fr-view">
    <?php
    for($x = 1; $x <= 15; $x++){
    if($x >= 14){
        $current_x = $x + 195;
    } else {
        $current_x = $x + 95;
    }
    $customValue = "custom_".$current_x;

    $hideOnMobile = hideStreamingOnMobile($customValue,$wa);
    $hideOnMobileClass = '';
     if($hideOnMobile){
         $hideOnMobileClass = ' hidden-xs';
     }
    
    
    if ($wa[$customValue] != "" && $wa[$customValue] == "Homepage Content") { ?>
        <div class="content-container custom-homepage-content homepage-section-<?php echo $x . $hideOnMobileClass?>">
            <div class="container">
                <?php echo eval("?>".$page['content']."<?")?>
            </div>
        </div>
    <?php } else if ($wa[$customValue] != "") { ?>
    <div class="homepage-section-<?php echo $x . $hideOnMobileClass; ?>"><?php if($wa[$customValue] == "Bootstrap Theme - Display - Events Calendar"){ ?>
        <div class="clearfix clearfix-lg"></div>
        <div class="content-container">
            <div class="container">
                <?php if ($wa['homepage_calendarSidebarToggle'] != ""){ ?>
                <div class="col-md-8 <?php if ($wa['homepage_calendarSidebarToggle'] == "left"){ ?>pull-right<?php } ?>" <?php if ($wa['homepage_calendarTitle'] == "" && $wa['homepage_calendarSubTitle'] == "") { ?>style="margin-top: -30px;"<?php } ?>>
                    <?php } ?>
                    <h1 class="text-center bold" <?php if($wa['calendar_tColor'] != ""){ ?>style="color: <?php echo $wa['calendar_tColor']; ?>"<?php } ?>>
                        <?php echo $wa['homepage_calendarTitle']; ?>
                    </h1>
                    <h2 class="text-center bold" <?php if($wa['calendar_tColor'] != ""){ ?>style="color: <?php echo $wa['calendar_tColor']; ?>"<?php } ?>>
                        <?php echo $wa['homepage_calendarSubTitle']; ?>
                    </h2>
                    <?php echo widget($wa[$customValue],'',$w['website_id'],$w); ?>
                    <?php if ($wa['homepage_calendarSidebarToggle'] != "" && $wa['homepage_calendarSidebar'] != ""){ ?>
                </div>
                <div class="col-md-4">
                    <?php echo sidebar($wa['homepage_calendarSidebar'],"",$w['website_id'],$w); ?>
                </div>
            <?php } ?>
            </div>
        </div>
        <?php } else {
            echo widget($wa[$customValue], '', $w['website_id'], $w);
        } ?></div>
    <?php }
    }
?>
</div>
<script>
// document.addEventListener("DOMContentLoaded", function () {
//     const form = document.querySelector(".website-search");

//     if (form) {
//         form.action = "/";   
//     }
// });

</script>