<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require_once '../Fonctions.php';

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

$sheet2 = $workbook->createSheet(1);

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('N°'));
	$sheet->setCellValue('B1',utf8_encode('Entité'));
	$sheet->setCellValue('C1',utf8_encode('Prestation'));
	$sheet->setCellValue('D1',utf8_encode('Thème'));
	$sheet->setCellValue('E1',utf8_encode('Questionnaire'));
	$sheet->setCellValue('F1',utf8_encode('Etat'));
	$sheet->setCellValue('G1',utf8_encode('Note'));
	$sheet->setCellValue('H1',utf8_encode('N° Action Tracker'));
	$sheet->setCellValue('I1',utf8_encode('Date'));
	$sheet->setCellValue('J1',utf8_encode('Surveillé'));
	$sheet->setCellValue('K1',utf8_encode('Surveillant'));
	$sheet->setCellValue('L1',utf8_encode('Question'));
	$sheet->setCellValue('M1',utf8_encode('Description de la NC'));
	$sheet->setCellValue('N1',utf8_encode('Action'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('N°'));
	$sheet->setCellValue('B1',utf8_encode('Entity'));
	$sheet->setCellValue('C1',utf8_encode('Activity'));
	$sheet->setCellValue('D1',utf8_encode('Theme'));
	$sheet->setCellValue('E1',utf8_encode('Questionnaire'));
	$sheet->setCellValue('F1',utf8_encode('State'));
	$sheet->setCellValue('G1',utf8_encode('Score'));
	$sheet->setCellValue('H1',utf8_encode('Action Tracker number'));
	$sheet->setCellValue('I1',utf8_encode('Date'));
	$sheet->setCellValue('J1',utf8_encode('Supervised'));
	$sheet->setCellValue('K1',utf8_encode('Supervisor'));	
	$sheet->setCellValue('L1',utf8_encode('Question'));
	$sheet->setCellValue('M1',utf8_encode('Description of the CN'));
	$sheet->setCellValue('N1',utf8_encode('Action'));
}
$sheet->getStyle('A1:N1')->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:N1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(40);
$sheet->getColumnDimension('G')->setWidth(10);
$sheet->getColumnDimension('H')->setWidth(13);
$sheet->getColumnDimension('I')->setWidth(13);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);

if($_SESSION['FiltreSODATDBThematique_Plage']=="12"){
	$dateDebut=date("Y-m-1",strtotime(date("Y-m-1")." -1 Year"));
	$dateFin=date("Y-m-d",strtotime(date("Y-m-1")." -1 day"));
}
else{
	$dateDebut=date('Y-01-01');
	$dateFin=date('Y-12-31');
}

$req = "SELECT soda_surveillance_question.Id_Question,
		(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
		(SELECT Question FROM soda_question WHERE Id=Id_Question) AS Question,
			COUNT(soda_surveillance_question.Id_Question) AS NbQuestion,
			(SELECT COUNT(tab.Id) 
			FROM soda_surveillance AS tab 
			WHERE tab.Suppr=0 
			AND tab.AutoSurveillance=0
			AND tab.Etat='Clôturé'
			AND tab.DateSurveillance>='".$dateDebut."'
			AND tab.DateSurveillance<='".$dateFin."'
			AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
			AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
			AND tab.Id IN (SELECT Id_Surveillance FROM soda_surveillance_question AS tab2 WHERE tab2.Id_Surveillance=tab.Id AND tab2.Id_Question=soda_surveillance_question.Id_Question)
			) AS NbSurveillance,
			(
			COUNT(soda_surveillance_question.Id_Question)/
			(SELECT COUNT(tab.Id) 
			FROM soda_surveillance AS tab 
			WHERE tab.Suppr=0 
			AND tab.AutoSurveillance=0
			AND tab.Etat='Clôturé'
			AND tab.DateSurveillance>='".$dateDebut."'
			AND tab.DateSurveillance<='".$dateFin."'
			AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
			AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
			AND tab.Id IN (SELECT Id_Surveillance FROM soda_surveillance_question AS tab2 WHERE tab2.Id_Surveillance=tab.Id AND tab2.Id_Question=soda_surveillance_question.Id_Question)
			)
			) AS Nb
		FROM soda_surveillance_question
		LEFT JOIN soda_surveillance 
		ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
		WHERE soda_surveillance.Suppr=0 
		AND soda_surveillance.AutoSurveillance=0
		AND soda_surveillance.Etat='Clôturé'
		AND soda_surveillance.DateSurveillance>='".$dateDebut."'
		AND soda_surveillance.DateSurveillance<='".$dateFin."'
		AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
		AND soda_surveillance_question.Etat='NC'
		AND soda_surveillance_question.Id_Question>0
		GROUP BY soda_surveillance_question.Id_Question
		ORDER BY 
			Nb DESC, NbQuestion DESC
		";
$result=mysqli_query($bdd,$req);
$nbPareto=mysqli_num_rows($result);
$listeQuestion="";
if($nbPareto>0){
	while($rowPareto=mysqli_fetch_array($result)) {
		if($listeQuestion<>''){$listeQuestion.=",";}
		$listeQuestion.=$rowPareto['Id_Question'];
	}
}

if($listeQuestion<>""){
	
	$req = "SELECT soda_surveillance.Id,
		(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
		(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
		(SELECT Question FROM soda_question WHERE Id=Id_Question) AS Question,
		DateSurveillance,soda_surveillance.Etat,NumActionTracker,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,
		ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100) AS Resultat,
		(SELECT Question FROM soda_question WHERE soda_question.Id=soda_surveillance_question.Id_Question) AS Question,
		soda_surveillance_question.Commentaire,
		soda_surveillance_question.Action
		FROM soda_surveillance_question
		LEFT JOIN soda_surveillance 
		ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
		WHERE soda_surveillance.Suppr=0 
		AND soda_surveillance.AutoSurveillance=0
		AND soda_surveillance.Etat='Clôturé'
		AND soda_surveillance.DateSurveillance>='".$dateDebut."'
		AND soda_surveillance.DateSurveillance<='".$dateFin."'
		AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
		AND soda_surveillance_question.Etat='NC'
		AND soda_surveillance_question.Id_Question>0
		AND soda_surveillance_question.Id_Question IN (".$listeQuestion.")
		ORDER BY soda_surveillance.Id DESC ";
	$resultSurveillance=mysqli_query($bdd,$req);
	$nbSurveillance=mysqli_num_rows($resultSurveillance);

	if($nbSurveillance > 0){
		$Couleur="EEEEEE";
		$ligne = 2;
		while($rowSurveillance=mysqli_fetch_array($resultSurveillance)){
			if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
			else{$Couleur="EEEEEE";}

			$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
			if($presta==""){$presta=$rowSurveillance['Prestation'];}
			
			$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($rowSurveillance['Id']));
			$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($rowSurveillance['Plateforme']));
			$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($presta));
			$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode(stripslashes($rowSurveillance['Theme'])));
			$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode(stripslashes($rowSurveillance['Questionnaire'])));
			$sheet->setCellValueByColumnAndRow(5,$ligne,utf8_encode($rowSurveillance['Etat']));
			$sheet->setCellValueByColumnAndRow(6,$ligne,utf8_encode($rowSurveillance['Resultat']."%"));
			$sheet->setCellValueByColumnAndRow(7,$ligne,utf8_encode($rowSurveillance['NumActionTracker']));
			$sheet->setCellValueByColumnAndRow(8,$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowSurveillance['DateSurveillance'])));
			$sheet->setCellValueByColumnAndRow(9,$ligne,utf8_encode(stripslashes($rowSurveillance['Surveille'])));
			$sheet->setCellValueByColumnAndRow(10,$ligne,utf8_encode(stripslashes($rowSurveillance['Surveillant'])));
			$sheet->setCellValueByColumnAndRow(11,$ligne,utf8_encode(stripslashes($rowSurveillance['Question'])));
			$sheet->setCellValueByColumnAndRow(12,$ligne,utf8_encode(stripslashes($rowSurveillance['Commentaire'])));
			$sheet->setCellValueByColumnAndRow(13,$ligne,utf8_encode(stripslashes($rowSurveillance['Action'])));
			$sheet->getStyle('A'.$ligne.':N'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
			
			$ligne++;
		}
	}

}

if($_SESSION['Langue']=="FR"){
	$sheet2->setCellValue('A1',utf8_encode('N°'));
	$sheet2->setCellValue('B1',utf8_encode('Question'));
	$sheet2->setCellValue('C1',utf8_encode('Nb'));
}
else{
	$sheet2->setCellValue('A1',utf8_encode('N°'));
	$sheet2->setCellValue('B1',utf8_encode('Question'));
	$sheet2->setCellValue('C1',utf8_encode('Nb'));
}
$sheet2->getStyle('A1:C1')->getAlignment()->setWrapText(true);
$sheet2->getStyle('A1:C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet2->getColumnDimension('B')->setWidth(150);

$req = "SELECT soda_surveillance_question.Id_Question,
	(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
	(SELECT Question FROM soda_question WHERE Id=Id_Question) AS Question,
		COUNT(soda_surveillance_question.Id_Question) AS NbQuestion,
		(SELECT COUNT(tab.Id) 
		FROM soda_surveillance AS tab 
		WHERE tab.Suppr=0 
		AND tab.AutoSurveillance=0
		AND tab.Etat='Clôturé'
		AND tab.DateSurveillance>='".$dateDebut."'
		AND tab.DateSurveillance<='".$dateFin."'
		AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
		AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
		AND tab.Id IN (SELECT Id_Surveillance FROM soda_surveillance_question AS tab2 WHERE tab2.Id_Surveillance=tab.Id AND tab2.Id_Question=soda_surveillance_question.Id_Question)
		) AS NbSurveillance,
		(
		COUNT(soda_surveillance_question.Id_Question)/
		(SELECT COUNT(tab.Id) 
		FROM soda_surveillance AS tab 
		WHERE tab.Suppr=0 
		AND tab.AutoSurveillance=0
		AND tab.Etat='Clôturé'
		AND tab.DateSurveillance>='".$dateDebut."'
		AND tab.DateSurveillance<='".$dateFin."'
		AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
		AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
		AND tab.Id IN (SELECT Id_Surveillance FROM soda_surveillance_question AS tab2 WHERE tab2.Id_Surveillance=tab.Id AND tab2.Id_Question=soda_surveillance_question.Id_Question)
		)
		) AS Nb
	FROM soda_surveillance_question
	LEFT JOIN soda_surveillance 
	ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
	WHERE soda_surveillance.Suppr=0 
	AND soda_surveillance.AutoSurveillance=0
	AND soda_surveillance.Etat='Clôturé'
	AND soda_surveillance.DateSurveillance>='".$dateDebut."'
	AND soda_surveillance.DateSurveillance<='".$dateFin."'
	AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
	AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
	AND soda_surveillance_question.Etat='NC'
	AND soda_surveillance_question.Id_Question>0
	GROUP BY soda_surveillance_question.Id_Question
	ORDER BY 
		Nb DESC, NbQuestion DESC
	LIMIT 10
	";
$result=mysqli_query($bdd,$req);
$nbPareto=mysqli_num_rows($result);
$i=0;
if($nbPareto>0){
	$Couleur="EEEEEE";
	$ligne = 2;
	while($rowPareto=mysqli_fetch_array($result)) {
		if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
		else{$Couleur="EEEEEE";}

		$sheet2->setCellValueByColumnAndRow(0,$ligne,utf8_encode($rowPareto['Id_Question']));
		$sheet2->setCellValueByColumnAndRow(1,$ligne,utf8_encode(stripslashes($rowPareto['Questionnaire']."\n".$rowPareto['Question'])));
		$sheet2->setCellValueByColumnAndRow(2,$ligne,utf8_encode(round($rowPareto['Nb']*100)."%"));
		$sheet2->getStyle('A'.$ligne.':C'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
		$sheet2->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_NC.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_NC.xlsx';
$writer->save($chemin);
readfile($chemin);
?>