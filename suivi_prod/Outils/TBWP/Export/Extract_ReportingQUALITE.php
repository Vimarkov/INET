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
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
$laVeille = date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
$leLendemain= date("Y-m-d", $timestamp);		
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$NumJour = date("N", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+5-$NumJour, $tabDate[0]);
$leVendredi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+6-$NumJour, $tabDate[0]);
$leSamedi= date("Y-m-d", $timestamp);	
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+7-$NumJour, $tabDate[0]);
$leDimanche= date("Y-m-d", $timestamp);	

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

$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_dossier.PNE, ";
$req.="sp_dossier.Priorite,sp_ficheintervention.CommentaireQUALITE,sp_ficheintervention.Id_RetourQUALITE,sp_ficheintervention.SaisieQualite, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_dossier.Priorite ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($laVacation=="VSD"){
	if($pole==-1){$req.="WHERE sp_ficheintervention.Id_Pole IN (2,6) AND ( ";}
	else{$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND ( ";}
	$req.="(sp_ficheintervention.DateInterventionQ='".$leVendredi."' AND sp_ficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leSamedi."' AND sp_ficheintervention.VacationQ='VSD Jour') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leSamedi."' AND sp_ficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leDimanche."' AND sp_ficheintervention.VacationQ='VSD Jour')) ";
}
else{
	if($pole==-1){$req.="WHERE sp_ficheintervention.DateInterventionQ='".$leJour."' AND sp_ficheintervention.Id_StatutQUALITE<>'' AND sp_ficheintervention.VacationQ='".$vacation."' AND sp_ficheintervention.Id_Pole IN (2,6) ";}
	else{$req.="WHERE sp_ficheintervention.DateInterventionQ='".$leJour."' AND sp_ficheintervention.Id_StatutQUALITE<>'' AND sp_ficheintervention.VacationQ='".$vacation."' AND sp_ficheintervention.Id_Pole=".$pole." ";}
}
$req.="ORDER BY sp_dossier.MSN ASC, sp_dossier.Reference ASC ";

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$req="SELECT sp_ficheintervention.Id ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($pole==-1){$req.="WHERE sp_ficheintervention.Id_Pole IN (2,6) AND sp_ficheintervention.DateCreation>='2016-05-23' AND sp_ficheintervention.DateIntervention<='".$leJour."' AND (sp_ficheintervention.Id_StatutPROD='QARJ' OR sp_ficheintervention.Id_StatutPROD='REWORK') AND ( sp_ficheintervention.Id_StatutQUALITE='' OR ( ";}
else{$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND sp_ficheintervention.DateCreation>='2016-05-23' AND sp_ficheintervention.DateIntervention<='".$leJour."' AND (sp_ficheintervention.Id_StatutPROD='QARJ' OR sp_ficheintervention.Id_StatutPROD='REWORK') AND ( sp_ficheintervention.Id_StatutQUALITE='' OR ( ";}
if($laVacation=="VSD"){
	$req.=" sp_ficheintervention.Id_StatutQUALITE<>'' AND ( ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leVendredi."' AND sp_ficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leSamedi."' AND sp_ficheintervention.VacationQ='VSD Jour') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leSamedi."' AND sp_ficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leDimanche."' AND sp_ficheintervention.VacationQ='VSD Jour')))) ";
}
else{
	$req.=" sp_ficheintervention.DateInterventionQ='".$leJour."' AND sp_ficheintervention.Id_StatutQUALITE<>'' AND sp_ficheintervention.VacationQ='".$vacation."' AND sp_ficheintervention.Id_Pole=".$pole.")) ";
}

$resultDispo=mysqli_query($bdd,$req);
$nbDispo=mysqli_num_rows($resultDispo);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Reporting QUALITE');

$sheet->setCellValue('A1',utf8_encode("1. Bilan de la vacation : "));
$sheet->setCellValue('A3',utf8_encode("Date"));
$sheet->setCellValue('A4',utf8_encode("Pôle"));
$sheet->setCellValue('A5',utf8_encode("Vacation"));
$sheet->setCellValue('A6',utf8_encode("Nombre d'IQ présent"));
$sheet->setCellValue('A7',utf8_encode("Gammes disponibles pour contrôle"));
$sheet->setCellValue('A8',utf8_encode("Gammes CERT (hors PNE) 100%"));
$sheet->setCellValue('A9',utf8_encode("Gammes CERT PNE 100%"));
$sheet->setCellValue('A10',utf8_encode("Gammes CERT only 100%"));
$sheet->setCellValue('A11',utf8_encode("Gammes avancées"));
$sheet->setCellValue('A12',utf8_encode("Gammes RETC"));

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(25);

$sheet->getStyle('A3:B12')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A3:B12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A3:B12')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$sheet->setCellValue('A14',utf8_encode("2. Points chauds : "));

$sheet->setCellValue('A17',utf8_encode("3. Activité vacation : "));

$sheet->setCellValue('A19',utf8_encode("MSN"));
$sheet->setCellValue('B19',utf8_encode("OF"));
$sheet->setCellValue('C19',utf8_encode("Titre"));
$sheet->setCellValue('D19',utf8_encode("Priorité"));
$sheet->setCellValue('E19',utf8_encode("Statut"));
$sheet->setCellValue('F19',utf8_encode("Retour"));
$sheet->setCellValue('G19',utf8_encode("Commentaire"));

$sheet->getStyle('A19:G19')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A19:G19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A19:G19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$Dispo=0;
$Cert=0;
$CertPNE=0;
$CertOnly=0;
$Avancee=0;
$Retc=0;
$ligne=20;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){	
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut=$row['Id_StatutQUALITE'];
		$retour=$row['RetourQUALITE'];
		
		$couleur="#ffffff";
		if($statut=="CERT"){$couleur="#00b050";}
		elseif($statut=="TVS"){$couleur="#ffc000";}
		
		if(($row['Id_StatutPROD']<>"QARJ" && $row['Id_StatutPROD']<>"REWORK") || (($row['Id_StatutPROD']=="QARJ" || $row['Id_StatutPROD']=="REWORK") && $statut<>"")){
			$Dispo++;
		}
		if($statut=="CERT"){
			if($row['SaisieQualite']==1){
				$CertOnly++;
			}
			else{
				if($row['PNE']==0){$Cert++;}
				else{$CertPNE++;}
			}
		}
		elseif($statut=="TVS"){
			if($row['Id_RetourQUALITE']==5){$Avancee++;}
			else{$Retc++;}
		}
		$couleurPrio="#ffffff";
		if($row['Priorite']=="1"){$couleurPrio="#a7da4e";}
		elseif($row['Priorite']=="2"){$couleurPrio="#ffc20e";}
		else{$couleurPrio="#ed1c24";}
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Titre']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($Priorite));
		$sheet->setCellValue('E'.$ligne,utf8_encode($statut));
		$sheet->setCellValue('F'.$ligne,utf8_encode($retour));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['CommentaireQUALITE']));
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

if($laVacation=="VSD"){
	$sheet->setCellValue('B3',utf8_encode($leVendredi." | ".$leSamedi." | ".$leDimanche));
}
else{
	$sheet->setCellValue('B3',utf8_encode($dateReporting));
}
$Dispo+=$nbDispo;
$sheet->setCellValue('B4',utf8_encode($lePole));
$sheet->setCellValue('B5',utf8_encode($laVacation));
$sheet->setCellValue('B7',utf8_encode($Dispo));
$sheet->setCellValue('B8',utf8_encode($Cert));
$sheet->setCellValue('B9',utf8_encode($CertPNE));
$sheet->setCellValue('B10',utf8_encode($CertOnly));
$sheet->setCellValue('B11',utf8_encode($Avancee));
$sheet->setCellValue('B12',utf8_encode($Retc));

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="ReportingQUALITE.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/ReportingQUALITE.xlsx';
$writer->save($chemin);
readfile($chemin);
 ?>