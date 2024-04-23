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
	$requete.="Id_Controleur=".$_POST['controleur'].",";
	$requete.="PN_EngineLH='".addslashes($_POST['PN_EngineLH'])."',";
	$requete.="PN_FanCowlLHELH='".addslashes($_POST['PN_FanCowlLHELH'])."',";
	$requete.="PN_FanCowlRHELH='".addslashes($_POST['PN_FanCowlRHELH'])."',";
	$requete.="PN_FanReverserLHELH='".addslashes($_POST['PN_FanReverserLHELH'])."',";
	$requete.="PN_FanReverserRHELH='".addslashes($_POST['PN_FanReverserRHELH'])."',";
	$requete.="PN_DemountablePowerLH='".addslashes($_POST['PN_DemountablePowerLH'])."',";
	$requete.="PN_EngineRH='".addslashes($_POST['PN_EngineRH'])."',";
	$requete.="PN_FanCowlLHERH='".addslashes($_POST['PN_FanCowlLHERH'])."',";
	$requete.="PN_FanCowlRHERH='".addslashes($_POST['PN_FanCowlRHERH'])."',";
	$requete.="PN_FanReverserLHERH='".addslashes($_POST['PN_FanReverserLHERH'])."',";
	$requete.="PN_FanReverserRHERH='".addslashes($_POST['PN_FanReverserRHERH'])."',";
	$requete.="PN_DemountablePowerRH='".addslashes($_POST['PN_DemountablePowerRH'])."',";
	$requete.="SN_EngineLH='".addslashes($_POST['SN_EngineLH'])."',";
	$requete.="SN_FanCowlLHELH='".addslashes($_POST['SN_FanCowlLHELH'])."',";
	$requete.="SN_FanCowlRHELH='".addslashes($_POST['SN_FanCowlRHELH'])."',";
	$requete.="SN_FanReverserLHELH='".addslashes($_POST['SN_FanReverserLHELH'])."',";
	$requete.="SN_FanReverserRHELH='".addslashes($_POST['SN_FanReverserRHELH'])."',";
	$requete.="SN_DemountablePowerLH='".addslashes($_POST['SN_DemountablePowerLH'])."',";
	$requete.="SN_EngineRH='".addslashes($_POST['SN_EngineRH'])."',";
	$requete.="SN_FanCowlLHERH='".addslashes($_POST['SN_FanCowlLHERH'])."',";
	$requete.="SN_FanCowlRHERH='".addslashes($_POST['SN_FanCowlRHERH'])."',";
	$requete.="SN_FanReverserLHERH='".addslashes($_POST['SN_FanReverserLHERH'])."',";
	$requete.="SN_FanReverserRHERH='".addslashes($_POST['SN_FanReverserRHERH'])."',";
	$requete.="SN_DemountablePowerRH='".addslashes($_POST['SN_DemountablePowerRH'])."',";
	$requete.="Doc_EngineLH='".addslashes($_POST['Doc_EngineLH'])."',";
	$requete.="Doc_FanCowlLHELH='".addslashes($_POST['Doc_FanCowlLHELH'])."',";
	$requete.="Doc_FanCowlRHELH='".addslashes($_POST['Doc_FanCowlRHELH'])."',";
	$requete.="Doc_FanReverserLHELH='".addslashes($_POST['Doc_FanReverserLHELH'])."',";
	$requete.="Doc_FanReverserRHELH='".addslashes($_POST['Doc_FanReverserRHELH'])."',";
	$requete.="Doc_DemountablePowerLH='".addslashes($_POST['Doc_DemountablePowerLH'])."',";
	$requete.="Doc_EngineRH='".addslashes($_POST['Doc_EngineRH'])."',";
	$requete.="Doc_FanCowlLHERH='".addslashes($_POST['Doc_FanCowlLHERH'])."',";
	$requete.="Doc_FanCowlRHERH='".addslashes($_POST['Doc_FanCowlRHERH'])."',";
	$requete.="Doc_FanReverserLHERH='".addslashes($_POST['Doc_FanReverserLHERH'])."',";
	$requete.="Doc_FanReverserRHERH='".addslashes($_POST['Doc_FanReverserRHERH'])."',";
	$requete.="Doc_DemountablePowerRH='".addslashes($_POST['Doc_DemountablePowerRH'])."'";
	$requete.=" WHERE Id=".$_POST['Id'];
	$result=mysqli_query($bdd,$requete);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$titre="";
	$titre="Liste des EC";
	$req="SELECT Id,MSN,TypeMoteur,Id_Controleur,PN_EngineLH,PN_FanCowlLHELH,PN_FanCowlRHELH,PN_FanReverserLHELH,PN_FanReverserRHELH, ";
	$req.="PN_DemountablePowerLH,PN_EngineRH,PN_FanCowlLHERH,PN_FanCowlRHERH,PN_FanReverserLHERH,PN_FanReverserRHERH,PN_DemountablePowerRH,";
	$req.="SN_EngineLH,SN_FanCowlLHELH,SN_FanCowlRHELH,SN_FanReverserLHELH,SN_FanReverserRHELH,SN_DemountablePowerLH,SN_EngineRH,";
	$req.="SN_FanCowlLHERH,SN_FanCowlRHERH,SN_FanReverserLHERH,SN_FanReverserRHERH,SN_DemountablePowerRH,Doc_EngineLH,Doc_FanCowlLHELH,";
	$req.="Doc_FanCowlRHELH,Doc_FanReverserLHELH,Doc_FanReverserRHELH,Doc_DemountablePowerLH,Doc_EngineRH,Doc_FanCowlLHERH,Doc_FanCowlRHERH,";
	$req.="Doc_FanReverserLHERH,Doc_FanReverserRHERH,Doc_DemountablePowerRH ";
	$req.="FROM sp_atrmoteur WHERE Id=".$_GET['Id'];
	$result=mysqli_query($bdd,$req);
	$Ligne=mysqli_fetch_array($result);
	
	$disabled="";
	if($Ligne['TypeMoteur']=="LEAP"){$disabled="disabled='disabled'";}
?>
		<form id="formulaire" method="POST" action="Modif_EC.php" onSubmit="return VerifChamps();">
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
							<td colspan="2" class="Libelle">&nbsp;MSN : <?php echo $Ligne['MSN'];?>
							</td>
							<td class="Libelle">&nbsp;Type de moteur :
								<?php echo $Ligne['TypeMoteur'];?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr align="left">
							<td class="Libelle">&nbsp;Contrôleur : </td>
							<td>
								<select id="controleur" name="controleur" style="width:150px;">
									<?php
									echo"<option name='0' value='0'></option>";
									$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
									$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=463 AND SUBSTR(sp_acces.Droit,5,1)='1' ORDER BY Nom, Prenom;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$i=0;
										while($rowIQ=mysqli_fetch_array($result)){
											$selected="";
											if($rowIQ['Id']==$Ligne['Id_Controleur']){$selected="selected";}
											echo "<option value='".$rowIQ['Id']."' ".$selected.">".$rowIQ['Nom']." ".$rowIQ['Prenom']."</option>";
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="3" align="center">
								<table>
									<tr>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">Désignation</td>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">PART NUMBER</td>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">N° de série relevé<br>sur élément</td>
										<td class="Libelle" bgcolor="#00325F" style="text-decoration:none;color:#ffffff;font-weight:bold;">N° doc libératoire</td>
									</tr>
									<tr>
										<td class="Libelle" style="text-decoration:none;font-weight:bold;">ENGINE LH</td>
										<td><input type="texte" name="PN_EngineLH" size="20" <?php echo $disabled;?> value="<?php echo $Ligne['PN_EngineLH'];?>"></td>
										<td><input type="texte" name="SN_EngineLH" size="20" value="<?php echo $Ligne['SN_EngineLH'];?>"></td>
										<td><input type="texte" name="Doc_EngineLH" size="20" value="<?php if($Ligne['Doc_EngineLH']<>""){echo $Ligne['Doc_EngineLH'];}else{echo "Non Nécessaire";} ?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#b3f977" style="text-decoration:none;font-weight:bold;">FAN COWL LH (ENGINE LH)</td>
										<td bgcolor="#b3f977"><input type="texte" name="PN_FanCowlLHELH" size="20" value="<?php echo $Ligne['PN_FanCowlLHELH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="SN_FanCowlLHELH" size="20" value="<?php echo $Ligne['SN_FanCowlLHELH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="Doc_FanCowlLHELH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['Doc_FanCowlLHELH'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" style="text-decoration:none;font-weight:bold;">FAN COWL RH (ENGINE LH)</td>
										<td><input type="texte" name="PN_FanCowlRHELH" size="20" value="<?php echo $Ligne['PN_FanCowlRHELH'];?>"></td>
										<td><input type="texte" name="SN_FanCowlRHELH" size="20" value="<?php echo $Ligne['SN_FanCowlRHELH'];?>"></td>
										<td><input type="texte" name="Doc_FanCowlRHELH" size="20" <?php echo $disabled;?> value="<?php echo $Ligne['Doc_FanCowlRHELH'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#b3f977" style="text-decoration:none;font-weight:bold;">FAN REVERSER LH (ENGINE LH)</td>
										<td bgcolor="#b3f977"><input type="texte" name="PN_FanReverserLHELH" size="20" value="<?php echo $Ligne['PN_FanReverserLHELH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="SN_FanReverserLHELH" size="20" value="<?php echo $Ligne['SN_FanReverserLHELH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="Doc_FanReverserLHELH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['Doc_FanReverserLHELH'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" style="text-decoration:none;font-weight:bold;">FAN REVERSER RH (ENGINE LH)</td>
										<td><input type="texte" name="PN_FanReverserRHELH" size="20" value="<?php echo $Ligne['PN_FanReverserRHELH'];?>"></td>
										<td><input type="texte" name="SN_FanReverserRHELH" size="20" value="<?php echo $Ligne['SN_FanReverserRHELH'];?>"></td>
										<td><input type="texte" name="Doc_FanReverserRHELH" size="20" <?php echo $disabled;?> value="<?php echo $Ligne['Doc_FanReverserRHELH'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#b3f977" style="text-decoration:none;font-weight:bold;">DEMOUNTABLE POWER PLANT-LH</td>
										<td bgcolor="#b3f977"><input type="texte" name="PN_DemountablePowerLH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['PN_DemountablePowerLH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="SN_DemountablePowerLH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['SN_DemountablePowerLH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="Doc_DemountablePowerLH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['Doc_DemountablePowerLH'];?>"></td>
									</tr>
									
									
									<tr>
										<td class="Libelle" style="text-decoration:none;font-weight:bold;">ENGINE RH</td>
										<td><input type="texte" name="PN_EngineRH" size="20" <?php echo $disabled;?> value="<?php echo $Ligne['PN_EngineRH'];?>"></td>
										<td><input type="texte" name="SN_EngineRH" size="20" value="<?php echo $Ligne['SN_EngineRH'];?>"></td>
										<td><input type="texte" name="Doc_EngineRH" size="20" value="<?php if($Ligne['Doc_EngineRH']<>""){echo $Ligne['Doc_EngineRH'];}else{echo "Non Nécessaire";}?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#b3f977" style="text-decoration:none;font-weight:bold;">FAN COWL LH (ENGINE RH)</td>
										<td bgcolor="#b3f977"><input type="texte" name="PN_FanCowlLHERH" size="20" value="<?php echo $Ligne['PN_FanCowlLHERH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="SN_FanCowlLHERH" size="20" value="<?php echo $Ligne['SN_FanCowlLHERH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="Doc_FanCowlLHERH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['Doc_FanCowlLHERH'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" style="text-decoration:none;font-weight:bold;">FAN COWL RH (ENGINE RH)</td>
										<td><input type="texte" name="PN_FanCowlRHERH" size="20" value="<?php echo $Ligne['PN_FanCowlRHERH'];?>"></td>
										<td><input type="texte" name="SN_FanCowlRHERH" size="20" value="<?php echo $Ligne['SN_FanCowlRHERH'];?>"></td>
										<td><input type="texte" name="Doc_FanCowlRHERH" size="20" <?php echo $disabled;?> value="<?php echo $Ligne['Doc_FanCowlRHERH'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#b3f977" style="text-decoration:none;font-weight:bold;">FAN REVERSER LH (ENGINE RH)</td>
										<td bgcolor="#b3f977"><input type="texte" name="PN_FanReverserLHERH" size="20" value="<?php echo $Ligne['PN_FanReverserLHERH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="SN_FanReverserLHERH" size="20" value="<?php echo $Ligne['SN_FanReverserLHERH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="Doc_FanReverserLHERH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['Doc_FanReverserLHERH'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" style="text-decoration:none;font-weight:bold;">FAN REVERSER RH (ENGINE RH)</td>
										<td><input type="texte" name="PN_FanReverserRHERH" size="20" value="<?php echo $Ligne['PN_FanReverserRHERH'];?>"></td>
										<td><input type="texte" name="SN_FanReverserRHERH" size="20" value="<?php echo $Ligne['SN_FanReverserRHERH'];?>"></td>
										<td><input type="texte" name="Doc_FanReverserRHERH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['Doc_FanReverserRHERH'];?>"></td>
									</tr>
									<tr>
										<td class="Libelle" bgcolor="#b3f977" style="text-decoration:none;;font-weight:bold;">DEMOUNTABLE POWER PLANT-RH</td>
										<td bgcolor="#b3f977"><input type="texte" name="PN_DemountablePowerRH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['PN_DemountablePowerRH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="SN_DemountablePowerRH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['SN_DemountablePowerRH'];?>"></td>
										<td bgcolor="#b3f977"><input type="texte" name="Doc_DemountablePowerRH" <?php echo $disabled;?> size="20" value="<?php echo $Ligne['Doc_DemountablePowerRH'];?>"></td>
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