<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
require_once("QCM_Fonctions.php");

//Faire l'ouverture de tous les QCM pour la personne
//rechercher les qcms de la personne
$arrIds = rechercher_IdSessionPersonneDocument($_GET['Id'],$_GET['Traite']);
//pour chaque QCM
$Ids="";          
foreach($arrIds as $rowId){
	$Ids.=";".$rowId[0];
	if($_GET['Checked'] == "true"){
		ouvrirAccesDocument($rowId[0]);
	}
	else{
		fermerAccesDocument($rowId[0]);
	}
}

echo $Ids;
?>