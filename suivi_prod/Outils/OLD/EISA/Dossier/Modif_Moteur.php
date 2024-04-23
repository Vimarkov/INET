<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
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
		function FermerEtRecharger(){
			window.opener.location = "Liste_Moteur.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../../Fonctions.php");
require("../../Connexioni.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
Ecrire_Code_JS_Init_Date();
if($_POST){
	$requete="UPDATE sp_atrmoteur SET ";
	$requete.="PNVerinGauche1='".$_POST['PNVerinGauche1']."',";
	$requete.="PNVerinDroit1='".$_POST['PNVerinDroit1']."',";
	$requete.="PNHCU1='".$_POST['PNHCU1']."',";
	$requete.="SNVerinGauche1='".$_POST['SNVerinGauche1']."',";
	$requete.="SNVerinDroit1='".$_POST['SNVerinDroit1']."',";
	$requete.="SNHCU1='".$_POST['SNHCU1']."',";
	$requete.="PNVerinGauche2='".$_POST['PNVerinGauche2']."',";
	$requete.="PNVerinDroit2='".$_POST['PNVerinDroit2']."',";
	$requete.="PNHCU2='".$_POST['PNHCU2']."',";
	$requete.="SNVerinGauche2='".$_POST['SNVerinGauche2']."',";
	$requete.="SNVerinDroit2='".$_POST['SNVerinDroit2']."',";
	$requete.="SNHCU2='".$_POST['SNHCU2']."' ";
	$requete.="WHERE Id=".$_POST['Id'];
	$result=mysqli_query($bdd,$requete);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$titre="";
	$titre="Suivi des moteurs";
	$result=mysqli_query($bdd,"SELECT Id,MSN,TypeMoteur,PosteMontage,PNVerinGauche1,PNVerinDroit1,PNHCU1,SNVerinGauche1,SNVerinDroit1,SNHCU1,PNVerinGauche2,PNVerinDroit2,PNHCU2, SNVerinGauche2, SNVerinDroit2, SNHCU2 FROM sp_atrmoteur WHERE Id=".$_GET['Id']);
	$Ligne=mysqli_fetch_array($result);
?>
		<form id="formulaire" method="POST" action="Modif_Moteur.php" onSubmit="return VerifChamps();">
		<table width="100%">
			<tr>
				<td>
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="4"></td>
							<td class="TitrePage"><?php echo $titre;?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td>
				<input type="hidden" name="Id" value="<?php echo $Ligne['Id'];?>">
				</td>
			</tr>
			<tr>
				<td>
					<table width="95%"  align="center" class="TableCompetences">
						<tr>
							<td class="Libelle">&nbsp;MSN :
								<?php echo $Ligne['MSN'];?>
							</td>
							<td class="Libelle">&nbsp;Type de moteur :
								<?php echo $Ligne['TypeMoteur'];?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Poste de montage : 
								<?php echo $Ligne['PosteMontage'];?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td>
								<table>
									<tr>
										<td colspan="3" class="Libelle" align="center">Moteur 1</td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#00325F"></td>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">P/N</td>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">S/N</td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">Vérin Gauche</td>
										<td><input type="texte" name="PNVerinGauche1" size="15" value="<?php echo $Ligne['PNVerinGauche1'];?>"></td>
										<td><input type="texte" name="SNVerinGauche1" size="15" value="<?php echo $Ligne['SNVerinGauche1'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">Vérin Droit</td>
										<td><input type="texte" name="PNVerinDroit1" size="15" value="<?php echo $Ligne['PNVerinDroit1'];?>"></td>
										<td><input type="texte" name="SNVerinDroit1" size="15" value="<?php echo $Ligne['SNVerinDroit1'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">HCU (si IAE)</td>
										<td><input type="texte" name="PNHCU1" size="15" value="<?php echo $Ligne['PNHCU1'];?>"></td>
										<td><input type="texte" name="SNHCU1" size="15" value="<?php echo $Ligne['SNHCU1'];?>"></td>
									</tr>
								</table>
							</td>
							<td>
								<table>
									<tr>
										<td colspan="3" class="Libelle" align="center">Moteur 2</td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#00325F"></td>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">P/N</td>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">S/N</td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">Vérin Gauche</td>
										<td><input type="texte" name="PNVerinGauche2" size="15" value="<?php echo $Ligne['PNVerinGauche2'];?>"></td>
										<td><input type="texte" name="SNVerinGauche2" size="15" value="<?php echo $Ligne['SNVerinGauche2'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">Vérin Droit</td>
										<td><input type="texte" name="PNVerinDroit2" size="15" value="<?php echo $Ligne['PNVerinDroit2'];?>"></td>
										<td><input type="texte" name="SNVerinDroit2" size="15" value="<?php echo $Ligne['SNVerinDroit2'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">HCU (si IAE)</td>
										<td><input type="texte" name="PNHCU2" size="15" value="<?php echo $Ligne['PNHCU2'];?>"></td>
										<td><input type="texte" name="SNHCU2" size="15" value="<?php echo $Ligne['SNHCU2'];?>"></td>
									</tr>
								</table>							
							</td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td align="center">
					<input class="Bouton" type="submit" value="<?php echo "Valider";?>">
				</td>
			</tr>
		</table>
		</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>