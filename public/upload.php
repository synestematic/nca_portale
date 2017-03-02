<?php
require_once("../private/initialize.php");
use Icewind\SMB\Server;
require('/var/www/html/private/SMB-master/vendor/autoload.php');

if (!$session->is_logged_in()) { redirect("login"); }
$logged_user = User::find_by_id($_SESSION["user_id"]);
if (!$logged_user->is_branch && !$logged_user->is_agency) { redirect("admin"); }
if (!isset($_GET['d'])) { redirect("admin"); }
Time::if_sunday_go_to('admin');

$targa = 'Targa';
$stockid = 'Stock ID';
$correct_stockid = true; //defaults to true so Agencies pass validation even without the stock_id form field.

$data_richiesta = (isset($_GET['d'])) ? $data_richiesta = base64_decode($_GET["d"]) : strftime("%Y-%m-%d");
// $correct_date = false;

$data_normale = Time::inverse_date($data_richiesta);

// NAS CONNECTION
$nas = new Server(NAS_SERVER, NAS_USER, NAS_PASS);
$share = $nas->getShare(NAS_SHARE);
$main_contents = $share->dir($logged_user->main_share);

// IM NOT DOING THIS VALIDATION ATM as I cannot go back into other years folders
// RELYING solely ON base64encoding to avoid users messing with urls
// THIS IS NECESSARY IF ANYONE DECIDES TO MESS WITH THE URL TO VIEW OTHER DATES
// foreach ($main_contents as $item) {
//     if ($item->getName() === $data_richiesta ) {
//       $correct_date = true;
//     }
// }

// POST handles File submits while GET handles day-view requests
if ( isset($_POST['submit']) ) {
// FILE SUBMITTED
    $targa = strtoupper($_POST['targa']);
    $stockid = (isset($_POST['stockid'])) ? strtoupper($_POST['stockid']) : $stockid ;

    if ($logged_user->is_branch === true) {
      // this if statement will rarely evaluate as validation is also done client side thru main.js
      $ab = substr($_POST['stockid'] ,0,2);
      $num = substr($_POST['stockid'] ,2,5);
      if ( strlen($_POST['stockid']) !== 7 || !ctype_alpha($ab) || !is_numeric($num)) {
        $correct_stockid = false;
      }
    }

    if ( $correct_stockid === true ) {

          $error = $_FILES['file_upload']['error'];

          if($error !== 0) {
              $_SESSION["message"] = $upload_errors[$error];
          } else {
              if ($_FILES['file_upload']['type'] !== 'application/pdf') {
                  $_SESSION["message"] = 'Selezionare un Documento PDF.';
              } else {
                  $tmp_file = $_FILES['file_upload']['tmp_name'];
                  $upload_dir = $logged_user->main_share.$data_richiesta.DS;

                  $day_contents = $share->dir($upload_dir);
                  $logged_user->set_upload_filename($stockid, $targa, $day_contents);

                  $share->put($tmp_file, $upload_dir.$logged_user->upload_file);
                  $_SESSION["message"] = "Documento inviato.";
              }
          }
          // $_SESSION["message"] .= ' Richiesta POST eseguita.';
    } else {
      $_SESSION["message"] .= ( isset($_SESSION["message"]) ) ? '' : 'Richiesta POST non eseguita.' ;
    }
}
?>

<?php include("../private/layouts/header.php"); ?>
<div id="main">
  <div id="navigation">
    <?php include("../private/layouts/logout_link.php"); ?>
    <a href="admin">&laquo; Torna indietro</a><br><br>
    <fieldset>
      <legend>Invia Documento:</legend>
      <form name="manda_doc" <?php echo ($logged_user->is_branch === true) ? 'onsubmit="return validateBR()"' : 'onsubmit="return validateAG()"'; ?> action=?d="<?php echo base64_encode($data_richiesta); ?>" enctype="multipart/form-data" method="POST">
      <table><tr><td>
        <input type="hidden" name="MAX_FILE_SIZE" value="80000000" />
        <input style="width:95%" type="file" name="file_upload"/>
      </td></tr></table><br>
      <?php
          if ($logged_user->is_branch === true) {
            echo '<input type="text" id="jsstockid" name="stockid" value="'.$stockid.'"><br><br>';
          }
          echo '<input type="text" name="targa" id="jstarga" value="'.$targa.'"><br><br>';
      ?>
      <input type="submit" name="submit" value="Invia">
      </form>
    </fieldset>
    <ul class="pages">
     		<li><a href="?d=<?php echo base64_encode($data_richiesta); ?>">Ricarica</a></li>
    </ul>
  </div>
  <div id="page">
    <?php echo $session->message(); ?>
    <h2>Documentazione <?php echo '<i><small>'.$data_normale.'</small></i>'; ?></h2>
    <table id="giornata">
      <tr><th>Data</th></tr>
      <?php
          rsort($main_contents);
          foreach ($main_contents as $item) {
              if ( $item->getName() === $data_richiesta ) {
                  echo '<tr style="background: #D4E6F4">' ;
              } else {
                  echo '<tr>' ;
              }
              echo '<td><a href="?d='.base64_encode($item->getName()).'">'.Time::inverse_date($item->getName()).'</a></td>';
              echo '<tr>';
          }
      ?>
    </table>
      <?php
          echo '<table id="filedim">';
          echo '<tr><th>File</th><th>Dimensioni</th></tr>';
          $day_contents = $share->dir($logged_user->main_share.$data_richiesta.DS);
          foreach ($day_contents as $item) {
            // SHOWS ONLY .pdf EXTENSIONS
            if (strpos($item->getName(), '.pdf') !== false) {
              echo '<tr>';
              echo '<td><a target="_blank" href="download?foo='.base64_encode($item->getName()).'&bar='.base64_encode($data_richiesta).'">'.$item->getName().'</a></td>';
              // CONVERTS TO KB
              $size = ($item->getSize() / 1024 );
              $size = number_format((float)$size, 1, '.', '');
              echo '<td>'.$size.' KB</td>';
              echo '</tr>';
            }
          }
          echo '</table>';
      ?>
  </div>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php include("../private/layouts/footer.php"); ?>
<?php
    if ( $correct_stockid === false ) {
        // this if statement will rarely evaluate as validation is also done client side thru main.js
        echo '<script>alert("Lo Stock ID dev\'essere composto da 2 lettere seguiti da 5 numeri.");</script>';
    }
    // if ( $correct_date === false ) {
    //     echo '<script>alert("La data '. $data_normale . ' non è presente nel sistema.");</script>';
    // }
?>
