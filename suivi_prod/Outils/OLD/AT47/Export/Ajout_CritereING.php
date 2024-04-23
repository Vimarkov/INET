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
	if($_POST['msn']<>"" && strpos($_SESSION['EXTRACT_INGMSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_INGMSN']=$_SESSION['EXTRACT_INGMSN'].$_POST['msn'].$btn;
		$_SESSION['EXTRACT_INGMSN2']=$_SESSION['EXTRACT_INGMSN2'].$_POST['msn'].";";
	}
	if($_POST['dossier']<>"" && strpos($_SESSION['EXTRACT_INGDossier2'],$_POST['dossier'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('dossier','".$_POST['dossier']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_INGDossier']=$_SESSION['EXTRACT_INGDossier'].$_POST['dossier'].$btn;
		$_SESSION['EXTRACT_INGDossier2']=$_SESSION['EXTRACT_INGDossier2'].$_POST['dossier'].";";
	}
	if($_POST['ingredient']<>"" && strpos($_SESSION['EXTRACT_INGIngredient2'],$_POST['ingredient'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('ingredient','".$_POST['ingredient']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_INGIngredient']=$_SESSION['EXTRACT_INGIngredient'].$_POST['ingredient'].$btn;
		$_SESSION['EXTRACT_INGIngredient2']=$_SESSION['EXTRACT_INGIngredient2'].$_POST['ingredient'].";";
	}
	if($_POST['numLot']<>"" && strpos($_SESSION['EXTRACT_INGNumLot2'],$_POST['numLot'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('numLot','".$_POST['numLot']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_INGNumLot']=$_SESSION['EXTRACT_INGNumLot'].$_POST['numLot'].$btn;
		$_SESSION['EXTRACT_INGNumLot2']=$_SESSION['EXTRACT_INGNumLot2'].$_POST['numLot'].";";
	}
	if($_POST['datePeremption']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('datePeremption','".$_POST['datePeremption']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_INGDatePeremption']=$_POST['datePeremption'].$btn;
		$_SESSION['EXTRACT_INGDatePeremption2']=$_POST['datePeremption'];
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_INGDu']=$_POST['du'].$btn;
		$_SESSION['EXTRACT_INGDu2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_INGAu']=$_POST['au'].$btn;
		$_SESSION['EXTRACT_INGAu2']=$_POST['au'];
	}
	if($_POST['dateTERA']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('dateTERA','".$_POST['dateTERA']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_INGDateTERA']=$_POST['dateTERA'].$btn;
		$_SESSION['EXTRACT_INGDateTERA2']=$_POST['dateTERA'];
	}
	if($_POST['dateTERC']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('dateTERC','".$_POST['dateTERC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_INGDateTERC']=$_POST['dateTERC'].$btn;
		$_SESSION['EXTRACT_INGDateTERC2']=$_POST['dateTERC'];
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_INGMSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_INGMSN']);
			$_SESSION['EXTRACT_INGMSN2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_INGMSN2']);
		}
		elseif($_GET['critere']=="dossier"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('dossier','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_INGDossier']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_INGDossier']);
			$_SESSION['EXTRACT_INGDossier2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_INGDossier2']);
		}
		elseif($_GET['critere']=="ingredient"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('ingredient','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_INGIngredient']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_INGIngredient']);
			$_SESSION['EXTRACT_INGIngredient2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_INGIngredient2']);
		}
		if($_GET['critere']=="numLot"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('numLot','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_INGNumLot']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_INGNumLot']);
			$_SESSION['EXTRACT_INGNumLot2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_INGNumLot2']);
		}
		if($_GET['critere']=="datePeremption"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('datePeremption','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_INGDatePeremption']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_INGDatePeremption']);
			$_SESSION['EXTRACT_INGDatePeremption2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_INGDatePeremption2']);
		}
		if($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_INGDu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_INGDu']);
			$_SESSION['EXTRACT_INGDu2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_INGDu2']);
		}
		if($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_INGAu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_INGAu']);
			$_SESSION['EXTRACT_INGAu2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_INGAu2']);
		}
		if($_GET['critere']=="dateTERA"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('dateTERA','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_INGDateTERA']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_INGDateTERA']);
			$_SESSION['EXTRACT_INGDateTERA2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_INGDateTERA2']);
		}
		if($_GET['critere']=="dateTERC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereING('dateTERC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_INGDateTERC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_INGDateTERC']);
			$_SESSION['EXTRACT_INGDateTERC2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_INGDateTERC2']);
		}
		echo "<script>FermerEtRecharger2();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereING.php">
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
			<td width=30%>
				&nbsp; Ingrédient :
			</td>
			<td width=70%>
				<input type="texte" style="text-align:center;" name="ingredient" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; N° lot :
			</td>
			<td width=70%>
				<input type="texte" style="text-align:center;" name="numLot" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; Date de péremption :
			</td>
			<td width=70%>
				<input type="date" style="text-align:center;" name="datePeremption" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; MSN :
			</td>
			<td width=70%>
				<input type="texte" style="text-align:center;" name="msn" size="10" value="">
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; N° dossier :
			</td>
			<td width=70%>
				<input type="texte" style="text-align:center;" name="dossier" size="10" value="">
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; Du :
			</td>
			<td width=70%>
				<input type="date" style="text-align:center;" name="du" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; Au :
			</td>
			<td width=70%>
				<input type="date" style="text-align:center;" name="au" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; Date TERA :
			</td>
			<td width=70%>
				<input type="date" style="text-align:center;" name="dateTERA" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; Date TERC :
			</td>
			<td width=70%>
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