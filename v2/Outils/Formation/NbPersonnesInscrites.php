<?php
if($_POST){
	$Id_TypeContrat="";
	$Interim=0;
	$Salarie=0;
	$NC=0;
	if(isset($_POST['Id_TypeContrat'])){
		if (is_array($_POST['Id_TypeContrat'])) {
			foreach($_POST['Id_TypeContrat'] as $value){
				if($Id_TypeContrat<>''){$Id_TypeContrat.=",";}
			  $Id_TypeContrat.=$value;
			  if($value=="'0'"){$Interim=1;}
				if($value=="'1'"){$Salarie=1;}
				if($value=="'NULL'"){$NC=1;}
			}
		} else {
			$value = $_POST['Id_TypeContrat'];
			$Id_TypeContrat = $value;
			if($value=="'0'"){$Interim=1;}
			if($value=="'1'"){$Salarie=1;}
			if($value=="'NULL'"){$NC=1;}
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
	
	$_SESSION['FiltreNbPersonneInscrite_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreNbPersonneInscrite_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreNbPersonneInscrite_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreNbPersonneInscrite_Type']=$Id_Type;
	$_SESSION['FiltreNbPersonneInscrite_ModeAffichage']=$_POST['ModeAffichage'];
	$_SESSION['FiltreNbPersonneInscrite_Formation']=$_POST['formation'];
	$_SESSION['FiltreNbPersonneInscrite_Categorie']=$Categorie;
	$_SESSION['FiltreNbPersonneInscrite_TypeContrat']=$Id_TypeContrat;
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
						$arrayNbPersonnesInscrites=array();
						if($_SESSION['Langue']=="EN"){
							$arrayLegendeNbPersonnesInscrites=array(utf8_encode("Interim"),utf8_encode("Employee"),utf8_encode("NC"));
						}
						else{
							$arrayLegendeNbPersonnesInscrites=array(utf8_encode("Int�rim"),utf8_encode("Salari�"),utf8_encode("NC"));
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
						elseif($_POST['ModeAffichage']=="Ann�e"){
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
							elseif($_POST['ModeAffichage']=="Ann�e"){
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
							

							$NbInterim=NbPersonnesInscritesV2($_POST['Id_Plateforme'],$Id_Type,$Categorie,$_POST['formation'],$PremierJour,$DernierJour,"'0'");
							$NbSalarie=NbPersonnesInscritesV2($_POST['Id_Plateforme'],$Id_Type,$Categorie,$_POST['formation'],$PremierJour,$DernierJour,"'1'");
							$NbNC=NbPersonnesInscritesV2($_POST['Id_Plateforme'],$Id_Type,$Categorie,$_POST['formation'],$PremierJour,$DernierJour,"'NULL'");
							
							$total=$NbInterim+$NbSalarie+$NbNC;
							$arrayNbPersonnesInscrites[$i]=array("Abscisse" => $abscisse,"NbInterim" => $NbInterim,"NbSalarie" => $NbSalarie,"NbNC" => $NbNC,"Total" => $total);
							$leParcours = date("Y-m-d", strtotime($DernierJour." +1 day"));
							$i++;
						}
						
				?>
					<tr>
						<td height="450px;">
							<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE PERSONNES INSCRITES EN FORMATION";}else{echo "NUMBER OF PERSONS ENTERED IN TRAINING";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_NBPersonnesInscrites" style="width:100%;height:450px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_NBPersonnesInscrites", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayNbPersonnesInscrites); ?>;

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
										<?php if($Interim==1 || ($Interim==0 && $Salarie==0 && $NC==0)){ ?>
										var series1 = chart.series.push(new am4charts.ColumnSeries());
										series1.columns.template.width = am4core.percent(80);
										series1.tooltipText = "{name}: {valueY.value}";
										series1.dataFields.categoryX = "Abscisse";
										series1.dataFields.valueY = "NbInterim";
										series1.name = <?php echo json_encode($arrayLegendeNbPersonnesInscrites[0]); ?>;
										series1.stacked = false;
										series1.stroke  = "#3d7ad5";
										series1.fill  = "#3d7ad5";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.2;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										<?php } ?>
										
										<?php if($Salarie==1 || ($Interim==0 && $Salarie==0 && $NC==0)){ ?>
										// Create series
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Abscisse";
										series2.dataFields.valueY = "NbSalarie";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesInscrites[1]); ?>;
										series2.stacked = true;
										series2.stroke  = "#29dae9";
										series2.fill  = "#29dae9";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.2;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										<?php } ?>
										
										<?php if($NC==1 || ($Interim==0 && $Salarie==0 && $NC==0)){ ?>
										// Create series
										var series3 = chart.series.push(new am4charts.ColumnSeries());
										series3.columns.template.width = am4core.percent(80);
										series3.tooltipText = "{name}: {valueY.value}";
										series3.dataFields.categoryX = "Abscisse";
										series3.dataFields.valueY = "NbNC";
										series3.name = <?php echo json_encode($arrayLegendeNbPersonnesInscrites[2]); ?>;
										series3.stacked = true;
										series3.stroke  = "#e8f951";
										series3.fill  = "#e8f951";
										
										var bullet1 = series3.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.2;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;
										<?php } ?>
										
										var series4 = chart.series.push(new am4charts.LineSeries());
										series4.tooltipText = "[{categoryX}: bold]{valueY.value}";
										series4.dataFields.categoryX = "Abscisse";
										series4.dataFields.valueY = "Total";
										series4.name = <?php if($_SESSION['Langue']=="FR"){echo json_encode("");}else{echo json_encode("");} ?>;
										series4.yAxis = valueAxis;
										series4.strokeOpacity = 0;
										series4.strokeWidth = 2;
										series4.minBulletDistance = 10;
										
										var bullet = series4.bullets.push(new am4charts.LabelBullet());
										bullet.label.text = "[bold] {valueY}";
										bullet.label.dy = -5;
										bullet.label.dx = -2;
										
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
					if($_SESSION['FiltreNbPersonneInscrite_ModeAffichage']=='Semaine'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Semaine' ".$selected.">Semaine</option>";}
					else{echo "<option value='Semaine' ".$selected.">Week</option>";}
					
					$selected="";
					if($_SESSION['FiltreNbPersonneInscrite_ModeAffichage']=='Mois'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Mois' ".$selected.">Mois</option>";}
					else{echo "<option value='Mois' ".$selected.">Month</option>";}
					
					$selected="";
					if($_SESSION['FiltreNbPersonneInscrite_ModeAffichage']=='Ann�e'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Ann�e' ".$selected.">Ann�e</option>";}
					else{echo "<option value='Ann�e' ".$selected.">Year</option>";}
					
					echo "</select>";
					?>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date d�but";}else{echo "Start date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreNbPersonneInscrite_DateDebut']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreNbPersonneInscrite_DateFin']); ?>"></td>
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
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unit� d'exploitation";}else{echo "Operating unit";}?> : </td>
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
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Type de contrat";}else{echo "Type of Contract";}?> : </td>
			</tr>
			<tr>
				<td>
					<table width='100%'>
						<?php
							$checked="";
							if($_POST){
								$checkboxes = isset($_POST['Id_TypeContrat']) ? $_POST['Id_TypeContrat'] : array();
								foreach($checkboxes as $value) {
									if($value=="'0'"){$checked="checked";}
								}
							}
							else{
								$checked="checked";	
							}
							echo "<tr><td>";
							echo "<input type='checkbox' class='checkTypeContrat' name='Id_TypeContrat[]' Id='Id_TypeContrat[]' value=\"'0'\" ".$checked.">";
							if($LangueAffichage=="FR"){echo "Int�rim";}else{echo "Interim";}
							echo "</td></tr>";
							
							$checked="";
							if($_POST){
								$checkboxes = isset($_POST['Id_TypeContrat']) ? $_POST['Id_TypeContrat'] : array();
								foreach($checkboxes as $value) {
									if("'1'"==$value){$checked="checked";}
								}
							}
							else{
								$checked="checked";	
							}
							
							echo "<tr><td>";
							echo "<input type='checkbox' class='checkTypeContrat' name='Id_TypeContrat[]' Id='Id_TypeContrat[]' value=\"'1'\" ".$checked.">";
							if($LangueAffichage=="FR"){echo "Salari�";}else{echo "Employee";}
							echo "</td></tr>";
							
							$checked="";
							if($_POST){
								$checkboxes = isset($_POST['Id_TypeContrat']) ? $_POST['Id_TypeContrat'] : array();
								foreach($checkboxes as $value) {
									if("'NULL'"==$value){$checked="checked";}
								}
							}
							else{
								$checked="checked";	
							}
							
							echo "<tr><td>";
							echo "<input type='checkbox' class='checkTypeContrat' name='Id_TypeContrat[]' Id='Id_TypeContrat[]' value=\"'NULL'\" ".$checked.">";
							if($LangueAffichage=="FR"){echo "NC";}else{echo "NC";}
							echo "</td></tr>";
						?>
					</table>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Cat�gorie";}else{echo "Category";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="checkbox" name="selectAllCategorie" id="selectAllCategorie" onclick="SelectionnerToutCategorie()" /><?php if($LangueAffichage=="FR"){echo "S�l. tout";}else{echo "Select all";} ?></td>
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
						$laformation=$_SESSION['FiltreNbPersonneInscrite_Formation'];

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