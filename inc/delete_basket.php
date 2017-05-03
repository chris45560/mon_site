<?php

// CHARGEMENT DU FICHIER "config.php"
require 'config.php';

// CHARGEMENT DU FICHIER connexion.php
require 'connexion.php';

// CHARGEMENT DES fonctions
require 'functions.php';

session_start();

/**
* Cette fonction met à jour le montant total du panier
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return void
*/
function Adjust_Total()
{
  $_SESSION['basket']['total'] = 0;

  foreach($_SESSION['basket'] as $iId => $aValue)
  {
    $_SESSION['basket']['total'] += $aValue["giftscard_total_amount"];
  }
}

/**
* Cette fonction met à jour la quantité totale du panier
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return void
*/
function Adjust_Quantity_Total()
{
  $_SESSION['basket']['quantity_total'] = 0;

  foreach($_SESSION['basket'] as $iId => $aValue)
  {
    $_SESSION['basket']['quantity_total'] += $aValue["giftscard_quantity"];
  }
}

if(isset($_GET['del_product_basket']))
{
  unset($_SESSION['basket'][$_GET['del_product_basket']]);
  Adjust_Total();
  Adjust_Quantity_Total();
  echo "OK";
}
else
{
  echo "KO";
}
