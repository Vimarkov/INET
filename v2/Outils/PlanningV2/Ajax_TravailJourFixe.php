<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateDebut=TrsfDate_($_GET['DateDebut']);
$dateFin=TrsfDate_($_GET['DateFin']);
$tab=explode("_",$_GET['Id_Personnes']);
echo "lesJoursDEBUT";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$reqP="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
		$resultP=mysqli_query($bdd,$reqP);
		$nbP=mysqli_num_rows($resultP);
		
		$Personne="";
		if($nbP>0){
			$rowJP=mysqli_fetch_array($resultP);
			$Personne=$rowJP['Personne'];
		}
		$req="SELECT DateJour, 
				(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsence) AS TypeAbsence
			FROM rh_jourfixe 
			WHERE Suppr=0
			AND Id_Plateforme IN (
				SELECT Id_Plateforme 
				FROM rh_personne_mouvement
				LEFT JOIN new_competences_prestation
				ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
				AND rh_personne_mouvement.EtatValidation=1 
				AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
			)
			AND (Id_Prestation=0 OR Id_Prestation IN (
					SELECT Id_Prestation 
					FROM rh_personne_mouvement
					LEFT JOIN new_competences_prestation
					ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
					WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
					AND rh_personne_mouvement.EtatValidation=1 
					AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
					AND rh_personne_mouvement.Id_Prestation<>0
				)
			)			
			AND DateJour>='".$dateDebut."'
			AND DateJour<='".$dateFin."'
			";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			while($rowJour=mysqli_fetch_array($result)){
				echo $Personne."- ".AfficheDateJJ_MM_AAAA($rowJour['DateJour'])." (".$rowJour['TypeAbsence'].") \n";
			}
		}
	}
}
echo "lesJoursFIN";
?>