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