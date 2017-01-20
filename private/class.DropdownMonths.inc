<?php
class DropdownMonths extends Dropdown {

	function __construct() {
		for ($i = 1; $i <= 12; $i++) {
			$i_padded = sprintf("%02d", $i);
			$this->options[$i] =  $i_padded;
		}
	}

}

?>
