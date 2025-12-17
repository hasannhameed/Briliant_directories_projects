<?php
echo widget("Bootstrap Theme - Detail Page - Schema Markup - Product Post Type");
echo widget("Bootstrap Theme - Display - Posted By Snippet");
?>

<div id="post-content">

	<div class="col-sm-6">

		<div class="col-md-12 vmargin">

			<h1 class="bold h2">
				<?php echo $group['group_name']; ?>
			</h1>

			<div class="clearfix"></div>

			<?php
			if ($group['post_location'] != "" || $group['post_venue'] !="") { ?>
			<i class="fa fa-map-marker text-danger"></i>
			<b><?php echo $group['post_venue']; ?></b>
			<span class="inline-block font-sm">
				<?php echo $group['post_location']; ?>
			</span>
			<?php
			} ?>

			<div class="clearfix"></div>

		</div>

		<?php
		if ($group['post_promo'] != "" || $group['post_availability'] != "") { ?>
			<div class="col-md-12">
				<div class="btn-sm fpad bg-primary no-radius-bottom">
					<?php
					if ($group['post_promo'] != "") { ?>
						<span class="h4 nobmargin bold rmargin">
							<?php echo $group['post_promo']; ?>
						</span>
					<?php } if ($group['post_availability'] != "") { ?>
						<span class="pull-right badge">
							<?php echo $group['post_availability']; ?>
						</span>
					<?php
					} ?>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div>
		<?php
		} ?>

	</div>

	<div class="clearfix"></div>

	<?php
	$photogroup = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
			*
		FROM
			`users_portfolio`
		WHERE
			`group_id` = '".$group[group_id]."'
		AND
        	`data_id` = '".$group[data_id]."'
		AND
			`file` != ''
		ORDER BY
			`order` ASC");
	$total = mysql_num_rows($photogroup);

	if ($total > 0) { ?>
    <div id="gallery-1" class="royalSlider rsDefault">
        <?php
        while ($p = mysql_fetch_array($photogroup)) { 
			$p = getMetaData('users_portfolio', $p['photo_id'], $p, $w); ?>
            <a class="rsImg" data-rsbigimg="/<?php echo $w['photo_folder']; ?>/main/<?php echo $p['file']; ?>"  <?php if($p['video'] != "" && $dc['photo_gallery_videos'] == "1")  { ?>data-rsVideo="<?php echo $p['video'] ?>" <?php } ?>  href="/<?php echo $w['photo_folder']; ?>/main/<?php echo $p['file']; ?>">
                <?php if ($total > 1) { ?><img  class="rsTmb" src="/<?php echo $w['photo_folder']; ?>/display/<?php echo $p['file']; ?>"><?php } ?>
                <?php
                if ($p['desc'] != "" || $p['title'] != "") { ?>
                    <figure class="rsCaption">
                        <div class="captionContent">
                            <h4 class='nomargin'><?php echo $p['title']; ?></h4>
                            <?php echo limitWords($p['desc'],160); ?>
                        </div>
                    </figure>
                    <?php
                } ?>
            </a>
            <?php
        } ?>
    </div>
	<div class="clearfix"></div>
    <?php
	} ?>

	<div class="row">
		<?php
		if ($subscription['receive_messages'] != 1 && $user['active'] == 2) { ?>
		<div class="<?php if ($group['post_link']!="") { ?>col-sm-6<?php } else { ?>col-sm-12<?php } ?> vmargin">
			<a data-toggle="modal" data-target="#contactModal" class="btn btn-success btn-lg btn-block bold">
				%%%contact_member_label%%%
			</a>
			[widget=Bootstrap Theme - Contact Member Modal]
		</div>
		<?php }
		if ($group['post_link'] != "") { ?>
		<div class="<?php if ($subscription['receive_messages'] != 1 && $user['active'] == 2) { ?>col-sm-6<?php } else { ?>col-sm-12<?php } ?> vmargin">
			<a class="btn btn-primary btn-lg btn-block bold" title="<?php echo $group['group_name']; ?>" <?php if ($subscription['nofollow_links'] =="1") { ?>rel="nofollow"<?php } ?> href="<?php if (strpos($group['post_link'],'http') !== FALSE) { ?><?=$group['post_link']?><?php } else { ?>//<?=$group['post_link']?><?php } ?>" target="_blank">
				%%%more_details_membership_feature%%%
			</a>
		</div>
		<?php
		} ?>
		<div class="clearfix"></div>
	</div>

	<?php
	if ($group['post_tags'] != "" || $group['group_desc'] != "" || $post['post_content'] !="") { ?>
	<div class="well">
		<?php
		// the post description
		if ($group['group_desc_clean'] != "") {

				echo '<div class="the-post-description">' . $group['group_desc_clean'] .'<div class="clearfix"></div></div>';
		} ?>

		<?php
		if ($group['post_tags'] != "") {
			if ($group['group_desc_clean'] != "" ) { ?>
			<hr class=tmargin>
			<?php
			} ?>
		<div class="tags">
			<?php
			foreach(explode(",",$group['post_tags']) as $_ENV['tag']) {
				echo widget("Bootstrap Theme - Tag Link")." ";
			} ?>
		</div>
		<?php
		} ?>
	</div>
	<?php
	} ?>

</div>
<style>
   #gallery-1 {
    display: flex;
    flex-direction: row-reverse;
    width: 50% !important;
}
    #post-content,.rsSlide {
         background-color: white !important;
    }
    .rsSlide {
        width: 50%;
        left: 0px;
        display: flex !important;
        justify-content: center;
        align-items: center;
    }
    .rsDefault, .rsDefault .rsOverflow, .rsDefault .rsSlide, .rsDefault .rsVideoFrameHolder, .rsDefault .rsThumbs {
        background: #FFF !important;
    }
    .rsGCaption{
        display: none;
    }
    .col-sm-6 > div:nth-child(2) {
    display: none !important;
}

   .rsImg{
        width: 295px;
        height: 295px;
        margin-left: 152px;
        margin-top: 4px;
   }
   .rsImg,.rsMainSlideImage{
    visibility: visible;
    opacity: 1;
    transition: opacity 400ms 
    ease-in-out;
    width: 100%;
    height: 100%;
    margin-left: 0 !important;
    margin-top: 0 !important;
   }
   .rsDefault .rsThumbsHor {
        width: 25% !important;
        height: 100% ;
    }
    .rsDefault.rsWithThumbsHor .rsThumbsContainer {
        position: relative;
       display: flex;
       flex-direction: column-reverse;
       justify-content: center;
       align-items: center;
    }
    .rsThumbsArrowRight,.rsThumbsArrowLeft{
        display: none !important;
    }
    .rsDefault.rsWithThumbsHor .rsThumbsContainer {
        position: relative;
        height: 100%;
        width: 100% !important;
    }
    .rsThumbsContainer {
    transform: none !important;
    transition: none !important;
}
   
</style>
<script>
setTimeout(() => {
  const thumbs = document.querySelector(".rsNav.rsThumbs.rsThumbsHor");
  if (thumbs) {
    thumbs.style.setProperty("height", "100%", "important");
  }
}, 1000);

setTimeout(() => {
  const parent = document.querySelector(".rsOverflow.grab-cursor");
  if (parent) {
    parent.style.setProperty("height", "340px", "important");
    parent.style.setProperty("width", "75%", "important");
  }
}, 2000);


</script>

<script>
// document.addEventListener("DOMContentLoaded", function () {
//     const el = document.querySelector(".rsDefault .rsThumbsHor");

//     if (el) {
//         el.style.setProperty("width", "100%", "important");
//         el.style.setProperty("display", "flex", "important");
//         el.style.setProperty("flex-direction", "column-reverse", "important");
//         el.style.setProperty("justify-content", "center", "important");
//         el.style.setProperty("align-items", "center", "important");
//     }
// });

</script>