<html>
<head>
	<title>Compétences - Poste</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>	
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

/**
 * EnvoyerMail_Sortie
 * 
 * Préviens l'IT lors du passage d'une personne en Z-SORTIE
 * 
 * @param int $Id_personne Identifiant de la personne
 * @param date $DateSortie Date de départ de la société
 * 
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function EnvoyerMail_Sortie($Id_personne,$DateSortie)
{
	$req = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id = ".$Id_personne.";";
    $row = mysqli_fetch_array(getRessource($req));
    $Personne_Modifiee = $row['Nom']." ".$row['Prenom'];
	
	//Le sujet
	$sujet = "Départ de Daher industriel services DIS ".$Personne_Modifiee;
	
	$message_html='<html><head><title>Départ de Daher industriel services DIS - '.$Personne_Modifiee.'</title></head>
					<body>Bonjour,<br><br>
					
					'.$Personne_Modifiee.' a été déclaré en SORTIE sur l\'Extranet à partir du '.$DateSortie;

	envoyerMailExtranet("informatique.aaa@daher.com,o.milandou@daher.com", $sujet, "", $message_html);
}


$req="INSERT INTO new_competences_personne_plateforme(Id_Plateforme,Id_Personne) VALUES (14,".$_GET['Id_Personne'].")";
$result=mysqli_query($bdd,$req);

//Récupère les formations
$req= "SELECT DISTINCT Id_Prestation, Id_Formation 
	FROM form_besoin
	WHERE Id_Personne = ".$_GET['Id_Personne']." ";
$ressourceFormations=mysqli_query($bdd,$req);

//Pour chaque formation
while($rFormations = mysqli_fetch_array($ressourceFormations)) {
	//Supprimer les besoins
	$resBesoinsAffectes = Supprimer_BesoinsFormations($rFormations['Id_Prestation'], $rFormations['Id_Formation'],-1,$_GET['Id_Personne'], "MettreEnZSORTIE");
}

//Suppression des 'B' reliées à des besoins supprimés
$req = "
	UPDATE new_competences_relation
	SET Suppr=1
	WHERE new_competences_relation.Suppr=0
	AND new_competences_relation.Id_Besoin>0
	AND Evaluation='B' 
	AND ((SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)<2 
		OR (SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)=5
	)
	AND Id_Besoin IN (
		SELECT form_besoin.Id FROM form_besoin 
		WHERE form_besoin.Suppr=1
	)";
$result=mysqli_query($bdd,$req);

$result=mysqli_query($bdd,"UPDATE new_competences_personne_prestation SET Date_Fin='".date('Y-m-d')."' WHERE Id_Personne=".$_GET['Id_Personne']." AND Date_Debut<='".date('Y-m-d')."' AND (Date_Fin<='0001-01-01' OR Date_Fin>'".date('Y-m-d')."')");

$result=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET MetierPaie='', DateAncienneteCDI='0001-01-01', Contrat='' WHERE Id=".$_GET['Id_Personne']." ");

EnvoyerMail_Sortie($_GET['Id_Personne'],AfficheDateJJ_MM_AAAA(date('Y-m-d')));

echo "<script>opener.location.reload();</script>";
echo "<script>window.close();</script>";

?>
	
</body>
</html>