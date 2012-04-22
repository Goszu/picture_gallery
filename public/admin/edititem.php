<?php
require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }

if (isset($_POST['submit'])) {

    $item = Item::find_by_id(trim($_POST['item_id']));
    $item->name = trim($_POST['name']);
    $item->item_text = trim($_POST['item_text']);
    if (isset($_POST['slideshow'])) {
        $item->slideshow = 1;
    } else {
        $item->slideshow = 0;
    }
    $item->bcolor = trim($_POST['bcolor']);

    $item->thumbnail = $_POST['thumbnail'];

    if($item->update()) {
        // Success
        $session->message("Item updated successfully.");
        redirect_to('listitems.php');
    } else {
        // Failure
        $message = "Failed to add an item.";
    }

} else {
      $item = Item::find_by_id(trim($_GET['itemid']));
};

include_layout_template('admin_header.php');
?>

<div id="main">
    <h2>Edit item</h2>
    <?php echo output_message($message); ?>
    <form action="edititem.php?itemid=<?php echo trim($_GET['itemid']) ?>" enctype="multipart/form-data" method="post">
        <label for="name">Item name:</label>
        <input id="name" type="text" name="name" value="<?php echo $item->name ?>" />
        <label for="item_text">Item Text:</label>
        <textarea id="item_text" name="item_text" rows="10" cols="30"><?php echo $item->item_text ?></textarea>
        <input type="hidden" value="<?php echo trim($_GET['itemid']) ?>" name="item_id" />
        <input type="hidden" value="<?php echo $item->filename ?>" name="old_file" />

        <div id="img-browse">
            <input id="thumbnail" name="thumbnail" type="text" value="<?php echo $item->thumbnail ?>" />
            <input type="button" id="browse" value="Browse Server" />
        </div>

        <input id="slideshow" type="checkbox" name="slideshow" value="1" <?php if ($item->slideshow == 1) {?> checked="checked" <?php } ?>/>
        <label for="slideshow">Enable slideshow</label>
        <label class="dblock" for="bcolor">Background color</label>
        <input id="bcolor" type="text" value="<?php if ($item->bcolor != null) echo $item->bcolor ?>" name="bcolor" />

        <input type="submit" name="submit" value="Update Item" />
    </form>
    <a href="listitems.php">Return to items list</a>
</div>

<script type="text/javascript" src="../../includes/ckfinder/ckfinder.js"></script>

<script type="text/javascript">
    //<![CDATA[
    $(function () {
        CKEDITOR.replace( 'item_text',
            {
                filebrowserBrowseUrl : '../../includes/ckfinder/ckfinder.html',
                filebrowserImageBrowseUrl : '../../includes/ckfinder/ckfinder.html?Type=Images',
                filebrowserUploadUrl : '../../includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl : '../../includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
            });
        $('#browse').click(function () {
            PORTFOLIO.browseServer();
        });
    });
    //]]>
</script>
