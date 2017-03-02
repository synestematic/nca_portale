<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }
$logged_user = User::find_by_id($_SESSION["user_id"]);

// if ($logged_user->dept === "agenzia") {
//   Time::if_sunday_go_to('admin'); //redirects forever...
// }

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
            echo ($logged_user->admin || $logged_user->dept === "ops" ) ? '<li><a href="chiamate">Chiamate Natterbox</a></li><br>' : '';
            echo ($logged_user->su || $logged_user->full_name === "Piero Mezzasalma" || $logged_user->full_name === "Alessio Calenda" || $logged_user->full_name === "Emilia Monita" ) ? '<li><a href="fp_all" onclick="return trafficWarning();">Report Finproget ALL VEHICLES</a></li><br>' : '';
            echo ($logged_user->dept === "ops" || $logged_user->dept === "bi" || $logged_user->su === "1" ) ? '<li><a href="fp_warm" onclick="return trafficWarning();">Report Finproget WARM VEHICLES</a></li><br>' : '';
            echo ($logged_user->dept === "ops" || $logged_user->dept === "bi" || $logged_user->su === "1" ) ? '<li><a href="fp_atti">Report Finproget ATTI RICEVUTI</a></li><br>' : '';
            echo ($logged_user->dept === "ops" || $logged_user->dept === "bi" || $logged_user->su === "1" ) ? '<li><a href="fp_merchants">Ricerca Merchants</a></li><br>' : '';
            echo ($logged_user->dept === "agenzia" || $logged_user->dept_id === "9" || $logged_user->su === "1" ) ? '<li><a href="upload?d='.base64_encode(strftime("%Y-%m-%d")).'">Gestione Documenti</a></li><br>' : '';
            echo '<br>';
            echo ($logged_user->su) ? '<li><a href="segnalazione">Segnala una problematica</a></li><br>' : '';
            echo ($logged_user->su) ? '<li><a href="pogolsa">Fai la Pogolsa</a></li><br>' : '';
            echo '<br>';
            echo ($logged_user->su  ) ? '<li><a href="users">Gestione Utenti</a></li><br>' : '';
            echo ($logged_user->su) ? '<li><a href="edit_user?id='.urlencode($_SESSION["user_id"]).'">Impostazioni di profilo</a></li><br>' : '' ;
            echo ($logged_user->su === "1") ? '<li><a href="fp_request?page=warm" onclick="return trafficWarning();">WARM</a></li><br>' : '';
            echo ($logged_user->su === "1") ? '<li><a href="fp_request?page=all" onclick="return trafficWarning();">ALL</a></li><br>' : '';
            echo ($logged_user->su === "1") ? '<li><a href="fp_request?page=atti">ATTI</a></li><br>' : '';
            echo ($logged_user->su === "1") ? '<li><a href="fp_request?page=merchants">MERCHANTS</a></li><br>' : '';
          ?>
        </ul>
      </div>
    </div>
<?php include("../private/layouts/footer.php"); ?>
