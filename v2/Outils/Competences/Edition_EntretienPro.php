<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require_once("../Fonctions.php");

$Nom="";
$Prenom="";
$Metier="";
$DateEntretien="";
$Plateforme="";
$Evaluateur="";
$MetierEval="";
$DateDernierEPP="";
$Matricule="";
$MatriculeEval="";

if($_GET['ProjetEPE']==0){
	$reqEntretien = "SELECT Date_Reel FROM new_competences_personne_rh_eia WHERE Id=".$_GET['Id_Entretien'];
}
else{
	$reqEntretien = "SELECT IF((SELECT COUNT(Id)
						FROM epe_personne 
						WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(DateCreation)= YEAR(epe_personne_datebutoir.DateButoir))>0,
						(SELECT DateEntretien
						FROM epe_personne 
						WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(DateCreation)= YEAR(epe_personne_datebutoir.DateButoir) LIMIT 1)
						,'0001-01-01')
						AS Date_Reel
						FROM epe_personne_datebutoir
						WHERE Id=".$_GET['Id_Entretien'];
}
$Entretien=mysqli_query($bdd,$reqEntretien);
$nbEntretien=mysqli_num_rows($Entretien);
if($nbEntretien>0)
{
	$rowEntretien=mysqli_fetch_array($Entretien);
	if($rowEntretien['Date_Reel'] > "0001-01-01"){$DateEntretien=$rowEntretien['Date_Reel'];}
}

$reqPersonne = "
	SELECT
		new_rh_etatcivil.Nom,MatriculeAAA,
		new_rh_etatcivil.Prenom, ";
	if($DateEntretien==""){
		$reqPersonne.="(SELECT (SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) 
					FROM new_competences_personne_prestation 
					WHERE Id_Personne=".$_GET['IdPersonne']."
					AND Date_Debut<='".date('Y-m-d')."'
					AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
					ORDER BY Id DESC
					LIMIT 1
		) AS Plateforme, ";
	}
	else{
		$reqPersonne.="(SELECT (SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation)
					FROM new_competences_personne_prestation 
					WHERE Id_Personne=".$_GET['IdPersonne']."
					AND Date_Debut<='".$DateEntretien."'
					AND (Date_Fin<='0001-01-01' OR Date_Fin>='".$DateEntretien."')
					ORDER BY Id DESC
					LIMIT 1
		) AS Plateforme, ";
	}
	$reqPersonne.="(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id=new_competences_personne_metier.Id_Metier) AS Metier
	FROM
		new_rh_etatcivil
		LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne=new_rh_etatcivil.Id
	WHERE
		new_rh_etatcivil.Id=".$_GET['IdPersonne'];
$Personne=mysqli_query($bdd,$reqPersonne);
$nbPersonne=mysqli_num_rows($Personne);
if($nbPersonne>0)
{
	$rowPersonne=mysqli_fetch_array($Personne);
	$Matricule=$rowPersonne['MatriculeAAA'];
	$Nom=$rowPersonne['Nom'];
	$Prenom=$rowPersonne['Prenom'];
	$Metier=$rowPersonne['Metier'];
	$Plateforme=$rowPersonne['Plateforme'];
}

$reqEvaluateur="
	SELECT
		new_rh_etatcivil.Nom,
		new_rh_etatcivil.Prenom,MatriculeAAA,
		(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id=new_competences_personne_metier.Id_Metier) AS Metier
	FROM
		new_rh_etatcivil
		LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne=new_rh_etatcivil.Id
	WHERE
		new_rh_etatcivil.Id=".$_GET['IdEval'];
$Personne=mysqli_query($bdd,$reqEvaluateur);
$nbPersonne=mysqli_num_rows($Personne);
if($nbPersonne>0)
{
	$rowPersonne=mysqli_fetch_array($Personne);
	$MatriculeEval=$rowPersonne['MatriculeAAA'];
	$Evaluateur=$rowPersonne['Nom']." ".$rowPersonne['Prenom'];
	$MetierEval=$rowPersonne['Metier'];
}


$req="SELECT Date_Reel FROM new_competences_personne_rh_eia WHERE Type='EPP' AND Id_Personne=".$_GET['IdPersonne']." 
	UNION
	SELECT IF((SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir)= YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)))>0,
	(SELECT DateEntretien
	FROM epe_personne 
	WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir)= YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) LIMIT 1)
	,'0001-01-01')
	AS Date_Reel
	FROM epe_personne_datebutoir
	WHERE Id_Personne=".$_GET['IdPersonne']."
	AND TypeEntretien='EPP'
	ORDER BY Date_Reel DESC";
$resultEIA=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($resultEIA);
if($nbenreg>0){
	$RowEIA=mysqli_fetch_array($resultEIA);
	if($RowEIA['Date_Reel'] > "0001-01-01"){$DateDernierEPP=$RowEIA['Date_Reel'];}
}

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();

if($_GET['Type']=="EPE")
{
	if($LangueAffichage=="FR"){
		$excel = $workbook->load('../../../Qualite/D/7/D-0705/D-0705-012.xlsx');
		$sheet = $excel->getSheetByName('NON CADRES');
		$sheet->setCellValue("B3",utf8_encode($Matricule));
		$sheet->setCellValue("B4",utf8_encode($Nom));
		$sheet->setCellValue("B5",utf8_encode($Prenom));
		$sheet->setCellValue("B6",utf8_encode($Metier));
		$sheet->setCellValue("Q3",utf8_encode($DateEntretien));
		$sheet->setCellValue("Q4",utf8_encode($Plateforme));
		$sheet->setCellValue("Q5",utf8_encode($Evaluateur));
		$sheet->setCellValue("Q6",utf8_encode($MatriculeEval));
		$sheet->setCellValue("Q7",utf8_encode($MetierEval));
		
		$sheet2 = $excel->getSheetByName('CADRES');
		$sheet2->setCellValue("B3",utf8_encode($Matricule));
		$sheet2->setCellValue("B4",utf8_encode($Nom));
		$sheet2->setCellValue("B5",utf8_encode($Prenom));
		$sheet2->setCellValue("B6",utf8_encode($Metier));
		$sheet2->setCellValue("Q3",utf8_encode($DateEntretien));
		$sheet2->setCellValue("Q4",utf8_encode($Plateforme));
		$sheet2->setCellValue("Q5",utf8_encode($Evaluateur));
		$sheet2->setCellValue("Q6",utf8_encode($MatriculeEval));
		$sheet2->setCellValue("Q7",utf8_encode($MetierEval));
	}
	else{
		$excel = $workbook->load('../../../Qualite/D/7/D-0705/D-0705-012-EPE eng.xlsx');
		$sheet = $excel->getSheetByName('D-0705-012');
		$sheet->setCellValue("B3",utf8_encode($Nom));
		$sheet->setCellValue("B4",utf8_encode($Prenom));
		$sheet->setCellValue("B5",utf8_encode($Metier));
		$sheet->setCellValue("O3",utf8_encode($DateEntretien));
		$sheet->setCellValue("O4",utf8_encode($Plateforme));
		$sheet->setCellValue("O5",utf8_encode($Evaluateur));
		$sheet->setCellValue("O6",utf8_encode($MetierEval));

	}
	
	$DateDernierEPE=date('Y-01-01',strtotime(date('Y-m-d')." -1 year"));
	
	$req="SELECT Date_Reel FROM new_competences_personne_rh_eia WHERE Type='EPE' AND Id_Personne=".$_GET['IdPersonne']." 
	UNION
	SELECT IF((SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir)= YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)))>0,
	(SELECT DateEntretien
	FROM epe_personne 
	WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir)= YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) LIMIT 1)
	,'0001-01-01')
	AS Date_Reel
	FROM epe_personne_datebutoir
	WHERE Id_Personne=".$_GET['IdPersonne']."
	AND TypeEntretien='EPE'
	ORDER BY Date_Reel DESC";
	$resultEIA=mysqli_query($bdd,$req);
	$nbenreg=mysqli_num_rows($resultEIA);
	if($nbenreg>0){
		$RowEIA=mysqli_fetch_array($resultEIA);
		$DateDernierEPE=$RowEIA['Date_Reel'];
		
		//Remonter 2 mois avant
		$DateDernierEPE=date('Y-m-d',strtotime($DateDernierEPE." -2 month"));
	}

	$RequeteListeQualification="
		SELECT
			new_competences_qualification.Id,
			new_competences_qualification.Id_Categorie_Qualification,
			new_competences_qualification.Libelle AS LIBELLE_QUALIFICATION,
			new_competences_qualification.Periodicite_Surveillance,
			new_competences_categorie_qualification.Libelle,
			new_competences_relation.Sans_Fin,
			new_competences_relation.Evaluation,
			new_competences_relation.Date_QCM AS DATEQCM,
			new_competences_relation.QCM_Surveillance,
			new_competences_relation.Date_Surveillance,
			new_competences_relation.Id AS Id_Relation,
			new_competences_relation.Visible,
			new_competences_relation.Date_Debut AS DATEDEBUT,
			new_competences_relation.Date_Fin,
			new_competences_relation.Resultat_QCM,
			new_competences_relation.Id_Besoin,
			new_competences_relation.Id_Session_Personne_Qualification
		FROM
			new_competences_relation,
			new_competences_qualification,
			new_competences_categorie_qualification
		WHERE
			new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
			AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
			AND new_competences_relation.Id_Personne=".$_GET['IdPersonne']."
			AND new_competences_relation.Type='Qualification'
			AND (new_competences_relation.Date_QCM >= '".$DateDernierEPE."' OR new_competences_relation.Date_Debut>='".$DateDernierEPE."')
			AND new_competences_relation.Date_QCM<'".date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))."'
			AND new_competences_relation.Suppr=0
			AND Evaluation NOT IN ('','B','Bi')
			AND new_competences_qualification.Id NOT IN (1643,1644)
			AND (
				Evaluation = 'X'
				OR (Evaluation IN ('Q','S') AND new_competences_categorie_qualification.Id_Categorie_Maitre=2 )
			)
			AND new_competences_categorie_qualification.Id_Categorie_Maitre<>1
		ORDER BY
			new_competences_categorie_qualification.Libelle ASC,
			new_competences_qualification.Libelle ASC,
			new_competences_relation.Date_Debut DESC,
			new_competences_relation.Date_QCM DESC";
	$ListeQualification=mysqli_query($bdd,$RequeteListeQualification);
	$nbLigneQualif=mysqli_num_rows($ListeQualification);
	$RequeteListeFormation="SELECT DateSession AS FORMATION_DATE,Libelle AS FORMATION_LIBELLE,Organisme,Type
			FROM
			(
			SELECT
			form_besoin.Id AS Id_Besoin,
			0 AS Id_PersonneFormation,
			(
				SELECT
					form_session_date.DateSession
				FROM
					form_session_personne
				LEFT JOIN 
					form_session_date 
				ON 
					form_session_personne.Id_Session=form_session_date.Id_Session
				WHERE
					form_session_personne.Id_Besoin=form_besoin.Id
					AND form_session_personne.Id NOT IN 
						(
						SELECT
							Id_Session_Personne
						FROM
							form_session_personne_qualification
						WHERE
							Suppr=0	
						)
					AND form_session_personne.Suppr=0
					AND form_session_personne.Validation_Inscription=1
					AND form_session_personne.Presence=1
					AND form_session_date.Suppr=0
				ORDER BY DateSession DESC
				LIMIT 1
			) AS DateSession,
			(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
				WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
				AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
				AND Suppr=0 LIMIT 1) AS Organisme,
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
				AND Suppr=0) AS Libelle,
		'Professionnelle' AS Type
		FROM
			form_besoin,
			new_competences_prestation
		WHERE
			form_besoin.Id_Personne=".$_GET['IdPersonne']."
			AND form_besoin.Id_Prestation=new_competences_prestation.Id
			AND form_besoin.Suppr=0
			AND form_besoin.Valide=1
			AND form_besoin.Traite=4
			AND form_besoin.Id IN
			(
			SELECT
				Id_Besoin
			FROM
				form_session_personne
			WHERE
				form_session_personne.Id NOT IN 
					(
					SELECT
						Id_Session_Personne
					FROM
						form_session_personne_qualification
					WHERE
						Suppr=0	
					)
				AND Suppr=0
				AND form_session_personne.Validation_Inscription=1
				AND form_session_personne.Presence=1
			)
			AND (
				SELECT
					form_session_date.DateSession
				FROM
					form_session_personne
				LEFT JOIN 
					form_session_date 
				ON 
					form_session_personne.Id_Session=form_session_date.Id_Session
				WHERE
					form_session_personne.Id_Besoin=form_besoin.Id
					AND form_session_personne.Id NOT IN 
						(
						SELECT
							Id_Session_Personne
						FROM
							form_session_personne_qualification
						WHERE
							Suppr=0	
						)
					AND form_session_personne.Suppr=0
					AND form_session_personne.Validation_Inscription=1
					AND form_session_personne.Presence=1
					AND form_session_date.Suppr=0
				ORDER BY DateSession DESC
				LIMIT 1
			)>='".$DateDernierEPE."'
			
			AND (
				SELECT
					form_session_date.DateSession
				FROM
					form_session_personne
				LEFT JOIN 
					form_session_date 
				ON 
					form_session_personne.Id_Session=form_session_date.Id_Session
				WHERE
					form_session_personne.Id_Besoin=form_besoin.Id
					AND form_session_personne.Id NOT IN 
						(
						SELECT
							Id_Session_Personne
						FROM
							form_session_personne_qualification
						WHERE
							Suppr=0	
						)
					AND form_session_personne.Suppr=0
					AND form_session_personne.Validation_Inscription=1
					AND form_session_personne.Presence=1
					AND form_session_date.Suppr=0
				ORDER BY DateSession DESC
				LIMIT 1
			)<'".date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))."'
			
			UNION 
			
			SELECT 
			0 AS Id_Besoin,
			new_competences_personne_formation.Id AS Id_PersonneFormation, 
			new_competences_personne_formation.Date AS DateSession,
			'' AS Organisme,
			(SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) AS Libelle ,
			new_competences_personne_formation.Type 
			FROM new_competences_personne_formation
			WHERE new_competences_personne_formation.Id_Personne=".$_GET['IdPersonne'].") AS TAB 
			WHERE DateSession>='".$DateDernierEPE."'
			AND DateSession<'".date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))."'
			ORDER BY Type ASC, Libelle ASC, DateSession DESC ";
	$ListeFormation=mysqli_query($bdd,$RequeteListeFormation);
	$nbLigneFormation=mysqli_num_rows($ListeFormation);
	$nbLigne=$nbLigneQualif+$nbLigneFormation;
	
	if($nbLigne>3)
	{
		for($i=4;$i<=$nbLigne;$i++)
		{
			$sheet->insertNewRowBefore(68, 1);
			$sheet->mergeCells('A68:F68');
			$sheet->mergeCells('G68:I68');
			
			if($LangueAffichage=="FR"){
				$sheet2->insertNewRowBefore(68, 1);
				$sheet2->mergeCells('A68:F68');
				$sheet2->mergeCells('G68:I68');
			}
		}
	}
	
	//QUALIFICATION
	$i=67;
	while($LigneQualification=mysqli_fetch_array($ListeQualification))
	{
		$DateAPrendreEnAfficher=$LigneQualification['DATEQCM'];
		if($LigneQualification['DATEDEBUT'] > $LigneQualification['DATEQCM']){$DateAPrendreEnAfficher=$LigneQualification['DATEDEBUT'];}
		$sheet->setCellValue("A".$i,utf8_encode($LigneQualification['LIBELLE_QUALIFICATION']));
		$sheet->setCellValue("G".$i,utf8_encode($DateAPrendreEnAfficher));
		$sheet->setCellValue("J".$i,utf8_encode($DateAPrendreEnAfficher));
		
		if($LangueAffichage=="FR"){
			$sheet2->setCellValue("A".$i,utf8_encode($LigneQualification['LIBELLE_QUALIFICATION']));
			$sheet2->setCellValue("G".$i,utf8_encode($DateAPrendreEnAfficher));
			$sheet2->setCellValue("J".$i,utf8_encode($DateAPrendreEnAfficher));
		}
		$i++;
	}
	//FORMATION
	while($LigneFormation=mysqli_fetch_array($ListeFormation))
	{
		$sheet->setCellValue("A".$i,utf8_encode($LigneFormation['FORMATION_LIBELLE']));
		$sheet->setCellValue("G".$i,utf8_encode($LigneFormation['FORMATION_DATE']));
		$sheet->setCellValue("J".$i,utf8_encode($LigneFormation['FORMATION_DATE']));
		
		if($LangueAffichage=="FR"){
			$sheet2->setCellValue("A".$i,utf8_encode($LigneFormation['FORMATION_LIBELLE']));
			$sheet2->setCellValue("G".$i,utf8_encode($LigneFormation['FORMATION_DATE']));
			$sheet2->setCellValue("J".$i,utf8_encode($LigneFormation['FORMATION_DATE']));
		}
		$i++;
	}
	
	//Enregistrement du fichier excel
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
	header('Content-Disposition: attachment;filename="D-0705-12-EPE.xlsx"'); 
	header('Cache-Control: max-age=0'); 

	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

	$chemin = '../../tmp/D-0705-12-EPE.xlsx';
	$writer->save($chemin);
	readfile($chemin);
}
elseif($_GET['Type']=="EPP")
{
	if($LangueAffichage=="FR"){$excel = $workbook->load('../../../Qualite/D/7/D-0705/D-0705-013.xlsx');}
	else{$excel = $workbook->load('../../../Qualite/D/7/D-0705/D-0705-013 EPP eng.xlsx');}
	$sheet = $excel->getSheetByName('D-0705-013_EPP');
	
	if($LangueAffichage=="FR"){
		$sheet->setCellValue("B3",utf8_encode($Matricule));
		$sheet->setCellValue("B4",utf8_encode($Nom));
		$sheet->setCellValue("B5",utf8_encode($Prenom));
		$sheet->setCellValue("B6",utf8_encode($Metier));
		$sheet->setCellValue("O3",utf8_encode($DateEntretien));
		$sheet->setCellValue("O4",utf8_encode($Plateforme));
		$sheet->setCellValue("O5",utf8_encode($Evaluateur));
		$sheet->setCellValue("O6",utf8_encode($MatriculeEval));
		$sheet->setCellValue("O7",utf8_encode($MetierEval));
	}
	else{
		$sheet->setCellValue("C4",utf8_encode($Nom));
		$sheet->setCellValue("C5",utf8_encode($Prenom));
		$sheet->setCellValue("C6",utf8_encode($Metier));
		$sheet->setCellValue("R4",utf8_encode($DateEntretien));
		$sheet->setCellValue("R5",utf8_encode($DateDernierEPP));
		$sheet->setCellValue("R6",utf8_encode($Evaluateur));
		$sheet->setCellValue("R7",utf8_encode($MetierEval));
		
	}
	
	//Enregistrement du fichier excel
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
	header('Content-Disposition: attachment;filename="D-0705-013 EPP.xlsx"'); 
	header('Cache-Control: max-age=0'); 

	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

	$chemin = '../../tmp/D-0705-013 EPP.xlsx';
	$writer->save($chemin);
	readfile($chemin);
}
else
{
	if($LangueAffichage=="FR"){$excel = $workbook->load('../../../Qualite/D/7/D-0705/D-0705-014.xlsx');}
	else{$excel = $workbook->load('../../../Qualite/D/7/D-0705/D-0705-014.xlsx');}
	$sheet = $excel->getSheetByName('BIlan');
	
	$sheet->setCellValue("B3",utf8_encode($Matricule));
	$sheet->setCellValue("B4",utf8_encode($Nom));
	$sheet->setCellValue("B5",utf8_encode($Prenom));
	$sheet->setCellValue("B6",utf8_encode($Metier));
	$sheet->setCellValue("O3",utf8_encode($DateEntretien));
	$sheet->setCellValue("O4",utf8_encode($Plateforme));
	$sheet->setCellValue("O5",utf8_encode($Evaluateur));
	$sheet->setCellValue("O6",utf8_encode($MatriculeEval));
	$sheet->setCellValue("O7",utf8_encode($MetierEval));
	
	//Enregistrement du fichier excel
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
	header('Content-Disposition: attachment;filename="D-0705-013 EPP.xlsx"'); 
	header('Cache-Control: max-age=0'); 

	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

	$chemin = '../../tmp/D-0705-014.xlsx';
	$writer->save($chemin);
	readfile($chemin);
}
?>