<?php
require_once("../../includes/initialise.php");
if($session->is_logged_in()) { redirect_to("index.php");}

// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['submit'])) { // Form has been submitted.

  $username = trim($_POST['username']);
  $password = md5(trim($_POST['password']));
  
  // Check database to see if username/password exist.
	$found_user = User::authenticate($username, $password);
	
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

?>
<html>
<head>
    <title>Portfolio Admin Login</title>
    <link href="../css/main.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="header">
      	<h1>Portfolio Admin</h1>
    </div>
    <div id="main">
		<h2>Login</h2>
		<?php echo output_message($message); ?>
		<form action="login.php" method="post">
		  	<label for="username">Username:</label>
		    <input id="username" type="text" name="username" maxlength="30" />
		    <label for="password">Password:</label> 
		    <input id="password" type="password" name="password" maxlength="30" />
		    <input type="submit" name="submit" value="Login" />
		</form>
    </div>
    <div id="footer">
    	Copyright <?php echo date("Y", time()); ?>, GoodWebSites.eu
    </div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>
