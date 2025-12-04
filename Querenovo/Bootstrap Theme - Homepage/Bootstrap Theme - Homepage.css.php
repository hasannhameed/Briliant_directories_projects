<?php
if (strpos($wa['custom_30'],'Map') == false && $wa['custom_30']!="Blank") { ?>
	.body-content {margin:0px;}
	#first_container {
	<?php if ($wa['custom_76']!="" && $wa['custom_77']=="1" && ($wa['custom_78'] == "0" || !addonController::isAddonActive('homepage_background_slideshow'))) { ?>
		background-image: url('<?php echo $wa['custom_76'];?>');
	<?php } ?>
	background-position: center <?php echo ($wa['home_bg_position'] != "") ? $wa['home_bg_position'] : 'top'; ?>;
	background-repeat: no-repeat;
	position: relative;
	<?php if ($wa['home_bg_attachment'] == "fixed") { ?>
		background-attachment: fixed;
	<?php } ?>
	<?php if ($wa['custom_268'] == "cover" || $wa['custom_268'] == "") { ?>
		background-size: cover;
	<?php } else { ?>
		background-size: 100% auto;
	<?php } ?>
	}
<?php } ?>


.search_box,.homepage_settings .center-block {
float:
<?php if ($wa['custom_25']=="center") { ?>
	none!important;
<?php } else { ?>
	<?php echo $wa['custom_25'];?>!important;
<?php } ?>
}

<?php if ($wa['home_bg_attachment'] == "fixed") { ?>
/* Rules for devices that do not support Fixed */
@supports (-webkit-touch-callout: none) and (-webkit-overflow-scrolling: touch) {
  #first_container { background-attachment: scroll !important; }
}
/* Blanket fallback: all touch contexts */
@media (any-hover: none) and (any-pointer: coarse) {
  #first_container { background-attachment: scroll !important; }
  @supports not (-webkit-overflow-scrolling: touch) {
    /* Non-iOS touch (e.g., Android) → allow fixed again */
    #first_container { background-attachment: fixed !important; }
  }
}
/* Accessibility & print quality */
@media (prefers-reduced-motion: reduce) {
  #first_container { background-attachment: scroll !important; }
}
@media print {
  #first_container { background-attachment: scroll !important; }
}
<?php } ?>

@media only screen and (max-width: 768px) {
	#first_container {
		background-position: <?php echo $wa['custom_25'];?> <?php echo ($wa['home_bg_position'] != "") ? $wa['home_bg_position'] : 'top'; ?>;
		<?php if ($wa['mobile_custom_76']!="" && $wa['custom_77']=="1" && ($wa['custom_78'] == "0" || !addonController::isAddonActive('homepage_background_slideshow'))) { ?>
			background-image: url('<?php echo $wa['mobile_custom_76'];?>');
			background-position: center <?php echo ($wa['home_bg_position'] != "") ? $wa['home_bg_position'] : 'top'; ?>;
		<?php } ?>
	}
	.homepage_title,.homepage_settings h2,.search_box {
		float:none!important;
	}
	<?php if ($wa['hide_hero_on_mobile']=="1") { ?>
		#first_container {
		background-image:none;
		}
	<?php } ?>
}

[class^="homepage-section-"]:empty {
	display: none;
}
.primary-hero-content .search_box, .secondary-hero-content  .search_box {
	width: 100%;
}
.homepage_settings:has(.secondary-hero-content) {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
}
<?php if ($wa['custom_25'] == "right") { ?>
	.homepage_settings:has(.secondary-hero-content) .primary-hero-content {
		order: 1;
	}
	.homepage_settings:has(.secondary-hero-content) .secondary-hero-content {
		order: 0;
	}
<?php } else if ($wa['custom_25'] == "left") { ?>
	.homepage_settings:has(.secondary-hero-content) .primary-hero-content {
		order: 0;
	}
	.homepage_settings:has(.secondary-hero-content) .secondary-hero-content {
		order: 1;
	}
<?php } ?>
.homepage_settings:has(.secondary-hero-content) > * {
	order: 10;
}
.custom-title {
    margin-top: -30px;
    margin-bottom: 30px;
    font-size: 20px;
}