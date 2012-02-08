<?php
 require_once("../includes/initialise.php");

if (isset($_POST['item_id'])) {

    $item = Item::find_by_id(trim($_POST['item_id']));
    echo $item->item_text;
}

if(isset($database)) { $database->close_connection(); }

?>