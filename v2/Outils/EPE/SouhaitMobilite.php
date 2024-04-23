<?php
if($_POST){
	$Id_Plateforme="";
	if(isset($_POST['Id_Plateforme'])){
		if (is_array($_POST['Id_Plateforme'])) {
			foreach($_POST['Id_Plateforme'] as $value){
				if($Id_Plateforme<>''){$Id_Plateforme.=",";}
			  $Id_Plateforme.=$value;
			}
		} else {
			$value = $_POST['Id_Plateforme'];
			$Id_Plateforme = $value;
		}
	}
	
	if($_POST){$annee=$_POST['annee'];}
	if($annee==""){$annee=date("Y");}
	
	$_SESSION['FiltreEPEIndicateurs_Plateforme']=$Id_Plateforme;
	$_SESSION['FiltreEPEIndicateurs_Annee']=$annee;
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="80%">
		<table class="GeneralInfo" width="100%">
			<?php if($_POST){ 
				if($_POST['annee']<>""){
				$requete="SELECT Id,SouhaitMobilite,SouhaitMobiliteON
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type='EPP' 
					AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreSouhaitEPP_Annee']." 
					AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salari�',IF(DateEvaluateur>'0001-01-01','R�alis�','Signature manager'))) IN ('Signature salari�','Signature manager','R�alis�') ";
				if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
					
				}
				else{
					$requete.="
					AND
					( (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
						)
					)
						";
				}
				if($_SESSION['FiltreEPEIndicateurs_Plateforme']<>""){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";}
				$result=mysqli_query($bdd,$requete);
				$nbResulta=mysqli_num_rows($result);
					
				$Oui=0;
				$Non=0;
				if($nbResulta>0){
					while($row=mysqli_fetch_array($result))
					{
						if($row['SouhaitMobiliteON']==1){$Oui++;}
						else{$Non++;}
					}
				}
				$array[0]=array("Abscisse" => utf8_encode("Oui"),"Nombre" => $Oui);
				$array[1]=array("Abscisse" => utf8_encode("Non"),"Nombre" => $Non);
			?>
			<tr>
				<td height="200px;">
					<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "Souhait de mobilit� g�ographique";}else{echo "Desire for geographic mobility";} ?></td>
							<td style="cursor:pointer;" align="right"></td>
						</tr>
						<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td valign="top" width="50%" >
								<div id="chart_taux" style="width:100%;height:200px"></div>
								<script>
									// Create chart instance
									var chart = am4core.create("chart_taux", am4charts.PieChart);

								// Add data
								chart.data = <?php echo json_encode($array); ?>;
								
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
							<td valign="top" width="50%" rowspan="2">
								<?php 
									$requete="
									SELECT Id_SouhaitMobilite, COUNT(Id_SouhaitMobilite) AS Nb,
										(SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite) AS SouhaitMobilite
									FROM (
									SELECT DISTINCT Id_EPE, Id_SouhaitMobilite
										FROM epe_personne_souhaitmobilite
										LEFT JOIN epe_personne
										ON epe_personne_souhaitmobilite.Id_EPE=epe_personne.Id
										WHERE epe_personne.Suppr=0 AND epe_personne.Type='EPP' 
										AND SouhaitMobiliteON=1
										AND PasMobiliteEPP=0
										AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreSouhaitEPP_Annee']." 
										AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salari�',IF(DateEvaluateur>'0001-01-01','R�alis�','Signature manager'))) IN ('Signature salari�','Signature manager','R�alis�') ";
									if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
										
									}
									else{
										$requete.="
										AND
										( (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN 
											(
												SELECT Id_Plateforme 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']." 
												AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
											)
										)
											";
									}
									if($_SESSION['FiltreEPEIndicateurs_Plateforme']<>""){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";}
									$requete.=") AS TAB 
									GROUP BY Id_SouhaitMobilite
									ORDER BY SouhaitMobilite
									";
									$result=mysqli_query($bdd,$requete);
									$nbResulta=mysqli_num_rows($result);
										
									if($nbResulta>0){
									?>
										<table align="center" cellpadding="0" cellspacing="0" width="60%" >
											<tr><td style="border:solid black 1px;" align="center" bgcolor="#d2d6da" >Souhait exprim�</td><td style="border:solid black 1px;" align="center" bgcolor="#d2d6da" >Nombre de salari�</td></tr>
									<?php
										while($row=mysqli_fetch_array($result))
										{
									?>
											<tr><td style="border:solid black 1px;" align="center"><?php echo $row['SouhaitMobilite']; ?></td><td style="border:solid black 1px;" align="center"><?php echo $row['Nb']; ?></td></tr>
									<?php		
										}
									?>
										</table>
									<?php 
									}
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		<?php	
			}
		}
		?>
		</table>
		<?php if($_POST){ 
				if($_POST['annee']<>""){
		?>
		<table class="GeneralInfo" width="100%">
			<tr>
				<tr>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
					<td class="EnTeteTableauCompetences" width="9%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
					<td class="EnTeteTableauCompetences" width="9%"><?php if($_SESSION["Langue"]=="FR"){echo "M�tier (Paie)";}else{echo "Job (Payroll)";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Unit� d'exploitation";}else{echo "Operating unit";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Responsable";}else{echo "Responsible";} ?></td>
					
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Mobilit�";}else{echo "Mobility";} ?></td>
					<td class="EnTeteTableauCompetences" width="3%">
					&nbsp;<a style="text-decoration:none;" href="javascript:Excel_M();">
						<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
					</a>&nbsp;
					</td>
				</tr>
				<?php 
					$requete="
						SELECT DISTINCT Id_EPE, Id_SouhaitMobilite,
							(SELECT MetierPaie FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MetierPaie,
							(SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite) AS SouhaitMobilite,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
							(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
							(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
							(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
							(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager
							FROM epe_personne_souhaitmobilite
							LEFT JOIN epe_personne
							ON epe_personne_souhaitmobilite.Id_EPE=epe_personne.Id
							WHERE epe_personne.Suppr=0 AND epe_personne.Type='EPP' 
							AND SouhaitMobiliteON=1
							AND PasMobiliteEPP=0
							AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreSouhaitEPP_Annee']." 
							AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salari�',IF(DateEvaluateur>'0001-01-01','R�alis�','Signature manager'))) IN ('Signature salari�','Signature manager','R�alis�') ";
						if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
							
						}
						else{
							$requete.="
							AND
							( (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
								)
							)
								";
						}
						if($_SESSION['FiltreEPEIndicateurs_Plateforme']<>""){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";}
						$requete.="ORDER BY Personne";
						$result=mysqli_query($bdd,$requete);
						$nbResulta=mysqli_num_rows($result);
						
					if($nbResulta>0){
						$result=mysqli_query($bdd,$requete);
						$couleur="#d6d9dc";
						while($row=mysqli_fetch_array($result))
						{
							
							?>
								<tr bgcolor="<?php echo $couleur; ?>">
									<td><?php echo stripslashes($row['MatriculeAAA']);?></td>
									<td><?php echo stripslashes($row['Personne']);?></td>
									<td><?php echo stripslashes($row['MetierPaie']);?></td>
									<td><?php echo stripslashes($row['Prestation']);?></td>
									<td><?php echo stripslashes($row['Plateforme']);?></td>
									<td><?php echo stripslashes($row['Manager']);?></td>
									<td colspan="2" ><?php echo stripslashes($row['SouhaitMobilite']);?></td>
								</tr>
							<?php 
							if($couleur=="#d6d9dc"){$couleur="#ffffff";}
							else{$couleur="#d6d9dc";}
							
						}
					}
				?>
			</tr>
		</table>
		<?php	
			}
		}
		?>
	</td>
	<td align="right" valign="top" width="20%">
		<table class="GeneralInfo" style="border-spacing:0; width:100%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Ann�e";}else{echo "Year";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $_SESSION['FiltreEPEIndicateurs_Annee']; ?>" size="5"/></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unit� d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" checked /><?php if($LangueAffichage=="FR"){echo "S�l. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
					if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
						
					}
					else{
						$requetePlateforme.="
						AND
						( Id IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
							)
						)
							";
					}
					$requetePlateforme.="ORDER BY Libelle";
					$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
					
					while($LigPlateforme=mysqli_fetch_array($resultPlateforme)){
						$checked="";
						if($_POST){
							$checkboxes = isset($_POST['Id_Plateforme']) ? $_POST['Id_Plateforme'] : array();
							foreach($checkboxes as $value) {
								if($LigPlateforme['Id']==$value){$checked="checked";}
							}
						}
						else{
							$checked="checked";	
						}
						echo "<tr><td>";
						echo "<input type='checkbox' class='checkPlateforme' name='Id_Plateforme[]' Id='Id_Plateforme[]' value='".$LigPlateforme['Id']."' ".$checked." >".$LigPlateforme['Libelle'];
						echo "</td></tr>";
					}
					
					?>
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