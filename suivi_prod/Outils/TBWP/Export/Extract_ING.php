<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';


$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("Ingrédient"));
$sheet->setCellValue('B1',utf8_encode("N° lot"));
$sheet->setCellValue('C1',utf8_encode("Date péremption"));
$sheet->setCellValue('D1',utf8_encode("Coeff hygrométrique"));
$sheet->setCellValue('E1',utf8_encode("Température"));
$sheet->setCellValue('F1',utf8_encode("N° MSN"));
$sheet->setCellValue('G1',utf8_encode("N° Dossier"));
$sheet->setCellValue('H1',utf8_encode("Personne"));
$sheet->setCellValue('I1',utf8_encode("Date du TERA"));
$sheet->setCellValue('J1',utf8_encode("Date du TERC"));

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(40);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(20);

$sheet->getStyle('A1:J1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_fi_ingredient.NumLot,sp_fi_ingredient.DatePeremption,sp_fi_ingredient.CoeffHydrometrique,
	sp_fi_ingredient.Temperature,
	IF(sp_fi_ingredient.Id_Ingredient>0,(SELECT Libelle FROM sp_ingredient WHERE sp_ingredient.Id=sp_fi_ingredient.Id_Ingredient),sp_fi_ingredient.Ingredient) AS Ingredient2,
	(SELECT sp_dossier.MSN FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) AS MSN,
	(SELECT sp_dossier.Reference FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) AS Reference,
	(SELECT DateIntervention FROM sp_ficheintervention AS sp_fi WHERE sp_fi.Id_StatutPROD='QARJ' AND sp_fi.Id_Dossier=sp_ficheintervention.Id_Dossier LIMIT 1) AS DateTERA,
	(SELECT DateInterventionQ FROM sp_ficheintervention AS sp_fi WHERE sp_fi.Id_StatutQUALITE='CERT' AND sp_fi.Id_Dossier=sp_ficheintervention.Id_Dossier LIMIT 1) AS DateTERC,
	sp_fi_ingredient.Id_FI
	FROM sp_fi_ingredient
	LEFT JOIN sp_ficheintervention 
	ON sp_fi_ingredient.Id_FI=sp_ficheintervention.Id 
	WHERE ";
	if($_SESSION['EXTRACT_INGMSN2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_INGMSN2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="(SELECT sp_dossier.MSN FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier)=".$valeur." OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_INGIngredient2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_INGIngredient2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="IF(sp_fi_ingredient.Id_Ingredient>0,(SELECT Libelle FROM sp_ingredient WHERE sp_ingredient.Id=sp_fi_ingredient.Id_Ingredient),sp_fi_ingredient.Ingredient) LIKE '%".$valeur."%' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_INGDossier2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_INGDossier2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="(SELECT sp_dossier.Reference FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier)='".$valeur."' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_INGNumLot2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_INGNumLot2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="NumLot='".$valeur."' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_INGDatePeremption2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_INGDatePeremption2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="DatePeremption='".TrsfDate_($valeur)."' OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
	if($_SESSION['EXTRACT_INGDu2']<>""){
		$req.=" ( ";
			$req.="DateIntervention >= '". TrsfDate_($_SESSION['EXTRACT_INGDu2'])."' OR ";
			$req.="DateInterventionQ >= '". TrsfDate_($_SESSION['EXTRACT_INGDu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_INGAu2']<>""){
		$req.=" ( ";
			$req.="DateIntervention <= '". TrsfDate_($_SESSION['EXTRACT_INGAu2'])."' OR ";
			$req.="DateInterventionQ <= '". TrsfDate_($_SESSION['EXTRACT_INGAu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_INGDateTERA2']<>""){
		$req.=" ( ";
			$req.="DateIntervention = '". TrsfDate_($_SESSION['EXTRACT_INGDateTERA2'])."' AND Id_StatutPROD='QARJ' " ;
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_INGDateTERC2']<>""){
		$req.=" ( ";
			$req.="DateInterventionQ = '". TrsfDate_($_SESSION['EXTRACT_INGDateTERC2'])."' AND Id_StatutQUALITE='CERT' " ;
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
		
		$dateTERA="0001-01-01";
		if($row['DateTERA']<>""){$dateTERA=$row['DateTERA'];}
		$dateTERC="0001-01-01";
		if($row['DateTERC']<>""){$dateTERC=$row['DateTERC'];}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Ingredient2']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['NumLot']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DatePeremption'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['CoeffHydrometrique']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['Temperature']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($personne));
		$sheet->setCellValue('I'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($dateTERA)));
		$sheet->setCellValue('J'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($dateTERC)));
	
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Ingredients.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Ingredients.xlsx';
$writer->save($chemin);
readfile($chemin);
?>