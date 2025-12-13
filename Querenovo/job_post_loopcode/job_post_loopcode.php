    <?php  if($pars[0]=='jobs') { ?>
    
    <?php
    $subscription2 = $_COOKIE['subscription_id'];

    if($post['post_category']=='Sous-traitance' && ($subscription2 == 1 || $subscription2 == 6 || $subscription2 == '')){  }else{

    $post = getMetaData("data_posts", $post['post_id'], $post, $w); 
    $subscription = getSubscription($user['subscription_id'],$w);
    $postFeaturedClass = ($post['sticky_post'] &&
        ($post['sticky_post_expiration_date'] == '0000-00-00' || $post['sticky_post_expiration_date'] >= date('Y-m-d')))
        ? ' featured-post featured-post-' . $dc['data_filename']
        : '';
    ?>
    <div class="search_result row-fluid member-level-<?php echo $user['subscription_id'] . $postFeaturedClass; ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <meta itemprop="position" content="<?php echo ++$GLOBALS['search_result_position']; ?>">
        <meta itemprop="url" content="/<?php echo $post['post_filename']; ?>">
        <meta itemprop="name" content="<?php echo htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8'); ?>">
        [widget=Bootstrap Theme - Detail Page - Schema Markup - Jobs Post Type]
        <div class="grid_element">
        
            <div class="col-xs-8 col-sm-10 nolpad font-sm bmargin posted_meta_data" style='display:none;'>
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
            
            <?php
            if ($post['post_image'] != "") {
                $postImageFile = explode("/", str_replace("'", "", $post['post_image']));
                $postImageFileName = $postImageFile[3];
                $thumbnailImage = "";
                if ($w['website_id'] < 9999) {
                    $imageSettingsCheck = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT 1 FROM `image_settings` LIMIT 1");
                    if ($imageSettingsCheck && $w['enable_new_path_image'] == 1) {
                        $thumbnailImage = "/uploads/news-pictures-thumbnails/" . $postImageFileName;
                    } else {
                        $thumbnailImage = "/uploads/news-pictures/" . $postImageFileName;
                    }
                } else {
                    $thumbnailImage = "/uploads/news-pictures-thumbnails/" . $postImageFileName;
                }
                list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $thumbnailImage);
                if ($attr == "") {
                    $attr = 'width="525" height="280"';
                }					
            ?>
            <div class="img_section col-sm-4 nopad sm-bmargin">  
                
                <a title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                
                        <div class="alert-secondary btn-block text-center img-div">
                                <img <?php echo $attr; ?> class="search_result_image center-block" alt="<?php echo (!empty($post['post_alt'])?$post['post_alt']:$post['post_title'])?>" title="<?php echo $post['post_title']; ?>" src="<?php echo $thumbnailImage; ?>"/>
                        </div>
                
                </a>
                <div class='custom_category_badge '>
                    <?php if($post['urgent']==1){ ?>
                        <p class='btn-primary btn-sm custom_badge_urgent'>Urgent</p>
                    <?php } ?>
                </div>
            
            </div>          
            <?php
            } ?>
            
            <div class="mid_section xs-nopad <?php if ($post['post_image'] != "") { ?>col-sm-8<? } else { ?>col-sm-12<? } ?> ">           
                <div class='col-sm-12 nopad'>
                    <a class="h3 bold bmargin center-block pull-left" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>"><?php echo $post['post_title']; ?></a>
                </div>
                <div class='custom_category_badge col-sm-12 text-left nopad bmargin'>
                    <span class='<? echo $post['post_category'] != '' ?  'custom_badge':''; ?>'><?php echo $post['post_category']; ?></span>
                    <span class='<? echo $post['data_type'] != '' ?  'custom_badge':''; ?>'><?php echo $post['data_type']; ?></span>
                    <span class='<? echo $post['data_type'] != '' ?  'custom_badge':''; ?>'><?php echo $post['post_id']; ?></span>

                    <?php if ($post['post_job'] != "" && false) { ?> 
                        <span class="custom_badge">
                            <?php echo $post['post_job']; ?>
                        </span>
                    <?php } ?>
                </div>
                
                <div class="clearfix"></div>

                <?php
                if ($post['post_content'] != "" ) { ?>
                    <p class="bpad xs-nomargin xs-nopad show description">
                        <?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $post['post_content']),115);?>... 
                        <a class="inline-block bold" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                        %%%view_more_label%%%
                        </a>
                    </p>
                <?php
                } ?>

                <?php 

                    $user_id = (int)$post['user_id'];

                    $query = mysql_query("SELECT company, email, phone_number, first_name, last_name FROM users_data WHERE user_id = ".$user_id);

                    if(!$query){
                        echo "DB Error";
                        return;
                    }
                
                    $data  =  mysql_fetch_assoc($query);
                    $email =  $data['email'];
                    $phone_number = $data['phone_number'];
                    $company_name = !empty($data['company']) 
                        ? $data['company'] 
                        : $data['first_name'] . ' ' . $data['last_name'];

                    echo '<span class="inline-block bold bmargin">' . $company_name . '</span>';

                ?>


                <div class="col=sm-12 nopad textwrapper">
                    <div class="job_category_parent">
                        <?php
                        if ($post['post_location'] != "" || $post['post_venue'] !="") { ?>
                        <div class="post-location-snippet xs-nomargin">
                            <!-- <i class="fa fa-map-marker text-danger"></i>  -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-4 h-4"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <span><?php echo $post['post_venue']; ?></span>
                                <span class="inline-block hidden-xs font-sm">
                                    <?php echo $post['post_location']; ?>
                                </span>	
                        </div>
                        <?php } ?>
                    </div>

                    <?php if ($post['post_job'] != "" || $post['post_promo'] != "") { ?>
                        <div class="btn-sm nopad line-height-xl bold no-radius-bottom green-text">

                            <?php if ($post['post_promo'] != "") { ?>

                                <span class="pull-left font-lg">
                                    <?php
                                    if ($post['post_price'] == "0") {
                                        echo '<span class="dollor">$</span>'.$label['unpaid_membership_features'];
                                    } else {
                                        echo '<span class="dollor">$</span>'.displayPrice($post['post_price']).'/month'; 
                                    } ?>            
                                </span> 

                            <?php } ?> 

                            <div class="clearfix"></div>

                        </div>
                    <?php  } ?> 

                    <div class="clearfix"></div>
                    
                    <div class="hidden-xs row-fluid bpad ">
                        <a title="<?php echo $post['post_title']; ?>" class="view-details rmargin text-left hide" href="/<?php echo $post['post_filename']; ?>">
                        %%%results_view_details_link%%%
                        </a>
                        <?php
                        if ($post['post_start_date'] != "") { ?>
                        <a title="<?php echo $post['post_title']; ?>" class="contact-member lmargin text-left" href="/<?php echo $post['post_filename']; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-3 h-3"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                            <small>%%%start_date_membership_feature_details%%%</small>
                            <span><?php echo transformDate($post['post_start_date'],"QB"); ?></span>
                        </a>                                
                        <?php 
                        } ?>
                        <div class="clearfix"></div>
                    </div> 
                </div>

                <div class='col-sm-12 nopad img_section button-section'>
                    <div class="col-sm-6 nopad btn-block">
                        <div class='btndiv1'>
                            <a href='mailto:<?php echo $email; ?>'>
                                <button class='btn  btn-block'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail w-4 h-4 mr-2"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                                    &nbsp;&nbsp;<span>Email</span>
                                </button>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-6 nopad btn-block">
                        <div class='btndiv2'>
                            <a href='tel:<?php echo $phone_number; ?>'>
                            <button class='btn btn-secondary btn-block'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone w-4 h-4 mr-2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                &nbsp;&nbsp;<span>Appeler</span>
                            </button>
                        </div>
                    </div>
                </div>                             

                <?php
                if ($post['post_category'] != "") { ?> 
                <div class="nolpad hidden-sm hidden-xs font-sm text-left post-search-category inline-block hide" >
                    %%%category%%% 
                    <a class="bold" title="<?php echo $dc['data_name']; ?> - <?php echo $post['post_category']; ?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo urlencode($post['post_category']); ?>">
                        <?php echo $post['post_category']; ?>
                    </a> 
                </div>
                <?php } addonController::showWidget('star_ratings_for_posts','609d748eaa051578e8ae2e3c8f848d9a');?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <hr> 
    <?php
    if ($post['lat'] != '' && $post['lon'] != '') {
        $_ENV['post'] = $post;
        echo widget("Bootstrap Theme - Google Pins Locations", "", $w['website_id'], $w);
    } ?>

    <?php  } ?>
    <?php  } else { ?>
        <?php
            $post = getMetaData("data_posts", $post['post_id'], $post, $w); 
            $subscription = getSubscription($user['subscription_id'],$w);
            $postFeaturedClass = ($post['sticky_post'] &&
                ($post['sticky_post_expiration_date'] == '0000-00-00' || $post['sticky_post_expiration_date'] >= date('Y-m-d')))
                ? ' featured-post featured-post-' . $dc['data_filename']
                : '';
            ?>
            <div class="search_result row-fluid member-level-<?php echo $user['subscription_id'] . $postFeaturedClass; ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <meta itemprop="position" content="<?php echo ++$GLOBALS['search_result_position']; ?>">
                <meta itemprop="url" content="/<?php echo $post['post_filename']; ?>">
                <meta itemprop="name" content="<?php echo htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8'); ?>">
                [widget=Bootstrap Theme - Detail Page - Schema Markup - Jobs Post Type]
                <div class="grid_element">
                
                    <div class="col-xs-8 col-sm-10 nolpad font-sm bmargin posted_meta_data">
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
                    
                    <?php
                    if ($post['post_image'] != "") {
                        $postImageFile = explode("/", str_replace("'", "", $post['post_image']));
                        $postImageFileName = $postImageFile[3];
                        $thumbnailImage = "";
                        if ($w['website_id'] < 9999) {
                            $imageSettingsCheck = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT 1 FROM `image_settings` LIMIT 1");
                            if ($imageSettingsCheck && $w['enable_new_path_image'] == 1) {
                                $thumbnailImage = "/uploads/news-pictures-thumbnails/" . $postImageFileName;
                            } else {
                                $thumbnailImage = "/uploads/news-pictures/" . $postImageFileName;
                            }
                        } else {
                            $thumbnailImage = "/uploads/news-pictures-thumbnails/" . $postImageFileName;
                        }
                        list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $thumbnailImage);
                        if ($attr == "") {
                            $attr = 'width="525" height="280"';
                        }					
                    ?>
                    <div class="img_section col-sm-4 nopad sm-bmargin">  

                        <?php
                        if ($post['post_job'] != "" || $post['post_promo'] != "") { ?>
                        <div class="btn-sm bg-primary line-height-xl bold no-radius-bottom">
                            <?php
                            if ($post['post_promo'] != "") { ?>
                            <span class="pull-left font-lg">
                                <?php
                                if ($post['post_price'] == "0") {
                                    echo $label['unpaid_membership_features'];
                                } else {
                                    echo displayPrice($post['post_price']); 
                                } ?>            
                            </span> 
                            <?php
                            } if ($post['post_job'] != "") { ?> 
                            <span class="pull-right badge">
                                <?php echo $post['post_job']; ?>
                            </span>
                            <?php
                            } ?>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                        } ?> 
                        
                        <a title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                            <div class="alert-secondary btn-block text-center">
                                <img <?php echo $attr; ?> class="search_result_image center-block" alt="<?php echo (!empty($post['post_alt'])?$post['post_alt']:$post['post_title'])?>" title="<?php echo $post['post_title']; ?>" src="<?php echo $thumbnailImage; ?>"/>
                            </div>
                        </a> 

                    </div>          
                    <?php
                    } ?>
                    
                    <div class="mid_section xs-nopad <?php if ($post['post_image'] != "") { ?>col-sm-8<? } else { ?>col-sm-12<? } ?> ">           

                        <a class="h3 bold bmargin center-block" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                            <?php echo $post['post_title']; ?>
                        </a>

                        <div class="clearfix"></div>

                        <?php
                        if ($post['post_location'] != "" || $post['post_venue'] !="") { ?>
                        <div class="post-location-snippet bmargin xs-nomargin">
                            <i class="fa fa-map-marker text-danger"></i> 
                            <b><?php echo $post['post_venue']; ?></b>
                            <span class="inline-block hidden-xs font-sm">
                                <?php echo $post['post_location']; ?>
                            </span>		
                        </div>
                        <?php
                        } ?>

                        <?php
                        if ($post['post_content'] != "") { ?>
                            <p class="bpad xs-nomargin xs-nopad">
                                <?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $post['post_content']),115);?>... 
                                <a class="inline-block bold" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                                    %%%view_more_label%%%
                                </a>
                            </p>
                        <?php
                        } ?>

                        <div class="clearfix"></div>
                        
                        <div class="hidden-xs row-fluid bpad">
                            <a title="<?php echo $post['post_title']; ?>" class="btn btn-success col-sm-5 view-details rmargin" href="/<?php echo $post['post_filename']; ?>">
                                %%%results_view_details_link%%%
                            </a>
                            <?php
                            if ($post['post_start_date'] != "") { ?>
                            <a title="<?php echo $post['post_title']; ?>" class="btn btn-primary col-sm-5 contact-member lmargin" href="/<?php echo $post['post_filename']; ?>">
                                <small>%%%start_date_membership_feature_details%%%</small>
                                <b><?php echo transformDate($post['post_start_date'],"QB"); ?></b>
                            </a>                                
                            <?php 
                            } ?>
                            <div class="clearfix"></div>
                        </div>                              

                        <?php
                        if ($post['post_category'] != "") { ?> 
                        <div class="nolpad hidden-sm hidden-xs font-sm text-left post-search-category inline-block">
                            %%%category%%% 
                            <a class="bold" title="<?php echo $dc['data_name']; ?> - <?php echo $post['post_category']; ?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo urlencode($post['post_category']); ?>">
                                <?php echo $post['post_category']; ?>
                            </a> 
                        </div>
                        <?php } addonController::showWidget('star_ratings_for_posts','609d748eaa051578e8ae2e3c8f848d9a');?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr> 
            <?php
            if ($post['lat'] != '' && $post['lon'] != '') {
                $_ENV['post'] = $post;
                echo widget("Bootstrap Theme - Google Pins Locations", "", $w['website_id'], $w);
            } ?>
    <?php } ?>