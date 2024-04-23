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

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Besoins"));
	
	$sheet->setCellValue('A1',utf8_encode("Reference"));
	$sheet->setCellValue('B1',utf8_encode("Responsable"));
	$sheet->setCellValue('C1',utf8_encode("Code analytique"));
	$sheet->setCellValue('D1',utf8_encode("Matricule"));
	$sheet->setCellValue('E1',utf8_encode("Personne"));
	$sheet->setCellValue('F1',utf8_encode("Contrat"));
	$sheet->setCellValue('G1',utf8_encode("ETT"));
	$sheet->setCellValue('H1',utf8_encode("Date fin contrat"));
	$sheet->setCellValue('I1',utf8_encode("CSP"));
	$sheet->setCellValue('J1',utf8_encode("Sexe"));
	$sheet->setCellValue('K1',utf8_encode("Âge"));
	$sheet->setCellValue('L1',utf8_encode("Salaire horaire chargé"));
	$sheet->setCellValue('M1',utf8_encode("Intitulé"));
	$sheet->setCellValue('N1',utf8_encode("Interne/Externe"));
	$sheet->setCellValue('O1',utf8_encode("Organisme"));
	$sheet->setCellValue('P1',utf8_encode("Type de cours"));
	$sheet->setCellValue('Q1',utf8_encode("Catégorie"));
	$sheet->setCellValue('R1',utf8_encode("Inter/Intra"));
	$sheet->setCellValue('S1',utf8_encode("Date début"));
	$sheet->setCellValue('T1',utf8_encode("Date fin"));
	$sheet->setCellValue('U1',utf8_encode("Nb heures"));
	$sheet->setCellValue('V1',utf8_encode("Nb jours"));
	$sheet->setCellValue('W1',utf8_encode("Coût pédagogique"));
	$sheet->setCellValue('X1',utf8_encode("Coût salarial"));
	$sheet->setCellValue('Y1',utf8_encode("Dde prise en charge envoyée"));
	$sheet->setCellValue('Z1',utf8_encode("Accord de prise en charge"));
	$sheet->setCellValue('AA1',utf8_encode("Traitement convention"));
	$sheet->setCellValue('AB1',utf8_encode("Présent (P)/Absent (A)"));
	$sheet->setCellValue('AC1',utf8_encode("Motif absence"));
	$sheet->setCellValue('AD1',utf8_encode("Feuille de présence"));
	$sheet->setCellValue('AE1',utf8_encode("Attestation de formation"));
	$sheet->setCellValue('AF1',utf8_encode("Evaluation à chaud"));
	$sheet->setCellValue('AG1',utf8_encode("Remplissage EXTRANET"));
	$sheet->setCellValue('AH1',utf8_encode("Habilitation à la conduite"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Reference"));
	$sheet->setCellValue('B1',utf8_encode("Responsible"));
	$sheet->setCellValue('C1',utf8_encode("Analytical code"));
	$sheet->setCellValue('D1',utf8_encode("Registration number"));
	$sheet->setCellValue('E1',utf8_encode("Person"));
	$sheet->setCellValue('F1',utf8_encode("Contract"));
	$sheet->setCellValue('G1',utf8_encode("ETT"));
	$sheet->setCellValue('H1',utf8_encode("Contract end date"));
	$sheet->setCellValue('I1',utf8_encode("CSP"));
	$sheet->setCellValue('J1',utf8_encode("Gender"));
	$sheet->setCellValue('K1',utf8_encode("Age"));
	$sheet->setCellValue('L1',utf8_encode("Hourly rate charged"));
	$sheet->setCellValue('M1',utf8_encode("Entitled"));
	$sheet->setCellValue('N1',utf8_encode("Internal/External"));
	$sheet->setCellValue('O1',utf8_encode("Organization"));
	$sheet->setCellValue('P1',utf8_encode("Type of course"));
	$sheet->setCellValue('Q1',utf8_encode("Category"));
	$sheet->setCellValue('R1',utf8_encode("Inter/Intra"));
	$sheet->setCellValue('S1',utf8_encode("Start date"));
	$sheet->setCellValue('T1',utf8_encode("End date"));
	$sheet->setCellValue('U1',utf8_encode("Nb hours"));
	$sheet->setCellValue('V1',utf8_encode("Nb of days"));
	$sheet->setCellValue('W1',utf8_encode("Educational cost"));
	$sheet->setCellValue('X1',utf8_encode("Cost of salary"));
	$sheet->setCellValue('Y1',utf8_encode("Support request sent"));
	$sheet->setCellValue('Z1',utf8_encode("Support agreement"));
	$sheet->setCellValue('AA1',utf8_encode("Convention processing"));
	$sheet->setCellValue('AB1',utf8_encode("Present (P)/Absent (A)"));
	$sheet->setCellValue('AC1',utf8_encode("Reason for absence"));
	$sheet->setCellValue('AD1',utf8_encode("Timesheet"));
	$sheet->setCellValue('AE1',utf8_encode("Training certificate"));
	$sheet->setCellValue('AF1',utf8_encode("Hot evaluation"));
	$sheet->setCellValue('AG1',utf8_encode("EXTRANET filling"));
	$sheet->setCellValue('AH1',utf8_encode("Driving licenses"));
}

$sheet->getStyle('A1:A1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'2b8bb4'))));
$sheet->getStyle('B1:B1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fe344e'))));
$sheet->getStyle('C1:F1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'9caaae'))));
$sheet->getStyle('G1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fe344e'))));
$sheet->getStyle('I1:K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'9caaae'))));
$sheet->getStyle('L1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'2b8bb4'))));
$sheet->getStyle('M1:AH1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'09b800'))));
$sheet->getStyle('Z1:Z1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fe344e'))));
$sheet->getStyle('AC1:AC1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fe344e'))));

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(12);
$sheet->getColumnDimension('E')->setWidth(22);
$sheet->getColumnDimension('G')->setWidth(13);
$sheet->getColumnDimension('H')->setWidth(13);
$sheet->getColumnDimension('I')->setWidth(17);
$sheet->getColumnDimension('M')->setWidth(45);
$sheet->getColumnDimension('N')->setWidth(12);
$sheet->getColumnDimension('O')->setWidth(15);
$sheet->getColumnDimension('P')->setWidth(13);
$sheet->getColumnDimension('Q')->setWidth(18);
$sheet->getColumnDimension('S')->setWidth(12);
$sheet->getColumnDimension('T')->setWidth(12);


$req="
SELECT
	IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
	IF(
	(SELECT Id_TypeContrat FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1) IN (1,2,4,11)
	,
	CONCAT((SELECT YEAR(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1),'-',(SELECT LEFT(UCASE(Libelle),2) FROM new_competences_plateforme WHERE Id=form_session.Id_Plateforme),form_session_personne.Id)
	,'-'
	)
	,'-') AS Reference,
	form_session_personne.Id,
	form_session_personne.Validation_Inscription AS Validation_Inscription,
	form_session_personne.SemiPresence,
	(SELECT (SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Contrat,
	(SELECT (SELECT Libelle FROM rh_agenceinterim WHERE Id=Id_AgenceInterim) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS ETT,
	(SELECT IF((SELECT EstInterim FROM rh_typecontrat WHERE Id=Id_TypeContrat)=1,TauxHoraire*1.48,(SalaireBrut/(SELECT NbHeureMois FROM rh_tempstravail WHERE Id=Id_TempsTravail))*1.48) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS SalaireHoraireCharge,
	(SELECT (SELECT (SELECT Libelle FROM rh_classificationmetier WHERE Id=Id_Classification) FROM new_competences_metier WHERE Id=Id_Metier) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS CSP,
	IF((SELECT DateFin FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1)<(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1),
	(SELECT DateFin FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1),'0001-01-01') AS DateFinContrat,
	(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS DateDebut,
	(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) AS DateFin,
	(SELECT (SELECT Code_Analytique FROM new_competences_prestation WHERE Id=Id_Prestation)
	FROM rh_personne_mouvement
	WHERE rh_personne_mouvement.Suppr=0
	AND rh_personne_mouvement.Id_Personne=form_session_personne.Id_Personne
	AND EtatValidation=1
	AND rh_personne_mouvement.DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)) LIMIT 1) AS CodeAnalytique,
	(SELECT (SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)
		FROM new_competences_personne_poste_prestation
		WHERE Id_Poste IN (".$IdPosteCoordinateurEquipe.")
		AND new_competences_personne_poste_prestation.Id_Prestation=rh_personne_mouvement.Id_Prestation
		AND new_competences_personne_poste_prestation.Id_Pole=rh_personne_mouvement.Id_Pole
		AND Backup=0
		LIMIT 1
	)
	FROM rh_personne_mouvement
	WHERE rh_personne_mouvement.Suppr=0
	AND rh_personne_mouvement.Id_Personne=form_session_personne.Id_Personne
	AND EtatValidation=1
	AND rh_personne_mouvement.DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)) LIMIT 1) AS Responsable,
	
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne,
	(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Matricule,
	(SELECT Sexe FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Sexe,
	(SELECT if(Date_Naissance<='0001-01-01','',(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Date_Naissance)), '%Y')+0)) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Age,
	form_session.Id_Formation AS Id_Formation,
	form_session.Recyclage AS Recyclage,
	form_session.Id_Plateforme AS Id_Plateforme,
	(SELECT (SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Type,
	(SELECT Categorie FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Categorie,
	(SELECT IF(Elearning=0,'Présentiel','E-learning') FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS TypeCours,
	
	(SELECT IF(form_session.Recyclage=0,Libelle,LibelleRecyclage)
	FROM form_formation_langue_infos 
	WHERE form_formation_langue_infos.Suppr=0 
	AND form_formation_langue_infos.Id_Langue=(
		SELECT
			Id_Langue
		FROM
		form_formation_plateforme_parametres 
		WHERE
			form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
			AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
			AND Suppr=0 LIMIT 1
	)
	AND form_formation_langue_infos.Id_Formation=form_session.Id_Formation
	LIMIT 1
	) AS Formation,
	(
		SELECT
		(
			SELECT
				Libelle
			FROM
				form_organisme
			WHERE
				Id=Id_Organisme
		)
		FROM
			form_formation_plateforme_parametres 
		WHERE
			form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
		AND Suppr=0 LIMIT 1
	) AS Organisme ,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,0,
	IF(form_session_personne.Presence<0,0,(SELECT
		IF(form_session.Recyclage=0,Duree,DureeRecyclage)
		FROM
			form_formation_plateforme_parametres 
		WHERE
			form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
		AND Suppr=0 LIMIT 1
	))) AS NbHeures,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,0,
	IF(form_session_personne.Presence<0,0,(SELECT IF((SELECT EstInterim FROM rh_typecontrat WHERE Id=Id_TypeContrat)=1,TauxHoraire*1.48*(	SELECT
		IF(form_session.Recyclage=0,Duree,DureeRecyclage)
		FROM
			form_formation_plateforme_parametres 
		WHERE
			form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
		AND Suppr=0 LIMIT 1
	),(SalaireBrut/(SELECT NbHeureMois FROM rh_tempstravail WHERE Id=Id_TempsTravail))*1.48*(	SELECT
		IF(form_session.Recyclage=0,Duree,DureeRecyclage)
		FROM
			form_formation_plateforme_parametres 
		WHERE
			form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
		AND Suppr=0 LIMIT 1
	)) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1))) AS CoutSalarial,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,0,
	IF(form_session_personne.Presence<0,0,(SELECT
		IF(form_session.Recyclage=0,NbJour,NbJourRecyclage)
		FROM
			form_formation_plateforme_parametres 
		WHERE
			form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
		AND Suppr=0 LIMIT 1
	))) AS NbJours,
	form_session.InterIntra,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,DdePriseEnChargeEnvoyee,'-')) AS DdePriseEnChargeEnvoyee,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,AccordPriseEnCharge,'-')) AS AccordPriseEnCharge,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,TraitementConvention,'-')) AS TraitementConvention,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence=1,'-',MotifAbsence)) AS MotifAbs,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,form_session_personne.FeuillePresence,
	IF(form_session_personne.Presence<>0,'X','-')))) AS FeuillePresence,
	form_session_personne.Cout,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence=0,'-',IF(form_session_personne.Presence=1,'P','A'))) AS PresentAbsent,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
	IF(AttestationFormation<>'','X','-'),
	IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
	IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND Etat=0
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id),'','X')
	,
	IF((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)<'".date('Y-m-d')."','X','')
	)))) AS AttestationFormation,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
	IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND Etat=0
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id),'','X')
	,
	IF((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)<'".date('Y-m-d')."','X','')
	))) AS RemplissageExtranet,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,EvaluationAChaud,
	IF((SELECT COUNT(form_session_personne_document.Id) 
	FROM form_session_personne_document 
	WHERE form_session_personne_document.Suppr=0 
	AND form_session_personne_document.Id_Session_Personne=form_session_personne.Id)>0,
	IF((SELECT COUNT(form_session_personne_document.Id) 
	FROM form_session_personne_document 
	WHERE form_session_personne_document.Suppr=0 
	AND Id_Document=6
	AND DateHeureRepondeur=0
	AND form_session_personne_document.Id_Session_Personne=form_session_personne.Id),'','X')
	,
	'-'
	)
	))) AS EvaluationAChaud,
	IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
	IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
	AND (SELECT COUNT(Id)
	FROM new_competences_qualification_moyen
	WHERE new_competences_qualification_moyen.Id_Qualification=form_session_personne_qualification.Id_Qualification
	AND Suppr=0)>0
	)>0,
	IF((SELECT DateEditionAutorisationTravail FROM new_rh_etatcivil WHERE Id=form_session_personne.Id_Personne)<='0001-01-01','','X')
	,
	'-'
	),
	'-'
	))) AS HabilitationExtranet
FROM
	form_session_personne 
LEFT JOIN
	form_session
ON
	form_session_personne.Id_Session=form_session.Id
WHERE
	form_session.Suppr=0
	AND form_session.Annule=0
	AND ((form_session_personne.Suppr=0 AND Validation_Inscription=1) OR 
	 (
		(
			(form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0)
			OR form_session_personne.Validation_Inscription=-1
		)
		AND AComptabiliser=1
	 )
	) ";

if($_SESSION['FiltreSuiviFormation_Plateforme']<>0 && $_SESSION['FiltreSuiviFormation_Plateforme']<>""){$req.=" AND form_session.Id_Plateforme=".$_SESSION['FiltreSuiviFormation_Plateforme']." ";}
if($_SESSION['FiltreSuiviFormation_Personne']<>""){$req.="AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) LIKE '%".$_SESSION['FiltreSuiviFormation_Personne']."%' ";}
if($_SESSION['FiltreSuiviFormation_TypeFormation']>0 && $_SESSION['FiltreSuiviFormation_TypeFormation']<>""){
$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=".$_SESSION['FiltreSuiviFormation_TypeFormation']." ";
}
if($_SESSION['FiltreSuiviFormation_DateDebut']<>"")
{
$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) >= '".TrsfDate_($_SESSION['FiltreSuiviFormation_DateDebut'])."' ";
}
if($_SESSION['FiltreSuiviFormation_DateFin']<>"")
{
$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) <= '".TrsfDate_($_SESSION['FiltreSuiviFormation_DateFin'])."' ";
}
if($_SESSION['FiltreSuiviFormation_DateFinContrat']<>"")
{
$req.="AND IF((SELECT DateFin FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1)<(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1),
	(SELECT DateFin FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
	AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1),'0001-01-01') <= '".TrsfDate_($_SESSION['FiltreSuiviFormation_DateFinContrat'])."' ";
}
if($_SESSION['FiltreSuiviFormation_Formation']<>""){
$req.=" AND (SELECT IF(form_session.Recyclage=0,Libelle,LibelleRecyclage)
	FROM form_formation_langue_infos 
	WHERE form_formation_langue_infos.Suppr=0 
	AND form_formation_langue_infos.Id_Langue=(
		SELECT
			Id_Langue
		FROM
		form_formation_plateforme_parametres 
		WHERE
			form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
			AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
			AND Suppr=0 LIMIT 1
	)
	AND form_formation_langue_infos.Id_Formation=form_session.Id_Formation
	LIMIT 1
	) LIKE '%".$_SESSION['FiltreSuiviFormation_Formation']."%' ";
}
if($_SESSION['FiltreSuiviFormation_Organisme']>0 && $_SESSION['FiltreSuiviFormation_Organisme']<>""){
$req.=" AND (
		SELECT
			Id_Organisme
		FROM
			form_formation_plateforme_parametres 
		WHERE
			form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
		AND Suppr=0 LIMIT 1
	)=".$_SESSION['FiltreSuiviFormation_Organisme']." ";
}
if($_SESSION['FiltreSuiviFormation_Motif']<>""){
$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence=1,'-',MotifAbsence)) LIKE '%".$_SESSION['FiltreSuiviFormation_Motif']."%' ";
}
if($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']<>"0"){
$valeur="='' ";
if($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']=="(vide)"){$valeur="='' ";}
elseif($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']=="-"){$valeur="='-' ";}
elseif($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']=="X"){$valeur="='X' ";}
elseif($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']=="Autre"){$valeur=" NOT IN ('','-','X') ";}

$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,DdePriseEnChargeEnvoyee,'-')) ".$valeur." ";
}
if($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']<>"0"){
$valeur="='' ";
if($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']=="(vide)"){$valeur="='' ";}
elseif($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']=="-"){$valeur="='-' ";}
elseif($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']=="X"){$valeur="='X' ";}
elseif($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']=="Autre"){$valeur=" NOT IN ('','-','X') ";}

$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,AccordPriseEnCharge,'-')) ".$valeur." ";
}
if($_SESSION['FiltreSuiviFormation_TraitementConvention']<>"0"){
$valeur="='' ";
if($_SESSION['FiltreSuiviFormation_TraitementConvention']=="(vide)"){$valeur="='' ";}
elseif($_SESSION['FiltreSuiviFormation_TraitementConvention']=="-"){$valeur="='-' ";}
elseif($_SESSION['FiltreSuiviFormation_TraitementConvention']=="X"){$valeur="='X' ";}
elseif($_SESSION['FiltreSuiviFormation_TraitementConvention']=="Autre"){$valeur=" NOT IN ('','-','X') ";}

$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,TraitementConvention,'-')) ".$valeur." ";
}
if($_SESSION['FiltreSuiviFormation_FeuillePresence']<>"0"){
$valeur="='' ";
if($_SESSION['FiltreSuiviFormation_FeuillePresence']=="(vide)"){$valeur="='' ";}
elseif($_SESSION['FiltreSuiviFormation_FeuillePresence']=="-"){$valeur="='-' ";}
elseif($_SESSION['FiltreSuiviFormation_FeuillePresence']=="X"){$valeur="='X' ";}
elseif($_SESSION['FiltreSuiviFormation_FeuillePresence']=="Autre"){$valeur=" NOT IN ('','-','X') ";}

$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,form_session_personne.FeuillePresence,
	IF(form_session_personne.Presence<>0,'X','-')))) ".$valeur." ";
}
if($_SESSION['FiltreSuiviFormation_AttestationFormation']<>"0"){
$valeur="='' ";
if($_SESSION['FiltreSuiviFormation_AttestationFormation']=="(vide)"){$valeur="='' ";}
elseif($_SESSION['FiltreSuiviFormation_AttestationFormation']=="-"){$valeur="='-' ";}
elseif($_SESSION['FiltreSuiviFormation_AttestationFormation']=="X"){$valeur="='X' ";}
elseif($_SESSION['FiltreSuiviFormation_AttestationFormation']=="Autre"){$valeur=" NOT IN ('','-','X') ";}

$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
	IF(AttestationFormation<>'','X','-'),
	IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
	IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND Etat=0
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id),'','X')
	,
	IF((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)<'".date('Y-m-d')."','X','')
	)))) ".$valeur." ";
}
if($_SESSION['FiltreSuiviFormation_EvaluationAChaud']<>"0"){
$valeur="='' ";
if($_SESSION['FiltreSuiviFormation_EvaluationAChaud']=="(vide)"){$valeur="='' ";}
elseif($_SESSION['FiltreSuiviFormation_EvaluationAChaud']=="-"){$valeur="='-' ";}
elseif($_SESSION['FiltreSuiviFormation_EvaluationAChaud']=="X"){$valeur="='X' ";}
elseif($_SESSION['FiltreSuiviFormation_EvaluationAChaud']=="Autre"){$valeur=" NOT IN ('','-','X') ";}

$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,EvaluationAChaud,
	IF((SELECT COUNT(form_session_personne_document.Id) 
	FROM form_session_personne_document 
	WHERE form_session_personne_document.Suppr=0 
	AND form_session_personne_document.Id_Session_Personne=form_session_personne.Id)>0,
	IF((SELECT COUNT(form_session_personne_document.Id) 
	FROM form_session_personne_document 
	WHERE form_session_personne_document.Suppr=0 
	AND Id_Document=6
	AND DateHeureRepondeur=0
	AND form_session_personne_document.Id_Session_Personne=form_session_personne.Id),'','X')
	,
	'-'
	)
	))) ".$valeur." ";
}
if($_SESSION['FiltreSuiviFormation_RemplissageExtranet']<>"0"){
$valeur="='' ";
if($_SESSION['FiltreSuiviFormation_RemplissageExtranet']=="(vide)"){$valeur="='' ";}
elseif($_SESSION['FiltreSuiviFormation_RemplissageExtranet']=="-"){$valeur="='-' ";}
elseif($_SESSION['FiltreSuiviFormation_RemplissageExtranet']=="X"){$valeur="='X' ";}
elseif($_SESSION['FiltreSuiviFormation_RemplissageExtranet']=="Autre"){$valeur=" NOT IN ('','-','X') ";}

$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
	IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND Etat=0
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id),'','X')
	,
	IF((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)<'".date('Y-m-d')."','X','')
	))) ".$valeur." ";
}

if($_SESSION['FiltreSuiviFormation_HabilitationConduite']<>"0"){
$valeur="='' ";
if($_SESSION['FiltreSuiviFormation_HabilitationConduite']=="(vide)"){$valeur="='' ";}
elseif($_SESSION['FiltreSuiviFormation_HabilitationConduite']=="-"){$valeur="='-' ";}
elseif($_SESSION['FiltreSuiviFormation_HabilitationConduite']=="X"){$valeur="='X' ";}
elseif($_SESSION['FiltreSuiviFormation_HabilitationConduite']=="Autre"){$valeur=" NOT IN ('','-','X') ";}

$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
	IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
	IF((SELECT COUNT(form_session_personne_qualification.Id) 
	FROM form_session_personne_qualification 
	WHERE form_session_personne_qualification.Suppr=0 
	AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
	AND (SELECT COUNT(Id)
	FROM new_competences_qualification_moyen
	WHERE new_competences_qualification_moyen.Id_Qualification=form_session_personne_qualification.Id_Qualification
	AND Suppr=0)>0
	)>0,
	IF((SELECT DateEditionAutorisationTravail FROM new_rh_etatcivil WHERE Id=form_session_personne.Id_Personne)<='0001-01-01','','X')
	,
	'-'
	),
	'-'
	))) ".$valeur." ";
}
if($_SESSION['TriSuiviFormation_General']<>""){$req.=" ORDER BY ".substr($_SESSION['TriSuiviFormation_General'],0,-1);}

$ResultSessions=mysqli_query($bdd,$req);
$NbSessions=mysqli_num_rows($ResultSessions);

if($NbSessions>0){
	$ligne=1;
	while($row=mysqli_fetch_array($ResultSessions)){
		$ligne++;
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Reference'])));
		if($row['Responsable']==""){$sheet->setCellValue('B'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Responsable'])));}
		if($row['CodeAnalytique']==""){$sheet->setCellValue('C'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['CodeAnalytique'])));}
		if($row['Matricule']==""){$sheet->setCellValue('D'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Matricule'])));}
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['Personne'])));
		if($row['Contrat']==""){$sheet->setCellValue('F'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Contrat'])));}
		if($row['ETT']==""){$sheet->setCellValue('G'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['ETT'])));}
		if($row['DateFinContrat']=="" || $row['DateFinContrat']=="-" || $row['DateFinContrat']<="0001-01-01"){
			$sheet->setCellValue('H'.$ligne,utf8_encode("-"));
		}
		else{
			$date = explode("-",$row['DateFinContrat']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('H'.$ligne,$time);
			$sheet->getStyle('H'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($row['CSP']==""){$sheet->setCellValue('I'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['CSP'])));}
		if($row['Sexe']==""){$sheet->setCellValue('J'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['Sexe'])));}
		if($row['Age']==""){$sheet->setCellValue('K'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['Age'])));}
		if($row['SalaireHoraireCharge']==""){$sheet->setCellValue('L'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes(Round($row['SalaireHoraireCharge'],2))));}
		if($row['Formation']==""){$sheet->setCellValue('M'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes($row['Formation'])));}
		if($row['Type']==""){$sheet->setCellValue('N'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('N'.$ligne,utf8_encode(stripslashes($row['Type'])));}
		if($row['Organisme']==""){$sheet->setCellValue('O'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($row['Organisme'])));}
		if($row['TypeCours']==""){$sheet->setCellValue('P'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($row['TypeCours'])));}
		if($row['Categorie']==""){$sheet->setCellValue('Q'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('Q'.$ligne,utf8_encode(stripslashes($row['Categorie'])));}
		if($row['InterIntra']==""){$sheet->setCellValue('R'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('R'.$ligne,utf8_encode(stripslashes($row['InterIntra'])));}
		if($row['DateDebut']>'0001-01-01'){
			$date = explode("-",$row['DateDebut']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('S'.$ligne,$time);
			$sheet->getStyle('S'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($row['DateFin']>'0001-01-01'){
			$date = explode("-",$row['DateFin']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('T'.$ligne,$time);
			$sheet->getStyle('T'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('U'.$ligne,utf8_encode(stripslashes($row['NbHeures'])));
		$sheet->setCellValue('V'.$ligne,utf8_encode(stripslashes($row['NbJours'])));
		$sheet->setCellValue('W'.$ligne,utf8_encode(stripslashes($row['Cout'])));
		if($row['CoutSalarial']==""){$sheet->setCellValue('X'.$ligne,utf8_encode("-"));}else{$sheet->setCellValue('X'.$ligne,utf8_encode(stripslashes(Round($row['CoutSalarial'],2))));}
		$sheet->setCellValue('Y'.$ligne,utf8_encode(stripslashes($row['DdePriseEnChargeEnvoyee'])));
		$sheet->setCellValue('Z'.$ligne,utf8_encode(stripslashes($row['AccordPriseEnCharge'])));
		$sheet->setCellValue('AA'.$ligne,utf8_encode(stripslashes($row['TraitementConvention'])));
		$sheet->setCellValue('AB'.$ligne,utf8_encode(stripslashes($row['PresentAbsent'])));
		$sheet->setCellValue('AC'.$ligne,utf8_encode(stripslashes($row['MotifAbs'])));
		$sheet->setCellValue('AD'.$ligne,utf8_encode(stripslashes($row['FeuillePresence'])));
		$sheet->setCellValue('AE'.$ligne,utf8_encode(stripslashes($row['AttestationFormation'])));
		$sheet->setCellValue('AF'.$ligne,utf8_encode(stripslashes($row['EvaluationAChaud'])));
		$sheet->setCellValue('AG'.$ligne,utf8_encode(stripslashes($row['RemplissageExtranet'])));
		$sheet->setCellValue('AH'.$ligne,utf8_encode(stripslashes($row['HabilitationExtranet'])));
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