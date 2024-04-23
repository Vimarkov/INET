<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$DateHS=TrsfDate_($_GET['DateHS']);
$tab=explode("_",$_GET['Id_Personnes']);
foreach($tab as $Id_Personne){
	if($Id_Personne<>"" && $Id_Personne<>"0"){
		$req="SELECT Id,DateHS,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne
			FROM rh_personne_hs
			WHERE Suppr=0
			AND Id_Personne=".$Id_Personne." 
			AND Etat2<>-1
			AND Etat3<>-1
			AND Etat4<>-1
			AND DateHS='".$DateHS."'
			ORDER BY Personne,DateHS
			";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			$personne="";
			while($rowHS=mysqli_fetch_array($result)){
				$personne.= "- ".$rowHS['Personne']." : ".AfficheDateJJ_MM_AAAA($rowHS['DateHS'])."<br>";
			}
			if($_SESSION['Langue']=="FR"){
				echo "<img width='25px' src='../../Images/attention.png'/>Des heures supplémentaires sont déjà déclarés pendant ce créneau pour les personnes suivantes : <br>".$personne;
			}
			else{
				echo "<img width='25px' src='../../Images/attention.png'/>Overtime is already reported during this time slot for the following people : <br>".$personne;
			}
		}
	}
}
?>