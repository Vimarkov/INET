<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';


$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("Type"));
$sheet->setCellValue('B1',utf8_encode("N° client"));
$sheet->setCellValue('C1',utf8_encode("Date fin étalonnage"));
$sheet->setCellValue('D1',utf8_encode("N° MSN"));
$sheet->setCellValue('E1',utf8_encode("N° Dossier"));
$sheet->setCellValue('F1',utf8_encode("Personne"));
$sheet->setCellValue('G1',utf8_encode("Date du TERA"));
$sheet->setCellValue('H1',utf8_encode("Date du TERC"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(40);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);

$sheet->getStyle('A1:H1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_atrot_ecmeclient.NumClient,sp_atrot_ecmeclient.DateFinEtalonnage,
	(SELECT Libelle FROM sp_atrtypeecme WHERE sp_atrtypeecme.Id=sp_atrot_ecmeclient.Id_Type) AS Type,
	sp_atrot.MSN,sp_atrot.OrdreMontage,sp_atrot.DateTERA,sp_atrot.DateTERC,sp_atrot_ecmeclient.Id_OT
	FROM sp_atrot_ecmeclient
	LEFT JOIN sp_atrot 
	ON sp_atrot_ecmeclient.Id_OT=sp_atrot.Id 
	WHERE sp_atrot.Id_Prestation=262 
	AND sp_atrot.Supprime=0 AND ";
	if($_SESSION['EXTRACT_ECMECLIENTMSN2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMECLIENTMSN2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="sp_atrot.MSN=".$valeur." OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_ECMECLIENTClient2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMECLIENTClient2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="sp_atrot_ecmeclient.NumClient='".$valeur."' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_ECMECLIENTDossier2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMECLIENTDossier2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="sp_atrot.OrdreMontage='".$valeur."' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="DateFinEtalonnage='".TrsfDate_($valeur)."' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_ECMECLIENTDu2']<>""){
		$req.=" ( ";
			$req.="DateTERA >= '". TrsfDate_($_SESSION['EXTRACT_ECMECLIENTDu2'])."' OR ";
			$req.="DateTERC >= '". TrsfDate_($_SESSION['EXTRACT_ECMECLIENTDu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_ECMECLIENTAu2']<>""){
		$req.=" ( ";
			$req.="DateTERA <= '". TrsfDate_($_SESSION['EXTRACT_ECMECLIENTAu2'])."' OR ";
			$req.="DateTERC <= '". TrsfDate_($_SESSION['EXTRACT_ECMECLIENTAu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_ECMECLIENTDateTERA2']<>""){
		$req.=" ( ";
			$req.="DateTERA = '". TrsfDate_($_SESSION['EXTRACT_ECMECLIENTDateTERA2'])."'" ;
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_ECMECLIENTDateTERC2']<>""){
		$req.=" ( ";
			$req.="DateTERC = '". TrsfDate_($_SESSION['EXTRACT_ECMECLIENTDateTERC2'])."'" ;
		$req.=" ) ";
		$req.=" AND ";
	}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$req="SELECT 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_compagnon.Id_Personne) AS Compagnon ";
		$req.="FROM sp_atrot_compagnon LEFT JOIN sp_atrot ON sp_atrot.Id=sp_atrot_compagnon.Id_OT ";
		$req.="WHERE sp_atrot.Id_Prestation=262 AND sp_atrot.Id=".$row['Id_OT'];
		$resultCompagnon=mysqli_query($bdd,$req);
		$nbCompagnon=mysqli_num_rows($resultCompagnon);
		$Compagnon="";
		if ($nbCompagnon>0){	
			while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
				$Compagnon.=$rowCompagnon['Compagnon']."  ";
			}
		}
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Type']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['NumClient']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFinEtalonnage'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['OrdreMontage']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($Compagnon));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERA'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERC'])));
	
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="EXTRACT_ECMECLIENT.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/EXTRACT_ECMECLIENT.xlsx';
$writer->save($chemin);
readfile($chemin);
?>