<?php
require_once("../private/initialize.php");
if (!$session->is_logged_in()) { redirect("login"); }
$session->logout();

// v1: simple logout
	// $_SESSION["user_id"] = null;
	// $_SESSION["email"] = null;
	// $_SESSION["user_admin"] = null;
	// $_SESSION["user_branch"] = null;
	// $_SESSION["user_branch_id"] = null;
	// $_SESSION["message"] = "Logout effettuato con successo.";

// v2: destroy session
	//  $_SESSION = array();
	//  if (isset($_COOKIE[session_name()])) {
	//    setcookie(session_name(), '', time()-42000, '/');
	//  }
	//  session_destroy();

//redirect("login");
?>
