<!-- FontAwesome Fonts -->
<link rel="stylesheet" media="print" onload="this.onload=null;this.removeAttribute('media');" href="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/font-awesome/css/font-awesome.min.css">
<noscript>
	<link rel="stylesheet" href="<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/font-awesome/css/font-awesome.min.css">
</noscript>

<?php if ((isset($page['disable_css_stylesheets']) && $page['disable_css_stylesheets'] != '' && $page['disable_css_stylesheets'] == "1") || ($w['website_disable_css_stylesheets'] != '' && $w['website_disable_css_stylesheets'] == "1")) {
	$noPageStylesheets = true;
	} if ($pars[0] != "account" && (!isset($noPageStylesheets) || !$noPageStylesheets)) { ?>
	<!-- Non-Critical Stylesheet -->
	<script>
		var non_critical_css  = document.getElementById('critical-styles');
		var link  = document.createElement('link');
		link.rel  = "stylesheet";
		link.media = "print";
		link.href = '<?php echo brilliantDirectories::cdnUrl(); ?>/directory/cdn/assets/bootstrap/css/non-critical-styles.pkgd.min.css';
		non_critical_css.before(link);
		link.removeAttribute('media');
	</script>

<?php } 
    $w['google_analytics'] = trim($w['google_analytics']);
    if ($w['google_analytics'] != "") { ?>
	<!-- Google Analytics Tracking Code -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', '<?php echo $w['google_analytics'];?>', 'auto');
		ga('send', 'pageview');
	</script>

<?php } ?>