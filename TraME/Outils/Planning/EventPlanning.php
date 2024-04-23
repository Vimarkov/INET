<?php
	session_start();
	require("../Connexioni.php");
	$nbHeure=0;
	if($_GET['NbH']<>""){
		$nbHeure=$_GET['NbH'];
	}
	$req="SELECT Id FROM trame_plannif WHERE Id_Preparateur=".$_GET['Id_Preparateur']." AND Semaine=".$_GET['Semaine']."  AND Annee=".$_GET['Annee']." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	if($nbResulta>0){
		$req="UPDATE trame_plannif SET NbHeure=".$nbHeure.", Id_Responsable=".$_SESSION['Id_PersonneTR'].", DateMAJ='".date("Y-m-d")."' WHERE Id_Preparateur=".$_GET['Id_Preparateur']." AND Semaine=".$_GET['Semaine']."  AND Annee=".$_GET['Annee']." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
		$result=mysqli_query($bdd,$req);
	}
	else{
		$req="INSERT INTO trame_plannif (Id_Preparateur,Semaine,Annee,NbHeure,Id_Responsable,DateMAJ,Id_Prestation) ";
		$req.="VALUES (".$_GET['Id_Preparateur'].",".$_GET['Semaine'].",".$_GET['Annee'].",".$nbHeure.",".$_SESSION['Id_PersonneTR'].",'".date("Y-m-d")."',".$_SESSION['Id_PrestationTR'].") ";
		$result=mysqli_query($bdd,$req);
	}
?>