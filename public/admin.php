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
        <?php  //echo '<pre>'; print_r($_SESSION); echo '</pre>'; echo "<br>"; echo $logged_user->table; ?>
        <h2>Area di Amministrazione</h2>
        <p>
          <?php echo $session->message(); ?>
          <?php //echo $logged_user->dept_id; ?>
          <?php echo form_errors($errors); ?>
        </p>
        <p>Scegli una opzione:</p>
        <ul>
          <?php
          // LINKS to be displayed //
          echo ($logged_user->admin) ? '<li><a href="chiamate.php">Visualizza le chiamate di Natterbox</a></li><br>' : '';
          echo ($logged_user->dept_id === "11" || $logged_user->su === "1") ? '<li><a href="piva.php">Ricerca PARTITA IVA</a></li><br>' : '';
          echo ($logged_user->dept_id === "6" || $logged_user->dept_id === "9" || $logged_user->su === "1") ? '<li><a href="upload.php">Carica un Documento</a></li><br>' : '';
          echo ($logged_user->su) ? '<li><a href="users.php">Gestisci gli altri utenti</a></li><br>' : '';
          echo '<li><a href="edit_user.php?id='.urlencode($_SESSION["user_id"]).'">Modifica la tua utenza</a></li>';
           ?>
        </ul>
      </div>
    </divo>
<?php include("../private/layouts/footer.php"); ?>
