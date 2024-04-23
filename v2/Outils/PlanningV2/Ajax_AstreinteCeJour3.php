<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateDebut1=TrsfDate_($_GET['DateDebut1']);
$dateDebut2=TrsfDate_($_GET['DateDebut2']);
$dateDebut3=TrsfDate_($_GET['DateDebut3']);
$dateDebut4=TrsfDate_($_GET['DateDebut4']);
$dateDebut5=TrsfDate_($_GET['DateDebut5']);
$dateDebut6=TrsfDate_($_GET['DateDebut6']);
$dateDebut7=TrsfDate_($_GET['DateDebut7']);
$tab=explode("_",$_GET['Id_Personnes']);
$personne="";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$req="SELECT DISTINCT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,DateAstreinte
			FROM rh_personne_rapportastreinte
			WHERE Suppr=0
			AND Id_Personne=".$Id_Personne." 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND (DateAstreinte='".$dateDebut1."' OR DateAstreinte='".$dateDebut2."' OR DateAstreinte='".$dateDebut3."' OR DateAstreinte='".$dateDebut4."' OR DateAstreinte='".$dateDebut5."' OR DateAstreinte='".$dateDebut6."' OR DateAstreinte='".$dateDebut7."')
			";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			while($row=mysqli_fetch_array($result)){
				$personne.= "- ".$row['Personne']." : ".AfficheDateJJ_MM_AAAA($row['DateAstreinte'])." <br>";
			}
		}
	}
}
if($personne<>""){
	if($_SESSION['Langue']=="FR"){
		echo "<img width='25px' src='../../Images/attention.png'/>Des astreintes sont déjà déclarés pendant ce créneau pour les personnes suivantes : <br>".$personne;
	}
	else{
		echo "<img width='25px' src='../../Images/attention.png'/>On-call reports are already declared during this slot for the following people : <br>".$personne;
	}
}
?>