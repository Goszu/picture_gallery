<?php
require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }

if (isset($_POST['submit'])) {
    foreach($_POST['iid'] as $itemId){

        $item = Item::find_by_id($itemId);
        $item->delete();
    }
    redirect_to('listitems.php');
};
?>
<?php if(isset($database)) { $database->close_connection(); } ?>