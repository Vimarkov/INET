<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");

function getJours($datedeb,$datefin){
    $nb_jours=1;
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

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° dossier"));
$sheet->setCellValue('C1',utf8_encode("Client"));
$sheet->setCellValue('D1',utf8_encode("Date 1ere intervention"));
$sheet->setCellValue('E1',utf8_encode("Date du TERC"));
$sheet->setCellValue('F1',utf8_encode("LT"));
$sheet->setCellValue('G1',utf8_encode("TAI"));
$sheet->setCellValue('H1',utf8_encode("Code usine"));
$sheet->setCellValue('I1',utf8_encode("Temps passé"));

$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(10);

$sheet->getStyle('A1:I1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_olwdossier.Id, sp_olwdossier.MSN,sp_olwdossier.Reference,(SELECT Libelle FROM sp_client WHERE Id=Id_Client) AS Client,
sp_olwficheintervention.DateTERC,sp_olwdossier.TAI_RestantACP,CodeUsine,TempsProd ";
$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwdossier.Id_Prestation=1598 AND sp_olwficheintervention.Id_StatutQUALITE='TERC' ";
if(TrsfDate_($_GET['du'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateTERC >= '".TrsfDate_($_GET['du'])."' ";
}
if(TrsfDate_($_GET['au'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateTERC <= '".TrsfDate_($_GET['au'])."' ";
}
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Client']));
		
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERC'])));
		
		$dateInter="0001-01-01";
		$req="SELECT DateIntervention FROM sp_olwficheintervention WHERE Id_Dossier=".$row['Id']." AND DateIntervention>'0001-01-01' ORDER BY DateIntervention ASC";
		$resultDateInter=mysqli_query($bdd,$req);
		$nbResultaDateInter=mysqli_num_rows($resultDateInter);
		if($nbResultaDateInter>0){
			$rowInter=mysqli_fetch_array($resultDateInter);
			$dateInter=$rowInter['DateIntervention'];
			$sheet->setCellValue('D'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowInter['DateIntervention'])));
		}

		if($dateInter>'0001-01-01' && $row['DateTERC']>'0001-01-01'){
			$sheet->setCellValue('F'.$ligne,utf8_encode(getJours($dateInter,$row['DateTERC'])));
		}
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['TAI_RestantACP']));
		
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['CodeUsine']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['TempsProd']));
		
		$sheet->getStyle('A'.$ligne.':I'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':I'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':I'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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