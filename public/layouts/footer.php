<footer id="footer">Copyright <?php echo date("Y", time()); ?>, PattersonDesignAssociates</footer>
</body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>