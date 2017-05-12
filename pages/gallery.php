<?php

session_start() or die("Failed to resume session\n");
if ($_SESSION['logged_user'] === null)
    header("Location: ../index.php");
?>

<html>
 <head>
   <meta charset="utf-8">
   <link rel="stylesheet" href="../public/css/gallery.css">
   <link rel="stylesheet" href="../public/css/headerfooter.css">
   <title>Camagru Gallery</title>
 </head>
 <body>
   <?php include '../app/header.php'; ?>
   <main>

     <?php
       require '../class/pictures.class.php';
       $pic = new Pictures("", "", "");
       $nbpicbypage = 5;
       $page = isset($_GET['page']) ? $_GET['page'] : 1;
       $pics = $pic->getPicturesByPage($page, $nbpicbypage);
       $nbpic = $pic->nbPictures();
       $nbpage = ceil($nbpic / $nbpicbypage);

       require '../class/likes.class.php';
       require '../class/comments.class.php';

       foreach ($pics as $value):
         $id_pic = $value['id_pic'];
         $user = $_SESSION['logged_user'];
         $like = new Likes($id_pic, $user);
         $liked = $like->getLike();
         $nblike = $like->nbLike();
         $comment = new Comments($id_pic, "", "");
         $comments = $comment->getComment();
      ?>
         <div class="picgallery">
           <div class="login" id="login_<?= $id_pic ?>"><?= $value['login'] ?></div>
           <img class="pic" id="pic_<?= $id_pic ?>" src="data:image/jpeg;base64,<?= base64_encode($value['pic'])?>"/>
         <div class="likeandcomment">
          <? if ($liked === false): ?>
            <button onclick="addLike(<?= $id_pic ?>)" class="like" ><img id=like_<?= $id_pic ?> src="../public/img/like.png"/></button>
          <? else: ?>
            <button onclick="addLike(<?= $id_pic ?>)" class="like" ><img id=like_<?= $id_pic ?> src="../public/img/like_red.png"/></button>
          <? endif;?>
          <button class="comment"><img id="comment_<?= $id_pic ?>" src="../public/img/comment.png"/></button>
          <span class="nblike" id="nblike_<?= $id_pic ?>"><?= $nblike ?> j'aime</span>
          </div>
          <div id="firstcomment_<?= $id_pic ?>">
            <? foreach ($comments as $line): ?>
              <div class="allcomments"><b><?= $line['login'] ?></b> <?= $line['comment'] ?></div>
            <? endforeach; ?>
          </div>
          <form method="post">
         <input type="text" onkeypress="{if (event.keyCode == 13) { event.preventDefault(); addComment(<?= $id_pic ?>, this, '<?= $user ?>')}}"
                class="inputcomment" id="new_comment_<?= $id_pic ?>" name="new_comment_<?= $id_pic ?>" placeholder="Ajouter un commentaire...">
         </form>
         </div>
       <? endforeach; ?>
    <div class="pages">
      <? if ($page != 1): ?>
        <a href="gallery.php?page=<?= ($page - 1) ?>" class="previous">⇦</a>
      <? endif; ?>
      <span class="pagenumber"><b><?= $page ?></b></span>
      <? if ($page != $nbpage): ?>
        <a href="gallery.php?page=<?= ($page + 1) ?>" class="next">⇨</a>
      <? endif; ?>
    </div>
   </main>
   <footer></footer>
   <script type="text/javascript" src="../public/js/gallery.js"></script>
 </body>
</html>
