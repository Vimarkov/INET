<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Fonctions.php");

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
if($_GET['Langue']=="FR"){
$excel = $workbook->load('D-0901-FR.xlsx');
}
else{
$excel = $workbook->load('D-0901-EN.xlsx');	
}

$requeteSurveillance = "
    SELECT
        new_surveillances_questionnaire.ID_Theme,
        (SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme,
        new_surveillances_questionnaire.Nom AS Questionnaire,
        new_surveillances_questionnaire.ID AS ID_Questionnaire
    FROM new_surveillances_questionnaire
     WHERE new_surveillances_questionnaire.ID=".$_GET['Id'];

$resultSurveillance=mysqli_query($bdd,$requeteSurveillance);
$LigneSurveillance=mysqli_fetch_array($resultSurveillance);

if($LigneSurveillance['ID_Theme']==2){$leD="D-0902 - Issue 2";}
else{$leD="D-0901 - Issue 2";}

$sheet = $excel->getSheetByName('1');

$sheet->setCellValue('C3',utf8_encode("N° "));
$sheet->setCellValue('C4',utf8_encode($LigneSurveillance['Questionnaire']));
$sheet->getStyle('C4')->getAlignment()->setWrapText(true);

$reqQuestion = "
	SELECT
		new_surveillances_question.ID,
		new_surveillances_question.Numero,
		new_surveillances_question.Question,
		new_surveillances_question.Question_EN,
		new_surveillances_question.Reponse,
		new_surveillances_question.Reponse_EN,
		new_surveillances_question.Modifiable
	FROM
		new_surveillances_question
	LEFT JOIN new_surveillances_questionnaire
		ON new_surveillances_questionnaire.ID = new_surveillances_question.ID_Questionnaire
	WHERE
		new_surveillances_questionnaire.ID =".$LigneSurveillance['ID_Questionnaire']."
		AND new_surveillances_question.Supprime =0
	ORDER BY
		new_surveillances_question.Numero ;";
$resultQuestion=mysqli_query($bdd,$reqQuestion);
$nbQuestion=mysqli_num_rows($resultQuestion);


if($nbQuestion > 0)
{
	$ligne=12;
	while($rowQuestion=mysqli_fetch_array($resultQuestion))
	{
		$laQuestion="";
		if($_GET['Langue']=="FR"){
			$laQuestion.= stripslashes($rowQuestion['Question'])."\n\n".stripslashes($rowQuestion['Reponse']);
		}
		else{
			$laQuestion.= stripslashes($rowQuestion['Question_EN'])."\n\n".stripslashes($rowQuestion['Reponse_EN']);
		}
		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($rowQuestion['Numero'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($laQuestion)));
		
		$sheet->getRowDimension($ligne)->setRowHeight(80);
		$sheet->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->mergeCells('B'.$ligne.':D'.$ligne);
		$sheet->mergeCells('H'.$ligne.':J'.$ligne);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			
		$ligne++;
	}
}

$ligne++;
if($_GET['Langue']=="FR"){$sheet->setCellValue('B'.$ligne,utf8_encode('Taux de bonne réponses :'));}
else{$sheet->setCellValue('B'.$ligne,utf8_encode('Ratio of correct answers :'));}
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
$sheet->setCellValue('E'.$ligne,utf8_encode('80%'));
$sheet->mergeCells('E'.$ligne.':E'.($ligne+1));

$ligne++;
$sheet->setCellValue('C'.$ligne,utf8_encode('Requested ratio of correct answers :'));

$ligne++;
$ligne++;
if($_GET['Langue']=="FR"){$sheet->setCellValue('B'.$ligne,utf8_encode('Résultat'));}
else{$sheet->setCellValue('B'.$ligne,utf8_encode('Result'));}
$sheet->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
$sheet->getRowDimension($ligne)->setRowHeight(50);
$sheet->getStyle('B'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('C'.$ligne.':D'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->mergeCells('C'.$ligne.':D'.$ligne);

$ligne++;
$ligne++;
if($_GET['Langue']=="FR"){$sheet->setCellValue('B'.$ligne,utf8_encode('Action(s) supplémentaire(s) : '));}
else{$sheet->setCellValue('B'.$ligne,utf8_encode('Additional Action(s) : '));}
$sheet->getStyle('B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$sheet->setCellValue('F'.$ligne,utf8_encode('N°'));

$ligne++;
if($_GET['Langue']=="FR"){$sheet->setCellValue('B'.$ligne,utf8_encode('à traçer sur Action Tracker : '));}
else{$sheet->setCellValue('B'.$ligne,utf8_encode('To be tracked in Action Tracker : '));}
$sheet->getStyle('B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$ligne++;
$ligne++;
if($_GET['Langue']=="FR"){$sheet->setCellValue('B'.$ligne,utf8_encode('Signature Surveillant'));}
else{$sheet->setCellValue('B'.$ligne,utf8_encode('Supervisor'));}
$sheet->mergeCells('B'.$ligne.':C'.$ligne);
$sheet->getStyle('B'.$ligne.':C'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->mergeCells('B'.($ligne+1).':C'.($ligne+1));

if($_GET['Langue']=="FR"){$sheet->setCellValue('E'.$ligne,utf8_encode('Signature Surveillé'));}
else{$sheet->setCellValue('E'.$ligne,utf8_encode('Supervised'));}
$sheet->mergeCells('E'.$ligne.':H'.$ligne);
$sheet->mergeCells('E'.($ligne+1).':H'.($ligne+1));
$sheet->getStyle('E'.$ligne.':H'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getRowDimension($ligne+1)->setRowHeight(80);


//PIED DE PAGE
$r = chr(13); 
$sheet->getHeaderFooter()->setOddFooter('&L' .$leD.$r.'03/03/2021' . '&C' .'AAA GROUP QUALITY MANAGEMENT DOCUMENT'.$r.'Reproductioninterdite sans autorisation ecrite de AAA GROUP' . '&R' . '&R &P / &N');
$sheet->getPageSetup()->setPrintArea('A1:J'.($ligne+1));

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="'.str_replace("/"," ",str_replace("'","",$LigneSurveillance['Questionnaire'])).'.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/'.str_replace("/"," ",str_replace("'","",$LigneSurveillance['Questionnaire'])).'.xlsx';
$writer->save($chemin);
readfile($chemin);
?>