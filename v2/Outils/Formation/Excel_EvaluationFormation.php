<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");
require_once("../Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");

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

$req="SELECT Id, Libelle 
		FROM form_document_langue_question
		WHERE Suppr=0 
		AND TypeReponse='Note (1 à 6)'
		AND Id_Document_Langue=(SELECT Id FROM form_document_langue WHERE Suppr=0 AND Id_Document=6 LIMIT 1) 
		";
	$ResultQuestion=mysqli_query($bdd,$req);
	$NbQuest=mysqli_num_rows($ResultQuestion);
	
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Evaluations"));
	
	$sheet->setCellValue('A1',utf8_encode("Prestation"));
	$sheet->setCellValue('B1',utf8_encode("Pôle"));
	$sheet->setCellValue('C1',utf8_encode("Personne"));
	$sheet->setCellValue('D1',utf8_encode("Contrat"));
	$sheet->setCellValue('E1',utf8_encode("Formation"));
	$sheet->setCellValue('F1',utf8_encode("Formateur"));
	$sheet->setCellValue('G1',utf8_encode("Date formation"));
	$sheet->setCellValue('H1',utf8_encode("Heure de début"));
	$sheet->setCellValue('I1',utf8_encode("Heure de fin"));
	$col="J";
	
	if($NbQuest>0)
	{
		while($row=mysqli_fetch_array($ResultQuestion))
		{
			
			$sheet->setCellValue($col.'1',utf8_encode($row['Libelle']));
			$col++;
		}
	}
	$sheet->setCellValue($col.'1',utf8_encode("Note moyenne"));
}
else{
	$sheet->setTitle(utf8_encode("Evaluations"));
	$sheet->setCellValue('A1',utf8_encode("Site"));
	$sheet->setCellValue('B1',utf8_encode("Pole"));
	$sheet->setCellValue('C1',utf8_encode("Person"));
	$sheet->setCellValue('D1',utf8_encode("Contract"));
	$sheet->setCellValue('E1',utf8_encode("Training"));
	$sheet->setCellValue('F1',utf8_encode("Former"));
	$sheet->setCellValue('G1',utf8_encode("Training date"));
	$sheet->setCellValue('H1',utf8_encode("Start time"));
	$sheet->setCellValue('I1',utf8_encode("End time"));
	$col="J";
	
	if($NbQuest>0)
	{
		while($row=mysqli_fetch_array($ResultQuestion))
		{
			
			$sheet->setCellValue($col.'1',utf8_encode($row['Libelle']));
			$col++;
		}
	}
	$sheet->setCellValue($col.'1',utf8_encode("Average grade"));
}

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(40);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(15);


$sheet->getStyle('A1:'.$col.'1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:'.$col.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:'.$col.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:'.$col.'1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:'.$col.'1')->getFont()->setBold(true);
$sheet->getStyle('A1:'.$col.'1')->getFont()->getColor()->setRGB('1f49a6');


$req="
SELECT
	form_session_personne_document.Id,
	form_session_personne.Id_Personne,
	(SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP,
	(
	SELECT
		(SELECT IF(form_besoin.Motif='Renouvellement' AND form_session.Recyclage=1,LibelleRecyclage,Libelle)
		FROM form_formation_langue_infos
		WHERE Id_Formation=form_besoin.Id_Formation
		AND Id_Langue=
			(SELECT Id_Langue 
			FROM form_formation_plateforme_parametres 
			WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
			AND Id_Formation=form_besoin.Id_Formation
			AND Suppr=0 
			LIMIT 1)
		AND Suppr=0)
	FROM
		form_besoin
	WHERE
		form_besoin.Id=form_session_personne.Id_Besoin
	
	) AS Formation,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session.Id_Formateur) AS Formateur,
	(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS DateDebut,
	(SELECT Heure_Debut FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS HeureDebut,
	(SELECT Heure_Fin FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) AS HeureFin,
	(
		SELECT
		(
			SELECT
				Libelle 
			FROM
				new_competences_prestation 
			WHERE
				new_competences_prestation.Id=form_besoin.Id_Prestation
		)
		FROM
			form_besoin
		WHERE form_besoin.Id=form_session_personne.Id_Besoin
	) AS Prestation,
	(
		SELECT
		(
			SELECT
				Libelle 
			FROM
				new_competences_pole 
			WHERE
				new_competences_pole.Id=form_besoin.Id_Pole
		)
		FROM
			form_besoin
		WHERE
			form_besoin.Id=form_session_personne.Id_Besoin
	) AS Pole,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne
FROM form_session_personne_document
LEFT JOIN form_session_personne ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
LEFT JOIN form_session ON form_session_personne.Id_Session=form_session.Id
WHERE
	form_session_personne.Suppr=0
	AND form_session.Annule=0
	AND form_session.Suppr=0
	AND form_session_personne.Presence=1
	AND form_session_personne.Validation_Inscription=1
	AND form_session_personne_document.Suppr=0 
	AND form_session_personne_document.DateHeureRepondeur>'0001-01-01'
	AND form_session_personne_document.Id_Document=6
	AND form_session_personne.Id_Personne IN
		(
			SELECT
				Id_Personne 
			FROM
				new_competences_personne_prestation
			LEFT JOIN
				new_competences_prestation 
			ON
				new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
			WHERE
				new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' 
				AND Id_Plateforme IN
				(
					SELECT
						Id_Plateforme 
					FROM
						new_competences_personne_poste_plateforme
					WHERE
						Id_Personne=".$IdPersonneConnectee."
						AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
				)
		) ";

if($_SESSION['FiltreFormEvalForm_Prestation']<>"")
{
$req.="
	AND
	( 
		(
		SELECT
		(
			SELECT
				Libelle 
			FROM
				new_competences_prestation 
			WHERE
				new_competences_prestation.Id=form_besoin.Id_Prestation
		)
		FROM
			form_besoin
		WHERE form_besoin.Id=form_session_personne.Id_Besoin
	) LIKE '%".$_SESSION['FiltreFormEvalForm_Prestation']."%' 
		OR
		(
		SELECT
		(
			SELECT
				Libelle 
			FROM
				new_competences_pole 
			WHERE
				new_competences_pole.Id=form_besoin.Id_Pole
		)
		FROM
			form_besoin
		WHERE
			form_besoin.Id=form_session_personne.Id_Besoin
	) LIKE '%".$_SESSION['FiltreFormEvalForm_Prestation']."%'
	)";
}
if($_SESSION['FiltreFormEvalForm_Personne']<>""){$req.="AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) LIKE '%".$_SESSION['FiltreFormEvalForm_Personne']."%' ";}

if($_SESSION['FiltreFormEvalForm_Formation']<>""){$req.="AND (
SELECT
	(SELECT IF(form_besoin.Motif='Renouvellement' AND form_session.Recyclage=1,LibelleRecyclage,Libelle)
	FROM form_formation_langue_infos
	WHERE Id_Formation=form_besoin.Id_Formation
	AND Id_Langue=
		(SELECT Id_Langue 
		FROM form_formation_plateforme_parametres 
		WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
		AND Id_Formation=form_besoin.Id_Formation
		AND Suppr=0 
		LIMIT 1)
	AND Suppr=0)
FROM
	form_besoin
WHERE
	form_besoin.Id=form_session_personne.Id_Besoin

) LIKE '%".$_SESSION['FiltreFormEvalForm_Formation']."%' ";}

if($_SESSION['FiltreFormEvalForm_DateDebut']<>"")
{
$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) >= '".TrsfDate_($_SESSION['FiltreFormEvalForm_DateDebut'])."' ";
}
if($_SESSION['FiltreFormEvalForm_DateFin']<>"")
{
$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) <= '".TrsfDate_($_SESSION['FiltreFormEvalForm_DateFin'])."' ";
}

//Ajout des eval sans session
	$req.="UNION
		SELECT
			form_session_personne_document.Id,
			form_besoin.Id_Personne,
			(SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP,
			(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
				FROM form_formation_langue_infos
				WHERE Id_Formation=form_besoin.Id_Formation
				AND Id_Langue=
					(SELECT Id_Langue 
					FROM form_formation_plateforme_parametres 
					WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
					AND Id_Formation=form_besoin.Id_Formation
					AND Suppr=0 
					LIMIT 1)
				AND Suppr=0) AS Formation,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne_qualification.Id_Ouvreur) AS Formateur,
			LEFT(form_session_personne_document.DateHeureRepondeur,10) AS DateDebut,
			'' AS HeureDebut,
			'' AS HeureFin,
			(
					SELECT
						Libelle 
					FROM
						new_competences_prestation 
					WHERE
						new_competences_prestation.Id=form_besoin.Id_Prestation
				) AS Prestation,
			(
					SELECT
						Libelle 
					FROM
						new_competences_pole 
					WHERE
						new_competences_pole.Id=form_besoin.Id_Pole
				) AS Pole,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS Personne
		FROM form_session_personne_document
		LEFT JOIN form_session_personne_qualification ON form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
		LEFT JOIN form_besoin ON form_session_personne_qualification.Id_Besoin=form_besoin.Id
		WHERE
			form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.TypePassageQCM=1
			AND form_session_personne_document.Suppr=0 
			AND form_session_personne_document.DateHeureRepondeur>'0001-01-01'
			AND form_session_personne_document.Id_Document=6
			AND form_besoin.Id_Personne IN
				(
					SELECT
						Id_Personne 
					FROM
						new_competences_personne_prestation
					LEFT JOIN
						new_competences_prestation 
					ON
						new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
					WHERE
						new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' 
						AND Id_Plateforme IN
						(
							SELECT
								Id_Plateforme 
							FROM
								new_competences_personne_poste_plateforme
							WHERE
								Id_Personne=".$IdPersonneConnectee."
								AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
						)
				) ";
	
	if($_SESSION['FiltreFormEvalForm_Prestation']<>"")
	{
		$req.="
			AND
			( 
				(
					SELECT
						Libelle 
					FROM
						new_competences_prestation 
					WHERE
						new_competences_prestation.Id=form_besoin.Id_Prestation
				) LIKE '%".$_SESSION['FiltreFormEvalForm_Prestation']."%' 
				OR
				(
					SELECT
						Libelle 
					FROM
						new_competences_pole 
					WHERE
						new_competences_pole.Id=form_besoin.Id_Pole
				) LIKE '%".$_SESSION['FiltreFormEvalForm_Prestation']."%'
			)";
	}
	if($_SESSION['FiltreFormEvalForm_Personne']<>""){$req.="AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) LIKE '%".$_SESSION['FiltreFormEvalForm_Personne']."%' ";}
	if($_SESSION['FiltreFormEvalForm_Formation']<>""){$req.="AND (SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
				FROM form_formation_langue_infos
				WHERE Id_Formation=form_besoin.Id_Formation
				AND Id_Langue=
					(SELECT Id_Langue 
					FROM form_formation_plateforme_parametres 
					WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
					AND Id_Formation=form_besoin.Id_Formation
					AND Suppr=0 
					LIMIT 1)
				AND Suppr=0) LIKE '%".$_SESSION['FiltreFormEvalForm_Formation']."%' ";}
	
	if($_SESSION['FiltreFormEvalForm_DateDebut']<>"")
	{
		$req.="AND LEFT(form_session_personne_document.DateHeureRepondeur,10) >= '".TrsfDate_($_SESSION['FiltreFormEvalForm_DateDebut'])."' ";
	}
	if($_SESSION['FiltreFormEvalForm_DateFin']<>"")
	{
		$req.="AND LEFT(form_session_personne_document.DateHeureRepondeur,10) <= '".TrsfDate_($_SESSION['FiltreFormEvalForm_DateFin'])."' ";
	}
					
$req.="ORDER BY DateDebut DESC";
$ResultSessions=mysqli_query($bdd,$req);
$NbSessions=mysqli_num_rows($ResultSessions);
if($NbSessions>0)
{
	$ligne=2;
	while($row=mysqli_fetch_array($ResultSessions))
	{
			$Moyenne="";
			$req="
			SELECT AVG(form_session_personne_document_question_reponse.Valeur_Reponse) AS Moyenne
			FROM form_session_personne_document_question_reponse
			LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
			WHERE form_session_personne_document_question_reponse.Suppr=0
			AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
			AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=".$row['Id']." ";
			$ResultNote=mysqli_query($bdd,$req);
			$NbNote=mysqli_num_rows($ResultNote);
			if($NbNote>0){
				$rowMoyenne=mysqli_fetch_array($ResultNote);
				$Moyenne=$rowMoyenne['Moyenne'];
			}
			
			/*$req="
			SELECT form_session_personne_document_question_reponse.Valeur_Reponse
			FROM form_session_personne_document_question_reponse
			LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
			WHERE form_session_personne_document_question_reponse.Suppr=0
			AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
			AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=".$row['Id']." 
			AND form_session_personne_document_question_reponse.Valeur_Reponse<=3
			";
			$ResultNote2=mysqli_query($bdd,$req);
			$NbNote2=mysqli_num_rows($ResultNote2);
			
			if($NbNote2>0){*/
				$Contrat="";
				$IdContrat=IdContrat($row['Id_Personne'],date('Y-m-d'));
				if($IdContrat>0){
					if(TypeContrat2($IdContrat)<>10){
						$Contrat=TypeContrat($IdContrat);
					}
					else{
						$tab=AgenceInterimContrat($IdContrat);
						if($tab<>0){
							$Contrat=$tab[0];
						}
					}
				}
				$sheet->setCellValue('A'.$ligne,utf8_encode(AfficheCodePrestation(stripslashes($row['Prestation']))));
				$sheet->setCellValue('B'.$ligne,utf8_encode($row['Pole']));
				$sheet->setCellValue('C'.$ligne,utf8_encode($row['Personne']));
				$sheet->setCellValue('D'.$ligne,utf8_encode($Contrat));
				$sheet->setCellValue('E'.$ligne,utf8_encode($row['Formation']));
				$sheet->setCellValue('F'.$ligne,utf8_encode($row['Formateur']));
				$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDebut'])));
				$sheet->setCellValue('H'.$ligne,utf8_encode(substr($row['HeureDebut'],0,5)));
				$sheet->setCellValue('I'.$ligne,utf8_encode(substr($row['HeureFin'],0,5)));
				
				$col="J";
				$req="SELECT Id, Libelle 
					FROM form_document_langue_question
					WHERE Suppr=0 
					AND TypeReponse='Note (1 à 6)'
					AND Id_Document_Langue=(SELECT Id FROM form_document_langue WHERE Suppr=0 AND Id_Document=6 LIMIT 1) 
					";
				$ResultQuestion=mysqli_query($bdd,$req);
				$NbQuest=mysqli_num_rows($ResultQuestion);
				if($NbQuest>0)
				{
					while($row2=mysqli_fetch_array($ResultQuestion))
					{
						$req="
						SELECT form_session_personne_document_question_reponse.Valeur_Reponse
						FROM form_session_personne_document_question_reponse
						LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
						WHERE form_session_personne_document_question_reponse.Suppr=0
						AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
						AND form_document_langue_question.Id=".$row2['Id']."
						AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=".$row['Id']." 
						";
						$ResultNote3=mysqli_query($bdd,$req);
						$NbNote3=mysqli_num_rows($ResultNote3);
						$note="";
						if($NbNote3>0){
							$row3=mysqli_fetch_array($ResultNote3);
							$note=$row3['Valeur_Reponse'];
						}
						$sheet->setCellValue($col.$ligne,utf8_encode($note));
						$col++;
					}
				}
				
				$sheet->setCellValue($col.$ligne,utf8_encode($Moyenne));
		

				$sheet->getStyle('A'.$ligne.':'.$col.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$sheet->getStyle('A'.$ligne.':'.$col.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$sheet->getStyle('A'.$ligne.':'.$col.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
				$ligne++;
			//}
	}
}

						
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Evaluations.xlsx"');}
else{header('Content-Disposition: attachment;filename="Evaluations.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Evaluations.xlsx';
$writer->save($chemin);
readfile($chemin);
?>