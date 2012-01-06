<?php
$dbhost							= "localhost";
$dbuser							= "username";
$dbpass							= "password";
$dbname							= "database_name";

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Error connecting to database");
mysql_select_db($dbname);
?>