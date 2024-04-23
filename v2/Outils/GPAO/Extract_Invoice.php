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
$sheet->setCellValue('A1',utf8_encode('Imputation'));
$sheet->setCellValue('B1',utf8_encode('MSN'));
$sheet->setCellValue('C1',utf8_encode('Type'));
$sheet->setCellValue('D1',utf8_encode('AM'));
$sheet->setCellValue('E1',utf8_encode('OF/OT'));
$sheet->setCellValue('F1',utf8_encode('Para'));
$sheet->setCellValue('G1',utf8_encode('NC'));
$sheet->setCellValue('H1',utf8_encode('QLB'));
$sheet->setCellValue('I1',utf8_encode('TLB'));
$sheet->setCellValue('J1',utf8_encode('Concession'));
$sheet->setCellValue('K1',utf8_encode('Designation'));
$sheet->setCellValue('L1',utf8_encode('Production hours'));
$sheet->setCellValue('M1',utf8_encode('Quality hours'));
$sheet->setCellValue('N1',utf8_encode('Closure date'));
$sheet->setCellValue('O1',utf8_encode('Status'));
$sheet->setCellValue('P1',utf8_encode('Comments'));

$sheet->getStyle('A1:P1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT 
(SELECT Libelle FROM gpao_imputation WHERE Id=Id_Imputation) AS Imputation,
AM,OF,NC,QLB,TLB,Concession,Para,Designation,
(SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
(SELECT (SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Type,
(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
(SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) AS LastStatus,
Comments,
(SELECT SUM(ProductiveTime+IdleTime)
FROM gpao_productionsheet 
WHERE gpao_productionsheet.Suppr=0 
AND gpao_productionsheet.Id_WO=gpao_wo.Id) AS ProductionHours,
(SELECT SUM(TimeUsed)
FROM gpao_statutquality 
WHERE gpao_statutquality.Suppr=0 
AND gpao_statutquality.Id_WO=gpao_wo.Id) AS QualityHours,
ClosureDate
FROM gpao_wo
WHERE gpao_wo.Suppr=0
AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
AND Id_Customer=".$_GET['Customer']." 
AND Invoiced=0 
AND ClosureDate>'0001-01-01' 
AND ClosureDate<'".TrsfDate_($_GET['EndDate'])."' ";

$resultRapport=mysqli_query($bdd,$req);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Imputation'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['MSN'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Type'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['AM'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['OF'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Para'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['NC'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['QLB'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['TLB'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['Concession'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['Designation'])));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($row['ProductionHours'])));
		$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes($row['QualityHours'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['ClosureDate'])));
		$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($row['LastStatus'])));
		$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($row['Comments'])));

		$sheet->getStyle('A'.$ligne.':P'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//UPDATE INVOICED 
$req="UPDATE gpao_wo
SET Invoiced=1, DateInvoiced='".date('Y-m-d')."'
WHERE gpao_wo.Suppr=0
AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
AND Id_Customer=".$_GET['Customer']." 
AND Invoiced=0 
AND ClosureDate>'0001-01-01' 
AND ClosureDate<'".TrsfDate_($_GET['EndDate'])."' ";
$resultUpdt=mysqli_query($bdd,$req);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Invoice.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_Invoice.xlsx';
$writer->save($chemin);
readfile($chemin);
?>