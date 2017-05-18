<?php

class Likes {

  private $db;
  private $id_pic;
  private $login;

  public function __construct($id_pic, $login) {
    require '../config/database.php';
    $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->id_pic = $id_pic;
    $this->login = $login;
  }

  public function getLike() {
    $req = $this->db->prepare("SELECT * FROM `likes` WHERE `id_pic` = ? AND `login` = ?");
    $res = $req->execute(array($this->id_pic, $this->login));
    $like = $req->fetch(PDO::FETCH_ASSOC);
    return $like;
  }

  public function addLike() {
    date_default_timezone_set('Europe/Paris');
  	$date_creation = date("Y-m-d H:i:s");
    $req = $this->db->prepare("INSERT INTO `likes` (`id_pic`, `login`, `date_creation`) VALUES (?, ?, ?)");
    $req->execute(array($this->id_pic, $this->login, $date_creation));
  }

  public function nbLike() {
    $req = $this->db->query("SELECT count(*) FROM `likes` WHERE `id_pic` = $this->id_pic");
    $nblike = $req->fetch(PDO::FETCH_ASSOC);
    return $nblike['count(*)'];
  }
  public function deleteLike() {
    $req = $this->db->prepare("DELETE FROM `likes` WHERE `id_pic` = ? AND `login` = ?");
    $req->execute(array($this->id_pic, $this->login));
  }

  public function deleteAllLike() {
    $req = $this->db->prepare("DELETE FROM `likes` WHERE `id_pic` = ?");
    $req->execute(array($this->id_pic));
  }

}

?>
