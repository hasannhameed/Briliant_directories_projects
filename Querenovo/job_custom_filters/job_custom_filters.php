<style>

    .grid_element{
        background: #fff;
        border: 1px solid #e6e6e6;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

    .grid_element:hover {
        box-shadow: 5px 5px 10px 2px #e5e7eb;
        border-radius: 10px;
    }

   .show :has(.grid_element){
        background-color: white !important;
        border: 1px solid #e6e6e6 !important;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

   .module:has(.form) {
        background-color: white !important;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

    .module2{
         background-color: white !important;
         border: none !important;
         background: #fff;
         border-color: rgb(255 255 255);
    }
    .custom-sidebar-search-filters-inner{
        background-color: white !important;
        padding: 0px !important;
        border: none;
    }
    .custom-sidebar-search-filters-inner .form-group{
        margin: 0px;
        
    }
    .sub-cat-checkbox-container.sub-sub label {
        padding-left: 25px;
        display: flex;
        padding-left: 0px;
        padding-bottom: 5px;
    }
    .sub-cat-checkbox-container.sub-sub{
        margin-bottom: 30px;
    }
    .checkbox-name-filter-category {
        width: calc(100% - 20px);
        font-weight: 400;
        display: -webkit-box !important;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-left: 5px;
    }
    /* Hide dropdown list by default */
        .dep-pro-wrapper {
        position: relative;
    }

    .dep-pro-input {
        border-radius: 10px;
        padding: 10px 12px;
    }

    .dep-pro-dropdown {
        display: none;
        position: absolute;
        z-index: 999;
        width: 100%;
        max-height: 260px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #e6e6e6;
        border-radius: 12px;
        margin-top: 6px;
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
    }

    .dep-pro-option {
        padding: 10px 14px;
        cursor: pointer;
        display: flex;
        gap: 8px;
        align-items: center;
        font-size: 14px;
    }

    .dep-pro-option strong {
        color: #111;
        min-width: 15px;
    }

    .dep-pro-option span {
        color: #555;
    }

    .dep-pro-option:hover {
        background: #f5f7fa;
    }


</style>

<div class="module2" bis_skin_checked="1">
    <h3>
        <font dir="auto" style="vertical-align: inherit;">
            <!-- <font dir="auto" style="vertical-align: inherit;">Filtres avancés</font> -->
        </font>
    </h3>

  

        

        <div class="custom-sidebar-search-filters-inner" data-pid="sub_marques">

             <!-- DEPARTMENT -->
            <?php
                $dep_query = mysql_query("SELECT dep_id, dep_name FROM departments ORDER BY dep_name ASC");
                $departments = [];
                while ($r = mysql_fetch_assoc($dep_query)) {
                    $departments[] = $r;
                }
                $department_id = $_GET['department_code'][0] ?? '';
                $dep_name = '';
                if ($department_id !== '') {
                    $query = mysql_query("SELECT dep_name FROM departments WHERE dep_id = '" . intval($department_id) . "'");

                    $row = mysql_fetch_assoc($query);
                    $dep_name = $row['dep_name'] ?? '';

                    echo $dep_name;
                }   
            ?>
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title"><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">departements</font></font></span>
            </label>
            <div class="dep-pro-wrapper sub-cat-checkbox-container sub-sub">
                <input
                    type="text"
                    id="depSearch"
                    class="form-control dep-pro-input"
                    placeholder="Ex: 75 ou Paris"
                    autocomplete="off"
                    value="<?PHP echo $department_id ? $department_id." - ".$dep_name : ''; ?>"
                >
                <small>Tapez le numéro d'un département et sélectionnez-le</small>

                <div id="depResults" class="dep-pro-dropdown">
                    <?php foreach ($departments as $d): ?>
                        <div
                            class="dep-pro-option"
                            data-value="<?= $d['dep_id']; ?>"
                            data-search="<?= strtolower($d['dep_id'].'-'.$d['dep_name']); ?>"
                        >
                            <strong><?= $d['dep_id'] ? $d['dep_id']."-":''; ?></strong>
                            <span><?= $d['dep_name']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- actual field submitted -->
                <input type="hidden" name="department_code[]" id="selectedDepartment" value="<?php echo $_GET['department_code'][0] ?? ''; ?>">
            </div>



            <!-- TYPE DE CONTRAT -->
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Type de contrat</span>
            </label>
            <div class="sub-cat-checkbox-container sub-sub">
                <label>
                    <input type="checkbox" name="type_de_contrat[]" value="cdi">
                    <span class="checkbox-name-filter-category">CDI</span>
                </label>

                <label>
                    <input type="checkbox" name="type_de_contrat[]" value="cdd">
                    <span class="checkbox-name-filter-category">CDD</span>
                </label>

                <label>
                    <input type="checkbox" name="type_de_contrat[]" value="stage">
                    <span class="checkbox-name-filter-category">Stage</span>
                </label>

                <label>
                    <input type="checkbox" name="type_de_contrat[]" value="intrim">
                    <span class="checkbox-name-filter-category">Intérim</span>
                </label>

                <label>
                    <input type="checkbox" name="type_de_contrat[]" value="alternance">
                    <span class="checkbox-name-filter-category">Alternance</span>
                </label>

                <label>
                    <input type="checkbox" name="type_de_contrat[]" value="soustraitance">
                    <span class="checkbox-name-filter-category">Sous-traitance</span>
                </label>

                <label>
                    <input type="checkbox" name="type_de_contrat[]" value="cotraitance">
                    <span class="checkbox-name-filter-category">Co-traitance</span>
                </label>
            </div>

            <!-- URGENCE -->
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Urgence</span>
            </label>
            <div class="sub-cat-checkbox-container sub-sub">
                <label>
                    <input type="checkbox" name="urgent[]" value="1">
                    <span class="checkbox-name-filter-category">Annonces urgentes uniquement</span>
                </label>

                <label>
                    <input type="checkbox" name="urgent[]" value="0">
                    <span class="checkbox-name-filter-category">Annonces non urgentes uniquement</span>
                </label>
            </div>

            <!-- TYPE D'ENTREPRISE -->
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Type d'entreprise</span>
            </label>
            <div class="sub-cat-checkbox-container sub-sub">
                <label>
                    <input type="checkbox" name="type_dentreprise[]" value="familiale">
                    <span class="checkbox-name-filter-category">Familiale</span>
                </label>

                <label>
                    <input type="checkbox" name="type_dentreprise[]" value="pme">
                    <span class="checkbox-name-filter-category">PME</span>
                </label>

                <label>
                    <input type="checkbox" name="type_dentreprise[]" value="grande_enseigne">
                    <span class="checkbox-name-filter-category">Grande enseigne</span>
                </label>

                <label>
                    <input type="checkbox" name="type_dentreprise[]" value="public">
                    <span class="checkbox-name-filter-category">Public</span>
                </label>

                <label>
                    <input type="checkbox" name="type_dentreprise[]" value="associatif">
                    <span class="checkbox-name-filter-category">Associatif</span>
                </label>
            </div>

            <!-- MODE DE TRAVAIL -->
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Mode de travail</span>
            </label>
            <div class="sub-cat-checkbox-container sub-sub">
                <label>
                    <input type="checkbox" name="mode_de_travail[]" value="sdentaire">
                    <span class="checkbox-name-filter-category">Sédentaire</span>
                </label>

                <label>
                    <input type="checkbox" name="mode_de_travail[]" value="tltravail">
                    <span class="checkbox-name-filter-category">Télétravail</span>
                </label>

                <label>
                    <input type="checkbox" name="mode_de_travail[]" value="dplacements">
                    <span class="checkbox-name-filter-category">Déplacements</span>
                </label>

                <label>
                    <input type="checkbox" name="mode_de_travail[]" value="hybride">
                    <span class="checkbox-name-filter-category">Hybride</span>
                </label>

                <label>
                    <input type="checkbox" name="mode_de_travail[]" value="alternance">
                    <span class="checkbox-name-filter-category">Alternance</span>
                </label>

                <label>
                    <input type="checkbox" name="mode_de_travail[]" value="soustraitance">
                    <span class="checkbox-name-filter-category">Sous-traitance</span>
                </label>

                <label>
                    <input type="checkbox" name="mode_de_travail[]" value="cotraitance">
                    <span class="checkbox-name-filter-category">Co-traitance</span>
                </label>
            </div>

        </div>


        <!-- <div class="form-group nobmargin" bis_skin_checked="1">
            <button type="submit" class="btn btn-primary btn-block">
                <font dir="auto" style="vertical-align: inherit;">
                    <font dir="auto" style="vertical-align: inherit;">Search now</font>
                </font>
            </button>
        </div> -->
   
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const params = new URLSearchParams(window.location.search);

    // Function to check inputs based on URL values
    function restoreCheckboxState(paramName) {
        const values = params.getAll(paramName + "[]"); 
        if (values.length === 0) return;

        values.forEach(function(value) {
            const selector = "input[name='" + paramName + "[]'][value='" + value + "']";
            const checkbox = document.querySelector(selector);
            if (checkbox) checkbox.checked = true;
        });
    }

    restoreCheckboxState("type_de_contrat");
    restoreCheckboxState("urgent");
    restoreCheckboxState("type_dentreprise");
    restoreCheckboxState("mode_de_travail");

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const input   = document.getElementById("depSearch");
    const dropdown = document.getElementById("depResults");
    const options  = dropdown.querySelectorAll(".dep-pro-option");
    const hidden   = document.getElementById("selectedDepartment");

    // 🔹 CLEAN EMPTY / INVALID INITIAL VALUE
    input.addEventListener("input", () => {
        if (!input.value || input.value.trim() === '-' || input.value.trim() === '') {
            input.value = '';
            hidden.value = '';
        }
    });

    // if (!input.value || input.value.trim() === '-' || input.value.trim() === '') {
    //     input.value = '';
    //     hidden.value = '';
    // }

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
