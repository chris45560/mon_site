<?php

// CHARGEMENT DU FICHIER "config.php"
require 'config.php';

// CHARGEMENT DU FICHIER "connexion.php"
require 'connexion.php';

// CHARGEMENT DU FICHIER "functions.php"
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

if(isset($_GET['new_quantity']) && isset($_GET['id']))
{
  if(
    (
      $_GET['id'] == 1 ||
      $_GET['id'] == 2 ||
      $_GET['id'] == 3)
      &&
      (
        $_GET['new_quantity'] == 1 ||
        $_GET['new_quantity'] == 2 ||
        $_GET['new_quantity'] == 3 ||
        $_GET['new_quantity'] == 4 ||
        $_GET['new_quantity'] == 5 ||
        $_GET['new_quantity'] == 6 ||
        $_GET['new_quantity'] == 7 ||
        $_GET['new_quantity'] == 8 ||
        $_GET['new_quantity'] == 9 ||
        $_GET['new_quantity'] == 10
        )
        )
        {
          $_SESSION['basket'][$_GET['id']]['giftscard_quantity'] = $_GET['new_quantity'];

          $_SESSION['basket'][$_GET['id']]['giftscard_total_amount'] =
          ($_SESSION['basket'][$_GET['id']]['giftscard_quantity']
          *$_SESSION['basket'][$_GET['id']]['giftscard_saleprice']);

          Adjust_Total();
          Adjust_Quantity_Total();

          $aTab = [
            'total_amount_id' => $_SESSION['basket'][$_GET['id']]['giftscard_total_amount'],
            'total' => $_SESSION['basket']['total'],
            'total_quantity' => $_SESSION['basket']['quantity_total']
          ];
          echo json_encode($aTab);
        }
        else
        {
          echo "KO";
        }
      }
      else
      {
        echo "KO";
      }
