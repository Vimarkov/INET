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
	if($_POST['numOF']<>"" && strpos($_SESSION['AMNumOF2'],$_POST['numOF'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numOF','".$_POST['numOF']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMNumOF']=$_SESSION['AMNumOF'].$_POST['numOF'].$btn;
		$_SESSION['AMNumOF2']=$_SESSION['AMNumOF2'].$_POST['numOF'].";";
	}
	if($_POST['numDERO']<>"" && strpos($_SESSION['AMNumDERO2'],$_POST['numDERO'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDERO','".$_POST['numDERO']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMNumDERO']=$_SESSION['AMNumDERO'].$_POST['numDERO'].$btn;
		$_SESSION['AMNumDERO2']=$_SESSION['AMNumDERO2'].$_POST['numDERO'].";";
	}
	$left=substr($_POST['localisation'],0,strpos($_POST['localisation'],";"));
	if($_POST['localisation']<>"" && strpos($_SESSION['AMLocalisation2'],$left.";")===false){
		$right=substr($_POST['localisation'],strpos($_POST['localisation'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('localisation','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMLocalisation']=$_SESSION['AMLocalisation'].$right.$btn;
		$_SESSION['AMLocalisation2']=$_SESSION['AMLocalisation2'].$left.";";
	}
	$left=substr($_POST['cote'],0,strpos($_POST['cote'],";"));
	if($_POST['cote']<>"" && strpos($_SESSION['AMCote2'],$left.";")===false){
		$right=substr($_POST['cote'],strpos($_POST['cote'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('cote','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMCote']=$_SESSION['AMCote'].$right.$btn;
		$_SESSION['AMCote2']=$_SESSION['AMCote2'].$left.";";
	}
	$left=substr($_POST['actionCurative'],0,strpos($_POST['actionCurative'],";"));
	if($_POST['actionCurative']<>"" && strpos($_SESSION['AMActionCurative2'],$left.";")===false){
		$right=substr($_POST['actionCurative'],strpos($_POST['actionCurative'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('actionCurative','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMActionCurative']=$_SESSION['AMActionCurative'].$right.$btn;
		$_SESSION['AMActionCurative2']=$_SESSION['AMActionCurative2'].$left.";";
	}
	$left=substr($_POST['produitImpacte'],0,strpos($_POST['produitImpacte'],";"));
	if($_POST['produitImpacte']<>"" && strpos($_SESSION['AMProduitImpacte2'],$left.";")===false){
		$right=substr($_POST['produitImpacte'],strpos($_POST['produitImpacte'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('produitImpacte','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMProduitImpacte']=$_SESSION['AMProduitImpacte'].$right.$btn;
		$_SESSION['AMProduitImpacte2']=$_SESSION['AMProduitImpacte2'].$left.";";
	}
	$left=substr($_POST['typeDefaut'],0,strpos($_POST['typeDefaut'],";"));
	if($_POST['typeDefaut']<>"" && strpos($_SESSION['AMTypeDefaut2'],$left.";")===false){
		$right=substr($_POST['typeDefaut'],strpos($_POST['typeDefaut'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('typeDefaut','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMTypeDefaut']=$_SESSION['AMTypeDefaut'].$right.$btn;
		$_SESSION['AMTypeDefaut2']=$_SESSION['AMTypeDefaut2'].$left.";";
	}
	if($_POST['origineAM']<>"" && strpos($_SESSION['AMOrigineAM2'],$_POST['origineAM'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('origineAM','".$_POST['origineAM']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMOrigineAM']=$_SESSION['AMOrigineAM'].$_POST['origineAM'].$btn;
		$_SESSION['AMOrigineAM2']=$_SESSION['AMOrigineAM2'].$_POST['origineAM'].";";
	}
	if($_POST['statut']<>"" && strpos($_SESSION['AMStatut2'],$_POST['statut'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statut','".$_POST['statut']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMStatut']=$_SESSION['AMStatut'].$_POST['statut'].$btn;
		$_SESSION['AMStatut2']=$_SESSION['AMStatut2'].$_POST['statut'].";";
	}
	$left=substr($_POST['recurrence'],0,strpos($_POST['recurrence'],";"));
	if($_POST['recurrence']<>"" && strpos($_SESSION['AMRecurrence2'],$left.";")===false){
		$right=substr($_POST['recurrence'],strpos($_POST['recurrence'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('recurrence','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMRecurrence']=$_SESSION['AMRecurrence'].$right.$btn;
		$_SESSION['AMRecurrence2']=$_SESSION['AMRecurrence2'].$left.";";
	}
	$left=substr($_POST['imputation'],0,strpos($_POST['imputation'],";"));
	if($_POST['imputation']<>"" && strpos($_SESSION['AMImputation2'],$left.";")===false){
		$right=substr($_POST['imputation'],strpos($_POST['imputation'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputation','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMImputation']=$_SESSION['AMImputation'].$right.$btn;
		$_SESSION['AMImputation2']=$_SESSION['AMImputation2'].$left.";";
	}
	$left=substr($_POST['moment'],0,strpos($_POST['moment'],";"));
	if($_POST['moment']<>"" && strpos($_SESSION['AMMoment2'],$left.";")===false){
		$right=substr($_POST['moment'],strpos($_POST['moment'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('moment','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['AMMoment']=$_SESSION['AMMoment'].$right.$btn;
		$_SESSION['AMMoment2']=$_SESSION['AMMoment2'].$left.";";
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
	$_SESSION['AMPage']=0;
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
		elseif($_GET['critere']=="numOF"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numOF','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['AMNumOF']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['AMNumOF']);
			$_SESSION['AMNumOF2']=str_replace($_GET['valeur'].";","",$_SESSION['AMNumOF2']);
		}
		elseif($_GET['critere']=="numDERO"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('numDERO','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['AMNumDERO']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['AMNumDERO']);
			$_SESSION['AMNumDERO2']=str_replace($_GET['valeur'].";","",$_SESSION['AMNumDERO2']);
		}
		elseif($_GET['critere']=="origineAM"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('origineAM','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['AMOrigineAM']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['AMOrigineAM']);
			$_SESSION['AMOrigineAM2']=str_replace($_GET['valeur'].";","",$_SESSION['AMOrigineAM2']);
		}
		elseif($_GET['critere']=="localisation"){
			$_SESSION['AMLocalisation2']=str_replace($_GET['valeur'].";","",$_SESSION['AMLocalisation2']);
			$tab = explode(";",$_SESSION['AMLocalisation2']);
			$_SESSION['AMLocalisation']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('localisation','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrlocalisation WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['AMLocalisation'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="cote"){
			$_SESSION['AMCote2']=str_replace($_GET['valeur'].";","",$_SESSION['AMCote2']);
			$tab = explode(";",$_SESSION['AMCote2']);
			$_SESSION['AMCote']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('cote','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrcote WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['AMCote'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="actionCurative"){
			$_SESSION['AMActionCurative2']=str_replace($_GET['valeur'].";","",$_SESSION['AMActionCurative2']);
			$tab = explode(";",$_SESSION['AMActionCurative2']);
			$_SESSION['AMActionCurative']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('actionCurative','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atractioncurative WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['AMActionCurative'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="produitImpacte"){
			$_SESSION['AMProduitImpacte2']=str_replace($_GET['valeur'].";","",$_SESSION['AMProduitImpacte2']);
			$tab = explode(";",$_SESSION['AMProduitImpacte2']);
			$_SESSION['AMProduitImpacte']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('produitImpacte','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrproduitimpacte WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['AMProduitImpacte'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="typeDefaut"){
			$_SESSION['AMTypeDefaut2']=str_replace($_GET['valeur'].";","",$_SESSION['AMTypeDefaut2']);
			$tab = explode(";",$_SESSION['AMTypeDefaut2']);
			$_SESSION['AMTypeDefaut']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('typeDefaut','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrtypedefaut WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['AMTypeDefaut'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="statut"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statut','".utf8_decode($_GET['valeur'])."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['AMStatut']=str_replace(utf8_decode($_GET['valeur']).$valeur,"",$_SESSION['AMStatut']);
			$_SESSION['AMStatut2']=str_replace(utf8_decode($_GET['valeur']).";","",$_SESSION['AMStatut2']);
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
		elseif($_GET['critere']=="imputation"){
			$_SESSION['AMImputation2']=str_replace($_GET['valeur'].";","",$_SESSION['AMImputation2']);
			$tab = explode(";",$_SESSION['AMImputation2']);
			$_SESSION['AMImputation']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('imputation','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrimputation WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['AMImputation'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="moment"){
			$_SESSION['AMMoment2']=str_replace($_GET['valeur'].";","",$_SESSION['AMMoment2']);
			$tab = explode(";",$_SESSION['AMMoment2']);
			$_SESSION['AMMoment']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('moment','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrmomentdetection WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['AMMoment'].=$row['Libelle'].$valeur;
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
		$_SESSION['AMPage']=0;
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
				&nbsp; N° AM :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="numAMNC" size="15" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>&nbsp;N° OF :</td>
			<td>
				<input type="texte" name="numOF" id="numOF" size="10" value="">
			</td>
			<td>&nbsp;Localisation :</td>
			<td>
				<select name="localisation">
					<option value=""></option>
					<?php 
						$req="SELECT Id,Libelle FROM sp_atrlocalisation WHERE Id_Prestation=463";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$row['Id'].";".$row['Libelle']."' ".$selected.">".$row['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>&nbsp;Origine de l'AM :</td>
			<td>
				<select name="origineAM">
					<option value=""></option>
					<option value="Poste engine">Poste engine</option>
					<option value="P17">P17</option>
				</select>
			</td>
			<td>&nbsp;Type de défaut :</td>
			<td>
				<select name="typeDefaut">
					<option value=""></option>
					<?php 
						$req="SELECT Id,Libelle FROM sp_atrtypedefaut WHERE Id_Prestation=463";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$row['Id'].";".$row['Libelle']."' ".$selected.">".$row['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>&nbsp;Imputation :</td>
			<td>
				<select name="imputation">
					<option value=""></option>
					<?php 
						$req="SELECT Id,Libelle FROM sp_atrimputation WHERE Id_Prestation=463";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$row['Id'].";".$row['Libelle']."' ".$selected.">".$row['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
			<td>&nbsp;Moment de détection :</td>
			<td>
				<select name='moment' id='moment'>
					<option value=""></option>
					<?php 
						$req="SELECT Id,Libelle FROM sp_atrmomentdetection WHERE Id_Prestation=463";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$row['Id'].";".$row['Libelle']."' ".$selected.">".$row['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>&nbsp;N° DERO :</td>
			<td>
				<input type="texte" name="numDERO" id="numDERO" size="10" value="">
			</td>
			<td>&nbsp;Récurrence :</td>
			<td>
				<select name='recurrence' id='recurrence'>
					<option value=''></option>
					<option value='1;Oui'>Oui</option>
					<option value='0;Non'>Non</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>&nbsp;Statut :</td>
			<td>
				<select name='statut' id='statut'>
					<option value=''></option>
					<option value='Ouverte'>Ouverte</option>
					<option value='Fermée'>Fermée</option>
				</select>
			</td>
			<td>&nbsp;Produit impacté :</td>
			<td>
				<select name="produitImpacte">
					<option value=""></option>
					<?php 
						$req="SELECT Id,Libelle FROM sp_atrproduitimpacte WHERE Id_Prestation=463";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$row['Id'].";".$row['Libelle']."' ".$selected.">".$row['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>&nbsp;Côté :</td>
			<td>
				<select name="cote">
					<option value=""></option>
					<?php 
						$req="SELECT Id,Libelle FROM sp_atrcote WHERE Id_Prestation=463";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$row['Id'].";".$row['Libelle']."' ".$selected.">".$row['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
			<td>&nbsp;Action curative :</td>
			<td>
				<select name="actionCurative">
					<option value=""></option>
					<?php 
						$req="SELECT Id,Libelle FROM sp_atractioncurative WHERE Id_Prestation=463";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$row['Id'].";".$row['Libelle']."' ".$selected.">".$row['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>
				&nbsp; Du :
			</td>
			<td>
				<input type="date" style="text-align:center;" name="du" size="15" value="">
			</td>
			<td>
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