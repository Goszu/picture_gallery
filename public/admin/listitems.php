<?php
require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<html>
<head>
    <title>Portfolio Admin Item List</title>
    <link href="../css/main.css" media="all" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="../js/custom.js"></script>
</head>
<body>
    <div id="header">
      	<h1>Portfolio - Item List</h1>
    </div>

    <?php echo output_message($message); ?>

    <div id="main">
        <form action="deleteitems.php" method="post">
            <table>
            <?php
            $items = Item::find_all();
            echo "<ul class='sortable'>";
            foreach ($items as $item) {
                echo "<li><input type='checkbox' value='". $item->id ."' name='iid[]' /><span>". $item->id ."</span><span> ". $item->filename ."</span></li>";
            }
            echo "</ul>";
            ?>
            </table>
            <input type="submit" name="submit" value="Delete Selected Items" />
        </form>
        <a href="index.php">Return to admin menu</a>


    </div>
    <div id="footer">
    	Copyright <?php echo date("Y", time()); ?>, GoodWebSites.eu
    </div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>
