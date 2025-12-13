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
} 

//print_r($_ENV['sqlquery']);

?>

<style>
	.job_category_parent{
		display:flex;
		flex-direction:column;
		align-items: flex-start;
	}
	.green-text{
		color:green;
	}
	.img_section {
		display: flex;
		justify-content: space-between;
		flex-direction: row;
		border-radius:0px !important;
	}
	.img-div {
		width: 64px;
		height: 62px;
	}
	.dollor {
		padding: 0px 2px;
		background-color: #ffb900;
		border-radius: 100%;
		color: #5a8000;
		border: 1px solid green;
		margin: 0px 5px 0px 1px;
		font-size: 12px !important;
	}
	.fr-view img {
		position: relative;
		width: auto;
		height: 100%;
		max-height: 100%;
		max-width: 100%;
		border-radius: 10px;
	}
	.custom_badge{
		border: 1px solid #77777761;
		padding: 1px 5px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: bold;
		margin-right: 5px;
	}
	.btn-block+.btn-block{
		margin-top: 0px !important;
	}
	.contact-member,.green-text,.job_category_parent{
		font-weight:'';
	}
	.post-search-result-count-container{
		display:block !important;
	}
	.description{
		font-weight: 200;
        font-size: 15px;
	}
	.h3{
		    float: left !important;
        text-align: left;
	}
.textwrapper * {
    font-weight: 200;
    font-size: 15px;
}
	.description a{
		font-weight: 200;
        font-size: 15px;
	}
	.custom_badge_urgent{
		padding: 2px 14px;
	}
	.btndiv1{
		padding-right: 4px;
	}
	.btndiv2{
		padding-left: 4px;
	}
	.btndiv1 button{
		padding: 5px 10px;
		background-color: white;
		border: 1px solid #00000026;
		border-radius: 5px;
	}
	.btndiv2 button{
		padding: 5px 10px;
		border: 1px solid #00000026;
		border-radius: 5px;
	}



</style>

<div class="grid-container">