<?php
class String {

	public $length;

	public static function ucfirst_rmunderscores($string) {
	  $string = str_replace("_", " ", $string);
	  $string = ucfirst($string);
	  return $string;
	}

}
?>
