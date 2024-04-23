<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Qualifications"));
	
	$sheet->setCellValue('A1',utf8_encode("Qualification"));
	$sheet->setCellValue('B1',utf8_encode("Unité d'exploitation"));
	$sheet->setCellValue('C1',utf8_encode("Avion"));
	$sheet->setCellValue('D1',utf8_encode("Produit"));
	$sheet->setCellValue('E1',utf8_encode("Client"));
	$sheet->setCellValue('F1',utf8_encode("Document(s) applicable(s)"));
	$sheet->setCellValue('G1',utf8_encode("Formation initiale"));
	$sheet->setCellValue('H1',utf8_encode("Formation spécifique"));
	$sheet->setCellValue('I1',utf8_encode("Experience"));
	$sheet->setCellValue('J1',utf8_encode("Autres qualifications"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Qualification"));
	$sheet->setCellValue('B1',utf8_encode("Operating unit"));
	$sheet->setCellValue('C1',utf8_encode("Aircraft"));
	$sheet->setCellValue('D1',utf8_encode("Product"));
	$sheet->setCellValue('E1',utf8_encode("Client"));
	$sheet->setCellValue('F1',utf8_encode("Applicable documents"));
	$sheet->setCellValue('G1',utf8_encode("Initial formation"));
	$sheet->setCellValue('H1',utf8_encode("Specific training"));
	$sheet->setCellValue('I1',utf8_encode("Experience"));
	$sheet->setCellValue('J1',utf8_encode("Other qualifications"));
}

$sheet->getColumnDimension('A')->setWidth(35);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(35);
$sheet->getColumnDimension('E')->setWidth(35);
$sheet->getColumnDimension('F')->setWidth(35);
$sheet->getColumnDimension('G')->setWidth(35);
$sheet->getColumnDimension('H')->setWidth(35);
$sheet->getColumnDimension('I')->setWidth(35);
$sheet->getColumnDimension('J')->setWidth(35);


$sheet->getStyle('A1:J1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:J1')->getFont()->setBold(true);
$sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('1f49a6');

$requete="SELECT new_competences_qualification.Libelle,
		(SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_qualification_plateforme_infos.Id_Plateforme) AS Plateforme,
		new_competences_qualification_plateforme_infos.Avion,
		new_competences_qualification_plateforme_infos.Produit,
		new_competences_qualification_plateforme_infos.Client,
		new_competences_qualification_plateforme_infos.Doc_Applicable,
		new_competences_qualification_plateforme_infos.Formation_Initiale,
		new_competences_qualification_plateforme_infos.Formation_Specifique,
		new_competences_qualification_plateforme_infos.Experience,
		new_competences_qualification_plateforme_infos.Autre_Qualification
		FROM new_competences_qualification_plateforme_infos
		LEFT JOIN new_competences_qualification
		ON new_competences_qualification.Id=new_competences_qualification_plateforme_infos.Id_Qualification
		WHERE new_competences_qualification.Suppr=0
		AND new_competences_qualification_plateforme_infos.Suppr=0
	";
$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
$ligne=2;
while($row=mysqli_fetch_array($result)){
	$sheet->setCellValue('A'.$ligne,utf8_encode($row['Libelle']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row['Plateforme']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row['Avion']));
	$sheet->setCellValue('D'.$ligne,utf8_encode($row['Produit']));
	$sheet->setCellValue('E'.$ligne,utf8_encode($row['Client']));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row['Doc_Applicable']));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row['Formation_Initiale']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($row['Formation_Specifique']));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row['Experience']));
	$sheet->setCellValue('J'.$ligne,utf8_encode($row['Autre_Qualification']));
	
	$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	
	$ligne++;
	}
}	//Fin boucle

						
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="Qualifications.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="Qualifications.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Qualifications.xlsx';

$writer->save($chemin);
readfile($chemin);
?>