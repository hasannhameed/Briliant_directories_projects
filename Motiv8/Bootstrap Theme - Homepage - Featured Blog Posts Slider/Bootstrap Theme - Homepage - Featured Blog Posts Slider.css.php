#myCarousel {
	overflow:hidden;
}
#myCarousel .carousel-inner.col-sm-8 {
    width: 66.66666667%;
}
#myCarousel .carousel-inner {
	margin: 0 auto;
}
#myCarousel .list-group {
	width: calc(33.33333333% + 11px);
	height: 400px;
	overflow-y: auto;
	margin:0;
	position:absolute;
	<?php if ($wa['streaming_articlesList'] == "left") { ?>
		left:0;    
		<?php } else { ?>
		right:0;
		<?php } ?>
	top:0;
}

#myCarousel .item img {
	margin: 0 auto;
	max-width: 760px;
	height: 100%;
	max-height: 401px;
	border-radius: 0 !important;
}

#myCarousel .carousel-inner .item {
	min-height: 400px;
	max-height: 400px;
	left: -2px;
}

#myCarousel .carousel-caption {
	left: 0;
	right: -2px;
	bottom: 0;
	text-align: left;
	padding: 15px 0;
	text-shadow: none;
	font-size: 18px;
	font-weight:600;
}

#myCarousel li.list-group-item h4 {
	line-height: 1.6em;
	margin: 0;
	display: table-cell;
	vertical-align: middle	
}

#myCarousel .list-group-item {
	border-radius: 0px;
	cursor: pointer;
	max-height: 80px;
	box-shadow: 0 0 1px rgba(0, 0, 0, 0.5);
	border-width: 0 0 1px;
	margin: 0;
	height: 81px;
	overflow:hidden;
	display: table;
	width:100%;
}

@media only screen and (max-width: 1200px) { 
	#myCarousel {
		display:inline-block;
	}
	#myCarousel .carousel-inner {
		width: 100% !important;
		max-width: 100% !important;
		overflow: visible;
	}	
	#myCarousel .carousel-inner .item {
		min-height: initial !important;
		max-height: none !important;
		left: 0 !important;
		width: 100%;
		text-align:center;
		min-width:0;
	}	
	#myCarousel .list-group {
		display: none;
	}
}	
@media only screen and (max-width: 767px) {	
	#myCarousel .item .carousel-caption,#myCarousel .item img {
		position: relative;
		display: inline-block;
		max-width: none;
		width: calc(100% + 1px) !important;
		height: auto;
		max-height: none;	
	}	
}