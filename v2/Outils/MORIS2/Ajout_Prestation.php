<html>
<head>
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.prestation.value=='0'){alert('You do not select a site.');return false;}
				if(formulaire.dateDemarrage.value==''){alert('You did not enter the start date.');return false;}
			}
			else{
				if(formulaire.prestation.value=='0'){alert('Vous n\'avez pas sélectionné de prestation.');return false;}
				if(formulaire.dateDemarrage.value==''){alert('Vous n\'avez pas renseigné la date de démarrage.');return false;}
			}
			return true;
		}
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
		function OuvreFenetreAjout(Id_Prestation,Id){
			var w=window.open("Ajout_SuiviDate.php?Mode=A&Id_Prestation="+Id_Prestation+"&Id="+Id,"PageSuivi","status=no,menubar=no,scrollbars=yes,width=500,height=150");
			w.focus();
		}
		function OuvreFenetreModif(Id_Prestation,Id){
			var w=window.open("Ajout_SuiviDate.php?Mode=M&Id_Prestation="+Id_Prestation+"&Id="+Id,"PageSuivi","status=no,menubar=no,scrollbars=yes,width=500,height=150");
			w.focus();
		}
		function OuvreFenetreSuppr(Id_Prestation,Id){
			var w=window.open("Ajout_SuiviDate.php?Mode=S&Id_Prestation="+Id_Prestation+"&Id="+Id,"PageSuivi","status=no,menubar=no,scrollbars=yes,width=500,height=50");
			w.focus();
		}
	</script>
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/webforms2-0/webforms2-p.js"></script>	
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="../JS/colorpicker.js"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");

if($_POST){
	if($_POST['Mode']=="A"){
		$requete="UPDATE new_competences_prestation 
				SET Id_Contrat=".$_POST['contrat'].",
				Id_Client=".$_POST['client'].",
				Id_DivisionClient=".$_POST['divisionClient'].",
				Id_EntiteAchat=".$_POST['entiteAchat'].",
				UtiliseMORIS=1,
				Id_FamilleR03=".$_POST['familleR03'].",
				ToleranceOTDOQD=".$_POST['tolerance'].",
				ChargeADesactive=".$_POST['charge'].",
				ProductiviteADesactive=".$_POST['productivite'].",
				PlanPreventionADesactivite=".$_POST['planPrevention'].",
				PolyvalenceADesactive=".$_POST['polyvalence'].",
				OTDOQDADesactive=".$_POST['otdOqd'].",
				ManagementADesactive=".$_POST['management'].",
				CompetenceADesactive=".$_POST['competence'].",
				SecuriteADesactive=".$_POST['securite'].",
				PRMADesactive=".$_POST['prm'].",
				NCADesactive=".$_POST['nc']."
				WHERE Id=".$_POST['prestation']." ";
		$result=mysqli_query($bdd,$requete);
		
		$req="INSERT INTO moris_datesuivi (Id_Prestation,DateDebut,DateFin) VALUES (".$_POST['prestation'].",'".TrsfDate_($_POST['dateDemarrage'])."','0001-01-01') ";
		$result=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE new_competences_prestation 
				SET Id_Contrat=".$_POST['contrat'].",
				Id_Client=".$_POST['client'].",
				Id_DivisionClient=".$_POST['divisionClient'].",
				Id_EntiteAchat=".$_POST['entiteAchat'].",
				Id_FamilleR03=".$_POST['familleR03'].",
				ToleranceOTDOQD=".$_POST['tolerance'].",
				ChargeADesactive=".$_POST['charge'].",
				ProductiviteADesactive=".$_POST['productivite'].",
				PlanPreventionADesactivite=".$_POST['planPrevention'].",
				PolyvalenceADesactive=".$_POST['polyvalence'].",
				OTDOQDADesactive=".$_POST['otdOqd'].",
				ManagementADesactive=".$_POST['management'].",
				CompetenceADesactive=".$_POST['competence'].",
				SecuriteADesactive=".$_POST['securite'].",
				PRMADesactive=".$_POST['prm'].",
				NCADesactive=".$_POST['nc']."
				WHERE Id=".$_POST['id']." ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		$nbResultaMoisPresta=0;
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Libelle, RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,Sigle,DateDebut,DateFin,Id_Contrat,Id_FamilleR03,ToleranceOTDOQD,ChargeADesactive,ProductiviteADesactive,PlanPreventionADesactivite,PolyvalenceADesactive,Id_Client,Id_DivisionClient,Id_Programme,MailAcheteur,MailDO,Id_EntiteAchat,OTDOQDADesactive,ManagementADesactive,CompetenceADesactive,SecuriteADesactive,PRMADesactive,NCADesactive FROM new_competences_prestation WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
			
			$req="SELECT Id,RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,Sigle,Id_Contrat,
				Id_CoorEquipe,Id_RespProjet,Id_EntiteAchat,MailAcheteur,MailDO,
				(SELECT Libelle FROM moris_programme WHERE Id=Id_Programme) AS Programme
			FROM moris_moisprestation
			WHERE moris_moisprestation.Id_Prestation=".$_GET['Id']." 
			AND Suppr=0
			ORDER BY Id DESC
			";
			$result=mysqli_query($bdd,$req);
			$nbResultaMoisPresta=mysqli_num_rows($result);
			if($nbResultaMoisPresta>0){
				$LigneMoisPrestation=mysqli_fetch_array($result);
			}
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Prestation.php" onSubmit="<?php if($_GET['Mode']=="A"){echo "return VerifChamps('".$_SESSION['Langue']."');";}?>" >
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?> </td>
				<td colspan="3">
					<?php 
					if($_GET['Mode']=="M"){echo stripslashes($Ligne['Libelle']);}
					else{
					?>
					<select	id="prestation" name="prestation" style="width:300px;">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM new_competences_prestation WHERE UtiliseMORIS=0 AND Id_Plateforme IN (1,3,4,9,10,16,19,23,28,29) AND Active=0 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($row['Id']==$Ligne['Id']){$selected="selected";}
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>";
								}
							}
						?>
					</select>
					<?php } ?>
				</td>
				<?php if($_GET['Mode']=="A"){?>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Follow-up start date";}else{echo "Date démarrage du suivi";} ?> </td>
				<td>
					<input type="date" name="dateDemarrage" id="dateDemarrage" value="<?php echo AfficheDateFR(date('Y-m-d')); ?>" />
				</td>
				<?php }?>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Contract";}else{echo "Contrat";} ?> </td>
				<td>
					<select	id="contrat" name="contrat" style="width:200px;">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM moris_contrat WHERE Suppr=0 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($row['Id']==$Ligne['Id_Contrat']){$selected="selected";}
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>";
								}
							}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Client";}else{echo "Client";} ?> </td>
				<td>
					<select	id="client" name="client" style="width:150px;">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM moris_client WHERE Suppr=0 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($row['Id']==$Ligne['Id_Client']){$selected="selected";}
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>";
								}
							}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Customer division";}else{echo "Division client";} ?> </td>
				<td>
					<select	id="divisionClient" name="divisionClient" style="width:150px;">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM moris_divisionclient WHERE Suppr=0 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($row['Id']==$Ligne['Id_DivisionClient']){$selected="selected";}
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>";
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Family R03";}else{echo "Famille R03";} ?> </td>
				<td>
					<select	id="familleR03" name="familleR03" style="width:150px;">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Num, Libelle FROM moris_famille_r03 WHERE Suppr=0 ORDER BY Num";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($row['Id']==$Ligne['Id_FamilleR03']){$selected="selected";}
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Num'])." - ".stripslashes($row['Libelle'])."</option>";
								}
							}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Purchasing entity";}else{echo "Entité achat";} ?> </td>
				<td>
					<select	id="entiteAchat" name="entiteAchat" style="width:150px;">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM moris_entiteachat WHERE Suppr=0 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($row['Id']==$Ligne['Id_EntiteAchat']){$selected="selected";}
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>";
								}
							}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "OTD/OQD tolerance";}else{echo "Tolérance OTD/OQD";} ?> </td>
				<td>
					<select	id="tolerance" name="tolerance">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['ToleranceOTDOQD']==0){echo "selected";}}?>>Inactif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['ToleranceOTDOQD']==1){echo "selected";}}?>>Actif</option>
					</select>
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Charge/Capacité";}else{echo "Charge/Capacity";} ?> </td>
				<td>
					<select	id="charge" name="charge">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['ChargeADesactive']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['ChargeADesactive']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Productivité";}else{echo "Productivity";} ?> </td>
				<td>
					<select	id="productivite" name="productivite">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['ProductiviteADesactive']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['ProductiviteADesactive']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Plan de prévention";}else{echo "Prevention plan";} ?> </td>
				<td>
					<select	id="planPrevention" name="planPrevention">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['PlanPreventionADesactivite']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['PlanPreventionADesactivite']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Polyvalence";}else{echo "Versatility";} ?> </td>
				<td>
					<select	id="polyvalence" name="polyvalence">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['PolyvalenceADesactive']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['PolyvalenceADesactive']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Compétence";}else{echo "Skill";} ?> </td>
				<td>
					<select	id="competence" name="competence">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['CompetenceADesactive']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['CompetenceADesactive']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Management";}else{echo "Management";} ?> </td>
				<td>
					<select	id="management" name="management">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['ManagementADesactive']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['ManagementADesactive']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Sécurité";}else{echo "Security";} ?> </td>
				<td>
					<select	id="securite" name="securite">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['SecuriteADesactive']==0){echo "selected";}}?>>Saisie automatique</option>
						<option value="-1" <?php if($_GET['Mode']=="M"){if($Ligne['SecuriteADesactive']==-1){echo "selected";}}?>>Saisie manuelle</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['SecuriteADesactive']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "PRM & Satisfaction client";}else{echo "PRM & Customer Satisfaction";} ?> </td>
				<td>
					<select	id="prm" name="prm">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['PRMADesactive']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['PRMADesactive']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "NC & RC";}else{echo "NC & RC";} ?> </td>
				<td>
					<select	id="nc" name="nc">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['NCADesactive']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['NCADesactive']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "OTD & OQD";}else{echo "OTD & OQD";} ?> </td>
				<td>
					<select	id="otdOqd" name="otdOqd">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['OTDOQDADesactive']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['OTDOQDADesactive']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
			</tr>
			<tr><td height="20px"></td></tr>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Siglum";}else{echo "Sigle";} ?> </td>
				<td>
					<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['Sigle']);} ?>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Reference project";}else{echo "Ref CDC";} ?> </td>
				<td>
					<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['RefCDC']);} ?>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Project / WP name";}else{echo "Intitulé CDC/WP";} ?> </td>
				<td>
					<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['IntituleCDC']);} ?>
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			</tr>
			<tr class="TitreColsUsers">
				
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "EGP Buyer in charge of contract";}else{echo "Acheteur client";} ?> </td>
				<td>
					<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['AcheteurClient']);} ?>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Buyer mail";}else{echo "Mail acheteur";} ?> </td>
				<td>
					<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['MailAcheteur']);} ?>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Program / Product";}else{echo "Programme / Produit";} ?> </td>
				<td>
					<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['Programme']);} ?>
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Donneur d'ordre";} ?> </td>
				<td>
					<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['DonneurOrdre']);} ?>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Customer mail";}else{echo "Mail donneur d'ordre";} ?> </td>
				<td>
					<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['MailDO']);} ?>
				</td>
			</tr>			
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
			<?php if($_GET['Mode']=="M"){?>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td colspan="6" align="left">
					<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:30%;">
						<tr>
							<td class="EnTeteTableauCompetences" width="13%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Follow-up start date";}else{echo "Date de début de suivi";} ?></td>
							<td class="EnTeteTableauCompetences" width="13%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Follow-up end date";}else{echo "Date de fin de suivi";} ?></td>
							<td class="EnTeteTableauCompetences" width="4%" colspan='2'>
								<a href="javascript:OuvreFenetreAjout(<?php echo $Ligne['Id']; ?>,0)">
									<img src='../../Images/add.png' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajout";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajout";} ?>'>
								</a>
							</td>
						</tr>
						<?php
							$req="SELECT Id,DateDebut,DateFin 
								FROM moris_datesuivi 
								WHERE Id_Prestation=".$Ligne['Id']."
								AND Suppr=0
								ORDER BY DateDebut ASC";
							$resultSuivi=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($resultSuivi);
							if ($nbResulta>0){
								$couleur="#ffffff";
								$i=0;
								while($rowSuivi=mysqli_fetch_array($resultSuivi)){
									?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td width="13%"><?php echo AfficheDateJJ_MM_AAAA($rowSuivi['DateDebut']);?></td>
										<td width="13%"><?php echo AfficheDateJJ_MM_AAAA($rowSuivi['DateFin']);?></td>
										<td width="2%" align="center">
											<a href="javascript:OuvreFenetreModif(<?php echo $Ligne['Id']; ?>,<?php echo $rowSuivi['Id']; ?>)">
												<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modif";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modif";} ?>'>
											</a>
										</td>
										<td width="2%" align="center">
										<?php if($i>0){?>
											<a href="javascript:OuvreFenetreSuppr(<?php echo $Ligne['Id']; ?>,<?php echo $rowSuivi['Id']; ?>)">
												<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
											</a>
										<?php }?>
										</td>
									</tr>
									<?php
									$i++;
									if($couleur=="#ffffff"){$couleur="#a3e4ff";}
									else{$couleur="#ffffff";}
								}
							}
						?>
					</table>
				</td>
			</tr>
			<?php }?>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="UPDATE new_competences_prestation SET UtiliseMORIS=0 WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>