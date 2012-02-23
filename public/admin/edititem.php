<?php
require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }

if (isset($_POST['submit'])) {

    $item = Item::find_by_id(trim($_POST['item_id']));
    $item->name = trim($_POST['name']);
    $item->item_text = trim($_POST['item_text']);

    if ($_FILES['file_upload']) {
        $item->delete_file($_POST['old_file']);
        $item->attach_file($_FILES['file_upload']);
        $item->move_file($_FILES['file_upload']);
    }

    if($item->update()) {
        // Success
        $session->message("Item updated successfully.");
        redirect_to('listitems.php');
    } else {
        // Failure
        $message = join("<br />", $item->errors);

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
        <label for="file_upload">New thumbnail file: (old one will be deleted!)</label>
        <input id="file_upload" type="file" name="file_upload" />
        <input type="submit" name="submit" value="Update Item" />
    </form>
    <a href="listitems.php">Return to items list</a>
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