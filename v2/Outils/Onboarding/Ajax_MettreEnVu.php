<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");

$Id=$_GET['Id'];

$req="SELECT Id FROM onboarding_contenu_lu WHERE Id_Contenu=".$Id." AND Id_Personne=".$_SESSION['Id_Personne']." ";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);

if($nb==0){
	$req="INSERT INTO onboarding_contenu_lu (Id_Contenu,Id_Personne,DateHeureLecture) VALUES (".$Id.",".$_SESSION['Id_Personne'].",'".date('Y-m-d H:i:s')."')";
	$result=mysqli_query($bdd,$req);
}
?>