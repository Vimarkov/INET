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
$HorsContrat="";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		if((EnContratCeJour($dateDebut1,$Id_Personne)==0 && $dateDebut1>"0001-01-01") || (EnContratCeJour($dateDebut2,$Id_Personne)==0 && $dateDebut2>"0001-01-01") || (EnContratCeJour($dateDebut3,$Id_Personne)==0 && $dateDebut3>"0001-01-01") || (EnContratCeJour($dateDebut4,$Id_Personne)==0 && $dateDebut4>"0001-01-01") || (EnContratCeJour($dateDebut5,$Id_Personne)==0 && $dateDebut5>"0001-01-01") || (EnContratCeJour($dateDebut6,$Id_Personne)==0 && $dateDebut6>"0001-01-01") || (EnContratCeJour($dateDebut7,$Id_Personne)==0 && $dateDebut7>"0001-01-01")){
			if($HorsContrat==""){
				if($_SESSION['Langue']=="FR"){
					$HorsContrat="<img width='25px' src='../../Images/attention.png'/>Impossible de déclarer des astreintes car les personnes suivantes sont sans contrat : <br>";
				}
				else{
					$HorsContrat="<img width='25px' src='../../Images/attention.png'/>Unable to report on-call because the following people are without a contract : <br>";
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