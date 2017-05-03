<?php

session_start();

// if (isset($_SESSION['flash'])) {
// 	 echo "Message flash : ".$_SESSION['flash'];
// 	 unset($_SESSION['flash']);
// }

if($_SERVER['REQUEST_URI'] == '/index.php') header('Location:/');

// Pour l'ajout du CAPTCHA
require 'inc/recaptchalib.php';

$siteKey = '6LexIB4UAAAAAOl2OOO0N2_h0HWO4MLOrQ5e2tz_'; // Ma clé publique
$secret = '6LexIB4UAAAAANtbboivGCcJ95D68yoqYLns6FyW'; // Ma clé privée

/************************* REQUIRE ***************************/

// CHARGEMENT DU FICHIER "config.php"
require 'inc/config.php';

// CHARGEMENT DU FICHIER "connexion.php"
require 'inc/connexion.php';

// CHARGEMENT DU FICHIER "functions.php"
require 'inc/functions.php';

// CHARGEMENT DU FICHIER "form_control.php"
require 'inc/form_control.php';

/************************* CHARGEMENT DU HEADER ***************************/

$sPage = (isset($_GET['page']) ? $_GET['page'] : 'accueil');

if($sPage == "accueil"){ include('views/header_accueil.phtml'); }
else
{
	if(isset($_SESSION['pseudo']) && isset($_SESSION['password']))
	{
		if($_SESSION['status'] == 5)
		{
			include('views/header_connect_admin.phtml');
		}
		else
		{
			include('views/header_connect.phtml');
		}
	}
	else
	{
		include('views/header.phtml');
	}
}

/********* SYSTEME DE ROUTAGE **********/

switch($sPage)
{
	case 'accueil':
	include('views/accueil.phtml');
	break;

	case 'cocktail':
	include('views/cocktail.phtml');
	break;

	case 'admin':
	include('views/admin.phtml');
	break;

	case 'confirm_reset_password':
	include('views/confirm_reset_password.phtml');
	break;

	case 'reset_password':
	include('views/reset_password.phtml');
	if(isset($_POST) && isset($_POST['reset_password']))
	{
		$bVerif = false;
		try{
			PseudoControl_Reset($_POST['pseudo']);
			EmailControl_Reset($_POST['mail_user']);
		}
		catch(DomainException $e){
			echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> ".$e->getMessage()." !</p>";
			break;
		}
		if(RecupInfosUser_Pseudo_Mail($_POST['pseudo'], $_POST['mail_user']) != 0){ $bVerif = true; }
		if($bVerif)
		{
			$aUsers = Recup_Infos_User_Mail($_POST['mail_user']);
			$sPseudo = $aUsers['user_pseudo'];
			$sNom = $aUsers['user_lastname'];
			$sPrenom = $aUsers['user_firstname'];

			$char = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$sNew_Pass = str_shuffle($char);
			$sNew_Pass = substr($sNew_Pass,0,15);

			$sNew_Pass_Bdd = cryptagePwd($sNew_Pass);
			Change_Password_User($sNew_Pass_Bdd, $sPseudo);

			$mail = $_POST['mail_user']; // Déclaration de l'adresse de destination.

			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
			/* On filtre les serveurs qui présentent des bogues.
			Sur certains hébergeurs, le passage à la ligne est: "\r\n" */
			{ $passage_ligne = "\r\n"; }
			else{ $passage_ligne = "\n"; }

			/********** Déclaration des messages au format texte et au format HTML.***********/
			$message_txt = "Réinitialisation de votre mot de passe".$passage_ligne.
			"Bonjour ".$sPrenom." ".$sNom.",".$passage_ligne.
			"Vous venez d'effectuer une demande de réinitialisation du mot de passe de votre compte.".$passage_ligne.
			"Si vous n'êtes pas à l'origine de cette demande, personne n'a pu accéder à votre compte mais votre ancien mot de passe n'est plus valide.
			Utilisez le mot de passe ci-dessous pour vous connecter.".$passage_ligne.
			"Vous trouverez ci-dessous les codes d'accès à votre compte :".$passage_ligne.
			"PSEUDO : ".$sPseudo.$passage_ligne.
			"MOT DE PASSE : ".$sNew_Pass.$passage_ligne.
			"Je reste à votre entière disposition pour toute question au 06 82 06 26 29 ou par e-mail
			(répondez simplement à cet e-mail).".$passage_ligne.
			"Cordialement,".$passage_ligne.
			"Christophe HEBERT";

			$message_html = "<html><head></head><body>
			<h1>Mon Atelier Cocktails</h1>".$passage_ligne.
			"<h1>Réinitialisation de votre mot de passe</h1>".$passage_ligne.
			"<p>Bonjour <b>".$sPrenom." ".$sNom.",</b></p>".$passage_ligne.
			"<p>Vous venez d'effectuer une demande de réinitialisation du mot de passe de votre compte.</p>".$passage_ligne.
			"<p>Si vous n'êtes pas à l'origine de cette demande, personne n'a pu accéder à votre compte mais votre ancien mot de passe n'est plus valide.</p>".$passage_ligne.
			"<p>Utilisez le mot de passe ci-dessous pour vous connecter :</p>".$passage_ligne.
			"<p><b>PSEUDO : </b>".$sPseudo.$passage_ligne."</p>".
			"<p><b>MOT DE PASSE</b> : ".$sNew_Pass.$passage_ligne."</p>".
			"<p>Je reste à votre entière disposition pour toute question au 06 82 06 26 29 ou par e-mail
			(répondez simplement à cet e-mail).</p>".$passage_ligne.
			"<p>Cordialement,</p>".$passage_ligne.
			"<p>Christophe HEBERT</p>".
			"</body></html>";

			/************* Création de la boundary ****************/
			$boundary = "-----=".md5(rand());
			/**************************************************/

			/************* Définition du sujet ****************/
			$sujet = "Mon Ateliers Cocktails - Reinitialisation Mot de Passe";
			/**************************************************/

			/*********************** Création du HEADER de l'e-mail ************************/

			/* Déclaration de l'expéditeur */
			$header = "From: \"Christophe\"<christophe.hebert45@gmail.com>".$passage_ligne;

			/* Déclaration de l'adresse de retour */
			$header.= "Reply-to: \"Christophe\" <christophe.hebert45@gmail.com>".$passage_ligne;

			/* Déclaration de la version de MIME */
			$header.= "MIME-Version: 1.0".$passage_ligne;

			/* Déclaration du Content-Type */
			$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			/* multipart/alternative car notre but est d'envoyer un e-mail en mode texte ET en mode HTML
			Le programme qui recevra l'e-mail pourra choisir d'afficher soit la partie HTML soit la partie texte

			Boundary se traduit en français par frontière.
			Boundary va nous permettre de séparer les différentes parties de notre e-mail,
			et c'est OBLIGATOIRE. On pourrait les considérer comme des super-balises.
			Le format d'une boundary est le suivant :
			Format :
			----=Chaîne_aléatoire
			==>
			$boundary = "-----=".md5(rand());
			Une boundary étant une frontière, il lui faut donc aussi une fin.
			Malheureusement, la création de la boundary comme cela ne pourra pas suffire pour le corps du message.
			Il va falloir ajouter ces caractères devant à chaque fois
			qu'on les utilise en dehors de la déclaration qui se situe dans le header.
			Caractères :
			--
			Il va aussi falloir fermer la boundary.
			Pour fermer, il suffit de la réutiliser en l'ajoutant à la fin.
			Fin de boundary :
			--
			*/

			/*******************************************************************************/

			/*************** Création du message. *****************/
			$message = $passage_ligne."--".$boundary.$passage_ligne;
			/*************** Ajout du message au format texte. *****************/
			$message .= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message .= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_txt.$passage_ligne;
			/**************************************************/
			$message.= $passage_ligne."--".$boundary.$passage_ligne;
			/*************** Ajout du message au format HTML. *****************/
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_html.$passage_ligne;
			/**************************************************/
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;

			/**************************************************/
			/*
			- Le Content-Type sert à dire si l'on veut placer à la suite du texte ou du code HTML.
			Mode Texte => text/plain
			Mode HTML => text/html

			- Le charset=\"ISO-8859-1\" est supporté par tous les webmails, contrairement à l'UTF-8.

			- Le Content-Transfer-Encoding permet de définir sur combien de bits sera encodé le message,
			ce qui détermine en fait le nombre de caractères différents possibles.
			Si on souhaite utiliser des accents, on doit obligatoirement régler le Content-Transfer-Encoding sur 8 bits. */

			//var_dump($mail,$sujet,$message,$header);

			/*************** Envoi de l'e-mail. *****************/
			if(mail($mail,$sujet,utf8_decode($message),$header))
			{
				echo "<script type='text/javascript'>document.location.replace('confirm_reset_password');</script>";
			}
			else
			{
				"<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> Mail Non envoyé !</p>";
			}
		}
		else
		{
			echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> Votre pseudo et / ou votre adresse mail n'existe pas !</p>";
		}
	}
	break;

	case 'videogames':
	include('views/video-games.phtml');
	break;

	case 'list_order':
	include('views/list_order.phtml');
	break;

	case 'thanks_order':
	$Id_Order = recup_last_order_id();
	include('views/thanks_order.phtml');
	break;

	case 'confirm_order':
	include('views/confirm_order.phtml');
	if(isset($_POST) && isset($_POST['order_confirm']) && isset($_POST['cgv_yes']) && $_POST['cgv_yes'] == "on" && ($_SESSION['basket']['total'] != 0))
	{
		validCommande($_SESSION['id'],$_SESSION['basket']);

		$_SESSION['basket'] = array();
		$_SESSION['basket']['total'] = 0;
		$_SESSION['basket']['quantity_total'] = 0;

		echo "<script type='text/javascript'>document.location.replace('thanks_order');</script>";
		break;
	}
	else if(isset($_POST) && isset($_POST['order_confirm']) && ($_SESSION['basket']['total'] != 0))
	{
		echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Veuillez accepter les Conditions Générales de Vente !</p>";
		break;
	}
	break;

	case 'user_check_contact_information':
		include('views/user_check_contact_information.phtml');
		if(isset($_POST) && isset($_POST['check_order_contact_information']))
		{
			echo "<script type='text/javascript'>document.location.replace('confirm_order');</script>";
		}
		break;

		case 'user_order_contact_information':
			include('views/user_order_contact_information.phtml');

			if(isset($_POST) && isset($_POST['user_order_contact_information']))
			{
				try{
					CivilityControl($_POST['civility_user_order_contact']);
					FirstNameControl($_POST['firstname_user_order_contact']);
					LastNameControl($_POST['lastname_user_order_contact']);
					AddressControl($_POST['address_user_order_contact']);
					CityControl($_POST['city_user_order_contact']);
					ZipCodeControl($_POST['zipcode_user_order_contact']);
					CountryControl($_POST['country_user_order_contact']);
					PhoneControl($_POST['phone_user_order_contact']);
					EmailControl($_POST['user_id'],$_POST['mail_user_order_contact']);

					BirthDateControl_Day($_POST['day_birthdate_user_order_contact']);
					BirthDateControl_Month($_POST['month_birthdate_user_order_contact']);
					BirthDateControl_Year($_POST['year_birthdate_user_order_contact']);
					BirthDateControl($_POST['day_birthdate_user_order_contact'],
					$_POST['month_birthdate_user_order_contact'],
					$_POST['year_birthdate_user_order_contact']);
				}

				catch(DomainException $e) {
					echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> ".$e->getMessage()." !</p>";
					break;
				}

				$sBirthDate = $_POST['year_birthdate_user_order_contact'].'-'
				.$_POST['month_birthdate_user_order_contact'].'-'
				.$_POST['day_birthdate_user_order_contact'];

				Add_User_Contact(
					$_POST['user_id'],
					$_POST['mail_user_order_contact'],
					$sBirthDate,
					$_POST['civility_user_order_contact'],
					$_POST['firstname_user_order_contact'],
					$_POST['lastname_user_order_contact'],
					$_POST['address_user_order_contact'],
					$_POST['city_user_order_contact'],
					$_POST['zipcode_user_order_contact'],
					$_POST['country_user_order_contact'],
					$_POST['phone_user_order_contact']
				);

				echo "<script type='text/javascript'>document.location.replace('user_check_contact_information');</script>";
			}
			break;

			case 'user_basket':
			include('views/basket.phtml');
			break;

			case 'user_order':
			include('views/order.phtml');
			if(isset($_POST) && isset($_POST['check_user']))
			{
				$_POST['pseudo'] = htmlspecialchars($_POST['pseudo']);
				$_POST['password'] = htmlspecialchars($_POST['password']);

				if (VerifExistUser($_POST['pseudo'], $_POST['password']))
				{
					if($_POST['pseudo'] == $_SESSION['pseudo'])
					{
						if(VerifContactUser($_POST['pseudo']))
						{
							echo "<script type='text/javascript'>document.location.replace('user_check_contact_information');</script>";
						}
						else
						{
							echo "<script type='text/javascript'>document.location.replace('user_order_contact_information');</script>";
						}
					}
					else
					{
						session_unset();
						$aUser = array();
						$aUser = RecupInfosUser($_POST['pseudo']);
						$_SESSION['id'] = $aUser['user_id'];
						$_SESSION['pseudo'] = $aUser['user_pseudo'];
						$_SESSION['password'] = $_POST['password'];
						$_SESSION['mail'] = $aUser['user_mail'];
						$_SESSION['status'] = $aUser['user_status'];

						$aAvatar = Recup_Avatar_User_Pseudo($_POST['pseudo']);

						if(isset($aAvatar['avatar_source']))
						{
							$_SESSION['avatar_source'] = $aAvatar['avatar_source'];
						}
						Update_Lastlogintimestamp($aUser['user_id']);

						if(VerifContactUser($_POST['pseudo']))
						{
							echo "<script type='text/javascript'>document.location.replace('user_check_contact_information');</script>";
						}
						else
						{
							echo "<script type='text/javascript'>document.location.replace('user_order_contact_information');</script>";
						}
					}
				}
				else
				{
					echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Echec d'authentification !</p>";
				}
			}
			break;

			case 'list_users':
			$aList_Users = Recup_List_Users();
			include('views/list_users.phtml');
			break;

			case 'list_posts':
			$aList_Posts = Recup_List_Posts();
			include('views/list_posts.phtml');
			break;

			case 'list_comments':
			if(isset($_GET['id_post'])){
				$aList_Comments_Post = Recup_List_Comments_Post($_GET['id_post']);
			}
			else if(isset($_GET['id_user'])){
				$aList_Comments_User = Recup_List_Comments_User($_GET['id_user']);
			}
			else{
				$aList_Comments = Recup_List_Comments();
			}
			include('views/list_comments.phtml');
			break;

			case 'list_pictures':
			$aList_Pictures = Recup_List_Pictures();
			include('views/list_pictures.phtml');
			break;

			case 'list_avatars':
			$aList_Avatars = Recup_List_Avatars();
			include('views/list_avatars.phtml');
			break;

			case 'list_materials':
			$aList_Materials = Recup_List_Materials();
			include('views/list_materials.phtml');
			break;

			case 'list_ingredients':
			$aList_Ingredients = Recup_List_Ingredients();
			include('views/list_ingredients.phtml');
			break;

			case 'list_giftscards':
				$aList_Giftscards = Recup_List_Giftscards();
				include('views/list_giftscards.phtml');
				break;

				case 'list_orders':
				$aList_Orders = getAllOrders();
				include('views/list_orders.phtml');
				break;

				case 'new':
				if(!isset($_SESSION['id']) || $_SESSION['status'] < 1)
				{
					echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
					echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Vous devez être connecté pour pouvoir poster un article !</p>";
				}
				else
				{
					include('views/form_add_post.phtml');

					if(isset($_POST) && isset($_POST['envoyer']) && isset($_SESSION['id']))
					{
						$bError = false;
						$sError_Message = "";
						$sPicture_Name = "";
						$sFile_Name = "";

						if(!empty($_FILES['picture']['name']))
						{
							$maxsize = $_POST['MAX_FILE_SIZE'];
							$maxwidth = '3000';
							$maxheight = '3000';
							/*
							Une première vérification consiste à savoir si le fichier a bien été uploadé.
							UPLOAD_ERR_NO_FILE : fichier manquant.
							UPLOAD_ERR_INI_SIZE : fichier dépassant la taille maximale autorisée par PHP.
							UPLOAD_ERR_FORM_SIZE : fichier dépassant la taille maximale autorisée par le formulaire.
							UPLOAD_ERR_PARTIAL : fichier transféré partiellement.
							*/
							if ($_FILES['picture']['error'] > 0)
							{
								$bError = true;
								$sError_Message = "Le fichier n'a pas bien été uploadé";
							}
							// Contrôle de la taille maximale
							if ($_FILES['picture']['size'] > $maxsize)
							{
								$bError = true;
								$sError_Message = "Le fichier est trop gros";
							}
							// Contrôle du type
							$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
							// 1. strrchr renvoie l'extension avec le point (« . »).
							// 2. substr(chaine,1) ignore le premier caractère de chaine.
							// 3. strtolower met l'extension en minuscules.
							$extension_upload = strtolower(  substr(  strrchr($_FILES['picture']['name'], '.')  ,1)  );
							if (! in_array($extension_upload,$extensions_valides) )
							{
								$bError = true;
								$sError_Message = "Le fichier n'a pas la bonne extension (jpg, jpeg, gif ou png)";
							}
							// Contrôle de la dimension
							$image_sizes = getimagesize($_FILES['picture']['tmp_name']);
							if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
							{
								$bError = true;
								$sError_Message = "Image trop grande";
							}

							if(!$bError)
							{
								$sFile_Name = $_FILES['picture']['name'];
								// On enlève l'extension du nom
								$sPicture_Name = str_replace($extension_upload,"",$_FILES['picture']['name']);
								// On enlève le '.'
								$sPicture_Name = substr($sPicture_Name, 0, -1);

								$nomfichier = $_SESSION['id'].$_SESSION['pseudo'].$sPicture_Name;
								$nom = "pictures/{$nomfichier}.{$extension_upload}";
								$resultat = move_uploaded_file($_FILES['picture']['tmp_name'],$nom);

								if ($resultat)
								{ echo "Transfert réussi !"; }
								else
								{ echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Transfert échoué !</p>";
									break;
								}
							}
							else
							{ echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Transfert échoué ==> ERREUR : ".$sError_Message."</p>";
								break;
							}
						}
						$aMaterial = array(
							'shaker' => htmlspecialchars($_POST['material_shaker']),
							'verreamelange' => htmlspecialchars($_POST['material_verreamelange']),
							'cuillereamelange' => htmlspecialchars($_POST['material_cuillereamelange']),
							'pilon' => htmlspecialchars($_POST['material_pilon']),
							'blender' => htmlspecialchars($_POST['material_blender'])
						);

						$_POST['title'] = ucwords($_POST['title']); // Met en majuscule la première lettre de tous les mots

						$sPicture_Source = "{$nomfichier}.{$extension_upload}";
						$aPosts = Add_Post(
							htmlspecialchars($_POST['title']),
							htmlspecialchars($_POST['description']),
							htmlspecialchars($_POST['category']),
							htmlspecialchars($_SESSION['id']),
							htmlspecialchars($sPicture_Name),
							htmlspecialchars($sPicture_Source),
							$aMaterial,
							htmlspecialchars($_POST['ingredients'])
						);
						echo "<script type='text/javascript'>
						alert('Post ajouté avec succés !');
						document.location.replace('realisations');</script>";
					}
				}
				break;

				case 'modify':
					if(!isset($_SESSION['id']) || $_SESSION['status'] < 1)
					{
						echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
						echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Vous devez être connecté pour pouvoir poster un article !</p>";
					}
					else
					{
						include('views/form_modif_post.phtml');

						if(isset($_POST) && isset($_POST['modifier']))
						{
							include('control_pictures.php');
							$bError = false;
							$sError_Message = "";
							$sPicture_Name = "";
							$sFile_Name = "";

							if(!empty($_FILES['picture']['name']))
							{
								$maxsize = $_POST['MAX_FILE_SIZE'];
								$maxwidth = '1000';
								$maxheight = '1000';
								/*
								Une première vérification consiste à savoir si le fichier a bien été uploadé.
								UPLOAD_ERR_NO_FILE : fichier manquant.
								UPLOAD_ERR_INI_SIZE : fichier dépassant la taille maximale autorisée par PHP.
								UPLOAD_ERR_FORM_SIZE : fichier dépassant la taille maximale autorisée par le formulaire.
								UPLOAD_ERR_PARTIAL : fichier transféré partiellement.
								*/
								if ($_FILES['picture']['error'] > 0)
								{
									$bError = true;
									$sError_Message = "Le fichier n'a pas bien été uploadé";
								}
								// Contrôle de la taille maximale
								if ($_FILES['picture']['size'] > $maxsize)
								{
									$bError = true;
									$sError_Message = "Le fichier est trop gros";
								}
								// Contrôle du type
								$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
								// 1. strrchr renvoie l'extension avec le point (« . »).
								// 2. substr(chaine,1) ignore le premier caractère de chaine.
								// 3. strtolower met l'extension en minuscules.
								$extension_upload = strtolower(  substr(  strrchr($_FILES['picture']['name'], '.')  ,1)  );
								if (! in_array($extension_upload,$extensions_valides) )
								{
									$bError = true;
									$sError_Message = "Le fichier n'a pas la bonne extension (jpg, jpeg, gif ou png)";
								}
								// Contrôle de la dimension
								$image_sizes = getimagesize($_FILES['picture']['tmp_name']);
								if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
								{
									$bError = true;
									$sError_Message = "Image trop grande";
								}
								if(!$bError)
								{
									$sFile_Name = $_FILES['picture']['name'];
									// On enlève l'extension du nom
									$sPicture_Name = str_replace($extension_upload,"",$_FILES['picture']['name']);
									// On enlève le '.'
									$sPicture_Name = substr($sPicture_Name, 0, -1);

									$nomfichier = $_SESSION['id'].$_SESSION['pseudo'].$sPicture_Name;
									$nom = "pictures/{$nomfichier}.{$extension_upload}";
									$resultat = move_uploaded_file($_FILES['picture']['tmp_name'],$nom);
									if ($resultat)
									{ echo "Transfert réussi !"; }
									else
									{ echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Transfert échoué !</p>";
										break;
									}
								}
								else
								{ echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Transfert échoué ==> ERREUR : ".$sError_Message."</p>";
									break;
								}
							}
							$sPicture_Source = "{$nomfichier}.{$extension_upload}";
							$aMaterial = array(
								'shaker' => htmlspecialchars($_POST['material_shaker']),
								'verreamelange' => htmlspecialchars($_POST['material_verreamelange']),
								'cuillereamelange' => htmlspecialchars($_POST['material_cuillereamelange']),
								'pilon' => htmlspecialchars($_POST['material_pilon']),
								'blender' => htmlspecialchars($_POST['material_blender'])
							);
							$aPosts = Modifier(
								htmlspecialchars($_POST['id_post']),
								htmlspecialchars($_POST['title']),
								htmlspecialchars($_POST['description']),
								htmlspecialchars($_POST['category']),
								htmlspecialchars($_SESSION['id']),
								htmlspecialchars($sPicture_Name),
								htmlspecialchars($sPicture_Source),
								$aMaterial,
								htmlspecialchars($_POST['ingredients'])
							);
							echo "<script type='text/javascript'>
							alert('Modifications effectuées avec succés !');
							document.location.replace('".$domain."cocktail/".$_POST['id_post']."');</script>";
						}
					}
					break;

					case 'create_account':
					include('views/form_create_account.phtml');

					if(isset($_POST) && isset($_POST['creation']))
					{
						try{
							PseudoControl($_POST['pseudo']);
							PasswordControl($_POST['password']);
							PasswordControl($_POST['password_confirm']);
							ConfirmPasswordControl($_POST['password'],$_POST['password_confirm']);
							EmailControl_Register($_POST['mail_user']);
							BirthDateControl_Day($_POST['day_birthdate_user']);
							BirthDateControl_Month($_POST['month_birthdate_user']);
							BirthDateControl_Year($_POST['year_birthdate_user']);
							BirthDateControl($_POST['day_birthdate_user'],
							$_POST['month_birthdate_user'],
							$_POST['year_birthdate_user']);

							/*************** CAPTCHA **************/

							$reCaptcha = new ReCaptcha($secret);

							if(!empty($_POST["g-recaptcha-response"]))
							{
								var_dump($_POST["g-recaptcha-response"]);
								$resp = $reCaptcha->verifyResponse(
									$_SERVER["REMOTE_ADDR"],
									$_POST["g-recaptcha-response"]
								);
								if ($resp != null && $resp->success)
								{
									echo "OK";
								}
								else
								{
									echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> CAPTCHA incorrect !</p>";
									break;
								}
							}
							else
							{
								echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> Cocher la case 'Je ne suis pas un robot' !</p>";
								break;
							}

						}
						catch(DomainException $e) {
							echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> ".$e->getMessage()." !</p>";
							break;
						}

						$sBirthDate = $_POST['year_birthdate_user'].'-'
						.$_POST['month_birthdate_user'].'-'
						.$_POST['day_birthdate_user'];

						$sPassword = cryptagePwd($_POST['password']);

						AjoutUser(
							$_POST['pseudo'],
							$sPassword,
							$_POST['mail_user'],
							$sBirthDate
						);

						echo "<script type='text/javascript'> alert('Votre compte a été créé avec succés !');</script>";
						echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
					}
					break;

					case 'user_profile':
					if(!isset($_SESSION['id']))
					{
						echo "<script type='text/javascript'>document.location.replace('index.php');</script>";
					}
					else
					{
						include('views/user_profile.phtml');

						if(isset($_POST) && isset($_POST['modify_user_profile']))
						{
							if(($_POST['user_id'] == $_SESSION['id']) && ($_POST['pseudo_user_id'] == $_SESSION['pseudo']))
							{
								$bError = false;
								$sError_Message = "";
								$sAvatar_Name = "";
								$sFile_Name = "";

								if(!empty($_FILES['avatar_user_profile']['name']))
								{
									$maxsize = $_POST['MAX_FILE_SIZE'];
									$maxwidth = '1000';
									$maxheight = '1000';
									if ($_FILES['avatar_user_profile']['error'] > 0)
									{
										$bError = true;
										$sError_Message = "Le fichier n'a pas bien été uploadé";
									}
									// Contrôle de la taille maximale
									if ($_FILES['avatar_user_profile']['size'] > $maxsize)
									{
										$bError = true;
										$sError_Message = "Le fichier est trop gros";
									}
									// Contrôle du type
									$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
									// 1. strrchr renvoie l'extension avec le point (« . »).
									// 2. substr(chaine,1) ignore le premier caractère de chaine.
									// 3. strtolower met l'extension en minuscules.
									$extension_upload = strtolower(  substr(  strrchr($_FILES['avatar_user_profile']['name'], '.')  ,1)  );
									if (! in_array($extension_upload,$extensions_valides) )
									{
										$bError = true;
										$sError_Message = "Le fichier n'a pas la bonne extension (jpg, jpeg, gif ou png)";
									}
									// Contrôle de la dimension
									$image_sizes = getimagesize($_FILES['avatar_user_profile']['tmp_name']);
									if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
									{
										$bError = true;
										$sError_Message = "Image trop grande";
									}

									if(!$bError)
									{
										$sFile_Name = $_FILES['avatar_user_profile']['name'];
										// On enlève l'extension du nom
										$sAvatar_Name = str_replace($extension_upload,"",$_FILES['avatar_user_profile']['name']);
										// On enlève le '.'
										$sAvatar_Name = substr($sAvatar_Name, 0, -1);

										$nomfichier = $_SESSION['pseudo'].$sAvatar_Name;
										$nom = "avatars/{$nomfichier}.{$extension_upload}";
										$resultat = move_uploaded_file($_FILES['avatar_user_profile']['tmp_name'],$nom);

										if ($resultat)
										{
											echo "Transfert réussi !";
										}
										else
										{
											echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Transfert échoué !</p>";
											break;
										}
									}
									else
									{
										echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Transfert échoué ==> ERREUR : ".$sError_Message."</p>";
										break;
									}
								}
								$sPicture_Source = "{$nomfichier}.{$extension_upload}";

								if($sPicture_Source == ".") // Si l'utilisateur n'a pas uploadé d'image
								{
									if($_POST['avatar_user_profile_bdd'] == Return_Avatar_Id($_SESSION['id'])['avatar_id'])
									// Si le choix d'avatar dans la bdd de l'utilisateur est le même que celui qu'il a actuellement
									{
										$sPicture_Source = "";
										$sAvatar_Name = "";
									}
									else // Si le choix est différent de celui qu'il a actuellement (ou premier choix)
									{
										$aTab = Return_Avatar_Infos($_POST['avatar_user_profile_bdd']);
										$sAvatar_Name = $aTab['avatar_titre'];
										$sPicture_Source = $aTab['avatar_source'];
									}
								}
								try{
									if(!empty($_POST['new_password_user_profile']) || !empty($_POST['new_password_confirm_user_profile']))
									{
										PasswordModifControl($_POST['password_user_profile'], $_SESSION['password']);
										PasswordControl($_POST['new_password_user_profile']);
										PasswordControl($_POST['new_password_confirm_user_profile']);
										ConfirmPasswordControl($_POST['new_password_user_profile'],$_POST['new_password_confirm_user_profile']);
									}
									EmailControl($_POST['user_id'],$_POST['mail_user_profile']);
									BirthDateControl_Day($_POST['day_birthdate_user_profile']);
									BirthDateControl_Month($_POST['month_birthdate_user_profile']);
									BirthDateControl_Year($_POST['year_birthdate_user_profile']);
									BirthDateControl($_POST['day_birthdate_user_profile'],
									$_POST['month_birthdate_user_profile'],
									$_POST['year_birthdate_user_profile']);
									MAX_FILE_SIZE($_POST['MAX_FILE_SIZE']);

									CivilityControl($_POST['civility_user_profile']);
									FirstNameControl($_POST['firstname_user_profile']);
									LastNameControl($_POST['lastname_user_profile']);
									AddressControl($_POST['address_user_profile']);
									CityControl($_POST['city_user_profile']);
									ZipCodeControl($_POST['zipcode_user_profile']);
									CountryControl($_POST['country_user_profile']);
									PhoneControl($_POST['phone_user_profile']);

									NewsletterControl($_POST['newsletter_user_profile']);

									/*************** CAPTCHA **************/
									// $reCaptcha = new ReCaptcha($secret);
									// if(isset($_POST["g-recaptcha-response"])) {
									//     $resp = $reCaptcha->verifyResponse(
									//         $_SERVER["REMOTE_ADDR"],
									//         $_POST["g-recaptcha-response"]
									//         );
									//     if ($resp != null && $resp->success) {echo "OK";}
									//     else {echo "CAPTCHA incorrect";}
									//     }
								}
								catch(DomainException $e) {
									echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> ".$e->getMessage()." !</p>";
									break;
								}

								$sBirthDate = $_POST['year_birthdate_user_profile'].'-'
								.$_POST['month_birthdate_user_profile'].'-'
								.$_POST['day_birthdate_user_profile'];


								if(!empty($_POST['new_password_user_profile']))
								{
									$sPassword = cryptagePwd($_POST['new_password_user_profile']);
								}
								else
								{
									$sPassword = cryptagePwd($_SESSION['password']);
								}
								if($_POST['newsletter_user_profile'] == "yes"){ $iNewsletter = 1;}
								else { $iNewsletter = 0;}

								Modify_User(
									$_POST['user_id'],
									$sPassword,
									$_POST['mail_user_profile'],
									$sBirthDate,
									$_POST['civility_user_profile'],
									$_POST['firstname_user_profile'],
									$_POST['lastname_user_profile'],
									$_POST['address_user_profile'],
									$_POST['city_user_profile'],
									$_POST['zipcode_user_profile'],
									$_POST['country_user_profile'],
									$_POST['phone_user_profile'],
									$iNewsletter,
									$sAvatar_Name,
									$sPicture_Source
								);
								echo "<script type='text/javascript'> alert('Modifications effectuées avec succés !');</script>";
								echo "<script type='text/javascript'>document.location.replace('user_profile');</script>";
							}
							else
							{
								echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR !</p>";
							}
						}
					}
					break;

					case 'who_am_i':
					include('views/who_am_i.phtml');
					break;

					case 'prestations':
					include('views/prestations.phtml');
					break;

					case 'realisations':
					include('views/realisations.phtml');
					break;

					case 'prestations_cours_cocktails':
					include('views/prestations_cours_cocktails.phtml');
					break;

					case 'prestations_party_cocktails':
					include('views/prestations_party_cocktails.phtml');
					break;

					case 'prestations_team_building':
					include('views/prestations_team_building.phtml');
					break;

					case 'prestations_animation_enfants':
					include('views/prestations_animation_enfants.phtml');
					break;

					case 'mentions_legales':
					include('views/mentions_legales.phtml');
					break;

					case 'cgv':
					include('views/CGV.phtml');
					break;

					case 'gifts_cards':
						include('views/gifts_cards.phtml');
						if(isset($_GET['id_gifts_cards']) && isset($_GET['giftcards_quantity'])){
							echo "<script type='text/javascript'>document.location.replace('gifts_cards');</script>";
						}
						break;

						case 'contact':
						include('views/contact.phtml');
						if(isset($_POST) && isset($_POST['submit_contact']))
						{
							if(isset($_POST['name_user_contact']) &&
							!empty($_POST['name_user_contact']) &&
							!ctype_space($_POST['name_user_contact']) &&
							isset($_POST['mail_user_contact']) &&
							!empty($_POST['mail_user_contact']) &&
							!ctype_space($_POST['mail_user_contact']))
							{
								try{
									NameControl(str_replace(' ','',$_POST['name_user_contact']));
									EmailControl_Reset($_POST['mail_user_contact']);
								}
								catch(DomainException $e){
									echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> ".$e->getMessage()." !</p>";
									break;
								}

								$mail = 'christophe.hebert45@gmail.com'; // Déclaration de l'adresse de destination.

								if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
								/* On filtre les serveurs qui présentent des bogues.
								Sur certains hébergeurs, le passage à la ligne est: "\r\n" */
								{ $passage_ligne = "\r\n"; }
								else{ $passage_ligne = "\n"; }

								if(!empty($_POST['prestation_contact']))
								{
									switch($_POST['prestation_contact']){
										case "cours_cocktails": $sPrestation = "Cours de Cocktails";
										break;
										case "party_cocktails": $sPrestation = "Fêter un Evénement";
										break;
										case "team_building": $sPrestation = "Team Building";
										break;
										case "children_coktails": $sPrestation = "Animation Cocktails Enfants";
										break;
										default: $sPrestation = "";
										break;
									}
								}

								if(!empty($_POST['date_contact']))
								{
									$aTab_Date = explode("-", $_POST['date_contact']);
									$iYear = $aTab_Date[0];
									switch($aTab_Date[1]){
										case "01" : $sMonth = "Janvier";
										break;
										case "02" : $sMonth = "Février";
										break;
										case "03" : $sMonth = "Mars";
										break;
										case "04" : $sMonth = "Avril";
										break;
										case "05" : $sMonth = "Mai";
										break;
										case "06" : $sMonth = "Juin";
										break;
										case "07" : $sMonth = "Juillet";
										break;
										case "08" : $sMonth = "Août";
										break;
										case "09" : $sMonth = "Septembre";
										break;
										case "10" : $sMonth = "Octobre";
										break;
										case "11" : $sMonth = "Novembre";
										break;
										case "12" : $sMonth = "Décembre";
										break;
										default: $sMonth = "";
										break;
									}
									$iDay = $aTab_Date[2];
								}

								/********** Déclaration des messages au format texte et au format HTML.***********/
								$message_txt = " Nom : ".$_POST['name_user_contact'].$passage_ligne.
								"Mon Atelier Cocktails".$passage_ligne.
								" Mail : ".$_POST['mail_user_contact'].$passage_ligne.
								" Prestation : ".$sPrestation.$passage_ligne.
								" Date souhaitee : ".$iDay." ".$sMonth." ".$iYear.$passage_ligne.
								" Heure de Debut : ".$_POST['hour_begin_contact'].":".$_POST['minutes_begin_contact'].$passage_ligne.
								" Heure de Fin : ".$_POST['hour_end_contact'].":".$_POST['minutes_end_contact'].$passage_ligne.
								" Nombre de Personnes : ".$_POST['persons_number'].$passage_ligne.
								" Message : ".$_POST['message_contact'];

								$message_html = "<html><head></head><body>
								<h1>Mon Atelier Cocktails</h1>".$passage_ligne.
								"<h1>Réservation</h1>".$passage_ligne.
								"<p><b> Nom : </b>".$_POST['name_user_contact']."</p>".$passage_ligne.
								"<p><b> Mail : </b>".$_POST['mail_user_contact']."</p>".$passage_ligne.
								"<p><b> Prestation : </b>".$sPrestation."</p>".$passage_ligne.
								"<p><b> Date souhaitée : </b>".$iDay." ".$sMonth." ".$iYear."</p>".$passage_ligne.
								"<p><b> Heure de Début : </b>".$_POST['hour_begin_contact'].":".$_POST['minutes_begin_contact']."</p>".$passage_ligne.
								"<p><b> Heure de Fin : </b>".$_POST['hour_end_contact'].":".$_POST['minutes_end_contact']."</p>".$passage_ligne.
								"<p><b> Nombre de Personnes : </b>".$_POST['persons_number']."</p>".$passage_ligne.
								"<p><b> Message : </b>".$_POST['message_contact']."</p>".
								"</body></html>";

								/************* Création de la boundary ****************/
								$boundary = "-----=".md5(rand());
								/**************************************************/

								/************* Définition du sujet ****************/
								$sujet = "Mon Atelier Cocktails - Réservation";
								/**************************************************/

								/*********************** Création du HEADER de l'e-mail ************************/

								/* Déclaration de l'expéditeur */
								$header = "From: \"".$_POST['name_user_contact']."\"<".$_POST['mail_user_contact'].">".$passage_ligne;

								/* Déclaration de l'adresse de retour */
								$header.= "Reply-to: \"".$_POST['name_user_contact']."\"<".$_POST['mail_user_contact'].">".$passage_ligne;

								/* Déclaration de la version de MIME */
								$header.= "MIME-Version: 1.0".$passage_ligne;

								/* Déclaration du Content-Type */
								$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
								/* multipart/alternative car notre but est d'envoyer un e-mail en mode texte ET en mode HTML
								Le programme qui recevra l'e-mail pourra choisir d'afficher soit la partie HTML soit la partie texte

								Boundary se traduit en français par frontière.
								Boundary va nous permettre de séparer les différentes parties de notre e-mail,
								et c'est OBLIGATOIRE. On pourrait les considérer comme des super-balises.
								Le format d'une boundary est le suivant :
								Format :
								----=Chaîne_aléatoire
								==>
								$boundary = "-----=".md5(rand());
								Une boundary étant une frontière, il lui faut donc aussi une fin.
								Malheureusement, la création de la boundary comme cela ne pourra pas suffire pour le corps du message.
								Il va falloir ajouter ces caractères devant à chaque fois
								qu'on les utilise en dehors de la déclaration qui se situe dans le header.
								Caractères :
								--
								Il va aussi falloir fermer la boundary.
								Pour fermer, il suffit de la réutiliser en l'ajoutant à la fin.
								Fin de boundary :
								--
								*/

								/*******************************************************************************/

								/*************** Création du message. *****************/
								$message = $passage_ligne."--".$boundary.$passage_ligne;
								/*************** Ajout du message au format texte. *****************/
								$message .= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
								$message .= "Content-Transfer-Encoding: 8bit".$passage_ligne;
								$message.= $passage_ligne.$message_txt.$passage_ligne;
								/**************************************************/
								$message.= $passage_ligne."--".$boundary.$passage_ligne;
								/*************** Ajout du message au format HTML. *****************/
								$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
								$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
								$message.= $passage_ligne.$message_html.$passage_ligne;
								/**************************************************/
								$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
								$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
								/**************************************************/
								/*
								- Le Content-Type sert à dire si l'on veut placer à la suite du texte ou du code HTML.
								Mode Texte => text/plain
								Mode HTML => text/html

								- Le charset=\"ISO-8859-1\" est supporté par tous les webmails, contrairement à l'UTF-8.

								- Le Content-Transfer-Encoding permet de définir sur combien de bits sera encodé le message,
								ce qui détermine en fait le nombre de caractères différents possibles.
								Si on souhaite utiliser des accents, on doit obligatoirement régler le Content-Transfer-Encoding sur 8 bits. */

								//var_dump($mail,$sujet,$message,$header);

								/*************** Envoi de l'e-mail. *****************/
								if(mail($mail,$sujet,utf8_decode($message),$header))
								{
									/* Envoi du Mail envoyé à l'expéditeur : Confirmation + Message Envoyé + Indication de Réponse dans les plus brefs délais */

									if(EmailControl_Contact($_POST['mail_user_contact']))
									{
										$message_reponse_txt = "Bonjour Madame, Monsieur,".$passage_ligne.
										"Ce message est envoyé automatiquement suite au courriel que vous venez de m'adresser sur le site christophehebert.fr.".$passage_ligne.
										"J'ai bien réceptionné votre email. Soyez certain que j'y porterai la plus grande attention et que je ferai mon maximum pour y répondre dans les plus brefs délais.".$passage_ligne.
										"Voici le rappel de votre demande de contact / réservation :".$passage_ligne.
										$passage_ligne.
										"Mon Atelier Cocktails".$passage_ligne.
										"Réservation".$passage_ligne.
										" Nom : ".$_POST['name_user_contact'].$passage_ligne.
										"Mon Atelier Cocktails".$passage_ligne.
										" Mail : ".$_POST['mail_user_contact'].$passage_ligne.
										" Prestation : ".$sPrestation.$passage_ligne.
										" Date souhaitee : ".$iDay." ".$sMonth." ".$iYear.$passage_ligne.
										" Heure de Debut : ".$_POST['hour_begin_contact'].":".$_POST['minutes_begin_contact'].$passage_ligne.
										" Heure de Fin : ".$_POST['hour_end_contact'].":".$_POST['minutes_end_contact'].$passage_ligne.
										" Nombre de Personnes : ".$_POST['persons_number'].$passage_ligne.
										" Message : ".$_POST['message_contact'].
										$passage_ligne.
										$passage_ligne.
										"Je reste à votre disposition pour tout renseignement complémentaire.".$passage_ligne.
										"Cordialement,".$passage_ligne.
										"Christophe HEBERT";

										$message_reponse_html = "<html><head></head><body>
										<p>Bonjour Madame, Monsieur,</p>".$passage_ligne.
										"<p><em>Ce message est envoyé automatiquement suite au courriel que vous venez de m'adresser sur le site <a href='http://www.christophehebert.fr'>christophehebert.fr</a>.</em></p>".$passage_ligne.
										"<p>J'ai bien réceptionné votre email. Soyez certain que j'y porterai la plus grande attention et que je ferai mon maximum pour y répondre dans les plus brefs délais.</p>".$passage_ligne.
										"<p>Voici le rappel de votre demande de contact / réservation :</p>".$passage_ligne.
										"<div><em>".$passage_ligne.
										"<h2>Mon Atelier Cocktails - Réservation</h2>".$passage_ligne.
										"<p><b> Nom : </b>".$_POST['name_user_contact']."</p>".$passage_ligne.
										"<p><b> Mail : </b>".$_POST['mail_user_contact']."</p>".$passage_ligne.
										"<p><b> Prestation : </b>".$sPrestation."</p>".$passage_ligne.
										"<p><b> Date souhaitée : </b>".$iDay." ".$sMonth." ".$iYear."</p>".$passage_ligne.
										"<p><b> Heure de Début : </b>".$_POST['hour_begin_contact'].":".$_POST['minutes_begin_contact']."</p>".$passage_ligne.
										"<p><b> Heure de Fin : </b>".$_POST['hour_end_contact'].":".$_POST['minutes_end_contact']."</p>".$passage_ligne.
										"<p><b> Nombre de Personnes : </b>".$_POST['persons_number']."</p>".$passage_ligne.
										"<p><b> Message : </b>".$_POST['message_contact']."</p>".
										"</div></em>".$passage_ligne.
										"<br>".$passage_ligne.
										"<p>Je reste à votre disposition pour tout renseignement complémentaire.</p>".$passage_ligne.
										"<p>Cordialement,</p>".$passage_ligne.
										"<p>Christophe HEBERT</p>".
										"</body></html>";

										/************* Création de la boundary ****************/
										$boundary = "-----=".md5(rand());
										/**************************************************/

										/************* Définition du sujet ****************/
										$sujet = "Mon Atelier Cocktails - Demande de Contact / Réservation";
										/**************************************************/

										/*********************** Création du HEADER de l'e-mail ************************/

										/* Déclaration de l'expéditeur */
										$header_retour = "From: \""."Christophe HEBERT"."\"<".$mail.">".$passage_ligne;

										/* Déclaration de l'adresse de retour */
										$header_retour.= "Reply-to: \""."Christophe HEBERT"."\"<".$mail.">".$passage_ligne;

										/* Déclaration de la version de MIME */
										$header_retour.= "MIME-Version: 1.0".$passage_ligne;

										/* Déclaration du Content-Type */
										$header_retour.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
										/*******************************************************************************/

										/*************** Création du message. *****************/
										$message_retour = $passage_ligne."--".$boundary.$passage_ligne;
										/*************** Ajout du message au format texte. *****************/
										$message_retour .= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
										$message_retour .= "Content-Transfer-Encoding: 8bit".$passage_ligne;
										$message_retour.= $passage_ligne.$message_reponse_txt.$passage_ligne;
										/**************************************************/
										$message_retour.= $passage_ligne."--".$boundary.$passage_ligne;
										/*************** Ajout du message au format HTML. *****************/
										$message_retour.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
										$message_retour.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
										$message_retour.= $passage_ligne.$message_reponse_html.$passage_ligne;
										/**************************************************/
										$message_retour.= $passage_ligne."--".$boundary."--".$passage_ligne;
										$message_retour.= $passage_ligne."--".$boundary."--".$passage_ligne;
										/**************************************************/

										if(mail($_POST['mail_user_contact'],$sujet,utf8_decode($message_retour),$header_retour))
										{
											echo "<p class='warning_mail_ok'><i class='fa fa-check-circle' aria-hidden='true'></i> Mail envoyé ! <br> Vous allez recevoir un message de confirmation.</p>";
										}
										else
										{
											echo "<p class='warning_mail_ok'><i class='fa fa-check-circle' aria-hidden='true'></i> Mail envoyé !</p>";
										}
									}
									else
									{
										echo "<p class='warning_mail_ok'><i class='fa fa-check-circle' aria-hidden='true'></i> Mail envoyé !</p>";
									}
								}
								else
								{
									echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> Mail non envoyé !</p>";
								}
							}
							else
							{
								echo "<p class='warning_connect'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> ERREUR ==> Les champs marqués d'un * sont obligatoires !</p>";
							}
						}
						break;

						default:
						include('views/error_404.phtml');
						break;
					}

					if($sPage == "accueil"){ include('views/footer_accueil.phtml'); }
					else{ include('views/footer.phtml'); }