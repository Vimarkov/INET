<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location='Tableau_De_Bord.php?Menu=26';
			window.close();
		}
	</script>
</head>

<?php
if($_GET['Valider']=='valider'){$traitement=1;}
else{$traitement=-1;}
$req="UPDATE soda_surveillance_question SET TraitementNA=".$traitement." WHERE Id=".$_GET['Id']." ";
$resultUpd=mysqli_query($bdd,$req);

if($traitement==1){
	$req="SELECT Id_Prestation,soda_surveillance_question.Id_Question
		FROM soda_surveillance
		LEFT JOIN soda_surveillance_question
		ON soda_surveillance.Id=soda_surveillance_question.Id_Surveillance 
		WHERE soda_surveillance_question.Id=".$_GET['Id']." ";
	$resultSel=mysqli_query($bdd,$req);
	$nbSurveillance=mysqli_num_rows($resultSel);
	if($nbSurveillance>0){
		$rowSurveillance=mysqli_fetch_array($resultSel);
		$req="SELECT Id FROM soda_question_exceptionprestation WHERE Suppr=0 AND Id_Question=".$rowSurveillance['Id_Question']." AND Id_Prestation=".$rowSurveillance['Id_Prestation']." ";
		$resultExc=mysqli_query($bdd,$req);
		$nbExc=mysqli_num_rows($resultExc);
		if($nbExc==0){
			$req="INSERT INTO soda_question_exceptionprestation (Id_Question,Id_Prestation,DateCreation,Id_Creation)
				VALUES (".$rowSurveillance['Id_Question'].",".$rowSurveillance['Id_Prestation'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].") ";
			$resultIns=mysqli_query($bdd,$req);
		}
	}
}
echo "<script>FermerEtRecharger();</script>";
?>
</body>
</html>