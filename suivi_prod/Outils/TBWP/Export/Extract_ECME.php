<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';


$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("Métier utilisateur"));
$sheet->setCellValue('B1',utf8_encode("Type ECME"));
$sheet->setCellValue('C1',utf8_encode("Référence ECME"));
$sheet->setCellValue('D1',utf8_encode("N° MSN"));
$sheet->setCellValue('E1',utf8_encode("N° Dossier"));
$sheet->setCellValue('F1',utf8_encode("Personne"));
$sheet->setCellValue('G1',utf8_encode("Date du TERA"));
$sheet->setCellValue('H1',utf8_encode("Date du TERC"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(30);
$sheet->getColumnDimension('F')->setWidth(40);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);

$sheet->getStyle('A1:H1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT IF(sp_fi_ecme.ProdQualite=0,'Production','Qualité') AS Metier,
	sp_fi_ecme.ProdQualite,
	IF(Id_ECME>0,(SELECT Libelle FROM sp_typeecme WHERE sp_typeecme.Id=(SELECT sp_ecme.Id_Type FROM sp_ecme WHERE sp_ecme.Id=sp_fi_ecme.Id_ECME)),
	(SELECT Libelle FROM sp_typeecme WHERE sp_typeecme.Id=sp_fi_ecme.Id_TypeECME)) AS TypeECME,
	IF(Id_ECME>0,(SELECT sp_ecme.Id_Type FROM sp_ecme WHERE sp_ecme.Id=sp_fi_ecme.Id_ECME),
	sp_fi_ecme.Id_TypeECME) AS Id_TypeECME,
	IF(sp_fi_ecme.Id_ECME>0,(SELECT Libelle FROM sp_ecme WHERE sp_ecme.Id=sp_fi_ecme.Id_ECME),sp_fi_ecme.ECME) AS Reference,
	(SELECT sp_dossier.MSN FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) AS MSN,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_ficheintervention.Id_QUALITE) AS Controleur,
	(SELECT DateIntervention FROM sp_ficheintervention AS sp_fi WHERE sp_fi.Id_StatutPROD='QARJ' AND sp_fi.Id_Dossier=sp_ficheintervention.Id_Dossier LIMIT 1) AS DateTERA,
	(SELECT DateInterventionQ FROM sp_ficheintervention AS sp_fi WHERE sp_fi.Id_StatutQUALITE='CERT' AND sp_fi.Id_Dossier=sp_ficheintervention.Id_Dossier LIMIT 1) AS DateTERC,
	(SELECT sp_dossier.Reference FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) AS ReferenceDossier,sp_fi_ecme.Id_FI
	FROM sp_fi_ecme
	LEFT JOIN sp_ficheintervention 
	ON sp_fi_ecme.Id_FI=sp_ficheintervention.Id 
	WHERE ";
	if($_SESSION['EXTRACT_ECMEMSN2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMEMSN2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="(SELECT sp_dossier.MSN FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier)=".$valeur." OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_ECMEMetier2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMEMetier2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="sp_fi_ecme.ProdQualite=".$valeur." OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_ECMEDossier2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMEDossier2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="(SELECT sp_dossier.Reference FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier)='".$valeur."' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_ECMEReference2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMEReference2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="Reference='".$valeur."' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_ECMEType2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMEType2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="Id_TypeECME=".$valeur." OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_ECMEDu2']<>""){
		$req.=" ( ";
			$req.="DateIntervention >= '". TrsfDate_($_SESSION['EXTRACT_ECMEDu2'])."' OR ";
			$req.="DateInterventionQ >= '". TrsfDate_($_SESSION['EXTRACT_ECMEDu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_ECMEAu2']<>""){
		$req.=" ( ";
			$req.="DateIntervention <= '". TrsfDate_($_SESSION['EXTRACT_ECMEAu2'])."' OR ";
			$req.="DateInterventionQ <= '". TrsfDate_($_SESSION['EXTRACT_ECMEAu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_ECMEDateTERA2']<>""){
		$req.=" ( ";
			$req.="DateIntervention = '". TrsfDate_($_SESSION['EXTRACT_ECMEDateTERA2'])."' AND Id_StatutPROD='QARJ' " ;
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_ECMEDateTERC2']<>""){
		$req.=" ( ";
			$req.="DateInterventionQ = '". TrsfDate_($_SESSION['EXTRACT_ECMEDateTERC2'])."' AND Id_StatutQUALITE='CERT' " ;
		$req.=" ) ";
		$req.=" AND ";
	}

if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$personne="";
		if($row['ProdQualite']==0){
			$req="SELECT 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_fi_travaileffectue.Id_Personne) AS Compagnon ";
			$req.="FROM sp_fi_travaileffectue LEFT JOIN sp_ficheintervention ON sp_ficheintervention.Id=sp_fi_travaileffectue.Id_FI ";
			$req.="WHERE sp_ficheintervention.Id=".$row['Id_FI'];
			$resultCompagnon=mysqli_query($bdd,$req);
			$nbCompagnon=mysqli_num_rows($resultCompagnon);
			if ($nbCompagnon>0){	
				while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
					$personne.=$rowCompagnon['Compagnon']."  ";
				}
			}
		}
		else{
			$personne.=$row['Controleur']."  ";
		}
		
		$dateTERA="0001-01-01";
		if($row['DateTERA']<>""){$dateTERA=$row['DateTERA'];}
		$dateTERC="0001-01-01";
		if($row['DateTERC']<>""){$dateTERC=$row['DateTERC'];}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Metier']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['TypeECME']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['ReferenceDossier']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($personne));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($dateTERA)));
		$sheet->setCellValue('H'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($dateTERC)));
	
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_ECME.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_ECME.xlsx';
$writer->save($chemin);
readfile($chemin);
?>