<?php
if($_POST){
	if(isset($_POST['Btn_EnregistrerAide'])){
		$req="UPDATE moris_aideparagraphe
			SET EN='".addslashes($_POST['informationsGeneraleEN'])."',
			FR='".addslashes($_POST['informationsGeneraleFR'])."'
			WHERE NomParagraphe='INFORMATIONS GENERALE' ";
		$result=mysqli_query($bdd,$req);
		
		$req="UPDATE moris_aideparagraphe
			SET EN='".addslashes($_POST['besoinRecrutementEN'])."',
			FR='".addslashes($_POST['besoinRecrutementFR'])."'
			WHERE NomParagraphe='BESOIN STAFFING' ";
		$result=mysqli_query($bdd,$req);
		
		$req="UPDATE moris_aideparagraphe
			SET EN='".addslashes($_POST['productiviteEN'])."',
			FR='".addslashes($_POST['productiviteFR'])."'
			WHERE NomParagraphe='PRODUCTIVITE' ";
		$result=mysqli_query($bdd,$req);
		
		$req="UPDATE moris_aideparagraphe
			SET EN='".addslashes($_POST['qualiteEN'])."',
			FR='".addslashes($_POST['qualiteFR'])."'
			WHERE NomParagraphe='QUALITE' ";
		$result=mysqli_query($bdd,$req);
		
		$req="UPDATE moris_aideparagraphe
			SET EN='".addslashes($_POST['managementEN'])."',
			FR='".addslashes($_POST['managementFR'])."'
			WHERE NomParagraphe='MANAGEMENT' ";
		$result=mysqli_query($bdd,$req);
		
		$req="UPDATE moris_aideparagraphe
			SET EN='".addslashes($_POST['competenceEN'])."',
			FR='".addslashes($_POST['competenceFR'])."'
			WHERE NomParagraphe='COMPETENCES' ";
		$result=mysqli_query($bdd,$req);
		
		$req="UPDATE moris_aideparagraphe
			SET EN='".addslashes($_POST['securiteEN'])."',
			FR='".addslashes($_POST['securiteFR'])."'
			WHERE NomParagraphe='SECURITE' ";
		$result=mysqli_query($bdd,$req);
		
		$req="UPDATE moris_aideparagraphe
			SET EN='".addslashes($_POST['ncEN'])."',
			FR='".addslashes($_POST['ncFR'])."'
			WHERE NomParagraphe='NC' ";
		$result=mysqli_query($bdd,$req);
		
		$req="UPDATE moris_aideparagraphe
			SET EN='".addslashes($_POST['prmEN'])."',
			FR='".addslashes($_POST['prmFR'])."'
			WHERE NomParagraphe='PRM' ";
		$result=mysqli_query($bdd,$req);
	}
}
?>
<?php
function SousTitre($Libelle,$Lien,$Selected){
	$tiret="";
	$couleurTexte="#00577c";
	if($Selected==true){$tiret="border-bottom:3px solid #ffffff;font-style:italic;font-size:14px;";$couleurTexte="#ffffff";}
	echo "<td style=\"height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;".$tiret."\" onclick=\"window.stop();\">
		<a style=\"text-decoration:none;width:70px;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;\" onmouseover=\"this.style.color='".$couleurTexte."';\" onmouseout=\"this.style.color='".$couleurTexte."';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}
?>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr bgcolor="#6EB4CD">
		<?php
		if($_SESSION["Langue"]=="FR"){SousTitre("ACCES SUPP.","Outils/MORIS2/TableauDeBord.php?Menu=16",false);}
		else{SousTitre("ADDITIONAL ACCESS","Outils/MORIS2/TableauDeBord.php?Menu=16",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("ADMINISTRATEURS","Outils/MORIS2/TableauDeBord.php?Menu=3",false);}
		else{SousTitre("ADMINISTRATOR","Outils/MORIS2/TableauDeBord.php?Menu=3",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("LISTE PRESTATIONS","Outils/MORIS2/TableauDeBord.php?Menu=4",false);}
		else{SousTitre("LIST OF SITE","Outils/MORIS2/TableauDeBord.php?Menu=4",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("AIDE","Outils/MORIS2/TableauDeBord.php?Menu=5",true);}
		else{SousTitre("HELP","Outils/MORIS2/TableauDeBord.php?Menu=5",true);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=10",false);}
		else{SousTitre("CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=10",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("DIVISION CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=13",false);}
		else{SousTitre("CUSTOMER DIVISION","Outils/MORIS2/TableauDeBord.php?Menu=13",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("CONTRAT","Outils/MORIS2/TableauDeBord.php?Menu=9",false);}
		else{SousTitre("CONTRACT","Outils/MORIS2/TableauDeBord.php?Menu=9",false);}

		if($_SESSION["Langue"]=="FR"){SousTitre("ENTITE ACHAT","Outils/MORIS2/TableauDeBord.php?Menu=11",false);}
		else{SousTitre("PURCHASE ENTITY","Outils/MORIS2/TableauDeBord.php?Menu=11",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("PROGRAMME / PRODUIT","Outils/MORIS2/TableauDeBord.php?Menu=12",false);}
		else{SousTitre("PROGRAM / PRODUCT","Outils/MORIS2/TableauDeBord.php?Menu=12",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("FAMILLE METIER/FONCTION","Outils/MORIS2/TableauDeBord.php?Menu=15",false);}
		else{SousTitre("JOB/POSITION FAMILY","Outils/MORIS2/TableauDeBord.php?Menu=15",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("OBJECTIFS","Outils/MORIS2/TableauDeBord.php?Menu=18",false);}
		else{SousTitre("OBJECTIVES","Outils/MORIS2/TableauDeBord.php?Menu=18",false);}
		
		?>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center" colspan="11">
			<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_EnregistrerAide" value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Enregistrer";}?>">
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td colspan="11">
		<table cellpadding="0" cellspacing="0" align="center" style="width:100%;">
			<tr>
				<td style="width:50%;">
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='INFORMATIONS GENERALE' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						$Ligne=mysqli_fetch_array($result);
					?>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
						<tr>
							<td class="EnTeteTableauCompetences" colspan="2" bgcolor="#009cdc" style="text-align:center;font-size:15px;color:#ffffff;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "GENERAL INFORMATION";}else{echo "INFORMATIONS GENERALES";} ?></td>
						</tr>
						<tr>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "EN";}else{echo "EN";} ?></td>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "FR";}else{echo "FR";} ?></td>
						</tr>
						<tr>
							<td width="50%" style="text-align:center"><textarea name="informationsGeneraleEN" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['EN'])); ?></textarea></td>
							<td width="50%" style="text-align:center"><textarea name="informationsGeneraleFR" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['FR'])); ?></textarea></td>
						</tr>
						<tr><td height="10"></td></tr>
					</table>
				</td>
				<td style="width:50%;">
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='BESOIN STAFFING' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						$Ligne=mysqli_fetch_array($result);
					?>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
						<tr>
							<td class="EnTeteTableauCompetences" colspan="2" bgcolor="#009cdc" style="text-align:center;font-size:15px;color:#ffffff;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "NEED STAFFING";}else{echo "PLAN DE CHARGES PREVISIONNEL";} ?></td>
						</tr>
						<tr>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "EN";}else{echo "EN";} ?></td>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "FR";}else{echo "FR";} ?></td>
						</tr>
						<tr>
							<td width="50%" style="text-align:center"><textarea name="besoinRecrutementEN" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['EN'])); ?></textarea></td>
							<td width="50%" style="text-align:center"><textarea name="besoinRecrutementFR" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['FR'])); ?></textarea></td>
						</tr>
						<tr><td height="10"></td></tr>
					</table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td style="width:50%;">
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='PRODUCTIVITE' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						$Ligne=mysqli_fetch_array($result);
					?>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
						<tr>
							<td class="EnTeteTableauCompetences" colspan="2" bgcolor="#009cdc" style="text-align:center;font-size:15px;color:#ffffff;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "PRODUCTIVITY";}else{echo "PRODUCTIVITE";} ?></td>
						</tr>
						<tr>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "EN";}else{echo "EN";} ?></td>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "FR";}else{echo "FR";} ?></td>
						</tr>
						<tr>
							<td width="50%" style="text-align:center"><textarea name="productiviteEN" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['EN'])); ?></textarea></td>
							<td width="50%" style="text-align:center"><textarea name="productiviteFR" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['FR'])); ?></textarea></td>
						</tr>
						<tr><td height="10"></td></tr>
					</table>
				</td>
				<td style="width:50%;">
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='QUALITE' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						$Ligne=mysqli_fetch_array($result);
					?>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
						<tr>
							<td class="EnTeteTableauCompetences" colspan="2" bgcolor="#009cdc" style="text-align:center;font-size:15px;color:#ffffff;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "QUALITY";}else{echo "QUALITE";} ?></td>
						</tr>
						<tr>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "EN";}else{echo "EN";} ?></td>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "FR";}else{echo "FR";} ?></td>
						</tr>
						<tr>
							<td width="50%" style="text-align:center"><textarea name="qualiteEN" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['EN'])); ?></textarea></td>
							<td width="50%" style="text-align:center"><textarea name="qualiteFR" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['FR'])); ?></textarea></td>
						</tr>
						<tr><td height="10"></td></tr>
					</table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td style="width:50%;">
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='MANAGEMENT' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						$Ligne=mysqli_fetch_array($result);
					?>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
						<tr>
							<td class="EnTeteTableauCompetences" colspan="2" bgcolor="#009cdc" style="text-align:center;font-size:15px;color:#ffffff;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "MANAGEMENT";}else{echo "MANAGEMENT";} ?></td>
						</tr>
						<tr>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "EN";}else{echo "EN";} ?></td>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "FR";}else{echo "FR";} ?></td>
						</tr>
						<tr>
							<td width="50%" style="text-align:center"><textarea name="managementEN" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['EN'])); ?></textarea></td>
							<td width="50%" style="text-align:center"><textarea name="managementFR" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['FR'])); ?></textarea></td>
						</tr>
						<tr><td height="10"></td></tr>
					</table>
				</td>
				<td style="width:50%;">
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='PRM' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						$Ligne=mysqli_fetch_array($result);
					?>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
						<tr>
							<td class="EnTeteTableauCompetences" colspan="2" bgcolor="#009cdc" style="text-align:center;font-size:15px;color:#ffffff;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "PRM & SATISFACTION CLIENTS";}else{echo "PRM & SATISFACTION CLIENTS";} ?></td>
						</tr>
						<tr>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "EN";}else{echo "EN";} ?></td>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "FR";}else{echo "FR";} ?></td>
						</tr>
						<tr>
							<td width="50%" style="text-align:center"><textarea name="prmEN" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['EN'])); ?></textarea></td>
							<td width="50%" style="text-align:center"><textarea name="prmFR" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['FR'])); ?></textarea></td>
						</tr>
						<tr><td height="10"></td></tr>
					</table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td style="width:50%;">
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='COMPETENCES' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						$Ligne=mysqli_fetch_array($result);
					?>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
						<tr>
							<td class="EnTeteTableauCompetences" colspan="2" bgcolor="#009cdc" style="text-align:center;font-size:15px;color:#ffffff;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "SKILLS";}else{echo "COMPETENCES";} ?></td>
						</tr>
						<tr>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "EN";}else{echo "EN";} ?></td>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "FR";}else{echo "FR";} ?></td>
						</tr>
						<tr>
							<td width="50%" style="text-align:center"><textarea name="competenceEN" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['EN'])); ?></textarea></td>
							<td width="50%" style="text-align:center"><textarea name="competenceFR" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['FR'])); ?></textarea></td>
						</tr>
						<tr><td height="10"></td></tr>
					</table>
				</td>
				<td style="width:50%;">
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='SECURITE' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						$Ligne=mysqli_fetch_array($result);
					?>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
						<tr>
							<td class="EnTeteTableauCompetences" colspan="2" bgcolor="#009cdc" style="text-align:center;font-size:15px;color:#ffffff;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "SECURITY";}else{echo "SECURITE";} ?></td>
						</tr>
						<tr>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "EN";}else{echo "EN";} ?></td>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "FR";}else{echo "FR";} ?></td>
						</tr>
						<tr>
							<td width="50%" style="text-align:center"><textarea name="securiteEN" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['EN'])); ?></textarea></td>
							<td width="50%" style="text-align:center"><textarea name="securiteFR" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['FR'])); ?></textarea></td>
						</tr>
						<tr><td height="10"></td></tr>
					</table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td style="width:50%;">
					<?php 
						$req="SELECT FR,EN
						FROM moris_aideparagraphe
						WHERE moris_aideparagraphe.NomParagraphe='NC' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						$Ligne=mysqli_fetch_array($result);
					?>
					<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
						<tr>
							<td class="EnTeteTableauCompetences" colspan="2" bgcolor="#009cdc" style="text-align:center;font-size:15px;color:#ffffff;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "NC and/or DAC OPEN";}else{echo "NC et/ou DAC OUVERTES";} ?></td>
						</tr>
						<tr>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "EN";}else{echo "EN";} ?></td>
							<td width="50%" class="Libelle" style="text-align:center"><?php if($_SESSION['Langue']=="EN"){echo "FR";}else{echo "FR";} ?></td>
						</tr>
						<tr>
							<td width="50%" style="text-align:center"><textarea name="ncEN" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['EN'])); ?></textarea></td>
							<td width="50%" style="text-align:center"><textarea name="ncFR" cols="50" rows="8" noresize="noresize"><?php echo str_replace("\\","",stripslashes($Ligne['FR'])); ?></textarea></td>
						</tr>
						<tr><td height="10"></td></tr>
					</table>
				</td>
				<td style="width:50%;">
					
				</td>
			</tr>
		</table>
	</td>
	</tr>
	<tr><td height="10"></td></tr>
</table>