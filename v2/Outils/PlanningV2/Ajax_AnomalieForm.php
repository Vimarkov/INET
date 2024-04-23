<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$nb=NombreAnomalieFormation();
if($nb==0){echo "<div>0</div>";}
else{echo "<div style='color:#de0006;'>".$nb."</div>";}
?>