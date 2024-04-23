<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
    <script src="../JS/jquery-1.11.1.min.js"></script>
    <script src="../JS/mask.js"></script>
	<script>
		function Recharger(){
			opener.location="Extract.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Extract.php";
			window.close();
		}
	</script>
</head>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

 Ecrire_Code_JS_Init_Date(); 
 if($_POST){
	if(strlen($_POST['mois'])==7  && strpos($_SESSION['EXTRACT_MoisQualite'],$_POST['mois'].";")===false){
		$_SESSION['EXTRACT_MoisQualite']=$_POST['mois'];
		$_SESSION['EXTRACT_MoisQualite2']=$_POST['mois'];
	}
	$left=substr($_POST['wp'],0,strpos($_POST['wp'],";"));
	if($_POST['wp']<>"" && strpos($_SESSION['EXTRACT_WPQualite2'],$left.";")===false){
		$right=substr($_POST['wp'],strpos($_POST['wp'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereQualite('wp','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_WPQualite']=$_SESSION['EXTRACT_WPQualite'].$right.$btn;
		$_SESSION['EXTRACT_WPQualite2']=$_SESSION['EXTRACT_WPQualite2'].$left.";";
	}
	$left=substr($_POST['checklist'],0,strpos($_POST['checklist'],";"));
	if($_POST['checklist']<>"" && strpos($_SESSION['EXTRACT_Checklist2'],$left.";")===false){
		$right=substr($_POST['checklist'],strpos($_POST['checklist'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereQualite('checklist','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_Checklist']=$right.$btn;
		$_SESSION['EXTRACT_Checklist2']=$left.";";
	}
	$left=substr($_POST['responsable'],0,strpos($_POST['responsable'],";"));
	if($_POST['responsable']<>"" && strpos($_SESSION['EXTRACT_Responsable2'],$left.";")===false){
		$right=substr($_POST['responsable'],strpos($_POST['responsable'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereQualite('responsable','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_Responsable']=$right.$btn;
		$_SESSION['EXTRACT_Responsable2']=$left.";";
	}
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="wp"){
			$_SESSION['EXTRACT_WPQualite2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_WPQualite2']);
			$tab = explode(";",$_SESSION['EXTRACT_WPQualite2']);
			$_SESSION['EXTRACT_WPQualite']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereQualite('wp','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_wp WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['EXTRACT_WPQualite'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		if($_GET['critere']=="checklist"){
			$_SESSION['EXTRACT_Checklist2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_Checklist2']);
			$tab = explode(";",$_SESSION['EXTRACT_Checklist2']);
			$_SESSION['EXTRACT_Checklist']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereQualite('checklist','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_checklist WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['EXTRACT_Checklist'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		if($_GET['critere']=="responsable"){
			$_SESSION['EXTRACT_Responsable2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_Responsable2']);
			$tab = explode(";",$_SESSION['EXTRACT_Responsable2']);
			$_SESSION['EXTRACT_Responsable']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereQualite('checklist','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_responsable WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['EXTRACT_Responsable'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereQualite.php">
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
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Month mm/yyyy";}else{echo "Mois mm/aaaa";} ?></td>
			<td>
				<input id="mois" name="mois">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
			<td colspan="4">
				<select id="wp" name="wp">
					<?php
						echo"<option value=''></option>";
						$req="SELECT trame_wp.Id, trame_wp.Libelle FROM trame_wp WHERE trame_wp.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowWP=mysqli_fetch_array($result)){
								echo "<option value=\"".$rowWP['Id'].";".$rowWP['Libelle']."\">".$rowWP['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Checklist";}else{echo "Checklist";} ?></td>
			<td colspan="4">
				<select id="checklist" name="checklist">
					<?php
						echo"<option value=''></option>";
						$req="SELECT trame_checklist.Id, trame_checklist.Libelle FROM trame_checklist WHERE trame_checklist.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=0 ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowWP=mysqli_fetch_array($result)){
								echo "<option value=\"".$rowWP['Id'].";".$rowWP['Libelle']."\">".$rowWP['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Responsible";}else{echo "Responsable";} ?></td>
			<td colspan="4">
				<select id="responsable" name="responsable">
					<?php
						echo"<option value=''></option>";
						$req="SELECT trame_responsable.Id, trame_responsable.Libelle FROM trame_responsable WHERE trame_responsable.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=0 ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowWP=mysqli_fetch_array($result)){
								echo "<option value=\"".$rowWP['Id'].";".$rowWP['Libelle']."\">".$rowWP['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="10">
				<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";} ?>">
			</td>
			
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
</form>
</table>
<script>
	var options;
	var masks = [];
	var mask;
	var setErrorFunction = function(isValid, input){
		if(isValid == false){
			input.parent().css("border", "1px solid red");
		}else{
			input.parent().css("border", "1px solid green");
		}
	} 
	mask = Mask.newMask(
	{
		$el: $("#mois"),
		mask: "MM/YYYY",
		errorFunction: setErrorFunction,
		isUtc: true
	}
	);
	masks.push(mask); 
</script>
</body>
</html>