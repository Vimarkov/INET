<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$Attention="";
$nbAAA=0;
$nbDSK=0;
$nbDaher=0;
if($_GET['MatriculeAAA']<>""){
	$req="SELECT Id
		FROM new_rh_etatcivil 
		WHERE MatriculeAAA='".$_GET['MatriculeAAA']."'
		";
	$result=mysqli_query($bdd,$req);
	$nbAAA=mysqli_num_rows($result);
}
if($_GET['MatriculeDSK']<>""){
	$req="SELECT Id
		FROM new_rh_etatcivil 
		WHERE MatriculeDSK='".$_GET['MatriculeDSK']."'
		";
	$result=mysqli_query($bdd,$req);
	$nbDSK=mysqli_num_rows($result);
}
if($_GET['MatriculeDaher']<>""){
	$req="SELECT Id
		FROM new_rh_etatcivil 
		WHERE MatriculeDaher='".$_GET['MatriculeDaher']."'
		";
	$result=mysqli_query($bdd,$req);
	$nbDaher=mysqli_num_rows($result);
}
if($nbAAA>0 || $nbDSK>0 || $nbDaher>0){
	$Attention="EXISTE";
}

echo $Attention;
?>