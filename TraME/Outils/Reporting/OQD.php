<?php
session_start();
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_OQD.xlsx');

$sheet = $excel->getSheetByName('Indicateurs');

$req="SELECT Libelle FROM trame_prestation WHERE Id=".$_SESSION['Id_PrestationTR'];
$resultP=mysqli_query($bdd,$req);
$nbResultaP=mysqli_num_rows($resultP);
$prestation="";
if($nbResultaP>0){
$rowP=mysqli_fetch_array($resultP);
$prestation=$rowP['Libelle'];
}
$sheet->setCellValue('D1','Site : '.utf8_encode($prestation));

//-----------------------------------Taux de conformité (OQD))--------------------------------------------------//
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
$title = new PHPExcel_Chart_Title(utf8_encode('Taux de conformité (OQD)'));


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

//-----------------------------------Nombre d’anomalies OQD--------------------------------------------------//
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$J$33', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$K$33', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$L$33', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$M$33', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$N$33', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$O$33', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Indicateurs!$H$34:$H$45', NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$J$34:$J$45', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$K$34:$K$45', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$L$34:$L$45', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$M$34:$M$45', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$N$34:$N$45', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Indicateurs!$O$34:$O$45', NULL, 4),
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
$title = new PHPExcel_Chart_Title(utf8_encode("Nombre d'anomalies OQD"));


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

//-----------------------------------Répartition des anomalies du mois--------------------------------------------------//
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
$title = new PHPExcel_Chart_Title(utf8_encode('Répartition des anomalies du mois'));



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
//-----------------------------------Récurrence anomalies OQD--------------------------------------------------//
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
$title = new PHPExcel_Chart_Title(utf8_encode('Récurrence anomalies OQD'));


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

$req="SELECT Id, DatePreparateur ";
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
$resultT12=mysqli_query($bdd,$req);
$nbResultaT12=mysqli_num_rows($resultT12);

$req="SELECT Id_FamilleErreur2,Id_FamilleErreur1,DateAnomalie ";
$req.="FROM trame_anomalie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND ";
$req.="DateAnomalie>='".$mois."' AND DateAnomalie<'".date("Y-m-d",strtotime($mois." +12 month"))."' ";
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
if($_SESSION['EXTRACT_Responsable2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_Responsable2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Responsable=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$resultA=mysqli_query($bdd,$req);
$nbResultaA=mysqli_num_rows($resultA);

$req="SELECT Id_FamilleErreur2,Id_FamilleErreur1,DateAnomalie ";
$req.="FROM trame_anomalie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND ";
$req.="DateAnomalie>='".$mois."' AND DateAnomalie<'".date("Y-m-d",strtotime($mois." +12 month"))."' ";
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
if($_SESSION['EXTRACT_Responsable2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_Responsable2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Responsable=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$resultT=mysqli_query($bdd,$req);

$nbResultaT=mysqli_num_rows($resultT);

$req="SELECT DISTINCT Id_FamilleErreur1 AS Id_Famille, ";
$req.="(SELECT Libelle FROM trame_familleerreur WHERE trame_familleerreur.Id=trame_anomalie.Id_FamilleErreur1) AS Famille ";
$req.="FROM trame_anomalie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_FamilleErreur1<>0 AND ";
$req.="DateAnomalie>='".$mois."' AND DateAnomalie<'".date("Y-m-d",strtotime($mois." +12 month"))."' ";
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
if($_SESSION['EXTRACT_Responsable2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_Responsable2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Responsable=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$req.=" UNION ";
$req.="SELECT DISTINCT Id_FamilleErreur2 AS Id_Famille, ";
$req.="(SELECT Libelle FROM trame_familleerreur WHERE trame_familleerreur.Id=trame_anomalie.Id_FamilleErreur2) AS Famille ";
$req.="FROM trame_anomalie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_FamilleErreur2<>0 AND ";
$req.="DateAnomalie>='".$mois."' AND DateAnomalie<'".date("Y-m-d",strtotime($mois." +12 month"))."' ";
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
if($_SESSION['EXTRACT_Responsable2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_Responsable2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Responsable=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$req.="ORDER BY Famille ";

$resultR=mysqli_query($bdd,$req);
$nbResultaR=mysqli_num_rows($resultR);
if ($nbResultaT>0){
	$ligne=49;
	while($row=mysqli_fetch_array($resultR)){
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['Famille']));
		$nb=0;
		if ($nbResultaT>0){
			mysqli_data_seek($resultT,0);
			while($rowT=mysqli_fetch_array($resultT)){
				if(date("m",strtotime($rowT['DateAnomalie']." +0 month"))==date("m",strtotime($mois." +11 month")) && date("Y",strtotime($rowT['DateAnomalie']." +0 month"))==date("Y",strtotime($mois." +11 month"))){
					if($rowT['Id_FamilleErreur1']==$row['Id_Famille'] || $rowT['Id_FamilleErreur2']==$row['Id_Famille']){
						$nb++;
					}
				}
			}
		}
		$sheet->setCellValue('I'.$ligne,$nb);
		$ligne++;
	}
}

$req="SELECT Id_FamilleErreur2,Id_FamilleErreur1,DateAnomalie ";
$req.="FROM trame_anomalie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND ";
$req.="DateAnomalie>='".$mois."' AND DateAnomalie<'".date("Y-m-d",strtotime($mois." +12 month"))."' ";
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
if($_SESSION['EXTRACT_Responsable2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_Responsable2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Responsable=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$resultT=mysqli_query($bdd,$req);
$nbResultaT=mysqli_num_rows($resultT);

$req="SELECT DISTINCT Id_FamilleErreur1 AS Id_Famille, ";
$req.="(SELECT Libelle FROM trame_familleerreur WHERE trame_familleerreur.Id=trame_anomalie.Id_FamilleErreur1) AS Famille ";
$req.="FROM trame_anomalie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_FamilleErreur1<>0 AND ";
$req.="DateAnomalie>='".$mois."' AND DateAnomalie<'".date("Y-m-d",strtotime($mois." +12 month"))."' ";
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
if($_SESSION['EXTRACT_Responsable2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_Responsable2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Responsable=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$req.=" UNION ";
$req.="SELECT DISTINCT Id_FamilleErreur2 AS Id_Famille, ";
$req.="(SELECT Libelle FROM trame_familleerreur WHERE trame_familleerreur.Id=trame_anomalie.Id_FamilleErreur2) AS Famille ";
$req.="FROM trame_anomalie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_FamilleErreur2<>0 AND ";
$req.="DateAnomalie>='".$mois."' AND DateAnomalie<'".date("Y-m-d",strtotime($mois." +12 month"))."' ";
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
if($_SESSION['EXTRACT_Responsable2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_Responsable2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Responsable=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$req.="ORDER BY Famille ";

$resultR=mysqli_query($bdd,$req);
$nbResultaR=mysqli_num_rows($resultR);
if ($nbResultaT>0){
	$ligne=72;
	$colonne="J";
	while($row=mysqli_fetch_array($resultR)){
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['Famille']));
		$sheet->setCellValue($colonne.'33',utf8_encode($row['Famille']));
		$nb=0;
		if ($nbResultaT>0){
			mysqli_data_seek($resultT,0);
			while($rowT=mysqli_fetch_array($resultT)){
				if($rowT['Id_FamilleErreur1']==$row['Id_Famille'] || $rowT['Id_FamilleErreur2']==$row['Id_Famille']){
					$nb++;
				}
			}
		}
		$sheet->setCellValue('J'.$ligne,$nb);
		$ligne++;
		$colonne++;
	}
}

for($ligne=8;$ligne<=19;$ligne++){
	$sheet->setCellValue('H'.$ligne,date("m/Y",strtotime($mois." +0 month")));
	$sheet->setCellValue('H'.($ligne+26),date("m/Y",strtotime($mois." +0 month")));
	$nbAnomalie=0;
	$nbLiv=0;
	if ($nbResultaA>0){
		mysqli_data_seek($resultA,0);
		while($rowT=mysqli_fetch_array($resultA)){
			if(date("m",strtotime($rowT['DateAnomalie']." +0 month"))==date("m",strtotime($mois." +0 month")) && date("Y",strtotime($rowT['DateAnomalie']." +0 month"))==date("Y",strtotime($mois." +0 month"))){
				$nbAnomalie++;
			}		
		}
	}
	if ($nbResultaT12>0){
		mysqli_data_seek($resultT12,0);
		while($rowT=mysqli_fetch_array($resultT12)){
			if(date("m",strtotime($rowT['DatePreparateur']." +0 month"))==date("m",strtotime($mois." +0 month")) && date("Y",strtotime($rowT['DatePreparateur']." +0 month"))==date("Y",strtotime($mois." +0 month"))){
				$nbLiv++;
			}
		}
	}
	$colonne="J";
	if ($nbResultaR>0){
		mysqli_data_seek($resultR,0);
		while($rowA=mysqli_fetch_array($resultR)){
			$nbFamille=0;
			if ($nbResultaA>0){
				mysqli_data_seek($resultA,0);
				while($rowT=mysqli_fetch_array($resultA)){
					if(date("m",strtotime($rowT['DateAnomalie']." +0 month"))==date("m",strtotime($mois." +0 month")) && date("Y",strtotime($rowT['DateAnomalie']." +0 month"))==date("Y",strtotime($mois." +0 month"))){
						if($rowT['Id_FamilleErreur1']==$rowA['Id_Famille'] || $rowT['Id_FamilleErreur2']==$rowA['Id_Famille']){
						$nbFamille++;
						}
					}		
				}
			}
			$sheet->setCellValue($colonne.($ligne+26),$nbFamille);
			$colonne++;
		}
	}
	if($nbLiv>0){
		$sheet->setCellValue('I'.$ligne,($nbLiv-$nbAnomalie)/$nbLiv);
	}
	else{
		$sheet->setCellValue('I'.$ligne,1);
	}
	$sheet->setCellValue('I'.($ligne+26),$nbAnomalie);
	$mois= date("Y-m-d",strtotime($mois." +1 month"));
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="OQD.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->setIncludeCharts(TRUE);
$chemin = '../../tmp/OQD.xlsx';
$writer->save($chemin);
readfile($chemin);
?>