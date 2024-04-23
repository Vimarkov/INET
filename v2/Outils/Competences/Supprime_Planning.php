<?php
session_start();	//require("../VerifPage.php");
require("../ConnexioniSansBody.php");
require_once("../Formation/Globales_Fonctions.php");
?>
<html>
<head>
	<title>Compétences - Profil personne - Prestation</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Feuille.css" type="text/css">
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
		}
	</script>
</head>
<body>
<?php
	$leDebut1 = date('Y-m-d', $_GET['Debut1']);
	$leDebut2 = date('Y-m-d', $_GET['Debut2']);
	
	$req = "DELETE FROM new_planning_personne_vacationabsence ";
	$req .= "WHERE new_planning_personne_vacationabsence.Id_Personne = ".$_GET['Id_Personne']." ";
	$req .= "AND new_planning_personne_vacationabsence.Id_Prestation = ".$_GET['Id_Prestation']." ";
	$req .= "AND ( ";
	if ($_GET['Fin1'] <> 0){
		$req .= "( ";
	}
	$req .= "new_planning_personne_vacationabsence.DatePlanning >='".$leDebut1."' ";
	$req .= "AND new_planning_personne_vacationabsence.DatePlanning <='".$leDebut2."' ";
	if ($_GET['Fin1'] <> 0){
		$leFin1 = date('Y-m-d', $_GET['Fin1']);
		$leFin2 = date('Y-m-d', $_GET['Fin2']);
		$req .= ") OR ";
		$req .= "(new_planning_personne_vacationabsence.DatePlanning >='".$leDebut1."' ";
		$req .= "AND new_planning_personne_vacationabsence.DatePlanning <='".$leDebut2."') ";
	}
	$req .= ")";
	$result=mysqli_query($bdd,$req);
	
	$req = "DELETE FROM new_planning_personne_formation ";
	$req .= "WHERE new_planning_personne_formation.ID_Personne = ".$_GET['Id_Personne']." ";
	$req .= "AND new_planning_personne_formation.Id_Prestation = ".$_GET['Id_Prestation']." ";
	$req .= "AND ( ";
	if ($_GET['Fin1'] <> 0){
		$req .= "( ";
	}
	$req .= "new_planning_personne_formation.DateFormation >='".$leDebut1."' ";
	$req .= "AND new_planning_personne_formation.DateFormation <='".$leDebut2."' ";
	if ($_GET['Fin1'] <> 0){
		$leFin1 = date('Y-m-d', $_GET['Fin1']);
		$leFin2 = date('Y-m-d', $_GET['Fin2']);
		$req .= ") OR ";
		$req .= "(new_planning_personne_formation.DateFormation >='".$leDebut1."' ";
		$req .= "AND new_planning_personne_formation.DateFormation <='".$leDebut2."') ";
	}
	$req .= ")";
	$result=mysqli_query($bdd,$req);
	
	if($_GET['ModeProfil'] <> "0"){
		echo "<script>alert('Pensez à avertir les moyens généraux et le pôle informatique pour le transfert du matériel !')</script>";
		echo "<script>FermerEtRecharger('Profil.php?Mode=".$_GET['ModeProfil']."&Id_Personne=".$_GET['Id_Personne']."');</script>";
	}
	else{
		echo "<script>alert('Pensez à avertir les moyens généraux et le pôle informatique pour le transfert du matériel !')</script>";
		echo "<script>window.location.replace('Profil.php?Mode=Modif&Id_Personne=".$_GET['Id_Personne']."');</script>";
	}
?>