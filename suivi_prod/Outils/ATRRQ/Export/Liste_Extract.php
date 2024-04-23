<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereExtract.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=440");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereExtract.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
			w.focus();
		}
		function ExtractClient(){
			var w=window.open("Extract_Client.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function ExtractPNE(){
			var w=window.open("Extract_PNE.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function ExtractPROD(du,au){
			var w=window.open("Extract_PROD.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function ExtractCompagnon(){
			var w=window.open("Extract_Compagnon.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function ExtractCT(du,au){
			if (du>"0001-01-01"){
				var w=window.open("Extract_MiseEnProduction.php?du="+du+"&au="+au,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez compléter les critères");
			}
		}
		function ExtractMesureECME(ecme,du,au){
			if (ecme!="" && du>"0001-01-01"){
				var w=window.open("Extract_MesureECME.php?ecme="+ecme+"&du="+du+"&au="+au,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez compléter les critères");
			}
		}
	</script>
	
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>		
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
require("../../../Menu.php");
require("../../Fonctions.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['Extract_MSN']="";
		$_SESSION['Extract_Zone']="";
		$_SESSION['Extract_Statut']="";
		$_SESSION['Extract_Vacation']="";
		$_SESSION['Extract_Urgence']="";
		$_SESSION['Extract_Du']="";
		$_SESSION['Extract_Au']="";
		$_SESSION['Extract_SansDate']="";
		$_SESSION['Extract_Pole']="";
		
		$_SESSION['Extract_MSN2']="";
		$_SESSION['Extract_Zone2']="";
		$_SESSION['Extract_Statut2']="";
		$_SESSION['Extract_Vacation2']="";
		$_SESSION['Extract_Urgence2']="";
		$_SESSION['Extract_Du2']="";
		$_SESSION['Extract_Au2']="";
		$_SESSION['Extract_SansDate2']="";
		$_SESSION['Extract_Pole2']="";
	}
	
	if(isset($_POST['Recherche_RAZECME'])){
		$_SESSION['EXTRACT_ECMEMetier']="";
		$_SESSION['EXTRACT_ECMEReference']="";
		$_SESSION['EXTRACT_ECMEType']="";
		$_SESSION['EXTRACT_ECMEDateTERA']="";
		$_SESSION['EXTRACT_ECMEDateTERC']="";
		$_SESSION['EXTRACT_ECMEDu']="";
		$_SESSION['EXTRACT_ECMEAu']="";
		$_SESSION['EXTRACT_ECMEMSN']="";
		$_SESSION['EXTRACT_ECMEDossier']="";
		
		$_SESSION['EXTRACT_ECMEMetier2']="";
		$_SESSION['EXTRACT_ECMEReference2']="";
		$_SESSION['EXTRACT_ECMEType2']="";
		$_SESSION['EXTRACT_ECMEDateTERA2']="";
		$_SESSION['EXTRACT_ECMEDateTERC2']="";
		$_SESSION['EXTRACT_ECMEDu2']="";
		$_SESSION['EXTRACT_ECMEAu2']="";
		$_SESSION['EXTRACT_ECMEMSN2']="";
		$_SESSION['EXTRACT_ECMEDossier2']="";	
	}
	if(isset($_POST['Recherche_RAZECMECLIENT'])){
		$_SESSION['EXTRACT_ECMECLIENTClient']="";
		$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']="";
		$_SESSION['EXTRACT_ECMECLIENTDateTERA']="";
		$_SESSION['EXTRACT_ECMECLIENTDateTERC']="";
		$_SESSION['EXTRACT_ECMECLIENTDu']="";
		$_SESSION['EXTRACT_ECMECLIENTAu']="";
		$_SESSION['EXTRACT_ECMECLIENTMSN']="";
		$_SESSION['EXTRACT_ECMECLIENTDossier']="";
		
		$_SESSION['EXTRACT_ECMECLIENTClient2']="";
		$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']="";
		$_SESSION['EXTRACT_ECMECLIENTDateTERA2']="";
		$_SESSION['EXTRACT_ECMECLIENTDateTERC2']="";
		$_SESSION['EXTRACT_ECMECLIENTDu2']="";
		$_SESSION['EXTRACT_ECMECLIENTAu2']="";
		$_SESSION['EXTRACT_ECMECLIENTMSN2']="";
		$_SESSION['EXTRACT_ECMECLIENTDossier2']="";
	}
	if(isset($_POST['Recherche_RAZING'])){
		$_SESSION['EXTRACT_INGIngredient']="";
		$_SESSION['EXTRACT_INGNumLot']="";
		$_SESSION['EXTRACT_INGDatePeremption']="";
		$_SESSION['EXTRACT_INGDateTERA']="";
		$_SESSION['EXTRACT_INGDateTERC']="";
		$_SESSION['EXTRACT_INGDu']="";
		$_SESSION['EXTRACT_INGAu']="";
		$_SESSION['EXTRACT_INGMSN']="";
		$_SESSION['EXTRACT_INGDossier']="";
		
		$_SESSION['EXTRACT_INGIngredient2']="";
		$_SESSION['EXTRACT_INGNumLot2']="";
		$_SESSION['EXTRACT_INGDatePeremption2']="";
		$_SESSION['EXTRACT_INGDateTERA2']="";
		$_SESSION['EXTRACT_INGDateTERC2']="";
		$_SESSION['EXTRACT_INGDu2']="";
		$_SESSION['EXTRACT_INGAu2']="";
		$_SESSION['EXTRACT_INGMSN2']="";
		$_SESSION['EXTRACT_INGDossier2']="";
	}
	if(isset($_POST['Recherche_RAZING'])){
		$_SESSION['EXTRACT_INGIngredient']="";
		$_SESSION['EXTRACT_INGNumLot']="";
		$_SESSION['EXTRACT_INGDatePeremption']="";
		$_SESSION['EXTRACT_INGDateTERA']="";
		$_SESSION['EXTRACT_INGDateTERC']="";
		$_SESSION['EXTRACT_INGDu']="";
		$_SESSION['EXTRACT_INGAu']="";
		$_SESSION['EXTRACT_INGMSN']="";
		$_SESSION['EXTRACT_INGDossier']="";
		
		$_SESSION['EXTRACT_INGIngredient2']="";
		$_SESSION['EXTRACT_INGNumLot2']="";
		$_SESSION['EXTRACT_INGDatePeremption2']="";
		$_SESSION['EXTRACT_INGDateTERA2']="";
		$_SESSION['EXTRACT_INGDateTERC2']="";
		$_SESSION['EXTRACT_INGDu2']="";
		$_SESSION['EXTRACT_INGAu2']="";
		$_SESSION['EXTRACT_INGMSN2']="";
		$_SESSION['EXTRACT_INGDossier2']="";
	}
	if(isset($_POST['Recherche_RAZPS'])){
		$_SESSION['EXTRACT_PSCompagnon']="";
		$_SESSION['EXTRACT_PSIQ']="";
		$_SESSION['EXTRACT_PSReference']="";
		$_SESSION['EXTRACT_PSDateTERA']="";
		$_SESSION['EXTRACT_PSDateTERC']="";
		$_SESSION['EXTRACT_PSDu']="";
		$_SESSION['EXTRACT_PSAu']="";
		$_SESSION['EXTRACT_PSMSN']="";
		$_SESSION['EXTRACT_PSDossier']="";
		
		$_SESSION['EXTRACT_PSCompagnon2']="";
		$_SESSION['EXTRACT_PSIQ2']="";
		$_SESSION['EXTRACT_PSReference2']="";
		$_SESSION['EXTRACT_PSDateTERA2']="";
		$_SESSION['EXTRACT_PSDateTERC2']="";
		$_SESSION['EXTRACT_PSDu2']="";
		$_SESSION['EXTRACT_PSAu2']="";
		$_SESSION['EXTRACT_PSMSN2']="";
		$_SESSION['EXTRACT_PSDossier2']="";
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form id="formulaire" class="test" method="POST" action="Liste_Extract.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des extracts</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td><b>&nbsp; Critères de recherche : </b></td>
			<td align="right" colspan="6"><input class="Bouton" name="Recherche_RAZ" type="submit" value="Vider les critères de recherche"></td>
			<td align="right">
			<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_Critere()">&nbsp;<img src="../../../Images/Plus2.png" border="0" alt="Ajouter critères" title="Ajouter critères">&nbsp;&nbsp;</a>
			</td>
		</tr>
		<?php
			if($_SESSION['Extract_MSN']<>""){
				echo "<tr>";
				echo "<td>MSN : ".$_SESSION['Extract_MSN']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Extract_Zone']<>""){
				echo "<tr>";
				echo "<td>Zones : ".$_SESSION['Extract_Zone']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Extract_Statut']<>""){
				echo "<tr>";
				echo "<td>Statuts IC : ".$_SESSION['Extract_Statut']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Extract_Vacation']<>""){
				echo "<tr>";
				echo "<td>Vacations : ".$_SESSION['Extract_Vacation']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Extract_Urgence']<>""){
				echo "<tr>";
				echo "<td>Urgences : ".$_SESSION['Extract_Urgence']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Extract_Du']<>""){
				echo "<tr>";
				echo "<td>Date de début : ".$_SESSION['Extract_Du']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Extract_Au']<>""){
				echo "<tr>";
				echo "<td>Date de fin : ".$_SESSION['Extract_Au']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Extract_SansDate']<>""){
				echo "<tr>";
				echo "<td>Sans date : ".$_SESSION['Extract_SansDate']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Extract_Pole']<>""){
				echo "<tr>";
				echo "<td>Pôle : ".$_SESSION['Extract_Pole']."</td>";
				echo "</tr>";
			}
		?>
		
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td><b>&nbsp; Extracts : </b></td>
	</tr>
	<tr>
		<td align="left">
			<a style="text-decoration:none;" href="javascript:ExtractClient()">&nbsp; &bull; Extract Client&nbsp;&nbsp;</a>
		</td>
	</tr>
	<tr>
		<td align="left">
			<a style="text-decoration:none;" href="javascript:ExtractCompagnon()">&nbsp; &bull; Extract Compagnon (uniquement si CERT)&nbsp;&nbsp;</a>
		</td>
	</tr>
	<tr>
		<td align="left">
			<a style="text-decoration:none;" href="javascript:ExtractPROD()">&nbsp; &bull; Extract PROD &nbsp;&nbsp;</a>
		</td>
	</tr>
	</table>
	</td></tr>
	<tr><td height="10"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td colspan=4><b>&nbsp; Critères de recherche : </b></td>
			</td>
		</tr>
		<?php
			$ecme="";
			$du="";
			$au="";
			$duEnvoi="";
			$auEnvoi="";
			if($_POST){
				if(isset($_POST['ecme'])){
				$ecme=$_POST['ecme'];
				$du=$_POST['du'];
				$au=$_POST['au'];
				$duEnvoi=TrsfDate_($_POST['du']);
				$auEnvoi=TrsfDate_($_POST['au']);
				echo "<script>ExtractMesureECME('".$ecme."','".$duEnvoi."','".$auEnvoi."');</script>";
				}
			}
		?>
		<tr>
			<td width="4%">&nbsp; ECME <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%" colspan="3"><input type="text" name="ecme" id="ecme" value="<?php echo $ecme; ?>"/></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="4%">&nbsp; Du <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%"><input type="date" name="du" id="du" value="<?php echo $du; ?>"/></td>
			<td width="4%" align="left">&nbsp; au </td>
			<td width="60%"><input type="date" name="au" id="au" value="<?php echo $au; ?>"/></td>
		</tr>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td colspan=4><b>&nbsp; Extracts : </b></td>
	</tr>
	<tr>
		<td align="left" colspan=4>
			<a style="text-decoration:none;" href="#" onClick="document.getElementById('formulaire').submit()" >&nbsp; &bull; Extract "Suivi des mesures effectuées avec un ECME" &nbsp;&nbsp;</a>
		</td>
	</tr>
	</table>
	</td></tr>
	
	<tr><td height="10"></td></tr>
</form>
</table>

<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>