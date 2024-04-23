<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
require_once("QCM_Fonctions.php");

$estOuvert=QCMestOuvert($_GET['Id']);
if($estOuvert){fermerAccesQCM($_GET['Id']);}
else{ouvrirAccesQCM($_GET['Id']);}

echo $estOuvert;
?>