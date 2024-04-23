<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Fonctions.php");

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();

$requeteSurveillance = "SELECT Id AS ID, 
		(SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS ID_Theme,
		(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
		(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
		(SELECT AutoriserQuestionsAdditionnelles FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS AutoriserQuestionsAdditionnelles,
		Id_Questionnaire AS ID_Questionnaire,
		(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
		IF(Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)) AS Plateforme,
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		Id_Prestation,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,
		(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS SeuilReussite,
		(SELECT NonAleatoire FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS NonAleatoire, 
		DateSurveillance,
		NumActionTracker,
		SignatureSurveillant,
		SignatureSurveille,
		Commentaire
		FROM soda_surveillance 
		WHERE Id=".$_GET['Id']."
		AND Suppr=0
		";

$resultSurveillance=mysqli_query($bdd,$requeteSurveillance);
$LigneSurveillance=mysqli_fetch_array($resultSurveillance);

$presta=substr($LigneSurveillance['Prestation'],0,strpos($LigneSurveillance['Prestation']," "));
if($presta==""){$presta=$LigneSurveillance['Prestation'];}

if($LigneSurveillance['ID_Theme']==7){
	$leD="D-0902 - Issue 2";
	$excel = $workbook->load('D-0901-Procedes.xlsx');
}
elseif($LigneSurveillance['ID_Theme']==8){
	$leD="D-0919 - Issue 1";
	$excel = $workbook->load('D-0901-Processus.xlsx');
}
else{
	$leD="D-0901 - Issue 2";
	$excel = $workbook->load('D-0901-Operationnelle.xlsx');
}

$sheet = $excel->getSheetByName('1');

$sheet->setCellValue('J1',utf8_encode($LigneSurveillance['Plateforme']));
$sheet->setCellValue('C3',utf8_encode("N° ".$LigneSurveillance['ID']));
$sheet->setCellValue('C4',utf8_encode($LigneSurveillance['Questionnaire']));
$sheet->setCellValue('J4',utf8_encode($presta));

$sheet->setCellValue('F6',utf8_encode(stripslashes($LigneSurveillance['Surveillant'])));
$sheet->setCellValue('C7',utf8_encode(stripslashes($LigneSurveillance['Surveille'])));
$sheet->setCellValue('C6',utf8_encode(AfficheDateJJ_MM_AAAA($LigneSurveillance['DateSurveillance'])));

$sheet->getStyle('C4')->getAlignment()->setWrapText(true);

$total = 0;
$C = 0;

$reqQuestionSurveillance = "
SELECT
	soda_surveillance_question.Id AS ID,
	soda_question.Question,
	soda_question.Question_EN,
	soda_question.Reponse,
	soda_question.Reponse_EN,
	soda_question.ImageQuestion,
	soda_surveillance_question.Ponderation,
	soda_surveillance_question.Etat,
	soda_surveillance_question.Commentaire,
	soda_surveillance_question.Action
FROM
	soda_surveillance_question
LEFT JOIN soda_question
	ON soda_surveillance_question.Id_Question = soda_question.Id
WHERE
	soda_surveillance_question.Id_Surveillance =".$LigneSurveillance['ID']." 
AND soda_surveillance_question.Id_Question>0 ";
if($LigneSurveillance['NonAleatoire']==1){
	$reqQuestionSurveillance .= "ORDER BY soda_question.Ordre ";
}
$resultQuestion=mysqli_query($bdd,$reqQuestionSurveillance);
$nbQuestion=mysqli_num_rows($resultQuestion);

$ligne=12;
if($nbQuestion > 0)
{
	while($rowQuestion=mysqli_fetch_array($resultQuestion))
	{
		$laQuestion="";
		$laQuestion.= "FR : ".$rowQuestion['Question']."\nEN : ".$rowQuestion['Question_EN']."\n\n";
		$laQuestion.= "FR : ".$rowQuestion['Reponse']."\nEN : ".$rowQuestion['Reponse_EN'];
		
		$tailleImage=0;
		if($rowQuestion['ImageQuestion']<>""){
			if(file_exists ('ImageQCM/'.$rowQuestion['ImageQuestion'])){
				//Insérer l'image
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setName(utf8_encode($rowQuestion['ImageQuestion']));
				$objDrawing->setDescription('PHPExcel logo');
				$objDrawing->setPath('ImageQCM/'.$rowQuestion['ImageQuestion']);
				$objDrawing->setCoordinates('A'.$ligne);
				$objDrawing->setOffsetX(3);
				$objDrawing->setOffsetY(200);
				$objDrawing->setWidth(380);
				$objDrawing->setWorksheet($sheet);
				$tailleImage=$objDrawing->getHeight();
			}
		}
		
		$checkC = "";
		$checkNC = "";
		$checkNA = "";
		$observation ="";
		$action = "";
		$cloture = "";
		$score="/".$rowQuestion['Ponderation'];
		if ($rowQuestion['Etat'] == "NC")
		{
			$checkNC = "X";
			$total=$total+$rowQuestion['Ponderation'];
			$score="0/".$rowQuestion['Ponderation'];
		}
		elseif ($rowQuestion['Etat'] == "C")
		{
			$score=$rowQuestion['Ponderation']."/".$rowQuestion['Ponderation'];
			$checkC = "X";
			$total=$total+$rowQuestion['Ponderation'];
			$C=$C+$rowQuestion['Ponderation'];
		}
		elseif($rowQuestion['Etat'] == "NA"){
			$score="";
			$checkNA = "X";
		}
		$observation = $rowQuestion['Commentaire'];
		$action = $rowQuestion['Action'];

		if($observation<>"" && $action<>""){
			$observation.="\n".$action;
		}
		elseif($observation=="" && $action<>""){
			$observation.=$action;
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($laQuestion)));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($checkC)));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($checkNC)));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($checkNA)));
		$sheet->setCellValue('H'.$ligne,utf8_encode($score));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($observation)));
		
		
		$sheet->getRowDimension($ligne)->setRowHeight(80);
		
		if($rowQuestion['ImageQuestion']<>""){
			if(file_exists ('ImageQCM/'.$rowQuestion['ImageQuestion'])){
				$sheet->getStyle('A'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$sheet->getRowDimension($ligne)->setRowHeight($sheet->getRowDimension($ligne)->getRowHeight()+$tailleImage+50);
			}
		}
		$sheet->getStyle('A'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('I'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->mergeCells('A'.$ligne.':D'.$ligne);
		$sheet->mergeCells('I'.$ligne.':J'.$ligne);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			
		$ligne++;
	}
}

$reqQuestionSurveillance = "
SELECT
	soda_surveillance_question.Id AS ID,
	soda_surveillance_question.QuestionAdditionnelle,
	soda_surveillance_question.ReponseAdditionnelle,
	soda_surveillance_question.Ponderation,
	soda_surveillance_question.Etat,
	soda_surveillance_question.Commentaire,
	soda_surveillance_question.Action
FROM
	soda_surveillance_question
WHERE
	soda_surveillance_question.Id_Surveillance =".$LigneSurveillance['ID']." 
AND soda_surveillance_question.Id_Question=0 ";
$resultQuestion=mysqli_query($bdd,$reqQuestionSurveillance);
$nbQuestion=mysqli_num_rows($resultQuestion);

if($nbQuestion > 0)
{
	while($rowQuestion=mysqli_fetch_array($resultQuestion))
	{
		$laQuestion="";
		$laQuestion.= $rowQuestion['QuestionAdditionnelle'];
		
		$checkC = "";
		$checkNC = "";
		$checkNA = "";
		$observation ="";
		$action = "";
		$cloture = "";
		$score="/".$rowQuestion['Ponderation'];
		
		if ($rowQuestion['Etat'] == "NC")
		{
			$checkNC = "X";
			$total=$total+$rowQuestion['Ponderation'];
			$score="0/".$rowQuestion['Ponderation'];
		}
		elseif ($rowQuestion['Etat'] == "C")
		{
			$score=$rowQuestion['Ponderation']."/".$rowQuestion['Ponderation'];
			$checkC = "X";
			$total=$total+$rowQuestion['Ponderation'];
			$C=$C+$rowQuestion['Ponderation'];
		}
		elseif($rowQuestion['Etat'] == "NA"){
			$score="";
			$checkNA = "X";
		}
		$observation = $rowQuestion['Commentaire'];
		$action = $rowQuestion['Action'];

		if($observation<>"" && $action<>""){
			$observation.="\n".$action;
		}
		elseif($observation=="" && $action<>""){
			$observation.=$action;
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($laQuestion)));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($checkC)));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($checkNC)));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($checkNA)));
		$sheet->setCellValue('H'.$ligne,utf8_encode($score));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($observation)));
		
		$sheet->getRowDimension($ligne)->setRowHeight(80);
		$sheet->getStyle('A'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('I'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->mergeCells('A'.$ligne.':D'.$ligne);
		$sheet->mergeCells('I'.$ligne.':J'.$ligne);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			
		$ligne++;
	}
}

if($LigneSurveillance['AutoriserQuestionsAdditionnelles'] > 0)
{
	for($k=1;$k<=5;$k++)
	{
		$sheet->setCellValue('H'.$ligne,utf8_encode("/1"));
		
		$sheet->getRowDimension($ligne)->setRowHeight(80);
		$sheet->getStyle('A'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->mergeCells('A'.$ligne.':D'.$ligne);
		$sheet->mergeCells('I'.$ligne.':J'.$ligne);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			
		$ligne++;
	}
}

$ligne++;
$sheet->setCellValue('B'.$ligne,utf8_encode('Taux de bonne réponses / Ratio of correct answers :'));
$sheet->mergeCells('E'.$ligne.':G'.$ligne);
$sheet->getStyle('E'.$ligne.':G'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

$sheet->getStyle('B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->setCellValue('I'.$ligne,utf8_encode('C : Conforme'));
$sheet->getStyle('I'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$ligne++;
$sheet->setCellValue('I'.$ligne,utf8_encode('NC : Non Conforme'));
$sheet->getStyle('I'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$ligne++;
$sheet->setCellValue('I'.$ligne,utf8_encode('NA : Non Applicable'));
$sheet->getStyle('I'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->setCellValue('C'.$ligne,utf8_encode('Taux de bonnes réponses requis :'));
$sheet->setCellValue('E'.$ligne,utf8_encode($LigneSurveillance['SeuilReussite'].'%'));
$sheet->mergeCells('E'.$ligne.':E'.($ligne+1));

$ligne++;
$sheet->setCellValue('C'.$ligne,utf8_encode('Requested ratio of correct answers :'));

$ligne++;
$ligne++;
$sheet->setCellValue('B'.$ligne,utf8_encode('Résultat / Result'));
$sheet->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
$sheet->getRowDimension($ligne)->setRowHeight(50);
$sheet->getStyle('B'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('C'.$ligne.':D'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->mergeCells('C'.$ligne.':D'.$ligne);

$ligne++;
$ligne++;
$sheet->setCellValue('B'.$ligne,utf8_encode('Commentaire / Comment'));
$sheet->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
$sheet->getRowDimension($ligne)->setRowHeight(50);
$sheet->getStyle('B'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('C'.$ligne.':I'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->mergeCells('C'.$ligne.':I'.$ligne);

$ligne++;
$ligne++;
$sheet->setCellValue('B'.$ligne,utf8_encode('Action(s) supplémentaire(s) / Additional Action(s) : '));
$sheet->getStyle('B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$sheet->setCellValue('F'.$ligne,utf8_encode('N°'));

$ligne++;
$sheet->setCellValue('B'.$ligne,utf8_encode('à traçer sur Action Tracker / To be tracked in Action Tracker : '));
$sheet->getStyle('B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$ligne++;
$ligne++;
$sheet->setCellValue('B'.$ligne,utf8_encode('Signature Surveillant / Supervisor'));
$sheet->mergeCells('B'.$ligne.':C'.$ligne);
$sheet->getStyle('B'.$ligne.':C'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->mergeCells('B'.($ligne+1).':C'.($ligne+1));

$sheet->setCellValue('E'.$ligne,utf8_encode('Signature Surveillé / Supervised'));
$sheet->mergeCells('E'.$ligne.':H'.$ligne);
$sheet->mergeCells('E'.($ligne+1).':H'.($ligne+1));
$sheet->getStyle('E'.$ligne.':H'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getRowDimension($ligne+1)->setRowHeight(80);

//PIED DE PAGE
$r = chr(13); 
$sheet->getHeaderFooter()->setOddFooter('&L' .$leD.$r.'25/10/2022' . '&C' .'AAA GROUP QUALITY MANAGEMENT DOCUMENT'.$r.'Reproductioninterdite sans autorisation ecrite de AAA GROUP' . '&R' . '&R &P / &N');
$sheet->getPageSetup()->setPrintArea('A1:J'.($ligne+1));

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Document.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/Document.xlsx';
$writer->save($chemin);
readfile($chemin);
?>