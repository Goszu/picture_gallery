<div id="footer">
    Copyright <?php echo date("Y", time()); ?>, MarcinGosz
</div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>