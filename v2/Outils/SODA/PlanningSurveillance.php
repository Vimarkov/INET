<?php
$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbAccess=mysqli_num_rows($resAcc);

$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

$AccesQualite=false;
if($nbAccess>0 || $nbSuperAdmin>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentSurveillance)))
	{$AccesQualite=true;}	

Ecrire_Code_JS_Init_Date(); 
$couleurPlanif = "#ffff00";
$couleurRetard = "#ff0000";
$couleurRealise = "#0070c0";
$couleurCloture = "#92d050";
$couleurRetard = "#e9a1ac";
$couleurEC = "#61b4ff";

?>
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%">	
				<tr>
					<td>
						<table width="95%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
							<tr>
								<td width="11%" class="Libelle">
									&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?> :
									<select class="plateforme" name="plateforme" id="plateforme" style="width:150px;" onchange="submit();">
									<?php
									$uer="";
									$reqPlat="SELECT Id, Libelle FROM new_competences_plateforme WHERE Id<>11 AND Id<>14 ";
									if($nbAccess>0 || $nbSuperAdmin>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
										
									}
									else{
										$reqPlat.="AND (Id IN (
											SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentSurveillance.")
										)
										OR 
										Id IN (
											SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
										)
										)";
									}
									$reqPlat.=" ORDER BY Libelle ASC";
									$resultPlateforme=mysqli_query($bdd,$reqPlat);
									$nbPlateforme=mysqli_num_rows($resultPlateforme);
									
									$PlateformeSelect=$_SESSION['FiltreSODA_Plateforme'];
									if($_POST){$PlateformeSelect=$_POST['plateforme'];}
									$_SESSION['FiltreSODA_Plateforme']=$PlateformeSelect;
									
									
									if ($nbPlateforme > 0)
									{
										while($row=mysqli_fetch_array($resultPlateforme))
										{
											if ($_SESSION['FiltreSODA_Plateforme'] == 0){$_SESSION['FiltreSODA_Plateforme'] = $row['Id'];}
											$Selected = "";
											if ($row['Id'] == $_SESSION['FiltreSODA_Plateforme']){
												$Selected = "Selected";
												$uer=$row['Libelle'];
											}
											echo "<option value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
										}
									 }
									 ?>
									</select>
								</td>
								<td width="10%" class="Libelle">
									&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?> :
									<select class="prestation" name="prestations"   style="width:150px;" onchange="submit();">
									<?php
									$req = "SELECT Id, new_competences_prestation.Libelle AS Libelle FROM new_competences_prestation WHERE 
									SousSurveillance IN ('','Oui/Yes') ";
									$req .= "AND new_competences_prestation.Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']." ";
									if($nbAccess>0 || $nbSuperAdmin>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
										
									}
									else{
										$req.="AND (Id_Plateforme IN (
											SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentSurveillance.")
										)
										OR 
										Id_Plateforme IN (
											SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
											)
										) ";
									}
									$req.=" 
									AND Active=0
									ORDER BY Active DESC, Libelle;";
									$resultPrestation=mysqli_query($bdd,$req);
									$nbPrestation=mysqli_num_rows($resultPrestation);
									
									$PrestationSelect = 0;
									$prestation="";
									$Selected = "";
									
									if ($nbPrestation > 0 && $_SESSION['FiltreSODA_Plateforme'] > 0)
									{
										if (!empty($_POST['prestations'])){
											echo "<option name='0' value='0' Selected></option>";
											if ($PrestationSelect == 0){$PrestationSelect = $_POST['prestations'];}
											while($row=mysqli_fetch_array($resultPrestation))
											{
												if ($row[0] == $_POST['prestations']){
													$Selected = "Selected";
													$prestation=substr($row['Libelle'],0,strpos($row['Libelle']," "));
												}
												echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
												$Selected = "";
											}
										}
										else{
											echo "<option name='0' value='0' selected></option>";
											$PrestationSelect = 0;
											while($row=mysqli_fetch_array($resultPrestation))
											{
												echo "<option name='".$row['Id']."' value='".$row['Id']."' >".$row['Libelle']."</option>";
											}
										}
									 }
									 ?>
									</select>
								</td>
								<td width="40%" class="Libelle">
									&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Date début";}else{echo "Start date";}?> :
									<?php
										$dateDebut=$_SESSION['FiltreSODA_DateDebut'];
										if($_POST){
											$dateDebut=TrsfDate_($_POST['DateDeDebut']);
											if(isset($_POST['MoisPrecedent'])){
												$dateDebut=date("Y-m-d",strtotime($dateDebut." -1 month"));
											}
											elseif(isset($_POST['MoisSuivant'])){
												$dateDebut=date("Y-m-d",strtotime($dateDebut." +1 month"));
											}
										}
										$_SESSION['FiltreSODA_DateDebut']=$dateDebut;
										
										$dateDeFin=$_SESSION['FiltreSODA_DateFin'];
										if($_POST){
											$dateDeFin=TrsfDate_($_POST['DateDeFin']);
											if(isset($_POST['MoisPrecedent'])){
												$dateDeFin=date("Y-m-d",strtotime($dateDeFin." -1 month"));
											}
											elseif(isset($_POST['MoisSuivant'])){
												$dateDeFin=date("Y-m-d",strtotime($dateDeFin." +1 month"));
											}
										}
										$_SESSION['FiltreSODA_DateFin']=$dateDeFin;
										$MoisPrecedent=date("Y-m-d",strtotime($dateDebut." -1 month"));
										$MoisSuivant=date("Y-m-d",strtotime($dateDebut." +1 month"));
									?>
									
									<input type="date" style="text-align:center;" name="DateDeDebut" size="10" value="<?php echo AfficheDateFR($dateDebut); ?>">
									&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Date fin";}else{echo "End date";}?> :
									<input type="date" style="text-align:center;" name="DateDeFin"  size="10" value="<?php echo AfficheDateFR($dateDeFin); ?>">
									<input class="Bouton" name="BtnDateDebut" size="10" type="submit" value="<?php if($_SESSION['Langue']=="FR"){echo "Valider";}else{echo "Validate";}?>">
									&nbsp;
									<input class="Bouton" name="MoisPrecedent" size="10" type="submit" alt="Mois précédent" value="<< <?php echo AfficheDateJJ_MM_AAAA($MoisPrecedent) ; ?>">
									<input class="Bouton" name="MoisSuivant" size="10" type="submit" alt="Mois suivant" value="<?php echo AfficheDateJJ_MM_AAAA($MoisSuivant); ?> >>">
									&nbsp;
									&nbsp;
									&nbsp;
									&nbsp;
									<?php 
									if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentSurveillance))){
									?>
									<input class="Bouton" name="AutoPlannif" size="10" type="Button" onclick="OuvreFenetrePlannifAuto(0,0)" value="<?php if($_SESSION['Langue']=="FR"){echo "Auto Plannif";}else{echo "Auto Schedule";} ?>">
									<?php } ?>
								</td>
								<td width="1%">
								</td>
								<td width="2%">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div style="overflow:auto;height:600px;width:100%;">
			<table>
				<?php
				$anneeATraiter = date("Y",strtotime($_SESSION['FiltreSODA_DateDebut']." +0 month"));
				
				$EnTeteMois = "<th ";
				$EnTeteSemaine = "<th ";
				$EnTeteJourSemaine = "";
				$EnTeteJour = "";
				//Cas Google CHROME
				$tmpDate = date("Y/m/d",strtotime($dateDebut." +0 month"));

				$dateFin = date("Y/m/d", strtotime($dateDeFin." +0 month"));
				$tabDate = explode('/', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
				$tmpMois = date('n', $timestamp) . ' ' . date('Y', $timestamp);
				$cptMois = 0;
				$cptSemaine = 0;
				$cptJour = 0;
				$cptTotal = 0;
				if($_SESSION['Langue']=="FR")
				{
					$MoisLettre = array("Janv.", "Fev.", "Mars", "Avr.", "Mai", "Juin", "Juil.", "Aout", "Sept.", "Oct.", "Nov.", "Dec.");
				}
				else
				{
					$MoisLettre = array("Jan.", "Feb.", "Mar.", "Apr.", "May", "June", "July", "Aug.t", "Sept.", "Oct.", "Nov.", "Dec.");
				}
				// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
				$premierJour = date("Y/m/d",strtotime($dateDebut." +0 month"));
				
				$tmpDate = date("Y/m/d",strtotime($dateDebut." +0 month"));
				
				$premierJour = date("Y/m/d",strtotime($dateDebut." +0 month"));
				while ($tmpDate <= $dateFin) 
				{
					//Jour suivant
					$lundi=$tmpDate;
					if(date("N",strtotime($lundi." +0 day"))==1){
						$lundi=$lundi;
					}
					else{
						$lundi=date("Y/m/d",strtotime($lundi."last Monday"));
					}
					$jeudi=date("Y/m/d",strtotime($lundi." +3 day"));
					$dimanche=date("Y/m/d",strtotime($lundi." +6 day"));
					//if($premierJour>$lundi){$lundi=$premierJour;}
					if($dateFin<$dimanche){$dimanche=$dateFin;}
					$tabDate = explode('/', $jeudi);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
					$mois = $tabDate[1];
					$semaine = date('W', $timestamp);
					$cptMois++;
					
					$semaineEC="";
					if(date('W')==$semaine){$semaineEC="background-color:#133090;color:#ffffff;";}
					$EnTeteSemaine .= " class='EnTeteSemaine' style='top:0;position: sticky;".$semaineEC."' colspan=2  width='55px'>S".$semaine."</th><th ";
					
					//Jour suivant
					$tmpDate2=$dimanche;
					$tmpDate2=date("Y/m/d",strtotime($tmpDate2." +1 day"));
					$tabDate2 = explode('/', $tmpDate2);
					
					$jeudiSuivant=date("Y/m/d",strtotime($tmpDate2." +3 day"));
					$tabJeudiSuivant = explode('/', $jeudiSuivant);
					
					if ($tabJeudiSuivant[1] <> $tabDate[1] && $tmpDate2 <= $dateFin)
					{
						$cptMois = $cptMois * 2;
						$EnTeteMois .= " class='EnTeteMois' style='top:0;position: sticky;' colspan=".$cptMois.">".$MoisLettre[$mois-1]." ".$tabDate[0]."</th><th ";
						$cptMois = 0;
					}
					$cptJour++;
					//Jour suivant
					$tmpDate=$dimanche;
					$tmpDate=date("Y/m/d",strtotime($tmpDate." +1 day"));
					
				}
				$cptMois = $cptMois * 2;
				$tabDate = explode('/', $jeudi);
				$mois = $tabDate[1];
				$EnTeteMois .= " class='EnTeteMois' style='top:0;position: sticky;' colspan=".$cptMois.">".$MoisLettre[$mois-1]." ".$tabDate[0]."</th>";

				?>
				<thead align="center">
					<th class="Libelle" style="top:0;position: sticky;" width="200px" colspan='3' align="center" valign="center">
						<table align="center">
							<tr>
								<td  class="Libelle" style="background-color:<?php echo $couleurPlanif; ?>;top:0;position: sticky;" width="200px" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Planifié";}else{echo "Planed";}?></th>
								<td  class="Libelle" style="background-color:<?php echo $couleurCloture; ?>;top:0;position: sticky;" width="200px" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Clôturé";}else{echo "Closed";}?></th>
							</tr>
							<tr>
								<td  class="Libelle" style="background-color:<?php echo $couleurEC; ?>;top:0;position: sticky;" width="200px" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Brouillon/En cours - papier";}else{echo "Draft/In progress - paper";}?></th>
								<td  class="Libelle" style="background-color:<?php echo $couleurRetard; ?>;top:0;position: sticky;" width="200px" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Retard";}else{echo "Delay";}?></th>
							</tr>
						</table>
					</th>
					<?php echo $EnTeteMois ;?>
					<?php 
					if($PrestationSelect>0){
					?>
						<th  class="EnTeteMois" width="100px" align="center" valign="center" style="top:0;position: sticky;" colspan="2"><?php echo $prestation." ".$anneeATraiter; ?></th>
					<?php
					}
					?>
					<th  class="EnTeteMois" width="400px" align="center" valign="center" style="top:0;position: sticky;" colspan="6"><?php echo $uer." ".$anneeATraiter; ?></th>
				</thead>
				<thead align="center">
					<th class="EnTeteSemaine" style="font-size:12px;width:200px;top:0;position: sticky;" ><?php if($_SESSION['Langue']=="FR"){echo "Thème";}else{echo "Theme";}?></th>
					<th colspan="2" class="EnTeteSemaine" style="font-size:12px;width:400px;top:0;position: sticky;" >Questionnaire</th>
					<?php echo $EnTeteSemaine ;?>
					<?php 
					if($PrestationSelect>0){
					?>
						<th  class="EnTeteMois" width="50px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Volume planifié";}else{echo "Planned volume";}?></th>
						<th  class="EnTeteMois" width="50px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Volume réalisé";}else{echo "Volume achieved";}?></th>
					<?php
					}
					?>
					<th  class="EnTeteMois" width="50px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Objectif volume";}else{echo "Volume goal";}?></th>
					<th  class="EnTeteMois" width="50px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Volume planifié";}else{echo "Planned volume";}?></th>
					<th  class="EnTeteMois" width="50px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Volume réalisé";}else{echo "Volume achieved";}?></th>
					<th  class="EnTeteMois" width="50px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Objectif presta différentes";}else{echo "Different site objective";}?></th>
					<th  class="EnTeteMois" width="50px"style="top:0;position: sticky;"  align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Presta différentes planifiées";}else{echo "Different scheduled stes";}?></th>
					<th  class="EnTeteMois" width="50px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Presta différentes réalisées";}else{echo "Different services provided";}?></th>
				</thead>
				<tbody>
					<?php
					// FIN GESTION DES ENTETES DU TABLEAU
					
					//DEBUT CORPS DU TABLEAU
					$tmpDate = date("Y/m/d",strtotime($dateDebut." +0 month"));
					$dateFin = date("Y/m/d", strtotime($dateDeFin." +0 month"));
					
					$ldateFin  = date("Y-m-d", strtotime($dateDeFin." +0 month"));
					
					//Liste des questionnaires
					$req = "SELECT Id,Libelle,Id_Theme,Specifique,
							(SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) AS Theme,
							(SELECT COUNT(soda_theme.Id)
							FROM soda_theme 
							WHERE Suppr=0 
							AND soda_theme.Id=soda_questionnaire.Id_Theme
							AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")
							) AS NbTheme
							FROM soda_questionnaire
							WHERE Suppr=0 
							AND Actif=0 
							ORDER BY Theme,Specifique,Libelle";
					$resultQuestionnaire=mysqli_query($bdd,$req);
					$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
					$Id_Theme=0;
					$Id_Theme2=0;
					if ($nbQuestionnaire > 0){
						$couleurQuestionnaire = "bgcolor=#548FFB";
						while($row=mysqli_fetch_array($resultQuestionnaire)){
							$Id_Questionnaire = $row['Id'];

							$ligne1 = "<tr>";
							$ligne2 = "<tr>";
							
							$OnClickAjoutSurveillance="";
							if($AccesQualite && $row['Id_Theme']<>8)
							{
								$OnClickAjoutSurveillance="style='cursor:pointer;' onclick=\"OuvreFenetrePlannifAuto('".$row['Id_Theme']."','".$Id_Questionnaire."');\"";
							}
							//Nb questionnaires
							$req = "SELECT Id 
									FROM soda_questionnaire
									WHERE Suppr=0 
									AND Actif=0 
									AND Id_Theme=".$row['Id_Theme']." ";
							$resultQuestionnaire2=mysqli_query($bdd,$req);
							$nbQuestionnaire2=mysqli_num_rows($resultQuestionnaire2);
							$rowspan=$nbQuestionnaire2*2;
							
							if($Id_Theme2<>$row['Id_Theme']){
								$ligne1 .= "<td rowspan='".$rowspan."' ".$couleurQuestionnaire." ".$OnClickAjoutSurveillance."  width='200px' height='30px'>".$row['Theme']."</td>";
								$Id_Theme2=$row['Id_Theme'];
							}
							$ligne1 .= "<td rowspan='2' width='400px' colspan='2' height='30px' ".$couleurQuestionnaire.">";
							if($row['Specifique']==0){$ligne1 .= "[Géné] ";}
							else{$ligne1 .= "[Spec] ";}
							$ligne1 .= $row['Libelle']."</td>";
							
							if ($couleurQuestionnaire == "bgcolor=#347afa"){
								
								$couleurQuestionnaire = "bgcolor=#548FFB";
							}
							else{
								$couleurQuestionnaire = "bgcolor=#347afa";
							}
							
							$tmpDate = date("Y/01/01",strtotime($dateDebut." +0 month"));
							$premierJour = date("Y/m/d",strtotime($dateDebut." +0 month"));
							$width=30;
							$semaine2=date('Y')."S";
							if(date('W')<10){$semaine2.=date('W');}else{$semaine2.=date('W');}
							
							while ($tmpDate <= $dateFin) {
								$tabDate = explode('/', $tmpDate);
								$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
								$annee = date('Y', $timestamp);
								$semaine = date('W', $timestamp);
								$dateAffichage = date("d/m/Y",$timestamp);
								$dateAffichage2 = date("Y-m-d",$timestamp);
								$class="semaine";

								//Recherche si planning pour ce jour-ci
								$nbPlanif=0;
								$nbCloture=0;
								$nbRetard=0;
								$nbEC=0;
								$nbCellule = 0;
								$info="";
								$infoCloture="";
								
								$lundi=$tmpDate;
								if(date("N",strtotime($lundi." +0 day"))==1){
									$lundi=$lundi;
								}
								else{
									$lundi=date("Y/m/d",strtotime($lundi."last Monday"));
								}
								$dimanche=date("Y/m/d",strtotime($lundi." +6 day"));
								if($dateFin<$dimanche){$dimanche=$dateFin;}
								
								//Liste des surveillances planifiées
								$req = "SELECT Id_Questionnaire,Id_Prestation,Annee,Semaine,
										IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) AS Id_Plateforme,
										(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
										Volume-(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND Etat IN ('Clôturé','En cours - papier','Brouillon') AND AutoSurveillance=0 AND Id_PlannifManuelle=soda_plannifmanuelle.Id ) AS Volume
										FROM soda_plannifmanuelle 
										WHERE Annee=".date("Y",strtotime($lundi." +0 day"))."
										AND Semaine=".date("W",strtotime($lundi." +0 day"))."
										AND Id_Questionnaire=".$Id_Questionnaire."
										";
								if ($_SESSION['FiltreSODA_Plateforme'] > 0){
									$req .= "AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$_SESSION['FiltreSODA_Plateforme']." ";
								}
								if ($PrestationSelect > 0){
									$req .= "AND Id_Prestation =".$PrestationSelect." ";
								}
								if($nbAccess>0 || $nbSuperAdmin>0  || $nbGestionnaire>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
									
								}
								else{
									$req.="AND (
										IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
											SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentSurveillance.")
										)
										OR 
										IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
											SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
										)
									) ";
								}
								$req.="ORDER BY Prestation";
								
								$resultSurveillance=mysqli_query($bdd,$req);
								$nbSurveillance=mysqli_num_rows($resultSurveillance);
								
								if ($nbSurveillance>0){
									while($rowSurveillance=mysqli_fetch_array($resultSurveillance)) {
										$lasemaine=$rowSurveillance['Annee']."S";
										if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
										
										$volume=$rowSurveillance['Volume'];
										if($semaine2>$lasemaine){
											$nbRetard+=$volume;
										}
										else{
											$nbPlanif+=$volume;
										}
										if($volume>0){
											if($rowSurveillance['Id_Prestation']>0){
												$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
												if($presta==""){$presta=$rowSurveillance['Prestation'];}
												$info.="<B>".$presta."</B> : ".$rowSurveillance['Volume']." <br>";
											}
										}
									}
									if($nbRetard>0 || $nbPlanif>0){$nbCellule=1;}
								}
								
								
								if($tmpDate>=date("Y/m/d",strtotime($dateDebut." +0 month")) || 
								date("Y-W",strtotime($tmpDate." +0 month"))==date("Y-W",strtotime($dateDebut." +0 month"))){
									//Liste des surveillances clôturés
									$req = "SELECT Id
											FROM soda_surveillance 
											WHERE Suppr=0 
											AND AutoSurveillance=0
											AND Etat='Clôturé'
											AND YEAR(DateSurveillance)=".date("Y",strtotime($lundi." +0 day"))."
											AND DATE_FORMAT(DateSurveillance,'%u')=".date("W",strtotime($lundi." +0 day"))."
											AND Id_Questionnaire=".$Id_Questionnaire."
											";
									if ($_SESSION['FiltreSODA_Plateforme'] > 0){
										$req .= "AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$_SESSION['FiltreSODA_Plateforme']." ";
									}
									if ($PrestationSelect > 0){
										$req .= "AND Id_Prestation =".$PrestationSelect." ";
									}
									if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
										
									}
									else{
										$req.="AND (IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
											SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentSurveillance.")
										)
										OR 
										IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
											SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
											)
										) ";
									}
									$resultCloture=mysqli_query($bdd,$req);
									$nbCloture=mysqli_num_rows($resultCloture);
									if($nbCloture>0){$nbCellule++;}
									
									//Liste des surveillances en cours
									$req = "SELECT Id
											FROM soda_surveillance 
											WHERE Suppr=0 
											AND AutoSurveillance=0
											AND Etat IN ('En cours - papier','Brouillon')
											AND YEAR(DateSurveillance)=".date("Y",strtotime($lundi." +0 day"))."
											AND DATE_FORMAT(DateSurveillance,'%u')=".date("W",strtotime($lundi." +0 day"))."
											AND Id_Questionnaire=".$Id_Questionnaire."
											";
									if ($_SESSION['FiltreSODA_Plateforme'] > 0){
										$req .= "AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$_SESSION['FiltreSODA_Plateforme']." ";
									}
									if ($PrestationSelect > 0){
										$req .= "AND Id_Prestation =".$PrestationSelect." ";
									}
									if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
										
									}
									else{
										$req.="AND (IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
											SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentSurveillance.")
										)
										OR 
										IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
											SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
											)
										) ";
									}
									$resultEC=mysqli_query($bdd,$req);
									$nbEC=mysqli_num_rows($resultEC);
									if($nbEC>0){$nbCellule++;}
									
									//Liste des surveillances clôturés
									$req = "SELECT COUNT(Id) AS Nb,
											(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
											FROM soda_surveillance 
											WHERE Suppr=0 
											AND AutoSurveillance=0
											AND Etat='Clôturé'
											AND YEAR(DateSurveillance)=".date("Y",strtotime($lundi." +0 day"))."
											AND DATE_FORMAT(DateSurveillance,'%u')=".date("W",strtotime($lundi." +0 day"))."
											AND Id_Questionnaire=".$Id_Questionnaire."
											";
									if ($_SESSION['FiltreSODA_Plateforme'] > 0){
										$req .= "AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$_SESSION['FiltreSODA_Plateforme']." ";
									}
									if ($PrestationSelect > 0){
										$req .= "AND Id_Prestation =".$PrestationSelect." ";
									}
									if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
										
									}
									else{
										$req.="AND (IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
											SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentSurveillance.")
										)
										OR 
										IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
											SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
											)
										) ";
									}
									$req.=" 
									GROUP BY Id_Prestation
									ORDER BY Prestation";
									$resultClotureListe=mysqli_query($bdd,$req);
									$nbClotureListe=mysqli_num_rows($resultClotureListe);
									if($nbClotureListe>0){
										while($rowClotureListe=mysqli_fetch_array($resultClotureListe)) {
											$presta=substr($rowClotureListe['Prestation'],0,strpos($rowClotureListe['Prestation']," "));
											if($presta==""){$presta=$rowClotureListe['Prestation'];}
											$infoCloture.="<B>".$presta."</B> : ".$rowClotureListe['Nb']." <br>";
										}
									}
									
									$OnClickAjoutSurveillance="";
									if($nbAccess>0 || $nbSuperAdmin>0 || $row['NbTheme']>0 || DroitsFormation1Plateforme($PlateformeSelect,array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteChargeMissionOperation,$IdPosteReferentSurveillance))
									|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite)))
									{
										$parametreAjoutSurveillance=",'".$row['Id_Theme']."','".$row['Id']."'";
										if($row['Id_Theme']==8){
											$OnClickAjoutSurveillance="onclick=\"OuvreFenetrePlannifManuelleP('".$annee."','".$semaine."'".$parametreAjoutSurveillance.");\"";
										}
										else{
											$OnClickAjoutSurveillance="onclick=\"OuvreFenetrePlannifManuelle('".$annee."','".$semaine."'".$parametreAjoutSurveillance.");\"";
										}
									}
									if ($nbCellule == 1){
										if ($nbPlanif > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
										}
										elseif($nbRetard > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$info."</span>\n</td>";
										}
										elseif($nbCloture > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
										}
										elseif($nbEC > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
										}
										else{
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' >PB</td>";
										}
									}
									elseif ($nbCellule == 2){
										if ($nbPlanif > 0 && $nbRetard > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='28px' rowspan='2' height='30px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='27px' rowspan='2' height='30px' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$info."</span>\n</td>";
										}
										elseif ($nbPlanif > 0 && $nbCloture > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
											$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
										}
										elseif ($nbPlanif > 0 && $nbEC > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
											$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
										}
										elseif ($nbRetard > 0 && $nbCloture > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$info."</span>\n</td>";
											$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
										}
										elseif ($nbRetard > 0 && $nbEC > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$info."</span>\n</td>";
											$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
										}
										elseif ($nbCloture > 0 && $nbEC > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
											$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
										}
										else{
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' ></td>";
										}
									}
									elseif ($nbCellule == 3){
										if ($nbPlanif > 0 && $nbRetard > 0 && $nbCloture > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='28px' height='30px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='27px' height='30px' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$info."</span>\n</td>";
											$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
										}
										elseif ($nbPlanif > 0 && $nbRetard > 0 && $nbEC > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='28px' height='30px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='27px' height='30px' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$info."</span>\n</td>";
											$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
										}
										elseif ($nbPlanif > 0 && $nbCloture > 0 && $nbEC > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='28px' height='30px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
											$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
										}
										elseif ($nbRetard > 0 && $nbCloture > 0 && $nbEC > 0){
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='27px' height='30px' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$info."</span>\n</td>";
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
											$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
										}
										else{
											$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' ></td>";
										}
									}
									else{
										$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' class='".$class."'><br></td>";
									}
								}
								//Jour suivant
								$tmpDate=$dimanche;
								$tmpDate=date("Y/m/d",strtotime($tmpDate." +1 day"));
							}
							if($row['Specifique']==1 && $row['Id_Theme']<>8){
								$ligne1.="<td bgcolor='#e9e9e9' style='font-size:11px;width:400px;' height='30px' colspan='6' rowspan='2' align='center'></td>";
							}
							else{
								if($row['Id_Theme']<>8){
									if($Id_Theme<>$row['Id_Theme']){
										//Nb questionnaires
										$req = "SELECT Id 
												FROM soda_questionnaire
												WHERE Suppr=0 
												AND Actif=0 
												AND Id_Theme=".$row['Id_Theme']." 
												AND Specifique=0";
										$resultQuestionnaire2=mysqli_query($bdd,$req);
										$nbQuestionnaire2=mysqli_num_rows($resultQuestionnaire2);
										
										//Nb questionnaires all
										$req = "SELECT Id 
												FROM soda_questionnaire
												WHERE Suppr=0 
												AND Actif=0 
												AND Id_Theme=".$row['Id_Theme']." ";
										$resultQuestionnaireAll2=mysqli_query($bdd,$req);
										$nbQuestionnaireAll2=mysqli_num_rows($resultQuestionnaireAll2);
										
										$pourcentageApplicabilite=0;
										$pourcentageDiversite=0;
										$req="SELECT PourcentageApplicabilite, PourcentageDiversite
										FROM soda_objectif_theme
										WHERE Annee=".$anneeATraiter." 
										AND Id_Theme=".$row['Id_Theme']." ";
										$result=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($result);
										if ($nb > 0)
										{
											$rowT=mysqli_fetch_array($result);
											$pourcentageApplicabilite=$rowT['PourcentageApplicabilite']/100;
											$pourcentageDiversite=$rowT['PourcentageDiversite']/100;
										}
										
										$req="SELECT Id,Libelle
											FROM new_competences_prestation
											WHERE Id_Plateforme NOT IN (11,14)
											AND SousSurveillance IN ('','Oui/Yes')
											AND Active=0 
											AND Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']." ";
										$resultPresta=mysqli_query($bdd,$req);
										$nbPresta=mysqli_num_rows($resultPresta);
										
										$req="SELECT SUM(Volume) AS Vol
										FROM soda_plannifmanuelle 
										WHERE Annee=".$anneeATraiter." 
										AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$row['Id_Theme']." AND Specifique=0) 
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_SESSION['FiltreSODA_Plateforme']." ";
										$resultVolumePlanifie=mysqli_query($bdd,$req);
										$nbVolumePlanifie=mysqli_num_rows($resultVolumePlanifie);
										
										$volumeObjectif=round(($nbPresta*$pourcentageApplicabilite),0);
										
										$volumePlanifie=0;
										if ($nbVolumePlanifie > 0)
										{
											$rowP=mysqli_fetch_array($resultVolumePlanifie);
											$volumePlanifie=$rowP['Vol'];
										}
										
										$deltaPlanifie=$volumePlanifie-$volumeObjectif;
										if($deltaPlanifie>=0){$styleDP="background-color:#68b30f;";}
										else{$styleDP="background-color:#f1696d;";}
										
										$req="SELECT DISTINCT Id_Prestation
										FROM soda_plannifmanuelle 
										WHERE Annee=".$anneeATraiter." 
										AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$row['Id_Theme']." AND Specifique=0)  
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_SESSION['FiltreSODA_Plateforme']." ";
										$resultPrestaDistinct=mysqli_query($bdd,$req);
										$nbPrestaDistinct=mysqli_num_rows($resultPrestaDistinct);
										
										$objectifDiversite=round(($nbPresta*$pourcentageDiversite),0);
										
										$deltaDiversite=$nbPrestaDistinct-$objectifDiversite;
										if($deltaDiversite>=0){$styleD="background-color:#68b30f;";}
										else{$styleD="";}
										
										//Liste des surveillances clôturés de l'année
										$req = "SELECT Id
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND YEAR(DateSurveillance)=".date("Y",strtotime($lundi." +0 day"))."
												AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$row['Id_Theme']." AND Specifique=0) 
												";
										if ($_SESSION['FiltreSODA_Plateforme'] > 0){
											$req .= "AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) =".$_SESSION['FiltreSODA_Plateforme']." ";
										}
										$resultCloture=mysqli_query($bdd,$req);
										$nbCloture=mysqli_num_rows($resultCloture);
										
										$deltaRealisePlanifie=$volumeObjectif-$nbCloture;
										if($deltaRealisePlanifie>0){$styleDR="";}
										else{$styleDR="background-color:#68b30f;";}
										
										//Liste des surveillances clôturés de l'année sur prestations distinctes
										$req = "SELECT DISTINCT Id_Prestation
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND YEAR(DateSurveillance)=".date("Y",strtotime($lundi." +0 day"))."
												AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$row['Id_Theme']." AND Specifique=0) 
												";
										if ($_SESSION['FiltreSODA_Plateforme'] > 0){
											$req .= "AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) =".$_SESSION['FiltreSODA_Plateforme']." ";
										}
										$resultCloture=mysqli_query($bdd,$req);
										$nbCloturePresta=mysqli_num_rows($resultCloture);
										
										if($nbCloturePresta>=$objectifDiversite){$styleD2="background-color:#68b30f;";}
										else{$styleD2="";}
										
										$rowspan=$nbQuestionnaire2*2;
										$rowspan2=$nbQuestionnaireAll2*2;
										if($PrestationSelect>0){
											$req="SELECT SUM(Volume) AS Vol
											FROM soda_plannifmanuelle 
											WHERE Annee=".$anneeATraiter." 
											AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$row['Id_Theme'].") 
											AND Id_Prestation=".$PrestationSelect." ";
											
											$resultVolumePlanifie2=mysqli_query($bdd,$req);
											$nbVolumePlanifie2=mysqli_num_rows($resultVolumePlanifie2);
											
											$volumePlanifieP=0;
											if ($nbVolumePlanifie2 > 0)
											{
												$rowP=mysqli_fetch_array($resultVolumePlanifie2);
												$volumePlanifieP=$rowP['Vol'];
											}
											
											//Liste des surveillances clôturés de l'année
											$req = "SELECT Id
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND YEAR(DateSurveillance)=".date("Y",strtotime($lundi." +0 day"))."
													AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$row['Id_Theme'].") 
													AND Id_Prestation =".$PrestationSelect." ";

											$resultCloture=mysqli_query($bdd,$req);
											$nbClotureP=mysqli_num_rows($resultCloture);
											
											$deltaRealisePlanifie2=$volumePlanifieP-$nbClotureP;
											if($deltaRealisePlanifie2>0){$styleDR2="";}
											else{$styleDR2="background-color:#68b30f;";}
											
											$ligne1.="
												<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;' height='30px' rowspan='".$rowspan2."' align='center'>".$volumePlanifieP."</td>
												<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;".$styleDR2."' height='30px' rowspan='".$rowspan2."' align='center'>".$nbClotureP."</td>
												";
										}
										$ligne1.="
										<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;' height='30px' rowspan='".$rowspan."' align='center'>".$volumeObjectif."</td>
										<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;".$styleDP."' height='30px' rowspan='".$rowspan."' align='center'>".$volumePlanifie."</td>
										<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;".$styleDR."' height='30px' rowspan='".$rowspan."' align='center'>".$nbCloture."</td>
										
										<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;' height='30px' rowspan='".$rowspan."' align='center'>".$objectifDiversite."</td>
										<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;".$styleD."' height='30px' rowspan='".$rowspan."' align='center'>".$nbPrestaDistinct."</td>
										<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;".$styleD2."' height='30px' rowspan='".$rowspan."' align='center'>".$nbCloturePresta."</td>
										";
										
										$Id_Theme=$row['Id_Theme'];
									}
								}
								else{
									$volumeObjectif=0;
									$req="SELECT NbSurveillance
									FROM soda_objectif_theme
									WHERE Annee=".$anneeATraiter." 
									AND Id_Questionnaire=".$row['Id']."
									AND Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']."
									";
									$result=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($result);
									if ($nb > 0)
									{
										$rowT=mysqli_fetch_array($result);
										$volumeObjectif=$rowT['NbSurveillance'];
									}
									
									$req="SELECT SUM(Volume) AS Vol
									FROM soda_plannifmanuelle 
									WHERE Annee=".$anneeATraiter." 
									AND Id_Questionnaire =".$row['Id']."
									AND Id_Plateforme =".$_SESSION['FiltreSODA_Plateforme']." ";
									$resultVolumePlanifie=mysqli_query($bdd,$req);
									$nbVolumePlanifie=mysqli_num_rows($resultVolumePlanifie);

									$volumePlanifie=0;
									if ($nbVolumePlanifie > 0)
									{
										$rowP=mysqli_fetch_array($resultVolumePlanifie);
										$volumePlanifie=$rowP['Vol'];
									}
									
									$deltaPlanifie=$volumePlanifie-$volumeObjectif;
									if($deltaPlanifie>=0){$styleDP="background-color:#68b30f;";}
									else{$styleDP="background-color:#f1696d;";}
									
									//Liste des surveillances clôturés de l'année
									$req = "SELECT Id
											FROM soda_surveillance 
											WHERE Suppr=0 
											AND AutoSurveillance=0
											AND Etat='Clôturé'
											AND YEAR(DateSurveillance)=".date("Y",strtotime($lundi." +0 day"))."
											AND Id_Questionnaire=".$row['Id']."
											";
									if ($_SESSION['FiltreSODA_Plateforme'] > 0){
										$req .= "AND Id_Plateforme =".$_SESSION['FiltreSODA_Plateforme']." ";
									}
									$resultCloture=mysqli_query($bdd,$req);
									$nbCloture=mysqli_num_rows($resultCloture);
									
									$deltaRealisePlanifie=$volumeObjectif-$nbCloture;
									if($deltaRealisePlanifie<=0){$styleDR="background-color:#68b30f;";}
									else{$styleDR="background-color:#f1696d;";}
									
									$rowspan=2;
									if($PrestationSelect>0){
										$ligne1.="
											<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;' height='30px' colspan='2' rowspan='".$rowspan."' align='center'></td>
											";
									}
									$ligne1.="
									<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;' height='30px' rowspan='".$rowspan."' align='center'>".$volumeObjectif."</td>
									<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;".$styleDP."' height='30px' rowspan='".$rowspan."' align='center'>".$volumePlanifie."</td>
									<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;".$styleDR."' height='30px' rowspan='".$rowspan."' align='center'>".$nbCloture."</td>
									<td bgcolor='#e9e9e9' style='font-size:11px;width:50px;' height='30px' rowspan='".$rowspan."' colspan='3' align='center'></td>
									
									";
									
									$Id_Theme=$row['Id_Theme'];
								}
								
							}
							
							$ligne1 .= "</tr>";
							$ligne2 .= "</tr>";
							
							echo $ligne1;
							echo $ligne2;
							
						}
					 }
					?>
					</tbody>
					
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td height="5px">
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:50%;" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td height="10px"></td>
				</tr>
				<tr>
					<td class="Libelle" width="15%"><?php if($_SESSION['Langue']=="FR"){echo "Rappel des règles métiers";}else{echo "Reminder of business rules";}?></td>
					<td class="Libelle" width="85%">
					<?php 
						$req="SELECT Regle FROM soda_objectif WHERE Annee=".date('Y',strtotime($dateDebut." +0 month"))." "; 
						$resultRegle=mysqli_query($bdd,$req);
						$nbRegle=mysqli_num_rows($resultRegle);
						
						if ($nbRegle>0){
							$row=mysqli_fetch_array($resultRegle);
							echo nl2br(stripslashes($row['Regle']));
						}
					?>
					</td>
				</tr>
				<tr>
					<td height="10px"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="500px"></td>
	</tr>
</table>
</body>
</html>