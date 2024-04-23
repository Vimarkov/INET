<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formation - Suppression besoin en formation</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Production.js"></script>
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger()
		{
			window.opener.document.getElementById('formulaire').submit();
			window.close();
		}
	</script>
</head>
<body>

<?php

//MODE SUPPRESSION
//----------------
$ReqSupprBesoin="
	UPDATE
		form_besoin
	SET
		Traite=0
	WHERE
		Id=".$_GET['Id_Besoin'];
$ResultSupprBesoin=mysqli_query($bdd,$ReqSupprBesoin);

//Suppression des qualifications créées dans la gestion des compétences suite au besoin généré, uniquement si relation non traité
$ReqSupprRelation="UPDATE form_session_personne_qualification 
		SET Suppr=1 
		WHERE Id=".$_GET['Id_SessionPersonneQualification'];
$ResultSupprRelation=mysqli_query($bdd,$ReqSupprRelation);

echo "<script>FermerEtRecharger();</script>";

?>
</body>
</html>
