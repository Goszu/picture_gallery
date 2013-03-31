<footer id="footer">Copyright <?php echo date("Y", time()); ?>, MarcinGosz</footer>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>