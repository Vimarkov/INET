<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$req2="SELECT Id,MSN,OrdreMontage,Designation,Id_StatutPROD,Id_StatutQUALITE,";
$req2.="(SELECT sp_atrmoteur.PosteMontage FROM sp_atrmoteur WHERE sp_atrmoteur.MSN=sp_atrot.MSN LIMIT 1) AS PosteMontage, ";
$req2.="(SELECT sp_atrarticle.TypeMoteur FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS TypeMoteur, ";
$req2.="(SELECT sp_atrarticle.MoteurSharklet FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS MoteurSharklet, ";
$req2.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardPROD) AS CauseP, ";
$req2.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardQUALITE) AS CauseQ ";
$req="FROM sp_atrot ";
$req.="WHERE sp_atrot.Id_Prestation=463 AND ";
if($_SESSION['OTMSN2']<>""){
	$tab = explode(";",$_SESSION['OTMSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_atrot.MSN=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTOM2']<>""){
	$tab = explode(";",$_SESSION['OTOM2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_atrot.OrdreMontage='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTDesignation2']<>""){
	$tab = explode(";",$_SESSION['OTDesignation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_atrot.Designation LIKE '%".addslashes($valeur)."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTTypeMoteur2']<>""){
	$tab = explode(";",$_SESSION['OTTypeMoteur2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="?"){$req.="(SELECT sp_atrarticle.TypeMoteur FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) ='' OR (SELECT sp_atrarticle.TypeMoteur FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) IS NULL OR ";}
			else{$req.="(SELECT sp_atrarticle.TypeMoteur FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1)='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTMoteurSharklet2']<>""){
	$tab = explode(";",$_SESSION['OTMoteurSharklet2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="?"){$req.="(SELECT sp_atrarticle.MoteurSharklet FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) ='' OR (SELECT sp_atrarticle.MoteurSharklet FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) IS NULL OR ";}
			else{$req.="(SELECT sp_atrarticle.MoteurSharklet FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1)='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTPosteMontage2']<>""){
	$tab = explode(";",$_SESSION['OTPosteMontage2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="?"){$req.="(SELECT sp_atrmoteur.PosteMontage FROM sp_atrmoteur WHERE sp_atrmoteur.MSN=sp_atrot.MSN LIMIT 1) IS NULL OR ";}
			else{$req.="(SELECT sp_atrmoteur.PosteMontage FROM sp_atrmoteur WHERE sp_atrmoteur.MSN=sp_atrot.MSN LIMIT 1)='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTStatutP2']<>""){
	$tab = explode(";",$_SESSION['OTStatutP2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="(vide)"){$req.="sp_atrot.Id_StatutPROD='' OR ";}
			else{$req.="sp_atrot.Id_StatutPROD='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTRaisonP2']<>""){
	$tab = explode(";",$_SESSION['OTRaisonP2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_atrot.Id_CauseRetardPROD=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTStatutQ2']<>""){
	$tab = explode(";",$_SESSION['OTStatutQ2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="(vide)"){$req.="sp_atrot.Id_StatutQUALITE='' OR ";}
			else{$req.="sp_atrot.Id_StatutQUALITE='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTRaisonQ2']<>""){
	$tab = explode(";",$_SESSION['OTRaisonQ2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_atrot.Id_CauseRetardQUALITE=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

$reqFin="";
if($_SESSION['OTTriGeneral']<>""){
	$req.="ORDER BY ".substr($_SESSION['OTTriGeneral'],0,-1);
}
$result2=mysqli_query($bdd,$req2.$req.$reqFin);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° OF"));
$sheet->setCellValue('C1',utf8_encode("Désignation"));
$sheet->setCellValue('D1',utf8_encode("Type moteur"));
$sheet->setCellValue('E1',utf8_encode("Moteur/Sharklet"));
$sheet->setCellValue('F1',utf8_encode("Poste montage"));
$sheet->setCellValue('G1',utf8_encode("Statut PROD"));
$sheet->setCellValue('H1',utf8_encode("Cause retard PROD"));
$sheet->setCellValue('I1',utf8_encode("Statut QUALITE"));
$sheet->setCellValue('J1',utf8_encode("Cause retard QUALITE"));

$sheet->getStyle('A1:J1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:J1')->getFont()->setBold(true);
$sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(50);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(18);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(18);
$sheet->getColumnDimension('H')->setWidth(18);
$sheet->getColumnDimension('I')->setWidth(18);
$sheet->getColumnDimension('J')->setWidth(18);

$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	$TypeMoteur="?";
	if($row2['TypeMoteur']<>""){$TypeMoteur=$row2['TypeMoteur'];}
	
	$MoteurSharklet="?";
	if($row2['MoteurSharklet']<>""){$MoteurSharklet=$row2['MoteurSharklet'];}
	
	$PosteMontage="?";
	if($row2['PosteMontage']<>""){$PosteMontage=$row2['PosteMontage'];}
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['OrdreMontage']));
	$sheet->setCellValue('C'.$ligne,utf8_encode(addslashes($row2['Designation'])));
	$sheet->setCellValue('D'.$ligne,utf8_encode($TypeMoteur));
	$sheet->setCellValue('E'.$ligne,utf8_encode($MoteurSharklet));
	$sheet->setCellValue('F'.$ligne,utf8_encode($PosteMontage));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['Id_StatutPROD']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($row2['CauseP']));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Id_StatutQUALITE']));
	$sheet->setCellValue('J'.$ligne,utf8_encode($row2['CauseQ']));
	
	$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_OT.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_OT.xlsx';
$writer->save($chemin);
readfile($chemin);
?>