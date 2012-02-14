<?php require_once("../includes/initialise.php");?>
<?php include_layout_template('blocks_header.php'); ?>

<script type="text/javascript">
    $(function () {
        boxmania('#pane');
    });

</script>


<div id="pane">
    <?php $items = Item::find_all();
    $item_count = count($items);
    $index = 0;
    foreach ($items as $item) {
        $index++ ?>

        <div class="block" id="bl-<?php echo $index ?>" data-no="<?php echo $index ?>" data-id="<?php echo $item->id ?>">
            <div class="image-container">
                <img src="images/<?php echo $item->filename ?>" />
            </div>
            <div class="name">
                <?php echo $item->name ?>
            </div>
        </div>
    <?php } ?>
</div>

<?php include_layout_template('footer.php'); ?>