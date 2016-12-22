<?php
class Session {

  private $logged_in=false;
  public $user_id;

  function __construct($data = array()) {
    session_start();
    $this->check_login();
  }
  private function check_login() {
    if (isset($_SESSION["user_id"])) {
      $this->user_id = $_SESSION["user_id"];
      $this->logged_in = true;
    } else {
      unset($this->user_id);
      $this->logged_in = false;
    }
  }

  public function is_logged_in() {
    return $this->logged_in;
  }

  public function login($user) {
    if ($user) {
      $this->user_id = $_SESSION["user_id"] = $user->id;
      $this->logged_in = true;
    }
  }

  public function logout() {
    unset($this->user_id);
    unset($_SESSION["user_id"]);
    $this->logged_in = false;

    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();
    redirect("login.php");
  }

  function message() {
    if (isset($_SESSION["message"])) {
      $output = "<div class=\"message\">";
      $output .= htmlentities($_SESSION["message"]);
      $output .= "</div>";
      $_SESSION["message"] = null;
      return $output;
    }
  }

}

$session = new Session();

?>
