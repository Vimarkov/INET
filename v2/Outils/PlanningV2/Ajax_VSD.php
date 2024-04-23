<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");


//Pour les congés, vérifier si travail ce jour là ou si en contrat VSD
$IdContrat=IdContrat($_GET['Id_Personne'],$_GET['DateJour']);
$vsd="";
$req="SELECT Id_TempsTravail
	FROM rh_personne_contrat
	WHERE Id=".$IdContrat;
	echo "<script>debug.print('".$req."');</script>";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
if($nb>0){
	$row=mysqli_fetch_array($result);
	if($row['Id_TempsTravail']==18){$vsd="VSD";}
	elseif($row['Id_TempsTravail']==41){$vsd="SD";}
}
echo $vsd;

?>