<?php require_once("../includes/initialise.php");?>
<?php include_layout_template('header.php'); ?>
<div id="pane">
    <?php $items = Item::find_all();
    $item_count = count($items);
    $index = 0;
    foreach ($items as $item) {
        $index++ ?>

        <div <?php if ($item->bcolor != null) { ?> style="border-color: #<? echo $item->bcolor ?>" <?php } ?>
            class="block <?php if ($item->slideshow) { ?> slide<?php } ?>" id="bl-<?php echo $index ?>"
            data-no="<?php echo $index ?>" data-id="<?php echo $item->id ?>">
            <div class="image-container">
                <img alt="<?php echo $item->name ?>" src="images/block_thumbs/<?php echo $item->filename ?>" />
            </div>
            <div class="name">
                <?php echo $item->name ?>
            </div>
        </div>
    <?php } ?>
</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/boxmania.js"></script>
<script type="text/javascript">
// <[CDATA[
    $(function () {
        boxes = boxmania('#pane');
    });
// ]]>
</script>

<?php include_layout_template('footer.php'); ?>