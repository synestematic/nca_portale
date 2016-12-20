<?php
class Dropdown {

	public $selected;

	public $option1;
	public $option2;
	public $option3;
	public $option4;
	public $option5;
	public $option6;
	public $option7;
	public $option8;
	public $option9;
	public $option0;

	function __construct() {

	}

	private static function instantiate() {
		$object = new self;

		return $object;
	}

	//private function get_options() {
	function get_options() {
		$option_array = array();
		$vars = get_object_vars($this);
		foreach ($vars as $var) {
		  if (strpos($var, 'opzione') !== false) {
		    echo $var;
				$option_array[$var] = $var;
		  }
		}
		return $option_array;
	}


	public function menu() {

		echo '<select id="field" name="field1" value="" >';
		echo ($this->selected_option == 'id') ? '<option selected>' : '<option>';
		echo 'id</option>';
		echo ($this->selected_option == 'tipo_connessione') ? '<option selected>' : '<option>';
		echo 'tipo_connessione</option>';
		echo '</select>';

	}

}

?>
