<?php
session_start();
require("../ConnexioniSansBody.php");
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
require("../Fonctions.php");


$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '2048MB', 'cacheTime' => 6000);
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract");

$req2="SELECT trame_travaileffectue.Id,Statut,Designation,DatePreparateur,DateValidation,StatutDelai,DescriptionModification,TempsPasse,HeurePreparateur, ";
$req2.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_travaileffectue.Id_WP) AS WP, ";
$req2.="Complexite,Relation,TypeTravail,TempsAlloue, ";
$req2.="(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_travaileffectue_uo.Id_UO) AS UO, ";
$req2.="(SELECT Libelle FROM trame_uo WHERE trame_uo.Id=trame_travaileffectue_uo.Id_UO) AS UORef, ";
$req2.="(SELECT (SELECT Libelle FROM trame_categorie WHERE trame_categorie.Id=trame_uo.Id_Categorie) FROM trame_uo WHERE trame_uo.Id=trame_travaileffectue_uo.Id_UO) AS Categorie, ";
$req2.="(SELECT Libelle FROM trame_domainetechnique WHERE trame_domainetechnique.Id=trame_travaileffectue_uo.Id_DomaineTechnique) AS DT, ";
$req2.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Tache, ";
$req2.="(SELECT Libelle FROM trame_responsabledelais WHERE trame_responsabledelais.Id=trame_travaileffectue.Id_ResponsableDelai) AS ResponsableDelais, ";
$req2.="(SELECT Libelle FROM trame_causedelais WHERE trame_causedelais.Id=trame_travaileffectue.Id_CauseDelai) AS CauseDelais, ";
$req2.="(SELECT (SELECT Libelle FROM trame_familletache WHERE trame_familletache.Id=trame_tache.Id_FamilleTache) FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS FamilleTache, ";
$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Preparateur) AS Preparateur, ";
$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Responsable) AS Valideur ";
$req="FROM trame_travaileffectue_uo LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
$req.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_travaileffectue_uo.TravailFait=1 AND ";
if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0' && substr($_SESSION['DroitTR'],4,1)=='0'){
	$req.=" trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." AND ";
}
if($_SESSION['EXTRACT_WP2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_WP2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="trame_travaileffectue.Id_WP=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['EXTRACT_Statut2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_Statut2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="trame_travaileffectue.Statut='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['EXTRACT_DateDebut2']<>"" || $_SESSION['EXTRACT_DateFin2']<>""){
	$req.=" ( ";
	if($_SESSION['EXTRACT_DateDebut2']<>""){
		$req.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['EXTRACT_DateDebut2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_DateFin2']<>""){
		$req.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['EXTRACT_DateFin2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) AND ";
}
if($_SESSION['EXTRACT_Controle2']<>""){
	$req.=" (SELECT COUNT(trame_controlecroise.Id) FROM trame_controlecroise WHERE trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id)>0 AND ";
}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
//echo $req2.$req;
$result2=mysqli_query($bdd,$req2.$req);
$nbResulta2=mysqli_num_rows($result2);

$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
$resultPlanning=mysqli_query($bdd,$reqPlanning);
$nbResultaPlanning=mysqli_num_rows($resultPlanning);
if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Workpackage"));
	$sheet->setCellValue('B1',utf8_encode("Task family"));
	$sheet->setCellValue('C1',utf8_encode("Task"));
	$sheet->setCellValue('D1',utf8_encode("Work unit"));
	$sheet->setCellValue('E1',utf8_encode("Technical domain"));
	$sheet->setCellValue('F1',utf8_encode("Category"));
	$sheet->setCellValue('G1',utf8_encode("Type of work"));
	$sheet->setCellValue('H1',utf8_encode("Complexity"));
	$sheet->setCellValue('I1',utf8_encode("Reference"));
	$sheet->setCellValue('J1',utf8_encode("Manufacturing engineer"));
	$sheet->setCellValue('K1',utf8_encode("Validator"));
	$sheet->setCellValue('L1',utf8_encode("Date of work"));
	$sheet->setCellValue('M1',utf8_encode("Validation date"));
	$sheet->setCellValue('N1',utf8_encode("Deadline"));
	$sheet->setCellValue('O1',utf8_encode("Responsible of delay"));
	$sheet->setCellValue('P1',utf8_encode("Cause of delay"));
	$sheet->setCellValue('Q1',utf8_encode("Anomaly"));
	$sheet->setCellValue('R1',utf8_encode("Comment"));
	$sheet->setCellValue('S1',utf8_encode("Status"));
	$sheet->setCellValue('T1',utf8_encode("Time allocated"));
	if($nbResultaPlanning>0){
		$sheet->setCellValue('U1',utf8_encode("Time spent"));
		$sheet->setCellValue('V1',utf8_encode("Ref Worf unit"));
		$sheet->setCellValue('W1',utf8_encode("Id"));
		$sheet->setCellValue('X1',utf8_encode("Working hours"));
	}
	else{
		$sheet->setCellValue('U1',utf8_encode("Ref Worf unit"));
		$sheet->setCellValue('V1',utf8_encode("Id"));
		$sheet->setCellValue('W1',utf8_encode("Working hours"));
	}
	
}
else{
	$sheet->setCellValue('A1',utf8_encode("Workpackage"));
	$sheet->setCellValue('B1',utf8_encode("Famille tâche"));
	$sheet->setCellValue('C1',utf8_encode("Tâche"));
	$sheet->setCellValue('D1',utf8_encode("Unité d'oeuvre"));
	$sheet->setCellValue('E1',utf8_encode("Domaine technique"));
	$sheet->setCellValue('F1',utf8_encode("Catégorie"));
	$sheet->setCellValue('G1',utf8_encode("Type de travail"));
	$sheet->setCellValue('H1',utf8_encode("Complexité"));
	$sheet->setCellValue('I1',utf8_encode("Référence livrable"));
	$sheet->setCellValue('J1',utf8_encode("Préparateur"));
	$sheet->setCellValue('K1',utf8_encode("Valideur"));
	$sheet->setCellValue('L1',utf8_encode("Date de production"));
	$sheet->setCellValue('M1',utf8_encode("Date de validation"));
	$sheet->setCellValue('N1',utf8_encode("Statut délais"));
	$sheet->setCellValue('O1',utf8_encode("Responsable délais"));
	$sheet->setCellValue('P1',utf8_encode("Cause délais"));
	$sheet->setCellValue('Q1',utf8_encode("Anomalie"));
	$sheet->setCellValue('R1',utf8_encode("Commentaire"));
	$sheet->setCellValue('S1',utf8_encode("Statut"));
	$sheet->setCellValue('T1',utf8_encode("Temps alloué"));
	if($nbResultaPlanning>0){
		$sheet->setCellValue('U1',utf8_encode("Temps passé"));
		$sheet->setCellValue('V1',utf8_encode("Ref UO"));
		$sheet->setCellValue('W1',utf8_encode("Id"));
		$sheet->setCellValue('X1',utf8_encode("Heure du travail"));
	}
	else{
		$sheet->setCellValue('U1',utf8_encode("Ref UO"));
		$sheet->setCellValue('V1',utf8_encode("Id"));
		$sheet->setCellValue('W1',utf8_encode("Heure du travail"));
	}
}

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(30);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(20);
$sheet->getColumnDimension('N')->setWidth(15);
$sheet->getColumnDimension('O')->setWidth(15);
$sheet->getColumnDimension('P')->setWidth(15);
$sheet->getColumnDimension('Q')->setWidth(15);
$sheet->getColumnDimension('R')->setWidth(30);
$sheet->getColumnDimension('S')->setWidth(15);
$sheet->getColumnDimension('T')->setWidth(15);
if($nbResultaPlanning>0){
	$sheet->getColumnDimension('U')->setWidth(20);
}

//Liste des colonnes
$req="SELECT DISTINCT (SELECT Info FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Info ";
$req.="FROM trame_travaileffectue_info LEFT JOIN trame_travaileffectue ON trame_travaileffectue_info.Id_TravailEffectue=trame_travaileffectue.Id ";
$req.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND ";
if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0' && substr($_SESSION['DroitTR'],4,1)=='0'){
	$req.=" trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." AND ";
}
if($_SESSION['EXTRACT_WP2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_WP2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="trame_travaileffectue.Id_WP=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['EXTRACT_Statut2']<>""){
	$tab = explode(";",$_SESSION['EXTRACT_Statut2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="trame_travaileffectue.Statut='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['EXTRACT_DateDebut2']<>"" || $_SESSION['EXTRACT_DateFin2']<>""){
	$req.=" ( ";
	if($_SESSION['EXTRACT_DateDebut2']<>""){
		$req.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['EXTRACT_DateDebut2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_DateFin2']<>""){
		$req.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['EXTRACT_DateFin2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
$resultListeInfo=mysqli_query($bdd,$req);
$nbResultaListeInfo=mysqli_num_rows($resultListeInfo);

$col="W";
if($nbResultaPlanning>0){
	$col="X";
}
if($nbResultaListeInfo>0){
	while($rowCol=mysqli_fetch_array($resultListeInfo)){
		$col++;
		$sheet->setCellValue($col.'1',utf8_encode($rowCol['Info']));
	}
}
$sheet->getStyle('A1:'.$col.'1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:'.$col.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:'.$col.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:'.$col.'1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:'.$col.'1')->getFont()->setBold(true);
$sheet->getStyle('A1:'.$col.'1')->getFont()->getColor()->setRGB('1f49a6');

$req="SELECT Reference FROM trame_anomalie WHERE ".$_SESSION['Id_PrestationTR']." AND ";
if($_SESSION['EXTRACT_DateDebut2']<>"" || $_SESSION['EXTRACT_DateFin2']<>""){
	$req.=" ( ";
	if($_SESSION['EXTRACT_DateDebut2']<>""){
		$req.="DateAnomalie >= '". TrsfDate_($_SESSION['EXTRACT_DateDebut2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['EXTRACT_DateFin2']<>""){
		$req.="DateAnomalie <= '". TrsfDate_($_SESSION['EXTRACT_DateFin2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
$resultAnomalie=mysqli_query($bdd,$req);
$nbResultaAnomalie=mysqli_num_rows($resultAnomalie);

$ligne=2;
while($row2=mysqli_fetch_array($result2)){
	if($_SESSION['Langue']=="EN"){
		$anomalie="NO";
	}
	else{
		$anomalie="NON";
	}
	$nbAnomalie=0;
	if ($nbResultaAnomalie>0){
		mysqli_data_seek($resultAnomalie,0);
		while($rowAnomalie=mysqli_fetch_array($resultAnomalie)){
			if($rowAnomalie['Reference']==$row2['Designation']){
				$nbAnomalie++;
			}
		}
	}
	if($nbAnomalie>0){
		if($_SESSION['Langue']=="EN"){;
			$anomalie="YES";
		}
		else{
			$anomalie="OUI";
		}
	}

	$Infos="";
	$nb=1;
	$col="W";
	if($nbResultaPlanning>0){
		$col="X";
	}
	
	$req="SELECT trame_travaileffectue_info.ValeurInfo,trame_travaileffectue_info.Id_TravailEffectue, ";
	$req.="(SELECT Info FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Info, ";
	$req.="(SELECT Type FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Type ";
	$req.="FROM trame_travaileffectue_info LEFT JOIN trame_travaileffectue ON trame_travaileffectue_info.Id_TravailEffectue=trame_travaileffectue.Id ";
	$req.="WHERE trame_travaileffectue_info.Id_TravailEffectue=".$row2['Id']." ";
	$resultInfo=mysqli_query($bdd,$req);
	$nbResultaInfo=mysqli_num_rows($resultInfo);

	if($nbResultaListeInfo>0){
		mysqli_data_seek($resultListeInfo,0);
		while($rowCol=mysqli_fetch_array($resultListeInfo)){
			$col++;
			if ($nbResultaInfo>0){
				mysqli_data_seek($resultInfo,0);
				while($rowInfo=mysqli_fetch_array($resultInfo)){
					if($rowInfo['Id_TravailEffectue']==$row2['Id'] && strtoupper($rowInfo['Info'])==strtoupper($rowCol['Info'])){
						$sheet->setCellValue($col.$ligne,utf8_encode($rowInfo['ValeurInfo']));
					}
				}
			}
		}
	}
	$statut=$row2['Statut'];
	if($_SESSION['Langue']=="EN"){
		if($row2['Statut']=="EN COURS"){$statut="IN PROGRESS";}
		elseif($row2['Statut']=="BLOQUE"){$statut="BLOCKED";}
		elseif($row2['Statut']=="EN ATTENTE"){$statut="WAITING";}
		elseif($row2['Statut']=="A VALIDER"){$statut="TO BE VALIDATED";}
		elseif($row2['Statut']=="VALIDE"){$statut="VALIDATED";}
		elseif($row2['Statut']=="REFUSE"){$statut="RETURN";}
		elseif($row2['Statut']=="AC"){$statut="AUTO CONTROL";}
		elseif($row2['Statut']=="CONTROLE"){$statut="CONTROL";}
		if($row2['Statut']=="REC"){$statut="CONTROL AGAIN";}
	}
	else{
		if($row2['Statut']=="AC"){$statut="AUTO-CONTROLE";}
		if($row2['Statut']=="REC"){$statut="RECONTROLE";}
		elseif($row2['Statut']=="REFUSE"){$statut="RETOURNE";}
	}
	
	$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['WP']))));
	$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['FamilleTache']))));
	$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$row2['Tache']))));
	$sheet->setCellValue('D'.$ligne,utf8_encode($row2['UO']));
	$sheet->setCellValue('E'.$ligne,utf8_encode($row2['DT']));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row2['Categorie']));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['TypeTravail']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($row2['Complexite']));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Designation']));
	$sheet->setCellValue('J'.$ligne,utf8_encode($row2['Preparateur']));
	$sheet->setCellValue('K'.$ligne,utf8_encode($row2['Valideur']));
	if(AfficheDateFR($row2['DatePreparateur'])<>""){
		$date = explode("-",$row2['DatePreparateur']);
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
		$sheet->setCellValue('L'.$ligne,$time);
		$sheet->getStyle('L'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	if(AfficheDateFR($row2['DateValidation'])<>""){
		$date = explode("-",$row2['DateValidation']);
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
		$sheet->setCellValue('M'.$ligne,$time);
		$sheet->getStyle('M'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('N'.$ligne,utf8_encode($row2['StatutDelai']));
	$sheet->setCellValue('O'.$ligne,utf8_encode($row2['ResponsableDelais']));
	$sheet->setCellValue('P'.$ligne,utf8_encode($row2['CauseDelais']));
	$sheet->setCellValue('Q'.$ligne,utf8_encode($anomalie));
	$description=stripslashes(str_replace("\\","",$row2['DescriptionModification']));
	if(substr($description,0,1)=="=" || substr($description,0,1)=="+" || substr($description,0,1)=="-"){
		$description=substr($description,1);
	}
	$sheet->setCellValue('R'.$ligne,utf8_encode($description));
	$sheet->setCellValue('S'.$ligne,utf8_encode($statut));
	$sheet->setCellValue('T'.$ligne,utf8_encode($row2['TempsAlloue']));
	if($nbResultaPlanning>0){
		$sheet->setCellValue('U'.$ligne,utf8_encode($row2['TempsPasse']));
		$sheet->setCellValue('V'.$ligne,utf8_encode($row2['UORef']));
		$sheet->setCellValue('W'.$ligne,utf8_encode($row2['Id']));
		$sheet->setCellValue('X'.$ligne,utf8_encode($row2['HeurePreparateur']));
	}
	else{
		$sheet->setCellValue('U'.$ligne,utf8_encode($row2['UORef']));
		$sheet->setCellValue('V'.$ligne,utf8_encode($row2['Id']));
		$sheet->setCellValue('W'.$ligne,utf8_encode($row2['HeurePreparateur']));
	}
	
	$ligne++;
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

//$writer->save('php://output');
?>