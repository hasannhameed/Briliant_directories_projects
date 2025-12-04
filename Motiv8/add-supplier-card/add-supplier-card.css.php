<?php if($pars['1'] == "edit-supplier-card"){ ?>
.nav-tabs {
    border-bottom:none!important;
}
#goback a{
	padding-top:0;
}
	<?php } ?>

.invalid-feedback{
	display:none;
	background: rgb(247, 61, 81);
    color: rgb(255, 255, 255) !important;
	border-radius: 2px;
	padding: 3px 10px;
	width: auto;
}

.company-thum * {
	font-family: Helvetica !important;
}
.above-content-banner-ad {
	display: none;
}
.thumbnail > div >img {
    height: 75px !important;
    width: auto;
    padding: 8px 0;
	display: flex;
	margin: 0 auto;
}
.buttons-container .row .col-xs-6:first-child {
    padding-right: 7.5px;
}
.buttons-container .row .col-xs-6:last-child {
    padding-left: 7.5px;
}
.company_desc {
	display: flex;
    justify-content: center;
    align-items: center;
    /*height: 94px;*/
	min-height:94px;
	word-break:break-word;
	/*word-break:break-all;*/
}
/*.company_desc {
	
   
    text-align: center;
    height: 94px;
}*/
.up-next-title {
	font-weight: bold;
}
.thumbnail {
	border: 3px solid #666;
	margin-bottom: 0;
}
.company_logo {
	width: 100%;
}

.company-thum .caption{
	padding: 0 15px 15px;
	
}
.grid-row {
	display: flex;
	flex-wrap: wrap;
}
.exhibitor-container .col-sm-6 {
    display: flex;
	margin-bottom: 15px;
	margin-top: 15px;
}
.live_tag {
    padding: 6px 10px;
    vertical-align: middle;
    color: white;
    font-size: 16px;
    position: absolute;
    right: 47px;
    top: 105px;
    border-radius: 5px;
	z-index: 9;
	cursor: default;
}
.live-color {
	background: #d92626;
}
.rewatch-color {
	background: #007bff;
}
.live-soon-color {
	background: #ffc107;
}
.company-thumb-img img {
	width: 100%;
}
.company-thumb-img iframe {
	margin-bottom: 21px;
}
.company-thumb-img {
	width: 100%;
    height: 218px;
    overflow: hidden;
    min-height: 218px;
    max-height: 218px;
	padding: 0 15px;
	display: flex;
	align-items: center;
}

.company-thum.thumbnail {
	display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.buttons-container {
	margin-left: 12px;
	margin-right: 12px;
	margin-bottom: 15px;
}

.presenting-ribbon {
	display: block;
    float: right;
    width: 115px;
    background: #666;
    color: #fff;
    text-align: center;
    margin-top: -5px;
    margin-left: calc(100% - 109px);
    right: 0;
	font-size: 12px;
	z-index: 99;
}
@media (max-width: 769px) {
	.link-video img, iframe, .company-single-embed{
		height: 300px !important;
		min-height: 300px !important;
    	max-height: 300px !important;
	}
}

.addeventatc , .addeventatc:hover{
	font-size: 13px;
	font-weight: 300;
	padding: 10px 10px 10px 33px;
}

.addeventatc .addeventatc_icon {
    width: 19px;
    height: 18px;
    position: absolute;
    z-index: 1;
    left: 10px;
    top: 8px;
    background: url(https://www.addevent.com/gfx/icon-calendar-t1.svg) no-repeat;
    background-size: 15px 15px;
}
@media (max-width: 1199px) AND (min-width: 992px) {
	.addeventatc , .addeventatc:hover{
		padding: 10px 7px;
	}
	.addeventatc .addeventatc_icon {
		display: none;
	}

}

  .breadcrumb {
  	display: none;
  }

.single-row-button {
	margin-bottom: 26px;
	margin-top: 26px;
}

/* CSS for loading spinner */
.loading-preview {
  display: none; /* hide by default */
  text-align: center;
	    position: absolute;
    top: 40%;
    left: 40%;
	    top: 200px;
    left: 150px;
}

.loading-spinner i {
  font-size: 32px;
  color: #999;
}

p.account-tip-vk {
    color: rgb(41, 41, 41);
}

p.account-tip-vk {
    background: url(/images/tip-icon.png) no-repeat scroll 10px center rgba(0,0,0,.03);
    border-radius: 5px;
    display: block;
    margin-bottom: 5px;
    padding: 10px 10px 10px 30px;
    width: 100%;
}
.wellv2{
	padding-left:32px;
	padding-right:32px;
}
#event_summary, #thumbnail-booth{
	margin-bottom: 6px !important;
}
span small{
	color: rgba(41, 41, 41,0.5);
    font-size: 14px;
}
#preview_img{max-height:196px;display:block}
.data-upload-image .bootstrap-filestyle.input-group,.data-upload-image .bootstrap-filestyle.input-group label.btn{display:block}
.emptyphoto .delete-image{position:absolute;right:0;top:0}
.emptyphoto .well{min-height:100px}
.emptyphoto img{max-height:196px}
.emptyphoto{color:#333;overflow:hidden;text-align:center;width:auto;position:relative}
