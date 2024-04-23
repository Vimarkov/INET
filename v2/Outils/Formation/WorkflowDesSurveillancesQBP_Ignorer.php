<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formation - Ignorer une surveillance</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">	
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>
</head>
<?php
$reqQ="
	UPDATE 
		new_competences_relation
	SET
		Statut_Surveillance='', 
		IgnorerSurveillance=1, 
		Date_Ignore='".date('Y-m-d')."', 
		Id_Ignore=".$_SESSION['Id_Personne']."
	WHERE
		new_competences_relation.Suppr = 0
		AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin>='".date('Y-m-d')."')
		AND (SELECT Id_Categorie_Qualification FROM new_competences_qualification WHERE new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id)=147
		AND (SELECT Libelle FROM new_competences_qualification WHERE new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id) LIKE 'WA - Basic%'
		AND new_competences_relation.Id_Personne=".$_GET['Id']." ";
$resultQ=mysqli_query($bdd,$reqQ);

echo "<script>FermerEtRecharger();</script>";
?>	
