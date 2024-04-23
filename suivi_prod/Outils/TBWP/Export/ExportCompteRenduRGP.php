<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Compte rendu');

//orientation paysage
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//format de page
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
$sheet->getPageSetup()->setFitToHeight(10);

function TrsfDate_($Date){
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;}
		elseif (preg_match("/Firefox/i", $_SERVER["HTTP_USER_AGENT"], $version)){
			$ub="Firefox";
			if(!preg_match_all('#(?<browser>'.join('|', array('Version', $ub, 'other')).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#', $_SERVER["HTTP_USER_AGENT"], $matches)) { }
			$i = count($matches['browser']);
			if($i !== 1) {
				if(strripos($_SERVER["HTTP_USER_AGENT"], "Version") < strripos($_SERVER["HTTP_USER_AGENT"], $ub)) {
					$version = $matches['version'][0];
				} else {
					$version = $matches['version'][1];
				}
			} else {
				$version = $matches['version'][0];
			}
			if($version == null) {
				$version = "";
			}
			if($version>="57.0"){
				$NavigOk = true;
			}
			else{
				$NavigOk = false;
			}
		} 
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

$pole = $_GET['pole'];
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
		if($pole<>5){
			$lePole=$row['Libelle'];
		}
		else{
			$lePole="STRUCTURE";
		}
	}
}

$req="SELECT DISTINCT Id_RetourPROD, (SELECT Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,sp_dossier.PNE,sp_dossier.Fuel,sp_dossier.Elec,sp_dossier.Hydraulique,sp_dossier.Metal,sp_dossier.Structure,sp_dossier.Systeme,sp_dossier.Oxygene, ";
$req.="(SELECT EstRetour FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS EstRetour ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($pole==-1){$req.="WHERE sp_ficheintervention.Id_Pole IN (2,6) AND Id_RetourPROD<>0 AND sp_ficheintervention.Vacation<>'' AND Id_StatutPROD<>'' AND ";}
else{$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND Id_RetourPROD<>0 AND sp_ficheintervention.Vacation<>'' AND Id_StatutPROD<>'' AND ";}
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
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='N')) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJeudi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}
$req.=" ORDER BY RetourPROD ";
$resultRetourProd=mysqli_query($bdd,$req);
$nbRETP=mysqli_num_rows($resultRetourProd);

$req="SELECT DISTINCT Id_Dossier,Vacation ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($pole==-1){$req.="WHERE sp_ficheintervention.Id_Pole IN (2,6) AND Id_StatutPROD<>'' AND ";}
else{$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND Id_StatutPROD<>'' AND ";}
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
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='N')) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJeudi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}
$resultGamme=mysqli_query($bdd,$req);
$nbGamme=mysqli_num_rows($resultGamme);

$req="SELECT Id_Pole,Id_StatutPROD, Id_RetourPROD, Id_StatutQUALITE,Vacation, ";
$req.="(SELECT EstRetour FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS EstRetour ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($pole==-1){$req.="WHERE sp_ficheintervention.Id_Pole IN (2,6) AND Id_StatutPROD='TFS' AND ";}
else{$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND Id_StatutPROD='TFS' AND ";}
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
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='N')) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJeudi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$ligne=1;
$sheet->setCellValue('A'.$ligne,utf8_encode("Date : ".$dateReporting));
$sheet->mergeCells('A1:C1');
$sheet->setCellValue('D'.$ligne,utf8_encode("J"));
$sheet->setCellValue('E'.$ligne,utf8_encode("PN"));
$sheet->setCellValue('F'.$ligne,utf8_encode("GN"));
$sheet->setCellValue('G'.$ligne,utf8_encode("VSD"));
$sheet->setCellValue('H'.$ligne,utf8_encode("Total"));

$req="SELECT sp_ficheintervention.Id_RetourPROD,Vacation,sp_dossier.PNE, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($pole==-1){$req.="WHERE sp_ficheintervention.Id_Pole IN (2,6) AND sp_ficheintervention.Vacation<>'' AND ";}
else{$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND sp_ficheintervention.Vacation<>'' AND ";}
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
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='N')) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJeudi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}

$result2=mysqli_query($bdd,$req);
$nbResulta2=mysqli_num_rows($result2);

$DispoJ=0;
$DispoPN=0;
$DispoGN=0;
$DispoVSD=0;
$DispoTOTAL=0;

$QarjJ=0;
$QarjPN=0;
$QarjGN=0;
$QarjVSD=0;
$QarjTOTAL=0;

$ReworkJ=0;
$ReworkPN=0;
$ReworkGN=0;
$ReworkVSD=0;
$ReworkTOTAL=0;

$EcJ=0;
$EcPN=0;
$EcGN=0;
$EcVSD=0;
$EcTOTAL=0;

$RetpJ=0;
$RetpPN=0;
$RetpGN=0;
$RetpVSD=0;
$RetpTOTAL=0;

$RelanceeJ=0;
$RelanceePN=0;
$RelanceeGN=0;
$RelanceeVSD=0;
$RelanceeTOTAL=0;

$TercJ=0;
$TercPN=0;
$TercGN=0;
$TercVSD=0;
$TercTOTAL=0;

$TercPNEJ=0;
$TercPNEPN=0;
$TercPNEGN=0;
$TercPNEVSD=0;
$TercPNETOTAL=0;

if ($nbResulta2>0){	
	while($row=mysqli_fetch_array($result2)){
		$statut="";
		$retour="";
		$statut=$row['Id_StatutPROD'];
		$statutQ=$row['Id_StatutQUALITE'];
		
		if($row['Vacation']=="J"){$DispoJ++;}
		elseif($row['Vacation']=="S"){$DispoPN++;}
		elseif($row['Vacation']=="N"){$DispoGN++;}
		elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$DispoVSD++;}
		$DispoTOTAL++;
		
		if($statutQ=="CERT" ){
			if($row['PNE']==0){
				if($row['Vacation']=="J"){$TercJ++;}
				elseif($row['Vacation']=="S"){$TercPN++;}
				elseif($row['Vacation']=="N"){$TercGN++;}
				elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$TercVSD++;}
				$TercTOTAL++;
			}
			else{
				if($row['Vacation']=="J"){$TercPNEJ++;}
				elseif($row['Vacation']=="S"){$TercPNEPN++;}
				elseif($row['Vacation']=="N"){$TercPNEGN++;}
				elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$TercPNEVSD++;}
				$TercPNETOTAL++;
			}
		}
		if($statut=="QARJ" ){
			if($row['Vacation']=="J"){$QarjJ++;}
			elseif($row['Vacation']=="S"){$QarjPN++;}
			elseif($row['Vacation']=="N"){$QarjGN++;}
			elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$QarjVSD++;}
			$QarjTOTAL++;
		}
		elseif($statut=="REWORK"){
			if($row['Vacation']=="J"){$ReworkJ++;}
			elseif($row['Vacation']=="S"){$ReworkPN++;}
			elseif($row['Vacation']=="N"){$ReworkGN++;}
			elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$ReworkVSD++;}
			$ReworkTOTAL++;
		}
		elseif($statut=="TFS"){
			if($row['Id_RetourPROD']==15 || $row['Id_RetourPROD']==16 || $row['Id_RetourPROD']==38 || $row['Id_RetourPROD']==39){
				if($row['Vacation']=="J"){$EcJ++;}
				elseif($row['Vacation']=="S"){$EcPN++;}
				elseif($row['Vacation']=="N"){$EcGN++;}
				elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$EcVSD++;}
				$EcTOTAL++;
			}
			elseif($row['Id_RetourPROD']==6 || $row['Id_RetourPROD']==14 || $row['Id_RetourPROD']==35){
				if($row['Vacation']=="J"){$RelanceeJ++;}
				elseif($row['Vacation']=="S"){$RelanceePN++;}
				elseif($row['Vacation']=="N"){$RelanceeGN++;}
				elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$RelanceeVSD++;}
				$RelanceeTOTAL++;
			}
			else{
				if($row['Vacation']=="J"){$RetpJ++;}
				elseif($row['Vacation']=="S"){$RetpPN++;}
				elseif($row['Vacation']=="N"){$RetpGN++;}
				elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$RetpVSD++;}
				$RetpTOTAL++;
			
			}
		}
	}
}

$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("Nombre de gammes planifiées"));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$sheet->setCellValue('D'.$ligne,utf8_encode($DispoJ));
$sheet->setCellValue('E'.$ligne,utf8_encode($DispoPN));
$sheet->setCellValue('F'.$ligne,utf8_encode($DispoGN));
$sheet->setCellValue('G'.$ligne,utf8_encode($DispoVSD));
$sheet->setCellValue('H'.$ligne,utf8_encode($DispoTOTAL));

$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("Nombre de QARJ"));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$sheet->setCellValue('D'.$ligne,utf8_encode($QarjJ));
$sheet->setCellValue('E'.$ligne,utf8_encode($QarjPN));
$sheet->setCellValue('F'.$ligne,utf8_encode($QarjGN));
$sheet->setCellValue('G'.$ligne,utf8_encode($QarjVSD));
$sheet->setCellValue('H'.$ligne,utf8_encode($QarjTOTAL));

$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("Nombre de Rework"));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$sheet->setCellValue('D'.$ligne,utf8_encode($ReworkJ));
$sheet->setCellValue('E'.$ligne,utf8_encode($ReworkPN));
$sheet->setCellValue('F'.$ligne,utf8_encode($ReworkGN));
$sheet->setCellValue('G'.$ligne,utf8_encode($ReworkVSD));
$sheet->setCellValue('H'.$ligne,utf8_encode($ReworkTOTAL));

$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("Nombre de TERC (hors PNE)"));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$sheet->setCellValue('D'.$ligne,utf8_encode($TercJ));
$sheet->setCellValue('E'.$ligne,utf8_encode($TercPN));
$sheet->setCellValue('F'.$ligne,utf8_encode($TercGN));
$sheet->setCellValue('G'.$ligne,utf8_encode($TercVSD));
$sheet->setCellValue('H'.$ligne,utf8_encode($TercTOTAL));

$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("Nombre de TERC PNE"));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$sheet->setCellValue('D'.$ligne,utf8_encode($TercPNEJ));
$sheet->setCellValue('E'.$ligne,utf8_encode($TercPNEPN));
$sheet->setCellValue('F'.$ligne,utf8_encode($TercPNEGN));
$sheet->setCellValue('G'.$ligne,utf8_encode($TercPNEVSD));
$sheet->setCellValue('H'.$ligne,utf8_encode($TercPNETOTAL));

$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("Nombre d'en cours"));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$sheet->setCellValue('D'.$ligne,utf8_encode($EcJ));
$sheet->setCellValue('E'.$ligne,utf8_encode($EcPN));
$sheet->setCellValue('F'.$ligne,utf8_encode($EcGN));
$sheet->setCellValue('G'.$ligne,utf8_encode($EcVSD));
$sheet->setCellValue('H'.$ligne,utf8_encode($EcTOTAL));

$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("Nombre de relance"));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$sheet->setCellValue('D'.$ligne,utf8_encode($RelanceeJ));
$sheet->setCellValue('E'.$ligne,utf8_encode($RelanceePN));
$sheet->setCellValue('F'.$ligne,utf8_encode($RelanceeGN));
$sheet->setCellValue('G'.$ligne,utf8_encode($RelanceeVSD));
$sheet->setCellValue('H'.$ligne,utf8_encode($RelanceeTOTAL));

$ligne++;

//Nb retours
$sheet->setCellValue('A'.$ligne,utf8_encode("Nombre de retours"));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$nb=0;
$nbJ=0;
$nbPN=0;
$nbGN=0;
$nbVSD=0;
$nbTotal=0;
if ($nbResulta>0){
	mysqli_data_seek($result,0);
	while($row=mysqli_fetch_array($result)){
		if($row['EstRetour']==1){
			if($row['Vacation']=="J"){$nbJ++;}
			elseif($row['Vacation']=="S"){$nbPN++;}
			elseif($row['Vacation']=="N"){$nbGN++;}
			elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$nbVSD++;}
			$nbTotal++;
		}
	}
}
$sheet->setCellValue('D'.$ligne,utf8_encode($nbJ));
$sheet->setCellValue('E'.$ligne,utf8_encode($nbPN));
$sheet->setCellValue('F'.$ligne,utf8_encode($nbGN));
$sheet->setCellValue('G'.$ligne,utf8_encode($nbVSD));
$sheet->setCellValue('H'.$ligne,utf8_encode($nbTotal));

$ligne++;
$sheet->setCellValue('A'.$ligne,utf8_encode("Taux de retours"));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$nbRetour=0;
$nbRetourJ=0;
$nbRetourPN=0;
$nbRetourGN=0;
$nbRetourVSD=0;
$nbRetourTotal=0;
if ($nbResulta>0){
	mysqli_data_seek($result,0);
	$nbRetourJ=0;
	$nbRetourPN=0;
	$nbRetourGN=0;
	$nbRetourVSD=0;
	$nbRetourTotal=0;
	while($row=mysqli_fetch_array($result)){
		if($row['EstRetour']==1){
			if($row['Vacation']=="J"){$nbRetourJ++;}
			elseif($row['Vacation']=="S"){$nbRetourPN++;}
			elseif($row['Vacation']=="N"){$nbRetourGN++;}
			elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$nbRetourVSD++;}
			$nbRetourTotal++;
		}
	}
}

$nbGa=0;
$nbGaJ=0;
$nbGaPN=0;
$nbGaGN=0;
$nbGaVSD=0;
$nbGaTotal=0;
if ($nbGamme>0){
	mysqli_data_seek($resultGamme,0);
	while($rowGamme=mysqli_fetch_array($resultGamme)){
		if($rowGamme['Vacation']=="J"){$nbGaJ++;}
		elseif($rowGamme['Vacation']=="S"){$nbGaPN++;}
		elseif($rowGamme['Vacation']=="N"){$nbGaGN++;}
		elseif($rowGamme['Vacation']=="VSD Jour" || $rowGamme['Vacation']=="VSD Nuit"){$nbGaVSD++;}
		$nbGaTotal++;
	}
}
$TauxJ=0;
$TauxPN=0;
$TauxGN=0;
$TauxVSD=0;
$TauxTotal=0;
if($nbGaJ>0){$TauxJ=round(($nbRetourJ/$nbGaJ)*100,0);}
if($nbGaPN>0){$TauxPN=round(($nbRetourPN/$nbGaPN)*100,0);}
if($nbGaGN>0){$TauxGN=round(($nbRetourGN/$nbGaGN)*100,0);}
if($nbGaVSD>0){$TauxVSD=round(($nbRetourVSD/$nbGaVSD)*100,0);}
if($nbGaTotal>0){$TauxTotal=round(($nbRetourTotal/$nbGaTotal)*100,0);}

$sheet->setCellValue('D'.$ligne,utf8_encode($TauxJ."%"));
$sheet->setCellValue('E'.$ligne,utf8_encode($TauxPN."%"));
$sheet->setCellValue('F'.$ligne,utf8_encode($TauxGN."%"));
$sheet->setCellValue('G'.$ligne,utf8_encode($TauxVSD."%"));
$sheet->setCellValue('H'.$ligne,utf8_encode($TauxTotal."%"));

$ligne++;

$sheet->setCellValue('A'.$ligne,utf8_encode("Types de retours "));
$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$ligne++;
if ($nbRETP>0){
	mysqli_data_seek($resultRetourProd,0);
	while($rowRETP=mysqli_fetch_array($resultRetourProd)){
			$sheet->setCellValue('A'.$ligne,utf8_encode($rowRETP['RetourPROD']));
			$sheet->mergeCells('A'.$ligne.':C'.$ligne);
			$nbJ=0;
			$nbPN=0;
			$nbGN=0;
			$nbVSD=0;
			$nbTotal=0;
			if ($nbResulta>0){
				mysqli_data_seek($result,0);
				while($row=mysqli_fetch_array($result)){
					if($row['Id_RetourPROD']==$rowRETP['Id_RetourPROD']){
						if($row['Vacation']=="J"){$nbJ++;}
						elseif($row['Vacation']=="S"){$nbPN++;}
						elseif($row['Vacation']=="N"){$nbGN++;}
						elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$nbVSD++;}
						$nbTotal++;
					}
				}
			}
			$sheet->setCellValue('D'.$ligne,utf8_encode($nbJ));
			$sheet->setCellValue('E'.$ligne,utf8_encode($nbPN));
			$sheet->setCellValue('F'.$ligne,utf8_encode($nbGN));
			$sheet->setCellValue('G'.$ligne,utf8_encode($nbVSD));
			$sheet->setCellValue('H'.$ligne,utf8_encode($nbTotal));
			$ligne++;
	}
}
$ligne--;
$sheet->getStyle('A1:H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$ligne++;

$req="SELECT sp_ficheintervention.Id, sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_ficheintervention.DateIntervention,sp_ficheintervention.Vacation,  ";
$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="sp_dossier.Priorite, sp_ficheintervention.CommentairePROD, sp_ficheintervention.CommentaireQUALITE,sp_dossier.PNE,sp_dossier.Fuel,sp_dossier.Elec,sp_dossier.Hydraulique,sp_dossier.Metal,sp_dossier.Structure,sp_dossier.Systeme,sp_dossier.Oxygene, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_ficheintervention.TravailRealise,sp_dossier.Priorite ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($pole==-1){$req.="WHERE sp_ficheintervention.Id_Pole IN (2,6) AND ";}
else{$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND ";}
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
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='N')) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJeudi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}
$req.="ORDER BY sp_ficheintervention.Vacation ASC, sp_dossier.MSN ASC, sp_ficheintervention.Id_StatutPROD ASC";

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(20);
$sheet->getColumnDimension('L')->setWidth(20);
$sheet->getColumnDimension('M')->setWidth(20);
$sheet->getColumnDimension('N')->setWidth(10);
$sheet->getColumnDimension('O')->setWidth(30);

$ligne++;
$ligne++;
$ligneJour=$ligne;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("JOUR"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->getStyle('D'.$ligneJour)->getAlignment()->setWrapText(true);
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut PROD"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("RETP"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Commentaire PROD"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Statut QUALITE"));
$sheet->setCellValue('J'.$ligneJour,utf8_encode("RETQ"));
$sheet->setCellValue('K'.$ligneJour,utf8_encode("Commentaire QUALITE"));
$sheet->setCellValue('L'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('M'.$ligneJour,utf8_encode("Vacation"));
$sheet->setCellValue('N'.$ligneJour,utf8_encode("PNE"));
$sheet->setCellValue('O'.$ligneJour,utf8_encode("Compétence"));
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$ligneJour++;

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
			$sheet->setCellValue('F'.$ligneJour,utf8_encode($row['Id_StatutPROD']));
			$sheet->setCellValue('G'.$ligneJour,utf8_encode($row['RetourPROD']));
			$sheet->setCellValue('H'.$ligneJour,utf8_encode($row['CommentairePROD']));
			$sheet->getStyle('H'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('I'.$ligneJour,utf8_encode($row['Id_StatutQUALITE']));
			$sheet->setCellValue('J'.$ligneJour,utf8_encode($row['RetourQUALITE']));
			$sheet->setCellValue('K'.$ligneJour,utf8_encode($row['CommentaireQUALITE']));
			$sheet->getStyle('K'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('L'.$ligneJour,utf8_encode($row['DateIntervention']));
			$sheet->setCellValue('M'.$ligneJour,utf8_encode($vacation));
			$PNE="";
			if($row['PNE']==1){$PNE="Oui";}
			$sheet->setCellValue('N'.$ligneJour,utf8_encode($PNE));
			
			$competence="";
			if($row['Elec']==1){$competence.="Elec ";}
			if($row['Fuel']==1){$competence.="Fuel ";}
			if($row['Hydraulique']==1){$competence.="Hydraulique ";}
			if($row['Metal']==1){$competence.="Metal ";}
			if($row['Oxygene']==1){$competence.="Oxygene ";}
			if($row['Structure']==1){$competence.="Structure ";}
			if($row['Systeme']==1){$competence.="Systeme ";}
			$sheet->setCellValue('O'.$ligneJour,utf8_encode($competence));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}
$ligneJour++;
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("SOIR"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->getStyle('D'.$ligneJour)->getAlignment()->setWrapText(true);
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut PROD"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("RETP"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Commentaire PROD"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Statut QUALITE"));
$sheet->setCellValue('J'.$ligneJour,utf8_encode("RETQ"));
$sheet->setCellValue('K'.$ligneJour,utf8_encode("Commentaire QUALITE"));
$sheet->setCellValue('L'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('M'.$ligneJour,utf8_encode("Vacation"));
$sheet->setCellValue('N'.$ligneJour,utf8_encode("PNE"));
$sheet->setCellValue('O'.$ligneJour,utf8_encode("Compétence"));
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$ligneJour++;

if ($nbResulta>0){	
	mysqli_data_seek($result,0);
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
			$sheet->setCellValue('F'.$ligneJour,utf8_encode($row['Id_StatutPROD']));
			$sheet->setCellValue('G'.$ligneJour,utf8_encode($row['RetourPROD']));
			$sheet->setCellValue('H'.$ligneJour,utf8_encode($row['CommentairePROD']));
			$sheet->getStyle('H'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('I'.$ligneJour,utf8_encode($row['Id_StatutQUALITE']));
			$sheet->setCellValue('J'.$ligneJour,utf8_encode($row['RetourQUALITE']));
			$sheet->setCellValue('K'.$ligneJour,utf8_encode($row['CommentaireQUALITE']));
			$sheet->getStyle('K'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('L'.$ligneJour,utf8_encode($row['DateIntervention']));
			$sheet->setCellValue('M'.$ligneJour,utf8_encode($vacation));
			$PNE="";
			if($row['PNE']==1){$PNE="Oui";}
			$sheet->setCellValue('N'.$ligneJour,utf8_encode($PNE));
			
			$competence="";
			if($row['Elec']==1){$competence.="Elec ";}
			if($row['Fuel']==1){$competence.="Fuel ";}
			if($row['Hydraulique']==1){$competence.="Hydraulique ";}
			if($row['Metal']==1){$competence.="Metal ";}
			if($row['Oxygene']==1){$competence.="Oxygene ";}
			if($row['Structure']==1){$competence.="Structure ";}
			if($row['Systeme']==1){$competence.="Systeme ";}
			$sheet->setCellValue('O'.$ligneJour,utf8_encode($competence));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}

$ligneJour++;
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("NUIT"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->getStyle('D'.$ligneJour)->getAlignment()->setWrapText(true);
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut PROD"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("RETP"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Commentaire PROD"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Statut QUALITE"));
$sheet->setCellValue('J'.$ligneJour,utf8_encode("RETQ"));
$sheet->setCellValue('K'.$ligneJour,utf8_encode("Commentaire QUALITE"));
$sheet->setCellValue('L'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('M'.$ligneJour,utf8_encode("Vacation"));
$sheet->setCellValue('N'.$ligneJour,utf8_encode("PNE"));
$sheet->setCellValue('O'.$ligneJour,utf8_encode("Compétence"));
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$ligneJour++;
if ($nbResulta>0){	
	mysqli_data_seek($result,0);
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
			$sheet->setCellValue('F'.$ligneJour,utf8_encode($row['Id_StatutPROD']));
			$sheet->setCellValue('G'.$ligneJour,utf8_encode($row['RetourPROD']));
			$sheet->setCellValue('H'.$ligneJour,utf8_encode($row['CommentairePROD']));
			$sheet->getStyle('H'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('I'.$ligneJour,utf8_encode($row['Id_StatutQUALITE']));
			$sheet->setCellValue('J'.$ligneJour,utf8_encode($row['RetourQUALITE']));
			$sheet->setCellValue('K'.$ligneJour,utf8_encode($row['CommentaireQUALITE']));
			$sheet->getStyle('K'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('L'.$ligneJour,utf8_encode($row['DateIntervention']));
			$sheet->setCellValue('M'.$ligneJour,utf8_encode($vacation));
			$PNE="";
			if($row['PNE']==1){$PNE="Oui";}
			$sheet->setCellValue('N'.$ligneJour,utf8_encode($PNE));
			
			$competence="";
			if($row['Elec']==1){$competence.="Elec ";}
			if($row['Fuel']==1){$competence.="Fuel ";}
			if($row['Hydraulique']==1){$competence.="Hydraulique ";}
			if($row['Metal']==1){$competence.="Metal ";}
			if($row['Oxygene']==1){$competence.="Oxygene ";}
			if($row['Structure']==1){$competence.="Structure ";}
			if($row['Systeme']==1){$competence.="Systeme ";}
			$sheet->setCellValue('O'.$ligneJour,utf8_encode($competence));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}
$ligneJour++;
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("VSD"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->getStyle('D'.$ligneJour)->getAlignment()->setWrapText(true);
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut PROD"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("RETP"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Commentaire PROD"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Statut QUALITE"));
$sheet->setCellValue('J'.$ligneJour,utf8_encode("RETQ"));
$sheet->setCellValue('K'.$ligneJour,utf8_encode("Commentaire QUALITE"));
$sheet->setCellValue('L'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('M'.$ligneJour,utf8_encode("Vacation"));
$sheet->setCellValue('N'.$ligneJour,utf8_encode("PNE"));
$sheet->setCellValue('O'.$ligneJour,utf8_encode("Compétence"));
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$ligneJour++;
if ($nbResulta>0){	
	mysqli_data_seek($result,0);
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
			$sheet->setCellValue('F'.$ligneJour,utf8_encode($row['Id_StatutPROD']));
			$sheet->setCellValue('G'.$ligneJour,utf8_encode($row['RetourPROD']));
			$sheet->setCellValue('H'.$ligneJour,utf8_encode($row['CommentairePROD']));
			$sheet->getStyle('H'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('I'.$ligneJour,utf8_encode($row['Id_StatutQUALITE']));
			$sheet->setCellValue('J'.$ligneJour,utf8_encode($row['RetourQUALITE']));
			$sheet->setCellValue('K'.$ligneJour,utf8_encode($row['CommentaireQUALITE']));
			$sheet->getStyle('K'.$ligneJour)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('L'.$ligneJour,utf8_encode($row['DateIntervention']));
			$sheet->setCellValue('M'.$ligneJour,utf8_encode($vacation));
			$PNE="";
			if($row['PNE']==1){$PNE="Oui";}
			$sheet->setCellValue('N'.$ligneJour,utf8_encode($PNE));
			
			$competence="";
			if($row['Elec']==1){$competence.="Elec ";}
			if($row['Fuel']==1){$competence.="Fuel ";}
			if($row['Hydraulique']==1){$competence.="Hydraulique ";}
			if($row['Metal']==1){$competence.="Metal ";}
			if($row['Oxygene']==1){$competence.="Oxygene ";}
			if($row['Structure']==1){$competence.="Structure ";}
			if($row['Systeme']==1){$competence.="Systeme ";}
			$sheet->setCellValue('O'.$ligneJour,utf8_encode($competence));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
				
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':O'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_CompteRendu.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_CompteRendu.xlsx';
$writer->save($chemin);
readfile($chemin);
 ?>