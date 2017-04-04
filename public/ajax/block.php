<?php
require_once("../../private/initialize.php");
sleep(1);

// print_r($_POST);
// echo $_POST["StockID"];

$error_message = 'ERRORE:';
foreach ($_POST as $key => $value) {
  if ($key === 'StockID' && strlen($value) > 8) {
      $error_message .= ' Lo StockID non deve superare gli 8 caratteri.';
  }
  if ($key === 'Targa' && strlen($value) > 8) {
      $error_message .= ' La Targa non deve superare gli 8 caratteri.';
  }
}
if ($error_message !== 'ERRORE:') {
    echo $error_message; exit;
}

$array = $_POST;
$array += array('inserito' => strftime("%Y-%m-%d"));
$table='registro_sospensioni';

$sql_table = new FpTable();
$result_array = $sql_table->insert_into($array, $table);

if (!empty($result_array)) {
  echo 'Pratica Bloccata: <'.strtoupper($array["StockID"]).'>';
} else {
  echo '';
}

?>
