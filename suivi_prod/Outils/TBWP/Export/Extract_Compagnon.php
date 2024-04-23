<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

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

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° OF"));
$sheet->setCellValue('C1',utf8_encode("Compagnon ayant réalisé le travail"));
$sheet->setCellValue('D1',utf8_encode("Contrôleur ayant CERT"));
$sheet->setCellValue('E1',utf8_encode("AIPI/AIPS"));

$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(45);

$sheet->getStyle('A1:E1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_ficheintervention.Id, ";
$req.="(SELECT sp_dossier.MSN FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) AS MSN,";
$req.="(SELECT sp_dossier.Reference FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) AS Reference,";
$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_fi_travaileffectue.Id_Personne) AS Compagnon, ";
$req.="(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Tab1.Id_QUALITE) ";
$req.="FROM sp_ficheintervention AS Tab1 LEFT JOIN sp_dossier AS Tab2 ON Tab2.Id=Tab1.Id_Dossier ";
$req.="WHERE Tab1.Id_StatutQUALITE='CERT' AND Tab1.Id_QUALITE<>0 AND Tab2.Id=sp_ficheintervention.Id_Dossier LIMIT 1) AS Controleur ";
$req.="FROM sp_fi_travaileffectue LEFT JOIN sp_ficheintervention ON sp_fi_travaileffectue.Id_FI=sp_ficheintervention.Id ";
$req.="WHERE ";
$req.="(SELECT COUNT(sp_ficheintervention.Id) FROM sp_ficheintervention AS Tab1 LEFT JOIN sp_dossier AS Tab2 ON Tab2.Id=Tab1.Id_Dossier ";
$req.="WHERE Tab1.Id_StatutQUALITE='CERT' AND Tab2.Id=sp_ficheintervention.Id_Dossier)>0 AND ";
if($_SESSION['Extract_MSN2']<>""){
	$tab = explode(";",$_SESSION['Extract_MSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="(SELECT sp_dossier.MSN FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier)=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Zone2']<>""){
	$tab = explode(";",$_SESSION['Extract_Zone2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="(SELECT sp_dossier.Id_ZoneDeTravail FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier)=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Pole2']<>""){
	$tab = explode(";",$_SESSION['Extract_Pole2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Id_Pole=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Vacation2']<>""){
	$tab = explode(";",$_SESSION['Extract_Vacation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Vacation='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Urgence2']<>""){
	$tab = explode(";",$_SESSION['Extract_Urgence2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="(SELECT sp_dossier.Id_Urgence FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier)=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_SansDate2']=="oui"){
	$req.=" ( ";
	$req.="sp_ficheintervention.DateIntervention <= '0001-01-01' OR ";
}
if($_SESSION['Extract_Du2']<>"" || $_SESSION['Extract_Au2']<>""){
	$req.=" ( ";
	if($_SESSION['Extract_Du2']<>""){
		$req.="sp_ficheintervention.DateIntervention >= '". TrsfDate_($_SESSION['Extract_Du2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['Extract_Au2']<>""){
		$req.="sp_ficheintervention.DateIntervention <= '". TrsfDate_($_SESSION['Extract_Au2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
if($_SESSION['Extract_SansDate2']=="oui"){
	$req.=" ) ";
}
if($_SESSION['Extract_SansDate2']=="oui" || $_SESSION['Extract_Du2']<>"" || $_SESSION['Extract_Au2']<>""){
	$req.=" AND ";
}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
$req.=" ORDER BY (SELECT sp_dossier.Reference FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) ASC";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Compagnon']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Controleur']));
		
		$liste="";
		$req="SELECT new_competences_qualification.Libelle ";
		$req.="FROM sp_fi_aipi ";
		$req.="LEFT JOIN new_competences_qualification ";
		$req.="ON sp_fi_aipi.Id_Qualification = new_competences_qualification.Id ";
		$req.="WHERE sp_fi_aipi.Id_FI=".$row['Id']." ";
		$resultAIPI=mysqli_query($bdd,$req);
		$nbAIPI=mysqli_num_rows($resultAIPI);
		if ($nbAIPI>0){	
			while($rowAIPI=mysqli_fetch_array($resultAIPI)){
				$liste.=$rowAIPI['Libelle']." \n";
			}
		}
		$sheet->getStyle('E'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('E'.$ligne,utf8_encode($liste));
		$sheet->getStyle('A'.$ligne.':E'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Compagnon.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Compagnon.xlsx';
$writer->save($chemin);
readfile($chemin);
?>