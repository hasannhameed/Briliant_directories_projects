<?php
global $subscription;
$schema_base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
$schema_collection_url = $schema_base_url . strtok($_SERVER['REQUEST_URI'], '?');


?>

<div class="grid-container">




<style>
	.member_results_header,p{
		text-align:justify!important;
	}
/* =========================
   BASE CARD + LAYOUT
   ========================= */
.bd-card {
  border: 2px solid hsl(0 0% 89.8%);
  border-radius: 10px !important;
  
  background: #fff;
  transition: 0.2s;
}

.bd-card:hover {
  box-shadow: 5px 5px 10px 2px #e5e7eb;
  border-radius:10px;
}

/* inner layout (BD .grid_element → your .grid-card) */
.grid-card {
  min-height: 270px;
  box-sizing: border-box;
  padding: 18px;
  position: relative;
  display: flex;
  /* gap: 16px; */
}

.sub_category_in_results {
  /* display: block !important; */
  text-align:justify;
  padding:4px 0px;
}

.card-name:hover{
	color:red;	
}

/* =========================
   BUTTON GROUP
   ========================= */
.grid-container.list-mode .grid-btns{
 /* margin-top: auto; */
    display: flex;
    
    /* gap: 12px; */
    flex-wrap: wrap;
    align-items: center;
    /* position: relative; */
    flex-direction: row;
    top: 220px;
    right: 280%;
    width: 100%;
    align-content: stretch;
}

.grid-container.list-mode .grid-btns .btn{
  
  /* padding: 10px 16px; */
  padding: 8px;
  height: 35px;
  border-radius: 8px;
  font-weight: 600;
}

/* primary */
.grid-container.list-mode .grid-btns .view-btn {
  background: #0f2133;
  border-color: #0f2133;
  color: #fff;
  width:49%;
}

/* secondary */
.grid-container.list-mode .grid-btns .contact-btn {
  background: #fff;
  border: 1px solid #e6e9ee;
  color: #0f2133;
  width:49%;
}

/* LIST VIEW – make phone button look like plain text */
.grid-container.list-mode .grid-card .info_section .show-btn .click-to-call-button,
.grid-container.list-mode .grid-card .info_section .show-btn .search_show_phone_button {
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
  border-radius: 0 !important;

  display: inline !important;
  width: auto !important;
  padding: 0 !important;
  margin: 0 !important;

  font-weight: 400 !important;
  font-size: 14px !important;
  color: #000 !important;
  text-align: left !important;
  cursor: pointer;
}

/* Hover – like text/link, not a button */
.grid-container.list-mode .grid-card .info_section .show-btn .click-to-call-button:hover,
.grid-container.list-mode .grid-card .info_section .show-btn .search_show_phone_button:hover {
  text-decoration: underline !important;
  background: transparent !important;
  border: none !important;
}

	
/* =========================
   IMAGE
   ========================= */

.grid-card .img_section img.search_result_image {
  width: 140px;
  height: 140px;
  border-radius: 8px;
  object-fit: cover;
  display: block;
   margin: 0 7.5px;
}

.grid-card .img_section {
  /* width: 150px;
  height: 140px; */
  /* border-radius: 8px;
  object-fit: cover;
  display: block;
   margin: 0 7.5px; */
}

/* =========================
   LOCATION + SHOW BUTTON (LIST VIEW)
   ========================= */
.grid-container.list-mode .location  {
  display: inline-block;
    float: right;
}

.grid-container.list-mode .grid-card .info_section .show-btn {
  position: absolute;
  left: calc(-12px + 18px); /* adjust if needed */
  top: -18px;
  transform: translateY(-50%);
  white-space: nowrap;
  z-index: 30;
  width: 15%;
}


	/* .grid-container.list-mode svg:not(:root) { overflow: hidden; position: absolute; margin-left: -30px !important; } */



/* =========================
   MOBILE RESPONSIVE
   ========================= */
@media (max-width: 792px) {
.grid-card .img_section img.search_result_image {
    width: 100%;
    height: 140px;
    border-radius: 8px;
    object-fit: cover;
    display: block;
    margin: 0 7.5px;
}


.views .listView .grid-container .grid-card {
        padding: 20px 18px !important; */
        /* display: block !important; */
        padding: 14px !important;
        min-height: auto !important;
        flex: 1 1 auto;
        /* display: flex !important; */
        flex-wrap: wrap;
        column-gap: 18px;
        min-width: min-content;
        min-height: 100%;
        margin-top: 0px !important;
        display: -webkit-inline-box;
    }
}
@media (max-width: 768px) {

  .grid-active .grid-btns .show-btn {
    order: 0;
    padding: 20px 0px;
    flex: 0 0 100%;
    width: 100% !important;
    margin: -30px 0px 0px -12px !important;
    text-align: left;
    box-sizing: border-box;
}

  .grid-card {
    flex-direction: column;
  }

  .grid-btns {
    gap: 10px;
    margin-top: 12px;
    justify-content: space-between;
  }

  .grid-btns .btn {
    flex: 1;
    min-width: 0;
  }

  .grid-card .info_section .show-btn {
    position: static;
    transform: none;
    margin-top: 10px;
  }

  .grid-container.is-grid > .bd-card {
    width: 100% !important;
    margin: 8px 0 !important;
  }

  .grid-container.is-grid .bd-card .grid-card .img_section {
    height: 180px !important;
    padding: 10px !important;
  }

  .grid-container.is-grid .bd-card .grid-card .grid-btns .btn {
    min-width: 48%;
    flex: 1;
  }
}


/************************************
   GRID VIEW – STACKED RIGHT CONTENT
************************************/


	
	.grid-active .bd-card:hover{
		box-shadow: 5px 5px 10px 2px #e5e7eb!important;
        border-radius:10px !important;
	}



/* kill bootstrap floats */
.grid-active .img_section,
.grid-active .mid_section,
.grid-active .info_section {
  float: none !important;
  width: auto !important;
  max-width: none !important;
  /* padding: 0 !important; */
}

/* LEFT: PHOTO */
.grid-active .img_section {
  flex: 0 0 150px;
}
.grid-active .img_section img {
  /* width: 100% !important; */
  height: 140px !important;
  object-fit: cover !important;
  border-radius: 10px !important;
}

/* RIGHT TOP: name + badge + category + desc + location (STACKED) */
.grid-active .mid_section {
  flex: 1 1 calc(100% - 170px);   /* right column, first row */
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 4px;
      justify-content: space-between;
}

/* 1) NAME */
.grid-active .mid_section .card-name {
 

  display: inline-block;
 
}

/* 2) VERIFIED BADGE as its own line */
.grid-active .mid_section .rmargin.inline-block {
}

/* 3) CATEGORY LINE */
.grid-active .mid_section .top_category_in_results {
  margin: 2px 0;
  font-size: 14px;
  color: #333;
}

/* --- 1) SHOW DESCRIPTION (2 lines + ...) IN GRID VIEW --- */
.grid-active .member-search-description {
  margin-top: 2px;
  color: #444;
  display: -webkit-box !important;   /* force visible, override BD */
  -webkit-line-clamp: 4;             /* only 2 lines */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  text-align: justify;

}
.member-search-description{
  cursor: pointer;
      margin-top: 2px;
    /* color: #444; */
    display: -webkit-box !important;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    text-align: justify;
}
/* if BD put <br> in description, hide them to keep clamp clean */
.grid-active .member-search-description br {
  display: none;
}



/* make sure the float grid still clears at the end */
.grid-active::after {
  content: "";
  display: block;
  clear: both;
}


/* 5) LOCATION (next line) */
.grid-active .mid_section .location {
  margin-top: 4px;
  /* margin-left: 6px !important; */
  display: inline-flex;
  align-items: center;
  gap: 6px;
  
}


/* two main buttons (override .btn-block full width) */
.grid-active .grid-btns .btn,
.grid-active .grid-btns .btn.btn-block {
  display: inline-flex !important;
  align-items: center;
  justify-content: center;
  padding: 12px 24px !important;
  border-radius: 8px !important;
  font-weight: 600 !important;
  font-size: 15px;
  white-space: nowrap;
  width: auto !important;          /* <-- no more full width */
}

/* remove BD clearfix between buttons */
.grid-active .grid-btns .clearfix {
  display: none !important;
}
/* right button – white outline with icon */
.grid-active .grid-btns .contact-btn {
  background: #fff !important;
  border: 2px solid #d4d9df !important;
  color: #0f2133 !important;
  
}
/* remove the SVG absolute hack only in grid */
.grid-active svg:not(:root) {
  position: static !important;
  margin-left: -6px !important;
}

/* responsive: 1 card per row on smaller screens */
@media (max-width: 1200px) {
  .grid-active .bd-card {
    width: 100% !important;
    float: none !important;
  }
  .grid-active .grid-card {
    flex-direction: column;
  }
  .grid-card .img_section img.search_result_image {
      width: 100%;
      height: 140px;
      border-radius: 8px;
      object-fit: cover;
      display: block;
      margin: 0 7.5px;
  }
  .grid-active .mid_section {
    flex: 1 1 calc(100% - 170px);
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
    justify-content: space-between;
            max-height: 240px;
}
    .grid-active .info_section {
      display: flex !important;
      flex-direction: column;
      align-items: flex-start;
      margin-top: 18px !important;
      margin-left: 0px !important;
      width: calc(100% - 170px);
  }
}
/* make the grid container a flexbox so all cards in a row share height */
.grid-active {
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;   /* stretch cards to same height in each row */
}

/* 2 cards per row, each card is a flex item */
.grid-active .bd-card {
  float: none !important;                     /* stop using floats in grid view */
  width: calc(50% - 24px) !important;
  margin: 0px 12px 12px 0px;

  background: #fff !important;
  border-radius: 16px !important;
  border: 1px solid #e6e9ee !important;
  box-shadow: 0 8px 20px rgba(0,0,0,.06) !important;
  padding:0px;
  display: flex;              /* card itself becomes flex to stretch inner */
}

.main-container .row {
  margin-right: -15px;
  margin-left: 0px !important;
}


/* inner content fills the card height */
.grid-active .grid-card {
  flex: 1 1 auto;
  display: flex !important;
  flex-wrap: wrap;            /* keep your existing row/column behavior */
  column-gap: 18px;
  min-width: min-content;
  
  min-height: 100%;           /* grow to full card height */
  margin-top:0px !important;
		
}

.grid-card .fpad {
    padding: 10px 0px;
}

/* ===== FINAL BUTTON FIX FOR GRID VIEW ===== */


/* BD wraps everything (alerts + buttons) in this div */
.grid-active .info_section .nomargin.fpad.text-center {
  display: block !important;
  width: 100%;
}

/* HARD RESET for BD's grid-btns hacks in grid view */
.grid-active .grid-btns,
.grid-active .grid-btns * {
  position: static !important;
  float: none !important;
}
	.grid-active .small .sub_category_in_results{
		text-align: left!important;
        margin-left: 10px !important;
	}

/* main button row – like reference screenshot */
.grid-active .grid-btns {
 
  flex-wrap: wrap;                 /* let phone go to next line */  
}
/* Both buttons visible, same height */
.grid-active .grid-btns .btn {
  display: inline-flex !important;
  align-items: center;
  justify-content: center;
  padding: 5px !important;
  border-radius: 8px !important;
  font-weight: 600 !important;
  font-size: 15px;
  white-space: nowrap;
}

/* Right: white outline with icon (Contacter) */
.grid-active .grid-btns .contact-btn {
  background: #fff !important;
  border: 2px solid #d4d9df !important;
  color: #0f2133 !important;
  gap: 8px;
   min-width:40% !important;
  font-size:10px !important;
}

/* Phone row – always full width under both buttons */
.grid-active .show-btn {
  position: static !important;
  margin-top: -30px !important;
  transform: none !important;
  width: 40% !important;
  text-align: center;
}	



/* White button appears to the right of blue */
.grid-active .grid-btns .contact-btn {
    font-size:15px;
    flex:0 0 auto;                /* No auto width stretching */
}

/* Phone section moves BELOW them — full width */
.grid-active .show-btn {
    flex:0 0 100%;
    text-align:center;
    margin-top:14px !important;
}

/* Remove BD width forcing */
.grid-active .btn.btn-block {
    width:auto !important;
}
	
	/* =========================================
   ALIGN BUTTON BLOCK UNDER CONTENT COLUMN
   ========================================= */

/* shift the whole right block (buttons + phone)
   so it starts under the text, not under the image */
.grid-active .info_section {
  display: flex !important;
  flex-direction: column;
  align-items: flex-start;
  margin-top: 18px !important;
  margin-left: 175px;                 /* start at content column */
  width: calc(100% - 170px);          /* use only content width */
}



/* shape of the two main buttons */
.grid-active .grid-btns .btn {
  padding: 12px 24px !important;
  border-radius: 8px !important;
  font-weight: 600 !important;
  font-size: 15px;
  white-space: nowrap;
}

/* phone button: full width but only under content column */
.grid-active .show-btn {
  width: 100% !important;            /* 100% of info_section (content width) */
  margin-top: 14px !important;
  text-align: center;
}
/* =========================
   FINAL GRID BUTTON LAYOUT
   ========================= */
/* 2) Base button size – smaller than now */
.grid-active .grid-btns .btn,
.grid-active .grid-btns .btn.btn-block {
  flex: 0 0 auto;
  padding: 8px 20px !important;      /* smaller buttons */
  font-size: 10px !important;
  border-radius: 8px !important;
  font-weight: 600 !important;
  white-space: nowrap;
  width: auto !important;            /* kill any 100% width */
  min-width: 0 !important;           /* kill old min-width:40% */
  box-sizing: border-box;
}

/* Blue button (left) */
.grid-active .grid-btns .view-btn {
  background: #0f2133 !important;
  border: 2px solid #0f2133 !important;
  color: #fff !important;
  max-width: 48%; /* never larger than half row */
  margin-right:26px;
  margin-left:-6px;
 
}

/* White button (right) */
.grid-active .grid-btns .contact-btn {
  background: #fff !important;
  border: 2px solid #d4d9df !important;
  color: #0f2133 !important;
  display: inline-flex;
  align-items: center;
 
  max-width: 48%;                    /* sit nicely next to blue */
}

/* 3) Phone button – full width of content, stay inside card */
.grid-active .show-btn {
  margin-top: 14px !important;
  width: 100% !important;            /* 100% of info_section width */
  box-sizing: border-box;
  text-align: center;
}
/* ==========================================
   FINAL GRID BUTTON LAYOUT
========================================== */

.grid-active .grid-btns {
  display: flex !important;
  flex-wrap: wrap !important;          /* allow multiple rows */
  align-items: center;
  justify-content: center;
  flex-direction: row;
  width: 100% !important;
  margin-top: -12px !important;
}

/* PHONE BUTTON – FIRST ROW, FULL WIDTH */
.grid-active .grid-btns .show-btn {
  order: 0;
  padding: 20px 0px;
  flex: 0 0 100%;
  width: 100% !important;
  margin: -42px 0px 0px 5px;
  text-align: left;
  box-sizing: border-box;
 
}

/* Make phone "button" look like plain text ONLY in grid view */
.grid-active .grid-btns .show-btn .click-to-call-button,
.grid-active .grid-btns .show-btn .search_show_phone_button {
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
  border-radius: 0 !important;

  display: inline !important;
  width: auto !important;
  padding: 0 !important;
  margin: 0 !important;

  font-weight: 400 !important;
  font-size: 14px !important;
  color: #000 !important;
  text-align: left !important;
  cursor: pointer;
}

/* Hover – like text/link, not a button */
.grid-active .grid-btns .show-btn .click-to-call-button:hover,
.grid-active .grid-btns .show-btn .search_show_phone_button:hover {
  text-decoration: underline !important;
  background: transparent !important;
  border: none !important;
}

/* BLUE + WHITE CTA BUTTONS – SECOND ROW, SIDE BY SIDE */
.grid-active .grid-btns .view-btn,
.grid-active .grid-btns .contact-btn {
  order: 1;
  flex: 0 0 auto;
  width: auto !important;             /* no 100% / 40% widths */
  min-width: 0 !important;
  display: inline-flex !important;
  align-items: center;
  justify-content: center;
  padding: 5px 15px !important;    /* smaller size */
  font-size: 14px !important;
  border-radius: 8px !important;
  font-weight: 600 !important;
  white-space: nowrap;
  
}

/* override any Bootstrap .btn-block width */
.grid-active .grid-btns .btn.btn-block {
  width: auto !important;
  display: inline-flex !important;
  gap: 5px!important;
  
}
	.grid-active .member-search-verified{
		margin-left:6px;
	}

	
	
	
/*responsiveness*/


/* =========================
   FINAL OVERRIDES – BD + RESPONSIVE
   ========================= */

/* MOBILE: ≤ 768px
   - only GRID view (your design)
   - 1 card per row
*/
@media (max-width: 768px) {

  /* kill any flex grid / BD grid */
  .grid-container.grid-active,
  .grid-container.is-grid {
    display: block !important;
  }

  /* one card per row */
  .grid-container.grid-active .bd-card,
  .grid-container.is-grid .bd-card {
    width: 100% !important;
    margin: 10px 0 !important;
    float: none !important;
    height: auto !important;
  }

  /* stack inside card: image → content → buttons */
  .grid-container.grid-active .grid-card,
  .grid-container.is-grid .grid-card {
    display: block !important;
    padding: 14px !important;
   
    min-height: auto !important;
  }

  .grid-container.grid-active .img_section,
  .grid-container.grid-active .mid_section,
  .grid-container.grid-active .info_section,
  .grid-container.is-grid .img_section,
  .grid-container.is-grid .mid_section,
  .grid-container.is-grid .info_section {
    width: 100% !important;
    margin: 0 !important;
    float: none !important;
  }

  .grid-container.grid-active .img_section img,
  .grid-container.is-grid .img_section img {
    height: 180px !important;
    object-fit: cover !important;
    width: 100%;
  }

  /* location under description */
  .grid-container.grid-active .mid_section .location,
  .grid-container.list-mode .location {
    margin-left: 0 !important;
    margin-top: 6px !important;
  }

  /* buttons full-width row */
  .grid-container.grid-active .grid-btns,
  .grid-container.list-mode .grid-btns {
    position: static !important;
    top: auto !important;
    right: auto !important;
    display: flex !important;
    flex-wrap: wrap;
    gap: 8px;
   
    width: 100% !important;
  }

  .grid-container.grid-active .grid-btns .btn,
  .grid-container.list-mode .grid-btns .btn {
    flex: 1 1 48%;
    padding: 8px 10px !important;
    font-size: 13px !important;
  }

  /* phone row – full width below buttons */
  .grid-container.grid-active .show-btn,
  .grid-container.list-mode .show-btn {
    width: 100% !important;
   
    text-align: left !important;
  }

  /* font sizes smaller */
  .grid-container .card-name {
    font-size: 16px !important;
  }
  .grid-container .member-search-description,
  .grid-container .sub_category_in_results {
    font-size: 13px !important;
  }

  /* hide BD view toggles – only grid on mobile */
  .views .listView,
  .views .gridView {
    /* display: none !important; */
  }
}

/* TABLET: 769px – 1024px
   - list & grid buttons visible
   - grid = 2 cards per row
   - list = single column, your list design
*/
@media (min-width: 769px) and (max-width: 1024px) {


  /* GRID: 2 per row, but don’t cut content */
  .grid-container.grid-active .bd-card,
  .grid-container.is-grid .bd-card {
    width: calc(50% - 20px) !important;
    margin: 10px !important;
    float: none !important;
	
  }

  .grid-container.grid-active .info_section,
  .grid-container.is-grid .info_section {
    margin-left: 0 !important;
    width: 100% !important;
  }

  .grid-container.grid-active .grid-btns .btn,
  .grid-container.is-grid .grid-btns .btn {
    padding: 8px 16px !important;
    font-size: 11px !important;
	  margin-right:0;
  }

  /* LIST: keep your list look but remove huge offset that breaks small width */
  .grid-container.list-mode .grid-btns {
    position: static !important;
    top: auto !important;
    right: auto !important;
    margin-top: 16px !important;
  }

  .grid-container.list-mode .location {
    margin-left: 40% !important;
  }
	
}



@media (max-width: 768px) {

  /* more breathing space between text & buttons */
  .grid-container .grid-card {
    padding: 18px 16px !important;
  }

  .grid-container .mid_section {
    margin-bottom: 10px !important;
  }

  .grid-container .mid_section .location {
    margin-left: 0 !important;
    margin-top: 10px !important;
  }

  /* phone line: block + space before buttons */
  .grid-container .info_section .show-btn {
    position: static !important;
    transform: none !important;
    display: block !important;
    width: 100% !important;
    /* margin-top: 10px !important;
    margin-bottom: 12px !important; */
    text-align: left !important;
  }

  /* button group: vertical, full width */
  .grid-container .grid-btns {
    position: static !important;
    top: auto !important;
    right: auto !important;

    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;
    gap: 10px;
    width: 100% !important;
    margin-top: 4px !important;
  }

  .grid-container .grid-btns .btn {
    flex: 0 0 auto !important;
    width: 100% !important;
    max-width: 100% !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    justify-content: center !important;
  }
}


@media (min-width: 769px) and (max-width: 1024px) {

  .grid-container .bd-card {
    height: auto !important;
  }

  .grid-container .grid-card {
      /* padding: 20px 18px !important; */
      /* display: block !important; */
      padding: 14px !important;
      min-height: auto !important;
      flex: 1 1 auto;
      display: flex !important;
      flex-wrap: wrap;
      column-gap: 18px;
      min-width: min-content;
      min-height: 100%;
      margin-top: 0px !important;
  }

  .grid-container .mid_section {
    margin-bottom: 14px !important;
  }

  /* list-mode location alignment a bit closer to text */
  .grid-container.list-mode .location {
    margin-left: 0 !important;
    margin-top: 10px !important;
  }

  /* phone line above buttons with space */
  .grid-container .info_section .show-btn {
    position: static !important;
    transform: none !important;
    display: block !important;
    width: 100% !important;
    margin-top: 10px !important;
    margin-bottom: 14px !important;
    text-align: left !important;
  }

  /* buttons stacked, same width */
  .grid-container .grid-btns {
    position: static !important;
    top: auto !important;
    right: auto !important;

    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 10px;
    width: 260px !important;     /* adjust if you want wider/narrower */
    margin-top: 8px !important;
  }

  .grid-container .grid-btns .btn {
    width: 100% !important;
    max-width: 100% !important;
    padding: 10px 16px !important;
    font-size: 14px !important;
    justify-content: center !important;
  }
}

/* =========================================
   FINAL BUTTON ROW FIX – MOBILE & TABLET
   ========================================= */

/* MOBILE: buttons in one row, 50/50 */
@media (max-width: 768px) {
  .grid-container .grid-btns {
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between !important;
    align-items: stretch !important;
    gap: 8px;
    width: 100% !important;
    margin-top: 8px !important;
  }

  .grid-container .grid-btns .btn {
    flex: 1 1 48% !important;
    max-width: 48% !important;
    width: auto !important;
    padding: 10px 8px !important;
    font-size: 14px !important;
    justify-content: center !important;
	margin-right: 0;
  }
}

/* TABLET: buttons in one row, centered and a bit wider */
@media (min-width: 769px) and (max-width: 1024px) {
  .grid-container .grid-btns {
    display: flex !important;
    flex-direction: row !important;
    justify-content: flex-start !important;
    align-items: center !important;
    gap: 12px;
    width: 100% !important;
    margin-top: -60px !important;
	margin-right: 0;
  }

  .grid-container .grid-btns .btn {
    flex: 0 0 46% !important;      /* two buttons in one row */
    max-width: 46% !important;
    width: auto !important;
    padding: 10px 14px !important;
    font-size: 11px !important;
    justify-content: center !important;
  }
}

/* =========================
   LIST VIEW – TABLET (≈1024px)
   Fix CTA layout: 2 buttons side by side
   ========================= */
@media (min-width: 769px) and (max-width: 1024px) {

  /* keep right column normal width */
  .grid-container.list-mode .info_section {
    float: none !important;
    width: auto !important;
    position: static !important;
  }

  /* phone line above buttons, full width of right column */
  .grid-container.list-mode .info_section .show-btn {
    position: static !important;
    transform: none !important;
    display: block !important;
    width: 260px !important;          /* adjust if you want wider */
    margin: 10px 0 12px 0 !important;
    text-align: left !important;
  }

  /* CTA wrapper – one row */
  .grid-container.list-mode .grid-btns {
    position: static !important;
    top: auto !important;
    right: auto !important;

    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 8px !important;

    width: 260px !important;          /* same width as phone line */
    margin-top: 0 !important;
  }

  /* two buttons share the row */
  .grid-container.list-mode .grid-btns .btn,
  .grid-container.list-mode .grid-btns .btn.btn-block {
    flex: 1 1 0 !important;
    min-width: 120px !important;      /* prevents vertical text */
    max-width: none !important;
    width: auto !important;

    padding: 10px 12px !important;
    font-size: 13px !important;
    white-space: nowrap;
    text-align: center;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
  }

  /* keep your colors */
  .grid-container.list-mode .grid-btns .view-btn {
    background: #0d1b3e !important;
    border: 2px solid #0d1b3e !important;
    color: #fff !important;
  }

  .grid-container.list-mode .grid-btns .contact-btn {
    background: #fff !important;
    border: 1px solid #e6e9ee !important;
    color: #0f2133 !important;
  }
}


/* =========================================
   SPECIAL FIX – 1024px LIST VIEW ONLY
   ========================================= */
@media (width: 1024px) {

  /* phone row: full width above CTAs */
  .grid-container.list-mode .info_section .show-btn {
    position: static !important;
    transform: none !important;
    display: block !important;
    width: 100% !important;
    margin: 8px 0 12px 0 !important;
    text-align: left !important;
  }

  /* CTA row container */
  .grid-container.list-mode .grid-btns {
    position: static !important;
    top: auto !important;
    right: auto !important;

    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 12px;

    width: 100% !important;
    margin-top: 6px !important;
  }

  /* reset BD widths */
  .grid-container.list-mode .grid-btns .btn,
  .grid-container.list-mode .grid-btns .btn.btn-block {
    width: auto !important;
    max-width: none !important;
    min-width: 0 !important;
    flex: 0 0 auto !important;

    display: inline-flex !important;
    align-items: center;
    justify-content: center;

    padding: 10px 18px !important;
    font-size: 13px !important;
    white-space: nowrap !important;
    box-sizing: border-box;
  }

  /* blue button wider, white a bit smaller – same row */
  .grid-container.list-mode .grid-btns .view-btn {
    flex-basis: 60% !important;
  }

  .grid-container.list-mode .grid-btns .contact-btn {
    flex-basis: 35% !important;
  }
}

.verifiaction .member-search-verified {
    display: inline-flex;
    align-items: center;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 11px;
    line-height: 0.2em;
    background-color: white;
    border: 1px solid transparent;
}
.member-search-verified-icon {
    font-size: 14px;
    margin-right: 5px;
    color: red;
}
.bi::before, [class*=" bi-"]::before, [class^=bi-]::before {
    color: red;
    display: inline-block;
    font-family: bootstrap-icons !important;
    font-style: normal;
    font-weight: 400 !important;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    vertical-align: -.125em;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.verifiaction{
  border: 1px solid #d1d2d4;
  border-radius: 5px;
  margin-right: 10px;
}
.topcategory {
    border: 1px solid #d1d2d4;
    border-radius: 5px;
    align-items: center;
    padding: 4px 10px;
    line-height: 0.2em;
    background-color: white;
    display: inline-flex;
}
.small, small {
    font-size: 100%;
}

.top_category_in_results:has(.topcategory) {
  width: auto;
  float: right;
}
.verifiactionparent{
  display: flex;
  flex-direction: row;
}
</style>