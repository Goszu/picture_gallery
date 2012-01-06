<?php 
$connection = mysql_connect("localhost", "root", "");

if (!$connection) {
	die("<strong>Połączenie z bazą danych nie powiodło się: </strong>" . mysql_error() );
}
$db_select = mysql_select_db("gerry", $connection);
if (!$db_select) {
	die("<strong>Nie znaleziono bazy danych: </strong>" . mysql_error() );
}

// add new item
if (isset($_POST[position]) && isset($_POST[link_txt]) && isset($_POST[link_url]) && isset($_POST[text])) {
	$position = mysql_real_escape_string($_POST[position]);
	$link_txt = mysql_real_escape_string($_POST[link_txt]);
    $link_url = mysql_real_escape_string($_POST[link_url]);
    $text = mysql_real_escape_string($_POST[text]);
    
	mysql_query("INSERT INTO items (position, link-txt, link-url, text) VALUES('{$position}', '{$link_txt}', '{$link_url}', '{$text}')");
}
mysql_close($connection);
header("Location: index.php");
?>