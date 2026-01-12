<?php
	$homeLink = brilliantDirectories::getWebsiteURL();
?>
<div id="website_logo" class="col-md-3 tpad xs-nopad xs-hpad sm-text-center xs-bmargin header-left-container">
	<?php
	if ($w['website_logo'] != '') {
		$logoPath = $_SERVER['DOCUMENT_ROOT'] . $w['website_logo'];

		// Check if the image is an SVG
		if (strtolower(pathinfo($logoPath, PATHINFO_EXTENSION)) == "svg") {
			$svgContent = simplexml_load_file($logoPath);
			if ($svgContent && isset($svgContent['width']) && isset($svgContent['height'])) {
				$width = (string)$svgContent['width'];
				$height = (string)$svgContent['height'];
				$attr = 'width="' . $width . '" height="' . $height . '"';
			} else {
				$attr = "";
			}
		} else {
			list($width, $height, $type, $attr) = getimagesize($logoPath);
		}
		?>
		<a href="<?php echo $homeLink; ?>" title="<?php echo $w['website_name']; ?>">
			<img <?php echo $attr; ?> src="<?php echo trim($w['website_logo']); ?>" alt="<?php echo $w['website_name']; ?>">
		</a>
	<?php } else { ?>	
      <a class="logo vpad xs-nomargin xs-nopad" title="<?php echo $w['website_name'];?>" href="<?php echo $homeLink;?>">
          <?php if ($wa['custom_14'] !='') {?>
		  	<i class="fa <?php echo $wa['custom_14'];?> pull-left hidden-sm hidden-xs bmargin"></i>
		  <?php } ?>
          <span class="logo_title"><?php echo $wa['custom_150'];?></span>
          <span class="tpad slogan"><?php echo $wa['custom_151'];?></span>
      </a> 
    <?php } ?>
	<div class="clearfix"></div>
</div>