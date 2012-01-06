<?php
require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<html>
<head>
    <title>Portfolio Admin Users List</title>
    <link href="../css/main.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="header">
      	<h1>Portfolio - User List</h1>
    </div>
    <div id="main">
        <form action="deleteusers.php" method="post">
            <table>
            <?php
            $users = User::find_all();
            foreach ($users as $user) {
                echo "<tr><td><input type='checkbox' value='". $user->id ."' name='uid[]' /></td>";
                echo "<td>". $user->id ."</td><td>". $user->username ."</td></tr>";
            }
            ?>
            </table>
            <input type="submit" name="submit" value="Delete Selected Users" />
        </form>
        <a href="index.php">Return to admin menu</a>


    </div>
    <div id="footer">
    	Copyright <?php echo date("Y", time()); ?>, GoodWebSites.eu
    </div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>
