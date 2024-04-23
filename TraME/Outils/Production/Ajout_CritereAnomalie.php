<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function Recharger(){
			opener.location="Anomalie.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Anomalie.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
 Ecrire_Code_JS_Init_Date(); 
 if($_POST){
	if($_POST['reference']<>"" && strpos($_SESSION['ANOM_Reference2'],$_POST['reference'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('reference','".$_POST['reference']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ANOM_Reference']=$_SESSION['ANOM_Reference'].$_POST['reference'].$btn;
		$_SESSION['ANOM_Reference2']=$_SESSION['ANOM_Reference2'].$_POST['reference'].";";
	}
	if($_POST['probleme']<>"" && strpos($_SESSION['ANOM_Probleme'],$_POST['probleme'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('probleme','".$_POST['probleme']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ANOM_Probleme']=$_SESSION['ANOM_Probleme'].$_POST['probleme'].$btn;
		$_SESSION['ANOM_Probleme2']=$_SESSION['ANOM_Probleme2'].$_POST['probleme'].";";
	}
	$left=substr($_POST['wp'],0,strpos($_POST['wp'],";"));
	if($_POST['wp']<>"" && strpos($_SESSION['ANOM_WP2'],$left.";")===false){
		$right=substr($_POST['wp'],strpos($_POST['wp'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('wp','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ANOM_WP']=$_SESSION['ANOM_WP'].$right.$btn;
		$_SESSION['ANOM_WP2']=$_SESSION['ANOM_WP2'].$left.";";
	}
	$left=substr($_POST['createur'],0,strpos($_POST['createur'],";"));
	if($_POST['createur']<>"" && strpos($_SESSION['ANOM_Createur2'],$left.";")===false){
		$right=substr($_POST['createur'],strpos($_POST['createur'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createur','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ANOM_Createur']=$_SESSION['ANOM_Createur'].$right.$btn;
		$_SESSION['ANOM_Createur2']=$_SESSION['ANOM_Createur2'].$left.";";
	}
	$left=substr($_POST['origine'],0,strpos($_POST['origine'],";"));
	if($_POST['origine']<>"" && strpos($_SESSION['ANOM_Origine2'],$left.";")===false){
		$right=substr($_POST['origine'],strpos($_POST['origine'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('origine','".$_POST['origine']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ANOM_Origine']=$_SESSION['ANOM_Origine'].$right.$btn;
		$_SESSION['ANOM_Origine2']=$_SESSION['ANOM_Origine2'].$left.";";
	}
	$left=substr($_POST['responsable'],0,strpos($_POST['responsable'],";"));
	if($_POST['responsable']<>"" && strpos($_SESSION['ANOM_Responsable2'],$left.";")===false){
		$right=substr($_POST['responsable'],strpos($_POST['responsable'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('responsable','".$_POST['responsable']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ANOM_Responsable']=$_SESSION['ANOM_Responsable'].$right.$btn;
		$_SESSION['ANOM_Responsable2']=$_SESSION['ANOM_Responsable2'].$left.";";
	}
	$left=substr($_POST['familleErreur'],0,strpos($_POST['familleErreur'],";"));
	if($_POST['familleErreur']<>"" && strpos($_SESSION['ANOM_FamilleErreur2'],$left.";")===false){
		$right=substr($_POST['familleErreur'],strpos($_POST['familleErreur'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('familleErreur','".$_POST['familleErreur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ANOM_FamilleErreur']=$_SESSION['ANOM_FamilleErreur'].$right.$btn;
		$_SESSION['ANOM_FamilleErreur2']=$_SESSION['ANOM_FamilleErreur2'].$left.";";
	}
	if($_POST['dateDebut']<>"" && strpos($_SESSION['ANOM_DateDebut2'],$_POST['dateDebut'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateDebut','".$_POST['dateDebut']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ANOM_DateDebut']=$_POST['dateDebut'].$btn;
		$_SESSION['ANOM_DateDebut2']=$_POST['dateDebut'];
	}
	if($_POST['dateFin']<>"" && strpos($_SESSION['ANOM_DateFin2'],$_POST['dateFin'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateFin','".$_POST['dateFin']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ANOM_DateFin']=$_POST['dateFin'].$btn;
		$_SESSION['ANOM_DateFin2']=$_POST['dateFin'];
	}
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="reference"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('reference','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['ANOM_Reference']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['ANOM_Reference']);
			$_SESSION['ANOM_Reference2']=str_replace($_GET['valeur'].";","",$_SESSION['ANOM_Reference2']);
		}
		elseif($_GET['critere']=="probleme"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('probleme','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['ANOM_Probleme']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['ANOM_Probleme']);
			$_SESSION['ANOM_Probleme2']=str_replace($_GET['valeur'].";","",$_SESSION['ANOM_Probleme2']);
		}
		elseif($_GET['critere']=="wp"){
			$_SESSION['ANOM_WP2']=str_replace($_GET['valeur'].";","",$_SESSION['ANOM_WP2']);
			$tab = explode(";",$_SESSION['ANOM_WP2']);
			$_SESSION['ANOM_WP']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('wp','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_wp WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['ANOM_WP'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="origine"){
			$_SESSION['ANOM_Origine2']=str_replace($_GET['valeur'].";","",$_SESSION['ANOM_Origine2']);
			$tab = explode(";",$_SESSION['ANOM_Origine2']);
			$_SESSION['ANOM_Origine']="";
			foreach($tab as $Id){
				if($Id=="0"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('origine','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($_SESSION['Langue']=="EN"){
						$_SESSION['ANOM_Origine'].="(empty)".$valeur;
					}
					else{
						$_SESSION['ANOM_Origine'].="(vide)".$valeur;
					}
				}
				elseif($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('origine','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_origine WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['ANOM_Origine'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="responsable"){
			$_SESSION['ANOM_Responsable2']=str_replace($_GET['valeur'].";","",$_SESSION['ANOM_Responsable2']);
			$tab = explode(";",$_SESSION['ANOM_Responsable2']);
			$_SESSION['ANOM_Responsable']="";
			foreach($tab as $Id){
				if($Id=="0"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('responsable','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($_SESSION['Langue']=="EN"){
						$_SESSION['ANOM_Responsable'].="(empty)".$valeur;
					}
					else{
						$_SESSION['ANOM_Responsable'].="(vide)".$valeur;
					}
				}
				elseif($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('responsable','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_responsable WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['ANOM_Responsable'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="familleErreur"){
			$_SESSION['ANOM_FamilleErreur2']=str_replace($_GET['valeur'].";","",$_SESSION['ANOM_FamilleErreur2']);
			$tab = explode(";",$_SESSION['ANOM_FamilleErreur2']);
			$_SESSION['ANOM_FamilleErreur']="";
			foreach($tab as $Id){
				if($Id=="0"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('familleErreur','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($_SESSION['Langue']=="EN"){
						$_SESSION['ANOM_FamilleErreur'].="(empty)".$valeur;
					}
					else{
						$_SESSION['ANOM_FamilleErreur'].="(vide)".$valeur;
					}
				}
				elseif($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('familleErreur','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_familleerreur WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['ANOM_FamilleErreur'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="createur"){
			$_SESSION['ANOM_Createur2']=str_replace($_GET['valeur'].";","",$_SESSION['ANOM_Createur2']);
			$tab = explode(";",$_SESSION['ANOM_Createur2']);
			$_SESSION['ANOM_Createur']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createur','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['ANOM_Createur'].=$row['Nom']." ".$row['Prenom'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="dateDebut"){
			$_SESSION['ANOM_DateDebut']="";
			$_SESSION['ANOM_DateDebut2']="";
		}
		elseif($_GET['critere']=="dateFin"){
			$_SESSION['ANOM_DateFin']="";
			$_SESSION['ANOM_DateFin2']="";
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereAnomalie.php">
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
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Référence";} ?></td>
			<td colspan="4"> 
				<input type="texte" name="reference" size="20" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Problem";}else{echo "Problème";} ?></td>
			<td colspan="4"> 
				<input type="texte" name="probleme" size="70" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
			<td colspan="4">
				<select id="wp" name="wp">
					<?php
						echo"<option value=''></option>";
						$req="SELECT DISTINCT trame_wp.Id, trame_wp.Libelle FROM trame_anomalie LEFT JOIN trame_wp on trame_anomalie.Id_WP=trame_wp.Id WHERE trame_anomalie.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
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
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Origin";}else{echo "Origine";} ?></td>
			<td>
				<select id="origine" name="origine">
					<?php
						echo"<option value=''></option>";
						if($_SESSION['Langue']=="EN"){
							echo"<option value='0;(empty)'>(empty)</option>";
						}
						else{
							echo"<option value='0;(vide)'>(vide)</option>";
						}
						$req="SELECT DISTINCT trame_origine.Id, trame_origine.Libelle FROM trame_anomalie LEFT JOIN trame_origine on trame_anomalie.Id_Origine=trame_origine.Id WHERE trame_anomalie.Id_Origine<>0 AND trame_anomalie.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowOrigine=mysqli_fetch_array($result)){
								echo "<option value=\"".$rowOrigine['Id'].";".$rowOrigine['Libelle']."\">".$rowOrigine['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Responsible";}else{echo "Responsable";} ?></td>
			<td>
				<select id="responsable" name="responsable">
					<?php
						echo"<option value=''></option>";
						if($_SESSION['Langue']=="EN"){
							echo"<option value='0;(empty)'>(empty)</option>";
						}
						else{
							echo"<option value='0;(vide)'>(vide)</option>";
						}
						$req="SELECT DISTINCT trame_responsable.Id, trame_responsable.Libelle FROM trame_anomalie LEFT JOIN trame_responsable on trame_anomalie.Id_Responsable=trame_responsable.Id WHERE trame_anomalie.Id_Origine<>0 AND trame_anomalie.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowResp=mysqli_fetch_array($result)){
								echo "<option value=\"".$rowResp['Id'].";".$rowResp['Libelle']."\">".$rowResp['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Creator";}else{echo "Créateur";} ?></td>
			<td>
				<select id="createur" name="createur">
					<?php
						echo"<option value=''></option>";
						$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM trame_anomalie LEFT JOIN new_rh_etatcivil on trame_anomalie.Id_Createur=new_rh_etatcivil.Id WHERE trame_anomalie.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowPrepa=mysqli_fetch_array($result)){
								echo "<option value=\"".$rowPrepa['Id'].";".$rowPrepa['Nom']." ".$rowPrepa['Prenom']."\">".$rowPrepa['Nom']." ".$rowPrepa['Prenom']."</option>";
							}
						}
					?>
				</select>
			</td>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Error family";}else{echo "Famille d'erreur";} ?></td>
			<td>
				<select id="familleErreur" name="familleErreur">
					<?php
						echo"<option value=''></option>";
						if($_SESSION['Langue']=="EN"){
							echo"<option value='0;(empty)'>(empty)</option>";
						}
						else{
							echo"<option value='0;(vide)'>(vide)</option>";
						}
						$req="SELECT trame_familleerreur.Id, trame_familleerreur.Libelle FROM (SELECT Id_FamilleErreur1 AS Id_Famille FROM trame_anomalie WHERE Id_FamilleErreur1<>0 AND trame_anomalie.Id_Prestation=".$_SESSION['Id_PrestationTR']." UNION SELECT Id_FamilleErreur2 AS Id_Famille FROM trame_anomalie WHERE Id_FamilleErreur2<>0 AND trame_anomalie.Id_Prestation=".$_SESSION['Id_PrestationTR'].") AS tab_anomalie LEFT JOIN trame_familleerreur ON tab_anomalie.Id_Famille=trame_familleerreur.Id ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowResp=mysqli_fetch_array($result)){
								echo "<option value=\"".$rowResp['Id'].";".$rowResp['Libelle']."\">".$rowResp['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Date de début";} ?></td>
			<td>
				<input type="date" name="dateDebut" size="10" value=""/>
			</td>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date de fin";} ?></td>
			<td>
				<input type="date" name="dateFin"  size="10" value=""/>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
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
</body>
</html>