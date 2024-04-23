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
	$sheet->setTitle(utf8_encode("Liste désinscriptions"));
	
	$sheet->setCellValue('A1',utf8_encode("Personne"));
	$sheet->setCellValue('B1',utf8_encode("Prestation"));
	$sheet->setCellValue('C1',utf8_encode("Type de formation"));
	$sheet->setCellValue('D1',utf8_encode("Initial/Recyclage"));
	$sheet->setCellValue('E1',utf8_encode("Formation"));
	$sheet->setCellValue('F1',utf8_encode("Date début"));
	$sheet->setCellValue('G1',utf8_encode("Date fin"));
	$sheet->setCellValue('H1',utf8_encode("Date désinscription"));
	$sheet->setCellValue('I1',utf8_encode("Coût"));
	$sheet->setCellValue('J1',utf8_encode("A comptabiliser"));
}
else{
	$sheet->setTitle(utf8_encode("Unsubscribe list"));
	
	$sheet->setCellValue('A1',utf8_encode("Person"));
	$sheet->setCellValue('B1',utf8_encode("Site"));
	$sheet->setCellValue('C1',utf8_encode("Type of training"));
	$sheet->setCellValue('D1',utf8_encode("Initial / Recycling"));
	$sheet->setCellValue('E1',utf8_encode("Training"));
	$sheet->setCellValue('F1',utf8_encode("Start date"));
	$sheet->setCellValue('G1',utf8_encode("End date"));
	$sheet->setCellValue('H1',utf8_encode("Unsubscribe date"));
	$sheet->setCellValue('I1',utf8_encode("Cost"));
	$sheet->setCellValue('J1',utf8_encode("To be counted"));
}

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(100);

$sheet->getStyle('A1:J1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:J1')->getFont()->setBold(true);
$sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('1f49a6');

$req="
SELECT
	(SELECT Libelle FROM form_typeformation WHERE Id=(SELECT Id_TypeFormation FROM form_formation WHERE Id=form_session.Id_Formation)) AS TypeFormation,
	IF(form_session_personne.Suppr=1,form_session_personne.Date_Desinscription,form_session_personne.Date_Valideur) AS Date_Desinscription,
	form_session_personne.Cout AS COUT,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne,
	(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) FROM form_besoin WHERE Id=Id_Besoin) AS Prestation,
	(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) FROM form_besoin WHERE Id=Id_Besoin) AS Pole,
	AComptabiliser,
	Recyclage,
	(
		SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
		FROM form_formation_langue_infos
		WHERE Id_Formation=form_session.Id_Formation
		AND Id_Langue=
			(SELECT Id_Langue 
			FROM form_formation_plateforme_parametres 
			WHERE Id_Plateforme=form_session.Id_Plateforme
			AND Id_Formation=form_session.Id_Formation
			AND form_formation_plateforme_parametres.Suppr=0 
			LIMIT 1)
		AND Suppr=0
	) AS Formation,
	(	SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) 
		FROM form_formation_plateforme_parametres 
		WHERE Id_Plateforme=form_session.Id_Plateforme
		AND Id_Formation=form_session.Id_Formation
		AND form_formation_plateforme_parametres.Suppr=0 
		LIMIT 1
	) AS Organisme,
	(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1) AS DateDebut,
	(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession DESC LIMIT 1) AS DateFin
FROM form_session_personne
LEFT JOIN form_session
ON form_session_personne.Id_Session=form_session.Id
WHERE form_session.Suppr=0
	AND form_session.Id_Plateforme=".$_SESSION['FiltreFormDesinscription_Plateforme']."
	AND Annule=1 
	AND (SELECT COUNT(form_session_date.Id)	
	AND (
	(form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0)
	OR form_session_personne.Validation_Inscription=-1
	)
	FROM form_session_date 
	WHERE form_session_date.Suppr=0 
	AND form_session_date.Id_Session=form_session.Id 
	AND DateSession>='".$_SESSION['FiltreFormDesinscription_DateDebut']."'
	AND DateSession<='".$_SESSION['FiltreFormDesinscription_DateFin']."'
	)>0
	AND Id_Plateforme=".$_SESSION['FiltreFormDesinscription_Plateforme']."
	";
if($_SESSION['FiltreFormDesinscription_Type']<>""){
	$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=form_session.Id_Formation) IN (".$_SESSION['FiltreFormDesinscription_Type'].") ";
}
$req.=" ORDER BY DateDebut ASC";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;
		
		$organisme="";
		if($row['Organisme']<>""){
			$organisme=" (".$row['Organisme'].")";
		}
		if($row['Recyclage']==1){
			if($LangueAffichage=="FR"){$iniRecy= "Recyclage";}else{$iniRecy= "Recycling";}
		}
		else{
			if($LangueAffichage=="FR"){$iniRecy= "Initial";}else{$iniRecy= "Initial";}
		}
		
		$presta=$row['Prestation'];
		if($row['Pole']<>""){$presta.=" - ".$row['Pole'];}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($presta));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['TypeFormation']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($iniRecy));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['Formation']));
		
		if($row['DateDebut']>'0001-01-01'){
			$date = explode("-",$row['DateDebut']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('F'.$ligne,$time);
			$sheet->getStyle('F'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($row['DateFin']>'0001-01-01'){
			$date = explode("-",$row['DateFin']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('G'.$ligne,$time);
			$sheet->getStyle('G'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($row['Date_Desinscription']>'0001-01-01'){
			$date = explode("-",$row['Date_Desinscription']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('H'.$ligne,$time);
			$sheet->getStyle('H'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['COUT']));
		
		if($row['AComptabiliser']==1){
			$sheet->setCellValue('J'.$ligne,utf8_encode("X"));
		}
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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