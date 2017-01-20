<?php
require_once("../private/initialize.php");
// $branches = Branch::find_by_nb_number("0282950985");
// echo $branches->filiale;
// echo '<br>';

// $page = $_GET["page"];
// include($page);

$yo = new DropdownSymbols();
// echo $yo->options[31];
$yo->menu();


?>
