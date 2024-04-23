<?php
	session_start();
	require("../Connexioni.php");
	if($_GET['Id_Tache']<>"" && $_GET['Id_Tache']<>"0" && $_GET['Id_Prestation']<>"0"){
		$req="INSERT INTO trame_planning (Id_Prestation,Id_Preparateur,Id_Tache,Id_WP,DateDebut,HeureDebut,HeureFin,Commentaire) ";
		$req.="VALUES (".$_GET['Id_Prestation'].",".$_GET['Id_Prepa'].",".$_GET['Id_Tache'].",".$_GET['Id_WP'].",'".$_GET['laDate']."','".$_GET['heureDebut']."','".$_GET['heureFin']."','".addslashes($_GET['commentaire'])."')";
		$result=mysqli_query($bdd,$req);
	}
 ?>