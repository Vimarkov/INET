<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateDebut=$_GET['DateDebut'];
$dateFin=$_GET['DateFin'];
$CongesEC=$_GET['CongesEC'];
$Id_Personne=$_GET['Id_Personne'];
$Attention="";
$req="SELECT rh_absence.Id 
	FROM rh_absence 
	LEFT JOIN rh_personne_demandeabsence 
	ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
	WHERE rh_absence.Suppr=0
	AND rh_personne_demandeabsence.Suppr=0
	AND rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
	AND EtatN1<>-1
	AND EtatN2<>-1
	AND IF(rh_absence.Id_TypeAbsenceInitial>0,rh_absence.Id_TypeAbsenceInitial,rh_absence.Id_TypeAbsenceDefinitif)<>32
	AND Annulation=0
	AND DateDebut<='".$dateFin."'
	AND DateFin>='".$dateDebut."'
	";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
if($nb>0){
	if($_SESSION['Langue']=="FR"){
		$Attention="<img width='25px' src='../../Images/attention.png'/>Des congés ou absences sont déjà déclarés pendant ce créneau ";
	}
	else{
		$Attention="<img width='25px' src='../../Images/attention.png'/>Holidays or absences are already declared during this date window";
	}
}
else{
	//Vérifier dans les congés EC
	$tabAbsence = explode("|",$CongesEC);
	foreach($tabAbsence as $abs){
		if($abs<>""){
			$tabInfo = explode(";",$abs);
		
			$nbJour=0;
			$dateDebutEC = $tabInfo[0];
			$dateFinEC = $tabInfo[1];

			if($dateDebut<=$dateFinEC && $dateFin>=$dateDebutEC){
				if($_SESSION['Langue']=="FR"){
					$Attention="<img width='25px' src='../../Images/attention.png'/>Des congés ou absences sont déjà déclarés pendant ce créneau ";
				}
				else{
					$Attention="<img width='25px' src='../../Images/attention.png'/>Holidays or absences are already declared during this date window";
				}
			}
		}

	}
}
echo $Attention;
?>