<?php 
if ($w['google_analytics_4'] != "") {
    $googleAnalyticsID = htmlspecialchars(trim($w['google_analytics_4']));
?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $googleAnalyticsID; ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $googleAnalyticsID; ?>');
    </script>
<?php } ?>
