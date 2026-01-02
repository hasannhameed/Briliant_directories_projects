<?php
$country_name = "";
$country_name_sql = "";

if (isset($pars[0])) {

    if ($pars[0] != "search_results") {
          $countries_without_states = array("denmark", "finland", "singapore");
          $search_for_city = false;
          if (in_array($pars[0], $countries_without_states)) {
            $search_for_city = true;

            $state = $pars[0];

            $cityResults = mysql($w['database'],"
                    SELECT
                        lc.city_ln,
                        lc.city_filename,
                        lco.country_name
                    FROM
                        `users_data` AS ud,
                        `location_cities` AS lc,
                        `subscription_types` AS st,
                        `list_countries` AS lco
                    WHERE
                        lco.country_name = '".$state."' AND ud.city = lc.city_ln AND lc.country_sn = lco.country_code AND ud.subscription_id = st.subscription_id AND st.searchable = '1' AND ud.active = '2'
                    GROUP BY
                        ud.city
                    ORDER BY
                        ud.city ASC
                ");



            if (mysql_num_rows($cityResults) > 0) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 id="panel-title" class="panel-title">Browse <?php echo ($pars[1]) ? "by all " : "by" ?> cities in <?php echo urldecode(ucwords(str_replace('-', ' ', $pars[0]))); ?></h3>
                    </div>
                    <div class="panel-body">
                        
                        <?php while ($city = mysql_fetch_assoc($cityResults)) {
                            
                            $country_filename = strtolower(str_replace(' ', '-', $city['country_name']));
                            $actual_filename = $country_filename."/".$city['city_filename'];
                            ?>
                            <div class="col-sm-6 col-md-3 bmargin">
                                <a href="/<?php echo urldecode($actual_filename); ?>" title="<?php echo urltotext($city['city_ln']); ?>" class="location-link-search">
                                    <?php echo $city['city_ln']; ?>
                                </a>
                            </div>
                        <?php } ?>
                        
                        
                    </div>
                </div>
            
            <?php }

          }
          if (isset($pars[1])) {
              $state = $pars[1];

              $cityResults = mysql($w['database'],"SELECT
                  lc.city_ln,
                  lc.city_filename,
                  ls.state_filename,
                  lco.country_name
                  
              FROM
                  `users_data` AS ud,
                  `location_cities` AS lc,
                  `location_states` AS ls,
                  `subscription_types` AS st,
                  `list_countries` AS lco
              WHERE
                  ls.state_filename = '".$state."' AND lc.state_sn = ls.state_sn AND ud.city = lc.city_ln AND lc.country_sn = lco.country_code AND lc.country_sn = ls.country_sn AND ud.subscription_id = st.subscription_id AND st.searchable = '1' AND ud.active = '2'
              GROUP BY
                  ud.city
              ORDER BY
                  ud.city ASC
              ");

              if (mysql_num_rows($cityResults) > 0) { ?>
                  <div class="panel panel-default">
                      <div class="panel-heading" style="background-color:#e2e2e2 !important;">
                          <h3 id="panel-title" class="panel-title">Browse <?php echo ($pars[2]) ? "by all " : "by" ?> cities in <?php echo urldecode(ucwords(str_replace('-', ' ', $pars[1]))); ?></h3>
                      </div>
                      <div class="panel-body">
                          
                          <?php while ($city = mysql_fetch_array($cityResults)) {
                              
                              $country_filename = strtolower(str_replace(' ', '-', $city['country_name']));
                              $actual_filename = $country_filename."/".$city['state_filename']."/".$city['city_filename'];
                              ?>
                              <div class="col-sm-6 col-md-3 bmargin">
                                  <a href="/<?php echo urldecode($actual_filename); ?>" title="<?php echo urltotext($city['city_ln']); ?>" class="location-link-search">
                                      <?php echo $city['city_ln']; ?>
                                  </a>
                              </div>
                          <?php } ?>
                      </div>
                  </div>
              
              <?php }
          }

          if (isset($pars[0])) {
              $country_name = $pars[0];
              $country_name_sql = "AND country_name='".urltotext($country_name)."'";
          }

          $sresults = mysql($w['database'],"SELECT 
                  country_code,
                  country_name
              FROM 
                  `list_countries` 
              WHERE 
                  list_countries.active > 0 
              ".$country_name_sql."

               ");
          if (mysql_num_rows($sresults) > 0) {

              while ($country = mysql_fetch_assoc($sresults)) { 
                  $countries[$country['country_name']] = $country['country_code'];
              }  

          } else {
              $countries = array($w['country']);
          }
          if (is_array($countries)) {

              foreach ($countries as $countryLn => $country) {
                  $nocountries .= "ud.country_code!='$country' AND ";
                  $sresults = mysql($w['database'],"SELECT 
                          ls.state_ln,
                          ls.state_filename,
                          lc.country_name
                      FROM 
                          `users_data` AS ud,
                          `location_states` AS ls,
                          `list_countries` AS lc,
                          `subscription_types` AS st
                      WHERE 
                          ls.state_sn = ud.state_code
                      AND
                          ls.country_sn = lc.country_code
                      AND
                          ud.subscription_id = st.subscription_id
                      AND
                          ud.country_code = '".$country."' 
                      AND 
                          ls.country_sn =  '".$country."' 
                      AND 
                          ud.state_code != ''
                      AND
                          ud.active = '2'
                      AND
                          st.searchable='1'
                      GROUP BY 
                          ls.state_filename
                      ORDER BY 
                          ls.state_ln ASC");
                  $top_cities_sql = mysql($w['database'],"
                          SELECT
                              lc.city_ln,
                              lc.city_filename,
                              ls.state_filename,
                              lco.country_name,
                              COUNT(ud.user_id) as count_users
                          FROM
                              `users_data` AS ud,
                              `location_cities` AS lc,
                              `location_states` AS ls,
                              `subscription_types` AS st,
                              `list_countries` AS lco
                          WHERE
                              lco.country_code = '".$country."' AND lc.state_sn = ls.state_sn AND ud.city = lc.city_ln AND lc.country_sn = lco.country_code AND ls.country_sn = lco.country_code AND ud.subscription_id = st.subscription_id AND st.searchable = '1' AND ud.active = '2'
                          GROUP BY
                              ud.city
                          ORDER BY
                              count_users DESC
                          LIMIT 20
                      ");

                  
                  if (mysql_num_rows($sresults) > 0) { ?>
                      <div class="card panel-default">
                          <ul class="nav nav-tabs" role="tablist row">
                              <li role="presentation" class="col-md-6 nopad active"><a href="#states" aria-controls="state" role="tab" data-toggle="tab" aria-expanded="true">  Browse by states in <?php echo urldecode(ucwords(str_replace('-', ' ', $pars[0]))); ?> </h3></a></li>
                              <li role="presentation" class="col-md-6 nopad"><a href="#cities" aria-controls="city" role="city" data-toggle="tab" aria-expanded="false">  Browse by top cities in <?php echo urldecode(ucwords(str_replace('-', ' ', $pars[0]))); ?> </h3></a></li>
                          </ul>
                          <!-- Tab panes -->        
                          <div class="tab-content panel-body">
                              <div role="tabpanel" class="tab-pane active row" id="states">
                                  <?php
                                  $number_of_states = mysql_num_rows($sresults);
                                  $total_rows = $number_of_states / 4;
                                  $total_rows = round($total_rows);
                                  $i = 1;
                                  echo '<div class="col-sm-6 col-md-3">';
                                   while ($s = mysql_fetch_array($sresults)) {
                                  if($total_rows>0){
                                    $modulus = $i % $total_rows;
                                  }
                                  
                                  
                                  $filename   = strtolower(str_replace(' ', '-', $s['country_name'])."/".$s['state_filename']);
                                  ?>
                                  <div class=" bmargin">
                                      <a href="/<?php echo urldecode($filename) ?>" title="<?php echo urltotext($s['state_ln']); ?>" class="location-link-search">
                                          <?php echo $s[state_ln]; ?>
                                      </a>
                                  </div>
                                  <?php if (!$modulus): ?>
                                      </div>
                                      <div class="col-sm-6 col-md-3">
                                  <?php endif ?>
                                  <? $i++; } ?>
                                  </div>
                              </div>
                              <div role="tabpanel" class="tab-pane row" id="cities">
                                  <?php while ($top_city = mysql_fetch_array($top_cities_sql)) {
                                  $filename   = strtolower(str_replace(' ', '-', $top_city['country_name'])."/".$top_city['state_filename']."/".$top_city['city_filename']);
                                  ?>
                                  <div class="col-sm-6 col-md-3 bmargin">
                                      <a href="/<?php echo urldecode($filename) ?>" title="<?php echo urltotext($s['state_ln']); ?>" class="location-link-search">
                                          <?php echo $top_city[city_ln]; ?>
                                      </a>
                                  </div>
                                  <? } ?>
                              </div>
                          </div>
                      </div>
                  <?php } else if  (false) { 
                  // <?php } else if  ($country == 'GR' || $country == 'SG') { // we can add more countries that not have state

                      $cityResults = mysql($w['database'],"SELECT ud.city, ls.city_filename
                      FROM    `users_data` AS ud, 
                                  `location_cities` AS ls, 
                                  `subscription_types` AS st 
                      WHERE ud.country_code = '".$country."' 
                                  AND ud.subscription_id = st.subscription_id 
                                  AND st.searchable = '1' 
                                  AND ud.active = '2' 
                                  AND ls.city_ln = ud.city
                      GROUP BY ud.city 
                      ORDER BY ud.city ASC");

                      if (mysql_num_rows($cityResults) > 0) { ?>
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h3 id="panel-title" class="panel-title">%%%browse_label%%% <?php echo $countryLn; ?> %%%members_label%%%</h3>
                              </div>
                              <div class="panel-body">
                                  
                                  <?php while ($city = mysql_fetch_array($cityResults)) { 
                                          $countryFilename = strtolower(str_replace(' ', '-', $countryLn));
                                          $filenameBdString = new bdString($countryFilename.'/'.$city['city_filename']);
                                          $filenameBdString->urldecode();
                                          $filename = $filenameBdString->modifiedValue;
                                      ?>
                                      <div class="col-sm-6 col-md-3 bmargin">
                                          <a href="/<?php echo $filename; ?>" title="<?php echo urltotext($city['city']); ?>" class="location-link-search">
                                              <?php echo $city['city']; ?>
                                          </a>
                                      </div>
                                  <?php } ?>
                              </div>
                          </div>
                      
                      <?php }
                      
                  }
              }
          }

      }  
    echo widget("resutls-home","",$w[website_id],$w);
}



?>
<style>
    .browse-country-heading {
        display: none;
    }
</style>