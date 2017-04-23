<?php

session_start() or die("Failed to resume session\n");

?>

<!-- Page webcam : possibilité de prendre des photos à partir d'une webcam avec un choix de 3 images superposables -->
<!-- no access if not logged to be added -->

<html>
  <head>
    <meta charset="utf-8">
    <title>Camagru</title>
  </head>
  <body>
    <header>
      <a href="logout.php">Logout</a>
    </header>
    <main>
      <video id="video"></video>
      <button id="startbutton">Prendre une photo</button>
      <!-- <input type="submit" id="startbutton" name="startbutton" value="Prendre une photo"> -->
      <canvas id="canvas"></canvas>
      <img src="http://placekitten.com/g/320/261" id="photo" alt="photo">
    </main>
    <script type="text/javascript" src="../public/js/webcam.js"></script>
  </body>
</html>
