<?php

if(addonController::isAddonActive('featured_blog_article_slider') === true){
    $wa = getWebsiteLayout($w['website_id']);
    $now = date ('YmdHis');
    $get_postsResults = false;
    $dataCategoryModel = new data_categories();
    $dataCategory = $dataCategoryModel->get($wa['custom_251'], 'data_id');

    if($dataCategory->data_type == '20'){
        $get_postsModel = new data_posts();
        $get_postsWhere = array(
            array('value' => $wa['custom_251'] , 'column' => 'data_id', 'logic' => '='),
            array('value' => '' , 'column' => 'post_title', 'logic' => '!='),
            array('value' => '1' , 'column' => 'post_featured', 'logic' => '='),
            array('value' => '' , 'column' => 'post_image', 'logic' => '!='),
            array('value' => '1' , 'column' => 'post_status', 'logic' => '='),
            array('value' => $now , 'column' => 'post_start_date', 'logic' => '<')
        );
        $get_postsModel->setOrder('post_start_date', 'DESC');
        $get_postsResults = $get_postsModel->getByLimit(0, 5, $get_postsWhere);
        $resultsArray = array();
        if($get_postsResults !== false){
            if(!is_array($get_postsResults)){
                $resultsArray[] = $get_postsResults;
            } else {
                $resultsArray = $get_postsResults;
            }
        }
    } else if($dataCategory->data_type == '4') {
        $usersMetaModel = new users_meta();
        $groupsModel = new users_portfolio_groups();
        $usersMetaModel->setOrder('database_id', 'DESC');
        $metaWhere = array(
            array('value' => 'post_featured' , 'column' => 'key', 'logic' => '='),
            array('value' => '1' , 'column' => 'value', 'logic' => '='),
            array('value' => 'users_portfolio_groups' , 'column' => 'database', 'logic' => '='),
        );
        $featuredAlbumsResults = $usersMetaModel->get($metaWhere);
        $albumsArray = array();
        if($featuredAlbumsResults !== false){
            if(!is_array($featuredAlbumsResults)){
                $albumsArray[] = $featuredAlbumsResults;
            } else {
                $albumsArray = $featuredAlbumsResults;
            }
        }
        foreach($albumsArray as $album){
            $groupsWhere = array(
                array('value' => $wa['custom_251'] , 'column' => 'data_id', 'logic' => '='),
                array('value' => '' , 'column' => 'group_name', 'logic' => '!='),
                array('value' => '1' , 'column' => 'group_status', 'logic' => '='),
                array('value' => $album->database_id , 'column' => 'group_id', 'logic' => '=')
            );
            $matchedAlbum = $groupsModel->get($groupsWhere);
            if($matchedAlbums !== false){
                $groupsResults[] = $matchedAlbum;
            }
        }
        foreach($groupsResults as $group){
            $usersPortfolioModel = new users_portfolio();
            $usersPortfolioModel->setOrder('`order`','asc');
            $portfolioWhere = array(
                array('value' => $group->group_id , 'column' => 'group_id', 'logic' => '='),
            );
            $photoResult = $usersPortfolioModel->getByLimit(0, 1, $portfolioWhere);
            
            if($photoResult !== false){
                $resultsArray[$group->group_id] = $group;
                $resultsArray[$group->group_id]->photo = '/photos/main/'.$photoResult[0]->file;         
            }        
        }
        $resultsArray = array_slice($resultsArray,0,5);
    }
    if(!empty($resultsArray)){
    ?>

    <div class="clearfix clearfix-lg"></div>

    <div class="content-container">
    <div class="container">
        <div id="myCarousel" class="carousel slide col-md-offset-1 col-lg-offset-0 img-rounded" data-ride="carousel" style="max-width: 100%;">      

        <!-- Wrapper for slides -->
            <div class="carousel-inner col-xs-12 col-sm-8 nopad <?php if ($wa['streaming_articlesList'] == "left") { ?>pull-right<?php } ?>">
            <?

            $slidecount = 0;
            foreach($resultsArray as $get_post){  
                if($dataCategory->data_type == '4'){
                    $title = $get_post->group_name;
                    $content = $get_post->group_desc;
                    $filename = $get_post->group_filename;
                    $image = $get_post->photo;
                } else {
                    $title = $get_post->post_title;
                    $content = $get_post->post_content;
                    $filename = $get_post->post_filename;
                    $image = $get_post->post_image;
                }
                $slidecount++;
                //$get_post=getMetaData("data_posts",$get_post->post_id,$get_post,$w);
                $user = getUser($get_post->user_id,$w);
                foreach ($get_post as $key => $value){
                    $get_post->$key = stripslashes($value);
                }
                ?>
                <div class="item <? if ($slidecount == 1) { ?> active <? } ?>">
                    <a href="/<?php echo$filename?>">
                        <?php if (!empty($w['lazy_load_images'])) { ?>
                            <img class="lazyloader" alt="<?php echo $title?>" title="<?php echo $title?>" data-src="<?php echo str_replace("'","",$image)?>"  />
                        <?php } else { ?>
                            <img alt="<?php echo $title?>" title="<?php echo $title?>" src="<?php echo str_replace("'","",$image)?>"  />
                        <?php } ?>
                    </a>
                    <div class="carousel-caption">
                        <div class="col-sm-7 col-md-7 col-lg-9">
                            <p class="hidden-lg font-lg nomargin">
                                <?php echo $title?>
                            </p>
                            <p class="hidden-md hidden-sm hidden-xs nomargin">
                                <?php 
                                if($content){
                                    if(strlen($content) > 120){
                                        echo limitWords(strip_tags($content),120).'...';
                                    } else {
                                        echo $content;
                                    }
                                } ?>
                                
                            </p>
                        </div>
                        <div class="col-sm-5 col-md-5 col-lg-3 xs-tmargin">
                            <a title="<?php echo $title?>" class="btn btn-success btn-block xs-tmargin" href="/<?php echo $filename?>">
                                %%%results_read_more_link%%%
                            </a>
                        </div>
                    </div>
                </div><!-- End Item -->
                <? } ?>
        </div><!-- End Carousel Inner -->


        <ul class="list-group col-sm-4 nopad hidden-sm hidden-xs hidden-md">
        <?
        $slidecount = 0;
        foreach($resultsArray as $get_post){ 
            $get_post->$key = stripslashes($value);
            if($dataCategory->data_type == '4'){
                    $title = $get_post->group_name;
                    $content = $get_post->group_desc;
                    $filename = $get_post->group_filename;
                    $image = $get_post->photo;
                } else {
                    $title = $get_post->post_title;
                    $content = $get_post->post_content;
                    $filename = $get_post->post_filename;
                    $image = $get_post->post_photo;
            }
        $slidecount++;
        //$get_post=getMetaData("data_posts",$get_post->post_id,$get_post,$w);
        $user = getUser($get_post->user_id,$w);
        foreach ($get_post as $key => $value){
        }
        ?>
            <li data-target="#myCarousel" data-slide-to="<?php echo $slidecount - 1?>" class="list-group-item <? if ($slidecount == 1) { ?> active <? } ?>">
                <h4><?php echo limitWords(strip_tags($title),60);if(strlen($title)>60){?>...<? }?></h4>
            </li>
            <? } ?>
            </ul>
            
            <!-- Controls -->
            <div class="carousel-controls hidden-lg">
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            </div>      

        <!-- End Carousel -->
        </div> <!-- End myCarousel -->
    </div> <!-- End container -->
    </div> <!-- End content-container -->
    <div class="clearfix"></div>
    <?php } 
}?>