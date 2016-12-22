<?php
require_once("../private/initialize.php");


$branches = Branch::find_by_nb_number("0282950985");

echo $branches->filiale;
//
// if ($branches) {
//   foreach ($branches as $branch) { echo $branch->filiale; }
// } else { echo "niente"; }



?>
