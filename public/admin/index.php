<?php
require_once("../../includes/initialise.php");

if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<html>
<head>
    <title>Photo Gallery</title>
    <link href="../css/main.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="header">
    	<h1>Portfolio Admin</h1>
    </div>
    <div id="main">
		<h2>Menu</h2>
        <ul>
            <li>
                <a href="adduser.php">Add user</a>
            </li>
            <li>
                <a href="additem.php">Add item</a>
            </li>
            <li>
                <a href="listusers.php">Users</a>
            </li>
            <li>
                <a href="listitems.php">Items</a>
            </li>
        </ul>
	</div>
		
    <div id="footer">
    	Copyright <?php echo date("Y", time()); ?>, GoodWebSites.eu
    </div>
</body>
</html>
