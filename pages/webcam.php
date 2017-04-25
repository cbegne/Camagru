<?php

session_start() or die("Failed to resume session\n");
if ($_SESSION['logged_user'] === null)
    header("Location: ../index.php");

?>

<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../public/css/webcam.css">
    <title>Camagru</title>
  </head>
  <body>
    <header>
      <a href="logout.php">Logout</a>
    </header>
    <div class="centre">
      <main>
        <div class="webcam">
          <video id="video"></video>
          <canvas id="canvas"></canvas><br />
        </div>
        <br /><button id="startbutton">Prendre une photo</button>

        <!-- <img src="http://placekitten.com/g/320/261" id="photo" alt="photo"> -->
      </main><br />
      <aside>
        <?php
          require '../class/pictures.class.php';
          $pic = new Pictures("", $_SESSION['logged_user']);
          $res = $pic->getPicture();
          $res = array_reverse($res);
          foreach ($res as $value) {
            echo '<img class=minipic src="data:image/jpeg;base64,' . base64_encode($value['pic']) . '"/>';
          }
        ?>
      </aside>
      <script type="text/javascript" src="../public/js/webcam.js"></script>
    </div>
  </body>
</html>

<!-- Page webcam : possibilité de prendre des photos à partir d'une webcam avec un choix de 3 images superposables -->
