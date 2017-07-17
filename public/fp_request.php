<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }
$logged_user = User::find_by_id($_SESSION["user_id"]);
if ( !$logged_user->dept_id === "11" || !$logged_user->dept_id === "4" || !$logged_user->su === "1" ) {
	 redirect("admin");
}

$allowed_pages = array('atti','merchants','warm','all', 'sospensioni');
$valid_page = $validation->check_for_allowed_get_values('page', $allowed_pages);

if (!$_GET["page"] || !$valid_page) { redirect("admin"); }

$search_menu = new Search();

$filename = 'export';
$action = 'ajax/fp_response';
if ($_GET["page"] === 'atti') {
		$requested_fields = array('targa', 'stockId', 'denominazione', 'dataSP', 'data_trascrizione', 'evento', 'dataEvento', 'note');
		foreach ($requested_fields as $field) {
			$search_menu->text_input($field);
		}
		$table = 'atti_ricevuti';
		$filename = 'Atti_ricevuti_'.strftime("%F_%H%M");
}
if ($_GET["page"] === 'merchants') {
		$requested_fields = array('Partita_IVA', 'Denominazione_Cliente', 'destinatario_sped','indirizzo_sped', 'cap_sped','citta_sped','provincia_sped','telefono','email');
		foreach ($requested_fields as $field) {
			$search_menu->text_input($field);
		}
		$table = 'indirizzi_merchant_it';
		$filename = 'Indirizzi_merchant_'.strftime("%F_%H%M");
}
if ($_GET["page"] === 'warm') {
		$requested_fields = array( 'Targa', 'StockID', 'Telaio', 'DataIncarico', 'Denominazione_Cliente', 'Data_ric_atto', 'CDP', 'DataCDP', 'CdC', 'DataCdC', 'Targhe', 'DataTarghe', 'Chiavi', 'DataChiavi', 'Busta', 'DataBusta', 'CMR', 'DataCMR', 'TT2120', '`ACI-PRA`', 'CCIAA', 'DocRiconoscim', 'Privacy', 'TT2120cs', '`ACI-PRAcs`', 'Contratto', 'Data_Pagamento', 'Tipo', 'EventoConclusivo', 'Data_Evento', 'Tracking_code', 'DataSpedizione', 'DataConsegna', 'Consegna_a_mano', 'NoteAuto1', '`E-Mail`');
		$search_menu->double_text_input($requested_fields);
		$table = 'warm_vehicles';
		$filename = 'Warm_vehicles_'.strftime("%F_%H%M");
}
if ($_GET["page"] === 'all') {
		if ($logged_user->full_name === "Emilia Monita" ) {
			$requested_fields = array('Targa', 'StockID', 'EventoConclusivo', 'DataCdP', 'DataCdC');
		} else {
			$requested_fields = array('Targa', 'StockID', 'EventoConclusivo', 'Data_Evento', 'TT2120', 'DataCdc', 'DataTarghe', 'DataSpedizione', 'DataConsegna', 'Consegna_a_mano');
		}
		foreach ($requested_fields as $field) {
			$search_menu->text_input($field);
		}
		$table = 'all_vehicles';
		$filename = 'Finproget_rawdata';
}
if ($_GET["page"] === 'sospensioni') {
		$requested_fields = array('Targa', 'StockID', 'Note', 'Inserito', 'Aggiornato', 'Rilasciato');
		foreach ($requested_fields as $field) {
			$search_menu->text_input($field);
		}
		$table = 'registro_sospensioni';
		$block_menu = new Block();
		$blocks = array('Targa', 'StockID', 'Note');
		foreach ($blocks as $block) {
			$block_menu->text_input($block);
		}
		$action = 'ajax/fp_response_sosp';
}

$sql_table = new FpTable();
$column_names = $sql_table->show_fields_from($requested_fields, $table);

?>
<?php include("../private/layouts/header.php"); ?>
<div id="main">
 <div id="navigation">
  <?php include("../private/layouts/logout_link.php"); ?>
	<a href="admin">&laquo; Torna indietro</a><br><br>
	<?php echo (isset($block_menu)) ? $block_menu->rendered_form('ajax/block').'<br>' : '' ; ?>
	<?php echo $search_menu->rendered_form($action); ?>
  <ul class="pages">
 		<li><a href="">Ripristina</a></li>
		<li><a id="export_link" href="">Esporta in Excel</a></li>
  </ul>
 </div>
 <div id="page">
	<h2 id="h2title"><?php echo String::ucfirst_rmunderscores($table); ?></h2>
	<div id="table_div">
		<table id="fp_table">
			<tbody id="fp_table_body">
				<tr id="table_header_row">
				<?php
						foreach ($column_names as $column_name) {
						  echo '<th>'.String::ucfirst_rmunderscores($column_name).'</th>';
						}
				?>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
		// json_encode($requested_fields, JSON_FORCE_OBJECT);
		echo '<textarea style="display:none;" name="select">'.json_encode($requested_fields).'</textarea>';
		echo '<textarea style="display:none;" name="from">'.$table.'</textarea>';
	?>
 </div>
</div>
<script src="js/fp_ajax.js"></script>
<?php include("../private/layouts/footer.php"); ?>
