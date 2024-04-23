<?php
session_start();
require("../Connexioni.php");

$reference=$_GET['reference'];

$Id_WP="";

$reqTE="SELECT Id, Designation, Id_WP FROM trame_travaileffectue WHERE Designation='".$reference."' AND Id_Prestation=".$_SESSION['Id_PrestationTR'];
$resultTE=mysqli_query($bdd,$reqTE);
$nbResultaTE=mysqli_num_rows($resultTE);
if ($nbResultaTE>0){
	$rowTE=mysqli_fetch_array($resultTE);
	$Id_WP=$rowTE['Id_WP'];
}
echo $Id_WP;
?>