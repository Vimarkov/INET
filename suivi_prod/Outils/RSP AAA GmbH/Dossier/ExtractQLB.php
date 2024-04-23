<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$req2="SELECT Id,MSN,NumCQLB,NumCV,ImputationAAA,OMAssocie,AMAssociee,Id_Type,Recurrence, ";
$req2.="Designation, ";
$req2.="(SELECT Libelle FROM sp_localisation WHERE sp_localisation.Id=sp_atrcqlb.Id_Localisation) AS Localisation, ";
$req2.="(SELECT Libelle FROM sp_atrtype WHERE sp_atrtype.Id=sp_atrcqlb.Id_Type) AS Type ";
$req="FROM sp_atrcqlb WHERE Id_Prestation=1047 AND ";
if($_SESSION['CQLBMSN2']<>""){
	$tab = explode(";",$_SESSION['CQLBMSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="MSN=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CQLBNumCQLB2']<>""){
	$tab = explode(";",$_SESSION['CQLBNumCQLB2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="NumCQLB='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CQLBNumCV2']<>""){
	$tab = explode(";",$_SESSION['CQLBNumCV2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="NumCV='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CQLBLocalisation2']<>""){
	$tab = explode(";",$_SESSION['CQLBLocalisation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Localisation='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CQLBImputationAAA2']<>""){
	$tab = explode(";",$_SESSION['CQLBImputationAAA2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="ImputationAAA=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CQLBOMAssocie2']<>""){
	$tab = explode(";",$_SESSION['CQLBOMAssocie2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="OMAssocie='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CQLBAMAssociee2']<>""){
	$tab = explode(";",$_SESSION['CQLBAMAssociee2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="AMAssociee='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CQLBType2']<>""){
	$tab = explode(";",$_SESSION['CQLBType2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Type=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CQLBRecurrence2']<>""){
	$tab = explode(";",$_SESSION['CQLBRecurrence2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Recurrence=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CQLBDu2']<>"" || $_SESSION['CQLBAu2']<>""){
	$req.=" ( ";
	if($_SESSION['CQLBDu2']<>""){
		$req.="DateCreation >= '". TrsfDate_($_SESSION['CQLBDu2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['CQLBAu2']<>""){
		$req.="DateCreation <= '". TrsfDate_($_SESSION['CQLBAu2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

$reqFin="";		
if($_SESSION['TriCQLBGeneral']<>""){
	$req.="ORDER BY ".substr($_SESSION['TriCQLBGeneral'],0,-1);
}

$result2=mysqli_query($bdd,$req2.$req.$reqFin);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° CQLB"));
$sheet->setCellValue('C1',utf8_encode("N° CV"));
$sheet->setCellValue('D1',utf8_encode("Désignation"));
$sheet->setCellValue('E1',utf8_encode("Localisation"));
$sheet->setCellValue('F1',utf8_encode("Imputation AAA"));
$sheet->setCellValue('G1',utf8_encode("Ordre de montage associé"));
$sheet->setCellValue('H1',utf8_encode("AM associée"));
$sheet->setCellValue('I1',utf8_encode("Type"));
$sheet->setCellValue('J1',utf8_encode("Récurrence"));

$sheet->getStyle('A1:J1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:J1')->getFont()->setBold(true);
$sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(18);
$sheet->getColumnDimension('D')->setWidth(40);
$sheet->getColumnDimension('E')->setWidth(18);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(18);
$sheet->getColumnDimension('H')->setWidth(18);
$sheet->getColumnDimension('I')->setWidth(18);
$sheet->getColumnDimension('J')->setWidth(18);

$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	if($row2['ImputationAAA']==0){$imputationAAA="Non";}
	else{$imputationAAA="Oui";}
	if($row2['Recurrence']==0){$recurrence="Non";}
	else{$recurrence="Oui";}
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['NumCQLB']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['NumCV']));
	$sheet->setCellValue('D'.$ligne,utf8_encode(addslashes($row2['Designation'])));
	$sheet->setCellValue('E'.$ligne,utf8_encode($row2['Localisation']));
	$sheet->setCellValue('F'.$ligne,utf8_encode($imputationAAA));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['OMAssocie']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($row2['AMAssociee']));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Type']));
	$sheet->setCellValue('J'.$ligne,utf8_encode($recurrence));

	$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_CQLB.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_CQLB.xlsx';
$writer->save($chemin);
readfile($chemin);
?>