<?php
$lazyLoadSearchReults = getAddOnInfo("search_results_lazy_load","8e5c29cd2531efea4db02ebc567b8442");
if(isset($lazyLoadSearchReults["status"]) && $lazyLoadSearchReults["status"] === "success" && ($dc["enableLazyLoad"] == 1 || $dc["enableLazyLoad"] == "")) {
	echo widget($lazyLoadSearchReults["widget"],"",$w['website_id'],$w); 
} ?>
</div>
<div class="map_container">
	<?php 
	$mapViewAddOn = getAddOnInfo('google_map_search_results','8342a12b460d88d90a8c421953a44530');
	if (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success') {
		echo widget($mapViewAddOn['widget'],"",$w['website_id'],$w);
	} ?>
</div>
<script>
document.querySelectorAll('.video__play_link').forEach(function(el){
    let rawId = el.getAttribute('id');                      
    let videoId = rawId.split('?')[0];                     

    let thumbnail = "https://img.youtube.com/vi/" + videoId + "/mqdefault.jpg";


    let img = el.querySelector('img');
    img.src = thumbnail;
});
	
	document.querySelectorAll(".video__play_link").forEach(function (el) {
    let img = el.querySelector("img");
    let data = el.getAttribute("data-video-url") || "";
    let id = el.getAttribute("id") || "";
    let thumbnail = "";

  
    if (id && id !== "Array") {
        const cleanId = id.split("?")[0];
        thumbnail = `https://img.youtube.com/vi/${cleanId}/mqdefault.jpg`;
    }

  
    if (!thumbnail && data.includes("youtube.com")) {
        let ytIdMatch = data.match(/v=([^"&]+)/);
        if (ytIdMatch) {
            thumbnail = `https://img.youtube.com/vi/${ytIdMatch[1]}/mqdefault.jpg`;
        }
    }

  
    if (!thumbnail && data.includes("vimeo.com")) {
        let vimeoMatch = data.match(/vimeo\.com\/video\/(\d+)/);

        if (vimeoMatch) {
            let vId = vimeoMatch[1];

            // Vimeo thumbnail URL (max-quality)
            thumbnail = `https://vumbnail.com/${vId}.jpg`;
        }
    }

   
    if (!thumbnail) {
        thumbnail = "/images/default-video-thumbnail.jpg";
    }

    img.src = thumbnail;
});


</script>