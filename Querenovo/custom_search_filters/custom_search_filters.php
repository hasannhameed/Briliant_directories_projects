<?php
// Capture selected SIDs
$selectedSIDs = array();
if (!empty($_GET['sid']) && is_array($_GET['sid'])) {
    foreach ($_GET['sid'] as $sid) {
        if (is_numeric($sid)) $selectedSIDs[] = (int)$sid;
    }
}
?>

<form method="GET" id="bd-cat-filter-form">

<div class="module custom-sidebar-search-filters" bis_skin_checked="1">
    <h3><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">Filter by category</font></font></h3>
    <div class="search-filter-element categories-search-filters" bis_skin_checked="1">

        <div class="category-group" data-pid="top3" bis_skin_checked="1">
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title"><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">
                    business
                </font></font></span>
                <i class="fa fa-minus" aria-hidden="true"></i>
            </label>

            <div class="sub-cat-checkbox-container" bis_skin_checked="1">

                <div class="category-group" data-pid="sub49389" bis_skin_checked="1">
                    <label class="category-view-switch-button">
                        <span class="custom-group-cat-title"><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">
                            I have my work done
                        </font></font></span>
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </label>

                    <div class="sub-cat-checkbox-container sub-sub" bis_skin_checked="1">

                        <label>
                            <input type="checkbox" name="sid[]" value="49405" class="single-checkbox-filter sub"
                                <?php if(in_array(49405, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                <font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">
                                    Other work
                                </font></font>
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49400" class="single-checkbox-filter sub"
                                <?php if(in_array(49400, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Joinery (doors, windows, shutters),
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49404" class="single-checkbox-filter sub"
                                <?php if(in_array(49404, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Photovoltaic electricity production,
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49398" class="single-checkbox-filter sub"
                                <?php if(in_array(49398, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Comprehensive, turnkey project
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49402" class="single-checkbox-filter sub"
                                <?php if(in_array(49402, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Heating system
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49403" class="single-checkbox-filter sub"
                                <?php if(in_array(49403, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Domestic hot water production system,
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49399" class="single-checkbox-filter sub"
                                <?php if(in_array(49399, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Insulation work (walls, ceilings, floors),
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49401" class="single-checkbox-filter sub"
                                <?php if(in_array(49401, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Ventilation / Mechanical Ventilation,
                            </span>
                        </label>

                    </div>
                </div>

                <div class="category-group" data-pid="sub49388" bis_skin_checked="1">
                    <label class="category-view-switch-button">
                        <span class="custom-group-cat-title">
                            I am preparing my project.
                        </span>
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </label>

                    <div class="sub-cat-checkbox-container sub-sub" bis_skin_checked="1">

                        <label>
                            <input type="checkbox" name="sid[]" value="49395" class="single-checkbox-filter sub"
                                <?php if(in_array(49395, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Architects - Project Managers - Project Management Assistance
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49394" class="single-checkbox-filter sub"
                                <?php if(in_array(49394, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Energy audits and diagnostics (DPE),
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49392" class="single-checkbox-filter sub"
                                <?php if(in_array(49392, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Pre-project advice - France Renov Advice Centers (ECFR),
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49396" class="single-checkbox-filter sub"
                                <?php if(in_array(49396, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Experts and various studies,
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49393" class="single-checkbox-filter sub"
                                <?php if(in_array(49393, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                My Approved Renovation Companion (MAR),
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49397" class="single-checkbox-filter sub"
                                <?php if(in_array(49397, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Financing solutions
                            </span>
                        </label>

                    </div>
                </div>

            </div>
        </div>

        <!-- TOP LEVEL: brand -->
        <div class="category-group" data-pid="top4" bis_skin_checked="1">
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">brand</span>
                <i class="fa fa-minus" aria-hidden="true"></i>
            </label>

            <div class="sub-cat-checkbox-container" bis_skin_checked="1">

                <div class="category-group" data-pid="sub49391" bis_skin_checked="1">
                    <label class="category-view-switch-button">
                        <span class="custom-group-cat-title">
                            I have my work done
                        </span>
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </label>

                    <div class="sub-cat-checkbox-container sub-sub" bis_skin_checked="1">

                        <label>
                            <input type="checkbox" name="sid[]" value="49413" class="single-checkbox-filter sub"
                                <?php if(in_array(49413, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Software/High-Tech Solutions
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49412" class="single-checkbox-filter sub"
                                <?php if(in_array(49412, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Software / High-tech
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49411" class="single-checkbox-filter sub"
                                <?php if(in_array(49411, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Joinery
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49410" class="single-checkbox-filter sub"
                                <?php if(in_array(49410, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Electricity production
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49407" class="single-checkbox-filter sub"
                                <?php if(in_array(49407, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Insulation solutions
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49408" class="single-checkbox-filter sub"
                                <?php if(in_array(49408, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Heating System
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49409" class="single-checkbox-filter sub"
                                <?php if(in_array(49409, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Ventilation / Mechanical Ventilation
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49406" class="single-checkbox-filter sub"
                                <?php if(in_array(49406, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                See all
                            </span>
                        </label>

                    </div>
                </div>

                <div class="category-group" data-pid="sub49390" bis_skin_checked="1">
                    <label class="category-view-switch-button">
                        <span class="custom-group-cat-title">
                            I am preparing my project.
                        </span>
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </label>

                    <div class="sub-cat-checkbox-container sub-sub" bis_skin_checked="1">

                        <label>
                            <input type="checkbox" name="sid[]" value="49416" class="single-checkbox-filter sub"
                                <?php if(in_array(49416, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Innovations/discovery
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49418" class="single-checkbox-filter sub"
                                <?php if(in_array(49418, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Bio-based materials and the circular economy
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49417" class="single-checkbox-filter sub"
                                <?php if(in_array(49417, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Equipment and tools
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49414" class="single-checkbox-filter sub"
                                <?php if(in_array(49414, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Training organizations
                            </span>
                        </label>

                        <label>
                            <input type="checkbox" name="sid[]" value="49415" class="single-checkbox-filter sub"
                                <?php if(in_array(49415, $selectedSIDs)) echo "checked"; ?>>
                            <span class="checkbox-name-filter-category">
                                Distribution networks
                            </span>
                        </label>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

</form>

<script>
document.querySelectorAll('input[name="sid[]"]').forEach(function (cb) {
    cb.addEventListener("change", function () {
        document.getElementById("bd-cat-filter-form").submit();
    });
});
</script>
