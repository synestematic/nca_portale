<?php

class Validation {

	private $errors = array();
	private $message = '';

	public function is_equal($string1, $string2) {
	  	if ($string1 !== $string2 && $string1 !== "" && $string2 !== "") {
	  		$this->errors['Attenzione!'] = "Le password non combaciano.";
	  }
	}

	public function check_max_length($fields_with_max_length) {
		// Expects an assoc. array
		foreach($fields_with_max_length as $field => $max) {
			$value = trim($_POST[$field]);
		  if (strlen($value) >= $max) {
		    $this->errors[$field] = String::ucfirst_rmunderscores($field) . " troppo lungo.";
		  }
		}
	}

	public function check_presence($required_fields) {
	  foreach($required_fields as $field) {
	    $value = trim($_POST[$field]);
	  	if (!isset($value) || $value === "" ) {
	  		$this->errors[$field] = String::ucfirst_rmunderscores($field) . " mancante.";
	  	}
	  }
	}

	public function check_stock_id($stock_id) {
		if ( preg_match("/^[a-zA-Z]{2}[0-9]{5}$/", $stock_id ) ) {
          return true;
	  	} else {
		  return false;
	  	}
	}

	public function check_targa($targa) {
		$ab = substr($targa ,0,2);
		$num = substr($targa ,2,3);
		$boh = substr($targa ,5,3);
		if (!ctype_alpha($ab) || !is_numeric($num)) {
          return false;
	  	} else {
			if (!is_numeric($boh) && strlen($targa) === 7) {
				return true;
			} else if (is_numeric($boh) && strlen($boh) > 1 && strlen($targa) < 9) {
				return true;
			} else {
				return false;
			}
	  	}
	}

	public function check_for_allowed_get_values($key, $values = array()) {
		$allowed_value_found = false ;
		foreach ($values as $value) {
			if ($_GET["$key"] === $value) {
				$allowed_value_found = true ;
			}
		}
		if ( $allowed_value_found === false ) {
			$_SESSION["message"] = "Parametro ".$key." non valido.";
		}
		return $allowed_value_found;
	}

	public function error_message() {
		// this message is only valid within same pages
		if (!empty($this->errors)) {
			$this->message = '';
			$this->message .= "<div class=\"error\">";
			$this->message .= "<b>Sono stati rilevati degli errori:</b>";
			$this->message .= "<ul>";
		  foreach ($this->errors as $key => $error) {
				$this->message .= "<li>" . htmlentities($error) . "</li>";
		  }
		  $this->message .= "</ul>";
		  $this->message .= "</div>";
			return $this->message;
		} else {
			return null;
		}
	}

}

$validation = new Validation();

?>
