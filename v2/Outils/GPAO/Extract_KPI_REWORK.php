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
$sheet->setCellValue('B1',utf8_encode('Date'));
$sheet->setCellValue('C1',utf8_encode('NC'));
$sheet->setCellValue('D1',utf8_encode('OF/OT'));
$sheet->setCellValue('E1',utf8_encode('AM'));
$sheet->setCellValue('F1',utf8_encode('Para'));
$sheet->setCellValue('G1',utf8_encode('Imputation Rework'));
$sheet->setCellValue('H1',utf8_encode('Issue Detected by Customer'));
$sheet->setCellValue('I1',utf8_encode('Week'));
$sheet->setCellValue('J1',utf8_encode('Statut'));
$sheet->setCellValue('K1',utf8_encode('Type'));

$sheet->getStyle('A1:K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT 
(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
Designation,PlanDate,LimitDateFOT,TargetTime,CreationDate,LaunchDate,ClosureDate,
(SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) AS Status,
DateStatut,
(SELECT Libelle FROM gpao_imputationrework WHERE Id=Id_ImputationRework) AS ImputationRework,
IF(IssueDetectedByCustomer=1,'YES','NO') AS IssueDetectedByCustomer,
IF(ClosureDate>'0001-01-01',
CONCAT(YEAR(ClosureDate),'_',IF(WEEK(ClosureDate,1)<10,CONCAT(0,WEEK(ClosureDate,1)),WEEK(ClosureDate,1))),
IF(LaunchDate>'0001-01-01',
CONCAT(YEAR(LaunchDate),'_',IF(WEEK(LaunchDate,1)<10,CONCAT(0,WEEK(LaunchDate,1)),WEEK(LaunchDate,1))),'')
) AS Week,
(SELECT (SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Type,
AM,OF,NC,QLB,TLB,Concession,Para
FROM gpao_statutquality
LEFT JOIN gpao_wo ON gpao_statutquality.Id_WO=gpao_wo.Id 
WHERE gpao_wo.Suppr=0 
AND gpao_statutquality.Suppr=0
AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";

$resultRapport=mysqli_query($bdd,$req);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Customer'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['DateStatut'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['NC'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['OF'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['AM'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Para'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['ImputationRework'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['IssueDetectedByCustomer'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['Week']));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['Status'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['Type'])));

		
		$sheet->getStyle('A'.$ligne.':K'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_KPI_REWORK.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_KPI_REWORK.xlsx';
$writer->save($chemin);
readfile($chemin);
?>