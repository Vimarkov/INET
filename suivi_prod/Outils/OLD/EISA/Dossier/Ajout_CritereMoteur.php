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
			opener.location="Liste_Moteur.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_Moteur.php";
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
	if($_POST['msn']<>"" && strpos($_SESSION['MOTMSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['MOTMSN']=$_SESSION['MOTMSN'].$_POST['msn'].$btn;
		$_SESSION['MOTMSN2']=$_SESSION['MOTMSN2'].$_POST['msn'].";";
	}
	if($_POST['typeMoteur']<>"" && strpos($_SESSION['MOTTypeMoteur2'],$_POST['typeMoteur'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('typeMoteur','".$_POST['typeMoteur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['MOTTypeMoteur']=$_SESSION['MOTTypeMoteur'].$_POST['typeMoteur'].$btn;
		$_SESSION['MOTTypeMoteur2']=$_SESSION['MOTTypeMoteur2'].$_POST['typeMoteur'].";";
	}
	if($_POST['posteMontage']<>"" && strpos($_SESSION['MOTPosteMontage2'],$_POST['posteMontage'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('posteMontage','".$_POST['posteMontage']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['MOTPosteMontage']=$_SESSION['MOTPosteMontage'].$_POST['posteMontage'].$btn;
		$_SESSION['MOTPosteMontage2']=$_SESSION['MOTPosteMontage2'].$_POST['posteMontage'].";";
	}
	$_SESSION['MOTPage']=0;
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['MOTMSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['MOTMSN']);
			$_SESSION['MOTMSN2']=str_replace($_GET['valeur'].";","",$_SESSION['MOTMSN2']);
		}
		elseif($_GET['critere']=="typeMoteur"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('typeMoteur','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['MOTTypeMoteur']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['MOTTypeMoteur']);
			$_SESSION['MOTTypeMoteur2']=str_replace($_GET['valeur'].";","",$_SESSION['MOTTypeMoteur2']);
		}
		elseif($_GET['critere']=="posteMontage"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('posteMontage','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['MOTPosteMontage']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['MOTPosteMontage']);
			$_SESSION['MOTPosteMontage2']=str_replace($_GET['valeur'].";","",$_SESSION['MOTPosteMontage2']);
		}
		$_SESSION['MOTPage']=0;
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereMoteur.php">
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
			<td width=20%>
				&nbsp; MSN :
			</td>
			<td> 
				<input onKeyUp="nombre(this)" type="texte" style="text-align:center;" name="msn" size="10" value="">
			</td>
			<td width=20% class="Libelle">
				&nbsp; Type moteur :
			</td>
			<td >
				<select name="typeMoteur">
					<option value=""></option>
					<option value="CFM">CFM</option>
					<option value="IAE">IAE</option>
					<option value="PW">PW</option>
					<option value="LEAP">LEAP</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20% class="Libelle">
				&nbsp; Poste de montage :
			</td>
			<td >
				<select name="posteMontage">
					<option value=""></option>
					<option value="AF">AF</option>
					<option value="M15">M15</option>
				</select>
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