<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../../ConnexioniSansBody.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('ECs.xlsx');

$Id = $_GET['Id'];

$req="SELECT Id,MSN,TypeMoteur,PN_EngineLH,PN_FanCowlLHELH,PN_FanCowlRHELH,PN_FanReverserLHELH,PN_FanReverserRHELH, ";
$req.="PN_DemountablePowerLH,PN_EngineRH,PN_FanCowlLHERH,PN_FanCowlRHERH,PN_FanReverserLHERH,PN_FanReverserRHERH,PN_DemountablePowerRH,";
$req.="SN_EngineLH,SN_FanCowlLHELH,SN_FanCowlRHELH,SN_FanReverserLHELH,SN_FanReverserRHELH,SN_DemountablePowerLH,SN_EngineRH,";
$req.="SN_FanCowlLHERH,SN_FanCowlRHERH,SN_FanReverserLHERH,SN_FanReverserRHERH,SN_DemountablePowerRH,Doc_EngineLH,Doc_FanCowlLHELH,";
$req.="Doc_FanCowlRHELH,Doc_FanReverserLHELH,Doc_FanReverserRHELH,Doc_DemountablePowerLH,Doc_EngineRH,Doc_FanCowlLHERH,Doc_FanCowlRHERH,";
$req.="Doc_FanReverserLHERH,Doc_FanReverserRHERH,Doc_DemountablePowerRH ";
$req.="FROM sp_atrmoteur WHERE Id=".$_GET['Id'];
$result=mysqli_query($bdd,$req);
$row=mysqli_fetch_array($result);

$sheet = $excel->setActiveSheetIndexByName($row['TypeMoteur']);
$sheet->setCellValue('J1',"MSN:".utf8_encode($row['MSN']));

//PN
if($row['TypeMoteur']<>"LEAP"){
	$sheet->setCellValue('E6',utf8_encode($row['PN_EngineLH']));
}
$sheet->setCellValue('E7',utf8_encode($row['PN_FanCowlLHELH']));
$sheet->setCellValue('E8',utf8_encode($row['PN_FanCowlRHELH']));
$sheet->setCellValue('E9',utf8_encode($row['PN_FanReverserLHELH']));
$sheet->setCellValue('E10',utf8_encode($row['PN_FanReverserRHELH']));
if($row['TypeMoteur']<>"LEAP"){
	$sheet->setCellValue('E11',utf8_encode($row['PN_DemountablePowerLH']));
	$sheet->setCellValue('E12',utf8_encode($row['PN_EngineRH']));
}
$sheet->setCellValue('E13',utf8_encode($row['PN_FanCowlLHERH']));
$sheet->setCellValue('E14',utf8_encode($row['PN_FanCowlRHERH']));
$sheet->setCellValue('E15',utf8_encode($row['PN_FanReverserLHERH']));
$sheet->setCellValue('E16',utf8_encode($row['PN_FanReverserRHERH']));
if($row['TypeMoteur']<>"LEAP"){
	$sheet->setCellValue('E17',utf8_encode($row['PN_DemountablePowerRH']));
}
//SN
$sheet->setCellValue('G6',utf8_encode($row['SN_EngineLH']));
$sheet->setCellValue('G7',utf8_encode($row['SN_FanCowlLHELH']));
$sheet->setCellValue('G8',utf8_encode($row['SN_FanCowlRHELH']));
$sheet->setCellValue('G9',utf8_encode($row['SN_FanReverserLHELH']));
$sheet->setCellValue('G10',utf8_encode($row['SN_FanReverserRHELH']));
if($row['TypeMoteur']<>"LEAP"){
	$sheet->setCellValue('G11',utf8_encode($row['SN_DemountablePowerLH']));
}
$sheet->setCellValue('G12',utf8_encode($row['SN_EngineRH']));
$sheet->setCellValue('G13',utf8_encode($row['SN_FanCowlLHERH']));
$sheet->setCellValue('G14',utf8_encode($row['SN_FanCowlRHERH']));
$sheet->setCellValue('G15',utf8_encode($row['SN_FanReverserLHERH']));
$sheet->setCellValue('G16',utf8_encode($row['SN_FanReverserRHERH']));
if($row['TypeMoteur']<>"LEAP"){
	$sheet->setCellValue('G17',utf8_encode($row['SN_DemountablePowerRH']));
}

//DOC
$sheet->setCellValue('J6',utf8_encode($row['Doc_EngineLH']));
if($row['TypeMoteur']<>"LEAP"){
	$sheet->setCellValue('J7',utf8_encode($row['Doc_FanCowlLHELH']));
	$sheet->setCellValue('J8',utf8_encode($row['Doc_FanCowlRHELH']));
	$sheet->setCellValue('J9',utf8_encode($row['Doc_FanReverserLHELH']));
	$sheet->setCellValue('J10',utf8_encode($row['Doc_FanReverserRHELH']));
	$sheet->setCellValue('J11',utf8_encode($row['Doc_DemountablePowerLH']));
}
$sheet->setCellValue('J12',utf8_encode($row['Doc_EngineRH']));
if($row['TypeMoteur']<>"LEAP"){
	$sheet->setCellValue('J13',utf8_encode($row['Doc_FanCowlLHERH']));
	$sheet->setCellValue('J14',utf8_encode($row['Doc_FanCowlRHERH']));
	$sheet->setCellValue('J15',utf8_encode($row['Doc_FanReverserLHERH']));
	$sheet->setCellValue('J16',utf8_encode($row['Doc_FanReverserRHERH']));
	$sheet->setCellValue('J17',utf8_encode($row['Doc_DemountablePowerRH']));
}
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="ECs.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../../tmp/ECs.xlsx';
$writer->save($chemin);
readfile($chemin);
?>