<?php
global $subscription;
$schema_base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
$schema_collection_url = $schema_base_url . strtok($_SERVER['REQUEST_URI'], '?');
?>
<style>
    /* ======================
       GLOBAL CONTAINER
    ====================== */
    .grid_element_custom {
        background-color: transparent; /* Let individual cards handle bg */
    }
    .grid-container {
        margin-bottom: 30px;
    }

    /* ======================
       THE CARD (Modern Wrapper)
    ====================== */
    .member_results .row-fluid {
        background: #ffffff;
        border: 1px solid #f1f5f9; /* Very subtle border */
        border-radius: 16px; /* Smooth rounded corners */
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); /* Soft shadow */
        margin-bottom: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    /* Hover Effect for Card */
    .member_results .row-fluid:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: #e2e8f0;
    }

    /* ======================
       IMAGE SECTION (Left Side)
    ====================== */
    .member_results .img_section {
        width: 140px;
        height: 140px;
        flex-shrink: 0; /* Prevent shrinking */
        border-radius: 12px;
        overflow: hidden;
        background-color: #f8fafc;
    }

    .member_results .img_section img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
    }

    /* ======================
       CONTENT SECTION (Right Side)
    ====================== */
    /* Wrapper for text content */
    .member_results .mid_section {
        flex: 1; /* Take remaining width */
        min-width: 0; /* Fix flexbox overflow issue */
    }

    /* Header Row (Name + Rating) */
    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .member_results .member-search-full-name {
        font-family: 'Segoe UI', system-ui, sans-serif;
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #0f172a; /* Dark Navy */
        margin: 0;
        line-height: 1.3;
    }

    .member_results .member-search-reviews {
        font-size: 14px !important;
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
        white-space: nowrap;
        background: #fffbeb; /* Light yellow background */
        padding: 4px 8px;
        border-radius: 6px;
    }
    
</style>
<style>


	.grid_element_custom{
		background-color:white;
	}
	/* ======================
   CARD WRAPPER
====================== */
.member_results .row-fluid{
    background: #fff;
    border: 1px solid #e6e6e6;
    border-radius: 14px;
    padding: 22px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
}
.member_results .grid {
    background: transparent;
    border: none;
    border-radius: 11px;
    box-shadow: none;
        width: 50%;
}
.member_results .grid .grid_element{
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
    padding: 22px;
    min-height: 280px;
    max-height: 280px;
}

.grid .well {
    margin-bottom: 10px;
    padding: 20px;
}
/* ======================
   IMAGE COLUMN
====================== */

.member_results .img_section {
    width: 115px;
    height: 140px;
    overflow: hidden;
    border-radius: 10px;
    text-align: center;
    float: left;
}
.member_results .img_section img {
    width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 10px;
    margin-left: 0px;
}

.row-fluid .col-xs-2 {
    width: 115px;
    height: 120px;
}
/* ======================
   TITLE + RATING
====================== */
.member_results .mid_section {
    position: relative;
}

.member_results .member-search-full-name {
    font-size: 20px !important;
    font-weight: 700 !important;
    display: inline-block;
    color: #0f2133;
}

.member_results .member-search-reviews {
    position: absolute;
    right: 0;
    top: 0;
    font-size: 16px !important;
    color: #ffb300 !important; /* star color */
}

/* ======================
   DESCRIPTION & TEXT
====================== */
.member_results .member-search-description {
    font-size: 14px;
    line-height: 1.45em;
    color: #444;
    max-width: 90%;
}

/* ======================
   ICON ROWS (phone, category etc.)
====================== */
.member_results small,
.member_results .top_category_in_results,
.member_results .sub_category_in_results {
    font-size: 13px !important;
    color: #555 !important;
}

.member_results .fa {
    color: #555 !important;
}

/* ======================
   LOCATION LINE
====================== */
.member_results .member-search-location small {
    font-size: 13px !important;
    color: #444 !important;
}
.member_results .fa-map-marker {
    color: #0f2133 !important;
}

/* ======================
   CTA BUTTONS
====================== */
.member_results .module .btn {
    border-radius: 8px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
}

.member_results .search_view_listing_button {
    background: #0f2133 !important;
    border-color: #0f2133 !important;
    color: #fff !important;
}

.member_results .search_contact_now_button {
    background: #fff !important;
    border: 1px solid #d6d6d6 !important;
    color: #0f2133 !important;
}

/* space between buttons */
.member_results .module .btn + .btn {
    margin-top: 8px;
}
.search_show_phone_button{
    border: none;
    text-align: justify;
    padding: 12px 0px;
    
}
.search_show_phone_button:hover{
    border: none;
    text-align: justify;
    background-color: white !important;
}
.mybuttons {
    border: none;
}
.grid .mybuttons{
    width: 75% !important;
    float: right !important;
    padding-left: 15px !important;
}
.profilebtn a{
    width: 95%;
}
.member_results .member-search-description {
    font-size: 14px;
    line-height: 1.45em;
    color: #444;
    max-width: 100%;
    margin-top: 0px !important;
}
.grid .member-search-description{
    display: block !important;
}
.grid .mid_section{
    width: 75%;
}

.grid .img_section{
    height: 120px;
}
.grid .img_section img {
    height: 100%;
}
.body-content{
    display: none;
}
.ex-col-8 {
   width: 66.66666667%;
}
.ex-col-2{
    width: 25%;
}
.grid .content_adjust{
    display: -webkit-box !important;
    -webkit-line-clamp: 1;  
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
.content_adjust1{
    display: -webkit-box !important;
    -webkit-line-clamp: 1;  
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
.grid .member-search-location small{
    display: -webkit-box !important;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.grid .member-search-location{
    display: inline-flex;
    justify-content: space-between;
    align-items: center;
}
.grid .top_category_in_results,.sub_category_in_results{
    display: block !important;
}    
.call-btn .clearfix{
    display: none;
}
.mid_section .clearfix{
display: none;
}
.member-search-verified {
    display: inline-flex;
    align-items: center;
    border-radius: 64px;
    padding: 5px 8px;
    font-size: 11px;
    line-height: 1.2em;
}
.alert-success-subtle, .label-success-subtle, .badge-success-subtle {
    background-color: #ffffff;
    border: 1px solid #0f21332b;
    /* border-color: #0f2133; */
    color: rgb(39, 60, 117);
    color: 
 color-mix(in srgb, rgb(39, 60, 117) 50%, rgb(34, 34, 34) 50%);
}
.row-fluid:hover{
    box-shadow: 5px 5px 10px 2px #e5e7eb;
    border-radius:10px;
}
.grid .grid_element:hover{
    box-shadow: 5px 5px 10px 2px #e5e7eb;
    border-radius:10px;
}
.grid-container{
        margin-bottom: 20px;
}
.category-group:not(.category-group.closed-mode-cat-group):not(.category-group .category-group) {
    background: rgb(255 255 255) !important;
}
.checkbox-name-filter-category {
    width: calc(100% - 20px);
    font-weight: 400;
    display: -webkit-box !important;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-left: 5px;
}
.sub-cat-checkbox-container .sub-sub{
    margin-bottom: 20px;
}
.sub-cat-checkbox-container.sub-sub label {
    padding-left: 25px;
    display: flex;
    padding-left: 0px;
    padding-bottom: 5px;
}
@media (min-width: 768px) {
    .info_section .col-sm-10 {
        width:87.5555% !important;
        float: right ;
         padding: 0px 0px 0px 10px;
    }
    .mid_section{
        width:87.5555% !important;
        float: right ;
        padding-left: 20px !important;
    }
    .grid .info_section .col-sm-10 {
        width:73% !important;
        float: right ;
         padding: 0px 0px 0px 10px;
    }
    .grid .mid_section{
        width:73% !important;
        float: right ;
        padding-left: 20px !important;
    }
}

/* ======================
   RESPONSIVE
====================== */
@media (max-width: 768px) {
     .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
        position: unset;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
    .profilebtn a{
        width: 100%;
        margin-bottom: 10px;
    }
    .member_results .grid .grid_element{
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
        min-height: 400px;
        max-height: 280px;
    }
    .grid .btn-outline {
        -webkit-backdrop-filter: blur(5px);
        backdrop-filter: blur(5px);
        display: block;
        -webkit-backdrop-filter: inherit;
        backdrop-filter: initial;
    }
}

@media (max-width: 768px) {

    .member_results {
        padding: 15px;
    }

    .row-fluid .col-xs-2{
        width: 100%;
        height: 100%;
    }

    .member_results .img_section {
        width: 100% !important;
        text-align: center;
        margin-bottom: 15px;
    }

    .member_results .img_section img {
        margin-left: 0px;
        width: 100%;
        max-width: 200px;
    }

    .member_results .member-search-reviews {
        position: static;
        float: right;
        margin-top: -20px;
    }

}

</style>

<div class="grid-container">