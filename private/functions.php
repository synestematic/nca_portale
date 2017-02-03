<?php
// remove once fully gone to OOP
$handle = mysqli_connect("localhost", "natterbox", "q1w2e3r4", "nca");
if(mysqli_connect_errno()) {
	die ("DB connection failed: " . mysqli_connect_error() .	" (" . mysqli_connect_errno() . ")" );
}
///////////////////////////////
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

/////////////////////old functions to take care after OOP///////////////////////////////

// function mysql_prep($string) {
// 	global $handle;
// 	$escaped_string = mysqli_real_escape_string($handle, $string);
// 	return $escaped_string;
// }

// function confirm_query($result_set) {
// 	if (!$result_set) {
// 		//echo "The query didn't return any results.";
// 		die("EPIC DB FAIL MAN... better luck next time.");
// 	}
// }

// function secs_to_his($secs) {
// 	$H = floor($secs / 3600) ;
//   $i = ($secs / 60) % 60 ;
//   $s = $secs % 60 ;
// 	$output = ($H > 0) ? sprintf("%02d:%02d:%02d", $H, $i, $s) : sprintf("%02d:%02d", $i, $s) ;
// 	echo $output;
// 	// return $output;
// }

// function select_records($table, $as, $bs, $cs, $ds, $da_anno, $da_mese, $da_giorno, $a_anno, $a_mese, $a_giorno, $da_h, $da_m, $a_h, $a_m, $simbolo_connesso, $secs_connesso, $simbolo_squillo, $secs_squillo) {
// 	global $handle;
// 	$a = mysqli_real_escape_string($handle, $as);
// 	$b = mysqli_real_escape_string($handle, $bs);
// 	$c = mysqli_real_escape_string($handle, $cs);
// 	$d = mysqli_real_escape_string($handle, $ds);
// 	$query = "SELECT * FROM $table ";
// 	$query .= "WHERE $a LIKE '%$b%' ";
// 	$query .= "AND $c LIKE '%$d%' ";
// //	$query .= "AND orario_chiamata BETWEEN '09:00:00' AND '11:00:00' ";
// 	$query .= "AND orario_chiamata BETWEEN '$da_h:$da_m:00' AND '$a_h:$a_m:00' ";
// //	$query .= "AND data_chiamata BETWEEN '2016-01-01' AND '2016-12-30' ";
// 	$query .= "AND data_chiamata BETWEEN '$da_anno-$da_mese-$da_giorno' AND '$a_anno-$a_mese-$a_giorno' ";
// //	$query .= "AND tempo_connesso > 10 ";
// 	$query .= ($simbolo_connesso == '*') ? " " : "AND tempo_connesso $simbolo_connesso $secs_connesso " ;
// 	$query .= ($simbolo_squillo == '*') ? " " : "AND tempo_squillo $simbolo_squillo $secs_squillo " ;
// 	$query .= "ORDER BY id DESC ";
// //  $query .= "LIMIT 1000";
// 	$proposte_set = mysqli_query($handle, $query);
// 	confirm_query($proposte_set);
// 	return $proposte_set;
// }

// function query_this( $sql="" ) {
// 	global $handle;
// 	$result_set = mysqli_query($handle, $sql);
// 	confirm_query($result_set);
// 	return $result_set;
// }

// function show_fields() {
// 	global $handle;
// 	$query = "SHOW fields ";
// 	$query .= "FROM chiamate_bc;";
// 	$field_set = mysqli_query($handle, $query);
// 	confirm_query($field_set);
// 	return $field_set;
// }
//
// function show_fields_fp() {
// 	global $conn;
// 	$query = 'SHOW fields FROM `adnca migrazione`.`report veicoli in stock e conclusi da meno di 31gg`';
// 	$field_set = mysqli_query($conn, $query);
// 	confirm_query($field_set);
// 	return $field_set;
// }
//
// function show_branches() {
// 	global $handle;
// 	$query = "SELECT * ";
// 	$query .= "FROM branches;";
// 	$field_set = mysqli_query($handle, $query);
// 	confirm_query($field_set);
// 	return $field_set;
// }
//
// function show_depts() {
// 	global $handle;
// 	$query = "SELECT * ";
// 	$query .= "FROM depts;";
// 	$field_set = mysqli_query($handle, $query);
// 	confirm_query($field_set);
// 	return $field_set;
// }
//
//
// function find_branch_by_id($bar) {
// 	global $handle;
// 	$foo = mysqli_real_escape_string($handle, $bar);
// 	$query = "SELECT * ";
// 	$query .= "FROM branches ";
// 	$query .= "WHERE id = '$foo' LIMIT 1;";
// 	$branch_id = mysqli_query($handle, $query);
// 	confirm_query($branch_id);
// 	return $branch_id;
// }
//
// function find_user_by_id($user_id) {
// 	global $handle;
// 	$safe_user_id = mysqli_real_escape_string($handle, $user_id);
//    	$query = "SELECT * FROM users WHERE id = {$safe_user_id} LIMIT 1;";
//     $user_set = mysqli_query($handle, $query);
// 	confirm_query($user_set);
// 	if($user = mysqli_fetch_assoc($user_set)) {
// 		return $user;
// 	} else {
// 		return null;
// 	}
// }
//
// function find_users() {
// 	global $handle;
//        	$query = "SELECT * FROM users WHERE admin = false ORDER BY branch_id ASC;";
//         $user_set = mysqli_query($handle, $query);
// 	confirm_query($user_set);
// 	return $user_set;
// }
//
// function find_users_by_dept($dept) {
// 	global $handle;
//   $query = "SELECT id FROM depts WHERE reparto = '$dept';";
//   $result = mysqli_query($handle, $query);
//   $array = mysqli_fetch_assoc($result);
//   $dept_id = $array['id'];
//   mysqli_free_result($result);
//  	$query = "SELECT * FROM users WHERE dept_id = $dept_id AND admin = false ORDER BY branch_id ASC;";
//   $user_set = mysqli_query($handle, $query);
// 	confirm_query($user_set);
// 	return $user_set;
// }
//
// function find_user_by_email($email) {
// 	global $handle;
// 	$safe_email = mysqli_real_escape_string($handle, $email);
// 	$query  = "SELECT * FROM users WHERE email = '{$safe_email}' LIMIT 1;";
// 	$user_set = mysqli_query($handle, $query);
// 	confirm_query($user_set);
// 	if($user = mysqli_fetch_assoc($user_set)) {
// 		return $user;
// 	} else {
// 		return null;
// 	}
// }
?>
