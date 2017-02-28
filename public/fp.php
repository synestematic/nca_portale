<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ( !$logged_user->dept_id === "11" || !$logged_user->dept_id === "4" || !$logged_user->su === "1" ) {
	 redirect("admin.php");
}
$allowed_pages = array('atti','merchants','warm','all');
$valid_page = $validation->check_for_allowed_get_values('page', $allowed_pages);

if (!$_GET["page"] || !$valid_page) { redirect("admin.php"); }

$requested_fields = array('*');
if ($_GET["page"] === 'atti') {
		$result_title = 'Atti Ricevuti';
		$table = 'atti_ricevuti';
}
if ($_GET["page"] === 'merchants') {
		$result_title = 'Merchants';
		$table = 'indirizzi_merchant_it';
}
if ($_GET["page"] === 'warm') {
		$result_title = 'Warm Vehicles';
		$table = 'warm_vehicles';
}
if ($_GET["page"] === 'all') {
		$result_title = 'All Vehicles';
		$table = 'all_vehicles';
		$requested_fields = ( $logged_user->full_name === "Emilia Monita" ) ? array('Targa', 'StockID', 'EventoConclusivo', 'DataCdP', 'DataCdC') : array('Targa', 'StockID', 'EventoConclusivo', 'Data_Evento', 'TT2120', 'DataCdc', 'DataTarghe') ;
}

$sql_table = new FpTable();
$column_names = $sql_table->show_fields_from($requested_fields, $table);
$result_array = $sql_table->select_from($requested_fields, $table);

?>
<?php include("../private/layouts/header.php"); ?>
<div id="main">
 <div id="navigation">
  <?php include("../private/layouts/logout_link.php"); ?>
	<a href="admin.php">&laquo; Torna indietro</a><br>
  <ul class="pages">
 		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Ricarica</a></li>
    <li><a target="_blank" href="export.php?a=<?php echo (isset($result_array[0])) ? base64_encode($result_array[0]->last_sql) : '" onclick="return validateExport()' ; ?>">Esporta in Excel</a></li>
	</ul>
 </div>
 <div id="page">
  <h2><?php echo count($result_array).' '.$result_title; ?></h2>
	<table id="tavol">
		<?php
		echo '<tr>';
		foreach ($column_names as $column_name) {
		  echo '<th style="width:40px">'.String::ucfirst_rmunderscores($column_name).'</th>';
		}
		echo '</tr>';
		foreach ($result_array as $result) {
			echo '<tr>';
			foreach ($result->sql_fields as $value) {
				echo '<td style="text-align: center;">' ;
				$value = Time::check_for_date($value);
				echo $value ;
				echo '</td>' ;
			}
			echo '</tr>';
		}
		?>
	</table><br><br><br><br><br><br>
 </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
