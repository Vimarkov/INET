<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$DateJour=TrsfDate_($_GET['DateJour']);
$tab=explode("_",$_GET['Id_Personnes']);
echo "lesASDEBUT";
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$req="SELECT DISTINCT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne
			FROM rh_personne_rapportastreinte
			WHERE Suppr=0
			AND Id_Personne=".$Id_Personne." 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND DateAstreinte='".$DateJour."' 
			";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if(TravailCeJourDeSemaine($DateJour,$Id_Personne)<>"" && $nb>0){
			$row=mysqli_fetch_array($result);
			echo "- ".$row['Personne']." \n";
		}
	}
}
echo "lesASFIN";
?>