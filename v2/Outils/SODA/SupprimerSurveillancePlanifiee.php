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

	$req="SELECT Volume FROM soda_plannifmanuelle WHERE Id=".$_GET['Id']." ";
	$result=mysqli_query($bdd,$req);
	$nbSurveillance=mysqli_num_rows($result);
	if($nbSurveillance>0){
		$row=mysqli_fetch_array($result);
		if($row['Volume']==$_GET['Volume']){
			$req="UPDATE soda_plannifmanuelle SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].",DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']." ";
			$result=mysqli_query($bdd,$req);
		}
		else{
			$req="UPDATE soda_plannifmanuelle SET Volume=".$_GET['Volume']." WHERE Id=".$_GET['Id']." ";
			$result=mysqli_query($bdd,$req);
		}
	}
	
	echo "<script>FermerEtRecharger();</script>";

?>
