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
			opener.location="Liste_PNE.php";
		}
		function FermerEtRecharger2(){
			opener.location="Liste_PNE.php";
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
	if($_POST['msn']<>"" && strpos($_SESSION['MSN_PNE2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['MSN_PNE']=$_SESSION['MSN_PNE'].$_POST['msn'].$btn;
		$_SESSION['MSN_PNE2']=$_SESSION['MSN_PNE2'].$_POST['msn'].";";
	}
	if($_POST['numFormA']<>"" && strpos($_SESSION['FormA2'],$_POST['numFormA'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numFormA','".$_POST['numFormA']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['FormA']=$_SESSION['FormA'].$_POST['numFormA'].$btn;
		$_SESSION['FormA2']=$_SESSION['FormA2'].$_POST['numFormA'].";";
	}
	if($_POST['poste']<>"" && strpos($_SESSION['Poste2'],$_POST['poste'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('poste','".$_POST['poste']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Poste']=$_SESSION['Poste'].$_POST['poste'].$btn;
		$_SESSION['Poste2']=$_SESSION['Poste2'].$_POST['poste'].";";
	}
	$left="_".substr($_POST['pole'],0,strpos($_POST['pole'],";"));
	if($_POST['pole']<>"" && strpos($_SESSION['Pole2'],$left.";")===false){
		$right=substr($_POST['pole'],strpos($_POST['pole'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('pole','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Pole']=$_SESSION['Pole'].$right.$btn;
		$_SESSION['Pole2']=$_SESSION['Pole2'].$left.";";
	}
	$left="_".substr($_POST['zone'],0,strpos($_POST['zone'],";"));
	if($_POST['zone']<>"" && strpos($_SESSION['Zone_PNE2'],$left.";")===false){
		$right=substr($_POST['zone'],strpos($_POST['zone'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('zone','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Zone_PNE']=$_SESSION['Zone_PNE'].$right.$btn;
		$_SESSION['Zone_PNE2']=$_SESSION['Zone_PNE2'].$left.";";
	}
	$left="_".substr($_POST['compagnon'],0,strpos($_POST['compagnon'],";"));
	if($_POST['compagnon']<>"" && strpos($_SESSION['Compagnon2'],$left.";")===false){
		$right=substr($_POST['compagnon'],strpos($_POST['compagnon'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('compagnon','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Compagnon']=$_SESSION['Compagnon'].$right.$btn;
		$_SESSION['Compagnon2']=$_SESSION['Compagnon2'].$left.";";
	}
	$left="_".substr($_POST['createur'],0,strpos($_POST['createur'],";"));
	if($_POST['createur']<>"" && strpos($_SESSION['Id_CreateurPNE2'],$left.";")===false){
		$right=substr($_POST['createur'],strpos($_POST['createur'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createur','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Id_CreateurPNE']=$_SESSION['Id_CreateurPNE'].$right.$btn;
		$_SESSION['Id_CreateurPNE2']=$_SESSION['Id_CreateurPNE2'].$left.";";
	}
	if($_POST['numEIC']<>"" && strpos($_SESSION['NumEIC2'],$_POST['numEIC'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numEIC','".$_POST['numEIC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['NumEIC']=$_SESSION['NumEIC'].$_POST['numEIC'].$btn;
		$_SESSION['NumEIC2']=$_SESSION['NumEIC2'].$_POST['numEIC'].";";
	}
	$left=substr($_POST['vacation'],0,strpos($_POST['vacation'],";"));
	if($_POST['vacation']<>"" && strpos($_SESSION['VacationPNE2'],$left.";")===false){
		$right=substr($_POST['vacation'],strpos($_POST['vacation'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['VacationPNE']=$_SESSION['VacationPNE'].$right.$btn;
		$_SESSION['VacationPNE2']=$_SESSION['VacationPNE2'].$left.";";
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['DateDebutPNE']=$_POST['du'].$btn;
		$_SESSION['DateDebutPNE2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['DateFinPNE']=$_POST['au'].$btn;
		$_SESSION['DateFinPNE2']=$_POST['au'];
	}
	if(isset($_POST['sansDate'])){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('SansDate','')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['SansDatePNE']="oui".$btn;
		$_SESSION['SansDatePNE2']="oui";
	}
	$_SESSION['ModeFiltre']="";
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['MSN_PNE']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['MSN_PNE']);
			$_SESSION['MSN_PNE2']=str_replace($_GET['valeur'].";","",$_SESSION['MSN_PNE2']);
		}
		elseif($_GET['critere']=="numFormA"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numFormA','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['FormA']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['FormA']);
			$_SESSION['FormA2']=str_replace($_GET['valeur'].";","",$_SESSION['FormA2']);
		}
		elseif($_GET['critere']=="poste"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('poste','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Poste']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Poste']);
			$_SESSION['Poste2']=str_replace($_GET['valeur'].";","",$_SESSION['Poste2']);
		}
		elseif($_GET['critere']=="pole"){
			$_SESSION['Pole2']=str_replace($_GET['valeur'].";","",$_SESSION['Pole2']);
			$tab = explode(";",$_SESSION['Pole2']);
			$_SESSION['Pole']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('pole','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM new_competences_pole WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Pole'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="zone"){
			$_SESSION['Zone_PNE2']=str_replace($_GET['valeur'].";","",$_SESSION['Zone_PNE2']);
			$tab = explode(";",$_SESSION['Zone_PNE2']);
			$_SESSION['Zone_PNE']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('zone','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_zonedetravail WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Zone_PNE'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="compagnon"){
			$_SESSION['Compagnon2']=str_replace($_GET['valeur'].";","",$_SESSION['Compagnon2']);
			$tab = explode(";",$_SESSION['Compagnon2']);
			$_SESSION['Compagnon']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('compagnon','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Compagnon'].=$row['Nom']." ".$row['Prenom'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="createur"){
			$_SESSION['Id_CreateurPNE2']=str_replace($_GET['valeur'].";","",$_SESSION['Id_CreateurPNE2']);
			$tab = explode(";",$_SESSION['Id_CreateurPNE2']);
			$_SESSION['Id_CreateurPNE']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createur','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Id_CreateurPNE'].=$row['Nom']." ".$row['Prenom'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="numEIC"){
			$_SESSION['NumEIC2']=str_replace($_GET['valeur'].";","",$_SESSION['NumEIC2']);
			$tab = explode(";",$_SESSION['NumEIC2']);
			$_SESSION['NumEIC']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numEIC','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['NumEIC'].=$Id.$valeur;
				}
			}
		}
		if($_GET['critere']=="vacation"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			if($_GET['valeur']=="J"){$_SESSION['VacationPNE']=str_replace("Jour".$valeur,"",$_SESSION['VacationPNE']);}
			elseif($_GET['valeur']=="S"){$_SESSION['VacationPNE']=str_replace("Soir".$valeur,"",$_SESSION['VacationPNE']);}
			elseif($_GET['valeur']=="N"){$_SESSION['VacationPNE']=str_replace("Nuit".$valeur,"",$_SESSION['VacationPNE']);}
			elseif($_GET['valeur']=="VSD Jour"){$_SESSION['VacationPNE']=str_replace("VSD Jour".$valeur,"",$_SESSION['VacationPNE']);}
			elseif($_GET['valeur']=="VSD Nuit"){$_SESSION['VacationPNE']=str_replace("VSD Nuit".$valeur,"",$_SESSION['VacationPNE']);}
			$_SESSION['VacationPNE2']=str_replace($_GET['valeur'].";","",$_SESSION['VacationPNE2']);
		}
		if($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateDebutPNE']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateDebutPNE']);
			$_SESSION['DateDebutPNE2']=str_replace($_GET['valeur'],"",$_SESSION['DateDebutPNE2']);
		}
		if($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['DateFinPNE']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['DateFinPNE']);
			$_SESSION['DateFinPNE2']=str_replace($_GET['valeur'],"",$_SESSION['DateFinPNE2']);
		}
		if($_GET['critere']=="SansDate"){
			$_SESSION['SansDatePNE']="";
			$_SESSION['SansDatePNE2']="";
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
				&nbsp; N° Form A :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="numFormA" size="15" value="">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Vacation :
			</td>
			<td width=80%>
				<select name="vacation">
					<option name="" value=""></option>
					<option name="J;Jour" value="J;Jour">Jour</option>
					<option name="S;Soir" value="S;Soir">Soir</option>
					<option name="N;Nuit" value="N;Nuit">Nuit</option>
					<option name="VSD Jour;VSD Jour" value="VSD Jour;VSD Jour">VSD Jour</option>
					<option name="VSD Nuit;VSD Nuit" value="VSD Nuit;VSD Nuit">VSD Nuit</option>
				</select>
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
				&nbsp; Sans date :
			</td>
			<td width=80%>
				<input type="checkbox" style="text-align:center;" name="sansDate" value="sansDate">
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Poste :
			</td>
			<td width=80%>
				<select name="poste">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT Poste FROM sp_poste_pole ORDER BY Poste;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option name='".$row['Poste']."' value='".$row['Poste']."'>".$row['Poste']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Pôle :
			</td>
			<td width=80%>
				<select name="pole">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT Id, Libelle FROM new_competences_pole WHERE (Id IN (1,2,3,5,6,42) AND Actif=0 AND Id_Prestation=255) OR Id=176 ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option name='".$row['Id']."' value='".$row['Id'].";".$row['Libelle']."'>".$row['Libelle']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width=20%>
				&nbsp; Zone :
			</td>
			<td width=80%>
				<select name="zone">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_dossier.Id_ZoneDeTravail AS Id, (SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail ";
					$req.="WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Libelle FROM sp_dossier ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Libelle="(vide)";
							if($row['Id']<>0){$Libelle=$row['Libelle'];}
							echo "<option name='".$row['Id']."' value='".$row['Id'].";".$Libelle."'>".$Libelle."</option>";
						}
					}
					?>
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
					$req.="FROM sp_pne INNER JOIN new_rh_etatcivil ON sp_pne.Id_Createur=new_rh_etatcivil.Id ORDER BY new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom;";
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
		<tr>
			<td width=20%>
				&nbsp; Compagnon :
			</td>
			<td width=80%>
				<select name="compagnon">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
					$req.="FROM sp_ficheintervention INNER JOIN new_rh_etatcivil ON sp_pne.Id_Compagnon=new_rh_etatcivil.Id ORDER BY new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom;";
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
		<tr>
			<td width=20%>
				&nbsp; N° d'eic :
			</td>
			<td width=80%>
				<input type="texte" style="text-align:center;" name="numEIC" size="20" value="">
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