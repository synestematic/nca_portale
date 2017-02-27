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

class DropdownMins extends Dropdown {
	function __construct() {
		for ($i = 0; $i <= 59; $i++) {
			$i_padded = sprintf("%02d", $i);
			$this->options[$i] =  $i_padded;
		}
	}
}

class DropdownHours extends Dropdown {
	function __construct() {
		for ($i = 0; $i <= 23; $i++) {
			$i_padded = sprintf("%02d", $i);
			$this->options[$i] =  $i_padded;
		}
	}
}

class DropdownDays extends Dropdown {
	function __construct() {
		for ($i = 1; $i <= 31; $i++) {
			$i_padded = sprintf("%02d", $i);
			$this->options[$i] =  $i_padded;
		}
	}
}

class DropdownMonths extends Dropdown {
	function __construct() {
		for ($i = 1; $i <= 12; $i++) {
			$i_padded = sprintf("%02d", $i);
			$this->options[$i] =  $i_padded;
		}
	}
}

class DropdownYears extends Dropdown {
	function __construct() {
		for ($i = 2016; $i <= 2017; $i++) {
			$this->options[$i] =  $i;
		}
	}
}

class DropdownSymbols extends Dropdown {
	function __construct() {
		$this->options = array('*','>','=','<');
	}
}

class DropdownFields extends Dropdown {
	function __construct() {
		$this->options = array('', 'id','tipo_connessione','estensione','utente', 'da_numero', 'numero_chiamato' ,'numero_connesso', 'esito_chiamata');
	}
}

class DropdownCustom extends Dropdown {
	function __construct($option_array) {
		$this->options = $option_array;
	}
}

?>
