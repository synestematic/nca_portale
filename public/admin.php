<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
?>
<?php include("../private/layouts/header.php"); ?>
    <div id="main">
      <div id="navigation">
      <?php include("../private/layouts/logout_link.php"); ?>
       <ul class="pages">
       </ul>
      </div>
      <div id="page">
        <?php  //echo '<pre>'; print_r($_SESSION); echo '</pre>'; echo "<br>";
        // echo $logged_user->main_share;
        ?>
        <h2>Area di Amministrazione</h2>
        <p>
          <?php
              echo $session->message();
              echo form_errors($errors);
          ?>
        </p>
        <p>Scegli una opzione:</p>
        <ul>
          <?php
          echo ($logged_user->admin || $logged_user->dept_id === "11" ) ? '<li><a href="chiamate.php">Chiamate Natterbox</a></li><br>' : '';
          echo ($logged_user->dept_id === "11" || $logged_user->dept_id === "4" || $logged_user->su === "1") ? '<li><a href="fp_merchants.php">Ricerca Merchants</a></li><br>' : '';
          echo ($logged_user->dept_id === "11" || $logged_user->dept_id === "4" || $logged_user->su === "1") ? '<li><a href="fp_vehicles.php">Report Veicoli Finproget</a></li><br>' : '';

          // echo ($logged_user->dept_id === "6" || $logged_user->dept_id === "9" || $logged_user->su === "1") ? '<li><a href="upload.php?day='.strftime("%Y-%m-%d").'">Gestione Documenti</a></li><br>' : '';
          echo ($logged_user->dept_id === "6" || $logged_user->dept_id === "9" || $logged_user->su === "1") ? '<li><a href="upload.php?d='.base64_encode(strftime("%Y-%m-%d")).'">Gestione Documenti</a></li><br>' : '';

          echo ($logged_user->su) ? '<li><a href="users.php">Gestione Utenti</a></li><br>' : '';
          echo ($logged_user->su) ? '<li><a href="edit_user.php?id='.urlencode($_SESSION["user_id"]).'">Impostazioni di profilo</a></li>' : '' ;
           ?>
        </ul>
      </div>
    </divo>
<?php include("../private/layouts/footer.php"); ?>
