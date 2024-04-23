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
	$_SESSION['FiltreEPEIndicateurs_Responsable']=$_POST['manager'];
	
	$dateDebut=date($annee.'-01-01');
	$dateFin=date($annee.'-12-31');
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="80%">
		<table class="GeneralInfo" width="100%">
			<?php if($_POST){ 
				if($_POST['annee']<>""){
					$requete="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA 
						FROM new_rh_etatcivil
						RIGHT JOIN epe_personne_datebutoir 
						ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
						WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
						OR 
							(SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0
						) 
						AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
						AND TypeEntretien IN ('EPE')
						";
					//Vérifier si appartient à une prestation OPTEA ou compétence
					$requete.="AND 
						(
							SELECT COUNT(new_competences_personne_prestation.Id)
							FROM new_competences_personne_prestation
							LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
							WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
							AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
							AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
						)>0 ";
					$result=mysqli_query($bdd,$requete);
					$nbResulta=mysqli_num_rows($result);
					
					$Oui=0;
					$Non=0;

					if($nbResulta>0){
						while($row=mysqli_fetch_array($result))
						{
							$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
							TypeEntretien AS TypeE,
							IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,DateButoir,
							epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
							(SELECT Stress
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS RPS,
							(SELECT Id
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Id_EPE,
							(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS PrestaPole,
							(SELECT Id_Evaluateur
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Id_Manager,
							(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Manager
							FROM new_rh_etatcivil
							RIGHT JOIN epe_personne_datebutoir 
							ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
							WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
							OR 
								(SELECT COUNT(Id)
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0
							) 
							AND new_rh_etatcivil.Id=".$row['Id']."
							AND IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0,
							(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']."),
							'A faire') IN ('Signature salarié','Signature manager','Réalisé')
							AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
							AND TypeEntretien IN ('EPE')
							";
							
							$ResultNb=mysqli_query($bdd,$reqNb);
							$leNb=mysqli_num_rows($ResultNb);
							
							if($leNb>0){
								while($rowNb=mysqli_fetch_array($ResultNb))
								{
									$Id_Prestation=0;
									$Id_Pole=0;
									
									
									$req="SELECT Id_Prestation,Id_Pole 
										FROM new_competences_personne_prestation
										WHERE Id_Personne=".$row['Id']." 
										AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
										AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."') 
										ORDER BY Date_Fin DESC, Date_Debut DESC
										";
									$resultch=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultch);
									$Id_PrestationPole="0_0";
									if($nb>0){
										$rowMouv=mysqli_fetch_array($resultch);
										$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
									}

									$TableauPrestationPole=explode("_",$Id_PrestationPole);
									$Id_Prestation=$TableauPrestationPole[0];
									$Id_Pole=$TableauPrestationPole[1];

									
									$Plateforme="";
									$Presta="";
									$Id_Plateforme=0;
									$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,Id_Plateforme,LEFT(Libelle,7) AS Prestation FROM new_competences_prestation WHERE Id=".$Id_Prestation;
									$ResultPresta=mysqli_query($bdd,$req);
									$NbPrest=mysqli_num_rows($ResultPresta);
									if($NbPrest>0){
										$RowPresta=mysqli_fetch_array($ResultPresta);
										$Presta=$RowPresta['Prestation'];
										$Plateforme=$RowPresta['Plateforme'];
										$Id_Plateforme=$RowPresta['Id_Plateforme'];
									}
									
									$Pole="";
									$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
									$ResultPole=mysqli_query($bdd,$req);
									$NbPole=mysqli_num_rows($ResultPole);
									if($NbPole>0){
										$RowPole=mysqli_fetch_array($ResultPole);
										$Pole=$RowPole['Libelle'];
									}
									
									if($Pole<>""){$Presta.=" - ".$Pole;}
									
									$Manager="";
									$Id_Manager=0;
									$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPEIndicateurs_Annee']." ";
									$ResultlaPresta=mysqli_query($bdd,$req);
									$NblaPresta=mysqli_num_rows($ResultlaPresta);
									if($NblaPresta>0){
										$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
										$Id_Manager=$RowlaPresta['Id_Manager'];
										$Manager=$RowlaPresta['Manager'];
									}
									else{
										$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
												FROM new_competences_personne_poste_prestation 
												LEFT JOIN new_rh_etatcivil
												ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
												WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
												AND Id_Prestation=".$Id_Prestation."
												AND Id_Pole=".$Id_Pole."
												AND Id_Personne=".$row['Id']."
												ORDER BY Backup ";
										$ResultManager2=mysqli_query($bdd,$req);
										$NbManager2=mysqli_num_rows($ResultManager2);
										if($NbManager2>0){
											$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
												FROM new_competences_personne_poste_prestation 
												LEFT JOIN new_rh_etatcivil
												ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
												WHERE Id_Poste=".$IdPosteCoordinateurProjet."
												AND Id_Prestation=".$Id_Prestation."
												AND Id_Pole=".$Id_Pole."
												ORDER BY Backup ";
											$ResultManager=mysqli_query($bdd,$req);
											$NbManager=mysqli_num_rows($ResultManager);
											if($NbManager>0){
												$RowManager=mysqli_fetch_array($ResultManager);
												$Manager=$RowManager['Personne'];
												$Id_Manager=$RowManager['Id'];
											}
										}
										else{
											$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
												FROM new_competences_personne_poste_prestation 
												LEFT JOIN new_rh_etatcivil
												ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
												WHERE Id_Poste=".$IdPosteChefEquipe."
												AND Id_Prestation=".$Id_Prestation."
												AND Id_Pole=".$Id_Pole."
												AND Id_Personne=".$row['Id']."
												ORDER BY Backup ";
											$ResultManager2=mysqli_query($bdd,$req);
											$NbManager2=mysqli_num_rows($ResultManager2);
											if($NbManager2>0){
												$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
													FROM new_competences_personne_poste_prestation 
													LEFT JOIN new_rh_etatcivil
													ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
													WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
													AND Id_Prestation=".$Id_Prestation."
													AND Id_Pole=".$Id_Pole."
													ORDER BY Backup ";
												$ResultManager=mysqli_query($bdd,$req);
												$NbManager=mysqli_num_rows($ResultManager);
												if($NbManager>0){
													$RowManager=mysqli_fetch_array($ResultManager);
													$Manager=$RowManager['Personne'];
													$Id_Manager=$RowManager['Id'];
												}
											}
											else{
												$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
												FROM new_competences_personne_poste_prestation 
												LEFT JOIN new_rh_etatcivil
												ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
												WHERE Id_Poste=".$IdPosteChefEquipe."
												AND Id_Prestation=".$Id_Prestation."
												AND Id_Pole=".$Id_Pole."
												ORDER BY Backup ";
												$ResultManager=mysqli_query($bdd,$req);
												$NbManager=mysqli_num_rows($ResultManager);
												if($NbManager>0){
													$RowManager=mysqli_fetch_array($ResultManager);
													$Manager=$RowManager['Personne'];
													$Id_Manager=$RowManager['Id'];
												}
											}
										}
									}
									
									$trouve=1;
									if($_SESSION['FiltreEPEIndicateurs_Responsable']<>"0"){
										if($_SESSION['FiltreEPEIndicateurs_Responsable']<>$Id_Manager){
											$trouve=0;
										}
									}
									
									$existe=0;
									if(isset($_POST['Id_Plateforme'])){
										if (is_array($_POST['Id_Plateforme'])) {
											foreach($_POST['Id_Plateforme'] as $value){
												if($value==$Id_Plateforme){$existe=1;}
											}
										} else {
											if($_POST['Id_Plateforme']==$Id_Plateforme){$existe=1;}

										}
										if($existe==0){$trouve=0;}
									}
									
									if($trouve==1){
									

										if($rowNb['RPS']==1){$Oui++;}
										else{$Non++;}
									}
								}
							}
							
						}
					}
					
					$array[0]=array("Abscisse" => utf8_encode("Oui"),"Nombre" => $Oui);
					$array[1]=array("Abscisse" => utf8_encode("Non"),"Nombre" => $Non);
			?>
			<tr>
				<td height="200px;">
					<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "RPS";}else{echo "RPS";} ?></td>
							<td style="cursor:pointer;" align="right"></td>
						</tr>
						<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td valign="top">
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
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Responsable";}else{echo "Responsible";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Date entretien";}else{echo "Interview date";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Note";}else{echo "Note";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire salarié";}else{echo "Employee comment";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Dispositif d'accompagnement souhaités";}else{echo "Support system desired";} ?></td>
					<td class="EnTeteTableauCompetences" width="3%">
					&nbsp;<a style="text-decoration:none;" href="javascript:Excel_RPS();">
						<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
					</a>&nbsp;
					</td>
				</tr>
				<?php 
					if($nbResulta>0){
						$result=mysqli_query($bdd,$requete);
						$couleur="#d6d9dc";
						while($row=mysqli_fetch_array($result))
						{
							$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
							TypeEntretien AS TypeE,
							IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,DateButoir,
							epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
							IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0,
							(SELECT DateEntretien
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1)
							,
							'0001-01-01') AS DateEntretien,
							(SELECT Id
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Id_EPE,
							(SELECT Stress
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS RPS,
							(SELECT ComSStress
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS ComSStress,
							(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS PrestaPole,
							(SELECT Id_Evaluateur
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Id_Manager,
							(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Manager
							FROM new_rh_etatcivil
							RIGHT JOIN epe_personne_datebutoir 
							ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
							WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
							OR 
								(SELECT COUNT(Id)
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0
							) 
							AND new_rh_etatcivil.Id=".$row['Id']."
							AND IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0,
							(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']."),
							'A faire') IN ('Signature salarié','Signature manager','Réalisé')
							AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
							AND TypeEntretien IN ('EPE')
							";
							
							$ResultNb=mysqli_query($bdd,$reqNb);
							$leNb=mysqli_num_rows($ResultNb);
							
							if($leNb>0){
								
								while($rowNb=mysqli_fetch_array($ResultNb))
								{
									$Id_Prestation=0;
									$Id_Pole=0;

									$req="SELECT Id_Prestation,Id_Pole 
										FROM new_competences_personne_prestation
										WHERE Id_Personne=".$row['Id']." 
										AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
										AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."') 
										ORDER BY Date_Fin DESC, Date_Debut DESC
										";
									$resultch=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultch);
									$Id_PrestationPole="0_0";
									if($nb>0){
										$rowMouv=mysqli_fetch_array($resultch);
										$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
									}

									$TableauPrestationPole=explode("_",$Id_PrestationPole);
									$Id_Prestation=$TableauPrestationPole[0];
									$Id_Pole=$TableauPrestationPole[1];

									
									$Plateforme="";
									$Presta="";
									$Id_Plateforme=0;
									$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,Id_Plateforme,LEFT(Libelle,7) AS Prestation FROM new_competences_prestation WHERE Id=".$Id_Prestation;
									$ResultPresta=mysqli_query($bdd,$req);
									$NbPrest=mysqli_num_rows($ResultPresta);
									if($NbPrest>0){
										$RowPresta=mysqli_fetch_array($ResultPresta);
										$Presta=$RowPresta['Prestation'];
										$Plateforme=$RowPresta['Plateforme'];
										$Id_Plateforme=$RowPresta['Id_Plateforme'];
									}
									
									$Pole="";
									$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
									$ResultPole=mysqli_query($bdd,$req);
									$NbPole=mysqli_num_rows($ResultPole);
									if($NbPole>0){
										$RowPole=mysqli_fetch_array($ResultPole);
										$Pole=$RowPole['Libelle'];
									}
									
									if($Pole<>""){$Presta.=" - ".$Pole;}
									
									$Manager="";
									$Id_Manager=0;
									$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPEIndicateurs_Annee']." ";
									$ResultlaPresta=mysqli_query($bdd,$req);
									$NblaPresta=mysqli_num_rows($ResultlaPresta);
									if($NblaPresta>0){
										$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
										$Id_Manager=$RowlaPresta['Id_Manager'];
										$Manager=$RowlaPresta['Manager'];
									}
									else{
										$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
												FROM new_competences_personne_poste_prestation 
												LEFT JOIN new_rh_etatcivil
												ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
												WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
												AND Id_Prestation=".$Id_Prestation."
												AND Id_Pole=".$Id_Pole."
												AND Id_Personne=".$row['Id']."
												ORDER BY Backup ";
										$ResultManager2=mysqli_query($bdd,$req);
										$NbManager2=mysqli_num_rows($ResultManager2);
										if($NbManager2>0){
											$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
												FROM new_competences_personne_poste_prestation 
												LEFT JOIN new_rh_etatcivil
												ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
												WHERE Id_Poste=".$IdPosteCoordinateurProjet."
												AND Id_Prestation=".$Id_Prestation."
												AND Id_Pole=".$Id_Pole."
												ORDER BY Backup ";
											$ResultManager=mysqli_query($bdd,$req);
											$NbManager=mysqli_num_rows($ResultManager);
											if($NbManager>0){
												$RowManager=mysqli_fetch_array($ResultManager);
												$Manager=$RowManager['Personne'];
												$Id_Manager=$RowManager['Id'];
											}
										}
										else{
											$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
												FROM new_competences_personne_poste_prestation 
												LEFT JOIN new_rh_etatcivil
												ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
												WHERE Id_Poste=".$IdPosteChefEquipe."
												AND Id_Prestation=".$Id_Prestation."
												AND Id_Pole=".$Id_Pole."
												AND Id_Personne=".$row['Id']."
												ORDER BY Backup ";
											$ResultManager2=mysqli_query($bdd,$req);
											$NbManager2=mysqli_num_rows($ResultManager2);
											if($NbManager2>0){
												$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
													FROM new_competences_personne_poste_prestation 
													LEFT JOIN new_rh_etatcivil
													ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
													WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
													AND Id_Prestation=".$Id_Prestation."
													AND Id_Pole=".$Id_Pole."
													ORDER BY Backup ";
												$ResultManager=mysqli_query($bdd,$req);
												$NbManager=mysqli_num_rows($ResultManager);
												if($NbManager>0){
													$RowManager=mysqli_fetch_array($ResultManager);
													$Manager=$RowManager['Personne'];
													$Id_Manager=$RowManager['Id'];
												}
											}
											else{
												$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
												FROM new_competences_personne_poste_prestation 
												LEFT JOIN new_rh_etatcivil
												ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
												WHERE Id_Poste=".$IdPosteChefEquipe."
												AND Id_Prestation=".$Id_Prestation."
												AND Id_Pole=".$Id_Pole."
												ORDER BY Backup ";
												$ResultManager=mysqli_query($bdd,$req);
												$NbManager=mysqli_num_rows($ResultManager);
												if($NbManager>0){
													$RowManager=mysqli_fetch_array($ResultManager);
													$Manager=$RowManager['Personne'];
													$Id_Manager=$RowManager['Id'];
												}
											}
										}
									}
									
									$trouve=1;
									if($_SESSION['FiltreEPEIndicateurs_Responsable']<>"0"){
										if($_SESSION['FiltreEPEIndicateurs_Responsable']<>$Id_Manager){
											$trouve=0;
										}
									}
									
									$existe=0;
									if(isset($_POST['Id_Plateforme'])){
										if (is_array($_POST['Id_Plateforme'])) {
											foreach($_POST['Id_Plateforme'] as $value){
												if($value==$Id_Plateforme){$existe=1;}
											}
										} else {
											if($_POST['Id_Plateforme']==$Id_Plateforme){$existe=1;}

										}
										if($existe==0){$trouve=0;}
									}
									
									if($trouve==1){
										if($rowNb['RPS']==1){
											$dispositif="";
											$req="SELECT EntretienRH, EntretienMedecienTravail, EntretienLumanisy, EntretienSoutienPsycho, EntretienHSE, EntretienAutre, ComEntretienAutre, 
												FormationOrganisationTravail, FormationStress,FormationAutre,ComFormationAutre
												FROM epe_personne
												WHERE Id=".$rowNb['Id_EPE'];
											$ResultEPE=mysqli_query($bdd,$req);
											$NbEPE=mysqli_num_rows($ResultEPE);
											if($NbEPE>0){
												$RowEPE=mysqli_fetch_array($ResultEPE);
												if($RowEPE['EntretienRH']==1){$dispositif.="Entretien RH";}
												if($RowEPE['EntretienMedecienTravail']==1){
													if($dispositif<>""){$dispositif.="<br>";}
													$dispositif.="Entretien avec la médecine du travail";
												}
												if($RowEPE['EntretienLumanisy']==1){
													if($dispositif<>""){$dispositif.="<br>";}
													$dispositif.="Entretien avec le service social du travail";
												}
												if($RowEPE['EntretienSoutienPsycho']==1){
													if($dispositif<>""){$dispositif.="<br>";}
													$dispositif.="Soutien psychologique";
												}
												if($RowEPE['EntretienHSE']==1){
													if($dispositif<>""){$dispositif.="<br>";}
													$dispositif.="Entretien avec le service HSE";
												}
												if($RowEPE['EntretienAutre']==1){
													if($dispositif<>""){$dispositif.="<br>";}
													$dispositif.=$RowEPE['ComEntretienAutre'];
												}
												if($RowEPE['FormationOrganisationTravail']==1){
													if($dispositif<>""){$dispositif.="<br>";}
													$dispositif.="Formation Organisation du travail, gestion du temps et des priorités";
												}
												if($RowEPE['FormationStress']==1){
													if($dispositif<>""){$dispositif.="<br>";}
													$dispositif.="Formation Gestion du stress";
												}
												if($RowEPE['FormationAutre']==1){
													if($dispositif<>""){$dispositif.="<br>";}
													$dispositif.=$RowEPE['ComFormationAutre'];
												}
												
											}
											?>
												<tr bgcolor="<?php echo $couleur; ?>">
													<td><?php echo stripslashes($row['MatriculeAAA']);?></td>
													<td><?php echo stripslashes($row['Personne']);?></td>
													<td><?php echo stripslashes($Plateforme);?></td>
													<td><?php echo stripslashes($Presta);?></td>
													<td><?php echo stripslashes($Manager);?></td>
													<td><?php echo AfficheDateJJ_MM_AAAA($rowNb['DateEntretien']);?></td>
													<td><?php echo stripslashes($rowNb['ComSStress']);?></td>
													<td colspan="2"><?php echo stripslashes($dispositif);?></td>
												</tr>
											<?php 
											if($couleur=="#d6d9dc"){$couleur="#ffffff";}
											else{$couleur="#d6d9dc";}
										}
									}
								}
							}
							
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
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Année";}else{echo "Year";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $_SESSION['FiltreEPEIndicateurs_Annee']; ?>" size="5"/></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Manager";}else{echo "Manager";}?> : </td>
			</tr>
			<tr>
				<td>
					<select id="manager" style="width:150px;" name="manager" onchange="submit();">
						<option value='0'></option>
						<?php
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM new_competences_personne_poste_prestation
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=new_competences_personne_poste_prestation.Id_Personne
									WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.")
									AND new_rh_etatcivil.Id>0
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
							$requetePersonne.="ORDER BY Personne ASC";
							$resultPersonne=mysqli_query($bdd,$requetePersonne);
							$NbPersonne=mysqli_num_rows($resultPersonne);

							while($rowPersonne=mysqli_fetch_array($resultPersonne))
							{
								echo "<option value='".$rowPersonne['Id']."'";
								if($_POST){
									if ($_POST['manager'] == $rowPersonne['Id']){echo " selected ";}
								}
								echo ">".$rowPersonne['Personne']."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
					ORDER BY Libelle";
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