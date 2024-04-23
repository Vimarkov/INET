<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$DateDebut=$_GET['DateDebut'];
$DateFin=$_GET['DateFin'];
$Id_Personne=$_GET['Id_Personne'];
$req="SELECT Id,DateHS
	FROM rh_personne_hs
	WHERE Suppr=0
	AND Id_Personne=".$Id_Personne." 
	AND Etat2<>-1
	AND Etat3<>-1
	AND Etat4<>-1
	AND DateHS>='".$DateDebut."'
	AND DateHS<='".$DateFin."'
	ORDER BY DateHS
	";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
if($nb>0){
	if($_SESSION['Langue']=="FR"){
		echo "<img width='25px' src='../../Images/attention.png'/>Des heures supplémentaires sont déjà déclarés pendant ce créneau";
	}
	else{
		echo "<img width='25px' src='../../Images/attention.png'/>Overtime is already reported during this time slot";
	}
}
?>