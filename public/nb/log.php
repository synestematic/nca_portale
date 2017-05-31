<?php
header("HTTP/1.1 200 OK");
header("content-type: application/xml; charset=UTF-8");
require_once("../../private/initialize.php");

if (!empty($_GET)) {

	function call_direction() {
	    if (strpos($_GET['z'], 'in') !== false) {
	        $direzione = 'inbound';
	    } else if (strpos($_GET['z'], 'out') !== false) {
	        $direzione = 'outbound';
	    }
	    return $direzione;
	}

	if (strpos($_GET['z'], 'ops') !== false) {
	    $direzione = call_direction();
	    $reparto = 'ops';
	    $table = '_ops';
	} else if (strpos($_GET['z'], 'bc') !== false) {
	    $direzione = call_direction();
	    $reparto = 'bc';
	    $table = '_bc';
	}

	$weekday_chiamata = strftime("%u", $_GET['a']);
	$ora_chiamata = strftime("%H", $_GET['a']);
	if ( ($ora_chiamata >= Dept::orario_apertura($reparto, $weekday_chiamata)) && ($ora_chiamata < Dept::orario_chiusura($reparto, $weekday_chiamata)) ) {
	// if ( ($ora_chiamata >= Dept::orario_apertura($reparto)) && ($ora_chiamata < Dept::orario_chiusura($reparto)) ) {

		$call = new Chiamata();

		$mysql_date = strftime("%Y-%m-%d", $_GET['a']);
		$call->data_chiamata  = $mysql_date;

		$mysql_time1 = strftime("%H:%M:%S", $_GET['a']);
		$call->orario_chiamata = $mysql_time1;

		$mysql_time2 = strftime("%H:%M:%S", $_GET['c']);
		$call->orario_hangup = $mysql_time2;

		$call->tempo_squillo = $_GET['d'];
		$call->tempo_connesso	= $_GET['e'];
		if (isset($_GET['b'])) {
			$call->estensione = $_GET['b'];
		}
		$call->utente = $_GET['f'];
		$call->da_numero = $_GET['g'];
		$call->numero_chiamato = $_GET['h'];
		$call->numero_connesso = $_GET['i'];
		$call->gruppo = $_GET['j'];
		$call->tipo_connessione = $direzione." ". $_GET['k']; // works only for inbounds
		$call->esito_chiamata = $_GET['l'];
		$call->datetime_inserzione = strftime("%Y-%m-%d %H:%M:%S");

		$duplicate_entry = $call->check_for_duplicate_into("chiamate".$table);
		if (count($duplicate_entry) != 0) {
			$call->comment = ' - duplicate entry - ';
			$call->create_into("extra".$table);
		} else {
			$call->comment =  ' direzione= '.$direzione.' reparto= '.$reparto.' table= '.$table;
			$call->create_into("chiamate".$table);
		}
	}
}
$call->return_XML();

?>
