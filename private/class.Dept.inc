<?php
class Dept {

	public $id;
	public $reparto;
	public $orario = array(
		'default' => array('apertura' => 9, 'chiusura' => 18,),
	);

	private static function instantiate($result) {
		$object = new self;
		isset($result["id"]) ? $object->id = $result["id"] : false ;
		isset($result["reparto"]) ? $object->reparto = $result["reparto"] : false ;
		if ($object->reparto === 'bc') {
			$object->orario = array(
				'default'	 => array('apertura' => 8, 'chiusura' => 21,),
				'6'			 => array('apertura' => 9, 'chiusura' => 19,),
				'7'			 => array('apertura' => 14,'chiusura' => 19,),
			);
		}
		if ($object->reparto === 'bi') {
			$object->orario = array(
				'default'	 => array('apertura' => 9, 'chiusura' => 19,),
			);
		}
		return $object;
	}

	public static function orario_apertura($dept = 'ops', $weekday) {
		$object = self::find_by_reparto($dept);
		if (!array_key_exists($weekday ,$object->orario)) {
			$weekday = 'default';
		}
		return $object->orario[$weekday]['apertura'];
	}
	public static function orario_chiusura($dept = 'ops', $weekday) {
		$object = self::find_by_reparto($dept);
		if (!array_key_exists($weekday ,$object->orario)) {
			$weekday = 'default';
		}
		return $object->orario[$weekday]['chiusura'];
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
