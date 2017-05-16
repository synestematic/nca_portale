<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->su == 0) { redirect("users"); }

if (isset($_POST['submit'])) {

  $required_fields = array("email", "nome", "password", "conferma_password", "branch", "dept");
  $validation->check_presence($required_fields);

  $fields_with_max_length = array("email" => 30);
  $validation->check_max_length($fields_with_max_length);

  $validation->is_equal($_POST["password"], $_POST["conferma_password"]);

  if ($validation->error_message() === null) {

    $to_be_created_user = new User();
    $to_be_created_user->branch = $_POST["branch"];
    $to_be_created_user->dept = $_POST["dept"];
    $to_be_created_user->email = $_POST["email"];
    $to_be_created_user->full_name = $_POST["nome"];
    $to_be_created_user->access = 'false';
    $to_be_created_user->su = 'false';
    $to_be_created_user->pwd = $_POST["password"];
    $user_created = $to_be_created_user->save();

    if ($user_created) {
      $_SESSION["message"] = "Creato nuovo utente: $to_be_created_user->email";
      redirect("users");
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
    <a href="users">&laquo; Torna indietro</a>
  </div>
  <div id="page">
    <?php
      echo $session->message();
      echo $validation->error_message();
    ?>
    <h2>Crea un nuovo Utente:</h2>
    <form action="" method="post">
      <table id="tavol"><tr><th></th><th></th></tr>
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
           <td>Nome Completo:</td>
           <td><input type="text" name="nome" value="<?php echo isset($_POST["nome"]) ? htmlentities($_POST["nome"]) : ''; ?>" /></td>
         </tr>
         <tr>
           <td>Password:</td>
           <td><input type="password" name="password" value="" /></td>
         </tr>
         <tr>
           <td>Conferma Password:</td>
           <td><input type="password" name="conferma_password" value="" /></td>
         </tr>
      </table>
      <br>
      <input type="submit" name="submit" value="Crea" />
    </form>
  </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
