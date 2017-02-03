<?php
class Merchant {

	public $partita_iva = "";
	public $denominazione_cliente = "";
	public $destinatario_sped = "";
	public $indirizzo_sped = "";
	public $cap_sped = "";
	public $citta_sped = "";
	public $provincia_sped = "";
	public $telefono = "";
	public $email = "";
	public $last_sql = "";

	private static function instantiate($result, $requested_query) {
		$object = new self;
		isset($result["Partita_IVA"]) ? $object->partita_iva = $result["Partita_IVA"] : false ;
		isset($result["Denominazione_Cliente"]) ? $object->denominazione_cliente = $result["Denominazione_Cliente"] : false ;
		isset($result["destinatario_sped"]) ? $object->destinatario_sped = $result["destinatario_sped"] : false ;
		isset($result["indirizzo_sped"]) ? $object->indirizzo_sped = $result["indirizzo_sped"] : false ;
		isset($result["cap_sped"]) ? $object->cap_sped = $result["cap_sped"] : false ;
		isset($result["citta_sped"]) ? $object->citta_sped = $result["citta_sped"] : false ;
		isset($result["provincia_sped"]) ? $object->provincia_sped = $result["provincia_sped"] : false ;
		isset($result["Telefono"]) ? $object->telefono = $result["Telefono"] : false ;
		isset($result["eMail"]) ? $object->email = $result["eMail"] : false ;
		$object->last_sql = $requested_query ;
		return $object;
	}

	public static function find_by_sql($sql="") {
		global $fp_db;
		$result_set = $fp_db->query($sql);
		$object_array = array();
		while ($result = mysqli_fetch_assoc($result_set)) {
			$object_array[] = self::instantiate($result , $sql);
		}
		return $object_array;
	}

	public function find_all() {
		global $fp_db;
		$fp_db->open_connection();
		return self::find_by_sql("SELECT * FROM indirizzi_merchant_it");
	}

	public function find_by_piva() {
		global $fp_db;
		$fp_db->open_connection();
		$this->partita_iva = $fp_db->escape_string($this->partita_iva);
		$query = "SELECT * FROM indirizzi_merchant_it WHERE Partita_IVA = '".$this->partita_iva."'";
		return self::find_by_sql($query);
	}

	public function find_by($field,$value) {
		global $fp_db;
		$fp_db->open_connection();
		$field = $fp_db->escape_string($field);
		$value = $fp_db->escape_string($value);
		$query = "SELECT * FROM indirizzi_merchant_it ";
		$query .= "WHERE $field LIKE '%$value%' ";
		// $query .= "AND $cs LIKE '%$ds%' ";
		return self::find_by_sql($query);
	}

}

?>
