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

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('SouhaitsFormation.xlsx');
$sheet = $excel->getSheetByName('Feuil1');

$requete="
SELECT (SELECT MetierPaie FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MetierPaie,
	(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
	(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
	(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
	(SELECT DateAncienneteCDI FROM new_rh_etatcivil WHERE Id=Id_Personne) AS DateAncienneteCDI,
	(SELECT Contrat FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Contrat,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
	(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
	(SELECT Cadre FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Cadre,
	epe_personne_souhaitformation.Formation,
	epe_personne_souhaitformation.Priorite,
	epe_personne_souhaitformation.Commentaire,
	epe_personne.DateEntretien
	FROM epe_personne_souhaitformation
	LEFT JOIN epe_personne
	ON epe_personne_souhaitformation.Id_epepersonne=epe_personne.Id
	WHERE epe_personne.Suppr=0 AND epe_personne.Type='EPE' 
	AND epe_personne_souhaitformation.Suppr=0
	AND epe_personne_souhaitformation.Favorable=0
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
$requete.="ORDER BY Nom, Prenom";

$result=mysqli_query($bdd,$requete);
$nbResulta=mysqli_num_rows($result);

if($nbResulta>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;

		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Plateforme'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Nom'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Prenom'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['MetierPaie']));
		if($row['DateAncienneteCDI']>'0001-01-01'){
			$date = explode("-",$row['DateAncienneteCDI']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('F'.$ligne,$time);
			$sheet->getStyle('F'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Contrat']));
		if($row['Cadre']==1){$cadre="Cadre";}
		else{$cadre="Non cadre";}
		$sheet->setCellValue('H'.$ligne,utf8_encode($cadre));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['Manager'])));
		if($row['DateEntretien']>'0001-01-01'){
			$date = explode("-",$row['DateEntretien']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('J'.$ligne,$time);
			$sheet->getStyle('J'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['Formation'])));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes('Défavorable')));
		$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes($row['Priorite'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode(stripslashes($row['Commentaire'])));
		$sheet->getStyle('N'.$ligne)->getAlignment()->setWrapText(true);
		
		$sheet->getStyle('A'.$ligne.':N'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':N'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':N'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	}
}
										
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>