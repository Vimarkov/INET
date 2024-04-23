<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';


$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("N° MSN"));
$sheet->setCellValue('B1',utf8_encode("N° dossier"));
$sheet->setCellValue('C1',utf8_encode("Procédés spéciaux ajoutés"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(60);

$sheet->getStyle('A1:C1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT DISTINCT sp_olwfi_aipi.Qualification,
	(SELECT sp_olwdossier.MSN FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier) AS MSN,
	(SELECT sp_olwdossier.Reference FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier) AS Reference
	FROM sp_olwfi_aipi 
	LEFT JOIN sp_olwficheintervention 
	ON sp_olwfi_aipi.Id_FI=sp_olwficheintervention.Id 
	WHERE (SELECT Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=-16
	AND sp_olwfi_aipi.Qualification<>''";

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Qualification']));
	
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_PS_ajoutes.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_PS_ajoutes.xlsx';
$writer->save($chemin);
readfile($chemin);
?>