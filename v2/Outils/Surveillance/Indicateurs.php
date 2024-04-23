<?php
require("../../Menu.php");
?>
<script type="text/javascript" language="Javascript" src="Indicateurs.js"></script>
<?php
require("FonctionsRequete.php");

/* pChart library inclusions */
 include("../../pChart/class/pData.class.php");
 include("../../pChart/class/pDraw.class.php");
 include("../../pChart/class/pImage.class.php"); 
 include("../../pChart/class/pScatter.class.php"); 

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$Annee = date("Y",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Indicateurs.php">
	<tr>
		<td colspan=3>
			<table class="GeneralPage" width="100%" cellpadding="0" cellspacing="0" style="background-color:#292adf;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Surveillance/Tableau_De_Bord.php'>";
							if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
							if($_SESSION["Langue"]=="FR"){echo "Gestion des surveillances # Graphiques & Indicateurs";}
							else{echo "Monitoring management # Charts & KPIs";}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		/* Create and populate the pData object */
		if((isset($_POST['Plateforme']) || isset($_POST['Prestation']))  && isset($_POST['legende']) && isset($_POST['annee']) && (isset($_POST['Generique']) || isset($_POST['Specifique']))){
			$bLegendeValide = true;
			$Legende = "";
			foreach($_POST['legende'] as $chkbx){
				$Legende = $chkbx;
				if ($chkbx == "NbSurveillance" ){
					$Legende = "NbSurveillance";
					if (isset($_POST['EtatSurveillance']) == false){$bLegendeValide = false;}
				}
			}
			if($Legende == "NumNC" || $Legende == "NumNA"){
				$ParTitre = ""; 
				$orientationAbscisse = 90;
				$Titrepart1 = "";
				$Generique = "";
				$Specifique = "";
				if (isset($_POST['Generique'])){$Generique = $_POST['Generique'];}
				if (isset($_POST['Specifique'])){$Specifique =$_POST['Specifique'];}
				
				$arrayQuestion = array();
				$arrayLegende = array();
				if($Legende == "NumNC"){$requeteContenu = reqNumQuestion($_POST['Prestation'],$_POST['annee'],"NC",$Generique,$Specifique);}
				else{$requeteContenu = reqNumQuestion($_POST['Prestation'],$_POST['annee'],"NA",$Generique,$Specifique);}

				$resultContenu=mysqli_query($bdd,$requeteContenu);
				$nbContenu=mysqli_num_rows($resultContenu);
				
				$arrayGraph=array();
				if ($nbContenu > 0){
					mysqli_data_seek($resultContenu,0);
					$i=0;
					while($rowContenu=mysqli_fetch_array($resultContenu)){
						$arrayQuestion[] = $rowContenu['Theme']." ".$rowContenu['Questionnaire']." ".$rowContenu['Numero'];
						$arrayLegende[] = $rowContenu['NbQuestion'];
						$arrayGraph[$i]=array("Abscisse" => utf8_encode($rowContenu['Theme']." ".$rowContenu['Questionnaire']." ".$rowContenu['Numero']),"NbQuestion" => $rowContenu['NbQuestion']);
						$i++;
					}

					 ?>
					 <tr>
						<td height="650px;" align="center">
							<table class="TableCompetences" width="70%" height="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($Legende == "NumNC"){echo "N° DES QUESTIONS NC";} else{echo "N° DES QUESTIONS NA";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								 <tr>
									<td valign="top">
										<div id="chart_Graph1" style="width:100%;height:650px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_Graph1", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayGraph); ?>;
										
										// Create axes
										var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Abscisse";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 10;
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
										series1.tooltipText = "{categoryX} \n {name}: {valueY.value}";
										series1.dataFields.categoryX = "Abscisse";
										series1.dataFields.valueY = "NbQuestion";
										series1.name = <?php echo json_encode("Nombre de questions"); ?>;
										series1.stacked = false;
										series1.stroke  = "#126bc6";
										series1.fill  = "#126bc6";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;


										// Cursor
										chart.cursor = new am4charts.XYCursor();
										chart.cursor.behavior = "panX";
										chart.cursor.lineX.opacity = 0;
										chart.cursor.lineY.opacity = 0;
										
										chart.scrollbarX = new am4core.Scrollbar();
										
										chart.exporting.menu = new am4core.ExportMenu();
										
										chart.exporting.menu.items =
										[
										  {
											"label": "...",
											"menu": [
											  {
												"label": "Image",
												"menu": [
												  { "type": "png", "label": "PNG" },
												  { "type": "jpg", "label": "JPG" },
												  { "type": "svg", "label": "SVG" }
												]
											  }, {
												"label": "Data",
												"menu": [
												  { "type": "csv", "label": "CSV" },
												  { "type": "xlsx", "label": "XLSX" },
												  { "type": "html", "label": "HTML" }
												]
											  }, {
												"label": "Print", "type": "print"
											  }
											]
										  }
										];
										
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
			else{
				if(isset($_POST['abscisse'])){
					if($bLegendeValide == true){
						$ParTitre = ""; 
						$orientationAbscisse = 90;
						$Titrepart1 = "";
						$Generique = "";
						$Specifique = "";
						$arrayGraph2=array();
						$checkChamps1=0;
						$checkChamps2=0;
						$checkChamps3=0;
						$checkChamps4=0;
						if (isset($_POST['Generique'])){$Generique = $_POST['Generique'];}
						if (isset($_POST['Specifique'])){$Specifique =$_POST['Specifique'];}
						 /* ------------------------ABSCISSE + LEGENDE---------------------*/
						 if($_POST['abscisse']== "Mois"){
							$ParTitre="MOIS";
							$MoisLettre = array("Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec");
							$arrayMois = array($_POST['annee']."_01",$_POST['annee']."_02",$_POST['annee']."_03",$_POST['annee']."_04",$_POST['annee']."_05",$_POST['annee']."_06",$_POST['annee']."_07",$_POST['annee']."_08",$_POST['annee']."_09",$_POST['annee']."_10",$_POST['annee']."_11",$_POST['annee']."_12");
							if($Legende == "NbSurveillance"){
								$arrayPlanif = array();
								$arrayCloture = array();
								$requeteContenu = reqNbSurveillanceMois($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$i=0;
								foreach($arrayMois as $leMois){
									$bTrouvePlanif = false;
									$bTrouveRePlanif = false;
									$bTrouveRealise = false;
									$bTrouveCloture = false;
									if ($nbContenu > 0){
										mysqli_data_seek($resultContenu,0);
										while($rowContenu=mysqli_fetch_array($resultContenu)){
											if($rowContenu['leMois'] == $leMois){
												if($rowContenu['Etat'] == "Planifié" || $rowContenu['Etat'] == "Replanifié"){
													$arrayPlanif[] = $rowContenu['Nb'];
													$bTrouvePlanif = true;
												}
												elseif($rowContenu['Etat'] == "Clôturé" || $rowContenu['Etat'] == "Réalisé"){
													$arrayCloture[] = $rowContenu['Nb'];
													$bTrouveCloture = true;
												}
											}
										}
									}
									if ($bTrouvePlanif == false){
										$arrayPlanif[] = 0;
									}
									if ($bTrouveCloture == false){
										$arrayCloture[] = 0;
									}
									
									$arrayGraph2[$i]=array("Abscisse" => utf8_encode($MoisLettre[$i]." ".$_POST['annee']),"Champs1" => $arrayPlanif[$i],"Champs2" => $arrayCloture[$i]);
									$i++;
								}
								
								$arrayLegende=array(utf8_encode("Surveillances planifiées"),utf8_encode("Surveillances côturées"));
								foreach($_POST['EtatSurveillance'] as $chkbx){
									if($chkbx == "Planifie"){
										$checkChamps1=1;
									}
									elseif($chkbx == "Cloturee"){
										$checkChamps2=1;
									}
								}
							}
							elseif($Legende == "MoyenneNotes"){
								$arrayValeur = array();
								$requeteContenu = reqMoyenneNoteMois($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Note"));
								foreach($arrayMois as $leMois){
									$bTrouveLegende = false;
									if ($nbContenu > 0){
										mysqli_data_seek($resultContenu,0);
										while($rowContenu=mysqli_fetch_array($resultContenu)){
											if($rowContenu['MoisAnnee'] == $leMois){
												$arrayValeur[] = $rowContenu['Note'];
												$bTrouveLegende = true;
											}
										}
									}
									if ($bTrouveLegende == false){
										$arrayValeur[] = 0;
									}
									$arrayGraph2[$i]=array("Abscisse" => utf8_encode($MoisLettre[$i]." ".$_POST['annee']),"Champs1" => $arrayValeur[$i]);
									$i++;
								}
							}
							elseif($Legende == "DeltaPlaRea"){
								$arrayValeur = array();
								$requeteContenu = reqDELTADatePlaReaMois($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Delta"));
								foreach($arrayMois as $leMois){
									$nbJour = 0;
									$nbSurveillance = 0;
									if ($nbContenu > 0){
										mysqli_data_seek($resultContenu,0);
										while($rowContenu=mysqli_fetch_array($resultContenu)){
											if($rowContenu['MoisAnnee'] == $leMois){
												$nbSurveillance++;
												$nbJour += getJours($rowContenu['Date_Debut'],$rowContenu['Date_Fin']);
											}
										}
									}
									if ($nbSurveillance == 0){
										$arrayValeur[] = 0;
									}
									else{
										$arrayValeur[] = round($nbJour/$nbSurveillance,1);
									}
									$arrayGraph2[$i]=array("Abscisse" => utf8_encode($MoisLettre[$i]." ".$_POST['annee']),"Champs1" => $arrayValeur[$i]);
									$i++;
								}
							}
							elseif($Legende == "DeltaReaClo"){
								$arrayValeur = array();
								$requeteContenu = reqDELTADateReaCloMois($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Delta"));
								foreach($arrayMois as $leMois){
									$nbJour = 0;
									$nbSurveillance = 0;
									if ($nbContenu > 0){
										mysqli_data_seek($resultContenu,0);
										while($rowContenu=mysqli_fetch_array($resultContenu)){
											if($rowContenu['MoisAnnee'] == $leMois){
												$nbSurveillance++;
												$nbJour += getJours($rowContenu['Date_Debut'],$rowContenu['Date_Fin']);
											}
										}
									}
									if ($nbSurveillance == 0){
										$arrayValeur[] = 0;
									}
									else{
										$arrayValeur[] = round($nbJour/$nbSurveillance,1);
									}
									$arrayGraph2[$i]=array("Abscisse" => utf8_encode($MoisLettre[$i]." ".$_POST['annee']),"Champs1" => $arrayValeur[$i]);
									$i++;
								}
							}
						 }
						 elseif($_POST['abscisse']== "Plateforme"){
							$array = array();
							$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle ";
							$req .= "FROM new_competences_plateforme ";
							$req .= "WHERE Id<> 11 AND Id<>14 AND ( ";
							foreach($_POST['Plateforme'] as $chkbx){
								$req .= "Id=".substr($chkbx,4)." OR ";
							}
							$req = substr($req,0,-3);
							$req .= ") ORDER BY new_competences_plateforme.Libelle;";
							$resultPlateforme=mysqli_query($bdd,$req);
							$nbPlateforme=mysqli_num_rows($resultPlateforme);
							
							if($Legende == "NbSurveillance"){
								$arrayPlanif = array();
								$arrayCloture = array();
								$requeteContenu = reqNbSurveillancePlateforme($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);

								if ($nbPlateforme > 0){
									$i=0;
									while($row=mysqli_fetch_array($resultPlateforme)){
										$array[] = $row['Libelle'];
										$bTrouvePlanif = false;
										$bTrouveCloture = false;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['Id_Plateforme'] == $row['Id']){
													if($rowContenu['Etat'] == "Planifié" || $rowContenu['Etat'] == "Replanifié"){
														$arrayPlanif[] = $rowContenu['Nb'];
														$bTrouvePlanif = true;
													}
													elseif($rowContenu['Etat'] == "Clôturé" || $rowContenu['Etat'] == "Réalisé"){
														$arrayCloture[] = $rowContenu['Nb'];
														$bTrouveCloture = true;
													}
												}
											}
										}
										if ($bTrouvePlanif == false){
											$arrayPlanif[] = 0;
										}
										if ($bTrouveCloture == false){
											$arrayCloture[] = 0;
										}
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode($row['Libelle']),"Champs1" => $arrayPlanif[$i],"Champs2" => $arrayCloture[$i]);
										$i++;
									}
								}
								
								$arrayLegende=array(utf8_encode("Surveillances planifiées"),utf8_encode("Surveillances côturées"));
								foreach($_POST['EtatSurveillance'] as $chkbx){
									if($chkbx == "Planifie"){
										$checkChamps1=1;
									}
									elseif($chkbx == "Cloturee"){
										$checkChamps2=1;
									}
								}
							}
							elseif($Legende == "MoyenneNotes"){
								$arrayValeur = array();
								$requeteContenu = reqMoyenneNotePlateforme($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Note"));
								if ($nbPlateforme > 0){
									while($row=mysqli_fetch_array($resultPlateforme)){
										$array[] = $row['Libelle'];
										$bTrouveLegende = false;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['Id_Plateforme'] == $row['Id']){
													$arrayValeur[] = $rowContenu['Note'];
													$bTrouveLegende = true;
												}
											}
										}
										if ($bTrouveLegende == false){
											$arrayValeur[] = 0;
										}
										
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode($row['Libelle']),"Champs1" => $arrayValeur[$i]);
										$i++;
									}
								}
							}
							elseif($Legende == "DeltaPlaRea"){
								/*
								$arrayValeur = array();
								$requeteContenu = reqDELTADatePlaReaPlateforme($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Delta"));
								if ($nbPlateforme > 0){
									$i=0;
									while($row=mysqli_fetch_array($resultPlateforme)){
										$array[] = $row['Libelle'];
										$nbJour = 0;
										$nbSurveillance = 0;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['Id_Plateforme'] == $row['Id']){
													$nbSurveillance++;
													$nbJour += getJours($rowContenu['Date_Debut'],$rowContenu['Date_Fin']);
												}
											}
										}
										if ($nbSurveillance == 0){
											$arrayValeur[] = 0;
										}
										else{
											$arrayValeur[] = round($nbJour/$nbSurveillance,1);
										}
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode($row['Libelle']),"Champs1" => $arrayValeur[$i]);
										$i++;
									}
								}
								*/
							}
							elseif($Legende == "DeltaReaClo"){
								/*
								$arrayValeur = array();
								$requeteContenu = reqDELTADateReaCloPlateforme($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Delta"));
								if ($nbPlateforme > 0){
									$i=0;
									while($row=mysqli_fetch_array($resultPlateforme)){
										$array[] = $row['Libelle'];
										$nbJour = 0;
										$nbSurveillance = 0;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['Id_Plateforme'] == $row['Id']){
													$nbSurveillance++;
													$nbJour += getJours($rowContenu['Date_Debut'],$rowContenu['Date_Fin']);
												}
											}
										}
										if ($nbSurveillance == 0){
											$arrayValeur[] = 0;
										}
										else{
											$arrayValeur[] = round($nbJour/$nbSurveillance,1);
										}
										
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode($row['Libelle']),"Champs1" => $arrayValeur[$i]);
										$i++;
									}
								}
								*/
							}
							$ParTitre="PLATEFORME";
						 
						 }
						 elseif($_POST['abscisse']== "Prestation"){
							$ParTitre="PRESTATION";
							$orientationAbscisse = 270;
							$array = array();
							$req = "SELECT new_competences_prestation.Id, CONCAT(new_competences_prestation.Libelle,' ',IF(Active=0,'[Actif]','[Inactif]')) AS Libelle ";
							$req .= "FROM new_competences_prestation ";
							$req .= "WHERE ";
							foreach($_POST['Prestation'] as $chkbx){
								$req .= "Id=".substr($chkbx,strrpos($chkbx,"_")+1)." OR ";
							}
							$req = substr($req,0,-3);
							$req .= "ORDER BY Active DESC, new_competences_prestation.Libelle;";
							$resultPrestation=mysqli_query($bdd,$req);
							$nbPrestation=mysqli_num_rows($resultPrestation);
							
							if($Legende == "NbSurveillance"){
								$arrayPlanif = array();
								$arrayCloture = array();
								$requeteContenu = reqNbSurveillancePrestation($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								if ($nbPrestation > 0){
									$i=0;
									while($row=mysqli_fetch_array($resultPrestation)){
										$array[] = $row['Libelle'];
										$bTrouvePlanif = false;
										$bTrouveRePlanif = false;
										$bTrouveRealise = false;
										$bTrouveCloture = false;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['Id_Prestation'] == $row['Id']){
													if($rowContenu['Etat'] == "Planifié" || $rowContenu['Etat'] == "Replanifié"){
														$arrayPlanif[] = $rowContenu['Nb'];
														$bTrouvePlanif = true;
													}
													elseif($rowContenu['Etat'] == "Clôturé" || $rowContenu['Etat'] == "Réalisé"){
														$arrayCloture[] = $rowContenu['Nb'];
														$bTrouveCloture = true;
													}
												}
											}
										}
										if ($bTrouvePlanif == false){
											$arrayPlanif[] = 0;
										}
										if ($bTrouveCloture == false){
											$arrayCloture[] = 0;
										}
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode(substr($row['Libelle'],0,7)),"Champs1" => $arrayPlanif[$i],"Champs2" => $arrayCloture[$i]);
										$i++;
									}
								}

								$arrayLegende=array(utf8_encode("Surveillances planifiées"),utf8_encode("Surveillances côturées"));
								foreach($_POST['EtatSurveillance'] as $chkbx){
									if($chkbx == "Planifie"){
										$checkChamps1=1;
									}
									elseif($chkbx == "Cloturee"){
										$checkChamps2=1;
									}
								}
							}
							elseif($Legende == "MoyenneNotes"){
								$arrayValeur = array();
								$requeteContenu = reqMoyenneNotePrestation($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Note"));
								if ($nbPrestation > 0){
									while($row=mysqli_fetch_array($resultPrestation)){
										$array[] = $row['Libelle'];
										$bTrouveLegende = false;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['Id_Prestation'] == $row['Id']){
													$arrayValeur[] = $rowContenu['Note'];
													$bTrouveLegende = true;
												}
											}
										}
										if ($bTrouveLegende == false){
											$arrayValeur[] = 0;
										}
										
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode(substr($row['Libelle'],0,7)),"Champs1" => $arrayValeur[$i]);
										$i++;
									}
								}
							}
							elseif($Legende == "DeltaPlaRea"){
								$arrayValeur = array();
								$requeteContenu = reqDELTADatePlaReaPrestation($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Delta"));
								if ($nbPrestation > 0){
									while($row=mysqli_fetch_array($resultPrestation)){
										$array[] = $row['Libelle'];
										$nbJour = 0;
										$nbSurveillance = 0;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['Id_Prestation'] == $row['Id']){
													$nbSurveillance++;
													$nbJour += getJours($rowContenu['Date_Debut'],$rowContenu['Date_Fin']);
												}
											}
										}
										if ($nbSurveillance == 0){
											$arrayValeur[] = 0;
										}
										else{
											$arrayValeur[] = round($nbJour/$nbSurveillance,1);
										}
										
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode(substr($row['Libelle'],0,7)),"Champs1" => $arrayValeur[$i]);
										$i++;
									}
								}
							}
							elseif($Legende == "DeltaReaClo"){
								$arrayValeur = array();
								$requeteContenu = reqDELTADateReaCloPrestation($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Delta"));
								if ($nbPrestation > 0){
									while($row=mysqli_fetch_array($resultPrestation)){
										$array[] = $row['Libelle'];
										$nbJour = 0;
										$nbSurveillance = 0;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['ID_Prestation'] == $row['Id']){
													$nbSurveillance++;
													$nbJour += getJours($rowContenu['Date_Debut'],$rowContenu['Date_Fin']);
												}
											}
										}
										if ($nbSurveillance == 0){
											$arrayValeur[] = 0;
										}
										else{
											$arrayValeur[] = round($nbJour/$nbSurveillance,1);
										}
										
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode(substr($row['Libelle'],0,7)),"Champs1" => $arrayValeur[$i]);
										$i++;
									}
								}
							}
						 }
						 elseif($_POST['abscisse']== "Thematique"){
							$ParTitre="THEMATIQUE";
							$orientationAbscisse = 270;
							$array = array();
							$TypeT = 0;
							$IsGene = 0;
							if (isset($_POST['Generique'])){$TypeT++;$IsGene = 1;}
							if (isset($_POST['Specifique'])){$TypeT++;}
								
								$req = "SELECT new_surveillances_questionnaire.ID, ";
								$req .= "CONCAT(new_surveillances_theme.Nom, ' - ',CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]'))) AS Nom ";
								$req .= "FROM new_surveillances_questionnaire LEFT JOIN new_surveillances_theme ON new_surveillances_questionnaire.ID_Theme = new_surveillances_theme.ID ";
								$req .= "WHERE ";
								$Generique = "";
								if (isset($_POST['Generique'])){
									foreach($_POST['Generique'] as $chkbx){
										$req .= "new_surveillances_questionnaire.ID=".substr($chkbx,strrpos($chkbx,"_")+1)." OR ";
									}
									$Generique = $_POST['Generique'];
								}
								if (isset($_POST['Specifique'])){
									foreach($_POST['Specifique'] as $chkbx){
										$req .= "new_surveillances_questionnaire.ID=".substr($chkbx,strrpos($chkbx,"_")+1)." OR ";
									}
								}
								$req = substr($req,0,-3);
								$req .= "ORDER BY Nom;";
							//}
							$resultTheme=mysqli_query($bdd,$req);
							$nbTheme=mysqli_num_rows($resultTheme);
							if($Legende == "NbSurveillance"){
								$arrayPlanif = array();
								$arrayCloture = array();

								$requeteContenu = reqNbSurveillanceThematique($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								if ($nbTheme > 0){
									$i=0;
									while($row=mysqli_fetch_array($resultTheme)){
										$array[] = $row['Nom'];
										$bTrouvePlanif = false;
										$bTrouveRePlanif = false;
										$bTrouveRealise = false;
										$bTrouveCloture = false;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['ID_Questionnaire'] == $row['ID'] && ($rowContenu['Etat'] == "Planifié" || $rowContenu['Etat'] == "Replanifié")){
													$arrayPlanif[] = $rowContenu['Nb'];
													$bTrouvePlanif = true;
												}
												elseif($rowContenu['ID_Questionnaire'] == $row['ID'] && ($rowContenu['Etat'] == "Clôturé" || $rowContenu['Etat'] == "Réalisé")){
													$arrayCloture[] = $rowContenu['Nb'];
													$bTrouveCloture = true;
												}
											}
										}
										if ($bTrouvePlanif == false){
											$arrayPlanif[] = 0;
										}
										if ($bTrouveCloture == false){
											$arrayCloture[] = 0;
										}
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode($row['Nom']),"Champs1" => $arrayPlanif[$i],"Champs2" => $arrayCloture[$i]);
										$i++;
									}
								}

								$arrayLegende=array(utf8_encode("Surveillances planifiées"),utf8_encode("Surveillances côturées"));
								foreach($_POST['EtatSurveillance'] as $chkbx){
									if($chkbx == "Planifie"){
										$checkChamps1=1;
									}
									elseif($chkbx == "Cloturee"){
										$checkChamps2=1;
									}
								}
							}
							elseif($Legende == "MoyenneNotes"){
								$arrayValeur = array();
								$requeteContenu = reqMoyenneNoteThematique($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Note"));
								if ($nbTheme > 0){
									while($row=mysqli_fetch_array($resultTheme)){
										$array[] = $row['Nom'];
										$bTrouveLegende = false;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['ID_Questionnaire'] == $row['ID']){
													$arrayValeur[] = $rowContenu['Note'];
													$bTrouveLegende = true;
												}
											}
										}
										if ($bTrouveLegende == false){
											$arrayValeur[] = 0;
										}
										
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode($row['Nom']),"Champs1" => $arrayValeur[$i]);
										$i++;
									}
								}
							}
							elseif($Legende == "DeltaPlaRea"){
								$arrayValeur = array();
								$requeteContenu = reqDELTADatePlaReaTheme($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Delta"));
								if ($nbTheme > 0){
									while($row=mysqli_fetch_array($resultTheme)){
										$array[] = $row['Nom'];
										$nbJour = 0;
										$nbSurveillance = 0;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['ID_Questionnaire'] == $row['ID']){
													$nbSurveillance++;
													$nbJour += getJours($rowContenu['Date_Debut'],$rowContenu['Date_Fin']);
												}
											}
										}
										if ($nbSurveillance == 0){
											$arrayValeur[] = 0;
										}
										else{
											$arrayValeur[] = round($nbJour/$nbSurveillance,1);
										}
										
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode($row['Nom']),"Champs1" => $arrayValeur[$i]);
										$i++;
									}
								}
							}
							elseif($Legende == "DeltaReaClo"){
								$arrayValeur = array();
								$requeteContenu = reqDELTADateReaCloTheme($_POST['Prestation'],$_POST['annee'],$Generique,$Specifique);
								$resultContenu=mysqli_query($bdd,$requeteContenu);
								$nbContenu=mysqli_num_rows($resultContenu);
								
								$checkChamps1=1;
								$i=0;
								$arrayLegende=array(utf8_encode("Delta"));
								if ($nbTheme > 0){
									while($row=mysqli_fetch_array($resultTheme)){
										$array[] = $row['Nom'];
										$nbJour = 0;
										$nbSurveillance = 0;
										if ($nbContenu > 0){
											mysqli_data_seek($resultContenu,0);
											while($rowContenu=mysqli_fetch_array($resultContenu)){
												if($rowContenu['ID_Questionnaire'] == $row['ID']){
													$nbSurveillance++;
													$nbJour += getJours($rowContenu['Date_Debut'],$rowContenu['Date_Fin']);
												}
											}
										}
										if ($nbSurveillance == 0){
											$arrayValeur[] = 0;
										}
										else{
											$arrayValeur[] = round($nbJour/$nbSurveillance,1);
										}
										
										$arrayGraph2[$i]=array("Abscisse" => utf8_encode($row['Nom']),"Champs1" => $arrayValeur[$i]);
										$i++;
									}
								}
							}
						 }

						$leTitre = "";
						foreach($_POST['legende'] as $chkbx){
							if ($chkbx == "NbSurveillance" ){
								$leTitre = "NOMBRE DE SURVEILLANCE";
							}
							elseif ($chkbx == "MoyenneNotes" ){
								$leTitre = "MOYENNE DES NOTES";
							}
							elseif ($chkbx == "DeltaPlaRea" ){
								$leTitre = "DELTA DATE PLANIFIEE-REALISEE";
							}
							elseif ($chkbx == "DeltaReaClo" ){
								$leTitre = "DELTA DATE REALISEE-CLOTUREE";
							}
							elseif ($chkbx == "NumNC" ){
								$leTitre = ">N° DES QUESTIONS NC";
							}
							elseif ($chkbx == "NumNA" ){
								$leTitre = ">N° DES QUESTIONS NA";
							}
							elseif ($chkbx == "Avancement" ){
								$leTitre = "% AVANCEMENT DES SURVEILLANCES";
							}
						}

						 ?>
						 <tr>
							<td height="650px;" align="center">
								<table class="TableCompetences" width="70%" height="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td class="Libelle" height="5%" style="font-size:15px;"><?php echo $leTitre."/".$ParTitre; ?></td>
										<td style="cursor:pointer;" align="right"></td>
									</tr>
									<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
									<tr><td height="4"></td></tr>
									 <tr>
										<td valign="top">
											<div id="chart_Graph2" style="width:100%;height:650px"></div>
											<script>
												// Create chart instance
												var chart = am4core.create("chart_Graph2", am4charts.XYChart);

											// Add data
											chart.data = <?php echo json_encode($arrayGraph2); ?>;
											
											// Create axes
											var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
											categoryAxis.dataFields.category = "Abscisse";
											categoryAxis.renderer.grid.template.location = 0;
											categoryAxis.renderer.minGridDistance = 10;
											categoryAxis.renderer.labels.template.horizontalCenter = "right";
											categoryAxis.renderer.labels.template.verticalCenter = "middle";
											categoryAxis.renderer.labels.template.rotation = 270;
											categoryAxis.tooltip.disabled = true;
											categoryAxis.renderer.minHeight = 0;
										
											var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
											valueAxis.renderer.minWidth = 0;
											
											<?php if($checkChamps1==1){?>
											// Create series
											var series1 = chart.series.push(new am4charts.ColumnSeries());
											series1.columns.template.width = am4core.percent(80);
											<?php if($Legende == "MoyenneNotes"){ ?>
											series1.tooltipText = "{categoryX} \n {name}: {valueY.value} %";
											<?php } else{?>
											series1.tooltipText = "{categoryX} \n {name}: {valueY.value}";	
											<?php } ?>
											series1.dataFields.categoryX = "Abscisse";
											series1.dataFields.valueY = "Champs1";
											series1.name = <?php echo json_encode($arrayLegende[0]); ?>;
											series1.stacked = true;
											<?php if($checkChamps2==1){?>
											series1.stroke  = "#eee936";
											series1.fill  = "#eee936";
											<?php }else{?>
											series1.stroke  = "#2e47e0";
											series1.fill  = "#2e47e0";
											<?php }?>
											
											var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "{valueY}";
											<?php if($Legende == "MoyenneNotes"){ ?>
											bullet1.label.text = "{valueY} %";
											<?php } else{?>
											bullet1.label.text = "{valueY}";
											<?php } ?>
											bullet1.locationY = 0.5;
											bullet1.label.fill = am4core.color("#ffffff");
											bullet1.interactionsEnabled = false;
											<?php } ?>
											
											<?php if($checkChamps2==1){?>
											// Create series
											var series2 = chart.series.push(new am4charts.ColumnSeries());
											series2.columns.template.width = am4core.percent(80);
											series2.tooltipText = "{categoryX} \n {name}: {valueY.value}";
											series2.dataFields.categoryX = "Abscisse";
											series2.dataFields.valueY = "Champs2";
											series2.name = <?php echo json_encode($arrayLegende[1]); ?>;
											series2.stacked = true;
											series2.stroke  = "#ff2525";
											series2.fill  = "#ff2525";
											
											var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
											bullet2.label.text = "{valueY}";
											bullet2.locationY = 0.5;
											bullet2.label.fill = am4core.color("#ffffff");
											bullet2.interactionsEnabled = false;
											<?php } ?>
											
											<?php if($checkChamps3==1){?>
											// Create series
											var series3 = chart.series.push(new am4charts.ColumnSeries());
											series3.columns.template.width = am4core.percent(80);
											series3.tooltipText = "{categoryX} \n {name}: {valueY.value}";
											series3.dataFields.categoryX = "Abscisse";
											series3.dataFields.valueY = "Champs3";
											series3.name = <?php echo json_encode($arrayLegende[2]); ?>;
											series3.stacked = true;
											series3.stroke  = "#24c92b";
											series3.fill  = "#24c92b";
											
											var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
											bullet3.label.text = "{valueY}";
											bullet3.locationY = 0.5;
											bullet3.label.fill = am4core.color("#ffffff");
											bullet3.interactionsEnabled = false;
											<?php } ?>
											
											<?php if($checkChamps4==1){?>
											// Create series
											var series4 = chart.series.push(new am4charts.ColumnSeries());
											series4.columns.template.width = am4core.percent(80);
											series4.tooltipText = "{categoryX} \n {name}: {valueY.value}";
											series4.dataFields.categoryX = "Abscisse";
											series4.dataFields.valueY = "Champs4";
											series4.name = <?php echo json_encode($arrayLegende[3]); ?>;
											series4.stacked = true;
											series4.stroke  = "#af2ee0";
											series4.fill  = "#af2ee0";
											
											var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
											bullet4.label.text = "{valueY}";
											bullet4.locationY = 0.5;
											bullet4.label.fill = am4core.color("#ffffff");
											bullet4.interactionsEnabled = false;
											<?php } ?>

											// Cursor
											chart.cursor = new am4charts.XYCursor();
											chart.cursor.behavior = "panX";
											chart.cursor.lineX.opacity = 0;
											chart.cursor.lineY.opacity = 0;
											
											
											chart.scrollbarX = new am4core.Scrollbar();


											chart.exporting.menu = new am4core.ExportMenu();
											
											chart.exporting.menu.items =
											[
											  {
												"label": "...",
												"menu": [
												  {
													"label": "Image",
													"menu": [
													  { "type": "png", "label": "PNG" },
													  { "type": "jpg", "label": "JPG" },
													  { "type": "svg", "label": "SVG" }
													]
												  }, {
													"label": "Data",
													"menu": [
													  { "type": "csv", "label": "CSV" },
													  { "type": "xlsx", "label": "XLSX" },
													  { "type": "html", "label": "HTML" }
													]
												  }, {
													"label": "Print", "type": "print"
												  }
												]
											  }
											];
											
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
		}
	?>
	<tr><td colspan=3>
		<table width="100%" cellpadding="0" cellspacing="0" align="center">
			<tr><td height="4"></td></tr>
				<tr>
					<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Rechercher";}else{echo "Search";}?>"></td>
				</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="25%" valign="top">
					<table class="GeneralInfo">
						<tr><td colspan="6"><b>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Unités d'exploitations & Prestations";}else{echo "Operating units & Activities";}?> : </b></td></tr>
						<?php
							$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle ";
							$req .= "FROM new_competences_plateforme ";
							$req .= "WHERE Id<> 11 AND Id<>14 ";
							$req .= "ORDER BY new_competences_plateforme.Libelle;";
							$resultPlateforme=mysqli_query($bdd,$req);
							$nbPlateforme=mysqli_num_rows($resultPlateforme);
							
							$reqPres = "SELECT new_competences_prestation.Id, CONCAT(new_competences_prestation.Libelle,' ',IF(Active=0,'[Actif]','[Inactif]')) AS Libelle, new_competences_prestation.Id_Plateforme ";
							$reqPres .= "FROM new_competences_prestation ";
							$reqPres .= "ORDER BY Active DESC, new_competences_prestation.Libelle;";
							$resultPresta=mysqli_query($bdd,$reqPres);
							$nbPresta=mysqli_num_rows($resultPresta);
							if ($nbPlateforme > 0){
								while($row=mysqli_fetch_array($resultPlateforme)){
									echo "<tr><td><input onchange='CochePrestations(".$row['Id'].")' type='checkbox' name='Plateforme[]' value='Pla_".$row['Id']."' ";
									if (isset($_POST['Plateforme'])){
										foreach($_POST['Plateforme'] as $chkbx){
											if ($chkbx == "Pla_".$row['Id']){
												echo "checked";
											}
										}
									}
									echo ">".$row['Libelle']."";
									echo " <img id='Image_PlusMoins_".$row['Id']."' src='../../Images/Plus.gif' onclick='javascript:AffichePrestations(".$row['Id'].");'>";
									echo "</td></tr>". "\n";
										if ($nbPresta > 0){
											mysqli_data_seek($resultPresta,0);
											while($rowPresta=mysqli_fetch_array($resultPresta)){
												if($row['Id'] == $rowPresta['Id_Plateforme']){
													$checked = "";
													if (isset($_POST['Prestation'])){
														foreach($_POST['Prestation'] as $chkbx){
															if ($chkbx == $row['Id']."_".$rowPresta['Id']){
																$checked = "checked";
															}
														}
													}
													echo "<tr style='display:none;' value='".$row['Id']."'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													echo "<input type='checkbox' name='Prestation[]' value='".$row['Id']."_".$rowPresta['Id']."' ".$checked." >".$rowPresta['Libelle']."</td></tr>". "\n";
												}
											}
										}
								}
							}
						?>
					</table>
				</td>
				<td width="35%" height="50%" valign="top">
					<table class="GeneralInfo">
						<tr><td colspan="6"><b>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Légende";}else{echo "Caption";}?> : </b></td></tr>
						<?php
							$checkedNbSurveillance = "";
							$checkedMoyenneNotes = "";
							$checkedDeltaPlaRea = "";
							$checkedDeltaReaClo = "";
							$checkedNumNC = "";
							$checkedNumNA = "";
							$checkedAvancement = "";
							$display = "style='display:none;'";
							if (isset($_POST['legende'])){
								foreach($_POST['legende'] as $chkbx){
									if ($chkbx == "NbSurveillance" ){
										$checkedNbSurveillance =  "checked";
										$display = "";
									}
									elseif ($chkbx == "MoyenneNotes" ){
										$checkedMoyenneNotes =  "checked";
									}
									elseif ($chkbx == "DeltaPlaRea" ){
										$checkedDeltaPlaRea =  "checked";
									}
									elseif ($chkbx == "DeltaReaClo" ){
										$checkedDeltaReaClo =  "checked";
									}
									elseif ($chkbx == "NumNC" ){
										$checkedNumNC =  "checked";
									}
									elseif ($chkbx == "NumNA" ){
										$checkedNumNA =  "checked";
									}
									elseif ($chkbx == "Avancement" ){
										$checkedAvancement =  "checked";
									}
								}
							}
							?>
						<tr><td><input onchange="AfficheTypeLegende()"  type="radio" id="legende" name="legende[]" value="NbSurveillance" <?php echo $checkedNbSurveillance;?>><?php if($_SESSION["Langue"]=="FR"){echo "Nombre de surveillances";}else{echo "Number of monitorings";}?></td></tr>
							<?php
							$checkedPla = "";
							$checkedRePla = "";
							$checkedRea = "";
							$checkedClo = "";
							
							
							if (isset($_POST['EtatSurveillance'])){
								foreach($_POST['EtatSurveillance'] as $chkbx){
									if ($chkbx == "Planifie" ){
										$checkedPla =  "checked";
									}
									elseif ($chkbx == "Replanifie" ){
										$checkedRePla =  "checked";
									}
									elseif ($chkbx == "Realisee" ){
										$checkedRea =  "checked";
									}
									elseif ($chkbx == "Cloturee" ){
										$checkedClo =  "checked";
									}
								}
							}
							?>
							<tr <?php echo $display;?> value="nbSurveillance"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="EtatSurveillance[]" value="Planifie" <?php echo $checkedPla;?>><?php if($_SESSION["Langue"]=="FR"){echo "Surveillances planifiées";}else{echo "Monitorings planned";}?></td></tr>
							<tr <?php echo $display;?> value="nbSurveillance"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="EtatSurveillance[]" value="Cloturee" <?php echo $checkedClo;?>><?php if($_SESSION["Langue"]=="FR"){echo "Surveillances clôturées";}else{echo "Monitorings closed";}?></td></tr>
						<tr><td><input onchange="AfficheTypeLegende()" type="radio" id="legende" name="legende[]" value="MoyenneNotes" <?php echo $checkedMoyenneNotes;?>><?php if($_SESSION["Langue"]=="FR"){echo "Moyenne des notes";}else{echo "Average scores";}?></td></tr>
						<tr><td><input onchange="AfficheTypeLegende()" type="radio" id="legende" name="legende[]" value="DeltaPlaRea" <?php echo $checkedDeltaPlaRea;?>>&#916; <?php if($_SESSION["Langue"]=="FR"){echo "Date plannifiée-réalisée";}else{echo "Planned-realized date";}?></td></tr>
						<tr><td><input onchange="AfficheTypeLegende()" type="radio" id="legende" name="legende[]" value="DeltaReaClo" <?php echo $checkedDeltaReaClo;?>>&#916; <?php if($_SESSION["Langue"]=="FR"){echo "Date réalisée-clôturée";}else{echo "Realized-closed date";}?></td></tr>
						<tr><td><input onchange="AfficheTypeLegende()" type="radio" id="NumNC" name="legende[]" value="NumNC" <?php echo $checkedNumNC;?>><?php if($_SESSION["Langue"]=="FR"){echo "N° des questions NC";}else{echo "NC question number";}?></td></tr>
						<tr><td><input onchange="AfficheTypeLegende()" type="radio" id="NumNA" name="legende[]" value="NumNA" <?php echo $checkedNumNA;?>><?php if($_SESSION["Langue"]=="FR"){echo "N° des questions NA";}else{echo "NA question number";}?></td></tr>
					</table>
					<br/>
					<table class="GeneralInfo">
						<tr><td><b>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?> : </b></td></tr>
						<tr><td><input onchange="AfficheGeneSpec('Generique')" type="checkbox" name="typeTheme[]" id="Generique" value="Generique" 
						<?php
						$display = "";
						if (isset($_POST['typeTheme'])){
							foreach($_POST['typeTheme'] as $chkbx){
								if ($chkbx == "Generique" ){
									echo "checked";
									$display = "";
								}
							}
						}
						?>
						><?php if($_SESSION["Langue"]=="FR"){echo "Génériques";}else{echo "Generic";}?></td></tr>
						<?php
							$req = "SELECT new_surveillances_questionnaire.ID, new_surveillances_theme.Nom, CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Questionnaire ";
							$req .= "FROM new_surveillances_theme LEFT JOIN new_surveillances_questionnaire ON new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme ";
							$req .= "WHERE new_surveillances_questionnaire.ID_Plateforme=0 AND Supprime=0 ";
							$req .= "ORDER BY Actif, new_surveillances_theme.Nom;";
							$resultGenerique=mysqli_query($bdd,$req);
							$nbresultGenerique=mysqli_num_rows($resultGenerique);
							if ($nbresultGenerique > 0){
								while($row=mysqli_fetch_array($resultGenerique)){
									echo "<tr ".$display." value='Gene'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' name='Generique[]' value='Gene_".$row['ID']."' ";
									if (isset($_POST['Generique'])){
										foreach($_POST['Generique'] as $chkbx){
											if ($chkbx == "Gene_".$row['ID'] ){
												echo "checked";
											}
										}
									}
									echo "><b>".$row['Nom']."</b> ".$row['Questionnaire']."</td></tr>";
								}
							}
						?>
						<tr><td><input onchange="AfficheGeneSpec('Specifique')" type="checkbox" name="typeTheme[]" id="Specifique" value="Specifique" 
						<?php
						$display = "";
						if (isset($_POST['typeTheme'])){
							foreach($_POST['typeTheme'] as $chkbx){
								if ($chkbx == "Specifique" ){
									echo "checked";
									$display = "";
								}
							}
						}
						?>
						><?php if($_SESSION["Langue"]=="FR"){echo "Spécifiques";}else{echo "Specific";}?></td></tr>
						<?php
							$req = "SELECT new_surveillances_questionnaire.ID, new_surveillances_theme.Nom, CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Questionnaire ";
							$req .= "FROM new_surveillances_theme LEFT JOIN new_surveillances_questionnaire ON new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme ";
							$req .= "WHERE new_surveillances_questionnaire.ID_Plateforme>0 AND Supprime=0 ";
							$req .= "ORDER BY Actif, new_surveillances_theme.Nom;";
							$resultGenerique=mysqli_query($bdd,$req);
							$nbresultGenerique=mysqli_num_rows($resultGenerique);
							if ($nbresultGenerique > 0){
								while($row=mysqli_fetch_array($resultGenerique)){
									echo "<tr ".$display." value='Spec'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' name='Specifique[]' value='Spec_".$row['ID']."' ";
									if (isset($_POST['Specifique'])){
										foreach($_POST['Specifique'] as $chkbx){
											if ($chkbx == "Spec_".$row['ID'] ){
												echo "checked";
											}
										}
									}
									echo "><b>".$row['Nom']."</b> ".$row['Questionnaire']."</td></tr>";
								}
							}
						?>
					</table>
				</td>
				<td width="15%" valign="top">
					<table width="100%" class="GeneralInfo">
						<?php
							if(isset($_POST['annee'])){
								$lAnnee = $_POST['annee'];
							}
							else{
								$lAnnee = $Annee;
							}
						?>
						<tr><td><b>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Année";}else{echo "Year";}?> : <input size="5" type="text" name="annee" value="<?php echo $lAnnee;?>"></td></tr>
					</table>
					<br/>
					<table id="tablePar" class="GeneralInfo">
						<tr><td><b>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Par";}else{echo "By";}?> : </b></td></tr>
						<?php
							if (!empty($_POST['abscisse'])){
								$abscisseSelect =$_POST['abscisse'];
							}
							else{
								$abscisseSelect ="";
							}
						?>
						<tr><td><input type="radio" name="abscisse" value="Thematique" id="Thematique" <?php if($abscisseSelect=="Thematique"){echo "checked";}?>><?php if($_SESSION["Langue"]=="FR"){echo "Thématique";}else{echo "Theme";}?></td></tr>
						<tr><td><input type="radio" name="abscisse" value="Mois" <?php if($abscisseSelect=="Mois"){echo "checked";}?>><?php if($_SESSION["Langue"]=="FR"){echo "Mois";}else{echo "Month";}?></td></tr>
						<tr><td><input type="radio" name="abscisse" value="Plateforme" <?php if($abscisseSelect=="Plateforme"){echo "checked";}?>><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td></tr>
						<tr><td><input type="radio" name="abscisse" value="Prestation" <?php if($abscisseSelect=="Prestation"){echo "checked";}?>><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?></td></tr>
					</table>
				</td>
			</tr>
		</table>
	</td></tr>
</form>
</table>

<?php
	echo "<script>AfficheTypeLegende();</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>