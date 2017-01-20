<?php
class DropdownYears extends Dropdown {

	function __construct() {
		for ($i = 2016; $i <= 2017; $i++) {
			$this->options[$i] =  $i;
		}
	}

}

?>
