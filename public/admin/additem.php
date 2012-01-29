<?php
require_once("../../includes/initialise.php");
if (!$session->is_logged_in()) { redirect_to("login.php"); }

include_layout_template('admin_header.php');

if (isset($_POST['submit'])) {

    $allItems = Item::find_all();
    $itemCount = sizeof($allItems);
    echo $itemCount;

    $item = new Item();
    $item->position = $itemCount + 1;
    $item->link_txt = trim($_POST['link_txt']);
    $item->link_url = trim($_POST['link_url']);
    $item->item_text = trim($_POST['item_text']);

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
?>

    <div id="main">
		<h2>Add Item</h2>
		<?php echo output_message($message); ?>
		<form action="additem.php" enctype="multipart/form-data" method="post">
		  	<!--<label for="position">Position:</label>
		    <input type="text" name="position" maxlength="5" />-->
		    <label for="link_txt">Text for link:</label>
		    <input type="text" id="link_txt" name="link_txt" />
            <label for="link_url">Link URL:</label>
		    <input type="text" id="link_url" name="link_url" />
            <label for="item_text">Item Text:</label>
            <textarea id="item_text" name="item_text" rows="10" cols="30"> </textarea>
            <label for="file_upload">Photo file:</label>
		    <input id="file_upload" type="file" name="file_upload" />

		    <input type="submit" name="submit" value="Add Item" />
		</form>
        <a href="index.php">Return to admin menu</a>
    </div>

    <?php include_layout_template('admin_footer.php'); ?>