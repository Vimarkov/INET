<?php
session_start();
require("../ConnexioniSansBody.php");
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
require("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;

$req2="SELECT DISTINCT trame_travaileffectue.Id_Tache, trame_tache.Libelle AS Tache, trame_tache.NiveauControle, 
		(SELECT Libelle FROM trame_checklist WHERE Id=Id_CL) AS CL
";
$req="FROM trame_travaileffectue 
	INNER JOIN trame_tache 
	ON trame_travaileffectue.Id_Tache=trame_tache.Id ";
$req.="WHERE trame_travaileffectue.Id_Preparateur>0 AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
if($_SESSION['TDB_Preparateur2']<>""){
	$tab = explode(";",$_SESSION['TDB_Preparateur2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
if($_SESSION['TDB_WP2']<>""){
	$tab = explode(";",$_SESSION['TDB_WP2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="trame_travaileffectue.Id_WP=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$req.="ORDER BY Tache ";
$result2=mysqli_query($bdd,$req2.$req);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
if($_SESSION['Langue']=="EN"){
	$sheet->setTitle("Control");
}
else{
	$sheet->setTitle("Controle");
}

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Manufacturing engineer"));
	$sheet->setCellValue('B1',utf8_encode("AUTO CONTROL"));
	$sheet->setCellValue('C1',utf8_encode("CONTROL"));
	$sheet->setCellValue('D1',utf8_encode("CONTROL AGAIN"));
	$sheet->setCellValue('E1',utf8_encode("RATE"));
	$sheet->setCellValue('F1',utf8_encode("LEVEL OF CONTROL"));
	$sheet->setCellValue('G1',utf8_encode("CHECK-LIST"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Préparateur"));
	$sheet->setCellValue('B1',utf8_encode("AUTO-CONTROLE"));
	$sheet->setCellValue('C1',utf8_encode("CONTROLE"));
	$sheet->setCellValue('D1',utf8_encode("RECONTROLE"));
	$sheet->setCellValue('E1',utf8_encode("RATIO"));
	$sheet->setCellValue('F1',utf8_encode("NIVEAU CONTROLE"));
	$sheet->setCellValue('G1',utf8_encode("CHECK-LIST"));
}

$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:G1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(10);
$sheet->getColumnDimension('F')->setWidth(10);
$sheet->getColumnDimension('G')->setWidth(30);



$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	$req2="SELECT Statut FROM trame_travaileffectue ";
	$req2.="WHERE trame_travaileffectue.Id_Tache=".$row2['Id_Tache']." AND trame_travaileffectue.Statut='AC' ";
	if($_SESSION['TDB_WP2']<>""){
		$tab = explode(";",$_SESSION['TDB_WP2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
		$req2.=" AND ( ";
		if($_SESSION['TDB_DateDebut2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
			$req2.=" AND ";
		}
		if($_SESSION['TDB_DateFin2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
			$req2.=" ";
		}
		if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
		$req2.=" ) ";
	}
	$result=mysqli_query($bdd,$req2);
	$nbResultaAC=mysqli_num_rows($result);
	$req2="SELECT Statut FROM trame_travaileffectue ";
	$req2.="WHERE trame_travaileffectue.Id_Tache=".$row2['Id_Tache']." AND trame_travaileffectue.Statut='CONTROLE' ";
	if($_SESSION['TDB_WP2']<>""){
		$tab = explode(";",$_SESSION['TDB_WP2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
		$req2.=" AND ( ";
		if($_SESSION['TDB_DateDebut2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
			$req2.=" AND ";
		}
		if($_SESSION['TDB_DateFin2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
			$req2.=" ";
		}
		if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
		$req2.=" ) ";
	}
	$result=mysqli_query($bdd,$req2);
	$nbResultaCONT=mysqli_num_rows($result);
	
	$req2="SELECT Statut FROM trame_travaileffectue ";
	$req2.="WHERE trame_travaileffectue.Id_Tache=".$row2['Id_Tache']." AND trame_travaileffectue.Statut='REC' ";
	if($_SESSION['TDB_WP2']<>""){
		$tab = explode(";",$_SESSION['TDB_WP2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
		$req2.=" AND ( ";
		if($_SESSION['TDB_DateDebut2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
			$req2.=" AND ";
		}
		if($_SESSION['TDB_DateFin2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
			$req2.=" ";
		}
		if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
		$req2.=" ) ";
	}
	$result=mysqli_query($bdd,$req2);
	$nbResultaREC=mysqli_num_rows($result);
	
	$req2="SELECT Statut FROM trame_travaileffectue ";
	$req2.="WHERE trame_travaileffectue.Id_Tache=".$row2['Id_Tache']." ";
	if($_SESSION['TDB_WP2']<>""){
		$tab = explode(";",$_SESSION['TDB_WP2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
		$req2.=" AND ( ";
		if($_SESSION['TDB_DateDebut2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
			$req2.=" AND ";
		}
		if($_SESSION['TDB_DateFin2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
			$req2.=" ";
		}
		if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
		$req2.=" ) ";
	}
	$result=mysqli_query($bdd,$req2);
	$nbResultaTOTAL=mysqli_num_rows($result);
	
	$ratio=0;
	if($nbResultaTOTAL>0){$ratio=round(($nbResultaAC+$nbResultaCONT+$nbResultaREC)/$nbResultaTOTAL,2);}
	$Niveau=$row2['NiveauControle'];
	if($Niveau==-1){$Niveau="M";}
	if($nbResultaAC>0 || $nbResultaCONT>0 || $nbResultaREC>0){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row2['Tache']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($nbResultaAC));
		$sheet->setCellValue('C'.$ligne,utf8_encode($nbResultaCONT));
		$sheet->setCellValue('D'.$ligne,utf8_encode($nbResultaREC));
		$sheet->setCellValue('E'.$ligne,utf8_encode($ratio));
		$sheet->setCellValue('F'.$ligne,utf8_encode($Niveau));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row2['CL']));
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_TDB.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_TDB.xlsx';
$writer->save($chemin);
readfile($chemin);
?>