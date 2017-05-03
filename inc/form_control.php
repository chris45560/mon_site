<?php

/**
* Cette fonction permet de crypter la variable passée en paramètre via une méthode de hashage (sha1, sha256 ou sha512) et un grain de sable
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sPwd Chaîne à crypter
* @since 1.0
* @return string Retourne une chaîne de caractères contenant l'empreinte numérique calculée en chiffres hexadécimals minuscules.
*/
function cryptagePwd($sPwd)
{
  $sMethod = 'sha1';
  $sGrainDeSable = 'PïzZ@sh@àiVr&Mièl';
  $sPwd .= $sGrainDeSable;

  $aListAlgos = hash_algos();

  if (in_array('sha512',$aListAlgos)) {
    $sMethod = 'sha512';
  }
  elseif (in_array('sha256',$aListAlgos)) {
    $sMethod = 'sha256';
  }
  return hash($sMethod, $sPwd);
}

/**
* Cette fonction permet de supprimer tous les symbôles et de convertir les caractères spéciaux présents dans la variable passée en paramètre en caractères "normaux" sans accentuation
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Chaîne à convertir
* @since 1.0
* @return string Retourne une chaîne de caractères sans symbôles avec les caractères spéciaux convertis en caractères "normaux" sans accentuation
*/
function Convert_String($sValue)
{
  $aTab_Symbols = array("@","#","&","(","§","!",")","[","|","]","°","-","_","^","¨","$","*","€","%","`"
  ,"£",":","/","=","+","?",".",",",";"," ","'",'"');
  $aTab_Symbols_Accept = array("-"," ","'");
  $aTab_Accents_Maj = array(
    'Á'=>'A','À'=>'A','Â'=>'A','Ä'=>'A','Ã'=>'A','Å'=>'A',
    'Ç'=>'C',
    'É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E',
    'Í'=>'I','Ï'=>'I','Î'=>'I','Ì'=>'I',
    'Ñ'=>'N',
    'Ó'=>'O','Ò'=>'O','Ô'=>'O','Ö'=>'O','Õ'=>'O',
    'Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U',
    'Ý'=>'Y'
  );
  $aTab_Accents_Min = array(
    'á'=>'a','à'=>'a','â'=>'a','ä'=>'a','ã'=>'a','å'=>'a',
    'ç'=>'c',
    'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
    'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
    'ñ'=>'n',
    'ó'=>'o','ò'=>'o','ô'=>'o','ö'=>'o','õ'=>'o',
    'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',
    'ý'=>'y', 'ÿ'=>'y'
  );

  $sValue = str_replace($aTab_Symbols_Accept, "", $sValue); // Remplace toutes les occurrences dans une chaîne
  $sValue = strtr($sValue, $aTab_Accents_Maj); // Remplace des caractères dans une chaîne
  $sValue = strtr($sValue, $aTab_Accents_Min); // Remplace des caractères dans une chaîne
  return $sValue;
}

/**
* Cette fonction permet de connaître l'âge d'une personne via sa date de naissance passée en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $naiss Date de naissance
* @since 1.0
* @return int Retourne un entier représentant l'âge de la personne
*/
function Convert_Age($naiss)
{
  list($annee, $mois, $jour) = explode('-', $naiss);
  $today['mois'] = date('n');
  $today['jour'] = date('j');
  $today['annee'] = date('Y');
  $annees = $today['annee'] - $annee;
  if ($today['mois'] <= $mois) {
    if ($mois == $today['mois']) {
      if ($jour > $today['jour'])
      $annees--;
    }
    else
    $annees--;
  }
  return $annees;
}

// is_string : Détermine si une variable est de type chaîne de caractères

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", alphanumérique et n'est pas faite que de caractères blancs. Elle contrôle également si la valeur (pseudo) passée en paramètre n'est pas déjà présent dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (pseudo de l'utilisateur)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre pseudo ne doit comporter que des chiffres et/ou des lettres');) ou (throw new DomainException('Ce pseudo est déjà pris');) si le contrôle n'est pas conforme, rien sinon
*/
function PseudoControl($sValue){
  if(!is_string($sValue) || ctype_alnum($sValue) == false || ctype_space($sValue)){
    throw new DomainException('Votre pseudo ne doit comporter que des chiffres et/ou des lettres');
  }
  if(RecupInfosUser($sValue) != 0){
    throw new DomainException('Ce pseudo est déjà pris');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", alphanumérique et n'est pas faite que de caractères blancs.
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (pseudo de l'utilisateur)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre pseudo ne doit comporter que des chiffres et/ou des lettres');) si le contrôle n'est pas conforme, rien sinon
*/
function PseudoControl_Reset($sValue){
  if(!is_string($sValue) || ctype_alnum($sValue) == false || ctype_space($sValue)){
    throw new DomainException('Votre pseudo ne doit comporter que des chiffres et/ou des lettres');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", alphabétique et n'est pas faite que de caractères blancs.
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (nom de l'utilisateur)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre nom ne doit comporter que des lettres');) si le contrôle n'est pas conforme, rien sinon
*/
function NameControl($sValue){
  if(!is_string($sValue) || ctype_alpha($sValue) == false || ctype_space($sValue)){
    throw new DomainException('Votre nom ne doit comporter que des lettres');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", alphanumérique et n'est pas faite que de caractères blancs.
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (password)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre mot de passe ne doit comporter que des chiffres et/ou des lettres');) si le contrôle n'est pas conforme, rien sinon
*/
function PasswordControl($sValue){
  if(!is_string($sValue) || ctype_alnum($sValue) == false || ctype_space($sValue)){
    throw new DomainException('Votre mot de passe ne doit comporter que des chiffres et/ou des lettres');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre ($sValue) est égale au password de l'utilisateur
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler
* @param string $sActual_Password Password de l'utilisateur
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre mot de passe est erroné');) si le contrôle n'est pas conforme, rien sinon
*/
function PasswordModifControl($sValue, $sActual_Password){
  if($sValue != $sActual_Password){
    throw new DomainException('Votre mot de passe est erroné');
  }
}

/**
* Cette fonction contrôle si les 2 valeurs passées en paramètre sont égales
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sMdp 1ère valeur (mot de passe)
* @param string $sConfirmMdp 2ème valeur (confirmation du mot de passe)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Les 2 Mots de Passe que vous avez entrés sont différents');) si le contrôle n'est pas conforme, rien sinon
*/
function ConfirmPasswordControl($sMdp, $sConfirmMdp){
  if($sMdp != $sConfirmMdp){
    throw new DomainException('Les 2 Mots de Passe que vous avez entrés sont différents');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", si c'est une adresse de courriel selon la syntaxe défini par la RFC 822 et si elle n'est pas faite que de caractères blancs. Elle contrôle également si la valeur passée en paramètre n'est pas déjà associée à un utilisateur différent de celui ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @param string $sValue Valeur à contrôler (e-mail)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre E-mail n\'est pas correctement écrit');) ou (throw new DomainException('Votre E-mail est déjà pris');) si le contrôle n'est pas conforme, rien sinon
*/
function EmailControl($iId_User, $sValue){
  if(!is_string($sValue) || !filter_var($sValue, FILTER_VALIDATE_EMAIL) || ctype_space($sValue)){
    throw new DomainException('Votre E-mail n\'est pas correctement écrit');
  }
  $aTab_User = Recup_List_Users_Mail($sValue);
  if($aTab_User != 0 && $aTab_User['user_id'] != $iId_User){
    throw new DomainException('Votre E-mail est déjà pris');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", si c'est une adresse de courriel selon la syntaxe défini par la RFC 822 et si elle n'est pas faite que de caractères blancs. Elle contrôle également si la valeur passée en paramètre n'est pas déjà associée à un utilisateur
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (e-mail)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre E-mail n\'est pas correctement écrit');) ou (throw new DomainException('Votre E-mail est déjà pris');) si le contrôle n'est pas conforme, rien sinon
*/
function EmailControl_Register($sValue){
  if(!is_string($sValue) || !filter_var($sValue, FILTER_VALIDATE_EMAIL) || ctype_space($sValue)){
    throw new DomainException('Votre E-mail n\'est pas correctement écrit');
  }
  if(Recup_List_Users_Mail($sValue) != 0){
    throw new DomainException('Votre E-mail est déjà pris');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", si c'est une adresse de courriel selon la syntaxe défini par la RFC 822 et si elle n'est pas faite que de caractères blancs.
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (e-mail)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre E-mail n\'est pas correctement écrit');) si le contrôle n'est pas conforme, rien sinon
*/
function EmailControl_Reset($sValue){
  if(!is_string($sValue) || !filter_var($sValue, FILTER_VALIDATE_EMAIL) || ctype_space($sValue)){
    throw new DomainException('Votre E-mail n\'est pas correctement écrit');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string" et si c'est une adresse de courriel selon la syntaxe défini par la RFC 822
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (e-mail)
* @since 1.0
* @return boolean Retourne true si le contrôle est conforme, false sinon
*/
function EmailControl_Contact($sValue){
  if(filter_var($sValue, FILTER_VALIDATE_EMAIL) && is_string($sValue)){
    return true;
  }
  else{ return false; }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", si c'est un entier et si sa taille est de 2 caractères
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (jour de naissance)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre jour de naissance doit comporter 2 chiffres');) si le contrôle n'est pas conforme, rien sinon
*/
function BirthDateControl_Day($sValue){
  if(!is_string($sValue) || ctype_digit($sValue) == false || strlen($sValue) != 2){
    throw new DomainException('Votre jour de naissance doit comporter 2 chiffres');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", si c'est un entier et si sa taille est de 2 caractères
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (mois de naissance)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre mois de naissance ne doit comporter que 2 chiffres');) si le contrôle n'est pas conforme, rien sinon
*/
function BirthDateControl_Month($sValue){
  if(!is_string($sValue) || ctype_digit($sValue) == false || strlen($sValue) != 2){
    throw new DomainException('Votre mois de naissance ne doit comporter que 2 chiffres');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", si c'est un entier et si sa taille est de 4 caractères
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (année de naissance)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre année de naissance doit comporter 4 chiffres');) si le contrôle n'est pas conforme, rien sinon
*/
function BirthDateControl_Year($sValue){
  if(!is_string($sValue) || ctype_digit($sValue) == false || strlen($sValue) != 4){
    throw new DomainException('Votre année de naissance doit comporter 4 chiffres');
  }
}

/**
* Cette fonction contrôle si les 3 valeurs passées en paramètre forment une date grégorienne, si cette date est inférieure ou égale à la date en cours et si, une fois converti en âge, est supérieure ou égale à 18
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iDay Jour de naissance (2 chiffres)
* @param int $iMonth Mois de naissance (2 chiffres)
* @param int $iYear Année de naissance (4 chiffres)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('La Date n\'existe pas');) ou (throw new DomainException('Vous ne pouvez pas être né plus tard qu\'aujourd\'hui');) ou throw new DomainException('Vous devez être majeur pour vous inscrire sur ce site'); si le contrôle n'est pas conforme, rien sinon
*/
function BirthDateControl($iDay, $iMonth, $iYear){
  if(checkdate($iMonth, $iDay, $iYear) == false){
    throw new DomainException('La Date n\'existe pas');
  }
  $sBirthDate = $iYear.'-'.$iMonth.'-'.$iDay;
  $date = new DateTime($sBirthDate);
  if($date->format('Y-m-d') > date('Y-m-d')){
    throw new DomainException('Vous ne pouvez pas être né plus tard qu\'aujourd\'hui');
  }
  if(Convert_Age($sBirthDate) < 18){
    throw new DomainException('Vous devez être majeur pour vous inscrire sur ce site');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string" et si elle égale à l'entier : 1048576
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (MAX_FILE_SIZE)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Ne touchez pas à cet input');) si le contrôle n'est pas conforme, rien sinon
*/
function MAX_FILE_SIZE($sValue){
  if(!is_string($sValue) || $sValue != 1048576){
    throw new DomainException('Ne touchez pas à cet input');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string" et si elle est égale soit à la chaîne de caractères "monsieur" soit à la chaîne de caractères "madame"
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (civilité)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre civilité est soit "monsieur" soit "madame"');) si le contrôle n'est pas conforme, rien sinon
*/
function CivilityControl($sValue){
  if(!is_string($sValue) || ($sValue != "monsieur" && $sValue != "madame")){
    throw new DomainException('Votre civilité est soit "monsieur" soit "madame"');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", alphabétique, n'est pas faite que de caractères blancs et si elle n'est pas vide
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (prénom)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre prénom ne doit comporter que des lettres');) si le contrôle n'est pas conforme, rien sinon
*/
function FirstNameControl($sValue){
  if(!is_string($sValue) || $sValue == "" || ctype_alpha($sValue) == false || ctype_space($sValue)){
    throw new DomainException('Votre prénom ne doit comporter que des lettres');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", alphabétique, n'est pas faite que de caractères blancs et si elle n'est pas vide
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (nom)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre nom ne doit comporter que des lettres');) si le contrôle n'est pas conforme, rien sinon
*/
function LastNameControl($sValue){
  if(!is_string($sValue) || $sValue == "" || ctype_alpha($sValue) == false  || ctype_space($sValue)){
    throw new DomainException('Votre nom ne doit comporter que des lettres');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string" puis, après avoir été converti en une chaîne de caractères sans symbôles avec les caractères spéciaux convertis en caractères "normaux" sans accentuation, si elle est alphanumérique, n'est pas faite que de caractères blancs et si elle n'est pas vide
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (adresse)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre adresse ne doit comporter que des chiffres et/ou des lettres, les espaces, les tirets et les apostrophes sont acceptés');) si le contrôle n'est pas conforme, rien sinon
*/
function AddressControl($sValue){
  if(is_string($sValue))
  {
    $sValue = Convert_String($sValue);
    if($sValue == "" || ctype_alnum($sValue) == false  || ctype_space($sValue)){
      throw new DomainException('Votre adresse ne doit comporter que des chiffres et/ou des lettres, les espaces, les tirets et les apostrophes sont acceptés');
    }
  }
  else
  {
    throw new DomainException('Votre adresse ne doit comporter que des chiffres et/ou des lettres, les espaces, les tirets et les apostrophes sont acceptés');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string" puis, après avoir été converti en une chaîne de caractères sans symbôles avec les caractères spéciaux convertis en caractères "normaux" sans accentuation, si elle est alphanumérique, n'est pas faite que de caractères blancs et si elle n'est pas vide
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (ville)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre ville ne doit comporter que des lettres et aucun accent, les espaces, les tirets et les apostrophes sont acceptés');) si le contrôle n'est pas conforme, rien sinon
*/
function CityControl($sValue){
  if(is_string($sValue))
  {
    $sValue = Convert_String($sValue);
    if($sValue == "" || ctype_alpha($sValue) == false || ctype_space($sValue)){
      throw new DomainException('Votre ville ne doit comporter que des lettres et aucun accent, les espaces, les tirets et les apostrophes sont acceptés');
    }
  }
  else
  {
    throw new DomainException('Votre ville ne doit comporter que des lettres et aucun accent, les espaces, les tirets et les apostrophes sont acceptés');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", si elle n'est pas vide, si c'est un entier et si sa taille est de 5 caractères
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (code postal)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre code postal doit comporter 5 chiffres');) si le contrôle n'est pas conforme, rien sinon
*/
function ZipCodeControl($sValue){
  if(!is_string($sValue) || $sValue == "" || ctype_digit($sValue) == false || strlen($sValue) != 5){
    throw new DomainException('Votre code postal doit comporter 5 chiffres');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string" puis, après avoir été converti en une chaîne de caractères sans symbôles avec les caractères spéciaux convertis en caractères "normaux" sans accentuation, si elle est alphanumérique, n'est pas faite que de caractères blancs et si elle n'est pas vide
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (pays)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre pays ne doit comporter que des lettres, les espaces, les tirets et les apostrophes sont acceptés');) si le contrôle n'est pas conforme, rien sinon
*/
function CountryControl($sValue){
  if(is_string($sValue))
  {
    $sValue = Convert_String($sValue);
    if($sValue == "" || ctype_alpha($sValue) == false || ctype_space($sValue)){
      throw new DomainException('Votre pays ne doit comporter que des lettres, les espaces, les tirets et les apostrophes sont acceptés');
    }
  }
  else
  {
    throw new DomainException('Votre pays ne doit comporter que des lettres, les espaces, les tirets et les apostrophes sont acceptés');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string", si elle n'est pas vide, si c'est un entier et si sa taille est de 10 caractères
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (numéro de téléphone)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre numéro de téléphone doit comporter 10 chiffres');) si le contrôle n'est pas conforme, rien sinon
*/
function PhoneControl($sValue){
  if(!is_string($sValue) || $sValue == "" || ctype_digit($sValue) == false || strlen($sValue) != 10){
    throw new DomainException('Votre numéro de téléphone doit comporter 10 chiffres');
  }
}

/**
* Cette fonction contrôle si la valeur passée en paramètre est bien de type "string" et si elle est égale soit à la chaîne de caractères "yes" soit à la chaîne de caractères "no"
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sValue Valeur à contrôler (réponse à l'inscription à la newsletter)
* @since 1.0
* @return DomainException Retourne une erreur (throw new DomainException('Votre réponse à la newsletter est soit "yes" soit "no"');) si le contrôle n'est pas conforme, rien sinon
*/
function NewsletterControl($sValue){
  if(!is_string($sValue) || ($sValue != "yes" && $sValue != "no")){
    throw new DomainException('Votre réponse à la newsletter est soit "yes" soit "no"');
  }
}
