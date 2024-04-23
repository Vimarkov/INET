<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$pole = $_GET['pole'];
$vacation = $_GET['vacation'];
$dateReporting = $_GET['date'];
$leJour=TrsfDate_($dateReporting);
$tabDate = explode('-', $leJour);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$NumJour = date("N", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
$laVeille = date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
$leLendemain= date("Y-m-d", $timestamp);		
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
	
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+5-$NumJour, $tabDate[0]);
$leVendredi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+6-$NumJour, $tabDate[0]);
$leSamedi= date("Y-m-d", $timestamp);	
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+7-$NumJour, $tabDate[0]);
$leDimanche= date("Y-m-d", $timestamp);	

$destinataire="";
$req="SELECT EmailPro FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_PersonneSP'];
$resulEmail=mysqli_query($bdd,$req);
$nbEmail=mysqli_num_rows($resulEmail);
if ($nbEmail>0){
	$row=mysqli_fetch_array($resulEmail);
	$destinataire=$row['EmailPro'];
}

$lePole="";
if($pole==-1){
	$req="SELECT Libelle FROM new_competences_pole WHERE Id IN (2,6)";
	$lePole="A50 / M50";
}
else{
	$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$pole;
	$resulPole=mysqli_query($bdd,$req);
	$nbPole=mysqli_num_rows($resulPole);
	if ($nbPole>0){
		$row=mysqli_fetch_array($resulPole);
		$lePole=$row['Libelle'];
	}
}

$laVacation="";
if($vacation=="J"){$laVacation="Jour";}
elseif($vacation=="S"){$laVacation="Soir";}
if($vacation=="N"){$laVacation="Nuit";}
if($vacation=="VSD"){$laVacation="VSD";}

$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_dossier.PNE,Id_RetourPROD,TAI_RestantACP,CommentairePROD,";
$req.="sp_dossier.Priorite,sp_ficheintervention.CommentaireQUALITE,sp_ficheintervention.Id_RetourQUALITE,sp_ficheintervention.SaisieQualite, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_dossier.Priorite ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($laVacation=="VSD"){
	if($pole==-1){$req.="WHERE sp_ficheintervention.Id_Pole IN (2,6) AND ( ";}
	else{$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND ( ";}
	$req.="(sp_ficheintervention.DateIntervention='".$leVendredi."' AND sp_ficheintervention.Vacation='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND sp_ficheintervention.Vacation='VSD Jour') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND sp_ficheintervention.Vacation='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND sp_ficheintervention.Vacation='VSD Jour')) ";
}
else{
	if($pole==-1){$req.="WHERE sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='".$vacation."' AND sp_ficheintervention.Id_Pole IN (2,6) ";}
	else{$req.="WHERE sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='".$vacation."' AND sp_ficheintervention.Id_Pole=".$pole." ";}
}
$req.="ORDER BY sp_dossier.MSN ASC, sp_dossier.Reference ASC ";

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Reporting PROD');

$sheet->setCellValue('A1',utf8_encode("1. Bilan de la vacation : "));
$sheet->setCellValue('A3',utf8_encode("Date"));
$sheet->setCellValue('A4',utf8_encode("Pôle"));
$sheet->setCellValue('A5',utf8_encode("Vacation"));
$sheet->setCellValue('A6',utf8_encode("Nombre de BC présent"));
$sheet->setCellValue('A7',utf8_encode("Gammes Disponibles/planifiées"));
$sheet->setCellValue('A8',utf8_encode("Gammes QARJ 100%"));
$sheet->setCellValue('A9',utf8_encode("Gammes E/C"));
$sheet->setCellValue('A10',utf8_encode("Gammes RETP"));
$sheet->setCellValue('A11',utf8_encode("Gammes Relancée"));

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(35);

$sheet->getStyle('A3:B11')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A3:B11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A3:B11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$messageSuite="";
$Dispo=0;
$Qarj=0;
$Ec=0;
$Retp=0;
$Relancee=0;
$ligne=20;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut="";
		$retour="";
		if($row['Id_StatutQUALITE']<>0){$statut=$row['Id_StatutQUALITE'];}
		else{$statut=$row['Id_StatutPROD'];}
		if($row['Id_RetourPROD']<>0){$retour=$row['RetourPROD'];}
		
		
		$couleur="#ffffff";
		if($statut=="QARJ" || $statut=="REWORK"){$couleur="#92d050";}
		elseif($statut=="TFS"){$couleur="#538dd5";}
		
		$Dispo++;
		if($statut=="QARJ" || $statut=="REWORK"){$Qarj++;}
		elseif($statut=="TFS"){
			if($row['Id_RetourPROD']==15 || $row['Id_RetourPROD']==16 || $row['Id_RetourPROD']==38 || $row['Id_RetourPROD']==39){$Ec++;}
			elseif($row['Id_RetourPROD']==6 || $row['Id_RetourPROD']==14 || $row['Id_RetourPROD']==35){$Relancee++;}
			else{$Retp++;}
		}
		$couleurPrio="#ffffff";
		if($row['Priorite']=="1"){$couleurPrio="#a7da4e";}
		elseif($row['Priorite']=="2"){$couleurPrio="#ffc20e";}
		else{$couleurPrio="#ed1c24";}
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Titre']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($Priorite));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['TAI_RestantACP']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($statut));
		$sheet->setCellValue('G'.$ligne,utf8_encode($retour));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['CommentairePROD']));
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}
if($laVacation=="VSD"){
	$sheet->setCellValue('B3',utf8_encode($leVendredi." | ".$leSamedi." | ".$leDimanche));
}
else{
	$sheet->setCellValue('B3',utf8_encode($dateReporting));
}
$sheet->setCellValue('B4',utf8_encode($lePole));
$sheet->setCellValue('B5',utf8_encode($laVacation));
$sheet->setCellValue('B7',utf8_encode($Dispo));
$sheet->setCellValue('B8',utf8_encode($Qarj));
$sheet->setCellValue('B9',utf8_encode($Ec));
$sheet->setCellValue('B10',utf8_encode($Retp));
$sheet->setCellValue('B11',utf8_encode($Relancee));

$sheet->setCellValue('A13',utf8_encode("2. Points chauds : "));

$sheet->setCellValue('A17',utf8_encode("3. Activité vacation : "));

$sheet->setCellValue('A19',utf8_encode("MSN"));
$sheet->setCellValue('B19',utf8_encode("OF"));
$sheet->setCellValue('C19',utf8_encode("Titre"));
$sheet->setCellValue('D19',utf8_encode("Priorité"));
$sheet->setCellValue('E19',utf8_encode("TAI restant"));
$sheet->setCellValue('F19',utf8_encode("Statut"));
$sheet->setCellValue('G19',utf8_encode("Retour"));
$sheet->setCellValue('H19',utf8_encode("Commentaire"));

$sheet->getStyle('A19:H19')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A19:H19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A19:H19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="ReportingPROD.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/ReportingPROD.xlsx';
$writer->save($chemin);
readfile($chemin);
 ?>