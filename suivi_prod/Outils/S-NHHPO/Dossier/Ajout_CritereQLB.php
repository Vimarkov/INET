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
	<script language="javascript" src="MSN.js"></script>
	<script>
		function Recharger(){
			opener.location="Liste_CQLB.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_CQLB.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
 Ecrire_Code_JS_Init_Date(); 
 if($_POST){
	if($_POST['msn']<>"" && strpos($_SESSION['CQLBMSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBMSN']=$_SESSION['CQLBMSN'].$_POST['msn'].$btn;
		$_SESSION['CQLBMSN2']=$_SESSION['CQLBMSN2'].$_POST['msn'].";";
	}
	if($_POST['omAssocie']<>"" && strpos($_SESSION['CQLBOMAssocie2'],$_POST['omAssocie'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('omAssocie','".$_POST['omAssocie']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBOMAssocie']=$_SESSION['CQLBOMAssocie'].$_POST['omAssocie'].$btn;
		$_SESSION['CQLBOMAssocie2']=$_SESSION['CQLBOMAssocie2'].$_POST['omAssocie'].";";
	}
	if($_POST['amAssociee']<>"" && strpos($_SESSION['CQLBAMAssociee2'],$_POST['amAssociee'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('amAssociee','".$_POST['amAssociee']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBAMAssociee']=$_SESSION['CQLBAMAssociee'].$_POST['amAssociee'].$btn;
		$_SESSION['CQLBAMAssociee2']=$_SESSION['CQLBAMAssociee2'].$_POST['amAssociee'].";";
	}
	if($_POST['numCQLB']<>"" && strpos($_SESSION['CQLBNumCQLB2'],$_POST['numCQLB'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numCQLB','".$_POST['numCQLB']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBNumCQLB']=$_SESSION['CQLBNumCQLB'].$_POST['numCQLB'].$btn;
		$_SESSION['CQLBNumCQLB2']=$_SESSION['CQLBNumCQLB2'].$_POST['numCQLB'].";";
	}
	if($_POST['numCV']<>"" && strpos($_SESSION['CQLBNumCV2'],$_POST['numCV'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numCV','".$_POST['numCV']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBNumCV']=$_SESSION['CQLBNumCV'].$_POST['numCV'].$btn;
		$_SESSION['CQLBNumCV2']=$_SESSION['CQLBNumCV2'].$_POST['numCV'].";";
	}
	$left=substr($_POST['imputationAAA'],0,strpos($_POST['imputationAAA'],";"));
	if($_POST['imputationAAA']<>"" && strpos($_SESSION['CQLBImputationAAA2'],$left.";")===false){
		$right=substr($_POST['imputationAAA'],strpos($_POST['imputationAAA'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputationAAA','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBImputationAAA']=$_SESSION['CQLBImputationAAA'].$right.$btn;
		$_SESSION['CQLBImputationAAA2']=$_SESSION['CQLBImputationAAA2'].$left.";";
	}
	$left=substr($_POST['recurrence'],0,strpos($_POST['recurrence'],";"));
	if($_POST['recurrence']<>"" && strpos($_SESSION['CQLBRecurrence2'],$left.";")===false){
		$right=substr($_POST['recurrence'],strpos($_POST['recurrence'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('recurrence','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBRecurrence']=$_SESSION['CQLBRecurrence'].$right.$btn;
		$_SESSION['CQLBRecurrence2']=$_SESSION['CQLBRecurrence2'].$left.";";
	}
	$left=substr($_POST['type'],0,strpos($_POST['type'],";"));
	if($_POST['type']<>"" && strpos($_SESSION['CQLBType2'],$left.";")===false){
		$right=substr($_POST['type'],strpos($_POST['type'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('type','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBType']=$_SESSION['CQLBType'].$right.$btn;
		$_SESSION['CQLBType2']=$_SESSION['CQLBType2'].$left.";";
	}
	$left=substr($_POST['localisation'],0,strpos($_POST['localisation'],";"));
	if($_POST['localisation']<>"" && strpos($_SESSION['CQLBLocalisation2'],$left.";")===false){
		$right=substr($_POST['localisation'],strpos($_POST['localisation'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('localisation','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBLocalisation']=$_SESSION['CQLBLocalisation'].$right.$btn;
		$_SESSION['CQLBLocalisation2']=$_SESSION['CQLBLocalisation2'].$left.";";
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBDu']=$_POST['du'].$btn;
		$_SESSION['CQLBDu2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CQLBAu']=$_POST['au'].$btn;
		$_SESSION['CQLBAu2']=$_POST['au'];
	}
	$_SESSION['CQLBPage']="0";
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['CQLBMSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['CQLBMSN']);
			$_SESSION['CQLBMSN2']=str_replace($_GET['valeur'].";","",$_SESSION['CQLBMSN2']);
		}
		elseif($_GET['critere']=="omAssocie"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('omAssocie','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['CQLBOMAssocie']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['CQLBOMAssocie']);
			$_SESSION['CQLBOMAssocie2']=str_replace($_GET['valeur'].";","",$_SESSION['CQLBOMAssocie2']);
		}
		elseif($_GET['critere']=="amAssociee"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('amAssociee','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['CQLBAMAssociee']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['CQLBAMAssociee']);
			$_SESSION['CQLBAMAssociee2']=str_replace($_GET['valeur'].";","",$_SESSION['CQLBAMAssociee2']);
		}
		elseif($_GET['critere']=="numCQLB"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numCQLB','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['CQLBNumCQLB']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['CQLBNumCQLB']);
			$_SESSION['CQLBNumCQLB2']=str_replace($_GET['valeur'].";","",$_SESSION['CQLBNumCQLB2']);
		}
		elseif($_GET['critere']=="numCV"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numCV','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['CQLBNumCV']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['CQLBNumCV']);
			$_SESSION['CQLBNumCV2']=str_replace($_GET['valeur'].";","",$_SESSION['CQLBNumCV2']);
		}
		elseif($_GET['critere']=="type"){
			$_SESSION['CQLBType2']=str_replace($_GET['valeur'].";","",$_SESSION['CQLBType2']);
			$tab = explode(";",$_SESSION['CQLBType2']);
			$_SESSION['CQLBType']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('type','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrtype WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['CQLBType'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="localisation"){
			$_SESSION['CQLBLocalisation2']=str_replace($_GET['valeur'].";","",$_SESSION['CQLBLocalisation2']);
			$tab = explode(";",$_SESSION['CQLBLocalisation2']);
			$_SESSION['CQLBLocalisation']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('localisation','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrlocalisation WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['CQLBLocalisation'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="imputationAAA"){
			$_SESSION['CQLBImputationAAA2']=str_replace($_GET['valeur'].";","",$_SESSION['CQLBImputationAAA2']);
			$tab = explode(";",$_SESSION['CQLBImputationAAA2']);
			$_SESSION['CQLBImputationAAA']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputationAAA','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($Id==0){
						$_SESSION['CQLBImputationAAA'].="Non".$valeur;
					}
					elseif($Id==1){
						$_SESSION['CQLBImputationAAA'].="Oui".$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="recurrence"){
			$_SESSION['CQLBRecurrence2']=str_replace($_GET['valeur'].";","",$_SESSION['CQLBRecurrence2']);
			$tab = explode(";",$_SESSION['CQLBRecurrence2']);
			$_SESSION['CQLBRecurrence']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('recurrence','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($Id==0){
						$_SESSION['CQLBRecurrence'].="Non".$valeur;
					}
					elseif($Id==1){
						$_SESSION['CQLBRecurrence'].="Oui".$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['CQLBDu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['CQLBDu']);
			$_SESSION['CQLBDu2']=str_replace($_GET['valeur'],"",$_SESSION['CQLBDu2']);
		}
		elseif($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['CQLBAu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['CQLBAu']);
			$_SESSION['CQLBAu2']=str_replace($_GET['valeur'],"",$_SESSION['CQLBAu2']);
		}
		$_SESSION['CQLBPage']="0";
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereCQLB.php">
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
			<td width=20%>
				&nbsp; MSN :
			</td>
			<td> 
				<input onKeyUp="nombre(this)" type="texte" style="text-align:center;" name="msn" size="10" value="">
			</td>
			<td width=20%>
				&nbsp; Type :
			</td>
			<td >
				<select name="type">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_atram.Id_Type, sp_atrtype.Libelle FROM sp_atram LEFT JOIN sp_atrtype ON sp_atram.Id_Type = sp_atrtype.Id WHERE sp_atram.Id_Prestation=1242 ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option value='".$row['Id_Type'].";".$row['Libelle']."'>".$row['Libelle']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20%>
				&nbsp; Ordre de montage associé :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="omAssocie" size="10" value="">
			</td>
			<td width=20%>
				&nbsp; Imputation AAA :
			</td>
			<td >
				<select name="imputationAAA">
					<option value=""></option>
					<option value="1;Oui">Oui</option>
					<option value="0;Non">Non</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20%>
				&nbsp; N° CQLB :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numCQLB" size="10" value="">
			</td>
			<td width=20%>
				&nbsp; N° CV :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numCV" size="10" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20%>
				&nbsp; AM associée :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="amAssociee" size="10" value="">
			</td>
			<td width=20%>
				&nbsp; Récurrence :
			</td>
			<td >
				<select name="recurrence">
					<option value=""></option>
					<option value="1;Oui">Oui</option>
					<option value="0;Non">Non</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20%>
				&nbsp; Du :
			</td>
			<td> 
				<input type="date" style="text-align:center;" name="du" size="15" value="">
			</td>
			<td width=20%>
				&nbsp; Au :
			</td>
			<td> 
				<input type="date" style="text-align:center;" name="au" size="15" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20%>
				&nbsp; Localisation :
			</td>
			<td >
				<select name="localisation">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_atrcqlb.Id_Localisation, sp_atrlocalisation.Libelle FROM sp_atrcqlb LEFT JOIN sp_atrlocalisation ON sp_atrcqlb.Id_Localisation = sp_atrlocalisation.Id WHERE sp_atrcqlb.Id_Prestation=1242 ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option value='".$row['Id_Localisation'].";".$row['Libelle']."'>".$row['Libelle']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center" colspan="10">
				<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Ajouter">
			</td>
			
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
</form>
</table>
</body>
</html>