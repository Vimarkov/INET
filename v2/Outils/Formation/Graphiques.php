<?php
require("../../Menu.php");

if($_SESSION["Langue"]=="FR")
{
	$MoisLettre = array("Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
}
else
{
	$MoisLettre = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
}

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=$leNombre;}
	return $nb;
}

if($_POST)
{
	$_SESSION['FiltreFormGraphique_Prestation']=$_POST['Prestation'];
	$_SESSION['FiltreFormGraphique_Formation']=$_POST['Formation'];
}

Ecrire_Code_JS_Init_Date(); 
?>
<form id="formulaire" action="Graphiques.php" method="post">
	<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#9bfd5f;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Graphiques";}else{echo "Graphics";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table style="width:100%; align:center; border-spacing:0;" class="GeneralInfo">
				<tr>
					<td height="4"></td>
					<td>
						<table width="100%">
							<tr>
							<?php 
								if($LangueAffichage=="FR"){
									echo "<td class=\"Libelle\">Prestation/Pôle</td>";
								}
								else{
									echo "<td class=\"Libelle\">Activity/Pole</td>";
								}
								$Prestation=$_SESSION['FiltreFormGraphique_Prestation'];
							?>
								<td><input style="width:200px" id="Prestation" name="Prestation" value="<?php echo $Prestation;?>"></td>
								<td width="20%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
									<select id="mois" name="mois" onchange="submit();">
										<option value='0' selected></option>
										<?php
											if($_SESSION["Langue"]=="FR"){
												$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
											}
											else{
												$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
											}
											$mois=$_SESSION['FiltreFormGraphique_Mois'];
											if($_POST){$mois=$_POST['mois'];}
											$_SESSION['FiltreFormGraphique_Mois']=$mois;
											
											for($i=0;$i<=11;$i++){
												$numMois=$i+1;
												if($numMois<10){$numMois="0".$numMois;}
												echo "<option value='".$numMois."'";
												if($mois== ($i+1)){echo " selected ";}
												echo ">".$arrayMois[$i]."</option>\n";
											}
											
											$annee=$_SESSION['FiltreFormGraphique_Annee'];
											if($_POST){$annee=$_POST['annee'];}
											if($annee==""){$annee=date("Y");}
											$_SESSION['FiltreFormGraphique_Annee']=$annee;
										?>
									</select>
								</td>
								<td width="8%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
									<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
								</td>
								<td class="Libelle">
								<?php 
									if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}
									$formation="";
									$formation=$_SESSION['FiltreFormGraphique_Formation'];
								?>
								</td>
								<td><input style="width:200px" id="Formation" name="Formation" value="<?php echo $formation;?>"></td>
								<td><input style='cursor:pointer;' class="Bouton" type="submit" value="Filtrer" ></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php 
		$moisEC=date($annee."-".$mois."-1");
		$date_11Mois = date("Y-m-d",strtotime($moisEC." -7 month"));

		//OQD Evaluation à chaud
		$arrayOQD=array();
		
		//Nombre de session de formations 
		$arrayNbSessionForm=array();
		if($_SESSION['Langue']=="EN"){
			$arrayLegendeNbSessionsForm=array(utf8_encode("Internal (Sessions realized)"),utf8_encode("External (Sessions realized)"),utf8_encode("Test tube (Sessions realized)"),utf8_encode("AAA TC (Sessions realized)"),utf8_encode("Internal (Canceled sessions)"),utf8_encode("External (Canceled sessions)"),utf8_encode("Test tube (Canceled sessions)"),utf8_encode("AAA TC (Canceled sessions)"));
		}
		else{
			$arrayLegendeNbSessionsForm=array(utf8_encode("Interne (Sessions réalisées)"),utf8_encode("Externe (Sessions réalisées)"),utf8_encode("Eprouvette (Sessions réalisées)"),utf8_encode("AAA TC (Sessions réalisées)"),utf8_encode("Interne (Sessions annulées)"),utf8_encode("Externe (Sessions annulées)"),utf8_encode("Eprouvette (Sessions annulées)"),utf8_encode("AAA TC (Sessions annulées)"));
		}
		
		//Nombre de personnes inscrites
		$arrayNbPersonnesInscrites=array();
		if($_SESSION['Langue']=="EN"){
			$arrayLegendeNbPersonnesInscrites=array(utf8_encode("Internal (Interim)"),utf8_encode("Interne (Salaried)"),utf8_encode("External (Interim)"),utf8_encode("External (Salaried)"));
		}
		else{
			$arrayLegendeNbPersonnesInscrites=array(utf8_encode("Interne (Interim)"),utf8_encode("Interne (Salarié)"),utf8_encode("Externe (Interim)"),utf8_encode("Externe (Salarié)"));
		}
		
		//Nombre de personnes présentes
		$arrayNbPersonnesPresentesInterne=array();
		$arrayNbPersonnesPresentesExterne=array();
		if($_SESSION['Langue']=="EN"){
			$arrayLegendeNbPersonnesPresentes=array(utf8_encode("Salaried (Present)"),utf8_encode("Salaried (Absent)"),utf8_encode("Interim (Present)"),utf8_encode("Interim (Absent)"));
		}
		else{
			$arrayLegendeNbPersonnesPresentes=array(utf8_encode("Salarié (Présent)"),utf8_encode("Salarié (Absent)"),utf8_encode("Interim (Présent)"),utf8_encode("Interim (Absent)"));
		}
		
		
		
		//POUR LE MOIS EN COURS
		
		//% par prestation des inscrits
		$ResultPrestaInscrit=mysqli_query($bdd,ReqInscriptionsParPrestation($annee,$mois,$Prestation,$formation));
		$NbPrestaInscrit=mysqli_num_rows($ResultPrestaInscrit);
		
		$arrayPrestationInscriptions=array();
		$i=0;
		if($NbPrestaInscrit>0){
			while($row=mysqli_fetch_array($ResultPrestaInscrit))
			{
				if($i<=20){
					$Pole="";
					if($row['Pole']<>""){$Pole=" - ".$row['Pole'];}
					
					$ResultAbs=mysqli_query($bdd,ReqInscriptionsAbsent($annee,$mois,$row['Id_Prestation'],$row['Id_Pole']));
					$NbAbs=mysqli_num_rows($ResultAbs);
					
					$TauxPresent=0;
					if($row['NbInscrit']>0){
						$TauxPresent=round((1-($NbAbs/$row['NbInscrit']))*100,1);
					}
					$arrayPrestationInscriptions[$i]=array("Prestation" => $row['Prestation'].$Pole,"NbInscrit" =>$row['NbInscrit'],"TauxPresent" =>$TauxPresent);
					$i++;
				}
			}
		}
		
		//% par prestation des inscrits
		$ResultPrestaAbsent=mysqli_query($bdd,ReqInscriptionsAbsentParPrestation($annee,$mois,$Prestation,$formation));
		$NbPrestaAbsent=mysqli_num_rows($ResultPrestaAbsent);
		
		$arrayPrestationInscriptionsAbsent=array();
		$i=0;
		if($NbPrestaAbsent>0){
			while($row=mysqli_fetch_array($ResultPrestaAbsent))
			{
				$Pole="";
				if($row['Pole']<>""){$Pole=" - ".$row['Pole'];}

				$arrayPrestationInscriptionsAbsent[$i]=array("Prestation" => $row['Prestation'].$Pole,"NbAbsent" =>$row['NbAbsent']);
				$i++;
			}
		}
		
		$i=0;
		$laDate=$date_11Mois;
		
		//PAR MOIS
		for($nbMois=1;$nbMois<=8;$nbMois++){
			$anneeEC=date("Y",strtotime($laDate." +0 month"));
			$moisEC=date("m",strtotime($laDate." +0 month"));
			
			$arrayMois[$i]=$MoisLettre[$moisEC-1]."<br>".date("y",strtotime($laDate." +0 month"));
			$arrayMoisLettre[$i]=$MoisLettre2[$moisEC-1];
			
		
			$OQD=0;
			$NbEval3=0;
			
			$NbInterneRealisee=NbSessions(3, $anneeEC,$moisEC,$Prestation,$formation,0);
			$NbExterneRealisee=NbSessions(4, $anneeEC,$moisEC,$Prestation,$formation,0);
			$NbEprouvetteRealisee=NbSessions(1, $anneeEC,$moisEC,$Prestation,$formation,0);
			$NbAAAATCRealisee=NbSessions(2, $anneeEC,$moisEC,$Prestation,$formation,0);
			$NbRealisee=$NbInterneRealisee+$NbExterneRealisee+$NbEprouvetteRealisee+$NbAAAATCRealisee;
			
			$NbInterneAnnulee=NbSessions(3, $anneeEC,$moisEC,$Prestation,$formation,1);
			$NbExterneAnnulee=NbSessions(4, $anneeEC,$moisEC,$Prestation,$formation,1);
			$NbEprouvetteAnnulee=NbSessions(1, $anneeEC,$moisEC,$Prestation,$formation,1);
			$NbAAAATCAnnulee=NbSessions(2, $anneeEC,$moisEC,$Prestation,$formation,1);
			$NbAnnulee=$NbInterneAnnulee+$NbExterneAnnulee+$NbEprouvetteAnnulee+$NbAAAATCAnnulee;
			
			$NbInterneInterim=NbPersonnesInscrites("INTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Intérim");
			$NbInterneSalarie=NbPersonnesInscrites("INTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Salarié");
			$NbInterne=$NbInterneInterim+$NbInterneSalarie;
			$NbExterneInterim=NbPersonnesInscrites("EXTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Intérim");
			$NbExterneSalarie=NbPersonnesInscrites("EXTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Salarié");
			$NbExterne=$NbExterneInterim+$NbExterneSalarie;
			
			$NbInterneInterimPresent=NbPersonnesPresentes("INTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Intérim",1);
			$NbInterneInterimAbsent=NbPersonnesPresentes("INTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Intérim",0);
			$NbInterneSalariePresent=NbPersonnesPresentes("INTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Salarié",1);
			$NbInterneSalarieAbsent=NbPersonnesPresentes("INTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Salarié",0);
			
			$NbExterneInterimPresent=NbPersonnesPresentes("EXTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Intérim",1);
			$NbExterneInterimAbsent=NbPersonnesPresentes("EXTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Intérim",0);
			$NbExterneSalariePresent=NbPersonnesPresentes("EXTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Salarié",1);
			$NbExterneSalarieAbsent=NbPersonnesPresentes("EXTERNE", $anneeEC,$moisEC,$Prestation,$formation,"Salarié",0);
			
			$req="
				SELECT
					form_session_personne_document.Id
				FROM form_session_personne_document
				LEFT JOIN form_session_personne ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
				LEFT JOIN form_session ON form_session_personne.Id_Session=form_session.Id
				WHERE
					form_session_personne.Suppr=0
					AND form_session.Annule=0
					AND form_session.Suppr=0
					AND form_session_personne.Presence=1
					AND form_session_personne.Validation_Inscription=1
					AND form_session_personne_document.Suppr=0 
					AND form_session_personne_document.DateHeureRepondeur>'0001-01-01'
					AND form_session_personne_document.Id_Document=6
					AND (SELECT YEAR(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$anneeEC."'
					AND (SELECT MONTH(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$moisEC."'
					AND form_session_personne.Id_Personne IN
					(
						SELECT
							Id_Personne 
						FROM
							new_competences_personne_prestation
						LEFT JOIN
							new_competences_prestation 
						ON
							new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
						WHERE
							new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' 
							AND Id_Plateforme IN
							(
								SELECT
									Id_Plateforme 
								FROM
									new_competences_personne_poste_plateforme
								WHERE
									Id_Personne=".$IdPersonneConnectee."
									AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
							)
					) ";
			
			if($Prestation<>"")
			{
				$req.="
					AND
					( 
						(
						SELECT
						(
							SELECT
								Libelle 
							FROM
								new_competences_prestation 
							WHERE
								new_competences_prestation.Id=form_besoin.Id_Prestation
						)
						FROM
							form_besoin
						WHERE form_besoin.Id=form_session_personne.Id_Besoin
					) LIKE '%".$Prestation."%' 
						OR
						(
						SELECT
						(
							SELECT
								Libelle 
							FROM
								new_competences_pole 
							WHERE
								new_competences_pole.Id=form_besoin.Id_Pole
						)
						FROM
							form_besoin
						WHERE
							form_besoin.Id=form_session_personne.Id_Besoin
					) LIKE '%".$Prestation."%'
					)";
			}
			if($formation<>""){$req.="AND (
					SELECT
						(SELECT IF(form_besoin.Motif='Renouvellement' AND form_session.Recyclage=1,LibelleRecyclage,Libelle)
						FROM form_formation_langue_infos
						WHERE Id_Formation=form_besoin.Id_Formation
						AND Id_Langue=
							(SELECT Id_Langue 
							FROM form_formation_plateforme_parametres 
							WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
							AND Id_Formation=form_besoin.Id_Formation
							AND Suppr=0 
							LIMIT 1)
						AND Suppr=0)
					FROM
						form_besoin
					WHERE
						form_besoin.Id=form_session_personne.Id_Besoin
					
					) LIKE '%".$formation."%' ";}

			$ResultSessions=mysqli_query($bdd,$req);
			$NbSessions=mysqli_num_rows($ResultSessions);
			
			
			if($NbSessions>0){
				while($row=mysqli_fetch_array($ResultSessions))
				{
					$req="
					SELECT form_session_personne_document_question_reponse.Valeur_Reponse
					FROM form_session_personne_document_question_reponse
					LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
					WHERE form_session_personne_document_question_reponse.Suppr=0
					AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
					AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=".$row['Id']." 
					AND form_session_personne_document_question_reponse.Valeur_Reponse<=3
					";
					$ResultNote2=mysqli_query($bdd,$req);
					$NbNote2=mysqli_num_rows($ResultNote2);
					if($NbNote2>0){$NbEval3++;}
				}
			}
			
			if($NbSessions>0){
				$OQD=1-($NbEval3/$NbSessions);
			}
			$OQD=round($OQD*100,1);
			$arrayOQD[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"OQD" => $OQD,"NbEval" => $NbSessions,"NbEval3" => $NbEval3);
			
			$arrayNbSessionForm[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"InterneRealisee" => $NbInterneRealisee,"ExterneRealisee" => $NbExterneRealisee,"EprouvetteRealisee" => $NbEprouvetteRealisee,"AAAATCRealisee" => $NbAAAATCRealisee,"InterneAnnulee" => $NbInterneAnnulee,"ExterneAnnulee" => $NbExterneAnnulee,"EprouvetteAnnulee" => $NbEprouvetteAnnulee,"AAAATCAnnulee" => $NbAAAATCAnnulee,"NbRealisee" => $NbRealisee,"NbAnnulee" => $NbAnnulee);
			
			$arrayNbPersonnesInscrites[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbInterneInterim" => $NbInterneInterim,"NbInterneSalarie" => $NbInterneSalarie,"NbExterneInterim" => $NbExterneInterim,"NbExterneSalarie" => $NbExterneSalarie,"NbInterne" => $NbInterne,"NbExterne" => $NbExterne);
			
			$arrayNbPersonnesPresentesInterne[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbSalariePresent" => $NbInterneSalariePresent,"NbSalarieAbsent" => $NbInterneSalarieAbsent,"NbInterimPresent" => $NbInterneInterimPresent,"NbInterimAbsent" => $NbInterneInterimAbsent);
			$arrayNbPersonnesPresentesExterne[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbSalariePresent" => $NbExterneSalariePresent,"NbSalarieAbsent" => $NbExterneSalarieAbsent,"NbInterimPresent" => $NbExterneInterimPresent,"NbInterimAbsent" => $NbExterneInterimAbsent);
			$i++;
			$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
		}
		
		$laDate=date($annee."-".$mois."-1");
	?>
	<tr><td height="5"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" height="350px;">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE SESSIONS DE FORMATIONS";}else{echo "NUMBER OF TRAINING SESSIONS";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<div id="chart_NBSessionForm" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_NBSessionForm", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayNbSessionForm); ?>;

										// Create axes
										var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
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
										series1.dataFields.categoryX = "Mois";
										series1.dataFields.valueY = "InterneRealisee";
										series1.name = <?php echo json_encode($arrayLegendeNbSessionsForm[0]); ?>;
										series1.stacked = false;
										series1.stroke  = "#3d7ad5";
										series1.fill  = "#3d7ad5";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										// Create series
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "ExterneRealisee";
										series2.name = <?php echo json_encode($arrayLegendeNbSessionsForm[1]); ?>;
										series2.stacked = true;
										series2.stroke  = "#29dae9";
										series2.fill  = "#29dae9";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										// Create series
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "EprouvetteRealisee";
										series2.name = <?php echo json_encode($arrayLegendeNbSessionsForm[2]); ?>;
										series2.stacked = true;
										series2.stroke  = "#77be40";
										series2.fill  = "#77be40";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										// Create series
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "AAAATCRealisee";
										series2.name = <?php echo json_encode($arrayLegendeNbSessionsForm[3]); ?>;
										series2.stacked = true;
										series2.stroke  = "#9695d7";
										series2.fill  = "#9695d7";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "InterneAnnulee";
										series2.name = <?php echo json_encode($arrayLegendeNbSessionsForm[4]); ?>;
										series2.stacked = false;
										series2.stroke  = "#f8ff6d";
										series2.fill  = "#f8ff6d";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value} \n";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "ExterneAnnulee";
										series2.name = <?php echo json_encode($arrayLegendeNbSessionsForm[5]); ?>;
										series2.stacked = true;
										series2.stroke  = "#f3b479";
										series2.fill  = "#f3b479";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value} \n";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "EprouvetteAnnulee";
										series2.name = <?php echo json_encode($arrayLegendeNbSessionsForm[6]); ?>;
										series2.stacked = true;
										series2.stroke  = "#ef857d";
										series2.fill  = "#ef857d";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value} \n";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "AAAATCAnnulee";
										series2.name = <?php echo json_encode($arrayLegendeNbSessionsForm[7]); ?>;
										series2.stacked = true;
										series2.stroke  = "#d59797";
										series2.fill  = "#d59797";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										var series3 = chart.series.push(new am4charts.LineSeries());
										series3.dataFields.categoryX = "Mois";
										series3.dataFields.valueY = "NbRealisee";
										series3.yAxis = valueAxis;
										series3.stroke  = "#9bbb59";
										series3.fill  = "#9bbb59";
										series3.strokeWidth = 0;
										series3.minBulletDistance = 0;
										
										var bullet = series3.bullets.push(new am4charts.LabelBullet());
										bullet.label.text = "[bold]{valueY}";
										bullet.label.dy = -10;
										bullet.label.dx = -25;
										
										var series3 = chart.series.push(new am4charts.LineSeries());
										series3.dataFields.categoryX = "Mois";
										series3.dataFields.valueY = "NbAnnulee";
										series3.yAxis = valueAxis;
										series3.stroke  = "#9bbb59";
										series3.fill  = "#9bbb59";
										series3.strokeWidth = 0;
										series3.minBulletDistance = 0;
										
										var bullet = series3.bullets.push(new am4charts.LabelBullet());
										bullet.label.text = "[bold]{valueY}";
										bullet.label.dy = -10;
										bullet.label.dx = 25;
										
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
					<td width="50%" height="350px;">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE PERSONNES INSCRITES EN FORMATION";}else{echo "NUMBER OF PERSONS ENTERED IN TRAINING";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<div id="chart_NBPersonnesInscrites" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_NBPersonnesInscrites", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayNbPersonnesInscrites); ?>;

										// Create axes
										var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
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
										series1.dataFields.categoryX = "Mois";
										series1.dataFields.valueY = "NbInterneInterim";
										series1.name = <?php echo json_encode($arrayLegendeNbPersonnesInscrites[0]); ?>;
										series1.stacked = false;
										series1.stroke  = "#3d7ad5";
										series1.fill  = "#3d7ad5";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										// Create series
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbInterneSalarie";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesInscrites[1]); ?>;
										series2.stacked = true;
										series2.stroke  = "#29dae9";
										series2.fill  = "#29dae9";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;

										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbExterneInterim";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesInscrites[2]); ?>;
										series2.stacked = false;
										series2.stroke  = "#f8ff6d";
										series2.fill  = "#f8ff6d";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value} \n";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbExterneSalarie";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesInscrites[3]); ?>;
										series2.stacked = true;
										series2.stroke  = "#f3b479";
										series2.fill  = "#f3b479";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										var series3 = chart.series.push(new am4charts.LineSeries());
										series3.dataFields.categoryX = "Mois";
										series3.dataFields.valueY = "NbInterne";
										series3.yAxis = valueAxis;
										series3.stroke  = "#9bbb59";
										series3.fill  = "#9bbb59";
										series3.strokeWidth = 0;
										series3.minBulletDistance = 0;
										
										var bullet = series3.bullets.push(new am4charts.LabelBullet());
										bullet.label.text = "[bold]{valueY}";
										bullet.label.dy = -10;
										bullet.label.dx = -25;
										
										var series3 = chart.series.push(new am4charts.LineSeries());
										series3.dataFields.categoryX = "Mois";
										series3.dataFields.valueY = "NbExterne";
										series3.yAxis = valueAxis;
										series3.stroke  = "#9bbb59";
										series3.fill  = "#9bbb59";
										series3.strokeWidth = 0;
										series3.minBulletDistance = 0;
										
										var bullet = series3.bullets.push(new am4charts.LabelBullet());
										bullet.label.text = "[bold]{valueY}";
										bullet.label.dy = -10;
										bullet.label.dx = 25;
										
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
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" height="350px;">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE PERSONNES PRÉSENTES EN FORMATION INTERNE";}else{echo "NUMBER OF PEOPLE PRESENT IN INTERNAL TRAINING";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<div id="chart_NBPersonnesPresentesInterne" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_NBPersonnesPresentesInterne", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayNbPersonnesPresentesInterne); ?>;

										// Create axes
										var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
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
										series1.dataFields.categoryX = "Mois";
										series1.dataFields.valueY = "NbSalariePresent";
										series1.name = <?php echo json_encode($arrayLegendeNbPersonnesPresentes[0]); ?>;
										series1.stacked = false;
										series1.stroke  = "#7cfc70";
										series1.fill  = "#7cfc70";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										// Create series
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbSalarieAbsent";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesPresentes[1]); ?>;
										series2.stacked = true;
										series2.stroke  = "#f67676";
										series2.fill  = "#f67676";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;

										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbInterimPresent";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesPresentes[2]); ?>;
										series2.stacked = false;
										series2.stroke  = "#15c803";
										series2.fill  = "#15c803";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value} \n";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbInterimAbsent";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesPresentes[3]); ?>;
										series2.stacked = true;
										series2.stroke  = "#eb0e0e";
										series2.fill  = "#eb0e0e";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
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
					<td width="50%" height="350px;">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE PERSONNES PRÉSENTES EN FORMATION EXTERNE";}else{echo "NUMBER OF PEOPLE PRESENT IN EXTERNAL TRAINING";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<div id="chart_NBPersonnesPresentesExterne" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_NBPersonnesPresentesExterne", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayNbPersonnesPresentesExterne); ?>;

										// Create axes
										var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
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
										series1.dataFields.categoryX = "Mois";
										series1.dataFields.valueY = "NbSalariePresent";
										series1.name = <?php echo json_encode($arrayLegendeNbPersonnesPresentes[0]); ?>;
										series1.stacked = false;
										series1.stroke  = "#7cfc70";
										series1.fill  = "#7cfc70";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										// Create series
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbSalarieAbsent";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesPresentes[1]); ?>;
										series2.stacked = true;
										series2.stroke  = "#f67676";
										series2.fill  = "#f67676";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;

										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbInterimPresent";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesPresentes[2]); ?>;
										series2.stacked = false;
										series2.stroke  = "#15c803";
										series2.fill  = "#15c803";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{name}: {valueY.value} \n";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbInterimAbsent";
										series2.name = <?php echo json_encode($arrayLegendeNbPersonnesPresentes[3]); ?>;
										series2.stacked = true;
										series2.stroke  = "#eb0e0e";
										series2.fill  = "#eb0e0e";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
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
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" height="350px;">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "INSCRIPTIONS DU MOIS / PRESTATIONS";}else{echo "REGISTRATION OF THE MONTH / SITE";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<div id="chart_NBInscriptionsPrestations" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_NBInscriptionsPrestations", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayPrestationInscriptions); ?>;

										// Create axes
										var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Prestation";
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
										series1.dataFields.categoryX = "Prestation";
										series1.dataFields.valueY = "NbInscrit";
										series1.name = <?php if($_SESSION['Langue']=="FR"){echo json_encode("Nb inscrits");}else{echo json_encode("Registered Nb");} ?>;
										series1.stacked = false;
										series1.stroke  = "#1f62d3";
										series1.fill  = "#1f62d3";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
										valueAxis.tooltip.disabled = true;
										valueAxis.renderer.axisFills.template.disabled = true;
										valueAxis.renderer.ticks.template.disabled = true;
										valueAxis.renderer.minWidth = 0;
										valueAxis.renderer.opposite = true;
										valueAxis.min = 10;
										valueAxis.max = 100; 
										
										var series3 = chart.series.push(new am4charts.LineSeries());
										series3.dataFields.categoryX = "Prestation";
										series3.dataFields.valueY = "TauxPresent";
										series3.tooltipText = "[{categoryX}: bold]{valueY.value}[/]%";
										series3.name = <?php if($_SESSION['Langue']=="FR"){echo json_encode("Taux presence");}else{echo json_encode("Rates present");} ?>;
										series3.yAxis = valueAxis;
										series3.stroke  = "#15c802";
										series3.fill  = "#15c802";
										series3.strokeWidth = 1;
										series3.minBulletDistance = 10;	
										
										var bullet = series3.bullets.push(new am4charts.LabelBullet());
										bullet.label.text = "[bold] {valueY}[/]%";
										bullet.label.dy = -13;
										bullet.label.dx = -2;
										
										var bullet = series3.bullets.push(new am4charts.CircleBullet());
										bullet.circle.radius = 6;
										bullet.circle.fill = am4core.color("#fff");
										bullet.circle.strokeWidth = 3;
	
										
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
					<td width="50%" height="350px;">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "ABSENTS DU MOIS / PRESTATIONS";}else{echo "ABSENTS OF THE MONTH / SITE";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<div id="chart_NBAbsentsPrestations" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_NBAbsentsPrestations", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayPrestationInscriptionsAbsent); ?>;

										// Create axes
										var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Prestation";
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
										series1.dataFields.categoryX = "Prestation";
										series1.dataFields.valueY = "NbAbsent";
										series1.name = <?php if($_SESSION['Langue']=="FR"){echo json_encode("Nb absents");}else{echo json_encode("Number of absentees");} ?>;
										series1.stacked = false;
										series1.stroke  = "#e60c0c";
										series1.fill  = "#e60c0c";
										
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
										
										chart.exporting.menu = new am4core.ExportMenu();
										
										chart.legend = new am4charts.Legend();
										
									</script>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" valign="top" height="350px;">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;" colspan="2"><?php if($_SESSION['Langue']=="FR"){echo "OQD SELON ÉVALUATION À CHAUD";}else{echo "OQD ACCORDING TO HOT EVALUATION";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" colspan="4">
									<div id="chart_OQD" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_OQD", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayOQD); ?>;

										// Create axes
										var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 30;
										categoryAxis.renderer.labels.template.horizontalCenter = "right";
										categoryAxis.renderer.labels.template.verticalCenter = "middle";
										categoryAxis.renderer.labels.template.rotation = 270;
										categoryAxis.tooltip.disabled = true;
										categoryAxis.renderer.minHeight = 0;
										
										var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
										valueAxis.tooltip.disabled = true;
										valueAxis.renderer.axisFills.template.disabled = true;
										valueAxis.renderer.ticks.template.disabled = true;
										valueAxis.renderer.minWidth = 0;

										// Create series
										var series1 = chart.series.push(new am4charts.ColumnSeries());
										series1.columns.template.width = am4core.percent(80);
										series1.tooltipText = "{categoryX}: {valueY.value}";
										series1.dataFields.categoryX = "Mois";
										series1.dataFields.valueY = "NbEval";
										series1.name = <?php if($_SESSION['Langue']=="FR"){echo json_encode("Nb d'evaluations");}else{echo json_encode("Nb of evaluations");} ?>;
										series1.stroke  = "#66b6dc";
										series1.fill  = "#66b6dc";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;

										var series1 = chart.series.push(new am4charts.ColumnSeries());
										series1.columns.template.width = am4core.percent(80);
										series1.tooltipText = "{categoryX}: {valueY.value}";
										series1.dataFields.categoryX = "Mois";
										series1.dataFields.valueY = "NbEval3";
										series1.name = <?php if($_SESSION['Langue']=="FR"){echo json_encode("Nb d'evaluations avec au moins 1 note <=3");}else{echo json_encode("Nb of evaluations with at least 1 rating <= 3");} ?>;
										series1.stroke  = "#eb7d13";
										series1.fill  = "#eb7d13";
										
										var bullet2 = series1.bullets.push(new am4charts.LabelBullet());
										bullet2.label.text = "{valueY}";
										bullet2.locationY = 0.5;
										bullet2.label.fill = am4core.color("#ffffff");
										bullet2.interactionsEnabled = false;
										
										var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
										valueAxis.tooltip.disabled = true;
										valueAxis.renderer.axisFills.template.disabled = true;
										valueAxis.renderer.ticks.template.disabled = true;
										valueAxis.renderer.minWidth = 0;
										valueAxis.renderer.opposite = true;
										valueAxis.min = 0;
										valueAxis.max = 100; 
										
										var series3 = chart.series.push(new am4charts.LineSeries());
										series3.tooltipText = "[{categoryX}: bold]{valueY.value}[/]%";
										series3.dataFields.categoryX = "Mois";
										series3.dataFields.valueY = "OQD";
										series3.name = <?php if($_SESSION['Langue']=="FR"){echo json_encode("OQD");}else{echo json_encode("OQD");} ?>;
										series3.yAxis = valueAxis;
										series3.stroke  = "#9bbb59";
										series3.fill  = "#9bbb59";
										series3.strokeWidth = 2;
										series3.minBulletDistance = 10;
										
										var bullet = series3.bullets.push(new am4charts.LabelBullet());
										bullet.label.text = "[bold] {valueY}[/]%";
										bullet.label.dy = -10;
										bullet.label.dx = -2;

										
										/* Add legend */
										chart.legend = new am4charts.Legend();
										
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
					<td width="50%" height="350px;">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	</table>
</form>
</html>
	