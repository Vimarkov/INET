<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

function TrsfDate_($Date)
{
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('/', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

$msn = $_GET['msn'];
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
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+4-$NumJour, $tabDate[0]);
$leJeudi= date("Y-m-d", $timestamp);
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

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Reporting RA');

$sheet->setCellValue('A1',utf8_encode("1. Bilan MSN : "));
$sheet->setCellValue('A3',utf8_encode("MSN ".$msn));
$sheet->setCellValue('A4',utf8_encode("FGTR"));
$sheet->setCellValue('A5',utf8_encode("Système"));
$sheet->setCellValue('A6',utf8_encode("Structure"));

$sheet->setCellValue('B3',utf8_encode("RTD du jour"));
$sheet->setCellValue('C3',utf8_encode("OTD de la semaine"));
$sheet->setCellValue('D3',utf8_encode("IN de la veille"));
$sheet->setCellValue('E3',utf8_encode("OUT de la veille"));
$sheet->setCellValue('F3',utf8_encode("Nbr points PROD"));
$sheet->setCellValue('G3',utf8_encode("Nbr points QLS"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(20);

$sheet->getStyle('A3:G6')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A3:G6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A3:G6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$sheet->setCellValue('A8',utf8_encode("2. Points chauds :"));

$sheet->setCellValue('A12',utf8_encode("3. Plannif de la journée :"));

$sheet->setCellValue('A14',utf8_encode("MSN"));
$sheet->setCellValue('B14',utf8_encode("OF"));
$sheet->setCellValue('C14',utf8_encode("Titre"));
$sheet->setCellValue('D14',utf8_encode("Zone Aircraft"));
$sheet->setCellValue('E14',utf8_encode("Priorité"));
$sheet->setCellValue('F14',utf8_encode("Statut"));
$sheet->setCellValue('G14',utf8_encode("Retour"));
$sheet->setCellValue('H14',utf8_encode("Commentaire"));
$sheet->setCellValue('I14',utf8_encode("Date"));
$sheet->setCellValue('J14',utf8_encode("Vacation"));

$sheet->getStyle('A14:J14')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A14:J14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A14:J14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_ficheintervention.DateIntervention,sp_ficheintervention.Vacation, ";
$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="sp_dossier.Priorite,sp_ficheintervention.Vacation,sp_ficheintervention.Commentaire, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE sp_dossier.MSN=".$msn." AND ";
if($NumJour<=4){
	$req.="((sp_ficheintervention.DateIntervention='".$leLendemain."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJour."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Nuit'))) ";
}
$req.="ORDER BY sp_ficheintervention.DateIntervention DESC,sp_ficheintervention.Vacation ASC";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=15;
if ($nbResulta>0){	
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
		
		$couleur="#ffffff";
		if($statut=="CERT"){$couleur="#00b050";}
		elseif($statut=="QARJ" || $statut=="REWORK"){$couleur="#92d050";}
		elseif($statut=="TVS"){$couleur="#ffc000";}
		elseif($statut=="TFS"){$couleur="#538dd5";}
		
		$couleurPrio="#ffffff";
		if($row['Priorite']=="1"){$couleurPrio="#a7da4e";}
		elseif($row['Priorite']=="2"){$couleurPrio="#ffc20e";}
		else{$couleurPrio="#ed1c24";}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Titre']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Zone']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($Priorite));
		$sheet->setCellValue('F'.$ligne,utf8_encode($statut));
		$sheet->setCellValue('G'.$ligne,utf8_encode($retour));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['Commentaire']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['DateIntervention']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($vacation));
		
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}
$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("4. Compte rendu de la veille :"));
$ligne++;
$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligne,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligne,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligne,utf8_encode("Zone Aircraft"));
$sheet->setCellValue('E'.$ligne,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligne,utf8_encode("Statut PROD"));
$sheet->setCellValue('G'.$ligne,utf8_encode("RETP"));
$sheet->setCellValue('H'.$ligne,utf8_encode("Commentaire PROD"));
$sheet->setCellValue('I'.$ligne,utf8_encode("Statut QUALITE"));
$sheet->setCellValue('J'.$ligne,utf8_encode("RETQ"));
$sheet->setCellValue('K'.$ligne,utf8_encode("Commentaire QUALITE"));
$sheet->setCellValue('L'.$ligne,utf8_encode("Date"));
$sheet->setCellValue('M'.$ligne,utf8_encode("Vacation"));

$sheet->getStyle('A'.$ligne.':M'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligne.':M'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligne.':M'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$ligne++;

$req="SELECT sp_ficheintervention.Id, sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_ficheintervention.DateIntervention,sp_ficheintervention.Vacation,  ";
$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="sp_dossier.Priorite, sp_ficheintervention.CommentairePROD, sp_ficheintervention.CommentaireQUALITE, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_ficheintervention.TravailRealise,sp_dossier.Priorite ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE sp_dossier.MSN=".$msn." AND ";
if($NumJour>=2 && $NumJour<=4){
	$req.="((sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$laVeille."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}
elseif($NumJour==1){
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-3, $tabDate[0]);
	$leVendredi= date("Y-m-d", $timestamp);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-2, $tabDate[0]);
	$leSamedi= date("Y-m-d", $timestamp);	
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
	$leDimanche= date("Y-m-d", $timestamp);	
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit'))) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJeudi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}
$req.="ORDER BY sp_ficheintervention.DateIntervention DESC, sp_ficheintervention.Vacation ASC";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut="";
		if($row['Id_StatutQUALITE']<>""){$statut=$row['Id_StatutQUALITE'];}
		else{$statut=$row['Id_StatutPROD'];}
		
		$vacation="";
		if($row['Vacation']=="J"){$vacation="Jour";}
		elseif($row['Vacation']=="S"){$vacation="Soir";}
		elseif($row['Vacation']=="N"){$vacation="Nuit";}
		elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$vacation="VSD";}
		
		$couleur="#ffffff";
		if($statut=="CERT"){$couleur="#00b050";}
		elseif($statut=="QARJ" || $statut=="REWORK"){$couleur="#92d050";}
		elseif($statut=="TVS"){$couleur="#ffc000";}
		elseif($statut=="TFS"){$couleur="#538dd5";}
		
		$couleurPrio="#ffffff";
		if($row['Priorite']=="1"){$couleurPrio="#a7da4e";}
		elseif($row['Priorite']=="2"){$couleurPrio="#ffc20e";}
		else{$couleurPrio="#ed1c24";}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Titre']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Zone']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($Priorite));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Id_StatutPROD']));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['RetourPROD']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['CommentairePROD']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['Id_StatutQUALITE']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['RetourQUALITE']));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['CommentaireQUALITE']));
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['DateIntervention']));
		$sheet->setCellValue('M'.$ligne,utf8_encode($vacation));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row['RetourQUALITE']));
		$sheet->getStyle('A'.$ligne.':N'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':N'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':N'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="ReportingPROD.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/ReportingPROD.xlsx';
$writer->save($chemin);
readfile($chemin);
 ?>