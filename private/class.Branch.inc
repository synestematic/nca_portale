<?php
//require_once(LIB_PATH.DS."oop".DS."local_db.php");

class Branch {

	public $id;
	public $filiale;
	public $nb_number;
	public $field;

	private static function instantiate($result) {
		$object = new self;
		isset($result["id"]) ? $object->id = $result["id"] : false ;
		isset($result["filiale"]) ? $object->filiale = $result["filiale"] : false ;
		isset($result["nb_number"]) ? $object->nb_number = $result["nb_number"] : false ;
		isset($result["Field"]) ? $object->field = $result["Field"] : false ;
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

	public static function find_all() {
		return self::find_by_sql("SELECT * FROM branches");
	}

	public static function find_by_id($id=1) {
		$result = self::find_by_sql("SELECT * FROM branches WHERE id = {$id};");
		return !empty($result) ? array_shift($result) : false ;
	}

	public static function find_by_filiale($filiale='') {
		$result = self::find_by_sql("SELECT * FROM branches WHERE filiale = '{$filiale}' LIMIT 1");
		return !empty($result) ? array_shift($result) : false ;
	}

	public static function find_by_nb_number($nb_number='') {
		return self::find_by_sql("SELECT * FROM branches WHERE nb_number = '{$nb_number}'");
	}

	public static function find_by_nb_number2($nb_number='') {
		return self::find_by_sql("SELECT * FROM branches WHERE nb_number LIKE '%$nb_number%'");
	}

	// create update delete methods will require instances to begin with and therefore will not be static functions

	public function save() {
		return isset($this->id) ? $this->update() : $this->create() ;
	}

	protected function create() {
		global $local_db;
		$sql = "INSERT INTO branches (";
		$sql .= "id, filiale, nb_number";
		$sql .= ") VALUES ('";
		$sql .= $local_db->escape_value($this->id) ."', '";
		$sql .= $local_db->escape_value($this->filiale) ."', '";
		$sql .= $local_db->escape_value($this->nb_number) ."') ";
           	if ($local_db->query($sql)) {
                         $this->id = $local_db->insert_id();
                         return true;
           	} else {
                         return false;
                }
	}

	protected function update() {
		global $local_db;
		$sql = "UPDATE branches SET ";
		$sql .= "filiale='". $local_db->escape_value($this->filiale) . "', ";
		$sql .= "nb_number='". $local_db->escape_value($this->nb_number) . "', ";
		$sql .= "WHERE id=". $local_db->escape_value($this->id);
		$sql .= "LIMIT 1";
		$local_db->query($sql);
		return ($local_db->affected_rows() == 1) ? true : false ;
	}

	public function delete() {
		global $local_db;
		$sql = "DELETE FROM branches ";
		$sql .= "WHERE id = ". $local_db->escape_value($this->id);
		$sql .= " LIMIT 1";
		$local_db->query($sql);
		return ($local_db->affected_rows() == 1) ? true : false ;
	}

	public static function branch_dropdown($name) {
		global $to_be_edited_user;
		global $logged_user;
		global $_POST;
		$branches = self::find_all();
		echo '<select name="'.$name.'" value="" >';
		foreach ($branches as $branch) {
			if (isset($_POST['branch'])) {
				echo ($_POST["branch"] == $branch->filiale) ? '<option selected>' : '<option>' ;
			} else {
				if (isset($to_be_edited_user->branch)) {
					echo ($to_be_edited_user->branch == $branch->filiale) ? '<option selected>' : '<option>' ;
					} else {
						echo ($logged_user->branch == $branch->filiale) ? '<option selected>' : '<option>' ;
					}
			}
			echo $branch->filiale;
			echo '</option>';
		}
		echo '</select>';
	}

}

?>
