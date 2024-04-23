<?php
	session_start();
	require("../Connexioni.php");
	require("../Fonctions.php");
	
	Ecrire_Code_JS_Init_Date();
	
	if($_GET['Date']<>""){
		$req="SELECT DateFacturation FROM trame_facturation WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'];
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if($nbResulta>0){
			$req="UPDATE trame_facturation SET DateFacturation='".TrsfDate_($_GET['Date'])."' WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'];
			$result=mysqli_query($bdd,$req);
		}
		else{
			$req="INSERT INTO trame_facturation (DateFacturation,Id_Prestation) VALUES ('".TrsfDate_($_GET['Date'])."',".$_SESSION['Id_PrestationTR'].")";
			$result=mysqli_query($bdd,$req);
		}
	}
 ?>