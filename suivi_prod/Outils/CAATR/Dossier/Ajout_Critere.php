<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>		
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function Recharger(){
			opener.location="Liste_Dossier.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_Dossier.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

//Verifier si Google CHROME (true) ou Autre (fale)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
else {$NavigOk = false;}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
 Ecrire_Code_JS_Init_Date(); 

 if($_POST){
	if($_POST['archive']<>"" && strpos($_SESSION['Archive2'],$_POST['archive'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('archive','".$_POST['archive']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Archive']=$_POST['archive'].$btn;
		$_SESSION['Archive2']=$_POST['archive'].";";
	}
	if($_POST['msn']<>"" && strpos($_SESSION['MSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['MSN']=$_SESSION['MSN'].$_POST['msn'].$btn;
		$_SESSION['MSN2']=$_SESSION['MSN2'].$_POST['msn'].";";
	}
	if($_POST['programme']<>"" && strpos($_SESSION['Programme2'],$_POST['programme'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('programme','".$_POST['programme']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Programme']=$_SESSION['Programme'].$_POST['programme'].$btn;
		$_SESSION['Programme2']=$_SESSION['Programme2'].$_POST['programme'].";";
	}
	if($_POST['numDossier']<>"" && strpos($_SESSION['NumDossier2'],$_POST['numDossier'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDossier','".$_POST['numDossier']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumDossier']=$_SESSION['NumDossier'].$_POST['numDossier'].$btn;
		$_SESSION['NumDossier2']=$_SESSION['NumDossier2'].$_POST['numDossier'].";";
	}
	if($_POST['numPointFolio']<>"" && strpos($_SESSION['NumPointFolio2'],$_POST['numPointFolio'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numPointFolio','".$_POST['numPointFolio']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumPointFolio']=$_SESSION['NumPointFolio'].$_POST['numPointFolio'].$btn;
		$_SESSION['NumPointFolio2']=$_SESSION['NumPointFolio2'].$_POST['numPointFolio'].";";
	}
	if($_POST['numNC']<>"" && strpos($_SESSION['NumNC2'],$_POST['numNC'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numNC','".$_POST['numNC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumNC']=$_SESSION['NumNC'].$_POST['numNC'].$btn;
		$_SESSION['NumNC2']=$_SESSION['NumNC2'].$_POST['numNC'].";";
	}
	if($_POST['numAM']<>"" && strpos($_SESSION['NumAM'],$_POST['numAM'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numAM','".$_POST['numAM']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumAM']=$_SESSION['NumAM'].$_POST['numAM'].$btn;
		$_SESSION['NumAM2']=$_SESSION['NumAM2'].$_POST['numAM'].";";
	}
	if($_POST['section']<>"" && strpos($_SESSION['Section2'],$_POST['section'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('section','".$_POST['section']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Section']=$_SESSION['Section'].$_POST['section'].$btn;
		$_SESSION['Section2']=$_SESSION['Section2'].$_POST['section'].";";
	}
	$left="_".substr($_POST['zone'],0,strpos($_POST['zone'],";"));
	if($_POST['zone']<>"" && strpos($_SESSION['Zone2'],$left.";")===false){
		$right=substr($_POST['zone'],strpos($_POST['zone'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('zone','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Zone']=$_SESSION['Zone'].$right.$btn;
		$_SESSION['Zone2']=$_SESSION['Zone2'].$left.";";
	}
	$left=substr($_POST['priorite'],0,strpos($_POST['priorite'],";"));
	if($_POST['priorite']<>"" && strpos($_SESSION['Zone2'],$left.";")===false){
		$right=substr($_POST['priorite'],strpos($_POST['priorite'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('priorite','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Priorite']=$_SESSION['Priorite'].$right.$btn;
		$_SESSION['Priorite2']=$_SESSION['Priorite2'].$left.";";
	}
	$left="_".substr($_POST['createurDossier'],0,strpos($_POST['createurDossier'],";"));
	if($_POST['createurDossier']<>"" && strpos($_SESSION['CreateurDossier2'],$left.";")===false){
		$right=substr($_POST['createurDossier'],strpos($_POST['createurDossier'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createurDossier','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CreateurDossier']=$_SESSION['CreateurDossier'].$right.$btn;
		$_SESSION['CreateurDossier2']=$_SESSION['CreateurDossier2'].$left.";";
	}
	if($_POST['imputation']<>"" && strpos($_SESSION['Imputation2'],$_POST['imputation'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputation','".$_POST['imputation']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Imputation']=$_SESSION['Imputation'].$_POST['imputation'].$btn;
		$_SESSION['Imputation2']=$_SESSION['Imputation2'].$_POST['imputation'].";";
	}
	$left="_".substr($_POST['client'],0,strpos($_POST['client'],";"));
	if($_POST['client']<>"" && strpos($_SESSION['Client2'],$left.";")===false){
		$right=substr($_POST['client'],strpos($_POST['client'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('client','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Client']=$_SESSION['Client'].$right.$btn;
		$_SESSION['Client2']=$_SESSION['Client2'].$left.";";
	}
	if($_POST['travailRealise']<>"" && strpos($_SESSION['TravailRealise2'],$_POST['travailRealise'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('travailRealise','".$_POST['travailRealise']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['TravailRealise']=$_SESSION['TravailRealise'].$_POST['travailRealise'].$btn;
		$_SESSION['TravailRealise2']=$_SESSION['TravailRealise2'].$_POST['travailRealise'].";";
	}
	if($_POST['localisation']<>"" && strpos($_SESSION['Localisation2'],$_POST['localisation'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('localisation','".$_POST['localisation']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Localisation']=$_SESSION['Localisation'].$_POST['localisation'].$btn;
		$_SESSION['Localisation2']=$_SESSION['Localisation2'].$_POST['localisation'].";";
	}
	if(isset($_POST['J;Jour']) && strpos($_SESSION['Vacation2'],"J".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','"."J"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Vacation']=$_SESSION['Vacation']."Jour".$btn;
		$_SESSION['Vacation2']=$_SESSION['Vacation2']."J".";";
	}
	if(isset($_POST['S;Soir']) && strpos($_SESSION['Vacation2'],"S".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','"."S"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Vacation']=$_SESSION['Vacation']."Soir".$btn;
		$_SESSION['Vacation2']=$_SESSION['Vacation2']."S".";";
	}
	if(isset($_POST['N;Nuit']) && strpos($_SESSION['Vacation2'],"N".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','"."N"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Vacation']=$_SESSION['Vacation']."Nuit".$btn;
		$_SESSION['Vacation2']=$_SESSION['Vacation2']."N".";";
	}
	if(isset($_POST['VSD_Jour']) && strpos($_SESSION['Vacation2'],"VSD Jour".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','"."VSD Jour"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Vacation']=$_SESSION['Vacation']."VSD Jour".$btn;
		$_SESSION['Vacation2']=$_SESSION['Vacation2']."VSD Jour".";";
	}
	if(isset($_POST['VSD_Nuit']) && strpos($_SESSION['Vacation2'],"VSD Nuit".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','"."VSD Nuit"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Vacation']=$_SESSION['Vacation']."VSD Nuit".$btn;
		$_SESSION['Vacation2']=$_SESSION['Vacation2']."VSD Nuit".";";
	}
	$left="_".substr($_POST['CE'],0,strpos($_POST['CE'],";"));
	if($_POST['CE']<>"" && strpos($_SESSION['CE2'],";".$left.";")===false){
		$right=substr($_POST['CE'],strpos($_POST['CE'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('CE','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CE']=$_SESSION['CE'].$right.$btn;
		$_SESSION['CE2']=$_SESSION['CE2'].$left.";";
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['DateDebut']=$_POST['du'].$btn;
		$_SESSION['DateDebut2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['DateFin']=$_POST['au'].$btn;
		$_SESSION['DateFin2']=$_POST['au'];
	}
	if(isset($_POST['sansDate'])){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('SansDate','')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['SansDate']="oui".$btn;
		$_SESSION['SansDate2']="oui";
	}
	
	if(isset($_POST['QJ;Jour']) && strpos($_SESSION['VacationQUALITE2'],"J".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacationQUALITE','"."J"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['VacationQUALITE']=$_SESSION['VacationQUALITE']."Jour".$btn;
		$_SESSION['VacationQUALITE2']=$_SESSION['VacationQUALITE2']."J".";";
	}
	if(isset($_POST['QS;Soir']) && strpos($_SESSION['VacationQUALITE2'],"S".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacationQUALITE','"."S"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['VacationQUALITE']=$_SESSION['VacationQUALITE']."Soir".$btn;
		$_SESSION['VacationQUALITE2']=$_SESSION['VacationQUALITE2']."S".";";
	}
	if(isset($_POST['QN;Nuit']) && strpos($_SESSION['VacationQUALITE2'],"N".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacationQUALITE','"."N"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['VacationQUALITE']=$_SESSION['VacationQUALITE']."Nuit".$btn;
		$_SESSION['VacationQUALITE2']=$_SESSION['VacationQUALITE2']."N".";";
	}
	if(isset($_POST['QVSD_Jour']) && strpos($_SESSION['VacationQUALITE2'],"VSD Jour".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacationQUALITE','"."VSD Jour"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['VacationQUALITE']=$_SESSION['VacationQUALITE']."VSD Jour".$btn;
		$_SESSION['VacationQUALITE2']=$_SESSION['VacationQUALITE2']."VSD Jour".";";
	}
	if(isset($_POST['QVSD_Nuit']) && strpos($_SESSION['VacationQUALITE2'],"VSD Nuit".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacationQUALITE','"."VSD Nuit"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['VacationQUALITE']=$_SESSION['VacationQUALITE']."VSD Nuit".$btn;
		$_SESSION['VacationQUALITE2']=$_SESSION['VacationQUALITE2']."VSD Nuit".";";
	}
	$left="_".substr($_POST['IQ'],0,strpos($_POST['IQ'],";"));
	if($_POST['IQ']<>"" && strpos($_SESSION['IQ2'],$left.";")===false){
		$right=substr($_POST['IQ'],strpos($_POST['IQ'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('IQ','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['IQ']=$_SESSION['IQ'].$right.$btn;
		$_SESSION['IQ2']=$_SESSION['IQ2'].$left.";";
	}
	if($_POST['stamp']<>"" && strpos($_SESSION['Stamp2'],$_POST['stamp'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('stamp','".$_POST['stamp']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Stamp']=$_SESSION['Stamp'].$_POST['stamp'].$btn;
		$_SESSION['Stamp2']=$_SESSION['Stamp2'].$_POST['stamp'].";";
	}
	if($_POST['duQ']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('duQ','".$_POST['duQ']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['DateDebutQUALITE']=$_POST['duQ'].$btn;
		$_SESSION['DateDebutQUALITE2']=$_POST['duQ'];
	}
	if($_POST['auQ']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('auQ','".$_POST['auQ']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['DateFinQUALITE']=$_POST['auQ'].$btn;
		$_SESSION['DateFinQUALITE2']=$_POST['auQ'];
	}
	if(isset($_POST['sansDateQ'])){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('SansDateQ','')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['SansDateQUALITE']="oui".$btn;
		$_SESSION['SansDateQUALITE2']="oui";
	}
	
	if($_POST['numDERO']<>"" && strpos($_SESSION['NumDERO2'],$_POST['numDERO'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDERO','".$_POST['numDERO']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumDERO']=$_SESSION['NumDERO'].$_POST['numDERO'].$btn;
		$_SESSION['NumDERO2']=$_SESSION['NumDERO2'].$_POST['numDERO'].";";
	}
	if($_POST['numDA']<>"" && strpos($_SESSION['NumDA2'],$_POST['numDA'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDA','".$_POST['numDA']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumDA']=$_SESSION['NumDA'].$_POST['numDA'].$btn;
		$_SESSION['NumDA2']=$_SESSION['NumDA2'].$_POST['numDA'].";";
	}
	if($_POST['numIC']<>"" && strpos($_SESSION['NumIC2'],$_POST['numIC'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numIC','".$_POST['numIC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumIC']=$_SESSION['NumIC'].$_POST['numIC'].$btn;
		$_SESSION['NumIC2']=$_SESSION['NumIC2'].$_POST['numIC'].";";
	}
	if($_POST['poste']<>"" && strpos($_SESSION['Poste2'],$_POST['poste'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('poste','".$_POST['poste']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Poste']=$_SESSION['Poste'].$_POST['poste'].$btn;
		$_SESSION['Poste2']=$_SESSION['Poste2'].$_POST['poste'].";";
	}
	$left="_".substr($_POST['createurIC'],0,strpos($_POST['createurIC'],";"));
	if($_POST['createurIC']<>"" && strpos($_SESSION['CreateurIC2'],$left.";")===false){
		$right=substr($_POST['createurIC'],strpos($_POST['createurIC'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createurIC','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CreateurIC']=$_SESSION['CreateurIC'].$right.$btn;
		$_SESSION['CreateurIC2']=$_SESSION['CreateurIC2'].$left.";";
	}
	if(isset($_POST['Prepa(vide)']) && strpos($_SESSION['StatutPrepa2'],"(vide)".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPrepa','"."(vide)"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutPrepa']=$_SESSION['StatutPrepa']."(vide)".$btn;
		$_SESSION['StatutPrepa2']=$_SESSION['StatutPrepa2']."(vide)".";";
	}
	if(isset($_POST['Lancable']) && strpos($_SESSION['StatutPrepa2'],"A lancer PROD".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPrepa','"."A lancer PROD"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutPrepa']=$_SESSION['StatutPrepa']."A lancer PROD".$btn;
		$_SESSION['StatutPrepa2']=$_SESSION['StatutPrepa2']."A lancer PROD".";";
	}
	if(isset($_POST['APlanifier']) && strpos($_SESSION['StatutPrepa2'],"A planifier".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPrepa','"."A planifier"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutPrepa']=$_SESSION['StatutPrepa']."A planifier".$btn;
		$_SESSION['StatutPrepa2']=$_SESSION['StatutPrepa2']."A planifier".";";
	}
	if(isset($_POST['(vide)']) && strpos($_SESSION['StatutIC2'],"(vide)".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."(vide)"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."(vide)".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."(vide)".";";
	}
	if(isset($_POST['ARELANCER']) && strpos($_SESSION['StatutIC2'],"A RELANCER".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."A RELANCER"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."A RELANCER".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."A RELANCER".";";
	}
	if(isset($_POST['TFS']) && strpos($_SESSION['StatutIC2'],"TFS".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."TFS"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."TFS".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."TFS".";";
	}
	if(isset($_POST['RETOURPREPA']) && strpos($_SESSION['StatutIC2'],"RETOUR PREPA".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."RETOUR PREPA"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."RETOUR PREPA".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."RETOUR PREPA".";";
	}
	if(isset($_POST['RETOURPROD']) && strpos($_SESSION['StatutIC2'],"RETOUR PROD".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."RETOUR PROD"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."RETOUR PROD".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."RETOUR PROD".";";
	}
	if(isset($_POST['TERA']) && strpos($_SESSION['StatutIC2'],"TERA".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."TERA"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."TERA".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."TERA".";";
	}
	if(isset($_POST['REWORK']) && strpos($_SESSION['StatutIC2'],"REWORK".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."REWORK"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."REWORK".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."REWORK".";";
	}
	if(isset($_POST['TVS']) && strpos($_SESSION['StatutIC2'],"TVS".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."TVS"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."TVS".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."TVS".";";
	}
	if(isset($_POST['RETQPREPA']) && strpos($_SESSION['StatutIC2'],"RETQ PREPA".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."RETQ PREPA"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."RETQ PREPA".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."RETQ PREPA".";";
	}
	if(isset($_POST['RETQPROD']) && strpos($_SESSION['StatutIC2'],"RETQ PROD".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."RETQ PROD"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."RETQ PROD".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."RETQ PROD".";";
	}
	if(isset($_POST['TERC']) && strpos($_SESSION['StatutIC2'],"TERC".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."TERC"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."TERC".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."TERC".";";
	}

	$_SESSION['Page']=0;
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="archive"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('archive','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Archive']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Archive']);
			$_SESSION['Archive2']=str_replace($_GET['valeur'].";","",$_SESSION['Archive2']);
		}
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['MSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['MSN']);
			$_SESSION['MSN2']=str_replace($_GET['valeur'].";","",$_SESSION['MSN2']);
		}
		if($_GET['critere']=="programme"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('programme','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Programme']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Programme']);
			$_SESSION['Programme2']=str_replace($_GET['valeur'].";","",$_SESSION['Programme2']);
		}
		elseif($_GET['critere']=="numPointFolio"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numPointFolio','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['NumPointFolio']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['NumPointFolio']);
			$_SESSION['NumPointFolio2']=str_replace($_GET['valeur'].";","",$_SESSION['NumPointFolio2']);
		}
		elseif($_GET['critere']=="numDossier"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDossier','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['NumDossier']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['NumDossier']);
			$_SESSION['NumDossier2']=str_replace($_GET['valeur'].";","",$_SESSION['NumDossier2']);
		}
		elseif($_GET['critere']=="numNC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numNC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['NumNC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['NumNC']);
			$_SESSION['NumNC2']=str_replace($_GET['valeur'].";","",$_SESSION['NumNC2']);
		}
		elseif($_GET['critere']=="numAM"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numAM','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['NumAM']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['NumAM']);
			$_SESSION['NumAM2']=str_replace($_GET['valeur'].";","",$_SESSION['NumAM2']);
		}
		elseif($_GET['critere']=="section"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('section','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Section']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Section']);
			$_SESSION['Section2']=str_replace($_GET['valeur'].";","",$_SESSION['Section2']);
		}
		elseif($_GET['critere']=="zone"){
			$_SESSION['Zone2']=str_replace($_GET['valeur'].";","",$_SESSION['Zone2']);
			$tab = explode(";",$_SESSION['Zone2']);
			$_SESSION['Zone']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('zone','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_olwzonedetravail WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Zone'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="priorite"){
			$_SESSION['Priorite2']=str_replace($_GET['valeur'].";","",$_SESSION['Priorite2']);
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('priorite','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			if($_GET['valeur']=="1"){$_SESSION['Priorite']=str_replace("1".$valeur,"",$_SESSION['Priorite']);}
			elseif($_GET['valeur']=="2"){$_SESSION['Priorite']=str_replace("2".$valeur,"",$_SESSION['Priorite']);}
			elseif($_GET['valeur']=="3"){$_SESSION['Priorite']=str_replace("DA".$valeur,"",$_SESSION['Priorite']);}
		}
		elseif($_GET['critere']=="createurDossier"){
			$_SESSION['CreateurDossier2']=str_replace($_GET['valeur'].";","",$_SESSION['CreateurDossier2']);
			$tab = explode(";",$_SESSION['CreateurDossier2']);
			$_SESSION['CreateurDossier']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createurDossier','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['CreateurDossier'].=$row['Nom']." ".$row['Prenom'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="imputation"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputation','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Imputation']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Imputation']);
			$_SESSION['Imputation2']=str_replace($_GET['valeur'].";","",$_SESSION['Imputation2']);
		}
		elseif($_GET['critere']=="client"){
			$_SESSION['Client2']=str_replace($_GET['valeur'].";","",$_SESSION['Client2']);
			$tab = explode(";",$_SESSION['Client2']);
			$_SESSION['Client']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('client','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_client WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Client'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="travailRealise"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('travailRealise','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['TravailRealise']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['TravailRealise']);
			$_SESSION['TravailRealise2']=str_replace($_GET['valeur'].";","",$_SESSION['TravailRealise2']);
		}
		elseif($_GET['critere']=="localisation"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('localisation','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Localisation']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Localisation']);
			$_SESSION['Localisation2']=str_replace($_GET['valeur'].";","",$_SESSION['Localisation2']);
		}
		elseif($_GET['critere']=="vacation"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			if($_GET['valeur']=="J"){$_SESSION['Vacation']=str_replace("Jour".$valeur,"",$_SESSION['Vacation']);}
			elseif($_GET['valeur']=="S"){$_SESSION['Vacation']=str_replace("Soir".$valeur,"",$_SESSION['Vacation']);}
			elseif($_GET['valeur']=="N"){$_SESSION['Vacation']=str_replace("Nuit".$valeur,"",$_SESSION['Vacation']);}
			elseif($_GET['valeur']=="VSD Jour"){$_SESSION['Vacation']=str_replace("VSD Jour".$valeur,"",$_SESSION['Vacation']);}
			elseif($_GET['valeur']=="VSD Nuit"){$_SESSION['Vacation']=str_replace("VSD Nuit".$valeur,"",$_SESSION['Vacation']);}
			$_SESSION['Vacation2']=str_replace($_GET['valeur'].";","",$_SESSION['Vacation2']);
		}
		elseif($_GET['critere']=="CE"){
			$_SESSION['CE2']=str_replace($_GET['valeur'].";","",$_SESSION['CE2']);
			$tab = explode(";",$_SESSION['CE2']);
			$_SESSION['CE']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('CE','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['CE'].=$row['Nom']." ".$row['Prenom'].$valeur;
					}
					else{
						$_SESSION['CE'].="(vide)".$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateDebut']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateDebut']);
			$_SESSION['DateDebut2']=str_replace($_GET['valeur'],"",$_SESSION['DateDebut2']);
		}
		elseif($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateFin']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateFin']);
			$_SESSION['DateFin2']=str_replace($_GET['valeur'],"",$_SESSION['DateFin2']);
		}
		elseif($_GET['critere']=="SansDate"){
			$_SESSION['SansDate']="";
			$_SESSION['SansDate2']="";
		}
		elseif($_GET['critere']=="vacationQUALITE"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacationQUALITE','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			if($_GET['valeur']=="J"){$_SESSION['VacationQUALITE']=str_replace("Jour".$valeur,"",$_SESSION['VacationQUALITE']);}
			elseif($_GET['valeur']=="S"){$_SESSION['VacationQUALITE']=str_replace("Soir".$valeur,"",$_SESSION['VacationQUALITE']);}
			elseif($_GET['valeur']=="N"){$_SESSION['VacationQUALITE']=str_replace("Nuit".$valeur,"",$_SESSION['VacationQUALITE']);}
			elseif($_GET['valeur']=="VSD Jour"){$_SESSION['VacationQUALITE']=str_replace("VSD Jour".$valeur,"",$_SESSION['VacationQUALITE']);}
			elseif($_GET['valeur']=="VSD Nuit"){$_SESSION['VacationQUALITE']=str_replace("VSD Nuit".$valeur,"",$_SESSION['VacationQUALITE']);}
			$_SESSION['VacationQUALITE2']=str_replace($_GET['valeur'].";","",$_SESSION['VacationQUALITE2']);
		}
		elseif($_GET['critere']=="IQ"){
			$_SESSION['IQ2']=str_replace($_GET['valeur'].";","",$_SESSION['IQ2']);
			$tab = explode(";",$_SESSION['IQ2']);
			$_SESSION['IQ']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('IQ','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['IQ'].=$row['Nom']." ".$row['Prenom'].$valeur;
					}
					else{
						$_SESSION['IQ'].="(vide)".$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="stamp"){
			$_SESSION['Stamp2']=str_replace($_GET['valeur'].";","",$_SESSION['Stamp2']);
			$tab = explode(";",$_SESSION['Stamp2']);
			$_SESSION['Stamp']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('stamp','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['Stamp'].=$Id.$valeur;
				}
			}
		}
		if($_GET['critere']=="duQ"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('duQ','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateDebutQUALITE']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateDebutQUALITE']);
			$_SESSION['DateDebutQUALITE2']=str_replace($_GET['valeur'],"",$_SESSION['DateDebutQUALITE2']);
		}
		if($_GET['critere']=="auQ"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('auQ','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateFinQUALITE']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateFinQUALITE']);
			$_SESSION['DateFinQUALITE2']=str_replace($_GET['valeur'],"",$_SESSION['DateFinQUALITE2']);
		}
		if($_GET['critere']=="SansDateQ"){
			$_SESSION['SansDateQUALITE']="";
			$_SESSION['SansDateQUALITE2']="";
		}
		elseif($_GET['critere']=="numDERO"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDERO','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['NumDERO']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['NumDERO']);
			$_SESSION['NumDERO2']=str_replace($_GET['valeur'].";","",$_SESSION['NumDERO2']);
		}
		elseif($_GET['critere']=="numDA"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDA','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['NumDA']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['NumDA']);
			$_SESSION['NumDA2']=str_replace($_GET['valeur'].";","",$_SESSION['NumDA2']);
		}
		elseif($_GET['critere']=="numIC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numIC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['NumIC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['NumIC']);
			$_SESSION['NumIC2']=str_replace($_GET['valeur'].";","",$_SESSION['NumIC2']);
		}
		elseif($_GET['critere']=="poste"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('poste','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Poste']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Poste']);
			$_SESSION['Poste2']=str_replace($_GET['valeur'].";","",$_SESSION['Poste2']);
		}
		elseif($_GET['critere']=="createurIC"){
			$_SESSION['CreateurIC2']=str_replace($_GET['valeur'].";","",$_SESSION['CreateurIC2']);
			$tab = explode(";",$_SESSION['CreateurIC2']);
			$_SESSION['CreateurIC']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createurIC','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['CreateurIC'].=$row['Nom']." ".$row['Prenom'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="statutIC"){
			$_SESSION['StatutIC2']=str_replace($_GET['valeur'].";","",$_SESSION['StatutIC2']);
			$tab = explode(";",$_SESSION['StatutIC2']);
			$_SESSION['StatutIC']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['StatutIC'].=$Id.$valeur;
				}
			}
		}
		elseif($_GET['critere']=="statutPrepa"){
			if($_GET['valeur']=="LanÃ§able"){
				$_SESSION['StatutPrepa2']=str_replace("A lancer PROD;","",$_SESSION['StatutPrepa2']);
			}
			else{
				$_SESSION['StatutPrepa2']=str_replace($_GET['valeur'].";","",$_SESSION['StatutPrepa2']);
			}
			$tab = explode(";",$_SESSION['StatutPrepa2']);
			$_SESSION['StatutPrepa']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPrepa','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['StatutPrepa'].=$Id.$valeur;
				}
			}
		}
		$_SESSION['Page']=0;
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_Critere.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Ajouter des critères</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="10"> &nbsp; DOSSIER
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; MSN :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="msn" size="10" value="">
			</td>
			<td>&nbsp; Programme : </td>
			<td>
				<select id="programme" name="programme">
					<option value=""></option>
					<option value="A320">A320</option>
					<option value="A330">A330</option>
					<option value="A350">A350</option>
					<option value="A380">A380</option>
					<option value="ATR">ATR</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; N° Point Folio :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numPointFolio" size="15" value="">
			</td>
			<td width=20%>
				&nbsp; N° OF/OF/Para :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numDossier" size="15" value="">
			</td>
			<td width=20%>
				&nbsp; N° NC :
			</td>
			<td>
				<input type="texte" style="text-align:center;" name="numNC" size="15" value="">
			</td>
			<td width=20%>
				&nbsp; N° AM :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numAM" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Section :
			</td>
			<td >
				<select name="section">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT SectionACP FROM sp_olwdossier WHERE Id_Prestation=79 ORDER BY SectionACP;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option name='".$row['SectionACP']."' value='".$row['SectionACP']."'>".$row['SectionACP']."</option>";
						}
					}
					?>
				</select>
			</td>
			<td width=20%>
				&nbsp; Zone :
			</td>
			<td >
				<select name="zone">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_olwdossier.Id_ZoneDeTravail AS Id, (SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail ";
					$req.="WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Libelle FROM sp_olwdossier WHERE Id_Prestation=79 ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Libelle="(vide)";
							if($row['Id']<>0){$Libelle=$row['Libelle'];}
							echo "<option name='".$row['Id']."' value='".$row['Id'].";".$Libelle."'>".$Libelle."</option>";
						}
					}
					?>
				</select>
			</td>
			<td width=20%>
				&nbsp; Priorité :
			</td>
			<td >
				<select name="priorite">
					<option value=""></option>
					<option value="1;1">1</option>
					<option value="3;DA">DA</option>
					<option value="2;2">2</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Localisation :
			</td>
			<td colspan="10">
				<input type="texte" style="text-align:center;" name="localisation" size="40" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Créateur dossier :
			</td>
			<td >
				<select name="createurDossier">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
					$req.="FROM sp_olwdossier INNER JOIN new_rh_etatcivil ON sp_olwdossier.Id_Personne=new_rh_etatcivil.Id WHERE Id_Prestation=79 ORDER BY new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Libelle="(vide)";
							if($row['Id']<>0){$Libelle=$row['Nom']." ".$row['Prenom'];}
							echo "<option name='".$row['Id']."' value='".$row['Id'].";".$Libelle."'>".$Libelle."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Imputation :
			</td>
			<td >
				<select name="imputation">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT Imputation FROM sp_olwdossier WHERE Imputation<>'' AND Id_Prestation=79 ORDER BY Imputation;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option name='".$row['Imputation']."' value='".$row['Imputation']."'>".$row['Imputation']."</option>";
						}
					}
					?>
				</select>
			</td>
			<td width=20%>
				&nbsp; Client :
			</td>
			<td >
				<select name="client">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_olwdossier.Id_Client,(SELECT Libelle FROM sp_client WHERE sp_client.Id=sp_olwdossier.Id_Client) AS Client FROM sp_olwdossier WHERE Id_Prestation=79 ORDER BY Client;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option name='".$row['Id_Client']."' value='".$row['Id_Client'].";".$row['Client']."'>".$row['Client']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Sujet du travail réalisé :
			</td>
			<td colspan="10">
				<input type="texte" style="text-align:center;" name="travailRealise" size="40" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20%>
				&nbsp; Dossiers archivés :
			</td>
			<td colspan="10">
				<select name="archive">
					<option name="" value=""></option>
					<option name="" value="Oui">Oui</option>
					<option name="" value="Non">Non</option>
				</select>
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td style="border-top:1px dotted #0077aa;" colspan="10"> &nbsp; PROD
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Vacation :
			</td>
			<td colspan="10">
				<input type="checkbox" id="J;Jour" name="J;Jour" value="J;Jour">Jour &nbsp;&nbsp;
				<input type="checkbox" id="S;Soir" name="S;Soir" value="S;Soir">Soir &nbsp;&nbsp;
				<input type="checkbox" id="N;Nuit" name="N;Nuit" value="N;Nuit">Nuit &nbsp;&nbsp;
				<input type="checkbox" id="VSD_Jour" name="VSD_Jour" value="VSD_Jour">VSD Jour &nbsp;&nbsp;
				<input type="checkbox" id="VSD_Nuit" name="VSD_Nuit" value="VSD_Nuit">VSD Nuit &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Chef d'équipe :
			</td>
			<td >
				<select name="CE">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_olwficheintervention.Id_PROD AS Id, ";
					$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_PROD) AS NomPrenom ";
					$req.="FROM sp_olwficheintervention WHERE (SELECT sp_olwdossier.Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=79 ORDER BY NomPrenom;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Libelle="(vide)";
							if($row['Id']<>0){$Libelle=$row['NomPrenom'];}
							echo "<option name='".$row['Id']."' value='".$row['Id'].";".$Libelle."'>".$Libelle."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Du :
			</td>
			<td> 
				<input type="date" style="text-align:center;" name="du" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Au :
			</td>
			<td> 
				<input type="date" style="text-align:center;" name="au" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Sans date :
			</td>
			<td> 
				<input type="checkbox" style="text-align:center;" name="sansDate" value="sansDate">
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td style="border-top:1px dotted #0077aa;" colspan="10"> &nbsp; QUALITE
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Vacation :
			</td>
			<td colspan="10">
				<input type="checkbox" id="QJ;Jour" name="QJ;Jour" value="J;Jour">Jour &nbsp;&nbsp;
				<input type="checkbox" id="QS;Soir" name="QS;Soir" value="S;Soir">Soir &nbsp;&nbsp;
				<input type="checkbox" id="QN;Nuit" name="QN;Nuit" value="N;Nuit">Nuit &nbsp;&nbsp;
				<input type="checkbox" id="QVSD_Jour" name="QVSD_Jour" value="VSD_Jour">VSD Jour &nbsp;&nbsp;
				<input type="checkbox" id="QVSD_Nuit" name="QVSD_Nuit" value="VSD_Nuit">VSD Nuit &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Inspecteur qualité :
			</td>
			<td >
				<select name="IQ">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_olwficheintervention.Id_QUALITE AS Id, ";
					$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_QUALITE) AS NomPrenom ";
					$req.="FROM sp_olwficheintervention WHERE (SELECT sp_olwdossier.Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=79 ORDER BY NomPrenom;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Libelle="(vide)";
							if($row['Id']<>0){$Libelle=$row['NomPrenom'];}
							echo "<option name='".$row['Id']."' value='".$row['Id'].";".$Libelle."'>".$Libelle."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=25%>
				&nbsp; Marque de contrôle :
			</td>
			<td >
				<select name="stamp">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT ";
					$req.="new_competences_personne_stamp.Num_Stamp ";
					$req.="FROM sp_olwficheintervention LEFT JOIN new_competences_personne_stamp ON new_competences_personne_stamp.Id_Personne=sp_olwficheintervention.Id_QUALITE ";
					$req.="WHERE Num_Stamp<>'' AND (SELECT sp_olwdossier.Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=79 ORDER BY Num_Stamp;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Libelle="(vide)";
							if($row['Id']<>0){$Libelle=$row['Num_Stamp'];}
							echo "<option name='".$row['Num_Stamp']."' value='".$row['Num_Stamp']."'>".$row['Num_Stamp']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Du :
			</td>
			<td> 
				<input type="date" style="text-align:center;" name="duQ" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Au :
			</td>
			<td>
				<input type="date" style="text-align:center;" name="auQ" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Sans date :
			</td>
			<td> 
				<input type="checkbox" style="text-align:center;" name="sansDateQ" value="sansDate">
			</td>
		</tr>
		<tr>
			<td style="border-bottom:1px dotted #0077aa;" colspan="10" height="4"></td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; N° Dérogation :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numDERO" size="15" value="">
			</td>
			<td width=20%>
				&nbsp; N° DA :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numDA" size="15" value="">
			</td>
			<td width=20%>
				&nbsp; N° IC :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numIC" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Poste :
			</td>
			<td >
				<select name="poste">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT PosteAvionACP FROM sp_olwficheintervention WHERE (SELECT sp_olwdossier.Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=79 ORDER BY PosteAvionACP;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option name='".$row['PosteAvionACP']."' value='".$row['PosteAvionACP']."'>".$row['PosteAvionACP']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Statut Prepa :
			</td>
			<td colspan="10">
			<input type="checkbox" id="Prepa(vide)" name="Prepa(vide)" value="Prepa(vide)">(vide) &nbsp;&nbsp;
			<input type="checkbox" id="Lancable" name="Lancable" value="A lancer PROD">A lancer PROD &nbsp;&nbsp;
			<input type="checkbox" id="APlanifier" name="APlanifier" value="A Planifier">A Planifier &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Créateur IC :
			</td>
			<td >
				<select name="createurIC">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
					$req.="FROM sp_olwficheintervention INNER JOIN new_rh_etatcivil ON sp_olwficheintervention.Id_Createur=new_rh_etatcivil.Id  WHERE (SELECT sp_olwdossier.Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=79 ORDER BY new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Libelle="(vide)";
							if($row['Id']<>0){$Libelle=$row['Nom']." ".$row['Prenom'];}
							echo "<option name='".$row['Id']."' value='".$row['Id'].";".$Libelle."'>".$Libelle."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Statut IC :
			</td>
			<td colspan="10">
			<input type="checkbox" id="(vide)" name="(vide)" value="(vide)">(vide) &nbsp;&nbsp;
			<input type="checkbox" id="A RELANCER" name="ARELANCER" value="A RELANCER">A RELANCER &nbsp;&nbsp;
			<input type="checkbox" id="TFS" name="TFS" value="TFS">TFS &nbsp;&nbsp;
			<input type="checkbox" id="RETOUR PREPA" name="RETOURPREPA" value="RETOUR PREPA">RETOUR PREPA &nbsp;&nbsp;
			<input type="checkbox" id="RETOUR PROD" name="RETOURPROD" value="RETOUR PROD">RETOUR PROD &nbsp;&nbsp;
			<input type="checkbox" id="TERA" name="TERA" value="TERA">TERA &nbsp;&nbsp;
			<input type="checkbox" id="REWORK" name="REWORK" value="REWORK">REWORK &nbsp;&nbsp;
			<input type="checkbox" id="TVS" name="TVS" value="TVS">TVS &nbsp;&nbsp;
			<input type="checkbox" id="RETQ PREPA" name="RETQPREPA" value="RETQ PREPA">RETQ PREPA&nbsp;&nbsp;
			<input type="checkbox" id="RETQ PROD" name="RETQPROD" value="RETQ PROD">RETQ PROD&nbsp;&nbsp;
			<input type="checkbox" id="TERC" name="TERC" value="TERC">TERC &nbsp;&nbsp;
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center" colspan="10">
				<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Ajouter">
			</td>
			
		</tr>
	</table>
	</td></tr>
</form>
</table>
</body>
</html>