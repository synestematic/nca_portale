<?php
class Session {

  private $logged_in=false;
  public $user_id;
  private $message = '';

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
      $this->create_user_dir($user);
    }
  }

  private function create_user_dir($user) {
      if (!is_dir($user->tmp_dir)) {
          mkdir($user->tmp_dir, 0700);
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
    redirect("login");
  }

  function message() {
    if (isset($_SESSION["message"])) {
      $this->message = "<div class=\"message\">";
      $this->message .= htmlentities($_SESSION["message"]);
      $this->message .= "</div>";
      $_SESSION["message"] = null;
      return $this->message;
    }
  }

}

$session = new Session();

?>
