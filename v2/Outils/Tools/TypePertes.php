<?php
if($_POST){
	$Id_Platef="";
	if(isset($_POST['Id_Platef'])){
		if (is_array($_POST['Id_Platef'])) {
			foreach($_POST['Id_Platef'] as $value){
				if($Id_Platef<>''){$Id_Platef.=",";}
			  $Id_Platef.="'".$value."'";
			}
		} else {
			$value = $_POST['Id_Platef'];
			$Id_Platef = "'".$value."'";
		}
	}
	$Id_Presta="";
	if(isset($_POST['Id_Presta'])){
		if (is_array($_POST['Id_Presta'])) {
			foreach($_POST['Id_Presta'] as $value){
				if($Id_Presta<>''){$Id_Presta.=",";}
			  $Id_Presta.="'".$value."'";
			}
		} else {
			$value = $_POST['Id_Presta'];
			$Id_Presta = "'".$value."'";
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
	
	$_SESSION['FiltreTypePerteMateriel_Plateformes']=$Id_Platef;
	$_SESSION['FiltreTypePerteMateriel_Prestations']=$Id_Presta;
	$_SESSION['FiltreTypePerteMateriel_Types']=$Id_Type;
	$_SESSION['FiltreTypePerteMateriel_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreTypePerteMateriel_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreTypePerteMateriel_ModeAffichage']=$_POST['ModeAffichage'];
	$_SESSION['FiltreTypePerteMateriel_Plateforme']=$_POST['Id_Plateforme'];
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo">
					<?php
						if($_POST){
							if($_POST['DateDebut']<>"" && $_POST['DateFin']<>""){
								$dateDebut=TrsfDate_($_POST['DateDebut']);
								$dateFin=TrsfDate_($_POST['DateFin']);
								if($dateFin>=$dateDebut){
									$array=array();
									if($_SESSION['Langue']=="EN"){
										$arrayLegende=array(utf8_encode("Nombre"));
									}
									else{
										$arrayLegende=array(utf8_encode("Number"));
									}
									
									if($Id_Platef<>""){$in=" IN (".$Id_Platef.") ";}
									else{$in=" NOT IN (6,7,11,12,14,15,16,18,20,22,26)";}
									
									if($_SESSION['FiltreTypePerteMateriel_ModeAffichage']=="Plateforme"){
											$valeur1="(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)";
											$valeur2="(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB_Mouvement.Id_Prestation)";
										}
										else{
											$valeur1="Id_Prestation";
											$valeur2="TAB_Mouvement.Id_Prestation";
										}
										
										$req="
										SELECT COUNT(Id_TypeMateriel) AS Nombre, (SELECT Libelle FROM tools_typemateriel WHERE Id=Id_TypeMateriel) AS Type
										FROM
										(
										SELECT Id_TypeMateriel
										FROM
										(SELECT tools_materiel.Id,0 AS Type,
											tools_famillemateriel.Id_TypeMateriel
										FROM
											tools_materiel
										LEFT JOIN
											tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
										LEFT JOIN
											tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
										WHERE 
											tools_materiel.Suppr=0 
										UNION ALL 
										SELECT Id,
										1 AS Type,
										-1 AS Id_TypeMateriel
										FROM
											tools_caisse
										WHERE 
											tools_caisse.Suppr=0 ) AS TAB
										WHERE IF(TAB.Type=0,
											(SELECT IF(TAB_Mouvement.Id_Caisse=0,
														".$valeur2.",
														(
														SELECT ".$valeur1."
														FROM tools_mouvement
														WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
														ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
														)
												)
												FROM tools_mouvement AS TAB_Mouvement
												WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB.Id
												ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
											),
											(SELECT ".$valeur1."
												FROM tools_mouvement
												WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB.Id
												ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
											)
											) ".$in."
											
											AND 
											IF(TAB.Type=0,
											(SELECT IF(TAB_Mouvement.Id_Caisse=0,
														IF(TAB_Mouvement.Id_Lieu=0,'',(SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)),
														(
														SELECT IF(Id_Lieu=0,'',(SELECT Libelle FROM tools_lieu WHERE Id=tools_mouvement.Id_Lieu))
														FROM tools_mouvement
														WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
														ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
														)
												)
												FROM tools_mouvement AS TAB_Mouvement
												WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB.Id
												ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
											),
											(SELECT IF(Id_Lieu=0,'',(SELECT Libelle FROM tools_lieu WHERE Id=tools_mouvement.Id_Lieu))
												FROM tools_mouvement
												WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB.Id
												ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
											)
											) IN ('Perdu','Perdu officiellement')
											
											AND 
											IF(TAB.Type=0,
											(SELECT TAB_Mouvement.DateReception
												FROM tools_mouvement AS TAB_Mouvement
												WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB.Id
												ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
											),
											(SELECT DateReception
												FROM tools_mouvement
												WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB.Id
												ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
											)
											)>='".$dateDebut."'
											
											AND 
											IF(TAB.Type=0,
											(SELECT TAB_Mouvement.DateReception
												FROM tools_mouvement AS TAB_Mouvement
												WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB.Id
												ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
											),
											(SELECT DateReception
												FROM tools_mouvement
												WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB.Id
												ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
											)
											
											)<='".$dateFin."'
										";
										if($Id_Type<>""){$req.=" AND TAB.Id_TypeMateriel IN (".$Id_Type.") ";}
										$req.=") AS TAB2 GROUP BY Id_TypeMateriel 
										";
									//echo $req;
									$result=mysqli_query($bdd,$req);
									$nbenreg=mysqli_num_rows($result);
									
									$i=0;
									if($nbenreg>0){
										$total=0;
										$couleur="#d6d9dc";
								?>
									<tr >
										<td class="EnTeteTableauCompetences" width="20%">
											<?php 
											if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}

											?>
										</td>
										<td class="EnTeteTableauCompetences" width="16%"><?php if($LangueAffichage=="FR"){echo "Nombre";}else{echo "Number";}?></td>
									</tr>
								<?php
										
										
										while($row=mysqli_fetch_array($result)){


								?>
									<tr bgcolor="<?php echo $couleur; ?>">
										<td><?php echo $row['Type'] ; ?></td>
										<td><?php echo unNombreSinon0($row['Nombre']) ; ?></td>
									</tr>
								<?php 
											$array[$i]=array("Abscisse" => utf8_encode($row['Type']),"Nombre" => unNombreSinon0($row['Nombre']));
											$i++;
										
											if($couleur=="#d6d9dc"){$couleur="#ffffff";}
											else{$couleur="#d6d9dc";}
										}
									}
								}
							}
						}
						?>
		</table>
		<?php
			if($_POST){
				if($_POST['DateDebut']<>"" && $_POST['DateFin']<>""){
					$dateDebut=TrsfDate_($_POST['DateDebut']);
					$dateFin=TrsfDate_($_POST['DateFin']);
					if($dateFin>=$dateDebut){
		?>
		<table>
			<tr>
				<td height="400px;">
					<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "TYPE DE PERTES";}else{echo "TYPE OF LOSS";} ?></td>
							<td style="cursor:pointer;" align="right"></td>
						</tr>
						<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td valign="top">
								<div id="chart1" style="width:100%;height:400px"></div>
								<script>
									// Create chart instance
									var chart = am4core.create("chart1", am4charts.XYChart);
									
									chart.cursor = new am4charts.XYCursor();
									chart.scrollbarX = new am4core.Scrollbar();
											
									// Add data
									chart.data = <?php echo json_encode($array); ?>;

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
									series1.tooltipText = "{valueY.value}";
									series1.dataFields.categoryX = "Abscisse";
									series1.dataFields.valueY = "Nombre";
									series1.name = <?php echo json_encode($arrayLegende[0]); ?>;
									series1.stacked = false;
									series1.stroke  = "#3d7ad5";
									series1.fill  = "#3d7ad5";
									
									var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
									bullet1.label.text = "{valueY}";
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
		</table>
		<?php 
					}
				}
			}
		?>
	</td>
	<td align="right" valign="top" width="25%">
		<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreTypePerteMateriel_DateDebut']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreTypePerteMateriel_DateFin']); ?>"></td>
			</tr>
			<tr style="display:none;"><td height="4px"></td></tr>
			<tr style="display:none;">
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Mode d'affichage";}else{echo "Display mode";}?> : </td>
			</tr>
			<tr style="display:none;">
				<td>
					<?php 
					echo "<select name='ModeAffichage' id='ModeAffichage' OnChange='submit()'>";
					
					$selected="";
					if($_SESSION['FiltreTypePerteMateriel_ModeAffichage']=='Plateforme'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Plateforme' ".$selected.">Unité d'exploitation</option>";}
					else{echo "<option value='Plateforme' ".$selected.">Operating unit</option>";}
					
					$selected="";
					if($_SESSION['FiltreTypePerteMateriel_ModeAffichage']=='Prestation'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Prestation' ".$selected.">Prestation</option>";}
					else{echo "<option value='Prestation' ".$selected.">Site</option>";}

					$mode=$_SESSION['FiltreTypePerteMateriel_ModeAffichage'];
					echo "</select>";
					?>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> : </td>
			</tr>
			<tr>
				<td>
					<div id='Div_Type' style="height:150px;overflow:auto;">
						<table width='100%'>
							<?php
								$rqType="SELECT Id, Libelle FROM tools_typemateriel WHERE Suppr=0 ORDER BY Libelle";
								
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
					</div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr <?php if($_POST){if($mode<>"Plateforme"){echo "style='display:none';";} } ?>><td height="4px"></td></tr>
			<tr <?php if($_POST){if($mode<>"Plateforme"){echo "style='display:none';";} } ?>>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr <?php if($_POST){if($mode<>"Plateforme"){echo "style='display:none';";} } ?>>
				<td class="Libelle" width="10%"><input type="checkbox" name="selectAllPlateforme" id="selectAllPlateforme" onclick="SelectionnerToutPlateforme()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
			</tr>
			
			<tr <?php if($_POST){if($mode<>"Plateforme"){echo "style='display:none';";} } ?>>
				<td>
					<div id='Div_Prestations' style="height:200px;overflow:auto;">
						<table width='100%'>
							<?php

								$requetePlateforme="SELECT Id, Libelle
							FROM new_competences_plateforme
							WHERE Id NOT IN (6,7,11,12,14,15,16,18,20,22,26)
							ORDER BY Libelle";

								$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
								$Id_Plateforme=0;
								while($LigPlateforme=mysqli_fetch_array($resultPlateforme))
								{
									$checked="";
									if($_POST){
										$checkboxes = isset($_POST['Id_Platef']) ? $_POST['Id_Platef'] : array();
										foreach($checkboxes as $value) {
											if($LigPlateforme['Id']==$value){$checked="checked";}
										}
									}
									else{
										$checked="checked";	
									}
									
									echo "<tr><td>";
									echo "<input type='checkbox' class='checkPlateforme' name='Id_Platef[]' Id='Id_Platef[]' value='".$LigPlateforme['Id']."' ".$checked.">".stripslashes($LigPlateforme['Libelle']);
									echo "</td></tr>";
								}
							?>
						</table>
					</div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr <?php if($_POST){if($mode=="Plateforme"){echo "style='display:none';";} }else{echo "style='display:none';";} ?>>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr <?php if($_POST){if($mode=="Plateforme"){echo "style='display:none';";} }else{echo "style='display:none';";} ?>>
				<td>
					<?php 
					$requetePlateforme="SELECT Id, Libelle
							FROM new_competences_plateforme
							WHERE Id NOT IN (6,7,11,12,14,15,16,18,20,22,26)
							ORDER BY Libelle";
					$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
					echo "<select name='Id_Plateforme' id='Id_Plateforme' OnChange='submit()' >";
					
					$Id_Plateforme=$_SESSION['FiltreTypePerteMateriel_Plateformes'];
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
					$_SESSION['FiltreTypePerteMateriel_Plateforme']=$Id_Plateforme;
					
					echo "</select>";
					?>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr <?php if($_POST){if($mode=="Plateforme"){echo "style='display:none';";} }else{echo "style='display:none';";} ?> >
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?> : </td>
			</tr>
			<tr <?php if($_POST){if($mode=="Plateforme"){echo "style='display:none';";} }else{echo "style='display:none';";} ?>>
				<td class="Libelle" width="10%"><input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
			</tr>
			
			<tr <?php if($_POST){if($mode=="Plateforme"){echo "style='display:none';";} }else{echo "style='display:none';";} ?>>
				<td>
					<div id='Div_Prestations' style="height:200px;overflow:auto;">
						<table width='100%'>
							<?php

								$rqPrestation="SELECT Id AS Id_Prestation, 
									Id_Plateforme,
									Libelle
									FROM new_competences_prestation 
									WHERE new_competences_prestation.Active=0
									AND Id_Plateforme=".$Id_Plateforme." 
									ORDER BY Libelle ";

								$resultPrestation=mysqli_query($bdd,$rqPrestation);
								$Id_PrestationPole=0;
								while($rowPrestation=mysqli_fetch_array($resultPrestation))
								{
									$checked="";
									if($_POST){
										$checkboxes = isset($_POST['Id_Presta']) ? $_POST['Id_Presta'] : array();
										foreach($checkboxes as $value) {
											if($rowPrestation['Id_Prestation']==$value){$checked="checked";}
										}
									}
									else{
										$checked="checked";	
									}
									
									echo "<tr><td>";
									echo "<input type='checkbox' class='checkPresta' name='Id_Presta[]' Id='Id_Presta[]' value='".$rowPrestation['Id_Prestation']."' ".$checked.">".stripslashes(substr($rowPrestation['Libelle'],0,7));
									echo "</td></tr>";
								}
							?>
						</table>
					</div>
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