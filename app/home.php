<?php

session_start() or die("Failed to resume session\n");
require 'class/users.php';

?>

<!-- Page d'acceuil si non connecté : possibilité de connexion ou creation de compte -->


<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="index.css">
  <title>Connexion à Camagru</title>
</head>
<body>
  <h1>Camagru</h1>
  <h2>Se connecter</h2>
  <form class="" action="#" method="post">
    Identifiant<br /><input type="text" name="login" value=""><br />
    Mot de passe<br /><input type="password" name="passwd" value=""><br />
    <input type="submit" name="OK" value="OK">
  </form>
  <?php
  if (!empty($_POST['login']) and !empty($_POST['passwd']) and $_POST['submit'] !== "OK") {
    $login = trim($_POST['login']);
    $passwd = trim($_POST['passwd']);
    $db = new Users($login, $passwd, "");
    $db->connect_user();
    if ($db->message)
      echo '<p style="color:red;">' . $db->message . '</p>';
    else {
      $_SESSION['logged'] = $login;
      echo '<script> location.replace("../index.php"); </script>';
    }
  }
  ?>
  <!-- Mot de passe oublié ? -->
  <h2>S'inscrire</h2>
  <form class="" action="#" method="post">
    Identifiant<br /><input type="text" name="new_login" value=""><br />
    Mot de passe<br /><input type="password" name="new_passwd" value=""><br />
    Mail<br /><input type="email" name="new_email" value=""><br />
    <input type="submit" name="new_OK" value="OK">
    <?php
    if (!empty($_POST['new_login']) and !empty($_POST['new_passwd']) and !empty($_POST['new_email']) and $_POST['submit'] !== "new_OK") {
      $new_login = trim($_POST['new_login']);
      $new_passwd = $_POST['new_passwd'];
      $new_email = trim($_POST['new_email']);
      $db = new Users($new_login, $new_passwd, $new_email);
      $db->add_user();
      if ($db->message)
        echo '<p style="color:red;">' . $db->message . '</p>';
    }
    ?>
  </form>
</body>
</html>


<?php

$pdo = null;

 ?>