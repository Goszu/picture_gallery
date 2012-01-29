<?php
require_once("../../includes/initialise.php");

if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>

    <div id="main">
        <h2>Items List</h2>

        <?php echo output_message($message); ?>

        <form action="deleteitems.php" method="post">
            <?php $items = Item::find_all(); ?>
            <ul class='sortable'>
            <?php foreach ($items as $item) { ?>
                <li class="ui-state-default" id="itemid-<?php echo $item->id ?>">
                    <input type="checkbox" value="<?php echo $item->id ?>" name="iid[]" />
                    <span><?php echo $item->id ?> </span>
                    <span><?php echo $item->item_text ?> </span>
                    <span><?php echo $item->filename ?> </span>
                    <span><?php echo $item->size_as_text() ?></span>
                    <span><a href="edititem.php?itemid=<?php echo $item->id ?>">Edit</a></span>
                </li>
            <?php } ?>
            </ul>
            <input type="submit" name="submit" value="Delete Selected Items" />
        </form>
        <a href="index.php">Return to admin menu</a>

    </div>

    <script type="text/javascript">
    // <![CDATA[
        $(function () {
            PORTFOLIO.sortItems();
        });
    // ]]>
    </script>

<?php include_layout_template('admin_footer.php'); ?>
