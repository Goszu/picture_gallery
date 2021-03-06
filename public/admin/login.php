<?php
require_once("../../includes/initialise.php");
if($session->is_logged_in()) { redirect_to("index.php");}

// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['submit'])) { // Form has been submitted.

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $md5_password = md5($password);
  
    // Check database to see if username/password exist.
	$found_user = User::authenticate($username, $md5_password);
	
    if ($found_user) {
        $session->login($found_user);
        redirect_to("index.php");
    } else {
        // username/password combo was not found in the database
        $message = "Username/password combination incorrect.";

    }
  
} else { // Form has not been submitted.
    $username = "";
    $password = "";
}

include_layout_template('admin_header.php'); ?>

    <div id="main">
		<h2>Login</h2>
		<?php echo output_message($message); ?>
		<form action="login.php" method="post">
		  	<label for="username">Username:</label>
		    <input id="username" type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
		    <label for="password">Password:</label> 
		    <input id="password" type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
		    <input type="submit" name="submit" value="Login" />
		</form>
    </div>

<?php include_layout_template('admin_footer.php'); ?>
