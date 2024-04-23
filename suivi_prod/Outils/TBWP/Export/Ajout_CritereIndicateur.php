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
			opener.location="Liste_Indicateur.php";
		}
		function FermerEtRecharger2(){
			opener.location="Liste_Indicateur.php";
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
	if($_POST['msn']<>"" && strpos($_SESSION['Indicateur_MSN2'],$_POST['msn'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_POST['msn']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Indicateur_MSN']=$_SESSION['Indicateur_MSN'].$_POST['msn'].$btn;
		$_SESSION['Indicateur_MSN2']=$_SESSION['Indicateur_MSN2'].$_POST['msn'].";";
	}
	$left="_".substr($_POST['pole'],0,strpos($_POST['pole'],";"));
	if($_POST['pole']<>"" && strpos($_SESSION['Indicateur_Pole2'],$left.";")===false){
		$right=substr($_POST['pole'],strpos($_POST['pole'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('pole','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Indicateur_Pole']=$_SESSION['Indicateur_Pole'].$right.$btn;
		$_SESSION['Indicateur_Pole2']=$_SESSION['Indicateur_Pole2'].$left.";";
	}
	$left=substr($_POST['vacation'],0,strpos($_POST['vacation'],";"));
	if($_POST['vacation']<>"" && strpos($_SESSION['Indicateur_Vacation2'],$left.";")===false){
		$right=substr($_POST['vacation'],strpos($_POST['vacation'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','".$left."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Indicateur_Vacation']=$_SESSION['Indicateur_Vacation'].$right.$btn;
		$_SESSION['Indicateur_Vacation2']=$_SESSION['Indicateur_Vacation2'].$left.";";
	}
	if($_POST['du']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_POST['du']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Indicateur_Du']=$_POST['du'].$btn;
		$_SESSION['Indicateur_Du2']=$_POST['du'];
	}
	if($_POST['au']<>""){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_POST['au']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Indicateur_Au']=$_POST['au'].$btn;
		$_SESSION['Indicateur_Au2']=$_POST['au'];
	}
	$_SESSION['ModeFiltre']="";
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="msn"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('msn','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Indicateur_MSN']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Indicateur_MSN']);
			$_SESSION['Indicateur_MSN2']=str_replace($_GET['valeur'].";","",$_SESSION['Indicateur_MSN2']);
		}
		elseif($_GET['critere']=="pole"){
			$_SESSION['Indicateur_Pole2']=str_replace($_GET['valeur'].";","",$_SESSION['Indicateur_Pole2']);
			$tab = explode(";",$_SESSION['Indicateur_Pole2']);
			$_SESSION['Indicateur_Pole']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('pole','".$Id."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM new_competences_pole WHERE Id=".substr($Id,1);
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['Indicateur_Pole'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		if($_GET['critere']=="vacation"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			if($_GET['valeur']=="J"){$_SESSION['Indicateur_Vacation']=str_replace("Jour".$valeur,"",$_SESSION['Indicateur_Vacation']);}
			elseif($_GET['valeur']=="S"){$_SESSION['Indicateur_Vacation']=str_replace("Soir".$valeur,"",$_SESSION['Indicateur_Vacation']);}
			elseif($_GET['valeur']=="N"){$_SESSION['Indicateur_Vacation']=str_replace("Nuit".$valeur,"",$_SESSION['Indicateur_Vacation']);}
			elseif($_GET['valeur']=="VSD"){$_SESSION['Indicateur_Vacation']=str_replace("VSD".$valeur,"",$_SESSION['Indicateur_Vacation']);}
			$_SESSION['Indicateur_Vacation2']=str_replace($_GET['valeur'].";","",$_SESSION['Indicateur_Vacation2']);
		}
		if($_GET['critere']=="du"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('du','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Indicateur_Du']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Indicateur_Du']);
			$_SESSION['Indicateur_Du2']=str_replace($_GET['valeur'],"",$_SESSION['Indicateur_Du2']);
		}
		if($_GET['critere']=="au"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('au','".$_GET['valeur']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Indicateur_Au']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Indicateur_Au']);
			$_SESSION['Indicateur_Au2']=str_replace($_GET['valeur'],"",$_SESSION['Indicateur_Au2']);
		}
		$_SESSION['ModeFiltre']="";
		echo "<script>FermerEtRecharger2();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereIndicateur.php">
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
				&nbsp; Vacation :
			</td>
			<td width=80%>
				<select name="vacation">
					<option name="" value=""></option>
					<option name="J;Jour" value="J;Jour">Jour</option>
					<option name="S;Soir" value="S;Soir">Soir</option>
					<option name="N;Nuit" value="N;Nuit">Nuit</option>
					<option name="VSD;VSD" value="VSD;VSD">VSD</option>
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