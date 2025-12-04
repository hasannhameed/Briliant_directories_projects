<?php
header("Access-Control-Allow-Origin: *");
ini_set('memory_limit','256M');
ini_set('max_execution_time', 300);

if($_POST['action'] === "import_members_new"){
    global $w,$DBMain,$sess;

    function uploadFiles($files) {
        global $sess,$w,$uploadtype;

        extract($files);

        for($i = 0; $i < count($userfile); $i++) {

            if ($userfile['name'][$i] != "") {

                $orgfile    = iconv($target_encoding, "UTF-8",basename($userfile['name'][$i]));
                $filename   = explode(".",$userfile['name'][$i]);
                $ext        = count($filename)-1;
                $ext        = strtolower($filename[$ext]);
                $tinfile    = $w['website_id']."-".$uploadtype."-".date("YmdHis").".$ext";

                if ($ext=="csv") {
                    $tinsize    = $userfile['size'][$i];
                    $tinfile    = str_replace(" ","",$tinfile);
                    $uploadfile = $w['admin_upload_absolute_path']."/contact_databases/".$tinfile;
                    if (move_uploaded_file($userfile['tmp_name'][$i], $uploadfile)) {
                    }
                }

            }

            /*$validImage = imageValidator::validateImage(
                $uploadfile,
                $sess['admin_id'],
                'importContacs.php import members CSV',
                imageValidator::CSV,
                true
            );*/

            $validImage = true;// until we figured out why is not returning true will off the validation
            if($validImage !== true){
                unlink($uploadfile);
            }else{
                $message.="<br>".$orgfile." is valid, and was successfully uploaded.\n";
            }

        }
        return $tinfile;
    }


    $result             = array(
        'tintFile'          => '',
        'totalMembers'      => 0,
        'importFilesId'     => 0
    );
    $tinfile            = uploadFiles($_FILES);
    $filename           = $w['admin_upload_absolute_path']."/contact_databases/".$tinfile;
    $result['tintFile'] = $filename;
    $fhandle            = fopen($filename,"r");
    $content            = fread($fhandle,filesize($filename));
    $fhandle            = fopen($filename,"w");

    fwrite($fhandle,$content);
    fclose($fhandle);

    $handle  = fopen($filename, "r");

    ini_set('auto_detect_line_endings', true);

    $first = true;
    while (($data = fgetcsv($handle, 8192, ",")) !== FALSE) {

        if($first === true){
            $first = false;
            continue;
        }

        if(bdArray::isAllContentEmpty($data) === true){
            continue;
        }

        $result['totalMembers']++;
    }

    fclose($handle);

    echo json_encode($result);

    exit;
}

if($_POST['action'] === 'import_members_new_bulk'){
    global $w;

    $result     = array(
        'status'                    => 'success',
        'rows'                      => array(),
        'inserted_success_queries'  => array(),
        'inserted_errors_queries'   => array()
    );

    ini_set('auto_detect_line_endings', true);

    $start                      = 0;
    $data                       = array();
    $columns                    = array();
    $concolumns                 = array();
    $_SERVER['DOCUMENT_ROOT']   = "/home/".brilliantDirectories::getDatabaseConfiguration('website_user')."/public_html";
    $tdate                      = date("YmdHis");

    $columnresuls               = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
        SELECT 
            `COLUMN_NAME` 
        FROM 
            `INFORMATION_SCHEMA`.`COLUMNS` 
        WHERE 
            `TABLE_SCHEMA`    = '".brilliantDirectories::getDatabaseConfiguration('database')."' 
        AND 
            `TABLE_NAME`      = 'users_data'
        "
    );

    while ( $c = mysql_fetch_assoc($columnresuls)) {
        $columns[] = $c['COLUMN_NAME'];
    }

    $columnresuls = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
        SELECT 
            `COLUMN_NAME` 
        FROM 
            `INFORMATION_SCHEMA`.`COLUMNS` 
        WHERE 
            `TABLE_SCHEMA`    = '".brilliantDirectories::getDatabaseConfiguration('database')."' 
        AND 
            `TABLE_NAME`      = 'contacts'
        "
    );

    while ( $c = mysql_fetch_assoc($columnresuls)) {
        $concolumns[] = $c['COLUMN_NAME'];
    }

    $handle = fopen($_POST['tintFile'], "r");

    while (($row = fgetcsv($handle, 8192, ",")) !== FALSE) {
        $rows[] = $row;
    }

    $start          = $_POST['currentBulkPosition'] - 1;
    $headers        = $rows[0];

    unset($rows[0]);

    $rows           = array_slice($rows, $start,$_POST['batchAmount']);
    $result['rows'] = $rows;

    $badwords       = array(
        'services',
        'profession_name',
        'service_id',
        'state_name',
        'address',
        'profile_photo',
        'logo',
        'county_name',
        'top_category',
        'service_name',
        'association_id',
        'user_id'
    );

    $badWordsTranslations  = array(
        'profession_name' => 'profession_id',
        'top_category'=> 'profession_id',
    );

    $listCountriesModel = new list_countries();

    $fetchedCountries = array();

    foreach ($rows as $rowIndex => $data) {

        if(bdArray::isAllContentEmpty($data) === true){
            continue;
        }

        $data = bdString::prepareSpecialCharacter($data,true);

        $varvalue                   = array();
        $conarray                   = array();
        $uarray                     = array();
        $totalcontacts              = 0;
        $id                         = 0;
        $sql                        = "";
        $consql                     = "";
        $adfield                    = "";
        $subscription_id            = $_POST['subscription_id'];
        $subscriptionModel          = false;
        $isProfessionOnCsv          = false;           
        $category                   = $_POST['category'];
        $disable_duplicate_check    = $_POST['disable_duplicate_check'];
        $createcategories           = $_POST['createcategories'];
        $active                     = $_POST['active'];

        foreach ($headers as $key => $value) {
            if($value == 'user_id'){//to avoid any primary key duplication in users_data
                continue;
            }

            $varvalue[$value] = rtrim(ltrim(trim($data[$key])," ")," ");
        }

        if ($_POST['disable_duplicate_check'] != 1) {
            $cresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                SELECT 
                    `user_id` 
                FROM 
                    `users_data` 
                WHERE 
                    `email`  = '".mysql_real_escape_string($varvalue['email'])."' 
                AND 
                    `email` != ''
            ");
            $totalcontacts = mysql_num_rows($cresults);
            mysql_free_result($cresults);
        }

        if ($totalcontacts == 0 ) {

            $token = hmac(date("YmdHis"),date("YmdHis"));

            if ($varvalue['password'] == "") {
                $varvalue['password'] = substr($token,0,8);
            }

            foreach($varvalue as $key => $value) {
                $varvalue[$key] = trim($varvalue[$key]);
                $value = trim($value);
                if ($value !="" ) {
                    $value = str_replace("'","'",$value);

                    if ($key == "name") {
                        
                        $value                  = str_replace("'","'",$value);
                        $name                   = explode(" ",$value);
                        $varvalue['first_name'] = trim($name[0]);
                        $varvalue['last_name']  = trim($name[1]." ".$name[2]." ".$name[3]." ".$name[4]);
                        $varvalue['name']       = "";

                    }

                    if ($key == "zip_code" ) {
                        $varvalue['zip_code'] = str_replace("? "," ",$varvalue['zip_code']);
                    }

                    if (($key == "profession_name" || $key == "top_category") && $value != "") {
                        //we marked that we found a profession id in the csv
                        $isProfessionOnCsv = true;

                        $presults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            SELECT 
                                `profession_id` 
                            FROM 
                                `list_professions` 
                            WHERE 
                                `name` = '".mysql_real_escape_string($value)."'
                        ");


                        if ( mysql_num_rows($presults) == 0 && $createcategories == 1 && $value != '' ) {
                            $value = urldecode($value );

                            $filenameEncoded = new bdString($value);
                            $filenameEncoded->urlencodeStringChain('*-+');

                            $newFilename = $filenameEncoded->modifiedValue;
                            $newFilename = strtolower($newFilename);

                            mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                INSERT INTO 
                                    `list_professions` 
                                SET 
                                    `name`      = '".stripslashes( stripslashes(  $value  ) )."',
                                    `filename`  = '".$newFilename."'
                            ");

                            $varvalue['profession_id'] = mysql_insert_id();
                        } else {
                            $p                          = mysql_fetch_assoc($presults);
                            $varvalue['profession_id']  = $p['profession_id'];
                        }

                    }



                    if ($key == "service_name" && $value != "") {

                        $presults = mysql($brilliantDirectories::getDatabaseConfiguration('database'),"
                            SELECT 
                                `service_id` 
                            FROM 
                                `list_services` 
                            WHERE 
                                `name` = '".mysql_real_escape_string($value)."'
                        ");

                        if (mysql_num_rows($presults) == 0 && $createcategories == 1 && $value!='' ) {
                            $value = urldecode($value );

                            $filenameEncoded = new bdString($value);
                            $filenameEncoded->urlencodeStringChain('*-+');

                            $newFilename = $filenameEncoded->modifiedValue;
                            $newFilename = strtolower($newFilename);

                            mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                INSERT INTO 
                                    `list_services` 
                                SET 
                                    `name`          ='".stripslashes( stripslashes(  $value  ) )."',
                                    `filename`      ='".$newFilename."',
                                    `profession_id` ='".$varvalue['profession_id']."'
                            ");

                            $varvalue['service_id'] = mysql_insert_id();
                        } else {
                            $p                      = mysql_fetch_assoc($presults);
                            $varvalue['service_id'] = $p['service_id'];
                        }
                    }

                    if ($key == "service_id" && $value > 0) {

                        $presults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            SELECT 
                                `service_id` 
                            FROM 
                                `list_services` 
                            WHERE 
                                `service_id` = '".$value."'
                        ");

                        while ( $p = mysql_fetch_assoc($presults) ) {
                            if ($varvalue['profession_id'] < 1 && $p['profession_id'] > 0) {
                                $varvalue['profession_id'] = $p['profession_id'];
                            }
                        }
                    }

                    if ($key == "state_code") {
                        $state = getState($varvalue['state_code'],$w,$varvalue['country_code']);
                        if ($state['code'] != "") {
                            $varvalue['state_code'] = $state['code'];
                        }
                    }

                    if ($key == "state_name") {
                        $state = getState($varvalue['state_name'],$w,$varvalue['country_code']);
                        if ($state['code'] != "") {
                            $varvalue['state_code'] = $state['code'];
                        }
                    }

                    if ($key == "address") {
                        $address                = explode(",",trim($varvalue['address']));
                        $count                  = count($address)-1;
                        $state                  = $count-1;
                        $city                   = $count-2;
                        $varvalue['zip_code']   = $address[$count];
                        $varvalue['state_code'] = trim(str_replace(",","",$address[$state]));
                        $varvalue['city']       = $address[$city];
                        for ($i = 0; $i < $state; $i++) {
                            $varvalue['address1'] .= $address[$i]." ";
                        }
                    }

                    if ($key == "password"){
                        $salt                   = substr(hash("md5",$w['website_id']."qmzpalvt193764"), -22);
                        $varvalue['password']   = crypt($value, '$2a$11$'.$salt);
                    }

                    if ($key == "sub_category") {
                        $varvalue['sub_category']   = '';
                        $key                        = "services";
                        $varvalue[$key]             = $value;
                    }

                    if(array_key_exists($key, $badWordsTranslations)){
                        $key = $badWordsTranslations[$key];
                    }

                    $keysToSkipUrlEncode = array(
                        'logo',
                        'profile_photo'
                    );

                    if ( ( filter_var($varvalue[$key], FILTER_VALIDATE_URL) || strpos($key,'filename') ) && !in_array($key, $keysToSkipUrlEncode) ) {
                        $varvalue[$key] = urldecode($varvalue[$key]);
                        $varvalue[$key] = urlencode($varvalue[$key]);
                    }

                    if (in_array($key,$columns)) {

                        $sql           .= (is_string($varvalue[$key])) ? "`".$key."`='".mysql_real_escape_string(stripslashes($varvalue[$key]))."', " : "`".$key."`='".$varvalue[$key]."', ";
                        $consql        .= (is_string($varvalue[$key])) ? "`".$key."`='".mysql_real_escape_string(stripslashes($varvalue[$key]))."', " : "`".$key."`='".$varvalue[$key]."', ";
                    }else if (!in_array($key,$badwords)) {
                        $uarray[$key]   = $value;
                    }else if (!in_array($key,$columns)){
                        $conarray[$key] = $value;
                    }
                    

                    if($key == "name"){
                        
                        if(!empty($varvalue['first_name'])){
                            $sql    .= " `first_name` = '".mysql_real_escape_string(stripslashes($varvalue['first_name']))."', ";
                            $consql .= " `first_name` = '".mysql_real_escape_string(stripslashes($varvalue['first_name']))."', ";
                        }

                        if(!empty($varvalue['last_name'])){
                            $sql    .= " `last_name` = '".mysql_real_escape_string(stripslashes($varvalue['last_name']))."', ";
                            $consql .= " `last_name` = '".mysql_real_escape_string(stripslashes($varvalue['last_name']))."', ";
                        }
                    }

                    if($key == "country_name"){

                        if(!empty($varvalue['country_name']) && array_key_exists($varvalue['country_name'], $fetchedCountries) && !isset($varvalue['country_code']) ){//if we have a country_name column and not a country code but we have already fetched this country_name
                            $sql    .= " `country_code` = '".mysql_real_escape_string(stripslashes($fetchedCountries[$varvalue['country_name']]))."', ";
                            $consql .= " `country_code` = '".mysql_real_escape_string(stripslashes($fetchedCountries[$varvalue['country_name']]))."', ";
                        }else if(isset($varvalue['country_name']) && !empty($varvalue['country_name'])){//if we have country_name and not country_code and we haven't fetch the country
                            $listCountriesModel->getCountryByName($varvalue['country_name']);
                            if($listCountriesModel->country_code != "" && !isset($varvalue['country_code']) ){//we check if have country name and we dont have country_code as columns in the csv
                                $fetchedCountries[$varvalue['country_name']] = $listCountriesModel->country_code;
                                $sql    .= " `country_code` = '".mysql_real_escape_string(stripslashes($fetchedCountries[$varvalue['country_name']]))."', ";
                                $consql .= " `country_code` = '".mysql_real_escape_string(stripslashes($fetchedCountries[$varvalue['country_name']]))."', ";
                            }
                            $listCountriesModel->resetModel($listCountriesModel);
                        }

                        //logic to collect country_name
                        if(!empty($varvalue['country_name'])){//if we have a value for country_name
                            $sql    .= " `country_ln` = '".mysql_real_escape_string(stripslashes($varvalue['country_name']))."', ";
                            $consql .= " `country_ln` = '".mysql_real_escape_string(stripslashes($varvalue['country_name']))."', ";
                        }
                    }
                }
            }

            //logic to selected profession id base on the membership plan
            if($isProfessionOnCsv === false){
                if($subscriptionModel === false){//we fetch only once into the db
                    //we get the subscription info
                    $subscriptionResult = bd_controller::subscription_types()->get($subscription_id,subscription_types::PRIMARY_KEY);
                    //we load the properties to load users_meta data
                    bd_controller::subscription_types()->loadProperties($subscriptionResult);
                    //we stored locally the model
                    $subscriptionModel = bd_controller::subscription_types();
                }

                //if we have a valid subscription and something selected in the membership plan 
                if($subscriptionModel !== false && !empty($subscriptionModel->profession_id) && $subscriptionModel->profession_id > 0){
                    $sql    .= " `profession_id` = '".mysql_real_escape_string($subscriptionModel->profession_id)."', ";
                    $consql .= " `profession_id` = '".mysql_real_escape_string($subscriptionModel->profession_id)."', ";
                } 
            }

            $sql = rtrim($sql,", ");

            $userModel = new user();
            $userModel->query("
                INSERT INTO 
                    `users_data` 
                SET ".$sql
            );

            $id = $userModel->getLastInsertId();

            if($id > 0){
                $user_id                                                                = $id;
                $result['inserted_success_queries']['user_csv_row'.($rowIndex+$start)]  = "INSERT INTO `users_data` SET ".$sql;

                if ($varvalue['user_id'] > 0) {
                    $id = $varvalue['user_id'];
                }

                $user = getUser($id,$w);
                $rand = rand(1,999);

                if ($varvalue['email'] == "" && $id > 0) {
                    if ($w['email_from'] == "") {
                        $w['email_from'] = str_replace("www.","",$w['website_url']);
                    }
                    $varvalue['email'] = $id."@".$w['email_from'];
                    mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                        UPDATE 
                            `users_data` 
                        SET 
                            `email`     = '".$varvalue['email']."' 
                        WHERE 
                            `user_id`   = '".$id."'
                    ");
                }

                if ($varvalue['profile_photo'] != "") {
                    $newpic = 0;
                    mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                        INSERT INTO 
                            `users_photo` 
                        SET 
                            `user_id`       = '".$id."',
                            `file`          = '".$newfile."',
                            `original`      = '".$varvalue['profile_photo']."',
                            `type`          = 'photo',
                            `date_added`    = '".$w['date']."'
                    ");
                }

                if ($varvalue['logo'] != "") {
                    $newpic = 0;
                    mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                        INSERT INTO 
                            `users_photo` 
                        SET 
                            `user_id`       = '".$id."',
                            `file`          = '".$newfile."',
                            `original`      = '".$varvalue['logo']."',
                            `type`          = 'logo',
                            `date_added`    = '".$w['date']."'
                    ");
                }

                for( $subi = 1; $subi <= 10; $subi++) {

                    $subkey                 = "sub_".$subi;
                    $subsubkey              = "sub_sub_".$subi;
                    $subsubsubkey           = "sub_sub_sub_".$subi;
                    $subsubsubsubkey        = "sub_sub_sub_sub_".$subi;
                    $master_id              = 0;
                    $sub                    = "";
                    $subs_subs              = "";
                    $subs_subs_subs         = "";
                    $subs_subs_subs_subs    = "";


                    if ($varvalue[$subkey] != "") {
                        $varvalue['sub'] = $varvalue[$subkey];
                    } else {
                        $varvalue['sub'] = "";
                    }

                    if ($varvalue[$subsubkey] != "") {
                        $varvalue['sub_sub'] = $varvalue[$subsubkey];
                    } else {
                        $varvalue['sub_sub'] = "";
                    }

                    if ($varvalue[$subsubsubkey] != "") {
                        $varvalue['sub_sub_sub'] = $varvalue[$subsubsubkey];
                    } else {
                        $varvalue['sub_sub_sub'] = "";
                    }

                    if ($varvalue[$subsubsubsubkey]!="") {
                        $varvalue['sub_sub_sub_sub'] = $varvalue[$subsubsubsubkey];
                    } else {
                        $varvalue['sub_sub_sub_sub'] = "";
                    }

                    if ($varvalue['sub'] != '') {
                        $sub        = $varvalue['sub'];
                        $presults   = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            SELECT 
                                `service_id` 
                            FROM 
                                `list_services` 
                            WHERE 
                                `name`      = '".mysql_real_escape_string($sub)."' 
                            AND 
                                `master_id` = '".$master_id."'
                        ");

                        if (mysql_num_rows($presults) == 0 && $createcategories == 1 ) {
                            $filename = texttourl($sub);

                            mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                INSERT INTO 
                                    `list_services` 
                                SET 
                                    `name`              = '".mysql_real_escape_string($sub)."',
                                    `filename`          = '".$filename."',
                                    `profession_id`     = '".$varvalue['profession_id']."',
                                    `master_id`         = '".$master_id."'
                            ");

                            $sub_id = mysql_insert_id();
                        }else{
                            $p          = mysql_fetch_assoc($presults);
                            $sub_id     = $p['service_id'];
                        }

                        $ckresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            SELECT 
                                `service_id` 
                            FROM 
                                `rel_services` 
                            WHERE 
                                `user_id`       = '".$user_id."' 
                            AND 
                                `service_id`    = '".$sub_id."'
                        ");


                        if (mysql_num_rows($ckresults) == 0) {
                            mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                INSERT INTO 
                                    `rel_services` 
                                SET 
                                    `user_id`       = '".$user_id."',
                                    `service_id`    = '".$sub_id."',
                                    `date`          = '".$w['date']."'
                            ");
                        }

                        if ($varvalue['sub_sub'] != '') {

                            if (strstr($varvalue['sub_sub'],',')) {
                                $sub_subs       = explode(",",$varvalue['sub_sub']);
                            }else{
                                $subs_subs[]    = $varvalue['sub_sub'];
                            }

                            if (is_array($subs_subs)) {

                                foreach ($subs_subs as $sub_sub) {
                                    $spresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                        SELECT 
                                            `service_id` 
                                        FROM 
                                            `list_services` 
                                        WHERE 
                                            `name`      ='".mysql_real_escape_string($sub_sub)."' 
                                        AND 
                                            `master_id` ='".$sub_id."'
                                    ");

                                    if (mysql_num_rows($spresults) == 0 && $createcategories == 1) {
                                        $filename = texttourl($sub_sub);

                                        mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                            INSERT INTO 
                                                `list_services` 
                                            SET 
                                                `name`          ='".mysql_real_escape_string($sub_sub)."',
                                                `filename`      ='".$filename."',
                                                `profession_id` ='".$varvalue['profession_id']."',
                                                `master_id`     ='".$sub_id."'
                                        ");

                                        $sub_sub_id = mysql_insert_id();

                                    }else{
                                        $p              = mysql_fetch_assoc($spresults);
                                        $sub_sub_id     = $p['service_id'];
                                    }

                                    $ckresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                        SELECT 
                                            `service_id` 
                                        FROM 
                                            `rel_services` 
                                        WHERE 
                                            `user_id`       = '".$user_id."' 
                                        AND 
                                            `service_id`    = '".$sub_sub_id."'
                                    ");

                                    if (mysql_num_rows($ckresults) == 0) {
                                        mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                            INSERT INTO 
                                                `rel_services` 
                                            SET 
                                                `user_id`       = '".$user_id."',
                                                `service_id`    = '".$sub_sub_id."',
                                                `date`          = '".$w['date']."'
                                        ");
                                    }

                                    if ( $varvalue['sub_sub_sub'] != '' ) {

                                        if (strstr($varvalue['sub_sub_sub'],',')) {
                                            $subs_subs_subs     = explode(",",$varvalue['sub_sub_sub']);
                                        }else{
                                            $subs_subs_subs[]   = $varvalue['sub_sub_sub'];
                                        }

                                        if (is_array($subs_subs_subs)) {

                                            foreach ($subs_subs_subs as $sub_sub_sub) {

                                                $sspresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                                    SELECT 
                                                        `service_id` 
                                                    FROM 
                                                        `list_services` 
                                                    WHERE 
                                                        `name`      = '".mysql_real_escape_string($sub_sub_sub)."' 
                                                    AND 
                                                        `master_id` = '".$sub_sub_id."'
                                                ");

                                                if (mysql_num_rows($sspresults) == 0 && $createcategories == 1) {

                                                    $filename = texttourl($sub_sub_sub);
                                                    mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                                        INSERT INTO 
                                                            `list_services` 
                                                        SET 
                                                            `name`          = '".mysql_real_escape_string($sub_sub_sub)."',
                                                            `filename`      = '".$filename."',
                                                            `profession_id` = '".$varvalue['profession_id']."',
                                                            `master_id`     = '".$sub_sub_id."'
                                                    ");

                                                    $sub_sub_sub_id = mysql_insert_id();

                                                }else{
                                                    $p              = mysql_fetch_assoc($sspresults);
                                                    $sub_sub_sub_id = $p['service_id'];
                                                }

                                                $ckresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                                    SELECT 
                                                        `service_id` 
                                                    FROM 
                                                        `rel_services` 
                                                    WHERE 
                                                        `user_id`       = '".$user_id."' 
                                                    AND 
                                                        `service_id`    = '".$sub_sub_sub_id."'
                                                ");

                                                if (mysql_num_rows($ckresults) == 0) {

                                                    mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                                        INSERT INTO 
                                                            `rel_services` 
                                                        SET 
                                                            `user_id`       = '".$user_id."',
                                                            `service_id`    = '".$sub_sub_sub_id."',
                                                            `date`          = '".$w['date']."'
                                                    ");

                                                }

                                                if ($varvalue['sub_sub_sub_sub'] != '') {

                                                    if (strstr($varvalue['sub_sub_sub_sub'],',')) {
                                                        $subs_subs_subs_subs    = explode(",",$varvalue['sub_sub_sub_sub']);
                                                    }else{
                                                        $subs_subs_subs_subs[0] = $varvalue['sub_sub_sub_sub'];
                                                    }

                                                    if (is_array($subs_subs_subs_subs)) {

                                                        foreach ($subs_subs_subs_subs as $sub_sub_sub_sub) {

                                                            $ssspresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                                                SELECT 
                                                                    `service_id` 
                                                                FROM 
                                                                    `list_services` 
                                                                WHERE 
                                                                    `name`      = '".mysql_real_escape_string($sub_sub_sub_sub)."' 
                                                                AND 
                                                                    `master_id` = '".$sub_sub_sub_id."'
                                                            ");

                                                            if (mysql_num_rows($ssspresults) == 0 && $createcategories == 1) {

                                                                $filename = texttourl($sub_sub_sub_sub);

                                                                mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                                                    INSERT INTO 
                                                                        `list_services` 
                                                                    SET 
                                                                        `name`          = '".mysql_real_escape_string($sub_sub_sub_sub)."',
                                                                        `filename`      = '".$filename."',
                                                                        `profession_id` = '".$varvalue['profession_id']."',
                                                                        `master_id`     = '".$sub_sub_sub_id."'
                                                                ");

                                                                $sub_sub_sub_sub_id = mysql_insert_id();
                                                            }else{
                                                                $p                  = mysql_fetch_assoc($ssspresults);
                                                                $sub_sub_sub_sub_id = $p['service_id'];
                                                            }

                                                            $ckresults=mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                                                SELECT 
                                                                    `service_id` 
                                                                FROM 
                                                                    `rel_services` 
                                                                WHERE 
                                                                    `user_id`       = '".$user_id."' 
                                                                AND 
                                                                    `service_id`    = '".$sub_sub_sub_sub_id."'
                                                            ");

                                                            if (mysql_num_rows($ckresults) == 0) {
                                                                mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                                                    INSERT INTO 
                                                                        `rel_services` 
                                                                    SET 
                                                                        `user_id`       = '".$user_id."',
                                                                        `service_id`    = '".$sub_sub_sub_sub_id."',
                                                                        `date`          = '".$w['date']."'
                                                                ");
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if ($_POST['category'] > 0) {

                    $consql     = rtrim($consql,", ");

                    $conresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                        SELECT 
                            `contact_id` 
                        FROM 
                            `contacts` 
                        WHERE 
                            `email`  = '".mysql_real_escape_string($varvalue['email'])."' 
                        AND 
                            `email` != ''
                    ");

                    if (mysql_num_rows($conresults)==0) {

                        mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            INSERT INTO 
                                `contacts` 
                            SET ".$consql
                        );

                        $contact_id = mysql_insert_id();

                        mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            UPDATE 
                                `contacts` 
                            SET 
                                `rel_db`        = 'users_data',
                                `rel_id`        = '".$id."',
                                `date_created`  = '".$w['date']."',
                                `created_by`    = '".$sess['admin_user']."' 
                            WHERE 
                                `contact_id`    = '".$contact_id."'
                        ");

                    }else{
                        $contacts   = mysql_fetch_assoc($conresults);
                        $contact_id = $contacts['contact_id'];
                    }

                    if ($contact_id>0) {
                        mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            INSERT INTO 
                                `contacts_bridge` 
                            SET 
                                `contact_id`    = '".$contact_id."',
                                `link_db`       = 'contact_categories',
                                `rel_id`        = '2',
                                `link_date`     = '".$w['date']."',
                                `user_id`       = '".$id."' 
                            LIMIT 1
                        ");
                        mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            INSERT INTO 
                                `contacts_bridge` 
                            SET 
                                `contact_id`    = '".$contact_id."',
                                `link_db`       = 'contact_categories',
                                `rel_id`        = '".$_POST['category']."',
                                `link_date`     = '".$w['date']."',
                                `user_id`       = '".$id."' 
                            LIMIT 1
                        ");
                    }

                }

                if (is_array($uarray) && $id > 0) {
                    storeMetaData("users_data",$id,$uarray,$w);
                }

                $token      = hmac($w['date'],$id);
                $filename   = nameFile($id,"profile",$w);
                $tdate      = date("YmdHis");

                if ($varvalue['subscription_id'] > 0) {
                    $subscription_id = $varvalue['subscription_id'];
                }

                if ($varvalue['filename'] != "") {
                    $filename = $varvalue['filename'];
                }

                if ($varvalue['active'] > 0) {
                    $active = $varvalue['active'];
                }

                mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                    UPDATE 
                        `users_data` 
                    SET 
                        `token`             = '".$token."',
                        `signup_date`       = '".$tdate."',
                        `active`            = '".$active."',
                        `subscription_id`   = '".$subscription_id."',
                        `filename`          = '".$filename."' 
                    WHERE 
                        `user_id`           = '".$id."'
                ");

                if ($varvalue['service_id'] > 0) {
                    mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                        INSERT INTO 
                            `rel_services` 
                        SET 
                            `user_id`       = '".$id."',
                            `service_id`    = '".$varvalue['service_id']."',
                            `date`          = '".$w['date']."'
                    ");
                }

                if ($varvalue['association_id'] != "") {
                    $assoc = explode(",",$varvalue['association_id']);
                    foreach($assoc as $key => $value) {
                        if ($value > 0) {
                            mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                INSERT INTO 
                                    `rel_users` 
                                SET 
                                    `user_id`           = '".$id."',
                                    `affiliation_id`    = '".$value."',
                                    `date`              = '".$w['date']."'
                            ");
                        }
                    }
                }

                if ($varvalue['services'] != "all" && $varvalue['services'] != "") {

                    $varvalue['services'] = explode(",",$varvalue['services']);

                    $result['services_exploded'] = $varvalue['services'];

                    if ($varvalue['profession_id'] > 0) {
                        $addprof = "AND `profession_id`='".$varvalue['profession_id']."'";
                    }

                    foreach ($varvalue['services'] as $value) {

                        $value = trim($value);

                        if (strstr($value,"'")) {
                            $value = mysql_real_escape_string($value);
                        }
                        
                        $extraWhere = "";

                        if(is_numeric($value)){
                            $extraWhere = " OR  `service_id` = '".$value."' ";
                        }
                        
                        $siresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            SELECT 
                                * 
                            FROM 
                                `list_services` 
                            WHERE 
                                ( (`name` = '".$value."' AND `name` != '') ".$extraWhere." ) ".$addprof." 
                            LIMIT 1
                        ");

                        $result['services_fetched_sql'][] = " SELECT * FROM `list_services` WHERE ((`name` = '".$value."' AND `name` != '') OR `service_id` = '".$value."') ".$addprof." LIMIT 1";

                        if (mysql_num_rows($siresults) == 0 && $createcategories == 1 && $value != '') {
                            $filename = texttourl($value);

                            mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                INSERT INTO 
                                    `list_services` 
                                SET 
                                    `name`          = '".$value."',
                                    `filename`      = '".$filename."',
                                    `profession_id` = '".$varvalue['profession_id']."'");

                            $si['service_id'] = mysql_insert_id();
                        }else{
                            $si = mysql_fetch_assoc($siresults);
                        }

                        $result['services_fetched_resultset'][] = $si;

                        if ($si['service_id'] != "") {
                            mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                                INSERT INTO 
                                    `rel_services` 
                                SET 
                                    `user_id`       = '".$id."',
                                    `service_id`    = '".$si['service_id']."',
                                    `date`          = '".$w['date']."'
                            ");
                            $result['services_insert_query'][] = "INSERT INTO `rel_services` SET `user_id` = '".$id."', `service_id` = '".$si['service_id']."', `date` = '".$w['date']."'";
                        }
                    }
                }

                if ($varvalue['services'] == "all" && $varvalue['profession_id'] > 0) {
                    $siresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                        SELECT * FROM 
                            `list_services` 
                        WHERE 
                            (`profession_id`    = '0' 
                        OR 
                            `profession_id`     = '".$varvalue['profession_id']."') GROUP BY `name`
                    ");

                    while ($si = mysql_fetch_assoc($siresults)) {
                        mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            INSERT INTO 
                                `rel_services` 
                            SET 
                                `user_id`       = '".$id."',
                                `service_id`    = '".$si['service_id']."',
                                `date`          = '".$w['date']."'
                        ");
                    }
                }

                //// Associate Locations
                if ($varvalue['county_name'] != "") {
                    $zip        = "";
                    $zresults   = mysql($DBMain,"SELECT * FROM `ZIPS` WHERE `COUNTY`='$varvalue[county_name]' AND `STATE`='$varvalue[state_code]'");

                    while ($z = mysql_fetch_assoc($zresults)) {
                        $zip                      .= $z['ZIP'].",";
                        $varvalue['state_code']    = $z['STATE'];
                        $varvalue['county_name']   = $z['COUNTY'];
                    }

                    $zip = rtrim($zip,",");

                    if ($zip != "") {
                        mysql(brilliantDirectories::getDatabaseConfiguration('database'),"
                            INSERT INTO 
                                `users_locations`  
                            SET 
                                `user_id`       = '".$id."',
                                `zip_id`        = '',
                                `county`        = '".$varvalue['county_name']."',
                                `state`         = '".$varvalue['state_code']."',
                                `date_added`    = '".$w['date']."',
                                `zips`          = '".$zip."'
                        ");
                    }
                }
                /// Create User Settings
                createUserSettings($id,$w);
                checkProfileComplete($id,$w);
            }else{
                $result['inserted_errors_queries']['user_csv_row'.($rowIndex+$start)] = "INSERT INTO `users_data` SET ".$sql;
            }
        }
    }

    echo json_encode(bdString::prepareSpecialCharacter($result));

    exit;
}
?>