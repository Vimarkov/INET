<?php
session_start();
require("../ConnexioniSansBody.php");
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$sheet = $workbook->getActiveSheet();
if($_SESSION['Langue']=="EN"){
	$sheet->setTitle("Allocation Task WP");
}
else{
	$sheet->setTitle("Allocation Tache WP");
}

$req="SELECT Id, Libelle ";
$req.="FROM trame_wp ";
$req.="WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false ORDER BY Libelle ";
$resultWP=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($resultWP);

$req2="SELECT Id, Libelle ";
$req2.="FROM trame_tache ";
$req2.="WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false ORDER BY Libelle";
$resultTache=mysqli_query($bdd,$req2);
$nbResulta2=mysqli_num_rows($resultTache);

$req3="SELECT Id_Tache,Id_WP ";
$req3.="FROM trame_tache_wp ";
$req3.="WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ";

$resultTacheWP=mysqli_query($bdd,$req3);
$nbResulta3=mysqli_num_rows($resultTacheWP);

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Task"));
	
}
else{
	$sheet->setCellValue('A1',utf8_encode("Tâche"));
}

$lettre="A";
while($row=mysqli_fetch_array($resultWP)){
	$lettre++;
	$sheet->setCellValue($lettre.'1',utf8_encode($row['Libelle']));
}
$sheet->getColumnDimension('A')->setWidth(90);

$sheet->getStyle('A1:'.$lettre.'1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:'.$lettre.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:'.$lettre.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:'.$lettre.'1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:'.$lettre.'1')->getFont()->setBold(true);
$sheet->getStyle('A1:'.$lettre.'1')->getFont()->getColor()->setRGB('1f49a6');

$ligne=2;

while($rowTache=mysqli_fetch_array($resultTache)){
	
	$sheet->setCellValue('A'.$ligne,utf8_encode($rowTache['Libelle']));
	$lettre2="A";
	mysqli_data_seek($resultWP,0);
	while($rowWP=mysqli_fetch_array($resultWP)){
		$lettre2++;
		$existe="";
		mysqli_data_seek($resultTacheWP,0);
		while($rowTacheWP=mysqli_fetch_array($resultTacheWP)){
			if($rowTacheWP['Id_Tache']==$rowTache['Id'] && $rowTacheWP['Id_WP']==$rowWP['Id']){
				$existe="X";
			}
		}
		
		$sheet->setCellValue($lettre2.$ligne,utf8_encode($existe));
		if($existe<>""){
			$sheet->getStyle($lettre2.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			$sheet->getStyle($lettre2.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
		}
	}
	
	$sheet->getStyle('A'.$ligne.':'.$lettre.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
	
}

//ONGLET 2
$sheet = $workbook->createSheet();
if($_SESSION['Langue']=="EN"){
	$sheet->setTitle("Setting task");
}
else{
	$sheet->setTitle(utf8_encode("Paramétrage tâche"));
}

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Task"));
	$sheet->setCellValue('B1',utf8_encode("Work unit code"));
	$sheet->setCellValue('C1',utf8_encode("Technical domain"));
	$sheet->setCellValue('D1',utf8_encode("CREATION"));
	$sheet->setCellValue('G1',utf8_encode("UPDATE"));
	$sheet->setCellValue('J1',utf8_encode("Work unit designation"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Tâche"));
	$sheet->setCellValue('B1',utf8_encode("Code UO"));
	$sheet->setCellValue('C1',utf8_encode("Domaine technique"));
	$sheet->setCellValue('D1',utf8_encode("Creation"));
	$sheet->setCellValue('G1',utf8_encode("UPDATE"));
	$sheet->setCellValue('J1',utf8_encode("Désignation UO"));
}
$sheet->setCellValue('D2',utf8_encode("LOW"));
$sheet->setCellValue('E2',utf8_encode("MEDIUM"));
$sheet->setCellValue('F2',utf8_encode("HIGH"));
$sheet->setCellValue('G2',utf8_encode("VERY HIGH"));
$sheet->setCellValue('H2',utf8_encode("OTHER"));

$sheet->setCellValue('I2',utf8_encode("LOW"));
$sheet->setCellValue('J2',utf8_encode("MEDIUM"));
$sheet->setCellValue('K2',utf8_encode("HIGH"));
$sheet->setCellValue('L2',utf8_encode("VERY HIGH"));
$sheet->setCellValue('M2',utf8_encode("OTHER"));

$sheet->mergeCells('A1:A2');
$sheet->mergeCells('B1:B2');
$sheet->mergeCells('C1:C2');
$sheet->mergeCells('D1:H1');
$sheet->mergeCells('I1:M1');
$sheet->mergeCells('N1:N2');
$sheet->getStyle('C1')->getAlignment()->setWrapText(true);

$sheet->getColumnDimension('A')->setWidth(90);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(12);
$sheet->getColumnDimension('E')->setWidth(12);
$sheet->getColumnDimension('F')->setWidth(12);
$sheet->getColumnDimension('G')->setWidth(12);
$sheet->getColumnDimension('H')->setWidth(12);
$sheet->getColumnDimension('I')->setWidth(12);
$sheet->getColumnDimension('K')->setWidth(12);
$sheet->getColumnDimension('L')->setWidth(12);
$sheet->getColumnDimension('M')->setWidth(12);
$sheet->getColumnDimension('N')->setWidth(60);

$sheet->getStyle('A1:N2')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:N2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:N2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:N2')->getFont()->setBold(true);
$sheet->getStyle('A1:N2')->getFont()->getColor()->setRGB('1f49a6');

$req="SELECT trame_uo.Libelle AS UO,trame_uo.Description,trame_tache.Id AS Id_Tache,trame_tache.Libelle AS Tache,trame_tache_uo.Complexite,trame_tache_uo.Relation,trame_tache_uo.TypeTravail, ";
$req.="(SELECT Libelle FROM trame_domainetechnique WHERE trame_domainetechnique.Id=trame_tache_uo.Id_DT) AS DT ";
$req.="FROM trame_tache_uo LEFT JOIN trame_tache ON trame_tache_uo.Id_Tache=trame_tache.Id ";
$req.="LEFT JOIN trame_uo ON trame_tache_uo.Id_UO=trame_uo.Id ";
$req.="WHERE trame_tache_uo.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_tache.Supprime=false AND trame_uo.Supprime=false ";
$req.="ORDER BY Tache,UO,DT ";
$resultTacheUO=mysqli_query($bdd,$req);
$nbResulta2=mysqli_num_rows($resultTacheUO);

$ligne=3;
$ligne2=2;
mysqli_data_seek($resultTache,0);
$IdTache=0;
$LaLigne=3;
while($rowTache=mysqli_fetch_array($resultTacheUO)){
	if($rowTache['Id_Tache']<>$IdTache){
		$sheet->setCellValue('A'.$ligne,utf8_encode($rowTache['Tache']));
		$sheet->getStyle('A'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if($LaLigne<$ligne2){
			$sheet->mergeCells('A'.$LaLigne.':A'.$ligne2);
		}
		$IdTache=$rowTache['Id_Tache'];
		$LaLigne=$ligne;
	}
	$sheet->setCellValue('B'.$ligne,utf8_encode($rowTache['UO']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($rowTache['DT']));
	if($rowTache['TypeTravail']=="Creation"){
		if($rowTache['Complexite']=="Low"){
			$sheet->setCellValue('D'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('D'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('D'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('D'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
		elseif($rowTache['Complexite']=="Medium"){
			$sheet->setCellValue('E'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('E'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('E'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('E'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
		elseif($rowTache['Complexite']=="High"){
			$sheet->setCellValue('F'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('F'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
		elseif($rowTache['Complexite']=="Very High"){
			$sheet->setCellValue('G'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('G'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('G'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('G'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
		elseif($rowTache['Complexite']=="Other"){
			$sheet->setCellValue('H'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('H'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('H'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('H'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
	}
	else{
		if($rowTache['Complexite']=="Low"){
			$sheet->setCellValue('I'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('I'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
		elseif($rowTache['Complexite']=="Medium"){
			$sheet->setCellValue('J'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('J'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('J'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('J'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
		elseif($rowTache['Complexite']=="High"){
			$sheet->setCellValue('k'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('k'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('k'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('k'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
		elseif($rowTache['Complexite']=="Very High"){
			$sheet->setCellValue('L'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('L'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
		elseif($rowTache['Complexite']=="Other"){
			$sheet->setCellValue('M'.$ligne,utf8_encode(substr($rowTache['Relation'],0,1)));
			$sheet->getStyle('M'.$ligne)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)));
			if($rowTache['Relation']=="Optional"){
				$sheet->getStyle('M'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
			}
			else{
				$sheet->getStyle('M'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffa500'))));
			}
		}
	}
	$sheet->setCellValue('N'.$ligne,utf8_encode($rowTache['Description']));
	$sheet->getStyle('A'.$ligne.':N'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
	$ligne2++;
}
if($LaLigne<$ligne2){
	$sheet->mergeCells('A'.$LaLigne.':A'.$ligne2);
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($_SESSION['Langue']=="EN"){
	header('Content-Disposition: attachment;filename="Extract_Setting.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="Extract_Parametrage.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

if($_SESSION['Langue']=="EN"){
	$chemin = '../../tmp/Extract_Setting.xlsx';
}
else{
	$chemin = '../../tmp/Extract_Parametrage.xlsx';
}
$writer->save($chemin);
readfile($chemin);

?>