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
            <?php
            $items = Item::find_all();
            ?>
            <ul class='sortable'>
            <?php foreach ($items as $item) { ?>
                <li>
                    <input type="checkbox" value="<?php echo $item->id ?>" name="iid[]" />
                    <span><?php echo $item->id ?> </span>
                    <span><?php echo $item->filename ?></span>
                    <span><a href="edititem.php?itemid=<?php echo $item->id ?>">Edit</a></span>
                </li>
            <?php } ?>
            </ul>
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
