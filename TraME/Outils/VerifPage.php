<?php
	if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" 
	|| $_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="frcodc0001" 
	|| $_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
		$chemin="".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME";}
	else{$chemin="https://extranet.aaa-aero.com/TraME";}
	if($_SESSION['LogTR']=="")
		{echo "<body onload='window.top.location.href=\"".$chemin."/index.php\";'>";}
?>