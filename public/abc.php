<?php
require_once("../private/initialize.php");

// $users = Chiamata::find_by_id_from("362", "chiamate_ops");
// echo $users->utente;
// echo '<br>';

// $call = new Chiamata();
// $call->data_chiamata = "";
// $call->orario_chiamata = "";
// $call->orario_hangup = "";
// $call->utente = "";
//
// $duplicate_entry = $call->check_for_duplicate_into("chiamate_ops");
// if (count($duplicate_entry) != 0) {
//   $call->create_into("extra_ops");
//   echo 'extra';
// } else {
//   $call->create_into("chiamate_ops");
//   echo 'nuova';
// }
//

$foo = new Dropdown();
$foo->selected = "tipo_connessione";
$foo->option1 = "opzione 1";
$foo->option2 = "opzione 2";
$foo->option3 = "opzione 3";

$foo->menu();
$vrs = get_class($foo);
echo $vrs;

$vars = get_class_vars("Dropdown");
foreach ($vars as $var => $field) {
    echo $var[$field];
}

// $vars = $foo->get_options();
// foreach ($vars as $var => $value) {
//     echo $var[$value];
// }
//

?>
