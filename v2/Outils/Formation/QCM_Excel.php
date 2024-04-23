<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("Globales_Fonctions.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$ReqQCM_Langue="
    SELECT
        Id,
        Id_QCM,
        Id_Langue,
        Libelle,
        Date_MAJ,
        Id_Personne_MAJ,
        (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne_MAJ) AS Personne
    FROM
        form_qcm_langue
    WHERE
        Id=".$_GET['Id_QCM_Langue'];
$ResultQCM_Langue=mysqli_query($bdd,$ReqQCM_Langue);
$RowQCM_Langue=mysqli_fetch_array($ResultQCM_Langue);

$ReqQCM="
    SELECT
        Id,
        Code,
        (SELECT Libelle FROM form_client WHERE form_client.Id=form_qcm.Id_Client) AS Client,
        Nb_Question,
        Id_QCM_Lie
    FROM
        form_qcm
    WHERE
        Id=".$RowQCM_Langue['Id_QCM'];
$ResultQCM=mysqli_query($bdd,$ReqQCM);
$RowQCM=mysqli_fetch_array($ResultQCM);

$sheet = $workbook->getActiveSheet();

$sheet->setTitle(utf8_encode('QCM'));

$sheet->mergeCells('A1:B4');
$sheet->mergeCells('C1:I1');
$sheet->mergeCells('C2:E2');
$sheet->mergeCells('C3:E3');
$sheet->mergeCells('F2:G3');
$sheet->mergeCells('H2:I3');
$sheet->mergeCells('C4:E4');

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../../Images/Logos/Logo_Doc_Group.png');
$objDrawing->setHeight(120);
$objDrawing->setWidth(190);
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(3);
$objDrawing->setOffsetY(30);
$objDrawing->setWorksheet($sheet);

$titre="QCM: ".$RowQCM_Langue['Libelle'];
$explication1="Cocher la (les) bonne(s) réponse(s). Il peut y avoir 1, 2 ou 3 bonnes réponses dans la colonne \"Réponse\".";
$explication2="Attention, le coefficient varie selon l'importance des questions.";
$explication3="Si une réponse est cochée alors qu'elle n'aurait pas dû l'être, cela engendre la perte totale des points pour la question concernée.
                1 bonne réponse / 3 = 0,33 point - 1 bonne réponse / 2 = 0,5 point - 2 bonnes réponses / 3 = 0,66 point";
if($RowQCM_Langue['Id_Langue']<>1)
{
    $titre="MCQ: ".$RowQCM_Langue['Libelle'];
    $explication1="Tick the relevant answer(s). There can be 1, 2 or 3 correct answers in the \"Answer\" column.";
    $explication2="Caution, the coefficient of the answers varies depending on the questions importance.";
    $explication3="If an answer is ticked when it shouldn't be, it will result in the total lost of the points for the concerned question.
                    1 good answer / 3 = 0.33 point - 1 good answer / 2 = 0.5 point - 2 good answers / 3 = 0.66 point";
}
$sheet->getStyle('A1:I5')->getFont()->setName('Arial');
$sheet->setCellValue('C1',utf8_encode($titre));
$sheet->getStyle('C1')->getFont()->setSize(24);
$sheet->getStyle('C1')->getAlignment()->setWrapText(true);
$sheet->setCellValue('C2',utf8_encode($explication1));
$sheet->getStyle('C2')->getAlignment()->setWrapText(true);
$sheet->setCellValue('C3',utf8_encode($explication2));
$sheet->getStyle('C3')->getAlignment()->setWrapText(true);
$sheet->getStyle('C3')->getFont()->setBold(true);
$sheet->setCellValue('C4',utf8_encode($explication3));
$sheet->getStyle('C4')->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:I5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('C2:C4')->getFont()->setSize(11);
$sheet->getStyle('F2:I3')->getFont()->setSize(12);
$sheet->getStyle('H2:I3')->getAlignment()->setWrapText(true);
$sheet->getStyle('C1')->getFont()->setBold(true);
$sheet->getStyle('C2:C4')->getFont()->getColor()->setRGB('0039c2');

$sheet->setCellValue('F2',utf8_encode('CODE'));
if(substr($RowQCM['Code'],0,4)=="AIPI")
{
    $PositionSlash=strpos($RowQCM['Code'], "/");
    $sheet->setCellValue('H2',utf8_encode(substr($RowQCM['Code'],0,$PositionSlash)."\n".substr($RowQCM['Code'],$PositionSlash+1)));
}
else{$sheet->setCellValue('H2',utf8_encode($RowQCM['Code']));}
//$sheet->setCellValue('H2',utf8_encode($RowQCM['Code']));
if($RowQCM['Client']<>"")
{
    $sheet->setCellValue('F4',utf8_encode('CLIENT'));
    $sheet->setCellValue('H4',utf8_encode($RowQCM['Client']));
    $sheet->mergeCells('F4:G4');
    $sheet->mergeCells('H4:I4');
}
else
{
    $sheet->mergeCells('F4:I4');
}
$sheet->getStyle('F2:I4')->getFont()->setBold(true);

$sheet->getColumnDimension('A')->setWidth(5);
//Pour 1er calcul de la hauteur B = B+C
$sheet->getColumnDimension('B')->setWidth(54);
$sheet->getColumnDimension('C')->setWidth(32);
$sheet->getColumnDimension('D')->setWidth(90);
$sheet->getColumnDimension('E')->setWidth(8);
$sheet->getColumnDimension('F')->setWidth(8);
$sheet->getColumnDimension('G')->setWidth(8);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(12);
$sheet->getColumnDimension('J')->setWidth(10);

$sheet->getRowDimension(1)->setRowHeight(60);
$sheet->getRowDimension(2)->setRowHeight(15);
$sheet->getRowDimension(3)->setRowHeight(15);
$sheet->getRowDimension(4)->setRowHeight(30);
$sheet->getRowDimension(5)->setRowHeight(30);
$sheet->getRowDimension(6)->setRowHeight(20);
$sheet->getStyle('A1:B4')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('C1:I1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('F2:I4')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('C4:E4')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

if($RowQCM_Langue['Id_Langue']==1)
{
    $sheet->setCellValue('A5',utf8_encode("Mis à jour le : ".AfficheDateJJ_MM_AAAA($RowQCM_Langue['Date_MAJ'])));
    $sheet->setCellValue('I5',utf8_encode("Par : ".$RowQCM_Langue['Personne']));
    
    $sheet->setCellValue('A6',utf8_encode("N°"));
    $sheet->setCellValue('B6',utf8_encode("Question"));
    $sheet->setCellValue('D6',utf8_encode("Choix"));
    $sheet->setCellValue('E6',utf8_encode("Réponse"));
    $sheet->setCellValue('F6',utf8_encode("Résultat"));
    $sheet->setCellValue('G6',utf8_encode("Note"));
    $sheet->setCellValue('H6',utf8_encode("Coefficient"));
    $sheet->setCellValue('I6',utf8_encode("Total"));
    $sheet->setCellValue('J6',utf8_encode("Solution"));
}
else
{
    $sheet->setCellValue('A5',utf8_encode("Updated the : ".AfficheDateJJ_MM_AAAA($RowQCM_Langue['Date_MAJ'])));
    $sheet->setCellValue('I5',utf8_encode("By : ".$RowQCM_Langue['Personne']));
    
    $sheet->setCellValue('A6',utf8_encode("No"));
    $sheet->setCellValue('B6',utf8_encode("Question"));
    $sheet->setCellValue('D6',utf8_encode("Choice"));
    $sheet->setCellValue('E6',utf8_encode("Reply"));
    $sheet->setCellValue('F6',utf8_encode("Result"));
    $sheet->setCellValue('G6',utf8_encode("Note"));
    $sheet->setCellValue('H6',utf8_encode("Coefficient"));
    $sheet->setCellValue('I6',utf8_encode("Total"));
    $sheet->setCellValue('J6',utf8_encode("Solution"));
}

$sheet->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$sheet->mergeCells('B6:C6');
$sheet->getStyle('A6:J6')->getFont()->setBold(true);
$sheet->getStyle('A6:J6')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fff800'))));
$sheet->getStyle('A6:J6')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A6:J6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A6:J6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A6:J6')->getBorders()->applyFromArray(array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A6:J6')->getBorders()->applyFromArray(array('top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A6')->getBorders()->applyFromArray(array('left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('J6')->getBorders()->applyFromArray(array('right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM ,'color' => array('rgb' => '#000000'))));

//Liste des questions
$req="SELECT Id, Coefficient, Type, Libelle, Fichier,Id_QCM_Langue,Num FROM form_qcm_langue_question WHERE Suppr=0 AND Id_QCM_Langue=".$_GET['Id_QCM_Langue']." ORDER BY Num";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

//Liste des réponses
$req="SELECT form_qcm_langue_question_reponse.Id, form_qcm_langue_question_reponse.Libelle, form_qcm_langue_question_reponse.Valeur,form_qcm_langue_question_reponse.Fichier, ";
$req.="form_qcm_langue_question_reponse.Id_QCM_Langue_Question FROM form_qcm_langue_question_reponse LEFT JOIN form_qcm_langue_question ";
$req.="ON form_qcm_langue_question_reponse.Id_QCM_Langue_Question = form_qcm_langue_question.Id ";
$req.="WHERE form_qcm_langue_question_reponse.Suppr=0 AND form_qcm_langue_question.Id_QCM_Langue=".$_GET['Id_QCM_Langue']." ORDER BY form_qcm_langue_question_reponse.Num " ;
$resultReponse=mysqli_query($bdd,$req);
$nbResultaReponse=mysqli_num_rows($resultReponse);
$nbCoeff=0;
$ligne=7;
$ligneReponse=6;
$num=1;
if($nbResulta>0){
	while($rowQuestion=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($rowQuestion['Num'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($rowQuestion['Libelle'])));
		$tailleImage=0;
		if($rowQuestion['Fichier']<>""){
			if(file_exists ('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$rowQuestion['Id_QCM_Langue'].'/'.$rowQuestion['Fichier'])){
				//Insérer l'image
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setName(utf8_encode($rowQuestion['Fichier']));
				$objDrawing->setDescription('PHPExcel logo');
				$objDrawing->setPath('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$rowQuestion['Id_QCM_Langue'].'/'.$rowQuestion['Fichier']);
				$objDrawing->setCoordinates('B'.$ligne);
				$objDrawing->setOffsetX(3);
				$objDrawing->setOffsetY(100);
				$objDrawing->setWorksheet($sheet);
				$tailleImage=$objDrawing->getHeight();
			}
		}
		$sheet->setCellValue('G'.$ligne,utf8_encode('/1'));
		$sheet->setCellValue('H'.$ligne,utf8_encode($rowQuestion['Coefficient']));
		$sheet->getStyle('H'.$ligne)->getFont()->setSize(16);
		$sheet->getStyle('H'.$ligne)->getFont()->setBold(true);
		$sheet->setCellValue('I'.$ligne,utf8_encode('/'.$rowQuestion['Coefficient']));
		$nbCoeff+=$rowQuestion['Coefficient'];
		$sheet->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('A'.$ligne.':D'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('G'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
		$sheet->getStyle('H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('I'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('G'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$sheet->getStyle('H'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('I'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$sheet->getRowDimension($ligne)->setRowHeight(-1);
		if($rowQuestion['Fichier']<>""){
			if(file_exists ('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$rowQuestion['Id_QCM_Langue'].'/'.$rowQuestion['Fichier'])){
				$sheet->getStyle('B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$sheet->getRowDimension($ligne)->setRowHeight($sheet->getRowDimension($ligne)->getRowHeight()+$tailleImage+50);
			}
		}
		$hauteurTotal=$sheet->getRowDimension($ligne)->getRowHeight();
		//Parcours des réponses
		if($nbResultaReponse>0){
			mysqli_data_seek($resultReponse,0);
			while($rowReponse=mysqli_fetch_array($resultReponse)){
				if($rowReponse['Id_QCM_Langue_Question']==$rowQuestion['Id']){
					$ligneReponse++;
					$sheet->setCellValue('D'.$ligneReponse,utf8_encode(stripslashes($rowReponse['Libelle'])));
					$tailleImage2=0;
					if($rowReponse['Fichier']<>""){
						if(file_exists ('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$rowQuestion['Id_QCM_Langue'].'/'.$rowReponse['Fichier'])){
							//Insérer l'image
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName(utf8_encode($rowReponse['Fichier']));
							$objDrawing->setDescription('PHPExcel logo');
							$objDrawing->setPath('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$rowQuestion['Id_QCM_Langue'].'/'.$rowReponse['Fichier']);
							$objDrawing->setCoordinates('D'.$ligneReponse);
							$objDrawing->setOffsetX(3);
							$objDrawing->setOffsetY(50);
							$objDrawing->setWorksheet($sheet);
							$tailleImage2=$objDrawing->getHeight();
						}
					}
					$vrai="OUI";
					$faux="NON";
					if($RowQCM_Langue['Id_Langue']<>1){
						$vrai="YES";
						$faux="NO";
					}
					if($rowReponse['Valeur']==0){$sheet->setCellValue('J'.$ligneReponse,utf8_encode($faux));}
					else{$sheet->setCellValue('J'.$ligneReponse,utf8_encode($vrai));}
					$sheet->getStyle('J'.$ligneReponse)->getFont()->setBold(true);
					$sheet->getStyle('J'.$ligneReponse)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheet->getStyle('D'.$ligneReponse)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$sheet->getStyle('D'.$ligneReponse)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$sheet->getStyle('D'.$ligneReponse.':F'.$ligneReponse)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
					$sheet->getStyle('D'.$ligneReponse)->getAlignment()->setWrapText(true);
					$sheet->getStyle('J'.$ligneReponse)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
					$sheet->getRowDimension($ligneReponse)->setRowHeight(-1);
					if($rowReponse['Fichier']<>""){
						if(file_exists ('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$rowQuestion['Id_QCM_Langue'].'/'.$rowReponse['Fichier'])){
							$sheet->getStyle('D'.$ligneReponse)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
							$sheet->getRowDimension($ligneReponse)->setRowHeight($sheet->getRowDimension($ligneReponse)->getRowHeight()+$tailleImage2+50);
						}
					}
				}
			}
		}
		if($ligneReponse>$ligne){
			$nbReponse=$ligneReponse-$ligne;
			$hauteurLigne=$hauteurTotal/$nbReponse;
			for($laLigne=$ligne;$laLigne<=$ligneReponse;$laLigne++){
				if($sheet->getRowDimension($laLigne)->getRowHeight()<$hauteurLigne){
					$sheet->getRowDimension($laLigne)->setRowHeight($hauteurLigne);
				}
			}
			$sheet->mergeCells('A'.$ligne.':A'.$ligneReponse);
			$sheet->mergeCells('B'.$ligne.':C'.$ligneReponse);
			$sheet->mergeCells('G'.$ligne.':G'.$ligneReponse);
			$sheet->mergeCells('H'.$ligne.':H'.$ligneReponse);
			$sheet->mergeCells('I'.$ligne.':I'.$ligneReponse);
			$ligne=$ligneReponse;
		}
		else{
			$sheet->mergeCells('B'.$ligne.':C'.$ligne);
			$ligneReponse++;
		}
		$sheet->getStyle('D'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM ,'color' => array('rgb' => '#000000'))));
		$ligne++;
		$num++;
	}
}
if($ligneReponse>6){
	$sheet->getStyle('A6:J'.$ligneReponse)->getBorders()->applyFromArray(array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A6:J'.$ligneReponse)->getBorders()->applyFromArray(array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A6:A'.$ligneReponse)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('G6:I'.$ligneReponse)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('H6:H'.$ligneReponse)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'c0c0c0'))));
	$sheet->getStyle('B6:C'.$ligneReponse)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM ,'color' => array('rgb' => '#000000'))));
}
//RESULTAT
$ligneResultat = $ligne;
if($RowQCM_Langue['Id_Langue']==1){
	$sheet->setCellValue('A'.$ligneResultat,utf8_encode('Renseigné par : '));
	$sheet->setCellValue('D'.$ligneResultat,utf8_encode('Date et signature : '));
	$sheet->setCellValue('E'.$ligneResultat,utf8_encode('TOTAL : '));
	$sheet->setCellValue('A'.($ligneResultat+1),utf8_encode('Corrigé par : '));
	$sheet->setCellValue('D'.($ligneResultat+1),utf8_encode('Date et signature : '));
	$sheet->setCellValue('E'.($ligneResultat+1),utf8_encode('% : '));
}
else{
	$sheet->setCellValue('A'.$ligneResultat,utf8_encode('Filled in by: : '));
	$sheet->setCellValue('D'.$ligneResultat,utf8_encode('Date and signature : '));
	$sheet->setCellValue('E'.$ligneResultat,utf8_encode('TOTAL : '));
	$sheet->setCellValue('A'.($ligneResultat+1),utf8_encode('Corrected by : '));
	$sheet->setCellValue('D'.($ligneResultat+1),utf8_encode('Date and signature : '));
	$sheet->setCellValue('E'.($ligneResultat+1),utf8_encode('% : '));
}
$sheet->setCellValue('H'.$ligneResultat,utf8_encode('/'.$nbCoeff));

$sheet->getRowDimension($ligneResultat)->setRowHeight(41);
$sheet->getRowDimension($ligneResultat+1)->setRowHeight(41);
$sheet->mergeCells('A'.$ligneResultat.':C'.$ligneResultat);
$sheet->mergeCells('E'.$ligneResultat.':G'.$ligneResultat);
$sheet->mergeCells('H'.$ligneResultat.':I'.$ligneResultat);

$sheet->mergeCells('A'.($ligneResultat+1).':C'.($ligneResultat+1));
$sheet->mergeCells('E'.($ligneResultat+1).':I'.($ligneResultat+1));
$sheet->getStyle('A'.$ligneResultat.':I'.($ligneResultat+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneResultat.':D'.($ligneResultat+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$sheet->getStyle('E'.$ligneResultat.':I'.($ligneResultat+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('E'.$ligneResultat)->getFont()->setSize(18);
$sheet->getStyle('H'.$ligneResultat)->getFont()->setSize(20);
$sheet->getStyle('E'.($ligneResultat+1))->getFont()->setSize(18);
$sheet->getStyle('E'.$ligneResultat.':I'.($ligneResultat+1))->getFont()->setBold(true);
$sheet->getStyle('A'.$ligneResultat.':D'.($ligneResultat+1))->applyFromArray(array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE)));
$sheet->getStyle('H'.$ligneResultat)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(35);

//AFFICHAGE
$sheet->getSheetView()->setZoomScale(90);
$sheet->getPageSetup()->setPrintArea('A1:I'.($ligneResultat+1));
$sheet->getPageSetup()->setOrientation('landscape'); //orientation paysage
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);

//REPETITION DES EN-TETES
$sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 6);

//PIED DE PAGE
$r = chr(13); 
if($RowQCM_Langue['Id_Langue']==1){
	$sheet->getHeaderFooter()->setOddFooter(utf8_encode('&L' .'Edition 1'.$r.'24/02/2014' . '&C' .'DOCUMENT QUALITE'.$r.'® Copyright protected' . '&R' . '&RPage &P / &N'));
}
else{
	$sheet->getHeaderFooter()->setOddFooter(utf8_encode('&L' .'Edition 1'.$r.'24/02/2014' . '&C' .'QUALITY DOCUMENT'.$r.'® Copyright protected' . '&R' . '&RPage &P / &N'));
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($RowQCM_Langue['Id_Langue']==1){
	header('Content-Disposition: attachment;filename="QCM.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="MCQ.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/QCM.xlsx';

$writer->save($chemin);
readfile($chemin);
?>