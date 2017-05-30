<?php
class String {

	public $length;

	public static function ucfirst_rmunderscores($string) {
		$string = str_replace("_", " ", $string);
		// $string = ucfirst($string);
		$string = ucwords($string);
		return $string;
	}

	public static function format_address($string) {
		$string = str_replace(", ", "</br>", $string);
		return $string;
	}

}
?>
