<?php

require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }


$array	= $_POST['itemid'];

if ($_POST['update'] == "update"){
    $count = 1;
    foreach ($array as $idval) {

        $item = Item::find_by_id($idval);
        $item->position = $count;
        $item->update();

        $count ++;
    }
    echo 'New item order saved!';
}

?>