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
<style>
    /* ============================
       1. MAIN CARD CONTAINER
    ============================ */
    /* Target the container that holds everything */
    .search_result.row-fluid {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0 !important; /* Let inner grid_element handle padding */
        overflow: hidden; /* Contains floats if any remain */
    }

    /* Hover Effects */
    .search_result.row-fluid:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }

    /* ============================
       2. FORCE FLEX LAYOUT
    ============================ */
    /* This overrides the bootstrap columns layout */
    .search_result .grid_element {
        display: flex !important;
        flex-wrap: nowrap !important;
        align-items: stretch;
        position: relative;
        padding: 20px;
        width: 100%;
    }

    /* Hide the clearfix divs that break flexbox */
    .search_result .grid_element .clearfix,
    .search_result .grid_element hr {
        display: none !important;
    }

    /* Position the favorite button absolutely so it doesn't break flow */
    .search_result .favorite_button {
        position: absolute !important;
        top: 15px;
        right: 15px;
        width: auto !important;
        height: auto !important;
        z-index: 10;
        padding: 0 !important;
        margin: 0 !important;
        float: none !important;
    }

    /* ============================
       3. IMAGE SECTION (Left)
    ============================ */
    /* Override Bootstrap col-sm-3 */
    .search_result .img_section {
        width: 200px !important;
        min-width: 200px !important;
        flex-basis: 200px !important;
        height: auto;
        min-height: 150px;
        margin: 0 10px 0 0 !important; /* Right margin only */
        padding: 0 !important;
        float: none !important;
        border-radius: 8px;
        flex-shrink: 0; /* Prevent shrinking */
    }

    /* Ensure image fills the box */
    .search_result .img_section img,
    .search_result .img_section .alert-secondary {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        border-radius: 8px;
        border: none !important;
    }

    /* ============================
       4. CONTENT SECTION (Right)
    ============================ */
    /* Override Bootstrap col-sm-9 */
    .search_result .mid_section {
        flex-grow: 1 !important;
        width: auto !important;
        float: none !important;
        padding: 0 !important;
        display: flex;
        display: flex;
		flex-direction: row;
		align-items: center;
		 justify-content: space-around;
    }

    /* Title Styling */
    .search_result .mid_section a.h3, 
    .search_result .mid_section h3 {
        color: #0f2133 !important; /* Navy Blue */
        font-size: 18px !important;
        font-weight: 700 !important;
        margin-top: 0;
        margin-bottom: 8px;
        display: block;
        text-decoration: none;
    }

    /* Description Text */
    .search_result .mid_section p {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 12px;
        line-height: 1.5;
    }

    /* Location Icon */
    .post-location-snippet {
        display: flex;
        align-items: center;
        color: #475569;
        font-size: 13px;
        margin-bottom: 10px;
    }
    .post-location-snippet i {
        color: #0f2133 !important;
        margin-right: 6px;
    }

    /* ============================
       5. BUTTON STYLING (Bottom)
    ============================ */
    /* Target the button container inside mid_section */
    .search_result .mid_section .row-fluid.bpad {
        margin-top: auto !important; /* Push to bottom */
        padding: 0 !important;
        display: flex;
        justify-content: flex-end; /* Align right */
        width: 100%;
    }

    /* Style the button */
    .search_result .btn.view-details,
    .search_result .btn-success {
        background-color: #0f2133 !important;
        color: #fff !important;
        border: 1px solid #0f2133 !important;
        border-radius: 6px !important;
        padding: 8px 20px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        text-transform: none !important;
        float: none !important;
        width: auto !important;
        min-width: 120px;
    }

    .search_result .btn.view-details:hover,
    .search_result .btn-success:hover {
        background-color: #1e293b !important;
        transform: translateY(-1px);
    }

    /* Category Label Styling (if present) */
    .post-search-category {
        margin-right: auto; /* Push to left */
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
    }
    .post-search-category a {
        color: #0f2133 !important;
        font-weight: 600;
    }
	.job-parent .views{
		display: none !important;
	}

	.img_section img {
		max-height: 175px;
		max-width: 100%;
		border: 1px solid #0f2133 !important;
		padding: 10px;
        background: white;
	}

	.img_section .alert-secondary {
		border: 1px solid #0f2133 !important;
	}
	.post-location-snippet{
		display: none;
	}
	.col-md-3:has(.module2){
		width: 20%;
        float: right;
	}
	.feature_results_header{
		margin-right: 45px;
		padding-left: 30px;
		margin-left: 70px;
		margin-bottom: 35px;
	}
    /* ============================
       6. MOBILE RESPONSIVE
    ============================ */
     @media (max-width: 991px) {
        .col-md-3:has(.module2){
            width: 100% !important;
        }
     }
    @media (max-width: 768px) {
        .search_result .grid_element {
            flex-direction: column !important;
        }
        
        .search_result .img_section {
            width: 100% !important;
            height: 200px !important;
            margin-right: 0 !important;
            margin-bottom: 20px !important;
        }

        .img_section img {
            max-height: 100%;
            max-width: 100%;
            border: 1px solid #0f2133 !important;
            padding: 10px;
            background: white;
        }

        .search_result .mid_section .row-fluid.bpad {
            margin-top: 15px !important;
        }
        
        .search_result .btn.view-details {
            width: 100% !important;
        }
        .feature_results_header{
            margin-left: 0px !important;
        }
    }
</style>
<?php 

//print_r($_ENV['sqlquery']);

?>
<div class="grid-container">