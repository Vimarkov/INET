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
$req2.="(SELECT sp_atrarticle.Ligne FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article) AS Ligne, ";
$req2.="(SELECT sp_atrarticle.Poste45 FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article) AS Poste45, ";
$req2.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardPROD) AS CauseP, ";
$req2.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardQUALITE) AS CauseQ ";
$req="FROM sp_atrot ";
$req.="WHERE sp_atrot.Id_Prestation=262 AND ";
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
if($_SESSION['OTLigne2']<>""){
	$tab = explode(";",$_SESSION['OTLigne2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="?"){$req.="(SELECT sp_atrarticle.Ligne FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article) IS NULL OR ";}
			else{$req.="(SELECT sp_atrarticle.Ligne FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article)=".$valeur." OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['OTPoste452']<>""){
	$tab = explode(";",$_SESSION['OTPoste452']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="(SELECT sp_atrarticle.Poste45 FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article)=".$valeur." OR ";
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
$sheet->setCellValue('B1',utf8_encode("Ordre de montage"));
$sheet->setCellValue('C1',utf8_encode("Désignation"));
$sheet->setCellValue('D1',utf8_encode("Ligne"));
$sheet->setCellValue('E1',utf8_encode("Poste 45"));
$sheet->setCellValue('F1',utf8_encode("Statut PROD"));
$sheet->setCellValue('G1',utf8_encode("Information statut production"));
$sheet->setCellValue('H1',utf8_encode("Statut QUALITE"));
$sheet->setCellValue('I1',utf8_encode("Information statut qualité"));

$sheet->getStyle('A1:I1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:I1')->getFont()->setBold(true);
$sheet->getStyle('A1:I1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(50);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(18);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(18);
$sheet->getColumnDimension('H')->setWidth(18);
$sheet->getColumnDimension('I')->setWidth(18);

$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	$Ligne="?";
	$Poste45="";
	$couleurLigne="color:#e31b1b;";
	if($row2['Ligne']<>""){$Ligne=$row2['Ligne'];$couleurLigne="";}
	
	if($row2['Poste45']==1){$Poste45="Oui";}
	elseif($row2['Poste45']==null){$Poste45="?";}
	elseif($row2['Poste45']==0){$Poste45="Non";}
	
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['OrdreMontage']));
	$sheet->setCellValue('C'.$ligne,utf8_encode(addslashes($row2['Designation'])));
	$sheet->setCellValue('D'.$ligne,utf8_encode($Ligne));
	$sheet->setCellValue('E'.$ligne,utf8_encode($Poste45));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row2['Id_StatutPROD']));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['CauseP']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($row2['Id_StatutQUALITE']));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['CauseQ']));
	
	$sheet->getStyle('A'.$ligne.':I'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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