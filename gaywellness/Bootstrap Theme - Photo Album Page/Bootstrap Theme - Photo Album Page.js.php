<!-- Royal Slider -->
<script src="/directory/cdn/assets/bootstrap/RoyalSlider/jquery.royalslider.min.js"></script>
<link href="/directory/cdn/assets/bootstrap/RoyalSlider/royalslider.min.css" rel="stylesheet">
<link href="/directory/cdn/assets/bootstrap/RoyalSlider/skins/default/rs-default.min.css" rel="stylesheet">
<?php

$photogroupid = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT DISTINCT
        COUNT(group_id)
    FROM
      `users_portfolio`
    WHERE
      `status` = '1'
    AND 
    	`user_id` = '".$group['user_id']."'
    AND 
    	`data_id` = '".$group['data_id']."'
    AND
      `file` != ''
    ORDER BY
      `order` ASC");
	$totalgroupid = mysql_num_rows($photogroupid);
	for ($x = 1; $x <= $totalgroupid; $x++) {
	  
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
	$(document).ready(function() {
		setTimeout(function() {
			$('#gallery-<?php echo $x ?>').royalSlider({
				fullscreen: {
					enabled: true,
					nativeFS: false
				},
				<?php if ($total[0] == 1){ ?>
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
				numImagesToPreload:2,
				arrowsNav:true,
				arrowsNavAutoHide: true,
				arrowsNavHideOnTouch: true,
				keyboardNavEnabled: true,
				fadeinLoadedSlide: true,
				globalCaptionInside: true,
				thumbs: {
					appendSpan: true,
					firstMargin: true,
					paddingBottom: 4
				}
			});
			var slider = $('#gallery-<?php echo $x ?>').data('royalSlider');
			if(typeof slider != 'undefined'){
				slider.ev.on('rsAfterContentSet', function (e, slideObject) {
					var img = slideObject.holder.find('img').eq(0);
					if (img && img.length && slideObject.caption) {
						img.attr('alt', slideObject.caption.find('h4').text());
					}
				});
			}
		},500);
	});
</script>
<?php } ?>
