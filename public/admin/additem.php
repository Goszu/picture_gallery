<?php
require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }

if (isset($_POST['submit'])) {

    $allItems = Item::find_all();
    $itemCount = sizeof($allItems);

    $item = new Item();
    $item->position = $itemCount + 1;
    $item->name = trim($_POST['name']);
    $item->item_text = trim($_POST['item_text']);
    if ($_POST['slideshow']) {
        $item->slideshow = 1;
    } else {
        $item->slideshow = 0;
    }
    $item->bcolor = trim($_POST['bcolor']);

    $item->attach_file($_FILES['file_upload']);
	if($item->save()) {
	// Success
        $session->message("Item uploaded successfully.");
		redirect_to('listitems.php');
	} else {
	// Failure
        $message = join("<br />", $item->errors);
	}

};

include_layout_template('admin_header.php');
?>

<div id="main">
    <h2>Add Item</h2>
    <?php echo output_message($message); ?>
    <form action="additem.php" enctype="multipart/form-data" method="post">
        <label for="name">Item name:</label>
        <input id="name" type="text" name="name" />
        <label for="item_text">Item Text:</label>
        <textarea id="item_text" name="item_text" rows="10" cols="30"></textarea>
        <label for="file_upload">Thumbnail file:</label>
        <input id="file_upload" type="file" name="file_upload" />
        <input id="slideshow" type="checkbox" name="slideshow" value="1" <?php if ($item->slideshow == 1) {?> checked="checked" <?php } ?>/>
        <label for="slideshow">Enable slideshow</label>
        <label for="bcolor">Background color</label>
        <input id="bcolor" type="text" name="bcolor" />

        <input type="submit" name="submit" value="Add Item" />
    </form>
    <a href="index.php">Return to admin menu</a>
</div>

<script type="text/javascript">
    //<![CDATA[
    CKEDITOR.replace( 'item_text',
        {
            filebrowserBrowseUrl : '../../includes/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : '../../includes/ckfinder/ckfinder.html?Type=Images',
            filebrowserFlashBrowseUrl : '../../includes/ckfinder/ckfinder.html?Type=Flash',
            filebrowserUploadUrl : '../../includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : '../../includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : '../../includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
    //]]>
</script>

    <?php include_layout_template('admin_footer.php'); ?>