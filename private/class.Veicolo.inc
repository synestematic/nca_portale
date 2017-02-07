<?php
setlocale(LC_TIME, 'it_IT');
setlocale(LC_ALL, 'it_IT');

class Veicolo {

	public $sql_fields = array();
	public $last_sql = "";
	// public $status ="";
	// public $next_step ="";

	private static function instantiate($sql_data, $requested_query) {
		$object = new self;
		$keys = array_keys($sql_data);
		foreach ( $keys as $key ) {
			$object->sql_fields[$key] = $sql_data[$key];
		}
		$object->last_sql = $requested_query ;
		return $object;
	}

	public static function find_by_sql($sql="") {
		global $fp_db;
		$fp_db->open_connection();
		$query_result = $fp_db->query($sql);
		while ( $row = mysqli_fetch_row($query_result) ) {
			// ALL THIS I SOGNNA GET DONE TWICE
				while ( $field = mysqli_fetch_field($query_result) ) {
						$field_array[] = $field->name;
				}
		    for ( $j=0 ; $j < mysqli_num_fields($query_result) ; $j++ ) {
					$sql_data[ $field_array[$j] ] =  $row[$j];
		    }
				$object_array[] = self::instantiate($sql_data, $sql);
				// echo mysqli_num_fields($query_result) ;
				// echo '<pre>';
				// print_r($sql_data);
				// echo '</pre>';
		}
		// echo '<pre>';
		// print_r($object_array);
		// echo '</pre>';
		return $object_array;
	}

		public static function return_sql_fields() {
			$veicolo_object = self::find_by_sql('SELECT * FROM warm_vehicles LIMIT 1');
			return array_keys($veicolo_object[0]->sql_fields);
		}

}

// Targa
// StockID
// Telaio
// DataIncarico
// Denominazione_cliente
// Data_ric_atto
// CDP
// DataCdP
// CdC
// DataCdC
// Targhe
// DataTarghe
// Chiavi
// DataChiavi
// Busta
// DataBusta
// CMR
// DataCmr
// TT2120
// ACI-PRA
// CCIAA
// DocRiconoscim
// Privacy
// TT2120cs
// ACI-PRAcs
// Contratto
// Data_Pagamento
// Tipo
// EventoConclusivo
// Data_Evento
// Tracking_code
// Consegna_a_mano
// NoteAuto1
// e-mail

?>
