<?php

session_start() or die("Failed to resume session\n");
if ($_SESSION['logged_user'] === null)
    header("Location: ../index.php");
 ?>

<html>
 <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../public/css/gallery.css">
   <title>Camagru Gallery</title>
 </head>
 <body>
   <header>
     <?php include '../app/header.php'; ?>
   </header>
   <main>
     <?php
       require '../class/pictures.class.php';
       $pic = new Pictures("", "", "");
       $res = $pic->getAllPictures();
       $res = array_reverse($res);
       require '../class/likes.class.php';
       foreach ($res as $value) {
         $like = new Likes($value['id_pic'], $_SESSION['logged_user']);
         $liked = $like->getLike();
         $nblike = $like->nbLike();
         echo '<div class=picgallery>';
         echo '<div class=login id="login_'. $value['id_pic'] . '">' . $value['login'] . '</div>';
         echo '<img class=pic id="pic_'. $value['id_pic'] . '"src="data:image/jpeg;base64,' . base64_encode($value['pic']) . '"/>';
         echo '<div class=likeandcomment>';
         if ($liked === false)
          echo '<button onclick="addLike('. $value['id_pic'] . ')" class="like" ><img id="like_'. $value['id_pic'] . '" src="../public/img/like.png"/></button>';
        else
          echo '<button onclick="addLike('. $value['id_pic'] . ')" class="like" ><img id="like_'. $value['id_pic'] . '" src="../public/img/like_red.png"/></button>';
         echo '<button class="comment"><img id="comment_'. $value['id_pic'] . '" src="../public/img/comment.png"/></button>';
         echo '<span class="nblike" id="nblike_'. $value['id_pic'] . '">'. $nblike . ' j\'aime</span>';
         echo '</div></div>';
       }
     ?>
   </main>
   <script type="text/javascript" src="../public/js/gallery.js"></script>
 </body>
</html>
