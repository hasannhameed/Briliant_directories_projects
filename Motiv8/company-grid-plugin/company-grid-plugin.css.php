helvetica_font_family * {
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
	min-width:307px;
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
    width: 130px; /* 115 */
    background: #666;
    color: #fff;
    text-align: center;
    margin-top: -5px;
    margin-left: calc(100% - 124px); /* 109 */
    right: 0;
	font-size: 12px;
	z-index: 99;
}
@media (max-width: 1199px){
	.company_desc{
		min-width: auto;
	}
}
@media (max-width: 991px){
	.company_desc{
		min-width:307px;
	}
}
@media (max-width: 769px) {
	.link-video img, iframe, .company-single-embed{
		height: 300px !important;
		min-height: 300px !important;
    	max-height: 300px !important;
	}
	.company-thum.thumbnail.search_supplier{
		width: 100%;
	}
}
@media (max-width: 480px) {
  .supplier-webinars {
    text-align: center;
    margin-bottom: 10px;
  }
	.company-thum.thumbnail.search_supplier{
		width: 100%;
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


@media only screen and (max-width: 768px) {
  .supplier-webinars {
    text-align: center;
  }

  .search-form {
    margin-top: 10px;
  }
	/*.exhibitor-container > .row >.col-sm-6.col-md-4 ,.company-thum.thumbnail.search_supplier{
    width: 100%;
}*/
}
@media only screen and (max-width: 767px) {
	.exhibitor-container > .row >.col-sm-6.col-md-4 ,.company-thum.thumbnail.search_supplier{
		width: 100%;
	}
}
@media only screen and (max-width: 320px) {
	.company_desc{
		min-width:auto;
		height: 130px;
	}
}
/* For mobile */


.company_name, .sub_category_name, .top_category_name{
	display:none;
}
