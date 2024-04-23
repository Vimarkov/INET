<?php
session_start();
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require '../Fonctions.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
if($_SESSION['Langue']=="EN"){
	$excel = $workbook->load('INDIC_TEMPLATE_EN.xlsx');
}
else{
	$excel = $workbook->load('INDIC_TEMPLATE.xlsx');
}

$sheet = $excel->getSheetByName('Indicateurs');
$sheetDATA = $excel->getSheetByName('DATA');
$sheetKO = $excel->getSheetByName('CC_NON_CONFORME');

if($_SESSION['EXTRACT_Checklist2']<>""){
	$req="SELECT Libelle, ObjectifFTR FROM trame_prestation WHERE Id=".$_SESSION['Id_PrestationTR'];
	$resultP=mysqli_query($bdd,$req);
	$nbResultaP=mysqli_num_rows($resultP);
	$prestation="";
	$objectifFTR=0;
	if($nbResultaP>0){
	$rowP=mysqli_fetch_array($resultP);
	$prestation=$rowP['Libelle'];
	$objectifFTR=$rowP['ObjectifFTR'];
	}

	$req="SELECT Libelle FROM trame_checklist WHERE Id=".$_SESSION['EXTRACT_Checklist2'];
	$resultP=mysqli_query($bdd,$req);
	$nbResultaP=mysqli_num_rows($resultP);
	$checklist="";
	if($nbResultaP>0){
	$rowCL=mysqli_fetch_array($resultP);
	$checklist=$rowCL['Libelle'];
	}

	$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne, CONCAT(LEFT(Prenom,1),LEFT(Nom,1),RIGHT(Nom,1)) AS Visa FROM new_rh_etatcivil 
	WHERE Id=".$_SESSION['Id_PersonneTR'];
	$resultP=mysqli_query($bdd,$req);
	$nbResultaP=mysqli_num_rows($resultP);
	$personne="";
	$visa="";
	if($nbResultaP>0){
	$rowP=mysqli_fetch_array($resultP);
	$personne=$rowP['Personne'];
	$visa=$rowP['Visa'];
	}

	$tabFR=array('janvier','février','mars','avril','mai','juin','juillet','ao&#251;t','septembre','octobre','novembre','décembre');
	$tabEN=array('january','february','march','april','may','june','july','august','september','october','november','december');

	$ladate= date("Y-m-d",strtotime(date(substr($_SESSION['EXTRACT_MoisQualite'],3)."-".substr($_SESSION['EXTRACT_MoisQualite'],0,2)."-1")." +0 month"));

	$moisAnnee=$ladate;
	$tabMoisAnnee=explode("-",$moisAnnee);
	$mois=$tabMoisAnnee[1];
	$annee=$tabMoisAnnee[0];

	$date_11= date("Y-m-d",strtotime(date(substr($_SESSION['EXTRACT_MoisQualite'],3)."-".substr($_SESSION['EXTRACT_MoisQualite'],0,2)."-1")." -11 month"));

	if($_SESSION['Langue']=="EN"){
		$sheet->setCellValue('D1','Site : '.$prestation);
		$sheet->setCellValue('A2',utf8_encode('Updated : '.date('d/m/Y')));
		$sheet->setCellValue('B2',utf8_encode('By : '.$personne));
		$sheet->setCellValue('D3',utf8_encode($tabEN[$mois-1]." ".$annee));
	}
	else{
		$sheet->setCellValue('D1','Prestation : '.$prestation);
		$sheet->setCellValue('A2',utf8_encode('Mis à jour le : '.date('d/m/Y')));
		$sheet->setCellValue('B2',utf8_encode('Par : '.$personne));
		$sheet->setCellValue('D3',utf8_encode($tabFR[$mois-1]." ".$annee));
	}
	$sheet->setCellValue('E2',utf8_encode($visa));
	$sheet->setCellValue('B3',utf8_encode($checklist));

	$Id_CLs=substr(str_replace(";",",",$_SESSION['EXTRACT_Checklist2']),0,-1);

	$req="SELECT DateControle
	FROM trame_controlecroise 
	LEFT JOIN trame_cl_version
	ON trame_controlecroise.Id_CLVersion=trame_cl_version.Id
	WHERE trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
	AND DateControle>='".$date_11."' AND DateControle<='".date("Y-m-d",strtotime($date_11." +12 month"))."' 
	AND trame_cl_version.Id_CL IN (".$Id_CLs.")
	";
	$resultCC=mysqli_query($bdd,$req);
	$nbResultaCC=mysqli_num_rows($resultCC);

	$req="SELECT DateControle
	FROM trame_controlecroise
	LEFT JOIN trame_cl_version
	ON trame_controlecroise.Id_CLVersion=trame_cl_version.Id
	WHERE trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
	AND DateControle>='".$date_11."' AND DateControle<='".date("Y-m-d",strtotime($date_11." +12 month"))."'  
	AND (SELECT COUNT(trame_controlecroise_contenu.Id) 
		FROM trame_controlecroise_contenu 
		WHERE Id_CC=trame_controlecroise.Id
		AND ValeurControle='KO')>0
	AND trame_cl_version.Id_CL IN (".$Id_CLs.")
	";
	$resultCCNOK=mysqli_query($bdd,$req);
	$nbResultaCCNOK=mysqli_num_rows($resultCCNOK);

	//NOMBRE DE NOK
	$req="SELECT trame_controlecroise_contenu.Id, trame_controlecroise.DateControle
	FROM trame_controlecroise_contenu
	LEFT JOIN trame_controlecroise 
	ON trame_controlecroise.Id=trame_controlecroise_contenu.Id_CC
	WHERE trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
	AND DateControle>='".$date_11."' AND DateControle<='".date("Y-m-d",strtotime($date_11." +12 month"))."' 
	AND trame_controlecroise_contenu.ValeurControle='KO'
	AND (SELECT 
		(SELECT Id_CL FROM trame_cl_version WHERE trame_cl_version.Id=trame_cl_version_contenu.Id_VersionCL) 
	FROM trame_cl_version_contenu 
	WHERE trame_cl_version_contenu.Id=trame_controlecroise_contenu.Id_Contenu
	) IN (".$Id_CLs.")
	";
	$resultNOK=mysqli_query($bdd,$req);
	$nbResultaNOK=mysqli_num_rows($resultNOK);

	$col="B";
	$nbNOKANNEE=0;
	for($ligne=1;$ligne<=12;$ligne++){
		$sheetDATA->setCellValue($col."1",date("m/Y",strtotime($date_11." +0 month")));
		
		$nbCC=0;
		if($nbResultaCC>0){
			mysqli_data_seek($resultCC,0);
			while($rowCC=mysqli_fetch_array($resultCC)){
				if(date("m/Y",strtotime($rowCC['DateControle']." +0 month"))==date("m/Y",strtotime($date_11." +0 month"))){
					$nbCC++;
				}
			}
		}
		$nbCCNOK=0;
		if($nbResultaCCNOK>0){
			mysqli_data_seek($resultCCNOK,0);
			while($rowCCNOK=mysqli_fetch_array($resultCCNOK)){
				if(date("m/Y",strtotime($rowCCNOK['DateControle']." +0 month"))==date("m/Y",strtotime($date_11." +0 month"))){
					$nbCCNOK++;
				}
			}
		}
		
		$nbNOK=0;
		if($nbResultaNOK>0){
			mysqli_data_seek($resultNOK,0);
			while($rowNOK=mysqli_fetch_array($resultNOK)){
				if(date("m/Y",strtotime($rowNOK['DateControle']." +0 month"))==date("m/Y",strtotime($date_11." +0 month"))){
					$nbNOK++;
					$nbNOKANNEE++;
				}
			}
		}
		
		$sheetDATA->setCellValue($col."2",$nbCC);
		$sheetDATA->setCellValue($col."3",$nbCCNOK);
		$sheetDATA->setCellValue($col."5",$objectifFTR/100);
		$sheetDATA->setCellValue($col."6",$nbNOK);
		
		$col++;
		$date_11= date("Y-m-d",strtotime($date_11." +1 month"));
	}


	//-------------------------------------------------------------------------------------//
	$dataseriesLabels = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$A$4', NULL, 1),
	);

	$xAxisTickValues = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$B$1:$M$1', NULL, 4),
	);

	$dataSeriesValues = array(
		new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$B$4:$M$4', NULL, 4),
	);

	//	Build the dataseries
	$series1 = new PHPExcel_Chart_DataSeries(
		PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
		PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,	// plotGrouping
		range(0, count($dataSeriesValues)-1),			// plotOrder
		$dataseriesLabels,								// plotLabel
		$xAxisTickValues,								// plotCategory
		$dataSeriesValues								// plotValues
	);
	//	Set additional dataseries parameters
	//		Make it a horizontal bar rather than a vertical column graph
	$series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

	$dataseriesLabels = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$A$5', NULL, 1),
	);

	$xAxisTickValues = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$B$1:$M$1', NULL, 4),
	);

	$dataSeriesValues = array(
		new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$B$5:$M$5', NULL, 4,array(),'none'),
	);

	//	Build the dataseries
	$series2 = new PHPExcel_Chart_DataSeries(
		PHPExcel_Chart_DataSeries::TYPE_LINECHART,		// plotType
		PHPExcel_Chart_DataSeries::GROUPING_STACKED,	// plotGrouping
		range(0, count($dataSeriesValues)-1),			// plotOrder
		$dataseriesLabels,								// plotLabel
		$xAxisTickValues,								// plotCategory
		$dataSeriesValues,								// plotValues
		true,
		PHPExcel_Chart_DataSeries::STYLE_SMOOTHMARKER
	);
	//	Set additional dataseries parameters
	//		Make it a horizontal bar rather than a vertical column graph
	$series2->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

	//	Set the chart legend 
	$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);

	//	Set the series in the plot area
	$layout = new PHPExcel_Chart_Layout();
	$layout->setShowVal(TRUE);
	$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series1,$series2));
	$title = new PHPExcel_Chart_Title(utf8_encode('First Time Right'));


	//	Create the chart
	$chart = new PHPExcel_Chart(
		'chart1',		// name
		$title,			// title
		$legend,		// legend
		$plotarea,		// plotArea
		true,			// plotVisibleOnly
		0,				// displayBlanksAs
		NULL,			// xAxisLabel
		NULL		// yAxisLabel
	);

	//	Set the position where the chart should appear in the worksheet
	$chart->setTopLeftPosition('A5');
	$chart->setBottomRightPosition('G23');

	//	Add the chart to the worksheet
	$sheet->addChart($chart);
	
	//-------------------------------------------------------------------------------------//
	$dataseriesLabels = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$A$6', NULL, 1),
	);

	$xAxisTickValues = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$B$1:$M$1', NULL, 4),
	);

	$dataSeriesValues = array(
		new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$B$6:$M$6', NULL, 4),
	);

	//	Build the dataseries
	$series1 = new PHPExcel_Chart_DataSeries(
		PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
		PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,	// plotGrouping
		range(0, count($dataSeriesValues)-1),			// plotOrder
		$dataseriesLabels,								// plotLabel
		$xAxisTickValues,								// plotCategory
		$dataSeriesValues								// plotValues
	);
	//	Set additional dataseries parameters
	//		Make it a horizontal bar rather than a vertical column graph
	$series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

	//	Set the chart legend 
	$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);

	//	Set the series in the plot area
	$layout = new PHPExcel_Chart_Layout();
	$layout->setShowVal(TRUE);
	$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series1));
	
	if($_SESSION['Langue']=="EN"){
		$title = new PHPExcel_Chart_Title(utf8_encode('Number of errors per month'));
	}
	else{
		$title = new PHPExcel_Chart_Title(utf8_encode('Nombre d\'erreur par mois'));
	}


	//	Create the chart
	$chart = new PHPExcel_Chart(
		'chart1',		// name
		$title,			// title
		NULL,		// legend
		$plotarea,		// plotArea
		true,			// plotVisibleOnly
		0,				// displayBlanksAs
		NULL,			// xAxisLabel
		NULL		// yAxisLabel
	);

	//	Set the position where the chart should appear in the worksheet
	$chart->setTopLeftPosition('A32');
	$chart->setBottomRightPosition('C50');

	//	Add the chart to the worksheet
	$sheet->addChart($chart);
	
	$req="SELECT COUNT(trame_controlecroise_contenu.Id) AS Nb, 
	(SELECT Chapitre FROM trame_cl_version_contenu WHERE trame_cl_version_contenu.Id=trame_controlecroise_contenu.Id_Contenu) AS Chapitre
	FROM trame_controlecroise_contenu
	LEFT JOIN trame_controlecroise 
	ON trame_controlecroise.Id=trame_controlecroise_contenu.Id_CC
	WHERE trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
	AND YEAR(trame_controlecroise.DateControle)=".$annee."
	AND MONTH(trame_controlecroise.DateControle)=".$mois."
	AND trame_controlecroise_contenu.ValeurControle='KO'
	GROUP BY Chapitre 
	ORDER BY Nb DESC ";
	$resultChp=mysqli_query($bdd,$req);
	$nbResultaChp=mysqli_num_rows($resultChp);
	$ligne=10;
	if($nbResultaChp>0){
		while($rowChp=mysqli_fetch_array($resultChp)){
			$sheetDATA->setCellValue('A'.$ligne,utf8_encode($rowChp['Chapitre']));
			$sheetDATA->setCellValue('B'.$ligne,utf8_encode($rowChp['Nb']));
			
			$sheetDATA->setCellValue('F'.$ligne,utf8_encode($rowChp['Chapitre']));
			$sheetDATA->setCellValue('G'.$ligne,utf8_encode($rowChp['Nb']));
			
			//Calcul de la moye,,e de NOK
			if($nbNOKANNEE>0){$sheetDATA->setCellValue('H'.$ligne,utf8_encode($rowChp['Nb']/$nbNOKANNEE));}
			$ligne++;
		}
	}
	if($ligne>10){$ligne--;}
	//-----------------------------------Répartition des erreurs du mois--------------------------------------------------//
	$dataseriesLabels = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$B$9', NULL, 1),
	);

	$xAxisTickValues = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$A$10:$A$'.$ligne, NULL, 3),
	);

	$dataSeriesValues = array(
		new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$B$10:$B$'.$ligne, NULL, 3),
	);

	//	Build the dataseries
	$series = new PHPExcel_Chart_DataSeries(
		PHPExcel_Chart_DataSeries::TYPE_PIECHART,		// plotType
		null,	// plotGrouping
		range(0, count($dataSeriesValues)-1),			// plotOrder
		null,								// plotLabel
		$xAxisTickValues,								// plotCategory
		$dataSeriesValues								// plotValues
	);
	//	Set additional dataseries parameters
	//		Make it a horizontal bar rather than a vertical column graph
	$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

	//	Set the chart legend 
	$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

	$layout = new PHPExcel_Chart_Layout();
	$layout->setShowVal(TRUE);

	//	Set the series in the plot area
	$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
	if($_SESSION['Langue']=="EN"){
		$title = new PHPExcel_Chart_Title(utf8_encode('Répartition des erreurs du mois'));
	}
	else{
		$title = new PHPExcel_Chart_Title(utf8_encode('Répartition des erreurs du mois'));
	}



	//	Create the chart
	$chart = new PHPExcel_Chart(
		'chart1',		// name
		$title,			// title
		$legend,		// legend
		$plotarea,		// plotArea
		true,			// plotVisibleOnly
		0,				// displayBlanksAs
		NULL,			// xAxisLabel
		NULL		// yAxisLabel
	);

	//	Set the position where the chart should appear in the worksheet
	$chart->setTopLeftPosition('C32');
	$chart->setBottomRightPosition('G50');

	//	Add the chart to the worksheet
	$sheet->addChart($chart);
	
	//--------------------------------------NB NOK / MOIS-----------------------------------------------//
	$dataseriesLabels = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$H$9', NULL, 1),
	);

	$xAxisTickValues = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$F$10:$F$'.$ligne, NULL, 4),
	);

	$dataSeriesValues = array(
		new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$H$10:$H$'.$ligne, NULL, 4),
	);

	//	Build the dataseries
	$series1 = new PHPExcel_Chart_DataSeries(
		PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
		PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,	// plotGrouping
		range(0, count($dataSeriesValues)-1),			// plotOrder
		$dataseriesLabels,								// plotLabel
		$xAxisTickValues,								// plotCategory
		$dataSeriesValues								// plotValues
	);
	//	Set additional dataseries parameters
	//		Make it a horizontal bar rather than a vertical column graph
	$series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

	$dataseriesLabels = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$G$9', NULL, 1),
	);

	$xAxisTickValues = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$F$10:$F$'.$ligne, NULL, 4),
	);

	$dataSeriesValues = array(
		new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$G$10:$G$'.$ligne, NULL, 4,array(),'none'),
	);

	//	Build the dataseries
	$series2 = new PHPExcel_Chart_DataSeries(
		PHPExcel_Chart_DataSeries::TYPE_LINECHART,		// plotType
		PHPExcel_Chart_DataSeries::GROUPING_STACKED,	// plotGrouping
		range(0, count($dataSeriesValues)-1),			// plotOrder
		$dataseriesLabels,								// plotLabel
		$xAxisTickValues,								// plotCategory
		$dataSeriesValues,								// plotValues
		true,
		PHPExcel_Chart_DataSeries::STYLE_SMOOTHMARKER
	);
	//	Set additional dataseries parameters
	//		Make it a horizontal bar rather than a vertical column graph
	$series2->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

	//	Set the chart legend 
	$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

	//	Set the series in the plot area
	$layout = new PHPExcel_Chart_Layout();
	$layout->setShowVal(TRUE);
	$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series1,$series2));
	if($_SESSION['Langue']=="EN"){
		$title = new PHPExcel_Chart_Title(utf8_encode('Recurrence of errors'));
	}
	else{
		$title = new PHPExcel_Chart_Title(utf8_encode('Récurrence des erreurs'));
	}


	//	Create the chart
	$chart = new PHPExcel_Chart(
		'chart1',		// name
		$title,			// title
		$legend,		// legend
		$plotarea,		// plotArea
		true,			// plotVisibleOnly
		0,				// displayBlanksAs
		NULL,			// xAxisLabel
		NULL		// yAxisLabel
	);

	//	Set the position where the chart should appear in the worksheet
	$chart->setTopLeftPosition('A59');
	$chart->setBottomRightPosition('G76');

	//	Add the chart to the worksheet
	$sheet->addChart($chart);
	
	//Liste de la checklist 
	$req="SELECT trame_cl_version_contenu.Id, Chapitre, Ponderation, Controle ";
	$req.="FROM trame_cl_version_contenu LEFT JOIN trame_cl_version ON trame_cl_version_contenu.Id_VersionCL=trame_cl_version.Id ";
	$req.="WHERE trame_cl_version.Id_CL IN (".$Id_CLs.") 
			AND Valide = 1 ";
	$req.=" ORDER BY trame_cl_version_contenu.Ordre";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	if($nbResulta>0){
		$ligne=87;
		while($row=mysqli_fetch_array($result)){
			$sheet->setCellValue('A'.$ligne,utf8_encode($row['Chapitre']));
			$sheet->setCellValue('B'.$ligne,utf8_encode($row['Controle']));
			$sheet->setCellValue('E'.$ligne,utf8_encode($row['Ponderation']));
			
			//Nombre d'erreur 
			$req="SELECT trame_controlecroise.Id_CLVersion
			FROM trame_controlecroise_contenu
			LEFT JOIN trame_controlecroise 
			ON trame_controlecroise.Id=trame_controlecroise_contenu.Id_CC
			WHERE trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
			AND YEAR(trame_controlecroise.DateControle)=".$annee."
			AND MONTH(trame_controlecroise.DateControle)=".$mois."
			AND trame_controlecroise_contenu.Id_Contenu=".$row['Id']."
			AND trame_controlecroise_contenu.ValeurControle='KO' ";
			$resultKO=mysqli_query($bdd,$req);
			$nbKO=mysqli_num_rows($resultKO);
			
			if($nbKO>0){$sheet->setCellValue('F'.$ligne,utf8_encode($nbKO));}
			
			$ligne++;
		}
	}
	
	//CC Non conforme
	$req="SELECT trame_controlecroise.Id_CLVersion,trame_controlecroise_contenu.Commentaire,DateControle,DateAutoC,DateReControle,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Controleur) AS Controleur,
	(SELECT Designation FROM trame_travaileffectue WHERE Id=Id_TravailEffectue) AS Livrable,
	(SELECT Chapitre FROM trame_cl_version_contenu LEFT JOIN trame_cl_version ON trame_cl_version_contenu.Id_VersionCL=trame_cl_version.Id WHERE trame_controlecroise_contenu.Id_Contenu=trame_cl_version_contenu.Id) AS Chapitre,
	(SELECT Ponderation FROM trame_cl_version_contenu LEFT JOIN trame_cl_version ON trame_cl_version_contenu.Id_VersionCL=trame_cl_version.Id WHERE trame_controlecroise_contenu.Id_Contenu=trame_cl_version_contenu.Id) AS Ponderation,
	(SELECT Controle FROM trame_cl_version_contenu LEFT JOIN trame_cl_version ON trame_cl_version_contenu.Id_VersionCL=trame_cl_version.Id WHERE trame_controlecroise_contenu.Id_Contenu=trame_cl_version_contenu.Id) AS Controle
	FROM trame_controlecroise_contenu
	LEFT JOIN trame_controlecroise 
	ON trame_controlecroise.Id=trame_controlecroise_contenu.Id_CC
	WHERE trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
	AND YEAR(trame_controlecroise.DateControle)=".$annee."
	AND MONTH(trame_controlecroise.DateControle)=".$mois."
	AND trame_controlecroise_contenu.ValeurControle='KO' 
	AND (SELECT 
		(SELECT Id_CL FROM trame_cl_version WHERE trame_cl_version.Id=trame_cl_version_contenu.Id_VersionCL) 
	FROM trame_cl_version_contenu 
	WHERE trame_cl_version_contenu.Id=trame_controlecroise_contenu.Id_Contenu
	) IN (".$Id_CLs.")
	";

	$resultKO=mysqli_query($bdd,$req);
	$nbResultaKO=mysqli_num_rows($resultKO);
	if($nbResultaKO>0){
		$ligne=2;
		while($row=mysqli_fetch_array($resultKO)){
			$sheetKO->setCellValue('A'.$ligne,utf8_encode($row['Chapitre']));
			$sheetKO->setCellValue('B'.$ligne,utf8_encode($row['Controle']));
			$sheetKO->setCellValue('C'.$ligne,utf8_encode($row['Ponderation']));
			$sheetKO->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Commentaire'])));
			if(AfficheDateFR($row['DateAutoC'])<>""){
				$date = explode("-",$row['DateAutoC']);
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheetKO->setCellValue('E'.$ligne,$time);
				$sheetKO->getStyle('E'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
			$sheetKO->setCellValue('F'.$ligne,utf8_encode($row['Controleur']));
			if(AfficheDateFR($row['DateControle'])<>""){
				$date = explode("-",$row['DateControle']);
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheetKO->setCellValue('G'.$ligne,$time);
				$sheetKO->getStyle('G'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
			if(AfficheDateFR($row['DateReControle'])<>""){
				$date = explode("-",$row['DateReControle']);
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheetKO->setCellValue('H'.$ligne,$time);
				$sheetKO->getStyle('H'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
			
			$sheetKO->setCellValue('I'.$ligne,utf8_encode($row['Livrable']));
			$ligne++;
		}
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_CC.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->setIncludeCharts(TRUE);
$chemin = '../../tmp/Extract_CC.xlsx';
$writer->save($chemin);
readfile($chemin);
?>