<?php
require_once("../../private/initialize.php");
// sleep(1);

if (isset($_POST)) {

    $table='registro_sospensioni';
    // print_r($_POST);
    foreach ($_POST as $key => $value) {
      if ($key === 'rilasciato') {
        $set_array = array(
          'rilasciato' => $value
        );
      }
      if ($key === 'note') {
        $set_array = array(
          'note' => $value
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
        if (isset($set_array['note'])) {
            exit('Pratica Aggiornata: <'.strtoupper($_POST["StockID"]).'>');
        }
        if (isset($set_array['rilasciato'])) {
            exit('Pratica Sbloccata: <'.strtoupper($_POST["StockID"]).'>');
        }
    } else {
      exit('');
    }

}
?>
