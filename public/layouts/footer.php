<div id="footer">Copyright <?php echo date("Y", time()); ?>, CompanyName</div>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>