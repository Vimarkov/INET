<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$Id_Plateforme=0;
$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$_GET['Id_Prestation'];
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
if($nb>0){
	$row=mysqli_fetch_array($result);
	if(estJourFerie(TrsfDate_($_GET['DateDebut']),$row['Id_Plateforme'],$_GET['Id_Prestation'])<>""){
		if($_SESSION['Langue']=="FR"){
			echo "<img width='25px' src='../../Images/attention.png'/> C'est un jour férié ";
		}
		else{
			echo "<img width='25px' src='../../Images/attention.png'/> It's a national holiday";
		}
	}
}
?>