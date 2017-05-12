<?php

  session_start() or die("Failed to resume session\n");
  $id_pic = $_GET['id_pic'];
  require '../class/pictures.class.php';
  $db = new Pictures($id_pic, "", $_SESSION['logged_user']);
  $db->deletePicture();

// delete comments and likes

 ?>
