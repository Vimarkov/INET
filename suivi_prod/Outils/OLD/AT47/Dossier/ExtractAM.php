<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

function TrsfDate_($Date)
{
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('/', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
	}
	
	return $dateReq;
}
function AfficheDateFR($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("d/m/Y", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

$req2="SELECT Id,MSN,ImputationAAA,NCMajeure,OMAssocie,Id_Type,Recurrence,NumAMNC, ";
$req2.="(SELECT Designation FROM sp_atrot WHERE sp_atrot.OrdreMontage=sp_atram.OMAssocie) AS Designation, ";
$req2.="(SELECT Libelle FROM sp_atrtype WHERE sp_atrtype.Id=sp_atram.Id_Type) AS Type ";
$req="FROM sp_atram WHERE Id_Prestation=262 AND ";
if($_SESSION['AMMSN2']<>""){
	$tab = explode(";",$_SESSION['AMMSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="MSN=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMOMAssocie2']<>""){
	$tab = explode(";",$_SESSION['AMOMAssocie2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="OMAssocie='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMNumAMNC2']<>""){
	$tab = explode(";",$_SESSION['AMNumAMNC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="NumAMNC='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMImputationAAA2']<>""){
	$tab = explode(";",$_SESSION['AMImputationAAA2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="ImputationAAA=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMNCMajeure2']<>""){
	$tab = explode(";",$_SESSION['AMNCMajeure2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="NCMajeure=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMType2']<>""){
	$tab = explode(";",$_SESSION['AMType2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Type=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMRecurrence2']<>""){
	$tab = explode(";",$_SESSION['AMRecurrence2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Recurrence=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMDu2']<>"" || $_SESSION['AMAu2']<>""){
	$req.=" ( ";
	if($_SESSION['AMDu2']<>""){
		$req.="DateCreation >= '". TrsfDate_($_SESSION['AMDu2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['AMAu2']<>""){
		$req.="DateCreation <= '". TrsfDate_($_SESSION['AMAu2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

$reqFin="";
if($_SESSION['TriAMGeneral']<>""){
	$req.="ORDER BY ".substr($_SESSION['TriAMGeneral'],0,-1);
}
$result2=mysqli_query($bdd,$req2.$req.$reqFin);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° AM/NC"));
$sheet->setCellValue('C1',utf8_encode("Ordre de montage associé"));
$sheet->setCellValue('D1',utf8_encode("Désignation"));
$sheet->setCellValue('E1',utf8_encode("Imputation AAA"));
$sheet->setCellValue('F1',utf8_encode("NC majeure"));
$sheet->setCellValue('G1',utf8_encode("Type"));
$sheet->setCellValue('H1',utf8_encode("Récurrence"));

$sheet->getStyle('A1:H1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:H1')->getFont()->setBold(true);
$sheet->getStyle('A1:H1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(50);
$sheet->getColumnDimension('E')->setWidth(18);
$sheet->getColumnDimension('F')->setWidth(18);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(18);

$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	if($row2['ImputationAAA']==0){$imputationAAA="Non";}
	else{$imputationAAA="Oui";}
	if($row2['NCMajeure']==0){$ncMajeure="Non";}
	else{$ncMajeure="Oui";}
	if($row2['Recurrence']==0){$recurrence="Non";}
	else{$recurrence="Oui";}
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['NumAMNC']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['OMAssocie']));
	$sheet->setCellValue('D'.$ligne,utf8_encode(addslashes($row2['Designation'])));
	$sheet->setCellValue('E'.$ligne,utf8_encode($imputationAAA));
	$sheet->setCellValue('F'.$ligne,utf8_encode($ncMajeure));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['Type']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($recurrence));

	$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_AM.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_AM.xlsx';
$writer->save($chemin);
readfile($chemin);
?>