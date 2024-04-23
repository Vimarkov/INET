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
			opener.location="Liste_Extract.php";
		}
		function FermerEtRecharger2(){
			opener.location="Liste_Extract.php";
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
	if($_POST['msn']<>"" && strpos($_SESSION['EXTRACT_ECMEMSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMEMSN']=$_SESSION['EXTRACT_ECMEMSN'].$_POST['msn'].$btn;
		$_SESSION['EXTRACT_ECMEMSN2']=$_SESSION['EXTRACT_ECMEMSN2'].$_POST['msn'].";";
	}
	if($_POST['dossier']<>"" && strpos($_SESSION['EXTRACT_ECMEDossier2'],$_POST['dossier'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('dossier','".$_POST['dossier']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMEDossier']=$_SESSION['EXTRACT_ECMEDossier'].$_POST['dossier'].$btn;
		$_SESSION['EXTRACT_ECMEDossier2']=$_SESSION['EXTRACT_ECMEDossier2'].$_POST['dossier'].";";
	}
	$left=substr($_POST['metier'],0,strpos($_POST['metier'],";"));
	if($_POST['metier']<>"" && strpos($_SESSION['EXTRACT_ECMEMetier2'],$left.";")===false){
		$right=substr($_POST['metier'],strpos($_POST['metier'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('metier','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMEMetier']=$_SESSION['EXTRACT_ECMEMetier'].$right.$btn;
		$_SESSION['EXTRACT_ECMEMetier2']=$_SESSION['EXTRACT_ECMEMetier2'].$left.";";
	}
	if($_POST['reference']<>"" && strpos($_SESSION['EXTRACT_ECMEReference2'],$_POST['reference'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('reference','".$_POST['reference']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMEReference']=$_SESSION['EXTRACT_ECMEReference'].$_POST['reference'].$btn;
		$_SESSION['EXTRACT_ECMEReference2']=$_SESSION['EXTRACT_ECMEReference2'].$_POST['reference'].";";
	}
	$left=substr($_POST['type'],0,strpos($_POST['type'],";"));
	if($_POST['type']<>"" && strpos($_SESSION['EXTRACT_ECMEType2'],$left.";")===false){
		$right=substr($_POST['type'],strpos($_POST['type'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href='javascript:Suppr_CritereECME(\"type\",\"".$left."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMEType']=$_SESSION['EXTRACT_ECMEType'].$right.$btn;
		$_SESSION['EXTRACT_ECMEType2']=$_SESSION['EXTRACT_ECMEType2'].$left.";";
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMEDu']=$_POST['du'].$btn;
		$_SESSION['EXTRACT_ECMEDu2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMEAu']=$_POST['au'].$btn;
		$_SESSION['EXTRACT_ECMEAu2']=$_POST['au'];
	}
	if($_POST['dateTERA']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('dateTERA','".$_POST['dateTERA']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMEDateTERA']=$_POST['dateTERA'].$btn;
		$_SESSION['EXTRACT_ECMEDateTERA2']=$_POST['dateTERA'];
	}
	if($_POST['dateTERC']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('dateTERC','".$_POST['dateTERC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_ECMEDateTERC']=$_POST['dateTERC'].$btn;
		$_SESSION['EXTRACT_ECMEDateTERC2']=$_POST['dateTERC'];
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMEMSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMEMSN']);
			$_SESSION['EXTRACT_ECMEMSN2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_ECMEMSN2']);
		}
		elseif($_GET['critere']=="dossier"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('dossier','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMEDossier']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMEDossier']);
			$_SESSION['EXTRACT_ECMEDossier2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_ECMEDossier2']);
		}
		elseif($_GET['critere']=="metier"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('metier','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			if($_GET['valeur']=="0"){$_SESSION['EXTRACT_ECMEMetier']=str_replace("Production".$valeur,"",$_SESSION['EXTRACT_ECMEMetier']);}
			elseif($_GET['valeur']=="1"){$_SESSION['EXTRACT_ECMEMetier']=str_replace("Qualité".$valeur,"",$_SESSION['EXTRACT_ECMEMetier']);}
			$_SESSION['EXTRACT_ECMEMetier2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_ECMEMetier2']);
		}
		elseif($_GET['critere']=="reference"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('reference','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMEReference']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMEReference']);
			$_SESSION['EXTRACT_ECMEReference2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_ECMEReference2']);
		}
		elseif($_GET['critere']=="type"){
			$_SESSION['EXTRACT_ECMEType2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_ECMEType2']);
			$tab = explode(";",$_SESSION['EXTRACT_ECMEType2']);
			$_SESSION['EXTRACT_ECMEType']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('type','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_olwtypeecme WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['EXTRACT_ECMEType'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		if($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMEDu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMEDu']);
			$_SESSION['EXTRACT_ECMEDu2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_ECMEDu2']);
		}
		if($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMEAu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMEAu']);
			$_SESSION['EXTRACT_ECMEAu2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_ECMEAu2']);
		}
		if($_GET['critere']=="dateTERA"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('dateTERA','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMEDateTERA']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMEDateTERA']);
			$_SESSION['EXTRACT_ECMEDateTERA2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_ECMEDateTERA2']);
		}
		if($_GET['critere']=="dateTERC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereECME('dateTERC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_ECMEDateTERC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_ECMEDateTERC']);
			$_SESSION['EXTRACT_ECMEDateTERC2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_ECMEDateTERC2']);
		}
		echo "<script>FermerEtRecharger2();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereECME.php">
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
			<td width="20%">
				&nbsp; Métier utilisateur :
			</td>
			<td width="80%">
				<select name="metier">
					<option value=""></option>
					<option value="0;Production">Prodution</option>
					<option value="1;Qualité">Qualité</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Référence ECME :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="reference" size="10" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Type ECME :
			</td>
			<td width=80%>
				<select name="type">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_olwfi_ecme.Id_TypeECME AS Id, (SELECT Libelle FROM sp_olwtypeecme ";
					$req.="WHERE sp_olwtypeecme.Id=sp_olwfi_ecme.Id_TypeECME) AS Libelle 
					FROM sp_olwfi_ecme 
					LEFT JOIN sp_olwficheintervention
					ON sp_olwfi_ecme.Id_FI=sp_olwficheintervention.Id
					WHERE (SELECT Id_Prestation FROM sp_olwdossier WHERE Id=sp_olwficheintervention.Id_Dossier)=237
					ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Libelle="(vide)";
							if($row['Id']<>0){$Libelle=$row['Libelle'];}
							echo "<option name=\"".$row['Id']."\" value=\"".$row['Id'].";".$Libelle."\">".$Libelle."</option>";
						}
					}
					?>
				</select>
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