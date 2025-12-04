<!-- Royal Slider -->
<script src="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/RoyalSlider/jquery.royalslider.min.js"></script>
<link href="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/RoyalSlider/royalslider.min.css" rel="stylesheet">
<link href="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/RoyalSlider/skins/default/rs-default.min.css" rel="stylesheet">
<?php

$photogroup = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
        COUNT(*)
    FROM
      `users_portfolio`
    WHERE
      `group_id` = '".$group['group_id']."'
    AND 
    	`user_id` = '".$group['user_id']."'
    AND 
    	`data_id` = '".$group['data_id']."'
    AND
      `file` != ''
    ORDER BY
      `order` ASC");
$total = mysql_fetch_array($photogroup);
?>

<script>
	$(document).ready(function () {
		setTimeout(function () {
			$('#gallery-1').royalSlider({
				fullscreen: {
					enabled: true,
					nativeFS: false
				},
				<?php if ($total[0] == 1) { ?>
				controlNavigation: false,
				<?php } else { ?>
				controlNavigation: 'thumbnails',
				<?php } ?>
				autoScaleSlider: true,
				autoScaleSliderWidth: 960,
				autoScaleSliderHeight: 600,
				globalCaption: true,
				loop: true,
				imageScaleMode: 'fit-if-smaller',
				navigateByClick: true,
				numImagesToPreload: 2,
				arrowsNav: true,
				arrowsNavAutoHide: true,
				arrowsNavHideOnTouch: true,
				keyboardNavEnabled: false,
				fadeinLoadedSlide: true,
				globalCaptionInside: true,
				thumbs: {
					appendSpan: true,
					firstMargin: true,
					paddingBottom: 4
				}
			});
			var slider = $('#gallery-1').data('royalSlider');
			if (typeof slider !== 'undefined') {
				// Flip margin-left to margin-right for RTL support
				if (document.dir === "rtl" || document.documentElement.dir === "rtl") {

					function flipAllMarginLeftsToRights() {
						$('.royalSlider .rsMainSlideImage').each(function () {
							var el = this;
							var ml = el.style.marginLeft;
							if (ml && ml !== '0px') {
								el.style.marginLeft = '';
								el.style.marginRight = ml;
							}
						});
					}
					slider.ev.on('rsAfterContentSet', function () {
						setTimeout(flipAllMarginLeftsToRights, 150);
					});
					slider.ev.on('rsAfterSlideChange', function () {
						setTimeout(flipAllMarginLeftsToRights, 150);
					});
					slider.ev.on('rsEnterFullscreen', function () {
						setTimeout(flipAllMarginLeftsToRights, 200);
						setTimeout(flipAllMarginLeftsToRights, 500);
					});
					slider.ev.on('rsExitFullscreen', function () {
						setTimeout(flipAllMarginLeftsToRights, 150);
					});
				}
				slider.ev.on('rsAfterContentSet', function (e, slideObject) {
					var img = slideObject.holder.find('img').eq(0);
					if (img && img.length && slideObject.caption) {
						img.attr('alt', slideObject.caption.find('h4').text());
					}
				});
			}
		}, 500);
	});
</script>