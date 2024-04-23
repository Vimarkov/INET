<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';


$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("Référence procédé"));
$sheet->setCellValue('B1',utf8_encode("N° MSN"));
$sheet->setCellValue('C1',utf8_encode("N° dossier"));
$sheet->setCellValue('D1',utf8_encode("Compagnons"));
$sheet->setCellValue('E1',utf8_encode("Qualiticiens"));
$sheet->setCellValue('F1',utf8_encode("Date du TERA"));
$sheet->setCellValue('G1',utf8_encode("Date du TERC"));

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);

$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT 
	IF(sp_atrot_aipi.Id_Qualification>0,(SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=sp_atrot_aipi.Id_Qualification),sp_atrot_aipi.Qualification) AS Qualif,
	sp_atrot.MSN,sp_atrot.OrdreMontage,sp_atrot.DateTERA,sp_atrot.DateTERC,sp_atrot_aipi.Id_OT
	FROM sp_atrot_aipi
	LEFT JOIN sp_atrot 
	ON sp_atrot_aipi.Id_OT=sp_atrot.Id 
	WHERE sp_atrot.Id_Prestation=463 
	AND sp_atrot.Id_StatutQUALITE='TERC'
	AND sp_atrot.Supprime=0 AND ";
	if($_SESSION['EXTRACT_PSMSN2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_PSMSN2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="sp_atrot.MSN=".$valeur." OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_PSReference2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_PSReference2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="IF(sp_atrot_aipi.Id_Qualification>0,(SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=sp_atrot_aipi.Id_Qualification),sp_atrot_aipi.Qualification) LIKE '%".addslashes($valeur)."%' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_PSDossier2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_PSDossier2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="sp_atrot.OrdreMontage='".$valeur."' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_PSCompagnon2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_PSCompagnon2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="(SELECT COUNT(Id) FROM sp_atrot_compagnon WHERE Id_OT=sp_atrot.Id AND Id_Personne=".$valeur.")>0 OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_PSIQ2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_PSIQ2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="(SELECT COUNT(Id) FROM sp_atrot_controleur WHERE Id_OT=sp_atrot.Id AND Id_Personne=".$valeur.")>0 OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_PSDu2']<>""){
		$req.=" ( ";
			$req.="DateTERA >= '". TrsfDate_($_SESSION['EXTRACT_PSDu2'])."' OR ";
			$req.="DateTERC >= '". TrsfDate_($_SESSION['EXTRACT_PSDu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_PSAu2']<>""){
		$req.=" ( ";
			$req.="DateTERA <= '". TrsfDate_($_SESSION['EXTRACT_PSAu2'])."' OR ";
			$req.="DateTERC <= '". TrsfDate_($_SESSION['EXTRACT_PSAu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_PSDateTERA2']<>""){
		$req.=" ( ";
			$req.="DateTERA = '". TrsfDate_($_SESSION['EXTRACT_PSDateTERA2'])."'" ;
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_PSDateTERC2']<>""){
		$req.=" ( ";
			$req.="DateTERC = '". TrsfDate_($_SESSION['EXTRACT_PSDateTERC2'])."'" ;
		$req.=" ) ";
		$req.=" AND ";
	}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$compagnon="";
		$req="SELECT 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_compagnon.Id_Personne) AS Compagnon ";
		$req.="FROM sp_atrot_compagnon LEFT JOIN sp_atrot ON sp_atrot.Id=sp_atrot_compagnon.Id_OT ";
		$req.="WHERE sp_atrot.Id_Prestation=463 AND sp_atrot.Id=".$row['Id_OT'];
		$resultCompagnon=mysqli_query($bdd,$req);
		$nbCompagnon=mysqli_num_rows($resultCompagnon);
		if ($nbCompagnon>0){	
			while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
				$compagnon.=$rowCompagnon['Compagnon']."  ";
			}
		}
		
		$controleur="";
		$req="SELECT 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_controleur.Id_Personne) AS Controleur ";
		$req.="FROM sp_atrot_controleur LEFT JOIN sp_atrot ON sp_atrot.Id=sp_atrot_controleur.Id_OT ";
		$req.="WHERE sp_atrot.Id_Prestation=463 AND sp_atrot.Id=".$row['Id_OT'];
		$resultCompagnon=mysqli_query($bdd,$req);
		$nbCompagnon=mysqli_num_rows($resultCompagnon);
		if ($nbCompagnon>0){	
			while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
				$controleur.=$rowCompagnon['Controleur']."  ";
			}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Qualif']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['OrdreMontage']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($compagnon));
		$sheet->setCellValue('E'.$ligne,utf8_encode($controleur));
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
header('Content-Disposition: attachment;filename="Extract_Procedes.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Procedes.xlsx';
$writer->save($chemin);
readfile($chemin);
?>