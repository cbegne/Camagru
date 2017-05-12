<?php

require 'database.php';

try {
  $pdo = new PDO($DB_DSN_FIRST, $DB_USER, $DB_PASSWORD);
  // set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ATTR_ERRMODE = Rapport d'erreur => ERRMODE_EXCEPTION = emet une exception (silent par defaut)

  $sql = 'CREATE DATABASE IF NOT EXISTS db_camagru';
  $pdo->exec($sql);
}
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

try {
  $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "CREATE TABLE IF NOT EXISTS `users` (
  `id_user` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `login` VARCHAR(30) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `mot_de_passe` VARCHAR(255) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `date_creation` DATETIME NOT NULL,
  `confirm` BOOLEAN DEFAULT 0 NOT NULL,
  `token` VARCHAR(255))";
  $pdo->exec($sql);   // use exec() because no results are returned

  $sql = "CREATE TABLE IF NOT EXISTS `pictures` (
  `id_pic` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `login` VARCHAR(30) NOT NULL,
  `date_creation` DATETIME NOT NULL,
  `pic` LONGBLOB NOT NULL)";
  $pdo->exec($sql);

  $sql = "CREATE TABLE IF NOT EXISTS `likes` (
  `id_like` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_pic` INT UNSIGNED NOT NULL,
  `login` VARCHAR(30) NOT NULL,
  `date_creation` DATETIME NOT NULL)";
  $pdo->exec($sql);

  $sql = "CREATE TABLE IF NOT EXISTS `comments` (
  `id_comment` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_pic` INT UNSIGNED NOT NULL,
  `comment` VARCHAR(255) NOT NULL,
  `login` VARCHAR(30) NOT NULL,
  `date_creation` DATETIME NOT NULL)";
  $pdo->exec($sql);
}
catch (PDOException $e) {
  die('Erreur : ' . $e->getMessage());
}

$pdo = null;
?>
