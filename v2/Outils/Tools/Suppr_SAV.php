<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function FermerEtRecharger(Type,Id)
		{
			window.opener.location="Ajout_SAV.php?Type="+Type+"&Id="+Id;
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();

	$requeteUpdate="UPDATE tools_mouvement SET 
			Suppr=1,
			Id_Suppr=".$_SESSION['Id_Personne'].",
			DateSuppr='".date('Y-m-d')."'
			WHERE Id=".$_GET['Id_Mouvement']." ";
	$resultat=mysqli_query($bdd,$requeteUpdate);
	echo "<script>FermerEtRecharger('".$_GET['Type']."','".$_GET['Id']."');</script>";

?>
</body>
</html>