<?php
function TrsfDate($Date)
{
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		elseif (preg_match("/Firefox/i", $_SERVER["HTTP_USER_AGENT"], $version)){
			$ub="Firefox";
			if(!preg_match_all('#(?<browser>'.join('|', array('Version', $ub, 'other')).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#', $_SERVER["HTTP_USER_AGENT"], $matches)) { }
			$i = count($matches['browser']);
			if($i !== 1) {
				if(strripos($_SERVER["HTTP_USER_AGENT"], "Version") < strripos($_SERVER["HTTP_USER_AGENT"], $ub)) {
					$version = $matches['version'][0];
				} else {
					$version = $matches['version'][1];
				}
			} else {
				$version = $matches['version'][0];
			}
			if($version == null) {
				$version = "";
			}
			if($version>="57.0"){
				$NavigOk = true;
			}
			else{
				$NavigOk = false;
			}
		} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y/m/d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('/', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateReq = date("Y/m/d", $timestampTransfert);
		}
	}
	
	return $dateReq;
}


function TrsfDate_($Date)
{
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;}
		elseif (preg_match("/Firefox/i", $_SERVER["HTTP_USER_AGENT"], $version)){
			$ub="Firefox";
			if(!preg_match_all('#(?<browser>'.join('|', array('Version', $ub, 'other')).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#', $_SERVER["HTTP_USER_AGENT"], $matches)) { }
			$i = count($matches['browser']);
			if($i !== 1) {
				if(strripos($_SERVER["HTTP_USER_AGENT"], "Version") < strripos($_SERVER["HTTP_USER_AGENT"], $ub)) {
					$version = $matches['version'][0];
				} else {
					$version = $matches['version'][1];
				}
			} else {
				$version = $matches['version'][0];
			}
			if($version == null) {
				$version = "";
			}
			if($version>="57.0"){
				$NavigOk = true;
			}
			else{
				$NavigOk = false;
			}
		} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('/', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

function AfficheDateFR($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
		
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		elseif (preg_match("/Firefox/i", $_SERVER["HTTP_USER_AGENT"], $version)){
			$ub="Firefox";
			if(!preg_match_all('#(?<browser>'.join('|', array('Version', $ub, 'other')).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#', $_SERVER["HTTP_USER_AGENT"], $matches)) { }
			$i = count($matches['browser']);
			if($i !== 1) {
				if(strripos($_SERVER["HTTP_USER_AGENT"], "Version") < strripos($_SERVER["HTTP_USER_AGENT"], $ub)) {
					$version = $matches['version'][0];
				} else {
					$version = $matches['version'][1];
				}
			} else {
				$version = $matches['version'][0];
			}
			if($version == null) {
				$version = "";
			}
			if($version>="57.0"){
				$NavigOk = true;
			}
			else{
				$NavigOk = false;
			}
		} 
		else {$NavigOk = false;}
		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("d/m/Y", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

function AfficheDateJJ_MM_AAAA($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
		$tabDateTransfert = explode('-', $Date);
		$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
		$dateReq = date("d/m/Y", $timestampTransfert);
	}
	
	return $dateReq;
}

function Ecrire_Code_JS_Init_Date()
{
	echo '<!-- Script DATE  -->';
	echo '<script>';
	echo 'var initDatepicker = function() {';
	echo '$(\'input[type=date]\').each(function() {';
	echo 'var $input = $(this);';
	echo '$input.datepicker({minDate: $input.attr(\'min\'),maxDate: $input.attr(\'max\'),dateFormat: \'dd/mm/yy\'});';  
	echo '});';  
	echo '};';
	echo 'if(!Modernizr.inputtypes.date){$(document).ready(initDatepicker);};';
	echo '</script>';
	
	echo '<!-- Piwik -->';
	echo '<script type="text/javascript">';
	echo '  var _paq = _paq || [];';
	echo '  _paq.push([\'trackPageView\']);';
	echo '  _paq.push([\'enableLinkTracking\']);';
	echo '  (function() {';
	echo '	var u=(("https:" == document.location.protocol) ? "https" : "http") + "://127.0.0.1/piwik/";';
	echo '	_paq.push([\'setTrackerUrl\', u+\'piwik.php\']);';
	echo '	_paq.push([\'setSiteId\', 1]);';
	echo '	var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0]; g.type=\'text/javascript\';';
	echo '	g.defer=true; g.async=true; g.src=u+\'piwik.js\'; s.parentNode.insertBefore(g,s);';
	echo '  })();';
	echo '</script>';
	echo '<noscript><p><img src="http://127.0.0.1/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>';
	echo '<!-- End Piwik Code -->';
}

function Ecrire_Code_JS_Init_DateFACTU()
{
	echo '<!-- Script DATE  -->';
	echo '<script>';
	echo 'var initDatepicker = function() {';
	echo '$(\'input[type=date]\').each(function() {';
	echo 'var $input = $(this);';
	echo '$input.datepicker({minDate: $input.attr(\'min\'),maxDate: $input.attr(\'max\'),dateFormat: \'dd-mm-yy\'});';  
	echo '});';  
	echo '};';
	echo 'if(!Modernizr.inputtypes.date){$(document).ready(initDatepicker);};';
	echo '</script>';
}

function TrsfDateFACTU($Date)
{
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		elseif (preg_match("/Firefox/i", $_SERVER["HTTP_USER_AGENT"], $version)){
			$ub="Firefox";
			if(!preg_match_all('#(?<browser>'.join('|', array('Version', $ub, 'other')).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#', $_SERVER["HTTP_USER_AGENT"], $matches)) { }
			$i = count($matches['browser']);
			if($i !== 1) {
				if(strripos($_SERVER["HTTP_USER_AGENT"], "Version") < strripos($_SERVER["HTTP_USER_AGENT"], $ub)) {
					$version = $matches['version'][0];
				} else {
					$version = $matches['version'][1];
				}
			} else {
				$version = $matches['version'][0];
			}
			if($version == null) {
				$version = "";
			}
			if($version>="57.0"){
				$NavigOk = true;
			}
			else{
				$NavigOk = false;
			}
		} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y/m/d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateReq = date("Y/m/d", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

function AfficheDateFRFACTU($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		elseif (preg_match("/Firefox/i", $_SERVER["HTTP_USER_AGENT"], $version)){
			$ub="Firefox";
			if(!preg_match_all('#(?<browser>'.join('|', array('Version', $ub, 'other')).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#', $_SERVER["HTTP_USER_AGENT"], $matches)) { }
			$i = count($matches['browser']);
			if($i !== 1) {
				if(strripos($_SERVER["HTTP_USER_AGENT"], "Version") < strripos($_SERVER["HTTP_USER_AGENT"], $ub)) {
					$version = $matches['version'][0];
				} else {
					$version = $matches['version'][1];
				}
			} else {
				$version = $matches['version'][0];
			}
			if($version == null) {
				$version = "";
			}
			if($version>="57.0"){
				$NavigOk = true;
			}
			else{
				$NavigOk = false;
			}
		} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("d-m-Y", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

function jour_ferie($timestamp)
{
	$EstFerie = 0;
	// Initialisation de la date de début
	$jour = intval(date("d", $timestamp));
	$mois = intval(date("m", $timestamp));
	$annee = intval(date("Y", $timestamp));
	// Calul des samedis et dimanches
 
	$jour_semaine = date('w', $timestamp);
	if($jour_semaine == 0 ||$jour_semaine == 6)
	{
		$EstFerie = 1;// Dimanche (0), Samedi (6)
	}
 
	if($jour_semaine == 1||$jour_semaine == 2||$jour_semaine == 3||$jour_semaine == 4||$jour_semaine == 5){
		// Définition des dates fériées fixes
		if($jour == 1 && $mois == 1) $EstFerie = 1; // 1er janvier
		if($jour == 1 && $mois == 5) $EstFerie = 1; // 1er mai
		if($jour == 8 && $mois == 5) $EstFerie = 1; // 8 mai
		if($jour == 14 && $mois == 7) $EstFerie = 1; // 14 juillet
		if($jour == 15 && $mois == 8 ) $EstFerie = 1; // 15 aout
		if($jour == 1 && $mois == 11) $EstFerie = 1; // 1 novembre
		if($jour == 11 && $mois == 11) $EstFerie = 1; // 11 novembre
		if($jour == 25 && $mois == 12) $EstFerie = 1; // 25 décembre
		// Calcul du jour de pâques
		//$date_paques = easter_date($annee);
	 
		$a = $annee % 4;
		$b = $annee % 7;
		$c = $annee % 19;
		$m = 24;
		$n = 5;
		$d = (19 * $c + $m ) % 30;
		$e = (2 * $a + 4 * $b + 6 * $d + $n) % 7;

		$easterdate = 22 + $d + $e;

		if ($easterdate > 31)
		{
			$jour_paques = $d + $e - 9;
			$mois_paques = 4;
		}
		else
		{
			$jour_paques = 22 + $d + $e;
			$mois_paques = 3;
		}

		if ($d == 29 && $e == 6)
		{
			$jour_paques = 10;
			$mois_paques = 04;
		}
		elseif ($d == 28 && $e == 6)
		{
			$jour_paques = 18;
			$mois_paques = 04;
		}
		//$jour_paques = date("d", $date_paques) + 1 ;
		//$mois_paques = date("m", $date_paques);
		if($jour_paques == $jour && $mois_paques == $mois) $EstFerie = 1;
		// Pâques
		$date_paques = mktime(0, 0, 0, $mois_paques, $jour_paques, $annee);
		// Calcul du jour de l ascension (38 jours après Paques)
		$date_ascension = mktime(date("H", $date_paques),
		date("i", $date_paques),
		date("s", $date_paques),
		date("m", $date_paques),
		date("d", $date_paques) + 39,
		date("Y", $date_paques)
		);
		$jour_ascension = date("d", $date_ascension);
		$mois_ascension = date("m", $date_ascension);
		if($jour_ascension == $jour && $mois_ascension == $mois) $EstFerie = 1;
		//Ascension

		// Calcul de Pentecôte (11 jours après Paques)
		$date_pentecote = mktime(date("H", $date_ascension),
		date("i", $date_ascension),
		date("s", $date_ascension),
		date("m", $date_ascension),
		date("d", $date_ascension) + 11,
		date("Y", $date_ascension)
		);
		$jour_pentecote = date("d", $date_pentecote);
		$mois_pentecote = date("m", $date_pentecote);
		if($jour_pentecote == $jour && $mois_pentecote == $mois) $EstFerie = 1;
		//Pentecote
	}
	return $EstFerie;
}//Fin de la fonction

function jour_ferieV2($timestamp)
{
	$EstFerie = 0;
	// Initialisation de la date de début
	$jour = intval(date("d", $timestamp));
	$mois = intval(date("m", $timestamp));
	$annee = intval(date("Y", $timestamp));
	// Calul des samedis et dimanches
 
	$jour_semaine = date('w', $timestamp);
	if($jour_semaine == 1||$jour_semaine == 2||$jour_semaine == 3||$jour_semaine == 4||$jour_semaine == 5){
		// Définition des dates fériées fixes
		if($jour == 1 && $mois == 1) $EstFerie = 1; // 1er janvier
		if($jour == 1 && $mois == 5) $EstFerie = 1; // 1er mai
		if($jour == 8 && $mois == 5) $EstFerie = 1; // 8 mai
		if($jour == 14 && $mois == 7) $EstFerie = 1; // 14 juillet
		if($jour == 15 && $mois == 8 ) $EstFerie = 1; // 15 aout
		if($jour == 1 && $mois == 11) $EstFerie = 1; // 1 novembre
		if($jour == 11 && $mois == 11) $EstFerie = 1; // 11 novembre
		if($jour == 25 && $mois == 12) $EstFerie = 1; // 25 décembre
		// Calcul du jour de pâques
		//$date_paques = easter_date($annee);
	 
		$a = $annee % 4;
		$b = $annee % 7;
		$c = $annee % 19;
		$m = 24;
		$n = 5;
		$d = (19 * $c + $m ) % 30;
		$e = (2 * $a + 4 * $b + 6 * $d + $n) % 7;

		$easterdate = 22 + $d + $e;

		if ($easterdate > 31)
		{
			$jour_paques = $d + $e - 9;
			$mois_paques = 4;
		}
		else
		{
			$jour_paques = 22 + $d + $e;
			$mois_paques = 3;
		}

		if ($d == 29 && $e == 6)
		{
			$jour_paques = 10;
			$mois_paques = 04;
		}
		elseif ($d == 28 && $e == 6)
		{
			$jour_paques = 18;
			$mois_paques = 04;
		}
		//$jour_paques = date("d", $date_paques) + 1 ;
		//$mois_paques = date("m", $date_paques);
		if($jour_paques == $jour && $mois_paques == $mois) $EstFerie = 1;
		// Pâques
		$date_paques = mktime(0, 0, 0, $mois_paques, $jour_paques, $annee);
		// Calcul du jour de l ascension (38 jours après Paques)
		$date_ascension = mktime(date("H", $date_paques),
		date("i", $date_paques),
		date("s", $date_paques),
		date("m", $date_paques),
		date("d", $date_paques) + 39,
		date("Y", $date_paques)
		);
		$jour_ascension = date("d", $date_ascension);
		$mois_ascension = date("m", $date_ascension);
		if($jour_ascension == $jour && $mois_ascension == $mois) $EstFerie = 1;
		//Ascension

		// Calcul de Pentecôte (11 jours après Paques)
		$date_pentecote = mktime(date("H", $date_ascension),
		date("i", $date_ascension),
		date("s", $date_ascension),
		date("m", $date_ascension),
		date("d", $date_ascension) + 11,
		date("Y", $date_ascension)
		);
		$jour_pentecote = date("d", $date_pentecote);
		$mois_pentecote = date("m", $date_pentecote);
		if($jour_pentecote == $jour && $mois_pentecote == $mois) $EstFerie = 1;
		//Pentecote
	}
	return $EstFerie;
}//Fin de la fonction

function EcrireCodeRechargerPageMere($RequeteALister,$InputReponseFenMere,$TableRadioFenMere)
{
	global $bdd;
	if($InputReponseFenMere=="Liste_Informations"){
		$CodeRecharge="";
		$resultReponses=mysqli_query($bdd,$RequeteALister);
		$RequeteALister=str_replace(", form_langue.Libelle"," ",$RequeteALister);
		$TableauChamps=explode(", ",trim(strstr(strstr($RequeteALister," ")," FROM",true)));
		$DivReponses="";
		while($rowReponses=mysqli_fetch_array($resultReponses))
		{
			$i=0;
			foreach($TableauChamps as $ValeurChamps)
			{
				$DivReponses.=addslashes($rowReponses[$i])."|";
				$i++;
			}
			$DivReponses=substr($DivReponses,0,-1)."µ";
		}
	}
	else{
		$CodeRecharge="";
		$TableauChamps=explode(",",trim(strstr(strstr($RequeteALister," ")," FROM",true)));
		$resultReponses=mysqli_query($bdd,$RequeteALister);
		$DivReponses="";
		while($rowReponses=mysqli_fetch_array($resultReponses))
		{
			$i=0;
			foreach($TableauChamps as $ValeurChamps)
			{
				$DivReponses.=addslashes($rowReponses[$i])."|";
				$i++;
			}
			$DivReponses=substr($DivReponses,0,-1)."µ";
		}
	}
	$CodeRecharge.="<script>";
	$CodeRecharge.="window.opener.document.getElementById('".$InputReponseFenMere."').value='".$DivReponses."';";
	$CodeRecharge.="RecliqueRadioPageMere('".$TableRadioFenMere."');";
	$CodeRecharge.="window.close();";
	$CodeRecharge.="</script>";
	return $CodeRecharge;
}

function mkdir_ftp($directory,$droits) {
	
	$ftpuser="ftp_user";
	$ftppass="Extranet#FTP2017";
	$ftpdir="";
	$ftpservername="extranet.aaa-aero.com";
	//$directory=str_replace("/home/k1294/","",$directory);
	
	$directory = $ftpdir . $directory;
//	$ftpConn = ftp_connect($ftpservername,21) or die("Impossible de se connecter au serveur $ftpservername");
//	if(false == ftp_login($ftpConn,$ftpuser,$ftppass)) {
//		watchdog('file system',
//				'The directory cannot be created, ftp_login() failed.',
//				array(), WATCHDOG_ERROR);
//		return FALSE;
//	}
//	//$list=split("/",$directory);
//	//$directory="";$first=true;
//	//foreach($list as $ldir){
//	//	if ($first)
//	//		$directory.="".$ldir;
//	//	else
//	//		$directory.="/".$ldir;
//	//	$first=false;
//	ftp_mkdir($ftpConn, $directory);
//	ftp_chmod($ftpConn, $droits, $directory);
//	//}
//	ftp_close($ftpConn);
	mkdir($directory,$droits);
	chmod($directory,$droits);
	return TRUE;
}

/**
 * AfficheCodePrestation
 *
 * Cette fonction permet de renvoyer le code de la prestation et non le libelle complet
 *
 * @param 	string 	$Libelle_Prestation 	Libelle de la prestation
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function AfficheCodePrestation($Libelle_Prestation)
{
	return substr($Libelle_Prestation,0,4);
}
?>