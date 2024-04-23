<?php
require("../../Menu.php");
?>

<script language="javascript">
	function OuvreFenetreSuppr(Type,Id){
		if(document.getElementById('Langue').value=="EN"){texte='Are you sure you want to delete?';}
		else{texte='Etes-vous sûr de vouloir supprimer ?';}
		if(window.confirm(texte)){
			var w=window.open("Supprimer_DemandeBesoin.php?Id="+Id,"PageDemandeBesoin","status=no,menubar=no,scrollbars=yes,width=80,height=30");
		}			
	}
	function OuvreFenetreDemandeBesoin()
	{
		var w= window.open("Demande_Besoin_Formation.php","PageBesoinFormation","status=no,menubar=no,width=620,height=550");
		w.focus();
	}
	function OuvreFenetreDemandeBesoinMetierPrestation()
	{
		var w= window.open("Demande_Besoin_Metier_Prestation.php","PageBesoinFormation","status=no,menubar=no,width=800,height=550");
		w.focus();
	}
	function OuvreFenetreRefus(Type,Id){
		if(document.getElementById('Langue').value=="EN"){texte='Are you sure you want to refuse?';}
		else{texte='Etes-vous sûr de vouloir refuser ?';}
		if(window.confirm(texte)){
			var w=window.open("Refuser_DemandeBesoin.php?Id="+Id,"PageDemandeBesoin","status=no,menubar=no,scrollbars=yes,width=800,height=300");
		}			
	}
	function CocherValide(Type){
		if(document.getElementById('check_Valide'+Type).checked==true){
			var elements = document.getElementsByClassName('check'+Type);
			for (i=0; i<elements.length; i++){
			  elements[i].checked=true;
			}
		}
		else{
			var elements = document.getElementsByClassName('check'+Type);
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
	}
	function CocherPriseEnCompte(Type){
		if(document.getElementById('check_PriseEnCompte'+Type).checked==true){
			var elements = document.getElementsByClassName('checkPriseEnCompte'+Type);
			for (i=0; i<elements.length; i++){
			  elements[i].checked=true;
			}
		}
		else{
			var elements = document.getElementsByClassName('checkPriseEnCompte'+Type);
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
	}
	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
</script>
<form id="formulaire" class="test" action="Liste_Demande_Besoin.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#edf430;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Demande de besoins";}else{echo "Demand for needs";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="4" style="background-color:#4e9dec;font-weight:bold;color:#000000;font-size:12px;height:20px;border-radius:25px 25px 25px 25px;">
						&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "EN ATTENTE";}else{echo "WAITING";}?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete="SELECT form_demandebesoin.Id, Id_Demandeur,Id_Personne,form_demandebesoin.Id_Prestation,Id_Pole,Date_Demande,Etat,
			Motif,Commentaire,Id_Valideur,Date_Validation,RaisonRefus,Id_Formation,
			new_competences_prestation.Libelle AS Prestation,
			(SELECT IF(LEFT(Reference,2)='HE',0,1) FROM form_formation WHERE Id=Id_Formation) AS EstValidable,
			(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
			WHERE form_formation_plateforme_parametres.Id_Formation=form_demandebesoin.Id_Formation
			AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
			AND Suppr=0 LIMIT 1) AS Organisme,
			(SELECT Libelle
			FROM form_formation_langue_infos
			WHERE Id_Formation=form_demandebesoin.Id_Formation
			AND Id_Langue=
				(SELECT Id_Langue 
				FROM form_formation_plateforme_parametres 
				WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_demandebesoin.Id_Prestation)
				AND Id_Formation=form_demandebesoin.Id_Formation
				AND Suppr=0 
				LIMIT 1)
			AND Suppr=0) AS Formation, 
			(
				SELECT
					new_competences_relation.Date_Fin
				FROM new_competences_relation
				WHERE
					new_competences_relation.Id_Personne=form_demandebesoin.Id_Personne
					AND new_competences_relation.Type='Qualification'
					AND new_competences_relation.Visible=0
					AND new_competences_relation.Suppr=0
					AND new_competences_relation.Id_Qualification_Parrainage IN 
					(
						SELECT form_formation_qualification.Id_Qualification
						FROM form_formation_qualification
						WHERE form_formation_qualification.Id_Formation=form_demandebesoin.Id_Formation
						AND form_formation_qualification.Suppr=0
					)
				ORDER BY
					Date_QCM DESC, Date_Fin DESC
					LIMIT 1
			) AS DateFinQualif,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) AS Pole,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_demandebesoin.Id_Personne) AS Personne,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_demandebesoin.Id_Demandeur) AS Demandeur,
			(SELECT COUNT(Id) FROM form_prestation_metier_formation WHERE form_prestation_metier_formation.Suppr=0 
				AND form_prestation_metier_formation.Id_Prestation=form_demandebesoin.Id_Prestation
				AND form_prestation_metier_formation.Id_Pole=form_demandebesoin.Id_Pole
				AND form_prestation_metier_formation.Id_Formation=form_demandebesoin.Id_Formation
				AND form_prestation_metier_formation.Id_Metier IN (SELECT Id_Metier FROM new_competences_personne_metier WHERE new_competences_personne_metier.Id_Personne=form_demandebesoin.Id_Personne)
				) AS Parametrage
			FROM form_demandebesoin
			LEFT JOIN new_competences_prestation ON form_demandebesoin.Id_Prestation=new_competences_prestation.Id
			WHERE Etat=0
			AND Suppr=0
			AND Type='Besoin' ";
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
		$requete.="AND new_competences_prestation.Id_Plateforme IN 
					(
						SELECT
							Id_Plateforme
						FROM
							new_competences_personne_poste_plateforme
						WHERE
							Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
							AND Id_Personne=".$IdPersonneConnectee."
					) ";
	}
	else{
		$requete.="AND CONCAT(form_demandebesoin.Id_Prestation,'_',form_demandebesoin.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
					) ";
	}
	$requete.=" ORDER BY Id DESC";
		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td class="Libelle">
			&nbsp; &bull; <?php if($_SESSION["Langue"]=="FR"){echo "BESOINS";}else{echo "NEEDS";} ?>
		</td>
		<td align="right"  width="10%">
			<?php 
				if((DroitsFormationPrestation($TableauIdPostesResponsablesPrestation))){
			?>
			<a class="Modif" href="javascript:OuvreFenetreDemandeBesoin();">
				<img src="../../Images/add.png" width="25px" border="0" alt="<?php if($LangueAffichage=="FR"){echo "Demander un besoin en formation";}else{echo "Ask a training need";} ?>" title="<?php if($LangueAffichage=="FR"){echo "Demander un besoin en formation";}else{echo "Ask a training need";} ?>">
			</a>
			&nbsp;&nbsp;&nbsp;
			<?php
				}
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Formation";}else{echo "Training";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Demandeur";}else{echo "Applicant";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date demande";}else{echo "Request date";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date fin validité";}else{echo "End date validity";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">
					</td>
					<td class='EnTeteTableauCompetences' width="15%" style="text-align:center;">
						<?php 
								if((DroitsFormationPrestation($TableauIdPostesCQ)) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
						?>
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Valider";}else{echo "Validate";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="validerSelectionBesoin" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"><br>
						<input type='checkbox' id="check_ValideBesoin" name="check_ValideBesoin" value="" checked onchange="CocherValide('Besoin')">
						<?php 
							} 
						?>
					</td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">
						<?php 
							if((DroitsFormationPrestation($TableauIdPostesCQ)) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
						?>
					<?php if($_SESSION["Langue"]=="FR"){echo "Refuser";}else{echo "Refuse";} ?>
					<?php 
						} 
					?>
					</td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">
					<?php 
								if((DroitsFormationPrestation($TableauIdPostesResponsablesPrestation))){
						?>
					<?php if($_SESSION["Langue"]=="FR"){echo "Suppr";}else{echo "Suppr";} ?>
					<?php 
						}
						?>
					</td>
				</tr>
	<?php
			if(isset($_POST['validerSelectionBesoin'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkBesoin_'.$row['Id'].''])){
						$requeteUpdate="UPDATE form_demandebesoin SET 
								Id_Valideur=".$_SESSION['Id_Personne'].",
								Date_Validation='".date('Y-m-d')."',
								Etat=1
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
						
						if(Get_NbBesoinExistant($row['Id_Personne'], $row['Id_Formation'])==0){
							$ReqInsertBesoin="
								INSERT INTO
									form_besoin
									(
										Id_Demandeur,
										Id_Prestation,
										Id_Pole,
										Id_Formation,
										Id_Personne,
										Date_Demande,
										Motif,
										Valide,
										Commentaire,
										Id_Valideur,
										Id_Personne_MAJ,
										Date_MAJ
									)
								SELECT Id_Demandeur,
										Id_Prestation,
										Id_Pole,
										Id_Formation,
										Id_Personne,
										'".date('Y-m-d')."',
										Motif,
										1,
										Commentaire,
										".$_SESSION['Id_Personne'].",
										".$_SESSION['Id_Personne'].",
										'".date('Y-m-d')."'
								FROM form_demandebesoin 
								WHERE Id=".$row['Id']."
										";
							$ResultInsertBesoin=mysqli_query($bdd,$ReqInsertBesoin);
							$ID_BESOIN=mysqli_insert_id($bdd);
							
							//Qualification liées à la formation
							$ReqQualifFormation="SELECT Id_Qualification FROM form_formation_qualification WHERE Id_Formation=".$row['Id_Formation']." AND Suppr=0 AND Masquer=0 ";
							$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
							$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);
							
							//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
							if($NbQualifFormation>0)
							{
								mysqli_data_seek($ResultQualifFormation,0);
								$ReqInsertBesoinGPEC="INSERT INTO new_competences_relation (Id_Personne, Type, Id_Qualification_Parrainage, Evaluation, Visible, Id_Besoin) VALUES ";
								while($RowQualifFormation=mysqli_fetch_array($ResultQualifFormation))
								{
									$ReqInsertBesoinGPEC.="(";
									$ReqInsertBesoinGPEC.=$row['Id_Personne'];
									$ReqInsertBesoinGPEC.=",'Qualification'";
									$ReqInsertBesoinGPEC.=",".$RowQualifFormation['Id_Qualification'];
									$ReqInsertBesoinGPEC.=",'B'";
									$ReqInsertBesoinGPEC.=",0";
									$ReqInsertBesoinGPEC.=",".$ID_BESOIN;
									$ReqInsertBesoinGPEC.="),";
								}
								$ReqInsertBesoinGPEC=substr($ReqInsertBesoinGPEC,0,strlen($ReqInsertBesoinGPEC)-1);
								$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
							}
						}
					}
				}
				$result=mysqli_query($bdd,$requete);
			}
			
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$organisme="";
					if($row['Organisme']){
						$organisme=$row['Organisme'];
					}

					$pole="";
					if($row['Id_Pole']>0){$pole=" - ".$row['Pole'];}
		?>
		<?php
						$Contrat="";
						$IdContrat=IdContrat($row['Id_Personne'],date('Y-m-d'));
						if($IdContrat>0){
							if(TypeContrat2($IdContrat)<>10){
								$Contrat=TypeContrat($IdContrat);
							}
							else{
								$tab=AgenceInterimContrat($IdContrat);
								if($tab<>0){
									$Contrat=$tab[0];
								}
							}
						}
			?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".stripslashes($row['Personne'])."</a>";?></td>
						<td><?php echo stripslashes($Contrat);?></td>
						<td><?php echo substr(stripslashes($row['Prestation']),0,7).$pole;?></td>
						<td><?php echo stripslashes($row['Formation']." ".$organisme);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['Date_Demande']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFinQualif']);?></td>
						<td><?php echo stripslashes($row['Commentaire']);?></td>
						<td><?php if($row['Parametrage']==0){echo "<img src='../../Images/attention.png' width='10px' border='0' alt='Refuse' title='Refuse'/>";} ?></td>
						<td align="center">
							<?php 
								if(((DroitsAUnePrestation($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)) && $row['EstValidable']==1) || DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))){
									echo "<input class='checkBesoin' type='checkbox' name='checkBesoin_".$row['Id']."' value='' checked>";
								}
							?>
						</td>
						<td align="center">
							<?php 
								if(((DroitsAUnePrestation($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)) && $row['EstValidable']==1) || DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))){
							?>
							<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Refuser";}else{echo "Refuse";}?>" href="javascript:OuvreFenetreRefus('Besoin','<?php echo $row['Id']; ?>')"><img src="../../Images/supprimer.png" width="18px" border="0" alt="Refuse" title="Refuse"></a>
							<?php 
								}
							?>
						</td>
						<td align="center">
							<?php 
								if((DroitsAUnePrestation($TableauIdPostesResponsablesPrestation,$row['Id_Prestation'],$row['Id_Pole']))){
							?>
							<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Suppr";}else{echo "Suppr";}?>" href="javascript:OuvreFenetreSuppr('Besoin','<?php echo $row['Id']; ?>')"><img src="../../Images/Suppression.gif" width="18px" border="0" alt="Suppr" title="Suppr"></a>
							<?php 
								}
							?>
						</td>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<?php
		$requete="SELECT form_demandebesoin.Id, Id_Demandeur,Id_Personne,form_demandebesoin.Id_Prestation,Id_Pole,Date_Demande,Etat,
			Motif,Commentaire,Id_Valideur,Date_Validation,RaisonRefus,Id_Formation,Id_Metier,
			new_competences_prestation.Libelle AS Prestation,
			(SELECT IF(LEFT(Reference,2)='HE',0,1) FROM form_formation WHERE Id=Id_Formation) AS EstValidable,
			(SELECT Libelle FROM new_competences_metier WHERE Id=form_demandebesoin.Id_Metier) AS Metier,
			(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
			WHERE form_formation_plateforme_parametres.Id_Formation=form_demandebesoin.Id_Formation
			AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
			AND Suppr=0 LIMIT 1) AS Organisme,Obligatoire,
			(SELECT Libelle
			FROM form_formation_langue_infos
			WHERE Id_Formation=form_demandebesoin.Id_Formation
			AND Id_Langue=
				(SELECT Id_Langue 
				FROM form_formation_plateforme_parametres 
				WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_demandebesoin.Id_Prestation)
				AND Id_Formation=form_demandebesoin.Id_Formation
				AND Suppr=0 
				LIMIT 1)
			AND Suppr=0) AS Formation, 
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) AS Pole,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_demandebesoin.Id_Demandeur) AS Demandeur 
			FROM form_demandebesoin
			LEFT JOIN new_competences_prestation ON form_demandebesoin.Id_Prestation=new_competences_prestation.Id
			WHERE Etat=0
			AND Suppr=0
			AND Type='BesoinMetierPrestation' ";
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
		$requete.="AND new_competences_prestation.Id_Plateforme IN 
					(
						SELECT
							Id_Plateforme
						FROM
							new_competences_personne_poste_plateforme
						WHERE
							Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
							AND Id_Personne=".$IdPersonneConnectee."
					) ";
	}
	else{
		$requete.="AND CONCAT(form_demandebesoin.Id_Prestation,'_',form_demandebesoin.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
					) ";
	}
	$requete.=" ORDER BY Id DESC";

		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td class="Libelle" >
			&nbsp; &bull; <?php if($_SESSION["Langue"]=="FR"){echo "PARAMETRAGE BESOINS / METIERS / PRESTATIONS ";}else{echo "SETTING NEEDS / JOBS / SITES";} ?>
		</td>
		<td align="right"  width="10%">
			<?php 
				if((DroitsFormationPrestation($TableauIdPostesResponsablesPrestation))){
			?>
			<a class="Modif" href="javascript:OuvreFenetreDemandeBesoinMetierPrestation();">
				<img src="../../Images/add.png" width="25px" border="0" alt="<?php if($LangueAffichage=="FR"){echo "Demander un besoin / métier / prestation";}else{echo "Ask a need / job / site";} ?>" title="<?php if($LangueAffichage=="FR"){echo "Demander un besoin / métier / prestation";}else{echo "Ask a need / job / site";} ?>">
			</a>
			&nbsp;&nbsp;&nbsp;
			<?php
				}
			?>
		</td>
	</tr>
	<tr>
		<td  colspan="2">
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="30%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Formation";}else{echo "Training";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Obli./Facult.";}else{echo "Mandatory/Optional";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Demandeur";}else{echo "Applicant";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date demande";}else{echo "Request date";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?></td>
					<td class='EnTeteTableauCompetences' width="10%" style="text-align:center;">
						<?php 
								if((DroitsFormationPrestation($TableauIdPostesCQ)) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
						?>
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Valider";}else{echo "Validate";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="validerSelectionBesoinMetierPrestation" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"><br>
						<input type='checkbox' id="check_ValideBesoinMetierPrestation" name="check_ValideBesoinMetierPrestation" value="" checked onchange="CocherValide('BesoinMetierPrestation')">
						<?php 
						}
						?>
					</td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">
					<?php 
								if((DroitsFormationPrestation($TableauIdPostesCQ)) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
						?>
					<?php if($_SESSION["Langue"]=="FR"){echo "Refuser";}else{echo "Refuse";} ?>
					<?php 
						}
						?>
					</td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">
					<?php 
								if((DroitsFormationPrestation($TableauIdPostesResponsablesPrestation))){
						?>
					<?php if($_SESSION["Langue"]=="FR"){echo "Suppr";}else{echo "Suppr";} ?>
					<?php 
						}
						?>
					</td>
				</tr>
	<?php
			if(isset($_POST['validerSelectionBesoinMetierPrestation'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkBesoinMetierPrestation_'.$row['Id'].''])){
						$requeteUpdate="UPDATE form_demandebesoin SET 
								Id_Valideur=".$_SESSION['Id_Personne'].",
								Date_Validation='".date('Y-m-d')."',
								Etat=1
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
						
						//COMPLETER
						$requeteAjout="
						INSERT INTO
							form_prestation_metier_formation
							(
								Id_Prestation,
								Id_Pole,
								Id_Metier,
								Id_Formation,
								Obligatoire,
								Id_Personne_MAJ,
								Date_MAJ
							)
						SELECT 
								Id_Prestation,
								Id_Pole,
								Id_Metier,
								Id_Formation,
								Obligatoire,
								".$_SESSION['Id_Personne'].",
								'".date('Y-m-d')."'
						FROM form_demandebesoin 
						WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteAjout);
						
						RecreerBesoinsManquantsPrestationFormation($row['Id_Prestation'],$row['Id_Pole'],$row['Id_Formation'],$row['Id_Metier'],$row['Obligatoire']);
					}
				}
				$result=mysqli_query($bdd,$requete);
			}
			
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$organisme="";
					if($row['Organisme']){
						$organisme=$row['Organisme'];
					}
					$pole="";
					if($row['Id_Pole']>0){$pole=" - ".$row['Pole'];}
					
					$obligatoire="";
					if($row['Obligatoire']==0){
						if($_SESSION['Langue']=="FR"){$obligatoire="Facultative";}else{$obligatoire= "Optional";}
					}
					else{
						if($_SESSION['Langue']=="FR"){$obligatoire= "Obligatoire";}else{$obligatoire= "Mandatory";}
					}
					
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php echo substr(stripslashes($row['Prestation']),0,7).$pole;?></td>
						<td><?php echo stripslashes($row['Formation']." ".$organisme);?></td>
						<td><?php echo stripslashes($row['Metier']);?></td>
						<td><?php echo stripslashes($obligatoire);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['Date_Demande']);?></td>
						<td><?php echo stripslashes($row['Commentaire']);?></td>
						<td align="center">
							<?php 
								if(((DroitsAUnePrestation($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)) && $row['EstValidable']==1) || DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))){
									echo "<input class='checkBesoinMetierPrestation' type='checkbox' name='checkBesoinMetierPrestation_".$row['Id']."' value='' checked>";
								}
							?>
						</td>
						<td align="center">
							<?php 
								if(((DroitsAUnePrestation($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)) && $row['EstValidable']==1) || DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))){
							?>
							<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Refuser";}else{echo "Refuse";}?>" href="javascript:OuvreFenetreRefus('BesoinMetierPrestation','<?php echo $row['Id']; ?>')"><img src="../../Images/supprimer.png" width="18px" border="0" alt="Refuse" title="Refuse"></a>
							<?php 
								}
							?>
						</td>
						<td align="center">
							<?php 
								if((DroitsAUnePrestation($TableauIdPostesResponsablesPrestation,$row['Id_Prestation'],$row['Id_Pole']))){
							?>
							<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Suppr";}else{echo "Suppr";}?>" href="javascript:OuvreFenetreSuppr('Besoin','<?php echo $row['Id']; ?>')"><img src="../../Images/Suppression.gif" width="18px" border="0" alt="Suppr" title="Suppr"></a>
							<?php 
								}
							?>
						</td>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
			</table>
		</td>
	</tr>
	
	
	<tr>
		<td height="30"></td>
	</tr>
	
	
	
	<tr>
		<td colspan="5"  colspan="2">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="4" style="background-color:#4e9dec;font-weight:bold;color:#000000;font-size:12px;height:20px;border-radius:25px 25px 25px 25px;">
						&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "VALIDÉ / REFUSÉ";}else{echo "VALIDATED / REFUSED";}?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete="SELECT form_demandebesoin.Id, Id_Demandeur,Id_Personne,form_demandebesoin.Id_Prestation,Id_Pole,Date_Demande,Etat,
			Motif,Commentaire,Id_Valideur,Date_Validation,RaisonRefus,
			new_competences_prestation.Libelle AS Prestation,
			(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
			WHERE form_formation_plateforme_parametres.Id_Formation=form_demandebesoin.Id_Formation
			AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
			AND Suppr=0 LIMIT 1) AS Organisme,
			(SELECT Libelle
			FROM form_formation_langue_infos
			WHERE Id_Formation=form_demandebesoin.Id_Formation
			AND Id_Langue=
				(SELECT Id_Langue 
				FROM form_formation_plateforme_parametres 
				WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_demandebesoin.Id_Prestation)
				AND Id_Formation=form_demandebesoin.Id_Formation
				AND Suppr=0 
				LIMIT 1)
			AND Suppr=0) AS Formation, 
			(
				SELECT
					new_competences_relation.Date_Fin
				FROM new_competences_relation
				WHERE
					new_competences_relation.Id_Personne=form_demandebesoin.Id_Personne
					AND new_competences_relation.Type='Qualification'
					AND new_competences_relation.Visible=0
					AND new_competences_relation.Suppr=0
					AND new_competences_relation.Id_Qualification_Parrainage IN 
					(
						SELECT form_formation_qualification.Id_Qualification
						FROM form_formation_qualification
						WHERE form_formation_qualification.Id_Formation=form_demandebesoin.Id_Formation
						AND form_formation_qualification.Suppr=0
					)
				ORDER BY
					Date_QCM DESC, Date_Fin DESC
					LIMIT 1
			) AS DateFinQualif,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) AS Pole,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_demandebesoin.Id_Personne) AS Personne,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_demandebesoin.Id_Demandeur) AS Demandeur,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_demandebesoin.Id_Valideur) AS Valideur 
			FROM form_demandebesoin
			LEFT JOIN new_competences_prestation ON form_demandebesoin.Id_Prestation=new_competences_prestation.Id
			WHERE Etat<>0
			AND Suppr=0
			AND Id_DemandeurPrisEnCompte=0
			AND Type='Besoin' ";
		if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
			$requete.="AND new_competences_prestation.Id_Plateforme IN 
						(
							SELECT
								Id_Plateforme
							FROM
								new_competences_personne_poste_plateforme
							WHERE
								Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
								AND Id_Personne=".$IdPersonneConnectee."
						) ";
		}
		else{
			$requete.="AND CONCAT(form_demandebesoin.Id_Prestation,'_',form_demandebesoin.Id_Pole) IN 
						(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
						) ";
		}
		$requete.=" ORDER BY Id DESC";
		
		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td class="Libelle"  colspan="2">
			&nbsp; &bull; <?php if($_SESSION["Langue"]=="FR"){echo "BESOINS";}else{echo "NEEDS";} ?>
		</td>
	</tr>
	<tr>
		<td  colspan="2">
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Formation";}else{echo "Training";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Demandeur";}else{echo "Applicant";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date demande";}else{echo "Request date";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date fin validité";}else{echo "End date validity";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Par";}else{echo "By";} ?></td>
					<td class='EnTeteTableauCompetences' width="7%" style="text-align:center;">
						<?php 
								if((DroitsFormationPrestation($TableauIdPostesResponsablesPrestation)) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
						?>
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>" name="priseEnCompteBesoin" value="<?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?>"><br>
						<input type='checkbox' id="check_PriseEnCompteBesoin" name="check_PriseEnCompteBesoin" value="" checked onchange="CocherPriseEnCompte('Besoin')">
						<?php 
						}
						?>
					</td>
				</tr>
	<?php
			if(isset($_POST['priseEnCompteBesoin'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkOUTBesoin_'.$row['Id'].''])){
						$requeteUpdate="UPDATE form_demandebesoin SET 
								Id_DemandeurPrisEnCompte=".$_SESSION['Id_Personne'].",
								DatePriseEnCompteDemandeur='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
				}
			}
			
			$result=mysqli_query($bdd,$requete);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}

					$pole="";
					if($row['Id_Pole']>0){$pole=" - ".$row['Pole'];}
					
					$Etat="";
					$couleurEtat="#ffed3b";
					$Hover="";
					if($row['Etat']==1){
						if($_SESSION["Langue"]=="FR"){$Etat="Validé";}
						else{$Etat="Validated";}
						$couleurEtat="#469400";
					}
					elseif($row['Etat']==-1){
						if($_SESSION["Langue"]=="FR"){$Etat="Refusé";}
						else{$Etat="Refused";}
						$couleurEtat="#e92525";
						
						$Hover=" id='leHover' ";
						$Etat.="<span>".stripslashes($row['RaisonRefus'])."</span>";
					}
					
					$Contrat="";
						$IdContrat=IdContrat($row['Id_Personne'],date('Y-m-d'));
						if($IdContrat>0){
							if(TypeContrat2($IdContrat)<>10){
								$Contrat=TypeContrat($IdContrat);
							}
							else{
								$tab=AgenceInterimContrat($IdContrat);
								if($tab<>0){
									$Contrat=$tab[0];
								}
							}
						}
						
					$organisme="";
					if($row['Organisme']){
						$organisme=$row['Organisme'];
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".stripslashes($row['Personne'])."</a>";?></td>
						<td><?php echo stripslashes($Contrat);?></td>
						<td><?php echo substr(stripslashes($row['Prestation']),0,7).$pole;?></td>
						<td><?php echo stripslashes($row['Formation']." ".$organisme);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['Date_Demande']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFinQualif']);?></td>
						<td><?php echo stripslashes($row['Commentaire']);?></td>
						<td <?php echo $Hover; ?>><?php echo stripslashes($Etat);?></td>
						<td><?php echo stripslashes($row['Valideur']);?></td>
						<td align="center">
							<?php 
								if($row['Etat']<>0){
									if((DroitsAUnePrestation($TableauIdPostesResponsablesPrestation,$row['Id_Prestation'],$row['Id_Pole']))){
										echo "<input class='checkPriseEnCompteBesoin' type='checkbox' name='checkOUTBesoin_".$row['Id']."' value='' checked>";
									}
								}
							?>
						</td>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete="SELECT form_demandebesoin.Id, Id_Demandeur,Id_Personne,form_demandebesoin.Id_Prestation,Id_Pole,Date_Demande,Etat,
			Motif,Commentaire,Id_Valideur,Date_Validation,RaisonRefus,
			new_competences_prestation.Libelle AS Prestation,
			(SELECT Libelle FROM new_competences_metier WHERE Id=form_demandebesoin.Id_Metier) AS Metier,
			(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
			WHERE form_formation_plateforme_parametres.Id_Formation=form_demandebesoin.Id_Formation
			AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
			AND Suppr=0 LIMIT 1) AS Organisme,Obligatoire,
			(SELECT Libelle
			FROM form_formation_langue_infos
			WHERE Id_Formation=form_demandebesoin.Id_Formation
			AND Id_Langue=
				(SELECT Id_Langue 
				FROM form_formation_plateforme_parametres 
				WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_demandebesoin.Id_Prestation)
				AND Id_Formation=form_demandebesoin.Id_Formation
				AND Suppr=0 
				LIMIT 1)
			AND Suppr=0) AS Formation, 
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) AS Pole,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_demandebesoin.Id_Demandeur) AS Demandeur,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_demandebesoin.Id_Valideur) AS Valideur
			FROM form_demandebesoin
			LEFT JOIN new_competences_prestation ON form_demandebesoin.Id_Prestation=new_competences_prestation.Id
			WHERE Etat<>0
			AND Suppr=0
			AND Id_DemandeurPrisEnCompte=0
			AND Type='BesoinMetierPrestation' ";
		if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
			$requete.="AND new_competences_prestation.Id_Plateforme IN 
						(
							SELECT
								Id_Plateforme
							FROM
								new_competences_personne_poste_plateforme
							WHERE
								Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
								AND Id_Personne=".$IdPersonneConnectee."
						) ";
		}
		else{
			$requete.="AND CONCAT(form_demandebesoin.Id_Prestation,'_',form_demandebesoin.Id_Pole) IN 
						(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
						) ";
		}
		$requete.=" ORDER BY Id DESC";
		
		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td class="Libelle"  colspan="2">
			&nbsp; &bull; <?php if($_SESSION["Langue"]=="FR"){echo "PARAMETRAGE BESOINS / METIER / PRESTATION";}else{echo "SETTING NEEDS / JOBS / SITES";} ?>
		</td>
	</tr>
	<tr>
		<td  colspan="2">
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="25%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Formation";}else{echo "Training";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Obli./Facult.";}else{echo "Mandatory/Optional";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Demandeur";}else{echo "Applicant";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date demande";}else{echo "Request date";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?></td>
					<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Par";}else{echo "By";} ?></td>
					<td class='EnTeteTableauCompetences' width="8%" style="text-align:center;">
						<?php 
								if((DroitsFormationPrestation($TableauIdPostesResponsablesPrestation)) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
						?>
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>" name="priseEnCompteBesoinMetierPrestation" value="<?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?>"><br>
						<input type='checkbox' id="check_PriseEnCompteBesoinMetierPrestation" name="check_PriseEnCompteBesoinMetierPrestation" value="" checked onchange="CocherPriseEnCompte('BesoinMetierPrestation')">
						<?php 
						}
						?>
					</td>
				</tr>
	<?php
			if(isset($_POST['priseEnCompteBesoinMetierPrestation'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkOUTBesoinMetierPrestation_'.$row['Id'].''])){
						$requeteUpdate="UPDATE form_demandebesoin SET 
								Id_DemandeurPrisEnCompte=".$_SESSION['Id_Personne'].",
								DatePriseEnCompteDemandeur='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
				}
			}
			
			$result=mysqli_query($bdd,$requete);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}

					$pole="";
					if($row['Id_Pole']>0){$pole=" - ".$row['Pole'];}
					
					$obligatoire="";
					if($row['Obligatoire']==0){
						if($_SESSION['Langue']=="FR"){$obligatoire="Facultative";}else{$obligatoire= "Optional";}
					}
					else{
						if($_SESSION['Langue']=="FR"){$obligatoire= "Obligatoire";}else{$obligatoire= "Mandatory";}
					}
					
					$Etat="";
					$couleurEtat="#ffed3b";
					$Hover="";
					if($row['Etat']==1){
						if($_SESSION["Langue"]=="FR"){$Etat="Validé";}
						else{$Etat="Validated";}
						$couleurEtat="#469400";
					}
					elseif($row['Etat']==-1){
						if($_SESSION["Langue"]=="FR"){$Etat="Refusé";}
						else{$Etat="Refused";}
						$couleurEtat="#e92525";
						
						$Hover=" id='leHover' ";
						$Etat.="<span>".stripslashes($row['RaisonRefus'])."</span>";
					}
					
					$organisme="";
					if($row['Organisme']){
						$organisme=$row['Organisme'];
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php echo substr(stripslashes($row['Prestation']),0,7).$pole;?></td>
						<td><?php echo stripslashes($row['Formation']." ".$organisme);?></td>
						<td><?php echo stripslashes($row['Metier']);?></td>
						<td><?php echo stripslashes($obligatoire);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['Date_Demande']);?></td>
						<td><?php echo stripslashes($row['Commentaire']);?></td>
						<td <?php echo $Hover; ?>><?php echo stripslashes($Etat);?></td>
						<td><?php echo stripslashes($row['Valideur']);?></td>
						<td align="center">
							<?php 
								if($row['Etat']<>0){
									if((DroitsAUnePrestation($TableauIdPostesResponsablesPrestation,$row['Id_Prestation'],$row['Id_Pole']))){
										echo "<input class='checkPriseEnCompteBesoinMetierPrestation' type='checkbox' name='checkOUTBesoinMetierPrestation_".$row['Id']."' value='' checked>";
									}
								}
							?>
						</td>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
			</table>
		</td>
	</tr>
	<tr><td height="50"></td></tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>