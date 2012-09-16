<?php require_once("../includes/initialise.php");?>
<?php include_layout_template('header.php'); ?>
<div id="pane">
    <?php $items = Item::find_all();
    $item_count = count($items);
    $index = 0;
    foreach ($items as $item) {
        $index++ ?>

        <section <?php if ($item->bcolor != null) { ?> data-bg-col="#<? echo $item->bcolor ?>" <?php } ?>
            class="block <?php if ($item->slideshow) { ?> slide<?php } ?>" id="bl-<?php echo $index ?>"
            data-no="<?php echo $index ?>" data-id="<?php echo $item->id ?>">

            <div class="image-container">
                <img alt="<?php echo $item->name ?>" src="<?php echo $item->thumbnail ?>" />
            </div>
            <h2 class="name">
                <?php echo $item->name ?>
            </h2>
            <noscript>
                <article>
                    <?php echo $item->item_text ?>
                </article>
            </noscript>
        </section>
    <?php } ?>
</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/boxmania.js"></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/Swis721_Lt_BT_400.font.js"></script>
<script type="text/javascript">
    $(function () {
        boxes = boxmania('#pane');
        Cufon.replace('#company');
    });
</script>

<?php include_layout_template('footer.php'); ?>