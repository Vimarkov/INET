<?php
	session_start();
	require("../Connexioni.php");
	$_SESSION['Id_GPAO']=$_GET['Id_Prestation'];
 ?>