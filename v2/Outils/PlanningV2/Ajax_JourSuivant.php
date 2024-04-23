<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

echo "LEJOUR".date("Y-m-d",strtotime($_GET['laDate']." +1 day"))."FIN";
?>