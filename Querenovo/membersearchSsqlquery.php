<?php

/**
 * This widget follows BD code standards
 * Widget Name: Search - Member - Search Query
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
    public $isServiceAreaSearch = false;

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
            if($this->isServiceAreaSearch){
                $this->selectQueryString .= ', ROW_NUMBER() OVER (PARTITION BY ud.user_id ORDER BY '.implode(", ", $this->orderParameters).' ) AS RowNum';
            }
        }
    }

    public function getOrderParameters(){
        return $this->orderParameters;
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
            $this->orderParameters = array_unique($this->orderParameters);
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
    protected $topLevelId = NULL;
    protected $subLevelId = NULL;
    protected $overallRating = NULL;
    protected $subSubLevelId = NULL;
    protected $professionId = 0;
    protected $countryCode = '';
    protected $countryShortName = '';
    protected $stateSearch = '';
    protected $stateSearchLN = '';
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
    public    $whereKeyWordStrict = "";
    public    $isCountrySearch  = false;
    public    $isStateSearch    = false;
    public    $subRan           = false;
    public    $subWasSent       = false;
    public    $subsubWasSent    = false;
    public    $topWasSent       = false;
    public    $multiCategory    = false;


    public function __construct()
    {
        parent::__construct();
        $this->devMode = (isset($_GET['devmode'])) ? $_GET['devmode'] : 0;
        $this->run();
    }

    public function isInclusiveSearch(){
        global $w;

        $isInclusiveSearch = false;

        if(isset($w['member_search_type']) && $w['member_search_type'] == "inclusive"){
            $isInclusiveSearch = true;
        }

        return $isInclusiveSearch;
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

            } else if($this->sortBy == "last_name_asc"){
                $this->sortBy = "last_name_asc";
            } else if($this->sortBy == "last_name_desc"){
                $this->sortBy = "last_name_desc";
            } else if ($this->sortBy == "reviews") {
                $this->sortBy = "reviews";
            } else if ($this->sortBy == "most_likes") {
                $this->sortBy = "most_likes";
            } else if ($this->sortBy == "least_likes") {
                $this->sortBy = "least_likes";
            } else if($this->sortBy == "userid-asc" || $this->sortBy == "userid-desc" || $this->sortBy == "random") {
                $this->sortBy = $this->sortBy;
            }  else {
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
        global $isPrettyUrl;
        if ($this->stateShortName != "" && $isPrettyUrl != true) {
            global $profs, $state;
            $countryCode = "";

            if($profs['country_shortname'] != ""){
                $countryCode = $profs['country_shortname'];
            }else if($profs['country_code'] != ""){
                $countryCode = $profs['country_code'];
            }

            if($countryCode != ""){
                $this->addQueryParameter('whereParameters', ' ((ud.state_code = "' . $state['state_sn'] . '" OR ud.state_ln = "' . $state['state_sn'] . '") AND ud.country_code = "'.$countryCode.'") ');
            }else{
                $this->addQueryParameter('whereParameters', ' (ud.state_code = "' . $state['state_sn'] . '" OR ud.state_ln = "' . $state['state_sn'] . '") ');
            }

            $profs['state_name'] = $state['state_ln'];
            $profs['state_code'] = $state['state_sn'];
            $profs['state_filename'] = $state['state_filename'];

            //stateSearchLN
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
            $city['city_ln'] = mysql_real_escape_string(stripslashes(stripslashes($city['city_ln'])));
            $this->addQueryParameter('whereParameters', " ud.city = '" . $city['city_ln'] . "' ");
        }
    }

    /**
     * This function that would try to look for a keyword match on list_professions
     */
    public function runTopSearchQuery(){
        global $w;

        //categories ids holder 1 - N
        $topCategoriesIds           = array();

        //search WHERE
        if(!empty($this->searchQuery)){
            $topFileNameQuery = new bdString($this->searchQuery);

            if($this->isInclusiveSearch()){
                $decodedString = $topFileNameQuery->urlencodeStringChain('-')->modifiedValue;
                $whereFields = "
                    lp.name LIKE '% " . $this->searchQuery . "' OR
                    lp.name LIKE '%-" . $this->searchQuery . "%' OR
                    lp.name LIKE '%," . $this->searchQuery . "%' OR
                    lp.name LIKE '" . $this->searchQuery . "%' OR
                    lp.name LIKE '% " . $this->searchQuery . " %' OR
                    lp.filename LIKE '%-".$decodedString."' OR
                    lp.filename LIKE '".$decodedString."-%' OR
                    lp.filename LIKE '".$decodedString."' OR
                    lp.filename LIKE '%-".$decodedString."-%' OR
                ";
            }else{
                $whereFields = "
                    lp.name LIKE '" . $this->searchQuery . "' OR
                    lp.filename LIKE '".$topFileNameQuery->urlencodeStringChain('-')->modifiedValue."' OR
                ";
            }


            $topCategoryWhereString     = "
            WHERE
                ".$whereFields."
                FIND_IN_SET(REGEXP_REPLACE('".$this->searchQuery."', '[[:space:]]+', '') , REGEXP_REPLACE(lp.keywords, '[[:space:]]+', '') ) > 0
            ";

        }

        //we check if we sent something over $_GET
        if(is_array($this->topLevelId)){
            $topCategoriesIds   = $this->topLevelId;
        }else if($this->topLevelId > 0){
            $topCategoriesIds   = array($this->topLevelId);
        }



        //if we didn't sent anything over $_GET we look for TOP CATEGORIES that match our keyword
        if(count($topCategoriesIds) <= 0 && $this->subRan === false && !empty($this->searchQuery)){
            $checkTopQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "
                SELECT
                    lp.profession_id
                FROM
                    `list_professions` AS lp
                ".$topCategoryWhereString."
                ORDER BY profession_id ASC
            ");

            //we include all categories
            if(isset($w['member_search_type']) && $w['member_search_type'] == "inclusive"){
                if (mysql_num_rows($checkTopQuery) > 0) {
                    while ( $topCategory = mysql_fetch_assoc($checkTopQuery) ) {
                        $topCategoriesIds[] = $topCategory['profession_id'];
                    }
                }
            }else{//we include just the first category result
                if (mysql_num_rows($checkTopQuery) > 0) {
                    $topCategory        = mysql_fetch_assoc($checkTopQuery);
                    $topCategoriesIds[]    = $topCategory['profession_id'];
                }
            }

            $this->topWasSent = false;
        }else if(count($topCategoriesIds) > 0){
            $this->topWasSent = true;
        }

        if(count($topCategoriesIds) > 0){

            if ($this->target == 'search') {
                $this->dataArray['pid'] = $topCategoriesIds;
                $this->setProperty('topLevelId', $this->dataArray['pid'], $value);
            } else if ($this->target == 'lead') {
                $this->dataArray['top_id'] = $topCategoriesIds;
                $this->setProperty('topLevelId', $this->dataArray['top_id'], $value);
            }

        }
    }

    /**
     * This function that would try to look for a keyword match on the list_services (sub or sub sub) OR we used the sent ids or id
     */
    public function runSubSearchQuery(){
        global $w;
        $subCategoryID          = false;//the sub / sub sub category id
        $isSub                  = false;//to know if is sub or sub sub
        $subCategoriesIds       = array();//the list of the sub ids
        $subSubCategoriesIds    = array(); //the list of the sub sub ids
        $subIdSent              = true;//to know if we sent the sub id or we searched it
        $subCategoryWhereString = "";//to store the where string to look for a keyword

        if (!empty($this->subSubLevelId)) {//if we have a sub sub
            $subCategoryID  = $this->subSubLevelId;//we store the sub sub ID into the container
        } else if(!empty($this->subLevelId)){//if we have a sub
            $subCategoryID = $this->subLevelId;//we store the sub ID into the container
            $isSub          = true; // we marked that we are using a sub ID not a sub sub ID
        }

        if(!is_array($subCategoryID) && $subCategoryID > 0 ){//if the container is not an array and the number is greater than 0

            if(is_string($subCategoryID) && strstr($subCategoryID, ',') !== false){
                $subCategoryID = explode(',', $subCategoryID);
                $this->multiCategory = true;
            }else{
                $subCategoryID = array($subCategoryID);//we make the container id into an array
            }

        }

        

        if($isSub === true && $subCategoryID !== false){//if we are searching a sub ID we store our container into the sub ids list
            $subCategoriesIds       = $subCategoryID;
            //we store into the object if the sub id / sub sub id was sent over $_GET
            $this->subWasSent = true;
        }else if($subCategoryID !== false){//if we are searching a sub sub ID we store our container into the sub sub ids list
            $subSubCategoriesIds    = $subCategoryID;
            $this->subWasSent = true;
        }

        if(!empty($this->searchQuery)){//if we have a keyword we set the msyql keyword WHERE search
            $subFileNameQuery = new bdString($this->searchQuery);

            $subsubWhere = ((count($subSubCategoriesIds) <= 0 && $isSub === true))? ' AND master_id IN ('.implode(',', $subCategoriesIds).')' : '';

            if($this->isInclusiveSearch()){
                $decodeString =$subFileNameQuery->urlencodeStringChain('-')->modifiedValue;
                $whereFields = "
                    ls.name LIKE '% " . $this->searchQuery . "' OR
                    ls.name LIKE '%-" . $this->searchQuery . "%' OR
                    ls.name LIKE '%," . $this->searchQuery . "%' OR
                    ls.name LIKE '" . $this->searchQuery . "%' OR
                    ls.name LIKE '% " . $this->searchQuery . " %' OR
                    ls.filename LIKE '%-".$decodeString."' OR
                    ls.filename LIKE '".$decodeString."-%' OR
                    ls.filename LIKE '".$decodeString."' OR
                    ls.filename LIKE '%-".$decodeString."-%' OR
                ";
            }else{
                $whereFields = "
                    ls.name LIKE '" . $this->searchQuery . "' OR
                    ls.filename LIKE '".$subFileNameQuery->urlencodeStringChain('-')->modifiedValue."' OR
                ";
            }

            //we remove any any spaces between the "," with REPLACE
            $subCategoryWhereString = "
                WHERE
                (
                    (
                        ".$whereFields."
                        FIND_IN_SET(REGEXP_REPLACE('".$this->searchQuery."', '[[:space:]]+', '') , REGEXP_REPLACE(ls.keywords, '[[:space:]]+', '') ) > 0
                    )
                    ".$subsubWhere."
                )
            ";
        }

        $topWasRan = false;

        //we look for tops first if we are in strict search
        if(!$this->isInclusiveSearch()){
            $this->runTopSearchQuery();
            //if we found one we dont look for subs
            if(!empty($this->topLevelId) && count($this->topLevelId) > 0 && $this->topWasSent === false){
                $topWasRan = true;
            }
        }

        //if we didn't sent anything over $_GET, We look for SUB / SUB_SUB CATEGORIES that match our keyword WHERE
        if( ((count($subCategoriesIds) <= 0) || (count($subSubCategoriesIds) <= 0)) && $subCategoryWhereString != "" && $topWasRan === false){

            if($this->isInclusiveSearch()){
                //since we run the sub first we look for any top (sent or matched)
                $this->runTopSearchQuery();
            }

            if(!empty($this->topLevelId) && count($this->topLevelId) > 0 && $this->topWasSent === true){//if we found a top and the top was sent we look for the subs that belongs to the top sent
                //we return the type of WHERE base if we have the msyql keyword WHERE search set
                $subCategoryWhereString .= (!empty($subCategoryWhereString))? " AND profession_id IN (".implode(",", $this->topLevelId).") " : " WHERE profession_id IN (".implode("','", $this->topLevelId).") ";

                if(count($subCategoriesIds) > 0 && $subCategoryWhereString != ""){

                    $subCategoryWhereString .= ($subsubWhere != "") ? "OR service_id IN (".implode(",", $subCategoriesIds).")" : " AND service_id IN (".implode(",", $subCategoriesIds).")";
                }
            }

            $checkSubQuery = false;

            if($subCategoryWhereString != ""){
                //we run the mysql search
                $checkSubQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "
                    SELECT
                        ls.service_id,
                        ls.name,
                        ls.profession_id,
                        ls.master_id
                    FROM
                        `list_services` AS ls
                    ".$subCategoryWhereString."
                    ORDER BY service_id ASC
                ");
            }

            //we include all categories
            if(isset($w['member_search_type']) && $w['member_search_type'] == "inclusive"){

                if (mysql_num_rows($checkSubQuery) > 0) {
                    while ( $subLevelCategory   = mysql_fetch_assoc($checkSubQuery) ) {
                        if($subLevelCategory['master_id'] != 0){
                            $subSubCategoriesIds[] = $subLevelCategory['service_id'];
                        }else if($this->subWasSent === false){
                            $subCategoriesIds[]     = $subLevelCategory['service_id'];
                        }
                    }
                }
            }else{//we include just the first category result
                if (mysql_num_rows($checkSubQuery) > 0) {
                    $subLevelCategory       = mysql_fetch_assoc($checkSubQuery);
                    if($subLevelCategory['master_id'] != 0){
                        $subSubCategoriesIds[] = $subLevelCategory['service_id'];
                    }else if($this->subWasSent === false){
                        $subCategoriesIds[]     = $subLevelCategory['service_id'];
                    }
                }
            }

        }else if($topWasRan === false){//if we have sub level sent we just look for the top sent or that matched our keywords
            $this->runTopSearchQuery();
        }

        if(count($subSubCategoriesIds) > 0){//if we have subs subs to stored them into the object
            $this->subSubLevelId    = $subSubCategoriesIds;
        }

        if(count($subCategoriesIds) > 0 && count($subSubCategoriesIds) <= 0){//if we have subs to stored them into the object
            $this->subLevelId       = $subCategoriesIds;
        }else{
            $this->subLevelId       = $subCategoriesIds;
        }

        if( (is_array($this->subLevelId) && count($this->subLevelId) > 0) || (is_array($this->subSubLevelId) && count($this->subSubLevelId) > 0)){//if we found a sub or sub sub we marked this proccess as ran
            $this->subRan = true;
        }
    }

    /**
     * This function that would try to look for a keyword match on users_data + users_meta columns
     */
    public function runMemberSearchQuery(){
        global $w,$dc;

        $searchType             = (isset($dc['keyword_search_filter']))? $dc['keyword_search_filter'] : "level_3" ;
        $_ENV['searchQuery']    = $this->searchQuery;

        if($searchType == "level_3"){
            $addonMemberKeywords2   = getAddOnInfo('search_members_keywords','929ed98697e21e4c92aca80d8dd680bb');
        }

        if(!empty($this->searchQuery)){

            if($searchType == "level_3"){
                $addonMemberKeywords    = getAddOnInfo("search_members_keywords","6a2f4bd232e526ac53144b56da479f03");
            }

            if ($searchType == "level_3" && isset($addonMemberKeywords['status']) && $addonMemberKeywords['status'] === 'success') {
                echo widget($addonMemberKeywords['widget'],"",$w['website_id'],$w);
                $whereValue = $_ENV['whereValue'];
            } else {
                $whereValue = '(CONCAT(TRIM(ud.first_name)," ",TRIM(ud.last_name)) LIKE "%' . mysql_real_escape_string(stripslashes(stripslashes($this->searchQuery))) . '%" OR TRIM(ud.company) LIKE "%' . mysql_real_escape_string(stripslashes(stripslashes($this->searchQuery))) . '%")';
            }

            if($this->isInclusiveSearch()){
                $this->whereKeyword         = $whereValue;
            }else{
                $this->whereKeyWordStrict   = $whereValue;
            }
        }

        if ($searchType == "level_3" && isset($addonMemberKeywords2['status']) && $addonMemberKeywords2['status'] === 'success' && (!empty($this->searchQuery) || (isset($w['search_results_require_complete_profile']) && $w['search_results_require_complete_profile'] == 1 && isset($w['complete_profile_fields']) && $w['complete_profile_fields'] != "") )) {
            echo widget($addonMemberKeywords2['widget'],"",$w['website_id'],$w);
            $userMetaQuery = $_ENV['userMetaQuery'];
            $this->addQueryParameter('tablesParameters', $userMetaQuery);
            unset($_ENV['userMetaQuery']);
        }

    }

    public function runTopLevelCategory(){

        $isInclusiveSearch  = $this->isInclusiveSearch();
        $topCategoryWhere   = "";
        $subLevelwhereParameter = "";

        if($isInclusiveSearch === true){
            $subLevelwhereParameter = $this->runSubLevelCategory(true);
        }

        if(!empty($this->topLevelId)){

            $topCategoryWhere = " ud.profession_id IN (" . implode(",", $this->topLevelId) . ")";

            if($isInclusiveSearch === true){

                if($this->whereKeyword != "" && $this->topWasSent === false && $this->subWasSent === false){//keyword sent , no top sent ,  no sub sent.

                    //we check if the sub is empty
                    $whereParameter = (!empty($subLevelwhereParameter))? "(".$this->whereKeyword." OR ".$topCategoryWhere." OR ".$subLevelwhereParameter.")" : "(".$this->whereKeyword." OR ".$topCategoryWhere.")" ;

                }else if($this->whereKeyword != "" && $this->topWasSent === true && $this->subWasSent === false){//keyword sent , top sent , no sub sent

                    //we check if the sub is empty
                    $whereParameter = (!empty($subLevelwhereParameter))? "(".$this->whereKeyword." OR ".$subLevelwhereParameter.") AND ".$topCategoryWhere : "(".$this->whereKeyword.") AND ".$topCategoryWhere;

                }else if($this->whereKeyword != "" && $this->topWasSent === false && $this->subWasSent === true){//keyword sent , no top sent , sub sent

                    $whereParameter = "(".$this->whereKeyword." OR ".$topCategoryWhere.") AND ".$subLevelwhereParameter;

                }else if($this->whereKeyword != "" && $this->topWasSent === true && $this->subWasSent === true){///keyword sent , top sent , sub sent

                    $whereParameter = $this->whereKeyword." AND ".$topCategoryWhere." AND ".$subLevelwhereParameter;

                }else if($this->topWasSent === true && $this->subWasSent === true){//no keyword sent , top sent , sub sent

                    $whereParameter = $topCategoryWhere." AND ".$subLevelwhereParameter;

                }else if($this->subWasSent === true){//only sub sent

                    $whereParameter = $subLevelwhereParameter;

                }else if($this->topWasSent === true){//only top sent

                    $whereParameter = $topCategoryWhere;

                }

            }else{//no inclusive search

                $whereParameter = $topCategoryWhere;


            }
        }else if( $this->topWasSent === false && $this->subWasSent === false && ( count($this->subLevelId) > 0 || count($this->subSubLevelId) > 0  ) ) {
            $whereParameter = ($this->whereKeyword != "") ? "( ".$this->whereKeyword." OR ".$subLevelwhereParameter." )" : $subLevelwhereParameter;
        }else if( $this->multiCategory === true || ($this->subWasSent === true && count($this->subLevelId) > 0) ) {
            $whereParameter = ($this->whereKeyword != "") ? "( ".$this->whereKeyword." AND ".$subLevelwhereParameter." )" : $subLevelwhereParameter;
        }else if( $this->topWasSent === false && $this->subWasSent !== false && $this->subsubWasSent !== false){
            $whereParameter = ($this->whereKeyword != "") ? "( ".$this->whereKeyword." AND ".$subLevelwhereParameter." )" : $subLevelwhereParameter;
        }else if($this->whereKeyword != ""){
            $whereParameter = $this->whereKeyword;
        }

        if($whereParameter != ""){// if where is not empty
            $this->addQueryParameter('whereParameters', $whereParameter);
        }

        if ($this->target == "search") {
            global $w, $profs, $profession;
            if ($w['enable_new_pretty_url_system'] == 1) {
                if($this->topWasSent === true){
                    $profs['profession'] = $w['profession'];
                    $getProfessionFilenameQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                            filename,
                            name
                        FROM
                            `list_professions`
                        WHERE
                            profession_id IN (" . implode(",", $this->topLevelId) . ")");
                    $getProfessionFilename = mysql_fetch_assoc($getProfessionFilenameQuery);

                    if($this->topLevelId != 0 && isset($getProfessionFilename['filename']) && isset($getProfessionFilename['name'])){
                        $profs['profession_filename']   = $getProfessionFilename['filename'];

                        $professionsArray = explode(',', $this->topLevelId[0]);
                        if (is_array($professionsArray)) {
                            foreach($professionsArray as $topCategory){

                                $professionNames[] = getProfession($topCategory, $w);
                            }
                            $profs['profession_name'] = implode(', ', array_filter($professionNames));
                        } else {
                            $profs['profession_name'] = $getProfessionFilename['name'];
                        }

                        $profs['new_filename'][3]       = "%profession_name~na%";
                    }
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

    public function runSubLevelCategory($returnQuery){
        global $w;

        $whereParameter = "";

        if ( ( (!empty($this->subLevelId) ) || !empty($this->subSubLevelId) ) && $this->serviceId <= 0 ) {

            //set wich is the dominant variable
            if (!is_null($this->subSubLevelId) && $this->subSubLevelId !== false) {
                $mainLevelId = $this->subSubLevelId;

                if(count($this->subLevelId) > 0){
                    $mainLevelId = array_merge($mainLevelId,$this->subLevelId);
                }
            } else {
                $mainLevelId = $this->subLevelId;
            }

            if (is_array($mainLevelId) && ( $this->isInclusiveSearch() || (isset($_GET['dynamic']) && isset($_GET['dynamic']) == 1) ) ) {
                $extraMultiCategoryWhere    = ($this->multiCategory === true)? " OR ls.master_id IN (" . implode(",", $mainLevelId) . ") ":"";
                $whereParameter             = " (rs.rel_service_id IN (" . implode(",", $mainLevelId) . ") ".$extraMultiCategoryWhere." OR (st.service_limit = 'all')) ";

                if($this->target === 'search' && empty($this->searchQuery) && !is_null($this->topLevelId) ){
                    $whereParameter = "( ". $whereParameter ." OR (rs.rel_service_id IN (" . implode(",", $mainLevelId ) . ") OR (st.service_limit = 'all' AND (list_service_profession_id = ud.profession_id OR ud.profession_id IN (" . implode(",", $this->topLevelId) . ")))) ) ";
                }else if(!isset($_GET['dynamic'])){
                    $whereParameter = "( ". $whereParameter ." OR (rs.rel_service_id IN (" . implode(",", $mainLevelId) . ") OR (st.service_limit = 'all' AND list_service_profession_id = ud.profession_id)) )";
                }

                $relServiceSTblSelect       = " rss.service_id IN (" . implode(",", $mainLevelId) . ") ";
            } else {

                if(is_array($mainLevelId)){
                    $mainLevelId = $mainLevelId[0];
                }

                if(empty($this->searchQuery) && !is_null($this->topLevelId)){
                    $whereParameter         = " (rs.rel_service_id IN (" . $mainLevelId . ") OR ls.master_id = '" . $mainLevelId . "' OR (st.service_limit = 'all' AND (list_service_profession_id = ud.profession_id OR ud.profession_id IN (" . implode(",", $this->topLevelId) . ")))) ";
                }else if(!isset($_GET['dynamic'])){
                    $whereParameter         = " (rs.rel_service_id IN (" . $mainLevelId . ") OR ls.master_id = '" . $mainLevelId . "' OR (st.service_limit = 'all' AND list_service_profession_id = ud.profession_id)) ";
                }

                $relServiceSTblSelect   = " rss.service_id IN (" . $mainLevelId . ")";

                if($this->whereKeyWordStrict != "" && $this->topWasSent === false && $this->subWasSent === false && $this->subsubWasSent === false ){
                    $whereParameter = "(".$this->whereKeyWordStrict." OR ".$whereParameter.")";
                    $this->whereKeyWordStrict  = "";
                }
            }

            if($returnQuery === false){
                $this->addQueryParameter('whereParameters', $whereParameter);
            }

            $joinType       = ($returnQuery === true)? "LEFT JOIN" : "INNER JOIN";

            if($this->topWasSent && $this->subWasSent && empty($this->searchQuery)){
                $joinType = "LEFT JOIN";
            }else if(!$this->topWasSent && !$this->subWasSent && !empty($this->searchQuery) && $mainLevelId != ""){
                $joinType = "LEFT JOIN";
            }

            $inclusiveWHERE = ($returnQuery === true)? "" : "";

            if($inclusiveWHERE != ""){
                $this->addQueryParameter('whereParameters', $inclusiveWHERE);
            }

            $listSerivesJoinType = "LEFT JOIN";
            $isDynamicSearchType = (isset($_GET['dynamic']))?true:false;

            if(is_array($mainLevelId)){
                $filteredCategories = array_filter($mainLevelId);
                arsort($filteredCategories);
                $filteredCategories = implode(',',$filteredCategories);
            }else{
                $filteredCategories = $mainLevelId;
            }

            //new dynamic search advance settings to be either on all Categories or just 1
            if($isDynamicSearchType && isset($w['dynamic_search_or_behavior']) && $w['dynamic_search_or_behavior'] == 0 && strpos($filteredCategories, ',') !== false){

                $sqlTablesParameters = " ".$joinType." (
                    SELECT
                        GROUP_CONCAT(rss.service_id ORDER BY rss.service_id DESC) as user_categories,
                        rss.service_id AS rel_service_id,
                        rss.user_id AS rel_service_user_id,
                        lsr.profession_id as list_service_profession_id
                    FROM
                        `rel_services` AS rss,
                        `list_services` AS lsr
                    WHERE
                        rss.service_id = lsr.service_id AND 
                        " . $relServiceSTblSelect . "
                    GROUP BY
                        rss.user_id
                    HAVING user_categories = '".$filteredCategories."'

                ) AS rs ON ud.user_id = rs.rel_service_user_id

                ".$listSerivesJoinType." `list_services` AS ls ON ls.service_id = rs.rel_service_id

                ";
            }else{
                $sqlTablesParameters = " ".$joinType." (
                    SELECT
                        rss.service_id AS rel_service_id,
                        rss.user_id AS rel_service_user_id,
                        lsr.profession_id as list_service_profession_id
                    FROM
                        `rel_services` AS rss,
                        `list_services` AS lsr
                    WHERE
                        rss.service_id = lsr.service_id AND 
                        " . $relServiceSTblSelect . "
                    GROUP BY
                        rss.service_id,rss.user_id

                ) AS rs ON ud.user_id = rs.rel_service_user_id

                ".$listSerivesJoinType." `list_services` AS ls ON ls.service_id = rs.rel_service_id

                ";
            }

            if ($this->target == 'search' && $this->subWasSent === true) {
                global $w, $profs;

                if (is_array($mainLevelId)) {
                    $serviceIdSelect        = $mainLevelId[0];
                    $profs['service_name']  = getServiceById($mainLevelId[0], $w);
                    $profs['sub_category_name'] =  $profs['service_name'];
                    if (count($this->subSubLevelId) > 0 && $this->target === 'search' && count($this->dataArray['tid']) > 0) {
                        $profs['sub_category_name'] = getServiceById($this->dataArray['tid'], $w);
                        $profs['sub_sub_category_name'] = $profs['service_name'];
                    }

                } else {
                    $serviceIdSelect = $mainLevelId;
                }

                $getServiceFilenameQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                        filename,
                        name,
                        master_id
                    FROM
                        `list_services`
                    WHERE
                        service_id = '" . $serviceIdSelect . "'");

                $getServiceFilename = mysql_fetch_assoc($getServiceFilenameQuery);

                if (is_array($mainLevelId)) {
                    foreach($mainLevelId as $subCategory){
                        $serviceNames[] = getServiceById($subCategory, $w);
                    }
                    $profs['service_name'] = implode(', ', array_filter($serviceNames));
                } else {
                    $profs['service_name'] = $getServiceFilename['name'];
                }

                $w['sub_category_name'] = $profs['service_name'];

                if($getServiceFilename['master_id'] > 0){
                    $w['sub_sub_category_name'] = $profs['sub_sub_category_name']   = $profs['service_name'];
                    $w['sub_category_name']     = $profs['sub_category_name']       = getServiceById($getServiceFilename['master_id'], $w);
                }

                $profs['service_filename'] = $getServiceFilename['filename'];
                $profs['new_filename'][4] = "%service_name~na%";

            }

            $this->addQueryParameter('tablesParameters', $sqlTablesParameters);
        }

        if($this->whereKeyWordStrict != "" && empty($this->topLevelId)){
            $this->addQueryParameter('whereParameters', $this->whereKeyWordStrict);
        }else if($this->whereKeyWordStrict != "" && $this->topWasSent && $this->subWasSent){
            $this->addQueryParameter('whereParameters', $this->whereKeyWordStrict);
        }else if($this->whereKeyWordStrict != "" && $this->target == 'lead'){
            $this->addQueryParameter('whereParameters', $this->whereKeyWordStrict);
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
                    service_id,
                    profession_id
                FROM
                    `list_services`
                WHERE
                    filename = '" . $service['filename'] . "'");

            while ($duplicatedFilenameService = mysql_fetch_assoc($duplicatedFilenameServiceQuery)) {
                $serviceFilenames[] = $duplicatedFilenameService['service_id'];
                $professionId = $duplicatedFilenameService['profession_id'];
            }
            $sqlTablesParameter = " LEFT JOIN (
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

            $sqlWhereParameter = " (rs.rel_service_id IN (" . implode(",", $serviceFilenames) . ") OR (st.service_limit = 'all' AND ud.profession_id = '".$professionId."')) ";

            $this->addQueryParameter('tablesParameters', $sqlTablesParameter);
            $this->addQueryParameter('whereParameters', $sqlWhereParameter);

            $profs['service_filename'] = $service['filename'];
            $profs['service_name'] = $service['name'];

            $w['sub_category_name'] = $profs['sub_category_name'] =  $service['name'];
            if($service['master_id'] > 0){
                $w['sub_category_name']     = $profs['sub_category_name'] = getServiceById($service['master_id'], $w);
                $w['sub_sub_category_name'] = $profs['sub_sub_category_name'] = $service['name'];
            }
            $profs['new_filename'][4] = "%service_name~na%";

        }
    }

    public function runSubscription()
    {
        if ($this->subscriptionId > 0) {
            global $profs, $subscription, $page;
            if($page['seo_type'] != 'search_results_all'){
                $this->addQueryParameter('whereParameters', " ud.subscription_id = '" . $this->subscriptionId . "' ");
            }
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

                    $sqlWhereParameter = " (st.search_membership_permissions LIKE '%visitor%' OR st.search_membership_permissions = '' OR st.search_membership_permissions = ',' ) ";
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
                    "street_number",
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
                    //we filled the admin level one short name in case is empty
                    if($this->adminLevel1ShortName == ""){
                        $this->adminLevel1ShortName = $this->stateSearch;
                    }
                    break;
                case 'administrative_area_level_2':
                    $this->searchMethod = $w['adm_area_lvl_2_search_method'];
                    break;
                case 'locality':

                    if($this->adminLevel1ShortName == ""){
                        $this->adminLevel1ShortName = $this->stateSearch;
                    }

                    if($w['enable_localized_search'] == 1 && $this->countyShortName == $this->stateSearch){

                        $whereCity = array(
                            array(
                                'column'    => 'city_ln',
                                'logic'     => '=',
                                'value'     => $this->currentCity
                            ),
                            array(
                                'column'    => 'country_sn',
                                'logic'     => '=',
                                'value'     => $this->countryShortName
                            )
                        );

                        $result = bd_controller::location_cities(WEBSITE_DB)->get($whereCity);

                        if($result !== false && !is_array($result)){
                            $this->adminLevel1ShortName = $result->state_sn;
                        }

                    }

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
            global $searchMethodRadius;
            $searchMethodRadius = $this->searchMethod;

            //variables that will hold the processing info and the final string added to the main sql for service areas
            $directNameAndService       = array();
            $directNameAndSqlService    = "";
            $directNameOrService        = array();
            $directNameOrSqlService     = "";
            //variables that will hold the processing info and the final string added to the main sql for direct user info
            $directNameAndUser          = array();
            $directNameAndSqlUser       = "";

            //Check for COUNTRY
            if ($this->countryShortName != "") {
                $directNameOrService['country_sn'][]    = " sa2.country_sn = '" . $this->countryShortName . "' ";
                $directNameOrService['country_sn'][]    = " sa2.location_type = 'Country' ";

                //strict search check to allow only members in main location
                if($w['strict_search_results'] == 1){
                    //we only have bounds for COUNTRY so we add both into the AND and OR
                    $directNameAndUser['country_code']       = " ud.country_code = '" . $this->countryShortName . "' ";
                    $directNameAndService['country_code']    = " sa2.country_sn = '" . $this->countryShortName . "' ";
                }
            }

            //Check for the STATE
            if ($this->adminLevel1ShortName != "") {

                //we only have bounds for STATE so we add both into the AND and OR
                $directNameOrService['adm_lvl_1_sn'][]  = " sa2.country_sn = '" . $this->countryShortName . "' ";
                $directNameOrService['adm_lvl_1_sn'][]  = " sa2.state_sn = '" . $this->adminLevel1ShortName . "' ";
                $directNameOrService['adm_lvl_1_sn'][]  = " sa2.location_type = 'State' ";

                //strict search check to allow only members in main location
                if ($w['strict_search_results'] == 1) {
                    $directNameAndService['state_code']     = " sa2.state_sn = '" . $this->adminLevel1ShortName . "' ";
                    $directNameAndUser['state_code']        = ' ud.state_code = "' . $this->adminLevel1ShortName . '" ';
                }
            }

            //Check for COUNTY
            if ($this->countyShortName != "") {
                if($this->adminLevel1ShortName == ""){
                    $this->adminLevel1ShortName = $this->stateSearch;
                }
                /**
                 * IMPORTANT
                 *TODO:
                 *add strict search for county
                 */
                $directNameOrService['county_sn'][] = " sa2.country_sn = '" . $this->countryShortName . "' ";
                $directNameOrService['county_sn'][] = " sa2.state_sn = '" . $this->adminLevel1ShortName . "' ";
                $directNameOrService['county_sn'][] = " sa2.county_sn = '" . $this->countyShortName . "' ";
                $directNameOrService['county_sn'][] = " sa2.location_type = 'County' ";
            }

            //Check for CITY
            if($this->currentCity != "" || $this->target == 'lead' ){
                $this->currentCity              = mysql_real_escape_string(stripslashes(stripslashes($this->currentCity)));
                $directNameOrService['city'][]  = " sa2.country_sn = '" . $this->countryShortName . "' ";
                $directNameOrService['city'][]  = " sa2.state_sn = '" . $this->adminLevel1ShortName . "' ";
                $directNameOrService['city'][]  = " sa2.county_sn = '" . $this->countyShortName . "' ";
                $directNameOrService['city'][]  = " sa2.city = '" . $this->currentCity . "' ";
                $directNameOrService['city'][]  = " sa2.location_type = 'City' ";
                //strict search check to allow only members in main location
                if ($w['locality_search_method'] == "bounds") {
                    $directNameAndUser['country_code']          = " ud.country_code = '" . $this->countryShortName . "' ";
                    $directNameAndUser['state_code']            = ' ud.state_code = "' . $this->adminLevel1ShortName . '" ';
                    $directNameAndUser['city']                  = ' ud.city = "' . $this->currentCity . '" ';

                    $directNameAndService['country_code']       = " sa2.country_sn = '" . $this->countryShortName . "' ";
                    $directNameAndService['state_code']         = " sa2.state_sn = '" . $this->adminLevel1ShortName . "' ";
                    $directNameAndService['city']               = " sa2.city = '" . $this->currentCity . "' ";
                }
            }

            if($this->zipCode != ""){
                if($this->adminLevel1ShortName == ""){
                    $this->adminLevel1ShortName = $this->stateSearch;
                }

                $directNameOrService['zip_code'][]  = " sa2.country_sn = '" . $this->countryShortName . "' ";
                $directNameOrService['zip_code'][]  = " sa2.state_sn = '" . $this->adminLevel1ShortName . "' ";
                $directNameOrService['zip_code'][]  = " sa2.county_sn = '" . $this->countyShortName . "' ";
                $directNameOrService['zip_code'][]  = " sa2.city = '" . $this->currentCity . "' ";
                $directNameOrService['zip_code'][]  = " sa2.zip_code = '" . $this->zipCode . "' ";
                $directNameOrService['zip_code'][]  = " sa2.location_type = 'Zip' ";

                if ($w['zipcode_search_method'] == "bound") {
                    $directNameAndUser['country_code']          = " ud.country_code = '" . $this->countryShortName . "' ";
                    $directNameAndUser['state_code']            = ' ud.state_code = "' . $this->adminLevel1ShortName . '" ';
                    $directNameAndUser['city']                  = ' ud.city = "' . $this->currentCity . '" ';
                    $directNameAndUser['zip_code']              = ' ud.zip_code = "' . $this->zipCode . '" ';

                    $directNameAndService['country_code']       = " sa2.country_sn = '" . $this->countryShortName . "' ";
                    $directNameAndService['state_code']         = " sa2.state_sn = '" . $this->adminLevel1ShortName . "' ";
                    $directNameAndService['city']               = " sa2.city = '" . $this->currentCity . "' ";
                    $directNameAndService['zip_code']           = " sa2.zip_code = '" . $this->zipCode . "' ";
                }
            }

            if (count($directNameOrService) > 0) {
                $stagingOrElements = array();
                foreach ($directNameOrService as $dnokey => $dnovalue) {
                    $stagingOrElements[] = " ( " . implode(" AND ", $dnovalue) . " ) ";
                }

                $directNameOrSqlService = " OR ( " . implode(" OR ", $stagingOrElements) . " ) ";
            }

            //create the sql code that will check on the main user info against the location names
            if (count($directNameAndService) > 0) {
                $directNameAndSqlService = " AND " . implode(" AND ", $directNameAndService);
            }

            //create the sql code that will check on the main user info against the location names
            if (count($directNameAndUser) > 0) {
                $directNameAndSqlUser = " AND " . implode(" AND ", $directNameAndUser);
            }

            //check if the fsearch (force search) parameter is not empty, if not, force the search to be radius because there is not enough info to make a bounds search
            if ($this->formatSearch != "") {
                $this->searchMethod     = "radius";
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
                    $this->isServiceAreaSearch = true;

                    $sqlBaseForDistance = "COALESCE((" . $metricUnit . " * acos( cos( radians(" . $this->lat . ") ) * cos( radians( ud.lat ) ) * cos( radians( ud.lon ) - radians(" . $this->lng . ") ) + sin( radians(" . $this->lat . ") ) * sin(radians(ud.lat)) ) ),0)";
                    $this->addQueryParameter('selectParameters', $sqlBaseForDistance . " AS distance");
                    $this->addQueryParameter('selectParameters', "user_service_areas.service_area_user_id AS service_area");
                    $this->addQueryParameter('selectParameters', "user_service_areas.service_area_id AS service_area_id");
                    $this->addQueryParameter('selectParameters', "user_service_areas.service_distance AS service_distance");
                    $this->addQueryParameter('selectParameters', "LEAST(".$sqlBaseForDistance.", COALESCE(CASE WHEN user_service_areas.service_distance < 1 THEN 1 ELSE user_service_areas.service_distance END,5000)) AS order_distance");

                    $this->addQueryParameter('selectParameters', "user_service_areas.service_area_address AS service_area_address");
                    //this is the sql that will create a table on the fly to check against  service areas
                    $this->addQueryParameter('tablesParameters', "LEFT JOIN (
                        SELECT
                            service_area_user_id,
                            service_area_address,
							service_area_id,
                            service_distance
                        FROM (
                            SELECT
                            sa2.user_id AS service_area_user_id,
							sa2.area_id AS service_area_id,
                            sa2.address AS service_area_address,
                                (" . $metricUnit . " * acos( cos( radians(" . $this->lat . ") ) * cos( radians( sa2.lat ) ) * cos( radians( sa2.lon ) - radians(" . $this->lng . ") ) + sin( radians(" . $this->lat . ") ) * sin(radians(sa2.lat)) ) ) AS service_distance,
                            ROW_NUMBER() OVER (PARTITION BY sa2.user_id ORDER BY (" . $metricUnit . " * acos( cos( radians(" . $this->lat . ") ) * cos( radians( sa2.lat ) ) * cos( radians( sa2.lon ) - radians(" . $this->lng . ") ) + sin( radians(" . $this->lat . ") ) * sin(radians(sa2.lat))
                            )) ASC) AS RowNum

                        FROM
                            `service_areas` AS sa2
                        INNER JOIN 
                            `users_data` AS ud2 ON sa2.user_id = ud2.user_id
                        INNER JOIN 
                            `subscription_types` AS st2 ON ud2.subscription_id = st2.subscription_id AND st2.location_limit > 0
                    
                        WHERE
                            (
                                COALESCE((" . $metricUnit . " * acos( cos( radians(" . $this->lat . ") ) * cos( radians( sa2.lat ) ) * cos( radians( sa2.lon ) - radians(" . $this->lng . ") ) + sin( radians(" . $this->lat . ") ) * sin(radians(sa2.lat)) ) ),0) <= " . $w['default_radius'] . "
                                " . $directNameAndSqlService . "
                            )
                            " . $directNameOrSqlService . "
                        ) as Subquery
                        WHERE 
                            RowNum = 1
                        ORDER BY 
                            `service_distance` ASC
                    ) user_service_areas ON user_service_areas.service_area_user_id = ud.user_id ");
                    //this is the sql section on the where that checks against the users current info and the result of the service areas table created on the fly
                    $this->addQueryParameter('whereParameters', "(
                            (
                                " . $sqlBaseForDistance . " <= " . $w['default_radius'] . "
                                " . $directNameAndSqlUser . "
                            )
                        OR
                            ( user_service_areas.service_area_user_id > 0 AND st.location_limit != 'all' )
                        OR
                            ud.nationwide = 1
                        OR
                            st.location_limit = 'all'
                    )");
                    $this->setOrderParemeters(true);
                    $this->addQueryParameter('whereParameters',"((ud.lat IS NOT NULL AND ud.lon IS NOT NULL AND ud.lat != 0 AND ud.lon != 0) OR user_service_areas.service_area_user_id IS NOT NULL)");
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
                        if(!isset($_GET['pretty_url_country_found']) || $_GET['pretty_url_country_found'] === true){
                            $profs['new_filename'][0] = "%country_name~na%";
                        }
                    }
                }
                //check if the locations states exists on the database, if it does then find the state information for seo purposes


                if ($this->adminLevel1ShortName != "") {

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
                    }
                    if(!isset($_GET['pretty_url_state_found']) || $_GET['pretty_url_state_found'] === true){
                        if(empty($profs['state_name']) && !empty($this->stateSearchLN)){
                            $profs['state_name'] = $this->stateSearchLN;
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

    public function runCountLikes() {
        if (empty($this->overallRating) && ($this->sortBy == "least_likes" || $this->sortBy == "most_likes")) {
            $this->addQueryParameter('groupByParameters', 'ud.user_id');
        }
    }

    public function runReviews() {
        if(!empty($this->overallRating)) {
            $fromReviewQuery = " RIGHT JOIN users_reviews AS ur ON ud.user_id = ur.user_id ";
            $this->addQueryParameter('tablesParameters', $fromReviewQuery);

            $selectReviewQuery = '(SELECT AVG(rating_overall) FROM `users_reviews` AS ur WHERE ud.user_id = ur.user_id AND ur.review_status = 2) AS rating_overall ';
            $this->addQueryParameter('selectParameters', $selectReviewQuery);
            $this->addQueryParameter('whereParameters', ' ur.review_status = 2 ');
            $this->addQueryParameter('groupByParameters', 'ud.user_id');
        }
        /* $havingReviewQuery='';
         $this->addQueryParameter('selectParameters', $havingReviewQuery);*/

    }

    public function runAvailability() {
        if (!empty($_GET['available_now']) && $_GET['available_now'] == 1) {
            $fromMetaQuery = " LEFT JOIN users_meta AS uma ON ud.user_id = uma.database_id AND uma.database = 'users_data' AND uma.`key` = 'available_now' ";
            $this->addQueryParameter('tablesParameters', $fromMetaQuery);
            $this->addQueryParameter('whereParameters', ' uma.value = 1 ');
        }
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
        $this->runAvailability();



        $this->runSubscription();


        $this->runRadius();

        $this->runMemberPermissions();
        $this->runLocation();


        $this->runFileName();
        $this->runReviews();
        $this->runCountLikes();
        $this->runMatchLimit();

        if ($this->target == 'lead') {
            $this->addQueryParameter('whereParameters', "email != ''");
            if (empty($this->dataArray['lead_filter_disabled'])) {
                $this->addQueryParameter('whereParameters', "!((SUBSTRING(email, 1, LOCATE('@', email) - 1) = ud.user_id) AND !(right(email, length(email)-INSTR(email, '@') ) != '" . $w['email_from'] . "')) ");
            }
        }

        $this->build();
    }

    protected function setSelectFields($excludeNewColumns = false)
    {

        $sqlSelectParameters = array();

        $orderNames = "CASE WHEN ud.listing_type = 'individual' THEN CASE WHEN TRIM(ud.first_name) <> '' THEN CONCAT(TRIM(ud.first_name), ' ', TRIM(ud.last_name)) ELSE ud.company END ELSE CASE WHEN ud.company = '' THEN CONCAT(TRIM(ud.first_name), ' ', TRIM(ud.last_name)) ELSE ud.company END END AS name , CASE WHEN ud.listing_type = 'individual' THEN CASE WHEN TRIM(ud.last_name) <> '' THEN ud.last_name ELSE ud.company END ELSE CASE WHEN TRIM(ud.company) = '' THEN ud.last_name ELSE ud.company END END AS last_name, st.search_priority as  search_priority";

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

                case 'least_likes':
                case 'most_likes':
                    $sqlSelectParameters = array(
                        "ud.user_id",
                        "ud.listing_type",
                        "totalLikes",
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
            $sqlSelectParameters[] = 'user_service_areas.service_area_address';
            $sqlSelectParameters[] = 'user_service_areas.service_area_id';
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
                (
                    ud.country_code = '".$this->countryShortName."'
                AND
                    ud.state_code = '".$this->adminLevel1ShortName."'
                )
            ";

            $boundsServiceAreaSqlWhere = "
                (
                    sa2.country_sn = '".$this->countryShortName."'
                AND
                    sa2.state_sn = '".$this->adminLevel1ShortName."'
                )
                OR
                (
                    sa2.country_sn = '".$this->countryShortName."'
                AND
                    sa2.state_sn = '' 
                )
            ";

        }else if($this->isCountrySearch === true && $this->countryShortName != ""){
            $boundsSqlWhereString       = " ud.country_code = '".$this->countryShortName."' ";
            $boundsServiceAreaSqlWhere  = " sa2.country_sn = '".$this->countryShortName."' ";
        }

        //this is the sql that will create a table on the fly to check against  service areas
        $boundsSql = "LEFT JOIN (
                SELECT
                    sa2.address AS service_area_address,
                    sa2.user_id AS service_area_user_id,
					sa2.area_id AS service_area_id
                FROM
                    `service_areas` AS sa2
                       INNER JOIN `users_data` AS ud2 ON sa2.user_id = ud2.user_id
                         INNER JOIN `subscription_types` AS st2 ON ud2.subscription_id = st2.subscription_id AND st2.location_limit > 0
                    
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
                        sa2.address AS service_area_address,
						sa2.area_id AS service_area_id
                    FROM
                        `service_areas` AS sa2
                           INNER JOIN `users_data` AS ud2 ON sa2.user_id = ud2.user_id
                         INNER JOIN `subscription_types` AS st2 ON ud2.subscription_id = st2.subscription_id AND st2.location_limit > 0
                    
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
            $this->addQueryParameter('selectParameters', "service_area_address");
            $this->addQueryParameter('selectParameters', "service_area_id");
        }

    }

    protected function setOrderParemeters($radiusParamer = false)
    {
        global $dc;
        $this->runSortBy();
        switch ($this->sortBy) {

            case 'random':
                session_start();

                if (!isset($_SESSION['randomSeed']) || (!isset($_REQUEST['currentPage']) && (!isset($_GET['page']) || $_GET['page'] < 1))) {
                    // Generate a new random seed for the first visit or when the current page is invalid
                    $randomSeed = mt_rand(100000000, 999999999);
                    $_SESSION['randomSeed'] = $randomSeed;
                } else {
                    $randomSeed = $_SESSION['randomSeed'];
                }


                $randomSeed = preg_replace("/[^0-9]/", "", $randomSeed);
                $randomSeed = substr($randomSeed, 0, 9);
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "RAND($randomSeed)"
                );
                break;

            case 'alphabet-desc':
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "name DESC",
                    "ud.user_id DESC"
                );
                break;

            case 'last_name_asc':
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "last_name ASC",
                    "ud.user_id DESC"
                );
                break;

            case 'last_name_desc':
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "last_name DESC",
                    "ud.user_id DESC"
                );
                break;

            case 'reviews':
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "reviews_number DESC",
                    "name ASC",
                    "ud.user_id DESC"
                );
                break;

            case 'userid-asc':
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "ud.user_id ASC"
                );
                break;

            case 'userid-desc':
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "ud.user_id DESC"
                );
                break;

            case 'most_likes':
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "totalLikes DESC",
                    "ud.user_id DESC"
                );
                break;
            case 'least_likes':
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "totalLikes ASC",
                    "ud.user_id DESC"
                );
                break;

            //  This case is for default or alphabet-desc ASC
            default:
                $sqlOrderByParameters = array(
                    "search_priority ASC",
                    "name ASC",
                    "ud.user_id DESC"
                );
        }


        if ($radiusParamer === true) {

            if (isset($this->dataArray['sort']) && $this->dataArray['sort'] != "distance") {

                $firstPosition = $sqlOrderByParameters[0];
                $secondPosition = $sqlOrderByParameters[1];
                $sqlOrderByParameters[0] = " order_distance ASC, distance ASC";


                array_unshift($sqlOrderByParameters, $secondPosition);
                array_unshift($sqlOrderByParameters, $firstPosition);

            } else {
                $firstPosition = $sqlOrderByParameters[0];
                $sqlOrderByParameters[0] = " order_distance ASC, distance ASC";


                array_unshift($sqlOrderByParameters, $firstPosition);
            }
        }

        if ($dc['category_ignore_search_priority'] == 1 && $this->target == 'search') {
            $indexPriority = array_search('search_priority ASC', $sqlOrderByParameters);
            if ($indexPriority !== false) {
                unset($sqlOrderByParameters[$indexPriority]);
            }
        }
        $this->clearQueryString('orderParameters');
        $this->setQueryParameter('orderParameters', $sqlOrderByParameters);


    }

    protected function setWhere()
    {
        global $w,$_ENV;

        $value = array(
            "ud.active = '2'",
            "st.searchable = '1'"
        );

        $w['is_front_end'] = true;
        $_ENV['completed_users_meta_fields_user_ids'] = array();

        users_controller::setCompleteProfileFieldsQuery($value);

        if(count($_ENV['completed_users_meta_fields_user_ids']) > 0){
            $listOfIds = array();
            foreach($_ENV['completed_users_meta_fields_user_ids'] as $ids){

                if(strpos($ids,"-1") !== false){//if theres no members in at least one users_meta complete profile fields
                    $listOfIds = array("-1");
                    break;
                }

                $ids        = explode(',',$ids);

                if(count($listOfIds) <= 0){
                    $listOfIds  = $ids;
                }else{
                    $listOfIds  = array_intersect($listOfIds,$ids);
                }
            }

            $listOfIds  = implode(',',array_unique($listOfIds));
            $value[]    = " ud.user_id IN (".$listOfIds.") ";
        }

        if(addonController::isAddonActive('sub_accounts') && property_exists(bd_controller::user(WEBSITE_DB), "parent_id")){

            $hideParentsWhere = array(
                array('value' => '1,0' , 'column' => 'value', 'logic' => '='),
                array('value' => 'hide_parent_accounts' , 'column' => 'key', 'logic' => '='),
                array('value' => 'subscription_types' , 'column' => 'database', 'logic' => '=')
            );

            $subsWithHideParent = bd_controller::users_meta()->get($hideParentsWhere);
            $hideParentSubs     = 0;
            if(is_array($subsWithHideParent)){
                foreach($subsWithHideParent as $subWithParent){
                    $hideParentSubsArray[] = $subWithParent->database_id;
                }
                $hideParentSubs = implode(',', $hideParentSubsArray);
            } else if($subsWithHideParent !== false) {
                $hideParentSubs = $subsWithHideParent->database_id;
            }

            $subAccountsIds = bd_controller::user()->getSubAccountsUserIds();

            $value[]=  "IF ( 
                            st.subscription_id IN (".$hideParentSubs."),
                            IF(
                                ud.user_id NOT IN (".$subAccountsIds."), 1, 0
                            ) ,1
                        )  = 1";
        }


        // =======================================================
        //  UPDATED SEARCH FILTERS (AND-Based Search)
        // =======================================================

        // 1. OPEN TO (e.g. Friends AND Dates)
        if (isset($_GET['open_to']) && !empty($_GET['open_to'])) {
            if (is_array($_GET['open_to'])) {
                $parts = array();
                foreach ($_GET['open_to'] as $item) {
                    $safe_item = mysql_real_escape_string($item);
                    $parts[] = "ud.open_to LIKE '%$safe_item%'";
                }
                // CHANGED TO AND: Must match ALL selected options
                $value[] = "(" . implode(" AND ", $parts) . ")";
            } else {
                $safe_item = mysql_real_escape_string($_GET['open_to']);
                $value[] = "ud.open_to LIKE '%$safe_item%'";
            }
        }

        // 2. LIKE TO MEET (e.g. for_a_walk AND for_drinks)
        if (isset($_GET['like_to_meet']) && !empty($_GET['like_to_meet'])) {
            if (is_array($_GET['like_to_meet'])) {
                $parts = array();
                foreach ($_GET['like_to_meet'] as $item) {
                    $safe_item = mysql_real_escape_string($item);
                    $parts[] = "ud.like_to_meet LIKE '%$safe_item%'";
                }
                // CHANGED TO AND
                $value[] = "(" . implode(" AND ", $parts) . ")";
            } else {
                $safe_item = mysql_real_escape_string($_GET['like_to_meet']);
                $value[] = "ud.like_to_meet LIKE '%$safe_item%'";
            }
        }

        // 3. INTERESTS (e.g. dance AND music)
        if (isset($_GET['interests']) && !empty($_GET['interests'])) {
            if (is_array($_GET['interests'])) {
                $parts = array();
                foreach ($_GET['interests'] as $item) {
                    $safe_item = mysql_real_escape_string($item);
                    $parts[] = "ud.interests LIKE '%$safe_item%'";
                }
                // CHANGED TO AND
                $value[] = "(" . implode(" AND ", $parts) . ")";
            } else {
                $safe_item = mysql_real_escape_string($_GET['interests']);
                $value[] = "ud.interests LIKE '%$safe_item%'";
            }
        }

        // 4. SEARCH DESCRIPTION (Text - Keeps single match logic)
        if (isset($_GET['search_description']) && !empty($_GET['search_description'])) {
            $safe_desc = mysql_real_escape_string($_GET['search_description']);
            $value[] = "ud.search_description LIKE '%$safe_desc%'";
        }

        // =======================================================

        

        $this->clearQueryString('whereParameters');
        $this->setQueryParameter('whereParameters', $value);
    }

    protected function setFromTables()
    {
        global $w;

        $value = array(
            "`users_data` AS ud"
        );

        //Default tables for a select
        if ($w['search_results_require_complete_profile'] == "0") {
            $value[] = "INNER JOIN `subscription_types` AS st ON ud.subscription_id = st.subscription_id";
        } else {
            users_controller::setCompleteProfileFieldsQuery($value,false);
        }

        if ($this->sortBy == "least_likes" || $this->sortBy == "most_likes") {
            $value[] = "LEFT JOIN (SELECT ownerId, COUNT(*) AS totalLikes FROM users_favorite WHERE dataType = 10 GROUP BY ownerId) uf ON ud.user_id = uf.ownerId ";
        }

        $this->clearQueryString('tablesParameters');
        $this->setQueryParameter('tablesParameters', $value);
    }

    protected function setAttributes()
    {
        $this->setDataArray();
        $this->newColumnsExists();
        $this->setWhere();
        $this->setSortBy();
        $this->setFromTables();
        $this->setOrderParemeters();
        $this->setSelectFields();
        $this->setSearchQuery();
        $this->setSubLevelId();
        $this->setSubSubLevelId();
        $this->setTopLevelId();
        $this->setOverallRating();
        $this->setProfessionId();
        $this->setServiceId();
        $this->setCountryCode();
        $this->setParentId();
        $this->setCountryShortName();
        $this->setStateSearch();
        $this->setStateSearchLN();
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

            $this->$property = mysql_real_escape_string($defaultValue);
            return;
        }

        if (!is_null($value) && !empty($value) && is_string($value)) {

            if (in_array($property,$this->coordinatesArray) && !is_numeric($value)) {
                $value = 0;
            }

            $this->$property = mysql_real_escape_string($value);
        }else if(!is_null($value) && !empty($value) && in_array($property,$this->coordinatesArray) ){

            if (!is_numeric($value)) {
                $value = 0;
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
        $this->runMemberSearchQuery();

        //this the only run that executes on a set
        if ($this->target == 'search') {
            $this->setProperty('topLevelId', $this->dataArray['pid'], $value);
        } else if ($this->target == 'lead') {
            $this->setProperty('topLevelId', $this->dataArray['top_id'], $value);
        }

        //if we have sub as first priority
        $this->runSubSearchQuery();

    }

    protected function setSubLevelId($value = '')
    {
        if ($this->target == 'search') {

            if(!is_array($this->dataArray['tid'])){
                $this->dataArray['tid'] = trim($this->dataArray['tid'],',');
            }

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
                $this->subsubWasSent = true;
            }
        } else {
            $this->setProperty('subSubLevelId', $this->dataArray['sub_sub_id'], $value);
        }
    }

    protected function setProfessionId($value = '')
    {
        global $isPrettyUrl;
        if ($this->target == 'search' && !isset($_GET['search_created']) && $isPrettyUrl != true) {
            global $profession;
            $this->setProperty('professionId', $profession['profession_id'], $value);
        }
    }

    protected function setServiceId($value = '')
    {
        global $isPrettyUrl;
        if ($this->target == 'search' && !isset($_GET['search_created']) && $isPrettyUrl != true) {
            global $service;
            $this->setProperty('serviceId', $service['service_id'], $value);
        }
    }

    protected function setCountryCode($value = '')
    {
        global $isPrettyUrl;

        if ($this->target == 'search' && $isPrettyUrl != true) {
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
        $this->setProperty('stateSearch', stripslashes($this->dataArray['stateSearch']), $value);
    }
    protected function setStateSearchLN($value = '')
    {
        $this->setProperty('stateSearchLN', stripslashes($this->dataArray['stateSearchLN']), $value);
    }
    protected function setAdminLevel1ShortName($value = '')
    {
        $this->setProperty('adminLevel1ShortName', stripslashes($this->dataArray['adm_lvl_1_sn']), $value);
    }

    protected function setCountyShortName($value = '')
    {
        $this->setProperty('countyShortName', stripslashes($this->dataArray['county_sn']), $value);
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
        $this->setProperty('formatAddress', stripslashes($this->dataArray['faddress']), $value);
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
            $this->setProperty('location', stripslashes($this->dataArray['location_value']), $value);
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

    public function getTarget() {
        return $this->target;
    }
    public function getStringGroupBy() {
        return $this->groupQueryString;
    }
    public function getOrderBy(){
        return $this->sortBy;
    }
}

$BDMembersQuery         = new BDMembersQuery();
$_ENV['custom_object']  = $BDMembersQuery;
$_ENV['custom_query']   = $BDMembersQuery->getQuery();
$_ENV['sqlquery']       = $BDMembersQuery->getQuery();
$_ENV['basesqlquery']   = $_ENV['sqlquery'];
$orderByReviews         = ($BDMembersQuery->getOrderBy() == "reviews") ?"reviews_number,":"";
$baseOrder              = str_replace("ud.user_id","user_id", implode(', ',$BDMembersQuery->getOrderParameters()));
$globalOrderBy          = ($BDMembersQuery->getOrderBy() == "reviews") ? $baseOrder : str_replace('reviews_number DESC,','',$baseOrder);

if($BDMembersQuery->isServiceAreaSearch){
    $explodedQuery          = explode('  ORDER BY search_priority',$_ENV['sqlquery']);
    $_ENV['sqlquery']       = " SELECT
    user_id,
    name,
    last_name,
    ".$orderByReviews."
    distance,
    service_area,
    service_distance,
    order_distance,
    service_area_address,
    service_area_id,
    search_priority,
    RowNum
FROM
    (
    SELECT
        user_id,
        name,
        last_name,
        ".$orderByReviews."
        distance,
        service_area,
        service_distance,
        order_distance,
        service_area_address,
		service_area_id,
        search_priority,
        ROW_NUMBER() OVER(
        PARTITION BY user_id
    ORDER BY
        search_priority ASC,
        order_distance ASC,
        DISTANCE ASC,
        name ASC,
        user_id
    DESC
    ) AS RowNum
FROM
    ( 
        ".$explodedQuery[0]." 
    ) AS Subquery) AS FinalQuery
    WHERE
        RowNum = 1
    ORDER BY
         ".$globalOrderBy;
}

?>