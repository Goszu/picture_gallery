<?php require_once("../includes/initialise.php");?>

<html>
<head>
    <title>Photo Gallery</title>
    <link href="css/main.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="items">
    <?php $items = Item::find_all();
    foreach ($items as $item) { ?>
        <div class="item">
            <img alt="<?php echo $item->link_txt ?>" style="width: 300px;" src="images/<?php echo $item->filename ?>" />
            <div class="item-text">
                <?php echo $item->item_text ?>
            </div>
            <div class="item-link">
                <a href="<?php echo $item->link_url ?>"><?php echo $item->link_txt ?></a>
            </div>

        </div>
    <?php } ?>
</div>

</body>
</html>