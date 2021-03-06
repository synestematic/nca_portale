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
            if (
              $logged_user->su === "1" ||
              $logged_user->dept === "ops"  ||
              $logged_user->full_name === 'Cristina Pogolsa' ||
              $logged_user->full_name === 'Berkan Limon' ||
              $logged_user->full_name === 'Giuseppe Valentino'
            ) { echo '<li><a href="chiamate?dept=as">Chiamate Aftersales Help-Desk</a></li><br>'; }

            if (
              $logged_user->su === "1" ||
              $logged_user->dept === "bc"  ||
              $logged_user->full_name === 'Berkan Limon' ||
              $logged_user->full_name === 'Cristina Pogolsa' ||
              $logged_user->full_name === 'Giuseppe Valentino'
            ) { echo '<li><a href="chiamate?dept=bc">Chiamate Booking Center</a></li><br>'; }

            if (
              $logged_user->su === "1" ||
              $logged_user->full_name === 'Berkan Limon' ||
              $logged_user->full_name === 'Cristina Pogolsa' ||
              $logged_user->full_name === 'Giuseppe Valentino'
            ) { echo '<li><a href="chiamate?dept=bqa">Chiamate Branch Quality Assurance</a></li><br>'; }

            if (
              $logged_user->su === "1" ||
              $logged_user->full_name === 'Berkan Limon' ||
              $logged_user->full_name === 'Cristina Pogolsa' ||
              $logged_user->full_name === 'Giuseppe Valentino'
            ) { echo '<li><a href="chiamate?dept=bcops">Chiamate Booking Center Operations</a></li><br>'; }

            if (
              $logged_user->su === "1" ||
              $logged_user->full_name === 'Berkan Limon' ||
              $logged_user->full_name === 'Cristina Pogolsa' ||
              $logged_user->full_name === 'Giuseppe Valentino'
            ) { echo '<li><a href="chiamate?dept=zrt">Chiamate Zero-Risk Trading</a></li><br>'; }

            echo '<br>';

            if (
                $logged_user->su ||
                $logged_user->full_name === 'Berkan Limon' ||
                $logged_user->full_name === "Luca Shawawreh" ||
                $logged_user->full_name === "Mirela Ancuta" ||
                $logged_user->full_name === "Alessio Calenda" ||
                $logged_user->full_name === "Emilia Monita" ||
                $logged_user->full_name === "Valentina Pipitone" ||
                $logged_user->full_name === "Mattia Ruffoni"  ||
                $logged_user->full_name === 'Cristina Pogolsa' ||
                $logged_user->full_name === 'Giuseppe Valentino'
            ) { echo '<li><a href="fp_request?page=all"><b>FinProget:</b> Report All Vehicles</a></li><br>' ; }

            if (
                $logged_user->dept === "ops" ||
                $logged_user->full_name === 'Berkan Limon' ||
                $logged_user->dept === "bi" ||
                $logged_user->su === "1"  ||
                $logged_user->full_name === 'Cristina Pogolsa' ||
                $logged_user->full_name === 'Giuseppe Valentino'
            ) { echo '<li><a href="fp_request?page=warm"><b>FinProget:</b> Report Warm Vehicles</a></li><br>' ; }

            if (
                $logged_user->dept === "ops" ||
                $logged_user->full_name === 'Berkan Limon' ||
                $logged_user->dept === "bi" ||
                $logged_user->su === "1"  ||
                $logged_user->full_name === 'Cristina Pogolsa' ||
                $logged_user->full_name === 'Giuseppe Valentino'
            ) { echo '<li><a href="fp_request?page=merchants"><b>FinProget:</b> Indirizzi Merchants</a></li><br>' ; }

            if (
                $logged_user->dept === "ops" ||
                $logged_user->full_name === 'Berkan Limon' ||
                $logged_user->dept === "bi" ||
                $logged_user->su === "1" ||
                $logged_user->full_name === 'Cristina Pogolsa' ||
                $logged_user->full_name === 'Giuseppe Valentino'
            ) { echo '<li><a href="fp_request?page=atti"><b>FinProget:</b> Atti Ricevuti</a></li><br><br>' ; }

            if (
                $logged_user->su === "1" ||
                $logged_user->full_name === 'Berkan Limon' ||
                $logged_user->full_name === "Alessio Calenda" ||
                $logged_user->full_name === "Emilia Monita" ||
                $logged_user->full_name === "Mattia Ruffoni"  ||
                $logged_user->full_name === 'Cristina Pogolsa' ||
                $logged_user->full_name === 'Giuseppe Valentino'
             ) { echo '<li><a href="fp_request?page=sospensioni"><b>FinProget:</b> Registro Sospensioni Pratiche</a></li><br>' ; }

            echo '<br>';

            if (
                $logged_user->dept === "agenzia" ||
                $logged_user->dept === "filiale" ||
                $logged_user->su === "1"
            ) { echo '<li><a href="upload?d='.base64_encode(strftime("%Y-%m-%d")).'">Gestione Documenti</a></li>'; }

            echo '<br>';

            if (
                $logged_user->su === "1"
            ) { echo '<li><a href="users">Gestione Utenti</a></li>' ; }
          ?>
        </ul>
      </div>
    </div>
<?php include("../private/layouts/footer.php"); ?>
