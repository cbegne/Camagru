<?php

  session_start() or die("Failed to resume session\n");
  $rawpic = $_POST['pic'];
  // $rawpic = str_replace(' ','+',$rawpic);
  $pic = base64_decode($rawpic);
  require '../class/pictures.class.php';
  $db = new Pictures("", $pic, $_SESSION['logged_user']);
  $id_pic = $db->addPicture();
  echo json_encode($id_pic);
?>
