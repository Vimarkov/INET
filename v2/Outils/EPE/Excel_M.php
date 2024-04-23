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
	$sheet->setTitle(utf8_encode("Souhait mobilite"));
	
	$sheet->setCellValue('A1',utf8_encode("Matricule"));
	$sheet->setCellValue('B1',utf8_encode("Personne"));
	$sheet->setCellValue('C1',utf8_encode("Métier (paie)"));
	$sheet->setCellValue('D1',utf8_encode("Prestation"));
	$sheet->setCellValue('E1',utf8_encode("Unité d'exploitation"));
	$sheet->setCellValue('F1',utf8_encode("Responsable"));
	$sheet->setCellValue('G1',utf8_encode("Mobilité"));
	$sheet->setCellValue('H1',utf8_encode("Souhait de mobilité"));
}
else{
	$sheet->setTitle(utf8_encode("Wish mobility"));
	
	$sheet->setCellValue('A1',utf8_encode("Registration number"));
	$sheet->setCellValue('B1',utf8_encode("People"));
	$sheet->setCellValue('C1',utf8_encode("Job"));
	$sheet->setCellValue('D1',utf8_encode("Site"));
	$sheet->setCellValue('E1',utf8_encode("Operating unit"));
	$sheet->setCellValue('F1',utf8_encode("Responsible"));
	$sheet->setCellValue('G1',utf8_encode("Mobility"));
	$sheet->setCellValue('H1',utf8_encode("Wish for mobility"));
	
}

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(50);

$sheet->getStyle('A1:H1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:H1')->getFont()->setBold(true);
$sheet->getStyle('A1:H1')->getFont()->getColor()->setRGB('1f49a6');

$requete="
SELECT DISTINCT Id_EPE, Id_SouhaitMobilite,SouhaitMobilite,
	(SELECT MetierPaie FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MetierPaie,
	(SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite) AS Mobilite,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
	(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
	(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager
	FROM epe_personne_souhaitmobilite
	LEFT JOIN epe_personne
	ON epe_personne_souhaitmobilite.Id_EPE=epe_personne.Id
	WHERE epe_personne.Suppr=0 AND epe_personne.Type='EPP' 
	AND SouhaitMobiliteON=1
	AND PasMobiliteEPP=0
	AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
	AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') ";
if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
	
}
else{
	$requete.="
	AND
	( (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN 
		(
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
		)
	)
		";
}
if($_SESSION['FiltreEPEIndicateurs_Plateforme']<>""){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";}

$requete.="
UNION
SELECT DISTINCT Id_EPE, Id_SouhaitMobilite,'' AS SouhaitMobilite,
	(SELECT MetierPaie FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MetierPaie,
	(SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite) AS Mobilite,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
	(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
	(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager
	FROM epe_personne_souhaitmobilite2
	LEFT JOIN epe_personne
	ON epe_personne_souhaitmobilite2.Id_EPE=epe_personne.Id
	WHERE epe_personne.Suppr=0 AND epe_personne.Type='EPP' 
	AND SouhaitMobiliteON=1
	AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
	AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') ";
if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
	
}
else{
	$requete.="
	AND
	( (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN 
		(
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
		)
	)
		";
}
if($_SESSION['FiltreEPEIndicateurs_Plateforme']<>""){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";}

$requete.="ORDER BY Personne";
$result=mysqli_query($bdd,$requete);
$nbResulta=mysqli_num_rows($result);

if($nbResulta>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;

		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Personne'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['MetierPaie'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Prestation']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['Plateforme']));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Manager'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['Mobilite'])));
		
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['SouhaitMobilite'])));
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true);
		
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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