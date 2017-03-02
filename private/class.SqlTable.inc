<?php
setlocale(LC_TIME, 'it_IT');
setlocale(LC_ALL, 'it_IT');

class SqlTable {

	public $sql_fields = array();
	public $last_sql = "";
	private $sql_limit = '';
	protected $wheres = '';
	protected $database;

	private static function instantiate($sql_data, $requested_query) {
		$object = new self;
		$keys = array_keys($sql_data);
		foreach ( $keys as $key ) {
			$object->sql_fields[$key] = $sql_data[$key];
		}
		$object->last_sql = $requested_query ;
		return $object;
	}

	public function find_by_sql($sql="") {
		// global $local_db;
		// $local_db->open_connection();
		$query_result = $this->database->query($sql);
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
		if (isset($object_array)) { return $object_array ;}
	}

	public function show_fields_from($field_array, $table) {
		$temp = $this->sql_limit;
		$this->sql_limit = 'LIMIT 1';
		$object_array = $this->select_from($field_array, $table);
		$this->sql_limit = $temp;
		return array_keys($object_array[0]->sql_fields);
	}

	public function select_from($field_array, $table) {
		// global $local_db;
		$table = $this->database->escape_string($table);
		$field_string = '';
		foreach ($field_array as $field) {
			// dont really need to escape these as they are set server side but in the future they may get chosen by user
			$field = $this->database->escape_string($field);
			$field_string .= $field . ' , ' ;
		}
		$field_string = substr($field_string, 0, -2);
		$object_array = $this->find_by_sql("SELECT $field_string FROM $table $this->wheres $this->sql_limit");
		return $object_array;
	}

}

class DbTable extends SqlTable {

	function __construct() {
		global $local_db;
		$this->database = $local_db;
	}

	public function select_from_where($field_array, $table, $field1, $value1, $field2, $value2, $id, $da_anno, $da_mese, $da_giorno, $a_anno, $a_mese, $a_giorno, $da_h, $da_m, $a_h, $a_m, $simbolo_connesso, $secs_connesso, $simbolo_squillo, $secs_squillo) {
		// global $local_db;

		$field1 = $this->database->escape_string($field1);
		$value1 = $this->database->escape_string($value1);
		$field2 = $this->database->escape_string($field2);
		$value2 = $this->database->escape_string($value2);
		$da_anno = $this->database->escape_string($da_anno);
		$da_mese = $this->database->escape_string($da_mese);
		$da_giorno = $this->database->escape_string($da_giorno);
		$a_anno = $this->database->escape_string($a_anno);
		$a_mese = $this->database->escape_string($a_mese);
		$a_giorno = $this->database->escape_string($a_giorno);
		$da_h = $this->database->escape_string($da_h);
		$da_m = $this->database->escape_string($da_m);
		$a_h = $this->database->escape_string($a_h);
		$a_m = $this->database->escape_string($a_m);
		$simbolo_connesso = $this->database->escape_string($simbolo_connesso);
		$secs_connesso = $this->database->escape_string($secs_connesso);
		$simbolo_squillo = $this->database->escape_string($simbolo_squillo);
		$secs_squillo = $this->database->escape_string($secs_squillo);

		$this->wheres = "WHERE $field1 LIKE '%$value1%' ";
		$this->wheres .= "AND $field2 LIKE '%$value2%' ";
		$this->wheres .= "AND id >= $id ";
		$this->wheres .= "AND orario_chiamata BETWEEN '$da_h:$da_m:00' AND '$a_h:$a_m:00' ";
		$this->wheres .= "AND data_chiamata BETWEEN '$da_anno-$da_mese-$da_giorno' AND '$a_anno-$a_mese-$a_giorno' ";
		$this->wheres .= ($simbolo_connesso == '*') ? "" : "AND tempo_connesso $simbolo_connesso $secs_connesso " ;
		$this->wheres .= ($simbolo_squillo == '*') ? "" : "AND tempo_squillo $simbolo_squillo $secs_squillo " ;
		$this->wheres .= "ORDER BY id DESC ";

		$object_array = $this->select_from($field_array, $table);
		return $object_array;
	}

}

class FpTable extends SqlTable {

		function __construct() {
			global $fp_db;
			$fp_db->open_connection();
			$this->database = $fp_db;
		}

		public function select_from_where($column_array, $table, $cons) {
			$this->wheres .= "WHERE ";
			foreach ($cons as $key => $value) {
				$this->set_where($key, $value);
			//	$this->wheres .= "$key LIKE '%".$this->database->escape_string($value)."%' AND ";
			}
			$this->wheres = substr($this->wheres, 0, -4);
			$object_array = $this->select_from($column_array, $table);
			return $object_array;
		}

		protected function set_where($key, $value) {
			if ($value === '') {
				// $this->wheres .= "$key IS NULL AND ";
				$this->wheres .= "";
			} else {
				$this->wheres .= "$key LIKE '%".$this->database->escape_string($value)."%' AND ";
			}
		}
}

?>
