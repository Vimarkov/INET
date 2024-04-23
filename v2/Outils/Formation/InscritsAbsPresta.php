<?php
if($_POST){
	$Id_Type="";
	if(isset($_POST['Id_Type'])){
		if (is_array($_POST['Id_Type'])) {
			foreach($_POST['Id_Type'] as $value){
				if($Id_Type<>''){$Id_Type.=",";}
			  $Id_Type.=$value;
			}
		} else {
			$value = $_POST['Id_Type'];
			$Id_Type = $value;
		}
	}
		
	$Categorie="";
	if(isset($_POST['Categorie'])){
		if (is_array($_POST['Categorie'])) {
			foreach($_POST['Categorie'] as $value){
				if($Categorie<>''){$Categorie.=",";}
			  $Categorie.="\"".$value."\"";
			}
		} else {
			$value = $_POST['Categorie'];
			$Categorie = "\"".$value."\"";
		}
	}
	
	$_SESSION['FiltreInscritsAbsPresta_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreInscritsAbsPresta_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreInscritsAbsPresta_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreInscritsAbsPresta_Type']=$Id_Type;
	$_SESSION['FiltreInscritsAbsPresta_Formation']=$_POST['formation'];
	$_SESSION['FiltreInscritsAbsPresta_Categorie']=$Categorie;
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table width="99%">
			<?php if($_POST){ 
				if($_POST['DateDebut']<>"" && $_POST['DateFin']<>""){
					$dateDebut=TrsfDate_($_POST['DateDebut']);
					$dateFin=TrsfDate_($_POST['DateFin']);
					
					if($dateFin>=$dateDebut){
						
						$arrayInscription=array();
						$arrayAbsence=array();
						
						$req="
						SELECT
							(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) FROM form_besoin WHERE form_besoin.Id=Id_Besoin) AS Prestation,
							(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=form_besoin.Id_Pole) FROM form_besoin WHERE form_besoin.Id=Id_Besoin) AS Pole,
							(SELECT Id_Prestation FROM form_besoin WHERE form_besoin.Id=Id_Besoin) AS Id_Prestation,
							(SELECT Id_Pole FROM form_besoin WHERE form_besoin.Id=Id_Besoin) AS Id_Pole,
							COUNT(form_session_personne.Id) AS Nombre
						FROM
							form_session_personne
						LEFT JOIN form_session ON form_session_personne.Id_Session = form_session.Id
						WHERE
							form_session_personne.Suppr=0
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne.Suppr=0
							AND form_session.Suppr=0
							AND form_session.Id_Plateforme IN (".$_SESSION['FiltreInscritsAbsPresta_Plateforme'].")
							AND Annule=0
							AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)>='".$_SESSION['FiltreInscritsAbsPresta_DateDebut']."'
							AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)<='".$_SESSION['FiltreInscritsAbsPresta_DateFin']."' ";

							if($_SESSION['FiltreInscritsAbsPresta_Type']<>""){
								$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$_SESSION['FiltreInscritsAbsPresta_Type'].") ";
							}
							if($_SESSION['FiltreInscritsAbsPresta_Categorie']<>""){
								$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$_SESSION['FiltreInscritsAbsPresta_Categorie'].") ";
							}
							if($_SESSION['FiltreInscritsAbsPresta_Formation']<>"" && $_SESSION['FiltreInscritsAbsPresta_Formation']<>"0_0"){
								$tabQual=explode("_",$_SESSION['FiltreInscritsAbsPresta_Formation']);
								if($tabQual[1]==0){
									$req.=" AND Id_Formation=".$tabQual[0]." ";
								}
								else{
									$req.=" AND Id_Formation IN 
										(SELECT Id_Formation 
										FROM form_formationequivalente_formationplateforme 
										WHERE Id_FormationEquivalente=".$tabQual[0].") ";
								}
							}
						$req.="GROUP BY Id_Prestation,Id_Pole 
							ORDER BY Nombre DESC";

						$Result=mysqli_query($bdd,$req);
						$NbResult=mysqli_num_rows($Result);
						
						$req="
						SELECT
							form_session_personne.Id
						FROM
							form_session_personne
						LEFT JOIN form_session ON form_session_personne.Id_Session = form_session.Id
						WHERE
							form_session_personne.Suppr=0
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne.Suppr=0
							AND form_session.Suppr=0
							AND form_session.Id_Plateforme IN (".$_SESSION['FiltreInscritsAbsPresta_Plateforme'].")
							AND Annule=0
							AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)>='".$_SESSION['FiltreInscritsAbsPresta_DateDebut']."'
							AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)<='".$_SESSION['FiltreInscritsAbsPresta_DateFin']."' ";

							if($_SESSION['FiltreInscritsAbsPresta_Type']<>""){
								$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$_SESSION['FiltreInscritsAbsPresta_Type'].") ";
							}
							if($_SESSION['FiltreInscritsAbsPresta_Categorie']<>""){
								$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$_SESSION['FiltreInscritsAbsPresta_Categorie'].") ";
							}
							if($_SESSION['FiltreInscritsAbsPresta_Formation']<>"" && $_SESSION['FiltreInscritsAbsPresta_Formation']<>"0_0"){
								$tabQual=explode("_",$_SESSION['FiltreInscritsAbsPresta_Formation']);
								if($tabQual[1]==0){
									$req.=" AND Id_Formation=".$tabQual[0]." ";
								}
								else{
									$req.=" AND Id_Formation IN 
										(SELECT Id_Formation 
										FROM form_formationequivalente_formationplateforme 
										WHERE Id_FormationEquivalente=".$tabQual[0].") ";
								}
							}

						$Result2=mysqli_query($bdd,$req);
						$NbResult2=mysqli_num_rows($Result2);
						
						if($NbResult>0){
							$i=0;
							$autre=0;
							while($row=mysqli_fetch_array($Result))
							{
								if(($row['Nombre']/$NbResult2*100)>=1){
									$Prestation=$row['Prestation'];
									if($row['Pole']<>""){$Prestation.=" - ".$row['Pole'];}
									$arrayInscription[$i]=array("Abscisse" => utf8_encode($Prestation),"Nombre" => $row['Nombre']);
									
									$i++;
								}
								else{
									$autre+=$row['Nombre'];
								}
							}
							if($autre>0){
								$arrayInscription[$i]=array("Abscisse" => utf8_encode("Autres prestations (< 1%)"),"Nombre" => $autre);
							}
						}
						
						
						
						$req="
						SELECT
							(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) FROM form_besoin WHERE form_besoin.Id=Id_Besoin) AS Prestation,
							(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=form_besoin.Id_Pole) FROM form_besoin WHERE form_besoin.Id=Id_Besoin) AS Pole,
							(SELECT Id_Prestation FROM form_besoin WHERE form_besoin.Id=Id_Besoin) AS Id_Prestation,
							(SELECT Id_Pole FROM form_besoin WHERE form_besoin.Id=Id_Besoin) AS Id_Pole,
							COUNT(form_session_personne.Id) AS Nombre
						FROM
							form_session_personne
						LEFT JOIN form_session ON form_session_personne.Id_Session = form_session.Id
						WHERE
							form_session_personne.Suppr=0
							AND form_session_personne.Validation_Inscription=1
							AND Presence IN (-1,-2)
							AND form_session_personne.Suppr=0
							AND form_session.Suppr=0
							AND form_session.Id_Plateforme IN (".$_SESSION['FiltreInscritsAbsPresta_Plateforme'].")
							AND Annule=0
							AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)>='".$_SESSION['FiltreInscritsAbsPresta_DateDebut']."'
							AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)<='".$_SESSION['FiltreInscritsAbsPresta_DateFin']."' ";

							if($_SESSION['FiltreInscritsAbsPresta_Type']<>""){
								$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$_SESSION['FiltreInscritsAbsPresta_Type'].") ";
							}
							if($_SESSION['FiltreInscritsAbsPresta_Categorie']<>""){
								$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$_SESSION['FiltreInscritsAbsPresta_Categorie'].") ";
							}
							if($_SESSION['FiltreInscritsAbsPresta_Formation']<>"" && $_SESSION['FiltreInscritsAbsPresta_Formation']<>"0_0"){
								$tabQual=explode("_",$_SESSION['FiltreInscritsAbsPresta_Formation']);
								if($tabQual[1]==0){
									$req.=" AND Id_Formation=".$tabQual[0]." ";
								}
								else{
									$req.=" AND Id_Formation IN 
										(SELECT Id_Formation 
										FROM form_formationequivalente_formationplateforme 
										WHERE Id_FormationEquivalente=".$tabQual[0].") ";
								}
							}
						$req.="GROUP BY Id_Prestation,Id_Pole 
							ORDER BY Nombre DESC";
						
						$Result=mysqli_query($bdd,$req);
						$NbResult=mysqli_num_rows($Result);
						
						$req="
						SELECT
							form_session_personne.Id
						FROM
							form_session_personne
						LEFT JOIN form_session ON form_session_personne.Id_Session = form_session.Id
						WHERE
							form_session_personne.Suppr=0
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne.Suppr=0
							AND Presence IN (-1,-2)
							AND form_session.Suppr=0
							AND form_session.Id_Plateforme IN (".$_SESSION['FiltreInscritsAbsPresta_Plateforme'].")
							AND Annule=0
							AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)>='".$_SESSION['FiltreInscritsAbsPresta_DateDebut']."'
							AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)<='".$_SESSION['FiltreInscritsAbsPresta_DateFin']."' ";

							if($_SESSION['FiltreInscritsAbsPresta_Type']<>""){
								$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$_SESSION['FiltreInscritsAbsPresta_Type'].") ";
							}
							if($_SESSION['FiltreInscritsAbsPresta_Categorie']<>""){
								$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$_SESSION['FiltreInscritsAbsPresta_Categorie'].") ";
							}
							if($_SESSION['FiltreInscritsAbsPresta_Formation']<>"" && $_SESSION['FiltreInscritsAbsPresta_Formation']<>"0_0"){
								$tabQual=explode("_",$_SESSION['FiltreInscritsAbsPresta_Formation']);
								if($tabQual[1]==0){
									$req.=" AND Id_Formation=".$tabQual[0]." ";
								}
								else{
									$req.=" AND Id_Formation IN 
										(SELECT Id_Formation 
										FROM form_formationequivalente_formationplateforme 
										WHERE Id_FormationEquivalente=".$tabQual[0].") ";
								}
							}

						$Result2=mysqli_query($bdd,$req);
						$NbResult2=mysqli_num_rows($Result2);
						if($NbResult>0){
							$i=0;
							$autre=0;
							while($row=mysqli_fetch_array($Result))
							{
								if(($row['Nombre']/$NbResult2*100)>=1){
									$Prestation=$row['Prestation'];
									if($row['Pole']<>""){$Prestation.=" - ".$row['Pole'];}
									$arrayAbsence[$i]=array("Abscisse" => utf8_encode($Prestation),"Nombre" => $row['Nombre']);
									
									$i++;
								}
								else{
									$autre+=$row['Nombre'];
								}
							}
							if($autre>0){
								$arrayAbsence[$i]=array("Abscisse" => utf8_encode("Autres prestations (< 1%)"),"Nombre" => $autre);
							}
						}
						
						
						

				?>
					<tr>
						<td height="600px;">
							<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "REPARTITION DES INSCRIPTIONS / PRESTATION";}else{echo "BREAKDOWN OF REGISTRATIONS / SITE";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_Inscription" style="width:100%;height:600px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_Inscription", am4charts.PieChart);

										// Add data
										chart.data = <?php echo json_encode($arrayInscription); ?>;
										
										var pieSeries = chart.series.push(new am4charts.PieSeries());
										pieSeries.dataFields.category = "Abscisse";
										pieSeries.dataFields.value = "Nombre";
										pieSeries.slices.template.stroke = am4core.color("#fff");
										pieSeries.slices.template.strokeWidth = 2;
										pieSeries.slices.template.strokeOpacity = 1;

										// This creates initial animation
										pieSeries.hiddenState.properties.opacity = 1;
										pieSeries.hiddenState.properties.endAngle = -90;
										pieSeries.hiddenState.properties.startAngle = -90;
										
										var level1ColumnTemplate = pieSeries.columns.template;

										var bullet1 = level1ColumnTemplate.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{name}: {valueY.value}";


										chart.exporting.menu = new am4core.ExportMenu();
										</script>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="600px;">
							<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "REPARTITION DES ABSENCES EN FORMATION / PRESTATION";}else{echo "BREAKDOWN OF ABSENCES IN TRAINING / SITE";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_absence" style="width:100%;height:600px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_absence", am4charts.PieChart);

										// Add data
										chart.data = <?php echo json_encode($arrayAbsence); ?>;
										
										var pieSeries = chart.series.push(new am4charts.PieSeries());
										pieSeries.dataFields.category = "Abscisse";
										pieSeries.dataFields.value = "Nombre";
										pieSeries.slices.template.stroke = am4core.color("#fff");
										pieSeries.slices.template.strokeWidth = 2;
										pieSeries.slices.template.strokeOpacity = 1;

										// This creates initial animation
										pieSeries.hiddenState.properties.opacity = 1;
										pieSeries.hiddenState.properties.endAngle = -90;
										pieSeries.hiddenState.properties.startAngle = -90;
										
										var level1ColumnTemplate = pieSeries.columns.template;

										var bullet1 = level1ColumnTemplate.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{name}: {valueY.value}";


										chart.exporting.menu = new am4core.ExportMenu();
										</script>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				<?php 
					}
				}
			}
			?>
		</table>
	</td>
	<td align="right" valign="top" width="25%">
		<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreInscritsAbsPresta_DateDebut']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreInscritsAbsPresta_DateFin']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Type de formation";}else{echo "Type of training";}?> : </td>
			</tr>
			<tr>
				<td>
					<table width='100%'>
						<?php
							$rqType="SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle";
							
							$resultType=mysqli_query($bdd,$rqType);
							while($rowType=mysqli_fetch_array($resultType))
							{
								$checked="";
								if($_POST){
									$checkboxes = isset($_POST['Id_Type']) ? $_POST['Id_Type'] : array();
									foreach($checkboxes as $value) {
										if($rowType['Id']==$value){$checked="checked";}
									}
								}
								else{
									$checked="checked";	
								}
								echo "<tr><td>";
								echo "<input type='checkbox' class='checkType' name='Id_Type[]' Id='Id_Type[]' value='".$rowType['Id']."' ".$checked." >".$rowType['Libelle'];
								echo "</td></tr>";
							}
						?>
					</table>
				</td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr>
				<td>
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
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="checkbox" name="selectAllCategorie" id="selectAllCategorie" onclick="SelectionnerToutCategorie()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
			</tr>
			
			<tr>
				<td>
					<div id='Div_Categorie' style="height:100px;overflow:auto;">
						<table width='100%'>
							<?php
								$rqCategorie="SELECT DISTINCT Categorie
								FROM form_formation_plateforme_parametres
								LEFT JOIN form_formation 
								ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id
								WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme."
								AND form_formation_plateforme_parametres.Suppr=0
								AND form_formation.Suppr=0
								ORDER BY Categorie";
								
								$resultCategorie=mysqli_query($bdd,$rqCategorie);
								$Categorie=0;
								while($rowCategorie=mysqli_fetch_array($resultCategorie))
								{
									$checked="";
									if($_POST){
										$checkboxes = isset($_POST['Categorie']) ? $_POST['Categorie'] : array();
										foreach($checkboxes as $value) {
											if($rowCategorie['Categorie']==$value){$checked="checked";}
										}
									}
									else{
										$checked="checked";	
									}
									
									echo "<tr><td>";
									echo "<input type='checkbox' class='checkCategorie' name='Categorie[]' Id='Categorie[]' value=\"".$rowCategorie['Categorie']."\" ".$checked.">".$rowCategorie['Categorie'];
									echo "</td></tr>";
								}
							?>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation ";}else{echo "Training ";}?></td>
			</tr>
			<tr>
				<td>
					<?php
						
					?>
					<select name="formation" id="formation" style="width:200px">
						<option value="0_0"></option>
						<?php
						$laformation=$_SESSION['FiltreInscritsAbsPresta_Formation'];

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
							if($laformation<>"")
							{
								if($laformation==$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']){$selected="selected";}
							}
							echo "<option value='".$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']."' ".$selected.">";
							echo stripslashes($rowForm['Formation']);
							echo "</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="center">
					<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
					<div id="filtrer"></div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
		</table>
	</td>
</tr>
<tr><td height="4"></td>
</table>	