<?php

class Users {

  private $db;
  private $login;
  private $passwd;
  private $passwdVerif;
  private $email;
  private $token;
  public $message;

  public function __construct($login, $passwd, $passwdVerif, $email, $token) {
    require '../config/database.php';
    $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->login = $login;
    $this->passwd = $passwd;
    $this->passwdVerif = $passwdVerif;
    $this->email = $email;
    $this->token = $token;
  }

  private function getUser() {
    $req = $this->db->prepare("SELECT * FROM `users` WHERE `login` = ?");
    $res = $req->execute(array($this->login));
    $user = $req->fetch(PDO::FETCH_ASSOC);
    return $user;
  }

  private function checkPassword() {
    if (strlen($this->passwd) > 255)
      return $this->message = "Votre mot de passe ne doit pas excéder 30 caractères.";
    if ($this->passwd != $this->passwdVerif)
      return $this->message = "Les mots de passe ne sont pas identiques.";
    if (!preg_match('/(?=.*[0-9])(?=.*[A-Za-z]).{7,30}/', $this->passwd))
      return $this->message = "Votre mot de passe doit être composé a minima de 7 caractères, dont une lettre et un chiffre.";
  }

  private function checkNewUser() {
    if (strlen($this->login) > 30)
      return $this->message = "Votre login ne doit pas excéder 30 caractères.";
    $user = $this->getUser();
    if ($user)
      return $this->message = "Ce nom d'utilisateur est déjà utilisé.";
    self::checkPassword();
    if ($this->message != null)
      return ;
    if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false)
      return $this->message = "Votre mail n'est pas valide.";
  }

  public function sendConfirmationUser() {
    self::checkNewUser();
    if ($this->message)
      return ;
    $token = bin2hex(random_bytes(16));
    $pwrurl = "localhost:8080/camagru/pages/home.php?q=" . $token;
    date_default_timezone_set('Europe/Paris');
  	$date_creation = date("Y-m-d H:i:s");
    $token_expires = date("Y-m-d H:i:s", strtotime($date_creation . ' + 2 days'));
    $req = $this->db->prepare("INSERT INTO `users` (`login`, `mot_de_passe`, `email`, `date_creation`, `token`, `token_expires`) VALUES (?, ?, ?, ?, ?, ?)");
    $req->execute(array($this->login, hash('whirlpool', $this->passwd), $this->email, $date_creation, $token, $token_expires));
    $req = $this->db->prepare("DELETE FROM `users` WHERE `token_expires` < NOW() AND `confirm` = 0");
    $req->execute();
    require '../app/mailconfirm.php';
  }

  public function confirmUser() {
    $req = $this->db->prepare("SELECT * FROM `users` WHERE `token` = ?");
    $res = $req->execute(array($this->token));
    $user = $req->fetch(PDO::FETCH_ASSOC);
    if (!$user)
      return $this->message = "Votre compte a déjà été validé ou le lien a expiré.";
    $req = $this->db->prepare("UPDATE `users` SET `confirm` = ?, `token` = ?, `token_expires` = ? WHERE `token` = ?");
    $req->execute(array(1, NULL, NULL, $this->token));
    $this->message = "Votre compte a bien été confirmé. Bienvenue " . $user['login'] . " !";
  }

  public function connectUser() {
    $user = $this->getUser();
    if (!$user)
      return $this->message = "Le nom d’utilisateur entré n’appartient à aucun compte.";
    if ($user['confirm'] == 0)
      return $this->message = "Votre compte n'a pas encore été validé.<br /> Merci de suivre le lien reçu par mail.";
    if ($user['mot_de_passe'] != hash('whirlpool', $this->passwd))
      return $this->message = "Votre mot de passe est incorrect.";
  }

  public function sendPassword() {
    $user = $this->getUser();
    if (!$user)
      return $this->message = "Le nom d’utilisateur entré n’appartient à aucun compte.";
    $email = $user['email'];
    $token = bin2hex(random_bytes(16));
    $pwrurl = "localhost:8080/camagru/pages/password.php?q=" . $token;
    date_default_timezone_set('Europe/Paris');
  	$date_creation = date("Y-m-d H:i:s");
    $token_expires = date("Y-m-d H:i:s", strtotime($date_creation . ' + 2 days'));
    $req = $this->db->prepare("UPDATE `users` SET `token` = ?, `token_expires` = ? WHERE `login` = ?");
    $req->execute(array($token, $token_expires, $this->login));
    $req = $this->db->prepare("UPDATE `users` SET `token` = ?, `token_expires` = ? WHERE `token_expires` < NOW() AND `confirm` = 1");
    $req->execute(array(NULL, NULL));
    require '../app/mailpassword.php';
  }

  public function resetPassword() {
    $req = $this->db->prepare("SELECT * FROM `users` WHERE `token` = ?");
    $res = $req->execute(array($this->token));
    $user = $req->fetch(PDO::FETCH_ASSOC);
    if (!$user)
      return $this->message = "Le lien a expiré ou n'a pas été correctement suivi.";
    self::checkPassword();
    if ($this->message != null)
      return ;
    $req = $this->db->prepare("UPDATE `users` SET `mot_de_passe` = ? WHERE `token` = ?");
    $req->execute(array(hash('whirlpool', $this->passwd), $this->token));
    $this->message = "Votre mot de passe a été modifié.";
  }
}
?>
