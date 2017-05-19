<?php

  session_start() or die("Failed to resume session\n");
  var_dump($_GET);
  $id_pic = $_GET['id_pic'];
  require '../class/likes.class.php';
  $db = new Likes($id_pic, $_SESSION['logged_user']);
  $db->addLike();

  // save like from gallery in DB
?>
