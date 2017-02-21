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

	public function error_message() {
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
