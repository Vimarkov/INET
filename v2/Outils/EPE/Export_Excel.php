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

$sheet->setTitle(utf8_encode("Liste"));

$sheet->setCellValue('A1',utf8_encode("Matricule Paris"));
$sheet->setCellValue('B1',utf8_encode("Personne"));
$sheet->setCellValue('C1',utf8_encode("Prestation"));
$sheet->setCellValue('D1',utf8_encode("Unité d'exploitation"));
$sheet->setCellValue('E1',utf8_encode("Responsable"));
$sheet->setCellValue('F1',utf8_encode("Priorité"));
$sheet->setCellValue('G1',utf8_encode("Type"));
$sheet->setCellValue('H1',utf8_encode("Date butoir"));
$sheet->setCellValue('I1',utf8_encode("Date prévisionnelle"));
$sheet->setCellValue('J1',utf8_encode("Etat"));
if(DroitsPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){
	$sheet->setCellValue('K1',utf8_encode("Pris en compte"));
}
$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(10);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(20);

$sheet->getStyle('A1:K1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:K1')->getFont()->setBold(true);
$sheet->getStyle('A1:K1')->getFont()->getColor()->setRGB('1f49a6');

$annee=$_SESSION['FiltreEPE_Annee'];
$dateDebut=date($annee.'-01-01');
$dateFin=date($annee.'-12-31');
					
$requete2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,DateAncienneteCDI,
IF((SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.TypeEntretien='EPP Bilan' AND YEAR(IF(TAB.DateReport>'0001-01-01',TAB.DateReport,TAB.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." AND
TAB.Id_Personne=new_rh_etatcivil.Id)>0 AND
IF(
	(SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type='EPP Bilan' AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
FROM epe_personne 
WHERE Suppr=0 AND epe_personne.Type='EPP Bilan' AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
'A faire') IN ('A faire','Brouillon'),1,2) AS Priorite
	";
$requete="FROM new_rh_etatcivil
	RIGHT JOIN epe_personne_datebutoir 
	ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
	WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
	OR 
		(SELECT COUNT(Id)
		FROM epe_personne 
		WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
	) 
	AND 
		(
			SELECT COUNT(new_competences_personne_prestation.Id)
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
			WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
			AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
			AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		)>0 
		AND 
		(
			SELECT Id_Prestation
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
			WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
			AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
			AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			ORDER BY Date_Fin DESC, Date_Debut DESC
			LIMIT 1
		) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		";
if($_SESSION['FiltreEPE_Personne']<>"0"){
	$requete.="AND new_rh_etatcivil.Id =".$_SESSION['FiltreEPE_Personne']." ";
}
$requete.="AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
	$requete.="AND TypeEntretien IN (";
	$lesTypes="";
	if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
	if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
	if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
	$requete.=$lesTypes.")";
}
if($_SESSION['FiltreEPE_EtatAF']<>"" || $_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
	$requete.="AND IF((SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
	(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
	'A faire') IN ( ";
	$lesTypes="";
	if($_SESSION['FiltreEPE_EtatAF']<>""){$lesTypes.="'A faire'";}
	if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
	if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
	if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
	if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
	$requete.=$lesTypes.") ";
}
if($_SESSION['FiltreEPE_Priorite']<>"0"){
			$requete.=" AND IF((SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.TypeEntretien='EPP Bilan' AND YEAR(IF(TAB.DateReport>'0001-01-01',TAB.DateReport,TAB.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." AND
		TAB.Id_Personne=new_rh_etatcivil.Id)>0 AND
		IF(
			(SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type='EPP Bilan' AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
		(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
		FROM epe_personne 
		WHERE Suppr=0 AND epe_personne.Type='EPP Bilan' AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
		'A faire') IN ('A faire','Brouillon'),1,2) = ".$_SESSION['FiltreEPE_Priorite']." ";
		}
$requete.="ORDER BY Personne ";
$result=mysqli_query($bdd,$requete2.$requete);
$nbResulta=mysqli_num_rows($result);

$req="DROP TEMPORARY TABLE TMP_EPE;";
$ResultD=mysqli_query($bdd,$req);

$req="CREATE TEMPORARY TABLE TMP_EPE (Id INT(11),Personne VARCHAR(255),MatriculeAAA VARCHAR(255),DateAncienneteCDI DATE,Priorite INT(11),Id_Prestation INT(11),Id_Pole INT(11),Id_Plateforme INT(11),Id_Manager INT(11),Prestation VARCHAR(255),Pole VARCHAR(255),Plateforme VARCHAR(255),Manager VARCHAR(255));";
$resultC=mysqli_query($bdd,$req);

if($nbResulta>0){
	while($row=mysqli_fetch_array($result))
	{
		$laDateCloture=date('Y-m-d');
		$dateCloture="";
		$req="SELECT DateCloture FROM epe_cloturecampagne WHERE Annee=".$_SESSION['FiltreEPE_Annee']." ";
		$resultDateCloture=mysqli_query($bdd,$req);
		$nbDateCloture=mysqli_num_rows($resultDateCloture);
		if($nbDateCloture>0){
			$rowDateCloture=mysqli_fetch_array($resultDateCloture);
			$dateCloture=$rowDateCloture['DateCloture'];
			$laDateCloture=$dateCloture;
		}
		
		$req="SELECT Id_Prestation,Id_Pole 
			FROM new_competences_personne_prestation
			WHERE Id_Personne=".$row['Id']." 
			AND new_competences_personne_prestation.Date_Debut<='".$laDateCloture."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$laDateCloture."') 
			ORDER BY Date_Fin DESC, Date_Debut DESC ";
		$resultch=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($resultch);
		
		if($nb==0){
			$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
			TypeEntretien AS TypeE,
			IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
			epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
			IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
			'A faire')
			AS Etat,
			(SELECT Id_Evaluateur
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
			(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
			(SELECT Id
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_PersonneEPE,
			(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme,
			(SELECT LectureRH
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS LectureRH,
			(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole
			FROM new_rh_etatcivil
			RIGHT JOIN epe_personne_datebutoir 
			ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
			WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
			OR 
				(SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
			) 
			AND new_rh_etatcivil.Id=".$row['Id']."
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
			if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
				$reqNb.="AND TypeEntretien IN (";
				$lesTypes="";
				if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
				if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
				if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
				$reqNb.=$lesTypes.")";
			}
			$reqNb.="AND IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire') NOT IN ('A faire') ";
			if($_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
				$reqNb.="AND IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire') IN ( ";
				$lesTypes="";
				if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
				if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
				if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
				if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
				$reqNb.=$lesTypes.") ";
			}
		}
		else{
			$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
			TypeEntretien AS TypeE,
			IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
			epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
			IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
			'A faire')
			AS Etat,
			(SELECT Id_Evaluateur
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
			(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
			(SELECT Id
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_PersonneEPE,
			(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme,
			(SELECT LectureRH
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS LectureRH,
			(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole
			FROM new_rh_etatcivil
			RIGHT JOIN epe_personne_datebutoir 
			ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
			WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
			OR 
				(SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
			)
			AND new_rh_etatcivil.Id=".$row['Id']."
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
			if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
				$reqNb.="AND TypeEntretien IN (";
				$lesTypes="";
				if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
				if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
				if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
				$reqNb.=$lesTypes.")";
			}
			if($_SESSION['FiltreEPE_EtatAF']<>"" || $_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
				$reqNb.="AND IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire') IN ( ";
				$lesTypes="";
				if($_SESSION['FiltreEPE_EtatAF']<>""){$lesTypes.="'A faire'";}
				if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
				if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
				if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
				if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
				$reqNb.=$lesTypes.") ";
			}
		}
		$ResultNb=mysqli_query($bdd,$reqNb);
		$leNb=mysqli_num_rows($ResultNb);
		
		$Manager="";
		$Id_Manager=0;
				
		if($leNb>0){
			$rowNb=mysqli_fetch_array($ResultNb);
			
			$Id_Prestation=0;
			$Id_Pole=0;
			$Id_Plateforme=0;
			
			$req="SELECT Id_Prestation,Id_Pole 
				FROM new_competences_personne_prestation
				WHERE Id_Personne=".$row['Id']." 
				AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."') 
				ORDER BY Date_Fin DESC, Date_Debut DESC";
			$resultch=mysqli_query($bdd,$req);
			$nb=mysqli_num_rows($resultch);
			$Id_PrestationPole="0_0";
			if($nb>0){
				$rowMouv=mysqli_fetch_array($resultch);
				$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
			}

			$TableauPrestationPole=explode("_",$Id_PrestationPole);
			$Id_Prestation=$TableauPrestationPole[0];
			$Id_Pole=$TableauPrestationPole[1];
			
			$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation." ";
			$ResultPlat=mysqli_query($bdd,$req);
			$NbPlat=mysqli_num_rows($ResultPlat);
			if($NbPlat>0){
				$RowPlat=mysqli_fetch_array($ResultPlat);
				$Id_Plateforme=$RowPlat['Id_Plateforme'];
			}
			if($rowNb['Etat']=="A faire"){
				$req="SELECT Id_Prestation,Id_Pole 
					FROM new_competences_personne_prestation
					WHERE Id_Personne=".$row['Id']." 
					AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
					AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') 
					ORDER BY Date_Fin DESC, Date_Debut DESC ";
				
				$resultch=mysqli_query($bdd,$req);
				$lenb=mysqli_num_rows($resultch);
				
				if($lenb>1){
					$req="SELECT Id_Prestation, Id_Pole, (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ORDER BY Id DESC";
					$ResultlaPresta=mysqli_query($bdd,$req);
					$NblaPresta=mysqli_num_rows($ResultlaPresta);
					if($NblaPresta>0){
						$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
						$Id_Prestation=$RowlaPresta['Id_Prestation'];
						$Id_Pole=$RowlaPresta['Id_Pole'];
						$Id_Plateforme=$RowlaPresta['Id_Plateforme'];
					}
				}
			}
			else{
				$tab = explode("_",$rowNb['PrestaPole']);
				$Id_Prestation=$tab[0];
				$Id_Pole=$tab[1];
				$Id_Plateforme=$rowNb['Id_Plateforme'];
			}
			
			$Presta="";
			$Plateforme="";
			$req="SELECT LEFT(Libelle,7) AS Prestation, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
			$ResultPresta=mysqli_query($bdd,$req);
			$NbPrest=mysqli_num_rows($ResultPresta);
			if($NbPrest>0){
				$RowPresta=mysqli_fetch_array($ResultPresta);
				$Presta=$RowPresta['Prestation'];
				$Plateforme=$RowPresta['Plateforme'];
			}
			
			$Pole="";
			$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
			$ResultPole=mysqli_query($bdd,$req);
			$NbPole=mysqli_num_rows($ResultPole);
			if($NbPole>0){
				$RowPole=mysqli_fetch_array($ResultPole);
				$Pole=$RowPole['Libelle'];
			}
			
			if($Pole<>""){$Presta.=" - ".$Pole;}


			if($rowNb['Etat']=="A faire"){
				$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
				$ResultlaPresta=mysqli_query($bdd,$req);
				$NblaPresta=mysqli_num_rows($ResultlaPresta);
				if($NblaPresta>0){
					$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
					$Id_Manager=$RowlaPresta['Id_Manager'];
					$Manager=$RowlaPresta['Manager'];
				}
				else{
					$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
							FROM new_competences_personne_poste_prestation 
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
							AND Id_Prestation=".$Id_Prestation."
							AND Id_Pole=".$Id_Pole."
							AND Id_Personne=".$row['Id']."
							AND Id_Personne>0
							ORDER BY Backup ";
					$ResultManager2=mysqli_query($bdd,$req);
					$NbManager2=mysqli_num_rows($ResultManager2);
					if($NbManager2>0){
						$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
							FROM new_competences_personne_poste_prestation 
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE Id_Poste=".$IdPosteCoordinateurProjet."
							AND Id_Prestation=".$Id_Prestation."
							AND Id_Pole=".$Id_Pole."
							AND Id_Personne>0
							ORDER BY Backup ";
						$ResultManager=mysqli_query($bdd,$req);
						$NbManager=mysqli_num_rows($ResultManager);
						if($NbManager>0){
							$RowManager=mysqli_fetch_array($ResultManager);
							$Manager=$RowManager['Personne'];
							$Id_Manager=$RowManager['Id'];
						}
					}
					else{
						$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
							FROM new_competences_personne_poste_prestation 
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE Id_Poste=".$IdPosteChefEquipe."
							AND Id_Prestation=".$Id_Prestation."
							AND Id_Pole=".$Id_Pole."
							AND Id_Personne=".$row['Id']."
							AND Id_Personne>0
							ORDER BY Backup ";
						$ResultManager2=mysqli_query($bdd,$req);
						$NbManager2=mysqli_num_rows($ResultManager2);
						if($NbManager2>0){
							$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
								FROM new_competences_personne_poste_prestation 
								LEFT JOIN new_rh_etatcivil
								ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
								WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
								AND Id_Prestation=".$Id_Prestation."
								AND Id_Pole=".$Id_Pole."
								AND Id_Personne>0
								ORDER BY Backup ";
							$ResultManager=mysqli_query($bdd,$req);
							$NbManager=mysqli_num_rows($ResultManager);
							if($NbManager>0){
								$RowManager=mysqli_fetch_array($ResultManager);
								$Manager=$RowManager['Personne'];
								$Id_Manager=$RowManager['Id'];
							}
						}
						else{
							$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
							FROM new_competences_personne_poste_prestation 
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE Id_Poste=".$IdPosteChefEquipe."
							AND Id_Prestation=".$Id_Prestation."
							AND Id_Pole=".$Id_Pole."
							AND Id_Personne>0
							ORDER BY Backup ";
							$ResultManager=mysqli_query($bdd,$req);
							$NbManager=mysqli_num_rows($ResultManager);
							if($NbManager>0){
								$RowManager=mysqli_fetch_array($ResultManager);
								$Manager=$RowManager['Personne'];
								$Id_Manager=$RowManager['Id'];
							}
						}
					}
				}
			}
			else{
				$Manager=$rowNb['Manager'];
				$Id_Manager=$rowNb['Id_Manager'];
			}
			
			$req= "INSERT INTO TMP_EPE (Id,Personne,MatriculeAAA,DateAncienneteCDI,Priorite,Id_Prestation,Id_Pole,Id_Plateforme,Id_Manager,Prestation,Pole,Plateforme,Manager)
				VALUES (".$row['Id'].",'".addslashes($row['Personne'])."','".$row['MatriculeAAA']."','".$row['DateAncienneteCDI']."',".$row['Priorite'].",".$Id_Prestation.",".$Id_Pole.",".$Id_Plateforme.",".$Id_Manager.",'".addslashes($Presta)."','".addslashes($Pole)."','".addslashes($Plateforme)."','".addslashes($Manager)."');";
			$ResultI=mysqli_query($bdd,$req);
		}

	}
}

$requete2="SELECT Id,Personne,MatriculeAAA,DateAncienneteCDI,Priorite,Id_Prestation,Id_Pole,Id_Plateforme,Id_Manager,Prestation,Pole,Plateforme,Manager ";
$requete="FROM TMP_EPE 
";
if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
	if($_SESSION['FiltreEPE_Manager']<>"0" || $_SESSION['FiltreEPE_Plateforme']<>"0"){
		$requete.=" WHERE ";
		if($_SESSION['FiltreEPE_Manager']<>"0"){
			$requete.="TMP_EPE.Id_Manager=".$_SESSION['FiltreEPE_Manager']." AND ";
		}
		if($_SESSION['FiltreEPE_Plateforme']<>"0"){
			$requete.="TMP_EPE.Id_Plateforme=".$_SESSION['FiltreEPE_Plateforme']." AND ";
		}
		if($_SESSION['FiltreEPE_Prestation']<>"0"){
			$requete.="TMP_EPE.Id_Prestation=".$_SESSION['FiltreEPE_Prestation']." AND ";
		}
		if($_SESSION['FiltreEPE_Pole']<>"0"){
			$requete.="TMP_EPE.Id_Pole=".$_SESSION['FiltreEPE_Pole']." AND ";
		}
		$requete=substr($requete,0,-4);
	}
}
else{
	if($_SESSION['FiltreEPE_AffichageBackup']<>""){
		$requete.="
		WHERE 
		(TMP_EPE.Id_Plateforme IN 
		(
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
		)
		OR CONCAT(TMP_EPE.Id_Prestation,'_',TMP_EPE.Id_Pole) IN 
		(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
		FROM new_competences_personne_poste_prestation 
		WHERE Id_Personne=".$_SESSION["Id_Personne"]."
		AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
		)
		OR TMP_EPE.Id=".$_SESSION['Id_Personne'].")
		";
	}
	else{
		$requete.="
		WHERE 
		(TMP_EPE.Id_Plateforme IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)
			OR TMP_EPE.Id_Manager=".$_SESSION['Id_Personne']."
			OR TMP_EPE.Id=".$_SESSION['Id_Personne'].")
		";
	}
	if($_SESSION['FiltreEPE_Manager']<>"0"){
		$requete.=" AND TMP_EPE.Id_Manager=".$_SESSION['FiltreEPE_Manager']." ";
	}
	if($_SESSION['FiltreEPE_Plateforme']<>"0"){
		$requete.=" AND TMP_EPE.Id_Plateforme=".$_SESSION['FiltreEPE_Plateforme']." ";
	}
	if($_SESSION['FiltreEPE_Prestation']<>"0"){
			$requete.="AND TMP_EPE.Id_Prestation=".$_SESSION['FiltreEPE_Prestation']." ";
		}
		if($_SESSION['FiltreEPE_Pole']<>"0"){
			$requete.="AND TMP_EPE.Id_Pole=".$_SESSION['FiltreEPE_Pole']." ";
		}
}
$requete.="ORDER BY Personne ";

$result=mysqli_query($bdd,$requete2.$requete);
$nbResulta=mysqli_num_rows($result);

if($nbResulta>0){
	$ligne=2;
	while($row=mysqli_fetch_array($result)){
		
		$laDateCloture=date('Y-m-d');
		$dateCloture="";
		$req="SELECT DateCloture FROM epe_cloturecampagne WHERE Annee=".$_SESSION['FiltreEPE_Annee']." ";
		$resultDateCloture=mysqli_query($bdd,$req);
		$nbDateCloture=mysqli_num_rows($resultDateCloture);
		if($nbDateCloture>0){
			$rowDateCloture=mysqli_fetch_array($resultDateCloture);
			$dateCloture=$rowDateCloture['DateCloture'];
			$laDateCloture=$dateCloture;
		}
		
		$req="SELECT Id_Prestation,Id_Pole 
			FROM new_competences_personne_prestation
			WHERE Id_Personne=".$row['Id']." 
			AND new_competences_personne_prestation.Date_Debut<='".$laDateCloture."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$laDateCloture."') 
			ORDER BY Date_Fin DESC, Date_Debut DESC ";
		$resultch=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($resultch);
		
		if($nb==0){
			$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
			TypeEntretien AS TypeE,
			IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
			epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,DatePrevisionnelle,
			IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
			'A faire')
			AS Etat,
			(SELECT Id_Evaluateur
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
			(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
			(SELECT Id
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_PersonneEPE,
			(SELECT Id_Plateforme
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme,
			(SELECT LectureRH
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS LectureRH,
			(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole,
			(SELECT COUNT(epe_personne_attente.Id) FROM epe_personne_attente WHERE epe_personne_attente.Id_Personne=new_rh_etatcivil.Id AND epe_personne_attente.Annee=".$_SESSION['FiltreEPE_Annee']."
			AND epe_personne_attente.TypeEntretien=epe_personne_datebutoir.TypeEntretien) AS EnAttente
			FROM new_rh_etatcivil
			RIGHT JOIN epe_personne_datebutoir 
			ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
			WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
			OR 
				(SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
			) 
			AND new_rh_etatcivil.Id=".$row['Id']."
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
			if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
				$reqNb.="AND TypeEntretien IN (";
				$lesTypes="";
				if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
				if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
				if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
				$reqNb.=$lesTypes.")";
			}
			$reqNb.="AND IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire') NOT IN ('A faire') ";
			if($_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
				$reqNb.="AND IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire') IN ( ";
				$lesTypes="";
				if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
				if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
				if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
				if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
				$reqNb.=$lesTypes.") ";
			}
		}
		else{
			$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
			TypeEntretien AS TypeE,
			IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
			epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,DatePrevisionnelle,
			IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
			'A faire')
			AS Etat,
			(SELECT Id_Evaluateur
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
			(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
			(SELECT Id
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_PersonneEPE,
			(SELECT Id_Plateforme
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme,
			(SELECT LectureRH
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS LectureRH,
			(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole,
			(SELECT COUNT(epe_personne_attente.Id) FROM epe_personne_attente WHERE epe_personne_attente.Id_Personne=new_rh_etatcivil.Id AND epe_personne_attente.Annee=".$_SESSION['FiltreEPE_Annee']."
			AND epe_personne_attente.TypeEntretien=epe_personne_datebutoir.TypeEntretien) AS EnAttente
			FROM new_rh_etatcivil
			RIGHT JOIN epe_personne_datebutoir 
			ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
			WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
			OR 
				(SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
			) 
			AND new_rh_etatcivil.Id=".$row['Id']."
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
			if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
				$reqNb.="AND TypeEntretien IN (";
				$lesTypes="";
				if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
				if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
				if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
				$reqNb.=$lesTypes.")";
			}
			if($_SESSION['FiltreEPE_EtatAF']<>"" || $_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
				$reqNb.="AND IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire') IN ( ";
				$lesTypes="";
				if($_SESSION['FiltreEPE_EtatAF']<>""){$lesTypes.="'A faire'";}
				if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
				if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
				if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
				if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
				$reqNb.=$lesTypes.") ";
			}
		}
		$ResultNb=mysqli_query($bdd,$reqNb);
		$leNb=mysqli_num_rows($ResultNb);
		
		$rowNb=mysqli_fetch_array($ResultNb);
		
		$Id_Prestation=0;
		$Id_Pole=0;

		$req="SELECT Id_Prestation,Id_Pole 
			FROM new_competences_personne_prestation
			WHERE Id_Personne=".$row['Id']." 
			AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."') 
			ORDER BY Date_Fin DESC, Date_Debut DESC
			";
		$resultch=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($resultch);
		$Id_PrestationPole="0_0";
		if($nb>0){
			$rowMouv=mysqli_fetch_array($resultch);
			$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
		}
		
		$TableauPrestationPole=explode("_",$Id_PrestationPole);
		$Id_Prestation=$TableauPrestationPole[0];
		$Id_Pole=$TableauPrestationPole[1];
		

		if($rowNb['Etat']=="A faire"){
			$req="SELECT Id_Prestation,Id_Pole 
				FROM new_competences_personne_prestation
				WHERE Id_Personne=".$row['Id']." 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') 
				ORDER BY Date_Fin DESC, Date_Debut DESC ";
			
			$resultch=mysqli_query($bdd,$req);
			$lenb=mysqli_num_rows($resultch);
			
			if($lenb>1){
				$req="SELECT Id_Prestation, Id_Pole, (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager=0 AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
				$ResultlaPresta=mysqli_query($bdd,$req);
				$NblaPresta=mysqli_num_rows($ResultlaPresta);
				if($NblaPresta>0){
					$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
					$Id_Prestation=$RowlaPresta['Id_Prestation'];
					$Id_Pole=$RowlaPresta['Id_Pole'];
					$Id_Plateforme=$RowlaPresta['Id_Plateforme'];
				}
			}
		}
		else{
			$tab = explode("_",$rowNb['PrestaPole']);
			$Id_Prestation=$tab[0];
			$Id_Pole=$tab[1];
			$Id_Plateforme=$rowNb['Id_Plateforme'];
		}
		
		$Presta="";
		$Plateforme="";
		$req="SELECT LEFT(Libelle,7) AS Prestation, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
		$ResultPresta=mysqli_query($bdd,$req);
		$NbPrest=mysqli_num_rows($ResultPresta);
		if($NbPrest>0){
			$RowPresta=mysqli_fetch_array($ResultPresta);
			$Presta=$RowPresta['Prestation'];
			$Plateforme=$RowPresta['Plateforme'];
		}
		
		$Pole="";
		$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
		$ResultPole=mysqli_query($bdd,$req);
		$NbPole=mysqli_num_rows($ResultPole);
		if($NbPole>0){
			$RowPole=mysqli_fetch_array($ResultPole);
			$Pole=$RowPole['Libelle'];
		}
		
		if($Pole<>""){$Presta.=" - ".$Pole;}
		
		$Manager="";
		$Id_Manager=0;
		if($rowNb['Etat']=="A faire"){
			$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
			$ResultlaPresta=mysqli_query($bdd,$req);
			$NblaPresta=mysqli_num_rows($ResultlaPresta);
			if($NblaPresta>0){
				$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
				$Id_Manager=$RowlaPresta['Id_Manager'];
				$Manager=$RowlaPresta['Manager'];
			}
			else{
				$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						AND Id_Personne=".$row['Id']."
						ORDER BY Backup ";
				$ResultManager2=mysqli_query($bdd,$req);
				$NbManager2=mysqli_num_rows($ResultManager2);
				if($NbManager2>0){
					$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE Id_Poste=".$IdPosteCoordinateurProjet."
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						ORDER BY Backup ";
					$ResultManager=mysqli_query($bdd,$req);
					$NbManager=mysqli_num_rows($ResultManager);
					if($NbManager>0){
						$RowManager=mysqli_fetch_array($ResultManager);
						$Manager=$RowManager['Personne'];
						$Id_Manager=$RowManager['Id'];
					}
				}
				else{
					$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE Id_Poste=".$IdPosteChefEquipe."
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						AND Id_Personne=".$row['Id']."
						ORDER BY Backup ";
					$ResultManager2=mysqli_query($bdd,$req);
					$NbManager2=mysqli_num_rows($ResultManager2);
					if($NbManager2>0){
						$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
							FROM new_competences_personne_poste_prestation 
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
							AND Id_Prestation=".$Id_Prestation."
							AND Id_Pole=".$Id_Pole."
							ORDER BY Backup ";
						$ResultManager=mysqli_query($bdd,$req);
						$NbManager=mysqli_num_rows($ResultManager);
						if($NbManager>0){
							$RowManager=mysqli_fetch_array($ResultManager);
							$Manager=$RowManager['Personne'];
							$Id_Manager=$RowManager['Id'];
						}
					}
					else{
						$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE Id_Poste=".$IdPosteChefEquipe."
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						ORDER BY Backup ";
						$ResultManager=mysqli_query($bdd,$req);
						$NbManager=mysqli_num_rows($ResultManager);
						if($NbManager>0){
							$RowManager=mysqli_fetch_array($ResultManager);
							$Manager=$RowManager['Personne'];
							$Id_Manager=$RowManager['Id'];
						}
					}
				}
			}
		}
		else{
			$Manager=$rowNb['Manager'];
			$Id_Manager=$rowNb['Id_Manager'];
		}
		
		if(DroitsPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || $Id_Manager==$_SESSION['Id_Personne'] || $row['Id']==$_SESSION['Id_Personne'] || $_SESSION['FiltreEPE_AffichageBackup']<>""){
			if($_SESSION['FiltreEPE_Manager']=="0" || $_SESSION['FiltreEPE_Manager']==$Id_Manager){
				$ResultNb=mysqli_query($bdd,$reqNb);
				if($leNb>0){
					while($rowNb=mysqli_fetch_array($ResultNb))
					{
						if(DroitsPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){
							$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
						}
						
						$sheet->setCellValue('B'.$ligne,utf8_encode($row['Personne']));
						$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Prestation'])));
						$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Plateforme'])));
						$sheet->setCellValue('E'.$ligne,utf8_encode($row['Manager']));
						$sheet->setCellValue('F'.$ligne,utf8_encode($row['Priorite']));
						
						$sheet->setCellValue('G'.$ligne,utf8_encode($rowNb['TypeEntretien']));
						if($rowNb['DateButoir']>'0001-01-01'){
							$date = explode("-",$rowNb['DateButoir']);
							$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
							$sheet->setCellValue('H'.$ligne,$time);
							$sheet->getStyle('H'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
						}
						if($rowNb['DatePrevisionnelle']>'0001-01-01'){
							$date = explode("-",$rowNb['DatePrevisionnelle']);
							$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
							$sheet->setCellValue('I'.$ligne,$time);
							$sheet->getStyle('I'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
						}
						$sheet->setCellValue('J'.$ligne,utf8_encode($rowNb['Etat']));

						if(DroitsPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){
							if($rowNb['Id_Plateforme']>0 && $rowNb['Etat']=='Réalisé'){
								if(DroitsFormation1Plateforme($rowNb['Id_Plateforme'],array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
									if($rowNb['LectureRH']==1)
									{
										$sheet->setCellValue('K'.$ligne,utf8_encode("X"));
									}
								}
							}
						}
						
						$sheet->getStyle('A'.$ligne.':K'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$sheet->getStyle('A'.$ligne.':K'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$sheet->getStyle('A'.$ligne.':K'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
						
						$ligne++;
					}
				}
			}
		}
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