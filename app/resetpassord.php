<!-- Comment récupérer login associé au mail envoyé ? -->

<html>
  <head>
    <meta charset="utf-8">
    <title>Nouveau mot de passe</title>
  </head>
  <body>
     <a href="home.php">Home</a><br />
    <h1>Réinitialiser mot de passe</h1>
    <form class="" action="home.php" method="post">
      Nouveau mot de passe <input type="text" name="new_passwd" value="">
      <input type="submit" name="submit_passwd" value="OK">
    </form>
    <?php
    require 'class/users.php';
    if ($_GET['q'] != "") {
      $token = $_GET['q'];
      $db = new Users("", $new_passwd, "", $token);
      $db->resetPassword();
      if ($db->message)
        echo '<p style="color:red;">' . $db->message . '</p>';
    }
     ?>
  </body>
</html>
