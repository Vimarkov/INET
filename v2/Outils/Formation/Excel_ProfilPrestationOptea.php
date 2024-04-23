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
	$sheet->setTitle(utf8_encode("Personnes"));
	
	$sheet->setCellValue('A1',utf8_encode("Personne"));
	$sheet->setCellValue('B1',utf8_encode("Prestation OPTEA"));
}
else{
	$sheet->setTitle(utf8_encode("People"));
	$sheet->setCellValue('A1',utf8_encode("Person"));
	$sheet->setCellValue('B1',utf8_encode("OPTEA Site"));
}

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(35);


$sheet->getStyle('A1:B1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:B1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:B1')->getFont()->setBold(true);
$sheet->getStyle('A1:B1')->getFont()->getColor()->setRGB('1f49a6');

$DateJour=date('Y-m-d');

$requetePersonnes="SELECT Id_Personne,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
		(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		(SELECT CONCAT(' - ',Libelle) FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND EtatValidation=1
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
		AND Id_Personne NOT IN (
			SELECT Id_Personne
			FROM new_competences_personne_prestation
			WHERE new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
			AND new_competences_personne_prestation.Id_Prestation IN ";
			if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
				$requetePersonnes.="(SELECT Id_Prestation 
				FROM new_competences_prestation 
				WHERE Id_Plateforme IN (
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableRH.")
				)) ";
			}
$requetePersonnes.=")
		AND Id_Prestation IN ";
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
		$requetePersonnes.="(SELECT Id_Prestation 
		FROM new_competences_prestation 
		WHERE Id_Plateforme IN (
		SELECT Id_Plateforme 
		FROM new_competences_personne_poste_plateforme
		WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableRH.")
		)) ";
	}
$requetePersonnes.=" ORDER BY Personne ";
$result=mysqli_query($bdd,$requetePersonnes);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0)
{
    $ligne=2;
    while($row=mysqli_fetch_array($result))
    {
    	//Gestion des couleurs en fonction du traitement du besoin
    	$Couleur="FFFFFF";

		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Prestation'].$row['Pole']));

		
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));

		$ligne++;
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