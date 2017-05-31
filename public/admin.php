<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }
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
        <h2>Benvenuto!</h2>
        <p>
          <?php echo $session->message(); ?>
        </p>
        <p>Scegli un'opzione:</p>
        <ul>
          <?php
            echo ($logged_user->dept === "ops" || $logged_user->su === "1" || $logged_user->dept === "bc" ) ? '<li><a href="chiamate">Chiamate Natterbox</a></li><br><br>' : '';

            echo ($logged_user->su || $logged_user->full_name === "Luca Shawawreh" || $logged_user->su || $logged_user->full_name === "Davide Tarizzo" || $logged_user->full_name === "Mirela Ancuta" || $logged_user->full_name === "Alessio Calenda" || $logged_user->full_name === "Emilia Monita" || $logged_user->full_name === "Mattia Ruffoni") ? '<li><a href="fp_request?page=all"><b>FinProget:</b> Report All Vehicles</a></li><br>' : '';

            echo ($logged_user->dept === "ops" || $logged_user->dept === "bi" || $logged_user->su === "1") ? '<li><a href="fp_request?page=warm"><b>FinProget:</b> Report Warm Vehicles</a></li><br>' : '';

            echo ($logged_user->dept === "ops" || $logged_user->dept === "bi" || $logged_user->su === "1") ? '<li><a href="fp_request?page=merchants"><b>FinProget:</b> Indirizzi Merchants</a></li><br>' : '';

            echo ($logged_user->dept === "ops" || $logged_user->dept === "bi" || $logged_user->su === "1") ? '<li><a href="fp_request?page=atti"><b>FinProget:</b> Atti Ricevuti</a></li><br><br>' : '';

            echo ($logged_user->su === "1" || $logged_user->full_name === "Alessio Calenda" || $logged_user->full_name === "Emilia Monita" || $logged_user->full_name === "Mattia Ruffoni") ? '<li><a href="fp_request?page=sospensioni"><b>FinProget:</b> Registro Sospensioni Pratiche</a></li><br>' : '';

            echo '<br>';

            echo ($logged_user->dept === "agenzia" || $logged_user->dept === "filiale" || $logged_user->su === "1") ? '<li><a href="upload?d='.base64_encode(strftime("%Y-%m-%d")).'">Gestione Documenti</a></li><br>' : '';
            echo '<br>';

            echo '<br>';

            echo ($logged_user->su) ? '<li><a href="users">Gestione Utenti</a></li><br>' : '';

            echo ($logged_user->su) ? '<li><a href="edit_user?id='.urlencode($_SESSION["user_id"]).'">Impostazioni di profilo</a></li><br>' : '' ;
          ?>
        </ul>
      </div>
    </div>
<?php include("../private/layouts/footer.php"); ?>
