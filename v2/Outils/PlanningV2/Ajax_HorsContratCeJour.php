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
		if(TravailCeJourDeSemaine($dateHS,$Id_Personne)==""){
			if($HorsContrat==""){
				if($_SESSION['Langue']=="FR"){
					$HorsContrat="<img width='25px' src='../../Images/attention.png'/>Impossible de déclarer des heures supplémentaires car les personnes suivantes sont sans contrat : <br>";
				}
				else{
					$HorsContrat="<img width='25px' src='../../Images/attention.png'/>Unable to report overtime because the following people are without a contract : <br>";
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