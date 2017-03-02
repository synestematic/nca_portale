<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }
$logged_user = User::find_by_id($_SESSION["user_id"]);

if ( $_GET["a"] === "" || !$_GET["a"] ) {
  echo "Richiesta non riconosciuta." ;
  exit ;
}

//$filename = 'export';
if ( strpos($_SERVER["HTTP_REFERER"] , 'fp') !== false ) {
    $fp_db->open_connection();
    $result = $fp_db->query(base64_decode($_GET["a"]));
    if ( strpos($_SERVER["HTTP_REFERER"] , 'all') !== false ) {
        $filename = 'Finproget_rawdata';
    }
    if ( strpos($_SERVER["HTTP_REFERER"] , 'warm') !== false ) {
        $filename = 'Warm_vehicles_'.strftime("%F_%H%M");
    }
    if ( strpos($_SERVER["HTTP_REFERER"] , 'atti') !== false ) {
        $filename = 'Atti_ricevuti_'.strftime("%F_%H%M");
    }
    if ( strpos($_SERVER["HTTP_REFERER"] , 'merchants') !== false ) {
        $filename = 'Indirizzi_merchants_'.strftime("%F_%H%M");
    }
}
if ( strpos($_SERVER["HTTP_REFERER"] , 'chiamate') !== false ) {
    $result = $local_db->query(base64_decode($_GET["a"]));
    $filename = 'Chiamate_bc_'.strftime("%F_%H%M");
}
//the problem with this is that i cant escape the values

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=".$filename.".xls");
header("Pragma: no-cache");
header("Expires: 0");

//Prints column names as MySQL fields
while ( $property = mysqli_fetch_field($result) ) {
    echo $property->name . "\t";
}
print("\n");

//Prints values
while ( $row = mysqli_fetch_row($result) ) {
    $schema_insert = "";
    for ( $j=0 ; $j<mysqli_num_fields($result) ; $j++ ) {
        if ( !isset( $row[$j] ) )
            // $schema_insert .= "NULL"."\t";
            $schema_insert .= ""."\t";
        elseif ( $row[$j] != "" )
            $schema_insert .= "$row[$j]"."\t";
        else
            $schema_insert .= ""."\t";
    }
    $schema_insert = str_replace("\t"."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print( trim($schema_insert) );
    print("\n");
}
?>
