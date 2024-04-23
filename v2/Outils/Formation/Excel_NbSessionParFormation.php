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
	$sheet->setTitle(utf8_encode("NbSessions"));
	
	$sheet->setCellValue('A1',utf8_encode("Formation"));
	$sheet->setCellValue('B1',utf8_encode("Nombre"));
}
else{
	$sheet->setTitle(utf8_encode("NoSessions"));
	$sheet->setCellValue('A1',utf8_encode("Training"));
	$sheet->setCellValue('B1',utf8_encode("Nb"));
}

$sheet->getColumnDimension('A')->setWidth(100);

$req="
SELECT
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
	COUNT(form_session.Id) AS NbFormation
	
FROM
	form_session_date
LEFT JOIN form_session
	ON form_session_date.Id_Session = form_session.Id
WHERE
	form_session_date.Suppr=0
	AND form_session.Suppr=0
	AND form_session.Id_Plateforme
	 IN (
		SELECT
			Id_Plateforme 
		FROM
			new_competences_personne_poste_plateforme
		WHERE
			Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
	)
	AND Annule=0 
	AND form_session_date.DateSession>='".TrsfDate_($_SESSION['FiltreNbSessionParFormation_DateDebut'])."'
	AND form_session_date.DateSession<='".TrsfDate_($_SESSION['FiltreNbSessionParFormation_DateFin'])."'
	AND Id_Plateforme=".$_SESSION['FiltreNbSessionParFormation_Plateforme']."
	";
if($_SESSION['FiltreNbSessionParFormation_Formateur']<>""){
	$req.=" AND form_session.Id_Formateur IN (".$_SESSION['FiltreNbSessionParFormation_Formateur'].") ";
}

if($_SESSION['FiltreNbSessionParFormation_Type']<>""){
	$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=form_session.Id_Formation) IN (".$_SESSION['FiltreNbSessionParFormation_Type'].") ";
}

$req.=" GROUP BY form_session.Id_Formation
	ORDER BY Formation ASC";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Formation']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['NbFormation']));
	
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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