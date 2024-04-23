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

$req2="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.Id_Dossier,sp_olwdossier.MSN,sp_olwdossier.Reference,sp_olwdossier.ReferenceNC,sp_olwdossier.TypeACP,";
$req2.="sp_olwdossier.Titre,sp_olwficheintervention.NumFI,sp_olwdossier.Priorite,sp_olwficheintervention.DateIntervention,sp_olwficheintervention.DateCreation,";
$req2.="sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.Vacation,sp_olwdossier.TAI_RestantACP,sp_olwficheintervention.TempsQUALITE,";
$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_Createur) AS CreateurIC,TempsST, ";
$req2.="(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Zone, 
		(SELECT Libelle FROM sp_pole WHERE sp_pole.Id=sp_olwficheintervention.Id_Pole) AS Pole, 
		(SELECT SUM(TempsST) FROM sp_olwficheintervention AS fiche WHERE fiche.Id_Dossier=sp_olwdossier.Id) AS SommeTempsST, 
		(SELECT SUM(TempsQUALITE) FROM sp_olwficheintervention AS fiche WHERE fiche.Id_Dossier=sp_olwdossier.Id) AS SommeTempsQUALITE,
		(SELECT sp_client.Libelle FROM sp_client WHERE sp_client.Id=sp_olwdossier.Id_Client) AS Client,sp_olwdossier.SectionACP, 
		sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.TravailRealise,sp_olwficheintervention.Id_StatutPROD,sp_olwficheintervention.Id_StatutQUALITE ";
$req="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwdossier.Id_Prestation=1539 AND sp_olwficheintervention.Id_StatutQUALITE='TERC' ";
if(TrsfDate_($_GET['du'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateInterventionQ >= '".TrsfDate_($_GET['du'])."' ";
}
if(TrsfDate_($_GET['au'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateInterventionQ <= '".TrsfDate_($_GET['au'])."' ";
}
$req.="ORDER BY MSN, Reference ASC ";
$result2=mysqli_query($bdd,$req2.$req);
$nbResulta2=mysqli_num_rows($result2);

$sheet = $workbook->getActiveSheet();
		
$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° OF"));
$sheet->setCellValue('C1',utf8_encode("N° NC/AM"));
$sheet->setCellValue('D1',utf8_encode("Type dossier"));
$sheet->setCellValue('E1',utf8_encode("Client"));
$sheet->setCellValue('F1',utf8_encode("Titre"));
$sheet->setCellValue('G1',utf8_encode("Date prépa"));
$sheet->setCellValue('H1',utf8_encode("Temps support technique"));
$sheet->setCellValue('I1',utf8_encode("Date d'intervention"));
$sheet->setCellValue('J1',utf8_encode("N° FI"));
$sheet->setCellValue('K1',utf8_encode("Statut PROD"));
$sheet->setCellValue('L1',utf8_encode("Statut QUALITE"));
$sheet->setCellValue('M1',utf8_encode("Création IC"));
$sheet->setCellValue('N1',utf8_encode("Nb heures intervention PROD"));
$sheet->setCellValue('O1',utf8_encode("Nb heures travail PROD"));
$sheet->setCellValue('P1',utf8_encode("Date intervention QUALITE"));
$sheet->setCellValue('Q1',utf8_encode("Temps de contrôle"));


$sheet->getStyle('A1:Q1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:Q1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:Q1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:Q1')->getFont()->setBold(true);
$sheet->getStyle('A1:Q1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(8);
$sheet->getColumnDimension('B')->setWidth(12);
$sheet->getColumnDimension('C')->setWidth(12);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(18);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);
$sheet->getColumnDimension('N')->setWidth(20);
$sheet->getColumnDimension('O')->setWidth(20);
		
$ligne=2;
mysqli_data_seek($result2,0);
while($row2=mysqli_fetch_array($result2)){
	$Priorite="";
	if($row2['Priorite']==1){$Priorite="Low";}
	elseif($row2['Priorite']==2){$Priorite="Medium";}
	else{$Priorite="High";}

	$Vacation="";
	if($row2['Vacation']=="J"){$vacation="Jour";}
	elseif($row2['Vacation']=="S"){$vacation="Soir";}
	elseif($row2['Vacation']=="N"){$vacation="Nuit";}
	elseif($row2['Vacation']=="VSD Jour"){$vacation="VSD Jour";}
	elseif($row2['Vacation']=="VSD Nuit"){$vacation="VSD Nuit";}
	
	$TempsPasseTotalFI=0;
	$TempsTravailTotalFI=0;
	$req="SELECT TempsPasse,TempsTravail 
	FROM sp_olwfi_travaileffectue 
	LEFT JOIN sp_olwficheintervention
	ON sp_olwfi_travaileffectue.Id_FI=sp_olwficheintervention.Id
	WHERE sp_olwficheintervention.Id_Dossier=".$row2['Id_Dossier']." ";
	$resultTP=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($resultTP);
	if ($nbResulta>0){
		while($rowCompagnon=mysqli_fetch_array($resultTP)){
			$TempsPasseTotalFI+=$rowCompagnon['TempsPasse'];
			$TempsTravailTotalFI+=$rowCompagnon['TempsTravail'];
		}
	}
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['Reference']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['ReferenceNC']));
	$sheet->setCellValue('D'.$ligne,utf8_encode($row2['TypeACP']));
	$sheet->setCellValue('E'.$ligne,utf8_encode($row2['Client']));
	$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row2['Titre'])));
	if(AfficheDateJJ_MM_AAAA($row2['DateCreation'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateCreation']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('G'.$ligne,$time);
		$sheet->getStyle('G'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('H'.$ligne,utf8_encode($row2['SommeTempsST']));
	if(AfficheDateJJ_MM_AAAA($row2['DateIntervention'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateIntervention']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('I'.$ligne,$time);
		$sheet->getStyle('I'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('J'.$ligne,utf8_encode($row2['NumFI']));
	$sheet->setCellValue('K'.$ligne,utf8_encode($row2['Id_StatutPROD']));
	$sheet->setCellValue('L'.$ligne,utf8_encode($row2['Id_StatutQUALITE']));
	$sheet->setCellValue('M'.$ligne,utf8_encode($row2['CreateurIC']));
	$sheet->setCellValue('N'.$ligne,utf8_encode($TempsPasseTotalFI));
	$sheet->setCellValue('O'.$ligne,utf8_encode($TempsTravailTotalFI));
	if(AfficheDateJJ_MM_AAAA($row2['DateInterventionQ'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateInterventionQ']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('P'.$ligne,$time);
		$sheet->getStyle('P'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('Q'.$ligne,utf8_encode($row2['SommeTempsQUALITE']));
	
	$sheet->getStyle('A'.$ligne.':Q'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
}
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Dossier.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Dossier.xlsx';
$writer->save($chemin);
readfile($chemin);
?>