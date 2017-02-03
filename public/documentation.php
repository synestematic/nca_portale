<?php
require_once("../private/initialize.php");
use Icewind\SMB\Server;
require('/var/www/html/private/SMB-master/vendor/autoload.php');

if (!$session->is_logged_in()) { redirect("login.php"); }
$logged_user = User::find_by_id($_SESSION["user_id"]);
if (!$logged_user->is_branch && !$logged_user->is_agency) { redirect("admin.php"); }

$anno = strftime("%Y");
$giorno_richiesto = strftime("%Y-%m-%d");
$giorno_normale = Time::inverse_date($giorno_richiesto);

if (isset($_GET['day'])) {
  $giorno_richiesto = $_GET["day"];
  $giorno_normale = Time::inverse_date($giorno_richiesto);
}

$nas = new Server('10.4.4.250', 'portale', '76837683');
$share = $nas->getShare('FTP_BRANCHES');

$main_contents = $share->dir($logged_user->main_share);
$day_contents = $share->dir($logged_user->main_share.$giorno_richiesto.DS);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
 <head>
  <!-- <title>NCA.it - PORTALE</title> -->
  <link href="css/style.css" media="all" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
 </head>
 <body>
<div id="main">
    <!-- <div id="page"> -->
    <div id="">
    <?php echo $session->message(); ?>
    <?php echo form_errors($errors); ?>
    <h2><?php //echo htmlentities($giorno_normale); ?></h2>
    <table id="giornata">
      <tr><th>Data</th></tr>
      <?php
          rsort($main_contents);
          foreach ($main_contents as $item) {
              $inversed_date = Time::inverse_date($item->getName());
              echo ( $item->getName() == $giorno_richiesto ) ? '<tr style="background: #ffffff">' : '<tr>' ;
              echo '<td><a href="'.$_SERVER['PHP_SELF'].'?day='.$item->getName().'">'.$inversed_date.'</a></td>';
              echo '<tr>';
          }
      ?>
    </table>
    <table id="filedim">
      <tr><th>File</th><th>Dimensioni</th></tr>
      <?php
          foreach ($day_contents as $item) {
            // fa vedere solo le estensioni .pdf
            if (strpos($item->getName(), '.pdf') !== false) {
              echo '<tr>';
              echo '<td><a target="_blank" href="download.php?foo='.base64_encode($item->getName()).'&bar='.base64_encode($giorno_richiesto).'">'.$item->getName().'</a></td>';
              // CONVERT TO KB
              $size = ($item->getSize() / 1024 );
              $size = number_format((float)$size, 1, '.', '');
              echo '<td>'.$size.' KB</td>';
              echo '<tr>';
            }
          }
      ?>
      <a href="edit_user.php?id='.urlencode($_SESSION["user_id"]).'">
    </table>
      <br><br><br><br><br><br>
  </div>
  <br><br><br><br><br><br>
</div>

<script src="js/documentation.js"></script>
</body>
</html>
<?php if (isset($handle)) { mysqli_close($handle); } ?>
<?php if (isset($conn)) { mysqli_close($conn); } ?>
<?php if (isset($local_db)) { $local_db->close_connection(); } ?>
