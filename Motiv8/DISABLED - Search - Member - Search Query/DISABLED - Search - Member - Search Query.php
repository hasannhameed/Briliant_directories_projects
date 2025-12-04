<?php

/**
 * This widget follows BD code standards
 * Widget Name: Search - Member - Search Query
 * Short Description:
 * Version: 0.2
 */
class queryBuilder
{
    protected $whereParameters = NULL;
    protected $selectParameters = NULL;
    protected $tablesParameters = NULL;
    protected $groupByParameters = NULL;
    protected $havingParameters = NULL;
    protected $orderParameters = NULL;
    protected $limitParameters = NULL;
    protected $query = NULL;
    protected $selectQueryString = NULL;
    protected $tableQueryString = NULL;
    protected $whereQueryString = NULL;
    protected $groupQueryString = NULL;
    protected $havingQueryString = NULL;
    protected $orderQueryString = NULL;
    protected $limitQueryString = NULL;

    public function __construct()
    {
        $this->selectParameters = array();
        $this->tablesParameters = array();
        $this->whereParameters = array();
        $this->groupByParameters = array();
        $this->havingParameters = array();
        $this->orderParameters = array();
    }

    public function clearQueryString($queryString)
    {
        $this->$queryString = '';
    }

    protected function setQuery()
    {
        $this->clearQueryString('query');
        $this->query .= $this->selectQueryString;
        $this->query .= $this->tableQueryString;
        $this->query .= $this->whereQueryString;
        $this->query .= $this->groupQueryString;
        $this->query .= $this->havingQueryString;
        $this->query .= $this->orderQueryString;
        $this->query .= $this->limitQueryString;
    }

    public function addQueryParameter($property, $value)
    {
        $this->{$property}[] = $value;
    }

    public function setQueryParameter($property, $value)
    {
        $this->$property = $value;
    }

    public function buildSelectQuery()
    {
        if (count($this->selectParameters) > 0) {
            $this->clearQueryString('selectQueryString');
            $this->selectQueryString .= "SELECT DISTINCT ";
            $this->selectQueryString .= implode(", ", $this->selectParameters);
        }
    }

    public function buildTableQuery()
    {
        if (count($this->tablesParameters) > 0) {
            $this->clearQueryString('tableQueryString');
            $this->tableQueryString .= " FROM ";
            $this->tableQueryString .= implode(" ", $this->tablesParameters);
        }
    }

    public function buildWhereQuery()
    {
        if (count($this->whereParameters) > 0) {
            $this->clearQueryString('whereQueryString');
            $this->whereQueryString .= " WHERE ";
            $this->whereQueryString .= implode(" AND ", $this->whereParameters);
        }
    }

    public function buildGroupQuery()
    {
        if (count($this->groupByParameters) > 0) {
            $this->clearQueryString('groupQueryString');
            $this->groupQueryString .= " GROUP BY ";
            $this->groupQueryString .= implode(", ", $this->groupByParameters);
        }
    }

    public function buildHavingQuery()
    {
        if (count($this->havingParameters) > 0) {
            $this->clearQueryString('havingQueryString');
            $this->havingQueryString .= " HAVING ";
            $this->havingQueryString .= implode(" AND ", $this->havingParameters);
        }
    }

    public function buildOrderQuery()
    {
        if (count($this->orderParameters) > 0) {
            $this->clearQueryString('orderQueryString');
            $this->orderQueryString .= " ORDER BY ";
            $this->orderQueryString .= implode(", ", $this->orderParameters);
        }
    }

    public function buildLimitQuery()
    {
        if (count($this->limitParameters) > 0) {
            $this->clearQueryString('limitQueryString');
            $this->orderQueryString .= " LIMIT ";
            $this->orderQueryString .= implode(", ", $this->limitParameters);
        }
    }

    public function build()
    {
        $this->buildSelectQuery();
        $this->buildTableQuery();
        $this->buildWhereQuery();
        $this->buildGroupQuery();
        $this->buildHavingQuery();
        $this->buildOrderQuery();
        $this->buildLimitQuery();
        $this->setQuery();

        return $this->query;
    }

    public function getQuery()
    {
        return $this->query;
    }

    /**
     *   Function to check is one specific column exists on the table
     * @param $database //String
     * @param $table //String
     * @param $column //String
     * @return $isThere // Boolean
     */
    public function validateColumn($database, $table, $column)
    {
        $isThere = false;

        $result = mysql($database, "SHOW COLUMNS FROM `" . $table . "` LIKE '" . $column . "'");
        $isThere = (mysql_num_rows($result)) ? TRUE : FALSE;

        return $isThere;
    }
}

class BDMembersQuery extends queryBuilder
{
    protected $dataArray = NULL;
    protected $target = '';
    protected $searchQuery = '';
    protected $sortBy = NULL;
    protected $topLevelId = 0;
    protected $subLevelId = NULL;
    protected $overallRating = NULL;
    protected $subSubLevelId = NULL;
    protected $professionId = 0;
    protected $countryCode = '';
    protected $countryShortName = '';
    protected $stateSearch = '';
    protected $adminLevel1ShortName = '';
    protected $countyShortName = '';
    protected $zipCode = '';
    protected $stateShortName = '';
    protected $currentCity = '';
    protected $cityLongName = '';
    protected $radius = '';
    protected $location = '';
    protected $locationType = '';
    protected $formatSearch = '';
    protected $formatAddress = '';
    protected $subscriptionId = 0;
    protected $serviceId = 0;
    protected $matchingLimit = 0;
    protected $neLng = '';
    protected $neLat = '';
    protected $swLng = '';
    protected $swLat = '';
    protected $lat = '';
    protected $lng = '';
    protected $coordinatesArray = array('neLng','neLat','swLng','swLat','lat','lng');
    protected $noMembership = NULL;
    protected $database = 0;
    protected $areNewColumns = false;
    protected $searchMethod = '';
    protected $runPermission = 0;
    protected $devMode = 0;
    protected $whereKeyword = '';
    protected $parentId         = 0;
    public    $isCountrySearch  = false;
    public    $isStateSearch    = false;


    public function __construct()
    {
        parent::__construct();
        $this->devMode = (isset($_GET['devmode'])) ? $_GET['devmode'] : 0;
        $this->run();
    }

    public function isOnDevMode()
    {
        return ($this->devMode == 1) ? true : false;
    }

    public function runParentId(){
        if(intval($this->parentId) > 0){
            $this->addQueryParameter('whereParameters',' (ud.parent_id = '.$this->parentId.' OR ud.user_id ='.$this->parentId.' )');
        }
    }

    public function runSortBy()
    {
        global $dc;
        if ($this->sortBy != null) {

            if ($this->sortBy == "name DESC" || $this->sortBy == 'alphabet-desc') {
                $this->sortBy = "alphabet-desc";

            } else if ($this->sortBy == "reviews") {
                $this->sortBy = "reviews";

            } else if($this->sortBy == "userid-asc" || $this->sortBy == "userid-desc") {
                $this->sortBy = $this->sortBy;
            } else {
                $this->sortBy = "alphabet-asc";
            }

        } else if (!empty($dc['category_order_by'])) {
            $this->sortBy = $dc['category_order_by'];
        }
    }

    public function runCountry()
    {
        if ($this->countryCode != "") {
            global $profs;

            $this->addQueryParameter('whereParameters', " ud.country_code = '" . $this->countryCode . "' ");
            //$profs['country_name'] = $this->countryCode;
            $profs['country_code'] = $this->countryCode;
            $profs['new_filename'][0] = "%country_name~na%";
        }
    }

    public function runState()
    {
        if ($this->stateShortName != "") {
            global $profs, $state;

            $this->addQueryParameter('whereParameters', ' ud.state_code = "' . $state['state_sn'] . '" ');
            $profs['state_name'] = $state['state_ln'];
            $profs['state_code'] = $state['state_sn'];
            $profs['state_filename'] = $state['state_filename'];
            $profs['new_filename'][1] = "%state_name~na%";
        }
    }

    public function runZipCode()
    {
        if ($this->zipCode != "") {
            global $profs;
            if(isset($this->dataArray['postal_code'])){
                $postalCode = $this->dataArray['postal_code'];
            }else if(isset($this->dataArray['postal_code_prefix'])){
                $postalCode = $this->dataArray['postal_code_prefix'];
            }
            $profs['zip_code'] = $postalCode;
        }
    }

    public function runCity()
    {
        if ($this->cityLongName != "" && $this->location == "") {
            global $profs, $city;
            $profs['city_name'] = $city['city_ln'];
            $profs['city_filename'] = $city['city_filename'];
            $profs['new_filename'][2] = "%city_name~na%";
            $city['city_ln'] = str_replace("'", "''", $city['city_ln']);
            $this->addQueryParameter('whereParameters', " ud.city LIKE '%" . $city['city_ln'] . "%' ");
        }
    }

    public function runSearchQuery()
    {

        global $w;

        $_ENV['searchQuery'] = $this->searchQuery;
        $addonMemberKeywords = getAddOnInfo("search_members_keywords","6a2f4bd232e526ac53144b56da479f03");
        if (isset($addonMemberKeywords['status']) && $addonMemberKeywords['status'] === 'success') {
            echo widget($addonMemberKeywords['widget'],"",$w['website_id'],$w);
            $whereValue = $_ENV['whereValue'];

        } else {
            $whereValue = '(CONCAT(TRIM(ud.first_name)," ",TRIM(ud.last_name)) LIKE "%' . mysql_real_escape_string(stripslashes(stripslashes($this->searchQuery))) . '%" OR TRIM(ud.company) LIKE "%' . mysql_real_escape_string(stripslashes(stripslashes($this->searchQuery))) . '%")';
        }



        if (!empty($this->searchQuery) && $this->target == 'search') {


            if(isset($w['member_search_type']) && $w['member_search_type'] == "inclusive"){
                // checks if q is the name of a top level category
                $checkTopQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "
                    SELECT
                        lp.profession_id
                    FROM
                        `list_professions` AS lp
                    WHERE
                        (
                            lp.name LIKE '%" . $this->searchQuery . "%' OR
                            lp.name LIKE '% " . $this->searchQuery . "%' OR
                            lp.name LIKE '%-" . $this->searchQuery . "%' OR
                            lp.name LIKE '%," . $this->searchQuery . "%' OR
                            lp.filename LIKE '%" . $this->searchQuery . "%' OR
                            lp.keywords LIKE '%" . $this->searchQuery . "%'
                        )
                ");

                // If q is the actually the name of a top level category it will assign the id to $_GET[pid] and that will trigger the top category search
                if (mysql_num_rows($checkTopQuery) > 0) {

                    $topCategories = array();
                    while ( $topCategory = mysql_fetch_assoc($checkTopQuery) ) {
                        $topCategories[] = $topCategory['profession_id'];
                    }

                    $this->dataArray['pid'] = $topCategories;
                }

                $checkSubQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "
                    SELECT
                        ls.service_id,
                        ls.name
                    FROM
                        `list_services` AS ls
                    WHERE
                        (
                            ls.name LIKE '%" . $this->searchQuery . "%' OR
                            ls.name LIKE '% " . $this->searchQuery . "%' OR
                            ls.name LIKE '%-" . $this->searchQuery . "%' OR
                            ls.name LIKE '%," . $this->searchQuery . "%' OR
                            ls.filename LIKE '%" . $this->searchQuery . "%' OR
                            ls.keywords LIKE '%" . $this->searchQuery . "%'
                        )
                ");

                if (mysql_num_rows($checkSubQuery) > 0) {

                    $serviceIdsArray = array();

                    while ($subLevelCategory = mysql_fetch_assoc($checkSubQuery)) {
                        $serviceIdsArray[] = $subLevelCategory['service_id'];
                    }

                    $this->dataArray['ttid'] = $serviceIdsArray;

                    // If there are not sub level category matches then the system will look for a frist, last or company name match
                }
                //"ud."
                $this->whereKeyword = $whereValue;

            }else{
                // checks if q is the name of a top level category
                $checkTopQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                        lp.profession_id
                    FROM
                        `list_professions` AS lp
                    WHERE
                        (
                            lp.name LIKE '" . $this->searchQuery . "' OR
                            lp.filename LIKE '" . $this->searchQuery . "' OR
                            lp.keywords LIKE '" . $this->searchQuery . "'
                        )
                    LIMIT
                        1");

                // If q is the actually the name of a top level category it will assign the id to $_GET[pid] and that will trigger the top category search
                if (mysql_num_rows($checkTopQuery) > 0) {

                    $topCategory = mysql_fetch_assoc($checkTopQuery);
                    $this->dataArray['pid'] = $topCategory['profession_id'];

                } else {// this will continue to search sub level category matches with q
                    $checkSubQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                            ls.service_id,
                            ls.name
                        FROM
                            `list_services` AS ls
                        WHERE
                            (
                                ls.name LIKE '" . $this->searchQuery . "' OR
                                ls.filename LIKE '" . $this->searchQuery . "' OR
                                ls.keywords LIKE '" . $this->searchQuery . "'
                            )
                        LIMIT
                            1");

                    if (mysql_num_rows($checkSubQuery) > 0) {

                        $subCategory = mysql_fetch_assoc($checkSubQuery);

                        $checkDuplicateNamesQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                                service_id
                            FROM
                                `list_services` as ls
                            WHERE
                                ls.name = '" . $subCategory['name'] . "'");

                        if (mysql_num_rows($checkDuplicateNamesQuery) > 1) {
                            $serviceIdsArray = array();

                            while ($duplicatedService = mysql_fetch_assoc($checkDuplicateNamesQuery)) {
                                $serviceIdsArray[] = $duplicatedService['service_id'];
                            }
                            $this->dataArray['ttid'] = $serviceIdsArray;

                        } else {
                            $this->dataArray['ttid'] = $subCategory['service_id'];
                        }

                        // If there are not sub level category matches then the system will look for a frist, last or company name match
                    } else {
                        $this->addQueryParameter('whereParameters', $whereValue);
                    }
                }
            }
        } else if (!empty($this->searchQuery) && $this->target == 'lead') {
            $this->addQueryParameter('whereParameters', $whereValue);
        }

        $addonMemberKeywords2 = getAddOnInfo('search_members_keywords','929ed98697e21e4c92aca80d8dd680bb');
        if (isset($addonMemberKeywords2['status']) && $addonMemberKeywords2['status'] === 'success') {
            $_ENV['userMetaQuery'] = $userMetaQuery;
            echo widget($addonMemberKeywords2['widget'],"",$w['website_id'],$w);
            $userMetaQuery = $_ENV['userMetaQuery'];

            $this->addQueryParameter('tablesParameters', $userMetaQuery);
        }
    }

    public function runTopLevelCategory()
    {
        global $w;

        if(isset($w['member_search_type']) && $w['member_search_type'] == "inclusive"){
            $whereParameter = $this->runSubLevelCategory(true);
        }

        if(is_array($this->topLevelId)){
            $topLevelId = $this->topLevelId[0];
            if(isset($w['member_search_type']) && $w['member_search_type'] == "inclusive"){
                $whereParameterTemp = " ( ud.profession_id IN (" . implode(',', $this->topLevelId) . ")";

                $whereParameter = ($whereParameter != "") ? $whereParameterTemp." OR ".$whereParameter." )" : $whereParameterTemp.") ";

                if($this->whereKeyword != ""){
                    $whereParameter = "(".$this->whereKeyword." OR ".$whereParameter.")";
                }
            }else{
                $whereParameter = " ud.profession_id IN (" . implode(',', $this->topLevelId) . ") ";
            }
        }else if($this->topLevelId > 0){

            $topLevelId = $this->topLevelId;
            if(isset($w['member_search_type']) && $w['member_search_type'] == "inclusive"){
                $whereParameterTemp = " ( ud.profession_id IN (" . $this->topLevelId . ")";

                $whereParameter = ($whereParameter != "") ? $whereParameterTemp." OR ".$whereParameter." )" : $whereParameterTemp.") ";

                if($this->whereKeyword != ""){
                    $whereParameter = "(".$this->whereKeyword." OR ".$whereParameter.")";
                }


            }else{
                $whereParameter = " ud.profession_id IN (" . $this->topLevelId . ")";
            }
        }else{

            if($this->whereKeyword != ""){
                $whereParameter = ($whereParameter != "") ? "(".$this->whereKeyword." OR ".$whereParameter.")" : $this->whereKeyword;
            }
        }

        if($whereParameter != ""){
            $this->addQueryParameter('whereParameters', $whereParameter);
        }

        if ($this->target == "search") {
            global $w, $profs, $profession;
            if ($w['enable_new_pretty_url_system'] == 1) {
                $profs['profession'] = $w['profession'];
                $getProfessionFilenameQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                            filename,
                            name
                        FROM
                            `list_professions`
                        WHERE
                            profession_id = '" . $this->topLevelId . "'");
                $getProfessionFilename = mysql_fetch_assoc($getProfessionFilenameQuery);
                if($this->topLevelId != 0 && isset($getProfessionFilename['filename']) && isset($getProfessionFilename['name'])){
                    $profs['profession_filename']   = $getProfessionFilename['filename'];
                    $profs['profession_name']       = $getProfessionFilename['name'];
                    $profs['new_filename'][3]       = "%profession_name~na%";
                }

            } else if(isset($profession['name']) && isset($profession['filename'])){
                $profs['profession']        = $profession['name'];
                $profs['profession_name']   = $profession['filename'];
                $profs['new_filename'][3]   = "%profession~na%";
            }
        }
    }
    public function runOverallRating() {
        global $w;
        $_ENV['whereValueOverallRating'] = $this->overallRating;
        $whereValueOverallRating = '';
        $addonFilterStarRating = getAddOnInfo("filter_by_star_rating", "e35fd7c6d199273a6bbed79ffcb0150c");
        if (isset($addonFilterStarRating['status']) && $addonFilterStarRating['status'] === 'success') {
            echo widget($addonFilterStarRating['widget'],'',$w['website_id'],$w);
            $whereValueOverallRating = $_ENV['whereValueReview'];
        }
        if (!is_null($this->overallRating) && !empty($whereValueOverallRating)) {
            $this->addQueryParameter('havingParameters', $whereValueOverallRating);
        }
    }
    public function runSubLevelCategory($returnQuery)
    {
        $whereParameter = "";

        if (!is_null($this->subLevelId) || !is_null($this->subSubLevelId)) {

            //set which is the dominant variable
            if (!is_null($this->subSubLevelId)) {
                $mainLevelId = $this->subSubLevelId;

            } else {
                $mainLevelId = $this->subLevelId;
            }

            if (is_array($mainLevelId)) {
                $whereParameter         = " (rs.rel_service_id IN (" . implode(",", $mainLevelId) . ") OR st.service_limit = 'all') ";
                $relServiceSTblSelect   = " rss.service_id IN (" . implode(",", $mainLevelId) . ") ";
            } else {
                $whereParameter         = " (rs.rel_service_id IN (" . $mainLevelId . ") OR ls.master_id = '" . $mainLevelId . "' OR st.service_limit = 'all') ";
                $relServiceSTblSelect   = " rss.service_id IN (" . $mainLevelId . ")";
            }

            if($returnQuery === false){
                $this->addQueryParameter('whereParameters', $whereParameter);
            }

            $joinType       = ($returnQuery === true)? "LEFT JOIN" : "INNER JOIN";
            $inclusiveWHERE = ($returnQuery === true)? "" : "";

            if($inclusiveWHERE != ""){
                $this->addQueryParameter('whereParameters', $inclusiveWHERE);
            }

            $sqlTablesParameters = " ".$joinType." (
                SELECT
                    rss.service_id AS rel_service_id,
                    rss.user_id AS rel_service_user_id
                FROM
                    `rel_services` AS rss
                WHERE
                    " . $relServiceSTblSelect . "
                GROUP BY
                    rss.user_id

            ) AS rs ON ud.user_id = rs.rel_service_user_id

            LEFT JOIN `list_services` AS ls ON ls.service_id = rs.rel_service_id

            ";

            if ($this->target == 'search') {
                global $w, $profs;

                if (is_array($mainLevelId)) {
                    $profs['service_name'] = getServiceById($mainLevelId[0], $w);
                } else {
                    $serviceIdSelect = $mainLevelId;
                }

                $getServiceFilenameQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                        filename,
                        name
                    FROM
                        `list_services`
                    WHERE
                        service_id = '" . $serviceIdSelect . "'");

                $getServiceFilename = mysql_fetch_assoc($getServiceFilenameQuery);
                if (is_array($mainLevelId)) {
                    $profs['service_name'] = getServiceById($mainLevelId[0], $w);
                } else {
                    $profs['service_name'] = $getServiceFilename['name'];
                }
                $profs['service_filename'] = $getServiceFilename['filename'];
                $profs['new_filename'][4] = "%service_name~na%";

            }

            $this->addQueryParameter('tablesParameters', $sqlTablesParameters);
        }

        return $whereParameter;
    }

    public function runProfession()
    {
        global $profession;

        if ($profession['profession_id'] > 0) {
            $this->addQueryParameter('whereParameters', "ud.profession_id = '" . $profession['profession_id'] . "' ");

            if ($this->target == 'search') {

                global $w, $profs;

                if ($w['enable_new_pretty_url_system'] == 1) {
                    $profs['profession'] = $w['profession'];
                    $profs['profession_filename'] = $profession['filename'];
                    $profs['profession_name'] = $profession['name'];
                    $profs['new_filename'][3] = "%profession_name~na%";

                } else {
                    $profs['profession'] = $profession['name'];
                    $profs['profession_name'] = $profession['name'];
                    $profs['new_filename'][3] = "%profession~na%";
                }

            }
        }
    }

    public function runService()
    {
        if ($this->serviceId > 0) {
            global $w, $service, $profs;
            //get all the services that have the same filename
            $duplicatedFilenameServiceQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                    service_id
                FROM
                    `list_services`
                WHERE
                    filename = '" . $service['filename'] . "'");

            while ($duplicatedFilenameService = mysql_fetch_assoc($duplicatedFilenameServiceQuery)) {
                $serviceFilenames[] = $duplicatedFilenameService['service_id'];
            }
            $sqlTablesParameter = " INNER JOIN (
                SELECT
                    rss.service_id AS rel_service_id,
                    rss.user_id AS rel_service_user_id
                FROM
                    `rel_services` AS rss
                WHERE
                    rss.service_id IN (" . implode(",", $serviceFilenames) . ")
                GROUP BY
                    rss.user_id

            ) AS rs ON ud.user_id = rs.rel_service_user_id ";

            $sqlWhereParameter = " (rs.rel_service_id IN (" . implode(",", $serviceFilenames) . ") OR st.service_limit = 'all') ";

            $this->addQueryParameter('tablesParameters', $sqlTablesParameter);
            $this->addQueryParameter('whereParameters', $sqlWhereParameter);

            $profs['service_filename'] = $service['filename'];
            $profs['service_name'] = $service['name'];
            $profs['new_filename'][4] = "%service_name~na%";
        }
    }

    public function runSubscription()
    {
        if ($this->subscriptionId > 0) {
            global $profs, $subscription;

            $this->addQueryParameter('whereParameters', " ud.subscription_id = '" . $this->subscriptionId . "' ");
            $profs['subscription_name'] = $subscription['subscription_name'];
            $profs['new_filename'][0] = "%subscription_name~na%";
        }
    }

    public function runRadius()
    {
        if ($this->radius != "") {
            global $w;
            $w['default_radius'] = $this->radius;
        }
    }

    public function runMemberPermissions()
    {
        if ($this->target == "search") {
            global $w;

            //code to check the new implementation for memebrship levels to search for other membership levels
            $membershipAdvOptQuery = mysql($w[database], "SHOW COLUMNS FROM
                    `subscription_types`
                LIKE
                    'search_membership_permissions'");
            $membershipAdvOpt = mysql_num_rows($membershipAdvOptQuery);

            if ($membershipAdvOpt > 0) {

                $membersOnlySearchVisibilityAddOn = getAddOnInfo('members_only','a12e81906e726b11a95ed205c0c1ed36');


                if (isset($membersOnlySearchVisibilityAddOn['status']) && $membersOnlySearchVisibilityAddOn['status'] === 'success') {

                    echo widget($membersOnlySearchVisibilityAddOn['widget'],"",$w['website_id'],$w);

                    if ($_ENV['whereValueSearchOption'] != "") {
                        $this->addQueryParameter('whereParameters', $_ENV['whereValueSearchOption']);
                    }


                } else {

                    $sqlWhereParameter = " (st.search_membership_permissions REGEXP 'visitor' OR st.search_membership_permissions = '') ";
                    $this->addQueryParameter('whereParameters', $sqlWhereParameter);
                }

            }
        }
    }

    // Special locations with weird lat and lon
    public function checkSpecialLocation () {
        // Array to add special locations, can be country, states and county
        $arraySapecialPlaces = array('AK', 'NZ');

        if (in_array($this->countryShortName, $arraySapecialPlaces)) {
            return false;
        } else if (in_array($this->stateSearch, $arraySapecialPlaces)) {
            return false;
        } else if (in_array($this->countyShortName, $arraySapecialPlaces)) {
            return false;
        }

        return true;

    }

    public function runLocation()
    {
        if ($this->location != '') {
            global $w, $profs;
            //check if the website is using kilometers or miles as the metric structure
            if ($w['distance'] == "mi") {
                $metricUnit = 3959;
            } else {
                $metricUnit = 6371;
            }
            //NOTE: this engine relies on the implementation of the google maps api on the pages that will send the coordinates parameters on the url
            // all the posible search types provided by google and how they are grouped in 6 main groups for their use in our system and for search settings in our system
            $AddressComponentsArray = array(
                "country" => array(
                    "country"
                ),
                "administrative_area_level_1" => array(
                    "administrative_area_level_1"
                ),
                "administrative_area_level_2" => array(
                    "administrative_area_level_2",
                    "administrative_area_level_3",
                    "administrative_area_level_4",
                    "administrative_area_level_5"
                ),
                "locality" => array(
                    "locality",
                    "colloquial_area",
                    "ward",
                    "sublocality",
                    "sublocality_level_1",
                    "sublocality_level_2",
                    "sublocality_level_3",
                    "sublocality_level_4",
                    "sublocality_level_5"
                ),
                "postal_code" => array(
                    "postal_code",
                    "postal_code_prefix"
                ),
                "neighborhood" => array(
                    "neighborhood",
                    "street_address",
                    "route",
                    "intersection",
                    "political",
                    "premise",
                    "subpremise",
                    "natural_feature",
                    "airport",
                    "park",
                    "point_of_interest"
                )
            );

            foreach ($AddressComponentsArray as $ackey => $acvalue) {

                //by having the type of search we can apply the search method chosen on the advanced settings of the given site
                if (in_array($this->locationType, $acvalue)) {
                    $location_type = $ackey;
                    break;
                }
            }
            //now that we know the type of search we will get the search method based on the advanced settings on the system
            $addStateParam = 0;
            $addCountry = 0;
            switch ($location_type) {
                case 'country':
                    $this->searchMethod = "bounds";
                    $this->isCountrySearch = true;
                    break;
                case 'administrative_area_level_1':
                    $this->searchMethod = $w['adm_area_lvl_1_search_method'];
                    $this->isStateSearch = true;
                    $this->isCountrySearch = true;
                    break;
                case 'administrative_area_level_2':
                    $this->searchMethod = $w['adm_area_lvl_2_search_method'];
                    break;
                case 'locality':
                    $this->searchMethod = $w['locality_search_method'];
                    break;
                case 'postal_code':
                case 'postal_code_prefix':
                    $this->searchMethod = $w['zipcode_search_method'];
                    break;
                case 'neighborhood':
                    $this->searchMethod = $w['neighborhood_search_method'];
                    break;
                default:
                    $this->searchMethod = "bounds";
                    break;
            }

            //variables that will hold the processing info and the final string added to the main sql for service areas
            $directNameAndService = array();
            $directNameAndSqlService = "";
            $directNameOrService = array();
            $directNameOrSqlService = "";
            //variables that will hold the processing info and the final string added to the main sql for direct user info
            $directNameAndUser = array();
            $directNameAndSqlUser = "";

            //if the country short name was sent on the GET request
            if ($this->countryShortName != "") {
                $directNameAndService[] = " country_sn = '" . $this->countryShortName . "' ";
                $directNameOrService['country_sn'][] = " sa2.country_sn = '" . $this->countryShortName . "' ";
                $directNameOrService['country_sn'][] = " sa2.location_type = 'Country' ";
                $directNameAndUser[] = " ud.country_code = '" . $this->countryShortName . "' ";
            }
            if ($this->stateSearch != "") {
                $directNameOrService['stateSearch'][] = " sa2.state_sn = '" . $this->stateSearch . "' ";
                $directNameOrService['stateSearch'][] = " sa2.location_type = 'State' ";
            }

            //Check if the website setting for including name arguments on the search is on, it will make it more strict and now more the results with the given info
            if ($w['strict_search_results'] != 0) {

                if ($this->adminLevel1ShortName != "") {
                    $directNameAndService[] = " state_sn = '" . $this->adminLevel1ShortName . "' ";
                    $directNameOrService['adm_lvl_1_sn'][] = " sa2.country_sn = '" . $this->countryShortName . "' ";
                    $directNameOrService['adm_lvl_1_sn'][] = " sa2.state_sn = '" . $this->adminLevel1ShortName . "' ";
                    $directNameOrService['adm_lvl_1_sn'][] = " sa2.location_type = 'State' ";
                    $directNameAndUser[] = ' ud.state_code = "' . $this->adminLevel1ShortName . '" ';
                }
                if ($this->countyShortName != "") {
                    $directNameAndService[] = " county_sn = '" . $this->countyShortName . "' ";
                    $directNameOrService['county_sn'][] = " sa2.country_sn = '" . $this->countryShortName . "' ";
                    $directNameOrService['county_sn'][] = " sa2.state_sn = '" . $this->adminLevel1ShortName . "' ";
                    $directNameOrService['county_sn'][] = " sa2.county_sn = '" . $this->countyShortName . "' ";
                    $directNameOrService['county_sn'][] = " sa2.location_type = 'County' ";
                }
            }
            //create the method that will check the service area using the geolocation information and will also check on big main places names
            if (count($directNameAndService) > 0) {
                $directNameAndSqlService = " AND " . implode(" AND ", $directNameAndService);
            }
            //create the sql code that will check on the main user info against the location names
            if (count($directNameAndUser) > 0) {
                $directNameAndSqlUser = " AND " . implode(" AND ", $directNameAndUser);
            }
            //create the method that will check the service area only based on the locations names and the location type of the service area
            if (count($directNameOrService) > 0) {
                $stagingOrElements = array();

                foreach ($directNameOrService as $dnokey => $dnovalue) {
                    $stagingOrElements[] = " ( " . implode(" AND ", $dnovalue) . " ) ";
                }
                $directNameOrSqlService = " OR ( " . implode(" OR ", $stagingOrElements) . " ) ";
            }
            //check if the fsearch (force search) parameter is not empty, if not, force the search to be radius because there is not enough info to make a bounds search
            if ($this->formatSearch != "") {
                $this->searchMethod = "radius";
            }

            //include the sql where parameters depending on the type of search you are performing

            switch ($this->searchMethod) {
                case 'bounds':
                default:

                    if ($this->neLng > $this->swLng) {
                        $andOrCheck = " AND ";

                    } else if ($this->swLng > $this->neLng) {
                        $andOrCheck = " OR ";
                    }

                    $this->setBoundsSql($directNameAndSqlService, $directNameOrSqlService, $andOrCheck, $directNameAndSqlUser);

                    $profs['location_search_type'] = "bounds";
                    break;

                case 'radius':

                    $this->setSelectFields(true);

                    $sqlBaseForDistance = "COALESCE((" . $metricUnit . " * acos( cos( radians(" . $this->lat . ") ) * cos( radians( ud.lat ) ) * cos( radians( ud.lon ) - radians(" . $this->lng . ") ) + sin( radians(" . $this->lat . ") ) * sin(radians(ud.lat)) ) ),0)";
                    $this->addQueryParameter('selectParameters', $sqlBaseForDistance . " AS distance");
                    $this->addQueryParameter('selectParameters', "user_service_areas.service_area_user_id AS service_area");
                    $this->addQueryParameter('selectParameters', "user_service_areas.service_distance AS service_distance");
                    //this is the sql that will create a table on the fly to check against  service areas
                    $this->addQueryParameter('tablesParameters', "LEFT JOIN (
                        SELECT
                            sa2.user_id AS service_area_user_id,
                                (" . $metricUnit . " * acos( cos( radians(" . $this->lat . ") ) * cos( radians( sa2.lat ) ) * cos( radians( sa2.lon ) - radians(" . $this->lng . ") ) + sin( radians(" . $this->lat . ") ) * sin(radians(sa2.lat)) ) ) AS service_distance

                        FROM
                            `service_areas` AS sa2
                        WHERE
                            (
                                COALESCE((" . $metricUnit . " * acos( cos( radians(" . $this->lat . ") ) * cos( radians( sa2.lat ) ) * cos( radians( sa2.lon ) - radians(" . $this->lng . ") ) + sin( radians(" . $this->lat . ") ) * sin(radians(sa2.lat)) ) ),0) <= " . $w['default_radius'] . "
                                " . $directNameAndSqlService . "
                            )
                            " . $directNameOrSqlService . "
                        GROUP BY
                            sa2.user_id
                    ) user_service_areas ON user_service_areas.service_area_user_id = ud.user_id ");
                    //this is the sql section on the where that checks against the users current info and the result of the service areas table created on the fly
                    $this->addQueryParameter('whereParameters', "(
                            (
                                " . $sqlBaseForDistance . " <= " . $w['default_radius'] . "
                                " . $directNameAndSqlUser . "
                            )
                        OR
                            user_service_areas.service_area_user_id > 0
                        OR
                            ud.nationwide = 1
                        OR
                            st.location_limit = 'all'
                    )");
                    $this->setOrderParemeters(true);
                    $profs['location_search_type'] = "radius";
                    break;
            }//END switch ($this->searchMethod)

            if ($this->target == 'search') {
                //check if the location country exists on the database, if it does then find the country information for SEO purposes
                if ($this->countryShortName != "") {
                    $countryValuesQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                            country_name
                        FROM
                            `list_countries`
                        WHERE
                            country_code = '" . $this->countryShortName . "'
                        LIMIT
                            1");

                    if (mysql_num_rows($countryValuesQuery) > 0) {
                        $countryValues = mysql_fetch_assoc($countryValuesQuery);
                        $profs['country_name'] = $countryValues['country_name'];
                        $profs['country_code'] = $this->countryShortName;
                        $profs['new_filename'][0] = "%country_name~na%";
                    }
                }
                //check if the locations states exists on the database, if it does then find the state information for seo purposes
                $checkLocationStates = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                        1
                    FROM
                        `location_states`
                    LIMIT
                        1");

                if ($checkLocationStates !== FALSE && $this->adminLevel1ShortName != "") {

                    $stateValuesQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                            state_ln
                        FROM
                            `location_states`
                        WHERE
                            state_sn = '" . $this->adminLevel1ShortName . "'
                        AND
                            country_sn = '" . $this->countryShortName . "'
                        LIMIT
                            1");

                    if (mysql_num_rows($stateValuesQuery) > 0) {
                        $stateValues = mysql_fetch_assoc($stateValuesQuery);
                        $profs['state_name'] = $stateValues['state_ln'];
                        $profs['state_code'] = $this->adminLevel1ShortName;

                        if(isset($this->dataArray['postal_code'])){
                            $profs['zip_code'] = $this->dataArray['postal_code'];
                        }else if(isset($this->dataArray['postal_code_prefix'])){
                            $profs['zip_code'] = $this->dataArray['postal_code_prefix'];
                        }

                        $profs['new_filename'][1] = "%state_name~na%";
                    }
                }
                //get the city information if it sent to use on search purposes
                if ($this->currentCity != "") {
                    $profs['city_name'] = $this->currentCity;
                    $profs['new_filename'][2] = "%city_name~na%";
                }
                $profs['location'] = $this->formatAddress;
            }
        } else {
            $this->setSelectFields(true);
        }

        if (empty($this->searchQuery) && empty($this->location)) {
            $this->setSelectFields(true);
        }
    }

    public function runFileName()
    {
        if ($this->target == "search") {
            global $profs;
            if (is_array($profs['new_filename'])) {
                ksort($profs['new_filename']);
                $profs['new_filename'] = implode("/", $profs['new_filename']);
            }
        }
    }

    public function runMatchLimit()
    {
        if ($this->target == 'lead') {
            if ($this->matchingLimit > 0) {
                $this->addQueryParameter("limitParameters", $this->matchingLimit);
            } else {
                $this->addQueryParameter("limitParameters", 10);
            }
        }
    }
    public function runReviews()
    {
        if(!empty($this->overallRating)) {
            $fromReviewQuery = " RIGHT JOIN users_reviews AS ur ON ud.user_id = ur.user_id ";
            $this->addQueryParameter('tablesParameters', $fromReviewQuery);

            $selectReviewQuery = '(SELECT ROUND(AVG(rating_overall)) FROM `users_reviews` AS ur WHERE ud.user_id = ur.user_id AND ur.review_status = 2) AS rating_overall ';
            $this->addQueryParameter('selectParameters', $selectReviewQuery);
            $this->addQueryParameter('whereParameters', ' ur.review_status = 2 ');
        }
        /* $havingReviewQuery='';
         $this->addQueryParameter('selectParameters', $havingReviewQuery);*/

    }
    protected function run()
    {
        global $w;
        //we set all attributtes
        $this->setAttributes();

        //we run all decision function base on sent parameters
        //$this->runSortBy(); suppose to goe here but was move to setOrderParameters//
        //$this->runSearchQuery() suppose to goe here but was move to setTopLevelId//
        $this->runTopLevelCategory();
        if( (!isset($w['member_search_type']) || $w['member_search_type'] == 'strict') ){
            $this->runSubLevelCategory(false);
        }
        $this->runOverallRating();
        $this->runProfession();
        $this->runService();
        $this->runCountry();
        $this->runParentId();
        $this->runState();
        $this->runZipCode();
        $this->runCity();
        $this->runSubscription();
        $this->runRadius();
        $this->runMemberPermissions();
        $this->runLocation();
        $this->runFileName();
        $this->runReviews();
        $this->runMatchLimit();

        $this->build();
    }

    protected function setSelectFields($excludeNewColumns = false)
    {

        $sqlSelectParameters = array();

        $orderNames = "CASE WHEN ud.listing_type = 'individual' THEN CASE WHEN TRIM(ud.first_name) <> '' THEN CONCAT(TRIM(ud.first_name), ' ', TRIM(ud.last_name)) ELSE ud.company END ELSE CASE WHEN ud.company = '' THEN CONCAT(TRIM(ud.first_name), ' ', TRIM(ud.last_name)) ELSE ud.company END END AS name ";

        if ($this->sortBy) {

            switch ($this->sortBy) {

                case 'reviews':
                    $sqlSelectParameters = array(
                        "ud.user_id",
                        "ud.listing_type",
                        " (SELECT count(*) AS reviews_number FROM `users_reviews` AS ur WHERE ud.user_id = ur.user_id AND ur.review_status = 2 ) AS reviews_number",
                        $orderNames
                    );

                    break;
                //  This case is for default, random, alphabet-asc, alphabet-desc
                default:
                    $sqlSelectParameters = array(
                        "ud.user_id",
                        "ud.listing_type",
                        $orderNames
                    );
            }

        } else {
            //Default select parameters
            $sqlSelectParameters = array(
                "ud.user_id",
                "ud.listing_type",
                $orderNames
            );
        }


        //if we have the new columns lets redefine
        if ($this->areNewColumns === true && $excludeNewColumns === false) {
            //Custom select parameters
            $sqlSelectParameters[] = 'user_service_areas.nelat';
            $sqlSelectParameters[] = 'user_service_areas.nelng';
            $sqlSelectParameters[] = 'user_service_areas.swlat';
            $sqlSelectParameters[] = 'user_service_areas.swlng';
            $sqlSelectParameters[] = 'user_service_areas.lat';
            $sqlSelectParameters[] = 'user_service_areas.lng';
            $sqlSelectParameters[] = 'user_service_areas.address';
            $sqlSelectParameters[] = 'ud.lon as user_lng';
            $sqlSelectParameters[] = 'ud.lat as user_lat';
        }

        $this->clearQueryString('selectParameters');
        $this->setQueryParameter('selectParameters', $sqlSelectParameters);
    }

    protected function setBoundsSql($directNameAndSqlService, $directNameOrSqlService, $andOrCheck, $directNameAndSqlUser)
    {

        if($this->isStateSearch === false && $this->isCountrySearch === false){
            $boundsServiceAreaSqlWhere = "
            (
                (
                        (
                                sa2.lon >= " . $this->swLng . "
                            " . $andOrCheck . "
                                sa2.lon <= " . $this->neLng . "
                        )
                    AND
                        sa2.lat >= " . $this->swLat . "
                    AND
                        sa2.lat <= " . $this->neLat . "

                )
                " . $directNameAndSqlService . "
            )
            " . $directNameOrSqlService;

            $boundsSqlWhereString = "
            (
                (
                        ud.lon >= " . $this->swLng . "
                    " . $andOrCheck . "
                        ud.lon <= " . $this->neLng . "
                )
                AND
                    ud.lat >= " . $this->swLat . "
                AND
                    ud.lat <= " . $this->neLat . "
                " . $directNameAndSqlUser . "
            )";

        }else if($this->isStateSearch === true && $this->adminLevel1ShortName != ""){
            $boundsSqlWhereString = "
                    ud.country_code = '".$this->countryShortName."'
                AND
                    ud.state_code = '".$this->adminLevel1ShortName."'
            ";

            $boundsServiceAreaSqlWhere = "
                    sa2.country_sn = '".$this->countryShortName."'
                AND
                    sa2.state_sn = '".$this->adminLevel1ShortName."'
            ";

        }else if($this->isCountrySearch === true && $this->countryShortName != ""){
            $boundsSqlWhereString       = " ud.country_code = '".$this->countryShortName."' ";
            $boundsServiceAreaSqlWhere  = " sa2.country_sn = '".$this->countryShortName."' ";
        }

        //this is the sql that will create a table on the fly to check against  service areas
        $boundsSql = "LEFT JOIN (
                SELECT
                    sa2.user_id AS service_area_user_id
                FROM
                    `service_areas` AS sa2
                WHERE
                    ".$boundsServiceAreaSqlWhere."
                GROUP BY
                    sa2.user_id
            ) user_service_areas ON user_service_areas.service_area_user_id = ud.user_id";

        $boundsSqlWhere = "(
                        (
                                ".$boundsSqlWhereString."
                            OR
                                user_service_areas.service_area_user_id > 0
                        )
                    OR
                        ud.nationwide = '1'
                    OR
                        st.location_limit = 'all'
                )";
        //we know check if we have new columns
        if ($this->areNewColumns === true && $this->isStateSearch === false && $this->isCountrySearch === false) {

            $boundsSql = "  LEFT JOIN (
                    SELECT
                        sa2.user_id AS service_area_user_id,
                        sa2.nelat,
                        sa2.nelng,
                        sa2.swlat,
                        sa2.swlng,
                        sa2.lat,
                        sa2.lon as lng,
                        sa2.address
                    FROM
                        `service_areas` AS sa2
                    WHERE
                        (
                            (
                                (
                                    (
                                        " . $this->swLat . " between sa2.swlat AND sa2.nelat
                                        OR
                                        " . $this->neLat . " between sa2.swlat AND sa2.nelat
                                    )
                                    AND
                                    (
                                        " . $this->swLng . " between sa2.swlng AND sa2.nelng
                                        OR
                                        " . $this->neLng . " between sa2.swlng AND sa2.nelng
                                    )
                                )
                                OR
                                (
                                    (
                                        sa2.swlat between " . $this->swLat . " AND " . $this->neLat . "
                                        OR
                                        sa2.nelat between " . $this->swLat . " AND " . $this->neLat . "
                                    )
                                    AND
                                    (
                                        sa2.swlng between " . $this->swLng . " AND " . $this->neLng . "
                                        OR
                                        sa2.nelng between " . $this->swLng . " AND " . $this->neLng . "
                                    )
                                )
                            )
                            " . $directNameAndSqlService . "
                        )
                    " . $directNameOrSqlService . "
                    GROUP BY
                        sa2.user_id
                ) user_service_areas ON user_service_areas.service_area_user_id = ud.user_id";

            if ($this->checkSpecialLocation()) {
                $boundsSqlWhere = "(
                            (
                                    (
                                        (
                                            ud.lat between " . $this->swLat . " AND " . $this->neLat . "
                                        AND
                                            ud.lon between " . $this->swLng . " AND " . $this->neLng . "
                                        )
                                        " . $directNameAndSqlUser . "
                                    )
                                OR
                                    user_service_areas.service_area_user_id > 0
                            )
                        OR
                            ud.nationwide = '1'
                        OR
                            st.location_limit = 'all'
                    )";
            }
        }

        //we add the sql to the global array past by pointer reference
        $this->addQueryParameter('tablesParameters', $boundsSql);
        $this->addQueryParameter('whereParameters', $boundsSqlWhere);

        if($this->isStateSearch === true || $this->isCountrySearch === true){
            $this->setSelectFields(true);
        }

    }

    protected function setOrderParemeters($radiusParamer = false)
    {

        $this->runSortBy();

        switch ($this->sortBy) {

            case 'random':
                $randomSeed = session_id();
                $randomSeed = preg_replace("/[^0-9,.]/", "", $randomSeed);
                $sqlOrderByParameters = array(
                    "st.search_priority ASC",
                    "RAND($randomSeed)"
                );
                break;

            case 'alphabet-desc':
                $sqlOrderByParameters = array(
                    "st.search_priority ASC",
                    "name DESC",
                    "ud.user_id DESC"
                );
                break;

            case 'reviews':
                $sqlOrderByParameters = array(
                    "st.search_priority ASC",
                    "reviews_number DESC",
                    "name ASC",
                    "ud.user_id DESC"
                );
                break;

            case 'userid-asc':
                $sqlOrderByParameters = array(
                    "st.search_priority ASC",
                    "ud.user_id ASC"
                );
                break;

            case 'userid-desc':
                $sqlOrderByParameters = array(
                    "st.search_priority ASC",
                    "ud.user_id DESC"
                );
                break;

            //  This case is for default or alphabet-desc ASC
            default:
                $sqlOrderByParameters = array(
                    "st.search_priority ASC",
                    "name ASC",
                    "ud.user_id DESC"
                );

        }

        if ($radiusParamer === true) {



            if (isset($this->dataArray['sort']) && $this->dataArray['sort'] != "distance") {

                $firstPosition = $sqlOrderByParameters[0];
                $secondPosition = $sqlOrderByParameters[1];

                $sqlOrderByParameters[0] = "distance ASC";

                array_unshift($sqlOrderByParameters, $secondPosition);
                array_unshift($sqlOrderByParameters, $firstPosition);

            } else {
                $firstPosition = $sqlOrderByParameters[0];
                $sqlOrderByParameters[0] = "distance ASC";
                array_unshift($sqlOrderByParameters, $firstPosition);
            }
        }
        $this->clearQueryString('orderParameters');
        $this->setQueryParameter('orderParameters', $sqlOrderByParameters);
    }

    protected function setWhere()
    {
        global $w;
        //Default Where parameters
        if ($w[search_results_require_complete_profile] == "0") {
            $value = array(
                "ud.active = '2'",
                "st.searchable = '1'"
            );
        } else {
            $value = array(
                "ud.active = '2'",
                "st.searchable = '1'",
                "(ud.company != '' OR ud.first_name != '')",
                "ud.profession_id > '0'",
                "ud.country_code != ''",
                "up.file != ''"
            );
        }

        $this->clearQueryString('whereParameters');
        $this->setQueryParameter('whereParameters', $value);
    }

    protected function setFromTables()
    {
        global $w;
        //Default tables for a select
        if ($w['search_results_require_complete_profile'] == "0") {
            $value = array(
                "`users_data` AS ud",
                "INNER JOIN `subscription_types` AS st ON ud.subscription_id = st.subscription_id"
            );
        } else {
            $value = array(
                "`users_data` AS ud",
                "INNER JOIN `subscription_types` AS st ON ud.subscription_id = st.subscription_id INNER JOIN `users_photo` AS up ON ud.user_id = up.user_id"
            );
        }


        $this->clearQueryString('tablesParameters');
        $this->setQueryParameter('tablesParameters', $value);
    }

    protected function setAttributes()
    {
        $this->setDataArray();
        $this->newColumnsExists();
        $this->setWhere();
        $this->setFromTables();
        $this->setSortBy();
        $this->setOrderParemeters();
        $this->setSelectFields();
        $this->setSearchQuery();
        $this->setTopLevelId();
        $this->setSubLevelId();
        $this->setSubSubLevelId();
        $this->setOverallRating();
        $this->setProfessionId();
        $this->setServiceId();
        $this->setCountryCode();
        $this->setParentId();
        $this->setCountryShortName();
        $this->setStateSearch();
        $this->setAdminLevel1ShortName();
        $this->setCountyShortName();
        $this->setStateShortName();
        $this->setCityLongName();
        $this->setCity();
        $this->setZipCode();
        $this->setRadius();
        $this->setLocation();
        $this->setLocationType();
        $this->setNeLat();
        $this->setNeLng();
        $this->setSwLat();
        $this->setSwLng();
        $this->setLat();
        $this->setLng();
        $this->setFormatSearch();
        $this->setFormatAddress();
        $this->setSubscriptionId();
        $this->setMatchingLimit();
        $this->setNoMembership();
    }

    protected function newColumnsExists()
    {
        global $w;

        $database = brilliantDirectories::getDatabaseConfiguration('database');

        $nelatIsThere = $this->validateColumn($database, 'service_areas', 'nelat');
        $nelngIsThere = $this->validateColumn($database, 'service_areas', 'nelat');
        $swlatIsThere = $this->validateColumn($database, 'service_areas', 'swlat');
        $swlngIsThere = $this->validateColumn($database, 'service_areas', 'swlng');

        if ($nelatIsThere === true && $nelngIsThere === true && $swlatIsThere === true && $swlngIsThere === true) {
            $this->areNewColumns = true;
        }
    }

    protected function setDataArray()
    {
        //we take the decision of the target members search query or auto match lead
        if (count($_GET) > 0 && count($_POST) <= 0) {//members search
            $this->dataArray = $_GET;
            $this->target = 'search';
        } else if (count($_POST) > 0) {//
            $this->dataArray = $_POST;
            $this->target = 'lead';
        }
    }
    protected function setPropertyArray($array) {

        if (is_array($array) && count($array) > 0) {
            foreach ($array as $post_key=>$post_value) {
                if (is_array($post_value)) {
                    $array[$post_key] = $this->setPropertyArray($post_value);
                } else {
                    $array[$post_key] = mysql_real_escape_string($post_value);
                }
            }
        }
        return $array;
    }

    protected function setProperty($property, $value, $defaultValue)
    {

        if ($property == "matchingLimit" && $this->target == 'lead') {
            $this->$property = $value;
        }


        if (!empty($defaultValue)) {
            
            if (preg_match("@^[a-zA-Z0-9%+-_]*$@", $defaultValue)){
                $defaultValue = urldecode($defaultValue);
            }

            $this->$property = mysql_real_escape_string($defaultValue);
            return;
        }

        if (!is_null($value) && !empty($value) && is_string($value)) {

            if (in_array($property,$this->coordinatesArray) && !is_numeric($value)) {
                $value = 0;
            }

            if (preg_match("@^[a-zA-Z0-9%+-_]*$@", $value)){
                $value = urldecode($value);
            }

            $this->$property = mysql_real_escape_string($value);
        }

        if (is_array($value)) {
            $this->$property = $this->setPropertyArray($value);
        }
    }

    protected function setSortBy($value = '')
    {
        if (isset($this->dataArray['sort']) && !empty($this->dataArray['sort'])) {
            $sortValue = urldecode($this->dataArray['sort']);
            $this->setProperty('sortBy', $sortValue, $value);
        }
    }

    protected function setSearchQuery($value = '')
    {
        $this->setProperty('searchQuery', $this->dataArray['q'], $value);
    }

    protected function setTopLevelId($value = '')
    {
        //this the only run that executes on a set
        $this->runSearchQuery();
        if ($this->target == 'search') {
            $this->setProperty('topLevelId', $this->dataArray['pid'], $value);
        } else if ($this->target == 'lead') {
            $this->setProperty('topLevelId', $this->dataArray['top_id'], $value);
        }
    }

    protected function setSubLevelId($value = '')
    {
        if ($this->target == 'search') {
            $this->setProperty('subLevelId', $this->dataArray['tid'], $value);

        } else {
            $this->setProperty('subLevelId', $this->dataArray['sub_id'], $value);
        }
        if(!is_null($this->subLevelId) && !is_null($this->topLevelId)){
            $this->topLevelId = null;
        }
    }
    protected function setOverallRating($value = '')
    {
        $this->setProperty('overallRating', $this->dataArray['overall_rating'],$value);
    }

    protected function setSubSubLevelId($value = '')
    {
        if ($this->target == 'search') {
            $this->setProperty('subSubLevelId', $this->dataArray['ttid'], $value);
            if(!is_null($this->subLevelId) && !is_null($this->subSubLevelId)){
                $this->subLevelId = null;
            }
        } else {
            $this->setProperty('subSubLevelId', $this->dataArray['sub_sub_id'], $value);
        }
    }

    protected function setProfessionId($value = '')
    {
        if ($this->target == 'search') {
            global $profession;
            $this->setProperty('professionId', $profession['profession_id'], $value);
        }
    }

    protected function setServiceId($value = '')
    {
        if ($this->target == 'search') {
            global $service;
            $this->setProperty('serviceId', $service['service_id'], $value);
        }
    }

    protected function setCountryCode($value = '')
    {
        if ($this->target == 'search') {
            global $country;

            $this->setProperty('countryCode', $country['country_code'], $value);
        }
    }

    protected function setParentId($value = 0)
    {
        if ( $this->target == 'search' && isset($this->dataArray['parentid']) ) {
            $this->setProperty('parentId', $this->dataArray['parentid'], $value);
        }

    }

    protected function setCountryShortName($value = '')
    {
        $this->setProperty('countryShortName', $this->dataArray['country_sn'], $value);
    }
    protected function setStateSearch($value = '')
    {
        $this->setProperty('stateSearch', $this->dataArray['stateSearch'], $value);
    }
    protected function setAdminLevel1ShortName($value = '')
    {
        $this->setProperty('adminLevel1ShortName', $this->dataArray['adm_lvl_1_sn'], $value);
    }

    protected function setCountyShortName($value = '')
    {
        $this->setProperty('countyShortName', $this->dataArray['county_sn'], $value);
    }

    protected function setZipCode($value = '')
    {
        if(isset($this->dataArray['postal_code'])){
            $postalCode = $this->dataArray['postal_code'];
        }else if(isset($this->dataArray['postal_code_prefix'])){
            $postalCode = $this->dataArray['postal_code_prefix'];
        }
        $this->setProperty('zipCode', $postalCode, $value);
    }

    protected function setFormatSearch($value = '')
    {
        $this->setProperty('formatSearch', $this->dataArray['fsearch'], $value);
    }

    protected function setFormatAddress($value = '')
    {
        $this->setProperty('formatAddress', $this->dataArray['faddress'], $value);
    }

    protected function setStateShortName($value = '')
    {
        if ($this->target == 'search') {
            global $state;
            $this->setProperty('stateShortName', $state['state_sn'], $value);
        }
    }

    protected function setCityLongName($value = '')
    {
        if ($this->target == 'search') {
            global $city;

            if(isset($city['city_ln'])){
                $cityValue = $city['city_ln'];
            }

            $this->setProperty('cityLongName', $cityValue, $value);
        }
    }

    protected function setCity($value = '')
    {
        $this->setProperty('currentCity', $this->dataArray['city'], $value);
    }

    protected function setRadius($value = '')
    {
        $this->setProperty('radius', $this->dataArray['radius'], $value);
    }

    protected function setLocation($value = '')
    {
        if ($this->target == 'search') {
            $this->setProperty('location', $this->dataArray['location_value'], $value);
        } else if ($this->target == 'lead') {
            $this->setProperty('location', $this->dataArray['lead_location'], $value);
        }
    }

    protected function setLocationType($value = '')
    {
        $this->setProperty('locationType', $this->dataArray['location_type'], $value);
    }

    protected function setNeLat($value = '')
    {
        $this->setProperty('neLat', $this->dataArray['nelat'], $value);
    }

    protected function setNeLng($value = '')
    {
        $this->setProperty('neLng', $this->dataArray['nelng'], $value);
    }

    protected function setSwLat($value = '')
    {
        $this->setProperty('swLat', $this->dataArray['swlat'], $value);
    }

    protected function setSwLng($value = '')
    {
        $this->setProperty('swLng', $this->dataArray['swlng'], $value);
    }

    protected function setLat($value = '')
    {
        $this->setProperty('lat', $this->dataArray['lat'], $value);
    }

    protected function setLng($value = '')
    {
        $this->setProperty('lng', $this->dataArray['lng'], $value);
    }

    protected function setSubscriptionId($value = '')
    {
        if ($this->target == 'search') {
            global $subscription;
            $this->setProperty('subscriptionId', $subscription['subscription_id'], $value);
        }
    }

    protected function setMatchingLimit($value = '')
    {
        $this->setProperty('matchingLimit', $this->dataArray['matching_limit'], $value);
    }

    protected function setNoMembership($value = '')
    {
        $this->setProperty('noMembership', $this->dataArray['no_membership'], $value);
    }

    public function getTarget()
    {
        return $this->target;
    }
}

$BDMembersQuery         = new BDMembersQuery();
$_ENV['custom_object']  = $BDMembersQuery;
$_ENV['custom_query']   = $BDMembersQuery->getQuery();
$_ENV['sqlquery']       = $BDMembersQuery->getQuery();
?>