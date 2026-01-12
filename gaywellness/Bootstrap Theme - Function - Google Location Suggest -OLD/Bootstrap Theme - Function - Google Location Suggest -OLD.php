<?php
if ($w['optimize_google_maps_js_delivery'] != "1") {
    $keyCode = "";
    $keySiteURL = str_replace("www.", "", $w['website_url']);

    if ($w['google_maps_api_key'] != "" && strstr($_SERVER['SERVER_NAME'], $keySiteURL)) {
        $keyCode = "&key=" . $w['google_maps_api_key'];
    }
    ?>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&region=<?php echo $w['country']; ?>&language=<?php echo brilliantDirectories::getWebsiteLanguage();
    echo $keyCode; ?>"></script>
<?php } ?>