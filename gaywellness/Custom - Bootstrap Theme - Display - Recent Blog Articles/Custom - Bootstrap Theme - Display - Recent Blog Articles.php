<?php
/*
 * Widget Name:  Bootstrap Theme - Display - Recent Blog Articles
 * Short Description: Enables the Streaming of Particular Membership Feature Posts.
 */
$featureUrl = $wa['custom_213']; // Use to Set the name of the Membership Feature this Streaming Widget will use. Can be controlled in the Design Settings.
$maxItems = 4; // Maximum amount of streaming items shown. Default is 4. Can be controlled in the Design Settings.
$hidePostsWithoutPhotos = $wa['custom_214']; // This will show or hide posts if they do not have photos. Default is true. Can be controlled in the Design Settings.
if ($wa['post_blogarticles_hideEmptyStream'] == "1" || $wa['post_blogarticles_hideEmptyStream'] == "") {
    $hideEmptyStream = true; // This hides the feature if there are no posts to show on it. Default is true. Can be controlled in the Design Settings.
} else {
    $hideEmptyStream = false;
}
if ($wa['post_blogarticles_onlyActiveMembers'] == "1" || $wa['post_blogarticles_onlyActiveMembers'] == "") {
    $onlyActiveMembers = true; // Show or Hide results from Active Members Only. Default is true.
} else {
    $onlyActiveMembers = false;
}
if ($wa['post_blogarticles_searchablePostsOnly'] == "0" || $wa['post_blogarticles_searchablePostsOnly'] == "") {
    $searchablePostsOnly = true; // Show posts even if they are not searchable. Useful for Membership Levels that do not want to show in the search results but want to show their posts in the streaming widgets, similar to how an admin account would work.
} else {
    $searchablePostsOnly = false;
}
$sortingOrder = "dp.post_start_date DESC, dp.post_live_date DESC"; // Sorting Order in which results will be shown. Values supported are DESC (Descending) & ASC (Ascending). Default is DESC.
if (isset($wa['custom_281'])) {
    $sortingOrder = ($wa['custom_281'] != 'RAND()') ? "dp.post_start_date " . $wa['custom_281'] . ", dp.post_live_date " . $wa['custom_281'] : $wa['custom_281'];
}
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


$onlySubscriptionIds = $wa['post_blogarticles_onlySubscriptionIds']; // Comma separated list of the subscription_ids that members are listed in to show posts of
$now = date('YmdHis');

if (empty($featureUrl)) {
    $featureUrl = 'my-blog';
}

if (empty($maxItems)) {
    $maxItems = 4;
}

if ($wa['custom_299'] == "2") {
    $columnWidth = "col-md-12";
} else if ($wa['custom_299'] == "1") {
    $columnWidth = "col-md-12";
} else {
    $columnWidth = "col-md-12";
}

if (is_numeric($featureUrl)) {
    $where = "data_id = " . $featureUrl;
} else {
    $where = "data_filename LIKE '" . $featureUrl . "'";
}

// Query that grabs information from the Membership Feature selected
$membershipFeaturesSQL = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
        data_id, data_filename
    FROM
        data_categories
    WHERE
        " . $where . "
    AND
        data_active = '1'
    LIMIT
        1");
$features = mysql_fetch_assoc($membershipFeaturesSQL);

$sqlSelectParameters = array(
    "dp.post_id",
    "dp.post_title",
    "dp.post_content",
    "dp.post_image",
    "dp.post_filename",
    "dp.post_updated",
    "dp.user_id"
);
if ($wa['custom_281'] == "" || $wa['custom_281'] == "DESC"){
    $tempTableOrder = $sortingOrder.", post_live_date DESC";
} else if ($wa['custom_281'] == "ASC"){
    $tempTableOrder = $sortingOrder.", post_live_date ASC";
} else {
    $tempTableOrder = "RAND()";
}
if ($wa['post_blogarticle_onePostperMember'] == "1") {
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
$sqlWhereParameters = array(
    "dp.user_id = ud.user_id",
    "ud.subscription_id = st.subscription_id",
    "dp.post_title != ''",
    "dp.post_status = '1'",
    "dp.data_id = ".$features['data_id'],
    "(dp.post_start_date <= $now OR dp.post_start_date = '')"
);
if (!empty($onlySubscriptionIds) && $onlySubscriptionIds != " ") {
    array_push($sqlWhereParameters, "ud.subscription_id IN (" . $onlySubscriptionIds . ")");
}
$sqlGroupByParameters = array();
if ($wa['post_blogarticle_onePostperMember'] == "1") {
    array_push($sqlGroupByParameters, "user_id");
}
$sqlHavingParameters = array();
$sqlOrderByParameters = array(
    $sortingOrder
);
$sqlLimitParameters = array(
    $maxItems
);

if ($onlyActiveMembers == true) {
    $sqlWhereParameters[] = "ud.active = '2'";
}
if ($hidePostsWithoutPhotos == true) {
    $sqlWhereParameters[] = "dp.post_image != ''";
}
$sqlWhereParameters[] = "st.data_settings LIKE '%" . $features["data_id"] . "%'";

/* -------------------------- Code That Constructs the SQL statement ------------------------------------- */
$sql = "";

if (count($sqlSelectParameters) > 0) {
    $sql .= "SELECT ";
    $sql .= implode(", ", $sqlSelectParameters);
}
if (count($sqlTablesParameters) > 0) {
    $sql .= " FROM ";
    $sql .= implode(", ", $sqlTablesParameters);
}
if (count($sqlWhereParameters) > 0) {
    $sql .= " WHERE ";
    $sql .= implode(" AND ", $sqlWhereParameters);
}
if (count($sqlGroupByParameters) > 0) {
    $sql .= " GROUP BY ";
    $sql .= implode(", ", $sqlGroupByParameters);
}
if (count($sqlHavingParameters) > 0) {
    $sql .= " HAVING ";
    $sql .= implode(" AND ", $sqlHavingParameters);
}
if (count($sqlOrderByParameters) > 0) {
    $sql .= " ORDER BY ";
    $sql .= implode(", ", $sqlOrderByParameters);
}
if (count($sqlLimitParameters) > 0) {
    $sql .= " LIMIT ";
    $sql .= implode(", ", $sqlLimitParameters);
}
/* -------------------------- END Code That Constructs the SQL statement ------------------------------------- */

$featureResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), $sql);
$featureNum = mysql_num_rows($featureResults);
$showFeature = true;

if (isset($_GET['devmode']) && $_GET['devmode'] == 1) {
    print_r($sql);
}

if ($hideEmptyStream == true) {

    if ($featureNum > 0) {
        $showFeature = true;

    } else {
        $showFeature = false;
    }
}

if ($wa['post_blogarticle_onePostperMember'] == "1") {
    mysql(brilliantDirectories::getDatabaseConfiguration('database'), "DROP TABLE temp_data_posts");
}

$titleStyleColor = "";
if($wa['recent_barticles_tColor'] != ""){
    $titleStyleColor = 'style="color: '.$wa['recent_barticles_tColor'].'"';
}

if ($showFeature == true) { ?>
    <div class="clearfix"></div>
    <div class="content-container">
        <div class="clearfix"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($wa['barticles_show_view_all'] != '0'){ ?>
                        <a href="/<?php echo $features['data_filename']; ?>" class="view-all-btn-desktop hidden-xs btn <?php echo $viewAllClass ?>">
                            View all blogs
                        </a>
                    <?php } ?>
                    <h2 class="nomargin sm-text-center streaming-title" <?php echo $titleStyleColor ?>>
                        Blog
                    </h2>
                    <hr>
                </div>
                <div class="row search_result member-level-<?php echo $user['subscription_id']; ?>" >
                    <div class="col-md-12 " >
                        <?php
                        while ($post = mysql_fetch_array($featureResults)) {
                            foreach ($post as $key => $value) {
                                $post[$key] = stripslashes($value);
                            }
                            $post['post_title'] = stripslashes($post['post_title']);

                            if (strlen($post['post_title']) > 40) {
                                $postTitle = substr($post['post_title'], 0, 40) . '...';

                            } else {
                                $postTitle = $post['post_title'];
                            }
                            if (strlen(strip_tags($post['post_content'])) > 60) {
                                $postContent = substr(strip_tags($post['post_content']), 0, 50) . '...';

                            } else {
                                $postContent = strip_tags($post['post_content']);
                            }
                            if ($post['post_image'] != "") {
                                $postImageFile = explode("/",str_replace("'","",$post['post_image']));
                                $postImageFileName = $postImageFile[3];
                                $thumbnailImage = "/uploads/news-pictures/" . $postImageFileName;
                                $postimage = "background-image:url(' ".$thumbnailImage." ')";
                            } else {
                                if($w['default_post_image'] != ''){
                                    $thumbnailImage = $w['default_post_image'];
                                    $postimage = "background-image:url(' ".$thumbnailImage." ')";
                                } else {
                                    $thumbnailImage = "/images/image-placeholder.jpg";
                                    $postimage = "background-image:url(' ".$thumbnailImage." ')";
                                }
                            } ?>
						
						
							<div class="mid_section xs-nopad col-sm-6 " style="background-color: #aae2d3; min-height: 400px;">           
			<div style="margin-top:35px; margin-right:-35px; margin-bottom:50px; padding: 35px; background-color:#fff; min-height:390px;">
	<div class="col-md-10 col-md-offset-1" style="margin-top:80px;">
			
			<a class="h3 bold bmargin center-block" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
				<?php echo $post['post_title']; ?>
			</a>

			<div class="clearfix"></div>

			<?php
			if ($post['post_content'] != "") { ?>
			<p class="xs-nomargin">
				<?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $post['post_content']),115)?>...<br><br><span style="color: #d77d64"> 
				<a class="inline-block bold" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
					Read more <span style="font-size:25px;"><strong>→</strong></span>
				</a></span>
			</p>
			<?php
			} ?>

			<div class="clearfix"></div>                             
</div>
			</div>

		</div>
                            <div class="hidden-xs col-sm-6  text-center bmargin " style=" margin-top:30px; " >
                            <?php if (!empty($w['lazy_load_images'])) { ?>
                                <div class="pic lazyloader" data-src="<?php echo $thumbnailImage; ?>">
									
									
									
                            <?php } else { ?>
                                <div class="pic" style="<?php echo $postimage; ?>">
                            <?php } ?>
                                    
                                </div>
                            </div> <div class="hidden-xs"><div class="clearfix" style="background-color: #aae2d3; min-height: 400px;"></div></div><br>
                        <?php } ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <?php if ($wa['barticles_show_view_all'] != '0'){ ?>
                    <div class="col-md-6">
                        <a href="<?php echo $features['data_filename']; ?>"
                           class="btn btn-lg <?php echo $viewAllClass ?> btn-block visible-xs-block">%%%view_all_label%%%</a>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?> 
            </div>
        </div>
    </div>
    <?php
    global $featureSliderEnabled, $featureMaxPerRow, $featureSliderClass, $postsCount;
    $postsCount = $featureNum;
    $featureSliderEnabled = (isset($wa['barticles_posts_slider']))?$wa['barticles_posts_slider']:NULL;
    $featureMaxPerRow = $wa['custom_299'];
    $featureSliderClass = '.slickBlogArticles';
    addonController::showWidget('post_carousel_slider','1a19675a36d28232077972bbdb6bb7fe');
}
?>