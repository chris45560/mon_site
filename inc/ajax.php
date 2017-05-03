<?php

// CHARGEMENT DU FICHIER "config.php"
require 'config.php';

// CHARGEMENT DU FICHIER connexion.php
require 'connexion.php';

// CHARGEMENT DES fonctions
require 'functions.php';

session_start();

if(isset($_POST) && isset($_POST['comment']) && isset($_SESSION['id']))
{
	if(empty($_POST['comment']))
	{
		echo "empty_comment";
		exit;
	}

	$comment = htmlspecialchars($_POST['comment']);

	$aPosts = AjoutComment(
		$_POST['id_post'],
		$_SESSION['id'],
		$comment
	);

	$aTab = array(
		'date' => Date("d/m/Y à H:i"),
		'comment_description' => $comment,
		'user_pseudo' => $_SESSION['pseudo']
	);

	include("../views/comments.phtml");
}
else
{
	echo "KO";
}
