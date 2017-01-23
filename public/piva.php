<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->admin === 0) { redirect("admin.php"); }
if (!$logged_user->dept_id === 11 || !$logged_user->su === 0) { redirect("admin.php"); }

if (isset($_POST["cerca"])) {
	$piva = $_POST["piva"];
} else {
	$piva = "";
	// $piva = "14056331003";
}
?>
<?php include("../private/layouts/header.php"); ?>
<div id="main">
 <div id="navigation">
  <?php include("../private/layouts/logout_link.php"); ?>
     <a href="admin.php">&laquo; Torna indietro</a><br><br>
    <fieldset>
      <legend>Ricerca Partita IVA:</legend>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<table><tr>
			<td style="text-align: center; height:10px">
			  <input id="stringa" type="text" name="piva" value="<?php echo $piva; ?>"> </td>   </tr>
				<tr><td>
			</td></tr>
			</table>
	   <input type="submit" name="cerca" value="Cerca">
        </form>
    </fieldset>
  <ul class="pages">
 		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Ricarica</a></li>
     <!-- <li><a href="export.php?field1=<?php echo urlencode($field1); ?>&stringa1=<?php echo urlencode($stringa1); ?>&field2=<?php echo urlencode($field2); ?>&stringa2=<?php echo urlencode($stringa2); ?>&da_anno=<?php echo urlencode($da_anno); ?>&da_mese=<?php echo urlencode($da_mese); ?>&da_giorno=<?php echo urlencode($da_giorno); ?>&a_anno=<?php echo urlencode($a_anno); ?>&a_mese=<?php echo urlencode($a_mese); ?>&a_giorno=<?php echo urlencode($a_giorno); ?>&da_ora=<?php echo urlencode($da_ora); ?>&da_min=<?php echo urlencode($da_min); ?>&a_ora=<?php echo urlencode($a_ora); ?>&a_min=<?php echo urlencode($a_min); ?>&simbolo_connesso=<?php echo urlencode($simbolo_connesso); ?>&secs_connesso=<?php echo urlencode($secs_connesso); ?>&simbolo_squillo=<?php echo urlencode($simbolo_squillo); ?>&secs_squillo=<?php echo urlencode($secs_squillo); ?>">Esporta in Excel</a></li> -->
	</ul>
 </div>
 <div id="page">
	  <?php
				$yo = new Piva();
				$yo->partita_iva = $piva;
				$piva_results = $yo->find_by_piva();
				// $piva_results = $yo->find_all();
		?>
  <h2>Risultati: <?php echo count($piva_results); ?> </h2>
   <table id="tavola">
    <tr>
     <th style="text-align: center; width:120px">Partita IVA</th>
     <th style="text-align: center; width:120px">Denominazione Cliente</th>
     <th style="text-align: center; width:120px">Destinatario<br>Spedizione</th>
     <th style="text-align: center; width:120px">Indirizzo<br>Spedizione</th>
     <th style="text-align: center; width:120px">CAP<br>Spedizione</th>
     <th style="text-align: center; width:120px">Citta'<br>Spedizione</th>
     <th style="text-align: center; width:120px">Provincia<br>Spedizione</th>
     <th style="text-align: center; width:120px">Telefono</th>
     <th style="text-align: center; width:180px">Email</th>
    </tr>
		<?php
		foreach ($piva_results as $piva_result) {
			echo '<tr bgcolor="#f2f2f2">';
			echo '<td style="text-align: center">'.$piva_result->partita_iva.'</td>';
			echo '<td style="text-align: center">'.$piva_result->denominazione_cliente.'</td>';
			echo '<td style="text-align: center">'.$piva_result->destinatario_sped.'</td>';
			echo '<td style="text-align: center">'.$piva_result->indirizzo_sped.'</td>';
			echo '<td style="text-align: center">'.$piva_result->cap_sped.'</td>';
			echo '<td style="text-align: center">'.$piva_result->citta_sped.'</td>';
			echo '<td style="text-align: center">'.$piva_result->provincia_sped.'</td>';
			echo '<td style="text-align: center">'.$piva_result->telefono.'</td>';
			echo '<td style="text-align: center">'.$piva_result->email.'</td>';
			echo '</tr>';
		}
		?>
   </table>
  <br><br><br><br><br><br>
 </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
