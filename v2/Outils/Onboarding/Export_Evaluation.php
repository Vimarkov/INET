<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
						
//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Date'));
	$sheet->setCellValue('B1',utf8_encode('Personne'));
	$sheet->setCellValue('C1',utf8_encode('Temps passé vs. utilité des informations présentées'));
	$sheet->setCellValue('D1',utf8_encode('Facilité de navigation dans l’espace accueil'));
	$sheet->setCellValue('E1',utf8_encode('Commentaire'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Date'));
	$sheet->setCellValue('B1',utf8_encode('Person'));
	$sheet->setCellValue('C1',utf8_encode('Time spent vs. usefulness of the information presented'));
	$sheet->setCellValue('D1',utf8_encode('Ease of navigation in the home page'));
	$sheet->setCellValue('E1',utf8_encode('Comment'));
	
}

$sheet->getStyle('A1:E1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);
$sheet->getColumnDimension("C")->setWidth(60);
$sheet->getColumnDimension("D")->setWidth(60);
$sheet->getColumnDimension("E")->setWidth(60);

$req="SELECT 
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
NoteTemps,NoteFacilite,Commentaire,DateFeedback FROM onboarding_feedback ORDER BY DateFeedback DESC ";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);

if($nb>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($result)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFeedback'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['NoteTemps']));	
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['NoteFacilite']));	
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['Commentaire'])));		
		$sheet->getStyle('A'.$ligne.':E'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Export.xlsx';
$writer->save($chemin);
readfile($chemin);
?>