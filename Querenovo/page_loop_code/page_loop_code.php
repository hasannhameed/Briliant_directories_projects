<div class="row-fluid col-sm-12 member_results norpads level_<?php echo $user_data['subscription_id'];?> search_result <?php echo ($user_data['verified'] == "1") ? 'verified_member_result' : ''; ?> clearfix" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
	<meta itemprop="name" content="<?php echo htmlspecialchars($user_data['full_name'], ENT_QUOTES, 'UTF-8'); ?>">
	<link itemprop="url" href="<?php echo $schema_collection_url; ?>">
	<meta itemprop="position" content="<?php echo ++$GLOBALS['search_result_position']; ?>">
	<?php
	// Re-encode filename segments (framework auto-decodes, we need to encode it back)
	$filenameParts = explode('/', ltrim($user_data['filename'], '/'));
	$encodedParts = array_map('rawurlencode', $filenameParts);
	$encodedFilename = implode('/', $encodedParts);
	?>
	<div itemprop="item" itemscope itemtype="https://schema.org/LocalBusiness" itemid="<?php echo $schema_base_url . '/' . $encodedFilename; ?>#entity">
		<meta itemprop="name" content="<?php echo htmlspecialchars($user_data['full_name'], ENT_QUOTES, 'UTF-8'); ?>">
		<link itemprop="url" href="<?php echo $schema_base_url . '/' . $encodedFilename; ?>">
		<link itemprop="image" href="<?php echo $user['image_main_file']; ?>">
		<meta itemprop="priceRange" content="$$">
		<meta itemprop="telephone" content="<?php echo !empty($user['phone_number']) ? preg_replace('/[^0-9+\-]/', '', $user['phone_number']) : 'N/A'; ?>">
		<?php if (!empty($user_data['search_description'])) { ?>
		<meta itemprop="description" content="<?php echo htmlspecialchars(strip_tags($user_data['search_description']), ENT_QUOTES, 'UTF-8'); ?>">
		<?php } elseif (!empty($user_data['about_me'])) { ?>
		<meta itemprop="description" content="<?php echo htmlspecialchars(strip_tags(limitWords($user_data['about_me'], 170)), ENT_QUOTES, 'UTF-8'); ?>">
		<?php } ?>
		<div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
			<meta itemprop="streetAddress" content="<?php echo htmlspecialchars(trim($user_data['address1']) ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?>">
			<meta itemprop="addressLocality" content="<?php echo htmlspecialchars(trim($user_data['city']) ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?>">
			<meta itemprop="addressRegion" content="<?php echo htmlspecialchars(trim($user_data['state_code']) ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?>">
			<meta itemprop="postalCode" content="<?php echo htmlspecialchars(trim($user_data['zip_code']) ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?>">
			<meta itemprop="addressCountry" content="<?php echo htmlspecialchars(trim($user_data['country_code']) ?: 'US', ENT_QUOTES, 'UTF-8'); ?>">
		</div>
		<?php if (!empty($user_data['lat']) && !empty($user_data['lon'])) { ?>
		<div itemprop="geo" itemscope itemtype="https://schema.org/GeoCoordinates">
			<meta itemprop="latitude" content="<?php echo $user_data['lat']; ?>">
			<meta itemprop="longitude" content="<?php echo $user_data['lon']; ?>">
		</div>
		<?php } ?>
		<div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
			<?php
			$ratingValue = (isset($_ENV['results']['rating']) && $_ENV['results']['rating'] > 0) ? $_ENV['results']['rating'] : 5;
			$reviewCount = (isset($_ENV['results']['total']) && $_ENV['results']['total'] > 0) ? intval($_ENV['results']['total']) : 1;
			?>
			<meta itemprop="ratingValue" content="<?php echo $ratingValue; ?>">
			<meta itemprop="reviewCount" content="<?php echo $reviewCount; ?>">
			<meta itemprop="bestRating" content="5">
			<meta itemprop="worstRating" content="1">
		</div>
		<link itemprop="mainEntityOfPage" href="<?php echo $schema_base_url . '/' . $encodedFilename; ?>#page">
	</div>
    <div class="grid_element grid_element_custom">
        <div class="img_section col-xs-2 nopad">
            <a title="<?php echo $user_data['full_name'];?> - <?php echo ucwords($w['profession'])?>" href="/<?php echo $user_data['filename']?>">
				<?php if (!empty($w['lazy_load_images'])) { ?>
				<img class="search_result_image img-rounded center-block lazyloader" loading="auto" width="400" height="400" alt="<?php echo $user_data['full_name'];?>" data-src="<?php echo $user['image_main_file']?>">
				<?php } else { ?>
				<img class="search_result_image img-rounded center-block" loading="auto" width="400" height="400" alt="<?php echo $user_data['full_name'];?>" src="<?php echo $user['image_main_file']?>" />
				<?php } ?>   
            </a>
        </div>
        <div class="mid_section col-xs-12 col-sm-10">
            <?php
            $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
            if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                      echo '<span class="postItem" data-userid="'.$user_data['user_id'].'" data-datatype="10" data-dataid="10" data-postid="0"></span>';
                      echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
            }

            ?>
            <a class="center-block" title="<?php echo $user_data['full_name']?>" href="/<?php echo $user_data['filename']?>">
                <span class="h3 bold inline-block member-search-full-name notranslate content_adjust">
                    <?php echo $user_data['full_name']?>
                </span>
                <?php if ($user_data['company'] != "" && $user_data['listing_type'] == "Individual") { ?>
                    <span class="hidden-xs inline-block member-search-company notranslate">
                        <?php echo $user_data['company']?>
                    </span>
                <?php } ?>
            </a>
			<!-- <div class="clearfix fpad-sm nobpad"></div> -->
			<?php if ($exact != "") { ?>
			<div class="hidden-sm hidden-md hidden-lg center-block small line-height-xl talign mobile-distance-within">
				<?php echo $exact ?>
			</div>

			<?php }?> 

			
			
			<?php if ($totalreviews > 0 && (in_array($reviewId['data_id'], $subscription['data_settings'])) == 1 && $subscription['hide_reviews_rating_options'] == 0) { ?>
			<div class="bold member-search-reviews inline-block rmargin align-middle small">
				<?php echo $rating['stars']?>
			</div>
			<?php } ?>
			<!-- <div class="clearfix fpad-sm nobpad"></div> -->
			<?php
			if ($w['member_position_in_results'] == "1" && $user_data['position'] != '') { ?>
			<p class="small nomargin member_position_in_results">
				<b class="line-height-xl">%%%search_results_position_label%%%</b> <?php echo $user_data['position']?>
			</p>
			<?php } ?>	
			
			<?php  if ($user_data['search_description'] != "") { ?>				
				<p class="small member-search-description content_adjust">
					<?php
						$user_data['search_description'] = bdString::prepareSpecialCharacter($user_data['search_description']);
						echo (preg_replace('#<[^>]+>#', ' ', $user_data['search_description']));
					?>
				</p>
            <?php }
			else if ($user_data['about_me'] !="" && $subscription['show_about_tab'] != 0 ) { ?>
				<p class="small member-search-description content_adjust">
					<?php
						$user_data['about_me'] = bdString::prepareSpecialCharacter($user_data['about_me']);
						echo limitWords(preg_replace('#<[^>]+>#', ' ', $user_data['about_me']),170);
					?>
				</p>
			<?php } ?> 

			
            <?php 
            if ($w['top_category_in_results'] == "1" && $user_data['profession_name'] != '') { ?>
                <p class="small nomargin top_category_in_results">
                    <b class="line-height-xl">%%%search_results_category_label%%%</b> <?php echo $user_data['profession_name']?>
                </p>
            <?php } ?>
            <?php if ($w['sub_category_in_results'] == "1"){
			$memberSubCategories = getMemberSubCategory($user_data['user_id'],"all","settings",intval($w['profile_services_display_limit']),"text");
			if ($memberSubCategories != "") { ?>
					<p class="small sub_category_in_results">
						<p class='content_adjust1'><b class="line-height-xl">%%Service%%:</b> <?php echo $memberSubCategories;?></p>
					</p>
			<?php } } ?>

			<?php if (true) { ?>
			<div class="rmargin inline-block">
				<div class="alert-success-subtle bold inline-block align-middle member-search-verified" title="This member has verified their information" bis_skin_checked="1">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-3 h-3 mr-1"><path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path><circle cx="12" cy="8" r="6"></circle></svg>
					<span class="member-search-verified-text"><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">
						France Rénov'
					</font></font></span>
				</div>
			</div>
			<?php }?> 

			<?php if ($user_data['verified'] == "1") { ?>
			<div class="rmargin inline-block">
				[widget=Bootstrap Theme - Member Verified Badge]
			</div>
			<?php }?> 

			<?php if ($user['department_code'] != '') { ?>
			<div class="rmargin inline-block">
			<div class="" title="This member has verified their information" bis_skin_checked="1">
				<span class="btn btn-default dep_button">
					<font dir="auto" style="vertical-align: inherit;">
						<font dir="auto" style="vertical-align: inherit;"><?php echo $user['department_code']; ?></font>
					</font>
				</span>	
			</div>
			</div>
			<?php }?> 

			<?php if (($user_data['city'] != '' || $user_data['state_ln'] !='' || $user_data['zip_code']!="" || $user_data['country_ln'] != '') && $subscription['profile_layout'] == "1") { ?>
                <div class="clearfix fpad-sm nobpad"></div>
        </div>
        <div class="info_section col-sm-12 norpad">

            <div class="module nomargin fpad text-center col-sm-10 mybuttons">
				
                <?php if ($user['nationwide']==1 && $label[serves_this_area] !="") { ?>
                    <div class="alert alert-success fpad-sm bmargin">
                        <i class="fa fa-map-marker"></i>
                        %%%serves_this_area%%%
                    </div>
                <?php } else if ($exact != "" && $distance != "") {
                    if ($exact != "") { echo $exact; } ?>
                    <!-- <div class="clearfix bpad"></div> -->
                <?php }
                $badgesAddOn = getAddOnInfo('member_listing_badges','4bfd736fb96d71876957b942d0293b1a');
                if ($subscription['category_badge'] != "" && isset($badgesAddOn['status']) && $badgesAddOn['status'] == 'success') {
                    echo widget($badgesAddOn['widget'],"",$w['website_id'],$w);
                }

                if (($user_data['service_area'] > 0 || $user_data['area_id_big_location'] > 0 || ($user_data['service_distance'] != "" && $user_data['service_distance'] <=  $w['default_radius'])) && $user['nationwide'] != 1 && $label[serves_this_area] !="") { ?>
                    <div class="alert alert-success fpad-sm bmargin">
                        <i class="fa fa-map-marker"></i>
                        %%%serves_this_area%%%
                    </div>
                <?php } ?>
				<div class="col-sm-12 nopad call-btn"> 
					<div class="col-sm-6 nopad">
						<?php	
						$membersOnly = addonController::isAddonActive('members_only');
						if (
							$user['phone_number'] != "" && 
							$subscription['show_phone'] == 1 && 
							( 
								$membersOnly  === false  || ($membersOnly === true && (isset($_COOKIE['userid']) || (!isset($_COOKIE['userid']) && strpos($subscription['membership_restriction'], "profile_pages") === false) ) ) 
							) 
						) {
						$clickPhoneAddOnLoop = getAddOnInfo("click_to_phone","1c75909cfae116e22fd25f087d8d4f7b");
						$statisticsAddOn = getAddOnInfo("user_statistics_addon","79c260ec6118524a0dea65acd7759ebe");
						if(isset($clickPhoneAddOnLoop['status']) && $clickPhoneAddOnLoop['status'] === 'success'){
							echo widget($clickPhoneAddOnLoop['widget'],"",$w['website_id'],$w);
						} else if (isset($statisticsAddOn['status']) && $statisticsAddOn['status'] === 'success') {
							echo widget($statisticsAddOn['widget'],"",$w['website_id'],$w);
						} else { ?>
							<div class="clearfix fpad-sm nobpad kk"></div>
							<span class="btn-default btn-sm bold text-center nohpad btn-block nomargin member-search-phone">
								%%%show_phone_number_icon%%%
								<?php echo $user['phone_number']; ?>
							</span>
						<?php }  }	?>
					</div>
					<div class="col-sm-6 nopad">
						<span class="member-search-location rmargin btn btn-outline btn-sm bold click-to-call-button search_show_phone_button btn-block">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-4 h-4 flex-shrink-0"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
							<small>
								<?php if ($user_data['city'] != '') {
									echo $user_data['city'];
								}

								if ($user_data['state_ln'] != '') {

									if ($user_data['city'] != '') {
										echo ', ';
									}
									echo $user_data['state_ln'];
								}

								if ($user_data['zip_code'] != '') {

									if ($user_data['state_ln'] != '' || $user_data['city'] != '') {
										echo ', ';
									}						
									echo '<span class="inline-block">' . $user_data['zip_code'] . '</span>';

								}

								if  ($user_data['country_ln'] != "") {

									if ($user_data['state_ln'] != '' || $user_data['city'] != '' || $user_data['zip_code'] != '') {
										echo ', ';
									}
									echo '<span class=inline-block>' . $user_data['country_ln'] . '</span>';
								}
								} ?>
							</small> 
						</span>
					</div>
					
				</div>

				<div class="col-sm-6 nopad profilebtn">
					<a 
					title="<?php echo htmlspecialchars(strip_tags($Label['view_listing_label']), ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8'); ?>" 
					aria-label="<?php echo htmlspecialchars(strip_tags($Label['view_listing_label']), ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8'); ?>" 
					class="btn btn-sm btn-primary btn-block bold search_view_listing_button" 
					href="/<?php echo $user_data['filename']?>">%%%view_listing_label%%%</a>
				</div>

				<?php if ($subscription['receive_messages'] != 1){ ?>

				<!-- <div class="clearfix fpad-sm nobpad"></div> -->
				<div class="col-sm-6 nopad">
				<a 
					title="<?php echo htmlspecialchars(strip_tags($Label['contact_now_label']), ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8'); ?>" 
					aria-label="<?php echo htmlspecialchars(strip_tags($Label['contact_now_label']), ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8'); ?>" 
					class="btn btn-sm btn-secondary btn-block bold search_contact_now_button" 
					href="/<?php echo $user_data['filename']?>/<?php echo $w['default_connect_url'];?>">%%%contact_now_label%%%</a>
				</div>

				<!-- <div class="clearfix"></div> -->
                <?php } ?>

            </div>
        </div>
		<div class="clearfix"></div>
    </div>
</div>
<div class="clearfix"></div>
<p></p>
<?php echo widget("Bootstrap Theme - Google Pins Locations","",$w['website_id'],$w); ?>