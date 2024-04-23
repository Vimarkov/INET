<?php
session_start();
require("../Connexioni.php");
require_once("Globales_Fonctions.php");
require("../Fonctions.php");

$nb=NbFinQualifEnAttente();
echo $nb;
?>