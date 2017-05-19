<?php

  $headers  = 'MIME-Version: 1.0' . "\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
  $headers .= "From: camagru-noreply@student.42.fr\n";
  $mailbody = "<html><body>";
  $mailbody .= "<p>Bonjour " . $login_pic . ",</p>";
  $mailbody .= "<p>Vous avez reçu un nouveau commentaire de " . $this->login . " pour votre photo : \"" . $this->comment . "\".</p>";
  $mailbody .= "<p>A bientôt !</p>";
  $mailbody .= "<p>Camagru</p>";
  $mailbody .= "</body></html>";

  mail($email, "Camagru - Nouveau commentaire", $mailbody, $headers);

  // mail comment to owner of the picture
 ?>
