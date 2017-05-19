<?php

session_start() or die("Failed to resume session\n");

?>

<html>
 <head>
   <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
   <link rel="stylesheet" href="../public/css/gallery.css">
   <link rel="stylesheet" href="../public/css/headerfooter.css">
   <title>Camagru Gallery</title>
 </head>
 <body>
  <?php include 'header.php'; ?>
  <main class="allgallery">
    <?php
      require '../class/pictures.class.php';
      $pic = new Pictures("", "", "");
      $nbpicbypage = 5;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;

      $nbpic = $pic->nbPictures();
      $nbpage = ceil($nbpic / $nbpicbypage);
      if ($nbpic == 0): ?>
        <p>Prenez votre première photo dans le Photobooth !</p>
      <? elseif (intval($page)  || $page > $nbpage ):
          echo '<script> location.replace("gallery.php?page=1") </script>';
      else:
        $pics = $pic->getPicturesByPage((($page - 1) * $nbpicbypage), $nbpicbypage); // LIMIT MySQL starts at 0, e.g pics from 0 (not included) to 5 (included)

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
          <? if ($_SESSION['logged_user'] !== null): ?>
            <? if ($liked === false): ?>
              <button onclick="addLike(<?= $id_pic ?>)" class="like" ><img id=like_<?= $id_pic ?> src="../public/img/like.png"/></button>
            <? else: ?>
              <button onclick="addLike(<?= $id_pic ?>)" class="like" ><img id=like_<?= $id_pic ?> src="../public/img/like_red.png"/></button>
            <? endif;?>
          <? else: ?>
            <button class="like" ><img src="../public/img/like.png"/></button>
          <? endif; ?>
          <label for="new_comment_<?= $id_pic ?>" class="comment"><img id="comment_<?= $id_pic ?>" src="../public/img/comment.png"/></label>
          <span class="nblike" id="nblike_<?= $id_pic ?>"><?= $nblike ?> j'aime</span>
          </div>
          <div id="firstcomment_<?= $id_pic ?>">
            <? foreach ($comments as $line): ?>
              <div class="allcomments"><b><?= $line['login'] ?></b> <?= $line['comment'] ?></div>
            <? endforeach; ?>
          </div>
          <form method="post">
          <?php if ($_SESSION['logged_user'] !== null): ?>
            <input type="text" maxlength="255" onkeypress="{if (event.keyCode == 13) { event.preventDefault(); addComment(<?= $id_pic ?>, this, '<?= $user ?>')}}"
                class="inputcomment" id="new_comment_<?= $id_pic ?>" name="new_comment_<?= $id_pic ?>" placeholder="Ajouter un commentaire...">
          <? endif; ?>
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
    <? endif; ?>
    </div>
   </main>
   <footer></footer>
   <script type="text/javascript" src="../public/js/gallery.js"></script>
 </body>
</html>
