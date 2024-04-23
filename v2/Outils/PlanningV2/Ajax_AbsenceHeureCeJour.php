<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateDebut=TrsfDate_($_GET['DateDebut']);
$dateFin=TrsfDate_($_GET['DateFin']);
$tab=explode("_",$_GET['Id_Personnes']);
$Attention="";
$Personne="";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$req="SELECT DISTINCT rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,
			NbHeureAbsJour+NbHeureAbsNuit AS NbHeures,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne,
			(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
			(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
			FROM rh_absence 
			LEFT JOIN rh_personne_demandeabsence 
			ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
			WHERE rh_absence.Suppr=0
			AND rh_personne_demandeabsence.Suppr=0
			AND rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND (NbHeureAbsJour>0 OR NbHeureAbsNuit>0)
			AND Annulation=0
			AND DateDebut<='".$dateFin."'
			AND DateFin>='".$dateDebut."'
			";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			while($rowAbs=mysqli_fetch_array($result)){
				$type=$rowAbs['TypeAbsenceIni'];
				if($rowAbs['Id_TypeAbsenceDefinitif']>0){$type=$rowAbs['TypeAbsenceDef'];}
				$Personne.= $rowAbs['Personne']." - ".AfficheDateJJ_MM_AAAA($rowAbs['DateDebut'])." -> ".AfficheDateJJ_MM_AAAA($rowAbs['DateFin'])." (".$rowAbs['NbHeures']."H ".$type.") <br>";
			}
		}
	}
}
if($Personne<>""){
	if($_SESSION['Langue']=="FR"){
		$Attention="<img width='25px' src='../../Images/attention.png'/>
			Des congés ou absences sont déjà déclarés à l'heure pendant ce créneau pour les personnes suivantes : <br>".$Personne."
			Veuillez vérifier que le congé / absence doit être maintenu
			";
	}
	else{
		$Attention="<img width='25px' src='../../Images/attention.png'/>Leave or absences are already declared on time during this time slot for the following people : <br>".$Personne."
			Please check that leave / absence must be maintained
		";
	}
}
echo $Attention;
?>