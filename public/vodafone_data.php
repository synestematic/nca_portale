<?php
require_once("../private/initialize.php");

$all_files = scandir("../private/csvs/");
$stats = array();
// filter directory ONLY FOR csv files
foreach ($all_files as $file) {
    if (strpos($file, '.csv') !== false && strpos($file, '._') === false) {
        $stats[] = new CellStats($file);
    }
}

if (isset($_GET['raw']) && $_GET['raw'] === 'true') {
    $delimiter = '<br>';
    foreach ($stats as $stat) {
        echo 'DIPENDENTE: ' . $stat->first_name . ' ' . $stat->last_name . $delimiter;
        echo 'NUMERO DI CELLULARE: ' . $stat->number . $delimiter;
        echo 'TOTALE CHIAMATE: ' . count($stat->calls) . $delimiter;
        echo 'CHIAMATE ON WORK: ' . ( count($stat->calls) - count($stat->calls_off) ). $delimiter;
        echo 'CHIAMATE OFF WORK: ' . count($stat->calls_off) . $delimiter;

        // echo '<pre>';
        // print_r($stat->calls);
        // echo '</pre>';
        // echo '<pre>';
        // print_r($stat->calls_off);
        // echo '</pre>';

        print("===================================<br>");
    }
} else {
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=vodafone_data.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $tab = "\t";

    //Print column names
    echo 'Last Name' . $tab;
    echo 'First Name' . $tab;
    echo 'Mobile Number' . $tab;
    echo 'Total Calls last month' . $tab;
    echo 'Calls ON working hours' . $tab;
    echo 'Calls OFF working hours' . $tab;
    print("\n");

    foreach ($stats as $stat) {
        echo $stat->last_name . $tab;
        echo $stat->first_name . $tab;
        echo $stat->number . $tab;
        echo count($stat->calls) . $tab;
        echo ( count($stat->calls) - count($stat->calls_off) ). $tab;
        echo count($stat->calls_off) . $tab;
        print("\n");
    }
}
?>
