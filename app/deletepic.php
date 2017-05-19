<?php

  session_start() or die("Failed to resume session\n");
  $id_pic = $_GET['id_pic'];
  require '../class/pictures.class.php';
  $db = new Pictures($id_pic, "", $_SESSION['logged_user']);
  $db->deletePicture();
  require '../class/likes.class.php';
  $like = new Likes($id_pic, "");
  $like->deleteAllLike();
  require '../class/comments.class.php';
  $comment = new Comments($id_pic, "", "");
  $comment->deleteAllComment();

  // delete picture and all likes and comments in DB
 ?>
