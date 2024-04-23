<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
$sheet->setCellValue('A1',utf8_encode('Customer'));
$sheet->setCellValue('B1',utf8_encode('Designation'));
$sheet->setCellValue('C1',utf8_encode('Plan date'));
$sheet->setCellValue('D1',utf8_encode('limitdate (FOT/Test)'));
$sheet->setCellValue('E1',utf8_encode('Target time'));
$sheet->setCellValue('F1',utf8_encode('Creation Date'));
$sheet->setCellValue('G1',utf8_encode('Launch Date'));
$sheet->setCellValue('H1',utf8_encode('Closure Date'));
$sheet->setCellValue('I1',utf8_encode('Week'));
$sheet->setCellValue('J1',utf8_encode('OTD'));
$sheet->setCellValue('K1',utf8_encode('Type'));
$sheet->setCellValue('L1',utf8_encode('Production hours'));
$sheet->setCellValue('M1',utf8_encode('Quality hours'));
$sheet->setCellValue('N1',utf8_encode('CostCenter'));

$sheet->getStyle('A1:N1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT 
(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
Designation,PlanDate,LimitDateFOT,TargetTime,CreationDate,LaunchDate,ClosureDate,
IF(ClosureDate>'0001-01-01',
CONCAT(YEAR(ClosureDate),'_',IF(WEEK(ClosureDate,1)<10,CONCAT(0,WEEK(ClosureDate,1)),WEEK(ClosureDate,1))),
IF(LaunchDate>'0001-01-01',
CONCAT(YEAR(LaunchDate),'_',IF(WEEK(LaunchDate,1)<10,CONCAT(0,WEEK(LaunchDate,1)),WEEK(LaunchDate,1))),'')
) AS Week,
IF(OTD=1,'YES','NO') AS OTD,
(SELECT (SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Type,
AM,OF,NC,QLB,TLB,Concession,Para,
(SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
(SELECT Position FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Position,
(SELECT SUM(ProductiveTime+IdleTime)
FROM gpao_productionsheet 
WHERE gpao_productionsheet.Suppr=0 
AND gpao_productionsheet.Id_WO=gpao_wo.Id) AS ProductionHours,
(SELECT SUM(TimeUsed)
FROM gpao_statutquality 
WHERE gpao_statutquality.Suppr=0 
AND gpao_statutquality.Id_WO=gpao_wo.Id) AS QualityHours,
(SELECT Libelle FROM gpao_costcenter WHERE Id=Id_CostCenter) AS CostCenter
FROM gpao_wo
WHERE Suppr=0 
AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";

$resultRapport=mysqli_query($bdd,$req);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Customer'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Designation'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['PlanDate'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['LimitDateFOT'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['TargetTime']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['CreationDate']));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['LaunchDate'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['ClosureDate'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['Week']));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['OTD'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['Type'])));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($row['ProductionHours'])));
		$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes($row['QualityHours'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode(stripslashes($row['CostCenter'])));

		
		$sheet->getStyle('A'.$ligne.':N'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_KPI.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_KPI.xlsx';
$writer->save($chemin);
readfile($chemin);
?>