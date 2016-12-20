<?php
require_once("../private/initialize.php");

// remove once fully gone to OOP
$handle = mysqli_connect("localhost", "natterbox", "q1w2e3r4", "nca");
if(mysqli_connect_errno()) {
	die ("DB connection failed: " . mysqli_connect_error() .	" (" . mysqli_connect_errno() . ")" );
}
///////////////////////////////
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->admin == 1) { redirect("admin.php"); }

?>
<?php
$message = '';
$foo = $logged_user->email;
$array = verify_status_proposte($foo);

// si estas esperando HQ podes ver los datos de la propuesta
if ($array['stato'] == 'in attesa HQ') {

//	echo '<pre>'; print_r($array); echo '</pre>';
	$id = $array["id"];
	$vettura = $array["id_vettura"];
	$targa = $array["targa"];
	$prezzo = $array["prezzo"];
	$ultimo_prop = $array["ultimo_prop"];
	$previ_prop = $array["previ_prop"];
	$commento = $array["commento"];
	$messaggio = "Attendi da HQ la conferma della proposta n. $id.";
	$html_disable = 'disabled';
};

// si te toca modificar la propuesta podes ver sus datos
if ($array['stato'] == 'in attesa buyer') {

//	echo '<pre>'; print_r($array); echo '</pre>';
	$id = $array["id"];
	$vettura = $array["id_vettura"];
	$targa = $array["targa"];
	$prezzo = $array["prezzo"];
	$ultimo_prop = $array["ultimo_prop"];
	$previ_prop = $array["previ_prop"];
	$commento = $array["commento"];
	$messaggio = "La proposta n. $id attende la tua verifica.";
	$html_disable = '';

	// si estas posteando la modifica de una propuesta
	if (isset($_POST['submit'])) {
	  $required_fields = array("car_id", "license", "price", "owner", "owner_no", "contract_plus",);
	  validate_presences($required_fields);

	  $fields_with_max_lengths = array("price" => 6);
	  validate_max_lengths($fields_with_max_lengths);

	  if (empty($errors)) {

	    $car_id = mysql_prep($_POST["car_id"]);
	    $license = mysql_prep($_POST["license"]);
	    $price = mysql_prep($_POST["price"]);
	    $owner = mysql_prep($_POST["owner"]);
	    $owner_no = mysql_prep($_POST["owner_no"]);
	    $contract_plus = mysql_prep($_POST["contract_plus"]);

	    $query  = "UPDATE proposte ";
	    $query .= "SET id_vettura = '{$car_id}', targa = '{$license}', prezzo = '{$price}', ultimo_prop = '{$owner}', previ_prop = '{$owner_no}', contratto = {$contract_plus}, stato = 'in attesa HQ' ";
	    $query .= "WHERE id = '$id';";
	    $result = mysqli_query($handle, $query);

	    if ($result) {
	      $messaggio = "La proposta n. $id e' stata modificata, attendi la conferma.";
	      redirect('index.php');
	    } else {
	      $messaggio = "Operazione Fallita.";
		  redirect('index.php');
	    }
	  }
	}
};

// si no hay propuestas esperando, miras una pagina sin datos:
if ($array['stato'] == null) {

	$vettura = "";
	$targa = "";
	$prezzo = "";
	$ultimo_prop = "";
	$commento = "";
	$messaggio = 'Non hai proposte in fase di valutazione.';
	$html_disable = '';

	// si estas postando un nueva propuesta
	if (isset($_POST['submit'])) {
	  $required_fields = array("car_id", "license", "price", "owner", "owner_no", "contract_plus",);
	  validate_presences($required_fields);

	  $fields_with_max_lengths = array("price" => 6);
	  validate_max_lengths($fields_with_max_lengths);

	  if (empty($errors)) {

	    $buyer = mysql_prep($logged_user->email);
	    $branch = mysql_prep($logged_user->branch);
	    $car_id = mysql_prep($_POST["car_id"]);
	    $license = mysql_prep($_POST["license"]);
	    $price = mysql_prep($_POST["price"]);
	    $owner = mysql_prep($_POST["owner"]);
	    $owner_no = mysql_prep($_POST["owner_no"]);
	    $contract_plus = mysql_prep($_POST["contract_plus"]);
	    $insert_date = date('d.m.Y H:i');

	    $query  = "INSERT INTO proposte (";
	    $query .= "buyer, branch, id_vettura, targa, prezzo, ultimo_prop, previ_prop, contratto, data_inserzione, commento, stato";
	    $query .= ") VALUES (";
	    $query .= "'{$buyer}', '{$branch}', '{$car_id}', '{$license}', '{$price}', '{$owner}', '{$owner_no}', {$contract_plus}, '{$insert_date}', '','in attesa HQ'";
	    $query .= ")";
	    $result = mysqli_query($handle, $query);

	    $query9 = "SELECT * FROM proposte where buyer = '{$buyer}' AND stato = 'in attesa HQ' LIMIT 1;";
	    $result9 = mysqli_query($handle, $query9);
	    $array = mysqli_fetch_assoc($result9);
		$bar = $array["id"];




	  	$upload_errors = array(
			// http://www.php.net/manual/en/features.file-upload.errors.php
		  UPLOAD_ERR_OK 		=> "Nessun errore.",
	      UPLOAD_ERR_INI_SIZE  	=> "Superato upload_max_filesize.",
		  UPLOAD_ERR_FORM_SIZE 	=> "Superata form MAX_FILE_SIZE.",
		  UPLOAD_ERR_PARTIAL 	=> "Upload parziale.",
		  UPLOAD_ERR_NO_FILE 	=> "Nessun file selezionato.",
		  UPLOAD_ERR_NO_TMP_DIR => "Directory temp mancante.",
		  UPLOAD_ERR_CANT_WRITE => "Impossibile scrivere su disco.",
		  UPLOAD_ERR_EXTENSION 	=> "Problema di extension."
		);
	  	// get extension from original filename
	  	$original_file = $_FILES['file_upload']['name'];
		$ext = pathinfo($original_file, PATHINFO_EXTENSION);
		// get temporary filename
		$tmp_file = $_FILES['file_upload']['tmp_name'];
		// prepare target filename
		$target_file = basename($license.'.'.$ext);

		if(move_uploaded_file($tmp_file, "uploads/".$target_file)) {
			$message = "File $target_file caricato correttamente.";
		} else {
			$error = $_FILES['file_upload']['error'];
			$message = $upload_errors[$error];
		}


	    if ($result9) {
	      $messaggio = "Proposta n. $bar inserita correttamente, attendi la conferma.";
	      $vettura = $array["id"];
		  $targa = $array["targa"];
  		  $prezzo = $array["prezzo"];
  		  $ultimo_prop = $array["ultimo_prop"];
 		  $previ_prop = $array["previ_prop"];
		  $commento = $array["commento"];
		  $html_disable = 'disabled';

	      mysqli_free_result($result9);
	    } else {
	      $messaggio = "Operazione Fallita.";
	      mysqli_free_result($result9);
	    }
	  }
	}
};
?>
<?php include("../private/layouts/header.php"); ?>
  <div id="main">
    <div id="page">
      <h2>Inserzione Proposta d'Acquisto</h2>
      <p>Ciao <b><i><?php echo htmlentities($logged_user->email); ?></i></b> di
	<b><?php echo htmlentities($logged_user->branch); ?></b>.<br></p>
	  <div class="message"> <?php if (isset($messaggio)) { echo $messaggio; } ?> </div>
	  <?php echo $session->message(); echo form_errors($errors);?>
	    <!-- The data encoding type, enctype, MUST be specified as below -->
        <form enctype="multipart/form-data" action="index.php" method="post">
	      <table id="tavol">
	        <tr>
	         <th style="width: 100px"> Campo</th>
	         <th style="width: 160px"> Valore</th>
	        </tr>
	        <tr>
	          <td style="text-align: left">ID Autovettura</td>
	          <td style="text-align: center">
	          <input style="width: 97%" type="text" name="car_id" <?php //echo $html_disable; ?> value="<?php echo $vettura;?>" /> </td>
	        </tr>
	        <tr>
	          <td style="text-align: left">Targa</td>
	          <td style="text-align: center">
	          <input style="width: 97%" type="text" name="license" <?php //echo $html_disable; ?> value="<?php echo $targa;?>" /> </td>
	        </tr>
	        <tr>
	          <td style="text-align: left">Prezzo d'Acquisto</td>
	          <td style="text-align: center">
	          <input style="width: 97%" type="text" name="price" <?php //echo $html_disable; ?> value="<?php echo $prezzo;?>" /> </td>
	        </tr>
	        <tr>
	          <td style="text-align: left">Ultimo Proprietario</td>
	          <td style="text-align: center">
	          <input style="width: 97%" type="text" name="owner" <?php //echo $html_disable; ?> value="<?php echo $ultimo_prop;?>" /> </td>
	        </tr>
	        <tr>
	          <td style="text-align: left">Previ Proprietari</td>
	          <td style="text-align: center">
		          <select style="width: 99%" name="owner_no" <?php //echo $html_disable; ?> >
		          	<?php
		              for ($i = 1; $i <= 9; $i++) {
		              	if ($i == $previ_prop) {
			                echo "<option selected>";
			            } else {
			            	echo "<option>";
			            }
		                echo $i;
		                echo "</option>";
		              }
		            ?>
		          </select>
	          </td>
	        </tr>
	        <tr>
	          <td style="text-align: left">Contratto Plus</td>
	          <td style="text-align: center"> Si
	          <input type="radio" name="contract_plus" value="true" <?php //echo $html_disable; ?> >
	          <input type="radio" name="contract_plus" value="false" <?php //echo $html_disable; ?> checked> No </td>
	        </tr>
	        <tr>
	        <td height="78" style="text-align: left">Commento</td>
	        <td height="78" style="text-align: center">
	       	  <textarea name="commento" disabled rows="5" cols="40"><?php echo $commento;?></textarea>
	        </td>
	        </tr>
	        <tr>
	        <td style="text-align: left">Bollo</td>
	        <td style="text-align: center">
	       	    <!-- MAX_FILE_SIZE must precede the file input field 5MB -->
			    <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
			    <!-- Name of input element determines name in $_FILES array -->
			    <input name="file_upload" type="file" />
	        </td>
	        </tr>
        </table><br>
	    <input type="submit" name="submit" value="Invia i dati" <?php //echo $html_disable; ?> />
      </form>

<?php
echo $message;
//echo $tmp_file;
echo '<pre>';
print_r($_FILES);
echo '</pre>';
	?>

    </div>
  </div>
  <div id="footer">
  <ul class="pages">
        <table>
			<tr>
			<td>
				<li><a href="index.php">Ricarica</a>
			</td>
			<td>
				</li><li><a href="logout.php">Esci</a></li>
			</td>
	        </tr>
        </table>
  </ul>
  </div>
 </body>
</html>
<?php if (isset($handle)) { mysqli_close($handle); } ?>
