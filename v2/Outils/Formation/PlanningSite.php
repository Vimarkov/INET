<?php
require("../../Menu.php");

$gris="#e4eae8";
$violet="#cd8be9";
$jaune="#f2ff43";
$bleu="#52a5f0";
$vert="#5cec4e";

$requetePersonnes="SELECT
	Id_Personne
FROM
	new_competences_personne_prestation
WHERE
	Date_Fin>='".$DateJour."'";
	 
$requetePersonnes.="AND CONCAT(Id_Prestation,'_',Id_Pole)   IN (SELECT CONCAT(Id_Prestation,'_',Id_Pole)  
FROM new_competences_personne_prestation
WHERE Date_Fin>='".$DateJour."' AND CONCAT(Id_Prestation,'_',Id_Pole)  IN (
	SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
	FROM new_competences_personne_poste_prestation
	WHERE Id_Personne=".$IdPersonneConnectee." AND
	Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite."))) ";

$resultPersResp=mysqli_query($bdd,$requetePersonnes);
$nbPersResp=mysqli_num_rows($resultPersResp);
$listeRespPers=0;
if($nbPersResp>0){
	$listeRespPers="";
	while($rowPersResp=mysqli_fetch_array($resultPersResp)){
		$listeRespPers.=$rowPersResp['Id_Personne'].",";
	}
	$listeRespPers=substr($listeRespPers,0,-1);
}
?>
	
	<?php Ecrire_Code_JS_Init_Date(); ?>
	<form action="PlanningSite.php" method="post">
	<table style="width:100%; border-spacing:0; align:center;">
	<tr><td colspan="2">
		<table style="width:100%; border-spacing:0; align:center;">
			<tr>
				<td colspan="5">
					<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ff8c1f;">
						<tr>
							<td class="TitrePage">
							<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
							if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
								
							if($LangueAffichage=="FR"){echo "Planning";}else{echo "Scheduling";}
							?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="8px"></td></tr>
			<tr>
				<td>
					<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
						<tr><td height="4px"></td></tr>
						<tr>
							<td class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
							<td width="8%">
								<select id="Id_Plateforme" name="Id_Plateforme" onchange="submit()">
									<?php
									$Plateforme=0;
									$reqPla="SELECT DISTINCT 
										(SELECT Id_Plateforme FROM new_competences_prestation 
										WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation) AS Id_Plateforme,
										(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) 
										FROM new_competences_prestation 
										WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation) AS Libelle 
										FROM new_competences_personne_poste_prestation 
										WHERE Id_Poste 
											IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
										AND Id_Personne=".$IdPersonneConnectee." 
										ORDER BY Libelle";
									$resultPlateforme=mysqli_query($bdd,$reqPla);
									$nbFormation=mysqli_num_rows($resultPlateforme);
									if($nbFormation>0){
										$selected="";
										if(isset($_POST['Id_Plateforme']))
										{
											if($_POST['Id_Plateforme']==0){$selected="selected";}
										}
										if(isset($_GET['Id_Plateforme']))
										{
											if($_GET['Id_Plateforme']==0){$selected="selected";}
										}
										while($rowplateforme=mysqli_fetch_array($resultPlateforme))
										{
											$selected="";
											if(isset($_POST['Id_Plateforme']))
											{
												if($_POST['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
											}
											if(isset($_GET['Id_Plateforme']))
											{
												if($_GET['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
											}
											echo "<option value='".$rowplateforme['Id_Plateforme']."' ".$selected.">".$rowplateforme['Libelle']."</option>\n";
											if($Plateforme==0){$Plateforme=$rowplateforme['Id_Plateforme'];}
										}
									}
									if(isset($_POST['Id_Plateforme'])){$Plateforme=$_POST['Id_Plateforme'];}
									if(isset($_GET['Id_Plateforme'])){$Plateforme=$_GET['Id_Plateforme'];}
									?>
								</select>
							</td>
							<td class="Libelle" width="10%">
								&nbsp;
								<?php if($LangueAffichage=="FR"){echo "Date de début :";}else{echo "Start date";}?>
							</td>
							<td width="55%">
								<?php
									$dateDebut=AfficheDateFR($_SESSION['FiltreFormPlanning_DateDebut']);
									$dateDeFin=AfficheDateFR($_SESSION['FiltreFormPlanning_DateFin']);
									$MoisPrecedent=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDebut)." - 1 month")));
									$MoisSuivant=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDebut)." + 1 month")));
									if(isset($_GET['DateDeDebut']))
									{
										$dateDebut=$_GET['DateDeDebut'];
										$_SESSION['FiltreFormPlanning_DateDebut']=TrsfDate_($dateDebut);
										
										$MoisPrecedent=$dateDebut;
										$MoisSuivant=$dateDeFin;
									}
									elseif(isset($_POST['DateDeDebut']))
									{
										$dateDebut=$_POST['DateDeDebut'];
										$_SESSION['FiltreFormPlanning_DateDebut']=TrsfDate_($dateDebut);
										
										$MoisPrecedent=$dateDebut;
										$MoisSuivant=$dateDeFin;
									}
									if(isset($_POST['DateDeFin']))
									{
										$dateDeFin=$_POST['DateDeFin'];
										$_SESSION['FiltreFormPlanning_DateFin']=TrsfDate_($dateDeFin);
									}
									if(isset($_POST['MoisPrecedent']))
									{
										$dateDebut=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDebut)." - 1 month")));
										$dateDeFin=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDeFin)." - 1 month")));
										
										$MoisPrecedent=$dateDebut;
										$MoisSuivant=$dateDeFin;
										
										$MoisPrecedent=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($MoisPrecedent)." - 1 month")));
										$MoisSuivant=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($MoisSuivant)." - 1 month")));
									}
									elseif(isset($_POST['MoisSuivant']))
									{
										$dateDebut=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDebut)." + 1 month")));
										$dateDeFin=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDeFin)." + 1 month")));
										
										$MoisPrecedent=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($MoisPrecedent)." + 1 month")));
										$MoisSuivant=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($MoisSuivant)." + 1 month")));
									}
								?>
								<input type="date" style="text-align:center;" id="DateDeDebut" name="DateDeDebut" size="10" value="<?php echo $dateDebut; ?>">
								<input class="Bouton" name="BtnDateDebut" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validated";}?>">
								&nbsp;
								<?php if($LangueAffichage=="FR"){echo "Date fin :";}else{echo "End date";}?> :
								<input type="date" style="text-align:center;" id="DateDeFin" name="DateDeFin"  size="10" value="<?php echo $dateDeFin; ?>">
								&nbsp;
								<input class="Bouton" name="MoisPrecedent" size="10" type="submit" alt="Mois précédent" value="<< <?php echo $MoisPrecedent; ?>">
								<input class="Bouton" name="MoisSuivant" size="10" type="submit" alt="Mois suivant" value="<?php echo $MoisSuivant; ?> >>">
							</td>
							<td width=5%>
								&nbsp;<a style="text-decoration:none;" href="javascript:OuvreFenetrePlanningSiteExport();">
									<img src="../../Images/excel.gif" border="0" alt="Excel" title="Export Excel">
								</a>&nbsp;
							</td>
						</tr>
						<tr><td height="4px"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";}?> :</td>
							<td>
								<?php
									$etat="";
									if(isset($_POST['etatAffichage'])){$etat=$_POST['etatAffichage'];}
									if(isset($_GET['etatAffichage'])){$etat=$_GET['etatAffichage'];}
								?>
								<select name="etatAffichage" id="etatAffichage" onchange="submit()">
									<option value="" <?php if($etat==""){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "";}else{echo "";}?></option>
									<option value="indisponible" <?php if($etat=="indisponible"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Pas de besoin / session passée";}else{echo "No need / past session";}?></option>
									<option value="disponible" <?php if($etat=="disponible"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Sessions disponibles";}else{echo "Sessions available";}?></option>
									<option value="stagiaires" <?php if($etat=="stagiaires"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Stagiaires pré-inscrits / inscrits";}else{echo "Pre-registered / registered trainees";}?></option>
								</select>
							</td>
							<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?> : </td>
							<td align="left" class="Libelle">
								<select name="formation" id="formation" style="width:400px" onchange="submit()">
									<option value="0_0"></option>
									<?php
									$formationR="";
									if(isset($_POST['formation'])){$formationR=$_POST['formation'];}
									if(isset($_GET['formation'])){$formationR=$_GET['formation'];}
									
									$requete="
											SELECT 
												IF(Id_FormationEquivalente>0,Id_FormationEquivalente,Id_Formation) AS Id_Formation,
												IF(Id_FormationEquivalente>0,FormationEquivalente,Libelle) AS Formation,
												IF(Id_FormationEquivalente>0,1,0) AS FormEquivalence
											FROM 
											(SELECT DISTINCT
												form_formation.Id AS Id_Formation,
												(SELECT 
												(SELECT Libelle FROM form_formationequivalente WHERE form_formationequivalente.Id=form_formationequivalente_formationplateforme.Id_FormationEquivalente)
												 FROM form_formationequivalente_formationplateforme
												 WHERE form_formationequivalente_formationplateforme.Id_Formation=form_formation.Id
												 AND form_formationequivalente_formationplateforme.Recyclage=0
												 LIMIT 1) AS FormationEquivalente,
												 (SELECT form_formationequivalente_formationplateforme.Id_FormationEquivalente
												 FROM form_formationequivalente_formationplateforme
												 WHERE form_formationequivalente_formationplateforme.Id_Formation=form_formation.Id 
												 AND form_formationequivalente_formationplateforme.Recyclage=0
												LIMIT 1) AS Id_FormationEquivalente,
												(SELECT Libelle
													FROM form_formation_langue_infos
													WHERE Id_Formation=form_formation.Id
													AND Id_Langue=
														(SELECT Id_Langue 
														FROM form_formation_plateforme_parametres 
														WHERE Id_Plateforme=".$Plateforme."
														AND Id_Formation=form_formation.Id
														AND Suppr=0 
														LIMIT 1)
													AND Suppr=0) AS Libelle,(@row_number:=@row_number + 1) AS rnk
											FROM
												form_formation
											WHERE 
												form_formation.Suppr=0 
												AND 
												(SELECT COUNT(Id)
													FROM form_formation_langue_infos
													WHERE Id_Formation=form_formation.Id
													AND Id_Langue=
														(SELECT Id_Langue 
														FROM form_formation_plateforme_parametres 
														WHERE Id_Plateforme=".$Plateforme."
														AND Id_Formation=form_formation.Id
														AND Suppr=0)
													AND Suppr=0)>0
											GROUP BY
												form_formation.Id
											ORDER BY
												Libelle) AS TAB
											GROUP BY 
												IF(Id_FormationEquivalente>0,Id_FormationEquivalente,Id_Formation),IF(Id_FormationEquivalente>0,1,0)
											ORDER BY 
												Formation
												";
									$resultForm=mysqli_query($bdd,$requete);
									while($rowForm=mysqli_fetch_array($resultForm))
									{
										$selected="";
										if($formationR<>"")
										{
											if($formationR==$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']){$selected="selected";}
										}
										echo "<option value='".$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']."' ".$selected.">";
										echo stripslashes($rowForm['Formation']);
										echo "</option>\n";
									}
									?>
								</select>
								
								<?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> :
								<select id="typeFormation" name="typeFormation" onchange="submit()">
									<option value="0"></option>
									<?php 
									$typeFormation=0; 
									if(isset($_POST['typeFormation'])){$typeFormation=$_POST['typeFormation'];}
									if(isset($_GET['typeFormation'])){$typeFormation=$_GET['typeFormation'];}
									$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
									$selected="";
									while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation))
									{
										$selected="";
										if($typeFormation<>"")
										{
											if($typeFormation==$rowTypeFormation['Id']){$selected="selected";}
										}
										echo "<option ".$selected." value='".$rowTypeFormation['Id']."'>".stripslashes($rowTypeFormation['Libelle'])."</option>\n";
									}
									
									?>
								</select>
							</td>
							<td>
								<input class="Bouton" name="BtnForm" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validated";}?>">
							</td>
						</tr>
						<tr><td height="4px"></td></tr>
					</table>
				</td>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table>
				<tr>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Légende";}else{echo "Legend";}?> : </td>
					<td class="Libelle" style="background-color:<?php echo $gris;?>;"><?php if($LangueAffichage=="FR"){echo "Pas de besoin / session passée";}else{echo "No need / past session";}?></td>
					<td class="Libelle" style="background-color:<?php echo $jaune;?>;"><?php if($LangueAffichage=="FR"){echo "Sessions disponibles";}else{echo "Sessions available";}?></td>
					<td class="Libelle" style="background-color:<?php echo $vert;?>;"><?php if($LangueAffichage=="FR"){echo "Stagiaires pré-inscrits / inscrits";}else{echo "Pre-registered / registered trainees";}?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td height="4px"></td></tr>
	<tr>
		<td style="font-size:20px;font-weight: bold;color:#055af4;" align="center" width="45%">
			<?php
				$tmpDate = TrsfDate_($dateDebut);
				$dateFin = TrsfDate_($dateDeFin);
				if($LangueAffichage=="FR")
				{
					$MoisLettre = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
				}
				else
				{
					$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
				}
				echo date('d', strtotime($tmpDate." + 0 month"))." ".$MoisLettre[intval(date('m', strtotime($tmpDate." + 0 month")))-1]." ".date('Y', strtotime($tmpDate." + 0 month"));
				echo " - ";
				echo date('d', strtotime($dateFin." + 0 month"))." ".$MoisLettre[intval(date('m', strtotime($dateFin." + 0 month")))-1]." ".date('Y', strtotime($dateFin." + 0 month"));
			?>
		</td>
	</tr>
	<tr><td height="4px"></td></tr>
	<tr><td colspan="2">
		<table style="width:150%; border-spacing:0;">
			<tr align="center">
				<td align="center" width="15px" valign="middle"></td>
				<td align="center" width="15px" valign="middle"></td>
				<td align="center" width="15px" valign="middle"></td>
				<td align="left" width="98%" valign="middle">
					<table style="margin:0; width:100%; border-spacing:0;">
						<tr>
							<?php 
								$heure=5;
								$min=0;
								for($i=1;$i<=61;$i++)
								{
									if($min==0){$minAffiche="";}
									else{$minAffiche=$min;}
									echo "<td class='EnTeteSemaine' width='15px' style='font-size:10px;border:1px solid #cccccc;word-break:break-all;'>".$heure."h<br>".$minAffiche."</td>";
									if($min==0){$min=15;}
									elseif($min==15){$min=30;}
									elseif($min==30){$min=45;}
									else{$min=0;$heure++;}
								}
							?>
						</tr>
					</table>
				</td>
			</tr>
			<?php
			//GESTION DU CORPS DU TABLEAU
			$tmpDate = TrsfDate_($dateDebut);
			$dateFin = TrsfDate_($dateDeFin);
			
			$tmpMois = date('n', strtotime($tmpDate." + 0 month")) . ' ' . date('Y', strtotime($tmpDate." + 0 month"));
			if($LangueAffichage=="FR")
			{
				$joursem = array("Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam");
			}
			else
			{
				$joursem = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
			}
			//Requete sessions de la période
			$req="
                SELECT
                    form_session.Id,
                    form_session.Id_Formation,
                    form_session_date.Id AS Id_SessionDate,
                    (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Id_TypeFormation,
                    (SELECT DateCreation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS DateCreationForm,
                    form_session_date.DateSession,
                    form_session_date.Heure_Debut,
                    form_session_date.Heure_Fin,
                    form_session.Recyclage,
                    form_session_date.PauseRepas,
                    form_session_date.HeureDebutPause,
                    form_session_date.HeureFinPause,
                    (SELECT COUNT(Id) FROM form_session_date WHERE form_session_date.Id_Session=form_session.Id) AS Nb,
					(SELECT COUNT(Id) FROM form_session_personne WHERE Validation_Inscription=0 AND Suppr=0 AND Id_Session=form_session.Id) AS NbPreInscrit,
                    (SELECT Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS Lieu,form_session.Id_GroupeSession,form_session.Formation_Liee,
                    (SELECT (SELECT Libelle FROM form_groupe_formation WHERE form_groupe_formation.Id=form_session_groupe.Id_GroupeFormation) FROM form_session_groupe WHERE form_session_groupe.Id=form_session.Id_GroupeSession) AS Groupe,
                    form_session.Nb_Stagiaire_Maxi,form_session.Nb_Stagiaire_Mini,
					form_session.MultiPlateforme,
					(
						SELECT COUNT(Id_Session)
						FROM form_session_prestation 
						WHERE form_session_prestation.Suppr=0 
						AND form_session_prestation.Id_Session=form_session.Id
						AND Id_Prestation IN (
							SELECT Id_Prestation 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")  
							AND Id_Personne=".$IdPersonneConnectee."
							)
					) AS NbPrestation
                FROM
                    form_session_date
                LEFT JOIN form_session
                    ON form_session_date.Id_Session = form_session.Id
                WHERE
                    form_session_date.Suppr=0
                    AND form_session.Suppr=0
                    AND form_session.Id_Plateforme=".$Plateforme."
                    AND form_session_date.DateSession>='".$tmpDate."'
                    AND form_session_date.DateSession<='".$dateFin."'
                    AND form_session.Diffusion_Creneau=1 ";
			//Liste des sessions créés pour sa prestation
			$req.="AND (
				(
				form_session.Id IN (
					SELECT Id_Session 
					FROM form_session_prestation 
					WHERE form_session_prestation.Suppr=0 
					AND form_session_prestation.Id_Session=form_session.Id
					AND Id_Prestation IN (
						SELECT Id_Prestation 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")  
						AND Id_Personne=".$IdPersonneConnectee."
						)
					)
				OR form_session.MultiPlateforme=1
				)					";
				//Liste des sessions contenants du personnel de ses prestations
				$req.=" OR form_session.Id IN (
							SELECT Id_Session 
							FROM form_session_personne 
							WHERE form_session_personne.Id_Personne IN (";
								//Liste de son personnel présent et futur
								$req.="SELECT Id_Personne 
									FROM new_competences_personne_prestation 
									WHERE Date_Fin>='".date('Y-m-d')."' 
									AND Id_Prestation IN (
										SELECT Id_Prestation 
										FROM new_competences_personne_poste_prestation 
										WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
										AND Id_Personne=".$IdPersonneConnectee."
										)
								)
						)
				) ";
			
			$tabQual=explode("_",$formationR);
			if($formationR<>"0_0" && $formationR<>""){
				if($tabQual[1]==0){
					$req.=" AND Id_Formation=".$tabQual[0]." ";
				}
				else{
					$req.=" AND Id_Formation IN (SELECT Id_Formation 
						FROM form_formationequivalente_formationplateforme 
						WHERE Id_FormationEquivalente=".$tabQual[0].") ";		
				}
			}

			if($typeFormation<>0){
				$req.=" AND (SELECT form_formation.Id_TypeFormation ";
				$req.= "FROM form_formation ";
				$req.= "WHERE form_formation.Id=form_session.Id_Formation)=".$typeFormation." ";
			}
			
			$req.=" AND form_session.Annule=0 ";
			$req.="ORDER BY form_session_date.DateSession, Heure_Fin";
			$resultSessions=mysqli_query($bdd,$req);
			$resultSessions2=mysqli_query($bdd,$req);

			$nbSession=mysqli_num_rows($resultSessions);	

			$requeteInfos="SELECT Id,Id_Formation,Id_Langue,Libelle,LibelleRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ";
			$resultInfos=mysqli_query($bdd,$requeteInfos);
			$nbInfos=mysqli_num_rows($resultInfos);
			
			$requeteParam="SELECT Id,Id_Formation,Id_Langue,(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme FROM form_formation_plateforme_parametres WHERE Suppr=0 AND Id_Plateforme=".$Plateforme." ";
			$resultParam=mysqli_query($bdd,$requeteParam);
			$nbParam=mysqli_num_rows($resultParam);
			
			$semaineEC=0;
			$tabSessionDate=array();
			$itab2=0;
			while ($tmpDate <= $dateFin)
			{
				$leJour = date('d', strtotime($tmpDate." + 0 month"));
				$jour = date('w', strtotime($tmpDate." + 0 month"));
				$mois = date('m', strtotime($tmpDate." + 0 month"));
				$semaine = date('W', strtotime($tmpDate." + 0 month"));
				
				$tabForm=array();
				$itab=0;
				$nbLigne=0;
				$nbSansHeures=0;
				$bTrouve=0;
				
				//CALCUL NEW 
				$taille=0;
				if($nbSession>0)
				{
					mysqli_data_seek($resultSessions,0);
					while($rowSession=mysqli_fetch_array($resultSessions))
					{
						if($rowSession['DateSession']==$tmpDate)
						{
							$taille++;
						}
					}
				}
				$tabResult[]= array();
				$nb=0;
				$iResultat=0;
				if($taille>0){
					while($nb+$nbSansHeures<$taille){
						$heure=5;
						$min=0;
						$nbLigne++;
						for($i=1;$i<=61;$i++){
							$heureFin="00:00:00";
							$trouve=0;
							$NbPreInscrit="";
							if($nbSession>0){
								mysqli_data_seek($resultSessions,0);
								while($rowSession=mysqli_fetch_array($resultSessions)){
									if($rowSession['DateSession']==$tmpDate){
										if($trouve==0){
											if($rowSession['Heure_Debut']==0){
												$bExiste=0;
												for($k=0;$k<=(sizeof($tabResult)-1);$k++)
												{
													if($tabResult[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
												}
												if($bExiste==0){
													$tabResult[$iResultat]=$rowSession['Id_SessionDate'];$iResultat++;
													$trouve=1;
													$heureFin="00:00:00";
													$nbSansHeures++;
													$i=54;
												}
											}
											else{
												if($rowSession['Heure_Debut']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){
													$bExiste=0;
													for($k=0;$k<=(sizeof($tabResult)-1);$k++)
													{
														if($tabResult[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
													}
													if($bExiste==0){
														$tabResult[$iResultat]=$rowSession['Id_SessionDate'];$iResultat++;
														$heureFin=$rowSession['Heure_Fin'];
														$trouve=1;
														$nb++;
														$h1=strtotime($rowSession['Heure_Fin']);
														$h2=strtotime($rowSession['Heure_Debut']);
														$val=intval(substr(gmdate("H:i",$h1-$h2),0,2))*4;
														if(substr(gmdate("H:i",$h1-$h2),3,2)=="15"){$val++;}
														elseif(substr(gmdate("H:i",$h1-$h2),3,2)=="30"){$val=$val+2;}
														elseif(substr(gmdate("H:i",$h1-$h2),3,2)=="45"){$val=$val+3;}
														if($val>1)
														{
															$i+=($val-1);
														}
													}
												}
											}
										}
									}
								}
							}
							
							if($min==0){$min=15;}
							elseif($min==15){$min=30;}
							elseif($min==30){$min=45;}
							else{$min=0;$heure++;}
							if($heureFin<>"00:00:00")
							{
								$heure=intval(substr($heureFin,0,2));
								$min=intval(substr($heureFin,3,2));
							}
						}
					}
					
					$total=$nbSansHeures+$nb;
					$nbLigne=$nbLigne-$nbSansHeures;
				}
				
				echo "<tr>\n";
				$rowspanSemaine="";
				if($semaineEC==0 || $semaine<>$semaineEC){
					if($jour<>0){$rowspanSemaine="rowspan='".(8-$jour)."'";}
					if($LangueAffichage=="FR"){
						echo "<td width='15px' class='EnTeteSemaine' ".$rowspanSemaine." align='center' valign='center' style='font-size:11px;border:1px solid #cccccc;'>S".$semaine."</td>\n";
					}
					else{
						echo "<td width='15px' class='EnTeteSemaine' ".$rowspanSemaine." align='center' valign='center' style='font-size:11px;border:1px solid #cccccc;'>W".$semaine."</td>\n";
					}
				}
				echo "<td width='15px' class='EnTeteSemaine' align='center' valign='center' style='font-size:15px;border:1px solid #cccccc'>".$joursem[$jour]."</td>\n";
				echo "<td width='15px' class='EnTeteSemaine' align='center' valign='center' style='font-size:15px;border:1px solid #cccccc;'>".$leJour."</td>\n";
				echo "<td width='92%' align='left' valign='center' style='font-size:15px;'>";
				echo "<table style='width:100%; margin:0; border-spacing:0;'>";
				if($nbSansHeures==0 && $nbLigne==0)
				{
					echo "<tr>\n";
						$heure=5;
						$min=0;
						for($i=1;$i<=61;$i++)
						{
							$colspan="";
							$couleur="#ffffff";
							$heureFin="00:00:00";
							$trouve=0;
							if($i>=29 && $i<=33){$couleur="#d6ecf2";}
							echo "<td height='30px' align='center' style='background-color:".$couleur.";border:1px solid #cccccc;' width='15px'></td>\n";
							if($min==0){$min=15;}
							elseif($min==15){$min=30;}
							elseif($min==30){$min=45;}
							else{$min=0;$heure++;}
						}
						echo "</tr>\n";
				}
				if($nbLigne>0)
				{
					for($j=1;$j<=$nbLigne;$j++)
					{
						echo "<tr>\n";
						$heure=5;
						$min=0;
						for($i=1;$i<=61;$i++)
						{
							$colspan="";
							$couleur="#ffffff";
							$heureFin="00:00:00";
							$trouve=0;
							$formation="";
							$onclick="";
							$val=1;
							$id="";
							$nbPlaceRestante="";
							if($i>=29 && $i<=33){$couleur="#d6ecf2";}
							if($nbSession>0)
							{
								mysqli_data_seek($resultSessions,0);
								while($rowSession=mysqli_fetch_array($resultSessions))
								{
									if($rowSession['DateSession']==$tmpDate)
									{
										if($rowSession['Heure_Debut']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00" && $trouve==0)
										{
											$bExiste=0;
											$NbPreInscrit=$rowSession['NbPreInscrit'];
											$couleurSession=$gris;
											//Vérifier si la formation n'a pas déjà commencée
											if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']==1)
											{
												//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
												$req="SELECT form_session_date.Id FROM form_session_date LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id ";
												$req.="WHERE form_session_date.DateSession<='".date('Y-m-d')."' AND form_session_date.Suppr=0 ";
												$req.="AND form_session.Suppr=0 AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 AND form_session.Id_GroupeSession=".$rowSession['Id_GroupeSession'];
												$resultDepasse=mysqli_query($bdd,$req);
												$nbDepasse=mysqli_num_rows($resultDepasse);
												if($nbDepasse>0){$bValide=0;}
											}
											else
											{
												//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
												$req="SELECT form_session_date.Id FROM form_session_date LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id ";
												$req.="WHERE form_session_date.DateSession<='".date('Y-m-d')."' AND form_session_date.Suppr=0 ";
												$req.="AND form_session.Suppr=0 AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 AND form_session_date.Id_Session=".$rowSession['Id'];
												$resultDepasse=mysqli_query($bdd,$req);
												$nbDepasse=mysqli_num_rows($resultDepasse);
											}
											//Places restantes
											$reqInscrit="SELECT Id FROM form_session_personne WHERE Validation_Inscription=1 AND Suppr=0 AND Id_Session=".$rowSession['Id'];
											$resultNbInscrit=mysqli_query($bdd,$reqInscrit);
											$nbInscrit=mysqli_num_rows($resultNbInscrit);
											if($rowSession['Nb_Stagiaire_Maxi']>0)
											{
												$nbPlaceRestante=$rowSession['Nb_Stagiaire_Maxi']-$nbInscrit;
												if($nbPlaceRestante<0){$nbPlaceRestante=0;}
											}
											
											//Date de début de la session 
											$reqSDate="SELECT DateSession FROM form_session_date WHERE Suppr=0 AND Id_Session=".$rowSession['Id']." ORDER BY DateSession ASC ";
											$resultSDate=mysqli_query($bdd,$reqSDate);
											$nbSDate=mysqli_num_rows($resultSDate);
											$DateDebutS="";
											if($nbSDate>0){
												$rowSDate=mysqli_fetch_array($resultSDate);
												$DateDebutS=AfficheDateJJ_MM_AAAA($rowSDate['DateSession']);
											}
											
											//Date de fin de la session 
											$reqSDate="SELECT DateSession FROM form_session_date WHERE Suppr=0 AND Id_Session=".$rowSession['Id']." ORDER BY DateSession DESC ";
											$resultSDate=mysqli_query($bdd,$reqSDate);
											$nbSDate=mysqli_num_rows($resultSDate);
											$DateFinS="";
											if($nbSDate>0){
												$rowSDate=mysqli_fetch_array($resultSDate);
												$DateFinS=AfficheDateJJ_MM_AAAA($rowSDate['DateSession']);
											}
											
											//Possible uniquement si jour non passé
											if($nbDepasse==0)
											{
												//Inscription disponible
												$reqBesoin="SELECT Id FROM form_besoin 
															WHERE Traite=0 
															AND Id_Formation=".$rowSession['Id_Formation']." 
															AND Suppr=0 AND Valide=1 
															AND Id_Prestation IN (
																SELECT Id_Prestation 
																FROM new_competences_personne_poste_prestation 
																WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
																AND Id_Personne=".$IdPersonneConnectee.") ";
												$resultBesoin=mysqli_query($bdd,$reqBesoin);
												$nbBesoin=mysqli_num_rows($resultBesoin);
												if($nbBesoin>0){$couleurSession=$jaune;}
											}
											
											//Stagiaires inscrits
											$reqStagInscrit="SELECT Id 
														FROM form_session_personne 
														WHERE Validation_Inscription IN (0,1) 
														AND Suppr=0 
														AND Id_Personne IN (
															".$listeRespPers.")
														AND Id_Session=".$rowSession['Id'];
											$resultStagInscrit=mysqli_query($bdd,$reqStagInscrit);
											$nbStagInscrit=mysqli_num_rows($resultStagInscrit);
											if($nbStagInscrit>0){$couleurSession=$vert;}
											
											for($k=0;$k<=(sizeof($tabForm)-1);$k++)
											{
												if($tabForm[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
											}
											$nbPartie=1;
											for($k=0;$k<=(sizeof($tabSessionDate)-1);$k++)
											{
												if($tabSessionDate[$k]==$rowSession['Id']){$nbPartie++;}
											}
											if($bExiste==0){$tabSessionDate[$itab2]=$rowSession['Id'];$itab2++;}
											if($bExiste==0)
											{
												$h1=strtotime($rowSession['Heure_Fin']);
												$h2=strtotime($rowSession['Heure_Debut']);
												$val=intval(substr(gmdate("H:i",$h1-$h2),0,2))*4;
												if(substr(gmdate("H:i",$h1-$h2),3,2)=="15"){$val++;}
												elseif(substr(gmdate("H:i",$h1-$h2),3,2)=="30"){$val=$val+2;}
												elseif(substr(gmdate("H:i",$h1-$h2),3,2)=="45"){$val=$val+3;}
												if($val>1)
												{
													$colspan="colspan='".$val."'";
													$i+=($val-1);
												}
												$heureFin=$rowSession['Heure_Fin'];
												$trouve=1;
												$tabForm[$itab]=$rowSession['Id_SessionDate'];
												$itab++;
												
												$Id_Langue=0;
												$organisme="";
												if($nbParam>0)
												{
													mysqli_data_seek($resultParam,0);
													while($rowParam=mysqli_fetch_array($resultParam))
													{
														if($rowParam['Id_Formation']==$rowSession['Id_Formation'])
														{
															$Id_Langue=$rowParam['Id_Langue'];
															if($rowParam['Organisme']<>""){$organisme=" (".stripslashes($rowParam['Organisme']).")";}
														}
													}
												}
												$Infos="";
												if($nbInfos>0)
												{
													mysqli_data_seek($resultInfos,0);
													while($rowInfo=mysqli_fetch_array($resultInfos))
													{
														if($rowInfo['Id_Formation']==$rowSession['Id_Formation'] && $rowInfo['Id_Langue']==$Id_Langue)
														{
															if($rowSession['Recyclage']==0){$Infos="<b>".stripslashes($rowInfo['Libelle']).$organisme."</b>";}
															else{$Infos="<b>".stripslashes($rowInfo['LibelleRecyclage']).$organisme."</b>";}
														}
													}
												}
												if($LangueAffichage=="FR"){$Lieu="<i>Lieu non défini</i>";}
												else{$Lieu="<i>Undefined location</i>";}
												if($rowSession['Lieu']){$Lieu="<i>".$rowSession['Lieu']."</i>";}
												$Heures=substr($rowSession['Heure_Debut'],0,5)." - ".substr($rowSession['Heure_Fin'],0,5);
												if($rowSession['PauseRepas']==1)
												{
													if($rowSession['Heure_Fin']>$rowSession['HeureDebutPause'] && $rowSession['Heure_Debut']<$rowSession['HeureFinPause'])
													{
														$Heures=substr($rowSession['Heure_Debut'],0,5)." - ".substr($rowSession['HeureDebutPause'],0,5)." | ".substr($rowSession['HeureFinPause'],0,5)." - ".substr($rowSession['Heure_Fin'],0,5);
													}
												}
												$id=$rowSession['Id'];
												$Partie="";
												if($LangueAffichage=="FR")
												{
													if($rowSession['Nb']>1){$Partie=" (Partie".$nbPartie.")";}
												}
												else
												{
													if($rowSession['Nb']>1){$Partie=" (Part".$nbPartie.")";}
												}
												$GroupeFormation="";
												if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']>0)
												{
													if($LangueAffichage=="FR"){$GroupeFormation="Groupe : ".$rowSession['Groupe']."<br>";}
													else{$GroupeFormation="Group : ".$rowSession['Groupe']."<br>";}
													$id="GR".$rowSession['Id_GroupeSession'];
												}
												$PlaceRestante="";
												if($LangueAffichage=="FR")
												{
													if($nbPlaceRestante>0){$PlaceRestante="Places restantes : ".$nbPlaceRestante;}
													else{$PlaceRestante="Places restantes : 0";}
												}
												else
												{
													if($nbPlaceRestante>0){$PlaceRestante="Remaining places: ".$nbPlaceRestante;}
													else{$PlaceRestante="Remaining places : 0";}
												}
												
												if($couleurSession==$vert){$onclick="onclick=\"ContenuSessionAllege('".$rowSession['Id']."')\"";}
												
												$besoin="";
												if($rowSession['Id_TypeFormation']<>"1"){
													if(DroitsFormationPlateforme($TableauIdPostesAF_RF) || $_SERVER['SERVER_NAME']=="192.168.20.3"){
														if($LangueAffichage=="FR")
														{
															$besoin="<a style='text-decoration:none;' href='javascript:OuvreFenetreBesoin(".$rowSession['Id_TypeFormation'].",".$rowSession['Id_Formation'].")'>
																        <img src='../../Images/B.png' width='15px' border='0' alt='Générer un besoin' title='Générer un besoin'>
															         </a>";
														}
														else
														{
															$besoin="<a style='text-decoration:none;' href='javascript:OuvreFenetreBesoin(".$rowSession['Id_TypeFormation'].",".$rowSession['Id_Formation'].")'>
																        <img src='../../Images/B.png' width='15px' border='0' alt='Generate a need' title='Generate a need'>
															         </a>";
														}
													}
												}
												//Si date non dépassée ET Session diffusée 
												if($rowSession['DateSession']>date('Y-m-d') && ($rowSession['MultiPlateforme']>0 || $rowSession['NbPrestation']>0)){
													if($LangueAffichage=="FR"){
														$besoin.="<a style='text-decoration:none;' href='javascript:InscrireSessionSite(\"".$rowSession['Id']."\")'>
            													          <img width='15px' src='../../Images/I.png' border='0' alt='Inscription' title='Inscription'></td>
            														  </a>";
													}
													else{
														$besoin.="<a style='text-decoration:none;' href='javascript:InscrireSessionSite(\"".$rowSession['Id']."\")'>
        															 <img width='15px' src='../../Images/I.png' border='0' alt='Registration' title='Registration'></td>
        														 </a>";
													}
												}
												$formNew="";
												if(date('Y-m-d',strtotime($rowSession['DateCreationForm']."+ 2 month"))>date('Y-m-d')){
													$formNew="<img width='30px' src='../../Images/New.png' border='0' alt='New' title='New'>";
												}
												
												$formation="
                                                    <table width='100%'>
                                                        <tr>
                                                            <td align='center' width='99%' ".$onclick.">".
												                $formNew.$GroupeFormation.$Infos.$Partie.
												                "<br/>
                                                                <font style='color:#000564;'> [".$DateDebutS." - ".$DateFinS."] </font>
                                                                <font style='color:#5159ff;'>".$Heures."</font>
                                                                <br/>".
												                $Lieu."
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <table width='100%'>
                                                                    <td valign='top' align='left'><b>".$PlaceRestante." (Préinscription : ".$NbPreInscrit.")</b></td>
                                                                    <td valign='top' align='right'><b>".$besoin."</b></td>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>";
											}
										}
									}
								}
							}
							if($formation<>""){$couleur=$couleurSession;}
							$taille=1;
							if($colspan<>""){$taille=$val*1;}
							if($etat=="disponible" && $couleur<>$jaune && $formation<>"")
							{
								$couleur="#ffffff";
								$formation="";
							}
							elseif($etat=="indisponible" && $couleur<>$gris && $formation<>"")
							{
								$couleur="#ffffff";
								$formation="";
							}
							elseif($etat=="stagiaires" && $couleur<>$vert && $formation<>"")
							{
								$couleur="#ffffff";
								$formation="";
							}
							$onmouse="";
							if($formation<>"")
							{
								$onmouse="onMouseOver=\"Surbrillance('".$id."','Over','".$couleur."');\" onMouseOut=\"Surbrillance('".$id."','Out','".$couleur."');\" ";
							}
							echo "<td height='30px' ".$colspan." width='".$taille."%' align='center' style='background-color:".$couleur.";word-break:break-all;border:1px solid #cccccc;' class=\"td_".$id."\" ".$onmouse." >".$formation."</td>\n";
							if($min==0){$min=15;}
							elseif($min==15){$min=30;}
							elseif($min==30){$min=45;}
							else{$min=0;$heure++;}
							if($heureFin<>"00:00:00")
							{
								$heure=intval(substr($heureFin,0,2));
								$min=intval(substr($heureFin,3,2));
							}
						}
						echo "</tr>\n";
					}
				}
				if($nbSansHeures>0)
				{
					//CAS DES FORMATIONS SANS HEURES DE DEBUT ET DE FIN
					if($nbSession>0)
					{
						$id="";
						$couleur="#eff7ff";
						$nbPlaceRestante="";
						$onclick="";
						mysqli_data_seek($resultSessions,0);
						while($rowSession=mysqli_fetch_array($resultSessions))
						{
							if($rowSession['DateSession']==$tmpDate)
							{
								if($rowSession['Heure_Debut']=="00:00:00")
								{
									$bExiste=0;
									$NbPreInscrit=$rowSession['NbPreInscrit'];
									$couleurSession=$gris;
									$onclick="";
									//Vérifier si la formation n'a pas déjà commencée
									if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']==1)
									{
										//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
										$req="SELECT form_session_date.Id FROM form_session_date LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id ";
										$req.="WHERE form_session_date.DateSession<='".date('Y-m-d')."' AND form_session_date.Suppr=0 ";
										$req.="AND form_session.Suppr=0 AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 AND form_session.Id_GroupeSession=".$rowSession['Id_GroupeSession'];
										$resultDepasse=mysqli_query($bdd,$req);
										$nbDepasse=mysqli_num_rows($resultDepasse);
									}
									else
									{
										//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
										$req="SELECT form_session_date.Id FROM form_session_date LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id ";
										$req.="WHERE form_session_date.DateSession<='".date('Y-m-d')."' AND form_session_date.Suppr=0 ";
										$req.="AND form_session.Suppr=0 AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 AND form_session_date.Id_Session=".$rowSession['Id'];
										$resultDepasse=mysqli_query($bdd,$req);
										$nbDepasse=mysqli_num_rows($resultDepasse);
									}
									//Places restantes
									$reqInscrit="SELECT Id FROM form_session_personne WHERE Validation_Inscription=1 AND Suppr=0 AND Id_Session=".$rowSession['Id'];
									$resultNbInscrit=mysqli_query($bdd,$reqInscrit);
									$nbInscrit=mysqli_num_rows($resultNbInscrit);
									if($rowSession['Nb_Stagiaire_Maxi']>0)
									{
										$nbPlaceRestante=$rowSession['Nb_Stagiaire_Maxi']-$nbInscrit;
										if($nbPlaceRestante<0){$nbPlaceRestante=0;}
									}
									
									//Date de début de la session 
									$reqSDate="SELECT DateSession FROM form_session_date WHERE Suppr=0 AND Id_Session=".$rowSession['Id']." ORDER BY DateSession ASC ";
									$resultSDate=mysqli_query($bdd,$reqSDate);
									$nbSDate=mysqli_num_rows($resultSDate);
									$DateDebutS="";
									if($nbSDate>0){
										$rowSDate=mysqli_fetch_array($resultSDate);
										$DateDebutS=AfficheDateJJ_MM_AAAA($rowSDate['DateSession']);
									}
									
									//Date de fin de la session 
									$reqSDate="SELECT DateSession FROM form_session_date WHERE Suppr=0 AND Id_Session=".$rowSession['Id']." ORDER BY DateSession DESC ";
									$resultSDate=mysqli_query($bdd,$reqSDate);
									$nbSDate=mysqli_num_rows($resultSDate);
									$DateFinS="";
									if($nbSDate>0){
										$rowSDate=mysqli_fetch_array($resultSDate);
										$DateFinS=AfficheDateJJ_MM_AAAA($rowSDate['DateSession']);
									}
									if($nbDepasse==0)
									{
										//session disponible
										$reqBesoin="SELECT Id 
													FROM form_besoin 
													WHERE Id_Formation=".$rowSession['Id_Formation']." 
													AND Suppr=0 
													AND Valide=0 
													AND Id_Prestation IN (
														SELECT Id_Prestation 
														FROM new_competences_personne_poste_prestation 
														WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
														AND Id_Personne=".$IdPersonneConnectee.") ";
										$resultBesoin=mysqli_query($bdd,$reqBesoin);
										$nbBesoin=mysqli_num_rows($resultBesoin);
										if($nbBesoin>0){$couleurSession=$jaune;}
									}
									//Stagiaires inscrits
									$reqStagInscrit="SELECT Id 
												FROM form_session_personne 
												WHERE Validation_Inscription=1 
												AND Suppr=0 
												AND Id_Personne IN (
													SELECT Id_Personne 
														FROM new_competences_personne_prestation 
														WHERE Date_Fin>='".date('Y-m-d')."' 
														AND Id_Prestation IN (
															SELECT Id_Prestation 
															FROM new_competences_personne_poste_prestation 
															WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
															AND Id_Personne=".$IdPersonneConnectee."
															)
													)
												AND Id_Session=".$rowSession['Id'];
									$resultStagInscrit=mysqli_query($bdd,$reqStagInscrit);
									$nbStagInscrit=mysqli_num_rows($resultStagInscrit);
									if($nbStagInscrit>0){$couleurSession=$vert;}
									
									for($k=0;$k<=(sizeof($tabForm)-1);$k++)
									{
										if($tabForm[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
									}
									$nbPartie=1;
									for($k=0;$k<=(sizeof($tabSessionDate)-1);$k++)
									{
										if($tabSessionDate[$k]==$rowSession['Id']){$nbPartie++;}
									}
									$tabSessionDate[$itab2]=$rowSession['Id'];
									if($bExiste==0)
									{
										$Id_Langue=0;
										if($nbParam>0)
										{
											mysqli_data_seek($resultParam,0);
											while($rowParam=mysqli_fetch_array($resultParam))
											{
												if($rowParam['Id_Formation']==$rowSession['Id_Formation']){$Id_Langue=$rowParam['Id_Langue'];}
											}
										}
										$organisme="";
										if($nbParam>0)
										{
											mysqli_data_seek($resultParam,0);
											while($rowParam=mysqli_fetch_array($resultParam))
											{
												if($rowParam['Id_Formation']==$rowSession['Id_Formation'])
												{
													$Id_Langue=$rowParam['Id_Langue'];
													if($rowParam['Organisme']<>""){$organisme=" (".stripslashes($rowParam['Organisme']).")";}
												}
											}
										}
										
										$Infos="";
										if($nbInfos>0)
										{
											mysqli_data_seek($resultInfos,0);
											while($rowInfo=mysqli_fetch_array($resultInfos))
											{
												if($rowInfo['Id_Formation']==$rowSession['Id_Formation'] && $rowInfo['Id_Langue']==$Id_Langue)
												{
													if($rowSession['Recyclage']==0){$Infos="<b>".stripslashes($rowInfo['Libelle']).$organisme."</b>";}
													else{$Infos="<b>".stripslashes($rowInfo['LibelleRecyclage']).$organisme."</b>";}
												}
											}
										}
										if($LangueAffichage=="FR"){$Lieu="<i>Lieu non défini</i>";}
										else{$Lieu="<i>Undefined location</i>";}
										if($rowSession['Lieu']){$Lieu="<i>".$rowSession['Lieu']."</i>";}
										$Partie="";
										if($LangueAffichage=="FR")
										{
											if($rowSession['Nb']>1){$Partie=" (Partie".$nbPartie.")";}
										}
										else
										{
											if($rowSession['Nb']>1){$Partie=" (Part".$nbPartie.")";}
										}
										$GroupeFormation="";
										$id=$rowSession['Id'];
										if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']>0)
										{
											if($LangueAffichage=="FR"){$GroupeFormation="Groupe : ".$rowSession['Groupe']."<br>";}
											else{$GroupeFormation="Group : ".$rowSession['Groupe']."<br>";}
											$id="GR".$rowSession['Id_GroupeSession'];
										}
										$PlaceRestante="";
										if($LangueAffichage=="FR")
										{
											if($nbPlaceRestante>0){$PlaceRestante="Places restantes : ".$nbPlaceRestante;}
											else{$PlaceRestante="Places restantes : 0";}
										}
										else
										{
											if($nbPlaceRestante>0){$PlaceRestante="Remaining places : ".$nbPlaceRestante;}
											else{$PlaceRestante="Remaining places : 0";}
										}
										
										if($couleurSession==$vert){$onclick="onclick=\"ContenuSessionAllege('".$rowSession['Id']."')\"";}
										$besoin="";
										if($rowSession['Id_TypeFormation'] <> $IdTypeFormationEprouvette){
											if(DroitsFormationPlateforme($TableauIdPostesAF_RF) || $_SERVER['SERVER_NAME']=="192.168.20.3"){
												if($LangueAffichage=="FR")
												{
													$besoin="<a style='text-decoration:none;' href='javascript:OuvreFenetreBesoin(".$rowSession['Id_TypeFormation'].",".$rowSession['Id_Formation'].")'>
																 <img src='../../Images/B.png' width='15px' border='0' alt='Générer un besoin' title='Générer un besoin'>
															 </a>";
												}
												else
												{
													$besoin="<a style='text-decoration:none;' href='javascript:OuvreFenetreBesoin(".$rowSession['Id_TypeFormation'].",".$rowSession['Id_Formation'].")'>
																 <img src='../../Images/B.png' width='15px' border='0' alt='Generate a need' title='Generate a need'>
															 </a>";
												}
											}
										}
										//Si date non dépassée ET Session diffusée 
										if($rowSession['DateSession']>date('Y-m-d') && ($rowSession['MultiPlateforme']>0 || $rowSession['NbPrestation']>0)){
											if($LangueAffichage=="FR"){
												$besoin.="<a style='text-decoration:none;' href='javascript:InscrireSessionSite(\"".$rowSession['Id']."\")'>
															  <img width='15px' src='../../Images/I.png' border='0' alt='Inscription' title='Inscription'></td>
														  </a>";
											}
											else{
												$besoin.="<a style='text-decoration:none;' href='javascript:InscrireSessionSite(\"".$rowSession['Id']."\")'>
															  <img width='15px' src='../../Images/I.png' border='0' alt='Registration' title='Registration'></td>
														  </a>";
											}
										}	
										$formation="<table width='100%'>";
										$formation.="<tr>";
										$formNew="";
										if(date('Y-m-d',strtotime($rowSession['DateCreationForm']."+ 2 month"))>date('Y-m-d')){
											$formNew="<img width='30px' src='../../Images/New.png' border='0' alt='New' title='New'>";
										}
										if($LangueAffichage=="FR"){$formation.="<td width='99%' align='center' ".$onclick.">".$formNew.$GroupeFormation.$Infos.$Partie."<br><font style='color:#000564;'> [".$DateDebutS." - ".$DateFinS."] </font><font style='color:#5159ff;'>Horaires non définis</font><br>".$Lieu."</td>";}
										else{$formation.="<td width='99%' align='center' ".$onclick.">".$formNew.$GroupeFormation.$Infos.$Partie."<br><font style='color:#000564;'> [".$DateDebutS." - ".$DateFinS."] </font><font style='color:#5159ff;'>Unresolved hours</font><br>".$Lieu."</td>";}
										$formation.="
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table width='100%'>
                                                        <td valign='top' align='left'><b>".$PlaceRestante." (Préinscription : ".$NbPreInscrit.")</b></td>
                                                        <td valign='top' align='right'><b>".$besoin."</b></td>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>";
										
										if($formation<>"")
										{
											if($formationR<>"")
											{
												if(stripos($GroupeFormation.$Infos.$Partie,$formationR)===false){$formation="";}
												else{$couleur=$couleurSession;}
											}
											else{$couleur=$couleurSession;}
										}
										else{$couleur=$couleurSession;}
										
										if($etat=="disponible" && $couleur<>$jaune && $formation<>"")
										{
											$couleur="#ffffff";
											$formation="";
										}
										elseif($etat=="indisponible" && $couleur<>$gris && $formation<>"")
										{
											$couleur="#ffffff";
											$formation="";
										}
										elseif($etat=="stagiaires" && $couleur<>$vert && $formation<>"")
										{
											$couleur="#ffffff";
											$formation="";
										}
										echo "<tr>\n";
										echo "<td height='60px' colspan='53' align='center' style='background-color:".$couleur.";border:1px solid #cccccc;' class=\"td_".$id."\" onMouseOver=\"Surbrillance('".$id."','Over','".$couleur."');\" onMouseOut=\"Surbrillance('".$id."','Out','".$couleur."');\" width='1%'>".$formation."</td>\n";
										echo "</tr>\n";
									}
								}
							}
						}
					}
				}
				echo "</table>";
				echo "</td>\n";
				$semaineEC=date('W', strtotime($tmpDate." + 0 month"));
				echo "</tr>\n";
				if($jour==0)
				{
					echo "<tr>";
					echo "<td height='10px;'></td>";
					echo "</tr>";
				}
				//Jour suivant
				$tmpDate = date("Y-m-d", strtotime($tmpDate." + 1 day"));
			}
			?>
		</table>
	</td></tr>
	<tr>
		<td>
			<table>
				<tr>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Légende";}else{echo "Legend";}?>  : </td>
					<td class="Libelle" style="background-color:<?php echo $gris;?>;"><?php if($LangueAffichage=="FR"){echo "Pas de besoin / session passée";}else{echo "No need / past session";}?></td>
					<td class="Libelle" style="background-color:<?php echo $jaune;?>;"><?php if($LangueAffichage=="FR"){echo "Sessions disponibles";}else{echo "Sessions available";}?></td>
					<td class="Libelle" style="background-color:<?php echo $vert;?>;"><?php if($LangueAffichage=="FR"){echo "Stagiaires pré-inscrits / inscrits";}else{echo "Pre-registered / registered trainees";}?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
</table>
</form>
</body>
</html>