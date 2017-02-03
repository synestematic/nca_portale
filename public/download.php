<?php
require_once("../private/initialize.php");
use Icewind\SMB\Server;
require('/var/www/html/private/SMB-master/vendor/autoload.php');

if (!$session->is_logged_in()) { redirect("login.php"); }
$logged_user = User::find_by_id($_SESSION["user_id"]);
if (!$logged_user->is_branch && !$logged_user->is_agency) { redirect("admin.php"); }
// or any other necessary validations

$nas = new Server('10.4.4.250', 'portale', '76837683');
$share = $nas->getShare('FTP_BRANCHES');

if ( isset($_GET['foo']) && isset($_GET['bar']) ) {

    $messaggio = "";
    $resource_name = base64_decode($_GET['foo']);
    $giorno_richiesto = base64_decode($_GET['bar']);

    // CHECK IF DIR/DATE EXISTS
    $main_contents = $share->dir($logged_user->main_share.DS);
    foreach ($main_contents as $item) {
        if (strpos($item->getName(), $giorno_richiesto) !== false) {
            $messaggio = 'Risorsa[d] trovata.';
            break;
        } else {
            $messaggio = 'Risorsa[d] non trovata.';
        }
    }
    if ( $messaggio === 'Risorsa[d] non trovata.' ) { echo $messaggio; }
    if ( $messaggio === 'Risorsa[d] trovata.' ) {
        // CHECK IF FILE EXISTS
        $day_contents = $share->dir($logged_user->main_share.$giorno_richiesto.DS);
        foreach ($day_contents as $item) {
            if (strpos($item->getName(), $resource_name) !== false) {
                $messaggio = 'Risorsa[f] trovata.';
                break;
            } else {
                $messaggio = 'Risorsa[f] non trovata.';
            }
        }
        if ( $messaggio === 'Risorsa[f] non trovata.' ) { echo $messaggio; }
        if ( $messaggio === 'Risorsa[f] trovata.') {
          // IF FILE EXISTS THEN DOWNLOAD IT
          $target = TMP_PATH . DS . $resource_name;
          $share->get($logged_user->main_share. $giorno_richiesto . DS . $resource_name, $target);
          sleep(1);
          redirect( 'tmp/'.$resource_name );
          // echo $resource_name;
          // echo '<br>';
          // echo $logged_user->main_share. $giorno_richiesto . DS . $resource_name;
          // echo '<br>';
          // echo $target;
        }
    }
}

?>
