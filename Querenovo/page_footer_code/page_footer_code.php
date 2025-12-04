</div>
<?php
$lazyLoadSearchReults = getAddOnInfo('search_results_lazy_load', '8e5c29cd2531efea4db02ebc567b8442');
if (isset($lazyLoadSearchReults["status"]) && $lazyLoadSearchReults["status"] === "success" && ($dc["enableLazyLoad"] == 1 || $dc["enableLazyLoad"] == "")) {
    echo widget($lazyLoadSearchReults["widget"], "", $w[website_id], $w);
} ?>

    <div class="map_container" style="display:none;">
        <?php
        $mapViewAddOn = getAddOnInfo('google_map_search_results','8342a12b460d88d90a8c421953a44530');
        if (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success') {
            echo widget($mapViewAddOn['widget'],"",$w['website_id'],$w);
        } ?>
    </div>
<?php
$clickPhoneAddOnFooter = getAddOnInfo("click_to_phone", "19dd6c19c0943cc078aa0873b330ada2");
if (isset($clickPhoneAddOnFooter['status']) && $clickPhoneAddOnFooter['status'] === 'success') {
    echo widget($clickPhoneAddOnFooter['widget'], "", $w[website_id], $w);
} else {
    $statisticsAddOnFooter = getAddOnInfo("user_statistics_addon", "ebb3e8d8cd4b30cb80a24d75f660987c");
    if (isset($statisticsAddOnFooter['status']) && $statisticsAddOnFooter['status'] === 'success') {
        echo widget($statisticsAddOnFooter['widget'], "", $w[website_id], $w);

    }
}
echo widget("Bootstrap Theme - Listing Search Results Statistics", '', $w[website_id], $w);

?>


<script>
document.addEventListener("DOMContentLoaded", function () {

    /** ================
     *  LIST VIEW
     *  ================
     */
    document.querySelector(".listView").addEventListener("click", function () {

        document.querySelectorAll(".grid_element").forEach(card => {

            const info = card.querySelector(".info_section");
            if (info) {
                info.classList.remove("col-sm-3");
                info.classList.add("col-sm-12");
            }

            const moduleDiv = card.querySelector(".info_section .module");
            if (moduleDiv) {
                moduleDiv.classList.remove("col-sm-10", "pull-right");
                moduleDiv.classList.add("col-sm-10", "pull-right", "nomargin", "text-center");
            }

        });

    });

    /** ================
     *  GRID VIEW
     *  ================
     */
    document.querySelector(".gridView").addEventListener("click", function () {

        document.querySelectorAll(".grid_element").forEach(card => {

            const btns = card.querySelectorAll(".mybuttons");
           
                btns.classList.remove("col-sm-10");
                btns.classList.add("col-sm-8, pull-right");
           

            const img = card.querySelector(".img_section");
            if (img) {
                img.classList.add("col-sm-3");
            }

            const mid = card.querySelector(".mid_section");
           
                mid.classList.remove("col-sm-10");
                mid.classList.remove("col-sm-12");
                mid.classList.add("col-sm-8");
            

        });

    });

});
</script>
<script>

function triggerListViewOnMobile() {
    if (window.innerWidth <= 768) {
        const listView2 = document.querySelector(".listView");
        if (listView2) listView2.click();
        console.log("Triggered on resize or load");
    }
}

document.addEventListener("DOMContentLoaded", triggerListViewOnMobile);
window.addEventListener("resize", function () {
    triggerListViewOnMobile();
});
document.querySelectorAll(".click-to-call-button").forEach(el => {
    el.classList.remove("btn-secondary");
});
document.querySelectorAll(".bi-check-circle-fill").forEach(el => {
    el.classList.remove("bi-check-circle-fill");
    el.classList.add("bi-check-circle");
});


</script>




