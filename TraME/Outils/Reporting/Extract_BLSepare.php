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
	
	$reqWP="SELECT Id, Libelle FROM trame_wp WHERE ";
	foreach($tabWP as $wp){
		if($wp<>""){
			$reqWP.="Id=".$wp." OR ";
		}
	}
	$reqWP=substr($reqWP,0,-3);
	$reqWP.=" ORDER BY Libelle ";
	$resultWP=mysqli_query($bdd,$reqWP);
	$nbResultaWP=mysqli_num_rows($resultWP);

	//ONGLET 1
	$sheetRecp = $workbook->getActiveSheet();
	$sheetRecp->setTitle("Recap");

	//ONGLET 2
	$sheet = $workbook->createSheet();
	$sheet->setTitle("Work unit");
	$sheet->getColumnDimension('A')->setWidth(15);
	$sheet->getColumnDimension('B')->setWidth(15);

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
	$sheetRecp->getColumnDimension('E')->setWidth(15);
	$sheetRecp->getColumnDimension('F')->setWidth(15);

	$ligneRecap=3;
	$ligneWU=1;
	$ligne2=4;
	$ligne=5;
	$UO="";
	$DT="";
	$LaLigne=5;
	$colonneNb=2;
	$colonneTime=12;
	$colonneTotal=22;
	$total=0;
	
	if($nbResultaWP>0){
		$formule="=";
		
		while($rowWP=mysqli_fetch_array($resultWP)){
			$UO="";
			$DT="";
			//ONGLET 1
			$sheetRecp->setCellValue('A'.$ligneRecap,utf8_encode("Work unit price"));
			$sheetRecp->setCellValue('A'.($ligneRecap+1),utf8_encode("Additional cost (if applicable)"));
			$sheetRecp->setCellValue('A'.($ligneRecap+2),utf8_encode("Sub-Total price"));
			$sheetRecp->getStyle('A'.$ligneRecap.':A'.($ligneRecap+1))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
			$sheetRecp->getStyle('A'.($ligneRecap+2))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'b5b6bd'))));

			$sheetRecp->setCellValue('C'.($ligneRecap+1),utf8_encode("/"));
			$sheetRecp->setCellValue('C'.($ligneRecap+2),"=SUM(C".$ligneRecap.":C".($ligneRecap+1).")", PHPExcel_Cell_DataType::TYPE_FORMULA);;
			
			$sheetRecp->setCellValue('E'.$ligneRecap,utf8_encode($rowWP['Libelle']));

			$sheetRecp->mergeCells('A'.$ligneRecap.':B'.$ligneRecap);
			$sheetRecp->mergeCells('A'.($ligneRecap+1).':B'.($ligneRecap+1));
			$sheetRecp->mergeCells('A'.($ligneRecap+2).':B'.($ligneRecap+2));
			$sheetRecp->mergeCells('C'.$ligneRecap.':D'.$ligneRecap);
			$sheetRecp->mergeCells('C'.($ligneRecap+1).':D'.($ligneRecap+1));
			$sheetRecp->mergeCells('C'.($ligneRecap+2).':D'.($ligneRecap+2));
			$sheetRecp->mergeCells('E'.$ligneRecap.':F'.($ligneRecap+2));
			$sheetRecp->getStyle('E'.$ligneRecap)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetRecp->getStyle('E'.$ligneRecap)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheetRecp->getStyle('A'.$ligneRecap.':F'.($ligneRecap+2))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			
			$formule.="C".($ligneRecap+2)."+";
			
			//ONGLET 2
			$sheet->mergeCells('A'.$ligneWU.':B'.$ligneWU);
			$sheet->setCellValue('A'.($ligneWU+1),utf8_encode("WORKPACKAGE"));
			$sheet->mergeCells('A'.($ligneWU+1).':B'.($ligneWU+1));
			$sheet->setCellValue('A'.($ligneWU+2),utf8_encode("FROM"));
			$sheet->setCellValue('A'.($ligneWU+3),utf8_encode("TO"));
			$sheet->setCellValue('B'.($ligneWU+2),utf8_encode($_GET['DateDebut']));
			$sheet->setCellValue('B'.($ligneWU+3),utf8_encode($_GET['DateFin']));

			$sheet->setCellValue('A'.($ligneWU+4),utf8_encode("WORK UNIT"));
			$sheet->setCellValue('B'.($ligneWU+4),utf8_encode("TECH. DOMAIN"));

			$sheet->setCellValue('C'.$ligneWU,utf8_encode("NUMBER OF WU PRODUCED"));
			$sheet->mergeCells('C'.$ligneWU.':L'.$ligneWU);
			$sheet->setCellValue('C'.($ligneWU+1),utf8_encode($rowWP['Libelle']));
			$sheet->mergeCells('C'.($ligneWU+1).':L'.($ligneWU+1));
			$sheet->setCellValue('C'.($ligneWU+2),utf8_encode("CREATION"));
			$sheet->mergeCells('C'.($ligneWU+2).':G'.($ligneWU+2));
			$sheet->setCellValue('H'.($ligneWU+2),utf8_encode("UPDATE"));
			$sheet->mergeCells('H'.($ligneWU+2).':L'.($ligneWU+2));
			$sheet->setCellValue('C'.($ligneWU+3),utf8_encode("COMPLEXITY LEVEL"));
			$sheet->mergeCells('C'.($ligneWU+3).':L'.($ligneWU+3));
			$sheet->setCellValue('C'.($ligneWU+4),utf8_encode("LOW"));
			$sheet->setCellValue('D'.($ligneWU+4),utf8_encode("MEDIUM"));
			$sheet->setCellValue('E'.($ligneWU+4),utf8_encode("HIGH"));
			$sheet->setCellValue('F'.($ligneWU+4),utf8_encode("VERY HIGH"));
			$sheet->setCellValue('G'.($ligneWU+4),utf8_encode("OTHER"));
			$sheet->setCellValue('H'.($ligneWU+4),utf8_encode("LOW"));
			$sheet->setCellValue('I'.($ligneWU+4),utf8_encode("MEDIUM"));
			$sheet->setCellValue('J'.($ligneWU+4),utf8_encode("HIGH"));
			$sheet->setCellValue('K'.($ligneWU+4),utf8_encode("VERY HIGH"));
			$sheet->setCellValue('L'.($ligneWU+4),utf8_encode("OTHER"));
			
			$sheet->setCellValue('M'.$ligneWU,utf8_encode("UNIT TIME OF WU"));
			$sheet->mergeCells('M'.$ligneWU.':V'.$ligneWU);
			$sheet->setCellValue('M'.($ligneWU+1),utf8_encode($rowWP['Libelle']));
			$sheet->mergeCells('M'.($ligneWU+1).':V'.($ligneWU+1));
			$sheet->setCellValue('M'.($ligneWU+2),utf8_encode("CREATION"));
			$sheet->mergeCells('M'.($ligneWU+2).':Q'.($ligneWU+2));
			$sheet->setCellValue('R'.($ligneWU+2),utf8_encode("UPDATE"));
			$sheet->mergeCells('R'.($ligneWU+2).':V'.($ligneWU+2));
			$sheet->setCellValue('M'.($ligneWU+3),utf8_encode("COMPLEXITY LEVEL"));
			$sheet->mergeCells('M'.($ligneWU+3).':V'.($ligneWU+3));
			$sheet->setCellValue('M'.($ligneWU+4),utf8_encode("LOW"));
			$sheet->setCellValue('N'.($ligneWU+4),utf8_encode("MEDIUM"));
			$sheet->setCellValue('O'.($ligneWU+4),utf8_encode("HIGH"));
			$sheet->setCellValue('P'.($ligneWU+4),utf8_encode("VERY HIGH"));
			$sheet->setCellValue('Q'.($ligneWU+4),utf8_encode("OTHER"));
			$sheet->setCellValue('R'.($ligneWU+4),utf8_encode("LOW"));
			$sheet->setCellValue('S'.($ligneWU+4),utf8_encode("MEDIUM"));
			$sheet->setCellValue('T'.($ligneWU+4),utf8_encode("HIGH"));
			$sheet->setCellValue('U'.($ligneWU+4),utf8_encode("VERY HIGH"));
			$sheet->setCellValue('V'.($ligneWU+4),utf8_encode("OTHER"));
			
			
			$sheet->setCellValue('W'.$ligneWU,utf8_encode("SUB-TOTAL WORK UNIT TIME"));
			$sheet->mergeCells('W'.$ligneWU.':AF'.$ligneWU);
			$sheet->setCellValue('W'.($ligneWU+1),utf8_encode($rowWP['Libelle']));
			$sheet->mergeCells('W'.($ligneWU+1).':AF'.($ligneWU+1));
			$sheet->setCellValue('W'.($ligneWU+2),utf8_encode("CREATION"));
			$sheet->mergeCells('W'.($ligneWU+2).':AA'.($ligneWU+2));
			$sheet->setCellValue('AB'.($ligneWU+2),utf8_encode("UPDATE"));
			$sheet->mergeCells('AB'.($ligneWU+2).':AF'.($ligneWU+2));
			$sheet->setCellValue('W'.($ligneWU+3),utf8_encode("COMPLEXITY LEVEL"));
			$sheet->mergeCells('W'.($ligneWU+3).':AF'.($ligneWU+3));
			$sheet->setCellValue('W'.($ligneWU+4),utf8_encode("LOW"));
			$sheet->setCellValue('X'.($ligneWU+4),utf8_encode("MEDIUM"));
			$sheet->setCellValue('Y'.($ligneWU+4),utf8_encode("HIGH"));
			$sheet->setCellValue('Z'.($ligneWU+4),utf8_encode("VERY HIGH"));
			$sheet->setCellValue('AA'.($ligneWU+4),utf8_encode("OTHER"));
			$sheet->setCellValue('AB'.($ligneWU+4),utf8_encode("LOW"));
			$sheet->setCellValue('AC'.($ligneWU+4),utf8_encode("MEDIUM"));
			$sheet->setCellValue('AD'.($ligneWU+4),utf8_encode("HIGH"));
			$sheet->setCellValue('AE'.($ligneWU+4),utf8_encode("VERY HIGH"));
			$sheet->setCellValue('AF'.($ligneWU+4),utf8_encode("OTHER"));


			$sheet->getStyle('A'.$ligneWU.':AF'.($ligneWU+4))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneWU.':AF'.($ligneWU+4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A'.$ligneWU.':AF'.($ligneWU+4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligneWU.':AF'.($ligneWU+4))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
			$sheet->getStyle('A'.$ligneWU.':AF'.($ligneWU+4))->getFont()->setBold(true);
			$sheet->getStyle('A'.$ligneWU.':AF'.($ligneWU+4))->getFont()->getColor()->setRGB('1f49a6');
			
			$req="SELECT trame_travaileffectue_uo.TypeTravail,trame_travaileffectue_uo.Complexite, ";
			$req.="(SELECT Libelle FROM trame_uo WHERE trame_uo.Id=trame_travaileffectue_uo.Id_UO) AS UO, ";
			$req.="(SELECT Libelle FROM trame_domainetechnique WHERE trame_domainetechnique.Id=trame_travaileffectue_uo.Id_DomaineTechnique) AS DT, ";
			$req.="COUNT(trame_travaileffectue_uo.TempsAlloue) AS NbLigne, ";
			$req.="SUM(trame_travaileffectue_uo.TempsAlloue) AS SommeTempsAlloue ";
			$req.="FROM trame_travaileffectue_uo LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
			$req.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
			$req.="AND trame_travaileffectue.Statut='VALIDE' AND trame_travaileffectue.DatePreparateur>='".TrsfDate_($_GET['DateDebut'])."' ";
			$req.="AND trame_travaileffectue.DatePreparateur<='".TrsfDate_($_GET['DateFin'])."' AND trame_travaileffectue.Id_WP=".$rowWP['Id'];

			if($_GET['Tache']<>""){
				foreach($tabWPTache as $tache){
					if($tache<>""){
						$tab = explode("_",$tache);
						if($tab[0]==$rowWP['Id']){
							$req.=" AND trame_travaileffectue.Id_Tache<>".$tab[1]." ";
						}
					}
				}
			}
			$req.=" GROUP BY UO, DT, TypeTravail, Complexite ";
			$req.="ORDER BY UO, DT, TypeTravail, Complexite ";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			$ligne=$ligneWU+5;
			$LaLigne=$ligneWU+5;
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
			$ligne=$ligne+2;
			
			$ligneWU=$ligne;
			
			$sheetRecp->setCellValue('C'.$ligneRecap,utf8_encode($total));
			$ligneRecap=$ligneRecap+3;
			
		}
		
		$ligneWU=$ligne;

		if($formule<>"="){$formule=substr($formule,0,-1);}
		else{$formule="";}
		$sheetRecp->setCellValue('A'.$ligneRecap,utf8_encode("Total price"));
		$sheetRecp->setCellValue('C'.$ligneRecap,utf8_encode($formule));
		$sheetRecp->getStyle('A'.$ligneRecap.':F'.$ligneRecap)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'7b7d89'))));
		$sheetRecp->getStyle('A'.$ligneRecap.':F'.$ligneRecap)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	}

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
			$sheet->setCellValue('E'.$ligne,$time);
			$sheet->getStyle('E'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
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

	if($_SESSION['Langue']=="EN"){
		$chemin = '../../tmp/BL.xlsx';
	}
	else{
		$chemin = '../../tmp/Extract_Tache.xlsx';
	}
	$writer->save($chemin);
	readfile($chemin);
}
?>