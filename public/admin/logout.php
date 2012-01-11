<?php require_once("../../includes/initialise.php");; ?>
<?php	
    $session->logout();
    redirect_to("login.php");
?>
