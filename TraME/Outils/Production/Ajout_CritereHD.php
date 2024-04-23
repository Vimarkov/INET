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
			opener.location="HorsDelais.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "HorsDelais.php";
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
	if($_POST['reference']<>"" && strpos($_SESSION['HorsDelais_Reference2'],$_POST['reference'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('reference','".$_POST['reference']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_Reference']=$_SESSION['HorsDelais_Reference'].$_POST['reference'].$btn;
		$_SESSION['HorsDelais_Reference2']=$_SESSION['HorsDelais_Reference2'].$_POST['reference'].";";
	}
	if($_POST['motCles']<>"" && strpos($_SESSION['HorsDelais_MotCles2'],$_POST['motCles'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('motCles','".$_POST['motCles']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_MotCles']=$_SESSION['HorsDelais_MotCles'].$_POST['motCles'].$btn;
		$_SESSION['HorsDelais_MotCles2']=$_SESSION['HorsDelais_MotCles2'].$_POST['motCles'].";";
	}
	$left=substr($_POST['wp'],0,strpos($_POST['wp'],";"));
	if($_POST['wp']<>"" && strpos($_SESSION['HorsDelais_WP2'],$left.";")===false){
		$right=substr($_POST['wp'],strpos($_POST['wp'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('wp','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_WP']=$_SESSION['HorsDelais_WP'].$right.$btn;
		$_SESSION['HorsDelais_WP2']=$_SESSION['HorsDelais_WP2'].$left.";";
	}
	$left=substr($_POST['tache'],0,strpos($_POST['tache'],";"));
	if($_POST['tache']<>"" && strpos($_SESSION['HorsDelais_Tache2'],$left.";")===false){
		$right=substr($_POST['tache'],strpos($_POST['tache'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('tache','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_Tache']=$_SESSION['HorsDelais_Tache'].$right.$btn;
		$_SESSION['HorsDelais_Tache2']=$_SESSION['HorsDelais_Tache2'].$left.";";
	}
	$left=substr($_POST['preparateur'],0,strpos($_POST['preparateur'],";"));
	if($_POST['preparateur']<>"" && strpos($_SESSION['HorsDelais_Preparateur2'],$left.";")===false){
		$right=substr($_POST['preparateur'],strpos($_POST['preparateur'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('preparateur','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_Preparateur']=$_SESSION['HorsDelais_Preparateur'].$right.$btn;
		$_SESSION['HorsDelais_Preparateur2']=$_SESSION['HorsDelais_Preparateur2'].$left.";";
	}
	$left=substr($_POST['statutPROD'],0,strpos($_POST['statutPROD'],";"));
	if($_POST['statutPROD']<>"" && strpos($_SESSION['HorsDelais_Statut2'],$left.";")===false){
		$right=substr($_POST['statutPROD'],strpos($_POST['statutPROD'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','".$_POST['statutPROD']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_Statut']=$_SESSION['HorsDelais_Statut'].$right.$btn;
		$_SESSION['HorsDelais_Statut2']=$_SESSION['HorsDelais_Statut2'].$left.";";
	}
	if($_POST['dateDebut']<>"" && strpos($_SESSION['HorsDelais_DateDebut2'],$_POST['dateDebut'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateDebut','".$_POST['dateDebut']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_DateDebut']=$_POST['dateDebut'].$btn;
		$_SESSION['HorsDelais_DateDebut2']=$_POST['dateDebut'];
	}
	if($_POST['dateFin']<>"" && strpos($_SESSION['HorsDelais_DateFin2'],$_POST['dateFin'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateFin','".$_POST['dateFin']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_DateFin']=$_POST['dateFin'].$btn;
		$_SESSION['HorsDelais_DateFin2']=$_POST['dateFin'];
	}
	$left=substr($_POST['responsableDelais'],0,strpos($_POST['responsableDelais'],";"));
	if($_POST['responsableDelais']<>"" && strpos($_SESSION['HorsDelais_RespDelais2'],$left.";")===false){
		$right=substr($_POST['responsableDelais'],strpos($_POST['responsableDelais'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('responsableDelais','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_RespDelais']=$_SESSION['HorsDelais_RespDelais'].$right.$btn;
		$_SESSION['HorsDelais_RespDelais2']=$_SESSION['HorsDelais_RespDelais2'].$left.";";
	}
	$left=substr($_POST['causeDelais'],0,strpos($_POST['causeDelais'],";"));
	if($_POST['causeDelais']<>"" && strpos($_SESSION['HorsDelais_CauseDelais2'],$left.";")===false){
		$right=substr($_POST['causeDelais'],strpos($_POST['causeDelais'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('causeDelais','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['HorsDelais_CauseDelais']=$_SESSION['HorsDelais_CauseDelais'].$right.$btn;
		$_SESSION['HorsDelais_CauseDelais2']=$_SESSION['HorsDelais_CauseDelais2'].$left.";";
	}
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="reference"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('reference','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['HorsDelais_Reference']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['HorsDelais_Reference']);
			$_SESSION['HorsDelais_Reference2']=str_replace($_GET['valeur'].";","",$_SESSION['HorsDelais_Reference2']);
		}
		elseif($_GET['critere']=="motCles"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('motCles','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['HorsDelais_MotCles']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['HorsDelais_MotCles']);
			$_SESSION['HorsDelais_MotCles2']=str_replace($_GET['valeur'].";","",$_SESSION['HorsDelais_MotCles2']);
		}
		elseif($_GET['critere']=="wp"){
			$_SESSION['HorsDelais_WP2']=str_replace($_GET['valeur'].";","",$_SESSION['HorsDelais_WP2']);
			$tab = explode(";",$_SESSION['HorsDelais_WP2']);
			$_SESSION['HorsDelais_WP']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('wp','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_wp WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['HorsDelais_WP'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="tache"){
			$_SESSION['HorsDelais_Tache2']=str_replace($_GET['valeur'].";","",$_SESSION['HorsDelais_Tache2']);
			$tab = explode(";",$_SESSION['HorsDelais_Tache2']);
			$_SESSION['HorsDelais_Tache']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('tache','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_tache WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['HorsDelais_Tache'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="preparateur"){
			$_SESSION['HorsDelais_Preparateur2']=str_replace($_GET['valeur'].";","",$_SESSION['HorsDelais_Preparateur2']);
			$tab = explode(";",$_SESSION['HorsDelais_Preparateur2']);
			$_SESSION['HorsDelais_Preparateur']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('preparateur','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['HorsDelais_Preparateur'].=$row['Nom']." ".$row['Prenom'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="statutPROD"){
			$left=substr($_GET['valeur'],0,strpos($_GET['valeur'],";"));
			$right=substr($_GET['valeur'],strpos($_GET['valeur'],";")+1);
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['HorsDelais_Statut']=str_replace($right.$valeur,"",$_SESSION['HorsDelais_Statut']);
			$_SESSION['HorsDelais_Statut2']=str_replace($left.";","",$_SESSION['HorsDelais_Statut2']);
		}
		elseif($_GET['critere']=="dateDebut"){
			$_SESSION['HorsDelais_DateDebut']="";
			$_SESSION['HorsDelais_DateDebut2']="";
		}
		elseif($_GET['critere']=="dateFin"){
			$_SESSION['HorsDelais_DateFin']="";
			$_SESSION['HorsDelais_DateFin2']="";
		}
		elseif($_GET['critere']=="responsableDelais"){
			$_SESSION['HorsDelais_RespDelais2']=str_replace($_GET['valeur'].";","",$_SESSION['HorsDelais_RespDelais2']);
			$tab = explode(";",$_SESSION['HorsDelais_RespDelais2']);
			$_SESSION['HorsDelais_RespDelais']="";
			foreach($tab as $Id){
				if($Id=="0"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('responsableDelais','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($_SESSION['Langue']=="EN"){
						$_SESSION['HorsDelais_RespDelais'].="(empty)".$valeur;
					}
					else{
						$_SESSION['HorsDelais_RespDelais'].="(vide)".$valeur;
					}
				}
				elseif($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('responsableDelais','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_responsabledelais WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['HorsDelais_RespDelais'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="causeDelais"){
			$_SESSION['HorsDelais_CauseDelais2']=str_replace($_GET['valeur'].";","",$_SESSION['HorsDelais_CauseDelais2']);
			$tab = explode(";",$_SESSION['HorsDelais_CauseDelais2']);
			$_SESSION['HorsDelais_CauseDelais']="";
			foreach($tab as $Id){
				if($Id=="0"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('causeDelais','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($_SESSION['Langue']=="EN"){
						$_SESSION['HorsDelais_CauseDelais'].="(empty)".$valeur;
					}
					else{
						$_SESSION['HorsDelais_CauseDelais'].="(vide)".$valeur;
					}
				}
				elseif($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('causeDelais','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_causedelais WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['HorsDelais_CauseDelais'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereHD.php">
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
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Keyword";}else{echo "Mot clés";} ?></td>
			<td colspan="4"> 
				<input type="texte" name="motCles" size="50" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
			<td colspan="4">
				<select id="wp" name="wp">
					<?php
						echo"<option value=''></option>";
						$req="SELECT DISTINCT trame_wp.Id, trame_wp.Libelle FROM trame_travaileffectue LEFT JOIN trame_wp on trame_travaileffectue.Id_WP=trame_wp.Id WHERE trame_travaileffectue.StatutDelai='KO' AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
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
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?></td>
			<td colspan="4">
				<select id="tache" name="tache" style="width:450px;">
					<?php
						echo"<option value=''></option>";
						$req="SELECT DISTINCT trame_tache.Id, trame_tache.Libelle FROM trame_travaileffectue LEFT JOIN trame_tache on trame_travaileffectue.Id_Tache=trame_tache.Id WHERE trame_travaileffectue.StatutDelai='KO' AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowTache=mysqli_fetch_array($result)){
								echo "<option value=\"".$rowTache['Id'].";".$rowTache['Libelle']."\">".$rowTache['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Manufacturing Engineer";}else{echo "Préparateur";} ?></td>
			<td>
				<select id="preparateur" name="preparateur">
					<?php
						echo"<option value=''></option>";
						$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM trame_travaileffectue LEFT JOIN new_rh_etatcivil on trame_travaileffectue.Id_Preparateur=new_rh_etatcivil.Id WHERE trame_travaileffectue.StatutDelai='KO' AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Nom, Prenom;";
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
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Status";}else{echo "Statut";} ?></td>
			<td>
				<select name="statutPROD">
					<option value=""></option>
					<option value="BLOQUE;<?php if($_SESSION['Langue']=="EN"){echo "BLOCKED";}else{echo "BLOQUE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "BLOCKED";}else{echo "BLOQUE";}?></option>
					<option value="EN COURS;<?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS";}else{echo "EN COURS";}?>"><?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS";}else{echo "EN COURS";}?></option>
					<option value="EN ATTENTE;<?php if($_SESSION['Langue']=="EN"){echo "WAITING";}else{echo "EN ATTENTE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "WAITING";}else{echo "EN ATTENTE";}?></option>
					<option value="STAND BY;<?php if($_SESSION['Langue']=="EN"){echo "STAND BY";}else{echo "STAND BY";}?>"><?php if($_SESSION['Langue']=="EN"){echo "STAND BY";}else{echo "STAND BY";}?></option>
					<option value="A VALIDER;<?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?>"><?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?></option>
					<option value="VALIDE;<?php if($_SESSION['Langue']=="EN"){echo "VALIDATED";}else{echo "VALIDE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "VALIDATED";}else{echo "VALIDE";}?></option>
					<option value="REFUSE;<?php if($_SESSION['Langue']=="EN"){echo "REFUSED";}else{echo "REFUSE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "RETURN";}else{echo "RETOURNE";}?></option>
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
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Responsable of delay";}else{echo "Responsable délais";} ?></td>
			<td colspan="4">
				<select id="responsableDelais" name="responsableDelais">
					<?php
						echo"<option value=''></option>";
						if($_SESSION['Langue']=="EN"){
							echo"<option value='0;(empty)'>(empty)</option>";
						}
						else{
							echo"<option value='0;(vide)'>(vide)</option>";
						}
						$req="SELECT DISTINCT trame_responsabledelais.Id, trame_responsabledelais.Libelle FROM trame_travaileffectue LEFT JOIN trame_responsabledelais on trame_travaileffectue.Id_ResponsableDelai=trame_responsabledelais.Id WHERE trame_responsabledelais.Id>0 AND trame_travaileffectue.StatutDelai='KO' AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
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
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Cause of delay";}else{echo "Cause délais";} ?></td>
			<td colspan="4">
				<select id="causeDelais" name="causeDelais">
					<?php
						echo"<option value=''></option>";
						if($_SESSION['Langue']=="EN"){
							echo"<option value='0;(empty)'>(empty)</option>";
						}
						else{
							echo"<option value='0;(vide)'>(vide)</option>";
						}
						$req="SELECT DISTINCT trame_causedelais.Id, trame_causedelais.Libelle FROM trame_travaileffectue LEFT JOIN trame_causedelais on trame_travaileffectue.Id_CauseDelai=trame_causedelais.Id WHERE trame_causedelais.Id>0 AND trame_travaileffectue.StatutDelai='KO' AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowCause=mysqli_fetch_array($result)){
								echo "<option value=\"".$rowCause['Id'].";".$rowCause['Libelle']."\">".$rowCause['Libelle']."</option>";
							}
						}
					?>
				</select>
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