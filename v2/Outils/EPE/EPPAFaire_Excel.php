<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";
$blanc="ffffff";

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('D-0705-013 EPP.xlsx');

$sheet = $excel->getSheetByName('D-0705-013_EPP');

$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);

$Id_Prestation=0;
$Id_Pole=0;

$req="SELECT Id_Prestation,Id_Pole 
	FROM new_competences_personne_prestation
	WHERE Id_Personne=".$rowEPE['Id']." 
	AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";
$resultch=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($resultch);
$Id_PrestationPole="0_0";
if($nb>0){
	$rowMouv=mysqli_fetch_array($resultch);
	$Id_Prestation=$rowMouv['Id_Prestation'];
	$Id_Pole=$rowMouv['Id_Pole'];
}


$Presta="";
$Plateforme="";
$req="SELECT LEFT(Libelle,7) AS Prestation,(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Presta=$RowPresta['Prestation'];
	$Plateforme=$RowPresta['Plateforme'];
}

$Pole="";
$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
$ResultPole=mysqli_query($bdd,$req);
$NbPole=mysqli_num_rows($ResultPole);
if($NbPole>0){
	$RowPole=mysqli_fetch_array($ResultPole);
	$Pole=$RowPole['Libelle'];
}

if($Pole<>""){$Presta.=" - ".$Pole;}

$Manager="";
$MatriculeAAAManager="";
$MetierManager="";
$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne, MatriculeAAA,MetierPaie AS Metier
		FROM new_rh_etatcivil
		WHERE Id=".$_GET['Id_Manager'];
$ResultManager=mysqli_query($bdd,$req);
$NbManager=mysqli_num_rows($ResultManager);
if($NbManager>0){
	$RowManager=mysqli_fetch_array($ResultManager);
	$Manager=$RowManager['Personne'];
	$MatriculeAAAManager=$RowManager['MatriculeAAA'];
	$MetierManager=$RowManager['Metier'];
}


$sheet->setCellValue('B3',utf8_encode(stripslashes($rowEPE['MatriculeAAA'])));
$sheet->setCellValue('B4',utf8_encode(stripslashes($rowEPE['Nom'])));
$sheet->setCellValue('B5',utf8_encode(stripslashes($rowEPE['Prenom'])));
$sheet->setCellValue('B6',utf8_encode(stripslashes($rowEPE['Metier'])));
$sheet->setCellValue('B7',utf8_encode(AfficheDateJJ_MM_AAAA($rowEPE['DateAncienneteCDI'])));

$sheet->setCellValue('O3',utf8_encode(date('d/m/Y')));
$sheet->setCellValue('O4',utf8_encode(stripslashes($Plateforme)));
$sheet->setCellValue('O5',utf8_encode(stripslashes($Manager)));
$sheet->setCellValue('O6',utf8_encode(stripslashes($MatriculeAAAManager)));
$sheet->setCellValue('O7',utf8_encode(stripslashes($MetierManager)));


$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche->setName('case');
$objDrawingNonCoche->setDescription('PHPExcel case');
$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-60);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B9');
$objDrawingNonCoche->setWorksheet($sheet);

$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche->setName('case');
$objDrawingNonCoche->setDescription('PHPExcel case');
$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-60);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B10');
$objDrawingNonCoche->setWorksheet($sheet);

$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche->setName('case');
$objDrawingNonCoche->setDescription('PHPExcel case');
$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-60);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B12');
$objDrawingNonCoche->setWorksheet($sheet);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="D-0705-013.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0705-013.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0705-013.xlsx';
$writer->save($chemin);
readfile($chemin);
?>