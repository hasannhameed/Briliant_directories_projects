<script>
document.addEventListener("DOMContentLoaded", function () {
    const input   = document.getElementById("depSearch");
    const dropdown = document.getElementById("depResults");
    const options  = dropdown.querySelectorAll(".dep-pro-option");
    const hidden   = document.getElementById("selectedDepartment");

    input.addEventListener("focus", () => dropdown.style.display = "block");

    input.addEventListener("keyup", function () {
        const val = this.value.toLowerCase();
        let visible = 0;

        options.forEach(opt => {
            if (opt.dataset.search.includes(val)) {
                opt.style.display = "flex";
                visible++;
            } else {
                opt.style.display = "none";
            }
        });

        dropdown.style.display = visible ? "block" : "none";
    });

    options.forEach(opt => {
        opt.addEventListener("click", function () {
            input.value = this.innerText.trim();
            hidden.value = this.dataset.value;
            dropdown.style.display = "none";
        });
    });

    document.addEventListener("click", e => {
        if (!e.target.closest(".dep-pro-wrapper")) {
            dropdown.style.display = "none";
        }
    });
});

</script>