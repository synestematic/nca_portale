<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }

$logged_user = User::find_by_id($_SESSION["user_id"]);
if ($logged_user->su == 0) { redirect("users"); }

if (!$_GET["id"]) { redirect("users"); }

$to_be_deleted_user = User::find_by_id($_GET["id"]);
if (!$to_be_deleted_user) { redirect("users"); }

$result = $to_be_deleted_user->delete();
if ($result) {
  $_SESSION["message"] = "Eliminato utente: $to_be_deleted_user->email";
  redirect("users");
} else {
  $_SESSION["message"] = "Operazione fallita.";
  redirect("users");
}
?>
