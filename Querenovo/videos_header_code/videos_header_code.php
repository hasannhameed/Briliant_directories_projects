<?php
$addOnWidget = '';
$gridViewAddOn = getAddOnInfo('grid_view_search_reults','769e3e86f08b2d05aaabb1e555221b87'); 
$mapViewAddOn = getAddOnInfo('google_map_search_results','ccda1a004e20781cca3712ec22a57434');

if (isset($gridViewAddOn['status']) && $gridViewAddOn['status'] == 'success'){
	$addOnWidget = $gridViewAddOn['widget'];
}
if (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success'){
	$addOnWidget = $mapViewAddOn['widget'];
}
if ((isset($gridViewAddOn['status']) && $gridViewAddOn['status'] == 'success') || (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success')) { 
	echo widget($addOnWidget,"",$w['website_id'],$w);
} ?> 

<div class="grid-container">
<div class="container inputdiv">
    <div class='custom_container'>
        [sidebar=Post Search Result]
    </div>
</div>

<style>
	/* Hide any existing play / overlay icons */
.video__play_link svg,
.video__play_link i,
.video__play_link .play-icon,
.video__play_link::before,
.video__play_link::after {
    display: none !important;
}
/* Card image container */
.img_section {
    position: relative;
    overflow: hidden;
}

/* Overlay (hidden by default) */
.inner_div {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    opacity: 0;
    transition: opacity 0.35s ease;
    pointer-events: none;
}

/* Play button circle */
.inner_div::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 64px;
    height: 64px;
    background: #e53935; /* your red */
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0.8);
    opacity: 0;
    transition: all 0.35s ease;
}

/* Play triangle */
.inner_div::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: 4px;
    width: 0;
    height: 0;
    border-left: 18px solid #fff;
    border-top: 11px solid transparent;
    border-bottom: 11px solid transparent;
    transform: translate(-50%, -50%) scale(0.8);
    opacity: 0;
    transition: all 0.35s ease;
}

/* Hover trigger */
.grid_element:hover .inner_div {
    opacity: 1;
}

.grid_element:hover .inner_div::before,
.grid_element:hover .inner_div::after {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

/* Subtle image zoom */
.img_section img {
    transition: transform 0.4s ease;
}

.grid_element:hover img {
    transform: scale(1.04);
}

/*---*/
.grid_element {
    background: #fff;
    border-radius: 14px;
    transition: box-shadow 0.35s ease;
}

/* Very soft hover shadow */
.grid_element:hover {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}
.grid_element:hover {
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
}

.grid_element:hover {
    /* transform: translateY(-4px); */
     box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.grid_element:hover img {
    transform: scale(1.02);
}

.grid_element img {
    transition: transform 0.25s ease;
}



	.h3.bold.bmargin.center-block.text-center.h4.tmargin {
		text-align: left;
	}

	.views{
		display:none !important;
	}

	.search-bar{
		background: white;
		margin-bottom:0px;
		padding: 10px;
	}	
	
	.date,.category{
		display: none;
	}

	.watch{
		background: white;
		color : black;
		margin-top: 20px;
	}
	
	.watch:hover{
		background: rgb(250, 250, 250);
		color:black;
	}

	.h3text{
		text-align:justify;
		margin-bottom: 5px;
	}
	
	.flx{
		display: flex;
        justify-content: space-between;
         width: 100%;
	}
	
	.post_tags a{
		padding:0px;
		background-color:transparent;
		padding: 4px;
        border-radius: 5px;
	}
	
	.post_tags a:hover{
		padding:0px;
		background-color:transparent;
		padding: 4px;
        border-radius: 5px;
	}
	.img_section{
		border-bottom-left-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
	}
	.img-rounded img, img.fr-dib.img-rounded {
		border-radius: 10px !important;
		border-bottom-left-radius: 0px !important;
		border-bottom-right-radius: 0px !important;
	}
	.channer_span {
		font-size: 12px;
		top: 8px;
		left: 8px;
		position: absolute;
		background-color: red;
		text: white;
		color: white;
		font-weight: bold !important;
		padding: 2px 9px;
		border-radius: 6px;
	}
	.inner_div{
		/* top: 0;
		width: 100%;
		height: 100%;
		position: absolute;
		z-index: \\;
		background-color: #0f285526;
		background: linear-gradient(to bottom, rgba(15, 40, 85, 0) 0%, rgb(15 40 85 / 57%) 100%); */
	}

.youtube-lazy-container {
    position: relative;
    display: block;
    cursor: pointer;
    overflow: hidden;
    background: #000;
    margin: 0;
    width: 100% !important;
    max-width: 100% ! IMPORTANT;
}
	
	
.modal-fullscreen {
	width: 50%;
    height: 100%;
    padding: 0;
}

.modal-fullscreen .modal-content {
    height: 95vh;
    border-radius: 0;
    border: none;
	border-radius: 10px;
	padding: 20px;
}

.modal-fullscreen iframe {
    width: 100%;
    height: 100vh;

}

/* Remove padding from body */
.no-padding {
    padding: 0;
}
.embed-responsive,.embed-responsive-16by9 {
		border-radius: 10px;
    position: relative !important;
    border-radius: 10px !important;
}

/* Close button improvements */
.modal-close-btn {
    position: absolute;
    right: 15px;
    top: 10px;
    z-index: 1051;
    font-size: 40px;
    color: white;
    background: transparent;
    border: none;
}

.feature_results_header {
    margin-top: 0px !important;
    margin-left: 230px;
}
	
@media (max-width: 768px) {
		.modal-fullscreen .modal-content {
			height: 95vh;
			width: 95vw;
			border-radius: 10px;
			border: none;
			padding: 20px;
		}
	}
	
@media (max-width: 1200px) {
  .feature_results_header {
    margin-left: 0 !important;
  }
	.container {
		padding-right: 0px !important;
		padding-left: 15px;
		margin-right: auto;
		margin-left: auto;
	}
}


	
</style>
	
<!-- Fullscreen Modal -->
<div id="videoModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-body no-padding">
        <iframe id="iframePreview" src="" frameborder="0" allowfullscreen></iframe>
      </div>
      <button type="button" class="close modal-close-btn" data-dismiss="modal">×</button>
    </div>
  </div>
</div>


	
<div class='bg container'>
<div class='custom_container'>
	
	
	[widget=videos_top_title]
	
	
</style>