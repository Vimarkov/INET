<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function ReportingRA(msn,date){
			if (msn!="" && date!=""){
				var w=window.open("ReportingRA.php?msn="+msn+"&date="+date,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs RA");
			}
		}
		function ReportingRAExtract(msn,date){
			if (msn!="" && date!=""){
				var w=window.open("Extract_ReportingRA.php?msn="+msn+"&date="+date,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs RA");
			}
		}
		function ReportingPROD(date,pole,vacation){
			if (date!="" && pole!="" && vacation!=""){
				var w=window.open("ReportingPROD.php?date="+date+"&pole="+pole+"&vacation="+vacation,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs PROD");
			}
		}
		function ReportingPRODExtract(date,pole,vacation){
			if (date!="" && pole!="" && vacation!=""){
				var w=window.open("Extract_ReportingPROD.php?date="+date+"&pole="+pole+"&vacation="+vacation,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs PROD");
			}
		}
		function ReportingPlannifRGP(du,pole){
			if (du!="" && pole!=""){
				var w=window.open("ReportingPlannifRGP.php?du="+du+"&pole="+pole,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs");
			}
		}
		function ReportingCompteRenduRGP(du,pole){
			if (du!="" && pole!=""){
				var w=window.open("ReportingCompteRenduRGP.php?du="+du+"&pole="+pole,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs");
			}
		}
		function ExportPlannifRGP(du,pole){
			if (du!="" && pole!=""){
				var w=window.open("ExportPlannifRGP.php?du="+du+"&pole="+pole,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs");
			}
		}
		function ExportCompteRenduRGP(du,pole){
			if (du!="" && pole!=""){
				var w=window.open("ExportCompteRenduRGP.php?du="+du+"&pole="+pole,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs");
			}
		}
		function ReportingQualite(date,pole,vacation){
			if (date!="" && pole!="" && vacation!=""){
				var w=window.open("ReportingQUALITE.php?date="+date+"&pole="+pole+"&vacation="+vacation,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs QUALITE");
			}
		}
		function ReportingQualiteExtract(date,pole,vacation){
			if (date!="" && pole!="" && vacation!=""){
				var w=window.open("Extract_ReportingQUALITE.php?date="+date+"&pole="+pole+"&vacation="+vacation,"PageReporting","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{
				alert("Veuillez renseigner les champs QUALITE");
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

//Verifier si Google CHROME (true) ou Autre (fale)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
else {$NavigOk = false;}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_POST){

}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form id="formulaire" class="test" method="POST" action="Liste_Reporting.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des reportings</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$Email="";
		$req="SELECT EmailPro FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_PersonneSP'];
		$resulEmail=mysqli_query($bdd,$req);
		$nbEmail=mysqli_num_rows($resulEmail);
		if ($nbEmail>0){
			$row=mysqli_fetch_array($resulEmail);
			$Email=$row['EmailPro'];
		}
		if($Email==""){
			echo "<tr><td>";
			echo "Veuillez compléter votre adresse mail pour pouvoir continuer";
			echo "</td></tr>";
		}
		else{
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1'){
	?>
	<tr><td>
	<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:35%;">
		<tr>
			<td colspan=4><b>&nbsp; Reporting RA : </b></td>
			</td>
		</tr>
		<?php
			$msn="";
			$dateRA=AfficheDateFR($DateJour);
			if($_POST){
				$msn=$_POST['msn'];
				$dateRA=$_POST['dateRA'];;
				if(isset($_POST['btnRA'])){
					echo "<script>ReportingRA('".$msn."','".$dateRA."');</script>";
				}
				if(isset($_POST['btnRAExtract'])){
					echo "<script>ReportingRAExtract('".$msn."','".$dateRA."');</script>";
				}
			}
		?>
		<tr>
			<td width="4%">&nbsp; Date <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%"><input type="date" name="dateRA" id="dateRA" size="10" value="<?php echo $dateRA; ?>"/></td>
		</tr>
		<tr>
			<td width="4%">&nbsp; MSN <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%"><input type="text" name="msn" id="msn" size="8" value="<?php echo $msn; ?>"/></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td></td>
			<td align="left">
				&nbsp;<input class="Bouton" type="submit" name="btnRA" value="Email">
				&nbsp;<input class="Bouton" type="submit" name="btnRAExtract" value="Export">
			</td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="10"></td></tr>
	<?php
		}
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1'){
	?>
	<tr><td>
	<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:35%;">
		<tr>
			<td colspan=4><b>&nbsp; Reporting PROD : </b></td>
			</td>
		</tr>
		<?php
			$dateProd=AfficheDateFR($DateJour);
			$poleProd="";
			$vacationProd="";
			if($_POST){
				$dateProd=$_POST['dateProd'];
				$poleProd=$_POST['poleProd'];
				$vacationProd=$_POST['vacationProd'];
				if(isset($_POST['btnPROD'])){
					echo "<script>ReportingPROD('".$dateProd."','".$poleProd."','".$vacationProd."');</script>";
				}
				if(isset($_POST['btnPRODExcel'])){
					echo "<script>ReportingPRODExtract('".$dateProd."','".$poleProd."','".$vacationProd."');</script>";
				}
			}
		?>
		<tr>
			<td width="4%">&nbsp; Date <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%"><input type="date" name="dateProd" id="dateProd" size="10" value="<?php echo $dateProd; ?>"/></td>
		</tr>	
		<tr>
			<td width="4%">&nbsp; Pôle <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%">
				<select name="poleProd">
					<option name="" value=""></option>
					<option name="-1" value="-1">A50 / M50</option>
					<?php
					$req="SELECT DISTINCT Id, Libelle FROM new_competences_pole WHERE Id IN (1,2,3,5,6,42) AND Id_Prestation=255 ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$selected="";
							if($poleProd==$row['Id']){$selected="selected";}
							echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="4%">&nbsp; Vacation <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%">
				<select name="vacationProd">
					<option name="" value=""></option>
					<option name="J" value="J" <?php if($vacationProd=="J"){echo "selected";}?>>Jour</option>
					<option name="S" value="S" <?php if($vacationProd=="S"){echo "selected";}?>>Soir</option>
					<option name="N" value="N" <?php if($vacationProd=="N"){echo "selected";}?>>Nuit</option>
					<option name="VSD" value="VSD" <?php if($vacationProd=="VSD"){echo "selected";}?>>VSD</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="4%"></td>
			<td align="left">
				&nbsp;<input class="Bouton" type="submit" name="btnPROD" value="Email">
				&nbsp;<input class="Bouton" type="submit" name="btnPRODExcel" value="Export">
			</td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="10"></td></tr>
	<?php
		}
		if(substr($_SESSION['DroitSP'],4,1)=='1'){
	?>
	<tr><td>
	<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:35%;">
		<tr>
			<td colspan=4><b>&nbsp; Reporting QUALITE : </b></td>
			</td>
		</tr>
		<?php
			$dateQualite=AfficheDateFR($DateJour);
			$poleQualite="";
			$vacationQualite="";
			if($_POST){
				$dateQualite=$_POST['dateQualite'];
				$poleQualite=$_POST['poleQualite'];
				$vacationQualite=$_POST['vacationQualite'];
				if(isset($_POST['btnQualite'])){
					echo "<script>ReportingQualite('".$dateQualite."','".$poleQualite."','".$vacationQualite."');</script>";
				}
				if(isset($_POST['btnQualiteExtract'])){
					echo "<script>ReportingQualiteExtract('".$dateQualite."','".$poleQualite."','".$vacationQualite."');</script>";
				}
			}
		?>
		<tr>
			<td width="4%">&nbsp; Date <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%"><input type="date" name="dateQualite" id="dateQualite" size="10" value="<?php echo $dateQualite; ?>"/></td>
		</tr>	
		<tr>
			<td width="4%">&nbsp; Pôle <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%">
				<select name="poleQualite">
					<option name="" value=""></option>
					<option name="-1" value="-1">A50 / M50</option>
					<?php
					$req="SELECT DISTINCT Id, Libelle FROM new_competences_pole WHERE Id IN (1,2,3,5,6,42) AND Id_Prestation=255 ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$selected="";
							if($poleQualite==$row['Id']){$selected="selected";}
							echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="4%">&nbsp; Vacation <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%">
				<select name="vacationQualite">
					<option name="" value=""></option>
					<option name="J" value="J" <?php if($vacationQualite=="J"){echo "selected";}?>>Jour</option>
					<option name="S" value="S" <?php if($vacationQualite=="S"){echo "selected";}?>>Soir</option>
					<option name="N" value="N" <?php if($vacationQualite=="N"){echo "selected";}?>>Nuit</option>
					<option name="VSD" value="VSD" <?php if($vacationQualite=="VSD"){echo "selected";}?>>VSD</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="4%"></td>
			<td align="left">
				&nbsp;<input class="Bouton" type="submit" name="btnQualite" value="Email">
				&nbsp;<input class="Bouton" type="submit" name="btnQualiteExtract" value="Export">
			</td>
		</tr>
	</table>
	</td></tr>
	<?php
		}
		if(substr($_SESSION['DroitSP'],3,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
	?>
	<tr><td>
	<tr><td height="10"></td></tr>
	<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:35%;">
		<tr>
			<td colspan=4><b>&nbsp; Reporting Journalier : </b></td>
			</td>
		</tr>
		<?php
			$duRGP=AfficheDateFR($DateJour);
			$poleRGP="";
			if($_POST){
				$duRGP=$_POST['duRGP'];
				$poleRGP=$_POST['poleRGP'];
				if(isset($_POST['btnJournalierPlannif'])){
					echo "<script>ReportingPlannifRGP('".$duRGP."','".$poleRGP."');</script>";
				}
				if(isset($_POST['btnJournalierCompteRendu'])){
					echo "<script>ReportingCompteRenduRGP('".$duRGP."','".$poleRGP."');</script>";
				}
				if(isset($_POST['btnJournalierPlannifExport'])){
					echo "<script>ExportPlannifRGP('".$duRGP."','".$poleRGP."');</script>";
				}
				if(isset($_POST['btnJournalierCompteRenduExport'])){
					echo "<script>ExportCompteRenduRGP('".$duRGP."','".$poleRGP."');</script>";
				}
			}
		?>
		<tr>
			<td width="4%">&nbsp; Pôle <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%">
				<select name="poleRGP">
					<option name="" value=""></option>
					<option name="-1" value="-1">A50 / M50</option>
					<?php
					$req="SELECT DISTINCT Id, Libelle FROM new_competences_pole WHERE Id IN (1,2,3,5,6,42) AND Id_Prestation=255 ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$selected="";
							if($poleRGP==$row['Id']){$selected="selected";}
							echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="4%">&nbsp; Date <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="10%"><input type="date" name="duRGP" id="duRGP" size="10" value="<?php echo $duRGP; ?>"/></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center" colspan="4">
				&nbsp;<input class="Bouton" type="submit" name="btnJournalierPlannifExport" value="Export Plannification">
				&nbsp;<input class="Bouton" type="submit" name="btnJournalierCompteRenduExport" value="Export Compte rendu">
			</td>
		</tr>
	</table>
	</td></tr>
	<?php
		}
		}
	?>
</form>
</table>

<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>