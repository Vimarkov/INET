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

function unNombreSinonRien($leNombre){
	$nb="";
	if($leNombre<>0){$nb=$leNombre;}
	return $nb;
}

$sheet->setTitle(utf8_encode("Feuil1"));
	
$sheet->setCellValue('A1',utf8_encode("Intitulé"));
$sheet->setCellValue('B1',utf8_encode("1"));
$sheet->setCellValue('C1',utf8_encode("2"));
$sheet->setCellValue('D1',utf8_encode("3"));
$sheet->setCellValue('E1',utf8_encode("4"));
$sheet->setCellValue('F1',utf8_encode("Moyenne"));

$sheet->getColumnDimension('A')->setWidth(70);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(10);
$sheet->getColumnDimension('D')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(10);
$sheet->getColumnDimension('F')->setWidth(10);

$sheet->getStyle('A1:F1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:F1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:F1')->getFont()->setBold(true);
$sheet->getStyle('A1:F1')->getFont()->getColor()->setRGB('1f49a6');

$requete="
SELECT DISTINCT epe_personne_bilanformation.Formation 
	FROM epe_personne_bilanformation
	LEFT JOIN epe_personne
	ON epe_personne_bilanformation.Id_epepersonne=epe_personne.Id
	WHERE epe_personne.Suppr=0 AND epe_personne.Type='EPE' 
	AND epe_personne_bilanformation.Suppr=0
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

$requete.="ORDER BY Formation";

$result=mysqli_query($bdd,$requete);
$nbResulta=mysqli_num_rows($result);

if($nbResulta>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;
		$Col="G";
		$NumCol=1;
		$Nb1=0;
		$Nb2=0;
		$Nb3=0;
		$Nb4=0;
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Formation'])));
		
		$requete="
			SELECT EvaluationAFroid,Commentaire
				FROM epe_personne_bilanformation
				LEFT JOIN epe_personne
				ON epe_personne_bilanformation.Id_epepersonne=epe_personne.Id
				WHERE epe_personne.Suppr=0 AND epe_personne.Type='EPE' 
				AND epe_personne_bilanformation.Suppr=0
				AND epe_personne_bilanformation.Formation=\"".stripslashes($row['Formation'])."\"
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
			$result2=mysqli_query($bdd,$requete);
			$nbResulta2=mysqli_num_rows($result2);

			if($nbResulta2>0){
				while($row2=mysqli_fetch_array($result2)){
					if($row2['EvaluationAFroid']==1){$Nb1++;}
					elseif($row2['EvaluationAFroid']==2){$Nb2++;}
					elseif($row2['EvaluationAFroid']==3){$Nb3++;}
					elseif($row2['EvaluationAFroid']==4){$Nb4++;}
					
					if($row2['Commentaire']<>""){
						$sheet->setCellValue($Col.$ligne,utf8_encode(stripslashes($row2['Commentaire'])));
						$sheet->setCellValue($Col."1",utf8_encode(stripslashes("Commentaire ".$NumCol)));
						$NumCol++;
						$Col++;
					}
				}
			}
			$sheet->setCellValue("B".$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb1))));
			$sheet->setCellValue("C".$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb2))));
			$sheet->setCellValue("D".$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb3))));
			$sheet->setCellValue("E".$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb4))));
			
			$total=$Nb1+$Nb2+$Nb3+$Nb4;
			$moyenne="";
			if($total>0){
				$moyenne=round(($Nb1+($Nb2*2)+($Nb3*3)+($Nb4*4))/$total,2);
			}
			$sheet->setCellValue("F".$ligne,utf8_encode(stripslashes($moyenne)));

		//$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		//$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

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