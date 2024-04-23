<?php
	if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" 
	|| $_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="frcodc0001"){
		$chemin="http://".$_SERVER['SERVER_NAME']."/suivi_prod";
	}
	elseif($_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
		$chemin="http://".$_SERVER['SERVER_NAME'].":443/suivi_prod";
	}
	else{
		$chemin="https://".$_SERVER['SERVER_NAME']."/suivi_prod";
	}

	// Connexion et sélection de la base
	if($_SERVER['SERVER_NAME']=="127.0.0.1"){
		$bdd=mysqli_connect("localhost:8118", "root", "","aaa_extranet");
	}
	elseif($_SERVER['SERVER_NAME']=="localhost" || $_SERVER['SERVER_NAME']=="192.168.20.3"){
		$bdd=mysqli_connect("192.168.20.3:3306", "aaa_extranet_usr", "t24u9QAcDFadDcy4","aaa_extranet");
	}
	elseif($_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
		$bdd=mysqli_connect("localhost", "aaa_extranet_usr", "t24u9QAcDFadDcy4","aaa_extranet");
	}
	else{
		$bdd=mysqli_connect("localhost", "aaa_extranet_usr", "t24u9QAcDFadDcy4","aaa_extranet");
	}
	
	$bdd->set_charset("latin1");
	if ($bdd->connect_errno) {
		echo "Echec lors de la connexion à MySQL : (" . $bdd->connect_errno . ") " . $bdd->connect_error;
	}
	if(!$bdd){
		echo "<body onload='window.top.location.href=\"".$chemin."/index.php?Cnx=BDD\";'>";
	}
?>
