<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
$requete="UPDATE new_competences_relation 
	SET Evaluation='X',
	Date_Debut='".date('Y-m-d')."'
	WHERE Id=".$_GET['Id']."";
$resultInsertUpdate=mysqli_query($bdd,$requete);
echo "<script>FermerEtRecharger();</script>";
?>
</body>
</html>