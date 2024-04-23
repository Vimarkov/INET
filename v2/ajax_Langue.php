<?php
	session_start();
	require("Outils/Connexioni.php");
	$_SESSION['Langue']=$_GET['Langue'];
	
	if($_SESSION['Langue']=="FR"){
		$resultUpdate=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET LangueEN=0 WHERE Id=".$_SESSION['Id_Personne']." ");
	}
	else{
		$resultUpdate=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET LangueEN=1 WHERE Id=".$_SESSION['Id_Personne']." ");
	}
 ?>