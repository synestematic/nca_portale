<?php
// sleep(2);
require_once("../private/initialize.php");

if ( !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
     $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest' ) {
       echo 'Richiesta non riconosciuta.'; exit;
}
if (!$session->is_logged_in()) { redirect("login.php"); }
$logged_user = User::find_by_id($_SESSION["user_id"]);

$requested_fields = json_decode($_POST["select"]);
$table = $_POST["from"];
$wheres = json_decode($_POST["where"]);

$sql_table = new FpTable();
$result_array = $sql_table->select_from_where($requested_fields, $table, $wheres);

if (!empty($result_array)) {
  foreach ($result_array as $result) {
    echo '<tr class="row">';
    foreach ($result->sql_fields as $key => $value) {
      $id = ($key === 'id') ? 'id="'.$value.'"' : '' ;
      echo '<td '.$id.'style="width: 150px; text-align: center;">';
      $value = Time::check_for_date($value);
      echo $value ;
      echo '</td>' ;
    }
    echo '</tr>';
  }
} else {
  // echo '<tr class="row">=========================</tr>';
  echo '';
}

?>
