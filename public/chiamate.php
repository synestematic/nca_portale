<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->admin == 0) { redirect("admin.php"); }

if (isset($_POST["cerca"])) {
	$stringa1 = $_POST["stringa1"];
	$field1 = $_POST["field1"];
	$field2 = $_POST["field2"];
	$stringa2 = $_POST["stringa2"];
	$da_giorno = $_POST["da_giorno"];
	$da_mese = $_POST["da_mese"];
	$da_anno = $_POST["da_anno"];
	$a_giorno = $_POST["a_giorno"];
	$a_mese = $_POST["a_mese"];
	$a_anno = $_POST["a_anno"];
	$da_ora = $_POST["da_ora"];
	$da_min = $_POST["da_min"];
	$a_ora = $_POST["a_ora"];
	$a_min = $_POST["a_min"];
	$secs_connesso = $_POST["secs_connesso"];
//	$simbolo_connesso = ($_POST["secs_connesso"] == '') ? '*' : $_POST["simbolo_connesso"];
	$simbolo_connesso = (is_numeric($_POST["secs_connesso"])) ? $_POST["simbolo_connesso"] : '*';
	$secs_squillo = $_POST["secs_squillo"];
	$simbolo_squillo = (is_numeric($_POST["secs_squillo"])) ? $_POST["simbolo_squillo"] : '*';
} else {
	$stringa1 = "";
	$field1 = "utente";
	$stringa2 = "";
	$field2 = "";
	$da_giorno = strftime("%d");
	$da_mese = strftime("%m");
	$da_anno = strftime("%Y");
	$a_giorno = strftime("%d");
	$a_mese = strftime("%m");
	$a_anno = strftime("%Y");
	$da_ora = "08";
	$da_min = "00";
	$a_ora = "21";
	$a_min = "00";
	$simbolo_connesso = "*";
	$secs_connesso = "";
	$simbolo_squillo = "*";
	$secs_squillo = "";
}
?>
<?php include("../private/layouts/header.php"); ?>
<div id="main">
 <div id="navigation">
  <?php include("../private/layouts/logout_link.php"); ?>
     <a href="admin.php">&laquo; Torna indietro</a><br><br>
    <fieldset>
      <legend>Ricerca:</legend>
      <form action="chiamate.php" method="post">
			<table><tr>
			<td style="text-align: center; height:10px">
			  <input id="stringa" type="text" name="stringa1" value="<?php echo $stringa1; ?>"> </td>   </tr>
				<tr><td>
				<?php
				echo '<select id="field" name="field1" value="" >';
				echo ($field1 == 'id') ? '<option selected>' : '<option>';
				echo 'id</option>';
				echo ($field1 == 'tipo_connessione') ? '<option selected>' : '<option>';
				echo 'tipo_connessione</option>';
				echo ($field1 == 'estensione') ? '<option selected>' : '<option>';
				echo 'estensione</option>';
				echo ($field1 == 'utente') ? '<option selected>' : '<option>';
				echo 'utente</option>';
				echo ($field1 == 'da_numero') ? '<option selected>' : '<option>';
				echo 'da_numero</option>';
				echo ($field1 == 'numero_chiamato') ? '<option selected>' : '<option>';
				echo 'numero_chiamato</option>';
				echo ($field1 == 'numero_connesso') ? '<option selected>' : '<option>';
				echo 'numero_connesso</option>';
				echo ($field1 == 'esito_chiamata') ? '<option selected>' : '<option>';
				echo 'esito_chiamata</option>';
		    echo '</select>';
				?>
			</td>
		  </tr>
			<tr><td style="text-align: center; height:10px"></td></tr>
			<tr>
			<td style="text-align: center; height:10px">
			  <input id="stringa" type="text" name="stringa2" value="<?php echo ($field2 == '') ? '' : $stringa2 ; ?>">
			</td>
			</tr>
			<tr>
			<td>







			  <select id="field" name="field2" value="" ><br><br>
				<?php echo ($field2 == '') ? '<option selected>' : '<option>'; ?>
				</option>
				<?php echo ($field2 == 'id') ? '<option selected>' : '<option>'; ?>
				id</option>
				<?php echo ($field2 == 'tipo_connessione') ? '<option selected>' : '<option>'; ?>
				tipo_connessione</option>
				<?php echo ($field2 == 'utente') ? '<option selected>' : '<option>'; ?>
				utente</option>
				<?php echo ($field2 == 'da_numero') ? '<option selected>' : '<option>'; ?>
				da_numero</option>
				<?php echo ($field2 == 'numero_chiamato') ? '<option selected>' : '<option>'; ?>
				numero_chiamato</option>
				<?php echo ($field2 == 'numero_connesso') ? '<option selected>' : '<option>'; ?>
				numero_connesso</option>
				<?php echo ($field2 == 'esito_chiamata') ? '<option selected>' : '<option>'; ?>
				esito_chiamata</option>
		          </select>
			</td></tr>
			</table>
		          <br>Dal:
				<table>
				<tr><th></th><th></th><th></th></tr>
				<tr>
				<td>
					<?php
					$yo = new DropdownDays();
					$yo->selected = $da_giorno;
					$yo->id = "data";
					$yo->name = "da_giorno";
					$yo->menu();
							// echo '<select name="da_giorno" id="data">';
		          // for ($i = 1; $i <= 31; $i++) {
		          // 	echo ($i == $da_giorno) ? "<option id='font' selected>" : "<option id='font'>";
		          //   echo $i;
		          //   echo "</option>";
		          // }
							// echo '</select>';
				  ?>
				</td>
				<td>
					<?php
					$yo = new DropdownMonths();
					$yo->selected = $da_mese;
					$yo->id = "data";
					$yo->name = "da_mese";
					$yo->menu();
							// echo '<select name="da_mese" id="data">';
		          // for ($i = 1; $i <= 12; $i++) {
		          // 	echo ($i == $da_mese) ? "<option id='font' selected>" : "<option id='font'>";
		          //   echo $i;
		          //   echo "</option>";
		          // }
							// echo '</select>';
				  ?>
				</td>
				<td>
					<?php
					$yo = new DropdownYears();
					$yo->selected = $da_anno;
					$yo->id = "anno";
					$yo->name = "da_anno";
					$yo->menu();
							// echo '<select name="da_anno" id="anno">';
		          // for ($i = 2016; $i <= 2020; $i++) {
		          // 	echo ($i == $da_anno) ? "<option id='font' selected>" : "<option id='font'>";
		          //   echo $i;
		          //   echo "</option>";
		          // }
							// echo '</select>';
				  ?>
				</td>
				</tr>
				</table>
Al:
				<table>
		<tr><th></th><th></th><th></th></tr>
		<tr>
		<td>
			<?php
			$yo = new DropdownDays();
			$yo->selected = $a_giorno;
			$yo->id = "data";
			$yo->name = "a_giorno";
			$yo->menu();
					// echo '<select name="a_giorno" id="data">';
          // for ($i = 1; $i <= 31; $i++) {
          // 	echo ($i == $a_giorno) ? "<option id='font' selected>" : "<option id='font'>";
          //   echo $i;
          //   echo "</option>";
          // }
					// echo '</select>';
		  ?>
		</td>
		<td>
			<?php
			$yo = new DropdownMonths();
			$yo->selected = $a_mese;
			$yo->id = "data";
			$yo->name = "a_mese";
			$yo->menu();
					// echo '<select name="a_mese" id="data">';
          // for ($i = 1; $i <= 12; $i++) {
          // 	echo ($i == $a_mese) ? "<option id='font' selected>" : "<option id='font'>";
          //   echo $i;
          //   echo "</option>";
          // }
					// echo '</select>';
		  ?>
		</td>
		<td>
			<?php
			$yo = new DropdownYears();
			$yo->selected = $a_anno;
			$yo->id = "anno";
			$yo->name = "a_anno";
			$yo->menu();
					// echo '<select name="a_anno" id="anno">';
          // for ($i = 2016; $i <= 2020; $i++) {
          // 	echo ($i == $a_anno) ? "<option id='font' selected>" : "<option id='font'>";
          //   echo $i;
          //   echo "</option>";
          // }
					// echo '</select>';
		  ?>
		</td>
		</tr>
		</table>
	<br>
		<table>
		<tr><th></th><th></th><th></th>
		</tr>
		<tr>
			<td>Dalle:</td>
		<td>
		  <?php
			$yo = new DropdownHours();
			$yo->selected = $da_ora;
			$yo->id = "data";
			$yo->name = "da_ora";
			$yo->menu();
			// echo '<select name="da_ora" id="data">';
			// for ($i = 8; $i <= 21; $i++) {
	    // 	$i_padded = sprintf("%02d", $i);
    	//  	echo ($i_padded == $da_ora) ? "<option id='font' selected>" : "<option id='font'>";
			// 	echo $i_padded;
		  //   echo "</option>";
			// }
			// echo '</select>';
		  ?>
		  </td>
		<td>
			<?php
			$yo = new DropdownMins();
			$yo->selected = $da_min;
			$yo->id = "data";
			$yo->name = "da_min";
			$yo->menu();
			// minutes_menu($da_min, "da_min");
			?>
		</td>
		</tr>
		<tr> <td>Alle:</td>
		<td>
			<?php
			$yo = new DropdownHours();
			$yo->selected = $a_ora;
			$yo->id = "data";
			$yo->name = "a_ora";
			$yo->menu();
			// echo '<select name="a_ora" id="data">';
		  // for ($i = 8; $i <= 21; $i++) {
			// 	$i_padded = sprintf("%02d", $i);
			// 	echo ($i_padded == $a_ora) ? "<option id='font' selected>" : "<option id='font'>";
			// 	echo $i_padded;
			// 	echo "</option>";
		  // }
			// echo '</select>';
		  ?>
		</td>
		<td>
			<?php
			$yo = new DropdownMins();
			$yo->selected = $a_min;
			$yo->id = "data";
			$yo->name = "a_min";
			$yo->menu();
			// minutes_menu($a_min, "a_min");
			?>
		</td>
		</tr>
</table>
<br>
	<table>
		<tr><td style="text-align: left"> Tempo connesso:
		</td></tr>
		</table>
		<table>
		<tr><td> </td></tr>
		<tr>
			<td style="text-align: right">
				<?php
				$yo = new DropdownSymbols();
				$yo->selected = $simbolo_connesso;
				$yo->name = "simbolo_connesso";
				$yo->menu();
				//symbol_menu($simbolo_connesso, "simbolo_connesso");
 				?>
			</td>
			<td>
		<input id="secs" type="text" name="secs_connesso" value="<?php echo ($simbolo_connesso == '*') ? '' : $secs_connesso; ?>">
		</td></tr>
	</table><table>
		<tr><td style="text-align: left"> Tempo squillo:
		</td></tr>
		</table>
		<table>
		<tr><td> </td></tr>
		<tr>
			<td style="text-align: right">
				<?php
				$foo = new DropdownSymbols();
				$foo->selected = $simbolo_squillo;
				$foo->name = "simbolo_squillo";
				$foo->menu();
				// symbol_menu($simbolo_squillo, "simbolo_squillo");
				?>
			</td>
			<td>
		<input id="secs" type="text" name="secs_squillo" value="<?php echo ($simbolo_squillo == '*') ? '' : $secs_squillo; ?>">
		</td></tr>
</table>
<br>
     <input type="submit" name="cerca" value="Cerca">
        </form>
    </fieldset>
	<?php	if ($field2 == '') { $field2 = $field1;	$stringa2 = $stringa1; } ?>
  <ul class="pages">
 		<li><a href="chiamate.php">Ricarica</a></li>
     <li><a href="export.php?field1=<?php echo urlencode($field1); ?>&stringa1=<?php echo urlencode($stringa1); ?>&field2=<?php echo urlencode($field2); ?>&stringa2=<?php echo urlencode($stringa2); ?>&da_anno=<?php echo urlencode($da_anno); ?>&da_mese=<?php echo urlencode($da_mese); ?>&da_giorno=<?php echo urlencode($da_giorno); ?>&a_anno=<?php echo urlencode($a_anno); ?>&a_mese=<?php echo urlencode($a_mese); ?>&a_giorno=<?php echo urlencode($a_giorno); ?>&da_ora=<?php echo urlencode($da_ora); ?>&da_min=<?php echo urlencode($da_min); ?>&a_ora=<?php echo urlencode($a_ora); ?>&a_min=<?php echo urlencode($a_min); ?>&simbolo_connesso=<?php echo urlencode($simbolo_connesso); ?>&secs_connesso=<?php echo urlencode($secs_connesso); ?>&simbolo_squillo=<?php echo urlencode($simbolo_squillo); ?>&secs_squillo=<?php echo urlencode($secs_squillo); ?>">Esporta in Excel</a></li>
	</ul>
 </div>
 <div id="page">
	  <?php
       $chiamata_set = Chiamata::select_records($logged_user->table, $field1, $stringa1, $field2, $stringa2, $da_anno, $da_mese, $da_giorno, $a_anno, $a_mese, $a_giorno, $da_ora, $da_min, $a_ora, $a_min, $simbolo_connesso, $secs_connesso, $simbolo_squillo, $secs_squillo);
		?>
  <h2>Risultati: <?php echo count($chiamata_set); ?> </h2>
   <table id="tavola">
    <tr>
     <th style="text-align: center; width:40px">ID</th>
     <th style="text-align: center; width:80px">Tipo<br>connessione</th>
		 <?php echo ($logged_user->table == "chiamate_ops") ? '<th style="text-align: center; width:80px">Estensione</th>' : '' ; ?>
     <th style="text-align: center; width:80px">Utente</th>
     <th style="text-align: center; width:80px">Data<br>chiamata</th>
     <th style="text-align: center; width:60px">Orario<br>chiamata</th>
     <th style="text-align: center; width:60px">Orario<br>hangup</th>
     <th style="text-align: center; width:60px">Tempo<br>squillo</th>
     <th style="text-align: center; width:60px">Tempo<br>connesso</th>
     <th style="text-align: center; width:100px">da<br>Numero</th>
     <th style="text-align: center; width:100px">Numero<br>chiamato</th>
     <th style="text-align: center; width:100px">Numero<br>connesso</th>
     <th style="text-align: center; width:140px">Esito<br>chiamata</th>
    </tr>
		<?php
		foreach ($chiamata_set as $chiamata) {
			echo (strpos($chiamata->tipo_connessione, 'inbound') !== false) ? '<tr bgcolor="#f2f2f2">' : '<tr bgcolor="#cccccc">';
				echo '<td style="text-align: center">'.$chiamata->id.'</td>';
				echo '<td style="text-align: center">'.$chiamata->tipo_connessione.'</td>';

				echo ($logged_user->table == "chiamate_ops") ? '<td style="text-align: center">'.$chiamata->estensione.'</td>' : '' ;

				echo '<td style="text-align: center">'.$chiamata->utente.'</td>';
				echo '<td style="text-align: center">'.$chiamata->data_chiamata.'</td>';
				echo '<td style="text-align: center">'.$chiamata->orario_chiamata.'</td>';
				echo '<td style="text-align: center">'.$chiamata->orario_hangup.'</td>';
				echo '<td style="text-align: center">'; secs_to_his($chiamata->tempo_squillo); echo '</td>';
				echo '<td style="text-align: center">'; secs_to_his($chiamata->tempo_connesso); echo '</td>';

				echo '<td style="text-align: center">';
				// displays branch->filiale instead of number if found
				$branches = Branch::find_by_nb_number($chiamata->da_numero);
				if ($branches) {
					foreach ($branches as $branch) { echo $branch->filiale; }
				} else { echo $chiamata->da_numero; }
				echo '</td>';

				echo '<td style="text-align: center">';
				// dqua non funziona per il +39
				// $branches = Branch::find_by_nb_number2($chiamata->numero_chiamato);
				// if ($branches) {
				//  foreach ($branches as $branch) {
				// 		echo $branch->filiale;
				//  }
				// } else {
					echo $chiamata->numero_chiamato;
				// }
				echo '</td>';

				echo '<td style="text-align: center">'.$chiamata->numero_connesso.'</td>';
				echo '<td style="text-align: center">'.$chiamata->esito_chiamata.'</td>';
			echo '</tr>';
		}
		?>
   </table>
  <br><br><br><br><br><br>
 </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
