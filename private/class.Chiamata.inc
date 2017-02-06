<?php
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
	public $comment = "";
	public $gruppo = "";
	public $last_sql = "";

	private static function instantiate($result, $requested_query) {
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
		$object->last_sql = $requested_query ;
		return $object;
	}

	public static function find_by_sql($sql="") {
		global $local_db;
		$result_set = $local_db->query($sql);
		$object_array = array();
		while ($result = mysqli_fetch_assoc($result_set)) {
			$object_array[] = self::instantiate($result , $sql);
		}
		return $object_array;
	}

	public static function find_all_from($table) {
		return self::find_by_sql("SELECT * FROM ".$table);
	}

	public static function find_by_id_from($id=1000, $table) {
		global $local_db;
		$sql = 'SELECT * FROM ';
		$sql .= $local_db->escape_string($table);
		$sql .= ' WHERE id = ';
		$sql .= $local_db->escape_string($id);
		$sql .= ' ';
		$result = self::find_by_sql($sql);
		return !empty($result) ? array_shift($result) : false ;
		// $result_array = self::find_by_sql("SELECT * FROM ".$table." WHERE id = {$id};");
		// return !empty($result_array) ? array_shift($result_array) : false ;
	}

	public function select_records($table, $as, $bs, $cs, $ds, $da_anno, $da_mese, $da_giorno, $a_anno, $a_mese, $a_giorno, $da_h, $da_m, $a_h, $a_m, $simbolo_connesso, $secs_connesso, $simbolo_squillo, $secs_squillo) {
		global $local_db;
		$table = $local_db->escape_string($table);
		$as = $local_db->escape_string($as);
		$bs = $local_db->escape_string($bs);
		$cs = $local_db->escape_string($cs);
		$ds = $local_db->escape_string($ds);
		$da_anno = $local_db->escape_string($da_anno);
		$da_mese = $local_db->escape_string($da_mese);
		$da_giorno = $local_db->escape_string($da_giorno);
		$a_anno = $local_db->escape_string($a_anno);
		$a_mese = $local_db->escape_string($a_mese);
		$a_giorno = $local_db->escape_string($a_giorno);
		$da_h = $local_db->escape_string($da_h);
		$da_m = $local_db->escape_string($da_m);
		$a_h = $local_db->escape_string($a_h);
		$a_m = $local_db->escape_string($a_m);
		$simbolo_connesso = $local_db->escape_string($simbolo_connesso);
		$secs_connesso = $local_db->escape_string($secs_connesso);
		$simbolo_squillo = $local_db->escape_string($simbolo_squillo);
		$secs_squillo = $local_db->escape_string($secs_squillo);

		$query = "SELECT * FROM $table ";
		$query .= "WHERE $as LIKE '%$bs%' ";
		$query .= "AND $cs LIKE '%$ds%' ";
		$query .= "AND orario_chiamata BETWEEN '$da_h:$da_m:00' AND '$a_h:$a_m:00' ";
		$query .= "AND data_chiamata BETWEEN '$da_anno-$da_mese-$da_giorno' AND '$a_anno-$a_mese-$a_giorno' ";
		$query .= ($simbolo_connesso == '*') ? "" : "AND tempo_connesso $simbolo_connesso $secs_connesso " ;
		$query .= ($simbolo_squillo == '*') ? "" : "AND tempo_squillo $simbolo_squillo $secs_squillo " ;
		$query .= "ORDER BY id DESC ";
		return self::find_by_sql($query);
	}

	public function check_for_duplicate_into($table) {
		global $local_db;
		$table = $local_db->escape_string($table);
		$utente = $local_db->escape_string($this->utente);
		$data_chiamata = $local_db->escape_string($this->data_chiamata);
		$orario_chiamata = $local_db->escape_string($this->orario_chiamata);
		$orario_hangup = $local_db->escape_string($this->orario_hangup);
		$check_query = "SELECT id FROM $table ";
		$check_query .= "WHERE utente = '$utente' ";
		$check_query .= "AND data_chiamata = '$data_chiamata' ";
		$check_query .= "AND orario_chiamata = '$orario_chiamata' ";
		$check_query .= "AND orario_hangup = '$orario_hangup' ";
		return self::find_by_sql($check_query);
	}

	public function create_into($table) {
		global $local_db;
		$sql = "INSERT INTO $table ";
		$sql .= "(data_chiamata, orario_chiamata, orario_hangup, tempo_squillo, tempo_connesso, estensione, utente, da_numero, numero_chiamato, numero_connesso, tipo_connessione, esito_chiamata, datetime_inserzione) ";
		$sql .= "VALUES ('";
		$sql .= $local_db->escape_string($this->data_chiamata) ."', '";
		$sql .= $local_db->escape_string($this->orario_chiamata) ."', '";
		$sql .= $local_db->escape_string($this->orario_hangup) ."', '";
		$sql .= $local_db->escape_string($this->tempo_squillo) ."', '";
		$sql .= $local_db->escape_string($this->tempo_connesso) ."', '";
		$sql .= $local_db->escape_string($this->estensione) ."', '";
		$sql .= $local_db->escape_string($this->utente) ."', '";
		$sql .= $local_db->escape_string($this->da_numero) ."', '";
		$sql .= $local_db->escape_string($this->numero_chiamato) ."', '";
		$sql .= $local_db->escape_string($this->numero_connesso) ."', '";
		$sql .= $local_db->escape_string($this->tipo_connessione) ."', '";
		$sql .= $local_db->escape_string($this->esito_chiamata) ."', '";
		$sql .= $local_db->escape_string($this->datetime_inserzione) . "')";
		if ($local_db->query($sql)) {
			$this->id = $local_db->insert_id();
			return true;
		} else {
			return false;
		}
	}

	public function return_XML() {
		echo "<records>\n";
		echo "  <record>\n";
		//echo "    <field>"."false"."</field>\n";
		echo "    <CallStartEpoch>".$this->data_chiamata." ".$this->orario_chiamata."</CallStartEpoch>\n";
		echo "    <CallEndEpoch>".$this->data_chiamata." ".$this->orario_hangup."</CallEndEpoch>\n";
		echo "    <CallRingSeconds>".$this->tempo_squillo."</CallRingSeconds>\n";
		echo "    <CallTalkSeconds>".$this->tempo_connesso."</CallTalkSeconds>\n";
		echo "    <CallerUserAgent>".$this->utente."</CallerUserAgent>\n";
		echo "    <CallerNumber>".$this->da_numero."</CallerNumber>\n";
		echo "    <DialledNumber>".$this->numero_chiamato."</DialledNumber>\n";
		echo "    <ConnectedNumber>".$this->numero_connesso."</ConnectedNumber>\n";
		echo "    <ConnectedType>".$this->tipo_connessione."</ConnectedType>\n";

		if (strpos($this->tipo_connessione, 'inbound') !== false) {
			echo "    <ConnectedGroup>".$this->gruppo."</ConnectedGroup>\n";
		}
		if (strpos($this->tipo_connessione, 'outbound') !== false) {
			echo "    <CallerGroup>".$this->gruppo."</CallerGroup>\n";
		}

		echo "    <HangupCause>".$this->esito_chiamata."</HangupCause>\n";
		echo "    <Comment>".$this->comment."</Comment>\n"; //make attribute for this
		echo "    <Extension>".$this->estensione."</Extension>\n";
		echo "  </record>\n";
		echo "</records>\n";
	}

}

?>
