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
			opener.location="Extract.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Extract.php";
			window.close();
		}
		function ChangerDate(){
			document.getElementById("dateDebut").value="";
			document.getElementById("dateFin").value="";
			if(document.getElementById("creneau").value=="AUJOURD'HUI"){
				document.getElementById("dateDebut").value=document.getElementById("aujourdhui").value;
				document.getElementById("dateFin").value=document.getElementById("aujourdhui").value;
			}
			else if(document.getElementById("creneau").value=="SEMAINE"){
				document.getElementById("dateDebut").value=document.getElementById("debutSemaine").value;
				document.getElementById("dateFin").value=document.getElementById("finSemaine").value;
			}
			else if(document.getElementById("creneau").value=="MOIS"){
				document.getElementById("dateDebut").value=document.getElementById("debutMois").value;
				document.getElementById("dateFin").value=document.getElementById("finMois").value;
			}
			else if(document.getElementById("creneau").value=="TRIMESTRE"){
				document.getElementById("dateDebut").value=document.getElementById("debutTrimestre").value;
				document.getElementById("dateFin").value=document.getElementById("finTrimestre").value;
			}
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$debutSemaine=date("Y-m-d",strtotime("last Monday"));
$finSemaine=date("Y-m-d",strtotime("next sunday"));
$debutMois=date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));
$finMois=date("Y-m-d",mktime(0,0,0,date("m")+1,0,date("Y")));
if(date("m")<=3){
	$debutTrimestre=date("Y-m-d",mktime(0,0,0,1,1,date("Y")));
	$finTrimestre=date("Y-m-d",mktime(0,0,0,4,0,date("Y")));
}
elseif(date("m")<=6){
	$debutTrimestre=date("Y-m-d",mktime(0,0,0,4,1,date("Y")));
	$finTrimestre=date("Y-m-d",mktime(0,0,0,7,0,date("Y")));
}
elseif(date("m")<=9){
	$debutTrimestre=date("Y-m-d",mktime(0,0,0,7,1,date("Y")));
	$finTrimestre=date("Y-m-d",mktime(0,0,0,10,0,date("Y")));
}
else{
	$debutTrimestre=date("Y-m-d",mktime(0,0,0,10,1,date("Y")));
	$finTrimestre=date("Y-m-d",mktime(0,0,0,1,0,date("Y")+1));
}
 Ecrire_Code_JS_Init_Date(); 
 if($_POST){
	if($_POST['dateDebut']<>"" && strpos($_SESSION['EXTRACT_DateDebut2'],$_POST['dateDebut'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateDebut','".$_POST['dateDebut']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_DateDebut']=$_POST['dateDebut'].$btn;
		$_SESSION['EXTRACT_DateDebut2']=$_POST['dateDebut'];
	}
	if($_POST['dateFin']<>"" && strpos($_SESSION['EXTRACT_DateFin2'],$_POST['dateFin'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateFin','".$_POST['dateFin']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_DateFin']=$_POST['dateFin'].$btn;
		$_SESSION['EXTRACT_DateFin2']=$_POST['dateFin'];
	}
	$left=substr($_POST['wp'],0,strpos($_POST['wp'],";"));
	if($_POST['wp']<>"" && strpos($_SESSION['EXTRACT_WP2'],$left.";")===false){
		$right=substr($_POST['wp'],strpos($_POST['wp'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('wp','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_WP']=$_SESSION['EXTRACT_WP'].$right.$btn;
		$_SESSION['EXTRACT_WP2']=$_SESSION['EXTRACT_WP2'].$left.";";
	}
	$left=substr($_POST['statut'],0,strpos($_POST['statut'],";"));
	if($_POST['statut']<>"" && strpos($_SESSION['EXTRACT_Statut2'],$left.";")===false){
		$right=substr($_POST['statut'],strpos($_POST['statut'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statut','".$_POST['statut']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['EXTRACT_Statut']=$_SESSION['EXTRACT_Statut'].$right.$btn;
		$_SESSION['EXTRACT_Statut2']=$_SESSION['EXTRACT_Statut2'].$left.";";
	}
	if(isset($_POST['livrableCC'])){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('livrableCC','V')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		if($_SESSION['Langue']=="EN"){$_SESSION['EXTRACT_Controle']="Yes".$btn;}
		else{$_SESSION['EXTRACT_Controle']="Oui".$btn;}
		$_SESSION['EXTRACT_Controle2']="1";
	}
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="dateDebut"){
			$_SESSION['EXTRACT_DateDebut']="";
			$_SESSION['EXTRACT_DateDebut2']="";
		}
		elseif($_GET['critere']=="dateFin"){
			$_SESSION['EXTRACT_DateFin']="";
			$_SESSION['EXTRACT_DateFin2']="";
		}
		elseif($_GET['critere']=="livrableCC"){
			$_SESSION['EXTRACT_Controle']="";
			$_SESSION['EXTRACT_Controle2']="";
		}
		elseif($_GET['critere']=="wp"){
			$_SESSION['EXTRACT_WP2']=str_replace($_GET['valeur'].";","",$_SESSION['EXTRACT_WP2']);
			$tab = explode(";",$_SESSION['EXTRACT_WP2']);
			$_SESSION['EXTRACT_WP']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('wp','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_wp WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['EXTRACT_WP'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="statut"){
			$left=substr($_GET['valeur'],0,strpos($_GET['valeur'],";"));
			$right=substr($_GET['valeur'],strpos($_GET['valeur'],";")+1);
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statut','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['EXTRACT_Statut']=str_replace($right.$valeur,"",$_SESSION['EXTRACT_Statut']);
			$_SESSION['EXTRACT_Statut2']=str_replace($left.";","",$_SESSION['EXTRACT_Statut2']);
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="left">
<form class="test" method="POST" action="Ajout_CritereExtractTE.php">
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
		<tr style="display:none;">
			<td>
				<input type="texte" name="aujourdhui" id="aujourdhui"  size="10" value="<?php echo AfficheDateFR($DateJour); ?>"/>
				<input type="texte" name="debutSemaine" id="debutSemaine"  size="10" value="<?php echo AfficheDateFR($debutSemaine); ?>"/>
				<input type="texte" name="finSemaine" id="finSemaine"  size="10" value="<?php echo AfficheDateFR($finSemaine); ?>"/>
				<input type="texte" name="debutMois" id="debutMois"  size="10" value="<?php echo AfficheDateFR($debutMois); ?>"/>
				<input type="texte" name="finMois" id="finMois"  size="10" value="<?php echo AfficheDateFR($finMois); ?>"/>
				<input type="texte" name="debutTrimestre" id="debutTrimestre"  size="10" value="<?php echo AfficheDateFR($debutTrimestre); ?>"/>
				<input type="texte" name="finTrimestre" id="finTrimestre"  size="10" value="<?php echo AfficheDateFR($finTrimestre); ?>"/>
			</td>
		</tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Slot";}else{echo "Créneau";} ?></td>
			<td>
				<select name="creneau" id="creneau" onclick="ChangerDate()">
					<option value=""></option>
					<option value="AUJOURD'HUI"><?php if($_SESSION['Langue']=="EN"){echo "Today";}else{echo "Aujourd'hui";}?></option>
					<option value="SEMAINE"><?php if($_SESSION['Langue']=="EN"){echo "This week";}else{echo "Cette semaine";}?></option>
					<option value="MOIS"><?php if($_SESSION['Langue']=="EN"){echo "This month";}else{echo "Ce mois";}?></option>
					<option value="TRIMESTRE"><?php if($_SESSION['Langue']=="EN"){echo "This quarter";}else{echo "Ce trimestre";}?></option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Date de début";} ?></td>
			<td>
				<input type="date" name="dateDebut" id="dateDebut" size="10" value=""/>
			</td>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date de fin";} ?></td>
			<td>
				<input type="date" name="dateFin" id="dateFin"  size="10" value=""/>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Status";}else{echo "Statut";} ?></td>
			<td>
				<select name="statut">
					<option value=""></option>
					<option value="BLOQUE;<?php if($_SESSION['Langue']=="EN"){echo "BLOCKED";}else{echo "BLOQUE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "BLOCKED";}else{echo "BLOQUE";}?></option>
					<option value="EN ATTENTE;<?php if($_SESSION['Langue']=="EN"){echo "WAITING";}else{echo "EN ATTENTE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "WAITING";}else{echo "EN ATTENTE";}?></option>
					<option value="STAND BY;<?php if($_SESSION['Langue']=="EN"){echo "STAND BY";}else{echo "STAND BY";}?>"><?php if($_SESSION['Langue']=="EN"){echo "STAND BY";}else{echo "STAND BY";}?></option>
					<option value="EN COURS;<?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS";}else{echo "EN COURS";}?>"><?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS";}else{echo "EN COURS";}?></option>
					<option value="AC;<?php if($_SESSION['Langue']=="EN"){echo "AUTO CONTROL";}else{echo "AUTO-CONTROLE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "AUTO CONTROL";}else{echo "AUTO-CONTROLE";}?></option>
					<option value="CONTROLE;<?php if($_SESSION['Langue']=="EN"){echo "CONTROL";}else{echo "CONTROLE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "CONTROL";}else{echo "CONTROLE";}?></option>
					<option value="REC;<?php if($_SESSION['Langue']=="EN"){echo "CONTROL AGAIN";}else{echo "RECONTROLE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "CONTROL AGAIN";}else{echo "RECONTROLE";}?></option>
					<option value="A VALIDER;<?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?>"><?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?></option>
					<option value="VALIDE;<?php if($_SESSION['Langue']=="EN"){echo "VALIDATED";}else{echo "VALIDE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "VALIDATED";}else{echo "VALIDE";}?></option>
					<option value="REFUSE;<?php if($_SESSION['Langue']=="EN"){echo "REFUSED";}else{echo "REFUSE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "RETURN";}else{echo "RETOURNE";}?></option>
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
								echo "<option value=\"".$rowWP['Id'].";".$rowWP['Libelle']."\">".$rowWP['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle" colspan="4">&nbsp;
			<input type="checkbox" name="livrableCC" id="livrableCC" value="">
			<?php if($_SESSION['Langue']=="EN"){echo "Only deliverables controlled or to be controlled";}else{echo "Uniquement les livrables contrôlés ou à contrôler";} ?></td>
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