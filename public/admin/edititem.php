<?php
require_once("../../includes/initialise.php");

if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php');

if (isset($_POST['submit'])) {

    $item = Item::find_by_id(trim($_POST['item_id']));
    $item->link_txt = trim($_POST['link_txt']);
    $item->link_url = trim($_POST['link_url']);
    $item->item_text = trim($_POST['item_text']);

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
?>

<div id="main">
    <h2>Edit item</h2>
    <?php echo output_message($message); ?>
    <form action="edititem.php" method="post">
        <!--<label for="position">Position:</label>
              <input type="text" name="position" maxlength="5" />-->
        <label for="link_txt">Text for link:</label>
        <input id="link_txt" type="text" name="link_txt" value="<?php echo $item->link_txt ?>" />
        <label for="link_url">Link URL:</label>
        <input id="link_url" type="text" name="link_url" value="<?php echo $item->link_url ?>" />
        <label for="item_text">Item Text:</label>
        <textarea id="item_text" name="item_text" rows="10" cols="30"> <?php echo $item->item_text ?> </textarea>
        <input type="hidden" value="<?php echo trim($_GET['itemid']) ?>" name="item_id" />
        <input type="submit" name="submit" value="Update Item" />
    </form>
</div>
<a href="index.php">Return to admin menu</a>

<?php include_layout_template('admin_footer.php'); ?>