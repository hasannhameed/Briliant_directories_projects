<?php



$user_id = $_COOKIE['userid'];
$q = mysql_query("SELECT selected_brands FROM users_data WHERE user_id = ".(int)$user_id);
$row = mysql_fetch_assoc($q);
$existingBrands = json_decode($row['selected_brands'], true) ?: [];
?>

<div id="brand-sublist" style="display:block;">

   <div class="col-sm-12 nopad bg-secondary vpad img-rounded nomargin no-radius-bottom alert">
        <div class="col-sm-8 nopad col-sm-8">
            <h4 class="h4 bold alert nomargin">
                Select Brands
            </h4>
        </div>
        <div class="col-sm-4 h4 bold alert nomargin nopad">
            <input type="hidden" name="brand_all" value="1">
            <input class="form-control brand-search" placeholder="Keyword search">
        </div>
   </div>

    <div class="clearfix"></div>

    <div class="table-responsive well no-radius-top brand-body" id="brand-holder" tabindex="-1">

        <ul class="list-inline inline-block btn-block brand-list">
            <li class="col-xs-12 col-sm-4 col-md-6 col-lg-4">
                <div class="brand-accordion">

                    <div class="checkbox brand-parent">
                        <label class="nopad">
                            <input class="brand-parent-check" style="opacity:0;" type="checkbox" value="brands_parent">
                            <a data-toggle="collapse" href="#brand-list-collapse" aria-expanded="true" class="bold">
                                Select Brands <i class="fa fa-caret-down"></i>
                            </a>
                        </label>
                    </div>

                    <div class="collapse in brand-collapse" id="brand-list-collapse">

                        <?php
                        $brands = [
                            ["id" => 70001, "name" => "Aldes"],
                            ["id" => 70002, "name" => "Atlantic"],
                            ["id" => 70003, "name" => "Daikin"],
                            ["id" => 70004, "name" => "Isover"],
                            ["id" => 70005, "name" => "K-line"],
                            ["id" => 70006, "name" => "Mitsubishi Electric"],
                            ["id" => 70007, "name" => "Rockwool"],
                            ["id" => 70008, "name" => "Saint-Gobain"],
                            ["id" => 70009, "name" => "Thermor"],
                            ["id" => 70010, "name" => "Velux"]
                        ];

                        foreach ($brands as $b) {
                            $checked = in_array($b["id"], (array)$existingBrands) ? "checked" : "";
                        ?>
                            <div class="checkbox brand-item">
                                <label>
                                    <input class="brand-check" type="checkbox"
                                        name="brands[]"
                                        value="<?php echo $b["id"]; ?>"
                                        data-name="<?php echo htmlspecialchars($b["name"]); ?>"
                                        <?php echo $checked; ?>>
                                    <span class="brand-label"><?php echo htmlspecialchars($b["name"]); ?></span>
                                </label>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </li>
        </ul>

        <div class="clearfix"></div>

        <p id="brand-bottom-text" class="bg-default fpad img-rounded vmargin bold">
            You have selected <span id="brand-count">0</span> brands
        </p>

        <div class="brand-selected-container"></div>

        <small id="brand-error" class="help-block" style="display:none;"></small>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", initBrandSelector);

    function initBrandSelector() {

        const MAX_SELECTION = 10;

        const brandContainer = document.querySelector("#brand-sublist");
        const chipHolder     = document.querySelector(".brand-selected-container");
        const countEl        = document.getElementById("brand-count");
        const searchInput    = document.querySelector(".brand-search");
        const errorBox       = document.getElementById("brand-error");

        const STORAGE_KEY = "quirenov_selected_brands";

        function debounce(fn, delay = 400) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => fn(...args), delay);
            };
        }

        function getStoredSelection() {
            try {
                return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            } catch (e) {
                return [];
            }
        }

        

        function saveToStorage(ids) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
            async function saveToDatabase(ids) {
                try {
                    const response = await fetch("https://www.quirenov.fr/api/widget/html/post/select_brand_ajax", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            action: "save_brand_selection",
                            brands: ids
                        })
                    });

                    const data = await response.text();
                    console.log("Server response:", data);

                } catch (error) {
                    console.error("Error saving brand selection:", error);
                }
            }
            saveToDatabase(ids);
        }
       


        function filterBrands() {
            const keyword = searchInput.value.toLowerCase().trim();

            document.querySelectorAll(".brand-check").forEach(cb => {
                const item  = cb.closest(".brand-item");
                const label = cb.dataset.name.toLowerCase();
                item.style.display = label.includes(keyword) ? "block" : "none";
            });
        }

        function renderChips() {

            const checkboxes = [...document.querySelectorAll(".brand-check")];

            const selected = checkboxes
                .filter(cb => cb.checked)
                .map(cb => ({ id: cb.value, name: cb.dataset.name }));

            countEl.textContent = selected.length;

            chipHolder.innerHTML = selected.map(item => `
                <span class="btn btn-default btn-xs bold rmargin bmargin checkedSub brand-chip">
                    ${item.name}
                    <i class="fa fa-times text-danger brand-chip-remove" data-id="${item.id}"></i>
                </span>
            `).join("");

            errorBox.style.display = "none";
        }

        const logSelection = debounce(function () {
            const checkboxes = [...document.querySelectorAll(".brand-check")];
            const selectedIDs = checkboxes.filter(cb => cb.checked).map(cb => cb.value);

            console.log("Selected Brands:", selectedIDs);

            saveToStorage(selectedIDs);
        }, 300);

        // ---------------------------------------------------
        // EVENT: Checkbox selection (delegated)
        // ---------------------------------------------------
        brandContainer.addEventListener("change", function(e) {
            if (!e.target.classList.contains("brand-check")) return;

            const checkboxes = [...document.querySelectorAll(".brand-check")];
            const selectedCount = checkboxes.filter(c => c.checked).length;

            if (selectedCount > MAX_SELECTION) {
                e.target.checked = false;
                errorBox.textContent = `You can select up to ${MAX_SELECTION} brands only.`;
                errorBox.style.display = "block";
                return;
            }

            renderChips();
            logSelection();
        });

        // ---------------------------------------------------
        // EVENT: Remove chip (delegated)
        // ---------------------------------------------------
        document.addEventListener("click", function(e) {
            if (!e.target.classList.contains("brand-chip-remove")) return;

            const id = e.target.dataset.id;
            const cb = document.querySelector(`.brand-check[value="${id}"]`);

            if (cb) cb.checked = false;

            renderChips();
            logSelection();
        });

        // ---------------------------------------------------
        // EVENT: Live Search
        // ---------------------------------------------------
        searchInput.addEventListener("keyup", filterBrands);

        // ---------------------------------------------------
        // INITIAL PAGE LOAD — RESTORE STATE
        // ---------------------------------------------------
        function restoreFromStorage() {
            const stored = getStoredSelection();
            if (!stored.length) return;

            document.querySelectorAll(".brand-check").forEach(cb => {
                cb.checked = stored.includes(cb.value);
            });
        }

        restoreFromStorage();
        renderChips();
        filterBrands();
    }
</script>


