<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");
require_once("../Fonctions.php");

$dateDebut=TrsfDate_($_SESSION['FiltreAvancementBesoin_Date']);

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
	$sheet->setTitle(utf8_encode("Besoins"));
	
	$sheet->setCellValue('A1',utf8_encode("Formation"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Nombre de besoins au ".AfficheDateJJ_MM_AAAA($dateDebut)));
	$sheet->setCellValue('D1',utf8_encode("Nombre de besoins traités"));
	$sheet->setCellValue('E1',utf8_encode("% avancement"));
}
else{
	$sheet->setTitle(utf8_encode("Needs"));
	$sheet->setCellValue('A1',utf8_encode("Training"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Number of requirements as of ".AfficheDateJJ_MM_AAAA($dateDebut)));
	$sheet->setCellValue('D1',utf8_encode("Number of needs processed"));
	$sheet->setCellValue('E1',utf8_encode("% advancement"));
}

$sheet->getColumnDimension('A')->setWidth(100);

$req="
SELECT 
	TAB.Libelle,
	TAB.Organisme,
	TAB.TypeFormation,
	TAB.Id_Formation,
	TAB.Recyclage,
	TAB.NombreFormation,
	TAB.NbTraite,
	ROUND((TAB.NbTraite/TAB.NombreFormation)*100) AS Avancement
	
FROM 
(
	SELECT TAB_BESOINSDEBUT.Libelle,
	TAB_BESOINSDEBUT.Organisme,
	TAB_BESOINSDEBUT.TypeFormation,
	TAB_BESOINSDEBUT.Id_Formation,
	TAB_BESOINSDEBUT.Recyclage,
	TAB_BESOINSDEBUT.NombreFormation,
	(SELECT COUNT(form_besoin.Id)
FROM
	form_besoin,
	form_formation
WHERE
	form_besoin.Id_Formation=form_formation.Id
	AND form_besoin.Id_Formation=TAB_BESOINSDEBUT.Id_Formation
	AND IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,1,0)=TAB_BESOINSDEBUT.Recyclage
	AND form_besoin.Suppr=0
	AND form_besoin.Valide=1
	AND form_besoin.Date_Demande<='".$dateDebut."'
	AND (Traite>0 
		AND (
			SELECT COUNT(form_session_date.Id)
			FROM form_session_date,
			form_session_personne
			WHERE form_session_personne.Suppr=0
			AND form_session_personne.Id_Besoin=form_besoin.Id 
			AND form_session_personne.Id_Session=form_session_date.Id_Session
			AND form_session_personne.Validation_Inscription<>-1
			AND form_session_date.DateSession>='".$dateDebut."'
		)>0
		AND
		(
			SELECT COUNT(new_competences_relation.Id)
			FROM new_competences_relation
			WHERE new_competences_relation.Suppr=0
			AND new_competences_relation.Id_Besoin=form_besoin.Id 
			AND new_competences_relation.Date_QCM>='".$dateDebut."'
			AND new_competences_relation.Evaluation NOT IN ('B','')
		)>0
	) ";
if($_SESSION['FiltreAvancementBesoin_TypeContrat']<>""){
	$req.=" AND IF((SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=form_besoin.Date_Demande
			AND (DateFin>=form_besoin.Date_Demande OR DateFin<='0001-01-01' )
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=form_besoin.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) IS NULL,'NULL',(SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=form_besoin.Date_Demande
			AND (DateFin>=form_besoin.Date_Demande OR DateFin<='0001-01-01' )
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=form_besoin.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1)) IN (".$_SESSION['FiltreAvancementBesoin_TypeContrat'].") ";
}
	$req.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) IN (".$_SESSION['FiltreAvancementBesoin_Plateforme'].")) AS NbTraite
	FROM
	(SELECT 
	form_typeformation.Libelle AS TypeFormation,
	form_besoin.Id_Formation,
	form_formation.Reference AS REFERENCE_FORMATION,
	(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
		WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
		AND Suppr=0 LIMIT 1) AS Organisme,
	 IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,1,0) AS Recyclage,
	(SELECT IF(form_besoin.Motif='Renouvellement',
			IF(LibelleRecyclage='',Libelle,LibelleRecyclage),
			Libelle
			)
		FROM form_formation_langue_infos
		WHERE form_formation_langue_infos.Id_Formation=form_besoin.Id_Formation
		AND form_formation_langue_infos.Id_Langue=
			(SELECT Id_Langue 
			FROM form_formation_plateforme_parametres 
			WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
			AND Id_Formation=form_besoin.Id_Formation
			AND Suppr=0 
			LIMIT 1)
		AND Suppr=0) AS Libelle,
	COUNT(form_besoin.Id) AS NombreFormation
FROM
	form_besoin,
	form_typeformation,
	form_formation,
	new_competences_prestation
WHERE
	form_besoin.Id_Formation=form_formation.Id
	AND form_formation.Id_TypeFormation=form_typeformation.Id
	AND form_besoin.Id_Prestation=new_competences_prestation.Id
	AND form_besoin.Suppr=0
	AND form_besoin.Valide=1
	AND form_besoin.Date_Demande<='".$dateDebut."'
	AND (form_besoin.Traite=0
	OR (
		Traite>0 
		AND (
			SELECT COUNT(form_session_date.Id)
			FROM form_session_date,
			form_session_personne
			WHERE form_session_personne.Suppr=0
			AND form_session_personne.Id_Besoin=form_besoin.Id 
			AND form_session_personne.Id_Session=form_session_date.Id_Session
			AND form_session_personne.Validation_Inscription<>-1
			AND form_session_date.DateSession>='".$dateDebut."' 
		)>0
		AND
		(
			SELECT COUNT(new_competences_relation.Id)
			FROM new_competences_relation
			WHERE new_competences_relation.Suppr=0
			AND new_competences_relation.Id_Besoin=form_besoin.Id 
			AND new_competences_relation.Date_QCM>='".$dateDebut."'
			AND new_competences_relation.Evaluation NOT IN ('B','') 
		)>0
		
		)
	)
	AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) IN (".$_SESSION['FiltreAvancementBesoin_Plateforme'].")
	 ";
if($_SESSION['FiltreAvancementBesoin_Type']<>""){
	$req.=" AND form_formation.Id_TypeFormation IN (".$_SESSION['FiltreAvancementBesoin_Type'].") ";
}

if($_SESSION['FiltreAvancementBesoin_Categorie']<>""){
	$req.=" AND form_formation.Categorie IN (".$_SESSION['FiltreAvancementBesoin_Categorie'].") ";
}
if($_SESSION['FiltreAvancementBesoin_Formation']<>"" && $_SESSION['FiltreAvancementBesoin_Formation']<>"0_0"){
	$tabQual=explode("_",$_SESSION['FiltreAvancementBesoin_Formation']);
	if($tabQual[1]==0){
		$req.=" AND form_besoin.Id_Formation=".$tabQual[0]." ";
	}
	else{
		$req.=" AND form_besoin.Id_Formation IN 
			(SELECT Id_Formation 
			FROM form_formationequivalente_formationplateforme 
			WHERE Id_FormationEquivalente=".$tabQual[0].") ";
	}
}
if($_SESSION['FiltreAvancementBesoin_TypeContrat']<>""){
	$req.=" AND IF((SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=form_besoin.Date_Demande
			AND (DateFin>=form_besoin.Date_Demande OR DateFin<='0001-01-01' )
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=form_besoin.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) IS NULL,'NULL',(SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=form_besoin.Date_Demande
			AND (DateFin>=form_besoin.Date_Demande OR DateFin<='0001-01-01' )
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=form_besoin.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1)) IN (".$_SESSION['FiltreAvancementBesoin_TypeContrat'].") ";
}						
$req.="GROUP BY Libelle, Organisme)
	AS TAB_BESOINSDEBUT) AS TAB
ORDER BY Avancement DESC ";

$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
	$ligne=2;
	while($row=mysqli_fetch_array($result)){
		$organisme="";
		if($row['Organisme']<>""){
			$organisme=" ".$row['Organisme'];
		}
		$abscisse=utf8_encode($row['Libelle'].$organisme);
		$Avancement=$row['Avancement'];
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Libelle'].$organisme));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['TypeFormation']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['NombreFormation']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['NbTraite']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['Avancement']."%"));
	
		$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':E'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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