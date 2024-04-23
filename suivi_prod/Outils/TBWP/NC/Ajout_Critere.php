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
			opener.location="Liste_NC.php";
		}
		function FermerEtRecharger2(){
			opener.location="Liste_NC.php";
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
	if($_POST['msn']<>"" && strpos($_SESSION['MSN_NC2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['MSN_NC']=$_SESSION['MSN_NC'].$_POST['msn'].$btn;
		$_SESSION['MSN_NC2']=$_SESSION['MSN_NC2'].$_POST['msn'].";";
	}
	if($_POST['numNC']<>"" && strpos($_SESSION['Num_NC2'],$_POST['numNC'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numNC','".$_POST['numNC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Num_NC']=$_SESSION['Num_NC'].$_POST['numNC'].$btn;
		$_SESSION['Num_NC2']=$_SESSION['Num_NC2'].$_POST['numNC'].";";
	}
	$left="_".substr($_POST['typeDefaut'],0,strpos($_POST['typeDefaut'],";"));
	if($_POST['typeDefaut']<>"" && strpos($_SESSION['TypeDefaut2'],$left.";")===false){
		$right=substr($_POST['typeDefaut'],strpos($_POST['typeDefaut'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('typeDefaut','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['TypeDefaut']=$_SESSION['TypeDefaut'].$right.$btn;
		$_SESSION['TypeDefaut2']=$_SESSION['TypeDefaut2'].$left.";";
	}
	$left="_".substr($_POST['imputationAAA'],0,strpos($_POST['imputationAAA'],";"));
	if($_POST['imputationAAA']<>"" && strpos($_SESSION['ImputationAAA2'],$left.";")===false){
		$right=substr($_POST['imputationAAA'],strpos($_POST['imputationAAA'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputationAAA','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ImputationAAA']=$_SESSION['ImputationAAA'].$right.$btn;
		$_SESSION['ImputationAAA2']=$_SESSION['ImputationAAA2'].$left.";";
	}
	$left="_".substr($_POST['createur'],0,strpos($_POST['createur'],";"));
	if($_POST['createur']<>"" && strpos($_SESSION['Id_Createur2'],$left.";")===false){
		$right=substr($_POST['createur'],strpos($_POST['createur'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createur','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Id_Createur']=$_SESSION['Id_Createur'].$right.$btn;
		$_SESSION['Id_Createur2']=$_SESSION['Id_Createur2'].$left.";";
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['DateDebutNC']=$_POST['du'].$btn;
		$_SESSION['DateDebutNC2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['DateFinNC']=$_POST['au'].$btn;
		$_SESSION['DateFinNC2']=$_POST['au'];
	}
	$_SESSION['ModeFiltre']="";
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['MSN_NC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['MSN_NC']);
			$_SESSION['MSN_NC2']=str_replace($_GET['valeur'].";","",$_SESSION['MSN_NC2']);
		}
		elseif($_GET['critere']=="numNC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numNC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Num_NC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Num_NC']);
			$_SESSION['Num_NC2']=str_replace($_GET['valeur'].";","",$_SESSION['Num_NC2']);
		}
		elseif($_GET['critere']=="typeDefaut"){
			$_SESSION['TypeDefaut2']=str_replace($_GET['valeur'].";","",$_SESSION['TypeDefaut2']);
			$tab = explode(";",$_SESSION['TypeDefaut2']);
			$_SESSION['TypeDefaut']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('typeDefaut','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_typedefautnc WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['TypeDefaut'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="createur"){
			$_SESSION['Id_Createur2']=str_replace($_GET['valeur'].";","",$_SESSION['Id_Createur2']);
			$tab = explode(";",$_SESSION['Id_Createur2']);
			$_SESSION['Id_Createur']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createur','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Id_Createur'].=$row['Nom']." ".$row['Prenom'].$valeur;
					}
				}
			}
		}
		if($_GET['critere']=="imputationAAA"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputationAAA','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			if($_GET['valeur']=="_1"){$_SESSION['ImputationAAA']=str_replace("Oui".$valeur,"",$_SESSION['ImputationAAA']);}
			elseif($_GET['valeur']=="_0"){$_SESSION['ImputationAAA']=str_replace("Non".$valeur,"",$_SESSION['ImputationAAA']);}
			$_SESSION['ImputationAAA2']=str_replace($_GET['valeur'].";","",$_SESSION['ImputationAAA2']);
		}
		if($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateDebutNC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateDebutNC']);
			$_SESSION['DateDebutNC2']=str_replace($_GET['valeur'],"",$_SESSION['DateDebutNC2']);
		}
		if($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateFinNC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateFinNC']);
			$_SESSION['DateFinNC2']=str_replace($_GET['valeur'],"",$_SESSION['DateFinNC2']);
		}
		$_SESSION['ModeFiltre']="";
		echo "<script>FermerEtRecharger2();</script>";
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
				&nbsp; N° NC :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="numNC" size="15" value="">
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
				&nbsp; Type de défaut :
			</td>
			<td width=80%>
				<select name="typeDefaut">
					<option name="" value=""></option>
					<?php
					$req="SELECT Id,Libelle FROM sp_typedefautnc WHERE Supprime=false ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option name='".$row['Id'].";".$row['Libelle']."' value='".$row['Id'].";".$row['Libelle']."'>".$row['Libelle']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Imputation AAA :
			</td>
			<td width=80%>
				<select name="imputationAAA">
					<option name="" value=""></option>
					<option name="1" value="1;Oui">Oui</option>
					<option name="0" value="0;Non">Non</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Créateur :
			</td>
			<td width=80%>
				<select name="createur">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
					$req.="FROM sp_nc INNER JOIN new_rh_etatcivil ON sp_nc.Id_Createur=new_rh_etatcivil.Id ORDER BY new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom;";
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