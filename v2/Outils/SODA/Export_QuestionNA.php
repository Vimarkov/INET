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
	$sheet->setCellValue('F1',utf8_encode('Question'));
	$sheet->setCellValue('G1',utf8_encode('Cause'));
	$sheet->setCellValue('H1',utf8_encode('Date'));
	$sheet->setCellValue('I1',utf8_encode('Surveillé'));
	$sheet->setCellValue('J1',utf8_encode('Surveillant'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('N°'));
	$sheet->setCellValue('B1',utf8_encode('Entity'));
	$sheet->setCellValue('C1',utf8_encode('Activity'));
	$sheet->setCellValue('D1',utf8_encode('Theme'));
	$sheet->setCellValue('E1',utf8_encode('Questionnaire'));
	$sheet->setCellValue('F1',utf8_encode('Question'));
	$sheet->setCellValue('G1',utf8_encode('Cause'));
	$sheet->setCellValue('H1',utf8_encode('Date'));
	$sheet->setCellValue('I1',utf8_encode('Supervised'));
	$sheet->setCellValue('J1',utf8_encode('Supervisor'));	
}
				
$sheet->getStyle('A1:J1')->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(40);
$sheet->getColumnDimension('G')->setWidth(10);
$sheet->getColumnDimension('H')->setWidth(13);
$sheet->getColumnDimension('I')->setWidth(13);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);

$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbAccess=mysqli_num_rows($resAcc);

$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);
$req="";
$req2="	SELECT soda_surveillance.Id,soda_surveillance_question.Id AS Id_SurveillanceQuestion,
		(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
		(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
		(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		DateSurveillance,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,Id_Surveille,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,Id_Surveillant,
		(SELECT Question FROM soda_question WHERE Id=Id_Question) AS Question,
		(SELECT Question_EN FROM soda_question WHERE Id=Id_Question) AS QuestionEN,
		soda_surveillance_question.Commentaire
		
		FROM soda_surveillance_question 
		LEFT JOIN soda_surveillance 
		ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
		WHERE soda_surveillance.Suppr=0
		AND soda_surveillance_question.Etat='NA'
		AND soda_surveillance.Etat='Clôturé'
		AND AutoSurveillance=0 
		AND TypeNA=2 
		AND TraitementNA=0 ";
		if($nbAccess>0 || $nbSuperAdmin>0){}
		else{
			$req.="AND ((SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) 
					IN (SELECT Id 
						FROM soda_theme 
						WHERE Suppr=0 
						AND (Id_Gestionnaire=".$_SESSION['Id_Personne']." OR Id_Backup1=".$_SESSION['Id_Personne']." OR Id_Backup2=".$_SESSION['Id_Personne']." OR Id_Backup3=".$_SESSION['Id_Personne'].")
						)
					OR 
					IF(Id_Plateforme>0,Id_Plateforme,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)) IN 
					(
						SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
						AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.")
					)	
					) ";
		}
if($_SESSION['FiltreSODAQuestionNA_NumSurveillance'] <> "")
{
	$req .= "AND soda_surveillance.Id =".$_SESSION['FiltreSODAQuestionNA_NumSurveillance']." ";
}
else
{
	if ($_SESSION['FiltreSODAQuestionNA_Plateforme'] <> 0){$req .= "AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_SESSION['FiltreSODAQuestionNA_Plateforme']." ";}
	if ($_SESSION['FiltreSODAQuestionNA_Prestation'] <> 0){$req .= "AND soda_surveillance.Id_Prestation =".$_SESSION['FiltreSODAQuestionNA_Prestation']." ";}
	if ($_SESSION['FiltreSODAQuestionNA_Theme'] <> 0)
	{
		$req .= "AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) =".$_SESSION['FiltreSODAQuestionNA_Theme']." ";
		if($_SESSION['FiltreSODAQuestionNA_Questionnaire'] <> 0){$req .= "AND Id_Questionnaire =".$_SESSION['FiltreSODAQuestionNA_Questionnaire']." ";}
	}
	if ($_SESSION['FiltreSODAQuestionNA_Surveille'] <> 0){$req .= "AND soda_surveillance.Id_Surveille =".$_SESSION['FiltreSODAQuestionNA_Surveille']." ";}
	if ($_SESSION['FiltreSODAQuestionNA_Surveillant'] <> 0){$req .= "AND soda_surveillance.Id_Surveillant =".$_SESSION['FiltreSODAQuestionNA_Surveillant']." ";}
}
$reqOrder = " ORDER BY DateSurveillance DESC ";

$resultSurveillance=mysqli_query($bdd,$req2.$req.$reqOrder);
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
		if($_SESSION['Langue']=="FR"){$sheet->setCellValueByColumnAndRow(5,$ligne,utf8_encode(stripslashes($rowSurveillance['Question'])));}
		else{$sheet->setCellValueByColumnAndRow(5,$ligne,utf8_encode(stripslashes($rowSurveillance['Question_EN'])));}
		$sheet->setCellValueByColumnAndRow(6,$ligne,utf8_encode(stripslashes($rowSurveillance['Commentaire'])));
		$sheet->setCellValueByColumnAndRow(7,$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowSurveillance['DateSurveillance'])));
		$sheet->setCellValueByColumnAndRow(8,$ligne,utf8_encode(stripslashes($rowSurveillance['Surveille'])));
		$sheet->setCellValueByColumnAndRow(9,$ligne,utf8_encode(stripslashes($rowSurveillance['Surveillant'])));
		$sheet->getStyle('A'.$ligne.':J'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
		
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_QuestionNA.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_QuestionNA.xlsx';
$writer->save($chemin);
readfile($chemin);
?>