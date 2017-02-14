<?php
function redirect($location) {
  header("Location: " . $location);
  exit;
}

function __autoload($class) {
  $path = LIB_PATH.DS."class.".$class.".inc";
  if (file_exists($path)) {
    require_once($path);
  } else {
    die("$class class not found and __autoloader not able to make up for it.");
  }
}
///////////////////// functions from OLD validation.php file///////////////////////////////

$errors = array();

function fieldname_as_text($fieldname) {
  $fieldname = str_replace("_", " ", $fieldname);
  $fieldname = ucfirst($fieldname);
  return $fieldname;
}

// use trim() so empty spaces don't count
// use === to avoid false positives
// empty() would consider "0" to be empty
function has_presence($value) {
	return isset($value) && $value !== "";
}

function validate_presences($required_fields) {
  global $errors;
  foreach($required_fields as $field) {
    $value = trim($_POST[$field]);
  	if (!has_presence($value)) {
  		$errors[$field] = fieldname_as_text($field) . ": vuoto";
  	}
  }
}

function is_equal($password, $conferma) {
  global $errors;
  	if ($password !== $conferma && $password !== "" && $conferma !== "") {
  		$errors['password'] = "Le password non combaciano.";
  }
}

function has_max_length($value, $max) {
	return strlen($value) <= $max;
}

function validate_max_lengths($fields_with_max_lengths) {
	global $errors;
	// Expects an assoc. array
	foreach($fields_with_max_lengths as $field => $max) {
		$value = trim($_POST[$field]);
	  if (!has_max_length($value, $max)) {
	    $errors[$field] = fieldname_as_text($field) . ": troppo lungo";
	  }
	}
}

// * inclusion in a set
function has_inclusion_in($value, $set) {
	return in_array($value, $set);
}

function form_errors($errors=array()) {
	$output = "";
	if (!empty($errors)) {
	  $output .= "<div class=\"error\">";
	  $output .= "<b>Sono stati rilevati degli errori:</b>";
	  $output .= "<ul>";
	  foreach ($errors as $key => $error) {
	    $output .= "<li>";
			$output .= htmlentities($error);
			$output .= "</li>";
	  }
	  $output .= "</ul>";
	  $output .= "</div>";
	}
	return $output;
}

?>
