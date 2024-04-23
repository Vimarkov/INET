<?php
session_start();
require("../ConnexioniSansBody.php");
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
require("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('D-0833-094.xlsx');
$sheet = $excel->getSheetByName('CheckList');

$req="SELECT ";
$req.="(SELECT Libelle FROM trame_prestation WHERE trame_prestation.Id=trame_cl_version.Id_Prestation) AS Prestation, ";
$req.="(SELECT Id_PrestationExtra FROM trame_prestation WHERE trame_prestation.Id=trame_cl_version.Id_Prestation) AS Id_PrestationExtra, ";
$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_cl_version.Id_Personne) AS Personne, ";
$req.="(SELECT CONCAT(LEFT(Prenom,1),LEFT(Nom,1),RIGHT(Nom,1)) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_cl_version.Id_Personne) AS VISA, ";
$req.="(SELECT Libelle FROM trame_checklist WHERE trame_checklist.Id=trame_cl_version.Id_CL) AS CheckList, ";
$req.="(SELECT NumDQ FROM trame_checklist WHERE trame_checklist.Id=trame_cl_version.Id_CL) AS NumDQ, ";
$req.="trame_cl_version.NumVersion AS NumVersion,trame_cl_version.DateCL AS DateCL ";
$req.="FROM trame_cl_version WHERE Id=".$_GET['Version'];
$result=mysqli_query($bdd,$req);
$Ligne=mysqli_fetch_array($result);

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A11',utf8_encode("Themes"));
	$sheet->setCellValue('B11',utf8_encode("Weighting (EIS)"));
	$sheet->setCellValue('C11',utf8_encode("Points to check"));
	$sheet->setCellValue('H11',utf8_encode("Self-check"));
	$sheet->setCellValue('I11',utf8_encode("Cross control"));
	$sheet->setCellValue('J11',utf8_encode("Rechecking"));
	$sheet->setCellValue('K11',utf8_encode("Comment"));
}
else{
	$sheet->setCellValue('A11',utf8_encode("Thèmes"));
	$sheet->setCellValue('B11',utf8_encode("Pondération (EIS)"));
	$sheet->setCellValue('C11',utf8_encode("Points à vérifier"));
	$sheet->setCellValue('H11',utf8_encode("Auto-Contrôle"));
	$sheet->setCellValue('I11',utf8_encode("Contrôle croisé"));
	$sheet->setCellValue('J11',utf8_encode("Recontrôle"));
	$sheet->setCellValue('K11',utf8_encode("Commentaire"));
}

$Plateforme="";
if($Ligne['Id_PrestationExtra']>0){
	$req="SELECT new_competences_plateforme.Libelle 
		FROM new_competences_prestation 
		LEFT JOIN new_competences_plateforme
		ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
		WHERE new_competences_prestation.Id=".$Ligne['Id_PrestationExtra'];
	$resultPlat=mysqli_query($bdd,$req);
	$nbResultaPlat=mysqli_num_rows($resultPlat);
	if ($nbResultaPlat>0){ 
		$LignePlat=mysqli_fetch_array($resultPlat);
		$Plateforme=$LignePlat['Libelle']." / ";
	}
}

$sheet->setCellValue('K2',utf8_encode($Plateforme.$Ligne['Prestation']));
$sheet->getStyle('B11')->getAlignment()->setWrapText(true);
$sheet->setCellValue('C4',utf8_encode("D-0833-".$Ligne['NumDQ']." ".$Ligne['Prestation']." - ".$Ligne['CheckList']." "));
$sheet->setCellValue('B5',utf8_encode(AfficheDateJJ_MM_AAAA($Ligne['DateCL'])));
$sheet->setCellValue('D5',utf8_encode($Ligne['Personne']));
$sheet->setCellValue('I5',utf8_encode($Ligne['VISA']));

$sheet->getStyle('A11:K11')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
$sheet->mergeCells('C11:G11');

$sheet->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A11:K11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A11:K11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$req="SELECT Chapitre, Ponderation, Controle ";
$req.="FROM trame_cl_version_contenu LEFT JOIN trame_cl_version ON trame_cl_version_contenu.Id_VersionCL=trame_cl_version.Id ";
$req.="WHERE trame_cl_version_contenu.Id_VersionCL=".$_GET['Version'];
$req.=" ORDER BY trame_cl_version_contenu.Ordre";
$result=mysqli_query($bdd,$req);
$laLigne=12;
$nbResulta=mysqli_num_rows($result);
if ($nbResulta>0){
	while($row2=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$laLigne,utf8_encode(stripslashes(str_replace("\\","",$row2['Chapitre']))));
		$sheet->setCellValue('B'.$laLigne,utf8_encode(stripslashes(str_replace("\\","",$row2['Ponderation']))));
		$sheet->setCellValue('C'.$laLigne,utf8_encode(stripslashes(str_replace("\\","",$row2['Controle']))));
		
		$sheet->getStyle('B'.$laLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('H'.$laLigne.':K'.$laLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A'.$laLigne.':K'.$laLigne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$laLigne)->getBorders()->getLeft()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));
		$sheet->getStyle('B'.$laLigne)->getBorders()->getLeft()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));
		$sheet->getStyle('C'.$laLigne)->getBorders()->getLeft()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));
		$sheet->getStyle('H'.$laLigne)->getBorders()->getLeft()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));
		$sheet->getStyle('I'.$laLigne)->getBorders()->getLeft()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));
		$sheet->getStyle('J'.$laLigne)->getBorders()->getLeft()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));
		$sheet->getStyle('J'.$laLigne.':J'.$laLigne)->getBorders()->getRight()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));
		$sheet->getStyle('K'.$laLigne)->getBorders()->getLeft()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));
		$sheet->getStyle('K'.$laLigne.':K'.$laLigne)->getBorders()->getRight()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));
		
		$sheet->mergeCells('C'.$laLigne.':G'.$laLigne);
		$laLigne++;
	}
}

$sheet->getStyle('A'.($laLigne-1).':K'.($laLigne-1))->getBorders()->getBottom()->applyFromArray(array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000')));

$laLigne++;
$sheet->setCellValue('G'.$laLigne,utf8_encode("Signature"));

$sheet->getStyle('G'.$laLigne.':J'.$laLigne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('H'.$laLigne.':K'.$laLigne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
$laLigne++;

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A'.$laLigne,utf8_encode("Legend"));
	$sheet->setCellValue('B'.$laLigne,utf8_encode("OK=Item checked and good"));
	$sheet->setCellValue('B'.($laLigne+1),utf8_encode("KO=Item checked and corrected(Correction to be made by the manufacturing engineer)"));
	$sheet->setCellValue('B'.($laLigne+2),utf8_encode("N/A=Item not valid for this output data"));
	
	//PIED DE PAGE
	$r = chr(13); 
	$sheet->getHeaderFooter()->setOddFooter(utf8_encode('&L' .'D-0833 - Edition 1'.$r.'01/09/2017' . '&C' .'AAA Group QUALITY MANAGEMENT DOCUMENT'.$r.'Reproduction forbidden without written authorization by AAA Group' . '&R' . '&RPage &P / &N'));
}
else{
	$sheet->setCellValue('A'.$laLigne,utf8_encode("Légende"));
	$sheet->setCellValue('B'.$laLigne,utf8_encode("OK=Item vérifié et bon"));
	$sheet->setCellValue('B'.($laLigne+1),utf8_encode("KO=Item vérifié et à corriger(correction à apporter par le préparateur)"));
	$sheet->setCellValue('B'.($laLigne+2),utf8_encode("N/A=Item non valable pour cette donnée de sortie"));
	
	//PIED DE PAGE
	$r = chr(13); 
	$sheet->getHeaderFooter()->setOddFooter(utf8_encode('&L' .'D-0833 - Edition 1'.$r.'01/09/2017' . '&C' .'DOCUMENT QUALITE AAA GROUP'.$r.'Reproduction interdite sans l\'autorisation écrite de AAA GROUP' . '&R' . '&RPage &P / &N'));
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="CheckList.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/CheckList.xlsx';
$writer->save($chemin);
readfile($chemin);
?>