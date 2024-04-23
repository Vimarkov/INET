<?php
	session_start();
	require("../Connexioni.php");
	if($_GET['heureDebut']=="-1"){
		$req="UPDATE trame_planning SET HeureFin='".$_GET['heureFin']."' WHERE Id=".$_GET['Id']." ";
	}
	else{
		$req="UPDATE trame_planning SET HeureDebut='".$_GET['heureDebut']."',HeureFin='".$_GET['heureFin']."' WHERE Id=".$_GET['Id']." ";
	}
	$result=mysqli_query($bdd,$req);
 ?>