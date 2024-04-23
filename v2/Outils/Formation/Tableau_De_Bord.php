<?php
require("../../Menu.php");

SupprimerQualifsEnAttenteMauvaisePrestation();

$req="DELETE FROM form_session_prestation
WHERE Id_Session IN(
SELECT Id_Session
FROM `form_session_date` 
WHERE DateSession<'".date('Y-m-d')."')";
$result=mysqli_query($bdd,$req);

function Titre($Libelle,$Lien)
{
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:black;text-decoration: none;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$HTTPServeur.$Lien."' >".$Libelle."</a></td></tr>\n";
}

function TitreV2($Libelle,$Lien)
{
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:black;text-decoration: none;\" target='_blank' onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$HTTPServeur.$Lien."' >".$Libelle."</a></td></tr>\n";
}

function Widget($Libelle,$Lien,$Image,$Couleur,$InfosSupp="")
{
	global $HTTPServeur;
	
	echo "
		<table style='border-spacing: 15px;display:inline-table;' >
			<tr>
				<td style=\"width:130px;height:110px;border-style:outset; border-radius: 15px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:center;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$HTTPServeur.$Lien."' >
						<img width='40px' src='../../Images/".$Image."' border='0' /><br>
						".$Libelle."
					</a>
				</td>
			</tr>";
	
	$css="";
	
	if($InfosSupp<>""){$css="bgcolor='".$Couleur."' width='250px'";}
	
	echo "
		<tr>
			<td ".$css.">
				".$InfosSupp."
			</tD>
		</tr>
	";
	echo "</table>";
}

//Renouvellement auto des qualifs
//-	FONDA_AERO - Aerospace Basics / Fondamentaux AERONAUTIQUE 3194
//-	DAM_01- Damage Prévention Awarness 2255
//-	DAN QUAL6-FOE/FOD 364


$requeteQualificationsAnalyse="
	SELECT
		*
	FROM
		(
		SELECT
			*
		FROM
			(
			SELECT
				new_competences_relation.Id,
				new_competences_relation.Id_Personne,
				new_competences_relation.Evaluation,
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) AS Id_Plateforme,
				new_competences_personne_prestation.Id_Prestation,
				new_competences_personne_prestation.Id_Pole,
				new_competences_relation.Id_Qualification_Parrainage,
				new_competences_relation.Date_Debut,
				new_competences_relation.Date_Fin,
				new_competences_relation.Date_QCM,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS Personne ,(@row_number:=@row_number + 1) AS rnk
			FROM
				new_competences_relation
			RIGHT JOIN new_competences_personne_prestation
				ON new_competences_relation.Id_Personne=new_competences_personne_prestation.Id_Personne 
			LEFT JOIN new_competences_qualification
				ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
			WHERE
			(
				new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'
				OR new_competences_personne_prestation.Date_Fin<='0001-01-01'
			)
				AND new_competences_personne_prestation.Id_Prestation IN
				(
					SELECT
						Id
					FROM
						new_competences_prestation
					WHERE
						Id_Plateforme IN (1,3,23,32)
				)
				AND new_competences_relation.Type='Qualification' 
				AND new_competences_relation.Suppr=0
				AND new_competences_relation.Statut_Surveillance != 'REFUSE'
				AND new_competences_qualification.Duree_Validite>0
				AND new_competences_relation.Date_Debut>'0001-01-01'
				AND new_competences_relation.Date_Fin > '0001-01-01'
				AND new_competences_relation.Date_Fin >= '".date("Y-m-d",strtotime(date("Y-m-d")." - 6 month"))."'
				AND new_competences_relation.Id_Qualification_Parrainage IN (3194,2255,364)
			ORDER BY
				new_competences_relation.Date_Debut DESC
			) AS Tab_Qualif
		GROUP BY
			Tab_Qualif.Id_Personne,
			Tab_Qualif.Id_Prestation,
			Tab_Qualif.Id_Qualification_Parrainage
		) AS TAB
	WHERE
		TAB.Evaluation<>'B'
		AND TAB.Evaluation<>''
		AND TAB.Date_Fin<='".date("Y-m-d",strtotime(date("Y-m-d")." + 4 month"))."'
		AND
			(
			SELECT
				COUNT(Id)
			FROM
				form_qualificationnecessaire_prestation
			WHERE
				form_qualificationnecessaire_prestation.Id_Relation=TAB.Id
				AND
					(
					form_qualificationnecessaire_prestation.Necessaire=0
					AND form_qualificationnecessaire_prestation.Id_Prestation=TAB.Id_Prestation
					AND form_qualificationnecessaire_prestation.Id_Pole=TAB.Id_Pole
					)
			)=0
		AND
		(
			SELECT
				COUNT(form_besoin.Id)
			FROM
				form_besoin
			WHERE
				form_besoin.Suppr=0
				AND form_besoin.Motif='Renouvellement'
				AND form_besoin.Id_Personne=TAB.Id_Personne
				AND form_besoin.Valide >=0 
				AND form_besoin.Traite<3
				AND form_besoin.Id_Formation IN
				(
					SELECT
						form_formation_qualification.Id_Formation
					FROM
						form_formation_qualification
					WHERE
						form_formation_qualification.Suppr=0
						AND form_formation_qualification.Id_Qualification=TAB.Id_Qualification_Parrainage
				)
		)=0 
		
		AND (
			(TAB.Id_Qualification_Parrainage IN (133,2145,2490,13,12,1683,75,167)
			AND 
				(
					SELECT
					   COUNT(new_competences_relation.Id)
					FROM
						new_competences_relation
					WHERE new_competences_relation.Id_Qualification_Parrainage IN (1606,2130,3258)
						AND new_competences_relation.Suppr=0
						AND new_competences_relation.Id_Personne=TAB.Id_Personne
						AND (new_competences_relation.Date_Fin <= '0001-01-01'
						OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
				)=0
			)
		
		OR
			TAB.Id_Qualification_Parrainage NOT IN (133,2145,2490,13,12,1683,75,167)
		)
		
		AND (
			SELECT
			   COUNT(new_competences_relation.Id)
			FROM
				new_competences_relation
			WHERE new_competences_relation.Id_Qualification_Parrainage=TAB.Id_Qualification_Parrainage
				AND new_competences_relation.Suppr=0
				AND new_competences_relation.Id_Personne=TAB.Id_Personne
				AND new_competences_relation.Evaluation IN ('L','T')
				AND new_competences_relation.Date_QCM>=TAB.Date_QCM
				AND (new_competences_relation.Date_Fin <= '0001-01-01'
				OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
		)=0
		AND (
			SELECT
			   COUNT(new_competences_relation.Id)
			FROM
				new_competences_relation
			WHERE new_competences_relation.Id_Qualification_Parrainage=TAB.Id_Qualification_Parrainage
				AND new_competences_relation.Suppr=0
				AND new_competences_relation.Id_Personne=TAB.Id_Personne
				AND new_competences_relation.Evaluation NOT IN ('B','')
				AND new_competences_relation.Date_QCM>=TAB.Date_QCM
				AND (new_competences_relation.Date_Fin <= '0001-01-01'
				OR new_competences_relation.Date_Fin >= '".date("Y-m-d",strtotime(date("Y-m-d")." + 4 month"))."')
		)=0
		";
$resultQualifications=mysqli_query($bdd,$requeteQualificationsAnalyse);
$nbQualifs=mysqli_num_rows($resultQualifications);

if ($nbQualifs>0){
	while($rowRel=mysqli_fetch_array($resultQualifications))
	{
		$Id_Relation = $rowRel['Id'];
		$Id_Prestation = $rowRel['Id_Prestation'];
		$Id_Qualification = $rowRel['Id_Qualification_Parrainage'];
		$Id_Plateforme = $rowRel['Id_Plateforme'];
		$Id_Personne = $rowRel['Id_Personne'];
		$Id_Pole = $rowRel['Id_Pole'];
		if($Id_Relation<>"" && $Id_Prestation<>"" && $Id_Qualification<>"" && $Id_Plateforme<>"" && $Id_Pole<>"")
		{
			$req="SELECT
					form_formation_qualification.Id_Formation,
					form_formation.Id_TypeFormation,
					form_formation.Recyclage 
				FROM
					form_formation_qualification 
				LEFT JOIN form_formation 
					ON form_formation_qualification.Id_Formation=form_formation.Id 
				WHERE
					form_formation_qualification.Suppr=0 
					AND form_formation.Suppr=0
					AND form_formation.Id_TypeFormation<>1
					AND form_formation_qualification.Id_Qualification=".$Id_Qualification." 
					AND (form_formation.Id_Plateforme=0 OR form_formation.Id_Plateforme=".$Id_Plateforme.") ";
			$result=mysqli_query($bdd,$req);
			$nbQualifs=mysqli_num_rows($result);
			if($nbQualifs==1)
			{
				$row=mysqli_fetch_array($result);
				
				//Vérifier si le besoin n'existe pas déjà
				if(Get_NbBesoinExistant($Id_Personne, $row['Id_Formation'])==0)
				{
					//Création du besoin
					$ReqQualifFormation="
						SELECT
							Id_Qualification 
						FROM
							form_formation_qualification 
						LEFT JOIN form_formation 
							ON form_formation_qualification.Id_Formation=form_formation.Id
						WHERE
							form_formation_qualification.Id_Formation=".$row['Id_Formation']." 
							AND form_formation_qualification.Suppr=0 
							AND form_formation.Suppr=0
							AND form_formation_qualification.Masquer=0 ";
					$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
					$nbQualifsFormation=mysqli_num_rows($ResultQualifFormation);
					
					if($nbQualifsFormation>=1)
					{
						$requete="INSERT INTO form_besoin(Id_Demandeur,Id_Prestation,Id_Pole,Id_Formation,Id_Personne,Date_Demande,Motif,Valide,Id_Personne_MAJ,Date_MAJ) ";
						$requete.="VALUES (0,".$Id_Prestation.",".$Id_Pole.",".$row['Id_Formation'].",".$Id_Personne.",'".date("Y-m-d")."','Renouvellement',1,0,'".date("Y-m-d")."') ";
						$result=mysqli_query($bdd,$requete);
						$IdCree = mysqli_insert_id($bdd);
						
						//Création des qualifications associées
						if($IdCree>0)
						{
							while($rowQualifFormation=mysqli_fetch_array($ResultQualifFormation))
							{
								//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
								$visible=0;
								$ReqInsertBesoinGPEC="INSERT INTO new_competences_relation (Id_Personne, Type, Id_Qualification_Parrainage, Evaluation, Visible, Id_Besoin) VALUES ";
								$ReqInsertBesoinGPEC.="(".$Id_Personne.",'Qualification',".$rowQualifFormation['Id_Qualification'].",'B',".$visible.",".$IdCree.")";
								$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
							}
						}
					}
				}
			}
			else
			{
				//Vérifier si n'existe pas déjà avant insert 
				$req="SELECT Id 
					FROM form_qualificationnecessaire_prestation 
					WHERE Id_Relation=".$Id_Relation." 
					AND Id_Prestation=".$Id_Prestation."
					AND Id_Pole=".$Id_Pole."
					AND Necessaire=1";
				$resultS=mysqli_query($bdd,$req);
				$nbSelect=mysqli_num_rows($resultS);
				if($nbSelect==0){
					$requete="INSERT INTO form_qualificationnecessaire_prestation(Id_Relation,Id_Prestation,Id_Pole,Necessaire,Id_Validateur,DateValidation) ";
					$requete.="VALUES (".$Id_Relation.",".$Id_Prestation.",".$Id_Pole.",1,0,'".date("Y-m-d")."') ";
					$result=mysqli_query($bdd,$requete);
				}
			}
		}
	}
}
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td  height="20px" valign="center" align="center" style="font-weight:bold;font-size:15px;">
			<table style="align:center;">
				<tr>
					<td style="height:50px;text-align:center;color:#103b63;valign:top;font-weight:bold;font-size:15px;border-radius: 15px;" bgcolor="#ffffff">&nbsp;&nbsp;
						<?php
							echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							if($LangueAffichage=="FR"){echo "Guide utilisateur : ";}else{echo "User Guide : ";}
							echo "<a target='_blank' href='User_guide_online QCM.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>";
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="6" height="20px" valign="center" align="right" style="font-weight:bold;font-size:15px;">
			<?php
			if($LangueAffichage=="FR"){echo "Vous avez des questions, un problème ? Contactez-nous : ";}
			else{echo "Do you have questions or a problem? Contact us : ";}
			?>
			<span style="color:#00577c;">help-qualipso.aaa@daher.com </span>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr bgcolor="#6EB4CD" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;">
			<?php if($LangueAffichage=="FR"){echo "GESTION DES FORMATIONS : QUALIPSO";}else{echo "TRAININGS MANAGEMENT : QUALIPSO";}?>
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td width="100%">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<td align="center" style="width:20%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
						<?php if($LangueAffichage=="FR"){echo "FORMATIONS";}else{echo "TRAININGS";} ?>
					</td>
					<?php	
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation($TableauIdPostesRespPresta_CQ) || DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM)){
					?>
					<td align="center" style="width:40%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
						<?php if($LangueAffichage=="FR"){echo "BESOINS / INSCRIPTIONS";}else{echo "NEEDS / REGISTRATION";} ?>
					</td>
					<?php	
					}
					?>
					<?php
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP) || DroitsFormationPrestations(array(1,3,23,32),$TableauIdPostesRespPresta_CQ))
					{
					?>
					<td width="30%" align="center" style="text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
						<?php if($LangueAffichage=="FR"){echo "CONFIGURATION";}else{echo "CONFIGURATION";} ?>
					</td>
					<?php 
						}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="100%">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<td align="center" style="width:20%" valign="top">
						<table>
							<tr>
								<td>
								<?php	
								if($LangueAffichage=="FR"){$libelle="<br>Catalogue de formations";}else{$libelle="<br>Training Catalog";}
								Widget($libelle,"Outils/Formation/Liste_FormationCatalogue.php","Formation/Catalogue.png","#5f80ff");
								
								if(isset($_SESSION['PartieFormation']))
								{
									if($_SESSION['PartieFormation']>1)
									{
										if($LangueAffichage=="FR"){$libelle="<br>QCM";}else{$libelle="<br>MCQ";}
										Widget($libelle,"Outils/Formation/Tableau_De_Bord_Stagiaire.php","Formation/Stagiaire.png","#209aa5");
									}
								}
								?>
								</td>
							</tr>
						</table>
					</td>
					<?php	
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPlateforme(array($IdPosteAssistantRH)) || DroitsFormationPrestation($TableauIdPostesRespPresta_CQ) || DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM)){
					?>
					<td align="center" style="width:40%" valign="top">
						<?php	
						
							if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS) || DroitsFormationPrestation($TableauIdPostesRespPresta_CQ)){
								$infos="";
								if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)==0){
									$infos="<table>";
									if($LangueAffichage=="FR"){
										$infos.= "<tr><td style=\"color:green\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id='nbDatesDispo'>?</span> besoin(s) disponible(s) dans le planning</b></td></tr>";
										$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id='nbBAConfirmer'>?</span> besoin(s) à confirmer</b></td></tr>";
									}
									else{
										$infos.= "<tr><td style=\"color:green\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id='nbDatesDispo'>?</span> need available in the schedule</b></td></tr>";
										$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id='nbBAConfirmer'>?</span> need to be confirmed</b></td></tr>";
									}
									$infos.="</table>";
								}
								
								if($LangueAffichage=="FR"){$libelle="<br>Workflow des besoins";}else{$libelle="<br>Needs workflow";}
								Widget($libelle,"Outils/Formation/Liste_Besoin_Formation.php","Formation/Besoins.png","#edf430",$infos);
								
								
							}
							
							if(DroitsFormationPlateformes(array(1,23),$TableauIdPostesAF_RF_RQ_RH_CQS) || (DroitsAUnePrestation($TableauIdPostesRespPresta_CQ,1,50))){
								if(DroitsFormationPlateformes(array(1,23),$TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestations(array(1,23),$TableauIdPostesRespPresta_CQ)){
									$infos="<table>";
									$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id='nbDemandeATraiter'>?</span></b></td></tr>";
									$infos.="</table>";
								
									if($LangueAffichage=="FR"){$libelle="Demande de besoins";}else{$libelle="Demand for needs";}
									Widget($libelle,"Outils/Formation/Liste_Demande_Besoin.php","Formation/Aide.png","#edf430",$infos);
								}
								else{
									echo "<input type='hidden' id='nbDemandeATraiter' name='nbDemandeATraiter' />";
								}
							}
							else{
								echo "<input type='hidden' id='nbDemandeATraiter' name='nbDemandeATraiter' />";
							}
							
							if(DroitsFormationPrestations(array(1,3,23,32),$TableauIdPostesRespPresta_CQ) || DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
								$nb=0;
								$infos="";
								$sautLigne="";
								if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)==0){
									$infos="<table>";
									if($LangueAffichage=="FR"){
										$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id='nbFinQualifEnAttente'>?</span> renouvellements à valider</b></td></tr>";
										$infos.= "<tr><td style=\"color:red\"><b><b></b></td></tr>";
									}
									else{
										$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id='nbFinQualifEnAttente'>?</span> renewals to validate</b></td></tr>";
										$infos.= "<tr><td style=\"color:red\"><b><b></b></td></tr>";
									}
									$infos.="</table>";
									$sautLigne="<br>";
								}
								if($LangueAffichage=="FR"){$libelle=$sautLigne."Fin de validité des qualifications";}else{$libelle=$sautLigne."End of validity of qualifications";}
								Widget($libelle,"Outils/Formation/Liste_FinValiditeQualification.php","Formation/Warning.png","#c28bd3",$infos);
								
							}
							
							if(DroitsFormationPrestations(array(1,3,23,32),$TableauIdPostesRespPresta_CQ)){
								if($LangueAffichage=="FR"){$libelle="<br>Planning / prestation";}else{$libelle="<br>Scheduling / activity";}
								Widget($libelle,"Outils/Formation/PlanningSite.php","Formation/Planning.png","#ff8c1f");
							}
							
							if(DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAF_RF_RQ) || DroitsFormationPlateformes(array(1,3,23,32),array($IdPosteAssistantRH,$IdPosteResponsableHSE)) || DroitsFormationPrestations(array(1,3,23,32),$TableauIdPostesRespPresta_CQ)){
								
								$infos="";
								if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ)==0 &&  DroitsFormationPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableHSE))==0){
									$infos="<table style='width:100%;'>
												<tr>
													<td width='100%' align='left'>
														<table style='width:100%; border-spacing:0; border:1px solid #4688fc;' >
															<tr>
																<td style='border:1px solid #4688fc;' align='center' class='Libelle' width='10%'>Date</td>
																<td style='border:1px solid #4688fc;' align='center' class='Libelle' width='90%'>";
																	if($LangueAffichage=="FR"){$infos.= "Nombre de personnes <br>en formation";}else{$infos.= "Number of people <br>in training";}
																$infos.= "</td>
															</tr>";
		
											$dateEC=date("Y-m-d");
											for($i=0;$i<4;$i++){
												$dateSemaine=0;
												$nb=0;
												$jour = date('w', strtotime($dateEC." + 0 month"));
												if($jour<>0 && $jour<>6){
													if($_SERVER['SERVER_NAME']<>"192.168.20.3" && $_SERVER['SERVER_NAME']<>"127.0.0.1"){
														$nb=NbPersonneEnFormation($dateEC);
													}
													$jour = date('w', strtotime($dateEC." + 0 month"));
												
													$infos.= "<tr><td style='border:1px solid #4688fc;' align='center'>".AfficheDateJJ_MM_AAAA($dateEC)."</td>";
													$infos.= "<td style='border:1px solid #4688fc;' align='center'>".$nb."</td>";
													$infos.="</tr>";
												}
												else{
													$i=$i-1;
												}
												$dateEC=date("Y-m-d",strtotime($dateEC." + 1 day"));
											}
											$infos.="</table>
													</td>
												</tr>
											</table>
										";
								}
								if($LangueAffichage=="FR"){$libelle="<br>Personnel inscrit";}else{$libelle="<br>Registered personnel";}
								Widget($libelle,"Outils/Formation/IdentificationPersonnelEnFormation_Liste.php","Formation/Personnes.png","#1574d0",$infos);
							}

							if(DroitsFormationPrestations(array(1,3,23,32),$TableauIdPostesCQ) || DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAFI_RF_RQ))
							{
								if($LangueAffichage=="FR"){$libelle="Gestion des <br>L & T";}else{$libelle="L / T <br>management";}
								Widget($libelle,"Outils/Formation/WorkflowDesLT.php","Formation/LT.png","#ff8b41");
							}
							
							if(DroitsFormationPrestation($TableauIdPostesRespPresta_CQ) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form))
							{
								$infos="";
								if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form)==0){
									$infos="<table>";
									if($LangueAffichage=="FR"){
										if(DroitsFormationPrestation($TableauIdPostesCQ))
										{
										$infos.= "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><input type='hidden' id='nbQCMATraiter' name='nbQCMATraiter'/><span id='nbSurveillancesAConfirmer'>?</span> surveillances à confirmer</b></td></tr>";
										}
										if(DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
										{
										$infos.= "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><input type='hidden' id='nbSurveillancesAConfirmer' name='nbSurveillancesAConfirmer'/> <span id='nbQCMATraiter'>?</span> QCM à diffuser (ouverture des accès)</b></td></tr>";
										}
										$infos.= "<tr><td><b><b></b></td></tr>";
									}
									else{
										if(DroitsFormationPrestation($TableauIdPostesCQ))
										{
										$infos.= "<tr><td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><input type='hidden' id='nbQCMATraiter' name='nbQCMATraiter'/><span id='nbSurveillancesAConfirmer'>?</span> checks to confirm</b></td></tr>";
										}
										if(DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
										{
										$infos.= "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><input type='hidden' id='nbSurveillancesAConfirmer' name='nbSurveillancesAConfirmer'/><span id='nbQCMATraiter'>?</span> multiple choice questions (open access)</b></td></tr>";
										}
										$infos.= "<tr><td><b><b></b></td></tr>";
									}
									$infos.="</table>";
								}
							
								if($LangueAffichage=="FR"){$libelle="Workflow des surveillances";}else{$libelle="Monitoring workflow";}
								Widget($libelle,"Outils/Formation/WorkflowDesSurveillances_Liste.php","Formation/Jumelles.png","#fff0e1",$infos);
								
								if($_SESSION['Id_Personne']==2833  || $_SESSION['Id_Personne']==2902 || $_SESSION['Id_Personne']==4394){
									if($LangueAffichage=="FR"){$libelle="Workflow des surveillances QBP";}else{$libelle="Monitoring workflow QBP";}
									Widget($libelle,"Outils/Formation/WorkflowDesSurveillancesQBP_Liste.php","Formation/Jumelles.png","#fff0e1");
								}
							}
							
							if(DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAF_RF)){
								$nb=0;
								$info="";
								$sautLigne="";
								if($_SERVER['SERVER_NAME']<>"192.168.20.3" && $_SERVER['SERVER_NAME']<>"127.0.0.1"){
									$nb=NbFinQualifEnAttenteFormation();
								}
								if($nb>0){
									$info="<table>";
									if($LangueAffichage=="FR"){
										$info.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$nb." qualifications en attente de formation</b></td></tr>";
									}
									else{
										$info.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$nb." qualifications awaiting training</b></td></tr>";
									}
									$info.="</table>";
									$sautLigne="<br>";
								}
								
								if($LangueAffichage=="FR"){$libelle=$sautLigne."Qualifications en attente de formations";}else{$libelle=$sautLigne."Qualifications awaiting training";}
								Widget($libelle,"Outils/Formation/Liste_FinValiditeQualificationFormation.php","Formation/Attente.png","#ee70a1",$info);
								
							}
							if(DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAF_RF_FORM)){
								if($LangueAffichage=="FR"){$libelle="<br>Planning";}else{$libelle="<br>Scheduling";}
								Widget($libelle,"Outils/Formation/Planning_v2.php","Formation/Planning.png","#10b9a6");
							}
							
							if(DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAFI_RF_FORM) || DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAFI_RF_RQ)){
								if($LangueAffichage=="FR"){$libelle="<br>Sessions de formation";}else{$libelle="<br>Training session";}
								Widget($libelle,"Outils/Formation/Gestion_SessionFormation.php","Formation/GestionSessions.png","#6fb543");
							}
							if(DroitsFormationPlateforme($TableauIdPostesAFI_RF_FORM_CQS) 
								|| DroitsFormationPrestation($TableauIdPostesCQ)){
									
								if(DroitsFormationPlateformes(array(3,4,9,10,13,19,20,22),$TableauIdPostesAFI_RF_FORM_CQS) 
									|| DroitsFormationPlateformes(array(1,3,4,9,10,13,19,22),$TableauIdPostesAFI_RF_FORM) 
								|| DroitsFormationPrestations(array(1,3,4,9,10,13,19,20,22),$TableauIdPostesCQ)){
									if($LangueAffichage=="FR"){$libelle="<br>Ouverture QCM hors planning qualipso";}else{$libelle="<br>MCQ opening out of qualipso scheduling";}
									Widget($libelle,"Outils/Formation/Liste_QCMsansFormation.php","Formation/QCM.png","#f99a33");
								}
							}

							if(DroitsFormationPlateformes(array(1,23),array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableHSE))){
								if($LangueAffichage=="FR"){$libelle="<br>Autorisations<br>de conduite";}else{$libelle="<br>Driving<br>Authorization";}
								Widget($libelle,"Outils/Formation/Liste_Autorisation_Travail.php","Formation/Autorisation.png","#ff645f");
							}
							
							if(DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAF_RF)){
								if($LangueAffichage=="FR"){$libelle="<br>Suivi des formations";}else{$libelle="<br>Training follow-up";}
								Widget($libelle,"Outils/Formation/Liste_SuiviFormation.php","Tableau.png","#ea4877");
							}
						?>
					</td>
					<?php	
					}
					?>
					<?php
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP) || DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
					{
					?>
					<td align="center" width="30%" valign="top">
						<table style='border-spacing:15px;display:inline-table;' >
							<tr>
								<td style="width:300px;border-style:outset; border-radius: 15px;height:90px;border-style:outset;border-color:#67cff1;border-spacing:0;color:black;valign:top;font-weight:bold;" bgcolor='#67cff1'>
									<table width='100%' height='100%'>	
										<tr>
											<td style="width:30%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;">
												<img width='40px' src='../../Images/Formation/Parametrage.png' border='0' /><br>
											</td>
										</tr>
										<tr>
											<td>
												<table style="width:100%; align:left; valign:top;">
												<?php
												if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation($TableauIdPostesRespPresta_CQ)){
													if($LangueAffichage=="FR"){Titre("Besoins par métier et par prestation","Outils/Formation/Liste_Prestation_Metier_Formation.php");}
													else{Titre("Needs per job and per site","Outils/Formation/Liste_Prestation_Metier_Formation.php");}
												}
												if(DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP_RH)){
													if($LangueAffichage=="FR"){Titre("Client QCM","Outils/Formation/Liste_Client.php");}
													else{Titre("Customer MCQ","Outils/Formation/Liste_Client.php");}
												}
												if(DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAF_RF)){
													if($_SESSION['PartieFormation']>1){
														if($LangueAffichage=="FR"){Titre("Documents complémentaires","Outils/Formation/Liste_Document.php");}
														else{Titre("Additional documents","Outils/Formation/Liste_Document.php");}
													}
													if($LangueAffichage=="FR"){Titre("Formations équivalentes","Outils/Formation/Liste_FormationEquivalente.php");}
													else{Titre("Equivalent training","Outils/Formation/Liste_FormationEquivalente.php");}
												}
												if(DroitsFormationPlateforme(array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteResponsableQualite))){
													if($LangueAffichage=="FR"){Titre("Formations / Unité d'exploitation","Outils/Formation/Liste_FormationPlateforme.php");}
													else{Titre("Training / Operating unit","Outils/Formation/Liste_FormationPlateforme.php");}
												}
												if( $_SESSION['Id_Personne']==105 || DroitsFormationPlateformes(array(1,23),array($IdPosteResponsableQualite)) || DroitsFormationPlateformes(array(17),array($IdPosteResponsableFormation))){
													if($LangueAffichage=="FR"){Titre("Formations SMQ","Outils/Formation/Liste_FormationSMQ.php");}
													else{Titre("Training QMS","Outils/Formation/Liste_FormationSMQ.php");}
												}
												if(DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAF_RF)){
													if($LangueAffichage=="FR"){Titre("Groupes de formations","Outils/Formation/Liste_Groupe_Formation.php");}
													else{Titre("Training Groups","Outils/Formation/Liste_Groupe_Formation.php");}
													if($LangueAffichage=="FR"){Titre("Langues","Outils/Formation/Liste_Langue.php");}
													else{Titre("Languages","Outils/Formation/Liste_Langue.php");}
													if($LangueAffichage=="FR"){Titre("Lieux","Outils/Formation/Liste_Lieu.php");}
													else{Titre("Places","Outils/Formation/Liste_Lieu.php");}
													if($LangueAffichage=="FR"){Titre("Organismes","Outils/Formation/Liste_Organisme.php");}
													else{Titre("Training organization","Outils/Formation/Liste_Organisme.php");}
												}
												if(DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP_RH)){
													if($LangueAffichage=="FR"){Titre("QCM","Outils/Formation/Liste_QCM.php");}
													else{Titre("MCQ","Outils/Formation/Liste_QCM.php");}
												}
												if($LangueAffichage=="FR"){TitreV2("Modules de formation (D-0738)","Outils/Competences/D-0738_Modules_de_Formation.php");}
												else{TitreV2("Training modules (D-0738)","Outils/Competences/D-0738_Modules_de_Formation.php");}
												?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td align="left">
						<?php	
									if($_SERVER['SERVER_NAME']<>"192.168.20.3" && $_SERVER['SERVER_NAME']<>"127.0.0.1"){
										function better_scandir($dir, $sorting_order = SCANDIR_SORT_ASCENDING) {
										  $files = array();
										  foreach (scandir($dir, $sorting_order) as $file) {
											if ($file[0] === '.') {
											  continue;
											}
											$files[$file] = filemtime($dir . '/' . $file);
										  } // foreach

										  if ($sorting_order == SCANDIR_SORT_ASCENDING) {
											asort($files, SORT_NUMERIC);
										  }
										  else {
											arsort($files, SORT_NUMERIC);
										  }

										  $ret = array_keys($files);
										  return ($ret) ? $ret : false;

										}
										
										$lesDoc="";
										if(DroitsFormationPlateformes(array(1),array($IdPosteResponsableFormation))){
											$dir    = '../../../Qualite/DQ/4/DQ413/Modules_de_formation';
											$listeDoc =  better_scandir($dir, SCANDIR_SORT_DESCENDING);
											 foreach ( $listeDoc as $file ) {
													if (stripos($file, 'QCM') !== FALSE || stripos($file, 'MCQ') !== FALSE) {
														if(date ("Y-m-d",filemtime($dir . '/' . $file))>=date('Y-m-d',strtotime(date('Y-m-d')." -1 month"))){
															$lesDoc.= $file." : ".AfficheDateJJ_MM_AAAA(date ("Y-m-d",filemtime($dir . '/' . $file)))."<br>";
														}
													}
											 }

											if($lesDoc<>""){
												if($LangueAffichage=="FR"){echo "<br><b><img width='20px' src='../../Images/attention.png' border='0' />Des QCM ont été modifiés ce dernier mois dans le module de formation. <br>Vérifier que les modificiations ont bien été faites dans QUALIPSO </b><br>";}
												else{echo "<br><b><img width='20px' src='../../Images/attention.png' border='0' />MCQs have been modified in the last month in the training module.  <br>Check that the modification has been done in QUALIPSO </b><br>";}
											}
											echo $lesDoc;
										}
									}
										?>
								</td>
						</tr>
					</table>
					</td>
					<?php
					}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	if(DroitsFormationPlateformes(array(1,3,23,32),$TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestations(array(1,3,23,32),$TableauIdPostesCQ))
	{
	?>
	<tr bgcolor="#6EB4CD" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;">
			<?php if($LangueAffichage=="FR"){echo "INDICATEURS / EXTRACTS";}else{echo "INDICATORS / EXTRACTS";}?>
		</td>
	</tr>
	<tr>
		<td width="100%">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<td align="center">
						<?php	
						if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation($TableauIdPostesCQ))
						{
							if($LangueAffichage=="FR"){$libelle="<br>Paramétrage";}else{$libelle="<br>Setting";}
							Widget($libelle,"Outils/Formation/Liste_ExtractParametrage.php","RH/Parametrage.png","#67cff1");
						}
						if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation(array($IdPosteReferentQualiteSysteme)))
						{
							if($LangueAffichage=="FR"){$libelle="<br>Besoins";}else{$libelle="<br>Needs";}
							Widget($libelle,"Outils/Formation/TDB_IndicateursBesoins.php","Formation/Besoins.png","#edf430");
						
							if($LangueAffichage=="FR"){$libelle="<br>Sessions de formation";}else{$libelle="<br>Training sessions";}
							Widget($libelle,"Outils/Formation/TDB_IndicateursSessions.php","Formation/GestionSessions.png","#6fb543");
							
							if($LangueAffichage=="FR"){$libelle="<br>Profil";}else{$libelle="<br>Profile";}
							Widget($libelle,"Outils/Formation/Liste_ExtractProfil.php","RH/Personne.png","#fdca83");
							
							if(DroitsFormationPlateforme($TableauIdPostesAFI_RF_RQ_RH))
							{
								if($LangueAffichage=="FR"){$libelle="<br>Évaluations de formations";}else{$libelle="<br>Training evaluations";}
								Widget($libelle,"Outils/Formation/Liste_EvaluationFormation.php","Formation/Evaluation.png","#1f9aa5");
							}
						}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<?php
	}
	?>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
<?php


	echo "<script>
		var xhr2 = $.ajax({
			url : 'Ajax_NbDatesDispos.php',
			dataType : 'html',
			async : true,
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('nbDatesDispo').innerHTML=data;
				}
		});
		
		var xhr3 = $.ajax({
			url : 'Ajax_NbDatesAConfirmer.php',
			dataType : 'html',
			async : true,
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('nbBAConfirmer').innerHTML=data;
				}
		});
		
		var xhr3 = $.ajax({
			url : 'Ajax_NbFinQualifEnAttente.php',
			dataType : 'html',
			async : true,
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('nbFinQualifEnAttente').innerHTML=data;
				}
		});
		
		var xhr3 = $.ajax({
			url : 'Ajax_NbDemandeATraiter.php',
			dataType : 'html',
			async : true,
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('nbDemandeATraiter').innerHTML=data;
				}
		});
		
		var xhr3 = $.ajax({
			url : 'Ajax_NbSurveillancesATraiter.php',
			dataType : 'html',
			async : true,
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('nbSurveillancesAConfirmer').innerHTML=data;
				}
		});
		
		var xhr3 = $.ajax({
			url : 'Ajax_NbQCMADiffuser.php',
			dataType : 'html',
			async : true,
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('nbQCMATraiter').innerHTML=data;
				}
		});
	</script>
	";
?>
</html>