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
	<script language="javascript" src="MSN.js"></script>
	<script>
		function Recharger(){
			opener.location="Dossier_Corbeille.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Dossier_Corbeille.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
 Ecrire_Code_JS_Init_Date(); 
 if($_POST){
	if($_POST['msn']<>"" && strpos($_SESSION['OTSupprMSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTSupprMSN']=$_SESSION['OTSupprMSN'].$_POST['msn'].$btn;
		$_SESSION['OTSupprMSN2']=$_SESSION['OTSupprMSN2'].$_POST['msn'].";";
	}
	if($_POST['ordreMontage']<>"" && strpos($_SESSION['OTSupprOM2'],$_POST['ordreMontage'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('ordreMontage','".$_POST['ordreMontage']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTSupprOM']=$_SESSION['OTSupprOM'].$_POST['ordreMontage'].$btn;
		$_SESSION['OTSupprOM2']=$_SESSION['OTSupprOM2'].$_POST['ordreMontage'].";";
	}
	$_SESSION['OTSupprPage']=0;
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['OTSupprMSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['OTSupprMSN']);
			$_SESSION['OTSupprMSN2']=str_replace($_GET['valeur'].";","",$_SESSION['OTSupprMSN2']);
		}
		elseif($_GET['critere']=="ordreMontage"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('ordreMontage','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['OTSupprOM']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['OTSupprOM']);
			$_SESSION['OTSupprOM2']=str_replace($_GET['valeur'].";","",$_SESSION['OTSupprOM2']);
		}
		$_SESSION['OTSupprPage']=0;
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereCorbeille.php">
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
			<td width=20% class="Libelle">
				&nbsp; MSN :
			</td>
			<td> 
				<input onKeyUp="nombre(this)" type="texte" style="text-align:center;" name="msn" size="10" value="">
			</td>
			<td width=20% class="Libelle">
				&nbsp; Ordre de montage :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="ordreMontage" size="15" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center" colspan="10">
				<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Ajouter">
			</td>
			
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
</form>
</table>
</body>
</html>