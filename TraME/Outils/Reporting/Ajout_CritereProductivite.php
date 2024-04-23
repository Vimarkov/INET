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
			opener.location="TDB_Production.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "TDB_Production.php";
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
	if(strlen($_POST['mois'])==7  && strpos($_SESSION['PRODUCTIVITE_Mois'],$_POST['mois'].";")===false){
		$_SESSION['PRODUCTIVITE_Mois']=$_POST['mois'];
		$_SESSION['PRODUCTIVITE_Mois2']=$_POST['mois'];
	}
	if($_POST['par']<>""){
		$_SESSION['PRODUCTIVITE_Par2']=$_POST['par'];
		if($_POST['par']==1){
			if($_SESSION['Langue']=="EN"){$_SESSION['PRODUCTIVITE_Par']= "Workpackage";}else{$_SESSION['PRODUCTIVITE_Par']= "Workpackage";}
		}
		elseif($_POST['par']==2){
			if($_SESSION['Langue']=="EN"){$_SESSION['PRODUCTIVITE_Par']= "Task";}else{$_SESSION['PRODUCTIVITE_Par']= "Tâche";}
		}
		elseif($_POST['par']==3){
			if($_SESSION['Langue']=="EN"){$_SESSION['PRODUCTIVITE_Par']= "Work unit";}else{$_SESSION['PRODUCTIVITE_Par']= "Unité d'oeuvre";}
		}
		elseif($_POST['par']==4){
			if($_SESSION['Langue']=="EN"){$_SESSION['PRODUCTIVITE_Par']= "Collaborater";}else{$_SESSION['PRODUCTIVITE_Par']= "Collaborateur";}
		}
	}
	$left=substr($_POST['wp'],0,strpos($_POST['wp'],";"));
	if($_POST['wp']<>"" && strpos($_SESSION['PRODUCTIVITE_WP2'],$left.";")===false){
		$right=substr($_POST['wp'],strpos($_POST['wp'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereProductivite('wp','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['PRODUCTIVITE_WP']=$_SESSION['PRODUCTIVITE_WP'].$right.$btn;
		$_SESSION['PRODUCTIVITE_WP2']=$_SESSION['PRODUCTIVITE_WP2'].$left.";";
	}
	$left=substr($_POST['tache'],0,strpos($_POST['tache'],";"));
	if($_POST['tache']<>"" && strpos($_SESSION['PRODUCTIVITE_Tache2'],$left.";")===false){
		$right=substr($_POST['tache'],strpos($_POST['tache'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereProductivite('tache','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['PRODUCTIVITE_Tache']=$_SESSION['PRODUCTIVITE_Tache'].$right.$btn;
		$_SESSION['PRODUCTIVITE_Tache2']=$_SESSION['PRODUCTIVITE_Tache2'].$left.";";
	}
	$left=substr($_POST['uo'],0,strpos($_POST['uo'],";"));
	if($_POST['uo']<>"" && strpos($_SESSION['PRODUCTIVITE_UO2'],$left.";")===false){
		$right=substr($_POST['uo'],strpos($_POST['uo'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereProductivite('uo','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['PRODUCTIVITE_UO']=$_SESSION['PRODUCTIVITE_UO'].$right.$btn;
		$_SESSION['PRODUCTIVITE_UO2']=$_SESSION['PRODUCTIVITE_UO2'].$left.";";
	}
	$left=substr($_POST['collaborateur'],0,strpos($_POST['collaborateur'],";"));
	if($_POST['collaborateur']<>"" && strpos($_SESSION['PRODUCTIVITE_Collaborateur2'],$left.";")===false){
		$right=substr($_POST['collaborateur'],strpos($_POST['collaborateur'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereProductivite('collaborateur','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['PRODUCTIVITE_Collaborateur']=$_SESSION['PRODUCTIVITE_Collaborateur'].$right.$btn;
		$_SESSION['PRODUCTIVITE_Collaborateur2']=$_SESSION['PRODUCTIVITE_Collaborateur2'].$left.";";
	}
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="wp"){
			$_SESSION['PRODUCTIVITE_WP2']=str_replace($_GET['valeur'].";","",$_SESSION['PRODUCTIVITE_WP2']);
			$tab = explode(";",$_SESSION['PRODUCTIVITE_WP2']);
			$_SESSION['PRODUCTIVITE_WP']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereProductivite('wp','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_wp WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['PRODUCTIVITE_WP'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="tache"){
			$_SESSION['PRODUCTIVITE_Tache2']=str_replace($_GET['valeur'].";","",$_SESSION['PRODUCTIVITE_Tache2']);
			$tab = explode(";",$_SESSION['PRODUCTIVITE_Tache2']);
			$_SESSION['PRODUCTIVITE_Tache']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereProductivite('tache','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_tache WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['PRODUCTIVITE_Tache'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="uo"){
			$_SESSION['PRODUCTIVITE_UO2']=str_replace($_GET['valeur'].";","",$_SESSION['PRODUCTIVITE_UO2']);
			$tab = explode(";",$_SESSION['PRODUCTIVITE_UO2']);
			$_SESSION['PRODUCTIVITE_UO']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereProductivite('uo','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_uo WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['PRODUCTIVITE_UO'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="collaborateur"){
			$_SESSION['PRODUCTIVITE_Collaborateur2']=str_replace($_GET['valeur'].";","",$_SESSION['PRODUCTIVITE_Collaborateur2']);
			$tab = explode(";",$_SESSION['PRODUCTIVITE_Collaborateur2']);
			$_SESSION['PRODUCTIVITE_Collaborateur']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CritereProductivite('collaborateur','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT CONCAT(Nom,' ',Prenom) AS Libelle FROM new_rh_etatcivil WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['PRODUCTIVITE_Collaborateur'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereProductivite.php">
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
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "By";}else{echo "Par";} ?></td>
			<td colspan="4">
				<select id="par" name="par">
					<option value=""></option>
					<option value="1"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></option>
					<option value="2"><?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?></option>
					<option value="4"><?php if($_SESSION['Langue']=="EN"){echo "Collaborater";}else{echo "Collaborateur";} ?></option>
				</select>
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
								echo "<option value=\"".$rowWP['Id'].";".stripslashes(str_replace("//","",$rowWP['Libelle']))."\">".stripslashes(str_replace("//","",$rowWP['Libelle']))."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?></td>
			<td colspan="4">
				<select id="tache" name="tache" style="width:400px;">
					<?php
						echo"<option value=''></option>";
						$req="SELECT trame_tache.Id, trame_tache.Libelle FROM trame_tache WHERE trame_tache.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								echo "<option value=\"".$row['Id'].";".stripslashes(str_replace("//","",$row['Libelle']))."\">".stripslashes(str_replace("//","",$row['Libelle']))."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Work unit";}else{echo "Unité d'oeuvre";} ?></td>
			<td colspan="4">
				<select id="uo" name="uo">
					<?php
						echo"<option value=''></option>";
						$req="SELECT trame_uo.Id, trame_uo.Libelle FROM trame_uo WHERE trame_uo.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								echo "<option value=\"".$row['Id'].";".stripslashes(str_replace("//","",$row['Libelle']))."\">".stripslashes(str_replace("//","",$row['Libelle']))."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Collaborater";}else{echo "Collaborateur";} ?></td>
			<td colspan="4">
				<select id="collaborateur" name="collaborateur">
					<?php
						echo"<option value=''></option>";
						$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM trame_acces LEFT JOIN new_rh_etatcivil on trame_acces.Id_Personne=new_rh_etatcivil.Id WHERE trame_acces.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								echo "<option value=\"".$row['Id'].";".$row['Nom']." ".$row['Prenom']."\">".$row['Nom']." ".$row['Prenom']."</option>";
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