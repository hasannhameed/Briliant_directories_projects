<?php
$sub = getSubscription($user['subscription_id'],$w);
//get the review data_id
$reviewIdQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
        *
    FROM
        `data_categories`
    WHERE
        data_type = '13'
    LIMIT
        1");
$reviewId = mysql_fetch_assoc($reviewIdQuery);
$reviewState = 0;
foreach ($sub['data_settings'] as $sdskey => $sdsvalue) {

    if ($sdsvalue == $reviewId['data_id']) {
        $reviewState = 1;//ON
    }
}
$userPhoto = getUserPhoto ($user['user_id'], $user['listing_type'], $w);
$userPhoto = $userPhoto['file'];

if($sub['coverPhoto'] == 1){
    addonController::showWidget('profile_cover_photo', '023876071143573e41dd0e03f5f56894','');
}
?>

<div class="member-profile-header">
    <div class="col-xs-12 <?php if ($subscription['profile_badge'] != "" || $user['nationwide'] == 1) { ?>col-sm-2<? } else { ?>col-sm-3<?php } ?> norpad text-center xs-nopad xs-bmargin">
        <?php
        if ($sub['receive_messages'] != 1) { ?>
            <a href="/<?php echo $user['filename']; ?>/connect" title="%%%contact_label%%% <?php echo $w['profession']; ?> <?php echo $user['full_name'];?>">
                <img class="img-rounded" src="<?php echo $userPhoto;?>" alt="<?php echo $w['profession']; ?> <?php echo $user['full_name']; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>" title="%%%contact_label%%% <?php echo $user['full_name']; ?>">
            </a>
        <? } else { ?>
            <img class="img-rounded" src="<?php echo $userPhoto;?>" alt="<?php echo $w['profession']; ?> <?php echo $user['full_name']; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>" title="%%%contact_label%%% <?php echo $user['full_name']; ?>">
        <?php } ?>
    </div>
    <div class="xs-text-center col-xs-12 col-sm-9">
        <div class="row">
            <div class="col-sm-10 norpad xs-nopad">
				<?php
          if ($user[subscription_id] != '71') { ?>

                <h1 class="bold inline-block">
                    <?php echo $user['company']; ?>  <?php if ($user['company'] != "" && $user['first_name'] != "") { ?>    by  <?php } ?> <?php echo $user['first_name']; ?>
                </h1>   <?php } ?>
				<?php
          if ($user[subscription_id] == '71') { ?>
				 <h1 class="bold inline-block capitalize">
                    <?php echo $user['first_name']; ?> <?php echo $user['last_name']; ?>
                </h1>   <?php } ?>
				<br>				
				<?php $memberSubCategories = getMemberSubCategory($user_data['user_id'],"all","first",intval($w['profile_services_display_limit']),"text");
					?>
				<div class="hidden-xs nomargin sub_category_in_results">
					<div class="list-subs-profile bmargin">
						<?php echo getMemberSubCategory($user[user_id], "sub", "first", "all", "linked");?>
					</div>
				</div>
			
				
			</div>
			<?php
			$addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
			if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
				echo "<div class='col-sm-1 text-right nolpad bmargin xs-nopad xs-text-center' style='float:right; margin-top:-20px;'>";
				echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
				echo "</div>";
			} ?>
        </div>
     
			

<div class="col-md-7">
			 <?php
            //review on && can send leads,show phone || review on && can't send leads, hide phone
            if ($reviewState == 1 && (($sub['receive_messages'] != 1 && $user['phone_number'] != "" && $sub['show_phone'] == 1) || ($subscription['receive_messages'] == 1 && ($subscription['show_phone'] != 1 || $sub['show_phone'] == 1 && $user['phone_number'] == "")))) { ?>
                <div class="col-sm-12 tmargin profile-header-write-review" style="margin-top:20px; padding-left:0px;">
                    <?php
                    if ($reviewState == 1 && $subscription['hide_reviews_rating_options'] == 0) {
                       
                    } ?>
                   
                </div>
	
 <a href="#div3" rel=" nofollow" aria-controls="t3" aria-label="<?php echo eval("?>" . strip_tags($tab) . "<?"); ?>" role="tab" data-toggle="tab" aria-selected="false">
                                    <?php echo eval("?>" . $tab . "<?"); ?>
                                    <?php echo $dc['Reviews']; ?> <?php echo $rating['stars'];         ?></a>
              
            <?php } else { ?>  <?php
                    if ($reviewState == 1 && $subscription['hide_reviews_rating_options'] == 0) {
                        echo $rating['stars'];
                    } ?>
               
            <?php } ?>
			  <?php //echo $subscription['hide_reviews_rating_options'] ;?>

            <?php
            //reviews on && can send leads,hide phone || can't send leads, show phone
            if ($reviewState == 1 && (($subscription['receive_messages'] == 1 && $subscription['show_phone'] == 1 && $user['phone_number'] != "") || ($sub['receive_messages'] != 1 && ($sub['show_phone'] == 0 ||  $sub['show_phone'] == 1 && $user['phone_number'] == "")))) {
                $reviewsAmount = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT 1
                FROM
                    `users_reviews`
                WHERE
                    user_id = ".$user['user_id']." AND review_status = 2");
                $memberReviewsExist = mysql_fetch_array($reviewsAmount);
                ?><div class="clearfix"></div>
                <div class="col-sm-5 col-sm-offset-7 profile-header-write-review" style="margin-top:<?php if ($memberReviewsExist == false || $subscription['hide_reviews_rating_options'] == 1){echo '0px';}else{echo '0px';}?>;">


                   
                   
                </div>
            <?php } ?>
			<div class="hidden-sm hidden-md hidden-lg hidden-xl ">    <div class="col-sm-12 tmargin xs-nomargin">
				
				
                <p class="line-height-xl nomargin" style="font-size: 16px;">
                    <?php
                    if ($sub['profile_layout'] != "0" && (!empty($user['city']) || !empty($user['state_ln']) || !empty($user['zip_code']) || !empty($user['country_ln']))) {
                        echo '<span class=small profile-header-location><i class="fa fa-map-marker" style="color:#d77d64;"></i> Based in ';

                        if (!empty($user['city']) || !empty($user['state_ln']) || !empty($user['zip_code'])) {
                            if (!empty($user['city'])) { 
                                echo $user['city'];
                            }
                            if (!empty($user['state_ln'])) {
                                if(!empty($user['city'])) {
                                    echo ", ";
                                }
                                echo $user['state_ln'];
                            }
                            if (!empty($user['zip_code'])) {
                                if(!empty($user['city']) || !empty($user['state_ln'])) {
                                    echo ", ";
                                }
                                echo $user['zip_code'];
                            }
                        } else if (!empty($user['country_ln'])) {
                            echo $user['country_ln'];
                        }
                        echo "</span>";
                    } ?>
					<?php if ($user['willing_to_travel'] != "") { ?>
					<div class="clearfix "></div> 
					<div class="hidden-xs small nomargin ">
						<strong>Willing to Travel:</strong>  <?php echo $user['willing_to_travel']; ?> <?php if ($user['distance'] != "mi") { ?> mi <?php } ?>  <?php if ($user['distance'] != "km") { ?> km <?php } ?>
						</div>
				
				     <?php } ?>
				
                </p>
					
            </div>

			 </div>
 <div class="hidden-xs"> 
            <div class="col-sm-12 tmargin xs-nomargin" style="padding-left:0px;">
				
				
                <p class="line-height-xl nomargin">
                    <?php
                    if ($sub['profile_layout'] != "0" && (!empty($user['city']) || !empty($user['state_ln']) || !empty($user['zip_code']) || !empty($user['country_ln']))) {
                        echo '<span class=small profile-header-location><i class="fa fa-map-marker" style="color:#d77d64;"></i> Based in ';

                        if (!empty($user['city']) || !empty($user['state_ln']) || !empty($user['zip_code'])) {
                            if (!empty($user['city'])) { 
                                echo $user['city'];
                            }
                            if (!empty($user['state_ln'])) {
                                if(!empty($user['city'])) {
                                    echo ", ";
                                }
                                echo $user['state_ln'];
                            }
                            if (!empty($user['zip_code'])) {
                                if(!empty($user['city']) || !empty($user['state_ln'])) {
                                    echo ", ";
                                }
                                echo $user['zip_code'];
                            }
                        } else if (!empty($user['country_ln'])) {
                            echo $user['country_ln'];
                        }
                        echo "</span>";
                    } ?><?php if ($user['willing_to_travel'] != "") { ?>
					<div class="clearfix "></div> 
					<div class="hidden-xs small nomargin ">
						<strong>Willing to Travel:</strong>  <?php echo $user['willing_to_travel']; ?> <?php if ($user['distance'] != "mi") { ?> mi <?php } ?>  <?php if ($user['distance'] != "km") { ?> km <?php } ?>
						</div>
				
				     <?php } ?>
                </p>
            </div>
	 
	 
</div>
  <div class=" " style="margin-top:10px"> 
	<?php if ($user['verified']==1) { ?>
		<div class="col-sm-2 ">
			<a href="#" data-original-title="%%%verified_badge_title%%%" id="popoverData" rel="popover" data-placement="left" data-trigger="hover" class="member_popover" data-content="%%%verified_badge_content%%%">
				<?php if ($w['verified_member_image']!="") { ?>
				<img style="margin-left:-15px; float-left; height:60px;" alt="%%%verified_badge_title%%%" src="<?php echo $w['verified_member_image'];?>">
				<?php } else { ?>
				<img style="margin-left:-15px; float-left; height:60px;" " alt="%%%verified_badge_title%%%" src="/images/verified_icon.png">
				<?php } ?>
			</a>
		</div> 
	 <?php } ?>
  <?php 
            if (strpos($user['where_i_work'], "Online Sessions") !== false) { ?>

		<div class="col-xs-4 col-xs-offset-4 hidden-sm- hidden-md hidden-md hidden-lg hidden-xl">
				<img style="margin-left:-15px; float-left; max-width:80px; max-height:80px;" alt="Online Sessions" src="/images/icons/icon-online.png"></div>

				
		</div> 
<div class="col-sm-3 hidden-xs">
				<img style="margin-left:-15px; float-left; max-width:80px; max-height:80px;" alt="Online Sessions" src="/images/icons/icon-online.png">
			
		</div> 

	 <?php } ?>
    <?php 
            if (strpos($user['status_check'], "Monkey") !== false) { ?>

		<div class="col-xs-4 col-xs-offset-4 hidden-sm- hidden-md hidden-md hidden-lg hidden-xl ">				
 <img style="margin-left:-15px; float-left; max-width:80px; max-height:80px;" alt="No Monkey Business" src="/images/icons/no-monkey.webp">
  <div class="hoverinfo">     <p>This icon means that the practitioner is strictly offering therapeutic services (not of a sensual or erotic nature).
</p>

</div>
		</div> 
<div class="col-sm-3 hidden-xs">
				<img style="margin-left:-15px; float-left; max-width:80px; max-height:80px;" alt="No Monkey Business" src="/images/icons/no-monkey.webp">
				  <div class="hoverinfo">    <p>This icon means that the practitioner is strictly offering therapeutic services (not of a sensual or erotic nature).
</p>

</div>
		</div> 
<div class="clearfix "></div> 
	 <?php } ?>

</div>



</div><div class="col-md-5">
 <?php
            //can send leads
            if ($sub['receive_messages'] != 1) { ?>
                <div class="col-sm-12 tmargin profile-header-send-message">
                    <a class="btn btn-sms btn-block btn-lg" title="%%%contact_label%%% <?php echo $user['full_name']; ?>" href="/<?php echo $user['filename']; ?>/<?php echo $w['default_connect_url'];?>">
                    <?php if ($w['enable_direct_chat_messages'] == "1" && $subscription['enable_direct_messages'] == "1") { ?>
                        %%%send_message_action%%%
                    <?php } else { ?>
                        %%%profile_send_message_button%%%
                    <?php } ?>
                    </a>
                </div>
            <?php } ?>
<?php if ($user['phone_whatsapp'] == "Use Whatsapp link for contacting") { ?>    
<div class="tmargin col-sm-12  profile-header-write-review">
<a title="WhatsApp" class="btn btn-primary btn-block btn-lg weblink" target="_blank" href="https://wa.me/<?php echo $user['phone_number']?>/?text=(from%20Gay%20Wellness)">
<i class="fa fa-whatsapp"></i> Contact via Whatsapp
</a> 
</div>
<?php if ($user['sms_number'] != "" && $subscription['show_phone'] == 1 && $user['display_sms_button'] != "No")  {  ?>    <div class="col-sm-12  profile-header-write-review" >
<a title="SMS" class="btn button-header btn-block btn-lg weblink" href="sms://<?php echo $user['sms_number']?>"><span class="blinking"><i class="fa fa-comment"></i></span> Click Here to Text</a> </div>
<?php } else if ($user['phone_number'] != "" && $subscription['show_phone'] == 1)  {  ?>     <div class="col-sm-12  tmargin profile-header-send-message">  
<a class="btn button-header btn-block btn-lg weblink" title="SMS" href="sms://<?php echo $user['phone_number']?>"><span class="blinking"><i class="fa fa-comment"></i></span> Click Here to Text</a></div>
<?php } ?>
<?php } ?>
<?php if ($user['phone_whatsapp'] != "Use Whatsapp link for contacting") { ?> 
<?php if ($user['sms_number'] != "" && $subscription['show_phone'] == 1 && $user['display_sms_button'] != "No")  {  ?>    <div class="col-sm-12   profile-header-write-review" >
<a title="SMS" class="btn button-header btn-block btn-lg weblink" href="sms://<?php echo $user['sms_number']?>"><span class="blinking"><i class="fa fa-chat"></i></span> Click Here to Text</a> </div>
<?php } else if ($user['phone_number'] != "" && $subscription['show_phone'] == 1)  {  ?>     <div class="col-sm-12 col-md-offset-  tmargin profile-header-send-message">  
<a class="btn button-header btn-block btn-lg weblink" title="SMS" href="sms://<?php echo $user['phone_number']?>"><span class="blinking"><i class="fa fa-comment"></i></span> Click Here to Text</a></div>
<?php } ?>
<?php } ?>






 <?php //can send leads
            if ($sub['receive_messages'] != 1) { ?>
				  
				  
 <?php

            //show phone number

            if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>

                <div class="col-sm-12  tmargin author-phone-pointer">

                    <?php

                    $clickPhoneAddOn = getAddOnInfo("click_to_phone","16c3439fea1f8b6d897987ea402dcd8e");

                    $statisticsAddOn = getAddOnInfo("user_statistics_addon","7f778bc02f0e6acbbd847b4061c7b76d");


                    if(isset($clickPhoneAddOn['status']) && $clickPhoneAddOn['status'] === 'success'){

                        echo widget($clickPhoneAddOn['widget'],"",$w['website_id'],$w);

                    } else if (isset($statisticsAddOn['status']) && $statisticsAddOn['status'] === 'success') {

                        echo widget($statisticsAddOn['widget'],"",$w['website_id'],$w);

                    } else {

                        if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>

                            <span style="padding:10px 16px;" class="well nobmargin text-center btn-lg btn-block author-phone">

                                %%%show_phone_number_icon%%%

                                <?php echo $user['phone_number']; ?>

                            </span>

                        <? }

                    } ?>

                </div>

            <? } ?>
				  
				  
            <?php } ?>

		 
<?php
            //review on && can send leads,show phone || review on && can't send leads, hide phone
            if ($reviewState == 1 && (($sub['receive_messages'] != 1 && $user['phone_number'] != "" && $sub['show_phone'] == 1) || ($subscription['receive_messages'] == 1 && ($subscription['show_phone'] != 1 || $sub['show_phone'] == 1 && $user['phone_number'] == "")))) { ?>
                <div class="col-sm-12  profile-header-write-review" style="margin-top:px;">
               
                    <?php
                    if ($reviewState == 1) { ?>
                        
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            <?php } else { ?>
                <div class="clearfix"></div>
            <?php } ?>


           

           

            <?php //echo $subscription['hide_reviews_rating_options'] ;?>

            <?php
            //reviews on && can send leads,hide phone || can't send leads, show phone
            if ($reviewState == 1 && (($subscription['receive_messages'] == 1 ) || ($sub['receive_messages'] != 1 ))) {
                $reviewsAmount = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT 1
                FROM
                    `users_reviews`
                WHERE
                    user_id = ".$user['user_id']." AND review_status = 2");
                $memberReviewsExist = mysql_fetch_array($reviewsAmount);
                ?>
                <div class="col-sm-12  profile-header-write-review" >


                    <?php
                    if ($reviewState == 1) { ?>
                        <a class="tmargin btn btn-primary btn-lg btn-block" href="/<?php echo $user['filename']; ?>/writeareview" title="%%%write_a_review_for%%%">
                            %%%profile_write_a_review%%%
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>

 
		<?php if ($user['verified']!=1) { ?> 
             <?php //can send leads
            if ($sub['receive_messages'] != 1) { ?>
	
	
 <?php

            //show phone number

            if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>

               

            <? } ?>
	
	
            <?php } ?>

<?php } ?>
</div>
        </div>
</div></div>
    <?php
    if ($subscription['profile_badge'] != ""  || $user['nationwide'] == 1) { ?>
        <div class="hidden-xs col-sm-1 nolpad member-badges">
            <?php echo widget("Bootstrap Theme - Member Profile - Badges","",$w['website_id'],$w); ?>
        </div>
    <?php } ?>
</div>
<div class="clearfix"></div><br>
<div class="hidden-sm hidden-md hidden-lg hidden-xl">
<div class="small nomargin sub_category_in_results">
					<div class="list-subs-profile bmargin">
						<?php echo getMemberSubCategory($user[user_id], "sub", "first", "all", "linked");?>
					</div>
				</div>
	</div>
