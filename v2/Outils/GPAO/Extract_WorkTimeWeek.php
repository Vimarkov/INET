<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
$sheet->setCellValue('A1',utf8_encode('Worker'));
$sheet->setCellValue('B1',utf8_encode('Year'));
$sheet->setCellValue('C1',utf8_encode('Week'));
$sheet->setCellValue('D1',utf8_encode('Productive Time'));
$sheet->setCellValue('E1',utf8_encode('Idle Time'));
$sheet->setCellValue('F1',utf8_encode('Total'));

$sheet->getStyle('A1:F1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$annee=$_GET['Annee'];
$semaine=$_GET['Semaine'];

$req="SELECT Id_Worker,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Worker) AS Worker,
	SUM(ProductiveTime) AS SommeProductiveTime,
	SUM(IdleTime) AS SommeIdleTime
	FROM gpao_productionsheet 
	LEFT JOIN gpao_wo
	ON gpao_productionsheet.Id_WO=gpao_wo.Id
	WHERE gpao_productionsheet.Suppr=0 
	AND gpao_wo.Suppr=0 
	AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
	AND YEAR(DateProd)='".$annee."' ";
if($semaine<>""){
	$req.="AND WEEK(DateProd)='".$semaine."' ";
}
$req.="	GROUP BY Id_Worker 
	ORDER BY Worker";

$resultRapport=mysqli_query($bdd,$req);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}

		$total=$row['SommeProductiveTime']+$row['SommeIdleTime'];		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Worker'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($annee)));
		$sheet->setCellValue('C'.$ligne,utf8_encode($semaine));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['SommeProductiveTime'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['SommeIdleTime'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($total)));

		
		$sheet->getStyle('A'.$ligne.':F'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_WorkTimeWeek.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_WorkTimeWeek.xlsx';
$writer->save($chemin);
readfile($chemin);
?>