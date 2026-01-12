<?php 
// 1. Execute Main Query Logic
if (true) { 
    $sql_string = $_ENV['sqlquery'];
    $sql_result = mysql_query($sql_string);
}
?>

<div class="grid-container three-up-mobile visible row" bis_skin_checked="1">

    <?php 
    // 3. Start the Loop
    if ($sql_result && mysql_num_rows($sql_result) > 0) {
        while ($search_row = mysql_fetch_assoc($sql_result)) {
            
            // --- STEP 1: GET USER ID FROM SEARCH RESULT ---
            $user_id = $search_row['user_id'];

            // --- STEP 2: FETCH ALL DETAILS FROM users_data TABLE ---
            $data_query = "SELECT * FROM users_data WHERE user_id = '$user_id' LIMIT 1";
            $data_result = mysql_query($data_query);
            $user_data = mysql_fetch_assoc($data_result);

            // Safety check: If user_data is empty (deleted user), skip this iteration
            if (!$user_data) { continue; }


            // --- STEP 3: PREPARE VARIABLES USING $user_data ---
            
            // Full Name Logic (Used for Titles/Alt tags)
            $full_name = trim($user_data['first_name'] . ' ' . $user_data['last_name']);
            if (empty($full_name)) { $full_name = $user_data['company']; }
            
            // Profile Link
            $profile_link = "/" . $user_data['filename'];
            
            // Location Logic
            $location = "";
            if (!empty($user_data['city'])) { $location .= $user_data['city']; }
            if (!empty($user_data['state_code'])) { $location .= ", " . $user_data['state_code']; }

            // Subscription Level (CSS Class)
            $sub_level = isset($user_data['subscription_id']) ? "level_" . $user_data['subscription_id'] : "level_73";


            // --- STEP 4: IMAGE FETCH LOGIC (users_photo table) ---
            $photo_query = "SELECT file FROM users_photo WHERE user_id = '$user_id' AND type = 'photo' LIMIT 1";
            $photo_result = mysql_query($photo_query);
            $photo_data = mysql_fetch_assoc($photo_result);

            // Set the Profile Photo URL
            if ($photo_data && !empty($photo_data['file'])) {
                $profile_photo = "/pictures/profile/" . $photo_data['file'];
            } else {
                // Fallback image
                $profile_photo = "/images/profile-profile-holder.png"; 
            }
            
    ?>

    <div class="member_results <?php echo $sub_level; ?> search_result clearfix m1 col-xs-12 col-sm-3 col-md-3 grid" bis_skin_checked="1">
        
        <div class="grid_element well xs-center-block" bis_skin_checked="1">
            <div class="img_section col-md-12 nopad" bis_skin_checked="1">
                <a title="<?php echo $full_name; ?>" href="<?php echo $profile_link; ?>">
                    <img loading="auto" class="search_result_image img-rounded center-block lazyloader grid_image" alt="<?php echo $full_name; ?>" src="<?php echo $profile_photo; ?>" data-processed="true" width="600" height="600">
                </a><br>
                <div class="badges" bis_skin_checked="1"></div>
            </div>
            
            <div class="mid_section norpad col-sm-12 nohpad ex-col-7 text-center bpad" bis_skin_checked="1">
                <div style="padding-top:5px;" bis_skin_checked="1">                  
                    <div class="col-xs-2" style="padding-right: 0;" bis_skin_checked="1">
                        <div style="text-align: left" bis_skin_checked="1">
                            <img loading="lazy" alt="Category" title="Category" src="/images/icons/COACHES-homepage-category-b&amp;w-button.png" style="height: 40px;width: 40px;min-width: auto;margin-left:10px;" data-processed="true" width="600" height="600">
                        </div>
                    </div>                  
                    <div class=" col-xs-10 " bis_skin_checked="1">
                        <div style="text-align: center" bis_skin_checked="1">
                            
                            <a class="h5 bold bmargin inline-block tpad" title="<?php echo $full_name; ?> - View Listing" href="<?php echo $profile_link; ?>" style="font-weight:700;">
                                <?php if ($user_data['subscription_id'] != '71') { ?>
                                    
                                    <?php echo $user_data['company']; ?> 
                                    
                                    <?php if ($user_data['company'] != "" && $user_data['first_name'] != "") { ?>
                                         by 
                                    <?php } ?> 
                                    
                                    <?php echo $user_data['first_name']; ?> 
                                
                                <?php } ?> 

                                <?php if ($user_data['subscription_id'] == '71') { ?>
                                    <span class="capitalize" style="color:#0d3f4f; font-size:24px;">
                                        <?php echo $user_data['first_name']; ?>
                                    </span>
                                <?php } ?> 
                            </a>
                            </div>
                    </div>
                </div>
                <div class="clearfix" bis_skin_checked="1"></div>

                <div class="connect-hide">
                    <div class="text-center subcategories col-xs-9">
                        <i><?php echo getMemberSubCategory($user_data['user_id'], "sub", "random", 3, "linked");?></i>
                    </div>
                    <div class="col-xs-3" style="margin-left:0px;">
                        <?php 
                            $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
                            if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                                echo '<span class="postItem" data-userid="'.$user_data['user_id'].'" data-datatype="10" data-dataid="10" data-postid="0"></span>';
                                echo widget($addonFavorites['widget'],"",$w['website_id'],$w); 
                            } 
                        ?>
                    </div>
                </div>
                
                <div class="clearfix" bis_skin_checked="1"></div>
                
                <div style="min-height:45px;" bis_skin_checked="1">
                    <div class="col-md-12" bis_skin_checked="1">
                        <div class="clearfix" bis_skin_checked="1"></div>
                        <small>
                            <span style="font-size:0.7em;">
                                <i class="fa fa-map-marker" style="color:#0D3F4F;"></i> 
                                <?php echo $location; ?>
                            </span>
                        </small>
                        <div class="clearfix" bis_skin_checked="1"></div>
                    </div>
                    
                    <?php if (isset($user_data['profession_id']) && $user_data['profession_id'] == 12) { ?> 
                        <div class="text-center subcategories col-xs-12">
                            <i><?php echo getMemberSubCategory($user_data['user_id'], "sub-sub", "random", 3, "text");?></i>
                        </div>
                    <?php } else { ?>
                        <div class="text-center subcategories col-xs-12" bis_skin_checked="1">
                            <i>Music, Painting, Fashion</i>
                        </div>
                    <?php } ?>

                    <div class="clearfix" bis_skin_checked="1"></div>
                </div>
                <hr style="height:1px;border-width:0;color:black;background-color:#E0E0E0">
            </div>

            <div class="info_section connect-hide col-sm-12 nopad" bis_skin_checked="1">
                <div class="nomargin text-center" bis_skin_checked="1">
                    <a class="btn btn-success btn-block" href="<?php echo $profile_link; ?>">View Listing</a>                                         
                    <div class="clearfix bpad" bis_skin_checked="1"></div>
                </div>
            </div>
        </div>
        
        <span class="google-pin-location" 
              id="google-pin-<?php echo $user_data['user_id']; ?>" 
              data-token="<?php echo $user_data['user_id']; ?>" 
              data-hidemessages="0" 
              data-lat="<?php echo $user_data['lat']; ?>" 
              data-lng="<?php echo $user_data['lon']; ?>" 
              data-name="<?php echo $full_name; ?>" 
              data-filename="/<?php echo $user_data['filename']; ?>" 
              data-photo="<?php echo $profile_photo; ?>" 
              data-phone="<?php echo $user_data['phone_number']; ?>" 
              data-addon="1">
        </span>

    </div> 
    
    <?php 
        } // End While
    } else{ // End If
 ?>
   
<?php } ?>
</div>

<div class="clearfix" style="display: none;" bis_skin_checked="1"></div>
<hr style="display: none;">

<script>
    setInterval(function() {
        var elements = document.querySelectorAll('.member_results');
        for (var i = 0; i < elements.length; i++) {
            elements[i].style.display = 'block';
        }
    }, 500); 
</script>