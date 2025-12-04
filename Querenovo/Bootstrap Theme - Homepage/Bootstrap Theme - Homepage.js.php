<script>
	$(document).ready(function() {
		var i;
        var imageRatio = 0.5625;
        <?php 
        if($wa['streaming_image_aspect_ratio'] == 'square'){ ?>
            imageRatio = 1;
        <?php }
		if($wa['streaming_image_aspect_ratio'] == '4:3'){ ?>
            imageRatio = 0.75;
        <?php } ?>
		for (i = 0; i < 16; i++) {

			var sectionid = ".homepage-section-" + i + " .pic";
			var calc = Math.floor($(sectionid).width() * imageRatio);
			$(sectionid).css({
				'height': calc + 'px'
			});

			var spanid = ".homepage-section-" + i + " .pic .pic-caption";
			var calc2 = $(sectionid).height() - 42;
			$(spanid).css({
				'transform' : 'translateY(' + calc2 + 'px)'
			});	
		}
	});
</script>