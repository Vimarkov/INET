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
			//if(window.opener.document.getElementById('formulaire')){window.opener.document.getElementById('formulaire').submit();}
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
$req="UPDATE new_competences_relation SET Statut_Surveillance='', IgnorerSurveillance=1, Date_Ignore='".date('Y-m-d')."', Id_Ignore=".$_SESSION['Id_Personne']." WHERE Id = ".$_GET['Id'];
$result=mysqli_query($bdd,$req);
echo "<script>FermerEtRecharger();</script>";
?>	
