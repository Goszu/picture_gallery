<?php require_once("../includes/initialise.php");?>
<?php include_layout_template('header.php'); ?>

<script type="text/javascript">
    PORTFOLIO.items = [
    <?php $items = Item::find_all();
          $item_count = count($items);
          $index = 0;
    foreach ($items as $item) { ?>
        {
            "decsription" : "<?php echo htmlentities($item->item_text) ?>",
            "image" : "<?php echo $item->filename ?>",
            "url" : "<?php echo $item->link_url ?>",
            "anchor" : "<?php echo htmlentities($item->link_txt) ?>"
        }<?php $index++; if ($index != $item_count) { echo ", "; } ?>
    <?php } ?>
    ];

    $(function () {
        PORTFOLIO.slideInstance = PORTFOLIO.bgSlide({
            container : "#content",
            width : 800,
            height : 800,
            items : PORTFOLIO.items
        });
    });

</script>

<div id="page">
    <div id="content">
        <div id="text"> </div>
        <div id="link"><a> </a></div>
    </div>
</div>

<?php include_layout_template('footer.php'); ?>
