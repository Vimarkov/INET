<?php
session_start();
require("../ConnexioniSansBody.php");
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
include '../Fonctions.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;


$req="SELECT Id,Volume,OTD,OQD,TypeTravail,Complexite,";
$req.="(SELECT Libelle FROM trame_domainetechnique WHERE trame_domainetechnique.Id=trame_uo_cdc.Id_DT) AS DT,";
$req.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_uo_cdc.Id_WP) AS WP,";
$req.="(SELECT DateDebut FROM trame_wp WHERE trame_wp.Id=trame_uo_cdc.Id_WP) AS DateDebut,";
$req.="(SELECT DateFin FROM trame_wp WHERE trame_wp.Id=trame_uo_cdc.Id_WP) AS DateFin,";
$req.="(SELECT (SELECT Libelle FROM trame_categorie WHERE trame_categorie.Id=trame_uo.Id_Categorie) AS Categorie FROM trame_uo WHERE trame_uo.Id=trame_uo_cdc.Id_UO) AS Categorie, ";
$req.="(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_uo_cdc.Id_UO) AS UO ";
$req.="FROM trame_uo_cdc WHERE Id_WP=".$_GET['WP']." ORDER BY UO;";
$result2=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Workpackage"));
	$sheet->setCellValue('B1',utf8_encode("Unité d'oeuvre"));
	$sheet->setCellValue('C1',utf8_encode("Category"));
	$sheet->setCellValue('D1',utf8_encode("Technical domain"));
	$sheet->setCellValue('E1',utf8_encode("Type of work"));
	$sheet->setCellValue('F1',utf8_encode("Complexity"));
	$sheet->setCellValue('G1',utf8_encode("Start date"));
	$sheet->setCellValue('H1',utf8_encode("End date"));
	$sheet->setCellValue('I1',utf8_encode("Volume"));
	$sheet->setCellValue('J1',utf8_encode("OTD"));
	$sheet->setCellValue('K1',utf8_encode("OQD"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Workpackage"));
	$sheet->setCellValue('B1',utf8_encode("Work unit"));
	$sheet->setCellValue('C1',utf8_encode("Catégorie"));
	$sheet->setCellValue('D1',utf8_encode("Domaine technique"));
	$sheet->setCellValue('E1',utf8_encode("Type de travail"));
	$sheet->setCellValue('F1',utf8_encode("Complexité"));
	$sheet->setCellValue('G1',utf8_encode("Date de début"));
	$sheet->setCellValue('H1',utf8_encode("Date de fin"));
	$sheet->setCellValue('I1',utf8_encode("Volume"));
	$sheet->setCellValue('J1',utf8_encode("OTD"));
	$sheet->setCellValue('K1',utf8_encode("OQD"));
}

$sheet->getStyle('A1:K1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:K1')->getFont()->setBold(true);
$sheet->getStyle('A1:K1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(10);
$sheet->getColumnDimension('K')->setWidth(10);

$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['WP']))));
	$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['UO']))));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['Categorie']));
	$sheet->setCellValue('D'.$ligne,utf8_encode($row2['DT']));
	$sheet->setCellValue('E'.$ligne,utf8_encode($row2['TypeTravail']));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row2['Complexite']));
	if(AfficheDateJJ_MM_AAAA($row2['DateDebut'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateDebut']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('G'.$ligne,$time);
		$sheet->getStyle('G'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	if(AfficheDateJJ_MM_AAAA($row2['DateFin'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateFin']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('H'.$ligne,$time);
		$sheet->getStyle('H'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Volume']));
	$sheet->setCellValue('J'.$ligne,utf8_encode($row2['OTD']));
	$sheet->setCellValue('K'.$ligne,utf8_encode($row2['OQD']));
	
	$sheet->getStyle('A'.$ligne.':K'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_CDC.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_CDC.xlsx';
$writer->save($chemin);
readfile($chemin);
?>