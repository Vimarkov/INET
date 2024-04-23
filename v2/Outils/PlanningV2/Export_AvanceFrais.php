<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_DemandeAvanceSurFrais.xlsx');
$sheet = $excel->getSheetByName('Feuil1');

$Id=$_GET['Id'];

$requete2="SELECT rh_personne_petitdeplacement.Id,rh_personne_petitdeplacement.DateCreation,
	rh_personne_petitdeplacement.Id_Metier,rh_personne_petitdeplacement.Montant,rh_personne_petitdeplacement.AvancePonctuelle,rh_personne_petitdeplacement.Periode,
	(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Plateforme,
	IF(Montant>0,1,0) AS DemandeAvance,
	(SELECT new_competences_metier.LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS MetierEN,
	(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS Metier,
	(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Prenom,
	(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Nom,
	(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Createur) AS DemandeurNom,
	(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Createur) AS DemandeurPrenom,
	(SELECT CONCAT(LEFT(Prenom,1),LEFT(Nom,1),RIGHT(Nom,1)) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Createur) AS DemandeurVisa	
	FROM rh_personne_petitdeplacement
	WHERE rh_personne_petitdeplacement.Id=".$Id;

$result=mysqli_query($bdd,$requete2);
$row=mysqli_fetch_array($result);

$sheet->setCellValue('B8',utf8_encode($row['Nom']));
$sheet->setCellValue('D8',utf8_encode($row['Prenom']));

$sheet->setCellValue('B27',utf8_encode($row['DemandeurNom']));
$sheet->setCellValue('C27',utf8_encode($row['DemandeurPrenom']));
$sheet->setCellValue('E27',utf8_encode($row['DemandeurVisa']));
$sheet->setCellValue('F27',utf8_encode(AfficheDateJJ_MM_AAAA($row['DateCreation'])));

if($_SESSION['Langue']=="FR"){$sheet->setCellValue('B10',utf8_encode($row['Metier']));}
else{$sheet->setCellValue('B10',utf8_encode($row['MetierEN']));}

$sheet->setCellValue('D10',utf8_encode($row['Plateforme']));

if($row['AvancePonctuelle']==1){
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('C16');
	$objDrawing->setOffsetX(10);
	$objDrawing->setOffsetY(11);
	$objDrawing->setWorksheet($sheet);
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche2');
	$objDrawing->setDescription('PHPExcel Coche2');
	$objDrawing->setPath('../../Images/CaseNonCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('C20');
	$objDrawing->setOffsetX(10);
	$objDrawing->setOffsetY(11);
	$objDrawing->setWorksheet($sheet);

	$sheet->setCellValue('D17',utf8_encode(AfficheDateJJ_MM_AAAA($row['Periode'])));
	$sheet->setCellValue('D18',utf8_encode($row['Montant']));
}
else{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseNonCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('C16');
	$objDrawing->setOffsetX(10);
	$objDrawing->setOffsetY(11);
	$objDrawing->setWorksheet($sheet);
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche2');
	$objDrawing->setDescription('PHPExcel Coche2');
	$objDrawing->setPath('../../Images/CaseCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('C20');
	$objDrawing->setOffsetX(10);
	$objDrawing->setOffsetY(11);
	$objDrawing->setWorksheet($sheet);
	
	$sheet->setCellValue('D22',utf8_encode($row['Montant']));
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="DemandeAvanceSurFrais.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/DemandeAvanceSurFrais.xlsx';
$writer->save($chemin);
readfile($chemin);
?>