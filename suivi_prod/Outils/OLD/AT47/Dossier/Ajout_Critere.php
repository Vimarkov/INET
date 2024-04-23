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
			opener.location="Liste_Dossier.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_Dossier.php";
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
	if($_POST['msn']<>"" && strpos($_SESSION['OTMSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTMSN']=$_SESSION['OTMSN'].$_POST['msn'].$btn;
		$_SESSION['OTMSN2']=$_SESSION['OTMSN2'].$_POST['msn'].";";
	}
	if($_POST['ordreMontage']<>"" && strpos($_SESSION['OTOM2'],$_POST['ordreMontage'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('ordreMontage','".$_POST['ordreMontage']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTOM']=$_SESSION['OTOM'].$_POST['ordreMontage'].$btn;
		$_SESSION['OTOM2']=$_SESSION['OTOM2'].$_POST['ordreMontage'].";";
	}
	if($_POST['designation']<>"" && strpos($_SESSION['OTDesignation2'],$_POST['designation'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('designation','".$_POST['designation']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTDesignation']=$_SESSION['OTDesignation'].$_POST['designation'].$btn;
		$_SESSION['OTDesignation2']=$_SESSION['OTDesignation2'].$_POST['designation'].";";
	}
	if($_POST['ligne']<>"" && strpos($_SESSION['OTLigne2'],$_POST['ligne'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('ligne','".$_POST['ligne']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTLigne']=$_SESSION['OTLigne'].$_POST['ligne'].$btn;
		$_SESSION['OTLigne2']=$_SESSION['OTLigne2'].$_POST['ligne'].";";
	}
	$left=substr($_POST['poste45'],0,strpos($_POST['poste45'],";"));
	if($_POST['poste45']<>"" && strpos($_SESSION['OTPoste452'],$left.";")===false){
		$right=substr($_POST['poste45'],strpos($_POST['poste45'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('poste45','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTPoste45']=$_SESSION['OTPoste45'].$right.$btn;
		$_SESSION['OTPoste452']=$_SESSION['OTPoste452'].$left.";";
	}
	if($_POST['statutPROD']<>"" && strpos($_SESSION['OTStatutP2'],$_POST['statutPROD'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','".$_POST['statutPROD']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTStatutP']=$_SESSION['OTStatutP'].$_POST['statutPROD'].$btn;
		$_SESSION['OTStatutP2']=$_SESSION['OTStatutP2'].$_POST['statutPROD'].";";
	}
	if($_POST['statutQUALITE']<>"" && strpos($_SESSION['OTStatutQ2'],$_POST['statutQUALITE'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutQUALITE','".$_POST['statutQUALITE']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTStatutQ']=$_SESSION['OTStatutQ'].$_POST['statutQUALITE'].$btn;
		$_SESSION['OTStatutQ2']=$_SESSION['OTStatutQ2'].$_POST['statutQUALITE'].";";
	}
	$left=substr($_POST['causeRetardPROD'],0,strpos($_POST['causeRetardPROD'],";"));
	if($_POST['causeRetardPROD']<>"" && strpos($_SESSION['OTRaisonP2'],$left.";")===false){
		$right=substr($_POST['causeRetardPROD'],strpos($_POST['causeRetardPROD'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('causeRetardPROD','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTRaisonP']=$_SESSION['OTRaisonP'].$right.$btn;
		$_SESSION['OTRaisonP2']=$_SESSION['OTRaisonP2'].$left.";";
	}
	$left=substr($_POST['causeRetardQUALITE'],0,strpos($_POST['causeRetardQUALITE'],";"));
	if($_POST['causeRetardQUALITE']<>"" && strpos($_SESSION['OTRaisonQ2'],$left.";")===false){
		$right=substr($_POST['causeRetardQUALITE'],strpos($_POST['causeRetardQUALITE'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('causeRetardQUALITE','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['OTRaisonQ']=$_SESSION['OTRaisonQ'].$right.$btn;
		$_SESSION['OTRaisonQ2']=$_SESSION['OTRaisonQ2'].$left.";";
	}
	$_SESSION['OTPage']="0";
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['OTMSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['OTMSN']);
			$_SESSION['OTMSN2']=str_replace($_GET['valeur'].";","",$_SESSION['OTMSN2']);
		}
		elseif($_GET['critere']=="ordreMontage"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('ordreMontage','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['OTOM']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['OTOM']);
			$_SESSION['OTOM2']=str_replace($_GET['valeur'].";","",$_SESSION['OTOM2']);
		}
		elseif($_GET['critere']=="designation"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('designation','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['OTDesignation']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['OTDesignation']);
			$_SESSION['OTDesignation2']=str_replace($_GET['valeur'].";","",$_SESSION['OTDesignation2']);
		}
		elseif($_GET['critere']=="ligne"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('ligne','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['OTLigne']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['OTLigne']);
			$_SESSION['OTLigne2']=str_replace($_GET['valeur'].";","",$_SESSION['OTLigne2']);
		}
		elseif($_GET['critere']=="statutPROD"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','".utf8_decode($_GET['valeur'])."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['OTStatutP']=str_replace(utf8_decode($_GET['valeur']).$valeur,"",$_SESSION['OTStatutP']);
			$_SESSION['OTStatutP2']=str_replace(utf8_decode($_GET['valeur']).";","",$_SESSION['OTStatutP2']);
		}
		elseif($_GET['critere']=="statutQUALITE"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutQUALITE','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['OTStatutQ']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['OTStatutQ']);
			$_SESSION['OTStatutQ2']=str_replace($_GET['valeur'].";","",$_SESSION['OTStatutQ2']);
		}
		elseif($_GET['critere']=="poste45"){
			$_SESSION['OTPoste452']=str_replace($_GET['valeur'].";","",$_SESSION['OTPoste452']);
			$tab = explode(";",$_SESSION['OTPoste452']);
			$_SESSION['OTPoste45']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('poste45','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					if($Id==0){
						$_SESSION['OTPoste45'].="Non".$valeur;
					}
					elseif($Id==1){
						$_SESSION['OTPoste45'].="Oui".$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="causeRetardPROD"){
			$_SESSION['OTRaisonP2']=str_replace($_GET['valeur'].";","",$_SESSION['OTRaisonP2']);
			$tab = explode(";",$_SESSION['OTRaisonP2']);
			$_SESSION['OTRaisonP']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('causeRetardPROD','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrcauseretard WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['OTRaisonP'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		elseif($_GET['critere']=="causeRetardQUALITE"){
			$_SESSION['OTRaisonQ2']=str_replace($_GET['valeur'].";","",$_SESSION['OTRaisonQ2']);
			$tab = explode(";",$_SESSION['OTRaisonQ2']);
			$_SESSION['OTRaisonQ']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('causeRetardQUALITE','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM sp_atrcauseretard WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['OTRaisonQ'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		$_SESSION['OTPage']="0";
		echo "<script>FermerEtRecharger();</script>";
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
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20% class="Libelle">
				&nbsp; MSN :
			</td>
			<td> 
				<input onKeyUp="nombre(this)" type="texte" style="text-align:center;" name="msn" size="10" value="">
			</td>
			<td width=20% class="Libelle">
				&nbsp; Ordre de montage :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="ordreMontage" size="15" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20% class="Libelle">
				&nbsp; Désignation :
			</td>
			<td colspan="4"> 
				<input type="texte" style="text-align:center;" name="designation" size="50" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20% class="Libelle">
				&nbsp; Ligne :
			</td>
			<td >
				<select name="ligne">
					<option value=""></option>
					<option value="?">?</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
				</select>
			</td>
			<td width=20% class="Libelle">
				&nbsp; Poste 45 :
			</td>
			<td >
				<select name="poste45">
					<option value=""></option>
					<option value="1;Oui">Oui</option>
					<option value="0;Non">Non</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20% class="Libelle">
				&nbsp; Statut PROD :
			</td>
			<td>
				<select name="statutPROD">
					<option value=""></option>
					<option value="(vide)">(vide)</option>
					<option value="En cours">En cours</option>
					<option value="Pas de responsabilité AAA">Pas de responsabilité AAA</option>
					<option value="TFS">TFS</option>
					<option value="TERA">TERA</option>
				</select>
			</td>
			<td width=20% class="Libelle">
				&nbsp; Statut QUALITE :
			</td>
			<td>
				<select name="statutQUALITE">
					<option value=""></option>
					<option value="(vide)">(vide)</option>
					<option value="En cours">En cours</option>
					<option value="TVS">TVS</option>
					<option value="TERC">TERC</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width=20% class="Libelle">
				&nbsp; Information statut production :
			</td>
			<td >
				<select name="causeRetardPROD">
					<option value=""></option>
					<?php 
						$req="SELECT Id,Libelle FROM sp_atrcauseretard WHERE Id_Prestation=262";
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
			<td width=20% class="Libelle">
				&nbsp; Information statut qualité :
			</td>
			<td >
				<select name="causeRetardQUALITE">
					<option value=""></option>
					<?php 
						$req="SELECT Id,Libelle FROM sp_atrcauseretard WHERE Id_Prestation=262";
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