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

$tabWP = explode(";",$_GET['WP']);	
$tabWPTache = explode(";",$_GET['Tache']);	

$req="SELECT DISTINCT trame_travaileffectue.Id ";
$req.="FROM trame_travaileffectue_uo LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
$req.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
$req.="AND trame_travaileffectue.Statut IN ('AC','CONTROLE','REC') AND trame_travaileffectue.DatePreparateur>='".TrsfDate_($_GET['DateDebut'])."' ";
$req.="AND trame_travaileffectue.DatePreparateur<='".TrsfDate_($_GET['DateFin'])."' AND (";
foreach($tabWP as $wp){
	if($wp<>""){
		$trouve=false;
		if($_GET['Tache']<>""){
			foreach($tabWPTache as $tache){
				if($tache<>""){
					$tab = explode("_",$tache);
					if($tab[0]==$wp){
						$trouve=true;
						$req.="(trame_travaileffectue.Id_WP=".$tab[0]." AND trame_travaileffectue.Id_Tache<>".$tab[1].") OR ";
					}
				}
			}
		}
		if($trouve==false){
			$req.="trame_travaileffectue.Id_WP=".$wp." OR ";
		}
	}
}

$req=substr($req,0,-3);
$req.=") ";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

if($nbResulta>0){
	if($_SESSION['Langue']=="FR"){
		echo "Impossible, des livrables sur cette période n'ont pas encore été contrôlés. ";
	}
	else{
		echo "Impossible, deliverables over this period have not yet been checked. ";
	}
}
else{

	//ONGLET 1
	$sheetRecp = $workbook->getActiveSheet();
	$sheetRecp->setTitle("Recap");

	$sheetRecp->setCellValue('A1',utf8_encode("FROM"));
	$sheetRecp->setCellValue('C1',utf8_encode("TO"));
	$sheetRecp->setCellValue('B1',utf8_encode($_GET['DateDebut']));
	$sheetRecp->setCellValue('D1',utf8_encode($_GET['DateFin']));

	$sheetRecp->getStyle('A1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
	$sheetRecp->getStyle('C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

	$sheetRecp->getColumnDimension('A')->setWidth(15);
	$sheetRecp->getColumnDimension('B')->setWidth(15);
	$sheetRecp->getColumnDimension('C')->setWidth(15);
	$sheetRecp->getColumnDimension('D')->setWidth(15);

	$sheetRecp->setCellValue('A3',utf8_encode("Work unit price"));
	$sheetRecp->setCellValue('A4',utf8_encode("Additional cost (if applicable)"));
	$sheetRecp->setCellValue('A5',utf8_encode("Total price"));
	$sheetRecp->getStyle('A3:A5')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

	$sheetRecp->setCellValue('C4',utf8_encode("/"));
	

	$sheetRecp->mergeCells('A3:B3');
	$sheetRecp->mergeCells('A4:B4');
	$sheetRecp->mergeCells('A5:B5');
	$sheetRecp->mergeCells('C3:D3');
	$sheetRecp->mergeCells('C4:D4');
	$sheetRecp->mergeCells('C5:D5');


	//ONGLET 2
	$sheet = $workbook->createSheet();
	$sheet->setTitle("Work unit");

	$req="SELECT trame_travaileffectue_uo.TypeTravail,trame_travaileffectue_uo.Complexite, ";
	$req.="(SELECT Libelle FROM trame_uo WHERE trame_uo.Id=trame_travaileffectue_uo.Id_UO) AS UO, ";
	$req.="(SELECT Libelle FROM trame_domainetechnique WHERE trame_domainetechnique.Id=trame_travaileffectue_uo.Id_DomaineTechnique) AS DT, ";
	$req.="COUNT(trame_travaileffectue_uo.TempsAlloue) AS NbLigne, ";
	$req.="SUM(trame_travaileffectue_uo.TempsAlloue) AS SommeTempsAlloue ";
	$req.="FROM trame_travaileffectue_uo LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
	$req.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
	$req.="AND trame_travaileffectue.Statut='VALIDE' AND trame_travaileffectue.DatePreparateur>='".TrsfDate_($_GET['DateDebut'])."' ";
	$req.="AND trame_travaileffectue.DatePreparateur<='".TrsfDate_($_GET['DateFin'])."' AND (";
	$reqWP="SELECT Libelle FROM trame_wp WHERE ";
	foreach($tabWP as $wp){
		if($wp<>""){
			$trouve=false;
			$reqWP.="Id=".$wp." OR ";
			if($_GET['Tache']<>""){
				foreach($tabWPTache as $tache){
					if($tache<>""){
						$tab = explode("_",$tache);
						if($tab[0]==$wp){
							$trouve=true;
							$req.="(trame_travaileffectue.Id_WP=".$tab[0]." AND trame_travaileffectue.Id_Tache<>".$tab[1].") OR ";
						}
					}
				}
			}
			if($trouve==false){
				$req.="trame_travaileffectue.Id_WP=".$wp." OR ";
			}
		}
	}
	$req=substr($req,0,-3);
	$reqWP=substr($reqWP,0,-3);
	$reqWP.=" ORDER BY Libelle ";
	$req.=") ";
	$req.="GROUP BY UO, DT, TypeTravail, Complexite ";
	$req.="ORDER BY UO, DT, TypeTravail, Complexite ";

	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);

	$resultWP=mysqli_query($bdd,$reqWP);
	$nbResultaWP=mysqli_num_rows($resultWP);
	$lesWP="";
	if($nbResultaWP>0){
		while($rowWP=mysqli_fetch_array($resultWP)){
			$lesWP.=$rowWP['Libelle'].", ";
		}
	}
	if($lesWP<>""){$lesWP=substr($lesWP,0,-2);}
	$ligne2=4;
	$ligne=5;
	$UO="";
	$DT="";
	$LaLigne=5;
	$colonneNb=2;
	$colonneTime=12;
	$colonneTotal=22;
	$total=0;
	
	if($nbResulta>0){
		while($row=mysqli_fetch_array($result)){
			if($row['UO']<>$UO){
				$ligne++;
				$ligne2++;
				$sheet->setCellValue('A'.$ligne,utf8_encode($row['UO']));
				$sheet->getStyle('A'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				if($LaLigne<$ligne2){
					$sheet->mergeCells('A'.$LaLigne.':A'.$ligne2);
				}
				$UO=$row['UO'];
				$DT=$row['DT'];
				$LaLigne=$ligne;
			}
			elseif($row['DT']<>$DT){
				$DT=$row['DT'];
				$ligne++;
				$ligne2++;
			}
			$sheet->setCellValue('B'.$ligne,utf8_encode($row['DT']));
			$nbTT=0;
			$nbComplexite=0;
			if($row['TypeTravail']=="Update"){$nbTT=3;}
			if($row['Complexite']=="Medium"){$nbComplexite=1;}
			elseif($row['Complexite']=="High"){$nbComplexite=2;}
			elseif($row['Complexite']=="Very High"){$nbComplexite=3;}
			elseif($row['Complexite']=="Other"){$nbComplexite=4;}
			$sheet->setCellValueByColumnAndRow($colonneNb+$nbTT+$nbComplexite,$ligne,utf8_encode($row['NbLigne']));
			if($row['NbLigne']!="0"){
			$sheet->setCellValueByColumnAndRow($colonneTime+$nbTT+$nbComplexite,$ligne,utf8_encode($row['SommeTempsAlloue']/$row['NbLigne']));
			}
			else{
				$sheet->setCellValueByColumnAndRow($colonneTime+$nbTT+$nbComplexite,$ligne,0);
			}
			$sheet->setCellValueByColumnAndRow($colonneTotal+$nbTT+$nbComplexite,$ligne,utf8_encode($row['SommeTempsAlloue']));
			$total+=$row['SommeTempsAlloue'];
			$sheet->getStyle('A'.$ligne.':AF'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		}
	}
	
	$ligne=$ligne+2;
	$sheet->setCellValue('P'.$ligne,utf8_encode("Total work unit time"));
	$sheet->mergeCells('P'.$ligne.':Q'.$ligne);
	$sheet->setCellValue('R'.$ligne,utf8_encode($total));
	$sheet->mergeCells('R'.$ligne.':S'.$ligne);
	$sheet->getStyle('P'.$ligne.':S'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne=$ligne+2;
	$sheet->getStyle('A'.$ligne.':AF'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'b5b6bd'))));
	$ligne=$ligne+2;

	$sheetRecp->setCellValue('C3',utf8_encode($total));
	$sheetRecp->setCellValue('C5',"=SUM(C3:C4)", PHPExcel_Cell_DataType::TYPE_FORMULA);
	
	$sheet->mergeCells('A1:B1');
	$sheet->setCellValue('A2',utf8_encode("WORKPACKAGE"));
	$sheet->mergeCells('A2:B2');
	if($_SESSION['Langue']=="EN"){
		$sheet->setCellValue('A3',utf8_encode("FROM"));
		$sheet->setCellValue('A4',utf8_encode("TO"));
	}
	else{
		$sheet->setCellValue('A3',utf8_encode("DU"));
		$sheet->setCellValue('A4',utf8_encode("AU"));
	}
	$sheet->setCellValue('B3',utf8_encode($_GET['DateDebut']));
	$sheet->setCellValue('B4',utf8_encode($_GET['DateFin']));

	$sheet->setCellValue('A5',utf8_encode("WORK UNIT"));
	$sheet->setCellValue('B5',utf8_encode("TECH. DOMAIN"));

	$sheet->setCellValue('C1',utf8_encode("NUMBER OF WU PRODUCED"));
	$sheet->mergeCells('C1:L1');
	$sheet->setCellValue('C2',utf8_encode($lesWP));
	$sheet->mergeCells('C2:L2');
	$sheet->setCellValue('C3',utf8_encode("CREATION"));
	$sheet->mergeCells('C3:G3');
	$sheet->setCellValue('H3',utf8_encode("UPDATE"));
	$sheet->mergeCells('H3:L3');
	$sheet->setCellValue('C4',utf8_encode("COMPLEXITY LEVEL"));
	$sheet->mergeCells('C4:L4');
	
	$sheet->setCellValue('C5',utf8_encode("LOW"));
	$sheet->setCellValue('D5',utf8_encode("MEDIUM"));
	$sheet->setCellValue('E5',utf8_encode("HIGH"));
	$sheet->setCellValue('F5',utf8_encode("VERY HIGH"));
	$sheet->setCellValue('G5',utf8_encode("OTHER"));
	$sheet->setCellValue('H5',utf8_encode("LOW"));
	$sheet->setCellValue('I5',utf8_encode("MEDIUM"));
	$sheet->setCellValue('J5',utf8_encode("HIGH"));
	$sheet->setCellValue('K5',utf8_encode("VERY HIGH"));
	$sheet->setCellValue('L5',utf8_encode("OTHER"));
	
	$sheet->setCellValue('M1',utf8_encode("UNIT TIME OF WU"));
	$sheet->mergeCells('M1:V1');
	$sheet->setCellValue('M2',utf8_encode($lesWP));
	$sheet->mergeCells('M2:V2');
	$sheet->setCellValue('M3',utf8_encode("CREATION"));
	$sheet->mergeCells('M3:Q3');
	$sheet->setCellValue('R3',utf8_encode("UPDATE"));
	$sheet->mergeCells('R3:V3');
	$sheet->setCellValue('M4',utf8_encode("COMPLEXITY LEVEL"));
	$sheet->mergeCells('M4:V4');
	$sheet->setCellValue('M5',utf8_encode("LOW"));
	$sheet->setCellValue('N5',utf8_encode("MEDIUM"));
	$sheet->setCellValue('O5',utf8_encode("HIGH"));
	$sheet->setCellValue('P5',utf8_encode("VERY HIGH"));
	$sheet->setCellValue('Q5',utf8_encode("OTHER"));
	$sheet->setCellValue('R5',utf8_encode("LOW"));
	$sheet->setCellValue('S5',utf8_encode("MEDIUM"));
	$sheet->setCellValue('T5',utf8_encode("HIGH"));
	$sheet->setCellValue('U5',utf8_encode("VERY HIGH"));
	$sheet->setCellValue('V5',utf8_encode("OTHER"));
	$sheet->setCellValue('W1',utf8_encode("SUB-TOTAL WORK UNIT TIME"));
	$sheet->mergeCells('W1:AF1');
	$sheet->setCellValue('W2',utf8_encode($lesWP));
	$sheet->mergeCells('W2:AF2');
	$sheet->setCellValue('W3',utf8_encode("CREATION"));
	$sheet->mergeCells('W3:AA3');
	$sheet->setCellValue('AB3',utf8_encode("UPDATE"));
	$sheet->mergeCells('AB3:AF3');
	$sheet->setCellValue('W4',utf8_encode("COMPLEXITY LEVEL"));
	$sheet->mergeCells('W4:AF4');
	$sheet->setCellValue('W5',utf8_encode("LOW"));
	$sheet->setCellValue('X5',utf8_encode("MEDIUM"));
	$sheet->setCellValue('Y5',utf8_encode("HIGH"));
	$sheet->setCellValue('Z5',utf8_encode("VERY HIGH"));
	$sheet->setCellValue('AA5',utf8_encode("OTHER"));
	$sheet->setCellValue('AB5',utf8_encode("LOW"));
	$sheet->setCellValue('AC5',utf8_encode("MEDIUM"));
	$sheet->setCellValue('AD5',utf8_encode("HIGH"));
	$sheet->setCellValue('AE5',utf8_encode("VERY HIGH"));
	$sheet->setCellValue('AF5',utf8_encode("OTHER"));

	$sheet->getStyle('A1:AF5')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A1:AF5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('A1:AF5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A1:AF5')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
	$sheet->getStyle('A1:AF5')->getFont()->setBold(true);
	$sheet->getStyle('A1:AF5')->getFont()->getColor()->setRGB('1f49a6');

	$sheet->getColumnDimension('A')->setWidth(15);
	$sheet->getColumnDimension('B')->setWidth(15);

	//ONGLET 3
	$sheet = $workbook->createSheet();
	if($_SESSION['Langue']=="EN"){
		$sheet->setTitle("Deliverable");
	}
	else{
		$sheet->setTitle("Livrable");
	}
	$req2="SELECT Id,Statut,Designation,DatePreparateur,DescriptionModification, ";
	$req2.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_travaileffectue.Id_WP) AS WP, ";
	$req2.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Tache, ";
	$req2.="(SELECT (SELECT Libelle FROM trame_familletache WHERE trame_familletache.Id=trame_tache.Id_FamilleTache) FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS FamilleTache ";
	$req2.="FROM trame_travaileffectue WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
	$req2.="AND Statut='VALIDE' AND DatePreparateur>='".TrsfDate_($_GET['DateDebut'])."' AND DatePreparateur<='".TrsfDate_($_GET['DateFin'])."' AND (";
	foreach($tabWP as $wp){
		if($wp<>""){
			$trouve=false;
			if($_GET['Tache']<>""){
				foreach($tabWPTache as $tache){
					if($tache<>""){
						$tab = explode("_",$tache);
						if($tab[0]==$wp){
							$trouve=true;
							$req2.="(trame_travaileffectue.Id_WP=".$tab[0]." AND trame_travaileffectue.Id_Tache<>".$tab[1].") OR ";
						}
					}
				}
			}
			if($trouve==false){
				$req2.="trame_travaileffectue.Id_WP=".$wp." OR ";
			}
		}
	}
	$req2=substr($req2,0,-3);
	$req2.=") ";
	$result2=mysqli_query($bdd,$req2);
	$nbResulta2=mysqli_num_rows($result2);

	if($_SESSION['Langue']=="EN"){
		$sheet->setCellValue('A1',utf8_encode("Workpackage"));
		$sheet->setCellValue('B1',utf8_encode("Task family"));
		$sheet->setCellValue('C1',utf8_encode("Task"));
		$sheet->setCellValue('D1',utf8_encode("Reference"));
		$sheet->setCellValue('E1',utf8_encode("Date of work"));
		$sheet->setCellValue('G1',utf8_encode("Status"));
		$sheet->setCellValue('H1',utf8_encode("Comment"));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode("Workpackage"));
		$sheet->setCellValue('B1',utf8_encode("Famille tâche"));
		$sheet->setCellValue('C1',utf8_encode("Tâche"));
		$sheet->setCellValue('D1',utf8_encode("Référence livrable"));
		$sheet->setCellValue('E1',utf8_encode("Date de production"));
		$sheet->setCellValue('G1',utf8_encode("Statut"));
		$sheet->setCellValue('H1',utf8_encode("Commentaire"));
	}

	$sheet->getStyle('A1:H1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle('A1:H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
	$sheet->getStyle('A1:H1')->getFont()->setBold(true);
	$sheet->getStyle('A1:H1')->getFont()->getColor()->setRGB('1f49a6');

	$sheet->getColumnDimension('A')->setWidth(25);
	$sheet->getColumnDimension('B')->setWidth(15);
	$sheet->getColumnDimension('C')->setWidth(25);
	$sheet->getColumnDimension('D')->setWidth(20);
	$sheet->getColumnDimension('E')->setWidth(20);
	$sheet->getColumnDimension('F')->setWidth(30);
	$sheet->getColumnDimension('G')->setWidth(15);
	$sheet->getColumnDimension('H')->setWidth(30);

	$ligne=2;

	while($row2=mysqli_fetch_array($result2)){
		$req="SELECT ValeurInfo, ";
		$req.="(SELECT Info FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Info, ";
		$req.="(SELECT Type FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Type ";
		$req.="FROM trame_travaileffectue_info WHERE Id_TravailEffectue=".$row2['Id'];
		$resultInfo=mysqli_query($bdd,$req);
		$nbResultaInfo=mysqli_num_rows($resultInfo);
		$Infos="";
		$nb=1;
		
		if ($nbResultaInfo>0){
			while($rowInfo=mysqli_fetch_array($resultInfo)){
				$n="\n";
				if($nbResultaInfo==$nb){$n="";}
				if($rowInfo['Type']=="Date"){
					$Infos.=$rowInfo['Info']." : ".AfficheDateFR($rowInfo['ValeurInfo']).$n;
				}
				else{
					$Infos.=$rowInfo['Info']." : ".$rowInfo['ValeurInfo'].$n;
				}
				$nb++;
			}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['WP']))));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['FamilleTache']))));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['Tache']))));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['Designation']))));
		if(AfficheDateFR($row2['DatePreparateur'])<>""){
			$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DatePreparateur']));
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
			$sheet->getStyle('E'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			$sheet->setCellValue('E'.$ligne,$time);
		}
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$Infos))));
		$sheet->getStyle('F'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('G'.$ligne,utf8_encode($row2['Statut']));
		$description=stripslashes(str_replace("\\","",$row2['DescriptionModification']));
		if(substr($description,0,1)=="=" || substr($description,0,1)=="+" || substr($description,0,1)=="-"){
			$description=substr($description,1);
		}
		$sheet->setCellValue('H'.$ligne,utf8_encode($description));
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$ligne++;
	}

	//Enregistrement du fichier excel
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
	if($_SESSION['Langue']=="EN"){
		header('Content-Disposition: attachment;filename="BL.xlsx"');
	}
	else{
		header('Content-Disposition: attachment;filename="BL.xlsx"');
	}
	header('Cache-Control: max-age=0'); 

	$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

	$chemin = '../../tmp/BL.xlsx';
	$writer->save($chemin);
	readfile($chemin);
	
}
?>