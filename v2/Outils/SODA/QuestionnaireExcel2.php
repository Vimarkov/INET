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

$excel = $workbook->load('Questionnaire.xlsx');

$sheet = $excel->getSheetByName('1');
if($LigneSurveillance['ID_Theme']==7){
	$sheet->setCellValue('C1',utf8_encode("SURVEILLANCE PROCÉDÉS / PROCESS SURVEILLANCE"));
}
elseif($LigneSurveillance['ID_Theme']==8){
	$sheet->setCellValue('C1',utf8_encode("SURVEILLANCE PROCESSUS / PROCESSUS SURVEILLANCE"));
}
else{
	$sheet->setCellValue('C1',utf8_encode("SURVEILLANCE OPERATIONNELLE / OPERATIONAL SURVEILLANCE"));
}

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

$ligne=9;
if($nbQuestion > 0)
{
	while($rowQuestion=mysqli_fetch_array($resultQuestion))
	{
		$laQuestion= "FR : ".$rowQuestion['Question']."\nEN : ".$rowQuestion['Question_EN'];
		$laReponse= "FR : ".$rowQuestion['Reponse']."\nEN : ".$rowQuestion['Reponse_EN'];
		
		$req="SELECT (SELECT Libelle FROM moris_client WHERE Id=Id_Client) AS Client 
		FROM soda_question_exceptionclient
		WHERE Suppr=0 AND Id_Question=".$rowQuestion['Id']." 
		ORDER BY Client ";
		$resultE=mysqli_query($bdd,$req);
		$nbE=mysqli_num_rows($resultE);
		$Exception="";
		if ($nbE > 0)
		{
			$Exception="Client : ";
			$liste="";
			while($rowE=mysqli_fetch_array($resultE))
			{
				if($liste<>""){$liste.=", ";}
				$liste.=$rowE['Client'];
			}
			$Exception.=$liste;
		}
		
		$req="SELECT (SELECT Num FROM moris_famille_r03 WHERE Id=Id_R03) AS R03 
		FROM soda_question_exceptionr03
		WHERE Suppr=0 AND Id_Question=".$rowQuestion['Id']." 
		ORDER BY R03 ";
		$resultE=mysqli_query($bdd,$req);
		$nbE=mysqli_num_rows($resultE);
		if ($nbE > 0)
		{
			if($Exception<>""){$Exception.="\n";}
			$Exception.="Famille R03 : ";
			$liste="";
			while($rowE=mysqli_fetch_array($resultE))
			{
				if($liste<>""){$liste.=", ";}
				$liste.=$rowE['R03'];
			}
			$Exception.=$liste;
		}
		
		$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER 
		FROM soda_question_exceptionuer
		WHERE Suppr=0 AND Id_Question=".$rowQuestion['Id']." 
		ORDER BY UER ";
		$resultE=mysqli_query($bdd,$req);
		$nbE=mysqli_num_rows($resultE);
		if ($nbE > 0)
		{
			if($Exception<>""){$Exception.="\n";}
			$Exception.="UER : ";
			$liste="";
			while($rowE=mysqli_fetch_array($resultE))
			{
				if($liste<>""){$liste.=", ";}
				$liste.=$rowE['UER'];
			}
			$Exception.=$liste;
		}
		
		$req="SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation 
		FROM soda_question_exceptionprestation
		WHERE Suppr=0 AND Id_Question=".$rowQuestion['Id']." 
		ORDER BY Prestation ";
		$resultE=mysqli_query($bdd,$req);
		$nbE=mysqli_num_rows($resultE);
		if ($nbE > 0)
		{
			if($Exception<>""){$Exception.="\n";}
			$Exception.="Prestation : ";
			$liste="";
			while($rowE=mysqli_fetch_array($resultE))
			{
				$presta=substr($rowE['Prestation'],0,strpos($rowE['Prestation']," "));
				if($presta==""){$presta=$rowE['Prestation'];}
				
				if($liste<>""){$liste.=", ";}
				$liste.=$presta;
			}
			$Exception.=$liste;
		}
		
		$tailleImage=0;
		if($rowQuestion['ImageQuestion']<>""){
			if(file_exists ('ImageQCM/'.$rowQuestion['ImageQuestion'])){
				//Insérer l'image
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setName(utf8_encode($rowQuestion['ImageQuestion']));
				$objDrawing->setDescription('PHPExcel logo');
				$objDrawing->setPath('ImageQCM/'.$rowQuestion['ImageQuestion']);
				$objDrawing->setCoordinates('B'.$ligne);
				$objDrawing->setOffsetX(3);
				$objDrawing->setOffsetY(200);
				$objDrawing->setWidth(380);
				$objDrawing->setWorksheet($sheet);
				$tailleImage=$objDrawing->getHeight();
			}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($rowQuestion['Id'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($laQuestion)));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($laReponse)));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($Exception)));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($rowQuestion['Ponderation'])));
		
		$sheet->getRowDimension($ligne)->setRowHeight(80);
		
		if($rowQuestion['ImageQuestion']<>""){
			if(file_exists ('ImageQCM/'.$rowQuestion['ImageQuestion'])){
				$sheet->getStyle('B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$sheet->getStyle('D'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$sheet->getRowDimension($ligne)->setRowHeight($sheet->getRowDimension($ligne)->getRowHeight()+$tailleImage+50);
			}
		}
		
		$sheet->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('D'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->mergeCells('B'.$ligne.':C'.$ligne);
		$sheet->mergeCells('D'.$ligne.':G'.$ligne);
		$sheet->mergeCells('H'.$ligne.':I'.$ligne);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			
		$ligne++;
	}
}

//PIED DE PAGE
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