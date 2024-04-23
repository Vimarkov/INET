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

$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

$req2="SELECT Id,DateDebut,HeureDebut,HeureFin,Commentaire, ";
$req2.="((HOUR(HeureFin)*60)+ MINUTE(HeureFin)) - ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut)) AS DureeMinute, ";
$req2.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_planning.Id_WP) AS WP, ";
$req2.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_planning.Id_Tache) AS Tache, ";
$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_planning.Id_Preparateur) AS Preparateur ";
$req="FROM trame_planning WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND ";
if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0' && substr($_SESSION['DroitTR'],4,1)=='0'){
	$req.=" trame_planning.Id_Preparateur=".$_SESSION['Id_PersonneTR']." AND ";
}
else{
	if($_SESSION['EXTRACT_PreparateurPointage2']<>""){
		$tab = explode(";",$_SESSION['EXTRACT_PreparateurPointage2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
}
if($_SESSION['EXTRACT_WPPointage2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_WPPointage2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_WP=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['EXTRACT_TachePointage2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_TachePointage2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Tache=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['EXTRACT_DateDebutPointage2']<>"" || $_SESSION['EXTRACT_DateFinPointage2']<>""){
	$req.=" ( ";
	if($_SESSION['EXTRACT_DateDebut2']<>""){
		$req.="DateDebut >= '". TrsfDate_($_SESSION['EXTRACT_DateDebutPointage2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_DateFinPointage2']<>""){
		$req.="DateDebut <= '". TrsfDate_($_SESSION['EXTRACT_DateFinPointage2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

$result2=mysqli_query($bdd,$req2.$req);
$nbResulta2=mysqli_num_rows($result2);

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Date"));
	$sheet->setCellValue('B1',utf8_encode("Manufacturing engineer"));
	$sheet->setCellValue('C1',utf8_encode("Task"));
	$sheet->setCellValue('D1',utf8_encode("Workpackage"));
	$sheet->setCellValue('E1',utf8_encode("Start time"));
	$sheet->setCellValue('F1',utf8_encode("End time"));
	$sheet->setCellValue('G1',utf8_encode("Time (minutes)"));
	$sheet->setCellValue('H1',utf8_encode("Status"));
	if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
		$sheet->setCellValue('I1',utf8_encode("Comment"));
	}
}
else{
	$sheet->setCellValue('A1',utf8_encode("Date"));
	$sheet->setCellValue('B1',utf8_encode("Préparateur"));
	$sheet->setCellValue('C1',utf8_encode("Tâche"));
	$sheet->setCellValue('D1',utf8_encode("Workpackage"));
	$sheet->setCellValue('E1',utf8_encode("Heure de début"));
	$sheet->setCellValue('F1',utf8_encode("Heure de fin"));
	$sheet->setCellValue('G1',utf8_encode("Durée (minutes)"));
	$sheet->setCellValue('H1',utf8_encode("Statut"));
	if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
		$sheet->setCellValue('I1',utf8_encode("Commentaire"));
	}
}

$col="H";
if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
	$col="I";
}
$sheet->getStyle('A1:'.$col.'1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:'.$col.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:'.$col.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:'.$col.'1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:'.$col.'1')->getFont()->setBold(true);
$sheet->getStyle('A1:'.$col.'1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(35);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);
if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
	$sheet->getColumnDimension('I')->setWidth(25);
}

$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	$tabDateTransfert = explode('-', $row2['DateDebut']);
	$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
	$semaine=date("W",$timestampTransfert);
	$annee=date("Y",$timestampTransfert);
	
	$reqPoint="SELECT Id FROM trame_plannif WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Preparateur=".$_SESSION['Id_PersonneTR'];
	$reqPoint.=" AND Valide=1 AND Semaine=".$semaine." AND Annee=".$annee." ";
	
	$resultPoint=mysqli_query($bdd,$reqPoint);
	$nbResultaPoint=mysqli_num_rows($resultPoint);
	if($_SESSION['Langue']=="EN"){
		$statut="IN PROGRESS";
	}
	else{
		$statut="EN COURS";
	}
	if($nbResultaPoint>0){
		if($_SESSION['Langue']=="EN"){
			$statut="VALIDATED";
		}
		else{
			$statut="VALIDE";
		}
	}

	if(AfficheDateJJ_MM_AAAA($row2['DateDebut'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateDebut']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('A'.$ligne,$time);
		$sheet->getStyle('A'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['Preparateur']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['Tache']));
	$sheet->setCellValue('D'.$ligne,utf8_encode($row2['WP']));
	$sheet->setCellValue('E'.$ligne,utf8_encode($row2['HeureDebut']));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row2['HeureFin']));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['DureeMinute']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($statut));
	if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
		$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Commentaire']));
	}
	if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
		$sheet->getStyle('A'.$ligne.':I'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	}
	else{
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	}
	$ligne++;
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($_SESSION['Langue']=="EN"){
	header('Content-Disposition: attachment;filename="Extract_Schedule.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="Extract_Pointage.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

if($_SESSION['Langue']=="EN"){
	$chemin = '../../tmp/Extract_Schedule.xlsx';
}
else{
	$chemin = '../../tmp/Extract_Pointage.xlsx';
}
$writer->save($chemin);
readfile($chemin);

?>