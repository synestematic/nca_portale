<?php
header("HTTP/1.1 200 OK");
header("content-type: application/xml; charset=UTF-8");
require_once("../../private/initialize.php");

$ora_inserzione = strftime("%H");
if (($ora_inserzione >= 07) && ($ora_inserzione <= 21) && isset($_GET['a'])) {

	$call = new Chiamata();

	$mysql_date = strftime("%Y-%m-%d", $_GET['a']);
	$call->data_chiamata  = $mysql_date;

	$mysql_time1 = strftime("%H:%M:%S", $_GET['a']);
	$call->orario_chiamata = $mysql_time1;

	$mysql_time2 = strftime("%H:%M:%S", $_GET['c']);
	$call->orario_hangup = $mysql_time2;

	$call->tempo_squillo = $local_db->escape_value($_GET['d']);
	$call->tempo_connesso	= $local_db->escape_value($_GET['e']);
	$call->estensione = $local_db->escape_value($_GET['b']);
	$call->utente = $local_db->escape_value($_GET['f']);
	$call->da_numero = $local_db->escape_value($_GET['g']);
	$call->numero_chiamato = $local_db->escape_value($_GET['h']);
	$call->numero_connesso = $local_db->escape_value($_GET['i']);
	//$ConnectedGroup = $local_db->escape_value($_GET['j']);
	$call->tipo_connessione = "inbound " . $local_db->escape_value($_GET['k']); // works only for inbounds
	$call->esito_chiamata = $local_db->escape_value($_GET['l']);
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

echo "<records>\n";
echo "  <record>\n";
//echo "    <field>"."false"."</field>\n";
echo "    <CallStartEpoch>".$_GET['a']."</CallStartEpoch>\n";
echo "    <CallEndEpoch>".$_GET['c']."</CallEndEpoch>\n";
echo "    <CallRingSeconds>".$_GET['d']."</CallRingSeconds>\n";
echo "    <CallTalkSeconds>".$_GET['e']."</CallTalkSeconds>\n";
echo "    <CallerUserAgent>".$_GET['f']."</CallerUserAgent>\n";
echo "    <CallerNumber>".$_GET['g']."</CallerNumber>\n";
echo "    <DialledNumber>".$_GET['h']."</DialledNumber>\n";
echo "    <ConnectedNumber>".$_GET['i']."</ConnectedNumber>\n";
echo "    <ConnectedGroup>".$_GET['j']."</ConnectedGroup>\n";
echo "    <ConnectedType>".$_GET['k']."</ConnectedType>\n";
echo "    <HangupCause>".$_GET['l']."</HangupCause>\n";
echo "    <Comment>".$comment."</Comment>\n";
echo "  </record>\n";
echo "</records>\n";
//print_r($_GET);

?>
