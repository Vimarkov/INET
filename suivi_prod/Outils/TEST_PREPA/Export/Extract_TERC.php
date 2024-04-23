<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

function getJours($datedeb,$datefin){
    $nb_jours=0;
    $dated=explode('-',$datedeb);
    $datef=explode('-',$datefin);
    $timestampcurr=mktime(0,0,0,$dated[1],$dated[2],$dated[0]);
    $timestampf=mktime(0,0,0,$datef[1],$datef[2],$datef[0]);
    while($timestampcurr<$timestampf){
 
      if((date('w',$timestampcurr)!=0)&&(date('w',$timestampcurr)!=6)){
        $nb_jours++;
      }
$timestampcurr=mktime(0,0,0,date('m',$timestampcurr),(date('d',$timestampcurr)+1)   ,date('Y',$timestampcurr));
 
    }
return $nb_jours;
}

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
		elseif (preg_match("/Firefox/i", $_SERVER["HTTP_USER_AGENT"], $version)){
			$ub="Firefox";
			if(!preg_match_all('#(?<browser>'.join('|', array('Version', $ub, 'other')).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#', $_SERVER["HTTP_USER_AGENT"], $matches)) { }
			$i = count($matches['browser']);
			if($i !== 1) {
				if(strripos($_SERVER["HTTP_USER_AGENT"], "Version") < strripos($_SERVER["HTTP_USER_AGENT"], $ub)) {
					$version = $matches['version'][0];
				} else {
					$version = $matches['version'][1];
				}
			} else {
				$version = $matches['version'][0];
			}
			if($version == null) {
				$version = "";
			}
			if($version>="57.0"){
				$NavigOk = true;
			}
			else{
				$NavigOk = false;
			}
		} 
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

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("N° point folio"));
$sheet->setCellValue('B1',utf8_encode("N° OF/OT/Para"));
$sheet->setCellValue('C1',utf8_encode("N° NC"));
$sheet->setCellValue('D1',utf8_encode("N° AM"));
$sheet->setCellValue('E1',utf8_encode("Date du TERC"));
$sheet->setCellValue('F1',utf8_encode("Temps passé global"));
$sheet->setCellValue('G1',utf8_encode("LT TERA-TERC"));
$sheet->setCellValue('H1',utf8_encode("Programme"));
$sheet->setCellValue('I1',utf8_encode("MSN"));
$sheet->setCellValue('J1',utf8_encode("Travail à réaliser"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(50);

$sheet->getStyle('A1:J1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_olwdossier.Id, sp_olwdossier.Reference,sp_olwdossier.ReferenceAM,sp_olwdossier.ReferenceNC,sp_olwdossier.ReferencePF,
sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.DateTERA,sp_olwficheintervention.DateTERC,
sp_olwdossier.MSN,sp_olwdossier.Programme
FROM sp_olwficheintervention 
LEFT JOIN sp_olwdossier 
ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id 
WHERE sp_olwdossier.Id_Prestation=-15 
AND sp_olwficheintervention.Id_StatutQUALITE='TERC' ";
if(TrsfDate_($_GET['du'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateInterventionQ >= '".TrsfDate_($_GET['du'])."' ";
}
if(TrsfDate_($_GET['au'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateInterventionQ <= '".TrsfDate_($_GET['au'])."' ";
}
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['ReferencePF']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['ReferenceNC']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['ReferenceAM']));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateFR($row['DateInterventionQ'])));
		
		if($row['DateTERA']>'0001-01-01' && $row['DateTERC']>'0001-01-01'){
			$sheet->setCellValue('G'.$ligne,utf8_encode(getJours($row['DateTERA'],$row['DateTERC'])));
		}
		
		$req="SELECT sp_olwfi_travaileffectue.TempsPasse ";
		$req.="FROM sp_olwfi_travaileffectue LEFT JOIN sp_olwficheintervention ON sp_olwfi_travaileffectue.Id_FI=sp_olwficheintervention.Id ";
		$req.="WHERE sp_olwficheintervention.Id_Dossier=".$row['Id']." ";
		$result2=mysqli_query($bdd,$req);
		$nbResulta2=mysqli_num_rows($result2);
		$nb=0;
		
		if($nbResulta2>0){
			while($row2=mysqli_fetch_array($result2)){
				$nb=$nb+floatval($row2['TempsPasse']);
			}
		}
		$sheet->setCellValue('F'.$ligne,utf8_encode($nb));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['Programme']));
		
		$req="SELECT DISTINCT TravailRealise ";
		$req.="FROM sp_olwficheintervention ";
		$req.="WHERE Id_Dossier=".$row['Id']." ";
		$result2=mysqli_query($bdd,$req);
		$nbResulta2=mysqli_num_rows($result2);
		$travailEffectue="";
		
		if($nbResulta2>0){
			while($row2=mysqli_fetch_array($result2)){
				if($travailEffectue<>""){$travailEffectue.="\n";}
				$travailEffectue.=stripslashes($row2['TravailRealise']);
			}
		}
		$sheet->setCellValue('J'.$ligne,utf8_encode($travailEffectue));
		$sheet->getStyle('J'.$ligne)->getAlignment()->setWrapText(true);
		
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_TERC.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_TERC.xlsx';
$writer->save($chemin);
readfile($chemin);
?>