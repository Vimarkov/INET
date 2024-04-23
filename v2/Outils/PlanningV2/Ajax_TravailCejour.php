<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");

echo "<script>debug.print('test');</script>";
if(TravailCeJourDeSemaine($_GET['DateJour'],$_GET['Id_Personne'])<>""){
	echo "TRAVAIL";
}
?>