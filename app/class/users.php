<?php

class Users {

  private $db;
  private $login;
  private $passwd;
  private $email;
  public $message;

  public function __construct($login, $passwd, $email) {
    require 'config/database.php';
    $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->login = $login;
    $this->passwd = $passwd;
    $this->email = $email;
  }

  private function check_new_user() {
    $req = $this->db->prepare("SELECT `login` FROM `users` WHERE `login` = ?");
    $res = $req->execute(array($this->login));
    if ($req->fetch())
      return $this->message = "Ce nom d'utilisateur est déjà utilisé.";
    // if (strlen($this->passwd) < 8 or preg_match('/[A-Z]+[a-z]+[0-9]+/', $this->passwd))
    //   $this->message = "Votre mot de passe doit être composé a minima de 7 caractères, dont un chiffre et une majuscule.";
  }

  public function add_user() {
    self::check_new_user();
    if ($this->message)
      return ;
    $req = $this->db->prepare("INSERT INTO `users` (`login`, `mot_de_passe`, `email`) VALUES (?, ?, ?)");
    $req->execute(array($this->login, hash('whirlpool', $this->passwd), $this->email));
    $this->message = "Votre compte a bien été créé. Bienvenue " . $this->login . " !";
  }

  public function connect_user() {
    $req = $this->db->prepare("SELECT * FROM `users` WHERE `login` = ?");
    $res = $req->execute(array($this->login));
    $user = $req->fetch(PDO::FETCH_ASSOC);
    if (!$user)
      return $this->message = "Le nom d’utilisateur entré n’appartient à aucun compte.";
    if ($user['mot_de_passe'] != hash('whirlpool', $this->passwd))
      return $this->message = "Votre mot de passe est incorrect.";
    // var_dump($user);
  }
}

 ?>
