<?php
require_once("../../includes/initialise.php");

if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>

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
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
	</div>

<?php include_layout_template('admin_footer.php'); ?>