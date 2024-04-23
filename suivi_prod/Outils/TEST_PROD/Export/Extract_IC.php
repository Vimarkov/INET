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
$sheet->setCellValue('B1',utf8_encode("N° IC"));
$sheet->setCellValue('C1',utf8_encode("Contrôleur"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);

$sheet->getStyle('A1:C1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_olwficheintervention.NumFI,sp_olwdossier.Reference,";
$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_QUALITE) AS Controleur ";
$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwdossier.Id_Prestation=-16 AND sp_olwdossier.Id_Statut='TERC' AND sp_olwficheintervention.NumFI<>'' ";
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
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['NumFI']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Controleur']));
	
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_IC.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_IC.xlsx';
$writer->save($chemin);
readfile($chemin);
?>