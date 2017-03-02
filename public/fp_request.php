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

$search_menu = new Search();

if ($_GET["page"] === 'atti') {
		$requested_fields = array('targa', 'stockid', 'denominazione', 'dataSP', 'data_trascrizione', 'evento', 'dataevento', 'note');
		foreach ($requested_fields as $field) {
			$search_menu->text_input($field, '');
		}
		$table = 'atti_ricevuti';
}
if ($_GET["page"] === 'merchants') {
		$requested_fields = array('Partita_IVA', 'Denominazione_Cliente', 'destinatario_sped','indirizzo_sped', 'cap_sped','citta_sped','provincia_sped','telefono','email');
		foreach ($requested_fields as $field) {
			$search_menu->text_input($field, '');
		}
		$table = 'indirizzi_merchant_it';
}
if ($_GET["page"] === 'warm') {
		$requested_fields = array( 'Targa', 'StockID', 'Telaio', 'DataIncarico', 'Denominazione_Cliente', 'Data_ric_atto', 'CDP', 'DataCDP', 'CdC', 'DataCdC', 'Targhe', 'DataTarghe', 'Chiavi', 'DataChiavi', 'Busta', 'DataBusta', 'CMR', 'DataCMR', 'TT2120', 'CCIAA', 'DocRiconoscim', 'Privacy', 'TT2120cs', 'Contratto', 'Data_Pagamento', 'Tipo', 'EventoConclusivo', 'Data_Evento', 'Tracking_code', 'DataSpedizione', 'DataConsegna', 'Consegna_a_mano', 'NoteAuto1'); //mancano ACI-PRA ACI-PRAcs e-mail
		foreach ($requested_fields as $field) {
			$search_menu->text_input($field, '');
		}
		$table = 'warm_vehicles';
}
if ($_GET["page"] === 'all') {
		if ($logged_user->full_name === "Emilia Monita" ) {
				$requested_fields = array('Targa', 'StockID', 'EventoConclusivo', 'DataCdP', 'DataCdC');
		} else {
				$requested_fields = array('Targa', 'StockID', 'EventoConclusivo', 'Data_Evento', 'TT2120', 'DataCdc', 'DataTarghe');
		}
		foreach ($requested_fields as $field) {
			$search_menu->text_input($field, '');
		}
		$table = 'all_vehicles';
}

$sql_table = new FpTable();
$column_names = $sql_table->show_fields_from($requested_fields, $table);

?>
<?php include("../private/layouts/header.php"); ?>
<div id="main">
 <div id="navigation">
  <?php include("../private/layouts/logout_link.php"); ?>
	<a href="admin.php">&laquo; Torna indietro</a><br><br>
	<?php echo $search_menu->render_form('fp_response.php'); ?>
  <ul class="pages">
 		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Ricarica</a></li>
	</ul>
 </div>
 <div id="page">
	 <div id="title">
		 <h2><?php echo String::ucfirst_rmunderscores($table); ?></h2>
		 <h3>bella</h3>
	</div>
	<table id="tavol">
		<?php
				echo '<thead style="position:fixed; z-index: 5; padding-top: 50px">';
				echo '<tr>';
				foreach ($column_names as $column_name) {
				  echo '<th style="width:150px">'.String::ucfirst_rmunderscores($column_name).'</th>';
				}
				echo '</tr>';
				echo '</thead>';
				echo '<tbody id="tbody" style="position:absolute; z-index: 4; padding-top: 92px; width: 100%">';
				echo '<tbody>';
		?>
	</table>
	<?php
		// json_encode($requested_fields, JSON_FORCE_OBJECT);
		echo '<textarea style="display:none;" name="select">'.json_encode($requested_fields).'</textarea><br>';
		echo '<textarea style="display:none;" name="from">'.$table.'</textarea><br>';
		echo '<br><br><br><br><br><br><br><br>';
	?>
<br><br><br><br><br><br><br><br><br>
 </div>
 <br><br><br><br><br><br><br><br><br>
</div>
<br><br><br><br><br><br><br><br><br>
<script src="js/fp_ajax.js"></script>
<?php include("../private/layouts/footer.php"); ?>
