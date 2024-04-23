<?php
session_start();
require("../ConnexioniSansBody.php");
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$req2="SELECT Id,(SELECT Libelle FROM trame_categorie_faq WHERE trame_categorie_faq.Id=trame_faq.Id_Categorie) AS Categorie,";
$req2.="Question,Reponse ";
$req="FROM trame_faq WHERE ";
if($_SESSION['CategorieFAQ2']<>""){
	$tab = explode(";",$_SESSION['CategorieFAQ2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Categorie=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['QuestionFAQ2']<>""){
	$tab = explode(";",$_SESSION['QuestionFAQ2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Question LIKE '%".$valeur."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['ReponseFAQ2']<>""){
	$tab = explode(";",$_SESSION['ReponseFAQ2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Reponse LIKE '%".$valeur."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

if($_SESSION['TriGeneralFAQ']<>""){
	$req.="ORDER BY ".substr($_SESSION['TriGeneralFAQ'],0,-1);
}

$result=mysqli_query($bdd,$req2.$req);
$nbResulta=mysqli_num_rows($result);


$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Category"));
	$sheet->setCellValue('B1',utf8_encode("Question"));
	$sheet->setCellValue('C1',utf8_encode("Answer"));	
}
else{
	$sheet->setCellValue('A1',utf8_encode("Catégorie"));
	$sheet->setCellValue('B1',utf8_encode("Question"));
	$sheet->setCellValue('C1',utf8_encode("Réponse"));
}

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(80);
$sheet->getColumnDimension('C')->setWidth(80);

$sheet->getStyle('A1:C1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:C1')->getFont()->setBold(true);
$sheet->getStyle('A1:C1')->getFont()->getColor()->setRGB('1f49a6');


$ligne=2;
while($row2=mysqli_fetch_array($result)){
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['Categorie']));
	$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['Question']))));
	$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['Reponse']))));
	$sheet->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
	$sheet->getStyle('C'.$ligne)->getAlignment()->setWrapText(true);
	
	$sheet->getStyle('A'.$ligne.':C'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));	
	$ligne++;
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="FAQ.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/FAQ.xlsx';
$writer->save($chemin);
readfile($chemin);
?>