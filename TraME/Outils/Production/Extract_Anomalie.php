<?php
session_start();
require("../ConnexioniSansBody.php");
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
include '../Fonctions.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$req2="SELECT Id,Reference,DateAnomalie,DatePrevisionnelle,DateReport,DateCloture,Probleme,ActionCurative,AnalyseCause,ActionPreventive,Observation, ";
$req2.="(SELECT Libelle FROM trame_origine WHERE trame_origine.Id=trame_anomalie.Id_Origine) AS Origine,YEAR(DateAnomalie) AS Annee,MONTH(DateAnomalie) AS Mois, ";
$req2.="(SELECT Libelle FROM trame_ponderation WHERE trame_ponderation.Id=trame_anomalie.Id_Ponderation) AS Ponderation, ";
$req2.="(SELECT Libelle FROM trame_familleerreur WHERE trame_familleerreur.Id=trame_anomalie.Id_FamilleErreur1) AS FamilleErreur1, ";
$req2.="(SELECT Libelle FROM trame_familleerreur WHERE trame_familleerreur.Id=trame_anomalie.Id_FamilleErreur2) AS FamilleErreur2, ";
$req2.="(SELECT Libelle FROM trame_responsable WHERE trame_responsable.Id=trame_anomalie.Id_Responsable) AS Responsable, ";
$req2.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_anomalie.Id_WP) AS WP, ";
$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_anomalie.Id_Createur) AS Createur ";
$req="FROM trame_anomalie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND ";
if($_SESSION['ANOM_Reference2']<>""){
	$tab = explode(";",$_SESSION['ANOM_Reference2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Reference='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['ANOM_WP2']<>""){
	$tab = explode(";",$_SESSION['ANOM_WP2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_WP=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['ANOM_Probleme2']<>""){
	$tab = explode(";",$_SESSION['ANOM_Probleme2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Probleme LIKE '%".$valeur."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['ANOM_Origine2']<>""){
	$tab = explode(";",$_SESSION['ANOM_Origine2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Origine=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['ANOM_Responsable2']<>""){
	$tab = explode(";",$_SESSION['ANOM_Responsable2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Responsable=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['ANOM_Createur2']<>""){
	$tab = explode(";",$_SESSION['ANOM_Createur2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Createur=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['ANOM_DateDebut2']<>"" || $_SESSION['ANOM_DateFin2']<>""){
	$req.=" ( ";
	if($_SESSION['ANOM_DateDebut2']<>""){
		$req.="DateAnomalie>= '". TrsfDate_($_SESSION['ANOM_DateDebut2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['ANOM_DateFin2']<>""){
		$req.="DateAnomalie <= '". TrsfDate_($_SESSION['ANOM_DateFin2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

$reqFin="";
if($_SESSION['TriANOM_General']<>""){
	$req.="ORDER BY ".substr($_SESSION['TriANOM_General'],0,-1);
}
$result2=mysqli_query($bdd,$req2.$req.$reqFin);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Date"));
	$sheet->setCellValue('B1',utf8_encode("Reference"));
	$sheet->setCellValue('C1',utf8_encode("Workpackage"));
	$sheet->setCellValue('D1',utf8_encode("Problem"));
	$sheet->setCellValue('E1',utf8_encode("Curative action"));
	$sheet->setCellValue('F1',utf8_encode("Causes analysis"));
	$sheet->setCellValue('G1',utf8_encode("Preventive action"));
	$sheet->setCellValue('H1',utf8_encode("Origin"));
	$sheet->setCellValue('I1',utf8_encode("Weighting"));
	$sheet->setCellValue('J1',utf8_encode("Responsible"));
	$sheet->setCellValue('K1',utf8_encode("Creator"));
	$sheet->setCellValue('L1',utf8_encode("Error family 1"));
	$sheet->setCellValue('M1',utf8_encode("Error family 2"));
	$sheet->setCellValue('N1',utf8_encode("Expected date"));
	$sheet->setCellValue('O1',utf8_encode("Date of reporting"));
	$sheet->setCellValue('P1',utf8_encode("Closing date"));
	$sheet->setCellValue('Q1',utf8_encode("Observation"));
	$sheet->setCellValue('R1',utf8_encode("Id"));
	$sheet->setCellValue('S1',utf8_encode("Year"));
	$sheet->setCellValue('T1',utf8_encode("Month"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Date"));
	$sheet->setCellValue('B1',utf8_encode("Référence"));
	$sheet->setCellValue('C1',utf8_encode("Workpackage"));
	$sheet->setCellValue('D1',utf8_encode("Problème"));
	$sheet->setCellValue('E1',utf8_encode("Action curative"));
	$sheet->setCellValue('F1',utf8_encode("Analyse cause"));
	$sheet->setCellValue('G1',utf8_encode("Action préventive"));
	$sheet->setCellValue('H1',utf8_encode("Origine"));
	$sheet->setCellValue('I1',utf8_encode("Pondération"));
	$sheet->setCellValue('J1',utf8_encode("Responsable"));
	$sheet->setCellValue('K1',utf8_encode("Créateur"));
	$sheet->setCellValue('L1',utf8_encode("Famille d'erreur 1"));
	$sheet->setCellValue('M1',utf8_encode("Famille d'erreur 2"));
	$sheet->setCellValue('N1',utf8_encode("Date prévisionnelle"));
	$sheet->setCellValue('O1',utf8_encode("Date report"));
	$sheet->setCellValue('P1',utf8_encode("Date de clôture"));
	$sheet->setCellValue('Q1',utf8_encode("Observation"));
	$sheet->setCellValue('R1',utf8_encode("Id"));
	$sheet->setCellValue('S1',utf8_encode("Année"));
	$sheet->setCellValue('T1',utf8_encode("Mois"));
}

$sheet->getStyle('A1:T1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:T1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:T1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:T1')->getFont()->setBold(true);
$sheet->getStyle('A1:T1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(50);
$sheet->getColumnDimension('E')->setWidth(50);
$sheet->getColumnDimension('F')->setWidth(50);
$sheet->getColumnDimension('G')->setWidth(50);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(25);
$sheet->getColumnDimension('L')->setWidth(25);
$sheet->getColumnDimension('M')->setWidth(25);
$sheet->getColumnDimension('N')->setWidth(15);
$sheet->getColumnDimension('O')->setWidth(15);
$sheet->getColumnDimension('P')->setWidth(15);
$sheet->getColumnDimension('Q')->setWidth(50);

$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	if(AfficheDateJJ_MM_AAAA($row2['DateAnomalie'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateAnomalie']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('A'.$ligne,$time);
		$sheet->getStyle('A'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['Reference']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['WP']));
	$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['Probleme']))));
	$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['ActionCurative']))));
	$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['AnalyseCause']))));
	$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['ActionPreventive']))));
	$sheet->setCellValue('H'.$ligne,utf8_encode($row2['Origine']));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Ponderation']));
	$sheet->setCellValue('J'.$ligne,utf8_encode($row2['Responsable']));
	$sheet->setCellValue('K'.$ligne,utf8_encode($row2['Createur']));
	$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['FamilleErreur1']))));
	$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['FamilleErreur2']))));
	if(AfficheDateJJ_MM_AAAA($row2['DatePrevisionnelle'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DatePrevisionnelle']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('N'.$ligne,$time);
		$sheet->getStyle('N'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	if(AfficheDateJJ_MM_AAAA($row2['DateReport'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateReport']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('O'.$ligne,$time);
		$sheet->getStyle('O'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	if(AfficheDateJJ_MM_AAAA($row2['DateCloture'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateCloture']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('P'.$ligne,$time);
		$sheet->getStyle('P'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('Q'.$ligne,utf8_encode(stripslashes($row2['Observation'])));
	
	$sheet->setCellValue('R'.$ligne,utf8_encode($row2['Id']));
	$sheet->setCellValue('S'.$ligne,utf8_encode($row2['Annee']));
	$sheet->setCellValue('T'.$ligne,utf8_encode($row2['Mois']));
	$sheet->getStyle('A'.$ligne.':T'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Anomalie.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_Anomalie.xlsx';
$writer->save($chemin);
readfile($chemin);
?>