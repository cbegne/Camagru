<?php

class Comments {

  private $db;
  private $id_pic;
  private $login;
  private $comment;

  public function __construct($id_pic, $login, $comment) {
    require '../config/database.php';
    $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->id_pic = $id_pic;
    $this->login = $login;
    $this->comment = $comment;
  }

  public function getComment() {
    $req = $this->db->prepare("SELECT * FROM `comments` WHERE `id_pic` = ?");
    $res = $req->execute(array($this->id_pic));
    $allcomments = $req->fetchAll(PDO::FETCH_ASSOC);
    return $allcomments;
  }

  public function addComment() {
    date_default_timezone_set('Europe/Paris');
  	$date_creation = date("Y-m-d H:i:s");
    $req = $this->db->prepare("INSERT INTO `comments` (`id_pic`, `comment`, `login`, `date_creation`) VALUES (?, ?, ?, ?)");
    $req->execute(array($this->id_pic, $this->comment, $this->login, $date_creation));
    self::sendMailComment();
  }

  private function sendMailComment() {
    $req = $this->db->prepare("SELECT `login` FROM `pictures` WHERE `id_pic` = ?");
    $res = $req->execute(array($this->id_pic));
    $pic = $req->fetch(PDO::FETCH_ASSOC);
    if ($pic['login'] != $this->login) {
      $login_pic = $pic['login'];
      $req = $this->db->query("SELECT `email` FROM `users` WHERE `login` = '$login_pic'");
      $user = $req->fetch(PDO::FETCH_ASSOC);
      $email = $user['email'];
      require '../app/mailcomment.php';
    }
  }

  public function deleteAllComment() {
    $req = $this->db->prepare("DELETE FROM `comments` WHERE `id_pic` = ?");
    $req->execute(array($this->id_pic));
  }
}
