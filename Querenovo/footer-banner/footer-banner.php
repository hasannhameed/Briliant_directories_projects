<?php if($pars[0] == 'videos' ) { ?>
[widget=top_4_videos]
<section class="share-video-section">
    <div class="section-content">
        <div class="video-icon-red">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-video w-16 h-16 text-[#e10c1a] mx-auto mb-6"><path d="m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5"></path><rect x="2" y="6" width="14" height="12" rx="2"></rect></svg>
        </div>
        <h2 class="section-title">Vous avez une vidéo à partager ?</h2>
        <p class="section-description">Partagez vos tutoriels, témoignages ou innovations dans la rénovation énergétique</p>
        <a href="#" class="propose-video-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send w-5 h-5 mr-2"><path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path><path d="m21.854 2.147-10.94 10.939"></path></svg>
            Proposer une vidéo
        </a>
    </div>
</section>

<style>
	
.feature_results_header {
    margin-top: 50px;
    margin-left: 230px;
}
	
.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

.row {
    margin-right: -15px !important;
    margin-left: -15px !important;
}
	
@media (max-width: 1200px) {
  .feature_results_header {
    margin-left: 0 !important;
  }
	.container {
		padding-right: 0px !important;
		padding-left: 0px !important;
		margin-right: auto;
		margin-left: auto;
	}
	.row {
		margin-right: 0px !important;
		margin-left: 0px !important;
	}
}

	

.share-video-section {
    background: linear-gradient(135deg, #0e1d40 0%, #102c5b 100%);
    padding: 60px 20px; 
    text-align: center; 
    color: white; 
    font-family: Arial, sans-serif; 
}

.section-content {
    max-width: 800px; 
    margin: 0 auto; 
}
.grid_element{
	padding:0px;
}
	.padding{
		padding: 20px 14px;
	}

.video-icon-red {
    margin-bottom: 25px; 
}
.img_section{
		position: relative;
}
.channer_span {
    font-size: 12px;
    top: 5px;
    left: 5px;
    position: absolute;
    background-color: red;
    text: white;
    color: white;
    font-weight: bold !important;
    padding: 2px 9px;
    border-radius: 10px;
}
.video-icon-red svg{
    width: 100%;
    height: 65px;
}


.video-icon-red svg rect,
.video-icon-red svg path {
    stroke: #e53935; 
}

.section-title {
    font-size: 2.2em; 
    font-weight: bold;
    margin-bottom: 15px; 
    line-height: 1.2;
}

.section-description {
    font-size: 1.1em; 
    margin-bottom: 30px; 
    opacity: 0.8; 
    line-height: 1.5;
}

.propose-video-button {
    display: inline-flex; 
    align-items: center; 
    gap: 8px; 
    background-color: #e53935; 
    color: white; 
    text-decoration: none; 
    padding: 12px 25px; 
    border-radius: 5px; 
    font-size: 1em;
    transition: background-color 0.3s ease; 
}

.propose-video-button:hover {
    background-color: #c62828; 
}


.propose-video-button svg path {
    stroke: white; 
}


#first_container .container {
    width: 100% !important;
    max-width: 100vw !important;
}
.bg,.grid-container{
    background-color: #fef5ef;
}


.grid_element.well {
    max-width: 100%;
    min-height: 480px;
}
.inputdiv{
    background-color: #fff;
}
	
	
.video__play_link:after {
    background: #20202000;
    border-radius: 8px;
    color: #fff;
    cursor: pointer;
    Content: "" ; 
    font-family: FontAwesome;
    font-size: 24px;
    font-style: normal;
    font-weight: 400;
    left: 50%;
    margin-left: -27px;
    opacity: .95;
    padding: 12px 22px;
    border-radius: 100%;
    position: absolute;
    text-align: center;
    text-decoration: inherit;
    top: 37%;
    -webkit-transition: all 150ms ease-out 0;
    -moz-transition: all 150ms ease-out 0;
    -o-transition: all 150ms ease-out 0;
    transition: all 150ms ease-out 0;
   
    width: 60px;
    height: 60px;
   
	background-repeat: no-repeat;
    background-position: center;
    background-size: 50px;

    background-image: url("data:image/svg+xml;utf8,<svg width='32' height='32' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><rect x='3.5' y='6.5' width='13' height='11' rx='2' stroke='white' stroke-width='2'/><path d='M16.5 9L20 7V17L16.5 15V9Z' stroke='white' stroke-width='2' stroke-linejoin='round'/></svg>");

}
.img_section:hover .video__play_link:after{
        content: "▷";
        font-size: 37px;
         padding: 5px 18px;
    /* /background-image: none;  */
}
	
	.video__play_link svg {
    transition: opacity .2s ease-in-out;
}
	
	.search_result_image{
		height: 230px !important;
		width: 100% !important;
	}
@media (min-width: 992px) {
    .col-md-4 {
        width: 33.33333333% !important;
    }
}

@media (min-width: 1200px) {

    .custom_container{
            width: 1420px;
            max-width: 95vw;
            margin: 0 auto;
    }
}

@media (max-width: 768px) {
    .share-video-section {
        padding: 40px 15px;
    }

    .section-title {
        font-size: 1.8em;
    }

    .section-description {
        font-size: 1em;
    }

    .propose-video-button {
        padding: 10px 20px;
        font-size: 0.95em;
    }
}

@media (max-width: 480px) {
    .share-video-section {
        padding: 30px 10px;
    }

    .video-icon-red {
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 1.5em;
    }

    .section-description {
        font-size: 0.9em;
    }

    .propose-video-button {
        padding: 10px 18px;
        font-size: 0.9em;
    }
}
</style>

<?php } ?>