    <div id="footer">
    Copyright <?php echo date("Y"); ?> - Noi Compriamo Auto.it	<br>
    <!-- <i> Site by Federico Rizzo </i> -->
    </div>
  <script src="js/main1.js"></script>
 </body>
</html>
<?php if (isset($handle)) { mysqli_close($handle); } ?>
<?php if (isset($conn)) { mysqli_close($conn); } ?>
<?php if (isset($local_db)) { $local_db->close_connection(); } ?>
