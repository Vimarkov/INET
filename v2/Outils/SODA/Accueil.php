<?php
if($_POST)
{
	if(isset($_POST['btnRecupererSurveillances'])){
		//Importer les surveillances planifiées manquantes
		/*
		$req="
		INSERT INTO soda_plannifmanuelle (Id_Old,Id_Questionnaire,Annee,Semaine,Id_Prestation,Volume,Id_Creation,DateCreation)
		SELECT ID, 
		(SELECT Id FROM soda_questionnaire WHERE Id_Old=ID_Questionnaire) AS ID_Questionnaire,
		IF(DateReplanif>'0001-01-01',YEAR(DateReplanif),YEAR(DatePlanif)) AS Annee,
		IF(DateReplanif>'0001-01-01',WEEK(DateReplanif,1),WEEK(DatePlanif,1)) AS Semaine,
		ID_Prestation,
		1 AS Volume,
		ID_Surveillant,
		DatePlanif
		FROM new_surveillances_surveillance
		WHERE Etat IN ('Planifié','Replanifié')
		AND YEAR(DatePlanif)=2022
		AND ID NOT IN (
		SELECT Id_Old
			FROM soda_plannifmanuelle
		)
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=ID_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
		AND ID_Questionnaire IN (1,66,130,83,87,133,134,136,101,64,88,120,148,21,27,28,29,
		114,141,100,113,139,138,137,123,131,99,98,60,65,147,77,62,
		144,142,92,149,140,95,97,124,125,146,145,132,122,121,106,105,104,103,102,94,
		93,89,107,108,109,115,118,116,117,143,91,135,126,127,128,76,67,19)";
		$result=mysqli_query($bdd,$req);
		
		$req="
		INSERT INTO soda_plannifmanuelle (Id_Old,Id_Questionnaire,Annee,Semaine,Id_Prestation,Volume,Id_Creation,DateCreation)
		SELECT ID, 
		(SELECT Id FROM soda_questionnaire WHERE Id_Old=ID_Questionnaire) AS ID_Questionnaire,
		IF(DateReplanif>'0001-01-01',YEAR(DateReplanif),YEAR(DatePlanif)) AS Annee,
		IF(DateReplanif>'0001-01-01',WEEK(DateReplanif,1),WEEK(DatePlanif,1)) AS Semaine,
		ID_Prestation,
		1 AS Volume,
		ID_Surveillant,
		DatePlanif
		FROM new_surveillances_surveillance
		WHERE Etat IN ('Planifié','Replanifié')
		AND YEAR(DatePlanif)=2022
		AND ID NOT IN (
		SELECT Id_Old
			FROM soda_plannifmanuelle
		)
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=ID_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
		AND ID_Questionnaire IN (12,129,112,80)";
		$result=mysqli_query($bdd,$req);*/
		
		//Importer les surveillances réalisées et clôturées
		$req="SELECT ID, 
		(SELECT Id FROM soda_plannifmanuelle WHERE soda_plannifmanuelle.Suppr=0 AND Id_Old=new_surveillances_surveillance.ID) AS Id_PlannifManuelle,
		(SELECT Id FROM soda_questionnaire WHERE Id_Old=ID_Questionnaire) AS ID_Questionnaire,
		IF(DateReplanif >'0001-01-01', DateReplanif, DatePlanif) AS DateSurveillance,
		IF(DateReplanif>'0001-01-01',YEAR(DateReplanif),YEAR(DatePlanif)) AS Annee,
		IF(DateReplanif>'0001-01-01',WEEK(DateReplanif,1),WEEK(DatePlanif,1)) AS Semaine,
		ID_Prestation,
		ID_Surveillant,
		DatePlanif,
		ID_Surveille,
		NumActionTracker,
		(SELECT Id_Metier FROM new_competences_personne_metier WHERE new_competences_personne_metier.Id_Personne=ID_Surveillant LIMIT 1) AS Id_Metier
		FROM new_surveillances_surveillance
		WHERE Etat IN ('Réalisé','Clôturé')
		AND YEAR(DatePlanif)=2024
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=ID_Prestation) = 20
		AND ID_Questionnaire IN (1,66,130,83,87,133,134,136,101,64,88,120,148,21,27,28,29,
		114,141,100,113,139,138,137,123,131,99,98,60,65,147,77,62,
		144,142,92,149,140,95,97,124,125,146,145,132,122,121,106,105,104,103,102,94,
		93,89,107,108,109,115,118,116,117,143,91,135,126,127,128,76,67,19,12,129,112,80)
		";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);

		if($nbenreg>0)
		{
			while($row=mysqli_fetch_array($result))
			{
				if($row['Id_PlannifManuelle']==""){
					$req="INSERT INTO soda_plannifmanuelle (Id_Old,Id_Questionnaire,Annee,Semaine,Id_Prestation,Volume,Id_Creation,DateCreation)
					VALUES (".$row['ID'].",".$row['ID_Questionnaire'].",".$row['Annee'].",".$row['Semaine'].",".$row['ID_Prestation'].",1,".$row['ID_Surveillant'].",'".$row['DatePlanif']."') ";
					$resultPlannif=mysqli_query($bdd,$req);
					$IdCree = mysqli_insert_id($bdd);
					
					$Id_Metier=0;
					if($row['Id_Metier']<>""){$Id_Metier=$row['Id_Metier'];}
					$req="INSERT INTO soda_surveillance (Id_Old,Id_PlannifManuelle,Id_Questionnaire,Id_Prestation,Id_Surveillant,Id_Surveille,Id_Metier,AutoSurveillance,DateSurveillance,Etat,SignatureSurveillant,SignatureSurveille) 
					VALUES (".$row['ID'].",".$IdCree.",".$row['ID_Questionnaire'].",".$row['ID_Prestation'].",".$row['ID_Surveillant'].",".$row['ID_Surveille']."
					,".$Id_Metier.",0,'".$row['DateSurveillance']."','Clôturé',1,1) ";
					$resultSurveillance=mysqli_query($bdd,$req);
					$IdCree2 = mysqli_insert_id($bdd);
					
					$req="SELECT ID, 
						ID_Question AS Id_QuestionOLD,
						(SELECT Id FROM soda_question WHERE Id_Old=ID_Question) AS ID_Question,
						Etat,
						Commentaire,
						Action,
						QuestionModifiable
						FROM new_surveillances_surveillance_question  
						WHERE ID_Surveillance=".$row['ID']." ";
					$resultQuestions=mysqli_query($bdd,$req);
					$nbenregQ=mysqli_num_rows($resultQuestions);
					$total=0;
					$C=0;
					$NC=0;
					if($nbenregQ>0)
					{
						while($rowQuestions=mysqli_fetch_array($resultQuestions))
						{
							if($rowQuestions['ID_Question']>0 || ($rowQuestions['ID_Question']=="" && $rowQuestions['Etat']<>"NA" && $rowQuestions['QuestionModifiable']<>"")){
								$Id_Question=0;
								if($rowQuestions['ID_Question']>0){$Id_Question=$rowQuestions['ID_Question'];}
								$req="INSERT INTO soda_surveillance_question (Id_Old,Id_Surveillance,Id_Question,Ponderation,Etat,Commentaire,Action,QuestionAdditionnelle) 
								VALUES (".$rowQuestions['ID'].",".$IdCree2.",".$Id_Question.",1,'".$rowQuestions['Etat']."','".addslashes($rowQuestions['Commentaire'])."',
								'".addslashes($rowQuestions['Action'])."','".addslashes($rowQuestions['QuestionModifiable'])."') ";
								$resultQuestion=mysqli_query($bdd,$req);
								$total++;
								if($rowQuestions['Etat']=="C"){$C++;}
								elseif($rowQuestions['Etat']=="NC"){$NC++;}
							}
						}
					}
					
					//METTRE A JOUR LE RESULTAT 
					$note =0;
					if($total>0){
						$note = round(($C/$total)*100);
					}
					$req="UPDATE soda_surveillance SET Resultat=".$note." WHERE Id=".$IdCree2." ";
					$resultSurveillance=mysqli_query($bdd,$req);
				}
				else{
					
					//VERIFIER SI SURVEILLANCE EXISTE SINON MISE A JOUR
					$req="SELECT MAJManuelle, Id FROM soda_surveillance WHERE Suppr=0 AND Id_Old=".$row['ID']." ";
					$resultVerif=mysqli_query($bdd,$req);
					$nbenregV=mysqli_num_rows($resultVerif);
					
					$majManuelle=0;
					$Id_Metier=0;
					if($row['Id_Metier']<>""){$Id_Metier=$row['Id_Metier'];}
					if($nbenregV==0){
						$req="INSERT INTO soda_surveillance (Id_Old,Id_PlannifManuelle,Id_Questionnaire,Id_Prestation,Id_Surveillant,Id_Surveille,Id_Metier,AutoSurveillance,DateSurveillance,Etat) 
						VALUES (".$row['ID'].",".$row['Id_PlannifManuelle'].",".$row['ID_Questionnaire'].",".$row['ID_Prestation'].",".$row['ID_Surveillant'].",".$row['ID_Surveille']."
						,".$Id_Metier.",0,'".$row['DateSurveillance']."','Clôturé') ";
						$resultSurveillance=mysqli_query($bdd,$req);
						$IdCree2 = mysqli_insert_id($bdd);
					}
					else{
						$rowVerif=mysqli_fetch_array($resultVerif);
						if($rowVerif['MAJManuelle']==0){
							$req="UPDATE soda_surveillance 
							SET
							Id_Prestation=".$row['ID_Prestation'].",
							Id_Surveillant=".$row['ID_Surveillant'].",
							Id_Surveille=".$row['ID_Surveille'].",
							Id_Metier=".$Id_Metier.",
							DateSurveillance='".$row['DateSurveillance']."'
							WHERE Id=".$rowVerif['Id']." ";

							$resultSurveillance=mysqli_query($bdd,$req);
							$IdCree2 = $rowVerif['Id'];
						}
						else{
							$majManuelle=1;
						}
					}
					
					if($majManuelle==0){
						//VERIFIER SI QUESTIONS EXISTE SINON MISE A JOUR
						$req="SELECT Id FROM soda_surveillance_question WHERE Id_Surveillance=".$IdCree2." ";
						$resultVerifQ=mysqli_query($bdd,$req);
						$nbenregVQ=mysqli_num_rows($resultVerifQ);
						
						$req="SELECT ID, 
							ID_Question AS Id_QuestionOLD,
							(SELECT Id FROM soda_question WHERE Id_Old=ID_Question) AS ID_Question,
							(SELECT COUNT(Id) 
								FROM soda_surveillance_question 
								WHERE Id_Old=new_surveillances_surveillance_question.ID AND 
								(
								soda_surveillance_question.Etat<>new_surveillances_surveillance_question.Etat
								OR soda_surveillance_question.Commentaire<>new_surveillances_surveillance_question.Commentaire
								OR soda_surveillance_question.Action<>new_surveillances_surveillance_question.Action
								OR soda_surveillance_question.QuestionAdditionnelle<>new_surveillances_surveillance_question.QuestionModifiable
								)
							) AS AChange,
							Etat,
							Commentaire,
							Action,
							QuestionModifiable
							FROM new_surveillances_surveillance_question  
							WHERE ID_Surveillance=".$row['ID']." ";
						$resultQuestions=mysqli_query($bdd,$req);
						$nbenregQ=mysqli_num_rows($resultQuestions);
						$total=0;
						$C=0;
						$NC=0;
						if($nbenregVQ==0){
							if($nbenregQ>0)
							{
								while($rowQuestions=mysqli_fetch_array($resultQuestions))
								{
									if($rowQuestions['ID_Question']>0 || ($rowQuestions['ID_Question']=="" && $rowQuestions['Etat']<>"NA" && $rowQuestions['QuestionModifiable']<>"")){
										$Id_Question=0;
										if($rowQuestions['ID_Question']>0){$Id_Question=$rowQuestions['ID_Question'];}
										$req="INSERT INTO soda_surveillance_question (Id_Old,Id_Surveillance,Id_Question,Ponderation,Etat,Commentaire,Action,QuestionAdditionnelle) 
										VALUES (".$rowQuestions['ID'].",".$IdCree2.",".$Id_Question.",1,'".$rowQuestions['Etat']."',\"".addslashes($rowQuestions['Commentaire'])."\",
										\"".addslashes($rowQuestions['Action'])."\",\"".addslashes($rowQuestions['QuestionModifiable'])."\") ";
										$resultQuestion=mysqli_query($bdd,$req);
										$total++;
										if($rowQuestions['Etat']=="C"){$C++;}
										elseif($rowQuestions['Etat']=="NC"){$NC++;}
									}
								}
							}
						}
						else{
							if($nbenregQ>0)
							{
								while($rowQuestions=mysqli_fetch_array($resultQuestions))
								{
									if($rowQuestions['ID_Question']>0 || ($rowQuestions['ID_Question']=="" && $rowQuestions['Etat']<>"NA" && $rowQuestions['QuestionModifiable']<>"")){
										$Id_Question=0;
										if($rowQuestions['ID_Question']>0){$Id_Question=$rowQuestions['ID_Question'];}
										if($rowQuestions['AChange']>0){
											$req="UPDATE soda_surveillance_question 
											SET Etat='".$rowQuestions['Etat']."',
											Commentaire=\"".addslashes($rowQuestions['Commentaire'])."\",
											Action=\"".addslashes($rowQuestions['Action'])."\",
											QuestionAdditionnelle=\"".addslashes($rowQuestions['QuestionModifiable'])."\" 
											WHERE Id_Old=".$rowQuestions['ID']." ";
											$resultQuestion=mysqli_query($bdd,$req);
										}
										$total++;
										if($rowQuestions['Etat']=="C"){$C++;}
										elseif($rowQuestions['Etat']=="NC"){$NC++;}
									}
								}
							}
						}
						
						
						// MISE A JOUR RESULTAT
						$note =0;
						if($total>0){
							$note = round(($C/$total)*100);
						}
						$req="UPDATE soda_surveillance SET Resultat=".$note." WHERE Id=".$IdCree2." ";
						$resultSurveillance=mysqli_query($bdd,$req);
					}
				}
			}
		}
	}
}
?>
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<?php if($nbAccess>0 || $nbSuperAdmin>0){ ?>
	<!--<tr>
		<td colspan="3" align="right">
			<input class="Bouton" name="btnRecupererSurveillances" type="submit"  value="Récupérer données 'Gestion des surveillances'" />
		</td>
	</tr>-->
	<?php } ?>
	<tr>
		<td height="10"></td>
	</tr>
	<tr><td width="100%" colspan="3">
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="TableCompetences">
			<tr><td height="4"></td></tr>
			<tr>
				<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Année : ";}else{echo "Year  : ";}?></td>
				<td width="15%" class="Libelle">
					<select id="annee" name="annee" onchange="submit();">
					<?php
						$annee=$_SESSION['FiltreSODA_Annee'];
						if($_POST){
							if(isset($_POST['annee'])){
								$annee=$_POST['annee'];
							}
						}
						$_SESSION['FiltreSODA_Annee']=$annee;
						
						for($i=2022;$i<=date('Y')+1;$i++){
							$selected="";
							if($i==$_SESSION['FiltreSODA_Annee']){$selected="selected";}
							echo "<option value='".$i."' ".$selected.">".$i."</option>";
						}
					 ?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Thème : ";}else{echo "Theme : ";}?></td>
				<td>
					<select style="width:200px;" name="theme" onchange="submit();">
					<?php
					$req = "SELECT soda_theme.Id, soda_theme.Libelle
							FROM soda_theme
							WHERE Suppr=0
							ORDER BY soda_theme.Libelle;";
					$resultTheme=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($resultTheme);
					
					$ThemeSelect = $_SESSION['FiltreSODAAccueil_Theme'];
					if($_POST){$ThemeSelect=$_POST['theme'];}
					$_SESSION['FiltreSODAAccueil_Theme']=$ThemeSelect;
					
					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbTheme > 0)
					{
						while($row=mysqli_fetch_array($resultTheme))
						{
							$selected="";
							if($ThemeSelect==$row['Id']){$selected="selected";}
							echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						}
					 }
					 
					 ?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Questionnaire : ";}else{echo "Questionnaire : ";}?></td>
				<td colspan="3" >
					<select name="Questionnaire" style="width:300px;" onchange="submit();">
					<?php
					$req = "SELECT soda_questionnaire.Id, 
							CONCAT(soda_questionnaire.Libelle,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Libelle
							FROM soda_questionnaire
							WHERE soda_questionnaire.Id_Theme =".$ThemeSelect." 
							AND soda_questionnaire.Suppr=0
							ORDER BY 
							soda_questionnaire.Actif,
							soda_questionnaire.Libelle;";
					$resultQuestionnaire=mysqli_query($bdd,$req);
					$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
					
					$QuestionnaireSelect = $_SESSION['FiltreSODAAccueil_Questionnaire'];
					if($changementPlateformeTheme==0)
					{
						if($_POST){$QuestionnaireSelect=$_POST['Questionnaire'];}
					}
					else
					{
						$QuestionnaireSelect=0;
					}
					$_SESSION['FiltreSODAAccueil_Questionnaire']=$QuestionnaireSelect;
					
					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbQuestionnaire > 0)
					{
						while($row=mysqli_fetch_array($resultQuestionnaire))
						{
							$selected="";
							if($QuestionnaireSelect==$row['Id']){$selected="selected";}
							echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						}
					 }
					 ?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="15%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
				<td width="15%">
					<select name="plateforme" style="width:150px;" onchange="submit();">
					<?php
					if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
						$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle
								FROM new_competences_plateforme
								WHERE Id<> 11 AND Id<>14
								ORDER BY new_competences_plateforme.Libelle;";
					}
					else{
						$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle
								FROM new_competences_plateforme
								WHERE Id<> 11 AND Id<>14
								AND (
									Id IN (
									SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
									AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteReferentSurveillance.")
								)
								OR 
								Id IN (
									SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
									FROM new_competences_personne_poste_prestation 
									WHERE Id_Personne=".$_SESSION['Id_Personne']."
									AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableProjet.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
									)
								)
								ORDER BY new_competences_plateforme.Libelle;";
					}
					$resultPlateforme=mysqli_query($bdd,$req);
					$nbPlateforme=mysqli_num_rows($resultPlateforme);
					
					$changementPlateforme=0;
					$PlateformeSelect = $_SESSION['FiltreSODAAccueil_Plateforme'];
					if($_POST)
					{
						$PlateformeSelect=$_POST['plateforme'];
						if($PlateformeSelect<>$_SESSION['FiltreSODAAccueil_Plateforme']){$changementPlateforme=1;}
					}
					$_SESSION['FiltreSODAAccueil_Plateforme']=$PlateformeSelect;

					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbPlateforme > 0)
					{
						while($row=mysqli_fetch_array($resultPlateforme))
						{
							$selected="";
							if($PlateformeSelect<>"0")
								{if($PlateformeSelect==$row['Id']){$selected="selected";}}
							echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						}
					 }
					 ?>
					</select></td>
				<td width="15%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
				<td width="20%">
					<select class="prestation" name="prestations" style="width:150px;" onchange="submit();">
						<?php
						if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteChargeMissionOperation))){
							$req = "SELECT new_competences_prestation.Id, 
									Libelle,
									IF(Active=0,'[Actif]','[Inactif]') AS Active
									FROM new_competences_prestation
									WHERE new_competences_prestation.Id_Plateforme=".$PlateformeSelect." 
									ORDER BY Active ASC, new_competences_prestation.Libelle;";
						}
						else{
							$req = "SELECT new_competences_prestation.Id, 
									Libelle,
									IF(Active=0,'[Actif]','[Inactif]') AS Active
									FROM new_competences_prestation
									WHERE new_competences_prestation.Id_Plateforme=".$PlateformeSelect." 
									AND (
										Id_Plateforme IN (
										SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteChargeMissionOperation.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteReferentSurveillance.")
									)
									OR 
									Id IN (
										SELECT Id_Prestation
										FROM new_competences_personne_poste_prestation 
										WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableProjet.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
										)
									)
									ORDER BY Active ASC, new_competences_prestation.Libelle;";
						}
						$resultPrestation=mysqli_query($bdd,$req);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$PrestationSelect = $_SESSION['FiltreSODAAccueil_Prestation'];
						if($changementPlateforme==0)
						{
							if($_POST){$PrestationSelect=$_POST['prestations'];}
						}
						else
						{
							$PrestationSelect=0;
						}
						 $_SESSION['FiltreSODAAccueil_Prestation']=$PrestationSelect;
						 
						$Selected = "";
						
						echo "<option value='0' Selected></option>";
						if ($nbPrestation > 0)
						{
							while($row=mysqli_fetch_array($resultPrestation))
							{
								$selected="";
								if($PrestationSelect==$row['Id']){$selected="selected";}
								$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "))." ".$row['Active'];
								if(substr($row['Libelle'],0,strpos($row['Libelle']," "))==""){$presta=$row['Libelle']." ".$row['Active'];}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($presta)."</option>\n";
							}
						 }
						
						 ?>
					</select>
				</td>
				<td width="15%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Surveillant : ";}else{echo "Supervisor : ";}?></td>
				<td width="20%">
					<select class="surveillant" name="surveillant" style="width:150px;" onchange="submit();">
						<?php
						$req = "
							SELECT DISTINCT Id_Personne AS Id, 
							(SELECT CONCAT(Nom, ' ', Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) as NomPrenom
							FROM new_competences_relation 
							WHERE (Evaluation='L'
							OR
							(Evaluation='X'
							AND Date_Debut<='".date('Y-m-d')."'
							AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
							)
							)
							AND Suppr=0
							AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
							UNION
							SELECT DISTINCT
								new_rh_etatcivil.Id,
								CONCAT(Nom, ' ', Prenom) as NomPrenom
							FROM
								new_rh_etatcivil
							INNER JOIN soda_surveillant
								ON new_rh_etatcivil.Id=soda_surveillant.Id_Personne
								
							ORDER BY NomPrenom ASC";
						$resultSurveillant=mysqli_query($bdd,$req);
						$nbSurveillant=mysqli_num_rows($resultSurveillant);
						
						$SurveillantSelect = $_SESSION['FiltreSODAAccueil_Surveillant'];
						if($_POST){$SurveillantSelect=$_POST['surveillant'];}
						 $_SESSION['FiltreSODAAccueil_Surveillant']=$SurveillantSelect;
						 

						echo "<option value='-1' Selected></option>";
						$selected = "";
						if($SurveillantSelect==0){$selected="selected";}
						if($_SESSION["Langue"]=="FR"){
							echo "<option value='0' ".$selected." >Pas de surveillant</option>";
						}
						else{
							echo "<option value='0' ".$selected." >No supervisor</option>";
						}
						if ($nbSurveillant > 0)
						{
							while($row=mysqli_fetch_array($resultSurveillant))
							{
								$selected="";
								if($SurveillantSelect==$row['Id']){$selected="selected";}
								echo "<option value='".$row['Id']."' ".$selected.">".$row['NomPrenom']."</option>\n";
							}
						 }
						
						 ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" colspan="6">
					<a style="text-decoration:none;" href="javascript:OuvreFenetreExcel('Accueil')"><img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel"></a>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td width="10%"></td>
		<td width="80%" class="Libelle">
			&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Liste des surveillances planifiées";}else{echo "List of scheduled monitoring";}?> :
		</td>
		<td width="10%"></td>
	</tr>
	<tr>
		<td width="10%"></td>
		<td width="80%">
			<div style="overflow:auto;height:600px;width:100%;">
				<table align="center" style="border-spacing:0; align:center;width:100%;" class="GeneralInfo">
					<thead align="center">
						<th width="15%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"><?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?></th>
						<th width="25%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"><?php if($_SESSION["Langue"]=="FR"){echo "Questionnaire";}else{echo "Questionnaire";}?></th>
						<th width="5%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"><?php if($_SESSION["Langue"]=="FR"){echo "Semaine";}else{echo "Week";}?></th>
						<th width="8%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"><?php if($_SESSION["Langue"]=="FR"){echo "UER";}else{echo "UER";}?></th>
						<th width="8%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";}?></th>
						<th width="8%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"><?php if($_SESSION["Langue"]=="FR"){echo "Responsable";}else{echo "Responsable";}?></th>
						<th width="5%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"><?php if($_SESSION["Langue"]=="FR"){echo "Nb";}else{echo "Number";}?></th>
						<th width="12%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillant";}else{echo "Supervisor";}?></th>
						<th width="6%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";}?></th>
						<th width="5%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"></th>
						<th width="8%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;">
							<?php 
								if($nbAccess>0 || $nbSuperAdmin>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteReferentSurveillance))
									|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite)))
								{
							?>
									<a class="Bouton" href="javascript:OuvreFenetreModif('ChoisirSurveillant.php','Modif',0);">
										<?php if($_SESSION["Langue"]=="FR"){echo "Choisir surveillant";}else{echo "Choose supervisor";}?>
									</a><br>
									<input type="checkbox" name="selectAllSurveillant" id="selectAllSurveillant" onclick="SelectionnerTout('Surveillant')" /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
							<?php
								}
							?>
						</th>
						<th width="10%" class="EnTeteTableauCompetences" style="background-color:#ffffff;border-bottom:1px solid black;top:0;position: sticky;"></th>
					</thead>
					<tbody>
					<?php 
						$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
						$nbAccess=mysqli_num_rows($resAcc);
						
						$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
						$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);
						
						$reqSurveillant = "SELECT Id FROM soda_surveillant WHERE Id_Personne=".$_SESSION['Id_Personne']." ";	
						$nbSurveillant=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqSurveillant));

						//Suppression des surveillances planifiées non réalisé le jour même
						$req="UPDATE soda_surveillance SET Suppr=1 WHERE Suppr=0 AND DateSurveillance<'".date('Y-m-d')."' AND Etat='Planifié' ";
						$resultUpdt=mysqli_query($bdd,$req);
		
						//Liste des surveillances planifiées
						$req="SELECT Id,'PLANNIF' AS Type,
								(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
								(SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Id_Theme,
								(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
								Id_Questionnaire,
								(SELECT Actif FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS SupprQuestionnaire,
								IF(Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)) AS Plateforme,
								IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) AS Id_Plateforme,
								(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,Id_Prestation,
								(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) FROM new_competences_personne_poste_prestation WHERE Id_Prestation=soda_plannifmanuelle.Id_Prestation AND Id_Poste=1 AND Id_Personne>0 ORDER BY Backup LIMIT 1) AS N1,
								(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) FROM new_competences_personne_poste_prestation WHERE Id_Prestation=soda_plannifmanuelle.Id_Prestation AND Id_Poste=2 AND Id_Personne>0 ORDER BY Backup LIMIT 1) AS N2,
								Volume-(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND Etat IN ('Clôturé','En cours - papier','Brouillon') AND AutoSurveillance=0 AND Id_PlannifManuelle=soda_plannifmanuelle.Id ) AS Volume,
								Annee,Semaine,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Surveillant) AS Surveillant,
								(SELECT COUNT(Id) FROM soda_surveillant_theme WHERE soda_surveillant_theme.Id_Theme=(SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) AND soda_surveillant_theme.Id_Surveillant=soda_plannifmanuelle.Id_Surveillant) AS SurvTheme,
								(SELECT COUNT(new_competences_relation.Id) 
									FROM new_competences_relation 
									LEFT JOIN soda_theme
									ON new_competences_relation.Id_Qualification_Parrainage=soda_theme.Id_Qualification
									WHERE Evaluation='L'
									AND new_competences_relation.Suppr=0
									AND soda_theme.Id=(SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire)
									AND new_competences_relation.Id_Personne=soda_plannifmanuelle.Id_Surveillant
								) AS EnFormationSurTheme,
								Id_Surveillant
								FROM soda_plannifmanuelle 
								WHERE Suppr=0
								AND Annee=".$_SESSION['FiltreSODA_Annee']."
								AND (SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND Etat IN ('Clôturé','En cours - papier','Brouillon') AND AutoSurveillance=0 
									AND Id_PlannifManuelle=soda_plannifmanuelle.Id) < soda_plannifmanuelle.Volume
								";
						if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteChargeMissionOperation,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
							
						}
						else{
							$req.="AND (IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
								SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
								AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteChargeMissionOperation.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteReferentSurveillance.")
							)
							OR 
							Id_Prestation IN (
								SELECT Id_Prestation
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION['Id_Personne']."
								AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableProjet.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
								)
							)
							";
						}
						if ($PlateformeSelect <> 0 && $PlateformeSelect <> -1){$req .= "AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme)=".$PlateformeSelect." ";}
						if ($PrestationSelect <> 0){$req .= "AND Id_Prestation =".$PrestationSelect." ";}
						if ($ThemeSelect <> 0)
						{
							$req .= "AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) =".$ThemeSelect." ";
							if($QuestionnaireSelect <> 0){$req .= "AND Id_Questionnaire =".$QuestionnaireSelect." ";}
						}
						$req.=" ORDER BY Semaine, Theme, Questionnaire, Prestation";

						$resultSurveillance=mysqli_query($bdd,$req);
						$nbSurveillance=mysqli_num_rows($resultSurveillance);
						$semaine=date('Y',strtotime(date('Y-m-d')."+0 month"))."S";
						$semaine.=date('W',strtotime(date('Y-m-d')."+0 month"));
						
						$semaine2=date('Y',strtotime(date('Y-m-d')."+1 month"))."S";
						$semaine2.=date('W',strtotime(date('Y-m-d')."+1 month"));

						$Couleur="#EEEEEE";
						if($nbSurveillance>0){
							while($row=mysqli_fetch_array($resultSurveillance)){
								$volume=$row['Volume'];
								
								$affiche=1;
								if ($SurveillantSelect <> -1){
									if($row['Id_Surveillant']<>$SurveillantSelect){$affiche=0;}
								}
								if($volume>0 && $affiche==1){
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
									
									if($_SESSION["Langue"]=="FR"){$etat="A faire";}else{$etat= "To do";}
									$lasemaine=$row['Annee']."S";
									$couleurTexte="";
									if($row['Semaine']<10){$lasemaine.="0".$row['Semaine'];}
									else{$lasemaine.=$row['Semaine'];}
									if($semaine>$lasemaine){
										$couleurTexte="style='color:#f31515;'";
										if($_SESSION["Langue"]=="FR"){$etat="En retard";}else{$etat= "Late";}
									}
									
									$presta=substr($row['Prestation'],0,strpos($row['Prestation']," "));
									if($presta==""){$presta=$row['Prestation'];}
									
									$enformation="";
									if($row['EnFormationSurTheme']==1 && $row['SurvTheme']==0){
										if($_SESSION['Langue']=="FR"){$enformation= "<i> [En formation] </i>";}
										else{$enformation= "<i> [In training] </i>";}
									}
									?>
									<tr bgcolor="<?php echo $Couleur;?>">
										<td width="15%" <?php echo $couleurTexte;?>><?php echo stripslashes($row['Theme']);?></td>
										<td width="25%" <?php echo $couleurTexte;?> ><?php echo stripslashes($row['Questionnaire']);?></td>
										<td width="5%" <?php echo $couleurTexte;?> align="center">S<?php if($row['Semaine']<10){echo "0".$row['Semaine'];}else{echo $row['Semaine'];} ?></td>
										<td width="8%" <?php echo $couleurTexte;?> align="center"><?php echo stripslashes($row['Plateforme']);?></td>
										<td width="8%" <?php echo $couleurTexte;?> align="center"><?php echo $presta;?></td>
										<td width="8%" <?php echo $couleurTexte;?> align="center"><?php if($row['N1']<>""){echo stripslashes($row['N1']);}else{echo stripslashes($row['N2']);} ?></td>
										<td width="5%" <?php echo $couleurTexte;?> align="center"><?php echo stripslashes($volume);?></td>
										<td width="12%" <?php echo $couleurTexte;?> align="center"><?php echo stripslashes($row['Surveillant'].$enformation);?></td>
										<td width="6%" <?php echo $couleurTexte;?> align="center"><?php echo $etat; ?></td>
										<td width="5%" <?php echo $couleurTexte;?> align="center">
										<?php 
											if($semaine2>=$lasemaine && ($nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0)){
												//Vérifier Si la personne peut faire les surveillances de cette thématique
												$req="SELECT Id FROM soda_surveillant_theme WHERE Id_Surveillant=".$_SESSION['Id_Personne']." AND Id_Theme=".$row['Id_Theme']." ";
												$resultSurvTheme=mysqli_query($bdd,$req);
												$nbSurvTheme=mysqli_num_rows($resultSurvTheme);
												
												$req="SELECT Id FROM soda_theme WHERE Id=".$row['Id_Theme']." AND Id_Qualification IN (
													SELECT DISTINCT Id_Qualification_Parrainage 
													FROM new_competences_relation 
													WHERE (Evaluation='X'
													AND Date_Debut<='".date('Y-m-d')."'
													AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
													)
													AND Suppr=0
													AND Id_Qualification_Parrainage IN (SELECT Id_Qualification FROM soda_theme WHERE Id=".$row['Id_Theme'].")
													AND Id_Personne=".$_SESSION['Id_Personne']."
												) ";
												$resultSurTheme= mysqli_query($bdd,$req);	
												$nbSurvThemeQualifie=mysqli_num_rows($resultSurTheme);
												
												$req="SELECT Id FROM soda_theme WHERE Id=".$row['Id_Theme']." AND Id_Qualification IN (
													SELECT DISTINCT Id_Qualification_Parrainage 
													FROM new_competences_relation 
													WHERE Evaluation='L'
													AND Suppr=0
													AND Id_Qualification_Parrainage IN (SELECT Id_Qualification FROM soda_theme WHERE Id=".$row['Id_Theme'].")
													AND Id_Personne=".$_SESSION['Id_Personne']."
												) ";
												$resultSurTheme= mysqli_query($bdd,$req);	
												$nbSurvThemeECQualifie=mysqli_num_rows($resultSurTheme);
												
												if($nbSurvTheme>0 || $nbSurvThemeQualifie>0 || ($nbSurvThemeECQualifie>0 && $row['Id_Surveillant']==$_SESSION['Id_Personne']) ){
													if($row['Id_Prestation']==0){
											?>
														<a class="Bouton" href="javascript:OuvreFenetreModif('LancerSurveillanceProcessus.php','Modif','<?php echo $row['Id']; ?>');">
															<?php if($_SESSION["Langue"]=="FR"){echo "Lancer";}else{echo "Launch";}?>
														</a>
											<?php			
													}
													else{
											?>
														<a class="Bouton" href="javascript:OuvreFenetreModif('LancerSurveillance.php','Modif','<?php echo $row['Id']; ?>');">
															<?php if($_SESSION["Langue"]=="FR"){echo "Lancer";}else{echo "Launch";}?>
														</a>
											<?php
													}
												}
											}
										?>
										</td>
										<td width="8%" <?php echo $couleurTexte;?> align="center">
										<?php 
											if($nbAccess>0 || $nbSuperAdmin>0 || DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteChargeMissionOperation,$IdPosteReferentSurveillance))
												|| DroitsFormationPrestationV2(array($row['Id_Prestation']),array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite)))
											{
										?>
												<input class="checkSurveillant" type="checkbox" value="<?php echo $row['Id'];?>" name="Surveillant<?php echo $row['Id'];?>">
										<?php
											}
										?>
										</td>
										<td width="10%" <?php echo $couleurTexte;?> align="center">
										<?php
											if($row['SupprQuestionnaire']==1){
												if($nbAccess>0 || $nbSuperAdmin>0)
												{
										?>
													<a style="text-decoration:none;" href="javascript:OuvreFenetreSupprSurveillancePlanifiee('SupprimerSurveillancePlanifiee.php',<?php echo $row['Id'];?>,<?php echo $row['Volume'];?>)"><img src="../../Images/Suppression2.gif" border="0" alt="Suppr" title="Suppr"></a>
										<?php
												}
											}
										?>
										</td>
									</tr>
									<?php
								}
							}
						}
					?>
					</tbody>
				</table>
			</div>
		</td>
		<td width="10%"></td>
	</tr>
	<tr>
		<td height="100"></td>
	</tr>
</table>
</body>
</html>