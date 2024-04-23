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
	$sheet->setTitle(utf8_encode("Sessions annulées"));
	
	$sheet->setCellValue('A1',utf8_encode("Type de formation"));
	$sheet->setCellValue('B1',utf8_encode("Initial/Recyclage"));
	$sheet->setCellValue('C1',utf8_encode("Formation"));
	$sheet->setCellValue('D1',utf8_encode("Date début"));
	$sheet->setCellValue('E1',utf8_encode("Date fin"));
}
else{
	$sheet->setTitle(utf8_encode("Sessions canceled"));
	
	$sheet->setCellValue('A1',utf8_encode("Type of training"));
	$sheet->setCellValue('B1',utf8_encode("Initial / Recycling"));
	$sheet->setCellValue('C1',utf8_encode("Training"));
	$sheet->setCellValue('D1',utf8_encode("Start date"));
	$sheet->setCellValue('E1',utf8_encode("End date"));
}

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(100);

$sheet->getStyle('A1:E1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:E1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFont()->getColor()->setRGB('1f49a6');

$req="
SELECT
	(SELECT Libelle FROM form_typeformation WHERE Id=(SELECT Id_TypeFormation FROM form_formation WHERE Id=form_session.Id_Formation)) AS TypeFormation,
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
FROM form_session
WHERE form_session.Suppr=0
	AND form_session.Id_Plateforme=".$_SESSION['FiltreFormAnnulees_Plateforme']."
	AND Annule=1 
	AND (SELECT COUNT(form_session_date.Id)	 
	FROM form_session_date 
	WHERE form_session_date.Suppr=0 
	AND form_session_date.Id_Session=form_session.Id 
	AND DateSession>='".$_SESSION['FiltreFormAnnulees_DateDebut']."'
	AND DateSession<='".$_SESSION['FiltreFormAnnulees_DateFin']."'
	)>0
	AND Id_Plateforme=".$_SESSION['FiltreFormAnnulees_Plateforme']."
	";
if($_SESSION['FiltreFormAnnulees_Type']<>""){
	$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=form_session.Id_Formation) IN (".$_SESSION['FiltreFormAnnulees_Type'].") ";
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
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['TypeFormation']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($iniRecy));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Formation']));
		
		if($row['DateDebut']>'0001-01-01'){
			$date = explode("-",$row['DateDebut']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('D'.$ligne,$time);
			$sheet->getStyle('D'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($row['DateFin']>'0001-01-01'){
			$date = explode("-",$row['DateFin']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('E'.$ligne,$time);
			$sheet->getStyle('E'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		

		$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':E'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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