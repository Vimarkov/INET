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
$sheet->setTitle('PLANNIFICATION');

//orientation paysage
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//format de page
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
$sheet->getPageSetup()->setFitToHeight(10);

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
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+8-$NumJour, $tabDate[0]);
$leLundi= date("Y-m-d", $timestamp);	

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


$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_ficheintervention.DateIntervention,sp_ficheintervention.Vacation, ";
$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="sp_dossier.Priorite,sp_ficheintervention.Vacation,sp_dossier.PNE,sp_dossier.Fuel,sp_dossier.Elec,sp_dossier.Hydraulique,sp_dossier.Metal,sp_dossier.Structure,sp_dossier.Systeme,sp_dossier.Oxygene, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($pole==-1){$req.="WHERE sp_ficheintervention.Id_Pole IN (2,6) AND  ";}
else{$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND  ";}
if($NumJour<=4){
	$req.="((sp_ficheintervention.DateIntervention='".$leLendemain."' AND (sp_ficheintervention.Vacation='N' OR sp_ficheintervention.Vacation='J')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='S')) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leLundi."' AND (sp_ficheintervention.Vacation='N' OR sp_ficheintervention.Vacation='J')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Nuit'))) ";
}
$req.="ORDER BY sp_dossier.MSN ASC,sp_dossier.Priorite DESC";
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
$sheet->getColumnDimension('J')->setWidth(8);
$sheet->getColumnDimension('K')->setWidth(30);

$ligneJour=1;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("SOIR"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->getStyle('D'.$ligneJour)->getAlignment()->setWrapText(true);
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("Retour"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Vacation"));
$sheet->setCellValue('J'.$ligneJour,utf8_encode("PNE"));
$sheet->setCellValue('K'.$ligneJour,utf8_encode("Compétence"));

$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
			$PNE="";
			if($row['PNE']==1){$PNE="Oui";}
			$sheet->setCellValue('J'.$ligneJour,utf8_encode($PNE));
			
			$competence="";
			if($row['Elec']==1){$competence.="Elec ";}
			if($row['Fuel']==1){$competence.="Fuel ";}
			if($row['Hydraulique']==1){$competence.="Hydraulique ";}
			if($row['Metal']==1){$competence.="Metal ";}
			if($row['Oxygene']==1){$competence.="Oxygene ";}
			if($row['Structure']==1){$competence.="Structure ";}
			if($row['Systeme']==1){$competence.="Systeme ";}
			$sheet->setCellValue('K'.$ligneJour,utf8_encode($competence));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("Retour"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Vacation"));
$sheet->setCellValue('J'.$ligneJour,utf8_encode("PNE"));
$sheet->setCellValue('K'.$ligneJour,utf8_encode("Compétence"));
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
			$PNE="";
			if($row['PNE']==1){$PNE="Oui";}
			$sheet->setCellValue('J'.$ligneJour,utf8_encode($PNE));
			
			$competence="";
			if($row['Elec']==1){$competence.="Elec ";}
			if($row['Fuel']==1){$competence.="Fuel ";}
			if($row['Hydraulique']==1){$competence.="Hydraulique ";}
			if($row['Metal']==1){$competence.="Metal ";}
			if($row['Oxygene']==1){$competence.="Oxygene ";}
			if($row['Structure']==1){$competence.="Structure ";}
			if($row['Systeme']==1){$competence.="Systeme ";}
			$sheet->setCellValue('K'.$ligneJour,utf8_encode($competence));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}
$ligneJour++;
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("JOUR"));
$ligneJour++;
$sheet->setCellValue('A'.$ligneJour,utf8_encode("MSN"));
$sheet->setCellValue('B'.$ligneJour,utf8_encode("OF"));
$sheet->setCellValue('C'.$ligneJour,utf8_encode("Titre"));
$sheet->setCellValue('D'.$ligneJour,utf8_encode("Zone Aircraft"));
$sheet->getStyle('D'.$ligneJour)->getAlignment()->setWrapText(true);
$sheet->setCellValue('E'.$ligneJour,utf8_encode("Priorité"));
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("Retour"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Vacation"));
$sheet->setCellValue('J'.$ligneJour,utf8_encode("PNE"));
$sheet->setCellValue('K'.$ligneJour,utf8_encode("Compétence"));
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
			$PNE="";
			if($row['PNE']==1){$PNE="Oui";}
			$sheet->setCellValue('J'.$ligneJour,utf8_encode($PNE));
			
			$competence="";
			if($row['Elec']==1){$competence.="Elec ";}
			if($row['Fuel']==1){$competence.="Fuel ";}
			if($row['Hydraulique']==1){$competence.="Hydraulique ";}
			if($row['Metal']==1){$competence.="Metal ";}
			if($row['Oxygene']==1){$competence.="Oxygene ";}
			if($row['Structure']==1){$competence.="Structure ";}
			if($row['Systeme']==1){$competence.="Systeme ";}
			$sheet->setCellValue('K'.$ligneJour,utf8_encode($competence));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
$sheet->setCellValue('F'.$ligneJour,utf8_encode("Statut"));
$sheet->setCellValue('G'.$ligneJour,utf8_encode("Retour"));
$sheet->setCellValue('H'.$ligneJour,utf8_encode("Date"));
$sheet->setCellValue('I'.$ligneJour,utf8_encode("Vacation"));
$sheet->setCellValue('J'.$ligneJour,utf8_encode("PNE"));
$sheet->setCellValue('K'.$ligneJour,utf8_encode("Compétence"));
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));

$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
			$PNE="";
			if($row['PNE']==1){$PNE="Oui";}
			$sheet->setCellValue('J'.$ligneJour,utf8_encode($PNE));
			
			$competence="";
			if($row['Elec']==1){$competence.="Elec ";}
			if($row['Fuel']==1){$competence.="Fuel ";}
			if($row['Hydraulique']==1){$competence.="Hydraulique ";}
			if($row['Metal']==1){$competence.="Metal ";}
			if($row['Oxygene']==1){$competence.="Oxygene ";}
			if($row['Structure']==1){$competence.="Structure ";}
			if($row['Systeme']==1){$competence.="Systeme ";}
			$sheet->setCellValue('K'.$ligneJour,utf8_encode($competence));
			
			if($row['Id_StatutQUALITE']=="CERT"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
			}
			elseif($row['Id_StatutQUALITE']=="TVS" || $row['Id_StatutPROD']=="TFS"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f6b132'))));
			}
			elseif($row['Id_StatutPROD']=="QARJ"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f9fb3f'))));
			}
			elseif($row['Id_StatutPROD']=="REWORK"){
				$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'41f9e9'))));
			}
			
			$sheet->getStyle('E'.$ligneJour)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurPrio))));
			
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligneJour.':K'.$ligneJour)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			$ligneJour++;
		}
	}
}


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Plannification.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Plannification.xlsx';
$writer->save($chemin);
readfile($chemin);
 ?>