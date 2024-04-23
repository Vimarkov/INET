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
$sheet2 = $workbook->createSheet();
if($_SESSION['Langue']=="EN"){
	$sheet->setTitle("WU");
	$sheet2->setTitle("WU format 2");
}
else{
	$sheet->setTitle("UO");
	$sheet2->setTitle("UO format 2");
}

$req="SELECT Id,Libelle,Description, (SELECT Libelle FROM trame_categorie WHERE trame_categorie.Id=trame_uo.Id_Categorie) AS Categorie FROM trame_uo WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false ORDER BY Libelle;";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$req="SELECT Id,Libelle FROM trame_domainetechnique WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false ORDER BY Libelle ";
$resultDT=mysqli_query($bdd,$req);
$nbResultaDT=mysqli_num_rows($resultDT);

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Work unit"));
	$sheet->setCellValue('B1',utf8_encode("Description"));
	$sheet->setCellValue('C1',utf8_encode("Category"));
	$sheet->setCellValue('D1',utf8_encode("Technical domain"));
	$sheet->setCellValue('E1',utf8_encode("CREATION"));
	$sheet->setCellValue('H1',utf8_encode("UPDATE"));
	
	$sheet2->setCellValue('A1',utf8_encode("Work unit"));
	$sheet2->setCellValue('B1',utf8_encode("Description"));
	$sheet2->setCellValue('C1',utf8_encode("Technical domain"));
	$sheet2->setCellValue('D1',utf8_encode("Complexity"));
	$sheet2->setCellValue('E1',utf8_encode("Type of work"));
	$sheet2->setCellValue('F1',utf8_encode("Time"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("UO"));
	$sheet->setCellValue('B1',utf8_encode("Description"));
	$sheet->setCellValue('C1',utf8_encode("Catégorie"));
	$sheet->setCellValue('D1',utf8_encode("Domaine technique"));
	$sheet->setCellValue('E1',utf8_encode("CREATION"));
	$sheet->setCellValue('H1',utf8_encode("UPDATE"));
	
	
	$sheet2->setCellValue('A1',utf8_encode("UO"));
	$sheet2->setCellValue('B1',utf8_encode("Description"));
	$sheet2->setCellValue('C1',utf8_encode("Domaine technique"));
	$sheet2->setCellValue('D1',utf8_encode("Complexité"));
	$sheet2->setCellValue('E1',utf8_encode("Type de travail"));
	$sheet2->setCellValue('F1',utf8_encode("Temps"));
}
$sheet->setCellValue('E2',utf8_encode("LOW"));
$sheet->setCellValue('F2',utf8_encode("MEDIUM"));
$sheet->setCellValue('G2',utf8_encode("HIGH"));
$sheet->setCellValue('H2',utf8_encode("VERY HIGH"));
$sheet->setCellValue('I2',utf8_encode("OTHER"));

$sheet->setCellValue('J2',utf8_encode("LOW"));
$sheet->setCellValue('K2',utf8_encode("MEDIUM"));
$sheet->setCellValue('L2',utf8_encode("HIGH"));
$sheet->setCellValue('M2',utf8_encode("VERY HIGH"));
$sheet->setCellValue('N2',utf8_encode("OTHER"));

$sheet->mergeCells('A1:A2');
$sheet->mergeCells('B1:B2');
$sheet->mergeCells('C1:C2');
$sheet->mergeCells('D1:D2');
$sheet->mergeCells('E1:I1');
$sheet->mergeCells('J1:N1');
$sheet->getStyle('D1')->getAlignment()->setWrapText(true);

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(70);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(12);
$sheet->getColumnDimension('F')->setWidth(12);
$sheet->getColumnDimension('G')->setWidth(12);
$sheet->getColumnDimension('H')->setWidth(12);
$sheet->getColumnDimension('I')->setWidth(12);
$sheet->getColumnDimension('J')->setWidth(12);
$sheet->getColumnDimension('K')->setWidth(12);
$sheet->getColumnDimension('L')->setWidth(12);
$sheet->getColumnDimension('M')->setWidth(12);
$sheet->getColumnDimension('N')->setWidth(12);

$sheet2->getColumnDimension('A')->setWidth(15);
$sheet2->getColumnDimension('B')->setWidth(70);
$sheet2->getColumnDimension('C')->setWidth(20);
$sheet2->getColumnDimension('D')->setWidth(15);
$sheet2->getColumnDimension('E')->setWidth(12);
$sheet2->getColumnDimension('F')->setWidth(12);

$sheet->getStyle('A1:N2')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:N2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:N2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:N2')->getFont()->setBold(true);
$sheet->getStyle('A1:N2')->getFont()->getColor()->setRGB('1f49a6');

$sheet2->getStyle('A1:F1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet2->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet2->getStyle('A1:F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet2->getStyle('A1:F1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet2->getStyle('A1:F1')->getFont()->setBold(true);
$sheet2->getStyle('A1:F1')->getFont()->getColor()->setRGB('1f49a6');

if ($nbResulta>0){
	$k=1;
	$ligne=3;
	$ligne2=2;
	$LaLigne=3;
	$laLigneOnglet2=2;
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Libelle']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Description']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Categorie']));
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$LaLigne=$ligne;
		if($nbResultaDT>0){mysqli_data_seek($resultDT,0);}
		$i=1;
		while($rowDT=mysqli_fetch_array($resultDT)){
			$sheet->setCellValue('D'.$LaLigne,utf8_encode($rowDT['Libelle']));
			
			$CL="";
			$CM="";
			$CH="";
			$CVH="";
			$COt="";
			$UL="";
			$UM="";
			$UH="";
			$UVH="";
			$UOt="";
			$req="SELECT Temps,Complexite,TypeTravail FROM trame_tempsalloue WHERE Id_UO='".$row['Id']."' AND Id_DomaineTechnique=".$rowDT['Id']." ";
			$resultTA=mysqli_query($bdd,$req);
			$nbResultaTA=mysqli_num_rows($resultTA);
			if($nbResultaTA){
				while($rowTA=mysqli_fetch_array($resultTA)){
					if($rowTA['TypeTravail']=="Creation"){
						if($rowTA['Complexite']=="Low"){$CL=$rowTA['Temps'];}
						elseif($rowTA['Complexite']=="Medium"){$CM=$rowTA['Temps'];}
						elseif($rowTA['Complexite']=="High"){$CH=$rowTA['Temps'];}
						elseif($rowTA['Complexite']=="Very High"){$CVH=$rowTA['Temps'];}
						elseif($rowTA['Complexite']=="Other"){$COt=$rowTA['Temps'];}
					}
					elseif($rowTA['TypeTravail']=="Update"){
						if($rowTA['Complexite']=="Low"){$UL=$rowTA['Temps'];}
						elseif($rowTA['Complexite']=="Medium"){$UM=$rowTA['Temps'];}
						elseif($rowTA['Complexite']=="High"){$UH=$rowTA['Temps'];}
						elseif($rowTA['Complexite']=="Very High"){$UVH=$rowTA['Temps'];}
						elseif($rowTA['Complexite']=="Other"){$UOt=$rowTA['Temps'];}
					}
				}
			}
			$sheet->setCellValue('E'.$LaLigne,utf8_encode($CL));
			$sheet->setCellValue('F'.$LaLigne,utf8_encode($CM));
			$sheet->setCellValue('G'.$LaLigne,utf8_encode($CH));
			$sheet->setCellValue('H'.$LaLigne,utf8_encode($CVH));
			$sheet->setCellValue('I'.$LaLigne,utf8_encode($COt));
			
			$sheet->setCellValue('J'.$LaLigne,utf8_encode($UL));
			$sheet->setCellValue('K'.$LaLigne,utf8_encode($UM));
			$sheet->setCellValue('L'.$LaLigne,utf8_encode($UH));
			$sheet->setCellValue('M'.$LaLigne,utf8_encode($UVH));
			$sheet->setCellValue('N'.$LaLigne,utf8_encode($UOt));
			$LaLigne++;

			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("LOW"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("CREATION"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($CL));
			$laLigneOnglet2++;
			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("MEDIUM"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("CREATION"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($CM));
			$laLigneOnglet2++;
			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("HIGH"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("CREATION"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($CH));
			$laLigneOnglet2++;
			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("VERY HIGH"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("CREATION"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($CVH));
			$laLigneOnglet2++;
			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("OTHER"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("CREATION"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($COt));
			$laLigneOnglet2++;
			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("LOW"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("UPDATE"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($UL));
			$laLigneOnglet2++;
			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("MEDIUM"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("UPDATE"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($UM));
			$laLigneOnglet2++;
			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("HIGH"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("UPDATE"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($UH));
			$laLigneOnglet2++;
			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("VERY HIGH"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("UPDATE"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($UVH));
			$laLigneOnglet2++;
			$sheet2->setCellValue('A'.$laLigneOnglet2,utf8_encode($row['Libelle']));
			$sheet2->setCellValue('B'.$laLigneOnglet2,utf8_encode($row['Description']));
			$sheet2->setCellValue('C'.$laLigneOnglet2,utf8_encode($rowDT['Libelle']));
			$sheet2->setCellValue('D'.$laLigneOnglet2,utf8_encode("OTHER"));
			$sheet2->setCellValue('E'.$laLigneOnglet2,utf8_encode("UPDATE"));
			$sheet2->setCellValue('F'.$laLigneOnglet2,utf8_encode($UOt));
			$laLigneOnglet2++;
			
		}
		if($ligne<$LaLigne){
			$sheet->mergeCells('A'.$ligne.':A'.($LaLigne-1));
			$sheet->mergeCells('B'.$ligne.':B'.($LaLigne-1));
			$sheet->mergeCells('C'.$ligne.':C'.($LaLigne-1));
		}
		$ligne=$LaLigne;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($_SESSION['Langue']=="EN"){
	header('Content-Disposition: attachment;filename="Extract_WU.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="Extract_UO.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

if($_SESSION['Langue']=="EN"){
	$chemin = '../../tmp/Extract_WU.xlsx';
}
else{
	$chemin = '../../tmp/Extract_UO.xlsx';
}
$writer->save($chemin);
readfile($chemin);

?>