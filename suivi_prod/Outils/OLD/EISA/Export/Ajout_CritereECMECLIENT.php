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
		function FermerEtRecharger(){
			opener.location="Liste_Reporting.php";
		}
		function FermerEtRecharger2(){
			opener.location="Liste_Reporting.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

$DateJour=date("Y-m-d");
Ecrire_Code_JS_Init_Date(); 
 
					
 if($_POST){
	if($_POST['msn']<>"" && strpos($_SESSION['EXTRACT_ECMECLIENTMSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMECLIENTMSN']=$_SESSION['EXTRACT_ECMECLIENTMSN'].$_POST['msn'].$btn;
		$_SESSION['EXTRACT_ECMECLIENTMSN2']=$_SESSION['EXTRACT_ECMECLIENTMSN2'].$_POST['msn'].";";
	}
	if($_POST['dossier']<>"" && strpos($_SESSION['EXTRACT_ECMECLIENTDossier2'],$_POST['dossier'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('dossier','".$_POST['dossier']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMECLIENTDossier']=$_SESSION['EXTRACT_ECMECLIENTDossier'].$_POST['dossier'].$btn;
		$_SESSION['EXTRACT_ECMECLIENTDossier2']=$_SESSION['EXTRACT_ECMECLIENTDossier2'].$_POST['dossier'].";";
	}
	if($_POST['client']<>"" && strpos($_SESSION['EXTRACT_ECMECLIENTClient2'],$_POST['client'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('client','".$_POST['client']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMECLIENTClient']=$_SESSION['EXTRACT_ECMECLIENTClient'].$_POST['client'].$btn;
		$_SESSION['EXTRACT_ECMECLIENTClient2']=$_SESSION['EXTRACT_ECMECLIENTClient2'].$_POST['client'].";";
	}
	if($_POST['dateEtalonnage']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('dateEtalonnage','".$_POST['dateEtalonnage']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']=$_POST['dateEtalonnage'].$btn;
		$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']=$_POST['dateEtalonnage'];
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMECLIENTDu']=$_POST['du'].$btn;
		$_SESSION['EXTRACT_ECMECLIENTDu2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMECLIENTAu']=$_POST['au'].$btn;
		$_SESSION['EXTRACT_ECMECLIENTAu2']=$_POST['au'];
	}
	if($_POST['dateTERA']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('dateTERA','".$_POST['dateTERA']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMECLIENTDateTERA']=$_POST['dateTERA'].$btn;
		$_SESSION['EXTRACT_ECMECLIENTDateTERA2']=$_POST['dateTERA'];
	}
	if($_POST['dateTERC']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('dateTERC','".$_POST['dateTERC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMECLIENTDateTERC']=$_POST['dateTERC'].$btn;
		$_SESSION['EXTRACT_ECMECLIENTDateTERC2']=$_POST['dateTERC'];
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMECLIENTMSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMECLIENTMSN']);
			$_SESSION['EXTRACT_ECMECLIENTMSN2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_ECMECLIENTMSN2']);
		}
		elseif($_GET['critere']=="dossier"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('dossier','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMECLIENTDossier']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMECLIENTDossier']);
			$_SESSION['EXTRACT_ECMECLIENTDossier2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_ECMECLIENTDossier2']);
		}
		elseif($_GET['critere']=="client"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('client','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMECLIENTClient']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMECLIENTClient']);
			$_SESSION['EXTRACT_ECMECLIENTClient2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_ECMECLIENTClient2']);
		}
		if($_GET['critere']=="dateEtalonnage"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('dateEtalonnage','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']);
			$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']);
		}
		if($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMECLIENTDu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMECLIENTDu']);
			$_SESSION['EXTRACT_ECMECLIENTDu2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_ECMECLIENTDu2']);
		}
		if($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMECLIENTAu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMECLIENTAu']);
			$_SESSION['EXTRACT_ECMECLIENTAu2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_ECMECLIENTAu2']);
		}
		if($_GET['critere']=="dateTERA"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('dateTERA','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMECLIENTDateTERA']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMECLIENTDateTERA']);
			$_SESSION['EXTRACT_ECMECLIENTDateTERA2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_ECMECLIENTDateTERA2']);
		}
		if($_GET['critere']=="dateTERC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECMECLIENT('dateTERC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMECLIENTDateTERC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMECLIENTDateTERC']);
			$_SESSION['EXTRACT_ECMECLIENTDateTERC2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_ECMECLIENTDateTERC2']);
		}
		echo "<script>FermerEtRecharger2();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereECMECLIENT.php">
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
				&nbsp; N° client :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="client" size="10" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Date fin étalonnage :
			</td>
			<td width=80%>
				<input type="date" style="text-align:center;" name="dateEtalonnage" size="15" value="">
			</td>
		</tr>
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
				&nbsp; N° dossier :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="dossier" size="10" value="">
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
				&nbsp; Date TERA :
			</td>
			<td width=80%>
				<input type="date" style="text-align:center;" name="dateTERA" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Date TERC :
			</td>
			<td width=80%>
				<input type="date" style="text-align:center;" name="dateTERC" size="15" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center" colspan="2"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Ajouter"></td>
		</tr>
	</table>
	</td></tr>
</form>
</table>
</body>
</html>