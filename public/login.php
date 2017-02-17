<?php
require_once("../private/initialize.php");
if ($session->is_logged_in()) { redirect("admin.php"); }

if (isset($_POST['submit'])) {
  $required_fields = array("email", "password");
  validate_presences($required_fields);
  if (empty($errors)) {
    $found_user = User::authenticate($_POST["email"], $_POST["password"]);
		if ($found_user) {
      $session->login($found_user);
			redirect("admin.php");
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
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <p>Username:<br>
          <input type="text" style="width:98%" name="email" value="<?php echo isset($_POST['submit']) ? htmlentities($_POST["email"]) : "" ; ?>" />
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
   <p>
    <?php echo $session->message(); ?>
    <?php echo form_errors($errors); ?>
   </p>
      <p> Effettua il login per accedere.</p>
  </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
