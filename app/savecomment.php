<?php

  session_start() or die("Failed to resume session\n");
  $comment = $_POST['comment'];
  $id_pic = $_POST['id_pic'];
  require '../class/comments.class.php';
  if ($comment !== null) {
   $com = new Comments($id_pic, $_SESSION['logged_user'], $comment);
   $com->addComment();
  }

  // save comment from gallery in DB
?>
