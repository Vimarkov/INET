<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";
$blanc="ffffff";

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("QCM"));
	
	$sheet->setCellValue('A1',utf8_encode("QCM"));
	$sheet->setCellValue('B1',utf8_encode("Langue"));
	$sheet->setCellValue('C1',utf8_encode("Date mise à jour"));
	$sheet->setCellValue('D1',utf8_encode("Brouillon"));
}
else{
	$sheet->setTitle(utf8_encode("MCQ"));
	$sheet->setCellValue('A1',utf8_encode("QCM"));
	$sheet->setCellValue('B1',utf8_encode("Language"));
	$sheet->setCellValue('C1',utf8_encode("Date updated"));
	$sheet->setCellValue('D1',utf8_encode("Draft"));
}

$sheet->getColumnDimension('A')->setWidth(35);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(25);

$sheet->getStyle('A1:D1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:D1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:D1')->getFont()->setBold(true);
$sheet->getStyle('A1:D1')->getFont()->getColor()->setRGB('1f49a6');

$requete="SELECT form_qcm.Code,
(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue,
IF(form_qcm_langue.Brouillon=0,'','X') AS Brouillon,
form_qcm_langue.Date_MAJ
FROM form_qcm 
LEFT JOIN form_qcm_langue
ON form_qcm_langue.Id_QCM=form_qcm.Id
WHERE form_qcm.Suppr=0
AND form_qcm_langue.Suppr=0 
ORDER BY Code, Langue
";

$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0)
{
    $ligne=2;
	$couleur="EEEEEE";
    while($row=mysqli_fetch_array($result))
    {
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
					
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Code']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Langue']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['Date_MAJ'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Brouillon']));

		$sheet->getStyle('A'.$ligne.':D'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':D'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':D'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':D'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));

		$ligne++;
    }	
}
						
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="QCM.xlsx"');}
else{header('Content-Disposition: attachment;filename="MCQ.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/QCM.xlsx';
$writer->save($chemin);
readfile($chemin);
?>