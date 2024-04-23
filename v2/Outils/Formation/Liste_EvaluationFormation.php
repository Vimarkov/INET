<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function Excel(){
		var w=window.open("Excel_EvaluationFormation.php","PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	function OuvreDocument(NomDocumentPHP,Id_Session_Personne_Document){
		var w=window.open("Document_Modele/"+NomDocumentPHP+"?Id_Session_Personne_Document="+Id_Session_Personne_Document,"PageDocumentExcel","status=no,menubar=no,width=50,height=50");
		w.focus();
	}
</script>	
<?php
if($_POST)
{
	$_SESSION['FiltreFormEvalForm_Prestation']=$_POST['Prestation'];
	$_SESSION['FiltreFormEvalForm_Personne']=$_POST['Stagiaire'];
	$_SESSION['FiltreFormEvalForm_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreFormEvalForm_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreFormEvalForm_Formation']=$_POST['Formation'];
}
Ecrire_Code_JS_Init_Date(); 
?>
<form id="formulaire" action="Liste_EvaluationFormation.php" method="post">
	<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#1f9aa5;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Evaluations de formations";}else{echo "Training evaluations";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table style="width:100%; align:center; border-spacing:0;" class="GeneralInfo">
				<tr>
					<td height="4"></td>
					<td>
						<table width="100%">
							<tr>
							<?php 
								if($LangueAffichage=="FR"){
									echo "<td class=\"Libelle\">Prestation/Pôle</td>";
								}
								else{
									echo "<td class=\"Libelle\">Activity/Pole</td>";
								}
								$Prestation=$_SESSION['FiltreFormEvalForm_Prestation'];
							?>
								<td><input style="width:200px" id="Prestation" name="Prestation" value="<?php echo $Prestation;?>"></td>
								<td class="Libelle">
								<?php 
									if($LangueAffichage=="FR"){echo "Date de début";}else{echo "Start date";}
									$dateD="";
									$dateD=$_SESSION['FiltreFormEvalForm_DateDebut'];
								?>
								</td>
								<td><input type="date" id="DateDebut" name="DateDebut" style="width:110px;" value="<?php echo $dateD;?>"></td>
								<td class="Libelle">
								<?php 
									if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}
									$formation="";
									$formation=$_SESSION['FiltreFormEvalForm_Formation'];
								?>
								</td>
								<td><input style="width:200px" id="Formation" name="Formation" value="<?php echo $formation;?>"></td>
								<td><input style='cursor:pointer;' class="Bouton" type="submit" value="Filtrer" ></td>
							</tr>
							<tr>	
								<td class="Libelle">
								<?php 
									if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}
									$stagiaire="";
									$stagiaire=$_SESSION['FiltreFormEvalForm_Personne'];
								?>
								</td>
								<td><input id="Stagiaire" name="Stagiaire" value="<?php echo $stagiaire;?>"></td>
								<td class="Libelle">
								<?php 
									if($LangueAffichage=="FR"){echo "Date de fin";}else{echo "End date";}
									$dateF="";
									$dateF=$_SESSION['FiltreFormEvalForm_DateFin'];
								?>
								</td>
								<td><input type="date" id="DateFin" name="DateFin" style="width:110px;" value="<?php echo $dateF;?>"></td>
								<td><img src="..\..\Images\excel.gif" style="cursor : pointer;" onclick="Excel()"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td>
	<tr>
		<td>
			<div style="width:100%;height:400px;overflow:auto;">
			<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
				<tr bgcolor="#2c8bb4">
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="7%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="5%"><?php if($LangueAffichage=="FR"){echo "Pôle";}else{echo "Pole";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="30%"><?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><?php if($LangueAffichage=="FR"){echo "Formateur";}else{echo "Former";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><?php if($LangueAffichage=="FR"){echo "Heure de début";}else{echo "Start time";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><?php if($LangueAffichage=="FR"){echo "Heure de fin";}else{echo "End time";} ?></td>
					<?php 
						$req="SELECT Id, Libelle 
							FROM form_document_langue_question
							WHERE Suppr=0 
							AND TypeReponse='Note (1 à 6)'
							AND Id_Document_Langue=(SELECT Id FROM form_document_langue WHERE Suppr=0 AND Id_Document=6 LIMIT 1) 
							";
						$ResultQuestion=mysqli_query($bdd,$req);
						$NbQuest=mysqli_num_rows($ResultQuestion);
						if($NbQuest>0)
						{
							while($row=mysqli_fetch_array($ResultQuestion))
							{
								echo "<td class='EnTeteTableauCompetences' style='color:#ffffff;border-bottom:1px dottom black;' width='6%'>".$row['Libelle']."</td>";
							}
						}
							
					?>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><?php if($LangueAffichage=="FR"){echo "Note moyenne";}else{echo "Average grade";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="1%"></td>
				</tr>
				<?php
					$req="
						SELECT
							form_session_personne_document.Id,
							form_session_personne.Id_Personne,
							(SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP,
							(
							SELECT
								(SELECT IF(form_besoin.Motif='Renouvellement' AND form_session.Recyclage=1,LibelleRecyclage,Libelle)
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_besoin.Id_Formation
								AND Id_Langue=
									(SELECT Id_Langue 
									FROM form_formation_plateforme_parametres 
									WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
									AND Id_Formation=form_besoin.Id_Formation
									AND Suppr=0 
									LIMIT 1)
								AND Suppr=0)
							FROM
								form_besoin
							WHERE
								form_besoin.Id=form_session_personne.Id_Besoin
							
							) AS Formation,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session.Id_Formateur) AS Formateur,
							(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS DateDebut,
							(SELECT Heure_Debut FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS HeureDebut,
							(SELECT Heure_Fin FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) AS HeureFin,
							(
								SELECT
								(
									SELECT
										Libelle 
									FROM
										new_competences_prestation 
									WHERE
										new_competences_prestation.Id=form_besoin.Id_Prestation
								)
								FROM
									form_besoin
								WHERE form_besoin.Id=form_session_personne.Id_Besoin
							) AS Prestation,
							(
								SELECT
								(
									SELECT
										Libelle 
									FROM
										new_competences_pole 
									WHERE
										new_competences_pole.Id=form_besoin.Id_Pole
								)
								FROM
									form_besoin
								WHERE
									form_besoin.Id=form_session_personne.Id_Besoin
							) AS Pole,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne
						FROM form_session_personne_document
						LEFT JOIN form_session_personne ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
						LEFT JOIN form_session ON form_session_personne.Id_Session=form_session.Id
						WHERE
							form_session_personne.Suppr=0
							AND form_session.Annule=0
							AND form_session.Suppr=0
							AND form_session_personne.Presence=1
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne_document.Suppr=0 
							AND form_session_personne_document.DateHeureRepondeur>'0001-01-01'
							AND form_session_personne_document.Id_Document=6
							AND form_session_personne.Id_Personne IN
								(
									SELECT
										Id_Personne 
									FROM
										new_competences_personne_prestation
									LEFT JOIN
										new_competences_prestation 
									ON
										new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
									WHERE
										new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' 
										AND Id_Plateforme IN
										(
											SELECT
												Id_Plateforme 
											FROM
												new_competences_personne_poste_plateforme
											WHERE
												Id_Personne=".$IdPersonneConnectee."
												AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
										)
								) ";
					
					if($Prestation<>"")
					{
						$req.="
							AND
							( 
								(
								SELECT
								(
									SELECT
										Libelle 
									FROM
										new_competences_prestation 
									WHERE
										new_competences_prestation.Id=form_besoin.Id_Prestation
								)
								FROM
									form_besoin
								WHERE form_besoin.Id=form_session_personne.Id_Besoin
							) LIKE '%".$Prestation."%' 
								OR
								(
								SELECT
								(
									SELECT
										Libelle 
									FROM
										new_competences_pole 
									WHERE
										new_competences_pole.Id=form_besoin.Id_Pole
								)
								FROM
									form_besoin
								WHERE
									form_besoin.Id=form_session_personne.Id_Besoin
							) LIKE '%".$Prestation."%'
							)";
					}
					if($stagiaire<>""){$req.="AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) LIKE '%".$stagiaire."%' ";}
					if($formation<>""){$req.="AND (
							SELECT
								(SELECT IF(form_besoin.Motif='Renouvellement' AND form_session.Recyclage=1,LibelleRecyclage,Libelle)
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_besoin.Id_Formation
								AND Id_Langue=
									(SELECT Id_Langue 
									FROM form_formation_plateforme_parametres 
									WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
									AND Id_Formation=form_besoin.Id_Formation
									AND Suppr=0 
									LIMIT 1)
								AND Suppr=0)
							FROM
								form_besoin
							WHERE
								form_besoin.Id=form_session_personne.Id_Besoin
							
							) LIKE '%".$formation."%' ";}
					
					if($dateD<>"")
					{
						$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) >= '".TrsfDate_($dateD)."' ";
					}
					if($dateF<>"")
					{
						$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) <= '".TrsfDate_($dateF)."' ";
					}
					
					//Ajout des eval sans session
					$req.="UNION
						SELECT
							form_session_personne_document.Id,
							form_besoin.Id_Personne,
							(SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP,
							(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_besoin.Id_Formation
								AND Id_Langue=
									(SELECT Id_Langue 
									FROM form_formation_plateforme_parametres 
									WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
									AND Id_Formation=form_besoin.Id_Formation
									AND Suppr=0 
									LIMIT 1)
								AND Suppr=0) AS Formation,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne_qualification.Id_Ouvreur) AS Formateur,
							LEFT(form_session_personne_document.DateHeureRepondeur,10) AS DateDebut,
							'' AS HeureDebut,
							'' AS HeureFin,
							(
									SELECT
										Libelle 
									FROM
										new_competences_prestation 
									WHERE
										new_competences_prestation.Id=form_besoin.Id_Prestation
								) AS Prestation,
							(
									SELECT
										Libelle 
									FROM
										new_competences_pole 
									WHERE
										new_competences_pole.Id=form_besoin.Id_Pole
								) AS Pole,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS Personne
						FROM form_session_personne_document
						LEFT JOIN form_session_personne_qualification ON form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
						LEFT JOIN form_besoin ON form_session_personne_qualification.Id_Besoin=form_besoin.Id
						WHERE
							form_session_personne_qualification.Suppr=0
							AND form_session_personne_qualification.TypePassageQCM=1
							AND form_session_personne_document.Suppr=0 
							AND form_session_personne_document.DateHeureRepondeur>'0001-01-01'
							AND form_session_personne_document.Id_Document=6
							AND form_besoin.Id_Personne IN
								(
									SELECT
										Id_Personne 
									FROM
										new_competences_personne_prestation
									LEFT JOIN
										new_competences_prestation 
									ON
										new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
									WHERE
										new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' 
										AND Id_Plateforme IN
										(
											SELECT
												Id_Plateforme 
											FROM
												new_competences_personne_poste_plateforme
											WHERE
												Id_Personne=".$IdPersonneConnectee."
												AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
										)
								) ";
					
					if($Prestation<>"")
					{
						$req.="
							AND
							( 
								(
									SELECT
										Libelle 
									FROM
										new_competences_prestation 
									WHERE
										new_competences_prestation.Id=form_besoin.Id_Prestation
								) LIKE '%".$Prestation."%' 
								OR
								(
									SELECT
										Libelle 
									FROM
										new_competences_pole 
									WHERE
										new_competences_pole.Id=form_besoin.Id_Pole
								) LIKE '%".$Prestation."%'
							)";
					}
					if($stagiaire<>""){$req.="AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) LIKE '%".$stagiaire."%' ";}
					if($formation<>""){$req.="AND (SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_besoin.Id_Formation
								AND Id_Langue=
									(SELECT Id_Langue 
									FROM form_formation_plateforme_parametres 
									WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
									AND Id_Formation=form_besoin.Id_Formation
									AND Suppr=0 
									LIMIT 1)
								AND Suppr=0) LIKE '%".$formation."%' ";}
					
					if($dateD<>"")
					{
						$req.="AND LEFT(form_session_personne_document.DateHeureRepondeur,10) >= '".TrsfDate_($dateD)."' ";
					}
					if($dateF<>"")
					{
						$req.="AND LEFT(form_session_personne_document.DateHeureRepondeur,10) <= '".TrsfDate_($dateF)."' ";
					}
					
					
					$req.="ORDER BY DateDebut DESC";
					$ResultSessions=mysqli_query($bdd,$req);
					$NbSessions=mysqli_num_rows($ResultSessions);
					
					$couleur="bgcolor='#ffffff'";
					if($NbSessions>0)
					{
						while($row=mysqli_fetch_array($ResultSessions))
						{
								$Moyenne="";
								$req="
								SELECT AVG(form_session_personne_document_question_reponse.Valeur_Reponse) AS Moyenne
								FROM form_session_personne_document_question_reponse
								LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
								WHERE form_session_personne_document_question_reponse.Suppr=0
								AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
								AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=".$row['Id']." 
								";
								$ResultNote=mysqli_query($bdd,$req);
								$NbNote=mysqli_num_rows($ResultNote);
								if($NbNote>0){
									$rowMoyenne=mysqli_fetch_array($ResultNote);
									$Moyenne=$rowMoyenne['Moyenne'];
								}
								
								/*$req="
								SELECT form_session_personne_document_question_reponse.Valeur_Reponse
								FROM form_session_personne_document_question_reponse
								LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
								WHERE form_session_personne_document_question_reponse.Suppr=0
								AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
								AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=".$row['Id']." 
								AND form_session_personne_document_question_reponse.Valeur_Reponse<=3
								";
								$ResultNote2=mysqli_query($bdd,$req);
								$NbNote2=mysqli_num_rows($ResultNote2);
								
								if($NbNote2>0){*/
									if($couleur=="bgcolor='#ffffff'"){$couleur="bgcolor='#e6e6e6'";}
									else{$couleur="bgcolor='#ffffff'";}
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
									<tr <?php echo $couleur; ?>>
										<td <?php echo $couleur; ?>><?php echo AfficheCodePrestation(stripslashes($row['Prestation'])); ?></td>
										<td><?php echo stripslashes($row['Pole']); ?></td>
										<td><?php echo stripslashes($row['Personne']); ?></td>
										<td><?php echo $Contrat; ?></td>
										<td><?php echo stripslashes($row['Formation']); ?></td>
										<td><?php echo stripslashes($row['Formateur']); ?></td>
										<td><?php echo stripslashes(AfficheDateJJ_MM_AAAA($row['DateDebut'])); ?></td>
										<td><?php echo stripslashes(substr($row['HeureDebut'],0,5)); ?></td>
										<td><?php echo stripslashes(substr($row['HeureFin'],0,5)); ?></td>
										<?php 
											$req="SELECT Id, Libelle 
												FROM form_document_langue_question
												WHERE Suppr=0 
												AND TypeReponse='Note (1 à 6)'
												AND Id_Document_Langue=(SELECT Id FROM form_document_langue WHERE Suppr=0 AND Id_Document=6 LIMIT 1) 
												";
											$ResultQuestion=mysqli_query($bdd,$req);
											$NbQuest=mysqli_num_rows($ResultQuestion);
											if($NbQuest>0)
											{
												while($row2=mysqli_fetch_array($ResultQuestion))
												{
													$req="
													SELECT form_session_personne_document_question_reponse.Valeur_Reponse, Texte_Reponse
													FROM form_session_personne_document_question_reponse
													LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
													WHERE form_session_personne_document_question_reponse.Suppr=0
													AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
													AND form_document_langue_question.Id=".$row2['Id']."
													AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=".$row['Id']." 
													";
													$ResultNote3=mysqli_query($bdd,$req);
													$NbNote3=mysqli_num_rows($ResultNote3);
													$note="";
													$etoile="";
													$lacouleur="";
													if($NbNote3>0){
														$row3=mysqli_fetch_array($ResultNote3);
														$note=$row3['Valeur_Reponse'];
														if($note<=3){
															$lacouleur="bgcolor='#ff7575'";
														}
														if($row3['Texte_Reponse']<>""){$etoile="<img width='10px' src='../../Images/etoile.png' style='border:0;'>";}
													}
													echo "<td ".$lacouleur." align='center'>".$note.$etoile."</td>";
												}
											}
												
										?>
										<td><?php echo stripslashes($Moyenne); ?></td>
										<td valign='center'><a class='Modif' href="javascript:OuvreDocument('<?php echo $row['Fichier_PHP'];?>','<?php echo $row['Id'];?>');"><img width='20px' src='../../Images/pdf.png' style='border:0;' alt='Document'>&nbsp;&nbsp;</a></td>
									</tr>
								<?php
								//}
						}
					}
				?>
			</table>
			</div>
		</td>
	</tr>
	</table>
</form>
</html>
	