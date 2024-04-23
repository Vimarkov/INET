<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateDebut=TrsfDate_($_GET['DateDebut']);
$dateFin=TrsfDate_($_GET['DateFin']);
$tab=explode("_",$_GET['Id_Personnes']);
$Attention="";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$req="SELECT rh_absence.Id 
			FROM rh_absence 
			LEFT JOIN rh_personne_demandeabsence 
			ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
			WHERE rh_absence.Suppr=0
			AND rh_personne_demandeabsence.Suppr=0
			AND rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
			AND EtatN1<>-1
			AND EtatN2<>-1
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
	}
}
echo $Attention;
?>