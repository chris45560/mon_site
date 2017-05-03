<?php

$oBdd = Connexion();

/******************************************************************************/
/******************************  UTILISATEURS  ********************************/
/******************************************************************************/

/*********** FONCTION RECUPERER LA LISTE DE TOUS LES UTILISATEURS *************/

/**
* Cette fonction récupère la liste de tous les utilisateurs
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau de tous les utilisateurs présents dans la base de données
*/
function Recup_List_Users()
{
	global $oBdd;
	$aUsers = array();
	$oQuery = $oBdd->prepare('SELECT * FROM users');
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aUsers = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aUsers;
}

/* FONCTION RECUPERER LA LISTE DE TOUS LES UTILISATEURS AYANT LE MAIL PASSE EN PARAMETRE */

/**
* Cette fonction récupère les infos de l'utilisateur ayant le mail passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sMail Mail de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant les infos de l'utilisateur ayant le mail passé en paramètre
*/
function Recup_List_Users_Mail($sMail)
{
	global $oBdd;
	$aUsers = array();
	$oQuery = $oBdd->prepare('SELECT * FROM users WHERE user_mail = ?');
	$bResult = $oQuery->execute([$sMail]);
	if ($bResult)
	{
		$aUsers = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aUsers;
}

/* FONCTION RECUPERER LES INFOS DE L'UTILISATEUR AYANT LE PSEUDO + LE MAIL PASSE EN PARAMETRE */

/**
* Cette fonction récupère les infos de l'utilisateur ayant le pseudo et le mail passés en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sPseudo_User Pseudo de l'utilisateur
* @param string $sMail_User Mail de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant les infos de l'utilisateur ayant le pseudo et le mail passés en paramètre
*/
function RecupInfosUser_Pseudo_Mail($sPseudo_User, $sMail_User)
{
	global $oBdd;
	$aUsers = array(
		'user_pseudo' => $sPseudo_User,
		'user_mail' => $sMail_User
	);
	$oQuery = $oBdd->prepare(
		'SELECT *
		FROM users
		WHERE user_mail = :user_mail
		AND user_pseudo = :user_pseudo'
	);
	$bResult = $oQuery->execute($aUsers);
	if ($bResult)
	{
		$aUsers = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aUsers;
}

/* FONCTION RECUPERER PSEUDO + NOM + PRENOM DE L'UTILISATEUR AYANT LE MAIL PASSE EN PARAMETRE */

/**
* Cette fonction récupère le pseudo, le nom et le prénom de l'utilisateur ayant le mail passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sMail Mail de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant le pseudo, le nom et le prénom de l'utilisateur ayant le mail passé en paramètre
*/
function Recup_Infos_User_Mail($sMail)
{
	global $oBdd;
	$aUsers = array();
	$oQuery = $oBdd->prepare(
		'SELECT user_pseudo, user_lastname, user_firstname
		FROM users
		WHERE user_mail = ?'
	);
	$bResult = $oQuery->execute([$sMail]);
	if ($bResult)
	{
		$aUsers = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aUsers;
}

/* FONCTION CHANGER LE PASSWORD DE L'UTILISATEUR AYANT LE PSEUDO PASSE EN PARAMETRE */

/**
* Cette fonction change le password de l'utilisateur ayant le pseudo passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sNewPass Nouveau password de l'utilisateur
* @param string $sPseudo_User Pseudo de l'utilisateur
* @since 1.0
* @return void
*/
function Change_Password_User($sNewPass, $sPseudo_User)
{
	global $oBdd;
	$aUsers = array(
		'user_pseudo' => $sPseudo_User,
		'user_password' => $sNewPass
	);
	$oQuery = $oBdd->prepare(
		'UPDATE users
		SET user_password = :user_password
		WHERE user_pseudo = :user_pseudo'
	);
	$bResult = $oQuery->execute($aUsers);
}

/*********** FONCTION AJOUTER LES COORDONNEES D'UN UTILISATEUR *************/
/**
* Cette fonction ajoute les coordonnées de l'utilisateur ayant l'id passé en paramètre dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @param string $sMail Nouveau Mail
* @param string $sBirthDate Nouvelle Date de Naissance
* @param string $sCivility Nouvelle Civilité
* @param string $sFirst_Name Nouveau Prénom
* @param string $sLast_Name Nouveau Nom
* @param string $sAddress Nouvelle Adresse
* @param string $sCity Nouvelle Ville
* @param string $iZipCode Nouveau Code Postal
* @param string $sCountry Nouveau Pays
* @param string $iPhone Nouveau Téléphone
* @since 1.0
* @return void
*/
function Add_User_Contact($iId_User,
$sMail,
$sBirthDate,
$sCivility,
$sFirst_Name,
$sLast_Name,
$sAddress,
$sCity,
$iZipCode,
$sCountry,
$iPhone)
{
	global $oBdd;

	$aTab = array(
		'user_id' => $iId_User,
		'user_mail' => $sMail,
		'user_birthdate' => $sBirthDate,
		'user_civility' => $sCivility,
		'user_firstname' => $sFirst_Name,
		'user_lastname' => $sLast_Name,
		'user_address' => $sAddress,
		'user_city' => $sCity,
		'user_zipcode' => $iZipCode,
		'user_country' => $sCountry,
		'user_phone' => $iPhone,
	);

	$oQuery = $oBdd->prepare(
		'UPDATE users
		SET user_mail = :user_mail,
		user_birthdate = :user_birthdate,
		user_civility = :user_civility,
		user_firstname = :user_firstname,
		user_lastname = :user_lastname,
		user_address = :user_address,
		user_city = :user_city,
		user_zipcode = :user_zipcode,
		user_country = :user_country,
		user_phone = :user_phone
		WHERE user_id = :user_id'
	);

	$bResult = $oQuery->execute($aTab);
}

/*********** FONCTION MODIFICATION D'UN UTILISATEUR *************/

/**
* Cette fonction modifie les informations de l'utilisateur ayant l'id passé en paramètre dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @param string $sPassword Nouveau Password
* @param string $sMail Nouveau Mail
* @param string $sBirthDate Nouvelle Date de Naissance
* @param string $sCivility Nouvelle Civilité
* @param string $sFirst_Name Nouveau Prénom
* @param string $sLast_Name Nouveau Nom
* @param string $sAddress Nouvelle Adresse
* @param string $sCity Nouvelle Ville
* @param string $iZipCode Nouveau Code Postal
* @param string $sCountry Nouveau Pays
* @param string $iPhone Nouveau Téléphone
* @param string $iNewsletter Inscription ou Non à la Newsletter (0 ou 1)
* @param string $sAvatar_Name Nouveau Nom Avatar
* @param string $sAvatar_Source Nouvelle Source Avatar
* @since 1.0
* @return void
*/
function Modify_User($iId_User,
$sPassword,
$sMail,
$sBirthDate,
$sCivility,
$sFirst_Name,
$sLast_Name,
$sAddress,
$sCity,
$iZipCode,
$sCountry,
$iPhone,
$iNewsletter,
$sAvatar_Name,
$sAvatar_Source)
{
	global $oBdd;

	$aTab = array(
		'user_id' => $iId_User,
		'user_password' => $sPassword,
		'user_mail' => $sMail,
		'user_birthdate' => $sBirthDate,
		'user_civility' => $sCivility,
		'user_firstname' => $sFirst_Name,
		'user_lastname' => $sLast_Name,
		'user_address' => $sAddress,
		'user_city' => $sCity,
		'user_zipcode' => $iZipCode,
		'user_country' => $sCountry,
		'user_phone' => $iPhone,
		'user_newsletter' => $iNewsletter
	);

	$oQuery = $oBdd->prepare(
		'UPDATE users
		SET user_password = :user_password,
		user_mail = :user_mail,
		user_birthdate = :user_birthdate,
		user_civility = :user_civility,
		user_firstname = :user_firstname,
		user_lastname = :user_lastname,
		user_address = :user_address,
		user_city = :user_city,
		user_zipcode = :user_zipcode,
		user_country = :user_country,
		user_phone = :user_phone,
		user_newsletter = :user_newsletter
		WHERE user_id = :user_id'
	);

	$bResult = $oQuery->execute($aTab);
	if ($bResult)
	{
		if(!empty($sAvatar_Name) && !empty($sAvatar_Source))
		{
			Replace_Avatar_User($iId_User, $sAvatar_Name, $sAvatar_Source);
		}
	}
}

/*********** FONCTION AJOUTER UN UTILISATEUR *************/

/**
* Cette fonction ajoute un utilisateur dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sPseudo_User Pseudo de l'utilisateur
* @param string $sPassword_User Password de l'utilisateur
* @param string $sMail_User Mail de l'utilisateur
* @param string $sBirth_Date Date de Naissance de l'utilisateur
* @since 1.0
* @return void
*/
function AjoutUser($sPseudo_User, $sPassword_User, $sMail_User, $sBirth_Date)
{
	global $oBdd;
	$aTab = array(
		'pseudo' => $sPseudo_User,
		'password' => $sPassword_User,
		'mail' => $sMail_User,
		'status' => 0,
		'birthdate' => $sBirth_Date
	);
	$oQuery = $oBdd->prepare(
		'INSERT INTO users (user_pseudo, user_password, user_mail, user_status, user_birthdate, user_creationtimestamp, user_lastlogintimestamp)
		VALUES (:pseudo, :password, :mail, :status, :birthdate, NOW(), NOW())'
	);
	$bResult = $oQuery->execute($aTab);
}

/**** FONCTION VERIFIER EXISTENCE UTILISATEUR ****/

/**
* Cette fonction vérifie l'existence d'un utilisateur dans la base de données via son pseudo et son password
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sPseudo_User Pseudo de l'utilisateur
* @param string $sPassword_User Password de l'utilisateur
* @since 1.0
* @return boolean true si l'utilisateur existe, false sinon
*/
function VerifExistUser($sPseudo_User, $sPassword_User)
{
	global $oBdd;
	$aUsers = array();
	$oQuery = $oBdd->prepare('SELECT user_pseudo, user_password FROM users');
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aUsers = $oQuery->fetchAll(PDO::FETCH_ASSOC);
		foreach($aUsers as $iIndex => $aTab)
		{
			if($aTab['user_pseudo'] == $sPseudo_User &&
			$aTab['user_password'] == cryptagePwd($sPassword_User))
			{ return true; }
		}
		return false;
	}
}

/**** FONCTION VERIFIER QUE L'UTILISATEUR A DEJA RENTRE OU NON SES COORDONNEES ****/

/**
* Cette fonction vérifie si l'utilisateur ayant le pseudo passé en paramètre a déjà rentré ses coordonnées ou non dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sPseudo_User Pseudo de l'utilisateur
* @since 1.0
* @return boolean true si l'utilisateur a déjà rentré ses coordonnées, false sinon
*/
function VerifContactUser($sPseudo_User)
{
	global $oBdd;
	$aUsers = array();
	$oQuery = $oBdd->prepare('SELECT * FROM users WHERE user_pseudo = ?');
	$bResult = $oQuery->execute([$sPseudo_User]);
	if ($bResult)
	{
		$aUsers = $oQuery->fetchAll(PDO::FETCH_ASSOC);

		foreach($aUsers as $iIndex => $aTab)
		{
			if(!empty($aTab['user_civility'])
			&& !empty($aTab['user_firstname'])
			&& !empty($aTab['user_lastname'])
			&& !empty($aTab['user_birthdate'])
			&& !empty($aTab['user_address'])
			&& !empty($aTab['user_city'])
			&& !empty($aTab['user_zipcode'])
			&& !empty($aTab['user_country'])
			&& !empty($aTab['user_phone'])
			&& !empty($aTab['user_mail'])
			)
			{ return true; }
			else{ return false; }
		}
	}
}

/**** FONCTION RECUPERER LES INFOS D'UN UTILISATEUR A PARTIR DU PSEUDO ****/

/**
* Cette fonction récupère les infos de l'utilisateur ayant le pseudo passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sPseudo_User Pseudo de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant les informations de l'utilisateur ayant le pseudo passé en paramètre
*/
function RecupInfosUser($sPseudo_User)
{
	global $oBdd;
	$aUsers = array();
	$oQuery = $oBdd->prepare('SELECT * FROM users  WHERE user_pseudo= ?');
	$bResult = $oQuery->execute([$sPseudo_User]);
	if ($bResult)
	{
		$aUsers = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aUsers;
}

/**** FONCTION RECUPERER LES INFOS D'UN UTILISATEUR A PARTIR DE L'ID ****/

/**
* Cette fonction récupère les infos de l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant les informations de l'utilisateur ayant l'id passé en paramètre
*/
function RecupInfosUser_Id($iId_User)
{
	global $oBdd;
	$aUsers = array();
	$oQuery = $oBdd->prepare('SELECT * FROM users  WHERE user_id= ?');
	$bResult = $oQuery->execute([$iId_User]);
	if ($bResult)
	{
		$aUsers = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aUsers;
}

/**** FONCTION POUR CHANGER LE STATUT DE L'UTILISATEUR AYANT L'ID PASSE EN PARAMETRE ****/

/**
* Cette fonction change le statut de l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @param int $iStatus_User Nouveau statut de l'utilisateur (0 => Utilisateur - 1 => Rédacteur - 5 => Admin)
* @since 1.0
* @return void
*/
function Change_User_Status($iId_User, $iStatus_User)
{
	global $oBdd;
	$aTab = array(
		'user_id' => $iId_User,
		'user_status' => $iStatus_User
	);
	$oQuery = $oBdd->prepare(
		'UPDATE users
		SET user_status = :user_status
		WHERE user_id = :user_id'
	);
	$bResult = $oQuery->execute($aTab);
}

/**** FONCTION QUI MET A JOUR LE "LASTLOGINTIMESTAMP" DANS LA BDD QUAND L'UTILISATEUR SE CONNECTE ****/
/**
* Cette fonction met à jour le "lastlogintimestamp" dans la base de données quand l'utilisateur ayant l'id passé en paramètre se connecte
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return void
*/
function Update_Lastlogintimestamp($iId_User)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'UPDATE users
		SET user_lastlogintimestamp = NOW()
		WHERE user_id = ?'
	);
	$bResult = $oQuery->execute([$iId_User]);
}

// FONCTION POUR RECUPERER TOUTES LES COMMANDES DE L'UTILISATEUR AYANT L'ID PASSE EN PARAMETRE
/**
* Cette fonction récupère toutes les commandes de l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant toutes les commandes (id + montant total + date de création) de l'utilisateur ayant l'id passé en paramètre
*/
function getAllOrders_User($iId_User)
{
	global $oBdd;
	$aOrders = array();
	$oQuery = $oBdd->prepare(
		'SELECT o.order_id, o.order_totalamount,
		DATE_FORMAT(o.order_creationtimestamp, \'%d/%m/%Y à %Hh%i\') AS order_date
		FROM orders AS o
		WHERE o.order_user_id = ?
		ORDER BY o.order_creationtimestamp DESC'
	);
	$bResult = $oQuery->execute([$iId_User]);
	if ($bResult)
	{
		$aOrders = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aOrders;
}

/******************************************************************************/
/*******************************  PICTURES  ***********************************/
/******************************************************************************/

/*********** FONCTION RECUPERER LA LISTE DE TOUTES LES IMAGES *************/

/**
* Cette fonction récupère la liste de toutes les images (pictures)
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de toutes les images (pictures)
*/
function Recup_List_Pictures()
{
	global $oBdd;
	$aPictures = array();
	$oQuery = $oBdd->prepare(
		'SELECT pic.picture_id,
		pic.picture_titre,
		pic.picture_source,
		pic.picture_id_post,
		p.post_titre,
		DATE_FORMAT(p.post_date, \'%d/%m/%Y à %Hh%i\') AS date,
		p.post_id_user,
		us.user_pseudo
		FROM pictures AS pic
		INNER JOIN posts AS p
		INNER JOIN users AS us
		WHERE pic.picture_id_post=p.post_id
		AND p.post_id_user=us.user_id'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aPictures = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aPictures;
}

/*********** FONCTION AJOUTER UNE IMAGE RELIEE A UN POST *************/

/**
* Cette fonction permet d'ajouter une image au post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du Post
* @param string $sPicture_Name Nom de l'image
* @param string $sPicture_Source Source de l'image
* @since 1.0
*/
function Add_Picture($iId_Post, $sPicture_Name, $sPicture_Source)
{
	global $oBdd;
	$aTab_Picture = array(
		'picture_title' => $sPicture_Name,
		'picture_source' => $sPicture_Source,
		'picture_id_post' => $iId_Post
	);
	$oQuery = $oBdd->prepare(
		'INSERT INTO pictures(
			picture_titre, picture_source, picture_id_post
		)
		VALUES(
			:picture_title, :picture_source, :picture_id_post
		)'
	);
	$bResult = $oQuery->execute($aTab_Picture);
}

/*********** FONCTION RECUPERER L'IMAGE RELIEE AU POST *************/

/**
* Cette fonction permet de récupérer l'image reliée au post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du Post
* @since 1.0
* @return array Retourne le tableau comprenant toutes les informations sur l'image reliée au post ayant l'id passé en paramètre : id, titre, source et l'id du post correspondant
*/
function Recup_Picture_Post($iId_Post)
{
	global $oBdd;
	$oQuery = $oBdd->prepare('SELECT * FROM pictures WHERE picture_id_post = ?');
	$bResult = $oQuery->execute([$iId_Post]);
	if($bResult)
	{
		$aPicture = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aPicture;
}

/*********** FONCTION VERIFIER SI UNE IMAGE EST DEJA RELIEE AU POST *************/

/**
* Cette fonction permet de vérifier si une image est déjà reliée au post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du Post
* @since 1.0
* @return boolean Retourne true si une image est déjà reliée au post ayant l'id passé en paramètre, false sinon
*/
function Check_Picture_Post($iId_Post)
{
	global $oBdd;
	$oQuery = $oBdd->prepare('SELECT * FROM pictures WHERE picture_id_post = ?');
	$bResult = $oQuery->execute([$iId_Post]);
	if($bResult)
	{
		$aPicture = $oQuery->fetch(PDO::FETCH_ASSOC);
		if(!$aPicture){ return false; }
		else{ return true; }
	}
}

/*********** FONCTION REMPLACER L'IMAGE RELIEE AU POST *************/

/**
* Cette fonction permet de remplacer l'image reliée au post ayant l'id passé en paramètre par une nouvelle image
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du Post
* @param string $sPicture_Name Nom de l'image
* @param string $sPicture_Source Source de l'image
* @since 1.0
* @return array Retourne le tableau comprenant les informations sur la nouvelle image reliée au post ayant l'id passé en paramètre
*/
function Replace_Picture_Post($iId_Post, $sPicture_Name, $sPicture_Source)
{
	if(Check_Picture_Post($iId_Post))
	{
		global $oBdd;
		$oQuery = $oBdd->prepare(
			'UPDATE pictures
			SET picture_titre ="'.htmlspecialchars($sPicture_Name).'",
			picture_source ="'.htmlspecialchars($sPicture_Source).'"
			WHERE picture_id_post = ?'
		);
		$bResult = $oQuery->execute([$iId_Post]);
		if($bResult)
		{
			$aPicture = $oQuery->fetch(PDO::FETCH_ASSOC);
		}
	}
	else
	{
		Add_Picture($iId_Post, $sPicture_Name, $sPicture_Source);
	}
	return $aPicture;
}

/******************************************************************************/
/********************************  AVATARS  ***********************************/
/******************************************************************************/

/*********** FONCTION RECUPERER LA LISTE DE TOUS LES AVATARS + LES PSEUDO USERS CORRESPONDANTS *************/

/**
* Cette fonction permet de récupérer la liste de tous les avatars et les pseudos des utilisateurs correspondants
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les avatars (id, titre, source) et les pseudos des utilisateurs correspondants
*/
function Recup_List_Avatars()
{
	global $oBdd;
	$aAvatars = array();
	$oQuery = $oBdd->prepare(
		'SELECT av.avatar_id,
		av.avatar_titre,
		av.avatar_source,
		us.user_pseudo
		FROM avatars AS av
		INNER JOIN users AS us
		WHERE us.user_avatar_id=av.avatar_id
		ORDER BY av.avatar_id'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aAvatars = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aAvatars;
}

/*********** FONCTION RECUPERER LA LISTE DES 10 PREMIERS AVATARS DE LA BDD *************/

/**
* Cette fonction permet de récupérer la liste des 10 premiers avatars présents dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste des 10 premiers avatars présents dans la base de données (id, titre, source)
*/
function Recup_Ten_Avatars_Bdd()
{
	global $oBdd;
	$aAvatars_Bdd = array();
	$oQuery = $oBdd->prepare(
		'SELECT av.avatar_id,
		av.avatar_titre,
		av.avatar_source
		FROM avatars AS av
		ORDER BY av.avatar_id
		LIMIT 0,10'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aAvatars_Bdd = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aAvatars_Bdd;
}

/*********** FONCTION RECUPERER L'AVATAR RELIE A L'ID DE L'UTILISATEUR *************/

/**
* Cette fonction permet de récupérer les informations de l'avatar correspondant à l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant les informations de l'avatar (id, titre, source) correspondant à l'utilisateur ayant l'id passé en paramètre
*/
function Recup_Avatar_User_Id($iId_User)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'SELECT * FROM avatars AS av
		INNER JOIN users AS us
		WHERE us.user_avatar_id = av.avatar_id
		AND us.user_id = ?'
	);
	$bResult = $oQuery->execute([$iId_User]);
	if($bResult)
	{
		$aAvatars = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aAvatars;
}

/*********** FONCTION RECUPERER L'AVATAR RELIE AU PSEUDO DE L'UTILISATEUR *************/

/**
* Cette fonction permet de récupérer les informations de l'avatar correspondant à l'utilisateur ayant le pseudo passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sPseudo_User Pseudo de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant les informations de l'avatar (id, titre, source) correspondant à l'utilisateur ayant le pseudo passé en paramètre
*/
function Recup_Avatar_User_Pseudo($sPseudo_User)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'SELECT * FROM avatars AS av
		INNER JOIN users AS us
		WHERE us.user_avatar_id = av.avatar_id
		AND us.user_pseudo = ?'
	);
	$bResult = $oQuery->execute([$sPseudo_User]);
	if($bResult)
	{
		$aAvatars = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aAvatars;
}

/*********** FONCTION VERIFIER SI UN AVATAR EST DEJA RELIE A UN UTILISATEUR *************/

/**
* Cette fonction permet de vérifier si l'avatar ayant le nom et la source passés en paramètre est déjà relié à un utilisateur
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sAvatar_Name Nom de l'avatar
* @param string $sAvatar_Source Source de l'avatar
* @since 1.0
* @return array|int Retourne le tableau comprenant l'id de l'avatar s'il est déjà présent dans la base de données (et donc déjà relié à un utilisateur), 0 sinon
*/
function Present_Avatar_Bdd($sAvatar_Name, $sAvatar_Source)
{
	global $oBdd;
	$aTab = array(
		'avatar_titre' => $sAvatar_Name,
		'avatar_source' => $sAvatar_Source
	);
	$oQuery = $oBdd->prepare(
		'SELECT avatar_id
		FROM avatars
		WHERE avatar_titre = :avatar_titre
		AND avatar_source = :avatar_source'
	);
	$bResult = $oQuery->execute($aTab);
	if($bResult)
	{
		$aAvatars = $oQuery->fetch(PDO::FETCH_ASSOC);
		if($aAvatars){ return $aAvatars; }
		else { return 0; }
	}
}

/*********** FONCTION RETOURNE L'ID AVATAR RELIE A L'ID USER PASSE EN PARAMETRE *************/

/**
* Cette fonction retourne l'id de l'avatar correspondant à l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant l'id de l'avatar correspondant à l'utilisateur ayant l'id passé en paramètre
*/
function Return_Avatar_Id($iId_User)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'SELECT av.avatar_id
		FROM avatars AS av
		INNER JOIN users AS us
		WHERE av.avatar_id = us.user_avatar_id
		AND us.user_id = ?'
	);
	$bResult = $oQuery->execute([$iId_User]);
	if($bResult)
	{
		$aAvatars = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aAvatars;
}

/*********** FONCTION RETOURNE LES INFOS DE L'AVATAR AYANT L'ID PASSE EN PARAMETRE *************/

/**
* Cette fonction retourne les informations de l'avatar (titre, source) ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Avatar Id de l'avatar
* @since 1.0
* @return array Retourne le tableau comprenant les informations de l'avatar (titre, source) ayant l'id passé en paramètre
*/
function Return_Avatar_Infos($iId_Avatar)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'SELECT av.avatar_titre,
		av.avatar_source
		FROM avatars AS av
		WHERE av.avatar_id = ?'
	);
	$bResult = $oQuery->execute([$iId_Avatar]);
	if($bResult)
	{
		$aAvatars = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aAvatars;
}

/*********** FONCTION REMPLACER L'AVATAR RELIE A L'UTILISATEUR *************/

/**
* Cette fonction permet de remplacer l'avatar correspondant à l'utilisateur ayant l'id passé en paramètre par un nouvel avatar
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @param string $sAvatar_Name Nom de l'avatar
* @param string $sAvatar_Source Source de l'avatar
* @since 1.0
* @return array Retourne le tableau comprenant les informations sur le nouvel avatar correspondant à l'utilisateur ayant l'id passé en paramètre
*/
function Replace_Avatar_User($iId_User, $sAvatar_Name, $sAvatar_Source)
{
	$iId_Avatar = Present_Avatar_Bdd($sAvatar_Name, $sAvatar_Source)['avatar_id'];
	if($iId_Avatar != 0)
	{
		$aTab = array(
			'id_avatar' => $iId_Avatar,
			'id_user' => $iId_User
		);
		global $oBdd;
		$oQuery = $oBdd->prepare(
			'UPDATE users
			SET user_avatar_id = :id_avatar
			WHERE user_id = :id_user'
		);
		$bResult = $oQuery->execute($aTab);
		if($bResult)
		{
			$aAvatars = $oQuery->fetch(PDO::FETCH_ASSOC);
		}
	}
	else
	{
		Add_Avatar($iId_User, $sAvatar_Name, $sAvatar_Source);
	}
	return $aAvatars;
}

/*********** FONCTION AJOUTER UN AVATAR RELIE A UN UTILISATEUR *************/

/**
* Cette fonction permet d'ajouter un avatar dans la base de données et de le relier à l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @param string $sAvatar_Name Nom de l'avatar
* @param string $sAvatar_Source Source de l'avatar
* @since 1.0
* @return boolean Retourne true si la liaison entre l'avatar et l'utilisateur s'est bien faite, false sinon
*/
function Add_Avatar($iId_User, $sAvatar_Name, $sAvatar_Source)
{
	global $oBdd;
	$aTab_Avatar = array(
		'avatar_titre' => $sAvatar_Name,
		'avatar_source' => $sAvatar_Source
	);
	$oQuery = $oBdd->prepare(
		'INSERT INTO avatars (avatar_titre, avatar_source)
		VALUES (:avatar_titre, :avatar_source)'
	);
	$bResult = $oQuery->execute($aTab_Avatar);
	$iId_Avatar = $oBdd->lastInsertId();
	$oQuery = $oBdd->prepare(
		'UPDATE users
		SET user_avatar_id ="'.htmlspecialchars($iId_Avatar).'"
		WHERE user_id = ?'
	);
	$bResult = $oQuery->execute([$iId_User]);

	if($bResult){ return true; }
	else { return false; }
}

/******************************************************************************/
/********************************  MATERIELS  ***********************************/
/******************************************************************************/

/*********** FONCTION RECUPERER LA LISTE DE TOUS LES MATERIELS *************/

/**
* Cette fonction permet de récupérer la liste de tous les matériels
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les matériels (id, titre, source)
*/
function Recup_List_Materials()
{
	global $oBdd;
	$aMaterials = array();
	$oQuery = $oBdd->prepare(
		'SELECT mat.material_id,
		mat.material_titre,
		mat.material_source
		FROM materials AS mat'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aMaterials = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aMaterials;
}

/*********** FONCTION AFFICHAGE DU MATERIEL LIE A L'ID PASSE EN PARAMETRE *************/

/**
* Cette fonction permet de récupérer les informations du matériel ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Material Id du matériel
* @since 1.0
* @return array Retourne le tableau comprenant les informations (id, titre, source) du matériel ayant l'id passé en paramètre
*/
function Recup_Material_Id($iId_Material)
{
	global $oBdd;
	$aMaterials = array();
	$oQuery = $oBdd->prepare(
		'SELECT *
		FROM materials
		WHERE material_id = ?'
	);
	$bResult = $oQuery->execute([$iId_Material]);
	if ($bResult)
	{
		$aMaterials = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aMaterials;
}

/******************************************************************************/
/********************************  INGREDIENTS  ***********************************/
/******************************************************************************/

/*********** FONCTION RECUPERER LA LISTE DE TOUS LES INGREDIENTS *************/

/**
* Cette fonction permet de récupérer la liste de tous les ingrédients
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les ingrédients (id, description, id du post correspondant)
*/
function Recup_List_Ingredients()
{
	global $oBdd;
	$aIngredients = array();
	$oQuery = $oBdd->prepare(
		'SELECT ing.ingredient_id,
		ing.ingredient_description,
		ing.ingredient_id_post
		FROM ingredients AS ing'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aIngredients = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aIngredients;
}

/*********** FONCTION AJOUTER UNE LISTE D'INGREDIENTS *************/

/**
* Cette fonction permet d'ajouter une liste d'ingrédients au post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @param string $sIngredients Liste des ingrédients
* @since 1.0
* @return void
*/
function Add_Ingredients($iId_Post, $sIngredients)
{
	$aTab_Ingredients = array(
		'ingredient_description' => $sIngredients,
		'ingredient_id_post' => $iId_Post
	);
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'INSERT INTO ingredients(
			ingredient_description, ingredient_id_post
		)
		VALUES (
			:ingredient_description, :ingredient_id_post
		)'
	);
	$bResult = $oQuery->execute($aTab_Ingredients);
}

/*********** FONCTION VERIFIER SI UNE LISTE D'INGREDIENTS EST DEJA RELIEE AU POST *************/

/**
* Cette fonction permet de vérifier si une liste d'ingrédients est déjà reliée au post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @since 1.0
* @return boolean Retourne true si une liste d'ingrédients est déjà reliée au post ayant l'id passé en paramètre, false sinon
*/
function Check_Ingredients_Post($iId_Post)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'SELECT * FROM ingredients
		WHERE ingredient_id_post = ?'
	);
	$bResult = $oQuery->execute([$iId_Post]);
	if($bResult)
	{
		$aIngredients = $oQuery->fetch(PDO::FETCH_ASSOC);
		if(!$aIngredients){ return false; }
		else{ return true; }
	}
}

/*********** FONCTION REMPLACER LES INGREDIENTS ('ingredient_description') CORRESPONDANT A L'ID POST PASSE EN PARAMETRE *************/

/**
* Cette fonction permet de remplacer la liste d'ingrédients correspondant au post ayant l'id passé en paramètre par une nouvelle liste d'ingrédients
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @param string $sIngredient_Description Nouvelle liste des ingrédients
* @since 1.0
* @return void
*/
function Replace_Ingredients($iId_Post , $sIngredient_Description)
{
	if(Check_Ingredients_Post($iId_Post))
	{
		global $oBdd;
		$oQuery = $oBdd->prepare(
			'UPDATE ingredients
			SET ingredient_description ="'.htmlspecialchars($sIngredient_Description).'"
			WHERE ingredient_id_post = ?'
		);
		$bResult = $oQuery->execute([$iId_Post]);
	}
	else
	{
		Add_Ingredients($iId_Post, $sIngredient_Description);
	}
}

// FONCTION RETOURNE L'ID DE L'INGREDIENT CORRESPONDANT A LA DESCRIPTION PASSE EN PARAMETRE

/**
* Cette fonction retourne l'id de l'ingrédient correspondant à la description passée en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sIngredient_Description Liste des ingrédients
* @since 1.0
* @return int Retourne l'id de l'ingrédient correspondant à la description passée en paramètre
*/
function Take_Id_Ingredient_Description($sIngredient_Description)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'SELECT ingredient_id
		FROM ingredients
		WHERE ingredient_description = ?'
	);
	$bResult = $oQuery->execute([$sIngredient_Description]);
	if($bResult)
	{
		$iId_Ingredient = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $iId_Ingredient['ingredient_id'];
}

/******************************************************************************/
/********************************  GIFTSCARDS  *********************************/
/******************************************************************************/

/*********** FONCTION RECUPERER LA LISTE DE TOUTES LES CARTES CADEAUX *************/

/**
* Cette fonction permet de récupérer la liste de toutes les cartes cadeaux
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de toutes les cartes cadeaux (id, nom, description, source de la photo, prix de vente unitaire)
*/
function Recup_List_Giftscards()
{
	global $oBdd;
	$aGiftscards = array();
	$oQuery = $oBdd->prepare(
		'SELECT gi.giftscard_id,
		gi.giftscard_name,
		gi.giftscard_description,
		gi.giftscard_saleprice,
		gi.giftscard_photo
		FROM giftscards AS gi'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aGiftscards = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aGiftscards;
}

/**** FONCTION RECUPERER LES INFOS D'UNE GIFT CARD A PARTIR DE L'ID ****/

/**
* Cette fonction permet de récupérer les informations de la carte cadeau ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Gift_Card Id de la carte cadeau
* @since 1.0
* @return array Retourne le tableau comprenant les informations de la carte cadeau (id, nom, description, source de la photo, prix de vente unitaire) ayant l'id passé en paramètre
*/
function Recup_Infos_Gift_Card($iId_Gift_Card)
{
	global $oBdd;
	$aGiftscards = array();
	$oQuery = $oBdd->prepare('SELECT * FROM giftscards WHERE giftscard_id= ?');
	$bResult = $oQuery->execute([$iId_Gift_Card]);
	if ($bResult)
	{
		$aGiftscards = $oQuery->fetch(PDO::FETCH_ASSOC);
		return $aGiftscards;
	}
}

/******************************************************************************/
/********************************  COMMENTAIRES  *********************************/
/******************************************************************************/

/*********** FONCTION RECUPERER LA LISTE DE TOUS LES COMMENTAIRES *************/

/**
* Cette fonction permet de récupérer la liste de tous les commentaires
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les commentaires (id, description, date de création, id du post correspondant, id de l'utilisateur correspondant)
*/
function Recup_List_Comments()
{
	global $oBdd;
	$aComments = array();
	$oQuery = $oBdd->prepare(
		'SELECT co.comment_id,
		co.comment_description,
		DATE_FORMAT(co.comment_date, \'%d/%m/%Y à %Hh%i\') AS date,
		co.comment_id_post,
		p.post_titre,
		us.user_id,
		us.user_pseudo
		FROM comments AS co
		INNER JOIN posts AS p
		INNER JOIN users AS us
		WHERE co.comment_id_post=p.post_id
		AND co.comment_id_user=us.user_id
		ORDER BY co.comment_date DESC'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aComments = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aComments;
}

/*********** FONCTION RECUPERER LA LISTE DE TOUS LES COMMENTAIRES CORRESPONDANTS A L'ID DU POST PASSE EN PARAMETRE *************/

/**
* Cette fonction permet de récupérer la liste de tous les commentaires correspondant au post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les commentaires (id, description, date de création, id du post correspondant, titre du post correspondant, id de l'utilisateur correspondant, pseudo de l'utilisateur correspondant) correspondant au post ayant l'id passé en paramètre
*/
function Recup_List_Comments_Post($iId_Post)
{
	global $oBdd;
	$aComments = array();
	$oQuery = $oBdd->prepare(
		'SELECT co.comment_id,
		co.comment_description,
		DATE_FORMAT(co.comment_date, \'%d/%m/%Y à %Hh%i\') AS date,
		co.comment_id_post,
		p.post_titre,
		us.user_id,
		us.user_pseudo
		FROM comments AS co
		INNER JOIN posts AS p
		INNER JOIN users AS us
		WHERE co.comment_id_post=p.post_id
		AND co.comment_id_user=us.user_id
		AND p.post_id = ?'
	);
	$bResult = $oQuery->execute([$iId_Post]);
	if ($bResult)
	{
		$aComments = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aComments;
}

/*********** FONCTION RECUPERER LA LISTE DE TOUS LES COMMENTAIRES CORRESPONDANTS A L'ID DU USER PASSE EN PARAMETRE *************/

/**
* Cette fonction permet de récupérer la liste de tous les commentaires correspondant à l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les commentaires (id, description, date de création, id du post correspondant, titre du post correspondant, id de l'utilisateur correspondant, pseudo de l'utilisateur correspondant) correspondant à l'utilisateur ayant l'id passé en paramètre
*/
function Recup_List_Comments_User($iId_User)
{
	global $oBdd;
	$aComments = array();
	$oQuery = $oBdd->prepare(
		'SELECT co.comment_id,
		co.comment_description,
		DATE_FORMAT(co.comment_date, \'%d/%m/%Y à %Hh%i\') AS date,
		co.comment_id_post,
		p.post_titre,
		us.user_id,
		us.user_pseudo
		FROM comments AS co
		INNER JOIN posts AS p
		INNER JOIN users AS us
		WHERE co.comment_id_post=p.post_id
		AND co.comment_id_user=us.user_id
		AND co.comment_id_user = ?'
	);
	$bResult = $oQuery->execute([$iId_User]);
	if ($bResult)
	{
		$aComments = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aComments;
}

/*********** FONCTION AFFICHAGE DES COMMENTAIRES *************/

/**
* Cette fonction permet de récupérer la liste des commentaires correspondant au post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les commentaires (description, pseudo de l'utilisateur correspondant, date de création) correspondant au post ayant l'id passé en paramètre
*/
function AffichageComments($iId_Post)
{
	global $oBdd;
	$aComments = array();
	$oQuery = $oBdd->prepare(
		'SELECT co.comment_description, users.user_pseudo,
		DATE_FORMAT(co.comment_date, \'%d/%m/%Y à %Hh%i\') AS date
		FROM comments AS co
		INNER JOIN users
		ON co.comment_id_user = users.user_id
		WHERE comment_id_post = ?
		ORDER BY co.comment_id ASC'
	);
	$bResult = $oQuery->execute([$iId_Post]);
	if ($bResult)
	{
		$aComments = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aComments;
}

/*********** FONCTION AJOUTER UN COMMENTAIRE *************/

/**
* Cette fonction permet d'ajouter un commentaire dans la base de données et de le relier au post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @param string $sPseudo_Id Pseudo de l'utilisateur
* @param string $sDescriptionComment Contenu du commentaire
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les posts (id, titre, description, date de création, catégorie), le pseudo de l'utilisateur correspondant, la source de l'image correspondante et la liste des commentaires correspondants
*/
function AjoutComment($iId_Post, $sPseudo_Id, $sDescriptionComment)
{
	global $oBdd;
	$aTab = array(
		'description' => $sDescriptionComment,
		'id_user' => $sPseudo_Id,
		'id_post' => $iId_Post
	);
	$oQuery = $oBdd->prepare(
		'INSERT INTO comments (comment_description, comment_date, comment_id_post, comment_id_user)
		VALUES (:description, NOW(), :id_post, :id_user)'
	);
	$bResult = $oQuery->execute($aTab);
	if ($bResult)
	{
		return AffichagePosts();
	}
}

/******************************************************************************/
/**********************************  POSTS  ***********************************/
/******************************************************************************/

/*********** FONCTION RECUPERER LA LISTE DE TOUS LES POSTS + PSEUDO UTILISATEUR *************/

/**
* Cette fonction permet de récupérer la liste de tous les posts et les informations des utilisateurs correspondant
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les posts (id, titre, description, date de création, catégorie) et les informations des utilisateurs correspondant (id, pseudo)
*/
function Recup_List_Posts()
{
	global $oBdd;
	$aPosts = array();
	$oQuery = $oBdd->prepare(
		'SELECT p.post_id,
		p.post_titre,
		p.post_description,
		DATE_FORMAT(p.post_date, \'%d/%m/%Y à %Hh%i\') AS date,
		p.post_categorie,
		p.post_id_user,
		us.user_pseudo FROM posts AS p
		INNER JOIN users AS us
		WHERE p.post_id_user=us.user_id'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aPosts = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aPosts;
}

/*********** FONCTION RECUPERER LE POST CORRESPONDANT A L'ID PASSE EN PARAMETRE (+ PSEUDO UTILISATEUR) *************/

/**
* Cette fonction permet de récupérer les informations du post ayant l'id passé en paramètre et les informations de l'utilisateur correspondant
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @since 1.0
* @return array Retourne le tableau comprenant les informations du post (id, titre, description, date de création, catégorie) ayant l'id passé en paramètre et les informations de l'utilisateur correspondant (id, pseudo)
*/
function Recup_Post_Id($iId_Post)
{
	global $oBdd;
	$aPosts = array();
	$oQuery = $oBdd->prepare(
		'SELECT p.post_id,
		p.post_titre,
		p.post_description,
		DATE_FORMAT(p.post_date, \'%d/%m/%Y à %Hh%i\') AS date,
		p.post_categorie,
		p.post_id_user,
		us.user_pseudo FROM posts AS p
		INNER JOIN users AS us
		WHERE p.post_id_user=us.user_id
		AND p.post_id = ?'
	);
	$bResult = $oQuery->execute([$iId_Post]);
	if ($bResult)
	{
		$aPosts = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aPosts;
}

/********* RECUPERER L'ID DU DERNIER POST ************/

/**
* Cette fonction permet de récupérer l'id du dernier post présent dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant l'id du dernier post présent dans la base de données
*/
function recup_last_post_id()
{
	global $oBdd;
	$aPosts = array();
	$oQuery = $oBdd->prepare(
		'SELECT MAX(post_id) AS id
		FROM posts'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aPosts = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aPosts;
}

/*********** FONCTION AFFICHAGE DES POSTS (COCKTAILS) *************/

/**
* Cette fonction permet de récupérer la liste de tous les posts (cocktails), le pseudo de l'utilisateur correspondant, la source de l'image correspondante et la liste des commentaires correspondants
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les posts (id, titre, description, date de création, catégorie), le pseudo de l'utilisateur correspondant, la source de l'image correspondante et la liste des commentaires correspondants
*/
function AffichagePosts()
{
	global $oBdd;
	$aPosts = array();
	$oQuery = $oBdd->prepare(
		'SELECT p.post_id, p.post_titre, p.post_description,
		DATE_FORMAT(p.post_date, \'%d/%m/%Y à %Hh%i\') AS date,
		p.post_categorie, us.user_pseudo, pic.picture_source
		FROM posts AS p
		LEFT JOIN pictures AS pic ON pic.picture_id_post = p.post_id
		INNER JOIN users AS us ON us.user_id = p.post_id_user
		GROUP BY p.post_id
		ORDER BY p.post_date DESC'
	);
	/*
	LEFT JOIN est un type de jointure entre 2 tables.
	Cela permet de lister tous les résultats de la table de gauche (left = gauche)
	même s’il n’y a pas de correspondance dans la deuxième table.
	*/
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aPosts = $oQuery->fetchAll(PDO::FETCH_ASSOC);
		foreach($aPosts as $iIndex => $aTab)
		{
			// Pour chaque "post", on ajoute dans le tableau les commentaires correspondants
			$aPosts[$iIndex]['comments'] = AffichageComments($aTab['post_id']);
		}
	}
	return $aPosts;
}

/*********** FONCTION AFFICHAGE DU POST LIE A L'ID PASSE EN PARAMETRE *************/

/**
* Cette fonction permet de récupérer les informations du post ayant l'id passé en paramètre, le pseudo de l'utilisateur correspondant, la source de l'image correspondante, les informations des matériels correspondants, la liste des ingrédients correspondant et la liste des commentaires correspondants
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les posts (id, titre, description, date de création, catégorie), le pseudo de l'utilisateur correspondant, la source de l'image correspondante, les informations des matériels correspondants, la liste des ingrédients correspondant et la liste des commentaires correspondants
*/
function AffichagePost_Id($iId_Post)
{
	global $oBdd;
	$aPosts = array();
	$oQuery = $oBdd->prepare(
		'SELECT p.post_id, p.post_titre, p.post_description,
		DATE_FORMAT(p.post_date, \'%d/%m/%Y à %Hh%i\') AS date,
		p.post_categorie,
		p.post_material_id1, p.post_material_id2, p.post_material_id3,
		us.user_pseudo, pic.picture_source,
		mat.material_titre, mat.material_source,
		ing.ingredient_description
		FROM posts AS p
		LEFT JOIN materials AS mat ON post_material_id1 = material_id
		LEFT JOIN pictures AS pic ON pic.picture_id_post = p.post_id
		INNER JOIN users AS us ON us.user_id = p.post_id_user
		LEFT JOIN ingredients AS ing ON ing.ingredient_id_post = p.post_id
		WHERE p.post_id = ?
		GROUP BY p.post_id
		ORDER BY p.post_date DESC'
	);
	$bResult = $oQuery->execute([$iId_Post]);
	if ($bResult)
	{
		$aPosts = $oQuery->fetch(PDO::FETCH_ASSOC);
		$aPosts['comments'] = AffichageComments($iId_Post);
	}
	return $aPosts;
}

/*********** FONCTION AJOUTER UN POST *************/

/**
* Cette fonction permet d'ajouter un post dans la base de données et de le relier ou non à une image et/ou à une liste d'ingrédients
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sTitle Titre du post
* @param string $sDescription Description du post
* @param string $sCategory Catégorie du post
* @param int $iId_User Id de l'utilisateur qui a créé ce post
* @param string $sPicture_Name Nom de l'image reliée au post
* @param string $sPicture_Source Source de l'image reliée au post
* @param array $aMaterial Liste des matériels du post
* @param string $sIngredients Liste des ingrédients du post
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les posts (id, titre, description, date de création, catégorie), le pseudo de l'utilisateur correspondant, la source de l'image correspondante et la liste des commentaires correspondants
*/
function Add_Post($sTitle, $sDescription, $sCategory, $iId_User, $sPicture_Name, $sPicture_Source, $aMaterial, $sIngredients)
{
	global $oBdd;

	/*************** MATERIEL ***************/

	$aMaterial_Sql = array();
	foreach($aMaterial as $sIndex => $sValue)
	{
		if($sValue == 'on')
		{
			switch($sIndex)
			{
				case 'shaker' : $aMaterial_Sql[] = 1;
				break;
				case 'verreamelange' : $aMaterial_Sql[] = 2;
				break;
				case 'cuillereamelange' : $aMaterial_Sql[] = 3;
				break;
				case 'pilon' : $aMaterial_Sql[] = 4;
				break;
				case 'blender' : $aMaterial_Sql[] = 5;
				break;
				default: break;
			}
		}
		else
		{
			$aMaterial_Sql[] = 0;
		}
	}
	rsort($aMaterial_Sql); // Trie un tableau en ordre inverse donc les '0' se retrouveront à la fin !

	$aTab_Post = array(
		'titre' => $sTitle,
		'description' => $sDescription,
		'categorie' => $sCategory,
		'id_user' => $iId_User,
		'id_material1' => $aMaterial_Sql[0],
		'id_material2' => $aMaterial_Sql[1],
		'id_material3' => $aMaterial_Sql[2],
		'id_material4' => $aMaterial_Sql[3],
		'id_material5' => $aMaterial_Sql[4]
	);
	$oQuery = $oBdd->prepare(
		'INSERT INTO posts(
			post_titre, post_description, post_categorie, post_date, post_id_user,
			post_material_id1, post_material_id2, post_material_id3, post_material_id4, post_material_id5
		)
		VALUES(
			:titre, :description, :categorie, NOW(), :id_user,
			:id_material1, :id_material2, :id_material3, :id_material4, :id_material5
		)'
	);
	$bResult = $oQuery->execute($aTab_Post);
	$iId_Post = $oBdd->lastInsertId();
	if($bResult)
	{
		if(!empty($sIngredients) && !ctype_space($sIngredients)) // ctype_space ==> Vérifie qu'une chaîne n'est faite que de caractères blancs
		{
			Add_Ingredients($iId_Post, $sIngredients); // Ajout des ingrédients dans la BDD
		}
		if(!empty($sPicture_Name) && !empty($sPicture_Source))
		{
			Add_Picture($iId_Post, $sPicture_Name, $sPicture_Source); // Ajout de l'image dans la BDD
		}
		return AffichagePosts();
	}
}

/*********** FONCTION SUPPRESSION D'UN POST *************/

/**
* Cette fonction permet de supprimer le post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les posts (id, titre, description, date de création, catégorie), le pseudo de l'utilisateur correspondant, la source de l'image correspondante et la liste des commentaires correspondants
*/
function Supprimer($iId_Post)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'DELETE FROM posts
		WHERE post_id = ?'
	);
	$bResult = $oQuery->execute([$iId_Post]);
	if ($bResult)
	{
		return AffichagePosts();
	}
}

/*********** FONCTION AFFICHAGE DU POST A MODIFIER *************/

/**
* Cette fonction retourne les informations du post ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @since 1.0
* @return array Retourne le tableau comprenant les informations du post ayant l'id passé en paramètre (id, titre, description, date de création, catégorie, id user, id materiel 1, id materiel 2, id materiel 3, id materiel 4, id materiel 5, note), les informations des ingrédients correspondants (id, description, id du post correspondant) et les informations sur l'image correspondante (id, titre, source, id du post correspondant)
*/
function AffModifier($iId_Post)
{
	global $oBdd;
	$aPosts = array();
	$oQuery = $oBdd->prepare(
		'SELECT * FROM posts AS po
		INNER JOIN ingredients AS ing
		WHERE po.post_id = ing.ingredient_id_post
		AND post_id = ?'
	);
	$bResult = $oQuery->execute([$iId_Post]);
	if ($bResult)
	{
		$aPosts = $oQuery->fetchAll(PDO::FETCH_ASSOC);
		$aPosts['picture'] = Recup_Picture_Post($iId_Post);
	}
	return $aPosts;
}

/*********** FONCTION MODIFICATION D'UN POST *************/

/**
* Cette fonction permet de modifier les informations du post ayant l'id passé en paramètre et les liaisons qu'il a ou non avec une image et/ou avec une liste d'ingrédients
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @param string $sTitle Nouveau titre du post
* @param string $sDescription Nouvelle description du post
* @param string $sCategory Nouvelle catégorie du post
* @param int $iId_User Id de l'utilisateur qui a modifié ce post
* @param string $sPicture_Name Nouveau nom de l'image reliée au post
* @param string $sPicture_Source Nouvelle source de l'image reliée au post
* @param array $aMaterial Nouvelle liste des matériels du post
* @param string $sIngredients Nouvelle liste des ingrédients du post
* @since 1.0
* @return array Retourne le tableau comprenant la liste de tous les posts (id, titre, description, date de création, catégorie), le pseudo de l'utilisateur correspondant, la source de l'image correspondante et la liste des commentaires correspondants
*/
function Modifier($iId_Post, $sTitle, $sDescription, $sCategory, $iId_User, $sPicture_Name, $sPicture_Source, $aMaterial, $sIngredients)
{
	global $oBdd;

	/*************** MATERIEL ***************/

	$aMaterial_Sql = array();

	foreach($aMaterial as $sIndex => $sValue)
	{
		if($sValue == 'on')
		{
			switch($sIndex)
			{
				case 'shaker' : $aMaterial_Sql[] = 1;
				break;
				case 'verreamelange' : $aMaterial_Sql[] = 2;
				break;
				case 'cuillereamelange' : $aMaterial_Sql[] = 3;
				break;
				case 'pilon' : $aMaterial_Sql[] = 4;
				break;
				case 'blender' : $aMaterial_Sql[] = 5;
				break;
				default: break;
			}
		}
		else
		{
			$aMaterial_Sql[] = 0;
		}
	}
	rsort($aMaterial_Sql); // Trie un tableau en ordre inverse donc les '0' se retrouveront à la fin !

	if(!empty($sIngredients) && !ctype_space($sIngredients))
	{
		Replace_Ingredients($iId_Post, $sIngredients); // Remplacement des ingrédients dans la BDD
	}

	$aTab = array(
		'id_user' => $iId_User,
		'id_post' => $iId_Post,
		'id_material1' => $aMaterial_Sql[0],
		'id_material2' => $aMaterial_Sql[1],
		'id_material3' => $aMaterial_Sql[2],
		'id_material4' => $aMaterial_Sql[3],
		'id_material5' => $aMaterial_Sql[4],
	);
	$oQuery = $oBdd->prepare(
		'UPDATE posts
		SET post_titre ="'.htmlspecialchars($sTitle).'",
		post_description ="'.htmlspecialchars($sDescription).'",
		post_date = NOW(),
		post_categorie ="'.htmlspecialchars($sCategory).'",
		post_id_user = :id_user,
		post_material_id1 = :id_material1,
		post_material_id2 = :id_material2,
		post_material_id3 = :id_material3,
		post_material_id4 = :id_material4,
		post_material_id5 = :id_material5
		WHERE post_id = :id_post'
	);
	$bResult = $oQuery->execute($aTab);
	if ($bResult)
	{
		if(!empty($sPicture_Name) && !empty($sPicture_Source))
		{
			Replace_Picture_Post($iId_Post, $sPicture_Name, $sPicture_Source); // Remplacement des images dans la BDD
		}
		return AffichagePosts();
	}
}

/**** FONCTION RECUPERER L'ID DU COCKTAIL A PARTIR DU NAME ****/

/**
* Cette fonction retourne l'id du post ayant le titre passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sCocktail_Name Titre du post
* @since 1.0
* @return array Retourne le tableau comprenant l'id du post ayant le titre passé en paramètre
*/
function Recup_Id_Cocktail($sCocktail_Name)
{
	global $oBdd;

	$aPosts = array();

	$oQuery = $oBdd->prepare('SELECT post_id FROM posts WHERE post_titre= ?');

	$bResult = $oQuery->execute([$sCocktail_Name]);

	if ($bResult)
	{
		$aPosts = $oQuery->fetch(PDO::FETCH_ASSOC);

		return $aPosts;
	}
}

/**** FONCTION VERIFIER EXISTENCE NOM COCKTAIL DANS LA BASE DE DONNEES ****/

/**
* Cette fonction vérifie l'existence du titre du post (nom du cocktail) passé en paramètre dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sCocktail_Name Titre du post
* @since 1.0
* @return boolean Retourne true si le titre du post (nom du cocktail) passé en paramètre est présent dans la base de données, false sinon
*/
function isCocktailInBdd($sCocktail_Name)
{
	$sCocktail_Name = htmlspecialchars($sCocktail_Name);
	global $oBdd;
	$aCocktails = array();
	$oQuery = $oBdd->prepare('SELECT post_titre FROM posts');
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aCocktails = $oQuery->fetchAll(PDO::FETCH_ASSOC);
		foreach($aCocktails as $iIndex => $aTab)
		{
			if($aTab['post_titre'] == $sCocktail_Name)
			{ return true; }
		}
		return false;
	}
}

/********************** ORDER + ORDERLINE *********************/

/**
* Cette fonction permet d'ajouter les commandes (orders) dans la base de données ainsi que les lignes de commandes (orderlines) correspondantes
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $idUser Id de l'utilisateur
* @param array $aDataBasket Contenu du Panier
* @since 1.0
* @return array Retourne le tableau comprenant les informations de la dernière ligne de commande ajoutée : quantité commandée, id de la carte cadeau, id de la commande, prix unitaire
*/
function validCommande($idUser, $aDataBasket)
{
	$aOrder = [
		'user_id' => $idUser,
		'total' 	=> $aDataBasket['total'],
	];

	$iIdOrder = createOrder($aOrder);

	foreach($aDataBasket as $Id => $aLine){

		if($Id != 'total' && $Id != 'quantity_total'){

			$aOrderLine = [
				'orderline_quantityordered' => $aLine['giftscard_quantity'],
				'orderline_giftcard_id' => $aLine['giftscard_id'],
				'orderline_order_id' => $iIdOrder,
				'orderline_priceeach' => $aLine['giftscard_saleprice']
			];
			createOrderLine($aOrderLine);
		}
	}
}

/******************************************************************************/
/**********************************  ORDERS  **********************************/
/******************************************************************************/

// FONCTION POUR RECUPERER TOUTES LES COMMANDES

/**
* Cette fonction permet de récupérer la liste de toutes les commandes (orders)
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant la liste de toutes les commandes (orders) : id, montant total, date de création et les informations sur l'utilisateur correspondant : id, pseudo, prénom, nom
*/
function getAllOrders()
{
	global $oBdd;
	$aOrders = array();
	$oQuery = $oBdd->prepare(
		'SELECT o.order_id, o.order_totalamount, DATE_FORMAT(o.order_creationtimestamp, \'%d/%m/%Y à %Hh%i\') AS order_date,
		o.order_user_id,
		us.user_id, us.user_pseudo, us.user_firstname, us.user_lastname
		FROM orders AS o
		INNER JOIN users AS us
		WHERE o.order_user_id = us.user_id
		ORDER BY o.order_creationtimestamp DESC'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aOrders = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aOrders;
}

// FONCTION POUR RECUPERER TOUTES LES INFOS D'UNE COMMANDE LIEE A L'ID PASSE EN PARAMETRE

/**
* Cette fonction permet de récupérer les informations de la commande ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Order Id de la commande
* @since 1.0
* @return array Retourne le tableau comprenant les informations de la commande ayant l'id passé en paramètre : id, id de l'utilisateur correspondant, montant total, date de création, date de livraison
*/
function get_Order_Id($iId_Order)
{
	global $oBdd;
	$aOrders = array();
	$oQuery = $oBdd->prepare(
		'SELECT *
		FROM orders
		WHERE order_id = ?'
	);
	$bResult = $oQuery->execute([$iId_Order]);
	if ($bResult)
	{
		$aOrders = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aOrders;
}

// FONCTION POUR INSERER UNE COMMANDE

/**
* Cette fonction permet d'ajouter une commande (order) dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param array $aOrder Informations de la commande
* @since 1.0
* @return int Retourne l'id de la commande qui vient d'être ajoutée dans la base de données
*/
function createOrder($aOrder)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'INSERT INTO `orders`
		(order_user_id, order_totalamount, order_creationtimestamp, order_completetimestamp)
		VALUES (:user_id, :total, NOW(), NOW())'
	);
	$bResult = $oQuery->execute($aOrder);
	return $oBdd->lastInsertId();
}

/********* FONCTION POUR RECUPERER L'ID DE LA DERNIERE COMMANDE ************/

/**
* Cette fonction permet de récupérer l'id de la dernière commande présente dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @since 1.0
* @return array Retourne le tableau comprenant l'id de la dernière commande présente dans la base de données
*/
function recup_last_order_id()
{
	global $oBdd;
	$aPosts = array();
	$oQuery = $oBdd->prepare(
		'SELECT MAX(order_id) AS id
		FROM orders'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aPosts = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aPosts;
}

/********* FONCTION POUR RECUPERER L'ID DE LA DERNIERE COMMANDE DE L'UTILISATEUR DONT L'ID EST PASSE EN PARAMETRE ************/

/**
* Cette fonction permet de récupérer l'id de la dernière commande de l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant l'id de la dernière commande de l'utilisateur ayant l'id passé en paramètre
*/
function recup_last_order_id_user($iId_User)
{
	global $oBdd;
	$aPosts = array();
	$oQuery = $oBdd->prepare(
		'SELECT MAX(order_id) AS id
		FROM orders
		INNER JOIN users
		WHERE user_id = ?
		AND user_id = order_user_id'
	);
	$bResult = $oQuery->execute([$iId_User]);
	if ($bResult)
	{
		$aPosts = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aPosts;
}

/******************************************************************************/
/**********************************  ORDERLINES  ******************************/
/******************************************************************************/

// FONCTION POUR INSERER UNE ORDERLINE

/**
* Cette fonction permet d'ajouter une ligne de commande (orderline) dans la base de données
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param array $aOrderLine Informations de la ligne de commande
* @since 1.0
* @return array Retourne le tableau comprenant les informations de la ligne de commande : quantité commandée, id de la carte cadeau, id de la commande, prix unitaire
*/
function createOrderLine($aOrderLine)
{
	global $oBdd;

	$aPosts = array();

	$oQuery = $oBdd->prepare(
		'INSERT INTO orderlines
		(orderline_quantityordered, orderline_giftcard_id, orderline_order_id, orderline_priceeach)
		VALUES (:orderline_quantityordered, :orderline_giftcard_id, :orderline_order_id, :orderline_priceeach)'
	);

	$bResult = $oQuery->execute($aOrderLine);

	if ($bResult)
	{
		$aPosts = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aPosts;
}

// FONCTION POUR RECUPERER TOUTES LES ORDERLINES DE L'ORDER AYANT L'ID PASSE EN PARAMETRE

/**
* Cette fonction permet de récupérer toutes les lignes de commandes (orderlines) de la commande (order) ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iIdOrder Id de la commande
* @since 1.0
* @return array Retourne le tableau comprenant la liste de toutes les lignes de commandes (nom de la carte cadeau, id de la ligne de commande, prix unitaire, quantité commandée, id de la commande) de la commande (order) ayant l'id passé en paramètre
*/
function getAllOrderLines($iIdOrder)
{
	global $oBdd;
	$aOrderlines = array();
	$oQuery = $oBdd->prepare(
		'SELECT gi.giftscard_name,
		ol.orderline_id, ol.orderline_priceeach,
		ol.orderline_quantityordered, ol.orderline_order_id
		FROM orderlines AS ol
		INNER JOIN giftscards AS gi
		ON ol.orderline_giftcard_id = gi.giftscard_id
		AND ol.orderline_order_id = ?'
	);
	$bResult = $oQuery->execute([$iIdOrder]);
	if ($bResult)
	{
		$aOrderlines = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	return $aOrderlines;
}

/******************************************************************************/
/***************************  COCKTAILS FAVORIS  ******************************/
/******************************************************************************/

// FONCTION POUR VERIFIER SI L'UTILISATEUR A DES COCKTALS FAVORIS OU NON

/**
* Cette fonction permet de vérifier si l'utilisateur ayant l'id passé en paramètre a ajouté des cocktails en favoris ou non
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return boolean Retourne true si l'utilisateur ayant l'id passé en paramètre a ajouté des cocktails en favoris, false sinon
*/

function Verif_Favorites_Cocktail_User($iId_User)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'SELECT user_cocktail_favorites
		FROM users
		WHERE user_id = ?'
	);
	$bResult = $oQuery->execute([$iId_User]);
	if ($bResult)
	{
		$aPosts = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	if($aPosts['user_cocktail_favorites'] != ""){ return true;}
	else { return false;}
}

// FONCTION POUR RECUPERER LA LISTE DES COCKTAILS FAVORIS DE L'UTILISATEUR

/**
* Cette fonction permet de récupérer la liste des cocktails favoris de l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant la liste des cocktails favoris de l'utilisateur ayant l'id passé en paramètre
*/
function Recup_Favorites_Cocktail_User($iId_User)
{
	global $oBdd;
	$aList_Favorites_Cocktails = array();
	$oQuery = $oBdd->prepare(
		'SELECT user_cocktail_favorites
		FROM users
		WHERE user_id = ?'
	);
	$bResult = $oQuery->execute([$iId_User]);
	if ($bResult)
	{
		$aList_Favorites_Cocktails = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aList_Favorites_Cocktails;
}

// FONCTION POUR INSERER UN COCKTAIL DANS LA LISTE DES COCKTAILS FAVORIS DE L'UTILISATEUR

/**
* Cette fonction permet d'ajouter un cocktail dans la liste des cocktails favoris de l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @param string $sCocktail_Name Nom du cocktail favori à ajouter
* @since 1.0
* @return void
*/
function Add_Favorites_Cocktail_User($iId_User,$sCocktail_Name)
{
	global $oBdd;

	// On vérifie si l'utilisateur a déjà des cocktails favoris
	// Si OUI
	// On récupère la liste de ses cocktails favoris
	// On ajoute le nouveau cocktail à la liste puis à la BDD
	// Si Non
	// On fait une insertion directe dans la BDD

	if(Verif_Favorites_Cocktail_User($iId_User))
	{
		$sFavorites = Recup_Favorites_Cocktail_User($iId_User);
		// $sFavorites['user_cocktail_favorites'] ==> Liste des cocktails favoris de l'utilisateur

		$aTab = explode("-",$sFavorites['user_cocktail_favorites']);
		if(!in_array($sCocktail_Name,$aTab))
		{
			array_push($aTab, $sCocktail_Name);
			$sCocktail_Favorites = implode("-",$aTab);
		}
		else
		{
			// Ne rien faire, le cocktail sélectionné est déjà dans la liste des cocktails favoris de l'utilisateur
			exit;
		}
		$aCocktails_Favorites = array(
			'user_id' => $iId_User,
			'user_cocktail_favorites' => $sCocktail_Favorites
		);
	}
	else
	{
		$aCocktails_Favorites = array(
			'user_id' => $iId_User,
			'user_cocktail_favorites' => $sCocktail_Name
		);
	}
	$oQuery = $oBdd->prepare(
		'UPDATE users
		SET user_cocktail_favorites = :user_cocktail_favorites
		WHERE user_id = :user_id'
	);
	$bResult = $oQuery->execute($aCocktails_Favorites);
}

/**
* Cette fonction permet de supprimer un cocktail dans la liste des cocktails favoris de l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @param string $sCocktail_Name Nom du cocktail favori à supprimer
* @since 1.0
* @return void
*/
function Delete_Favorites_Cocktail_User($iId_User,$sCocktail_Name)
{
	global $oBdd;

	if(Verif_Favorites_Cocktail_User($iId_User))
	{
		$sFavorites = Recup_Favorites_Cocktail_User($iId_User);
		// $sFavorites['user_cocktail_favorites'] ==> Liste des cocktails favoris de l'utilisateur

		$aTab = explode("-",$sFavorites['user_cocktail_favorites']);
		if(!in_array($sCocktail_Name,$aTab))
		{
			// Ne rien faire, le cocktail que l'on veut supprimer n'est pas présent dans la liste des cocktails favoris de l'utilisateur
			exit;
		}
		else
		{
			$key = array_search($sCocktail_Name,$aTab);
			unset($aTab[$key]);
			$sCocktail_Favorites = implode("-",$aTab);

			$aCocktails_Favorites = array(
				'user_id' => $iId_User,
				'user_cocktail_favorites' => $sCocktail_Favorites
			);

			$oQuery = $oBdd->prepare(
				'UPDATE users
				SET user_cocktail_favorites = :user_cocktail_favorites
				WHERE user_id = :user_id'
			);
			$bResult = $oQuery->execute($aCocktails_Favorites);
		}
	}
	else
	{
		// Ne rien faire : la liste des cocktails favoris de l'utilisateur est vide
		exit;
	}
}

/******************************************************************************/
/***************************  NOTATION COCKTAILS  *****************************/
/******************************************************************************/

// FONCTION POUR VERIFIER SI L'UTILISATEUR A DEJA "MARKE" DES COCKTALS OU NON

/**
* Cette fonction permet de vérifier si l'utilisateur ayant l'id passé en paramètre a noté des cocktails ou non
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return boolean Retourne true si l'utilisateur ayant l'id passé en paramètre a noté des cocktails, false sinon
*/
function Verif_Marks_Cocktail_User($iId_User)
{
	global $oBdd;
	$oQuery = $oBdd->prepare(
		'SELECT user_cocktail_marks
		FROM users
		WHERE user_id = ?'
	);
	$bResult = $oQuery->execute([$iId_User]);
	if ($bResult)
	{
		$aCocktail_Marks = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	if($aCocktail_Marks['user_cocktail_marks'] != ""){ return true;}
	else { return false;}
}

// FONCTION POUR RECUPERER LA LISTE DES COCKTAILS NOTES PAR L'UTILISATEUR

/**
* Cette fonction permet de récupérer la liste des cocktails notés par l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @since 1.0
* @return array Retourne le tableau comprenant la liste des cocktails notés par l'utilisateur ayant l'id passé en paramètre
*/
function Recup_Marks_Cocktail_User($iId_User)
{
	global $oBdd;
	$aCocktail_Marks = array();
	$oQuery = $oBdd->prepare(
		'SELECT user_cocktail_marks
		FROM users
		WHERE user_id = ?'
	);
	$bResult = $oQuery->execute([$iId_User]);
	if ($bResult)
	{
		$aCocktail_Marks = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aCocktail_Marks;
}

// FONCTION POUR INSERER UNE "MARK" D'UN COCKTAIL DANS LA LISTE DES COCKTAILS NOTES DE L'UTILISATEUR

/**
* Cette fonction permet d'ajouter une note à un cocktail dans la liste des cocktails notés par l'utilisateur ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_User Id de l'utilisateur
* @param string $sCocktail_Name Nom du cocktail noté
* @param int $iNote Note
* @since 1.0
* @return void
*/
function Add_Marks_Cocktail_User($iId_User,$sCocktail_Name,$iNote) // Ajout / Mise à jour dans la Table "users"
{
	global $oBdd;
	$Cocktail_Note = $sCocktail_Name.$iNote;
	// On vérifie si l'utilisateur a déjà noté des cocktails
	// Si OUI
	// On récupère la liste des cocktails qu'il a notés auparavant
	// On ajoute le nouveau cocktail + sa note à la liste puis à la BDD
	// Si Non
	// On fait une insertion directe dans la BDD

	if(Verif_Marks_Cocktail_User($iId_User))
	{
		$sMarks = Recup_Marks_Cocktail_User($iId_User);
		// $sMars['user_cocktail_marks'] ==> Liste des cocktails notés par l'utilisateur

		$aTab = explode("-",$sMarks['user_cocktail_marks']);
		$bVerif = false;
		foreach($aTab as $iKey => $sValue)
		{
			if(substr($sValue, 0, -1) == $sCocktail_Name)
			{
				$bVerif = true;
				if(intval(substr($sValue, -1)) == $iNote)
				{
					// Le nom est le même, la note aussi donc ne rien faire
					exit;
				}
				else
				{
					$aTab[$iKey]=$Cocktail_Note;
				}
			}
		}
		if(!$bVerif)
		{
			array_push($aTab,$Cocktail_Note);
		}
		$sCocktail_Marks = implode("-",$aTab);

		$aCocktails_Marks = array(
			'user_id' => $iId_User,
			'user_cocktail_marks' => $sCocktail_Marks
		);
	}
	else
	{
		$aCocktails_Marks = array(
			'user_id' => $iId_User,
			'user_cocktail_marks' => $Cocktail_Note
		);
	}
	$oQuery = $oBdd->prepare(
		'UPDATE users
		SET user_cocktail_marks = :user_cocktail_marks
		WHERE user_id = :user_id'
	);

	$bResult = $oQuery->execute($aCocktails_Marks);
}

// FONCTION POUR AJOUTER / METTRE A JOUR LA "MARK" DU COCKTAIL DANS LA TABLE "posts"

/**
* Cette fonction permet d'ajouter / de mettre à jour la note d'un post (cocktail) ayant le titre (nom) passé en paramètre en récupérant toutes les notes des utilisateurs qui l'ont notés et en faisant la moyenne
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sCocktail_Name Titre du post (nom du cocktail)
* @since 1.0
* @return void
*/
function Update_Marks_Post($sCocktail_Name)
{
	global $oBdd;
	$iNote_Cocktail = 0;
	$oQuery = $oBdd->prepare(
		'SELECT user_cocktail_marks FROM users
		WHERE user_cocktail_marks != ""'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aCocktails = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	if($aCocktails != "")
	{
		$aNote = array();

		foreach($aCocktails as $iKey => $aValue)
		{
			$aTab = explode("-",$aValue['user_cocktail_marks']);
			foreach($aTab as $iIndex => $sName)
			{
				if(substr($sName, 0, -1) == $sCocktail_Name)
				{
					$aNote[] = intval(substr($sName, -1));
				}
			}
		}
		$iNote_Cocktail = array_sum($aNote) / count($aNote);
	}
	else
	{
		// Ne rien faire car aucun utilisateur a noté ce cocktail
		exit;
	}
	$aTab = array(
		'post_titre' => $sCocktail_Name,
		'post_mark' => $iNote_Cocktail
	);
	$oQuery = $oBdd->prepare(
		'UPDATE posts
		SET post_mark = :post_mark
		WHERE post_titre = :post_titre'
	);

	$bResult = $oQuery->execute($aTab);
}

// FONCTION POUR RECUPERER LA "MARK" DU COCKTAIL AYANT L'ID PASSE EN PARAMETRE
/**
* Cette fonction permet de récupérer la note du post (cocktail) ayant l'id passé en paramètre
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param int $iId_Post Id du post
* @since 1.0
* @return array Retourne le tableau comprenant la note du post (cocktail) ayant l'id passé en paramètre
*/
function Recup_Marks_Cocktail($iId_Post)
{
	global $oBdd;
	$aCocktail_Marks = array();
	$oQuery = $oBdd->prepare(
		'SELECT post_mark
		FROM posts
		WHERE post_id = ?'
	);
	$bResult = $oQuery->execute([$iId_Post]);
	if ($bResult)
	{
		$aCocktail_Marks = $oQuery->fetch(PDO::FETCH_ASSOC);
	}
	return $aCocktail_Marks;
}

// FONCTION POUR RECUPERER LE NOMBRE DE NOTES DU COCKTAIL AYANT L'ID PASSE EN PARAMETRE

/**
* Cette fonction permet de récupérer le nombre de notes du post (cocktail) ayant le titre (nom) passé en paramètre en comptant toutes les notes des utilisateurs qui l'ont notés
* @author Christophe HEBERT <christophe.hebert45@gmail.com>
* @param string $sCocktail_Name Titre du post (nom du cocktail)
* @since 1.0
* @return array Retourne le tableau comprenant le nombre de notes du post (cocktail) ayant le titre (nom) passé en paramètre
*/
function Recup_Number_Marks_Cocktail($sCocktail_Name)
{
	global $oBdd;
	$aNumber_Marks = 0;
	$oQuery = $oBdd->prepare(
		'SELECT user_cocktail_marks FROM users
		WHERE user_cocktail_marks != ""'
	);
	$bResult = $oQuery->execute();
	if ($bResult)
	{
		$aCocktails = $oQuery->fetchAll(PDO::FETCH_ASSOC);
	}
	if($aCocktails != "")
	{
		$aNote = array();
		foreach($aCocktails as $iKey => $aValue)
		{
			$aTab = explode("-",$aValue['user_cocktail_marks']);
			foreach($aTab as $iIndex => $sName)
			{
				if(substr($sName, 0, -1) == $sCocktail_Name)
				{
					$aNumber_Marks ++;
				}
			}
		}
	}
	return $aNumber_Marks;
}

// function DeleteOrderLine($IdOrderLine)
// 	{
// 		global $oBdd;
//
// 		/* SELECTION DE L'ID DE LA COMMANDE correspondant à L'ID DE L'ORDER LINE */
// 		$commande = $database->query('SELECT o.Id FROM `Order` AS o INNER JOIN OrderLine AS ol ON ol.Id = ? AND ol.Order_Id = o.Id', [$IdOrderLine]);
//
// 		/* SUPPRESION DE L'ORDER LINE CORRESPONDANT A $IdOrderLine */
// 		$res = $database->executeSql('DELETE FROM OrderLine WHERE Id = ?', [$IdOrderLine]);
//
//
// 		/* SELECTION DE TOUTES LES ORDER LINE CORRESPONDANTS A L'ID DE LA COMMANDE */
// 		$orderlinescom = $database->query('SELECT ol.Id FROM OrderLine AS ol WHERE ol.Order_Id = ?',[$commande[0]['Id']]);
//
// 		if(count($orderlinescom)>0)
// 		{
// 			for($i=0; $i<count($orderlinescom); $i++)
// 			{
// 				$aTabPrix[$i] = $database->query('SELECT PriceEach FROM OrderLine AS ol WHERE ol.Id = ?', [$orderlinescom[$i]['Id']]);
// 			}
//
// 			$Total = 0;
// 			for($i=0; $i<count($orderlinescom); $i++)
// 			{
// 				$Total += $aTabPrix[$i][0]['PriceEach'];
// 			}
//
// 			$aRes = [
// 			'total' => $Total,
// 			'idorder' => $commande[0]['Id']
// 			];
//
// 			$database->executeSql('UPDATE `Order` SET TotalAmount = :total WHERE Id = :idorder', $aRes);
//
// 			/* CORRIGE CYRIL :
// 			$database->executeSql('UPDATE `Order` SET TotalAmount = (SELECT SUM( PriceEach * QuantityOrdered ) FROM  `OrderLine` WHERE Order_Id=:OrderId) WHERE Id=:OrderId', [$commande[0]['Id']]);
// 			*/
// 		}
// 		else
// 		{
// 			$res = $database->executeSql('DELETE FROM `Order` WHERE Id = ?', [$commande[0]['Id']]);
//
// 			 header('location:./');
// 		}
// 	}
