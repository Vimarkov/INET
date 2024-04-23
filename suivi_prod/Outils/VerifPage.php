<?php
	session_start();
	session_cache_limiter('private');
	session_cache_expire(30);	//Initialement à 480
	if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" 
	|| $_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="frcodc0001" 
	|| $_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
		$chemin="http://".$_SERVER['SERVER_NAME']."/suivi_prod";
	}
	else{
		$chemin="https://extranet.aaa-aero.com/suivi_prod";
	}
	
	if($_SESSION['LogSP']=="")
		{echo "<body onload='window.top.location.href=\"".$chemin."/index.php\";'>";}
?>