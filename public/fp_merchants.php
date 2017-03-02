<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->admin === 0) { redirect("admin"); }
if (!$logged_user->dept_id === 11 || !$logged_user->su === 0) { redirect("admin"); }

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
     <a href="admin">&laquo; Torna indietro</a><br><br>
    <fieldset>
      <legend>Ricerca Partita IVA:</legend>
      <form action="" method="post">
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
		<?php
				$merchant = new Merchant();
				$merchant->partita_iva = $piva;
				$merchant_results = $merchant->find_by('Partita_IVA', $piva);
				// $merchant_results = $merchant->find_all();
		?>
		<li><a href="">Ricarica</a></li>
    <li><a target="_blank" href="export?a=<?php echo (isset($merchant_results[0])) ? base64_encode($merchant_results[0]->last_sql) : '" onclick="return validateExport()' ; ?>">Esporta in Excel</a></li>
	</ul>
 </div>
 <div id="page">
  <h2>Merchants: <?php echo count($merchant_results); ?> </h2>
   <table id="tavol">
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
		foreach ($merchant_results as $merchant_result) {
			echo '<tr bgcolor="#f2f2f2">';
			echo '<td style="text-align: center">'.$merchant_result->partita_iva.'</td>';
			echo '<td style="text-align: center">'.$merchant_result->denominazione_cliente.'</td>';
			echo '<td style="text-align: center">'.$merchant_result->destinatario_sped.'</td>';
			echo '<td style="text-align: center">'.$merchant_result->indirizzo_sped.'</td>';
			echo '<td style="text-align: center">'.$merchant_result->cap_sped.'</td>';
			echo '<td style="text-align: center">'.$merchant_result->citta_sped.'</td>';
			echo '<td style="text-align: center">'.$merchant_result->provincia_sped.'</td>';
			echo '<td style="text-align: center">'.$merchant_result->telefono.'</td>';
			echo '<td style="text-align: center">'.$merchant_result->email.'</td>';
			echo '</tr>';
		}
		?>
   </table>
  <br><br><br><br><br><br>
 </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
