<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateHS=TrsfDate_($_GET['DateHS']);
$nbHeuresJ=$_GET['NbHeuresJ'];
$nbHeuresN=$_GET['NbHeuresN'];
$tab=explode("_",$_GET['Id_Personnes']);
$Plus48Heures="";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$nbTotal=NombreHeuresSemaine($Id_Personne,$dateHS)+$nbHeuresJ+$nbHeuresN;
		if($nbTotal>48){
			if($Plus48Heures==""){
				if($_SESSION['Langue']=="FR"){
					$Plus48Heures="<img width='25px' src='../../Images/attention.png'/>Impossible de déclarer des heures supplémentaires car les personnes suivantes ont trop d'heures pour cette semaine (max 48 heures / semaine) : <br>";
				}
				else{
					$Plus48Heures="<img width='25px' src='../../Images/attention.png'/>Unable to report overtime because the following people have too many hours for this week (max 48 hours / week) : <br>";
				}
			}
			$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
			$result=mysqli_query($bdd,$req);
			$rowPersonne=mysqli_fetch_array($result);
			$Plus48Heures.="- ".$rowPersonne['Personne']." : ".$nbTotal." h <br>";
		}
	}
}
echo $Plus48Heures;
?>