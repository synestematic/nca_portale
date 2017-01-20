<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }
$logged_user = User::find_by_id($_SESSION["user_id"]);

if (isset($_POST['submit'])) {

  $tmp_file = $_FILES['file_upload']['tmp_name'];
  $target_file = basename($_POST['targa']).".pdf";
  // $upload_dir = UPLOAD_PATH;
  $upload_dir = "/root/Desktop/smbmount";

  // You will probably want to first use file_exists() to make sure
  // there isn't already a file by the same name.

  // move_uploaded_file will return false if $tmp_file is not a valid upload file
  // or if it cannot be moved for any other reason
  if(move_uploaded_file($tmp_file, $upload_dir.DS.$target_file)) {
    $_SESSION["message"] = "File caricato correttamente.";
  } else {
    $error = $_FILES['file_upload']['error'];
    $_SESSION["message"] = $upload_errors[$error];
  }
  //
  // $required_fields = array("email", "password", "conferma");
  // validate_presences($required_fields);
  //
  // $fields_with_max_lengths = array("email" => 30);
  // validate_max_lengths($fields_with_max_lengths);
  //
  // is_equal($_POST["password"], $_POST["conferma"]);
  //
  // if (empty($errors)) {
  //
  //     $to_be_edited_user->branch = $_POST["branch"];
  //     $to_be_edited_user->dept = $_POST["dept"];
  //     $to_be_edited_user->email = $_POST["email"];
  //     $to_be_edited_user->full_name = $_POST["full_name"];
  //     $to_be_edited_user->pwd = $_POST["password"];
  //     $result = $to_be_edited_user->save();
  //
  //   if ($result) {
  //     $_SESSION["message"] = "Utente modificato: $to_be_edited_user->full_name";
  //     if ($to_be_edited_user->email == $logged_user->email) {
  //       redirect("admin.php");
  //     } else {
  //       redirect("users.php");
  //     }
  //   } else {
  //     $_SESSION["message"] = "Operazione fallita.";
  //   }
  // }
}

include("../private/layouts/header.php");
?>

<div id="main">
  <div id="navigation">
    <?php include("../private/layouts/logout_link.php"); ?>
    <a href="admin.php">&laquo; Torna indietro</a>
    <ul class="pages">
    </ul>
  </div>
  <div id="page">
    <?php echo $session->message(); ?>
    <?php echo form_errors($errors); ?>
    <h2>Carica Documento:</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="POST">
      <table id="tavola"><tr><th>Targa</th><th>Data</th></tr>
        <tr>
          <td>Filiale:</td>
          <td> <?php Branch::branch_dropdown("branch"); ?> </td>
        </tr>
        <tr>
          <td>Reparto:</td>
          <td> <?php Dept::dept_dropdown("dept"); ?> </td>
        </tr>
        <tr>
           <td><input type="text" name="targa" value="" /></td>
           <td>
              <?php
              $yo = new DropdownDays();
     					$yo->selected = //$da_giorno;
     					$yo->id = "data";
     					$yo->name = "da_giorno";
     					$yo->menu();
              $yo = new DropdownMonths();
     					$yo->selected = //$da_giorno;
     					$yo->id = "data";
     					$yo->name = "da_giorno";
     					$yo->menu();
              $yo = new DropdownYears();
     					$yo->selected = //$da_giorno;
     					$yo->id = "data";
     					$yo->name = "da_giorno";
     					$yo->menu();
              ?>
           </td>
        </tr>
      </table>
      <br>
      <input type="hidden" name="MAX_FILE_SIZE" value="8000000" />
      <input type="file" name="file_upload"/>
      <input type="submit" name="submit" value="Carica" />
    </form>
    <?php echo '<pre>';
    print_r($_FILES['file_upload']);
    echo '</pre>';
    ?>
  </div>
</div>
<?php include("../private/layouts/footer.php"); ?>
