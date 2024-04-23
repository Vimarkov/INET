<?php
if($_POST){
	$Id_Formateur="";
	if(isset($_POST['Id_Formateur'])){
		if (is_array($_POST['Id_Formateur'])) {
			foreach($_POST['Id_Formateur'] as $value){
				if($Id_Formateur<>''){$Id_Formateur.=",";}
			  $Id_Formateur.=$value;
			}
		} else {
			$value = $_POST['Id_Formateur'];
			$Id_Formateur = $value;
		}
	}
	
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
	
	$_SESSION['FiltreTauxReussiteFormation_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreTauxReussiteFormation_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreTauxReussiteFormation_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreTauxReussiteFormation_Type']=$Id_Type;
	$_SESSION['FiltreTauxReussiteFormation_ModeAffichage']=$_POST['ModeAffichage'];
	$_SESSION['FiltreTauxReussiteFormation_Formation']=$_POST['formation'];
	$_SESSION['FiltreTauxReussiteFormation_Categorie']=$Categorie;
	$_SESSION['FiltreTauxReussiteFormation_Formateur']=$Id_Formateur;
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
						$arrayNbSessionForm=array();
						if($_SESSION['Langue']=="EN"){
							$arrayLegendeTauxReussite=array(utf8_encode("Blue collar"),utf8_encode("White collar"));
						}
						else{
							$arrayLegendeTauxReussite=array(utf8_encode("Col bleu"),utf8_encode("Col blanc"));
						}
						
						$leDebut = date("Y-m-d", strtotime($dateDebut." +0 day"));
						$laFin = date("Y-m-d", strtotime($dateFin." +0 day"));
							
						$listeAbscisse=array();
						if($_POST['ModeAffichage']=="Semaine"){
							$letype="7 day";
							$format="Y-W";
						}
						elseif($_POST['ModeAffichage']=="Mois"){
							$letype="1 month";
							$format="Y-m";
						}
						elseif($_POST['ModeAffichage']=="Année"){
							$letype="1 year";
							$format="Y";
						}
						$leParcours=$leDebut;
						$i=0;
						
						while($leParcours<=$laFin){
							$abscisse= date($format, strtotime($leParcours." +0 day"));
							if($_POST['ModeAffichage']=="Mois"){
								$leMois=date("m", strtotime($leParcours." +0 day"));
								$lAnnee=date("Y", strtotime($leParcours." +0 day"));
								$abscisse=$lAnnee."-".$MoisLettre[$leMois-1];
							}
							
							$tabDate = explode('-', $leParcours);
							
							if($_POST['ModeAffichage']=="Semaine"){
								$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
								$jourSemaine = date('w', $timestamp);
								//1er jour
								if($jourSemaine==1){
									$PremierJour=$leParcours;
								}
								else{
									$PremierJour=date('Y-m-d',strtotime($leParcours." last Monday"));
								}
								//Dernier jour
								$DernierJour=date('Y-m-d',strtotime($PremierJour." next Sunday"));
							}
							elseif($_POST['ModeAffichage']=="Mois"){
								//1er jour
								$PremierJour=date('Y-m-01',strtotime($leParcours." +0 day"));
								//Dernier jour
								$DernierJour=date('Y-m-d',strtotime($PremierJour." + 1 month"));
								$tabDate = explode('-', $DernierJour);
								$DernierJour=date('Y-m-d',mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]));
							}
							elseif($_POST['ModeAffichage']=="Année"){
								//1er jour
								$PremierJour=date('Y-01-01',strtotime($leParcours." +0 day"));
								//Dernier jour
								$DernierJour=date('Y-12-31',strtotime($PremierJour." + 0 month"));
							}
							
							if($PremierJour<$leDebut){
								$PremierJour=$leDebut;
							}
							if($DernierJour>$laFin){
								$DernierJour=$laFin;
							}
							

							$ColBleu=TauxReussiteSession($_POST['Id_Plateforme'],$Id_Type,$Id_Formateur,$Categorie,$_POST['formation'],$PremierJour,$DernierJour,1);
							$ColBlanc=TauxReussiteSession($_POST['Id_Plateforme'],$Id_Type,$Id_Formateur,$Categorie,$_POST['formation'],$PremierJour,$DernierJour,0);
							
							$arrayNbSessionForm[$i]=array("Abscisse" => $abscisse,"ColBleu" => $ColBleu,"ColBlanc" => $ColBlanc);
							$leParcours = date("Y-m-d", strtotime($DernierJour." +1 day"));
							$i++;
						}
						
						//PAR CATEGORIE COL BLEU
						$req="
							SELECT  
							Categorie,
							ROUND(AVG(Remplissage)) AS TauxRemplissage
							FROM (
								SELECT
									(SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) AS Categorie,
									IF((SELECT COUNT(Id) FROM form_session_personne_qualification WHERE Suppr=0 AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
									((SELECT COUNT(Id) FROM form_session_personne_qualification WHERE Suppr=0 AND Etat=1 AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)/
									(SELECT COUNT(Id) FROM form_session_personne_qualification WHERE Suppr=0 AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id))*100,100) AS Remplissage
								FROM 
									form_session_personne
								LEFT JOIN form_session
									ON form_session_personne.Id_Session = form_session.Id
								WHERE
									form_session.Suppr=0
									AND form_session.Id_Plateforme IN (".$_POST['Id_Plateforme'].")
									AND Annule=0
									AND Validation_Inscription=1
									AND Presence=1
									AND ColBleu=1
									AND (SELECT COUNT(Id)
									FROM form_session_date
									WHERE form_session_date.Suppr=0
									AND form_session_date.DateSession>='".$dateDebut."'
									AND form_session_date.DateSession<='".$dateFin."' 
									AND form_session_date.Id_Session = form_session.Id ) >0  ";
							if($Id_Type<>""){
								$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$Id_Type.") ";
							}
							if($Id_Formateur<>""){
								$req.=" AND form_session.Id_Formateur IN (".$Id_Formateur.") ";
							}
							if($Categorie<>""){
								$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$Categorie.") ";
							}
							if($_POST['formation']<>"" && $_POST['formation']<>"0_0"){
								$tabQual=explode("_",$_POST['formation']);
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
							$req.=" 
							) AS TAB
						GROUP BY Categorie
						ORDER BY TauxRemplissage DESC ";

						$Result=mysqli_query($bdd,$req);
						$NbResult=mysqli_num_rows($Result);
						$arrayNbSessionFormCategorieBleu=array();
						if($NbResult>0){
							$i=0;
							while($row=mysqli_fetch_array($Result))
							{
								$arrayNbSessionFormCategorieBleu[$i]=array("Abscisse" => utf8_encode($row['Categorie']),"ColBleu" => $row['TauxRemplissage']);
								$i++;
							}
						}
						
						//PAR CATEGORIE COL BLANC
						$req="
							SELECT  
							Categorie,
							ROUND(AVG(Remplissage)) AS TauxRemplissage
							FROM (
								SELECT
									(SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) AS Categorie,
									IF((SELECT COUNT(Id) FROM form_session_personne_qualification WHERE Suppr=0 AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
									((SELECT COUNT(Id) FROM form_session_personne_qualification WHERE Suppr=0 AND Etat=1 AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)/
									(SELECT COUNT(Id) FROM form_session_personne_qualification WHERE Suppr=0 AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id))*100,100) AS Remplissage
								FROM 
									form_session_personne
								LEFT JOIN form_session
									ON form_session_personne.Id_Session = form_session.Id
								WHERE
									form_session.Suppr=0
									AND form_session.Id_Plateforme IN (".$_POST['Id_Plateforme'].")
									AND Annule=0
									AND Validation_Inscription=1
									AND Presence=1
									AND ColBleu=0
									AND (SELECT COUNT(Id)
									FROM form_session_date
									WHERE form_session_date.Suppr=0
									AND form_session_date.DateSession>='".$dateDebut."'
									AND form_session_date.DateSession<='".$dateFin."' 
									AND form_session_date.Id_Session = form_session.Id ) >0  ";
							if($Id_Type<>""){
								$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$Id_Type.") ";
							}
							if($Id_Formateur<>""){
								$req.=" AND form_session.Id_Formateur IN (".$Id_Formateur.") ";
							}
							if($Categorie<>""){
								$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$Categorie.") ";
							}
							if($_POST['formation']<>"" && $_POST['formation']<>"0_0"){
								$tabQual=explode("_",$_POST['formation']);
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
							$req.=" 
							) AS TAB
						GROUP BY Categorie
						ORDER BY TauxRemplissage DESC ";

						$Result=mysqli_query($bdd,$req);
						$NbResult=mysqli_num_rows($Result);
						$arrayNbSessionFormCategorieBlanc=array();
						if($NbResult>0){
							$i=0;
							while($row=mysqli_fetch_array($Result))
							{
								$arrayNbSessionFormCategorieBlanc[$i]=array("Abscisse" => utf8_encode($row['Categorie']),"ColBlanc" => $row['TauxRemplissage']);
								$i++;
							}
						}
						
				?>
					<tr>
						<td height="350px;">
							<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "TAUX DE REUSSITE";}else{echo "SUCCESS RATE";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_NBSessionTauxReussite" style="width:100%;height:350px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_NBSessionTauxReussite", am4charts.XYChart);

											// Add data
											chart.data = <?php echo json_encode($arrayNbSessionForm); ?>;

											// Create axes
											var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
											categoryAxis.dataFields.category = "Abscisse";
											categoryAxis.renderer.grid.template.location = 0;
											categoryAxis.renderer.minGridDistance = 30;
											categoryAxis.renderer.labels.template.horizontalCenter = "right";
											categoryAxis.renderer.labels.template.verticalCenter = "middle";
											categoryAxis.renderer.labels.template.rotation = 270;
											categoryAxis.tooltip.disabled = true;
											categoryAxis.renderer.minHeight = 0;

											var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
											valueAxis.renderer.minWidth = 0;
											
											// Create series
											var series1 = chart.series.push(new am4charts.ColumnSeries());
											series1.columns.template.width = am4core.percent(80);
											series1.tooltipText = "{name}: {valueY.value}[/]%";
											series1.dataFields.categoryX = "Abscisse";
											series1.dataFields.valueY = "ColBleu";
											series1.name = <?php echo json_encode($arrayLegendeTauxReussite[0]); ?>;
											series1.stacked = false;
											series1.stroke  = "#3d7ad5";
											series1.fill  = "#3d7ad5";
											
											var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "[bold] {valueY}[/]%";
											bullet1.label.verticalCenter = "bottom";
											bullet1.label.dy = -10;
											bullet1.label.fill = am4core.color("#3d7ad5");
											bullet1.interactionsEnabled = false;

											var series2 = chart.series.push(new am4charts.ColumnSeries());
											series2.columns.template.width = am4core.percent(80);
											series2.tooltipText = "{name}: {valueY.value}[/]%";
											series2.dataFields.categoryX = "Abscisse";
											series2.dataFields.valueY = "ColBlanc";
											series2.name = <?php echo json_encode($arrayLegendeTauxReussite[1]); ?>;
											series2.stacked = false;
											series2.stroke  = "#a5a5a5";
											series2.fill  = "#a5a5a5";
											
											var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "[bold] {valueY}[/]%";
											bullet1.label.verticalCenter = "bottom";
											bullet1.label.dy = -10;
											bullet1.label.fill = am4core.color("#4d4d4d");
											bullet1.interactionsEnabled = false;
											
											// Cursor
											chart.cursor = new am4charts.XYCursor();
											chart.cursor.behavior = "panX";
											chart.cursor.lineX.opacity = 0;
											chart.cursor.lineY.opacity = 0;
											
											chart.exporting.menu = new am4core.ExportMenu();
											
											chart.legend = new am4charts.Legend();
										</script>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="700px;">
							<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "TAUX DE REUSSITE COL BLEU / CATEGORIE";}else{echo "SUCCESS RATE BLUE COLLAR / CATEGORY";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_NBSessionFormCategorieBleu" style="width:100%;height:700px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_NBSessionFormCategorieBleu", am4charts.XYChart);

											// Add data
											chart.data = <?php echo json_encode($arrayNbSessionFormCategorieBleu); ?>;

											// Create axes
											var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
											categoryAxis.dataFields.category = "Abscisse";
											categoryAxis.renderer.grid.template.location = 0;
											categoryAxis.renderer.minGridDistance = 30;
											categoryAxis.renderer.labels.template.horizontalCenter = "right";
											categoryAxis.renderer.labels.template.verticalCenter = "middle";
											categoryAxis.renderer.labels.template.rotation = 270;
											categoryAxis.tooltip.disabled = true;
											categoryAxis.renderer.minHeight = 0;

											var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
											valueAxis.renderer.minWidth = 0;
											
											// Create series
											var series1 = chart.series.push(new am4charts.ColumnSeries());
											series1.columns.template.width = am4core.percent(80);
											series1.tooltipText = "{name}: {valueY.value}[/]%";
											series1.dataFields.categoryX = "Abscisse";
											series1.dataFields.valueY = "ColBleu";
											series1.name = <?php echo json_encode($arrayLegendeTauxReussite[1]); ?>;
											series1.stacked = false;
											series1.stroke  = "#3d7ad5";
											series1.fill  = "#3d7ad5";
											
											var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "[bold] {valueY}[/]%";
											bullet1.label.verticalCenter = "bottom";
											bullet1.label.dy = -10;
											bullet1.label.fill = am4core.color("#3d7ad5");
											bullet1.interactionsEnabled = false;

											// Cursor
											chart.cursor = new am4charts.XYCursor();
											chart.cursor.behavior = "panX";
											chart.cursor.lineX.opacity = 0;
											chart.cursor.lineY.opacity = 0;
											
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
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "TAUX DE REUSSITE COL BLANC / CATEGORIE";}else{echo "SUCCESS RATE WHITE COLLAR / CATEGORY";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_NBSessionFormCategorieBlanc" style="width:100%;height:600px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_NBSessionFormCategorieBlanc", am4charts.XYChart);

											// Add data
											chart.data = <?php echo json_encode($arrayNbSessionFormCategorieBlanc); ?>;

											// Create axes
											var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
											categoryAxis.dataFields.category = "Abscisse";
											categoryAxis.renderer.grid.template.location = 0;
											categoryAxis.renderer.minGridDistance = 30;
											categoryAxis.renderer.labels.template.horizontalCenter = "right";
											categoryAxis.renderer.labels.template.verticalCenter = "middle";
											categoryAxis.renderer.labels.template.rotation = 270;
											categoryAxis.tooltip.disabled = true;
											categoryAxis.renderer.minHeight = 0;

											var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
											valueAxis.renderer.minWidth = 0;
											
											// Create series
											var series1 = chart.series.push(new am4charts.ColumnSeries());
											series1.columns.template.width = am4core.percent(80);
											series1.tooltipText = "{name}: {valueY.value}[/]%";
											series1.dataFields.categoryX = "Abscisse";
											series1.dataFields.valueY = "ColBlanc";
											series1.name = <?php echo json_encode($arrayLegendeTauxReussite[1]); ?>;
											series1.stacked = false;
											series1.stroke  = "#a5a5a5";
											series1.fill  = "#a5a5a5";
											
											var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "[bold] {valueY}[/]%";
											bullet1.locationY = 0.3;
											bullet1.label.fill = am4core.color("#ffffff");
											bullet1.interactionsEnabled = false;

											// Cursor
											chart.cursor = new am4charts.XYCursor();
											chart.cursor.behavior = "panX";
											chart.cursor.lineX.opacity = 0;
											chart.cursor.lineY.opacity = 0;
											
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
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Mode d'affichage";}else{echo "Display mode";}?> : </td>
			</tr>
			<tr>
				<td>
					<?php 
					echo "<select name='ModeAffichage' id='ModeAffichage'>";
					
					$selected="";
					if($_SESSION['FiltreTauxReussiteFormation_ModeAffichage']=='Semaine'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Semaine' ".$selected.">Semaine</option>";}
					else{echo "<option value='Semaine' ".$selected.">Week</option>";}
					
					$selected="";
					if($_SESSION['FiltreTauxReussiteFormation_ModeAffichage']=='Mois'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Mois' ".$selected.">Mois</option>";}
					else{echo "<option value='Mois' ".$selected.">Month</option>";}
					
					$selected="";
					if($_SESSION['FiltreTauxReussiteFormation_ModeAffichage']=='Année'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Année' ".$selected.">Année</option>";}
					else{echo "<option value='Année' ".$selected.">Year</option>";}
					
					echo "</select>";
					?>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreTauxReussiteFormation_DateDebut']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreTauxReussiteFormation_DateFin']); ?>"></td>
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
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Formateur";}else{echo "Former";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="checkbox" name="selectAllFormateur" id="selectAllFormateur" onclick="SelectionnerToutFormateur()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
			</tr>
			
			<tr>
				<td>
					<div id='Div_Formateur' style="height:100px;overflow:auto;">
						<table width='100%'>
							<?php
								$checked="";
								if($_POST){
									$checkboxes = isset($_POST['Id_Formateur']) ? $_POST['Id_Formateur'] : array();
									foreach($checkboxes as $value) {
										if(0==$value){$checked="checked";}
									}
								}
								else{
									$checked="checked";	
								}
								
								echo "<tr><td>";
								echo "<input type='checkbox' class='checkFormateur' name='Id_Formateur[]' Id='Id_Formateur[]' value='0' ".$checked.">";
								echo "</td></tr>";
								
								$rqFormateur="SELECT DISTINCT Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Poste IN (".$IdPosteFormateur.")
								AND Id_Plateforme=".$Id_Plateforme."
								AND Id_Personne<>0
								ORDER BY Personne";
								
								$resultFormateur=mysqli_query($bdd,$rqFormateur);
								$Id_Formateur=0;
								while($rowFormateur=mysqli_fetch_array($resultFormateur))
								{
									$checked="";
									if($_POST){
										$checkboxes = isset($_POST['Id_Formateur']) ? $_POST['Id_Formateur'] : array();
										foreach($checkboxes as $value) {
											if($rowFormateur['Id_Personne']==$value){$checked="checked";}
										}
									}
									else{
										$checked="checked";	
									}
									
									echo "<tr><td>";
									echo "<input type='checkbox' class='checkFormateur' name='Id_Formateur[]' Id='Id_Formateur[]' value='".$rowFormateur['Id_Personne']."' ".$checked.">".$rowFormateur['Personne'];
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
						$laformation=$_SESSION['FiltreTauxReussiteFormation_Formation'];

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