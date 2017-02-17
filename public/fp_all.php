<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ( !$logged_user->dept_id === "11" || !$logged_user->dept_id === "4" || !$logged_user->su === "1" ) {
	 redirect("admin.php");
 }

?>
<?php include("../private/layouts/header.php"); ?>
<div id="main">
 <div id="navigation">
  <?php include("../private/layouts/logout_link.php"); ?>
	<a href="admin.php">&laquo; Torna indietro</a><br>
  <ul class="pages">
		<?php
			$requested_fields = array('Targa', 'StockID', 'EventoConclusivo', 'Data_Evento', 'TT2120', 'DataCdc');
			$veicolo_set = Veicolo::find_columns_from_table($requested_fields, 'all_vehicles');
		?>
 		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Ricarica</a></li>
        <li><a target="_blank" href="export.php?a=<?php echo (isset($veicolo_set[0])) ? base64_encode($veicolo_set[0]->last_sql) : '" onclick="return validateExport()' ; ?>">Esporta in Excel</a></li>
	</ul>
 </div>
 <div id="page">
  <h2>Veicoli: <?php echo count($veicolo_set); ?> </h2>
   <table id="tavol">
			<?php
			echo '<tr>';
			$column_names = Veicolo::return_column_names($requested_fields, 'all_vehicles');
			foreach ($column_names as $column_name) {
			  echo '<th style="width:40px">'.$column_name.'</th>';
			}
			echo '</tr>';
			?>
			<?php
			foreach ($veicolo_set as $veicolo) {
				echo '<tr>';
				foreach ($veicolo->sql_fields as $value) {
					echo '<td style="text-align: center;">' ;
					$value = Time::check_for_date($value);
					echo $value ;
					echo '</td>' ;
				}
				echo '</tr>';
			}
			?>
   </table>
	 <?php //echo $veicolo->last_sql; ?>
  <br><br><br><br><br><br>
 </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
