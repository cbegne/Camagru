<?php

session_start() or die("Failed to resume session\n");

if (!isset($_SESSION['logged'])) {
  require_once('app/config/setup.php');
  header("Location: app/home.php"); // redirection vers page connexion
}
else
  header("Location: app/webcam.php"); // redirection vers page webcam

?>
