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

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Sans prestation"));
	
	$sheet->setCellValue('A1',utf8_encode("Matricule"));
	$sheet->setCellValue('B1',utf8_encode("Personne"));
	$sheet->setCellValue('C1',utf8_encode("Date d'embauche"));
	$sheet->setCellValue('D1',utf8_encode("Métier paie"));
	$sheet->setCellValue('E1',utf8_encode("Contrat"));
	$sheet->setCellValue('F1',utf8_encode("Cadre / Non cadre"));
	$sheet->setCellValue('G1',utf8_encode("Unité d'exploitation"));
}
else{
	$sheet->setTitle(utf8_encode("No site"));
	
	$sheet->setCellValue('A1',utf8_encode("Registration number"));
	$sheet->setCellValue('B1',utf8_encode("People"));
	$sheet->setCellValue('C1',utf8_encode("Hiring date"));
	$sheet->setCellValue('D1',utf8_encode("Payroll profession"));
	$sheet->setCellValue('E1',utf8_encode("Contract"));
	$sheet->setCellValue('F1',utf8_encode("Executive / Non-executive"));
	$sheet->setCellValue('G1',utf8_encode("Operating unit"));
}

$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(35);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(25);

$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:G1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('1f49a6');

$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
		Cadre,DateAncienneteCDI,Contrat,MetierPaie,
		(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_personne_plateforme
		WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14) LIMIT 1) AS Plateforme
	FROM new_rh_etatcivil
	WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE')
	AND MetierPaie<>'' AND Cadre IN (0,1) 
	AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
		WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14)
		AND Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
		)>0 ";
if($_SESSION['FiltreEPEIndicateurs_Plateforme']<>""){
	$req.="AND (SELECT Id_Plateforme FROM new_competences_personne_plateforme
	WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14) LIMIT 1) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";	
}
if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
	
}
else{
	$req.="
		AND
		(
			SELECT COUNT(new_competences_personne_prestation.Id)
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
			WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
			AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
			AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			AND 
			((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
				)
			) 
		)>0
		";
}
$req.="ORDER BY Personne ";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;
		if($row['Cadre']==1){$cadre="Cadre";}
		else{$cadre="Non cadre";}
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Personne'])));
		if($row['DateAncienneteCDI']>'0001-01-01'){
			$date = explode("-",$row['DateAncienneteCDI']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('C'.$ligne,$time);
			$sheet->getStyle('C'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['MetierPaie'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['Contrat'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($cadre)));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['Plateforme'])));
		
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	}
}
										
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>