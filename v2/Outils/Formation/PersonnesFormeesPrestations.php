<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td align="center">
		<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : 
					<?php 
					$requetePlateforme="SELECT DISTINCT Id_Plateforme AS Id, 
					(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
					FROM new_competences_personne_poste_plateforme 
					WHERE Id_Poste 
						IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
					AND Id_Personne=".$IdPersonneConnectee." 
					UNION
					SELECT DISTINCT 
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id, 
						(SELECT (SELECT Libelle FROM new_competences_plateforme 
						WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Libelle 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Poste 
						IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
					AND Id_Personne=".$IdPersonneConnectee." 
					ORDER BY Libelle";
					$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
					echo "<select name='Id_Plateforme' id='Id_Plateforme' OnChange='submit()' >";
					
					$Id_Plateforme=$_SESSION['FiltrePersFormeesPresta_Plateforme'];
					if($_POST)
					{
						if(isset($_POST['Id_Plateforme']))
						{
							$Id_Plateforme=$_POST['Id_Plateforme'];
						}
					}

					while($LigPlateforme=mysqli_fetch_array($resultPlateforme)){
						$selected="";
						if($_POST){
							if($_POST['Id_Plateforme']==$LigPlateforme['Id']){
								$selected="selected";
								$Id_Plateforme=$LigPlateforme['Id'];
							}
						}
						else{
							if($Id_Plateforme==0){
								$Id_Plateforme=$LigPlateforme['Id'];
								$selected="selected";
							}
						}
						echo "<option value='".$LigPlateforme['Id']."' ".$selected.">".$LigPlateforme['Libelle']."</option>";
					}
					$_SESSION['FiltrePersFormeesPresta_Plateforme']=$Id_Plateforme;
					
					echo "</select>";
					?>
				</td>
				<td class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation ";}else{echo "Training ";}?></td>
				<td width="15%">
					<?php
						
					?>
					<select name="formationR" id="formationR" style="width:400px" onchange="submit()">
						<option value="0_0"></option>
						<?php
						$formation=0;
						if($_POST)
						{
							if(isset($_POST['formationR']))
							{
								$formation=$_POST['formationR'];
							}
						}
						
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
											WHERE Id_Plateforme=".$Id_Plateforme."
											AND Id_Formation=form_formation.Id
											AND Suppr=0 
											LIMIT 1)
										AND Suppr=0) AS Libelle
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
											WHERE Id_Plateforme=".$Id_Plateforme."
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
							if($formation<>"")
							{
								if($formation==$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']){$selected="selected";}
							}
							echo "<option value='".$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']."' ".$selected.">";
							echo stripslashes($rowForm['Formation']);
							echo "</option>\n";
						}
						?>
					</select>
				</td>
				<td width="15%">
					<span><?php if($LangueAffichage=="FR"){echo "Au moins une qualification de la formation à jour";}else{echo "At least one up-to-date training qualification";}?></span><br>
					<span style='color:#10aa4e'><?php if($LangueAffichage=="FR"){echo "Inscrit en formation";}else{echo "Registered in training";}?></span><br>
					<span style='color:#0151b9'><?php if($LangueAffichage=="FR"){echo "Besoin validé";}else{echo "Need validated";}?></span>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
		</table>
	</td>
</tr>
<tr><td height="4"></td>
<?php 

$tabQual=explode("_",$formation);
$qualification="";
if($formation<>"0_0" && $formation<>""){
	if($tabQual[1]==0){
		$req="SELECT DISTINCT Id_Qualification
			FROM form_formation_qualification
			WHERE Id_Formation=".$tabQual[0]."
			AND Suppr=0 ";
		$resultFormE=mysqli_query($bdd,$req);
		while($rowFormE=mysqli_fetch_array($resultFormE))
		{
			if($qualification<>""){$qualification.=",";}
			$qualification.=$rowFormE['Id_Qualification'];
		}
	}
	else{
		$req="SELECT DISTINCT Id_Qualification
			FROM form_formation_qualification
			WHERE Id_Formation IN 
			(SELECT Id_Formation 
			FROM form_formationequivalente_formationplateforme 
			WHERE Suppr=0 
			AND Id_FormationEquivalente=".$tabQual[0].") 
			AND Suppr=0 ";
		$resultFormE=mysqli_query($bdd,$req);
		while($rowFormE=mysqli_fetch_array($resultFormE))
		{
			if($qualification<>""){$qualification.=",";}
			$qualification.=$rowFormE['Id_Qualification'];
		}
			
	}
}

if($formation<>"0_0" && $formation<>""){
?>
<tr >
	<td align="center" colspan="10" width="3%">
	&nbsp;<a style="text-decoration:none;" href="javascript:Excel_PersonnesFormeesPrestations('<?php echo $qualification;?>');">
		<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
	</a>&nbsp;
	</td>
</tr>
<tr>
	<td align="center">
		<table style="width:90%; border-spacing:0; align:center;" class="GeneralInfo">
			<tr bgcolor="#2c8bb4">
				<td class="EnTeteTableauCompetences" rowspan="2" style="color:#ffffff;border-bottom:1px dottom black;" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";} ?></td>
				<?php 
					$semaine = date('W', strtotime(date('Y-m-d')." + 0 month"));
					if(date("N")==1){
						$lundi=date("Y-m-d");
					}
					else{
						$lundi=date("Y-m-d",strtotime("last Monday"));
					}
					$dimanche=date("Y-m-d",strtotime($lundi." +6 day"));
					
				?>
				<td class="EnTeteTableauCompetences" colspan="5" style="color:#ffffff;border-bottom:1px dottom black;" width="60%">S<?php echo $semaine; ?></td>
				
			</tr>
			<tr bgcolor="#2c8bb4">
				<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="15%">J</td>
				<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="15%">S</td>
				<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="15%">N</td>
				<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="15%">VSD</td>
				<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="15%">ABS</td>
			</tr>
				<?php 
				
				if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
					$rqPrestation="SELECT Id AS Id_Prestation, 
						Libelle,
						0 AS Id_Pole,
						'' AS Pole
						FROM new_competences_prestation 
						WHERE Id NOT IN (
							SELECT Id_Prestation
							FROM new_competences_pole
							WHERE Actif=0
						)
						AND new_competences_prestation.Active=0
						AND Id_Plateforme IN (
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$IdPersonneConnectee."
							AND Id_Poste IN (".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableRH.")
						)
						
						UNION
						
						SELECT Id_Prestation,
						new_competences_prestation.Libelle,
						new_competences_pole.Id AS Id_Pole,
						CONCAT(' - ',new_competences_pole.Libelle) AS Pole
						FROM new_competences_pole
						INNER JOIN new_competences_prestation
						ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						AND new_competences_pole.Actif=0
						AND new_competences_prestation.Active=0
						AND new_competences_prestation.Id_Plateforme IN (
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$IdPersonneConnectee."
							AND Id_Poste IN (".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableRH.")
						)
						ORDER BY Libelle, Pole";
				}
				else{
					$rqPrestation="SELECT Id AS Id_Prestation, 
						Id_Plateforme,
						Libelle,
						0 AS Id_Pole,
						'' AS Pole
						FROM new_competences_prestation 
						WHERE Id NOT IN (
							SELECT Id_Prestation
							FROM new_competences_pole 
							WHERE Actif=0   
						)
						AND (SELECT COUNT(Id)
							FROM new_competences_personne_poste_prestation
							WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
							AND Id_Personne=".$IdPersonneConnectee." 
							AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id)>0
						AND new_competences_prestation.Active=0
						AND Active=0
						
						UNION
						
						SELECT Id_Prestation,
						new_competences_prestation.Id_Plateforme,
						new_competences_prestation.Libelle,
						new_competences_pole.Id AS Id_Pole,
						CONCAT(' - ',new_competences_pole.Libelle) AS Pole
						FROM new_competences_pole
						INNER JOIN new_competences_prestation
						ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE (SELECT COUNT(Id)
							FROM new_competences_personne_poste_prestation
							WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
							AND Id_Personne=".$IdPersonneConnectee." 
							AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
							AND new_competences_personne_poste_prestation.Id_Pole=new_competences_pole.Id)>0
						AND new_competences_pole.Actif=0
						AND new_competences_prestation.Active=0
						AND Active=0
						AND Actif=0
						ORDER BY Libelle, Pole";
				}
				$resultPrestation=mysqli_query($bdd,$rqPrestation);
				$NbPresta=mysqli_num_rows($resultPrestation);
				
				$couleur="bgcolor='#ffffff'";
				if($NbPresta>0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						
						
						$reqPersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
						CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
						FROM new_rh_etatcivil
						LEFT JOIN rh_personne_mouvement 
						ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
						WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
						AND rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.DateDebut<='".$dimanche."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$lundi."')
						AND rh_personne_mouvement.EtatValidation=1 
						AND rh_personne_mouvement.Id_Prestation=".$row['Id_Prestation']."
						AND rh_personne_mouvement.Id_Pole=".$row['Id_Pole']." 
						";
						$resultPersonneTotal=mysqli_query($bdd,$reqPersonne);
						$NbPersonneTotal=mysqli_num_rows($resultPersonneTotal);
						
						$nbJTotal=0;
						$nbSTotal=0;
						$nbNTotal=0;
						$nbVSDTotal=0;
						
						if($NbPersonneTotal>0){
							while($rowPers=mysqli_fetch_array($resultPersonneTotal))
							{
								$leLJour=$lundi;
								$nbJ=0;
								$nbS=0;
								$nbN=0;
								$nbVSD=0;
								$nbAbs=0;
								while($leLJour<=$dimanche){
									$Couleur=TravailCeJourDeSemaine($leLJour,$rowPers['Id']);
									if ($Couleur <> ""){
										$vacation=EstEnVacationCeJour($rowPers['Id'],$leLJour);
										if($vacation==1 || $vacation==5 || $vacation==8 || $vacation==9 || $vacation==11){
											$nbJTotal++;
										}
										elseif($vacation==2 || $vacation==10){
											$nbSTotal++;
										}
										elseif($vacation==4){
											$nbNTotal++;
										}
										elseif($vacation==3){
											$nbVSDTotal++;
										}
									}
									$leLJour=date("Y-m-d",strtotime($leLJour." +1 day"));
								}
							}
						}
						
						$styleJ="";
						$styleS="";
						$styleN="";
						$styleVSD="";
						
						//PERSONNES FORMEES
						$reqPersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
						CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
						FROM new_rh_etatcivil
						LEFT JOIN rh_personne_mouvement 
						ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
						WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
						AND rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.DateDebut<='".$dimanche."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$lundi."')
						AND rh_personne_mouvement.EtatValidation=1 
						AND rh_personne_mouvement.Id_Prestation=".$row['Id_Prestation']."
						AND rh_personne_mouvement.Id_Pole=".$row['Id_Pole']." 
						AND ";
						if($qualification<>""){
							$reqPersonne.="new_rh_etatcivil.Id IN (
								SELECT Id_Personne
								FROM new_competences_relation 
								WHERE Id_Qualification_Parrainage IN (".$qualification.")
								AND new_competences_relation.Date_Debut<='".$dimanche."'
								AND (new_competences_relation.Date_Fin>='".$lundi."' OR (new_competences_relation.Date_Fin<='0001-01-01' AND Sans_Fin='Oui') )
								AND Evaluation IN ('L','Q','S','T','V','X')
								AND Suppr=0)
								";
						}
						else{
							$reqPersonne.="new_rh_etatcivil.Id IN
									(SELECT
										form_besoin.Id_Personne
									FROM
										form_besoin
									WHERE
										form_besoin.Suppr=0
										AND form_besoin.Valide=1
										AND form_besoin.Traite=4
										AND form_besoin.Id IN
										(
										SELECT
											Id_Besoin
										FROM
											form_session_personne
										WHERE
											form_session_personne.Id NOT IN 
												(
												SELECT
													Id_Session_Personne
												FROM
													form_session_personne_qualification
												WHERE
													Suppr=0	
												)
											AND Suppr=0
											AND form_session_personne.Validation_Inscription=1
											AND form_session_personne.Presence=1
										)
									)
								";
						}
						$resultPersonne=mysqli_query($bdd,$reqPersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$PersonneJ="";
						$PersonneS="";
						$PersonneN="";
						$PersonneVSD="";
						$PersonneABS="";

						if($NbPersonne>0){
							while($rowPers=mysqli_fetch_array($resultPersonne))
							{
								
								$leLJour=$lundi;
								$nbJ=0;
								$nbS=0;
								$nbN=0;
								$nbVSD=0;
								$nbAbs=0;
								while($leLJour<=$dimanche){
									$vacation=EstEnVacationCeJour($rowPers['Id'],$leLJour);
									if($vacation==1 || $vacation==5 || $vacation==8 || $vacation==9 || $vacation==11){
										$nbJ++;
									}
									elseif($vacation==2 || $vacation==10){
										$nbS++;
									}
									elseif($vacation==4){
										$nbN++;
									}
									elseif($vacation==3){
										$nbVSD++;
									}
									elseif($vacation==0){
										$nbAbs++;
									}
									$leLJour=date("Y-m-d",strtotime($leLJour." +1 day"));
								}
								if($nbJ>0){
									if($PersonneJ<>""){$PersonneJ.="<br>";}
									$PersonneJ.=stripslashes($rowPers['Personne']);
								}
								if($nbS>0){
									if($PersonneS<>""){$PersonneS.="<br>";}
									$PersonneS.=stripslashes($rowPers['Personne']);
								}
								if($nbN>0){
									if($PersonneN<>""){$PersonneN.="<br>";}
									$PersonneN.=stripslashes($rowPers['Personne']);
								}
								if($nbVSD>0){
									if($PersonneVSD<>""){$PersonneVSD.="<br>";}
									$PersonneVSD.=stripslashes($rowPers['Personne']);
								}
								if($nbAbs==7){
									if($PersonneABS<>""){$PersonneABS.="<br>";}
									$PersonneABS.=stripslashes($rowPers['Personne']);
								}
							}
						}
						
						//PERSONNES INSCRITES
						$reqPersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
						CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
						FROM new_rh_etatcivil
						LEFT JOIN rh_personne_mouvement 
						ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
						WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
						AND rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.DateDebut<='".$dimanche."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$lundi."')
						AND rh_personne_mouvement.EtatValidation=1 
						AND rh_personne_mouvement.Id_Prestation=".$row['Id_Prestation']."
						AND rh_personne_mouvement.Id_Pole=".$row['Id_Pole']." 
						AND
						";
						if($qualification<>""){
							$reqPersonne.="new_rh_etatcivil.Id IN (
								SELECT Id_Personne
								FROM new_competences_relation 
								WHERE Id_Qualification_Parrainage IN (".$qualification.")
								AND Evaluation IN ('Bi')
								AND Suppr=0)
								";
						}
						else{
							$reqPersonne.="new_rh_etatcivil.Id IN
									(SELECT
										form_besoin.Id_Personne
									FROM
										form_besoin
									WHERE
										form_besoin.Suppr=0
										AND form_besoin.Valide=1
										AND form_besoin.Traite IN (1,2)
										AND form_besoin.Id IN
										(
										SELECT
											Id_Besoin
										FROM
											form_session_personne
										WHERE
											form_session_personne.Id NOT IN 
												(
												SELECT
													Id_Session_Personne
												FROM
													form_session_personne_qualification
												WHERE
													Suppr=0	
												)
											AND Suppr=0
										)
									)
							";
						}
						
						$resultPersonne=mysqli_query($bdd,$reqPersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$PersonneJBi="";
						$PersonneSBi="";
						$PersonneNBi="";
						$PersonneVSDBi="";
						$PersonneABSBi="";

						if($NbPersonne>0){
							while($rowPers=mysqli_fetch_array($resultPersonne))
							{
								
								$leLJour=$lundi;
								$nbJBi=0;
								$nbSBi=0;
								$nbNBi=0;
								$nbVSDBi=0;
								$nbAbsBi=0;
								while($leLJour<=$dimanche){
									$vacation=EstEnVacationCeJour($rowPers['Id'],$leLJour);
									if($vacation==1 || $vacation==5 || $vacation==8 || $vacation==9 || $vacation==11){
										$nbJBi++;
									}
									elseif($vacation==2 || $vacation==10){
										$nbSBi++;
									}
									elseif($vacation==4){
										$nbNBi++;
									}
									elseif($vacation==3){
										$nbVSDBi++;
									}
									elseif($vacation==0){
										$nbAbsBi++;
									}
									$leLJour=date("Y-m-d",strtotime($leLJour." +1 day"));
								}
								if($nbJ>0){
									if($PersonneJBi<>""){$PersonneJBi.="<br>";}
									$PersonneJBi.=stripslashes($rowPers['Personne']);
								}
								if($nbS>0){
									if($PersonneSBi<>""){$PersonneSBi.="<br>";}
									$PersonneSBi.=stripslashes($rowPers['Personne']);
								}
								if($nbN>0){
									if($PersonneNBi<>""){$PersonneNBi.="<br>";}
									$PersonneNBi.=stripslashes($rowPers['Personne']);
								}
								if($nbVSD>0){
									if($PersonneVSDBi<>""){$PersonneVSDBi.="<br>";}
									$PersonneVSDBi.=stripslashes($rowPers['Personne']);
								}
								if($nbAbs==7){
									if($PersonneABSBi<>""){$PersonneABSBi.="<br>";}
									$PersonneABSBi.=stripslashes($rowPers['Personne']);
								}
							}
						}
						
						//PERSONNES AVEC UN B
						$reqPersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
						CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
						FROM new_rh_etatcivil
						LEFT JOIN rh_personne_mouvement 
						ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
						WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
						AND rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.DateDebut<='".$dimanche."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$lundi."')
						AND rh_personne_mouvement.EtatValidation=1 
						AND rh_personne_mouvement.Id_Prestation=".$row['Id_Prestation']."
						AND rh_personne_mouvement.Id_Pole=".$row['Id_Pole']." 
						AND 
						";
						
						if($qualification<>""){
							$reqPersonne.="new_rh_etatcivil.Id IN (
								SELECT Id_Personne
								FROM new_competences_relation 
								WHERE Id_Qualification_Parrainage IN (".$qualification.")
								AND Evaluation IN ('B')
								AND Suppr=0)
								";
						}
						else{
							$reqPersonne.="new_rh_etatcivil.Id IN
									(SELECT
										form_besoin.Id_Personne
									FROM
										form_besoin
									WHERE
										form_besoin.Suppr=0
										AND form_besoin.Valide=1
										AND form_besoin.Traite IN (0)
										AND form_besoin.Id IN
										(
										SELECT
											Id_Besoin
										FROM
											form_session_personne
										WHERE
											form_session_personne.Id NOT IN 
												(
												SELECT
													Id_Session_Personne
												FROM
													form_session_personne_qualification
												WHERE
													Suppr=0	
												)
											AND Suppr=0
										)
									)
							";
						}
						
						$resultPersonne=mysqli_query($bdd,$reqPersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$PersonneJB="";
						$PersonneSB="";
						$PersonneNB="";
						$PersonneVSDB="";
						$PersonneABSB="";

						if($NbPersonne>0){
							while($rowPers=mysqli_fetch_array($resultPersonne))
							{
								
								$leLJour=$lundi;
								$nbJB=0;
								$nbSB=0;
								$nbNB=0;
								$nbVSDB=0;
								$nbAbsB=0;
								while($leLJour<=$dimanche){
									$vacation=EstEnVacationCeJour($rowPers['Id'],$leLJour);
									if($vacation==1 || $vacation==5 || $vacation==8 || $vacation==9 || $vacation==11){
										$nbJB++;
									}
									elseif($vacation==2 || $vacation==10){
										$nbSB++;
									}
									elseif($vacation==4){
										$nbNB++;
									}
									elseif($vacation==3){
										$nbVSDB++;
									}
									elseif($vacation==0){
										$nbAbsB++;
									}
									$leLJour=date("Y-m-d",strtotime($leLJour." +1 day"));
								}
								if($nbJ>0){
									if($PersonneJB<>""){$PersonneJB.="<br>";}
									$PersonneJB.=stripslashes($rowPers['Personne']);
								}
								if($nbS>0){
									if($PersonneSB<>""){$PersonneSB.="<br>";}
									$PersonneSB.=stripslashes($rowPers['Personne']);
								}
								if($nbN>0){
									if($PersonneNB<>""){$PersonneNB.="<br>";}
									$PersonneNB.=stripslashes($rowPers['Personne']);
								}
								if($nbVSD>0){
									if($PersonneVSDB<>""){$PersonneVSDB.="<br>";}
									$PersonneVSDB.=stripslashes($rowPers['Personne']);
								}
								if($nbAbs==7){
									if($PersonneABSB<>""){$PersonneABSB.="<br>";}
									$PersonneABSB.=stripslashes($rowPers['Personne']);
								}
							}
						}
						
						//RESULTAT
						if($NbPersonneTotal>0){
							
							if($couleur=="bgcolor='#ffffff'"){$couleur="bgcolor='#e6e6e6'";$laCouleur="#e6e6e6";}
							else{$couleur="bgcolor='#ffffff'";$laCouleur="#ffffff";}
							
							if($nbJTotal==0){
							$styleJ="style='background-color:#2b8bb4;'";
						}
						if($nbSTotal==0){
							$styleS="style='background-color:#2b8bb4;'";
						}
						if($nbNTotal==0){
							$styleN="style='background-color:#2b8bb4;'";
						}
						if($nbVSDTotal==0){
							$styleVSD="style='background-color:#2b8bb4;'";
						}
						
						if($PersonneJ<>"" && $PersonneJBi<>""){$PersonneJBi="<br>".$PersonneJBi;}
						if($PersonneS<>"" && $PersonneSBi<>""){$PersonneSBi="<br>".$PersonneSBi;}
						if($PersonneN<>"" && $PersonneNBi<>""){$PersonneNBi="<br>".$PersonneNBi;}
						if($PersonneVSD<>"" && $PersonneVSDBi<>""){$PersonneVSDBi="<br>".$PersonneVSDBi;}
						
						if($PersonneJ<>"" && $PersonneJB<>""){$PersonneJB="<br>".$PersonneJB;}
						if($PersonneS<>"" && $PersonneSB<>""){$PersonneSB="<br>".$PersonneSB;}
						if($PersonneN<>"" && $PersonneNB<>""){$PersonneNB="<br>".$PersonneNB;}
						if($PersonneVSD<>"" && $PersonneVSDB<>""){$PersonneVSDB="<br>".$PersonneVSDB;}
					?>
						<tr <?php echo $couleur; ?>>
							<td><?php echo stripslashes(substr($row['Libelle'],0,7))." ".stripslashes($row['Pole']); ?></td>
							<td <?php echo $styleJ; ?>><?php echo $PersonneJ."<span style='color:#10aa4e'>".$PersonneJBi.'</span>'."<span style='color:#0151b9'>".$PersonneJB.'</span>'; ?></td>
							<td <?php echo $styleS; ?>><?php echo $PersonneS."<span style='color:#10aa4e'>".$PersonneSBi.'</span>'."<span style='color:#0151b9'>".$PersonneSB.'</span>'; ?></td>
							<td <?php echo $styleN; ?>><?php echo $PersonneN."<span style='color:#10aa4e'>".$PersonneNBi.'</span>'."<span style='color:#0151b9'>".$PersonneNB.'</span>'; ?></td>
							<td <?php echo $styleVSD; ?>><?php echo $PersonneVSD."<span style='color:#10aa4e'>".$PersonneVSDBi.'</span>'."<span style='color:#0151b9'>".$PersonneVSDB.'</span>'; ?></td>
							<td><?php echo $PersonneABS; ?></td>
						</tr>
					<?php
						}
					}
				}
			?>
		</table>
	</td>
</tr>
<?php 
 }
?>
</table>
	