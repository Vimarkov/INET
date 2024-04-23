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
	if($_POST['msn']<>"" && strpos($_SESSION['MSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['MSN']=$_SESSION['MSN'].$_POST['msn'].$btn;
		$_SESSION['MSN2']=$_SESSION['MSN2'].$_POST['msn'].";";
	}
	if($_POST['numDossier']<>"" && strpos($_SESSION['NumDossier2'],$_POST['numDossier'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDossier','".$_POST['numDossier']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumDossier']=$_SESSION['NumDossier'].$_POST['numDossier'].$btn;
		$_SESSION['NumDossier2']=$_SESSION['NumDossier2'].$_POST['numDossier'].";";
	}
	if($_POST['numIC']<>"" && strpos($_SESSION['NumIC2'],$_POST['numIC'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numIC','".$_POST['numIC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumIC']=$_SESSION['NumIC'].$_POST['numIC'].$btn;
		$_SESSION['NumIC2']=$_SESSION['NumIC2'].$_POST['numIC'].";";
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
	$left="_".substr($_POST['createurDossier'],0,strpos($_POST['createurDossier'],";"));
	if($_POST['createurDossier']<>"" && strpos($_SESSION['CreateurDossier2'],$left.";")===false){
		$right=substr($_POST['createurDossier'],strpos($_POST['createurDossier'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createurDossier','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CreateurDossier']=$_SESSION['CreateurDossier'].$right.$btn;
		$_SESSION['CreateurDossier2']=$_SESSION['CreateurDossier2'].$left.";";
	}
	$left="_".substr($_POST['createurIC'],0,strpos($_POST['createurIC'],";"));
	if($_POST['createurIC']<>"" && strpos($_SESSION['CreateurIC2'],$left.";")===false){
		$right=substr($_POST['createurIC'],strpos($_POST['createurIC'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createurIC','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CreateurIC']=$_SESSION['CreateurIC'].$right.$btn;
		$_SESSION['CreateurIC2']=$_SESSION['CreateurIC2'].$left.";";
	}
	$left="_".substr($_POST['CE'],0,strpos($_POST['CE'],";"));
	if($_POST['CE']<>"" && strpos($_SESSION['CE2'],";".$left.";")===false){
		$right=substr($_POST['CE'],strpos($_POST['CE'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('CE','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CE']=$_SESSION['CE'].$right.$btn;
		$_SESSION['CE2']=$_SESSION['CE2'].$left.";";
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
	$left="_".substr($_POST['pole'],0,strpos($_POST['pole'],";"));
	if($_POST['pole']<>"" && strpos($_SESSION['Pole_FI2'],$left.";")===false){
		$right=substr($_POST['pole'],strpos($_POST['pole'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('pole','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Pole_FI']=$_SESSION['Pole_FI'].$right.$btn;
		$_SESSION['Pole_FI2']=$_SESSION['Pole_FI2'].$left.";";
	}
	if(isset($_POST['(vide)']) && strpos($_SESSION['StatutIC2'],"(vide)".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."(vide)"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."(vide)".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."(vide)".";";
	}
	if(isset($_POST['CERT']) && strpos($_SESSION['StatutIC2'],"CERT".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."CERT"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."CERT".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."CERT".";";
	}
	if(isset($_POST['QARJ']) && strpos($_SESSION['StatutIC2'],"QARJ".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."QARJ"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."QARJ".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."QARJ".";";
	}
	if(isset($_POST['REWORK']) && strpos($_SESSION['StatutIC2'],"REWORK".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."REWORK"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."REWORK".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."REWORK".";";
	}
	if(isset($_POST['TFS']) && strpos($_SESSION['StatutIC2'],"TFS".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."TFS"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."TFS".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."TFS".";";
	}
	if(isset($_POST['TVS']) && strpos($_SESSION['StatutIC2'],"TVS".";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','"."TVS"."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['StatutIC']=$_SESSION['StatutIC']."TVS".$btn;
		$_SESSION['StatutIC2']=$_SESSION['StatutIC2']."TVS".";";
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
	if($_POST['etatIC']<>"" && strpos($_SESSION['EtatIC2'],$_POST['etatIC'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('etatIC','".$_POST['etatIC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EtatIC']=$_SESSION['EtatIC'].$_POST['etatIC'].$btn;
		$_SESSION['EtatIC2']=$_SESSION['EtatIC2'].$_POST['etatIC'].";";
	}
	if($_POST['competence']<>"" && strpos($_SESSION['Competence2'],$_POST['competence'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('competence','".$_POST['competence']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Competence']=$_SESSION['Competence'].$_POST['competence'].$btn;
		$_SESSION['Competence2']=$_SESSION['Competence2'].$_POST['competence'].";";
	}
	if($_POST['pne']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('pne','".$_POST['pne']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['PNE']=$_POST['pne'].$btn;
		$_SESSION['PNE2']=$_POST['pne'];
	}
	$left="_".substr($_POST['urgence'],0,strpos($_POST['urgence'],";"));
	if($_POST['urgence']<>"" && strpos($_SESSION['Urgence2'],$left.";")===false){
		$right=substr($_POST['urgence'],strpos($_POST['urgence'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('urgence','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Urgence']=$_SESSION['Urgence'].$right.$btn;
		$_SESSION['Urgence2']=$_SESSION['Urgence2'].$left.";";
	}
	if($_POST['titre']<>"" && strpos($_SESSION['Titre2'],$_POST['titre'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('titre','".$_POST['titre']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Titre']=$_SESSION['Titre'].$_POST['titre'].$btn;
		$_SESSION['Titre2']=$_SESSION['Titre2'].$_POST['titre'].";";
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
	$_SESSION['Page']=0;
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['MSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['MSN']);
			$_SESSION['MSN2']=str_replace($_GET['valeur'].";","",$_SESSION['MSN2']);
		}
		elseif($_GET['critere']=="numDossier"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDossier','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['NumDossier']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['NumDossier']);
			$_SESSION['NumDossier2']=str_replace($_GET['valeur'].";","",$_SESSION['NumDossier2']);
		}
		elseif($_GET['critere']=="numIC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numIC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['NumIC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['NumIC']);
			$_SESSION['NumIC2']=str_replace($_GET['valeur'].";","",$_SESSION['NumIC2']);
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
					$req="SELECT Libelle FROM sp_zonedetravail WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Zone'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="pole"){
			$_SESSION['Pole_FI2']=str_replace($_GET['valeur'].";","",$_SESSION['Pole_FI2']);
			$tab = explode(";",$_SESSION['Pole_FI2']);
			$_SESSION['Pole_FI']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('pole','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM new_competences_pole WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Pole_FI'].=$row['Libelle'].$valeur;
					}
				}
			}
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
		if($_GET['critere']=="vacation"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			if($_GET['valeur']=="J"){$_SESSION['Vacation']=str_replace("Jour".$valeur,"",$_SESSION['Vacation']);}
			elseif($_GET['valeur']=="S"){$_SESSION['Vacation']=str_replace("Soir".$valeur,"",$_SESSION['Vacation']);}
			elseif($_GET['valeur']=="N"){$_SESSION['Vacation']=str_replace("Nuit".$valeur,"",$_SESSION['Vacation']);}
			elseif($_GET['valeur']=="VSD Jour"){$_SESSION['Vacation']=str_replace("VSD Jour".$valeur,"",$_SESSION['Vacation']);}
			elseif($_GET['valeur']=="VSD Nuit"){$_SESSION['Vacation']=str_replace("VSD Nuit".$valeur,"",$_SESSION['Vacation']);}
			$_SESSION['Vacation2']=str_replace($_GET['valeur'].";","",$_SESSION['Vacation2']);
		}
		if($_GET['critere']=="vacationQUALITE"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacationQUALITE','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			if($_GET['valeur']=="J"){$_SESSION['VacationQUALITE']=str_replace("Jour".$valeur,"",$_SESSION['VacationQUALITE']);}
			elseif($_GET['valeur']=="S"){$_SESSION['VacationQUALITE']=str_replace("Soir".$valeur,"",$_SESSION['VacationQUALITE']);}
			elseif($_GET['valeur']=="N"){$_SESSION['VacationQUALITE']=str_replace("Nuit".$valeur,"",$_SESSION['VacationQUALITE']);}
			elseif($_GET['valeur']=="VSD Jour"){$_SESSION['VacationQUALITE']=str_replace("VSD Jour".$valeur,"",$_SESSION['VacationQUALITE']);}
			elseif($_GET['valeur']=="VSD Nuit"){$_SESSION['VacationQUALITE']=str_replace("VSD Nuit".$valeur,"",$_SESSION['VacationQUALITE']);}
			$_SESSION['VacationQUALITE2']=str_replace($_GET['valeur'].";","",$_SESSION['VacationQUALITE2']);
		}
		if($_GET['critere']=="etatIC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('etatIC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EtatIC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EtatIC']);
			$_SESSION['EtatIC2']=str_replace($_GET['valeur'].";","",$_SESSION['EtatIC2']);
		}
		if($_GET['critere']=="competence"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('competence','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Competence']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Competence']);
			$_SESSION['Competence2']=str_replace($_GET['valeur'].";","",$_SESSION['Competence2']);
		}
		if($_GET['critere']=="pne"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('pne','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['PNE']="";
			$_SESSION['PNE2']="";
		}
		if($_GET['critere']=="urgence"){
			$_SESSION['Urgence2']=str_replace($_GET['valeur'].";","",$_SESSION['Urgence2']);
			$tab = explode(";",$_SESSION['Urgence2']);
			$_SESSION['Urgence']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('urgence','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_urgence WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Urgence'].=$row['Libelle'].$valeur;
					}
					else{
						$_SESSION['Urgence'].="(vide)".$valeur;
					}
				}
			}
		}
		if($_GET['critere']=="titre"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('titre','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Titre']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Titre']);
			$_SESSION['Titre2']=str_replace($_GET['valeur'].";","",$_SESSION['Titre2']);
		}
		if($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateDebut']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateDebut']);
			$_SESSION['DateDebut2']=str_replace($_GET['valeur'],"",$_SESSION['DateDebut2']);
		}
		if($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateFin']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateFin']);
			$_SESSION['DateFin2']=str_replace($_GET['valeur'],"",$_SESSION['DateFin2']);
		}
		if($_GET['critere']=="SansDate"){
			$_SESSION['SansDate']="";
			$_SESSION['SansDate2']="";
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
		<tr>
			<td width=20%>
				&nbsp; MSN :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="msn" size="10" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; N° OF :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="numDossier" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; N° IC :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="numIC" size="15" value="">
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td style="border-top:1px dotted #0077aa;" colspan="2"> &nbsp; PROD
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Vacation :
			</td>
			<td>
				<input type="checkbox" id="J;Jour" name="J;Jour" value="J;Jour">Jour &nbsp;&nbsp;
				<input type="checkbox" id="S;Soir" name="S;Soir" value="S;Soir">Soir &nbsp;&nbsp;
				<input type="checkbox" id="N;Nuit" name="N;Nuit" value="N;Nuit">Nuit &nbsp;&nbsp;
				<input type="checkbox" id="VSD_Jour" name="VSD_Jour" value="VSD_Jour">VSD Jour &nbsp;&nbsp;
				<input type="checkbox" id="VSD_Nuit" name="VSD_Nuit" value="VSD_Nuit">VSD Nuit &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Du :
			</td>
			<td width=80%>
				<input type="date" style="text-align:center;" name="du" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Au :
			</td>
			<td width=80%>
				<input type="date" style="text-align:center;" name="au" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Sans date :
			</td>
			<td width=80%>
				<input type="checkbox" style="text-align:center;" name="sansDate" value="sansDate">
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td style="border-top:1px dotted #0077aa;" colspan="2"> &nbsp; QUALITE
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Vacation :
			</td>
			<td>
				<input type="checkbox" id="QJ;Jour" name="QJ;Jour" value="J;Jour">Jour &nbsp;&nbsp;
				<input type="checkbox" id="QS;Soir" name="QS;Soir" value="S;Soir">Soir &nbsp;&nbsp;
				<input type="checkbox" id="QN;Nuit" name="QN;Nuit" value="N;Nuit">Nuit &nbsp;&nbsp;
				<input type="checkbox" id="QVSD_Jour" name="QVSD_Jour" value="VSD_Jour">VSD Jour &nbsp;&nbsp;
				<input type="checkbox" id="QVSD_Nuit" name="QVSD_Nuit" value="VSD_Nuit">VSD Nuit &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Du :
			</td>
			<td width=80%>
				<input type="date" style="text-align:center;" name="duQ" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Au :
			</td>
			<td width=80%>
				<input type="date" style="text-align:center;" name="auQ" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Sans date :
			</td>
			<td width=80%>
				<input type="checkbox" style="text-align:center;" name="sansDateQ" value="sansDate">
			</td>
		</tr>
		<tr>
			<td style="border-bottom:1px dotted #0077aa;" colspan="2" height="4"></td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Section :
			</td>
			<td width=80%>
				<select name="section">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT SectionACP FROM sp_dossier ORDER BY SectionACP;";
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
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Zone :
			</td>
			<td width=80%>
				<select name="zone">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_dossier.Id_ZoneDeTravail AS Id, (SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail ";
					$req.="WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Libelle FROM sp_dossier ORDER BY Libelle;";
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
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Créateur dossier :
			</td>
			<td width=80%>
				<select name="createurDossier">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
					$req.="FROM sp_dossier INNER JOIN new_rh_etatcivil ON sp_dossier.Id_Personne=new_rh_etatcivil.Id ORDER BY new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom;";
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
				&nbsp; Créateur IC :
			</td>
			<td width=80%>
				<select name="createurIC">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
					$req.="FROM sp_ficheintervention INNER JOIN new_rh_etatcivil ON sp_ficheintervention.Id_Createur=new_rh_etatcivil.Id ORDER BY new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom;";
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
				&nbsp; Chef d'équipe :
			</td>
			<td width=80%>
				<select name="CE">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_ficheintervention.Id_PROD AS Id, ";
					$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_ficheintervention.Id_PROD) AS NomPrenom ";
					$req.="FROM sp_ficheintervention ORDER BY NomPrenom;";
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
				&nbsp; Inspecteur qualité :
			</td>
			<td width=80%>
				<select name="IQ">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_ficheintervention.Id_QUALITE AS Id, ";
					$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_ficheintervention.Id_QUALITE) AS NomPrenom ";
					$req.="FROM sp_ficheintervention ORDER BY NomPrenom;";
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
				&nbsp; Marque de contrôle :
			</td>
			<td width=80%>
				<select name="stamp">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT ";
					$req.="new_competences_personne_stamp.Num_Stamp ";
					$req.="FROM sp_ficheintervention LEFT JOIN new_competences_personne_stamp ON new_competences_personne_stamp.Id_Personne=sp_ficheintervention.Id_QUALITE ";
					$req.="WHERE Num_Stamp<>'' ORDER BY Num_Stamp;";
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
				&nbsp; Statut IC :
			</td>
			<td>
			<input type="checkbox" id="(vide)" name="(vide)" value="(vide)">(vide) &nbsp;&nbsp;
			<input type="checkbox" id="CERT" name="CERT" value="CERT">CERT &nbsp;&nbsp;
			<input type="checkbox" id="QARJ" name="QARJ" value="QARJ">QARJ &nbsp;&nbsp;
			<input type="checkbox" id="REWORK" name="REWORK" value="REWORK">REWORK &nbsp;&nbsp;
			<input type="checkbox" id="TFS" name="TFS" value="TFS">TFS &nbsp;&nbsp;
			<input type="checkbox" id="TVS" name="TVS" value="TVS">TVS &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Urgence :
			</td>
			<td width=80%>
				<select name="urgence">
				<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_dossier.Id_Urgence AS Id, (SELECT sp_urgence.Libelle FROM sp_urgence WHERE sp_urgence.Id=sp_dossier.Id_Urgence) AS Libelle ";
					$req.="FROM sp_dossier ORDER BY Libelle;";
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
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Titre :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="titre" size="40" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Etat IC :
			</td>
			<td width=80%>
				<select name="etatIC">
					<option name="" value=""></option>
					<option name="ANNULEE" value="ANNULEE">ANNULEE</option>
					<option name="A TRAITER" value="A TRAITER">A TRAITER</option>
					<option name="A VALIDER PAR CE" value="A VALIDER PAR CE">A VALIDER PAR CE</option>
					<option name="REFUSEE" value="REFUSEE">REFUSEE</option>
					<option name="VALIDEE" value="VALIDEE">VALIDEE</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Pôle :
			</td>
			<td width=80%>
				<select name="pole">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT Id, Libelle FROM new_competences_pole WHERE (Id IN (1,2,3,5,6,42) AND Actif=0 AND Id_Prestation=255) OR Id=176 ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option name='".$row['Id']."' value='".$row['Id'].";".$row['Libelle']."'>".$row['Libelle']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Compétence :
			</td>
			<td width=80%>
				<select name="competence">
					<option name="" value=""></option>
					<option name="Elec" value="Elec">Elec</option>
					<option name="Fuel" value="Fuel">Fuel</option>
					<option name="Hydraulique" value="Hydraulique">Hydraulique</option>
					<option name="Metal" value="Metal">Metal</option>
					<option name="Oxygene" value="Oxygene">Oxygene</option>
					<option name="Structure" value="Structure">Structure</option>
					<option name="Systeme" value="Systeme">Systeme</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20%>
				&nbsp; Poste neutre :
			</td>
			<td width=80%>
				<select name="pne">
					<option name="" value=""></option>
					<option name="Avec les PNE" value="Avec les PNE">Avec les PNE</option>
					<option name="Sans les PNE" value="Sans les PNE">Sans les PNE</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center" colspan="2">
				<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Ajouter">
			</td>
			
		</tr>
	</table>
	</td></tr>
</form>
</table>
</body>
</html>