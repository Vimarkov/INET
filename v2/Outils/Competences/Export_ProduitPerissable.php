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
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Famille de produits'));
	$sheet->setCellValue('B1',utf8_encode('Référence'));
	$sheet->setCellValue('C1',utf8_encode('Température mini de stockage'));
	$sheet->setCellValue('D1',utf8_encode('Température maxi de stockage'));
	$sheet->setCellValue('E1',utf8_encode('Péremption après ouverture'));
	$sheet->setCellValue('F1',utf8_encode('Fiche de données sécurité (FDS)'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Product family'));
	$sheet->setCellValue('B1',utf8_encode('Reference'));
	$sheet->setCellValue('C1',utf8_encode('Storage mini Temperature'));
	$sheet->setCellValue('D1',utf8_encode('Storage maxi Temperature'));
	$sheet->setCellValue('E1',utf8_encode('Validity after open'));
	$sheet->setCellValue('F1',utf8_encode('Safety Data Sheet <br>(FDS in french)'));
}
$sheet->getStyle('A1:C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(40);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(30);
$sheet->getColumnDimension('F')->setWidth(40);

$req="SELECT Id,AIMS,Reference,TemperatureMini,TemperatureMaxi,Peremption,FDS,FTP,Document 
	FROM produit_perrissable 
	WHERE Suppr=0 
	ORDER BY Reference ";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($result))
	{
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['AIMS']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Reference'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['TemperatureMini'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['TemperatureMaxi'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['Peremption'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['FDS'])));
		
		$sheet->getStyle('A'.$ligne.':F'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Excel.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Excel.xlsx';
$writer->save($chemin);
readfile($chemin);
?>