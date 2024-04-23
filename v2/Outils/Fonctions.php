<?php
global $NavigOk;

$NavigOk=false;

if(isset($_SESSION["Langue"])){$LangueAffichage=$_SESSION['Langue'];}
else{$LangueAffichage="FR";}

if(isset($_SESSION['Id_Personne'])){$IdPersonneConnectee=$_SESSION['Id_Personne'];}
else{$IdPersonneConnectee=0;}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

//Verifier si Google CHROME (true) ou Autre (false)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 

if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')){$NavigOk = true;}
elseif (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
elseif (preg_match("/Firefox/i", $_SERVER["HTTP_USER_AGENT"], $version))
{
	$ub="Firefox";
	if(!preg_match_all('#(?<browser>'.join('|', array('Version', $ub, 'other')).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#', $_SERVER["HTTP_USER_AGENT"], $matches)) { }
	$i = count($matches['browser']);
	
	if($i !== 1)
	{
		if(strripos($_SERVER["HTTP_USER_AGENT"], "Version") < strripos($_SERVER["HTTP_USER_AGENT"], $ub)){$version = $matches['version'][0];}
		else{$version = $matches['version'][1];}
	}
	else{$version = $matches['version'][0];}
	
	if($version == null){$version = "";}
	
	if($version>="57.0"){$NavigOk = true;}
	else{$NavigOk = false;}
} 	
else
{
	$NavigOk = false;
}
//------------------------------------------------

function TrsfDate($Date)
{
	global $NavigOk;
	
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
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
	global $NavigOk;
	
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
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
	global $NavigOk;
	
    if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
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

function AfficheDateJJ_MM($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
		$tabDateTransfert = explode('-', $Date);
		$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
		$dateReq = date("d/m", $timestampTransfert);
	}
	
	return $dateReq;
}

function AfficheDateMM_AAAA($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
		$tabDateTransfert = explode('-', $Date);
		$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
		$dateReq = date("m/Y", $timestampTransfert);
	}
	
	return $dateReq;
}

function AfficheDateJJ_MM_AAAA_HH_MM($Date)
{
	if($Date==Null || $Date=='' || substr($Date,0,10)<='0001-01-01' || substr($Date,0,10)=='0001-01-01'){$dateReq="";}
	else
	{
		$tabDateTransfert = explode('-', substr($Date,0,10));
		$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
		$dateReq = date("d/m/Y", $timestampTransfert)." ".substr($Date,11,5);
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
	global $NavigOk;
	
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
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
	global $NavigOk;
	
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
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
	mkdir($directory,$droits,true);
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
	return substr($Libelle_Prestation,0,7);
}

function estWE($timestamp)
{
	$EstWE = 0;
	// Initialisation de la date de début
	$jour = intval(date("d", $timestamp));
	$mois = intval(date("m", $timestamp));
	$annee = intval(date("Y", $timestamp));
	// Calul des samedis et dimanches
 
	$jour_semaine = date('w', $timestamp);
	if($jour_semaine == 6 || $jour_semaine == 0){
		$EstWE=1;
	}
	return $EstWE;
}//Fin de la fonction

function TrsfDateExcel_($Date)
{
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
		$tabDateTransfert = explode('/', $Date);
		$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
		$dateReq = date("Y-m-d", $timestampTransfert);
	}
	
	return $dateReq;
}

/**
 * Droits_PersonneConnectee_PageExtranet
 *
 * Cette fonction permet de renvoyer les droits de la personne connectée
 *
 * @param 	string 		$Page		Page dans la table accès
 * @param 	string 		$Dossier1	1er dossier dans la table accès
 * @param 	string 		$Dossier2	2ème dossier dans la table accès
 * @return 	string		Droits de la personne connectée pour les paramètres passés
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Droits_PersonneConnectee_PageExtranet($Page, $Dossier1, $Dossier2)
{
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteResponsableFormation;
	global $IdPosteResponsableHSE;
	global $IdPosteResponsableQualite;
	global $IdPosteProcedeSpecial;
	global $IdPosteAssistantFormationInterne;
	global $IdPosteAssistantFormationExterne;
	global $IdPosteAssistantFormationTC;
	global $IdPosteFormateur;
	global $IdPosteAssistantRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteGestionnaireMGX;
	global $IdPosteResponsableMGX;
	global $IdPosteInformatique;
	global $IdPosteAssistantAdministratif;
	global $IdPosteOperateurSaisieRH;
	global $IdPosteControleGestion;
	global $IdPosteResponsableRecrutement;
	global $IdPosteRecrutement;
	global $IdPosteGestionnaireBadges;
	global $IdPosteDirection;
	global $IdPosteAideRH;
	global $IdPosteDirectionOperation;
	global $IdPosteChargeMissionOperation;
	global $IdPosteCoordinateurSecurite;
	global $IdPosteMembreCODIR;
	global $IdPosteDivision;
	global $IdPosteReferentSurveillance;
	global $IdPosteAdministrateur;
	global $IdPosteReferentQualiteProcedesSpeciaux;
	global $IdPosteInnovation;
	global $IdPosteInnoLab;
	global $IdPosteTrainingModules;

	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteResponsableOperation;
	global $IdPosteReferentQualiteProduit;
	global $IdPosteReferentQualiteSysteme;
	global $IdPosteConsultation;
	global $IdPosteAssistantePrestation;
	global $IdPosteMagasinier;
	global $IdPosteSaisiePrestationRECORD;

	global $IdTypeFormationEprouvette;
	global $IdTypeFormationTC;
	global $IdTypeFormationInterne;
	global $IdTypeFormationExterne;

	global $IdPosteEluCFDT;
	global $IdPosteEluCFE_CGC;
	global $IdPosteEluCGT;
	global $IdPosteEluFO;
	global $IdPosteSecretaireCSE;
	global $IdCommissionCSSCT;
	global $IdCommissionEconomique;
	global $IdCommissionFEL;
	global $IdCommissionHandicap;
	global $IdCommissionConventionCollective;
	global $IdPosteAssistantQualite;
	
	$Droits="Aucun";
	
	if(isset($_SESSION['Log']))
	{
		$resultDroits=mysqli_query($bdd,"SELECT Droits FROM new_acces WHERE Login='".$_SESSION['Log']."' AND Page='".$Page."' AND Dossier1='".$Dossier1."' AND Dossier2='".$Dossier2."'");
		$nbDroits=mysqli_num_rows($resultDroits);
		$rowDroits=mysqli_fetch_array($resultDroits);
		if($nbDroits>0){$Droits=$rowDroits['Droits'];}
	}
	
	if($Page=="cedpchsct"){
		if($Dossier1=="ExpressionSyndicale"){
			$Droits="Lecture";
			if($Dossier2=="CFDT"){
				if(DroitsFormation1Plateforme(17,array($IdPosteEluCFDT))){$Droits="Administrateur";}
			}
			elseif($Dossier2=="CFE-CGC"){
				if(DroitsFormation1Plateforme(17,array($IdPosteEluCFE_CGC))){$Droits="Administrateur";}
			}
			elseif($Dossier2=="CGT"){
				if(DroitsFormation1Plateforme(17,array($IdPosteEluCGT))){$Droits="Administrateur";}
			} 
			elseif($Dossier2=="FO"){
				if(DroitsFormation1Plateforme(17,array($IdPosteEluFO))){$Droits="Administrateur";}
			}
		}
		elseif($Dossier1=="Commissions"){
			$Droits="Lecture";
			if($Dossier2=="CommissionCSSCT"){
				if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdCommissionCSSCT))){$Droits="Administrateur";}
			}
			elseif($Dossier2=="CommissionEconomique"){
				if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdCommissionEconomique))){$Droits="Administrateur";}
			}
			elseif($Dossier2=="CommissionFEL"){
				if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdCommissionFEL))){$Droits="Administrateur";}
			} 
			elseif($Dossier2=="CommissionHandicap"){
				if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdCommissionHandicap))){$Droits="Administrateur";}
			}
			elseif($Dossier2=="CommissionConventionCollective"){
				if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdCommissionConventionCollective))){$Droits="Administrateur";}
			}
		}
		elseif($Dossier1=="CSE"){
			$Droits="Lecture";
			if(DroitsFormation1Plateforme(17,array($IdPosteSecretaireCSE))){$Droits="Administrateur";}
		}
		elseif($Dossier1=="BDESE"){
			if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH))){$Droits="Administrateur";}
		}
	}
	elseif($Page=="missionhandicap"){
		$Droits="Lecture";
		if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH))){$Droits="Administrateur";}
	}
	elseif($Page=="rh"){
		$Droits="Lecture";
		if($Dossier1=="Communications" || $Dossier1=="ConventionsCollectives" 
		|| $Dossier1=="AccordEntreprise" || $Dossier1=="ConventionCollective" || $Dossier1=="Formulaires"
		|| $Dossier1=="PEE" || $Dossier1=="ReglementInterieur"){
			if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH))){$Droits="Administrateur";}
		}
	}
	elseif($Page=="performanceindustrielle"){
		$Droits="Lecture";
		if(DroitsFormation1Plateforme(17,array($IdPosteDirectionOperation,$IdPosteChargeMissionOperation))){$Droits="Administrateur";}
	}  
	elseif($Page=="insitu"){
		if($Dossier1=="MGX"){
			if(DroitsFormation1Plateforme(1,array($IdPosteResponsableMGX))){$Droits="Administrateur";}
		}
		elseif($Dossier1=="Ouest"){
			// 17 = Siège social et 10 = Region Ouest
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
				|| DroitsFormation1Plateforme(10,array($IdPosteResponsableQualite))
			)
			{
				$Droits="Administrateur";
			}
			elseif(DroitsFormation1Plateforme(10,array($IdPosteFormateur,$IdPosteResponsablePlateforme))
				|| DroitsFormationPrestations(array(10),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteReferentQualiteProduit))
			)
			{
				$Droits="Ecriture";
			}
		}
		elseif($Dossier1=="Nord"){
			// 17 = Siège social et 9 = Region Nord
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
				|| DroitsFormation1Plateforme(9,array($IdPosteResponsableQualite))
			)
			{
				$Droits="Administrateur";
			}
		}
		elseif($Dossier1=="SudEst"){
			if($Dossier2=="RH"){
				// 17 = Siège social et 19 = Sud Est
				if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
				|| DroitsFormation1Plateforme(19,array($IdPosteResponsableRH,$IdPosteResponsablePlateforme,$IdPosteResponsableQualite))
				)
				{
					$Droits="Administrateur";
				}
			}
			elseif($Dossier2=="HSE"){
				// 17 = Siège social et 19 = Sud Est
				if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableHSE))
				|| DroitsFormation1Plateforme(19,array($IdPosteResponsableRH,$IdPosteResponsablePlateforme,$IdPosteResponsableQualite,$IdPosteResponsableHSE))
				)
				{
					$Droits="Administrateur";
				}
			}
			elseif($Dossier2=="Qualite"){
				// 17 = Siège social et 19 = Sud Est
				if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
				|| DroitsFormation1Plateforme(19,array($IdPosteResponsableQualite,$IdPosteResponsablePlateforme))
				)
				{
					$Droits="Administrateur";
				}
			}
			elseif($Dossier2=="Pdp"){
				// 17 = Siège social et 19 = Sud Est
				if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableHSE))
				|| DroitsFormation1Plateforme(19,array($IdPosteResponsableQualite,$IdPosteResponsablePlateforme,$IdPosteResponsableHSE))
				|| DroitsFormationPrestations(array(19),array($IdPosteResponsableProjet))
				)
				{
					$Droits="Administrateur";
				}
			}
			elseif($Dossier2=="PlanningFormation"){
				// 17 = Siège social et 19 = Sud Est
				if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
				|| DroitsFormation1Plateforme(19,array($IdPosteResponsableQualite,$IdPosteResponsablePlateforme,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif))
				)
				{
					$Droits="Administrateur";
				}
			}
		}
		elseif($Dossier1=="Qualite"){
			// 17 = Siège social et 1 = TLS
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteAssistantQualite))
				|| DroitsFormation1Plateforme(1,array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteFormateur))
				|| DroitsFormationPrestations(array(1),array($IdPosteReferentQualiteProduit))
			)
			{
				$Droits="Administrateur";
			}
		}
		elseif($Dossier1=="CommBureauSites"){
			// 17 = Siège social et 1 = TLS
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
				|| DroitsFormation1Plateforme(1,array($IdPosteResponsableRH,$IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme))
				|| DroitsFormationPrestations(array(1),array($IdPosteReferentQualiteProduit))
			)
			{
				$Droits="Administrateur";
			}
		}
	} 
	elseif($Page=="qualite"){
		$Droits="Lecture";
		if($Dossier1=="Normes" || $Dossier1=="Certificats"){
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))
			)
			{
				$Droits="Administrateur";
			}
		}
		elseif($Dossier1=="MatricesAAA" || $Dossier1=="multiplateforme" || $Dossier1=="REX"){
			$Droits="";
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur))
			|| DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteReferentQualiteProcedesSpeciaux))
			|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
			)
			{
				$Droits="Administrateur";
			}
		}
		else{
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur))
			|| DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))
			)
			{
				//Enlever QS quand PCARRERE aura terminer le nettoyage
				$Droits="Administrateur";
			}
		}
	}
	elseif($Page=="communication"){
		if($Dossier1=="Innovation"){
			$Droits="";
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteInnovation))
			)
			{
				$Droits="Administrateur";
			}
		}
		if($Dossier1=="FormationInterne"){
			$Droits="";
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableFormation))
			)
			{
				$Droits="Administrateur";
			}
		}
		if($Dossier1=="TrainingModulesDRAFTSexchange"){
			$Droits="";
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur)) || DroitsFormation1Plateforme(-1,array($IdPosteTrainingModules))
			)
			{
				$Droits="Administrateur";
			}
		}
		if($Dossier1=="InnoLab-MarketplaceImpression3D"){
			$Droits="";
			if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur)) || DroitsFormation1Plateforme(-1,array($IdPosteInnoLab))
			)
			{
				$Droits="Administrateur";
			}
		}
	}
	elseif($Page=="news"){
		$Droits="Lecture";
	}

	return $Droits;
}

/**
 * EstPresent_HierarchiePrestation
 *
 * Cette fonction permet de savoir si la personne connectée est présente dans la hiérarchie du personnel pour les prestations
 *
 * @return 	boolean		Vrai ou foux en fonction de la présence dans la hiérarchie du personnel pour les prestations
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function EstPresent_HierarchiePrestation()
{
	global $bdd;
	
	$DroitsModifPrestation=false;
	
	if(isset($_SESSION['Id_Personne']))
	{
		$resultHierarchie=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']." ORDER BY Id_Poste DESC");
		$nbHierarchie=mysqli_num_rows($resultHierarchie);
		if($nbHierarchie>0){$DroitsModifPrestation=true;}
	}
	
	return $DroitsModifPrestation;
}

global $TableauSousDossiers;
$TableauSousDossiers=array();
array_push($TableauSousDossiers,"canada|ECMECalibration|Contrat");
array_push($TableauSousDossiers,"canada|ECMECalibration|FicheVie");
array_push($TableauSousDossiers,"canada|ECMECalibration|NormeCalibration");
array_push($TableauSousDossiers,"canada|ECMECalibration|SuiviExpiration");
array_push($TableauSousDossiers,"canada|ECMECalibration|RapportEtalonnage");
array_push($TableauSousDossiers,"canada|ECMECalibration|RapportNonConformites");
array_push($TableauSousDossiers,"canada|Reporting|M01");
array_push($TableauSousDossiers,"canada|Reporting|M02");
array_push($TableauSousDossiers,"canada|Reporting|R01");
array_push($TableauSousDossiers,"canada|Reporting|R03");
array_push($TableauSousDossiers,"canada|Reporting|R04");
array_push($TableauSousDossiers,"canada|Reporting|S02");
array_push($TableauSousDossiers,"canada|Reporting|S03");
array_push($TableauSousDossiers,"canada|Training|EBTT");
array_push($TableauSousDossiers,"canada|Training|EFTT");
array_push($TableauSousDossiers,"canada|Training|Planning");
array_push($TableauSousDossiers,"canada|Training|SBTT");
array_push($TableauSousDossiers,"canada|Training|SFTT");
array_push($TableauSousDossiers,"canada|Training|Training");
array_push($TableauSousDossiers,"canada|QUALITE_OPEX|DQ506"); //Attention correspond a D-0601
array_push($TableauSousDossiers,"canada|QUALITE_OPEX|Ecme");
array_push($TableauSousDossiers,"canada|QUALITE_OPEX|SpecificDocumentation");
array_push($TableauSousDossiers,"canada|QUALITE_OPEX|Audit");
array_push($TableauSousDossiers,"canada|QUALITE_OPEX|FormationSMQ");
array_push($TableauSousDossiers,"canada|BU_Ontario|PolyvalenceTables");
array_push($TableauSousDossiers,"canada|BU_Ontario|OTD_OQD");
array_push($TableauSousDossiers,"canada|BU_Ontario|CustomerSatisfaction");
array_push($TableauSousDossiers,"canada|BU_Ontario|QualityPCS");
array_push($TableauSousDossiers,"canada|BU_Ontario|Other");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-MIR_STL_G7_OSW");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PIT_BA_EWIS");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PL3_BAMX_CHL");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PL3_MHI_CHL_STR");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PL8_AER_A220");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PL8_AIR_A220_MET");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PL8_BAMX_CRJ");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PL8_CSALP_A220_AVR");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PL8_ITT_A220");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PL8_LAT_CRJ");
array_push($TableauSousDossiers,"canada|Operations|QUEBEC-PL8_MIRABEL");
array_push($TableauSousDossiers,"canada|Operations|AnalyseRisque");
array_push($TableauSousDossiers,"canada|RECRUTEMENT|");
array_push($TableauSousDossiers,"canada|EXPERIENCE_EMPLOYE|HRDocumentation");
array_push($TableauSousDossiers,"canada|EXPERIENCE_EMPLOYE|KitNouveauGestionnaire");
array_push($TableauSousDossiers,"canada|HSE|Comite");
array_push($TableauSousDossiers,"canada|HSE|Accident");
array_push($TableauSousDossiers,"canada|GestionDocumentaire|DocApp");
array_push($TableauSousDossiers,"canada|GestionDocumentaire|GestionDocumentaire");

global $TableauDossiersAutorisesATous;
$TableauDossiersAutorisesATous=array();
?>