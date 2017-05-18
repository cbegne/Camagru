<html>
  <head>
    <meta charset="utf-8">
    <title>Nouveau mot de passe</title>
    <link rel="stylesheet" href="../public/css/home.css">
    <link rel="stylesheet" href="../public/css/headerfooter.css">
  </head>
  <body>
    <?php include 'header.php'; ?>
    <h2>Réinitialiser mot de passe</h2>
    <form class="" action="#" method="post">
      Nouveau mot de passe <br /><input type="password" name="new_passwd" value=""><br />
      Confirmation mot de passe<br /><input type="password" name="new_passwd_verif" value=""><br />
       <input id="buttonforgot" class="button" type="submit" name="submit" value="OK">
    </form>
    <?php
    require '../class/users.class.php';
    if (htmlentities($_GET['q']) != "" && !empty($_POST['new_passwd']) && !empty($_POST['new_passwd_verif']) && $_POST['submit'] == "OK") {
      $token = htmlentities($_GET['q']);
      $new_passwd = $_POST['new_passwd'];
      $db = new Users("", $_POST['new_passwd'], $_POST['new_passwd_verif'], "", $token);
      $db->resetPassword();
      if ($db->message)
        echo '<p style="color:red;">' . $db->message . '</p>';
    }
     ?>
  </body>
</html>
