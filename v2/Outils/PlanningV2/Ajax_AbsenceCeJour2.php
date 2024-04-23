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
echo "lesABSDEBUT";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$req="SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne
			FROM rh_absence 
			LEFT JOIN rh_personne_demandeabsence 
			ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
			WHERE rh_absence.Suppr=0
			AND rh_personne_demandeabsence.Suppr=0
			AND rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND Annulation=0
			AND rh_personne_demandeabsence.Conge=1
			AND ((DateDebut<='".$dateDebut1."' AND DateFin>='".$dateDebut1."')
				OR (DateDebut<='".$dateDebut2."' AND DateFin>='".$dateDebut2."')
				OR (DateDebut<='".$dateDebut3."' AND DateFin>='".$dateDebut3."')
				OR (DateDebut<='".$dateDebut4."' AND DateFin>='".$dateDebut4."')
				OR (DateDebut<='".$dateDebut5."' AND DateFin>='".$dateDebut5."')
				OR (DateDebut<='".$dateDebut6."' AND DateFin>='".$dateDebut6."')
				OR (DateDebut<='".$dateDebut7."' AND DateFin>='".$dateDebut7."')
			)
			";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			while($rowABS=mysqli_fetch_array($result)){
				echo "- ".$rowABS['Personne']." \n";
			}
		}
	}
}
echo "lesABSFIN";
?>