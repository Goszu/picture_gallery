<?php
require_once("../../includes/initialise.php");

if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>

    <div id="main">

        <?php echo output_message($message); ?>

        <form action="deleteusers.php" method="post">
            <table>
            <?php
            $users = User::find_all();
            foreach ($users as $user) {
                echo "<tr><td><input type='checkbox' value='". $user->id ."' name='uid[]' /></td>";
                echo "<td>". $user->id ."</td><td>". $user->username ."</td></tr>";
            }
            ?>
            </table>
            <input type="submit" name="submit" value="Delete Selected Users" />
        </form>
        <a href="index.php">Return to admin menu</a>


    </div>

<?php include_layout_template('admin_footer.php'); ?>
