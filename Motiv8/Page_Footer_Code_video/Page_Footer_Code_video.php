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
const clickToLoadMoreBtn = document.querySelector('.clickToLoadMoreBtn');

function fetchmythumnails() {
    document.querySelectorAll(".video__play_link").forEach(function (el) {

        let img = el.querySelector("img");
        let data = el.getAttribute("data-video-url") || "";
        let rawId = el.getAttribute("id") || "";
        let thumbnail = "";

        if (rawId && rawId !== "Array") {
            let cleanId = rawId.split("?")[0];
            thumbnail = `https://img.youtube.com/vi/${cleanId}/mqdefault.jpg`;
        }

        if (!thumbnail && data.includes("youtube.com")) {
            let ytMatch = data.match(/v=([^"&]+)/);
            if (ytMatch) {
                thumbnail = `https://img.youtube.com/vi/${ytMatch[1]}/mqdefault.jpg`;
            }
        }

        if (!thumbnail && data.includes("vimeo.com")) {
            let vmMatch = data.match(/vimeo\.com\/video\/(\d+)/);
            if (vmMatch) {
                let vId = vmMatch[1];
                thumbnail = `https://vumbnail.com/${vId}.jpg`;
            }
        }

        if (!thumbnail) {
            thumbnail = "/images/default-video-thumbnail.jpg";
        }

        img.src = thumbnail;
    });
}


let intervalCounter = 0;
let intervalId = null;

function intervelfetch() {

   
    if (intervalId !== null) {
        clearInterval(intervalId);
    }

    intervalCounter = 0;

    intervalId = setInterval(() => {
        intervalCounter++;
        console.log("Running fetch:", intervalCounter);

        fetchmythumnails();

        if (intervalCounter >= 300) { 
            console.log("Stopped interval after 300 runs");
            clearInterval(intervalId);
            intervalId = null;
        }
    }, 150);
}

fetchmythumnails();
intervelfetch();


clickToLoadMoreBtn?.addEventListener("click", intervelfetch);

</script>
