<?php
// require_once(LIB_PATH.DS."oop".DS."local_db.php");
// require_once(LIB_PATH.DS."oop".DS."branch.php");
// require_once(LIB_PATH.DS."oop".DS."dept.php");

class sqlTable {

	public $name='';
	public $field;
	public $type;
	public $null;
	public $key;
	public $default;
	public $extra;

	private static function instantiate($result) {
		$object = new self;
		isset($result["Field"]) ? $object->field = $result["Field"] : false ;
		isset($result["Type"]) ? $object->type = $result["Type"] : false ;
		isset($result["Null"]) ? $object->null = $result["Null"] : false ;
		isset($result["Key"]) ? $object->key = $result["Key"] : false ;
		isset($result["Default"]) ? $object->default = $result["Default"] : false ;
		isset($result["Extra"]) ? $object->extra = $result["Extra"] : false ;
		return $object;
	}

	public static function find_by_sql($sql="") {
		global $local_db;
		$result_set = $local_db->query($sql);
		$object_array = array();
		while ($result = mysqli_fetch_assoc($result_set)) {
			//make an instance for every result
			$object_array[] = self::instantiate($result);
		}
		return $object_array;
	}

	public static function describe_table($name) {
//		$this->name = $name;
		return self::find_by_sql("DESCRIBE $name");
	}

}

?>
