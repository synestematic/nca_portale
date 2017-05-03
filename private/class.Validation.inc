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
		$regexes = array(
			"/^[a-zA-Z]{2}[0-9]{3}[a-zA-Z]{2}$/" ,  		// EL 432 PJ
			"/^[a-zA-Z]{2}[0-9]{5}[a-zA-Z]$/" ,				// RM 45872 L
	        "/^[a-zA-Z]{3}[0-9]{5}$/" ,                     // GEE 69999
	        "/^[a-zA-Z]{2}[0-9][a-zA-Z][0-9]{4}$/" ,        // RM 2 E 2426
	    );

	    foreach ($regexes as $regex) {
	        if (preg_match($regex, $targa )) {
	            return true ;
	        }
	    }
	    return false ;
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
