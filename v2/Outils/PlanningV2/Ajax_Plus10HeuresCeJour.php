<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateHS=TrsfDate_($_GET['DateHS']);
$nbHeuresJ=$_GET['NbHeuresJ'];
$nbHeuresN=$_GET['NbHeuresN'];
$tab=explode("_",$_GET['Id_Personnes']);
$Plus10Heures="";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$nbTotal=NombreHeuresJournee($Id_Personne,$dateHS)+$nbHeuresJ+$nbHeuresN;
		if($nbTotal>10){
			if($Plus10Heures==""){
				if($_SESSION['Langue']=="FR"){
					$Plus10Heures="<img width='25px' src='../../Images/attention.png'/>Impossible de déclarer des heures supplémentaires car les personnes suivantes ont trop d'heures pour ce jour (max 10 heures / jour) : <br>";
				}
				else{
					$Plus10Heures="<img width='25px' src='../../Images/attention.png'/>Can not report overtime because the following people have too many hours for this day (max 10 hours / day) : <br>";
				}
			}
			$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
			$result=mysqli_query($bdd,$req);
			$rowPersonne=mysqli_fetch_array($result);
			$Plus10Heures.="- ".$rowPersonne['Personne']." : ".$nbTotal." h <br>";
		}
	}
}
echo $Plus10Heures;
?>