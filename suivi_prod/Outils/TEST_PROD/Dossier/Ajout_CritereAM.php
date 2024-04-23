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
			opener.location="Liste_AM.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_AM.php";
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
	if($_POST['msn']<>"" && strpos($_SESSION['AMMSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMMSN']=$_SESSION['AMMSN'].$_POST['msn'].$btn;
		$_SESSION['AMMSN2']=$_SESSION['AMMSN2'].$_POST['msn'].";";
	}
	if($_POST['numAMNC']<>"" && strpos($_SESSION['AMNumAMNC2'],$_POST['numAMNC'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numAMNC','".$_POST['numAMNC']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMNumAMNC']=$_SESSION['AMNumAMNC'].$_POST['numAMNC'].$btn;
		$_SESSION['AMNumAMNC2']=$_SESSION['AMNumAMNC2'].$_POST['numAMNC'].";";
	}
	if($_POST['omAssocie']<>"" && strpos($_SESSION['AMOMAssocie2'],$_POST['omAssocie'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('omAssocie','".$_POST['omAssocie']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMOMAssocie']=$_SESSION['AMOMAssocie'].$_POST['omAssocie'].$btn;
		$_SESSION['AMOMAssocie2']=$_SESSION['AMOMAssocie2'].$_POST['omAssocie'].";";
	}
	$left=substr($_POST['imputationAAA'],0,strpos($_POST['imputationAAA'],";"));
	if($_POST['imputationAAA']<>"" && strpos($_SESSION['AMImputationAAA2'],$left.";")===false){
		$right=substr($_POST['imputationAAA'],strpos($_POST['imputationAAA'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputationAAA','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMImputationAAA']=$_SESSION['AMImputationAAA'].$right.$btn;
		$_SESSION['AMImputationAAA2']=$_SESSION['AMImputationAAA2'].$left.";";
	}
	$left=substr($_POST['ncMajeure'],0,strpos($_POST['ncMajeure'],";"));
	if($_POST['ncMajeure']<>"" && strpos($_SESSION['AMNCMajeure2'],$left.";")===false){
		$right=substr($_POST['ncMajeure'],strpos($_POST['ncMajeure'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('ncMajeure','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMNCMajeure']=$_SESSION['AMNCMajeure'].$right.$btn;
		$_SESSION['AMNCMajeure2']=$_SESSION['AMNCMajeure2'].$left.";";
	}
	$left=substr($_POST['recurrence'],0,strpos($_POST['recurrence'],";"));
	if($_POST['recurrence']<>"" && strpos($_SESSION['AMRecurrence2'],$left.";")===false){
		$right=substr($_POST['recurrence'],strpos($_POST['recurrence'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('recurrence','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMRecurrence']=$_SESSION['AMRecurrence'].$right.$btn;
		$_SESSION['AMRecurrence2']=$_SESSION['AMRecurrence2'].$left.";";
	}
	$left=substr($_POST['type'],0,strpos($_POST['type'],";"));
	if($_POST['type']<>"" && strpos($_SESSION['AMType2'],$left.";")===false){
		$right=substr($_POST['type'],strpos($_POST['type'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('type','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMType']=$_SESSION['AMType'].$right.$btn;
		$_SESSION['AMType2']=$_SESSION['AMType2'].$left.";";
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMDu']=$_POST['du'].$btn;
		$_SESSION['AMDu2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMAu']=$_POST['au'].$btn;
		$_SESSION['AMAu2']=$_POST['au'];
	}
	$_SESSION['AMPage']="0";
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['AMMSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['AMMSN']);
			$_SESSION['AMMSN2']=str_replace($_GET['valeur'].";","",$_SESSION['AMMSN2']);
		}
		elseif($_GET['critere']=="numAMNC"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numAMNC','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['AMNumAMNC']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['AMNumAMNC']);
			$_SESSION['AMNumAMNC2']=str_replace($_GET['valeur'].";","",$_SESSION['AMNumAMNC2']);
		}
		elseif($_GET['critere']=="omAssocie"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('omAssocie','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['AMOMAssocie']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['AMOMAssocie']);
			$_SESSION['AMOMAssocie2']=str_replace($_GET['valeur'].";","",$_SESSION['AMOMAssocie2']);
		}
		elseif($_GET['critere']=="type"){
			$_SESSION['AMType2']=str_replace($_GET['valeur'].";","",$_SESSION['AMType2']);
			$tab = explode(";",$_SESSION['AMType2']);
			$_SESSION['AMType']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('type','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrtype WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['AMType'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="imputationAAA"){
			$_SESSION['AMImputationAAA2']=str_replace($_GET['valeur'].";","",$_SESSION['AMImputationAAA2']);
			$tab = explode(";",$_SESSION['AMImputationAAA2']);
			$_SESSION['AMImputationAAA']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputationAAA','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($Id==0){
						$_SESSION['AMImputationAAA'].="Non".$valeur;
					}
					elseif($Id==1){
						$_SESSION['AMImputationAAA'].="Oui".$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="ncMajeure"){
			$_SESSION['AMNCMajeure2']=str_replace($_GET['valeur'].";","",$_SESSION['AMNCMajeure2']);
			$tab = explode(";",$_SESSION['AMNCMajeure2']);
			$_SESSION['AMNCMajeure']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('ncMajeure','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($Id==0){
						$_SESSION['AMNCMajeure'].="Non".$valeur;
					}
					elseif($Id==1){
						$_SESSION['AMNCMajeure'].="Oui".$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="recurrence"){
			$_SESSION['AMRecurrence2']=str_replace($_GET['valeur'].";","",$_SESSION['AMRecurrence2']);
			$tab = explode(";",$_SESSION['AMRecurrence2']);
			$_SESSION['AMRecurrence']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('recurrence','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($Id==0){
						$_SESSION['AMRecurrence'].="Non".$valeur;
					}
					elseif($Id==1){
						$_SESSION['AMRecurrence'].="Oui".$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['AMDu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['AMDu']);
			$_SESSION['AMDu2']=str_replace($_GET['valeur'],"",$_SESSION['AMDu2']);
		}
		elseif($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['AMAu']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['AMAu']);
			$_SESSION['AMAu2']=str_replace($_GET['valeur'],"",$_SESSION['AMAu2']);
		}
		$_SESSION['AMPage']="0";
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereAM.php">
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
					$req="SELECT DISTINCT sp_atram.Id_Type, sp_atrtype.Libelle FROM sp_atram LEFT JOIN sp_atrtype ON sp_atram.Id_Type = sp_atrtype.Id WHERE sp_atram.Id_Prestation=-16 ORDER BY Libelle;";
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
				&nbsp; N° AM/NC :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numAMNC" size="15" value="">
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
				&nbsp; NC majeure :
			</td>
			<td >
				<select name="ncMajeure">
					<option value=""></option>
					<option value="1;Oui">Oui</option>
					<option value="0;Non">Non</option>
				</select>
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