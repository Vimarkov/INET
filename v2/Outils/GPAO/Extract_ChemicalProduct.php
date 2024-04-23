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
$sheet->setCellValue('D1',utf8_encode('Product Type'));
$sheet->setCellValue('E1',utf8_encode('Product Reference'));
$sheet->setCellValue('F1',utf8_encode('Batch n°'));
$sheet->setCellValue('G1',utf8_encode('Expiration Date'));
$sheet->setCellValue('H1',utf8_encode('Application Date'));
$sheet->setCellValue('I1',utf8_encode('Application Time'));
$sheet->setCellValue('J1',utf8_encode('Thermohydrometer n°'));
$sheet->setCellValue('K1',utf8_encode('T°C'));
$sheet->setCellValue('L1',utf8_encode('Humidity'));

$sheet->getStyle('A1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT (SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
		gpao_chemicalproduct.ProductType,gpao_chemicalproduct.ProductReference,
		gpao_chemicalproduct.NumBatch,gpao_chemicalproduct.ExpirationDate,gpao_chemicalproduct.ApplicationDate,
		gpao_chemicalproduct.ApplicationTime,gpao_chemicalproduct.NumThermohydrometer,
		gpao_chemicalproduct.TemperatureC,gpao_chemicalproduct.Humidity,
		NC,OF
	FROM gpao_chemicalproduct 
	LEFT JOIN gpao_wo
	ON gpao_chemicalproduct.Id_WO=gpao_wo.Id
	WHERE gpao_chemicalproduct.Suppr=0 
	AND gpao_wo.Suppr=0 
	AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";
if($_GET['Reference']<>''){
	$req.="AND ProductReference LIKE '%".$_GET['Reference']."%' ";
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
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['ProductType'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['ProductReference'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['NumBatch'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['ExpirationDate'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['ApplicationDate'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['ApplicationTime'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['NumThermohydrometer'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['TemperatureC'])));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($row['Humidity'])));

		$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_ChemicalProduct.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_ChemicalProduct.xlsx';
$writer->save($chemin);
readfile($chemin);
?>