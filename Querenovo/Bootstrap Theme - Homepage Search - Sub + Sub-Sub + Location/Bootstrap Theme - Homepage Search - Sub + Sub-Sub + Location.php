<?php if ($wa['custom_29'] == "horizontal") { ?>
    
    <div class="col-md-12 search_box fpad img-rounded" >
     
        <?php if ($wa['custom_131'] != "") { ?>
            <h2 class="fpad bold nomargin sm-text-center">
                <?php echo $wa['custom_131'];?>
            </h2>
        <?php } ?>
		<div class="clearfix"></div>
		<div class="clearfix"></div>
        <form class="fpad notpad website-search" name="frm1" action="/<?php echo $w['default_search_url'];?>">

            <div class='col-sm-12 nopad custom-input'>
                <div class="form-group nomargin hidden-xs hidden-sm col-md-4 nopad" bis_skin_checked="1">
                    <label>
                        Recherche par mot-clé
                    </label>
                </div>

                <div class="col-sm-12 bmargin nopad" style="">
                    <input type="text" class='form-control input-lg' name='q' placeholder="Rechercher un professionnel, une marque, un produit...">
                </div>
            </div>
            <div class="hpad tmargin">
                <div class="form-group nomargin hidden-xs hidden-sm col-md-4 nolpad">
                    <label>
                        %%%home_search_dropdown_2%%%
                    </label>
                </div>
                <div class="form-group nomargin hidden-xs hidden-sm col-md-4 nolpad">
                    <label>
                        %%%more_options_label%%%
                    </label>
                </div>
                <div class="form-group nomargin hidden-xs hidden-sm col-md-4 nolpad">
                    <label>
                        %%%home_search_dropdown_3%%%
                    </label>
                </div>
                <div class="clearfix"></div>
            </div>
			<div class="form-group col-xs-12 col-md-4 nolpad sm-norpad">                
				<select placeholder="%%%home_search_default_2%%%" name="tid" id="tid" next="ttid" class="infinite-chained form-control input-lg">
					<option></option>
					<?php
						$topProfession = mysql($w['database'],"SELECT
                                profession_id
                            FROM
                                `list_professions`
                            LIMIT
                                1");
						$prof = mysql_fetch_array($topProfession);
						echo listServices(0,"list",$w,$prof['profession_id'],0,$w['fast_search']); ?>
				</select>                
			</div>
            <div class="form-group col-xs-12 col-md-4 nolpad sm-norpad">                
				<select placeholder="%%%more_options_placeholder%%%" name="ttid" id="ttid" class="infinite-chained form-control input-lg">
					<option></option>
				</select>                
            </div>

			<div class="form-group col-xs-12 col-md-4 nolpad sm-norpad">
				<div class="input-group input-group-lg col-sm-12">
					<div class="input-group-addon"><i class="fa fa-fw fa-location-arrow"></i></div>
					<span class="input-group-lg input_wrapper">
                        <select name="departement" id="departement" class="form-control input-lg" placeholder='Choisir un département'>
                            <option value="">Choisir un département</option>
                            <option value="Ain">01-Ain</option>
                            <option value="Aisne">02-Aisne</option>
                            <option value="Allier">03-Allier</option>
                            <option value="Alpes-de-Haute-Provence">04-Alpes-de-Haute-Provence</option>
                            <option value="Hautes-Alpes">05-Hautes-Alpes</option>
                            <option value="Alpes-Maritimes">06-Alpes-Maritimes</option>
                            <option value="Ardèche">07-Ardèche</option>
                            <option value="Ardennes">08-Ardennes</option>
                            <option value="Ariège">09-Ariège</option>
                            <option value="Aube">10-Aube</option>
                            <option value="Aude">11-Aude</option>
                            <option value="Aveyron">12-Aveyron</option>
                            <option value="Bouches-du-Rhône">13-Bouches-du-Rhône</option>
                            <option value="Calvados">14-Calvados</option>
                            <option value="Cantal">15-Cantal</option>
                            <option value="Charente">16-Charente</option>
                            <option value="Charente-Maritime">17-Charente-Maritime</option>
                            <option value="Cher">18-Cher</option>
                            <option value="Corrèze">19-Corrèze</option>
                            <option value="Côte-d'Or">21-Côte-d'Or</option>
                            <option value="Côtes-d'Armor">22-Côtes-d'Armor</option>
                            <option value="Creuse">23-Creuse</option>
                            <option value="Dordogne">24-Dordogne</option>
                            <option value="Doubs">25-Doubs</option>
                            <option value="Drôme">26-Drôme</option>
                            <option value="Eure">27-Eure</option>
                            <option value="Eure-et-Loir">28-Eure-et-Loir</option>
                            <option value="Finistère">29-Finistère</option>
                            <option value="Gard">30-Gard</option>
                            <option value="Haute-Garonne">31-Haute-Garonne</option>
                            <option value="Gers">32-Gers</option>
                            <option value="Gironde">33-Gironde</option>
                            <option value="Hérault">34-Hérault</option>
                            <option value="Ille-et-Vilaine">35-Ille-et-Vilaine</option>
                            <option value="Indre">36-Indre</option>
                            <option value="Indre-et-Loire">37-Indre-et-Loire</option>
                            <option value="Isère">38-Isère</option>
                            <option value="Jura">39-Jura</option>
                            <option value="Landes">40-Landes</option>
                            <option value="Loir-et-Cher">41-Loir-et-Cher</option>
                            <option value="Loire">42-Loire</option>
                            <option value="Haute-Loire">43-Haute-Loire</option>
                            <option value="Loire-Atlantique">44-Loire-Atlantique</option>
                            <option value="Loiret">45-Loiret</option>
                            <option value="Lot">46-Lot</option>
                            <option value="Lot-et-Garonne">47-Lot-et-Garonne</option>
                            <option value="Lozère">48-Lozère</option>
                            <option value="Maine-et-Loire">49-Maine-et-Loire</option>
                            <option value="Manche">50-Manche</option>
                            <option value="Marne">51-Marne</option>
                            <option value="Haute-Marne">52-Haute-Marne</option>
                            <option value="Mayenne">53-Mayenne</option>
                            <option value="Meurthe-et-Moselle">54-Meurthe-et-Moselle</option>
                            <option value="Meuse">55-Meuse</option>
                            <option value="Morbihan">56-Morbihan</option>
                            <option value="Moselle">57-Moselle</option>
                            <option value="Nièvre">58-Nièvre</option>
                            <option value="Nord">59-Nord</option>
                            <option value="Oise">60-Oise</option>
                            <option value="Orne">61-Orne</option>
                            <option value="Pas-de-Calais">62-Pas-de-Calais</option>
                            <option value="Puy-de-Dôme">63-Puy-de-Dôme</option>
                            <option value="Pyrénées-Atlantiques">64-Pyrénées-Atlantiques</option>
                            <option value="Hautes-Pyrénées">65-Hautes-Pyrénées</option>
                            <option value="Pyrénées-Orientales">66-Pyrénées-Orientales</option>
                            <option value="Bas-Rhin">67-Bas-Rhin</option>
                            <option value="Haut-Rhin">68-Haut-Rhin</option>
                            <option value="Rhône">69-Rhône</option>
                            <option value="Haute-Saône">70-Haute-Saône</option>
                            <option value="Saône-et-Loire">71-Saône-et-Loire</option>
                            <option value="Sarthe">72-Sarthe</option>
                            <option value="Savoie">73-Savoie</option>
                            <option value="Haute-Savoie">74-Haute-Savoie</option>
                            <option value="Paris">75-Paris</option>
                            <option value="Seine-Maritime">76-Seine-Maritime</option>
                            <option value="Seine-et-Marne">77-Seine-et-Marne</option>
                            <option value="Yvelines">78-Yvelines</option>
                            <option value="Deux-Sèvres">79-Deux-Sèvres</option>
                            <option value="Somme">80-Somme</option>
                            <option value="Tarn">81-Tarn</option>
                            <option value="Tarn-et-Garonne">82-Tarn-et-Garonne</option>
                            <option value="Var">83-Var</option>
                            <option value="Vaucluse">84-Vaucluse</option>
                            <option value="Vendée">85-Vendée</option>
                            <option value="Vienne">86-Vienne</option>
                            <option value="Haute-Vienne">87-Haute-Vienne</option>
                            <option value="Vosges">88-Vosges</option>
                            <option value="Yonne">89-Yonne</option>
                            <option value="Territoire de Belfort">90-Territoire de Belfort</option>
                            <option value="Essonne">91-Essonne</option>
                            <option value="Hauts-de-Seine">92-Hauts-de-Seine</option>
                            <option value="Seine-Saint-Denis">93-Seine-Saint-Denis</option>
                            <option value="Val-de-Marne">94-Val-de-Marne</option>
                            <option value="Val-d'Oise">95-Val-d'Oise</option>
                        </select>
						<!-- <input type="text" autocomplete="off" class="googleSuggest googleLocation img-rounded form-control input-lg sm-center-block" name="location_value" style="width:100%;" id="location_google_maps_homepage" value="<?php if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>" placeholder="%%%location_search_default%%%"> -->
					</span>
				</div>
			</div>

            <div class="form-group col-xs-12 col-md-12 nopad nomargin">
                <button type="submit" class="btn-block btn btn-lg btn_home_search">%%%home_search_submit%%%</button>
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
                <label>
                    %%%home_search_dropdown_2%%%
                </label>
            </div>
            <div class="form-group nomargin bpad">
                <select placeholder="%%%home_search_default_2%%%" name="tid" id="tid" next="ttid" class="infinite-chained form-control input-lg">
                    <option></option>
                    <?php
                      $topProfession = mysql($w['database'],"SELECT
                              profession_id
                          FROM
                              `list_professions`
                          LIMIT
                              1");
                      $prof = mysql_fetch_array($topProfession);
                      echo listServices(0,"list",$w,$prof['profession_id'],0,$w['fast_search']); ?>
                </select>
            </div>
            <div class="form-group nomargin hidden-sm hidden-xs tpad">
                <label>
                    %%%more_options_label%%%
                </label>
            </div>
            <div class="form-group nomargin bpad">
                <select placeholder="%%%more_options_placeholder%%%" name="ttid" id="ttid" class="infinite-chained form-control input-lg">
                    <option></option>
                </select>
            </div>
            <div class="form-group nomargin hidden-sm hidden-xs tpad">
                <label>
                    %%%home_search_dropdown_3%%%
                </label>
            </div>
			<div class="form-group nomargin">
				<div class="input-group input-group-lg col-sm-12">
					<div class="input-group-addon"><i class="fa fa-fw fa-location-arrow"></i></div>
					<span class="input-group-lg input_wrapper">
						<input type="text" autocomplete="off" placeholder="%%%location_search_default%%%" class="googleSuggest googleLocation form-control input-lg" name="location_value" id="location_google_maps_homepage" value="<?php if ($_GET['location_value']!="") { echo $_GET['location_value']; } else if ($w['geocode_visitor_default']==1 && $w['geocode']==1 && $_SESSION['vdisplay']!="") { echo $_SESSION['vdisplay']; } ?>">
					</span>
				</div>
			</div>
            <div class="col-md-12 nopad tmargin">
                <button type="submit" class="btn-block tmargin btn btn-lg btn_home_search">%%%home_search_submit%%%</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="clearfix"></div>
    </div>
<?php } ?>
