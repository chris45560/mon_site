<?php

// CHARGEMENT DU FICHIER "config.php"
require 'config.php';

// CHARGEMENT DU FICHIER connexion.php
require 'connexion.php';

// CHARGEMENT DES fonctions
require 'functions.php';

session_start();

/**
* Cette fonction détruit la variable $_SESSION['basket']
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return void
*/
function destroy(){
  if (isset($_SESSION['basket']))
  {
    unset($_SESSION['basket']);
  }
}

/**
* Cette fonction détruit la variable $_SESSION['basket']['id'] ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId Id d'une carte cadeau
* @since 1.0
* @return void
*/
function delProduct($iId)
{
  unset($_SESSION['basket'][$iId]);
}

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

if (!isset($_SESSION['basket']))
{
  $_SESSION['basket'] = array();
}

if(isset($_GET['id_gifts_cards']) && isset($_GET['giftcards_quantity']))
{
  if(
    (
      $_GET['id_gifts_cards'] == 1 ||
      $_GET['id_gifts_cards'] == 2 ||
      $_GET['id_gifts_cards'] == 3)
      &&
      (
        $_GET['giftcards_quantity'] == 1 ||
        $_GET['giftcards_quantity'] == 2 ||
        $_GET['giftcards_quantity'] == 3 ||
        $_GET['giftcards_quantity'] == 4 ||
        $_GET['giftcards_quantity'] == 5 ||
        $_GET['giftcards_quantity'] == 6 ||
        $_GET['giftcards_quantity'] == 7 ||
        $_GET['giftcards_quantity'] == 8 ||
        $_GET['giftcards_quantity'] == 9 ||
        $_GET['giftcards_quantity'] == 10
        )
        )
        {
          if(!isset($_SESSION['basket'][$_GET['id_gifts_cards']]))
          {
            $Id_Gift_Card = intval($_GET['id_gifts_cards']);

            $aBasket = Recup_Infos_Gift_Card($Id_Gift_Card);

            $_SESSION['basket'][$Id_Gift_Card] = $aBasket;
          }

          $_SESSION['basket'][$_GET['id_gifts_cards']]['giftscard_quantity'] += $_GET['giftcards_quantity'];

          if($_SESSION['basket'][$_GET['id_gifts_cards']]['giftscard_quantity'] > 10)
          {
            $_SESSION['basket'][$_GET['id_gifts_cards']]['giftscard_quantity'] = 10;

            $_SESSION['basket'][$_GET['id_gifts_cards']]['giftscard_total_amount'] =
            ($_SESSION['basket'][$_GET['id_gifts_cards']]['giftscard_quantity']
            *$_SESSION['basket'][$_GET['id_gifts_cards']]['giftscard_saleprice']);
            Adjust_Total();
            Adjust_Quantity_Total();
            echo "MAX";
          }

          else
          {
            $_SESSION['basket'][$_GET['id_gifts_cards']]['giftscard_total_amount'] =
            ($_SESSION['basket'][$_GET['id_gifts_cards']]['giftscard_quantity']
            *$_SESSION['basket'][$_GET['id_gifts_cards']]['giftscard_saleprice']);
            Adjust_Total();
            Adjust_Quantity_Total();

            echo "OK";
          }
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
