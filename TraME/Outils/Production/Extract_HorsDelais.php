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

$req2="SELECT Id,Statut,Designation,DatePreparateur,StatutDelai,DescriptionModification,Id_Preparateur,StatutDelai, ";
$req2.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_travaileffectue.Id_WP) AS WP,CommentaireDelai, ";
$req2.="(SELECT Libelle FROM trame_responsabledelais WHERE trame_responsabledelais.Id=trame_travaileffectue.Id_ResponsableDelai) AS RespDelais, ";
$req2.="(SELECT Libelle FROM trame_causedelais WHERE trame_causedelais.Id=trame_travaileffectue.Id_CauseDelai) AS CauseDelais, ";
$req2.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Tache, ";
$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Preparateur) AS Preparateur ";
$req="FROM trame_travaileffectue WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND StatutDelai='KO' AND ";
if($_SESSION['HorsDelais_Reference2']<>""){
	$tab = explode(";",$_SESSION['HorsDelais_Reference2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Designation='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['HorsDelais_WP2']<>""){
	$tab = explode(";",$_SESSION['HorsDelais_WP2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_WP=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['HorsDelais_Tache2']<>""){
	$tab = explode(";",$_SESSION['HorsDelais_Tache2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Tache=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['HorsDelais_RespDelais2']<>""){
	$tab = explode(";",$_SESSION['HorsDelais_RespDelais2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_ResponsableDelai=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['HorsDelais_CauseDelais2']<>""){
	$tab = explode(";",$_SESSION['HorsDelais_CauseDelais2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_CauseDelai=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['HorsDelais_Statut2']<>""){
	$tab = explode(";",$_SESSION['HorsDelais_Statut2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Statut='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['HorsDelais_Preparateur2']<>""){
	$tab = explode(";",$_SESSION['HorsDelais_Preparateur2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id_Preparateur=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['HorsDelais_MotCles2']<>""){
	$tab = explode(";",$_SESSION['HorsDelais_MotCles2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Designation LIKE '%".$valeur."%' OR DescriptionModification LIKE '%".$valeur."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['HorsDelais_DateDebut2']<>"" || $_SESSION['HorsDelais_DateFin2']<>""){
	$req.=" ( ";
	if($_SESSION['HorsDelais_DateDebut2']<>""){
		$req.="DatePreparateur >= '". TrsfDate_($_SESSION['HorsDelais_DateDebut2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['HorsDelais_DateFin2']<>""){
		$req.="DatePreparateur <= '". TrsfDate_($_SESSION['HorsDelais_DateFin2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
$reqFin="";
if($_SESSION['TriHorsDelais_General']<>""){
	$req.="ORDER BY ".substr($_SESSION['TriHorsDelais_General'],0,-1);
}
$result2=mysqli_query($bdd,$req2.$req.$reqFin);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Reference"));
	$sheet->setCellValue('B1',utf8_encode("Date of work"));
	$sheet->setCellValue('C1',utf8_encode("Workpackage"));
	$sheet->setCellValue('D1',utf8_encode("Task"));
	$sheet->setCellValue('E1',utf8_encode("Deadline"));
	$sheet->setCellValue('F1',utf8_encode("Responsible of delay"));
	$sheet->setCellValue('G1',utf8_encode("Cause of delay"));
	$sheet->setCellValue('H1',utf8_encode("Status"));
	$sheet->setCellValue('I1',utf8_encode("Manufacturing Engineer"));
	$sheet->setCellValue('J1',utf8_encode("Further information"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Référence"));
	$sheet->setCellValue('B1',utf8_encode("Date du travail"));
	$sheet->setCellValue('C1',utf8_encode("Workpackage"));
	$sheet->setCellValue('D1',utf8_encode("Tâche"));
	$sheet->setCellValue('E1',utf8_encode("Statut du délais"));
	$sheet->setCellValue('F1',utf8_encode("Resonsable délais"));
	$sheet->setCellValue('G1',utf8_encode("Cause délais"));
	$sheet->setCellValue('H1',utf8_encode("Statut"));
	$sheet->setCellValue('I1',utf8_encode("Préparateur"));
	$sheet->setCellValue('J1',utf8_encode("Informations complémentaires"));
}
$sheet->getStyle('A1:J1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:J1')->getFont()->setBold(true);
$sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(50);
$sheet->getColumnDimension('E')->setWidth(13);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(13);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(35);

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
				$Infos.=$rowInfo['Info']." : ".AfficheDateJJ_MM_AAAA($rowInfo['ValeurInfo']).$n;
			}
			else{
				$Infos.=$rowInfo['Info']." : ".$rowInfo['ValeurInfo'].$n;
			}
			$nb++;
		}
	}
	$statut=$row2['Statut'];
	if($_SESSION['Langue']=="EN"){
		if($row2['Statut']=="EN COURS"){$statut="IN PROGRESS";}
		elseif($row2['Statut']=="A VALIDER"){$statut="TO BE VALIDATED";}
		elseif($row2['Statut']=="VALIDE"){$statut="VALIDATED";}
		elseif($row2['Statut']=="REFUSE"){$statut="RETURN";}
	}
	else{
		if($row2['Statut']=="REFUSE"){$statut="RETOURNE";}
	}
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['Designation']));
	if(AfficheDateJJ_MM_AAAA($row2['DatePreparateur'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DatePreparateur']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('B'.$ligne,$time);
		$sheet->getStyle('B'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['WP']))));
	$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['Tache']))));
	$sheet->setCellValue('E'.$ligne,utf8_encode($row2['StatutDelai']));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row2['RespDelais']));
	$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['CauseDelais']))));
	$sheet->setCellValue('H'.$ligne,utf8_encode($statut));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Preparateur']));
	$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$Infos))));
	$sheet->getStyle('J'.$ligne)->getAlignment()->setWrapText(true);
	
	$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($_SESSION['Langue']=="EN"){
	header('Content-Disposition: attachment;filename="Extract_OutOfTime.xlsx"'); 
}
else{
	header('Content-Disposition: attachment;filename="Extract_HorsDelais.xlsx"'); 
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_PROD.xlsx';
$writer->save($chemin);
readfile($chemin);
?>