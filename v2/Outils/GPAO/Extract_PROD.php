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
$sheet->setCellValue('C1',utf8_encode('Worker'));
$sheet->setCellValue('D1',utf8_encode('Productive Time'));
$sheet->setCellValue('E1',utf8_encode('Idle Time'));
$sheet->setCellValue('F1',utf8_encode('Comments'));
$sheet->setCellValue('G1',utf8_encode('Cause of Idle Time'));
$sheet->setCellValue('H1',utf8_encode('Week'));

$sheet->getStyle('A1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT 
(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
DateProd,ProductiveTime,IdleTime,gpao_productionsheet.Comments,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Worker) AS Worker,
(SELECT Libelle FROM gpao_reasonofblocking WHERE Id=Id_CauseIdleTime) AS CauseIdleTime,
CONCAT(YEAR(DateProd),'_',IF(WEEK(DateProd,1)<10,CONCAT(0,WEEK(DateProd,1)),WEEK(DateProd,1))) AS Week
FROM gpao_productionsheet 
LEFT JOIN gpao_wo
ON gpao_productionsheet.Id_WO=gpao_wo.Id
WHERE gpao_productionsheet.Suppr=0 
AND gpao_wo.Suppr=0
AND gpao_productionsheet.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";

$resultRapport=mysqli_query($bdd,$req);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Customer'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateProd'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Worker'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['ProductiveTime']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['IdleTime']));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Comments'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['CauseIdleTime'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['Week']));

		$sheet->getStyle('A'.$ligne.':H'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_PROD.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_PROD.xlsx';
$writer->save($chemin);
readfile($chemin);
?>