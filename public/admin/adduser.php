<?php
require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }

if (isset($_POST['submit'])) {

    $user = new User();
    $user->username = trim($_POST['username']);

    $found_user = User::find_by_username($user->username);
    if ($found_user) {
        $message = "User with this name already exists";
    } else {
        $user->password = md5(trim($_POST['password']));
        if($user->create()) {
            redirect_to('index.php');
        } else {
            $message = "User has not been added!!";
        };
    };
};
?>
<html>
<head>
    <title>Portfolio Admin Add User</title>
    <link href="../css/main.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="header">
      	<h1>Portfolio - Add User</h1>
    </div>
    <div id="main">
		<h2>Add User</h2>
		<?php echo output_message($message); ?>
		<form action="adduser.php" method="post">
		  	<label for="username">Username:</label>
		    <input id="username" type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
		    <label for="password">Password:</label>
		    <input id="password" type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
		    <input type="submit" name="submit" value="Add User" />
		</form>
        <a href="index.php">Return to admin menu</a>
    </div>
    <div id="footer">
    	Copyright <?php echo date("Y", time()); ?>, GoodWebSites.eu
    </div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>
