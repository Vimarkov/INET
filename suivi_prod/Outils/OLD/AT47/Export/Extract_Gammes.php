<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;
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
function AfficheDateFR($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
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
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("d/m/Y", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

$req2="SELECT Id,MSN,OrdreMontage,Designation,Id_StatutPROD,Id_StatutQUALITE,";
$req2.="IF((SELECT COUNT(Id) FROM sp_atram WHERE sp_atram.OMAssocie=sp_atrot.OrdreMontage)>0,'Oui','') AS AMAssociee, ";
$req2.="(SELECT sp_atrarticle.Ligne FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS Ligne, ";
$req2.="(SELECT sp_atrarticle.Poste45 FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS Poste45, ";
$req2.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardPROD) AS CauseP, ";
$req2.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardQUALITE) AS CauseQ ";
$req2.="FROM sp_atrot ";
$req2.="WHERE sp_atrot.Id_Prestation=262 AND sp_atrot.Supprime=0 AND MSN=".$_GET['MSN']." ";
$req2.="ORDER BY Ligne,OrdreMontage";
$result2=mysqli_query($bdd,$req2);
$nbResulta2=mysqli_num_rows($result2);

$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Extract facturation");

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("Ordre de montage"));
$sheet->setCellValue('C1',utf8_encode("Désignation"));
$sheet->setCellValue('D1',utf8_encode("Ligne"));
$sheet->setCellValue('E1',utf8_encode("Poste 45"));
$sheet->setCellValue('F1',utf8_encode("AM associée"));
$sheet->setCellValue('G1',utf8_encode("Statut PROD"));
$sheet->setCellValue('H1',utf8_encode("Information statut production"));	
$sheet->setCellValue('I1',utf8_encode("Statut QUALITE"));
$sheet->setCellValue('J1',utf8_encode("Information statut qualité"));	

$sheet->getStyle('A1:J1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:J1')->getFont()->setBold(true);
$sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(18);
$sheet->getColumnDimension('C')->setWidth(35);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(10);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(25);

$ligne=2;

$nbGamme=0;
$nbTERC=0;
$nbNonTercAAA=0;
$nbTFSAMAAA=0;
$nbTFSAMEXT=0;
$nbTFSP45=0;
$nbTFSLND=0;
$nbEnCours=0;
$nbTVSAMAAA=0;
$nbTVSAMEXT=0;
$nbTVSP45=0;
$nbTVSLND=0;
while($row2=mysqli_fetch_array($result2)){
	$Ligne="?";
	if($row2['Ligne']<>""){$Ligne=$row2['Ligne'];}
	
	if($row2['Poste45']==1){$Poste45="Oui";}
	elseif($row2['Poste45']==null){$Poste45="?";}
	elseif($row2['Poste45']==0){$Poste45="Non";}
	
	switch($row2['Ligne']){
		case "1" : $couleur="92d050";break;
		case "2" : $couleur="00b0f0";break;
		case "3" : $couleur="f79545";break;
		case "4" : $couleur="c04f4c";break;
		case "5" : $couleur="ccc0da";break;
		case "6" : $couleur="538cd5";break;
		case "7" : $couleur="ffff00";break;
		case "MOU/DEMOUL" : $couleur="92d050";break;
		default : $couleur="ffffff";break;
		
	}
	if($row2['Id_StatutQUALITE']=="TERC"){
		$nbTERC++;
	}
	if($row2['Id_StatutPROD']<>"Pas de responsabilité AAA"){
		$nbGamme++;
	}
	if($row2['CauseP']==""){
		if($row2['Id_StatutPROD']=="En cours" || $row2['Id_StatutPROD']=="En cours"){
			$nbEnCours++;
		}
	}
	if($row2['Id_StatutPROD']=="TFS" && $row2['Id_StatutQUALITE']=="TVS"){
		if($row2['CauseP']=="AM extérieure"){
			$nbNonTercAAA++;
		}
	}
	if($row2['Id_StatutPROD']=="TFS"){
		if($row2['CauseP']=="AM AAA"){
			$nbTFSAMAAA++;
		}
		elseif($row2['CauseP']=="AM extérieure"){
			$nbTFSAMEXT++;
		}
		elseif($row2['CauseP']=="P45"){
			$nbTFSP45++;
		}
		elseif($row2['CauseP']=="LND"){
			$nbTFSLND++;
		}
	}
	if($row2['Id_StatutQUALITE']=="TVS"){
		if($row2['CauseQ']=="AM AAA"){
			$nbTVSAMAAA++;
		}
		elseif($row2['CauseQ']=="AM extérieure"){
			$nbTVSAMEXT++;
		}
		elseif($row2['CauseQ']=="P45"){
			$nbTVSP45++;
		}
		elseif($row2['CauseQ']=="LND"){
			$nbTVSLND++;
		}
	}
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['OrdreMontage']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['Designation']));
	$sheet->setCellValue('D'.$ligne,utf8_encode($Ligne));
	$sheet->setCellValue('E'.$ligne,utf8_encode($Poste45));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row2['AMAssociee']));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['Id_StatutPROD']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($row2['CauseP']));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Id_StatutQUALITE']));
	$sheet->setCellValue('J'.$ligne,utf8_encode($row2['CauseQ']));
	$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));	
	if($couleur<>"ffffff"){
		$sheet->getStyle('A'.$ligne.':J'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
	}
	$ligne++;
}

//ONGLET 2
$sheet = $workbook->createSheet();
$sheet->setTitle("AM-NC majeures");

$req2="SELECT Id,MSN,ImputationAAA,NCMajeure,OMAssocie,Id_Type,Recurrence,DateCreation,NumAMNC, ";
$req2.="(SELECT Designation FROM sp_atrot WHERE sp_atrot.OrdreMontage=sp_atram.OMAssocie) AS Designation, ";
$req2.="(SELECT Libelle FROM sp_atrtype WHERE sp_atrtype.Id=sp_atram.Id_Type) AS Type ";
$req2.="FROM sp_atram WHERE Id_Prestation=262 AND MSN=".$_GET['MSN']." ";
$req2.="ORDER BY OMAssocie ";

$result2=mysqli_query($bdd,$req2);
$nbResulta2=mysqli_num_rows($result2);
$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° AM/NC"));
$sheet->setCellValue('C1',utf8_encode("Ordre de montage associé"));
$sheet->setCellValue('D1',utf8_encode("Désignation"));
$sheet->setCellValue('E1',utf8_encode("Imputation AAA"));
$sheet->setCellValue('F1',utf8_encode("NC majeure"));
$sheet->setCellValue('G1',utf8_encode("Type"));
$sheet->setCellValue('H1',utf8_encode("Récurrence"));		

$sheet->getStyle('A1:H1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:H1')->getFont()->setBold(true);
$sheet->getStyle('A1:H1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(18);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(30);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);

$ligne=2;
$nbAMNCAAA=0;
$nbAMMajeure=0;
$nbCheminement=0;
$nbDegradation=0;
$nbFixation=0;
$nbPerte=0;
$nbProprete=0;
while($row2=mysqli_fetch_array($result2)){

	if($row2['ImputationAAA']==1){$imputation= "Oui";$nbAMNCAAA++;}else{$imputation= "Non";}
	if($row2['NCMajeure']==1){$ncmajeure= "Oui";$nbAMMajeure++;}else{$ncmajeure= "Non";}
	if($row2['Recurrence']==1){$recurrence= "Oui";}else{$recurrence= "Non";}
	
	switch($row2['Type']){
		case "Cheminement":
			$nbCheminement++;
			break;
		case "Dégradation" :
			$nbDegradation++;
			break;
		case "Fixation" :
			$nbFixation++;
			break;
		case "Perte" :
			$nbPerte++;
			break;
		Case "Propreté" :
			$nbProprete++;
			break;
	}
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['NumAMNC']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['OMAssocie']));
	$sheet->setCellValue('D'.$ligne,utf8_encode($row2['Designation']));
	$sheet->setCellValue('E'.$ligne,utf8_encode($imputation));
	$sheet->setCellValue('F'.$ligne,utf8_encode($ncmajeure));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['Type']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($recurrence));

	$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));	
	$ligne++;
}

//ONGLET 3
$sheet = $workbook->createSheet();
$sheet->setTitle("Points CQLB");

$req2="SELECT Id,MSN,NumCQLB,NumCV,ImputationAAA,OMAssocie,AMAssociee,Id_Type,Recurrence,DateCreation, ";
$req2.="(SELECT Designation FROM sp_atrot WHERE sp_atrot.OrdreMontage=sp_atrcqlb.OMAssocie) AS Designation, ";
$req2.="(SELECT Libelle FROM sp_atrlocalisation WHERE sp_atrlocalisation.Id=sp_atrcqlb.Id_Localisation) AS Localisation, ";
$req2.="(SELECT Libelle FROM sp_atrtype WHERE sp_atrtype.Id=sp_atrcqlb.Id_Type) AS Type ";
$req2.="FROM sp_atrcqlb WHERE Id_Prestation=262 AND MSN=".$_GET['MSN']." ";
$req2.="ORDER BY NumCQLB ";

$result2=mysqli_query($bdd,$req2);
$nbResulta2=mysqli_num_rows($result2);

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° CQLB"));
$sheet->setCellValue('C1',utf8_encode("N° CV"));
$sheet->setCellValue('D1',utf8_encode("Localisation"));
$sheet->setCellValue('E1',utf8_encode("Imputation AAA"));
$sheet->setCellValue('F1',utf8_encode("Ordre de montage associé"));
$sheet->setCellValue('G1',utf8_encode("Désignation"));
$sheet->setCellValue('H1',utf8_encode("AM associée"));		
$sheet->setCellValue('I1',utf8_encode("Type"));
$sheet->setCellValue('J1',utf8_encode("Récurrence"));

$sheet->getStyle('A1:J1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:J1')->getFont()->setBold(true);
$sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(30);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(15);

$ligne=2;
$nbCQLBAAA=0;
while($row2=mysqli_fetch_array($result2)){

	if($row2['ImputationAAA']==1){$imputation= "Oui";$nbCQLBAAA++;}else{$imputation= "Non";}
	if($row2['Recurrence']==1){$recurrence= "Oui";}else{$recurrence= "Non";}
	
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['NumCQLB']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['NumCV']));
	$sheet->setCellValue('D'.$ligne,utf8_encode($row2['Localisation']));
	$sheet->setCellValue('E'.$ligne,utf8_encode($imputation));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row2['OMAssocie']));
	$sheet->setCellValue('G'.$ligne,utf8_encode($row2['Designation']));
	$sheet->setCellValue('H'.$ligne,utf8_encode($row2['AMAssociee']));
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Type']));
	$sheet->setCellValue('J'.$ligne,utf8_encode($recurrence));

	$sheet->getStyle('A'.$ligne.':J'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));	
	$ligne++;
}

//ONGLET 4
$sheet = $workbook->createSheet();
$sheet->setTitle(utf8_encode("Données pour KPI"));

$sheet->getColumnDimension('A')->setWidth(40);
$sheet->getColumnDimension('B')->setWidth(40);

$sheet->setCellValue('A1',utf8_encode("N° MSN"));
$sheet->setCellValue('A2',utf8_encode("Date moulage"));
$sheet->setCellValue('A3',utf8_encode("Date démoulage"));
$sheet->setCellValue('A4',utf8_encode("Commentaire"));

$sheet->setCellValue('B1',utf8_encode($_GET['MSN']));
$result=mysqli_query($bdd,"SELECT DateMoulage,DateDemoulage,HeureMoulage,HeureDemoulage, Commentaire FROM sp_atrmsn WHERE MSN=".$_GET['MSN']);
$nbResulta=mysqli_num_rows($result);
if($nbResulta>0){
$Ligne=mysqli_fetch_array($result);
	$sheet->setCellValue('B2',utf8_encode(AfficheDateFR($Ligne['DateMoulage'])." ".$Ligne['HeureMoulage']));
	$sheet->setCellValue('B3',utf8_encode(AfficheDateFR($Ligne['DateDemoulage'])." ".$Ligne['HeureDemoulage']));
	$sheet->setCellValue('B4',utf8_encode($Ligne['Commentaire']));
}
$sheet->getStyle('A1:B4')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));	

$sheet->setCellValue('A6',utf8_encode("OTD"));
$sheet->mergeCells('A6:B6');
$sheet->getStyle('A6:B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->setCellValue('A7',utf8_encode("Nombre de gamme de responsabilité AAA"));
$sheet->setCellValue('A8',utf8_encode("Nombre de TERC + TVS P45"));
$sheet->setCellValue('A9',utf8_encode("Dossier non TERC imputation AAA"));
$sheet->setCellValue('A10',utf8_encode("Dossier TFS AM AAA"));
$sheet->setCellValue('A11',utf8_encode("Dossier TFS AM ext"));
$sheet->setCellValue('A12',utf8_encode("Dossier TFS P45"));
$sheet->setCellValue('A13',utf8_encode("Dossier En cours"));
$sheet->setCellValue('A14',utf8_encode("Dossier TVS AM AAA"));
$sheet->setCellValue('A15',utf8_encode("Dossier TVS AM ext"));
$sheet->setCellValue('A16',utf8_encode("Dossier  TVS LND"));

$sheet->setCellValue('B7',utf8_encode($nbGamme));
$sheet->setCellValue('B8',utf8_encode($nbTERC+$nbTVSP45));
$sheet->setCellValue('B9',utf8_encode($nbTFSAMAAA+$nbEnCours+$nbTVSAMAAA));
$sheet->setCellValue('B10',utf8_encode($nbTFSAMAAA));
$sheet->setCellValue('B11',utf8_encode($nbTFSAMEXT));
$sheet->setCellValue('B12',utf8_encode($nbTFSP45));
$sheet->setCellValue('B13',utf8_encode($nbEnCours));
$sheet->setCellValue('B14',utf8_encode($nbTVSAMAAA));
$sheet->setCellValue('B15',utf8_encode($nbTVSAMEXT));
$sheet->setCellValue('B16',utf8_encode($nbTVSLND));

$sheet->getStyle('A6:B16')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));	

$sheet->setCellValue('A20',utf8_encode("OQD"));
$sheet->mergeCells('A20:B20');
$sheet->getStyle('A20:B20')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->setCellValue('A21',utf8_encode("AM/NC d'imputation AAA"));
$sheet->setCellValue('A22',utf8_encode("CQLB d'imputation AAA"));
$sheet->setCellValue('A23',utf8_encode("Anomalie majeur d'imputation AAA"));
$sheet->setCellValue('A24',utf8_encode("Cheminement"));
$sheet->setCellValue('A25',utf8_encode("Dégradation"));
$sheet->setCellValue('A26',utf8_encode("Fixation"));
$sheet->setCellValue('A27',utf8_encode("Perte"));
$sheet->setCellValue('A28',utf8_encode("Propreté"));

$sheet->setCellValue('B21',utf8_encode($nbAMNCAAA));
$sheet->setCellValue('B22',utf8_encode($nbCQLBAAA));
$sheet->setCellValue('B23',utf8_encode($nbAMMajeure));
$sheet->setCellValue('B24',utf8_encode($nbCheminement));
$sheet->setCellValue('B25',utf8_encode($nbDegradation));
$sheet->setCellValue('B26',utf8_encode($nbFixation));
$sheet->setCellValue('B27',utf8_encode($nbPerte));
$sheet->setCellValue('B28',utf8_encode($nbProprete));
$sheet->getStyle('A20:B28')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));	


//ONGLET 5
$sheet = $workbook->createSheet();
$sheet->setTitle(utf8_encode("Satisfaction client"));

$sheet->setCellValue('A1',utf8_encode("DATE"));
$sheet->setCellValue('B1',utf8_encode("POINT"));
$sheet->setCellValue('C1',utf8_encode("Très insuffisant"));
$sheet->setCellValue('D1',utf8_encode("Insuffisant"));
$sheet->setCellValue('E1',utf8_encode("Satisfaisant"));
$sheet->setCellValue('F1',utf8_encode("Très safisfaisant"));
$sheet->setCellValue('G1',utf8_encode("CAUSE NON SATISFACTION"));

$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:G1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(40);

$sheet->setCellValue('A2',utf8_encode("SOUTE AVANT"));
$sheet->mergeCells('A2:G2');
$sheet->getStyle('A2:G2')->getFont()->setBold(true);
$sheet->getStyle('C7:F7')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fabf8f'))));
for($i='C';$i<>'G';$i++){
	$sheet->setCellValue($i.'7',"=COUNTA(".$i."3:".$i."6)", PHPExcel_Cell_DataType::TYPE_FORMULA);
}

$sheet->setCellValue('A8',utf8_encode("SOUS PLANCHERS COCKPIT"));
$sheet->mergeCells('A8:G8');
$sheet->getStyle('A8:G8')->getFont()->setBold(true);
$sheet->getStyle('C13:F13')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fabf8f'))));
for($i='C';$i<>'G';$i++){
	$sheet->setCellValue($i.'13',"=COUNTA(".$i."9:".$i."12)", PHPExcel_Cell_DataType::TYPE_FORMULA);
}

$sheet->setCellValue('A14',utf8_encode("ZONE 9-11VU"));
$sheet->mergeCells('A14:G14');
$sheet->getStyle('A14:G14')->getFont()->setBold(true);
$sheet->getStyle('C19:F19')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fabf8f'))));
for($i='C';$i<>'G';$i++){
	$sheet->setCellValue($i.'19',"=COUNTA(".$i."15:".$i."18)", PHPExcel_Cell_DataType::TYPE_FORMULA);
}

$sheet->setCellValue('A20',utf8_encode("ZONE 120VU"));
$sheet->mergeCells('A20:G20');
$sheet->getStyle('A20:G20')->getFont()->setBold(true);
$sheet->getStyle('C25:F25')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fabf8f'))));
for($i='C';$i<>'G';$i++){
	$sheet->setCellValue($i.'25',"=COUNTA(".$i."21:".$i."24)", PHPExcel_Cell_DataType::TYPE_FORMULA);
}

$sheet->setCellValue('A26',utf8_encode("ZONE 20VU"));
$sheet->mergeCells('A26:G26');
$sheet->getStyle('A26:G26')->getFont()->setBold(true);
$sheet->getStyle('C31:F31')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fabf8f'))));
for($i='C';$i<>'G';$i++){
	$sheet->setCellValue($i.'31',"=COUNTA(".$i."27:".$i."30)", PHPExcel_Cell_DataType::TYPE_FORMULA);
}

$sheet->setCellValue('A32',utf8_encode("GLOBAL"));
$sheet->mergeCells('A32:G32');
$sheet->getStyle('A32:G32')->getFont()->setBold(true);
$sheet->getStyle('C33:F33')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'fabf8f'))));
$sheet->getStyle('C34:F34')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e6b8b7'))));
for($i='C';$i<>'G';$i++){
	$sheet->setCellValue($i.'33',"=".$i."7+".$i."13+".$i."19+".$i."25+".$i."31", PHPExcel_Cell_DataType::TYPE_FORMULA);
}

$sheet->setCellValue('C34',"=IFERROR(C33/SUM(C33:F33),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
$sheet->setCellValue('D34',"=IFERROR(D33/SUM(C33:F33),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
$sheet->setCellValue('E34',"=IFERROR(E33/SUM(C33:F33),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
$sheet->setCellValue('F34',"=IFERROR(F33/SUM(C33:F33),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);

$sheet->getStyle('A2:G33')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G34')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:G34')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$sheet->getStyle('C34:F34')->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE));
	
$sheet->getStyle('C34:F34')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

$j=1;
for($i=3;$i<=30;$i++){
	if($j>4){$j=1;$i=$i+2;}
	$sheet->setCellValue('B'.$i,utf8_encode("POINT ".$j));
	$j++;
}
$id=0;
$req="SELECT Id FROM sp_atrmsn WHERE MSN=".$_GET['MSN'];
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
if($nbResulta>0){
	$row=mysqli_fetch_array($result);
	$id=$row['Id'];
}

if($id>0){
	$req="SELECT Id_Visite, Presentation,Zone,Support,Quality,Commentaire1,Commentaire2,Commentaire3,Commentaire4,DateVisite ";
	$req.="FROM sp_atrmsn_customer WHERE Id_MSN=".$id;
	$resultVisite=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($resultVisite);
	if ($nbResulta>0){
		while($rowVisite=mysqli_fetch_array($resultVisite)){
			$i=0;
			if($rowVisite['Id_Visite']==3){$i=3;}
			elseif($rowVisite['Id_Visite']==1){$i=9;}
			elseif($rowVisite['Id_Visite']==5){$i=15;}
			if($rowVisite['Id_Visite']==2){$i=21;}
			if($rowVisite['Id_Visite']==4){$i=27;}
			if($i>0){
				$sheet->setCellValue('A'.$i,utf8_encode(AfficheDateFR($rowVisite['DateVisite'])));
				$sheet->setCellValue('A'.($i+1),utf8_encode(AfficheDateFR($rowVisite['DateVisite'])));
				$sheet->setCellValue('A'.($i+2),utf8_encode(AfficheDateFR($rowVisite['DateVisite'])));
				$sheet->setCellValue('A'.($i+3),utf8_encode(AfficheDateFR($rowVisite['DateVisite'])));
				
				if($rowVisite['Presentation']=="Dissatisfied"){$sheet->setCellValue('C'.$i,utf8_encode("X"));}
				elseif($rowVisite['Presentation']=="Somehow Dissatisfied"){$sheet->setCellValue('D'.$i,utf8_encode("X"));}
				elseif($rowVisite['Presentation']=="Satisfied"){$sheet->setCellValue('E'.$i,utf8_encode("X"));}
				elseif($rowVisite['Presentation']=="Totally Satisfied"){$sheet->setCellValue('F'.$i,utf8_encode("X"));}
				
				if($rowVisite['Zone']=="Dissatisfied"){$sheet->setCellValue('C'.($i+1),utf8_encode("X"));}
				elseif($rowVisite['Zone']=="Somehow Dissatisfied"){$sheet->setCellValue('D'.($i+1),utf8_encode("X"));}
				elseif($rowVisite['Zone']=="Satisfied"){$sheet->setCellValue('E'.($i+1),utf8_encode("X"));}
				elseif($rowVisite['Zone']=="Totally Satisfied"){$sheet->setCellValue('F'.($i+1),utf8_encode("X"));}
				
				if($rowVisite['Support']=="Dissatisfied"){$sheet->setCellValue('C'.($i+2),utf8_encode("X"));}
				elseif($rowVisite['Support']=="Somehow Dissatisfied"){$sheet->setCellValue('D'.($i+2),utf8_encode("X"));}
				elseif($rowVisite['Support']=="Satisfied"){$sheet->setCellValue('E'.($i+2),utf8_encode("X"));}
				elseif($rowVisite['Support']=="Totally Satisfied"){$sheet->setCellValue('F'.($i+2),utf8_encode("X"));}
				
				if($rowVisite['Quality']=="Dissatisfied"){$sheet->setCellValue('C'.($i+3),utf8_encode("X"));}
				elseif($rowVisite['Quality']=="Somehow Dissatisfied"){$sheet->setCellValue('D'.($i+3),utf8_encode("X"));}
				elseif($rowVisite['Quality']=="Satisfied"){$sheet->setCellValue('E'.($i+3),utf8_encode("X"));}
				elseif($rowVisite['Quality']=="Totally Satisfied"){$sheet->setCellValue('F'.($i+3),utf8_encode("X"));}
				
				$sheet->setCellValue('G'.$i,utf8_encode(stripslashes($rowVisite['Commentaire1'])));
				$sheet->setCellValue('G'.($i+1),utf8_encode(stripslashes($rowVisite['Commentaire2'])));
				$sheet->setCellValue('G'.($i+2),utf8_encode(stripslashes($rowVisite['Commentaire3'])));
				$sheet->setCellValue('G'.($i+3),utf8_encode(stripslashes($rowVisite['Commentaire4'])));
			}
		}
	}
}

$i=30;

for($k=1;$k<=4;$k++){
	$i=$i+6;
	$sheet->setCellValue('A'.($i+$k),utf8_encode('LISTE '.$k));
	$sheet->setCellValue('A'.($i+$k+1),utf8_encode('Moyenne de points '.$k.' avec retour très insuffisant'));
	$sheet->setCellValue('A'.($i+$k+2),utf8_encode('Moyenne de points '.$k.' avec retour insuffisant'));
	$sheet->setCellValue('A'.($i+$k+3),utf8_encode('Moyenne de points '.$k.' avec retour satisfaisant'));
	$sheet->setCellValue('A'.($i+$k+4),utf8_encode('Moyenne de points '.$k.' avec retour très satisfaisant'));
	$sheet->mergeCells('A'.($i+$k).':F'.($i+$k));
	$sheet->mergeCells('A'.($i+$k+1).':C'.($i+$k+1));
	$sheet->mergeCells('A'.($i+$k+2).':C'.($i+$k+2));
	$sheet->mergeCells('A'.($i+$k+3).':C'.($i+$k+3));
	$sheet->mergeCells('A'.($i+$k+4).':C'.($i+$k+4));
	$sheet->mergeCells('D'.($i+$k+1).':F'.($i+$k+1));
	$sheet->mergeCells('D'.($i+$k+2).':F'.($i+$k+2));
	$sheet->mergeCells('D'.($i+$k+3).':F'.($i+$k+3));
	$sheet->mergeCells('D'.($i+$k+4).':F'.($i+$k+4));
	
	$sheet->setCellValue('D'.($i+$k+1),"=IFERROR(COUNTA(C".(3+$k-1).",C".(9+$k-1).",C".(15+$k-1).",C".(21+$k-1).",C".(27+$k-1).")/".$nbResulta.",0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
	$sheet->setCellValue('D'.($i+$k+2),"=IFERROR(COUNTA(D".(3+$k-1).",D".(9+$k-1).",D".(15+$k-1).",D".(21+$k-1).",D".(27+$k-1).")/".$nbResulta.",0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
	$sheet->setCellValue('D'.($i+$k+3),"=IFERROR(COUNTA(E".(3+$k-1).",E".(9+$k-1).",E".(15+$k-1).",E".(21+$k-1).",E".(27+$k-1).")/".$nbResulta.",0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
	$sheet->setCellValue('D'.($i+$k+4),"=IFERROR(COUNTA(F".(3+$k-1).",F".(9+$k-1).",F".(15+$k-1).",F".(21+$k-1).",F".(27+$k-1).")/".$nbResulta.",0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
	
	$sheet->getStyle('D'.($i+$k+1).':F'.($i+$k+4))->getNumberFormat()->applyFromArray( array( 'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE));
	
	$sheet->getStyle('A'.($i+$k).':F'.($i+$k+4))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A'.($i+$k))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('A'.($i+$k).':F'.($i+$k+4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('D'.($i+$k+1).':F'.($i+$k+4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}

$i=$i+10;

$sheet->getColumnDimension('B')->setWidth(25);

$sheet->setCellValue('B'.($i),utf8_encode('Moyennes'));
$sheet->setCellValue('B'.($i + 1),utf8_encode('Moyenne de pts 1'));
$sheet->setCellValue('B'.($i + 2),utf8_encode('Moyenne de pts 2'));
$sheet->setCellValue('B'.($i + 3),utf8_encode('Moyenne de pts 3'));
$sheet->setCellValue('B'.($i + 4),utf8_encode('Moyenne de pts 4'));

$sheet->setCellValue('A'.($i + 1),utf8_encode('1'));
$sheet->setCellValue('A'.($i + 2),utf8_encode('2'));
$sheet->setCellValue('A'.($i + 3),utf8_encode('3'));
$sheet->setCellValue('A'.($i + 4),utf8_encode('4'));

$sheet->getStyle('B'.($i))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.($i+1).':B'.($i+4))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.($i+1).':B'.($i+4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$sheet->setCellValue('D'.($i),utf8_encode('Très Insuffisant'));
$sheet->setCellValue('E'.($i),utf8_encode('Insuffisant'));
$sheet->setCellValue('F'.($i),utf8_encode('Satisfaisant'));
$sheet->setCellValue('G'.($i),utf8_encode('Très safisfaisant'));

$sheet->setCellValue('D'.($i+1),"=IFERROR(COUNTA(C3,C4,C5,C9,C10,C11,C15,C16,C17,C21,C22,C23,C27,C28,C29),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
$sheet->setCellValue('E'.($i+1),"=IFERROR(COUNTA(D3,D4,D5,D9,D10,D11,D15,D16,D17,D21,D22,D23,D27,D28,D29),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
$sheet->setCellValue('F'.($i+1),"=IFERROR(COUNTA(E3,E4,E5,E9,E10,E11,E15,E16,E17,E21,E22,E23,E27,E28,E29),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
$sheet->setCellValue('G'.($i+1),"=IFERROR(COUNTA(F3,F4,F5,F9,F10,F11,F15,F16,F17,F21,F22,F23,F27,F28,F29),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);

$sheet->setCellValue('D'.($i+2),"=IFERROR(COUNTA(C6,C12,C18,C24,C30),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
$sheet->setCellValue('E'.($i+2),"=IFERROR(COUNTA(D6,D12,D18,D24,D30),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
$sheet->setCellValue('F'.($i+2),"=IFERROR(COUNTA(E6,E12,E18,E24,E30),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);
$sheet->setCellValue('G'.($i+2),"=IFERROR(COUNTA(F6,F12,F18,F24,F30),0)", PHPExcel_Cell_DataType::TYPE_FORMULA);



$sheet->getStyle('D'.($i).':G'.($i+2))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('D'.($i).':G'.($i+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Gammes_'.$_GET['MSN'].'.xlsx"'); 
header('Cache-Control: max-age=0'); 
$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$writer->save('php://output');
?>