<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$DateJour=$_GET['DateJour'];
$Id_Personne=$_GET['Id_Personne'];
$req="SELECT Id
	FROM rh_personne_rapportastreinte
	WHERE Suppr=0
	AND Id_Personne=".$Id_Personne." 
	AND EtatN1<>-1
	AND EtatN2<>-1
	AND DateAstreinte='".$DateJour."' 
	";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
if($nb>0){
	echo "ASTREINTE";
}
?>