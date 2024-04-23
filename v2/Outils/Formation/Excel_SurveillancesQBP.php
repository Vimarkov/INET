<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");
require_once("../Fonctions.php");
require_once("WorkflowDesSurveillances_requetes.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR")
{
	$sheet->setTitle(utf8_encode("Surveillances"));
	
	$sheet->setCellValue('A1',utf8_encode("Personne"));
	$sheet->setCellValue('B1',utf8_encode("Prestation(s)"));
	$sheet->setCellValue('C1',utf8_encode("Qualifications [Date début] [Date surveillance]"));
	$sheet->setCellValue('D1',utf8_encode("1ere date qualif."));
	$sheet->setCellValue('E1',utf8_encode("Dernière date surveillance"));
	$sheet->setCellValue('F1',utf8_encode("Statut"));
}
else
{
	$sheet->setTitle(utf8_encode("Monitoring"));
	$sheet->setCellValue('A1',utf8_encode("Person"));
	$sheet->setCellValue('B1',utf8_encode("Activitie(s)"));
	$sheet->setCellValue('C1',utf8_encode("Qualifications [Start date] [Monitoring date]"));
	$sheet->setCellValue('D1',utf8_encode("1st qualifying date"));
	$sheet->setCellValue('E1',utf8_encode("Last monitoring date"));
	$sheet->setCellValue('F1',utf8_encode("State"));
}

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('C')->setWidth(65);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);

$sheet->getStyle('A1:F1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:F1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:F1')->getFont()->setBold(true);
$sheet->getStyle('A1:F1')->getFont()->getColor()->setRGB('1f49a6');

$ListePersonneSelonProfilConnecte="";
if(DroitsFormationPlateforme($TableauIdPostesAFI_RF_RQ_FORM))
{
	$ListePersonneSelonProfilConnecte.="
			SELECT
				Id_Personne 
			FROM
				new_competences_personne_prestation
			LEFT JOIN new_competences_prestation 
				ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
			WHERE
				Date_Fin>='".date('Y-m-d')."'
				AND Id_Plateforme IN
				(
					SELECT Id_Plateforme
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesAFI_RF_RQ_FORM).")
				)
		";
	
}
else
{
	$ListePersonneSelonProfilConnecte.="
			SELECT
				Id_Personne  
			FROM
				new_competences_personne_prestation
			WHERE
				Date_Fin>='".date('Y-m-d')."'
				AND CONCAT(Id_Prestation,'_',Id_Pole) IN
				(
					SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
				)
		";
}

//Uniquement les WA Basic de Airbus Helicopters QBP
$req="SELECT *
	FROM
	(SELECT
		new_competences_relation.Id,
		(SELECT CONCAT (Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
		new_competences_relation.Id_Personne,
		new_competences_qualification.Id AS Id_Qualif,
		new_competences_qualification.Libelle AS Qualification,
		(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Prestation,
		(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Pole,
		(SELECT Id_Prestation FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Prestation,
		(SELECT Id_Pole FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Pole,
		new_competences_relation.Date_Debut,Date_Surveillance AS DateSurveillance,
		IF(IgnorerSurveillance=1,'IGNORE',new_competences_relation.Statut_Surveillance) AS Statut,
		Statut_Surveillance,IgnorerSurveillance,(@row_number:=@row_number + 1) AS rnk
	FROM
		new_competences_relation,
		new_competences_qualification
	WHERE
		new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
		AND new_competences_relation.Suppr = 0
		AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin>='".date('Y-m-d')."')
		AND new_competences_qualification.Id_Categorie_Qualification=147
		AND new_competences_qualification.Libelle LIKE 'WA - Basic%'
		AND new_competences_relation.Id_Personne IN
		("
			.$ListePersonneSelonProfilConnecte."
		)";
if($_SESSION['FiltreFormSurveillanceQBP_Plateforme']<>"")
{
	$req.="
		AND (SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND Date_Fin>='".date('Y-m-d')."' LIMIT 1) IN (".$_SESSION['FiltreFormSurveillanceQBP_Plateforme'].") 
		";
}
if($_SESSION['FiltreFormSurveillanceQBP_Prestation']<>"")
{
	$req.=" AND (SELECT COUNT(Id) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') AND CONCAT(Id_Prestation,'_',Id_Pole)='".$_SESSION['FiltreFormSurveillanceQBP_Prestation']."')>0 ";
}
if($_SESSION['FiltreFormSurveillanceQBP_CQP']<>""){
	$req.="
			AND CONCAT((SELECT Id_Prestation FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND Date_Fin>='".date('Y-m-d')."' LIMIT 1),'_',(SELECT Id_Pole FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND Date_Fin>='".date('Y-m-d')."' LIMIT 1)) 
				IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$_SESSION['FiltreFormSurveillanceQBP_CQP'].")
					AND Id_Poste IN (".$IdPosteReferentQualiteProduit.")
				)
				";
}

if($_SESSION['FiltreFormSurveillanceQBP_Personne']<>""){$req.="AND (SELECT CONCAT (Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) LIKE '%".$_SESSION['FiltreFormSurveillanceQBP_Personne']."%' ";}
if($_SESSION['FiltreFormSurveillanceQBP_Statut']<>""){
	$req.="AND IF(IgnorerSurveillance=1,'IGNORE',new_competences_relation.Statut_Surveillance) LIKE '%".$_SESSION['FiltreFormSurveillanceQBP_Statut']."%' ";
}
else{
	$req.="AND IF(IgnorerSurveillance=1,'IGNORE',new_competences_relation.Statut_Surveillance) IN ('','VALIDE','ECHEC') ";
}
$req.="ORDER BY Id_Personne,Date_Debut) AS TAB
	WHERE 
	IF(DateSurveillance<='0001-01-01',ADDDATE(Date_Debut,INTERVAL 1 YEAR),ADDDATE(DateSurveillance,INTERVAL 2 YEAR)) <= '".date('Y-m-d',strtotime(date('Y-m-d')." +4 month"))."'
	GROUP BY Id_Personne
	
 ";
if($_SESSION['TriFormSurveillanceQBP_General']<>""){$req.=" ORDER BY ".substr($_SESSION['TriFormSurveillanceQBP_General'],0,-1);}
$resultSurveillance=mysqli_query($bdd,$req);
$nbSurveillance=mysqli_num_rows($resultSurveillance);

$ligne=2;
if ($nbSurveillance>0)
{
	while($row=mysqli_fetch_array($resultSurveillance))
	{
		$qualifications="";
		$reqQ="SELECT
				new_competences_relation.Id,
				new_competences_qualification.Libelle AS Qualification,
				new_competences_relation.Date_Debut,
				Date_Surveillance AS DateSurveillance
			FROM
				new_competences_relation,
				new_competences_qualification
			WHERE
				new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
				AND new_competences_relation.Suppr = 0
				AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin>='".date('Y-m-d')."')
				AND new_competences_qualification.Id_Categorie_Qualification=147
				AND new_competences_qualification.Libelle LIKE 'WA - Basic%'
				AND new_competences_relation.Id_Personne=".$row['Id_Personne']." 
			ORDER BY Date_Debut";
		$resultQ=mysqli_query($bdd,$reqQ);
		$nbQ=mysqli_num_rows($resultQ);
		if($nbQ>0){
			while($rowQ = mysqli_fetch_array($resultQ)){
				$qualifications.=stripslashes($rowQ['Qualification'])." [ ".AfficheDateJJ_MM_AAAA($rowQ['Date_Debut'])."] [".AfficheDateJJ_MM_AAAA($rowQ['DateSurveillance'])."]\n";	
			}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Personne'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Prestation']." ".$row['Pole'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode($qualifications));
		$sheet->setCellValue('D'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['Date_Debut'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateSurveillance'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Statut'])));
		$sheet->getStyle('C'.$ligne)->getAlignment()->setWrapText(true);
		
		$sheet->getStyle('A'.$ligne.':F'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':F'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':F'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$ligne++;
	}
}
				
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="SurveillancesQBP.xlsx"');}
else{header('Content-Disposition: attachment;filename="Monitoring.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/SurveillancesQBP.xlsx';
$writer->save($chemin);
readfile($chemin);
?>