'use strict';

// $(document).ready(function(){


/*
Pour qu'un cocktail soit équilibré, il faut respecter la règle des 3 S :
Sweet, Strong and Sour.

SWEET :
Jus d'orange
Jus d'ananas
Sirop de Grenadine
Sirop de Sucre de Canne
Triple sec
Curaçao Bleu
Liqueur Coco
Coca-Cola

STRONG :
Vodka
Rhum
Whisky
Tequila
Gin

SOUR :
Jus de citron
Champagne
*/

function alertperdant(){
	alert(product1+" + "+product2+" + "+product3+" est un cocktail dégueulasse !");
	if(confirm("Une autre partie ?"))
	{
		$('.div_svg_perdant').toggle(500);
		reset();
	}
	else
	{
		document.location.replace('../../videogames');
	}
}

function alertgagnant(){
	alert(product1+" + "+product2+" + "+product3+" est un cocktail magnifique ! Très bien équilibré !");
	if(confirm("Une autre partie ?"))
	{
		$('.div_svg_gagnant').toggle(500);
		reset();
	}
	else
	{
		document.location.replace('../../videogames');
	}
}

function reset(){
	$('.select').removeClass('select');
}

var product1, product2, product3;
var result = false;
var cat_product1 = 0, cat_product2 = 0, cat_product3 = 0;

$('article').click(function(){
	if($(this).hasClass('select')) // Si le produit est déjà sélectionné
	{
		$(this).removeClass('select');
	}
	else // Si le produit n'est pas sélectionné
	{
		$(this).addClass('select');
	}
});


$('.result').click(function(){
	if($('.select').length == 3) // Si 3 produits ont été selectionnés
	{
		product1 = $('.select')[0].getAttribute('data');
		product2 = $('.select')[1].getAttribute('data');
		product3 = $('.select')[2].getAttribute('data');
		// alert(product1+" + "+product2+" + "+product3+" vont être shakés ! Qu'est-ce que le barman va en penser?? Cliquer sur la bouteille bleue pour débuter l'animation !");

		switch(product1){
			case "Vodka" :
			case "Rhum" :
			case "Whisky" :
			case "Tequila" :
			case "Gin" : cat_product1 = "strong";
			break;

			case "Champagne" :
			case "Jus de citron" :
			case "Jus d'orange" : cat_product1 = "sour";
			break;

			case "Triple Sec" :
			case "Curaçao Bleu" :
			case "Liqueur Coco" :
			case "Coca-Cola" :
			case "Jus d'ananas" :
			case "Sirop de grenadine" :
			case "Sirop de sucre de canne" : cat_product1 = "sweet";

			default: break;
		}

		switch(product2){
			case "Vodka" :
			case "Rhum" :
			case "Whisky" :
			case "Tequila" :
			case "Gin" : cat_product2 = "strong";
			break;

			case "Champagne" :
			case "Jus de citron" :
			case "Jus d'orange" : cat_product2 = "sour";
			break;

			case "Triple Sec" :
			case "Curaçao Bleu" :
			case "Liqueur Coco" :
			case "Coca-Cola" :
			case "Jus d'ananas" :
			case "Sirop de grenadine" :
			case "Sirop de sucre de canne" : cat_product2 = "sweet";

			default: break;
		}

		switch(product3){
			case "Vodka" :
			case "Rhum" :
			case "Whisky" :
			case "Tequila" :
			case "Gin" : cat_product3 = "strong";
			break;

			case "Champagne" :
			case "Jus de citron" :
			case "Jus d'orange" : cat_product3 = "sour";
			break;

			case "Triple Sec" :
			case "Curaçao Bleu" :
			case "Liqueur Coco" :
			case "Coca-Cola" :
			case "Jus d'ananas" :
			case "Sirop de grenadine" :
			case "Sirop de sucre de canne" : cat_product3 = "sweet";

			default: break;
		}

		if(cat_product1 != 0 && cat_product2 != 0 && cat_product3 != 0 &&
			(
				(cat_product1 == "sweet" && cat_product2 == "strong" && cat_product3 == "sour")
				||
				(cat_product1 == "sweet" && cat_product2 == "sour" && cat_product3 == "strong")
				||
				(cat_product1 == "strong" && cat_product2 == "sweet" && cat_product3 == "sour")
				||
				(cat_product1 == "strong" && cat_product2 == "sour" && cat_product3 == "sweet")
				||
				(cat_product1 == "sour" && cat_product2 == "sweet" && cat_product3 == "strong")
				||
				(cat_product1 == "sour" && cat_product2 == "strong" && cat_product3 == "sweet")
			)
		)
		{
			$('.div_svg_gagnant').toggle(500);
			var start_bottle_gagnant = $('#anim1001');
			start_bottle_gagnant[0].beginElement();
			setTimeout(alertgagnant,38000);
			reset();
		}
		else
		{
			$('.div_svg_perdant').toggle(500);
			var start_bottle_perdant = $('#anim1');
			start_bottle_perdant[0].beginElement();
			setTimeout(alertperdant,38000);
			reset();
		}
	}
	else
	{
		alert("Vous devez sélectionner 3 produits !");
	}
});

// });
