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


$selectedSIDs = [];
if (!empty($_GET['tid'])) {
    foreach ($_GET['tid'] as $tid) {
        if (is_numeric($tid)) $selectedSIDs[] = (int)$tid;
    }
}


$q = mysql_query("SELECT profession_id FROM `list_professions` WHERE profession_id = 3");
$row = mysql_fetch_assoc($q);
$profession_id = $row['profession_id'];


$top = mysql_query("SELECT * FROM `list_services` WHERE profession_id = $profession_id AND master_id = 0 ORDER BY name ASC");

?>
<div class="module custom-sidebar-search-filters">
    <form method="GET" id="bd-cat-filter-form">

        <div class="module custom-sidebar-search-filters-inner" bis_skin_checked="1">
            <div class="display_inline">
                    <h3>Filtres</h3>
                    <button class=' btn btn-sm' type='button'>
                <bold>Réinitialiser</bold></button>
            </div>
            <div class="search-filter-element categories-search-filters" bis_skin_checked="1">

                <div class="category-group" data-pid="top3" bis_skin_checked="1">
                    <div class="sub-cat-checkbox-container" bis_skin_checked="1">

                        <input type="checkbox"
                            name="sid"
                            type="hidden"
                            class="hidden"
                            value='3'>

                        <?php while ($parent = mysql_fetch_assoc($top)) { ?>

                            <!-- PARENT CATEGORY -->
                            <div class="category-group" data-pid="sub<?php echo $parent['service_id']; ?>" bis_skin_checked="1">

                                <label class="category-view-switch-button">
                                    <span class="custom-group-cat-title">
                                        <?php echo $parent['name']; ?>
                                    </span>
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </label>

                                <?php
                                
                                $sub = mysql_query("SELECT * FROM `list_services` WHERE master_id = " . $parent['service_id'] . " ORDER BY name ASC");
                                ?>

                                <div class="sub-cat-checkbox-container sub-sub" bis_skin_checked="1">

                                    <?php while ($child = mysql_fetch_assoc($sub)) { ?>

                                        <label>
                                            <input type="checkbox"
                                                name="tid[]"
                                                value="<?php echo $child['service_id']; ?>"
                                                class="single-checkbox-filter sub"
                                                <?php if (in_array($child['service_id'], $selectedSIDs)) echo "checked"; ?>>

                                            <span class="checkbox-name-filter-category">
                                                <?php echo $child['name']; ?>
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

        <div class="module custom-sidebar-search-filters-inner" bis_skin_checked="1">
            <div class="category-group" data-pid="rge-status">
                <label class="category-view-switch-button">
                    <span class="custom-group-cat-title">
                        RGE Status
                    </span>
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </label>

                <div class="sub-cat-checkbox-container sub-sub">
                    <label>
                        <input type="checkbox"
                            name="rge_status[]"
                            value="oui"
                            class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">YES</span>
                    </label>

                    <label>
                        <input type="checkbox"
                            name="rge_status[]"
                            value="non"
                            class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">NO</span>
                    </label>

                    <label>
                        <input type="checkbox"
                            name="rge_status[]"
                            value="soustraite"
                            class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">Subcontract</span>
                    </label>

                </div>
            </div>
        </div>
        <div class="module custom-sidebar-search-filters-inner" bis_skin_checked="1">
            <div class="category-group">
                <label class="category-view-switch-button">
                    <span class="custom-group-cat-title">Labels and Certifications</span>
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </label>

                <div class="sub-cat-checkbox-container sub-sub">

                    <label>
                        <input type="checkbox" name="labels_and_certifications[]" value="france_rnov" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">France Renov'</span>
                    </label>

                    <label>
                        <input type="checkbox" name="labels_and_certifications[]" value="qualibat" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">Qualibat</span>
                    </label>

                </div>
            </div>
        </div>
        <div class="module custom-sidebar-search-filters-inner" bis_skin_checked="1">
            <div class="category-group">
                <label class="category-view-switch-button">
                    <span class="custom-group-cat-title">Type of company</span>
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </label>

                <div class="sub-cat-checkbox-container sub-sub">

                    <label>
                        <input type="checkbox" name="type_dentreprise[]" value="design_office" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">Design office</span>
                    </label>

                    <label>
                        <input type="checkbox" name="type_dentreprise[]" value="advice_area" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">Advice Area</span>
                    </label>

                    <label>
                        <input type="checkbox" name="type_dentreprise[]" value="public_body" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">Public body</span>
                    </label>

                </div>
            </div>
        </div>
        <div class="module custom-sidebar-search-filters-inner" bis_skin_checked="1">
            <div class="category-group">
                <label class="category-view-switch-button">
                    <span class="custom-group-cat-title">RGE Status</span>
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </label>

                <div class="sub-cat-checkbox-container sub-sub">

                    <label>
                        <input type="checkbox" name="rge_status[]" value="oui" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">YES</span>
                    </label>

                    <label>
                        <input type="checkbox" name="rge_status[]" value="non" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">NO</span>
                    </label>

                    <label>
                        <input type="checkbox" name="rge_status[]" value="soustraite" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">Subcontract</span>
                    </label>

                </div>
            </div>
        </div>
        <div class="module custom-sidebar-search-filters-inner" bis_skin_checked="1">
            <div class="category-group">
                <label class="category-view-switch-button">
                    <span class="custom-group-cat-title">Content Type</span>
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </label>

                <div class="sub-cat-checkbox-container sub-sub">

                    <label>
                        <input type="checkbox" name="type_de_contenu[]" value="fiches_compltes" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">Complete fact sheets</span>
                    </label>

                    <label>
                        <input type="checkbox" name="type_de_contenu[]" value="avec_vido" class="single-checkbox-filter sub">
                        <span class="checkbox-name-filter-category">With video</span>
                    </label>

                </div>
            </div>
        </div>
</div>

</form>

<script>
document.querySelectorAll('input[name="tid[]"], input[name="rge_status[]"]').forEach(function(cb) {
    cb.addEventListener("change", function() {
        document.getElementById("bd-cat-filter-form").submit();
    });
});


</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const params = new URLSearchParams(window.location.search);

  
  const rge = params.get("rge_status");
  if (rge) {
    const select = document.querySelector('select[name="rge_status"]');
    if (select) select.value = rge;
  }

  
  const labelCert = params.get("labels_and_certifications");
  if (labelCert) {
    const sel2 = document.querySelector('select[name="labels_and_certifications"]');
    if (sel2) sel2.value = labelCert;
  }
  const typeEnt = params.get("type_dentreprise");
  if (typeEnt) {
    const sel3 = document.querySelector('select[name="type_dentreprise"]');
    if (sel3) sel3.value = typeEnt;
  }
  const contentType = params.get("type_de_contenu");
  if (contentType) {
    const sel4 = document.querySelector('select[name="type_de_contenu"]');
    if (sel4) sel4.value = contentType;
  }

  
  const rgeList = params.getAll("rge_status[]");
  rgeList.forEach(function(v) {
    const cb = document.querySelector('input[name="rge_status[]"][value="' + v + '"]');
    if (cb) cb.checked = true;
  });

  
  
});
document.querySelector("button[type='button']").addEventListener("click", function () {
    document.querySelectorAll("#bd-cat-filter-form input[type='checkbox']").forEach(cb => {
        cb.checked = false;
    });
});

</script>
