<?php
require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }

if (isset($_POST['submit'])) {
    foreach($_POST['uid'] as $userId){

        $user = User::find_by_id($userId);
        $user->delete();
    }
    redirect_to('listusers.php');
};
?>
<?php if(isset($database)) { $database->close_connection(); } ?>