<?php

  $headers  = 'MIME-Version: 1.0' . "\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
  $headers .= "From: camagru-noreply@student.42.fr\n";
  $mailbody = "<html><body>";
  $mailbody .= "<p>Bonjour " . $this->login . ",</p>";
  $mailbody .= "<p>Pour réinitialiser votre mot de passe pour Camagru, cliquez sur le lien suivant dans les 48 heures : ";
  $mailbody .= "<a href=http://" . $pwrurl . ">Suivez-moi</a></p>";
  $mailbody .= "<p>A bientôt !</p>";
  $mailbody .= "<p>Camagru</p>";
  $mailbody .= "</body></html>";

  if (mail($email, "Camagru - Mot de passe oublié", $mailbody, $headers))
    $this->message = "Un mail vous a été envoyé pour réinitialiser votre mot de passe.";
  else
    $this->message = "Le mail de réinitialisation n'a pas pu être envoyé.";

  // mail to change password with a link to be followed
?>
