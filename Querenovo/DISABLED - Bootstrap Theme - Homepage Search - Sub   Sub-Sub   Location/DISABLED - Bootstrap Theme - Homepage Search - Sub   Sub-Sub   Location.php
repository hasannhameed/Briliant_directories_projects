<div class="sub-text"> 
    Trouvez les professionnels de la rénovation énergétique pour tous types de bâtiments : logements, tertiaire, industrie, collectivités  
</div>

<?php if ($wa['custom_29'] == "horizontal") { ?>

    <div class="col-md-12 search_box fpad img-rounded">
        <?php if ($wa['custom_131'] != "") { ?>
            <h2 class="fpad bold nomargin sm-text-center">
                <?php echo $wa['custom_131'];?>
            </h2>
        <?php } ?>

        <div class="clearfix"></div>
		<div class="q">
			<input type="text" class="infinite-chained form-control input-lg" placeholder="Rechercher un professionnel, une marque, un produit..."> 

		</div>

        <div class="hpad">
            <div class="form-group nomargin hidden-xs hidden-sm col-md-4 nolpad place">
                <label>%%%home_search_dropdown_2%%%</label>
            </div>
            <div class="form-group nomargin hidden-xs hidden-sm col-md-4 nolpad">
                <label>%%%more_options_label%%%</label>
            </div>
            <div class="form-group nomargin hidden-xs hidden-sm col-md-4 nolpad">
                <label>%%%home_search_dropdown_3%%%</label>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="clearfix"></div>

        <form class="fpad notpad website-search" name="frm1" action="/<?php echo $w['default_search_url'];?>">

            <div class="form-group col-xs-12 col-md-4 nolpad sm-norpad">
                <select placeholder="%%%home_search_default_2%%%" name="tid" id="tid" next="ttid" class="infinite-chained form-control input-lg">
                    <option></option>
                    <?php
                        $topProfession = mysql($w['database'],"SELECT profession_id FROM `list_professions` LIMIT 1");
                        $prof = mysql_fetch_array($topProfession);
                        echo listServices(0,"list",$w,$prof['profession_id'],0,$w['fast_search']);
                    ?>
                </select>
            </div>

            <div class="form-group col-xs-12 col-md-4 nolpad sm-norpad">
                <select placeholder="%%%more_options_placeholder%%%" name="ttid" id="ttid" class="infinite-chained form-control input-lg">
                    <option></option>
                </select>
            </div>

            <div class="form-group col-xs-12 col-md-4 nolpad sm-norpad">
                <div class="input-group input-group-lg col-sm-12">
                    <select id="department" class="infinite-chained form-control input-lg select2-preload" name="department_code" style="width:100%;" placeholder="%%%location_search_default%%%">
                        <option value="">Select Department</option>
                        <!-- French Department Options (unchanged) -->
                        <option value="01">Ain</option>
                        <option value="02">Aisne</option>
                        <option value="03">Allier</option>
                        <option value="04">Alpes-de-Haute-Provence</option>
                        <option value="05">Hautes-Alpes</option>
                        <option value="06">Alpes-Maritimes</option>
                        <option value="07">Ardèche</option>
                        <option value="08">Ardennes</option>
                        <option value="09">Ariège</option>
                        <option value="10">Aube</option>
                        <option value="11">Aude</option>
                        <option value="12">Aveyron</option>
                        <option value="13">Bouches-du-Rhône</option>
                        <option value="14">Calvados</option>
                        <option value="15">Cantal</option>
                        <option value="16">Charente</option>
                        <option value="17">Charente-Maritime</option>
                        <option value="18">Cher</option>
                        <option value="19">Corrèze</option>
                        <option value="2A">Corse-du-Sud</option>
                        <option value="2B">Haute-Corse</option>
                        <option value="21">Côte-d’Or</option>
                        <option value="22">Côtes-d’Armor</option>
                        <option value="23">Creuse</option>
                        <option value="24">Dordogne</option>
                        <option value="25">Doubs</option>
                        <option value="26">Drôme</option>
                        <option value="27">Eure</option>
                        <option value="28">Eure-et-Loir</option>
                        <option value="29">Finistère</option>
                        <option value="30">Gard</option>
                        <option value="31">Haute-Garonne</option>
                        <option value="32">Gers</option>
                        <option value="33">Gironde</option>
                        <option value="34">Hérault</option>
                        <option value="35">Ille-et-Vilaine</option>
                        <option value="36">Indre</option>
                        <option value="37">Indre-et-Loire</option>
                        <option value="38">Isère</option>
                        <option value="39">Jura</option>
                        <option value="40">Landes</option>
                        <option value="41">Loir-et-Cher</option>
                        <option value="42">Loire</option>
                        <option value="43">Haute-Loire</option>
                        <option value="44">Loire-Atlantique</option>
                        <option value="45">Loiret</option>
                        <option value="46">Lot</option>
                        <option value="47">Lot-et-Garonne</option>
                        <option value="48">Lozère</option>
                        <option value="49">Maine-et-Loire</option>
                        <option value="50">Manche</option>
                        <option value="51">Marne</option>
                        <option value="52">Haute-Marne</option>
                        <option value="53">Mayenne</option>
                        <option value="54">Meurthe-et-Moselle</option>
                        <option value="55">Meuse</option>
                        <option value="56">Morbihan</option>
                        <option value="57">Moselle</option>
                        <option value="58">Nièvre</option>
                        <option value="59">Nord</option>
                        <option value="60">Oise</option>
                        <option value="61">Orne</option>
                        <option value="62">Pas-de-Calais</option>
                        <option value="63">Puy-de-Dôme</option>
                        <option value="64">Pyrénées-Atlantiques</option>
                        <option value="65">Hautes-Pyrénées</option>
                        <option value="66">Pyrénées-Orientales</option>
                        <option value="67">Bas-Rhin</option>
                        <option value="68">Haut-Rhin</option>
                        <option value="69">Rhône</option>
                        <option value="70">Haute-Saône</option>
                        <option value="71">Saône-et-Loire</option>
                        <option value="72">Sarthe</option>
                        <option value="73">Savoie</option>
                        <option value="74">Haute-Savoie</option>
                        <option value="75">Paris</option>
                        <option value="76">Seine-Maritime</option>
                        <option value="77">Seine-et-Marne</option>
                        <option value="78">Yvelines</option>
                        <option value="79">Deux-Sèvres</option>
                        <option value="80">Somme</option>
                        <option value="81">Tarn</option>
                        <option value="82">Tarn-et-Garonne</option>
                        <option value="83">Var</option>
                        <option value="84">Vaucluse</option>
                        <option value="85">Vendée</option>
                        <option value="86">Vienne</option>
                        <option value="87">Haute-Vienne</option>
                        <option value="88">Vosges</option>
                        <option value="89">Yonne</option>
                        <option value="90">Territoire de Belfort</option>
                        <option value="91">Essonne</option>
                        <option value="92">Hauts-de-Seine</option>
                        <option value="93">Seine-Saint-Denis</option>
                        <option value="94">Val-de-Marne</option>
                        <option value="95">Val-d’Oise</option>
                    </select>
                </div>
            </div>

            
            <div class="form-group col-xs-12 nopad nomargin">
                <button type="submit" class="btn-block tmargin btn btn-lg btn_home_search">%%%home_search_submit%%%</button>
            </div>

            <div class="clearfix"></div>
        </form>

        <div class="clearfix"></div>
    </div>

<?php } else { ?>

    <div class="col-xs-12 col-sm-12 col-md-6 search_box fpad img-rounded center-block">
        <?php if ($wa['custom_131']!="") { ?>
            <h2 class="fpad bold nomargin sm-text-center"><?php echo $wa['custom_131'];?></h2>
        <?php } ?>

        <div class="clearfix"></div>

        <form class="fpad form-horizontal website-search" name="frm1" action="/<?=$w['default_search_url']?>">

            <div class="form-group nomargin hidden-sm hidden-xs tpad">
                <label>%%%home_search_dropdown_2%%%</label>
            </div>

            <div class="form-group nomargin bpad">
                <select placeholder="%%%home_search_default_2%%%" name="tid" id="tid" next="ttid" class="infinite-chained form-control input-lg">
                    <option></option>
                    <?php
                        $topProfession = mysql($w['database'],"SELECT profession_id FROM `list_professions` LIMIT 1");
                        $prof = mysql_fetch_array($topProfession);
                        echo listServices(0,"list",$w,$prof['profession_id'],0,$w['fast_search']);
                    ?>
                </select>
            </div>

            <div class="form-group nomargin hidden-sm hidden-xs tpad">
                <label>%%%more_options_label%%%</label>
            </div>

            <div class="form-group nomargin bpad">
                <select placeholder="%%%more_options_placeholder%%%" name="ttid" id="ttid" class="infinite-chained form-control input-lg">
                    <option></option>
                </select>
            </div>

            <div class="form-group nomargin hidden-sm hidden-xs tpad">
                <label>%%%home_search_dropdown_3%%%</label>
            </div>

            <div class="form-group nomargin">
                <div class="input-group input-group-lg col-sm-12">
                    <div class="input-group-addon"><i class="fa fa-fw fa-location-arrow"></i></div>
                    <span class="input-group-lg input_wrapper">
                        <input type="text" autocomplete="off" placeholder="%%%location_search_default%%%" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage" value="<?php if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>">
                    </span>
                </div>
            </div>

            <div class="col-md-12 nopad tmargin"><i class="fa fa-search" style="font-size:24px;color:white;"></i>
                <button type="submit" class="btn-block tmargin btn btn-lg btn_home_search">%%%home_search_submit%%%</button>
            </div>

            <div class="clearfix"></div>

        </form>

        <div class="clearfix"></div>
    </div>

<?php } ?>
