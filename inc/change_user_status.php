<?php

// CHARGEMENT DU FICHIER "config.php"
require 'config.php'

// CHARGEMENT DU FICHIER "connexion.php"
require 'connexion.php';

// CHARGEMENT DU FICHIER "functions.php"
require 'functions.php';

if(isset($_GET['id_user']) && isset($_GET['status']))
{
  Change_User_Status($_GET['id_user'],$_GET['status']);
  echo "OK";
}
else
{
  echo "KO";
}
