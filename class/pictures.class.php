<?php

class Pictures {

  private $db;
  private $pic;
  private $login;

  public function __construct($pic, $login) {
    require '../config/database.php';
    $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->pic = $pic;
    $this->login = $login;
  }

  public function addPicture() {
    date_default_timezone_set('Europe/Paris');
  	$date_creation = date("Y-m-d H:i:s");
    $req = $this->db->prepare("INSERT INTO `pictures` (`login`, `pic`, `date_creation`) VALUES (?, ?, ?)");
    $req->execute(array($this->login, $this->pic, $date_creation));
  }

  public function getPicture() {
    $req = $this->db->prepare("SELECT * FROM `pictures` WHERE `login` = ? ORDER BY id_pic DESC LIMIT 1");
    $req = $this->db->prepare("SELECT * FROM `pictures` WHERE `login` = ?");
    $res = $req->execute(array($this->login));
    $picture = $req->fetchAll(PDO::FETCH_ASSOC);
    return $picture;
  }
}

 ?>
