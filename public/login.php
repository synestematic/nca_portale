<?php
require_once("../private/initialize.php");
if ($session->is_logged_in()) { redirect("admin"); }

if (isset($_POST['submit'])) {

  $required_fields = array("username", "password");
  $validation->check_presence($required_fields);

  if ($validation->error_message() === null) {
    $found_user = User::authenticate($_POST["username"], $_POST["password"]);
		if ($found_user) {
      $session->login($found_user);
			redirect("admin");
		} else {
			$_SESSION["message"] = "Credenziali non corrette.";
		}
  }
}
?>
<?php include("../private/layouts/header.php"); ?>
<div id="main">
  <div id="navigation"><br>
	<fieldset>
    <legend>Autenticazione:</legend>
      <form action="" method="post">
        <p>Username:<br>
          <input type="text" style="width:98%" name="username" value="<?php echo isset($_POST['submit']) ? htmlentities($_POST["username"]) : "" ; ?>" />
        </p>
        <p>Password:<br>
          <input type="password" style="width:98%" name="password" value="" />
        </p>
        <input type="submit" name="submit" value="Accedi" />
      </form>
	</fieldset>
  </div>
  <div id="page">
    <h2>Portale NCA.it</h2>
    <p>Effettua il login per accedere.</p>
    <p>
      <?php
        echo $session->message();
        echo $validation->error_message();
      ?>
    </p>
  </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
