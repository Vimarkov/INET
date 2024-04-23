<?php
	
	if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" 
	|| $_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="frcodc0001"){
		$chemin="http://".$_SERVER['SERVER_NAME']."/v2";
	}
	elseif($_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
		$chemin="http://".$_SERVER['SERVER_NAME'].":443/v2";
	}
	else{
		$chemin="https://".$_SERVER['SERVER_NAME']."/v2";
	}
//    $chemin='https://extranet.aaa-aero.com';
	if($_SERVER['SERVER_NAME']=="127.0.0.1")
	{
		$CheminUpload="../../Upload/";
		$CheminFicLIRH="LIRH_fichiers/";
		$CheminQualite="../../Qualite/";
		$CheminFormation="Docs/";
		$CheminRecrutement="Documents/";
	}
	//else{$CheminUpload="http://aaa-extranet.thotem.com/Upload/";}
	else
	{
		$CheminUpload="/var/www/html/Upload/";
		$CheminFicLIRH="/var/www/html/Outils/LIRH/LIRH_fichiers/";
		$CheminQualite="/var/www/html/Qualite/";
		$CheminFormation="/var/www/html/v2/Outils/Formation/Docs/";
		$CheminRecrutement="/var/www/html/Outils/Recrutement/Documents/";
	}
	$CheminOuvrirUpload="https://extranet.aaa-aero.com/Upload/";

 	// Connexion et sélection de la base
	if($_SERVER['SERVER_NAME']=="127.0.0.1"){
		$bdd=mysqli_connect("localhost:3306", "root", "","aaa_extranet");
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
?>
