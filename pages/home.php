<?php

session_start() or die("Failed to resume session\n");
require_once '../class/users.class.php';

?>

<!-- Page d'acceuil si non connecté : possibilité de connexion ou creation de compte -->

<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../public/css/home.css">
  <title>Connexion à Camagru</title>
</head>
<body>
  <h1>Camagru</h1>
  <div class="connexion">
    <h2>Se connecter</h2>
    <form class="" action="#" method="post">
      Identifiant<br /><input type="text" name="login" value=""><br />
      Mot de passe<br /><input type="password" name="passwd" value=""><br /><br />
      <input class="button" type="submit" name="submit" value="OK">
    </form>
    <?php
    if (!empty($_POST['login']) and !empty($_POST['passwd']) and $_POST['submit'] == "OK") {

      $login = trim($_POST['login']);
      $passwd = $_POST['passwd'];
      $db = new Users($login, $passwd, "", "", "");
      $db->connectUser();
      if ($db->message)
        echo '<p style="color:red;">' . $db->message . '</p>';
      else {
        $_SESSION['logged_user'] = $login;
        echo '<script> location.replace("../index.php"); </script>';
      }
    }
    ?>
    <a href="forgot.php" id="forgot">Mot de passe oublié ?</a>
  </div>
  <div class="inscription">
    <h2>S'inscrire</h2>
    <form class="" action="#" method="post">
      Identifiant<br /><input type="text" name="new_login" value=""><br />
      Mot de passe<br /><input type="password" name="new_passwd" value=""><br />
      Confirmation mot de passe<br /><input type="password" name="new_passwd_verif" value=""><br />
      Mail<br /><input type="email" name="new_email" value=""><br /><br />
      <input class="button" type="submit" name="submit_new" value="OK">
      <?php
      if (!empty($_POST['new_login']) and !empty($_POST['new_passwd']) and !empty($_POST['new_passwd_verif']) and !empty($_POST['new_email']) and $_POST['submit_new'] == "OK") {

          $new_login = trim($_POST['new_login']);
          $new_email = trim($_POST['new_email']);
          $db = new Users($new_login, $_POST['new_passwd'], $_POST['new_passwd_verif'], $new_email, "");
          $db->sendConfirmationUser();
          if ($db->message)
            echo '<p style="color:red;">' . $db->message . '</p>';
      }
      else if ($_GET['q'] != "") {

        $token = $_GET['q'];
        $db = new Users("", "", "", "", $token);
        $db->confirmUser();
        if ($db->message)
          echo '<p style="color:red;">' . $db->message . '</p>';
      }
      ?>
    </form>
  </div>
</body>
</html>


<?php

$pdo = null;

 ?>
