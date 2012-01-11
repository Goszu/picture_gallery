<div id="footer">
    Copyright <?php echo date("Y", time()); ?>, GoodWebSites.eu
</div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>