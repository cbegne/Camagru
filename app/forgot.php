 <html>
   <head>
     <meta charset="utf-8">
     <title>Réinitialiser mot de passe</title>
   </head>
   <body>
     <a href="home.php">Home</a><br />
     <h1>Mot de passe oublié</h1>
     <form class="" action="#" method="post">
       Votre identifiant : <input type="text" name="login" value=""><br />
       <input type="submit" name="submit" value="Envoyer">
     </form>
     <?php
     require 'class/users.php';
     if (!empty($_POST['login']) && $_POST['submit'] == "Envoyer") {
       $login = trim($_POST['login']);
       $db = new Users($login, "", "", "");
       $db->sendPassword();
       if ($db->message)
         echo '<p style="color:red;">' . $db->message . '</p>';
     }
      ?>
   </body>
 </html>
