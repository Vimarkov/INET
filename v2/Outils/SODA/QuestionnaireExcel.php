<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Fonctions.php");

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();

$requeteSurveillance = "SELECT Id_Theme AS ID_Theme,
		(SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) AS Theme,
		Libelle AS Questionnaire,
		AutoriserQuestionsAdditionnelles,
		Id AS ID_Questionnaire,
		SeuilReussite
		FROM soda_questionnaire 
		WHERE Id=".$_GET['Id']."
		";

$resultSurveillance=mysqli_query($bdd,$requeteSurveillance);
$LigneSurveillance=mysqli_fetch_array($resultSurveillance);

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


$sheet->setCellValue('C4',utf8_encode($LigneSurveillance['Questionnaire']));

$sheet->getStyle('C4')->getAlignment()->setWrapText(true);

$total = 0;
$C = 0;

$reqQuestionSurveillance = "
SELECT
	Id,
	Question,
	Question_EN,
	ImageQuestion,
	Reponse,
	Reponse_EN,
	Ponderation
FROM
	soda_question
WHERE
	Suppr=0
	AND soda_question.Id_Questionnaire =".$LigneSurveillance['ID_Questionnaire']." 
ORDER BY Ordre	";

$resultQuestion=mysqli_query($bdd,$reqQuestionSurveillance);
$nbQuestion=mysqli_num_rows($resultQuestion);

$ligne=12;
if($nbQuestion > 0)
{
	while($rowQuestion=mysqli_fetch_array($resultQuestion))
	{
		$laQuestion= "FR : ".$rowQuestion['Question']."\nEN : ".$rowQuestion['Question_EN'];
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
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($laQuestion)));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes("/".$rowQuestion['Ponderation'])));

		
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