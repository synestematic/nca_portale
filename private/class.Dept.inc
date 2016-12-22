<?php
class Dept {

	public $id;
	public $reparto;
	public $field;

	private static function instantiate($result) {
		$object = new self;
		isset($result["id"]) ? $object->id = $result["id"] : false ;
		isset($result["reparto"]) ? $object->reparto = $result["reparto"] : false ;
		isset($result["Field"]) ? $object->field = $result["Field"] : false ;
		return $object;
	}

	public static function find_by_sql($sql="") {
		global $local_db;
		$result_set = $local_db->query($sql);
		$object_array = array();
		while ($result = mysqli_fetch_assoc($result_set)) {
			$object_array[] = self::instantiate($result);
		}
		return $object_array;
	}

	public static function describe_table() {
		return self::find_by_sql("DESCRIBE depts");
	}

	public static function find_all() {
		return self::find_by_sql("SELECT * FROM depts");
	}

	public static function find_by_id($id=1) {
		global $local_db;
		$sql = 'SELECT * FROM depts WHERE id = "';
		$sql .= $local_db->escape_string($id);
		$sql .= '" LIMIT 1';
		$result = self::find_by_sql($sql);
		return !empty($result) ? array_shift($result) : false ;
}

	public static function find_by_reparto($reparto='') {
		global $local_db;
		$sql = 'SELECT * FROM depts WHERE reparto = "';
		$sql .= $local_db->escape_string($reparto);
		$sql .= '" LIMIT 1';
		$result = self::find_by_sql($sql);
		return !empty($result) ? array_shift($result) : false ;
	}

	// create update delete methods will require instances to begin with and therefore will not be static functions
	public function save() {
		return isset($this->id) ? $this->update() : $this->create() ;
	}

	protected function create() {
		global $local_db;
		$sql = "INSERT INTO depts (";
		$sql .= "id, reparto";
		$sql .= ") VALUES ('";
		$sql .= $local_db->escape_string($this->id) ."', '";
		$sql .= $local_db->escape_string($this->reparto) ."') ";
    if ($local_db->query($sql)) {
        $this->id = $local_db->insert_id();
        return true;
    } else {
        return false;
          }
   	}

	protected function update() {
		global $local_db;
		$sql = "UPDATE depts SET ";
		$sql .= "reparto='". $local_db->escape_string($this->reparto) . "', ";
		$sql .= "WHERE id=". $local_db->escape_string($this->id);
		$sql .= " LIMIT 1";
		$local_db->query($sql);
		return ($local_db->affected_rows() == 1) ? true : false ;
	}

	public function delete() {
		global $local_db;
		$sql = "DELETE FROM depts ";
		$sql .= "WHERE id = ". $local_db->escape_string($this->id);
		$sql .= " LIMIT 1";
		$local_db->query($sql);
		return ($local_db->affected_rows() == 1) ? true : false ;
	}

	public static function dept_dropdown($name) {
		global $to_be_edited_user;
		global $logged_user;
		global $_POST;
		$depts = self::find_all();
		echo '<select name="'.$name.'" value="" >';
		foreach ($depts as $dept) {
			if (isset($_POST['dept'])) {
				echo ($_POST["dept"] == $dept->reparto) ? '<option selected>' : '<option>' ;
			} else {
				if (isset($to_be_edited_user->dept)) {
					echo ($to_be_edited_user->dept == $dept->reparto) ? '<option selected>' : '<option>' ;
					} else {
						echo ($logged_user->dept == $dept->reparto) ? '<option selected>' : '<option>' ;
					}
			}
			echo $dept->reparto;
			echo '</option>';
		}
		echo '</select>';
	}

}
?>
