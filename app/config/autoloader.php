<?php

// Systeme permettant le chargement dynamique des differentes classes

class Autolauder {

  static function register() {
    spl_autoload_register(array(__CLASS__, 'autoload')); // permet de creer une fonction pour enregistrer l'autoloading (pour eviter conflits avec autres developpeur utilisant __autoload qui ne peut etre instanciee aqu'une fois)
  }

  static function autoload($class_name) {
    require 'class/' . $class_name . '.php';
  }

}

 ?>
