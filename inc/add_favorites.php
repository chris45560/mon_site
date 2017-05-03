<?php

// CHARGEMENT DU FICHIER "config.php"
require 'config.php';

// CHARGEMENT DU FICHIER connexion.php
require 'connexion.php';

// CHARGEMENT DES fonctions
require 'functions.php';

session_start();

if(isset($_POST) && isset($_POST['cocktail_name']) && isset($_SESSION['id']) && isCocktailInBdd($_POST['cocktail_name']))
{
	$_POST['cocktail_name'] = htmlspecialchars($_POST['cocktail_name']);
	Add_Favorites_Cocktail_User($_SESSION['id'],$_POST['cocktail_name']);
	echo "OK";
}
else
{
	echo "KO";
}