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
$sheet->setCellValue('B1',utf8_encode('Reference'));
$sheet->setCellValue('C1',utf8_encode('Tool Type'));
$sheet->setCellValue('D1',utf8_encode('Next Calibration Date'));
$sheet->setCellValue('E1',utf8_encode('Date of Use'));
$sheet->setCellValue('F1',utf8_encode('NC'));
$sheet->setCellValue('G1',utf8_encode('AM'));
$sheet->setCellValue('H1',utf8_encode('OF/OT'));
$sheet->setCellValue('I1',utf8_encode('QLB'));
$sheet->setCellValue('J1',utf8_encode('TLB'));
$sheet->setCellValue('K1',utf8_encode('Concession'));
$sheet->setCellValue('L1',utf8_encode('Para'));

$sheet->getStyle('A1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT (SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
			gpao_cmte.Reference,
				gpao_cmte.ToolType,gpao_cmte.NextCalibrationDate,gpao_cmte.DateOfUse,
				NC,AM,OF,QLB,TLB,Concession,Para
				FROM gpao_cmte 
				LEFT JOIN gpao_wo
				ON gpao_cmte.Id_WO=gpao_wo.Id
				WHERE gpao_cmte.Suppr=0 
				AND gpao_wo.Suppr=0 
				AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";
if($_GET['Reference']<>''){
	$req.="AND Reference LIKE '%".$_GET['Reference']."%' ";
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
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Reference'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['ToolType'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['NextCalibrationDate'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateOfUse'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['NC'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['AM'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['OF'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['QLB'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['TLB'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['Concession'])));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($row['Para'])));

		$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_CMTE.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_CMTE.xlsx';
$writer->save($chemin);
readfile($chemin);
?>