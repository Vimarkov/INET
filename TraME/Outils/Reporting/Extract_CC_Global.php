<?php
session_start();
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
if($_SESSION['Langue']=="EN"){
	$excel = $workbook->load('INDIC_Global_EN.xlsx');
}
else{
	$excel = $workbook->load('INDIC_Global.xlsx');
}

$sheet = $excel->getSheetByName('Indicateurs');
$sheetDATA = $excel->getSheetByName('DATA');

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

$req="SELECT DateControle
FROM trame_controlecroise
WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." 
AND DateControle>='".$date_11."' AND DateControle<='".date("Y-m-d",strtotime($date_11." +12 month"))."'  
";
$resultCC=mysqli_query($bdd,$req);
$nbResultaCC=mysqli_num_rows($resultCC);

$req="SELECT DateControle
FROM trame_controlecroise
WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." 
AND DateControle>='".$date_11."' AND DateControle<='".date("Y-m-d",strtotime($date_11." +12 month"))."' 
AND (SELECT COUNT(trame_controlecroise_contenu.Id) 
	FROM trame_controlecroise_contenu 
	WHERE Id_CC=trame_controlecroise.Id
	AND ValeurControle='KO')>0
";

$resultCCNOK=mysqli_query($bdd,$req);
$nbResultaCCNOK=mysqli_num_rows($resultCCNOK);



$col="B";
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
	
	$sheetDATA->setCellValue($col."2",$nbCC);
	$sheetDATA->setCellValue($col."3",$nbCCNOK);
	$sheetDATA->setCellValue($col."5",$objectifFTR/100);

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


$date_11= date("Y-m-d",strtotime(date(substr($_SESSION['EXTRACT_MoisQualite'],3)."-".substr($_SESSION['EXTRACT_MoisQualite'],0,2)."-1")." -11 month"));	

//Liste des Checklist
$req="SELECT COUNT(trame_controlecroise.Id) AS Nb, 
trame_cl_version.Id_CL,
(SELECT Libelle FROM trame_checklist WHERE trame_checklist.Id=trame_cl_version.Id_CL) AS CheckList
FROM trame_controlecroise 
LEFT JOIN trame_cl_version
ON trame_controlecroise.Id_CLVersion=trame_cl_version.Id
WHERE trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
AND YEAR(trame_controlecroise.DateControle)=".$annee."
AND MONTH(trame_controlecroise.DateControle)=".$mois."
GROUP BY Id_CL
ORDER BY CheckList 
";
$tabCL=array();
$resultCL=mysqli_query($bdd,$req);
$nbResultaCL=mysqli_num_rows($resultCL);
if($nbResultaCL>0){
	$ligne=10;
	while($rowCL=mysqli_fetch_array($resultCL)){
		$sheetDATA->setCellValue('B'.$ligne,$rowCL['Nb']);
		$sheetDATA->setCellValue('D'.$ligne,$rowCL['CheckList']);
		$sheetDATA->setCellValue('F'.$ligne,$objectifFTR/100);
		$tabCL[]=$rowCL['Id_CL'];
		$ligne++;
	}
}
$laLigne=$ligne;
if($laLigne>10){$laLigne--;}
$req="SELECT COUNT(trame_controlecroise.Id) AS Nb, 
trame_cl_version.Id_CL,
(SELECT Libelle FROM trame_checklist WHERE trame_checklist.Id=trame_cl_version.Id_CL) AS CheckList
FROM trame_controlecroise 
LEFT JOIN trame_cl_version
ON trame_controlecroise.Id_CLVersion=trame_cl_version.Id
WHERE trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
AND YEAR(trame_controlecroise.DateControle)=".$annee."
AND MONTH(trame_controlecroise.DateControle)=".$mois."

AND (SELECT COUNT(trame_controlecroise_contenu.Id) 
	FROM trame_controlecroise_contenu 
	WHERE Id_CC=trame_controlecroise.Id
	AND ValeurControle='KO')>0
GROUP BY Id_CL
ORDER BY CheckList ";

$resultCL=mysqli_query($bdd,$req);
$nbResultaCL=mysqli_num_rows($resultCL);
if($nbResultaCL>0){
	while($rowCL=mysqli_fetch_array($resultCL)){
		$ligne=10;
		$i=0;
		foreach($tabCL as $IdCL){
			if($rowCL['Id_CL']==$IdCL){$ligne=$ligne+$i;}
			$i++;
		}
		$sheetDATA->setCellValue('C'.$ligne,$rowCL['Nb']);
		$ligne++;
	}
}


//-------------------------------------------------------------------------------------//
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$E$9', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$D$10:$D$'.$laLigne, NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$E$10:$E$'.$laLigne, NULL, 4),
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
	new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$F$9', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$D$10:$D$'.$laLigne, NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$F$10:$F$'.$laLigne, NULL, 4,array(),'none'),
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
$title = new PHPExcel_Chart_Title(utf8_encode('FTR / Checklist ('.$mois.'/'.$annee.')'));


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
$chart->setBottomRightPosition('G50');

//	Add the chart to the worksheet
$sheet->addChart($chart);

//TOP 5 
$req="SELECT COUNT(trame_controlecroise_contenu.Id) AS Nb, 
(SELECT Id FROM trame_cl_version_contenu WHERE trame_cl_version_contenu.Id=trame_controlecroise_contenu.Id_Contenu) AS Id_CLVersionContenu,
(SELECT Chapitre FROM trame_cl_version_contenu WHERE trame_cl_version_contenu.Id=trame_controlecroise_contenu.Id_Contenu) AS Chapitre,
(SELECT Ponderation FROM trame_cl_version_contenu WHERE trame_cl_version_contenu.Id=trame_controlecroise_contenu.Id_Contenu) AS Ponderation,
(SELECT Controle FROM trame_cl_version_contenu WHERE trame_cl_version_contenu.Id=trame_controlecroise_contenu.Id_Contenu) AS Controle,
(SELECT 
	(SELECT (SELECT Libelle FROM trame_checklist WHERE trame_checklist.Id=trame_cl_version.Id_CL) FROM trame_cl_version WHERE trame_cl_version.Id=trame_cl_version_contenu.Id_VersionCL) 
FROM trame_cl_version_contenu 
WHERE trame_cl_version_contenu.Id=trame_controlecroise_contenu.Id_Contenu
) AS CheckList
FROM trame_controlecroise_contenu
LEFT JOIN trame_controlecroise 
ON trame_controlecroise.Id=trame_controlecroise_contenu.Id_CC
WHERE trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
AND DateControle>='".$date_11."' AND DateControle<='".date("Y-m-d",strtotime($date_11." +12 month"))."' 
AND trame_controlecroise_contenu.ValeurControle='KO'
GROUP BY Id_CLVersionContenu 
ORDER BY Nb DESC 
";
$resultNOK=mysqli_query($bdd,$req);
$nbResultaNOK=mysqli_num_rows($resultNOK);
if($nbResultaNOK>0){
	$ligne=62;
	while($rowNOK=mysqli_fetch_array($resultNOK)){
		if($ligne<=66){
			$sheet->setCellValue('A'.$ligne,utf8_encode($rowNOK['Chapitre']));
			$sheet->setCellValue('B'.$ligne,utf8_encode($rowNOK['Controle']));
			$sheet->setCellValue('D'.$ligne,utf8_encode($rowNOK['Ponderation']));
			$sheet->setCellValue('E'.$ligne,utf8_encode($rowNOK['CheckList']));
			$sheet->setCellValue('F'.$ligne,$rowNOK['Nb']);
		}
		$ligne++;
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