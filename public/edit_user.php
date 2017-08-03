<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->su == 0) { redirect("users"); }

if (!$_GET["id"]) { redirect("users"); }
$to_be_edited_user = User::find_by_id($_GET["id"]);
if (!$to_be_edited_user) { redirect("users"); }

if (isset($_POST['submit'])) {
  $required_fields = array("email", "nome", "password", "conferma_password", "branch", "dept");
  $validation->check_presence($required_fields);

  $fields_with_max_length = array("email" => 30);
  $validation->check_max_length($fields_with_max_length);

  $validation->is_equal($_POST["password"], $_POST["conferma_password"]);

  if ($validation->error_message() === null) {

      $to_be_edited_user->branch = $_POST["branch"];
      $to_be_edited_user->dept = $_POST["dept"];
      $to_be_edited_user->email = $_POST["email"];
      $to_be_edited_user->full_name = $_POST["nome"];
      $to_be_edited_user->access = ($_POST["access"]) ? 'true' : 'false' ;
      $to_be_edited_user->su = ($_POST["su"]) ? 'true' : 'false' ;
      $to_be_edited_user->pwd = $_POST["password"];
      $result = $to_be_edited_user->save();

    if ($result) {
      $_SESSION["message"] = "Utente modificato: $to_be_edited_user->full_name";
      if ($to_be_edited_user->email == $logged_user->email) {
        redirect("admin");
      } else {
        redirect("users");
      }
    } else {
      $_SESSION["message"] = "Operazione fallita.";
    }
  }
}

include("../private/layouts/header.php");
?>

<div id="main">
  <div id="navigation">
    <?php include("../private/layouts/logout_link.php"); ?>
    <a href="users">&laquo; Torna indietro</a>
    <?php //echo ($to_be_edited_user->full_name == $logged_user->full_name) ? '<a href="admin">&laquo; Torna indietro</a>' : '<a href="users">&laquo; Torna indietro</a>' ; ?>
    <ul class="pages">
    </ul>
  </div>
  <div id="page">
    <?php
      echo $session->message();
      echo $validation->error_message();
    ?>
    <h2>Profilo Utente: <?php echo $to_be_edited_user->full_name; ?></h2>
    <form action="edit_user?id=<?php echo urlencode($to_be_edited_user->id); ?>" method="post">
      <table class="colored_table" id="small_table">
        <tr>
            <th><input type="submit" name="submit" value="Modifica Utente" />
            </th>
            <th>
                <table><tr>
                    <th id="no_border">Access<input id="access_input" type="checkbox" name="access" <?php echo ($to_be_edited_user->access) ? 'checked="checked"' : ''; ?>/></th>
                    <th id="no_border">Super<input id="super_input" type="checkbox" name="su" <?php echo ($to_be_edited_user->su) ? 'checked="checked"' : ''; ?>/></th>
                </tr></table>
            </th>
        </tr>
        <tr>
          <td>Filiale:</td>
          <td> <?php
          Branch::branch_dropdown("branch");
          ?> </td>
        </tr>
        <tr>
          <td>Reparto:</td>
          <td> <?php Dept::dept_dropdown("dept"); ?> </td>
        </tr>
        <tr>
           <td>Indirizzo Mail:</td>
           <td><input type="text" name="email" value="<?php echo $to_be_edited_user->email; ?>" /></td>
        </tr>
         <tr>
           <td>Nome Completo:</td>
           <td><input type="text" name="nome" value="<?php echo $to_be_edited_user->full_name; ?>" /></td>
         </tr>
         <tr>
           <td>Password:</td>
           <td><input type="password" name="password" value="" /></td>
         </tr>
         <tr>
           <td>Conferma Password:</td>
           <td><input type="password" name="conferma_password" value="" /></td>
         </tr>
      </table><br>
      <br><br>
    </form>
  </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
