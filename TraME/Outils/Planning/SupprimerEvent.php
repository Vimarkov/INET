<?php
	session_start();
	require("../Connexioni.php");
	$req="DELETE FROM trame_planning WHERE Id=".$_GET['Id']." ";
	$result=mysqli_query($bdd,$req);
 ?>