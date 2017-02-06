<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->su == 0) { redirect("index.php"); }

include("../private/layouts/header.php");

$order='id';
if (isset($_GET['order'])) {
  $order=$_GET['order'];
}

?>
<div id="main">
  <div id="navigation">
    <?php include("../private/layouts/logout_link.php"); ?>
    <a href="admin.php">&laquo; Torna indietro</a><br>
    <ul class="pages">
        <li><a href="create_user.php">Crea nuovo utente</a></li>
    </ul>
  </div>
  <div id="page">
    <?php echo $session->message();?>
    <h2>Gestisci gli altri utenti: </h2>
      <table>
        <tr>
          <th style="text-align: left; width: 200px;"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?order=full_name">UTENTE<a/></th>
          <th style="text-align: left; width: 200px;"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?order=email">E-MAIL</a></th>
          <th style="text-align: left; width: 200px;"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?order=branch_id">FILIALE</a></th>
          <th style="text-align: left; width: 200px;"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?order=dept_id">DIPARTIMENTO</a></th>
          <th colspan="2" style="text-align: left;">AZIONE</th>
        </tr>
        <?php
//        $dept_users = User::find_by_dept_id($logged_user->dept_id);
        $dept_users = User::find_all_order_by($order);
      	if ($dept_users) {
        		foreach ($dept_users as $dept_user) {
            		echo '<tr><td>'.$dept_user->full_name.'</td>' ;
                echo '<td>'.$dept_user->email.'</td>' ;
                echo '<td>'.$dept_user->branch.'</td>' ;
                echo '<td>'.$dept_user->dept.'</td>' ;
                echo '<td><a href="edit_user.php?id='.urlencode($dept_user->id).'">Modifica</a></td>';
                echo '<td>';
                echo '<a href="delete_user.php?id='.urlencode($dept_user->id).'" onclick="return confirm(\'Sei sicuro di voler eliminare '.htmlentities($dept_user->email).' ?\');">Elimina</a></td>';
            }
        }
      	?>

     </table> <br><br><br><br><br>
  </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
