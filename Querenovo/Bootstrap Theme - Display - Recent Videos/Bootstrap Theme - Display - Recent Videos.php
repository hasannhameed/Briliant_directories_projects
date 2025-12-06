<style>
/* -----------------------------------------------------------
   FINAL VIDEO CARD DESIGN (For Your Specific HTML Structure)
----------------------------------------------------------- */

/* 1. MAIN GRID CONTAINER */
.slickVideos {
    display: flex !important;
    flex-wrap: wrap !important;
    /* gap: 30px !important; */
    justify-content: space-evenly;
    /* padding: 0 15px !important; */
}

/* 2. CARD WRAPPER (The Column) */

/* 3. IMAGE CONTAINER (.pic) 
   We manipulate this to show the image only in the top half 
*/
.slickVideos .pic {
    /* Override inline height */
    height: auto !important; 
    min-height: 320px !important; 
    
    /* Position the background image to be the "Top Half" */
    background-size: 100% 220px !important; /* Image Width x Height */
    background-repeat: no-repeat !important;
    background-position: top center !important;
    background-color: transparent !important;
    
    /* Layout */
    display: flex !important;
    flex-direction: column !important;
    justify-content: flex-end !important; /* Push text to bottom */
    
    position: relative !important;
    border-radius: 16px !important;
    overflow: visible !important; /* Allow shadow to show */
    margin-bottom: 0 !important;
    
    /* Create space for the image so text doesn't overlap */
    padding-top: 220px !important; 
    transition: transform 0.3s ease !important;
}

/* Add border to the image part using pseudo-element */
.slickVideos .pic::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 220px !important; /* Matches image height */
    border: 1px solid #e2e8f0 !important;
    border-bottom: none !important;
    border-radius: 16px 16px 0 0 !important;
    pointer-events: none !important;
    z-index: 0 !important;
}

/* 4. TEXT AREA (.view-more) */
.slickVideos .view-more {
    width: 100% !important;
    min-height: 110px !important;
    background-color: #f1f5f9 !important; /* Gray Background */
    color: #475569 !important;
    
    /* Styling */
    padding: 20px 25px !important;
    border: 1px solid #e2e8f0 !important;
    border-top: none !important;
    border-radius: 0 0 16px 16px !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
    
    /* Text alignment */
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
    text-align: left !important;
    text-decoration: none !important;
    
    position: relative !important;
    z-index: 2 !important;
    margin: 0 !important; /* Removes any default margins */
}

/* "Video Gallery" Title */
.slickVideos .view-more::before {
    content: 'Video Gallery';
    font-size: 16px !important;
    font-weight: 800 !important;
    color: #0f172a !important;
    margin-bottom: 5px !important;
    display: block !important;
}

/* 5. CAMERA ICON OVERLAY */
/* Position it in the center of the Image Area (Top 220px) */
.slickVideos .play {
    position: absolute !important;
    top: 60px !important; 
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    
    width: 60px !important;
    height: 60px !important;
   
    border-radius: 16px !important;
    
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    z-index: 10 !important;
}

/* Hide original SVG */
.slickVideos .play svg, 
.slickVideos .play polygon { display: none !important; }

/* Insert CSS Camera Icon */
.slickVideos .play::after {
    content: '' !important;
    width: 100% !important;
    height: 60px !important;
   background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5'/%3E%3Crect x='2' y='6' width='14' height='12' rx='2'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
    background-size: contain !important;
}

/* 6. HOVER EFFECTS */
/* Lift the whole card */
.slickVideos div[class*="col-"]:hover .pic {
    transform: translateY(-8px) !important;
}

.slickVideos div[class*="col-"]:hover .view-more {
    background-color: #ffffff !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    border-color: #cbd5e1 !important;
}

/* Blue Icon on Hover */
.slickVideos div[class*="col-"]:hover .play {
    
    /* background: rgba(59, 130, 246, 0.1) !important; */
}
.slickVideos div[class*="col-"]:hover .play::after {
    /* background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%233b82f6'%3E%3Cpath d='M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm10 7a2 2 0 1 0-4 0 2 2 0 0 0 4 0zm0 0a4 4 0 1 1-8 0 4 4 0 0 1 8 0z'/%3E%3C/svg%3E") !important; */
}
</style>
<?php
/*
 * Widget Name: Bootstrap Theme - Display - Recent Videos
 * Short Description: Enables the Streaming of Particular Membership Feature Posts.
 */
$featureUrl = $wa['custom_197']; // Use to Set the name of the Membership Feature this Streaming Widget will use. Can be controlled in the Design Settings.
$maxItems = $wa['custom_175']; // Maximum amount of streaming items shown. Default is 4. Can be controlled in the Design Settings.
if ($wa['post_video_hideEmptyStream'] == "1" || $wa['post_video_hideEmptyStream'] == "") {
    $hideEmptyStream = true; // This hides the feature if there are no posts to show on it. Default is true. Can be controlled in the Design Settings.
} else {
    $hideEmptyStream = false;
}
if ($wa['post_video_onlyActiveMembers'] == "1" || $wa['post_video_onlyActiveMembers'] == ""){
    $onlyActiveMembers = true; // Show or Hide results from Active Members Only. Default is true.
} else {
    $onlyActiveMembers = false;
}
if ($wa['post_video_searchablePostsOnly'] == "0" || $wa['post_video_searchablePostsOnly'] == ""){
    $searchablePostsOnly = true; // Show posts even if they are not searchable. Useful for Membership Levels that do not want to show in the search results but want to show their posts in the streaming widgets, similar to how an admin account would work.
} else {
    $searchablePostsOnly = false;
}
$sortingOrder = 'dp.post_id DESC'; // Sorting Order in which results will be shown. Values supported are DESC (Descending) & ASC (Ascending). Default is DESC.
if(isset($wa['custom_292'])){
    $sortingOrder = ($wa['custom_292'] != 'RAND()')?"dp.post_id ".$wa['custom_292']:$wa['custom_292'];
}
$onlySubscriptionIds = $wa['post_video_onlySubscriptionIds']; // Comma separated list of the subscription_ids that members are listed in to show posts of
if($wa['streaming_view_more_class'] != ''){
    $viewAllClass = $wa['streaming_view_more_class'];
} else {
    $viewAllClass = 'btn-info';
}

if($wa['streaming_read_more_class'] != ''){
    $readMoreClass = $wa['streaming_read_more_class'];
} else {
    $readMoreClass = 'btn-success';
}
if (empty($featureUrl)) {
    $featureUrl = 'videos';
}
if (empty($maxItems)) {
    $maxItems = 4;
}

if(is_numeric($featureUrl)){
    $where = "data_id = ".$featureUrl;
}else{
    $where = "data_filename LIKE '".$featureUrl."'";
}

if ($wa['custom_310'] == "2"){
    $columnWidth = "col-md-6";
} else if ($wa['custom_310'] == "1") {
    $columnWidth = "col-md-4";
} else {
    $columnWidth = "col-md-3";
}

// Query that grabs information from the Membership Feature selected
$membershipFeaturesSQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
        data_id, data_filename
    FROM
        data_categories
    WHERE
        ".$where."
    AND
        data_active = '1'
    LIMIT
        1");

$features = mysql_fetch_assoc($membershipFeaturesSQL);


if ($wa['post_video_onePostperMember'] == "1") {
    if ($wa['custom_292'] == "" || $wa['custom_292'] == "DESC"){
        $tempTableOrder = $sortingOrder.", post_live_date DESC";
    } else if ($wa['custom_292'] == "ASC"){
        $tempTableOrder = $sortingOrder.", post_live_date ASC";
    } else {
        $tempTableOrder = "RAND()";
    }
    mysql(brilliantDirectories::getDatabaseConfiguration('database'), "CREATE TEMPORARY TABLE temp_data_posts SELECT * FROM data_posts as dp WHERE dp.data_id = '".$features['data_id']."' ORDER BY ".$tempTableOrder."");
    $sqlTablesParameters = array(
        "`temp_data_posts` AS dp",
        "`users_data` AS ud",
        "`subscription_types` AS st"
    );
} else {
    $sqlTablesParameters = array(
        "`data_posts` AS dp",
        "`users_data` AS ud",
        "`subscription_types` AS st"
    );
}

$sqlSelectParameters = array(
    "dp.post_id",
    "dp.post_title",
    "dp.post_content",
    "dp.post_image",
    "dp.post_filename",
    "dp.post_updated",
    "dp.user_id"
);

if ($w['support_data_post_videos'] == 1) {
    $sqlSelectParameters[] = "dp.post_video";
}

$sqlWhereParameters = array(
    "dp.user_id = ud.user_id",
    "ud.subscription_id = st.subscription_id",
    "dp.data_id = '".$features['data_id']."'",
    "dp.post_title != ''",
    "dp.post_status = '1'",
    "(dp.post_expire_date >= '".date('Ymd',websiteSettingsController::getCurrentTimeStamp())."000000' OR dp.post_expire_date = '')"
);
if (!empty($onlySubscriptionIds) && $onlySubscriptionIds != " ") {
    array_push($sqlWhereParameters,"ud.subscription_id IN (".$onlySubscriptionIds.")");
}
$sqlGroupByParameters = array();
if ($wa['post_video_onePostperMember'] == "1") {
    array_push($sqlGroupByParameters,"dp.user_id");
}
$sqlHavingParameters = array();
$sqlOrderByParameters = array(
    $sortingOrder
);
$sqlLimitParameters = array(
    $maxItems
);

if ($onlyActiveMembers) {
    $sqlWhereParameters[] = "ud.active = 2";
}
if ($searchablePostsOnly == true) {
    $sqlWhereParameters[] = "st.searchable = '1'";
}
$sqlWhereParameters[] = "st.data_settings LIKE '%".$features['data_id']."%'";

/* -------------------------- Code That Constructs the SQL statement ------------------------------------- */
if (count($sqlSelectParameters) > 0) {
    $sql .= "SELECT ";
    $sql .= implode(", ",$sqlSelectParameters);
}
if (count($sqlTablesParameters) > 0) {
    $sql .= " FROM ";
    $sql .= implode(", ",$sqlTablesParameters);
}
if (count($sqlWhereParameters) > 0) {
    $sql .= " WHERE ";
    $sql .= implode(" AND ",$sqlWhereParameters);
}
if (count($sqlGroupByParameters) > 0) {
    $sql .= " GROUP BY ";
    $sql .= implode(", ",$sqlGroupByParameters);
}
if (count($sqlHavingParameters) > 0) {
    $sql .= " HAVING ";
    $sql .= implode(" AND ",$sqlHavingParameters);
}
if (count($sqlOrderByParameters) > 0) {
    $sql .= " ORDER BY ";
    $sql .= implode(", ",$sqlOrderByParameters);
}
if (count($sqlLimitParameters) > 0) {
    $sql .= " LIMIT ";
    $sql .= implode(", ",$sqlLimitParameters);
}
/* -------------------------- END Code That Constructs the SQL statement ------------------------------------- */
$featureResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $sql);
$featureNum = mysql_num_rows($featureResults);
$showFeature = true;

if ($hideEmptyStream == true) {

    if ($featureNum > 0) {
        $showFeature = true;

    } else {
        $showFeature = false;
    }
}

if ($wa['custom_310'] == "2"){
    $columnWidth = "col-md-6";
} else if ($wa['custom_310'] == "1") {
    $columnWidth = "col-md-4";
} else {
    $columnWidth = "col-md-3";
}

if ($wa['post_video_onePostperMember'] == "1") {
    mysql(brilliantDirectories::getDatabaseConfiguration('database'), "DROP TABLE temp_data_posts");
}

$titleStyleColor = "";
if($wa['recent_videos_tColor'] != ""){
    $titleStyleColor = 'style="color: '.$wa['recent_videos_tColor'].'"';
}

if ($showFeature == true) { ?>
    <div class="clearfix"></div>
    <div class="content-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if ($wa['videos_show_view_all'] != '0'){ ?>
                    <a href="/<?php echo $features['data_filename'];?>" class="view-all-btn-desktop hidden-xs btn <?php echo $viewAllClass ?>">
                        Voir toutes les vidéos <i class="fa fa-arrow-right"></i>

                    </a>
                <?php } ?>
                <h2 class="nomargin sm-text-center streaming-title" <?php echo $titleStyleColor ?>>
                    <?php echo $wa['custom_129'];?><br>
					<p class ="sub">Conseils, témoignages et innovations</p>
                </h2>
				
               
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 slickVideos video">
                    <?php
                    while ($post = mysql_fetch_array($featureResults)) {
                    $post = getMetaData("data_posts",$post['post_id'],$post,$w);
                    $img = "";

                    foreach ($post as $key => $value) {
                        $post[$key] = stripslashes($value);
                    }
                    if ($post['post_video'] != "") {
                        echo '<td class="image">';
                        $videoOembed = new oembed_controller($post['post_video'],null,$post['post_image']);
                        $videoData = $videoOembed->getOembedData();
                        if (!empty($videoData)) {
                            $post['post_video_oembed'] = (array)$videoData;
                            $img = $videoOembed->getThumbnailHtml($post,'justUrl');
                        }

                        if($img == ''){
                            if($w['default_post_image'] != ''){
                                $img = $w['default_post_image'];
                            } else {
                                $img = '/images/image-placeholder.jpg';
                            }
                        }
                    }
                    $post['post_title'] = stripslashes($post['post_title']);

                    if (strlen($post['post_title']) > 30 ) {
                        $postTitle = limitName($post['post_title'],30);
                    } else {
                        $postTitle = $post['post_title'];
                    }

                    if (strlen($post['post_content']) > 40 ) {
                        $postContent = limitName(strip_tags($post['post_content']),40);
                    } else {
                        $postContent = strip_tags($post['post_content']);
                    } ?>
                    <div class="col-sm-6 <?php echo $columnWidth; ?> text-center bmargin">
                        <?php if (!empty($w['lazy_load_images'])) { ?>
                        	<div class="pic lazyloader" data-src="<?php echo $img; ?>" onclick="" style="background-repeat:no-repeat;background-position:center center;background-size:cover;">
						<?php } else { ?>
                            <div class="pic" style="background-repeat:no-repeat;background-position:center center;background-size:cover;background-image:url(<?php echo $img; ?>)" onclick="">
						<?php } ?>
								<span class=" bottom-to-top" onclick>
									<div class="play">									
									<svg width="48" height="48" viewBox="0 0 24 24" role="img" aria-label="Play"
     xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="0">
  <!-- Transparent background: no rect -->
  <polygon points="6,4 20,12 6,20" fill="currentColor" opacity="0.9"/>
</svg></div>
									<!-- <h3 class="pic-title"><?php echo $postTitle;?></h3>
									<p><?php echo $postContent;?></p>  -->
									<a href="/<?php echo $post['post_filename'];?>" class="btn <?php echo $readMoreClass?> fpad-lg vpad view-more">
										Conseils, témoignages et innovations
									</a>
								</span>
                                <a aria-label="<?php echo strip_tags($Label['watch_video']);?>" href="/<?php echo $post['post_filename'];?>" class="homepage-link-element <?php if ($wa['streaming_info_display'] == "on_hover") { ?>hidden-xs<?php } ?>"></a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <?php if ($wa['videos_show_view_all'] != '0'){ ?>
                    <div class="col-md-6">
                        <a href="<?php echo $features['data_filename'];?>" class="btn btn-lg <?php echo $viewAllClass ?> btn-block visible-xs-block"> Voir toutes les vidéos <i class="fa fa-arrow-right"></i></a>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    global $featureSliderEnabled, $featureMaxPerRow, $featureSliderClass, $postsCount;
    $postsCount = $featureNum;
    $featureSliderEnabled = $wa['videos_posts_slider'];
    $featureMaxPerRow = $wa['custom_310'];
    $featureSliderClass = '.slickVideos';
    addonController::showWidget('post_carousel_slider','1a19675a36d28232077972bbdb6bb7fe');
} ?>
<style>
    /* 1. Make the Modal Dialog wide and tall */
    #videoModal .modal-dialog {
        width: 50%;
        height: 95vh; 
        
    }
#videoFrame{
    width: 100%;
    padding: 20px;
}
    /* 2. Make the content wrapper fill that height */
    #videoModal .modal-content {
        height: 100%; 
        border-radius: 8px; 
        overflow: hidden; 
    }

    /* 3. Make the body fill the content */
    #videoModal .modal-body {
        height: 100%;
        padding: 0;
        overflow: hidden; /* Hide scrollbar */
    }

    /* 4. Force Iframe to fill the entire space */
    #videoFrame {
      
        height: 100%;
        border: none;
        display: block;
    }

    /* 5. Custom Close Button Styling */
    .custom-close-btn {
        position: absolute; 
        right: 15px; 
        top: 15px; 
        z-index: 9999; 
        color: #333; 
        background: rgba(255,255,255,0.8); 
        border-radius: 50%;
       
        height: 40px;
        opacity: 1;
        font-size: 30px;
        line-height: 35px; 
        text-align: center;
        border: 1px solid #ccc;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .custom-close-btn:hover {
        background: #fff;
        color: #000;
    }
</style>
<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel">
  <div class="modal-dialog" role="document"> <div class="modal-content">
      <div class="modal-body">
        
        <button type="button" class="close custom-close-btn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

        <iframe id="videoFrame" src="" frameborder="0" allowfullscreen></iframe>
        
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {

    $('.slickVideos .view-more, .slickVideos .homepage-link-element').on('click', function(e) {
        e.preventDefault();
        var videoUrl = $(this).attr('href');
        $('#videoFrame').attr('src', videoUrl);
        $('#videoModal').modal('show');
    });

    $('#videoModal').on('hidden.bs.modal', function () {
        $('#videoFrame').attr('src', '');
    });

});
</script>