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
$sheet->setCellValue('B1',utf8_encode('MSN'));
$sheet->setCellValue('C1',utf8_encode('Destination'));
$sheet->setCellValue('D1',utf8_encode('Position'));
$sheet->setCellValue('E1',utf8_encode('NC'));
$sheet->setCellValue('F1',utf8_encode('QLB'));
$sheet->setCellValue('G1',utf8_encode('TLB'));
$sheet->setCellValue('H1',utf8_encode('Designation'));
$sheet->setCellValue('I1',utf8_encode('Priority'));
$sheet->setCellValue('J1',utf8_encode('Plan date'));
$sheet->setCellValue('K1',utf8_encode('Working Shift'));
$sheet->setCellValue('L1',utf8_encode('Limit date'));
$sheet->setCellValue('M1',utf8_encode('Status'));
$sheet->setCellValue('N1',utf8_encode('Status date'));
$sheet->setCellValue('O1',utf8_encode('Status comments'));
$sheet->setCellValue('P1',utf8_encode('Target time'));
$sheet->setCellValue('Q1',utf8_encode('Working progress'));
$sheet->setCellValue('R1',utf8_encode('Type'));

$sheet->getStyle('A1:R1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT 
(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
Designation,PlanDate,LimitDateFOT,TargetTime,CreationDate,LaunchDate,ClosureDate,WorkingProgress,
(SELECT (SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Type,
(SELECT (SELECT Libelle FROM gpao_aircraftdestination WHERE Id=Id_AircraftDestination) FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Destination,
AM,OF,NC,QLB,TLB,Concession,Para,
(SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
(SELECT Position FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Position,
(SELECT Libelle FROM gpao_priority WHERE Id=Id_Priority) AS Priority,
(SELECT Libelle FROM gpao_workingshifts WHERE Id=Id_WorkingShift) AS WorkingShift,
(SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) AS LastStatus,
(SELECT DateStatut FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) AS LastStatusDate,
(SELECT StatusComments FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) AS StatusComment
FROM gpao_wo
WHERE Suppr=0 
AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
AND ClosureDate<='0001-01-01'
AND Invoiced=0 ";

$resultRapport=mysqli_query($bdd,$req);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Customer'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['MSN'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Destination'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Position'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['NC'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['QLB'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['TLB'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['Designation'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['Priority'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['PlanDate'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['WorkingShift'])));
		$sheet->setCellValue('L'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['LimitDateFOT'])));
		$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes($row['LastStatus'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row['LastStatusDate']));
		$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($row['StatusComment'])));
		$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($row['TargetTime'])));
		$sheet->setCellValue('Q'.$ligne,utf8_encode(stripslashes($row['WorkingProgress'])));
		$sheet->setCellValue('R'.$ligne,utf8_encode(stripslashes($row['Type'])));
		
		$sheet->getStyle('A'.$ligne.':R'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_ProductionPriosOverview.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_ProductionPriosOverview.xlsx';
$writer->save($chemin);
readfile($chemin);
?>