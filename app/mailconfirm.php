<?php

  $headers  = 'MIME-Version: 1.0' . "\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
  $headers .= "From: camagru-noreply@student.42.fr\n";
  $mailbody = "<html><body>";
  $mailbody .= "<p>Bonjour " . $this->login . ",</p>";
  $mailbody .= "Pour confirmer votre compte pour Camagru, cliquez sur le lien suivant dans les 48 heures : ";
  $mailbody .= "<a href=http://" . $pwrurl . ">Suivez-moi</a></p>";
  $mailbody .= "<p>A bientôt !</p>";
  $mailbody .= "<p>Camagru</p>";
  $mailbody .= "</body></html>";

  if (mail($this->email, "Camagru - Confirmez votre compte", $mailbody, $headers))
    $this->message = "Un mail vous a été envoyé pour confirmer votre compte.";
  else
    return $this->message = "Le mail d'inscription n'a pas pu être envoyé.";

  // confirmation mail with a link to be followed to confirm the account
?>
