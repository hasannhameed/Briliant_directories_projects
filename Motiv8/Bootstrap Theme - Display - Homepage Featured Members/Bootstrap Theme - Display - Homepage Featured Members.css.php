.featured-member-image {
    display: flex;
    align-items: center;
}

.featured-member .h4{color:#000 !important;}


.display-featured-members .featured-member {
	vertical-align:top;
}
.featured-member-image {
}
.featured-member-image img {
	max-height:112px;
}	
.featured-member-info p {
	min-height:75px;
}
<?php 
$columnWidth = 0;

if ($wa['custom_315'] == "1" || !isset($wa['custom_315'])) {
	$columnWidth = 3;	
} else if ($wa['custom_315'] == "2") {
	$columnWidth = 2;
} else if ($wa['custom_315'] == "0") {
	$columnWidth = 4;
}
?>

@media only screen and (min-width: 991px) {
	.display-featured-members .featured-member:nth-child(<?php echo $columnWidth; ?>n+1) {
		clear: left;
	}
}
@media only screen and (max-width: 991px) {
	.display-featured-members .featured-member:nth-child(2n+1) {
		clear: left;
	}
}