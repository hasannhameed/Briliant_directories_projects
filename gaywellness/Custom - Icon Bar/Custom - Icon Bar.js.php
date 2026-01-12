<script>
function gcToggleMobileFilters() {
    var filterBox = document.getElementById("gc-mobile-filter-wrapper");
    if (!filterBox) { return; }

    if (filterBox.className.indexOf("gc-open") === -1) {
        // add gc-open
        filterBox.className += (filterBox.className ? " " : "") + "gc-open";
    } else {
        // remove gc-open
        filterBox.className = filterBox.className.replace(/\bgc-open\b/, "").trim();
    }
}

</script>