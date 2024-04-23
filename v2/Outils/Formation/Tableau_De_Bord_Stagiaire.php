<?php
require("../../Menu.php"); 
?>
<script type="text/javascript">
	function QCM_Web(Id)
	{
		var w= window.open("QCM_Web_v3.php?Page=Tableau_De_Bord_Stagiaire&Id_Session_Personne_Qualification="+Id,"PageQCMWeb","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
		w.focus();
	}
	function Doc_WebSansSession(Id)
	{
		var w= window.open("Doc_Web.php?Id_Session_Personne_Document="+Id+"&sansFormation=1","PageDocWeb","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
		w.focus();
	}
</script>
<form id="formulaire" action="Tableau_De_Bord_Stagiaire.php" method="post">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#209aa5;">
				<tr>
					<td width="4">
					<?php
						echo "<a style='text-decoration:none;' href='".$HTTPServeur."Outils/Formation/Tableau_De_Bord.php'>";
						if($LangueAffichage=="FR"){echo "<img width='20px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='20px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a></td>";
					?>
					</td>
					<td class="TitrePage"><?php if($LangueAffichage=="FR"){echo "Gestion des formations # Tableau de bord stagiaires";}else{echo "Trainings management # Trainees dashboard";}?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="8px"></td></tr>
	<tr>
		<td align="center" colspan="2">
			<input class="Bouton" name="BtnActualiser" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Actualiser";}else{echo "Refresh";}?>">
		</td>
	</tr>
	<tr><td height="4px"></td></tr>
	<tr>
		<td width="50%" valign='top'>
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td class="EnTeteTableauCompetences" style="color:#0026e0;" colspan="2">
						<?php 
							if($LangueAffichage=="FR"){echo "SESSIONS DE FORMATION DU JOUR";}else{echo "DAY TRAINING SESSIONS";} 
						?>
					</td>
				</tr>
				<tr><td height="4px"></td></tr>
				<?php
				//Liste des sessions de formation ce jour 
				$req="
                    SELECT
                        form_session.Id,
                        form_session.Id_Formation,
                        form_session.Id_Lieu,
                        form_session.Id_Formateur,
                        form_session_date.Id AS Id_SessionDate,
                        form_session.nom_fichier, 
					    form_session_date.DateSession,
                        form_session_date.Heure_Debut,
                        form_session_date.Heure_Fin, 
					    form_session.Recyclage,
                        form_session_date.PauseRepas,
                        form_session_date.HeureDebutPause,
                        form_session_date.HeureFinPause, 
					    (SELECT Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS Lieu,form_session.Id_GroupeSession,form_session.Formation_Liee, 
				        (SELECT (SELECT Libelle FROM form_groupe_formation WHERE form_groupe_formation.Id=form_session_groupe.Id_GroupeFormation) FROM form_session_groupe WHERE form_session_groupe.Id=form_session.Id_GroupeSession) AS Groupe, 
					    (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session.Id_Formateur) AS Formateur,
					    (
                            SELECT
                                Id
                            FROM
                                form_session_personne
                            WHERE
                                Validation_Inscription=1 
								AND form_session_personne.Id_Personne=".$IdPersonneConnectee."
								AND Suppr=0
								AND form_session_personne.Id_Session=form_session.Id
							LIMIT 1
						) AS Id_Session_Personne,
						(
                            SELECT  
                                Id_Langue
                            FROM
                                form_formation_plateforme_parametres 
						    WHERE
                                form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0
                            LIMIT 1
                        ) AS Id_Langue,
					    (
                            SELECT
                                (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme)
                            FROM
                                form_formation_plateforme_parametres 
							WHERE
                                form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0
                            LIMIT 1
                        ) AS Organisme,
					    (
                            SELECT
                                IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
							FROM
                                form_formation_langue_infos
							WHERE
                                Id_Formation=form_session.Id_Formation
							    AND Id_Langue=
                                (
                                    SELECT
                                        Id_Langue
                                    FROM
                                        form_formation_plateforme_parametres 
								    WHERE
                                        Id_Plateforme=form_session.Id_Plateforme
										AND Id_Formation=form_session.Id_Formation
										AND Suppr=0 
								    LIMIT 1
                                )
							    AND Suppr=0
                        ) AS Libelle						
					FROM
                        form_session_date
                    LEFT JOIN form_session
					    ON form_session_date.Id_Session = form_session.Id 
					WHERE
                        form_session_date.Suppr=0 
						AND form_session.Suppr=0 
						AND form_session.Annule=0 
						AND form_session.Diffusion_Creneau=1 
						AND form_session_date.DateSession='".date('Y-m-d')."' 
						AND (
                                SELECT
                                    COUNT(form_session_personne.Id)
								FROM
                                    form_session_personne
								WHERE
                                    Validation_Inscription=1 
    								AND form_session_personne.Id_Personne=".$IdPersonneConnectee."
    								AND Suppr=0
    								AND form_session_personne.Id_Session=form_session.Id
						    )>0
					ORDER BY
                        form_session_date.Heure_Debut ";
				$resultSessions=mysqli_query($bdd,$req);
				$nbSession=mysqli_num_rows($resultSessions);
				if($nbSession>0)
				{
					mysqli_data_seek($resultSessions,0);
					while($rowSession=mysqli_fetch_array($resultSessions))
					{
					?>
						<tr>
							<td>
								<table style="width:100%;">
									<tr>
										<td style="color:#0026e0;" colspan="2">
											<?php echo strtoupper($rowSession['Libelle']." ".$rowSession['Organisme'])."&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;".AfficheDateJJ_MM_AAAA($rowSession['DateSession'])."&nbsp;&nbsp; - &nbsp;&nbsp;".substr($rowSession['Heure_Debut'],0,5)." / ".substr($rowSession['Heure_Fin'],0,5); ?>
										</td>
									</tr>
									<tr><td height="4px"></td></tr>
									<tr>
										<td width="40%">
										<?php 
											if($LangueAffichage=="FR"){echo "<u>Lieu de la formation </u>: ";}else{echo "<u>Place of training </u>: ";}
											echo $rowSession['Lieu'];
										?>
										</td>
										<td>
										<?php 
											if($LangueAffichage=="FR"){echo "<u>Formateur </u>: ";}else{echo "<u>Former </u>: ";}
											echo $rowSession['Formateur'];
										?>
										</td>
									</tr>
									<tr><td height="4px"></td></tr>
									<tr>
										<td width="95%" align="left" colspan="2">
											<table style="width:100%;">
											<?php
												//Liste des qualifications acquises pour cette formation
												$reqQualif="
													SELECT
                                                        Id,
                                                        Id_QCM,
                                                        Id_QCM_Lie,
                                                        Id_LangueQCM,
                                                        Resultat,
                                                        ResultatMere,
                                                        Id_Repondeur,
														DateHeureRepondeur,
                                                        Etat,
													    (
                                                            SELECT
                                                                Libelle 
															FROM
                                                                form_qcm_langue 
															WHERE
                                                                form_qcm_langue.Id_QCM=form_session_personne_qualification.Id_QCM
    															AND form_qcm_langue.Id_Langue=form_session_personne_qualification.Id_LangueQCM
    															AND Suppr=0
    															AND Brouillon=0
                                                        ) AS QCM,
													    (SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualif 
													FROM
                                                        form_session_personne_qualification
													WHERE
                                                        Id_Session_Personne=".$rowSession['Id_Session_Personne']." ";
												$resultQualif=mysqli_query($bdd,$reqQualif);
												$NbQualif=mysqli_num_rows($resultQualif);
												
												echo "";
												if($NbQualif>0)
												{
													while($RowQualif=mysqli_fetch_array($resultQualif))
													{
											?>
														<tr>
															<td colspan="2">&bull; <?php echo $RowQualif['Qualif']; ?> : </td>
														</tr>
											<?php
														//QCM de la qualification 
														if($RowQualif['Id_QCM']>0)
														{
															$resultat="";
															$resultatMere="";
															$Etat="";
															if($RowQualif['DateHeureRepondeur']>"0001-01-01 00:00:00")
															{
																if($RowQualif['Id_QCM_Lie']<>"")
																{
																	if($LangueAffichage=="FR"){$resultatMere="QCM mère : ".$RowQualif['ResultatMere']."<br>";}
																	else{$resultatMere="MCQ mother : ".$RowQualif['ResultatMere']."<br>";}
																	if($LangueAffichage=="FR"){$resultat="Note finale : ";}
																    else{$resultat="Final note : ";}
																}
																$resultat.=$RowQualif['Resultat'];
																if($LangueAffichage=="FR")
																{
																	if($RowQualif['Etat']==1){$Etat="<br><font color='#2dbe29'>Réussite</font>";}
																	elseif($RowQualif['Etat']==-1){$Etat="<br><font color='#e80000'>Echec</font>";}
																}
																else
																{
																	if($RowQualif['Etat']==1){$Etat="<br><font color='#2dbe29'>Success</font>";}
																	elseif($RowQualif['Etat']==-1){$Etat="<br><font color='#e80000'>Failure</font>";}
																}
															}
															echo "<tr>";
															echo "<td width='10%'></td>";
															echo "<td width='25%'>";
															if(QCMestOuvert($RowQualif['Id'])){
																echo "<a style='color:#0b7f17;text-decoration: underline;' href='javascript:QCM_Web(\"".$RowQualif['Id']."\");'>";
															}
															else{
																
															}
															echo $RowQualif['QCM'];
															if(QCMestOuvert($RowQualif['Id'])){echo "</a>";}
															echo "</td>";
															echo "<td width='65%'>";
															echo $resultatMere.$resultat.$Etat;
															echo "</td>";
															echo "</tr>";
														}
													}
												}
												
												//Liste des documents complémentaires pour cette formation
												$reqDoc="
													SELECT
                                                        Id,
                                                        Id_Document,
                                                        Id_LangueDocument,
                                                        Id_Repondeur,
														DateHeureRepondeur,
														(
                                                            SELECT
                                                                Libelle 
															FROM
                                                                form_document_langue 
															WHERE
                                                                form_document_langue.Id_Document=form_session_personne_document.Id_Document
    															AND form_document_langue.Id_Langue=form_session_personne_document.Id_LangueDocument
    															AND Suppr=0
                                                        ) AS Document
													FROM
                                                        form_session_personne_document
													WHERE
                                                        Id_Session_Personne=".$rowSession['Id_Session_Personne']." ";
												$resultDoc=mysqli_query($bdd,$reqDoc);
												$NbDoc=mysqli_num_rows($resultDoc);
												
												if($NbDoc>0)
												{
													while($RowDoc=mysqli_fetch_array($resultDoc))
													{
														//QCM de la qualification 
														if($RowDoc['Id_Document']>0)
														{
															$Etat="";
															if($RowDoc['DateHeureRepondeur']>"0001-01-01 00:00:00")
															{
																if($LangueAffichage=="FR"){$Etat="Répondu";}
																else{$Etat="Answered";}
															}
															echo "<tr>";
															echo "<td width='10%'></td>";
															echo "<td width='25%'>";
															if(DocestOuvert($RowDoc['Id'])){echo "<a style='color:#0b7f17;text-decoration: underline;' href='javascript:Doc_Web(\"".$RowDoc['Id']."\");'>";}
															echo $RowDoc['Document'];
															if(DocestOuvert($RowDoc['Id'])){echo "</a>";}
															echo "</td>";
															echo "<td width='65%'>";
															echo $Etat;
															echo "</td>";
															echo "</tr>";
														}
													}
												}
											?>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4px"></td></tr>
					<?php
					}
				}
			?>
		</table>
		</td>
		<td width="50%" valign='top'>
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td class="EnTeteTableauCompetences" style="color:#0026e0;" colspan="2">
						<?php 
							if($LangueAffichage=="FR"){echo "QCM SANS SESSION DE FORMATION";}else{echo "MCQ WITHOUT TRAINING SESSION";} 
						?>
					</td>
				</tr>
				<tr><td height="4px"></td></tr>
				<?php
				//Liste des sessions de formation sans session ouverts
				$req="
                    SELECT 
					    form_besoin.Id,
                        form_besoin.Id_Formation, 
					    (
                            SELECT
                                (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme)
                            FROM
                                form_formation_plateforme_parametres 
						    WHERE
                                form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
						        AND form_formation_plateforme_parametres.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=form_besoin.Id_Prestation)
						        AND Suppr=0 LIMIT 1
                        ) AS Organisme,
					    (
                            SELECT
                                IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
							FROM
                                form_formation_langue_infos
							WHERE
                                Id_Formation=form_besoin.Id_Formation
							    AND Id_Langue=
								(
                                    SELECT Id_Langue 
								    FROM form_formation_plateforme_parametres 
								    WHERE
                                        Id_Plateforme=
										(
                                            SELECT
                                                Id_Plateforme 
											FROM
                                                new_competences_prestation 
											WHERE
                                                new_competences_prestation.Id=form_besoin.Id_Prestation
                                        )
										AND Id_Formation=form_besoin.Id_Formation
										AND Suppr=0 
                                    LIMIT 1
                                )
							AND Suppr=0
                        ) AS Libelle						
				    FROM
                        form_session_personne_qualification 
					LEFT JOIN form_besoin
                        ON form_session_personne_qualification.Id_Besoin = form_besoin.Id 
					WHERE
                        form_session_personne_qualification.Suppr=0 
						AND form_besoin.Suppr=0
						AND (form_session_personne_qualification.DateHeureFermeture <= '0001-01-01'
							OR
							(SELECT
								COUNT(form_session_personne_document.Id)
							FROM
								form_session_personne_document
							LEFT JOIN 
								form_session_personne_qualification AS TAB2
							ON form_session_personne_document.Id_SessionPersonneQualification=TAB2.Id
							WHERE
								form_session_personne_document.Suppr=0
								AND form_session_personne_document.DateHeureRepondeur <= '0001-01-01'
								AND TAB2.Suppr=0
								AND TAB2.SessionRealise=1
								AND TAB2.Id=form_session_personne_qualification.Id)>0
						)
						AND form_session_personne_qualification.DateHeureOuverture > '0001-01-01'
						AND form_session_personne_qualification.DateHeureOuverture <= '".date("Y-m-d H:i:s")."'
						AND form_session_personne_qualification.TypePassageQCM=1
						AND form_session_personne_qualification.Suppr=0
						AND form_session_personne_qualification.Id_Session_Personne=0
						AND form_besoin.Id_Personne=".$IdPersonneConnectee." ";
				$resultSessions=mysqli_query($bdd,$req);
				$nbSession=mysqli_num_rows($resultSessions);
				if($nbSession>0)
				{
					mysqli_data_seek($resultSessions,0);
					while($rowSession=mysqli_fetch_array($resultSessions))
					{
					?>
						<tr>
							<td>
								<table style="width:100%;">
									<tr>
										<td style="color:#0026e0;" colspan="2">
											<?php echo strtoupper($rowSession['Libelle']." ".$rowSession['Organisme'])."&nbsp;&nbsp;&nbsp;"; ?>
										</td>
									</tr>
									<tr><td height="4px"></td></tr>
									<tr>
										<td width="95%" align="left" colspan="2">
											<table style="width:100%;">
											<?php
												//Liste des qualifications acquises pour cette formation
												$reqQualif="
													SELECT
                                                        Id,
                                                        Id_QCM,
                                                        Id_QCM_Lie,
                                                        Id_LangueQCM,
                                                        Resultat,
                                                        ResultatMere,
                                                        Id_Repondeur,
														DateHeureRepondeur,
                                                        Etat,
                                                        (
                                                            SELECT
                                                                Libelle 
                                                            FROM 
                                                                form_qcm_langue 
                                                            WHERE
                                                                form_qcm_langue.Id_QCM=form_session_personne_qualification.Id_QCM
    															AND form_qcm_langue.Id_Langue=form_session_personne_qualification.Id_LangueQCM
    															AND Suppr=0
    															AND Brouillon=0
                                                        ) AS QCM,
                                                        (SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualif 
													FROM
                                                        form_session_personne_qualification
													WHERE
                                                        Id_Besoin=".$rowSession['Id']." ";
												$resultQualif=mysqli_query($bdd,$reqQualif);
												$NbQualif=mysqli_num_rows($resultQualif);
												

												if($NbQualif>0)
                                                {
													while($RowQualif=mysqli_fetch_array($resultQualif))
													{
											?>
														<tr>
															<td colspan="3">&bull; <?php echo $RowQualif['Qualif']; ?> : </td>
														</tr>
											<?php
														//QCM de la qualification 
														if($RowQualif['Id_QCM']>0)
														{
															$resultat="";
															$resultatMere="";
															$Etat="";
															if($RowQualif['DateHeureRepondeur']>"0001-01-01 00:00:00")
															{
																if($RowQualif['Id_QCM_Lie']<>"")
																{
																	if($LangueAffichage=="FR"){$resultatMere="QCM mère : ".$RowQualif['ResultatMere']."<br>";}
																	else{$resultatMere="MCQ mother : ".$RowQualif['ResultatMere']."<br>";}
																	if($LangueAffichage=="FR"){$resultat="Note finale : ";}
                                                                    else{$resultat="Final note : ";}
																}
																$resultat.=$RowQualif['Resultat'];
																if($LangueAffichage=="FR")
																{
																	if($RowQualif['Etat']==1){$Etat="<br><font color='#2dbe29'>Réussite</font>";}
																	elseif($RowQualif['Etat']==-1){$Etat="<br><font color='#e80000'>Echec</font>";}
																}
																else
																{
																	if($RowQualif['Etat']==1){$Etat="<br><font color='#2dbe29'>Success</font>";}
																	elseif($RowQualif['Etat']==-1){$Etat="<br><font color='#e80000'>Failure</font>";}
																}
															}
															echo "<tr>";
															echo "<td width='10%'></td>";
															echo "<td width='25%'>";
															if(QCMestOuvert($RowQualif['Id'])){echo "<a style='color:#0b7f17;text-decoration: underline;' href='javascript:QCM_Web(\"".$RowQualif['Id']."\");'>";}
															echo $RowQualif['QCM'];
															if(QCMestOuvert($RowQualif['Id'])){echo "</a>";}
															echo "</td>";
															echo "<td width='65%'>";
															echo $resultatMere.$resultat.$Etat;
															echo "</td>";
															echo "</tr>";
															
															//Liste des documents complémentaires pour cette formation
															$reqDoc="
																SELECT
																	form_session_personne_document.Id,
																	form_session_personne_document.Id_Document,
																	form_session_personne_document.Id_LangueDocument,
																	form_session_personne_document.Id_Repondeur,
																	form_session_personne_document.DateHeureRepondeur,
																	(
																		SELECT
																			Libelle 
																		FROM
																			form_document_langue 
																		WHERE
																			form_document_langue.Id_Document=form_session_personne_document.Id_Document
																			AND form_document_langue.Id_Langue=form_session_personne_document.Id_LangueDocument
																			AND form_document_langue.Suppr=0
																	) AS Document
																FROM
																	form_session_personne_document
																LEFT JOIN 
																	form_session_personne_qualification
																ON form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
																WHERE
																	form_session_personne_document.Suppr=0
																	AND form_session_personne_qualification.Suppr=0
																	AND form_session_personne_qualification.SessionRealise=1
																	AND form_session_personne_qualification.Id=".$RowQualif['Id']." 
																LIMIT 1";
															$resultDoc=mysqli_query($bdd,$reqDoc);
															$NbDoc=mysqli_num_rows($resultDoc);
															if($NbDoc>0)
															{
																while($RowDoc=mysqli_fetch_array($resultDoc))
																{
																	//QCM de la qualification 
																	if($RowDoc['Id_Document']>0)
																	{
																		$Etat="";
																		if($RowDoc['DateHeureRepondeur']>"0001-01-01 00:00:00")
																		{
																			if($LangueAffichage=="FR"){$Etat="Répondu";}
																			else{$Etat="Answered";}
																		}
																		echo "<tr>";
																		echo "<td width='10%'></td>";
																		echo "<td width='25%'>";
																		if(DocestOuvert($RowDoc['Id'])){echo "<a style='color:#0b7f17;text-decoration: underline;' href='javascript:Doc_WebSansSession(\"".$RowDoc['Id']."\");'>";}
																		echo $RowDoc['Document'];
																		if(DocestOuvert($RowDoc['Id'])){echo "</a>";}
																		echo "</td>";
																		echo "<td width='65%'>";
																		echo $Etat;
																		echo "</td>";
																		echo "</tr>";
																	}
																}
															}
														}
													}
												}
											?>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4px"></td></tr>
					<?php
					}
				}
			?>
		</table>
		</td>
	</tr>
	<tr><td height="4px"></td></tr>
	<tr>
		<td width="50%" valign='top'>
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td class="EnTeteTableauCompetences" style="color:#0026e0;" colspan="2">
						<?php 
							if($LangueAffichage=="FR"){echo "QCM DE SURVEILLANCE";}else{echo "MONITORING MCQ";} 
						?>
					</td>
				</tr>
				<tr><td height="4px"></td></tr>
				<?php
				//Liste des QCM d'une surveillance
				$req="
                    SELECT 
                        new_competences_relation.Id, 
                        (SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage) AS Qualif
					FROM
                        form_session_personne_qualification 
					LEFT JOIN
                        new_competences_relation
                        ON form_session_personne_qualification.Id_Relation = new_competences_relation.Id 
					WHERE
                        form_session_personne_qualification.Suppr=0 
						AND new_competences_relation.Suppr=0
						AND form_session_personne_qualification.DateHeureFermeture <= '0001-01-01'
						AND form_session_personne_qualification.DateHeureOuverture > '0001-01-01'
						AND form_session_personne_qualification.DateHeureOuverture <= '".date("Y-m-d H:i:s")."'
						AND form_session_personne_qualification.TypePassageQCM=2
						AND form_session_personne_qualification.Id_Session_Personne=0
						AND new_competences_relation.Id_Personne=".$IdPersonneConnectee." ";
				$resultSessions=mysqli_query($bdd,$req);
				$nbSession=mysqli_num_rows($resultSessions);
				if($nbSession>0)
				{
					mysqli_data_seek($resultSessions,0);
					while($rowSession=mysqli_fetch_array($resultSessions))
					{
					?>
						<tr>
							<td>
								<table style="width:100%;">
									<tr>
										<td style="color:#0026e0;" colspan="2">
											<?php echo strtoupper($rowSession['Qualif'])."&nbsp;&nbsp;&nbsp;"; ?>
										</td>
									</tr>
									<tr><td height="4px"></td></tr>
									<tr>
										<td width="95%" align="left" colspan="2">
											<table style="width:100%;">
											<?php
												//Liste des qualifications acquises pour cette formation
												$reqQualif="
													SELECT
                                                        Id,
                                                        Id_QCM,
                                                        Id_QCM_Lie,
                                                        Id_LangueQCM,
                                                        Resultat,ResultatMere,
                                                        Id_Repondeur,
														DateHeureRepondeur,
                                                        Etat,
                                                        (
                                                            SELECT
                                                                Libelle 
                                                            FROM
                                                                form_qcm_langue 
    														WHERE
                                                                form_qcm_langue.Id_QCM=form_session_personne_qualification.Id_QCM
        														AND form_qcm_langue.Id_Langue=form_session_personne_qualification.Id_LangueQCM
        														AND Suppr=0
        														AND Brouillon=0
                                                        ) AS QCM,
                                                        (
                                                            SELECT
                                                                Libelle
                                                            FROM
                                                                new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification
                                                        ) AS Qualif 
													FROM
                                                        form_session_personne_qualification
													WHERE
														Suppr=0
                                                        AND Id_Relation=".$rowSession['Id']." ";
												$resultQualif=mysqli_query($bdd,$reqQualif);
												$NbQualif=mysqli_num_rows($resultQualif);
												
												echo "";
												if($NbQualif>0)
												{
													while($RowQualif=mysqli_fetch_array($resultQualif))
													{
											?>
														<tr>
															<td colspan="3">&bull; <?php echo $RowQualif['Qualif']; ?> : </td>
														</tr>
											<?php
														//QCM de la qualification 
														if($RowQualif['Id_QCM']>0)
														{
															$resultat="";
															$resultatMere="";
															$Etat="";
															if($RowQualif['DateHeureRepondeur']>"0001-01-01 00:00:00")
															{
																if($RowQualif['Id_QCM_Lie']<>"")
																{
																	if($LangueAffichage=="FR"){$resultatMere="QCM mère : ".$RowQualif['ResultatMere']."<br>";}
																	else{$resultatMere="MCQ mother : ".$RowQualif['ResultatMere']."<br>";}
																	if($LangueAffichage=="FR"){$resultat="Note finale : ";}
																    else{$resultat="Final note : ";}
																}
																$resultat.=$RowQualif['Resultat'];
																if($LangueAffichage=="FR")
																{
																	if($RowQualif['Etat']==1){$Etat="<br><font color='#2dbe29'>Réussite</font>";}
																	elseif($RowQualif['Etat']==-1){$Etat="<br><font color='#e80000'>Echec</font>";}
																}
																else
																{
																	if($RowQualif['Etat']==1){$Etat="<br><font color='#2dbe29'>Success</font>";}
																	elseif($RowQualif['Etat']==-1){$Etat="<br><font color='#e80000'>Failure</font>";}
																}
															}
															echo "<tr>";
															echo "<td width='10%'></td>";
															echo "<td width='25%'>";
															if(QCMestOuvert($RowQualif['Id'])){echo "<a style='color:#0b7f17;text-decoration: underline;' href='javascript:QCM_Web(\"".$RowQualif['Id']."\");'>";}
															echo $RowQualif['QCM'];
															if(QCMestOuvert($RowQualif['Id'])){echo "</a>";}
															echo "</td>";
															echo "<td width='65%'>";
															echo $resultatMere.$resultat.$Etat;
															echo "</td>";
															echo "</tr>";
														}
													}
												}
											?>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4px"></td></tr>
					<?php
					}
				}
			?>
		</table>
		</td>
		<td valign='top'>
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td class="EnTeteTableauCompetences" style="color:#0026e0;" colspan="2">
						<?php 
							if($LangueAffichage=="FR"){echo "SESSIONS DE FORMATION A VENIR";}else{echo "TRAINING SESSIONS TO COME";} 
						?>
					</td>
				</tr>
				<tr><td height="4px"></td></tr>
				<?php
				//Liste des sessions de formation à venir
				$req="
                    SELECT
                        form_session.Id,
                        form_session.Id_Formation,
                        form_session.Id_Lieu,
                        form_session.Id_Formateur,
                        form_session_date.Id AS Id_SessionDate,
                        form_session.nom_fichier, 
                        form_session_date.DateSession,
                        form_session_date.Heure_Debut,
                        form_session_date.Heure_Fin,
                        form_session.chemin_fichier,
                        form_session.MessageConvocation, 
                        form_session.Recyclage,
                        form_session_date.PauseRepas,
                        form_session_date.HeureDebutPause,
                        form_session_date.HeureFinPause, 
                        (SELECT Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS Lieu,form_session.Id_GroupeSession,form_session.Formation_Liee, 
                        (SELECT (SELECT Libelle FROM form_groupe_formation WHERE form_groupe_formation.Id=form_session_groupe.Id_GroupeFormation) FROM form_session_groupe WHERE form_session_groupe.Id=form_session.Id_GroupeSession) AS Groupe, 
                        (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session.Id_Formateur) AS Formateur,
                        (
                            SELECT
                                Id FROM form_session_personne
                            WHERE
                                Validation_Inscription=1 
    							AND form_session_personne.Id_Personne=".$IdPersonneConnectee."
    							AND Suppr=0
    							AND form_session_personne.Id_Session=form_session.Id
							LIMIT 1
                        ) AS Id_Session_Personne,
                        (
                            SELECT
                                Id_Langue
                            FROM
                                form_formation_plateforme_parametres 
                            WHERE
                                form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
                                AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
                                AND Suppr=0 LIMIT 1
                        ) AS Id_Langue,
                        (
                            SELECT
                                (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
                            WHERE
                                form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
                                AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
                                AND Suppr=0 LIMIT 1
                        ) AS Organisme,
                        (
                            SELECT
                                IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
							FROM
                                form_formation_langue_infos
							WHERE
                                Id_Formation=form_session.Id_Formation
							    AND Id_Langue=
								(
                                    SELECT
                                        Id_Langue 
                                    FROM
                                        form_formation_plateforme_parametres 
                                    WHERE
                                        Id_Plateforme=form_session.Id_Plateforme
    									AND Id_Formation=form_session.Id_Formation
    									AND Suppr=0 
                                    LIMIT 1
                                )
							    AND Suppr=0
                        ) AS Libelle						
					FROM
                        form_session_date LEFT JOIN form_session
                        ON form_session_date.Id_Session = form_session.Id 
					WHERE
                        form_session_date.Suppr=0 
						AND form_session.Suppr=0 
						AND form_session.Annule=0 
						AND form_session.Diffusion_Creneau=1 
						AND form_session_date.DateSession>'".date('Y-m-d')."' 
						AND
                        (
                            SELECT
                                COUNT(form_session_personne.Id)
                            FROM
                                form_session_personne
                            WHERE
                                Validation_Inscription=1 
    							AND form_session_personne.Id_Personne=".$IdPersonneConnectee."
    							AND Suppr=0
    							AND form_session_personne.Id_Session=form_session.Id
                        )>0
					ORDER BY
                        form_session_date.Heure_Debut ";
				$resultSessions=mysqli_query($bdd,$req);
				$nbSession=mysqli_num_rows($resultSessions);
				if($nbSession>0)
				{
					mysqli_data_seek($resultSessions,0);
					while($rowSession=mysqli_fetch_array($resultSessions))
					{
					?>
						<tr>
							<td>
								<table style="width:100%;">
									<tr>
										<td style="color:#0026e0;" colspan="2">
											<?php echo strtoupper($rowSession['Libelle']." ".$rowSession['Organisme'])."&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;".AfficheDateJJ_MM_AAAA($rowSession['DateSession'])."&nbsp;&nbsp; - &nbsp;&nbsp;".substr($rowSession['Heure_Debut'],0,5)." / ".substr($rowSession['Heure_Fin'],0,5); ?>
										</td>
									</tr>
									<tr><td height="4px"></td></tr>
									<tr>
										<td width="40%">
										<?php 
											if($LangueAffichage=="FR"){echo "<u>Lieu de la formation </u>: ";}else{echo "<u>Place of training </u>: ";}
											echo $rowSession['Lieu'];
										?>
										</td>
										<td>
										<?php 
											if($LangueAffichage=="FR"){echo "<u>Formateur </u>: ";}else{echo "<u>Former </u>: ";}
											echo $rowSession['Formateur'];
										?>
										</td>
									</tr>
									<?php
										if($rowSession['MessageConvocation']<>"")
										{
									?>
									<tr><td height="4px"></td></tr>
									<tr>
										<td>
											<?php if($LangueAffichage=="FR"){echo "<u>Informations complémentaires </u>: ";}else{echo "<u>Further information </u>: ";}?>
										</td>
									</tr>
									<tr>
										<td>
											<?php echo $rowSession['MessageConvocation']; ?>
										</td>
									</tr>
									<?php
										}
										if($rowSession['nom_fichier']<>"" && $rowSession['chemin_fichier']<>"")
										{
											if(file_exists ($rowSession['chemin_fichier'].$rowSession['nom_fichier']))
											{
									?>
									<tr><td height="4px"></td></tr>
									<tr>
										<td>
											<?php if($LangueAffichage=="FR"){echo "<u>Convocation </u>: ";}else{echo "<u>Convocation </u>: ";}?>
											<?php 
												echo "<a class=\"Info\" href=\"".$rowSession['chemin_fichier'].$rowSession['nom_fichier']."\" target=\"_blank\">".$rowSession['nom_fichier']."</a>" ?>
										</td>
									</tr>
									<?php
											}
										}
									?>
								</table>
							</td>
						</tr>
						<tr><td height="4px"></td></tr>
					<?php
					}
				}
			?>
			</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>