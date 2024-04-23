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
	$sheet->setCellValue('B1',utf8_encode("Catégorie"));
	$sheet->setCellValue('C1',utf8_encode("Catégorie maitre"));
	$sheet->setCellValue('D1',utf8_encode("Moyens"));
	$sheet->setCellValue('E1',utf8_encode("Catégorie moyens"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Qualification"));
	$sheet->setCellValue('B1',utf8_encode("Category"));
	$sheet->setCellValue('C1',utf8_encode("Category master"));
	$sheet->setCellValue('D1',utf8_encode("Means"));
	$sheet->setCellValue('E1',utf8_encode("Category means"));
}

$sheet->getColumnDimension('A')->setWidth(35);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(35);
$sheet->getColumnDimension('E')->setWidth(35);

$sheet->getStyle('A1:E1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:E1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFont()->getColor()->setRGB('1f49a6');

$requete="SELECT new_competences_qualification.Libelle,
		(SELECT (SELECT Libelle FROM new_competences_moyen WHERE new_competences_moyen.Id=new_competences_moyen_categorie.Id_Moyen) FROM new_competences_moyen_categorie WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Moyen,
		(SELECT Libelle FROM new_competences_moyen_categorie WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS CategorieMoyen,
		(SELECT Libelle FROM new_competences_categorie_qualification WHERE new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification) AS Categorie,
		(SELECT (SELECT Libelle FROM new_competences_categorie_qualification_maitre WHERE new_competences_categorie_qualification_maitre.Id=new_competences_categorie_qualification.Id_Categorie_Maitre) FROM new_competences_categorie_qualification WHERE new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification) AS CategorieMaitre
		FROM new_competences_qualification_moyen
		LEFT JOIN new_competences_qualification
		ON new_competences_qualification.Id=new_competences_qualification_moyen.Id_Qualification
		WHERE new_competences_qualification.Suppr=0
		AND new_competences_qualification_moyen.Suppr=0
	";
$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
$ligne=2;
while($row=mysqli_fetch_array($result)){
	$sheet->setCellValue('A'.$ligne,utf8_encode($row['Libelle']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row['Categorie']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row['CategorieMaitre']));
	$sheet->setCellValue('D'.$ligne,utf8_encode($row['Moyen']));
	$sheet->setCellValue('E'.$ligne,utf8_encode($row['CategorieMoyen']));
	
	$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A'.$ligne.':E'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	
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