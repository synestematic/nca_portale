<?php
// require_once(LIB_PATH.DS."oop".DS."local_db.php");
// require_once(LIB_PATH.DS."oop".DS."sql_table.php");

class Chiamata {

	public $id;
	public $data_chiamata = "";
	public $orario_chiamata = "";
	public $orario_hangup = "";
	public $tempo_squillo = "";
	public $tempo_connesso = "";
	public $estensione = "";
	public $utente = "";
	public $da_numero = "";
	public $numero_chiamato = "";
	public $numero_connesso = "";
	public $tipo_connessione = "";
	public $esito_chiamata = "";
	public $datetime_inserzione = "";

	private static function instantiate($result) {
		$object = new self;
		isset($result["id"]) ? $object->id = $result["id"] : false ;
		isset($result["data_chiamata"]) ? $object->data_chiamata = $result["data_chiamata"] : false ;
		isset($result["orario_chiamata"]) ? $object->orario_chiamata = $result["orario_chiamata"] : false ;
		isset($result["orario_hangup"]) ? $object->orario_hangup = $result["orario_hangup"] : false ;
		isset($result["tempo_squillo"]) ? $object->tempo_squillo = $result["tempo_squillo"] : false ;
		isset($result["tempo_connesso"]) ? $object->tempo_connesso = $result["tempo_connesso"] : false ;
		isset($result["estensione"]) ? $object->estensione = $result["estensione"] : $object->utente = "" ;
		isset($result["utente"]) ? $object->utente = $result["utente"] : $object->utente = "" ;
		isset($result["da_numero"]) ? $object->da_numero = $result["da_numero"] : false ;
		isset($result["numero_chiamato"]) ? $object->numero_chiamato = $result["numero_chiamato"] : false ;
		isset($result["numero_connesso"]) ? $object->numero_connesso = $result["numero_connesso"] : false ;
		isset($result["tipo_connessione"]) ? $object->tipo_connessione = $result["tipo_connessione"] : false ;
		isset($result["esito_chiamata"]) ? $object->esito_chiamata = $result["esito_chiamata"] : false ;
		isset($result["datetime_inserzione"]) ? $object->datetime_inserzione = $result["datetime_inserzione"] : false ;
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

	public static function find_all_from($table) {
		return self::find_by_sql("SELECT * FROM ".$table);
	}

	public static function find_by_id_from($id=1000, $table) {
		$result_array = self::find_by_sql("SELECT * FROM ".$table." WHERE id = {$id};");
		return !empty($result_array) ? array_shift($result_array) : false ;
	}

	public static function select_records($table, $as, $bs, $cs, $ds, $da_anno, $da_mese, $da_giorno, $a_anno, $a_mese, $a_giorno, $da_h, $da_m, $a_h, $a_m, $simbolo_connesso, $secs_connesso, $simbolo_squillo, $secs_squillo) {
		global $local_db;
		$as = $local_db->escape_value($as);
		$bs = $local_db->escape_value($bs);
		$cs = $local_db->escape_value($cs);
		$ds = $local_db->escape_value($ds);
		$query = "SELECT * FROM $table ";
		$query .= "WHERE $as LIKE '%$bs%' ";
		$query .= "AND $cs LIKE '%$ds%' ";
		$query .= "AND orario_chiamata BETWEEN '$da_h:$da_m:00' AND '$a_h:$a_m:00' ";
		$query .= "AND data_chiamata BETWEEN '$da_anno-$da_mese-$da_giorno' AND '$a_anno-$a_mese-$a_giorno' ";
		$query .= ($simbolo_connesso == '*') ? " " : "AND tempo_connesso $simbolo_connesso $secs_connesso " ;
		$query .= ($simbolo_squillo == '*') ? " " : "AND tempo_squillo $simbolo_squillo $secs_squillo " ;
		$query .= "ORDER BY id DESC ";
		return self::find_by_sql($query);
	}

	public function check_for_duplicate_into($table) {
		$check_query = "SELECT id FROM $table ";
		$check_query .= "WHERE utente = '$this->utente' ";
		$check_query .= "AND data_chiamata = '$this->data_chiamata' ";
		$check_query .= "AND orario_chiamata = '$this->orario_chiamata' ";
		$check_query .= "AND orario_hangup = '$this->orario_hangup' ";
		return self::find_by_sql($check_query);
	}

	public function create_into($table) {
		global $local_db;
		$sql = "INSERT INTO $table ";
		$sql .= "(data_chiamata, orario_chiamata, orario_hangup, tempo_squillo, tempo_connesso, estensione, utente, da_numero, numero_chiamato, numero_connesso, tipo_connessione, esito_chiamata, datetime_inserzione) ";
		$sql .= "VALUES ('";
		$sql .= $local_db->escape_value($this->data_chiamata) ."', '";
		$sql .= $local_db->escape_value($this->orario_chiamata) ."', '";
		$sql .= $local_db->escape_value($this->orario_hangup) ."', '";
		$sql .= $local_db->escape_value($this->tempo_squillo) ."', '";
		$sql .= $local_db->escape_value($this->tempo_connesso) ."', '";
		$sql .= $local_db->escape_value($this->estensione) ."', '";
		$sql .= $local_db->escape_value($this->utente) ."', '";
		$sql .= $local_db->escape_value($this->da_numero) ."', '";
		$sql .= $local_db->escape_value($this->numero_chiamato) ."', '";
		$sql .= $local_db->escape_value($this->numero_connesso) ."', '";
		$sql .= $local_db->escape_value($this->tipo_connessione) ."', '";
		$sql .= $local_db->escape_value($this->esito_chiamata) ."', '";
		$sql .= $local_db->escape_value($this->datetime_inserzione) . "')";
		if ($local_db->query($sql)) {
			$this->id = $local_db->insert_id();
			return true;
		} else {
			return false;
		}
	}


}

?>
