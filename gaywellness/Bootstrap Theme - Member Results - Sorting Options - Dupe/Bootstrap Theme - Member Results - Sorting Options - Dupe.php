<?php
if ($_ENV['total'] > 0) {
    $sortingStart = displayNumberFormat($_ENV['start']);
    $sortingEnd = displayNumberFormat($_ENV['end']);
    $sortingTotal = displayNumberFormat($_ENV['total']);
    ?>

    <div class="col-sm-4 nopad bmargin font-size-zero">
        %%%showing_sorting_label%%% <?php echo $sortingStart; ?>
        - <span class="current__amount__js"><?php echo $sortingEnd ?></span> %%%of_sorting_label%%%

        <?php if (intval($_ENV['total']) == 10000) {
            ?>10,000+ %%result%%;

        <?php } else {
            echo   '<span class="total__js">' . $sortingTotal . '</span>'; ?> %%Result%%
        <?php } ?>
    </div>
    <div class="col-sm-8 nopad text-right bmargin">
        <div class="form-inline">
            <?php
            $addonFilterStarRating = getAddOnInfo("filter_by_star_rating", "a70164a8f96fe39cb552139ed39ccfb8");
            if (isset($addonFilterStarRating['status']) && $addonFilterStarRating['status'] === 'success' && $wa['display-filter-star-rating'] == 1) {
                echo widget($addonFilterStarRating['widget'], '', $w['website_id'], $w);
            }
            ?>
            <div class="form-group sort-members-select">
                <select class="form-control pull-right input-sm" name=sort
                        onchange="window.location='<?php echo str_replace("sort=", "", $REQUEST_URI);
                        if (!strstr($REQUEST_URI, '?')) {
                            echo '?';
                        } else {
                            echo '&';
                        } ?>sort='+this.value;" id="sortbox">
                    <option value="" <?php if ($_GET['sort'] == "") { ?>selected<?php } ?>>%%%default_sort_label%%%
                    </option>

                    <?php if ($_GET['search'] == "zip-code" || $_COOKIE['sort'] == "distance" || ($city['LAT'] != "" && $city['LONG'] != "")) { ?>
                        <option value="distance" selected=selected>%%%closest_location_sort_label%%%</option>
                    <?php }
                    if (!empty($label['most_reviews_sort_label'])) { ?>
                        <option value="reviews" <?php if ($_GET['sort'] == "reviews") { ?>selected<?php } ?>>
                            %%%most_reviews_sort_label%%%
                        </option>
                    <?php }
                    if (!empty($label['az_sort_label'])) { ?>
                        <option value="name ASC" <?php if ($_GET['sort'] == "name ASC") { ?>selected<?php } ?>>
                            %%%az_sort_label%%%
                        </option>
                    <?php }
                    if (!empty($label['za_sort_label'])) { ?>
                        <option value="name DESC" <?php if ($_GET['sort'] == "name DESC") { ?>selected<?php } ?>>
                            %%%za_sort_label%%%
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <?php
                $addOnWidget = '';
                $gridViewAddOn = getAddOnInfo('grid_view_search_reults', '769e3e86f08b2d05aaabb1e555221b87');
                $mapViewAddOn = getAddOnInfo('google_map_search_results', 'ccda1a004e20781cca3712ec22a57434');

                if (isset($gridViewAddOn['status']) && $gridViewAddOn['status'] == 'success') {
                    $addOnWidget = $gridViewAddOn['widget'];
                }
                if (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success') {
                    $addOnWidget = $mapViewAddOn['widget'];
                }
                if ((isset($gridViewAddOn['status']) && $gridViewAddOn['status'] == 'success') || (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success')) {
                    echo widget($addOnWidget, "", $w['website_id'], $w);
                } ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
<?php } ?>