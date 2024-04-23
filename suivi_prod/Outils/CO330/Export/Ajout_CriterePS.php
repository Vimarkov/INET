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
	if($_POST['msn']<>"" && strpos($_SESSION['EXTRACT_PSMSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_PSMSN']=$_SESSION['EXTRACT_PSMSN'].$_POST['msn'].$btn;
		$_SESSION['EXTRACT_PSMSN2']=$_SESSION['EXTRACT_PSMSN2'].$_POST['msn'].";";
	}
	if($_POST['dossier']<>"" && strpos($_SESSION['EXTRACT_PSDossier2'],$_POST['dossier'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('dossier','".$_POST['dossier']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_PSDossier']=$_SESSION['EXTRACT_PSDossier'].$_POST['dossier'].$btn;
		$_SESSION['EXTRACT_PSDossier2']=$_SESSION['EXTRACT_PSDossier2'].$_POST['dossier'].";";
	}
	$left=substr($_POST['compagnon'],0,strpos($_POST['compagnon'],";"));
	if($_POST['compagnon']<>"" && strpos($_SESSION['EXTRACT_PSCompagnon2'],$left.";")===false){
		$right=substr($_POST['compagnon'],strpos($_POST['compagnon'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('compagnon','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_PSCompagnon']=$_SESSION['EXTRACT_PSCompagnon'].$right.$btn;
		$_SESSION['EXTRACT_PSCompagnon2']=$_SESSION['EXTRACT_PSCompagnon2'].$left.";";
	}
	$left=substr($_POST['qualiticien'],0,strpos($_POST['qualiticien'],";"));
	if($_POST['qualiticien']<>"" && strpos($_SESSION['EXTRACT_PSIQ2'],$left.";")===false){
		$right=substr($_POST['qualiticien'],strpos($_POST['qualiticien'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('qualiticien','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_PSIQ']=$_SESSION['EXTRACT_PSIQ'].$right.$btn;
		$_SESSION['EXTRACT_PSIQ2']=$_SESSION['EXTRACT_PSIQ2'].$left.";";
	}
	if($_POST['reference']<>"" && strpos($_SESSION['EXTRACT_PSReference2'],$_POST['reference'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('reference','".$_POST['reference']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_PSReference']=$_SESSION['EXTRACT_PSReference'].$_POST['reference'].$btn;
		$_SESSION['EXTRACT_PSReference2']=$_SESSION['EXTRACT_PSReference2'].$_POST['reference'].";";
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_PSDu']=$_POST['du'].$btn;
		$_SESSION['EXTRACT_PSDu2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_PSAu']=$_POST['au'].$btn;
		$_SESSION['EXTRACT_PSAu2']=$_POST['au'];
	}
	if($_POST['dateTERA']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('dateTERA','".$_POST['dateTERA']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_PSDateTERA']=$_POST['dateTERA'].$btn;
		$_SESSION['EXTRACT_PSDateTERA2']=$_POST['dateTERA'];
	}
	if($_POST['dateTERC']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('dateTERC','".$_POST['dateTERC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_PSDateTERC']=$_POST['dateTERC'].$btn;
		$_SESSION['EXTRACT_PSDateTERC2']=$_POST['dateTERC'];
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_PSMSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_PSMSN']);
			$_SESSION['EXTRACT_PSMSN2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_PSMSN2']);
		}
		elseif($_GET['critere']=="dossier"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('dossier','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_PSDossier']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_PSDossier']);
			$_SESSION['EXTRACT_PSDossier2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_PSDossier2']);
		}
		elseif($_GET['critere']=="compagnon"){
			$_SESSION['EXTRACT_PSCompagnon2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_PSCompagnon2']);
			$tab = explode(";",$_SESSION['EXTRACT_PSCompagnon2']);
			$_SESSION['EXTRACT_PSCompagnon']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('compagnon','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['EXTRACT_PSCompagnon'].=$row['Personne'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="qualiticien"){
			$_SESSION['EXTRACT_PSIQ2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_PSIQ2']);
			$tab = explode(";",$_SESSION['EXTRACT_PSIQ2']);
			$_SESSION['EXTRACT_PSIQ']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('qualiticien','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['EXTRACT_PSIQ'].=$row['Personne'].$valeur;
					}
				}
			}
		}
		if($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_PSDu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_PSDu']);
			$_SESSION['EXTRACT_PSDu2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_PSDu2']);
		}
		if($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_PSAu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_PSAu']);
			$_SESSION['EXTRACT_PSAu2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_PSAu2']);
		}
		if($_GET['critere']=="dateTERA"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('dateTERA','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_PSDateTERA']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_PSDateTERA']);
			$_SESSION['EXTRACT_PSDateTERA2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_PSDateTERA2']);
		}
		if($_GET['critere']=="dateTERC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePS('dateTERC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_PSDateTERC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['EXTRACT_PSDateTERC']);
			$_SESSION['EXTRACT_PSDateTERC2']=str_replace($_GET['valeur'],"",$_SESSION['EXTRACT_PSDateTERC2']);
		}
		echo "<script>FermerEtRecharger2();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CriterePS.php">
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
				&nbsp; Compagnon :
			</td>
			<td width=70%>
				<select name="compagnon">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_olwfi_travaileffectue.Id_Personne AS Id, 
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil 
						WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS Personne 
						FROM sp_olwfi_travaileffectue 
						LEFT JOIN sp_olwficheintervention
						ON sp_olwfi_travaileffectue.Id_FI=sp_olwficheintervention.Id
						WHERE (SELECT Id_Prestation FROM sp_olwdossier WHERE Id=sp_olwficheintervention.Id_Dossier)=1598
						ORDER BY Personne;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Personne="(vide)";
							if($row['Id']<>0){$Personne=$row['Personne'];}
							echo "<option name=\"".$row['Id']."\" value=\"".$row['Id'].";".$Personne."\">".$Personne."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; Qualiticien :
			</td>
			<td width=70%>
				<select name="qualiticien">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_olwficheintervention.Id_QUALITE AS Id, 
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil 
						WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_QUALITE) AS Personne 
						FROM sp_olwficheintervention 
						LEFT JOIN sp_olwdossier
						ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id
						WHERE sp_olwdossier.Id_Prestation=1598
						ORDER BY Personne;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Personne="(vide)";
							if($row['Id']<>0){$Personne=$row['Personne'];}
							echo "<option name=\"".$row['Id']."\" value=\"".$row['Id'].";".$Personne."\">".$Personne."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=30%>
				&nbsp; Référence procédé :
			</td>
			<td width=70%>
				<input type="texte" style="text-align:center;" name="reference" size="15" value="">
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