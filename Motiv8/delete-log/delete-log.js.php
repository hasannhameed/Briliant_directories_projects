<script>
document.addEventListener("DOMContentLoaded", function() {
    const search = document.getElementById("filterSearch");
    const typeFilter = document.getElementById("filterType");
    const tableFilter = document.getElementById("filterTable");
    const dateFilter = document.getElementById("filterDate");
    const rows = document.querySelectorAll("#tableBody tr");

    function filterLogs() {
        let s = search.value.toLowerCase();
        let t = typeFilter.value;
        let tb = tableFilter.value.toLowerCase();
        let dt = dateFilter.value;

        rows.forEach(row => {
            const txt = row.innerText.toLowerCase();
            const userType = row.querySelector(".btn-consistent").innerText;
            const tableText = row.querySelector(".table-text").innerText.toLowerCase();
            const dateText = row.querySelector(".date-text").innerText;

            // Match date in `dd-F-Y`
            let matchDate = true;
            if (dt !== "") {
                let selected = new Date(dt);
                let rowDate = new Date(dateText.replace(" of ", " "));
                matchDate = (selected.toDateString() === rowDate.toDateString());
            }

            const matchesSearch = txt.includes(s);
            const matchesType = t === "" || userType === t;
            const matchesTable = tb === "" || tableText.includes(tb);

            if (matchesSearch && matchesType && matchesTable && matchDate) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    search.addEventListener("keyup", filterLogs);
    typeFilter.addEventListener("change", filterLogs);
    tableFilter.addEventListener("keyup", filterLogs);
    dateFilter.addEventListener("change", filterLogs);
});
</script>
