<?php
echo widget("Bootstrap Theme - Detail Page - Schema Markup - Product Post Type");
echo widget("Bootstrap Theme - Display - Posted By Snippet");
?>

<div id="post-content">

	<div class="col-sm-7 col-sm-6 col-xs-12 nopad">

		<div class="col-md-12 vmargin customdiv">

			<h1 class="bold h2">
				<?php echo $group['group_name']; ?>
			</h1>
			<h4>
				<?php 
					$user_id = $group['user_id']; 
					$company_string = mysql_fetch_assoc(mysql_query('SELECT * FROM users_data WHERE user_id = '.$user_id));
					$company_name = $company_string['company'] ? $company_string['company']: $company_string['first_name'].' '.$company_string['last_name'];
					echo $company_name;
				?>
			</h4>

			<div class="clearfix"></div>
			<div class="clearfix"></div>

		</div>

		<?php
		if ($group['post_promo'] != "" || $group['post_availability'] != "") { ?>
			<div class="col-md-12">
				<div class="btn-sm fpad bg-primary no-radius-bottom nonetag">
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
					<!-- <div class="clearfix"></div> -->
				</div>
				<!-- <div class="clearfix"></div> -->
			</div>
		<?php
		} ?>

		<div class=''>
			<?php
			if ($subscription['receive_messages'] != 1 && $user['active'] == 2) { ?>
			<div class="<?php if ($group['post_link']!="") { ?>col-sm-6 nopad rpad noradius<?php } else { ?>col-sm-12<?php } ?> vmargin">
				<a data-toggle="modal" data-target="#contactModal" class="btn btn-success btn-lg btn-block bold custom-contact">
					%%%contact_member_label%%%
				</a>
				[widget=Bootstrap Theme - Contact Member Modal]
			</div>
			<?php }
			if ($group['post_link'] != "") { ?>
			<div class="<?php if ($subscription['receive_messages'] != 1 && $user['active'] == 2) { ?>col-sm-6 nopad noradius<?php } else { ?>col-sm-12<?php } ?> vmargin">
				<a class="btn btn-primary btn-lg btn-block bold" title="<?php echo $group['group_name']; ?>" <?php if ($subscription['nofollow_links'] =="1") { ?>rel="nofollow"<?php } ?> href="<?php if (strpos($group['post_link'],'http') !== FALSE) { ?><?=$group['post_link']?><?php } else { ?>//<?=$group['post_link']?><?php } ?>" target="_blank">
					%%%more_details_membership_feature%%%
				</a>
			</div>
			<?php
			} ?>
			<!-- <div class="clearfix"></div> -->
			<?php
			if (($group['post_location'] != "" || $group['post_venue'] !="") && false) { ?>
				<i class="fa fa-map-marker text-danger"></i>
				<b><?php echo $group['post_venue']; ?></b>
				<span class="inline-block font-sm bmargin">
					<?php echo $group['post_location']; ?>
				</span>
			<?php
			} ?>
		</div>

	</div>

	<!-- <div class="clearfix"></div> -->

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
    <div id="gallery-1" class="royalSlider rsDefault col-xs-12">
        <?php
        while ($p = mysql_fetch_array($photogroup)) { 
			$p = getMetaData('users_portfolio', $p['photo_id'], $p, $w); ?>
            <a class="rsImg" data-rsbigimg="/<?php echo $w['photo_folder']; ?>/main/<?php echo $p['file']; ?>"  <?php if($p['video'] != "" && $dc['photo_gallery_videos'] == "1")  { ?>data-rsVideo="<?php echo $p['video'] ?>" <?php } ?>  href="/<?php echo $w['photo_folder']; ?>/main/<?php echo $p['file']; ?>">
                <?php if ($total > 1) { ?>
					
					<img  class="rsTmb" src="/<?php echo $w['photo_folder']; ?>/display/<?php echo $p['file']; ?>"><?php } ?>
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
	<button type="button"
				class="btn btn-default btn-sm custonclick"
				onclick="openGalleryPopup()">
			<i class="fa fa-expand"></i>
		</button>
	<div class="clearfix"></div>
    <?php
	} ?>

	<div class="row" >
		
	</div>

	<?php
	if (($group['post_tags'] != "" || $group['group_desc'] != "" || $post['post_content'] !="") && false) { ?>
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

#galleryCarousel{
    width: 400px;
    height: 400px;
}
.custonclick {
    position: relative;
    z-index: 99;
    width: 31px;
    right: -37%;
}
#gallery-1 {
	position: absolute;
}
	.rsFullscreenIcn{
		display: none !important;
	}
.rsArrowIcn{
	background-color: #3c3c3c6b !important
}
.rsSlide  img{
	width: 100% !important;
	height: 100% !important;
	border-radius: 0px !important;
}
.rsThumbsContainer{
	gap: 5px;
}
#first_container{
	background-color: white;
}
.noradius a{
	border-radius: 5px;
}
.rpad{
	padding-right: 3px !important;
	padding-left: 10px !important;
}
.col-sm-6:has(.customdiv){
	float: right;
	min-height: 235px;
	display: flex;
    flex-direction: column;
    justify-content: space-between;
	
}
.rsDefault .rsThumb{
	float: left;
    overflow: hidden;
    width: auto;
    height: fit-content !important;
}
@media (min-width: 768px) {
    .col-sm-7 {
        width: 58.33333333% !important;
    }
}
.rsDefault .rsThumb img {
    opacity: 1 !important;
}
   #gallery-1 {
    display: flex;
    flex-direction: row-reverse;
    width: 40% !important;
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
    .rsOverflow {
        width: 100%;
        height: 100% !important;
        position: relative;
        overflow: hidden;
        float: left;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }
    .rsDefault, .rsDefault .rsOverflow, .rsDefault .rsSlide, .rsDefault .rsVideoFrameHolder, .rsDefault .rsThumbs {
        background: #FFF !important;
    }
    .rsGCaption{
        display: none;
    }
    .nonetag {
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
   .rsThumbsHor {
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
   .grab-cursor{
        width: 426px !important;
        /* height: 300px !important; */
   }
   .rsThumbsHor{
    height: 100% !important;
   }
</style>
<script>

    // setTimeout(() => {
    //     const thumbs = document.querySelector(".rsNav.rsThumbs.rsThumbsHor");
    //     if (thumbs) {
    //         thumbs.style.setProperty("height", "100%", "important");
    //     }
    // }, 100);

    // setTimeout(() => {
    //     const parent = document.querySelector(".rsOverflow.grab-cursor");
    //     if (parent) {
    //         parent.style.setProperty("height", "300px", "important");
    //         parent.style.setProperty("width", "75%", "important");
    //     }
    // }, 200);

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

<!-- <style>
        
    body, .modal-header, .modal-content, .modal-body, .modal-footer {
        background: transparent;
        color: rgb(34, 34, 34);
        font-size: 15px;
    }
    .modal-content{
        height: 500px;
        width: 500px;
    }
    #galleryFullscreenModal .modal-body {
        background: transparent;
    }
    #galleryFullscreenModal img {
        max-height: 90vh;
    }
    .carousel-control {
        width: 8%;
        opacity: 0;
    }
    .carousel-control :hover{
        width: 8%;
        opacity: 0 !important;
    }
</style> -->

<style>
/* FULLY TRANSPARENT BACKDROP */
.modal-backdrop,
.modal-backdrop.fade,
.modal-backdrop.in,
.modal-backdrop.show {
    background: transparent !important;
    opacity: 0 !important;
}
</style>
<style>

#galleryFullscreenModal .carousel-control {
    background: transparent !important;
    background-image: none !important;
    box-shadow: none !important;
    opacity: 1 !important;
    text-shadow: none !important;
}

#galleryFullscreenModal .carousel-control:hover {
    background: transparent !important;
    background-image: none !important;
    box-shadow: none !important;
    opacity: 1 !important;
}

#galleryFullscreenModal .carousel-control:focus {
    background: transparent !important;
    background-image: none !important;
    box-shadow: none !important;
    outline: none !important;
}

#galleryFullscreenModal .carousel-control:active {
    background: transparent !important;
    box-shadow: none !important;
}

#galleryFullscreenModal .carousel-control .glyphicon {
    background: none !important;
    box-shadow: none !important;
    text-shadow: none !important;
}
</style>
<style>
#galleryFullscreenModal .carousel-control .glyphicon {
    color: #fff;
    font-size: 28px;
}
@media (min-width: 768px) {
    .modal-content {
        -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5) !important;
        box-shadow: 0 0px 0px rgba(0, 0, 0, .5) !important;
    }
}
</style>

<style>
#galleryFullscreenModal .close-custom {
    position: fixed;
    top: 15px;
    right: 20px;
    color: #fff;
    background: rgba(0,0,0,0.4);
    padding: 6px 12px;
    border-radius: 4px;
    z-index: 1055;
    cursor: pointer;
}
#galleryFullscreenModal .carousel-control .glyphicon {
    color: #282828;
    font-size: 15px !important;
    background: white !important;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 100%;
}
.modal-content {
    position: relative;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid #999;
    border: none !important;
    border-radius: 6px;
    -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
    box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
}
</style>

<style>

#galleryFullscreenModal .close-custom {
    position: absolute;
    top: 15px;
    right: 20px;
    color: #fff;
    cursor: pointer;
    z-index: 1051;
}
#galleryFullscreenModal .carousel-control .glyphicon {
    color: #282828;
    font-size: 28px;
}

@media (max-width: 768px) {
    #gallery-1 {
        position: relative;
        display: flex;
        flex-direction: row-reverse;
        width: 100% !important;
    }
    #post-content, .rsSlide {
        display: flex;
        flex-direction: column-reverse;
    }
    .custonclick {
        position: relative;
        z-index: 99;
        width: 31px;
        right: -91%;
    }
    .rsThumbsHor {
        width: 38% !important;
        height: 100%;
    }
}
@media (min-width: 768px) {
    .modal-dialog {
        width: 600px;
        /* margin: 30px auto; */
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }
}
/* @media (max-width: 435px) {
    .custonclick {
        position: relative;
        z-index: 99;
        width: 31px;
        right: -30em;
    }
}
@media (max-width: 300px) {
    .custonclick {
        position: relative;
        z-index: 99;
        width: 31px;
        right: -19em;
    }
} */
</style>

<div class="modal" id="galleryFullscreenModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content">

      <!-- CLOSE BUTTON -->
      <div class="close-custom">&times;</div>

      <div class="modal-body nopad" style="padding:0;">

        <div id="galleryCarousel" class="carousel slide">
          <div class="carousel-inner"></div>

          <a class="left carousel-control" href="#galleryCarousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
          </a>

          <a class="right carousel-control" href="#galleryCarousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
          </a>
        </div>

      </div>
    </div>
  </div>
</div>


<style>
.rsFullscreenIcn{ display:none !important; }
#galleryFullscreenModal img{ max-height:90vh;margin:auto; }
.carousel-control{ width:8%; }
</style>

<script>
function openGalleryPopup() {

    if (typeof jQuery === 'undefined') {
        alert('jQuery not loaded');
        return;
    }

    var $carouselInner = jQuery('#galleryCarousel .carousel-inner');
    $carouselInner.empty();

    jQuery('#gallery-1 img.rsMainSlideImage').each(function(index){

        var src = jQuery(this).attr('src');

        if (src) {
            $carouselInner.append(
                '<div class="item '+(index === 0 ? 'active' : '')+'">' +
                    '<img src="'+src+'" class="img-responsive center-block">' +
                '</div>'
            );
        }
    });

    if ($carouselInner.children().length) {
        jQuery('#galleryCarousel').carousel(0);
        jQuery('#galleryFullscreenModal').modal('show');
    } else {
        alert('No images found');
    }
}
</script>
<script>
jQuery(document).on('click', '#galleryFullscreenModal .close-custom', function() {
    jQuery('#galleryFullscreenModal').modal('hide');
});
</script>
[widget=product_page_tabs]