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

$date_4mois=date("Y-m-d",strtotime(date("Y-m-d")." + 4 month"));
$date_2mois=date("Y-m-d",strtotime(date("Y-m-d")." + 2 month"));
$date_moins_6mois=date("Y-m-d",strtotime(date("Y-m-d")." - 6 month"));

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Qualifications"));
	
	$sheet->setCellValue('A1',utf8_encode("Personne"));
	$sheet->setCellValue('B1',utf8_encode("Prestation - Pôle"));
	$sheet->setCellValue('C1',utf8_encode("Qualification"));
	$sheet->setCellValue('D1',utf8_encode("Catégorie"));
	$sheet->setCellValue('E1',utf8_encode("Date de fin"));
}
else{
	$sheet->setTitle(utf8_encode("Qualifications"));
	$sheet->setCellValue('A1',utf8_encode("Person"));
	$sheet->setCellValue('B1',utf8_encode("Activity - Pole"));
	$sheet->setCellValue('C1',utf8_encode("Qualification"));
	$sheet->setCellValue('D1',utf8_encode("Category"));
	$sheet->setCellValue('E1',utf8_encode("End date"));
}

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('C')->setWidth(45);
$sheet->getColumnDimension('D')->setWidth(30);
$sheet->getColumnDimension('E')->setWidth(15);

$sheet->getStyle('A1:E1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:E1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFont()->getColor()->setRGB('1f49a6');

//QUALIFICATIONS A REPASSER DANS LES 4 MOIS
$requeteQualifications="SELECT form_qualificationnecessaire_prestation.Id, form_qualificationnecessaire_prestation.Id_Relation, ";
$requeteQualifications.="form_qualificationnecessaire_prestation.Id_Prestation, form_qualificationnecessaire_prestation.Necessaire,new_competences_relation.Id_Qualification_Parrainage, ";
$requeteQualifications.="form_qualificationnecessaire_prestation.Id_Validateur, form_qualificationnecessaire_prestation.DateValidation,new_competences_relation.Date_Fin, ";
$requeteQualifications.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS Personne, ";
$requeteQualifications.="(SELECT Libelle FROM new_competences_prestation WHERE Id=form_qualificationnecessaire_prestation.Id_Prestation) AS Prestation, ";
$requeteQualifications.="(SELECT Libelle FROM new_competences_pole WHERE Id=form_qualificationnecessaire_prestation.Id_Pole) AS Pole, ";
$requeteQualifications.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_qualificationnecessaire_prestation.Id_Prestation) AS Id_Plateforme, ";
$requeteQualifications.="(SELECT Libelle FROM new_competences_qualification WHERE Id=new_competences_relation.Id_Qualification_Parrainage) AS Qualif, ";
$requeteQualifications.="(SELECT (SELECT Libelle FROM new_competences_categorie_qualification WHERE new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification) FROM new_competences_qualification WHERE Id=new_competences_relation.Id_Qualification_Parrainage) AS Categorie ";
$requeteQualifications2="FROM form_qualificationnecessaire_prestation LEFT JOIN new_competences_relation ";
$requeteQualifications2.="ON form_qualificationnecessaire_prestation.Id_Relation=new_competences_relation.Id ";
$requeteQualifications2.="WHERE Necessaire=1 AND new_competences_relation.Suppr=0 ";
$requeteQualifications2.="AND form_qualificationnecessaire_prestation.Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE Id_Plateforme IN (SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Poste IN (".implode(",",$TableauIdPostesAF_RH).") AND Id_Personne=".$IdPersonneConnectee.")) ";
if($_SESSION['FiltreQualifEnAttente_Prestation']<>""){
	$requeteQualifications2.="AND CONCAT((SELECT Libelle FROM new_competences_prestation WHERE Id=form_qualificationnecessaire_prestation.Id_Prestation),' ',(SELECT Libelle FROM new_competences_pole WHERE Id=form_qualificationnecessaire_prestation.Id_Pole)) LIKE '%".$_SESSION['FiltreQualifEnAttente_Prestation']."%' ";
}
if($_SESSION['FiltreQualifEnAttente_Qualification']<>""){
	$requeteQualifications2.="AND (SELECT Libelle FROM new_competences_qualification WHERE Id=new_competences_relation.Id_Qualification_Parrainage) LIKE '%".$_SESSION['FiltreQualifEnAttente_Qualification']."%' ";
}
if($_SESSION['FiltreQualifEnAttente_Personne']<>""){
	$requeteQualifications2.="AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) LIKE '%".$_SESSION['FiltreQualifEnAttente_Personne']."%' ";
}
if($_SESSION['FiltreQualifEnAttente_RespProjet']<>""){
	$requeteQualifications2.="
			AND CONCAT(form_qualificationnecessaire_prestation.Id_Prestation,'_',form_qualificationnecessaire_prestation.Id_Pole) 
				IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$_SESSION['FiltreQualifEnAttente_RespProjet'].")
					AND Id_Poste IN (".$IdPosteResponsableProjet.")
				)
				";
}
if($_SESSION['TriQualifEnAttente_General']<>""){
	$requeteQualifications2.=" ORDER BY ".substr($_SESSION['TriQualifEnAttente_General'],0,-1);
}

$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2);
$nbQualifs=mysqli_num_rows($resultQualifications);

$ligne=2;
if ($nbQualifs>0){
	while($row=mysqli_fetch_array($resultQualifications)){
			$couleur2="black";
			if($row['Date_Fin']<=date('Y-m-d')){
				$couleur2="red";
			}
			elseif($row['Date_Fin']<=$date_2mois){
				$couleur2="orange";
			}
			$Pole="";
			if($row['Pole']<>""){
				$Pole=" - ".stripslashes($row['Pole']);
			}
			$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
			$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Prestation']).$Pole));
			$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Qualif'])));
			$sheet->setCellValue('D'.$ligne,utf8_encode( $row['Categorie']));
			$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['Date_Fin'])));
			
			$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligne.':E'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$ligne++;
	}
}
						
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="FinValiditeQualificationFormation.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="EndOfQualifyingValidityTraining.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/FinValiditeQualificationFormation.xlsx';

$writer->save($chemin);
readfile($chemin);
?>