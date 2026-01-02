<?php  
if ($pars[3] == "" || $pars[2] == "copy") { 
    $w[date] = date("YmdHis");
    $token = hmac($_COOKIE[userid],$w[date]);
    mysql($w[database],"INSERT INTO `users_data` (`token`) VALUES('".$token."')");
    $id = mysql_insert_id();    
    $new = 1;
    $pars[3] = $token; 
    $pars[2] = "edit";

} else { 
    $userPostResults = mysql($w[database],"SELECT * FROM `users_data` WHERE `token`='$pars[3]'");
    $userPost = mysql_fetch_array($userPostResults); 
    $id = $userPost[user_id];
}                           
$columnresuls = mysql($w[database],"SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='$w[database]' AND `TABLE_NAME`='users_data'");

while ($c = mysql_fetch_assoc($columnresuls)) { 
    $columns[] = $c[COLUMN_NAME]; 
}
$_POST[parent_id] = $user_data[user_id];         
$_POST[subscription_id] = $user_data[subscription_id];         

foreach ($_POST as $key => $value) { 
    
    if ($key != "user_id" && $key != "token") {
        
        if (is_string($value)) { 
            $value = trim($value); 
        }

        if (in_array($key,$columns)) { 
            
            $sql[] = "`$key`='" . mysql_real_escape_string($value) . "'";
            
        } else { 
            $additional_fields[$key] = $value; 
        }
    }//END if ($key != "data_id" && $key != "data_id") 
}//END foreach ($_POST as $key => $value)
if (is_array($sql)) { 
    $sql = implode(",",$sql); 
}
mysql($w[database],"UPDATE `users_data` SET $sql WHERE `token`='$pars[3]'"); 

if (is_array($additional_fields)) { 

    foreach ($additional_fields as $key => $value) {
        
        if (is_array($value)) { 
            $value = implode(",",$value); 
        }
        $array[$key] = $value;
    }
    
    if (is_array($array)) { 
        storeMetaData("users_data",$id,$array,$w); 
    }
}
nameFile($id,"profile",$w);
?>