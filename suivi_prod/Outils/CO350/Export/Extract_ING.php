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

$req="SELECT sp_olwfi_ingredient.NumLot,sp_olwfi_ingredient.DatePeremption,sp_olwfi_ingredient.CoeffHydrometrique,
	sp_olwfi_ingredient.Temperature,
	IF(sp_olwfi_ingredient.Id_Ingredient>0,(SELECT Libelle FROM sp_olwingredient WHERE sp_olwingredient.Id=sp_olwfi_ingredient.Id_Ingredient),sp_olwfi_ingredient.Ingredient) AS Ingredient2,
	(SELECT sp_olwdossier.MSN FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier) AS MSN,
	(SELECT sp_olwdossier.Reference FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier) AS Reference,
	sp_olwficheintervention.DateTERA,sp_olwficheintervention.DateTERC,sp_olwfi_ingredient.Id_FI
	FROM sp_olwfi_ingredient
	LEFT JOIN sp_olwficheintervention 
	ON sp_olwfi_ingredient.Id_FI=sp_olwficheintervention.Id 
	WHERE (SELECT Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=1792 AND ";
	if($_SESSION['EXTRACT_INGMSN2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_INGMSN2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="(SELECT sp_olwdossier.MSN FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=".$valeur." OR ";
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
				$req.="IF(sp_olwfi_ingredient.Id_Ingredient>0,(SELECT Libelle FROM sp_olwingredient WHERE sp_olwingredient.Id=sp_olwfi_ingredient.Id_Ingredient),sp_olwfi_ingredient.Ingredient) LIKE '%".$valeur."%' OR ";
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
				$req.="(SELECT sp_olwdossier.Reference FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)='".$valeur."' OR ";
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
			$req.="DateTERA >= '". TrsfDate_($_SESSION['EXTRACT_INGDu2'])."' OR ";
			$req.="DateTERC >= '". TrsfDate_($_SESSION['EXTRACT_INGDu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_INGAu2']<>""){
		$req.=" ( ";
			$req.="DateTERA <= '". TrsfDate_($_SESSION['EXTRACT_INGAu2'])."' OR ";
			$req.="DateTERC <= '". TrsfDate_($_SESSION['EXTRACT_INGAu2'])."' ";
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_INGDateTERA2']<>""){
		$req.=" ( ";
			$req.="DateTERA = '". TrsfDate_($_SESSION['EXTRACT_INGDateTERA2'])."'" ;
		$req.=" ) ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_INGDateTERC2']<>""){
		$req.=" ( ";
			$req.="DateTERC = '". TrsfDate_($_SESSION['EXTRACT_INGDateTERC2'])."'" ;
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

		$req="SELECT 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS Compagnon ";
		$req.="FROM sp_olwfi_travaileffectue LEFT JOIN sp_olwficheintervention ON sp_olwficheintervention.Id=sp_olwfi_travaileffectue.Id_FI ";
		$req.="WHERE sp_olwficheintervention.Id=".$row['Id_FI'];
		$resultCompagnon=mysqli_query($bdd,$req);
		$nbCompagnon=mysqli_num_rows($resultCompagnon);
		if ($nbCompagnon>0){	
			while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
				$personne.=$rowCompagnon['Compagnon']."  ";
			}
		}
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Ingredient2']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['NumLot']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DatePeremption'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['CoeffHydrometrique']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['Temperature']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($personne));
		$sheet->setCellValue('I'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERA'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERC'])));
	
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