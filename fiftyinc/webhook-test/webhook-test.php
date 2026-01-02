<?php 
	mysql_query("INSERT INTO test SET test = '".json_encode($_POST)."'");
 ?>