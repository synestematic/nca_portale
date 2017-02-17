<?php
setlocale(LC_TIME, 'it_IT');
setlocale(LC_ALL, 'it_IT');

class Veicolo {

	public $sql_fields = array();
	public $last_sql = "";

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
			// ALL THIS IS GONNA GET DONE TWICE
				while ( $field = mysqli_fetch_field($query_result) ) {
						$field_array[] = $field->name;
				}
		    for ( $j=0 ; $j < mysqli_num_fields($query_result) ; $j++ ) {
					$sql_data[ $field_array[$j] ] =  $row[$j];
		    }
				$object_array[] = self::instantiate($sql_data, $sql);
		}
		return $object_array;
	}

		public static function find_columns_from_table($field_array, $table) {
			$string = '';
			foreach ($field_array as $field) {
				$string .= $field . ' , ' ;
			}
			$string = substr($string, 0, -2);
			$object_array = self::find_by_sql("SELECT $string FROM $table");
			return $object_array;
		}

		public static function return_column_names($field_array, $table) {
			$veicolo_object = self::find_columns_from_table($field_array, $table.' LIMIT 1');
			return array_keys($veicolo_object[0]->sql_fields);
		}

}

?>
