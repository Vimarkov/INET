<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("N° dossier"));
$sheet->setCellValue('E1',utf8_encode("Date TERA"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);

$sheet->getStyle('A1:B1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_olwdossier.Id, sp_olwdossier.Reference,sp_olwdossier.ReferenceAM,sp_olwdossier.ReferenceNC,sp_olwdossier.ReferencePF,sp_olwficheintervention.DateIntervention ";
$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwdossier.Id_Prestation=316 AND sp_olwficheintervention.Id_StatutPROD='TERA' ";
if(TrsfDate_($_GET['du'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateIntervention >= '".TrsfDate_($_GET['du'])."' ";
}
if(TrsfDate_($_GET['au'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateIntervention <= '".TrsfDate_($_GET['au'])."' ";
}
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$req="SELECT sp_olwficheintervention.Id FROM sp_olwficheintervention ";
		$req.="WHERE sp_olwficheintervention.Id_Dossier=".$row['Id']." ";
		$req.=" AND sp_olwficheintervention.Id_StatutPROD IN ('TFS') ";
		$result2=mysqli_query($bdd,$req);
		$nbResulta2=mysqli_num_rows($result2);
		
		if($nbResulta2==0){
			$sheet->setCellValue('A'.$ligne,utf8_encode($row['Reference']));
			$sheet->setCellValue('B'.$ligne,utf8_encode(AfficheDateFR($row['DateIntervention'])));
		
			$sheet->getStyle('A'.$ligne.':B'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$ligne++;
		}
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_TERA.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_TERA.xlsx';
$writer->save($chemin);
readfile($chemin);
?>