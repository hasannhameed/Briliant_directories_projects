<style>
    .tab-content{
            padding: 0px;
    background: transparent;
    border: none;
    }
    .table-view{
        background-color: #ffffff;
        padding: 10px 20px;
        border-radius: 20px;
        box-shadow: -1px 1px 8px rgb(0 0 0 / 8%);
        margin-bottom: 20px !important;
    }
    .tab-pane .search_result+.clearfix{
        background-color: #ffffff;
        padding: 10px 20px;
        border-radius: 20px;
        box-shadow: -1px 1px 8px rgb(0 0 0 / 8%);
        margin-bottom: 20px !important;
    }
    .img_section,.mid_section{
        margin: 30px 0px;
    }
  

    .profile-tabs-nav {
        display: flex !important;
        flex-wrap: nowrap;
        overflow-x: auto !important;
         width: 100%;
    }

    .profile-tabs-nav > li {
        flex: 1;               
        text-align: center;    
    }

    .profile-tabs-nav > li a {
        width: 100%;            
        display: block;       
    }

</style>
<style>
.tabs-container {
    overflow-x: hidden;
}

.profile-tabs-nav {
    padding: 0;
    margin: 0;
    border-bottom: 2px solid #e0e0e0; 
}


.profile-tabs-nav li a {
    background: transparent !important;
    padding: 10px 18px; 
    color: #495057; 
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent; 
    text-decoration: none;
    display: block; 
    font-weight: 500;
}

.member-profile-tabs .tabs-container {
    display: flex;
    align-items: center;
    border-radius: 8px 8px 0 0;
    background-color: transparent;
}
.profile-tabs-nav li a:hover {
    color: #000;
    border-radius: 20px !important;
}


.profile-tabs-nav li.active a {
    color: #007bff; 
    border-bottom-color: #007bff;
    font-weight: 600; 
    border-radius: 20px !important;
}


.profile-tabs-nav li a small {
    margin-left: 5px;
    font-size: 0.8em;
    padding: 2px 5px;
    border-radius: 20px;
    background-color: #f0f0f0; 
    color: #6c757d;
}


.profile-tabs-nav li.active a small {
    background-color: #007bff; 
    
}

.profile-tabs-nav {

    border-bottom: none !important; 
}

.profile-tabs-nav li {
    
    margin-right: 10px;
    margin-bottom: 5px; 
}

.profile-tabs-nav li a {
    border-radius: 20px;
    padding: 8px 15px;
    font-weight: 500;
    background-color: #f5f5f5 !important;
    color: #343a40 !important;
    border-bottom: none !important;
    border: 1px solid #e0e0e0;
}


.profile-tabs-nav li a:hover {
    background-color: #e9ecef !important;
}


.profile-tabs-nav li.active a {

    border-radius: 20px;
    padding: 8px 15px;
    font-weight: 500;
    background-color: #f8f9fa !important;
    color: #343a40 !important;
    border-bottom: none !important;
}


.profile-tabs-nav li a small {
    margin-left: 5px;
    font-size: 0.8em;
    padding: 2px 5px;
    border-radius: 20px;
    background-color: #e9ecef; 
    color: #495057;
}


.profile-tabs-nav li.active a small {
 
    background-color: rgba(255, 255, 255, 0.3); 
     border-radius: 20px;
}
</style>

<?php
$subscription = getSubscription($user['subscription_id'], $w);
$multi_location = addonController::isAddonActive('multi_location');
$sectionAmount = 5;
if ($multi_location) {
    $sectionAmount = 6;
}

for ($section_order = 1; $section_order <= $sectionAmount; $section_order++){
    if (($subscription['location_map_order'] == $section_order || (!isset($subscription['location_map_order']) && $section_order == 1)) && $user['lat'] != "" && $user['lon'] != "") {
        $get_address = trim($user['address1']) . " " . trim($user['address2']) . " " . trim($user['city']) . " " . trim($user['state_ln']) . " " . trim($user['zip_code']) . " " . trim($user['country_ln']);
        $full_address = str_replace(' ', '+', $get_address);
        ?>        
        <div class="clearfix"></div>		
		<?php if(!empty($label['get_directions_label']) || !empty($label['sidebar_viewmap'])){ ?>
			<div class="alert alert-secondary fpad-sm nomargin bg-secondary no-radius-bottom profile-map-header">
				<?php if(!empty($label['get_directions_label'])){?>
					<a class="btn btn-sm btn-secondary bold map-link get-directions-link" rel="nofollow" target="_blank" href="https://maps.google.com/maps?daddr=<?php echo $full_address ; ?>" title="%%%click_for_directions%%%">
						%%%get_directions_label%%%
					</a>
				<?php } ?>
				<?php if(!empty($label['sidebar_viewmap'])){?>
					<a class="btn btn-sm btn-secondary bold pull-right map-link larger-map-link" href="#" target="_blank" data-target="#locationModal" data-toggle="modal" title="%%%sidebar_viewmap%%%">
						%%%sidebar_viewmap%%%
					</a>
				<?php } ?>
				<div class="clearfix"></div>
			</div>
		<?php } ?>
        <div class="clearfix"></div>
		<div id="map-canvas" class="no-radius-top"></div>
		<div class="clearfix bmargin"></div>
    <?php }

    if (($subscription['user_quote_order'] == $section_order || (!isset($subscription['user_quote_order']) && $section_order == 2)) && $user['quote']!=""){
        $allowedTagsQuote = "<b><strong><em><i><del><ins>";
        if (($w) && (array_key_exists("quote_live_links", $w)) && ($w['quote_live_links'] == 1)) {
            $allowedTagsQuote .= "<a>";
        }
        ?>
        <div class="clearfix"></div>
        <hr>
        <div class="well bmargin tmargin quote_box">
            <?php echo strip_tags($user['quote'],$allowedTagsQuote);?>
		</div>
		<div class="clearfix"></div>
    <?php }

    if ($subscription['contact_details_order'] == $section_order || (!isset($subscription['contact_details_order']) && $section_order == 3)){ ?>
        <?php
        /* Renders Data from Members Contact Details Form if Field DISPLAY VIEW is YES */
        if ($w['respect_member_data_display_setting'] == "1") {
            $contact_details_form = $subscription['contact_details_form'];
            $vars['view'] = "display";
            $vars['table_classes'] = "class";
            $vars['display_blank_values'] = 1;
            echo showEmailFields($contact_details_form,$user,$vars,$w,false); ?>
        <?php }
        else { /* Renders Deprecated Method for Displaying Members Contact Details */ ?>
            [widget=Bootstrap Theme - Deprecated - Member Profile - Display - Member Contact Details]
        <?php } ?>
    <?php }

    if ($subscription['listing_details_order'] == $section_order || (!isset($subscription['listing_details_order']) && $section_order == 4)){ ?>
        <?php
        /* Renders Data from Members Listing Details Form if Field DISPLAY VIEW is YES */
        if ($w['respect_member_data_display_setting'] == "1") {
            $listing_details_form = $subscription['listing_details_form'];
            $vars['view'] = "display";
            $vars['table_classes'] = "class";
            $vars['display_blank_values'] = 1;
            echo showEmailFields($listing_details_form,$user,$vars,$w,false); ?>
        <?php } else { /* Renders Deprecated Method for Displaying Members Listing Details Data */ ?>
            <h2 class="tmargin tpad xs-text-center xs-center-block clearfix">%%%listing_details%%%</h2>
            <?php echo $_ENV['member_rows']; ?>
        <?php }
    }

    if ($subscription['about_order'] == $section_order || (!isset($subscription['about_order']) && $section_order == 5)){
        if ($subscription['show_about_tab'] != 0 ) {
			// Wrap section in div for targeting
			echo '<div class="overview-tab-about-me">';
            // We get all the about me form fields to be display
            form_controller::showAboutFormFields($subscription,$user);
			echo '<div class="clearfix"></div></div>';
        } ?>
    <?php }
    if($subscription['list_services_areas_order'] == $section_order || (!isset($subscription['list_services_areas_order']) && $section_order == 6)){
		// Wrap section in div for targeting
		echo '<div class="tmargin tpad overview-tab-service-areas">';
		echo widget("Bootstrap Theme - Member Profile - Display Service Area Links");
		echo '<div class="clearfix"></div></div>';
    }
}
?>
<div class="clearfix"></div>