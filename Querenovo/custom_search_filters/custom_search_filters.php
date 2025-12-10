<style>
    .display_inline {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .custom-sidebar-search-filters-inner {
        background: transparent !important;
        border: none;
        padding: 0px;
    }
</style>

<?php
// --------------------
// 1. Read selected filters from $_GET
// --------------------

// Categories (ttid)
$selectedSIDs = [];
if (!empty($_GET['ttid'])) {
    $tidValues = is_array($_GET['ttid']) ? $_GET['ttid'] : [$_GET['ttid']];
    foreach ($tidValues as $tid) {
        if (is_numeric($tid)) {
            $selectedSIDs[] = (int) $tid;
        }
    }
}

// RGE status
$selectedRGE = [];
if (!empty($_GET['rge_status'])) {
    $vals = is_array($_GET['rge_status']) ? $_GET['rge_status'] : [$_GET['rge_status']];
    foreach ($vals as $v) {
        $selectedRGE[] = $v;
    }
}

// Labels & certifications
$selectedLabels = [];
if (!empty($_GET['labels_and_certifications'])) {
    $vals = is_array($_GET['labels_and_certifications']) ? $_GET['labels_and_certifications'] : [$_GET['labels_and_certifications']];
    foreach ($vals as $v) {
        $selectedLabels[] = $v;
    }
}

// Type of company
$selectedCompanyTypes = [];
if (!empty($_GET['type_dentreprise'])) {
    $vals = is_array($_GET['type_dentreprise']) ? $_GET['type_dentreprise'] : [$_GET['type_dentreprise']];
    foreach ($vals as $v) {
        $selectedCompanyTypes[] = $v;
    }
}

// Content type
$selectedContentTypes = [];
if (!empty($_GET['type_de_contenu'])) {
    $vals = is_array($_GET['type_de_contenu']) ? $_GET['type_de_contenu'] : [$_GET['type_de_contenu']];
    foreach ($vals as $v) {
        $selectedContentTypes[] = $v;
    }
}

//print_r($_GET['type_de_contenu']);
// --------------------
// 2. Get categories
// --------------------

$q = mysql_query("SELECT profession_id FROM `list_professions` WHERE profession_id = 3");
$row = mysql_fetch_assoc($q);
$profession_id = (int) $row['profession_id'];

$top = mysql_query("
    SELECT * 
    FROM `list_services` 
    WHERE profession_id = {$profession_id} 
      AND master_id = 0 
    ORDER BY name ASC
");
?>

<div class="module custom-sidebar-search-filters">
    <form method="GET" id="bd-cat-filter-form">

        <!-- CONTENT TYPE -->
        <div class="module custom-sidebar-search-filters-inner">

            <div class="display_inline">
                <h3>Filters</h3>
                <button class="btn btn-sm" type="button" id="bd-filter-reset">
                    <strong>Reset</strong>
                </button>
            </div>

            <div class="category-group">
                <label class="category-view-switch-button">
                    <span class="custom-group-cat-title">Content Type</span>
                    
                </label>

                <div class="sub-cat-checkbox-container sub-sub">
                    <label>
                        <input type="checkbox"
                            name="type_de_contenu[]"
                            value="fiches_compltes"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("fiches_compltes", $selectedContentTypes)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">Complete fact sheets</span>
                    </label>

                    <label>
                        <input type="checkbox"
                            name="type_de_contenu[]"
                            value="avec_vido"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("avec_vido", $selectedContentTypes)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">With video</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="module custom-sidebar-search-filters-inner" data-pid="sub_marques">

            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Marques proposées</span>
            </label>

            <div class="sub-cat-checkbox-container sub-sub">
                <?php

                $marques = [
                    "70001" => "Aldes",
                    "70002" => "Atlantic",
                    "70003" => "Daikin",
                    "70004" => "Isover",
                    "70005" => "K-line",
                    "70006" => "Mitsubishi Electric",
                    "70007" => "Rockwool",
                    "70008" => "Saint-Gobain",
                    "70009" => "Thermor",
                    "70010" => "Velux"
                ];

                // read selected from URL
                $selectedBrandIds = isset($_GET['brands_list']) && is_array($_GET['brands_list'])
                    ? $_GET['brands_list']
                    : [];

                foreach ($marques as $id => $name) {
                    $checked = in_array($id, $selectedBrandIds) ? "checked" : "";
                ?>
                
                <label>
                    <input
                        type="checkbox"
                        name="brands_list[]" 
                        value="<?php echo $id; ?>"  
                        data-name="<?php echo htmlspecialchars($name); ?>" 
                        class="brand-check"
                        <?php echo $checked; ?>
                    >
                    <span class="checkbox-name-filter-category"><?php echo htmlspecialchars($name); ?></span>
                </label>

                <?php } ?>
            </div>
        </div>

        <!-- RGE STATUS (single block, no duplicates) -->
        <div class="module custom-sidebar-search-filters-inner">
            <div class="category-group" data-pid="rge-status">
                <label class="category-view-switch-button">
                    <span class="custom-group-cat-title">RGE Status</span>
                    
                </label>

                <div class="sub-cat-checkbox-container sub-sub">
                    <label>
                        <input type="checkbox"
                            name="rge_status[]"
                            value="oui"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("oui", $selectedRGE)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">YES</span>
                    </label>

                    <label>
                        <input type="checkbox"
                            name="rge_status[]"
                            value="non"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("non", $selectedRGE)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">NO</span>
                    </label>

                    <label>
                        <input type="checkbox"
                            name="rge_status[]"
                            value="soustraite"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("soustraite", $selectedRGE)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">Subcontract</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- LABELS & CERTIFICATIONS -->
        <div class="module custom-sidebar-search-filters-inner">
            <div class="category-group">
                <label class="category-view-switch-button">
                    <span class="custom-group-cat-title">Labels and Certifications</span>
                    
                </label>

                <div class="sub-cat-checkbox-container sub-sub">
                    <label>
                        <input type="checkbox"
                            name="labels_and_certifications[]"
                            value="france_rnov"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("france_rnov", $selectedLabels)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">France Renov'</span>
                    </label>

                    <label>
                        <input type="checkbox"
                            name="labels_and_certifications[]"
                            value="qualibat"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("qualibat", $selectedLabels)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">Qualibat</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- TYPE OF COMPANY -->
        <div class="module custom-sidebar-search-filters-inner">
            <div class="category-group">
                <label class="category-view-switch-button">
                    <span class="custom-group-cat-title">Type of company</span>
                    
                </label>

                <div class="sub-cat-checkbox-container sub-sub">
                    <label>
                        <input type="checkbox"
                            name="type_dentreprise[]"
                            value="design_office"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("design_office", $selectedCompanyTypes)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">Design office</span>
                    </label>

                    <label>
                        <input type="checkbox"
                            name="type_dentreprise[]"
                            value="advice_area"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("advice_area", $selectedCompanyTypes)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">Advice Area</span>
                    </label>

                    <label>
                        <input type="checkbox"
                            name="type_dentreprise[]"
                            value="public_body"
                            class="single-checkbox-filter sub"
                            <?php if (in_array("public_body", $selectedCompanyTypes)) echo 'checked="checked"'; ?>>
                        <span class="checkbox-name-filter-category">Public body</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- FILTERS HEADER + RESET -->
        <div class="module custom-sidebar-search-filters-inner">

            <!-- CATEGORY FILTERS -->
            <div class="search-filter-element categories-search-filters">
                <div class="category-group" data-pid="top3">
                    <div class="sub-cat-checkbox-container">

                        <!-- Hidden profession ID -->
                        <input type="checkbox"
                            name="sid"
                            class="hidden"
                            value="3"
                            checked="checked">

                        <?php while ($parent = mysql_fetch_assoc($top)) { ?>
                            <!-- PARENT CATEGORY -->
                            <div class="category-group" data-pid="sub<?php echo $parent['service_id']; ?>">

                                <label class="category-view-switch-button">
                                    <span class="custom-group-cat-title">
                                        <?php echo htmlspecialchars($parent['name']); ?>
                                    </span>
                                    
                                </label>

                                <?php
                                $sub = mysql_query("
                                    SELECT * 
                                    FROM `list_services` 
                                    WHERE master_id = " . (int) $parent['service_id'] . " 
                                    ORDER BY name ASC
                                ");
                                ?>

                                <div class="sub-cat-checkbox-container sub-sub">
                                    <?php while ($child = mysql_fetch_assoc($sub)) { ?>
                                        <label>
                                            <input type="checkbox"
                                                name="ttid[]"
                                                value="<?php echo (int) $child['service_id']; ?>"
                                                class="single-checkbox-filter sub"
                                                <?php if (in_array((int) $child['service_id'], $selectedSIDs)) echo 'checked="checked"'; ?>>

                                            <span class="checkbox-name-filter-category">
                                                <?php echo htmlspecialchars($child['name']); ?>
                                            </span>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("bd-cat-filter-form");
    if (!form) return;

    var checkboxes = form.querySelectorAll('input[type="checkbox"]:not(.hidden)');
    checkboxes.forEach(function (cb) {
        cb.addEventListener("change", function () {
            form.submit();
        });
    });

    var resetBtn = document.getElementById("bd-filter-reset");
    if (resetBtn) {
        resetBtn.addEventListener("click", function () {
            var baseUrl = window.location.pathname;
            window.location.href = baseUrl;
        });
    }
});
</script>
