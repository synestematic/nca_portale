<?php
require_once("../private/initialize.php");
// perche in questo ordine? (inverso deosnt work)
use Icewind\SMB\Server;
require('/var/www/html/private/SMB-master/vendor/autoload.php');

if (!$session->is_logged_in()) { redirect("login.php"); }
$logged_user = User::find_by_id($_SESSION["user_id"]);
if (!$logged_user->is_branch && !$logged_user->is_agency) { redirect("admin.php"); }

$giorno = strftime("%d");
$mese = strftime("%m");
$anno = strftime("%Y");
$correct_stockid = true;
$correct_date = true;
$targa = 'Targa';
$stockid = 'Stock ID';

if ( isset($_POST['submit']) ) {

    $targa = strtoupper($_POST['targa']);
    $stockid = strtoupper($_POST['stockid']);

    if ($logged_user->is_branch === true) {
      // this if statement will rarely get used as validation is also done client side thru main.js
      $ab = substr($_POST['stockid'] ,0,2);
      $num = substr($_POST['stockid'] ,2,5);

      if ( strlen($_POST['stockid']) !== 7 || !ctype_alpha($ab) || !is_numeric($num)) {
        $correct_stockid = false;
      }
    }
    if ( $correct_stockid === true ) {
        $correct_date = false;

        $nas = new Server(NAS_SERVER, NAS_USER, NAS_PASS);
        $share = $nas->getShare(NAS_SHARE);
        $main_contents = $share->dir($logged_user->main_share);
        foreach ($main_contents as $item) {
            if ($item->getName() === $_POST['anno'] . '-' . $_POST['mese']. '-' . $_POST['giorno'] ) {
              $correct_date = true;
            }
        }
    }

    if ( $correct_stockid === true && $correct_date === true ) {

          $giorno = $_POST['giorno'];
          $mese = $_POST['mese'];
          $anno = $_POST['anno'];
          $error = $_FILES['file_upload']['error'];

          if($error !== 0) {
              $_SESSION["message"] = $upload_errors[$error];
          } else {
              if ($_FILES['file_upload']['type'] !== 'application/pdf') {
                  $_SESSION["message"] = 'Selezionare un Documento PDF.';
              } else {
                  $tmp_file = $_FILES['file_upload']['tmp_name'];
                  $upload_dir = $logged_user->main_share.$anno.'-'.$mese.'-'.$giorno.DS;

                  $logged_user->set_upload_file($stockid, $targa);
                  $share->put($tmp_file, $upload_dir.$logged_user->upload_file);
                  // You will probably want to first use file_exists() to make sure
                  // there isn't already a file by the same name.
                  $_SESSION["message"] = "Documento inviato.";
              }
          }
          $_SESSION["message"] .= ' Richiesta POST eseguita.';
    } else {
      $_SESSION["message"] .= ( isset($_SESSION["message"]) ) ? '' : 'Richiesta POST non eseguita.' ;
    }
}
?>

<?php include("../private/layouts/header.php"); ?>
<div id="main">
  <div id="navigation">
    <?php include("../private/layouts/logout_link.php"); ?>
    <a href="admin.php">&laquo; Torna indietro</a><br><br>
    <fieldset>
      <legend>Invia Documento:</legend>
      <form name="manda_doc" <?php echo ($logged_user->is_branch === true) ? 'onsubmit="return validateBR()"' : 'onsubmit="return validateAG()"'; ?> action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="POST">
      <table><tr><td>
        <input type="hidden" name="MAX_FILE_SIZE" value="80000000" />
        <input style="width:95%" type="file" name="file_upload"/>
      </td></tr></table>
      <?php
          if ($logged_user->is_branch === true) {
            echo '<br><input type="text" id="jsstockid" name="stockid" value="'.$stockid.'"><br><br>';
          }
          echo '<input type="text" name="targa" id="jstarga" value="'.$targa.'"><br><br>';
          echo '<table><tr><td>';
          $yo = new DropdownDays();
    			$yo->selected = $giorno;
    			$yo->id = "data";
    			$yo->name = "giorno";
    			$yo->menu();
          echo '</td><td>';
          $yo = new DropdownMonths();
    			$yo->selected = $mese;
    			$yo->id = "data";
    			$yo->name = "mese";
    			$yo->menu();
          echo '</td><td>';
          $yo = new DropdownYears();
    			$yo->selected = $anno;
    			$yo->id = "anno";
    			$yo->name = "anno";
      		$yo->menu();
          echo '</td></tr></table><br>';
      ?>
      <input type="submit" name="submit" value="Invia">
      </form>
    </fieldset>
    <ul class="pages">
     		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Ricarica</a></li>
    </ul>
  </div>
  <div id="page">
    <?php echo $session->message(); ?>
    <?php echo form_errors($errors); ?>
    <?php
    // if (isset($_FILES['file_upload'])) {
    //   echo '<pre>';
    //   print_r($_FILES['file_upload']);
    //   print_r($_POST);
    //   echo '</pre>';
    // }
    ?>
    <h2>Documentazione <?php echo htmlentities($logged_user->full_name); ?>:</h2>
    <iframe src="documentation.php" width="98%" height="98%"></iframe>
  </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
<?php
    if ( $correct_stockid === false ) {
        // this if statement will rarely get used as validation is also done client side thru main.js
        echo '<script>alert("Lo Stock ID dev\'essere composto da 2 lettere seguiti da 5 numeri.");</script>';
    }
    if ( $correct_date === false ) {
        echo '<script>alert("La data '. $_POST['giorno'] . ' / ' . $_POST['mese']. ' / ' . $_POST['anno']. ' non è presente nel sistema.");</script>';
    }
?>
