<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$req2="SELECT Id,MSN,NumOF,NumDERO,OrigineAM,Recurrence,NumAMNC,Id_Localisation,Id_TypeDefaut,Statut,DateCreation,Id_ProduitImpacte, ";
$req2.="(SELECT Libelle FROM sp_atrmomentdetection WHERE sp_atrmomentdetection.Id=sp_atram.Id_MomentDetection) AS Moment, ";
$req2.="(SELECT Libelle FROM sp_atrimputation WHERE sp_atrimputation.Id=sp_atram.Id_Imputation) AS ImputationAAA, ";
$req2.="(SELECT Libelle FROM sp_atrlocalisation WHERE sp_atrlocalisation.Id=sp_atram.Id_Localisation) AS Localisation, ";
$req2.="(SELECT Libelle FROM sp_atrproduitimpacte WHERE sp_atrproduitimpacte.Id=sp_atram.Id_ProduitImpacte) AS ProduitImpacte, ";
$req2.="(SELECT Libelle FROM sp_atrtypedefaut WHERE sp_atrtypedefaut.Id=sp_atram.Id_TypeDefaut) AS TypeDefaut, ";
$req2.="(SELECT Libelle FROM sp_atrcote WHERE sp_atrcote.Id=sp_atram.Id_Cote) AS Cote, ";
$req2.="(SELECT Libelle FROM sp_atractioncurative WHERE sp_atractioncurative.Id=sp_atram.Id_ActionCurative) AS ActionCurative, ";
$req2.="(SELECT Id FROM sp_atrot WHERE sp_atrot.OrdreMontage=sp_atram.NumOF AND sp_atrot.Supprime=0 LIMIT 1) AS Id_Dossier ";
$req="FROM sp_atram WHERE Id_Prestation=463 AND ";
if($_SESSION['AMMSN2']<>""){
	$tab = explode(";",$_SESSION['AMMSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="MSN=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMNumAMNC2']<>""){
	$tab = explode(";",$_SESSION['AMNumAMNC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="NumAMNC='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMNumOF2']<>""){
	$tab = explode(";",$_SESSION['AMNumOF2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="NumOF='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMOrigineAM2']<>""){
	$tab = explode(";",$_SESSION['AMOrigineAM2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="OrigineAM='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMImputation2']<>""){
	$tab = explode(";",$_SESSION['AMImputation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Imputation=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMNumDERO2']<>""){
	$tab = explode(";",$_SESSION['AMNumDERO2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="NumDERO='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMLocalisation2']<>""){
	$tab = explode(";",$_SESSION['AMLocalisation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Localisation=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMTypeDefaut2']<>""){
	$tab = explode(";",$_SESSION['AMTypeDefaut2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_TypeDefaut=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMProduitImpacte2']<>""){
	$tab = explode(";",$_SESSION['AMProduitImpacte2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_ProduitImpacte=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMMoment2']<>""){
	$tab = explode(";",$_SESSION['AMMoment2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_MomentDetection=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMRecurrence2']<>""){
	$tab = explode(";",$_SESSION['AMRecurrence2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Recurrence=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMStatut2']<>""){
	$tab = explode(";",$_SESSION['AMStatut2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Statut='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMCote2']<>""){
	$tab = explode(";",$_SESSION['AMCote2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Cote=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMActionCurative2']<>""){
	$tab = explode(";",$_SESSION['AMActionCurative2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_ActionCurative=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['AMDu2']<>"" || $_SESSION['AMAu2']<>""){
	$req.=" ( ";
	if($_SESSION['AMDu2']<>""){
		$req.="DateCreation >= '". TrsfDate_($_SESSION['AMDu2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['AMAu2']<>""){
		$req.="DateCreation <= '". TrsfDate_($_SESSION['AMAu2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

if($_SESSION['TriAMGeneral']<>""){
	$req.="ORDER BY ".substr($_SESSION['TriAMGeneral'],0,-1);
}
$result2=mysqli_query($bdd,$req2.$req);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° OF"));
$sheet->setCellValue('C1',utf8_encode("N° AM/NC"));
$sheet->setCellValue('D1',utf8_encode("Date de création"));
$sheet->setCellValue('E1',utf8_encode("Semaine"));
$sheet->setCellValue('F1',utf8_encode("Moment de détection"));
$sheet->setCellValue('G1',utf8_encode("Type de défaut"));
$sheet->setCellValue('H1',utf8_encode("Localisation"));
$sheet->setCellValue('I1',utf8_encode("Côté"));
$sheet->setCellValue('J1',utf8_encode("Produit impacté"));
$sheet->setCellValue('K1',utf8_encode("Imputation"));
$sheet->setCellValue('L1',utf8_encode("Action curative"));

$sheet->getStyle('A1:L1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:L1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:L1')->getFont()->setBold(true);
$sheet->getStyle('A1:L1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(20);
$sheet->getColumnDimension('L')->setWidth(20);

$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	if($row2['Recurrence']==0){$recurrence="Non";}
	else{$recurrence="Oui";}
	
	$dateCreation=explode('-',$row2['DateCreation']);
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['NumOF']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['NumAMNC']));
	$sheet->setCellValue('D'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateCreation'])));
	$sheet->setCellValue('E'.$ligne,utf8_encode("S".date('W',mktime(0,0,0,$dateCreation[1],$dateCreation[2],$dateCreation[0]))."_".date('Y',mktime(0,0,0,$dateCreation[1],$dateCreation[2],$dateCreation[0]))));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row2['Moment']));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['TypeDefaut']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($row2['Localisation']));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Cote']));
	$sheet->setCellValue('J'.$ligne,utf8_encode($row2['ProduitImpacte']));
	$sheet->setCellValue('K'.$ligne,utf8_encode($row2['ImputationAAA']));
	$sheet->setCellValue('L'.$ligne,utf8_encode($row2['ActionCurative']));

	$sheet->getStyle('A'.$ligne.':L'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_AM.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_AM.xlsx';
$writer->save($chemin);
readfile($chemin);
?>