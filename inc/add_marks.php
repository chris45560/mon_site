<?php

// CHARGEMENT DU FICHIER "config.php"
require 'config.php';

// CHARGEMENT DU FICHIER connexion.php
require 'connexion.php';

// CHARGEMENT DES fonctions
require 'functions.php';

session_start();

if(isset($_POST) && isset($_POST['cocktail_name']) && isset($_POST['note']) && isset($_SESSION['id']))
{
  Add_Marks_Cocktail_User($_SESSION['id'],$_POST['cocktail_name'],$_POST['note']); // Ajout / Mise à jour dans la Table "users"
  Update_Marks_Post($_POST['cocktail_name']); // Mise à jour de la note dans la Table "posts"
  echo "OK";
}
else
{ echo "KO"; }
