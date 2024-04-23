<?php
	session_start();
	require("../Connexioni.php");
	if($_GET['Id_Tache']<>"" && $_GET['Id_Tache']<>"0"){
		$req="UPDATE trame_planning SET Id_Tache=".$_GET['Id_Tache'].", Id_WP=".$_GET['Id_WP'].", Id_Prestation=".$_GET['Id_Prestation'].", Commentaire='".addslashes($_GET['Commentaire'])."' WHERE Id=".$_GET['Id']." ";
		$result=mysqli_query($bdd,$req);
	}
 ?>