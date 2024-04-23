<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Reporting.js"></script>
	<script language="javascript">
		function Excel_Gammes(){
			if(document.getElementById('msn').value != ""){
				var w=window.open("Extract_Gammes.php?MSN="+document.getElementById('msn').value,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{alert("Veuillez renseigner le MSN");}
		}
		function Excel_AMNC(){
			if(document.getElementById('msn').value != ""){
				var w=window.open("Extract_AMNC.php?MSN="+document.getElementById('msn').value,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{alert("Veuillez renseigner le MSN");}
		}
		function Excel_CQLB(){
			if(document.getElementById('msn').value != ""){
				var w=window.open("Extract_CQLB.php?MSN="+document.getElementById('msn').value,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{alert("Veuillez renseigner le MSN");}
		}
		function Excel_RetourClient(){
			if(document.getElementById('msn').value != ""){
				var w=window.open("Extract_RetourClient.php?MSN="+document.getElementById('msn').value,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
			}
			else{alert("Veuillez renseigner le MSN");}
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

<form id="formulaire" class="test" method="POST" action="Liste_Reporting.php">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="2">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des reportings</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td width="50%" valign="top">
			<table width="100%">
				<tr>
					<td class="MoyenTitre">MSN</td>
				</tr>
				<tr><td>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%">&nbsp; MSN <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
							<td width="80%" align="left"><input type="text" name="msn" id="msn" size="8" value=""/></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2"><a style="text-decoration:none;" href="javascript:Excel_Gammes()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;Données du MSN&nbsp;</a></td>
						</tr>
					</table>
				</td></tr>
				<tr>
					<td class="MoyenTitre">ECME</td>
				</tr>
				<tr><td>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
						<tr>
							<td colspan="4"><b>&nbsp; Critères de recherche : </b></td>
						</tr>
						<?php
							$ecme="";
							$du="";
							$au="";
							$duEnvoi="";
							$auEnvoi="";
							if($_POST){
								if(isset($_POST['ecme'])){
									if($_POST['ecme']<>""){
										$ecme=$_POST['ecme'];
										$du=$_POST['du'];
										$au=$_POST['au'];
										$duEnvoi=TrsfDate_($_POST['du']);
										$auEnvoi=TrsfDate_($_POST['au']);
										echo "<script>ExtractMesureECME('".$ecme."','".$duEnvoi."','".$auEnvoi."');</script>";
									}
								}
							}
						?>
						<tr>
							<td width="10%">&nbsp; ECME <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
							<td width="10%" colspan="3"><input type="text" name="ecme" id="ecme" value="<?php echo $ecme; ?>"/></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%">&nbsp; Du <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
							<td width="10%"><input type="date" name="du" id="du" value="<?php echo $du; ?>"/></td>
							<td width="10%" align="left">&nbsp; au </td>
							<td width="60%"><input type="date" name="au" id="au" value="<?php echo $au; ?>"/></td>
						</tr>
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
				<tr><td height="4"></td></tr>
				<tr><td>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
						<tr>
							<td><b>&nbsp; Critères de recherche : </b></td>
							<td align="right" colspan="6"><input class="Bouton" name="Recherche_RAZECME" type="submit" value="Vider les critères de recherche"></td>
							<td align="right">
							<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_CritereECME()">&nbsp;<img src="../../../Images/Plus2.png" border="0" alt="Ajouter critères" title="Ajouter critères">&nbsp;&nbsp;</a>
							</td>
						</tr>
							<?php
								if($_SESSION['EXTRACT_ECMEMetier']<>""){
									echo "<tr>";
									echo "<td>Métier utilisateur : ".$_SESSION['EXTRACT_ECMEMetier']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMEMSN']<>""){
									echo "<tr>";
									echo "<td>MSN : ".$_SESSION['EXTRACT_ECMEMSN']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMEDossier']<>""){
									echo "<tr>";
									echo "<td>N° dossier : ".$_SESSION['EXTRACT_ECMEDossier']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMEReference']<>""){
									echo "<tr>";
									echo "<td>Référence ECME : ".$_SESSION['EXTRACT_ECMEReference']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMEType']<>""){
									echo "<tr>";
									echo "<td>Type ECME : ".$_SESSION['EXTRACT_ECMEType']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMEDu']<>""){
									echo "<tr>";
									echo "<td>Du : ".$_SESSION['EXTRACT_ECMEDu']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMEAu']<>""){
									echo "<tr>";
									echo "<td>Au : ".$_SESSION['EXTRACT_ECMEAu']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMEDateTERA']<>""){
									echo "<tr>";
									echo "<td>Date TERA : ".$_SESSION['EXTRACT_ECMEDateTERA']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMEDateTERC']<>""){
									echo "<tr>";
									echo "<td>Date TERC : ".$_SESSION['EXTRACT_ECMEDateTERC']."</td>";
									echo "</tr>";
								}
							?>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2"><a style="text-decoration:none;" href="javascript:Excel_ECME()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;Liste des ECME production / qualité utilisés&nbsp;</a></td>
						</tr>
					</table>
				</td></tr>
				<tr><td height="4"></td></tr>
				<tr><td>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
						<tr>
							<td><b>&nbsp; Critères de recherche : </b></td>
							<td align="right" colspan="6"><input class="Bouton" name="Recherche_RAZECMECLIENT" type="submit" value="Vider les critères de recherche"></td>
							<td align="right">
							<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_CritereECMECLIENT()">&nbsp;<img src="../../../Images/Plus2.png" border="0" alt="Ajouter critères" title="Ajouter critères">&nbsp;&nbsp;</a>
							</td>
						</tr>
							<?php
								if($_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']<>""){
									echo "<tr>";
									echo "<td>Date fin étalonnage : ".$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMECLIENTClient']<>""){
									echo "<tr>";
									echo "<td>N° client : ".$_SESSION['EXTRACT_ECMECLIENTClient']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMECLIENTMSN']<>""){
									echo "<tr>";
									echo "<td>MSN : ".$_SESSION['EXTRACT_ECMECLIENTMSN']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMECLIENTDossier']<>""){
									echo "<tr>";
									echo "<td>N° dossier : ".$_SESSION['EXTRACT_ECMECLIENTDossier']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMECLIENTDu']<>""){
									echo "<tr>";
									echo "<td>Du : ".$_SESSION['EXTRACT_ECMECLIENTDu']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMECLIENTAu']<>""){
									echo "<tr>";
									echo "<td>Au : ".$_SESSION['EXTRACT_ECMECLIENTAu']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMECLIENTDateTERA']<>""){
									echo "<tr>";
									echo "<td>Date TERA : ".$_SESSION['EXTRACT_ECMECLIENTDateTERA']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_ECMECLIENTDateTERC']<>""){
									echo "<tr>";
									echo "<td>Date TERC : ".$_SESSION['EXTRACT_ECMECLIENTDateTERC']."</td>";
									echo "</tr>";
								}
							?>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2"><a style="text-decoration:none;" href="javascript:Excel_ECMECLIENT()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;Liste des ECME clients utilisés&nbsp;</a></td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<table width="100%">
				<tr>
					<td class="MoyenTitre">INGREDIENTS</td>
				</tr>
				<tr><td>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
						<tr>
							<td><b>&nbsp; Critères de recherche : </b></td>
							<td align="right" colspan="6"><input class="Bouton" name="Recherche_RAZING" type="submit" value="Vider les critères de recherche"></td>
							<td align="right">
							<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_CritereING()">&nbsp;<img src="../../../Images/Plus2.png" border="0" alt="Ajouter critères" title="Ajouter critères">&nbsp;&nbsp;</a>
							</td>
						</tr>
							<?php
								if($_SESSION['EXTRACT_INGIngredient']<>""){
									echo "<tr>";
									echo "<td>Ingrédient : ".$_SESSION['EXTRACT_INGIngredient']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_INGNumLot']<>""){
									echo "<tr>";
									echo "<td>N° lot : ".$_SESSION['EXTRACT_INGNumLot']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_INGDatePeremption']<>""){
									echo "<tr>";
									echo "<td>Date de péremption : ".$_SESSION['EXTRACT_INGDatePeremption']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_INGMSN']<>""){
									echo "<tr>";
									echo "<td>MSN : ".$_SESSION['EXTRACT_INGMSN']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_INGDossier']<>""){
									echo "<tr>";
									echo "<td>N° dossier : ".$_SESSION['EXTRACT_INGDossier']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_INGDu']<>""){
									echo "<tr>";
									echo "<td>Du : ".$_SESSION['EXTRACT_INGDu']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_INGAu']<>""){
									echo "<tr>";
									echo "<td>Au : ".$_SESSION['EXTRACT_INGAu']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_INGDateTERA']<>""){
									echo "<tr>";
									echo "<td>Date TERA : ".$_SESSION['EXTRACT_INGDateTERA']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_INGDateTERC']<>""){
									echo "<tr>";
									echo "<td>Date TERC : ".$_SESSION['EXTRACT_INGDateTERC']."</td>";
									echo "</tr>";
								}
							?>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2"><a style="text-decoration:none;" href="javascript:Excel_ING()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;Liste des ingrédients utilisés&nbsp;</a></td>
						</tr>
					</table>
				</td></tr>
				<tr>
					<td class="MoyenTitre">PROCEDES SPECIAUX</td>
				</tr>
				<tr><td>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
						<tr>
							<td><b>&nbsp; Critères de recherche : </b></td>
							<td align="right" colspan="6"><input class="Bouton" name="Recherche_RAZPS" type="submit" value="Vider les critères de recherche"></td>
							<td align="right">
							<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_CriterePS()">&nbsp;<img src="../../../Images/Plus2.png" border="0" alt="Ajouter critères" title="Ajouter critères">&nbsp;&nbsp;</a>
							</td>
						</tr>
							<?php
								if($_SESSION['EXTRACT_PSCompagnon']<>""){
									echo "<tr>";
									echo "<td>Compagnon : ".$_SESSION['EXTRACT_PSCompagnon']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_PSIQ']<>""){
									echo "<tr>";
									echo "<td>Qualiticien : ".$_SESSION['EXTRACT_PSIQ']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_PSReference']<>""){
									echo "<tr>";
									echo "<td>Référence procédé : ".$_SESSION['EXTRACT_PSReference']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_PSMSN']<>""){
									echo "<tr>";
									echo "<td>MSN : ".$_SESSION['EXTRACT_PSMSN']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_PSDossier']<>""){
									echo "<tr>";
									echo "<td>N° dossier : ".$_SESSION['EXTRACT_PSDossier']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_PSDu']<>""){
									echo "<tr>";
									echo "<td>Du : ".$_SESSION['EXTRACT_PSDu']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_PSAu']<>""){
									echo "<tr>";
									echo "<td>Au : ".$_SESSION['EXTRACT_PSAu']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_PSDateTERA']<>""){
									echo "<tr>";
									echo "<td>Date TERA : ".$_SESSION['EXTRACT_PSDateTERA']."</td>";
									echo "</tr>";
								}
								if($_SESSION['EXTRACT_PSDateTERC']<>""){
									echo "<tr>";
									echo "<td>Date TERC : ".$_SESSION['EXTRACT_PSDateTERC']."</td>";
									echo "</tr>";
								}
							?>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2"><a style="text-decoration:none;" href="javascript:Excel_PSUtilise()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;Liste des procédés spéciaux mis en oeuvre&nbsp;</a></td>
						</tr>
					</table>
				</td></tr>
				<tr><td height="4"></td></tr>
				<tr><td>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
						<tr><td height="4"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2"><a style="text-decoration:none;" href="javascript:Excel_PS()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;Liste des procédés spéciaux non identifiés&nbsp;</a></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>

<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>