<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet - Formation - Workflow des surveillances - Validation</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>

<?php

$reqQ="
	UPDATE 
		new_competences_relation
	SET
		Date_PlanifSurveillance='".date('Y-m-d')."',
		Id_PlanifSurveillance=".$_SESSION['Id_Personne'].",
		IgnorerSurveillance=0
	WHERE
		new_competences_relation.Suppr = 0
		AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin>='".date('Y-m-d')."')
		AND (SELECT Id_Categorie_Qualification FROM new_competences_qualification WHERE new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id)=147
		AND (SELECT Libelle FROM new_competences_qualification WHERE new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id) LIKE 'WA - Basic%'
		AND new_competences_relation.Id_Personne=".$_GET['Id']." ";
$resultQ=mysqli_query($bdd,$reqQ);
echo "<script>FermerEtRecharger();</script>";

?>
</body>
</html>