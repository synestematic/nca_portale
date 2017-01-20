<?php
require_once("../private/initialize.php");

if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->admin == 0) { redirect("admin.php"); }

if (isset($_POST['submit'])) {

  $required_fields = array("email", "password", "conferma", "branch", "dept", "full_name");
  validate_presences($required_fields);

  $fields_with_max_lengths = array("email" => 30);
  validate_max_lengths($fields_with_max_lengths);

  is_equal($_POST["password"], $_POST["conferma"]);

  if (empty($errors)) {

    $to_be_created_user = new User();
    $to_be_created_user->branch = $_POST["branch"];
    $to_be_created_user->dept = $_POST["dept"];
    $to_be_created_user->email = $_POST["email"];
    $to_be_created_user->full_name = $_POST["full_name"];
    $to_be_created_user->admin = false;
    $to_be_created_user->su = false;
    $to_be_created_user->pwd = $_POST["password"];
    $result = $to_be_created_user->save();

    if ($result) {
      $_SESSION["message"] = "Creato nuovo utente: $to_be_created_user->dept";
      redirect("users.php");
    } else {
      $_SESSION["message"] = "Operazione Fallita.";
    }
  }
}
include("../private/layouts/header.php");
?>

<div id="main">
  <div id="navigation">
    <?php include("../private/layouts/logout_link.php"); ?>
    <a href="users.php">&laquo; Torna indietro</a><br>
  </div>
  <div id="page">
    <?php echo $session->message(); ?>
    <?php echo form_errors($errors); ?>

    <h2>Crea un nuovo Utente:</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <table id="tavola"><tr><th></th><th></th></tr>
        <tr>
          <td>Filiale:</td>
          <td> <?php Branch::branch_dropdown("branch"); ?> </td>
        </tr>
        <tr>
          <td>Reparto:</td>
          <td> <?php Dept::dept_dropdown("dept"); ?> </td>
        </tr>
        <tr>
           <td>Indirizzo Mail:</td>
           <td><input type="text" name="email" value="<?php echo isset($_POST["email"]) ? htmlentities($_POST["email"]) : ''; ?>" /></td>
        </tr>
         <tr>
           <td>Nome e Cognome:</td>
           <td><input type="text" name="full_name" value="<?php echo isset($_POST["full_name"]) ? htmlentities($_POST["full_name"]) : ''; ?>" /></td>
         </tr>
         <tr>
           <td>Password:</td>
           <td><input type="password" name="password" value="" /></td>
         </tr>
         <tr>
           <td>Conferma:</td>
           <td><input type="password" name="conferma" value="" /></td>
         </tr>
      </table>
      <br>
      <input type="submit" name="submit" value="Crea" />
    </form>
  </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
