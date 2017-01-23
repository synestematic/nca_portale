<?php
header("HTTP/1.1 200 OK");
header("content-type: application/xml; charset=UTF-8");
require_once("../../private/initialize.php");

$ora_inserzione = strftime("%H");
if (($ora_inserzione >= 09) && ($ora_inserzione <= 18) && isset($_GET['a'])) {

	$call = new Chiamata();

	$mysql_date = strftime("%Y-%m-%d", $_GET['a']);
	$call->data_chiamata  = $mysql_date;

	$mysql_time1 = strftime("%H:%M:%S", $_GET['a']);
	$call->orario_chiamata = $mysql_time1;

	$mysql_time2 = strftime("%H:%M:%S", $_GET['c']);
	$call->orario_hangup = $mysql_time2;

	$call->tempo_squillo = $_GET['d'];
	$call->tempo_connesso	= $_GET['e'];
	$call->utente = $_GET['f'];
	$call->da_numero = $_GET['g'];
	$call->numero_chiamato = $_GET['h'];
	$call->numero_connesso = $_GET['i'];
	//$CallerGroup = $_GET['j'];
	$call->tipo_connessione = "outbound " . $_GET['k']; // works only for inbounds
	$call->esito_chiamata = $_GET['l'];
	$call->datetime_inserzione = strftime("%Y-%m-%d %H:%M:%S");

	$duplicate_entry = $call->check_for_duplicate_into("chiamate_ops");
	if (count($duplicate_entry) != 0) {
	  $call->create_into("extra_ops");
		$comment = ' - duplicate entry - ';
	} else {
	  $call->create_into("chiamate_ops");
		$comment =  ' - new entry - ';
	}
}

// THIS NEEDS TO BE SENT BACK ONLY IF THE CALL IS LOGGED CORRECTLY

echo "<records>\n";
echo "  <record>\n";
echo "    <CallStartEpoch>".$_GET['a']."</CallStartEpoch>\n";
echo "    <CallEndEpoch>".$_GET['c']."</CallEndEpoch>\n";
echo "    <CallRingSeconds>".$_GET['d']."</CallRingSeconds>\n";
echo "    <CallTalkSeconds>".$_GET['e']."</CallTalkSeconds>\n";
echo "    <CallerUserAgent>".$_GET['f']."</CallerUserAgent>\n";
echo "    <CallerNumber>".$_GET['g']."</CallerNumber>\n";
echo "    <DialledNumber>".$_GET['h']."</DialledNumber>\n";
echo "    <ConnectedNumber>".$_GET['i']."</ConnectedNumber>\n";
echo "    <CallerGroup>".$_GET['j']."</CallerGroup>\n";
echo "    <ConnectedType>".$_GET['k']."</ConnectedType>\n";
echo "    <HangupCause>".$_GET['l']."</HangupCause>\n";
echo "    <Comment>".$comment."</Comment>\n";
echo "  </record>\n";
echo "</records>\n";

?>
