<?php
class DropdownHours extends Dropdown {

	function __construct() {
		for ($i = 0; $i <= 23; $i++) {
			$i_padded = sprintf("%02d", $i);
			$this->options[$i] =  $i_padded;
		}
	}

}

?>
