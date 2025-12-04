<?php
$p = getMetaData("users_portfolio_groups",$p['group_id'],$p,$w);
$presults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
        *
    FROM
        `users_portfolio`
    WHERE
        users_portfolio.group_id = '".$p[group_id]."'
    ORDER BY
        users_portfolio.order ASC,
        users_portfolio.photo_id ASC
    LIMIT
        1");
$pic = mysql_fetch_assoc($presults);
$p['group_picture'] = $pic['file'];
//$user = getUser($pic[user_id],$w);

if ($p['title'] == "") {
    $p['title'] = defaultPhotoTitle($p,$w);
} else {
    $nofollow = "";
}
$subscription = getSubscription($user['subscription_id'],$w);?>
<div class="search_result row-fluid member-level-<?php echo $user['subscription_id']; ?>">
    <div class="grid_element">

        <div class="col-xs-8 col-sm-10 nolpad font-sm bmargin"> 
            %%%posted_on%%%
            <?php 
            echo transformDate($p['date_updated'],"QB");
            if ($subscription['searchable'] != 0) { ?> 
            <span class="posted-by inline-block">
                %%%by_label%%%
                <a class="bold" href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user[full_name]; ?>">
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
            echo '<span class="postItem" data-userid="'.$p['user_id'].'" data-datatype="'.$p['data_type'].'" data-dataid="'.$p['data_id'].'" data-postid="'.$p['group_id'].'"></span>';
            echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
            } ?>                
            <div class="clearfix"></div>
        </div>
        
        <div class="clearfix"></div>

        <?php
        if ($p['group_picture'] != "") { ?>
            <div class="img_section col-sm-4 nopad sm-bmargin">
                <?php
                if ($p['property_type'] != "" || $p['property_status'] != "") { ?>
                <div class="btn-sm bg-primary line-height-xl bold no-radius-bottom">
                    <?php
                    if ($p['property_type'] != "") { ?>
                    <span class="pull-left">
                        <?php echo $p['property_type']; ?>            
                    </span>
                    <?php } if ($p['property_status'] != "") { ?>
                    <span class="pull-right badge">
                        <?php echo $p['property_status']; ?>
                    </span>
                    <?php
                    } ?>
                    <div class="clearfix"></div> 
                </div>
                <?php
                } ?>
                <div class="alert-secondary btn-block text-center">
                    <?php echo widget("Bootstrap Theme - Search Results - Display Image Slider","",$w['website_id'],$w);?>
                </div>
                <?php
                if ($p['post_promo'] != ""){ ?>
                <div class="bg-secondary hpad font-lg text-center line-height-xl">
                    <?php echo str_replace(' ', '', $p['post_promo']);  ?>
                </div>
                <?php
                } ?>                
            </div>
        <?php
        } ?>

        <div class="mid_section xs-nopad <?php if ($p['group_picture'] != "") { ?>col-sm-8<? } else { ?>col-sm-12<? } ?>">            

            <a class="h3 bold bmargin center-block" title="<?php echo $p['group_name']; ?>" href="/<?php echo $p['group_filename']; ?>">
                <?php echo $p['group_name']; ?>
            </a>

            <div class="clearfix"></div>
            
            <?php
            if ($p['post_location'] != "") { ?>
            <div class="post-location-snippet bmargin font-sm">
                <i class="fa fa-map-marker text-danger"></i> 
                <?php echo $p['post_location']; ?>
            </div>
            <?php
            } ?>                

            <?php
            if ($p['group_desc'] != "") { ?>
                <p class="bpad xs-nopad xs-nomargin">
                    <?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $p['group_desc']),115)?>...
                    <a class="inline-block bold" title="<?php echo $p['group_name']; ?>" href="/<?php echo $p['group_filename']; ?>">
                        %%%view_more_label%%%
                    </a>
                </p>
            <?php
            } ?>

            <div class="clearfix"></div>

            <div class="hidden-xs row-fluid bpad">
                <a title="<?php echo $p['group_name']; ?>" class="btn btn-success col-sm-5 view-details rmargin" href="/<?php echo $p['group_filename']; ?>">
                    %%%results_view_details_link%%%
                </a>
                <div class="clearfix"></div>
            </div>
            
            <div class="clearfix"></div>
            
            <? if ($p['property_beds'] != "" || $p['property_baths'] != "" || $p['property_sqr_foot'] != "") { ?>
            <div class="post-location-snippet tmargin">
                <?php if ($p['property_beds'] != "") { ?>
                <span class="inline-block rmargin">
                    <b>%%%property_search_bedrooms%%%</b>
                    <?php echo number_format($p['property_beds']); ?>
                </span>
                <?php } if ($p['property_baths'] != "") { ?>
                <span class="inline-block rmargin">
                    <b>%%%property_search_bathrooms%%%</b>
                    <?php echo number_format($p['property_baths']); ?>
                </span>
                <?php } if ($p['property_sqr_foot']!="") { ?>
                <span class="inline-block">
                    <b>%%%property_search_sq%%%</b>
<?php echo number_format($p['property_sqr_foot']); ?>
                </span>                 
                <?php
                } ?>
            </div>
            <?php
            } ?>
            
        </div>
    </div>
</div>
<div class="clearfix"></div>
<hr>
<?php
if ($p['lat'] != '' && $p['lon'] != ''){
    $_ENV['group'] = $p;
    echo widget("Bootstrap Theme - Google Pins Locations","",$w['website_id'],$w);
} ?>
