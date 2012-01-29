<div id="footer">
    Copyright <?php echo date("Y", time()); ?>, footer info
</div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>