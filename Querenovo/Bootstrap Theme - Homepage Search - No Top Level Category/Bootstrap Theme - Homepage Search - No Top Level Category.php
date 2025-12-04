<?php
if ($_GET['location_value'] != "") {
    $googleLocationValue = $_GET['location_value'];

} else if ($w['geocode_visitor_default'] == 1 && $w['geocode'] == 1 && $_SESSION['vdisplay'] != "") {
    $googleLocationValue = $_SESSION['vdisplay'];
}
if ($wa['custom_29'] == "horizontal") { ?>
    <div class="col-md-12 search_box fpad img-rounded">
        <?php if ($wa['custom_131'] != "") { ?>
            <h2 class="fpad bold nomargin sm-text-center">
                <?php echo $wa['custom_131']; ?>
            </h2>
        <?php } ?>
        <div class="clearfix"></div>
        <div class="form-group nomargin hidden-xs hidden-sm col-md-3">
            <label class="nomargin">
                Sélectionnez le département du projet
            </label>
        </div>
        <div class="form-group nomargin hidden-xs hidden-sm col-md-3 nolpad">
            <label class="nomargin">
                À quelle étape du projet êtes-vous ?
            </label>
        </div>
        <div class="form-group nomargin hidden-xs hidden-sm col-md-3 nolpad">
            <label class="nomargin">
                Choisissez votre métier
            </label>
        </div>
        <div class="clearfix"></div>
        <form class="fpad form-inline website-search" name="frm1" action="/<?php echo $w['default_search_url']; ?>">
            <div class="form-group col-xs-12 col-md-3 nolpad sm-norpad">
                <div class="input-group col-xs-12">
                    <select placeholder="Sélectionnez le département du projet" name="tid" id="bd-chained" class="form-control input-lg">
                        <option value=""></option>
                        <?php $topProfession = mysql($w['database'], "SELECT
                                profession_id
                            FROM
                                `list_professions`
                            LIMIT
                                1");
                        $prof = mysql_fetch_array($topProfession);
                        echo listServices(0, "list", $w, $prof['profession_id']); ?>
                    </select>
                </div>
            </div>
            <div class="form-group col-xs-12 col-md-3 nolpad sm-norpad">
                <div class="input-group col-xs-12">
                    <select placeholder="À quelle étape du projet êtes-vous ?" name="ttid" id="tid" class="form-control input-lg">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="form-group col-xs-12 col-md-3 nolpad sm-norpad">
                <div class="input-group input-group-lg col-xs-12">
                    <span class="input-group-addon"><i class="fa fa-fw fa-location-arrow"></i></span>
                    <input type="text" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage" value="<?php echo $googleLocationValue; ?>" placeholder="Choisissez votre métier">
                </div>
            </div>
            <div class="form-group col-xs-12 col-md-3 nopad nomargin">
                <button type="submit" class="btn btn-lg btn-block btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>

<?php } else { ?>
    <div class="col-xs-12 col-sm-12 col-md-6 search_box fpad img-rounded center-block">
        <?php
        if ($wa['custom_131'] != "") { ?>
            <h2 class="fpad bold nomargin sm-text-center">
                <?php echo $wa['custom_131']; ?>
            </h2>
        <?php } ?>
        <div class="clearfix"></div>
        <form class="fpad form-horizontal website-search" name="frm1" action="/<?php echo $w['default_search_url']; ?>">
            <div class="form-group nomargin hidden-sm hidden-xs">
                <label>
                    Sélectionnez le département du projet
                </label>
            </div>
            <div class="form-group nomargin bpad">
                <select placeholder="Sélectionnez le département du projet" name="tid" id="bd-chained" class="form-control input-lg">
                    <option value=""></option>
                    <?php $topProfession = mysql($w['database'], "SELECT
                            profession_id
                        FROM
                            `list_professions`
                        LIMIT
                            1");
                    $prof = mysql_fetch_array($topProfession);
                    echo listServices(0, "list", $w, $prof['profession_id']); ?>
                </select>
            </div>
            <div class="form-group nomargin hidden-sm hidden-xs tpad">
                <label>
                    À quelle étape du projet êtes-vous ?
                </label>
            </div>
            <div class="form-group nomargin bpad">
                <select placeholder="À quelle étape du projet êtes-vous ?" name="ttid" id="tid" class="form-control input-lg">
                    <option></option>
                </select>
            </div>
            <div class="form-group nomargin hidden-sm hidden-xs tpad">
                <label>
                    Choisissez votre métier
                </label>
            </div>
            <div class="form-group nomargin">
                <input type="text" placeholder="Choisissez votre métier" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage" value="<?php echo $googleLocationValue; ?>">
            </div>
            <div class="col-md-12 nopad tmargin">
                <button type="submit" class="btn btn-lg btn-block btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } ?>
