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

  private function checkNewUser() {
    $user = $this->getUser();
    if ($user)
      return $this->message = "Ce nom d'utilisateur est déjà utilisé.";
    if ($this->passwd != $this->passwdVerif)
    return $this->message = "Les mots de passe ne sont pas identiques.";
    // if (strlen($this->passwd) < 8 or preg_match('/[A-Z]+[a-z]+[0-9]+/', $this->passwd))
    //   return $this->message = "Votre mot de passe doit être composé a minima de 7 caractères, dont un chiffre et une majuscule.";
    if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false)
      return $this->message = "Votre mail n'est pas valide.";
  }

  public function sendConfirmationUser() {
    self::checkNewUser();
    if ($this->message)
      return ;
    $token = bin2hex(random_bytes(16));
    $headers  = 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
    $headers .= "From: camagru-noreply@student.42.fr\n";
    $pwrurl = "localhost:8080/camagru/pages/home.php?q=" . $token;
    $mailbody = "<html><body><p>Bonjour " . $this->login . ",</p><p>Pour confirmer votre compte pour Camagru, cliquez sur le lien suivant :</p>" . "<a href=http://" . $pwrurl . ">" . $pwrurl . "</a><p>A bientôt !</p></body></html>";
    if (mail($this->email, "Camagru - Confirmez votre compte", $mailbody, $headers))
      $this->message = "Un mail vous a été envoyé pour confirmer votre compte.";
    else
      return $this->message = "Le mail d'inscription n'a pas pu être envoyé.";
    date_default_timezone_set('Europe/Paris');
  	$date_creation = date("Y-m-d H:i:s");
    $req = $this->db->prepare("INSERT INTO `users` (`login`, `mot_de_passe`, `email`, `date_creation`, `token`) VALUES (?, ?, ?, ?, ?)");
    $req->execute(array($this->login, hash('whirlpool', $this->passwd), $this->email, $date_creation, $token));
  }

  public function confirmUser() {
    $req = $this->db->prepare("SELECT * FROM `users` WHERE `token` = ?");
    $res = $req->execute(array($this->token));
    $user = $req->fetch(PDO::FETCH_ASSOC);
    if (!$user)
      return $this->message = "Votre compte a déjà été validé ou le lien n'a pas été correctement suivi.";
    $req = $this->db->prepare("UPDATE `users` SET `confirm` = ?, `token` = ? WHERE `token` = ?");
    $req->execute(array(1, NULL, $this->token));
    $this->message = "Votre compte a bien été confirmé. Bienvenue " . $user['login'] . " !";
  }

  public function connectUser() {
    $user = $this->getUser();
    if (!$user)
      return $this->message = "Le nom d’utilisateur entré n’appartient à aucun compte.";
    if ($user['mot_de_passe'] != hash('whirlpool', $this->passwd))
      return $this->message = "Votre mot de passe est incorrect.";
  }

  public function sendPassword() {
    $user = $this->getUser();
    if (!$user)
      return $this->message = "Le nom d’utilisateur entré n’appartient à aucun compte.";
    $email = $user['email'];
    $token = bin2hex(random_bytes(16));
    $req = $this->db->prepare("UPDATE `users` SET `token` = ? WHERE `login` = ?");
    $req->execute(array($token, $this->login));
    $headers  = 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
    $headers .= "From: camagru-noreply@student.42.fr\n";
    $pwrurl = "localhost:8080/camagru/pages/password.php?q=" . $token;
    $mailbody = "<html><body><p>Bonjour " . $this->login . ",</p><p>Pour réinitialiser votre mot de passe pour Camagru, cliquez sur le lien suivant :</p>" . "<a href=http://" . $pwrurl . ">" . $pwrurl . "</a><p>A bientôt !</p></body></html>";
    if (mail($email, "Camagru - Mot de passe oublié", $mailbody, $headers))
      $this->message = "Un mail vous a été envoyé pour réinitialiser votre mot de passe.";
    else
      $this->message = "Le mail de réinitialisation n'a pas pu être envoyé.";
  }

  public function resetPassword() {
    $req = $this->db->prepare("SELECT * FROM `users` WHERE `token` = ?");
    $res = $req->execute(array($this->token));
    $user = $req->fetch(PDO::FETCH_ASSOC);
    if (!$user)
      return $this->message = "Une erreur s'est produite. Veuillez vérifier que le lien envoyé a bien été suivi.";
    $req = $this->db->prepare("UPDATE `users` SET `mot_de_passe` = ?, `token` = ? WHERE `token` = ?");
    $req->execute(array(hash('whirlpool', $this->passwd), NULL, $this->token));
    $this->message = "Votre mot de passe a été modifié.";
  }
}
?>