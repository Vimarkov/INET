<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateHS=TrsfDate_($_GET['DateHS']);
$tab=explode("_",$_GET['Id_Personnes']);
$HorsContrat="";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$req="SELECT DISTINCT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne
			FROM rh_personne_rapportastreinte
			WHERE Suppr=0
			AND Id_Personne=".$Id_Personne." 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND DateAstreinte='".$dateHS."' 
			";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		
		//Impossible si combine travaille pas ce jour là et d'astreinte
		if(TravailCeJourDeSemaine($dateHS,$Id_Personne)=="" && $nb>0 ){
			if($HorsContrat==""){
				if($_SESSION['Langue']=="FR"){
					$HorsContrat="<img width='25px' src='../../Images/attention.png'/>Impossible de déclarer des heures supplémentaires car les personnes suivantes ont déjà une astreinte ce jour-là : <br>";
				}
				else{
					$HorsContrat="<img width='25px' src='../../Images/attention.png'/>Unable to report overtime because the following people are already on call that day : <br>";
				}
			}
			$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
			$result=mysqli_query($bdd,$req);
			$rowPersonne=mysqli_fetch_array($result);
			$HorsContrat.="- ".$rowPersonne['Personne']."<br>";
		}
		
	}
}
echo $HorsContrat;
?>