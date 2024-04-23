<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
function TrsfDate_($Date)
{
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('/', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
	}
	
	return $dateReq;
}
function AfficheDateFR($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("d/m/Y", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

$mois_11= date("Y-m-d",strtotime(date(substr($_SESSION['EXTRACT_Mois'],3)."-".substr($_SESSION['EXTRACT_Mois'],0,2)."-1")." -11 month"));
$mois_3= date("Y-m-d",strtotime(date(substr($_SESSION['EXTRACT_Mois'],3)."-".substr($_SESSION['EXTRACT_Mois'],0,2)."-1")." -3 month"));
$mois= date("Y-m-d",strtotime(date(substr($_SESSION['EXTRACT_Mois'],3)."-".substr($_SESSION['EXTRACT_Mois'],0,2)."-1")." +1 month"));
$dateFin= date("Y-m-d",strtotime(date(substr($_SESSION['EXTRACT_Mois'],3)."-".substr($_SESSION['EXTRACT_Mois'],0,2)."-1")." +1 month -1 day"));
$moisEC = date("n",strtotime(date(substr($_SESSION['EXTRACT_Mois'],3)."-".substr($_SESSION['EXTRACT_Mois'],0,2)."-1")." +0 month"));
//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Indicateurs EISA.xlsx');

//OQD 1 & OQD2
$sheet = $excel->getSheetByName('OQD1');
$sheet2 = $excel->getSheetByName('OQD2');

$sheet->setCellValue('A1',utf8_encode("AM par avion et par origine de détection du ".AfficheDateFR($mois_3)." au ".AfficheDateFR($dateFin)));
$sheet2->setCellValue('A1',utf8_encode("AM d’imputation USAAA par avion du ".AfficheDateFR($mois_3)." au ".AfficheDateFR($dateFin)));
$req="SELECT DISTINCT MSN FROM sp_atram WHERE Id_Prestation=463 ORDER BY MSN";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$req2="SELECT MSN, OrigineAM, ";
$req2.="(SELECT Libelle FROM sp_atrmomentdetection WHERE sp_atrmomentdetection.Id=sp_atram.Id_MomentDetection) AS MomentDetection, ";
$req2.="(SELECT Libelle FROM sp_atrimputation WHERE sp_atrimputation.Id=sp_atram.Id_Imputation) AS Imputation ";
$req2.="FROM sp_atram WHERE Id_Prestation=463 ";
$req2.="AND DateCreation>='".$mois_3."' AND DateCreation<'".$mois."' ";
$result2=mysqli_query($bdd,$req2);
$nbResulta2=mysqli_num_rows($result2);
if($nbResulta>0){
	$ligne=29;
	while($rowMSN=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($rowMSN['MSN']));
		$sheet2->setCellValue('A'.$ligne,utf8_encode($rowMSN['MSN']));
		$qteIncomingDPP=0;
		$qteIncomingMAT=0;
		$qteMontage=0;
		$qteP17=0;
		$qteJeux=0;
		$qteAAAMontage=0;
		$qteAAAP17=0;
		if($nbResulta2>0){
			mysqli_data_seek($result2,0);
			while($rowAM=mysqli_fetch_array($result2)){
				if($rowAM['MSN']==$rowMSN['MSN']){
					if($rowAM['OrigineAM']=="Poste engine"){
						if($rowAM['MomentDetection']=="Incoming"){
							if($rowAM['Imputation']=="USROH" || $rowAM['Imputation']=="USAAA" || $rowAM['Imputation']=="USRCL" ||
							$rowAM['Imputation']=="USPW" || $rowAM['Imputation']=="USCFM" || $rowAM['Imputation']=="USIAE" || $rowAM['Imputation']=="USSM-P17"){
								$qteIncomingDPP++;
							}
							elseif($rowAM['Imputation']=="USSE"){
								$qteIncomingMAT++;
							}
						}
						elseif($rowAM['MomentDetection']=="Montage"){
							$qteMontage++;
							if($rowAM['Imputation']=="USAAA"){
								$qteAAAMontage++;
							}
						}
					}
					elseif($rowAM['OrigineAM']=="P17"){
						$qteP17++;
						if($rowAM['Imputation']=="USAAA"){
							$qteAAAP17++;
						}
					}
					if($rowAM['MomentDetection']=="Jeux"){
						$qteJeux++;
					}
				}
			}
		}
		$sheet->setCellValue('B'.$ligne,utf8_encode($qteIncomingDPP));
		$sheet->setCellValue('C'.$ligne,utf8_encode($qteIncomingMAT));
		$sheet->setCellValue('D'.$ligne,utf8_encode($qteMontage));
		$sheet->setCellValue('E'.$ligne,utf8_encode($qteP17));
		$sheet->setCellValue('F'.$ligne,utf8_encode($qteJeux));
		
		$sheet2->setCellValue('B'.$ligne,utf8_encode($qteAAAMontage));
		$sheet2->setCellValue('C'.$ligne,utf8_encode($qteAAAP17));
		$ligne++;
	}
}
//Graphique OQD1
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD1!$B$28', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD1!$C$28', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD1!$D$28', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD1!$E$28', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD1!$F$28', NULL, 1),
);

$laLigne=29;
if($ligne>29){$laLigne=$ligne-1;}
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD1!$A$29:$A$'.$laLigne, NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD1!$B$29:$B$'.$laLigne, NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD1!$C$29:$C$'.$laLigne, NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD1!$D$29:$D$'.$laLigne, NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD1!$E$29:$E$'.$laLigne, NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD1!$F$29:$F$'.$laLigne, NULL, 4),
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
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);

//	Set the series in the plot area
$layout = new PHPExcel_Chart_Layout();
$layout->setShowVal(false);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode("AM per A/C and per origin of detection \n Originated by all"));

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
$chart->setTopLeftPosition('A12');
$chart->setBottomRightPosition('O26');

//	Add the chart to the worksheet
$sheet->addChart($chart);

//Graphique OQD2
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD2!$B$28', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD2!$C$28', NULL, 1),
);

$laLigne=29;
if($ligne>29){$laLigne=$ligne-1;}
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD2!$A$29:$A$'.$laLigne, NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD2!$B$29:$B$'.$laLigne, NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD2!$C$29:$C$'.$laLigne, NULL, 4),
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
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);

//	Set the series in the plot area
$layout = new PHPExcel_Chart_Layout();
$layout->setShowVal(false);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode("AAA AM per A/C and per origin of detection \n Originated by AAA"));

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
$chart->setTopLeftPosition('A9');
$chart->setBottomRightPosition('O26');

//	Add the chart to the worksheet
$sheet2->addChart($chart);

//OQD3
$sheet2 = $excel->getSheetByName('OQD3');
$Liste_mois=array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
$dateJ=$mois_11;
$ligne=29;
while($dateJ<$mois){
	
	$sheet2->setCellValue('A'.$ligne,utf8_encode($Liste_mois[date("m",strtotime($dateJ." +0 month"))-1]." ".date("Y",strtotime($dateJ." +0 month"))));
	$dateJ=date("Y-m-d",strtotime($dateJ." +1 month"));
	$ligne++;
}

//Graphique OQD3
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD3!$B$28', NULL, 1),
);

$laLigne=29;
if($ligne>29){$laLigne=$ligne-1;}
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD3!$A$29:$A$40', NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD3!$B$29:$B$40', NULL, 4),
);

//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_LINECHART,		// plotType
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
$layout->setShowVal(true);
//$layout->setShowLegendKey(true);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode("Moyenne d'AM/Avion hors P17"));

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
$chart->setTopLeftPosition('A11');
$chart->setBottomRightPosition('N26');

//	Add the chart to the worksheet
$sheet2->addChart($chart);

//OQD4
$sheet = $excel->getSheetByName('OQD4');
$sheet7 = $excel->getSheetByName('OQD7');
$dateJ=$mois_11;
$ligne=29;
$col="B";
while($dateJ<$mois){
	
	$sheet->setCellValue('A'.$ligne,utf8_encode($Liste_mois[date("m",strtotime($dateJ." +0 month"))-1]." ".date("Y",strtotime($dateJ." +0 month"))));
	$sheet7->setCellValue($col.'3',utf8_encode($Liste_mois[date("m",strtotime($dateJ." +0 month"))-1]." ".date("Y",strtotime($dateJ." +0 month"))));
	$dateJ=date("Y-m-d",strtotime($dateJ." +1 month"));
	$ligne++;
	$col++;
}

$req2="SELECT DateCreation,MSN, OrigineAM, ";
$req2.="(SELECT Libelle FROM sp_atrmomentdetection WHERE sp_atrmomentdetection.Id=sp_atram.Id_MomentDetection) AS MomentDetection, ";
$req2.="(SELECT Libelle FROM sp_atrimputation WHERE sp_atrimputation.Id=sp_atram.Id_Imputation) AS Imputation ";
$req2.="FROM sp_atram WHERE Id_Prestation=463 AND DateCreation>='".$mois_11."' AND DateCreation<'".$mois."' ";
$result2=mysqli_query($bdd,$req2);
$nbResulta2=mysqli_num_rows($result2);
$qteIncomingDPPec=0;
$qteIncomingMATec=0;
$qteMontageec=0;
$qteP17ec=0;
$qteJeuxec=0;
$col='B';
$ligne=29;
$dateJ=$mois_11;
while($dateJ<$mois){

	$qteIncomingDPP=0;
	$qteIncomingMAT=0;
	$qteMontage=0;
	$qteP17=0;
	$qteJeux=0;
	$qteAAAMontage=0;
	$qteAAAP17=0;
	$qteAAAP17=0;
	$qteP17=0;
	
	$reqMSN="SELECT DISTINCT MSN FROM sp_atram WHERE Id_Prestation=463 AND YEAR(DateCreation)=".date("Y",strtotime($dateJ." +0 month"))." AND MONTH(DateCreation)=".date("m",strtotime($dateJ." +0 month"));
	$resultMSN=mysqli_query($bdd,$reqMSN);
	$nbResultaMSN=mysqli_num_rows($resultMSN);
	
	if($nbResulta2>0){
		mysqli_data_seek($result2,0);
		while($rowAM=mysqli_fetch_array($result2)){
			$date = date_parse($rowAM['DateCreation']);
			if($date['year']==date("Y",strtotime($dateJ." +0 month")) && $date['month']==date("m",strtotime($dateJ." +0 month"))){
				if($rowAM['OrigineAM']=="Poste engine"){
					if($rowAM['MomentDetection']=="Incoming"){
						if($rowAM['Imputation']=="USROH" || $rowAM['Imputation']=="USAAA" || $rowAM['Imputation']=="USRCL" ||
						$rowAM['Imputation']=="USPW" || $rowAM['Imputation']=="USCFM" || $rowAM['Imputation']=="USIAE" || $rowAM['Imputation']=="USSM-P17"){
							$qteIncomingDPP++;
							if($date['year']==date("Y",strtotime($dateJ." +0 month")) && $moisEC==date("m",strtotime($dateJ." +0 month"))){
								$qteIncomingDPPec++;
							}
						}
						elseif($rowAM['Imputation']=="USSE"){
							$qteIncomingMAT++;
							if($date['year']==date("Y",strtotime($dateJ." +0 month")) && $moisEC==date("m",strtotime($dateJ." +0 month"))){
								$qteIncomingMATec++;
							}
						}
					}
					elseif($rowAM['MomentDetection']=="Montage"){
						$qteMontage++;
						if($date['year']==date("Y",strtotime($dateJ." +0 month")) && $moisEC==date("m",strtotime($dateJ." +0 month"))){
							$qteMontageec++;
						}
						if($rowAM['Imputation']=="USAAA"){
							$qteAAAMontage++;
						}
					}
				}
				elseif($rowAM['OrigineAM']=="P17"){
					$qteP17++;
					if($date['year']==date("Y",strtotime($dateJ." +0 month")) && $moisEC==date("m",strtotime($dateJ." +0 month"))){
						$qteP17ec++;
					}
					if($rowAM['Imputation']=="USAAA"){
						$qteAAAP17++;
					}
				}
				if($rowAM['MomentDetection']=="Jeux"){
					$qteJeux++;
					if($date['year']==date("Y",strtotime($dateJ." +0 month")) && $moisEC==date("m",strtotime($dateJ." +0 month"))){
						$qteJeuxec++;
					}
				}
			}
		}
	}
	if($nbResultaMSN>0){
		$sheet2->setCellValue('B'.$ligne,utf8_encode(round(($qteIncomingDPP+$qteIncomingMAT+$qteMontage+$qteJeux)/$nbResultaMSN,2)));
	}

	$sheet->setCellValue('B'.$ligne,utf8_encode($qteP17));
	$sheet->setCellValue('C'.$ligne,utf8_encode($qteAAAP17));
	
	$total=$qteIncomingDPP+$qteIncomingMAT+$qteMontage+$qteP17+$qteJeux;
	if($total>0){
		$qteIncomingDPP=round($qteIncomingDPP/$total,2);
		$qteIncomingMAT=round($qteIncomingMAT/$total,2);
		$qteMontage=round($qteMontage/$total,2);
		$qteP17=round($qteP17/$total,2);
		$qteJeux=round($qteJeux/$total,2);
	}
	$sheet7->setCellValue($col.'4',utf8_encode($qteIncomingDPP+$qteIncomingMAT));
	$sheet7->setCellValue($col.'5',utf8_encode($qteMontage+$qteP17+$qteJeux));
	$dateJ=date("Y-m-d",strtotime($dateJ." +1 month"));
	$ligne++;
	$col++;
}

//Graphique OQD4
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD4!$B$28', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD4!$C$28', NULL, 1),
);

$laLigne=29;
if($ligne>29){$laLigne=$ligne-1;}
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD4!$A$29:$A$40', NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD4!$B$29:$B$40', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD4!$C$29:$C$40', NULL, 4),
);

//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_LINECHART,		// plotType
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
$layout->setShowVal(true);
//$layout->setShowLegendKey(true);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode("Evolution du nombre d'AM P17"));

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
$chart->setTopLeftPosition('A9');
$chart->setBottomRightPosition('N26');

//	Add the chart to the worksheet
$sheet->addChart($chart);

//OQD5
$sheet5 = $excel->getSheetByName('OQD5');
$dateJ=$mois_11;
$ligne=29;
while($dateJ<$mois){
	$sheet5->setCellValue('A'.$ligne,utf8_encode($Liste_mois[date("m",strtotime($dateJ." +0 month"))-1]." ".date("Y",strtotime($dateJ." +0 month"))));
	$dateJ=date("Y-m-d",strtotime($dateJ." +1 month"));
	$ligne++;
}

$req2="SELECT DateMontage,TypeMoteur ";
$req2.="FROM sp_atrmoteur WHERE DateMontage>='".$mois_11."' AND DateMontage<'".$mois."' ";
$result2=mysqli_query($bdd,$req2);
$nbResulta2=mysqli_num_rows($result2);
$col='B';
$ligne=29;
$dateJ=$mois_11;
while($dateJ<$mois){
	$qtePW_LEAP=0;
	$qteIAE_CFM=0;
	
	if($nbResulta2>0){
		mysqli_data_seek($result2,0);
		while($rowType=mysqli_fetch_array($result2)){
			$date = date_parse($rowType['DateMontage']);
			if($date['year']==date("Y",strtotime($dateJ." +0 month")) && $date['month']==date("m",strtotime($dateJ." +0 month"))){
				if($rowType['TypeMoteur']=="PW" || $rowType['TypeMoteur']=="LEAP"){
					$qtePW_LEAP++;

				}
				elseif($rowType['TypeMoteur']=="IAE" || $rowType['TypeMoteur']=="CFM" || $rowType['TypeMoteur']=="CFMI"){
					$qteIAE_CFM++;

				}
			}
		}
	}

	$sheet5->setCellValue('B'.$ligne,utf8_encode($qtePW_LEAP));
	$sheet5->setCellValue('C'.$ligne,utf8_encode($qteIAE_CFM));
	$dateJ=date("Y-m-d",strtotime($dateJ." +1 month"));
	$ligne++;
}

//Graphique OQD5
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD5!$B$28', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD5!$C$28', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD5!$A$29:$A$40', NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD5!$B$29:$B$40', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD5!$C$29:$C$40', NULL, 4),
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
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);

//	Set the series in the plot area
$layout = new PHPExcel_Chart_Layout();
$layout->setShowVal(true);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode("Qté de moteurs livrés par type (NEO/CEO)"));

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
$chart->setTopLeftPosition('A4');
$chart->setBottomRightPosition('O21');

//	Add the chart to the worksheet
$sheet5->addChart($chart);

//OQD6
$sheet = $excel->getSheetByName('OQD6');
$sheet->setCellValue('B4',utf8_encode("Origine des AM ".$Liste_mois[$moisEC-1]));

$total=$qteIncomingDPPec+$qteIncomingMATec+$qteMontageec+$qteP17ec+$qteJeuxec;
if($total>0){
	$qteIncomingDPPec=round($qteIncomingDPPec/$total,2);
	$qteIncomingMATec=round($qteIncomingMATec/$total,2);
	$qteMontageec=round($qteMontageec/$total,2);
	$qteP17ec=round($qteP17ec/$total,2);
	$qteJeuxec=round($qteJeuxec/$total,2);
	
}
$sheet->setCellValue('C5',utf8_encode($qteIncomingDPPec));
$sheet->setCellValue('C6',utf8_encode($qteIncomingMATec));
$sheet->setCellValue('C7',utf8_encode($qteMontageec));
$sheet->setCellValue('C8',utf8_encode($qteP17ec));
$sheet->setCellValue('C9',utf8_encode($qteJeuxec));

$sheet->setCellValue('C10',utf8_encode($qteIncomingDPPec+$qteIncomingMATec));
$sheet->setCellValue('C11',utf8_encode($qteMontageec+$qteP17ec+$qteJeuxec));

//Graphique OQD6 1
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD6!$B$4', NULL, 1),
);
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD6!$B$5:$B$9', NULL, 3),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD6!$C$5:$C$9', NULL, 3),
);


//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_PIECHART,		// plotType
	null,	// plotGrouping
	range(0, count($dataSeriesValues)-1),			// plotOrder
	null,											// plotLabel
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
$title = new PHPExcel_Chart_Title(utf8_encode('Origine des AM '.$mois[$moisEC-1]));

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
$chart->setTopLeftPosition('B13');
$chart->setBottomRightPosition('H29');

//	Add the chart to the worksheet
$sheet->addChart($chart);

//Graphique OQD6 2
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD6!$B$4', NULL, 1),
);

$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD6!$B$10:$B$11', NULL, 3),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD6!$C$10:$C$11', NULL, 3),
);

//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_DOUGHTNUTCHART,		// plotType
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
$title = new PHPExcel_Chart_Title(utf8_encode('Ratio AM Incoming DPP_MAT / AM Montage_P17_Jeux '.$mois[$moisEC-1]));

//	Create the chart
$chart = new PHPExcel_Chart(
	'chart2',		// name
	$title,			// title
	$legend,		// legend
	$plotarea,		// plotArea
	true,			// plotVisibleOnly
	0,				// displayBlanksAs
	NULL,			// xAxisLabel
	NULL		// yAxisLabel
);

//	Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('I13');
$chart->setBottomRightPosition('P29');

//	Add the chart to the worksheet
$sheet->addChart($chart);

//Graphique OQD7
$dataseriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD7!$A$4', NULL, 1),
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD7!$A$5', NULL, 1),
);

$laLigne=29;
if($ligne>29){$laLigne=$ligne-1;}
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'OQD7!$B$3:$M$3', NULL, 4),
);

$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD7!$B$4:$M$4', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'OQD7!$B$5:$M$5', NULL, 4),
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
$layout->setShowVal(true);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$title = new PHPExcel_Chart_Title(utf8_encode("Historique annuel AM Montage / Incoming"));

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
$chart->setBottomRightPosition('M24');

//	Add the chart to the worksheet
$sheet7->addChart($chart);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="OQD.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->setIncludeCharts(TRUE);
$chemin = '../../../tmp/OQD.xlsx';
$writer->save($chemin);
readfile($chemin);
?>