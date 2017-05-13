<?php

session_start() or die("Failed to resume session\n");
if ($_SESSION['logged_user'] === null)
    header("Location: ../index.php");

?>

<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../public/css/webcam.css">
    <link rel="stylesheet" href="../public/css/headerfooter.css">
    <title>Camagru</title>
  </head>
  <body>
    <?php include 'header.php'; ?>
    <div class="centre">
      <main>
        <div class="webcam" id="column1">
          <video id="video"></video><br />
          <button id="img1"><img src="../public/img/image1.png" width=100/></button>
          <button id="img2"><img src="../public/img/image2.png" width=100/></button>
          <button id="img3"><img src="../public/img/image3.png" width=100/></button>
          <button id="startbutton">Prendre une photo</button>
          <p>OU</p>
          <form method="post" action="../app/uploadpic.php" enctype="multipart/form-data">
            <input type="file" name="uploadpic">
            <input type="hidden" name="MAX_FILE_SIZE" value="512000" />  <!-- poids max 500ko (1ko = 1024o) -->
          </form>
        </div>
        <div class="apercu">
          <canvas id="canvas"></canvas><br />
          <button id="savebutton">Sauvegarder</button>
        </div>
      </main><br />
      <aside id="side">
        <?php
          require '../class/pictures.class.php';
          $pic = new Pictures("", "", $_SESSION['logged_user']);
          $res = $pic->getPicture();
          $res = array_reverse($res);
          foreach ($res as $value): ?>
            <div class="displaypic">
              <img class="minipic" src="data:image/jpeg;base64,<?= base64_encode($value['pic']) ?>"/>
              <img class="deletepic" id="delete_<?= $value['id_pic']  ?>" onclick="deletePicture(<?= $value['id_pic']  ?>)" src="../public/img/delete.png" />
            </div>
          <? endforeach; ?>
      </aside>
      <script type="text/javascript" src="../public/js/webcam.js"></script>
    </div>
    <footer></footer>
  </body>
</html>

<!-- Page webcam : possibilité de prendre des photos à partir d'une webcam avec un choix de 3 images superposables -->
