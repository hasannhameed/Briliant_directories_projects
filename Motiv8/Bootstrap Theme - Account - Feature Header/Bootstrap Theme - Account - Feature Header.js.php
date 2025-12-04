<script>
	$( ".publish-post-button" ).wrap( "<div class='first-post'></div>" );
	$( ".publish-post-button" ).before( "<span class=first-post-inner><?php echo $label['publish_your_first'];?> <span class=inline-block><?php echo $dc['data_name'];?></span>" );
</script>