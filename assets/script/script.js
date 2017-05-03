'use strict';

$(document).ready(function(){

	var domain = "http://www.christophehebert.fr/";

	/************* KONAMI CODE *************/
	/* HAUT HAUT BAS BAS GAUCHE DROITE GAUCHE DROITE B A */
	if (window.addEventListener){
		var kkeys = [], konami = "38,38,40,40,37,39,37,39,66,65";
		window.addEventListener("keydown", function(e){
			kkeys.push( e.keyCode );
			if (kkeys.toString().indexOf(konami) >= 0){
				alert('Espèce de Geek ! =D');
				window.location = "video-games/chifoumi";
			}
		}, true);
	}

	/* EVENEMENT : CONFIRM AVANT DE CREER UN NOUVEAU POST */
	$("#creation_create_post").on('click', function(event){
		if ($("#title_add_post").val() != "")
		{
			if (!confirm("Voulez-vous envoyer votre post ?"))
			{
				event.preventDefault();
			}
			else
			{
				if($("#session").val() == "/") /* Aucun utilisateur n'est connecté */
				{
					alert("Vous devez être connecté pour pouvoir poster un article !");
					event.preventDefault();
				}
				// else
				// {
				// 	var data_add = $(this).parents('form').serialize();
				// 	console.log(data_add);
				// 	$.post("inc/add_post.php", data_add, function(message)
				// 	{
				// 		console.log(message);
				// 		if(message == "KO")
				// 		{
				// 			alert("Echec de l'Ajout du Post!");
				// 		}
				// 		else if(message == "OK")
				// 		{
				// 			document.location.href = "index.php";
				// 			alert("Post ajouté avec succés !");
				// 		}
				// 		else
				// 		{
				// 			//console.log("ERREUR");
				// 			console.log(message);
				// 		}
				// 	});
				// }
			}
		}
	});

	/* EVENEMENT : CONFIRM AVANT DE SUPPRIMER UN POST */
	$(".delete_post").on('click', function(event){
		event.preventDefault();
		if (!confirm("Êtes-vous sûr de vouloir supprimer ce post ?"))
		{
			event.preventDefault();
		}
		else
		{
			var data_get = $(this).attr("href");
			if ((data_get).charAt(0) == "?") // On enlève le "?" présent au début de la chaîne de caractères
			{
				data_get = data_get.substring(1);
			}
			$.get(domain+"/inc/delete_post.php", data_get, function(message)
			{
				if(message == "KO")
				{
					alert("Echec de la Suppression du Post!");
				}
				else if(message == "OK")
				{
					alert("Post Supprimé !");
					document.location.href = "../realisations";
				}
				else
				{
					console.log("ERREUR");
				}
			});
		}
	});

	/* EVENEMENT : CONFIRM AVANT DE MODIFIER UN POST */
	$("#modification_modify_post").on('click', function(event){
		if (!confirm("Êtes-vous sûr de vouloir modifier ce post ?"))
		{
			event.preventDefault();
		}
		else
		{
			if($("#session").val() == "/") /* Aucun utilisateur n'est connecté */
			{
				alert("Vous devez être connecté pour pouvoir modifier un article !");
				event.preventDefault();
			}
			// else
			// {
			// 	var data_modif = $(this).parents('form').serialize();
			//
			// 	$.post("inc/modify_post.php", data_modif, function(message)
			// 	{
			// 		if(message == "KO")
			// 		{
			// 			alert("Echec de la Modification du Post!");
			// 		}
			// 		else if(message == "OK")
			// 		{
			// 			document.location.href = "index.php";
			// 			alert("Modifications effectuées avec succés !");
			// 		}
			// 		else
			// 		{
			// 			//console.log("ERREUR");
			// 			console.log(message);
			// 		}
			// 	});
			// }
		}
	});

	/* EVENEMENT : OUVERTURE/FERMETURE DU FORMULAIRE D'AJOUT D'UN COMMENTAIRE */
	$(".new_comments").on('click', function(){
		if($(this).find('.fa').hasClass("fa-arrow-circle-down"))
		{
			$(this).find('.fa').removeClass("fa-arrow-circle-down");
			$(this).find('.fa').addClass("fa-arrow-circle-up");
		}
		else
		{
			$(this).find('.fa').removeClass("fa-arrow-circle-up");
			$(this).find('.fa').addClass("fa-arrow-circle-down");
		}
		$(this).next().toggle(500);
	});


	/* EVENEMENT : ENVOYER UN COMMENTAIRE DYNAMIQUEMENT */
	$(".send_comment").on('click', function(event){
		event.preventDefault();
		var textarea = $(this).prev('p').children('label').next('textarea');
		var zoneComments = $(this).parents('form')
		.prev('div').prev('.parent_comment');

		var data = $(this).parents('form').serialize();

		//tinyMCE.triggerSave();
		//var valtextarea = $(this).siblings('p').contents('textarea').val();
		// Permet de découper la chaîne et donne un résultat = ".....commentaire="
		//var position = data.search("commentaire");
		//var resultat = data.slice(0,position+12);
		//resultat += valtextarea;
		// Penser à enlever les espaces entre les img (émoticones) sinon ça ne marche pas !

		$.post(domain+"inc/ajax.php", data, function(message){
			if(message == "KO")
			{
				alert("ERREUR : Vous devez être connecté pour commenter un article !");
			}
			else if(message == "empty_comment")
			{
				alert("ERREUR : Votre commentaire est vide !");
			}
			else
			{
				alert("Commentaire ajouté avec succés !");
				zoneComments.append(message);
				textarea.val("");
			}
		});
	});

	/* EVENEMENT : CONFIRM AVANT DE CREER UN NOUVEAU USER */
	$("#creation_create_account").on('click', function(event){
		if ($("#pseudo_create_account").val() != "" && $("#password_create_account").val() != "")
		{
			if (!confirm("Voulez-vous créer votre compte ?")) {
				event.preventDefault();
			}
			else{
				// alert("Votre compte a été créé avec succés !");
				//document.location.replace('../index.php');
			}
		}
	});

	/* EVENEMENT : OUVERTURE/FERMETURE DE LA FENETRE DE CONNEXION */
	$("#logintogglebtn").on('click', function(event){
		event.preventDefault();
		event.stopPropagation();
		$("#logintogglebtn").toggle(500).addClass('hidden');
		$("#loggerarea").toggle(500).removeClass('hidden');
	});

	/* EVENEMENT : VERIFIER AUTHENTIFICATION USER */
	$("#connexion_logger_area").on('click', function(event){
		event.preventDefault();

		if ($("#pseudo__logger_area").val() != "" && $("#password_logger_area").val() != "")
		{
			var data = $(this).parents('form').serialize();

			$.post(domain+"inc/connexion_user.php", data, function(message){
				if(message == "OK")
				{
					window.location.reload();
					// document.location.href = "index.php";
					alert("Connexion réussie !");
				}
				else if(message == "KO")
				{
					alert("Authentification Impossible !");
				}
				else
				{
					console.log('ERREUR : '+message);
				}
			});
		}
	});

	/* EVENEMENT : OUVERTURE/FERMETURE DE "MON COMPTE" AU MOMENT DU "HOVER" */
	$(".div_primary_account").on('mouseover', function(){
		$(".div_account_user").removeClass('hidden');
	});

	$(".div_primary_account").on('mouseleave', function(){
		$(".div_account_user").addClass('hidden');
	});

	/* EVENEMENT : DECONNEXION UTILISATEUR */
	$("#disconnect_user").on('click', function(){
		if (!confirm("Voulez-vous vous déconnecter ?")) {
			event.preventDefault();
		}
		else{
			$.post(domain+"inc/deconnexion_user.php", function(){
				window.location.reload();
				// alert("Vous êtes déconnecté !");
			});
		}
	});

	/* EVENEMENT : DEMANDE CONFIRMATION AVANT DE CHANGER LE STATUT D'UN UTILISATEUR */
	$(".change_user_status").on('click', function(){
		if (!confirm("Êtes-vous sûr de vouloir changer le statut de cet utilisateur ?")) {
			event.preventDefault();
		}
		else{
			var data_get = $(this).attr("href");
			if ((data_get).charAt(0) == "?") // On enlève le "?" présent au début de la chaîne de caractères
			{
				data_get = data_get.substring(1);
			}
			$.get(domain+"inc/change_user_status.php", data_get, function(message){
				if(message == "OK")
				{
					document.location.href = "list_users";
					alert("Changement de statut effectué !");
				}
				else if(message == "KO")
				{
					alert("Changement de statut impossible !");
				}
				else
				{
					console.log('ERREUR');
				}
			});
		}
	});

	/* EVENEMENT : AJOUT D'UNE CARTE CADEAU DANS NOTRE PANIER */
	$(".basket").on('click', function(){
		if (!confirm("Êtes-vous sûr de vouloir ajouter cet article dans votre panier ?")) {
			event.preventDefault();
		}
		else{
			var attr_a = $(this).attr("href");
			var attr_spinner = $(this).prev('form').serialize();
			if ((attr_a).charAt(0) == "?")
			{
				attr_a = attr_a.substring(1); // On enlève le "?" présent au début de la chaîne de caractères
			}
			var control_id = attr_a.substr(-1); // Retourne un segment de chaîne, ici le dernier caractère de la chaîne "attr_a"
			// On devrait obtenir soit 1 soit 2 soit 3 - Valeur que l'on vérifie ensuite
			var control_quantity = attr_spinner.substr(-1);
			var control_big_quantity = attr_spinner.substr(-2);

			if(control_big_quantity == 10){
				control_quantity = control_big_quantity;
			}
			var data_get = attr_a+"&"+attr_spinner;

			if(attr_spinner.indexOf("-") == -1
			&& !isNaN(control_id)
			&& (control_id == 1 || control_id == 2 || control_id == 3)
			&& !isNaN(control_quantity)
			&& (
				control_quantity == 1 ||
				control_quantity == 2 ||
				control_quantity == 3 ||
				control_quantity == 4 ||
				control_quantity == 5 ||
				control_quantity == 6 ||
				control_quantity == 7 ||
				control_quantity == 8 ||
				control_quantity == 9 ||
				control_quantity == 10
			)
		)
		{
			$.get(domain+"inc/basket.php", data_get, function(message){
				if(message == "OK")
				{
					document.location.href = "user_basket";
					alert("Ajout au Panier effectué !");
				}
				else if(message == "KO")
				{
					alert("Impossible d'ajouter au Panier !");
				}
				else if(message == "MAX")
				{
					document.location.href = "gifts_cards";
					alert("Vous avez commandé trop de cartes cadeaux de cette catégorie ! Vous pouvez commander jusqu'à 10 cartes cadeaux dans chaque catégorie !");
				}
				else
				{
					console.log('ERREUR '+message);
				}
			});
		}
		else
		{
			alert("ERREUR !");
			event.preventDefault();
		}
	}
});

/* EVENEMENT : DEMANDE CONFIRMATION AVANT SUPPRESION D'UN ARTICLE DANS LE PANIER */
$(".basket_delete").on('click', function(){
	if (!confirm("Êtes-vous sûr de vouloir supprimer cet article de votre panier ?")) {
		event.preventDefault();
	}
	else{
		var data_get = $(this).attr("href");
		if ((data_get).charAt(0) == "?") // On enlève le "?" présent au début de la chaîne de caractères
		{
			data_get = data_get.substring(1);
		}
		var control_id = data_get.substr(-1);

		if(!isNaN(control_id) && control_id >=1 && control_id <=3)
		{
			$.get(domain+"inc/delete_basket.php", data_get, function(message){
				if(message == "OK")
				{
					document.location.href = "user_basket";
					alert("Suppression effectuée !");
				}
				else if(message == "KO")
				{
					alert("Impossible de supprimer cet article du Panier !");
				}
				else
				{
					console.log('ERREUR '+message);
				}
			});
		}
		else
		{
			event.preventDefault();
		}
	}
});

/* EVENEMENT : ACTUALISATION DU PANIER LORSQU'ON CHANGE LA QUANTITE DANS LE PANIER */
$(".giftcards_quantity_basket").on('click', function(){

	var new_quantity = $(this).val();
	var id_giftcards = $(this).attr("id");
	var target_quantity_id = $(this).parents('form').parents('td').next('.giftscard_total_amount');
	var target_quantity_total = $(this).parents('form').parents('td').parents('tbody').parents('table')
	.next('.basket_total_price')
	.children('p').children('strong');
	if(
		!isNaN(new_quantity) &&
		(
			new_quantity == 1 ||
			new_quantity == 2 ||
			new_quantity == 3 ||
			new_quantity == 4 ||
			new_quantity == 5 ||
			new_quantity == 6 ||
			new_quantity == 7 ||
			new_quantity == 8 ||
			new_quantity == 9 ||
			new_quantity == 10
		)
		&& !isNaN(id_giftcards) &&
		(
			id_giftcards == 1 ||
			id_giftcards == 2 ||
			id_giftcards == 3
		)
	)
	{
		var data_get = "new_quantity="+new_quantity+"&id="+id_giftcards;
		$.get(domain+"inc/update_basket.php", data_get, function(message){
			var aData = JSON.parse(message);

			target_quantity_id.html(aData.total_amount_id+",00 €");
			var quantity_total;
			if(aData.total_quantity == 1)
			{
				quantity_total=" article";
			}
			else
			{
				quantity_total=" articles";
			}
			target_quantity_total.html("TOTAL TTC ("
			+aData.total_quantity+quantity_total
			+") : <span>"+aData.total+",00 €</span>");
		});
	}
	else if(new_quantity == 0)
	{
		$(".basket_delete").trigger('click');
	}
	else if(new_quantity < 0 || new_quantity > 10)
	{
		document.location.href = "user_basket";
		alert ("La Quantité doit être comprise entre 0 et 10 !");
	}
	else
	{
		console.log("ERREUR !");
	}
});

/* EVENEMENT : AFFICHAGE D'UNE DIV SI ON CHANGE LA QUANTITE DU PANIER VIA LE CLAVIER */
$(".giftcards_quantity_basket").keyup(function(touche)
{ // on écoute l'évènement keyup()
var appui = touche.which || touche.keyCode; // le code est compatible tous navigateurs grâce à ces deux propriétés
if(appui >= 48 && appui <= 57)
{ // si le code de la touche est un nombre : 48 => 57 = 0 => 9
	$(".div_giftcards_quantity").show();
}
});

/* EVENEMENT : SI L'UTILISATEUR CLIQUE SUR LA DIV, LA QUANTITE DANS LE PANIER VA SE METTRE A JOUR ET LA DIV VA DISPARAITRE */
$(".div_giftcards_quantity").on('click', function()
{
	$(".giftcards_quantity_basket").trigger('click');
	$(".div_giftcards_quantity").hide();
});

// Enhance a text input for entering numeric values, with up/down buttons and arrow key handling.
$(".spinner").spinner();

// AFFICHAGE DU TABLEAU CONTENANT LES ORDERLINES LIEES A L'ORDER SUR LEQUEL L'UTILISATEUR A CLIQUE
// CHANGEMENT DE L'ICÔNE A CÔTE DE "Détails" : DE + EN - ET DE - EN +
$(".tr_order").on('click', function(){
	$(this).next('tr').children('td').children('.table_orderlines').toggle(100);

	if($(this).children('.td_details_order').children('.details_order').children('.fa').hasClass("fa-plus-circle"))
	{
		$(this).children('.td_details_order').children('.details_order').children('.fa').removeClass("fa-plus-circle");
		$(this).children('.td_details_order').children('.details_order').children('.fa').addClass("fa-minus-circle");
	}
	else
	{
		$(this).children('.td_details_order').children('.details_order').children('.fa').removeClass("fa-minus-circle");
		$(this).children('.td_details_order').children('.details_order').children('.fa').addClass("fa-plus-circle");
	}
});

// CARTE GOOGLE MAPS DANS "CONTACT / RESERVATION"
$('.maps').click(function () {
	$('.maps iframe').css("pointer-events", "auto");
});

$( ".maps" ).mouseleave(function() {
	$('.maps iframe').css("pointer-events", "none");
});

// CLIC SUR LE BOUTON RADIO CORRESPONDANT A L'AVATAR CLIQUE
$('.click_avatar').on('click',function () {
	$(this).parents('label').next('input').trigger('click');
});

// CHANGEMENT DE LA COULEUR DE L'ETOILE LORSQU'ON VEUT AJOUTER UN COCKTAIL A SES FAVORIS
// PUIS AJOUT DU COCKTAIL FAVORI DANS LES INFOS DE L'UTILISATEUR EN BASE DE DONNEES
$('.add_favorites').on('click',function () {
	// $(this).css('color'); ==> On va savoir de quelle couleur est l'étoile
	var etoile = $(this);
	if(etoile.css('color') == "rgb(255, 255, 255)") // Si la couleur de l'étoile est blanche
	{
		var data = "cocktail_name="+etoile.attr('data-ajax');

		$.post(domain+"inc/add_favorites.php", data, function(message){
			if(message == "OK")
			{
				etoile.css('color', 'gold'); // On va modifier la couleur de l'étoile en or
				etoile.next('.add_favorites_em').html(' : Retirer des Favoris');
				alert("Cocktail ajouté à vos Favoris !");
			}
			else
			{
				alert("Erreur !");
				console.log("ERREUR");
			}
		});
	}
	else // rgb(255, 215, 0) // Si la couleur de l'étoile est or
	{
		var data = "cocktail_name="+$(this).attr('data-ajax');
		$.post(domain+"inc/delete_favorites.php", data, function(message){
			if(message == "OK")
			{
				etoile.css('color', 'white'); // On va modifier la couleur de l'étoile en blanc
				etoile.next('.add_favorites_em').html(' : Ajouter aux Favoris');
				alert("Cocktail retiré de vos Favoris !");
			}
			else
			{
				alert("Erreur !");
				console.log("ERREUR");
			}
		});
	}
});

// SYSTEME DE NOTATION EN COEUR AU SURVOL DE LA SOURIS
// On ajoute la classe "js" à la liste pour mettre en place par la suite du code CSS uniquement dans le cas où le Javascript est activé
$("ul.notes-echelle").addClass("js");
// On passe chaque note à l'état grisé par défaut
$("ul.notes-echelle li").addClass("note-off");

// Au survol de chaque note à la souris
$("ul.notes-echelle li").mouseover(function() {
	// On passe les notes supérieures à l'état inactif (par défaut)
	$(this).nextAll("li").addClass("note-off");
	// On passe les notes inférieures à l'état actif
	$(this).prevAll("li").removeClass("note-off");
	// On passe la note survolée à l'état actif (par défaut)
	$(this).removeClass("note-off");
});
// Lorsque l'on sort du sytème de notation à la souris
$("ul.notes-echelle").mouseout(function() {
	// On passe toutes les notes à l'état inactif
	$(this).children("li").addClass("note-off");
	// On simule (trigger) un mouseover sur la note cochée s'il y a lieu
	$(this).find("li input:checked").parent("li").trigger("mouseover");
});


// MÊME RENDU AU CLAVIER :
$("ul.notes-echelle input")
// Lorsque le focus est sur un bouton radio
.focus(function() {
	// On passe les notes supérieures à l'état inactif (par défaut)
	$(this).parent("li").nextAll("li").addClass("note-off");
	// On passe les notes inférieures à l'état actif
	$(this).parent("li").prevAll("li").removeClass("note-off");
	// On passe la note du focus à l'état actif (par défaut)
	$(this).parent("li").removeClass("note-off");
})
// Lorsque l'on sort du sytème de notation au clavier
.blur(function() {
	// Si il n'y a pas de case cochée
	if($(this).parents("ul.notes-echelle").find("li input:checked").length == 0) {
		// On passe toutes les notes à l'état inactif
		$(this).parents("ul.notes-echelle").find("li").addClass("note-off");
	}
});

// QUAND LES IMAGES SONT DESACTIVEES :
$("ul.notes-echelle input")
// Lorsque le focus est sur un bouton radio
.focus(function() {
	// On supprime les classes de focus
	$(this).parents("ul.notes-echelle").find("li").removeClass("note-focus");
	// On applique la classe de focus sur l'item tabulé
	$(this).parent("li").addClass("note-focus");
	// [...] cf. code précédent
})
// Lorsque l'on sort du sytème de notation au clavier
.blur(function() {
	// On supprime les classes de focus
	$(this).parents("ul.notes-echelle").find("li").removeClass("note-focus");
	// [...] cf. code précédent
})
// Lorsque la note est cochée
.click(function() {
	// On supprime les classes de note cochée
	$(this).parents("ul.notes-echelle").find("li").removeClass("note-checked");
	// On applique la classe de note cochée sur l'item choisi
	$(this).parent("li").addClass("note-checked");
});

// On simule un survol souris des boutons cochés par défaut
$("ul.notes-echelle input:checked").parent("li").trigger("mouseover");
// On simule un click souris des boutons cochés
$("ul.notes-echelle input:checked").trigger("click");

// AJOUT DE LA "MARK" DU COCKTAIL DANS LES INFOS DE L'UTILISATEUR EN BASE DE DONNEES
$("ul.notes-echelle li label").click(function() {
	var note = $(this).html();
	var cocktail = $(this).attr('data-ajax');
	var data = "note="+note+"&"+"cocktail_name="+cocktail;
	$.post(domain+"inc/add_marks.php", data, function(message){
		if(message == "OK")
		{
			console.log("OK");
			window.location.reload();
		}
		else
		{
			console.log("ERREUR");
		}
	});
});

// EVENENEMENT POUR AFFICHER / CACHER LE MOT DE PASSE A L'INSCRIPTION DE L'UTILISATEUR
$('.show-password').click(function() {
	if($(this).parents('label').next('input').prop('type') == 'password') {
		//Si c'est un input type password
		$(this).parents('label').next('input').prop('type','text');
		$(this).text('cacher');
	} else {
		//Sinon
		$(this).parents('label').next('input').prop('type','password');
		$(this).text('afficher');
	}
});

// EVENENEMENT POUR AFFICHER / CACHER LE MOT DE PASSE A L'INSCRIPTION DE L'UTILISATEUR (MOZILLA)
$('.show-password_moz').click(function() {
	if($(this).parents('label').next('input').prop('type') == 'password') {
		//Si c'est un input type password
		$(this).parents('label').next('input').prop('type','text');
		$(this).text('cacher');
	} else {
		//Sinon
		$(this).parents('label').next('input').prop('type','password');
		$(this).text('afficher');
	}
});

});
