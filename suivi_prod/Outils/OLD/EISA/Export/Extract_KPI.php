<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_KPI.xlsx');

$sheetKPI = $excel->getSheetByName('KPI');
$sheetTAB = $excel->getSheetByName('Tableau');

//***************Récurrence type défaut***************//
//Tableau
$sheetTAB->setCellValue('A1',utf8_encode("Récurrence Type défaut ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['du']))." au ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['au']))));

$req="SELECT Id_TypeDefaut,
	sp_atrtypedefaut.Libelle AS TypeDefaut,
	COUNT(sp_atram.Id) AS Nb
	FROM sp_atram 
	LEFT JOIN sp_atrtypedefaut
	ON sp_atram.Id_TypeDefaut=sp_atrtypedefaut.Id
	WHERE sp_atram.Id_Prestation=463
	AND sp_atram.Id_TypeDefaut<>0
	AND sp_atram.DateCreation>='".TrsfDate_($_GET['du'])."'
	AND sp_atram.DateCreation<='".TrsfDate_($_GET['au'])."'
	GROUP BY sp_atram.Id_TypeDefaut
	ORDER BY Nb DESC ";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=3;
$derniereLigneSup1=3;
if($nbResulta>0){
	while($rowAM=mysqli_fetch_array($result)){
		$sheetTAB->setCellValue('A'.$ligne,utf8_encode($rowAM['TypeDefaut']));
		$sheetTAB->setCellValue('B'.$ligne,utf8_encode($rowAM['Nb']));
		if($rowAM['Nb']>1){$derniereLigneSup1++;}
		$ligne++;
	}
}

//Graphique
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Tableau!$B$2', NULL, 1),
);

if($derniereLigneSup1>3){$derniereLigneSup1=$derniereLigneSup1-1;}
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Tableau!$A$3:$A$'.$derniereLigneSup1, NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Tableau!$B$3:$B$'.$derniereLigneSup1, NULL, 4),
);

//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,	// plotGrouping
	range(0, count($dataSeriesValues)-1),			// plotOrder
	$dataseriesLabels,								// plotLabel
	$xAxisTickValues,								// plotCategory
	$dataSeriesValues								// plotValues
);
//	Set additional dataseries parameters
//		Make it a horizontal bar rather than a vertical column graph
$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

//	Set the chart legend 
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

//	Set the series in the plot area
$layout = new PHPExcel_Chart_Layout();
$layout->setShowVal(true);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode("Récurrence Type défaut ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['du']))." au ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['au']))));

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
$chart->setTopLeftPosition('A7');
$chart->setBottomRightPosition('K22');

//	Add the chart to the worksheet
$sheetKPI->addChart($chart);


//***************Récurrence produit impacté***************//
//Tableau

$sheetTAB->setCellValue('A1',utf8_encode("Récurrence produit impacté ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['du']))." au ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['au']))));

$req="SELECT Id_MomentDetection,
	sp_atrmomentdetection.Libelle AS MomentDetection,
	COUNT(sp_atram.Id) AS Nb
	FROM sp_atram 
	LEFT JOIN sp_atrmomentdetection
	ON sp_atram.Id_MomentDetection=sp_atrmomentdetection.Id
	WHERE sp_atram.Id_Prestation=463
	AND sp_atram.Id_MomentDetection<>0
	AND sp_atram.DateCreation>='".TrsfDate_($_GET['du'])."'
	AND sp_atram.DateCreation<='".TrsfDate_($_GET['au'])."'
	GROUP BY sp_atram.Id_MomentDetection
	ORDER BY Nb DESC ";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=3;
$derniereLigneSup1=3;
if($nbResulta>0){
	while($rowAM=mysqli_fetch_array($result)){
		$sheetTAB->setCellValue('D'.$ligne,utf8_encode($rowAM['MomentDetection']));
		$sheetTAB->setCellValue('E'.$ligne,utf8_encode($rowAM['Nb']));
		if($rowAM['Nb']>1){$derniereLigneSup1++;}
		$ligne++;
	}
}

//Graphique
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Tableau!$E$2', NULL, 1),
);

if($derniereLigneSup1>3){$derniereLigneSup1=$derniereLigneSup1-1;}
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Tableau!$D$3:$D$'.$derniereLigneSup1, NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Tableau!$E$3:$E$'.$derniereLigneSup1, NULL, 4),
);

//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,	// plotGrouping
	range(0, count($dataSeriesValues)-1),			// plotOrder
	$dataseriesLabels,								// plotLabel
	$xAxisTickValues,								// plotCategory
	$dataSeriesValues								// plotValues
);
//	Set additional dataseries parameters
//		Make it a horizontal bar rather than a vertical column graph
$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

//	Set the chart legend 
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

//	Set the series in the plot area
$layout = new PHPExcel_Chart_Layout();
$layout->setShowVal(true);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode("Récurrence produit impacté ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['du']))." au ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['au']))));

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
$chart->setTopLeftPosition('A25');
$chart->setBottomRightPosition('K40');

//	Add the chart to the worksheet
$sheetKPI->addChart($chart);

//***************AM per A/C and per origin of detection***************//
//***********************Originated by all****************************//
//Tableau

$sheetTAB->setCellValue('A1',utf8_encode("AM per A/C and per origin of detection Originated by all"));

$req="SELECT DISTINCT Id_MomentDetection,
	sp_atrmomentdetection.Libelle AS MomentDetection
	FROM sp_atram 
	LEFT JOIN sp_atrmomentdetection
	ON sp_atram.Id_MomentDetection=sp_atrmomentdetection.Id
	WHERE sp_atram.Id_Prestation=463
	AND sp_atram.Id_MomentDetection<>0
	AND sp_atram.DateCreation>='".TrsfDate_($_GET['du'])."'
	AND sp_atram.DateCreation<='".TrsfDate_($_GET['au'])."'
	ORDER BY MomentDetection ASC ";
$resultMoment=mysqli_query($bdd,$req);
$nbResultaMoment=mysqli_num_rows($resultMoment);

$req="SELECT sp_atram.MSN, CONCAT('01/',IF(MONTH(sp_atram.DateCreation)<10,CONCAT('0',MONTH(sp_atram.DateCreation)),MONTH(sp_atram.DateCreation)),'/',YEAR(sp_atram.DateCreation)) AS MoisAnnee,
	COUNT(sp_atram.Id) AS Nb
	FROM sp_atram 
	WHERE sp_atram.Id_Prestation=463
	AND sp_atram.DateCreation>='".TrsfDate_($_GET['du'])."'
	AND sp_atram.DateCreation<='".TrsfDate_($_GET['au'])."'
	GROUP BY sp_atram.MSN, CONCAT('01/',IF(MONTH(sp_atram.DateCreation)<10,CONCAT('0',MONTH(sp_atram.DateCreation)),MONTH(sp_atram.DateCreation)),'/',YEAR(sp_atram.DateCreation))
	ORDER BY MoisAnnee ASC, MSN ASC ";
$resultMSN_AM=mysqli_query($bdd,$req);
$nbResultaMSN_AM=mysqli_num_rows($resultMSN_AM);

$req="SELECT sp_atram.MSN, CONCAT('01/',IF(MONTH(sp_atram.DateCreation)<10,CONCAT('0',MONTH(sp_atram.DateCreation)),MONTH(sp_atram.DateCreation)),'/',YEAR(sp_atram.DateCreation)) AS MoisAnnee, Id_MomentDetection,
	sp_atrmomentdetection.Libelle AS MomentDetection,
	COUNT(sp_atram.Id) AS Nb
	FROM sp_atram 
	LEFT JOIN sp_atrmomentdetection
	ON sp_atram.Id_MomentDetection=sp_atrmomentdetection.Id
	WHERE sp_atram.Id_Prestation=463
	AND sp_atram.Id_MomentDetection<>0
	AND sp_atram.DateCreation>='".TrsfDate_($_GET['du'])."'
	AND sp_atram.DateCreation<='".TrsfDate_($_GET['au'])."'
	GROUP BY sp_atram.MSN, CONCAT('01/',IF(MONTH(sp_atram.DateCreation)<10,CONCAT('0',MONTH(sp_atram.DateCreation)),MONTH(sp_atram.DateCreation)),'/',YEAR(sp_atram.DateCreation)), sp_atram.Id_MomentDetection
	ORDER BY MoisAnnee ASC ";
$resultMSN_Moment=mysqli_query($bdd,$req);
$nbResultaMSN_Moment=mysqli_num_rows($resultMSN_Moment);

$colonne2="I";
if($nbResultaMoment>0){
	while($rowMoment=mysqli_fetch_array($resultMoment)){
		$colonne2++;
		$sheetTAB->setCellValue($colonne2.'2',utf8_encode($rowMoment['MomentDetection']));
	}
}

$ligne=3;
if($nbResultaMSN_AM>0){
	$Mois="";
	while($rowMSN_AM=mysqli_fetch_array($resultMSN_AM)){
		if($Mois<>$rowMSN_AM['MoisAnnee']){
			$sheetTAB->setCellValue('G'.$ligne,utf8_encode($rowMSN_AM['MoisAnnee']));
			$Mois=$rowMSN_AM['MoisAnnee'];
		}
		$sheetTAB->setCellValue('H'.$ligne,utf8_encode($rowMSN_AM['MSN']));
		$sheetTAB->setCellValue('I'.$ligne,utf8_encode($rowMSN_AM['Nb']));
		
		$colonne="J";
		mysqli_data_seek($resultMoment,0);
		while($rowMoment=mysqli_fetch_array($resultMoment)){
			mysqli_data_seek($resultMSN_Moment,0);
			$nb=0;
			while($rowMSN_Moment=mysqli_fetch_array($resultMSN_Moment)){
				if($rowMSN_Moment['Id_MomentDetection']==$rowMoment['Id_MomentDetection'] && $rowMSN_Moment['MSN']==$rowMSN_AM['MSN'] && $rowMSN_Moment['MoisAnnee']==$rowMSN_AM['MoisAnnee']){
					$sheetTAB->setCellValue($colonne.$ligne,utf8_encode($rowMSN_Moment['Nb']));
				}
			}
			$colonne++;
		}
		$ligne++;
	}
}

//Graphique
$tab = array();
$colonne2="J";
$i=0;
if($nbResultaMoment>0){
	mysqli_data_seek($resultMoment,0);
	while($rowMoment=mysqli_fetch_array($resultMoment)){
		$tab[$i]=new PHPExcel_Chart_DataSeriesValues('String', 'Tableau!$'.$colonne2.'$2', NULL, 1);
		$i++;
		$colonne2++;
	}
}
$dataseriesLabels = $tab;

$dataseriesLabels2 = array(new PHPExcel_Chart_DataSeriesValues('String', 'Tableau!$I$2', NULL, 1));

if($ligne>3){
	$ligne=$ligne-1;
	$xAxisTickValues = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'Tableau!$G$3:$H$'.$ligne, NULL, 12),
	);

	$tab = array();
	$colonne2="J";
	$i=0;
	if($nbResultaMoment>0){
		mysqli_data_seek($resultMoment,0);
		while($rowMoment=mysqli_fetch_array($resultMoment)){
			$tab[$i]=new PHPExcel_Chart_DataSeriesValues('Number', 'Tableau!$'.$colonne2.'$3:$'.$colonne2.'$'.$ligne, NULL, 12);
			$i++;
			$colonne2++;
		}
	}

	$dataSeriesValues = $tab;

	$dataSeriesValues2 = array(new PHPExcel_Chart_DataSeriesValues('Number', 'Tableau!$I$3:$I$'.$ligne, NULL, 12));


	//	Build the dataseries
	$series = new PHPExcel_Chart_DataSeries(
		PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
		PHPExcel_Chart_DataSeries::GROUPING_PERCENT_STACKED,	// plotGrouping
		range(0, count($dataSeriesValues)-1),			// plotOrder
		$dataseriesLabels,								// plotLabel
		$xAxisTickValues,								// plotCategory
		$dataSeriesValues								// plotValues
	);

	//	Set additional dataseries parameters
	//		Make it a horizontal bar rather than a vertical column graph
	$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

	//	Build the dataseries
	$series2 = new PHPExcel_Chart_DataSeries(
		PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
		PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,	// plotGrouping
		range(0, count($dataSeriesValues2)-1),			// plotOrder
		$dataseriesLabels2,								// plotLabel
		$xAxisTickValues,								// plotCategory
		$dataSeriesValues2								// plotValues
	);



	//	Set additional dataseries parameters
	//		Make it a horizontal bar rather than a vertical column graph
	$series2->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

	//	Set the chart legend 
	$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);

	//	Set the series in the plot area
	$layout = new PHPExcel_Chart_Layout();
	$layout->setShowVal(true);
	$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));

	$title = new PHPExcel_Chart_Title(utf8_encode("AM per A/C and per origin of detection Originated by all"));

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
	$chart->setTopLeftPosition('A43');
	$chart->setBottomRightPosition('K59');

	//	Add the chart to the worksheet

	$sheetKPI->addChart($chart);


	//GRAPH 2
	//	Set the series in the plot area
	$layout = new PHPExcel_Chart_Layout();
	$layout->setShowVal(true);
	$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series2));

	$title = new PHPExcel_Chart_Title(utf8_encode("AM per A/C Originated by all"));

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
	$chart->setTopLeftPosition('A60');
	$chart->setBottomRightPosition('K77');

	//	Add the chart to the worksheet
	$sheetKPI->addChart($chart);
}
//***************AM per A/C and per origin of detection***************//
//***********************Originated by AAA****************************//
//Tableau

$sheetTAB->setCellValue('A1',utf8_encode("AM per A/C and per origin of detection Originated by AAA"));

$req="SELECT DISTINCT Id_MomentDetection,
	sp_atrmomentdetection.Libelle AS MomentDetection
	FROM sp_atram 
	LEFT JOIN sp_atrmomentdetection
	ON sp_atram.Id_MomentDetection=sp_atrmomentdetection.Id
	WHERE sp_atram.Id_Prestation=463
	AND sp_atram.Id_MomentDetection<>0
	AND sp_atram.DateCreation>='".TrsfDate_($_GET['du'])."'
	AND sp_atram.DateCreation<='".TrsfDate_($_GET['au'])."'
	AND (SELECT Libelle FROM sp_atrimputation WHERE sp_atrimputation.Id=sp_atram.Id_Imputation)='USAAA'
	ORDER BY MomentDetection ASC ";
$resultMoment=mysqli_query($bdd,$req);
$nbResultaMoment=mysqli_num_rows($resultMoment);

$req="SELECT sp_atram.MSN, CONCAT('01/',IF(MONTH(sp_atram.DateCreation)<10,CONCAT('0',MONTH(sp_atram.DateCreation)),MONTH(sp_atram.DateCreation)),'/',YEAR(sp_atram.DateCreation)) AS MoisAnnee,
	COUNT(sp_atram.Id) AS Nb
	FROM sp_atram 
	LEFT JOIN sp_atrmomentdetection
	ON sp_atram.Id_MomentDetection=sp_atrmomentdetection.Id
	WHERE sp_atram.Id_Prestation=463
	AND sp_atram.Id_MomentDetection<>0
	AND sp_atram.DateCreation>='".TrsfDate_($_GET['du'])."'
	AND sp_atram.DateCreation<='".TrsfDate_($_GET['au'])."'
	AND (SELECT Libelle FROM sp_atrimputation WHERE sp_atrimputation.Id=sp_atram.Id_Imputation)='USAAA'
	GROUP BY sp_atram.MSN, CONCAT('01/',IF(MONTH(sp_atram.DateCreation)<10,CONCAT('0',MONTH(sp_atram.DateCreation)),MONTH(sp_atram.DateCreation)),'/',YEAR(sp_atram.DateCreation))
	ORDER BY MoisAnnee ASC, MSN ASC ";
$resultMSN_AM=mysqli_query($bdd,$req);
$nbResultaMSN_AM=mysqli_num_rows($resultMSN_AM);

$req="SELECT sp_atram.MSN, CONCAT('01/',IF(MONTH(sp_atram.DateCreation)<10,CONCAT('0',MONTH(sp_atram.DateCreation)),MONTH(sp_atram.DateCreation)),'/',YEAR(sp_atram.DateCreation)) AS MoisAnnee, Id_MomentDetection,
	sp_atrmomentdetection.Libelle AS MomentDetection,
	COUNT(sp_atram.Id) AS Nb
	FROM sp_atram 
	LEFT JOIN sp_atrmomentdetection
	ON sp_atram.Id_MomentDetection=sp_atrmomentdetection.Id
	WHERE sp_atram.Id_Prestation=463
	AND sp_atram.Id_MomentDetection<>0
	AND sp_atram.DateCreation>='".TrsfDate_($_GET['du'])."'
	AND sp_atram.DateCreation<='".TrsfDate_($_GET['au'])."'
	AND (SELECT Libelle FROM sp_atrimputation WHERE sp_atrimputation.Id=sp_atram.Id_Imputation)='USAAA'
	GROUP BY sp_atram.MSN, CONCAT('01/',IF(MONTH(sp_atram.DateCreation)<10,CONCAT('0',MONTH(sp_atram.DateCreation)),MONTH(sp_atram.DateCreation)),'/',YEAR(sp_atram.DateCreation)), sp_atram.Id_MomentDetection
	ORDER BY MoisAnnee ASC ";
$resultMSN_Moment=mysqli_query($bdd,$req);
$nbResultaMSN_Moment=mysqli_num_rows($resultMSN_Moment);

$colonne2="AA";
if($nbResultaMoment>0){
	while($rowMoment=mysqli_fetch_array($resultMoment)){
		$colonne2++;
		$sheetTAB->setCellValue($colonne2.'2',utf8_encode($rowMoment['MomentDetection']));
	}
}

$ligne=3;
if($nbResultaMSN_AM>0){
	$Mois="";
	while($rowMSN_AM=mysqli_fetch_array($resultMSN_AM)){
		if($Mois<>$rowMSN_AM['MoisAnnee']){
			$sheetTAB->setCellValue('Z'.$ligne,utf8_encode($rowMSN_AM['MoisAnnee']));
			$Mois=$rowMSN_AM['MoisAnnee'];
		}
		$sheetTAB->setCellValue('AA'.$ligne,utf8_encode($rowMSN_AM['MSN']));
		
		$colonne="AB";
		mysqli_data_seek($resultMoment,0);
		while($rowMoment=mysqli_fetch_array($resultMoment)){
			mysqli_data_seek($resultMSN_Moment,0);
			$nb=0;
			while($rowMSN_Moment=mysqli_fetch_array($resultMSN_Moment)){
				if($rowMSN_Moment['Id_MomentDetection']==$rowMoment['Id_MomentDetection'] && $rowMSN_Moment['MSN']==$rowMSN_AM['MSN'] && $rowMSN_Moment['MoisAnnee']==$rowMSN_AM['MoisAnnee']){
					$sheetTAB->setCellValue($colonne.$ligne,utf8_encode($rowMSN_Moment['Nb']));
				}
			}
			$colonne++;
		}
		$ligne++;
	}
}

//Graphique
$tab = array();
$colonne2="AB";
$i=0;
if($nbResultaMoment>0){
	mysqli_data_seek($resultMoment,0);
	while($rowMoment=mysqli_fetch_array($resultMoment)){
		$tab[$i]=new PHPExcel_Chart_DataSeriesValues('String', 'Tableau!$'.$colonne2.'$2', NULL, 1);
		$i++;
		$colonne2++;
	}
}
$dataseriesLabels = $tab;

if($ligne>3){
	$ligne=$ligne-1;
	$xAxisTickValues = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'Tableau!$Z$3:$AA$'.$ligne, NULL, 12),
	);

	$tab = array();
	$colonne2="AB";
	$i=0;
	if($nbResultaMoment>0){
		mysqli_data_seek($resultMoment,0);
		while($rowMoment=mysqli_fetch_array($resultMoment)){
			$tab[$i]=new PHPExcel_Chart_DataSeriesValues('Number', 'Tableau!$'.$colonne2.'$3:$'.$colonne2.'$'.$ligne, NULL, 12);
			$i++;
			$colonne2++;
		}
	}

	$dataSeriesValues = $tab;

	//	Build the dataseries
	$series = new PHPExcel_Chart_DataSeries(
		PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
		PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,	// plotGrouping
		range(0, count($dataSeriesValues)-1),			// plotOrder
		$dataseriesLabels,								// plotLabel
		$xAxisTickValues,								// plotCategory
		$dataSeriesValues								// plotValues
	);

	//	Set additional dataseries parameters
	//		Make it a horizontal bar rather than a vertical column graph
	$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

	//	Set the chart legend 
	$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);

	//	Set the series in the plot area
	$layout = new PHPExcel_Chart_Layout();
	$layout->setShowVal(true);
	$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));

	$title = new PHPExcel_Chart_Title(utf8_encode("AM per A/C and per origin of detection Originated by AAA"));

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
	$chart->setTopLeftPosition('A78');
	$chart->setBottomRightPosition('K96');

	//	Add the chart to the worksheet
	$sheetKPI->addChart($chart);
}
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="KPI.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->setIncludeCharts(TRUE);
$chemin = '../../../tmp/KPI.xlsx';
$writer->save($chemin);
readfile($chemin);
?>