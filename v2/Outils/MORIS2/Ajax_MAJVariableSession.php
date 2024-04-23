<?php
session_start();
require("../Connexioni.php");

$_SESSION['MORIS_Mois2']=$_GET['Mois'];
$_SESSION['MORIS_Annee2']=$_GET['Annee'];
$_SESSION['MORIS_Prestation']=$_GET['Prestation'];
?>