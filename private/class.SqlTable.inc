<?php
setlocale(LC_TIME, 'it_IT');
setlocale(LC_ALL, 'it_IT');

class SqlTable {

	public $sql_fields = array();
	public $last_sql = "";
	protected $sql_limit = '';
	protected $order = '';
	protected $where_string = '';
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
		$query_result = $this->database->query($sql);
		while ( $row = mysqli_fetch_row($query_result) ) {
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

	public function make_excel($query='') {
		// ALWAYS use double quotes when working with special control characters [ \n \t \r ]
		$output = "";
		// $result = $this->database->query($this->last_sql); //this produces empty query :-(
		$result = $this->database->query($query);

		//Prints column names as MySQL fields
		while ( $property = mysqli_fetch_field($result) ) {
		    $output .= $property->name . "\t";
		}
		$output .= "\n";

		//Prints values
		while ( $row = mysqli_fetch_row($result) ) {
		    $schema_insert = "";
		    for ( $j=0 ; $j<mysqli_num_fields($result) ; $j++ ) {
		        if ( !isset( $row[$j] ) )
		            // $schema_insert .= "NULL"."\t";
		            $schema_insert .= ""."\t";
		        elseif ( $row[$j] != "" )
		            $schema_insert .= "$row[$j]"."\t";
		        else
		            $schema_insert .= ""."\t";
		    }
		    $schema_insert = str_replace("\t"."$", "", $schema_insert);
		    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
		    $schema_insert .= "\t";
		    $output .= trim($schema_insert) ;
		    $output .= "\n";
		}
		return $output;
	}

	public function show_fields_from($field_array, $table) {
		$temp = $this->sql_limit;
		$this->sql_limit = 'LIMIT 1';
		$object_array = $this->select_from($field_array, $table);
		$this->sql_limit = $temp;
		return array_keys($object_array[0]->sql_fields);
	}

	public function select_from($field_array, $table) {
		$table = $this->database->escape_string($table);
		$field_string = '';
		foreach ($field_array as $field) {
			// dont really need to escape these as they are set server side but in the future they may get chosen by user
			$field = $this->database->escape_string($field);
			$field_string .= $field . ' , ' ;
		}
		$field_string = substr($field_string, 0, -2);
		$object_array = $this->find_by_sql("SELECT $field_string FROM $table $this->where_string $this->order $this->sql_limit");
		return $object_array;
	}

	public function insert_into($key_values, $table) {
			$key_string = '';
			$value_string = '';
			foreach ($key_values as $key => $value) {
					$key_string .= $key . ' , ' ;
					$value = $this->database->escape_string($value);
					$value_string .= '"'.$value . '" , ' ;
			}
			$key_string = substr($key_string, 0, -2);
			$value_string = substr($value_string, 0, -2);

			$sql = "INSERT INTO $table ($key_string) VALUES ($value_string);";

			if ($this->database->query($sql)) {
					return true;
			} else {
					return false;
			}
	}

	public function update_set_where($table, $set_array, $where_array) {
		$set_string = '';
		foreach ($set_array as $key => $value) {
				$set_string .= $key .'="'. $this->database->escape_string($value).'", ' ;
		}
		$set_string = substr($set_string, 0, -2);

		$where_string = '';
		foreach ($where_array as $key => $value) {
				$where_string .= $key .'="'. $this->database->escape_string($value).'", ' ;
		}
		$where_string = substr($where_string, 0, -2);

		$sql = "UPDATE $table SET $set_string WHERE $where_string LIMIT 1";

		if ($this->database->query($sql)) {
				return true;
		} else {
				return false;
		}
	}

}

class DbTable extends SqlTable {

	function __construct() {
		global $local_db;
		$this->database = $local_db;
	}

}

class FpTable extends SqlTable {

		function __construct() {
			global $fp_db;
			$fp_db->open_connection();
			$this->database = $fp_db;
		}

		public function select_from_where_order($column_array, $table, $where_array, $order) {
			$this->order = $order;
			$object_array = $this->select_from_where($column_array, $table, $where_array);
			return $object_array;
		}

		public function select_from_where($column_array, $table, $where_array) {
			$this->set_where_string($where_array);
			$object_array = $this->select_from($column_array, $table);
			return $object_array;
		}

		protected function set_where_string($where_array) {
			$at_least_one_where_has_been_set = false;
			$this->where_string .= "WHERE ";
			foreach ($where_array as $key => $value) {
				// if input displays "name" attribute OR is empty THEN ignore them
				if ($value === String::ucfirst_rmunderscores($key) || $value === '') {
					$this->where_string .= "";
				} else {
					$this->where_string .= "$key LIKE '%".$this->database->escape_string($value)."%' AND ";
					$at_least_one_where_has_been_set = true;
				}
			}
			$this->where_string = substr($this->where_string, 0, -4);
			if ($at_least_one_where_has_been_set === false) {
				$this->where_string = '';
			}
		}

}

?>
