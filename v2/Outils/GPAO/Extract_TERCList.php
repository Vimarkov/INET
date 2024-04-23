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
$sheet->setCellValue('A1',utf8_encode('MSN'));
$sheet->setCellValue('B1',utf8_encode('Customer'));
$sheet->setCellValue('C1',utf8_encode('Position'));
$sheet->setCellValue('D1',utf8_encode('Type'));
$sheet->setCellValue('E1',utf8_encode('Imputation'));
$sheet->setCellValue('F1',utf8_encode('AM'));
$sheet->setCellValue('G1',utf8_encode('OF/OT'));
$sheet->setCellValue('H1',utf8_encode('NC'));
$sheet->setCellValue('I1',utf8_encode('QLB'));
$sheet->setCellValue('J1',utf8_encode('Para'));
$sheet->setCellValue('K1',utf8_encode('TLB'));
$sheet->setCellValue('L1',utf8_encode('Concession'));
$sheet->setCellValue('M1',utf8_encode('Designation'));
$sheet->setCellValue('N1',utf8_encode('Status'));
$sheet->setCellValue('O1',utf8_encode('Creation Date'));
$sheet->setCellValue('P1',utf8_encode('Closure Date'));

$sheet->getStyle('A1:P1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$req="SELECT (SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
	(SELECT Position FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Position,
	(SELECT (SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Type,
	(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
	(SELECT Libelle FROM gpao_imputation WHERE Id=Id_Imputation) AS Imputation,
	(SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) AS Status,
	(SELECT Libelle FROM gpao_imputationrework WHERE Id=Id_ImputationRework) AS ImputationRework,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=gpao_statutquality.Id_UserName) AS UserName,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_NameResponsible) AS NameResponsible,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_NameResponsible2) AS NameResponsible2,
	(SELECT Libelle FROM gpao_qualitycontroltype WHERE Id=Id_QualityControlType) AS Category,
	DateStatut,StatusComments,NC,AM,OF,QLB,TLB,Concession,Para,Designation,gpao_wo.CreationDate,
	gpao_wo.ClosureDate
	FROM gpao_statutquality 
	LEFT JOIN gpao_wo
	ON gpao_statutquality.Id_WO=gpao_wo.Id
	WHERE gpao_statutquality.Suppr=0 
	AND gpao_wo.Suppr=0 
	AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
	AND ClosureDate>'0001-01-01'
	AND ((SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'TERC CLOSED' 
		OR (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'TERC CUSTOMER' 
		OR (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'PARA STAMPED SENT' 
		)
	ORDER BY ClosureDate DESC";

$resultRapport=mysqli_query($bdd,$req);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['MSN'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Customer'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Position'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Type'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['Imputation'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['AM'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['OF'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['NC'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['QLB'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['Para'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['TLB'])));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($row['Concession'])));
		$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes($row['Designation'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode(stripslashes($row['Status'])));
		$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($row['CreationDate'])));
		$sheet->setCellValue('P'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['ClosureDate'])));

		$sheet->getStyle('A'.$ligne.':P'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_TERCList.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_TERCList.xlsx';
$writer->save($chemin);
readfile($chemin);
?>