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
$sheet->setCellValue('A1',utf8_encode('Tool Type'));
$sheet->setCellValue('B1',utf8_encode('Reference'));
$sheet->setCellValue('C1',utf8_encode('Next Calibration Date'));
$sheet->setCellValue('D1',utf8_encode('Date of Use'));

$sheet->getStyle('A1:D1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT DISTINCT gpao_cmte.Reference,gpao_cmte.ToolType,gpao_cmte.NextCalibrationDate,gpao_cmte.DateOfUse
	FROM gpao_cmte 
	LEFT JOIN gpao_wo
	ON gpao_cmte.Id_WO=gpao_wo.Id
	WHERE gpao_cmte.Suppr=0 
	AND gpao_wo.Suppr=0 
	AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
	AND NextCalibrationDate>'0001-01-01'
	AND NextCalibrationDate < '".date('Y-m-d',strtotime(date('Y-m-d')." + 1 month"))."' ";

$resultRapport=mysqli_query($bdd,$req);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['ToolType'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Reference'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['NextCalibrationDate'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateOfUse'])));
		
		$sheet->getStyle('A'.$ligne.':D'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_UpcomingCalibration.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_UpcomingCalibration.xlsx';
$writer->save($chemin);
readfile($chemin);
?>