<?php

  session_start() or die("Failed to resume session\n");
  $id_pic = $_GET['id_pic'];
  require '../class/likes.class.php';
  $db = new Likes($id_pic, $_SESSION['logged_user']);
  $db->deleteLike();

  // unlike in gallery
 ?>
