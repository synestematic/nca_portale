<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login.php"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->admin == 0) { redirect("admin.php"); }

if (!$_GET["id"]) { redirect("users.php"); }

$to_be_deleted_user = User::find_by_id($_GET["id"]);
if (!$to_be_deleted_user) { redirect("users.php"); }

$result = $to_be_deleted_user->delete();
if ($result) {
  $_SESSION["message"] = "Eliminato utente: $to_be_deleted_user->email";
  redirect("users.php");
} else {
  $_SESSION["message"] = "Operazione fallita.";
  redirect("users.php");
}
?>
