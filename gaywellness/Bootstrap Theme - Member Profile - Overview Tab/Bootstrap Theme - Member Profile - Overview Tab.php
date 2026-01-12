<?php if ($user[subscription_id] == '87') { ?> [widget=Custom - Client Categories] 
<style>
	.nav-tabs {display:none;}
	
	.button-header, h2 {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    max-width: 320px;
    padding: 12px 22px;
    border-radius: 9999px;
    background-color: #ADC6C5;
    border: 1px solid #17718D;
    color: #0D3F4F;
    font-family: 'Nunito', sans-serif;
    font-weight: 600;
    font-size: 20px;
    text-decoration: none !important;
    cursor: pointer;
    transition: all 0.25s ease;
}
	.table-display-about_me {
		display:none;
	}
	
</style>  

<?php } ?>

<?php 
$subscription = getSubscription($user['subscription_id'],$w);

for ($section_order = 1; $section_order <= 5; $section_order++){
    if (($subscription['location_map_order'] == $section_order || (!isset($subscription['location_map_order']) && $section_order == 1)) && $user['lat'] != "" && $user['lon'] != ""){ ?>

        <div class="clearfix"></div>
        
    <?php } ?>

   <?php if (($subscription['user_quote_order'] == $section_order || (!isset($subscription['user_quote_order']) && $section_order == 2)) && $user['quote']!=""){ 
        $allowedTagsQuote = "<b><strong><em><i><del><ins>";
        if (($w) && (array_key_exists("quote_live_links", $w)) && ($w['quote_live_links'] == 1)) {
            $allowedTagsQuote .= "<a>";
        }
        ?>

        <div class="clearfix"></div><?php echo '<h2 class="hidden-xs tmargin tpad clearfix">';?>%%%more_about_label%%% <?php echo " ". '</h2>'; ?>
        <div class="well col-md-9 tmargin bmargin quote_box">
			
            <?php echo strip_tags($user['quote'],$allowedTagsQuote);?>
        </div>
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
        <? }
        else { /* Renders Deprecated Method for Displaying Members Contact Details */ ?>
            [widget=Bootstrap Theme - Deprecated - Member Profile - Display - Member Contact Details]
        <?php } ?>
    <?php } 

    if ($subscription['listing_details_order'] == $section_order || (!isset($subscription['listing_details_order']) && $section_order == 4)){ ?>
		<?php if ($user[subscription_id] != '87') {  ?>
  <div style=""><span class="button-header"><strong>Location</strong></span><br>

<?php if ($user['address1'] != "" && $user[subscription_id] != '71') { ?><?php echo $user['address1']; ?><br><?php } ?> 
<?php if ($user['address2'] != "" && $user[subscription_id] != '71') { ?><?php } ?> 
<?php if ($user['city'] != "") { ?><?php echo $user['city']; ?>,<?php } ?>  <?php if ($user['state_ln'] != "") { ?><?php echo $user['state_ln']; ?><?php } ?> <?php if ($user['zip_code'] != "") { ?> <?php echo $user['zip_code']; ?><?php } ?> <br>
<?php if ($user['country_ln'] != "") { ?><?php echo $user['country_ln']; ?><?php } ?> </strong><br>
</div>   <?php } ?>

<?php if ($user[subscription_id] != '87') {  ?>
 [widget=Custom - Profile Rates Mobile]
<?php } ?>

        <?php
            /* Renders Data from Members Listing Details Form if Field DISPLAY VIEW is YES */
            if ($w['respect_member_data_display_setting'] == "1") {
            $listing_details_form = $subscription['listing_details_form'];
            $vars['view'] = "display";
            $vars['table_classes'] = "class";
            $vars['display_blank_values'] = 1;
            echo showEmailFields($listing_details_form,$user,$vars,$w,false); ?>
        <? } else { /* Renders Deprecated Method for Displaying Members Listing Details Data */ ?>
            <h2 class="tmargin tpad xs-text-center xs-center-block clearfix">%%%listing_details%%%</h2>

            <?php echo $_ENV['member_rows']; ?>
        <?php }
    } ?>  
    
<?php
	
	
	echo $user['about_title'];
	
    if ($subscription['about_order'] == $section_order || (!isset($subscription['about_order']) && $section_order == 5)){ 
        if ($user['about_me'] !="" || $user['approach'] !="" && $subscription['show_about_tab'] != 0 ) {
			
            $about_user = $user['about_me'];
			$approach = $user['approach'];
            // neutralizes http image path to work with https
            $about_user = str_replace('src="http://','src="//',$about_user);
			$approach = str_replace('src="http://','src="//',$approach);
            if ($subscription['nofollow_links'] == "1") {
                // adds nofollow to links based on user subscription
                $about_user = str_replace(' href',' rel="nofollow" href',$about_user);
				$approach = str_replace(' href',' rel="nofollow" href',$approach);
            }
            if(!isset($w['allowed_tags'])){   
                $w['allowed_tags'] = '<img><h1><h2><h3><h4><h5><h6><b><p><span><ul><ol><li><strong><font><em><br><iframe><u><hr><blockquote><tr><th><td><thead><table><div>';
            }
            $allowedTags = $w['allowed_tags']; 
            if (($w) && (array_key_exists("about_live_links", $w)) && ($w['about_live_links'] == 1)) {
                // allows links in post if website advanced setting about_live_links equals 1
                $allowedTags .= "<a>";
            } ?>
			
			
	
  
        <?php     if ($user['about_me'] != "") { ?>
<div style=""><span class="button-header"><strong>About</strong></span></div>
<?php  }  ?>
<?php   echo '<div class="clearfix"></div>' . strip_tags($about_user,$allowedTags) .'<div class="clearfix"></div>'; ?>
 <?php
			if ($user['approach'] != "") { ?>
<div style=" padding-top:0px;"><span class="button-header"><strong>My Approach</strong></span></div>  <?php } ?>
          <?php  echo '<div class="clearfix"></div>' . strip_tags($approach,$allowedTags) .'<div class="clearfix"></div>';  ?>
			
<?php } } 
}
?>
<div>


</div>
<div class="clearfix"></div>

<div class="clearfix"></div>