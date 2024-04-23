<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once 'Globales_Fonctions.php';
require_once("../Fonctions.php");

//Calcul du nombre de lignes totales sur max 10 colonnes de pages et 4 par pages
$chaine = $_GET['Id'];
$Ids = explode(',', $chaine);
$nbTotal = count($Ids);

$mod = $nbTotal; // Le reste en cas de page incomplete
$nbTotalPages = ($nbTotal - $mod);
if($mod > 0)
	$nbTotalPages++; //Il existe une page incomplète	
	
//On divise le nombre de pages par 10 (10 pages par lignes)
	$mod = $nbTotalPages;
	$nbTotalLignes = ($nbTotalPages- $mod);
if($mod > 0)
	$nbTotalLignes++;

	
$nbPages = 0;
$OffsetColonne = 0;
$OffsetLigneDePage = 0;

$wb= creerFichier();																						//Creer le fichier MSExcel

//Boucle pour la création des pages
$nb=0;
for($curseur = 0; $curseur < $nbTotal; $curseur++) {
	//Calcul d'une page
	$mod = $curseur;
	$offsetLignedansPage = $mod * 12;
	/*
	if($mod== 0) {//Page suivante
		$nbPages++;
		//$OffsetColonne += 15;
		
		if($nbPages == 1)
			$OffsetColonne = 0;
		
		
		If(($nbPages % 10) == 0) { //Nouvelle ligne de pages
			//$OffsetColonne = 0;
			$OffsetLigneDePage++;
		}
		
	}*/

	//Calcul du numéro de la ligne MSExcel
	$offsetLigne = ($OffsetLigneDePage * 48) +$offsetLignedansPage;

	ecrireAutorisationDeTravail($wb, $Ids[$curseur], $OffsetColonne, $offsetLigne,$nb);				//Ecrit une autorisation de conduite position num 2
	$nb++;
	if($nb==4){$nb=0;}
}

enregistrerFichier($wb);																			//Enregistre le fichier MSExcel
	
	?>