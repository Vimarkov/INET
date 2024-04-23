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

$req="SELECT IF(sp_atrot_ecme.ProdQualite=0,'Production','Qualité') AS Metier,
	sp_atrot_ecme.ProdQualite,
	IF(Id_ECME>0,(SELECT Libelle FROM sp_atrtypeecme WHERE sp_atrtypeecme.Id=(SELECT sp_atrecme.Id_Type FROM sp_atrecme WHERE sp_atrecme.Id=sp_atrot_ecme.Id_ECME)),
	(SELECT Libelle FROM sp_atrtypeecme WHERE sp_atrtypeecme.Id=sp_atrot_ecme.Id_TypeECME)) AS TypeECME,
	IF(Id_ECME>0,(SELECT sp_atrecme.Id_Type FROM sp_atrecme WHERE sp_atrecme.Id=sp_atrot_ecme.Id_ECME),
	sp_atrot_ecme.Id_TypeECME) AS Id_TypeECME,
	IF(sp_atrot_ecme.Id_ECME>0,(SELECT Libelle FROM sp_atrecme WHERE sp_atrecme.Id=sp_atrot_ecme.Id_ECME),sp_atrot_ecme.Reference) AS Reference,
	sp_atrot.MSN,sp_atrot.OrdreMontage,sp_atrot.DateTERA,sp_atrot.DateTERC,sp_atrot_ecme.Id_OT
	FROM sp_atrot_ecme
	LEFT JOIN sp_atrot 
	ON sp_atrot_ecme.Id_OT=sp_atrot.Id 
	WHERE sp_atrot.Id_Prestation=463 
	AND sp_atrot.Supprime=0 AND ";
	if($_SESSION['EXTRACT_ECMEMSN2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_ECMEMSN2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="sp_atrot.MSN=".$valeur." OR ";
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
				$req.="sp_atrot_ecme.ProdQualite=".$valeur." OR ";
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
				$req.="sp_atrot.OrdreMontage='".$valeur."' OR ";
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
			$req.="DateTERA >= '". TrsfDate_($_SESSION['EXTRACT_ECMEDu2'])."' OR ";
			$req.="DateTERC >= '". TrsfDate_($_SESSION['EXTRACT_ECMEDu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_ECMEAu2']<>""){
		$req.=" ( ";
			$req.="DateTERA <= '". TrsfDate_($_SESSION['EXTRACT_ECMEAu2'])."' OR ";
			$req.="DateTERC <= '". TrsfDate_($_SESSION['EXTRACT_ECMEAu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_ECMEDateTERA2']<>""){
		$req.=" ( ";
			$req.="DateTERA = '". TrsfDate_($_SESSION['EXTRACT_ECMEDateTERA2'])."'" ;
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_ECMEDateTERC2']<>""){
		$req.=" ( ";
			$req.="DateTERC = '". TrsfDate_($_SESSION['EXTRACT_ECMEDateTERC2'])."'" ;
		$req.=" ) ";
		$req.=" AND ";
	}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$personne="";
		if($row['ProdQualite']==0){
			$req="SELECT 
				(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_compagnon.Id_Personne) AS Compagnon ";
			$req.="FROM sp_atrot_compagnon LEFT JOIN sp_atrot ON sp_atrot.Id=sp_atrot_compagnon.Id_OT ";
			$req.="WHERE sp_atrot.Id_Prestation=463 AND sp_atrot.Id=".$row['Id_OT'];
			$resultCompagnon=mysqli_query($bdd,$req);
			$nbCompagnon=mysqli_num_rows($resultCompagnon);
			if ($nbCompagnon>0){	
				while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
					$personne.=$rowCompagnon['Compagnon']."  ";
				}
			}
		}
		else{
			$req="SELECT 
				(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_controleur.Id_Personne) AS Controleur ";
			$req.="FROM sp_atrot_controleur LEFT JOIN sp_atrot ON sp_atrot.Id=sp_atrot_controleur.Id_OT ";
			$req.="WHERE sp_atrot.Id_Prestation=463 AND sp_atrot.Id=".$row['Id_OT'];
			$resultControleur=mysqli_query($bdd,$req);
			$nbConroleur=mysqli_num_rows($resultControleur);
			if ($nbConroleur>0){	
				while($rowControleur=mysqli_fetch_array($resultControleur)){
					$personne.=$rowControleur['Controleur']."  ";
				}
			}
		}
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Metier']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['TypeECME']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['OrdreMontage']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($personne));
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
header('Content-Disposition: attachment;filename="Extract_ECME.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_ECME.xlsx';
$writer->save($chemin);
readfile($chemin);
?>