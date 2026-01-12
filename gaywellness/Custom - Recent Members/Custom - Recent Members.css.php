.col-xs-2 {
    padding-right: 0px !important;
    padding-left: 15px !important;
}

.badge{
  position: absolute;
  z-index:1;
  top: 0;
  right: 0;
  margin-right: 55px;
  margin-top: 6px;
  background-color: #aae2d3 !important;
	color: #0D3F4F;
}
.badge2{
  position: absolute;
  z-index:1;
  top: 0;
  right: 0;
  margin-right: 23px;
  margin-top: 6px;
  background-color: #aae2d3 !important;
	color: #0D3F4F;
}


@keyframes blinkingText{
    0%{     color: #FFF;    }
    49%{    color: #FFF; }
    60%{    color: transparent; }
    99%{    color:transparent;  }
    100%{   color: #FFF;    }
}	

.btn-info {
    display: block;
    width: 100%;
}	


.well {
    min-height: 200px!important;
    
    margin-bottom: 10px;
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 10px!important;
    -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
    box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
}


.tab-content, .well:not(.well .well):not(.module .well):not(.tab-content .well), .module:not(.module .module):not(.well .module):not(.tab-content .module):not(.search_box .module), .panel {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0.19);
}

.col-md-3 .col-sm-6 {
    position: relative;
    min-height: 1px;
    padding-right: 5px!important;
    padding-left: 5px!important;
}


img {
    flex: none; /* keep aspect ratio */
	
}

.module2 {
    background: #fafafa;
    border-color: #eee;
    border-image: none;
    border-radius: 6px;
    border-style: solid;
	min-height: 82px;
    border-width: 1px;
    padding: 15px;
    width: 100%;
    margin-bottom: 0px;
	
}
img.enlarge{transition:all ease 400ms;}
img.enlarge:hover {transform:scale(1.1);} 