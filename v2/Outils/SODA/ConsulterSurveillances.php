<?php
$AccesQualite=false;
if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteChargeMissionOperation)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,8))){$AccesQualite=true;}

if(isset($_GET['Tri'])){
	$tab = array("Id","Plateforme","Prestation","Theme","Questionnaire","Etat","Resultat","NumActionTracker","DateSurveillance","Surveille","Surveillant");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriConsultSODA_General']= str_replace($tri." ASC,","",$_SESSION['TriConsultSODA_General']);
			$_SESSION['TriConsultSODA_General']= str_replace($tri." DESC,","",$_SESSION['TriConsultSODA_General']);
			$_SESSION['TriConsultSODA_General']= str_replace($tri." ASC","",$_SESSION['TriConsultSODA_General']);
			$_SESSION['TriConsultSODA_General']= str_replace($tri." DESC","",$_SESSION['TriConsultSODA_General']);
			if($_SESSION['TriConsultSODA_'.$tri]==""){$_SESSION['TriConsultSODA_'.$tri]="ASC";$_SESSION['TriConsultSODA_General'].= $tri." ".$_SESSION['TriConsultSODA_'.$tri].",";}
			elseif($_SESSION['TriConsultSODA_'.$tri]=="ASC"){$_SESSION['TriConsultSODA_'.$tri]="DESC";$_SESSION['TriConsultSODA_General'].= $tri." ".$_SESSION['TriConsultSODA_'.$tri].",";}
			else{$_SESSION['TriConsultSODA_'.$tri]="";}
		}
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr><td height="4"></td></tr>
	<tr><td width="100%">
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="TableCompetences">
			<tr><td height="4"></td></tr>
			<tr>
				<td width="7%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
				<td width="12%">
					<select name="plateforme" style="width:150px;" onchange="submit();">
					<?php
					$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle
							FROM new_competences_plateforme
							WHERE Id<> 11 AND Id<>14
							ORDER BY new_competences_plateforme.Libelle;";
					$resultPlateforme=mysqli_query($bdd,$req);
					$nbPlateforme=mysqli_num_rows($resultPlateforme);
					
					$changementPlateforme=0;
					$PlateformeSelect = $_SESSION['FiltreSODAConsult_Plateforme'];
					if($_POST)
					{
						$PlateformeSelect=$_POST['plateforme'];
						if($PlateformeSelect<>$_SESSION['FiltreSODAConsult_Plateforme']){$changementPlateforme=1;}
					}
					$_SESSION['FiltreSODAConsult_Plateforme']=$PlateformeSelect;

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
					</select>
				</td>
				<td width="6%" class="Libelle">
					<?php 
						$prestationA=$_SESSION['FiltreSODAConsult_PrestationA'];
						if($_POST){
							if(!empty($_POST['prestationA'])){$prestationA="1";}
							else{$prestationA="0";}
						}
						$_SESSION['FiltreSODAConsult_PrestationA']=$prestationA;
						
						$prestationI=$_SESSION['FiltreSODAConsult_PrestationI'];
						if($_POST){
							if(!empty($_POST['prestationI'])){$prestationI="1";}
							else{$prestationI="0";}
						}
						$_SESSION['FiltreSODAConsult_PrestationI']=$prestationI;
					?>
					&nbsp;Actif&nbsp;&nbsp;&nbsp;<input type="checkbox" name="prestationA" alt="Actif" title="Actif" <?php if($prestationA=="1"){echo "checked";} ?> onchange="submit();"/><br>
					&nbsp;Inactif&nbsp;&nbsp;<input type="checkbox" name="prestationI" alt="Inactif" title="Inactif" <?php if($prestationI=="1"){echo "checked";} ?> onchange="submit();"/>
				</td>
				<td width="7%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
				<td width="20%">
					<select class="prestation" name="prestations" style="width:150px;">
						<?php
						$req = "SELECT new_competences_prestation.Id, 
								Libelle,
								IF(Active=0,'[Actif]','[Inactif]') AS Active
								FROM new_competences_prestation
								WHERE new_competences_prestation.Id_Plateforme=".$PlateformeSelect."
								AND SousSurveillance IN ('','Oui/Yes') ";
						if($prestationA=="1" && $prestationI=="0"){$req.=" AND Active=0 ";}
						elseif($prestationA=="0" && $prestationI=="1"){$req.=" AND Active=-1 ";}
						elseif($prestationA=="0" && $prestationI=="0"){$req.=" AND Active=0 ";}
						$req.= "ORDER BY Active ASC, new_competences_prestation.Libelle;";
						$resultPrestation=mysqli_query($bdd,$req);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$PrestationSelect = $_SESSION['FiltreSODAConsult_Prestation'];
						if($changementPlateforme==0)
						{
							if($_POST){$PrestationSelect=$_POST['prestations'];}
						}
						else
						{
							$PrestationSelect=0;
						}
						 $_SESSION['FiltreSODAConsult_Prestation']=$PrestationSelect;
						 
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
				<td width="7%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Année : ";}else{echo "Year  : ";}?></td>
				<td width="10%" class="Libelle">
					<?php
						$annee=$_SESSION['FiltreSODAConsult_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						$_SESSION['FiltreSODAConsult_Annee']=$annee;
					?>
					<input onKeyUp="nombre(this)" style="width:100px;" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
				</td>
				<td width="12%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Date surveillance : ";}else{echo "Monitoring date  : ";}?></td>
				<td width="15%" class="Libelle">
					<?php
						$dateDebut=$_SESSION['FiltreSODAConsult_DateSurveillance'];
						if($_POST){$dateDebut=$_POST['DateSurveillance'];}
						$_SESSION['FiltreSODAConsult_DateSurveillance']=$dateDebut;
					?>
					<input type="date" style="width:100px;" name="DateSurveillance"  value="<?php echo $dateDebut; ?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
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
					
					$ThemeSelect = $_SESSION['FiltreSODAConsult_Theme'];
					if($_POST){$ThemeSelect=$_POST['theme'];}
					$_SESSION['FiltreSODAConsult_Theme']=$ThemeSelect;
					
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
				<td></td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Questionnaire : ";}else{echo "Questionnaire : ";}?></td>
				<td colspan="3" >
					<select name="Questionnaire" style="width:300px;">
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
					
					$QuestionnaireSelect = $_SESSION['FiltreSODAConsult_Questionnaire'];
					if($changementPlateformeTheme==0)
					{
						if($_POST){$QuestionnaireSelect=$_POST['Questionnaire'];}
					}
					else
					{
						$QuestionnaireSelect=0;
					}
					$_SESSION['FiltreSODAConsult_Questionnaire']=$QuestionnaireSelect;
					
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
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "N° surveillance : ";}else{echo "Monitoring number : ";}?></td>
				<td>
					<?php
					$numSurveillance = $_SESSION['FiltreSODAConsult_NumSurveillance'];
					if($_POST){$numSurveillance=$_POST['numSurveillance'];}
					$_SESSION['FiltreSODAConsult_NumSurveillance']=$numSurveillance;
					?>
					<input type="texte" style="width:80px;" name="numSurveillance"  value="<?php echo $numSurveillance; ?>">
				</td>
				<td></td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "N° ActionTracker : ";}else{echo "ActionTracker number : ";}?></td>
				<td class="Libelle">
					<?php
					$numAT = $_SESSION['FiltreSODAConsult_NumAT'];
					if($_POST){$numAT=$_POST['numAT'];}
					$_SESSION['FiltreSODAConsult_NumAT']=$numAT;
					
					$ATNonRenseigne = $_SESSION['FiltreSODAConsult_ATNonRenseigne'];
					if($_POST){if(isset($_POST['ATNonRenseigne'])){$ATNonRenseigne="checked";}else{$ATNonRenseigne="";}}
					$_SESSION['FiltreSODAConsult_ATNonRenseigne']=$ATNonRenseigne;
					?>
					<input type="texte" style="width:80px;" name="numAT"  value="<?php echo $numAT; ?>">&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="ATNonRenseigne" <?php if($ATNonRenseigne<>""){echo "checked";} ?>> <?php if($_SESSION['Langue']=="FR"){echo "N° ActionTracker non renseigné";}else{echo "ActionTracker number not filled in ";}?>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Surveillé : ";}else{echo "Supervised : ";}?></td>
				<td>
					<select name="surveille" style="width:190px;">
						<?php
							$req = "SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom
									FROM soda_surveillance 
									LEFT JOIN new_rh_etatcivil
									ON soda_surveillance.Id_Surveille=new_rh_etatcivil.Id
									WHERE soda_surveillance.Suppr=0 
									AND Id_Surveille>0
									AND Id_Surveille<>6572
									ORDER BY Nom, Prenom;";
							$resultSurveille=mysqli_query($bdd,$req);
							$nbSurveille=mysqli_num_rows($resultSurveille);
							
							$SurveilleSelect = $_SESSION['FiltreSODAConsult_Surveille'];
							if($_POST){$SurveilleSelect=$_POST['surveille'];}
							$_SESSION['FiltreSODAConsult_Surveille']=$SurveilleSelect;
							$Selected = "";

							echo "<option name='0' value='0' Selected></option>";
							if ($nbSurveille > 0)
							{
								while($row=mysqli_fetch_array($resultSurveille))
								{
									$selected="";
									if($SurveilleSelect==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Nom'])." ".stripslashes($row['Prenom'])."</option>\n";
								}
							 }
						 ?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Surveillant : ";}else{echo "Supervisor : ";}?></td>
				<td>
					<select name="surveillant" style="width:190px;">
						<?php
						$req = "SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom
									FROM soda_surveillance 
									LEFT JOIN new_rh_etatcivil
									ON soda_surveillance.Id_Surveillant=new_rh_etatcivil.Id
									WHERE soda_surveillance.Suppr=0 
									AND Id_Surveillant>0
									AND Id_Surveillant<>6572
									ORDER BY Nom, Prenom;";
						$resultSurveillant=mysqli_query($bdd,$req);
						$nbSurveillant=mysqli_num_rows($resultSurveillant);
						
						$SurveillantSelect = $_SESSION['FiltreSODAConsult_Surveillant'];
						if($_POST){$SurveillantSelect=$_POST['surveillant'];}
						$_SESSION['FiltreSODAConsult_Surveillant']=$SurveillantSelect;
						$Selected = "";
						echo "<option name='0' value='0' Selected></option>";
						if ($nbSurveillant > 0)
						{
							while($row=mysqli_fetch_array($resultSurveillant))
							{
								$selected="";
								if($SurveillantSelect==$row['Id']){$selected="selected";}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Nom'])." ".stripslashes($row['Prenom'])."</option>\n";
							}
						 }
						
						 ?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle" colspan="2">
					<?php
					$SurveillanceInfObjectif = $_SESSION['FiltreSODAConsult_InfObjectif'];
					if($_POST){if(isset($_POST['SurveillanceInfObjectif'])){$SurveillanceInfObjectif="checked";}else{$SurveillanceInfObjectif="";}}
					$_SESSION['FiltreSODAConsult_InfObjectif']=$SurveillanceInfObjectif;
					?>
					<?php if($_SESSION['Langue']=="FR"){echo "Surveillance < Objectif : ";}else{echo "Monitoring < Objective : ";}?>
					<input type="checkbox" name="SurveillanceInfObjectif" <?php if($SurveillanceInfObjectif<>""){echo "checked";} ?>>
				</td>
				<td></td>
				<td class="Libelle" colspan="2">
					<?php
					$MonPerimetre = $_SESSION['FiltreSODAConsult_MonPerimetre'];
					if($_POST){if(isset($_POST['MonPerimetre'])){$MonPerimetre="checked";}else{$MonPerimetre="";}}
					$_SESSION['FiltreSODAConsult_MonPerimetre']=$MonPerimetre;
					?>
					<?php if($_SESSION['Langue']=="FR"){echo "Mon périmètre : ";}else{echo "My perimeter : ";}?>
					<input type="checkbox" name="MonPerimetre" <?php if($MonPerimetre<>""){echo "checked";} ?>>
				</td>
				<td width="15%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Etat : ";}else{echo "State : ";}?></td>
				<td width="20%">
					<select class="etat" name="etat" style="width:150px;">
						<?php
						$EtatSelect = $_SESSION['FiltreSODAConsult_Etat'];
						if($_POST){$EtatSelect=$_POST['etat'];}
						$_SESSION['FiltreSODAConsult_Etat']=$EtatSelect;
						$Selected = "";
						?>
						<option value="" selected></option>
						<option value="A VALIDER" <?php if($EtatSelect=="A VALIDER"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "A Valider";}else{echo "Validate";}?></option>
						<option value="Brouillon" <?php if($EtatSelect=="Brouillon"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "Brouillon";}else{echo "Draft";}?></option>
						<option value="Clôturé" <?php if($EtatSelect=="Clôturé"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "Clôturé";}else{echo "Closed";}?></option>
						<option value="En cours - papier" <?php if($EtatSelect=="En cours - papier"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "En cours - papier";}else{echo "In progress - paper";}?></option>
					</select>
				</td>
				<td align="right" colspan="6">
					<a style="text-decoration:none;" href="javascript:OuvreFenetreExcel('Surveillance')">
						<?php if($_SESSION['Langue']=="FR"){echo "Export Surveillances";}else{echo "Export Surveillances";}?>
					</a>
					<br>
					<a style="text-decoration:none;" href="javascript:OuvreFenetreExcel('SurveillanceDetaillee')">
						<?php if($_SESSION['Langue']=="FR"){echo "Export Surveillances détaillées";}else{echo "Export Detailed Surveys";}?>
					</a>
					
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle" colspan="5">
					<?php
					$SurveillanceNCActionAT = $_SESSION['FiltreSODAConsult_NCActionAT'];
					if($_POST){if(isset($_POST['SurveillanceNCActionAT'])){$SurveillanceNCActionAT="checked";}else{$SurveillanceNCActionAT="";}}
					$_SESSION['FiltreSODAConsult_NCActionAT']=$SurveillanceNCActionAT;
					?>
					<?php if($_SESSION['Langue']=="FR"){echo "Surveillance avec non conformités (Action immédiate + Action Tracker) : ";}else{echo "Monitoring with non-conformities (Immediate Action + Tracker Action) : ";}?>
					<input type="checkbox" name="SurveillanceNCActionAT" <?php if($SurveillanceNCActionAT<>""){echo "checked";} ?>>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td align="center" colspan="10">
				<input class="Bouton" name="BtnRechercher" type="submit" value="<?php if($_SESSION['Langue']=="FR"){echo "Rechercher";}else{echo "Search";}?>">
				</td>
			</tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<?php
				$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
				$nbAccess=mysqli_num_rows($resAcc);
				
				$req="SELECT Id FROM soda_theme 
					WHERE Suppr=0 
					AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.") ";
				$resAcc=mysqli_query($bdd,$req);
				$nbGestionnaire=mysqli_num_rows($resAcc);
				
				$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
				$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);
				
				$reqSurveillant = "SELECT Id FROM soda_surveillant WHERE Id_Personne=".$_SESSION['Id_Personne']." ";	
				$nbSurveillant=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqSurveillant));
				
				$req2="	SELECT Id,
						(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
						(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,Id_Prestation,EnFormation,AttestationSurveillance,
						IF(Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)) AS Plateforme,
						(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
						YEAR(DateSurveillance) AS Annee,DATE_FORMAT(DateSurveillance,'%u') AS Semaine,DateSurveillance,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,Id_Surveille,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,Id_Surveillant,
						(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS SeuilReussite,
						soda_surveillance.Etat,NumActionTracker,
						ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100) AS Resultat ";
				$req = "FROM soda_surveillance 
						WHERE Suppr=0 
						AND AutoSurveillance=0 
						AND Etat IN ('Clôturé','En cours - papier','Brouillon') ";
				if($numSurveillance <> "")
				{
					$req .= "AND Id =".$numSurveillance." ";
				}
				else
				{
					if ($PlateformeSelect <> 0){$req .= "AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$PlateformeSelect." OR Id_Plateforme=".$PlateformeSelect.") ";}
					if($prestationA=="1" && $prestationI=="0"){$req.=" AND ((SELECT Active FROM new_competences_prestation WHERE Id=Id_Prestation)=0 OR Id_Plateforme>0) ";}
					elseif($prestationA=="0" && $prestationI=="1"){$req.=" AND ((SELECT Active FROM new_competences_prestation WHERE Id=Id_Prestation)=-1 OR Id_Plateforme>0) ";}
					elseif($prestationA=="0" && $prestationI=="0"){$req.=" AND ((SELECT Active FROM new_competences_prestation WHERE Id=Id_Prestation)=0 OR Id_Plateforme>0) ";}
					if ($PrestationSelect <> 0){$req .= "AND soda_surveillance.Id_Prestation =".$PrestationSelect." ";}
					if ($dateDebut <> ""){$req .= "AND soda_surveillance.DateSurveillance='".TrsfDate_($dateDebut)."' ";}
					if($annee <> ""){$req .= "AND YEAR(soda_surveillance.DateSurveillance) ='".$annee."' ";}
					if($EtatSelect <> ""){
						if($EtatSelect == "A VALIDER"){
							$req .= "AND soda_surveillance.Etat ='Clôturé' AND EnFormation=1 AND AttestationSurveillance=0 ";
						}
						else{
							$req .= "AND soda_surveillance.Etat ='".$EtatSelect."' ";
						}
					}
					if ($ThemeSelect <> 0)
					{
						$req .= "AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) =".$ThemeSelect." ";
						if($QuestionnaireSelect <> 0){$req .= "AND Id_Questionnaire =".$QuestionnaireSelect." ";}
					}
					if ($SurveilleSelect <> 0){$req .= "AND soda_surveillance.Id_Surveille =".$SurveilleSelect." ";}
					if ($SurveillantSelect <> 0){$req .= "AND soda_surveillance.Id_Surveillant =".$SurveillantSelect." ";}
					if($ATNonRenseigne<>""){$req .= "AND soda_surveillance.NumActionTracker ='' ";}
					if ($numAT <> ""){
						$req .= "AND soda_surveillance.NumActionTracker ='".$numAT."' ";
					}
					elseif($SurveillanceInfObjectif<>""){
						$req .= "AND ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)<(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AND Etat='Clôturé' ";
					}
					if($SurveillanceNCActionAT<>""){
						$req .= "AND (SELECT COUNT(soda_surveillance_question.Id) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='NC' AND Action='Action immédiate + Action Tracker')>0 ";
					}
					if($MonPerimetre<>""){
						if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
							
						}
						else{
							$req.="AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (
								SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
								AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
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
					}
				}

				$reqOrder="";
				if($_SESSION['TriConsultSODA_General']<>""){
					$reqOrder="ORDER BY ".substr($_SESSION['TriConsultSODA_General'],0,-1);
				}
				
				$reqAnalyse="SELECT soda_surveillance.Id ";
				$resultSurveillance=mysqli_query($bdd,$reqAnalyse.$req);
				$nbSurveillance=mysqli_num_rows($resultSurveillance);

				$nombreDePages=ceil($nbSurveillance/50);
				if(isset($_GET['Page'])){$_SESSION['Page']=$_GET['Page'];}
				if($_SESSION['Page']>$nombreDePages){$_SESSION['Page']="0";}
				$reqLimit=" LIMIT ".($_SESSION['Page']*50).",50";

				$resultSurveillance=mysqli_query($bdd,$req2.$req.$reqOrder.$reqLimit);
				$nbSurveillance=mysqli_num_rows($resultSurveillance);
				
				$nbPage=0;
				if($_SESSION['Page']>1){echo "<b> <a style='color:#00599f;' href='Tableau_De_Bord.php?Menu=8&Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['Page']<=5){$valeurDepart=1;}
				elseif($_SESSION['Page']>=($nombreDePages-6)){$valeurDepart=$nombreDePages-6;}
				else{$valeurDepart=$_SESSION['Page']-5;}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++)
				{
					if($i<=$nombreDePages)
					{
						if($i==($_SESSION['Page']+1)){echo "<b> [ ".$i." ] </b>";}	
						else{echo "<b> <a style='color:#00599f;' href='Tableau_De_Bord.php?Menu=8&Page=".($i-1)."'>".$i."</a> </b>&nbsp;";}
					}
				}
				if($_SESSION['Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Tableau_De_Bord.php?Menu=8&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="5%">
					<a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=Id"><?php if($_SESSION["Langue"]=="FR"){echo "N°";}else{echo "N°";}?><?php if($_SESSION['TriConsultSODA_Id']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_Id']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=Plateforme"><?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?><?php if($_SESSION['TriConsultSODA_Plateforme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_Plateforme']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?><?php if($_SESSION['TriConsultSODA_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=Theme"><?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?><?php if($_SESSION['TriConsultSODA_Theme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_Theme']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="20%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=Questionnaire">Questionnaire<?php if($_SESSION['TriConsultSODA_Questionnaire']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_Questionnaire']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="6%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=Etat"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";}?><?php if($_SESSION['TriConsultSODA_Etat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_Etat']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="5%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=Resultat"><?php if($_SESSION["Langue"]=="FR"){echo "Note";}else{echo "Score";}?><?php if($_SESSION['TriConsultSODA_Resultat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_Resultat']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="7%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=NumActionTracker"><?php if($_SESSION["Langue"]=="FR"){echo "N° Action Tracker";}else{echo "Action Tracker number";}?><?php if($_SESSION['TriConsultSODA_NumActionTracker']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_NumActionTracker']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=DateSurveillance">Date<?php if($_SESSION['TriConsultSODA_DateSurveillance']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_DateSurveillance']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="13%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=Surveille"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillé";}else{echo "Supervised";}?><?php if($_SESSION['TriConsultSODA_Surveille']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_Surveille']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="13%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Tableau_De_Bord.php?Menu=8&Tri=Surveillant"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillant";}else{echo "Supervisor";}?><?php if($_SESSION['TriConsultSODA_Surveillant']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriConsultSODA_Surveillant']=="ASC"){echo "&darr;";}?></a></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="3%"></td>
				</tr>
				<?php
					if($nbSurveillance > 0)
					{
						$semaine=date('Y')."S";
						if(date('W')<10){$semaine.="0".date('W');}
						else{$semaine.=date('W');}
						
						$couleur="#ffffff";
						while($rowSurveillance=mysqli_fetch_array($resultSurveillance))
						{
							$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
							if($presta==""){$presta=$rowSurveillance['Prestation'];}
							
							echo "<tr style='background-color:".$couleur."' >";
							echo "<td>";
							
							if($AccesQualite 
							|| DroitsFormationPrestationV2(array($rowSurveillance['Id_Prestation']),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite)) 
							|| $nbAccess>0 
							|| $nbSuperAdmin>0 
							|| $nbGestionnaire>0	
							|| $rowSurveillance['Id_Surveillant']==$_SESSION['Id_Personne']
							|| ($rowSurveillance['Etat']=="Clôturé" && $rowSurveillance['Id_Surveillant']<>$_SESSION['Id_Personne'] && $rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0)
							){
								echo '<a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(\'Modif_Surveillance.php\',\'M\','.$rowSurveillance['Id'].');">';
							}
							echo $rowSurveillance['Id'];
							if($AccesQualite 
							|| DroitsFormationPrestationV2(array($rowSurveillance['Id_Prestation']),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite)) 
							|| $nbAccess>0 
							|| $nbSuperAdmin>0 
							|| $nbGestionnaire>0	
							|| $rowSurveillance['Id_Surveillant']==$_SESSION['Id_Personne']
							|| ($rowSurveillance['Etat']=="Clôturé" && $rowSurveillance['Id_Surveillant']<>$_SESSION['Id_Personne'] && $rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0)
							){
								echo '</a>';
							}
							
							$etat=$rowSurveillance['Etat'];
							if($_SESSION["Langue"]=="EN"){
								if($rowSurveillance['Etat']=="Clôturé"){
									$etat="Closed";
									if($rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0){
										$etat="Validate";
									}
								}
								if($rowSurveillance['Etat']=="En cours - papier"){$etat="In progress - paper";}
								if($rowSurveillance['Etat']=="Brouillon"){$etat="Draft";}
							}
							else{
								if($rowSurveillance['Etat']=="Clôturé"){
									if($rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0){
										$etat="A Valider";
									}
								}
							}
							echo "</td>";
							echo "<td>".$rowSurveillance['Plateforme']."</td>";
							echo "<td>".$presta."</td>";
							echo "<td>".$rowSurveillance['Theme']."</td>";
							echo "<td>".$rowSurveillance['Questionnaire']."</td>";
							echo "<td>".$etat."</td>";
							if($rowSurveillance['Etat']=="Clôturé"){
								if($rowSurveillance['Resultat']<$rowSurveillance['SeuilReussite']){
									if($rowSurveillance['Resultat']==""){
										echo "<td>N/A</td>";
									}
									else{
										echo "<td style='color:red;'>".$rowSurveillance['Resultat']." %</td>";
									}
								}
								else{
									echo "<td>".$rowSurveillance['Resultat']." %</td>";
								}
							}
							else{
								echo "<td></td>";
							}
							echo "<td>".$rowSurveillance['NumActionTracker']."</td>";
							echo "<td>".AfficheDateJJ_MM_AAAA($rowSurveillance['DateSurveillance'])."</td>";
							echo "<td>".$rowSurveillance['Surveille']."</td>";
							echo "<td>".$rowSurveillance['Surveillant']."</td>";
							if($rowSurveillance['Etat']=="Clôturé"){
								echo "<td><a href='javascript:SurveillancePDF(".$rowSurveillance['Id'].");'><img src='../../Images/pdf.png' border='0' alt='PDF' width='14'></a></td>";
							}
							else{
								echo "<td><a href='javascript:SurveillanceExcel(".$rowSurveillance['Id'].");'><img src='../../Images/excel.gif' border='0' alt='Excel' width='14'></a></td>";
							}
							echo "<td>";
							if($nbSuperAdmin>0 || ($rowSurveillance['Id_Surveillant']==$_SESSION['Id_Personne'] && $rowSurveillance['Etat']<>"Clôturé")){
								echo "<a href=\"javascript:OuvreFenetreModif('Modif_Surveillance.php','S','".$rowSurveillance['Id']."');\"><img src='../../Images/Suppression.gif' border='0' alt='Suppr' width='14'></a>";
							}
							echo "</td>";
							echo "</tr>";
							
							if($couleur=="#ffffff"){$couleur="#b7dfe3";}
							else{$couleur="#ffffff";}
						}
					}
				?>
			</table>
		</td>
	</tr>
	<tr><td height="150"></td></tr>
</table>
</body>
</html>