<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->admin == 0) { redirect("admin.php"); }

//echo '<pre>'; print_r($_GET); echo '</pre>';

if (!$_GET["field1"]) { redirect("chiamate.php"); }
//if (!$_GET["stringa1"]) { redirect("chiamate.php"); }
if (!$_GET["field2"]) { redirect("chiamate.php"); }
//if (!$_GET["stringa2"]) { redirect("chiamate.php"); }
if (!$_GET["da_giorno"]) { redirect("chiamate.php"); }
if (!$_GET["da_mese"]) { redirect("chiamate.php"); }
if (!$_GET["da_anno"]) { redirect("chiamate.php"); }
if (!$_GET["a_giorno"]) { redirect("chiamate.php"); }
if (!$_GET["a_mese"]) { redirect("chiamate.php"); }
if (!$_GET["a_anno"]) { redirect("chiamate.php"); }
if (!$_GET["da_ora"]) { redirect("chiamate.php"); }
if (!$_GET["da_min"]) { redirect("chiamate.php"); }
if (!$_GET["a_ora"]) { redirect("chiamate.php"); }
if (!$_GET["a_min"]) { redirect("chiamate.php"); }
if (!$_GET["simbolo_connesso"]) { redirect("chiamate.php"); }
//if (!$_GET["secs_connesso"]) { redirect("chiamate.php"); }
if (!$_GET["simbolo_squillo"]) { redirect("chiamate.php"); }
//if (!$_GET["secs_squillo"]) { redirect("chiamate.php"); }

$result = select_records($logged_user->table, $_GET["field1"], $_GET["stringa1"], $_GET["field2"], $_GET["stringa2"], $_GET["da_anno"], $_GET["da_mese"], $_GET["da_giorno"], $_GET["a_anno"], $_GET["a_mese"], $_GET["a_giorno"], $_GET["da_ora"], $_GET["da_min"], $_GET["a_ora"], $_GET["a_min"], $_GET["simbolo_connesso"], $_GET["secs_connesso"], $_GET["simbolo_squillo"], $_GET["secs_squillo"]);

//header info for browser
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=export.xls");
header("Pragma: no-cache");
header("Expires: 0");

/*******Excel Formatting*******/
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character

//Prints column names as MySQL fields
while ($property = mysqli_fetch_field($result)) {
    echo $property->name . "\t";
}
print("\n");

//Prints values
while($row = mysqli_fetch_row($result)) {
    $schema_insert = "";
    for($j=0; $j<mysqli_num_fields($result);$j++)
    {
        if(!isset($row[$j]))
            $schema_insert .= "NULL".$sep;
        elseif ($row[$j] != "")
            $schema_insert .= "$row[$j]".$sep;
        else
            $schema_insert .= "".$sep;
    }
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}
?>
