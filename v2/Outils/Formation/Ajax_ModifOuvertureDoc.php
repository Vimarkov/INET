<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
require_once("QCM_Fonctions.php");

$estOuvert=DocestOuvert($_GET['Id']);
if($estOuvert){fermerAccesDocument($_GET['Id']);}
else{ouvrirAccesDocument($_GET['Id']);}

echo $estOuvert;
?>