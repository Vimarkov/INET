<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetrePlanningExport(Id_Plateforme,Id_Prestation,lDate,lDateFin)
	{
		window.open("PlanningSurveillance_Export.php?Id_Prestation="+Id_Prestation+"&lDate="+lDate+"&lDateFin="+lDateFin+"&Id_Plateforme="+Id_Plateforme,"PagePlanningExport","status=no,menubar=no,scrollbars=1,width=90,height=40");
	}
	function OuvreFenetreAjoutSurveillance(DateAdd,Id_Theme,Id_Questionnaire,ID_Plateforme)
	{
		var w=window.open("Ajout_Surveillance.php?Mode=Ajout&Id=0&DateAdd="+DateAdd+"&Id_Theme="+Id_Theme+"&Id_Questionnaire="+Id_Questionnaire+"&ID_Plateforme="+ID_Plateforme+"&ID_Plateforme2="+document.getElementById('plateforme').value,"PageASurveillance","status=no,menubar=no,width=800,height=350");
		w.focus();
	}
</script>
<?php
$AccesQualite=false;
if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteAssistantFormationInterne,$IdPosteFormateur,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))
	|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,8)))
	{$AccesQualite=true;}

$dateDuJour = date("Y/m/d");

$couleurPlanif = "#ffff00";
$couleurReplanif = "#ff0000";
$couleurRealise = "#0070c0";
$couleurCloture = "#92d050";
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="150%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table class="GeneralPage" width="65%" cellpadding="0" cellspacing="0" style="background-color:#f3f414;">
				<tr>
					<td class="TitrePage">
						<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Surveillance/Tableau_De_Bord.php'>";
							if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
							if($_SESSION["Langue"]=="FR"){echo "Gestion des surveillances # Planning";}
							else{echo "Monitoring management # Planning";}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<br/>
		</td>
	</tr>
</table>
<table width="100%" align="center">	
	<tr>
		<td width="80%">
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<form action="PlanningSurveillance.php" method="post">
				<tr>
					<td width="11%">
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?> :
						<select class="plateforme" name="plateforme" id="plateforme" onchange="submit();">
						<?php
						$reqPlat="SELECT Id, Libelle FROM new_competences_plateforme WHERE Id<>11 AND Id<>14 ";
						if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
							
						}
						else{
							$reqPlat.="AND (Id IN (
								SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
								AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
							)
							OR 
							Id IN (
								SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) 
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION['Id_Personne']."
								AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
							)
							)";
						}
						$reqPlat.=" ORDER BY Libelle ASC";
						$resultPlateforme=mysqli_query($bdd,$reqPlat);
						$nbPlateforme=mysqli_num_rows($resultPlateforme);
						
						$PlateformeSelect = 0;
						$Selected = "";
						if ($nbPlateforme > 0)
						{
							echo "<option name='0' value='0' Selected></option>";
							if (!empty($_POST['plateforme'])){
								if ($PlateformeSelect == 0){$PlateformeSelect = $_POST['plateforme'];}
								while($row=mysqli_fetch_array($resultPlateforme))
								{
									if ($row[0] == $_POST['plateforme']){
										$Selected = "Selected";
									}
									echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
									$Selected = "";
								}
							}
							else{
								while($row=mysqli_fetch_array($resultPlateforme))
								{
									echo "<option name='".$row['Id']."' value='".$row['Id']."' >".$row['Libelle']."</option>";
								}
							}
						 }
						 ?>
						</select>
					</td>
					<td width="15%">
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?> :
						<select class="prestation" name="prestations" size=1 onchange="submit();">
						<?php
						$req = "SELECT Id, CONCAT(new_competences_prestation.Libelle,' ',IF(Active=0,'[Actif]','[Inactif]')) AS Libelle FROM new_competences_prestation WHERE ";
						$req .= "new_competences_prestation.Id_Plateforme=".$PlateformeSelect." ";
						if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
							
						}
						else{
							$req.="AND (Id_Plateforme IN (
								SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
								AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
							)
							OR 
							Id IN (
								SELECT Id_Prestation 
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION['Id_Personne']."
								AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
								)
							) ";
						}
						$req.=" ORDER BY Active DESC, Libelle;";
						$resultPrestation=mysqli_query($bdd,$req);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$PrestationSelect = 0;
						$Selected = "";
						
						if ($nbPrestation > 0 && $PlateformeSelect > 0)
						{
							if (!empty($_POST['prestations'])){
								echo "<option name='0' value='0' Selected></option>";
								if ($PrestationSelect == 0){$PrestationSelect = $_POST['prestations'];}
								while($row=mysqli_fetch_array($resultPrestation))
								{
									if ($row[0] == $_POST['prestations']){
										$Selected = "Selected";
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
					<td width="30%">
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Date début";}else{echo "Start date";}?> :
						<?php
							$dateEnvoi ="";
							if (!empty($_GET['Tri'])){
								$ltri = $_GET['Tri'];
							}
							if($_POST){
								$dateDebut = TrsfDate_($_POST['DateDeDebut']);
								$dateDeFin = TrsfDate_($_POST['DateDeFin']);
								
								if(isset($_POST['MoisPrecedent'])){
									$dateDebut=date("Y-m-d",strtotime($dateDebut." -1 month"));
									$dateDeFin=date("Y-m-d",strtotime($dateDeFin." -1 month"));
								}
								elseif(isset($_POST['MoisSuivant'])){
									$dateDebut=date("Y-m-d",strtotime($dateDebut." +1 month"));
									$dateDeFin=date("Y-m-d",strtotime($dateDeFin." +1 month"));
								}
							}
							else{
								$dateDebut = date("Y-m-d", mktime(0, 0, 0, date('m'), 1 ,date('Y')));
								$dateDeFin = date("Y-m-d", mktime(0, 0, 0, date('m')+1, 0 ,date('Y')));
							}
							
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
					</td>
					<td width="1%">
					</td>
					<td width="2%">
					<?php
						echo "&nbsp;";
						echo "<a style='text-decoration:none;' href=\"javascript:OuvreFenetrePlanningExport(".$PlateformeSelect.",".$PrestationSelect.",'".$dateDebut."','".$dateDeFin."');\">";
						echo "<img src='../../Images/excel.gif' border='0' alt='Excel' title='Export Excel'>";
						echo "</a>";
						echo "&nbsp;";
					?>
					</td>
				</tr>
				</form>
			</table>
		</td>
	</tr>
</table>
<table width="100%" align="center">
	<?php
	$EnTeteMois = "<td ";
	$EnTeteSemaine = "<td ";
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
		$joursem = array("D", "L", "M", "M", "J", "V", "S");
		$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
	}
	else
	{
		$joursem = array("Su", "M", "T", "W", "T", "F", "Sa");
		$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	}
	// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
	
	$premierJour = date("Y/m/d",strtotime($dateDebut." +0 month"));
	while ($tmpDate <= $dateFin) 
	{
		$cptTotal++;
		
		
		//Jour suivant
		$lundi=$tmpDate;
		if(date("N",strtotime($lundi." +0 day"))==1){
			$lundi=$lundi;
		}
		else{
			$lundi=date("Y/m/d",strtotime($lundi."last Monday"));
		}
		$dimanche=date("Y/m/d",strtotime($lundi." +6 day"));
		if($premierJour>$lundi){$lundi=$premierJour;}
		if($dateFin<$dimanche){$dimanche=$dateFin;}
		
		$tabDate = explode('/', $lundi);
		$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
		$jour = date('w', $timestamp);
		$mois = $tabDate[1];
		$semaine = date('W', $timestamp);
		$cptMois++;
		
		$EnTeteSemaine .= " class='EnTeteSemaine' colspan=2>S".$semaine."</td><td ";
		
		//Jour suivant
		$tmpDate2=$dimanche;
		$tmpDate2=date("Y/m/d",strtotime($tmpDate2." +1 day"));
		$tabDate2 = explode('/', $tmpDate2);
		
		if ($tabDate2[1] <> $tabDate[1] && $tmpDate2 <= $dateFin)
		{
			$cptMois = $cptMois * 2;
			$EnTeteMois .= " class='EnTeteMois' colspan=".$cptMois.">".$MoisLettre[$mois-1]." ".$tabDate[0]."</td><td ";
			$cptMois = 0;
		}
		$cptJour++;
		//Jour suivant
		$tmpDate=$dimanche;
		$tmpDate=date("Y/m/d",strtotime($tmpDate." +1 day"));
		
	}
	$cptMois = $cptMois * 2;
	$tabDate = explode('/', $lundi);
	$mois = $tabDate[1];
	$EnTeteMois .= " class='EnTeteMois' colspan=".$cptMois.">".$MoisLettre[$mois-1]." ".$tabDate[0]."</td>";

	?>
	<tr align="center">
		<td bgcolor="<?php echo $couleurPlanif; ?>" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Planifié";}else{echo "Planed";}?></td>
		<td bgcolor="<?php echo $couleurCloture; ?>" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Clôturé";}else{echo "Closed";}?></td>
		<td align="center" valign="center"></td>
		<?php echo $EnTeteMois ;?>
	</tr>
	<tr align="center">
		<td width="5%" class="EnTeteSemaine" style="font-size:12px;"><?php if($_SESSION['Langue']=="FR"){echo "Thème";}else{echo "Theme";}?></td>
		<td width="5%" class="EnTeteSemaine" style="font-size:12px;"><?php if($_SESSION['Langue']=="FR"){echo "Origine du QCM";}else{echo "MCQ source";}?></td>
		<td width="10%" class="EnTeteSemaine" style="font-size:12px;">Questionnaire</td>
		<?php echo $EnTeteSemaine ;?>
	</tr>
	<?php
	// FIN GESTION DES ENTETES DU TABLEAU
	
	//DEBUT CORPS DU TABLEAU
	$tmpDate = date("Y/m/d",strtotime($dateDebut." +0 month"));
	$dateFin = date("Y/m/d", strtotime($dateDeFin." +0 month"));
	
	$ldateFin  = date("Y-m-d", strtotime($dateDeFin." +0 month"));

	//Liste des surveillances
	$req = "SELECT new_surveillances_surveillance.ID as ID_Surveillance, ";
	$req .= "new_surveillances_surveillance.ID_Prestation, ";
	$req .= "new_competences_prestation.Libelle AS Prestation, ";
	$req .= "(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id = new_competences_prestation.ID_Plateforme) AS Plateforme, ";
	$req .= "(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id = new_surveillances_surveillance.ID_Surveille) AS Surveille, ";
	$req .= "(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id = new_surveillances_surveillance.ID_Surveillant) AS Surveillant, ";
	$req .= "new_surveillances_surveillance.ID_Questionnaire, ";
	$req .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance, ";
	$req .= "new_surveillances_surveillance.DatePlanif, ";
	$req .= "new_surveillances_surveillance.DateReplanif, ";
	$req .= "new_surveillances_surveillance.DateCloture, ";
	$req .= "IF(new_surveillances_surveillance.Etat='Clôturé' OR new_surveillances_surveillance.Etat='Réalisé','Clôturé','Planifié') AS Etat ";
	$req .= "FROM new_surveillances_surveillance ";
	$req .= "LEFT JOIN new_competences_prestation ";
	$req .= "ON new_competences_prestation.Id = new_surveillances_surveillance.ID_Prestation ";
	$req .= "WHERE IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) >='".$dateDebut."' ";
	$req .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) <='".$ldateFin."'";
	if ($PlateformeSelect > 0){
		$req .= "AND new_competences_prestation.ID_Plateforme =".$PlateformeSelect." ";
	}
	if ($PrestationSelect > 0){
		$req .= "AND new_competences_prestation.Id =".$PrestationSelect." ";
	}
	if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
		
	}
	else{
		$req.="AND (new_competences_prestation.Id_Plateforme IN (
			SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
			AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
		)
		OR 
		new_surveillances_surveillance.ID_Prestation IN (
			SELECT Id_Prestation 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION['Id_Personne']."
			AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
			)
		) ";
	}
	$resultSurveillance=mysqli_query($bdd,$req);
	$nbSurveillance=mysqli_num_rows($resultSurveillance);
	
	//Liste des questionnaires
	$req = "SELECT distinct new_surveillances_questionnaire.ID,new_surveillances_questionnaire.ID_Theme, 
			new_surveillances_questionnaire.ID_Plateforme, 
			(SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme, ";
	$req .= "(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id = new_surveillances_questionnaire.ID_Plateforme) AS Plateforme, ";
	$req .= "CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Nom ";
	$req .= "FROM ((new_surveillances_surveillance ";
	$req .= "LEFT JOIN new_competences_prestation ";
	$req .= "ON new_competences_prestation.Id = new_surveillances_surveillance.ID_Prestation) ";
	$req .= "LEFT JOIN new_surveillances_questionnaire ";
	$req .= "ON new_surveillances_questionnaire.ID = new_surveillances_surveillance.ID_Questionnaire) ";
	$req .= "WHERE IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) >='".$dateDebut."' ";
	$req .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) <='".$ldateFin."' ";
	if ($PlateformeSelect > 0){
			$req .= "AND new_competences_prestation.ID_Plateforme =".$PlateformeSelect." ";
	}
	if ($PrestationSelect > 0){
		$req .= "AND new_competences_prestation.Id =".$PrestationSelect." ";
	}
	$req .= "ORDER BY Theme, Plateforme, new_surveillances_questionnaire.Nom ;";

	$resultQuestionnaire=mysqli_query($bdd,$req);
	$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
	$nbLargeur = 80/ $cptTotal;
	$nbLargeur2 = $nbLargeur / 2;
	if ($nbQuestionnaire > 0){
		$couleurQuestionnaire = "bgcolor=#548FFB";
		while($row=mysqli_fetch_array($resultQuestionnaire)){
			$Id_Questionnaire = $row['ID'];

			$ligne1 = "<tr>";
			$ligne2 = "<tr>";
			$ligne1 .= "<td rowspan='2' width='5%' height='20px' ".$couleurQuestionnaire.">".$row['Theme']."</td>";
			$ligne1 .= "<td rowspan='2' width='5%' height='20px' ".$couleurQuestionnaire.">".$row['Plateforme']."</td>";
			$ligne1 .= "<td rowspan='2' width='10%' height='20px' ".$couleurQuestionnaire.">".$row['Nom']."</td>";
			if ($couleurQuestionnaire == "bgcolor=#347afa"){
				
				$couleurQuestionnaire = "bgcolor=#548FFB";
			}
			else{
				$couleurQuestionnaire = "bgcolor=#347afa";
			}
			
			$tmpDate = date("Y/m/d",strtotime($dateDebut." +0 month"));
			$premierJour = date("Y/m/d",strtotime($dateDebut." +0 month"));
			while ($tmpDate <= $dateFin) {
				$tabDate = explode('/', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
				$dateAffichage = date("d/m/Y",$timestamp);
				$dateAffichage2 = date("Y-m-d",$timestamp);
				$class="";
				if (jour_ferie($timestamp) == true){
					$class = "weekFerie";
				}
				else{
					$class = "semaine";
				}

				//Recherche si planning pour ce jour-ci
				$nbPlanif=0;
				$nbRePlanif=0;
				$nbRealise=0;
				$nbCloture=0;
				$nbCellule = 0;
				$info="";
				
				$lundi=$tmpDate;
				if(date("N",strtotime($lundi." +0 day"))==1){
					$lundi=$lundi;
				}
				else{
					$lundi=date("Y/m/d",strtotime($lundi."last Monday"));
				}
				$dimanche=date("Y/m/d",strtotime($lundi." +6 day"));
				if($premierJour>$lundi){$lundi=$premierJour;}
				if($dateFin<$dimanche){$dimanche=$dateFin;}
				if ($nbSurveillance>0){
					mysqli_data_seek($resultSurveillance,0);
					while($rowSurveillance=mysqli_fetch_array($resultSurveillance)) {
						$tabDateVac = explode('-', $rowSurveillance['DateSurveillance']);
						$timestampVac = mktime(0, 0, 0, $tabDateVac[1], $tabDateVac[2], $tabDateVac[0]);
						$dateVac = date("Y/m/d", $timestampVac);
						if ($dateVac >= $lundi && $dateVac <= $dimanche && $rowSurveillance['ID_Questionnaire'] == $row['ID']){
							if($rowSurveillance['Etat'] == "Planifié"){
								if($nbPlanif == 0){$nbCellule++;}
								$nbPlanif++;
							}
							elseif($rowSurveillance['Etat'] == "Clôturé"){
								if($nbCloture == 0){$nbCellule++;}
								$nbCloture++;
							}
							
							$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
							$info.="<B>UER</B> : ".$rowSurveillance['Plateforme']." | <B>Surveille</B>: ".$rowSurveillance['Surveille']." | <B>Surveillant</B>: ".$rowSurveillance['Surveillant']." | <B>Prestation</B> : ".$presta." <br>";
						}
					}
				}
				
				$OnClickAjoutSurveillance="";
				if($AccesQualite)
				{
					$parametreAjoutSurveillance=",'".$row['ID_Theme']."','".$row['ID']."','".$row['ID_Plateforme']."'";
					$OnClickAjoutSurveillance="onclick=\"OuvreFenetreAjoutSurveillance('".$dateAffichage2."'".$parametreAjoutSurveillance.");\"";
				}
				if ($nbCellule == 4){
					$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
					$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurReplanif."' id='leHover'>".$nbRePlanif."\n<span>".$info."</span>\n</td>";

					$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurRealise."' id='leHover'>".$nbRealise."\n<span>".$info."</span>\n</td>";
					$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$info."</span>\n</td>";
				}
				elseif ($nbCellule == 1){
					if ($nbPlanif > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;' width='".$nbLargeur."%' height='20px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
					}
					elseif($nbRePlanif > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;' width='".$nbLargeur."%' height='20px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurReplanif."' id='leHover'>".$nbRePlanif."\n<span>".$info."</span>\n</td>";
					}
					elseif($nbRealise > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;' width='".$nbLargeur."%' height='20px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurRealise."' id='leHover'>".$nbRealise."\n<span>".$info."</span>\n</td>";
					}
					elseif($nbCloture > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;' width='".$nbLargeur."%' height='20px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$info."</span>\n</td>";
					}
				}
				elseif ($nbCellule == 2){
					if ($nbPlanif > 0 && $nbRePlanif > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' rowspan='2' height='20px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' rowspan='2' height='20px' align='center' bgcolor='".$couleurReplanif."' id='leHover'>".$nbRePlanif."\n<span>".$info."</span>\n</td>";
					}
					elseif ($nbPlanif > 0 && $nbRealise > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' colspan='2' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' colspan='2' align='center' bgcolor='".$couleurRealise."' id='leHover'>".$nbRealise."\n<span>".$info."</span>\n</td>";
					}
					elseif ($nbPlanif > 0 && $nbCloture > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' colspan='2' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$info."</span>\n</td>";
					}
					elseif ($nbRePlanif > 0 && $nbRealise > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' colspan='2' align='center' bgcolor='".$couleurReplanif."' id='leHover'>".$nbRePlanif."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' colspan='2' align='center' bgcolor='".$couleurRealise."' id='leHover'>".$nbRealise."\n<span>".$info."</span>\n</td>";
					
					}
					elseif ($nbRePlanif > 0 && $nbCloture > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' colspan='2' align='center' bgcolor='".$couleurReplanif."' id='leHover'>".$nbRePlanif."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$info."</span>\n</td>";
					}
					elseif ($nbRealise > 0 && $nbCloture > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='20px' rowspan='2' align='center' bgcolor='".$couleurRealise."' id='leHover'>".$nbRealise."\n<span>".$info."</span>\n</td>";
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='20px' rowspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$info."</span>\n</td>";
					}
				}
				elseif ($nbCellule == 3){
					if ($nbPlanif > 0 && $nbRePlanif > 0 && $nbRealise > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurReplanif."' id='leHover'>".$nbRePlanif."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' align='center' colspan='2' bgcolor='".$couleurRealise."' id='leHover'>".$nbRealise."\n<span>".$info."</span>\n</td>";
					}
					elseif ($nbPlanif > 0 && $nbRePlanif > 0 && $nbCloture > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurReplanif."' id='leHover'>".$nbRePlanif."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' align='center' colspan='2' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$info."</span>\n</td>";
					}
					elseif ($nbPlanif > 0 && $nbRealise > 0 && $nbCloture > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' align='center' colspan='2' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurRealise."' id='leHover'>".$nbRealise."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$info."</span>\n</td>";
					}
					elseif ($nbRePlanif > 0 && $nbRealise > 0 && $nbCloture > 0){
						$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur."%' height='10px' colspan='2' align='center' bgcolor='".$couleurReplanif."' >".$nbRePlanif."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurRealise."' id='leHover'>".$nbRealise."\n<span>".$info."</span>\n</td>";
						$ligne2 .= "<td ".$OnClickAjoutSurveillance." style='font-size:9px;' width='".$nbLargeur2."%' height='10px' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$info."</span>\n</td>";
					}
				}
				else{
					$ligne1 .= "<td ".$OnClickAjoutSurveillance." style='font-size:11px;' width='".$nbLargeur."%' height='20px' colspan='2' rowspan='2' class='".$class."'><br></td>";
				}
				
				//Jour suivant
				$tmpDate=$dimanche;
				$tmpDate=date("Y/m/d",strtotime($tmpDate." +1 day"));
			}
			$ligne1 .= "</tr>";
			$ligne2 .= "</tr>";
			
			echo $ligne1;
			echo $ligne2;
		}
	 }
	?>
	<tr>
		<td height=500></td>
	</tr>
</table>
</body>
</html>