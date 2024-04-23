<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$nb=NombreAlerteHeureSupp($_GET['Id_Personne']);
echo "<NbHS>".$nb."</NbHS>";
?>