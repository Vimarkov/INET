<?php
session_start();
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_OTD.xlsx');

$sheet = $excel->getSheetByName('Indicateurs');

$req="SELECT Libelle FROM trame_prestation WHERE Id=".$_SESSION['Id_PrestationTR'];
$resultP=mysqli_query($bdd,$req);
$nbResultaP=mysqli_num_rows($resultP);
$prestation="";
if($nbResultaP>0){
$rowP=mysqli_fetch_array($resultP);
$prestation=$rowP['Libelle'];
}
$sheet->setCellValue('D1','Site : '.$prestation);

//-----------------------------------Taux de conformité (OTD))--------------------------------------------------//
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$I$7', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$H$8:$H$20', NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$I$8:$I$20', NULL, 4),
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
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$J$7', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$H$8:$H$20', NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$J$8:$J$20', NULL, 4,array(),'none'),
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
$title = new PHPExcel_Chart_Title(utf8_encode('Taux de conformité (OTD)'));


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

//-----------------------------------Nombre d’anomalies OTD--------------------------------------------------//
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$J$33', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$K$33', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$L$33', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$M$33', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$H$34:$H$45', NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$J$34:$J$45', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$K$34:$K$45', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$L$34:$L$45', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$M$34:$M$45', NULL, 4),
);

//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_STACKED,	// plotGrouping
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
$layout->setShowVal(TRUE);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode("Nombre d'anomalies OTD"));


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
$chart->setTopLeftPosition('A32');
$chart->setBottomRightPosition('C48');

//	Add the chart to the worksheet
$sheet->addChart($chart);

//-----------------------------------Répartition des anomalies du mois (AAA)--------------------------------------------------//
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$I$48', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$H$49:$H$57', NULL, 3),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$I$49:$I$57', NULL, 3),
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
$title = new PHPExcel_Chart_Title(utf8_encode('Répartition des anomalies du mois (AAA)'));



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
$chart->setBottomRightPosition('G48');

//	Add the chart to the worksheet
$sheet->addChart($chart);
//-----------------------------------Récurrence anomalies OTD (AAA)--------------------------------------------------//
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$J$71', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$I$72:$I$79', NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$J$72:$J$79', NULL, 4),
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

//	Set the series in the plot area
$layout = new PHPExcel_Chart_Layout();
$layout->setShowVal(TRUE);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode('Récurrence anomalies OTD (AAA)'));


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
$chart->setTopLeftPosition('A57');
$chart->setBottomRightPosition('G75');

//	Add the chart to the worksheet
$sheet->addChart($chart);


//-------------------------CALCUL-----------------------------------------//
//Taux de conformité + Nombre d'anomalies OTD

$mois= date("Y-m-d",strtotime(date(substr($_SESSION['EXTRACT_MoisQualite'],3)."-".substr($_SESSION['EXTRACT_MoisQualite'],0,2)."-1")." -11 month"));

$req="SELECT Id, DatePreparateur, StatutDelai,Id_ResponsableDelai,Id_CauseDelai, ";
$req.="(SELECT Libelle FROM trame_responsabledelais WHERE trame_responsabledelais.Id=trame_travaileffectue.Id_ResponsableDelai) AS Responsable ";
$req.="FROM trame_travaileffectue WHERE Statut='VALIDE' AND Id_Prestation=".$_SESSION['Id_PrestationTR']." AND ";
$req.=" DatePreparateur>='".$mois."' AND DatePreparateur<='".date("Y-m-d",strtotime($mois." +12 month"))."' ";
if($_SESSION['EXTRACT_WPQualite2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_WPQualite2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_WP=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$resultT=mysqli_query($bdd,$req);
$nbResultaT=mysqli_num_rows($resultT);
for($ligne=8;$ligne<=19;$ligne++){
	$sheet->setCellValue('H'.$ligne,date("m/Y",strtotime($mois." +0 month")));
	$sheet->setCellValue('H'.($ligne+26),date("m/Y",strtotime($mois." +0 month")));
	$nbLiv=0;
	$nbLivKO=0;
	$nbAAA=0;
	$nbClient=0;
	$nbFournisseur=0;
	$nbAutre=0;
	if ($nbResultaT>0){
		mysqli_data_seek($resultT,0);
		while($rowT=mysqli_fetch_array($resultT)){
			if(date("m",strtotime($rowT['DatePreparateur']." +0 month"))==date("m",strtotime($mois." +0 month")) && date("Y",strtotime($rowT['DatePreparateur']." +0 month"))==date("Y",strtotime($mois." +0 month"))){
				$nbLiv++;
				if($rowT['StatutDelai']=="KO"){
					$nbLivKO++;
					if($rowT['Responsable']=="AAA"){$nbAAA++;}
					elseif($rowT['Responsable']=="Client"){$nbClient++;}
					elseif($rowT['Responsable']=="Fournisseur"){$nbFournisseur++;}
					else{$nbAutre++;}
				}
			}
		}
	}
	$sheet->setCellValue('I'.($ligne+26),$nbLivKO);
	$sheet->setCellValue('J'.($ligne+26),$nbAAA);
	$sheet->setCellValue('K'.($ligne+26),$nbClient);
	$sheet->setCellValue('L'.($ligne+26),$nbFournisseur);
	$sheet->setCellValue('M'.($ligne+26),$nbAutre);
	if($nbLiv>0){
		$sheet->setCellValue('I'.$ligne,($nbLiv-$nbLivKO)/$nbLiv);
	}
	else{
		$sheet->setCellValue('I'.$ligne,1);
	}
	$mois= date("Y-m-d",strtotime($mois." +1 month"));
}

$req="SELECT DISTINCT Id_CauseDelai, ";
$req.="(SELECT Libelle FROM trame_causedelais WHERE trame_causedelais.Id=trame_travaileffectue.Id_CauseDelai) AS CauseDelais ";
$req.="FROM trame_travaileffectue WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_CauseDelai<>0 AND ";
$req.=" DatePreparateur>='".$mois."' AND DatePreparateur<='".date("Y-m-d",strtotime($mois." +12 month"))."' ";
if($_SESSION['EXTRACT_WPQualite2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_WPQualite2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_WP=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$req.="ORDER BY CauseDelais ";

$resultR=mysqli_query($bdd,$req);
$nbResultaR=mysqli_num_rows($resultR);
if ($nbResultaT>0){
	$ligne=49;
	while($row=mysqli_fetch_array($resultR)){
		$sheet->setCellValue('H'.$ligne,$row['CauseDelais']);
		$nb=0;
		if ($nbResultaT>0){
			mysqli_data_seek($resultT,0);
			while($rowT=mysqli_fetch_array($resultT)){
				if(date("m",strtotime($rowT['DatePreparateur']." +0 month"))==date("m",strtotime($mois." -1 month")) && date("Y",strtotime($rowT['DatePreparateur']." +0 month"))==date("Y",strtotime($mois." -1 month"))){
					if($rowT['Id_CauseDelai']==$row['Id_CauseDelai'] && $rowT['Responsable']=="AAA"){
						$nb++;
					}
				}
			}
		}
		$sheet->setCellValue('I'.$ligne,$nb);
		$ligne++;
	}
}

$req="SELECT DISTINCT Id_CauseDelai, ";
$req.="(SELECT Libelle FROM trame_causedelais WHERE trame_causedelais.Id=trame_travaileffectue.Id_CauseDelai) AS CauseDelais ";
$req.="FROM trame_travaileffectue WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_CauseDelai<>0 AND ";
$req.=" DatePreparateur>='".$mois."' AND DatePreparateur<='".date("Y-m-d",strtotime($mois." +12 month"))."' ";
if($_SESSION['EXTRACT_WPQualite2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_WPQualite2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_WP=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$req.="ORDER BY CauseDelais ";
$resultR=mysqli_query($bdd,$req);
$nbResultaR=mysqli_num_rows($resultR);
if ($nbResultaT>0){
	$ligne=72;
	while($row=mysqli_fetch_array($resultR)){
		$sheet->setCellValue('I'.$ligne,$row['CauseDelais']);
		$nb=0;
		if ($nbResultaT>0){
			mysqli_data_seek($resultT,0);
			while($rowT=mysqli_fetch_array($resultT)){
				if($rowT['Id_CauseDelai']==$row['Id_CauseDelai'] && $rowT['Responsable']=="AAA"){
					$nb++;
				}
			}
		}
		$sheet->setCellValue('J'.$ligne,$nb);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="OTD.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->setIncludeCharts(TRUE);
$chemin = '../../tmp/OTD.xlsx';
$writer->save($chemin);
readfile($chemin);
?>