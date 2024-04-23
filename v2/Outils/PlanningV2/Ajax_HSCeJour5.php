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
$tabDate= array($dateDebut1,$dateDebut2,$dateDebut3,$dateDebut4,$dateDebut5,$dateDebut6,$dateDebut7);
echo "lesHSDEBUT";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		foreach($tabDate as $laDate){
			if($laDate>"0001-01-01"){
				$req="SELECT Id,DateHS,Id_Personne,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne
					FROM rh_personne_hs
					WHERE Suppr=0
					AND Id_Personne=".$Id_Personne." 
					AND Etat2<>-1
					AND Etat3<>-1
					AND Etat4<>-1
					AND DateHS='".$laDate."'
					ORDER BY Personne,DateHS
					";
				$result=mysqli_query($bdd,$req);
				$nb=mysqli_num_rows($result);
				if(TravailCeJourDeSemaine($laDate,$Id_Personne)<>"" && $nb>0){
					$personne="";
					while($rowHS=mysqli_fetch_array($result)){
						echo "- ".$rowHS['Personne']." : ".AfficheDateJJ_MM_AAAA($rowHS['DateHS'])."\n";
					}
				}
			}
		}
	}
}
echo "lesHSFIN";
?>