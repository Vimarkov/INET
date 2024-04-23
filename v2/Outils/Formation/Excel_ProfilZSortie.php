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
}
else{
	$sheet->setTitle(utf8_encode("People"));
	$sheet->setCellValue('A1',utf8_encode("Person"));
}

$sheet->getColumnDimension('A')->setWidth(25);


$sheet->getStyle('A1:A1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:A1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:A1')->getFont()->setBold(true);
$sheet->getStyle('A1:A1')->getFont()->getColor()->setRGB('1f49a6');

$DateJour=date('Y-m-d');

$requetePersonnes="SELECT Id_Personne,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
		FROM new_competences_personne_plateforme
		WHERE Id_Plateforme <> 14
		AND Id_Plateforme IN (
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableRH.")
		)
		AND Id_Personne NOT IN (
			SELECT Id_Personne
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
		)";
		
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

		$sheet->getStyle('A'.$ligne.':A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':A'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':A'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':A'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));

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