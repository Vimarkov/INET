<?php
session_start();
require("../Connexioni.php");
require_once("Globales_Fonctions.php");
require("../Fonctions.php");

$nb=NbDemandeBesoinATraiter();
$nb2=NbDemandeBesoinAPrendreEnCompte();

$texte="";
if($nb>0){
	if($LangueAffichage=="FR"){
		$texte.=$nb." demande(s) à valider";
	}
	else{
		$texte.=$nb." request(s) to validate";
	}
}
if($nb2>0){
	if($texte<>""){$texte.="<br>";}
	if($LangueAffichage=="FR"){
		$texte.=$nb2." demande(s) à prendre en compte";
	}
	else{
		$texte.=$nb2." request(s) to take into account";
	}
}
echo $texte;
?>