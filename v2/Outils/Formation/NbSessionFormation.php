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
	
	$_SESSION['FiltreNbSessionFormation_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreNbSessionFormation_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreNbSessionFormation_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreNbSessionFormation_Type']=$Id_Type;
	$_SESSION['FiltreNbSessionFormation_ModeAffichage']=$_POST['ModeAffichage'];
	$_SESSION['FiltreNbSessionFormation_Formation']=$_POST['formation'];
	$_SESSION['FiltreNbSessionFormation_Categorie']=$Categorie;
	$_SESSION['FiltreNbSessionFormation_Formateur']=$Id_Formateur;
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
							$arrayLegendeNbSessionsForm=array(utf8_encode("Effective sessions"),utf8_encode("Canceled sessions"));
						}
						else{
							$arrayLegendeNbSessionsForm=array(utf8_encode("Sessions effectives"),utf8_encode("Sessions annulées"));
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
							

							$Effective=NbSessionsV2($_POST['Id_Plateforme'],$Id_Type,$Id_Formateur,$Categorie,$_POST['formation'],$PremierJour,$DernierJour,0);
							$Annulee=NbSessionsV2($_POST['Id_Plateforme'],$Id_Type,$Id_Formateur,$Categorie,$_POST['formation'],$PremierJour,$DernierJour,1);
							
							$arrayNbSessionForm[$i]=array("Abscisse" => $abscisse,"Effective" => $Effective,"Annulee" => $Annulee);
							$leParcours = date("Y-m-d", strtotime($DernierJour." +1 day"));
							$i++;
						}
						
						//PAR CATEGORIE 
						$req="
							SELECT
								(SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) AS Categorie,
								COUNT(form_session.Id) AS NbSession
							FROM
								form_session
							WHERE
								form_session.Suppr=0
								AND form_session.Id_Plateforme IN (".$_POST['Id_Plateforme'].")
								AND Annule=0
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1)>='".$dateDebut."'
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1)<='".$dateFin."' ";
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
						GROUP BY Categorie
						ORDER BY NbSession DESC ";

						$ResultCat=mysqli_query($bdd,$req);
						$NbResultCat=mysqli_num_rows($ResultCat);
						$arrayNbSessionFormCategorie=array();
						if($NbResultCat>0){
							$i=0;
							while($rowCat=mysqli_fetch_array($ResultCat))
							{
								$arrayNbSessionFormCategorie[$i]=array("Abscisse" => utf8_encode($rowCat['Categorie']),"Effective" => $rowCat['NbSession']);
								$i++;
							}
						}
						
						
						//PAR FORMATEUR
						$req="
							SELECT
								(SELECT Trigramme FROM new_rh_etatcivil WHERE Id=Id_Formateur) AS Formateur,
								COUNT(form_session.Id) AS NbSession
							FROM
								form_session
							WHERE
								form_session.Suppr=0
								AND form_session.Id_Plateforme IN (".$_POST['Id_Plateforme'].")
								AND Annule=0
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1)>='".$dateDebut."'
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1)<='".$dateFin."' ";
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
						GROUP BY Formateur
						ORDER BY NbSession DESC ";
						
						$ResultFormateur=mysqli_query($bdd,$req);
						$NbResultFormateur=mysqli_num_rows($ResultFormateur);
						$arrayNbSessionFormFormateur2=array();
						if($NbResultFormateur>0){
							$i=0;
							while($rowFormateur=mysqli_fetch_array($ResultFormateur))
							{
								$arrayNbSessionFormFormateur2[$i]=array("Abscisse" => utf8_encode($rowFormateur['Formateur']),"Effective" => $rowFormateur['NbSession']);
								$i++;
							}
						}
						
						
						
						//PAR FORMATEUR
						$req="
							SELECT
								(SELECT Trigramme FROM new_rh_etatcivil WHERE Id=Id_Formateur) AS Formateur,
								(SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) AS Categorie,
								COUNT(form_session.Id) AS NbSession
							FROM
								form_session
							WHERE
								form_session.Suppr=0
								AND form_session.Id_Plateforme IN (".$_POST['Id_Plateforme'].")
								AND Annule=0
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1)>='".$dateDebut."'
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1)<='".$dateFin."' ";
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
						GROUP BY Formateur,Categorie
						ORDER BY Formateur,NbSession DESC ";

						$Result=mysqli_query($bdd,$req);
						$NbResult=mysqli_num_rows($Result);
						$arrayNbSessionFormFormateur=array();
						if($NbResult>0){
							$i=0;
							while($row=mysqli_fetch_array($Result))
							{
								$arrayNbSessionFormFormateur[utf8_encode($row['Formateur'])][utf8_encode($row['Categorie'])]=$row['NbSession'];
								$i++;
							}
						}
						
						
						//PAR PERIODE
						$req="
							SELECT ";
								if($_POST['ModeAffichage']=="Semaine"){
									$req.="DATE_FORMAT((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1),'%Y-%U') AS Periode2,";
									$req.="DATE_FORMAT((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1),'%Y-%U') AS Periode,";
								}
								elseif($_POST['ModeAffichage']=="Mois"){
									$req.="DATE_FORMAT((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1),'%Y-%m') AS Periode2,";
									$req.="DATE_FORMAT((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1),'%Y-%b') AS Periode,";
								}
								elseif($_POST['ModeAffichage']=="Année"){
									$req.="DATE_FORMAT((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1),'%Y') AS Periode2,";
									$req.="DATE_FORMAT((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1),'%Y') AS Periode,";
								}
						$req.="(SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) AS Categorie,
								COUNT(form_session.Id) AS NbSession
							FROM
								form_session
							WHERE
								form_session.Suppr=0
								AND form_session.Id_Plateforme IN (".$_POST['Id_Plateforme'].")
								AND Annule=0
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1)>='".$dateDebut."'
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1)<='".$dateFin."' ";
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
						GROUP BY Periode2,Categorie
						ORDER BY Periode2,NbSession DESC ";

						$Result=mysqli_query($bdd,$req);
						$NbResult=mysqli_num_rows($Result);
						$arrayNbSessionFormCate=array();
						if($NbResult>0){
							$i=0;
							while($row=mysqli_fetch_array($Result))
							{
								$arrayNbSessionFormCate[utf8_encode($row['Periode'])][utf8_encode($row['Categorie'])]=$row['NbSession'];
								$i++;
							}
						}
				?>
					<tr>
						<td height="350px;">
							<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE SESSIONS DE FORMATIONS";}else{echo "NUMBER OF TRAINING SESSIONS";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_NBSessionForm" style="width:100%;height:350px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_NBSessionForm", am4charts.XYChart);

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
											series1.tooltipText = "{name}: {valueY.value}";
											series1.dataFields.categoryX = "Abscisse";
											series1.dataFields.valueY = "Effective";
											series1.name = <?php echo json_encode($arrayLegendeNbSessionsForm[0]); ?>;
											series1.stacked = false;
											series1.stroke  = "#3d7ad5";
											series1.fill  = "#3d7ad5";
											
											var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "{valueY}";
											bullet1.label.verticalCenter = "bottom";
											bullet1.label.dy = -10;
											bullet1.label.fill = am4core.color("#3d7ad5");
											bullet1.interactionsEnabled = false;

											var series2 = chart.series.push(new am4charts.ColumnSeries());
											series2.columns.template.width = am4core.percent(80);
											series2.tooltipText = "{name}: {valueY.value}";
											series2.dataFields.categoryX = "Abscisse";
											series2.dataFields.valueY = "Annulee";
											series2.name = <?php echo json_encode($arrayLegendeNbSessionsForm[1]); ?>;
											series2.stacked = false;
											series2.stroke  = "#ff6d80";
											series2.fill  = "#ff6d80";
											
											var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "{valueY}";
											bullet1.label.verticalCenter = "bottom";
											bullet1.label.dy = -10;
											bullet1.label.fill = am4core.color("#ff6d80");
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
						<td height="600px;">
							<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE SESSIONS DE FORMATIONS EFFECTIVES / CATEGORIE";}else{echo "NUMBER OF EFFECTIVE TRAINING SESSIONS / CATEGORY";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_NBSessionFormCategorie" style="width:100%;height:600px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_NBSessionFormCategorie", am4charts.XYChart);

											// Add data
											chart.data = <?php echo json_encode($arrayNbSessionFormCategorie); ?>;

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
											series1.tooltipText = "{name}: {valueY.value}";
											series1.dataFields.categoryX = "Abscisse";
											series1.dataFields.valueY = "Effective";
											series1.name = <?php echo json_encode($arrayLegendeNbSessionsForm[0]); ?>;
											series1.stacked = false;
											series1.stroke  = "#3d7ad5";
											series1.fill  = "#3d7ad5";
											
											var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "{valueY}";
											bullet1.label.fill = am4core.color("#3d7ad5");
											bullet1.label.verticalCenter = "bottom";
											bullet1.label.dy = -10;
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
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE SESSIONS DE FORMATIONS EFFECTIVES / FORMATEUR";}else{echo "NUMBER OF EFFECTIVE TRAINING SESSIONS / FORMER";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_NBSessionFormFormateur" style="width:100%;height:600px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_NBSessionFormFormateur", am4charts.XYChart);

											// Add data
											chart.data = <?php echo json_encode($arrayNbSessionFormFormateur2); ?>;

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
											series1.tooltipText = "{name}: {valueY.value}";
											series1.dataFields.categoryX = "Abscisse";
											series1.dataFields.valueY = "Effective";
											series1.name = <?php echo json_encode($arrayLegendeNbSessionsForm[0]); ?>;
											series1.stacked = false;
											series1.stroke  = "#3d7ad5";
											series1.fill  = "#3d7ad5";
											
											var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "{valueY}";
											bullet1.label.fill = am4core.color("#3d7ad5");
											bullet1.label.verticalCenter = "bottom";
											bullet1.label.dy = -10;
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
						<td height="700px;">
							<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE SESSIONS DE FORMATIONS EFFECTIVES / CATEGORIE";}else{echo "NUMBER OF EFFECTIVE TRAINING SESSIONS / CATEGORY";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chart_NBSessionFormCate" style="width:100%;height:700px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chart_NBSessionFormCate", am4charts.XYChart);
											
											chart.paddingBottom = 50;

											chart.cursor = new am4charts.XYCursor();
											chart.scrollbarX = new am4core.Scrollbar();

											// will use this to store colors of the same items
											var colors = {};

											var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
											categoryAxis.dataFields.category = "category";
											categoryAxis.renderer.minGridDistance = 30;
											categoryAxis.renderer.grid.template.location = 0;
											categoryAxis.dataItems.template.text = "{realName}";
											categoryAxis.renderer.labels.template.horizontalCenter = "right";
											categoryAxis.renderer.labels.template.verticalCenter = "middle";
											categoryAxis.renderer.labels.template.rotation = 270;
											categoryAxis.tooltip.disabled = true;
											categoryAxis.renderer.minHeight = 0;
											
											categoryAxis.adapter.add("tooltipText", function(tooltipText, target){
											  return categoryAxis.tooltipDataItem.dataContext.realName;
											})

											var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
											valueAxis.tooltip.disabled = true;
											valueAxis.min = 0;

											// single column series for all data
											var columnSeries = chart.series.push(new am4charts.ColumnSeries());
											columnSeries.columns.template.width = am4core.percent(80);
											columnSeries.tooltipText = "{provider}: {realName}, {valueY}";
											columnSeries.dataFields.categoryX = "category";
											columnSeries.dataFields.valueY = "value";

											var bullet1 = columnSeries.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "{valueY}";
											bullet1.label.verticalCenter = "bottom";
											bullet1.label.dy = -10;
											bullet1.interactionsEnabled = false;
											
											

											// fill adapter, here we save color value to colors object so that each time the item has the same name, the same color is used
											columnSeries.columns.template.adapter.add("fill", function(fill, target) {
											 var name = target.dataItem.dataContext.realName;
											 if (!colors[name]) {
											   colors[name] = chart.colors.next();
											 }
											 target.stroke = colors[name];
											 return colors[name];
											})


											var rangeTemplate = categoryAxis.axisRanges.template;
											rangeTemplate.tick.disabled = false;
											rangeTemplate.tick.location = 0;
											rangeTemplate.tick.strokeOpacity = 0.6;
											rangeTemplate.tick.length = 60;
											rangeTemplate.grid.strokeOpacity = 0.5;
											rangeTemplate.label.tooltip = new am4core.Tooltip();
											rangeTemplate.label.tooltip.dy = -30;
											rangeTemplate.label.cloneTooltip = false;

											///// DATA
											var chartData = [];
											var lineSeriesData = [];

											var data = <?php echo json_encode($arrayNbSessionFormCate); ?>;

											// process data ant prepare it for the chart
											for (var providerName in data) {
											 var providerData = data[providerName];

											 // add data of one provider to temp array
											 var tempArray = [];
											 var count = 0;
											 // add items
											 for (var itemName in providerData) {
											   if(itemName != "quantity"){
											   count++;
											   // we generate unique category for each column (providerName + "_" + itemName) and store realName
											   tempArray.push({ category: providerName + "_" + itemName, realName: itemName, value: providerData[itemName], provider: providerName})
											   }
											 }
											 
											 // sort temp array
											 tempArray.sort(function(a, b) {
											   if (a.value > b.value) {
											   return 1;
											   }
											   else if (a.value < b.value) {
											   return -1
											   }
											   else {
											   return 0;
											   }
											 })

											 // push to the final data
											 am4core.array.each(tempArray, function(item) {
											   chartData.push(item);
											 })

											 // create range (the additional label at the bottom)
											 var range = categoryAxis.axisRanges.create();
											 range.category = tempArray[0].category;
											 range.endCategory = tempArray[tempArray.length - 1].category;
											 range.label.text = tempArray[0].provider;
											 range.label.dy = -1;
											 range.label.rotation = 0;
											 range.label.truncate = true;
											 range.label.fontWeight = "bold";
											 range.label.tooltipText = tempArray[0].provider;

											 range.label.adapter.add("maxWidth", function(maxWidth, target){
											   var range = target.dataItem;
											   var startPosition = categoryAxis.categoryToPosition(range.category, 0);
											   var endPosition = categoryAxis.categoryToPosition(range.endCategory, 1);
											   var startX = categoryAxis.positionToCoordinate(startPosition);
											   var endX = categoryAxis.positionToCoordinate(endPosition);
											   return endX - startX;
											 })
											}

											chart.data = chartData;


											// last tick
											var range = categoryAxis.axisRanges.create();
											range.category = chart.data[chart.data.length - 1].category;
											range.label.disabled = true;
											range.tick.location = 1;
											range.grid.location = 1;
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
									<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE SESSIONS DE FORMATIONS EFFECTIVES / FORMATEUR";}else{echo "NUMBER OF EFFECTIVE TRAINING SESSIONS / FORMER";} ?></td>
									<td style="cursor:pointer;" align="right"></td>
								</tr>
								<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td valign="top">
										<div id="chartdiv" style="width:100%;height:700px"></div>
										<script>
											// Create chart instance
											var chart = am4core.create("chartdiv", am4charts.XYChart);
											
											chart.paddingBottom = 50;

											chart.cursor = new am4charts.XYCursor();
											chart.scrollbarX = new am4core.Scrollbar();

											// will use this to store colors of the same items
											var colors = {};

											var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
											categoryAxis.dataFields.category = "category";
											categoryAxis.renderer.minGridDistance = 30;
											categoryAxis.renderer.grid.template.location = 0;
											categoryAxis.dataItems.template.text = "{realName}";
											categoryAxis.renderer.labels.template.horizontalCenter = "right";
											categoryAxis.renderer.labels.template.verticalCenter = "middle";
											categoryAxis.renderer.labels.template.rotation = 270;
											categoryAxis.tooltip.disabled = true;
											categoryAxis.renderer.minHeight = 0;
											
											categoryAxis.adapter.add("tooltipText", function(tooltipText, target){
											  return categoryAxis.tooltipDataItem.dataContext.realName;
											})

											var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
											valueAxis.tooltip.disabled = true;
											valueAxis.min = 0;

											// single column series for all data
											var columnSeries = chart.series.push(new am4charts.ColumnSeries());
											columnSeries.columns.template.width = am4core.percent(80);
											columnSeries.tooltipText = "{provider}: {realName}, {valueY}";
											columnSeries.dataFields.categoryX = "category";
											columnSeries.dataFields.valueY = "value";

											var bullet1 = columnSeries.bullets.push(new am4charts.LabelBullet());
											bullet1.label.text = "{valueY}";
											bullet1.label.verticalCenter = "bottom";
											bullet1.label.dy = -10;
											bullet1.interactionsEnabled = false;
											
											

											// fill adapter, here we save color value to colors object so that each time the item has the same name, the same color is used
											columnSeries.columns.template.adapter.add("fill", function(fill, target) {
											 var name = target.dataItem.dataContext.realName;
											 if (!colors[name]) {
											   colors[name] = chart.colors.next();
											 }
											 target.stroke = colors[name];
											 return colors[name];
											})


											var rangeTemplate = categoryAxis.axisRanges.template;
											rangeTemplate.tick.disabled = false;
											rangeTemplate.tick.location = 0;
											rangeTemplate.tick.strokeOpacity = 0.6;
											rangeTemplate.tick.length = 60;
											rangeTemplate.grid.strokeOpacity = 0.5;
											rangeTemplate.label.tooltip = new am4core.Tooltip();
											rangeTemplate.label.tooltip.dy = -30;
											rangeTemplate.label.cloneTooltip = false;

											///// DATA
											var chartData = [];
											var lineSeriesData = [];

											var data = <?php echo json_encode($arrayNbSessionFormFormateur); ?>;

											// process data ant prepare it for the chart
											for (var providerName in data) {
											 var providerData = data[providerName];

											 // add data of one provider to temp array
											 var tempArray = [];
											 var count = 0;
											 // add items
											 for (var itemName in providerData) {
											   if(itemName != "quantity"){
											   count++;
											   // we generate unique category for each column (providerName + "_" + itemName) and store realName
											   tempArray.push({ category: providerName + "_" + itemName, realName: itemName, value: providerData[itemName], provider: providerName})
											   }
											 }
											 
											 // sort temp array
											 tempArray.sort(function(a, b) {
											   if (a.value > b.value) {
											   return 1;
											   }
											   else if (a.value < b.value) {
											   return -1
											   }
											   else {
											   return 0;
											   }
											 })

											 // push to the final data
											 am4core.array.each(tempArray, function(item) {
											   chartData.push(item);
											 })

											 // create range (the additional label at the bottom)
											 var range = categoryAxis.axisRanges.create();
											 range.category = tempArray[0].category;
											 range.endCategory = tempArray[tempArray.length - 1].category;
											 range.label.text = tempArray[0].provider;
											 range.label.dy = -1;
											 range.label.rotation = 0;
											 range.label.truncate = true;
											 range.label.fontWeight = "bold";
											 range.label.tooltipText = tempArray[0].provider;

											 range.label.adapter.add("maxWidth", function(maxWidth, target){
											   var range = target.dataItem;
											   var startPosition = categoryAxis.categoryToPosition(range.category, 0);
											   var endPosition = categoryAxis.categoryToPosition(range.endCategory, 1);
											   var startX = categoryAxis.positionToCoordinate(startPosition);
											   var endX = categoryAxis.positionToCoordinate(endPosition);
											   return endX - startX;
											 })
											}

											chart.data = chartData;


											// last tick
											var range = categoryAxis.axisRanges.create();
											range.category = chart.data[chart.data.length - 1].category;
											range.label.disabled = true;
											range.tick.location = 1;
											range.grid.location = 1;
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
					if($_SESSION['FiltreNbSessionFormation_ModeAffichage']=='Semaine'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Semaine' ".$selected.">Semaine</option>";}
					else{echo "<option value='Semaine' ".$selected.">Week</option>";}
					
					$selected="";
					if($_SESSION['FiltreNbSessionFormation_ModeAffichage']=='Mois'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='Mois' ".$selected.">Mois</option>";}
					else{echo "<option value='Mois' ".$selected.">Month</option>";}
					
					$selected="";
					if($_SESSION['FiltreNbSessionFormation_ModeAffichage']=='Année'){$selected="selected";}
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
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreNbSessionFormation_DateDebut']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreNbSessionFormation_DateFin']); ?>"></td>
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
						$laformation=$_SESSION['FiltreNbSessionFormation_Formation'];

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