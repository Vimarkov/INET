<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';


$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("N° client"));
$sheet->setCellValue('B1',utf8_encode("Date fin étalonnage"));
$sheet->setCellValue('C1',utf8_encode("N° MSN"));
$sheet->setCellValue('D1',utf8_encode("N° Dossier"));
$sheet->setCellValue('E1',utf8_encode("Personne"));
$sheet->setCellValue('F1',utf8_encode("Date du TERA"));
$sheet->setCellValue('G1',utf8_encode("Date du TERC"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(40);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);

$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_olwfi_ecmeclient.NumClient,sp_olwfi_ecmeclient.DateFinEtalonnage,sp_olwfi_ecmeclient.Id_FI,
	(SELECT sp_olwdossier.MSN FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier) AS MSN,
	(SELECT sp_olwdossier.Reference FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier) AS Reference,
	sp_olwficheintervention.DateTERA,sp_olwficheintervention.DateTERC
	FROM sp_olwfi_ecmeclient
	LEFT JOIN sp_olwficheintervention 
	ON sp_olwfi_ecmeclient.Id_FI=sp_olwficheintervention.Id 
	WHERE (SELECT Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=1598 AND ";
	if($_SESSION['EXTRACT_ECMECLIENTMSN2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMECLIENTMSN2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="(SELECT sp_olwdossier.MSN FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=".$valeur." OR ";
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
				$req.="sp_olwfi_ecmeclient.NumClient='".$valeur."' OR ";
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
				$req.="(SELECT sp_olwdossier.Reference FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)='".$valeur."' OR ";
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
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS Compagnon ";
		$req.="FROM sp_olwfi_travaileffectue LEFT JOIN sp_olwficheintervention ON sp_olwficheintervention.Id=sp_olwfi_travaileffectue.Id_FI ";
		$req.="WHERE sp_olwficheintervention.Id=".$row['Id_FI'];
		$resultCompagnon=mysqli_query($bdd,$req);
		$nbCompagnon=mysqli_num_rows($resultCompagnon);
		$Compagnon="";
		if ($nbCompagnon>0){	
			while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
				$Compagnon.=$rowCompagnon['Compagnon']."  ";
			}
		}
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['NumClient']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFinEtalonnage'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($Compagnon));
		$sheet->setCellValue('F'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERA'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERC'])));
	
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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