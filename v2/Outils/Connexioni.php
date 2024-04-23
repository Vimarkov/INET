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
	if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost")
	{
		$CheminUpload="../../Upload/";
		$CheminFicLIRH="LIRH_fichiers/";
		$CheminQualite="http://".$_SERVER['SERVER_NAME']."/Qualite/";
		$CheminFormation="Docs/";
		$CheminRecrutement="Documents/";
		$CheminProduitsPerissables="ProduitsPerissables/";
		$CheminAffichageObligatoire="AffichageObligatoire/";
		$CheminOnBoarding="http://".$_SERVER['SERVER_NAME']."/v2/Outils/Onboarding/Contenu/";
		$CheminImage="http://".$_SERVER['SERVER_NAME']."/v2/Images";
		$CheminOuvrirUpload="https://extranet.aaa-aero.com/Upload/";
	}
	elseif($_SERVER['SERVER_NAME']=="192.168.20.3")
	{
		$CheminUpload="../../Upload/";
		$CheminFicLIRH="LIRH_fichiers/";
		$CheminQualite="http://".$_SERVER['SERVER_NAME']."/Qualite/";
		$CheminFormation="Outils/Formation/Docs/";
		$CheminRecrutement="Documents/";
		$CheminProduitsPerissables="ProduitsPerissables/";
		$CheminAffichageObligatoire="AffichageObligatoire/";
		$CheminOnBoarding="Contenu/";
		$CheminImage="http://".$_SERVER['SERVER_NAME']."/v2/Images";
		$CheminOuvrirUpload="https://extranet.aaa-aero.com/Upload/";
	}
	elseif($_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43")
	{
		$CheminUpload="/var/www/html/Upload/";
		$CheminFicLIRH="/var/www/html/Outils/LIRH/LIRH_fichiers/";
		$CheminQualite="/var/www/html/Qualite/";
		$CheminFormation="/var/www/html/v2/Outils/Formation/Docs/";
		$CheminRecrutement="/v2/Outils/TalentBoost/Documents/";
		$CheminProduitsPerissables="/v2/Outils/Competences/ProduitsPerissables/";
		$CheminAffichageObligatoire="/v2/Outils/AffichageObligatoire/";
		$CheminOnBoarding="/v2/Outils/Onboarding/Contenu/";
		$CheminImage="http://".$_SERVER['SERVER_NAME'].":443/v2/Images";
		
		$CheminOuvrirUpload="http://".$_SERVER['SERVER_NAME'].":443/Upload";
	}
	else
	{
		$CheminUpload="/var/www/html/Upload/";
		$CheminFicLIRH="/var/www/html/Outils/LIRH/LIRH_fichiers/";
		$CheminQualite="/var/www/html/Qualite/";
		$CheminFormation="/var/www/html/v2/Outils/Formation/Docs/";
		$CheminRecrutement="/v2/Outils/TalentBoost/Documents/";
		$CheminProduitsPerissables="/v2/Outils/Competences/ProduitsPerissables/";
		$CheminAffichageObligatoire="/v2/Outils/AffichageObligatoire/";
		$CheminOnBoarding="/v2/Outils/Onboarding/Contenu/";
		$CheminImage="https://".$_SERVER['SERVER_NAME']."/v2/Images";
		
		$CheminOuvrirUpload="https://extranet.aaa-aero.com/Upload/";
	}
	
	
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
	 
	if ($bdd->connect_errno) {
		echo "Echec lors de la connexion à MySQL : (" . $bdd->connect_errno . ") " . $bdd->connect_error;
	}
	if(!$bdd){
		echo "<body onload='window.top.location.href=\"".$chemin."/index.php?Cnx=BDD\";'>";
	}
	else{
		echo "<body>";
	}
	
?>
