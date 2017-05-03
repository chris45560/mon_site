<?php

// CHARGEMENT DU FICHIER "config.php"
require 'config.php';

require('../assets/pdf/fpdf181/fpdf.php');
// require('DancingScript-Regular.php');

// CHARGEMENT DU FICHIER "connexion.php"
require 'connexion.php';

// CHARGEMENT DU FICHIER "functions.php"
require 'functions.php';

session_start();

define('EURO', chr(128));
define('A', chr(224));

class PDF extends FPDF
{
  /**
  * Cette fonction permet de récupérer le tableau comprenant la liste de toutes les lignes de commandes (nom de la carte cadeau, id de la ligne de commande, prix unitaire, quantité commandée, id de la commande) de la commande (order) ayant l'id passé en paramètre
  * @author Christophe HEBERT <christophe.hebert45@gmail.com>
  * @since 1.0
  * @return array Retourne le tableau comprenant la liste de toutes les lignes de commandes (nom de la carte cadeau, id de la ligne de commande, prix unitaire, quantité commandée, id de la commande) de la commande (order) ayant l'id passé en paramètre
  */
  function LoadData()
  {
    $aEachOrder = getAllOrderLines($_GET['order_id']);
    return $aEachOrder;
  }

  /**
  * Cette fonction permet de créer et de construire un tableau dans un PDF
  * @author Christophe HEBERT <christophe.hebert45@gmail.com>
  * @param array $header Tableau comportant les noms des champs du tableau PDF
  * @param array $data Tableau comportant les données du tableau PDF
  * @since 1.0
  */
  function FancyTable($header, $data)
  {
    // Couleurs, épaisseur du trait et police grasse
    $this->SetFillColor(100,100,100); // R V B - Couleur de Remplissage
    $this->SetTextColor(255, 255, 255); // R V B - Couleur du Texte
    $this->SetDrawColor(0,0,0); // R V B - Couleur du Tracé
    $this->SetLineWidth(.3); // Epaisseur des Traits - Valeur par défaut: 0,2mm
    $this->SetFont('','B'); // Fixe la Police - Si vide, la famille courante est conservée

    // En-tête
    $w = array(50, 35, 35, 40);
    for($i=0;$i<count($header);$i++)
    $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    // Imprime une Cellule
    // Largeur de la Cellule
    // Hauteur de la Cellule
    // Chaîne à imprimer

    // Indique si des bords doivent être tracés autour de la cellule. La valeur peut être soit un nombre :
    // 0 : aucun bord
    // 1 : cadre
    // soit une chaîne contenant certains ou tous les caractères suivants (dans un ordre quelconque) :
    // L : gauche
    // T : haut
    // R : droit
    // B : bas
    // La valeur par défaut est 0.

    // Indique où se déplace la position courante après l'appel à la méthode. Les valeurs possibles sont :
    // 0 : à droite
    // 1 : au début de la ligne suivante
    // 2 : en dessous
    // Mettre 1 est équivalent à mettre 0 et appeler la méthode Ln() juste après.
    // La valeur par défaut est 0.

    // Permet de centrer ou d'aligner le texte. Les valeurs possibles sont :
    // L ou chaîne vide : alignement à gauche (valeur par défaut)
    // C : centrage
    // R : alignement à droite

    // Indique si le fond doit être coloré ou transparent - Par défaut : false

    $this->Ln();
    // Effectue un Saut de Ligne
    // L'abscisse courante est ramenée à la valeur de la marge gauche et
    // l'ordonnée augmente de la valeur indiquée en paramètre.
    // L'amplitude du saut de ligne.
    // Par défaut, la valeur est égale à la hauteur de la dernière cellule imprimée.

    // Restauration des couleurs et de la police
    $this->SetFillColor(255,255,255);
    $this->SetTextColor(0);
    $this->SetFont('');

    // Données
    $fill = true;
    foreach($data as $row)
    {
      $this->SetX(23);
      $this->Cell($w[0],6,"     ".utf8_decode($row['giftscard_name']),'LR',0,'L',$fill);
      $this->Cell($w[1],6,$row['orderline_priceeach'].",00 ".EURO,'LR',0,'C',$fill);
      $this->Cell($w[2],6,$row['orderline_quantityordered'],'LR',0,'C',$fill);
      $this->Cell($w[3],6,($row['orderline_priceeach']*$row['orderline_quantityordered']).",00 ".EURO,'LR',0,'C',$fill);
      $this->Ln();
      // $fill = !$fill;
    }
    // Trait de terminaison
    $this->SetX(23);
    $this->Cell(array_sum($w),0,'','T');
  }
}

if(isset($_GET) && isset($_GET['order_id']))
{
  $aOrder = get_Order_Id($_GET['order_id']);

  if(!empty($aOrder) && is_numeric($aOrder['order_id'])) // is_numeric ==> Détermine si une variable est de type numérique
  {
    if(isset($_SESSION['id']) && ($_SESSION['id'] == $aOrder['order_user_id']))
    {
      if(VerifContactUser($_SESSION['pseudo'])) // Coordonnées de l'utilisateur remplies
      {
        $aUser = RecupInfosUser_Id($aOrder['order_user_id']);

        $pdf = new PDF();
        // $pdf=new FPDF('P','mm',A4);
        // Création du PDF en mode Portrait, Unités en mm, Format A4
        // P => Portrait
        // L => Paysage

        $pdf->SetAutoPageBreak(0);
        // Active le mode saut de page automatique.
        // En cas d'activation, le second paramètre représente la distance par rapport au bas de la page qui déclenche le saut.
        // Par défaut, le mode est activé et la marge vaut 2 cm

        $pdf->AddPage();
        // Création d'une page dans le document, sinon vide

        $pdf->Image('../assets/img/test4.png',0,0,210,297);


        $pdf->SetFont('Arial','B',16);
        // Avant d'imprimer du texte, il est impératif de définir la police avec SetFont().
        // On choisit ici de l'Arial gras en taille 16
        // B => Gras
        // I => Italique
        // U => Souligné
        // Police normale => Chaîne vide ""

        // $pdf->Image('../assets/img/mojito_fraise.jpg',10,10,50,50);
        // Importation du logo en forçant la taille
        // Chemin ou URL de l'Image
        // Abscisse du coin supérieur gauche
        // Ordonnée du coin supérieur gauche
        // Largeur de l'Image
        // Hauteur de l'Image

        $pdf->SetXY(55,20);
        // Abscisse
        // Ordonnée

        $pdf->AddFont('Dancing Script','','DancingScript-Regular.php');
        $pdf->SetFont('Dancing Script','','35');
        $pdf->MultiCell(100,15,'Mon Atelier Cocktails',0,"C");
        // Placement du pointeur et Ecriture du Titre

        // Largeur des cellules, si 0, elles s'étendent jusqu'à la marge droite de la page

        // Hauteur des cellules

        // Chaîne à imprimer

        // Indique si des bords doivent être tracés autour du bloc de cellules. La valeur peut être soit un nombre :
        // 0 : aucun bord
        // 1 : cadre
        // soit une chaîne contenant certains ou tous les caractères suivants (dans un ordre quelconque) :
        // L : gauche
        // T : haut
        // R : droit
        // B : bas
        // La valeur par défaut est 0.

        // Contrôle l'alignement du texte. Les valeurs possibles sont :
        // L : alignement à gauche
        // C : centrage
        // R : alignement à droite
        // J : justification (valeur par défaut)

        // Indique si le fond des cellules doit être coloré (true) ou transparent (false). Valeur par défaut : false.

        $pdf->SetXY(75,40);
        $pdf->SetFont('Arial','B',12);

        $date = new DateTime($aOrder['order_creationtimestamp']);
        $date = $date->format('d-m-Y '.A.' H:i:s');

        $pdf->Cell(15,5,'Date : ',0,"L");
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(60,5,$date,0,"L");

        $pdf->SetXY(87,50);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(28,5,'Commande :',0,"L");
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(25,5,$aOrder['order_id'],0,"L");


        $pdf->SetXY(71,75);
        $pdf->SetFont('Arial','B',16);
        $texte = "Informations Utilisateur";
        $pdf->Cell(120,2,$texte);

        $pdf->SetFont('Arial','',14);
        $pdf->SetXY(49,85);

        if($aUser['user_civility']=="monsieur")
        { $civilite = "Monsieur"; }
        else{ $civilite = "Madame"; }
        $country = strtoupper($aUser['user_country']);

        $pdf->SetFillColor(255,255,255);



        $pdf->MultiCell(110,7,
        $civilite." ".
        utf8_decode($aUser['user_lastname'])." ".
        utf8_decode($aUser['user_firstname']).
        "\n".
        utf8_decode($aUser['user_address']).
        "\n".
        utf8_decode($aUser['user_city'])." - ".
        $aUser['user_zipcode'].
        "\n".
        utf8_decode($country),'1','C','true'
      );

      $pdf->SetXY(62,140);
      $pdf->SetFont('Arial','B',16);
      $texte = "Récapitulatif de la Commande";
      $pdf->Cell(120,2,utf8_decode($texte));

      $pdf->SetFont('Arial','B',14);
      $quantity = "Quantité";
      $header = array('Nom du Produit', 'Prix Unitaire', utf8_decode($quantity), 'Prix Total');
      $data = $pdf->LoadData();
      $pdf->SetXY(23,150);
      $pdf->FancyTable($header,$data);

      $pdf->SetXY(67,200);
      $pdf->SetFont('Arial','B',16);
      $pdf->SetFillColor(244,102,27);
      $pdf->SetTextColor(0,0,0);
      $texte = "Montant Total : ".$aOrder['order_totalamount'].",00 ".EURO;
      $pdf->Cell(70,10,$texte,'1','O','C','true');


      $pdf->SetXY(80,-10);
      $pdf->SetFont('Arial','I',8);
      $texte="© Copyright Mon Atelier Cocktails";
      $pdf->Cell(120,2,utf8_decode($texte));
      // Placer le Copyright

      $pdf->Output();
      // Le Document est terminé et envoyé au navigateur
    }
    else // Coordonnées de l'utilisateur non remplies
    {
      echo "<script type='text/javascript'>document.location.replace('../user_profile');</script>";
    }
  }
  else
  {
    echo "<script type='text/javascript'>document.location.replace('../list_order');</script>";
  }
}
else
{
  echo "<script type='text/javascript'>document.location.replace('../list_order');</script>";
}
}
