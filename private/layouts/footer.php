    <div id="footer"><i>
    Copyright <?php echo date("Y"); ?> - Noi Compriamo Auto.it</i><br>
    <!-- <i> Site by Federico Rizzo </i> -->
    </div>
  <script src="js/main1.js"></script>
 </body>
</html>
<?php if (isset($local_db)) { $local_db->close_connection(); } ?>
<?php if (isset($fp_db)) { $fp_db->close_connection(); } ?>
