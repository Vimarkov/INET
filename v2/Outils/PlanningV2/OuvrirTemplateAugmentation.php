<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require_once '../Fonctions.php';
require_once("Fonctions_Planning.php"); 

$EnAttente="#ffbf03";
$Automatique="#3d9538";
$Validee="#6beb47";
$Refusee="#ff5353";
$Gris="#dddddd";
$AbsenceInjustifies="#ff0303";

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('TemplateImportAugmentation.xlsx');
$sheet = $excel->getSheetByName('Listes');
$sheet2 = $excel->getSheetByName('Import');

$sheet2->setCellValue('B1',utf8_encode(date('m')));
$sheet2->setCellValue('E1',utf8_encode(date('Y')));


$req="SELECT Id, 
	Libelle, LibelleEN
	FROM new_competences_metier WHERE Suppr=0 
	ORDER BY Libelle ";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0)
{
	$i=2;
	while($row=mysqli_fetch_array($result))
	{
		$sheet->setCellValue('A'.$i,utf8_encode($row['Libelle']));
		$i++;
	}
}

$req="SELECT Id, 
	Libelle
	FROM rh_tempstravail WHERE Suppr=0 
	ORDER BY Libelle ";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0)
{
	$i=2;
	while($row=mysqli_fetch_array($result))
	{
		$sheet->setCellValue('B'.$i,utf8_encode($row['Libelle']));
		$i++;
	}
}


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Augementations.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/Augementations.xlsx';
$writer->save($chemin);
readfile($chemin);
?>