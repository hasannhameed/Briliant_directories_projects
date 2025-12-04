.display-recent-members .recent-member {
	vertical-align:top;
}
.recent-member-image {
	height:112px;
}
.recent-member-image img {
	max-height:112px;
}
.recent-member.smaller-image img {
	max-height: 100px;
}
.recent-member-info p {
	min-height:75px;
	
}
.streaming-recent-member-stars.stars-with-image .the-rating-text{
	display:block;
}
.streaming-recent-member-stars .the-average-rating{
	display:none;
}
.streaming-recent-member-stars .the-star-icons {
	letter-spacing: -1px;
}
.streaming-recent-member-stars .the-review-count {
  font-size: 11px;
}
.streaming-recent-member-stars .star_rating {
    white-space: nowrap;
}
<?php
$columnWidth = 0;

if ($wa['custom_313'] == "1" || !isset($wa['custom_313'])) {
	$columnWidth = 3;
} else if ($wa['custom_313'] == "2") {
	$columnWidth = 2;
} else if ($wa['custom_313'] == "0") {
	$columnWidth = 4;
}
?>
<?php if($wa['members_carousel_slider'] != '1' || ($wa['members_carousel_slider'] == '1' && !addonController::isAddonActive('post_carousel_slider'))){ ?>
@media only screen and (min-width: 991px) {
	.display-recent-members .recent-member:nth-child(<?php echo $columnWidth; ?>n+1) {
		clear: left;
	}
}
@media only screen and (max-width: 991px) {
	.display-recent-members .recent-member:nth-child(2n+1) {
		clear: left;
	}
}
<?php } ?>


.name{
	margin-left: 38%;
	font-size: 13px !important;
}
.color,.icon{
	color: hsla(176.97, 39.4%, 49.2%, 1) ;
}
.location{
	display : inline !important;
}
.h2title{
	text-align: left !important;
	font-size: 1.5rem !important;
	padding-bottom: 0rem !important;
    padding-top: 2rem !important;
}
.boarder{
	border-radius: 0px !important;
}
.col-md-12 {
	width: 98%;
}