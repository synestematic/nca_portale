<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->su == 0) { redirect("admin"); }

$requested_field = ( isset($_GET['rf']) ) ? $_GET['rf'] : 'full_name' ;
$existing_field = ( isset($_GET['ef']) ) ? $_GET['ef'] : '' ;
$order = ( isset($_GET['o']) ) ? $_GET['o'] : 'ASC' ;
$order = User::set_order($requested_field, $order, $existing_field);

include("../private/layouts/header.php");

?>
<div id="main">
  <div id="navigation">
    <?php include("../private/layouts/logout_link.php"); ?>
    <a href="admin">&laquo; Torna indietro</a><br>
    <ul class="pages">
        <li><a href="create_user">Crea nuovo utente</a></li>
        <li><a href="">Ricarica</a></li>
    </ul>
  </div>
  <div id="page">
    <?php
    echo $session->message();
     $dept_users = User::find_all($requested_field, $order);
    echo '<h2>Gestione '.count($dept_users).' Utenti</h2>';
    ?>
      <table id="usertable">
          <?php
          echo '<tr>';
          echo '<th style="width: 200px;"><a href="?rf=full_name&ef='.$requested_field.'&o='.$order.'">UTENTE<a/></th>';
          echo '<th style="width: 200px;"><a href="?rf=email&ef='.$requested_field.'&o='.$order.'">E-MAIL<a/></th>';
          echo '<th style="width: 200px;"><a href="?rf=branch_id&ef='.$requested_field.'&o='.$order.'">FILIALE<a/></th>';
          echo '<th style="width: 200px;"><a href="?rf=dept_id&ef='.$requested_field.'&o='.$order.'">DIPARTIMENTO<a/></th>';
          echo '<th style="width: 80px;"><a href="?rf=access&ef='.$requested_field.'&o='.$order.'">ACCESS<a/></th>';
          echo '<th style="width: 80px;"><a href="?rf=su&ef='.$requested_field.'&o='.$order.'">SUPER<a/></th>';
          echo '<th colspan="2" style="width: 50px;">AZIONE</th>';
          echo '</tr>';
        	if ($dept_users) {
              foreach ($dept_users as $dept_user) {
                  echo (strpos($dept_user->dept, 'filiale') !== false) ? '<tr bgcolor="#dddddd">' : '<tr bgcolor="#f2f2f2">';
                  echo '<td>'.$dept_user->full_name.'</td>' ;
                  echo '<td>'.$dept_user->email.'</td>' ;
                  echo '<td>'.$dept_user->branch.'</td>' ;
                  echo (strpos($dept_user->dept, 'filiale') !== false) ? '<td bgcolor="#dddddd">' : '<td bgcolor="#f2f2f2">';
                  echo ucfirst($dept_user->dept).'</td>' ;
                  echo (strpos($dept_user->access, '0') === false) ? '<td bgcolor="#dddddd">'.'Si'.'</td>' : '<td bgcolor="#f2f2f2">'.''.'</td>';
                  echo (strpos($dept_user->su, '0') === false) ? '<td bgcolor="#dddddd">'.'Si'.'</td>' : '<td bgcolor="#f2f2f2">'.''.'</td>';
                  echo '<td><a href="edit_user?id='.urlencode($dept_user->id).'">Modifica</a></td>';
                  echo '<td>';
                  echo '<a href="delete_user?id='.urlencode($dept_user->id).'" onclick="return confirm(\'Sei sicuro di voler eliminare '.htmlentities($dept_user->email).' ?\');">Elimina</a></td>';
                  echo '</tr>';
            }
        }
      	?>
     </table><br><br><br><br><br>
  </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
