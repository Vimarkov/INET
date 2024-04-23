<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateDebut1=TrsfDate_($_GET['DateDebut1']);
$dateDebut2=TrsfDate_($_GET['DateDebut2']);
$dateDebut3=TrsfDate_($_GET['DateDebut3']);
$tab=explode("_",$_GET['Id_Personnes']);
$personne="";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$req="SELECT Id,DateHS,Id_Personne,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne
			FROM rh_personne_hs
			WHERE Suppr=0
			AND Id_Personne=".$Id_Personne." 
			AND Etat2<>-1
			AND Etat3<>-1
			AND Etat4<>-1
			AND (DateHS='".$dateDebut1."' OR DateHS='".$dateDebut2."' OR DateHS='".$dateDebut3."')
			ORDER BY Personne,DateHS
			";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			while($rowHS=mysqli_fetch_array($result)){
				if(TravailCeJourDeSemaine($rowHS['DateHS'],$rowHS['Id_Personne'])==""){
					if(estJour_Fixe($rowHS['DateHS'],$rowHS['Id_Personne'])==""){
						$personne.= "- ".$rowHS['Personne']." : ".AfficheDateJJ_MM_AAAA($rowHS['DateHS'])."<br>";
					}
				}
			}
		}
	}
}
if($personne<>""){
	if($_SESSION['Langue']=="FR"){
		echo "<img width='25px' src='../../Images/attention.png'/>Des heures supplémentaires sont déjà déclarés pendant ce créneau pour les personnes suivantes : <br>".$personne;
	}
	else{
		echo "<img width='25px' src='../../Images/attention.png'/>Overtime is already reported during this time slot for the following people : <br>".$personne;
	}
}
?>