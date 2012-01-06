<?php require_once("../includes/initialise.php");?>

<html>
<head>
    <title>Photo Gallery</title>
    <link href="css/main.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="items">
<?php
$items = Item::find_all();
foreach ($items as $item) {
?>
    <div class="item">
        <img src="images/<?php echo $item->filename ?>" />
    </div>
<?php
}
?>
</div>

</body>
</html>