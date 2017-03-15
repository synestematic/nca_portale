<?php
require_once("../private/initialize.php");
// sleep(1);

$table='registro_sospensioni';
// print_r($_POST);
foreach ($_POST as $key => $value) {
  if ($key === 'rilasciato') {
    $set_array = array(
      'rilasciato' => $value
    );
  }
  if ($key === 'StockID') {
    $where_array = array(
      'StockID' => $value
    );
  }
}

$sql_table = new FpTable();
$result_array = $sql_table->update_set_where($table, $set_array, $where_array);

if (!empty($result_array)) {
  echo 'Pratica Sbloccata: <'.strtoupper($_POST["StockID"]).'>';
} else {
  echo '';
}

?>
