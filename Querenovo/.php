<?php 
// 1. Execute Main Query Logic
if ($_SERVER['REMOTE_ADDR'] == '49.205.169.64') {
    $sql_string = $_ENV['sqlquery'];
    $sql_result = mysql_query($sql_string);
}
?>

<div class="grid-container three-up-mobile visible row" bis_skin_checked="1">

   

    <?php 
    // 3. Start the Loop
    if ($sql_result && mysql_num_rows($sql_result) > 0) {
        while ($user = mysql_fetch_assoc($sql_result)) {
            
            // --- BASIC VARIABLES ---
            $user_id = $user['user_id'];
            
            // Full Name Logic
            $full_name = trim($user['first_name'] . ' ' . $user['last_name']);
            if (empty($full_name)) { $full_name = $user['company']; }
            
            $profile_link = "/" . $user['filename'];
            
            // Location Logic
            $location = "";
            if (!empty($user['city'])) { $location .= $user['city']; }
            if (!empty($user['state_code'])) { $location .= ", " . $user['state_code']; }

            // --- IMAGE FETCH LOGIC FROM 'users_photo' TABLE ---
            // We fetch the 'file' column where type is 'photo' (to ignore cover photos)
            $photo_query = "SELECT file FROM users_photo WHERE user_id = '$user_id' AND type = 'photo' LIMIT 1";
            $photo_result = mysql_query($photo_query);
            $photo_data = mysql_fetch_assoc($photo_result);

            // Set the Profile Photo URL
            if ($photo_data && !empty($photo_data['file'])) {
                // Construct URL as requested
                $profile_photo = "https://gaywellness.com/pictures/profile/" . $photo_data['file'];
            } else {
                // Fallback image if no photo exists in users_photo table
                $profile_photo = "https://ik.imagekit.io/s0mrmiffm/pictures/profile/default_profile.jpg"; 
            }
            
            $sub_level = isset($user['subscription_id']) ? "level_" . $user['subscription_id'] : "level_73";
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
                            <img loading="lazy" alt="Category" title="Category" src="https://ik.imagekit.io/s0mrmiffm/images/icons/COACHES-homepage-category-b&amp;w-button.png" style="height: 40px;width: 40px;min-width: auto;margin-left:10px;" data-processed="true" width="600" height="600">
                        </div>
                    </div>                  
                    <div class=" col-xs-10 " bis_skin_checked="1">
                        <div style="text-align: center" bis_skin_checked="1">
                            <a class="h5 bold bmargin inline-block tpad" title="<?php echo $full_name; ?> - View Listing" href="<?php echo $profile_link; ?>" style="font-weight:700;">
                                <?php echo $full_name; ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="clearfix" bis_skin_checked="1"></div>
                
                <div class="connect-hide" bis_skin_checked="1">
                    <div class="text-center subcategories col-xs-9" bis_skin_checked="1">
                        <i><a href="/arts-culture">Arts &amp; Culture</a></i>
                    </div>
                    <div class="col-xs-3" style="margin-left:0px;" bis_skin_checked="1">
                        <span class="postItem" data-userid="<?php echo $user['user_id']; ?>" data-datatype="10" data-dataid="10" data-postid="0"></span>
                        <button class="item-post-list-0 favorite fa fa-thumbs-o-up fa-2x" data-count="0" data-postid="0" data-state="0" data-activefeature="1" data-activeuser="<?php echo $user['user_id']; ?>">
                            <span id="bookmark-content">&nbsp;LIKE</span> 
                            <span class="" id="number-0"></span>
                        </button>
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
                    
                    <div class="text-center subcategories col-xs-12" bis_skin_checked="1">
                        <i>Music, Painting, Fashion</i>
                    </div>
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
              id="google-pin-<?php echo $user['user_id']; ?>" 
              data-token="<?php echo $user['user_id']; ?>" 
              data-hidemessages="0" 
              data-lat="<?php echo $user['lat']; ?>" 
              data-lng="<?php echo $user['lon']; ?>" 
              data-name="<?php echo $full_name; ?>" 
              data-filename="/<?php echo $user['filename']; ?>" 
              data-photo="<?php echo $profile_photo; ?>" 
              data-phone="<?php echo $user['phone_number']; ?>" 
              data-addon="1">
        </span>

    </div> 
    
    <?php 
        } // End While
    } // End If
    ?>

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