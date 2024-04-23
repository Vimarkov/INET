<?php
	session_start();
	require("../Connexioni.php");
	$req="SELECT Id FROM trame_plannif WHERE Id_Preparateur=".$_GET['Id_Preparateur']." AND Semaine=".$_GET['Semaine']."  AND Annee=".$_GET['Annee']." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	if($nbResulta>0){
		$req="UPDATE trame_plannif SET Valide=".$_GET['Valide'].", Id_ResponsableValide=".$_SESSION['Id_PersonneTR'].", DateValidation='".date("Y-m-d")."' WHERE Id_Preparateur=".$_GET['Id_Preparateur']." AND Semaine=".$_GET['Semaine']."  AND Annee=".$_GET['Annee']." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
		$result=mysqli_query($bdd,$req);
	}
	else{
		$req="INSERT INTO trame_plannif (Id_Preparateur,Semaine,Annee,NbHeure,Id_Responsable,DateMAJ,Id_Prestation,Valide,Id_ResponsableValide,DateValidation) ";
		$req.="VALUES (".$_GET['Id_Preparateur'].",".$_GET['Semaine'].",".$_GET['Annee'].",0,".$_SESSION['Id_PersonneTR'].",'".date("Y-m-d")."',".$_SESSION['Id_PrestationTR'].",".$_GET['Valide'].",".$_SESSION['Id_PersonneTR'].",'".date("Y-m-d")."') ";
		$result=mysqli_query($bdd,$req);
	}
 ?>