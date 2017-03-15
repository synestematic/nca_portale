<?php
require_once("../private/initialize.php");
// print_r($_POST);
if ( !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
     $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest' ) {
       echo 'Richiesta non riconosciuta.'; exit;
}
if (!$session->is_logged_in()) { exit ; }
$logged_user = User::find_by_id($_SESSION["user_id"]);

$requested_fields = json_decode($_POST["select"]);
$table = $_POST["from"];
$wheres = json_decode($_POST["where"]);

$sql_table = new FpTable();
$result_array = $sql_table->select_from_where($requested_fields, $table, $wheres);

if (!empty($result_array)) {
    foreach ($result_array as $result) {
        echo '<tr id="fp_table_data_row">';
        foreach ($result->sql_fields as $key => $value) {
            $disable = 'disabled';
            if ($key === 'StockID') {
                $stock_id = $value;
            }
            echo '<td>';
            if ($key === 'Rilasciato' && $value === NULL) {
              echo '<input id="sblock_input_'.$stock_id.'" class="sblock_input_class" type="text" name="" value="-- Inserire Data --">';
              $disable = '';
            }
            if ($key !== 'Note') {
                echo strtoupper(Time::check_for_date($value)) ;
            } else {
                echo Time::check_for_date($value) ;
            }
            echo '</td>' ;
        }
        echo '<td style="width:10px; padding:2px"><input class="sblock_button_class" id="sblock_button_'.$stock_id.'" type="button" value="Sblocca" '.$disable.'></td>';
        echo '</tr>';
    }
} else {
    echo '';
}
// sleep(1);

?>
