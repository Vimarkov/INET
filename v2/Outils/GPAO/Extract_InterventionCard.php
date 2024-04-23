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
$sheet->setCellValue('A1',utf8_encode('MSN'));
$sheet->setCellValue('B1',utf8_encode('NC'));
$sheet->setCellValue('C1',utf8_encode('OF/OT'));
$sheet->setCellValue('D1',utf8_encode('Intervention Card n°'));
$sheet->setCellValue('E1',utf8_encode('Start date'));
$sheet->setCellValue('F1',utf8_encode('End Date'));
$sheet->setCellValue('G1',utf8_encode('Closure Type'));
$sheet->setCellValue('H1',utf8_encode('Intervention Name'));
$sheet->setCellValue('I1',utf8_encode('Intervention Signature Date'));
$sheet->setCellValue('J1',utf8_encode('QI Stamp n°'));
$sheet->setCellValue('K1',utf8_encode('QI Closure Date'));

$sheet->getStyle('A1:K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT (SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
	gpao_interventioncard.Numero,gpao_interventioncard.StartDate,
	gpao_interventioncard.EndDate,gpao_interventioncard.ClosureType,gpao_interventioncard.IntervenerName,
	gpao_interventioncard.IntervenerSignatureDate,gpao_interventioncard.QIStampNum,
	gpao_interventioncard.QIClosureDate,
	NC,OF
	FROM gpao_interventioncard 
	LEFT JOIN gpao_wo
	ON gpao_interventioncard.Id_WO=gpao_wo.Id
	WHERE gpao_interventioncard.Suppr=0 
	AND gpao_wo.Suppr=0 
	AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";
if($_GET['NC']<>""){
	$req.="AND NC LIKE '%".$_GET['NC']."%' ";
}
if($_GET['OF']<>""){
	$req.="AND OF LIKE '%".$_GET['OF']."%' ";
}
if($_GET['MSN']<>""){
	$req.="AND (SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) LIKE '%".$_GET['MSN']."%' ";
}

$resultRapport=mysqli_query($bdd,$req);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['MSN'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['NC'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['OF'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Numero'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['StartDate'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['EndDate'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['ClosureType'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['IntervenerName'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['IntervenerSignatureDate'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['QIStampNum'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['QIClosureDate'])));

		$sheet->getStyle('A'.$ligne.':K'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_InterventionCard.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_InterventionCard.xlsx';
$writer->save($chemin);
readfile($chemin);
?>