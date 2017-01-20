<?php
class Dropdown {

	public $id = '';
	public $name = '';
	public $value = '';

	public $selected = '';
	public $options = array();

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
		// foreach ($vars as $var) {
		//     echo $var;
		// }
		echo $vars;
		return $option_array;
	}

	public function menu() {
		echo '<select id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" >';
		foreach ($this->options as $option) {
			echo ($this->selected == $option) ? '<option selected>' : '<option>';
			echo $option;
			echo '</option>';
		}
		echo '</select>';
	}

}

?>
