</div>
</div>

<div>

<?php
$lazyLoadSearchReults = getAddOnInfo("search_results_lazy_load","8e5c29cd2531efea4db02ebc567b8442");
if(isset($lazyLoadSearchReults["status"]) && $lazyLoadSearchReults["status"] === "success" && ($dc["enableLazyLoad"] == 1 || $dc["enableLazyLoad"] == "")) {
    echo widget($lazyLoadSearchReults["widget"],"",$w['website_id'],$w);
} ?>

<div class="map_container">
    <?php
    $mapViewAddOn = getAddOnInfo('google_map_search_results','8342a12b460d88d90a8c421953a44530');
    if (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success') {
        echo widget($mapViewAddOn['widget'],"",$w['website_id'],$w);
    } ?>
</div>
	

<script>
$(document).ready(function () {

    // Handles click for multiple selectors
    $(document).on("click", "a.video__play_link, .h3text, .view-details .video-card", function (e) {
        e.preventDefault();

        var videoUrl = $(this).attr("href");

        $("#iframePreview").attr("src", videoUrl);
        $("#videoModal").modal("show");
    });

    // When modal closes, reset video
    $('#videoModal').on('hidden.bs.modal', function () {
        $("#iframePreview").attr("src", ""); // Stop video on close
    });

});
</script>
