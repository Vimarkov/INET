<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$DateDebut=$_GET['DateDebut'];
$DateFin=$_GET['DateFin'];
$Id_Personne=$_GET['Id_Personne'];

$HorsContrat="";
if(EnContratDansCettePeriode($DateDebut,$DateFin,$Id_Personne)==0){
	if($_SESSION['Langue']=="FR"){
		$HorsContrat="<img width='25px' src='../../Images/attention.png'/>Impossible de déclarer des congés car aucun contrat n'existe dans cette période";
	}
	else{
		$HorsContrat="<img width='25px' src='../../Images/attention.png'/>Can not declare holidays because no contract exists in this period";
	}
}

echo $HorsContrat;
?>