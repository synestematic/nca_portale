<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->admin == 0) { redirect("index.php"); }
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
          <?php echo form_errors($errors); ?>
        </p>
        <p>Scegli una opzione:</p>
        <ul>
          <?php
          echo ($logged_user->dept_id == 11) ? '<li><a href="fp.php">DB Finproget</a></li><br>' : '';
          echo ($logged_user->dept_id) ? '<li><a href="chiamate.php">Visualizza le chiamate di Natterbox</a></li><br>' : '';
          echo ($logged_user->su) ? '<li><a href="users.php">Gestisci gli altri utenti</a></li><br>' : '';
           ?>
          <li><a href="edit_user.php?id=<?php echo urlencode($_SESSION["user_id"]); ?>">Modifica la tua utenza</a></li>
        </ul>
      </div>
    </divo>
<?php include("../private/layouts/footer.php"); ?>
