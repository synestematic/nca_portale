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

$filename = $table.'_'.strftime("%F_%H%M");
if ( $table === 'all_vehicles' ) {
    $filename = 'finproget_rawdata_'.strftime("%F_%H%M");
}

$complete_path = $logged_user->tmp_dir.$filename.'.xls';
$contents = $sql_table->make_excel($result_array[0]->last_sql);

if ($openfile = fopen($complete_path, 'w')) {
    fwrite($openfile, $contents);
    fclose($openfile);
    echo $complete_path;
}

?>
