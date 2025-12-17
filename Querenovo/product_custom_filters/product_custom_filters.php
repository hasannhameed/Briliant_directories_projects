<style>
<?php if($pars[0]=='products'){ ?>
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
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
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
<?php } ?>
<?php if( $pars[0] == 'videos' ){ ?>
    .sub-cat-checkbox-container{
        display: flex;
        justify-content: space-between;
    }
    
    .sub-cat-checkbox-container label{
        width: 70%;
    }
    .sub-cat-checkbox-container :has(.custom-btn2){
        display: inline-flex;

    }
    .sub-cat-checkbox-container :has(.custom-btn2) input{
        width: 70%;
    }
    .sub-cat-checkbox-container :has(.custom-btn2) button{
        width:15%;
    }
<?php } ?>
</style>

<div class="module2">
    <form method="get" action="">
        <div class="custom-sidebar-search-filters-inner">

        <?php if($pars[0] == 'products'){ ?>
            <!-- CATÉGORIES -->
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Catégories</span>
            </label>
            <div class="sub-cat-checkbox-container sub-sub">
                <label>
                    <input type="checkbox" name="group_category[]" value="Underfloor heating">
                    <span class="checkbox-name-filter-category">Chauffage par le sol</span>
                </label>
                <label>
                    <input type="checkbox" name="group_category[]" value="Cassette for heating">
                    <span class="checkbox-name-filter-category">Cassette pour chauffage</span>
                </label>
            </div>

            <!-- FABRICANTS -->
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Fabricants</span>
            </label>
            <div class="sub-cat-checkbox-container sub-sub">
                <label>
                    <input type="checkbox" name="manufacturers[]" value="ao_smith">
                    <span class="checkbox-name-filter-category">AO SMITH</span>
                </label>
                <label>
                    <input type="checkbox" name="manufacturers[]" value="chemelex_raychem">
                    <span class="checkbox-name-filter-category">Chemelex Raychem</span>
                </label>
                <label>
                    <input type="checkbox" name="manufacturers[]" value="flktgroup">
                    <span class="checkbox-name-filter-category">FläktGroup</span>
                </label>
                <label>
                    <input type="checkbox" name="manufacturers[]" value="my_infrared_heater">
                    <span class="checkbox-name-filter-category">Mon Chauffage Infrarouge</span>
                </label>
                <label>
                    <input type="checkbox" name="manufacturers[]" value="poujoulat">
                    <span class="checkbox-name-filter-category">Poujoulat</span>
                </label>
                <label>
                    <input type="checkbox" name="manufacturers[]" value="resideo">
                    <span class="checkbox-name-filter-category">Resideo</span>
                </label>
                <label>
                    <input type="checkbox" name="manufacturers[]" value="salus_controls">
                    <span class="checkbox-name-filter-category">Salus Controls</span>
                </label>
            </div>

            <!-- PRODUITS -->
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Produits</span>
            </label>
            <div class="sub-cat-checkbox-container sub-sub">
                <label>
                    <input type="checkbox" name="has_video" value="1">
                    <span class="checkbox-name-filter-category">Avec vidéo</span>
                </label>
                <label>
                    <input type="checkbox" name="has_product_link" value="1">
                    <span class="checkbox-name-filter-category">Demande en ligne</span>
                </label>
            </div>

            <!-- MARCHÉS D’APPLICATION -->
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Marchés d’application</span>
            </label>
            <div class="sub-cat-checkbox-container sub-sub">
                <label>
                    <input type="checkbox" name="application_markets[]" value="commerce">
                    <span class="checkbox-name-filter-category">Commerce</span>
                </label>
                <label>
                    <input type="checkbox" name="application_markets[]" value="education_and_culture">
                    <span class="checkbox-name-filter-category">Enseignement et culture</span>
                </label>
                <label>
                    <input type="checkbox" name="application_markets[]" value="hotel_and_restaurant">
                    <span class="checkbox-name-filter-category">Hôtel et restaurant</span>
                </label>
                <label>
                    <input type="checkbox" name="application_markets[]" value="industry_and_logistics">
                    <span class="checkbox-name-filter-category">Industrie et logistique</span>
                </label>
                <label>
                    <input type="checkbox" name="application_markets[]" value="collective_housing">
                    <span class="checkbox-name-filter-category">Logement collectif</span>
                </label>
                <label>
                    <input type="checkbox" name="application_markets[]" value="detached_house">
                    <span class="checkbox-name-filter-category">Maison individuelle</span>
                </label>
                <label>
                    <input type="checkbox" name="application_markets[]" value="health">
                    <span class="checkbox-name-filter-category">Santé</span>
                </label>
                <label>
                    <input type="checkbox" name="application_markets[]" value="sport_and_leisure">
                    <span class="checkbox-name-filter-category">Sport et loisirs</span>
                </label>
                <label>
                    <input type="checkbox" name="application_markets[]" value="tertiary">
                    <span class="checkbox-name-filter-category">Tertiaire</span>
                </label>
            </div>

            <?php } ?>

            <!-- RECHERCHE PAR MOT-CLÉ -->
              <?php if($pars[0] != 'videos'){ ?>
            <label class="category-view-switch-button">
                <span class="custom-group-cat-title">Recherche par mot-clé</span>
            </label>
            <?php } ?>
            <div class="sub-cat-checkbox-container sub-sub">
                <label>
                    <input type="text" class="form-control" name="q" value="" placeholder="Recherche par mot-clé"> 
                    <?php if($pars[0]=='videos'){ ?>
                        &nbsp;&nbsp;&nbsp; 
                        <button class="btn btn-primary btn-block custom-btn custom-btn2">To research</button>
                    <?php } ?>
                </label>
                 <?php if($pars[0]=='products'){ ?>
                <div class="form-group nomargin" bis_skin_checked="1">
                    <button type="submit" class="btn btn-primary btn-block custom-btn"><font dir="auto" style="vertical-align: inherit;"><font dir="auto" style="vertical-align: inherit;">To research</font></font></button>
                </div>
                <?php } ?>
                <?php if($pars[0]=='videos'){ ?>

                    <div class="custom-combobox" data-state="closed">
                        <button type="button"
                            class="form-control combobox-trigger"
                            role="combobox"
                            aria-expanded="false"
                            aria-autocomplete="none">
                            
                            <span class="combobox-label">
                                <span class='combobox-label'>
                                    <span class="combobox-icon">
                                        <!-- Calendar Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            viewBox="0 0 24 24">
                                            <path d="M8 2v4"></path>
                                            <path d="M16 2v4"></path>
                                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                            <path d="M3 10h18"></path>
                                        </svg>
                                    </span>
                                    <span class="combobox-text">Plus récentes</span>
                                </span>
                                <span class="combobox-arrow">
                                    <!-- Chevron -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="m6 9 6 6 6-6"></path>
                                    </svg>
                                </span>
                            </span>

                            <!-- <span class="combobox-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    viewBox="0 0 24 24">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </span> -->

                        </button>

                        <div class="combobox-dropdown">
                            <div class="combobox-option" data-value="recent">Plus récentes</div>
                            <div class="combobox-option" data-value="oldest">Plus anciennes</div>
                            <div class="combobox-option" data-value="views">Plus vues</div>
                            <div class="combobox-option" data-value="likes">Plus aimées</div>
                            <div class="combobox-option" data-value="short">Plus courtes</div>
                            <div class="combobox-option" data-value="long">Plus longues</div>
                        </div>

                    </div>
                    <style>
                        .custom-combobox {
                            position: relative;
                            width: 100%;
                            max-width: 260px;
                            font-family: inherit;
                        }
                        .custom-btn{
                            height: 36px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
                        /* .combobox-trigger {
                            width: 100%;
                            height: 48px;
                            padding: 0 12px;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            border: 1px solid #e5e7eb;
                            border-radius: 10px;
                            background: #fff;
                            font-size: 14px;
                            cursor: pointer;
                            box-shadow: 0 1px 2px rgba(0,0,0,.05);
                        } */

                        .combobox-trigger:focus {
                            outline: none;
                            border-color: #2563eb;
                            box-shadow: 0 0 0 1px #2563eb;
                        }

                        .combobox-label {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            justify-content: space-between;  
                        }

                        .combobox-icon {
                            display: flex;
                            align-items: center;
                            color: #555;
                        }

                        .combobox-text {
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        }

                        .combobox-arrow {
                            color: #888;
                        }

                        .combobox-dropdown {
                            position: absolute;
                            top: calc(100% + 6px);
                            left: 0;
                            width: 100%;
                            background: #fff;
                            border: 1px solid #e5e7eb;
                            border-radius: 10px;
                            box-shadow: 0 10px 25px rgba(0,0,0,.08);
                            display: none;
                            z-index: 999;
                        }

                        .custom-combobox[data-state="open"] .combobox-dropdown {
                            display: block;
                        }

                        .combobox-option {
                            padding: 10px 14px;
                            font-size: 14px;
                            cursor: pointer;
                        }

                        .combobox-option:hover {
                            background: #f5f7fa;
                        }

                    </style>
                    <script>
                        (function () {
                            const combo = document.querySelector('.custom-combobox');
                            const trigger = combo.querySelector('.combobox-trigger');
                            const dropdown = combo.querySelector('.combobox-dropdown');
                            const label = combo.querySelector('.combobox-text');

                            trigger.addEventListener('click', function (e) {
                                e.stopPropagation();
                                const isOpen = combo.getAttribute('data-state') === 'open';
                                combo.setAttribute('data-state', isOpen ? 'closed' : 'open');
                                trigger.setAttribute('aria-expanded', !isOpen);
                            });

                            dropdown.querySelectorAll('.combobox-option').forEach(option => {
                                option.addEventListener('click', function () {
                                    label.textContent = this.textContent;
                                    combo.setAttribute('data-state', 'closed');
                                    trigger.setAttribute('aria-expanded', false);

                                    console.log('Selected:', this.dataset.value);
                                });
                            });

                            document.addEventListener('click', function () {
                                combo.setAttribute('data-state', 'closed');
                                trigger.setAttribute('aria-expanded', false);
                            });
                        })();
                    </script>

                <?php } ?>
            </div>
        </div>
    </form>
</div>
<script>
(function () {

    // Parse URL params safely
    const params = new URLSearchParams(window.location.search);

    // Handle array-based checkboxes
    function restoreArray(name) {
        const values = params.getAll(name + '[]');
        if (!values.length) return;

        document.querySelectorAll('input[name="' + name + '[]"]').forEach(function (el) {
            if (values.indexOf(el.value) !== -1) {
                el.checked = true;
            }
        });
    }

    // Handle single checkbox
    function restoreSingle(name) {
        if (!params.has(name)) return;

        document.querySelectorAll('input[name="' + name + '"]').forEach(function (el) {
            el.checked = true;
        });
    }

    // Handle text input
    function restoreInput(name) {
        if (!params.has(name)) return;

        const el = document.querySelector('input[name="' + name + '"]');
        if (el) {
            el.value = params.get(name);
        }
    }

    // RESTORE ALL
    restoreArray('group_category');
    restoreArray('manufacturers');
    restoreArray('application_markets');

    restoreSingle('has_video');
    restoreSingle('has_product_link');

    restoreInput('q');

})();
</script>
