<?php
session_start();
require("../Connexioni.php");

$Attention="";
$req="SELECT Id
	FROM gpao_aircraft 
	WHERE Suppr=0
	AND MSN='".$_GET['MSN']."'
	";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
if($nb>0){
	$Attention="OUI";
}

echo $Attention;
 ?>