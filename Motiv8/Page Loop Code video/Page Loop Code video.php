<?php 
$subscription = getSubscription($user['subscription_id'],$w);
echo"";
?>

<div class="search_result row-fluid member-level-<?php echo $user['subscription_id']; ?>">
	<div class="grid_element">

		<div class="col-xs-8 col-sm-10 nolpad font-sm bmargin">  
			<!-- %%%posted_on%%%
			<?php echo transformDate($post['post_live_date'],"QB");?> -->
			<?php 
			if ($subscription['searchable'] != 0) { ?> 
			<span class="posted-by inline-block">
				Posted %%%by_label%%%
				<a class="bold" href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
					<?php echo $user['full_name']; ?>
				</a>
			</span>
			<?php 
			} ?>   
		</div>

		<div class="col-xs-4 col-sm-2 norpad"> 
			<?php
			$addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
			if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
			echo '<span class="postItem" data-userid="'.$post['user_id'].'" data-datatype="'.$post['data_type'].'" data-dataid="'.$post['data_id'].'" data-postid="'.$post['post_id'].'"></span>';
			echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
			} ?>              
		</div>

		<div class="clearfix"></div>
        
        <?php if ($post['post_video']!="") { ?>
            <div class="img_section col-sm-4 nopad sm-bmargin">
                <?php
                $link = $post[post_video_link];
                $url = str_replace( 'https://', 'http://', $link );
                if(strpos($url, "youtube")){
                    if(strpos($url, "embed")){
                        $cart =  substr($url , 29, 11);
                    } else {
                        parse_str( parse_url( $url, PHP_URL_QUERY ), $video_array_of_vars ); 
                        $cart = $video_array_of_vars['v'];
                    }   
                    echo $img = "<a class='video__play_link' data-video-url='$post[post_video]' title='$post[post_title]' id='$cart' href='/$post[post_filename]'><img title='$post[post_title]' src='//img.youtube.com/vi/$cart/mqdefault.jpg' class='search_result_image img-rounded center-block'></a>";
                }
                if(strpos($url, "youtu.be")){
                    $cart =  substr($url , 16);
                    echo $img = "<a class='video__play_link' data-video-url='$post[post_video]' title='$post[post_title]' id='$cart' href='/$post[post_filename]'><img alt='$post[post_title]' src='//img.youtube.com/vi/$cart/mqdefault.jpg' class='search_result_image img-rounded center-block'></a>";

                }
                if(strpos($url, "vimeo")){  //vimeo
                    $cart = explode("/",$url);
                    $cartLength = count($cart) - 1;
                    $imgid = $cart[$cartLength];
                    $url = "http://vimeo.com/api/v2/video/".$imgid.".json";
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $curlData = curl_exec($curl);
                    curl_close($curl);
                    $hash = current(json_decode($curlData, true));
                    echo $img = "<a class='video__play_link'  data-video-url='$post[post_video]' title='$post[post_title]' id='$cart' href='/$post[post_filename]'><img alt='$post[post_title]' class='search_result_image img-rounded center-block' src='" . $hash['thumbnail_medium'] . "'/></a>";
                }
                ?>
            </div>
        <? } ?>

        <div class="mid_section xs-nopad <?php if ($post['post_video'] != "") { ?>col-sm-8<?php } else { ?>col-sm-12<?php } ?>">

            <a class="h3 bold bmargin center-block" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename'];?>">
                <?php echo $post['post_title']; ?>
            </a>

            <div class="clearfix"></div>

            <?php
            if ($post['post_location'] != "") { ?>
            <div class="post-location-snippet bmargin font-sm">
                <i class="fa fa-map-marker text-danger"></i> 
                <?php echo $post['post_location']; ?>
            </div>
            <?php
            } if ($post['post_content'] != "") { ?>
            <p class="bpad xs-nopad xs-nomargin">
                <?php echo limitWords(strip_tags($post['post_content']),115);?>... 
                <a class="inline-block bold" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                    %%%results_read_more_link%%%
                </a>
            </p>
            <?php 
            } ?>

            <div class="clearfix"></div>
            
            <div class="hidden-xs row-fluid bpad">
                <a title="<?php echo $post['post_title']; ?>" class="btn btn-success col-sm-5 view-details rmargin" href="/<?php echo $post['post_filename']; ?>">
                    %%%watch_video%%%
                </a>
				<div class="clearfix"></div>
            </div>             
            
            <?php
            if ($post['post_category'] != "") { ?> 
            <div class="col-md-12 nolpad tmargin hidden-xs font-sm text-left">
                %%%category%%%
                <a class="bold" title="<?php echo $dc['data_name']; ?> - <?php echo $post['post_category']; ?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo $post['post_category']; ?>">
                    <?php echo $post['post_category']; ?>
                </a> 
            </div>
            <?php 
            } ?>                

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