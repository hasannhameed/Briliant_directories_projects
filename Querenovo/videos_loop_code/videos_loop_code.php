<?php
$subscription = getSubscription($user['subscription_id'],$w);
$postFeaturedClass = ($post['sticky_post'] &&
    ($post['sticky_post_expiration_date'] == '0000-00-00' || $post['sticky_post_expiration_date'] >= date('Y-m-d')))
    ? ' featured-post featured-post-' . $dc['data_filename']
    : '';
?>


    <div class="search_result <?php echo $pars[0] == 'videos' ? 'col-md-4':''; ?> row-fluid member-level-<?php echo $user['subscription_id'] . $postFeaturedClass; ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
		<meta itemprop="position" content="<?php echo ++$GLOBALS['search_result_position']; ?>">
		<meta itemprop="url" content="/<?php echo $post['post_filename']; ?>">
		<meta itemprop="name" content="<?php echo htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8'); ?>">
		[widget=Bootstrap Theme - Detail Page - Schema Markup - Video Post Type]
        <div class="grid_element">

            <div class="col-xs-4 col-sm-2 norpad favorite_button">
                <?php
                $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
                if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                    echo '<span class="postItem" data-userid="'.$post['user_id'].'" data-datatype="'.$post['data_type'].'" data-dataid="'.$post['data_id'].'" data-postid="'.$post['post_id'].'"></span>';
                    echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
                }
                addonController::showWidget('post_comments','88a1bbf8d5d259c20c2d7f6c7651f672'); ?>
            </div>

            <div class="clearfix"></div>

            <?php if ($post['post_video_thumbnail']!="") { ?>
                <div class="img_section col-sm-4 nopad sm-bmargin">
                    <?php
                    echo $post['post_video_thumbnail'];
                    ?>
					<span class='channer_span'>Chaîne Quirenov'</span>
                    <div class='inner_div'></div>
                </div>
            <?php } ?>
            <div class='padding'>
                <div class="mid_section xs-nopad <?php if ($post['post_video'] != "" || $post['post_video_thumbnail']!="") { ?>col-sm-8<?php } else { ?>col-sm-12<?php } ?>">
                    <a class="h3 bold bmargin pull-left h3text" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename'];?>" style='display:none;'>
                        <?php echo $post['post_title']; ?>
                    </a>
                    <div class="clearfix"></div>
                    <div class='col-sm-12 nopad tmargin'>
                        <?php if($post['post_tags'] != '' ){ ?>
                                <p class='show pull-left post_tags'><? echo $post['post_tags'];  ?></p>
                        <?php  }  ?>
                    </div>
                   
                    <?php
                    if ($post['post_location'] != "") { ?>
                        <div class="post-location-snippet bmargin font-sm">
                            <i class="fa fa-map-marker text-danger"></i>
                            <?php echo $post['post_location']; ?>
                        </div>
                        <?php
                    } if ($post['post_content'] != "") { ?>
                        <p class="bpad xs-nopad xs-nomargin show" >
                            <?php echo limitWords(strip_tags($post['post_content']),115);?>...
                            <a class="inline-block bold" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                                %%%results_read_more_link%%%
                            </a>
                        </p>
                        <?php
                    } ?>

                    <div class="flx">
                        <div>
                            <?php if($post['post_category'] != '' ){ ?>
                                    <p class='show'><? echo $post['post_category'];  ?></p>
                            <?php  }  ?>
                        </div>
                        <div class=" nolpad font-sm bmargin show">
                                <span>
                                %%%posted_on%%%
                                <?php echo transformDate($post['post_live_date'],"QB");?>
                                </span>
                                    <?php
                                    if ($subscription['searchable'] != 0) { ?>
                                        <span class="posted-by inline-block">
                                    %%%by_label%%%
                                    <a class="bold notranslate" href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
                                        <?php echo $user['full_name']; ?>
                                    </a>
                                </span>
                                    <?php
                                } ?>
                        </div>
                    </div>

                    

                    <div class="hidden-xs row-fluid bpad ">
                        <a title="<?php echo $post['post_title']; ?>" class="btn btn-success col-sm-5 view-details rmargin watch" href="/<?php echo $post['post_filename']; ?>">
                            %%%watch_video%%%
                        </a>
                        <div class="clearfix"></div>
                    </div>

                    <?php
                    if ($post['post_category'] != "") { ?>
                        <div class="nolpad hidden-sm hidden-xs font-sm text-left post-search-category inline-block category">
                            %%%category%%%
                            <a class="bold" title="<?php echo $dc['data_name']; ?> - <?php echo $post['post_category']; ?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo urlencode($post['post_category']); ?>">
                                <?php echo $post['post_category']; ?>
                            </a>
                        </div>
                        <?php
                    } addonController::showWidget('star_ratings_for_posts','609d748eaa051578e8ae2e3c8f848d9a');?>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <hr>
<?php
if ($post['lat'] != '' && $post['lon'] != ''){
    $_ENV['post'] = $post;
    echo widget("Bootstrap Theme - Google Pins Locations","",$w['website_id'],$w);
} ?>