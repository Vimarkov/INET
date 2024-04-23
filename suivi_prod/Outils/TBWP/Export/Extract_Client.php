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

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("Zone"));
$sheet->setCellValue('C1',utf8_encode("Zone Aircraft"));
$sheet->setCellValue('D1',utf8_encode("ATA"));
$sheet->setCellValue('E1',utf8_encode("N° OF"));
$sheet->setCellValue('F1',utf8_encode("N° FI"));
$sheet->setCellValue('G1',utf8_encode("Titre"));
$sheet->setCellValue('H1',utf8_encode("Travail à réaliser"));
$sheet->setCellValue('I1',utf8_encode("Date"));
$sheet->setCellValue('J1',utf8_encode("Vacation"));
$sheet->setCellValue('K1',utf8_encode("Priorité"));
$sheet->setCellValue('L1',utf8_encode("Urgence"));
$sheet->setCellValue('M1',utf8_encode("Statut"));
$sheet->setCellValue('N1',utf8_encode("Avancement"));
$sheet->setCellValue('O1',utf8_encode("Retour"));
$sheet->setCellValue('P1',utf8_encode("Commentaire"));

$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(10);
$sheet->getColumnDimension('J')->setWidth(10);
$sheet->getColumnDimension('K')->setWidth(10);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(10);
$sheet->getColumnDimension('N')->setWidth(15);
$sheet->getColumnDimension('O')->setWidth(20);
$sheet->getColumnDimension('P')->setWidth(20);

$sheet->getStyle('A1:P1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:P1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,";
$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="(SELECT sp_urgence.Libelle FROM sp_urgence WHERE sp_urgence.Id=sp_dossier.Id_Urgence) AS Urgence, ";
$req.="sp_dossier.Priorite,sp_dossier.Titre,sp_ficheintervention.NumFI, sp_ficheintervention.Vacation,sp_ficheintervention.Commentaire, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_ficheintervention.DateIntervention,sp_ficheintervention.TravailRealise,sp_dossier.Priorite,sp_dossier.SectionACP,sp_ficheintervention.Avancement ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE sp_ficheintervention.DateIntervention>'0001-01-01' AND sp_ficheintervention.Vacation<>'' AND ";
if($_SESSION['Extract_MSN2']<>""){
	$tab = explode(";",$_SESSION['Extract_MSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.MSN=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Zone2']<>""){
	$tab = explode(";",$_SESSION['Extract_Zone2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.Id_ZoneDeTravail=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Pole2']<>""){
	$tab = explode(";",$_SESSION['Extract_Pole2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Id_Pole=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Vacation2']<>""){
	$tab = explode(";",$_SESSION['Extract_Vacation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Vacation='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Urgence2']<>""){
	$tab = explode(";",$_SESSION['Extract_Urgence2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.Id_Urgence=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Statut2']<>""){
	$tab = explode(";",$_SESSION['Extract_Statut2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="(vide)"){$req.="sp_ficheintervention.Id_StatutPROD='' OR sp_ficheintervention.Id_StatutQUALITE='' OR ";}
			elseif($valeur=="TFS" || $valeur=="QARJ"){$req.="sp_ficheintervention.Id_StatutPROD='".$valeur."' OR ";}
			elseif($valeur=="TVS" || $valeur=="CERT"){$req.="sp_ficheintervention.Id_StatutQUALITE='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_SansDate2']=="oui"){
	$req.=" ( ";
	$req.="sp_ficheintervention.DateIntervention <= '0001-01-01' OR ";
}
if($_SESSION['Extract_Du2']<>"" || $_SESSION['Extract_Au2']<>""){
	$req.=" ( ";
	if($_SESSION['Extract_Du2']<>""){
		$req.="sp_ficheintervention.DateIntervention >= '". TrsfDate_($_SESSION['Extract_Du2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['Extract_Au2']<>""){
		$req.="sp_ficheintervention.DateIntervention <= '". TrsfDate_($_SESSION['Extract_Au2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
if($_SESSION['Extract_SansDate2']=="oui"){
	$req.=" ) ";
}
if($_SESSION['Extract_SansDate2']=="oui" || $_SESSION['Extract_Du2']<>"" || $_SESSION['Extract_Au2']<>""){
	$req.=" AND ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$Zone="";
		if(substr($row['SectionACP'],0,6)=="S11/12" || substr($row['SectionACP'],0,6)=="S13/14"){$Zone="PA";}
		elseif(substr($row['SectionACP'],0,6)=="S15/21" || substr($row['SectionACP'],0,6)=="S16/19"){$Zone="TC";}
		$sheet->setCellValue('B'.$ligne,utf8_encode($Zone));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Zone']));
		$reqATA="SELECT ATA,SousATA FROM sp_dossier_ata WHERE Id_Dossier=".$row['Id_Dossier'];
		$resultATA=mysqli_query($bdd,$reqATA);
		$nbATA=mysqli_num_rows($resultATA);
		$ATA="";
		if($nbATA>0){
			while($rowATA=mysqli_fetch_array($resultATA)){
				$ATA.=$rowATA['ATA']."_".$rowATA['SousATA']."\n";
			}
		}
		if($ATA<>""){$ATA=substr($ATA,0,-1);}
		$sheet->setCellValue('D'.$ligne,utf8_encode($ATA));
		$sheet->getStyle('D'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['NumFI']));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Titre']));
		$sheet->getStyle('G'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['TravailRealise']));
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['DateIntervention']));
		$Vacation="";
		if($row['Vacation']=="J"){$Vacation="Jour";}
		elseif($row['Vacation']=="S"){$Vacation="Soir";}
		if($row['Vacation']=="N"){$Vacation="Nuit";}
		if($row['Vacation']=="VSD"){$Vacation="VSD";}
		$sheet->setCellValue('J'.$ligne,utf8_encode($Vacation));
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		$sheet->setCellValue('K'.$ligne,utf8_encode($Priorite));
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['Urgence']));
		$statut="";
		$retour="";
		$avancement="";
		if($row['Id_StatutQUALITE']<>""){
			$statut=$row['Id_StatutQUALITE'];
			$retour=$row['RetourQUALITE'];
		}
		else{
			$statut=$row['Id_StatutPROD'];
			$retour=$row['RetourPROD'];
			if($row['Id_StatutPROD']=="TFS"){$avancement=$row['Avancement']."%";}
		}
		$sheet->setCellValue('M'.$ligne,utf8_encode($statut));
		$sheet->setCellValue('N'.$ligne,utf8_encode($avancement));
		$sheet->setCellValue('O'.$ligne,utf8_encode($retour));
		$sheet->getStyle('O'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('P'.$ligne,utf8_encode($row['Commentaire']));
		$sheet->getStyle('P'.$ligne)->getAlignment()->setWrapText(true);
		
		$sheet->getStyle('A'.$ligne.':P'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':P'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':P'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		if($statut=="CERT"){
			$sheet->getStyle('A'.$ligne.':P'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
		}
		elseif($statut=="QARJ" || $statut=="REWORK"){
			$sheet->getStyle('A'.$ligne.':P'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'92d050'))));
		}
		elseif($statut=="TVS"){
			$sheet->getStyle('A'.$ligne.':P'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
		}
		elseif($statut=="TFS"){
			$sheet->getStyle('A'.$ligne.':P'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'538dd5'))));
		}
		if($row['Priorite']=="1"){
			$sheet->getStyle('K'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'a7da4e'))));
		}
		elseif($row['Priorite']=="2"){
			$sheet->getStyle('K'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc20e'))));
		}
		else{
			$sheet->getStyle('K'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ed1c24'))));
		}
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Client.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Client.xlsx';
$writer->save('php://output');

?>