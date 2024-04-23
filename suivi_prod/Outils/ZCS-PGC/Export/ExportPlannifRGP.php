<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('PLANNIFICATION');

//orientation paysage
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//format de page
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
$sheet->getPageSetup()->setFitToHeight(10);

$dateReporting = $_GET['du'];
$leJour=TrsfDate_($dateReporting);
$tabDate = explode('-', $leJour);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
$laVeille = date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
$leLendemain= date("Y-m-d", $timestamp);		
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$NumJour = date("N", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+4-$NumJour, $tabDate[0]);
$leJeudi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+5-$NumJour, $tabDate[0]);
$leVendredi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+6-$NumJour, $tabDate[0]);
$leSamedi= date("Y-m-d", $timestamp);	
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+7-$NumJour, $tabDate[0]);
$leDimanche= date("Y-m-d", $timestamp);	
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+8-$NumJour, $tabDate[0]);
$leLundi= date("Y-m-d", $timestamp);	

$req="SELECT sp_olwdossier.MSN,sp_olwdossier.Reference,sp_olwficheintervention.Id_Dossier,sp_olwdossier.Titre,sp_olwficheintervention.DateIntervention,sp_olwficheintervention.Vacation, ";
$req.="(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Zone, ";
$req.="sp_olwdossier.Priorite,sp_olwficheintervention.Vacation, ";
$req.="sp_olwficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_olwficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQUALITE ";
$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE Id_Prestation=1539 AND ";
if($NumJour<=4){
	$req.="((sp_olwficheintervention.DateIntervention='".$leLendemain."' AND (sp_olwficheintervention.Vacation='N')) OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leJour."' AND (sp_olwficheintervention.Vacation='J' OR sp_olwficheintervention.Vacation='S'))) ";
}
else{
	$req.="((sp_olwficheintervention.DateIntervention='".$leSamedi."' AND (sp_olwficheintervention.Vacation='VSD Jour' OR sp_olwficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leDimanche."' AND (sp_olwficheintervention.Vacation='VSD Jour' OR sp_olwficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leLundi."' AND (sp_olwficheintervention.Vacation='N')) OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leVendredi."' AND (sp_olwficheintervention.Vacation='S' OR sp_olwficheintervention.Vacation='J' OR sp_olwficheintervention.Vacation='VSD Nuit'))) ";
}
$req.="ORDER BY sp_olwdossier.MSN ASC,sp_olwdossier.Priorite DESC";

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(20);

$ligneJour=1;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("JOUR"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("Retour"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Vacation"));
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$ligneJour++;
if ($nbResulta>0){	
	mysqli_data_seek($result,0);
	while($row=mysqli_fetch_array($result)){
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut="";
		$retour="";
		if($row['Id_StatutQUALITE']<>""){
			$statut=$row['Id_StatutQUALITE'];
			$retour=$row['RetourQUALITE'];
		}
		else{
			$statut=$row['Id_StatutPROD'];
			$retour=$row['RetourPROD'];
		}
		
		$vacation="";
		if($row['Vacation']=="J"){$vacation="Jour";}
		elseif($row['Vacation']=="S"){$vacation="Soir";}
		elseif($row['Vacation']=="N"){$vacation="Nuit";}
		elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$vacation="VSD";}
		
		if($row['Priorite']=="1"){$couleurPrio="ffffff";}
		elseif($row['Priorite']=="2"){$couleurPrio="ffc20e";}
		else{$couleurPrio="ed1c24";}
		
		if($vacation=="Jour"){
			$sheet->setCellValue('A'.$ligneJour,utf8_encode($row['MSN']));
			$sheet->setCellValue('B'.$ligneJour,utf8_encode($row['Reference']));
			$sheet->setCellValue('C'.$ligneJour,utf8_encode($row['Titre']));
			$sheet->getStyle('C'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('D'.$ligneJour,utf8_encode($row['Zone']));
			$sheet->setCellValue('E'.$ligneJour,utf8_encode($Priorite));
			$sheet->setCellValue('F'.$ligneJour,utf8_encode($statut));
			$sheet->setCellValue('G'.$ligneJour,utf8_encode($retour));
			$sheet->setCellValue('H'.$ligneJour,utf8_encode($row['DateIntervention']));
			$sheet->setCellValue('I'.$ligneJour,utf8_encode($vacation));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}

/*
$ligneJour++;
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("NUIT"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("Retour"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Vacation"));
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$ligneJour++;
if ($nbResulta>0){	
	mysqli_data_seek($result,0);
	while($row=mysqli_fetch_array($result)){
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut="";
		$retour="";
		if($row['Id_StatutQUALITE']<>""){
			$statut=$row['Id_StatutQUALITE'];
			$retour=$row['RetourQUALITE'];
		}
		else{
			$statut=$row['Id_StatutPROD'];
			$retour=$row['RetourPROD'];
		}
		
		$vacation="";
		if($row['Vacation']=="J"){$vacation="Jour";}
		elseif($row['Vacation']=="S"){$vacation="Soir";}
		elseif($row['Vacation']=="N"){$vacation="Nuit";}
		elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$vacation="VSD";}
		
		if($row['Priorite']=="1"){$couleurPrio="ffffff";}
		elseif($row['Priorite']=="2"){$couleurPrio="ffc20e";}
		else{$couleurPrio="ed1c24";}
		
		if($vacation=="Nuit"){
			$sheet->setCellValue('A'.$ligneJour,utf8_encode($row['MSN']));
			$sheet->setCellValue('B'.$ligneJour,utf8_encode($row['Reference']));
			$sheet->setCellValue('C'.$ligneJour,utf8_encode($row['Titre']));
			$sheet->getStyle('C'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('D'.$ligneJour,utf8_encode($row['Zone']));
			$sheet->setCellValue('E'.$ligneJour,utf8_encode($Priorite));
			$sheet->setCellValue('F'.$ligneJour,utf8_encode($statut));
			$sheet->setCellValue('G'.$ligneJour,utf8_encode($retour));
			$sheet->setCellValue('H'.$ligneJour,utf8_encode($row['DateIntervention']));
			$sheet->setCellValue('I'.$ligneJour,utf8_encode($vacation));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}
*/

$ligneJour++;
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("SOIR"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("Retour"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Vacation"));
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$ligneJour++;
if ($nbResulta>0){	
	mysqli_data_seek($result,0);
	while($row=mysqli_fetch_array($result)){
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut="";
		$retour="";
		if($row['Id_StatutQUALITE']<>""){
			$statut=$row['Id_StatutQUALITE'];
			$retour=$row['RetourQUALITE'];
		}
		else{
			$statut=$row['Id_StatutPROD'];
			$retour=$row['RetourPROD'];
		}
		
		$vacation="";
		if($row['Vacation']=="J"){$vacation="Jour";}
		elseif($row['Vacation']=="S"){$vacation="Soir";}
		elseif($row['Vacation']=="N"){$vacation="Nuit";}
		elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$vacation="VSD";}
		
		if($row['Priorite']=="1"){$couleurPrio="ffffff";}
		elseif($row['Priorite']=="2"){$couleurPrio="ffc20e";}
		else{$couleurPrio="ed1c24";}
		
		if($vacation=="Soir"){
			$sheet->setCellValue('A'.$ligneJour,utf8_encode($row['MSN']));
			$sheet->setCellValue('B'.$ligneJour,utf8_encode($row['Reference']));
			$sheet->setCellValue('C'.$ligneJour,utf8_encode($row['Titre']));
			$sheet->getStyle('C'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('D'.$ligneJour,utf8_encode($row['Zone']));
			$sheet->setCellValue('E'.$ligneJour,utf8_encode($Priorite));
			$sheet->setCellValue('F'.$ligneJour,utf8_encode($statut));
			$sheet->setCellValue('G'.$ligneJour,utf8_encode($retour));
			$sheet->setCellValue('H'.$ligneJour,utf8_encode($row['DateIntervention']));
			$sheet->setCellValue('I'.$ligneJour,utf8_encode($vacation));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}
/*
$ligneJour++;
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("VSD"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("Retour"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Vacation"));
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$ligneJour++;
if ($nbResulta>0){	
	mysqli_data_seek($result,0);
	while($row=mysqli_fetch_array($result)){
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut="";
		$retour="";
		if($row['Id_StatutQUALITE']<>""){
			$statut=$row['Id_StatutQUALITE'];
			$retour=$row['RetourQUALITE'];
		}
		else{
			$statut=$row['Id_StatutPROD'];
			$retour=$row['RetourPROD'];
		}
		
		$vacation="";
		if($row['Vacation']=="J"){$vacation="Jour";}
		elseif($row['Vacation']=="S"){$vacation="Soir";}
		elseif($row['Vacation']=="N"){$vacation="Nuit";}
		elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$vacation="VSD";}
		
		if($row['Priorite']=="1"){$couleurPrio="ffffff";}
		elseif($row['Priorite']=="2"){$couleurPrio="ffc20e";}
		else{$couleurPrio="ed1c24";}
		
		if($vacation=="VSD"){
			$sheet->setCellValue('A'.$ligneJour,utf8_encode($row['MSN']));
			$sheet->setCellValue('B'.$ligneJour,utf8_encode($row['Reference']));
			$sheet->setCellValue('C'.$ligneJour,utf8_encode($row['Titre']));
			$sheet->getStyle('C'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('D'.$ligneJour,utf8_encode($row['Zone']));
			$sheet->setCellValue('E'.$ligneJour,utf8_encode($Priorite));
			$sheet->setCellValue('F'.$ligneJour,utf8_encode($statut));
			$sheet->setCellValue('G'.$ligneJour,utf8_encode($retour));
			$sheet->setCellValue('H'.$ligneJour,utf8_encode($row['DateIntervention']));
			$sheet->setCellValue('I'.$ligneJour,utf8_encode($vacation));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':I'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}*/


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Plannification.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Plannification.xlsx';
$writer->save($chemin);
readfile($chemin);
 ?>