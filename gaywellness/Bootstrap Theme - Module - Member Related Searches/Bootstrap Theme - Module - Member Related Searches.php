<?php
$profession = getProfessionDetails($user['profession_id'],$w);
$locity = array();
$lostate = array();
$related = getRelated($user['user_id'],$w);
$http = "http";
$wwwURL = $w['website_url'];

if ($w['https_frontend'] == 1 || $w['https_redirect'] == 1) {
    $http = "https";
}

if ($w['preferred_domain'] == "non-www") {
    $wwwURL = str_replace('www.', '', $wwwURL);
}

$relerelatedURL = $http . "://" . $wwwURL;

if (is_array($related) && count($related) > 0) { ?>
    <div class="module" >
        <h4>%%%sidebar_related_searches%%%</h4>
        <ul class="list-unstyled font-sm related-searches-ul">
            <li><a href="<?php echo $relatedURL;?>/<?php echo rawurldecode($profession['filename']);?>" title="<?php echo $profession['name']; ?>">%%%all_label%%% <?php echo $profession['name']; ?></a>
            <br/>
            <?php
            $stateString = "";

            if ($user['country_code'] == "US") {
              $state = getStateName($user['state_code'],$w,$user['country_code']);

            } else {
              $state = getCountryName(strtoupper($user['country_code']),$w);

              if ($w['enable_localized_search'] == 0) {
                $stateString = " %%%in_label%%% " . getCountryName(strtoupper($user['country_code']),$w);
              }
            }

            if ($user['country_code'] != "US" && $user['country_code'] != "CA" && $user['country_code'] != "UK") {
                $user['state_code'] = $country;
            }

            if ($w['enable_localized_search'] == 0 || $user['country_code'] == "US") {
              $relerelatedURL   = $relerelatedURL . "/" . $state;
            }
            
            $relerelatedURL     = strtolower(str_replace(' ', '-', $relerelatedURL));
            $relerelatedURL     = rtrim(rawurldecode($relerelatedURL),'/');  
            
            $locationsCitiesModel = new location_cities();
            $locationsStatesModel = new location_states();
            $locationsCountriesModel = new list_countries();          

            foreach($related['name'] as $key => $value) {

                if ($user['city'] != "" && ($locationsCitiesModel->get($user['city'],"city_ln") != false)) {
                    $locity[] = "<li><a href='" . $relerelatedURL . "/" . filenameString($user['city']) . "/" . $related['file'][$key]. "' title='" . $value . " %%%in_label%%% " . $user['city'] ."'>". $value ." %%%in_label%%% ". $user['city'] ."</a><br/>";
                }

                
            }

            if (count($locity) > 0) {

                foreach ($locity as $value) {
                    echo "$value";
                }
            }

            if (count($lozip) > 0) {

                foreach ($lozip as $value) {
                    echo "$value";
                }
            } ?>
        </ul>
    </div>
<?php
} ?>